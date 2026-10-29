<?php

namespace Tests\Feature\Auth;

use App\Enums\ConfigKey;
use App\Models\Config;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_bound_users_can_not_login_with_password_when_sso_is_forced()
    {
        $this->forceSso(true);
        $user = User::factory()->create();
        $user->oauthAccounts()->create([
            'provider' => 'custom-oauth',
            'provider_user_id' => 'sso-1',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_unbound_users_can_still_login_with_password_when_sso_is_forced()
    {
        $this->forceSso(true);
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_bound_users_can_login_with_password_when_sso_is_not_forced()
    {
        $this->forceSso(false);
        $user = User::factory()->create();
        $user->oauthAccounts()->create([
            'provider' => 'custom-oauth',
            'provider_user_id' => 'sso-2',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_bound_users_can_not_get_api_token_with_password_when_sso_is_forced()
    {
        $this->forceSso(true);
        $user = User::factory()->create();
        $user->oauthAccounts()->create([
            'provider' => 'custom-oauth',
            'provider_user_id' => 'sso-3',
        ]);

        $this->postJson('/api/v1/tokens', ['email' => $user->email, 'password' => 'password'])
            ->assertJsonPath('status', false);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_unbound_users_can_still_get_api_token_with_password_when_sso_is_forced()
    {
        $this->forceSso(true);
        $user = User::factory()->create();

        $this->postJson('/api/v1/tokens', ['email' => $user->email, 'password' => 'password'])
            ->assertJsonPath('status', true);
    }

    private function forceSso(bool $force): void
    {
        foreach ([ConfigKey::OauthEnable => 1, ConfigKey::OauthForceLogin => (int) $force] as $name => $value) {
            Config::query()->updateOrCreate(['name' => $name], ['value' => (string) $value]);
        }
        Cache::flush();
    }
}
