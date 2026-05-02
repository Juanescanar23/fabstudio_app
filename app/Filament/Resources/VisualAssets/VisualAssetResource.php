<?php

namespace App\Filament\Resources\VisualAssets;

use App\Filament\Resources\VisualAssets\Pages\CreateVisualAsset;
use App\Filament\Resources\VisualAssets\Pages\EditVisualAsset;
use App\Filament\Resources\VisualAssets\Pages\ListVisualAssets;
use App\Filament\Resources\VisualAssets\Pages\ViewVisualAsset;
use App\Filament\Resources\VisualAssets\Schemas\VisualAssetForm;
use App\Filament\Resources\VisualAssets\Schemas\VisualAssetInfolist;
use App\Filament\Resources\VisualAssets\Tables\VisualAssetsTable;
use App\Models\VisualAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisualAssetResource extends Resource
{
    protected static ?string $model = VisualAsset::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Documentos';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Recursos visuales';

    protected static ?string $modelLabel = 'recurso visual';

    protected static ?string $pluralModelLabel = 'Recursos visuales';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return VisualAssetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VisualAssetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisualAssetsTable::configure($table);
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
            'index' => ListVisualAssets::route('/'),
            'create' => CreateVisualAsset::route('/create'),
            'view' => ViewVisualAsset::route('/{record}'),
            'edit' => EditVisualAsset::route('/{record}/edit'),
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
