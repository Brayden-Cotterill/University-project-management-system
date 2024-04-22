<?php

namespace App\Filament\Resources\InterestResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    /**
     * Forces Relation manager to be read only,
     * as we only want to show what users have that interest
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return true;
    }

    /**
     * Creates a striped table with the relation manager that has the user id, first_name, surname and user_type
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('No users yet...')
            ->emptyStateDescription('There\'re currently no Students or project supervisors that have this interest')
            ->recordTitleAttribute('user_id')
            ->columns([
                TextColumn::make('user_id')
                    ->label('User ID'),
                TextColumn::make('first_name')
                    ->label('Forename (first name)'),
                TextColumn::make('surname')
                    ->label('Surname'),
                TextColumn::make('user_type')
                    ->label('User Type'),
            ]);
    }
}
