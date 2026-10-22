@extends('layouts.app', [
  'title' => 'Verifikasi Logbook & Bimbingan Mahasiswa (F2-F4) - SIM-PKL Dosen',
  'pageTitle' => 'Review Logbook & Bimbingan (F3-F4)',
  'breadcrumbParent' => 'Portal Dosen TIK',
  'activePage' => 'logbook'
])

@section('content')
<!-- Sub-header Breadcrumb & Lecturer Institutional Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Dosen TIK</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Monitoring Mahasiswa Bimbingan</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Verifikasi Form F3 &amp; F4</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Verifikasi Logbook &amp; Bimbingan Mahasiswa
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Pemeriksaan jurnal aktivitas harian industri (Form F3), validasi uraian tugas, dan konsultasi bimbingan akademik (Form F4).
    </p>
  </div>

  <!-- Lecturer Mentorship Capacity Pill -->
  <div class="flex items-center gap-3.5 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm self-start md:self-auto">
    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold flex-shrink-0">
      <span class="material-symbols-outlined text-[24px]">supervisor_account</span>
    </div>
    <div class="flex flex-col pr-2 min-w-0">
      <div class="flex items-center gap-2">
        <span class="text-xs sm:text-sm font-bold text-slate-800 truncate">Dr. Bambang Sutrisno, M.T.</span>
        <span class="w-2 h-2 rounded-full bg-[#009688]"></span>
      </div>
      <span class="text-[11px] text-slate-500 truncate">
        Kapasitas: <strong>{{ isset($students) ? $students->count() : 5 }} / 7 Mahasiswa Bimbingan</strong> (Maks. 7)
      </span>
    </div>
  </div>
</div>

<!-- 1. Top Metrics Bar for Lecturer -->
@php
  $totalMentoredLogbooks = isset($logbooks) ? $logbooks->count() : 0;
  $pendingReviewCount = isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0;
  $approvedCount = isset($logbooks) ? $logbooks->where('status', 'approved')->count() : 0;
  $approvalRate = ($totalMentoredLogbooks > 0) ? round(($approvedCount / $totalMentoredLogbooks) * 100) : 0;
@endphp
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
  
  <!-- Metric Card A: Logbook Masuk Perlu Validasi (Form F3) -->
  <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card relative overflow-hidden flex flex-col justify-between">
    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-50 rounded-full blur-2xl pointer-events-none"></div>
    <div>
      <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2">
          <span class="p-2 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px] material-symbols-fill">pending_actions</span>
          </span>
          <span class="text-sm font-bold text-slate-800 font-heading">Antrean Logbook Masuk (Form F3)</span>
        </div>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $pendingReviewCount > 0 ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }} text-xs font-bold">
          <span class="w-1.5 h-1.5 rounded-full {{ $pendingReviewCount > 0 ? 'bg-amber-500 animate-pulse' : 'bg-[#009688]' }}"></span>
          {{ $pendingReviewCount > 0 ? $pendingReviewCount . ' Menunggu Validasi' : 'Semua Terverifikasi' }}
        </span>
      </div>

      <div class="flex items-baseline gap-2 mt-2 mb-3">
        <span class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">{{ $approvedCount }}</span>
        <span class="text-sm text-slate-500 font-medium">/ {{ $totalMentoredLogbooks }} Logbook Disetujui</span>
        <span class="ml-auto text-xs font-bold text-[#0056B3] font-mono">{{ $approvalRate }}% Validasi</span>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
        <div class="bg-gradient-to-r from-[#0056B3] to-[#02796B] h-2.5 rounded-full transition-all duration-500" style="width: {{ $approvalRate }}%"></div>
      </div>
    </div>

    <div class="flex items-center justify-between pt-4 mt-5 bg-slate-50/70 -mx-6 -mb-6 px-6 py-3 border-t border-slate-100">
      <span class="text-xs text-slate-500 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px] text-[#0056B3]">fact_check</span>
        <span>Validasi berkala menjamin kualitas laporan akhir PKL</span>
      </span>
      <span class="text-xs font-bold text-[#0056B3]">
        {{ $pendingReviewCount > 0 ? 'Perlu Ditinjau' : 'Up-to-Date' }}
      </span>
    </div>
  </div>

  <!-- Metric Card B: Progres Sesi Bimbingan (Form F4) -->
  <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card relative overflow-hidden flex flex-col justify-between">
    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-teal-50 rounded-full blur-2xl pointer-events-none"></div>
    <div>
      <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2">
          <span class="p-2 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">co_present</span>
          </span>
          <span class="text-sm font-bold text-slate-800 font-heading">Syarat Konsultasi Bimbingan (Form F4)</span>
        </div>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
          <span class="w-1.5 h-1.5 rounded-full bg-[#009688]"></span>
          Standar: Min. 4 Kali Bimbingan
        </span>
      </div>

      <div class="flex items-baseline gap-2 mt-2 mb-3">
        <span class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">{{ isset($students) ? $students->count() : 1 }} / {{ isset($students) ? $students->count() : 1 }}</span>
        <span class="text-sm text-slate-500 font-medium">Mahasiswa Terdaftar Bimbingan</span>
        <span class="ml-auto text-xs font-bold text-[#02796B] font-mono">100% Aktif</span>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
        <div class="bg-gradient-to-r from-[#02796B] to-[#009688] h-2.5 rounded-full w-full transition-all duration-500"></div>
      </div>
    </div>

    <div class="flex items-center justify-between pt-4 mt-5 bg-slate-50/70 -mx-6 -mb-6 px-6 py-3 border-t border-slate-100">
      <span class="text-xs text-slate-500 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px] text-[#02796B]">verified</span>
        <span>Pengesahan konsultasi berbobot nilai bimbingan akademik</span>
      </span>
      <button type="button" class="text-xs text-[#0056B3] hover:underline font-bold flex items-center gap-1" onclick="switchTab('tab-f4')">
        <span>Buka Lembar F4</span>
        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
      </button>
    </div>
  </div>

