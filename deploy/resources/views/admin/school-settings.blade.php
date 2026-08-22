@extends('layouts.app')
@section('title', 'Format & Profil Sekolah')
@section('page-title', 'Pengaturan Format Cetak Raport & Sekolah')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="card">
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Identitas Sekolah & Kop Raport</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Informasi ini akan tercetak pada kop atas, isi lembar, serta tanda tangan raport.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.school-settings.preview') }}" target="_blank" class="btn-secondary !py-1.5 !px-3 text-xs flex items-center gap-1.5 font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 border-blue-200 dark:border-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview Format Raport
                </a>
                <span class="badge bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">Tampilan Cetak</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.school-settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Pengaturan Logo Sekolah -->
            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/60">
                <label class="label mb-2 block font-semibold text-slate-800 dark:text-slate-200">Logo Resmi Sekolah</label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-20 h-20 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center bg-white dark:bg-slate-900 overflow-hidden shrink-0 shadow-sm">
                        @if(!empty($setting->school_logo))
                            <img src="{{ asset($setting->school_logo) }}" alt="Logo Sekolah" class="w-full h-full object-contain p-1">
                        @else
                            <div class="text-center p-2 text-slate-400 dark:text-slate-500">
                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] block leading-tight mt-0.5">Tanpa Logo</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-2">
                        <input type="file" name="school_logo" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-950/80 dark:file:text-blue-300 cursor-pointer">
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Format: PNG, JPG, JPEG, WEBP, atau SVG (Maks. 2MB). Disarankan berlatar transparan.</p>
                        @if(!empty($setting->school_logo))
                            <label class="inline-flex items-center gap-1.5 text-xs text-red-600 dark:text-red-400 hover:underline cursor-pointer mt-1">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                Hapus logo saat disimpan
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="label">Judul Utama Dokumen (Header)</label>
                    <input type="text" name="header_title" value="{{ old('header_title', $setting->header_title) }}" required class="input" placeholder="LAPORAN HASIL CAPAIAN KOMPETENSI PESERTA DIDIK">
                </div>

                <div>
                    <label class="label">Nama Resmi Sekolah</label>
                    <input type="text" name="school_name" value="{{ old('school_name', $setting->school_name) }}" required class="input" placeholder="SMA NEGERI INDONESIA">
                </div>

                <div>
                    <label class="label">Jenjang / Sub-Title</label>
                    <input type="text" name="school_level" value="{{ old('school_level', $setting->school_level) }}" class="input" placeholder="SMA NEGERI">
                </div>

                <div class="md:col-span-2">
                    <label class="label">Alamat Lengkap Sekolah</label>
                    <input type="text" name="school_address" value="{{ old('school_address', $setting->school_address) }}" class="input" placeholder="Jl. Pendidikan No. 123, Jakarta">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-3">Tanda Tangan & Titimangsa Raport</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nama Kepala Sekolah</label>
                        <input type="text" name="principal_name" value="{{ old('principal_name', $setting->principal_name) }}" required class="input" placeholder="Drs. Budi Santoso, M.Pd.">
                    </div>

                    <div>
                        <label class="label">NIP Kepala Sekolah</label>
                        <input type="text" name="principal_nip" value="{{ old('principal_nip', $setting->principal_nip) }}" class="input" placeholder="196505151990011001">
                    </div>

                    <div>
                        <label class="label">Tempat / Kota Penetapan</label>
                        <input type="text" name="report_place" value="{{ old('report_place', $setting->report_place) }}" required class="input" placeholder="Jakarta">
                    </div>

                    <div>
                        <label class="label">Tanggal Cetak Raport (Contoh: 21 Agustus 2026)</label>
                        <input type="text" name="report_date" value="{{ old('report_date', $setting->report_date) }}" class="input" placeholder="21 Agustus 2026">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary px-6">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
