<?php

namespace App\Filament\Resources\Allotments;

use App\Filament\Resources\Allotments\Pages\CreateAllotment;
use App\Filament\Resources\Allotments\Pages\EditAllotment;
use App\Filament\Resources\Allotments\Pages\ListAllotments;
use App\Filament\Resources\Allotments\Pages\ViewAllotment;
use App\Filament\Resources\Allotments\Schemas\AllotmentForm;
use App\Filament\Resources\Allotments\Schemas\AllotmentInfolist;
use App\Filament\Resources\Allotments\Tables\AllotmentsTable;
use App\Models\Allotment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AllotmentResource extends Resource
{
    protected static ?string $model = Allotment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Allotment';

    public static function form(Schema $schema): Schema
    {
        return AllotmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AllotmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllotmentsTable::configure($table);
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
            'index' => ListAllotments::route('/'),
            'create' => CreateAllotment::route('/create'),
            'view' => ViewAllotment::route('/{record}'),
            'edit' => EditAllotment::route('/{record}/edit'),
        ];
    }
}
