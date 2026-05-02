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
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('commentable_type'),
                TextInput::make('commentable_id')
                    ->numeric(),
                Select::make('type')
                    ->options(FabStudioOptions::COMMENT_TYPES)
                    ->required()
                    ->default('comment'),
                Select::make('visibility')
                    ->options(FabStudioOptions::VISIBILITIES)
                    ->required()
                    ->default('internal'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Select::make('decision')
                    ->options(FabStudioOptions::DECISIONS),
                DateTimePicker::make('decided_at'),
                KeyValue::make('metadata')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
            ]);
    }
}
