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
            if (! Schema::hasColumn('before_after_items', 'title')) {
                $table->string('title')->nullable();
            }

            if (! Schema::hasColumn('before_after_items', 'description')) {
                $table->text('description')->nullable();
            }

            if (! Schema::hasColumn('before_after_items', 'image')) {
                $table->string('image')->nullable();
            }

            if (! Schema::hasColumn('before_after_items', 'before_image')) {
                $table->string('before_image')->nullable();
            }

            if (! Schema::hasColumn('before_after_items', 'after_image')) {
                $table->string('after_image')->nullable();
            }

            if (! Schema::hasColumn('before_after_items', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('before_after_items')) {
            return;
        }

        Schema::table('before_after_items', function (Blueprint $table) {
            if (Schema::hasColumn('before_after_items', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('before_after_items', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('before_after_items', 'image')) {
                $table->dropColumn('image');
            }

            if (Schema::hasColumn('before_after_items', 'before_image')) {
                $table->dropColumn('before_image');
            }

            if (Schema::hasColumn('before_after_items', 'after_image')) {
                $table->dropColumn('after_image');
            }

            if (Schema::hasColumn('before_after_items', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
