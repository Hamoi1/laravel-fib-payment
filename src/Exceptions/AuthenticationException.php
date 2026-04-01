<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Exceptions;

final class AuthenticationException extends FibException
{
    public function __construct(string $message = 'FIB authentication failed', int $code = 401, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
