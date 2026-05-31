<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests;

use Eustasy\Browning\CurlTransport;
use Eustasy\Browning\Tests\Support\MockHttpServer;
use PHPUnit\Framework\TestCase;

/**
 * Integration test for the one class that does real I/O. Everything else is
 * unit-tested with FakeTransport.
 */
final class CurlTransportTest extends TestCase
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

    public function testPostSendsFieldsAndReturnsBody(): void
    {
        $response = (new CurlTransport())->post($this->server->url('/messages'), ['from' => 'a@b.c', 'subject' => 'Hi']);

        $this->assertSame(0, $response->errorCode);
        $this->assertFalse($response->failed());
        $this->assertIsString($response->body);

        $echo = $this->decodeJson($response->body);
        $this->assertSame('/messages', $echo['path']);

        $post = $echo['post'];
        $this->assertIsArray($post);
        $this->assertSame('a@b.c', $post['from']);
        $this->assertSame('Hi', $post['subject']);
    }

    public function testBasicAuthIsSent(): void
    {
        $response = (new CurlTransport())->post($this->server->url('/x'), ['a' => 'b'], 'api:key123');

        $echo = $this->decodeJson($response->body);
        $this->assertSame('api', $echo['auth_user']);
        $this->assertSame('key123', $echo['auth_pw']);
    }

    public function testNullFieldsAreOmitted(): void
    {
        $response = (new CurlTransport())->post($this->server->url('/x'), ['kept' => 'yes', 'dropped' => null]);

        $echo = $this->decodeJson($response->body);
        $post = $echo['post'];
        $this->assertIsArray($post);
        $this->assertArrayHasKey('kept', $post);
        $this->assertArrayNotHasKey('dropped', $post);
    }

    public function testTransportErrorIsReported(): void
    {
        // Port 1 refuses the connection.
        $response = (new CurlTransport())->post('http://127.0.0.1:1/x', ['a' => 'b']);

        $this->assertNotSame(0, $response->errorCode);
        $this->assertNull($response->body);
        $this->assertTrue($response->failed());
    }

    /**
     * Decode the echo server's JSON body, asserting it really is an array so
     * the offset accesses above are type-safe.
     *
     * @return array<array-key, mixed>
     */
    private function decodeJson(?string $body): array
    {
        $decoded = json_decode((string) $body, true);
        $this->assertIsArray($decoded);

        return $decoded;
    }
}
