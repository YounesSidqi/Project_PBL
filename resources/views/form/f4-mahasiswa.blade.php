@extends('layouts.app', [
  'title' => 'Form F4: Bimbingan Dosen - SIM-PKL',
  'pageTitle' => 'Form F4: Bimbingan Dosen',
  'breadcrumbParent' => 'Mahasiswa',
  'activePage' => 'f4'
])

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="text-xl font-bold font-heading text-slate-900">Form F4: Buku Bimbingan Dosen</h2>
    <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi konsultasi akademik bersama dosen pembimbing (minimal 8 sesi).</p>
  </div>

  @if($pengajuan)
    <a href="{{ route('dokumen.f4', $pengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm self-start sm:self-auto">
      <span class="material-symbols-outlined text-[16px]">file_download</span>
      <span>Unduh PDF F4</span>
    </a>
  @endif
</div>

<!-- Ringkasan Progres Bimbingan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center gap-3.5">
    <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-sm shrink-0">
      {{ strtoupper(substr($pengajuan?->dosen?->name ?? 'D', 0, 2)) }}
    </div>
    <div class="min-w-0">
      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dosen Pembimbing</span>
      <h3 class="text-sm font-bold text-slate-800 truncate">{{ $pengajuan?->dosen?->name ?? 'Belum Ditetapkan' }}</h3>
      <span class="text-xs text-slate-500 font-mono">NIP: {{ $pengajuan?->dosen?->identifier ?? $pengajuan?->dosen?->nip ?? '-' }}</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Sesi Terlaksana</span>
      <div class="flex items-baseline gap-1 mt-0.5">
        <span class="text-2xl font-extrabold text-[#0056B3] font-heading">{{ $totalSessions }}</span>
        <span class="text-xs text-slate-500">/ 8 Sesi</span>
      </div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[20px]">co_present</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Status Evaluasi Akhir</span>
      @if($totalSessions >= 8)
        <span class="text-sm font-bold text-emerald-600 block mt-0.5">Memenuhi Syarat</span>
        <span class="text-[11px] text-slate-500">Siap penilaian akhir (F6 &amp; F7)</span>
      @else
        <span class="text-sm font-bold text-amber-700 block mt-0.5">Kurang {{ 8 - $totalSessions }} Sesi</span>
        <span class="text-[11px] text-slate-500">Wajib minimal 8 sesi bimbingan</span>
      @endif
    </div>
    <div class="w-10 h-10 rounded-xl {{ $totalSessions >= 8 ? 'bg-teal-50 text-[#02796B]' : 'bg-amber-50 text-amber-700' }} flex items-center justify-center">
      <span class="material-symbols-outlined text-[20px]">{{ $totalSessions >= 8 ? 'verified' : 'hourglass_top' }}</span>
    </div>
  </div>
</div>

<!-- Daftar Sesi Bimbingan -->
<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-4">
  <div class="flex items-center justify-between pb-3 border-b border-slate-100">
    <h3 class="text-sm font-bold font-heading text-slate-800">Riwayat Catatan Bimbingan</h3>
    <span class="text-xs font-bold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg">
      {{ $totalSessions }} Sesi
    </span>
  </div>

  @if($bimbingans->isEmpty())
    <div class="text-center py-10 bg-slate-50/60 rounded-xl border border-dashed border-slate-200">
      <span class="material-symbols-outlined text-3xl text-slate-300 mb-1.5 block">history_edu</span>
      <h4 class="text-sm font-bold text-slate-700">Belum Ada Catatan Bimbingan</h4>
      <p class="text-xs text-slate-500 mt-0.5">Catatan bimbingan akan diinput oleh dosen pembimbing setiap selesai sesi konsultasi.</p>
    </div>
  @else
    <div class="space-y-3">
      @foreach($bimbingans as $sesi)
        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200/80 flex gap-3.5 items-start">
          <div class="w-9 h-9 rounded-xl bg-[#0056B3] text-white font-bold text-xs flex items-center justify-center shrink-0">
            #{{ $sesi->sesi_ke }}
          </div>
          <div class="flex-1 min-w-0 space-y-1.5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
              <h4 class="font-bold text-slate-800 text-sm">{{ $sesi->materi_bimbingan }}</h4>
              <span class="text-xs text-slate-400 font-mono shrink-0">{{ \Carbon\Carbon::parse($sesi->tanggal_bimbingan)->translatedFormat('d M Y') }}</span>
            </div>
            @if($sesi->progres_laporan)
              <p class="text-xs text-slate-600 bg-white px-3 py-2 rounded-lg border border-slate-200/60">
                <span class="font-semibold text-slate-700">Progres:</span> {{ $sesi->progres_laporan }}
              </p>
            @endif
            @if($sesi->catatan_dosen)
              <p class="text-xs text-slate-600 bg-white px-3 py-2 rounded-lg border border-slate-200/60">
                <span class="font-semibold text-slate-700">Arahan Dosen:</span> {{ $sesi->catatan_dosen }}
              </p>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
