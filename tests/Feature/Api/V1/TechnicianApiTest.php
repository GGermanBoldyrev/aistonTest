<?php

namespace Tests\Feature\Api\V1;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TechnicianApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_list_technicians()
    {
        Technician::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/technicians', [
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
                                'phone',
                                'email',
                                'createdAt',
                                'updatedAt'
                            ]
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_technician()
    {
        $technicianData = [
            'data' => [
                'type' => 'technicians',
                'attributes' => [
                    'name' => 'John Doe',
                    'phone' => '+1234567890',
                    'email' => 'john@example.com'
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/technicians', $technicianData, [
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
                            'phone',
                            'email',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('technicians', [
            'name' => 'John Doe',
            'phone' => '+1234567890',
            'email' => 'john@example.com'
        ]);
    }

    #[Test]
    public function it_can_show_technician()
    {
        $technician = Technician::factory()->create();

        $response = $this->getJson("/api/v1/technicians/{$technician->id}", [
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
                            'phone',
                            'email',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_update_technician()
    {
        $technician = Technician::factory()->create();

        $updateData = [
            'data' => [
                'type' => 'technicians',
                'id' => (string) $technician->id,
                'attributes' => [
                    'name' => 'Updated Name'
                ]
            ]
        ];

        $response = $this->patchJson("/api/v1/technicians/{$technician->id}", $updateData, [
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
                            'phone',
                            'email',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('technicians', [
            'id' => $technician->id,
            'name' => 'Updated Name'
        ]);
    }

    #[Test]
    public function it_can_delete_technician()
    {
        $technician = Technician::factory()->create();

        $response = $this->deleteJson("/api/v1/technicians/{$technician->id}", [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('technicians', [
            'id' => $technician->id
        ]);
    }

    #[Test]
    public function it_validates_technician_name_is_required()
    {
        $response = $this->postJson('/api/v1/technicians', [
            'data' => [
                'type' => 'technicians',
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
    public function it_validates_technician_email_format()
    {
        $response = $this->postJson('/api/v1/technicians', [
            'data' => [
                'type' => 'technicians',
                'attributes' => [
                    'name' => 'John Doe',
                    'email' => 'invalid-email'
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
                    'detail' => 'The email field must be a valid email address.',
                    'source' => [
                        'pointer' => '/data/attributes/email'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_technician_email_is_unique()
    {
        $existingTechnician = Technician::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/v1/technicians', [
            'data' => [
                'type' => 'technicians',
                'attributes' => [
                    'name' => 'John Doe',
                    'email' => 'existing@example.com'
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
                    'detail' => 'The email has already been taken.',
                    'source' => [
                        'pointer' => '/data/attributes/email'
                    ]
                ]
            ]
        ]);
    }
}

