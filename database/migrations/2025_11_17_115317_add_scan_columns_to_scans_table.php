<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scans', function (Blueprint $table) {
            if (!Schema::hasColumn('scans', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
            }
            if (!Schema::hasColumn('scans', 'target')) {
                $table->string('target')->after('user_id');
            }
            if (!Schema::hasColumn('scans', 'scan_mode')) {
                $table->string('scan_mode')->after('target');
            }
            if (!Schema::hasColumn('scans', 'auto_detect')) {
                $table->boolean('auto_detect')->default(false)->after('scan_mode');
            }
            if (!Schema::hasColumn('scans', 'features')) {
                $table->json('features')->nullable()->after('auto_detect');
            }
            if (!Schema::hasColumn('scans', 'ports')) {
                $table->json('ports')->nullable()->after('features');
            }
            if (!Schema::hasColumn('scans', 'raw_output')) {
                $table->longText('raw_output')->nullable()->after('ports');
            }
        });
    }

    public function down(): void
    {
        Schema::table('scans', function (Blueprint $table) {
            $columns = ['user_id','target','scan_mode','auto_detect','features','ports','raw_output'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('scans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
