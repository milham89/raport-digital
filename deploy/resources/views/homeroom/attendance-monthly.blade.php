@extends('layouts.app')
@section('title', 'Rekap Absensi Bulanan')
@section('page-title', 'Rekap Absensi Bulanan Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">Senin - Jum'at (5 Hari Belajar)</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $activeYear ? $activeYear->year : '' }} ({{ $activeYear ? $activeYear->semester : '' }})</span>
            </div>
            <h2 class="font-bold text-slate-800 dark:text-slate-100 text-lg mt-1">Rekap Presensi Bulanan: Kelas {{ $class ? $class->name : '' }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Tampilan kalender absensi siswa bulan {{ $selectedMonth->format('F Y') }}.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('homeroom.attendance.daily') }}" class="btn-primary !text-xs font-semibold">
                + Input Presensi Harian
            </a>
            <form method="POST" action="{{ route('homeroom.attendance.sync') }}" onsubmit="return confirm('Sinkronkan seluruh rekap absensi semester ini berdasarkan absensi harian yang telah diinput?')">
                @csrf
                <button type="submit" class="btn-secondary !text-xs font-semibold text-blue-600 dark:text-blue-400">
                    &#x21bb; Sinkronkan ke Rapor
                </button>
            </form>
            <a href="{{ route('homeroom.remarks') }}" class="btn-secondary !text-xs font-semibold">
                Catatan & Rapor &rarr;
            </a>
        </div>
    </div>

    <!-- Month Picker -->
    <div class="card p-4">
        <form method="GET" action="{{ route('homeroom.attendance.monthly') }}" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <label for="month_input" class="text-xs text-slate-600 dark:text-slate-300 font-bold">Pilih Bulan & Tahun:</label>
                <input type="month" id="month_input" name="month" value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()"
                       class="input !py-1.5 text-sm font-semibold !w-auto">
                <button type="submit" class="btn-primary !py-1.5 !px-4 text-xs font-bold shadow-sm">Tampilkan</button>
            </div>
            <div class="text-xs text-slate-600 dark:text-slate-300">
                Bulan: <strong class="text-slate-900 dark:text-slate-100 uppercase font-bold">{{ $selectedMonth->format('F Y') }}</strong> &bull; Total Hari Efektif: <strong class="text-blue-600 dark:text-blue-400 font-bold">{{ count($weekdays) }} Hari</strong>
            </div>
        </form>
    </div>

    <!-- Attendance Matrix Table -->
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800">
                        <th class="table-head !text-[11px] !py-2.5 w-8 text-center sticky left-0 bg-slate-100 dark:bg-slate-800 z-10">No</th>
                        <th class="table-head !text-[11px] !py-2.5 min-w-[170px] sticky left-8 bg-slate-100 dark:bg-slate-800 z-10">Nama Siswa</th>
                        @foreach($weekdays as $w)
                        <th class="table-head !text-[10px] !py-1 px-1 text-center font-mono border-l border-slate-200 dark:border-slate-800 {{ in_array($w->dayOfWeek, [1]) ? 'border-l-2 border-slate-300 dark:border-slate-700' : '' }}" title="{{ $w->format('l, d F Y') }}">
                            <span class="block text-slate-500 dark:text-slate-400 font-normal">{{ substr($w->format('D'), 0, 2) }}</span>
                            <a href="{{ route('homeroom.attendance.daily', ['date' => $w->format('Y-m-d')]) }}" class="hover:text-blue-600 dark:hover:text-blue-400 hover:underline">
                                {{ $w->format('d') }}
                            </a>
                        </th>
                        @endforeach
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-l border-slate-200 dark:border-slate-800" title="Hadir">H</th>
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-blue-50 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300" title="Sakit">S</th>
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300" title="Izin">I</th>
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300" title="Alpa">A</th>
                        <th class="table-head !text-[11px] !py-2 px-2 text-center bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200" title="Persentase Kehadiran">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($students as $idx => $s)
                    @php $sum = $summaries[$s->id] ?? ['h'=>0,'s'=>0,'i'=>0,'a'=>0,'total'=>0,'rate'=>0]; @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="table-cell text-center font-mono text-[11px] text-slate-500 dark:text-slate-400 sticky left-0 bg-white dark:bg-slate-900 z-10">{{ $idx + 1 }}</td>
                        <td class="table-cell font-semibold text-slate-800 dark:text-slate-200 py-2 sticky left-8 bg-white dark:bg-slate-900 z-10 whitespace-nowrap border-r border-slate-200 dark:border-slate-800">
                            <span class="block truncate max-w-[160px]">{{ $s->name }}</span>
                        </td>
                        @foreach($weekdays as $w)
                        @php
                            $dStr = $w->format('Y-m-d');
                            $st = $matrix[$s->id][$dStr] ?? null;
                            $bgCell = '';
                            $textCell = '-';
                            if ($st === 'H') { $bgCell = 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold'; $textCell = 'H'; }
                            elseif ($st === 'S') { $bgCell = 'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 font-bold'; $textCell = 'S'; }
                            elseif ($st === 'I') { $bgCell = 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold'; $textCell = 'I'; }
                            elseif ($st === 'A') { $bgCell = 'bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 font-bold'; $textCell = 'A'; }
                            else { $bgCell = 'text-slate-300 dark:text-slate-600'; }
                        @endphp
                        <td class="table-cell text-center !p-1 border-l border-slate-200 dark:border-slate-800 {{ in_array($w->dayOfWeek, [1]) ? 'border-l-2 border-slate-300 dark:border-slate-700' : '' }}">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded text-[10px] {{ $bgCell }}">{{ $textCell }}</span>
                        </td>
                        @endforeach
                        <td class="table-cell text-center font-bold bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border-l border-slate-200 dark:border-slate-800 px-1">{{ $sum['h'] }}</td>
                        <td class="table-cell text-center font-bold bg-blue-50/70 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 px-1">{{ $sum['s'] }}</td>
                        <td class="table-cell text-center font-bold bg-amber-50/70 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 px-1">{{ $sum['i'] }}</td>
                        <td class="table-cell text-center font-bold bg-rose-50/70 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 px-1">{{ $sum['a'] }}</td>
                        <td class="table-cell text-center font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/60 px-2 border-l border-slate-200 dark:border-slate-800">{{ $sum['rate'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-600 dark:text-slate-400">
            <div class="flex items-center gap-4">
                <span class="font-semibold text-slate-800 dark:text-slate-200">Keterangan:</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500 inline-block"></span> H = Hadir</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-500 inline-block"></span> S = Sakit</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500 inline-block"></span> I = Izin</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-rose-500 inline-block"></span> A = Alpa</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400">Klik tanggal pada judul kolom di atas untuk mengedit absensi harian pada tanggal terkait.</p>
        </div>
    </div>
</div>
@endsection

