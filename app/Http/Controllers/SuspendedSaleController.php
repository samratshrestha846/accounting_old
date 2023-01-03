<?php

namespace App\Http\Controllers;

use App\Actions\SuspendedSale\DeleteSuspendedSaleAction;
use App\Http\Controllers\Controller;
use App\Models\SuspendedBill;
use Illuminate\Http\Request;

class SuspendedSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->user()->can('manage-suspended-bill'))
        {
            $suspendedSales = SuspendedBill::filters($request->all())
                                ->with('suspendedUser','customer','outlet')
                                ->withCount('suspendedItems')
                                ->userData()
                                ->latest()
                                ->paginate(10);
            return view('backend.suspended_sale.index', compact('suspendedSales'));
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
    
    public function destroy(Request $request, $id)
    {
        if($request->user()->can('manage-suspended-bill')) {
            $suspendedBill = SuspendedBill::findOrFail($id);
            (new DeleteSuspendedSaleAction())->execute($suspendedBill);

            return redirect()->route('suspendedsale.index')->with('success', 'Suspended Bill deleted successfully');
        }else {
            return view('backend.permission.permission');
        }
    }
}