</div>

<!-- 2. Segmented Navigation Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-2">
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2">
    <div class="flex items-center p-1 bg-slate-100/90 rounded-xl flex-wrap gap-1">
      <button type="button" id="nav-btn-f3" onclick="switchTab('tab-f3')" class="px-4 py-2 rounded-lg text-xs sm:text-sm bg-white text-[#0056B3] shadow-sm transition-all flex items-center gap-2 font-bold font-heading">
        <span class="material-symbols-outlined text-[18px] text-[#0056B3]">rate_review</span>
        <span>Form F3: Verifikasi Logbook Harian Mahasiswa</span>
      </button>
      <button type="button" id="nav-btn-f4" onclick="switchTab('tab-f4')" class="px-4 py-2 rounded-lg text-xs sm:text-sm text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2 font-medium">
        <span class="material-symbols-outlined text-[18px]">co_present</span>
        <span>Form F4: Buku Bimbingan Kampus Dosen</span>
      </button>
      <button type="button" id="nav-btn-f2" onclick="switchTab('tab-f2')" class="px-4 py-2 rounded-lg text-xs sm:text-sm text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2 font-medium">
        <span class="material-symbols-outlined text-[18px]">terminal</span>
        <span>Form F2: Pemantauan Capaian Kompetensi DUDI</span>
      </button>
    </div>
    
    <div class="flex items-center gap-2 px-3 py-1">
      <span class="text-xs font-bold text-[#02796B]">Jurusan Teknik Informatika &amp; Komputer</span>
    </div>
  </div>
</div>

