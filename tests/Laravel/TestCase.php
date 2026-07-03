<?php

declare(strict_types=1);

namespace Tests\Laravel;

use Illuminate\Foundation\Application;
use Mds\Natcash\Laravel\NatcashServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [NatcashServiceProvider::class];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('natcash', [
            'private_key' => 'test-private-key',
            'partner_code' => 'test-partner-code',
            'function_code' => 'test-function-code',
            'username' => 'test-user',
            'password' => 'test-pass',
            'callback_url' => 'https://example.test/callback',
            'enable_fee' => true,
            'language' => 'ht',
            'debug' => true,
        ]);
    }
}
