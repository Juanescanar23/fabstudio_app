<?php

namespace App\Filament\Resources\QuoteVersions\Pages;

use App\Filament\Resources\QuoteVersions\QuoteVersionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuoteVersion extends ViewRecord
{
    protected static string $resource = QuoteVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
