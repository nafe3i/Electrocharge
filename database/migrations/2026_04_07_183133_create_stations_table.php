<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();

            
            // lien avec API OpenChargeMap
            $table->string('ocm_id')->unique();

            // infos principales
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('city');

            // géolocalisation
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            // opérateur
            $table->string('operator_name')->nullable();

            // horaires
            $table->text('opening_hours')->nullable();

            // image
            $table->string('photo_url')->nullable();

            // actif ou non
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
