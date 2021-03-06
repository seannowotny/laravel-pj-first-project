<?php

namespace Tests\Feature;

use App\BlogPost;
use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private function createDummyBlogPost($user_id = null): BlogPost
    {
        return factory(BlogPost::class)->states('new-title')->create(
            [
                'user_id' => $user_id ?? $this->user()->id,
            ]
        );
    }

    public function test_No_BlogPosts_When_Nothing_In_DB()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }

    public function test_See_One_Blog_Post_When_There_Is_One_Without_Comments()
    {
        $this->createDummyBlogPost();

        $response = $this->get('/posts');

        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');
        $response->assertDontSee('Content of the blog post');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title',
        ]);
    }

    public function test_See_One_Post_With_Comments()
    {
        $post = $this->createDummyBlogPost();
        factory(Comment::class, 4)->create([
            'commentable_id' => $post->id,
            'commentable_type' => 'App\BlogPost',
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('4 comments');
    }

    public function test_Store_Valid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'Valid title'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function test_Store_Fail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function test_Update_Valid()
    {
        $user = $this->user();
        // $user->is_admin = true;
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $params = [
            'title' => 'A new named title',
            'content' => 'Content was changed'
        ];

        $this->actingAs($user)
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title',
            'content' => 'Content was changed'
        ]);
    }

    public function test_Delete()
    {
        $user = $this->user();
        // $user->is_admin = true;
        $post = $this->createDummyBlogPost($user->id);

        // dd($user->toArray(), $post->toArray());

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!');
        $this->assertSoftDeleted('blog_posts', $post->toArray());
    }
}
