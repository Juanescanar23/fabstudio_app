# Hito 7 - QA, Despliegue y Entrega

Fecha: 2026-05-03

## Objetivo

Cerrar una version estable, demostrable y operable de FAB STUDIO App en `https://app.fabstudio.com.co`, con manual operativo, checklist de QA, evidencias tecnicas y pendientes claros antes de capacitacion.

## Estado Actual

- Aplicacion desplegada en Railway.
- Dominio custom activo con SSL.
- Healthcheck final responde `HTTP 200`.
- Suite automatizada local pasa completa.
- Smoke test real de cotizaciones PDF ejecutado en produccion.
- Readiness tecnico ejecutado en produccion.
- Manual operativo creado.
- Guia de seguridad y cierre creada.

## Checklist QA

| Area | Validacion | Estado | Evidencia |
| --- | --- | --- | --- |
| Salud app | `/up` responde `HTTP 200` | Aprobado | `curl -I https://app.fabstudio.com.co/up` |
| Admin | `/admin` redirige a login seguro | Aprobado | `HTTP 302` hacia `/admin/login` |
| PDF privado | Descarga sin sesion redirige a login | Aprobado | `/admin/quote-versions/1/pdf` devuelve `HTTP 302` |
| Tests automatizados | Suite Laravel/Pest completa | Aprobado | 52 tests, 151 assertions |
| Permisos admin | Cliente bloqueado en Filament | Aprobado | `AdminPanelAccessTest` |
| Portal cliente | Aislamiento Cliente A / Cliente B | Aprobado | `ClientPortalTest` |
| Documentos | Descarga privada por proyecto/cliente | Aprobado | `ClientPortalTest` |
| Sitio publico | Home, formulario y proyectos publicos | Aprobado | `PublicSiteTest` |
| Cotizaciones | Flujo plantilla, revision, aprobacion y PDF | Aprobado | `QuoteWorkflowTest` y smoke test produccion |
| Espanol | Textos principales localizados | Aprobado parcial | Suite + revision visual previa |
| Readiness tecnico | Comando `app:readiness-check` | Aprobado | Verificacion local y Railway produccion |

## Smoke Tests De Produccion

Verificacion ejecutada: 2026-05-03 00:47 America/Bogota.

### Dominio

```text
GET https://app.fabstudio.com.co/up -> HTTP 200
```

### Admin

```text
GET https://app.fabstudio.com.co/admin sin sesion -> HTTP 302 hacia /admin/login
```

### Seguridad PDF

```text
GET https://app.fabstudio.com.co/admin/quote-versions/1/pdf sin sesion -> HTTP 302 hacia login
```

### Cotizacion PDF

Registro generado:

- Cotizacion: `QA-H6-20260503001732`.
- Version: `1`.
- Estado de version: `exported`.
- Estado de cotizacion: `exported`.
- PDF: `quotes/qa-h6-20260503001732-v1.pdf`.

## Criterios De Aceptacion

- El equipo FAB STUDIO puede ingresar al panel.
- El equipo puede crear cliente, proyecto, documentos y assets.
- El equipo puede crear cotizaciones desde plantilla y exportar PDF.
- El cliente solo ve informacion de su propia cuenta.
- La app opera sobre `app.fabstudio.com.co` con SSL valido.
- El manual operativo esta disponible en el repositorio.

## Verificacion Automatizada Local

Ultima verificacion ejecutada:

```text
composer test
Pint: passed
Pest: 52 tests, 151 assertions
```

Comando de readiness agregado:

```text
php artisan app:readiness-check
php artisan app:readiness-check --strict
```

Ultimo resultado local:

```text
Readiness check aprobado con advertencias.
Advertencias esperadas: correo transaccional en log y sin plantillas comerciales activas en SQLite local.
```

Ultimo resultado en produccion:

```text
php artisan app:readiness-check
Readiness check aprobado con advertencias.
Advertencia: correo transaccional en log.
Plantillas activas: 1.
```

## Pendientes Antes De Cierre Formal

Estos puntos no bloquean la app desplegada, pero si deben resolverse antes de entregar produccion como cerrada:

- Regenerar tokens expuestos durante configuracion: Hostinger y Railway.
- Cambiar password inicial del admin.
- Configurar proveedor de correo real: SMTP, Resend, Postmark o equivalente.
- Definir backup automatico de MySQL Railway.
- Decidir si se eliminan o conservan datos QA.
- Crear plantillas comerciales reales de FAB STUDIO.
- Realizar capacitacion con el usuario final.

## Plan De Capacitacion

Duracion sugerida: 60 a 90 minutos.

Agenda:

1. Acceso y roles.
2. Clientes y proyectos.
3. Documentos y versiones.
4. Assets visuales y portal cliente.
5. Cotizaciones, revision, aprobacion y PDF.
6. CMS y sitio publico.
7. Buenas practicas de seguridad.
8. Preguntas y ajustes de operacion.

## Decision De Entrega

La aplicacion esta lista para QA funcional guiado con el cliente. El cierre formal debe esperar la rotacion de secretos, definicion de correo transaccional real y capacitacion.

Documento de soporte: `docs/SEGURIDAD_CIERRE_PRODUCCION.md`.
