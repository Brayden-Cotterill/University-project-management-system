<?php

namespace App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource\Pages;

use App\Enums\UserType;
use App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;

class CreateModuleLeader extends CreateRecord
{
    protected static string $resource = ModuleLeaderResource::class;

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
                ->description('Please provide the user name for the Module Leader:')
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
                                ->helperText(str('Please enter the forename of the module leader.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->alpha()
                                ->autocapitalize()
                                ->minLength(1)
                                ->maxLength(100),
                            TextInput::make('surname')
                                ->label('Surname')
                                ->helperText(str('Please enter the new module leaders surname.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->alpha()
                                ->autocapitalize()
                                ->minLength(1)
                                ->maxLength(100),
                            TextInput::make('email')
                                ->label('Email address')
                                ->helperText(str('Please enter the email address of the module leader.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->unique()
                                ->email()
                                ->minLength(1)
                                ->maxLength(255),
                            TextInput::make('password')
                                ->label('Password')
                                ->helperText(str('Remember to advise module leader to change password')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->password()
                                ->revealable()
                                ->minLength(5)
                                ->maxLength(30),
                            Hidden::make('user_type')
                                ->default(fn($state) => UserType::ModuleLeader),

                        ]),
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }
}
