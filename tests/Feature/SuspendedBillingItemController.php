<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SuspendedBill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SuspendedBillingItemController extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_suspended_billing_item_list()
    {
        $this->actingAs(User::factory()->create());

        Product::factory()->create();

        $products = [
            [

            ]
        ];

        $suspendedBilling = SuspendedBill::factory()->create([

        ]);

        // dd($suspendedBilling);
        $response = $this->get('/api/products/suspendedsales/5/suspendeditems');
        dd($response->json());
        $response->assertStatus(200);
    }
}
