<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Contracts;

use Hamoi1\FibIntegration\Data\PaymentRequest;
use Hamoi1\FibIntegration\Data\PaymentResponse;
use Hamoi1\FibIntegration\Data\PaymentStatusInfo;
use Hamoi1\FibIntegration\Exceptions\RateLimitException;
use Hamoi1\FibIntegration\Exceptions\PaymentFailedException;
use Hamoi1\FibIntegration\Exceptions\AuthenticationException;

interface FibClientInterface
{
    /**
     * Create a new payment in FIB.
     *
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function createPayment(PaymentRequest $request): PaymentResponse;

    /**
     * Retrieve payment status by payment id.
     *
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function getPaymentStatus(string $paymentId): PaymentStatusInfo;

    /**
     * Cancel an existing payment.
     *
     * @return bool True when cancellation request is accepted.
     *
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function cancelPayment(string $paymentId): bool;

    /**
     * Refund an existing payment.
     *
     * @return bool True when refund request is accepted.
     *
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function refundPayment(string $paymentId): bool;
}
