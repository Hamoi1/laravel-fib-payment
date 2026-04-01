<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment\Tests;

use Hamoi1\FibPayment\FibServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FibServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('fib', [
            'environment' => 'stage',
            'callback_url' => 'http://localhost/fib/callback',
            'client_id' => 'test-client-id',
            'client_secret' => 'test-client-secret',
            'currency' => 'IQD',
            'timeout' => 15,
            'connect_timeout' => 5,
            'retry_times' => 3,
            'retry_delay' => 200,
            'cache_driver' => null,
            'refundable_for' => 'P7D',
            'allowed_ips' => [],
        ]);
    }
}
