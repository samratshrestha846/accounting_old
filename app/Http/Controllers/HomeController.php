<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\Billing;
use App\Models\BillingCredit;
use App\Models\Category;
use App\Models\Client;
use App\Models\ChildAccount;
use App\Models\DamagedProducts;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\JournalVouchers;
use App\Models\JournalExtra;
use App\Models\OnlinePayment;
use App\Models\OpeningBalance;
use Carbon\Carbon;
use App\Models\PaymentInfo;
use App\Models\Product;
use App\Models\ProductNotification;
use App\Models\Reconciliation;
use App\Models\SalesBills;
use App\Models\Service;
use App\Models\SubAccount;
use App\Models\TaxInfo;
use App\Models\UserCompany;
use App\Models\User;
// use App\Models\VatRefund;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function App\NepaliCalender\datenep;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // public function client_vendor_to_child(){
    //     $date = date("Y-m-d");
    //     $nepalidate = datenep($date);
    //     $exploded_date = explode("-", $nepalidate);

    //     $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        // $current_fiscal_year = FiscalYear::first();


    //     $clients = Client::where('child_account_id', null)->get();
    //     foreach($clients as $client){
    //         $related_child_account = ChildAccount::where('title', $client->name)->first();
    //         if($related_child_account == null){
    //             $child_account = ChildAccount::create([
    //                 'sub_account_id'=>'18',
    //                 'title'=>$client->name,
    //                 'slug'=>Str::slug($client->name),
    //             ]);
    //             OpeningBalance::create([
    //                 'child_account_id'=>$child_account['id'],
    //                 'fiscal_year_id'=>$current_fiscal_year->id,
    //                 'opening_balance'=>0,
    //                 'closing_balance'=>0,
    //             ]);
    //             $client->update([
    //                 'child_account_id'=> $child_account['id'],
    //             ]);
    //         }else{
    //             $client->update([
    //                 'child_account_id' => $related_child_account->id,
    //             ]);
    //             $opening_balance = OpeningBalance::where('child_account_id', $related_child_account->id)->where('fiscal_year_id', $current_fiscal_year->id)->first();
    //             if($opening_balance == null){
    //                 OpeningBalance::create([
    //                     'child_account_id'=>$related_child_account->id,
    //                     'fiscal_year_id'=>$current_fiscal_year->id,
    //                     'opening_balance'=>0,
    //                     'closing_balance'=>0,
    //                 ]);
    //             }
    //         }
    //     }

    //     $vendors = Vendor::where('child_account_id', null)->get();
    //     foreach($vendors as $vendor){
    //         $related_child_account = ChildAccount::where('title', $vendor->company_name)->first();
    //         if($related_child_account == null){
    //             $child_account = ChildAccount::create([
    //                 'sub_account_id'=>'18',
    //                 'title'=>$vendor->company_name,
    //                 'slug'=>Str::slug($vendor->company_name),
    //             ]);
    //             OpeningBalance::create([
    //                 'child_account_id'=>$child_account['id'],
    //                 'fiscal_year_id'=>$current_fiscal_year->id,
    //                 'opening_balance'=>0,
    //                 'closing_balance'=>0,
    //             ]);
    //             $vendor->update([
    //                 'child_account_id'=> $child_account['id'],
    //             ]);
    //         }else{
    //             $vendor->update([
    //                 'child_account_id' => $related_child_account->id,
    //             ]);
    //             $opening_balance = OpeningBalance::where('child_account_id', $related_child_account->id)->where('fiscal_year_id', $current_fiscal_year->id)->first();
    //             if($opening_balance == null){
    //                 OpeningBalance::create([
    //                     'child_account_id'=>$related_child_account->id,
    //                     'fiscal_year_id'=>$current_fiscal_year->id,
    //                     'opening_balance'=>0,
    //                     'closing_balance'=>0,
    //                 ]);
    //             }
    //         }

    //     }
    // }

    public function managestock(){
        $products = Product::all();
        foreach($products as $product){
            $godownproduct = GodownProduct::where('product_id', $product->id)->first();
            // dd($godownproduct);
            if(!$godownproduct == null){
                $godownproduct->update([
                    'stock'=>$product->total_stock,
                ]);
            }

        }
    }

    public function managechildopeningbalance(){
        $childaccounts = ChildAccount::with('this_year_opening_balance')->get();
        foreach($childaccounts as $childAccount){
            $openingbal = $childAccount->this_year_opening_balance->opening_balance ?? 0;
            $childAccount->update([
                'opening_balance' => $openingbal
            ]);
        }
    }


    public function childacAppendProductCode(){
        $products = Product::where('child_account_id','!=',null)->get();
        foreach($products as $product){
            ChildAccount::where('id',$product->child_account_id)->update([
                'title'=>$product->product_name.'('.$product->product_code.')',
                'slug'=>Str::slug($product->product_name.'('.$product->product_code.')'),
            ]);
        }
    }

    public function productchildacOpeningblnc(){
        $products = Product::all();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        foreach($products as $product){
            $balance = $product->total_cost * $product->total_stock;
            $openingBalance = OpeningBalance::where('child_account_id',$product->child_account_id)->first();
            if($openingBalance){
                $openingBalance->update(['opening_balance'=>$balance]);
            }else{
                OpeningBalance::create([
                    'child_account_id'=>$product->child_account_id,
                    'fiscal_year_id '=>$current_fiscal_year->id,
                    'opening_balance'=>$balance,
                    'closing_balance'=>$balance
                ]);
            }

        }

    }
    public function servicechildacOpeningblnc(){
        $services = Service::all();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        // dd($current_fiscal_year);
        foreach($services as $service){
            // dd($service);
            $openingBalance = OpeningBalance::where('child_account_id', $service->child_account_id)->first();
            if($openingBalance == null){
                OpeningBalance::create([
                    'child_account_id'=>$service->child_account_id,
                    'fiscal_year_id '=>$current_fiscal_year->id,
                    'opening_balance'=>0,
                    'closing_balance'=>0
                ]);
            }
        }
    }

    public function manageOnlinepaymentChildAc(){
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $bankAccount = SubAccount::where('slug', 'bank')->first();
        if($bankAccount == null){
            $newbankaccount = SubAccount::create([
                'title'=>'Bank',
                'slug'=>Str::slug('bank'),
                'account_id'=>1,
                'sub_account_id'=>1,
            ]);
            $newbankaccount->save();
            $bankaccount_id = $newbankaccount['id'];
        }else{
            $bankaccount_id = $bankAccount->id;
        }
        $onlinepayments = OnlinePayment::where('child_account_id',null)->get();
        foreach($onlinepayments as $online){
            $childAccount = ChildAccount::create([
                'title' => $online['name'].'('.$online['payment_id'].')',
                'slug' => Str::slug($online['name'].'('.$online['payment_id'].')'),
                'opening_balance' => 0,
                'sub_account_id' => $bankaccount_id,
            ]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => 0,
                'closing_balance' => 0
            ]);
        }
    }


    public function manageBankChildAc(){
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $bankAccount = SubAccount::where('slug', 'bank')->first();
            if($bankAccount == null){
                $newbankaccount = SubAccount::create([
                    'title'=>'Bank',
                    'slug'=>Str::slug('bank'),
                    'account_id'=>1,
                    'sub_account_id'=>1,
                ]);
                $newbankaccount->save();
                $bankaccount_id = $newbankaccount['id'];
            }else{
                $bankaccount_id = $bankAccount->id;
            }

            $banks = Bank::where('child_account_id',null)->get();
            foreach($banks as $bank){
                $account_type = BankAccountType::where('id', $bank['account_type_id'])->first();
                $childAccount = ChildAccount::create([
                    'title' => $bank['bank_name'].'('.$account_type->account_type_name.')',
                    'slug' => Str::slug($bank['bank_name']),
                    'opening_balance' => 0,
                    'sub_account_id' => $bankaccount_id,
                ]);
                $openingbalance = OpeningBalance::create([
                    'child_account_id' => $childAccount['id'],
                    'fiscal_year_id' => $current_fiscal_year->id,
                    'opening_balance' => 0,
                    'closing_balance' => 0
                ]);
            }
    }

    public function manageClientVendorChildAc(){
        $subaccount = SubAccount::where('slug', 'sundry-debtors')->first();
        if($subaccount == null){
            $newsubaccount = SubAccount::create([
                'title' => 'Sundry Debtors',
                'slug' => Str::slug('Sundry Debtors'),
                'account_id' => '1'
            ]);
            $newsubaccount->save();
        }
        $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $clients = Client::where('child_account_id',null)->get();
        foreach($clients as $client){
            $childAccount = ChildAccount::create([
                'title' => $client['name'],
                'slug' => Str::slug($client['name']),
                'opening_balance' => 0,
                'sub_account_id' => $subaccount_id,
            ]);
            $client->update(['child_account_id'=>$childAccount['id']]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => 0,
                'closing_balance' => 0
            ]);
        }

        $vendors = Vendor::where('child_account_id',null)->get();
        foreach($vendors as $vendor){
            $childAccount = ChildAccount::create([
                'title' => $vendor['company_name'],
                'slug' => Str::slug($vendor['company_name']),
                'opening_balance' => 0,
                'sub_account_id' => $subaccount_id,
            ]);
            $vendor->update(['child_account_id'=>$childAccount['id']]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => 0,
                'closing_balance' => 0
            ]);
        }

    }

     public function manageServiceChildAc(){
        $subaccount = SubAccount::where('slug', 'service')->first();
        $services = Service::where('child_account_id',null)->get();
        if($subaccount == null){
            $newsubaccount = SubAccount::create([
                'title' => 'Service',
                'slug' => Str::slug('Service'),
                'account_id' => '1',
                'sub_account_id' => '8'
            ]);
            $newsubaccount->save();
        }

        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        foreach($services as $service){
            $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;

            $childAccount = ChildAccount::create([
                'title' => $service['service_name'],
                'slug' => Str::slug($service['service_name']),
                'opening_balance' => 0,
                'sub_account_id' => $subaccount_id,
            ]);
            OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => 0,
                'closing_balance' => 0
            ]);
            $service->update([
                'child_account_id'=>$childAccount['id'],
            ]);
        }

     }

     public function manageProductChildAc(){

        $subacarray = array(
            [
                "account_id" => 1,
                "title"=>"Inventory",
                "slug"=>Str::slug("Inventory"),
            ],
            [
                "account_id" => 1,
                "title"=>"Sundry Debtors",
                "slug"=>Str::slug("Sundry Debtors"),
            ],
            [
                "account_id" => 2,
                "title"=>"Sundry Creditors",
                "slug"=>Str::slug("Sundry Creditors"),
            ]
            );

            foreach($subacarray as $key=>$subac){

                SubAccount::updateOrCreate(
                    ['slug'=>$subac['slug']],
                    ['account_id'=>$subac['account_id'],'title'=>$subac['title']]
                );
            }


        $childacarray =  array(
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "8",
                "title"=>"Sales Margin",
                "slug"=>Str::slug("Sales Margin"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "5",
                "title"=>"Tax Payable",
                "slug"=>Str::slug("Tax Payable"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "9",
                "title"=>"Discount Received",
                "slug"=>Str::slug("Discount Received"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "9",
                "title"=>"Incoming Tax",
                "slug"=>Str::slug("Incoming Tax"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "11",
                "title"=>"Service Charge",
                "slug"=>Str::slug("Service Charge"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "12",
                "title"=>"Discount",
                "slug"=>Str::slug("Discount"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "12",
                "title"=>"Outgoing Tax",
                "slug"=>Str::slug("Outgoing Tax"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "8",
                "title"=>"Service Income",
                "slug"=>Str::slug("Service Income"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "11",
                "title"=>"Shipping Charge",
                "slug"=>Str::slug("Shipping Charge"),
            ],

        );

        foreach($childacarray as $key=>$childac){
            ChildAccount::updateOrCreate(
                ['slug'=>$childac['slug']],
                ['company_id'=>$childac['company_id'],
                'branch_id'=>$childac['branch_id'],
                'sub_account_id'=>$childac['sub_account_id'],
                'title'=>$childac['title']
                ]
            );
        }


        $products = Product::where('child_account_id',null)->get();
        $subac = SubAccount::where('slug','inventory')->first();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        foreach($products as $product){
            $childac = ChildAccount::where('slug', Str::slug($product->product_name.'('.$product->product_code.')'))->count();

            if($childac == 0){
                $childAccount = ChildAccount::create([
                    'title' => $product->product_name.'('.$product->product_code.')',
                    'slug' => Str::slug($product->product_name.'('.$product->product_code.')'),
                    'opening_balance' => 0,
                    'sub_account_id' =>$subac->id,
                ]);
                $product->update(['child_account_id'=>$childAccount['id']]);
                OpeningBalance::create([
                    'child_account_id' => $childAccount['id'],
                    'fiscal_year_id' => $current_fiscal_year->id,
                    'opening_balance' => 0,
                    'closing_balance' => 0
                ]);
            }
        }




     }

    public function manage_old_billingcredit(){
        $duedateforoldbiling =  date('Y-m-d', strtotime(date('Y-m-d'). ' + 10 days'));

        $billings = Billing::all();
        foreach($billings as $billing){
            $paymentinfo = PaymentInfo::where('billing_id',$billing->id)->latest()->first();
            $billingcredit = BillingCredit::where('billing_id',$billing->id)->first();

            if(!empty($paymentinfo)){

                $credit_amount = $billing->grandtotal - $paymentinfo->total_paid_amount;
                if(empty($billingcredit) && $credit_amount > 0){
                    BillingCredit::create([
                        'billing_id'=>$billing->id,
                        'due_date_eng'=>$duedateforoldbiling,
                        'due_date_nep'=>'',
                        'credit_amount'=>$credit_amount,
                        'vendor_id'=>$billing->vendor_id,
                        'client_id'=>$billing->client_id,
                        'notified'=>0,
                        'is_read'=>0,
                        'billing_type_id'=>$billing->billing_type_id,
                    ]);
                }
             }

        }
        //for service
        // is_sale_service = 1
        $service_billings = SalesBills::all();
        foreach($service_billings as $billing){
            $credit_amount = $billing->grandtotal - $billing->payment_amount;
            BillingCredit::create([
                'billing_id'=>$billing->id,
                'due_date_eng'=>$duedateforoldbiling,
                'due_date_nep'=>'',
                'credit_amount'=>$credit_amount,
                'vendor_id'=>$billing->vendor_id,
                'client_id'=>$billing->client_id,
                'notified'=>0,
                'is_read'=>0,
                'billing_type_id'=>$billing->billing_type_id,
                'is_sale_service'=>1,
            ]);
        }

        return redirect()->route('home');
    }

    public function manage_subaccount_creditorOrDebitor(){
        $childaccounts = ChildAccount::with('this_year_opening_balance')->get();

        foreach($childaccounts as $child_account){
            $all_debit_amounts = [];
            $all_credit_amounts = [];
            $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q){
                $q->where('is_cancelled', 0)->where('status', 1);

            })
            ->where('child_account_id', $child_account->id)->get();


            foreach ($journal_extras as $jextra) {
                array_push($all_debit_amounts, $jextra->debitAmount);
                array_push($all_credit_amounts, $jextra->creditAmount);
            }
            $all_debit_sum = array_sum($all_debit_amounts);
            $all_credit_sum = array_sum($all_credit_amounts);
            $current_sub_account = SubAccount::where('id', $child_account->sub_account_id)->first();
            $sundry_creditor_account_id = SubAccount::where('slug', "sundry-creditors")->first()->id;
            $sundry_debtor_account_id = SubAccount::where('slug', "sundry-debtors")->first()->id;
            $opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;

            $closing_balance = $opening_balance + $all_debit_sum - $all_credit_sum;
            // echo $opening_balance.'<br>';
            $opening_balance = OpeningBalance::where('child_account_id',$child_account->id)->where('fiscal_year_id',1)->update([
                'closing_balance'=>$closing_balance,
            ]);
            if($current_sub_account->slug == "sundry-debtors" || $current_sub_account->slug == "sundry-creditors"){
                // echo $closing_balance.'<br>';
                if($closing_balance < 0){
                    $child_account->update([
                        'sub_account_id' => $sundry_creditor_account_id,
                    ]);
                }else{
                    $child_account->update([
                        'sub_account_id' => $sundry_debtor_account_id,
                    ]);
                }
            }

        }

    }

    public function productbilltojournals(){
        $billings = Billing::with('billingextras')->whereIn('billing_type_id', [1,2,5,6])->where('related_jv_no', null)->get();
        // dd($billings->billingextras);
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $current_fiscal_year->id)->count();
        if($journals == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }

        DB::beginTransaction();
        try{
            foreach($billings as $billing){
                $payment_amt = $billing->payment_infos_first->payment_amount;
                $payment_typ = $billing->payment_infos_first->payment_type;
                $jvno = "JV-".$jvnumber;
                $particulars = [];
                $quantity = [];
                $particular_cheque_no = [];
                $unit = [];
                $rate = [];
                $narration = [];
                $discountamt = [];
                $discounttype = [];
                $dtamt = [];
                $taxamt = [];
                $tax = [];
                $itemtax = [];
                $taxtype = [];
                $total = [];

                foreach($billing->billingextras as $extras){
                    array_push($particulars, $extras->particulars);
                    array_push($quantity, $extras->quantity);
                    array_push($particular_cheque_no, $extras->particular_cheque_no);
                    array_push($unit, $extras->unit);
                    array_push($rate, $extras->rate);
                    array_push($narration, $extras->narration);
                    array_push($discountamt, $extras->discountamt);
                    array_push($discounttype, $extras->discounttype);
                    array_push($dtamt, $extras->dtamt);
                    array_push($taxamt, $extras->taxamt);
                    array_push($tax, $extras->tax);
                    array_push($itemtax, $extras->itemtax);
                    array_push($taxtype, $extras->taxtype);
                    array_push($total, $extras->total);
                }
                $count = count($particulars);

                $taxsum = 0;
                $discountsum = $billing->discountamount;
                for($x=0; $x<$count; $x++)
                {
                    $taxsum += $itemtax[$x];
                    $discountsum += ($discountamt[$x] * $quantity[$x]);
                }
                $billing->update([
                    'related_jv_no' => $jvno
                ]);

                if($billing->billing_type_id == 1){
                    // For Journal Voucher

                    $journal_voucher = JournalVouchers::create([
                        'journal_voucher_no' => $jvno,
                        'entry_date_english' => $billing->eng_date,
                        'entry_date_nepali' => $billing->eng_date,
                        'fiscal_year_id' => $billing->fiscal_year_id,
                        'debitTotal' => $billing->grandtotal + $discountsum,
                        'creditTotal' => $billing->grandtotal + $discountsum,
                        'payment_method' => $billing->payment_method,
                        'receipt_payment' => $billing->receipt_payment ?? null,
                        'bank_id' => $billing->bank_id,
                        'online_portal_id' => $billing->online_portal_id,
                        'cheque_no' => $billing->cheque_no,
                        'customer_portal_id' => $billing->customer_portal_id,
                        'narration' => 'Being Goods Sold',
                        'is_cancelled'=>'0',
                        'status' =>$billing->status,
                        'vendor_id'=>null,
                        'entry_by'=> Auth::user()->id,
                        'approved_by'=> $billing->status == 1 ? Auth::user()->id : null,
                        'client_id'=>$billing->vendor_id,
                    ]);

                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    if($payment_typ == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($payment_typ == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$payment_amt,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>$payment_amt,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $payment_amt,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$payment_amt,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($payment_typ == 'unpaid'){
                        $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$billing->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    $totdiscount = $discountsum;
                    if($totdiscount > 0){
                        $discount_id = ChildAccount::where('slug', 'discount')->first()->id;
                        $discount_received_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_id,
                            'remarks'=>'Discount Given',
                            'debitAmount'=>$totdiscount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $discount_received_entry);
                    }


                    // Credit Entries
                    // Cash Paid
                    $margin = 0;
                    for($x=0; $x<$count; $x++){
                        $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code', 'total_cost')->first();
                        $cost_price = $particular_product->total_cost;
                        $total_cost_price = $cost_price * $quantity[$x];
                        $rate_per_qty = $rate[$x] * $quantity[$x];
                        $margin += $rate_per_qty - $total_cost_price;

                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>0,
                            'creditAmount'=>$total_cost_price,
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Margin
                    $margin_child_id = ChildAccount::where('slug', 'sales-margin')->first()->id;
                    $margin_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$margin_child_id,
                        'remarks'=>'Sales Total Margin',
                        'debitAmount'=>0,
                        'creditAmount'=>$margin,
                    ];
                    array_push($jv_extras, $margin_entry);
                    // Tax Entry
                    $tottax = $taxsum;
                    if($billing->taxamount > 0){
                        $incoming_tax_id = ChildAccount::where('slug', 'incoming-tax')->first()->id;
                        $incoming_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$incoming_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>0,
                            'creditAmount'=>$tottax,
                        ];
                        array_push($jv_extras, $incoming_tax_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->shipping,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }elseif($billing->billing_type_id == 2){
                    // For Journal Voucher
                    $journal_voucher = JournalVouchers::create([
                        'journal_voucher_no' => $jvno,
                        'entry_date_english' => $billing->eng_date,
                        'entry_date_nepali' => $billing->nep_date,
                        'fiscal_year_id' => $billing->fiscal_year_id,
                        'debitTotal' => $billing->grandtotal + $billing->discountamount,
                        'creditTotal' => $billing->grandtotal + $billing->discountamount,
                        'payment_method' => $billing->payment_method,
                        'receipt_payment' => $billing->receipt_payment ?? null,
                        'bank_id' => $billing->bank_id,
                        'online_portal_id' => $billing->online_portal_id,
                        'cheque_no' => $billing->cheque_no,
                        'customer_portal_id' => $billing->customer_portal_id,
                        'narration' => 'Being Goods Purchased',
                        'is_cancelled'=>'0',
                        'status' =>$billing->status,
                        'vendor_id'=>$billing->vendor_id,
                        'entry_by'=> Auth::user()->id,
                        'approved_by'=> $billing->status == 1 ? Auth::user()->id : null,
                        'client_id'=>null,
                    ]);

                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    for($x=0; $x<$count; $x++){
                        $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code')->first();
                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>$total[$x],
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Tax Entry
                    if($billing->taxamount > 0){
                        $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                        $outgoing_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$outgoing_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>$billing->taxamount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $outgoing_tax_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>$billing->shipping,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    // Credit Entries
                    // Cash Paid

                    if($payment_typ == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($payment_typ == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$payment_amt,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$payment_amt,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $payment_amt,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$payment_amt,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($payment_typ == 'unpaid'){
                        $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->grandtotal,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    if($billing->discountamount > 0){
                        $discount_received_id = ChildAccount::where('slug', 'discount-received')->first()->id;
                        $discount_received_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_received_id,
                            'remarks'=>'Discount Received',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->discountamount,
                        ];
                        array_push($jv_extras, $discount_received_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }elseif($billing->billing_type_id == 5){
                    // For Journal Voucher
                    $journal_voucher = JournalVouchers::create([
                        'journal_voucher_no' => $jvno,
                        'entry_date_english' => $billing->eng_date,
                        'entry_date_nepali' => $billing->nep_date,
                        'fiscal_year_id' => $billing->fiscal_year_id,
                        'debitTotal' => $billing->grandtotal + $billing->discountamount,
                        'creditTotal' => $billing->grandtotal + $billing->discountamount,
                        'payment_method' => $billing->payment_method,
                        'receipt_payment' => $billing->receipt_payment ?? null,
                        'bank_id' => $billing->bank_id,
                        'online_portal_id' => $billing->online_portal_id,
                        'cheque_no' => $billing->cheque_no,
                        'customer_portal_id' => $billing->customer_portal_id,
                        'narration' => 'Being Purchase Returned',
                        'is_cancelled'=>'0',
                        'status' =>$billing->status,
                        'vendor_id'=>$billing->vendor_id,
                        'entry_by'=> Auth::user()->id,
                        'approved_by'=> $billing->status == 1 ? Auth::user()->id : null,
                        'client_id'=>null,
                    ]);

                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    if($payment_typ == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($payment_typ == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$payment_amt,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>$payment_amt,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $payment_amt,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$payment_amt,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($payment_typ == 'unpaid'){
                        $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$billing->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    if($billing->discountamount > 0){
                        $discount_id = ChildAccount::where('slug', 'discount')->first()->id;
                        $discount_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_id,
                            'remarks'=>'Discount',
                            'debitAmount'=>$billing->discountamount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $discount_entry);
                    }


                    // Credit Entries
                    // Cash Paid
                    for($x=0; $x<$count; $x++){
                        $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code')->first();
                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>0,
                            'creditAmount'=>$total[$x],
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Tax Entry
                    if($billing->taxamount > 0){
                        $incoming_tax_id = ChildAccount::where('slug', 'incoming-tax')->first()->id;
                        $incoming_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$incoming_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->taxamount,
                        ];
                        array_push($jv_extras, $incoming_tax_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->shipping,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }
                elseif($billing->billing_type_id == 6){
                    // For Journal Voucher
                    $journal_voucher = JournalVouchers::create([
                        'journal_voucher_no' => $jvno,
                        'entry_date_english' => $billing->eng_date,
                        'entry_date_nepali' => $billing->nep_date,
                        'fiscal_year_id' => $billing->fiscal_year_id,
                        'debitTotal' => $billing->grandtotal + $discountsum,
                        'creditTotal' => $billing->grandtotal + $discountsum,
                        'payment_method' => $billing->payment_method,
                        'receipt_payment' => $billing->receipt_payment ?? null,
                        'bank_id' => $billing->bank_id,
                        'online_portal_id' => $billing->online_portal_id,
                        'cheque_no' => $billing->cheque_no,
                        'customer_portal_id' => $billing->customer_portal_id,
                        'narration' => 'Being Sales Returned',
                        'is_cancelled'=>'0',
                        'status' =>$billing->status,
                        'vendor_id'=>null,
                        'entry_by'=> Auth::user()->id,
                        'approved_by'=> $billing->status == 1 ? Auth::user()->id : null,
                        'client_id'=>$billing->client_id,
                    ]);

                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    $margin = 0;
                    for($x=0; $x<$count; $x++){
                        $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code', 'total_cost')->first();
                        $cost_price = $particular_product->total_cost;
                        $total_cost_price = $cost_price * $quantity[$x];
                        $rate_per_qty = $rate[$x] * $quantity[$x];
                        $margin += $rate_per_qty - $total_cost_price;

                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>$total_cost_price,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Margin
                    $margin_child_id = ChildAccount::where('slug', 'sales-margin')->first()->id;
                    $margin_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$margin_child_id,
                        'remarks'=>'Sales Total Margin',
                        'debitAmount'=>$margin,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $margin_entry);
                    // Tax Entry
                    $tottax = $taxsum;
                    if($billing->taxamount > 0){
                        $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                        $outgoing_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$outgoing_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>$tottax,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $outgoing_tax_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>$billing->shipping,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    // Credit Entries
                    // Cash Paid

                    if($payment_typ == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($payment_typ == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$payment_amt,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->bank_id)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->cheque_no ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$payment_amt,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->bank_id != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->bank_id,
                                        'cheque_no' => $billing->cheque_no,
                                        'receipt_payment' => $billing->receipt_payment,
                                        'amount' => $payment_amt,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->online_portal)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$payment_amt,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $payment_amt;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($payment_typ == 'unpaid'){
                        $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->grandtotal,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    $totdiscount = $discountsum;
                    if($totdiscount > 0){
                        $discount_received_id = ChildAccount::where('slug', 'discount-received')->first()->id;
                        $discount_received_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_received_id,
                            'remarks'=>'Discount Received',
                            'debitAmount'=>0,
                            'creditAmount'=>$totdiscount,
                        ];
                        array_push($jv_extras, $discount_received_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }
                $jvnumber += 1;
            }
            DB::commit();
            return redirect()->route('home')->with('success', 'Successfully Converted');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function servicebilltojournals(){
        $servicebillings = SalesBills::with('serviceSalesExtra')->whereIn('billing_type_id', [1,2,5,6])->where('related_jv_no', null)->get();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $current_fiscal_year->id)->count();
        if($journals == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }
        DB::beginTransaction();
        try{
            foreach($servicebillings as $billing){
                $jvno = "JV-".$jvnumber;
                $particulars = [];
                $quantity = [];
                $rate = [];
                $unit = [];
                $discountamt = [];
                $discounttype = [];
                $dtamt = [];
                $taxamt = [];
                $itemtax = [];
                $taxtype = [];
                $tax = [];
                $total = [];


                foreach($billing->serviceSalesExtra as $extras){
                    array_push($particulars, $extras->particulars);
                    array_push($quantity, $extras->quantity);
                    array_push($rate, $extras->rate);
                    array_push($unit, $extras->unit);
                    array_push($discountamt, $extras->discountamt);
                    array_push($discounttype, $extras->discounttype);
                    array_push($dtamt, $extras->dtamt);
                    array_push($taxamt, $extras->taxamt);
                    array_push($itemtax, $extras->itemtax);
                    array_push($taxtype, $extras->taxtype);
                    array_push($tax, $extras->tax);
                    array_push($total, $extras->total);
                }
                $count = count($particulars);

                $taxsum = 0;
                if($billing->alldiscounttype == 'percent'){
                    $discountsum = $billing->servicediscountamount;
                }elseif($billing->alldiscounttype == 'fixed'){
                    $discountsum = $billing->servicediscountamount;
                }
                for($x = 0; $x < $count; $x++)
                {
                    $taxsum += $itemtax[$x];
                    $discountsum += ($discountamt[$x] * $quantity[$x]);
                }
                $tottax = $taxsum == 0 ? $billing->taxamount :  $taxsum;


                $billing->update([
                    'related_jv_no' => $jvno,
                ]);

                $journal_voucher = JournalVouchers::create([
                    'journal_voucher_no' => $jvno,
                    'entry_date_english' => $billing->eng_date,
                    'entry_date_nepali' => $billing->nep_date,
                    'fiscal_year_id' => $billing->fiscal_year_id,
                    'debitTotal' => $billing->grandtotal + $discountsum,
                    'creditTotal' => $billing->grandtotal + $discountsum,
                    'payment_method' => $billing->payment_method,
                    'receipt_payment' => null,
                    'bank_id' => $billing->getPaymentBankId,
                    'online_portal_id' => $billing->getOnlinePortalId,
                    'cheque_no' => $billing->getChequeNo,
                    'customer_portal_id' => $billing->getCustomerPortalId,
                    'narration' => 'Being Services Purchased',
                    'is_cancelled'=>'0',
                    'status' =>$billing->status,
                    'vendor_id'=>$billing->vendor_id,
                    'entry_by'=> Auth::user()->id,
                    'approved_by'=> $billing->status == 1 ? Auth::user()->id : null,
                    'client_id'=> $billing->client_id ? $billing->client_id : null,
                ]);

                if($billing->billing_type_id == 2){
                    $jv_extras = [];
                    for($x=0; $x<$count; $x++){
                        $particular_product = Service::where('id', $particulars[$x])->select('child_account_id', 'service_name', 'service_code', 'cost_price')->first();
                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->service_name . '('. $particular_product->service_code .')';
                        // $service_toti_discount = $discountamt[$x] * $quantity[$x];
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>$rate[$x] * $quantity[$x],
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Tax Entry
                    if($tottax > 0){
                        $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                        $outgoing_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$outgoing_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>$tottax,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $outgoing_tax_entry);
                    }

                    // Service Charge
                    if($billing->service_charge > 0){
                        $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                        $service_charge_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$service_charge_id,
                            'remarks'=>'Service Charge',
                            'debitAmount'=>$billing->service_charge,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $service_charge_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>$billing->shipping,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    // Credit Entries
                    // Cash Paid

                    if($billing->payment_type == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($billing->payment_type == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->payment_amount,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->payment_amount,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->payment_amount,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->payment_amount,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($billing->payment_type == 'unpaid'){
                        $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->grandtotal,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    $totdiscount = $discountsum;
                    if($totdiscount > 0){
                        $discount_received_id = ChildAccount::where('slug', 'discount-received')->first()->id;
                        $discount_received_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_received_id,
                            'remarks'=>'Discount Received',
                            'debitAmount'=>0,
                            'creditAmount'=>$totdiscount,
                        ];
                        array_push($jv_extras, $discount_received_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }elseif($billing->billing_type_id == 1){

                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    if($billing->payment_type == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($billing->payment_type == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->payment_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>$billing->payment_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->payment_amount,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->payment_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($billing->payment_type == 'unpaid'){
                        $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$billing->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    $totdiscount = $discountsum;
                    if($totdiscount > 0){
                        $discount_received_id = ChildAccount::where('slug', 'discount')->first()->id;
                        $discount_received_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_received_id,
                            'remarks'=>'Discount',
                            'debitAmount'=>$totdiscount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $discount_received_entry);
                    }
                    // Credit Entries
                    // Cash Paid
                    $margin = 0;
                    for($x=0; $x<$count; $x++){
                        $particular_product = Service::where('id', $particulars[$x])->select('child_account_id', 'service_name', 'service_code', 'cost_price')->first();
                        $cost_price = $particular_product->cost_price;
                        $total_cost_price = $cost_price * $quantity[$x];
                        $rate_per_qty = $rate[$x] * $quantity[$x];
                        // $margin += $rate_per_qty - $total_cost_price;

                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->service_name . '('. $particular_product->service_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>0,
                            'creditAmount'=>$rate_per_qty,
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Margin
                    // $margin_child_id = ChildAccount::where('slug', 'sales-margin')->first()->id;
                    // $margin_entry = [
                    //     'journal_voucher_id'=>$journal_voucher['id'],
                    //     'child_account_id'=>$margin_child_id,
                    //     'remarks'=>'Sales Total Margin',
                    //     'debitAmount'=>0,
                    //     'creditAmount'=>$margin,
                    // ];
                    // array_push($jv_extras, $margin_entry);
                    // Tax Entry
                    if($tottax > 0){
                        $incoming_tax_id = ChildAccount::where('slug', 'incoming-tax')->first()->id;
                        $incoming_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$incoming_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>0,
                            'creditAmount'=>$tottax,
                        ];
                        array_push($jv_extras, $incoming_tax_entry);
                    }

                    // Service Charge
                    if($billing->service_charge > 0){
                        $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                        $service_charge_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$service_charge_id,
                            'remarks'=>'Service Charge',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->service_charge,
                        ];
                        array_push($jv_extras, $service_charge_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->shipping,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }
                    // dd($jv_extras);
                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }elseif($billing->billing_type_id == 5){
                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    if($billing->payment_type == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->grandtotal,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($billing->payment_type == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->payment_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>$billing->payment_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->payment_amount,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$billing->payment_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$vendor_child_id,
                                'remarks'=>'',
                                'debitAmount'=>$due_amount,
                                'creditAmount'=>0,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($billing->payment_type == 'unpaid'){
                        $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$billing->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    $totdiscount = $discountsum;
                    if($totdiscount > 0){
                        $discount_id = ChildAccount::where('slug', 'discount')->first()->id;
                        $discount_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_id,
                            'remarks'=>'Discount',
                            'debitAmount'=>$totdiscount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $discount_entry);
                    }
                    // Credit Entries
                    // Cash Paid

                    for($x=0; $x<$count; $x++){
                        $particular_product = Service::where('id', $particulars[$x])->select('child_account_id', 'service_name', 'service_code')->first();
                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->service_name . '('. $particular_product->service_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>0,
                            'creditAmount'=>$rate[$x] * $quantity[$x],
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Tax Entry
                    if($tottax > 0){
                        $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                        $outgoing_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$outgoing_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>0,
                            'creditAmount'=>$tottax,
                        ];
                        array_push($jv_extras, $outgoing_tax_entry);
                    }

                    // Service Charge
                    if($billing->service_charge > 0){
                        $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                        $service_charge_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$service_charge_id,
                            'remarks'=>'Service Charge',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->service_charge,
                        ];
                        array_push($jv_extras, $service_charge_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->shipping,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }elseif($billing->billing_type_id == 6){
                    // Getting Child Account Id of every Particulars
                    // Debit Entries
                    $jv_extras = [];
                    $margin = 0;
                    for($x=0; $x<$count; $x++){
                        $particular_product = Service::where('id', $particulars[$x])->select('child_account_id', 'service_name', 'service_code', 'cost_price')->first();
                        $cost_price = $particular_product->cost_price;
                        $total_cost_price = $cost_price * $quantity[$x];
                        $rate_per_qty = $rate[$x] * $quantity[$x];
                        // $margin += $rate_per_qty - $total_cost_price;

                        $particular_child_account_id = $particular_product->child_account_id;
                        $remark = $particular_product->service_name . '('. $particular_product->service_code .')';
                        $particular_jv_extras = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$particular_child_account_id,
                            'remarks'=>$remark,
                            'debitAmount'=>$rate_per_qty,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $particular_jv_extras);
                    }
                    // Margin
                    // $margin_child_id = ChildAccount::where('slug', 'sales-margin')->first()->id;
                    // $margin_entry = [
                    //     'journal_voucher_id'=>$journal_voucher['id'],
                    //     'child_account_id'=>$margin_child_id,
                    //     'remarks'=>'Sales Total Margin',
                    //     'debitAmount'=>$margin,
                    //     'creditAmount'=>0,
                    // ];
                    // array_push($jv_extras, $margin_entry);
                    // Tax Entry
                    if($tottax > 0){
                        $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                        $outgoing_tax_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$outgoing_tax_id,
                            'remarks'=>'Tax',
                            'debitAmount'=>$tottax,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $outgoing_tax_entry);
                    }

                    // Service Charge
                    if($billing->service_charge > 0){
                        $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                        $service_charge_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$service_charge_id,
                            'remarks'=>'Service Charge',
                            'debitAmount'=>$billing->service_charge,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $service_charge_entry);
                    }


                    // Shipping Entry
                    if($billing->shipping > 0){
                        $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                        $shipping_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$shipping_id,
                            'remarks'=>'Shipping Cost',
                            'debitAmount'=>$billing->shipping,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $shipping_entry);
                    }

                    // Credit Entries
                    // Cash Paid

                    if($billing->payment_type == 'paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $cash_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $bank_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $bank_entry);
                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->grandtotal,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->grandtotal,
                            ];
                            array_push($jv_extras, $online_portal);
                        }
                    }elseif($billing->payment_type == 'partially_paid'){
                        if($billing->payment_method == 1){
                            $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$cash_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->payment_amount,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }elseif($billing->payment_method == 2 || $billing->payment_method == 3){
                            $bank_child_id = Bank::where('id', $billing->getPaymentBankId)->first()->child_account_id;
                            $cash_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$bank_child_id,
                                'remarks'=>$billing->getChequeNo ?? '',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->payment_amount,
                            ];
                            array_push($jv_extras, $cash_entry);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);

                            if($billing->payment_method == 2){
                                if($billing->getPaymentBankId != null)
                                {
                                    Reconciliation::create([
                                        'jv_id' => $journal_voucher['id'],
                                        'bank_id' => $billing->getPaymentBankId,
                                        'cheque_no' => $billing->getChequeNo,
                                        'receipt_payment' => null,
                                        'amount' => $billing->payment_amount,
                                        'cheque_entry_date' => $billing->nep_date,
                                        'vendor_id' => $billing->vendor_id,
                                        'client_id'=>$billing->client_id ?? null,
                                        'other_receipt' => '-'
                                    ]);
                                }
                            }
                        }elseif($billing->payment_method == 4){
                            $online_portal_child_id = OnlinePayment::where('id', $billing->getCustomerPortalId)->first()->child_account_id;
                            $online_portal = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$online_portal_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$billing->payment_amount,
                            ];
                            array_push($jv_extras, $online_portal);

                            $due_amount = $billing->grandtotal - $billing->payment_amount;

                            $client_child_id = Client::where('id', $billing->client_id)->first()->child_account_id;
                            $due_entry = [
                                'journal_voucher_id'=>$journal_voucher['id'],
                                'child_account_id'=>$client_child_id,
                                'remarks'=>'',
                                'debitAmount'=>0,
                                'creditAmount'=>$due_amount,
                            ];
                            array_push($jv_extras, $due_entry);
                        }
                    }elseif($billing->payment_type == 'unpaid'){
                        $vendor_child_id = Vendor::where('id', $billing->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$billing->grandtotal,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                    // Discount Taken
                    $totdiscount = $discountsum;
                    if($totdiscount > 0){
                        $discount_received_id = ChildAccount::where('slug', 'discount-received')->first()->id;
                        $discount_received_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$discount_received_id,
                            'remarks'=>'Discount Received',
                            'debitAmount'=>0,
                            'creditAmount'=>$totdiscount,
                        ];
                        array_push($jv_extras, $discount_received_entry);
                    }

                    foreach($jv_extras as $key => $jv_extra){
                        JournalExtra::create($jv_extra);
                        if($billing->status == 1){
                            $this->openingbalance($jv_extra['child_account_id'], $billing->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                        }
                    }
                }
                $jvnumber+=1;
            }
            DB::commit();
            return redirect()->route('home')->with('success', 'Successfully Converted');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function openingbalance($childaccounts, $fiscal_year_id, $debitAmounts, $creditAmounts){
        $opening_balance = OpeningBalance::where('child_account_id', $childaccounts)->where('fiscal_year_id', $fiscal_year_id)->first();
        if($opening_balance == null){
            $closing = $debitAmounts - $creditAmounts;
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childaccounts,
                'fiscal_year_id' => $fiscal_year_id,
                'opening_balance' => 0,
                'closing_balance' => $closing,
            ]);
            $openingbalance->save();
        }else{
            $closing = $opening_balance->closing_balance + $debitAmounts - $creditAmounts;
            $opening_balance->update([
                'closing_balance' => $closing,
            ]);
        }
        $child_account = ChildAccount::where('id', $childaccounts)->first();
        $current_sub_account = SubAccount::where('id', $child_account->sub_account_id)->first();
        $sundry_creditor_account_id = SubAccount::where('slug', "sundry-creditors")->first()->id;
        $sundry_debtor_account_id = SubAccount::where('slug', "sundry-debtors")->first()->id;
        if($current_sub_account->slug == "sundry-debtors" || $current_sub_account->slug == "sundry-creditors"){
            if($closing < 0){
                $child_account->update([
                    'sub_account_id' => $sundry_creditor_account_id,
                ]);
            }else{
                $child_account->update([
                    'sub_account_id' => $sundry_debtor_account_id,
                ]);
            }
        }
    }


    public function index(Request $request)
    {
        $user = Auth::user()->id;
        $usercompanies = UserCompany::where('user_id', $user)->get();
        $currentcomp = UserCompany::where('user_id', $user)
            ->where('is_selected', 1)
            ->first();
        // $billingcreditNotification = BillingCredit::leftJoin('clients as clt', 'clt.id', '=', 'billing_credits.client_id')
        //                             ->leftjoin('vendors', 'vnd.id', '=', 'billing_credits.vendor_id')
                                    // ->leftJoin('billings as billing', 'billing.id', '=', 'billing_credits.billing_id')
                                    // ->select(['clt.name as cltname', 'vnd.company_name as vndname', 'billing.reference_no as ref_no', 'billing.company_id as cid', 'billing.branch_id as bid'])
                                    // ->where('cid', $currentcomp->company_id)
                                    // ->where('bid', $currentcomp->branch_id)
                                    // ->get();
        // $billingcreditNotification = BillingCredit::leftJoin('clients', function($query) use($currentcomp){
        //                             $query->on('billing_credits.client_id', '=', '')
        //                             });
        // dd($billingcreditNotification);
        // $cashinhand = ChildAccount::with('openingbalance')->where('slug','cash-in-hand')->get();
        $cashinhand = ChildAccount::leftJoin('opening_balances as ob','ob.child_account_id','=','child_accounts.id')
                                    ->where('slug','cash-in-hand')->sum('closing_balance');

        $cashinbank = SubAccount::leftJoin('child_accounts as ca','ca.sub_account_id','=','sub_accounts.id')
                                ->leftJoin('opening_balances as ob','ob.child_account_id','=','ca.id')
                                ->where('sub_accounts.slug','bank')
                                ->sum('closing_balance');

        //  foreach($cashinbank as $cashbank){

        // }


        // Product Expiry Notification
        // $godownproducts = GodownProduct::with('expiryproducts')->get();
        $expiredproducts = Product::where('expiry_date', '=', Carbon::now()->subDays(1)->toDateString())->get();



        $expiryproducts = Product::where('expiry_date', '=', Carbon::now()->subDays(-10)->toDateString())->get();//for days greater

        foreach(Godown::all() as $godown){
            foreach($expiryproducts as $product){
                $gdproduct = GodownProduct::where('product_id', $product->id)->where('godown_id', $godown->id)->count();

                if($gdproduct > 0){//check if product available in godown
                    $productnotis = ProductNotification::where('product_id', $product->id)->where('godown_id', $godown->id)->where('noti_type', 'expiry')->count();

                    if($productnotis == 0){//check if notification is not added
                        $productnotification = ProductNotification::create([
                            'product_id' => $product->id,
                            'godown_id' => $godown->id,
                            'noti_type' => 'expiry',
                            'status' => 0,
                            'read_at' => null,
                            'read_by' => null,
                        ]);
                    }
                }
            }

            foreach($expiredproducts as $product){
                $gdproduct = GodownProduct::where('product_id', $product->id)->where('godown_id', $godown->id)->count();
                // dd($gdproduct);
                if($gdproduct > 0){//check if product available in godown
                    $productnotis = ProductNotification::where('product_id', $product->id)->where('godown_id', $godown->id)->where('noti_type', 'expired')->count();
                    if($productnotis == 0){//check if notification is not added
                        $productnotification = ProductNotification::create([
                            'product_id' => $product->id,
                            'godown_id' => $godown->id,
                            'noti_type' => 'expired',
                            'status' => 0,
                            'read_at' => null,
                            'read_by' => null,
                        ]);
                    }
                }
            }
        }


        // dd($godownproducts);
        // foreach($godownproducts as $godownproduct){
        //     $product = $godownproduct->product;

        //     if(!$product->expiry_date == null){
        //         $thisday = strtotime(date('Y-m-d'));
        //         $expirydate = strtotime($product->expiry_date);
        //         $secs = $expirydate - $thisday;
        //         $days = $secs / 86400;
        //         if($days <= 10 && $days > 0){
        //             $productnotis = ProductNotification::where('product_id', $product->product_id)->where('godown_id', $godownproduct->godown_id)->where('noti_type', 'expiry')->count();
        //             if(!$productnotis == 0){
        //                 $productnotification = ProductNotification::create([
        //                     'product_id' => $godownproduct->product_id,
        //                     'godown_id' => $godownproduct->godown_id,
        //                     'noti_type' => 'expiry',
        //                     'status' => 0,
        //                     'read_at' => null,
        //                     'read_by' => null,
        //                 ]);
        //                 $productnotification->save();
        //             }
        //         }elseif($days <= 0){
        //             $productnotis = ProductNotification::where('product_id', $product->product_id)->where('godown_id', $godownproduct->godown_id)->where('noti_type', 'expired')->count();
        //             if(!$productnotis == 0){
        //                 $productnotification = ProductNotification::create([
        //                     'product_id' => $godownproduct->product_id,
        //                     'godown_id' => $godownproduct->godown_id,
        //                     'noti_type' => 'expired',
        //                     'status' => 0,
        //                     'read_at' => null,
        //                     'read_by' => null,
        //                 ]);
        //                 $productnotification->save();
        //             }
        //         }
        //     }
        // }

        //Generate New Fiscal Year
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        if ($exploded_date[1] > 3) {
            $new_fiscal_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            $existing_fiscal_year = FiscalYear::where('fiscal_year', $new_fiscal_year)->first();

            if(!$existing_fiscal_year) {
                $fiscal_year = FiscalYear::create([
                    'fiscal_year' => $new_fiscal_year
                ]);
                $fiscal_year->save();
            }

             //Generate Vatrefund
            //  $fiscalyearforvat = $fiscal_year ?? $existing_fiscal_year;
            // $vatrefund = VatRefund::where('fiscal_year','LIKE',$exploded_date[0].'/'.'%')->first();

            // if($vatrefund){
            //     $total_amount = Billing::latest()->with('billing_types')->with('suppliers')->where('fiscal_year_id',$vatrefund->fiscal_year_id)->where('billing_type_id', 1)->where('status', '1')->where('is_cancelled', '0')->where('vat_refundable', '>', '0')->sum('vat_refundable');
            //     $fiscal_year = FiscalYear::find($vatrefund->fiscal_year_id);
            //     $updatedata = array(
            //         'amount'=>$total_amount,
            //         'total_amount'=>$total_amount + $vatrefund->due,

            //     );
            //     $vatrefund->update($updatedata);
            // }else{
            //     $previousvatrefund = VatRefund::where('fiscal_year','LIKE',$exploded_date[0] - 1 .'/'.'%')->first();
            //     if($previousvatrefund){
            //         $due = ($previousvatrefund->refunded == 0) ? $previousvatrefund->total_amount : 0;

            //     }else{
            //         $due=0;
            //     }
            //     VatRefund::create([
            //         'fiscal_year_id'=>$fiscalyearforvat->id,
            //         'fiscal_year'=>$fiscalyearforvat->fiscal_year,
            //         'amount'=>0,
            //         'due'=>$due,
            //         'total_amount'=>$due,
            //         'refunded'=>0
            //     ]);
            // }

        }


        //Generate TaxInfo for New Month
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

        $nepdate = (int)$exploded_date[1] - 1;
        $nepmonth = $monthname[$nepdate];

        $currentfiscalyear = FiscalYear::latest()->first();
        $taxdetail = TaxInfo::latest()->where('fiscal_year', $currentfiscalyear->fiscal_year)->where('nep_month', $nepmonth)->first();
        if($taxdetail == null){
            $unpaidtaxes = Taxinfo::where('is_paid', 0)->get();
            $unpaids = [];
            foreach($unpaidtaxes as $unpaidtax){
                array_push($unpaids, $unpaidtax->total_tax);
            }
            $duetax = array_sum($unpaids);
            $taxinfo = TaxInfo::create([
                'fiscal_year' => $currentfiscalyear->fiscal_year,
                'nep_month' => $nepmonth,
                'purchase_tax' => 0,
                'sales_tax' => 0,
                'purchasereturn_tax' => 0,
                'salesreturn_tax' => 0,
                'total_tax' => 0,
                'is_paid' => 0,
                'due' => $duetax,
            ]);

            $taxinfo->save();
        }

        $date_today = date('Y-m-d');
        $date_in_nepali = datenep($date_today);
        $exploded_date = explode("-", $date_in_nepali);

        if ($exploded_date[1] < 4) {
            $previous_year = $exploded_date[0] - 1;
            $fiscal_year = $previous_year.'/'.$exploded_date[0];
        } else {
            $next_year = $exploded_date[0] + 1;
            $fiscal_year = $exploded_date[0].'/'.$next_year;
        }

        $current_fiscal_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();
        $total_journals = JournalVouchers::where('fiscal_year_id', $current_fiscal_year->id)->count();
        $approved_journals = JournalVouchers::where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->count();
        $unapproved_journals = JournalVouchers::where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->count();
        $cancelled_journals = JournalVouchers::where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->count();

        //Product Report
        $latest_products = Product::latest()->take(10)->get();
        $products = Product::count();
        $damagedProducts = DamagedProducts::count();
        $product_categories = Category::count();
        $godowns = Godown::count();

        //Billing Report
        //Sales Report
        $billings = Billing::all();
        $totsalesbillamt = $billings->where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)
                        // ->select('grandtotal')
                        ->sum('grandtotal');
        $salesbillcount = $billings->where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)->count();
        // $totsalesbillamt = 0;
        // for($x = 0; $x < $salesbillcount; $x++){
        //     $totsalesbillamt += $salesbill[$x]->grandtotal;
        // }
        //Approved Sales Billing
        $totappsalesbillamt = $billings->where('billing_type_id', 1)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->sum('grandtotal');
        $appsalesbillcount = $billings->where('billing_type_id', 1)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->count();
        // $totappsalesbillamt = 0;
        // for($x = 0; $x < $appsalesbillcount; $x++){
        //     $totappsalesbillamt += $appsalesbill[$x]->grandtotal;
        // }
        //Unapproved Sales Billing
        $totunappsalesbillamt = $billings->where('billing_type_id', 1)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->sum('grandtotal');
        $unappsalesbillcount = $billings->where('billing_type_id', 1)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->count();
        // $totunappsalesbillamt = 0;
        // for($x = 0; $x < $unappsalesbillcount; $x++){
        //     $totunappsalesbillamt += $unappsalesbill[$x]->grandtotal;
        // }

        $totcancelledappsalesbillamt = $billings->where('billing_type_id', 1)->where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->sum('grandtotal');
        $cancelledsalesbillcount = $billings->where('billing_type_id', 1)->where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->count();
        // $totcancelledappsalesbillamt = 0;
        // for($x = 0; $x < $cancelledsalesbillcount; $x++){
        //     $totcancelledappsalesbillamt += $cancelledsalesbill[$x]->grandtotal;
        // }

        //Sales Chart
        $month = [4,5,6,7,8,9,10,11,12,1,2,3];
        $appsales = [];
        $unappsales = [];

        foreach($month as $key=>$value){
            $appsales[] = $billings->where('billing_type_id', 1)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }
        foreach($month as $key=>$value){
            $unappsales[] = $billings->where('billing_type_id', 1)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }

        //Monthly Sales Bill
        $today = date('Y-m-d');
        $neptoday = datenep($today);
        $expdate = explode('-', $neptoday);
        $mth = intval($expdate[1]);

        $totmonthlysalesbillamt = Billing::where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->sum('grandtotal');

        // $totmonthlysalesbillamt = Billing::where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->toSql();
            // dd($totmonthlysalesbillamt);
        // dd($monthlysalesbill);
        $monthlysalesbillcount = Billing::where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->count();
        // $totmonthlysalesbillamt = 0;
        // for($x = 0; $x < $monthlysalesbillcount; $x++){
        //     $totmonthlysalesbillamt += $monthlysalesbill[$x]->grandtotal;
        // }

        //Approved Sales Billing
        $totappmonthlysalesbillamt = $billings->where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)->where('status', 1)->where('is_cancelled', 0)
                                    ->filter(function($q) use($mth){
                                      return $q->where(\DB::raw("substr(nep_date,6,7)"), $mth);
                                    })->sum('grandtotal');
        $appmonthlysalesbillcount = $billings->where('billing_type_id', 1)->where('fiscal_year_id', $current_fiscal_year->id)->where('status', 1)->where('is_cancelled', 0)
                                    ->filter(function($q) use ($mth){
                                        return $q->where(\DB::raw("substr(nep_date,6,7)"),$mth);
                                    })->count();
        // $totappmonthlysalesbillamt = 0;
        // for($x = 0; $x < $appmonthlysalesbillcount; $x++){
        //     $totappmonthlysalesbillamt += $appmonthlysalesbill[$x]->grandtotal;
        // }

        //Unapproved Sales Billing
        $totunappmonthlysalesbillamt = $billings->where('billing_type_id', 1)->where('status', 0)->where('is_cancelled', 0)
                                     ->filter(function($q) use ($mth){
                                         return $q->where(\DB::raw("substr(nep_date,6,7)"), $mth);
                                     })->sum('grandtotal');
        $unappmonthlysalesbillcount = $billings->where('billing_type_id', 1)->where('status', 0)->where('is_cancelled', 0)
                                    ->filter(function($q) use ($mth){
                                        return $q->where(\DB::raw("substr(nep_date,6,7)"), $mth);
                                    })->count();
        // $totunappmonthlysalesbillamt = 0;
        // for($x = 0; $x < $unappmonthlysalesbillcount; $x++){
        //     $totunappmonthlysalesbillamt += $unappmonthlysalesbill[$x]->grandtotal;
        // }

        $totcancelledappmonthlysalesbillamt = Billing::where('billing_type_id', 1)->where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->sum('grandtotal');
        $cancelledmonthlysalesbillcount = Billing::where('billing_type_id', 1)->where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->count();

        // $totcancelledappmonthlysalesbillamt = 0;
        // for($x = 0; $x < $cancelledmonthlysalesbillcount; $x++){
        //     $totcancelledappmonthlysalesbillamt += $cancelledmonthlysalesbill[$x]->grandtotal;
        // }

        //Today Sales Bill
        $tottodaysalesbill = Billing::where('billing_type_id', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');

        // dd($todaysalesbill);
        $todaysalesbillcount = $tottodaysalesbill->count();
        $tottodaysalesbillamt = $tottodaysalesbill->sum('grandtotal');
        // $tottodaysalesbillamt = 0;
        // for($x = 0; $x < $todaysalesbillcount; $x++){
        //     $tottodaysalesbillamt += $todaysalesbill[$x]->grandtotal;
        // }

        //Approved Sales Billing
        $totapptodaysalesbill = Billing::where('billing_type_id', 1)->where('status', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $totapptodaysalesbillamt = $totapptodaysalesbill->sum('grandtotal');
        $apptodaysalesbillcount = $totapptodaysalesbill->count();
        // $totapptodaysalesbillamt = 0;
        // for($x = 0; $x < $apptodaysalesbillcount; $x++){
        //     $totapptodaysalesbillamt += $apptodaysalesbill[$x]->grandtotal;
        // }

        //Unapproved Sales Billing
        $totunapptodaysalesbill = Billing::where('billing_type_id', 1)->where('status', 0)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $totunapptodaysalesbillamt = $totunapptodaysalesbill->sum('grandtotal');
        $unapptodaysalesbillcount = $totunapptodaysalesbill->count();
        // $totunapptodaysalesbillamt = 0;
        // for($x = 0; $x < $unapptodaysalesbillcount; $x++){
        //     $totunapptodaysalesbillamt += $unapptodaysalesbill[$x]->grandtotal;
        // }

        $totcancelledapptodaysalesbill = Billing::where('billing_type_id', 1)->where('is_cancelled', 1)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $totcancelledapptodaysalesbillamt = $totcancelledapptodaysalesbill->sum('grandtotal');
        $cancelledtodaysalesbillcount = $totcancelledapptodaysalesbill->count();
        // $totcancelledapptodaysalesbillamt = 0;
        // for($x = 0; $x < $cancelledtodaysalesbillcount; $x++){
        //     $totcancelledapptodaysalesbillamt += $cancelledtodaysalesbill[$x]->grandtotal;
        // }



        //Sales Return Report
        $totsalesreturnbillamt = Billing::where('billing_type_id', 6)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $salesreturnbillcount = Billing::where('billing_type_id', 6)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totsalesreturnbillamt = 0;
        // for($x = 0; $x < $salesreturnbillcount; $x++){
        //     $totsalesreturnbillamt += $salesreturnbill[$x]->grandtotal;
        // }

        //Approved Salesreturn Billing
        $totappsalesreturnbillamt = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $appsalesreturnbillcount = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totappsalesreturnbillamt = 0;
        // for($x = 0; $x < $appsalesreturnbillcount; $x++){
        //     $totappsalesreturnbillamt += $appsalesreturnbill[$x]->grandtotal;
        // }

        //Unapproved Salesreturn Billing
        $totunappsalesreturnbillamt = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $unappsalesreturnbillcount = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totunappsalesreturnbillamt = 0;
        // for($x = 0; $x < $unappsalesreturnbillcount; $x++){
        //     $totunappsalesreturnbillamt += $unappsalesreturnbill[$x]->grandtotal;
        // }

        $totcancelledappsalesreturnbillamt = Billing::where('billing_type_id', 6)->where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $cancelledsalesreturnbillcount = Billing::where('billing_type_id', 6)->where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totcancelledappsalesreturnbillamt = 0;
        // for($x = 0; $x < $cancelledsalesreturnbillcount; $x++){
        //     $totcancelledappsalesreturnbillamt += $cancelledsalesreturnbill[$x]->grandtotal;
        // }

        $totmonthlysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->sum('grandtotal');
        // dd($monthlysalesreturnbill);
        $monthlysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->count();
        // $totmonthlysalesreturnbillamt = 0;
        // for($x = 0; $x < $monthlysalesreturnbillcount; $x++){
        //     $totmonthlysalesreturnbillamt += $monthlysalesreturnbill[$x]->grandtotal;
        // }
        //Approved Sales Return Billing
        $totappmonthlysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->sum('grandtotal');
        $appmonthlysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->count();
        // $totappmonthlysalesreturnbillamt = 0;
        // for($x = 0; $x < $appmonthlysalesreturnbillcount; $x++){
        //     $totappmonthlysalesreturnbillamt += $appmonthlysalesreturnbill[$x]->grandtotal;
        // }

        //Unapproved Sales Return Billing
        $totunappmonthlysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->sum('grandtotal');
        $unappmonthlysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->count();
        // $totunappmonthlysalesreturnbillamt = 0;
        // for($x = 0; $x < $unappmonthlysalesreturnbillcount; $x++){
        //     $totunappmonthlysalesreturnbillamt += $unappmonthlysalesreturnbill[$x]->grandtotal;
        // }

        $totcancelledappmonthlysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->sum('grandtotal');
        $cancelledmonthlysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal')->count();
        // $totcancelledappmonthlysalesreturnbillamt = 0;
        // for($x = 0; $x < $cancelledmonthlysalesreturnbillcount; $x++){
        //     $totcancelledappmonthlysalesreturnbillamt += $cancelledmonthlysalesreturnbill[$x]->grandtotal;
        // }

        //Today Sales Return Bill
        $tottodaysalesreturnbillamt = Billing::where('billing_type_id', 6)->whereDate('created_at', Carbon::today())->select('grandtotal')->sum('grandtotal');
        // dd($todaysales Returnbill);
        $todaysalesreturnbillcount = Billing::where('billing_type_id', 6)->whereDate('created_at', Carbon::today())->select('grandtotal')->count();
        // $tottodaysalesreturnbillamt = 0;
        // for($x = 0; $x < $todaysalesreturnbillcount; $x++){
        //     $tottodaysalesreturnbillamt += $todaysalesreturnbill[$x]->grandtotal;
        // }

        //Approved Sales Return Billing
        $totapptodaysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal')->sum('grandtotal');
        $apptodaysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal')->count();
        // $totapptodaysalesreturnbillamt = 0;
        // for($x = 0; $x < $apptodaysalesreturnbillcount; $x++){
        //     $totapptodaysalesreturnbillamt += $apptodaysalesreturnbill[$x]->grandtotal;
        // }

        //Unapproved Sales Return Billing
        $totunapptodaysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal')->sum('grandtotal');
        $unapptodaysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal')->count();
        // $totunapptodaysalesreturnbillamt = 0;
        // for($x = 0; $x < $unapptodaysalesreturnbillcount; $x++){
        //     $totunapptodaysalesreturnbillamt += $unapptodaysalesreturnbill[$x]->grandtotal;
        // }

        $totcancelledapptodaysalesreturnbillamt = Billing::where('billing_type_id', 6)->where('is_cancelled', 1)->whereDate('created_at', Carbon::today())->select('grandtotal')->sum('grandtotal');
        $cancelledtodaysalesreturnbillcount = Billing::where('billing_type_id', 6)->where('is_cancelled', 1)->whereDate('created_at', Carbon::today())->select('grandtotal')->count();
        // $totcancelledapptodaysalesreturnbillamt = 0;
        // for($x = 0; $x < $cancelledtodaysalesreturnbillcount; $x++){
        //     $totcancelledapptodaysalesreturnbillamt += $cancelledtodaysalesreturnbill[$x]->grandtotal;
        // }

        //Salesreturn Chart
        $appsalesreturn = [];
        $unappsalesreturn = [];

        foreach($month as $key=>$value){
            $appsalesreturn[] = Billing::where('billing_type_id', 6)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }
        foreach($month as $key=>$value){
            $unappsalesreturn[] = Billing::where('billing_type_id', 6)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }

        //Purchase Report
        $totpurchasebillamt = Billing::where('billing_type_id', 2)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $purchasebillcount = Billing::where('billing_type_id', 2)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totpurchasebillamt = 0;
        // for($x = 0; $x < $purchasebillcount; $x++){
        //     $totpurchasebillamt += $purchasebill[$x]->grandtotal;
        // }

        //Approved Purchase Billing
        $totapppurchasebillamt = Billing::where('billing_type_id', 2)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $apppurchasebillcount = Billing::where('billing_type_id', 2)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totapppurchasebillamt = 0;
        // for($x = 0; $x < $apppurchasebillcount; $x++){
        //     $totapppurchasebillamt += $apppurchasebill[$x]->grandtotal;
        // }

        //Unapproved Purchase Billing
        $totunapppurchasebillamt = Billing::where('billing_type_id', 2)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->sum('grandtotal');
        $unapppurchasebillcount = Billing::where('billing_type_id', 2)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal')->count();
        // $totunapppurchasebillamt = 0;
        // for($x = 0; $x < $unapppurchasebillcount; $x++){
        //     $totunapppurchasebillamt += $unapppurchasebill[$x]->grandtotal;
        // }

        $cancelledpurchasebill = Billing::where('billing_type_id', 2)->where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
        $cancelledpurchasebillcount = $cancelledpurchasebill->count();
        $totcancelledapppurchasebillamt = $cancelledpurchasebill->sum('grandtotal');
        // for($x = 0; $x < $cancelledpurchasebillcount; $x++){
        //     $totcancelledapppurchasebillamt += $cancelledpurchasebill[$x]->grandtotal;
        // }

        $monthlypurchasebill = Billing::where('billing_type_id', 2)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        // dd($monthlypurchasebill);
        $monthlypurchasebillcount = $monthlypurchasebill->count();
        $totmonthlypurchasebillamt = $monthlypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $monthlypurchasebillcount; $x++){
        //     $totmonthlypurchasebillamt += $monthlypurchasebill[$x]->grandtotal;
        // }

        //Approved purchase Billing
        $appmonthlypurchasebill = Billing::where('billing_type_id', 2)->where('status', 1)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        $appmonthlypurchasebillcount = $appmonthlypurchasebill->count();
        $totappmonthlypurchasebillamt = $appmonthlypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $appmonthlypurchasebillcount; $x++){
        //     $totappmonthlypurchasebillamt += $appmonthlypurchasebill[$x]->grandtotal;
        // }

        //Unapproved purchase Billing
        $unappmonthlypurchasebill = Billing::where('billing_type_id', 2)->where('status', 0)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        $unappmonthlypurchasebillcount = $unappmonthlypurchasebill->count();
        $totunappmonthlypurchasebillamt = $unappmonthlypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $unappmonthlypurchasebillcount; $x++){
        //     $totunappmonthlypurchasebillamt += $unappmonthlypurchasebill[$x]->grandtotal;
        // }

        $cancelledmonthlypurchasebill = Billing::where('billing_type_id', 2)->where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        $cancelledmonthlypurchasebillcount = $cancelledmonthlypurchasebill->count();
        $totcancelledappmonthlypurchasebillamt = $cancelledmonthlypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $cancelledmonthlypurchasebillcount; $x++){
        //     $totcancelledappmonthlypurchasebillamt += $cancelledmonthlypurchasebill[$x]->grandtotal;
        // }

        //Today purchase Bill
        $todaypurchasebill = Billing::where('billing_type_id', 2)->whereDate('created_at', Carbon::today());
        // dd($todaypurchasebill);
        $todaypurchasebillcount = $todaypurchasebill->count();
        $tottodaypurchasebillamt = $todaypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $todaypurchasebillcount; $x++){
        //     $tottodaypurchasebillamt += $todaypurchasebill[$x]->grandtotal;
        // }

        //Approved purchase Billing
        $apptodaypurchasebill = Billing::where('billing_type_id', 2)->where('status', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $apptodaypurchasebillcount = $apptodaypurchasebill->count();
        $totapptodaypurchasebillamt = $apptodaypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $apptodaypurchasebillcount; $x++){
        //     $totapptodaypurchasebillamt += $apptodaypurchasebill[$x]->grandtotal;
        // }

        //Unapproved purchase Billing
        $unapptodaypurchasebill = Billing::where('billing_type_id', 2)->where('status', 0)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $unapptodaypurchasebillcount = $unapptodaypurchasebill->count();
        $totunapptodaypurchasebillamt = $unapptodaypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $unapptodaypurchasebillcount; $x++){
        //     $totunapptodaypurchasebillamt += $unapptodaypurchasebill[$x]->grandtotal;
        // }

        $cancelledtodaypurchasebill = Billing::where('billing_type_id', 2)->where('is_cancelled', 1)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $cancelledtodaypurchasebillcount = $cancelledtodaypurchasebill->count();
        $totcancelledapptodaypurchasebillamt = $cancelledtodaypurchasebill->sum('grandtotal');
        // for($x = 0; $x < $cancelledtodaypurchasebillcount; $x++){
        //     $totcancelledapptodaypurchasebillamt += $cancelledtodaypurchasebill[$x]->grandtotal;
        // }

        //Purchase Chart
        $apppurchase = [];
        $unapppurchase = [];

        foreach($month as $key=>$value){
            $apppurchase[] = Billing::where('billing_type_id', 2)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }
        foreach($month as $key=>$value){
            $unapppurchase[] = Billing::where('billing_type_id', 2)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }

        //Purchase Return Report
        $purchasereturnbill = Billing::where('billing_type_id', 5)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
        $purchasereturnbillcount = $purchasereturnbill->count();
        $totpurchasereturnbillamt = $purchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $purchasereturnbillcount; $x++){
        //     $totpurchasereturnbillamt += $purchasereturnbill[$x]->grandtotal;
        // }

        //Approved Purchase Return Billing
        $apppurchasereturnbill = Billing::where('billing_type_id', 5)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
        $apppurchasereturnbillcount = $apppurchasereturnbill->count();
        $totapppurchasereturnbillamt = $apppurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $apppurchasereturnbillcount; $x++){
        //     $totapppurchasereturnbillamt += $apppurchasereturnbill[$x]->grandtotal;
        // }

        //Unapproved Purchase Return Billing
        $unapppurchasereturnbill = Billing::where('billing_type_id', 5)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
        $unapppurchasereturnbillcount = $unapppurchasereturnbill->count();
        $totunapppurchasereturnbillamt = $unapppurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $unapppurchasereturnbillcount; $x++){
        //     $totunapppurchasereturnbillamt += $unapppurchasereturnbill[$x]->grandtotal;
        // }

        $cancelledpurchasereturnbill = Billing::where('billing_type_id', 5)->where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
        $cancelledpurchasereturnbillcount = $cancelledpurchasereturnbill->count();
        $totcancelledapppurchasereturnbillamt = $cancelledpurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $cancelledpurchasereturnbillcount; $x++){
        //     $totcancelledapppurchasereturnbillamt += $cancelledpurchasereturnbill[$x]->grandtotal;
        // }

        $monthlypurchasereturnbill = Billing::where('billing_type_id', 5)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        // dd($monthlypurchasereturnbill);
        $monthlypurchasereturnbillcount = $monthlypurchasereturnbill->count();
        $totmonthlypurchasereturnbillamt = $monthlypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $monthlypurchasereturnbillcount; $x++){
        //     $totmonthlypurchasereturnbillamt += $monthlypurchasereturnbill[$x]->grandtotal;
        // }

        //Approved purchasereturn Billing
        $appmonthlypurchasereturnbill = Billing::where('billing_type_id', 5)->where('status', 1)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        $appmonthlypurchasereturnbillcount = $appmonthlypurchasereturnbill->count();
        $totappmonthlypurchasereturnbillamt = $appmonthlypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $appmonthlypurchasereturnbillcount; $x++){
        //     $totappmonthlypurchasereturnbillamt += $appmonthlypurchasereturnbill[$x]->grandtotal;
        // }

        //Unapproved purchasereturn Billing
        $unappmonthlypurchasereturnbill = Billing::where('billing_type_id', 5)->where('status', 0)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        $unappmonthlypurchasereturnbillcount = $unappmonthlypurchasereturnbill->count();
        $totunappmonthlypurchasereturnbillamt = $unappmonthlypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $unappmonthlypurchasereturnbillcount; $x++){
        //     $totunappmonthlypurchasereturnbillamt += $unappmonthlypurchasereturnbill[$x]->grandtotal;
        // }

        $cancelledmonthlypurchasereturnbill = Billing::where('billing_type_id', 5)->where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
        $cancelledmonthlypurchasereturnbillcount = $cancelledmonthlypurchasereturnbill->count();
        $totcancelledappmonthlypurchasereturnbillamt = $cancelledmonthlypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $cancelledmonthlypurchasereturnbillcount; $x++){
        //     $totcancelledappmonthlypurchasereturnbillamt += $cancelledmonthlypurchasereturnbill[$x]->grandtotal;
        // }

        //Today purchasereturn Bill
        $todaypurchasereturnbill = Billing::where('billing_type_id', 5)->whereDate('created_at', Carbon::today())->select('grandtotal');
        // dd($todaypurchasereturnbill);
        $todaypurchasereturnbillcount = $todaypurchasereturnbill->count();
        $tottodaypurchasereturnbillamt = $todaypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $todaypurchasereturnbillcount; $x++){
        //     $tottodaypurchasereturnbillamt += $todaypurchasereturnbill[$x]->grandtotal;
        // }

        //Approved purchasereturn Billing
        $apptodaypurchasereturnbill = Billing::where('billing_type_id', 5)->where('status', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $apptodaypurchasereturnbillcount = $apptodaypurchasereturnbill->count();
        $totapptodaypurchasereturnbillamt = $apptodaypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $apptodaypurchasereturnbillcount; $x++){
        //     $totapptodaypurchasereturnbillamt += $apptodaypurchasereturnbill[$x]->grandtotal;
        // }

        //Unapproved purchasereturn Billing
        $unapptodaypurchasereturnbill = Billing::where('billing_type_id', 5)->where('status', 0)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $unapptodaypurchasereturnbillcount = $unapptodaypurchasereturnbill->count();
        $totunapptodaypurchasereturnbillamt = $unapptodaypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $unapptodaypurchasereturnbillcount; $x++){
        //     $totunapptodaypurchasereturnbillamt += $unapptodaypurchasereturnbill[$x]->grandtotal;
        // }

        $cancelledtodaypurchasereturnbill = Billing::where('billing_type_id', 5)->where('is_cancelled', 1)->whereDate('created_at', Carbon::today())->select('grandtotal');
        $cancelledtodaypurchasereturnbillcount = $cancelledtodaypurchasereturnbill->count();
        $totcancelledapptodaypurchasereturnbillamt = $cancelledtodaypurchasereturnbill->sum('grandtotal');
        // for($x = 0; $x < $cancelledtodaypurchasereturnbillcount; $x++){
        //     $totcancelledapptodaypurchasereturnbillamt += $cancelledtodaypurchasereturnbill[$x]->grandtotal;
        // }

        //Purchase Return Chart
        $apppurchasereturn = [];
        $unapppurchasereturn = [];

        foreach($month as $key=>$value){
            $apppurchasereturn[] = Billing::where('billing_type_id', 5)->where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }
        foreach($month as $key=>$value){
            $unapppurchasereturn[] = Billing::where('billing_type_id', 5)->where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
        }

        //Recent Quotation Bills
        $recentquotations = Billing::where("billing_type_id", 7)->get();

        //Data of Clients Sales  for Pie
        $billings = Billing::select('client_id', 'grandtotal', 'billing_type_id')
                        ->where('billing_type_id', 1)
                        ->where('client_id', '!=', null)
                        ->where('status', 1)
                        ->select('client_id', DB::raw('sum(grandtotal) as salessum'))
                        ->groupByRaw('client_id')
                        ->orderByDesc('salessum')
                        ->take(10)
                        ->get();
        // dd($billings);
        $salesforclient = array();
        $eachclientname = [];
        $colorcode = [];
        foreach($billings as $billing){
            $clientid = $billing->client_id;
            $client = Client::where('id', $clientid)->first();
            array_push($eachclientname, $client->name);
            array_push($salesforclient, $billing->salessum);
            $randcolor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            array_push($colorcode, $randcolor);
        }

        //Data of Suppliers Purchases  for Pie
        $purchasebillings = Billing::select('vendor_id', 'grandtotal')
                        ->where('vendor_id', '!=', null)
                        ->where('status', 1)
                        ->select('vendor_id', DB::raw('sum(grandtotal) as sum'))
                        ->groupByRaw('vendor_id')
                        ->orderByDesc('sum')
                        ->take(10)
                        ->get();
        // dd($purchasebillings);
        $purchasefromsuppliers = array();
        $eachvendorname = [];
        $purchasecolorcode = [];
        foreach($purchasebillings as $purchasebilling){
            $vendor_id = $purchasebilling->vendor_id;
            $vendorname = Vendor::where('id', $vendor_id)->first();
            array_push($eachvendorname, $vendorname->company_name);
            array_push($purchasefromsuppliers, $purchasebilling->sum);

            $purchaserandcolor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            array_push($purchasecolorcode, $purchaserandcolor);
        }

        $productsales = Billing::leftJoin('billing_extras', 'billings.id', '=', 'billing_extras.billing_id')
                            ->where('billing_type_id', 1)
                            ->where('status', 1)
                            ->where('billing_id', '!=', null)
                            ->select('particulars', DB::raw('count(*) as total'))
                            ->groupByRaw('particulars')
                            ->orderByDesc('total')
                            ->take(10)
                            ->get();
        $productlists = [];
        $productsalescount = [];
        $productcolorcode = [];
        // dd($productsales);
        foreach($productsales as $product)
        {
            $thisproduct = Product::where('id', $product->particulars)->first();
            $productname = $thisproduct->product_name;
            array_push($productlists, $productname);
            array_push($productsalescount, $product->total);
            $productrandcolor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            array_push($productcolorcode, $productrandcolor);
        }

        $productsalesbyamount = Billing::leftJoin('billing_extras', 'billings.id', '=', 'billing_extras.billing_id')
                                    ->where('billing_type_id', 1)
                                    ->where('status', 1)
                                    ->where('billing_id', '!=', null)
                                    ->select('particulars', DB::raw('sum(total) as salestotal'))
                                    ->groupByRaw('particulars')
                                    ->orderByDesc('salestotal')
                                    ->take(10)
                                    ->get();
        $productsalesamountlist = [];
        $productsalsesamount = [];
        $productamountcolorcode = [];

        foreach($productsalesbyamount as $productamount){
            $thisamountproduct = Product::where('id', $productamount->particulars)->first();
            $amountproductname = $thisamountproduct->product_name ?? "undefined";
            array_push($productsalesamountlist, $amountproductname);
            array_push($productsalsesamount, $productamount->salestotal);
            $productamountrandcolor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            array_push($productamountcolorcode, $productamountrandcolor);
        }

        $reconciliations = Reconciliation::latest()->where('cheque_cashed_date', null)->take(10)->get();

          //Service Sales Report
          $servicesalesbill = SalesBills::where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
          $servicesalesbillcount = $servicesalesbill->count();
          $totservicesalesbillamt = $servicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $servicesalesbillcount; $x++){
        //       $totservicesalesbillamt += $servicesalesbill[$x]->grandtotal;
        //   }
          //Approved service Sales Billing
          $appservicesalesbill = SalesBills::where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
          $appservicesalesbillcount = $appservicesalesbill->count();
          $totappservicesalesbillamt = $appservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $appservicesalesbillcount; $x++){
        //       $totappservicesalesbillamt += $appservicesalesbill[$x]->grandtotal;
        //   }
          //Unapproved service Sales Billing
          $unappservicesalesbill = SalesBills::where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
          $unappservicesalesbillcount = $unappservicesalesbill->count();
          $totunappservicesalesbillamt = $unappservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $unappservicesalesbillcount; $x++){
        //       $totunappservicesalesbillamt += $unappservicesalesbill[$x]->grandtotal;
        //   }

          $cancelledservicesalesbill = SalesBills::where('is_cancelled', 1)->where('fiscal_year_id', $current_fiscal_year->id)->select('grandtotal');
          $cancelledservicesalesbillcount = $cancelledservicesalesbill->count();
          $totcancelledappservicesalesbillamt = $cancelledservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $cancelledservicesalesbillcount; $x++){
        //       $totcancelledappservicesalesbillamt += $cancelledservicesalesbill[$x]->grandtotal;
        //   }

          //service Sales Chart
          $appservicesales = [];
          $unappservicesales = [];

          foreach($month as $key=>$value){
              $appservicesales[] = SalesBills::where('status', 1)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
          }
          foreach($month as $key=>$value){
              $unappservicesales[] = SalesBills::where('status', 0)->where('is_cancelled', 0)->where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date, 6,7)"), $value)->count();
          }

          //Monthly service Sales Bill
          $monthlyservicesalesbill = SalesBills::where('fiscal_year_id', $current_fiscal_year->id)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
          // dd($monthlyservicesalesbill);
          $monthlyservicesalesbillcount = $monthlyservicesalesbill->count();
          $totmonthlyservicesalesbillamt = $monthlyservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $monthlyservicesalesbillcount; $x++){
        //       $totmonthlyservicesalesbillamt += $monthlyservicesalesbill[$x]->grandtotal;
        //   }

          //Approved service Sales Billing
          $appmonthlyservicesalesbill = SalesBills::where('status', 1)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
          $appmonthlyservicesalesbillcount = $appmonthlyservicesalesbill->count();
          $totappmonthlyservicesalesbillamt = $appmonthlyservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $appmonthlyservicesalesbillcount; $x++){
        //       $totappmonthlyservicesalesbillamt += $appmonthlyservicesalesbill[$x]->grandtotal;
        //   }

          //Unapproved service Sales Billing
          $unappmonthlyservicesalesbill = SalesBills::where('status', 0)->where('is_cancelled', 0)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
          $unappmonthlyservicesalesbillcount = $unappmonthlyservicesalesbill->count();
          $totunappmonthlyservicesalesbillamt = $unappmonthlyservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $unappmonthlyservicesalesbillcount; $x++){
        //       $totunappmonthlyservicesalesbillamt += $unappmonthlyservicesalesbill[$x]->grandtotal;
        //   }

          $cancelledmonthlyservicesalesbill = SalesBills::where('is_cancelled', 1)->where(\DB::raw("substr(nep_date,6,7)"), $mth)->select('grandtotal');
          $cancelledmonthlyservicesalesbillcount = $cancelledmonthlyservicesalesbill->count();
          $totcancelledappmonthlyservicesalesbillamt = $cancelledmonthlyservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $cancelledmonthlyservicesalesbillcount; $x++){
        //       $totcancelledappmonthlyservicesalesbillamt += $cancelledmonthlyservicesalesbill[$x]->grandtotal;
        //   }

          //Today service Sales Bill
          $todayservicesalesbill = SalesBills::whereDate('created_at', Carbon::today())->get();

          // dd($todayservicesalesbill);
          $todayservicesalesbillcount = $todayservicesalesbill->count();
          $tottodayservicesalesbillamt = $todayservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $todayservicesalesbillcount; $x++){
        //       $tottodayservicesalesbillamt += $todayservicesalesbill[$x]->grandtotal;
        //   }

          //Approved service Sales Billing
          $apptodayservicesalesbill = SalesBills::where('status', 1)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
          $apptodayservicesalesbillcount = $apptodayservicesalesbill->count();
          $totapptodayservicesalesbillamt = $apptodayservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $apptodayservicesalesbillcount; $x++){
        //       $totapptodayservicesalesbillamt += $apptodayservicesalesbill[$x]->grandtotal;
        //   }

          //Unapproved service Sales Billing
          $unapptodayservicesalesbill = SalesBills::where('status', 0)->where('is_cancelled', 0)->whereDate('created_at', Carbon::today())->select('grandtotal');
          $unapptodayservicesalesbillcount = $unapptodayservicesalesbill->count();
          $totunapptodayservicesalesbillamt = $unapptodayservicesalesbill->sum('grandtotal');
        //   for($x = 0; $x < $unapptodayservicesalesbillcount; $x++){
        //       $totunapptodayservicesalesbillamt += $unapptodayservicesalesbill[$x]->grandtotal;
        //   }

          $cancelledtodayservicesalesbill = SalesBills::where('is_cancelled', 1)->whereDate('created_at', Carbon::today())->select('grandtotal');
          $cancelledtodayservicesalesbillcount = $cancelledtodayservicesalesbill->count();
          $totcancelledapptodayservicesalesbillamt = $cancelledtodayservicesalesbill->sum('grandtotal');

        //   for($x = 0; $x < $cancelledtodayservicesalesbillcount; $x++){
        //       $totcancelledapptodayservicesalesbillamt += $cancelledtodayservicesalesbill[$x]->grandtotal;
        //   }

        return view('backend.dashboard', compact('total_journals', 'reconciliations', 'godowns', 'product_categories', 'latest_products', 'products', 'damagedProducts', 'approved_journals', 'unapproved_journals', 'cancelled_journals', 'fiscal_year','recentquotations',
                                                 'salesbillcount', 'appsalesbillcount', 'unappsalesbillcount', 'cancelledsalesbillcount', 'totsalesbillamt', 'totappsalesbillamt', 'totunappsalesbillamt', 'totcancelledappsalesbillamt','appsales','unappsales',
                                                 'monthlysalesbillcount', 'appmonthlysalesbillcount', 'unappmonthlysalesbillcount', 'cancelledmonthlysalesbillcount', 'totmonthlysalesbillamt', 'totappmonthlysalesbillamt', 'totunappmonthlysalesbillamt', 'totcancelledappmonthlysalesbillamt',
                                                 'todaysalesbillcount', 'apptodaysalesbillcount', 'unapptodaysalesbillcount', 'cancelledtodaysalesbillcount', 'tottodaysalesbillamt', 'totapptodaysalesbillamt', 'totunapptodaysalesbillamt', 'totcancelledapptodaysalesbillamt',
                                                 'salesreturnbillcount', 'appsalesreturnbillcount', 'unappsalesreturnbillcount', 'cancelledsalesreturnbillcount', 'totsalesreturnbillamt', 'totappsalesreturnbillamt', 'totunappsalesreturnbillamt', 'totcancelledappsalesreturnbillamt','appsalesreturn','unappsalesreturn',
                                                 'monthlysalesreturnbillcount', 'appmonthlysalesreturnbillcount', 'unappmonthlysalesreturnbillcount', 'cancelledmonthlysalesreturnbillcount', 'totmonthlysalesreturnbillamt', 'totappmonthlysalesreturnbillamt', 'totunappmonthlysalesreturnbillamt', 'totcancelledappmonthlysalesreturnbillamt',
                                                 'todaysalesreturnbillcount', 'apptodaysalesreturnbillcount', 'unapptodaysalesreturnbillcount', 'cancelledtodaysalesreturnbillcount', 'tottodaysalesreturnbillamt', 'totapptodaysalesreturnbillamt', 'totunapptodaysalesreturnbillamt', 'totcancelledapptodaysalesreturnbillamt',
                                                 'purchasebillcount', 'apppurchasebillcount', 'unapppurchasebillcount', 'cancelledpurchasebillcount', 'totpurchasebillamt', 'totapppurchasebillamt', 'totunapppurchasebillamt', 'totcancelledapppurchasebillamt','apppurchase','unapppurchase',
                                                 'monthlypurchasebillcount', 'appmonthlypurchasebillcount', 'unappmonthlypurchasebillcount', 'cancelledmonthlypurchasebillcount', 'totmonthlypurchasebillamt', 'totappmonthlypurchasebillamt', 'totunappmonthlypurchasebillamt', 'totcancelledappmonthlypurchasebillamt',
                                                 'todaypurchasebillcount', 'apptodaypurchasebillcount', 'unapptodaypurchasebillcount', 'cancelledtodaypurchasebillcount', 'tottodaypurchasebillamt', 'totapptodaypurchasebillamt', 'totunapptodaypurchasebillamt', 'totcancelledapptodaypurchasebillamt',
                                                 'purchasereturnbillcount', 'apppurchasereturnbillcount', 'unapppurchasereturnbillcount', 'cancelledpurchasereturnbillcount', 'totpurchasereturnbillamt', 'totapppurchasereturnbillamt', 'totunapppurchasereturnbillamt', 'totcancelledapppurchasereturnbillamt','apppurchasereturn','unapppurchasereturn',
                                                 'monthlypurchasereturnbillcount', 'appmonthlypurchasereturnbillcount', 'unappmonthlypurchasereturnbillcount', 'cancelledmonthlypurchasereturnbillcount', 'totmonthlypurchasereturnbillamt', 'totappmonthlypurchasereturnbillamt', 'totunappmonthlypurchasereturnbillamt', 'totcancelledappmonthlypurchasereturnbillamt',
                                                 'todaypurchasereturnbillcount', 'apptodaypurchasereturnbillcount', 'unapptodaypurchasereturnbillcount', 'cancelledtodaypurchasereturnbillcount', 'tottodaypurchasereturnbillamt', 'totapptodaypurchasereturnbillamt', 'totunapptodaypurchasereturnbillamt', 'totcancelledapptodaypurchasereturnbillamt',
                                                 'eachclientname', 'salesforclient','colorcode',
                                                 'eachvendorname', 'purchasefromsuppliers','purchasecolorcode',
                                                 'productlists', 'productsalescount', 'productcolorcode',
                                                 'productsalesamountlist', 'productsalsesamount', 'productamountcolorcode',
                                                  'todayservicesalesbillcount','tottodayservicesalesbillamt','apptodayservicesalesbillcount','totapptodayservicesalesbillamt','unapptodayservicesalesbillcount','totunapptodayservicesalesbillamt','cancelledtodayservicesalesbillcount','totcancelledapptodayservicesalesbillamt',
                                                  'monthlyservicesalesbillcount','totmonthlyservicesalesbillamt','appmonthlyservicesalesbillcount','unappmonthlyservicesalesbillcount','cancelledmonthlyservicesalesbillcount','totcancelledappmonthlyservicesalesbillamt','totappmonthlyservicesalesbillamt','totunappmonthlyservicesalesbillamt',
                                                   'servicesalesbillcount','totservicesalesbillamt','appservicesalesbillcount','totappservicesalesbillamt','unappservicesalesbillcount','totunappservicesalesbillamt','cancelledservicesalesbillcount','totcancelledappservicesalesbillamt',
                                                 'appservicesales','unappservicesales','cashinhand','cashinbank'

                                                ));
    }

    public function switchselected($id)
    {
        $currcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
        $currcomp->update([
            'is_selected'=>0,
        ]);
        $currcomp->save();
        $newselect = UserCompany::findorfail($id);
        $newselect->update([
            'is_selected'=> 1,
        ]);
        $newselect->save();
        return redirect()->route('home');
    }

    public function globsearch(Request $request){
        $key = $request->searchkey;
        $client = Client::select('name','id')->where('name','LIKE','%'.$key.'%')->orWhere('email',"LIKE",'%'.$key.'%')->orWhere('phone',"LIKE",'%'.$key.'%')->get();
        $vendor = Vendor::select('company_name','id')->where('company_name','LIKE','%'.$key.'%')->orWhere('company_email',"LIKE",'%'.$key.'%')->orWhere('company_phone',"LIKE",'%'.$key.'%')->get();
        $searchdatas = collect($client)->merge($vendor);
        $html = "";
        if(count($searchdatas) < 1 ){
            return "<p>No Data Found</p>";
        }
        foreach($searchdatas as $data){
            if(isset($data->name)){
             $html .= "<p><a style='color:#423d3d' href='".route('client.show',$data->id)."'>".$data->name."(Customer)</a></p>";
            }
            if(isset($data->company_name)){
                $html .= "<p><a style='color:#423d3d' href='".route('vendors.show',$data->id)."'>".$data->company_name."(Vendor)</a></p>";
            }
        }
        $html .= "";
       return $html;
        // print_r($searchdatas);exit;
        // return 'hi';
    }
}
