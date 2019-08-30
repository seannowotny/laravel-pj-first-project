@extends('layout')

@section('content')
    @forelse($posts as $post)
        <h3>
            {{ $post->title }}
        </h3>
        <a href="{{ route('posts.show', ['post' => $post->id]) }}">View Post</a>
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}">Edit</a>

        <form method="POST" 
              action="{{ route('posts.destroy', ['post' => $post->id]) }}">
            @csrf
            @method("DELETE")
            <button type="submit">Delete!</button>
        </form>
    @empty
        <p>No blog posts yet!</p>
    @endforelse
@endsection