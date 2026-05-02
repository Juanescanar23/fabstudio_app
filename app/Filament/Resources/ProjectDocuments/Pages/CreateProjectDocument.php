<?php

namespace App\Filament\Resources\ProjectDocuments\Pages;

use App\Filament\Resources\ProjectDocuments\ProjectDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectDocument extends CreateRecord
{
    protected static string $resource = ProjectDocumentResource::class;
}
