<?php

namespace App\Filament\Pages\Auth;

use App\Models\ProjectSupervisor;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Arr;

/**
 * EditProfile extends BaseEdit Profile
 *
 * TL;DR; Forced to change it because Filament uses Laravels
 * default user migration; mine is different.
 *
 * Also:
 * The user cannot change their username as within a HE environment
 * they are given it to them
 */
class EditProfile extends BaseEditProfile
{
    /**
     * Defines the form schema and columns
     *
     * @param Form $form The form object.
     * @return Form The form object with the schema defined.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label('Forename (First name)')
                    ->required()
                    ->alpha()
                    ->minLength(1)
                    ->maxLength(100)
                    ->helperText(str('Your **first name** here.')->inlineMarkdown()->toHtmlString()),

                TextInput::make('surname')
                    ->label('Surname')
                    ->required()
                    ->alpha()
                    ->maxLength(100)
                    ->helperText(str('Your **Surname** here. Leave unchanged if you dont want to edit')->inlineMarkdown()->toHtmlString()),

                CheckboxList::make('interests')
                    ->relationship(titleAttribute: 'interest_name')
                    ->helperText(str('Please select your interests.')->inlineMarkdown()->toHtmlString())
                    ->visible(function (User $user) {
                        if ($user->student || $user->projectsupervisor) {
                            return true;
                        }
                    }),

                /*
                 * Hides the max student assign value if the given user isn't a project supervisor
                 */
                Grid::make()
                    ->disabled(function (User $user) {
                        if (!$user->projectsupervisor) {
                            return true;
                        }
                    })
                    ->relationship('projectsupervisor')
                    ->schema([
                        TextInput::make('max_student_assign')
                            ->label('Maximum number of students assigned to')
                            ->visible(function (ProjectSupervisor $user) {
                                if ($user->user) {
                                    return true;
                                }
                            })
                            ->required()
                            ->numeric()
                            ->minValue(3)
                            ->maxValue(10),
                    ]),

                /*
                 * get the rest of the other email components
                 */
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    /**
     * Remove sensitive data from the form fields before filling the form.
     *
     * This is to prevent mass assignment vulnerability.
     *
     * @param array $data The form data.
     * @return array The form data with sensitive data removed.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        unset($data['user_type']);
        unset($data['user_name']);
        unset($data['id']);

        return $data;
    }

    /**
     * Before validating the form for project supervisors,
     * it checks to see if their updated max_student_assign is less than the students they currently supervise
     * if so, the form is halted and a notification is sent saying to change the value
     */
    protected function beforeValidate(): void
    {
        $valueToUpdate = Arr::get($this->data, 'projectsupervisor.max_student_assign');

        if (is_int($valueToUpdate) && $valueToUpdate < $this->getUser()->project->where('project_supervisor_id', '=', $this->getUser()->projectsupervisor->id)->count()) {
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
