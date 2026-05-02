<?php

namespace App\Filament\Resources\QuoteVersions;

use App\Filament\Resources\QuoteVersions\Pages\CreateQuoteVersion;
use App\Filament\Resources\QuoteVersions\Pages\EditQuoteVersion;
use App\Filament\Resources\QuoteVersions\Pages\ListQuoteVersions;
use App\Filament\Resources\QuoteVersions\Pages\ViewQuoteVersion;
use App\Filament\Resources\QuoteVersions\Schemas\QuoteVersionForm;
use App\Filament\Resources\QuoteVersions\Schemas\QuoteVersionInfolist;
use App\Filament\Resources\QuoteVersions\Tables\QuoteVersionsTable;
use App\Models\QuoteVersion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuoteVersionResource extends Resource
{
    protected static ?string $model = QuoteVersion::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Cotizaciones';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Versiones de cotización';

    protected static ?string $modelLabel = 'versión de cotización';

    protected static ?string $pluralModelLabel = 'Versiones de cotización';

    protected static ?string $recordTitleAttribute = 'version_number';

    public static function form(Schema $schema): Schema
    {
        return QuoteVersionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuoteVersionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuoteVersionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuoteVersions::route('/'),
            'create' => CreateQuoteVersion::route('/create'),
            'view' => ViewQuoteVersion::route('/{record}'),
            'edit' => EditQuoteVersion::route('/{record}/edit'),
        ];
    }
}
