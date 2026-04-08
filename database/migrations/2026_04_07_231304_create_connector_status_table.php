<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('connector_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connector_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('status', ['libre', 'occupée', 'hors_service']);
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('last_updated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connector_status');
    }
};
