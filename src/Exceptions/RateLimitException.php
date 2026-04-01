<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment\Exceptions;

final class RateLimitException extends FibException
{
    public function __construct(string $message = 'FIB API rate limit exceeded', int $code = 429, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
