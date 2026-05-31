<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests;

use Eustasy\Browning\Mailer;
use Eustasy\Browning\Response;
use Eustasy\Browning\Tests\Support\FakeTransport;
use PHPUnit\Framework\TestCase;

final class MailerTest extends TestCase
{
    private function mailer(FakeTransport $transport, bool $debug = false): Mailer
    {
        return new Mailer('https://mailgun.test/v3/dom', 'key-test', 'Default Sender', 'default@example.com', $transport, $debug);
    }

    public function testSuccessfulSendBuildsTheExpectedRequest(): void
    {
        $transport = new FakeTransport(new Response('{"id":"<x@dom>","message":"Queued. Thank you."}'));

        $result = $this->mailer($transport)->send('to@example.com', 'Hello', 'Body');

        $this->assertTrue($result->success);
        $this->assertNull($result->error);

        $call = $transport->lastCall();
        $this->assertSame('https://mailgun.test/v3/dom/messages', $call['url']);
        $this->assertSame('api:key-test', $call['basicAuth']);
        $this->assertSame('to@example.com', $call['fields']['to']);
        $this->assertSame('Hello', $call['fields']['subject']);
        $this->assertSame('Body', $call['fields']['text']);
        $this->assertSame('Default Sender <default@example.com>', $call['fields']['from']);
    }

    public function testFromNameAndAddressOverrideTheDefaults(): void
    {
        $transport = new FakeTransport(new Response('ok'));

        $this->mailer($transport)->send('to@example.com', 'Hi', 'Body', 'Custom Name', 'custom@example.com');

        $this->assertSame('Custom Name <custom@example.com>', $transport->lastCall()['fields']['from']);
    }

    public function testMissingRecipientReturnsSingularError(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('ok')))->send('', 'Subject', 'Body');

        $this->assertFalse($result->success);
        $this->assertSame('Please provide the following required field: recipient email address.', $result->error);
    }

    public function testMissingTwoFieldsReturnsPluralError(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('ok')))->send('', '', 'Body');

        $this->assertSame('Please provide the following required fields: recipient email address, subject.', $result->error);
    }

    public function testMissingAllFieldsAreListedInOrder(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('ok')))->send('', '', '');

        $this->assertSame('Please provide the following required fields: recipient email address, subject, message body.', $result->error);
    }

    public function testForbiddenResponseReturnsFriendlyError(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('Forbidden')))->send('to@example.com', 'Hi', 'Body');

        $this->assertFalse($result->success);
        $this->assertSame('This website is unable to send email.', $result->error);
    }

    public function testForbiddenResponseInDebugModeReturnsTechnicalDetail(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('Forbidden')), true)->send('to@example.com', 'Hi', 'Body');

        $this->assertSame('Forbidden: Check your Mailgun configuration and API key.', $result->error);
    }

    public function testEmptyResponseReturnsFriendlyError(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('')))->send('to@example.com', 'Hi', 'Body');

        $this->assertFalse($result->success);
        $this->assertSame('Unable to send email at this time. Please try again later.', $result->error);
    }

    public function testEmptyResponseInDebugModeReturnsTechnicalDetail(): void
    {
        $result = $this->mailer(new FakeTransport(new Response('')), true)->send('to@example.com', 'Hi', 'Body');

        $this->assertSame('No response received from mail server.', $result->error);
    }

    public function testTransportErrorReturnsFriendlyError(): void
    {
        $result = $this->mailer(new FakeTransport(new Response(null, 7, 'Connection refused')))->send('to@example.com', 'Hi', 'Body');

        $this->assertFalse($result->success);
        $this->assertSame('Unable to send email at this time. Please try again later.', $result->error);
    }

    public function testTransportErrorInDebugModeReturnsTechnicalDetail(): void
    {
        $result = $this->mailer(new FakeTransport(new Response(null, 7, 'Connection refused')), true)->send('to@example.com', 'Hi', 'Body');

        $this->assertSame('7 Error: Connection refused', $result->error);
    }

    public function testFromArrayBuildsAWorkingMailer(): void
    {
        $transport = new FakeTransport(new Response('ok'));
        $config = ['URL' => 'https://mg.test/v3/d', 'Key' => 'k', 'Default' => ['Regards' => 'Name', 'ReplyTo' => 'r@e.com']];

        $result = Mailer::fromArray($config, $transport)->send('to@example.com', 'Hi', 'Body');

        $this->assertTrue($result->success);
        $this->assertSame('https://mg.test/v3/d/messages', $transport->lastCall()['url']);
        $this->assertSame('api:k', $transport->lastCall()['basicAuth']);
        $this->assertSame('Name <r@e.com>', $transport->lastCall()['fields']['from']);
    }
}
