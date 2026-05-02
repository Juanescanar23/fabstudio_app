# FAB STUDIO App - Integracion Hostinger DNS

Fecha: 2026-05-02

## Objetivo

Administrar la zona DNS de `fabstudio.com.co` desde comandos internos de Laravel, usando la API oficial de Hostinger con credenciales fuera del repositorio.

Subdominio privado previsto: `app.fabstudio.com.co`.

## Fuentes revisadas

- Documentacion oficial: https://developers.hostinger.com
- API Postman oficial Hostinger: https://www.postman.com/hostinger-api/hostinger
- Endpoints DNS usados:
  - `GET /api/dns/v1/zones/{domain}` para listar registros.
  - `POST /api/dns/v1/zones/{domain}/validate` para validar cambios sin aplicar.
  - `PUT /api/dns/v1/zones/{domain}` para actualizar registros.

## Variables de entorno

Estas variables deben vivir en `.env` local, Railway o el gestor de secretos correspondiente. No se deben versionar.

```dotenv
HOSTINGER_API_TOKEN=
HOSTINGER_DOMAIN=fabstudio.com.co
HOSTINGER_API_BASE_URL=https://developers.hostinger.com/api
```

El token compartido durante configuracion debe considerarse expuesto por haber pasado por chat/captura. Antes de produccion conviene regenerarlo en Hostinger y actualizar el secreto en el entorno real.

## Comandos disponibles

Listar registros actuales:

```bash
php artisan hostinger:dns:list
```

Listar respuesta completa:

```bash
php artisan hostinger:dns:list --json
```

Crear un archivo de produccion a partir del template y reemplazar todos los valores `REPLACE_*`:

```bash
cp infra/dns/fabstudio.com.co.template.json infra/dns/fabstudio.com.co.production.json
```

Validar el archivo de zona sin modificar DNS:

```bash
php artisan hostinger:dns:validate infra/dns/fabstudio.com.co.production.json
```

Sincronizar en modo seco. Valida contra Hostinger, pero no aplica cambios:

```bash
php artisan hostinger:dns:sync infra/dns/fabstudio.com.co.production.json
```

Aplicar cambios reales:

```bash
php artisan hostinger:dns:sync infra/dns/fabstudio.com.co.production.json --apply
```

Si se necesita reemplazar registros existentes con el mismo `name` y `type`, usar:

```bash
php artisan hostinger:dns:sync infra/dns/fabstudio.com.co.production.json --apply --overwrite
```

## Formato de zona

Hostinger espera una propiedad `zone` con registros en este formato:

```json
{
  "zone": [
    {
      "name": "@",
      "type": "A",
      "ttl": 3600,
      "records": [
        {
          "content": "203.0.113.10"
        }
      ]
    }
  ]
}
```

El template versionado esta en `infra/dns/fabstudio.com.co.template.json`. Debe copiarse a un archivo no sensible de produccion y reemplazar placeholders cuando Railway entregue los destinos reales.

## Reglas operativas

- `hostinger:dns:list` y `hostinger:dns:validate` son seguros: no modifican DNS.
- `hostinger:dns:sync` sin `--apply` es modo seco.
- `hostinger:dns:sync --apply` modifica DNS real y pide confirmacion en consola.
- `--overwrite` puede reemplazar registros coincidentes. Usarlo solo con backup/export previo de la zona.
- No aplicar `--overwrite` sobre la zona completa salvo que exista backup/export previo y aprobacion explicita.

## Auditoria API 2026-05-02

Consulta real ejecutada contra Hostinger API para `fabstudio.com.co`: exitosa.

Zona actual observada:

- `@` usa `ALIAS` hacia CDN de Hostinger.
- `www` usa `CNAME` hacia CDN de Hostinger.
- Correo activo en Hostinger mediante `MX`, `SPF`, `DMARC`, `autodiscover`, `autoconfig` y DKIM.
- `ftp` apunta por `A` a IP de hosting.
- `app` no existia todavia en la zona DNS.

## Cambio aplicado 2026-05-02

Se agrego el archivo operativo `infra/dns/fabstudio.com.co.app-railway-cname.json` y se aplico contra Hostinger API.

Registros aplicados:

```text
app CNAME eroh8bc4.up.railway.app.
_railway-verify.app TXT railway-verify=<token-de-verificacion-railway>
```

Verificacion:

- Hostinger API lista `app` como `CNAME` activo.
- Hostinger API lista `_railway-verify.app` como `TXT` activo.
- DNS publico resuelve `app.fabstudio.com.co` hacia el target dedicado de Railway.
- DNS publico resuelve el `TXT` de verificacion Railway.

Estado final: Railway ya reconoce `app.fabstudio.com.co` como custom domain y el healthcheck `https://app.fabstudio.com.co/up` responde `HTTP 200` con SSL valido.
