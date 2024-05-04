<?php

declare(strict_types=1);

namespace Tests\Unit\Mappers;

use Bag\Mappers\Stringable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(Stringable::class)]
class StringableMapperTest extends TestCase
{
    public function testItMapsUsingSingleTransform()
    {
        $mapper = new Stringable('upper');

        $this->assertSame('SOME_WORDS_HERE', $mapper('some_words_here'));
    }

    public function testItMapsUsingMultipleTransforms()
    {
        $mapper = new Stringable('upper', 'replace:_,-');

        $this->assertSame('SOME-WORDS-HERE', $mapper('some_words_here'));
    }
}
