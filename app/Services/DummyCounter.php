<?php

namespace App\Services;

use App\Contracts\CounterContract;

class DummyCounter implements  CounterContract
{
    public function GetUsersOnPageAmount(string $sessionsName, array $tags = null): int
    {
        dd("I'm a dummy counter not implemented yet");
        return 0;
    }
}
