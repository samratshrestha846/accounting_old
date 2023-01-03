<?php

namespace App\Http\Controllers\API\BioTime;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if(!Auth::attempt($data)){
            return response()->json([
                'error' => true,
                'status' => 401,
                'message' => 'Login credential incorrect'
            ], 401);
        }

        $user = User::where('email', $data['email'])->first();


        if($user->IsSuperAdmin()){
            return response()->json([
                'error' => true,
                'status' => 401,
                'message' => 'You have no permission to login from this account in bio time'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'error' => false,
            'status' => 200,
            'message' => 'Login successfull',
            'data' => [
                'token_type' => 'Bearer',
                'access_token' => $token,
            ],
        ]);
    }
}
