@extends('portal.layout', ['title' => 'Documentos - '.$project->name, 'project' => $project])

@section('content')
    <section class="space-y-8">
        <div>
            <p class="text-sm font-medium text-neutral-500">{{ $project->name }}</p>
            <h1 class="text-3xl font-semibold tracking-normal">Boveda documental</h1>
        </div>

        <div class="space-y-5">
            @forelse ($documents as $document)
                <article class="rounded-md border border-neutral-200 bg-white p-5">
                    <div class="grid gap-5 lg:grid-cols-[1fr_280px]">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-xl font-semibold">{{ $document->title }}</h2>
                                <span class="rounded-md bg-neutral-100 px-2 py-1 text-xs font-semibold uppercase text-neutral-700">{{ $document->category }}</span>
                            </div>
                            <p class="mt-2 text-sm text-neutral-600">{{ $document->description ?: 'Sin descripcion' }}</p>

                            <div class="mt-5 space-y-3">
                                @forelse ($document->versions as $version)
                                    <div class="flex flex-col gap-3 rounded-md border border-neutral-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="font-medium">{{ $version->original_name }}</p>
                                            <p class="text-sm text-neutral-600">Version {{ $version->version_number }} - {{ $version->created_at->format('Y-m-d') }}</p>
                                        </div>
                                        <a href="{{ route('portal.projects.documents.download', [$project, $version]) }}" class="w-fit rounded-md bg-neutral-900 px-3 py-2 text-sm font-semibold text-white hover:bg-neutral-700">
                                            Descargar
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-sm text-neutral-600">Sin versiones publicadas.</p>
                                @endforelse
                            </div>
                        </div>

                        <form method="POST" action="{{ route('portal.projects.decisions.store', $project) }}" class="rounded-md bg-neutral-50 p-4">
                            @csrf
                            <input type="hidden" name="target_type" value="document">
                            <input type="hidden" name="target_id" value="{{ $document->id }}">

                            <label class="block text-sm font-semibold text-neutral-800" for="decision-document-{{ $document->id }}">Decision</label>
                            <select id="decision-document-{{ $document->id }}" name="decision" required class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm">
                                <option value="approved">Aprobar</option>
                                <option value="changes_requested">Solicitar cambios</option>
                                <option value="rejected">Rechazar</option>
                            </select>

                            <label class="mt-4 block text-sm font-semibold text-neutral-800" for="body-document-{{ $document->id }}">Comentario</label>
                            <textarea id="body-document-{{ $document->id }}" name="body" rows="4" class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm"></textarea>

                            <button class="mt-4 w-full rounded-md bg-amber-500 px-3 py-2 text-sm font-semibold text-neutral-950 hover:bg-amber-400">
                                Registrar decision
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-md border border-neutral-200 bg-white p-8 text-sm text-neutral-600">
                    Sin documentos publicados para este proyecto.
                </div>
            @endforelse
        </div>
    </section>
@endsection