<!-- 3. TAB CONTENT F3: VERIFIKASI LOGBOOK HARIAN MAHASISWA BIMBINGAN [ACTIVE] -->
<div class="tab-panel flex flex-col space-y-5" id="tab-content-f3">
  
  <!-- Filter & Action Toolbar -->
  <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
    <div class="flex flex-wrap items-center gap-3">
      
      <!-- Student Filter -->
      <div class="relative">
        <select id="filter-student" onchange="filterDosenLogbooks()" class="h-10 pl-4 pr-9 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none cursor-pointer font-medium">
          <option value="all">Semua Mahasiswa Bimbingan ({{ isset($students) ? $students->count() : 1 }})</option>
          @foreach($students ?? [] as $st)
            <option value="{{ strtolower($st->name) }}">{{ $st->name }} ({{ $st->identifier }})</option>
          @endforeach
        </select>
        <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-slate-400 pointer-events-none text-[18px]">expand_more</span>
      </div>

      <!-- Status Filter -->
      <div class="relative">
        <select id="filter-status" onchange="filterDosenLogbooks()" class="h-10 pl-4 pr-9 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none cursor-pointer font-medium">
          <option value="all">Semua Status Validasi</option>
          <option value="pending">Menunggu Validasi</option>
          <option value="approved">Telah Disetujui</option>
          <option value="revision">Perlu Revisi</option>
        </select>
        <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-slate-400 pointer-events-none text-[18px]">filter_list</span>
      </div>

      <!-- Search Input -->
      <div class="relative flex-1 md:w-64">
        <input id="search-logbook" onkeyup="filterDosenLogbooks()" class="w-full h-10 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20" placeholder="Cari nama, NIM, topik..." type="text">
        <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-slate-400 text-[18px]">search</span>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button type="button" class="h-10 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs sm:text-sm transition-colors flex items-center gap-2 shadow-sm" onclick="showToast('Rekapitulasi logbook seluruh mahasiswa bimbingan diexport ke berkas rekap.')">
        <span class="material-symbols-outlined text-[18px]">print</span>
        <span>Rekap Nilai Logbook</span>
      </button>
    </div>
  </div>

  <!-- Feed Logbook Masuk untuk Ditinjau Dosen -->
  <div class="space-y-4" id="logbooks-feed">
    @forelse($logbooks ?? [] as $lb)
      <div class="dosen-logbook-card p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/90 hover:border-blue-300 transition-all shadow-sm space-y-4"
           data-student="{{ strtolower($lb->student->name ?? 'mahasiswa') }}"
           data-status="{{ $lb->status }}">
        
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-3 border-b border-slate-100">
          <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0 mt-0.5">
              {{ strtoupper(substr($lb->student->name ?? 'M', 0, 2)) }}
            </div>
            <div>
              <div class="flex items-center gap-2 flex-wrap">
                <strong class="text-sm sm:text-base font-bold text-slate-800 font-heading">{{ $lb->student->name ?? 'Mahasiswa Bimbingan' }}</strong>
                <span class="text-xs font-mono px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] font-semibold">NIM: {{ $lb->student->identifier ?? '2107411002' }}</span>
                <span class="text-xs text-slate-500">{{ $lb->student->prodi ?? 'Teknik Informatika (D4)' }}</span>
              </div>
              
              <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading mt-1">
                {{ $lb->subject }}
              </h3>

              <div class="flex items-center gap-3 text-xs text-slate-500 font-mono mt-1">
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                  <span>{{ $lb->tanggal->translatedFormat('l, d F Y') }}</span>
                </span>
                <span>•</span>
                <span class="flex items-center gap-1 text-slate-600">
                  <span class="material-symbols-outlined text-[14px] text-teal-600">domain</span>
                  <span>PT Telkomsel (Cloud Platform)</span>
                </span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 self-start sm:self-auto flex-shrink-0">
            @if($lb->status === 'approved')
              <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                <span class="material-symbols-outlined text-[15px]">check_circle</span>
                <span>Telah Disetujui</span>
              </span>
            @elseif($lb->status === 'revision')
              <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                <span class="material-symbols-outlined text-[15px]">warning</span>
                <span>Perlu Revisi</span>
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                <span class="material-symbols-outlined text-[15px] animate-spin">refresh</span>
                <span>Menunggu Validasi</span>
              </span>
            @endif

            <button type="button" 
                    onclick="openDosenReviewModal({{ $lb->id }}, '{{ addslashes($lb->student->name ?? 'Mahasiswa') }}', '{{ addslashes($lb->student->identifier ?? '') }}', '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}')"
                    class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
              <span class="material-symbols-outlined text-[16px]">rate_review</span>
              <span>Review Logbook</span>
            </button>
          </div>
        </div>

        <!-- Uraian Aktivitas (Long Text) yang Ditulis Mahasiswa -->
        <div>
          <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
            Uraian Aktivitas Mahasiswa (Long Text):
          </label>
          <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-normal">
            {{ $lb->content }}
          </div>
        </div>

        @if($lb->feedback)
          <div class="p-3.5 bg-blue-50/80 border border-blue-200/80 rounded-xl text-xs text-[#0056B3] flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[18px] mt-0.5 flex-shrink-0">comment</span>
            <div>
              <strong class="font-bold">Catatan Arahan Dosen Pembimbing:</strong>
              <p class="mt-0.5 text-slate-700">{{ $lb->feedback }}</p>
            </div>
          </div>
        @endif

      </div>
    @empty
      <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-slate-200 space-y-3">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0056B3] mx-auto flex items-center justify-center">
          <span class="material-symbols-outlined text-[28px]">folder_open</span>
        </div>
        <h4 class="text-sm font-bold text-slate-800">Belum Ada Logbook Masuk</h4>
        <p class="text-xs text-slate-500">Logbook harian yang dibuat oleh mahasiswa bimbingan akan langsung muncul di sini.</p>
      </div>
    @endforelse
  </div>

