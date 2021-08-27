<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->jsonApi()->get(route('api.v1.categories.read', $category));

        $response->assertExactJson([
            'data' => [
                'type' => 'categories',
                'id' => (string) $category->getRouteKey(),
                'attributes' => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'createdAt' => $category->created_at,
                    'updatedAt' => $category->updated_at
                ],
                'relationships' => [
                    'articles' => [
                        'links' => [
                            'self' => 'http://jsonapi.test/api/v1/categories/' . $category->getRouteKey() . '/relationships/articles',
                            'related' => route('api.v1.categories.relationships.articles', $category)
                        ]
                    ]
                ],
                'links' => [
                    'self' => route('api.v1.categories.read', $category)
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_categories()
    {
        $categories = category::factory()->count(3)->create();

        $response = $this->jsonApi()->get(route('api.v1.categories.index'));

        $response->assertJsonFragment([
            'data' => [
                [
                    'type' => 'categories',
                    'id' => (string) $categories[0]->getRouteKey(),
                    'attributes' => [
                        'name' => $categories[0]->name,
                        'slug' => $categories[0]->slug,
                        'createdAt' => $categories[0]->created_at,
                        'updatedAt' => $categories[0]->updated_at,
                    ],
                    'relationships' => [
                        'articles' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/categories/' . $categories[0]->getRouteKey() . '/relationships/articles',
                                'related' => route('api.v1.categories.relationships.articles', $categories[0])
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.read', $categories[0])
                    ]
                ],
                [
                    'type' => 'categories',
                    'id' => (string) $categories[1]->getRouteKey(),
                    'attributes' => [
                        'name' => $categories[1]->name,
                        'slug' => $categories[1]->slug,
                        'createdAt' => $categories[1]->created_at,
                        'updatedAt' => $categories[1]->updated_at,
                    ],
                    'relationships' => [
                        'articles' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/categories/' . $categories[1]->getRouteKey() . '/relationships/articles',
                                'related' => route('api.v1.categories.relationships.articles', $categories[1])
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.read', $categories[1])
                    ]
                ],
                [
                    'type' => 'categories',
                    'id' => (string) $categories[2]->getRouteKey(),
                    'attributes' => [
                        'name' => $categories[2]->name,
                        'slug' => $categories[2]->slug,
                        'createdAt' => $categories[2]->created_at,
                        'updatedAt' => $categories[2]->updated_at,
                    ],
                    'relationships' => [
                        'articles' => [
                            'links' => [
                                'self' => 'http://jsonapi.test/api/v1/categories/' . $categories[2]->getRouteKey() . '/relationships/articles',
                                'related' => route('api.v1.categories.relationships.articles', $categories[2])
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.read', $categories[2])
                    ]
                ]
            ]
        ]);
    }
}
