@extends('layout')

@section('content')
    <div class="row">
        <div class="col-8">
            @if($post->image)
                <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color: white; text-align: center; background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat";>
                    <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
            @else
            </h1>
            @endif
            {{ $post->title }}
            @badge(['show' => now()->diffInMinutes($post->created_at) < 520])
              New!
            @endbadge
          </h1>
          @if($post->image)
            </div>
            @endif

          <p>{{ $post->content }}</p>

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
