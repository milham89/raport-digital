@extends('layouts.app')
@section('title', 'Rekap Kelas ' . $class->name)
@section('page-title', 'Rekap Akademik Kelas ' . $class->name)
@section('content')
<div class="space-y-6">
    <div class="card flex justify-between items-center">
        <div>
            <h2 class="font-bold text-slate-800 dark:text-slate-100 text-lg">Rekap Capaian Kelas {{ $class->name }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Wali Kelas: {{ $class->homeroomTeacher->name ?? '-' }} &bull; Tahun: {{ $activeYear?->year }} ({{ $activeYear?->semester }})</p>
        </div>
        <a href="{{ route('principal.dashboard') }}" class="btn-secondary">&larr; Kembali</a>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">NIS</th><th class="table-head">Nama Siswa</th><th class="table-head text-center">Rata-rata Nilai</th><th class="table-head text-center">Status Ketuntasan</th><th class="table-head text-right">Rapor</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($students as $s)
                @php
                    $sGrades = $grades[$s->id] ?? collect();
                    $avg = $sGrades->whereNotNull('final_score')->count() > 0 ? round($sGrades->whereNotNull('final_score')->avg('final_score'), 1) : '-';
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <td class="table-cell font-mono text-xs">{{ $s->nis }}</td>
                    <td class="table-cell font-semibold text-slate-800 dark:text-slate-200">{{ $s->name }}</td>
                    <td class="table-cell text-center font-bold text-blue-600 dark:text-blue-400">{{ $avg }}</td>
                    <td class="table-cell text-center">
                        <span class="badge {{ is_numeric($avg) && $avg >= 75 ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300' }}">
                            {{ is_numeric($avg) ? ($avg >= 75 ? 'Memuaskan' : 'Perlu Bimbingan') : 'Belum Lengkap' }}
                        </span>
                    </td>
                    <td class="table-cell text-right">
                        <a href="{{ route('principal.report-card', [$class, $s]) }}" target="_blank" class="btn-secondary !py-1 !text-xs font-semibold">Cetak Raport</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
</div>
@endsection