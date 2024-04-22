<?php

namespace App\Filament\Project_supervisor\Resources;

use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource\Pages\ListProjectSupervisors;
use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource\Pages\ManageProjects;
use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource\RelationManagers;
use App\Models\User;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectSupervisorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * To be honest; this is very janky:
     *
     * The table isn't getting records from Student (where it should), its getting items from the Users table
     * Then I set a query modifier just to get those that are students.
     *
     * This is because Filament Doesn't support loading of multiple $model
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->has('student'))
            ->columns([
                TextColumn::make('user_name')
                    ->label('Student\'s User Name'),

                TextColumn::make('first_name')
                    ->label('Student\'s Forename'),

                TextColumn::make('surname')
                    ->label('Student\'s Surname'),
            ])
            /**
             * Create filters, being:
             * A selectfilter of interests
             * A toggle filter of students that do not have a project
             */
            ->filters([
                SelectFilter::make('interests')
                    ->relationship('interests', 'interest_name')
                    ->label('Your Interests')
                    ->multiple()
                    ->preload()
                    ->default(function (): array {
                        $user = User::find(Auth::id());
                        $attributes = [];
                        foreach ($user->interests as $interest) {
                            $attributes[] = $interest->pivot->interest_id;
                        }
                        return $attributes;
                    }),

                /*
                 * query used to determine relationship absence,
                 * thus showing the free users
                 */
                Filter::make('Free Students')
                    ->query(fn(Builder $query): Builder => $query->doesntHave('student.project'))
                    ->toggle()
                    ->default(),
            ]);
    }

    /**
     * Gets the pages as required for the relation page
     * @return array|PageRegistration[]
     */
    public static function getPages(): array
    {
        return [
            'index' => ListProjectSupervisors::route('/'),
            'projects' => ManageProjects::route('/{record}/projects'),
        ];
    }
}
