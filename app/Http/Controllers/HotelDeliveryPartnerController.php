<?php

namespace App\Http\Controllers;

use App\Actions\CreateNewDeliveryPartnerAction;
use App\Actions\DeleteDeliveryPartnerAction;
use App\Actions\UpdateDeliveryPartnerAction;
use App\Actions\UploadDeliveryPartnerAction;
use App\Factories\HotelDeliveryPartnerFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelDeliveryPartnerStoreRequest;
use App\Http\Requests\HotelDeliveryPartnerUpdateRequest;
use App\Models\District;
use App\Models\HotelDeliveryPartner;
use App\Models\Province;
use Illuminate\Http\Request;

class HotelDeliveryPartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if (!$request->user()->can('delivery-partner-view')) {
        //     return view('backend.permission.permission');
        // }

        $deliveryPartners = HotelDeliveryPartner::query()
            ->filters($request->all())
            ->with('province', 'district')
            ->paginate(10);

        return view('backend.hotel.delivery_partner.index')
            ->with('deliveryPartners', $deliveryPartners);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if (!$request->user()->can('delivery-partner-create')) {
        //     return view('backend.permission.permission');
        // }

        $provinces = Province::get();

        return view('backend.hotel.delivery_partner.create')
            ->with('provinces', $provinces);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelDeliveryPartnerStoreRequest $request)
    {
        try {
            $request->is_active = (bool) $request->is_active;

            $data = HotelDeliveryPartnerFactory::make($request);

            $deliveryPartner = (new CreateNewDeliveryPartnerAction)->execute($data);

            if ($request->hasFile('logo')) {
                (new UploadDeliveryPartnerAction)->execute($deliveryPartner, $request->file('logo'));
            }

        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success',"Delivery partner created successfully");
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
    public function edit(HotelDeliveryPartner $deliveryPartner)
    {
        // if (!$request->user()->can('delivery-partner-edit')) {
        //     return view('backend.permission.permission');
        // }

        $provinces = Province::get();

        return view('backend.hotel.delivery_partner.edit')
            ->with('deliveryPartner' , $deliveryPartner)
            ->with('provinces', $provinces);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelDeliveryPartnerUpdateRequest $request, HotelDeliveryPartner $deliveryPartner)
    {
        try {
            $request->is_active = (bool) $request->is_active;

            $data = HotelDeliveryPartnerFactory::make($request);

            (new UpdateDeliveryPartnerAction)->execute($deliveryPartner, $data);

            if ($request->hasFile('logo')) {
                (new UploadDeliveryPartnerAction)->execute($deliveryPartner, $request->file('logo'));
            }

        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('delivery-partner.index')->with('success',"Delivery partner updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelDeliveryPartner $deliveryPartner)
    {
        try {
            (new DeleteDeliveryPartnerAction)->execute($deliveryPartner);
        } catch(\Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

        return redirect()->back()->with('success',"Delivery partner deleted successfully");

    }
}
