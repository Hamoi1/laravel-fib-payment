<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Facades;

use Illuminate\Support\Facades\Facade;
use Hamoi1\FibIntegration\Contracts\FibClientInterface;

/**
 * @method static \Hamoi1\FibIntegration\Data\PaymentResponse createPayment(\Hamoi1\FibIntegration\Data\PaymentRequest $request)
 * @method static \Hamoi1\FibIntegration\Data\PaymentStatusInfo getPaymentStatus(string $paymentId)
 * @method static bool cancelPayment(string $paymentId)
 * @method static bool refundPayment(string $paymentId)
 *
 * @see FibClientInterface
 */
final class Fib extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FibClientInterface::class;
    }
}
