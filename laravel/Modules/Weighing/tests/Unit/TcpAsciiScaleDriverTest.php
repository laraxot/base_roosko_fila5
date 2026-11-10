<?php

declare(strict_types=1);

use Modules\Weighing\Drivers\TcpAsciiScaleDriver;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Exceptions\ScaleAddressNotAllowedException;
use Modules\Weighing\Exceptions\ScaleConnectionException;
use Modules\Weighing\Exceptions\ScaleReadingParseException;
use Modules\Weighing\Exceptions\ScaleTimeoutException;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\fclose;
use function Safe\fwrite;
use function Safe\stream_socket_accept;
use function Safe\stream_socket_get_name;
use function Safe\stream_socket_server;

uses(TestCase::class);

function weighingTcpScale(?string $host, int $port): Scale
{
    return new Scale([
        'code' => 'TCP-TEST',
        'name' => 'TCP test',
        'driver' => ScaleDriverEnum::TCP_ASCII,
        'host' => $host,
        'port' => $port,
        'max_capacity_kg' => '60000.000',
        'resolution_kg' => '20.000',
        'is_active' => true,
    ]);
}

/**
 * @return array{0: resource, 1: int}
 */
function weighingListeningServer(): array
{
    $server = stream_socket_server('tcp://127.0.0.1:0');
    $name = stream_socket_get_name($server, false);

    return [$server, (int) substr($name, strrpos($name, ':') + 1)];
}

beforeEach(function (): void {
    config([
        'weighing.tcp_ascii.connect_timeout' => 1.0,
        'weighing.tcp_ascii.read_timeout' => 0.3,
        'weighing.tcp_ascii.max_lines' => 5,
        'weighing.tcp_ascii.request_command' => null,
        // I server di prova ascoltano su loopback: va consentito esplicitamente.
        'weighing.allowed_scale_hosts' => ['127.0.0.1'],
    ]);
});

describe('TcpAsciiScaleDriver', function (): void {
    test('fails with a domain exception when the host is missing', function (): void {
        TestCase::assertCallbackThrows(ScaleConnectionException::class, function (): void {
            (new TcpAsciiScaleDriver)->read(weighingTcpScale(null, 4001));
        });
    });

    test('fails with a domain exception when the indicator refuses the connection, without the socket error', function (): void {
        [$server, $port] = weighingListeningServer();
        fclose($server);

        try {
            (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', $port));
            Assert::fail('ScaleConnectionException expected');
        } catch (ScaleConnectionException $e) {
            Assert::assertStringNotContainsStringIgnoringCase('refused', $e->getMessage());
        }
    });

    test('never connects to loopback, metadata or public addresses without an allowlist', function (): void {
        $check = function (string $host): void {
            config(['weighing.allowed_scale_hosts' => []]);

            (new TcpAsciiScaleDriver)->read(weighingTcpScale($host, 4001));
        };

        foreach (['127.0.0.1', '169.254.169.254', 'localhost', '8.8.8.8'] as $case) {
            TestCase::assertCallbackThrows(ScaleAddressNotAllowedException::class, static function () use ($check, $case): void {
                $check($case);
            });
        }
    });

    test('rejects ports outside the configured range', function (): void {
        TestCase::assertCallbackThrows(ScaleAddressNotAllowedException::class, function (): void {
            (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', 22));
        });
    });

    test('does not echo unreadable device output in the error message', function (): void {
        if (! function_exists('pcntl_fork')) {
            Assert::markTestSkipped('pcntl non disponibile');
        }

        [$server, $port] = weighingListeningServer();
        $pid = pcntl_fork();
        if ($pid === 0) {
            $client = @stream_socket_accept($server, 2);
            if ($client !== false) {
                fwrite($client, "SECRET-BANNER\r\n");
                usleep(200_000);
                fclose($client);
            }
            exit(0);
        }

        try {
            (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', $port));
            Assert::fail('ScaleReadingParseException expected');
        } catch (ScaleReadingParseException $e) {
            Assert::assertStringNotContainsString('SECRET', $e->getMessage());
        } finally {
            fclose($server);
            pcntl_waitpid($pid, $status);
        }
    });

    test('times out when the indicator sends nothing', function (): void {
        TestCase::assertCallbackThrows(ScaleTimeoutException::class, function (): void {
            [$server, $port] = weighingListeningServer();

            try {
                (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', $port));
            } finally {
                fclose($server);
            }
        });
    });

    test('returns the first stable reading of a continuous stream', function (): void {
        if (! function_exists('pcntl_fork')) {
            Assert::markTestSkipped('pcntl non disponibile');
        }

        [$server, $port] = weighingListeningServer();
        $pid = pcntl_fork();
        if ($pid === 0) {
            $client = stream_socket_accept($server, 2);
            fwrite($client, "US,GS,+  12300 kg\r\nST,GS,+  12340 kg\r\nST,GS,+  12345 kg\r\n");
            usleep(200_000);
            fclose($client);
            exit(0);
        }

        try {
            $reading = (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', $port));
        } finally {
            fclose($server);
            pcntl_waitpid($pid, $status);
        }

        Assert::assertSame(12340.0, $reading->weightKg);
        Assert::assertTrue($reading->stable);
    });
});
