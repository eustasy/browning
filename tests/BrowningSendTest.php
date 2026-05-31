<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests;

use Eustasy\Browning\Tests\Fixtures\MockHttpServer;
use PHPUnit\Framework\TestCase;

/**
 * Covers Browning()'s send paths against a local mock Mailgun endpoint.
 *
 * $Browning['URL'] is pointed at the mock server; the router branches on the
 * posted `subject` to drive each response branch in the function.
 */
final class BrowningSendTest extends TestCase
{
    private MockHttpServer $server;

    protected function setUp(): void
    {
        global $Browning;

        $this->server = new MockHttpServer();

        $Browning = [
            'URL' => $this->server->url(),
            'Key' => 'key-test',
            'Default' => [
                'Regards' => 'Default Sender',
                'ReplyTo' => 'default@example.com',
            ],
        ];
    }

    protected function tearDown(): void
    {
        $this->server->stop();

        global $Browning;
        $Browning = [];
    }

    public function testSuccessfulSendReturnsSuccessTrue(): void
    {
        $result = \Browning('to@example.com', 'Hello', 'Body');

        $this->assertTrue($result['Success']);
        $this->assertFalse($result['Error']);
    }

    public function testWhitespaceSubjectPassesValidationAndSends(): void
    {
        // empty(' ') === false, so a space reaches the server and sends.
        $result = \Browning('to@example.com', ' ', 'Body');

        $this->assertTrue($result['Success']);
    }

    public function testForbiddenResponseReturnsFriendlyError(): void
    {
        $result = \Browning('to@example.com', 'FORBIDDEN', 'Body');

        $this->assertFalse($result['Success']);
        $this->assertStringContainsString('unable to send', strtolower((string) $result['Error']));
    }

    public function testForbiddenResponseInDebugModeReturnsTechnicalDetail(): void
    {
        $result = \Browning('to@example.com', 'FORBIDDEN', 'Body', false, false, true);

        $this->assertFalse($result['Success']);
        $this->assertStringContainsString('Forbidden', (string) $result['Error']);
    }

    public function testEmptyResponseReturnsError(): void
    {
        $result = \Browning('to@example.com', 'EMPTY', 'Body');

        $this->assertFalse($result['Success']);
        $this->assertIsString($result['Error']);
    }

    public function testEmptyResponseInDebugModeReturnsTechnicalDetail(): void
    {
        $result = \Browning('to@example.com', 'EMPTY', 'Body', false, false, true);

        $this->assertFalse($result['Success']);
        $this->assertSame('No response received from mail server.', $result['Error']);
    }

    public function testTransportErrorReturnsError(): void
    {
        global $Browning;
        $Browning['URL'] = 'http://127.0.0.1:1'; // connection refused

        $result = \Browning('to@example.com', 'Hello', 'Body', false, false, true);

        $this->assertFalse($result['Success']);
        $this->assertNotFalse($result['Error']);
    }

    public function testRegardsAndReplyToOverrideDefaults(): void
    {
        \Browning('to@example.com', 'Hello', 'Body', 'Custom Name', 'custom@example.com');

        $last = $this->server->lastRequest();
        $this->assertNotNull($last);
        $this->assertSame('Custom Name <custom@example.com>', $last['post']['from']);
        $this->assertSame('to@example.com', $last['post']['to']);
    }

    public function testFallsBackToDefaultRegardsAndReplyTo(): void
    {
        \Browning('to@example.com', 'Hello', 'Body');

        $last = $this->server->lastRequest();
        $this->assertNotNull($last);
        $this->assertSame('Default Sender <default@example.com>', $last['post']['from']);
    }
}
