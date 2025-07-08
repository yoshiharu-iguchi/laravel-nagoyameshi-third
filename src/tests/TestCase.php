<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト時にCSRFミドルウェアを無効化
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }
}
