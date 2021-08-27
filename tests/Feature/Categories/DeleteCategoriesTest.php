<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_cannot_delete_categories()
    {
        $category = Category::factory()->create();

        $this->jsonApi()
            ->delete(route('api.v1.categories.delete', $category))
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_delete_categories()
    {
        $category = Category::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->delete(route('api.v1.categories.delete', $category))
            ->assertStatus(204);
    }
}
