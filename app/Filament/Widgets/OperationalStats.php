<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Quote;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalStats extends BaseWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $activeProjects = Project::query()
            ->whereIn('status', ['planning', 'active'])
            ->count();

        $openLeads = Lead::query()
            ->whereIn('status', ['new', 'contacted', 'qualified'])
            ->count();

        $pendingMilestones = Milestone::query()
            ->whereNull('completed_at')
            ->count();

        $quotesInReview = Quote::query()
            ->whereIn('status', ['draft', 'review', 'sent'])
            ->count();

        return [
            Stat::make('Proyectos activos', $activeProjects)
                ->description(Client::query()->count().' clientes registrados')
                ->color('success'),
            Stat::make('Leads abiertos', $openLeads)
                ->description('Pendientes de gestion comercial')
                ->color('info'),
            Stat::make('Hitos pendientes', $pendingMilestones)
                ->description('Sin fecha de cierre registrada')
                ->color($pendingMilestones > 0 ? 'warning' : 'success'),
            Stat::make('Cotizaciones abiertas', $quotesInReview)
                ->description('Borrador, revision o enviadas')
                ->color('gray'),
        ];
    }
}
