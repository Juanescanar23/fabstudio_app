<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config([
        'services.hostinger.token' => 'test-token',
        'services.hostinger.domain' => 'fabstudio.com.co',
        'services.hostinger.base_url' => 'https://hostinger.test/api',
    ]);
});

it('lista registros DNS desde Hostinger', function (): void {
    Http::fake([
        'https://hostinger.test/api/dns/v1/zones/fabstudio.com.co' => Http::response([
            'zone' => [
                [
                    'name' => '@',
                    'type' => 'A',
                    'ttl' => 3600,
                    'records' => [
                        ['content' => '203.0.113.10'],
                    ],
                ],
            ],
        ]),
    ]);

    $this->artisan('hostinger:dns:list')
        ->expectsOutputToContain('203.0.113.10')
        ->assertExitCode(0);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'GET'
        && $request->url() === 'https://hostinger.test/api/dns/v1/zones/fabstudio.com.co'
        && $request->hasHeader('Authorization', 'Bearer test-token'));
});

it('valida una zona DNS sin aplicar cambios', function (): void {
    $fixture = hostingerDnsFixturePath();

    Http::fake([
        'https://hostinger.test/api/dns/v1/zones/fabstudio.com.co/validate' => Http::response(),
    ]);

    $this->artisan('hostinger:dns:validate', ['file' => $fixture])
        ->expectsOutputToContain('Zona DNS valida')
        ->assertExitCode(0);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && data_get($request->data(), 'zone.0.name') === '@'
        && data_get($request->data(), 'zone.0.records.0.content') === '203.0.113.10');
});

it('sincroniza en modo seco sin ejecutar PUT', function (): void {
    $fixture = hostingerDnsFixturePath();

    Http::fake([
        'https://hostinger.test/api/dns/v1/zones/fabstudio.com.co/validate' => Http::response(),
        '*' => Http::response(['unexpected' => true], 500),
    ]);

    $this->artisan('hostinger:dns:sync', ['file' => $fixture])
        ->expectsOutputToContain('Modo seco')
        ->assertExitCode(0);

    Http::assertNotSent(fn (Request $request): bool => $request->method() === 'PUT');
});

it('aplica sincronizacion cuando se confirma explicitamente', function (): void {
    $fixture = hostingerDnsFixturePath();

    Http::fake([
        'https://hostinger.test/api/dns/v1/zones/fabstudio.com.co/validate' => Http::response(),
        'https://hostinger.test/api/dns/v1/zones/fabstudio.com.co' => Http::response(['ok' => true]),
    ]);

    $this->artisan('hostinger:dns:sync', [
        'file' => $fixture,
        '--apply' => true,
        '--overwrite' => true,
    ])
        ->expectsConfirmation('Esto modificara registros DNS reales de fabstudio.com.co. Continuar?', 'yes')
        ->expectsOutputToContain('Registros DNS sincronizados')
        ->assertExitCode(0);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
        && data_get($request->data(), 'overwrite') === true);
});

function hostingerDnsFixturePath(): string
{
    File::ensureDirectoryExists(storage_path('framework/testing'));

    $path = storage_path('framework/testing/hostinger-zone-'.uniqid().'.json');

    file_put_contents($path, json_encode([
        'zone' => [
            [
                'name' => '@',
                'type' => 'A',
                'ttl' => 3600,
                'records' => [
                    ['content' => '203.0.113.10'],
                ],
            ],
        ],
    ], JSON_PRETTY_PRINT));

    return $path;
}
