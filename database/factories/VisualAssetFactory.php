<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use App\Models\VisualAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VisualAsset>
 */
class VisualAssetFactory extends Factory
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
            'title' => $this->faker->randomElement(['Render fachada', 'Plano general', 'Modelo 3D base']),
            'type' => $this->faker->randomElement(['image', 'render', 'plan', 'model_3d']),
            'visibility' => $this->faker->randomElement(['internal', 'client', 'public']),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'file_path' => 'projects/demo/visuals/'.$this->faker->uuid().'.jpg',
            'preview_path' => null,
            'external_url' => null,
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(120000, 8000000),
            'metadata' => [
                'alt' => $this->faker->sentence(),
            ],
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
