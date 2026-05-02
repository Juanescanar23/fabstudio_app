# FAB STUDIO App - Checklist de Ejecucion

Estado inicial auditado: 2026-05-02

## Hito 1 - Alineacion y Base de Proyecto

Objetivo: convertir la documentacion en un proyecto ejecutable, trazable y listo para desarrollo.

- [x] Revisar documentacion contractual, tecnica, roadmap y presentacion.
- [x] Confirmar alcance MVP y backlog post-MVP.
- [x] Confirmar stack objetivo: Laravel, Filament, MySQL, Livewire, Redis/colas, Railway.
- [x] Auditar entorno local disponible.
- [x] Inicializar repositorio Git local.
- [x] Crear estructura base de aplicacion Laravel.
- [x] Configurar README operativo inicial.
- [x] Definir archivo `.env.example` sin secretos.
- [x] Crear primer commit de cimentacion.

## Hito 2 - Cimentacion Tecnica

Objetivo: establecer autenticacion, permisos, datos base y panel inicial.

- [x] Instalar PHP, Composer y Laravel CLI.
- [x] Crear Laravel app.
- [x] Instalar dependencias frontend.
- [x] Configurar base de datos local de validacion con SQLite.
- [ ] Provisionar MySQL local o Railway MySQL para entorno final.
- [x] Instalar Filament.
- [x] Crear usuario admin inicial.
- [x] Instalar sistema de roles/permisos.
- [x] Instalar auditoria base.
- [x] Validar migraciones base y seeders en base local SQLite.
- [x] Crear modelos principales: Client, Project, ProjectPhase, Milestone.
- [x] Crear modelos documentales: ProjectDocument, DocumentVersion, VisualAsset.
- [x] Crear modelos comerciales: Lead, Quote, QuoteVersion.
- [x] Crear factories y seeders de demo.

## Hito 3 - Operacion Interna

Objetivo: panel administrativo usable para leads, clientes, proyectos, hitos, documentos y auditoria.

- [x] Recurso Filament para Leads.
- [x] Recurso Filament para Clientes.
- [x] Recurso Filament para Proyectos.
- [x] Recurso Filament para Fases e Hitos.
- [x] Recurso Filament para Documentos y versiones.
- [x] Recurso Filament para Comentarios/Aprobaciones.
- [x] Dashboard operativo inicial.
- [x] Filtros y busqueda por cliente, proyecto, estado y fase.
- [x] Pruebas de autorizacion internas.

## Hito 4 - Portal Cliente y Comunicacion Visual

Objetivo: portal privado para seguimiento, documentos, renders y aprobaciones.

- [x] Rutas y layout del portal cliente.
- [x] Dashboard cliente.
- [x] Timeline de proyecto.
- [x] Boveda documental privada.
- [x] Galeria privada de renders, imagenes y videos.
- [x] Visor base para modelos compatibles.
- [x] Comentarios y aprobaciones por entregable.
- [x] Validar aislamiento Cliente A / Cliente B.
- [x] Localizar plataforma, portal, autenticacion, validaciones y textos base al espanol.

## Hito 5 - Landing-App, CMS y Marca

Objetivo: conectar la vitrina publica con leads, CMS basico, multimedia y SEO.

- [x] Formulario de contacto conectado a Leads.
- [x] CMS basico para contenido editable.
- [x] Biblioteca multimedia.
- [x] Proyectos publicos destacados.
- [x] Metadatos SEO administrables.
- [ ] Conexion de dominio principal y subdominio privado.

Nota: la aplicacion ya tiene rutas separadas para sitio publico, panel y portal cliente. La conexion real de dominio/subdominio queda pendiente de Railway/DNS.

## Hito 6 - Cotizaciones, IA y PDF

Objetivo: generar propuestas profesionales con historial, revision humana y exportacion PDF.

- [ ] Modelo de plantillas de cotizacion.
- [ ] Flujo de estados: borrador, revisado, aprobado, exportado.
- [ ] Servicio de asistencia IA con datos minimos.
- [ ] Pantalla de revision humana obligatoria.
- [ ] Generacion de PDF.
- [ ] Historial y versionado.
- [ ] Notificaciones transaccionales.
- [ ] Prueba end-to-end de cotizacion.

## Hito 7 - QA, Despliegue y Entrega

Objetivo: publicar una version estable y demostrable.

- [ ] Pruebas funcionales principales.
- [ ] Pruebas de permisos y aislamiento.
- [ ] Pruebas de carga/descarga documental.
- [ ] Pruebas de PDF e IA.
- [ ] Configuracion Railway app.
- [ ] Configuracion Railway worker.
- [ ] Configuracion Railway cron.
- [ ] Variables de entorno documentadas.
- [ ] Manual operativo breve.
- [ ] Capacitacion y cierre.

## Backlog Post-MVP

- [ ] OCR documental avanzado.
- [ ] Dashboards ejecutivos profundos.
- [ ] Firma digital avanzada.
- [ ] Automatizaciones comerciales complejas.
- [ ] App movil nativa.
- [ ] AR avanzada o modelado 3D nuevo.
- [ ] Integraciones contables o CRM externo.
