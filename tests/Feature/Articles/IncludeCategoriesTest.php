<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncludeCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_include_categories()
    {
        $article = Article::factory()->create();

        $this->jsonApi()
            ->includePaths('categories')
            ->get(route('api.v1.articles.read', $article))
            ->assertSee($article->category->name)
            ->assertJsonFragment([
                'related' => route('api.v1.articles.relationships.categories', $article)
            ])
            ->assertJsonFragment([
                'self' => route('api.v1.articles.relationships.categories.read', $article)
            ]);
    }

    /** @test */
    public function can_fetch_related_categories()
    {
        $article = Article::factory()->create();

        $this->jsonApi()
            ->get(route('api.v1.articles.relationships.categories', $article))
            ->assertSee($article->category->name);

        $this->jsonApi()
            ->get(route('api.v1.articles.relationships.categories.read', $article))
            ->assertSee($article->category->getRouteKey());
    }
}
