# Backups Y Restauracion

Fecha: 2026-05-03

## Objetivo

Definir el procedimiento profesional para proteger la base de datos MySQL de FAB STUDIO App y dejar claro que evidencia falta para declarar cierre formal.

## Estado Actual

- Proveedor esperado: Railway MySQL.
- Volumen Railway detectado: `mysql-volume`.
- Volume ID: `b338174a-5c7d-48b0-bed9-b8fd67b89092`.
- Servicio asociado: `MySQL`.
- Mount: `/var/lib/mysql`.
- Politica declarada en variables: `FABSTUDIO_BACKUP_PROVIDER=railway-mysql`.
- Retencion minima documentada: `7` dias.

## Intentos Tecnicos

Se intento validar y activar backups desde Railway CLI/API. El token actual permite listar volumenes y servicios, pero Railway rechazo operaciones de backup con `Not Authorized`.

Operaciones investigadas en GraphQL Railway:

- `volumeInstanceBackupCreate`
- `volumeInstanceBackupRestore`
- `volumeInstanceBackupScheduleUpdate`
- Enum de frecuencia: `DAILY`, `WEEKLY`, `MONTHLY`

Resultado: la configuracion de schedule/restore requiere permisos de cuenta o accion desde dashboard Railway con un usuario autorizado.

## Politica Minima

- Frecuencia: diaria.
- Retencion: minimo 7 dias.
- Responsable: `operaciones@fabstudio.com.co`.
- Prueba de restauracion: mensual o antes de entregar a cliente.
- Restauracion siempre en entorno temporal, nunca directo sobre produccion sin ventana aprobada.

## Activacion En Railway

1. Entrar a Railway con usuario admin del proyecto.
2. Abrir proyecto `FABstudio_App`.
3. Abrir servicio `MySQL`.
4. Ir a la seccion de volumen/backups.
5. Activar backups automaticos diarios.
6. Confirmar retencion minima de 7 dias.
7. Crear un backup manual inicial si Railway lo permite.
8. Registrar fecha, hora y responsable en `docs/LOG_EJECUCION.md`.

## Prueba De Restauracion

1. Crear un entorno temporal o servicio MySQL temporal.
2. Restaurar el backup mas reciente en ese entorno.
3. Conectar una instancia temporal de la app o validar tablas con cliente SQL.
4. Verificar tablas criticas:
   - `users`
   - `clients`
   - `projects`
   - `quotes`
   - `quote_versions`
   - `project_documents`
   - `document_versions`
   - `automation_logs`
5. Ejecutar `php artisan app:readiness-check` contra ese entorno.
6. Documentar resultado y eliminar el entorno temporal.

## Estado De Cierre

Backups no pueden marcarse como cerrados al 100% hasta que Railway confirme el schedule o exista evidencia de backup manual/restauracion. La app ya documenta la politica, pero falta la prueba operacional con permisos de proveedor.
