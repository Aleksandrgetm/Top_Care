<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_provider', 20)->nullable()->after('delivery_method');
            $table->foreignId('delivery_point_id')->nullable()->after('delivery_provider')->constrained('delivery_points')->nullOnDelete();
            $table->string('delivery_point_name')->nullable()->after('delivery_point_id');
            $table->text('delivery_point_address')->nullable()->after('delivery_point_name');
            $table->string('delivery_city')->nullable()->after('delivery_point_address');
            $table->string('delivery_postal_code', 32)->nullable()->after('delivery_city');
            $table->decimal('delivery_price', 10, 2)->nullable()->after('delivery_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_point_id');
            $table->dropColumn([
                'delivery_provider',
                'delivery_point_name',
                'delivery_point_address',
                'delivery_city',
                'delivery_postal_code',
                'delivery_price',
            ]);
        });
    }
};
