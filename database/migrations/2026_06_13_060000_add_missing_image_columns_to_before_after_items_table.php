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
            if (! Schema::hasColumn('before_after_items', 'before_image')) {
                $table->string('before_image')->nullable();
            }

            if (! Schema::hasColumn('before_after_items', 'after_image')) {
                $table->string('after_image')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('before_after_items')) {
            return;
        }

        Schema::table('before_after_items', function (Blueprint $table) {
            if (Schema::hasColumn('before_after_items', 'before_image')) {
                $table->dropColumn('before_image');
            }

            if (Schema::hasColumn('before_after_items', 'after_image')) {
                $table->dropColumn('after_image');
            }
        });
    }
};
