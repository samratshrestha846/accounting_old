<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="LoginRequest",
     *      tags={"Login"},
     *      summary="Login",
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfull",
     *          @OA\JsonContent(ref="#/components/schemas/LoginData")
     *       ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Login Attemp Fail",
     *      ),
     * )
     */
    public function login(Request $request)
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
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'error' => false,
            'status' => 200,
            'message' => 'Login successfull',
            'data' => [
                'token_type' => 'Bearer',
                'access_token' => $token,
                'outlets' => $user->getOutlets(),
            ],
        ]);
    }
}
