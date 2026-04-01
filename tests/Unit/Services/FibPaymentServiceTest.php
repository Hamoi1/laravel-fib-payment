<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Hamoi1\FibPayment\Enums\Currency;
use Hamoi1\FibPayment\Data\PaymentRequest;
use Hamoi1\FibPayment\Enums\PaymentStatus;
use Hamoi1\FibPayment\Data\PaymentResponse;
use Hamoi1\FibPayment\Data\PaymentStatusInfo;
use Hamoi1\FibPayment\Services\FibPaymentService;
use Hamoi1\FibPayment\Contracts\FibClientInterface;

beforeEach(function (): void {
    config(['fib.environment' => 'stage']);
    config(['fib.client_id' => 'test-client']);
    config(['fib.client_secret' => 'test-secret']);
});

it('resolves from the container', function (): void {
    $service = app(FibClientInterface::class);

    expect($service)->toBeInstanceOf(FibPaymentService::class);
});

it('caches the access token', function (): void {
    Http::fake([
        '*/auth/realms/*/token' => Http::response([
            'access_token' => 'cached-token',
            'expires_in' => 300,
        ]),
        '*/protected/v1/payments' => Http::response([
            'paymentId' => 'pay-123',
            'readableCode' => 'RC123',
            'qrCode' => 'base64data',
            'validUntil' => now()->addHour()->toIso8601String(),
            'personalAppLink' => 'https://fib.iq/personal',
            'businessAppLink' => 'https://fib.iq/business',
            'corporateAppLink' => 'https://fib.iq/corporate',
        ]),
    ]);

    $service = app(FibClientInterface::class);
    $request = new PaymentRequest(amount: 100.0);

    // First call - should fetch token
    $service->createPayment($request);

    // Second call - should use cached token
    $service->createPayment($request);

    Http::assertSentCount(3); // 1 token + 2 payments (token fetched only once)
    expect(Cache::get('fib_oauth_access_token.test-client'))->toBe('cached-token');
});

it('creates payment successfully', function (): void {
    Http::fake([
        '*/auth/realms/*/token' => Http::response([
            'access_token' => 'test-token',
            'expires_in' => 300,
        ]),
        '*/protected/v1/payments' => Http::response([
            'paymentId' => 'pay-123',
            'readableCode' => 'RC123',
            'qrCode' => 'base64data',
            'validUntil' => now()->addHour()->toIso8601String(),
            'personalAppLink' => 'https://fib.iq/personal',
            'businessAppLink' => 'https://fib.iq/business',
            'corporateAppLink' => 'https://fib.iq/corporate',
        ]),
    ]);

    $service = app(FibClientInterface::class);
    $request = new PaymentRequest(
        amount: 100.0,
        currency: Currency::IQD,
        description: 'Test payment',
    );

    $response = $service->createPayment($request);

    expect($response)->toBeInstanceOf(PaymentResponse::class)
        ->and($response->paymentId)->toBe('pay-123')
        ->and($response->readableCode)->toBe('RC123');
});

it('gets payment status successfully', function (): void {
    Http::fake([
        '*/auth/realms/*/token' => Http::response([
            'access_token' => 'test-token',
            'expires_in' => 300,
        ]),
        '*/protected/v1/payments/pay-123/status' => Http::response([
            'paymentId' => 'pay-123',
            'status' => 'PAID',
            'validUntil' => now()->addHour()->toIso8601String(),
            'amount' => [
                'amount' => 100.0,
                'currency' => 'IQD',
            ],
            'paidBy' => [
                'name' => 'John Doe',
                'iban' => 'IQ123456789',
            ],
        ]),
    ]);

    $service = app(FibClientInterface::class);
    $status = $service->getPaymentStatus('pay-123');

    expect($status)->toBeInstanceOf(PaymentStatusInfo::class)
        ->and($status->paymentId)->toBe('pay-123')
        ->and($status->status)->toBe(PaymentStatus::PAID)
        ->and($status->isPaid())->toBeTrue()
        ->and($status->paidBy)->not->toBeNull()
        ->and($status->paidBy->name)->toBe('John Doe');
});
