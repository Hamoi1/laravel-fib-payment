<?php

declare(strict_types=1);

use Hamoi1\FibIntegration\Enums\PaymentStatus;

it('has correct values', function (): void {
    expect(PaymentStatus::PAID->value)->toBe('PAID')
        ->and(PaymentStatus::UNPAID->value)->toBe('UNPAID')
        ->and(PaymentStatus::DECLINED->value)->toBe('DECLINED');
});

it('can be created from string', function (): void {
    expect(PaymentStatus::from('PAID'))->toBe(PaymentStatus::PAID)
        ->and(PaymentStatus::from('UNPAID'))->toBe(PaymentStatus::UNPAID)
        ->and(PaymentStatus::from('DECLINED'))->toBe(PaymentStatus::DECLINED);
});
