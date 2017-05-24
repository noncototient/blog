<?php

namespace tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Http\Controllers;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatePostTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_update_existing_post()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who also previously created and published a post...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => true,
            'published_at' => Carbon::now(),
        ]);

        // Then tries to update the post...
        $updatedPost = factory(\App\Post::class)->raw([
            'title' => 'Updated post title',
            'body' => 'Updated post body',
        ]);

        // And submits the updated post to the server...
        $response = $this->actingAs($user)
            ->patch(route('post.update', $post), $updatedPost);

        // Should result in the database having updated title and body for that post...
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $updatedPost['title'],
            'body' => $updatedPost['body']
        ]);

        // As well as redirect the user with a success message...
        $response->assertRedirect(route('post.index'))
            ->assertSessionHas('success');
    }

    /** @test */
    public function an_unauthenticated_user_cannot_update_any_posts()
    {
// Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who also previously created and published a post...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => true,
            'published_at' => Carbon::now(),
        ]);

        // Then, an unauthenticated tries to update the post...
        $updatedPost = factory(\App\Post::class)->raw([
            'title' => 'Updated post title',
            'body' => 'Updated post body',
        ]);

        // And submits the updated post to the server...
        $response = $this->withExceptionHandling()
            ->patch(route('post.update', $post), $updatedPost);

        // Should result in the user being redirected to the login page...
        $response->assertRedirect(route('login'));

     }
}

