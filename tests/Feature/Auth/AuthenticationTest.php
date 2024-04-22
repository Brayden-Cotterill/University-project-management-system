<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.user_name', $user->user_name)
            ->set('form.password', 'password');

        $component->call('login');

        //$value = $user->user_type;

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/system/' . $user->user_type->value);

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.user_name', $user->user_name)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/system/' . $user->user_type->value);


        $response
            ->assertOk();

    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        //$this->actingAs($user);

        $component = $this->actingAs($user)->post('/system/' . $user->user_type->value . '/logout');


        $this->assertGuest();
        $component->assertRedirect();

    }
}
