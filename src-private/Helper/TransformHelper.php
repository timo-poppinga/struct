<?php

declare(strict_types=1);

namespace Struct\Struct\Private\Helper;

use Struct\Struct\Contracts\DataTypeInterface;
use Struct\Struct\Exception\TransformException;

class TransformHelper
{
    public static function formatDateTime(\DateTimeInterface $dateTime): string
    {
        return $dateTime->format('c');
    }

    public static function formatEnum(\UnitEnum $enum): mixed
    {
        if ($enum instanceof \BackedEnum) {
            return $enum->value;
        }
        return $enum->name;
    }

    public static function transformBuildIn(mixed $value, string $toType): mixed
    {
        $valueType = \gettype($value);
        switch ($valueType) {
            case 'string':
                return self::parseString($value, $toType);
            case 'boolean':
                if ($toType === 'bool') {
                    return $value;
                }
                break;
            case 'integer':
                if ($toType === 'int') {
                    return $value;
                }
                if ($toType === 'float') {
                    return (float)$value;  // @phpstan-ignore-line
                }
                break;
            case 'double':
                if ($toType === 'float') {
                    return $value;
                }
                break;
            default:
                throw new TransformException('Can not parse type <' . $valueType . '>', 1675967897);
        }
        throw new TransformException('Can not transform to type <' . $toType . '>', 1675967900);
    }

    protected static function parseString(mixed $value, string $type): string|\DateTimeInterface
    {
        if (\is_string($value) === false) {
            throw new TransformException('The value is not an string', 1675967906);
        }
        if (is_a($type, \DateTimeInterface::class, true)) {
            try {
                return new \DateTime($value);
            } catch (\Exception $exception) {
                throw new TransformException('String <' . $value . '> can not be parsed as DateTime', 1675967909, $exception);
            }
        }
        if ($type === 'string') {
            return $value;
        }
        throw new TransformException('Type of property can not be transformed', 1675967912);
    }
}
