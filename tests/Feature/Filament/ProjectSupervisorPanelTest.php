<?php

namespace Feature\Filament;

use App\Filament\Project_supervisor\Resources\ProjectSupervisorResource;
use App\Models\Project;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests for all features on the student panel.
 *
 */
class ProjectSupervisorPanelTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests the following on the project supervisor panel:
     * Can it render
     * does it return a status of 300
     * can all the table columns be rendered
     * does the summarization get rendered
     * @return void
     */
    public function test_are_are_project_supervisor_resource_pages_rendered_and_seen(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $users = User::factory(2)->create(['user_type' => 'student']);

        $this->seed();

        $this->actingAs($user);

        Livewire::test(ProjectSupervisorResource\Pages\ListProjectSupervisors::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('user_name')
            ->assertCanRenderTableColumn('first_name')
            ->assertCanRenderTableColumn('surname')
            ->assertTableColumnExists('user_name')
            ->assertTableColumnExists('first_name')
            ->assertTableColumnExists('surname');

        $this->get(ProjectSupervisorResource::getUrl('index'))->assertSuccessful();
        $this->get(ProjectSupervisorResource::getUrl('projects', ['record' => $user,]))->assertSuccessful();
    }

    public function test_are_are_project_relation_page_column_rendered(): void
    {
        $user = User::factory()->has(Project::factory()->count(2))->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $project = Project::factory(2)->create();

        $this->actingAs($user);

        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->assertSuccessful()
            ->assertCanSeeTableRecords($user->project()->get())
            ->assertCanRenderTableColumn('student.user.user_name')
            ->assertCanRenderTableColumn('student.user.first_name')
            ->assertCanRenderTableColumn('student.user.surname')
            ->assertCanRenderTableColumn('project_name');
    }


    public function test_are_are_project_relation_editable(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $project = Project::factory()->recycle($user)->create();


        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->callTableAction('edit', $project, [
                'project_name' => 'new project name',
            ]);
        $this->assertDatabaseHas(Project::class, [
            'project_name' => 'new project name',
        ]);
    }

    public function test_are_project_relation_edit_valid(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $project = Project::factory()->recycle($user)->create();

        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->mountTableAction(EditAction::class, $project)
            ->assertTableActionDataSet([
                'project_supervisor_id' => $user->projectsupervisor->id,
                'student_id' => $project->student->id,
                'project_name' => $project->project_name,
            ])
            ->callTableAction('edit', $project, [
                'project_name' => null,
            ])
            ->assertHasFormComponentActionErrors();
        $before = $project->project_name;
        $project->refresh();
        $this->assertSame($before, $project->project_name);
    }

    public function test_can_project_be_created(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $this->seed();

        $student = Student::factory()->create()->id;

        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->callTableAction('create', null, [
                'project_supervisor_id' => $user->projectsupervisor->id,
                'student_id' => $student,
                'project_name' => 'new project name'
            ]);
        $this->assertDatabaseHas(Project::class, [
            'project_supervisor_id' => $user->projectsupervisor->id,
            'student_id' => $student,
            'project_name' => 'new project name',
        ]);
    }

    public function test_does_project_form_create_validate(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $this->seed();

        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->callTableAction('create', null, [
                'project_supervisor_id' => $user->projectsupervisor->id,
                'student_id' => 'not an int',
                'project_name' => null
            ])
            ->assertHasFormComponentActionErrors();
        $this->assertDatabaseMissing(Project::class, [
            'project_supervisor_id' => $user->projectsupervisor->id,
            'student_id' => 'not an int',
            'project_name' => null,
        ]);
    }

    public function test_can_project_supervisor_not_add_student_when_full(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $this->seed();

        Project::factory(3)->recycle($user->projectsupervisor)->create();

        $student = Student::factory()->create();

        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->callTableAction('create', null, [
                'project_supervisor_id' => $user->projectsupervisor->id,
                'student_id' => $student->getKey(),
                'project_name' => 'another new project'
            ]);

        $this->assertDatabaseMissing(Project::class, [
            'project_supervisor_id' => $user->projectsupervisor->id,
            'student_id' => $student->getKey(),
            'project_name' => 'another new project'
        ]);
    }

    public function test_can_free_student_filter_work(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $student = User::factory(2)->create(['user_type' => 'student']);

        $this->seed();

        Livewire::test(ProjectSupervisorResource\Pages\ListProjectSupervisors::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($student)
            ->filterTable('Free Students')
            ->assertCanSeeTableRecords($student->where('Free Students', true));
    }

    public function test_are_project_relation_deletable(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $project = Project::factory()->recycle($user)->create();


        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->callTableAction(DeleteAction::class, $project);

        $this->assertModelMissing($project);
    }

    public function test_are_project_relation_bulk_deletable(): void
    {
        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $this->actingAs($user);

        $this->seed();

        $projects = Project::factory(3)->recycle($user->projectsupervisor)->create();

        Livewire::test(ProjectSupervisorResource\Pages\ManageProjects::class, [
            'record' => $user->id,
        ])
            ->callTableBulkAction(DeleteBulkAction::class, $projects);

        foreach ($projects as $project) {
            $this->assertModelMissing($project);
        }
    }

}
