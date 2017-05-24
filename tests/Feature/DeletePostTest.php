<?php

namespace tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Http\Controllers;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeletePostTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_delete_a_post()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // And an existing post...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => true,
            'published_at' => Carbon::now()
        ]);

        // Attemp to soft delete that post...
        $response = $this->actingAs($user)->delete(route('post.delete', $post->id));

        // Should update that post deleted_at column...
        $this->assertSoftDeleted('posts', ['title' => $post->title]);

        // And be redirected back to the all posts page with success message
        $response->assertRedirect(route('post.index'))->assertSessionHas('success');
    }

    /** @test */
    public function an_unauthenticated_user_cannot_delete_any_posts()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who also has a post...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => true,
            'published_at' => Carbon::now(),
        ]);

        // While another, unauthenticated user tries to delete this post...
        $response = $this->withExceptionHandling()->delete(route('post.delete', $post->id));

        // Is redirected to the login page.
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_force_delete_a_post()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who also has a post...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => true,
            'published_at' => Carbon::now(),
        ]);

        // That has been soft deleted (placed in trash)...
        $this->actingAs($user)->delete(route('post.delete', $post->id));

        // And tries to force delete it from the database...
        $response = $this->actingAs($user)->delete(route('post.force.delete', $post->id));

        // Should result in the post being deleted from the database...
        $this->assertDatabaseMissing('posts', ['title' => $post->title, 'body' => $post->body]);

        // And user being redirected back to posts trash page with a success message.
        $response->assertRedirect(route('post.trash'))->assertSessionHas('success');
    }

    /** @test */
    public function an_unauthenticated_user_can_force_delete_a_post()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who also created a post and then soft deleted it...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => false,
            'published_at' => null,
            'deleted_at' => Carbon::now(),
        ]);

        // And then an unauthenticated user tries to force delete that post...
        $response = $this->withExceptionHandling()->delete(route('post.force.delete', $post->fresh()->id));

        // Should result in the post still being in the database...
        $this->assertDatabaseHas('posts', ['title' => $post->title, 'body' => $post->body]);

        // Should result in the user being redirect to the login page.
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_authenticated_user_can_restore_a_soft_deleted_post()
    {
        // Given we have a user...
        $user = factory(\App\User::class)->create();

        // Who also created a post and then soft deleted it...
        $post = factory(\App\Post::class)->create([
            'user_id' => $user->id,
            'active' => false,
            'published_at' => null,
            'deleted_at' => Carbon::now(),
        ]);

        // Who then tries to restore the post...
        $response = $this->actingAs($user)->patch(route('post.restore', $post->id));

        // Should result in the post being restored...
        $this->assertDatabaseHas('posts', ['title' => $post->title, 'body' => $post->body, 'deleted_at' => null]);

        // And user being redirected back to the post trash page with success message.
        $response->assertRedirect(route('post.trash'))->assertSessionHas('success');
    }
}

