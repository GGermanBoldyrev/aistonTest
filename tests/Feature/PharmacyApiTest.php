<?php

namespace Tests\Feature;

use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PharmacyApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_list_pharmacies()
    {
        Pharmacy::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/pharmacies', [
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
                                'code',
                                'address',
                                'city',
                                'createdAt',
                                'updatedAt'
                            ]
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_pharmacy()
    {
        $pharmacyData = [
            'data' => [
                'type' => 'pharmacies',
                'attributes' => [
                    'code' => 'PH-001',
                    'address' => '123 Main Street',
                    'city' => 'New York'
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/pharmacies', $pharmacyData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'code',
                            'address',
                            'city',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('pharmacies', [
            'code' => 'PH-001',
            'address' => '123 Main Street',
            'city' => 'New York'
        ]);
    }

    #[Test]
    public function it_can_show_pharmacy()
    {
        $pharmacy = Pharmacy::factory()->create();

        $response = $this->getJson("/api/v1/pharmacies/{$pharmacy->id}", [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'code',
                            'address',
                            'city',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_update_pharmacy()
    {
        $pharmacy = Pharmacy::factory()->create();

        $updateData = [
            'data' => [
                'type' => 'pharmacies',
                'id' => (string) $pharmacy->id,
                'attributes' => [
                    'address' => 'Updated Address'
                ]
            ]
        ];

        $response = $this->patchJson("/api/v1/pharmacies/{$pharmacy->id}", $updateData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'code',
                            'address',
                            'city',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('pharmacies', [
            'id' => $pharmacy->id,
            'address' => 'Updated Address'
        ]);
    }

    #[Test]
    public function it_can_delete_pharmacy()
    {
        $pharmacy = Pharmacy::factory()->create();

        $response = $this->deleteJson("/api/v1/pharmacies/{$pharmacy->id}", [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('pharmacies', [
            'id' => $pharmacy->id
        ]);
    }

    #[Test]
    public function it_validates_pharmacy_code_is_required()
    {
        $response = $this->postJson('/api/v1/pharmacies', [
            'data' => [
                'type' => 'pharmacies',
                'attributes' => [
                    'code' => null,
                    'address' => '123 Main Street'
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
                    'detail' => 'The code field is required.',
                    'source' => [
                        'pointer' => '/data/attributes/code'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_pharmacy_address_is_required()
    {
        $response = $this->postJson('/api/v1/pharmacies', [
            'data' => [
                'type' => 'pharmacies',
                'attributes' => [
                    'code' => 'PH-001',
                    'address' => null
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
                    'detail' => 'The address field is required.',
                    'source' => [
                        'pointer' => '/data/attributes/address'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_pharmacy_code_is_unique()
    {
        $existingPharmacy = Pharmacy::factory()->create(['code' => 'PH-EXISTING']);

        $response = $this->postJson('/api/v1/pharmacies', [
            'data' => [
                'type' => 'pharmacies',
                'attributes' => [
                    'code' => 'PH-EXISTING',
                    'address' => '123 Main Street'
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
                    'detail' => 'The code has already been taken.',
                    'source' => [
                        'pointer' => '/data/attributes/code'
                    ]
                ]
            ]
        ]);
    }
}

