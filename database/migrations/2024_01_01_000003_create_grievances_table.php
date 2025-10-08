<?php
// database/migrations/2024_01_01_000003_create_grievances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->text('description');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'under_investigation', 'resolved', 'closed'])->default('pending');
            $table->text('investigation_report')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
            
            $table->index('reference_number');
            $table->index('status');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievances');
    }
};