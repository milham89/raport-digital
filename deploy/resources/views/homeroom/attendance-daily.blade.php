@extends('layouts.app')
@section('title', 'Absensi Harian')
@section('page-title', 'Absensi Harian Siswa')

@section('content')
<div class="space-y-6">
    <div class="card flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge bg-blue-100 text-blue-700">Senin - Jum'at</span>
                <span class="text-xs text-slate-400 font-mono">{{ $activeYear ? $activeYear->year : '' }} ({{ $activeYear ? $activeYear->semester : '' }})</span>
            </div>
            <h2 class="font-bold text-slate-800 text-lg mt-1">Presensi Harian: Kelas {{ $class ? $class->name : '' }}</h2>
            <p class="text-xs text-slate-500">Pilih tanggal untuk mencatat absensi harian siswa (Hadir, Sakit, Izin, Alpa).</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('homeroom.attendance.monthly', ['month' => $selectedDate->format('Y-m')]) }}" class="btn-secondary !text-xs font-semibold">Rekap Bulanan</a>
            <a href="{{ route('homeroom.remarks') }}" class="btn-secondary !text-xs font-semibold">Rekap Semester & Rapor &rarr;</a>
        </div>
    </div>

    <!-- Date Navigation Header (Clean Light Card) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <a href="{{ route('homeroom.attendance.daily', ['date' => $prevDate->format('Y-m-d')]) }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition flex items-center gap-1.5 border border-slate-200">
                &larr; {{ $prevDate->format('D, d M') }}
            </a>
            <form method="GET" action="{{ route('homeroom.attendance.daily') }}" class="flex items-center gap-2">
                <label for="date_input" class="text-xs text-slate-600 font-bold">Tanggal:</label>
                <input type="date" id="date_input" name="date" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()"
                       class="bg-slate-50 text-slate-800 border border-slate-300 rounded-xl px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">Buka</button>
            </form>
            <a href="{{ route('homeroom.attendance.daily', ['date' => $nextDate->format('Y-m-d')]) }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition flex items-center gap-1.5 border border-slate-200">
                {{ $nextDate->format('D, d M') }} &rarr;
            </a>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-2 font-medium">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-slate-600">Hari terpilih: <strong class="text-slate-900 uppercase font-bold">{{ $selectedDate->format('l, d F Y') }}</strong></span>
            </div>
            @if($isWeekend)
                <span class="text-amber-800 bg-amber-100 border border-amber-300 px-2.5 py-1 rounded-lg text-xs font-semibold">Perhatian: Hari Sabtu/Minggu di luar hari aktif belajar standar</span>
            @endif
        </div>
    </div>
    <!-- Attendance Form -->
    <div class="card overflow-hidden p-0">
        <form method="POST" action="{{ route('homeroom.attendance.daily.store') }}" id="attendance-form">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">

            <div class="p-4 bg-slate-50 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600">
                    <span>Setel Cepat:</span>
                    <button type="button" onclick="setAllStatus('H')" class="px-2.5 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-lg transition">Semua Hadir (H)</button>
                    <button type="button" onclick="setAllStatus('S')" class="px-2.5 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg transition">Semua Sakit (S)</button>
                    <button type="button" onclick="setAllStatus('I')" class="px-2.5 py-1 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg transition">Semua Izin (I)</button>
                    <button type="button" onclick="setAllStatus('A')" class="px-2.5 py-1 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-lg transition">Semua Alpa (A)</button>
                </div>
                <div class="text-xs text-slate-500 font-medium">Total Siswa: <strong class="text-slate-800">{{ $students->count() }}</strong></div>
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
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $index => $s)
                        @php
                            $att = $attendances[$s->id] ?? null;
                            $curStatus = $att && isset($att->status) ? $att->status : 'H';
                            $curNote = $att && isset($att->note) ? $att->note : '';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="table-cell text-center font-mono text-xs text-slate-400">{{ $index + 1 }}</td>
                            <td class="table-cell font-semibold text-slate-800 py-3">
                                {{ $s->name }}<br><span class="text-xs text-slate-400 font-mono">{{ $s->nis }}</span>
                            </td>
                            <td class="table-cell text-center py-3">
                                <div class="inline-flex rounded-xl bg-slate-100 p-1 gap-1 border border-slate-200">
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="H" class="sr-only peer status-radio" {{ $curStatus == 'H' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 transition peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-sm hover:bg-slate-200">H</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="S" class="sr-only peer status-radio" {{ $curStatus == 'S' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 transition peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-sm hover:bg-slate-200">S</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="I" class="sr-only peer status-radio" {{ $curStatus == 'I' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 transition peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-sm hover:bg-slate-200">I</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center justify-center">
                                        <input type="radio" name="attendances[{{ $s->id }}][status]" value="A" class="sr-only peer status-radio" {{ $curStatus == 'A' ? 'checked' : '' }}>
                                        <span class="px-3 py-1 text-xs font-bold rounded-lg text-slate-600 transition peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-sm hover:bg-slate-200">A</span>
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

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-500"><span class="font-semibold text-slate-700">Keterangan:</span> H = Hadir, S = Sakit, I = Izin, A = Alpa.</p>
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

