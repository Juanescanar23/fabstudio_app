<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectDocument>
 */
class ProjectDocumentFactory extends Factory
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
            'uploaded_by_id' => User::factory(),
            'title' => $this->faker->randomElement(['Plano inicial', 'Contrato', 'Memoria descriptiva']),
            'category' => $this->faker->randomElement(['planos', 'contratos', 'cotizaciones', 'entregables']),
            'visibility' => $this->faker->randomElement(['internal', 'client']),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
