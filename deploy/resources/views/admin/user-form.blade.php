@extends('layouts.app')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('page-title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('content')
<div class="max-w-2xl mx-auto card">
    <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-4">
        @csrf
        @if(isset($user)) @method('PUT') @endif
        <div><label class="label">Nama Lengkap</label><input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="input"></div>
        <div><label class="label">Email</label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="input"></div>
        <div><label class="label">NIP (Opsional)</label><input type="text" name="nip" value="{{ old('nip', $user->nip ?? '') }}" class="input"></div>
        <div><label class="label">Role</label>
            <select name="role" class="select" required>
                <option value="teacher" {{ old('role', $user->role ?? '') == 'teacher' ? 'selected' : '' }}>Guru Mapel</option>
                <option value="homeroom" {{ old('role', $user->role ?? '') == 'homeroom' ? 'selected' : '' }}>Wali Kelas</option>
                <option value="principal" {{ old('role', $user->role ?? '') == 'principal' ? 'selected' : '' }}>Kepala Sekolah</option>
                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>
        <div><label class="label">Password {{ isset($user) ? '(Kosongkan jika tidak diubah)' : '' }}</label><input type="password" name="password" {{ isset($user) ? '' : 'required' }} class="input"></div>
        <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-700 text-blue-600">
            <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300">Akun Aktif</label>
        </div>
        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('admin.users') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">{{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}</button>
        </div>
    </form>
</div>
@endsection