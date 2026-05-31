<?php

declare(strict_types=1);

/*
 * Router for the CurlTransport integration test. Echoes back what it received
 * (path, basic-auth credentials, POST fields) as JSON so the test can assert
 * that CurlTransport built the request correctly.
 */

header('Content-Type: application/json');

echo json_encode([
    'path' => parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH),
    'auth_user' => $_SERVER['PHP_AUTH_USER'] ?? null,
    'auth_pw' => $_SERVER['PHP_AUTH_PW'] ?? null,
    'post' => $_POST,
]);
