# Seguridad Y Cierre De Produccion

Fecha: 2026-05-03

## Objetivo

Cerrar la entrega con una postura segura: secretos rotados, password admin definitivo, correo transaccional real, backups definidos y datos QA controlados.

## Secretos Expuestos Durante Configuracion

Durante la configuracion inicial se usaron tokens en chat/terminal. Deben considerarse expuestos aunque hayan permitido completar el despliegue.

Secretos a rotar:

- Token API Hostinger.
- Token API Railway.

Estado actualizado 2026-05-03:

- Password admin final aplicada en produccion.
- 2FA confirmado para el admin y exigido por el panel Filament.
- Credenciales finales guardadas fuera del repo:

```text
/Users/juanescanar/.codex/memories/fabstudio_admin_security_2026-05-03.json
```

## Rotacion Hostinger

1. Entrar al panel Hostinger.
2. Crear un nuevo token API con permisos necesarios para DNS.
3. Actualizar `HOSTINGER_API_TOKEN` en Railway, si se va a operar DNS desde produccion.
4. Actualizar `.env` local no versionado.
5. Validar lectura DNS:

```bash
php artisan hostinger:dns:list
```

6. Revocar el token anterior en Hostinger.

## Rotacion Railway

1. Entrar a Railway.
2. Crear nuevo token API con scope minimo necesario para proyecto.
3. Actualizar el entorno local del operador.
4. Validar:

```bash
railway whoami
railway service status --service fabstudio-app
```

5. Revocar el token anterior.

## Password Admin

La password final debe ser fuerte, unica y guardada en un gestor de contrasenas.

Opciones:

- Cambiar desde la pantalla de seguridad del usuario admin.
- Si se requiere operacion tecnica controlada, actualizar por consola con `Hash::make(...)` y registrar la accion en el log operativo.

Despues de cambiarla:

- Validar login en `/admin`.
- Confirmar que no se comparte por chat.

## Correo Transaccional

Actualmente la app puede generar notificaciones de cotizaciones y automatizaciones, pero antes de enviar emails reales a clientes se debe configurar un proveedor.

No marcar este punto como cerrado con `MAIL_MAILER=log`; ese modo solo registra correos en logs.

Opciones compatibles:

- SMTP.
- Resend.
- Postmark.
- SES.

Variables a configurar segun proveedor:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=sistema@fabstudio.com.co
MAIL_FROM_NAME="FAB STUDIO App"
```

Despues de configurar:

```bash
php artisan app:readiness-check --strict
```

## Backups

Railway MySQL debe tener una politica de backup antes de cierre formal.

Checklist:

- Confirmar backup automatico o snapshot recurrente en Railway.
- Definir frecuencia.
- Definir retencion.
- Documentar responsable.
- Probar al menos una restauracion en entorno no productivo cuando exista staging.

Detalle operativo:

- Ver `docs/BACKUPS_RESTAURACION.md`.
- El token actual permite listar el volumen `mysql-volume`, pero Railway rechazo activar schedule/restore por API con `Not Authorized`.
- La activacion final debe realizarse desde Railway dashboard o con token con permisos suficientes.

## Datos QA

Datos QA conocidos:

- Cliente: `Cliente QA Hito 6`.
- Cotizacion: `QA-H6-20260503001732`.
- Plantilla: `QA Hito 6 - Plantilla PDF`.

Decision pendiente:

- Conservar como evidencia tecnica interna.
- Archivar/eliminar antes de capacitacion con cliente.

No borrar datos QA sin backup o confirmacion explicita del responsable.

## Readiness Antes De Cierre

Ejecutar:

```bash
composer test
php artisan app:readiness-check --strict
curl -I https://app.fabstudio.com.co/up
```

Condicion para cierre formal:

- Tests verdes.
- Readiness estricto sin fallos.
- Dominio final en `HTTP 200`.
- Tokens rotados.
- Password admin final actualizada.
- 2FA admin exigido por panel.
- Correo real configurado o decision formal de mantenerlo desactivado.
- Backups definidos.
- Capacitacion ejecutada.
