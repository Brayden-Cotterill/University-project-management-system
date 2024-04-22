<?php

namespace Feature\Filament;

use App\Filament\Module_leader\Resources\ModuleLeaderResource;
use App\Models\Project;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests for all features on the module leader panel.
 *
 */
class ModuleLeaderPanelTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests the following on the module leader panel:
     * Can it render
     * does it return a status of 300
     * can all the table columns be rendered
     * does the summarization get rendered
     * @return void
     */
    public function test_are_are_module_leader_resource_pages_rendered_and_seen(): void
    {
        $user = User::factory()->create(['user_type' => 'module_leader']);

        $panel = Filament::getPanel('moduleleader');
        Filament::setCurrentPanel($panel);

        $users = Project::factory(3)->create();

        $this->actingAs($user);

        $this->get(ModuleLeaderResource::getUrl('index'))->assertSuccessful();
        Livewire::test(ModuleLeaderResource\Pages\ListModuleLeaders::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertTableColumnSummarySet('project_name', 'total_projects', $users->count())
            ->assertCanRenderTableColumn('projectsupervisor.user.first_name')
            ->assertCanRenderTableColumn('student.user.first_name')
            ->assertCanRenderTableColumn('student.user.surname')
            ->assertCanRenderTableColumn('student.user.user_name')
            ->assertTableColumnExists('projectsupervisor.user.first_name')
            ->assertTableColumnExists('student.user.first_name')
            ->assertTableColumnExists('student.user.surname')
            ->assertTableColumnExists('student.user.user_name');
    }

}
