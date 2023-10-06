<?php

declare(strict_types=1);

namespace Struct\Struct\Private\Utility;

use Struct\Contracts\DataType\DataTypeInterface;
use Struct\Contracts\StructInterface;
use Struct\Exception\InvalidStructException;
use Struct\Exception\UnexpectedException;

class HashUtility
{
    public function buildHashString(StructInterface $structure): string
    {
        $hashString = $this->_buildHashString($structure);
        return $hashString;
    }

    protected function _buildHashString(StructInterface $structure): string
    {
        $propertyNames = $this->readPropertyNames($structure);
        \sort($propertyNames);

        $compactString = '';

        foreach ($propertyNames as $propertyName) {
            $value = $structure->$propertyName; // @phpstan-ignore-line
            $valueAsString = $this->valueToString($value);
            $compactString .= $valueAsString . '-';
        }
        return $compactString;
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
            throw new UnexpectedException(1651559371, $exception);
        }
        $reflectionProperties = $reflection->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            if ($reflectionProperty->isPublic() === false) {
                throw new InvalidStructException('The property <' . $propertyName . '> must be public', 1651559697);
            }
            $propertyNames[] = $propertyName;
        }
        return $propertyNames;
    }

    protected function valueToString(mixed $value): string
    {
        $type = \gettype($value);
        if ($value === null) {
            return 'n:null';
        }

        if (
            $type === 'boolean' ||
            $type === 'integer' ||
            $type === 'double' ||
            $type === 'string'
        ) {
            return 's:' . $this->escapeString((string) $value); // @phpstan-ignore-line
        }
        return $this->formatComplexValue($value);
    }

    protected function escapeString(string $input): string
    {
        $output = \str_replace('&', '&#038;', $input);
        $output = \str_replace('-', '&#045;', $output);
        $output = \str_replace(':', '&#058;', $output);
        $output = \str_replace('=', '&#061;', $output);
        return $output;
    }

    protected function formatComplexValue(mixed $value): string
    {
        if (\is_array($value)) {
            return 'a:' . $this->formatArrayValue($value);
        }

        if ($value instanceof \UnitEnum) {
            return 's:' . $this->formatEnum($value);
        }

        if (\is_object($value)) {
            return $this->formatObjectValue($value);
        }

        throw new InvalidStructException('The type of value is not supported', 1651515873);
    }

    protected function formatEnum(\UnitEnum $enum): mixed
    {
        if ($enum instanceof \BackedEnum) {
            return $enum->value;
        }
        return $enum->name;
    }

    /**
     * @param mixed[] $value
     */
    protected function formatArrayValue(array $value): string
    {
        $isList = \array_is_list($value);
        $values = '';
        foreach ($value as $key => $item) {
            if ($isList) {
                $values = 'i:' . $this->valueToString($item);
            } else {
                $values = 'k:' . $key . '=i:' . $this->valueToString($item);
            }
        }
        return $values;
    }

    protected function formatObjectValue(object $value): string
    {
        if (\is_a($value, \DateTimeInterface::class)) {
            return 'd:' . $value->format('c');
        }
        if (\is_a($value, StructInterface::class)) {
            return 'h:' . $this->_buildHashString($value);
        }
        if (\is_a($value, DataTypeInterface::class)) {
            return 's:' . $value->serializeToString();
        }
        throw new InvalidStructException('The type of value is not supported', 1651521990);
    }
}
