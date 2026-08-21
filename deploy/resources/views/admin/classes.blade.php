@extends('layouts.app')
@section('title', 'Kelas')
@section('page-title', 'Kelola Kelas')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card h-fit">
        <h3 class="font-bold text-slate-800 mb-4 text-base">+ Tambah Kelas Baru</h3>
        <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-4">
            @csrf
            <div><label class="label">Nama Kelas</label><input type="text" name="name" placeholder="X IPA 1" required class="input"></div>
            <div><label class="label">Tingkat</label><input type="text" name="grade_level" placeholder="10" required class="input"></div>
            <div><label class="label">Wali Kelas</label>
                <select name="homeroom_teacher_id" class="select">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
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
            <button type="submit" class="w-full btn-primary">Tambah Kelas</button>
        </form>
    </div>
    <div class="lg:col-span-2 card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">Nama Kelas</th><th class="table-head">Tingkat</th><th class="table-head">Wali Kelas</th><th class="table-head">Tahun</th><th class="table-head text-right">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($classes as $class)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell font-bold text-slate-800">{{ $class->name }}</td>
                    <td class="table-cell text-slate-500">Kelas {{ $class->grade_level }}</td>
                    <td class="table-cell font-medium">{{ $class->homeroomTeacher->name ?? 'Belum Ditentukan' }}</td>
                    <td class="table-cell text-xs text-slate-500">{{ $class->academicYear->year ?? '-' }} ({{ $class->academicYear->semester ?? '-' }})</td>
                    <td class="table-cell text-right">
                        <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" class="inline" onsubmit="return confirm('Hapus kelas?')">
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