<?php

namespace App\Filament\Resources\ProjectPhases;

use App\Filament\Resources\ProjectPhases\Pages\CreateProjectPhase;
use App\Filament\Resources\ProjectPhases\Pages\EditProjectPhase;
use App\Filament\Resources\ProjectPhases\Pages\ListProjectPhases;
use App\Filament\Resources\ProjectPhases\Pages\ViewProjectPhase;
use App\Filament\Resources\ProjectPhases\Schemas\ProjectPhaseForm;
use App\Filament\Resources\ProjectPhases\Schemas\ProjectPhaseInfolist;
use App\Filament\Resources\ProjectPhases\Tables\ProjectPhasesTable;
use App\Models\ProjectPhase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectPhaseResource extends Resource
{
    protected static ?string $model = ProjectPhase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Operacion';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Fases';

    protected static ?string $modelLabel = 'fase';

    protected static ?string $pluralModelLabel = 'fases';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProjectPhaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectPhaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectPhasesTable::configure($table);
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
            'index' => ListProjectPhases::route('/'),
            'create' => CreateProjectPhase::route('/create'),
            'view' => ViewProjectPhase::route('/{record}'),
            'edit' => EditProjectPhase::route('/{record}/edit'),
        ];
    }
}
