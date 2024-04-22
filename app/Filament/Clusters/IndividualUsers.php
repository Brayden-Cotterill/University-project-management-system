<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Class IndividualUsers shows each individual users within the Admin panel
 */
class IndividualUsers extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'User Type';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'user_type';
}
