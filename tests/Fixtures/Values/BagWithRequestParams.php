<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Attributes\Laravel\FromRouteParameterProperty;
use Bag\Bag;
use Tests\Fixtures\Models\CastedModel;

readonly class BagWithRequestParams extends Bag
{
    public function __construct(
        #[FromRouteParameter()]
        public ?string $stringParam = null,
        #[FromRouteParameter('stringParam')]
        public ?string $notNamedStringParam = null,
        #[FromRouteParameter()]
        public ?int $intParam = null,
        #[FromRouteParameter('intParam')]
        public ?int $notNamedIntParam = null,
        #[FromRouteParameter()]
        public ?CastedModel $modelParam = null,
        #[FromRouteParameter('modelParam')]
        public ?CastedModel $notNamedModelParam = null,
        #[FromRouteParameterProperty('modelParam')]
        public ?TestBag $bag = null,
        #[FromRouteParameterProperty('modelParam', 'bag')]
        public ?TestBag $notNamedBag = null,
        #[FromRouteParameterProperty('invalidParam')]
        public ?string $invalid = null,
        public ?string $notBound = null,
    ) {
    }
}
