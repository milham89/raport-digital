@extends('layouts.app')
@section('title', 'Penugasan Guru')
@section('page-title', 'Penugasan Guru Mapel')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card h-fit">
        <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-4 text-base">+ Tambah Penugasan</h3>
        <form method="POST" action="{{ route('admin.assignments.store') }}" class="space-y-4">
            @csrf
            <div><label class="label">Guru</label>
                <select name="teacher_id" class="select" required>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">Mata Pelajaran</label>
                <select name="subject_id" class="select" required>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">Kelas</label>
                <select name="class_id" class="select" required>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">Tahun Ajaran</label>
                <select name="academic_year_id" class="select" required>
                    @foreach($years as $year)
                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>{{ $year->year }} - {{ $year->semester }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full btn-primary">Tugaskan Guru</button>
        </form>
    </div>
    <div class="lg:col-span-2 card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">Nama Guru</th><th class="table-head">Mata Pelajaran</th><th class="table-head">Kelas</th><th class="table-head">Tahun</th><th class="table-head text-right">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($assignments as $a)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <td class="table-cell font-semibold text-slate-800 dark:text-slate-200">{{ $a->teacher->name ?? '-' }}</td>
                    <td class="table-cell font-medium text-blue-600 dark:text-blue-400">{{ $a->subject->name ?? '-' }}</td>
                    <td class="table-cell font-bold text-slate-700 dark:text-slate-300">{{ $a->schoolClass->name ?? '-' }}</td>
                    <td class="table-cell text-xs text-slate-500 dark:text-slate-400">{{ $a->academicYear->year ?? '-' }}</td>
                    <td class="table-cell text-right">
                        <form method="POST" action="{{ route('admin.assignments.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus penugasan?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
</div>
@endsection