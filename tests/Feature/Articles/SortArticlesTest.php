<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_sort_articles_by_title_asc()
    {
        Article::factory()->create(['title' => 'C Title']);
        Article::factory()->create(['title' => 'A Title']);
        Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => 'title']);

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title'
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_desc()
    {
        Article::factory()->create(['title' => 'C Title']);
        Article::factory()->create(['title' => 'A Title']);
        Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => '-title']);

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'C Title',
            'B Title',
            'A Title'
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_and_content()
    {
        Article::factory()->create([
            'title' => 'C Title',
            'content' => 'A Content'
        ]);
        Article::factory()->create([
            'title' => 'A Title',
            'content' => 'C Content'
        ]);
        Article::factory()->create([
            'title' => 'B Title',
            'content' => 'B Content'
        ]);

        $url = route('api.v1.articles.index', ['sort' => 'title,content']);

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title'
        ]);

        $url = route('api.v1.articles.index', ['sort' => '-content,title']);

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'C Content',
            'B Content',
            'A Content'
        ]);
    }

    /** @test */
    public function it_cannot_sort_articles_by_unknown_fields()
    {
        Article::factory()->times(3)->create();

        $url = route('api.v1.articles.index', ['sort' => 'unknown']);

        $this->jsonApi()->get($url)->assertStatus(400);
    }
}
