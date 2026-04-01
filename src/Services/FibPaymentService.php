<?php

declare(strict_types=1);

namespace Hamoi1\FibPayment\Services;

use Hamoi1\FibPayment\Contracts\FibClientInterface;
use Hamoi1\FibPayment\Data\PaymentRequest;
use Hamoi1\FibPayment\Data\PaymentResponse;
use Hamoi1\FibPayment\Data\PaymentStatusInfo;
use Hamoi1\FibPayment\Enums\FibEnvironment;
use Hamoi1\FibPayment\Exceptions\AuthenticationException;
use Hamoi1\FibPayment\Exceptions\PaymentFailedException;
use Hamoi1\FibPayment\Exceptions\RateLimitException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class FibPaymentService implements FibClientInterface
{
    private const TOKEN_CACHE_KEY = 'fib_oauth_access_token';

    private const TOKEN_CACHE_TTL_PADDING = 60;

    public function __construct(
        private readonly FibEnvironment $environment,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $callbackUrl,
        private readonly int $timeout = 15,
        private readonly int $connectTimeout = 5,
        private readonly int $retryTimes = 3,
        private readonly int $retryDelay = 200,
        private readonly ?string $cacheDriver = null,
    ) {}

    /**
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function createPayment(PaymentRequest $request): PaymentResponse
    {
        $payload = $request->toApiPayload($this->callbackUrl);

        $response = $this->authenticatedRequest()
            ->post($this->baseUrl() . '/protected/v1/payments', $payload);

        $this->handleErrorResponse($response, 'payment creation');

        return PaymentResponse::fromApiResponse($response->json());
    }

    /**
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function getPaymentStatus(string $paymentId): PaymentStatusInfo
    {
        $response = $this->authenticatedRequest()
            ->get($this->baseUrl() . '/protected/v1/payments/' . $paymentId . '/status');

        $this->handleErrorResponse($response, 'payment status check');

        return PaymentStatusInfo::fromApiResponse($response->json());
    }

    /**
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function cancelPayment(string $paymentId): bool
    {
        $response = $this->authenticatedRequest()
            ->post($this->baseUrl() . '/protected/v1/payments/' . $paymentId . '/cancel');

        $this->handleErrorResponse($response, 'payment cancellation');

        return true;
    }

    /**
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    public function refundPayment(string $paymentId): bool
    {
        $response = $this->authenticatedRequest()
            ->post($this->baseUrl() . '/protected/v1/payments/' . $paymentId . '/refund');

        $this->handleErrorResponse($response, 'payment refund');

        return true;
    }

    private function baseUrl(): string
    {
        return $this->environment->baseUrl();
    }

    private function authenticatedRequest(): PendingRequest
    {
        return Http::timeout($this->timeout)
            ->connectTimeout($this->connectTimeout)
            ->retry($this->retryTimes, $this->retryDelay)
            ->withToken($this->getAccessToken());
    }

    /**
     * Resolve or fetch OAuth access token and cache it using expires_in - 60s.
     *
     * @throws AuthenticationException
     */
    private function getAccessToken(): string
    {
        $cacheKey = self::TOKEN_CACHE_KEY . '.' . $this->clientId;
        $cache = $this->cacheDriver !== null ? Cache::store($this->cacheDriver) : Cache::store();
        $cachedToken = $cache->get($cacheKey);
        if ($cachedToken !== null) {
            return $cachedToken;
        }

        $response = Http::timeout($this->timeout)
            ->connectTimeout($this->connectTimeout)
            ->retry($this->retryTimes, $this->retryDelay)
            ->asForm()
            ->post($this->baseUrl() . '/auth/realms/fib-online-shop/protocol/openid-connect/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

        if ($response->failed()) {
            throw new AuthenticationException(
                message: 'Failed to obtain FIB access token: ' . $response->body(),
                code: $response->status(),
            );
        }

        $data = $response->json();
        $token = $data['access_token'];
        $expiresIn = $data['expires_in'] ?? 300;

        $cacheTtl = max(1, $expiresIn - self::TOKEN_CACHE_TTL_PADDING); // Cache the token for its lifetime minus a padding to ensure we refresh before it expires
        $cache->put($cacheKey, $token, $cacheTtl);

        return $token;
    }

    /**
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws PaymentFailedException
     */
    private function handleErrorResponse(Response $response, string $context): void
    {
        if ($response->successful()) {
            return;
        }

        $status = $response->status();
        $body = $response->body();
        $message = "FIB {$context} failed (HTTP {$status}): {$body}";

        throw match (true) {
            $status === 401 || $status === 403 => new AuthenticationException($message, $status),
            $status === 429 => new RateLimitException($message, $status),
            default => new PaymentFailedException($message, $status),
        };
    }
}
