<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\QuoteVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteVersion>
 */
class QuoteVersionFactory extends Factory
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
            'quote_id' => Quote::factory(),
            'created_by_id' => User::factory(),
            'version_number' => 1,
            'status' => 'draft',
            'content' => [
                'scope' => $this->faker->paragraph(),
                'deliverables' => $this->faker->sentences(3),
            ],
            'ai_model' => null,
            'ai_prompt_hash' => null,
            'pdf_path' => null,
            'subtotal' => $subtotal,
            'tax' => 0,
            'discount' => 0,
            'total' => $subtotal,
            'reviewed_at' => null,
            'approved_at' => null,
        ];
    }
}
