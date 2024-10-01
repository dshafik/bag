<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Wrap;
use Bag\Attributes\WrapJson;
use Bag\Enums\OutputType;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\CollectionOutput;
use Illuminate\Support\Collection as LaravelCollection;

readonly class WrapCollection
{
    public function __invoke(CollectionOutput $output): CollectionOutput
    {
        if ($output->outputType === OutputType::UNWRAPPED) {
            $output->collection = $output->collection->toBase();

            return $output;
        }

        $wrapAttribute = null;
        if ($output->outputType === OutputType::JSON) {
            $wrapAttribute = Reflection::getAttribute(Reflection::getClass($output->collection), WrapJson::class);
        }

        if ($wrapAttribute === null) {
            $wrapAttribute = Reflection::getAttribute(Reflection::getClass($output->collection), Wrap::class);
            if ($wrapAttribute === null) {
                $output->collection = $output->collection->toBase();

                return $output;
            }
        }

        /** @var LaravelCollection<string, mixed> $wrapped */
        $wrapped = LaravelCollection::make([Reflection::getAttributeInstance($wrapAttribute)?->wrapKey => $output->collection->toBase()]);
        $output->collection = $wrapped;

        return $output;
    }
}
