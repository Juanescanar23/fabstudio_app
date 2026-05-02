<?php

namespace App\Filament\Resources\ProjectPhases\Pages;

use App\Filament\Resources\ProjectPhases\ProjectPhaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectPhase extends ViewRecord
{
    protected static string $resource = ProjectPhaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
