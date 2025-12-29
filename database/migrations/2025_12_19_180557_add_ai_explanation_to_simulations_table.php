<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->longText('ai_explanation')->nullable()
                  ->after('metadata');
        });
    }

    public function down(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->dropColumn('ai_explanation');
        });
    }
};
