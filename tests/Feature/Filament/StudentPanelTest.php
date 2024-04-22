<?php

namespace Feature\Filament;

use App\Filament\Student\Resources\StudentResource;
use App\Models\Project;
use App\Models\ProjectSupervisor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests for all features on the student panel.
 *
 */
class StudentPanelTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests the following on the student panel:
     * Can it render
     * does it return a status of 300
     * can all the table columns be rendered
     * does the summarization get rendered
     * @return void
     */
    public function test_are_are_student_resource_pages_rendered_and_seen(): void
    {
        $user = User::factory()->create(['user_type' => 'student']);

        $panel = Filament::getPanel('student');
        Filament::setCurrentPanel($panel);

        $users = ProjectSupervisor::factory(3)->create();

        $userHasMaxStudent = ProjectSupervisor::factory(1)->has(Project::factory(3))->create();

        $this->actingAs($user);

        $singleUser = $users->first();

        $this->get(StudentResource::getUrl('index'))->assertSuccessful();
        Livewire::test(StudentResource\Pages\ListStudents::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($users)
            ->assertCanRenderTableColumn('user.first_name')
            ->assertCanRenderTableColumn('user.surname')
            ->assertCanRenderTableColumn('user.email')
            ->assertTableColumnExists('user.first_name')
            ->assertTableColumnExists('user.surname')
            ->assertTableColumnExists('user.email')
            ->filterTable('Free project supervisors')
            ->assertCanSeeTableRecords($users->where('Free project supervisors', true))
            ->assertCanNotSeeTableRecords($userHasMaxStudent->where('Free project supervisors', true));
        /*
         * Cant really test if mailto: link works,
         * as since filament doesnt really support it
         */

    }

}
