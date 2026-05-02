<?php

namespace Database\Factories;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
class MilestoneFactory extends Factory
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
            'project_phase_id' => null,
            'title' => $this->faker->randomElement([
                'Reunion inicial',
                'Entrega de propuesta',
                'Revision de planos',
                'Aprobacion del cliente',
            ]),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'sort_order' => $this->faker->numberBetween(1, 8),
            'due_at' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'completed_at' => null,
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
