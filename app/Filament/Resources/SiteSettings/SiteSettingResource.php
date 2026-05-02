<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\CreateSiteSetting;
use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettings\Pages\ListSiteSettings;
use App\Filament\Resources\SiteSettings\Pages\ViewSiteSetting;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
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

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Ajustes del sitio';

    protected static ?string $modelLabel = 'ajuste del sitio';

    protected static ?string $pluralModelLabel = 'Ajustes del sitio';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('group')
                ->label('Grupo')
                ->required()
                ->default('general'),
            TextInput::make('key')
                ->label('Clave')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('label')
                ->label('Etiqueta'),
            Select::make('type')
                ->label('Tipo')
                ->options([
                    'text' => 'Texto',
                    'textarea' => 'Texto largo',
                    'url' => 'URL',
                    'email' => 'Correo electrónico',
                ])
                ->required()
                ->default('text'),
            Textarea::make('value')
                ->label('Valor')
                ->columnSpanFull(),
            Textarea::make('description')
                ->label('Descripción interna')
                ->columnSpanFull(),
            Toggle::make('is_public')
                ->label('Disponible para sitio público')
                ->default(true),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('group')
                ->label('Grupo'),
            TextEntry::make('key')
                ->label('Clave'),
            TextEntry::make('label')
                ->label('Etiqueta')
                ->placeholder('-'),
            TextEntry::make('value')
                ->label('Valor')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('is_public')
                ->label('Público')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label('Grupo')
                    ->searchable(),
                TextColumn::make('key')
                    ->label('Clave')
                    ->searchable(),
                TextColumn::make('label')
                    ->label('Etiqueta')
                    ->searchable(),
                TextColumn::make('is_public')
                    ->label('Público')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Grupo')
                    ->options([
                        'site' => 'Sitio',
                        'seo' => 'SEO',
                        'contact' => 'Contacto',
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
            'index' => ListSiteSettings::route('/'),
            'create' => CreateSiteSetting::route('/create'),
            'view' => ViewSiteSetting::route('/{record}'),
            'edit' => EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
