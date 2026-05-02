<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FAB STUDIO App</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts

        <style>
            :root {
                color-scheme: light;
                font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
                background: #f6f5f2;
                color: #111111;
            }

            * {
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                margin: 0;
                display: grid;
                place-items: center;
                padding: 32px;
            }

            main {
                width: min(100%, 760px);
                border: 1px solid #dedbd3;
                border-radius: 8px;
                background: #ffffff;
                padding: clamp(32px, 7vw, 64px);
                box-shadow: 0 20px 60px rgba(17, 17, 17, 0.08);
            }

            .brand {
                margin: 0 0 28px;
                font-size: clamp(32px, 6vw, 56px);
                line-height: 1;
                font-weight: 800;
                letter-spacing: 0;
            }

            .intro {
                max-width: 560px;
                margin: 0 0 40px;
                color: #555047;
                font-size: 18px;
                line-height: 1.6;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            a {
                display: inline-flex;
                min-height: 44px;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                padding: 0 18px;
                color: #111111;
                font-weight: 700;
                text-decoration: none;
            }

            .primary {
                background: #f8b803;
            }

            .secondary {
                border: 1px solid #dedbd3;
                background: #ffffff;
            }

            @media (max-width: 560px) {
                body {
                    padding: 16px;
                }

                main {
                    padding: 28px;
                }

                .actions {
                    display: grid;
                }

                a {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <main>
            <h1 class="brand">FAB STUDIO App</h1>

            <p class="intro">
                Plataforma operativa para administrar clientes, proyectos, entregables, cotizaciones y seguimiento del portal cliente.
            </p>

            <div class="actions">
                <a class="primary" href="{{ url('/admin') }}">Entrar al panel</a>
                <a class="secondary" href="{{ url('/portal') }}">Portal cliente</a>
                @guest
                    <a class="secondary" href="{{ route('login') }}">Iniciar sesión</a>
                @endguest
            </div>
        </main>
    </body>
</html>
