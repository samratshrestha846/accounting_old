<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        return $this->responseOk(
            "Taxes fetched successfully",
            Tax::select('id','title','percent')->get(),
        );
    }
}
