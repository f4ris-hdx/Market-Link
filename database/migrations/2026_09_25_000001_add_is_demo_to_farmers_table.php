<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('farmers', 'is_demo')) {
            Schema::table('farmers', function (Blueprint $table) {
                $table->boolean('is_demo')->default(false)->after('status');
            });
        }

        // Existing farmer profiles without a login are development/sample data.
        DB::table('farmers')->whereNull('user_id')->update(['is_demo' => true]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('farmers', 'is_demo')) {
            Schema::table('farmers', function (Blueprint $table) {
                $table->dropColumn('is_demo');
            });
        }
    }
};
