<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GodownResource;
use App\Models\Godown;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GodownController extends Controller
{
    public function index(Request $request)
    {
        $godowns = QueryBuilder::for(Godown::class)
        ->allowedIncludes([
            'godownproduct.product',
        ])
        ->filters($request->all())
        ->get();

        return $this->responseOk(
            $message = "Godown fetched successfully",
            GodownResource::collection($godowns)
        );
    }
}
