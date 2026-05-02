<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => null,
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'source' => $this->faker->randomElement(['landing', 'referido', 'instagram', 'whatsapp']),
            'status' => $this->faker->randomElement(['new', 'contacted', 'qualified']),
            'interest' => $this->faker->randomElement(['vivienda', 'remodelacion', 'consultoria', 'obra nueva']),
            'message' => $this->faker->paragraph(),
            'metadata' => [
                'campaign' => $this->faker->optional()->word(),
            ],
            'converted_at' => null,
        ];
    }

    public function converted(Client $client): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => $client->id,
            'status' => 'converted',
            'converted_at' => now(),
        ]);
    }
}
