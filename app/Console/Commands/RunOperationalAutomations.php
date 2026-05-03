<?php

namespace App\Console\Commands;

use App\Services\Automation\OperationalAutomationService;
use Illuminate\Console\Command;

class RunOperationalAutomations extends Command
{
    protected $signature = 'automations:run
        {--dry-run : Evaluar candidatos sin registrar logs ni enviar notificaciones}
        {--json : Mostrar resultado en JSON}';

    protected $description = 'Ejecuta automatizaciones operativas de seguimiento, entregables y vencimientos.';

    public function handle(OperationalAutomationService $service): int
    {
        $summary = $service->run(dryRun: (bool) $this->option('dry-run'));

        if ($this->option('json')) {
            $this->line(json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        }

        $this->table(['Metrica', 'Valor'], [
            ['Candidatos evaluados', $summary['evaluated']],
            ['Registros creados', $summary['created']],
            ['Notificaciones enviadas', $summary['notified']],
            ['Duplicados omitidos', $summary['skipped']],
            ['Modo seco', $summary['dry_run'] ? 'si' : 'no'],
        ]);

        foreach ($summary['warnings'] as $warning) {
            $this->warn($warning);
        }

        $this->info('Automatizaciones operativas ejecutadas.');

        return self::SUCCESS;
    }
}
