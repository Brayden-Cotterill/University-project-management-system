<?php

namespace App\Filament\Clusters\IndividualUsers\Resources;

use App\Filament\Clusters\IndividualUsers;
use App\Filament\Clusters\IndividualUsers\Resources\ProjectSupervisorResource\Pages\CreateProjectSupervisor;
use App\Filament\Clusters\IndividualUsers\Resources\ProjectSupervisorResource\Pages\ListProjectSupervisors;
use App\Filament\Clusters\IndividualUsers\Resources\ProjectSupervisorResource\RelationManagers;
use App\Models\ProjectSupervisor;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Creates the following table with:
 *
 * project supervisors User ID within a text column
 *
 * project supervisors id within a text column
 *
 * project supervisor first name within a text column
 *
 * Their first name and surname
 *
 * A textcolumn representing the maximum number of students they are assigned to
 *
 * Optional hidden filters to sort by the time of creation and/or update
 * @param Table $table
 * @return Table
 */
class ProjectSupervisorResource extends Resource
{
    protected static ?string $model = ProjectSupervisor::class;

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
                    ->label('Project Supervisor ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.first_name')
                    ->label('First Name (Forename)'),
                TextColumn::make('user.surname')
                    ->label('Surname'),
                TextColumn::make('max_student_assign')
                    ->label('Max Student(s) supervised'),
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
            'index' => ListProjectSupervisors::route('/'),
            'create' => CreateProjectSupervisor::route('/create'),
        ];
    }
}
