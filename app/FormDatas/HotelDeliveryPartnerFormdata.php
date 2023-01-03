<?php
namespace App\FormDatas;

class HotelDeliveryPartnerFormdata {

    public string $name;
    public string $email;
    public string $address;
    public string $contact_number;
    public int $province_id;
    public int $district_id;
    public bool $is_active;

    public function __construct(
        string $name,
        string $email,
        string $address,
        string $contact_number,
        int $province_id,
        int $district_id,
        bool $is_active
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->contact_number = $contact_number;
        $this->province_id = $province_id;
        $this->district_id = $district_id;
        $this->is_active = $is_active;
    }
}
