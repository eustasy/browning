<?php

declare(strict_types=1);

/*
 * Router for the CurlTransport integration test. Echoes back what it received
 * (path, basic-auth credentials, POST fields) as JSON so the test can assert
 * that CurlTransport built the request correctly.
 */

header('Content-Type: application/json');

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url(is_string($uri) ? $uri : '/', PHP_URL_PATH);

// /status/<code> answers with that HTTP status, so the transport's status
// capture can be tested.
if (is_string($path) && preg_match('#^/status/(\d{3})$#', $path, $m) === 1) {
    http_response_code((int) $m[1]);
}

echo json_encode([
    'path' => $path,
    'auth_user' => $_SERVER['PHP_AUTH_USER'] ?? null,
    'auth_pw' => $_SERVER['PHP_AUTH_PW'] ?? null,
    'post' => $_POST,
]);
