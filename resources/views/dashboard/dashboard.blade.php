@extends('layouts.app', [
  'title' => 'Dashboard Mahasiswa - SIM-PKL JTIK PNJ',
  'pageTitle' => 'Dashboard & Penempatan PKL',
  'breadcrumbParent' => 'Portal Mahasiswa',
  'activePage' => 'dashboard'
])

@section('content')
@php
  $activeF1 = $f1Application ?? null;
  $statusVerifikasiInd = $pengajuanAktif?->industri?->status_verifikasi;
  $statusSurat = $pengajuanAktif?->status_surat_pengantar;
  $canSubmitPengajuan = !$pengajuanAktif
    || in_array($statusSurat, ['draft', 'ditolak'], true)
    || $statusVerifikasiInd === 'reject';

  $hasAssignedDosen = !empty(Auth::user()->dosen_id) && $dosen != null;
  $isF1Approved = ($activeF1 && $activeF1->status === 'approved') || $hasAssignedDosen;
  $isF1Pending = ($activeF1 && $activeF1->status === 'pending') && !$hasAssignedDosen;

  $dospem = $dosen ?? ($activeF1?->dosen ?? null);
  $dospemName = $dospem?->name ?? 'Belum Ditetapkan';
  $dospemNip = $dospem?->identifier ?? '-';
  $words = explode(' ', trim($dospemName));
  $initials = '';
  foreach ($words as $w) {
    $clean = preg_replace('/[^a-zA-Z]/', '', $w);
    if (strlen($clean) > 0 && !in_array(strtolower($clean), ['dr', 'eng', 'kom', 'mt', 'mkom', 'drs', 'h', 'st', 'si', 'ti'])) {
      $initials .= strtoupper($clean[0]);
      if (strlen($initials) >= 2) break;
    }
  }
  if (!$initials) $initials = 'DP';
@endphp

<!-- Profil Mahasiswa -->
<div class="rounded-2xl bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white p-5 sm:p-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-white shrink-0">
      <span class="material-symbols-outlined text-[32px]">account_circle</span>
    </div>
    <div>
      <div class="flex items-center gap-2 flex-wrap">
        <h1 class="text-lg sm:text-xl font-extrabold font-heading text-white">
          {{ Auth::user()->name ?? 'Mahasiswa' }}
        </h1>
        <span class="px-2 py-0.5 rounded bg-white/15 text-blue-50 text-xs font-mono">
          {{ Auth::user()->identifier ?? '-' }}
        </span>
      </div>
      <p class="text-blue-100 text-xs mt-1">
        {{ Auth::user()->prodi ?? 'D4 Teknik Informatika' }} • Jurusan Teknik Informatika &amp; Komputer PNJ
      </p>
    </div>
  </div>

  <div class="flex items-center gap-2.5 self-start lg:self-center">
    <div class="bg-white/10 border border-white/15 px-3.5 py-2 rounded-xl flex items-center gap-2.5">
      <span class="material-symbols-outlined text-emerald-300 text-[20px]">verified</span>
      <div>
        <span class="text-[10px] text-blue-200 uppercase font-semibold block">Status PKL</span>
        <span class="text-xs font-bold text-white">Mahasiswa Aktif</span>
      </div>
    </div>
  </div>
</div>

<!-- Hasil Nilai Akhir Fuzzy Logic -->
@if($pengajuanAktif && $pengajuanAktif->nilai_akhir_fuzzy)
<div class="rounded-2xl bg-slate-900 text-white p-5 shadow-sm border border-teal-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-teal-500/20 text-teal-300 border border-teal-400/30 flex items-center justify-center shrink-0">
      <span class="material-symbols-outlined text-[26px]">workspace_premium</span>
    </div>
    <div>
      <span class="text-[10px] font-bold uppercase tracking-wider text-teal-300">Nilai Akhir PKL (F5 60% + F6 40%)</span>
      <h3 class="text-lg sm:text-xl font-black font-heading text-white mt-0.5">
        Skor: <span class="text-teal-300 font-mono">{{ $pengajuanAktif->nilai_akhir_fuzzy }}</span>
        <span class="text-xs font-semibold text-emerald-400 ml-1">({{ $pengajuanAktif->fuzzy_percentage }}% — Lulus)</span>
      </h3>
    </div>
  </div>

  <a href="{{ route('dokumen.f7', $pengajuanAktif->id) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs flex items-center gap-1.5 transition-all shrink-0 self-start md:self-center">
    <span class="material-symbols-outlined text-[17px]">download</span>
    <span>Unduh Form F7</span>
  </a>
