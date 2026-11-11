@extends('layouts.app', [
  'title' => 'Form F4: Buku Penghubung Dosen Pembimbing - SIM-PKL TIK',
  'pageTitle' => 'Form F4: Bimbingan Dosen',
  'breadcrumbParent' => 'Portal Mahasiswa TIK',
  'activePage' => 'f4'
])

@section('content')
<!-- Sub-header Breadcrumb & Header Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Mahasiswa</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Kurikulum PKL TIK</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Form F4 (Buku Penghubung Dosen Pembimbing)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F4: Buku Bimbingan Dosen Pembimbing
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Rekam konsultasi berkala, arahan akademik dosen pembimbing, dan pemenuhan syarat minimal 8 sesi bimbingan.
    </p>
  </div>

  @if($pengajuan)
    <a href="{{ route('dokumen.f4', $pengajuan->id) }}" target="_blank" class="h-10 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs sm:text-sm transition-colors flex items-center gap-2 shadow-sm self-start md:self-auto">
      <span class="material-symbols-outlined text-[18px]">file_download</span>
      <span>Unduh PDF F4</span>
    </a>
  @endif
</div>

<!-- Dosen Info & Guidance Progress Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
  <!-- Dosen Card -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
      {{ strtoupper(substr($pengajuan?->dosen?->name ?? 'D', 0, 2)) }}
    </div>
    <div class="min-w-0">
      <span class="text-[10px] font-bold text-[#02796B] uppercase tracking-wider block">Dosen Pembimbing PKL</span>
      <h3 class="text-sm font-bold text-slate-800 truncate">{{ $pengajuan?->dosen?->name ?? 'Belum Ditetapkan' }}</h3>
      <span class="text-xs text-slate-500 font-mono">NIP: {{ $pengajuan?->dosen?->nip ?? '-' }}</span>
    </div>
  </div>

  <!-- Sesi Terlaksana -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Sesi Terlaksana</span>
      <div class="flex items-baseline gap-1 mt-1">
        <span class="text-2xl font-extrabold text-[#0056B3] font-heading">{{ $totalSessions }}</span>
        <span class="text-xs text-slate-500">/ Min. 8 Sesi</span>
      </div>
      @if($totalSessions >= 8)
        <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[14px]">check_circle</span> Memenuhi kuota minimal
        </span>
      @else
        <span class="text-[11px] text-amber-700 font-medium">Kurang {{ 8 - $totalSessions }} sesi untuk syarat sidang &amp; Form F7</span>
      @endif
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">co_present</span>
    </div>
  </div>

  <!-- Status Kelayakan -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Kelayakan Form F7</span>
      @if($totalSessions >= 8)
        <span class="text-sm font-bold text-emerald-600 block mt-1">Layak Sidang &amp; Evaluasi</span>
        <span class="text-[11px] text-slate-500">Dosen dapat mengeksekusi kalkulasi Fuzzy</span>
      @else
        <span class="text-sm font-bold text-amber-700 block mt-1">Menunggu {{ 8 - $totalSessions }} Sesi Lagi</span>
        <span class="text-[11px] text-slate-500">Minimal 8 sesi wajib dipenuhi</span>
      @endif
    </div>
    <div class="w-10 h-10 rounded-xl {{ $totalSessions >= 8 ? 'bg-teal-50 text-[#02796B]' : 'bg-amber-50 text-amber-700' }} flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">{{ $totalSessions >= 8 ? 'verified' : 'hourglass_top' }}</span>
    </div>
  </div>
</div>

<!-- Log Sesi Bimbingan Dosen -->
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card space-y-5">
  <div class="flex items-center justify-between pb-4 border-b border-slate-100">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Riwayat Catatan Bimbingan Akademik</h2>
      <p class="text-xs text-slate-500">Semua sesi asistensi dan arahan revisi yang disahkan oleh dosen pembimbing.</p>
    </div>
    <span class="text-xs font-semibold px-3 py-1 bg-teal-50 text-[#02796B] rounded-lg">
      {{ $totalSessions }} Sesi Tercatat
    </span>
  </div>

  @if($bimbingans->isEmpty())
    <div class="text-center py-12 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
      <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">history_edu</span>
      <h4 class="text-sm font-bold text-slate-700">Belum Ada Sesi Bimbingan yang Dicatat</h4>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Lakukan konsultasi berkala dengan Dosen Pembimbing Anda. Dosen akan menginput histori bimbingan pada sistem.</p>
    </div>
  @else
    <div class="space-y-4">
      @foreach($bimbingans as $sesi)
        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 flex flex-col md:flex-row gap-4 items-start">
          <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">
            {{ $sesi->sesi_ke }}
          </div>
          <div class="flex-1 space-y-1.5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
              <h4 class="font-bold text-slate-800 text-sm sm:text-base font-heading">{{ $sesi->materi_bimbingan }}</h4>
              <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($sesi->tanggal_bimbingan)->translatedFormat('d M Y') }}</span>
            </div>
            @if($sesi->progres_laporan)
              <p class="text-xs text-slate-600 bg-blue-50/50 p-2.5 rounded-lg border border-blue-100">
                <strong class="text-slate-700">Progres Laporan:</strong> {{ $sesi->progres_laporan }}
              </p>
            @endif
            @if($sesi->catatan_dosen)
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/70">
                <strong>Arahan Dosen:</strong> {{ $sesi->catatan_dosen }}
              </p>
            @endif
            <div class="flex items-center gap-2 pt-1">
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
                <span class="material-symbols-outlined text-[13px]">verified</span> Terverifikasi Dosen
              </span>
              <span class="text-xs font-mono text-slate-400">NIP: {{ $pengajuan?->dosen?->nip ?? '-' }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
