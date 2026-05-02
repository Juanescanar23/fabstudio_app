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
                    ->label('Documento')
                    ->relationship('document', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('uploaded_by_id')
                    ->label('Subido por')
                    ->relationship('uploadedBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('version_number')
                    ->label('Número de versión')
                    ->required()
                    ->numeric(),
                TextInput::make('original_name')
                    ->label('Nombre original')
                    ->required(),
                FileUpload::make('file_path')
                    ->label('Archivo')
                    ->disk('local')
                    ->directory('documents')
                    ->required(),
                TextInput::make('disk')
                    ->label('Disco')
                    ->required()
                    ->default('local'),
                TextInput::make('mime_type')
                    ->label('Tipo MIME'),
                TextInput::make('size')
                    ->label('Tamaño')
                    ->numeric(),
                TextInput::make('checksum')
                    ->label('Checksum'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
                Toggle::make('is_current')
                    ->label('Versión vigente')
                    ->default(true)
                    ->required(),
            ]);
    }
}
