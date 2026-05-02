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
- [ ] Crear modelos principales: Client, Project, ProjectPhase, Milestone.
- [ ] Crear modelos documentales: Document, DocumentVersion, VisualAsset.
- [ ] Crear modelos comerciales: Lead, Quote, QuoteVersion.
- [x] Crear factories y seeders de demo.

## Hito 3 - Operacion Interna

Objetivo: panel administrativo usable para leads, clientes, proyectos, hitos, documentos y auditoria.

- [ ] Recurso Filament para Leads.
- [ ] Recurso Filament para Clientes.
- [ ] Recurso Filament para Proyectos.
- [ ] Recurso Filament para Fases e Hitos.
- [ ] Recurso Filament para Documentos y versiones.
- [ ] Recurso Filament para Comentarios/Aprobaciones.
- [ ] Dashboard operativo inicial.
- [ ] Filtros y busqueda por cliente, proyecto, estado y fase.
- [ ] Pruebas de autorizacion internas.

## Hito 4 - Portal Cliente y Comunicacion Visual

Objetivo: portal privado para seguimiento, documentos, renders y aprobaciones.

- [ ] Rutas y layout del portal cliente.
- [ ] Dashboard cliente.
- [ ] Timeline de proyecto.
- [ ] Boveda documental privada.
- [ ] Galeria privada de renders, imagenes y videos.
- [ ] Visor base para modelos compatibles.
- [ ] Comentarios y aprobaciones por entregable.
- [ ] Validar aislamiento Cliente A / Cliente B.

## Hito 5 - Landing-App, CMS y Marca

Objetivo: conectar la vitrina publica con leads, CMS basico, multimedia y SEO.

- [ ] Formulario de contacto conectado a Leads.
- [ ] CMS basico para contenido editable.
- [ ] Biblioteca multimedia.
- [ ] Proyectos publicos destacados.
- [ ] Metadatos SEO administrables.
- [ ] Conexion de dominio principal y subdominio privado.

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
