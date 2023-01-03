<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Company;
use App\Models\District;
use App\Models\FiscalYear;
use App\Models\SubAccount;
use App\Models\ChildAccount;
use App\Models\OpeningBalance;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use function app\NepaliCalender\datenep;

class CustomerImport implements ToCollection, WithHeadingRow
{
    public function collection(collection $rows)
    {
        try{
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $company = Company::first();

        $subaccount = SubAccount::where('slug', 'sundry-debtors')->first();
        if($subaccount == null){
            $newsubaccount = SubAccount::create([
                'title' => 'Sundry Debtors',
                'slug' => Str::slug('Sundry Debtors'),
                'account_id' => '1'
            ]);
            $newsubaccount->save();
        }

        $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;

        foreach($rows as $key=>$row){

        $customer = Client::where('client_code',$row['customer_code'])->first();
        if($customer){
            $childacid = $customer->child_account_id;
            if(!empty($childacid)){
                OpeningBalance::where('child_account_id',$childacid)->update(['opening_balance'=>$row['opening_balance'],'closing_balance'=>$row['opening_balance']]);
            }
        }else{
            $childAccount = ChildAccount::create([
                'title' => $row['name'],
                'slug' => Str::slug($row['name']),
                'opening_balance' => $row['opening_balance'],
                'sub_account_id' => $subaccount_id,
            ]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $row['opening_balance'],
                'closing_balance' => $row['opening_balance'],
            ]);

             Client::create([
                'company_id'=>$company->id ?? 1,
                'name'=> $row['name'],
                'phone'=> null,
                'email'=>$row['email'],
                'local_address'=>null,
                'dealer_type_id' => 6,
                'client_code' =>  'CL' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                'pan_vat' => 0,
                'district' => null,
                'child_account_id'=>$childAccount->id,
            ]);
        }
        }
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        return false;
        // as.k
        $district =   $this->getDistrict($row['district']);
        return new Client(
            [
                'company_id' => $row['company_id'],
                'branch_id' => $row['branch_id'],
                'client_type' => $row['client_type'],
                'name' => $row['name'],
                'client_code' => $row['clientcode'],
                'pan_vat' => $row['pan_vat'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'province' => $row['provience'] ?? null,
                'district' =>  $row['district'] ?? null,
                'local_address' => $row['address'],
                'dealer_type_id' => 6,
            ]
        );
    }
    private function getDistrict($name)
    {
        return District::select('province_id', 'id')->where('dist_name', $name)->first();
    }
}
