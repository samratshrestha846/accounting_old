<?php

namespace App\Http\Controllers\Hotel;

use App\Enums\ClientType;
use App\Enums\DiscountType;
use App\Enums\OrderItemStatus;
use App\Enums\PaymentType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Billingtype;
use App\Models\Category;
use App\Models\HotelOrder;
use App\Models\HotelOrderType;
use App\Models\Paymentmode;
use App\Models\PosSettings;
use App\Models\Quotationsetting;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use PDF;

use function App\NepaliCalender\datenep;

class PosOrderInvoiceController extends Controller
{
    protected $categoryAll;
    protected array $paymentTypes;
    protected $orderTypes;
    protected array $customerTypes;
    protected array $taxTypes;
    protected array $discountTypes;
    protected $taxes;
    protected PosSettings $posSetting;
    protected UserCompany $userCompany;
    protected string $engDate;
    protected string $nepDate;
    protected array $totalRecords;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['useroutlet']);
        $this->categoryAll = (new Category())->getAll();
        $this->paymentTypes = Paymentmode::select('id', 'payment_mode')->active()->get()->toArray();
        $this->orderTypes = (new HotelOrderType())->getAll();
        $this->customerTypes = ClientType::getAllValues();
        $this->taxTypes = TaxType::getAllValues();
        $this->discountTypes = [DiscountType::FIXED, DiscountType::PERCENTAGE];
        $this->taxes = (new Tax())->getAll();
        $this->posSetting = PosSettings::with('customer')->first();
        $this->engDate = date("Y-m-d");
        $this->nepDate = datenep($this->engDate);
        $this->totalRecords = [
            'total_pending' => DB::table('hotel_orders')->select('id')->where('status',OrderItemStatus::PENDING)->count(),
            'total_suspended' => DB::table('hotel_orders')->select('id')->where('status',OrderItemStatus::SUSPENDED)->count(),
            'total_today_order' => DB::table('hotel_orders')->select('id')->whereDate('order_at', now())->count(),
            'total_take_away' => HotelOrder::takeAway()->count(),
            'total_online_delivery' => HotelOrder::onlineDelivery()->count(),
            'total_table_order' => HotelOrder::tableOrder()->count(),
        ];
    }

    public function index(Request $request)
    {
        if ($request->user()->can('hotel-order-invoice')) {
            $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            $outlet =  auth()->user()->getSessionOutlet();


            return view('backend.hotel.pos.pos_order')->with([
                'paymentTypes' => $this->paymentTypes,
                'orderTypes' => $this->orderTypes,
                'customerTypes' => $this->customerTypes,
                'categories' => $this->categoryAll,
                'taxTypes' => $this->taxTypes,
                'discountTypes' => $this->discountTypes,
                'taxes' => $this->taxes,
                'showtaxrow' =>  $this->posSetting->show_tax ? true : false,
                'showdiscountrow' =>  $this->posSetting->show_discount ? true : false,
                'posSetting' => $this->posSetting,
                'nepDate' => $this->nepDate,
                'engDate' => $this->engDate,
                'userCompany' => $userCompany,
                'outlet' => $outlet,
                'totalRecords' => $this->totalRecords,
            ]);
        } else {
            return view('backend.permission.permission');
        }
    }

    public function edit(Request $request, HotelOrder $hotelOrder)
    {

        if ($request->user()->can('hotel-order-invoice')) {
            $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            return view('backend.hotel.pos.pos_order')->with([
                'paymentTypes' => $this->paymentTypes,
                'orderTypes' => $this->orderTypes,
                'customerTypes' => $this->customerTypes,
                'categories' => $this->categoryAll,
                'taxTypes' => $this->taxTypes,
                'discountTypes' => $this->discountTypes,
                'taxes' => $this->taxes,
                'showtaxrow' =>  $this->posSetting->show_tax ? true : false,
                'showdiscountrow' =>  $this->posSetting->show_discount ? true : false,
                'posSetting' => $this->posSetting,
                'nepDate' => $this->nepDate,
                'engDate' => $this->engDate,
                'userCompany' => $userCompany,
                'orderItem' => $hotelOrder->load(['order_items.food', 'customer', 'tables:id,name', 'billing.payment_infos', 'billing.user_entry']),
                'totalRecords' => $this->totalRecords,
            ]);
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * View order item billing invoice
     *
     * @return view
     */
    public function viewBillingInvoice(Request $request, Billing $billing)
    {
        if ($request->user()->can('hotel-order-invoice') || $request->user()->can('hotel-order-view')) {
            $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', auth()->id())->where('is_selected', 1)->first();
            return view('backend.hotel.pos.view_billing_invoice')->with([
                'userCompany' => $userCompany,
                'billing' => $billing->load('client', 'payment_infos', 'billingextras.food')
            ]);
        } else {
            return view('backend.permission.permission');
        }
    }


    /**
     * Print Kot order items
     */
    public function printKotOrderItem(Request $request,HotelOrder $hotelOrder)
    {
        if (!$request->user()->can('hotel-order-invoice')) {
            return view('backend.permission.permission');
        }
        $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

        return view('backend.hotel.print.kot_food_item')
            ->with([
                'nepDate' => $this->nepDate,
                'engDate' => $this->engDate,
                'userCompany' => $userCompany,
                'orderItem' => $hotelOrder->load('order_items.food', 'customer', 'table'),
            ]);
    }

    /**
     * Print order items invoice
     */
    public function printOrderItemInvoice(Request $request, HotelOrder $hotelOrder)
    {
        if (!$request->user()->can('hotel-order-invoice')) {
            return view('backend.permission.permission');
        }

        $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

        return view('backend.hotel.print.order_item_invoice')
            ->with([
                'nepDate' => $this->nepDate,
                'engDate' => $this->engDate,
                'userCompany' => $userCompany,
                'orderItem' => $hotelOrder->load('order_items.food', 'customer', 'table'),
            ]);
    }

    /**
     * Generate order item billing invoice
     *
     * @return view
     */
    public function greateBillingInvoice(Request $request, Billing $billing)
    {
        if ($request->user()->can('hotel-order-invoice')) {
            $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', auth()->id())->where('is_selected', 1)->first();
            return view('backend.hotel.pos.billing.pos_invoice')->with([
                'userCompany' => $userCompany,
                'billing' => $billing->load('client', 'payment_infos', 'billingextras.food')
            ]);
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Download order item billing invoice PDF
     *
     * @return view
     */

    public function greateBillingInvoicePdf(Request $request, Billing $billing)
    {
        if ($request->user()->can('hotel-order-invoice')) {
            $userCompany = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', auth()->id())->where('is_selected', 1)->first();
            // return view('backend.hotel.pos.billing.pos_invoice')->with([
            //     'userCompany' => $userCompany,
            //     'billing' => $billing->load('client','payment_infos','billingextras.food')
            // ]);
            $billing = $billing->load('client', 'payment_infos', 'billingextras.food');



            $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
            $setting = Setting::first();
            $quotationsetting = Quotationsetting::first();
            $currentcomp = UserCompany::with('company.districts', 'company.branches', 'company.provinces')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            $opciones_ssl = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );


            $img_path = 'uploads/' . $currentcomp->company->company_logo;
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
            $img_base_64 = base64_encode($data);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;


            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])
                ->setPaper('a4', 'portrait')
                ->loadView('backend.hotel.pos.billing.pos_invoice_pdf', compact('userCompany', 'billing', 'billing_type', 'setting', 'path_img', 'quotationsetting', 'currentcomp'));




            // $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])
            //     ->setPaper('a4', 'portrait')
            //     ->loadView('backend.billings.downloadbilling', compact('billing', 'billing_type', 'setting', 'path_img', 'quotationsetting', 'currentcomp'));
            $ref_no = $billing->reference_no;
            $predlcount = $billing->downloadcount;
            $newdownloadcount = $predlcount + 1;
            $billing->update([
                'downloadcount' => $newdownloadcount,
            ]);
            $billing->save();

            if ($billing->is_pos_data == 1 && $billing->outlet_id != null) {
                return $pdf->download('POS Bill.pdf');
            }
            return $pdf->download($ref_no . '.pdf');
        } else {
            return view('backend.permission.permission');
        }

    }
}
