<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $seoTitle ?? 'FAB STUDIO' }}</title>
        @isset($seoDescription)
            <meta name="description" content="{{ $seoDescription }}">
        @endisset
        <link rel="canonical" href="{{ url()->current() }}">
        <meta property="og:title" content="{{ $seoTitle ?? 'FAB STUDIO' }}">
        @isset($seoDescription)
            <meta property="og:description" content="{{ $seoDescription }}">
        @endisset
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-neutral-50 text-neutral-950 antialiased">
        <header class="absolute inset-x-0 top-0 z-20">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5">
                <a href="{{ route('home') }}" class="text-lg font-black text-white drop-shadow-sm">
                    FAB STUDIO
                </a>

                <nav class="hidden items-center gap-6 text-sm font-semibold text-white/90 md:flex">
                    <a class="hover:text-white" href="{{ route('public.projects.index') }}">Proyectos</a>
                    <a class="hover:text-white" href="{{ route('home') }}#contacto">Contacto</a>
                    <a class="hover:text-white" href="{{ route('portal.dashboard') }}">Portal cliente</a>
                    <a class="rounded-md bg-white px-3 py-2 text-neutral-950 hover:bg-amber-300" href="{{ url('/admin') }}">Panel</a>
                </nav>
            </div>
        </header>

        @yield('content')

        <footer class="border-t border-neutral-200 bg-white">
            <div class="mx-auto grid max-w-7xl gap-6 px-5 py-8 text-sm text-neutral-600 md:grid-cols-[1fr_auto]">
                <div>
                    <p class="font-bold text-neutral-950">FAB STUDIO</p>
                    <p class="mt-2 max-w-xl">Arquitectura, diseño y gestión visual para proyectos con trazabilidad profesional.</p>
                </div>
                <div class="flex flex-wrap gap-4 md:justify-end">
                    <a href="{{ route('home') }}#contacto" class="font-semibold text-neutral-950">Contacto</a>
                    <a href="{{ route('portal.dashboard') }}" class="font-semibold text-neutral-950">Portal cliente</a>
                </div>
            </div>
        </footer>
    </body>
</html>
