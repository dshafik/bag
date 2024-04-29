<?php

declare(strict_types=1);

namespace Tests\Feature;

use Bag\BagServiceProvider;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\Fixtures\TestBag;

class BagServiceProviderTest extends TestCase
{
    public function testItResolvesValueFromRequest()
    {
        $this->instance('request', Request::createFromBase(new SymfonyRequest(
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                    'email' => 'davey@php.net',
                ],
                JSON_THROW_ON_ERROR
            ),
        )));

        $value = resolve(TestBag::class);
        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertSame('davey@php.net', $value->email);
    }

    protected function getPackageProviders($app)
    {
        return [
            BagServiceProvider::class,
        ];
    }
}
