<?php

declare(strict_types=1);

namespace SevenSpan\LaravelChat\Facades;

use Illuminate\Support\Facades\Facade;

class Chat extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Chat';
    }
}
