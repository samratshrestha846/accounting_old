<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BackupInfoController;
use App\Http\Controllers\BankAccountTypeController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BillerController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChildAccountController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyShareController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\CustomerEndController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\DailyExpensesController;
use App\Http\Controllers\DamagedProductsController;
use App\Http\Controllers\DealerTypeController;
use App\Http\Controllers\DealerUserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GodownController;
use App\Http\Controllers\GodownTransferController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalImageController;
use App\Http\Controllers\JournalVouchersController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OnlinePaymentController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaymentInfoController;
use App\Http\Controllers\PaymentmodeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonalShareController;
use App\Http\Controllers\PointOfSaleController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PosSettingsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductNotificationController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\QuotationFollowupController;
use App\Http\Controllers\QuotationSettingController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesBarcodeController;
use App\Http\Controllers\SalesBillsController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SubAccountController;
use App\Http\Controllers\SuperSettingController;
use App\Http\Controllers\SuspendedSaleController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TaxInfoController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WarehousePosTransferController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportSuppliersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// Route::post('/login', LoginController::class);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/customerlogin', [CustomerLoginController::class, 'login'])->name('customer.login');
Route::post('/signincustomer', [CustomerLoginController::class, 'signInCustomer'])->name('signInCustomer');



//route of hotel
include 'hotel_pos.php';
include 'lab.php';

