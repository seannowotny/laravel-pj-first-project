@extends('layout')

@section('content')
    <div class="row">
        <div class="col-8">
          <h1>
            {{ $post->title }}
            @badge(['show' => now()->diffInMinutes($post->created_at) < 520])
              New!
            @endbadge
          </h1>

          <p>{{ $post->content }}</p>

            <img src="{{ $post->image->url() }}" />

          @updated(['date' => $post->created_at, 'name' => $post->user->name ])
          @endupdated
          @updated(['date' => $post->updated_at])
            Updated
          @endupdated

          @tags(['tags' => $post->tags])@endtags

          <p>Currently read by {{ $counter }} people</p>

          <h4>Comments</h4>

            @include('comments._form')

          @forelse ($post->comments as $comment)
            <p>
                {{ $comment->content }}
            </p>
            @updated(['date' => $comment->created_at, 'name' => $comment->user->name ])
            @endupdated
          @empty
            <p>No Comments yet!</p>
          @endforelse
        </div>
        <div class="col-4">
            @include('posts._activity');
        </div>
    </div>
@endsection
