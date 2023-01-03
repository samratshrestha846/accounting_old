<?php
namespace App\Actions;

use App\Exceptions\InvalidPasswordException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordAction {

    public function execute(User $user, string $currentPassword, string $newPassword): bool
    {
        if(!Hash::check($currentPassword, $user->password))
            throw new InvalidPasswordException("The provided password does not match your current password.");

        return $user->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();
    }
}
