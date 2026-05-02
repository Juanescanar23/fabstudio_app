<?php

namespace App\Filament\Resources\DocumentVersions\Pages;

use App\Filament\Resources\DocumentVersions\DocumentVersionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentVersion extends EditRecord
{
    protected static string $resource = DocumentVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
