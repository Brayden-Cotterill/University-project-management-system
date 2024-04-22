<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Clusters\IndividualUsers\Resources\AdminResource;
use App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource;
use App\Filament\Clusters\IndividualUsers\Resources\ProjectSupervisorResource;
use App\Filament\Clusters\IndividualUsers\Resources\StudentResource;
use App\Filament\Resources\UserResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    /**
     * Sets the header actions to create a new user type
     * Url is from the given cluster with the given name
     * @return array|Action[]|ActionGroup[]
     */
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                ActionGroup::make([
                    Action::make('New Student')
                        ->url(StudentResource::getUrl('create')),

                ])->dropdown(false),

                ActionGroup::make([
                    Action::make('New Admin')
                        ->url(AdminResource::getUrl('create')),

                ])->dropdown(false),
                ActionGroup::make([
                    Action::make('New Project Supervisor')
                        ->url(ProjectSupervisorResource::getUrl('create')),

                ])->dropdown(false),
                Action::make('New Module Leader')
                    ->url(ModuleLeaderResource::getUrl('create')),
            ])
                ->label('Create User')
                ->color('primary')
                ->button()
        ];
    }


}
