<?php

namespace App\Contracts;

interface CounterContract
{
    public function GetUsersOnPageAmount(string $sessionsName, array $tags = null): int;
}
