<?php

declare(strict_types=1);

namespace SevenSpan\Chat\Facades;

use Illuminate\Support\Facades\Facade;

class Channel extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Channel';
    }
}
