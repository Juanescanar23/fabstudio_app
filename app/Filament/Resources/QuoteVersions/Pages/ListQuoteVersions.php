<?php

namespace App\Filament\Resources\QuoteVersions\Pages;

use App\Filament\Resources\QuoteVersions\QuoteVersionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuoteVersions extends ListRecords
{
    protected static string $resource = QuoteVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
