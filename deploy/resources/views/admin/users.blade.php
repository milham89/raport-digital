@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Pengguna')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola akun staf, guru, wali kelas, dan kepsek.</p>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Tambah User</a>
    </div>
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto"><table class="w-full">
            <thead><tr><th class="table-head">Nama</th><th class="table-head">Email</th><th class="table-head">NIP</th><th class="table-head">Role</th><th class="table-head text-center">Status</th><th class="table-head text-right">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <td class="table-cell font-semibold text-slate-800 dark:text-slate-200">{{ $user->name }}</td>
                    <td class="table-cell text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                    <td class="table-cell font-mono text-xs">{{ $user->nip ?? '-' }}</td>
                    <td class="table-cell"><span class="badge bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 uppercase">{{ $user->role }}</span></td>
                    <td class="table-cell text-center"><span class="badge {{ $user->is_active ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="table-cell text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-info">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
</div>
@endsection