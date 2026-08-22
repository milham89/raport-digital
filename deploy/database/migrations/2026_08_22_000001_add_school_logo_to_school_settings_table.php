<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('school_settings') && !Schema::hasColumn('school_settings', 'school_logo')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->string('school_logo')->nullable()->after('school_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('school_settings') && Schema::hasColumn('school_settings', 'school_logo')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->dropColumn('school_logo');
            });
        }
    }
};
