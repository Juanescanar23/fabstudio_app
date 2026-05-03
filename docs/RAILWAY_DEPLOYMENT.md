# FAB STUDIO App - Despliegue Railway

Fecha: 2026-05-02

## Objetivo

Publicar la aplicacion privada Laravel en Railway bajo `app.fabstudio.com.co`, manteniendo `fabstudio.com.co` y `www` en Hostinger para la landing publica actual.

Repositorio oficial:

```text
git@github.com:Juanescanar23/fabstudio_app.git
```

## Fuentes revisadas

- Guia oficial Laravel en Railway: https://docs.railway.com/guides/laravel
- Dominios personalizados Railway: https://docs.railway.com/networking/domains
- MySQL en Railway: https://docs.railway.com/databases/mysql

## Servicios Railway previstos

- `fabstudio-app`: servicio web Laravel.
- `fabstudio-worker`: worker de colas Laravel.
- `fabstudio-cron`: cron operativo Laravel.
- `MySQL`: base de datos gestionada Railway.

## Configuracion por servicio

El repo incluye archivos separados para evitar mezclar procesos:

- `railway.json`: configuracion principal usada por Railway CLI para el servicio web.
- `railway/app.json`: servicio web, build frontend, migraciones y seed base antes del deploy.
- `railway/worker.json`: worker con `php artisan queue:work database`.
- `railway/cron.json`: cron corto con `php artisan automations:run`.

En Railway, el servicio web puede usar `railway.json` directamente. Si se crean worker y cron desde el mismo repositorio via dashboard, cada servicio debe apuntar al archivo correspondiente en la opcion de config file path.

## Variables de entorno

Usar como base:

```text
railway/variables.production.example
```

Antes de desplegar, reemplazar:

- `APP_KEY`: generar con `php artisan key:generate --show`.
- `ADMIN_PASSWORD`: clave fuerte inicial del super admin.
- `HOSTINGER_API_TOKEN`: token activo de Hostinger si se van a operar DNS desde Railway.
- `DB_URL`: debe apuntar a `${{MySQL.MYSQL_URL}}` cuando el servicio MySQL exista.

Variables clave:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://app.fabstudio.com.co
RAILPACK_PHP_EXTENSIONS=intl,zip
RAILPACK_SKIP_MIGRATIONS=true
DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

## Flujo de despliegue

1. Crear proyecto Railway desde el repo `Juanescanar23/fabstudio_app`.
2. Crear servicio MySQL.
3. Configurar variables del servicio web con el archivo `railway/variables.production.example`.
4. En servicio web, usar config file path `railway/app.json`.
5. Crear servicio worker desde el mismo repo y usar `railway/worker.json`.
6. Crear servicio cron desde el mismo repo y usar `railway/cron.json`.
   - Cron Schedule recomendado: `*/15 * * * *`.
   - Railway evalua cron en UTC y espera que el proceso termine.
7. Desplegar primero `fabstudio-app`; las migraciones y roles/admin base corren en `railway/init-app.sh`.
8. Validar `/up` y acceso inicial al panel `/admin`.
9. Agregar dominio personalizado `app.fabstudio.com.co` en Railway.
10. Railway entregara los registros DNS requeridos; aplicarlos en Hostinger API sin tocar correo ni landing.

## DNS esperado

No modificar estos registros actuales sin validacion adicional:

- `@` ALIAS hacia CDN de Hostinger.
- `www` CNAME hacia CDN de Hostinger.
- `MX`, `TXT`, DKIM, DMARC, `autodiscover` y `autoconfig` de correo Hostinger.

Cambio aplicado para el subdominio privado:

```text
app CNAME eroh8bc4.up.railway.app.
_railway-verify.app TXT railway-verify=<token-de-verificacion-railway>
```

Railway puede solicitar un registro TXT de verificacion. Si lo entrega, tambien debe agregarse en Hostinger.

## Estado actual

- GitHub listo y sincronizado.
- Hostinger API validado en lectura, validacion y sincronizacion.
- `app.fabstudio.com.co` existe en DNS como `CNAME` hacia el target dedicado entregado por Railway.
- `_railway-verify.app.fabstudio.com.co` existe como `TXT` de verificacion Railway.
- Railway CLI instalado y autenticado.
- Proyecto Railway `FABstudio_App` vinculado a `production`.
- Servicio `fabstudio-app` desplegado con estado `SUCCESS`.
- Healthcheck temporal validado en `https://fabstudio-app-production.up.railway.app/up`.
- Dominio custom `https://app.fabstudio.com.co` creado en Railway y listado para `fabstudio-app`.
- Healthcheck final validado en `https://app.fabstudio.com.co/up` con `HTTP 200` y SSL valido.
