<?php

namespace App\Http\Controllers\User;

use App\Enums\UserConfigKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettingRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as InterventionImage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $configs = $user->group->configs;
        $strategies = $user->group->strategies()->get();
        return view('user.dashboard', compact('strategies', 'configs', 'user'));
    }

    public function settings(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $oauthTableExists = Schema::hasTable('oauth_accounts');
        $oauthAccounts = $oauthTableExists ? $user->oauthAccounts()->get() : collect();

        return view('user.settings', compact('oauthAccounts', 'oauthTableExists'));
    }

    public function update(UserSettingRequest $request): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->validated('name');
        $user->url = $request->validated('url') ?: '';
        $user->configs = $user->configs->merge(collect($request->validated('configs'))->transform(function ($value) {
            return (int)$value;
        }));
        if ($password = $request->validated('password')) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ]);

            event(new PasswordReset($user));
        }
        $user->save();
        return $this->success('儲存成功');
    }

    public function setStrategy(Request $request): Response
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $strategy = $user->group->strategies()->find($request->id)) {
            return $this->fail('沒有找到該策略');
        }
        $user->update(['configs->'.UserConfigKey::DefaultStrategy => $strategy->id]);
        return $this->success('設定成功');
    }

    /**
     * 輸出使用者自訂頭像（公開，畫廊等頁面會顯示他人頭像）
     */
    public function avatar(User $user): BinaryFileResponse
    {
        $disk = Storage::disk('local');

        abort_unless($user->avatar_path && $disk->exists($user->avatar_path), 404);

        return response()->file($disk->path($user->avatar_path), [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function uploadAvatar(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ], [], ['avatar' => '頭像']);

        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        try {
            // 重新編碼為固定尺寸的 PNG，丟棄原檔內容與中繼資料
            $contents = (string) InterventionImage::make($request->file('avatar')->getRealPath())
                ->orientate()
                ->fit(256, 256)
                ->encode('png');
        } catch (\Throwable $e) {
            return $this->fail('無法處理該圖片，請換一張再試');
        }

        $path = 'avatars/'.$user->id.'-'.Str::random(12).'.png';
        Storage::disk('local')->put($path, $contents);

        if ($user->avatar_path) {
            Storage::disk('local')->delete($user->avatar_path);
        }

        $user->forceFill(['avatar_path' => $path])->save();

        return $this->success('頭像已更新', ['avatar' => $user->avatar]);
    }

    public function deleteAvatar(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar_path) {
            Storage::disk('local')->delete($user->avatar_path);
            $user->forceFill(['avatar_path' => null])->save();
        }

        return $this->success('已恢復預設頭像', ['avatar' => $user->avatar]);
    }
}
