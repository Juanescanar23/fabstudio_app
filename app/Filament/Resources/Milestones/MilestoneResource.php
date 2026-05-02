<?php

namespace App\Filament\Resources\Milestones;

use App\Filament\Resources\Milestones\Pages\CreateMilestone;
use App\Filament\Resources\Milestones\Pages\EditMilestone;
use App\Filament\Resources\Milestones\Pages\ListMilestones;
use App\Filament\Resources\Milestones\Pages\ViewMilestone;
use App\Filament\Resources\Milestones\Schemas\MilestoneForm;
use App\Filament\Resources\Milestones\Schemas\MilestoneInfolist;
use App\Filament\Resources\Milestones\Tables\MilestonesTable;
use App\Models\Milestone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Operación';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Hitos';

    protected static ?string $modelLabel = 'hito';

    protected static ?string $pluralModelLabel = 'Hitos';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MilestoneForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MilestoneInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MilestonesTable::configure($table);
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
            'index' => ListMilestones::route('/'),
            'create' => CreateMilestone::route('/create'),
            'view' => ViewMilestone::route('/{record}'),
            'edit' => EditMilestone::route('/{record}/edit'),
        ];
    }
}
