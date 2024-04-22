<?php

namespace Tests\Feature\Filament;

use App\Filament\Clusters\IndividualUsers\Resources\AdminResource;
use App\Filament\Clusters\IndividualUsers\Resources\ModuleLeaderResource;
use App\Filament\Clusters\IndividualUsers\Resources\ProjectSupervisorResource;
use App\Filament\Clusters\IndividualUsers\Resources\StudentResource;
use App\Filament\Resources\InterestResource;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Resources\UserResource;
use App\Models\Interest;
use App\Models\Project;
use App\Models\ProjectSupervisor;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests for all features on the admin panel.
 *
 * WARNING: Due to the use of DatabaseMigrations trait as well as seeding,
 * This test is **very** expensive
 */
class AdminPanelTest extends TestCase
{
    /*
     * Use DatabaseMigrations rather than RefreshDatabase due to the use of seed
     */
    use DatabaseMigrations;

    public function test_are_admin_user_resource_pages_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $users = User::factory(1)->create();

        $this->seed();

        $this->actingAs($user);

        $this->get(UserResource::getUrl('index'))->assertSuccessful();
        Livewire::test(UserResource\Pages\ListUsers::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('user_name')
            ->assertCanRenderTableColumn('first_name')
            ->assertCanRenderTableColumn('surname')
            ->assertCanRenderTableColumn('user_type')
            ->sortTable('user_type', 'desc')
            ->assertCanSeeTableRecords($users->sortByDesc('user_type'), inOrder: true)
            ->assertTableColumnExists('id')
            ->assertTableColumnExists('user_name')
            ->assertTableColumnExists('first_name')
            ->assertTableColumnExists('surname')
            ->assertTableColumnExists('user_type');
        $this->get(UserResource::getUrl('view', ['record' => User::factory()->create(),]))->assertSuccessful();
        $this->get(UserResource::getUrl('edit', ['record' => User::factory()->create(),]))->assertSuccessful();
    }

    public function test_are_admin_interest_resource_page_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $users = User::factory(1)->create();

        $this->seed();

        $this->actingAs($user);

        $this->get(InterestResource::getUrl('index'))->assertSuccessful();
        Livewire::test(InterestResource\Pages\ListInterests::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('interest_name');
        $this->get(InterestResource::getUrl('edit', ['record' => Interest::factory()->create(),]))->assertSuccessful();
    }

    public function test_are_admin_project_resource_page_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        //$this->seed();

        $users = Project::factory(2)->for(ProjectSupervisor::factory())->has(Student::factory())->create();

        $this->actingAs($user);

        $this->get(ProjectResource::getUrl('index'))->assertSuccessful();
        Livewire::test(ListProjects::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('project_supervisor_id')
            ->assertCanRenderTableColumn('projectsupervisor.user.user_name')
            ->assertCanRenderTableColumn('student.user.user_name')
            ->assertCanRenderTableColumn('project_name');

    }

    public function test_are_admin_student_cluster_resource_pages_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $users = User::factory(1)->create();

        $this->seed();

        $this->actingAs($user);

        $this->get(StudentResource::getUrl('index'))->assertSuccessful();
        $this->get(StudentResource::getUrl('create'))->assertSuccessful();
        Livewire::test(StudentResource\Pages\ListStudents::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('user.id')
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('user.first_name')
            ->assertCanRenderTableColumn('user.surname');
    }

    public function test_are_admin_project_supervisor_cluster_resource_pages_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $users = User::factory(1)->create();

        $this->seed();

        $this->actingAs($user);

        $this->get(ProjectSupervisorResource::getUrl('index'))->assertSuccessful();
        $this->get(ProjectSupervisorResource::getUrl('create'))->assertSuccessful();
        Livewire::test(ProjectSupervisorResource\Pages\ListProjectSupervisors::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('user.id')
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('user.first_name')
            ->assertCanRenderTableColumn('user.surname')
            ->assertCanRenderTableColumn('max_student_assign');
    }

    public function test_are_admin_module_leader_cluster_pages_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $users = User::factory(1)->create(['user_type' => 'module_leader']);

        $this->seed();

        $this->actingAs($user);

        $this->get(ModuleLeaderResource::getUrl('index'))->assertSuccessful();
        $this->get(ModuleLeaderResource::getUrl('create'))->assertSuccessful();
        Livewire::test(ModuleLeaderResource\Pages\ListModuleLeaders::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('user.id')
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('user.first_name')
            ->assertCanRenderTableColumn('user.surname');
    }

