<?php

namespace App\Filament\Clusters\IndividualUsers\Resources;

use App\Filament\Clusters\IndividualUsers;
use App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource\Pages\CreateModuleLeader;
use App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource\Pages\ListModuleLeaders;
use App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource\RelationManagers;
use App\Models\ModuleLeader;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Creates the following table with:
 *
 * module leaders User ID within a text column
 *
 * module leaders id within a text column
 *
 * module leaders first name within a text column
 *
 * Their first name and surname
 *
 * Optional hidden filters to sort by the time of creation and/or update
 * @param Table $table
 * @return Table
 */
class ModuleLeaderResource extends Resource
{
    protected static ?string $model = ModuleLeader::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = IndividualUsers::class;

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('user.id')
                    ->label('User ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id')
                    ->label('Module Leader ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.first_name')
                    ->label('First Name (Forename)'),
                TextColumn::make('user.surname')
                    ->label('Surname'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => ListModuleLeaders::route('/'),
            'create' => CreateModuleLeader::route('/create'),
        ];
    }
}
