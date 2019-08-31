@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <h5>{{ $post->content }}</h5>

    <h3>Comments</h3>
    @forelse ($post->comments as $comment)
        <p>{{ $comment->content }}</p>
        <p class="text-muted">Added {{ $comment->created_at->diffForHumans() }}</p>
    @empty
        <p>No Comments yet!</p>
    @endforelse

    <h5>Added {{ $post->created_at->diffForHumans() }}</h5>

    @if(($diff = (new Carbon\Carbon())->diffInMinutes($post->created_at)) < 5)
        <h4>New!</h4>
        <h5>Created {{ $diff }} minutes ago</h5>
    @endif
@endsection