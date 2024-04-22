<?php

namespace App\Filament\Clusters\IndividualUsers\Resources\AdminResource\Pages;

use App\Enums\UserType;
use App\Filament\Clusters\IndividualUsers\Resources\AdminResource;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    use HasWizard;

    protected function getSteps(): array
    {
        return [
            Step::make('User Details')
                ->description('Please provide the user name for the administrator:')
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
                                ->alphaNum()
                                ->minLength(3)
                                ->maxLength(50),
                            TextInput::make('first_name')
                                ->label('Forename (first name)')
                                ->helperText(str('Please enter the forename of the administrator.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->alpha()
                                ->autocapitalize()
                                ->minLength(1)
                                ->maxLength(100),
                            TextInput::make('surname')
                                ->label('Surname')
                                ->helperText(str('Please enter the new administrators surname.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->alpha()
                                ->autocapitalize()
                                ->minLength(1)
                                ->maxLength(100),
                            TextInput::make('email')
                                ->label('Email address')
                                ->helperText(str('Please enter the email address of the administrator.')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->unique()
                                ->email()
                                ->minLength(1)
                                ->maxLength(50),
                            TextInput::make('password')
                                ->label('Password')
                                ->helperText(str('Remember to advise administrator to change password')->inlineMarkdown()->toHtmlString())
                                ->required()
                                ->password()
                                ->alphaDash()
                                ->revealable()
                                ->minLength(5)
                                ->maxLength(30),
                            Hidden::make('user_type')
                                ->default(fn($state) => UserType::Admin),

                        ]),
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }
}
