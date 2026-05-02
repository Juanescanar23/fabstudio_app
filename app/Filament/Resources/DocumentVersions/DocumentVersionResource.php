<?php

namespace App\Filament\Resources\DocumentVersions;

use App\Filament\Resources\DocumentVersions\Pages\CreateDocumentVersion;
use App\Filament\Resources\DocumentVersions\Pages\EditDocumentVersion;
use App\Filament\Resources\DocumentVersions\Pages\ListDocumentVersions;
use App\Filament\Resources\DocumentVersions\Pages\ViewDocumentVersion;
use App\Filament\Resources\DocumentVersions\Schemas\DocumentVersionForm;
use App\Filament\Resources\DocumentVersions\Schemas\DocumentVersionInfolist;
use App\Filament\Resources\DocumentVersions\Tables\DocumentVersionsTable;
use App\Models\DocumentVersion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentVersionResource extends Resource
{
    protected static ?string $model = DocumentVersion::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Documentos';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Versiones de documento';

    protected static ?string $modelLabel = 'versión de documento';

    protected static ?string $pluralModelLabel = 'Versiones de documento';

    protected static ?string $recordTitleAttribute = 'version_number';

    public static function form(Schema $schema): Schema
    {
        return DocumentVersionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentVersionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentVersionsTable::configure($table);
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
            'index' => ListDocumentVersions::route('/'),
            'create' => CreateDocumentVersion::route('/create'),
            'view' => ViewDocumentVersion::route('/{record}'),
            'edit' => EditDocumentVersion::route('/{record}/edit'),
        ];
    }
}
