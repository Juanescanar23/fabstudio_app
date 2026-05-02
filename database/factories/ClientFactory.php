<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(['individual', 'company']),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'identification' => $this->faker->numerify('##########'),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'status' => 'active',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
