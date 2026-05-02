<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\DocumentVersion;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\ProjectDocument;
use App\Models\ProjectPhase;
use App\Models\User;
use App\Models\VisualAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_view_own_project_portal(): void
    {
        $this->withoutVite();

        [$user, $client] = $this->clientUser();
        $project = Project::factory()->for($client)->create(['name' => 'Casa Cliente A']);
        $phase = ProjectPhase::factory()->for($project)->create(['name' => 'Anteproyecto']);
        Milestone::factory()->for($project)->create([
            'project_phase_id' => $phase->id,
            'title' => 'Entrega conceptual',
        ]);
        ProjectDocument::factory()->for($project)->create([
            'title' => 'Plano publicado',
            'visibility' => 'client',
            'status' => 'published',
        ]);
        VisualAsset::factory()->for($project)->create([
            'title' => 'Render publicado',
            'visibility' => 'client',
            'status' => 'published',
        ]);

        $this->actingAs($user)
            ->get(route('portal.projects.show', $project))
            ->assertOk()
            ->assertSee('Casa Cliente A')
            ->assertSee('Anteproyecto')
            ->assertSee('Plano publicado')
            ->assertSee('Render publicado');
    }

    public function test_client_cannot_view_another_client_project(): void
    {
        $this->withoutVite();

        [$user] = $this->clientUser();
        $otherProject = Project::factory()->for(Client::factory())->create();

        $this->actingAs($user)
            ->get(route('portal.projects.show', $otherProject))
            ->assertForbidden();
    }

    public function test_portal_only_lists_client_visible_deliverables(): void
    {
        $this->withoutVite();

        [$user, $client] = $this->clientUser();
        $project = Project::factory()->for($client)->create();

        ProjectDocument::factory()->for($project)->create([
            'title' => 'Documento visible',
            'visibility' => 'client',
            'status' => 'published',
        ]);
        ProjectDocument::factory()->for($project)->create([
            'title' => 'Documento interno',
            'visibility' => 'internal',
            'status' => 'published',
        ]);
        VisualAsset::factory()->for($project)->create([
            'title' => 'Render visible',
            'visibility' => 'client',
            'status' => 'published',
        ]);
        VisualAsset::factory()->for($project)->create([
            'title' => 'Render interno',
            'visibility' => 'internal',
            'status' => 'published',
        ]);

        $this->actingAs($user)
            ->get(route('portal.projects.documents', $project))
            ->assertOk()
            ->assertSee('Documento visible')
            ->assertDontSee('Documento interno');

        $this->actingAs($user)
            ->get(route('portal.projects.visuals', $project))
            ->assertOk()
            ->assertSee('Render visible')
            ->assertDontSee('Render interno');
    }

    public function test_client_can_comment_and_register_decision_on_visible_document(): void
    {
        [$user, $client] = $this->clientUser();
        $project = Project::factory()->for($client)->create();
        $document = ProjectDocument::factory()->for($project)->create([
            'visibility' => 'client',
            'status' => 'published',
        ]);

        $this->actingAs($user)
            ->post(route('portal.projects.comments.store', $project), [
                'target_type' => 'document',
                'target_id' => $document->id,
                'body' => 'Comentario del cliente.',
            ])
            ->assertRedirect();

        $this->actingAs($user)
            ->post(route('portal.projects.decisions.store', $project), [
                'target_type' => 'document',
                'target_id' => $document->id,
                'decision' => 'approved',
                'body' => 'Aprobado por cliente.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas(ProjectComment::class, [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'commentable_type' => $document->getMorphClass(),
            'commentable_id' => $document->id,
            'type' => 'comment',
            'visibility' => 'client',
            'body' => 'Comentario del cliente.',
        ]);

        $this->assertDatabaseHas(ProjectComment::class, [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'commentable_type' => $document->getMorphClass(),
            'commentable_id' => $document->id,
            'type' => 'approval',
            'decision' => 'approved',
        ]);
    }

    public function test_client_can_download_visible_document_version(): void
    {
        Storage::fake('local');

        [$user, $client] = $this->clientUser();
        $project = Project::factory()->for($client)->create();
        $document = ProjectDocument::factory()->for($project)->create([
            'visibility' => 'client',
            'status' => 'published',
        ]);
        $version = DocumentVersion::factory()->for($document, 'document')->create([
            'disk' => 'local',
            'file_path' => 'documents/test.pdf',
            'original_name' => 'test.pdf',
        ]);

        Storage::disk('local')->put('documents/test.pdf', 'PDF');

        $this->actingAs($user)
            ->get(route('portal.projects.documents.download', [$project, $version]))
            ->assertOk();
    }

    private function clientUser(): array
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::firstOrCreate(['name' => 'client']);

        $client = Client::factory()->create();
        $user = User::factory()->for($client)->create();
        $user->assignRole('client');

        return [$user, $client];
    }
}
