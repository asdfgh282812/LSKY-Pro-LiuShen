<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SvgUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_svg_with_script_is_rejected_on_upload()
    {
        $file = UploadedFile::fake()->createWithContent('evil.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(document.cookie)</script></svg>');

        $this->actingAs(User::factory()->create())
            ->post('/upload', ['file' => $file], ['Accept' => 'application/json'])
            ->assertJsonPath('status', false);

        $this->assertSame(0, Image::query()->count());
    }
}
