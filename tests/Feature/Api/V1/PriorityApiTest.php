<?php

namespace Tests\Feature\Api\V1;

use App\Models\Priority;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PriorityApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_list_priorities()
    {
        Priority::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/priorities', [
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
                                'color',
                                'description',
                                'order_column',
                                'createdAt',
                                'updatedAt'
                            ]
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_priority()
    {
        $priorityData = [
            'data' => [
                'type' => 'priorities',
                'attributes' => [
                    'name' => 'High Priority',
                    'color' => '#FF0000',
                    'description' => 'Very important'
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/priorities', $priorityData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'name',
                            'color',
                            'description',
                            'order_column',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('priorities', [
            'name' => 'High Priority',
            'color' => '#FF0000',
            'description' => 'Very important'
        ]);
    }

    #[Test]
    public function it_can_show_priority()
    {
        $priority = Priority::factory()->create();

        $response = $this->getJson("/api/v1/priorities/{$priority->id}", [
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
                            'color',
                            'description',
                            'order_column',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_update_priority()
    {
        $priority = Priority::factory()->create();

        $updateData = [
            'data' => [
                'type' => 'priorities',
                'id' => (string) $priority->id,
                'attributes' => [
                    'name' => 'Updated Priority Name'
                ]
            ]
        ];

        $response = $this->patchJson("/api/v1/priorities/{$priority->id}", $updateData, [
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
                            'color',
                            'description',
                            'order_column',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('priorities', [
            'id' => $priority->id,
            'name' => 'Updated Priority Name'
        ]);
    }

    #[Test]
    public function it_can_delete_priority()
    {
        $priority = Priority::factory()->create();

        $response = $this->deleteJson("/api/v1/priorities/{$priority->id}", [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('priorities', [
            'id' => $priority->id
        ]);
    }

    #[Test]
    public function it_validates_priority_name_is_required()
    {
        $response = $this->postJson('/api/v1/priorities', [
            'data' => [
                'type' => 'priorities',
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
    public function it_validates_priority_name_is_unique()
    {
        $existingPriority = Priority::factory()->create(['name' => 'Existing Priority']);

        $response = $this->postJson('/api/v1/priorities', [
            'data' => [
                'type' => 'priorities',
                'attributes' => [
                    'name' => 'Existing Priority'
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

