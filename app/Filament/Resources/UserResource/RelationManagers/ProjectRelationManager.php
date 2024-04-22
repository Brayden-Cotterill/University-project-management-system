<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\UserType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Creates relationmanager for project
 */
class ProjectRelationManager extends RelationManager
{
    protected static string $relationship = 'project';

    protected static bool $isLazy = true;

    /**
     * Conditionally hides the relation manager on the edit page
     * @param Model $ownerRecord
     * @param string $pageClass
     * @return bool
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->user_type === UserType::ProjectSupervisor;
    }

    /**
     * Creates the following form for the relation manager:
     *
     * Text input with the project supervisor's id (disabled and dehydrated so user cannot edit it at the front end and so it gets saved to the db)
     * A select field of the students user name, that is searchable and preloaded
     * Text input of the project's name
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('project_supervisor_id')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->default(fn(RelationManager $livewire) => $livewire->getOwnerRecord()->projectsupervisor->id),

                /*
                 * Filament's select doesn't work with nested relationships, so I have to
                 * manually add in the column in order for it to work
                 */
                Select::make('student_id')
                    ->relationship(
                        'student',
                        'student_user_name',
                    )
                    ->searchable()
                    ->preload()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('project_name')
                    ->required()
                    ->minLength(1)
                    ->maxLength(100),
            ]);
    }

    /**
     * Creates the following table:
     *
     * A text column of the student's user name
     * A text column of the students first name
     * A text column of the students surname
     * A text column of the project name
     * An action button that saves the given project
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('student.user.user_name')
                    ->label('Student\'s user name'),
                TextColumn::make('student.user.first_name')
                    ->label('First Name'),
                TextColumn::make('student.user.surname')
                    ->label('Surname'),
                TextColumn::make('project_name'),
            ])
            /**
             * Before checks to see if the given project supervisor is full
             */
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->before(function (CreateAction $action) {
                        if ($this->getOwnerRecord()->projectsupervisor->max_student_assign <= $this->getOwnerRecord()->project->where('project_supervisor_id', '=', $this->getOwnerRecord()->projectsupervisor->id)->count()) {
                            Notification::make()
                                ->warning()
                                ->title('Error:')
                                ->body('Project supervisor has more than the students set')
                                ->seconds('10')
                                ->send();

                            $action->cancel();
                        }

                    })

            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
