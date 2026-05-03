<?php

namespace App\Filament\Resources\AutomationLogs;

use App\Filament\Resources\AutomationLogs\Pages\ListAutomationLogs;
use App\Filament\Resources\AutomationLogs\Pages\ViewAutomationLog;
use App\Filament\Resources\AutomationLogs\Schemas\AutomationLogInfolist;
use App\Filament\Resources\AutomationLogs\Tables\AutomationLogsTable;
use App\Models\AutomationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AutomationLogResource extends Resource
{
    protected static ?string $model = AutomationLog::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Automatización';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Registro de automatizaciones';

    protected static ?string $modelLabel = 'registro de automatización';

    protected static ?string $pluralModelLabel = 'Registro de automatizaciones';

    protected static ?string $recordTitleAttribute = 'title';

    public static function infolist(Schema $schema): Schema
    {
        return AutomationLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AutomationLogsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAutomationLogs::route('/'),
            'view' => ViewAutomationLog::route('/{record}'),
        ];
    }
}
