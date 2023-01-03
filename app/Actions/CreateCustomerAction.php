<?php
namespace App\Actions;

use App\Models\Client;
use Illuminate\Support\Arr;

class CreateCustomerAction {

    public function execute(array $data): Client
    {
        $customerName = Arr::get($data, 'name');

        $client = Client::create( [
            'client_type'=> Arr::get($data, 'client_type'),
            'name'=> $customerName ? $customerName : Arr::get($data, 'phone'),
            'client_code'=> Arr::get($data, 'client_code'),
            'pan_vat'=> Arr::get($data, 'pan_vat'),
            'phone'=> Arr::get($data, 'phone'),
            'email'=> Arr::get($data, 'email'),
            'province'=> Arr::get($data, 'province'),
            'district'=> Arr::get($data, 'district'),
            'local_address'=> Arr::get($data, 'local_address'),
            'concerned_name'=> Arr::get($data, 'concerned_name'),
            'concerned_email'=> Arr::get($data, 'concerned_email'),
            'concerned_phone'=> Arr::get($data, 'concerned_phone'),
            'designation'=> Arr::get($data, 'designation'),
            'dealer_type_id' => Arr::get($data,'dealer_type_id'),
        ] );

        (new CreateCustomerCreditAction())->execute($client);

        return $client;
    }
}
