<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $fillable = ['name', 'age', 'email'];
}
