<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\Quote;
use App\Models\QuoteTemplate;
use App\Models\QuoteVersion;
use App\Models\User;
use App\Notifications\QuoteVersionExportedNotification;
use App\Services\Quotes\QuoteWorkflowService;
use Database\Seeders\RolesAndAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class QuoteWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_quote_workflow_generates_reviewed_approved_and_exported_pdf(): void
    {
        Notification::fake();
        Storage::fake('local');
        $this->seed(RolesAndAdminSeeder::class);

        $admin = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))
            ->firstOrFail();

        $client = Client::factory()->create([
            'email' => 'cliente@example.com',
            'name' => 'Cliente Prueba',
        ]);

        $project = Project::factory()
            ->for($client)
            ->create([
                'name' => 'Casa Patio',
                'location' => 'Popayán, Cauca',
                'typology' => 'residencial',
            ]);

        $quote = Quote::factory()
            ->for($project)
            ->for($client)
            ->create([
                'created_by_id' => $admin->id,
                'quote_number' => 'COT-9001',
                'title' => 'Propuesta Casa Patio',
                'status' => 'draft',
                'subtotal' => 0,
                'tax' => 0,
                'discount' => 0,
                'total' => 0,
            ]);

        $template = QuoteTemplate::factory()
            ->for($admin, 'createdBy')
            ->create([
                'name' => 'Diseño inicial test',
                'sections' => [
                    [
                        'heading' => 'Alcance',
                        'body' => 'Propuesta para {{project_name}} en {{project_location}}.',
                        'sort_order' => 1,
                    ],
                ],
                'line_items' => [
                    [
                        'name' => 'Diseño conceptual',
                        'description' => 'Etapa inicial',
                        'quantity' => 1,
                        'unit_price' => 10000000,
                    ],
                    [
                        'name' => 'Documentación',
                        'description' => 'Entregables base',
                        'quantity' => 2,
                        'unit_price' => 3000000,
                    ],
                ],
            ]);

        $service = app(QuoteWorkflowService::class);

        $version = $service->createVersionFromTemplate($quote, $template, $admin);

        $this->assertSame('review', $version->status);
        $this->assertSame(1, $version->version_number);
        $this->assertSame($template->id, $version->quote_template_id);
        $this->assertSame('fabstudio-assistant-local-v1', $version->ai_model);
        $this->assertTrue($version->content['summary']['human_review_required']);
        $this->assertStringContainsString('Casa Patio', $version->content['sections'][0]['body']);
        $this->assertEquals(16000000, (float) $version->total);
        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'status' => 'review',
            'total' => 16000000,
        ]);

        $version = $service->markReviewed($version, $admin);

        $this->assertSame('reviewed', $version->status);
        $this->assertSame($admin->id, $version->reviewed_by_id);
        $this->assertNotNull($version->reviewed_at);

        $version = $service->approve($version, $admin);

        $this->assertSame('approved', $version->status);
        $this->assertSame($admin->id, $version->approved_by_id);
        $this->assertNotNull($version->approved_at);

        $version = $service->exportPdf($version);

        $this->assertSame('exported', $version->status);
        $this->assertNotNull($version->exported_at);
        $this->assertNotNull($version->pdf_path);
        Storage::disk('local')->assertExists($version->pdf_path);

        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'status' => 'exported',
        ]);

        Notification::assertSentOnDemand(
            QuoteVersionExportedNotification::class,
            fn ($notification, array $channels, $notifiable): bool => in_array('mail', $channels, true)
                && $notifiable->routes['mail'] === 'cliente@example.com',
        );
    }

    public function test_pdf_export_requires_human_approval(): void
    {
        Storage::fake('local');

        $version = QuoteVersion::factory()->create([
            'status' => 'review',
            'approved_at' => null,
        ]);

        $this->expectException(ValidationException::class);

        app(QuoteWorkflowService::class)->exportPdf($version);
    }

    public function test_quote_pdf_download_requires_admin_access(): void
    {
        Storage::fake('local');
        $this->seed(RolesAndAdminSeeder::class);

        $admin = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))
            ->firstOrFail();

        $clientUser = User::factory()->create();
        $clientUser->assignRole('client');

        $quote = Quote::factory()->create(['quote_number' => 'COT-9002']);
        $version = QuoteVersion::factory()
            ->for($quote)
            ->create([
                'version_number' => 2,
                'status' => 'exported',
                'pdf_disk' => 'local',
                'pdf_path' => 'quotes/cot-9002-v2.pdf',
            ]);

        Storage::disk('local')->put($version->pdf_path, '%PDF-1.4 test');

        $this->actingAs($clientUser)
            ->get(route('admin.quote-versions.pdf', $version))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.quote-versions.pdf', $version))
            ->assertOk()
            ->assertDownload('COT-9002-v2.pdf');
    }
}
