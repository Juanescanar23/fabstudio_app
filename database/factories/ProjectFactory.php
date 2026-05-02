<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'lead_id' => null,
            'code' => 'FAB-'.$this->faker->unique()->numerify('####'),
            'name' => $this->faker->randomElement([
                'Casa bioclimatica',
                'Remodelacion integral',
                'Modulo comercial',
                'Vivienda campestre',
            ]),
            'typology' => $this->faker->randomElement(['residencial', 'comercial', 'interiorismo']),
            'status' => $this->faker->randomElement(['planning', 'active', 'paused']),
            'current_phase' => $this->faker->randomElement(['Diagnostico', 'Diseno', 'Presupuesto']),
            'location' => $this->faker->city(),
            'description' => $this->faker->paragraph(),
            'budget_estimate' => $this->faker->numberBetween(20000000, 180000000),
            'starts_at' => $this->faker->optional()->date(),
            'ends_at' => null,
        ];
    }
}
