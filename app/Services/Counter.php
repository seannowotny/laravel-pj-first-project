<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter
{
    private $timeout;

    public function __construct(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function GetUsersOnPageAmount(string $sessionsName, array $tags = null): int
    {
        $sessions = Cache::tags($tags)->get($sessionsName);
        $visitorSession = session()->getId();
        $sessions[$visitorSession] = now();

        $sessions = $this->RemoveOutdatedSessions($sessions, $this->timeout);

        Cache::tags($tags)->forever($sessionsName, $sessions);

        return count($sessions);
    }

    private function RemoveOutdatedSessions(array $sessions, int $maxTimeInSeconds): array
    {
        foreach($sessions as $session => $lastVisit)
        {
            if(now()->diffInSeconds($lastVisit) > $maxTimeInSeconds)
            {
                unset($sessions[$session]);
            }
        }

        return $sessions;
    }
}
