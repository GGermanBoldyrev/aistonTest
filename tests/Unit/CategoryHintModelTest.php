<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\CategoryHint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryHintModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_category_hint()
    {
        $category = Category::factory()->create();

        $hint = CategoryHint::create([
            'category_id' => $category->id,
            'text' => 'Test hint text with minimum length',
            'hint_type' => 'positive',
            'order_column' => 1
        ]);

        $this->assertInstanceOf(CategoryHint::class, $hint);
        $this->assertEquals('Test hint text with minimum length', $hint->text);
        $this->assertEquals('positive', $hint->hint_type);
        $this->assertEquals(1, $hint->order_column);
    }

    #[Test]
    public function it_belongs_to_category()
    {
        $category = Category::factory()->create();
        $hint = CategoryHint::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $hint->category);
        $this->assertEquals($category->id, $hint->category->id);
    }

    #[Test]
    public function it_has_fillable_fields()
    {
        $hint = new CategoryHint();
        $fillable = $hint->getFillable();

        $this->assertContains('category_id', $fillable);
        $this->assertContains('text', $fillable);
        $this->assertContains('hint_type', $fillable);
        $this->assertContains('order_column', $fillable);
    }

    #[Test]
    public function it_can_be_found_by_text()
    {
        $hint = CategoryHint::factory()->create(['text' => 'Unique hint text']);

        $found = CategoryHint::where('text', 'Unique hint text')->first();

        $this->assertNotNull($found);
        $this->assertEquals($hint->id, $found->id);
    }

    #[Test]
    public function it_can_be_found_by_hint_type()
    {
        CategoryHint::factory()->create(['hint_type' => 'positive']);
        CategoryHint::factory()->create(['hint_type' => 'negative']);

        $positiveHints = CategoryHint::where('hint_type', 'positive')->get();
        $negativeHints = CategoryHint::where('hint_type', 'negative')->get();

        $this->assertCount(1, $positiveHints);
        $this->assertCount(1, $negativeHints);
    }

    #[Test]
    public function it_can_be_ordered_by_order_column()
    {
        $category = Category::factory()->create();

        $hint1 = CategoryHint::factory()->create([
            'category_id' => $category->id,
            'order_column' => 2
        ]);

        $hint2 = CategoryHint::factory()->create([
            'category_id' => $category->id,
            'order_column' => 1
        ]);

        $hint3 = CategoryHint::factory()->create([
            'category_id' => $category->id,
            'order_column' => 3
        ]);

        $orderedHints = CategoryHint::where('category_id', $category->id)
            ->orderBy('order_column')
            ->get();

        $this->assertEquals($hint2->id, $orderedHints[0]->id);
        $this->assertEquals($hint1->id, $orderedHints[1]->id);
        $this->assertEquals($hint3->id, $orderedHints[2]->id);
    }
}

