<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ConfigKey;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\OAuthAccount;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\OAuth\OAuthException;
use App\Services\OAuth\OAuthService;
use App\Utils;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect(OAuthService $service): RedirectResponse
    {
        if (! Schema::hasTable('oauth_accounts')) {
            return redirect()->route('login')->withErrors(['email' => 'OAuth 資料表不存在，請先執行資料庫遷移']);
        }

        try {
            return redirect()->away($service->buildAuthorizationUrl('login'));
        } catch (OAuthException $e) {
            return redirect()->route('login')->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function callback(Request $request, OAuthService $service): RedirectResponse
    {
        try {
            $service->validateState($request->query('state'), 'login');
            $tokenPayload = $service->fetchAccessToken((string) $request->query('code'), 'login');
            $profile = $service->normalizeProfile($service->fetchUserProfile((string) ($tokenPayload['access_token'] ?? '')));
        } catch (OAuthException $e) {
            return redirect()->route('login')->withErrors(['email' => $e->getMessage()]);
        }

        $oauthAccount = OAuthAccount::query()
            ->where('provider', $profile['provider'])
            ->where('provider_user_id', $profile['provider_user_id'])
            ->first();

        if ($oauthAccount) {
            $linkedUser = $oauthAccount->user;

            if (! $linkedUser || $linkedUser->status !== UserStatus::Normal) {
                return redirect()->route('login')->withErrors(['email' => '該帳號狀態異常，暫時無法透過 OAuth 登入']);
            }

            $oauthAccount->forceFill($service->syncOauthAccountData($tokenPayload, $profile))->save();
            Auth::login($linkedUser);
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        if (! $service->canRegister()) {
            return redirect()->route('login')->withErrors([
                'email' => '當前網站不允許透過 OAuth 自動註冊，請先使用電子郵件註冊並在個人設定中繫結帳號。',
            ]);
        }

        $email = trim((string) $profile['provider_user_email']);

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('login')->withErrors([
                'email' => 'OAuth 使用者資訊中的電子郵件無效，無法自動註冊，請先使用電子郵件註冊後再繫結。',
            ]);
        }

        if (User::query()->where('email', $email)->exists()) {
            return redirect()->route('login')->withErrors([
                'email' => '該電子郵件已存在，請先登入現有帳號並在個人設定中繫結 OAuth 帳號。',
            ]);
        }

        $name = trim((string) $profile['provider_user_name']);
        $user = DB::transaction(function () use ($request, $profile, $tokenPayload, $service, $email, $name) {
            $user = User::create([
                'name' => $name !== '' ? Str::limit($name, 20, '') : 'OAuth 使用者',
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'registered_ip' => $request->ip(),
            ]);

            $user->oauthAccounts()->create([
                'provider' => $profile['provider'],
                'provider_user_id' => $profile['provider_user_id'],
                ...$service->syncOauthAccountData($tokenPayload, $profile),
            ]);

            if (Utils::config(ConfigKey::IsUserNeedVerify)) {
                event(new Registered($user));
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function bindRedirect(OAuthService $service): RedirectResponse
    {
        if (! $service->isEnabled()) {
            return redirect()->route('settings')->withErrors(['oauth' => '網站管理員關閉了 OAuth 登入功能']);
        }

        if (! Schema::hasTable('oauth_accounts')) {
            return redirect()->route('settings')->withErrors(['oauth' => 'OAuth 資料表不存在，請先執行資料庫遷移']);
        }

        try {
            return redirect()->away($service->buildAuthorizationUrl('bind'));
        } catch (OAuthException $e) {
            return redirect()->route('settings')->withErrors(['oauth' => $e->getMessage()]);
        }
    }

    public function bindCallback(Request $request, OAuthService $service): RedirectResponse
    {
        if (! $service->isEnabled()) {
            return redirect()->route('settings')->withErrors(['oauth' => '網站管理員關閉了 OAuth 登入功能']);
        }

        /** @var User $user */
        $user = Auth::user();

        try {
            $service->validateState($request->query('state'), 'bind');
            $tokenPayload = $service->fetchAccessToken((string) $request->query('code'), 'bind');
            $profile = $service->normalizeProfile($service->fetchUserProfile((string) ($tokenPayload['access_token'] ?? '')));
        } catch (OAuthException $e) {
            return redirect()->route('settings')->withErrors(['oauth' => $e->getMessage()]);
        }

        $exists = OAuthAccount::query()
            ->where('provider', $profile['provider'])
            ->where('provider_user_id', $profile['provider_user_id'])
            ->where('user_id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return redirect()->route('settings')->withErrors(['oauth' => '該 OAuth 帳號已繫結到其他使用者，無法重複繫結']);
        }

        $user->oauthAccounts()->updateOrCreate(
            ['provider' => $profile['provider']],
            [
                'provider_user_id' => $profile['provider_user_id'],
                ...$service->syncOauthAccountData($tokenPayload, $profile),
            ]
        );

        return redirect()->route('settings')->with('oauth_status', 'OAuth 帳號繫結成功');
    }

    public function unbind(Request $request, OAuthAccount $oauthAccount): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'password' => 'required|string',
        ]);

        if ($oauthAccount->user_id !== $user->id) {
            return $this->fail('沒有找到該繫結記錄');
        }

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            return $this->fail('密碼錯誤，請重新輸入');
        }

        $oauthAccount->delete();

        return $this->success('解綁成功');
    }
}
