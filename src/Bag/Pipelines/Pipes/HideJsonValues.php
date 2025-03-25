<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\HiddenFromJson;
use Bag\Enums\OutputType;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Illuminate\Support\Collection;

readonly class HideJsonValues
{
    public function __invoke(BagOutput $output): BagOutput
    {
        if ($output->outputType !== OutputType::JSON) {
            return $output;
        }

        $output->values = Cache::remember(__METHOD__, $output->bag, function () use ($output): Collection {
            $values = $output->values;
            $output->properties->each(function (Value $value) use (&$values) {
                $isHidden = Reflection::getAttribute($value->property, HiddenFromJson::class) !== null;
                if ($isHidden) {
                    $values = $values->forget($value->property->getName());
                }
            });

            $output->params->each(function (Value $value) use (&$values) {
                $isHidden = Reflection::getAttribute($value->property, HiddenFromJson::class) !== null;
                if ($isHidden) {
                    $values = $values->forget($value->property->getName());
                }
            });

            return $values;
        });

        return $output;
    }
}
