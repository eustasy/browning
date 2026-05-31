<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests;

use Eustasy\Browning\Recaptcha;
use Eustasy\Browning\Response;
use Eustasy\Browning\Tests\Support\FakeTransport;
use PHPUnit\Framework\TestCase;

final class RecaptchaTest extends TestCase
{
    public function testValidTokenSucceeds(): void
    {
        $transport = new FakeTransport(new Response('{"success":true,"hostname":"test"}'));

        $result = (new Recaptcha('secret', $transport))->verify('token');

        $this->assertTrue($result->success);
        $this->assertSame([], $result->errorCodes);
        $this->assertNull($result->error);
    }

    public function testInvalidTokenFailsWithErrorCodes(): void
    {
        $transport = new FakeTransport(new Response('{"success":false,"error-codes":["invalid-input-response"]}'));

        $result = (new Recaptcha('secret', $transport))->verify('token');

        $this->assertFalse($result->success);
        $this->assertContains('invalid-input-response', $result->errorCodes);
    }

    public function testNonJsonResponseIsInvalid(): void
    {
        $result = (new Recaptcha('secret', new FakeTransport(new Response('not json'))))->verify('token');

        $this->assertFalse($result->success);
        $this->assertSame('Invalid response from reCAPTCHA server.', $result->error);
    }

    public function testTransportErrorReturnsFriendlyMessage(): void
    {
        $result = (new Recaptcha('secret', new FakeTransport(new Response(null, 7, 'fail'))))->verify('token');

        $this->assertFalse($result->success);
        $this->assertSame('Unable to verify reCAPTCHA at this time.', $result->error);
    }

    public function testTransportErrorInDebugModeReturnsTechnicalDetail(): void
    {
        $result = (new Recaptcha('secret', new FakeTransport(new Response(null, 7, 'fail')), true))->verify('token');

        $this->assertSame('7 Error: fail', $result->error);
    }

    public function testSecretTokenAndRemoteIpAreForwarded(): void
    {
        $transport = new FakeTransport(new Response('{"success":true}'));

        (new Recaptcha('my-secret', $transport))->verify('the-token', '203.0.113.7');

        $call = $transport->lastCall();
        $this->assertSame('https://www.google.com/recaptcha/api/siteverify', $call['url']);
        $this->assertSame('my-secret', $call['fields']['secret']);
        $this->assertSame('the-token', $call['fields']['response']);
        $this->assertSame('203.0.113.7', $call['fields']['remoteip']);
    }

    public function testFromArrayBuildsAWorkingVerifier(): void
    {
        $transport = new FakeTransport(new Response('{"success":true}'));
        $config = ['Recaptcha' => ['SecretKey' => 'cfg-secret']];

        $result = Recaptcha::fromArray($config, $transport)->verify('token');

        $this->assertTrue($result->success);
        $this->assertSame('cfg-secret', $transport->lastCall()['fields']['secret']);
    }
}
