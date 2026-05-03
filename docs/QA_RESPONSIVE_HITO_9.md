# QA Responsive Hito 9

Fecha: 2026-05-03

## Objetivo

Certificar visualmente que las vistas principales de produccion funcionan en mobile, tablet y desktop sin depender solo de pruebas automatizadas.

## Ambiente

- URL: `https://app.fabstudio.com.co`
- Browser: Chromium Playwright 1.57.0
- Mobile: `390x844`
- Tablet: `768x1024`
- Desktop: `1440x1000`
- Locale: `es-CO`

## Evidencia

Capturas guardadas en `docs/evidencias/hito-9/responsive/`.

| Vista | Mobile | Tablet | Desktop | Estado |
| --- | --- | --- | --- | --- |
| Sitio publico | `public-home-mobile.png` | `public-home-tablet.png` | `public-home-desktop.png` | Aprobado |
| Proyectos publicos | `public-projects-mobile.png` | `public-projects-tablet.png` | `public-projects-desktop.png` | Aprobado |
| Login admin | `admin-login-mobile.png` | `admin-login-tablet.png` | `admin-login-desktop.png` | Aprobado |
| Desafio MFA admin | `admin-mfa-challenge-mobile.png` | `admin-mfa-challenge-tablet.png` | `admin-mfa-challenge-desktop.png` | Aprobado |
| Dashboard admin | `admin-dashboard-mobile.png` | `admin-dashboard-tablet.png` | `admin-dashboard-desktop.png` | Aprobado |
| Cotizaciones admin | `admin-quotes-mobile.png` | `admin-quotes-tablet.png` | `admin-quotes-desktop.png` | Aprobado |
| Portal cliente | `portal-dashboard-mobile.png` | `portal-dashboard-tablet.png` | `portal-dashboard-desktop.png` | Aprobado |

## Hallazgos

- La interfaz esta localizada en espanol en las vistas capturadas.
- El login admin es responsive y no presenta solapamientos visibles en los tamanos evaluados.
- El panel admin exige MFA con codigo de autenticacion antes de entrar.
- El panel admin mantiene navegacion, tablas y acciones disponibles en mobile/tablet/desktop.
- El portal cliente abre correctamente con usuario QA vinculado al cliente de prueba.
- No se detectaron textos desbordados ni elementos criticos inaccesibles en las capturas generadas.

## Cuenta QA

Se creo una cuenta QA de cliente para validar el portal en produccion. Las credenciales no se versionan ni se escriben en documentacion:

```text
/Users/juanescanar/.codex/memories/fabstudio_client_qa_credentials_2026-05-03.json
```

Despues de capacitacion puede conservarse como cuenta de prueba interna o eliminarse desde el panel admin.
