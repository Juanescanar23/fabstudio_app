<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\CmsPage;
use App\Models\Lead;
use App\Models\MediaItem;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_renders_cms_content_and_featured_projects(): void
    {
        $this->withoutVite();

        CmsPage::create([
            'slug' => 'inicio',
            'title' => 'FAB STUDIO',
            'eyebrow' => 'Arquitectura consciente',
            'summary' => 'Diseño y seguimiento profesional.',
            'content' => [
                'service_1_title' => 'Diseño a medida',
                'service_1_text' => 'Texto editable desde CMS.',
            ],
            'is_published' => true,
            'published_at' => now()->subMinute(),
        ]);

        MediaItem::create([
            'title' => 'Hero demo',
            'collection' => 'hero',
            'external_url' => 'https://example.com/hero.jpg',
            'is_public' => true,
            'published_at' => now()->subMinute(),
        ]);

        $project = Project::factory()
            ->for(Client::factory())
            ->create([
                'name' => 'Casa Pública',
                'public_slug' => 'casa-publica',
                'public_summary' => 'Proyecto visible en landing.',
                'is_public' => true,
                'is_featured' => true,
                'public_published_at' => now()->subMinute(),
            ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('FAB STUDIO')
            ->assertSee('Diseño a medida')
            ->assertSee($project->name)
            ->assertSee(route('public.projects.show', $project));
    }

    public function test_contact_form_creates_landing_lead(): void
    {
        $this->post(route('public.contact.store'), [
            'name' => 'Cliente Web',
            'email' => 'cliente.web@example.com',
            'phone' => '',
            'interest' => 'Vivienda',
            'city' => 'Popayán',
            'budget_range' => '100M - 200M',
            'message' => 'Quiero iniciar una vivienda familiar.',
        ])->assertRedirect()
            ->assertSessionHas('contact_status');

        $this->assertDatabaseHas(Lead::class, [
            'name' => 'Cliente Web',
            'email' => 'cliente.web@example.com',
            'source' => 'landing',
            'status' => 'new',
            'interest' => 'Vivienda',
        ]);
    }

    public function test_public_projects_only_show_published_projects(): void
    {
        $this->withoutVite();

        $publicProject = Project::factory()
            ->for(Client::factory())
            ->create([
                'name' => 'Proyecto visible',
                'public_slug' => 'proyecto-visible',
                'is_public' => true,
                'public_published_at' => now()->subMinute(),
            ]);

        $hiddenProject = Project::factory()
            ->for(Client::factory())
            ->create([
                'name' => 'Proyecto privado',
                'public_slug' => 'proyecto-privado',
                'is_public' => false,
                'public_published_at' => now()->subMinute(),
            ]);

        $this->get(route('public.projects.index'))
            ->assertOk()
            ->assertSee($publicProject->name)
            ->assertDontSee($hiddenProject->name);

        $this->get(route('public.projects.show', $publicProject))
            ->assertOk()
            ->assertSee($publicProject->name);

        $this->get(route('public.projects.show', $hiddenProject))
            ->assertNotFound();
    }
}
