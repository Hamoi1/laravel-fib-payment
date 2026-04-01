<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment;

use Illuminate\Support\ServiceProvider;
use Hamoi1\FibPayment\Enums\FibEnvironment;
use Hamoi1\FibPayment\Services\FibPaymentService;
use Hamoi1\FibPayment\Contracts\FibClientInterface;

final class FibServiceProvider extends ServiceProvider
{
    /**
     * Register package bindings into the Laravel container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fib.php', 'fib');

        $this->app->singleton(FibClientInterface::class, function ($app): FibPaymentService {
            $config = $app->make('config')->get('fib');

            return new FibPaymentService(
                environment: FibEnvironment::from($config['environment']),
                clientId: $config['client_id'],
                clientSecret: $config['client_secret'],
                callbackUrl: $config['callback_url'],
                timeout: $config['timeout'],
                connectTimeout: $config['connect_timeout'],
                retryTimes: $config['retry_times'],
                retryDelay: $config['retry_delay'],
                cacheDriver: $config['cache_driver'],
            );
        });
    }

    /**
     * Bootstrap publishable package resources.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/fib.php' => config_path('fib.php'),
        ], 'fib-config');
    }
}
