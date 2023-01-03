<?php

namespace App\Http\Controllers;

use App\Enums\ClientType;
use App\Enums\DiscountType;
use App\Enums\PaymentType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Billingtype;
use App\Models\Category;
use App\Models\FiscalYear;
use App\Models\PosSettings;
use App\Models\SuspendedBill;
use App\Models\Tax;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function App\NepaliCalender\datenep;

class PointOfSaleController extends Controller
{
    protected $categoryAll;
    protected array $paymentTypes;
    protected array $customerTypes;
    protected array $taxTypes;
    protected array $discountTypes;
    protected $taxes;
    protected PosSettings $posSetting;
    protected UserCompany $userCompany;
    protected string $engDate;
    protected string $nepDate;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['useroutlet']);
        $this->categoryAll = Category::select('id','category_name','category_id')->orderBy('in_order','asc')->get();
        $this->paymentTypes = PaymentType::getAllValues();
        $this->customerTypes = ClientType::getAllValues();
        $this->taxTypes = TaxType::getAllValues();
        $this->discountTypes = [DiscountType::FIXED, DiscountType::PERCENTAGE];
        $this->taxes = (new Tax())->getAll();
        $this->posSetting = PosSettings::with('customer')->first();
        $this->engDate = date("Y-m-d");
        $this->nepDate = datenep($this->engDate);
    }

    public function index(Request $request)
    {
        if ( $request->user()->can( 'manage-offer-setting' ) ) {
            $userCompany = UserCompany::with('company.districts','company.branches','company.provinces')->where('user_id',Auth::user()->id)->where('is_selected', 1)->first();
            $outlet =  auth()->user()->getSessionOutlet();

            if(!$outlet)
                return redirect()->route('outlet.index')->with('error', 'No outlets or pos created. Create a POS first.');

            return view('backend.pos.sale_pos')->with([
                'paymentTypes' => $this->paymentTypes,
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
            ]);
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function showSuspendedSale(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-suspended-bill' ) ) {
            $suspendedSale = SuspendedBill::with('customer','outlet')->findOrFail($id);
            $userCompany = UserCompany::with('company.districts','company.branches','company.provinces')->where('user_id',Auth::user()->id)->where('is_selected', 1)->first();
            $outlet = $suspendedSale->outlet;

            if(!auth()->user()->canAccessOutlet($outlet->id))
            {
                return redirect()->route('biller.index')->with('error', 'You are not authorized on this outlet.');
            }

            return view('backend.pos.sale_pos')->with([
                'paymentTypes' => $this->paymentTypes,
                'customerTypes' => $this->customerTypes,
                'categories' => $this->categoryAll,
                'suspendedSale' => $suspendedSale,
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
            ]);
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function switchOuletBranch(Request $request)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $request->validate([
                'outlet_id' => [
                    'required',
                    'exists:outlets,id'
                ],
            ]);

            if(!auth()->user()->canAccessOutlet($request['outlet_id'])){
                return redirect()->route('biller.index')->with('error', 'You are not authorized on this outlet.');
            }

            $request->session()->put('outlet', $request['outlet_id']);

            return redirect()->route('pos.index');
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function posSalesReport(Request $request)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $billing_type_id = 1;
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);
            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $billings = Billing::with('outlet')->pos()->userData()->paginate(10);
            return view('backend.pos.salesreport', compact('billings', 'fiscal_years', 'actual_year', 'billing_type_id', 'billingtype', 'current_fiscal_year'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * View the invoice billing
     *
    */
    public function viewInvoiceBilling(Request $request, Billing $billing)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $userCompany = UserCompany::with('company.districts','company.branches','company.provinces')->where('user_id',Auth::user()->id)->where('is_selected', 1)->first();
            return view('backend.pos.view_billing_invoice')->with([
                'userCompany' => $userCompany,
                'billing' => $billing->load('client','payment_infos','billingextras.product')
            ]);
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     *
     * Generate print billing invoice of pos
     *
     */
    public function generatePosInvoiceBilling(Request $request, Billing $billing)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $userCompany = UserCompany::with('company.districts','company.branches','company.provinces')->where('user_id',Auth::user()->id)->where('is_selected', 1)->first();
            return view('backend.pos.billing.pos_invoice')->with([
                'userCompany' => $userCompany,
                'billing' => $billing->load('client','payment_infos','billingextras.product')
            ]);
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
