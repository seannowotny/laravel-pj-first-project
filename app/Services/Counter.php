<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class Counter
{
    private $cache;
    private $session;
    private $timeout;
    private $supportsTags;

    public function __construct(Cache $cache, Session $session, int $timeout)
    {
        $this->cache = $cache;
        $this->session = $session;
        $this->timeout = $timeout;
        $this->supportsTags = method_exists($this->cache, 'tags');
    }

    public function GetUsersOnPageAmount(string $sessionsName, array $tags = null): int
    {
        $cache = $this->GetCache($tags);

        $sessions = $cache->get($sessionsName);
        $visitorSession = $this->session->getId();
        $sessions[$visitorSession] = now();

        $sessions = $this->RemoveOutdatedSessions($sessions, $this->timeout);

        $cache->forever($sessionsName, $sessions);

        return count($sessions);
    }

    private function GetCache(array $tags)
    {
        if($this->supportsTags && $tags !== null)
        {
            $cache = $this->cache->tags($tags);
        }
        else
        {
            $cache = $this->cache;
        }

        return $cache;
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
