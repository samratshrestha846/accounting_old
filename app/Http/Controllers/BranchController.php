<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChildAccount;
use App\Models\Company;
use App\Models\District;
use App\Models\Province;
use App\Models\SuperSetting;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
            $branches = Branch::with( 'companies' )->where('company_id', $currentcomp->company_id)->latest()->paginate( 10 );
            return view( 'backend.branch.index', compact( 'branches' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );
        $branches = Branch::query()
        ->where( 'name', 'LIKE', "%{$search}%" )
        ->orWhere( 'local_address', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.branch.search', compact( 'branches' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $companies = Company::latest()->get();
            $provinces = Province::latest()->get();
            $district_group = District::latest()->get();
            $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
            $branch_count = Branch::where( 'company_id', $currentcomp->company_id )->get()->count();
            $super_setting = SuperSetting::first();
            if ( $branch_count >= $super_setting->branch_limit ) {
                return redirect()->route( 'branch.index' )->with( 'error', 'You have exceeded branch limitations for this company. Cannot create new branch.' );
            }
            return view( 'backend.branch.create', compact( 'companies', 'provinces', 'district_group', 'currentcomp' ) );
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

    public function store( Request $request ) {
        $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
        $super_setting = SuperSetting::first();
        $countbranch = Branch::where( 'company_id', $currentcomp->company_id )->count();
        if ( $countbranch >= $super_setting->branch_limit ) {
            return redirect()->route( 'branch.index' )->with( 'error', 'You have already met the limit for creating Branch for this Company' );
        } else {
            $this->validate( $request, [
                'company_id'=>'required',
                'name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'province_no'=>'required',
                'district_no'=>'required',
                'local_address'=>'required',
            ] );

            DB::beginTransaction();
            try{

            $branch = Branch::create( [
                'company_id'=>$request['company_id'],
                'name'=>$request['name'],
                'email'=>$request['email'],
                'phone'=>$request['phone'],
                'province_no'=>$request['province_no'],
                'district_no'=>$request['district_no'],
                'local_address'=>$request['local_address'],
                'is_headoffice'=>0,
            ] );
            $branch->save();
            if(Auth::user()->id == 1){
                $usercompany = [
                    [
                        'user_id' => 1,
                        'company_id' => $request['company_id'],
                        'branch_id' => $branch['id'],
                    ],
                ];
            }else{
                $usercompany = [
                    [
                        'user_id' => 1,
                        'company_id' => $request['company_id'],
                        'branch_id' => $branch['id'],
                    ],
                    [
                        'user_id' => Auth::user()->id,
                        'company_id' => $request['company_id'],
                        'branch_id' => $branch['id'],
                    ],
                ];
            }

            UserCompany::insert($usercompany);


            ChildAccount::insert([
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "14",
                    "title"=>"Capital",
                    "slug"=>Str::slug("Capital"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "15",
                    "title"=>"Dividend",
                    "slug"=>Str::slug("Dividend"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "1",
                    "title"=>"Cash In Hand",
                    "slug"=>Str::slug("Cash In Hand"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "1",
                    "title"=>"Cash In Bank",
                    "slug"=>Str::slug("Cash In Bank"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "5",
                    "title"=>"Accounts Payable",
                    "slug"=>Str::slug("Accounts Payable"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "5",
                    "title"=>"Bank Overdraft",
                    "slug"=>Str::slug("Bank Overdraft"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Product Sales",
                    "slug"=>Str::slug("Product Sales"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Service Sales",
                    "slug"=>Str::slug("Service Sales"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "11",
                    "title"=>"Salary Expenses",
                    "slug"=>Str::slug("Salary Expenses"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "11",
                    "title"=>"Office Rent",
                    "slug"=>Str::slug("Office Rent"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Sales Margin",
                    "slug"=>Str::slug("Sales Margin"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "5",
                    "title"=>"Tax Payable",
                    "slug"=>Str::slug("Tax Payable"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "9",
                    "title"=>"Discount Received",
                    "slug"=>Str::slug("Discount Received"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "9",
                    "title"=>"Incoming Tax",
                    "slug"=>Str::slug("Incoming Tax"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "11",
                    "title"=>"Service Charge",
                    "slug"=>Str::slug("Service Charge"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "12",
                    "title"=>"Discount",
                    "slug"=>Str::slug("Discount"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "12",
                    "title"=>"Outgoing Tax",
                    "slug"=>Str::slug("Outgoing Tax"),
                ],
                [
                    "company_id" => $currentcomp->company_id,
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Service Income",
                    "slug"=>Str::slug("Service Income"),
                ],
            ]);
            DB::commit();
            return redirect()->route( 'branch.index' )->with( 'success', 'Branch Successfully created' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage);
        }
        }

    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Branch  $branch
    * @return \Illuminate\Http\Response
    */

    public function show( Branch $branch ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Branch  $branch
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $branch = Branch::findorfail( $id );
            $companies = Company::latest()->get();
            $provinces = Province::latest()->get();
            $district_group = District::latest()->get();
            $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
            return view( 'backend.branch.edit', compact( 'branch', 'companies', 'provinces', 'district_group', 'currentcomp' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Branch  $branch
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $branch = Branch::findorfail( $id );
        $this->validate( $request, [
            'company_id'=>'required',
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'province_no'=>'required',
            'district_no'=>'required',
            'local_address'=>'required',
        ] );
        DB::beginTransaction();
        try{
            $branch->update( [
                'company_id'=>$request['company_id'],
                'name'=>$request['name'],
                'email'=>$request['email'],
                'phone'=>$request['phone'],
                'province_no'=>$request['province_no'],
                'district_no'=>$request['district_no'],
                'local_address'=>$request['local_address'],
                'is_headoffice'=>0,
            ] );
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route( 'branch.index' )->with( 'success', 'Branch Successfully Updated' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Branch  $branch
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $branch = Branch::findorfail( $id );
            $usercompany = UserCompany::where('branch_id', $id)->get();
            foreach($usercompany as $uc){
                $uc->delete();
            }
            $branch->delete();

            $newusercompany = UserCompany::where('user_id', Auth::user()->id)->first();
            if($newusercompany){
                $newusercompany->update([
                    'user_id'=> Auth::user()->id,
                    'company_id'=>$newusercompany->company_id,
                    'branch_id'=>$newusercompany->branch_id,
                    'is_selected'=>1,
                ]);
            }else{
                return redirect()->route('logout')->with( 'success', 'Branch Successfully Deleted' );
            }

            return redirect()->route( 'branch.index' )->with( 'success', 'Branch Successfully Deleted' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
