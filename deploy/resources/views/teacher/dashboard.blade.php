@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Penilaian Guru')
@section('content')
<div class="space-y-6">
    <div class="card bg-gradient-to-r from-blue-600 to-indigo-700 text-white border-0 shadow-lg">
        <h2 class="text-xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="text-blue-100 text-sm mt-1">Silakan pilih kelas dan mata pelajaran yang Anda ampu untuk melakukan input atau pembaruan nilai.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($assignments as $a)
        <div class="card hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <span class="badge bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 mb-2">{{ $a->schoolClass->name ?? '-' }}</span>
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 text-lg">{{ $a->subject->name ?? '-' }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">KKM Mapel: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $a->subject->kkm ?? 75 }}</span></p>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $a->academicYear->year ?? '-' }} ({{ $a->academicYear->semester ?? '-' }})</span>
                <a href="{{ route('teacher.grades', $a) }}" class="btn-primary">Input Nilai &rarr;</a>
            </div>
        </div>
        @empty
        <div class="col-span-full card text-center py-12 text-slate-500 dark:text-slate-400">Belum ada kelas/mapel yang ditugaskan kepada Anda.</div>
        @endforelse
    </div>
</div>
@endsection