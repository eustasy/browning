<?php

/**
 * Mock HTTP server router for Browning's test suite.
 *
 * Started via `php -S` by {@see \Eustasy\Browning\Tests\Fixtures\MockHttpServer}.
 * It emulates just enough of the Mailgun and Google reCAPTCHA endpoints to
 * exercise every branch of Browning() and Recaptcha_Verify() without touching
 * the real network.
 *
 * Behaviour is selected per request: Mailgun branches on the posted `subject`,
 * reCAPTCHA on the posted `response` token. Every request is appended to the
 * log file named by the MOCK_LOG environment variable so tests can assert on
 * what was actually sent.
 */

$path = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$log = getenv('MOCK_LOG');
if ($log !== false && $log !== '') {
    file_put_contents(
        $log,
        json_encode(['path' => $path, 'post' => $_POST]) . "\n",
        FILE_APPEND | LOCK_EX
    );
}

// Google reCAPTCHA siteverify emulation.
if (strpos($path, 'siteverify') !== false) {
    $token = $_POST['response'] ?? '';

    if ($token === 'NOTJSON') {
        echo 'not json at all';

        return;
    }

    header('Content-Type: application/json');
    if ($token === 'VALID') {
        echo json_encode([
            'success' => true,
            'hostname' => 'test',
            'challenge_ts' => '2026-01-01T00:00:00Z',
        ]);

        return;
    }

    echo json_encode([
        'success' => false,
        'error-codes' => ['invalid-input-response'],
    ]);

    return;
}

// Mailgun /messages emulation.
$subject = $_POST['subject'] ?? '';

if ($subject === 'FORBIDDEN') {
    echo 'Forbidden';

    return;
}

if ($subject === 'EMPTY') {
    // Empty 200 response — exercises Browning's "no response" branch.
    return;
}

header('Content-Type: application/json');
echo json_encode([
    'id' => '<20260101.1@example.com>',
    'message' => 'Queued. Thank you.',
]);
