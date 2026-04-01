<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Data;

readonly class PaymentResponse
{
    public function __construct(
        public string $paymentId,
        public string $readableCode,
        public string $qrCode,
        public string $validUntil,
        public string $personalAppLink,
        public string $businessAppLink,
        public string $corporateAppLink,
    ) {}

    /**
     * Map raw FIB payment response to typed object.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromApiResponse(array $payload): self
    {
        return new self(
            paymentId: (string) $payload['paymentId'],
            readableCode: (string) $payload['readableCode'],
            qrCode: (string) $payload['qrCode'],
            validUntil: (string) $payload['validUntil'],
            personalAppLink: (string) $payload['personalAppLink'],
            businessAppLink: (string) $payload['businessAppLink'],
            corporateAppLink: (string) $payload['corporateAppLink'],
        );
    }
}
