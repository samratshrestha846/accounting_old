<?php

namespace App\Http\Controllers\API;

use App\Actions\UpdateUserPasswordAction;
use App\Exceptions\InvalidPasswordException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateController extends Controller
{
    public function __invoke()
    {
        $data = request()->validate([
            'current_password' => ['required'],
            'new_password' => ['required','min:6','different:current_password'],
        ]);

        try {
            (new UpdateUserPasswordAction())->execute(auth()->user(), $data['current_password'], $data['new_password']);
        } catch(InvalidPasswordException $e) {
            return $this->responseError($e->getMessage(), 401);
        }

        return $this->responseSuccessMessage("Password updatede successfully");
    }
}
