<?php

namespace App\Filament\Resources\VisualAssets\Schemas;

use App\Models\VisualAsset;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VisualAssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('uploadedBy.name')
                    ->label('Uploaded by')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('type'),
                TextEntry::make('visibility'),
                TextEntry::make('status'),
                TextEntry::make('file_path')
                    ->placeholder('-'),
                TextEntry::make('preview_path')
                    ->placeholder('-'),
                TextEntry::make('external_url')
                    ->placeholder('-'),
                TextEntry::make('mime_type')
                    ->placeholder('-'),
                TextEntry::make('size')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('metadata')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (VisualAsset $record): bool => $record->trashed()),
            ]);
    }
}
