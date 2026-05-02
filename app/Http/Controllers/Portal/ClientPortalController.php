<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DocumentVersion;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\ProjectDocument;
use App\Models\User;
use App\Models\VisualAsset;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientPortalController extends Controller
{
    public function index(Request $request): View
    {
        $client = $this->clientFor($request->user());

        $projects = $client->projects()
            ->withCount([
                'documents as client_documents_count' => fn ($query) => $query->visibleToClients(),
                'visualAssets as client_visual_assets_count' => fn ($query) => $query->visibleToClients(),
                'milestones as pending_milestones_count' => fn ($query) => $query->whereNull('completed_at'),
            ])
            ->latest()
            ->get();

        return view('portal.dashboard', [
            'client' => $client,
            'projects' => $projects,
        ]);
    }

    public function showProject(Request $request, Project $project): View
    {
        $project = $this->projectFor($request->user(), $project)
            ->load([
                'phases.milestones',
                'documents' => fn ($query) => $query->visibleToClients()->latest(),
                'documents.versions' => fn ($query) => $query->where('is_current', true)->latest(),
                'visualAssets' => fn ($query) => $query->visibleToClients(),
                'comments' => fn ($query) => $query->where('visibility', 'client')->with('user')->latest(),
            ]);

        return view('portal.projects.show', [
            'project' => $project,
        ]);
    }

    public function documents(Request $request, Project $project): View
    {
        $project = $this->projectFor($request->user(), $project);

        $documents = $project->documents()
            ->visibleToClients()
            ->with(['versions' => fn ($query) => $query->latest('version_number')])
            ->latest()
            ->get();

        return view('portal.projects.documents', [
            'project' => $project,
            'documents' => $documents,
        ]);
    }

    public function visuals(Request $request, Project $project): View
    {
        $project = $this->projectFor($request->user(), $project);

        $visualAssets = $project->visualAssets()
            ->visibleToClients()
            ->latest()
            ->get();

        return view('portal.projects.visuals', [
            'project' => $project,
            'visualAssets' => $visualAssets,
        ]);
    }

    public function downloadDocument(Request $request, Project $project, DocumentVersion $version): StreamedResponse
    {
        $this->projectFor($request->user(), $project);

        $version->load('document');

        abort_unless($version->document?->project_id === $project->id, 404);
        abort_unless($this->documentIsVisibleToClient($version->document), 403);
        abort_unless(Storage::disk($version->disk)->exists($version->file_path), 404);

        return Storage::disk($version->disk)->download($version->file_path, $version->original_name);
    }

    public function visualAssetFile(Request $request, Project $project, VisualAsset $visualAsset): StreamedResponse
    {
        $this->projectFor($request->user(), $project);

        abort_unless($visualAsset->project_id === $project->id, 404);
        abort_unless($this->visualAssetIsVisibleToClient($visualAsset), 403);
        abort_if(blank($visualAsset->file_path), 404);
        abort_unless(Storage::disk('local')->exists($visualAsset->file_path), 404);

        return Storage::disk('local')->response($visualAsset->file_path);
    }

    public function storeComment(Request $request, Project $project): RedirectResponse
    {
        $this->projectFor($request->user(), $project);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'target_type' => ['nullable', 'string', 'in:project,document,visual_asset'],
            'target_id' => ['nullable', 'integer'],
        ]);

        $commentable = $this->resolveCommentable($project, $validated['target_type'] ?? 'project', $validated['target_id'] ?? null);

        ProjectComment::create([
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
            'commentable_type' => $commentable?->getMorphClass(),
            'commentable_id' => $commentable?->getKey(),
            'type' => 'comment',
            'visibility' => 'client',
            'body' => $validated['body'],
        ]);

        return back()->with('status', 'Comentario registrado.');
    }

    public function storeDecision(Request $request, Project $project): RedirectResponse
    {
        $this->projectFor($request->user(), $project);

        $validated = $request->validate([
            'decision' => ['required', 'string', 'in:approved,rejected,changes_requested'],
            'body' => ['nullable', 'string', 'max:2000'],
            'target_type' => ['required', 'string', 'in:document,visual_asset'],
            'target_id' => ['required', 'integer'],
        ]);

        $commentable = $this->resolveCommentable($project, $validated['target_type'], $validated['target_id']);

        ProjectComment::create([
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
            'commentable_type' => $commentable?->getMorphClass(),
            'commentable_id' => $commentable?->getKey(),
            'type' => 'approval',
            'visibility' => 'client',
            'body' => $validated['body'] ?: 'Decision registrada desde portal cliente.',
            'decision' => $validated['decision'],
            'decided_at' => now(),
        ]);

        return back()->with('status', 'Decision registrada.');
    }

    private function clientFor(User $user): Client
    {
        abort_unless($user->canAccessClientPortal(), 403);

        return $user->client()->firstOrFail();
    }

    private function projectFor(User $user, Project $project): Project
    {
        $this->clientFor($user);

        abort_unless($project->client_id === $user->client_id, 403);

        return $project;
    }

    private function resolveCommentable(Project $project, string $targetType, ?int $targetId): ?Model
    {
        if ($targetType === 'project') {
            return null;
        }

        abort_unless(filled($targetId), 422);

        if ($targetType === 'document') {
            $document = ProjectDocument::query()
                ->whereKey($targetId)
                ->where('project_id', $project->id)
                ->visibleToClients()
                ->firstOrFail();

            return $document;
        }

        $visualAsset = VisualAsset::query()
            ->whereKey($targetId)
            ->where('project_id', $project->id)
            ->visibleToClients()
            ->firstOrFail();

        return $visualAsset;
    }

    private function documentIsVisibleToClient(ProjectDocument $document): bool
    {
        return $document->visibility === 'client' && $document->status === 'published';
    }

    private function visualAssetIsVisibleToClient(VisualAsset $visualAsset): bool
    {
        return $visualAsset->visibility === 'client' && $visualAsset->status === 'published';
    }
}
