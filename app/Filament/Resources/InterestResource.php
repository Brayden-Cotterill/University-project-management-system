<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InterestResource\Pages\EditInterest;
use App\Filament\Resources\InterestResource\Pages\ListInterests;
use App\Filament\Resources\InterestResource\RelationManagers\UsersRelationManager;
use App\Models\Interest;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * InterestResource shows to the admin the current interests the system has
 */
class InterestResource extends Resource
{
    protected static ?string $model = Interest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    /**
     * Creates a form with the following fields:
     * Text input area called interest name
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('interest_name')
                    ->required()
                    ->string()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
            ]);
    }

    /**
     * renders a table with the following columns:
     * Interest id
     * Interest name
     * Timestamps (hidden with a toggle)
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('No Interests yet...')
            ->emptyStateDescription('Click on "New interest" to add a new interest')
            ->columns([
                TextColumn::make('id')
                    ->label('Interest ID')
                    ->numeric(),
                TextColumn::make('interest_name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
     * Gets the relation manager that shows the users for the given interest
     * @return string[]
     */
    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    /**
     * Returns the given pages for this resource
     * @return array|PageRegistration[]
     */
    public static function getPages(): array
    {
        return [
            'index' => ListInterests::route('/'),
            'edit' => EditInterest::route('/{record}/edit'),
        ];
    }
}
