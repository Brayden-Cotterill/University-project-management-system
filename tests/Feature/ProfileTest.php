<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\EditProfile;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests the profile page of Filament
 */
class ProfileTest extends TestCase
{
    /*
     * Used DatabaseMigrations trait because of the use of seed
     * Note: this does significantly make the test slower
     */
    use DatabaseMigrations;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/system/' . $user->user_type->value . '/profile');

        $component = Livewire::test(EditProfile::class);

        $response
            ->assertOk();

        $component
            ->assertFormExists();
    }

    public function test_profile_information_can_be_updated(): void
    {

        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        //seed the information
        $this->seed();

        $this->actingAs($user);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $component = Livewire::test(EditProfile::class)
            ->fillForm([
                'first_name' => 'Test',
                'surname' => 'User',
                'email' => 'test@example.com',
                'projectsupervisor.max_student_assign' => 5,
                'interests.id' => 1
            ])
            ->call('save');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect()
            ->assertHasNoFormErrors();

        $user->refresh();

        $this->assertSame('Test', $user->first_name);
        $this->assertSame('User', $user->surname);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame(5, $user->projectsupervisor->max_student_assign);
        foreach ($user->interests as $interest) {
            $same = $interest->pivot->interest_id;
            $this->assertSame(1, $same);
        }
        $this->assertNotNull($user->email_verified_at);
    }

    /*
     * Password update is tested in PasswordUpdateTest.php
     */
    public function test_profile_information_validation_works(): void
    {

        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        $this->actingAs($user);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $component = Livewire::test(EditProfile::class)
            ->fillForm([
                'first_name' => null,
                'surname' => 'User',
                'email' => 1234,
                'projectsupervisor.max_student_assign' => 'not an int',
                'interests.id' => 'not an int'
            ])
            ->call('save');

        $component
            ->assertHasFormErrors();

        $user->refresh();

        /*
         * Check to see if the given updates have not modified
         * the attributes
         */
        $this->assertNotNull($user->first_name);
        $this->assertSame($user->surname, $user->surname);
        $this->assertSame($user->email, $user->email);
        $this->assertEmpty($user->interests);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_profile_information_filled_with_correct_existing_data(): void
    {

        $user = User::factory()->create(['user_type' => 'project_supervisor']);

        //seed the information
        $this->seed();

        $this->actingAs($user);

        $panel = Filament::getPanel('projectsupervisor');
        Filament::setCurrentPanel($panel);

        $component = Livewire::test(EditProfile::class, [
            'record' => $user->getRouteKey(),
        ]);

        $component
            ->assertHasNoErrors()
            ->assertFormSet([
                'first_name' => $user->first_name,
                'surname' => $user->surname,
                'interests' => [],
                'projectsupervisor.max_student_assign' => $user->projectsupervisor->max_student_assign,
            ]);

    }
}
