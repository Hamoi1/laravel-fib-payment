<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Enums;

enum DecliningReason: string
{
    case SERVER_FAILURE = 'SERVER_FAILURE';
    case PAYMENT_EXPIRATION = 'PAYMENT_EXPIRATION';
    case PAYMENT_CANCELLATION = 'PAYMENT_CANCELLATION';
}
