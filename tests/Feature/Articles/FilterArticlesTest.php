<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
        Article::factory()->create([
            'title' => 'Aprende Laravel desde cero'
        ]);

        Article::factory()->create([
            'title' => 'Otro artículo'
        ]);

        $url = route('api.v1.articles.index', ['filter[title]' => 'Laravel']);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel desde cero')
            ->assertDontSee('Otro artículo');
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        Article::factory()->create([
            'content' => '<div>Aprende Laravel desde cero</div>'
        ]);

        Article::factory()->create([
            'content' => '<div>Otro artículo</div>'
        ]);

        $url = route('api.v1.articles.index', ['filter[content]' => 'Laravel']);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel desde cero')
            ->assertDontSee('Otro artículo');
    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        Article::factory()->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021)
        ]);

        Article::factory()->create([
            'title' => 'Article from 2020',
            'created_at' => now()->year(2020)
        ]);

        $url = route('api.v1.articles.index', ['filter[year]' => 2020]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from 2020')
            ->assertDontSee('Article from 2021');
    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        Article::factory()->create([
            'title' => 'Article from February',
            'created_at' => now()->month(2)
        ]);

        Article::factory()->create([
            'title' => 'Another article from February',
            'created_at' => now()->month(2)
        ]);

        Article::factory()->create([
            'title' => 'Article from June',
            'created_at' => now()->month(6)
        ]);

        $url = route('api.v1.articles.index', ['filter[month]' => 2]);

        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from February')
            ->assertSee('Another article from February')
            ->assertDontSee('Article from June');
    }

    /** @test */
    public function cannot_filter_articles_by_unknown_filters()
    {
        Article::factory()->create([]);

        $url = route('api.v1.articles.index', ['filter[unknown]' => 2]);

        $this->getJson($url)->assertStatus(400);
    }

    /** @test */
    public function can_search_articles_by_title_and_content()
    {
        Article::factory()->create([
            'title' => 'Title',
            'content' => 'Content'
        ]);

        Article::factory()->create([
            'title' => 'Another article from Carlos',
            'content' => 'Another Content...',
        ]);

        Article::factory()->create([
            'title' => 'Another article',
            'content' => 'Content from Carlos'
        ]);

        $url = route('api.v1.articles.index', ['filter[search]' => 'Carlos']);

        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Another article from Carlos')
            ->assertSee('Another article')
            ->assertDontSee('Title');
    }

    /** @test */
    public function can_search_articles_by_title_and_content_with_multiple_terms()
    {
        Article::factory()->create([
            'title' => 'Title',
            'content' => 'Content'
        ]);

        Article::factory()->create([
            'title' => 'Another article from Carlos',
            'content' => 'Another Content...',
        ]);

        Article::factory()->create([
            'title' => 'Another Laravel article from Carlos',
            'content' => 'Another Content...',
        ]);

        Article::factory()->create([
            'title' => 'Another article',
            'content' => 'Content from Carlos'
        ]);

        $url = route('api.v1.articles.index', ['filter[search]' => 'Carlos Laravel']);

        $this->getJson($url)
            ->assertJsonCount(3, 'data')
            ->assertSee('Another article from Carlos')
            ->assertSee('Another Laravel article from Carlos')
            ->assertSee('Another article')
            ->assertDontSee('Title');
    }
}
