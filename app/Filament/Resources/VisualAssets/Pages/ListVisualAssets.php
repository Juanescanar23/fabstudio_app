<?php

namespace App\Filament\Resources\VisualAssets\Pages;

use App\Filament\Resources\VisualAssets\VisualAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVisualAssets extends ListRecords
{
    protected static string $resource = VisualAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
