# Capacitacion Final

Fecha: 2026-05-03

## Objetivo

Guiar la entrega operativa de FAB STUDIO App al cliente final, cubriendo uso diario, seguridad, automatizaciones, soporte y criterios de cierre.

## Participantes

- Responsable FAB STUDIO: define usuarios reales, correos, dominios y criterios de aprobacion.
- Administrador de plataforma: opera panel admin, usuarios, proyectos, documentos y cotizaciones.
- Cliente piloto: valida portal, documentos, comentarios y aprobaciones.
- Responsable tecnico: confirma backups, correo real, secretos y soporte.

## Agenda Recomendada

### 1. Acceso Y Seguridad

- Entrar a `https://app.fabstudio.com.co/admin`.
- Confirmar usuario admin real.
- Activar/verificar app 2FA del administrador.
- Guardar codigos de recuperacion en gestor seguro.
- Confirmar que las credenciales no se comparten por chat.

### 2. Operacion Interna

- Crear/editar clientes.
- Crear proyectos y fases.
- Crear hitos.
- Subir documentos y versiones.
- Publicar o despublicar archivos para cliente.
- Subir assets visuales.
- Revisar comentarios y aprobaciones.

### 3. Cotizaciones

- Crear plantilla de cotizacion.
- Crear cotizacion asociada a cliente/proyecto.
- Generar version asistida.
- Revisar contenido con control humano.
- Aprobar version.
- Exportar PDF.
- Validar que el PDF queda protegido.

### 4. Portal Cliente

- Ingresar a `/portal` con usuario cliente.
- Revisar dashboard.
- Abrir proyecto.
- Descargar documento publicado.
- Revisar galeria visual.
- Registrar comentario.
- Registrar decision/aprobacion.
- Confirmar que el cliente no ve informacion de otros clientes.

### 5. Automatizaciones

- Revisar modulo `Automatizaciones`.
- Explicar eventos actuales:
  - leads nuevos.
  - hitos por vencer o vencidos.
  - documentos publicados.
  - assets publicados.
  - cotizaciones proximas a vencer.
- Explicar diferencia entre log interno y correo real.
- Confirmar que mientras `MAIL_MAILER=log`, no salen emails a clientes.

### 6. Produccion Y Soporte

- Revisar healthcheck `/up`.
- Ejecutar o revisar `app:readiness-check`.
- Confirmar backups Railway.
- Confirmar correo transaccional real.
- Confirmar rotacion de tokens Railway/Hostinger.
- Definir canal de soporte e incidentes.

## Checklist De Aceptacion Cliente

- [ ] Cliente puede entrar al portal.
- [ ] Cliente ve solo sus proyectos.
- [ ] Cliente puede descargar documentos publicados.
- [ ] Cliente puede revisar assets visuales.
- [ ] Cliente puede comentar y aprobar.
- [ ] Admin puede crear y operar clientes/proyectos/documentos/cotizaciones.
- [ ] Admin tiene 2FA activo.
- [ ] Correo real probado o decision formal de mantenerlo en `log`.
- [ ] Backups confirmados en Railway.
- [ ] Responsables de soporte definidos.

## Pendientes Que Requieren Decision Externa

- Credenciales de proveedor de correo real.
- Rotacion final de tokens desde dashboards de Railway y Hostinger.
- Activacion/confirmacion de backups desde Railway con usuario autorizado.
- Ejecucion de la sesion real con el cliente.
