<?php

declare(strict_types=1);

namespace Tests\Fixtures\Controllers;

use Tests\Fixtures\Models\CastedModel;
use Tests\Fixtures\Values\BagWithRequestParams;

class TestController
{
    public function stringParam(string $stringParam)
    {

    }

    public function intParam(int $intParam)
    {

    }

    public function modelParam(CastedModel $modelParam)
    {

    }

    public function invalidParam(string $invalidParam)
    {

    }

    public function noBinding(string $notBound)
    {

    }

    public function withBag(BagWithRequestParams $bag, string $stringParam)
    {

    }
}
