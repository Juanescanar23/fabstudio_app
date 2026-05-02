<?php

namespace App\Filament\Resources\ProjectDocuments;

use App\Filament\Resources\ProjectDocuments\Pages\CreateProjectDocument;
use App\Filament\Resources\ProjectDocuments\Pages\EditProjectDocument;
use App\Filament\Resources\ProjectDocuments\Pages\ListProjectDocuments;
use App\Filament\Resources\ProjectDocuments\Pages\ViewProjectDocument;
use App\Filament\Resources\ProjectDocuments\Schemas\ProjectDocumentForm;
use App\Filament\Resources\ProjectDocuments\Schemas\ProjectDocumentInfolist;
use App\Filament\Resources\ProjectDocuments\Tables\ProjectDocumentsTable;
use App\Models\ProjectDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectDocumentResource extends Resource
{
    protected static ?string $model = ProjectDocument::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Documentos';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Documentos';

    protected static ?string $modelLabel = 'documento';

    protected static ?string $pluralModelLabel = 'Documentos';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ProjectDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectDocumentsTable::configure($table);
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
            'index' => ListProjectDocuments::route('/'),
            'create' => CreateProjectDocument::route('/create'),
            'view' => ViewProjectDocument::route('/{record}'),
            'edit' => EditProjectDocument::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
