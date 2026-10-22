@extends('layouts.app', [
  'title' => 'Verifikasi Logbook Mahasiswa (Form F3) - SIM-PKL Dosen',
  'pageTitle' => 'Verifikasi Logbook (Form F3)',
  'breadcrumbParent' => 'Portal Dosen TIK',
  'activePage' => 'logbook'
])

@section('content')
<!-- Sub-header Breadcrumb & Institutional Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <a href="{{ route('dashboard.dosen') }}" class="hover:underline">Portal Dosen TIK</a>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Monitoring Bimbingan</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <a href="{{ route('logbook.dosen') }}" class="text-[#0056B3] font-semibold hover:underline">Form F3 (Logbook Harian)</a>
      @if($selectedStudent)
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-slate-800 font-bold">{{ $selectedStudent->name }}</span>
      @endif
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      @if($selectedStudent)
        Logbook Harian: {{ $selectedStudent->name }}
      @else
        Verifikasi Logbook Harian Mahasiswa (Form F3)
      @endif
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      @if($selectedStudent)
        Daftar jurnal aktivitas harian magang industri mahasiswa bimbingan. Diisi berkala setiap hari tanpa batas kuota.
      @else
        Pilih mahasiswa bimbingan untuk melihat riwayat logbook harian, validasi uraian tugas, dan memberikan arahan bimbingan.
      @endif
    </p>
  </div>

  <!-- Lecturer Mentorship Capacity Pill -->
  <div class="flex items-center gap-3.5 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm self-start md:self-auto">
    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold flex-shrink-0 shadow-sm">
      <span class="material-symbols-outlined text-[24px]">supervisor_account</span>
    </div>
    <div class="flex flex-col pr-2 min-w-0">
      <div class="flex items-center gap-2">
        <span class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</span>
        <span class="w-2 h-2 rounded-full bg-[#009688]"></span>
      </div>
      <span class="text-[11px] text-slate-500 truncate">
        Kapasitas: <strong>{{ isset($students) ? $students->count() : 1 }} / 7 Mahasiswa Bimbingan</strong> (Maks. 7)
      </span>
    </div>
  </div>
</div>

<!-- 1. Top Metrics Bar (Tanpa Limit Artifisial / Bebas Diisi Setiap Hari) -->
@php
  $totalMentoredStudents = isset($students) ? $students->count() : 0;
  $allLogbooksCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->count()) : (isset($logbooks) ? $logbooks->count() : 0);
  $pendingCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->where('status', 'pending')->count()) : 0;
  $approvedCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->where('status', 'approved')->count()) : 0;
  $revisionCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->where('status', 'revision')->count()) : 0;
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  
  <!-- Metric 1: Mahasiswa Bimbingan -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Mahasiswa Bimbingan</span>
      <span class="text-2xl font-extrabold text-slate-900 font-heading mt-1 block">{{ $totalMentoredStudents }} Mahasiswa</span>
      <span class="text-[11px] text-[#0056B3] font-medium">Bimbingan PKL Jurusan TIK</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">groups</span>
    </div>
  </div>

  <!-- Metric 2: Total Logbook Masuk (Tanpa Batasan Limit) -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Total Logbook Masuk</span>
      <span class="text-2xl font-extrabold text-slate-900 font-heading mt-1 block">{{ $allLogbooksCount }} Jurnal</span>
      <span class="text-[11px] text-[#02796B] font-medium">Diisi harian tanpa batasan limit</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">auto_stories</span>
    </div>
  </div>

  <!-- Metric 3: Menunggu Validasi -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Menunggu Validasi</span>
      <span class="text-2xl font-extrabold text-amber-600 font-heading mt-1 block">{{ $pendingCount }} Jurnal</span>
      <span class="text-[11px] text-amber-700 font-medium">{{ $pendingCount > 0 ? 'Perlu ditinjau dosen' : 'Semua sudah tervalidasi' }}</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center relative">
      <span class="material-symbols-outlined text-[24px]">pending_actions</span>
      @if($pendingCount > 0)
        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
      @endif
    </div>
  </div>

  <!-- Metric 4: Telah Disetujui -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Telah Disetujui</span>
      <span class="text-2xl font-extrabold text-emerald-600 font-heading mt-1 block">{{ $approvedCount }} Jurnal</span>
      <span class="text-[11px] text-emerald-600 font-medium">Diverifikasi stempel digital</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">verified</span>
    </div>
  </div>

</div>

