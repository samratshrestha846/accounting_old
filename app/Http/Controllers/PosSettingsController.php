<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\PosSettings;
use App\Models\User;
use Illuminate\Http\Request;

class PosSettingsController extends Controller
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
        if ( $request->user()->can( 'manage-pos-setting' ) ) {
            $categories = Category::latest()->get();
            $customers = Client::latest()->get();
            $users = User::latest()->get();

            $posSetting = PosSettings::first();

            return view('backend.posSettings', compact('categories', 'customers', 'users', 'posSetting'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function update(Request $request, $id)
    {
        $posSetting = PosSettings::findorFail($id);
        $this->validate($request, [
            'display_products' => ['required','integer'],
            'default_category' => ['nullable','exists:categories,id'],
            'default_customer' => ['nullable','exists:clients,id'],
            'default_biller' => ['nullable','exists:users,id'],
            'show_tax' => '',
            'show_discount' => '',
            'default_currency' => ['required','string'],
            'kot_print_after_placing_order' => '',
        ]);

        if ( $request['show_tax'] == null ) {
            $show_tax = 0;
        } else {
            $show_tax = 1;
        }

        if ( $request['show_discount'] == null ) {
            $show_discount = 0;
        } else {
            $show_discount = 1;
        }

        $posSetting->update([
            'display_products' => $request['display_products'],
            'default_category' => $request['default_category'],
            'default_customer' => $request['default_customer'],
            'default_biller' => $request['default_biller'],
            'show_tax' => $show_tax,
            'show_discount' => $show_discount,
            'default_currency' => $request['default_currency'],
            'kot_print_after_placing_order' => $request['kot_print_after_placing_order'] ? true : false,
        ]);

        return redirect()->route('posSettings.index')->with('success', 'POS Settings is successfully updated.');
    }
}
