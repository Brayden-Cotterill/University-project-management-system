<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * ProjectResource gets the given projects within the Project table
 */
class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Creates the following table:
     * projects with:
     * The project supervisor Id column
     * The project supervisor's user name
     * The students user name
     * the name of the project
     *
     * Grouped together
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('No Projects Yet...')
            ->emptyStateDescription('Advise the Module leaders to encourage students to think of new projects!')
            ->columns([
                TextColumn::make('project_supervisor_id'),
                TextColumn::make('projectsupervisor.user.user_name')
                    ->label('Project Supervisor\'s user name'),
                TextColumn::make('student.user.user_name')
                    ->label('Student\'s user Name'),
                TextColumn::make('project_name')
            ])
            ->defaultGroup('project_supervisor_id');

    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
        ];
    }
}
