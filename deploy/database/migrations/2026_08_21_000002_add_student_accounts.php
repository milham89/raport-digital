<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add student_id to users table so a student can login
        Schema::table('students', function (Blueprint $table) {
            $table->string('password')->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};
