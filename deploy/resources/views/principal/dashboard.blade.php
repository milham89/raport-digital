@extends('layouts.app')
@section('title', 'Dashboard Kepala Sekolah')
@section('page-title', 'Rekap Nilai Sekolah')
@section('content')
<div class="space-y-6">
    <div class="card bg-gradient-to-r from-amber-500 to-orange-600 text-white border-0 shadow-lg">
        <h2 class="text-xl font-bold">Panel Kepala Sekolah</h2>
        <p class="text-amber-100 text-sm mt-1">Tahun Ajaran: {{ $activeYear?->year }} ({{ $activeYear?->semester }}) &bull; Pantau capaian akademik per kelas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($classes as $c)
        <div class="card hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <span class="badge bg-amber-50 text-amber-700 mb-2">Tingkat {{ $c->grade_level }}</span>
                    <h3 class="font-bold text-slate-800 text-lg">{{ $c->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Wali: <span class="font-semibold text-slate-700">{{ $c->homeroomTeacher->name ?? 'Belum ada' }}</span></p>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">{{ $c->students->count() }} Siswa</span>
                <a href="{{ route('principal.class-report', $c) }}" class="btn-primary !bg-amber-600 hover:!bg-amber-700">Lihat Rekap &rarr;</a>
            </div>
        </div>
        @empty
        <div class="col-span-full card text-center py-12 text-slate-400">Belum ada kelas terdaftar.</div>
        @endforelse
    </div>
</div>
@endsection