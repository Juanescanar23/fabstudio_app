<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Lead;
use App\Models\MediaItem;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        $page = CmsPage::query()
            ->published()
            ->where('slug', 'inicio')
            ->first();

        $settings = SiteSetting::query()
            ->public()
            ->pluck('value', 'key');

        $heroMedia = MediaItem::query()
            ->published()
            ->where('collection', 'hero')
            ->orderBy('sort_order')
            ->first();

        $gallery = MediaItem::query()
            ->published()
            ->where('collection', 'galeria')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $featuredProjects = Project::query()
            ->public()
            ->where('is_featured', true)
            ->latest('public_published_at')
            ->limit(3)
            ->get();

        return view('public.home', [
            'page' => $page,
            'settings' => $settings,
            'heroMedia' => $heroMedia,
            'gallery' => $gallery,
            'featuredProjects' => $featuredProjects,
            'seoTitle' => $page?->seo_title ?: $settings->get('site.meta_title', 'FAB STUDIO'),
            'seoDescription' => $page?->seo_description ?: $settings->get('site.meta_description'),
        ]);
    }

    public function projects(): View
    {
        $projects = Project::query()
            ->public()
            ->latest('public_published_at')
            ->get();

        return view('public.projects.index', [
            'projects' => $projects,
            'seoTitle' => 'Proyectos - FAB STUDIO',
            'seoDescription' => 'Proyectos destacados de FAB STUDIO.',
        ]);
    }

    public function project(Project $project): View
    {
        abort_unless($project->is_public && $project->public_published_at?->isPast(), 404);

        return view('public.projects.show', [
            'project' => $project,
            'seoTitle' => $project->seo_title ?: $project->name.' - FAB STUDIO',
            'seoDescription' => $project->seo_description ?: $project->public_summary,
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:80', 'required_without:email'],
            'interest' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'budget_range' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:2500'],
        ], attributes: [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'interest' => 'tipo de proyecto',
            'city' => 'ciudad',
            'budget_range' => 'rango de inversión',
            'message' => 'mensaje',
        ]);

        Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'source' => 'landing',
            'status' => 'new',
            'interest' => $validated['interest'],
            'message' => $validated['message'],
            'metadata' => [
                'city' => $validated['city'] ?? null,
                'budget_range' => $validated['budget_range'] ?? null,
                'submitted_from' => 'public_landing',
                'submitted_at' => now()->toISOString(),
            ],
        ]);

        return back()->with('contact_status', 'Recibimos tu solicitud. El equipo de FAB STUDIO revisará la información y te contactará pronto.');
    }
}
