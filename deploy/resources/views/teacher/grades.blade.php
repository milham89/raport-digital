@extends('layouts.app')
@section('title', 'Input Nilai')
@section('page-title', 'Input Nilai Siswa')
@section('content')
<div class="space-y-6">
    <div class="card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $assignment->subject->name }} - Kelas {{ $assignment->schoolClass->name }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tahun Ajaran: {{ $assignment->academicYear->year }} ({{ $assignment->academicYear->semester }}) &bull; KKM: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $assignment->subject->kkm }}</span></p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn-secondary">&larr; Kembali</a>
    </div>

    <div class="card overflow-hidden p-0">
        <form method="POST" action="{{ route('teacher.grades.store', $assignment) }}">
            @csrf
            <div class="overflow-x-auto"><table class="w-full min-w-[840px]">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800">
                        <th class="table-head w-28">NIS</th>
                        <th class="table-head min-w-[200px]">Nama Siswa</th>
                        <th class="table-head text-center w-28 px-3">TP 1</th>
                        <th class="table-head text-center w-28 px-3">TP 2</th>
                        <th class="table-head text-center w-28 px-3">Formatif</th>
                        <th class="table-head text-center w-28 px-3">Sumatif</th>
                        <th class="table-head text-center w-28 px-3">PAS</th>
                        <th class="table-head text-center w-28">Nilai Akhir</th>
                        <th class="table-head text-center w-24">Predikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($students as $student)
                    @php $grade = $grades[$student->id] ?? null; @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                        <td class="table-cell font-mono text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $student->nis }}</td>
                        <td class="table-cell font-semibold text-slate-800 dark:text-slate-200 whitespace-nowrap">{{ $student->name }}</td>
                        <td class="table-cell text-center px-2 py-3">
                            <input type="number" step="any" min="0" max="100" name="grades[{{ $student->id }}][tp1]" value="{{ $grade?->tp1 }}" class="input-score" placeholder="0">
                        </td>
                        <td class="table-cell text-center px-2 py-3">
                            <input type="number" step="any" min="0" max="100" name="grades[{{ $student->id }}][tp2]" value="{{ $grade?->tp2 }}" class="input-score" placeholder="0">
                        </td>
                        <td class="table-cell text-center px-2 py-3">
                            <input type="number" step="any" min="0" max="100" name="grades[{{ $student->id }}][formatif]" value="{{ $grade?->formatif }}" class="input-score" placeholder="0">
                        </td>
                        <td class="table-cell text-center px-2 py-3">
                            <input type="number" step="any" min="0" max="100" name="grades[{{ $student->id }}][sumatif]" value="{{ $grade?->sumatif }}" class="input-score" placeholder="0">
                        </td>
                        <td class="table-cell text-center px-2 py-3">
                            <input type="number" step="any" min="0" max="100" name="grades[{{ $student->id }}][pas]" value="{{ $grade?->pas }}" class="input-score" placeholder="0">
                        </td>
                        <td class="table-cell text-center font-bold text-base text-blue-600 dark:text-blue-400">{{ $grade?->final_score ?? '-' }}</td>
                        <td class="table-cell text-center">
                            @if($grade?->letter_grade)
                                <span class="badge {{ in_array($grade->letter_grade, ['A','B']) ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : ($grade->letter_grade === 'C' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300') }}">{{ $grade->letter_grade }}</span>
                            @else
                                <span class="text-slate-500 dark:text-slate-400 font-bold">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table></div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="submit" class="btn-primary !px-6 py-2.5 font-bold shadow-md shadow-blue-500/30">Simpan Seluruh Nilai</button>
            </div>
        </form>
    </div>
</div>
@endsection