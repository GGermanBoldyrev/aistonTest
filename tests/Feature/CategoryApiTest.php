<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories', [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'attributes' => [
                                'name',
                                'createdAt',
                                'updatedAt'
                            ]
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_category()
    {
        $categoryData = [
            'data' => [
                'type' => 'categories',
                'attributes' => [
                    'name' => 'Test Category'
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/categories', $categoryData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes'
                    ]
                ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }

    #[Test]
    public function it_can_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category->id}", [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'name',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_update_category()
    {
        $category = Category::factory()->create();

        $updateData = [
            'data' => [
                'type' => 'categories',
                'id' => (string) $category->id,
                'attributes' => [
                    'name' => 'Updated Category Name'
                ]
            ]
        ];

        $response = $this->patchJson("/api/v1/categories/{$category->id}", $updateData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name'
        ]);
    }

    #[Test]
    public function it_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/v1/categories/{$category->id}", [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    #[Test]
    public function it_validates_category_name_is_required()
    {
        $response = $this->postJson('/api/v1/categories', [
            'data' => [
                'type' => 'categories',
                'attributes' => [
                    'name' => null
                ]
            ]
        ], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                [
                    'status' => '422',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'The name field is required.',
                    'source' => [
                        'pointer' => '/data/attributes/name'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_category_name_is_unique()
    {
        $existingCategory = Category::factory()->create(['name' => 'Existing Category']);

        $response = $this->postJson('/api/v1/categories', [
            'data' => [
                'type' => 'categories',
                'attributes' => [
                    'name' => 'Existing Category'
                ]
            ]
        ], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                [
                    'status' => '422',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'The name has already been taken.',
                    'source' => [
                        'pointer' => '/data/attributes/name'
                    ]
                ]
            ]
        ]);
    }
}
