<?php

declare(strict_types=1);

namespace Struct\Struct\Private\Utility;

use Struct\Struct\Contracts\DataTypeInterface;
use Struct\Struct\Contracts\StructInterface;
use Struct\Struct\Exception\InvalidValueException;
use Struct\Struct\Exception\TransformException;
use Struct\Struct\Exception\UnexpectedException;
use Struct\Struct\Private\Enum\SerializeDataType;
use Struct\Struct\Private\Helper\PropertyReflectionHelper;
use Struct\Struct\Private\Helper\TransformHelper;
use Struct\Struct\Private\Struct\PropertyReflection;

class UnSerializeUtility
{
    /**
     * @param class-string<StructInterface> $type
     * @return StructInterface
     */
    public function unSerialize(array|Object $data, string $type): StructInterface
    {
        $structure = $this->_unSerializeStructure($data, $type);
        return $structure;
    }

    protected function _unSerialize(mixed $data, string $type, PropertyReflection $propertyReflection): mixed
    {
        $dataType = $this->_findDataType($data, $type);
        $result = match ($dataType) {
            SerializeDataType::NullType => $this->parseNull($propertyReflection),
            SerializeDataType::EnumType => $this->_unSerializeEnum($data, $type),
            SerializeDataType::StructureType  => $this->_unSerializeStructure($data, $type),
            SerializeDataType::ArrayType => $this->_unSerializeArray($data, $propertyReflection),
            SerializeDataType::DataType => $this->_unSerializeDataType($data, $propertyReflection),
            SerializeDataType::BuildInType => $this->_unSerializeBuildIn($data, $type, $propertyReflection),
        };
        return $result;
    }

    protected function _findDataType(mixed $data, string $type): SerializeDataType
    {
        if ($data === null) {
            return SerializeDataType::NullType;
        }
        if (is_a($type, \UnitEnum::class, true) === true) {
            return SerializeDataType::EnumType;
        }
        if (is_a($type, DataTypeInterface::class, true) === true) {
            return SerializeDataType::DataType;
        }
        if (is_a($type, StructInterface::class, true) === true) {
            //if (\is_array($data) && \count($data) === 0) {
            //    return SerializeDataType::NullType;
            //}
            return SerializeDataType::StructureType;
        }
        if ($type === 'array') {
            return SerializeDataType::ArrayType;
        }
        return SerializeDataType::BuildInType;
    }

    protected function _unSerializeEnum(mixed $data, string $type): \UnitEnum
    {
        if (is_string($data) === false && is_int($data) === false) {
            throw new \LogicException('The value for <' . $data . '> must be string or int', 1652900283);
        }

        if (is_a($type, \BackedEnum::class, true) === true) {
            $enum = $type::tryFrom($data);
            if ($enum === null) {
                throw new \LogicException('The value <' . $data . '> is not allowed for Enum <' . $type . '>', 1652900286);
            }
            return $enum;
        }
        $cases = $type::cases();
        /** @var \UnitEnum $case */
        foreach ($cases as $case) {
            if ($case->name === $data) {
                return $case;
            }
        }
        throw new \LogicException('The value <' . $data . '> is not allowed for Enum <' . $type . '>', 1652899974);
    }

    protected function _unSerializeStructure(mixed $data, string $type): StructInterface
    {
        $dataArray = $this->_transformObjectToArray($data);
        if (is_a($type, StructInterface::class, true) === false) {
            throw new InvalidValueException('The type: <' . $type . '> must implement <' . StructInterface::class . '>', 1652123590);
        }
        $structure = new $type();
        $propertyReflections = PropertyReflectionHelper::readProperties($structure);

        foreach ($propertyReflections as $propertyReflection) {
            $name = $propertyReflection->name;
            $value = null;
            if (\array_key_exists($name, $dataArray) === true) {
                $value = $dataArray[$name];
            }
            $structure->$name = $this->_unSerialize($value, $propertyReflection->type, $propertyReflection);  // @phpstan-ignore-line
        }

        return $structure;
    }

    protected function _transformObjectToArray(mixed $data): array
    {
        if (\is_array($data) === true) {
            return $data;
        }

        if (
            \is_object($data) === true &&
            is_a($data, \DateTimeInterface::class) === false
        ) {
            $dataArray = [];
            $dataArrayTransform = (array) $data;
            foreach ($dataArrayTransform as $key => $value) {
                if (is_a($value, \DateTimeInterface::class)) {
                    $value = TransformHelper::formatDateTime($value);
                }
                if ($value instanceof \UnitEnum) {
                    $value = TransformHelper::formatEnum($value);
                }
                $dataArray[$key] = $value;
            }
            return $dataArray;
        }
        throw new UnexpectedException(1676979096);
    }

    protected function _unSerializeDataType(string|\Stringable $serializedData, PropertyReflection $propertyReflection): DataTypeInterface
    {
        $serializedData = (string) $serializedData;
        /** @var DataTypeInterface $type */
        $type = $propertyReflection->type;
        $dataType = $type::deserializeToString($serializedData);
        return $dataType;
    }

    protected function _unSerializeArray(mixed $dataArray, PropertyReflection $propertyReflection): array
    {
        if (\is_array($dataArray) === false) {
            throw new UnexpectedException(1675967242);
        }
        $type = $propertyReflection->arrayValueType;
        if ($type === null) {
            throw new InvalidValueException('The array type of property <' . $propertyReflection->name . '> must be declared', 1675967245);
        }
        $parsedOutput = [];
        foreach ($dataArray as $key => $value) {
            if ($propertyReflection->isArrayKeyList === true) {
                $parsedOutput[$key] = $this->_unSerialize($value, $type, $propertyReflection);
            } else {
                $parsedOutput[] = $this->_unSerialize($value, $type, $propertyReflection);
            }
        }

        return $parsedOutput;
    }

    protected function _unSerializeBuildIn(mixed $value, string $type, PropertyReflection $propertyReflection): mixed
    {
        try {
            return TransformHelper::transformBuildIn($value, $type);
        } catch (TransformException $transformException) {
            throw new \LogicException('Can not transform property <' . $propertyReflection->name . '>', 1652190689, $transformException);
        }
    }

    protected function parseNull(PropertyReflection $propertyReflection): mixed
    {
        if ($propertyReflection->isAllowsNull === true) {
            return null;
        }
        if ($propertyReflection->isHasDefaultValue === true) {
            return $propertyReflection->defaultValue;
        }
        throw new \LogicException('No value for <' . $propertyReflection->name . '> found', 1675967217);
    }
}
