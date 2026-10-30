<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_avatar_is_generated_locally_without_external_service()
    {
        $user = User::factory()->create(['name' => '王小明']);

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $user->avatar);
        $this->assertStringContainsString('王', base64_decode(substr($user->avatar, strlen('data:image/svg+xml;base64,'))));
    }

    public function test_default_avatar_escapes_the_name()
    {
        $user = User::factory()->create(['name' => '<script>']);

        $svg = base64_decode(substr($user->avatar, strlen('data:image/svg+xml;base64,')));

        $this->assertStringNotContainsString('<script', $svg);
        $this->assertStringContainsString('&lt;', $svg);
    }

    public function test_user_can_upload_and_reset_avatar()
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('settings.avatar.upload'), ['avatar' => UploadedFile::fake()->image('me.png', 600, 300)])
            ->assertJsonPath('status', true);

        $user->refresh();
        Storage::disk('local')->assertExists($user->avatar_path);
        $this->assertStringContainsString("/avatar/{$user->id}", $user->avatar);

        $size = getimagesizefromstring(Storage::disk('local')->get($user->avatar_path));
        $this->assertSame([256, 256], [$size[0], $size[1]]);

        auth()->logout();
        $this->get($user->avatar)->assertOk()->assertHeader('Content-Type', 'image/png');

        $old = $user->avatar_path;
        $this->actingAs($user)->delete(route('settings.avatar.delete'))->assertJsonPath('status', true);

        Storage::disk('local')->assertMissing($old);
        $this->assertNull($user->refresh()->avatar_path);
        $this->assertStringStartsWith('data:image/svg+xml', $user->avatar);
    }

    public function test_non_image_upload_is_rejected()
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('settings.avatar.upload'), ['avatar' => UploadedFile::fake()->create('x.php', 10, 'text/x-php')])
            ->assertJsonPath('status', false);

        $this->assertNull($user->refresh()->avatar_path);
    }

    public function test_user_without_custom_avatar_returns_404()
    {
        $user = User::factory()->create();

        $this->get(route('avatar.show', $user->id))->assertNotFound();
    }

    public function test_guests_cannot_upload_avatar()
    {
        $this->post(route('settings.avatar.upload'), ['avatar' => UploadedFile::fake()->image('me.png')])
            ->assertRedirect(route('login'));
    }
}
