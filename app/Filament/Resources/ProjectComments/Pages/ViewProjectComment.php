<?php

namespace App\Filament\Resources\ProjectComments\Pages;

use App\Filament\Resources\ProjectComments\ProjectCommentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectComment extends ViewRecord
{
    protected static string $resource = ProjectCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
