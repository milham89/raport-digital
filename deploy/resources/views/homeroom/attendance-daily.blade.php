@extends('layouts.app')
@section('title', 'Absensi Harian')
@section('page-title', 'Absensi Harian Siswa')

@section('content')
<div class="space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">Senin - Jum'at</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $activeYear ? $activeYear->year : '' }} ({{ $activeYear ? $activeYear->semester : '' }})</span>
            </div>
            <h2 class="font-bold text-slate-800 dark:text-slate-100 text-lg mt-1">Presensi Harian: Kelas {{ $class ? $class->name : '' }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pilih tanggal untuk mencatat absensi harian siswa (Hadir, Sakit, Izin, Alpa).</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('homeroom.attendance.monthly', ['month' => $selectedDate->format('Y-m')]) }}" class="btn-secondary !text-xs font-semibold">Rekap Bulanan</a>
            <a href="{{ route('homeroom.remarks') }}" class="btn-secondary !text-xs font-semibold">Rekap Semester & Rapor &rarr;</a>
        </div>
    </div>

    <!-- Date Navigation Header -->
    <div class="card p-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <a href="{{ route('homeroom.attendance.daily', ['date' => $prevDate->format('Y-m-d')]) }}" class="btn-secondary !text-xs font-bold flex items-center gap-1.5">
                &larr; {{ $prevDate->format('D, d M') }}
            </a>
            <form method="GET" action="{{ route('homeroom.attendance.daily') }}" class="flex items-center gap-2">
                <label for="date_input" class="text-xs text-slate-600 dark:text-slate-300 font-bold">Tanggal:</label>
                <input type="date" id="date_input" name="date" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()"
                       class="input !py-1.5 text-sm font-semibold !w-auto">
                <button type="submit" class="btn-primary !py-1.5 !px-4 text-xs font-bold shadow-sm">Buka</button>
            </form>
            <a href="{{ route('homeroom.attendance.daily', ['date' => $nextDate->format('Y-m-d')]) }}" class="btn-secondary !text-xs font-bold flex items-center gap-1.5">
                {{ $nextDate->format('D, d M') }} &rarr;
            </a>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-2 font-medium">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-slate-600 dark:text-slate-400">Hari terpilih: <strong class="text-slate-900 dark:text-slate-100 uppercase font-bold">{{ $selectedDate->format('l, d F Y') }}</strong></span>
            </div>
            @if($isWeekend)
                <span class="text-amber-800 dark:text-amber-300 bg-amber-100 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-800/60 px-2.5 py-1 rounded-lg text-xs font-semibold">Perhatian: Hari Sabtu/Minggu di luar hari aktif belajar standar</span>
            @endif
        </div>
    </div>
    <!-- Attendance Form -->
    <div class="card overflow-hidden p-0">
        <form method="POST" action="{{ route('homeroom.attendance.daily.store') }}" id="attendance-form">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">

            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <span>Setel Cepat:</span>
                    <button type="button" onclick="setAllStatus('H')" class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-950/60 hover:bg-emerald-200 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 rounded-lg transition border border-emerald-300 dark:border-emerald-800/60">Semua Hadir (H)</button>
                    <button type="button" onclick="setAllStatus('S')" class="px-2.5 py-1 bg-blue-100 dark:bg-blue-950/60 hover:bg-blue-200 dark:hover:bg-blue-900/60 text-blue-800 dark:text-blue-300 rounded-lg transition border border-blue-300 dark:border-blue-800/60">Semua Sakit (S)</button>
                    <button type="button" onclick="setAllStatus('I')" class="px-2.5 py-1 bg-amber-100 dark:bg-amber-950/60 hover:bg-amber-200 dark:hover:bg-amber-900/60 text-amber-800 dark:text-amber-300 rounded-lg transition border border-amber-300 dark:border-amber-800/60">Semua Izin (I)</button>
                    <button type="button" onclick="setAllStatus('A')" class="px-2.5 py-1 bg-rose-100 dark:bg-rose-950/60 hover:bg-rose-200 dark:hover:bg-rose-900/60 text-rose-800 dark:text-rose-300 rounded-lg transition border border-rose-300 dark:border-rose-800/60">Semua Alpa (A)</button>
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Siswa: <strong class="text-slate-800 dark:text-slate-100">{{ $students->count() }}</strong></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead>
                        <tr>
                            <th class="table-head w-12 text-center">No</th>
                            <th class="table-head min-w-[200px]">Siswa</th>
                            <th class="table-head text-center w-72">Status Kehadiran</th>
                            <th class="table-head min-w-[200px]">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($students as $index => $s)
                        @php
                            $att = $attendances[$s->id] ?? null;
                            $curStatus = $att && isset($att->status) ? $att->status : 'H';
                            $curNote = $att && isset($att->note) ? $att->note : '';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="table-cell text-center font-mono text-xs text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                            <td class="table-cell font-semibold text-slate-800 dark:text-slate-200 py-3">
                                {{ $s->name }}<br><span class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $s->nis }}</span>
                            </td>
                            <td class="table-cell text-center py-3">
                                <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800 p-1 gap-1 border border-slate-300 dark:border-slate-700 shadow-inner">
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="H" class="sr-only peer status-radio" {{ $curStatus == 'H' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 dark:text-slate-300 transition peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow hover:bg-slate-200 dark:hover:bg-slate-700">H</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="S" class="sr-only peer status-radio" {{ $curStatus == 'S' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 dark:text-slate-300 transition peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow hover:bg-slate-200 dark:hover:bg-slate-700">S</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="I" class="sr-only peer status-radio" {{ $curStatus == 'I' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 dark:text-slate-300 transition peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow hover:bg-slate-200 dark:hover:bg-slate-700">I</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="A" class="sr-only peer status-radio" {{ $curStatus == 'A' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 dark:text-slate-300 transition peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow hover:bg-slate-200 dark:hover:bg-slate-700">A</span>
                                    </label>
                                </div>
                            </td>
                            <td class="table-cell py-3">
                                <input type="text" name="attendances[{{ $s->id }}][note]" value="{{ $curNote }}" placeholder="Catatan (opsional)..." class="input !py-1.5 text-xs">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-500 dark:text-slate-400"><span class="font-semibold text-slate-700 dark:text-slate-200">Keterangan:</span> H = Hadir, S = Sakit, I = Izin, A = Alpa.</p>
                <button type="submit" class="btn-primary !px-6 py-2.5 font-bold shadow-md shadow-blue-500/30">Simpan Presensi Harian</button>
            </div>
        </form>
    </div>
</div>

<script>
function setAllStatus(status) {
    document.querySelectorAll(`input.status-radio[value="${status}"]`).forEach(r => r.checked = true);
}
</script>
@endsection

