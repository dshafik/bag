<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Attributes\Laravel\FromRouteParameterProperty;
use Bag\Exceptions\InvalidRouteParameterException;
use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\LaravelRouteParameters;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Orchestra\Testbench\Attributes\WithEnv;
use function Orchestra\Testbench\workbench_path;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Controllers\TestController;
use Tests\Fixtures\Models\CastedModel;
use Tests\Fixtures\Values\BagWithRequestParams;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[WithEnv('DB_CONNECTION', 'testing')]
#[CoversClass(LaravelRouteParameters::class)]
#[CoversClass(FromRouteParameter::class)]
#[CoversClass(FromRouteParameterProperty::class)]
class LaravelRouteParametersTest extends TestCase
{
    use DatabaseTransactions;

    protected Router $router;

    protected function defineRoutes($router)
    {
        $router->get('/string/{stringParam}', [TestController::class, 'stringParam'])->middleware(SubstituteBindings::class);
        $router->get('/int/{intParam}', [TestController::class, 'intParam'])->middleware(SubstituteBindings::class);
        $router->get('/model/{modelParam}', [TestController::class, 'modelParam'])->middleware(SubstituteBindings::class);
        $router->get('/invalid/{invalidParam}', [TestController::class, 'invalidParam'])->middleware(SubstituteBindings::class);
        $router->get('/no-binding/{notBound}', [TestController::class, 'noBinding'])->middleware(SubstituteBindings::class);
        $router->post('/with-bag/{stringParam}', [TestController::class, 'withBag'])->middleware(SubstituteBindings::class);
    }

    public function testItPopulatesStringParameterValue()
    {
        $this->get('/string/testing');

        $input = $this->runPipeline();

        $this->assertSame('testing', $input->values->get('stringParam'));
        $this->assertSame('testing', $input->values->get('notNamedStringParam'));
    }

    public function testItPopulatesIntParameterValue()
    {
        $this->get('/int/1234');

        $input = $this->runPipeline();

        $this->assertSame(1234, $input->values->get('intParam'));
        $this->assertSame(1234, $input->values->get('notNamedIntParam'));
    }

    public function testItPopulatesModelParameterValue()
    {
        $model = CastedModel::create([
            'bag' => null,
        ]);

        $this->get('/model/' . $model->id);

        $input = $this->runPipeline();

        $this->assertInstanceOf(CastedModel::class, $input->values->get('modelParam'));
        $this->assertSame(1, $input->values->get('modelParam')->id);

        $this->assertInstanceOf(CastedModel::class, $input->values->get('notNamedModelParam'));
        $this->assertSame(1, $input->values->get('notNamedModelParam')->id);
    }

    public function testItPopulatesModelParameterPropertyValue()
    {
        $model = CastedModel::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        $this->get('/model/' . $model->id);

        $input = $this->runPipeline();

        $this->assertInstanceOf(TestBag::class, $input->values->get('bag'));
        $this->assertSame('Davey Shafik', $input->values->get('bag')->name);
        $this->assertSame(40, $input->values->get('bag')->age);
        $this->assertSame('davey@php.net', $input->values->get('bag')->email);

        $this->assertInstanceOf(TestBag::class, $input->values->get('notNamedBag'));
        $this->assertSame('Davey Shafik', $input->values->get('notNamedBag')->name);
        $this->assertSame(40, $input->values->get('notNamedBag')->age);
        $this->assertSame('davey@php.net', $input->values->get('notNamedBag')->email);
    }

    public function testItErrorsWhenTryingToGetPropertyOnScalarValue()
    {
        $this->expectException(InvalidRouteParameterException::class);
        $this->expectExceptionMessage('Route parameter "invalidParam" must be an object.');
        $this->get('/invalid/testing');

        $this->runPipeline();
    }

    public function testItIgnoresUnboundParams()
    {
        $this->get('/no-binding/testing');

        $input = $this->runPipeline();

        $this->assertNull($input->values->get('notBound'));
    }

    public function testItDoesNotOverrideBody()
    {
        $this->postJson('/with-bag/overridden', ['stringParam' => 'testing', 'notNamedStringParam' => 'testing']);

        $input = $this->runPipeline();

        $this->assertSame('overridden', $input->values->get('stringParam'));
        $this->assertSame('overridden', $input->values->get('notNamedStringParam'));
    }

    protected function runPipeline(): BagInput
    {
        $input = new BagInput(BagWithRequestParams::class, collect());

        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);
        $input = (new LaravelRouteParameters())($input);
        $input = (new CastInputValues())($input);

        return $input;
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }
}
