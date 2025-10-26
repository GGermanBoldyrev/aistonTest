<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryHint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryHintApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_list_category_hints()
    {
        CategoryHint::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/category-hints', [
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
                                'text',
                                'hint_type',
                                'order',
                                'createdAt',
                                'updatedAt'
                            ]
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_category_hint()
    {
        $category = Category::factory()->create();

        $hintData = [
            'data' => [
                'type' => 'category-hints',
                'attributes' => [
                    'text' => 'Test hint text with minimum length',
                    'hint_type' => 'positive',
                    'order' => 1
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => (string) $category->id
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/category-hints', $hintData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'text',
                            'hint_type',
                            'order',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('category_hints', [
            'text' => 'Test hint text with minimum length',
            'hint_type' => 'positive',
            'order_column' => 1,
            'category_id' => $category->id
        ]);
    }

    #[Test]
    public function it_can_show_category_hint()
    {
        $hint = CategoryHint::factory()->create();

        $response = $this->getJson("/api/v1/category-hints/{$hint->id}", [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'text',
                            'hint_type',
                            'order',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_update_category_hint()
    {
        $hint = CategoryHint::factory()->create();

        $updateData = [
            'data' => [
                'type' => 'category-hints',
                'id' => (string) $hint->id,
                'attributes' => [
                    'text' => 'Updated hint text with length'
                ]
            ]
        ];

        $response = $this->patchJson("/api/v1/category-hints/{$hint->id}", $updateData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'text',
                            'hint_type',
                            'order',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('category_hints', [
            'id' => $hint->id,
            'text' => 'Updated hint text with length'
        ]);
    }

    #[Test]
    public function it_can_delete_category_hint()
    {
        $hint = CategoryHint::factory()->create();

        $response = $this->deleteJson("/api/v1/category-hints/{$hint->id}", [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('category_hints', [
            'id' => $hint->id
        ]);
    }

    #[Test]
    public function it_validates_hint_text_is_required()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/category-hints', [
            'data' => [
                'type' => 'category-hints',
                'attributes' => [
                    'text' => null,
                    'hint_type' => 'positive'
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => (string) $category->id
                        ]
                    ]
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
                    'detail' => 'The text field is required.',
                    'source' => [
                        'pointer' => '/data/attributes/text'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_hint_type_is_valid()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/category-hints', [
            'data' => [
                'type' => 'category-hints',
                'attributes' => [
                    'text' => 'Test hint with length',
                    'hint_type' => 'invalid_type'
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => (string) $category->id
                        ]
                    ]
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
                    'detail' => 'The selected hint type is invalid.',
                    'source' => [
                        'pointer' => '/data/attributes/hint_type'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_text_minimum_length()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/category-hints', [
            'data' => [
                'type' => 'category-hints',
                'attributes' => [
                    'text' => 'Hi',
                    'hint_type' => 'positive'
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => (string) $category->id
                        ]
                    ]
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
                    'detail' => 'The text field must be at least 5 characters.',
                    'source' => [
                        'pointer' => '/data/attributes/text'
                    ]
                ]
            ]
        ]);
    }
}
