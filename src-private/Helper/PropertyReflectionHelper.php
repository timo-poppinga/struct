<?php

declare(strict_types=1);

namespace Struct\Struct\Private\Helper;

use Exception\Unexpected\UnexpectedException;
use Struct\Attribute\ArrayKeyList;
use Struct\Attribute\ArrayList;
use Struct\Contracts\StructInterface;
use Struct\Exception\InvalidValueException;
use Struct\Struct\Struct\PropertyReflection;

class PropertyReflectionHelper
{
    /**
     * @return PropertyReflection[]
     */
    public static function readProperties(StructInterface $structure): array
    {
        $properties = [];
        try {
            $reflection = new \ReflectionClass($structure);
            // @phpstan-ignore-next-line
        } catch (\ReflectionException $exception) {
            throw new UnexpectedException(1652124640, $exception);
        }
        $reflectionProperties = $reflection->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            if ($reflectionProperty->isPublic() === false) {
                throw new InvalidValueException('The property <' . $propertyName . '> must be public', 1675967772);
            }
            $properties[] = self::buildPropertyReflection($reflectionProperty);
        }
        return $properties;
    }

    protected static function buildPropertyReflection(\ReflectionProperty $reflectionProperty): PropertyReflection
    {
        $propertyReflection = new PropertyReflection();
        $propertyReflection->name = $reflectionProperty->getName();
        $type = $reflectionProperty->getType();
        if ($type === null) {
            throw new InvalidValueException('The property <' . $propertyReflection->name . '> must have an type declaration', 1652179807);
        }
        if (is_a($type, \ReflectionIntersectionType::class) === true) {
            throw new InvalidValueException('Intersection type is not supported at property <' . $propertyReflection->name . '>', 1652179804);
        }
        if (is_a($type, \ReflectionUnionType::class) === true) {
            throw new InvalidValueException('Union type is not supported at property <' . $propertyReflection->name . '>', 1652179804);
        }
        if (is_a($type, \ReflectionNamedType::class) === false) {
            throw new UnexpectedException(1652187714);
        }

        $propertyReflection->type = $type->getName();
        $propertyReflection->isAllowsNull = $type->allowsNull();
        $propertyReflection->isHasDefaultValue = $reflectionProperty->hasDefaultValue();
        $propertyReflection->defaultValue = $reflectionProperty->getDefaultValue();
        $propertyReflection->isBuiltin = $type->isBuiltin();

        self::buildAttributes($reflectionProperty, $propertyReflection);

        return $propertyReflection;
    }

    protected static function buildAttributes(\ReflectionProperty $reflectionProperty, PropertyReflection $propertyReflection): void
    {
        $arrayListAttributes = $reflectionProperty->getAttributes(ArrayList::class);
        $arrayKeyListAttributes = $reflectionProperty->getAttributes(ArrayKeyList::class);
        if (count($arrayListAttributes) === 0 && count($arrayKeyListAttributes) === 0) {
            return;
        }
        if (count($arrayListAttributes) > 0 &&  count($arrayKeyListAttributes) > 0) {
            throw new InvalidValueException('The property <' . $reflectionProperty->getName() . '> can not be ArrayList and ArrayKeyList', 1652195496);
        }

        $attributes = $arrayListAttributes;
        if (count($attributes) === 0) {
            $propertyReflection->isArrayKeyList = true;
            $attributes = $arrayKeyListAttributes;
        }
        $attribute = $attributes[0];
        $arguments = $attribute->getArguments();
        if (count($arguments) === 0) {
            return;
        }
        $propertyReflection->arrayValueType = $arguments[0];
    }
}
