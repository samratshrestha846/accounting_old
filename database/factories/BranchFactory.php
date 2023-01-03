<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Branch '.rand(1, 100),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'province_no' => Province::first()->id,
            'district_no' => District::first()->id,
            'local_address' => $this->faker->address(),
            'is_headoffice' => true,
        ];
    }
}
