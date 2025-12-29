<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            // DDoS specific
            $table->string('ddos_mode')->nullable();      // low | medium | high
            $table->string('target')->nullable();         // login | api | website
            $table->integer('request_rate')->nullable();  // req/sec
            $table->integer('duration')->nullable();      // seconds
            $table->integer('total_requests')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->dropColumn([
                'ddos_mode',
                'target',
                'request_rate',
                'duration',
                'total_requests',
            ]);
        });
    }
};
