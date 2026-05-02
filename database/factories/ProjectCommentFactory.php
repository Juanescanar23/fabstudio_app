<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectComment>
 */
class ProjectCommentFactory extends Factory
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
            'user_id' => User::factory(),
            'commentable_type' => null,
            'commentable_id' => null,
            'type' => $this->faker->randomElement(['comment', 'approval', 'internal_note']),
            'visibility' => $this->faker->randomElement(['internal', 'client']),
            'body' => $this->faker->paragraph(),
            'decision' => null,
            'decided_at' => null,
            'metadata' => [],
        ];
    }
}
