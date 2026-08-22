@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Kelola Siswa & Akun Portal')
@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, modalStudent: null, modalName: '' }">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card h-fit">
            <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-4 text-base">+ Tambah Siswa & Akun</h3>
            <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-3.5">
                @csrf
                <div><label class="label">NIS</label><input type="text" name="nis" placeholder="2425006" required class="input"></div>
                <div><label class="label">NISN</label><input type="text" name="nisn" placeholder="0091234006" required class="input"></div>
                <div><label class="label">Nama Lengkap Siswa</label><input type="text" name="name" placeholder="Nama Lengkap" required class="input"></div>
                <div><label class="label">Jenis Kelamin</label>
                    <select name="gender" class="select" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div><label class="label">Kelas</label>
                    <select name="class_id" class="select" required>
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->academicYear->year ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Password Akun Login Siswa</label>
                    <input type="text" name="password" placeholder="Default: siswa123" class="input">
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Siswa login menggunakan NIS / Email dan password ini.</p>
                </div>
                <button type="submit" class="w-full btn-primary">Tambah Siswa & Akun</button>
            </form>
        </div>

        <div class="lg:col-span-2 card overflow-hidden p-0">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Daftar Siswa Terdaftar</h3>
                <span class="badge bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">{{ $students->count() }} Siswa</span>
            </div>
            <div class="overflow-x-auto"><table class="w-full">
                <thead><tr><th class="table-head">NIS / NISN</th><th class="table-head">Nama Siswa</th><th class="table-head">Kelas</th><th class="table-head">Akun Portal</th><th class="table-head text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($students as $s)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="table-cell font-mono text-xs text-slate-600 dark:text-slate-300"><b>{{ $s->nis }}</b><br><span class="text-slate-400 dark:text-slate-500">{{ $s->nisn }}</span></td>
                        <td class="table-cell font-semibold text-slate-800 dark:text-slate-200">{{ $s->name }} ({{ $s->gender }})</td>
                        <td class="table-cell"><span class="badge bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">{{ $s->schoolClass->name ?? '-' }}</span></td>
                        <td class="table-cell text-xs">
                            @if($s->userAccount)
                                <span class="badge bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">Aktif</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $s->userAccount->email }}</span>
                            @else
                                <span class="badge bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300">Belum Ada Akun</span>
                            @endif
                        </td>
                        <td class="table-cell text-right space-x-1.5 whitespace-nowrap">
                            <a href="{{ route('admin.students.report', $s) }}" target="_blank" class="btn-secondary !py-1 !px-2.5 !text-xs text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 font-semibold">Preview Raport</a>
                            <button @click="modalOpen = true; modalStudent = '{{ $s->id }}'; modalName = '{{ $s->name }}'" class="btn-info">Set Pass</button>
                            <form method="POST" action="{{ route('admin.students.destroy', $s) }}" class="inline" onsubmit="return confirm('Hapus siswa dan akunnya?')">
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

    {{-- Reset Password Modal --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" style="display:none">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl" @click.outside="modalOpen=false">
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-1">Set Password Akun Siswa</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4" x-text="'Siswa: ' + modalName"></p>
            <form :action="'/admin/students/' + modalStudent + '/password'" method="POST" class="space-y-4">
                @csrf
                <div><label class="label">Password Baru</label><input type="text" name="password" required class="input" placeholder="Minimal 6 karakter"></div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="modalOpen=false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection