<?php

namespace Database\Factories;

use App\Enums\ClientType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_type' => ClientType::PERSON,
            'name' => $this->faker->name(),
            'client_code' => $this->faker->unique()->safeEmail(),
            'pan_vat' => now(),
            'phone' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => Str::random(10),
        ];
    }
}
