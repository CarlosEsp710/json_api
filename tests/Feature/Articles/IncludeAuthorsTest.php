<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncludeAuthorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_include_authors()
    {
        $article = Article::factory()->create();

        $this->jsonApi()
            ->includePaths('authors')
            ->get(route('api.v1.articles.read', $article))
            ->assertSee($article->user->name, false)
            ->assertJsonFragment([
                'related' => route('api.v1.articles.relationships.authors', $article)
            ])
            ->assertJsonFragment([
                'self' => 'http://jsonapi.test/api/v1/articles/' . $article->getRouteKey() . '/relationships/authors'
            ]);
    }

    /** @test */
    public function can_fetch_related_authors()
    {
        $article = Article::factory()->create();

        $this->jsonApi()
            ->get(route('api.v1.articles.relationships.authors', $article))
            ->assertSee($article->user->name);

        $this->jsonApi()
            ->get(route('api.v1.articles.relationships.authors.read', $article))
            ->assertSee($article->user->id);
    }
}
