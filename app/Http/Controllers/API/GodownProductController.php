<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Godown\ProductResource;
use App\Http\Resources\GodownProductResource;
use App\Mail\POSBillMail;
use App\Models\Billing;
use App\Models\Godown;
use App\Models\Product;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GodownProductController extends Controller
{
    public function getGodownsByProductId(Product $product)
    {
        return $this->responseOk(
            "Godown fetched successfully",
            $product->godowns()->get()
        );
    }

    public function getProductsByGodownId(Godown $godown)
    {
        return $this->responseOk(
            "Godown fetched successfully",
            ProductResource::collection(
                $godown->products()->with(['godownproduct' => function($q) use($godown){
                    return $q->with(['serialnumbers:id,serial_number,godown_product_id'])->where('godown_id', $godown->id)->first();
                }])->get()
            )
        );
    }

    public function sendBillInfoEmail(Request $request, $id)
    {
        $billing = Billing::findorFail($id);
        $userCompany = UserCompany::with('company.districts','company.branches','company.provinces')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

        Mail::to($request['email'])->send(new POSBillMail($userCompany, $billing));

        return $this->responseSuccessMessage("Mail sent successfully.");
    }
}
