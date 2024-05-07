<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Exceptions\InvalidBag;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use ReflectionParameter;

readonly class ProcessParameters
{
    public function __invoke(BagInput|BagOutput $inputOrOutput)
    {
        $inputOrOutput->params = Cache::remember(__METHOD__, $inputOrOutput->bagClassname, function () use ($inputOrOutput) {
            $class = Reflection::getClass($inputOrOutput->bagClassname);
            /** @var ValueCollection $params */
            $params = ValueCollection::make(Reflection::getParameters(Reflection::getConstructor($class)))->mapWithKeys(function (ReflectionParameter $param) use ($class) {
                return [$param->getName() => Value::create($class, $param)]; // @codeCoverageIgnore
            });

            if ($params === null || $params->count() === 0) {
                throw new InvalidBag(sprintf('Bag "%s" must have a constructor with at least one parameter', $inputOrOutput->bagClassname));
            }

            return $params;
        });

        return $inputOrOutput;
    }
}
