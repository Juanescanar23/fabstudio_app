# FAB STUDIO App - Manual Operativo

Fecha: 2026-05-03

## Accesos

- Aplicacion privada: `https://app.fabstudio.com.co`
- Panel administrativo: `https://app.fabstudio.com.co/admin`
- Portal cliente: `https://app.fabstudio.com.co/portal`

La plataforma debe operar 100% en espanol para el cliente. Si aparece texto estructural en ingles en una pantalla, debe registrarse como incidencia de QA.

## Roles

- `super_admin`: acceso completo al panel.
- `admin`: acceso administrativo al panel.
- `studio_member`: rol reservado para operacion interna con permisos futuros.
- `client`: acceso al portal cliente cuando el usuario esta vinculado a un cliente.

## Flujo De Cliente Y Proyecto

1. Entrar al panel administrativo.
2. Ir a `Clientes`.
3. Crear o actualizar cliente con nombre, correo, telefono, ciudad y estado.
4. Ir a `Proyectos`.
5. Crear proyecto asociado al cliente.
6. Completar codigo, nombre, tipologia, estado, fase actual, ubicacion y descripcion.
7. Crear fases e hitos si se requiere seguimiento detallado.

Buenas practicas:

- Usar codigos consistentes por proyecto.
- Mantener estados actualizados.
- No crear usuarios cliente sin asociarlos a un cliente real.

## Documentos

1. Ir a `Documentos`.
2. Crear documento asociado a un proyecto.
3. Definir categoria, visibilidad y estado.
4. Usar `client` en visibilidad solo cuando el archivo pueda verlo el cliente.
5. Crear o subir versiones desde `Versiones documento`.

Regla operativa:

- Un documento publicado y visible para cliente aparece en el portal privado.
- Versiones internas no deben marcarse como visibles al cliente.

## Assets Visuales

1. Ir a `Assets visuales`.
2. Crear asset asociado a proyecto.
3. Definir tipo: imagen, render, video, modelo 3D o tour.
4. Definir visibilidad y estado.
5. Cargar archivo o URL externa segun aplique.

Regla operativa:

- Renders o videos de revision deben estar en estado publicado y visibilidad cliente para aparecer en portal.

## Cotizaciones

### Crear Plantilla

1. Ir a `Plantillas de cotizacion`.
2. Crear plantilla activa.
3. Completar tipo, moneda, dias de vigencia y descripcion.
4. Agregar secciones de propuesta.
5. Agregar servicios y valores.
6. Agregar condiciones comerciales.
7. Completar instrucciones para asistencia IA si aplica.

Variables disponibles en textos:

- `{{client_name}}`
- `{{project_name}}`
- `{{project_location}}`
- `{{project_typology}}`
- `{{quote_number}}`
- `{{quote_title}}`

### Crear Cotizacion

1. Ir a `Cotizaciones`.
2. Crear cotizacion asociada a proyecto y cliente.
3. Completar numero, titulo, moneda y estado inicial.
4. Abrir la cotizacion.
5. Usar accion `Crear version desde plantilla`.
6. Seleccionar plantilla activa.
7. Confirmar creacion de version.

La version queda en estado `En revision`.

### Revision Y PDF

1. Ir a `Versiones de cotizacion`.
2. Abrir la version generada.
3. Revisar contenido, valores, condiciones y datos del cliente/proyecto.
4. Usar `Marcar revisada`.
5. Usar `Aprobar`.
6. Usar `Exportar PDF`.
7. Usar `Descargar PDF` para validar el archivo generado.

Reglas obligatorias:

- No exportar PDF sin revision humana.
- No enviar cotizaciones al cliente sin aprobacion.
- El asistente IA local solo prepara borradores; no reemplaza criterio comercial.

## Portal Cliente

1. Crear usuario con rol `client`.
2. Asociarlo al cliente correcto.
3. Entrar a `/portal`.
4. Validar que solo vea proyectos, documentos y assets de su cliente.
5. Validar comentarios y decisiones sobre entregables.

Regla de seguridad:

- Si un cliente ve informacion de otro cliente, es incidencia critica.

## CMS Y Sitio Publico

1. Ir a `Paginas CMS`.
2. Editar contenidos de portada y textos publicos.
3. Ir a `Ajustes del sitio`.
4. Actualizar SEO, correo publico y telefono.
5. Ir a `Biblioteca multimedia`.
6. Actualizar imagenes publicas o URLs externas.
7. Validar sitio publico en `/` y `/proyectos`.

## Automatizaciones

Las automatizaciones operan desde cron/colas y dejan evidencia en el panel administrativo.

Ubicacion:

- Panel: `Automatizacion > Registro de automatizaciones`.
- Comando manual: `php artisan automations:run`.
- Modo seco: `php artisan automations:run --dry-run`.

Automatizaciones activas:

- Prospectos nuevos sin seguimiento.
- Hitos proximos a vencer.
- Hitos vencidos.
- Documentos publicados para cliente.
- Assets visuales publicados para cliente.
- Cotizaciones proximas a vencer.

Reglas operativas:

- Un registro con estado `sent` indica que la notificacion fue despachada al sistema de correo.
- Un registro con estado `skipped` indica duplicado o ausencia de destinatarios.
- Si `MAIL_MAILER=log`, los correos no salen a clientes reales.
- Si se publica por error un documento o asset para cliente, corregir visibilidad/estado y revisar el log.

## Despliegue

El repositorio oficial es:

```text
git@github.com:Juanescanar23/fabstudio_app.git
```

El servicio web en Railway es `fabstudio-app`.

Comandos operativos:

```bash
composer test
php artisan app:readiness-check
php artisan automations:run --dry-run
git push
railway up --service fabstudio-app
railway service status --service fabstudio-app
curl -I https://app.fabstudio.com.co/up
```

Verificacion estricta antes de entrega:

```bash
php artisan app:readiness-check --strict
```

## Seguridad Antes De Entrega

Acciones obligatorias antes de cierre formal:

- Regenerar token Hostinger expuesto durante configuracion.
- Regenerar token Railway expuesto durante configuracion.
- Cambiar password inicial del admin.
- Configurar correo transaccional real si se requiere envio de emails a clientes.
- Definir politica de backups de base de datos.
- Eliminar o archivar datos QA si no deben quedar visibles.

Guia detallada: `docs/SEGURIDAD_CIERRE_PRODUCCION.md`.

## Soporte Basico

Si el panel no carga:

1. Validar `https://app.fabstudio.com.co/up`.
2. Revisar Railway deploy status.
3. Revisar logs de `fabstudio-app`.
4. Confirmar que MySQL esta activo.

Si un cliente no ve documentos:

1. Revisar que el usuario tenga rol `client`.
2. Revisar que el usuario tenga `client_id`.
3. Revisar que el documento/asset tenga visibilidad `client`.
4. Revisar que el estado sea publicable.
