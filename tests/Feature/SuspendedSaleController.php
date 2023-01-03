<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuspendedSaleController extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $products = [
            'id' => 1,
        ];
        $response = $this->postJson('products/sales/suspends',[

        ]);

        $response->assertStatus(200);
    }
}
