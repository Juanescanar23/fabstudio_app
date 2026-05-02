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
- `fabstudio-cron`: scheduler Laravel.
- `MySQL`: base de datos gestionada Railway.

## Configuracion por servicio

El repo incluye archivos separados para evitar mezclar procesos:

- `railway/app.json`: servicio web, build frontend, migraciones y seed base antes del deploy.
- `railway/worker.json`: worker con `php artisan queue:work database`.
- `railway/cron.json`: scheduler con `php artisan schedule:work`.

En Railway, cada servicio debe apuntar al archivo correspondiente en la opcion de config file path.

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
7. Desplegar primero `fabstudio-app`; las migraciones y roles/admin base corren en `railway/init-app.sh`.
8. Validar `/up` y acceso inicial al panel `/admin`.
9. Agregar dominio personalizado `app.fabstudio.com.co` en Railway.
10. Railway entregara los registros DNS requeridos; aplicarlos en Hostinger API sin tocar correo ni landing.

## DNS esperado

No modificar estos registros actuales sin validacion adicional:

- `@` ALIAS hacia CDN de Hostinger.
- `www` CNAME hacia CDN de Hostinger.
- `MX`, `TXT`, DKIM, DMARC, `autodiscover` y `autoconfig` de correo Hostinger.

Cambio esperado cuando Railway entregue target:

```text
app CNAME <target-de-railway>
```

Railway puede solicitar un registro TXT de verificacion. Si lo entrega, tambien debe agregarse en Hostinger.

## Estado actual

- GitHub listo y sincronizado.
- Hostinger API validado en modo lectura.
- `app.fabstudio.com.co` aun no existe en DNS.
- Railway CLI no estaba instalado en el equipo al momento de esta preparacion.
- Falta autenticacion Railway por API token o CLI login para crear/vincular servicios desde terminal.
