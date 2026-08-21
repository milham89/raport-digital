<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Raport Digital') - SMA</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  /* Reset input number to remove spinners and ensure clear visible text */
  input[type=number]::-webkit-inner-spin-button, 
  input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none !important; 
    margin: 0 !important; 
  }
  input[type=number] {
    -moz-appearance: textfield !important;
    appearance: textfield !important;
  }
  .input-score {
    display: inline-block !important;
    width: 72px !important;
    min-width: 64px !important;
    max-width: 80px !important;
    height: 38px !important;
    padding: 2px 6px !important;
    text-align: center !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    background-color: #ffffff !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    outline: none !important;
    box-sizing: border-box !important;
  }
  .input-score:focus {
    border-color: #2563eb !important;
    background-color: #eff6ff !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2) !important;
  }
  .input-number {
    display: inline-block !important;
    width: 64px !important;
    min-width: 56px !important;
    height: 38px !important;
    padding: 2px 6px !important;
    text-align: center !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    background-color: #ffffff !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    outline: none !important;
    box-sizing: border-box !important;
  }
  .input-number:focus {
    border-color: #2563eb !important;
    background-color: #eff6ff !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2) !important;
  }
</style>
<style type="text/tailwindcss">
  .nav-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white transition text-sm font-medium; }
  .nav-link.active { @apply bg-blue-600 text-white shadow-md font-semibold; }
  .card { @apply bg-white rounded-2xl shadow-sm border border-slate-100 p-6; }
  .btn-primary { @apply inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm font-semibold shadow-sm cursor-pointer; }
  .btn-secondary { @apply inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition text-sm font-medium cursor-pointer; }
  .btn-danger { @apply inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition text-xs font-semibold border border-rose-200 cursor-pointer; }
  .btn-success { @apply inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition text-xs font-semibold border border-emerald-200 cursor-pointer; }
  .btn-info { @apply inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-xs font-semibold border border-blue-200 cursor-pointer; }

  .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
  .input { @apply w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white; }
  .select { @apply w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white; }
  .label { @apply block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider; }
  .table-head { @apply bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider px-4 py-3 text-left border-b border-slate-200; }
  .table-cell { @apply px-4 py-3.5 text-sm text-slate-700 border-b border-slate-100; }
  .sidebar-section { @apply px-4 pt-5 pb-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest; }
</style>
</head>
<body class="h-full bg-slate-50 font-sans antialiased" x-data="{ sidebarOpen: false }">
@auth
@php
    $roleLabels = ['admin'=>'Administrator','teacher'=>'Guru Mapel','homeroom'=>'Wali Kelas','principal'=>'Kepala Sekolah','student'=>'Siswa'];
    $roleColors = ['admin'=>'bg-purple-100 text-purple-700','teacher'=>'bg-blue-100 text-blue-700','homeroom'=>'bg-emerald-100 text-emerald-700','principal'=>'bg-amber-100 text-amber-700','student'=>'bg-teal-100 text-teal-700'];
@endphp
<div class="flex h-full min-h-screen">
    @include('layouts.sidebar')
    <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-sm lg:hidden" style="display:none"></div>
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3.5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen=true" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-base font-bold text-slate-800">@yield('page-title','Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex badge {{ $roleColors[auth()->user()->role] ?? 'bg-slate-100 text-slate-700' }}">{{ $roleLabels[auth()->user()->role] ?? auth()->user()->role }}</span>
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            </div>
        </header>
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-sm"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li class="font-medium">{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@else
    @yield('content')
@endauth
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>