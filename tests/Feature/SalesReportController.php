<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SalesReportController extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_fetch_today_sale_report()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->actingAs($user);

        $response = $this->get('/api/products/salesreports/today-sale');

        $response->assertStatus(200);
    }
}
