<?php

namespace Tests\Unit;

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

class TicketModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_ticket()
    {
        $pharmacy = Pharmacy::factory()->create();
        $priority = Priority::factory()->create();
        $category = Category::factory()->create();
        $status = Status::factory()->create();

        $ticket = Ticket::create([
            'number' => 'TICKET-001',
            'topic' => 'Test Topic',
            'description' => 'Test Description',
            'user_id' => '123',
            'is_warranty_case' => true,
            'pharmacy_id' => $pharmacy->id,
            'priority_id' => $priority->id,
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals('TICKET-001', $ticket->number);
        $this->assertEquals('Test Topic', $ticket->topic);
        $this->assertEquals('Test Description', $ticket->description);
        $this->assertEquals('123', $ticket->user_id);
        $this->assertTrue($ticket->is_warranty_case);
    }

    #[Test]
    public function it_belongs_to_pharmacy()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(Pharmacy::class, $ticket->pharmacy);
        $this->assertNotNull($ticket->pharmacy->id);
    }

    #[Test]
    public function it_belongs_to_priority()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(Priority::class, $ticket->priority);
        $this->assertNotNull($ticket->priority->id);
    }

    #[Test]
    public function it_belongs_to_category()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(Category::class, $ticket->category);
        $this->assertNotNull($ticket->category->id);
    }

    #[Test]
    public function it_belongs_to_status()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(Status::class, $ticket->status);
        $this->assertNotNull($ticket->status->id);
    }

    #[Test]
    public function it_belongs_to_technician()
    {
        $technician = Technician::factory()->create();
        $ticket = Ticket::factory()->create([
            'technician_id' => $technician->id,
        ]);

        $this->assertInstanceOf(Technician::class, $ticket->technician);
        $this->assertEquals($technician->id, $ticket->technician->id);
    }

    #[Test]
    public function it_has_many_attachments()
    {
        $ticket = Ticket::factory()->create();

        $attachment1 = $ticket->attachments()->create([
            'disk' => 'public',
            'path' => 'tickets/test1.pdf',
            'original_name' => 'test1.pdf',
            'mime' => 'application/pdf',
            'size' => 1024,
        ]);

        $attachment2 = $ticket->attachments()->create([
            'disk' => 'public',
            'path' => 'tickets/test2.pdf',
            'original_name' => 'test2.pdf',
            'mime' => 'application/pdf',
            'size' => 2048,
        ]);

        $ticket->refresh();

        $this->assertCount(2, $ticket->attachments);
        $this->assertTrue($ticket->attachments->contains($attachment1));
        $this->assertTrue($ticket->attachments->contains($attachment2));
    }

    #[Test]
    public function it_has_fillable_fields()
    {
        $ticket = new Ticket();
        $fillable = $ticket->getFillable();

        $this->assertContains('number', $fillable);
        $this->assertContains('topic', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('user_id', $fillable);
        $this->assertContains('is_warranty_case', $fillable);
        $this->assertContains('pharmacy_id', $fillable);
        $this->assertContains('priority_id', $fillable);
        $this->assertContains('category_id', $fillable);
        $this->assertContains('status_id', $fillable);
        $this->assertContains('technician_id', $fillable);
    }

    #[Test]
    public function it_casts_is_warranty_case_to_boolean()
    {
        $ticket = Ticket::factory()->create([
            'is_warranty_case' => 1,
        ]);

        $this->assertIsBool($ticket->is_warranty_case);
        $this->assertTrue($ticket->is_warranty_case);
    }

    #[Test]
    public function it_can_be_found_by_number()
    {
        $ticket = Ticket::factory()->create([
            'number' => 'UNIQUE-TICKET-001',
        ]);

        $found = Ticket::where('number', 'UNIQUE-TICKET-001')->first();

        $this->assertNotNull($found);
        $this->assertEquals($ticket->id, $found->id);
    }
}

