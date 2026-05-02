<?php

namespace App\Filament\Resources\ProjectComments\Pages;

use App\Filament\Resources\ProjectComments\ProjectCommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectComment extends CreateRecord
{
    protected static string $resource = ProjectCommentResource::class;
}
