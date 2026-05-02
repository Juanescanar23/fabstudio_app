<?php

namespace App\Filament\Resources\QuoteTemplates;

use App\Filament\Resources\QuoteTemplates\Pages\CreateQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Pages\EditQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Pages\ListQuoteTemplates;
use App\Filament\Resources\QuoteTemplates\Pages\ViewQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Schemas\QuoteTemplateForm;
use App\Filament\Resources\QuoteTemplates\Schemas\QuoteTemplateInfolist;
use App\Filament\Resources\QuoteTemplates\Tables\QuoteTemplatesTable;
use App\Models\QuoteTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuoteTemplateResource extends Resource
{
    protected static ?string $model = QuoteTemplate::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Cotizaciones';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Plantillas de cotización';

    protected static ?string $modelLabel = 'plantilla de cotización';

    protected static ?string $pluralModelLabel = 'Plantillas de cotización';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return QuoteTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuoteTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuoteTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuoteTemplates::route('/'),
            'create' => CreateQuoteTemplate::route('/create'),
            'view' => ViewQuoteTemplate::route('/{record}'),
            'edit' => EditQuoteTemplate::route('/{record}/edit'),
        ];
    }
}
