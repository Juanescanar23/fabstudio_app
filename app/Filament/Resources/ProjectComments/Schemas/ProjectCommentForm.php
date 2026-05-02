<?php

namespace App\Filament\Resources\ProjectComments\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectCommentForm
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
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('commentable_type')
                    ->label('Tipo de entregable'),
                TextInput::make('commentable_id')
                    ->label('ID del entregable')
                    ->numeric(),
                Select::make('type')
                    ->label('Tipo')
                    ->options(FabStudioOptions::COMMENT_TYPES)
                    ->required()
                    ->default('comment'),
                Select::make('visibility')
                    ->label('Visibilidad')
                    ->options(FabStudioOptions::VISIBILITIES)
                    ->required()
                    ->default('internal'),
                Textarea::make('body')
                    ->label('Comentario')
                    ->required()
                    ->columnSpanFull(),
                Select::make('decision')
                    ->label('Decisión')
                    ->options(FabStudioOptions::DECISIONS),
                DateTimePicker::make('decided_at')
                    ->label('Decidido el'),
                KeyValue::make('metadata')
                    ->label('Metadatos')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
            ]);
    }
}
