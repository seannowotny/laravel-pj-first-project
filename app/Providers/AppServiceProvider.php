<?php

namespace App\Providers;

use App\BlogPost;
use App\Comment;
use App\Http\ViewComposers\ActivityComposer;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\Comment as CommentResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::component('components.badge', 'badge');
        Blade::component('components.updated', 'updated');
        Blade::component('components.card', 'card');
        Blade::component('components.tags', 'tags');
        Blade::component('components.errors', 'errors');
        Blade::component('components.comment-form', 'commentForm');
        Blade::component('components.comment-list', 'commentList');

        /*view()->composer(['*'], ActivityComposer::class);*/
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);

        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        $this->app->singleton(Counter::class, function($app)
        {
           return new Counter($app->make('Illuminate\Contracts\Cache\Factory'), $app->make('Illuminate\Contracts\Session\Session'), env('COUNTER_TIMEOUT'));
        });

        $this->app->bind('App\Contracts\CounterContract', Counter::class);

        /*CommentResource::withoutWrapping();*/
        Resource::withoutWrapping();

        //Dependency injection methodology for non-singleton classes only
        /*$this->app->when(Counter::class)
            ->needs('$timeout')
            ->give(env('COUNTER_TIMEOUT'));*/
    }
}
