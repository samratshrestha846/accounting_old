<?php

namespace App\Http\Controllers\API;

use App\Actions\UpdateUserPinNumberAction;
use App\Exceptions\InvalidPasswordException;
use App\Helpers\HashPinNumber;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserPinNumberController extends Controller
{
    protected HashPinNumber $hashPinNumber;

    public function __construct()
    {
        $this->hashPinNumber = new HashPinNumber();
    }

    public function checkPinNumber(Request $request)
    {
        $data = $request->validate([
            'pin_number' => ['required']
        ]);

        $user = auth()->user();

        if(!($user && $user->pin_number == $this->hashPinNumber->make($data['pin_number'])))
            return $this->responseError("The given pin_number is invalid", 401);

        return $this->responseSuccessMessage("Pin check successfull");
    }

    public function updatePinNumber(Request $request)
    {
        $data = $request->validate([
            'current_pinnumber' => ['required'],
            'new_pinnumber' => ['required','integer','digits_between:4,4','different:current_pinnumber'],
        ]);

        $user = auth()->user();

        try {
            (new UpdateUserPinNumberAction())->execute($user, $data['current_pinnumber'], $data['new_pinnumber']);
        } catch(InvalidPasswordException $e) {
            return $this->responseError($e->getMessage(), 401);
        }

        return $this->responseSuccessMessage("Pinnumber updated successfull");
    }
}
