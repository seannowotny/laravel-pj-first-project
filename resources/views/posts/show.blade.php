@extends('layout')

@section('content')
  <h1>
    {{ $post->title }}
    @badge(['show' => now()->diffInMinutes($post->created_at) < 520])
      New!
    @endbadge
  </h1>

  <p>{{ $post->content }}</p>
  <p>Added {{ $post->created_at->diffForHumans() }}</p>

  <h4>Comments</h4>
  @forelse ($post->comments as $comment)
    <p>{{ $comment->content }}</p>
    <p class="text-muted">Added {{ $comment->created_at->diffForHumans() }}</p>
  @empty
    <p>No Comments yet!</p>
  @endforelse
@endsection