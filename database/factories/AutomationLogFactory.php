<?php

namespace Database\Factories;

use App\Models\AutomationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AutomationLog>
 */
class AutomationLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'automation_key' => 'qa.automation',
            'category' => 'operation',
            'severity' => 'info',
            'status' => 'sent',
            'channel' => 'mail',
            'title' => 'Automatizacion QA',
            'summary' => $this->faker->sentence(),
            'payload' => [
                'source' => 'factory',
            ],
            'deduplication_key' => 'qa.automation:'.$this->faker->uuid(),
            'processed_at' => now(),
            'notified_at' => now(),
        ];
    }
}
