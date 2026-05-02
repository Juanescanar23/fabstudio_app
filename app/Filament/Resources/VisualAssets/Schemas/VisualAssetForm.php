<?php

namespace App\Filament\Resources\VisualAssets\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VisualAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('uploaded_by_id')
                    ->relationship('uploadedBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required(),
                Select::make('type')
                    ->options(FabStudioOptions::VISUAL_ASSET_TYPES)
                    ->required()
                    ->default('image'),
                Select::make('visibility')
                    ->options(FabStudioOptions::VISIBILITIES)
                    ->required()
                    ->default('internal'),
                Select::make('status')
                    ->options(FabStudioOptions::PUBLISH_STATUSES)
                    ->required()
                    ->default('draft'),
                FileUpload::make('file_path')
                    ->disk('local')
                    ->directory('visual-assets'),
                FileUpload::make('preview_path')
                    ->disk('local')
                    ->directory('visual-asset-previews'),
                TextInput::make('external_url')
                    ->url(),
                TextInput::make('mime_type'),
                TextInput::make('size')
                    ->numeric(),
                KeyValue::make('metadata')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
