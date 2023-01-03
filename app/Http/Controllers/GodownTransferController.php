<?php

namespace App\Http\Controllers;

use App\Models\GodownTransfer;
use Illuminate\Http\Request;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class GodownTransferController extends Controller {
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
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            $transfered_products = GodownTransfer::latest()->paginate( 10 );
            $nepali_date = datenep( date( 'Y-m-d' ) );
            return view( 'backend.product_service.transfered_products.index', compact( 'transfered_products', 'nepali_date' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function searchTransfer( Request $request ) {
        $search = $request->input( 'search' );

        $transfered_products = GodownTransfer::query()
        ->where( 'transfered_nep_date', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        $nepali_date = datenep( date( 'Y-m-d' ) );
        return view( 'backend.product_service.transfered_products.index', compact( 'transfered_products', 'nepali_date' ) );

    }

    public function generateTransferReport( Request $request )
    {
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];

            $start_date = dateeng( $starting_date );
            $end_date = dateeng( $ending_date );

            $transfered_products = GodownTransfer::latest()->where( 'transfered_eng_date', '>=', $start_date )->where( 'transfered_eng_date', '<=', $end_date )->paginate( 10 );
            $nepali_date = datenep( date( 'Y-m-d' ) );

            return view( 'backend.product_service.filterTransferReport', compact( 'starting_date', 'ending_date', 'transfered_products', 'nepali_date' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
