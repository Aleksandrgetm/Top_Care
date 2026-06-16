<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('supports_courier')->default(true)->after('is_active');
            $table->boolean('supports_dpd')->default(false)->after('supports_courier');
            $table->boolean('supports_omniva')->default(false)->after('supports_dpd');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'supports_courier',
                'supports_dpd',
                'supports_omniva',
            ]);
        });
    }
};
