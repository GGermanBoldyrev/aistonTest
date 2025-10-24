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
            $table->foreignId('pharmacy_id')->nullable()->constrained();
            $table->string('topic');
            $table->foreignId('priority_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('technician_id')->nullable()->constrained();
            $table->foreignId('status_id')->default(1)->constrained();
            $table->timestamps();
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
