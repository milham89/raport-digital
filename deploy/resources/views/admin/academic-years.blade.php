@extends('layouts.app')
@section('title', 'Tahun Ajaran')
@section('page-title', 'Kelola Tahun Ajaran')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card h-fit">
        <h3 class="font-bold text-slate-800 mb-4 text-base">+ Tambah Tahun Ajaran</h3>
        <form method="POST" action="{{ route('admin.academic-years.store') }}" class="space-y-4">
            @csrf
            <div><label class="label">Tahun Ajaran</label><input type="text" name="year" placeholder="2025/2026" required class="input"></div>
            <div><label class="label">Semester</label>
                <select name="semester" class="select" required>
                    <option value="GANJIL">GANJIL</option>
                    <option value="GENAP">GENAP</option>
                </select>
            </div>
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" value="1" id="is_act" class="rounded border-slate-300 text-blue-600">
                <label for="is_act" class="text-sm font-medium text-slate-700">Set Sebagai Aktif</label>
            </div>
            <button type="submit" class="w-full btn-primary">Simpan</button>
        </form>
    </div>
    <div class="lg:col-span-2 card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">Tahun</th><th class="table-head">Semester</th><th class="table-head text-center">Status</th><th class="table-head text-right">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($years as $year)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell font-semibold text-slate-800">{{ $year->year }}</td>
                    <td class="table-cell font-medium">{{ $year->semester }}</td>
                    <td class="table-cell text-center"><span class="badge {{ $year->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $year->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="table-cell text-right space-x-2">
                        @if(!$year->is_active)
                        <form method="POST" action="{{ route('admin.academic-years.activate', $year) }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-success">Aktifkan</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" class="inline" onsubmit="return confirm('Hapus?')">
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