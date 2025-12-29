<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->integer('emails_sent')->nullable();
            $table->integer('clicked_links')->nullable();
            $table->integer('entered_details')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->dropColumn([
                'emails_sent',
                'clicked_links',
                'entered_details',
            ]);
        });
    }

};
