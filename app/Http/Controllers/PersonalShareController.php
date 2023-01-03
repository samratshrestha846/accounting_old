<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\PersonalShare;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function App\NepaliCalender\datenep;
use Illuminate\Support\Facades\DB;

class PersonalShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->user()->can('manage-personal-shares'))
        {
            $personalshares = PersonalShare::latest()->paginate(10);
            return view('backend.personal_share.index', compact('personalshares'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request){
        $search = $request->input('search');

        $personalshares = PersonalShare::query()
            ->where('shareholder_name', 'LIKE', "%{$search}%")
            ->orWhere('quantity_kitta', 'LIKE', "%{$search}%")
            ->orWhere('total_amount', 'LIKE', "%{$search}%")
            ->orWhere('share_type', 'LIKE', "%{$search}%")
            ->paginate(10);

        return view('backend.personal_share.search', compact('personalshares'));
    }

    public function deletedPersonalShares(Request $request)
    {
        if($request->user()->can('manage-trash'))
        {
            $personalshares = PersonalShare::onlyTrashed()->latest()->paginate(10);
            return view('backend.trash.personal_share_trash', compact('personalshares'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('manage-personal-shares'))
        {
            $today = date('Y-m-d');
            $nepali_today = datenep($today);
            $provinces = Province::latest()->get();
            return view('backend.personal_share.create', compact('provinces', 'nepali_today'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
        [
            'shareholder_name' => 'required',
            'citizenship_no' => 'required',
            'citizenship_issue_date' => 'required',
            'citizenship' => 'required|mimes:jpg,png,jpeg',
            'identity_type' => 'required',
            'identity_document' => 'required|mimes:jpg,png,jpeg',
            'total_amount' => 'required',
            'quantity_kitta' => 'required',
            'share_type' => 'required',

            'grandfather' => 'required',
            'father' => 'required',
            'permanent_province_no' => 'required',
            'permanent_district_no' => 'required',
            'permanent_local_address' => 'required',
            'temporary_province_no' => 'required',
            'temporary_district_no' => 'required',
            'temporary_local_address' => 'required',
            'contact_no' => 'required',
            'email' => 'required',
            'occupation' => 'required',
            'marital_status' => 'required',
            'spouse_name' => '',
            'spouse_contact_no' => '',

            'benefitiary_name' => 'required',
            'benefitiary_permanent_province_no' => 'required',
            'benefitiary_permanent_district_no' => 'required',
            'benefitiary_permanent_local_address' => 'required',
            'benefitiary_temporary_province_no' => 'required',
            'benefitiary_temporary_district_no' => 'required',
            'benefitiary_temporary_local_address' => 'required',
            'benefitiary_contact_no' => 'required',
            'benefitiary_email' => 'required',
            'relationship' => 'required',
            'benefitiary_citizenship' => 'required|mimes:jpg,png,jpeg',
        ]);

        $shareholder_citizenship = '';
        $shareholder_identity = '';
        $benefitiary_citizenship = '';

        if($request->hasfile('citizenship')) {
            $citizenship = $request->file('citizenship');
            $shareholder_citizenship = $citizenship->store('shareholder_documents', 'uploads');
        }

        if($request->hasfile('identity_document')) {
            $identity = $request->file('identity_document');
            $shareholder_identity = $identity->store('shareholder_documents', 'uploads');
        }
        if($request->hasfile('benefitiary_citizenship')) {
            $benefitiary_citizenship_info = $request->file('benefitiary_citizenship');
            $benefitiary_citizenship = $benefitiary_citizenship_info->store('shareholder_documents', 'uploads');
        }
        DB::beginTransaction();
        try{

            PersonalShare::create([
                'shareholder_name' => $request['shareholder_name'],
                'citizenship_no' => $request['citizenship_no'] ,
                'citizenship_issue_date'=> $request['citizenship_issue_date'],
                'citizenship' => $shareholder_citizenship,
                'identity_type' => $request['identity_type'],
                'identity_document' => $shareholder_identity,
                'total_amount' => $request['total_amount'],
                'quantity_kitta' => $request['quantity_kitta'],
                'share_type' => $request['share_type'],

                'grandfather' => $request['grandfather'],
                'father' => $request['father'],
                'permanent_province_no' => $request['permanent_province_no'],
                'permanent_district_no' => $request['permanent_district_no'],
                'permanent_local_address' => $request['permanent_local_address'],
                'temporary_province_no' => $request['temporary_province_no'],
                'temporary_district_no' => $request['temporary_district_no'],
                'temporary_local_address' => $request['temporary_local_address'],
                'contact_no' => $request['contact_no'],
                'email' => $request['email'],
                'occupation' => $request['occupation'],
                'marital_status' => $request['marital_status'],
                'spouse_name' => $request['spouse_name'],
                'spouse_contact_no' => $request['spouse_contact_no'],

                'benefitiary_name' => $request['benefitiary_name'],
                'benefitiary_permanent_province_no' => $request['benefitiary_permanent_province_no'],
                'benefitiary_permanent_district_no' => $request['benefitiary_permanent_district_no'],
                'benefitiary_permanent_local_address' => $request['benefitiary_permanent_local_address'],
                'benefitiary_temporary_province_no' => $request['benefitiary_temporary_province_no'],
                'benefitiary_temporary_district_no' => $request['benefitiary_temporary_district_no'],
                'benefitiary_temporary_local_address' => $request['benefitiary_temporary_local_address'],
                'benefitiary_contact_no' => $request['benefitiary_contact_no'],
                'benefitiary_email' => $request['benefitiary_email'],
                'relationship' => $request['relationship'],
                'benefitiary_citizenship' => $benefitiary_citizenship,
            ]);
            DB::commit();
            return redirect()->route('personal_share.index')->with('success', 'Personal Share information is saved successfully.');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PersonalShare  $personalShare
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if($request->user()->can('manage-personal-shares'))
        {
            $personal_share = PersonalShare::findorFail($id);

            return view('backend.personal_share.show', compact('personal_share'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PersonalShare  $personalShare
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->user()->can('manage-personal-shares'))
        {
            $personal_share = PersonalShare::findorFail($id);
            $provinces = Province::latest()->get();
            $districts = District::where('province_id', $personal_share->permanent_province_no)->latest()->get();
            $temporary_districts = District::where('province_id', $personal_share->temporary_province_no)->latest()->get();
            $benefitiary_permanent_districts = District::where('province_id', $personal_share->benefitiary_permanent_province_no)->latest()->get();
            $benefitiary_temporary_districts = District::where('province_id', $personal_share->benefitiary_temporary_province_no)->latest()->get();
            return view('backend.personal_share.edit', compact('personal_share', 'provinces', 'districts', 'temporary_districts', 'benefitiary_permanent_districts', 'benefitiary_temporary_districts'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PersonalShare  $personalShare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $personal_share = PersonalShare::findorFail($id);

        $this->validate($request,
        [
            'shareholder_name' => 'required',
            'citizenship_no' => 'required',
            'citizenship_issue_date' => 'required',
            'citizenship' => 'mimes:jpg,png,jpeg',
            'identity_type' => 'required',
            'identity_document' => 'mimes:jpg,png,jpeg',
            'total_amount' => 'required',
            'quantity_kitta' => 'required',
            'share_type' => 'required',

            'grandfather' => 'required',
            'father' => 'required',
            'permanent_province_no' => 'required',
            'permanent_district_no' => 'required',
            'permanent_local_address' => 'required',
            'temporary_province_no' => 'required',
            'temporary_district_no' => 'required',
            'temporary_local_address' => 'required',
            'contact_no' => 'required',
            'email' => 'required',
            'occupation' => 'required',
            'marital_status' => 'required',
            'spouse_name' => '',
            'spouse_contact_no' => '',

            'benefitiary_name' => 'required',
            'benefitiary_permanent_province_no' => 'required',
            'benefitiary_permanent_district_no' => 'required',
            'benefitiary_permanent_local_address' => 'required',
            'benefitiary_temporary_province_no' => 'required',
            'benefitiary_temporary_district_no' => 'required',
            'benefitiary_temporary_local_address' => 'required',
            'benefitiary_contact_no' => 'required',
            'benefitiary_email' => 'required',
            'relationship' => 'required',
            'benefitiary_citizenship' => 'mimes:jpg,png,jpeg',
        ]);

        $shareholder_citizenship = '';
        $shareholder_identity = '';
        $benefitiary_citizenship = '';

        if($request->hasfile('citizenship')) {
            Storage::disk('uploads')->delete($personal_share->citizenship);
            $citizenship = $request->file('citizenship');
            $shareholder_citizenship = $citizenship->store('shareholder_documents', 'uploads');
        } else {
            $shareholder_citizenship = $personal_share->citizenship;
        }

        if($request->hasfile('identity_document')) {
            Storage::disk('uploads')->delete($personal_share->identity_document);
            $identity = $request->file('identity_document');
            $shareholder_identity = $identity->store('shareholder_documents', 'uploads');
        } else {
            $shareholder_identity = $personal_share->identity_document;
        }

        if($request->hasfile('benefitiary_citizenship')) {
            Storage::disk('uploads')->delete($personal_share->benefitiary_citizenship);
            $benefitiary_citizenship_info = $request->file('benefitiary_citizenship');
            $benefitiary_citizenship = $benefitiary_citizenship_info->store('shareholder_documents', 'uploads');
        } else {
            $benefitiary_citizenship = $personal_share->benefitiary_citizenship;
        }
        DB::beginTransaction();
        try{

            $personal_share->update([
                'shareholder_name' => $request['shareholder_name'],
                'citizenship_no' => $request['citizenship_no'] ,
                'citizenship_issue_date'=> $request['citizenship_issue_date'],
                'citizenship' => $shareholder_citizenship,
                'identity_type' => $request['identity_type'],
                'identity_document' => $shareholder_identity,
                'total_amount' => $request['total_amount'],
                'quantity_kitta' => $request['quantity_kitta'],
                'share_type' => $request['share_type'],

                'grandfather' => $request['grandfather'],
                'father' => $request['father'],
                'permanent_province_no' => $request['permanent_province_no'],
                'permanent_district_no' => $request['permanent_district_no'],
                'permanent_local_address' => $request['permanent_local_address'],
                'temporary_province_no' => $request['temporary_province_no'],
                'temporary_district_no' => $request['temporary_district_no'],
                'temporary_local_address' => $request['temporary_local_address'],
                'contact_no' => $request['contact_no'],
                'email' => $request['email'],
                'occupation' => $request['occupation'],
                'marital_status' => $request['marital_status'],
                'spouse_name' => $request['spouse_name'],
                'spouse_contact_no' => $request['spouse_contact_no'],

                'benefitiary_name' => $request['benefitiary_name'],
                'benefitiary_permanent_province_no' => $request['benefitiary_permanent_province_no'],
                'benefitiary_permanent_district_no' => $request['benefitiary_permanent_district_no'],
                'benefitiary_permanent_local_address' => $request['benefitiary_permanent_local_address'],
                'benefitiary_temporary_province_no' => $request['benefitiary_temporary_province_no'],
                'benefitiary_temporary_district_no' => $request['benefitiary_temporary_district_no'],
                'benefitiary_temporary_local_address' => $request['benefitiary_temporary_local_address'],
                'benefitiary_contact_no' => $request['benefitiary_contact_no'],
                'benefitiary_email' => $request['benefitiary_email'],
                'relationship' => $request['relationship'],
                'benefitiary_citizenship' => $benefitiary_citizenship,
            ]);
            DB::commit();
            return redirect()->route('personal_share.index')->with('success', 'Personal Share information is updated successfully.');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonalShare  $personalShare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->can('manage-personal-shares'))
        {
            $personal_share = PersonalShare::findorFail($id);
            $personal_share->delete();

            return redirect()->route('personal_share.index')->with('success', 'Personal Share deleted successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restorePersonalShare(Request $request, $id)
    {
        if($request->user()->can('manage-trash'))
        {
            $deleted_share = PersonalShare::onlyTrashed()->findorFail($id);
            $deleted_share->restore();

            return redirect()->route('personal_share.index')->with('success', 'Personal Share restored successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }
}
