<?php

namespace App\Http\Controllers;

use App\Models\DealerUser;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DealerUserCompany;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DealerUserController extends Controller
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

    public function index(Request $request, $id)
    {
        //
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $client = Client::with('dealer_users')->where('id',$id)->first();
            return view('backend.dealeruser.index', compact('client'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        //
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $client = Client::with('dealer_users')->where('id', $id)->first();
            return view('backend.dealeruser.create', compact('client'));
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
    public function store(Request $request)
    {
        //
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $data = $this->validate($request, [
                'client_id'=>'required|integer',
                'name'=>'required',
                'email'=>'required|email|unique:dealer_users',
                'password'=>'required|min:8|confirmed',
            ]);
            $user = Auth::user()->id;
            $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
            DB::beginTransaction();
            try{
                $dealeruser = DealerUser::create([
                    'client_id' => $data['client_id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);

                $dealerusercompany = DealerUserCompany::create([
                    'dealer_user_id' => $dealeruser['id'],
                    'company_id' => $currentcomp->company_id,
                    'branch_id'=>$currentcomp->branch_id,
                    'is_selected'=>1,
                ]);

                $dealerusercompany->save();
                DB::commit();
                return redirect()->route('clientuser.index', $data['client_id'])->with('success', 'Dealer User Successfully Created');
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DealerUser  $dealerUser
     * @return \Illuminate\Http\Response
     */
    public function show(DealerUser $dealerUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DealerUser  $dealerUser
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $dealerUser = DealerUser::findorfail($id);
            return view('backend.dealeruser.edit',compact('dealerUser'));
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DealerUser  $dealerUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        try{
            if ( $request->user()->can( 'manage-dealer' ) ) {
                $dealeruser = DealerUser::findorfail($id);
                if(isset($_POST['updatedetails'])){
                    $data = $this->validate($request, [
                        'client_id'=>'required',
                        'name'=>'required|string|max:255',
                        'email'=>'required|string|email|max:255|unique:dealer_users,email,'.$dealeruser->id,
                    ]);
                    $dealeruser->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                    ]);
                    $dealeruser->save();
                    DB::commit();
                    return redirect()->route('clientuser.index', $dealeruser->client_id)->with('success', 'Dealer UserDetails Successfully updated');
                }
                elseif(isset($_POST['updatepassword']))
                {
                    $data = $this->validate($request, [
                        'new_password' => 'sometimes|min:8|confirmed|different:password',
                    ]);

                    if (!Hash::check($data['new_password'] , $dealeruser->password)) {
                        $newpass = Hash::make($data['new_password']);

                        $dealeruser->update([
                            'password' => $newpass,
                        ]);
                        $dealeruser->save;
                        DB::commit();
                        session()->flash('success','Password updated successfully');
                        return redirect()->route('clientuser.index', $dealeruser->client_id);
                    }

                    else
                    {
                        session()->flash('error','New password can not be the old password!');
                        return redirect()->back();
                    }
                }
            } else {
                return view( 'backend.permission.permission' );
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DealerUser  $dealerUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $dealeruser = DealerUser::findorfail($id);
            $dealeruser->delete();
            return redirect()->route('clientuser.index', $dealeruser->client_id)->with('success', "User Successfully Deleted");
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
