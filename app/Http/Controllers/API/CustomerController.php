<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Actions\CreateCustomerAction;
use App\Http\Resources\CustomerResource;
use App\Models\Client;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->can(['view-customer','edit-customer','remove-customer']);

        $customers = Client::query()->filters($request->all());

        if($request['per_page']){
            $customers = $customers->paginate($request['per_page']);
            return CustomerResource::collection($customers);
        }else{
            $customers = $customers->get();
        }

        return response()->json([
            'error' => false,
            'status' => 200,
            'message' => "Customer Fetched Successfully",
            'data' => CustomerResource::collection($customers),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $request['dealer_type_id'] = 5;

        $customer = (new CreateCustomerAction())->execute($request->all());

        return response()->json([
            'error' => false,
            'status' => 200,
            'message' => "Customer Created Successfully",
            'data' => $customer,
        ], 200);
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
