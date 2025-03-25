<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Hidden;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use SensitiveParameter;

readonly class HideValues
{
    public function __invoke(BagOutput $output): BagOutput
    {
        $output->values = Cache::remember(__METHOD__, $output->bag, function () use ($output) {
            $values = $output->values;
            $output->properties->each(function (Value $property) use (&$values) {
                $isHidden = Reflection::getAttribute($property->property, Hidden::class) !== null || Reflection::getAttribute($property->property, SensitiveParameter::class) !== null;
                if ($isHidden) {
                    $values = $values->forget($property->property->getName());
                }
            });

            $output->params->each(function (Value $param) use (&$values) {
                $isHidden = Reflection::getAttribute($param->property, Hidden::class) !== null || Reflection::getAttribute($param->property, SensitiveParameter::class) !== null;
                if ($isHidden) {
                    $values = $values->forget($param->property->getName());
                }
            });

            return $values;
        });

        return $output;
    }
}
