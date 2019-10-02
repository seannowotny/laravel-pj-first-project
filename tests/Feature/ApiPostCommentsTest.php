<?php

namespace Tests\Feature;

use App\BlogPost;
use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiPostCommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_Non_Existent_BlogPost_Returns_404()
    {
        $response = $this->json('GET', 'api/v1/posts/1/comments');

        $response->assertStatus(404);
    }

    public function test_BlogPost_Without_Comments_Doesnt_Return_Any_Comments()
    {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id
        ]);

        $response = $this->json('GET', 'api/v1/posts/1/comments');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data');
    }

    public function test_Blog_Post_With_10_Comments_Returns_10_Comments()
    {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id
        ])->each(function(BlogPost $post){
            $post->comments()->saveMany(
                factory(Comment::class, 10)->make([
                    'user_id' => $this->user()->id
                ])
            );
        });

        $response = $this->json('GET', 'api/v1/posts/2/comments');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        '*' =>
                        [
                            'id',
                            'created_at',
                            'updated_at',
                            'content',
                            'user' =>
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ],
                    'links',
                    'meta'
                ])
            ->assertJsonCount(10, 'data');
    }

    public function test_Adding_Comment_Without_Authentication_Doesnt_Work()
    {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id
        ]);

        $response = $this->json('POST', 'api/v1/posts/3/comments', [
            'content' => 'Hello'
        ]);

        $response->assertStatus(401);
    }

    public function test_Adding_Comment_With_Authentication_Works()
    {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id
        ]);

        $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/posts/4/comments', [
            'content' => 'Hello'
        ]);

        $response->assertStatus(201);
    }

    public function test_Adding_Comment_With_Invalid_Data_Doesnt_Work()
    {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id
        ]);

        $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/posts/5/comments', []);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'content' => [
                        'The content field is required.'
                    ]
                ]
            ]);
    }
}
