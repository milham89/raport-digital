<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Raport Digital') - SMA</title>
<script>
    // Runs before any render — prevents flash of wrong theme
    (function() {
        var dark = localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
    })();
</script>
<meta name="color-scheme" content="light dark">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = { darkMode: 'class' };
</script>
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
  .dark .input-score {
    color: #f8fafc !important;
    background-color: #0f172a !important;
    border-color: #334155 !important;
  }
  .input-score:focus {
    border-color: #3b82f6 !important;
    background-color: #eff6ff !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
  }
  .dark .input-score:focus {
    background-color: #1e293b !important;
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
  .dark .input-number {
    color: #f8fafc !important;
    background-color: #0f172a !important;
    border-color: #334155 !important;
  }
  .input-number:focus {
    border-color: #3b82f6 !important;
    background-color: #eff6ff !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
  }
  .dark .input-number:focus {
    background-color: #1e293b !important;
  }

  /* Date & Month Picker Indicator in Dark Mode */
  .dark input[type="date"]::-webkit-calendar-picker-indicator,
  .dark input[type="month"]::-webkit-calendar-picker-indicator,
  .dark input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0.8) brightness(1.2);
    cursor: pointer;
  }

  /* Dark mode select options */
  .dark select option {
    background-color: #0f172a;
    color: #f8fafc;
  }

  /* Smooth Custom Scrollbar for dark/light mode */
  ::-webkit-scrollbar {
    width: 7px;
    height: 7px;
  }
  ::-webkit-scrollbar-track {
    background: #f1f5f9;
  }
  .dark ::-webkit-scrollbar-track {
    background: #090d16;
  }
  ::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }
  .dark ::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
  .dark ::-webkit-scrollbar-thumb:hover {
    background: #475569;
  }
</style>
<style type="text/tailwindcss">
  .nav-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white transition text-sm font-medium; }
  .nav-link.active { @apply bg-blue-600 text-white shadow-md font-semibold; }
  .card { @apply bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6; }
  .btn-primary { @apply inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm font-semibold shadow-sm cursor-pointer; }
  .btn-secondary { @apply inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition text-sm font-medium cursor-pointer border border-slate-200 dark:border-slate-700; }
  .btn-danger { @apply inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/50 transition text-xs font-semibold border border-rose-200 dark:border-rose-800/60 cursor-pointer; }
  .btn-success { @apply inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition text-xs font-semibold border border-emerald-200 dark:border-emerald-800/60 cursor-pointer; }
  .btn-info { @apply inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-xs font-semibold border border-blue-200 dark:border-blue-800/60 cursor-pointer; }

  .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
  .input { @apply w-full border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 placeholder-slate-400 dark:placeholder-slate-500 text-slate-800 dark:text-slate-100; }
  .select { @apply w-full border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100; }
  .label { @apply block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wider; }
  .table-head { @apply bg-slate-50 dark:bg-slate-800/80 text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider px-3.5 py-3 text-left border-b border-slate-200 dark:border-slate-800 whitespace-nowrap; }
  .table-cell { @apply px-3.5 py-2.5 text-sm text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-800; }
  .sidebar-section { @apply px-4 pt-5 pb-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-widest; }
</style>
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased transition-colors duration-200" x-data="{ sidebarOpen: false }">
@auth
@php
    $roleLabels = ['admin'=>'Administrator','teacher'=>'Guru Mapel','homeroom'=>'Wali Kelas','principal'=>'Kepala Sekolah','student'=>'Siswa'];
    $roleColors = ['admin'=>'bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300','teacher'=>'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300','homeroom'=>'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300','principal'=>'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300','student'=>'bg-teal-100 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300'];
@endphp
<div class="flex h-full min-h-screen">
    @include('layouts.sidebar')
    <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-sm lg:hidden" style="display:none"></div>
    <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:ml-64">
        <header class="sticky top-0 z-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur border-b border-slate-200/80 dark:border-slate-800/80 px-4 sm:px-6 py-3.5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="sidebarOpen=true" class="lg:hidden p-2 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex-shrink-0" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-base font-bold text-slate-800 dark:text-slate-100 truncate">@yield('page-title','Dashboard')</h1>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                <!-- Dark Mode Toggle Button -->
                <button type="button" 
                        onclick="(function(){ var d=document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', d?'dark':'light'); })()"
                        id="theme-toggle"
                        class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
                        aria-label="Ganti Tema">
                    <!-- Sun icon: visible in dark mode -->
                    <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon: visible in light mode -->
                    <svg class="w-5 h-5 block dark:hidden text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <span class="hidden sm:inline-flex badge {{ $roleColors[auth()->user()->role] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">{{ $roleLabels[auth()->user()->role] ?? auth()->user()->role }}</span>
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            </div>
        </header>
        <main class="flex-1 p-3 sm:p-5 lg:p-6 w-full max-w-full min-w-0">
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-2xl text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 px-4 py-3 rounded-2xl text-sm"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li class="font-medium">{{ $e }}</li>@endforeach</ul></div>
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