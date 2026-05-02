<?php

namespace App\Filament\Resources\MediaItems;

use App\Filament\Resources\MediaItems\Pages\CreateMediaItem;
use App\Filament\Resources\MediaItems\Pages\EditMediaItem;
use App\Filament\Resources\MediaItems\Pages\ListMediaItems;
use App\Filament\Resources\MediaItems\Pages\ViewMediaItem;
use App\Models\MediaItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MediaItemResource extends Resource
{
    protected static ?string $model = MediaItem::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Biblioteca multimedia';

    protected static ?string $modelLabel = 'recurso multimedia';

    protected static ?string $pluralModelLabel = 'Biblioteca multimedia';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Título')
                ->required(),
            TextInput::make('collection')
                ->label('Colección')
                ->required()
                ->default('general'),
            TextInput::make('alt_text')
                ->label('Texto alternativo'),
            Textarea::make('caption')
                ->label('Pie de imagen')
                ->columnSpanFull(),
            FileUpload::make('file_path')
                ->label('Archivo')
                ->disk('public')
                ->directory('media-library'),
            TextInput::make('external_url')
                ->label('URL externa')
                ->url(),
            TextInput::make('mime_type')
                ->label('Tipo MIME'),
            TextInput::make('size')
                ->label('Tamaño')
                ->numeric(),
            Toggle::make('is_public')
                ->label('Público')
                ->default(true),
            TextInput::make('sort_order')
                ->label('Orden')
                ->numeric()
                ->required()
                ->default(0),
            DateTimePicker::make('published_at')
                ->label('Publicado el'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('title')
                ->label('Título'),
            TextEntry::make('collection')
                ->label('Colección'),
            TextEntry::make('alt_text')
                ->label('Texto alternativo')
                ->placeholder('-'),
            TextEntry::make('caption')
                ->label('Pie de imagen')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('file_path')
                ->label('Archivo')
                ->placeholder('-'),
            TextEntry::make('external_url')
                ->label('URL externa')
                ->placeholder('-'),
            TextEntry::make('is_public')
                ->label('Público')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
            TextEntry::make('sort_order')
                ->label('Orden'),
            TextEntry::make('published_at')
                ->label('Publicado el')
                ->dateTime()
                ->placeholder('-'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('collection')
                    ->label('Colección')
                    ->searchable(),
                TextColumn::make('is_public')
                    ->label('Público')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Publicado el')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('collection')
                    ->label('Colección')
                    ->options([
                        'hero' => 'Hero',
                        'galeria' => 'Galería',
                        'general' => 'General',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaItems::route('/'),
            'create' => CreateMediaItem::route('/create'),
            'view' => ViewMediaItem::route('/{record}'),
            'edit' => EditMediaItem::route('/{record}/edit'),
        ];
    }
}