    public function test_are_admin_cluster_resource_pages_rendered(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $users = User::factory(1)->create();

        $this->seed();

        $this->actingAs($user);


        $this->get(AdminResource::getUrl('index'))->assertSuccessful();
        $this->get(AdminResource::getUrl('create'))->assertSuccessful();
        Livewire::test(AdminResource\Pages\ListAdmins::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('user.id')
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('user.first_name')
            ->assertCanRenderTableColumn('user.surname');
    }

    public function test_can_student_be_created(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $newStudent = User::factory()->make(['user_type' => 'student']);


        $this->actingAs($user);

        $this->get(StudentResource::getUrl('index'))->assertSuccessful();
        $this->get(StudentResource::getUrl('create'))->assertSuccessful();

        Livewire::test(StudentResource\Pages\CreateStudent::class)
            ->fillForm([
                'user.id' => $newStudent->id,
                'user.user_name' => $newStudent->user_name,
                'user.first_name' => $newStudent->first_name,
                'user.surname' => $newStudent->surname,
                'user.email' => $newStudent->email,
                'user.password' => 'password',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(User::class, [
            'user_name' => $newStudent->user_name,
            'first_name' => $newStudent->first_name,
            'surname' => $newStudent->surname,
            'email' => $newStudent->email,
        ]);


        //check the password in a separate column as since their hashed
        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_is_student_form_validated(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $newStudent = User::factory()->make(['user_type' => 'student']);


        $this->actingAs($user);

        $this->get(StudentResource::getUrl('index'))->assertSuccessful();
        $this->get(StudentResource::getUrl('create'))->assertSuccessful();

        Livewire::test(StudentResource\Pages\CreateStudent::class)
            ->fillForm([
                'user.id' => null,
                'user.user_name' => null,
                'user.first_name' => 123,
                'user.surname' => 433,
                'user.email' => null,
                'user.password' => null,
            ])
            ->call('create')
            ->assertHasFormErrors();
    }

    public function test_can_project_supervisor_be_created(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $newProjectSupervisor = User::factory()->make(['user_type' => 'project_supervisor']);

        $this->actingAs($user);

        $this->get(ProjectSupervisorResource::getUrl('index'))->assertSuccessful();
        $this->get(ProjectSupervisorResource::getUrl('create'))->assertSuccessful();

        Livewire::test(ProjectSupervisorResource\Pages\CreateProjectSupervisor::class)
            ->fillForm([
                'user.id' => $newProjectSupervisor->id,
                'user.user_name' => $newProjectSupervisor->user_name,
                'user.first_name' => $newProjectSupervisor->first_name,
                'user.surname' => $newProjectSupervisor->surname,
                'user.email' => $newProjectSupervisor->email,
                'user.password' => 'password',
                'max_student_assign' => 5,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(User::class, [
            'user_name' => $newProjectSupervisor->user_name,
            'first_name' => $newProjectSupervisor->first_name,
            'surname' => $newProjectSupervisor->surname,
            'email' => $newProjectSupervisor->email,
        ]);

        $this->assertDatabaseHas(ProjectSupervisor::class, [
            'max_student_assign' => 5,
        ]);


        //check the password in a seprate column as since their hashed
        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_is_project_supervisor_form_validated(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $newStudent = User::factory()->make(['user_type' => 'project_supervisor']);


        $this->actingAs($user);

        $this->get(ProjectSupervisorResource::getUrl('index'))->assertSuccessful();
        $this->get(ProjectSupervisorResource::getUrl('create'))->assertSuccessful();

        Livewire::test(ProjectSupervisorResource\Pages\CreateProjectSupervisor::class)
            ->fillForm([
                'user.id' => null,
                'user.user_name' => null,
                'user.first_name' => 123,
                'user.surname' => 433,
                'user.email' => null,
                'user.password' => null,
                'max_student_assign' => 'not a int'
            ])
            ->call('create')
            ->assertHasFormErrors();
    }

    public function test_can_module_leader_be_created(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $newModuleLeader = User::factory()->make(['user_type' => 'module_leader']);

        $this->actingAs($user);

        $this->get(ProjectSupervisorResource::getUrl('index'))->assertSuccessful();
        $this->get(ProjectSupervisorResource::getUrl('create'))->assertSuccessful();

        Livewire::test(ModuleLeaderResource\Pages\CreateModuleLeader::class)
            ->fillForm([
                'user.id' => $newModuleLeader->id,
                'user.user_name' => $newModuleLeader->user_name,
                'user.first_name' => $newModuleLeader->first_name,
                'user.surname' => $newModuleLeader->surname,
                'user.email' => $newModuleLeader->email,
                'user.password' => 'password',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(User::class, [
            'user_name' => $newModuleLeader->user_name,
            'first_name' => $newModuleLeader->first_name,
            'surname' => $newModuleLeader->surname,
            'email' => $newModuleLeader->email,
        ]);


        //check the password in a seprate column as since their hashed
        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_is_module_leader_form_validated(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);


        $this->actingAs($user);

        $this->get(ProjectSupervisorResource::getUrl('index'))->assertSuccessful();
        $this->get(ProjectSupervisorResource::getUrl('create'))->assertSuccessful();

        Livewire::test(ModuleLeaderResource\Pages\CreateModuleLeader::class)
            ->fillForm([
                'user.id' => null,
                'user.user_name' => 'admin',
                'user.first_name' => 123,
                'user.surname' => 433,
                'user.email' => null,
                'user.password' => null,
            ])
            ->call('create')
            ->assertHasFormErrors();
    }

    public function test_can_admin_be_created(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $newAdmin = User::factory()->make(['user_type' => 'admin']);

        $this->actingAs($user);

        $this->get(AdminResource::getUrl('index'))->assertSuccessful();
        $this->get(AdminResource::getUrl('create'))->assertSuccessful();

        Livewire::test(AdminResource\Pages\CreateAdmin::class)
            ->fillForm([
                'user.id' => $newAdmin->id,
                'user.user_name' => $newAdmin->user_name,
                'user.first_name' => $newAdmin->first_name,
                'user.surname' => $newAdmin->surname,
                'user.email' => $newAdmin->email,
                'user.password' => 'password',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(User::class, [
            'user_name' => $newAdmin->user_name,
            'first_name' => $newAdmin->first_name,
            'surname' => $newAdmin->surname,
            'email' => $newAdmin->email,
            'user_type' => $newAdmin->user_type,
        ]);


        //check the password in a seprate column as since their hashed
        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_is_admin_form_validated(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);


        $this->actingAs($user);

        $this->get(AdminResource::getUrl('index'))->assertSuccessful();
        $this->get(AdminResource::getUrl('create'))->assertSuccessful();

        Livewire::test(AdminResource\Pages\CreateAdmin::class)
            ->fillForm([
                'user.id' => null,
                'user.user_name' => 'admin',
                'user.first_name' => 123,
                'user.surname' => 433,
                'user.email' => null,
                'user.password' => null,
            ])
            ->call('create')
            ->assertHasFormErrors();
    }

    public function test_does_edit_user_fill_with_data(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $userToEdit = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->seed();

        $this->actingAs($user);

        Livewire::test(UserResource\Pages\EditUser::class, [
            'record' => $userToEdit->getRouteKey(),
        ])
            ->assertFormSet([
                'user_name' => $userToEdit->user_name,
                'first_name' => $userToEdit->first_name,
                'surname' => $userToEdit->surname,
                'user_type' => $userToEdit->user_type,
                'id' => $userToEdit->projectsupervisor->id,
                'email' => $userToEdit->email,
                'projectsupervisor.max_student_assign' => $userToEdit->projectsupervisor->max_student_assign,

            ])
            ->assertFormFieldIsDisabled('user_type')
            ->assertFormFieldIsDisabled('id');
    }

    public function test_does_edit_save_data(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $userToEdit = User::factory()->create(['user_type' => 'project_supervisor']);


        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->seed();

        $newData = User::factory()->make(['user_type' => 'project_supervisor']);

        $this->actingAs($user);

        Livewire::test(UserResource\Pages\EditUser::class, [
            'record' => $userToEdit->getRouteKey(),
        ])
            ->fillForm([
                'user_name' => $newData->user_name,
                'first_name' => $newData->first_name,
                'surname' => $newData->surname,
                'email' => $newData->email,
                'projectsupervisor.max_student_assign' => 5,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $userToEdit->refresh();

        $this->assertSame($userToEdit->user_name, $newData->user_name);
        $this->assertSame($userToEdit->first_name, $newData->first_name);
        $this->assertSame($userToEdit->surname, $newData->surname);
        $this->assertSame(5, $userToEdit->projectsupervisor->max_student_assign);
    }

    public function test_does_relation_manager_render(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $interest = User::factory()
            ->has(Interest::factory()->count(2))->create();


        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        Livewire::test(UserResource\RelationManagers\ProjectRelationManager::class, [
            'ownerRecord' => $interest,
            'pageClass' => UserResource\Pages\EditUser::class,
        ])
            ->assertSuccessful();
    }

    public function test_is_edit_data_valid(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $userToEdit = User::factory()->create(['user_type' => 'project_supervisor']);


        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->seed();

        $this->actingAs($user);

        Livewire::test(UserResource\Pages\EditUser::class, [
            'record' => $userToEdit->getRouteKey(),
        ])
            ->fillForm([
                'user_name' => null,
                'first_name' => 2,
                'surname' => 3,
                'email' => null,
                'projectsupervisor.max_student_assign' => 'not an int',
            ])
            ->call('save')
            ->assertHasFormErrors();
    }

    public function test_can_delete_record(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $userToRemove = User::factory()->create(['user_type' => 'project_supervisor']);


        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->seed();

        $this->actingAs($user);

        Livewire::test(UserResource\Pages\ListUsers::class)
            ->callTableAction(DeleteAction::class, $userToRemove);

        $this->assertModelMissing($userToRemove);
    }

    public function test_can_delete_bulk_record(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $usersToRemove = User::factory(10)->create();


        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        Livewire::test(UserResource\Pages\ListUsers::class)
            ->callTableBulkAction(DeleteBulkAction::class, $usersToRemove);

        foreach ($usersToRemove as $user) {
            $this->assertModelMissing($user);
        }
    }

}
