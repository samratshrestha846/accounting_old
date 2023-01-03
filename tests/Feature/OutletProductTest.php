<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutletProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_will_get_outlet_product_list()
    {
        // $company = Company::factory()
        //     ->has(
        //         Branch::factory()
        //             ->count(1)
        //     )
        //     ->create();

        // $user = User::factory()->create();
        // $user->usercompany()->create([
        //     'company_id' => $company->id,
        //     'branch_id' => Branch::first()->id,
        //     'is_selected' =>  true
        // ]);

        // $this->actingAs($user);
        // $response = $this->get('/api/pos/products');
        // dd($response->json());
        // $response->assertStatus(200);
    }
}
