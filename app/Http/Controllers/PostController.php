<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Requests\StorePost;
use App\User;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
        ->only(['create', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-most-commented', 60*60, function(){
            return BlogPost::mostCommented()->take(5)->get();
        });
        $mostActive = Cache::remember('users-most-active', 60*60, function(){
            return User::withMostBlogPosts()->take(5)->get();
        });
        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 60*60, function(){
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
                'mostCommented' => $mostCommented,
                'mostActive' => $mostActive,
                'mostActiveLastMonth' => $mostActiveLastMonth,
            ]
        );
    }

    public function show(int $id)
    {
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60*60, function() use($id) {
            return BlogPost::with('comments')->findOrFail($id);
        });

        $counter = $this->GetUsersOnPageAmount("blog-post-{$id}-users");

        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter,
        ]);
    }

    private function GetUsersOnPageAmount($sessionsName)
    {
        $sessions = Cache::tags(['blog-post'])->get($sessionsName);
        $visitorSession = session()->getId();
        $sessions[$visitorSession] = now();

        $sessions = $this->RemoveOutdatedSessions($sessions, 60);

        Cache::tags(['blog-post'])->forever($sessionsName, $sessions);

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

    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validatedData);
        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function edit(int $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);

        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, int $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);

        $validatedData = $request->validated();

        $post->fill($validatedData);
        $post->save();

        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);

        BlogPost::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
