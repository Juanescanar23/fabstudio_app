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
                    ->label('Proyecto')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('uploaded_by_id')
                    ->label('Subido por')
                    ->relationship('uploadedBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Título')
                    ->required(),
                Select::make('type')
                    ->label('Tipo')
                    ->options(FabStudioOptions::VISUAL_ASSET_TYPES)
                    ->required()
                    ->default('image'),
                Select::make('visibility')
                    ->label('Visibilidad')
                    ->options(FabStudioOptions::VISIBILITIES)
                    ->required()
                    ->default('internal'),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::PUBLISH_STATUSES)
                    ->required()
                    ->default('draft'),
                FileUpload::make('file_path')
                    ->label('Archivo')
                    ->disk('local')
                    ->directory('visual-assets'),
                FileUpload::make('preview_path')
                    ->label('Vista previa')
                    ->disk('local')
                    ->directory('visual-asset-previews'),
                TextInput::make('external_url')
                    ->label('URL externa')
                    ->url(),
                TextInput::make('mime_type')
                    ->label('Tipo MIME'),
                TextInput::make('size')
                    ->label('Tamaño')
                    ->numeric(),
                KeyValue::make('metadata')
                    ->label('Metadatos')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
