<?php

namespace App\Http\Controllers;

use App\Models\BackupInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackupInfoController extends Controller {
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
        if ( $request->user()->can( 'manage-database-backup' ) ) {
            $backups = BackupInfo::latest()->paginate( 10 );
            return view( 'backend.backup', compact( 'backups' ) );
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
        if ( $request->user()->can( 'manage-database-backup' ) ) {
            $user = Auth::user()->id;
            $backup = BackupInfo::create( [
                'backedup_by' => $user,
                'status' => 1,
            ] );
            $backup->save();
            return redirect()->route( 'backup.index' )->with( 'success', 'Successfuly Backedup' );
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
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\BackupInfo  $backupInfo
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\BackupInfo  $backupInfo
    * @return \Illuminate\Http\Response
    */

    public function edit( $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\BackupInfo  $backupInfo
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\BackupInfo  $backupInfo
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
    }
}
