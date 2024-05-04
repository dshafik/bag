<?php

declare(strict_types=1);

namespace Tests\Unit\Mappers;

use Bag\Mappers\Alias;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(Alias::class)]
class AliasMapperTest extends TestCase
{
    public function testItMapsUsingSingleTransform()
    {
        $mapper = new Alias('different_words_go_here');

        $this->assertSame('different_words_go_here', $mapper('some_words_here'));
    }
}
