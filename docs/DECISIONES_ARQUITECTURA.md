# FAB STUDIO App - Decisiones de Arquitectura

Fecha base: 2026-05-02

## Decision 1 - Tipo de aplicacion

Construir un monolito Laravel full-stack. La aplicacion privada vive separada de la landing publica por dominio/rutas, pero comparte el mismo nucleo operativo.

Motivo: el alcance requiere velocidad, trazabilidad, permisos fuertes, panel administrativo y portal cliente sin sobredimensionar el frontend.

## Decision 2 - Stack inicial

- Backend: Laravel.
- Admin: Filament.
- Portal: Blade, Livewire y Alpine.js.
- Datos: MySQL.
- Colas/cache: Redis cuando el entorno este disponible.
- Archivos: storage privado, descarga autorizada por policies.
- Despliegue: Railway con servicios separados para app, worker, cron y base de datos.

## Decision 3 - Versiones objetivo

Si no hay bloqueo de compatibilidad, iniciar con Laravel 13.x, PHP 8.4 y Filament 5.x.

Motivo: es un proyecto nuevo, sin deuda tecnica previa, y conviene arrancar en la linea estable actual del ecosistema.

## Decision 4 - Seguridad de datos

El modelo de seguridad se basa en minimo privilegio:

- Usuarios internos con roles.
- Usuarios cliente con visibilidad limitada a sus proyectos.
- Policies para clientes, proyectos, documentos, cotizaciones y assets visuales.
- Storage privado sin enlaces publicos permanentes.
- Auditoria para accesos, descargas, aprobaciones, cambios y exportaciones.

## Decision 5 - IA comercial

La IA no toma decisiones ni envia propuestas automaticamente. Solo genera borradores o sugerencias con datos minimos; el equipo revisa, edita y aprueba antes de exportar o notificar.

## Decision 6 - Orden de construccion

No se empieza por IA ni por la interfaz final. Primero se construye el nucleo:

usuarios, permisos, clientes, proyectos, documentos, trazabilidad y auditoria.

Sobre esa base se conectan portal, CMS, assets visuales, cotizador, PDF e IA.
