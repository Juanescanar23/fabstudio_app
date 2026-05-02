<?php

namespace App\Filament\Resources\ProjectDocuments\Pages;

use App\Filament\Resources\ProjectDocuments\ProjectDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectDocument extends ViewRecord
{
    protected static string $resource = ProjectDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
