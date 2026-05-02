<?php

namespace App\Filament\Resources\ProjectDocuments\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectDocumentForm
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
                Select::make('category')
                    ->label('Categoría')
                    ->options(FabStudioOptions::DOCUMENT_CATEGORIES)
                    ->required()
                    ->default('general'),
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
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
            ]);
    }
}
