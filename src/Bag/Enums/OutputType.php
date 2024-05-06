<?php

declare(strict_types=1);

namespace Bag\Enums;

enum OutputType
{
    case ARRAY;
    case JSON;
    case UNWRAPPED;
    case RAW;
}
