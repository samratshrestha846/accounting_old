<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOnlinePaymentRequest;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\OnlinePayment;
use App\Models\OpeningBalance;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function app\NepaliCalender\datenep;

class OnlinePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ( $request->user()->can( 'manage-online-payment' ) )
        {
            $portals = OnlinePayment::latest()->paginate(10);
            return view('backend.onlinepayment.index', compact('portals'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ( $request->user()->can( 'manage-online-payment' )) {
            return view('backend.onlinepayment.create');
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOnlinePaymentRequest $request)
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        DB::beginTransaction();
        try{
            // child Account
            if($request['opening_balance'] == null){
                $opening_balance = 0;
            }else{
                if($request['behaviour'] == "credit")
                {
                    $opening_balance = '-'.$request['opening_balance'];
                }
                elseif($request['behaviour'] == "debit")
                {
                    $opening_balance = $request['opening_balance'];
                }
            }
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
            $childAccount = ChildAccount::create([
                'title' => $request['name'].'('.$request['payment_id'].')',
                'slug' => Str::slug($request['name'].'('.$request['payment_id'].')'),
                'opening_balance' => $opening_balance,
                'sub_account_id' => $bankaccount_id,
            ]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $opening_balance,
                'closing_balance' => $opening_balance
            ]);
            $openingbalance->save();
            $onlinePayment = OnlinePayment::create([
                'name'=>$request['name'],
                'payment_id'=>$request['payment_id'],
                'child_account_id'=>$childAccount['id']
            ]);
            $onlinePayment->save();
            DB::commit();
            return redirect()->route('onlinepayment.index')->with('success', 'Online payment portal is successfully created.');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OnlinePayment  $onlinePayment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OnlinePayment  $onlinePayment
     * @return \Illuminate\Http\Response
     */
    public function edit($id ,Request $request)
    {
        if ( $request->user()->can( 'manage-online-payment' ) ) {
            $onlinePayment = OnlinePayment::findorFail($id);
            return view('backend.onlinepayment.edit', compact('onlinePayment'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OnlinePayment  $onlinePayment
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOnlinePaymentRequest $request, $id)
    {
        $onlinePayment = OnlinePayment::findorFail($id);
        $onlinePayment->update([
            'name'=>$request['name'],
            'payment_id'=>$request['payment_id'],
        ]);
        return redirect()->route('onlinepayment.index')->with('success', 'Online payment portal is successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OnlinePayment  $onlinePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ( $request->user()->can( 'manage-online-payment' ) ) {
            OnlinePayment::findorFail($id)->delete();
            return redirect()->route('onlinepayment.index')->with('success', 'Online payment portal is successfully deleted.');
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
