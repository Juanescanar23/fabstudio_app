<?php

namespace App\Services\Hostinger;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HostingerDnsClient
{
    private string $token;

    private string $domain;

    private string $baseUrl;

    public function __construct(?string $token = null, ?string $domain = null, ?string $baseUrl = null)
    {
        $this->token = trim((string) ($token ?? config('services.hostinger.token')));
        $this->domain = trim((string) ($domain ?? config('services.hostinger.domain')));
        $this->baseUrl = rtrim(trim((string) ($baseUrl ?? config('services.hostinger.base_url'))), '/');
    }

    public function records(?string $domain = null): array
    {
        return $this->request()
            ->get($this->zonePath($domain))
            ->throw()
            ->json() ?? [];
    }

    public function validate(array $zone, bool $overwrite = false, ?string $domain = null): array
    {
        return $this->request()
            ->post($this->zonePath($domain).'/validate', [
                'overwrite' => $overwrite,
                'zone' => $this->normalizeZone($zone),
            ])
            ->throw()
            ->json() ?? [];
    }

    public function update(array $zone, bool $overwrite = false, ?string $domain = null): array
    {
        return $this->request()
            ->put($this->zonePath($domain), [
                'overwrite' => $overwrite,
                'zone' => $this->normalizeZone($zone),
            ])
            ->throw()
            ->json() ?? [];
    }

    public function normalizeZone(array $zone): array
    {
        if (isset($zone['zone']) && is_array($zone['zone'])) {
            $zone = $zone['zone'];
        }

        return array_map(fn (array $record): array => $this->normalizeRecord($record), array_values($zone));
    }

    public function configuredDomain(?string $domain = null): string
    {
        $domain = strtolower(trim((string) ($domain ?? $this->domain)));

        if ($domain === '') {
            throw new RuntimeException('HOSTINGER_DOMAIN no esta configurado.');
        }

        return ltrim($domain, '.');
    }

    private function normalizeRecord(array $record): array
    {
        $name = trim((string) ($record['name'] ?? ''));
        $type = strtoupper(trim((string) ($record['type'] ?? '')));
        $records = $record['records'] ?? null;

        if ($records === null && isset($record['content'])) {
            $records = [['content' => $record['content']]];
        }

        if ($records === null && isset($record['value'])) {
            $records = [['content' => $record['value']]];
        }

        if ($name === '' || $type === '' || ! is_array($records) || $records === []) {
            throw new RuntimeException('Cada registro DNS debe incluir name, type y records.');
        }

        $normalized = [
            'name' => $name,
            'type' => $type,
            'records' => array_map(fn (mixed $entry): array => [
                'content' => is_array($entry) ? (string) ($entry['content'] ?? '') : (string) $entry,
            ], array_values($records)),
        ];

        if (isset($record['ttl'])) {
            $normalized['ttl'] = (int) $record['ttl'];
        }

        return $normalized;
    }

    private function request(): PendingRequest
    {
        if ($this->token === '') {
            throw new RuntimeException('HOSTINGER_API_TOKEN no esta configurado.');
        }

        if ($this->baseUrl === '') {
            throw new RuntimeException('HOSTINGER_API_BASE_URL no esta configurado.');
        }

        return Http::acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->baseUrl($this->baseUrl)
            ->timeout(20)
            ->retry(2, 250);
    }

    private function zonePath(?string $domain = null): string
    {
        return '/dns/v1/zones/'.rawurlencode($this->configuredDomain($domain));
    }
}
