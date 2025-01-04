<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Exceptions\InvalidBag;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use ReflectionParameter;

/**
 * Reflect Constructor Parameters
 */
readonly class ProcessParameters
{
    /**
     * @template T of Bag
     * @param BagInput<T>|BagOutput $inputOrOutput
     * @return BagInput<T>|BagOutput
     */
    public function __invoke(BagInput|BagOutput $inputOrOutput): BagInput|BagOutput
    {
        $inputOrOutput->params = Cache::remember(__METHOD__, $inputOrOutput->bagClassname, function () use ($inputOrOutput) {
            $class = Reflection::getClass($inputOrOutput->bagClassname);
            $params = ValueCollection::wrap(Reflection::getParameters(Reflection::getConstructor($class))->mapWithKeys(
                /**
                 * @return array{string, Value}
                 */
                function ($param) use ($class): array {
                    /** @var ReflectionParameter $param */
                    return [$param->getName() => Value::create($class, $param)]; // @codeCoverageIgnore
                }
            ));

            if ($params === null || $params->count() === 0) {
                throw new InvalidBag(sprintf('Bag "%s" must have a constructor with at least one parameter', $inputOrOutput->bagClassname));
            }

            return $params;
        });

        return $inputOrOutput;
    }
}
