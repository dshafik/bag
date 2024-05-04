<?php

declare(strict_types=1);

namespace Tests\Unit\Mappers;

use Bag\Mappers\CamelCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(CamelCase::class)]
class CamelCaseMapperTest extends TestCase
{
    public function testItTransformsToCamelCase()
    {
        $mapper = new CamelCase();

        $this->assertSame('someWordsHere', $mapper('some_words_here'));
    }

    public function testItLeavesCamelCaseAlone()
    {
        $mapper = new CamelCase();

        $this->assertSame('someWordsHere', $mapper('someWordsHere'));
    }
}
