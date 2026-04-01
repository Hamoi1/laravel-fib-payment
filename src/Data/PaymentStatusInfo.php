<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment\Data;

use Hamoi1\FibPayment\Enums\PaymentStatus;
use Hamoi1\FibPayment\Enums\DecliningReason;

readonly class PaymentStatusInfo
{
    public function __construct(
        public string $paymentId,
        public PaymentStatus $status,
        public string $validUntil,
        public MonetaryValue $amount,
        public ?DecliningReason $decliningReason = null,
        public ?string $declinedAt = null,
        public ?PayerInfo $paidBy = null,
    ) {}

    /**
     * Map raw FIB status response to typed object.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromApiResponse(array $payload): self
    {
        return new self(
            paymentId: (string) $payload['paymentId'],
            status: PaymentStatus::from((string) $payload['status']),
            validUntil: (string) $payload['validUntil'],
            amount: MonetaryValue::fromApiResponse((array) $payload['amount']),
            decliningReason: isset($payload['decliningReason']) ? DecliningReason::tryFrom((string) $payload['decliningReason']) : null,
            declinedAt: isset($payload['declinedAt']) ? (string) $payload['declinedAt'] : null,
            paidBy: isset($payload['paidBy']) ? PayerInfo::fromApiResponse((array) $payload['paidBy']) : null,
        );
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::PAID;
    }

    public function isUnpaid(): bool
    {
        return $this->status === PaymentStatus::UNPAID;
    }

    public function isDeclined(): bool
    {
        return $this->status === PaymentStatus::DECLINED;
    }
}
