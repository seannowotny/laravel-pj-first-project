@forelse ($comments as $comment)
    <p>
        {{ $comment->content }}
    </p>
    @tags(['tags' => $comment->tags])@endtags
    @updated(['date' => $comment->created_at, 'name' => $comment->user->name, 'userID' => $comment->user->id ])
    @endupdated
@empty
    <p>No Comments yet!</p>
@endforelse
