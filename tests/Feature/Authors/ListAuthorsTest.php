<?php

namespace Tests\Feature\Authors;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListAuthorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_author()
    {
        $author = User::factory()->create();

        $this->jsonApi()
            ->get(route('api.v1.authors.read', $author))
            ->assertSee($author->name);
    }

    /** @test */
    public function can_fetch_all_authors()
    {
        $authors = User::factory(3)->create();

        $this->jsonApi()
            ->get(route('api.v1.authors.index'))
            ->assertSee($authors[0]->name);
    }
}
