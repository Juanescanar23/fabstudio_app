<?php

namespace Database\Factories;

use App\Models\QuoteTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteTemplate>
 */
class QuoteTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'created_by_id' => User::factory(),
            'name' => 'Plantilla '.$this->faker->words(3, true),
            'type' => 'design',
            'status' => 'active',
            'currency' => 'COP',
            'default_valid_days' => 30,
            'description' => 'Plantilla base para propuestas arquitectónicas.',
            'sections' => [
                [
                    'heading' => 'Alcance',
                    'body' => 'Desarrollo conceptual y anteproyecto para {{project_name}}.',
                    'sort_order' => 1,
                ],
                [
                    'heading' => 'Entregables',
                    'body' => 'Planos base, referencias visuales y una ronda de ajustes.',
                    'sort_order' => 2,
                ],
            ],
            'line_items' => [
                [
                    'name' => 'Diseño arquitectónico',
                    'description' => 'Etapa inicial de diseño y documentación.',
                    'quantity' => 1,
                    'unit_price' => 18000000,
                ],
            ],
            'terms' => 'La propuesta requiere revisión humana antes de enviarse al cliente.',
            'ai_instructions' => 'Priorizar claridad comercial, alcance concreto y lenguaje profesional.',
            'metadata' => [],
        ];
    }
}
