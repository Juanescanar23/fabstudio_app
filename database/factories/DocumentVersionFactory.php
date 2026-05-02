<?php

namespace Database\Factories;

use App\Models\DocumentVersion;
use App\Models\ProjectDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentVersion>
 */
class DocumentVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_document_id' => ProjectDocument::factory(),
            'uploaded_by_id' => User::factory(),
            'version_number' => 1,
            'original_name' => $this->faker->slug().'.pdf',
            'file_path' => 'projects/demo/documents/'.$this->faker->uuid().'.pdf',
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => $this->faker->numberBetween(50000, 6000000),
            'checksum' => $this->faker->sha256(),
            'notes' => $this->faker->optional()->sentence(),
            'is_current' => true,
        ];
    }
}
