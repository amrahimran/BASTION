<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();

            // Type of simulation
            $table->string('simulation_type'); 
            // e.g. MITM, DDoS, PHISHING

            // General status
            $table->string('status')->default('Completed');

            // MITM / DDoS generic metrics
            $table->integer('intercepted_packets')->nullable();
            $table->integer('exposed_credentials')->nullable();

            // Risk
            $table->string('risk_level')->nullable();

            // Store extra data if needed later
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
