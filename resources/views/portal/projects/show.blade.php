@extends('portal.layout', ['title' => $project->name, 'project' => $project])

@section('content')
    <section class="space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-sm font-medium text-neutral-500">{{ $project->code }}</p>
                <h1 class="text-3xl font-semibold tracking-normal">{{ $project->name }}</h1>
                <p class="mt-2 max-w-3xl text-neutral-600">{{ $project->description ?: $project->location }}</p>
            </div>
            <span class="w-fit rounded-md bg-neutral-900 px-3 py-2 text-sm font-medium text-white">{{ $project->status }}</span>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-md border border-neutral-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-neutral-500">Fase actual</p>
                <p class="mt-2 font-semibold">{{ $project->current_phase ?: 'Pendiente' }}</p>
            </div>
            <div class="rounded-md border border-neutral-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-neutral-500">Inicio</p>
                <p class="mt-2 font-semibold">{{ $project->starts_at?->format('Y-m-d') ?: '-' }}</p>
            </div>
            <div class="rounded-md border border-neutral-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-neutral-500">Cierre</p>
                <p class="mt-2 font-semibold">{{ $project->ends_at?->format('Y-m-d') ?: '-' }}</p>
            </div>
            <div class="rounded-md border border-neutral-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-neutral-500">Entregables</p>
                <p class="mt-2 font-semibold">{{ $project->documents->count() + $project->visualAssets->count() }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.4fr_0.8fr]">
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Timeline</h2>
                </div>

                <div class="space-y-4">
                    @forelse ($project->phases as $phase)
                        <div class="rounded-md border border-neutral-200 bg-white p-5">
                            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-lg font-semibold">{{ $phase->name }}</p>
                                    <p class="text-sm text-neutral-600">{{ $phase->starts_at?->format('Y-m-d') ?: '-' }} / {{ $phase->due_at?->format('Y-m-d') ?: '-' }}</p>
                                </div>
                                <span class="w-fit rounded-md bg-amber-100 px-2 py-1 text-sm font-medium text-amber-900">{{ $phase->status }}</span>
                            </div>

                            <div class="mt-5 space-y-3">
                                @forelse ($phase->milestones as $milestone)
                                    <div class="grid gap-2 border-l-2 border-neutral-300 pl-4 md:grid-cols-[1fr_auto]">
                                        <div>
                                            <p class="font-medium">{{ $milestone->title }}</p>
                                            <p class="text-sm text-neutral-600">{{ $milestone->description ?: 'Sin descripción' }}</p>
                                        </div>
                                        <span class="text-sm font-medium text-neutral-700">{{ $milestone->status }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-neutral-600">Sin hitos registrados.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="rounded-md border border-neutral-200 bg-white p-5 text-sm text-neutral-600">
                            Sin fases registradas.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="space-y-6">
                <div class="rounded-md border border-neutral-200 bg-white p-5">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold">Documentos</h2>
                        <a href="{{ route('portal.projects.documents', $project) }}" class="text-sm font-semibold text-neutral-900 underline">Ver</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($project->documents->take(3) as $document)
                            <div>
                                <p class="font-medium">{{ $document->title }}</p>
                                <p class="text-sm text-neutral-600">{{ $document->category }} / {{ $document->status }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-600">Sin documentos publicados.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-md border border-neutral-200 bg-white p-5">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold">Galería visual</h2>
                        <a href="{{ route('portal.projects.visuals', $project) }}" class="text-sm font-semibold text-neutral-900 underline">Ver</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($project->visualAssets->take(3) as $asset)
                            <div>
                                <p class="font-medium">{{ $asset->title }}</p>
                                <p class="text-sm text-neutral-600">{{ $asset->type }} / {{ $asset->status }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-600">Sin assets publicados.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <section class="rounded-md border border-neutral-200 bg-white p-5">
            <h2 class="text-xl font-semibold">Comentarios</h2>

            <form method="POST" action="{{ route('portal.projects.comments.store', $project) }}" class="mt-5 space-y-3">
                @csrf
                <input type="hidden" name="target_type" value="project">
                <textarea name="body" rows="4" required class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm focus:border-neutral-900 focus:outline-none" placeholder="Escribe un comentario"></textarea>
                <button class="rounded-md bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-700">
                    Enviar comentario
                </button>
            </form>

            <div class="mt-6 divide-y divide-neutral-200">
                @forelse ($project->comments as $comment)
                    <div class="py-4">
                        <div class="flex flex-wrap items-center gap-2 text-sm text-neutral-600">
                            <span class="font-semibold text-neutral-900">{{ $comment->user?->name ?: 'FAB STUDIO' }}</span>
                            <span>{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                            <span class="rounded-md bg-neutral-100 px-2 py-1">{{ $comment->type }}</span>
                            @if ($comment->decision)
                                <span class="rounded-md bg-amber-100 px-2 py-1 text-amber-900">{{ $comment->decision }}</span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-neutral-800">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="mt-5 text-sm text-neutral-600">Sin comentarios visibles.</p>
                @endforelse
            </div>
        </section>
    </section>
@endsection
