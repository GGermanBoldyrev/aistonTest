<?php

namespace Tests\Unit;

use App\Models\Priority;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PriorityModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_priority()
    {
        $priority = Priority::create([
            'name' => 'Test Priority',
            'color' => '#FF0000',
            'description' => 'Test description'
        ]);

        $this->assertInstanceOf(Priority::class, $priority);
        $this->assertEquals('Test Priority', $priority->name);
        $this->assertEquals('#FF0000', $priority->color);
        $this->assertEquals('Test description', $priority->description);
    }

    #[Test]
    public function it_has_many_tickets()
    {
        $priority = Priority::factory()->create();

        $ticket1 = Ticket::create([
            'number' => 'TEST-001',
            'topic' => 'Test Topic 1',
            'description' => 'Test Description 1',
            'user_id' => '1',
            'category_id' => \App\Models\Category::factory()->create()->id,
            'pharmacy_id' => \App\Models\Pharmacy::factory()->create()->id,
            'priority_id' => $priority->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
        ]);

        $ticket2 = Ticket::create([
            'number' => 'TEST-002',
            'topic' => 'Test Topic 2',
            'description' => 'Test Description 2',
            'user_id' => '2',
            'category_id' => \App\Models\Category::factory()->create()->id,
            'pharmacy_id' => \App\Models\Pharmacy::factory()->create()->id,
            'priority_id' => $priority->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
        ]);

        $priority->refresh();

        $this->assertCount(2, $priority->tickets);
        $this->assertTrue($priority->tickets->contains($ticket1));
        $this->assertTrue($priority->tickets->contains($ticket2));
    }

    #[Test]
    public function it_has_fillable_fields()
    {
        $priority = new Priority();
        $fillable = $priority->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('color', $fillable);
        $this->assertContains('description', $fillable);
    }

    #[Test]
    public function it_can_be_found_by_name()
    {
        $priority = Priority::factory()->create(['name' => 'Unique Priority Name']);

        $found = Priority::where('name', 'Unique Priority Name')->first();

        $this->assertNotNull($found);
        $this->assertEquals($priority->id, $found->id);
    }

    #[Test]
    public function it_can_have_nullable_color_and_description()
    {
        $priority = Priority::create([
            'name' => 'Simple Priority'
        ]);

        $this->assertInstanceOf(Priority::class, $priority);
        $this->assertEquals('Simple Priority', $priority->name);
        $this->assertNull($priority->color);
        $this->assertNull($priority->description);
    }
}

