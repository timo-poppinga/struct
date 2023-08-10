<?php

declare(strict_types=1);

namespace Struct\Struct\Exception;

use RuntimeException;
use Throwable;

final class UnexpectedException extends RuntimeException
{
    /**
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('This exception should never be thrown, it is a helper to cache exception which cannot be thrown according to the program logic.', $code, $previous);
    }
}
