<?php

namespace Tests\Feature;

use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class TrustProxiesTest extends TestCase
{
    use CreatesApplication;

    private function resolveIp(?string $trusted): string
    {
        config(['app.trusted_proxies' => $trusted]);

        $request = Request::create('/', 'GET', server: [
            'REMOTE_ADDR' => '203.0.113.9',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.7',
        ]);

        (new TrustProxies())->handle($request, fn () => response('ok'));

        return $request->ip();
    }

    public function test_forwarded_headers_are_ignored_when_no_proxy_is_trusted()
    {
        $this->assertSame('203.0.113.9', $this->resolveIp(''));
    }

    public function test_forwarded_headers_are_honoured_for_listed_proxies_only()
    {
        $this->assertSame('198.51.100.7', $this->resolveIp('203.0.113.0/24'));
        $this->assertSame('203.0.113.9', $this->resolveIp('10.0.0.1'));
    }

    public function test_wildcard_trusts_every_proxy()
    {
        $this->assertSame('198.51.100.7', $this->resolveIp('*'));
    }
}
