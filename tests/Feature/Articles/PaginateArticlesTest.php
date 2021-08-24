<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaginateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_paginated_articles()
    {
        $articles = Article::factory()->times(10)->create();

        $url = route('api.v1.articles.index', ['page[size]' => 2, 'page[number]' => 3]);

        $response = $this->getJson($url);

        $response->assertJsonCount(2, 'data')
            ->assertDontSee($articles[0]->title)
            ->assertDontSee($articles[1]->title)
            ->assertDontSee($articles[2]->title)
            ->assertDontSee($articles[3]->title)
            ->assertSee($articles[4]->title)
            ->assertSee($articles[5]->title)
            ->assertDontSee($articles[6]->title)
            ->assertDontSee($articles[7]->title)
            ->assertDontSee($articles[8]->title)
            ->assertDontSee($articles[9]->title);

        $response->assertJsonStructure([
            'links' => ['first', 'last', 'prev', 'next']
        ]);

        $response->assertJsonFragment([
            'first' => route('api.v1.articles.index', ['page[size]' => 2, 'page[number]' => 1]),
            'last' => route('api.v1.articles.index', ['page[size]' => 2, 'page[number]' => 5]),
            'prev' => route('api.v1.articles.index', ['page[size]' => 2, 'page[number]' => 2]),
            'next' => route('api.v1.articles.index', ['page[size]' => 2, 'page[number]' => 4]),
        ]);
    }
}
