<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SuspendedBill;
use App\Models\SuspendedItem;
use App\Repositories\SuspendedItemRepository;
use Illuminate\Http\Request;

class SuspendedBillingItemController extends Controller
{
    protected SuspendedItemRepository $suspendedItemRepository;

    public function __construct()
    {
        $this->suspendedItemRepository = new SuspendedItemRepository();
        $this->middleware('auth');
    }

    public function index(Request $request, $suspendedBillId)
    {
        if($request->user()->can('manage-suspended-bill'))
        {
            $suspendedBill = SuspendedBill::findOrFail($suspendedBillId);

            $suspendedItems = $this->suspendedItemRepository->getListBySuspendedBillIdAndOutletId($suspendedBill->id, $suspendedBill->outlet->id);

            return $this->responseOk(
                'Suspended Item fetched successfully',
                $suspendedItems
            );
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function destroy(Request $request, $id)
    {
        if($request->user()->can('manage-suspended-bill'))
        {
            SuspendedItem::findOrFail($id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Suspended Item deleted successfully'
            ]);
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
}
