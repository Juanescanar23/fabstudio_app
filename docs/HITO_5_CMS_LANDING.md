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

Pendiente de infraestructura externa:

- Dominio principal: sitio publico.
- Subdominio privado: panel/portal.
- Configuracion Railway: app, variables, certificados y DNS.

La aplicacion ya separa rutas publicas, panel y portal. La conexion real se ejecuta en Hito 7 junto con despliegue.
