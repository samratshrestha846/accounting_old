<?php
namespace App\Actions;

use App\Models\Client;
use App\Models\Credit;
use App\Models\SuperSetting;

class CreateCustomerCreditAction {
    public function execute(Client $client)
    {
        $supersetting = SuperSetting::first();

        return Credit::create([
            'customer_id' => $client->id,
            'allocated_days' => $supersetting->allocated_days,
            'allocated_bills' => $supersetting->allocated_bills,
            'allocated_amount' => $supersetting->allocated_amount,
        ]);
    }
}