@if(!$selectedStudent)
  <!-- ======================================================== -->
  <!-- LEVEL 1: LIST MAHASISWA BIMBINGAN (TIDAK LANGSUNG NUMPUK) -->
  <!-- ======================================================== -->
  <div class="space-y-5">
    
    <!-- Toolbar & Search Mahasiswa -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-base sm:text-lg font-bold font-heading text-slate-900">Daftar Mahasiswa Bimbingan</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pilih salah satu mahasiswa bimbingan di bawah ini untuk melihat daftar riwayat logbook hariannya secara teratur.</p>
      </div>

      <div class="flex items-center gap-3">
        <div class="relative w-full sm:w-72">
          <input type="text" id="search-student" onkeyup="filterStudentCards()" placeholder="Cari nama, NIM, atau prodi..." class="w-full h-10 pl-9 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20">
          <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-slate-400 text-[18px]">search</span>
        </div>
      </div>
    </div>

    <!-- Grid List Mahasiswa Bimbingan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5" id="student-cards-container">
      @forelse($students ?? [] as $st)
        @php
          $stTotal = $st->logbooks->count();
          $stPending = $st->logbooks->where('status', 'pending')->count();
          $stApproved = $st->logbooks->where('status', 'approved')->count();
          $stRevision = $st->logbooks->where('status', 'revision')->count();
          $stLatest = $st->logbooks->sortByDesc('tanggal')->first();
        @endphp
        <div class="student-card bg-white rounded-2xl border border-slate-200/90 hover:border-blue-300 hover:shadow-md transition-all p-5 sm:p-6 flex flex-col justify-between space-y-4"
             data-name="{{ strtolower($st->name) }}"
             data-nim="{{ strtolower($st->identifier ?? '') }}">
          
          <div class="space-y-3">
            <!-- Student Header -->
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                  {{ strtoupper(substr($st->name, 0, 2)) }}
                </div>
                <div>
                  <h3 class="text-base font-bold font-heading text-slate-900">{{ $st->name }}</h3>
                  <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-xs font-mono px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] font-semibold">NIM: {{ $st->identifier ?? '2107411002' }}</span>
                    <span class="text-xs text-slate-500">{{ $st->prodi ?? 'Teknik Informatika (D4)' }}</span>
                  </div>
                </div>
              </div>

              @if($stPending > 0)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold shrink-0">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                  {{ $stPending }} Perlu Review
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold shrink-0">
                  <span class="material-symbols-outlined text-[14px]">check_circle</span>
                  Up to Date
                </span>
              @endif
            </div>

            <!-- Internship Info -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 text-xs space-y-1">
              <div class="flex items-center gap-1.5 text-slate-700 font-semibold">
                <span class="material-symbols-outlined text-[16px] text-teal-600">domain</span>
                <span>PT Telkomsel (Cloud Platform &amp; DevOps)</span>
              </div>
              <div class="flex items-center justify-between text-slate-500 pt-1 border-t border-slate-200/50">
                <span>Logbook Terakhir:</span>
                <span class="font-mono text-slate-700 font-medium">
                  {{ $stLatest ? $stLatest->tanggal->translatedFormat('d M Y') : 'Belum pernah mengisi' }}
                </span>
              </div>
            </div>

            <!-- Logbook Mini Stats -->
            <div class="grid grid-cols-3 gap-2 text-center pt-1">
              <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Log</span>
                <span class="text-base font-extrabold text-slate-800">{{ $stTotal }}</span>
              </div>
              <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                <span class="text-[10px] uppercase font-bold text-emerald-600 block">Disetujui</span>
                <span class="text-base font-extrabold text-emerald-700">{{ $stApproved }}</span>
              </div>
              <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                <span class="text-[10px] uppercase font-bold text-amber-600 block">Menunggu</span>
                <span class="text-base font-extrabold text-amber-700">{{ $stPending }}</span>
              </div>
            </div>
          </div>

          <!-- Action Button: Open Student Logbook List -->
          <div class="pt-2 border-t border-slate-100">
            <a href="{{ route('logbook.dosen', ['student_id' => $st->id]) }}" class="w-full py-2.5 px-4 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white text-xs sm:text-sm font-bold font-heading flex items-center justify-center gap-2 shadow-sm transition-all">
              <span>Buka Logbook Mahasiswa ({{ $stTotal }})</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>

        </div>
      @empty
        <div class="col-span-2 p-12 text-center bg-white rounded-2xl border border-dashed border-slate-200 space-y-3">
          <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0056B3] mx-auto flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">groups</span>
          </div>
          <h4 class="text-sm font-bold text-slate-800">Belum Ada Mahasiswa Bimbingan</h4>
          <p class="text-xs text-slate-500">Mahasiswa yang dibimbing oleh Anda akan tercantum di sini.</p>
        </div>
      @endforelse
    </div>

  </div>
