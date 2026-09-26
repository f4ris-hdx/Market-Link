<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('reviews')
            ->whereNotNull('product_id')
            ->update(['farmer_id' => null]);
    }

    public function down(): void
    {
        // Product reviews intentionally do not duplicate their product's farmer ID.
    }
};
