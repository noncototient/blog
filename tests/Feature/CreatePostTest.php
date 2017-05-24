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
    public function an_authenticated_user_can_create_a_post()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who creates a post...
        $post = factory(\App\Post::class)->raw([
            'user_id' => $user->id,
            'active' => true,
        ]);

        // And submits the create post form...
        $response = $this->actingAs($user)
            ->post(route('post.store'), $post);

        // Should result in a post being stored in the database
        $this->assertDatabaseHas('posts', [
            'title' => $post['title'],
            'body' => $post['body']
        ]);

        // And redirect the user with a success message
        $response->assertRedirect(route('post.index'))
            ->assertSessionHas('success', 'Your post has been successfully published.');
    }

    /** @test */
    public function an_authenticated_user_cannot_create_a_post_with_empty_fields()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who creates a post and misses title and the body
        $post = factory(\App\Post::class)->raw([
            'user_id' => $user->id,
            'title' => null,
            'body' => null,
            'active' => true
        ]);

        // And then submits the create post form...
        $response = $this->withExceptionHandling()->actingAs($user)->post(route('post.store'), $post);

        // Should result in the database not saving the data
        $this->assertDatabaseMissing('posts', ['title' => $post['title']]);

        // As well as redirecting the user
        $response->assertStatus(302);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_post()
    {
        // Given we have a user...
        factory(\App\User::class)->create();

        // Who somehow figures out the pattern to create a post without a form...
        $post = factory(\App\Post::class)->raw([
            'user_id' => 1
        ]);

        // But is not authenticated and tries to send the data to the server...
        // Should result in redirection to 'login'
        $this->withExceptionHandling()
            ->post(route('post.store'), $post)
            ->assertRedirect('/login');

        // Just for sanity check, make sure the post was not saved to the database
        $this->assertDatabaseMissing('posts', ['title' => $post['title']]);
     }
}