</div>
@endif

<!-- Grid Utama: Kiri (Penempatan & Surat) + Kanan (Form F1 & Bimbingan) -->
<div class="grid grid-cols-1 xl:grid-cols-12 gap-5">
  
  <!-- Kolom Kiri -->
  <div class="xl:col-span-7 space-y-5">
    
    <!-- Card Penempatan Industri (DUDI) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div class="flex items-start gap-3.5">
          <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] border border-teal-100 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[24px]">domain</span>
          </div>
          <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Tempat Pelaksanaan PKL</span>
            <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900 mt-0.5">
              {{ $pengajuanAktif?->industri?->nama_perusahaan ?? $pengajuanAktif?->nama_perusahaan ?? 'Belum Mengajukan Perusahaan' }}
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
              {{ $pengajuanAktif?->industri?->bidang_usaha ?? $pengajuanAktif?->alamat_perusahaan ?? 'Ajukan tempat PKL untuk diverifikasi Admin Jurusan.' }}
            </p>
            @if($pengajuanAktif?->industri?->alamat)
              <p class="text-[11px] text-slate-400 mt-0.5">
                {{ $pengajuanAktif->industri->alamat }}{{ $pengajuanAktif->industri->kota ? ', ' . $pengajuanAktif->industri->kota : '' }}
              </p>
            @endif
          </div>
        </div>

        @if($canSubmitPengajuan)
          <button type="button" onclick="document.getElementById('modal-request-surat').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs flex items-center gap-1.5 transition-all shrink-0 self-start">
            <span class="material-symbols-outlined text-[16px]">add_business</span>
            <span>{{ $pengajuanAktif ? 'Ajukan Ulang' : 'Ajukan Perusahaan' }}</span>
          </button>
        @else
          <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 font-bold text-xs flex items-center gap-1.5 shrink-0 self-start">
            <span class="material-symbols-outlined text-[15px] text-amber-600">lock</span>
            <span>{{ $statusVerifikasiInd === 'pending' || $statusSurat === 'pending' ? 'Menunggu Verifikasi' : 'Terkunci' }}</span>
          </span>
        @endif
      </div>

      <!-- Status Verifikasi -->
      @if($pengajuanAktif)
        @if($statusVerifikasiInd === 'reject' || $statusSurat === 'ditolak' || $statusSurat === 'draft')
          <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 space-y-2">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600 shrink-0"></span>
                <p class="text-xs font-bold text-rose-900">
                  @if($statusVerifikasiInd === 'reject')
                    Pengajuan Perusahaan Ditolak Admin
                  @elseif($statusSurat === 'draft')
                    Pengajuan Dikembalikan ke Draft — Silakan Ajukan Ulang
                  @else
                    Permohonan Surat Pengantar Ditolak Admin
                  @endif
                </p>
              </div>
              <button type="button" onclick="document.getElementById('modal-request-surat').classList.remove('hidden')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold self-start sm:self-auto">
                <span class="material-symbols-outlined text-[14px]">refresh</span>
                <span>Ajukan Ulang</span>
              </button>
            </div>
            @if($pengajuanAktif->catatan_penolakan || $pengajuanAktif->industri?->catatan_admin)
              <div class="text-xs text-rose-800 bg-white/80 rounded-lg p-2.5 border border-rose-200/60">
                <strong>Catatan Admin:</strong> {{ $pengajuanAktif->catatan_penolakan ?: $pengajuanAktif->industri?->catatan_admin }}
              </div>
            @endif
          </div>
        @elseif($statusVerifikasiInd === 'pending')
          <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-center gap-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
              <div>
                <p class="text-xs font-bold text-amber-900">Menunggu Verifikasi Perusahaan oleh Admin</p>
                <p class="text-[11px] text-amber-700">Data perusahaan sedang diperiksa oleh Admin Jurusan.</p>
              </div>
            </div>
            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-lg text-[11px] font-bold shrink-0 self-start sm:self-auto">Pending Admin</span>
          </div>
        @else
          <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-center gap-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shrink-0"></span>
              <div>
                <p class="text-xs font-bold text-emerald-900">Mitra Industri Terverifikasi</p>
                @if($pengajuanAktif->industri?->email_pj)
                  <p class="text-[11px] text-emerald-700 font-mono">PIC: {{ $pengajuanAktif->industri->email_pj }}</p>
                @endif
              </div>
            </div>
            <span class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-[11px] font-bold shrink-0 self-start sm:self-auto">Terverifikasi</span>
          </div>
        @endif
      @endif

      <!-- Info Periode & PIC -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
          <span class="text-[10px] font-bold uppercase text-slate-400 block">Periode PKL</span>
          <p class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5">
            @if($pengajuanAktif?->tanggal_mulai && $pengajuanAktif?->tanggal_selesai)
              {{ $pengajuanAktif->tanggal_mulai->format('d M Y') }} – {{ $pengajuanAktif->tanggal_selesai->format('d M Y') }}
            @else
              Belum Ditetapkan
            @endif
          </p>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
          <span class="text-[10px] font-bold uppercase text-slate-400 block">PIC Industri / Posisi</span>
          <p class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5">
            {{ $pengajuanAktif?->industri?->kontak_person ?? $pengajuanAktif?->mentor_industri ?? 'Belum Ditetapkan' }}
          </p>
          <span class="text-[11px] text-slate-500 block">
            {{ $pengajuanAktif?->posisi_magang ?? '-' }}
          </span>
        </div>
      </div>

      @if($pengajuanAktif)
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100">
          <a href="{{ route('dokumen.f2', $pengajuanAktif->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors">
            <span class="material-symbols-outlined text-[15px]">print</span>
            <span>Cetak Form F2</span>
          </a>
          <a href="{{ route('dokumen.f3', $pengajuanAktif->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors">
            <span class="material-symbols-outlined text-[15px]">print</span>
            <span>Cetak Form F3</span>
          </a>
        </div>
      @endif
    </div>

    <!-- Card Surat Pengantar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
      <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[22px]">description</span>
          </div>
          <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Dokumen Pengajuan</span>
            <h4 class="text-sm sm:text-base font-bold font-heading text-slate-800">Surat Pengantar PKL</h4>
          </div>
        </div>

        @if($pengajuanAktif?->status_surat_pengantar === 'diterbitkan')
          <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-lg">Diterbitkan</span>
        @elseif($pengajuanAktif?->status_surat_pengantar === 'pending')
          <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-lg">Pending Admin</span>
        @elseif($pengajuanAktif?->status_surat_pengantar === 'ditolak')
          <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-lg">Ditolak</span>
        @else
          <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg">Belum Diajukan</span>
        @endif
      </div>

      <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <span class="text-[10px] text-slate-400 uppercase font-bold block">Nomor Surat</span>
          <span class="text-xs font-bold text-[#0056B3] font-mono">
            {{ $pengajuanAktif?->nomor_surat_pengantar ?? 'Belum Diterbitkan' }}
          </span>
        </div>

        @if($pengajuanAktif && $pengajuanAktif->status_surat_pengantar === 'diterbitkan')
          <a href="{{ route('dokumen.surat-pengantar', $pengajuanAktif->id) }}" target="_blank" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-[16px]">download</span>
            <span>Unduh Surat (PDF)</span>
          </a>
        @endif
      </div>
    </div>

  </div>

  <!-- Kolom Kanan -->
  <div class="xl:col-span-5 space-y-5">
    
    <!-- Card Form F1: Dosen Pembimbing -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
      <div class="flex items-center justify-between gap-3">
        <div>
          <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Pembimbing Akademik</span>
          <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">Form F1: Dosen Pembimbing</h3>
        </div>
        <div class="w-9 h-9 rounded-xl bg-teal-50 text-[#009688] flex items-center justify-center shrink-0">
          <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
        </div>
      </div>

      @if($isF1Approved)
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-[10px] text-slate-400 font-bold uppercase">Dosen Ditetapkan</span>
            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">Disahkan KPS</span>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-xs shrink-0">
              {{ $initials }}
            </div>
            <div class="min-w-0">
              <span class="text-xs sm:text-sm font-bold text-slate-800 block truncate">{{ $dospemName }}</span>
              <span class="text-[11px] text-slate-500 font-mono">NIP: {{ $dospemNip }}</span>
            </div>
          </div>
        </div>

        @if($activeF1)
          <a href="{{ route('f1.surat.download', $activeF1->id) }}" target="_blank" class="w-full bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center justify-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-[17px]">picture_as_pdf</span>
            <span>Unduh Form F1 (PDF)</span>
          </a>
        @endif

      @elseif($isF1Pending)
        <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-[10px] text-amber-800 font-bold uppercase">Pengajuan Form F1</span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
              <span>Menunggu Penetapan KPS</span>
            </span>
          </div>

          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
              {{ $initials }}
            </div>
            <div class="min-w-0">
              <span class="text-xs sm:text-sm font-bold text-slate-800 block truncate">{{ $activeF1->dosen->name ?? $dospemName }}</span>
              <span class="text-[11px] text-slate-500 font-mono">NIP: {{ $activeF1->dosen->identifier ?? $dospemNip }}</span>
            </div>
          </div>

          <div class="p-2.5 bg-white rounded-lg border border-amber-200/80 text-xs text-slate-600 space-y-0.5">
            <p><strong class="text-slate-800">Instansi:</strong> {{ $activeF1->company_name }}</p>
            <p><strong class="text-slate-800">Topik:</strong> {{ $activeF1->internship_title }}</p>
          </div>

          <form action="{{ route('f1.cancel', $activeF1->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan dosen ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 font-bold text-xs py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 transition-colors">
              <span class="material-symbols-outlined text-[15px]">cancel</span>
              <span>Batalkan / Ganti Dosen</span>
            </button>
          </form>
        </div>

      @else
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center space-y-3">
          <p class="text-xs text-slate-500">Anda belum memilih preferensi dosen pembimbing PKL.</p>
          <button type="button" onclick="openSelectDosenModal()" class="w-full bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center justify-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-[17px]">how_to_reg</span>
            <span>Pilih Dosen Pembimbing (F1)</span>
          </button>
        </div>
      @endif
    </div>

    <!-- Riwayat Bimbingan Terakhir -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-3.5">
      <div class="flex items-center justify-between">
        <h4 class="text-sm sm:text-base font-bold font-heading text-slate-800">Riwayat Bimbingan (F4)</h4>
        <a href="{{ route('form.f4.mahasiswa') }}" class="text-xs font-bold text-[#0056B3] hover:underline">Lihat Semua</a>
      </div>

      @php
        $upcomingBimbingans = $pengajuanAktif?->bimbingans ? $pengajuanAktif->bimbingans->take(3) : collect();
      @endphp

      <div class="space-y-2">
        @forelse($upcomingBimbingans as $sesiBimb)
          <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-9 h-9 rounded-lg bg-[#0056B3] text-white flex flex-col items-center justify-center font-mono font-bold leading-none shrink-0">
                <span class="text-[8px] uppercase">{{ \Carbon\Carbon::parse($sesiBimb->tanggal_bimbingan)->translatedFormat('M') }}</span>
                <span class="text-xs">{{ \Carbon\Carbon::parse($sesiBimb->tanggal_bimbingan)->format('d') }}</span>
              </div>
              <div class="min-w-0">
                <h5 class="text-xs font-bold text-slate-800 truncate">Sesi {{ $sesiBimb->sesi_ke }}: {{ $sesiBimb->materi_bimbingan }}</h5>
                <p class="text-[11px] text-slate-500 truncate">{{ $sesiBimb->catatan_dosen ?: 'Terverifikasi Dosen' }}</p>
              </div>
            </div>
            <span class="material-symbols-outlined text-emerald-500 text-[18px] shrink-0">check_circle</span>
          </div>
        @empty
          <div class="p-5 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
            Belum ada catatan bimbingan.
          </div>
        @endforelse
      </div>
    </div>

  </div>
</div>

<!-- Logbook Harian Mahasiswa -->
<div id="logbook-section" class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
    <div>
      <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Form F3</span>
      <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Logbook Kegiatan Harian</h3>
    </div>
    <button type="button" onclick="openCreateLogbookModal()" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all self-start sm:self-auto">
      <span class="material-symbols-outlined text-[17px]">add</span>
      <span>Tulis Logbook</span>
    </button>
  </div>

  <div class="space-y-3">
    @forelse($logbooks ?? [] as $lb)
      <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200 space-y-2.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h4 class="text-sm font-bold text-slate-800">{{ $lb->subject }}</h4>
            <div class="flex flex-wrap items-center gap-2 mt-1">
              <span class="text-xs text-slate-500 font-mono">{{ $lb->tanggal->translatedFormat('d M Y') }}</span>
              @if($lb->hari_terlambat > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Terlambat {{ $lb->hari_terlambat }} Hari</span>
              @endif
              @if($lb->status_industri === 'acc')
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-[#02796B] border border-teal-200">ACC DUDI</span>
              @elseif($lb->status_industri === 'revisi')
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Revisi DUDI</span>
              @endif
            </div>
          </div>

          @if($lb->status === 'approved')
            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold self-start sm:self-auto">Disetujui Dosen</span>
          @else
            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold self-start sm:self-auto">Menunggu Validasi</span>
          @endif
        </div>

        <p class="p-3 bg-white rounded-lg border border-slate-200/80 text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $lb->content }}</p>

        @if($lb->feedback)
          <div class="p-2.5 bg-blue-50/80 border border-blue-200/80 rounded-lg text-xs text-[#0056B3]">
            <strong>Catatan Dosen:</strong> <span class="text-slate-700">{{ $lb->feedback }}</span>
          </div>
        @endif
      </div>
    @empty
      <div class="p-6 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
        Belum ada catatan logbook harian.
      </div>
    @endforelse
  </div>
</div>

<!-- MODAL: Tulis Logbook -->
<div id="modal-create-logbook" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
  <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-scale-up">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <h3 class="text-base font-bold font-heading text-slate-800">Tulis Logbook Harian (Form F3)</h3>
      <button onclick="closeCreateLogbookModal()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form action="{{ route('logbooks.store') }}" method="POST" class="space-y-3.5">
      @csrf
      <div>
        <label for="logbook-subject" class="block text-xs font-bold text-slate-700 mb-1">Topik Kegiatan <span class="text-rose-500">*</span></label>
        <input type="text" id="logbook-subject" name="subject" required placeholder="Contoh: Implementasi API Authentication" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-[#0056B3]">
      </div>

      <div>
        <label for="logbook-tanggal" class="block text-xs font-bold text-slate-700 mb-1">Tanggal <span class="text-rose-500">*</span></label>
        <input type="date" id="logbook-tanggal" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-[#0056B3]">
      </div>

      <div>
        <label for="logbook-content" class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas <span class="text-rose-500">*</span></label>
        <textarea id="logbook-content" name="content" rows="5" required placeholder="Tuliskan rincian pekerjaan dan hasil yang dicapai..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-[#0056B3]"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeCreateLogbookModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2 rounded-xl text-xs font-bold">Simpan Logbook</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Pilih Dosen Pembimbing (Form F1) -->
<div id="modal-select-dosen" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
  <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-scale-up max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Pilih Dosen Pembimbing (Form F1)</h3>
        <p class="text-xs text-slate-500">Pengajuan akan ditetapkan oleh KPS.</p>
      </div>
      <button type="button" onclick="closeSelectDosenModal()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form action="{{ route('f1.select-dosen') }}" method="POST" class="space-y-4 overflow-y-auto pr-1 flex-1">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label for="f1-company-name" class="block text-xs font-bold text-slate-700 mb-1">Perusahaan PKL <span class="text-rose-500">*</span></label>
          <input type="text" id="f1-company-name" name="company_name" required value="{{ $pengajuanAktif?->industri?->nama_perusahaan ?? '' }}" placeholder="Nama Perusahaan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0056B3]">
        </div>
        <div>
          <label for="f1-internship-title" class="block text-xs font-bold text-slate-700 mb-1">Topik / Judul PKL <span class="text-rose-500">*</span></label>
          <input type="text" id="f1-internship-title" name="internship_title" required placeholder="Topik PKL" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0056B3]">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label class="text-xs font-bold text-slate-700">Pilih Dosen <span class="text-rose-500">*</span></label>
          <span class="text-[11px] text-slate-400">{{ count($allDosen ?? []) }} Dosen Tersedia</span>
        </div>

        <input type="text" id="dosen-search-box" onkeyup="filterDosenListModal()" placeholder="Cari nama atau NIP dosen..." class="w-full px-3 py-2 mb-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0056B3]">

        <div class="space-y-2 max-h-56 overflow-y-auto pr-1" id="dosen-options-container">
          @forelse($allDosen ?? [] as $d)
            <label class="dosen-option-item flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-[#0056B3] cursor-pointer transition-all has-[:checked]:border-[#0056B3] has-[:checked]:bg-blue-50/50" data-search="{{ strtolower($d->name . ' ' . $d->identifier . ' ' . $d->prodi) }}">
              <div class="flex items-center gap-3 min-w-0">
                <input type="radio" name="dosen_id" value="{{ $d->id }}" required {{ $loop->first ? 'checked' : '' }} class="w-4 h-4 text-[#0056B3]">
                <div class="min-w-0">
                  <span class="text-xs font-bold text-slate-800 block truncate">{{ $d->name }}</span>
                  <span class="text-[11px] text-slate-500 font-mono">NIP: {{ $d->identifier ?? '-' }}</span>
                </div>
              </div>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">Tersedia</span>
            </label>
          @empty
            <p class="text-xs text-slate-500 text-center py-4">Tidak ada dosen pembimbing yang tersedia.</p>
          @endforelse
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeSelectDosenModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2 rounded-xl text-xs font-bold">Kirim Pengajuan F1</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Pengajuan Perusahaan & Surat Pengantar -->
<div id="modal-request-surat" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-xl w-full p-6 text-left shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Pengajuan Tempat PKL &amp; Surat Pengantar</h3>
        <p class="text-xs text-slate-500">Pilih mitra terdaftar atau ajukan perusahaan baru.</p>
      </div>
      <button type="button" onclick="document.getElementById('modal-request-surat').classList.add('hidden')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <div class="grid grid-cols-2 gap-1.5 p-1 rounded-xl bg-slate-100 border border-slate-200/80 mb-4">
      <button type="button" id="tab-industri-baru" onclick="switchIndustriMode('baru')" class="py-2 px-3 rounded-lg text-xs font-bold transition-all bg-[#0056B3] text-white shadow-sm">
        Perusahaan Baru
      </button>
      <button type="button" id="tab-industri-terdaftar" onclick="switchIndustriMode('terdaftar')" class="py-2 px-3 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900">
        Mitra Terdaftar ({{ isset($industris) ? $industris->count() : 0 }})
      </button>
    </div>

    <form method="POST" action="{{ route('mahasiswa.request-surat') }}" class="space-y-3.5">
      @csrf

      <div id="section-industri-terdaftar" class="hidden space-y-2 p-3.5 rounded-xl bg-teal-50/50 border border-teal-200">
        <label class="block text-xs font-bold text-slate-700">Pilih Mitra Industri <span class="text-rose-500">*</span></label>
        <select name="industri_id" id="select-industri-id" disabled class="w-full text-xs rounded-xl border border-slate-200 p-2.5 bg-white">
          <option value="">-- Pilih Perusahaan Mitra --</option>
          @foreach($industris ?? [] as $indItem)
            <option value="{{ $indItem->id }}" {{ $pengajuanAktif?->industri_id === $indItem->id ? 'selected' : '' }}>
              {{ $indItem->nama_perusahaan }} ({{ $indItem->kota ?: 'Jakarta' }})
            </option>
          @endforeach
        </select>
      </div>

      <div id="section-industri-baru" class="space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_perusahaan" id="input-nama-perusahaan" required value="{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->nama_perusahaan ?? '') : '' }}" placeholder="PT Contoh Nusantara" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha</label>
            <input type="text" name="bidang_usaha" value="{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->bidang_usaha ?? '') : '' }}" placeholder="Teknologi Informasi" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Kota</label>
            <input type="text" name="kota" value="{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->kota ?? '') : '' }}" placeholder="Jakarta Selatan" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Telepon Perusahaan</label>
            <input type="text" name="telepon" value="{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->telepon ?? '') : '' }}" placeholder="021-xxxxxx" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
          <textarea name="alamat" rows="2" placeholder="Alamat lengkap perusahaan..." class="w-full text-xs rounded-xl border border-slate-200 p-2.5">{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->alamat ?? '') : '' }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama PIC / HRD</label>
            <input type="text" name="kontak_person" value="{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->kontak_person ?? '') : '' }}" placeholder="Nama Kontak Person" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Resmi PIC</label>
            <input type="email" name="email_pj" value="{{ $pengajuanAktif?->industri?->status_verifikasi !== 'acc' ? ($pengajuanAktif?->industri?->email_pj ?? '') : '' }}" placeholder="hrd@perusahaan.co.id" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
        </div>
      </div>

      <div class="pt-3 border-t border-slate-100 space-y-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Posisi / Divisi Magang</label>
          <input type="text" name="posisi_magang" value="{{ $pengajuanAktif?->posisi_magang ?? '' }}" placeholder="Software Engineer Intern" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $pengajuanAktif?->tanggal_mulai?->format('Y-m-d') ?? '' }}" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $pengajuanAktif?->tanggal_selesai?->format('Y-m-d') ?? '' }}" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="document.getElementById('modal-request-surat').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-[#0056B3] hover:bg-blue-700 text-white font-bold text-xs">Kirim Pengajuan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function openCreateLogbookModal() {
    const modal = document.getElementById('modal-create-logbook');
    if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
  }

  function closeCreateLogbookModal() {
    const modal = document.getElementById('modal-create-logbook');
    if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
  }

  function openSelectDosenModal() {
    const modal = document.getElementById('modal-select-dosen');
    if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
  }

  function closeSelectDosenModal() {
    const modal = document.getElementById('modal-select-dosen');
    if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
  }

  function filterDosenListModal() {
    const query = (document.getElementById('dosen-search-box')?.value || '').toLowerCase();
    document.querySelectorAll('.dosen-option-item').forEach(item => {
      const text = item.getAttribute('data-search') || '';
      item.style.display = text.includes(query) ? 'flex' : 'none';
    });
  }

  function switchIndustriMode(mode) {
    const tabBaru = document.getElementById('tab-industri-baru');
    const tabTerdaftar = document.getElementById('tab-industri-terdaftar');
    const secBaru = document.getElementById('section-industri-baru');
    const secTerdaftar = document.getElementById('section-industri-terdaftar');
    const selectIndustri = document.getElementById('select-industri-id');
    const inputNamaPerusahaan = document.getElementById('input-nama-perusahaan');

    if (mode === 'terdaftar') {
      tabTerdaftar.className = 'py-2 px-3 rounded-lg text-xs font-bold transition-all bg-[#0056B3] text-white shadow-sm';
      tabBaru.className = 'py-2 px-3 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900';
      secTerdaftar.classList.remove('hidden');
      secBaru.classList.add('hidden');
      selectIndustri.disabled = false;
      selectIndustri.required = true;
      inputNamaPerusahaan.disabled = true;
      inputNamaPerusahaan.required = false;
    } else {
      tabBaru.className = 'py-2 px-3 rounded-lg text-xs font-bold transition-all bg-[#0056B3] text-white shadow-sm';
      tabTerdaftar.className = 'py-2 px-3 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900';
      secBaru.classList.remove('hidden');
      secTerdaftar.classList.add('hidden');
      selectIndustri.disabled = true;
      selectIndustri.required = false;
      inputNamaPerusahaan.disabled = false;
      inputNamaPerusahaan.required = true;
    }
  }
</script>
@endpush
