<?php

namespace App\Console\Commands;

use App\Services\Hostinger\HostingerDnsClient;
use App\Services\Hostinger\HostingerZoneFile;
use Illuminate\Console\Command;
use Throwable;

class HostingerDnsSync extends Command
{
    protected $signature = 'hostinger:dns:sync
        {file : Archivo JSON con el arreglo zone}
        {domain? : Dominio a sincronizar. Por defecto usa HOSTINGER_DOMAIN}
        {--apply : Aplicar cambios reales en Hostinger}
        {--overwrite : Reemplazar registros coincidentes de mismo nombre y tipo}';

    protected $description = 'Valida y sincroniza registros DNS en Hostinger con modo seco por defecto.';

    public function handle(HostingerDnsClient $client): int
    {
        try {
            $domain = $client->configuredDomain($this->argument('domain'));
            $zone = HostingerZoneFile::read($this->argument('file'));
            $overwrite = (bool) $this->option('overwrite');

            $client->validate($zone, $overwrite, $domain);

            if (! $this->option('apply')) {
                $this->info('Zona DNS valida. Modo seco: no se aplicaron cambios.');
                $this->line('Ejecuta el comando con --apply cuando los destinos de despliegue esten confirmados.');

                return self::SUCCESS;
            }

            if (! $this->confirm("Esto modificara registros DNS reales de {$domain}. Continuar?", false)) {
                $this->warn('Sincronizacion cancelada. No se aplicaron cambios.');

                return self::FAILURE;
            }

            $client->update($zone, $overwrite, $domain);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Registros DNS sincronizados en Hostinger.');

        return self::SUCCESS;
    }
}
