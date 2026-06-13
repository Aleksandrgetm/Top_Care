<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('before_after_items')) {
            return;
        }

        Schema::table('before_after_items', function (Blueprint $table) {
            if (! Schema::hasColumn('before_after_items', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('before_after_items')) {
            return;
        }

        Schema::table('before_after_items', function (Blueprint $table) {
            if (Schema::hasColumn('before_after_items', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
