<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\VariadicBag;
use Tests\TestCase;

#[CoversClass(IsVariadic::class)]
class IsVariadicTest extends TestCase
{
    public function testItIsVariadic()
    {
        $input = new BagInput(VariadicBag::class, collect());
        $input = (new ProcessParameters())($input, fn ($input) => $input);

        $pipe = new IsVariadic();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertTrue($input->variadic);
    }

    public function testItIsNotVariadic()
    {
        $input = new BagInput(TestBag::class, collect());
        $input = (new ProcessParameters())($input, fn ($input) => $input);

        $pipe = new IsVariadic();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertFalse($input->variadic);
    }
}
