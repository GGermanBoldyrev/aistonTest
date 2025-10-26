<?php

namespace Tests\Unit;

use App\Models\Pharmacy;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PharmacyModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_pharmacy()
    {
        $pharmacy = Pharmacy::create([
            'code' => 'PH-001',
            'address' => '123 Main Street',
            'city' => 'New York'
        ]);

        $this->assertInstanceOf(Pharmacy::class, $pharmacy);
        $this->assertEquals('PH-001', $pharmacy->code);
        $this->assertEquals('123 Main Street', $pharmacy->address);
        $this->assertEquals('New York', $pharmacy->city);
    }

    #[Test]
    public function it_has_many_tickets()
    {
        $pharmacy = Pharmacy::factory()->create();

        $ticket1 = Ticket::create([
            'number' => 'TEST-001',
            'topic' => 'Test Topic 1',
            'description' => 'Test Description 1',
            'user_id' => '1',
            'category_id' => \App\Models\Category::factory()->create()->id,
            'pharmacy_id' => $pharmacy->id,
            'priority_id' => \App\Models\Priority::factory()->create()->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
        ]);

        $ticket2 = Ticket::create([
            'number' => 'TEST-002',
            'topic' => 'Test Topic 2',
            'description' => 'Test Description 2',
            'user_id' => '2',
            'category_id' => \App\Models\Category::factory()->create()->id,
            'pharmacy_id' => $pharmacy->id,
            'priority_id' => \App\Models\Priority::factory()->create()->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
        ]);

        $pharmacy->refresh();

        $this->assertCount(2, $pharmacy->tickets);
        $this->assertTrue($pharmacy->tickets->contains($ticket1));
        $this->assertTrue($pharmacy->tickets->contains($ticket2));
    }

    #[Test]
    public function it_has_fillable_fields()
    {
        $pharmacy = new Pharmacy();
        $fillable = $pharmacy->getFillable();

        $this->assertContains('code', $fillable);
        $this->assertContains('address', $fillable);
        $this->assertContains('city', $fillable);
    }

    #[Test]
    public function it_can_be_found_by_code()
    {
        $pharmacy = Pharmacy::factory()->create(['code' => 'PH-UNIQUE']);

        $found = Pharmacy::where('code', 'PH-UNIQUE')->first();

        $this->assertNotNull($found);
        $this->assertEquals($pharmacy->id, $found->id);
    }

    #[Test]
    public function it_can_have_nullable_city()
    {
        $pharmacy = Pharmacy::create([
            'code' => 'PH-002',
            'address' => '456 Second Street'
        ]);

        $this->assertInstanceOf(Pharmacy::class, $pharmacy);
        $this->assertEquals('PH-002', $pharmacy->code);
        $this->assertEquals('456 Second Street', $pharmacy->address);
        $this->assertNull($pharmacy->city);
    }
}

