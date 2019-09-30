<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter
{
    public function GetUsersOnPageAmount(string $sessionsName, $tags = null): int
    {
        $sessions = Cache::tags($tags)->get($sessionsName);
        $visitorSession = session()->getId();
        $sessions[$visitorSession] = now();

        $sessions = $this->RemoveOutdatedSessions($sessions, 60);

        Cache::tags($tags)->forever($sessionsName, $sessions);

        return count($sessions);
    }

    private function RemoveOutdatedSessions($sessions, $maxTimeInSeconds)
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
