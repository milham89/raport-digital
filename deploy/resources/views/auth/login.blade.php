@extends('layouts.app')
@section('title', 'Login - Raport Digital')
@section('content')
<div class="min-h-screen bg-slate-900 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-xl shadow-blue-500/30 mb-3">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/></svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">RAPORT DIGITAL</h2>
        <p class="mt-1 text-sm text-slate-400">Portal Nilai & Rapor Sekolah Mandiri</p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-800/90 py-8 px-6 sm:px-8 shadow-2xl rounded-3xl border border-slate-700/80 backdrop-blur">
            @if($errors->any())
                <div class="mb-4 bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs rounded-xl p-3 font-medium">
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">Email / NIS / NIP</label>
                    <input type="text" name="login" value="{{ old('login') }}" required autofocus
                           class="w-full border border-slate-700 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-900 placeholder-slate-500 text-slate-100" placeholder="Masukkan Email, NIS siswa, atau NIP guru">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" required class="w-full border border-slate-700 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-900 placeholder-slate-500 text-slate-100" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-slate-400 text-xs cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-primary py-2.5 text-sm font-semibold shadow-lg shadow-blue-600/30">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>
</div>
@endsection