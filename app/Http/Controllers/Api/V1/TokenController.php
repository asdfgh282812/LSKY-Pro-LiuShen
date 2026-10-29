<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OAuth\OAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{
    use InteractsWithTokenAbilities;

    /**
     * 取得當前使用者的 Token 列表
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $tokens = $user->tokens()
            ->select(['id', 'name', 'abilities', 'last_used_at', 'created_at'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities ?: $this->allTokenAbilities(),
                    'ability_groups' => $this->tokenAbilitiesForDisplay($token->abilities),
                    'last_used_at' => $token->last_used_at?->format('Y-m-d H:i:s'),
                    'created_at' => $token->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return $this->success('success', compact('tokens'));
    }

    /**
     * 建立 Token（API 方式，需驗證密碼）
     */
    public function store(Request $request): Response
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'abilities' => 'nullable|array',
                'abilities.*' => ['string', Rule::in($this->allTokenAbilities())],
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User|null $user */
        $user = User::query()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->fail('The email address or password is incorrect.');
        }

        if (app(OAuthService::class)->requiresSsoLogin($user)) {
            return $this->fail('網站已啟用強制 SSO 登入，該帳號請改至網頁端登入後於「API」頁面建立 Token');
        }

        $abilities = $this->normalizedRequestedAbilities($request->input('abilities'));
        $token = $user->createToken($user->email, $abilities)->plainTextToken;

        return $this->success('success', [
            'token' => $token,
            'abilities' => $abilities,
            'ability_groups' => $this->tokenAbilitiesForDisplay($abilities),
        ]);
    }

    /**
     * Web 端建立 Token（已登入使用者，需驗證當前密碼）
     */
    public function createWebToken(Request $request): Response
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:50',
                'password' => 'required|string',
                'abilities' => 'nullable|array',
                'abilities.*' => ['string', Rule::in($this->allTokenAbilities())],
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return $this->fail('密碼錯誤，請重新輸入');
        }

        $name = $request->input('name') ?: $user->email;
        $abilities = $this->normalizedRequestedAbilities($request->input('abilities'));
        $token = $user->createToken($name, $abilities)->plainTextToken;

        return $this->success('建立成功', [
            'token' => $token,
            'abilities' => $abilities,
            'ability_groups' => $this->tokenAbilitiesForDisplay($abilities),
        ]);
    }

    /**
     * 修改 Token 名稱（僅允許修改 name 欄位）
     */
    public function update(Request $request, int $id): Response
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->tokens()->find($id);

        if (! $token) {
            return $this->fail('Token 不存在');
        }

        $token->forceFill([
            'name' => $request->input('name'),
        ])->save();

        return $this->success('修改成功');
    }

    /**
     * 刪除單個 Token（嚴格校驗所有權，需驗證當前密碼）
     */
    public function destroy(Request $request, int $id): Response
    {
        try {
            $request->validate([
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return $this->fail('密碼錯誤，請重新輸入');
        }

        $token = $user->tokens()->find($id);

        if (! $token) {
            return $this->fail('Token 不存在');
        }

        $token->delete();

        return $this->success('刪除成功');
    }

    /**
     * 清空當前使用者的所有 Token
     */
    public function clear(): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->success();
    }
}
