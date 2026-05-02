@extends('portal.layout', ['title' => 'Galeria - '.$project->name, 'project' => $project])

@section('content')
    <section class="space-y-8">
        <div>
            <p class="text-sm font-medium text-neutral-500">{{ $project->name }}</p>
            <h1 class="text-3xl font-semibold tracking-normal">Galeria visual</h1>
        </div>

        <div class="grid gap-5 xl:grid-cols-2">
            @forelse ($visualAssets as $asset)
                @php
                    $assetUrl = $asset->external_url ?: ($asset->file_path ? route('portal.projects.visuals.file', [$project, $asset]) : null);
                @endphp

                <article class="overflow-hidden rounded-md border border-neutral-200 bg-white">
                    <div class="aspect-video bg-neutral-100">
                        @if ($assetUrl && in_array($asset->type, ['image', 'render']))
                            <img src="{{ $assetUrl }}" alt="{{ $asset->title }}" class="h-full w-full object-cover">
                        @elseif ($assetUrl && $asset->type === 'model')
                            <model-viewer
                                src="{{ $assetUrl }}"
                                camera-controls
                                ar
                                shadow-intensity="1"
                                class="h-full w-full"
                            ></model-viewer>
                        @elseif ($assetUrl && $asset->type === 'video')
                            <video src="{{ $assetUrl }}" controls class="h-full w-full object-cover"></video>
                        @else
                            <div class="flex h-full items-center justify-center px-6 text-center text-sm font-medium text-neutral-500">
                                Preview pendiente
                            </div>
                        @endif
                    </div>

                    <div class="grid gap-5 p-5 lg:grid-cols-[1fr_260px]">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-xl font-semibold">{{ $asset->title }}</h2>
                                <span class="rounded-md bg-neutral-100 px-2 py-1 text-xs font-semibold uppercase text-neutral-700">{{ $asset->type }}</span>
                            </div>
                            <p class="mt-2 text-sm text-neutral-600">{{ $asset->mime_type ?: 'Formato pendiente' }}</p>

                            @if ($assetUrl)
                                <a href="{{ $assetUrl }}" class="mt-4 inline-block rounded-md border border-neutral-300 px-3 py-2 text-sm font-semibold hover:bg-neutral-100">
                                    Abrir archivo
                                </a>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('portal.projects.decisions.store', $project) }}" class="rounded-md bg-neutral-50 p-4">
                            @csrf
                            <input type="hidden" name="target_type" value="visual_asset">
                            <input type="hidden" name="target_id" value="{{ $asset->id }}">

                            <label class="block text-sm font-semibold text-neutral-800" for="decision-asset-{{ $asset->id }}">Decision</label>
                            <select id="decision-asset-{{ $asset->id }}" name="decision" required class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm">
                                <option value="approved">Aprobar</option>
                                <option value="changes_requested">Solicitar cambios</option>
                                <option value="rejected">Rechazar</option>
                            </select>

                            <label class="mt-4 block text-sm font-semibold text-neutral-800" for="body-asset-{{ $asset->id }}">Comentario</label>
                            <textarea id="body-asset-{{ $asset->id }}" name="body" rows="4" class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm"></textarea>

                            <button class="mt-4 w-full rounded-md bg-amber-500 px-3 py-2 text-sm font-semibold text-neutral-950 hover:bg-amber-400">
                                Registrar decision
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-md border border-neutral-200 bg-white p-8 text-sm text-neutral-600">
                    Sin assets visuales publicados para este proyecto.
                </div>
            @endforelse
        </div>
    </section>
@endsection
