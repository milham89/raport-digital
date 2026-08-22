<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name',
        'school_logo',
        'school_level',
        'school_address',
        'principal_name',
        'principal_nip',
        'report_place',
        'report_date',
        'header_title',
    ];

    public static function getSettings(): self
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('school_settings') && !\Illuminate\Support\Facades\Schema::hasColumn('school_settings', 'school_logo')) {
                \Illuminate\Support\Facades\Schema::table('school_settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('school_logo')->nullable()->after('school_name');
                });
            }
        } catch (\Throwable $e) {
            // Ignore schema migration issues if any
        }

        return static::firstOrCreate([], [
            'school_name'     => 'SMA NEGERI INDONESIA',
            'school_level'    => 'SMA NEGERI',
            'school_address'  => 'Jl. Pendidikan No. 123',
            'principal_name'  => 'Drs. Budi Santoso, M.Pd.',
            'principal_nip'   => '196505151990011001',
            'report_place'    => 'Jakarta',
            'report_date'     => date('d F Y'),
            'header_title'    => 'LAPORAN HASIL CAPAIAN KOMPETENSI PESERTA DIDIK',
        ]);
    }
}
