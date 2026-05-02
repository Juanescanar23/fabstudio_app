<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(5000000, 60000000);

        return [
            'project_id' => Project::factory(),
            'client_id' => Client::factory(),
            'created_by_id' => User::factory(),
            'quote_number' => 'COT-'.$this->faker->unique()->numerify('####'),
            'title' => 'Propuesta '.$this->faker->randomElement(['arquitectonica', 'bioclimatica', 'de remodelacion']),
            'status' => $this->faker->randomElement(['draft', 'reviewed', 'approved']),
            'currency' => 'COP',
            'subtotal' => $subtotal,
            'tax' => 0,
            'discount' => 0,
            'total' => $subtotal,
            'valid_until' => $this->faker->dateTimeBetween('+15 days', '+45 days'),
            'sent_at' => null,
            'approved_at' => null,
            'notes' => $this->faker->optional()->sentence(),
            'metadata' => [],
        ];
    }
}
