@extends('layouts.app')
@section('title', 'Dashboard Wali Kelas')
@section('page-title', 'Dashboard Kelas Binaan')
@section('content')
<div class="space-y-6">
    <div class="card bg-gradient-to-r from-emerald-600 to-teal-700 text-white border-0 shadow-lg">
        <h2 class="text-xl font-bold">Wali Kelas: {{ $class ? $class->name : 'Belum Ditugaskan' }}</h2>
        <p class="text-emerald-100 text-sm mt-1">Tahun Ajaran: {{ $activeYear ? $activeYear->year : '' }} ({{ $activeYear ? $activeYear->semester : '' }}) &bull; Total Siswa: {{ $students->count() }} orang</p>
    </div>

    @if($class)
    <div class="flex flex-wrap justify-between items-center gap-3">
        <h3 class="font-bold text-slate-800 text-base">Daftar Nilai & Presensi Rapor Siswa</h3>
        <div class="flex items-center gap-2">
            <a href="{{ route('homeroom.attendance.daily') }}" class="btn-primary !text-xs font-semibold">Absensi Harian (Senin - Jum'at)</a>
            <a href="{{ route('homeroom.attendance.monthly') }}" class="btn-secondary !text-xs font-semibold">Rekap Bulanan</a>
            <a href="{{ route('homeroom.remarks') }}" class="btn-secondary !text-xs font-semibold">Catatan & Absensi Rapor &rarr;</a>
        </div>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">NIS</th><th class="table-head">Nama Siswa</th><th class="table-head text-center">Rata-rata Nilai</th><th class="table-head text-center">Absensi (S/I/A)</th><th class="table-head text-right">Cetak Raport</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($students as $s)
                @php
                    $sGrades = $grades[$s->id] ?? collect();
                    $avg = $sGrades->whereNotNull('final_score')->count() > 0 ? round($sGrades->whereNotNull('final_score')->avg('final_score'), 1) : '-';
                    $rem = $remarks[$s->id] ?? null;
                @endphp
                <tr class="hover:bg-slate-50">
                    <td class="table-cell font-mono text-xs">{{ $s->nis }}</td>
                    <td class="table-cell font-semibold text-slate-800">{{ $s->name }}</td>
                    <td class="table-cell text-center font-bold text-blue-600">{{ $avg }}</td>
                    <td class="table-cell text-center text-xs text-slate-500">{{ $rem && isset($rem->sick) ? $rem->sick : 0 }} / {{ $rem && isset($rem->permission) ? $rem->permission : 0 }} / {{ $rem && isset($rem->unexcused) ? $rem->unexcused : 0 }}</td>
                    <td class="table-cell text-right">
                        <a href="{{ route('homeroom.report-card', $s) }}" target="_blank" class="btn-secondary !py-1 !text-xs font-semibold">Lihat / Cetak Raport</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
    @else
    <div class="card text-center py-12 text-slate-400">Anda belum ditugaskan sebagai wali kelas aktif saat ini.</div>
    @endif
</div>
@endsection