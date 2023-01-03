<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber() ,
            'province_no' => 1,
            'district_no' => 1,
            'local_address' => $this->faker->address(),
            'registration_no' => Str::random('10') ,
            'pan_vat' => Str::random('10'),
            'company_logo' => $this->faker->image(),
            'is_importer' => true,
        ];
    }
}
