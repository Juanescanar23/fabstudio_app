# Hito 5 - Landing-App, CMS y Marca

Fecha de inicio: 2026-05-02

## Alcance implementado

- Landing publica en `/`.
- Formulario publico en `POST /contacto`, conectado a `Lead`.
- Listado publico de proyectos en `/proyectos`.
- Detalle publico de proyecto en `/proyectos/{slug}`.
- CMS basico administrable desde Filament.
- Biblioteca multimedia administrable desde Filament.
- Ajustes publicos del sitio administrables desde Filament.
- Metadatos SEO por pagina CMS y por proyecto publico.

## Modelos agregados

- `CmsPage`: paginas editables por slug, contenido clave/valor, estado de publicacion y SEO.
- `MediaItem`: biblioteca multimedia con archivo local o URL externa, coleccion, orden y publicacion.
- `SiteSetting`: ajustes clave/valor para textos publicos, SEO general y contacto.

## Extension de proyectos

`Project` ahora puede publicarse en la vitrina sin duplicar informacion operativa:

- `public_slug`
- `public_summary`
- `public_cover_path`
- `is_public`
- `is_featured`
- `public_published_at`
- `seo_title`
- `seo_description`

## Administracion Filament

Grupo nuevo: `Contenido`.

- `Paginas CMS`
- `Biblioteca multimedia`
- `Ajustes del sitio`

Ademas, `Proyectos` incluye campos de publicacion publica y SEO.

## Flujo comercial

El formulario publico crea un prospecto con:

- `source`: `landing`
- `status`: `new`
- `interest`: tipo de proyecto seleccionado
- `message`: mensaje del formulario
- `metadata`: ciudad, rango de inversion, origen y fecha de envio

Esto mantiene el embudo comercial en el recurso existente de `Prospectos`.

## Dominio y subdominio

Base de administracion DNS implementada:

- Integracion con Hostinger API por variables de entorno.
- Comando `hostinger:dns:list` para auditar registros actuales.
- Comando `hostinger:dns:validate` para validar zona antes de aplicar.
- Comando `hostinger:dns:sync` con modo seco por defecto y `--apply` para cambios reales.
- Template versionado en `infra/dns/fabstudio.com.co.template.json`.
- Runbook operativo en `docs/HOSTINGER_DNS.md`.

Pendiente de infraestructura externa:

- Dominio principal: sitio publico.
- Subdominio privado: `app.fabstudio.com.co` para panel/portal.
- Configuracion Railway: app, variables, certificados y destinos DNS finales.

La aplicacion ya separa rutas publicas, panel y portal. La conexion real se ejecuta cuando Railway entregue los destinos definitivos y se aplique DNS en Hostinger.
