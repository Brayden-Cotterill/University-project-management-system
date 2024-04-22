<?php

namespace App\Filament\Clusters\IndividualUsers\Resources\StudentResource\Pages;

use App\Enums\UserType;
use App\Filament\Clusters\IndividualUsers\Resources\StudentResource;
use App\Filament\Resources\UserResource;
use App\Models\Student;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;


    use HasWizard;

    /*
     * So the documentation is unclear upon
     * how to add relationship to layout form components...
     * turns out you have to do it via adding a section/grid prior
     */

    /**
     * Creates the following user within a Wizard
     *
     * (A wizard essentially makes the creation process be step-by-step)
     * @return array consisting of the steps required
     */
    protected function getSteps(): array
    {
        return [
            Step::make('User Details')
                ->description('Please provide the user name for the student:')
                ->schema([
                    Grid::make()
                        ->relationship('user')
                        ->schema([
                            TextInput::make('user_name')
                                ->label('User Name')
                                ->helperText(str('**Must be between 3 and 50 characters**.')->inlineMarkdown()->toHtmlString())
                                ->alpha()
                                ->required()
                                ->unique()
                                ->minLength(3)
                                ->maxLength(50),

                            TextInput::make('first_name')
                                ->label('Forename (first name)')
                                ->helperText(str('Please enter the forename of the student.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->alpha()
                                ->autocapitalize()
                                ->minLength(1)
                                ->maxLength(100),

                            TextInput::make('surname')
                                ->label('Surname')
                                ->helperText(str('Please enter the new students surname.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->alpha()
                                ->autocapitalize()
                                ->minLength(1)
                                ->maxLength(100),

                            TextInput::make('email')
                                ->label('Email address')
                                ->helperText(str('Please enter the email address of the student.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->unique()
                                ->email()
                                ->minLength(1)
                                ->maxLength(100),

                            TextInput::make('password')
                                ->label('Password')
                                ->helperText(str('Remember to advise student to change password')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->password()
                                ->revealable()
                                ->alphaDash()
                                ->minLength(5)
                                ->maxLength(30),
                            Hidden::make('user_type')
                                //default used to make sure the user_type is correct
                                ->default(fn($state) => UserType::Student),

                        ]),
                ]),
            Step::make('Student Interests')
                ->description('Optional: Please provide the students interests')
                ->schema([
                    Grid::make()
                        ->relationship('user')
                        ->schema([
                            CheckboxList::make('interests')
                                ->relationship(titleAttribute: 'interest_name')

                        ]),
                ]),
        ];
    }

    /**
     * Sets the student_user_name column to the user_name the student has when created
     * @return void
     */
    protected function afterCreate(): void
    {
        $student = Student::latest()->first();
        $student->student_user_name = $student->user->user_name;
        $student->save();
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }

}
