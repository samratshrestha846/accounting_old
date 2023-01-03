<?php

namespace App\Http\Controllers;

use App\Actions\CreateProductAction;
use App\Actions\UpdateProductAction;
use App\Exports\ExcelExport;
use App\Exports\NonImporterExport;
use App\Exports\UpdateExcelExport;
use App\Exports\UpdateNonImporter;
use App\FormDatas\ProductFormData;
use App\Imports\ProductNonImporter as ImportsProductNonImporter;
use App\Imports\ProductNonUpdate;
use App\Imports\ProductsImport;
use App\Imports\ProductUpdate;
use App\Models\BillingExtra;
use App\Models\Brand;
use App\Models\Category;
use App\Models\DamagedProducts;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\GodownTransfer;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductStock;
use App\Models\Province;
use App\Models\SalesRecord;
use App\Models\SalesReturnRecord;
use App\Models\Series;
use App\Models\Service;
use App\Models\StockOut;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\UserCompany;
use App\Models\Vendor;
use App\Models\WarehousePosTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PDF;
use Throwable;
use Session;

use function App\NepaliCalender\datenep;
use Yajra\Datatables\Datatables;

class ProductController extends Controller
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

    public function ajaxgetProducts(){
        $products = Product::leftJoin('brands','brands.id','=','products.brand_id')->select('brands.brand_name','products.id','products.total_cost','products.primary_unit','products.has_serial_number','products.product_code','products.product_name')->orderBy('products.product_name','desc')->get();

        return json_encode($products);
    }

    public function index(Request $request)
    {

        if ($request->user()->can('view-products')) {

            if ($request->ajax()) {
                $query = new Product;
                $query = $query->with(['category:id,category_name'])->with(['brand:id,brand_name','series']);
                if (isset($request->filtercolumn) && !empty($request->filtercolumn)) {
                    $query = $query->orderBy($request->filtercolumn, $request->filterby);
                }


                $datatables = new Datatables;
                return Datatables::of($query)

                    ->addColumn('cate_name', function ($row) {
                        // dd($row);
                        $category = $row->category->category_name ?? '';
                        if ($category) {
                            return $category;
                        } else {
                            return '';
                        }
                    })

                    ->editColumn('total_stock', function ($row) {
                        if ($row->secondary_unit == null) {
                            $total_stock = $row->total_stock . ' ' . $row->primary_unit;
                        } else {
                            $total_stock = $row->total_stock . ' ' . $row->primary_unit
                                . '<br>(' . $row->primary_number . ' ' . $row->primary_unit . ' contains ' . $row->secondary_number . ' ' . $row->secondary_unit . ')';
                        }

                        return $total_stock;
                    })
                    ->addColumn('brand_name', function ($row) {
                        $brand = $row->brand->brand_name ?? '';
                        if ($brand) {
                            return $brand;
                        } else {
                            return '';
                        }
                    })
                    ->addColumn('series_name', function ($row) {
                        $series_name = $row->series->series_name ?? '';
                        if ($series_name) {
                            return $series_name;
                        } else {
                            return '';
                        }
                    })
                    ->editColumn('margin_type', function ($row) {
                        if(!empty($row->margin_type) || !empty($row->margin_value)){
                            $margin_type = ($row->margin_type == "fixed") ? 'Rs.' . $row->profit_margin : $row->margin_value . ' %';
                            return $margin_type;
                        }
                        return '';
                    })
                    ->editColumn('status', function ($row) {
                        $status = ($row->status == "1") ? 'Approved' : 'Not Approved';
                        return $status;
                    })
                    ->addColumn('stock_validation', function ($row) {
                        $stockvalidation = 'Rs.' . number_format($row->total_stock * $row->product_price, 2);
                        return $stockvalidation;
                    })

                    ->addColumn('action', function ($row) {

                        $editurl = route('product.edit', $row->id);
                        $showurl = route('product.show', $row->id);
                        $deleteurl = route('product.destroy', $row->id);
                        $printbarcode = route('barcodeprint', ['id' => $row->id, 'quantity' => 501]);
                        $printqrcode = route('qrcodeprint', ['id' => $row->id, 'quantity' => 501]);
                        $csrf_token = csrf_token();
                        $btn = '<div class="btn-bulk justify-content-center">';
                        if ($row->has_serial_number == 0) {
                            $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                            <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                            <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteproduct$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                            <a href='#' class='edit btn btn-secondary icon-btn btn-sm' title='Barcode' data-toggle='modal' data-target='#barcodeprint$row->id'><i class='fas fa-barcode'></i></a>
                            <a href='#' class='edit btn btn-primary icon-btn btn-sm' title='QRcode' data-toggle='modal' data-target='#qrcodeprint$row->id'><i class='fas fa-qrcode'></i></a>

                            <!-- Modal -->
                                <div class='modal fade text-left' id='deleteproduct$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                            </div>
                                            <div class='modal-body text-center'>
                                                <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                <label for='reason'>Are you sure you want to delete??</label><br>
                                                <input type='hidden' name='_method' value='DELETE' />
                                                    <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='modal fade' id='barcodeprint$row->id' tabindex='-1' role='dialog' aria-labelledby='barcodeprint$row->idLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                            <h5 class='modal-title' id='barcodeprint$row->idLabel'>$row->product_name Barcode</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                            </div>
                                            <div class='modal-body'>
                                            <input type='hidden' class='pro_id' name='pro_id' value='$row->id'>
                                            <div class='row'>
                                                <div class='col-md-10'>
                                                    <input type='number' class='form-control tot_num' name='tot_num' placeholder='Enter Print Quantity Max(500)'>
                                                </div>
                                                <div class='col-md-2 pl-0'>
                                                    <a href='javascript:void(0)' class='btn btn-primary print' data-dismiss='modal'>Print</a>
                                                </div>
                                            </div>
                                            <p class='text-danger msg off'>Quantity can't be more than 500 </p>
                                            <a style='display:none;' class=' btnprint link'>click</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='modal fade' id='qrcodeprint$row->id' tabindex='-1' role='dialog' aria-labelledby='qrcodeprint$row->idLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                            <h5 class='modal-title' id='qrcodeprint$row->idLabel'>$row->product_name QRcode</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                            </div>
                                            <div class='modal-body'>
                                            <input type='hidden' class='qrpro_id' name='pro_id' value='$row->id'>
                                            <div class='row'>
                                                <div class='col-md-10'>
                                                    <input type='number' class='form-control qrtot_num' name='tot_num' placeholder='Enter Print Quantity Max(500)'>
                                                </div>
                                                <div class='col-md-2 pl-0'>
                                                    <a href='javascript:void(0)' class='btn btn-primary qrprint' data-dismiss='modal'>Print</a>
                                                </div>
                                            </div>
                                            <p class='text-danger qrmsg off'>Quantity can't be more than 500 </p>
                                            <a style='display:none;' class=' qrbtnprint link'>click</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";
                        } else if ($row->has_serial_number == 1) {
                            $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteproduct$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                <a href='$printbarcode' class='serial_number_print_barcode btn btn-secondary icon-btn btn-sm' title='Barcode'><i class='fas fa-barcode'></i></a>
                                <a href='$printqrcode' class='serial_number_print_qrcode btn btn-primary icon-btn btn-sm' title='QRcode'><i class='fas fa-qrcode'></i></a>

                                <!-- Modal -->
                                    <div class='modal fade text-left' id='deleteproduct$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body text-center'>
                                                    <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                    <label for='reason'>Are you sure you want to delete??</label><br>
                                                    <input type='hidden' name='_method' value='DELETE' />
                                                        <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
                        }
                        $btn .= "</div>";
                        return $btn;
                    })
                    ->rawColumns(['total_stock', 'cate_name','brand_name', 'series_name', 'margin_type', 'status', 'stock_validation', 'action'])
                    ->make(true);
            }

            // $products = Product::latest()->paginate(10);
            $totalproductvalidation =  Product::all()->where('status',1)->sum(function($query) {
                return $query->total_cost * $query->total_stock;
            });



            $services = Service::latest()->paginate(10);
            $godowns = Godown::latest()->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            $filterby = '';
            $filtercolumn = '';

            return view('backend.product_service.index', compact('godowns', 'currentcomp', 'services', 'filtercolumn', 'filterby', 'totalproductvalidation'));
        } else {
            return view('backend.permission.permission');
        }
    }

    // public function searchproduct(Request $request)
    // {
    //     $search = $request->input('search');
    //     $products = Product::query()
    //         ->where('product_name', 'LIKE', "%{$search}%")
    //         ->orWhere('product_code', 'LIKE', "%{$search}%")
    //         ->latest()
    //         ->paginate(10);
    //     $godowns = Godown::latest()->get();
    //     $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
    //     return view('backend.product_service.searchproduct', compact('products', 'godowns', 'currentcomp'));
    // }

    public function deletedproduct(Request $request)
    {
        DB::beginTransaction();
        try{
            if ($request->user()->can('manage-trash')) {
                $products = Product::onlyTrashed()->latest()->paginate(10);
                DB::commit();
                return view('backend.trash.itemstrash', compact('products'));
            } else {
                return view('backend.permission.permission');
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        // dd('HI');
        if ($request->user()->can('create-product')) {
            // $categories = Category::orderBy('in_order', 'asc')->get();
            $categories = Category::whereNull('category_id')
                ->with('childrenCategories')
                ->orderBy('in_order', 'asc')
                ->get();
            // dd($categories);
            $units = Unit::latest()->get();
            $godowns = Godown::latest()->get();
            $suppliers = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $products = Product::latest()->get();
            $services = Service::latest()->get();
            $brands = Brand::latest()->get();

            $taxes = Tax::latest()->get();

            $product_code = 'PD' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $secondary_code = 'SP' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $category_code = 'CT' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $brand_code = 'BD' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $unit_code = 'UT' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $company_code = 'SU' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);

            $allprocodes = [];
            foreach ($products as $product) {
                array_push($allprocodes, $product->product_code);
            }

            $allsecondarycodes = [];
            foreach ($products as $product) {
                array_push($allsecondarycodes, $product->secondary_code);
            }

            $allservicecodes = [];
            foreach ($services as $service) {
                array_push($allservicecodes, $service->service_code);
            }

            $allcategorycodes = [];
            foreach ($categories as $category) {
                array_push($allcategorycodes, $category->category_code);
            }

            $allsuppliercodes = [];
            foreach ($suppliers as $supplier) {
                array_push($allsuppliercodes, $supplier->supplier_code);
            }

            $allunitcodes = [];
            foreach ($units as $unit) {
                array_push($allunitcodes, $unit->unit_code);
            }

            $allbrandcodes = [];
            foreach ($brands as $brand) {
                array_push($allbrandcodes, $brand->brand_code);
            }

            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            return view('backend.product_service.create', compact('categories', 'taxes', 'units', 'provinces', 'godowns', 'suppliers', 'allprocodes', 'allservicecodes', 'allcategorycodes', 'allsuppliercodes', 'allunitcodes', 'allbrandcodes', 'currentcomp', 'product_code', 'secondary_code', 'category_code', 'brand_code', 'unit_code', 'company_code'));
        } else {
            return view('backend.permission.permission');
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
        //   dd($request->all());
        $saveandcontinue = $request->saveandcontinue ?? 0;


        if ($request->user()->can('create-product')) {
            // dd($request->all());
            $currentcomp =  UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->with('company')->first();
            $this->validate($request, [
                'product_name' => 'required',
                'product_code' => ['required', 'string', Rule::unique('products')->where(function ($query) use ($currentcomp) {
                    return $query->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id);
                })],
                'category' => 'required',
                'size' => '',
                'stock' => '',
                'weight' => '',
                'lot_no' => '',
                'stockTotal' => '',
                'color' => '',
                'original_vendor_price' => 'required',
                'charging_rate' => '',
                'final_vendor_price' => 'required',
                'carrying_cost' => '',
                'transportation_cost' => '',
                'miscellaneous_percent' => '',
                'other_cost' => '',
                'product_cost' => 'required',
                'custom_duty' => '',
                'after_custom' => '',
                'tax' => ['nullable', 'exists:taxes,id'],
                'total_cost' => '',
                'margin_type' => '',
                'margin_value' => '',
                'product_price' => 'required',
                // 'secondary_unit_selling_price'=>'',
                'description' => '',
                'status' => '',
                'primary_number' => 'required',
                'primary_unit' => 'required',
                'secondary_number' => '',
                'secondary_unit' => '',
                // 'secondary_code' => 'required',
                'godown' => ['array'],
                'godown.*' => [],
                'supplier_id' => '',
                'brand' => '',
                'series' => '',
                'refundable' => '',
                'product_image' => ['array'],
                'product_image.*' => 'image|mimes:png,jpg,jpeg|max:2048',
                'alert_on' => '',
                'floor_on' => ['sometimes', 'array'],
                'floor_on.*' => ['nullable'],
                'rack_on' => ['sometimes', 'array'],
                'rack_on.*' => ['sometimes'],
                'row_on' => ['sometimes', 'array'],
                'row_on.*' => ['row_no'],
                'expiry_date' => ['nullable', 'date_format:Y-m-d'],
                'warranty_months' => '',
                'selected_filter_option' => '',
                'opening_balance'=> 'required',
                'behaviour'=>'required',
            ]);


            $isImporter = auth()->user()->isImporter();

            $godowns = [];

            if (!$request['stockTotal'] == null) {
                foreach ($request->godown as $key => $value) {
                    $godowns[] = [
                        'godown_id' => $value,
                        'floor_no' => $request['floor_no'][$key] ?? null,
                        'rack_no' => $request['rack_no'][$key] ?? null,
                        'alert_no' => $request['alert_on'][$key] ?? null,
                        'stock' => $request['stock'][$key] ?? null,
                        'serial_numbers' => $request['serial_numbers_' . ($key + 1)] ?? [],
                    ];
                }
            }


            // dd($request->series_id);
            $primary_unit_id = Unit::where('unit_code', $request->primary_unit)->first()->id;
            // dd($primary_unit_id);
            if ($request->secondary_unit) {
                $secondary_unit_id = Unit::where('unit_code', $request->secondary_unit)->first()->id;
            } else {
                $secondary_unit_id = null;
            }
            DB::beginTransaction();
            try{


            $productFormData = (new ProductFormData(
                $request->product_name,
                $request->product_code,
                $request->category,
                $request->supplier_id,
                $request->declaration_form_no,
                $request->check_serial_number ? true : false,
                $godowns,
                $request->primary_number,
                $primary_unit_id,
                $request->secondary_number,
                $secondary_unit_id,
                $request->brand,
                $request->series,
                $request->size,
                $request->weight,
                $request->lot_no,
                $request->color,
                $request->description,
                $request->status ? true : false,
                $request->refundable ? true : false,
                (float) $request->original_vendor_price,
                (float) $request->charging_rate,
                (float) $request->carrying_cost,
                (float) $request->transportation_cost,
                (float) $request->miscellaneous_percent,
                (float) $request->custom_duty,
                $request->margin_type,
                (float) $request->margin_value,
                (float) $request->product_price,
                $request->tax ? (int) $request->tax : 0,
                $request->hasFile('product_image') ? $request->file('product_image') : [],
                $request->warranty_months,
                $request->manufacturing_date,
                $request->expiry_date,
                $request->selected_filter_option,
                $request->opening_balance,
                $request->behaviour,
            ))
                ->setIsImport($isImporter);

            $product = (new CreateProductAction())->execute(auth()->user(), $productFormData);
            DB::commit();

            if ($request->ajax()) {

                return $product;
            }
            if ($saveandcontinue == 1) {
                return redirect()->route('product.create')->with('success', 'Product information is successfully inserted.');
            } else {
                return redirect()->route('product.index')->with('success', 'Product information is successfully inserted.');
            }
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
            //code written by anish
            // $currcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            // $importer = $currcomp->company->is_importer;
            // if ($importer == 1) {
            //     if ($request['margin_type'] == 'percent' && !$request['margin_value'] == null) {
            //         $profit_margin = ($request['total_cost'] * $request['margin_value']) / 100;
            //     } elseif ($request['margin_type'] == 'fixed' && !$request['margin_value'] == null) {
            //         $profit_margin = $request['margin_value'];
            //     } else {
            //         $profit_margin = null;
            //     }
            // } else {
            //     if ($request['margin_type'] == 'percent' && !$request['margin_value'] == null) {
            //         $profit_margin = ($request['original_vendor_price'] * $request['margin_value']) / 100;
            //     } elseif ($request['margin_type'] == 'fixed' && !$request['margin_value'] == null) {
            //         $profit_margin = $request['margin_value'];
            //     } else {
            //         $profit_margin = null;
            //     }
            // }

            // if ($request['status'] == null) {
            //     $status = 0;
            // } else {
            //     $status = 1;
            // }

            // if ($request['refundable'] == null) {
            //     $refundable = 0;
            // } else {
            //     $refundable = 1;
            // }

            // $primary_unit = Unit::where('unit_code', $request['primary_unit'])->first();
            // $secondary_unit = Unit::where('unit_code', $request['secondary_unit'])->first();

            // $product = Product::create([
            //     'product_name' => $request['product_name'],
            //     'product_code' => $request['product_code'],
            //     'category_id' => $request['category'],
            //     'size' => $request['size'],
            //     'opening_stock' => $request['stockTotal'],
            //     'total_stock' => $request['stockTotal'],
            //     'color' => $request['color'],
            //     'original_vendor_price' => $request['original_vendor_price'],
            //     'charging_rate' => $request['charging_rate'],
            //     'final_vendor_price' => $request['final_vendor_price'],
            //     'carrying_cost' => $request['carrying_cost'],
            //     'transportation_cost' => $request['transportation_cost'],
            //     'miscellaneous_percent' => $request['miscellaneous_percent'],
            //     'other_cost' => $request['other_cost'],
            //     'cost_of_product' => $request['product_cost'],
            //     'custom_duty' => $request['custom_duty'],
            //     'after_custom' => $request['after_custom'],
            //     'tax' => $request['tax'],
            //     'total_cost' => $request['total_cost'],
            //     'margin_type' => $request['margin_type'],
            //     'margin_value' => $request['margin_value'],
            //     'profit_margin' => $profit_margin,
            //     'product_price' => $request['product_price'],
            //     'status' => $status,
            //     'description' => $request['description'],
            //     'primary_number' => $request['primary_number'],
            //     'primary_unit' => $primary_unit->unit,
            //     'primary_unit_id' => $primary_unit->id,
            //     'primary_unit_code' => $request['primary_unit'],
            //     'secondary_number' => $request['secondary_number'],
            //     'secondary_unit' => $secondary_unit->unit,
            //     'secondary_unit_id' => $secondary_unit->id,
            //     'secondary_unit_code' => $request['secondary_unit'],
            //     'supplier_id' => $request['supplier_id'],
            //     'brand_id' => $request['brand'],
            //     'series_id' => $request['series'],
            //     'refundable' => $refundable,
            //     'expiry_date' => $request['expiry_date'],
            // ]);

            // $imagename = '';
            // if ($request->hasfile('product_image')) {
            //     $images = $request->file('product_image');
            //     foreach ($images as $image) {
            //         $imagename = $image->store('item_images', 'uploads');
            //         $product_images = ProductImages::create([
            //             'product_id' => $product['id'],
            //             'location' => $imagename,
            //         ]);
            //         $product_images->save();
            //     }
            // }

            // for ($i = 0; $i < count($request['stock']); $i++) {
            //     GodownProduct::create([
            //         'godown_id' => $request->godown[$i],
            //         'product_id' => $product->id,
            //         'opening_stock' => $request->stock[$i],
            //         'stock' => $request->stock[$i],
            //         'alert_on' => $request->alert_on[$i],
            //         'floor_no' => $request->floor_no[$i],
            //         'rack_no' => $request->rack_no[$i],
            //         'row_no' => $request->row_no[$i],
            //     ]);
            // }

            // $product->save();
            // return redirect()->route('product.index')->with('success', 'Product information is successfully inserted.');
        } else {
            return view('backend.permission.permission');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id, Request $request)
    {
        if ($request->user()->can('view-products')) {
            $product = Product::findorFail($id);
            $godowns = Godown::latest()->get();
            $product_images = ProductImages::where('product_id', $product->id)->latest()->get();
            $godown_products = GodownProduct::where('product_id', $product->id)->where('stock', '>', 0)->latest()->get();
            $user = Auth::user()->id;
            $productstocks = ProductStock::where('product_id', $id)->latest()->get();
            // dd( $productstocks );
            $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->with('company')->first();

            return view('backend.product_service.viewproduct', compact('product', 'godowns', 'product_images', 'godown_products', 'currentcomp', 'productstocks'));
        } else {
            return view('backend.permission.permission');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id, Request $request)
    {
        if ($request->user()->can('edit-product')) {
            $product = Product::findorFail($id);
            // $categories = Category::latest()->get();
            $categories = Category::with('childrenCategories')
                ->orderBy('in_order', 'asc')
                ->get();
            $product_images = ProductImages::where('product_id', $product->id)->get();
            $godown_products = GodownProduct::with('serialnumbers')->where('product_id', $product->id)->latest()->get();
            // dd($godown_products);
            $units = Unit::latest()->get();
            $suppliers = Vendor::latest()->get();

            $godowns = Godown::latest()->get();
            $brands = Brand::latest()->get();
            $related_series = Series::where('brand_id', $product->brand_id)->latest()->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            $taxes = Tax::latest()->get();

            $selected_filter =  json_decode($product->selected_filter_option, true);
            $selected_filter_option = $selected_filter;
            // dd($selected_filter_option);
            return view('backend.product_service.editproduct', compact('taxes', 'product', 'related_series', 'categories', 'brands', 'currentcomp', 'suppliers', 'units', 'product_images', 'godown_products', 'godowns'));
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{

            if ($request->user()->can('edit-product')) {
                $product = Product::findorFail($id);
                $currentcomp =  UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->with('company')->first();
                if (isset($_POST['submit'])) {
                    $this->validate($request, [
                        'product_name' => 'required',
                        'product_code' => ['required', 'string', Rule::unique('products')->where(function ($query) use ($currentcomp, $id) {
                            return $query->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('id', '!=', $id);
                        })],
                        'category' => 'required',
                        'size' => '',
                        'weight' => '',
                        'lot_no' => '',
                        'stockTotal' => '',
                        'color' => '',
                        'godown' => '',
                        'stock' => '',
                        'original_vendor_price' => 'required',
                        'charging_rate' => '',
                        'final_vendor_price' => 'required',
                        'carrying_cost' => '',
                        'transportation_cost' => '',
                        'miscellaneous_percent' => '',
                        'other_cost' => '',
                        'product_cost' => 'required',
                        'custom_duty',
                        'after_custom',
                        'tax',
                        'total_cost',
                        'margin_type' => '',
                        'margin_value' => '',
                        'product_price' => 'required',
                        'description' => '',
                        'status' => '',
                        'supplier_id' => '',
                        'brand' => '',
                        'series' => '',
                        'refundable' => '',
                        'primary_number' => 'required',
                        'primary_unit' => 'required',
                        'secondary_number' => '',
                        'secondary_unit' => '',
                        // 'secondary_code' => '',
                        'alert_on' => '',
                        'floor_on' => '',
                        'rack_on' => '',
                        'row_on' => '',
                        'expiry_date' => '',
                        'warranty_months' => '',
                        'selected_filter_option' => '',
                    ]);

                    $isImporter = auth()->user()->isImporter();

                    $godowns = [];
                    if (!$request['stockTotal'] == null) {
                        foreach ($request->godown as $key => $value) {
                            $godowns[] = [
                                'godown_id' => $value,
                                'floor_no' => $request['floor_no'][$key],
                                'rack_no' => $request['rack_no'][$key],
                                'alert_no' => $request['alert_on'][$key],
                                'stock' => $request['stock'][$key],
                                'serial_numbers' => $request['serial_numbers_' . ($key + 1)] ?? [],
                            ];
                        }
                    }
                    $primary_unit_id = Unit::where('unit_code', $request->primary_unit)->first()->id;
                    if ($request->secondary_unit) {
                        $secondary_unit_id = Unit::where('unit_code', $request->secondary_unit)->first()->id;
                    } else {
                        $secondary_unit_id = null;
                    }

                    $behaviour = 'debit';
                    $opening_balance = 0;
                    $productFormData = (new ProductFormData(
                        $request->product_name,
                        $request->product_code,
                        $request->category,
                        $request->supplier_id,
                        $request->declaration_form_no,
                        $request->check_serial_number ? true : false,
                        $godowns,
                        $request->primary_number,
                        $primary_unit_id,
                        $request->secondary_number,
                        $secondary_unit_id,
                        $request->brand,
                        $request->series,
                        $request->size,
                        $request->weight,
                        $request->lot_no,
                        $request->color,
                        $request->description,
                        $request->status ? true : false,
                        $request->refundable ? true : false,
                        (float) $request->original_vendor_price,
                        (float) $request->charging_rate,
                        (float) $request->carrying_cost,
                        (float) $request->transportation_cost,
                        (float) $request->miscellaneous_percent,
                        (float) $request->custom_duty,
                        $request->margin_type,
                        (float) $request->margin_value,
                        (float) $request->product_price,
                        $request->tax ? (int) $request->tax : 0,
                        $request->hasFile('product_image') ? $request->file('product_image') : [],
                        $request->warranty_months,
                        $request->manufacturing_date,
                        $request->expiry_date,
                        $request->selected_filter_option,
                        $opening_balance,
                        $behaviour,
                    ))
                        ->setIsImport($isImporter);

                    (new UpdateProductAction())->execute(auth()->user(), $product, $productFormData);
                    DB::commit();
                    return redirect()->route('product.edit', $id)->with('success', 'Product information is successfully updated.');

                    //From here anish code
                    //     $currcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
                    //     $importer = $currcomp->company->is_importer;
                    //     if ($importer == 1) {
                    //         if ($request['margin_type'] == 'percent' && !$request['margin_value'] == null) {
                    //             $profit_margin = ($request['total_cost'] * $request['margin_value']) / 100;
                    //         } elseif ($request['margin_type'] == 'fixed' && !$request['margin_value'] == null) {
                    //             $profit_margin = $request['margin_value'];
                    //         } else {
                    //             $profit_margin = null;
                    //         }
                    //     } else {
                    //         if ($request['margin_type'] == 'percent' && !$request['margin_value'] == null) {
                    //             $profit_margin = ($request['original_vendor_price'] * $request['margin_value']) / 100;
                    //         } elseif ($request['margin_type'] == 'fixed' && !$request['margin_value'] == null) {
                    //             $profit_margin = $request['margin_value'];
                    //         } else {
                    //             $profit_margin = null;
                    //         }
                    //     }

                    //     if ($request['status'] == null) {
                    //         $status = 0;
                    //     } else {
                    //         $status = 1;
                    //     }

                    //     if ($request['refundable'] == null) {
                    //         $refundable = 0;
                    //     } else {
                    //         $refundable = 1;
                    //     }

                    //     $primary_unit = Unit::where('unit_code', $request['primary_unit'])->first();
                    //     $secondary_unit = Unit::where('unit_code', $request['secondary_unit'])->first();

                    //     $product->update([
                    //         'product_name' => $request['product_name'],
                    //         'product_code' => $request['product_code'],
                    //         'category_id' => $request['category'],
                    //         'size' => $request['size'],
                    //         'opening_stock' => $request['stockTotal'],
                    //         'total_stock' => $request['stockTotal'],
                    //         'color' => $request['color'],
                    //         'original_vendor_price' => $request['original_vendor_price'],
                    //         'charging_rate' => $request['charging_rate'],
                    //         'final_vendor_price' => $request['final_vendor_price'],
                    //         'carrying_cost' => $request['carrying_cost'],
                    //         'transportation_cost' => $request['transportation_cost'],
                    //         'miscellaneous_percent' => $request['miscellaneous_percent'],
                    //         'other_cost' => $request['other_cost'],
                    //         'cost_of_product' => $request['product_cost'],
                    //         'custom_duty' => $request['custom_duty'],
                    //         'after_custom' => $request['after_custom'],
                    //         'tax' => $request['tax'],
                    //         'total_cost' => $request['total_cost'],
                    //         'margin_type' => $request['margin_type'],
                    //         'margin_value' => $request['margin_value'],
                    //         'profit_margin' => $profit_margin,
                    //         'product_price' => $request['product_price'],
                    //         // 'secondary_unit_selling_price' => $secondary_unit_selling_price,
                    //         'status' => $status,
                    //         'description' => $request['description'],
                    //         'primary_number' => $request['primary_number'],
                    //         'primary_unit' => $primary_unit->unit,
                    //         'primary_unit_id' => $primary_unit->id,
                    //         'primary_unit_code' => $request['primary_unit'],
                    //         'secondary_number' => $request['secondary_number'],
                    //         'secondary_unit' => $secondary_unit->unit,
                    //         // 'secondary_code' => $secondary_code,
                    //         'secondary_unit_id' => $secondary_unit->id,
                    //         'secondary_unit_code' => $request['secondary_unit'],
                    //         'supplier_id' => $request['supplier_id'],
                    //         'brand_id' => $request['brand'],
                    //         'series_id' => $request['series'],
                    //         'refundable' => $refundable,
                    //         'expire_date' => $request['expire_date'],
                    //     ]);

                    //     $godown_products = GodownProduct::where('product_id', $product->id)->latest()->get();
                    //     foreach ($godown_products as $godown_product) {
                    //         $godown_product->delete();
                    //     }

                    //     for (
                    //         $i = 0;
                    //         $i < count($request['stock']);
                    //         $i++
                    //     ) {
                    //         $godown_product = GodownProduct::create([
                    //             'godown_id' => $request->godown[$i],
                    //             'product_id' => $product->id,
                    //             'opening_stock' => $request->stock[$i],
                    //             'stock' => $request->stock[$i],
                    //             'alert_on' => $request->alert_on[$i],
                    //             'floor_no' => $request->floor_no[$i],
                    //             'rack_no' => $request->rack_no[$i],
                    //             'row_no' => $request->row_no[$i],
                    //         ]);
                    //         $godown_product->save();
                    //     }
                    // } elseif (isset($_POST['update'])) {
                    //     $this->validate($request, [
                    //         'product_image' => '',
                    //         'product_image.*' => 'mimes:png,jpg,jpeg|max:2048',
                    //     ]);

                    //     $imagename = '';
                    //     if ($request->hasfile('product_image')) {

                    //         $images = $request->file('product_image');
                    //         foreach ($images as $image) {
                    //             $imagename = $image->store('item_images', 'uploads');
                    //             $product_images = ProductImages::create([
                    //                 'product_id' => $product->id,
                    //                 'location' => $imagename,
                    //             ]);
                    //             $product_images->save();
                    //         }
                    //     }
                    // }

                    // return redirect()->route('product.index')->with('success', 'Product information is successfully updated.');
                }
            } else {
                return view('backend.permission.permission');
            }
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id, Request $request)
    {
        if ($request->user()->can('delete-product')) {
            $product = Product::findorFail($id);
            $billings = BillingExtra::where('particulars', $product->id)->get();

            if (count($billings) > 0) {
                return redirect()->back()->with('error', 'Products are used in Billings. Cant delete.');
            } else {
                $godown_products = GodownProduct::where('product_id', $product->id)->get();

                foreach ($godown_products as $godown_product) {
                    $godown_product->delete();
                }
                $product->delete();

                return redirect()->route('product.index')->with('success', 'Product information is successfully deleted.');
            }
        } else {
            return view('backend.permission.permission');
        }
    }

    public function deleteproductimage($id, Request $request)
    {
        if ($request->user()->can('delete-product')) {
            $product_image = ProductImages::findorfail($id);
            $images = ProductImages::where('product_id', $product_image->product_id)->get();
            if (count($images) < 2) {
                return redirect()->back()->with('error', 'Only one image cannot be deleted.');
            }
            $product_image->delete();
            return redirect()->back()->with('success', 'Image Removed Successfully');
        } else {
            return view('backend.permission.permission');
        }
    }

    public function restoreproduct($id, Request $request)
    {
        if ($request->user()->can('manage-trash')) {
            $deletedproduct = Product::onlyTrashed()->findorFail($id);
            $category = Category::onlyTrashed()->where('id', $deletedproduct->category_id)->first();
            if ($category) {
                return redirect()->back()->with('error', 'Category is not present or is soft deleted. Check Category Module.');
            }

            $deletedGodownProducts = GodownProduct::onlyTrashed()->where('product_id', $deletedproduct->id)->get();
            foreach ($deletedGodownProducts as $deletedGodownProduct) {
                $deletedGodownProduct->restore();
            }
            $deletedproduct->restore();
            return redirect()->route('product.index')->with('success', 'Product information is restored successfully.');
        } else {
            return view('backend.permission.permission');
        }
    }

    public function transferproducts($id)
    {
        $product = Product::findorFail($id);
        $godown_products = GodownProduct::where('product_id', $product->id)->latest()->get();
        $godowns = Godown::latest()->get();
        $godown_has_products = [];
        foreach ($godown_products as $godown_product) {
            array_push($godown_has_products, $godown_product->godown_id);
        }
        $related_godowns = Godown::whereIn('id', $godown_has_products)->latest()->get();
        return view('backend.product_service.transfershares', compact('godowns', 'product', 'godown_products', 'related_godowns'));
    }

    public function transferNow(Request $request)
    {
        $this->validate($request, [
            'product_id' => '',
            'from_godown' => 'required',
            'to_godown' => 'required',
            'stock' => '',
            'remarks' => 'required',
            'serial_numbers' => ''
        ]);

        $product = Product::findorFail($request['product_id']);

        if ($product->has_serial_number == 1) {
            $stock = count($request['serial_numbers']);
        } elseif ($product->has_serial_number == 0) {
            $stock = $request['stock'];
        }

        if ($request['from_godown'] == $request['to_godown']) {
            return redirect()->back()->with('error', 'Transferring godowns should be different.');
        }

        $from_godown_product = GodownProduct::where('product_id', $request['product_id'])->where('godown_id', $request['from_godown'])->first();

        if ($stock > $from_godown_product->stock) {
            return redirect()->back()->with('error', 'Stock cannot be more than available in stock.');
        }

        $transfering_godown_stock = $from_godown_product->stock - $stock;
        $from_godown_product->update(['stock' => $transfering_godown_stock]);

        $existing_destination_godown = GodownProduct::where('product_id', $request['product_id'])->where('godown_id', $request['to_godown'])->first();

        if ($existing_destination_godown) {
            $existing_godown_stock = $existing_destination_godown->stock + $stock;
            $existing_destination_godown->update(['stock' => $existing_godown_stock]);

            $new_godown_id = $existing_destination_godown->id;
        } else {
            if ($product->has_serial_number == 0) {
                $new_godown_product = GodownProduct::create([
                    'product_id' => $request['product_id'],
                    'godown_id' => $request['to_godown'],
                    'stock' => $stock,
                    'opening_stock' => $stock,
                    'has_serial_number' => 0
                ]);
            } elseif ($product->has_serial_number == 1) {
                $new_godown_product = GodownProduct::create([
                    'product_id' => $request['product_id'],
                    'godown_id' => $request['to_godown'],
                    'stock' => $stock,
                    'opening_stock' => $stock,
                    'has_serial_number' => 1
                ]);
            }

            $new_godown_id = $new_godown_product->id;
            $new_godown_product->save();
        }

        if ($product->has_serial_number == 1) {
            for ($i = 0; $i < $stock; $i++) {
                $serial_number_product = GodownSerialNumber::where('id', $request['serial_numbers'][$i])->first();
                $serial_number_product->update(['godown_product_id' => $new_godown_id]);
            }
        }

        $transfered_date = date('Y-m-d');
        $transfered_date_nep = datenep($transfered_date);

        $godown_transer = GodownTransfer::create([
            'transfer_from' => $request['from_godown'],
            'transfer_to' => $request['to_godown'],
            'transfered_by' => Auth::user()->id,
            'transfered_product' => $request['product_id'],
            'stock' => $stock,
            'remarks' => $request['remarks'],
            'transfered_nep_date' => $transfered_date_nep,
            'transfered_eng_date' => $transfered_date,
        ]);

        $godown_transer->save();

        return redirect()->route('product.index')->with('success', 'Stock transferred successfully.');
    }

    public function extra(Request $request)
    {

        if (isset($_POST['by_godown'])) {

            $godown_id = $request['godown'];
            if ($godown_id == "all") {
                return redirect()->route('product.index');
            }
            return redirect()->route('filterProducts', ['id' => $godown_id]);
        } elseif (isset($_POST['by_margin'])) {

            $option = $request['margin_amount'];
            $godowns = Godown::latest()->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            if ($option == 0) {
                $filter = "Low Profit Margin";
                $products = Product::orderBy('profit_margin', 'ASC')->paginate(10);
                $filtercolumn = 'profit_margin';
                $filterby = 'ASC';
            } else if ($option == 1) {
                $filter = "High Profit Margin";
                $products = Product::orderBy('profit_margin', 'DESC')->paginate(10);
                $filtercolumn = 'profit_margin';
                $filterby = 'DESC';
            } else if ($option == 2) {
                $filter = "Lowest Selling Price";
                $products = Product::orderBy('product_price', 'ASC')->paginate(10);
                $filtercolumn = 'product_price';
                $filterby = 'ASC';
            } else if ($option == 3) {
                $filter = "Highest Selling Price";
                $products = Product::orderBy('product_price', 'DESC')->paginate(10);
                $filtercolumn = 'product_price';
                $filterby = 'DESC';
            }

            $request->merge(['filtercolumn' => $filtercolumn, 'filterby' => $filterby]);

            $totalproductvalidation =  Product::all()->where('status',1)->sum(function($query) {
                return $query->product_price * $query->total_stock;
            });
            return view('backend.product_service.index', compact('totalproductvalidation','godowns', 'currentcomp', 'filtercolumn', 'filterby'));
            // return view('backend.product_service.filterbyMargin', compact('products', 'godowns', 'currentcomp', 'filter'));
        }
    }

    public function filterProducts(Request $request, $godown_id)
    {

        if ($request->ajax()) {
            // dd($request->search['value']);
            $data = GodownProduct::latest('godown_products.created_at')->with('product', 'product.category', 'product.brand', 'product.series')->where('godown_id', $godown_id)->get();

            return Datatables::of($data)
                ->addIndexColumn()


                ->editColumn('total_stock', function ($row) {
                    if ($row->secondary_unit == null) {
                        $total_stock = $row->total_stock . ' ' . $row->primary_unit;
                    } else {
                        $total_stock = $row->total_stock . ' ' . $row->primary_unit
                            . '<br>(' . $row->primary_number . ' ' . $row->primary_unit . ' contains ' . $row->secondary_number . ' ' . $row->secondary_unit . ')';
                    }

                    return $total_stock;
                })

                ->addColumn('brand_name', function ($row) {
                    $brand = $row->brand->brand_name ?? '';
                    if ($brand) {
                        return $brand;
                    } else {
                        return '';
                    }
                })
                ->addColumn('series_name', function ($row) {
                    $series_name = $row->series->series_name ?? '';
                    if ($series_name) {
                        return $series_name;
                    } else {
                        return '';
                    }
                })

                ->addColumn('margin_type', function ($row) {
                    $margin_type = ($row->product->margin_type == "fixed") ? 'Rs.' . $row->product->profit_margin : $row->product->margin_value . ' %';
                    return $margin_type;
                })

                ->addColumn('status', function ($row) {
                    $status = ($row->product->status == "1") ? 'Approved' : 'Not Approved';
                    return $status;
                })
                ->addColumn('stock_validation', function ($row) {
                    $stockvalidation = 'Rs.' . number_format($row->stock * $row->product->product_price, 2);
                    return $stockvalidation;
                })
                ->addColumn('action', function ($row) {
                    $editurl = route('product.edit', $row->product->id);
                    $showurl = route('product.show', $row->product->id);
                    $deleteurl = route('product.destroy', $row->product->id);
                    $printbarcode = route('barcodeprint', ['id' => $row->product->id, 'quantity' => 501]);
                    $printqrcode = route('qrcodeprint', ['id' => $row->product->id, 'quantity' => 501]);
                    $csrf_token = csrf_token();
                    $btn = '<div class="btn-bulk justify-content-center">';
                    if ($row->product->has_serial_number == 0) {
                        $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                        <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                        <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteproduct$row->product->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                        <a href='#' class='edit btn btn-secondary icon-btn btn-sm' title='Barcode' data-toggle='modal' data-target='#barcodeprint$row->product->id'><i class='fas fa-barcode'></i></a>
                        <a href='#' class='edit btn btn-primary icon-btn btn-sm' title='QRcode' data-toggle='modal' data-target='#qrcodeprint$row->product->id'><i class='fas fa-qrcode'></i></a>

                        <!-- Modal -->
                            <div class='modal fade text-left' id='deleteproduct$row->product->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body text-center'>
                                            <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                            <input type='hidden' name='_token' value='$csrf_token'>
                                            <label for='reason'>Are you sure you want to delete??</label><br>
                                            <input type='hidden' name='_method' value='DELETE' />
                                                <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='modal fade' id='barcodeprint$row->product->id' tabindex='-1' role='dialog' aria-labelledby='barcodeprint$row->idLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h5 class='modal-title' id='barcodeprint$row->product->idLabel'>$row->product->product_name Barcode</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>
                                        <input type='hidden' class='pro_id' name='pro_id' value='$row->product->id'>
                                        <div class='row'>
                                            <div class='col-md-10'>
                                                <input type='number' class='form-control tot_num' name='tot_num' placeholder='Enter Print Quantity Max(500)'>
                                            </div>
                                            <div class='col-md-2 pl-0'>
                                                <a href='javascript:void(0)' class='btn btn-primary print' data-dismiss='modal'>Print</a>
                                            </div>
                                        </div>
                                        <p class='text-danger msg off'>Quantity can't be more than 500 </p>
                                        <a style='display:none;' class=' btnprint link'>click</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='modal fade' id='qrcodeprint$row->product->id' tabindex='-1' role='dialog' aria-labelledby='qrcodeprint$row->product->idLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h5 class='modal-title' id='qrcodeprint$row->product->idLabel'>$row->product->product_name QRcode</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>
                                        <input type='hidden' class='qrpro_id' name='pro_id' value='$row->product->id'>
                                        <div class='row'>
                                            <div class='col-md-10'>
                                                <input type='number' class='form-control qrtot_num' name='tot_num' placeholder='Enter Print Quantity Max(500)'>
                                            </div>
                                            <div class='col-md-2 pl-0'>
                                                <a href='javascript:void(0)' class='btn btn-primary qrprint' data-dismiss='modal'>Print</a>
                                            </div>
                                        </div>
                                        <p class='text-danger qrmsg off'>Quantity can't be more than 500 </p>
                                        <a style='display:none;' class=' qrbtnprint link'>click</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ";
                    } else if ($row->product->has_serial_number == 1) {
                        $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                            <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                            <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteproduct$row->product->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                            <a href='$printbarcode' class='serial_number_print_barcode btn btn-secondary icon-btn btn-sm' title='Barcode'><i class='fas fa-barcode'></i></a>
                            <a href='$printqrcode' class='serial_number_print_qrcode btn btn-primary icon-btn btn-sm' title='QRcode'><i class='fas fa-qrcode'></i></a>

                            <!-- Modal -->
                                <div class='modal fade text-left' id='deleteproduct$row->product->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                            </div>
                                            <div class='modal-body text-center'>
                                                <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                <label for='reason'>Are you sure you want to delete??</label><br>
                                                <input type='hidden' name='_method' value='DELETE' />
                                                    <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";
                    }
                    $btn .= "</div>";
                    return $btn;
                })
                ->rawColumns(['total_stock', 'brand_name', 'series_name', 'margin_type', 'action', 'stock_validation'])
                ->make(true);
        }
        $productvalidationGodown = GodownProduct::join('products','godown_products.product_id','products.id')
        ->select(DB::raw('sum(godown_products.stock*products.product_price) AS total_sum'))
        ->where('godown_id',$godown_id)
        ->where('products.status',1)
        ->first();


        $godowns = Godown::latest()->get();
        $related_godown = Godown::where('id', $godown_id)->first();
        $services = Service::latest()->paginate(10);
        $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
        // return view('backend.product_service.index', compact('godowns', 'currentcomp', 'services','godown_id'));
        return view('backend.product_service.filterproducts', compact('godowns', 'related_godown', 'godown_id','productvalidationGodown'));
        // }
    }

    public function import()
    {
        // dd('hi');
        try {
            Excel::import(new ProductsImport, request()->file('csv_file'));
        } catch (\Throwable $th) {
            return redirect()->route('product.index')->with('error', 'There were some errors while importing.');
        }

        return redirect()->route('product.index')->with('success', 'Products uploaded successfully.');
    }

    public function export()
    {
        return Excel::download(new ExcelExport, 'example.xlsx');
    }

    public function importNonImporter()
    {
        // Excel::import(new ImportsProductNonImporter, request()->file('csv_file'));
        try {
            Excel::import(new ImportsProductNonImporter, request()->file('csv_file'));
        } catch (\Throwable $th) {
           $error_message =  $th->getMessage();
            return redirect()->route('product.index')->with('error', $error_message);
        }
        return redirect()->route('product.index')->with('success', 'Products uploaded successfully.');
    }

    public function exportNonImporter()
    {
        return Excel::download(new NonImporterExport, 'example.xlsx');
    }

    public function importUpdate()
    {
        try {
            Excel::import(new ProductUpdate, request()->file('update_csv_file'));
        } catch (\Throwable $th) {
            return redirect()->route('product.index')->with('error', 'There were some errors while importing.');
        }
        return redirect()->route('product.index')->with('success', 'Products updated successfully.');
    }

    public function exportUpdate()
    {
        return Excel::download(new UpdateExcelExport, 'updateProductExample.xlsx');
    }

    public function importNonUpdate()
    {
        try {
            Excel::import(new ProductNonUpdate, request()->file('update_csv_file'));
        } catch (\Throwable $th) {
            return redirect()->route('product.index')->with('error', 'There were some errors while importing.');
        }
        return redirect()->route('product.index')->with('success', 'Products updated successfully.');
    }

    public function exportNonUpdate()
    {
        return Excel::download(new UpdateNonImporter, 'updateNonImporterProductExample.xlsx');
    }

    public function getVat($id)
    {
        $vat = Tax::findorFail($id);
        return response()->json($vat);
    }

    public function fileManager()
    {
        return view('backend.filemanager');
    }

    public function productPostImage(Request $request)
    {
        if ($request->user()->can('manage-product-images')) {
            $imagearray = explode(',', $request->image);

            for (
                $i = 0;
                $i < count($imagearray);
                $i++
            ) {
                $exploded_array = explode('.', $imagearray[$i]);
                $count_exploded_array = count($exploded_array) - 2;
                $exploded_final_link = explode('/', $exploded_array[$count_exploded_array]);
                $count_final_link = count($exploded_final_link) - 1;

                $products = Product::all();
                foreach ($products as $product) {
                    if ($product->product_code == $exploded_final_link[$count_final_link]) {
                        $explode_for_database = explode('/', $imagearray[$i]);
                        $count_for_database = count($explode_for_database);
                        $name_for_database = $explode_for_database[$count_for_database - 4] . '/' . $explode_for_database[$count_for_database - 3] . '/' . $explode_for_database[$count_for_database - 2] . '/' . $explode_for_database[$count_for_database - 1];

                        $product_image = ProductImages::create([
                            'product_id' => $product->id,
                            'location' => $name_for_database,
                        ]);

                        $product_image->save();
                    }
                }
            }
            return redirect()->route('product.index')->with('success', 'Product Images updated successfully.');
        } else {
            return view('backend.permission.permission');
        }
    }

    public function viewProductBarCodes($id)
    {
        $code = "Bar";
        $product = Product::findorfail($id);
        return view('backend.product_service.productBarCodes', compact('product', 'code'));
    }

    public function viewProductQRCodes($id)
    {
        $code = "QR";
        $product = Product::findorfail($id);
        return view('backend.product_service.productBarCodes', compact('product', 'code'));
    }

    public function barcode($id, $quantity)
    {
        $product = Product::findorfail($id);
        $serial_number = null;
        if ($quantity == 501) {
            $serial_number = 1;
        }
        return view('backend.product_service.barcodeprintpreview', compact('product', 'quantity', 'serial_number'));
    }

    // public function secondaryBarcode( $id, $quantity ) {
    //     $product = Product::findorfail( $id );
    //     return view( 'backend.product_service.secondarybarcode', compact( 'product', 'quantity' ) );
    // }

    public function qrcode($id, $quantity)
    {
        $product = Product::findorfail($id);
        $serial_number = null;
        if ($quantity == 501) {
            $serial_number = 1;
        }
        return view('backend.product_service.qrcodeprintpreview', compact('product', 'quantity', 'serial_number'));
    }

    // public function secondaryqrcode( $id, $quantity ) {
    //     $product = Product::findorfail( $id );
    //     return view( 'backend.product_service.secondaryqr', compact( 'product', 'quantity' ) );
    // }

    public function stockreportcreate(Request $request)
    {


        if ($request->user()->can('manage-product-report')) {
            if($request->ajax()){
                // $defaultgodown = Godown::with('godownproduct', 'godownproduct.product');

                $defaultgodown = GodownProduct::with('godown', 'product')->where('godown_id',$request->gd_id);
                // $gdproducts = $defaultgodown->godownproduct;

                return Datatables::of($defaultgodown)
                ->addIndexColumn()
                ->editColumn('total_added',function(){

                })

                ->make(true);
            }
            $products = Product::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->latest()->get();
            $defaultgodown = Godown::with('godownproduct', 'godownproduct.product')->where('is_default', 1)->first();
            return view('backend.product_service.stockreportcreate', compact('godowns', 'products', 'defaultgodown'));
        } else {
            return view('backend.permission.permission');
        }
    }
    public function godownstockreportcreate(Request $request, $id)
    {
        if ($request->user()->can('manage-product-report')) {
            $products = Product::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->latest()->get();
            $defaultgodown = Godown::with('godownproduct', 'godownproduct.product')->where('id', $id)->first();
            // dd($defaultgodown);
            return view('backend.product_service.stockreportcreate', compact('godowns', 'products', 'defaultgodown'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function stockgenerate(Request $request)
    {
        if ($request->user()->can('manage-product-report')) {
            $products = Product::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->latest()->get();
            $startdate = $request['starting_date'];
            $enddate = $request['ending_date'];
            // dd( $request['product_id'] );
            $product = Product::where('id', $request['product_id'])->first();
            $godown = Godown::where('id', $request['godown_id'])->first();
            $product_name = $product->product_name;
            $godown_name = $godown->godown_name;
            $created_at = date_format($product->created_at, 'Y-m-d');
            $godownproduct = GodownProduct::where('godown_id', $request['godown_id'])->where('product_id', $request['product_id'])->first();
            $openingstock = $product->opening_stock;
            if ($created_at <= $startdate) {
                $openingstock = $product->opening_stock;

                $godownopeningstock = $godownproduct->opening_stock;
            } else {
                // $ostock = $product->opening_stock;
                $oproductsadded = ProductStock::where('product_id', $request['product_id'])->where('added_date', '<=', $enddate)
                    ->whereHas('billings', function ($q) {
                        $q->where('status', 1);
                        $q->where('is_cancelled', 0);
                    })
                    ->get();
                $godownoproductsadded = ProductStock::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('added_date', '<=', $enddate)
                    ->whereHas('billings', function ($q) {
                        $q->where('status', 1);
                        $q->where('is_cancelled', 0);
                    })
                    ->get();
                // dd( $productsadded );
                $oapros = [];
                foreach ($oproductsadded as $oaddedproduct) {
                    array_push($oapros, $oaddedproduct->added_stock);
                }
                $ototadded = array_sum($oapros);

                $godownoapros = [];
                foreach ($godownoproductsadded as $godownoaddedproduct) {
                    array_push($godownoapros, $godownoaddedproduct->added_stock);
                }
                $godownototadded = array_sum($godownoapros);

                $oproductsold = SalesRecord::where('product_id', $request['product_id'])->where('date_sold', '<=', $enddate)->get();
                $godownoproductsold = SalesRecord::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('date_sold', '<=', $enddate)->get();
                // dd( $productsold );
                $ospros = [];
                foreach ($oproductsold as $osoldproduct) {
                    array_push($ospros, $osoldproduct->stock_sold);
                }
                $ototsold = array_sum($ospros);

                $godownospros = [];
                foreach ($godownoproductsold as $godownosoldproduct) {
                    array_push($godownospros, $godownosoldproduct->stock_sold);
                }
                $godownototsold = array_sum($godownospros);

                $oproductsreturn = SalesReturnRecord::where('product_id', $request['product_id'])->where('date_return', '<=', $enddate)->get();
                $godownoproductsreturn = SalesReturnRecord::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('date_return', '<=', $enddate)->get();
                // dd( $productsold );
                $osrpros = [];
                foreach ($oproductsreturn as $oreturnproduct) {
                    array_push($osrpros, $oreturnproduct->stock_return);
                }
                $ototreturn = array_sum($osrpros);

                $godownosrpros = [];
                foreach ($godownoproductsreturn as $godownoreturnproduct) {
                    array_push($godownosrpros, $godownoreturnproduct->stock_return);
                }
                $godownototreturn = array_sum($godownosrpros);

                $odamagedproducts = DamagedProducts::where('product_id', $request['product_id'])->where('created_at', '<=', $enddate)->get();
                $godownodamagedproducts = DamagedProducts::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('created_at', '<=', $enddate)->get();

                $odampros = [];
                foreach ($odamagedproducts as $odamagedproduct) {
                    array_push($odampros, $odamagedproduct->stock);
                }
                $ototdamaged = array_sum($odampros);

                $godownodampros = [];
                foreach ($godownodamagedproducts as $godownodamagedproduct) {
                    array_push($godownodampros, $godownodamagedproduct->stock);
                }
                $godownototdamaged = array_sum($godownodampros);

                $openingstock = $product->opening_stock + $ototadded - $ototsold + $ototreturn - $ototdamaged;
                $godownopeningstock = $godownproduct->opening_stock + $godownototadded - $godownototsold + $godownototreturn - $godownototdamaged;
            }

            //Closing Stock

            $cproductsadded = ProductStock::where('product_id', $request['product_id'])->where('added_date', '<=', $enddate)
                ->whereHas('billings', function ($q) {
                    $q->where('status', 1);
                    $q->where('is_cancelled', 0);
                })
                ->get();
            $godowncproductsadded = ProductStock::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('added_date', '<=', $enddate)
                ->whereHas('billings', function ($q) {
                    $q->where('status', 1);
                    $q->where('is_cancelled', 0);
                })
                ->get();
            // dd( $productsadded );
            $capros = [];
            foreach ($cproductsadded as $caddedproduct) {
                array_push($capros, $caddedproduct->added_stock);
            }
            $ctotadded = array_sum($capros);

            $godowncapros = [];
            foreach ($godowncproductsadded as $godowncaddedproduct) {
                array_push($godowncapros, $godowncaddedproduct->added_stock);
            }
            $godownctotadded = array_sum($godowncapros);

            $cproductsold = SalesRecord::where('product_id', $request['product_id'])->where('date_sold', '<=', $enddate)->get();
            $godowncproductsold = SalesRecord::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('date_sold', '<=', $enddate)->get();
            // dd( $productsold );
            $cspros = [];
            foreach ($cproductsold as $csoldproduct) {
                array_push($cspros, $csoldproduct->stock_sold);
            }
            $ctotsold = array_sum($cspros);

            $godowncspros = [];
            foreach ($godowncproductsold as $godowncsoldproduct) {
                array_push($godowncspros, $godowncsoldproduct->stock_sold);
            }
            $godownctotsold = array_sum($godowncspros);

            $cproductsreturn = SalesReturnRecord::where('product_id', $request['product_id'])->where('date_return', '<=', $enddate)->get();
            $godowncproductsreturn = SalesReturnRecord::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('date_return', '<=', $enddate)->get();
            // dd( $productsold );
            $csrpros = [];
            foreach ($cproductsreturn as $creturnproduct) {
                array_push($csrpros, $creturnproduct->stock_return);
            }
            $ctotreturn = array_sum($csrpros);

            $godowncsrpros = [];
            foreach ($godowncproductsreturn as $godowncreturnproduct) {
                array_push($godowncsrpros, $godowncreturnproduct->stock_return);
            }
            $godownctotreturn = array_sum($godowncsrpros);

            $cdamagedproducts = DamagedProducts::where('product_id', $request['product_id'])->where('created_at', '<=', $enddate)->get();
            $godowncdamagedproducts = DamagedProducts::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('created_at', '<=', $enddate)->get();

            $cdampros = [];
            foreach ($cdamagedproducts as $cdamagedproduct) {
                array_push($cdampros, $cdamagedproduct->stock);
            }
            $ctotdamaged = array_sum($cdampros);

            $godowncdampros = [];
            foreach ($godowncdamagedproducts as $godowncdamagedproduct) {
                array_push($godowncdampros, $godowncdamagedproduct->stock);
            }
            $godownctotdamaged = array_sum($godowncdampros);
            // dd( $ctotsold );

            // Stock Out Calculation
            $allstockout = StockOut::where(function ($query) use ($request, $startdate, $enddate) {
                $query->whereHas('stockoutproducts', function ($q) use ($request) {
                    $q->where('product_id', $request['product_id']);
                });
                $query->where('stock_out_date', '>=', $startdate);
                $query->where('stock_out_date', '<=', $enddate);
            })->get();
            $allstockouts = [];
            foreach ($allstockout as $stockout) {
                foreach ($stockout->stockoutproducts as $stocks) {
                    array_push($allstockouts, $stocks->total_stock_out);
                }
            }
            $totalstockout = array_sum($allstockouts);

            // Stock Transfer to Outlet Calculation

            $alloutletstock = WarehousePosTransfer::where(function ($query) use ($request, $startdate, $enddate) {
                $query->whereHas('warehousetransferproduct', function ($q) use ($request) {
                    $q->where('product_id', $request['product_id']);
                });
                $query->where('transfer_eng_date', '>=', $startdate);
                $query->where('transfer_eng_date', '<=', $enddate);
            })->get();

            $alloutletstocks = [];
            foreach ($alloutletstock as $alloutlet) {
                foreach ($alloutlet->warehousetransferproduct as $stocks) {
                    array_push($alloutletstocks, $stocks->stock);
                }
            }
            $totaloutletstock = array_sum($alloutletstocks);

            $closingstock = $product->opening_stock + $ctotadded - $ctotsold + $ctotreturn - $ctotdamaged - $totalstockout - $totaloutletstock;

            $addedproducts = ProductStock::where('product_id', $request['product_id'])->where('added_date', '>=', $startdate)->where('added_date', '<=', $enddate)
                ->whereHas('billings', function ($q) {
                    $q->where('status', 1);
                    $q->where('is_cancelled', 0);
                })
                ->get();
            $alladded = [];
            foreach ($addedproducts as $productadded) {
                array_push($alladded, $productadded->added_stock);
            }
            $totaladded = array_sum($alladded);

            $godownaddedproducts = ProductStock::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('added_date', '>=', $startdate)->where('added_date', '<=', $enddate)
                ->whereHas('billings', function ($q) {
                    $q->where('status', 1);
                    $q->where('is_cancelled', 0);
                })
                ->get();
            $godownalladded = [];
            foreach ($godownaddedproducts as $godownproductadded) {
                array_push($godownalladded, $godownproductadded->added_stock);
            }
            $godowntotaladded = array_sum($godownalladded);

            $allsold = SalesRecord::where('product_id', $request['product_id'])->where('date_sold', '>=', $startdate)->where('date_sold', '<=', $enddate)->get();
            // dd( $productsold );
            $allpros = [];
            foreach ($allsold as $soldpro) {
                array_push($allpros, $soldpro->stock_sold);
            }
            $totalsold = array_sum($allpros);

            $godownallsold = SalesRecord::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('date_sold', '>=', $startdate)->where('date_sold', '<=', $enddate)->get();
            // dd( $productsold );
            $godownallpros = [];
            foreach ($godownallsold as $godownsoldpro) {
                array_push($godownallpros, $godownsoldpro->stock_sold);
            }
            $godowntotalsold = array_sum($godownallpros);

            $allreturn = SalesReturnRecord::where('product_id', $request['product_id'])->where('date_return', '>=', $startdate)->where('date_return', '<=', $enddate)->get();
            // dd( $productreturn );
            $allsalesreturn = [];
            foreach ($allreturn as $returnpro) {
                array_push($allsalesreturn, $returnpro->stock_return);
            }
            $totalreturn = array_sum($allsalesreturn);

            $godownallreturn = SalesReturnRecord::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('date_return', '>=', $startdate)->where('date_return', '<=', $enddate)->get();
            // dd( $productreturn );
            $godownallsalesreturn = [];
            foreach ($godownallreturn as $godownreturnpro) {
                array_push($godownallsalesreturn, $godownreturnpro->stock_return);
            }
            $godowntotalreturn = array_sum($godownallsalesreturn);

            $alldamaged = DamagedProducts::where('product_id', $request['product_id'])->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate)->get();
            // dd( $alldamaged );
            $alldamed = [];
            foreach ($alldamaged as $damaged) {
                array_push($alldamed, $damaged->stock);
            }
            $totaldamaged = array_sum($alldamed);

            $godownalldamaged = DamagedProducts::where('product_id', $request['product_id'])->where('godown_id', $request['godown_id'])->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate)->get();
            // dd( $godownalldamaged );
            $godownalldamed = [];
            foreach ($godownalldamaged as $godowndamaged) {
                array_push($godownalldamed, $godowndamaged->stock);
            }
            $godowntotaldamaged = array_sum($godownalldamed);



            // Stock out as per godown
            $godownstockout = StockOut::where(function ($query) use ($request, $startdate, $enddate) {
                $query->where('godown_id', '>=', $request['godown_id']);
                $query->whereHas('stockoutproducts', function ($q) use ($request) {
                    $q->where('product_id', $request['product_id']);
                });
                $query->where('stock_out_date', '>=', $startdate);
                $query->where('stock_out_date', '<=', $enddate);
            })->get();
            $godownstockouts = [];
            foreach ($godownstockout as $stockout) {
                foreach ($stockout->stockoutproducts as $stocks) {
                    array_push($godownstockouts, $stocks->total_stock_out);
                }
            }
            $godownstockout = array_sum($godownstockouts);

            // Stock Transfer to Outlet Calculation as per Godown

            $godownoutletstock = WarehousePosTransfer::where(function ($query) use ($request, $startdate, $enddate) {
                $query->where('godown_id', '>=', $request['godown_id']);
                $query->whereHas('warehousetransferproduct', function ($q) use ($request) {
                    $q->where('product_id', $request['product_id']);
                });
                $query->where('transfer_eng_date', '>=', $startdate);
                $query->where('transfer_eng_date', '<=', $enddate);
            })->get();
            $godownoutletstocks = [];
            foreach ($godownoutletstock as $godownoutlet) {
                foreach ($godownoutlet->warehousetransferproduct as $stocks) {
                    array_push($godownoutletstocks, $stocks->stock);
                }
            }

            $totalgodownoutletstock = array_sum($godownoutletstocks);


            $godownclosingstock = $godownproduct->opening_stock + $godownctotadded - $godownctotsold + $godownctotreturn - $godownctotdamaged - $godownstockout - $totalgodownoutletstock;

            return view('backend.product_service.generatestockreport', compact(
                'products',
                'product_name',
                'openingstock',
                'totaladded',
                'totalsold',
                'totalreturn',
                'totaldamaged',
                'totalstockout',
                'totaloutletstock',
                'closingstock',
                'godowns',
                'godown_name',
                'godownopeningstock',
                'godowntotaladded',
                'godowntotalsold',
                'godowntotalreturn',
                'godowntotaldamaged',
                'godownstockout',
                'totalgodownoutletstock',
                'godownclosingstock',

            ));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function low_stock_products(Request $request)
    {
        if ($request->user()->can('manage-product-report')) {
            $products = GodownProduct::with('product', 'godown')->whereRaw('stock <= alert_on')->where('stock', '>', 0)->paginate(20);
            return view('backend.product_service.lowstockproduct_report', compact('products'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function outof_stock_products(Request $request)
    {
        if ($request->user()->can('manage-product-report')) {
            $products = GodownProduct::with('product', 'godown')->where('stock', '<=', 0)->paginate(20);
            return view('backend.product_service.outofstockproduct_report', compact('products'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function expiring_stock_products(Request $request)
    {
        if ($request->user()->can('manage-product-report')) {
            $godownproducts = GodownProduct::with('expiryproducts')->get();
            $products = [];
            foreach ($godownproducts as $godownproduct) {
                $product = Product::with('godownproduct.godown')->where('id', $godownproduct->product_id)->first();
                if (!$product->expiry_date == null) {
                    $thisday = strtotime(date('Y-m-d'));
                    $expirydate = strtotime($product->expiry_date);
                    $secs = $expirydate - $thisday;
                    $days = $secs / 86400;
                    if ($days <= 10 && $days > 0) {
                        array_merge($products, $product);
                    }
                }
            }
            return view('backend.product_service.expiringstockproduct_report', compact('products'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function expired_stock_products(Request $request)
    {
        if ($request->user()->can('manage-product-report')) {
            $godownproducts = GodownProduct::with('expiryproducts')->get();
            $products = [];
            foreach ($godownproducts as $godownproduct) {
                $product = Product::with('godownproduct.godown')->where('id', $godownproduct->product_id)->first();
                if (!$product->expiry_date == null) {
                    $thisday = strtotime(date('Y-m-d'));
                    $expirydate = strtotime($product->expiry_date);
                    $secs = $expirydate - $thisday;
                    $days = $secs / 86400;
                    if ($days <= 0) {
                        array_merge($products, $product);
                    }
                }
            }
            return view('backend.product_service.expiredstockproduct_report', compact('products'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function productsales(Request $request, $id)
    {
        if ($request->user()->can('manage-product-report')) {
            $product = Product::findorfail($id);
            $productsales = BillingExtra::leftJoin('billings', 'billing_extras.billing_id', '=', 'billings.id')
                ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                ->leftJoin('clients', 'billings.client_id', '=', 'clients.id')
                ->select('clients.id as client_id', 'billing_id', 'reference_no', 'godown_name', 'eng_date', 'nep_date', 'quantity', 'status', 'name')
                ->where('particulars', $id)->where('billings.billing_type_id', 1)->paginate(15);
            // dd($productsales);
            return view('backend.product_service.productsales', compact('product', 'productsales'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function productpurchases(Request $request, $id)
    {
        if ($request->user()->can('manage-product-report')) {
            $product = Product::findorfail($id);
            $productpurchases = BillingExtra::leftJoin('billings', 'billing_extras.billing_id', '=', 'billings.id')
                ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                ->leftJoin('vendors', 'billings.vendor_id', '=', 'vendors.id')
                ->select('vendors.id as vendor_id', 'billing_id', 'reference_no', 'godown_name', 'eng_date', 'nep_date', 'quantity', 'status', 'company_name')
                ->where('particulars', $id)->where('billings.billing_type_id', 2)->paginate(15);
            // dd($productpurchases);
            return view('backend.product_service.productpurchases', compact('product', 'productpurchases'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function productsales_returns($id, Request $request)
    {
        if ($request->user()->can('manage-product-report')) {
            $product = Product::findorfail($id);
            $productsales_returns = BillingExtra::leftJoin('billings', 'billing_extras.billing_id', '=', 'billings.id')
                ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                ->leftJoin('clients', 'billings.client_id', '=', 'clients.id')
                ->select('clients.id as client_id', 'billing_id', 'reference_no', 'godown_name', 'eng_date', 'nep_date', 'quantity', 'status', 'name')
                ->where('particulars', $id)->where('billings.billing_type_id', 6)->paginate(15);
            // dd($productsales_returns);
            return view('backend.product_service.productsales_returns', compact('product', 'productsales_returns'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function productpurchase_returns(Request $request, $id)
    {
        if ($request->user()->can('manage-product-report')) {
            $product = Product::findorfail($id);
            $productpurchase_returns = BillingExtra::leftJoin('billings', 'billing_extras.billing_id', '=', 'billings.id')
                ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                ->leftJoin('vendors', 'billings.vendor_id', '=', 'vendors.id')
                ->select('vendors.id as vendor_id', 'billing_id', 'reference_no', 'godown_name', 'eng_date', 'nep_date', 'quantity', 'status', 'company_name')
                ->where('particulars', $id)->where('billings.billing_type_id', 5)->paginate(15);
            // dd($productpurchase_returns);
            return view('backend.product_service.productpurchase_returns', compact('product', 'productpurchase_returns'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function productquotations(Request $request, $id)
    {
        if ($request->user()->can('manage-product-report')) {
            $product = Product::findorfail($id);
            $productquotations = BillingExtra::leftJoin('billings', 'billing_extras.billing_id', '=', 'billings.id')
                ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                ->leftJoin('clients', 'billings.client_id', '=', 'clients.id')
                ->select('clients.id as client_id', 'billing_id', 'reference_no', 'godown_name', 'eng_date', 'nep_date', 'quantity', 'status', 'name')
                ->where('particulars', $id)->where('billings.billing_type_id', 7)->paginate(15);
            // dd($productquotations);
            return view('backend.product_service.productquotation', compact('product', 'productquotations'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function productFilterSession(Request $request){

        Session::put($request->checkeditem,$request->checkedvalue);

    }


}
