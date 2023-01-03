<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Validation\UnauthorizedException;

trait CanAuthorizePermission {

    public function can($permissions): bool
    {

        $can = false;

        $user = auth()->user();

        if(is_array($permissions)) {
            foreach($permissions as $permission) {
                if($user && $user->can($permission)) {
                  $can = true;
                  break;
                }
            }
        } else {
            $can = $user && $user->can($permissions);
        }

        if(!$can) {
            throw new UnauthorizedException("You are unauthorized", 401);
        }

        return $can;
    }
}
