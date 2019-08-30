@extends('layout')

@section('content')
    @forelse($posts as $post)
        <h3>
            {{ $post->title }}
        </h3>
        <a href="{{ route('posts.show', ['post' => $post->id]) }}">View Post</a>
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}">Edit</a>
    @empty
        <p>No blog posts yet!</p>
    @endforelse
@endsection