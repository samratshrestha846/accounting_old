<?php

namespace App\Http\Controllers;

use App\Traits\CanAuthorizePermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, CanAuthorizePermission;

    public function responseOk(string $message, $data, $code = 200)
    {
        return response()->json([
            'error' => false,
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function responseSuccessData($data, $code = 200)
    {
        return response()->json([
            'error' => false,
            'status' => $code,
            'data' => $data
        ], $code);
    }

    public function responseSuccessMessage(string $message, $code = 200)
    {
        return response()->json([
            'error' => false,
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    public function responseError(string $message, $code = 500)
    {
        return response()->json([
            'error' => true,
            'status' => $code,
            'message' => $message,
        ], $code);
    }

}
