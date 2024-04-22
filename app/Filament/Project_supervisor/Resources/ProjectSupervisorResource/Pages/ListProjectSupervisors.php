<?php

namespace App\Filament\Project_supervisor\Resources\ProjectSupervisorResource\Pages;

use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListProjectSupervisors extends ListRecords
{
    protected static string $resource = ProjectSupervisorResource::class;

    /**
     * Get the relation record for the given project supervisor's projects
     * @return array|Action[]|Actions\ActionGroup[]
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Add/Manage Project')
                ->url(ProjectSupervisorResource::getUrl('projects', ['record' => Auth::id()]))
        ];
    }
}
