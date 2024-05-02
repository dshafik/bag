<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\MapName;
use Bag\Mappers\MapperInterface;
use ReflectionAttribute;

class Map
{
    public function __construct(public string $inputName, public string $outputName)
    {
    }

    public static function create(?MapName $classMap, \ReflectionParameter|\ReflectionProperty $property): self
    {
        $name = $property->getName();

        $aliases = ['input' => $name, 'output' => $name];

        $map = $classMap;

        /** @var array<ReflectionAttribute<MapName>> $maps */
        $maps = $property->getAttributes(name: MapName::class, flags: ReflectionAttribute::IS_INSTANCEOF);
        if (count($maps) > 0) {
            $map = $maps[0]->newInstance();
        }

        if ($map !== null && $map->input !== null) {
            $aliases['input'] = self::mapName(mapper: $map->input, params: $map->inputParams, name: $name);
        }

        if ($map !== null && $map->output !== null) {
            $aliases['output'] = self::mapName(mapper: $map->output, params: $map->outputParams, name: $name);
        }

        return new self(inputName: $aliases['input'], outputName: $aliases['output']);
    }

    /**
     * @param  class-string<MapperInterface>  $mapper
     */
    protected static function mapName(string $mapper, array $params, string $name): string
    {
        return (new $mapper(... $params))($name);
    }
}
