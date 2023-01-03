<?php

namespace App\Http\Controllers;

use App\Models\DealerType;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class DealerTypeController extends Controller
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
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $dealerTypes = DealerType::latest()->paginate( 10 );
            return view( 'backend.dealertypes.index', compact( 'dealerTypes' ) );
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
        if ( $request->user()->can( 'manage-dealer' ) ) {
            return view( 'backend.dealertypes.create' );
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
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $data = $this->validate( $request, [
                'title'=>'required',
                'percent'=>'required',
                'make_user'=>'required',
            ] );

            $dealerType = DealerType::create( [
                'title'=>$data['title'],
                'percent'=>$data['percent'],
                'make_user'=>$data['make_user'],
            ] );
            $dealerType->save();
            return redirect()->route( 'dealertype.index' )->with( 'success', 'DealerType Successfully Created' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DealerType  $dealerType
     * @return \Illuminate\Http\Response
     */
    public function show(DealerType $dealerType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DealerType  $dealerType
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $dealerType = DealerType::findorfail($id);
            return view( 'backend.dealertypes.edit', compact( 'dealerType' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DealerType  $dealerType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $dealerType = DealerType::findorfail($id);
            $data = $this->validate( $request, [
                'title'=>'required',
                'percent'=>'required',
                'make_user'=>'required',
            ] );

            $dealerType->update( [
                'title'=>$data['title'],
                'percent'=>$data['percent'],
                'make_user'=>$data['make_user'],
            ] );
            $dealerType->save();
            return redirect()->route( 'dealertype.index' )->with( 'success', 'DealerType Successfully Updated' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DealerType  $dealerType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ( $request->user()->can( 'manage-dealer' ) ) {
            $dealerType = DealerType::findorfail($id);
            $dealerclient = Client::where('dealer_type_id', $id)->count();
            if($dealerclient>0){
                return redirect()->route( 'dealertype.index' )->with( 'error', 'DealerType cannot be deleted. Client is linked with this Type' );
            }else{
                $dealerType->delete();
                return redirect()->route( 'dealertype.index' )->with( 'success', 'DealerType Successfully Deleted' );
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function mark_as_default(Request $request)
    {
        $dealer_default = DealerType::where('is_default',1)->first();
        if($dealer_default){
            $dealer_default->is_default = 0;
            $dealer_default->save();
        }

        if($request->id != 0){
            $dealer = DealerType::find($request->id);
            $dealer->is_default = 1;
            $dealer->save();
        }

        $response = array('status' => 'success','message' => 'Mark as Default Added');
        echo(json_encode($response));
    }
}
