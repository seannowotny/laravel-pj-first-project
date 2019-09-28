@component('mail::message')
# Introduction

Hello {{ $user->name }}

@component('mail::button', ['url' => route('posts.show', ['post' => $comment->commentable->id])])
View The Blog Post
@endcomponent

@component('mail::button', ['url' => route('users.show', ['user' => $comment->user->id])])
Visit {{ $comment->user->name }}'s profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
