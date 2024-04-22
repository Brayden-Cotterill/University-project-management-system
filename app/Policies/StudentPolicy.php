<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */

    public function before(User $user): bool|null
    {
        if ($user->user_type === UserType::Admin) {
            return true;
        }
        return null;
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): Response
    {
        return $user->user_type === UserType::Admin
            ? Response::allow()
            : Response::deny('You are not an admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type === UserType::Admin;

    }

    public function update(User $user): bool
    {
        return $user->user_type === UserType::Admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->user_type === UserType::Admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->user_type === UserType::Admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->user_type === UserType::Admin;
    }
}
