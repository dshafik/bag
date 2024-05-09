<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\MapInputName;
use Bag\Attributes\MapName;
use Bag\Attributes\MapOutputName;
use Bag\Internal\Reflection;
use Bag\Mappers\MapperInterface;
use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionClass;

class MapCollection extends Collection
{
    public static function create(ReflectionClass $bagClass, \ReflectionParameter|\ReflectionProperty $property)
    {
        $name = $property->getName();

        $classMapNameAttributes = collect(Reflection::getAttributes($bagClass, MapName::class));
        $classMapInputNameAttributes = collect(Reflection::getAttributes($bagClass, MapInputName::class));
        $classMapOutputNameAttributes = collect(Reflection::getAttributes($bagClass, MapOutputName::class));

        $inputMaps = $classMapNameAttributes->merge($classMapInputNameAttributes);
        $outputMaps = $classMapNameAttributes->merge($classMapOutputNameAttributes);

        $propertyMapNameAttributes = collect(Reflection::getAttributes($property, MapName::class));
        $propertyMapInputNameAttributes = collect(Reflection::getAttributes($property, MapInputName::class));
        $propertyMapOutputNameAttributes = collect(Reflection::getAttributes($property, MapOutputName::class));

        $inputMaps = $inputMaps->merge($propertyMapNameAttributes)->merge($propertyMapInputNameAttributes);
        $outputMaps = $outputMaps->merge($propertyMapNameAttributes)->merge($propertyMapOutputNameAttributes);

        $aliases = [
            'input' => $inputMaps->map(fn (ReflectionAttribute $attribute) => self::getMap($attribute, $name)['input']),
            'output' => $outputMaps->map(fn (ReflectionAttribute $attribute) => self::getMap($attribute, $name)['output'])->last(),
        ];

        return new self($aliases);
    }

    protected static function getMap(ReflectionAttribute $attribute, string $name): array
    {
        /** @var MapName|MapInputName|MapOutputName $map */
        $map = Reflection::getAttributeInstance($attribute);

        $aliases = ['input' => $name, 'output' => $name];

        if ($map !== null && $map->input !== null) {
            $aliases['input'] = self::mapName(mapper: $map->input, params: $map->inputParams, name: $name);
        }

        if ($map !== null && $map->output !== null) {
            $aliases['output'] = self::mapName(mapper: $map->output, params: $map->outputParams, name: $name);
        }

        return $aliases;
    }

    /**
     * @param  class-string<MapperInterface>  $mapper
     */
    protected static function mapName(string $mapper, array $params, string $name): string
    {
        return (new $mapper(... $params))($name);
    }
}
