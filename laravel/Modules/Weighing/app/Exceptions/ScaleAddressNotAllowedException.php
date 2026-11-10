<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

/**
 * Host o porta di una bilancia fuori da quanto consentito
 * ({@see \Modules\Weighing\Actions\EnsureScaleAddressAllowedAction}). Il messaggio serve anche come
 * errore di validazione del form, quindi non nomina la bilancia.
 */
class ScaleAddressNotAllowedException extends WeighingException
{
    public static function missingHost(): self
    {
        return self::make('missing_host');
    }

    public static function invalidHost(string $host): self
    {
        return self::make('invalid_host', ['host' => $host]);
    }

    public static function unresolvable(string $host): self
    {
        return self::make('unresolvable', ['host' => $host]);
    }

    public static function notAllowed(string $host): self
    {
        return self::make('not_allowed', ['host' => $host]);
    }

    public static function port(int $port, int $min, int $max): self
    {
        return self::make('port', ['port' => (string) $port, 'min' => (string) $min, 'max' => (string) $max]);
    }

    /**
     * @param  array<string, string>  $replace
     */
    private static function make(string $key, array $replace = []): self
    {
        return new self((string) __('weighing::exceptions.scale_address.'.$key, $replace));
    }
}
