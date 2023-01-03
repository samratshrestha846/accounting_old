<?php
namespace App\Actions;

use App\Exceptions\InvalidPasswordException;
use App\Helpers\HashPinNumber;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPinNumberAction {

    protected HashPinNumber $hashPinNumber;

    public function __construct()
    {
        $this->hashPinNumber = new HashPinNumber();
    }

    public function execute(User $user, string $currentPinnumber, string $newPinnumber): bool
    {
        if(!($user && $user->pin_number == $this->hashPinNumber->make($currentPinnumber)))
            throw new InvalidPasswordException("The provided pin_number does not match your current pin_number.");

        return $user->forceFill([
            'pin_number' => $this->hashPinNumber->make($newPinnumber),
        ])->save();
    }
}
