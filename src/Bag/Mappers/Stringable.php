<?php

declare(strict_types=1);

namespace Bag\Mappers;

use Illuminate\Support\Str;

class Stringable implements MapperInterface
{
    /**
     * @var string[]
     */
    protected array $stringOperations;

    /**
     * @param  string  ...$stringOperation  The string operations to perform on the value, use operation:arg1,arg2 to pass arguments to the operation
     */
    public function __construct(string ...$stringOperation)
    {
        $this->stringOperations = $stringOperation;
    }

    public function __invoke(string $inputName): string
    {
        $outputName = Str::of($inputName);
        foreach ($this->stringOperations as $stringOperation) {
            $stringOperation = Str::of($stringOperation)->explode(':');
            $args = $stringOperation->map(fn ($op) => Str::of($op)->explode(',')->toArray())->flatten()->skip(1)->toArray();
            $outputName = $outputName->{$stringOperation->first()}(...$args);
        }

        return $outputName->toString();
    }
}
