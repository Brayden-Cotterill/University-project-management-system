<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Creates an enum to be used throughout the application for each usertype
 */
enum UserType: string implements HasLabel
{
    case Student = 'student';
    case ProjectSupervisor = 'project_supervisor';
    case ModuleLeader = 'module_leader';
    case Admin = 'admin';

    //ensuring that the enum is readable
    public function getLabel(): ?string
    {
        //return $this->name;
        return match ($this) {
            self::Student => 'Student',
            self::ProjectSupervisor => 'Project Supervisor',
            self::ModuleLeader => 'Module Leader',
            self::Admin => 'Admin',
        };
    }
}
