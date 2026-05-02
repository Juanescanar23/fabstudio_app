@extends('public.layout', [
    'seoTitle' => $seoTitle,
    'seoDescription' => $seoDescription,
])

@php
    $content = $page?->content ?? [];
    $heroImage = $heroMedia?->publicUrl() ?: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1800&q=85';
@endphp

@section('content')
    <main>
        <section class="relative min-h-[86svh] overflow-hidden bg-neutral-950">
            <img
                src="{{ $heroImage }}"
                alt="{{ $heroMedia?->alt_text ?: 'Proyecto arquitectónico contemporáneo' }}"
                class="absolute inset-0 h-full w-full object-cover"
            >
            <div class="absolute inset-0 bg-black/45"></div>
            <div class="relative z-10 mx-auto flex min-h-[86svh] max-w-7xl flex-col justify-end px-5 pb-16 pt-28">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">
                    {{ $page?->eyebrow ?: $settings->get('site.hero_eyebrow', 'Arquitectura + gestión visual') }}
                </p>
                <h1 class="mt-5 max-w-4xl text-5xl font-black leading-[0.98] text-white sm:text-6xl lg:text-7xl">
                    {{ $page?->title ?: $settings->get('site.hero_title', 'FAB STUDIO') }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-white/85">
                    {{ $page?->summary ?: $settings->get('site.hero_summary', 'Diseñamos, documentamos y acompañamos proyectos arquitectónicos con una experiencia clara para clientes y equipo interno.') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#contacto" class="inline-flex min-h-11 items-center justify-center rounded-md bg-amber-400 px-5 font-bold text-neutral-950 hover:bg-amber-300">
                        Solicitar propuesta
                    </a>
                    <a href="{{ route('public.projects.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-md border border-white/50 px-5 font-bold text-white hover:bg-white/10">
                        Ver proyectos
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-white py-14">
            <div class="mx-auto grid max-w-7xl gap-8 px-5 md:grid-cols-3">
                <div>
                    <p class="text-sm font-bold text-amber-700">01</p>
                    <h2 class="mt-2 text-2xl font-black">{{ data_get($content, 'service_1_title', 'Diseño arquitectónico') }}</h2>
                    <p class="mt-3 leading-7 text-neutral-600">{{ data_get($content, 'service_1_text', 'Conceptualización, anteproyecto y desarrollo espacial con criterios técnicos desde el inicio.') }}</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-amber-700">02</p>
                    <h2 class="mt-2 text-2xl font-black">{{ data_get($content, 'service_2_title', 'Documentación y seguimiento') }}</h2>
                    <p class="mt-3 leading-7 text-neutral-600">{{ data_get($content, 'service_2_text', 'Control de entregables, versiones, decisiones y aprobaciones para que cada avance quede trazado.') }}</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-amber-700">03</p>
                    <h2 class="mt-2 text-2xl font-black">{{ data_get($content, 'service_3_title', 'Visualización') }}</h2>
                    <p class="mt-3 leading-7 text-neutral-600">{{ data_get($content, 'service_3_text', 'Renders, recursos visuales y revisión privada para tomar decisiones con claridad.') }}</p>
                </div>
            </div>
        </section>

        @if ($featuredProjects->isNotEmpty())
            <section class="bg-neutral-100 py-16">
                <div class="mx-auto max-w-7xl px-5">
                    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-700">Portafolio</p>
                            <h2 class="mt-3 text-4xl font-black">Proyectos destacados</h2>
                        </div>
                        <a href="{{ route('public.projects.index') }}" class="font-bold text-neutral-950 underline decoration-amber-400 decoration-2 underline-offset-4">
                            Ver todos
                        </a>
                    </div>

                    <div class="mt-8 grid gap-5 md:grid-cols-3">
                        @foreach ($featuredProjects as $project)
                            <a href="{{ route('public.projects.show', $project) }}" class="group overflow-hidden rounded-md bg-white shadow-sm ring-1 ring-neutral-200">
                                <div class="aspect-[4/3] overflow-hidden bg-neutral-200">
                                    <img
                                        src="{{ $project->publicCoverUrl() ?: $heroImage }}"
                                        alt="{{ $project->name }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    >
                                </div>
                                <div class="p-5">
                                    <p class="text-sm font-semibold text-amber-700">{{ $project->location ?: 'Ubicación reservada' }}</p>
                                    <h3 class="mt-2 text-xl font-black">{{ $project->name }}</h3>
                                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-neutral-600">{{ $project->public_summary ?: $project->description }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($gallery->isNotEmpty())
            <section class="bg-white py-16">
                <div class="mx-auto max-w-7xl px-5">
                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-700">Galería</p>
                    <h2 class="mt-3 text-4xl font-black">Biblioteca visual</h2>
                    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($gallery as $item)
                            <figure class="overflow-hidden rounded-md bg-neutral-100">
                                <img
                                    src="{{ $item->publicUrl() }}"
                                    alt="{{ $item->alt_text ?: $item->title }}"
                                    class="aspect-[4/3] w-full object-cover"
                                >
                                <figcaption class="p-4 text-sm font-semibold text-neutral-700">{{ $item->caption ?: $item->title }}</figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section id="contacto" class="bg-neutral-950 py-16 text-white">
            <div class="mx-auto grid max-w-7xl gap-10 px-5 lg:grid-cols-[0.85fr_1.15fr]">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Contacto</p>
                    <h2 class="mt-3 text-4xl font-black">Cuéntanos sobre tu proyecto</h2>
                    <p class="mt-5 max-w-xl leading-8 text-white/75">
                        El formulario entra directo al tablero comercial como prospecto nuevo para que el equipo pueda dar seguimiento sin perder contexto.
                    </p>
                    <div class="mt-8 space-y-2 text-sm text-white/70">
                        @if ($settings->get('site.contact_email'))
                            <p>{{ $settings->get('site.contact_email') }}</p>
                        @endif
                        @if ($settings->get('site.contact_phone'))
                            <p>{{ $settings->get('site.contact_phone') }}</p>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('public.contact.store') }}" class="grid gap-4 rounded-md bg-white p-5 text-neutral-950 md:grid-cols-2">
                    @csrf

                    @if (session('contact_status'))
                        <div class="rounded-md bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 md:col-span-2">
                            {{ session('contact_status') }}
                        </div>
                    @endif

                    <label class="grid gap-2 text-sm font-bold">
                        Nombre
                        <input name="name" value="{{ old('name') }}" required class="min-h-11 rounded-md border border-neutral-300 px-3 font-normal" autocomplete="name">
                        @error('name') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-bold">
                        Correo electrónico
                        <input name="email" value="{{ old('email') }}" class="min-h-11 rounded-md border border-neutral-300 px-3 font-normal" autocomplete="email">
                        @error('email') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-bold">
                        Teléfono
                        <input name="phone" value="{{ old('phone') }}" class="min-h-11 rounded-md border border-neutral-300 px-3 font-normal" autocomplete="tel">
                        @error('phone') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-bold">
                        Tipo de proyecto
                        <select name="interest" required class="min-h-11 rounded-md border border-neutral-300 px-3 font-normal">
                            <option value="">Seleccionar</option>
                            <option @selected(old('interest') === 'Vivienda')>Vivienda</option>
                            <option @selected(old('interest') === 'Comercial')>Comercial</option>
                            <option @selected(old('interest') === 'Interiorismo')>Interiorismo</option>
                            <option @selected(old('interest') === 'Visualización')>Visualización</option>
                        </select>
                        @error('interest') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-bold">
                        Ciudad
                        <input name="city" value="{{ old('city') }}" class="min-h-11 rounded-md border border-neutral-300 px-3 font-normal">
                        @error('city') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-bold">
                        Rango de inversión
                        <input name="budget_range" value="{{ old('budget_range') }}" class="min-h-11 rounded-md border border-neutral-300 px-3 font-normal">
                        @error('budget_range') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-bold md:col-span-2">
                        Mensaje
                        <textarea name="message" required rows="5" class="rounded-md border border-neutral-300 px-3 py-3 font-normal">{{ old('message') }}</textarea>
                        @error('message') <span class="font-normal text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <div class="md:col-span-2">
                        <button class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-amber-400 px-5 font-black text-neutral-950 hover:bg-amber-300">
                            Enviar solicitud
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
