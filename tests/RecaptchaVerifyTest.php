<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests;

use Eustasy\Browning\Tests\Fixtures\MockHttpServer;
use PHPUnit\Framework\TestCase;

/**
 * Covers Recaptcha_Verify() against a local mock siteverify endpoint, reached
 * via the optional $Endpoint seam. The router branches on the posted token.
 */
final class RecaptchaVerifyTest extends TestCase
{
    private MockHttpServer $server;

    protected function setUp(): void
    {
        $this->server = new MockHttpServer();
    }

    protected function tearDown(): void
    {
        $this->server->stop();
    }

    private function endpoint(): string
    {
        return $this->server->url('/recaptcha/api/siteverify');
    }

    public function testValidTokenReturnsSuccessTrue(): void
    {
        $result = \Recaptcha_Verify('secret', 'VALID', false, false, $this->endpoint());

        $this->assertTrue($result['Success']);
        $this->assertTrue($result['success']);
    }

    public function testInvalidTokenReturnsSuccessFalse(): void
    {
        $result = \Recaptcha_Verify('secret', 'INVALID', false, false, $this->endpoint());

        $this->assertFalse($result['Success']);
        $this->assertContains('invalid-input-response', (array) $result['error-codes']);
    }

    public function testNonJsonResponseReturnsInvalidError(): void
    {
        $result = \Recaptcha_Verify('secret', 'NOTJSON', false, false, $this->endpoint());

        $this->assertFalse($result['Success']);
        $this->assertSame('Invalid response from reCAPTCHA server.', $result['Error']);
    }

    public function testTransportErrorReturnsError(): void
    {
        $result = \Recaptcha_Verify('secret', 'VALID', false, false, 'http://127.0.0.1:1/siteverify');

        $this->assertFalse($result['Success']);
        $this->assertNotEmpty($result['Error']);
    }

    public function testDebugModeDumpsResponseAndInfo(): void
    {
        ob_start();
        \Recaptcha_Verify('secret', 'VALID', false, true, $this->endpoint());
        $output = (string) ob_get_clean();

        $this->assertStringContainsString('$Info is', $output);
        $this->assertStringContainsString('$Response is', $output);
    }

    public function testSecretAndUserIpAreForwarded(): void
    {
        \Recaptcha_Verify('my-secret', 'VALID', '203.0.113.7', false, $this->endpoint());

        $last = $this->server->lastRequest();
        $this->assertNotNull($last);
        $this->assertSame('my-secret', $last['post']['secret']);
        $this->assertSame('203.0.113.7', $last['post']['remoteip']);
    }
}
