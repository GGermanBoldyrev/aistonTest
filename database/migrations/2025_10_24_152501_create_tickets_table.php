<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('topic');
            $table->text('description');
            $table->boolean('is_warranty_case')->default(false);

            // Должно быть по связи но реально уже много времени потратил
            $table->string('user_id');

            // связи
            $table->foreignId('pharmacy_id')->constrained()->onDelete('cascade');
            $table->foreignId('priority_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('status_id')->default(1)->constrained();
            $table->foreignId('technician_id')->nullable()->constrained()->onDelete('set null');

            // Поля для метрик
            $table->timestamp('reacted_at')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index('topic');
            $table->index('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
