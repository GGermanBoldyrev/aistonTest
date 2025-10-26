<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем пользователя и авторизуемся
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_category()
    {
        $category = Category::create([
            'name' => 'Test Category'
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
    }

    #[Test]
    public function it_has_many_tickets()
    {
        $category = Category::factory()->create();

        // Создаем тикеты напрямую с минимальными данными
        $ticket1 = Ticket::create([
            'number' => 'TEST-001',
            'topic' => 'Test Topic 1',
            'description' => 'Test Description 1',
            'user_id' => '1',
            'category_id' => $category->id,
            'pharmacy_id' => \App\Models\Pharmacy::factory()->create()->id,
            'priority_id' => \App\Models\Priority::factory()->create()->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
        ]);

        $ticket2 = Ticket::create([
            'number' => 'TEST-002',
            'topic' => 'Test Topic 2',
            'description' => 'Test Description 2',
            'user_id' => '2',
            'category_id' => $category->id,
            'pharmacy_id' => \App\Models\Pharmacy::factory()->create()->id,
            'priority_id' => \App\Models\Priority::factory()->create()->id,
            'status_id' => \App\Models\Status::factory()->create()->id,
        ]);

        // Перезагружаем категорию с тикетами
        $category->refresh();

        $this->assertCount(2, $category->tickets);
        $this->assertTrue($category->tickets->contains($ticket1));
        $this->assertTrue($category->tickets->contains($ticket2));
    }

    #[Test]
    public function it_has_many_hints()
    {
        $category = Category::factory()->create();

        $hint1 = $category->hints()->create(['text' => 'First hint', 'hint_type' => 'positive']);
        $hint2 = $category->hints()->create(['text' => 'Second hint', 'hint_type' => 'negative']);

        $this->assertCount(2, $category->hints);
        $this->assertTrue($category->hints->contains($hint1));
        $this->assertTrue($category->hints->contains($hint2));
    }

    #[Test]
    public function it_has_fillable_name()
    {
        $category = new Category();
        $fillable = $category->getFillable();

        $this->assertContains('name', $fillable);
    }

    #[Test]
    public function it_can_be_found_by_name()
    {
        $category = Category::factory()->create(['name' => 'Unique Category Name']);

        $found = Category::where('name', 'Unique Category Name')->first();

        $this->assertNotNull($found);
        $this->assertEquals($category->id, $found->id);
    }
}
