<?php

namespace App\Http\Controllers\API;

use App\Actions\SuspendedSale\CreateSuspendedSaleAction;
use App\Actions\SuspendedSale\UpdateSuspendedSaleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSuspendSaleRequest;
use App\Http\Requests\UpdateSuspendSaleRequest;
use App\Http\Resources\SuspendedBillResource;
use App\Models\Outlet;
use App\Models\SuspendedBill;
use App\Models\SuspendedItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

class SuspendProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResource
    {
        $suspendedBills = QueryBuilder::for(SuspendedBill::class)
        ->allowedIncludes(['customer','suspendedUser'])
        ->filters($request->all());

        if($request['per_page']){
            $suspendedItems = $suspendedBills->paginate($request['per_page']);
        }else{
            $suspendedItems = $suspendedBills->get();
        }

        return SuspendedBillResource::collection($suspendedItems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSuspendSaleRequest $request)
    {
        $suspendedBill = (new CreateSuspendedSaleAction())->execute(auth()->user(), $request);

        return response()->json([
            'status' => true,
            'data' => $suspendedBill->load('suspendedItems'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $suspendedBill = SuspendedBill::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $suspendedBill->load('customer','suspendedItems'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSuspendSaleRequest $request, $id)
    {
        $suspendedBill = SuspendedBill::findOrFail($id);

        (new UpdateSuspendedSaleAction())->execute(auth()->user(), $suspendedBill, $request);

        return response()->json([
            'status' => true,
            'message' => 'Suspended Sale updated successfully'
        ]);
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

    public function cancleSuspendedBill($id)
    {
        $suspendedBill = SuspendedBill::findOrFail($id);

        $suspendedBill->cancleIt();

        return response()->json([
            'status' => true,
            'message' => 'Suspended Sale cancled successfully'
        ]);
    }
}
