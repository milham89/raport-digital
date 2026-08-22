@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Kelola Mata Pelajaran')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card h-fit">
        <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-4 text-base">+ Tambah Mapel</h3>
        <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-4">
            @csrf
            <div><label class="label">Kode Mapel</label><input type="text" name="code" placeholder="MTK" required class="input uppercase"></div>
            <div><label class="label">Nama Mapel</label><input type="text" name="name" placeholder="Matematika" required class="input"></div>
            <div><label class="label">KKM</label><input type="number" name="kkm" placeholder="75" min="0" max="100" required class="input"></div>
            <button type="submit" class="w-full btn-primary">Simpan Mapel</button>
        </form>
    </div>
    <div class="lg:col-span-2 card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">Kode</th><th class="table-head">Nama Mata Pelajaran</th><th class="table-head text-center">KKM</th><th class="table-head text-right">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($subjects as $subject)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <td class="table-cell font-mono font-bold text-blue-600 dark:text-blue-400">{{ $subject->code }}</td>
                    <td class="table-cell font-medium text-slate-800 dark:text-slate-200">{{ $subject->name }}</td>
                    <td class="table-cell text-center font-semibold text-slate-700 dark:text-slate-300">{{ $subject->kkm }}</td>
                    <td class="table-cell text-right">
                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="inline" onsubmit="return confirm('Hapus mapel?')">
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