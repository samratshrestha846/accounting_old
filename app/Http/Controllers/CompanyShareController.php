<?php

namespace App\Http\Controllers;

use App\Models\CompanyShare;
use App\Models\CompanyShareHolders;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function App\NepaliCalender\datenep;
use Illuminate\Support\Facades\DB;

class CompanyShareController extends Controller {
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
        if ( $request->user()->can( 'manage-company-shares' ) ) {
            $companyshares = CompanyShare::latest()->paginate( 10 );
            return view( 'backend.company_share.index', compact( 'companyshares' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $companyshares = CompanyShare::query()
        ->where( 'company_name', 'LIKE', "%{$search}%" )
        ->orWhere( 'quantity_kitta', 'LIKE', "%{$search}%" )
        ->orWhere( 'total_amount', 'LIKE', "%{$search}%" )
        ->orWhere( 'share_type', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );
        // dd( $companyshares );

        return view( 'backend.company_share.search', compact( 'companyshares' ) );
    }

    public function deletedcompanyShares( Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $companyshares = CompanyShare::onlyTrashed()->latest()->paginate();
            return view( 'backend.trash.company_share_trash', compact( 'companyshares' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-company-shares' ) ) {
            $today = date( 'Y-m-d' );
            $nepali_today = datenep( $today );
            $provinces = Province::latest()->get();
            return view( 'backend.company_share.create', compact( 'provinces', 'nepali_today' ) );
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
        $this->validate( $request, [
            'total_amount' => 'required',
            'quantity_kitta' => 'required',
            'share_type' => 'required',

            'company_name' => 'required',
            'company_registration_no' => 'required',
            'pan_vat_no' => 'required',
            'registration_date' => 'required',
            'registered_province_no' => 'required',
            'registered_district_no' => 'required',
            'registered_local_address' => 'required',
            'company_contact_no' => 'required',
            'company_email' => 'required',
            'company_capital' => 'required',
            'company_paidup_capital' => 'required',
            'work_details' => 'required',

            'request_person_name' => 'required',
            'designation' => 'required',
            'request_contact_no' => 'required',
            'request_email' => 'required',

            'registration_documents' => 'required',
            'pan_vat_document' => 'required',
            'request_person_citizenship' => 'required',
            'last_year_audit_report' => 'required',
            'project_field_report' => 'required',

            'shareholder_name' => '',
            'shareholder_contact' => '',
            'shareholder_email' => '',
        ] );

        $registration_documents = '';
        $pan_vat_document = '';
        $request_person_citizenship = '';
        $last_year_audit_report = '';
        $project_field_report = '';

        if ( $request->hasfile( 'registration_documents' ) ) {
            $registration = $request->file( 'registration_documents' );
            $registration_documents = $registration->store( 'shareholder_documents', 'uploads' );
        }

        if ( $request->hasfile( 'pan_vat_document' ) ) {
            $identity = $request->file( 'pan_vat_document' );
            $pan_vat_document = $identity->store( 'shareholder_documents', 'uploads' );
        }

        if ( $request->hasfile( 'request_person_citizenship' ) ) {
            $request_person_citizenship_info = $request->file( 'request_person_citizenship' );
            $request_person_citizenship = $request_person_citizenship_info->store( 'shareholder_documents', 'uploads' );
        }

        if ( $request->hasfile( 'last_year_audit_report' ) ) {
            $audit_report = $request->file( 'last_year_audit_report' );
            $last_year_audit_report = $audit_report->store( 'shareholder_documents', 'uploads' );
        }
        if ( $request->hasfile( 'project_field_report' ) ) {
            $project_field_report_info = $request->file( 'project_field_report' );
            $project_field_report = $project_field_report_info->store( 'shareholder_documents', 'uploads' );
        }
        DB::beginTransaction();
        try{

            $companyShare = CompanyShare::create( [
                'total_amount' => $request['total_amount'],
                'quantity_kitta' => $request['quantity_kitta'],
                'share_type' => $request['share_type'],

                'company_name' => $request['company_name'],
                'company_registration_no' => $request['company_registration_no'],
                'pan_vat_no' => $request['pan_vat_no'],
                'registration_date' => $request['registration_date'],
                'registered_province_no' => $request['registered_province_no'],
                'registered_district_no' => $request['registered_district_no'],
                'registered_local_address' => $request['registered_local_address'],
                'company_contact_no' => $request['company_contact_no'],
                'company_email' => $request['company_email'],
                'company_capital' => $request['company_capital'],
                'company_paidup_capital' => $request['company_paidup_capital'],
                'work_details' => $request['work_details'],

                'request_person_name' => $request['request_person_name'],
                'designation' => $request['designation'],
                'request_contact_no' => $request['request_contact_no'],
                'request_email' => $request['request_email'],

                'registration_documents' => $registration_documents,
                'pan_vat_document' => $pan_vat_document,
                'request_person_citizenship' => $request_person_citizenship,
                'last_year_audit_report' => $last_year_audit_report,
                'project_field_report' => $project_field_report,
            ] );

            $companyShare->save();

            for ( $i = 0; $i < count( $request->shareholder_name );
            $i++ ) {
                if ( $request->shareholder_name[$i] == null ) {

                } else {
                    $shareholder_info = CompanyShareHolders::create( [
                        'company_share_id' => $companyShare['id'],
                        'shareholder_name' => $request->shareholder_name[$i],
                        'shareholder_email' => $request->shareholder_email[$i],
                        'shareholder_contact' => $request->shareholder_contact[$i],
                    ] );

                    $shareholder_info->save();
                }
            }
            DB::commit();
            return redirect()->route( 'company_share.index' )->with( 'success', 'Share information successfully inserted' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\CompanyShare  $companyShare
    * @return \Illuminate\Http\Response
    */

    public function show( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-shares' ) ) {
            $companyShare = CompanyShare::findorFail( $id );
            $province = Province::where( 'id', $companyShare->registered_province_no )->first();
            $district = District::where( 'id', $companyShare->registered_district_no )->first();
            $company_shareholders = CompanyShareHolders::where( 'company_share_id', $companyShare->id )->latest()->get();
            return view( 'backend.company_share.show', compact( 'companyShare', 'province', 'district', 'company_shareholders' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\CompanyShare  $companyShare
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-shares' ) ) {
            $companyShare = CompanyShare::findorFail( $id );
            $company_shareholders = CompanyShareHolders::where( 'company_share_id', $companyShare->id )->latest()->get();
            $provinces = Province::latest()->get();
            $districts = District::where( 'province_id', $companyShare->registered_province_no )->latest()->get();

            return view( 'backend.company_share.edit', compact( 'companyShare', 'company_shareholders', 'provinces', 'districts' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\CompanyShare  $companyShare
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $companyShare = CompanyShare::findorFail( $id );
        $company_shareholders = CompanyShareHolders::where( 'company_share_id', $companyShare->id )->latest()->get();
        foreach ( $company_shareholders as $shareholder ) {
            $shareholder->forceDelete();
        }

        $this->validate( $request, [
            'total_amount' => 'required',
            'quantity_kitta' => 'required',
            'share_type' => 'required',

            'company_name' => 'required',
            'company_registration_no' => 'required',
            'pan_vat_no' => 'required',
            'registration_date' => 'required',
            'registered_province_no' => 'required',
            'registered_district_no' => 'required',
            'registered_local_address' => 'required',
            'company_contact_no' => 'required',
            'company_email' => 'required',
            'company_capital' => 'required',
            'company_paidup_capital' => 'required',
            'work_details' => 'required',

            'request_person_name' => 'required',
            'designation' => 'required',
            'request_contact_no' => 'required',
            'request_email' => 'required',

            'registration_documents' => '',
            'pan_vat_document' => '',
            'request_person_citizenship' => '',
            'last_year_audit_report' => '',
            'project_field_report' => '',

            'shareholder_name' => '',
            'shareholder_contact' => '',
            'shareholder_email' => '',
        ] );

        $registration_documents = '';
        $pan_vat_document = '';
        $request_person_citizenship = '';
        $last_year_audit_report = '';
        $project_field_report = '';

        if ( $request->hasfile( 'registration_documents' ) ) {
            Storage::disk( 'uploads' )->delete( $companyShare->registration_documents );
            $registration = $request->file( 'registration_documents' );
            $registration_documents = $registration->store( 'shareholder_documents', 'uploads' );
        } else {
            $registration_documents = $companyShare->registration_documents;
        }

        if ( $request->hasfile( 'pan_vat_document' ) ) {
            Storage::disk( 'uploads' )->delete( $companyShare->pan_vat_document );
            $identity = $request->file( 'pan_vat_document' );
            $pan_vat_document = $identity->store( 'shareholder_documents', 'uploads' );
        } else {
            $pan_vat_document = $companyShare->pan_vat_document;
        }

        if ( $request->hasfile( 'request_person_citizenship' ) ) {
            Storage::disk( 'uploads' )->delete( $companyShare->request_person_citizenship );
            $request_person_citizenship_info = $request->file( 'request_person_citizenship' );
            $request_person_citizenship = $request_person_citizenship_info->store( 'shareholder_documents', 'uploads' );
        } else {
            $request_person_citizenship = $companyShare->request_person_citizenship;
        }

        if ( $request->hasfile( 'last_year_audit_report' ) ) {
            Storage::disk( 'uploads' )->delete( $companyShare->last_year_audit_report );
            $audit_report = $request->file( 'last_year_audit_report' );
            $last_year_audit_report = $audit_report->store( 'shareholder_documents', 'uploads' );
        } else {
            $last_year_audit_report = $companyShare->last_year_audit_report;
        }

        if ( $request->hasfile( 'project_field_report' ) ) {
            Storage::disk( 'uploads' )->delete( $companyShare->project_field_report );
            $project_field_report_info = $request->file( 'project_field_report' );
            $project_field_report = $project_field_report_info->store( 'shareholder_documents', 'uploads' );
        } else {
            $project_field_report = $companyShare->project_field_report;
        }
        DB::beginTransaction();
        try{
            $companyShare->update( [
                'total_amount' => $request['total_amount'],
                'quantity_kitta' => $request['quantity_kitta'],
                'share_type' => $request['share_type'],

                'company_name' => $request['company_name'],
                'company_registration_no' => $request['company_registration_no'],
                'pan_vat_no' => $request['pan_vat_no'],
                'registration_date' => $request['registration_date'],
                'registered_province_no' => $request['registered_province_no'],
                'registered_district_no' => $request['registered_district_no'],
                'registered_local_address' => $request['registered_local_address'],
                'company_contact_no' => $request['company_contact_no'],
                'company_email' => $request['company_email'],
                'company_capital' => $request['company_capital'],
                'company_paidup_capital' => $request['company_paidup_capital'],
                'work_details' => $request['work_details'],

                'request_person_name' => $request['request_person_name'],
                'designation' => $request['designation'],
                'request_contact_no' => $request['request_contact_no'],
                'request_email' => $request['request_email'],

                'registration_documents' => $registration_documents,
                'pan_vat_document' => $pan_vat_document,
                'request_person_citizenship' => $request_person_citizenship,
                'last_year_audit_report' => $last_year_audit_report,
                'project_field_report' => $project_field_report,
            ] );

            for ( $i = 0; $i < count( $request->shareholder_name ); $i++ ) {
                if ( $request->shareholder_name[$i] != null ) {
                    $shareholder_info = CompanyShareHolders::create( [
                        'company_share_id' => $companyShare['id'],
                        'shareholder_name' => $request->shareholder_name[$i],
                        'shareholder_email' => $request->shareholder_email[$i],
                        'shareholder_contact' => $request->shareholder_contact[$i],
                    ] );

                    $shareholder_info->save();
                }
            }
            DB::commit();
            return redirect()->route( 'company_share.index' )->with( 'success', 'Share information successfully updated' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\CompanyShare  $companyShare
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id ) {
        if ( $request->user()->can( 'manage-company-shares' ) ) {
            $companyShare = CompanyShare::findorFail( $id );
            $shareholders = CompanyShareHolders::where( 'company_share_id', $companyShare->id )->latest()->get();

            foreach ( $shareholders as $shareholder ) {
                $shareholder->delete();
            }
            $companyShare->delete();

            return redirect()->route( 'company_share.index' )->with( 'success', 'Company Share restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restorecompanyShare( Request $request, $id ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $deleted_share = CompanyShare::onlyTrashed()->findorFail( $id );
            $deleted_share_holders = CompanyShareHolders::onlyTrashed()->where( 'company_share_id', $deleted_share->id )->get();

            foreach ( $deleted_share_holders as $shareholder ) {
                $shareholder->restore();
            }
            $deleted_share->restore();

            return redirect()->route( 'company_share.index' )->with( 'success', 'Company Share restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
