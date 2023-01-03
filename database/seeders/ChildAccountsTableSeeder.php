<?php

namespace Database\Seeders;

use App\Models\ChildAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChildAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        // ChildAccount::insert([
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "14",
        //         "title"=>"Capital",
        //         "slug"=>Str::slug("Capital"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "15",
        //         "title"=>"Dividend",
        //         "slug"=>Str::slug("Dividend"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "1",
        //         "title"=>"Cash In Hand",
        //         "slug"=>Str::slug("Cash In Hand"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "1",
        //         "title"=>"Cash In Bank",
        //         "slug"=>Str::slug("Cash In Bank"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "5",
        //         "title"=>"Accounts Payable",
        //         "slug"=>Str::slug("Accounts Payable"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "5",
        //         "title"=>"Bank Overdraft",
        //         "slug"=>Str::slug("Bank Overdraft"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "8",
        //         "title"=>"Product Sales",
        //         "slug"=>Str::slug("Product Sales"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "8",
        //         "title"=>"Service Sales",
        //         "slug"=>Str::slug("Service Sales"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "11",
        //         "title"=>"Salary Expenses",
        //         "slug"=>Str::slug("Salary Expenses"),
        //     ],
        //     [
        //         "company_id" => 2,
        //         "branch_id" => 2,
        //         "sub_account_id" => "11",
        //         "title"=>"Office Rent",
        //         "slug"=>Str::slug("Office Rent"),
        //     ]
        // ]);

        // ChildAccount::insert([
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "14",
        //         "title"=>"Capital",
        //         "slug"=>Str::slug("Capital"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "15",
        //         "title"=>"Dividend",
        //         "slug"=>Str::slug("Dividend"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "1",
        //         "title"=>"Cash In Hand",
        //         "slug"=>Str::slug("Cash In Hand"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "1",
        //         "title"=>"Cash In Bank",
        //         "slug"=>Str::slug("Cash In Bank"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "5",
        //         "title"=>"Accounts Payable",
        //         "slug"=>Str::slug("Accounts Payable"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "5",
        //         "title"=>"Bank Overdraft",
        //         "slug"=>Str::slug("Bank Overdraft"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "8",
        //         "title"=>"Product Sales",
        //         "slug"=>Str::slug("Product Sales"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "8",
        //         "title"=>"Service Sales",
        //         "slug"=>Str::slug("Service Sales"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "11",
        //         "title"=>"Salary Expenses",
        //         "slug"=>Str::slug("Salary Expenses"),
        //     ],
        //     [
        //         "company_id" => 1,
        //         "branch_id" => 1,
        //         "sub_account_id" => "11",
        //         "title"=>"Office Rent",
        //         "slug"=>Str::slug("Office Rent"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "14",
        //         "title"=>"Capital",
        //         "slug"=>Str::slug("Capital"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "15",
        //         "title"=>"Dividend",
        //         "slug"=>Str::slug("Dividend"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "1",
        //         "title"=>"Cash In Hand",
        //         "slug"=>Str::slug("Cash In Hand"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "1",
        //         "title"=>"Cash In Bank",
        //         "slug"=>Str::slug("Cash In Bank"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "5",
        //         "title"=>"Accounts Payable",
        //         "slug"=>Str::slug("Accounts Payable"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "5",
        //         "title"=>"Bank Overdraft",
        //         "slug"=>Str::slug("Bank Overdraft"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "8",
        //         "title"=>"Product Sales",
        //         "slug"=>Str::slug("Product Sales"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "8",
        //         "title"=>"Service Sales",
        //         "slug"=>Str::slug("Service Sales"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "11",
        //         "title"=>"Salary Expenses",
        //         "slug"=>Str::slug("Salary Expenses"),
        //     ],
        //     [
        //         "company_id" => 3,
        //         "branch_id" => 3,
        //         "sub_account_id" => "11",
        //         "title"=>"Office Rent",
        //         "slug"=>Str::slug("Office Rent"),
        //     ]
        // ]);
    }
}
