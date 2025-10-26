<?php

namespace Tests\Unit;

use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TechnicianModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_technician()
    {
        $technician = Technician::create([
            'name' => 'John Doe',
            'phone' => '+1234567890',
            'email' => 'john@example.com'
        ]);

        $this->assertInstanceOf(Technician::class, $technician);
        $this->assertEquals('John Doe', $technician->name);
        $this->assertEquals('+1234567890', $technician->phone);
        $this->assertEquals('john@example.com', $technician->email);
    }

    #[Test]
    public function it_has_many_tickets()
    {
        $technician = Technician::factory()->create();

        $ticket1 = Ticket::create([
            'number' => 'TEST-001',
            'topic' => 'Test Topic 1',
            'description' => 'Test Description 1',
            'user_id' => '1',
            'category_id' => \App\Models\Category::factory()->create()->id,
            'pharmacy_id' => \App\Models\Pharmacy::factory()->create()->id,
            'priority_id' => \App\Models\Priority::factory()->create()->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
            'technician_id' => $technician->id,
        ]);

        $ticket2 = Ticket::create([
            'number' => 'TEST-002',
            'topic' => 'Test Topic 2',
            'description' => 'Test Description 2',
            'user_id' => '2',
            'category_id' => \App\Models\Category::factory()->create()->id,
            'pharmacy_id' => \App\Models\Pharmacy::factory()->create()->id,
            'priority_id' => \App\Models\Priority::factory()->create()->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
            'technician_id' => $technician->id,
        ]);

        $technician->refresh();

        $this->assertCount(2, $technician->tickets);
        $this->assertTrue($technician->tickets->contains($ticket1));
        $this->assertTrue($technician->tickets->contains($ticket2));
    }

    #[Test]
    public function it_has_fillable_fields()
    {
        $technician = new Technician();
        $fillable = $technician->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('email', $fillable);
    }

    #[Test]
    public function it_can_be_found_by_email()
    {
        $technician = Technician::factory()->create(['email' => 'unique@example.com']);

        $found = Technician::where('email', 'unique@example.com')->first();

        $this->assertNotNull($found);
        $this->assertEquals($technician->id, $found->id);
    }

    #[Test]
    public function it_can_have_nullable_phone_and_email()
    {
        $technician = Technician::create([
            'name' => 'Jane Doe'
        ]);

        $this->assertInstanceOf(Technician::class, $technician);
        $this->assertEquals('Jane Doe', $technician->name);
        $this->assertNull($technician->phone);
        $this->assertNull($technician->email);
    }
}

