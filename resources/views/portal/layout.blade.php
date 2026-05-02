<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? 'Portal cliente'])
    </head>
    <body class="min-h-screen bg-neutral-50 text-neutral-950 antialiased">
        <div class="min-h-screen">
            <header class="border-b border-neutral-200 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
                    <a href="{{ route('portal.dashboard') }}" class="text-xl font-semibold tracking-normal">
                        FAB STUDIO Portal
                    </a>

                    <div class="flex items-center gap-4 text-sm">
                        <span class="hidden text-neutral-600 sm:inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-md border border-neutral-300 px-3 py-2 font-medium hover:bg-neutral-100">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="mx-auto grid max-w-7xl gap-8 px-5 py-8 lg:grid-cols-[240px_1fr]">
                <aside class="space-y-2">
                    <a
                        href="{{ route('portal.dashboard') }}"
                        class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('portal.dashboard') ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100' }}"
                    >
                        Escritorio
                    </a>

                    @isset($project)
                        <div class="pt-5">
                            <p class="px-3 pb-2 text-xs font-semibold uppercase text-neutral-500">Proyecto</p>
                            <a
                                href="{{ route('portal.projects.show', $project) }}"
                                class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('portal.projects.show') ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100' }}"
                            >
                                Timeline
                            </a>
                            <a
                                href="{{ route('portal.projects.documents', $project) }}"
                                class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('portal.projects.documents') ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100' }}"
                            >
                                Documentos
                            </a>
                            <a
                                href="{{ route('portal.projects.visuals', $project) }}"
                                class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('portal.projects.visuals') ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100' }}"
                            >
                                Galería visual
                            </a>
                        </div>
                    @endisset
                </aside>

                <main class="min-w-0">
                    @if (session('status'))
                        <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
