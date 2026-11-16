<?php

namespace App\Services;

use App\Models\User;

class JwtService
{
    private string $secret;

    public function __construct(?string $secret = null)
    {
        $this->secret = $secret ?? (string) config('app.key', 'base64:lavero-secret-jwt-key-2026');
    }

    /**
     * Generate a signed HS256 JWT token for a user.
     *
     * @param  array<string, mixed>  $customClaims
     */
    public function generateToken(User $user, int $ttlSeconds = 86400, array $customClaims = []): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $now = time();
        $payload = array_merge([
            'iss' => config('app.url', 'http://localhost'),
            'sub' => (string) $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'iat' => $now,
            'exp' => $now + $ttlSeconds,
        ], $customClaims);

        $headerEncoded = $this->base64UrlEncode((string) json_encode($header));
        $payloadEncoded = $this->base64UrlEncode((string) json_encode($payload));

        $signature = hash_hmac('sha256', "{$headerEncoded}.{$payloadEncoded}", $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
    }

    /**
     * Verify and parse a JWT token. Returns payload array or null on failure.
     *
     * @return array<string, mixed>|null
     */
    public function verifyToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $expectedSignature = hash_hmac('sha256', "{$headerEncoded}.{$payloadEncoded}", $this->secret, true);
        $expectedSignatureEncoded = $this->base64UrlEncode($expectedSignature);

        if (! hash_equals($expectedSignatureEncoded, $signatureEncoded)) {
            return null;
        }

        $payloadJson = $this->base64UrlDecode($payloadEncoded);
        if (! $payloadJson) {
            return null;
        }

        $payload = json_decode($payloadJson, true);
        if (! is_array($payload)) {
            return null;
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null; // Token expired
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string|false
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }
}
