<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests\Fixtures;

use RuntimeException;

/**
 * Boots a throwaway PHP built-in web server backed by {@see router.php}.
 *
 * Keeping cURL real (rather than stubbing the curl_* functions) means the
 * tests exercise the actual request the functions build, end to end.
 */
final class MockHttpServer
{
    public string $host = '127.0.0.1';

    public int $port;

    public string $logFile;

    /** @var resource|null */
    private $process;

    /** @var array<int, resource> */
    private array $pipes = [];

    public function __construct()
    {
        $this->port = $this->findFreePort();
        $this->logFile = (string) tempnam(sys_get_temp_dir(), 'browning-mock-');

        $command = sprintf(
            '%s -S %s:%d %s',
            escapeshellarg(PHP_BINARY),
            $this->host,
            $this->port,
            escapeshellarg(__DIR__ . '/router.php')
        );

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $env = array_replace(getenv() ?: [], ['MOCK_LOG' => $this->logFile]);

        $process = proc_open($command, $descriptors, $this->pipes, null, $env);
        if (! is_resource($process)) {
            throw new RuntimeException('Unable to start the mock HTTP server.');
        }
        $this->process = $process;

        $this->waitUntilReady();
    }

    /** Absolute URL for a path on the mock server, e.g. url('/messages'). */
    public function url(string $path = ''): string
    {
        return 'http://' . $this->host . ':' . $this->port . $path;
    }

    /**
     * Every request the server has received, oldest first.
     *
     * @return array<int, array{path: string, post: array<string, mixed>}>
     */
    public function requests(): array
    {
        $raw = is_file($this->logFile) ? (string) file_get_contents($this->logFile) : '';

        $requests = [];
        foreach (array_filter(explode("\n", $raw)) as $line) {
            $decoded = json_decode($line, true);
            if (is_array($decoded)) {
                $requests[] = $decoded;
            }
        }

        return $requests;
    }

    /** The most recent request, or null if none has arrived. */
    public function lastRequest(): ?array
    {
        $requests = $this->requests();

        return $requests === [] ? null : end($requests);
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

        if (is_file($this->logFile)) {
            @unlink($this->logFile);
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
