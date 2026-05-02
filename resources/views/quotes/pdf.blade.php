<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $quote->quote_number }} - {{ $quote->title }}</title>
    <style>
        body {
            color: #151515;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            margin: 38px;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            border-bottom: 2px solid #111;
            margin-bottom: 28px;
            padding-bottom: 18px;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0;
        }

        .muted {
            color: #666;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-top: 20px;
        }

        .grid {
            display: table;
            margin-top: 22px;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 50%;
        }

        .label {
            color: #666;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .value {
            margin-bottom: 10px;
        }

        .section {
            margin-top: 22px;
        }

        .section h2 {
            border-bottom: 1px solid #ddd;
            font-size: 15px;
            margin-bottom: 8px;
            padding-bottom: 5px;
        }

        table {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
        }

        th, td {
            border-bottom: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f4f4f4;
            font-size: 10px;
            text-transform: uppercase;
        }

        .right {
            text-align: right;
        }

        .totals {
            margin-left: auto;
            margin-top: 18px;
            width: 280px;
        }

        .totals td {
            border: 0;
            padding: 5px 0;
        }

        .totals .total td {
            border-top: 2px solid #111;
            font-size: 15px;
            font-weight: 700;
            padding-top: 8px;
        }

        .footer {
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
            margin-top: 34px;
            padding-top: 12px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="brand">FAB STUDIO</div>
        <p class="muted">Propuesta comercial revisada y aprobada internamente</p>
        <h1 class="title">{{ $quote->title }}</h1>
        <p class="muted">{{ $quote->quote_number }} · Versión {{ $version->version_number }}</p>
    </header>

    <section class="grid">
        <div class="col">
            <p class="label">Cliente</p>
            <p class="value">{{ $quote->client?->name ?: '-' }}</p>
            <p class="label">Proyecto</p>
            <p class="value">{{ $quote->project?->name ?: '-' }}</p>
            <p class="label">Ubicación</p>
            <p class="value">{{ $quote->project?->location ?: '-' }}</p>
        </div>
        <div class="col">
            <p class="label">Moneda</p>
            <p class="value">{{ $quote->currency }}</p>
            <p class="label">Válida hasta</p>
            <p class="value">{{ $quote->valid_until?->translatedFormat('M j, Y') ?: '-' }}</p>
            <p class="label">Aprobada por</p>
            <p class="value">{{ $version->approvedBy?->name ?: 'FAB STUDIO' }}</p>
        </div>
    </section>

    @foreach (($content['sections'] ?? []) as $section)
        <section class="section">
            <h2>{{ $section['heading'] ?? 'Sección' }}</h2>
            <p>{!! nl2br(e($section['body'] ?? '')) !!}</p>
        </section>
    @endforeach

    <section class="section">
        <h2>Detalle económico</h2>
        <table>
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th class="right">Cant.</th>
                    <th class="right">Valor unitario</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach (($content['line_items'] ?? []) as $item)
                    <tr>
                        <td>
                            <strong>{{ $item['name'] ?? 'Servicio' }}</strong><br>
                            <span class="muted">{{ $item['description'] ?? '' }}</span>
                        </td>
                        <td class="right">{{ number_format((float) ($item['quantity'] ?? 1), 2, ',', '.') }}</td>
                        <td class="right">{{ number_format((float) ($item['unit_price'] ?? 0), 0, ',', '.') }}</td>
                        <td class="right">{{ number_format((float) ($item['total'] ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td class="right">{{ number_format((float) $version->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Impuestos</td>
                <td class="right">{{ number_format((float) $version->tax, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Descuento</td>
                <td class="right">{{ number_format((float) $version->discount, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total</td>
                <td class="right">{{ $quote->currency }} {{ number_format((float) $version->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </section>

    @if (filled($content['terms'] ?? null))
        <section class="section">
            <h2>Condiciones</h2>
            <p>{!! nl2br(e($content['terms'])) !!}</p>
        </section>
    @endif

    <footer class="footer">
        Documento generado por FAB STUDIO App el {{ now()->translatedFormat('M j, Y H:i') }}.
        Esta versión tuvo revisión humana antes de exportarse.
    </footer>
</body>
</html>
