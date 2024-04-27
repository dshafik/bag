<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Transforms;
use Bag\Bag;
use Illuminate\Database\Eloquent\Model;
use stdClass;
use Tests\Fixtures\Models\TestModel;

readonly class BagWithTransformers extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
        public string $email,
        public ?string $type = null,
    ) {
    }

    #[Transforms(stdClass::class)]
    public static function fromStdClass(stdClass $obj): array
    {
        return (array) $obj + ['email' => 'davey@php.net'];
    }

    #[Transforms(Bag::FROM_JSON)]
    protected static function fromJsonString(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    #[Transforms(Model::class)]
    protected static function fromAnyModel(Model $model): array
    {
        return $model->toArray() + ['type' => Model::class];
    }

    #[Transforms(TestModel::class)]
    protected static function fromTestModel(Model $model): array
    {
        return $model->toArray() + ['type' => TestModel::class];
    }

    #[Transforms('int')]
    #[Transforms('string')]
    protected static function fromMultipleTransforms(int|string $value): array
    {
        if (is_string($value)) {
            return ['name' => $value, 'age' => 40, 'email' => 'davey@php.net'];
        }

        return ['name' => 'Davey Shafik', 'age' => $value, 'email' => 'davey@php.net'];
    }

    #[Transforms('array', 'object')]
    protected static function fromMultipleTypes(array|object $value): array
    {
        return ((array) $value) + ['email' => 'davey@php.net'];
    }
}
