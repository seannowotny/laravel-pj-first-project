@extends('layout')

@section('content')
  <h1>{{ $post->title }}</h1>
  <h5>{{ $post->content }}</h5>

  <h5>Added {{ $post->created_at->diffForHumans() }}</h5>

  @if(($diff = (new Carbon\Carbon())->diffInMinutes($post->created_at)) < 520)
    @badge
        New!
    @endbadge
  @endif

  <h3>Comments</h3>
  @forelse ($post->comments as $comment)
    <p>{{ $comment->content }}</p>
    <p class="text-muted">Added {{ $comment->created_at->diffForHumans() }}</p>
  @empty
    <p>No Comments yet!</p>
  @endforelse
@endsection