@extends('layouts.app')
@section('title', 'Portal Siswa')
@section('page-title', 'Dashboard Siswa')
@section('content')
<div class="space-y-6">
    <div class="card bg-gradient-to-r from-blue-600 to-indigo-700 text-white border-0 shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">{{ $student->name }}</h2>
                <p class="text-blue-100 text-sm">NIS: {{ $student->nis }} &bull; NISN: {{ $student->nisn }} &bull; Kelas: {{ $student->schoolClass->name ?? '-' }}</p>
                <p class="text-xs text-blue-200 mt-1">Tahun Ajaran: {{ $activeYear?->year }} ({{ $activeYear?->semester }})</p>
            </div>
            <a href="{{ route('student.report-card') }}" class="btn-secondary !bg-white !text-blue-700 font-bold self-start sm:self-auto">Cetak Raport</a>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="card"><p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Rata-rata Nilai</p><p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $avgScore }}</p></div>
        <div class="card"><p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Mapel Tuntas</p><p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $passedCount }} <span class="text-xs font-normal text-slate-400 dark:text-slate-500">/ {{ $grades->count() }}</span></p></div>
        <div class="card"><p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Absensi (S/I/A)</p><p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $remark?->sick ?? 0 }} / {{ $remark?->permission ?? 0 }} / {{ $remark?->unexcused ?? 0 }}</p></div>
    </div>
    <div class="card overflow-hidden p-0">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between"><h3 class="font-bold text-slate-800 dark:text-slate-100">Daftar Nilai Akademik</h3><span class="text-xs text-slate-500 dark:text-slate-400">{{ $activeYear?->semester }}</span></div>
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">Mata Pelajaran</th><th class="table-head text-center">KKM</th><th class="table-head text-center">TP1</th><th class="table-head text-center">TP2</th><th class="table-head text-center">Formatif</th><th class="table-head text-center">Sumatif</th><th class="table-head text-center">PAS</th><th class="table-head text-center">Nilai Akhir</th><th class="table-head text-center">Predikat</th><th class="table-head text-center">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($grades as $grade)
                @php $kkm = $grade->subject->kkm ?? 75; $passed = $grade->final_score >= $kkm; @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <td class="table-cell font-semibold text-slate-800 dark:text-slate-200">{{ $grade->subject->name ?? '-' }}</td>
                    <td class="table-cell text-center text-slate-500 dark:text-slate-400">{{ $kkm }}</td>
                    <td class="table-cell text-center">{{ $grade->tp1 ?? '-' }}</td>
                    <td class="table-cell text-center">{{ $grade->tp2 ?? '-' }}</td>
                    <td class="table-cell text-center">{{ $grade->formatif ?? '-' }}</td>
                    <td class="table-cell text-center">{{ $grade->sumatif ?? '-' }}</td>
                    <td class="table-cell text-center">{{ $grade->pas ?? '-' }}</td>
                    <td class="table-cell text-center font-bold {{ $passed ? 'text-blue-600 dark:text-blue-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $grade->final_score ?? '-' }}</td>
                    <td class="table-cell text-center font-semibold">{{ $grade->letter_grade ?? '-' }}</td>
                    <td class="table-cell text-center"><span class="badge {{ $passed ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' }}">{{ $passed ? 'Tuntas' : 'Belum' }}</span></td>
                </tr>
                @empty
                <tr><td colspan="10" class="table-cell text-center text-slate-500 dark:text-slate-400 py-6">Belum ada nilai yang diinput.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
</div>
@endsection