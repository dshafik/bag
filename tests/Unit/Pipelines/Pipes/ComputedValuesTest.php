<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Pipelines\Pipes\ComputedValues;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\ComputedPropertyBag;
use Tests\Fixtures\Values\ComputedPropertyMissingBag;
use Tests\TestCase;

#[CoversClass(ComputedValues::class)]
class ComputedValuesTest extends TestCase
{
    public function testItValidatesComputedExists()
    {
        $input = new BagInput(ComputedPropertyBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);

        /** @var BagInput $input */
        Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));
        $input->bag = new ComputedPropertyBag($input->values->get('name'), $input->values->get('age'));

        $pipe = new ComputedValues();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertSame('1984-05-04', $input->bag->dob->format('Y-m-d'));
    }

    public function testItErrorsWhenComputedNotSet()
    {
        $input = new BagInput(ComputedPropertyMissingBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);

        /** @var BagInput $input */
        Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));
        $input->bag = new ComputedPropertyMissingBag($input->values->get('name'), $input->values->get('age'));

        $this->expectException(ComputedPropertyUninitializedException::class);
        $this->expectExceptionMessage('Property Tests\Fixtures\Values\ComputedPropertyMissingBag->dob must be computed');
        $pipe = new ComputedValues();
        $pipe($input, fn ($input) => $input);
    }
}
