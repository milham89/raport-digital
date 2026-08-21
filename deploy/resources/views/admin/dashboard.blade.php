@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Administrator')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="card"><p class="text-xs text-slate-500 font-semibold uppercase">Total Pengguna</p><p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalUsers }}</p></div>
        <div class="card"><p class="text-xs text-slate-500 font-semibold uppercase">Total Siswa</p><p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalStudents }}</p></div>
        <div class="card"><p class="text-xs text-slate-500 font-semibold uppercase">Total Kelas</p><p class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalClasses }}</p></div>
        <div class="card"><p class="text-xs text-slate-500 font-semibold uppercase">Tahun Ajaran Aktif</p><p class="text-lg font-bold text-purple-600 mt-1">{{ $activeYear ? $activeYear->year . ' (' . $activeYear->semester . ')' : 'Belum diatur' }}</p></div>
    </div>
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4">Aksi Cepat Sistem</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Tambah Pengguna</a>
            <a href="{{ route('admin.students') }}" class="btn-secondary">Data Siswa & Akun</a>
            <a href="{{ route('admin.classes') }}" class="btn-secondary">Kelola Kelas</a>
            <a href="{{ route('admin.assignments') }}" class="btn-secondary">Penugasan Guru</a>
        </div>
    </div>
</div>
@endsection