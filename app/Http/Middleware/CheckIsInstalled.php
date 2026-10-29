<?php

namespace App\Http\Middleware;

use App\Http\Result;
use Closure;
use Illuminate\Http\Request;

class CheckIsInstalled
{
    use Result;

    public function handle(Request $request, Closure $next)
    {
        // 檢測程式是否安裝
        if (! file_exists(base_path('installed.lock'))) {
            if (! $request->expectsJson()) {
                return redirect('install');
            } else {
                return $this->fail('It has already been installed.');
            }
        }

        return $next($request);
    }
}
