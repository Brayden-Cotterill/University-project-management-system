<?php

namespace App\Filament\Resources;

use App\Enums\UserType;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\RelationManagers\ProjectRelationManager;
use App\Models\ProjectSupervisor;
use App\Models\User;
use Exception;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    /*
     * This resource can:
     * View information about the user
     * Delete the user (and its child models)
     *
     * It cannot:
     * Create Users (done via creating a specific user subtype)
     * Edit User's type (once set it's set)
     */

    /**
     * Form for the user resource
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                /**
                 * TextInput for ID and usertype gotten from:
                 * https://www.answeroverflow.com/m/1154005399813492767
                 *
                 * Have to use form to make the action for some reason...
                 * Would use Infolist but I cant
                 */
                //
                TextInput::make('UserID')
                    ->label('User ID')
                    ->helperText(str('**Not** to be confused with the Student ID/ Project Supervisor ID')->inlineMarkdown()->toHtmlString())
                    ->numeric()
                    ->disabled()
                    ->formatStateUsing(fn(User $user) => $user->id), #Gets Id because ive set ID to be on the model

                TextInput::make('user_name')
                    ->label(__('User Name'))
                    ->required()
                    //->alphaDash()
                    ->alpha()
                    ->minLength(3)
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                TextInput::make('first_name')
                    ->label(__('First Name (Forename)'))
                    ->required()
                    ->alpha()
                    ->minLength(1)
                    ->maxLength(100),

                TextInput::make('surname')
                    ->alpha()
                    ->required()
                    ->minLength(1)
                    ->maxLength(100),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),


                Radio::make('user_type')
                    ->label(__('User Type'))
                    ->disabled()
                    ->options(UserType::class)
                    ->formatStateUsing(fn(User $user) => $user->user_type),

                //forced to do it this way because TextInput doesnt support dot notation
                TextInput::make('id')
                    ->disabled()
                    ->label(function (User $user) {
                        if ($user->student) {
                            return 'Student ID';
                        }
                        if ($user->projectsupervisor) {
                            return 'Project Supervisor ID';
                        }
                    })
                    ->numeric()
                    ->visible(function (User $user) {
                        if ($user->student || $user->projectsupervisor) {
                            return true;
                        }
                    })
                    ->formatStateUsing(function (User $user) {
                        if ($user->student) {
                            return $user->student->id;
                        }
                        if ($user->projectsupervisor) {
                            return $user->projectsupervisor->id;
                        }
                    }), #need to figure out how to add this into one line

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
                CheckboxList::make('interests')
                    ->relationship(titleAttribute: 'interest_name')
                    ->visible(function (User $user) {
                        if ($user->student || $user->projectsupervisor) {
                            return true;
                        }
                    })
            ]);
    }

    /**
     * Creates a table for the user resource with the following methods
     * @param Table $table
     * @return Table
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            //striped used to enhance readability
            ->striped()
            ->emptyStateHeading('No Users...')
            ->emptyStateDescription('Did you unintentionally delete ALL users? If so, rollback the DB')
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('User ID'),
                TextColumn::make('user_name'),
                TextColumn::make('first_name'),
                TextColumn::make('surname'),
                TextColumn::make('user_type')
                    ->sortable(),
            ])
            //filters for the resource
            ->filters([
                SelectFilter::make('user_type')
                    ->options(UserType::class)
            ])
            //actions
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            //bulk actions if one wants to do bulk actions
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Returns the given relationmanager for the project
     * @return string[]
     */
    public static function getRelations(): array
    {
        return [
            ProjectRelationManager::class,
        ];
    }

    /**
     * Gets the pages as requested from the pages directory
     * @return array|PageRegistration[]
     */
    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

}
