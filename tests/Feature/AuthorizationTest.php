<?php

namespace Tests\Feature;

use App\Filament\Module_leader\Resources\ModuleLeaderResource;
use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource;
use App\Filament\Resources\UserResource;
use App\Filament\Student\Resources\StudentResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for authorization of different user types
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Feature test to check if student cannot access the admin panel
     * should assert code 403
     * @return void
     */
    public function test_can_student_not_access_admin_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'student']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(UserResource::getUrl('index'));


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if student cannot access the project supervisor panel
     * should assert code 403
     * @return void
     */
    public function test_can_student_not_access_project_supervisor_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'student']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ProjectSupervisorResource::getUrl());


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if student cannot access the module_leader panel
     * should assert code 403
     * @return void
     */
    public function test_can_student_not_access_module_leader_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'student']);

        $panel = Filament::getPanel('moduleleader');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ModuleLeaderResource::getUrl('index'));


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if student cannot access their panel
     * should assert code 403
     * @return void
     */
    public function test_can_student_access_student_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'student']);

        $panel = Filament::getPanel('student');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(StudentResource::getUrl('index'));


        $response
            ->assertSuccessful();
    }

    /**
     * Feature test to check if project supervisor cannot access the admin panel
     * should assert code 403
     * @return void
     */
    public function test_can_project_supervisor_not_access_admin_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(UserResource::getUrl('index'));


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if project supervisor can access the project supervisor panel
     * should assert code 403
     * @return void
     */
    public function test_can_project_supervisor_access_project_supervisor_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ProjectSupervisorResource::getUrl());


        $response
            ->assertSuccessful();
    }

    /**
     * Feature test to check if project supervisor cannot access the module_leader panel
     * should assert code 403
     * @return void
     */
    public function test_can_project_supervisor_not_access_module_leader_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('moduleleader');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ModuleLeaderResource::getUrl('index'));


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if project supervisor cannot access the student panel
     * should assert code 403
     * @return void
     */
    public function test_can_project_supervisor_not_access_student_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('student');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(StudentResource::getUrl('index'));


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if module leader cannot access the admin panel
     * should assert code 403
     * @return void
     */
    public function test_can_module_leader_not_access_admin_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'module_leader']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(UserResource::getUrl('index'));


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if module leader cannot access the project supervisor panel
     * should assert code 403
     * @return void
     */
    public function test_can_module_leader_not_access_project_supervisor_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'module_leader']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ProjectSupervisorResource::getUrl());


        $response
            ->assertForbidden();
    }

    /**
     * Feature test to check if module leader can access the module_leader panel
     * should assert code 403
     * @return void
     */
    public function test_can_module_leader_access_module_leader_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'module_leader']);

        $panel = Filament::getPanel('moduleleader');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ModuleLeaderResource::getUrl('index'));


        $response
            ->assertSuccessful();
    }

    /**
     * Feature test to check if admin can access the student panel
     * should assert code 403
     * @return void
     */
    public function test_can_admin_access_student_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('student');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(StudentResource::getUrl('index'));


        $response
            ->assertSuccessful();
    }

    /**
     * Feature test to check if admin can access their panel
     * should assert code 200
     * @return void
     */
    public function test_can_admin_access_admin_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(UserResource::getUrl('index'));


        $response
            ->assertSuccessful();
    }

    /**
     * Feature test to check if admin can access the project supervisor panel
     * should assert code 200
     * @return void
     */
    public function test_can_admin_access_project_supervisor_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ProjectSupervisorResource::getUrl());


        $response
            ->assertSuccessful();
    }

    /**
     * Feature test to check if admin can access the module_leader panel
     * should assert code 200
     * @return void
     */
    public function test_can_admin_access_module_leader_panel(): void
    {
        /*
         * Create a single student from user factory
         */
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('moduleleader');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $response = $this->get(ModuleLeaderResource::getUrl('index'));


        $response
            ->assertSuccessful();
    }

}
