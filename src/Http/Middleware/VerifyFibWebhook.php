<?php

declare(strict_types=1);

namespace Hamoi1\FibIntegration\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Hamoi1\FibIntegration\Enums\PaymentStatus;
use Symfony\Component\HttpFoundation\Response;

final class VerifyFibWebhook
{
    /**
     * Validate webhook payload and optional IP allowlist before request handling.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->has(['paymentId', 'status'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (PaymentStatus::tryFrom((string) $request->input('status')) === null) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $allowedIps = config('fib.allowed_ips', []);
        if ($allowedIps !== [] && $allowedIps !== [''] && ! in_array($request->ip(), $allowedIps, true)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
