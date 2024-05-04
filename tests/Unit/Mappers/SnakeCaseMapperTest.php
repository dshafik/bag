<?php

declare(strict_types=1);

namespace Tests\Unit\Mappers;

use Bag\Mappers\SnakeCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SnakeCase::class)]
class SnakeCaseMapperTest extends TestCase
{
    public function testItTransformsToSnakeCase()
    {
        $mapper = new SnakeCase();

        $this->assertSame('some_words_here', $mapper('someWordsHere'));
    }

    public function testItLeavesSnakeCaseAlone()
    {
        $mapper = new SnakeCase();

        $this->assertSame('some_words_here', $mapper('some_words_here'));
    }
}
