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
- 2FA confirmado para usuarios administrativos.
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
2FA admin: todos los usuarios administrativos tienen 2FA confirmado.
Advertencia: correo transaccional en log.
```

## Seguridad

Estado ejecutado:

- Password admin final generada y aplicada en produccion.
- 2FA confirmado para `admin@fabstudio.com.co`.
- 2FA nativo de Filament activado en el panel admin.
- Cuenta QA cliente creada para validar portal responsive.
- Secretos guardados fuera del repositorio con permisos `600`.
- `SESSION_SECURE_COOKIE=true` confirmado en produccion.

Archivos privados locales con credenciales, no versionados:

```text
/Users/juanescanar/.codex/memories/fabstudio_admin_security_2026-05-03.json
/Users/juanescanar/.codex/memories/fabstudio_client_qa_credentials_2026-05-03.json
```

Pendientes que requieren accion en dashboard externo:

- Rotar token de Railway y revocar el anterior.
- Rotar token de Hostinger y revocar el anterior.

## Correo Transaccional

Estado actual: infraestructura lista, proveedor real pendiente.

No se configuro un mailer real porque no existen credenciales SMTP/Resend/Postmark/SES en el entorno. Mantener `MAIL_MAILER=log` evita enviar correos falsos o incompletos a clientes.

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

Estado: certificado visualmente con capturas Playwright.

Matriz minima:

| Vista | Mobile 390px | Tablet 768px | Desktop 1440px | Estado |
| --- | --- | --- | --- | --- |
| Login | Captura generada | Captura generada | Captura generada | Aprobado |
| Admin dashboard | Captura generada | Captura generada | Captura generada | Aprobado |
| Listados Filament | Captura generada | Captura generada | Captura generada | Aprobado |
| Portal cliente | Captura generada | Captura generada | Captura generada | Aprobado |
| Sitio publico | Captura generada | Captura generada | Captura generada | Aprobado |
| Desafio MFA admin | Captura generada | Captura generada | Captura generada | Aprobado |
| PDF cotizacion | No aplica | No aplica | Validado funcional | Aprobado tecnico |

Evidencia:

- `docs/QA_RESPONSIVE_HITO_9.md`
- `docs/evidencias/hito-9/responsive/`

Criterio de aceptacion:

- No debe existir texto solapado.
- Las tablas deben poder desplazarse o colapsar sin romper layout.
- Las acciones principales deben quedar accesibles sin zoom manual.
- Login y portal deben verse correctos en telefono.

## Capacitacion

Material preparado:

- `docs/CAPACITACION_FINAL.md`

Sesiones sugeridas:

1. Administracion interna: leads, clientes, proyectos, documentos, assets, cotizaciones.
2. Portal cliente: acceso, documentos, galeria, comentarios y aprobaciones.
3. Automatizaciones: que correos salen, donde se ven logs, que hacer ante fallos.
4. Operacion de produccion: usuarios, backups, seguridad y soporte.

## Criterio De Cierre

El cierre tecnico queda avanzado, pero el cierre formal con cliente requiere:

- `composer test` pasa.
- `app:readiness-check --strict` pasa en produccion.
- Correo real configurado y probado.
- Backups confirmados.
- Secretos rotados.
- QA responsive firmado.
- Cliente capacitado.
- Manual operativo actualizado con responsables.
