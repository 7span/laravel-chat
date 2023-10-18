<?php

declare(strict_types=1);

namespace SevenSpan\Chat\Facades;

use Illuminate\Support\Facades\Facade;

class Message extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Message';
    }
}
