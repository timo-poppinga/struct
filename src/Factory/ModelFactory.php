<?php

declare(strict_types=1);

namespace Struct\Struct\Factory;

use Struct\Contracts\DataType\DataTypeInterface;
use Struct\Contracts\Serialize\SerializableToInt;
use Struct\Contracts\Serialize\SerializableToString;

class ModelFactory
{
    /**
     * @template T of DataTypeInterface
     * @param  class-string<T> $type
     * @return T
     */
    public static function createDataTypeFromString(string $type, string $serializedData): DataTypeInterface
    {
        return self::createFromString($type, $serializedData);
    }

    /**
     * @template T of SerializableToString
     * @param  class-string<T> $type
     * @return T
     */
    public static function createFromString(string $type, string $serializedData): SerializableToString
    {
        $model = new $type();
        $model->deserializeFromString($serializedData);
        return $model;
    }

    /**
     * @template T of SerializableToInt
     * @param  class-string<T> $type
     * @return T
     */
    public static function createFromInt(string $type, int $serializedData): SerializableToInt
    {
        $model = new $type();
        $model->deserializeFromInt($serializedData);
        return $model;
    }
}
