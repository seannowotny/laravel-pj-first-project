<?php

namespace App\Http\Controllers;

use App\Events\BlogPostPosted;
use App\Image;
use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
        ->only(['create', 'edit', 'update', 'destroy']);
    }

    public function index()
    {


        return view(
            'posts.index',
            [
                'posts' => BlogPost::latestWithRelations()->get(),
            ]
        );
    }

    public function show(int $id)
    {
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60*60, function() use($id) {
            return BlogPost::with('comments', 'tags', 'user', 'comments.user')->findOrFail($id);
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

        if($request->hasFile('thumbnail'))
        {
            $path = $request->file('thumbnail')->store('thumbnails');
            $blogPost->image()->save(
                Image::make(['path' => $path])
            );
        }

        event(new BlogPostPosted($blogPost));

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

        if($request->hasFile('thumbnail'))
        {
            $path = $request->file('thumbnail')->store('thumbnails');
            if($post->image)
            {
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }
            else
            {
                $post->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

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
