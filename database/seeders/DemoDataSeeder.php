<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\CmsPage;
use App\Models\DocumentVersion;
use App\Models\Lead;
use App\Models\MediaItem;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\ProjectDocument;
use App\Models\ProjectPhase;
use App\Models\Quote;
use App\Models\QuoteVersion;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\VisualAsset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@fabstudio.local'))->firstOrFail();

        $client = Client::firstOrCreate(
            ['email' => 'cliente.demo@fabstudio.local'],
            [
                'name' => 'Cliente Demo FAB STUDIO',
                'phone' => '+57 300 000 0000',
                'city' => 'Popayan',
                'notes' => 'Cliente de demostracion para validar el flujo MVP.',
            ],
        );

        $clientRole = Role::firstOrCreate(['name' => 'client']);

        $clientUser = User::updateOrCreate(
            ['email' => 'cliente.demo@fabstudio.local'],
            [
                'client_id' => $client->id,
                'name' => 'Cliente Demo FAB STUDIO',
                'password' => Hash::make('password'),
            ],
        );

        $clientUser->forceFill(['email_verified_at' => now()])->save();

        if (! $clientUser->hasRole($clientRole)) {
            $clientUser->assignRole($clientRole);
        }

        $this->seedPublicContent();

        $existingProject = Project::where('code', 'FAB-0001')->first();

        if ($existingProject) {
            $existingProject->update($this->publicProjectAttributes());

            return;
        }

        $lead = Lead::factory()
            ->converted($client)
            ->create([
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'source' => 'landing',
                'interest' => 'vivienda bioclimatica',
            ]);

        $project = Project::factory()
            ->for($client)
            ->create([
                'lead_id' => $lead->id,
                'code' => 'FAB-0001',
                'name' => 'Casa Bioclimatica Demo',
                'typology' => 'residencial',
                'status' => 'active',
                'current_phase' => 'Anteproyecto',
                'location' => 'Popayan, Cauca',
            ] + $this->publicProjectAttributes());

        $phase = ProjectPhase::factory()
            ->for($project)
            ->create([
                'name' => 'Anteproyecto',
                'status' => 'in_progress',
                'sort_order' => 1,
            ]);

        Milestone::factory()
            ->for($project)
            ->create([
                'project_phase_id' => $phase->id,
                'title' => 'Entrega de primera propuesta',
                'status' => 'pending',
                'sort_order' => 1,
            ]);

        $document = ProjectDocument::factory()
            ->for($project)
            ->create([
                'uploaded_by_id' => $admin->id,
                'title' => 'Plano conceptual inicial',
                'category' => 'design',
                'visibility' => 'client',
                'status' => 'published',
            ]);

        DocumentVersion::factory()
            ->for($document, 'document')
            ->create([
                'uploaded_by_id' => $admin->id,
                'version_number' => 1,
                'original_name' => 'plano-conceptual-v1.pdf',
            ]);

        $asset = VisualAsset::factory()
            ->for($project)
            ->create([
                'uploaded_by_id' => $admin->id,
                'title' => 'Render fachada principal',
                'type' => 'render',
                'visibility' => 'client',
                'status' => 'published',
                'sort_order' => 1,
            ]);

        $quote = Quote::factory()
            ->for($project)
            ->for($client)
            ->create([
                'created_by_id' => $admin->id,
                'quote_number' => 'COT-0001',
                'title' => 'Propuesta inicial de diseno',
                'status' => 'review',
            ]);

        QuoteVersion::factory()
            ->for($quote)
            ->create([
                'created_by_id' => $admin->id,
                'version_number' => 1,
                'status' => 'review',
            ]);

        ProjectComment::factory()
            ->for($project)
            ->for($admin, 'user')
            ->for($asset, 'commentable')
            ->create([
                'type' => 'comment',
                'visibility' => 'internal',
                'body' => 'Render demo asociado al portal cliente.',
            ]);
    }

    private function seedPublicContent(): void
    {
        foreach ([
            ['site', 'site.meta_title', 'Título SEO principal', 'FAB STUDIO - Arquitectura y gestión visual', 'text'],
            ['site', 'site.meta_description', 'Descripción SEO principal', 'Arquitectura, visualización y seguimiento profesional de proyectos desde un portal privado para clientes.', 'textarea'],
            ['site', 'site.hero_eyebrow', 'Etiqueta hero', 'Arquitectura + gestión visual', 'text'],
            ['site', 'site.hero_title', 'Título hero', 'FAB STUDIO', 'text'],
            ['site', 'site.hero_summary', 'Resumen hero', 'Diseñamos, documentamos y acompañamos proyectos arquitectónicos con entregables claros, renders y seguimiento privado para clientes.', 'textarea'],
            ['contact', 'site.contact_email', 'Correo público', 'hola@fabstudio.local', 'email'],
            ['contact', 'site.contact_phone', 'Teléfono público', '+57 300 000 0000', 'text'],
        ] as [$group, $key, $label, $value, $type]) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'label' => $label,
                    'value' => $value,
                    'type' => $type,
                    'is_public' => true,
                ],
            );
        }

        CmsPage::updateOrCreate(
            ['slug' => 'inicio'],
            [
                'title' => 'FAB STUDIO',
                'eyebrow' => 'Arquitectura + gestión visual',
                'summary' => 'Diseñamos proyectos con criterio técnico y una experiencia digital clara para clientes.',
                'content' => [
                    'service_1_title' => 'Diseño arquitectónico',
                    'service_1_text' => 'Conceptualización, anteproyecto y desarrollo espacial con control técnico desde el inicio.',
                    'service_2_title' => 'Documentación y seguimiento',
                    'service_2_text' => 'Entregables, versiones, comentarios y aprobaciones trazables para cada proyecto.',
                    'service_3_title' => 'Visualización',
                    'service_3_text' => 'Renders, recursos visuales y revisión privada para tomar decisiones con claridad.',
                ],
                'seo_title' => 'FAB STUDIO - Arquitectura y gestión visual',
                'seo_description' => 'Arquitectura, visualización y seguimiento profesional de proyectos desde una plataforma privada para clientes.',
                'is_published' => true,
                'published_at' => now(),
            ],
        );

        foreach ([
            [
                'title' => 'Casa contemporánea integrada al paisaje',
                'collection' => 'hero',
                'alt_text' => 'Casa contemporánea con relación interior exterior',
                'caption' => 'Dirección visual para proyectos residenciales.',
                'external_url' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1800&q=85',
                'sort_order' => 1,
            ],
            [
                'title' => 'Interior cálido de vivienda',
                'collection' => 'galeria',
                'alt_text' => 'Interior residencial con luz natural',
                'caption' => 'Interiorismo con materialidad sobria.',
                'external_url' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1200&q=85',
                'sort_order' => 1,
            ],
            [
                'title' => 'Volumen residencial exterior',
                'collection' => 'galeria',
                'alt_text' => 'Fachada residencial contemporánea',
                'caption' => 'Lectura volumétrica desde etapas tempranas.',
                'external_url' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=85',
                'sort_order' => 2,
            ],
            [
                'title' => 'Espacio social abierto',
                'collection' => 'galeria',
                'alt_text' => 'Sala comedor con apertura visual',
                'caption' => 'Acompañamiento visual para decisiones de diseño.',
                'external_url' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=85',
                'sort_order' => 3,
            ],
        ] as $media) {
            MediaItem::updateOrCreate(
                ['title' => $media['title']],
                $media + [
                    'disk' => 'public',
                    'is_public' => true,
                    'published_at' => now(),
                ],
            );
        }
    }

    private function publicProjectAttributes(): array
    {
        return [
            'public_slug' => 'casa-bioclimatica-demo',
            'public_summary' => 'Proyecto residencial de demostración enfocado en confort, luz natural y una relación franca con el paisaje.',
            'public_cover_path' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1400&q=85',
            'is_public' => true,
            'is_featured' => true,
            'public_published_at' => now(),
            'seo_title' => 'Casa Bioclimática Demo - FAB STUDIO',
            'seo_description' => 'Proyecto residencial público de FAB STUDIO con enfoque bioclimático, visualización y seguimiento profesional.',
        ];
    }
}
