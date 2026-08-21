<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Raport - {{ $student->name }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .print-sheet { border: none !important; box-shadow: none !important; padding: 0 !important; max-width: 100% !important; }
  }
</style>
</head>
<body class="bg-slate-100 font-sans p-4 sm:p-8">
<div class="no-print max-w-4xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
    <h2 class="font-bold text-slate-800">Cetak Lembar Raport Siswa</h2>
    <button onclick="window.print()" class="btn-primary px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow">Cetak Dokumen</button>
</div>

<div class="print-sheet max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-slate-200 text-slate-800">
    <div class="text-center border-b-2 border-slate-900 pb-3 mb-5">
        <h1 class="text-lg font-black uppercase tracking-wider">LAPORAN HASIL CAPAIAN KOMPETENSI PESERTA DIDIK</h1>
        <p class="text-sm font-semibold">SMA NEGERI INDONESIA</p>
        <p class="text-xs text-slate-500">Tahun Ajaran {{ $activeYear?->year }} Semester {{ $activeYear?->semester }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4 text-xs mb-5">
        <div>
            <p>Nama Siswa : <b>{{ $student->name }}</b></p>
            <p class="mt-1">NIS / NISN : {{ $student->nis }} / {{ $student->nisn }}</p>
        </div>
        <div>
            <p>Kelas / Semester : {{ $class->name ?? '-' }} / {{ $activeYear?->semester }}</p>
            <p class="mt-1">Wali Kelas : {{ $class->homeroomTeacher->name ?? '-' }}</p>
        </div>
    </div>

    <table class="w-full text-xs border-collapse border border-slate-900 mb-5">
        <thead>
            <tr class="bg-slate-100">
                <th class="border border-slate-900 px-2 py-1.5 w-8 text-center">No</th>
                <th class="border border-slate-900 px-2 py-1.5 text-left">Mata Pelajaran</th>
                <th class="border border-slate-900 px-2 py-1.5 text-center w-12">KKM</th>
                <th class="border border-slate-900 px-2 py-1.5 text-center w-16">Nilai Akhir</th>
                <th class="border border-slate-900 px-2 py-1.5 text-center w-14">Predikat</th>
                <th class="border border-slate-900 px-2 py-1.5 text-center w-20">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $i => $grade)
            @php $kkm = $grade->subject->kkm ?? 75; $passed = $grade->final_score >= $kkm; @endphp
            <tr>
                <td class="border border-slate-900 px-2 py-1.5 text-center">{{ $i + 1 }}</td>
                <td class="border border-slate-900 px-2 py-1.5 font-semibold">{{ $grade->subject->name ?? '-' }}</td>
                <td class="border border-slate-900 px-2 py-1.5 text-center">{{ $kkm }}</td>
                <td class="border border-slate-900 px-2 py-1.5 text-center font-bold">{{ $grade->final_score ?? '-' }}</td>
                <td class="border border-slate-900 px-2 py-1.5 text-center font-semibold">{{ $grade->letter_grade ?? '-' }}</td>
                <td class="border border-slate-900 px-2 py-1.5 text-center text-[11px]">{{ $grade->final_score !== null ? ($passed ? 'Tuntas' : 'Belum') : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="border border-slate-900 px-2 py-3 text-center text-slate-400">Belum ada data nilai.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="grid grid-cols-2 gap-4 text-xs mb-6">
        <div class="border border-slate-900 p-3 rounded">
            <p class="font-bold border-b border-slate-200 pb-1 mb-1.5">KETIDAKHADIRAN</p>
            <p>Sakit : {{ $remark?->sick ?? 0 }} hari</p>
            <p>Izin : {{ $remark?->permission ?? 0 }} hari</p>
            <p>Tanpa Keterangan : {{ $remark?->unexcused ?? 0 }} hari</p>
        </div>
        <div class="border border-slate-900 p-3 rounded">
            <p class="font-bold border-b border-slate-200 pb-1 mb-1.5">CATATAN WALI KELAS</p>
            <p class="italic text-slate-600">{{ $remark?->homeroom_note ?? 'Tingkatkan terus semangat belajar dan prestasimu.' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-2 text-center text-xs mt-8">
        <div><p>Orang Tua / Wali,</p><div class="h-14"></div><p class="font-bold">( .................... )</p></div>
        <div><p>Kepala Sekolah,</p><div class="h-14"></div><p class="font-bold">Drs. Budi Santoso, M.Pd.</p></div>
        <div><p>Wali Kelas,</p><div class="h-14"></div><p class="font-bold">{{ $class->homeroomTeacher->name ?? 'Dewi Lestari, S.Pd.' }}</p></div>
    </div>
</div>
</body>
</html>