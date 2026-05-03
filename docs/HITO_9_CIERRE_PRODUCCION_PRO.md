# Hito 9 - Cierre Profesional de Produccion

Fecha: 2026-05-03

## Objetivo

Convertir el despliegue funcional en una entrega profesional: seguridad, operacion, backups, QA responsive, correo real, capacitacion y checklist de cierre.

## Alcance

Este hito no agrega pantallas "de relleno". Endurece lo que ya existe para que FAB STUDIO pueda operar con menos riesgo y con trazabilidad.

## Readiness Ampliado

El comando `php artisan app:readiness-check` valida ahora:

- APP_KEY configurada.
- APP_URL con HTTPS en produccion.
- APP_DEBUG apagado.
- Conexion a base de datos.
- Tablas criticas, incluyendo jobs, failed jobs, cache locks y automation logs.
- Usuario admin.
- Storage escribible.
- Cola no sincronica en produccion.
- Correo transaccional.
- Cookies seguras.
- Plantillas de cotizacion activas.
- Motor de automatizaciones.
- Politica declarada de backups.
- Password admin inicial no presente en produccion.

Resultado local actual:

```text
Readiness check aprobado con advertencias.
Advertencias locales: correo transaccional en log y sin plantillas activas en SQLite local.
```

Resultado de produccion:

```text
Readiness check aprobado con advertencias.
Colas: QUEUE_CONNECTION=database.
Sesion segura: cookies seguras activas.
Automatizaciones: motor activo y tabla de logs disponible.
Backups: proveedor/politica declarada railway-mysql.
Advertencia: correo transaccional en log.
```

## Seguridad

Pendientes obligatorios antes de declarar cierre formal:

- Rotar token de Railway.
- Rotar token de Hostinger.
- Cambiar password admin final.
- Activar 2FA en usuarios administrativos.
- Confirmar `SESSION_SECURE_COOKIE=true`.
- Mantener secretos fuera del repositorio.

## Correo Transaccional

Estado actual: infraestructura lista, proveedor real pendiente.

Recomendacion operativa:

1. Elegir proveedor: Resend, Postmark, Amazon SES o SMTP corporativo.
2. Validar dominio remitente con SPF, DKIM y DMARC.
3. Configurar variables `MAIL_*` en Railway.
4. Ejecutar `php artisan app:readiness-check --strict`.
5. Probar flujo de cotizacion y automatizaciones.

## Backups

Politica minima recomendada:

- Backup automatico diario de MySQL.
- Retencion minima: 7 dias.
- Responsable: correo operacional definido en `FABSTUDIO_BACKUP_OWNER_EMAIL`.
- Prueba mensual de restauracion en entorno temporal.
- Evidencia en `docs/LOG_EJECUCION.md`.

Variables:

```text
FABSTUDIO_BACKUP_PROVIDER=railway-mysql
FABSTUDIO_BACKUP_RETENTION_DAYS=7
FABSTUDIO_BACKUP_OWNER_EMAIL=operaciones@fabstudio.com.co
```

## QA Responsive

Estado: responsive funcional por base Filament/Tailwind, pendiente certificacion visual final.

Matriz minima:

| Vista | Mobile 390px | Tablet 768px | Desktop 1440px | Estado |
| --- | --- | --- | --- | --- |
| Login | Pendiente screenshot | Pendiente screenshot | Validado funcional | Pendiente visual |
| Admin dashboard | Pendiente screenshot | Pendiente screenshot | Validado funcional | Pendiente visual |
| Listados Filament | Pendiente screenshot | Pendiente screenshot | Validado funcional | Pendiente visual |
| Portal cliente | Pendiente screenshot | Pendiente screenshot | Validado por tests | Pendiente visual |
| Sitio publico | Pendiente screenshot | Pendiente screenshot | Validado por tests | Pendiente visual |
| PDF cotizacion | No aplica | No aplica | Validado funcional | Aprobado tecnico |

Criterio de aceptacion:

- No debe existir texto solapado.
- Las tablas deben poder desplazarse o colapsar sin romper layout.
- Las acciones principales deben quedar accesibles sin zoom manual.
- Login y portal deben verse correctos en telefono.

## Capacitacion

Sesiones sugeridas:

1. Administracion interna: leads, clientes, proyectos, documentos, assets, cotizaciones.
2. Portal cliente: acceso, documentos, galeria, comentarios y aprobaciones.
3. Automatizaciones: que correos salen, donde se ven logs, que hacer ante fallos.
4. Operacion de produccion: usuarios, backups, seguridad y soporte.

## Criterio De Cierre

El proyecto puede cerrarse formalmente cuando:

- `composer test` pasa.
- `app:readiness-check --strict` pasa en produccion.
- Correo real configurado y probado.
- Backups confirmados.
- Secretos rotados.
- QA responsive firmado.
- Cliente capacitado.
- Manual operativo actualizado con responsables.
