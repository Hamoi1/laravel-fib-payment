<?php

declare(strict_types=1);

use Hamoi1\FibPayment\Enums\Currency;
use Hamoi1\FibPayment\Data\PaymentRequest;

it('can be created with defaults', function (): void {
    $request = new PaymentRequest(amount: 100.0);

    expect($request->amount)->toBe(100.0)
        ->and($request->currency)->toBe(Currency::IQD)
        ->and($request->description)->toBe('')
        ->and($request->callbackUrl)->toBeNull();
});

it('can be created with custom values', function (): void {
    $request = new PaymentRequest(
        amount: 50,
        currency: Currency::USD,
        description: 'Test payment',
        callbackUrl: 'http://example.com/callback',
    );

    expect($request->amount)->toBe(50)
        ->and($request->currency)->toBe(Currency::USD)
        ->and($request->description)->toBe('Test payment')
        ->and($request->callbackUrl)->toBe('http://example.com/callback');
});

it('converts to API payload correctly', function (): void {
    $request = new PaymentRequest(
        amount: 100.0,
        currency: Currency::IQD,
        description: 'Test',
    );

    $payload = $request->toApiPayload('https://example.com/fib/webhook');

    expect($payload)->toHaveKeys(['monetaryValue', 'statusCallbackUrl', 'description'])
        ->and($payload['monetaryValue'])->toBe(['amount' => 100.0, 'currency' => 'IQD'])
        ->and($payload['statusCallbackUrl'])->toBe('https://example.com/fib/webhook');
});
