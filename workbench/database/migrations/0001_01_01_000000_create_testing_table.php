<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testing', function (Blueprint $table) {
            $table->id();
            $table->json('bag')->nullable();
            $table->json('collection')->nullable();
            $table->json('custom_collection')->nullable();
            $table->json('hidden_bag')->nullable();
            $table->json('nulls_bag')->nullable();
            $table->json('optional_bag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testing');
    }
};
