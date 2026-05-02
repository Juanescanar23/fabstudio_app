@extends('portal.layout', ['title' => 'Portal Cliente'])

@section('content')
    <section class="space-y-8">
        <div class="flex flex-col gap-2">
            <p class="text-sm font-medium text-neutral-500">{{ $client->name }}</p>
            <h1 class="text-3xl font-semibold tracking-normal">Portal cliente</h1>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-md border border-neutral-200 bg-white p-5">
                <p class="text-sm font-medium text-neutral-500">Proyectos</p>
                <p class="mt-2 text-3xl font-semibold">{{ $projects->count() }}</p>
            </div>
            <div class="rounded-md border border-neutral-200 bg-white p-5">
                <p class="text-sm font-medium text-neutral-500">Documentos</p>
                <p class="mt-2 text-3xl font-semibold">{{ $projects->sum('client_documents_count') }}</p>
            </div>
            <div class="rounded-md border border-neutral-200 bg-white p-5">
                <p class="text-sm font-medium text-neutral-500">Hitos pendientes</p>
                <p class="mt-2 text-3xl font-semibold">{{ $projects->sum('pending_milestones_count') }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-md border border-neutral-200 bg-white">
            <div class="border-b border-neutral-200 px-5 py-4">
                <h2 class="text-base font-semibold">Proyectos</h2>
            </div>

            <div class="divide-y divide-neutral-200">
                @forelse ($projects as $project)
                    <a href="{{ route('portal.projects.show', $project) }}" class="grid gap-4 px-5 py-5 hover:bg-neutral-50 md:grid-cols-[1fr_auto]">
                        <div>
                            <p class="text-lg font-semibold">{{ $project->name }}</p>
                            <p class="mt-1 text-sm text-neutral-600">{{ $project->code }} - {{ $project->location ?: 'Ubicacion pendiente' }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-sm">
                            <span class="rounded-md bg-neutral-100 px-2 py-1 font-medium text-neutral-700">{{ $project->status }}</span>
                            <span class="rounded-md bg-amber-100 px-2 py-1 font-medium text-amber-900">{{ $project->current_phase ?: 'Fase pendiente' }}</span>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-10 text-sm text-neutral-600">
                        No hay proyectos activos asociados a esta cuenta.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
