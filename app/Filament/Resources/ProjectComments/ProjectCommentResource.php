<?php

namespace App\Filament\Resources\ProjectComments;

use App\Filament\Resources\ProjectComments\Pages\CreateProjectComment;
use App\Filament\Resources\ProjectComments\Pages\EditProjectComment;
use App\Filament\Resources\ProjectComments\Pages\ListProjectComments;
use App\Filament\Resources\ProjectComments\Pages\ViewProjectComment;
use App\Filament\Resources\ProjectComments\Schemas\ProjectCommentForm;
use App\Filament\Resources\ProjectComments\Schemas\ProjectCommentInfolist;
use App\Filament\Resources\ProjectComments\Tables\ProjectCommentsTable;
use App\Models\ProjectComment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectCommentResource extends Resource
{
    protected static ?string $model = ProjectComment::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Documentos';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Comentarios';

    protected static ?string $modelLabel = 'comentario';

    protected static ?string $pluralModelLabel = 'Comentarios';

    protected static ?string $recordTitleAttribute = 'body';

    public static function form(Schema $schema): Schema
    {
        return ProjectCommentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectCommentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectCommentsTable::configure($table);
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
            'index' => ListProjectComments::route('/'),
            'create' => CreateProjectComment::route('/create'),
            'view' => ViewProjectComment::route('/{record}'),
            'edit' => EditProjectComment::route('/{record}/edit'),
        ];
    }
}
