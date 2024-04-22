<?php

namespace App\Filament\Clusters\IndividualUsers\Resources\StudentResource\Pages;

use App\Filament\Clusters\IndividualUsers\Resources\StudentResource;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

}
