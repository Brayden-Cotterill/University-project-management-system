<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Arr;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * BeforeValidate checks if the array key of $this->data
     * has projectsupervisor and will halt if it's less than
     * the current students a projectsupervisor supervises
     * @return void
     * @throws Halt
     */
    protected function beforeValidate(): void
    {
        $valueToUpdate = Arr::get($this->data, 'projectsupervisor.max_student_assign');

        if (is_int($valueToUpdate) && $valueToUpdate < $this->getRecord()->project->where('project_supervisor_id', '=', $this->getRecord()->projectsupervisor->id)->count()) {
            Notification::make()
                ->warning()
                ->title('You can\'t set below the the students you have')
                ->body('Please choose less')
                ->seconds(10)
                ->send();
            $this->halt();
        }
    }

}
