<?php

namespace Tests\Feature\Authors;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncludeArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_include_articles()
    {
        $author = User::factory()
            ->hasArticles()
            ->create();

        $this->jsonApi()
            ->includePaths('articles')
            ->get(route('api.v1.authors.read', $author))
            ->assertSee($author->articles[0]->title)
            ->assertJsonFragment([
                'related' => route('api.v1.authors.relationships.articles', $author)
            ])
            ->assertJsonFragment([
                'self' => route('api.v1.authors.relationships.articles.read', $author)
            ]);
    }

    /** @test */
    public function can_fetch_related_articles()
    {
        $author = User::factory()
            ->hasArticles()
            ->create();

        $this->jsonApi()
            ->get(route('api.v1.authors.relationships.articles', $author))
            ->assertSee($author->articles[0]->title);

        $this->jsonApi()
            ->get(route('api.v1.authors.relationships.articles.read', $author))
            ->assertSee($author->articles[0]->getRouteKey());
    }
}
