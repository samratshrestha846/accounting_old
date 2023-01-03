<?php

namespace App\Http\Controllers\Hotel;

use App\Actions\CreateNewHotelFood;
use App\Actions\UpdateHotelFood;
use App\Actions\UploadHotelFoodImage;
use App\FormDatas\HotelFoodFormdata;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelFoodStoreRequest;
use App\Http\Requests\HotelFoodUpdateRequest;
use App\Models\Category;
use App\Models\HotelFood;
use App\Models\HotelKitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    protected $perPages = [10, 20, 50, 100, 250];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-food-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $foods = HotelFood::filters($request->all())->paginate($perPage);
        return view('backend.hotel.food.index')
            ->with('foods', $foods)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->can('hotel-food-create')) {
            return view('backend.permission.permission');
        }
        $kitchens = HotelKitchen::select('id', 'kitchen_name')->get();
        $categories = Category::select('id', 'category_name')->get();
        return view('backend.hotel.food.create')
            ->with('kitchens', $kitchens)
            ->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelFoodStoreRequest $request, CreateNewHotelFood $createNewHotelFood)
    {
        if (!$request->user()->can('hotel-food-create')) {
            return view('backend.permission.permission');
        }
        $cooking_time = $request->cooking_time_hour .':'. $request->cooking_time_min;

        DB::beginTransaction();
        try {
            $data = new HotelFoodFormdata(
                (int) $request->category,
                (int) $request->kitchen,
                $request->food_name,
                null,
                $request->component,
                $request->description,
                $cooking_time,
                (float) $request->food_price,
                (bool) $request->status,
            );

            $hotelFood = $createNewHotelFood->execute($data);

            if ($request->hasFile('food_image')) {
                (new UploadHotelFoodImage)->execute($hotelFood, $request->file('food_image'));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-food.index')->with('success', 'Food Item created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        if (!$request->user()->can('hotel-food-view')) {
            return view('backend.permission.permission');
        }

        $foodDetails = HotelFood::findOrFail($id);
        return view('backend.hotel.food.show', compact('foodDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,HotelFood $hotelFood)
    {
        if (!$request->user()->can('hotel-food-edit')) {
            return view('backend.permission.permission');
        }
        $kitchens = HotelKitchen::select('id', 'kitchen_name')->get();
        $categories = Category::select('id', 'category_name')->get();
        return view('backend.hotel.food.edit')
            ->with('kitchens', $kitchens)
            ->with('categories', $categories)
            ->with('food', $hotelFood);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelFoodUpdateRequest $request, HotelFood $hotelFood, UpdateHotelFood $updateHotelFood)
    {
        if (!$request->user()->can('hotel-food-edit')) {
            return view('backend.permission.permission');
        }

        $cooking_time = $request->cooking_time_hour .':'. $request->cooking_time_min;

        DB::beginTransaction();
        try {
            $data = new HotelFoodFormdata(
                (int) $request->category,
                (int) $request->kitchen,
                $request->food_name,
                $hotelFood->food_image,
                $request->component,
                $request->description,
                $cooking_time,
                (float) $request->food_price,
                (bool) $request->status,
            );

            $updateHotelFood->execute($hotelFood, $data);

            if ($request->hasFile('food_image')) {
                (new UploadHotelFoodImage)->execute($hotelFood, $request->file('food_image'));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-food.index')->with('success', 'Food Item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,HotelFood $hotelFood)
    {
        if (!$request->user()->can('hotel-food-delete')) {
            return view('backend.permission.permission');
        }

        try {
            if ($hotelFood->food_image) {
                unlink("uploads/" . $hotelFood->food_image);
            }

            $hotelFood->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-food.index')->with('success', 'Food Item deleted successfully');
    }
}
