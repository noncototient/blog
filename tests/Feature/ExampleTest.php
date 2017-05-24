<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_create_a_post() {
        $user = factory('App\User')->create();

        $post = factory('App\Post')->raw();

        $response = $this->actingAs($user)->post(route('blog.store'), $post);

        $response->assertStatus(200);
    }
}
