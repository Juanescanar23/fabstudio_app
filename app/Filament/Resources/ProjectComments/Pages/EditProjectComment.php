<?php

namespace App\Filament\Resources\ProjectComments\Pages;

use App\Filament\Resources\ProjectComments\ProjectCommentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectComment extends EditRecord
{
    protected static string $resource = ProjectCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
