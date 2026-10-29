<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * 受信任的代理由 TRUSTED_PROXIES 設定（逗號分隔的 IP / CIDR，或 * 信任全部）。
     * 網站直接對外、未經反向代理時請設為空值，避免 X-Forwarded-* 被偽造。
     *
     * @return array<int, string>|string|null
     */
    protected function proxies()
    {
        $proxies = config('app.trusted_proxies', '*');

        if (in_array($proxies, ['*', '**'], true) || ! is_string($proxies)) {
            return $proxies;
        }

        return array_values(array_filter(array_map('trim', explode(',', $proxies)))) ?: null;
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
