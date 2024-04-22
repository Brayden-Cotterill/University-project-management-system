<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\StudentResource\Pages\ListStudents;
use App\Filament\Student\Resources\StudentResource\RelationManagers;
use App\Models\ProjectSupervisor;
use App\Models\User;
use Exception;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class StudentResource extends Resource
{
    protected static ?string $model = ProjectSupervisor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    #[Url]
    public ?array $tableFilters = null;

    /**
     * Creates a table with the following columns:
     *
     * A text column of the users' first name
     * A text column of the users surname
     * A text column of the users email (with a link to open a mailto link)
     * @param Table $table
     * @return Table
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('All Project Supervisors assigned!')
            ->emptyStateDescription('Please communicate with the Module Leader for further guidance')
            ->columns([
                //TextColumn::make('id'),
                TextColumn::make('user.first_name')
                    ->label('First name (Forename)'),

                TextColumn::make('user.surname')
                    ->label('Surname'),

                TextColumn::make('user.email')
                    ->description('Click to mail')
                    ->icon('heroicon-m-envelope')
                    ->url(fn(string $state): string => url("mailto: {$state}"))
                    ->label('Email Address'),
            ])
            /**
             * Gets the interests filters
             */
            ->filters([
                SelectFilter::make('interests')
                    ->label('Your Interests')
                    ->multiple()
                    ->relationship('user.interests', 'interest_name')
                    ->preload()
                    /**
                     * gets the given users' pivot attributes with foreach
                     */
                    ->default(function (): array {

                        $user = User::find(Auth::id());
                        $attributes = [];
                        foreach ($user->interests as $interest) {
                            $attributes[] = $interest->pivot->interest_id;
                        }
                        return $attributes;
                    }),
                /*
                 * DB::Raw is used to change the param (that would usually be a number)
                 * to the column `max_student_assign`
                 * From testing, it doesn't 'look like' a query injection could occur
                 */

                Filter::make('Free project supervisors')
                    ->query(function (Builder $query) {
                        $query->has('project', '<', DB::raw('`max_student_assign`'))
                            ->orDoesntHave('project');
                    })
                    ->toggle()
                    ->default()
            ]);
    }

    /**
     * Gets the required page to list the project supervisors
     * @return array|PageRegistration[]
     */
    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
        ];
    }
}

