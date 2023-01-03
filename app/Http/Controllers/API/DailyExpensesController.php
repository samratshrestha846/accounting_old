<?php

namespace App\Http\Controllers\API;

use App\Actions\DailyExpenses\CreateDailyExpensesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDailyExpensesRequest;
use App\Http\Resources\DailyExpenseResource;
use App\Models\DailyExpenses;
use Illuminate\Http\Request;

class DailyExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request['per_page'];

        $dailyExpenses = DailyExpenses::query();

        if($perPage){
            return DailyExpenseResource::collection($dailyExpenses->paginate($perPage));
        }


        return $this->responseOk(
            'Daily Expenses list fetched successfully',
            DailyExpenseResource::collection($dailyExpenses->get())
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDailyExpensesRequest $request)
    {
        $dailyExpenses = (new CreateDailyExpensesAction())->execute($request);

        return $this->responseOk(
            "Daily expenses created successfully",
            DailyExpenseResource::make($dailyExpenses),
            201
        );
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
