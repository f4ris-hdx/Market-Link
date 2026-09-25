<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'market_id']);
            $table->index(['market_id', 'status']);
        });

        // Existing catalog products should remain visible after this migration.
        // Give them an approved placement at their farmer's main market.
        $now = now();
        $rows = DB::table('products')
            ->join('farmers', 'farmers.id', '=', 'products.farmer_id')
            ->whereNotNull('farmers.market_id')
            ->select([
                'products.id as product_id',
                'farmers.market_id as market_id',
            ])
            ->get()
            ->map(fn ($row) => [
                'product_id' => $row->product_id,
                'market_id' => $row->market_id,
                'status' => 'approved',
                'reviewed_by' => null,
                'reviewed_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows !== []) {
            DB::table('market_product')->insertOrIgnore($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('market_product');
    }
};
