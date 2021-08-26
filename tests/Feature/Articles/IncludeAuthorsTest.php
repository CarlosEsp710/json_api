<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
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
            ->dump()
            ->assertSee($article->user->name);
    }
}
