<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_article()
    {
        $article = Article::factory()->create();

        $response = $this->jsonApi()->get(route('api.v1.articles.read', $article));

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                    'createdAt' => $article->created_at,
                    'updatedAt' => $article->updated_at
                ],
                'relationships' => [
                    'authors' => [
                        'links' => [
                            'self' => 'http://jsonapi.test/api/v1/articles/' . $article->getRouteKey() . '/relationships/authors',
                            'related' => route('api.v1.articles.relationships.authors', $article)
                        ]
                    ],
                    'categories' => [
                        'links' => [
                            'self' => 'http://jsonapi.test/api/v1/articles/' . $article->getRouteKey() . '/relationships/categories',
                            'related' => route('api.v1.articles.relationships.categories', $article)
                        ]
                    ]
                ],
                'links' => [
                    'self' => route('api.v1.articles.read', $article)
                ]
            ]
        ]);

        $this->assertNull($response->json(
            'data.relationships.authors.data',
            "The key 'data.relationships.authors.data' must be null"
        ));
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        $articles = Article::factory()->count(3)->create();

        $response = $this->jsonApi()->get(route('api.v1.articles.index'));

        $response->assertJsonFragment([
            'data' => [
                [
                    'type' => 'articles',
                    'id' => (string) $articles[0]->getRouteKey(),
                    'attributes' => [
                        'title' => $articles[0]->title,
                        'slug' => $articles[0]->slug,
                        'content' => $articles[0]->content,
                        'createdAt' => $articles[0]->created_at,
                        'updatedAt' => $articles[0]->updated_at,
                    ],
                    'relationships' => [
                        'authors' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/articles/' . $articles[0]->getRouteKey() . '/relationships/authors',
                                'related' => route('api.v1.articles.relationships.authors',  $articles[0])
                            ]
                        ],
                        'categories' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/articles/' . $articles[0]->getRouteKey() . '/relationships/categories',
                                'related' => route('api.v1.articles.relationships.categories', $articles[0])
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $articles[0])
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[1]->getRouteKey(),
                    'attributes' => [
                        'title' => $articles[1]->title,
                        'slug' => $articles[1]->slug,
                        'content' => $articles[1]->content,
                        'createdAt' => $articles[1]->created_at,
                        'updatedAt' => $articles[1]->updated_at,
                    ],
                    'relationships' => [
                        'authors' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/articles/' . $articles[1]->getRouteKey() . '/relationships/authors',
                                'related' => route('api.v1.articles.relationships.authors',  $articles[1])
                            ]
                        ],
                        'categories' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/articles/' . $articles[1]->getRouteKey() . '/relationships/categories',
                                'related' => route('api.v1.articles.relationships.categories', $articles[1])
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $articles[1])
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[2]->getRouteKey(),
                    'attributes' => [
                        'title' => $articles[2]->title,
                        'slug' => $articles[2]->slug,
                        'content' => $articles[2]->content,
                        'createdAt' => $articles[2]->created_at,
                        'updatedAt' => $articles[2]->updated_at,
                    ],
                    'relationships' => [
                        'authors' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/articles/' . $articles[2]->getRouteKey() . '/relationships/authors',
                                'related' => route('api.v1.articles.relationships.authors',  $articles[2])
                            ]
                        ],
                        'categories' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/articles/' . $articles[2]->getRouteKey() . '/relationships/categories',
                                'related' => route('api.v1.articles.relationships.categories', $articles[2])
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $articles[2])
                    ]
                ]
            ]
        ]);
    }
}
