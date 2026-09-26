<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_market_change_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('current_market_id')->nullable()->constrained('markets')->nullOnDelete();
            $table->foreignId('requested_market_id')->constrained('markets')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_market_change_requests');
    }
};
