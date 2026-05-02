<?php

namespace App\Filament\Resources\DocumentVersions\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentVersionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_document_id')
                    ->relationship('document', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('uploaded_by_id')
                    ->relationship('uploadedBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('version_number')
                    ->required()
                    ->numeric(),
                TextInput::make('original_name')
                    ->required(),
                FileUpload::make('file_path')
                    ->disk('local')
                    ->directory('documents')
                    ->required(),
                TextInput::make('disk')
                    ->required()
                    ->default('local'),
                TextInput::make('mime_type'),
                TextInput::make('size')
                    ->numeric(),
                TextInput::make('checksum'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Toggle::make('is_current')
                    ->default(true)
                    ->required(),
            ]);
    }
}
