<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment\Facades;

use Illuminate\Support\Facades\Facade;
use Hamoi1\FibPayment\Contracts\FibClientInterface;

/**
 * @method static \Hamoi1\FibPayment\Data\PaymentResponse createPayment(\Hamoi1\FibPayment\Data\PaymentRequest $request)
 * @method static \Hamoi1\FibPayment\Data\PaymentStatusInfo getPaymentStatus(string $paymentId)
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
