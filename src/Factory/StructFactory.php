<?php

declare(strict_types=1);

namespace Struct\Struct\Factory;

use Struct\Contracts\DataType\DataTypeInterface;
use Struct\Contracts\StructInterface;
use Struct\Exception\Serializer\InvalidStructException;
use Struct\Exception\UnexpectedException;
use Struct\Serializer\Private\Helper\StructurePropertyHelper;
use Struct\Struct\Private\Struct\StructureProperty;

class StructFactory
{
    /**
     * @param  class-string<StructInterface> $structureType
     * @return StructInterface
     */
    public static function create(string $structureType): StructInterface
    {
        if (\is_a($structureType, StructInterface::class, true) === false) {
            throw new InvalidStructException('The structureType <' . $structureType . '> must implement the interface <' . StructInterface::class . '>', 1675967937);
        }
        $structure = new $structureType();
        $properties = StructurePropertyHelper::readProperties($structure);
        foreach ($properties as $property) {
            $name = $property->name;
            $structure->$name = self::buildValue($property); // @phpstan-ignore-line
        }
        return $structure;
    }

    /**
     * @param StructureProperty $property
     * @return mixed
     */
    protected static function buildValue(StructureProperty $property): mixed
    {
        if ($property->hasDefaultValue) {
            return $property->defaultValue;
        }
        if ($property->allowsNull) {
            return null;
        }
        $type = $property->type;
        if ($type === 'array') {
            return [];
        }
        if (\is_a($type, StructInterface::class, true) === true) {
            return self::create($type);
        }

        if ($type === 'string') {
            return '';
        }
        if ($type === 'int') {
            return 0;
        }
        if ($type === 'float') {
            return 0.0;
        }
        if ($type === 'bool') {
            return false;
        }

        if (\is_a($type, \DateTimeInterface::class, true) === true) {
            try {
                return new \DateTime('2000-01-01 00:00:00', new \DateTimeZone('UTC'));
            } catch (\Exception $exception) { // @phpstan-ignore-line
                throw new UnexpectedException(1675967987, $exception);
            }
        }
        if (\is_a($type, DataTypeInterface::class, true) === true) {
            $dataType = new $type();
            return $dataType;
        }
        if (\is_a($type, \UnitEnum::class, true) === true) {
            return $type::cases()[0];
        }
        throw new InvalidStructException('The type <' . $type . '> is not supported', 1675967989);
    }
}
