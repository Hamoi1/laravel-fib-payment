<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Data;

use Hamoi1\FibIntegration\Enums\Currency;

readonly class MonetaryValue
{
    public function __construct(
        public float|int $amount,
        public Currency $currency,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromApiResponse(array $payload): self
    {
        return new self(
            amount: (float) $payload['amount'],
            currency: Currency::from((string) $payload['currency']),
        );
    }
}
