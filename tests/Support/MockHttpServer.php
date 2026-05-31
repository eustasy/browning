<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests\Support;

use RuntimeException;

/**
 * Boots a throwaway PHP built-in web server running {@see echo-server.php}.
 * Used only to integration-test CurlTransport against real cURL + HTTP.
 */
final class MockHttpServer
{
    public string $host = '127.0.0.1';

    public int $port;

    /** @var resource|null */
    private $process;

    /** @var array<int, resource> */
    private array $pipes = [];

    public function __construct()
    {
        $this->port = $this->findFreePort();

        $command = sprintf(
            '%s -S %s:%d %s',
            escapeshellarg(PHP_BINARY),
            $this->host,
            $this->port,
            escapeshellarg(__DIR__ . '/echo-server.php'),
        );

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptors, $this->pipes);
        if (! is_resource($process)) {
            throw new RuntimeException('Unable to start the mock HTTP server.');
        }
        $this->process = $process;

        $this->waitUntilReady();
    }

    public function url(string $path = ''): string
    {
        return 'http://' . $this->host . ':' . $this->port . $path;
    }

    public function stop(): void
    {
        foreach ($this->pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }
        $this->pipes = [];

        if (is_resource($this->process)) {
            proc_terminate($this->process);
            proc_close($this->process);
            $this->process = null;
        }
    }

    private function findFreePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        if ($socket === false) {
            throw new RuntimeException("Unable to find a free port: {$errstr} ({$errno})");
        }

        $name = (string) stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr($name, (int) strrpos($name, ':') + 1);
    }

    private function waitUntilReady(): void
    {
        $deadline = microtime(true) + 5.0;

        while (microtime(true) < $deadline) {
            $connection = @fsockopen($this->host, $this->port, $errno, $errstr, 0.2);
            if (is_resource($connection)) {
                fclose($connection);

                return;
            }
            usleep(50_000);
        }

        throw new RuntimeException("Mock server never came up on {$this->host}:{$this->port}");
    }
}
