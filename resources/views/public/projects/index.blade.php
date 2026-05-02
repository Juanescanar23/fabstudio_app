@extends('public.layout', [
    'seoTitle' => $seoTitle,
    'seoDescription' => $seoDescription,
])

@section('content')
    <main class="bg-neutral-100">
        <section class="bg-neutral-950 pt-28 text-white">
            <div class="mx-auto max-w-7xl px-5 pb-14">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Portafolio</p>
                <h1 class="mt-3 text-5xl font-black">Proyectos públicos</h1>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-5 py-12">
            <div class="grid gap-5 md:grid-cols-3">
                @forelse ($projects as $project)
                    <a href="{{ route('public.projects.show', $project) }}" class="group overflow-hidden rounded-md bg-white shadow-sm ring-1 ring-neutral-200">
                        <div class="aspect-[4/3] overflow-hidden bg-neutral-200">
                            <img
                                src="{{ $project->publicCoverUrl() ?: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=85' }}"
                                alt="{{ $project->name }}"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            >
                        </div>
                        <div class="p-5">
                            <p class="text-sm font-semibold text-amber-700">{{ $project->location ?: 'Ubicación reservada' }}</p>
                            <h2 class="mt-2 text-xl font-black">{{ $project->name }}</h2>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-neutral-600">{{ $project->public_summary ?: $project->description }}</p>
                        </div>
                    </a>
                @empty
                    <div class="rounded-md bg-white p-8 text-neutral-600 ring-1 ring-neutral-200 md:col-span-3">
                        Aún no hay proyectos públicos publicados.
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
