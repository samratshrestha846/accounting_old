<?php

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Models\Client;
use App\Models\District;
use App\Models\Godown;
use App\Models\Province;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SalesBillingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_create_sales_billing()
    {
        $this->seed();

        $user = User::first();
        $godown = Godown::first();
        $customer = Client::factory()->create();

        $this->actingAs($user);

        $data = [
            'fiscal_year_id' => 1,
            'customer_id' => $customer->id,
            'ledger_no' => '32424',
            'file_no' => '21312',
            'payment_mode' => '1',
            'payment_amount' => '230',
            'products' => [
                [
                    'product_id' => 1,
                    'total_quantity' => 1,
                    'product_price' => 200,
                    'tax_rate_id' => 1,
                    'tax_type' => TaxType::EXCLUSIVE,
                    'discount_type' => DiscountType::PERCENTAGE,
                    'value_discount' => 10,
                ]
            ],
            'gross_total' => 200,
            'remarks' => 'This is payment',
            'vat_refundable_amount' => 10,
            'sync_ird' => '0',
            'status' => 0,
        ];

        // $this->actingAs($user);
        $response = $this->json('POST','api/godowns/'.$godown->id.'/salesbilling', $data);

        dd($response->json());

        $response->assertStatus(200);
    }
}
