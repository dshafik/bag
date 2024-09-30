<?php

declare(strict_types=1);

namespace Bag\Exceptions;

use ArgumentCountError;
use Bag\Bag;
use Bag\Internal\Reflection;
use Exception;
use Illuminate\Support\Collection;

class AdditionalPropertiesException extends Exception
{
    /**
     * @param class-string<Bag> $bagClass
     */
    public function __construct(string $bagClass, Collection $extraProperties)
    {
        $extraProperties->filter(fn (string|int $value) => ctype_digit((string) $value))
            ->whenNotEmpty(function () use ($bagClass, $extraProperties) {
                $expectedArgCount = count(Reflection::getParameters(Reflection::getConstructor($bagClass)));

                throw new ArgumentCountError(
                    sprintf(
                        '%s::from(): Too many arguments passed, expected %d, got %d',
                        $bagClass,
                        $expectedArgCount,
                        count($extraProperties) + $expectedArgCount,
                    )
                );
            });

        parent::__construct('Additional properties found: '.$extraProperties->implode(', '));
    }
}
