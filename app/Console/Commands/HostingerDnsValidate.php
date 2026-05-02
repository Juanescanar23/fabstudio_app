<?php

namespace App\Console\Commands;

use App\Services\Hostinger\HostingerDnsClient;
use App\Services\Hostinger\HostingerZoneFile;
use Illuminate\Console\Command;
use Throwable;

class HostingerDnsValidate extends Command
{
    protected $signature = 'hostinger:dns:validate
        {file : Archivo JSON con el arreglo zone}
        {domain? : Dominio a validar. Por defecto usa HOSTINGER_DOMAIN}
        {--overwrite : Validar como reemplazo de registros coincidentes}';

    protected $description = 'Valida una zona DNS contra la API de Hostinger sin modificar registros.';

    public function handle(HostingerDnsClient $client): int
    {
        try {
            $zone = HostingerZoneFile::read($this->argument('file'));
            $client->validate($zone, (bool) $this->option('overwrite'), $this->argument('domain'));
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Zona DNS valida en Hostinger. No se aplicaron cambios.');

        return self::SUCCESS;
    }
}
