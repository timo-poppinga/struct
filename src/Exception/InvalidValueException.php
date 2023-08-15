<?php

declare(strict_types=1);

namespace Struct\Struct\Exception;

use LogicException;
use Throwable;

final class InvalidValueException extends LogicException
{
    public function __construct(string $message, int $code, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
