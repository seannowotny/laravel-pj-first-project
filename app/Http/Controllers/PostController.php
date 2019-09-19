<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Requests\StorePost;
use App\User;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
        ->only(['create', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        // DB::connection()->enableQueryLog();

        // dd(BlogPost::withCount(['comments', 'comments as new_comments' => function($query){
        //     $query->where('created_at', '>=', '2019-08-31 09:30:56');
        // }])->get());

        // dd(DB::getQueryLog());

        //comments_count
        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
                'mostCommented' => BlogPost::mostCommented()->take(5)->get(),
                'mostActive' => User::withMostBlogPosts()->take(5)->get(),
                'mostActiveLastMonth' => User::withMostBlogPostsLastMonth()->take(5)->get(),
            ]
        );
    }

    public function show(int $id)
    {
        // return view('posts.show', [
        //     'post' => BlogPost::with(['comments' => function($query){
        //         return $query->latest();
        //     }])->findOrFail($id)
        // ]);
        return view('posts.show', [
            'post' => BlogPost::with('comments')->findOrFail($id)
        ]);
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
