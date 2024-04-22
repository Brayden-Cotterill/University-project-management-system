<?php

namespace App\Filament\Module_leader\Resources;

use App\Filament\Module_leader\Resources\ModuleLeaderResource\Pages\ListModuleLeaders;
use App\Models\Project;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ModuleLeaderResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Creates a table showing all project supervisors, their given students
     * and a summery of all of the total students
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('No Students have assigned a project')
            ->emptyStateDescription('You should inform students about the proposal due date')
            ->columns([

                TextColumn::make('projectsupervisor.user.first_name')
                    ->label('Project Supervisor\'s name'),

                TextColumn::make('student.user.first_name')
                    ->label('Student\'s Forename'),

                TextColumn::make('student.user.surname')
                    ->label('Student surname'),

                TextColumn::make('student.user.user_name')
                    ->label('Student user name'),

                TextColumn::make('project_name')
                    ->summarize(Count::make('total_projects')->label('Total Projects')),
            ])
            ->groups([
                Group::make('projectsupervisor.user.user_name')
                    ->label('Project Supervisor')
                    //cant set it to be collapsed by default
                    ->collapsible(),
            ])
            ->defaultGroup('projectsupervisor.user.user_name');

    }

    public static function getPages(): array
    {
        return [
            'index' => ListModuleLeaders::route('/'),
        ];
    }
}
