<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('market_product')
            ->join('products', 'products.id', '=', 'market_product.product_id')
            ->join('farmers', 'farmers.id', '=', 'products.farmer_id')
            ->where('market_product.status', 'pending')
            ->where('products.status', 'approved')
            ->where('farmers.status', 'verified')
            ->update([
                'market_product.status' => 'approved',
                'market_product.reviewed_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Approved placements are intentionally not reverted to pending.
    }
};
