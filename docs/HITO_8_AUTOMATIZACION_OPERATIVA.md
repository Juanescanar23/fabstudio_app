# Hito 8 - Automatizacion Operativa

Fecha: 2026-05-03

## Objetivo

Elevar FAB STUDIO App de MVP funcional a sistema operativo con seguimiento automatico, trazabilidad de ejecucion, colas, cron y notificaciones transaccionales.

## Investigacion Tecnica Aplicada

- Laravel Scheduler permite definir tareas en codigo, evitar solapes con `withoutOverlapping` y limitar ejecucion a un servidor con `onOneServer`.
- Laravel Queues permite desacoplar trabajo de segundo plano con jobs `ShouldQueue`.
- Railway recomienda separar procesos persistentes, workers y cron jobs. Los cron jobs deben ejecutar una tarea corta y terminar; la frecuencia minima documentada es cada 5 minutos y los horarios se interpretan en UTC.

Referencias oficiales:

- https://laravel.com/docs/13.x/scheduling
- https://laravel.com/docs/13.x/queues
- https://docs.railway.com/reference/cron-jobs
- https://docs.railway.com/guides/cron-workers-queues

## Arquitectura Implementada

### Motor

- Servicio: `App\Services\Automation\OperationalAutomationService`.
- Job encolable: `App\Jobs\RunOperationalAutomationsJob`.
- Comando manual/cron: `php artisan automations:run`.
- Modo seco: `php artisan automations:run --dry-run`.
- Registro idempotente: tabla `automation_logs` con `deduplication_key` unico.

### Observabilidad

- Recurso Filament: `Automatizacion > Registro de automatizaciones`.
- Cada automatizacion registra:
  - clave de automatizacion;
  - severidad;
  - estado;
  - entidad relacionada;
  - destinatarios;
  - payload tecnico;
  - fecha de proceso y notificacion.

### Notificaciones

- Notificacion generica encolable: `OperationalAutomationNotification`.
- Cotizaciones exportadas ahora usan `ShouldQueue`.
- Canal actual: correo Laravel.
- Mientras `MAIL_MAILER=log`, las notificaciones quedan registradas pero no salen a clientes reales.

## Automatizaciones Activadas

| Automatizacion | Disparador | Destinatario | Severidad |
| --- | --- | --- | --- |
| Seguimiento de prospectos | Lead `new` sin contacto despues de 15 minutos | Admins | warning |
| Hitos por vencer | Hito pendiente con vencimiento en 3 dias | Admins | warning |
| Hitos vencidos | Hito pendiente con fecha vencida | Admins | critical |
| Documento publicado | Documento `published` con visibilidad `client` | Usuarios del cliente / email cliente | info |
| Asset visual publicado | Asset `published` con visibilidad `client` | Usuarios del cliente / email cliente | info |
| Cotizacion por vencer | Cotizacion revisada/aprobada/exportada que vence en 7 dias | Admins | warning |

## Railway

Servicios esperados:

- `fabstudio-app`: web.
- `fabstudio-worker`: `php artisan queue:work database`.
- `fabstudio-cron`: `php artisan automations:run`.

Cron recomendado para Railway:

```text
*/15 * * * *
```

Railway ejecuta cron en UTC. La automatizacion es idempotente, por lo que una ejecucion repetida no duplica correos ni logs.

Estado de produccion:

- `fabstudio-app`: online.
- `fabstudio-worker`: online, deployment `106b5030-1b3d-4605-bd80-5569ad914af1`.
- `fabstudio-cron`: online, deployment `05a0200e-5c5a-4c9f-9973-20903b3661f2`, schedule `*/15 * * * *`.
- `php artisan automations:run --dry-run` ejecutado en produccion desde `fabstudio-app`: 0 candidatos actuales.

## Variables

```text
FABSTUDIO_OPERATIONS_EMAIL=operaciones@fabstudio.com.co
FABSTUDIO_AUTOMATIONS_ENABLED=true
FABSTUDIO_LEAD_FOLLOWUP_MINUTES=15
FABSTUDIO_MILESTONE_DUE_SOON_DAYS=3
FABSTUDIO_QUOTE_VALIDITY_WARNING_DAYS=7
QUEUE_CONNECTION=database
```

## QA

Pruebas agregadas:

- Notifica prospectos, hitos, documentos, assets y cotizaciones.
- No duplica logs ni notificaciones en ejecuciones repetidas.
- Modo seco no persiste registros.

Resultado local:

```text
composer test
Pint: passed
Pest: 54 tests, 171 assertions

php artisan automations:run --dry-run
Candidatos evaluados: 2
Modo seco: si
```

Comandos:

```bash
php artisan automations:run --dry-run
php artisan automations:run
composer test
```

## Pendientes De Producto

- Definir tono final de emails con copy comercial de FAB STUDIO.
- Configurar proveedor real de correo.
- Agregar canales WhatsApp/SMS si se aprueba en fase posterior.
- Agregar preferencias de notificacion por usuario/cliente.
