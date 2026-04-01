<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Data;

readonly class PayerInfo
{
    public function __construct(
        public string $name,
        public string $iban,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromApiResponse(array $payload): self
    {
        return new self(
            name: (string) $payload['name'],
            iban: (string) $payload['iban'],
        );
    }
}
