<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectPhase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectPhase>
 */
class ProjectPhaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->randomElement(['Diagnostico', 'Anteproyecto', 'Diseno', 'Cotizacion']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'sort_order' => $this->faker->numberBetween(1, 5),
            'starts_at' => $this->faker->optional()->date(),
            'due_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'completed_at' => null,
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
