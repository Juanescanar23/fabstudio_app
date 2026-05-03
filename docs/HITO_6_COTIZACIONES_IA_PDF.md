# Hito 6 - Cotizaciones, IA y PDF

Fecha: 2026-05-02

## Alcance implementado

El flujo de cotizaciones permite crear propuestas profesionales desde plantillas administrables, generar una version asistida, exigir revision humana, aprobarla, exportarla a PDF y notificar al cliente.

## Componentes

- `QuoteTemplate`: plantillas con secciones, servicios, valores, condiciones, instrucciones IA y metadatos.
- `QuoteWorkflowService`: orquesta versionado, calculo de totales, revision, aprobacion, exportacion y notificacion.
- `QuotePdfGenerator`: genera PDF con Dompdf usando vista Blade.
- `QuoteVersionExportedNotification`: correo transaccional al cliente cuando el PDF queda exportado.
- Filament:
  - `Plantillas de cotizacion`.
  - Accion `Crear version desde plantilla` en cotizaciones.
  - Acciones `Marcar revisada`, `Aprobar`, `Exportar PDF` y `Descargar PDF` en versiones.

## Politica IA

La primera version usa asistencia local deterministica bajo el identificador `fabstudio-assistant-local-v1`.

No se envia informacion del cliente a un proveedor externo. La asistencia toma datos minimos del proyecto, cliente y plantilla para preparar un borrador estructurado. La version queda en estado `En revision` y no puede exportarse a PDF hasta tener aprobacion humana.

## Estados

- `draft`: borrador.
- `review`: en revision.
- `reviewed`: revisada por el equipo.
- `approved`: aprobada por el equipo.
- `exported`: PDF generado y disponible.

Tambien se conservan estados comerciales previos como `sent`, `rejected` y `expired` para compatibilidad operativa.

## PDF y seguridad

Los PDFs se guardan en el disco configurado por la version, por defecto `local`, bajo `quotes/`.

La descarga administrativa esta protegida por autenticacion y rol `super_admin` o `admin`.

## Pruebas

Cobertura agregada:

- Flujo end-to-end desde plantilla hasta PDF exportado.
- Bloqueo de exportacion si no existe aprobacion humana.
- Descarga protegida de PDF para admin y bloqueada para cliente.

Resultado verificado: 50 tests, 147 assertions.

## Smoke test en produccion

Fecha: 2026-05-03

Se ejecuto el flujo real dentro del servicio Railway `fabstudio-app`:

- Se creo una plantilla QA activa porque produccion no tenia plantillas sembradas.
- Se creo cliente, proyecto y cotizacion QA.
- Se genero version desde plantilla.
- Se marco como revisada.
- Se aprobo.
- Se exporto PDF.
- Se verifico existencia fisica del archivo PDF.
- Se valido que la ruta de descarga sin autenticacion redirige a login y no expone el archivo.

Registro QA generado:

- Cotizacion: `QA-H6-20260503001732`.
- Version: `1`.
- Estado de version: `exported`.
- Estado de cotizacion: `exported`.
- PDF: `quotes/qa-h6-20260503001732-v1.pdf`.
- Healthcheck final: `https://app.fabstudio.com.co/up` respondio `HTTP 200`.
