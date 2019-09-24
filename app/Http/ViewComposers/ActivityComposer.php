<?php

namespace App\Http\ViewComposers;

use App\BlogPost;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer
{
    public function compose(View $view)
    {
        $timeInSeconds = 60 * 10;

        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-most-commented', $timeInSeconds, function(){
            return BlogPost::mostCommented()->take(5)->get();
        });
        $mostActive = Cache::remember('users-most-active', $timeInSeconds, function(){
            return User::withMostBlogPosts()->take(5)->get();
        });
        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', $timeInSeconds, function(){
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        $view->with('mostCommented', $mostCommented);
        $view->with('mostActive', $mostActive);
        $view->with('mostActiveLastMonth', $mostActiveLastMonth);
    }
}