Route::group(['middleware' => ['auth:customer']], function () {
    Route::get('/customerhome', [CustomerLoginController::class, 'dashboard'])->name('customer.home');
    Route::get('/switchselectedcustomer/{id}', [CustomerLoginController::class, 'switchselected'])->name('switch.selectedcustomer');
    Route::get('/customer/purchaseordercreate', [CustomerEndController::class, 'pruchaseordercreate'])->name('purchaseOrder.customercreate');
    Route::get('/customer/purchaseorderindex', [CustomerEndController::class, 'pruchaseorderindex'])->name('purchaseOrder.customerindex');
    Route::get('/clientpurchaseorder/notify/{id}', [ClientOrderController::class, 'purchaseordernotify'])->name('clientpurchaseorder.notify');
    Route::get('/clientpurchaseorder/print/{id}', [ClientOrderController::class, 'purchaseorderprint'])->name('clientpurchaseorder.print');
    Route::resource('clientpurchaseorder', ClientOrderController::class);
    Route::get('/clientpurchases', [ClientOrderController::class, 'purchases'])->name('client.purchases');
    Route::get('/clientpaidpurchases', [ClientOrderController::class, 'paidpurchases'])->name('client.paidpurchases');
    Route::get('/clientduepurchases', [ClientOrderController::class, 'duepurchases'])->name('client.duepurchases');
    Route::get('/clientpurchases/{id}', [ClientOrderController::class, 'singlepurchase'])->name('mypurchase.show');
    Route::get('/clientquotations', [ClientOrderController::class, 'quotations'])->name('client.quotations');
    Route::get('/clientquotations/{id}', [ClientOrderController::class, 'singlequotation'])->name('myquotation.show');
    Route::get('/clientpurchasereturns', [ClientOrderController::class, 'purchasereturns'])->name('client.purchasereturns');
    Route::get('/clientpurchasereturns/{id}', [ClientOrderController::class, 'singlepurchasereturn'])->name('mypurchasereturn.show');
});
Route::group(['middleware' => ['auth']], function () {
    //as.k
    Route::get('manage-old-credit',[HomeController::class,'manage_old_billingcredit']);
    Route::get('manage-subaccount-crOrDr',[HomeController::class,'manage_subaccount_creditorOrDebitor']);
    Route::get('manage-product-childac',[HomeController::class,'manageProductChildAc']);
    Route::get('manage-service-childac',[HomeController::class,'manageServiceChildAc']);
    Route::get('manage-client-vendor-childac',[HomeController::class,'manageClientVendorChildAc']);
    Route::get('manage-bank-childac',[HomeController::class,'manageBankChildAc']);
    Route::get('manage-onlinepayment-childac',[HomeController::class,'manageOnlinepaymentChildAc']);
    Route::get('manage-product-childac-openingbalance',[HomeController::class,'productchildacOpeningblnc']);
    Route::get('manage-service-childac-openingbalance',[HomeController::class,'servicechildacOpeningblnc']);
    Route::get('manage-child-openingbalance', [HomeController::class, 'managechildopeningbalance']);

    Route::get('childaccountconversion', [HomeController::class, 'childaccountconversion']);
    Route::get('childac-append-productcode',[HomeController::class,'childacAppendProductCode']);
    Route::get('client_vendor_to_child', [HomeController::class, 'client_vendor_to_child']);
    Route::get('change_password_nectar',[UserController::class,'change_password_nectar']);
    Route::get('managestock', [HomeController::class, 'managestock']);

    // globalsearch
    Route::get('search',[HomeController::class,'globsearch'])->name('globalSearch');

    //end as.k
    Route::get('productbilltojournals', [HomeController::class, 'productbilltojournals']);
    Route::get('servicebilltojournals', [HomeController::class, 'servicebilltojournals']);


    // Users
    Route::resource('user', UserController::class);
    Route::post('user/search', [UserController::class, 'search'])->name('user.search');
    Route::get('deletedusers', [UserController::class, 'deletedusers'])->name('deletedusers');
    Route::get('restoreusers/{id}', [UserController::class, 'restoreusers'])->name('restoreusers');

    // Permissions
    Route::resource('permission', PermissionController::class);
    Route::post('permission/search', [PermissionController::class, 'search'])->name('permission.search');

    // Staff Management
    Route::resource('department', DepartmentController::class);
    Route::resource('position', PositionController::class);
    Route::resource('device', DeviceController::class);
    Route::post('device/search', [DeviceController::class, 'search'])->name('device.search');
    Route::post('position/search', [PositionController::class, 'search'])->name('position.search');
    Route::put('position/disable/{id}', [PositionController::class, 'disableposition'])->name('position.disable');
    Route::put('position/enable/{id}', [PositionController::class, 'enableposition'])->name('position.enable');
    Route::resource('staff', StaffController::class);
    Route::post('staff/search', [StaffController::class, 'search'])->name('staff.search');
    Route::put('staff/disable/{id}', [StaffController::class, 'disablestaff'])->name('staff.disable');
    Route::put('staff/enable/{id}', [StaffController::class, 'enablestaff'])->name('staff.enable');

    //Online Payment
    Route::resource('onlinepayment', OnlinePaymentController::class);

    // Attendance Management
    Route::post('updateexit', [AttendanceController::class, 'updateexit'])->name('updateexit');
    Route::get('report', [AttendanceController::class, 'report'])->name('report');
    Route::get('reportgenerator', [AttendanceController::class, 'reportgenerator'])->name('reportgenerator');
    Route::get('leftattendance', [AttendanceController::class, 'leftattendance'])->name('leftattendance');
    Route::post('storeleftattendance', [AttendanceController::class, 'storeleftattendance'])->name('storeleftattendance');
    Route::post('storeOvertime', [AttendanceController::class, 'storeOvertime'])->name('storeOvertime');

    Route::get('paymentInfo/{id}', [AttendanceController::class, 'paymentInfo'])->name('paymentInfo');
    Route::get('payrollIndex', [AttendanceController::class, 'payrollIndex'])->name('payrollIndex');
    Route::get('payroll', [AttendanceController::class, 'payroll'])->name('payroll');
    Route::post('savePayroll', [AttendanceController::class, 'savePayroll'])->name('savePayroll');
    Route::get('editPayroll/{id}', [AttendanceController::class, 'editPayroll'])->name('editPayroll');
    Route::put('updatePayroll/{id}', [AttendanceController::class, 'updatePayroll'])->name('updatePayroll');
    Route::delete('deletePayroll/{id}', [AttendanceController::class, 'deletePayroll'])->name('deletePayroll');
    Route::post('payroll/search', [AttendanceController::class, 'searchPayroll'])->name('searchPayroll');
    Route::get('payrollReport', [AttendanceController::class, 'payrollReport'])->name('payrollReport');
    Route::get('generatePayrollReport', [AttendanceController::class, 'generatePayrollReport'])->name('generatePayrollReport');
    Route::get('printSinglePayroll/{id}', [AttendanceController::class, 'printSinglePayroll'])->name('printSinglePayroll');
    Route::resource('attendance', AttendanceController::class);


    // Settings
    Route::resource('setting', SettingController::class);
    Route::resource('unit', UnitController::class);
    Route::post('unit/search', [UnitController::class, 'search'])->name('unit.search');
    Route::resource('brand', BrandController::class);
    Route::post('brand/search', [BrandController::class, 'search'])->name('brands.search');
    Route::get('deletedbrand', [BrandController::class, 'deletedbrand'])->name('deletedbrand');
    Route::get('restorebrand/{id}', [BrandController::class, 'restorebrand'])->name('restorebrand');

    // Roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/search', [RoleController::class, 'search'])->name('role.search');
    Route::get('deletedroles', [RoleController::class, 'deletedroles'])->name('deletedroles');
    Route::get('restoreroles/{id}', [RoleController::class, 'restoreroles'])->name('restoreroles');

    //Switch Branch or Company
    Route::get('/switchselected/{id}', [HomeController::class, 'switchselected'])->name('switch.selected');

    //Company
    Route::resource('company', CompanyController::class);
    Route::post('company/search', [CompanyController::class, 'search'])->name('company.search');
    Route::resource('branch', BranchController::class);
    Route::post('branch/search', [BranchController::class, 'search'])->name('branches.search');

    // Account Heads
    Route::resource('account', AccountController::class);
    Route::post('accounts/search', [AccountController::class, 'search'])->name('accounts.search');
    Route::get('restoreaccount/{id}', [AccountController::class, 'restore'])->name('restore');
    Route::get('deletedindex', [AccountController::class, 'deletedindex'])->name('deletedindex');
    Route::get('accounthierarchy', [AccountController::class, 'accounthierarchy'])->name('accounthierarchy');

    // Sub Account Heads
    Route::resource('sub_account', SubAccountController::class);
    Route::post('sub_accounts/search', [SubAccountController::class, 'search'])->name('subaccounts.search');
    Route::get('restoresubaccount/{id}', [SubAccountController::class, 'restoresubaccount'])->name('restoresubaccount');
    Route::get('deletedsubindex', [SubAccountController::class, 'deletedsubindex'])->name('deletedsubindex');

    // Child Account Types
    Route::resource('child_account', ChildAccountController::class);
    Route::post('child_accounts/search', [ChildAccountController::class, 'search'])->name('childaccounts.search');
    Route::get('restorechildaccount/{id}', [ChildAccountController::class, 'restorechildaccount'])->name('restorechildaccount');
    Route::get('deletedchildindex', [ChildAccountController::class, 'deletedchildindex'])->name('deletedchildindex');

    // Journal Voucher
    Route::resource('journals', JournalVouchersController::class);
    Route::post('journals/status/{id}', [JournalVouchersController::class, 'status'])->name('journals.status');
    Route::post('journals/approve', [JournalVouchersController::class, 'multiapprove'])->name('journals.approve');
    Route::post('journals/unapprove', [JournalVouchersController::class, 'multiunapprove'])->name('journals.unapprove');
    Route::post('journals/cancel/{id}', [JournalVouchersController::class, 'cancel'])->name('journals.cancel');
    Route::post('journals/revive/{id}', [JournalVouchersController::class, 'revive'])->name('journals.revive');

    // Print Preview Journal Voucher
    Route::get('journals/print/{id}', [JournalVouchersController::class, 'printpreview'])->name('journals.print');
    Route::get('cancelledjournals', [JournalVouchersController::class, 'cancelledindex'])->name('journals.cancelled');
    Route::get('unapprovedjournals', [JournalVouchersController::class, 'unapprovedindex'])->name('journals.unapproved');

    // Trialbalance
    Route::get('/trialbalance', [JournalVouchersController::class, 'trialbalance'])->name('journals.trialbalance');

    // Profit And Loss
    Route::get('/profitandloss', [JournalVouchersController::class, 'profitandloss'])->name('journals.profitandloss');
    Route::post('/profitandlossfilter', [JournalVouchersController::class, 'profitandlossfilter'])->name('journals.profitandlossfilter');

    // Balance Sheet
    Route::get('/balancesheet', [JournalVouchersController::class, 'balancesheet'])->name('journals.balancesheet');
    Route::post('/balancesheetfilter', [JournalVouchersController::class, 'balancesheetfilter'])->name('journals.balancesheetfilter');

    // Route::resource('journalimage', JournalImageController::class);
    Route::delete('/journalimage/{id}', [JournalImageController::class, 'destroy'])->name('journalimage.destroy');

    // Vendors
    Route::get('supplierExport',[ExportController::class,'supplierExport'])->name('supplierExport');
    Route::post('supplierPdf',[VendorController::class,'supplierPdf'])->name('pdf.supplier');
    Route::post('vendor/search', [VendorController::class, 'search'])->name('vendor.search');
    Route::get('restorevendor/{id}', [VendorController::class, 'restorevendor'])->name('restorevendor');
    Route::get('deletedvendor', [VendorController::class, 'deletedvendor'])->name('deletedvendor');
    Route::get('supplierpurchaseExport/{id}', [ExportController::class, 'supplierpurchaseExport'])->name('supplierpurchaseExport');
    Route::post('supplierpurchasepdf/{id}', [VendorController::class, 'supplierpurchasepdf'])->name('pdf.supplierpurchase');
    Route::get('supplierpurchase/{id}', [VendorController::class, 'supplierpurchase'])->name('supplier.purchase');
    Route::get('supplieServicepurchase/{id}', [VendorController::class, 'supplierServicepurchase'])->name('supplier.servicepurchase');
    Route::get('supplierdebitnote/{id}', [VendorController::class, 'supplierdebitnote'])->name('supplier.debitnote');
    Route::post('supplier/makedefault/', [VendorController::class, 'makedefault'])->name('supplier.makedefault');
    Route::get('vendorproducts/{id}', [VendorController::class, 'Vendorproducts'])->name('vendors.products');
    Route::get('supplierLedgers', [VendorController::class, 'supplierLedgers'])->name('supplierLedgers');
    Route::get('supplierLedgersunpaid/{paidStatus}', [VendorController::class, 'supplierLedgersunpaid'])->name('supplierLedgersunpaid');
    Route::post('supplierLedgersgeneratepdf', [VendorController::class, 'supplierLedgersgeneratepdf'])->name('supplierLedgersgeneratepdf');
    Route::get('supplierledgerexportexcel', [ExportController::class, 'supplierledgerexportexcel'])->name('supplierledgerexportexcel');
    Route::resource('vendors', VendorController::class);

    // Daily Expenses
    Route::resource('dailyexpenses', DailyExpensesController::class);
    Route::post('dailyexpenses/search', [DailyExpensesController::class, 'search'])->name('dailyexpense.search');
    Route::get('restoreexpenses/{id}', [DailyExpensesController::class, 'restoreexpenses'])->name('restoreexpenses');
    Route::get('deletedexpenses', [DailyExpensesController::class, 'deletedexpenses'])->name('deletedexpenses');

    // Category
    Route::resource('category', CategoryController::class);

    Route::post('set_order', [CategoryController::class, 'set_order'])->name('category.set_order');

    Route::post('updateCategory', [CategoryController::class, 'updateCategoryOrder'])->name('updateCategoryOrder');
    Route::post('category/search', [CategoryController::class, 'search'])->name('category.search');
    Route::get('deletedcategory', [CategoryController::class, 'deletedcategory'])->name('deletedcategory');
    Route::get('restorecategory/{id}', [CategoryController::class, 'restorecategory'])->name('restorecategory');
    Route::post('categoryImporter', [CategoryController::class, 'categoryImporter'])->name('categoryImporter');

    // Product

    Route::get('ajaxgetProducts', [ProductController::class, 'ajaxgetProducts'])->name('ajaxgetProducts');
    Route::get('product/sales/{id}', [ProductController::class, 'productsales'])->name('product.sales');
    Route::get('productFilterSession', [ProductController::class, 'productFilterSession'])->name('productFilterSession');
    Route::get('productsExportCsv', [ExportController::class, 'productsExportCsv'])->name('productsExportCsv');
    Route::get('product/purchases/{id}', [ProductController::class, 'productpurchases'])->name('product.purchases');
    Route::get('product/sales_returns/{id}', [ProductController::class, 'productsales_returns'])->name('product.sales_returns');
    Route::get('product/purchase_returns/{id}', [ProductController::class, 'productpurchase_returns'])->name('product.purchase_returns');
    Route::get('product/quotations/{id}', [ProductController::class, 'productquotations'])->name('product.quotations');
    Route::get('product/stockreport', [ProductController::class, 'stockreportcreate'])->name('stockreportcreate');
    Route::get('product/stockreport/{id}', [ProductController::class, 'godownstockreportcreate'])->name('stockreportcreate.godown');
    Route::post('product/stockgenerate', [ProductController::class, 'stockgenerate'])->name('product.stockgenerate');
    Route::get('product/lowstockreport', [ProductController::class, 'low_stock_products'])->name('lowstockproduct');
    Route::get('product/outofstockreport', [ProductController::class, 'outof_stock_products'])->name('outofstockproduct');
    Route::get('product/expiringstockreport', [ProductController::class, 'expiring_stock_products'])->name('expiringstockproduct');
    Route::get('product/expiredstockreport', [ProductController::class, 'expired_stock_products'])->name('expiredstockproduct');
    Route::resource('product', ProductController::class);
    Route::delete('productimage/{id}', [ProductController::class, 'deleteproductimage'])->name('deleteproductimage');
    Route::get('restoreproduct/{id}', [ProductController::class, 'restoreproduct'])->name('restoreproduct');
    Route::get('deletedproduct', [ProductController::class, 'deletedproduct'])->name('deletedproduct');
    // Route::post('searchproduct', [ProductController::class, 'searchproduct'])->name('searchproduct');
    Route::get('viewProductBarCodes/{product_id}', [ProductController::class, 'viewProductBarCodes'])->name('viewProductBarCodes');
    Route::get('viewProductQRCodes/{product_id}', [ProductController::class, 'viewProductQRCodes'])->name('viewProductQRCodes');
    Route::get('product/barcodeprint/{id}/{quantity}', [ProductController::class, 'barcode'])->name('barcodeprint');
    // Route::get('product/secondarybarcodeprint/{id}/{quantity}', [ProductController::class, 'secondaryBarcode'])->name('secondarybarcodeprint');
    Route::get('product/qrcodeprint/{id}/{quantity}', [ProductController::class, 'qrcode'])->name('qrcodeprint');
    // Route::get('product/secondaryqrcodeprint/{id}/{quantity}', [ProductController::class, 'secondaryqrcode'])->name('secondaryqrcodeprint');
    Route::get('product/addstock/{id}', [ProductStockController::class, 'create'])->name('product.addstockcreate');
    Route::get('product/addstockreport/{id}', [ProductStockController::class, 'index'])->name('product.addstockindex');
    Route::resource('addstock', ProductStockController::class);
    Route::get('productnotification/{type}', [ProductNotificationController::class, 'productnotificationtype'])->name('productnoti.type');
    Route::get('productnotification/read/{type}', [ProductNotificationController::class, 'readproductnotification'])->name('productnoti.read');
    Route::resource('productnotification', ProductNotificationController::class);

    // Service
    Route::resource('service_category', ServiceCategoryController::class);
    Route::post('updateServiceCategory', [ServiceCategoryController::class, 'updateServiceCategoryOrder'])->name('updateServiceCategoryOrder');
    Route::post('serviceCategory/search', [ServiceCategoryController::class, 'search'])->name('serviceCategory.search');
    Route::resource('service', ServiceController::class);
    Route::delete('serviceimage/{id}', [ServiceController::class, 'deleteserviceimage'])->name('deleteserviceimage');
    Route::get('restoreservice/{id}', [ServiceController::class, 'restoreservice'])->name('restoreservice');
    Route::get('deletedservice', [ServiceController::class, 'deletedservice'])->name('deletedservice');
    Route::post('searchservice', [ServiceController::class, 'searchservice'])->name('searchservice');

    // Scheme
    Route::resource('scheme', SchemeController::class);
    Route::post('searchscheme', [SchemeController::class, 'searchscheme'])->name('searchscheme');

    // Dealer
    Route::post('dealertype/mark-as-default', [DealerTypeController::class, 'mark_as_default'])->name('dealertype.mark_as_default');
    Route::resource('dealertype', DealerTypeController::class);


    // Outlet
    Route::post('outlet/search', [OutletController::class, 'search'])->name('outlet.search');
    Route::resource('outlet', OutletController::class);

    // Generate Journal Report
    Route::get('extra', [JournalVouchersController::class, 'extra'])->name('extra');
    Route::get('report/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generatereport'])->name('generatereport');
    // Route::post('journals/search', [JournalVouchersController::class, 'journalsearch'])->name('journalvoucher.search');
    Route::post('cancelledjournals/search', [JournalVouchersController::class, 'cancelledjournalsearch'])->name('cancelledjournalvoucher.search');
    Route::post('unapprovejournals/search', [JournalVouchersController::class, 'unapprovejournalsearch'])->name('unapprovejournalvoucher.search');

    // Fetch district data
    Route::get('vendors/getdistricts/{id}', [VendorController::class, 'getdistricts'])->name('getdistricts');
    Route::get('getSeries/{id}', [BrandController::class, 'getSeries'])->name('getSeries');
    Route::get('getVat/{id}', [ProductController::class, 'getVat'])->name('getVat');

    // Generate Trial Balance Report
    Route::get('trialextra', [JournalVouchersController::class, 'trialextra'])->name('trialextra');
    Route::get('trialreport/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generatetrialreport'])->name('generatetrialreport');

    // Download Journal PDF
    Route::get('pdf/generateJournal/{id}', [JournalVouchersController::class, 'generateJournalPDF'])->name('pdf.generateJournal');

    //Billings
    Route::get('billings/salescreate', [BillingController::class, 'salescreate'])->name('billings.salescreate');
    Route::post('billings/salesstore', [BillingController::class, 'salesstore'])->name('billings.salesstore');
    Route::get('billings/purchasecreate', [BillingController::class, 'purchasecreate'])->name('billings.purchasecreate');
    Route::post('billings/purchasestore', [BillingController::class, 'purchasestore'])->name('billings.purchasestore');
    Route::get('convertToPurchase/{id}', [BillingController::class, 'convertToPurchase'])->name('convertToPurchase');
    Route::get('billings/debitnotecreate/{id}', [BillingController::class, 'debitnotecreate'])->name('billings.debitnotecreate');
    Route::post('billings/debitnotestore', [BillingController::class, 'debitnotestore'])->name('billings.debitnotestore');
    Route::get('billings/creditnotecreate/{id}', [BillingController::class, 'creditnotecreate'])->name('billings.creditnotecreate');
    Route::post('billings/creditnotestore', [BillingController::class, 'creditnotestore'])->name('billings.creditnotestore');
    Route::get('billings/paymentcreate', [BillingController::class, 'paymentcreate'])->name('billings.paymentcreate');
    Route::get('billings/receiptcreate', [BillingController::class, 'receiptcreate'])->name('billings.receiptcreate');
    Route::get('billings/quotationcreate', [BillingController::class, 'quotationcreate'])->name('billings.quotationcreate');
    Route::post('billings/status/{id}/{billingtype}', [BillingController::class, 'status'])->name('billings.status');
    Route::post('billings/search/{id}', [BillingController::class, 'search'])->name('billings.search');
    Route::post('billings/unapprovesearch/{id}', [BillingController::class, 'unapprovesearch'])->name('billings.unapprovesearch');
    Route::post('billings/cancelledsearch/{id}', [BillingController::class, 'cancelledsearch'])->name('billings.cancelledsearch');
    Route::post('billings/cancel/{billingtype}', [BillingController::class, 'cancel'])->name('billings.cancel');
    Route::post('billings/revive/{billingtype}', [BillingController::class, 'revive'])->name('billings.revive');

    Route::post('billings/unapprove', [BillingController::class, 'multiunapprove'])->name('billings.unapprove');
    Route::post('billings/approve', [BillingController::class, 'multiapprove'])->name('billings.approve');

    Route::get('billing/extra', [BillingController::class, 'extra'])->name('billings.extra');
    Route::get('report/{id}/{starting_date}/{ending_date}/{billing_type_id}', [BillingController::class, 'generateBillingReport'])->name('generateBillingReport');
    Route::get('posReport/{id}/{starting_date}/{ending_date}/{billing_type_id}', [BillingController::class, 'generateBillingPOSReport'])->name('generateBillingPOSReport');

    Route::get('salesRegister', [BillingController::class, 'salesRegister'])->name('salesRegister');
    Route::post('salesRegister/search', [BillingController::class, 'salesRegistersearch'])->name('salesRegister.search');
    Route::get('salesReturnRegister', [BillingController::class, 'salesReturnRegister'])->name('salesReturnRegister');
    Route::post('salesReturnRegister/search', [BillingController::class, 'salesReturnRegistersearch'])->name('salesReturnRegister.search');
    Route::get('purchaseRegister', [BillingController::class, 'purchaseRegister'])->name('purchaseRegister');
    Route::post('purchaseRegister/search', [BillingController::class, 'purchaseRegistersearch'])->name('purchaseRegister.search');
    Route::get('purchaseReturnRegister', [BillingController::class, 'purchaseReturnRegister'])->name('purchaseReturnRegister');
    Route::post('purchaseReturnRegister/search', [BillingController::class, 'purchaseReturnRegistersearch'])->name('purchaseReturnRegister.search');
    Route::post('billings/cancelledsearch/{id}', [BillingController::class, 'cancelledsearch'])->name('billings.cancelledsearch');

    Route::get('sendEmail/{id}', [BillingController::class, 'sendEmail'])->name('sendEmail');

    // Service Sales Billings
    Route::post('serviceSalesBills/unapprove', [SalesBillsController::class, 'multiunapprove'])->name('serviceSalesBillsUnapprove');
    Route::post('serviceSalesBills/approve', [SalesBillsController::class, 'multiapprove'])->name('serviceSalesBillsApprove');
    Route::get('generateSalesBillingReport', [SalesBillsController::class, 'generateSalesBillingReport'])->name('generateSalesBillingReport');
    // Route::get('generateSalesBillingReport/report', [SalesBillsController::class, 'index'])->name('generateSalesBillingReport');
    Route::get('unapprovedServiceBills', [SalesBillsController::class, 'unapprovedServiceBills'])->name('unapprovedServiceBills');
    Route::get('cancelledServiceBills', [SalesBillsController::class, 'cancelledServiceBills'])->name('cancelledServiceBills');
    Route::get('unapproveSingleServiceBill/{id}', [SalesBillsController::class, 'unapproveSingleServiceBill'])->name('unapproveSingleServiceBill');
    Route::post('unapproveSingleServiceBill/{id}', [SalesBillsController::class, 'unapproveSingleServiceBill'])->name('unapproveSingleServiceBill');
    Route::get('approveSingleServiceBill/{id}', [SalesBillsController::class, 'approveSingleServiceBill'])->name('approveSingleServiceBill');
    Route::post('cancelSingleServiceBill/{id}', [SalesBillsController::class, 'cancelSingleServiceBill'])->name('cancelSingleServiceBill');
    Route::get('reviveSingleServiceBill/{id}', [SalesBillsController::class, 'reviveSingleServiceBill'])->name('reviveSingleServiceBill');
    Route::post('searchResults/serviceSales', [SalesBillsController::class, 'search'])->name('searchResults');
    Route::post('serviceSaleBillPrint/{bill_id}', [SalesBillsController::class, 'printBill'])->name('serviceSalesBillPrinted');
    Route::get('serviceSalesBill/print/{id}', [SalesBillsController::class, 'printpreview'])->name('serviceSalesBillPrint');
    Route::get('pdf/generateServiceSalesBilling/{id}', [SalesBillsController::class, 'generateServiceSalesBillingPDF'])->name('pdf.generateServiceSalesBilling');
    Route::get('services/purchasecreate', [SalesBillsController::class, 'purchasecreate'])->name('service.purchasecreate');
    // Route::get('services/purchasestore', [SalesBillsController::class, 'purchasestore'])->name('service_sales.purchasestore');

    Route::post('pdf/ServiceSalesBillings', [SalesBillsController::class, 'serviceSalesBillingsPDF'])->name('pdf.ServiceSalesBillings');
    Route::post('pdf/ServiceSalesBillingsReport/{id}/{starting_date}/{ending_date}', [SalesBillsController::class, 'serviceSalesBillingsReportPDF'])->name('pdf.generateServiceSalesBillingReport');
    Route::post('exportServiceSalesBills/{id}', [ExportController::class, 'serviceSalesBillsexport'])->name('exportServiceSalesBills');
    Route::post('exportFilterServiceSalesBills/{id}/{start_date}/{end_date}', [ExportController::class, 'serviceSalesBillsFilterExport'])->name('exportFilterServiceSalesBills');
    Route::get('sendServiceSaleEmail/{id}', [SalesBillsController::class, 'sendServiceSaleEmail'])->name('sendServiceSaleEmail');
    ROute::resource('service_sales', SalesBillsController::class);
    ROute::get('serviceSaleCreditNote/{id}',[SalesBillsController::class,'serviceSaleCreditNote'])->name('serviceSaleCreditNote');
    ROute::get('serviceSaleDebitNote/{id}',[SalesBillsController::class,'serviceSaleDebitNote'])->name('serviceSaleDebitNote');

    // Send Purchase order Email
    Route::get('sendPurchaseOrderEmail/{id}', [PurchaseOrderController::class, 'sendPurchaseOrderEmail'])->name('sendPurchaseOrderEmail');

    //Print Count
    Route::post('billings/print/{bill_id}', [BillingController::class, 'billingprint'])->name('billing.print');

    Route::get('billings/unapprovedbillingsreport/{billing_type_id}', [BillingController::class, 'unapprovedbillingsreport'])->name('billings.unapproved');
    Route::get('billings/cancelledbillingsreport/{billing_type_id}', [BillingController::class, 'cancelledbillingsreport'])->name('billings.cancelled');
    Route::get('billings/billingsreport/{billing_type_id}', [BillingController::class, 'billingsreport'])->name('billings.report');
    Route::get('billings/credits', [BillingController::class, 'allbillingcredits'])->name('billing.billingcredits');
    Route::get('billings/credit/{id}', [BillingController::class, 'editBillingCredits'])->name('billing.editBillingCredits');
    Route::get('salesinvoice/{billing_type_id}', [BillingController::class, 'salesinvoice'])->name('salesinvoice');
    Route::get('salesInvoiceCreate', [BillingController::class, 'salesInvoiceCreate'])->name('salesInvoiceCreate');
    Route::get('salesInvoiceEdit/{id}', [BillingController::class, 'salesInvoiceEdit'])->name('salesInvoiceEdit');
    Route::get('convertToBill/{id}', [BillingController::class, 'convertToBill'])->name('convertToBill');
    Route::get('showSalesInvoice/{id}', [BillingController::class, 'showSalesInvoice'])->name('showSalesInvoice');
    Route::get('billings/billingsmaterialized/{billing_type_id}', [BillingController::class, 'materialized'])->name('billings.materialized');
    Route::post('billings/materializedsearch/{id}', [BillingController::class, 'materializedsearch'])->name('billings.materializedsearch');
    Route::get('billings/billingscbms/{billing_type_id}', [BillingController::class, 'cbms'])->name('billings.cbms');
    Route::post('billings/cbmssearch/{id}', [BillingController::class, 'cbmssearch'])->name('billings.cbmssearch');
    Route::get('billings/vatrefund', [BillingController::class, 'vatrefundreport'])->name('billing.vatrefund');
    Route::post('billings/vatrefundsearch', [BillingController::class, 'vatrefundsearch'])->name('billings.vatrefundsearch');

    // Credit Management
    Route::get('credit-customer', [CreditController::class, 'customerCredit'])->name('customerCredit');
    Route::resource('credit', CreditController::class);

    // Billing Print
    Route::get('billings/print/{id}', [BillingController::class, 'printpreview'])->name('billings.print');
    Route::post('billings/posprint', [BillingController::class, 'printpospreview'])->name('billings.posprint');
    Route::get('pdf/generateBilling/{id}', [BillingController::class, 'generateBillingPDF'])->name('pdf.generateBilling');
    Route::get('pdf/generateSalesInvoice/{id}', [BillingController::class, 'generateSalesInvoicePDF'])->name('pdf.generateSalesInvoice');
    Route::get('pdf/generatechallanBilling/{id}', [BillingController::class, 'generatechallanBillingPDF'])->name('pdf.generatechallanBilling');
    Route::get('letterheadpdf/generateBilling/{id}', [BillingController::class, 'generateBillingletterheadPDF'])->name('letterheadpdf.generateBilling');
    Route::get('pdf/generateBillingNonPictures/{id}', [BillingController::class, 'generateBillingNonPicturesPDF'])->name('pdf.generateBillingNonPictures');
    Route::resource('billings', BillingController::class);

    // Bill Payment Details
    Route::resource('paymentinfo', PaymentInfoController::class);


    // Export Journal CSV
    Route::get('exportjournal/{id}', [ExportController::class, 'journalexport'])->name('exportjournal');
    Route::get('exportfilterjournal/{id}/{start_date}/{end_date}', [ExportController::class, 'journalfilterexport'])->name('exportfilterjournal');

    // Export All Sales
    Route::post('exportSalesBills/{id}/{billingtype_id}', [ExportController::class, 'salesBillsexport'])->name('exportSalesBills');
    Route::post('exportfiltersalesBills/{id}/{start_date}/{end_date}/{billingtype_id}', [ExportController::class, 'salesBillsfilterexport'])->name('exportfiltersalesBills');

    // Download Trial Balance PDF
    Route::get('pdf/generateTrialBalance', [JournalVouchersController::class, 'generateTrialBalancePDF'])->name('pdf.generateTrialBalance');
    Route::get('pdf/generateTrialBalanceReport/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generateTrialBalanceReportPDF'])->name('pdf.generateTrialBalanceReport');

    // Download Sales Bills PDF
    Route::post('pdf/SalesBills/{billingtype_id}', [BillingController::class, 'salesBillsPDF'])->name('pdf.SalesBills');
    Route::post('pdf/SalesBillsReport/{id}/{starting_date}/{ending_date}/{billingtype_id}', [BillingController::class, 'salesBillsReportPDF'])->name('pdf.generateSalesBillingReport');

    // Export Trial Balance CSV
    Route::get('exporttrialbalance/{id}', [ExportController::class, 'trialbalanceexport'])->name('exporttrialbalance');
    Route::get('exportfiltertrialbalance/{id}/{start_date}/{end_date}', [ExportController::class, 'trialbalancefilterexport'])->name('exportfiltertrialbalance');

    // Accounting Ledgers
    Route::get('ledgers', [JournalVouchersController::class, 'ledgers'])->name('ledgers');
    Route::post('generateledgers', [JournalVouchersController::class, 'generateledgers'])->name('generateledgers');

    // Download Ledger PDF
    Route::get('pdf/generateLedgerReport/{fiscal_year_id}/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generateLedgerReportPDF'])->name('pdf.generateLedgerReport');

    // Export Ledger CSV
    Route::get('exportfilterledger/{fiscal_year_id}/{id}/{starting_date}/{ending_date}', [ExportController::class, 'ledgerfilterexport'])->name('exportfilterledger');

    // Download Profit and Loss Account PDF
    Route::get('pdf/generateProfitandLoss', [JournalVouchersController::class, 'generateProfitandLossPDF'])->name('pdf.generateProfitandLoss');
    Route::get('pdf/generateProfitandLossReport/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generateProfitandLossReportPDF'])->name('pdf.generateProfitandLossReport');

    // Export Profit and Loss CSV
    Route::get('exportprofitandloss/{id}', [ExportController::class, 'profitandlossexport'])->name('exportprofitandloss');
    Route::get('exportfilterprofitandloss/{id}/{starting_date}/{ending_date}', [ExportController::class, 'profitandlossfilterexport'])->name('exportfilterprofitandloss');

    // Download Balance Sheet PDF
    Route::get('pdf/generateBalanceSheet', [JournalVouchersController::class, 'generateBalanceSheetPDF'])->name('pdf.generateBalanceSheet');
    Route::get('pdf/generateBalanceSheetReport/{id}/{starting_date}/{ending_date}', [JournalVouchersController::class, 'generateBalanceSheetReportPDF'])->name('pdf.generateBalanceSheetReport');

    // Export Balance Sheet CSV
    Route::get('exportbalancesheet/{id}', [ExportController::class, 'balancesheetexport'])->name('exportbalancesheet');
    Route::get('exportfilterbalancesheet/{id}/{starting_date}/{ending_date}', [ExportController::class, 'balancesheetfilterexport'])->name('exportfilterbalancesheet');

    //Tax
    Route::resource('tax', TaxController::class);
    Route::post('tax/search', [TaxController::class, 'search'])->name('tax.search');
    Route::post('tax/mark-as-default', [TaxController::class, 'mark_as_default'])->name('tax.mark_as_default');


    //TaxInfo
    Route::get('taxcalculate', [TaxInfoController::class, 'taxcalculate'])->name('taxcalculate');
    Route::resource('taxinfo', TaxInfoController::class);

    // PaymentModes
    Route::resource('paymentmode', PaymentmodeController::class);
    Route::post('paymentmode/search', [PaymentmodeController::class, 'search'])->name('paymentmode.search');

    // Budget and Expenditure
    Route::get('budgetsetup', [BudgetController::class, 'budgetsetup'])->name('budgetsetup');

    // Fetch Budget Info
    Route::get('getinfo/{id}', [SupplierController::class, 'getinfo'])->name('getinfo');
    Route::get('getCreditInfo/{id}', [SupplierController::class, 'getCreditInfo'])->name('getCreditInfo');
    Route::get('budgetinfo', [BudgetController::class, 'budgetinfo'])->name('budgetinfo');
    Route::post('saveinfo', [BudgetController::class, 'saveinfo'])->name('saveinfo');
    Route::get('editbudget/{id}', [BudgetController::class, 'editbudget'])->name('editbudget');
    Route::get('budgetview/{id}', [BudgetController::class, 'budgetview'])->name('budgetview');
    Route::put('updatebudget/{id}', [BudgetController::class, 'updatebudget'])->name('updatebudget');
    Route::post('/budget/search', [BudgetController::class, 'search'])->name('budget.search');

    // Personal Share
    Route::resource('personal_share', PersonalShareController::class);
    Route::post('personalshare/search', [PersonalShareController::class, 'search'])->name('personalshare.search');
    Route::get('deletedPersonalShares', [PersonalShareController::class, 'deletedPersonalShares'])->name('deletedPersonalShares');
    Route::get('restorePersonalShare/{id}', [PersonalShareController::class, 'restorePersonalShare'])->name('restorePersonalShare');

    // Company Share
    Route::resource('company_share', CompanyShareController::class);
    Route::post('companyshare/search', [CompanyShareController::class, 'search'])->name('companyshare.search');
    Route::get('deletedcompanyShares', [CompanyShareController::class, 'deletedcompanyShares'])->name('deletedcompanyShares');
    Route::get('restorecompanyShare/{id}', [CompanyShareController::class, 'restorecompanyShare'])->name('restorecompanyShare');

    // Loan Management
    Route::resource('loan', LoanController::class);

    // Series
    Route::resource('series', SeriesController::class);
    Route::get('deletedseries', [SeriesController::class, 'deletedseries'])->name('deletedseries');
    Route::get('restoreseries/{id}', [SeriesController::class, 'restoreseries'])->name('restoreseries');

    // Bank Reconciliation Statement
    Route::put('reconciliationCashedOut/{id}', [ReconciliationController::class, 'reconciliationCashedOut'])->name('reconciliationCashedOut');
    Route::resource('bankReconciliationStatement', ReconciliationController::class);
    Route::get('deletedBankReconciliation', [ReconciliationController::class, 'deletedBankReconciliation'])->name('deletedBankReconciliation');
    Route::get('restoreBankReconciliation/{id}', [ReconciliationController::class, 'restoreBankReconciliation'])->name('restoreBankReconciliation');
    Route::post('restoreBankReconciliation/search', [ReconciliationController::class, 'search'])->name('searchBankReconciliation');

    // Bank Management
    Route::post('bank/search', [BankController::class, 'search'])->name('bank.search');
    Route::get('deletedBankInfo', [BankController::class, 'deletedBankInfo'])->name('deletedBankInfo');
    Route::get('restoreBankInfo/{id}', [BankController::class, 'restoreBankInfo'])->name('restoreBankInfo');
    Route::get('bankLedgers', [BankController::class, 'bankLedgers'])->name('bankLedgers');
    Route::post('bankLedgersPdf', [BankController::class, 'bankLedgersPdf'])->name('bankLedgersPdf');
    Route::get('bankledgerexportexcel', [ExportController::class, 'bankledgerexportexcel'])->name('bankledgerexportexcel');
    Route::resource('bank', BankController::class);

    // Bank Account Type
    // Route::post('bank/search', [BankController::class, 'search'])->name('bank.search');
    // Route::get('deletedBankInfo', [BankController::class, 'deletedBankInfo'])->name('deletedBankInfo');
    Route::put('changeStatus/{id}', [BankAccountTypeController::class, 'changeStatus'])->name('changeStatus');
    Route::resource('bankAccountType', BankAccountTypeController::class);

    // Godown
    Route::resource('godown', GodownController::class);
    Route::post('godown/search', [GodownController::class, 'search'])->name('godown.search');
    Route::get('deletedGodownInfo', [GodownController::class, 'deletedGodownInfo'])->name('deletedGodownInfo');
    Route::get('restoreGodownInfo/{id}', [GodownController::class, 'restoreGodownInfo'])->name('restoreGodownInfo');
    Route::post('godown/markasdefault', [GodownController::class, 'markasdefault'])->name('godown.mark_as_default');
    Route::get('getGodown',[GodownController::class,'getGodown'])->name('getGodown');
    Route::post('saveGodownProductStock',[GodownController::class,'saveGodownProductStock'])->name('saveGodownProductStock');
    Route::get('godownserialnumber',[GodownController::class,'godownserialnumber'])->name('godownserialnumber');

    // Damaged Products
    Route::resource('damaged_products', DamagedProductsController::class);
    Route::post('damagedproducts/search', [DamagedProductsController::class, 'search'])->name('damagedproduct.search');
    Route::get('deletedDamagedProducts', [DamagedProductsController::class, 'deletedDamagedProducts'])->name('deletedDamagedProducts');
    Route::get('restoreDamagedProducts/{id}', [DamagedProductsController::class, 'restoreDamagedProducts'])->name('restoreDamagedProducts');

    // Transfer Products
    Route::get('transferproducts/{id}', [ProductController::class, 'transferproducts'])->name('transferproducts');
    Route::post('transferNow', [ProductController::class, 'transferNow'])->name('transferNow');
    Route::get('fileManager', [ProductController::class, 'fileManager'])->name('fileManager');
    Route::post('productPostImage', [ProductController::class, 'productPostImage'])->name('productPostImage');
    Route::resource('transferReport', GodownTransferController::class);
    Route::post('transfers/search', [GodownTransferController::class, 'searchTransfer'])->name('transfer.search');
    Route::post('generateTransferReport', [GodownTransferController::class, 'generateTransferReport'])->name('generateTransferReport');

    // Filter Products
    Route::post('extra', [ProductController::class, 'extra'])->name('extraProduct');
    Route::get('filterProducts/{id}', [ProductController::class, 'filterProducts'])->name('filterProducts');

    // CSV product import
    Route::post('import', [ProductController::class, 'import'])->name('import');
    Route::post('importNonImporter', [ProductController::class, 'importNonImporter'])->name('importNonImporter');
    Route::post('importUpdate', [ProductController::class, 'importUpdate'])->name('importUpdate');
    Route::post('importNonUpdate', [ProductController::class, 'importNonUpdate'])->name('importNonUpdate');
    Route::get('export', [ProductController::class, 'export'])->name('export');
    Route::get('exportNonImporter', [ProductController::class, 'exportNonImporter'])->name('exportNonImporter');
    Route::get('exportUpdate', [ProductController::class, 'exportUpdate'])->name('exportUpdate');
    Route::get('exportNonUpdate', [ProductController::class, 'exportNonUpdate'])->name('exportNonUpdate');
    // Route::get('ajaxProducts', [ProductController::class, 'index'])->name('ajaxProducts');


    //IRD Form
    Route::get('irdSyncForm', [BillingController::class, 'irdSyncForm'])->name('irdSyncForm');

    // Purchase Order
    Route::get('unapprovedPurchaseOrders', [PurchaseOrderController::class, 'unapprovedPurchaseOrders'])->name('unapprovedPurchaseOrders');
    Route::get('cancelledPurchaseOrders', [PurchaseOrderController::class, 'cancelledPurchaseOrders'])->name('cancelledPurchaseOrders');
    Route::get('filterByNumber', [PurchaseOrderController::class, 'filterByNumber'])->name('filterByNumber');
    Route::get('purchaseOrderReport', [PurchaseOrderController::class, 'purchaseOrderReport'])->name('purchaseOrderReport');
    Route::post('purchaseOrderUnapprove', [PurchaseOrderController::class, 'multiunapprove'])->name('purchaseOrderUnapprove');
    Route::post('purchaseOrderApprove', [PurchaseOrderController::class, 'multiapprove'])->name('purchaseOrderApprove');
    Route::post('purchaseOrderStatus/{id}', [PurchaseOrderController::class, 'status'])->name('purchaseOrderStatus');
    Route::post('purchaseOrderCancel/{id}', [PurchaseOrderController::class, 'cancel'])->name('purchaseOrderCancel');
    Route::post('purchaseOrderRevive/{id}', [PurchaseOrderController::class, 'revive'])->name('purchaseOrderRevive');
    Route::get('pdf/generatePurchaseOrder/{id}', [PurchaseOrderController::class, 'generatePurchaseOrderPDF'])->name('pdf.generatePurchaseOrder');
    Route::post('purchaserOrder/search', [PurchaseOrderController::class, 'search'])->name('purchaserOrder.search');
    Route::post('purchaserOrder/unapprovesearch', [PurchaseOrderController::class, 'unapprovesearch'])->name('purchaserOrder.unapprovesearch');
    Route::post('purchaserOrder/cancelledsearch', [PurchaseOrderController::class, 'cancelledsearch'])->name('purchaserOrder.cancelledsearch');
    Route::get('purchaseOrder/clients', [PurchaseOrderController::class, 'clientorders'])->name('purchaseOrder.client');
    Route::post('purchaseOrder/clients/search', [PurchaseOrderController::class, 'clientorderssearch'])->name('clientOrder.search');
    Route::resource('purchaseOrder', PurchaseOrderController::class);

    // Clients
    Route::get('customersExport',[ExportController::class,'clientExport'])->name('customersExports');
    Route::get('clientsalesExport/{id}', [ExportController::class, 'clientsalesExport'])->name('clientsalesExport');
    Route::post('clientsalesPdf/{id}', [ClientController::class, 'clientsalesPdf'])->name('pdf.clientsales');
    Route::get('clientproducts/{id}', [ClientController::class, 'clientproducts'])->name('client.products');
    Route::get('clientsales/{id}', [ClientController::class, 'clientsales'])->name('client.sales');
    Route::get('clientcreditnote/{id}', [ClientController::class, 'clientcreditnote'])->name('client.creditnote');
    Route::post('customer/makedefault/', [ClientController::class, 'makedefault'])->name('customer.makedefault');
    Route::get('clientuser/create/{id}', [DealerUserController::class, 'create'])->name('clientuser.create');
    Route::get('clientuser/index/{id}', [DealerUserController::class, 'index'])->name('clientuser.index');
    Route::get('clientuser/edit/{id}', [DealerUserController::class, 'edit'])->name('clientuser.edit');
    Route::post('clientuser/store', [DealerUserController::class, 'store'])->name('clientuser.store');
    Route::put('clientuser/update/{id}', [DealerUserController::class, 'update'])->name('clientuser.update');
    Route::delete('clientuser/destroy/{id}', [DealerUserController::class, 'destroy'])->name('clientuser.destroy');
    Route::get('customersLedgers', [ClientController::class, 'customersLedgers'])->name('customersLedgers');
    Route::get('customersUnpaidLedgers', [ClientController::class, 'customersUnpaidLedgers'])->name('customersUnpaidLedgers');
    Route::post('customerLedgersgeneratepdf', [ClientController::class, 'customerLedgersgeneratepdf'])->name('customerLedgersgeneratepdf');
    Route::get('customerledgerexportexcel', [ExportController::class, 'customerledgerexportexcel'])->name('customerledgerexportexcel');
    Route::resource('client', ClientController::class);

    //biller
    Route::resource('biller', BillerController::class);

    //suspended sales
    Route::get('suspendedsale', [SuspendedSaleController::class, 'index'])->name('suspendedsale.index');
    Route::delete('suspendedsale/{suspendedbill}', [SuspendedSaleController::class, 'destroy'])->name('suspendedsale.delete');

    //Pos
    Route::get('/pos', [PointOfSaleController::class, 'index'])->name('pos.index');
    Route::get('/pos/suspendedsale/{id}', [PointOfSaleController::class, 'showSuspendedSale'])->name('pos.suspendedsale.index');
    Route::get('/pos/salesreport', [PointOfSaleController::class, 'posSalesReport'])->name('pos.sales');
    Route::post('/pos/switch-outlet', [PointOfSaleController::class, 'switchOuletBranch'])->name('pos.switchoutlet');
    Route::get('/pos/billing/{billing}/view-invoice', [PointOfSaleController::class, 'viewInvoiceBilling'])->name('pos.viewinvoice');
    Route::get('/pos/billing/{billing}/generateinvoice', [PointOfSaleController::class, 'generatePosInvoiceBilling'])->name('pos.generateinvoicebill');

    // Offers
    Route::resource('offer', OfferController::class);

    // POS Settings
    Route::get('pos-settings', [PosSettingsController::class, 'index'])->name('posSettings.index');
    Route::put('pos-settings/{id}', [PosSettingsController::class, 'update'])->name('posSettings.update');

    // API
    Route::get('apisupplier', [SupplierController::class, 'index'])->name('apisupplier');
    Route::post('apisupplier', [SupplierController::class, 'store'])->name('post.apisupplier');

    Route::get('apiclient', [SupplierController::class, 'clientindex'])->name('apiclient');
    Route::post('apiclient', [SupplierController::class, 'clientstore'])->name('post.apiclient');

    Route::get('apibrand', [SupplierController::class, 'brandindex'])->name('apibrand');

    Route::get('apibankinfo', [SupplierController::class, 'apibankinfo'])->name('apibankinfo');
    Route::post('apibankinfo', [SupplierController::class, 'bankinfostore'])->name('post.apibankinfo');

    Route::get('apiBankAccountType', [SupplierController::class, 'apiBankAccountType'])->name('apiBankAccountType');
    Route::post('apiBankAccountType', [SupplierController::class, 'bankAccountTypeStore'])->name('post.apiBankAccountType');

    Route::get('apiOnlinePortal', [SupplierController::class, 'apiOnlinePortal'])->name('apiOnlinePortal');
    Route::post('apiOnlinePortal', [SupplierController::class, 'onlinePortalstore'])->name('post.apiOnlinePortal');

    Route::get('api/allbillers', [SupplierController::class, 'allbillers'])->name('allbillers');
    Route::get('api/billers', [SupplierController::class, 'billers'])->name('billers');
    Route::get('api/getBillers/{id}', [SupplierController::class, 'getBillers'])->name('getBillers');
    Route::get('api/outlets', [SupplierController::class, 'outlets'])->name('outlets');
    Route::get('apiunits', [SupplierController::class, 'apiunits'])->name('apiunits');
    Route::get('apiSecondaryUnits/{id}', [SupplierController::class, 'apiSecondaryUnits'])->name('apiSecondaryUnits');
    Route::post('apiunits', [SupplierController::class, 'unitstore'])->name('post.apiunits');
    Route::post('storeProductCategory', [SupplierController::class, 'storeProductCategory'])->name('storeProductCategory');

    Route::get('apicategory', [App\Http\Controllers\API\CategoryController::class, 'index'])->name('apicategory');

    Route::get('apiproduct/{id}', [SupplierController::class, 'apiproduct'])->name('apiproduct');
    Route::get('apiService/{id}', [SupplierController::class, 'apiService'])->name('apiService');
    Route::get('apiServices', [SupplierController::class, 'apiServices'])->name('apiServices');
    Route::post('storeServices', [SupplierController::class, 'storeServices'])->name('storeServices');
    Route::get('apiServiceCategories', [SupplierController::class, 'apiServiceCategories'])->name('apiServiceCategories');
    Route::get('apiServiceFromCategories/{id}', [SupplierController::class, 'apiServiceFromCategories'])->name('apiServiceFromCategories');
    Route::get('apiproductImage/{id}', [SupplierController::class, 'apiproductImage'])->name('apiproductImage');

    Route::get('apigodown_stock/{id}', [SupplierController::class, 'godown_stock'])->name('godown_stock');

    Route::get('apigodown/{id}', [SupplierController::class, 'godown'])->name('godown');
    Route::get('api/filterGodown/{id}', [SupplierController::class, 'filterGodown'])->name('filterGodown');
    Route::get('api/allSerialNumbers/{godown_id}/{product_id}', [SupplierController::class, 'allSerialNumbers'])->name('allSerialNumbers');
    Route::get('api/outletSerialNumbers/{product_id}', [SupplierController::class, 'outletSerialNumbers'])->name('outletSerialNumbers');
    Route::get('api/serialNumbers/{godown_id}/{product_id}', [SupplierController::class, 'serialNumbers'])->name('serialNumbers');
    Route::get('api/damagedSerialNumbers/{godown_id}/{product_id}', [SupplierController::class, 'damagedSerialNumbers'])->name('damagedSerialNumbers');


    Route::get('quotationsetting/{id}', [QuotationSettingController::class, 'quotationsetting'])->name('quotation.setting');
    Route::post('quotationsetting/update/{id}', [QuotationSettingController::class, 'quotationsettingupdate'])->name('quotationsetting.update');
    Route::resource('quotationfollowup', QuotationFollowupController::class);
    Route::get('supersetting/edit/{id}', [SuperSettingController::class, 'edit'])->name('supersetting.edit');
    Route::get('discountSetting', [SuperSettingController::class, 'discountSetting'])->name('discountSetting');
    Route::get('creditSettings', [SuperSettingController::class, 'creditSettings'])->name('creditSettings');
    Route::put('supersetting/update/{id}', [SuperSettingController::class, 'update'])->name('supersetting.update');
    Route::get('backup', [BackupInfoController::class, 'index'])->name('backup.index');

    Route::get('transferrecords/{product_id}', [WarehousePosTransferController::class, 'transferrecords'])->name('outletproduct.transfer');
    Route::get('outletrecords/{outlet_id}', [WarehousePosTransferController::class, 'outletrecords'])->name('outletrecord.product');
    Route::post('outletStockEdit/{outlet_id}', [WarehousePosTransferController::class, 'outletStockEdit'])->name('outletStockEdit');
    Route::post('stockTransferToGodown/{outlet_id}', [WarehousePosTransferController::class, 'stockTransferToGodown'])->name('stockTransferToGodown');
    Route::resource('outlettransfer', WarehousePosTransferController::class);

    //Stock Out
    Route::get('stockout/approve/{id}', [StockOutController::class, 'approve'])->name('stockout.approve');
    Route::get('stockout/unapprove/{id}', [StockOutController::class, 'unapprove'])->name('stockout.unapprove');
    Route::get('stockout/unapprovedindex', [StockOutController::class, 'unapprovedindex'])->name('stockout.unapprovedindex');
    Route::post('stockout/search', [StockOutController::class, 'stockoutsearch'])->name('searchstockout');
    Route::post('stockout/unapprovedsearch', [StockOutController::class, 'stockoutunapprovedsearch'])->name('searchunapprovestockout');
    Route::resource('stockout', StockOutController::class);
    Route::post('/search-product-barcode', [SalesBarcodeController::class, 'getProduct'])->name('product-barcode-search');
    Route::post('/search-product-name', [SalesBarcodeController::class, 'productSearch'])->name('product-name-search');


    //Import Routes
    Route::post('/journals-import', [ImportSuppliersController::class, 'journals'])->name('journals-import');
    Route::post('/suppliers-import', [ImportSuppliersController::class, 'suppliers'])->name('suppliers-import');
    Route::post('/customers-import', [ImportSuppliersController::class, 'customers'])->name('customers-import');
    Route::post('/sales-import', [ImportSuppliersController::class, 'sales'])->name('sales-import');
    Route::post('/purchase-import', [ImportSuppliersController::class, 'purchase'])->name('purchase-import');

    Route::post('/suppliers-import-demo', [ImportSuppliersController::class, 'suppliersDemoExcel'])->name('suppliers-import-demo');
    Route::post('/customers-import-demo', [ImportSuppliersController::class, 'customersDemoExcel'])->name('customers-import-demo');
    Route::post('/journals-import-demo', [ImportSuppliersController::class, 'journalsDemoExcel'])->name('journals-import-demo');
    Route::post('/sales-import-demo', [ImportSuppliersController::class, 'salesDemoExcel'])->name('sales-import-demo');
    Route::post('/purchase-import-demo', [ImportSuppliersController::class, 'purchaseDemoExcel'])->name('purchase-import-demo');

    Route::resource('fiscal_year',App\Http\Controllers\FiscalYearController::class);

    Route::resource('vat_refund',App\Http\Controllers\VatrefundController::class);
    Route::get('/vat_refund/vatRefundVerificationStatus/{id}',[App\Http\Controllers\VatrefundController::class,'vatRefundVerificationStatus'])->name('vatRefundVerificationStatus');

    Route::get('backup/store', function () {
        Artisan::call('backup:run', [
            '--only-db' => true,
        ]);
        return redirect()->route('backup.store');
    })->name('backup.create');

    Route::get('backup/create', [BackupInfoController::class, 'create'])->name('backup.store');

    Route::get('expired', function () {
        return view('backend.expired');
    })->name('expired');
});
