<?php
namespace App\Actions;

use App\FormDatas\ServiceSaleBillFormData;
use App\Models\SalesBills;
use App\Models\ServiceSalesExtra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SalesBillsController;
use App\Models\Bank;
use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\JournalExtra;
use App\Models\JournalVouchers;
use App\Models\OnlinePayment;
use App\Models\OpeningBalance;
use App\Models\Reconciliation;
use App\Models\Service;
use App\Models\SubAccount;
use App\Models\Vendor;

class CreateServiceSaleBillsAction {

    public function execute(ServiceSaleBillFormData $serviceSaleBillFormData): SalesBills
    {
        DB::beginTransaction();
        try
        {
            $journals = JournalVouchers::latest()->where('fiscal_year_id', $serviceSaleBillFormData->fiscal_year_id)->get();
            if(count($journals) == 0)
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
            $jvno = "JV-".$jvnumber;
            // dd($serviceSaleBillFormData);
            $serviceSaleBill = SalesBills::create([
                'client_id' => $serviceSaleBillFormData->client_id ?? null,
                'transaction_no' => $serviceSaleBillFormData->getTransactionNo(),
                'reference_no' => $serviceSaleBillFormData->getRegistrationNo(),
                'ledger_no' => $serviceSaleBillFormData->ledger_no,
                'file_no' => $serviceSaleBillFormData->file_no,
                'remarks' => $serviceSaleBillFormData->remarks,
                'eng_date' => $serviceSaleBillFormData->eng_date,
                'nep_date' => $serviceSaleBillFormData->nep_date,
                'payment_method' => $serviceSaleBillFormData->payment_method,
                'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                'online_portal_id' => $serviceSaleBillFormData->getOnlinePortalId(),
                'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                'customer_portal_id' => $serviceSaleBillFormData->getCustomerPortalId(),
                'entry_by' => Auth::user()->id,
                'status' => $serviceSaleBillFormData->status,
                'approved_by' => $serviceSaleBillFormData->getApprovedBy(),
                'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                'subtotal' => $serviceSaleBillFormData->subtotal,
                'alltaxtype' => $serviceSaleBillFormData->alltaxtype,
                'taxpercent' => $serviceSaleBillFormData->alltax,
                'alltax' => $serviceSaleBillFormData->alltax,
                'alldtamt' => $serviceSaleBillFormData->alldtamt,
                'alldiscounttype' => $serviceSaleBillFormData->alldiscounttype,
                'discountpercent' => $serviceSaleBillFormData->getDiscountPercent(),
                'taxamount' => $serviceSaleBillFormData->taxamount,
                'discountamount' => $serviceSaleBillFormData->getDiscountAmount(),
                'shipping' => $serviceSaleBillFormData->shipping,
                'grandtotal' => $serviceSaleBillFormData->grandtotal,
                'payment_type' => $serviceSaleBillFormData->payment_type,
                'payment_amount' => $serviceSaleBillFormData->payment_amount,
                'sync_ird' => $serviceSaleBillFormData->getSyncIrdInfo(),
                'is_realtime' => $serviceSaleBillFormData->getRealTimeInfo(),
                'vat_refundable' => $serviceSaleBillFormData->vat_refundable,
                'vendor_id' => $serviceSaleBillFormData->vendor_id,
                'billing_type_id' => $serviceSaleBillFormData->billing_type_id,
                'reference_invoice_no' => $serviceSaleBillFormData->reference_invoice_no,
                'service_charge' => $serviceSaleBillFormData->service_charge,
                'servicediscounttype' => $serviceSaleBillFormData->servicediscounttype,
                'servicediscountamount' => $serviceSaleBillFormData->servicediscountamount,
                'related_jv_no' => $jvno,
            ]);

            $particulars = $serviceSaleBillFormData->particulars;
            $quantity = $serviceSaleBillFormData->quantity;
            $rate = $serviceSaleBillFormData->rate;
            $unit = $serviceSaleBillFormData->unit;
            $discountamt = $serviceSaleBillFormData->discountamt;
            $discounttype = $serviceSaleBillFormData->discounttype;
            $dtamt = $serviceSaleBillFormData->dtamt;
            $taxamt = $serviceSaleBillFormData->taxamt;
            $itemtax = $serviceSaleBillFormData->itemtax;
            $taxtype = $serviceSaleBillFormData->taxtype;
            $tax = $serviceSaleBillFormData->tax;
            $total = $serviceSaleBillFormData->total;
            $count = count($particulars);

            $taxsum = 0;
            if($serviceSaleBillFormData->alldiscounttype == 'percent'){
                $discountsum = $serviceSaleBillFormData->getDiscountPercent();
            }elseif($serviceSaleBillFormData->alldiscounttype == 'fixed'){
                $discountsum = $serviceSaleBillFormData->getDiscountAmount();
            }


            for($x = 0; $x < $count; $x++)
            {
                ServiceSalesExtra::create([
                    'sales_bills_id' => $serviceSaleBill->id,
                    'particulars' => $particulars[$x],
                    'quantity' => $quantity[$x],
                    'unit'=>$unit[$x],
                    'rate' => $rate[$x],
                    'discountamt' => $discountamt[$x],
                    'discounttype' => $discounttype[$x],
                    'dtamt' => $dtamt[$x],
                    'taxamt' => $taxamt[$x],
                    'itemtax' => $itemtax[$x],
                    'taxtype' => $taxtype[$x],
                    'tax' => $tax[$x],
                    'total' => $total[$x],
                ]);

                $taxsum += $itemtax[$x];
                $discountsum += ($discountamt[$x] * $quantity[$x]);
            }
            $tottax = $taxsum == 0 ? $serviceSaleBillFormData->taxamount :  $taxsum;
            $serviceSaleBill->save();

            //for taxinfo
            if($serviceSaleBillFormData->sync_ird == 1 && $serviceSaleBillFormData->status == 1)
             {
                $taxinfoarray = array(
                    'eng_date' => $serviceSaleBillFormData->eng_date,
                    'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                    'taxamount' => $serviceSaleBillFormData->taxamount,
                );
                $salebill = new SalesBillsController;
                    $returnnotes = ($serviceSaleBillFormData->billing_type_id == 6) ? 'creditnote' : "";
                    if($serviceSaleBillFormData->billing_type_id == 2){
                        $returnnotes = 'serviceSalepurchase';
                    }
                    if($serviceSaleBillFormData->billing_type_id == 5){
                        $returnnotes = 'debitnote';
                    }

                $salebill->taxinfoCreate($taxinfoarray,$returnnotes);
             }

             //forbillingcredit
             if($serviceSaleBillFormData->payment_type != "paid"){
                $salebill = new SalesBillsController;
                $serviceSaleBill->due_date_nep = $serviceSaleBillFormData->due_date_nep;
                $serviceSaleBill->due_date_eng = $serviceSaleBillFormData->due_date_eng;
                $salebill->billingCredit($serviceSaleBill);
             }

            if($serviceSaleBillFormData->billing_type_id == 2){
                // For Journal Voucher
                $journal_voucher = JournalVouchers::create([
                    'journal_voucher_no' => $jvno,
                    'entry_date_english' => $serviceSaleBillFormData->eng_date,
                    'entry_date_nepali' => $serviceSaleBillFormData->nep_date,
                    'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                    'debitTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'creditTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'payment_method' => $serviceSaleBillFormData->payment_method,
                    'receipt_payment' => null,
                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                    'online_portal_id' => $serviceSaleBillFormData->getOnlinePortalId(),
                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                    'customer_portal_id' => $serviceSaleBillFormData->getCustomerPortalId(),
                    'narration' => 'Being Services Purchased',
                    'is_cancelled'=>'0',
                    'status' =>$serviceSaleBillFormData->status,
                    'vendor_id'=>$serviceSaleBillFormData->vendor_id,
                    'entry_by'=> Auth::user()->id,
                    'approved_by'=> $serviceSaleBillFormData->status == 1 ? Auth::user()->id : null,
                    'client_id'=> null,
                ]);

                // Getting Child Account Id of every Particulars
                // Debit Entries
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
                if($taxsum > 0){
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
                if($serviceSaleBillFormData->service_charge > 0){
                    $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                    $service_charge_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$service_charge_id,
                        'remarks'=>'Service Charge',
                        'debitAmount'=>$serviceSaleBillFormData->service_charge,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $service_charge_entry);
                }


                // Shipping Entry
                if($serviceSaleBillFormData->shipping > 0){
                    $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                    $shipping_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$shipping_id,
                        'remarks'=>'Shipping Cost',
                        'debitAmount'=>$serviceSaleBillFormData->shipping,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $shipping_entry);
                }

                // Credit Entries
                // Cash Paid

                if($serviceSaleBillFormData->payment_type == 'paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                        ];
                        array_push($jv_extras, $cash_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $bank_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                        ];
                        array_push($jv_extras, $bank_entry);
                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->grandtotal,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                        ];
                        array_push($jv_extras, $online_portal);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'partially_paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->payment_amount,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$due_amount,
                        ];
                        array_push($jv_extras, $due_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->payment_amount,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$due_amount,
                        ];
                        array_push($jv_extras, $due_entry);

                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->payment_amount,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->payment_amount,
                        ];
                        array_push($jv_extras, $online_portal);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$due_amount,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'unpaid'){
                    $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                    ];
                    array_push($jv_extras, $due_entry);
                }
                // Discount Taken
                $totdiscount = $discountsum;
                if($discountsum > 0){
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
                    if($serviceSaleBillFormData->status == 1){
                        $this->openingbalance($jv_extra['child_account_id'], $serviceSaleBillFormData->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                    }
                }

                $journal_voucher->save();
            // Journal Voucher Ends Here
             }elseif($serviceSaleBillFormData->billing_type_id == 1){
                // For Journal Voucher
                $journal_voucher = JournalVouchers::create([
                    'journal_voucher_no' => $jvno,
                    'entry_date_english' => $serviceSaleBillFormData->eng_date,
                    'entry_date_nepali' => $serviceSaleBillFormData->nep_date,
                    'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                    'debitTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'creditTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'payment_method' => $serviceSaleBillFormData->payment_method,
                    'receipt_payment' => null,
                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                    'online_portal_id' => $serviceSaleBillFormData->getOnlinePortalId(),
                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                    'customer_portal_id' => $serviceSaleBillFormData->getCustomerPortalId(),
                    'narration' => 'Being Services Sold',
                    'is_cancelled'=>'0',
                    'status' =>$serviceSaleBillFormData->status,
                    'vendor_id'=>$serviceSaleBillFormData->vendor_id,
                    'entry_by'=> Auth::user()->id,
                    'approved_by'=> $serviceSaleBillFormData->status == 1 ? Auth::user()->id : null,
                    'client_id'=> null,
                ]);

                // Getting Child Account Id of every Particulars
                // Debit Entries
                $jv_extras = [];
                if($serviceSaleBillFormData->payment_type == 'paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $cash_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $bank_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $bank_entry);
                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->grandtotal,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $online_portal);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'partially_paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->payment_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $client_child_id = Client::where('id', $serviceSaleBillFormData->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$due_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>$serviceSaleBillFormData->payment_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$due_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);

                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->payment_amount,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->payment_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $online_portal);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$due_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'unpaid'){
                    $client_child_id = Client::where('id', $serviceSaleBillFormData->client_id)->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);
                }
                // Discount Taken
                $totdiscount = $discountsum;
                if($discountsum > 0){
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
                    $margin += $rate_per_qty - $total_cost_price;

                    $particular_child_account_id = $particular_product->child_account_id;
                    $remark = $particular_product->service_name . '('. $particular_product->service_code .')';
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
                if($taxsum > 0){
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
                if($serviceSaleBillFormData->service_charge > 0){
                    $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                    $service_charge_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$service_charge_id,
                        'remarks'=>'Service Charge',
                        'debitAmount'=>0,
                        'creditAmount'=>$serviceSaleBillFormData->service_charge,
                    ];
                    array_push($jv_extras, $service_charge_entry);
                }


                // Shipping Entry
                if($serviceSaleBillFormData->shipping > 0){
                    $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                    $shipping_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$shipping_id,
                        'remarks'=>'Shipping Cost',
                        'debitAmount'=>0,
                        'creditAmount'=>$serviceSaleBillFormData->shipping,
                    ];
                    array_push($jv_extras, $shipping_entry);
                }
                // dd($jv_extras);
                foreach($jv_extras as $key => $jv_extra){
                    JournalExtra::create($jv_extra);
                    if($serviceSaleBillFormData->status == 1){
                        $this->openingbalance($jv_extra['child_account_id'], $serviceSaleBillFormData->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                    }
                }

                $journal_voucher->save();
             }elseif($serviceSaleBillFormData->billing_type_id == 5){

                // For Journal Voucher
                $journal_voucher = JournalVouchers::create([
                    'journal_voucher_no' => $jvno,
                    'entry_date_english' => $serviceSaleBillFormData->eng_date,
                    'entry_date_nepali' => $serviceSaleBillFormData->nep_date,
                    'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                    'debitTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'creditTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'payment_method' => $serviceSaleBillFormData->payment_method,
                    'receipt_payment' => null,
                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                    'online_portal_id' => $serviceSaleBillFormData->getOnlinePortalId(),
                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                    'customer_portal_id' => $serviceSaleBillFormData->getCustomerPortalId(),
                    'narration' => 'Being Purchased Services Return',
                    'is_cancelled'=>'0',
                    'status' =>$serviceSaleBillFormData->status,
                    'vendor_id'=>$serviceSaleBillFormData->vendor_id,
                    'entry_by'=> Auth::user()->id,
                    'approved_by'=> $serviceSaleBillFormData->status == 1 ? Auth::user()->id : null,
                    'client_id'=> null,
                ]);

                // Getting Child Account Id of every Particulars
                // Debit Entries
                $jv_extras = [];
                if($serviceSaleBillFormData->payment_type == 'paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $cash_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $bank_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $bank_entry);
                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->grandtotal,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->grandtotal,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $online_portal);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'partially_paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->payment_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$due_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>$serviceSaleBillFormData->payment_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$due_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);

                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->payment_amount,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$serviceSaleBillFormData->payment_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $online_portal);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$vendor_child_id,
                            'remarks'=>'',
                            'debitAmount'=>$due_amount,
                            'creditAmount'=>0,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'unpaid'){
                    $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$serviceSaleBillFormData->grandtotal,
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
                if($taxsum > 0){
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
                if($serviceSaleBillFormData->service_charge > 0){
                    $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                    $service_charge_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$service_charge_id,
                        'remarks'=>'Service Charge',
                        'debitAmount'=>0,
                        'creditAmount'=>$serviceSaleBillFormData->service_charge,
                    ];
                    array_push($jv_extras, $service_charge_entry);
                }


                // Shipping Entry
                if($serviceSaleBillFormData->shipping > 0){
                    $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                    $shipping_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$shipping_id,
                        'remarks'=>'Shipping Cost',
                        'debitAmount'=>0,
                        'creditAmount'=>$serviceSaleBillFormData->shipping,
                    ];
                    array_push($jv_extras, $shipping_entry);
                }

                foreach($jv_extras as $key => $jv_extra){
                    JournalExtra::create($jv_extra);
                    if($serviceSaleBillFormData->status == 1){
                        $this->openingbalance($jv_extra['child_account_id'], $serviceSaleBillFormData->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                    }
                }

                $journal_voucher->save();
             }elseif($serviceSaleBillFormData->billing_type_id == 6){
                // For Journal Voucher
                $journal_voucher = JournalVouchers::create([
                    'journal_voucher_no' => $jvno,
                    'entry_date_english' => $serviceSaleBillFormData->eng_date,
                    'entry_date_nepali' => $serviceSaleBillFormData->nep_date,
                    'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                    'debitTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'creditTotal' => $serviceSaleBillFormData->grandtotal + $discountsum,
                    'payment_method' => $serviceSaleBillFormData->payment_method,
                    'receipt_payment' => null,
                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                    'online_portal_id' => $serviceSaleBillFormData->getOnlinePortalId(),
                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                    'customer_portal_id' => $serviceSaleBillFormData->getCustomerPortalId(),
                    'narration' => 'Being Sold Services Returned',
                    'is_cancelled'=>'0',
                    'status' =>$serviceSaleBillFormData->status,
                    'vendor_id'=>$serviceSaleBillFormData->vendor_id,
                    'entry_by'=> Auth::user()->id,
                    'approved_by'=> $serviceSaleBillFormData->status == 1 ? Auth::user()->id : null,
                    'client_id'=> null,
                ]);

                // Getting Child Account Id of every Particulars
                // Debit Entries
                $jv_extras = [];
                $margin = 0;
                for($x=0; $x<$count; $x++){
                    $particular_product = Service::where('id', $particulars[$x])->select('child_account_id', 'service_name', 'service_code', 'cost_price')->first();
                    $cost_price = $particular_product->cost_price;
                    $total_cost_price = $cost_price * $quantity[$x];
                    $rate_per_qty = $rate[$x] * $quantity[$x];
                    $margin += $rate_per_qty - $total_cost_price;

                    $particular_child_account_id = $particular_product->child_account_id;
                    $remark = $particular_product->service_name . '('. $particular_product->service_code .')';
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
                if($taxsum > 0){
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
                if($serviceSaleBillFormData->service_charge > 0){
                    $service_charge_id = ChildAccount::where('slug', 'service-charge')->first()->id;
                    $service_charge_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$service_charge_id,
                        'remarks'=>'Service Charge',
                        'debitAmount'=>$serviceSaleBillFormData->service_charge,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $service_charge_entry);
                }


                // Shipping Entry
                if($serviceSaleBillFormData->shipping > 0){
                    $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                    $shipping_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$shipping_id,
                        'remarks'=>'Shipping Cost',
                        'debitAmount'=>$serviceSaleBillFormData->shipping,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $shipping_entry);
                }

                // Credit Entries
                // Cash Paid

                if($serviceSaleBillFormData->payment_type == 'paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                        ];
                        array_push($jv_extras, $cash_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $bank_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                        ];
                        array_push($jv_extras, $bank_entry);
                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->grandtotal,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                        ];
                        array_push($jv_extras, $online_portal);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'partially_paid'){
                    if($serviceSaleBillFormData->payment_method == 1){
                        $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$cash_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->payment_amount,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $client_child_id = Client::where('id', $serviceSaleBillFormData->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$due_amount,
                        ];
                        array_push($jv_extras, $due_entry);
                    }elseif($serviceSaleBillFormData->payment_method == 2 || $serviceSaleBillFormData->payment_method == 3){
                        $bank_child_id = Bank::where('id', $serviceSaleBillFormData->getPaymentBankId())->first()->child_account_id;
                        $cash_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$bank_child_id,
                            'remarks'=>$serviceSaleBillFormData->getChequeNo() ?? '',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->payment_amount,
                        ];
                        array_push($jv_extras, $cash_entry);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $client_child_id = Client::where('id', $serviceSaleBillFormData->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$due_amount,
                        ];
                        array_push($jv_extras, $due_entry);

                        if($serviceSaleBillFormData->payment_method == 2){
                            if($serviceSaleBillFormData->getPaymentBankId() != null)
                            {
                                Reconciliation::create([
                                    'jv_id' => $journal_voucher['id'],
                                    'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                                    'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                                    'receipt_payment' => null,
                                    'amount' => $serviceSaleBillFormData->payment_amount,
                                    'cheque_entry_date' => $serviceSaleBillFormData->nep_date,
                                    'vendor_id' => $serviceSaleBillFormData->vendor_id,
                                    'client_id'=>$serviceSaleBillFormData->client_id ?? null,
                                    'other_receipt' => '-'
                                ]);
                            }
                        }
                    }elseif($serviceSaleBillFormData->payment_method == 4){
                        $online_portal_child_id = OnlinePayment::where('id', $serviceSaleBillFormData->getCustomerPortalId())->first()->child_account_id;
                        $online_portal = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$online_portal_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$serviceSaleBillFormData->payment_amount,
                        ];
                        array_push($jv_extras, $online_portal);

                        $due_amount = $serviceSaleBillFormData->grandtotal - $serviceSaleBillFormData->payment_amount;

                        $client_child_id = Client::where('id', $serviceSaleBillFormData->client_id)->first()->child_account_id;
                        $due_entry = [
                            'journal_voucher_id'=>$journal_voucher['id'],
                            'child_account_id'=>$client_child_id,
                            'remarks'=>'',
                            'debitAmount'=>0,
                            'creditAmount'=>$due_amount,
                        ];
                        array_push($jv_extras, $due_entry);
                    }
                }elseif($serviceSaleBillFormData->payment_type == 'unpaid'){
                    $vendor_child_id = Vendor::where('id', $serviceSaleBillFormData->vendor_id)->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$serviceSaleBillFormData->grandtotal,
                    ];
                    array_push($jv_extras, $due_entry);
                }
                // Discount Taken
                $totdiscount = $discountsum;
                if($discountsum > 0){
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
                    if($serviceSaleBillFormData->status == 1){
                        $this->openingbalance($jv_extra['child_account_id'], $serviceSaleBillFormData->fiscal_year_id, $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                    }
                }

                $journal_voucher->save();
             }
            DB::commit();
        }
        catch(\Exception $e)
        {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $serviceSaleBill;
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


}
