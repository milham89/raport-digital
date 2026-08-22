@extends('layouts.app')
@section('title', 'Catatan & Absensi')
@section('page-title', 'Kelola Catatan & Absensi Siswa')
@section('content')
<div class="space-y-6">
    <div class="card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Catatan & Absensi Kelas {{ $class->name ?? '' }}</h2>
            <p class="text-xs text-slate-500">Tahun Ajaran: {{ $activeYear?->year }} ({{ $activeYear?->semester }}) &bull; Nilai S/I/A akan tertera di cetak raport.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('homeroom.attendance.daily') }}" class="btn-primary !text-xs font-semibold">
                Absensi Harian &rarr;
            </a>
            <a href="{{ route('homeroom.attendance.monthly') }}" class="btn-secondary !text-xs font-semibold">
                Rekap Bulanan
            </a>
            <a href="{{ route('homeroom.dashboard') }}" class="btn-secondary !text-xs">&larr; Dashboard</a>
        </div>
    </div>

    <div class="card overflow-hidden p-0">
        <form method="POST" action="{{ route('homeroom.remarks.store') }}">
            @csrf
            <div class="overflow-x-auto"><table class="w-full min-w-[700px]">
                <thead>
                    <tr>
                        <th class="table-head min-w-[200px]">Siswa</th>
                        <th class="table-head text-center w-24 px-2">Sakit (S)</th>
                        <th class="table-head text-center w-24 px-2">Izin (I)</th>
                        <th class="table-head text-center w-24 px-2">Alpa (A)</th>
                        <th class="table-head min-w-[280px]">Catatan Perkembangan Siswa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $s)
                    @php $rem = $remarks[$s->id] ?? null; @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="table-cell font-semibold text-slate-800 whitespace-nowrap py-3">
                            {{ $s->name }}<br><span class="text-xs text-slate-400 font-mono">{{ $s->nis }}</span>
                        </td>
                        <td class="table-cell text-center px-2 py-3"><input type="number" min="0" name="remarks[{{ $s->id }}][sick]" value="{{ $rem?->sick ?? 0 }}" class="input-number w-16" placeholder="0"></td>
                        <td class="table-cell text-center px-2 py-3"><input type="number" min="0" name="remarks[{{ $s->id }}][permission]" value="{{ $rem?->permission ?? 0 }}" class="input-number w-16" placeholder="0"></td>
                        <td class="table-cell text-center px-2 py-3"><input type="number" min="0" name="remarks[{{ $s->id }}][unexcused]" value="{{ $rem?->unexcused ?? 0 }}" class="input-number w-16" placeholder="0"></td>
                        <td class="table-cell py-3"><input type="text" name="remarks[{{ $s->id }}][homeroom_note]" value="{{ $rem?->homeroom_remark ?? ($rem?->homeroom_note ?? '') }}" placeholder="Masukkan catatan perkembangan..." class="input !py-1.5 text-sm"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table></div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-500">Nilai Sakit, Izin, dan Alpa disinkronkan otomatis dari Absensi Harian atau dapat disesuaikan manual.</p>
                <button type="submit" class="btn-primary !px-6 py-2.5 font-bold shadow-md shadow-blue-500/30">Simpan Catatan & Absensi</button>
            </div>
        </form>
    </div>
</div>
@endsection