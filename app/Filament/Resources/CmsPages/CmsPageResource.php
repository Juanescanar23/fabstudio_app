<?php

namespace App\Filament\Resources\CmsPages;

use App\Filament\Resources\CmsPages\Pages\CreateCmsPage;
use App\Filament\Resources\CmsPages\Pages\EditCmsPage;
use App\Filament\Resources\CmsPages\Pages\ListCmsPages;
use App\Filament\Resources\CmsPages\Pages\ViewCmsPage;
use App\Models\CmsPage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CmsPageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Páginas CMS';

    protected static ?string $modelLabel = 'página CMS';

    protected static ?string $pluralModelLabel = 'Páginas CMS';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('title')
                ->label('Título')
                ->required(),
            TextInput::make('eyebrow')
                ->label('Etiqueta superior'),
            Textarea::make('summary')
                ->label('Resumen')
                ->columnSpanFull(),
            KeyValue::make('content')
                ->label('Contenido editable')
                ->columnSpanFull()
                ->keyLabel('Clave')
                ->valueLabel('Texto'),
            TextInput::make('seo_title')
                ->label('Título SEO'),
            Textarea::make('seo_description')
                ->label('Descripción SEO')
                ->columnSpanFull(),
            Toggle::make('is_published')
                ->label('Publicado')
                ->default(false),
            DateTimePicker::make('published_at')
                ->label('Publicado el'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('title')
                ->label('Título'),
            TextEntry::make('slug')
                ->label('Slug'),
            TextEntry::make('eyebrow')
                ->label('Etiqueta superior')
                ->placeholder('-'),
            TextEntry::make('summary')
                ->label('Resumen')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('seo_title')
                ->label('Título SEO')
                ->placeholder('-'),
            TextEntry::make('seo_description')
                ->label('Descripción SEO')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('is_published')
                ->label('Publicado')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
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
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('is_published')
                    ->label('Publicado')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('published_at')
                    ->label('Publicado el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListCmsPages::route('/'),
            'create' => CreateCmsPage::route('/create'),
            'view' => ViewCmsPage::route('/{record}'),
            'edit' => EditCmsPage::route('/{record}/edit'),
        ];
    }
}
