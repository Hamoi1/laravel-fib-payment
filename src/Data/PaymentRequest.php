<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Data;

use Hamoi1\FibIntegration\Enums\Currency;

readonly class PaymentRequest
{
    public function __construct(
        public float|int $amount,
        public Currency $currency = Currency::IQD,
        public string $description = '',
        public ?string $callbackUrl = null,
    ) {}

    /**
     * Convert request object into FIB create-payment payload.
     *
     * @return array<string, mixed>
     */
    public function toApiPayload(?string $fallbackCallbackUrl = null): array
    {
        return array_filter([
            'monetaryValue' => [
                'amount' => $this->amount,
                'currency' => $this->currency->value,
            ],
            'description' => $this->description,
            'statusCallbackUrl' => $this->callbackUrl ?? $fallbackCallbackUrl,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
