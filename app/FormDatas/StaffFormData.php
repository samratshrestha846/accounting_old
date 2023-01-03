<?php
namespace App\FormDatas;

use Illuminate\Http\UploadedFile;

class StaffFormData {

    public int $departmentId;
    public int $positionId;
    public string $employeeId;
    public string $firstName;
    public string $lastName;
    public string $email;
    public ?string $phone;
    public ?string $gender;
    public ?string $empType;
    public ?string $dateOfBirth;
    public ?string $city;
    public ?string $address;
    public ?string $postcode;
    public ?string $joinDate;
    public ?string $image;
    public ?string $nationalId;
    public ?string $document;
    public ?string $contract;

    public function __construct(
        int $departmentId,
        int $positionId,
        string $employeeId,
        string $firstName,
        string $lastName,
        string $email,
        string $phone = null,
        string $gender = null,
        string $empType = null,
        string $dateOfBirth = null,
        string $city = null,
        string $address = null,
        string $postcode = null,
        string $joinDate = null,
        string $image = null,
        string $nationalId = null,
        string $document = null,
        string $contract = null
    )
    {
        $this->departmentId = $departmentId;
        $this->positionId = $positionId;
        $this->employeeId = $employeeId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->gender = $gender;
        $this->empType = $empType;
        $this->dateOfBirth = $dateOfBirth;
        $this->city = $city;
        $this->address = $address;
        $this->postcode = $postcode;
        $this->joinDate = $joinDate;
        $this->image = $image;
        $this->nationalId = $nationalId;
        $this->document = $document;
        $this->contract = $contract;
    }


}
