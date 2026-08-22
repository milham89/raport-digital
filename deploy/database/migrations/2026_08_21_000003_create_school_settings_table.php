<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMA NEGERI INDONESIA');
            $table->string('school_level')->nullable()->default('SMA NEGERI');
            $table->string('school_address')->nullable()->default('Jl. Pendidikan No. 123');
            $table->string('principal_name')->default('Drs. Budi Santoso, M.Pd.');
            $table->string('principal_nip')->nullable()->default('196505151990011001');
            $table->string('report_place')->default('Jakarta');
            $table->string('report_date')->nullable();
            $table->string('header_title')->default('LAPORAN HASIL CAPAIAN KOMPETENSI PESERTA DIDIK');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