</div>

<!-- 4. TAB CONTENT F4: BUKU PENGHUBUNG DOSEN PEMBIMBING (BIMBINGAN AKADEMIK) -->
<div class="tab-panel hidden flex flex-col space-y-5" id="tab-content-f4">
  <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-[10px] font-bold uppercase tracking-wider">FORM AKADEMIK F4</span>
        <span class="text-xs text-slate-500">Standar Kurikulum PKL TIK 2026</span>
      </div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Rekam Konsultasi &amp; Validasi Naskah Laporan PKL</h2>
      <p class="text-xs text-slate-500 mt-0.5">Catatan arahan bimbingan akademik dosen untuk pengesahan berkas pendaftaran sidang (F7).</p>
    </div>
    
    <button type="button" onclick="showToast('Membuka formulir jadwal konsultasi baru untuk mahasiswa bimbingan.')" class="h-10 px-4 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-sm">
      <span class="material-symbols-outlined text-[18px]">add_task</span>
      <span>+ Jadwalkan Sesi Konsultasi</span>
    </button>
  </div>

  <div class="space-y-4">
    <!-- Sesi 1 Bimbingan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] font-bold text-base flex items-center justify-center shrink-0">1</div>
      <div class="flex-1 space-y-1.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <div class="flex items-center gap-2">
            <h4 class="font-bold text-slate-800 text-sm sm:text-base">Konsultasi Bab I &amp; Penyesuaian Lingkup Form F2</h4>
            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Ahmad Fauzi</span>
          </div>
          <span class="text-xs text-slate-400">25 Feb 2026 • Daring via Zoom</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200/60">
          <strong>Arahan Dosen:</strong> Latar belakang sudah selaras dengan portofolio Cloud Engineer di Telkomsel. Pastikan arsitektur sistem digambarkan dengan standar C4 model pada Bab II.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">verified</span> ACC Bab I
          </span>
          <span class="text-xs font-mono text-slate-400">Tandatangan Digital: NIP.198503152010121002</span>
        </div>
      </div>
    </div>

    <!-- Sesi 2 Bimbingan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] font-bold text-base flex items-center justify-center shrink-0">2</div>
      <div class="flex-1 space-y-1.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <div class="flex items-center gap-2">
            <h4 class="font-bold text-slate-800 text-sm sm:text-base">Review Metodologi Pelaksanaan &amp; Verifikasi Logbook Sesi 1-20</h4>
            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Ahmad Fauzi</span>
          </div>
          <span class="text-xs text-slate-400">18 Mar 2026 • Portal SIM-PKL</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200/60">
          <strong>Arahan Dosen:</strong> Uraian logbook harian sudah representatif dengan kompetensi DevOps. Lampiran log sistem harus disensor dari kredensial rahasia industri.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">verified</span> Validasi Logbook Periode 1
          </span>
          <span class="text-xs font-mono text-slate-400">Tandatangan Digital: NIP.198503152010121002</span>
        </div>
      </div>
    </div>

    <!-- Sesi 3 Bimbingan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] font-bold text-base flex items-center justify-center shrink-0">3</div>
      <div class="flex-1 space-y-1.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <div class="flex items-center gap-2">
            <h4 class="font-bold text-slate-800 text-sm sm:text-base">Evaluasi Progres Penulisan Laporan Akhir (Bab III &amp; IV)</h4>
            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Ahmad Fauzi</span>
          </div>
          <span class="text-xs text-slate-400">08 Apr 2026 • Lab Jaringan Gd. AA</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200/60">
          <strong>Arahan Dosen:</strong> Bab IV (Pembahasan Proyek) perlu penambahan grafik performa latency sebelum vs setelah implementasi PgBouncer dan ArgoCD.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">verified</span> Revisi Minor Bab IV
          </span>
          <span class="text-xs font-mono text-slate-400">Tandatangan Digital: NIP.198503152010121002</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 5. TAB CONTENT F2: PEMANTAUAN CAPAIAN KOMPETENSI MAHASISWA -->
