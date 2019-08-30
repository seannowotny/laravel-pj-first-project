@extends('layout')

@section('content')
    @forelse($posts as $post)
        <h3 class="mt-3">
            <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
        </h3>
            
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" 
           class="btn btn-primary">Edit</a>

        <form method="POST" class="fm-inline"
              action="{{ route('posts.destroy', ['post' => $post->id]) }}">
            @csrf
            @method("DELETE")

            <input type="submit" class="btn btn-primary" value="Delete!"/>
        </form>
    @empty
        <p>No blog posts yet!</p>
    @endforelse
@endsection