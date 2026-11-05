<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

/**
 * Base delle eccezioni di dominio del modulo: il messaggio e' gia' tradotto e si puo' mostrare all'operatore.
 */
abstract class WeighingException extends \RuntimeException {}
