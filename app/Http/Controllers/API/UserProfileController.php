<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @OA\Get(
     *      path="/auth/me/profile",
     *      operationId="getAuthenticatedUserDetail",
     *      tags={"UserProfile"},
     *      summary="Get authenticated user profile detail",
     *      description="Returns user data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserProfile")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function myProfile()
    {
        return response()->json([
            'error' => false,
            'status' => 200,
            'data' => UserProfileResource::make(auth()->user()),
        ]);
    }
}
