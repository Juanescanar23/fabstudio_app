<?php

namespace App\Filament\Resources\ProjectComments\Pages;

use App\Filament\Resources\ProjectComments\ProjectCommentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectComments extends ListRecords
{
    protected static string $resource = ProjectCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
