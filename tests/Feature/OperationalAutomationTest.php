<?php

namespace Tests\Feature;

use App\Models\AutomationLog;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\Quote;
use App\Models\User;
use App\Models\VisualAsset;
use App\Notifications\OperationalAutomationNotification;
use App\Services\Automation\OperationalAutomationService;
use Database\Seeders\RolesAndAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OperationalAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_operational_automations_notify_recipients_and_do_not_duplicate_logs(): void
    {
        Notification::fake();
        $this->seed(RolesAndAdminSeeder::class);

        $admin = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))
            ->firstOrFail();

        $client = Client::factory()->create([
            'email' => 'cliente.principal@example.com',
        ]);

        $clientUser = User::factory()
            ->for($client)
            ->create(['email' => 'cliente.portal@example.com']);
        $clientUser->assignRole('client');

        $project = Project::factory()
            ->for($client)
            ->create(['name' => 'Casa Automatizada']);

        Lead::factory()->create([
            'status' => 'new',
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        Milestone::factory()
            ->for($project)
            ->create([
                'title' => 'Entrega conceptual',
                'status' => 'pending',
                'due_at' => now()->addDay()->toDateString(),
            ]);

        ProjectDocument::factory()
            ->for($project)
            ->create([
                'title' => 'Plano publicado',
                'visibility' => 'client',
                'status' => 'published',
            ]);

        VisualAsset::factory()
            ->for($project)
            ->create([
                'title' => 'Render publicado',
                'visibility' => 'client',
                'status' => 'published',
            ]);

        Quote::factory()
            ->for($project)
            ->for($client)
            ->create([
                'quote_number' => 'COT-AUTO-001',
                'status' => 'approved',
                'valid_until' => now()->addDays(2)->toDateString(),
            ]);

        $summary = app(OperationalAutomationService::class)->run();

        $this->assertSame(5, $summary['evaluated']);
        $this->assertSame(5, $summary['created']);
        $this->assertSame(5, $summary['notified']);
        $this->assertSame(0, $summary['skipped']);

        $this->assertDatabaseHas('automation_logs', ['automation_key' => 'lead.follow_up']);
        $this->assertDatabaseHas('automation_logs', ['automation_key' => 'milestone.due_soon']);
        $this->assertDatabaseHas('automation_logs', ['automation_key' => 'document.published']);
        $this->assertDatabaseHas('automation_logs', ['automation_key' => 'visual_asset.published']);
        $this->assertDatabaseHas('automation_logs', ['automation_key' => 'quote.validity_expiring']);

        Notification::assertSentTo($admin, OperationalAutomationNotification::class);
        Notification::assertSentTo($clientUser, OperationalAutomationNotification::class);
        Notification::assertSentOnDemand(
            OperationalAutomationNotification::class,
            fn ($notification, array $channels, $notifiable): bool => $notifiable->routes['mail'] === 'cliente.principal@example.com',
        );

        $secondRun = app(OperationalAutomationService::class)->run();

        $this->assertSame(5, $secondRun['evaluated']);
        $this->assertSame(0, $secondRun['created']);
        $this->assertSame(0, $secondRun['notified']);
        $this->assertSame(5, $secondRun['skipped']);
        $this->assertSame(5, AutomationLog::query()->count());
    }

    public function test_automation_command_dry_run_does_not_persist_logs(): void
    {
        Lead::factory()->create([
            'status' => 'new',
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        $this->artisan('automations:run --dry-run')
            ->expectsOutputToContain('Automatizaciones operativas ejecutadas.')
            ->assertSuccessful();

        $this->assertDatabaseCount('automation_logs', 0);
    }
}
