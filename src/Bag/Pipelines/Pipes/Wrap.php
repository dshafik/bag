<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Wrap as WrapAttribute;
use Bag\Attributes\WrapJson;
use Bag\Collection;
use Bag\Enums\OutputType;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagOutput;

readonly class Wrap
{
    public function __invoke(BagOutput $output)
    {
        if ($output->outputType !== OutputType::ARRAY && $output->outputType !== OutputType::JSON) {
            return $output;
        }

        $wrapAttribute = null;

        if ($output->outputType === OutputType::JSON) {
            $wrapAttribute = Reflection::getAttribute(Reflection::getClass($output->bag), WrapJson::class);
        }

        if ($wrapAttribute === null) {
            $wrapAttribute = Reflection::getAttribute(Reflection::getClass($output->bag), WrapAttribute::class);
            if ($wrapAttribute === null) {
                return $output;
            }
        }

        $output->output = Collection::make([Reflection::getAttributeInstance($wrapAttribute)->wrapKey => $output->output]);

        return $output;
    }
}
