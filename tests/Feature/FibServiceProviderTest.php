<?php

declare(strict_types=1);

use Hamoi1\FibPayment\Facades\Fib;
use Illuminate\Support\ServiceProvider;
use Hamoi1\FibPayment\FibServiceProvider;
use Hamoi1\FibPayment\Services\FibPaymentService;
use Hamoi1\FibPayment\Contracts\FibClientInterface;

it('registers the service provider', function (): void {
    $providers = app()->getProviders(FibServiceProvider::class);

    expect($providers)->toHaveCount(1);
});

it('binds the interface to the service', function (): void {
    $instance = app(FibClientInterface::class);

    expect($instance)->toBeInstanceOf(FibPaymentService::class);
});

it('resolves singleton from container', function (): void {
    $instance1 = app(FibClientInterface::class);
    $instance2 = app(FibClientInterface::class);

    expect($instance1)->toBe($instance2);
});

it('facade resolves correctly', function (): void {
    expect(Fib::getFacadeRoot())->toBeInstanceOf(FibClientInterface::class);
});

it('publishes config file', function (): void {
    $publishes = ServiceProvider::pathsToPublish(FibServiceProvider::class);

    expect($publishes)->not->toBeEmpty();
});
