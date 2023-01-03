<?php

use App\Http\Controllers\API\BillingController;
use App\Http\Controllers\API\BioTime\DepartmentController;
use App\Http\Controllers\API\BioTime\DeviceController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SalesPosPaymentController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DailyExpensesController;
use App\Http\Controllers\API\DistrictController;
use App\Http\Controllers\API\FiscalYearController;
use App\Http\Controllers\API\GodownController;
use App\Http\Controllers\API\GodownProductController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PasswordUpdateController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProvinceController;
use App\Http\Controllers\API\PurchaseBillingController;
use App\Http\Controllers\API\SalesBillingController;
use App\Http\Controllers\API\SalesReportController;
use App\Http\Controllers\API\SuspendProductController;
use App\Http\Controllers\API\TaxController;
use App\Http\Controllers\API\UserProfileController;
use App\Http\Controllers\API\VendorController;
use App\Http\Controllers\SuspendedBillingItemController;
use App\Http\Controllers\API\SyncOfflineData\DailyExpensesController as SyncOfflineDataDailyExpensesController;
use App\Http\Controllers\API\SyncOfflineData\OutletSalesBillingController;
use App\Http\Controllers\API\SyncOfflineData\PurchaseBillingController as SyncOfflineDataPurchaseBillingController;
use App\Http\Controllers\API\SyncOfflineData\SalesBillingController as SyncOfflineDataSalesBillingController;
use App\Http\Controllers\API\UserPinNumberController;
use App\Http\Controllers\API\BioTime\LoginController as BioTimeLoginController;
use App\Http\Controllers\API\BioTime\PositionController;
use App\Http\Controllers\API\BioTime\StaffAttendanceController;
use App\Http\Controllers\API\BioTime\StaffController;
use App\Http\Resources\CBMSResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Billing;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     // dd($request->user());
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function () {


Route::post('login', [LoginController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    //user profile detail
    Route::get('/auth/me/profile',[UserProfileController::class, 'myProfile']);
});

Route::get('purchasesMaterialized', function() {
    return PurchaseResource::collection(Billing::latest()->where('billing_type_id', 2)->get());
});

Route::get('salesMaterialized', function() {
    return PurchaseResource::collection(Billing::latest()->where('billing_type_id', 1)->get());
});

Route::get('purchasesReturnMaterialized', function() {
    return PurchaseResource::collection(Billing::latest()->where('billing_type_id', 5)->get());
});

Route::get('salesReturnMaterialized', function() {
    return PurchaseResource::collection(Billing::latest()->where('billing_type_id', 6)->get());
});

Route::get('purchasesCbms', function() {
    return CBMSResource::collection(Billing::latest()->where('billing_type_id', 2)->get());
});

Route::get('salesCbms', function() {
    return CBMSResource::collection(Billing::latest()->where('billing_type_id', 1)->get());
});

Route::get('purchasesReturnCbms', function() {
    return CBMSResource::collection(Billing::latest()->where('billing_type_id', 5)->get());
});

Route::get('salesReturnCbms', function() {
    return CBMSResource::collection(Billing::latest()->where('billing_type_id', 6)->get());
});

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::post('auth/user/update-password', PasswordUpdateController::class);
    Route::post('auth/user/check-pinnumber', [UserPinNumberController::class,'checkPinNumber']);
    Route::post('auth/user/update-pinnumber', [UserPinNumberController::class,'updatePinNumber']);


    Route::get('/taxes',[TaxController::class,'index']);
    Route::get('districts',[DistrictController::class, 'index']);
    Route::get('provinces',[ProvinceController::class, 'index']);
    Route::get('categories',[CategoryController::class,'index']);

    //fiscal year route
    Route::get('fiscalyears',[FiscalYearController::class,'index']);

    //customer route
    Route::get('customers',[CustomerController::class,'index']);
    Route::post('customers',[CustomerController::class,'store']);

    //pos product route
    Route::get('pos/outlets/{outlet}/products',[ProductController::class, 'getPosProductListByOuletId']);
    Route::get('pos/outlets/{outlet}/products/{product_code}',[ProductController::class,'findPosProductByOutletIdAndProductCode']);

    //godowns of products
    Route::get('products/{product}/godowns', [GodownProductController::class,'getGodownsByProductId'])->name('getGodownsProductId');

    //products of godown
    Route::get('godowns/{godown}/products', [GodownProductController::class,'getProductsByGodownId'])->name('getProductsByGodownId');

    //vendor route
    Route::get('vendors',[VendorController::class,'index']);
    Route::post('vendors',[VendorController::class,'store']);

    //billing
    Route::get('billings', [BillingController::class,'index']);
    Route::get('godowns/salesbillings',[SalesBillingController::class,'index']);
    Route::get('vendors/purchasebillings',[PurchaseBillingController::class,'index']);
    Route::post('godowns/{godown}/salesbilling',[SalesBillingController::class,'store']);
    Route::post('vendors/{vendor}/purchasebilling',[PurchaseBillingController::class,'store']);

    //pos sales payment
    Route::post('pos/outlets/{outlet}/saleitems/purchase', [SalesPosPaymentController::class,'store'])->middleware('auth:sanctum');

    //suspended sale
    Route::get('products/suspendedsales',[SuspendProductController::class, 'index']);
    Route::post('products/sales/suspends',[SuspendProductController::class, 'store']);
    Route::patch('products/suspendedsales/{suspendedbill}',[SuspendProductController::class, 'update']);
    Route::post('products/suspendedsales/{suspendedbill}/cancle',[SuspendProductController::class, 'cancleSuspendedBill']);
    Route::get('products/suspendedsales/{id}',[SuspendProductController::class, 'show']);
    Route::get('products/suspendedsales/{id}/suspendeditems',[SuspendedBillingItemController::class, 'index']);


    //daily expenses
    Route::get('/dailyexpenses',[DailyExpensesController::class,'index']);
    Route::post('/dailyexpenses',[DailyExpensesController::class,'store']);


    //warehouse||godown
    Route::get('godowns',[GodownController::class,'index']);

    //sales report
    Route::get('outlets/{outlet}/salesreports/today-sale',[SalesReportController::class,'getTodaySalesReport']);

    //Send Email
    Route::post('pos/billings/{billing_id}/sent_email', [GodownProductController::class,'sendBillInfoEmail'])->name('pos.sendEmailBill');

    //sync offline data
    Route::group(['prefix' => 'sync'], function() {
        Route::post('dailyexpenses', [SyncOfflineDataDailyExpensesController::class,'store']);
        Route::post('vendors/purchasebilling', [SyncOfflineDataPurchaseBillingController::class,'store']);
        Route::post('godowns/salesbilling', [SyncOfflineDataSalesBillingController::class,'store']);
        Route::post('outlets/salesbilling', [OutletSalesBillingController::class,'store']);
    });
});

Route::post('biotime/login', BioTimeLoginController::class);

//Bio time
Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'biotime'], function(){

    //position
    Route::get('positions',[PositionController::class,'index']);
    Route::post('positions', [PositionController::class, 'store']);

    //devices
    Route::get('devices', [DeviceController::class,'index']);
    Route::post('devices', [DeviceController::class, 'store']);
    Route::patch('devices/{device:uuid}', [DeviceController::class, 'update']);

    //department
    Route::get('departments',[DepartmentController::class,'index']);
    Route::post('departments', [DepartmentController::class, 'store']);

    //staff|Employee
    Route::get('employees',[StaffController::class,'index']);
    Route::post('employees',[StaffController::class, 'store']);

    //staff|employee attendance
    Route::post('employees/attendances',[StaffAttendanceController::class,'store']);
});
