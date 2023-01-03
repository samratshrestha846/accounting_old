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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller {
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
            $companies = Company::latest()->where('id', $currentcomp->company_id)->paginate( 10 );
            return view( 'backend.company.index', compact( 'companies' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $companies = Company::query()
        ->where( 'name', 'LIKE', "%{$search}%" )
        ->orWhere( 'local_address', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.company.search', compact( 'companies' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $provinces = Province::latest()->get();
            $district_group = District::latest()->get();
            $company_count = Company::all()->count();
            $super_setting = SuperSetting::first();
            if ( $company_count >= $super_setting->company_limit ) {
                return redirect()->route( 'company.index' )->with( 'error', 'You have exceeded company limitations. Cannot create new company.' );
            }
            return view( 'backend.company.create', compact( 'provinces', 'district_group' ) );
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
        $compcount = Company::count();
        $super_setting = SuperSetting::first();
        if ( $compcount >= $super_setting->company_limit ) {
            return redirect()->route( 'company.index' )->with( 'error', 'You have already met the limit for creating Company' );
        } else {
            $data = $this->validate( $request, [
                'name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'province_no'=>'required',
                'district_no'=>'required',
                'local_address'=>'required',
                'pan_vat'=>'required',
                'is_importer'=>'',
                'registration_no'=>'required',
                'company_logo'=>'required|mimes:png,jpg,jpeg',
                'ird_sync'=> '',
                'invoice_color'=>'',
            ]);

            $imagename = '';
            if ( $request->hasfile( 'company_logo' ) ) {
                $image = $request->file( 'company_logo' );
                $imagename = $image->store( 'company_logo', 'uploads' );
            }

            if ( $request['is_importer'] == null ) {
                $importer = 0;
            } else {
                $importer = $request['is_importer'];
            }
            if ( $request['ird_sync'] == null ) {
                $ird_sync = '0';
            } else {
                $ird_sync = $request['ird_sync'];
            }

            DB::beginTransaction();
            try{


                $company = Company::create( [
                    'name'=>$data['name'],
                    'email'=>$data['email'],
                    'phone'=>$data['phone'],
                    'province_no'=>$data['province_no'],
                    'district_no'=>$data['district_no'],
                    'local_address'=>$data['local_address'],
                    'pan_vat' => $data['pan_vat'],
                    'registration_no' => $data['registration_no'],
                    'company_logo'=>$imagename,
                    'is_importer' => $importer,
                    'invoice_color' => $data['invoice_color'],
                ]);

                // $company->save();

                $branch = Branch::create([
                    'company_id'=>$company['id'],
                    'name'=>$data['name'],
                    'email'=>$data['email'],
                    'phone'=>$data['phone'],
                    'province_no'=>$data['province_no'],
                    'district_no'=>$data['district_no'],
                    'local_address'=>$data['local_address'],
                    'is_headoffice'=>1,
                ]);

                $branch->save();
                if(Auth::user()->id == 1){
                    $usercompany = [
                        [
                            'user_id' => 1,
                            'company_id' => $company['id'],
                            'branch_id' => $branch['id'],
                        ]
                        ];
                }else{
                    $usercompany = [
                        [
                            'user_id' => 1,
                            'company_id' => $company['id'],
                            'branch_id' => $branch['id'],
                        ],
                        [
                            'user_id' => Auth::user()->id,
                            'company_id' => $company['id'],
                            'branch_id' => $branch['id'],
                        ],

                    ];

                }
                UserCompany::insert($usercompany);


                ChildAccount::insert([
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "14",
                        "title"=>"Capital",
                        "slug"=>Str::slug("Capital"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "15",
                        "title"=>"Dividend",
                        "slug"=>Str::slug("Dividend"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "1",
                        "title"=>"Cash In Hand",
                        "slug"=>Str::slug("Cash In Hand"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "1",
                        "title"=>"Cash In Bank",
                        "slug"=>Str::slug("Cash In Bank"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "5",
                        "title"=>"Accounts Payable",
                        "slug"=>Str::slug("Accounts Payable"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "5",
                        "title"=>"Bank Overdraft",
                        "slug"=>Str::slug("Bank Overdraft"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "8",
                        "title"=>"Product Sales",
                        "slug"=>Str::slug("Product Sales"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "8",
                        "title"=>"Service Sales",
                        "slug"=>Str::slug("Service Sales"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "11",
                        "title"=>"Salary Expenses",
                        "slug"=>Str::slug("Salary Expenses"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "11",
                        "title"=>"Office Rent",
                        "slug"=>Str::slug("Office Rent"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "8",
                        "title"=>"Sales Margin",
                        "slug"=>Str::slug("Sales Margin"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "5",
                        "title"=>"Tax Payable",
                        "slug"=>Str::slug("Tax Payable"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "9",
                        "title"=>"Discount Received",
                        "slug"=>Str::slug("Discount Received"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "9",
                        "title"=>"Incoming Tax",
                        "slug"=>Str::slug("Incoming Tax"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "11",
                        "title"=>"Service Charge",
                        "slug"=>Str::slug("Service Charge"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "12",
                        "title"=>"Discount Given",
                        "slug"=>Str::slug("Discount Given"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "12",
                        "title"=>"Outgoing Tax",
                        "slug"=>Str::slug("Outgoing Tax"),
                    ],
                    [
                        "company_id" => $company['id'],
                        "branch_id" => $branch['id'],
                        "sub_account_id" => "8",
                        "title"=>"Service Income",
                        "slug"=>Str::slug("Service Income"),
                    ]
                ]);
                DB::commit();
                return redirect()->route( 'company.index' )->with( 'success', 'Company Successfully created.' );
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
            UserCompany::insert($usercompany);


            ChildAccount::insert([
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "14",
                    "title"=>"Capital",
                    "slug"=>Str::slug("Capital"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "15",
                    "title"=>"Dividend",
                    "slug"=>Str::slug("Dividend"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "1",
                    "title"=>"Cash In Hand",
                    "slug"=>Str::slug("Cash In Hand"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "1",
                    "title"=>"Cash In Bank",
                    "slug"=>Str::slug("Cash In Bank"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "5",
                    "title"=>"Accounts Payable",
                    "slug"=>Str::slug("Accounts Payable"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "5",
                    "title"=>"Bank Overdraft",
                    "slug"=>Str::slug("Bank Overdraft"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Product Sales",
                    "slug"=>Str::slug("Product Sales"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Service Sales",
                    "slug"=>Str::slug("Service Sales"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "11",
                    "title"=>"Salary Expenses",
                    "slug"=>Str::slug("Salary Expenses"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "11",
                    "title"=>"Office Rent",
                    "slug"=>Str::slug("Office Rent"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Sales Margin",
                    "slug"=>Str::slug("Sales Margin"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "5",
                    "title"=>"Tax Payable",
                    "slug"=>Str::slug("Tax Payable"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "9",
                    "title"=>"Discount Received",
                    "slug"=>Str::slug("Discount Received"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "9",
                    "title"=>"Incoming Tax",
                    "slug"=>Str::slug("Incoming Tax"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "11",
                    "title"=>"Service Charge",
                    "slug"=>Str::slug("Service Charge"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "12",
                    "title"=>"Discount",
                    "slug"=>Str::slug("Discount"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "12",
                    "title"=>"Outgoing Tax",
                    "slug"=>Str::slug("Outgoing Tax"),
                ],
                [
                    "company_id" => $company['id'],
                    "branch_id" => $branch['id'],
                    "sub_account_id" => "8",
                    "title"=>"Service Income",
                    "slug"=>Str::slug("Service Income"),
                ]
            ]);
            return redirect()->route( 'company.index' )->with( 'success', 'Company Successfully created.' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $company = Company::findorfail( $id );
            $provinces = Province::latest()->get();
            $district_group = District::latest()->get();
            return view( 'backend.company.edit', compact( 'company', 'provinces', 'district_group' ) );
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {

        $company = Company::findorfail( $id );
        $data = $this->validate( $request, [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'province_no'=>'required',
            'district_no'=>'required',
            'local_address'=>'required',
            'pan_vat'=>'required',
            'is_importer' => '',
            'registration_no'=>'required',
            'company_logo'=>'mimes:png,jpg,jpeg',
            'ird_sync'=>'',
            'invoice_color'=>'',
        ] );

        $imagename = '';
        if ( $request->hasfile( 'company_logo' ) ) {
            $image = $request->file( 'company_logo' );
            $imagename = $image->store( 'company_logo', 'uploads' );
        } else {
            $imagename = $company->company_logo;
        }

        if ( $request['is_importer'] == null ) {
            $importer = 0;
        } else {
            $importer = $request['is_importer'];
        }

        if ( $request['ird_sync'] == null ) {
            $ird_sync = '0';
        } else {
            $ird_sync = $request['ird_sync'];
        }

        DB::beginTransaction();
        try{


            $company->update( [
                'name'=>$data['name'],
                'email'=>$data['email'],
                'phone'=>$data['phone'],
                'province_no'=>$data['province_no'],
                'district_no'=>$data['district_no'],
                'local_address'=>$data['local_address'],
                'pan_vat' => $data['pan_vat'],
                'is_importer' => $importer,
                'registration_no' => $data['registration_no'],
                'company_logo'=>$imagename,
                'ird_sync' => $ird_sync,
                'invoice_color'=>$data['invoice_color'],
            ] );

            $company->save();
            $branch = Branch::where( 'company_id', $id )->where( 'is_headoffice', 1 )->first();

            $branch->update( [
                'company_id'=>$id,
                'name'=>$data['name'],
                'email'=>$data['email'],
                'phone'=>$data['phone'],
                'province_no'=>$data['province_no'],
                'district_no'=>$data['district_no'],
                'local_address'=>$data['local_address'],
                'is_headoffice'=>1,
            ] );
            DB::commit();
            return redirect()->route( 'company.index' )->with( 'success', 'Company Successfully Updated' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-setting' ) ) {
            $company = Company::findorfail( $id );
            $usercompany = UserCompany::where('company_id', $id)->get();
            foreach($usercompany as $uc){
                $uc->delete();
            }
            $company->delete();

            $newusercompany = UserCompany::where('user_id', Auth::user()->id)->first();
            if($newusercompany){
                $newusercompany->update([
                    'user_id'=> Auth::user()->id,
                    'company_id'=>$newusercompany->company_id,
                    'branch_id'=>$newusercompany->branch_id,
                    'is_selected'=>1,
                ]);
            }else{
                return redirect()->route('logout')->with( 'success', 'Company Successfully Deleted' );
            }

            return redirect()->route( 'company.index' )->with( 'success', 'Company Successfully Deleted' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