<div class="tab-panel hidden flex flex-col space-y-5" id="tab-content-f2">
  <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Form F2: Pemantauan Capaian Kompetensi Magang Mahasiswa</h2>
      <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Sinkronisasi matriks kompetensi teknis mahasiswa bimbingan di mitra industri.</p>
    </div>
    <span class="text-xs font-bold bg-teal-50 text-[#02796B] border border-teal-200 px-3 py-1.5 rounded-xl">
      Kurikulum TIK Berbasis OBE 2026
    </span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <!-- To Do -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold text-slate-700">Direncanakan (To-Do)</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">2</span>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-05</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Disaster Recovery Multi-Region Drill</h4>
        <p class="text-xs text-slate-500">Mahasiswa: Ahmad Fauzi • Target: Mei 2026</p>
      </div>
    </div>

    <!-- In Progress -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold text-amber-800">Sedang Berjalan</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">1</span>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-amber-700 font-mono font-bold">REQ-TIK-04</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Penerapan Observability Stack (Tempo &amp; Loki)</h4>
        <p class="text-xs text-slate-500">Mahasiswa: Ahmad Fauzi • Progres: 65%</p>
      </div>
    </div>

    <!-- Done -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold text-[#02796B]">Selesai &amp; Terverifikasi</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">3</span>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-01</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Otomasi Provisioning VM via Terraform</h4>
        <p class="text-xs text-slate-500">Telah diverifikasi pada logbook sesi #22</p>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: REVIEW & VALIDASI LOGBOOK OLEH DOSEN -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-dosen-review">
  <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div>
        <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Verifikasi Logbook (Form F3)</span>
        <h3 id="modal-student-name" class="text-base font-bold font-heading text-slate-800 mt-0.5">Review Logbook Mahasiswa</h3>
        <p id="modal-student-sub" class="text-xs text-slate-500">Detail aktivitas harian lapangan</p>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400" onclick="toggleModal('modal-dosen-review')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <!-- Subject & Date Display -->
    <div class="p-6 space-y-4">
      <div class="p-3.5 bg-blue-50/70 border border-blue-100 rounded-xl space-y-1">
        <div class="flex items-center justify-between gap-2">
          <span class="text-[10px] font-bold text-[#0056B3] uppercase tracking-wider">Subject Logbook:</span>
          <span id="modal-logbook-tanggal" class="text-xs font-mono text-slate-500"></span>
        </div>
        <h4 id="modal-logbook-subject" class="text-sm sm:text-base font-bold font-heading text-slate-800"></h4>
      </div>

      <!-- Long Text Content Written by Student -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas Mahasiswa (Long Text):</label>
        <div id="modal-logbook-content" class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line max-h-56 overflow-y-auto"></div>
      </div>

      <!-- Form to Approve or Request Revision with Feedback -->
      <form id="form-approve-logbook" method="POST" action="" class="space-y-4 pt-1">
        @csrf
        <input type="hidden" name="status" id="review-status-input" value="approved">

        <div>
          <label for="review-feedback" class="block text-xs font-bold text-slate-700 mb-1">
            Catatan / Arahan Bimbingan Dosen:
          </label>
          <textarea id="review-feedback" name="feedback" rows="3" placeholder="Tuliskan catatan arahan bimbingan atau poin revisi untuk mahasiswa..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]"></textarea>
          <span class="text-[11px] text-slate-400 mt-1 block">Wajib diisi jika meminta revisi logbook.</span>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="toggleModal('modal-dosen-review')" class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Tutup
          </button>
          <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <button type="button" onclick="submitDosenReview('revision')" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold font-heading flex items-center justify-center gap-1.5 transition-all">
              <span class="material-symbols-outlined text-[16px]">assignment_late</span>
              <span>Minta Revisi</span>
            </button>
            <button type="button" onclick="submitDosenReview('approved')" class="flex-1 sm:flex-none bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#005f54] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center justify-center gap-1.5 transition-all shadow-sm">
              <span class="material-symbols-outlined text-[16px]">check_circle</span>
              <span>Setujui &amp; Validasi</span>
            </button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  function switchTab(tabId) {
    document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));

    const btnF2 = document.getElementById('nav-btn-f2');
    const btnF3 = document.getElementById('nav-btn-f3');
    const btnF4 = document.getElementById('nav-btn-f4');

    [btnF2, btnF3, btnF4].forEach(btn => {
      btn.className = "px-4 py-2 rounded-lg text-xs sm:text-sm text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2 font-medium";
    });

    const targetContent = document.getElementById('tab-content-' + tabId.replace('tab-', ''));
    if (targetContent) {
      targetContent.classList.remove('hidden');
    }

    const activeBtn = document.getElementById('nav-btn-' + tabId.replace('tab-', ''));
    if (activeBtn) {
      activeBtn.className = "px-4 py-2 rounded-lg text-xs sm:text-sm bg-white text-[#0056B3] shadow-sm transition-all flex items-center gap-2 font-bold font-heading";
    }
  }

  function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.toggle('hidden');
    }
  }

  function openDosenReviewModal(id, studentName, studentNim, subject, tanggal, content, status, feedback) {
    document.getElementById('modal-student-name').innerText = `Review Logbook: ${studentName}`;
    document.getElementById('modal-student-sub').innerText = `NIM: ${studentNim}`;
    document.getElementById('modal-logbook-subject').innerText = subject;
    document.getElementById('modal-logbook-tanggal').innerText = tanggal;
    document.getElementById('modal-logbook-content').innerText = content;
    
    const form = document.getElementById('form-approve-logbook');
    form.action = `/logbooks/${id}/status`;

    document.getElementById('review-feedback').value = (feedback && feedback.trim() !== '') ? feedback : '';

    toggleModal('modal-dosen-review');
  }

  function submitDosenReview(status) {
    const feedbackInput = document.getElementById('review-feedback');
    const feedback = feedbackInput.value.trim();

    if (status === 'revision' && (!feedback || feedback.length < 5)) {
      alert('Harap tuliskan catatan arahan revisi untuk mahasiswa (minimal 5 karakter).');
      feedbackInput.focus();
      return;
    }

    document.getElementById('review-status-input').value = status;
    document.getElementById('form-approve-logbook').submit();
  }

  function filterDosenLogbooks() {
    const query = document.getElementById('search-logbook').value.toLowerCase();
    const studentFilter = document.getElementById('filter-student').value.toLowerCase();
    const statusFilter = document.getElementById('filter-status').value;
    const cards = document.querySelectorAll('.dosen-logbook-card');

    cards.forEach(card => {
      const cardText = card.innerText.toLowerCase();
      const cardStudent = card.getAttribute('data-student') || '';
      const cardStatus = card.getAttribute('data-status') || '';

      const matchesQuery = cardText.includes(query);
      const matchesStudent = (studentFilter === 'all') || cardStudent.includes(studentFilter);
      const matchesStatus = (statusFilter === 'all') || (cardStatus === statusFilter);

      card.style.display = (matchesQuery && matchesStudent && matchesStatus) ? '' : 'none';
    });
  }
</script>
@endpush
