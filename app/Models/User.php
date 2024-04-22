<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model class
 * Every user within this system is a user, of different types
 * @extends Authenticatable as we use it for auth
 * @implements HasName
 * @implements FilamentUser
 */
class User extends Authenticatable implements HasName, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Between project supervisor and Student,
     * this is a 1 to 1  relationship
     */


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'student',
        'projectsupervisor',
        'id',
        'user_type',
    ];
    /**
     * The attributes that should be cast.
     * user_type is gotten from the enum folder
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'user_type' => UserType::class,
    ];

    /**
     * A 1 to 1 relationship between user and student
     * @return HasOne
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * A 1 to 1 relationship between user and project supervisor
     * @return HasOne
     */
    public function projectsupervisor(): HasOne
    {
        return $this->hasOne(ProjectSupervisor::Class);
    }

    /**
     * A 1 to 1 relationship between user and Admin
     * @return HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * A 1 to 1 relationship between user and module leader
     * @return HasOne
     */
    public function moduleleader(): HasOne
    {
        return $this->hasOne(ModuleLeader::class);
    }

    /*
     * Filament hides attributes via $hidden, NOT $fillable
     * meaning that all models can be unguarded and only
     * $hidden is needed (to my understanding)
     */

    /**
     * project() function allows me to access all projects a user could have via projectSupervisor
     * @return HasManyThrough
     */
    public function project(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, ProjectSupervisor::class);
    }

    /**
     * Interests that belong to the user
     * Many to many relationship, used to access values
     * @return BelongsToMany
     */
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class, 'user_interests');
    }

    /**
     * Determines what user can access what panel.
     * Note: admin can access all panels
     * @param Panel $panel
     * @return bool
     * @throws Exception
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->user_type === UserType::Admin;
        }
        if ($panel->getId() === 'student') {
            return $this->user_type === UserType::Student || $this->user_type === UserType::Admin;
        }
        if ($panel->getId() === 'projectsupervisor') {
            return $this->user_type === UserType::ProjectSupervisor || $this->user_type === UserType::Admin;
        }
        if ($panel->getId() === 'moduleleader') {
            return $this->user_type === UserType::ModuleLeader || $this->user_type === UserType::Admin;
        }
        return false;
    }

    /**
     * getFilamentName gets the first_name and surname column from the DB and returns it for the dashboard
     * @return string
     */
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->surname}";
    }
}
