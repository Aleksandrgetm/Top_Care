<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_points', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 20);
            $table->string('external_id')->nullable();
            $table->string('name');
            $table->string('country', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('raw_data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['provider', 'is_active']);
            $table->unique(['provider', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_points');
    }
};
