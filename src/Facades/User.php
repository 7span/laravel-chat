<?php

declare(strict_types=1);

namespace SevenSpan\Chat\Facades;

use Illuminate\Support\Facades\Facade;

class User extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'User';
    }
}
