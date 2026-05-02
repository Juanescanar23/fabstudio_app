<?php

namespace App\Console\Commands;

use App\Services\Hostinger\HostingerDnsClient;
use Illuminate\Console\Command;
use Throwable;

class HostingerDnsList extends Command
{
    protected $signature = 'hostinger:dns:list
        {domain? : Dominio a consultar. Por defecto usa HOSTINGER_DOMAIN}
        {--json : Mostrar la respuesta completa de Hostinger como JSON}';

    protected $description = 'Lista los registros DNS actuales de un dominio gestionado en Hostinger.';

    public function handle(HostingerDnsClient $client): int
    {
        try {
            $payload = $client->records($this->argument('domain'));
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($this->option('json')) {
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        $records = $this->extractZone($payload);

        if ($records === []) {
            $this->warn('Hostinger no devolvio registros DNS para mostrar.');

            return self::SUCCESS;
        }

        $this->table(
            ['Nombre', 'Tipo', 'TTL', 'Contenido'],
            array_map(fn (array $record): array => [
                (string) ($record['name'] ?? '-'),
                (string) ($record['type'] ?? '-'),
                (string) ($record['ttl'] ?? '-'),
                $this->formatRecordContent($record),
            ], $records),
        );

        return self::SUCCESS;
    }

    private function extractZone(array $payload): array
    {
        $zone = $payload['zone'] ?? $payload['data']['zone'] ?? $payload['data'] ?? $payload;

        if (! is_array($zone)) {
            return [];
        }

        return array_values(array_filter($zone, is_array(...)));
    }

    private function formatRecordContent(array $record): string
    {
        $records = $record['records'] ?? [];

        if (! is_array($records)) {
            return '-';
        }

        $contents = array_map(fn (mixed $entry): string => is_array($entry)
            ? (string) ($entry['content'] ?? '-')
            : (string) $entry, $records);

        return implode(', ', array_filter($contents));
    }
}
