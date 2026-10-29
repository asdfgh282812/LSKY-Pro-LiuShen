<?php

namespace App\Http\Middleware;

use App\Enums\ConfigKey;
use App\Http\Result;
use App\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsEnableGuestUpload
{
    use Result;

    public function handle(Request $request, Closure $next)
    {
        // 檢測是否啟用了訪客上傳，未啟用、未登入情況下跳轉至登入頁面
        if (! Utils::config(ConfigKey::IsAllowGuestUpload) && Auth::guest()) {
            return redirect('login');
        }

        return $next($request);
    }
}
