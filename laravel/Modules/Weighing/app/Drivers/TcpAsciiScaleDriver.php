<?php

declare(strict_types=1);

namespace Modules\Weighing\Drivers;

use Illuminate\Support\Facades\Log;
use Modules\Weighing\Actions\EnsureScaleAddressAllowedAction;
use Modules\Weighing\Actions\ParseScaleReadingAction;
use Modules\Weighing\Contracts\ScaleDriverContract;
use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Exceptions\ScaleConnectionException;
use Modules\Weighing\Exceptions\ScaleReadingParseException;
use Modules\Weighing\Exceptions\ScaleTimeoutException;
use Modules\Weighing\Models\Scale;
use Throwable;

use function Safe\fclose;
use function Safe\fwrite;
use function Safe\stream_set_timeout;
use function Safe\stream_socket_client;

/**
 * Indicatore di peso in rete che parla ASCII su TCP (anche tramite convertitore seriale/ethernet).
 *
 * Apre il socket (timeout di connessione), invia l'eventuale `request_command`, legge fino a
 * `max_lines` righe entro `read_timeout` e restituisce la prima lettura stabile; se non ne arriva
 * nessuna restituisce l'ultima lettura valida (non stabile). Errori come eccezioni di dominio:
 * host mancante o irraggiungibile → ScaleConnectionException, nessun dato → ScaleTimeoutException,
 * solo righe illeggibili → ScaleReadingParseException.
 *
 * Prima di aprire il socket l'indirizzo passa da {@see EnsureScaleAddressAllowedAction} (rete locale o
 * lista consentita, porta nell'intervallo) e ci si collega all'IP controllato. Errori del socket e righe
 * illeggibili vanno nel log, non nei messaggi all'operatore.
 */
class TcpAsciiScaleDriver implements ScaleDriverContract
{
    private const int MAX_LINE_LENGTH = 256;

    public function read(Scale $scale): ScaleReadingData
    {
        $host = $scale->host !== null ? trim($scale->host) : '';
        if ($host === '') {
            throw ScaleConnectionException::missingHost($scale);
        }
        $port = $scale->port ?? config()->integer('weighing.tcp_ascii.default_port', 4001);
        $ip = app(EnsureScaleAddressAllowedAction::class)->execute($host, $port);

        $connectTimeout = config()->float('weighing.tcp_ascii.connect_timeout', 2.0);
        $readTimeout = config()->float('weighing.tcp_ascii.read_timeout', 3.0);
        $maxLines = max(1, config()->integer('weighing.tcp_ascii.max_lines', 10));
        $command = config('weighing.tcp_ascii.request_command');

        $address = sprintf('tcp://%s:%d', str_contains($ip, ':') ? '['.$ip.']' : $ip, $port);
        $errno = 0;
        $errstr = '';
        try {
            $socket = stream_socket_client($address, $errno, $errstr, $connectTimeout);
        } catch (Throwable $e) {
            Log::warning('Weighing: bilancia non raggiungibile', [
                'scale' => $scale->code,
                'address' => $address,
                'errno' => $errno,
                'error' => $errstr,
                'exception' => $e->getMessage(),
            ]);

            throw ScaleConnectionException::unreachable($scale, $host, $port, is_int($errno) ? $errno : 0);
        }

        $last = null;
        $lastError = null;
        $deadline = microtime(true) + $readTimeout;

        try {
            $seconds = (int) floor($readTimeout);
            stream_set_timeout($socket, $seconds, (int) round(($readTimeout - $seconds) * 1_000_000));

            if (is_string($command) && $command !== '') {
                try {
                    fwrite($socket, $command);
                } catch (Throwable) {
                    throw ScaleConnectionException::closed($scale);
                }
            }

            for ($i = 0; $i < $maxLines && microtime(true) < $deadline; $i++) {
                $line = fgets($socket, self::MAX_LINE_LENGTH);

                if ($line === false) {
                    if (stream_get_meta_data($socket)['timed_out'] || feof($socket)) {
                        break;
                    }

                    continue;
                }

                if (trim($line) === '') {
                    continue;
                }

                try {
                    $reading = app(ParseScaleReadingAction::class)->execute($line);
                } catch (ScaleReadingParseException $e) {
                    Log::info('Weighing: riga della bilancia non interpretabile', ['scale' => $scale->code, 'raw' => $e->raw]);
                    $lastError = $e;

                    continue;
                }

                if ($reading->stable) {
                    return $reading;
                }
                $last = $reading;
            }
        } finally {
            fclose($socket);
        }

        if ($last !== null) {
            return $last;
        }

        throw $lastError ?? ScaleTimeoutException::noData($scale, $readTimeout);
    }
}
