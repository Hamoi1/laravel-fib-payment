<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Enums;

enum PaymentStatus: string
{
    case PAID = 'PAID';
    case UNPAID = 'UNPAID';
    case DECLINED = 'DECLINED';
}
