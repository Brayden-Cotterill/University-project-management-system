<?php

namespace App\Filament\Project_supervisor\Resources\ProjectSupervisorResource\Pages;

use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Relation page ManageProjects shows the related pages for the given projects
 */
class ManageProjects extends ManageRelatedRecords
{
    protected static string $resource = ProjectSupervisorResource::class;

    protected static string $relationship = 'project';
    protected static bool $isLazy = true;


    public static function getNavigationLabel(): string
    {
        return 'Projects';
    }

    /**
     * canAccess ensures that only the current project supervisor can access their relation
     * @param array $parameters
     * @return bool as a 403
     */
    public static function canAccess(array $parameters = []): bool
    {
        $value = Arr::get($parameters, 'record.id');
        return $value === Auth::id();
    }

    /**
     * Creates a form with the following inputs:
     *
     * A TextInput of the project supervisor id (to be inserted within the DB)
     * A TextInput of the Student ID
     * The project name
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
                    ->default(fn() => auth()->user()->projectsupervisor->id),
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
     * Creates a table with the following:
     *
     * A text column consisting of the students user name
     * A text column consisting of the students first name
     * A text column consisting of the students surname
     * A text column consisting of the project title
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
             * A header action to create the project
             * method Before is used to check if the given project supervisor is over their maximum students assigned
             */
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->before(function (CreateAction $action) {
                        if ($this->getOwnerRecord()->projectsupervisor->max_student_assign <=
                            $this->getOwnerRecord()->project->where('project_supervisor_id', '=', $this->getOwnerRecord()->projectsupervisor->id)->count()) {
                            Notification::make()
                                ->warning()
                                ->title('Error:')
                                ->body('You already have the maximum number of students!')
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

    /**
     * removes sensitive data within the createAction (as since Filament considers it an edit page)
     * to ensure the system is not vulnerable to mass assigment
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        unset($data['user_type']);
        unset($data['user_name']);
        unset($data['id']);
        unset($data['password']);

        return $data;
    }

}
