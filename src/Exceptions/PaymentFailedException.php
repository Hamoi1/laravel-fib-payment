<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment\Exceptions;

final class PaymentFailedException extends FibException
{
    public function __construct(string $message = 'FIB payment operation failed', int $code = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
