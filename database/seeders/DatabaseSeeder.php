<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildAccount;
use App\Models\ExampleExcel;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\ProductNonImporter;
use App\Models\Series;
use App\Models\Setting;
use App\Models\SubAccount;
use App\Models\Unit;
use App\Models\UpdateExcelExport;
use App\Models\UpdateNonImporter;
use App\Models\Vendor;
use App\Models\Vendorconcern;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CompanyTableSeeder::class);
        $this->call(BranchTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
        $this->call(UserPermissionTableSeeder::class);
        $this->call(RolesPermissionsTableSeeder::class);
        $this->call(ProvinceTableSeeder::class);
        $this->call(DistrictTableSeeder::class);
        $this->call(PaymentmodeTableSeeder::class);
        $this->call(BillingTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(QuotationTableSeeder::class);
        $this->call(SuperSettingSeeder::class);
        $this->call(DealerTypesTableSeeder::class);
        $this->call(HotelOrderTypeTableSeeder::class);
        // $this->call(ChildAccountsTableSeeder::class);

        // Main Account Seeder
        Account::insert([
            [
                "title"=>"Assets",
                "slug"=>Str::slug("Assets"),
            ],
            [
                "title"=>"Liabilities",
                "slug"=>Str::slug("Liabilities"),
            ],
            [
                "title"=>"Revenue / Income",
                "slug"=>Str::slug("Revenue / Income"),
            ],
            [
                "title"=>"Equity",
                "slug"=>Str::slug("Equity"),
            ],
            [
                "title"=>"Expenses",
                "slug"=>Str::slug("Expenses"),
            ]
        ]);

         // Sub Account Seeder
         SubAccount::insert([
            [
                "account_id" => "1",
                "title"=>"Current Assets",
                "slug"=>Str::slug("Current Assets"),
            ],
            [
                "account_id" => "1",
                "title"=>"Fixed Assets",
                "slug"=>Str::slug("Fixed Assets"),
            ],
            [
                "account_id" => "1",
                "title"=>"Intangible Assets",
                "slug"=>Str::slug("Intangible Assets"),
            ],
            [
                "account_id" => "1",
                "title"=>"Other Assets",
                "slug"=>Str::slug("Other Assets"),
            ],
            [
                "account_id" => "2",
                "title"=>"Current Liabilites",
                "slug"=>Str::slug("Current Liabilites"),
            ],
            [
                "account_id" => "2",
                "title"=>"Non-Current Liabilities",
                "slug"=>Str::slug("Non-Current Liabilities"),
            ],
            [
                "account_id" => "2",
                "title"=>"Other Liabilites",
                "slug"=>Str::slug("Other Liabilites"),
            ],
            [
                "account_id" => "3",
                "title"=>"Operating Income",
                "slug"=>Str::slug("Operating Income"),
            ],
            [
                "account_id" => "3",
                "title"=>"Non-Operating Income",
                "slug"=>Str::slug("Non-Operating Income"),
            ],
            [
                "account_id" => "3",
                "title"=>"Other Income",
                "slug"=>Str::slug("Other Income"),
            ],
            [
                "account_id" => "5",
                "title"=>"Operating Expenses",
                "slug"=>Str::slug("Operating Expenses"),
            ],
            [
                "account_id" => "5",
                "title"=>"Non-Operating Expenses",
                "slug"=>Str::slug("Non-Operating Expenses"),
            ],
            [
                "account_id" => "5",
                "title"=>"Cost of Sales",
                "slug"=>Str::slug("Cost of Sales"),
            ],
            [
                "account_id" => "4",
                "title"=>"Shareholders Equity",
                "slug"=>Str::slug("Shareholders Equity"),
            ],
            [
                "account_id" => "4",
                "title"=>"Dividends",
                "slug"=>Str::slug("Dividends"),
            ],
            [
                "account_id" => "1",
                "title"=>"Inventory",
                "slug"=>Str::slug("Inventory"),
            ],
            [
                "account_id" => "1",
                "title"=>"Sundry Debtors",
                "slug"=>Str::slug("Sundry Debtors"),
            ],
            [
                "account_id" => "2",
                "title"=>"Sundry Creditors",
                "slug"=>Str::slug("Sundry Creditors"),
            ],

        ]);

        // Child Account Seeder
        ChildAccount::insert([
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "14",
                "title"=>"Capital",
                "slug"=>Str::slug("Capital"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "15",
                "title"=>"Dividend",
                "slug"=>Str::slug("Dividend"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "1",
                "title"=>"Cash In Hand",
                "slug"=>Str::slug("Cash In Hand"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "1",
                "title"=>"Cash In Bank",
                "slug"=>Str::slug("Cash In Bank"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "5",
                "title"=>"Accounts Payable",
                "slug"=>Str::slug("Accounts Payable"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "5",
                "title"=>"Bank Overdraft",
                "slug"=>Str::slug("Bank Overdraft"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "8",
                "title"=>"Product Sales",
                "slug"=>Str::slug("Product Sales"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "8",
                "title"=>"Service Sales",
                "slug"=>Str::slug("Service Sales"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "11",
                "title"=>"Salary Expenses",
                "slug"=>Str::slug("Salary Expenses"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "11",
                "title"=>"Office Rent",
                "slug"=>Str::slug("Office Rent"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "8",
                "title"=>"Sales Margin",
                "slug"=>Str::slug("Sales Margin"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "5",
                "title"=>"Tax Payable",
                "slug"=>Str::slug("Tax Payable"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "9",
                "title"=>"Discount Received",
                "slug"=>Str::slug("Discount Received"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "9",
                "title"=>"Incoming Tax",
                "slug"=>Str::slug("Incoming Tax"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "11",
                "title"=>"Service Charge",
                "slug"=>Str::slug("Service Charge"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "12",
                "title"=>"Discount",
                "slug"=>Str::slug("Discount"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "12",
                "title"=>"Outgoing Tax",
                "slug"=>Str::slug("Outgoing Tax"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "8",
                "title"=>"Service Income",
                "slug"=>Str::slug("Service Income"),
            ],
            [
                "company_id" => 1,
                "branch_id" => 1,
                "sub_account_id" => "11",
                "title"=>"Shipping Charge",
                "slug"=>Str::slug("Shipping Charge"),
            ],
        ]);

        Setting::insert([
            [
                "company_name"=>"LekhaBidhi",
                "company_email"=>"lekhabidhi@gmail.com ",
                "company_phone"=>"01-5904030",
                "province_id"=>"3",
                "district_id"=>"23",
                "address"=>"Gushingal, Kupondole",
                "logo"=>"favicon.png",
                "pan_vat"=>"1542-551-575",
                "registration_no"=>"5346846546",
            ]
        ]);

        FiscalYear::insert([
            [
                "fiscal_year" => "2078/2079"
            ],
        ]);

        Unit::insert([
            [
                'unit' => 'Bags',
                'short_form' => 'bgs',
                'unit_code' => '1'
            ],
            [
                'unit' => 'Bottles',
                'short_form' => 'btls',
                'unit_code' => '2'
            ],
            [
                'unit' => 'Box',
                'short_form' => 'box',
                'unit_code' => '3'
            ],
            [
                'unit' => 'Bundles',
                'short_form' => 'bdls',
                'unit_code' => '4'
            ],
            [
                'unit' => 'Cans',
                'short_form' => 'cans',
                'unit_code' => '5'
            ],
            [
                'unit' => 'Cartoons',
                'short_form' => 'crtns',
                'unit_code' => '6'
            ],
            [
                'unit' => 'Dozens',
                'short_form' => 'dzs',
                'unit_code' => '7'
            ],
            [
                'unit' => 'Grammes',
                'short_form' => 'gms',
                'unit_code' => '8'
            ],
            [
                'unit' => 'Kilograms',
                'short_form' => 'kgs',
                'unit_code' => '9'
            ],
            [
                'unit' => 'Litre',
                'short_form' => 'ltr',
                'unit_code' => '10'
            ],
            [
                'unit' => 'Metres',
                'short_form' => 'm',
                'unit_code' => '11'
            ],
            [
                'unit' => 'Mililiter',
                'short_form' => 'ml',
                'unit_code' => '12'
            ],
            [
                'unit' => 'Numbers',
                'short_form' => 'num',
                'unit_code' => '13'
            ],
            [
                'unit' => 'Packs',
                'short_form' => 'pks',
                'unit_code' => '14'
            ],
            [
                'unit' => 'Pairs',
                'short_form' => 'pr',
                'unit_code' => '15'
            ],
            [
                'unit' => 'Pieces',
                'short_form' => 'pcs',
                'unit_code' => '16'
            ],
            [
                'unit' => 'Quintal',
                'short_form' => 'Q',
                'unit_code' => '17'
            ],
            [
                'unit' => 'Rolls',
                'short_form' => 'rls',
                'unit_code' => '18'
            ],
            [
                'unit' => 'Bags',
                'short_form' => 'bgs',
                'unit_code' => '19'
            ],
            [
                'unit' => 'Square Feet',
                'short_form' => 'sq. ft.',
                'unit_code' => '20'
            ],
            [
                'unit' => 'Square meter',
                'short_form' => 'sq. m.',
                'unit_code' => '21'
            ],
            [
                'unit' => 'Tablets',
                'short_form' => 'tbl',
                'unit_code' => '22'
            ],
        ]);

        Category::insert([
            [
                'company_id'=>1,
                'branch_id'=>1,
                'category_name' => 'Test Category',
                'category_code' => '1',
                'category_image' => 'noimage.jpg',
                'in_order' => 1,
            ]
        ]);

        Brand::insert([
            'brand_name' => 'Test brand',
            'brand_code' => '1',
            'brand_logo' => 'noimage.jpg',
        ]);

        Series::insert([
            'brand_id' => '1',
            'series_name' => 'Test Series',
            'series_code' => '1',
        ]);

        Vendor::insert([
            [
                'company_id'=>1,
                'branch_id'=>1,
                'company_name' => 'Test Company',
                'company_email' => 'test@gmail.com ',
                'company_phone' => '9854652121',
                'company_address' => 'local address',
                'province_id' => '1',
                'district_id' => '23',
                'pan_vat' => '',
                'supplier_code' => '1',
            ]
        ]);

        Vendorconcern::insert([
            'vendor_id' => '1',
            'concerned_name' => 'Tester company',
            'concerned_phone' => '9854652312',
            'concerned_email' => 'tester@gmail.com',
            'designation' => 'Manager',
            'default' => 1
        ]);

        Godown::insert([
            [
                'company_id'=>1,
                'branch_id'=>1,
                'godown_name' => 'Balaju Godown',
                'province_id' => '3',
                'district_id' => '23',
                'local_address' => 'Gushingal',
                'godown_code' => '1',
                'is_default' => 1,
            ],
            [
                'company_id'=>1,
                'branch_id'=>1,
                'godown_name' => 'Banasthali Godown',
                'province_id' => '3',
                'district_id' => '23',
                'local_address' => 'Banasthali',
                'godown_code' => '2',
                'is_default' => 0,
            ]
        ]);

        ExampleExcel::insert([
            [
                'product_name'=> 'product_name',
                'product_code'=> 'unique_product_code',
                'category'=> 'category_code',
                'size'=> 'size',
                'color'=> 'color',
                'serial_numbers'=> 'serial_numbers',
                'total_stock'=> 'total_stock',
                'original_vendor_price'=> 'original_vendor_price',
                'changing_rate'=> 'changing_rate',
                'final_vendor_price'=> 'final_vendor_price',
                'carrying_cost'=> 'carrying_cost',
                'transportation_cost'=> 'transportation_cost',
                'miscellaneous_percent'=> 'miscellaneous_percent',
                'other_cost'=> 'other_cost',
                'cost_of_product'=> 'cost_of_product',
                'custom_duty'=> 'custom_duty',
                'after_custom'=> 'after_custom',
                'tax'=> 'tax',
                'total_cost'=> 'total_cost',
                'profit_margin'=> 'profit_margin',
                'product_price'=> 'product_price',
                'description'=> 'description',
                'status'=> 'status',
                'primary_number'=> 'primary_number',
                'primary_unit'=> 'primary_unit_code',
                'secondary_number'=> 'secondary_number',
                'secondary_unit'=> 'secondary_unit_code',
                'supplier'=> 'supplier_code',
                'brand'=> 'brand_code',
                'series'=> 'series_code',
                'refundable'=> 'refundable',
                'godowns'=> 'godowns_code',
                'stock_by_godown'=> 'stock_by_godown',
                'tips'=> 'Note*',
            ],
            [
                'product_name'=> 'Test Product',
                'product_code'=> '0025',
                'category'=> '1',
                'size'=> 'Medium',
                'color'=> 'Red, Green',
                'serial_numbers'=> '',
                'total_stock'=> '150',
                'original_vendor_price'=> '140',
                'changing_rate'=> '1.5',
                'final_vendor_price'=> '210',
                'carrying_cost'=> '15',
                'transportation_cost'=> '30',
                'miscellaneous_percent' => '10',
                'other_cost'=> '10',
                'cost_of_product'=> '265',
                'custom_duty'=> '10',
                'after_custom'=> '365',
                'tax'=> '13',
                'total_cost'=> '456',
                'profit_margin'=> '10',
                'product_price'=> '498.2',
                'description'=> 'This is the test product',
                'status'=> 'Active',
                'primary_number'=> '1',
                'primary_unit'=> '2',
                'secondary_number'=> '12',
                'secondary_unit'=> '3',
                'supplier'=> '1',
                'brand'=> '1',
                'series'=> '1',
                'refundable'=> 'Yes',
                'godowns'=> '1,2',
                'stock_by_godown'=> '250,150',
                'tips'=> '(Change the type of godowns_code and stock_by_godown from number to text)',
            ],
        ]);

        ProductNonImporter::insert([
            [
                'product_name'=> 'product_name',
                'product_code'=> 'unique_product_code',
                'category'=> 'category_code',
                'size'=> 'size',
                'color'=> 'color',
                'serial_numbers'=> 'serial_numbers',
                'total_stock'=> 'total_stock',
                'purchase_price'=> 'purchase_price',
                'profit_margin'=> 'profit_margin',
                'product_price'=> 'product_price',
                'description'=> 'description',
                'status'=> 'status',
                'primary_number'=> 'primary_number',
                'primary_unit'=> 'primary_unit_code',
                'secondary_number'=> 'secondary_number',
                'secondary_unit'=> 'secondary_unit_code',
                'supplier'=> 'supplier_code',
                'brand'=> 'brand_code',
                'series'=> 'series_code',
                'refundable'=> 'refundable',
                'godowns'=> 'godowns_code',
                'stock_by_godown'=> 'stock_by_godown',
                'tips'=> 'Note*',
            ],
            [
                'product_name'=> 'Test Product',
                'product_code'=> '0025',
                'category'=> '1',
                'size'=> 'Medium',
                'color'=> 'Red, Green',
                'serial_numbers'=> '',
                'total_stock'=> '150',
                'purchase_price'=> '140',
                'profit_margin'=> '10',
                'product_price'=> '280',
                'description'=> 'This is the test product',
                'status'=> 'Active',
                'primary_number'=> '1',
                'primary_unit'=> '2',
                'secondary_number'=> '12',
                'secondary_unit'=> '3',
                'supplier'=> '1',
                'brand'=> '1',
                'series'=> '1',
                'refundable'=> 'Yes',
                'godowns'=> '1,2',
                'stock_by_godown'=> '250,150',
                'tips'=> '(Change the type of godowns_code and stock_by_godown from number to text)',
            ],
        ]);

        UpdateExcelExport::insert([
            [
                'product_code'=> 'unique_product_code',
                'original_vendor_price'=> 'original_vendor_price',
                'changing_rate'=> 'changing_rate',
                'final_vendor_price'=> 'final_vendor_price',
                'carrying_cost'=> 'carrying_cost',
                'transportation_cost'=> 'transportation_cost',
                'miscellaneous_percent'=> 'miscellaneous_percent',
                'other_cost'=> 'other_cost',
                'cost_of_product'=> 'cost_of_product',
                'custom_duty'=> 'custom_duty',
                'after_custom'=> 'after_custom',
                'tax'=> 'tax',
                'total_cost'=> 'total_cost',
                'profit_margin'=> 'profit_margin',
                'product_price'=> 'product_price',
                'tips'=> 'Note*',
            ],
            [
                'product_code'=> '0025',
                'original_vendor_price'=> '140',
                'changing_rate'=> '2',
                'final_vendor_price'=> '280',
                'carrying_cost'=> '10',
                'transportation_cost'=> '10',
                'miscellaneous_percent' => '10',
                'other_cost'=> '10',
                'cost_of_product'=> '265',
                'custom_duty'=> '10',
                'after_custom'=> '365',
                'tax'=> '13',
                'total_cost'=> '456',
                'profit_margin'=> '10',
                'product_price'=> '620',
                'tips'=> '(Write the correct product name and product code to update)',
            ],
        ]);

        UpdateNonImporter::insert([
            [
                'product_code'=> 'unique_product_code',
                'purchase_price'=> 'purchase_price',
                'profit_margin'=> 'profit_margin',
                'product_price'=> 'product_price',
                'tips'=> 'Note*',
            ],
            [
                'product_code'=> '0025',
                'purchase_price'=> '140',
                'profit_margin'=> '100',
                'product_price'=> '280',
                'tips'=> '(Write the correct product name and product code to update)',
            ],
        ]);
        // $this->call(ProductSeeder::class);
        $this->call(PosSettingSeeder::class);
    }
}
