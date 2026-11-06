<?php

declare(strict_types=1);

use Modules\Weighing\Drivers\TcpAsciiScaleDriver;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Exceptions\ScaleConnectionException;
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
    ]);
});

describe('TcpAsciiScaleDriver', function (): void {
    test('fails with a domain exception when the host is missing', function (): void {
        (new TcpAsciiScaleDriver)->read(weighingTcpScale(null, 4001));
    })->throws(ScaleConnectionException::class);

    test('fails with a domain exception when the indicator refuses the connection', function (): void {
        [$server, $port] = weighingListeningServer();
        fclose($server);

        (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', $port));
    })->throws(ScaleConnectionException::class);

    test('times out when the indicator sends nothing', function (): void {
        [$server, $port] = weighingListeningServer();

        try {
            (new TcpAsciiScaleDriver)->read(weighingTcpScale('127.0.0.1', $port));
        } finally {
            fclose($server);
        }
    })->throws(ScaleTimeoutException::class);

    test('returns the first stable reading of a continuous stream', function (): void {
        if (! function_exists('pcntl_fork')) {
            test()->markTestSkipped('pcntl non disponibile');
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
