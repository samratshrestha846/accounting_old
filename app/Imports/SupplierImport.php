<?php

namespace App\Imports;

use App\Models\District;
use App\Models\Vendorconcern;
use App\Models\SubAccount;
use App\Models\Vendor;
use App\Models\ChildAccount;
use App\Models\OpeningBalance;
use App\Models\FiscalYear;
use App\Models\Supp;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use function app\NepaliCalender\datenep;
use Session;

class SupplierImport implements ToCollection, WithHeadingRow
{
    public function collection(collection $rows)
    {

        try{
            // dd($rows);

        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        foreach($rows as $key=>$row){

            $subaccount = SubAccount::where('slug', 'sundry-creditors')->first();
            if($subaccount == null){
                $newsubaccount = SubAccount::create([
                    'title' => 'Sundry Creditors',
                    'slug' => Str::slug('Sundry Creditors'),
                    'account_id' => '1'
                ]);
                $newsubaccount->save();
            }

            $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;
           $vendor =  Vendor::where('supplier_code',$row['company_code'])->first();
           $openingbal = $row['opening_balance']*-1;
            if($vendor){
                $childacid = $vendor->child_account_id;
                if(!empty($childacid)){
                    OpeningBalance::where('child_account_id',$childacid)->update(['opening_balance'=>$openingbal,'closing_balance'=>$openingbal]);
                }

            }else{

                $childAccount = ChildAccount::create([
                    'title' => $row['company_name'],
                    'slug' => Str::slug($row['company_name']),
                    'opening_balance' => $openingbal,
                    'sub_account_id' => $subaccount_id,
                ]);
                $openingbalance = OpeningBalance::create([
                    'child_account_id' => $childAccount['id'],
                    'fiscal_year_id' => $current_fiscal_year->id,
                    'opening_balance' => $openingbal,
                    'closing_balance' => $openingbal,
                ]);


                $new_vendor = Vendor::create([
                    'company_id' => 1,
                    'branch_id' => 1,
                    'company_name' => $row['company_name'],
                    'company_email' => $row['company_email'],
                    'company_phone' => null,
                    'company_address' => $row['address'] ?? null,
                    'province_id' => null,
                    'district_id' => null,
                    'pan_vat' => $row['pan_vat'] ?? null,
                    'supplier_code' => $row['company_code'] ?? 'SU' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'child_account_id'=>$childAccount->id,
                ]);

                Vendorconcern::create([
                    'vendor_id'=>$new_vendor->id,
                    'concerned_name'=>$row['company_name'],
                    'default'=>1,
                ]);
            }

        }
    }catch(\Exception $e)
        {

            Session::put('error',$e->getMessage());
            throw new \Exception($e->getMessage());
        }


        return false;

        $district =   $this->getDistrict($row['district']);
        return new Vendor([
            'company_id' => 1,
            'branch_id' => 1,
            'company_name' => $row['name'],
            'company_email' => $row['email'],
            'company_phone' => $row['phone'],
            'company_address' => $row['address'],
            'province_id' => $district->province_id ?? null,
            'district_id' => $district->id ?? null,
            'pan_vat' => $row['pan_vat'],
            'supplier_code' => $row['suppliercode'] ?? 'SU' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT),
        ]);
    }

    private function getDistrict($name)
    {
        return District::select('province_id', 'id')->where('dist_name', $name)->first();
    }
}
