@extends('public.layout', [
    'seoTitle' => $seoTitle,
    'seoDescription' => $seoDescription,
])

@section('content')
    <main class="bg-white">
        <section class="relative min-h-[72svh] overflow-hidden bg-neutral-950">
            <img
                src="{{ $project->publicCoverUrl() ?: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1800&q=85' }}"
                alt="{{ $project->name }}"
                class="absolute inset-0 h-full w-full object-cover"
            >
            <div class="absolute inset-0 bg-black/45"></div>
            <div class="relative z-10 mx-auto flex min-h-[72svh] max-w-7xl flex-col justify-end px-5 pb-14 pt-28">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">{{ $project->location ?: 'Proyecto FAB STUDIO' }}</p>
                <h1 class="mt-4 max-w-4xl text-5xl font-black leading-none text-white lg:text-7xl">{{ $project->name }}</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-white/85">{{ $project->public_summary ?: $project->description }}</p>
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl gap-10 px-5 py-14 lg:grid-cols-[0.7fr_1.3fr]">
            <aside class="space-y-5">
                <div>
                    <p class="text-sm font-bold text-neutral-500">Tipología</p>
                    <p class="mt-1 text-lg font-black">{{ $project->typology ?: 'No especificada' }}</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-neutral-500">Fase</p>
                    <p class="mt-1 text-lg font-black">{{ $project->current_phase ?: 'En desarrollo' }}</p>
                </div>
            </aside>

            <article class="prose prose-neutral max-w-none">
                <h2>Resumen del proyecto</h2>
                <p>{{ $project->description ?: $project->public_summary }}</p>
            </article>
        </section>
    </main>
@endsection
