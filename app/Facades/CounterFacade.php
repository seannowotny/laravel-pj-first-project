<?php

namespace App\Facades;

use Barryvdh\Debugbar\Facade;

/**
 * A Facade do contract
 * @method static int GetUsersOnPageAmount(string $key, array $tags = null)
 */
class CounterFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'App\Contracts\CounterContract';
    }
}
