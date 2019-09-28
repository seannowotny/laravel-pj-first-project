<?php

namespace App\Observers;

use App\BlogPost;
use App\Comment;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    function creating (Comment $comment)
    {
        if($comment->commentable_type === BlogPost::class)
        {
            Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
            Cache::tags(['blog-post'])->forget('mostCommented');
        }
    }
}
