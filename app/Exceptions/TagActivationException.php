<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class TagActivationException extends RuntimeException
{
    public static function invalidCode(): self
    {
        return new self(__('Código inválido o ya utilizado'));
    }

    public static function alreadyUsed(): self
    {
        return new self(__('Código inválido o ya utilizado'));
    }

    public static function unauthorized(): self
    {
        return new self(__('No autorizado'));
    }

    public static function tooManyAttempts(): self
    {
        return new self(__('Demasiados intentos. Intenta de nuevo en un minuto.'));
    }
}
