@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>

    <p>Added {{ $post->created_at->diffForHumans() }}</p>

    @if(($diff = (new Carbon\Carbon())->diffInMinutes($post->created_at)) < 5)
        <h2>New!</h2>
        <h3>Created {{ $diff }} minutes ago</h3>
    @endif
@endsection