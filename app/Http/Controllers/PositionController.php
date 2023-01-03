<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PositionController extends Controller {
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
        if ( $request->user()->can( 'manage-positions' ) ) {
            $positions = Position::latest()->paginate( 10 );
            return view( 'backend.positions.index', compact( 'positions' ) );
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
        if ( $request->user()->can( 'manage-positions' ) ) {
            return view( 'backend.positions.create' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $positions = Position::query()
        ->where( 'name', 'LIKE', "%{$search}%" )
        ->orWhere( 'slug', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.positions.search', compact( 'positions' ) );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $data = $this->validate( $request, [
            'name' => 'required'
        ] );

        $positionexist = Position::where( 'name', $request['name'] )->first();

        if ( $positionexist ) {
            return redirect()->back()->with( 'danger', 'Position already exists.' );
        } else {
            $position = Position::create( [
                'name' => $data['name'],
                'slug' => Str::slug( $data['name'] ),
                'status' => 1
            ] );

            $position->save();
            return redirect()->route( 'position.index' )->with( 'success', 'New Position created successfully.' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Position  $position
    * @return \Illuminate\Http\Response
    */

    public function show( Position $position ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Position  $position
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if ( $request->user()->can( 'manage-positions' ) ) {
            $position = Position::findorFail( $id );
            return view( 'backend.positions.edit', compact( 'position' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Position  $position
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $data = $this->validate( $request, [
            'name' => 'required'
        ] );

        $position = Position::findorFail( $id );

        $position->update( [
            'name' => $data['name'],
            'slug' => Str::slug( $data['name'] ),
        ] );

        return redirect()->route( 'position.index' )->with( 'success', 'Position information updated successfully.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Position  $position
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id, Request $request ) {
        if ( $request->user()->can( 'manage-positions' ) ) {
            $position = Position::findorFail( $id );
            $staff = Staff::where( 'position_id', $position->id )->get();
            if ( $staff ) {
                return redirect()->route( 'position.index' )->with( 'danger', 'Position used in staff. Cannot delete.' );
            }
            $position->delete();
            return redirect()->route( 'position.index' )->with( 'success', 'Position deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function disableposition( $id, Request $request ) {
        if ( $request->user()->can( 'manage-positions' ) ) {
            $position = Position::findorfail( $id );
            $position->update( [
                'status' => '0',
            ] );
            return redirect()->route( 'position.index' )->with( 'success', 'Position Disabled Successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function enableposition( $id, Request $request ) {
        if ( $request->user()->can( 'manage-positions' ) ) {
            $position = Position::findorfail( $id );
            $position->update( [
                'status' => '1',
            ] );
            return redirect()->route( 'position.index' )->with( 'success', 'Position Enabled Successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
