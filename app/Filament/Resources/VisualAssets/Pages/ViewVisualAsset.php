<?php

namespace App\Filament\Resources\VisualAssets\Pages;

use App\Filament\Resources\VisualAssets\VisualAssetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVisualAsset extends ViewRecord
{
    protected static string $resource = VisualAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
