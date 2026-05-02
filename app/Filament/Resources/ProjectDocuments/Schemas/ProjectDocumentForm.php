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
                Select::make('category')
                    ->options(FabStudioOptions::DOCUMENT_CATEGORIES)
                    ->required()
                    ->default('general'),
                Select::make('visibility')
                    ->options(FabStudioOptions::VISIBILITIES)
                    ->required()
                    ->default('internal'),
                Select::make('status')
                    ->options(FabStudioOptions::PUBLISH_STATUSES)
                    ->required()
                    ->default('draft'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
