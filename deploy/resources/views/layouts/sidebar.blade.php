<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 flex flex-col transition-transform duration-300 ease-in-out"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0 shadow-lg shadow-blue-500/30">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/></svg>
        </div>
        <div>
            <p class="text-white font-bold text-sm tracking-wide leading-tight">RAPORT DIGITAL</p>
            <p class="text-slate-400 text-xs font-medium">SMA NEGERI</p>
        </div>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @if(auth()->user()->isAdmin())
            <p class="sidebar-section">Menu Admin</p>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">Manajemen User</a>
            <a href="{{ route('admin.academic-years') }}" class="nav-link {{ request()->routeIs('admin.academic-years*') ? 'active' : '' }}">Tahun Ajaran</a>
            <a href="{{ route('admin.classes') }}" class="nav-link {{ request()->routeIs('admin.classes*') ? 'active' : '' }}">Kelas</a>
            <a href="{{ route('admin.subjects') }}" class="nav-link {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}">Mata Pelajaran</a>
            <a href="{{ route('admin.assignments') }}" class="nav-link {{ request()->routeIs('admin.assignments*') ? 'active' : '' }}">Penugasan Guru</a>
            <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">Data Siswa & Akun</a>
            <a href="{{ route('admin.school-settings') }}" class="nav-link {{ request()->routeIs('admin.school-settings*') ? 'active' : '' }}">Format & Sekolah</a>
        @elseif(auth()->user()->isTeacher())
            <p class="sidebar-section">Menu Guru</p>
            <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.*') ? 'active' : '' }}">Input Nilai</a>
        @elseif(auth()->user()->isHomeroom())
            <p class="sidebar-section">Menu Wali Kelas</p>
            <a href="{{ route('homeroom.dashboard') }}" class="nav-link {{ request()->routeIs('homeroom.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('homeroom.remarks') }}" class="nav-link {{ request()->routeIs('homeroom.remarks*') ? 'active' : '' }}">Catatan & Absensi</a>
        @elseif(auth()->user()->isPrincipal())
            <p class="sidebar-section">Menu Kepala Sekolah</p>
            <a href="{{ route('principal.dashboard') }}" class="nav-link {{ request()->routeIs('principal.*') ? 'active' : '' }}">Rekap Nilai</a>
        @elseif(auth()->user()->isStudent())
            <p class="sidebar-section">Portal Siswa</p>
            <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard Nilai</a>
            <a href="{{ route('student.report-card') }}" class="nav-link {{ request()->routeIs('student.report-card') ? 'active' : '' }}">Lihat Raport Saya</a>
        @endif
    </nav>
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs">{{ $roleLabels[auth()->user()->role] ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition text-sm">
                Keluar
            </button>
        </form>
    </div>
</aside>
