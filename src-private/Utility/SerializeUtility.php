<?php

declare(strict_types=1);

namespace Struct\Struct\Private\Utility;


use Struct\Struct\Contracts\StructInterface;
use Struct\Struct\Exception\InvalidValueException;
use Struct\Struct\Exception\UnexpectedException;
use Struct\Struct\Private\Helper\TransformHelper;
use Struct\DataType\Contracts\DataTypeInterface;

class SerializeUtility
{
    public function serialize(StructInterface $structure): array
    {
        $serializedData = $this->_serialize($structure);
        return $serializedData;
    }

    public function _serialize(StructInterface $structure): array
    {
        $serializedData = [];

        $propertyNames = $this->readPropertyNames($structure);
        foreach ($propertyNames as $propertyName) {
            $value = $structure->$propertyName; // @phpstan-ignore-line
            $formattedValue = $this->formatValue($value);
            if ($formattedValue === null) {
                continue;
            }
            $serializedData[$propertyName] = $formattedValue;
        }

        return $serializedData;
    }

    /**
     * @return string[]
     */
    protected function readPropertyNames(StructInterface $structure): array
    {
        $propertyNames = [];
        try {
            $reflection = new \ReflectionClass($structure);
            // @phpstan-ignore-next-line
        } catch (\ReflectionException $exception) {
            throw new UnexpectedException(651559371, $exception);
        }
        $reflectionProperties = $reflection->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            if ($reflectionProperty->isPublic() === false) {
                throw new InvalidValueException('The property <' . $propertyName . '> must be public', 1651559697);
            }
            $propertyNames[] = $propertyName;
        }
        return $propertyNames;
    }

    protected function formatValue(mixed $value): mixed
    {
        $type = \gettype($value);
        if ($value === null) {
            return null;
        }

        if (
            $type === 'boolean' ||
            $type === 'integer' ||
            $type === 'double' ||
            $type === 'string'
        ) {
            return $value;
        }

        return $this->formatComplexValue($value);
    }

    protected function formatComplexValue(mixed $value): mixed
    {
        if (\is_array($value)) {
            return $this->formatArrayValue($value);
        }

        if ($value instanceof \UnitEnum) {
            return TransformHelper::formatEnum($value);
        }

        if (\is_object($value)) {
            return $this->formatObjectValue($value);
        }

        throw new InvalidValueException('The type of value is not supported', 1651515873);
    }

    protected function formatArrayValue(array $value): array
    {
        $isList = \array_is_list($value);
        $values = [];
        foreach ($value as $key => $item) {
            if ($isList) {
                $values[] = $this->formatValue($item);
            } else {
                $values[$key] = $this->formatValue($item);
            }
        }
        return $values;
    }

    protected function formatObjectValue(object $value): array|string
    {
        if (\is_a($value, \DateTimeInterface::class)) {
            return TransformHelper::formatDateTime($value);
        }
        if (\is_a($value, StructInterface::class)) {
            return $this->_serialize($value);
        }
        if (\is_a($value, DataTypeInterface::class)) {
            return $value->serializeToString();
        }
        throw new InvalidValueException('The type of value is not supported', 1651521990);
    }
}
