@extends('layouts.app')
@section('title', 'Rekap Absensi Bulanan')
@section('page-title', 'Rekap Absensi Bulanan Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge bg-emerald-100 text-emerald-700">Senin - Jum'at (5 Hari Belajar)</span>
                <span class="text-xs text-slate-400 font-mono">{{ $activeYear?->year }} ({{ $activeYear?->semester }})</span>
            </div>
            <h2 class="font-bold text-slate-800 text-lg mt-1">Rekap Presensi Bulanan: Kelas {{ $class->name ?? '' }}</h2>
            <p class="text-xs text-slate-500">Tampilan kalender absensi siswa bulan {{ $selectedMonth->format('F Y') }}.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('homeroom.attendance.daily') }}" class="btn-primary !text-xs font-semibold">
                + Input Presensi Harian
            </a>
            <form method="POST" action="{{ route('homeroom.attendance.sync') }}" onsubmit="return confirm('Sinkronkan seluruh rekap absensi semester ini berdasarkan absensi harian yang telah diinput?')">
                @csrf
                <button type="submit" class="btn-secondary !text-xs font-semibold text-blue-600">
                    &#x21bb; Sinkronkan ke Rapor
                </button>
            </form>
            <a href="{{ route('homeroom.remarks') }}" class="btn-secondary !text-xs font-semibold">
                Catatan & Rapor &rarr;
            </a>
        </div>
    </div>

    <!-- Month Picker -->
    <div class="card bg-slate-900 text-white border-0 shadow-md p-4">
        <form method="GET" action="{{ route('homeroom.attendance.monthly') }}" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <label for="month_input" class="text-xs text-slate-300 font-medium">Pilih Bulan & Tahun:</label>
                <input type="month" id="month_input" name="month" value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()"
                       class="bg-white/10 border border-white/20 rounded-xl px-3 py-1.5 text-sm text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400">
    <!-- Attendance Matrix Table -->
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="table-head !text-[11px] !py-2.5 w-8 text-center sticky left-0 bg-slate-100 z-10">No</th>
                        <th class="table-head !text-[11px] !py-2.5 min-w-[170px] sticky left-8 bg-slate-100 z-10">Nama Siswa</th>
                        @foreach($weekdays as $w)
                        <th class="table-head !text-[10px] !py-1 px-1 text-center font-mono border-l border-slate-200 {{ in_array($w->dayOfWeek, [1]) ? 'border-l-2 border-slate-300' : '' }}" title="{{ $w->format('l, d F Y') }}">
                            <span class="block text-slate-400 font-normal">{{ substr($w->format('D'), 0, 2) }}</span>
                            <a href="{{ route('homeroom.attendance.daily', ['date' => $w->format('Y-m-d')]) }}" class="hover:text-blue-600 hover:underline">
                                {{ $w->format('d') }}
                            </a>
                        </th>
                        @endforeach
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-emerald-50 text-emerald-800 border-l border-slate-200" title="Hadir">H</th>
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-blue-50 text-blue-800" title="Sakit">S</th>
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-amber-50 text-amber-800" title="Izin">I</th>
                        <th class="table-head !text-[11px] !py-2 px-1 text-center bg-rose-50 text-rose-800" title="Alpa">A</th>
                        <th class="table-head !text-[11px] !py-2 px-2 text-center bg-slate-200 text-slate-800" title="Persentase Kehadiran">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $idx => $s)
                    @php $sum = $summaries[$s->id] ?? ['h'=>0,'s'=>0,'i'=>0,'a'=>0,'total'=>0,'rate'=>0]; @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="table-cell text-center font-mono text-[11px] text-slate-400 sticky left-0 bg-white z-10">{{ $idx + 1 }}</td>
                        <td class="table-cell font-semibold text-slate-800 py-2 sticky left-8 bg-white z-10 whitespace-nowrap">
                            <span class="block truncate max-w-[160px]">{{ $s->name }}</span>
                        </td>
                        @foreach($weekdays as $w)
                        @php
                            $dStr = $w->format('Y-m-d');
                            $st = $matrix[$s->id][$dStr] ?? null;
                            $bgCell = '';
                            $textCell = '-';
                            if ($st === 'H') { $bgCell = 'bg-emerald-100 text-emerald-800 font-bold'; $textCell = 'H'; }
                            elseif ($st === 'S') { $bgCell = 'bg-blue-100 text-blue-800 font-bold'; $textCell = 'S'; }
                            elseif ($st === 'I') { $bgCell = 'bg-amber-100 text-amber-800 font-bold'; $textCell = 'I'; }
                            elseif ($st === 'A') { $bgCell = 'bg-rose-100 text-rose-800 font-bold'; $textCell = 'A'; }
                            else { $bgCell = 'text-slate-300'; }
                        @endphp
                        <td class="table-cell text-center !p-1 border-l border-slate-100 {{ in_array($w->dayOfWeek, [1]) ? 'border-l-2 border-slate-200' : '' }}">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded text-[10px] {{ $bgCell }}">{{ $textCell }}</span>
                        </td>
                        @endforeach
                        <td class="table-cell text-center font-bold bg-emerald-50/70 text-emerald-800 border-l border-slate-200 px-1">{{ $sum['h'] }}</td>
                        <td class="table-cell text-center font-bold bg-blue-50/70 text-blue-800 px-1">{{ $sum['s'] }}</td>
                        <td class="table-cell text-center font-bold bg-amber-50/70 text-amber-800 px-1">{{ $sum['i'] }}</td>
                        <td class="table-cell text-center font-bold bg-rose-50/70 text-rose-800 px-1">{{ $sum['a'] }}</td>
                        <td class="table-cell text-center font-bold text-slate-800 bg-slate-50 px-2">{{ $sum['rate'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-600">
            <div class="flex items-center gap-4">
                <span class="font-semibold text-slate-800">Keterangan:</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500 inline-block"></span> H = Hadir</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-500 inline-block"></span> S = Sakit</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500 inline-block"></span> I = Izin</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-rose-500 inline-block"></span> A = Alpa</span>
            </div>
            <p class="text-slate-400">Klik tanggal pada judul kolom di atas untuk mengedit absensi harian pada tanggal terkait.</p>
        </div>
    </div>
</div>
@endsection

                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">Tampilkan</button>
            </div>
            <div class="text-xs text-slate-300">
                Bulan: <strong class="text-white uppercase">{{ $selectedMonth->format('F Y') }}</strong> &bull; Total Hari Efektif: <strong class="text-white">{{ count($weekdays) }} Hari</strong>
            </div>
        </form>
    </div>

