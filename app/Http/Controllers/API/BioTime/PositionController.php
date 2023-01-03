<?php

namespace App\Http\Controllers\API\BioTime;

use App\Actions\CreatePositionAction;
use App\FormDatas\PositionFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Get a list of Position
     *
     * @queryParam per_page integer The perpage number. Example: 10
     * @queryParam page integer The page number. Example: 1
     *
     * @responseFile  responses/Position/positions.get.json
     */
    public function index(Request $request)
    {
        $perPage = $request['per_page'];

        $positions = Position::query()->select('id','name');

        if($perPage) {
            $positions = $positions->paginate($perPage);
        } else {
            $positions = $positions->get();
        }

        return PositionResource::collection($positions);
    }

    /**
     * Create a Position
     *
     * @bodyParam  name string required The name of the position. Example: Software Department
     *
     * @responseFile  responses/Position/created.get.json
     */
    public function store(StorePositionRequest $request)
    {
        $positionFormData = new PositionFormData(
            $request->name,
            true
        );

        $position = (new CreatePositionAction)->execute($positionFormData);

        return $this->responseOk('Position created successfully', $position);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
