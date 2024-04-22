<?php

namespace App\Filament\Clusters\IndividualUsers\Resources;

use App\Filament\Clusters\IndividualUsers;
use App\Filament\Clusters\IndividualUsers\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Clusters\IndividualUsers\Resources\StudentResource\Pages\ListStudents;
use App\Filament\Clusters\IndividualUsers\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Shows each student within this cluster of students
 */
class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = IndividualUsers::class;

    /**
     * Creates the following table with:
     *
     * student's User ID within a text column
     *
     * Students id within a text column
     *
     * students first name within a text column
     *
     * Their first name and surname
     *
     * Optional hidden filters to sort by the time of creation and/or update
     * @param Table $table
     * @return Table
     */
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
                    ->label('Student ID')
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
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
        ];
    }
}
