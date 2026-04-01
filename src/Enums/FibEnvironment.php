<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Enums;

enum FibEnvironment: string
{
    case PRODUCTION = 'prod';
    case STAGING = 'stage';

    public function baseUrl(): string
    {
        return match ($this) {
            self::PRODUCTION => 'https://fib.prod.fib.iq',
            self::STAGING => 'https://fib.stage.fib.iq',
        };
    }
}
