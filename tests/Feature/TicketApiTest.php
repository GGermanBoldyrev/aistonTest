<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Pharmacy;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_list_tickets()
    {
        Ticket::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tickets', [
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
                                'number',
                                'topic',
                                'description',
                                'user_id',
                                'isWarrantyCase',
                                'createdAt',
                                'updatedAt'
                            ]
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_create_ticket()
    {
        $pharmacy = Pharmacy::factory()->create();
        $priority = Priority::factory()->create();
        $category = Category::factory()->create();
        $status = Status::factory()->create();

        $ticketData = [
            'data' => [
                'type' => 'tickets',
                'attributes' => [
                    'number' => 'TICKET-001',
                    'topic' => 'Test Ticket Topic',
                    'description' => 'This is a test ticket description with minimum length',
                    'user_id' => '123',
                    'isWarrantyCase' => true
                ],
                'relationships' => [
                    'pharmacy' => [
                        'data' => [
                            'type' => 'pharmacies',
                            'id' => (string) $pharmacy->id
                        ]
                    ],
                    'priority' => [
                        'data' => [
                            'type' => 'priorities',
                            'id' => (string) $priority->id
                        ]
                    ],
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => (string) $category->id
                        ]
                    ],
                    'status' => [
                        'data' => [
                            'type' => 'statuses',
                            'id' => (string) $status->id
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/tickets', $ticketData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'number',
                            'topic',
                            'description',
                            'user_id',
                            'isWarrantyCase',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('tickets', [
            'number' => 'TICKET-001',
            'topic' => 'Test Ticket Topic',
            'user_id' => '123',
            'is_warranty_case' => true,
            'pharmacy_id' => $pharmacy->id,
            'priority_id' => $priority->id,
            'category_id' => $category->id,
            'status_id' => $status->id
        ]);
    }

    #[Test]
    public function it_can_show_ticket()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->getJson("/api/v1/tickets/{$ticket->id}", [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'number',
                            'topic',
                            'description',
                            'user_id',
                            'isWarrantyCase',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_update_ticket()
    {
        $ticket = Ticket::factory()->create();

        $updateData = [
            'data' => [
                'type' => 'tickets',
                'id' => (string) $ticket->id,
                'attributes' => [
                    'topic' => 'Updated Ticket Topic'
                ]
            ]
        ];

        $response = $this->patchJson("/api/v1/tickets/{$ticket->id}", $updateData, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'attributes' => [
                            'number',
                            'topic',
                            'description',
                            'user_id',
                            'isWarrantyCase',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'topic' => 'Updated Ticket Topic'
        ]);
    }

    #[Test]
    public function it_can_delete_ticket()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->deleteJson("/api/v1/tickets/{$ticket->id}", [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id
        ]);
    }

    #[Test]
    public function it_validates_ticket_number_is_required()
    {
        $pharmacy = Pharmacy::factory()->create();
        $priority = Priority::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/tickets', [
            'data' => [
                'type' => 'tickets',
                'attributes' => [
                    'number' => null,
                    'topic' => 'Test Topic',
                    'description' => 'Test description with minimum length',
                    'user_id' => '123'
                ],
                'relationships' => [
                    'pharmacy' => [
                        'data' => [
                            'type' => 'pharmacies',
                            'id' => (string) $pharmacy->id
                        ]
                    ],
                    'priority' => [
                        'data' => [
                            'type' => 'priorities',
                            'id' => (string) $priority->id
                        ]
                    ],
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
                    'detail' => 'The number field is required.',
                    'source' => [
                        'pointer' => '/data/attributes/number'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_ticket_number_is_unique()
    {
        $pharmacy = Pharmacy::factory()->create();
        $priority = Priority::factory()->create();
        $category = Category::factory()->create();

        Ticket::factory()->create([
            'number' => 'TICKET-EXISTING'
        ]);

        $response = $this->postJson('/api/v1/tickets', [
            'data' => [
                'type' => 'tickets',
                'attributes' => [
                    'number' => 'TICKET-EXISTING',
                    'topic' => 'Test Topic',
                    'description' => 'Test description with minimum length',
                    'user_id' => '123'
                ],
                'relationships' => [
                    'pharmacy' => [
                        'data' => [
                            'type' => 'pharmacies',
                            'id' => (string) $pharmacy->id
                        ]
                    ],
                    'priority' => [
                        'data' => [
                            'type' => 'priorities',
                            'id' => (string) $priority->id
                        ]
                    ],
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
                    'detail' => 'The number has already been taken.',
                    'source' => [
                        'pointer' => '/data/attributes/number'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_validates_description_minimum_length()
    {
        $pharmacy = Pharmacy::factory()->create();
        $priority = Priority::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/tickets', [
            'data' => [
                'type' => 'tickets',
                'attributes' => [
                    'number' => 'TICKET-001',
                    'topic' => 'Test Topic',
                    'description' => 'Short',
                    'user_id' => '123'
                ],
                'relationships' => [
                    'pharmacy' => [
                        'data' => [
                            'type' => 'pharmacies',
                            'id' => (string) $pharmacy->id
                        ]
                    ],
                    'priority' => [
                        'data' => [
                            'type' => 'priorities',
                            'id' => (string) $priority->id
                        ]
                    ],
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
                    'detail' => 'The description field must be at least 10 characters.',
                    'source' => [
                        'pointer' => '/data/attributes/description'
                    ]
                ]
            ]
        ]);
    }

    #[Test]
    public function it_can_include_relationships()
    {
        $technician = Technician::factory()->create();
        $ticket = Ticket::factory()->create([
            'technician_id' => $technician->id,
        ]);

        $response = $this->getJson("/api/v1/tickets/{$ticket->id}?include=pharmacy,priority,category,status,technician", [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'type',
                        'relationships' => [
                            'pharmacy',
                            'priority',
                            'category',
                            'status',
                            'technician'
                        ]
                    ],
                    'included' => [
                        '*' => [
                            'id',
                            'type',
                            'attributes'
                        ]
                    ]
                ]);
    }
}

