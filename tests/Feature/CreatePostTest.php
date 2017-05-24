<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePostTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_create_a_post() {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who creates a post...
        $post = factory(\App\Post::class)->raw([
            'user_id' => $user->id,
            'active' => true,
        ]);

        // And submits the create post form...
        $response = $this->withExceptionHandling()->actingAs($user)->post(route('post.store'), $post);
        
        // Should result in a post being stored in the database
        $this->assertDatabaseHas('posts', ['title' => $post['title'], 'body' => $post['body']]);

        // And redirect the user with a success message
        $response->assertSessionHas('success', 'Your post has been successfully published.')->assertStatus(302);
    }
}
