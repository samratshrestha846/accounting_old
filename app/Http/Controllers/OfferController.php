<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use function App\NepaliCalender\datenep;

class OfferController extends Controller
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
        if ( $request->user()->can( 'manage-offer-setting' ) )
        {
            $offers = Offer::latest()->paginate(10);
            return view('backend.offer.index', compact('offers'));
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
        if ( $request->user()->can( 'manage-offer-setting' ) ) {
            $today = date('Y-m-d');
            $nepali_today = datenep($today);
            return view('backend.offer.create', compact('today', 'nepali_today'));
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
        $this->validate($request, [
            "offer_start_date_nepali" => 'required',
            "offer_start_date_english" => 'required',
            "offer_end_date_nepali" => 'required',
            "offer_end_date_english" => 'required',
            "offer_name" => 'required',
            "offer_percent" => 'required',
            "minimun_price_range" => '',
            "maximum_price_range" => ''
        ]);

        if ( $request['status'] == null ) {
            $status = 0;
        } else {
            $status = 1;
        }

        Offer::create([
            'offer_name' => $request['offer_name'],
            'offer_percent' => $request['offer_percent'],
            'range_min' => $request['minimun_price_range'],
            'range_max' => $request['maximum_price_range'],
            'offer_start_eng_date' => $request['offer_start_date_english'],
            'offer_start_nep_date' => $request['offer_start_date_nepali'],
            'offer_end_eng_date' => $request['offer_end_date_english'],
            'offer_end_nep_date' => $request['offer_end_date_nepali'],
            'status' => $status,
        ]);

        return redirect()->route('offer.index')->with('success', 'Offer information is saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-offer-setting' ) ) {
            $existing_offer = Offer::findorFail($id);
            return view('backend.offer.edit', compact('existing_offer'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existing_offer = Offer::findorFail($id);
        $this->validate($request, [
            "offer_start_date_nepali" => 'required',
            "offer_start_date_english" => 'required',
            "offer_end_date_nepali" => 'required',
            "offer_end_date_english" => 'required',
            "offer_name" => 'required',
            "offer_percent" => 'required',
            "minimun_price_range" => '',
            "maximum_price_range" => ''
        ]);

        if ( $request['status'] == null ) {
            $status = 0;
        } else {
            $status = 1;
        }

        $existing_offer->update([
            'offer_name' => $request['offer_name'],
            'offer_percent' => $request['offer_percent'],
            'range_min' => $request['minimun_price_range'],
            'range_max' => $request['maximum_price_range'],
            'offer_start_eng_date' => $request['offer_start_date_english'],
            'offer_start_nep_date' => $request['offer_start_date_nepali'],
            'offer_end_eng_date' => $request['offer_end_date_english'],
            'offer_end_nep_date' => $request['offer_end_date_nepali'],
            'status' => $status,
        ]);

        return redirect()->route('offer.index')->with('success', 'Offer information is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-offer-setting' ) ) {
            $existing_offer = Offer::findorFail($id);
            $existing_offer->delete();

            return redirect()->route('offer.index')->with('success', 'Offer information is deleted successfully.');
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
