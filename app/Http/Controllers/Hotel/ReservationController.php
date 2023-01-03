<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\CabinType;
use App\Models\Client;
use App\Models\DealerType;
use App\Models\HotelReservation;
use App\Models\HotelRoom;
use App\Models\HotelTable;
use App\Models\Paymentmode;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-reservation-view')) {
            return view('backend.permission.permission');
        }

        if ($request->date_time_start && $request->date_time_end) {

            $request->validate([
                'date_time_start' => ['required', 'date_format:m/d/Y h:i A'],
                'date_time_end' => ['required', 'date_format:m/d/Y h:i A', 'after:date_time_start'],
            ]);

            $tables = HotelTable::with('floor', 'room')->paginate();
            $reservations = HotelReservation::with('table', 'client')
                ->where('date_time_start','>=', Carbon::parse($request['date_time_start']))
                ->where('date_time_end','<=', Carbon::parse($request['date_time_end']))
                ->paginate();
            $filterArray = array(
                'date_time_start' => $request->date_time_start,
                'date_time_end' => $request->date_time_end,
            );
        } else {
            $tables = HotelTable::with('floor', 'room')->paginate();
            $reservations = HotelReservation::with('table', 'client')
                ->paginate();
            $filterArray = null;
        }

        return view('backend.hotel.reservation.index', compact('filterArray', 'tables', 'reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if (!$request->user()->can('hotel-reservation-create')) {
            return view('backend.permission.permission');
        }

        $avaliableTable = [];

        // $rooms = HotelRoom::with('floor')->get();
        $rooms = HotelRoom::paginate();
        $cabinTypes = CabinType::get();
        $clients = Client::all();
        $paymentMode = Paymentmode::active()->get();

        $dealerTypes = [];
        $client_code = '';
        $provinces = [];
        $allclientcodes = [];
        return view('backend.hotel.reservation.create', compact('allclientcodes', 'dealerTypes', 'provinces', 'client_code', 'avaliableTable', 'rooms', 'cabinTypes', 'clients', 'paymentMode'));
    }

    public function avaliable_table(Request $request)
    {
        if (!$request->user()->can('hotel-reservation-create')) {
            return view('backend.permission.permission');
        }


        $request->validate([
            'no_of_person' => ['required', 'min:1'],
            'date_time_start' => ['required', 'date_format:m/d/Y h:i A'],
            'date_time_end' => ['required', 'date_format:m/d/Y h:i A', 'after:date_time_start'],
        ]);

        $filterOption = array(
            'no_of_person' => $request->no_of_person,
            'date_time_start' => $request->date_time_start,
            'date_time_end' => $request->date_time_end,
            'start_date_time' => Carbon::parse($request['date_time_start']),
            'end_date_time' => Carbon::parse($request['date_time_end']),
        );


        $avaliableTable = HotelTable::whereDoesntHave('reservation', function (Builder $query) use ($filterOption) {
            return $query->where('date_time_start', '>=', $filterOption['start_date_time'])
                ->where('date_time_end', '<=', $filterOption['end_date_time']);
        })->where('max_capacity', '>=', $filterOption['no_of_person'])->get();

        $rooms = HotelRoom::paginate();
        $cabinTypes = CabinType::get();

        $paymentMode = Paymentmode::active()->get();


        $provinces = Province::latest()->get();

        $dealerTypes = DealerType::latest()->get();
        $clients = Client::with('dealertype')->latest()->get();
        $allclientcodes = [];
        foreach ($clients as $client) {
            array_push($allclientcodes, $client->client_code);
        }
        $client_code = 'CL' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        return view('backend.hotel.reservation.create', compact('allclientcodes', 'dealerTypes', 'provinces', 'client_code', 'avaliableTable', 'rooms', 'cabinTypes', 'filterOption', 'clients', 'paymentMode'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->user()->can('hotel-reservation-create')) {
            return view('backend.permission.permission');
        }
        $request->validate([
            'id' => 'required|integer',
            'client_id' => 'required',
            'number_of_people' => 'required',
            'date_time_start' => ['required', 'date_format:m/d/Y h:i A'],
            'date_time_end' => ['required', 'date_format:m/d/Y h:i A'],
            'payment_by' => 'required',
            'is_paid' => 'required',
            'status' => 'required',
        ]);

        HotelReservation::create([
            'table_id' => $request->id,
            'client_id' => $request->client_id,
            'customer_type_id' => 0,
            'number_of_people' => $request->number_of_people,
            'date_time_start' => date('Y-m-d h:i:s', strtotime($request->date_time_start)),
            'date_time_end' => date('Y-m-d h:i:s', strtotime($request->date_time_end)),
            'payment_method' => $request->payment_by,
            'status' => $request->status,
            'is_paid' => $request->is_paid,
            'amount' => $request->amount,
            'date_to_paid' => $request->date_to_paid ?? null,
        ]);


        return redirect()->route('hotel-reservation.index')
            ->with('success', 'Reservation Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, HotelReservation $hotelReservation)
    {
        if (!$request->user()->can('hotel-reservation-edit')) {
            return view('backend.permission.permission');
        }

        $clients = Client::all();
        $paymentMode = Paymentmode::active()->get();
        return view('backend.hotel.reservation.edit', compact('hotelReservation', 'clients', 'paymentMode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelReservation $hotelReservation)
    {


        if (!$request->user()->can('hotel-reservation-edit')) {
            return view('backend.permission.permission');
        }

        $request->validate([
            'number_of_people' => 'required',
            'date_time_end' => ['required', 'date_format:m/d/Y h:i A'],
            'payment_by' => 'required',
            'is_paid' => 'required',
            'status' => 'required',
        ]);

        $hotelReservation->update([
            'number_of_people' => $request->number_of_people,
            'date_time_end' => date('Y-m-d h:i:s', strtotime($request->date_time_end)),
            'payment_method' => $request->payment_by,
            'status' => $request->status,
            'is_paid' => $request->is_paid,
            'amount' => $request->amount,
            'date_to_paid' => $request->date_to_paid,
        ]);

        return redirect()->route('hotel-reservation.index')
            ->with('success', 'Reservation Created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, HotelReservation $hotelReservation)
    {
        if (!$request->user()->can('hotel-reservation-delete')) {
            return view('backend.permission.permission');
        }

        $hotelReservation->delete();
        return redirect()->route('hotel-reservation.index')
            ->with('success', 'Reservation Created');
    }

    public function cancel(Request $request, HotelReservation $hotelReservation)
    {
        if (!$request->user()->can('hotel-reservation-delete')) {
            return view('backend.permission.permission');
        }

        $hotelReservation->update([
            'status' => 2, // status 2: cancelled Reservation
        ]);
        return redirect()->route('hotel-reservation.index')
            ->with('success', 'Reservation Cancelled');
    }
}
