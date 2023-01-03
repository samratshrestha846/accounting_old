<?php
namespace App\Actions;

use App\FormDatas\ProductFormData;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\OpeningBalance;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\SubAccount;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function app\NepaliCalender\datenep;

class CreateProductAction {

    public function execute(User $user, ProductFormData $productFormData): Product
    {
        DB::beginTransaction();
        try {
            // Child Account of Product
            if($productFormData->opening_balance == null){
                $opening_balance = 0;
            }else{
                if($productFormData->behaviour == "credit")
                {
                    $opening_balance = '-'.$productFormData->opening_balance;
                }
                elseif($productFormData->behaviour == "debit")
                {
                    $opening_balance = $productFormData->opening_balance;
                }
            }

            $subaccount = SubAccount::where('slug', 'inventory')->first();
            if($subaccount == null){
                $newsubaccount = SubAccount::create([
                    'title' => 'Inventory',
                    'slug' => Str::slug('Inventory'),
                    'account_id' => '1',
                    'sub_account_id' => '1'
                ]);
                $newsubaccount->save();
            }

            $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;

            $childAccount = ChildAccount::create([
                'title' => $productFormData->productName .'('.$productFormData->productCode.')',
                'slug' => Str::slug($productFormData->productName .'('.$productFormData->productCode.')'),
                'opening_balance' => $opening_balance,
                'sub_account_id' => $subaccount_id
            ]);
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $opening_balance,
                'closing_balance' => $opening_balance
            ]);

            // For Storing Product
            $isImporter = $user->isImporter();
            $productFormData->setIsImport($isImporter);
            $totalStock = $productFormData->getTotalStock();
            $primaryUnit = Unit::findOrFail($productFormData->primaryUnitId);
            $secondaryUnit = Unit::where('id',$productFormData->secondaryUnitId)->first();

            $product = Product::create([
                'product_name' => $productFormData->productName,
                'product_code' => $productFormData->productCode,
                'category_id' => $productFormData->categoryId,
                'size' => $productFormData->size,
                'weight' => $productFormData->weight,
                'lot_no' => $productFormData->lotNo,
                'color' => $productFormData->color,
                'opening_stock' => $totalStock,
                'total_stock' => $totalStock,
                'original_vendor_price' => $productFormData->originalSupplierPrice,
                'charging_rate' => $productFormData->changingRate,
                'final_vendor_price' => $productFormData->getFinalSupplierCost(),
                'carrying_cost' => $productFormData->carryingCost,
                'transportation_cost' => $productFormData->transportationCost,
                'miscellaneous_percent' => $productFormData->miscellaneousPercent,
                'other_cost' => $productFormData->getOtherCost(),
                'cost_of_product' => $productFormData->getProductCost(),
                'custom_duty' => $productFormData->customDutyPercent,
                'after_custom' => $productFormData->getCustomAmount(),
                'tax' => $productFormData->taxId,
                'total_cost' => $productFormData->getTotalCost(),
                'margin_type' => $productFormData->profitMarginType,
                'margin_value' => $productFormData->profitMarginValue ,
                'profit_margin' => $productFormData->getProfitMargin(),
                'product_price' =>  $productFormData->getFinalSellingPrice(),
                'description' => $productFormData->description,
                'status' => $productFormData->status,
                'primary_number' => $productFormData->primaryNumber,
                'primary_unit' => $primaryUnit->unit,
                'primary_unit_id' => $productFormData->primaryUnitId,
                'primary_unit_code' => $primaryUnit->unit_code,
                'secondary_number' => $productFormData->secondaryNumber,
                'secondary_unit' => $secondaryUnit->unit ?? '' ,
                'secondary_unit_id' => $productFormData->secondaryUnitId,
                'secondary_unit_code' => $primaryUnit->unit_code,
                'supplier_id' => $productFormData->supplierId,
                'declaration_form_no' => $productFormData->declaration_form_no,
                'brand_id' => $productFormData->brandId,
                'series_id' => $productFormData->seriesId,
                'refundable' => $productFormData->refundable,
                'secondary_unit_selling_price' => 0,
                'has_serial_number' => $productFormData->hasSerialNumber,
                'warranty_months' => $productFormData->warrantyMonths,
                'manufacturing_date' => $productFormData->manufacturing_date,
                'expiry_date' => $productFormData->expiry_date,
                'child_account_id' => $childAccount['id']
            ]);

            if ( $productFormData->productImages ) {
                foreach ($productFormData->productImages as $image ) {
                    $imagename = $image->store( 'item_images', 'uploads' );
                    ProductImages::create( [
                        'product_id' => $product->id,
                        'location' => $imagename,
                    ]);
                }
            } else
            {
                ProductImages::create( [
                    'product_id' => $product->id,
                    'location' => 'favicon.png',
                ]);
            }

            $godownSerialNumbers = [];

            //create product and godown relation

            foreach($productFormData->godowns as $godown) {

                if($godown['godown_id'] == null){
                    continue;
                }

                $stock = $productFormData->hasSerialNumber ? count($godown['serial_numbers'] ?? []) : ($godown['stock'] ?? 0);

                $godownProduct = GodownProduct::create([
                    'product_id' => $product->id,
                    'godown_id' => $godown['godown_id'] ?? 0,
                    'floor_no' => $godown['floor_no'] ?? null,
                    'rack_no' => $godown['rack_no'] ?? null,
                    'row_no' => $godown['row_no'] ?? null,
                    'opening_stock' => $stock,
                    'stock' => $stock,
                    'alert_on' => $godown['alert_no'] ?? 0,
                    'has_serial_number' => $productFormData->hasSerialNumber
                ]);

                $serialNumbers = Arr::get($godown, 'serial_numbers');
                if($serialNumbers && is_array($serialNumbers)) {
                    foreach($serialNumbers as $serialnumber) {
                        $godownSerialNumbers[] = [
                            'godown_product_id' => $godownProduct->id,
                            'serial_number' => $serialnumber
                        ];
                    }
                }
            }

            //create godown serial number
            if($productFormData->hasSerialNumber && count($godownSerialNumbers) > 0) {
                GodownSerialNumber::insert($godownSerialNumbers);
            }

            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $product;
    }
}
