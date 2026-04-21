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

        Schema::table('stations', function (Blueprint $table) {
            $table->foreignId('operator_id')
                ->nullable()
                ->after('operator_name')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->dropForeignIdFor(null, 'operator_user_id');
            $table->dropColumn('operator_id');
        });
    }
};
