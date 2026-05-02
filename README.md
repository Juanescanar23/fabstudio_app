# FAB STUDIO App

Sistema privado a la medida para FAB STUDIO: gestion de leads, clientes, proyectos, documentos, renders, portal cliente, CMS, cotizaciones asistidas por IA, PDF, auditoria y despliegue.

## Estado

Proyecto en cimentacion. La documentacion base esta en `DOCUMENTACION/` y el seguimiento de ejecucion esta en `docs/`.

La base Laravel + Livewire ya fue creada en la raiz del repositorio.

## Roadmap Operativo

1. Alineacion y base de proyecto.
2. Cimentacion tecnica.
3. Operacion interna.
4. Portal cliente y comunicacion visual.
5. Landing-App, CMS y marca.
6. Cotizaciones, IA y PDF.
7. QA, despliegue y entrega.

## Stack Objetivo

- Laravel 13.x
- PHP 8.4
- Filament 5.x
- Blade, Livewire, Alpine.js
- MySQL
- Redis / queues / cron
- Railway
- OpenAI o Anthropic API para asistencia comercial revisada por humanos

## Arranque Local

Requisitos instalados en este equipo:

- PHP 8.4.1
- Composer 2.8.12
- Laravel Installer 5.26.1
- Node 24.15.0
- npm 11.12.1

Comandos base:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
composer run dev
```

Nota: el MVP usa MySQL. La base local esperada es `fabstudio_app`.

Para poblar datos de demostracion:

```bash
php artisan db:seed --class=DemoDataSeeder
```

## Documentos de Trabajo

- `docs/CHECKLIST_EJECUCION.md`
- `docs/DECISIONES_ARQUITECTURA.md`
- `docs/LOG_EJECUCION.md`
