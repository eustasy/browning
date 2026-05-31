<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * The default {@see Transport}: a thin cURL POST. This is the whole reason
 * Browning exists — no heavyweight SDK, just curl.
 */
final class CurlTransport implements Transport
{
    public function post(string $url, array $fields, ?string $basicAuth = null): Response
    {
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $this->normalise($fields));

        if ($basicAuth !== null) {
            curl_setopt($handle, CURLOPT_USERPWD, $basicAuth);
            curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        $body = curl_exec($handle);
        $errorCode = curl_errno($handle);
        $errorMessage = curl_error($handle);

        curl_close($handle);

        return new Response(
            is_string($body) ? $body : null,
            $errorCode,
            $errorMessage,
        );
    }

    /**
     * cURL multipart fields must be strings; null fields are simply omitted.
     *
     * @param array<string, string|int|float|bool|null> $fields
     * @return array<string, string>
     */
    private function normalise(array $fields): array
    {
        $normalised = [];
        foreach ($fields as $key => $value) {
            if ($value === null) {
                continue;
            }
            $normalised[$key] = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
        }

        return $normalised;
    }
}