@else
  <!-- ======================================================== -->
  <!-- LEVEL 2: DAFTAR LOGBOOK HARIAN MAHASISWA TERPILIH       -->
  <!-- ======================================================== -->
  <div class="space-y-5">
    
    <!-- Back Button & Selected Student Overview Header -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
      
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
        <a href="{{ route('logbook.dosen') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-[#0056B3] hover:text-[#00428A] bg-blue-50 hover:bg-blue-100 px-3.5 py-2 rounded-xl transition-all self-start">
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          <span>Kembali ke Daftar Mahasiswa</span>
        </a>

        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold text-slate-500">Masa PKL: Semester Genap 2025/2026</span>
        </div>
      </div>

      <!-- Student Profile Card -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-extrabold text-lg shadow-md shrink-0">
            {{ strtoupper(substr($selectedStudent->name, 0, 2)) }}
          </div>
          <div>
            <div class="flex items-center gap-2 flex-wrap">
              <h2 class="text-lg sm:text-xl font-bold font-heading text-slate-900">{{ $selectedStudent->name }}</h2>
              <span class="text-xs font-mono px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] font-bold">NIM: {{ $selectedStudent->identifier ?? '2107411002' }}</span>
              <span class="text-xs text-slate-500">{{ $selectedStudent->prodi ?? 'Teknik Informatika (D4)' }}</span>
            </div>
            
            <div class="flex items-center gap-4 text-xs text-slate-600 mt-1 flex-wrap">
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-teal-600">domain</span>
                <strong>PT Telkomsel (Cloud Platform &amp; DevOps)</strong>
              </span>
              <span>•</span>
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-[#0056B3]">co_present</span>
                <span>Dosen Pembimbing: {{ $dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</span>
              </span>
            </div>
          </div>
        </div>

        <!-- Student Quick Counter -->
        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200/80 shrink-0">
          <div class="text-center px-3 border-r border-slate-200">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Jurnal</span>
            <span class="text-base font-extrabold text-slate-800">{{ $logbooks->count() }}</span>
          </div>
          <div class="text-center px-3 border-r border-slate-200">
            <span class="text-[10px] uppercase font-bold text-amber-600 block">Review</span>
            <span class="text-base font-extrabold text-amber-700">{{ $logbooks->where('status', 'pending')->count() }}</span>
          </div>
          <div class="text-center px-3">
            <span class="text-[10px] uppercase font-bold text-emerald-600 block">Valid</span>
            <span class="text-base font-extrabold text-emerald-700">{{ $logbooks->where('status', 'approved')->count() }}</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Filter & Search Toolbar for Selected Student's Logbooks -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
      <div class="flex flex-wrap items-center gap-3">
        <!-- Status Filter -->
        <div class="relative">
          <select id="filter-status" onchange="filterSelectedStudentLogbooks()" class="h-10 pl-4 pr-9 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none cursor-pointer font-medium">
            <option value="all">Semua Status ({{ $logbooks->count() }})</option>
            <option value="pending">Menunggu Validasi ({{ $logbooks->where('status', 'pending')->count() }})</option>
            <option value="approved">Telah Disetujui ({{ $logbooks->where('status', 'approved')->count() }})</option>
            <option value="revision">Perlu Revisi ({{ $logbooks->where('status', 'revision')->count() }})</option>
          </select>
          <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-slate-400 pointer-events-none text-[18px]">filter_list</span>
        </div>

        <!-- Search Input -->
        <div class="relative flex-1 md:w-72">
          <input id="search-logbook" onkeyup="filterSelectedStudentLogbooks()" class="w-full h-10 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20" placeholder="Cari tanggal, subject, atau isi..." type="text">
          <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-slate-400 text-[18px]">search</span>
        </div>
      </div>

      <div class="text-xs text-slate-500 font-medium">
        Menampilkan <strong>{{ $logbooks->count() }}</strong> jurnal aktivitas harian
      </div>
    </div>

    <!-- Feed / List Logbook Harian Mahasiswa Terpilih -->
    <div class="space-y-4" id="logbooks-feed">
      @forelse($logbooks ?? [] as $lb)
        <div class="dosen-logbook-card p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/90 hover:border-blue-300 transition-all shadow-sm space-y-4"
             data-status="{{ $lb->status }}">
          
          <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-[#0056B3] text-xs font-bold font-mono">
                  <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                  <span>{{ $lb->tanggal->translatedFormat('l, d F Y') }}</span>
                </span>
                <span class="text-xs text-slate-400 font-mono">Log #{{ $loop->iteration }}</span>
              </div>
              
              <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading pt-1">
                {{ $lb->subject }}
              </h3>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-auto flex-shrink-0">
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

              <!-- LEVEL 3 TRIGGER: LIHAT DETAIL & REVIEW LOGBOOK -->
              <button type="button" 
                      onclick="openDosenReviewModal({{ $lb->id }}, '{{ addslashes($selectedStudent->name) }}', '{{ addslashes($selectedStudent->identifier ?? '') }}', '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('l, d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}')"
                      class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[17px]">rate_review</span>
                <span>Lihat Detail &amp; Review</span>
              </button>
            </div>
          </div>

          <!-- Cuplikan Uraian Aktivitas (Long Text) -->
          <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
              Ringkasan Aktivitas Harian Mahasiswa:
            </label>
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed line-clamp-3">
              {{ $lb->content }}
            </div>
          </div>

          @if($lb->feedback)
            <div class="p-3 bg-blue-50/80 border border-blue-200/80 rounded-xl text-xs text-[#0056B3] flex items-start gap-2.5">
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
          <h4 class="text-sm font-bold text-slate-800">Belum Ada Logbook Harian</h4>
          <p class="text-xs text-slate-500">Mahasiswa ini belum membuat jurnal aktivitas logbook harian.</p>
        </div>
      @endforelse
    </div>

  </div>
@endif

<!-- ======================================================== -->
<!-- LEVEL 3: MODAL DETAIL LENGKAP & REVIEW LOGBOOK HARIAN    -->
<!-- ======================================================== -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-dosen-review">
  <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div>
        <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Detail &amp; Verifikasi Logbook Harian (Form F3)</span>
        <h3 id="modal-student-name" class="text-base sm:text-lg font-bold font-heading text-slate-800 mt-0.5">Review Logbook Mahasiswa</h3>
        <p id="modal-student-sub" class="text-xs text-slate-500">Detail lengkap aktivitas harian lapangan mahasiswa bimbingan</p>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400" onclick="toggleModal('modal-dosen-review')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <div class="p-6 space-y-4">
      
      <!-- Subject & Tanggal Kegiatan -->
      <div class="p-4 bg-blue-50/70 border border-blue-100 rounded-xl space-y-1.5">
        <div class="flex items-center justify-between gap-2 flex-wrap">
          <span class="text-[10px] font-bold text-[#0056B3] uppercase tracking-wider">Topik / Subject Kegiatan:</span>
          <span id="modal-logbook-tanggal" class="text-xs font-mono text-slate-600 bg-white/80 px-2.5 py-0.5 rounded border border-blue-100"></span>
        </div>
        <h4 id="modal-logbook-subject" class="text-sm sm:text-base font-bold font-heading text-slate-900"></h4>
      </div>

      <!-- Long Text Content Written by Student -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">
          Uraian Lengkap Aktivitas Mahasiswa (Long Text):
        </label>
        <div id="modal-logbook-content" class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line max-h-60 overflow-y-auto font-normal"></div>
      </div>

      <!-- Review & Validation Form for Dosen -->
      <form id="form-approve-logbook" method="POST" action="" class="space-y-4 pt-1">
        @csrf
        <input type="hidden" name="status" id="review-status-input" value="approved">

        <div>
          <label for="review-feedback" class="block text-xs font-bold text-slate-700 mb-1">
            Catatan / Arahan Bimbingan Dosen:
          </label>
          <textarea id="review-feedback" name="feedback" rows="3" placeholder="Tuliskan catatan arahan bimbingan atau instruksi perbaikan untuk mahasiswa..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]"></textarea>
          <span class="text-[11px] text-slate-400 mt-1 block">Wajib diisi jika Anda memilih status Minta Revisi.</span>
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

  function filterStudentCards() {
    const query = (document.getElementById('search-student')?.value || '').toLowerCase();
    const cards = document.querySelectorAll('.student-card');

    cards.forEach(card => {
      const cardName = card.getAttribute('data-name') || '';
      const cardNim = card.getAttribute('data-nim') || '';
      const text = card.innerText.toLowerCase();

      const match = text.includes(query) || cardName.includes(query) || cardNim.includes(query);
      card.style.display = match ? '' : 'none';
    });
  }

  function filterSelectedStudentLogbooks() {
    const query = (document.getElementById('search-logbook')?.value || '').toLowerCase();
    const statusFilter = document.getElementById('filter-status')?.value || 'all';
    const cards = document.querySelectorAll('.dosen-logbook-card');

    cards.forEach(card => {
      const cardText = card.innerText.toLowerCase();
      const cardStatus = card.getAttribute('data-status') || '';

      const matchesQuery = cardText.includes(query);
      const matchesStatus = (statusFilter === 'all') || (cardStatus === statusFilter);

      card.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
    });
  }
</script>
@endpush
