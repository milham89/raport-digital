<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->unique();
            $table->string('role')->default('teacher'); // admin, teacher, homeroom, principal
            $table->boolean('is_active')->default(true);
        });

        // Academic Years
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('year'); // e.g. 2024/2025
            $table->string('semester'); // GANJIL, GENAP
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Classes
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade_level');
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->timestamps();
        });

        // Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('kkm', 5, 2)->default(75);
            $table->timestamps();
        });

        // Teacher Assignments
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->timestamps();
        });

        // Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nisn')->unique();
            $table->string('name');
            $table->string('gender'); // L, P
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->timestamps();
        });

        // Grades
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('tp1', 5, 2)->nullable();
            $table->decimal('tp2', 5, 2)->nullable();
            $table->decimal('formatif', 5, 2)->nullable();
            $table->decimal('sumatif', 5, 2)->nullable();
            $table->decimal('pas', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->string('letter_grade', 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Student Attendance & Remarks
        Schema::create('student_attendance_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->integer('sick')->default(0);
            $table->integer('permission')->default(0);
            $table->integer('unexcused')->default(0);
            $table->text('homeroom_remark')->nullable();
            $table->string('status')->default('DRAFT'); // DRAFT, FINALIZED, APPROVED
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendance_remarks');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teacher_assignments');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('academic_years');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'role', 'is_active']);
        });
    }
};
