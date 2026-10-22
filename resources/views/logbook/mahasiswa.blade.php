@extends('layouts.app', [
  'title' => 'Daily Internship Workspace & Logbook (F2-F4) - SIM-PKL',
  'pageTitle' => 'Daily Workspace & Logbook (F2-F4)',
  'breadcrumbParent' => 'Portal Mahasiswa TIK',
  'activePage' => 'logbook'
])

@section('content')
<!-- Sub-header Breadcrumb & Institutional Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Workspace Mahasiswa</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Monitoring Pelaksanaan</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Form F2 – F4 (Terpadu)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Daily Internship Workspace &amp; Logbook
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Manajemen pemenuhan syarat operasional magang industri DUDI dan bimbingan akademik Jurusan TIK PNJ 2026.
    </p>
  </div>

  <!-- Student & Industry Quick Snapshot Pill -->
  <div class="flex items-center gap-3.5 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm self-start md:self-auto">
    <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center font-bold flex-shrink-0">
      <span class="material-symbols-outlined text-[26px]">corporate_fare</span>
    </div>
    <div class="flex flex-col pr-2 min-w-0">
      <div class="flex items-center gap-2">
        <span class="text-xs sm:text-sm font-bold text-slate-800 truncate">PT Telkomsel (Cloud Platform)</span>
        <span class="w-2 h-2 rounded-full bg-[#009688]"></span>
      </div>
      <span class="text-[11px] text-slate-500 truncate">
        Supervisor: Hendra Gunawan, S.T. • Dospem: Dr. Bambang Sutrisno, M.T.
      </span>
    </div>
  </div>
</div>

<!-- 1. Top Metrics Bar (Prerequisite Trackers) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
  
  <!-- Counter Widget A: Logbook Industri Form F3 -->
  @php
    $totalLogbooksCount = isset($logbooks) ? $logbooks->count() : 0;
    $approvedLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'approved')->count() : 0;
    $pendingLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0;
    $revisionLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'revision')->count() : 0;
    $minLogbookTarget = 10;
    $logbookPercent = ($minLogbookTarget > 0) ? min(100, round(($approvedLogbooksCount / $minLogbookTarget) * 100)) : 0;
  @endphp
  <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card relative overflow-hidden flex flex-col justify-between">
    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-teal-50 rounded-full blur-2xl pointer-events-none"></div>
    <div>
      <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2">
          <span class="p-2 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px] material-symbols-fill">task_alt</span>
          </span>
          <span class="text-sm font-bold text-slate-800 font-heading">Logbook Industri (Form F3)</span>
        </div>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-teal-50 text-[#02796B] border border-teal-200/80 text-xs font-bold">
          <span class="w-1.5 h-1.5 rounded-full bg-[#009688]"></span>
          {{ $approvedLogbooksCount >= $minLogbookTarget ? 'Memenuhi Syarat Evaluasi' : 'Dalam Pemenuhan' }}
        </span>
      </div>

      <div class="flex items-baseline gap-2 mt-2 mb-3">
        <span class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">{{ $approvedLogbooksCount }}</span>
        <span class="text-sm text-slate-500 font-medium">/ {{ $minLogbookTarget }} Target Sesi Terverifikasi</span>
        <span class="ml-auto text-xs font-bold text-[#02796B] font-mono">{{ $logbookPercent }}% Target</span>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
        <div class="bg-gradient-to-r from-[#02796B] to-[#009688] h-2.5 rounded-full transition-all duration-500" style="width: {{ $logbookPercent }}%"></div>
      </div>
    </div>

    <div class="flex items-center justify-between pt-4 mt-5 bg-slate-50/70 -mx-6 -mb-6 px-6 py-3 border-t border-slate-100">
      <span class="text-xs text-slate-500 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px] text-[#02796B]">verified_user</span>
        <span>Sinkronisasi Buku Saku PKL F3 Jurusan TIK</span>
      </span>
      <span class="text-xs font-bold text-[#02796B]">
        {{ $approvedLogbooksCount >= $minLogbookTarget ? 'Siap Sidang Akhir' : 'Lengkapi Logbook' }}
      </span>
    </div>
  </div>

  <!-- Counter Widget B: Bimbingan Kampus Form F4 -->
  <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card relative overflow-hidden flex flex-col justify-between">
    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-amber-50 rounded-full blur-2xl pointer-events-none"></div>
    <div>
      <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2">
          <span class="p-2 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">school</span>
          </span>
          <span class="text-sm font-bold text-slate-800 font-heading">Bimbingan Kampus (Form F4)</span>
        </div>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
          Kurang 1 Sesi Sebelum Sidang/F7
        </span>
      </div>

      <div class="flex items-baseline gap-2 mt-2 mb-3">
        <span class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">3</span>
        <span class="text-sm text-slate-500 font-medium">/ 4 Minimal Bimbingan Berkala</span>
        <span class="ml-auto text-xs font-bold text-amber-800 font-mono">75% Progres</span>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
        <div class="bg-amber-500 h-2.5 rounded-full w-3/4 transition-all duration-500"></div>
      </div>
    </div>

    <div class="flex items-center justify-between pt-4 mt-5 bg-slate-50/70 -mx-6 -mb-6 px-6 py-3 border-t border-slate-100">
      <span class="text-xs text-slate-500 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px] text-amber-600">info</span>
        <span>Batas submit draft bimbingan ke-4: 25 Apr 2026</span>
      </span>
      <button type="button" class="text-xs text-[#0056B3] hover:underline font-bold flex items-center gap-1" onclick="switchTab('tab-f4')">
        <span>Jadwalkan Konsultasi</span>
        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
      </button>
    </div>
  </div>

</div>

<!-- 2. Segmented Navigation Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-2">
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2">
    <div class="flex items-center p-1 bg-slate-100/90 rounded-xl flex-wrap gap-1">
      <button type="button" id="nav-btn-f2" onclick="switchTab('tab-f2')" class="px-4 py-2 rounded-lg text-xs sm:text-sm text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2 font-medium">
        <span class="material-symbols-outlined text-[18px]">terminal</span>
        <span>Form F2: User Requirement Industri</span>
      </button>
      <button type="button" id="nav-btn-f3" onclick="switchTab('tab-f3')" class="px-4 py-2 rounded-lg text-xs sm:text-sm bg-white text-[#0056B3] shadow-sm transition-all flex items-center gap-2 font-bold font-heading">
        <span class="material-symbols-outlined text-[18px] text-[#02796B]">menu_book</span>
        <span>Form F3: Buku Penghubung Industri (Logbook)</span>
      </button>
      <button type="button" id="nav-btn-f4" onclick="switchTab('tab-f4')" class="px-4 py-2 rounded-lg text-xs sm:text-sm text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2 font-medium">
        <span class="material-symbols-outlined text-[18px]">co_present</span>
        <span>Form F4: Buku Penghubung Dosen Pembimbing</span>
      </button>
    </div>
    <div class="flex items-center gap-2 px-3 py-1">
      <span class="w-2 h-2 rounded-full bg-[#009688]"></span>
      <span class="text-xs text-slate-500 font-medium">Siklus Pelaksanaan Magang Terdaftar</span>
    </div>
  </div>
</div>

<!-- 3. KONTEN TAB F3 (BUKU PENGHUBUNG INDUSTRI / LOGBOOK) - ACTIVE TAB -->
<div class="tab-panel flex flex-col space-y-5" id="tab-content-f3">
  
  <!-- Header Aksi & Filter Toolbar -->
  <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
    <div class="flex flex-wrap items-center gap-3">
      <div class="relative">
        <select id="filter-cycle" onchange="filterLogbookTable()" class="h-10 pl-4 pr-9 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none cursor-pointer">
          <option value="all">Semua Siklus PKL (2026)</option>
          <option value="apr">April 2026</option>
          <option value="mar">Maret 2026</option>
          <option value="feb">Februari 2026</option>
        </select>
        <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-slate-400 pointer-events-none text-[18px]">expand_more</span>
      </div>
      <div class="relative">
        <select id="filter-status" onchange="filterLogbookTable()" class="h-10 pl-4 pr-9 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none cursor-pointer font-medium">
          <option value="all">Semua Status ({{ $totalLogbooksCount }})</option>
          <option value="pending">Menunggu Verifikasi ({{ $pendingLogbooksCount }})</option>
          <option value="approved">Diverifikasi / Disetujui ({{ $approvedLogbooksCount }})</option>
          <option value="revision">Perlu Revisi ({{ $revisionLogbooksCount }})</option>
        </select>
        <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-slate-400 pointer-events-none text-[18px]">filter_list</span>
      </div>
      <div class="relative flex-1 md:w-64">
        <input id="search-logbook" onkeyup="filterLogbookTable()" class="w-full h-10 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20" placeholder="Cari subject, uraian aktivitas..." type="text">
        <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-slate-400 text-[18px]">search</span>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button type="button" class="h-10 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs sm:text-sm transition-colors flex items-center gap-2 shadow-sm" onclick="downloadRekap()">
        <span class="material-symbols-outlined text-[18px]">cloud_download</span>
        <span>Unduh Rekap (PDF)</span>
      </button>
      <button type="button" class="h-10 px-4 rounded-xl bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#005f54] text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-md" onclick="toggleModal('modal-add-log')">
        <span class="material-symbols-outlined text-[18px]">add</span>
        <span>+ Tambah Logbook Harian</span>
      </button>
    </div>
  </div>

  <!-- Tabel Logbook Form F3 -->
  <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse" id="table-logbooks">
        <thead>
          <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
            <th class="py-3.5 px-4 w-12 text-center">No</th>
            <th class="py-3.5 px-4 w-44">Hari / Tanggal</th>
            <th class="py-3.5 px-4">Subject &amp; Uraian Aktivitas (Long Text)</th>
            <th class="py-3.5 px-4 w-52">Status Verifikasi Dosen</th>
            <th class="py-3.5 px-4 w-36 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
          
          <!-- Loop real logbooks from database -->
          @php $rowNum = 1; @endphp
          @forelse($logbooks ?? [] as $lb)
            <tr class="logbook-row hover:bg-blue-50/30 transition-colors" data-status="{{ $lb->status }}">
              <td class="py-4 px-4 text-center font-bold text-slate-400 font-mono">{{ sprintf('%02d', $rowNum++) }}</td>
              
              <td class="py-4 px-4">
                <div class="flex flex-col">
                  <span class="font-bold text-slate-800">{{ $lb->tanggal->translatedFormat('l, d M Y') }}</span>
                  <span class="text-xs text-slate-500 font-mono flex items-center gap-1 mt-0.5">
                    <span class="material-symbols-outlined text-[13px]">calendar_today</span>
                    <span>{{ $lb->tanggal->format('Y-m-d') }}</span>
                  </span>
                </div>
              </td>

              <td class="py-4 px-4">
                <div class="flex flex-col pr-4 space-y-1.5">
                  <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-bold text-slate-900 font-heading text-sm sm:text-base">{{ $lb->subject }}</h4>
                    <span class="text-[10px] bg-blue-50 text-[#0056B3] px-2 py-0.5 rounded font-mono font-semibold">Form F3</span>
                  </div>
                  
                  <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/60 p-3 rounded-xl border border-slate-200/60">
                    {{ $lb->content }}
                  </p>

                  @if($lb->status === 'revision')
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 flex items-start gap-2.5 shadow-sm">
                      <span class="material-symbols-outlined text-[18px] text-rose-600 mt-0.5 flex-shrink-0">warning</span>
                      <div class="flex-1">
                        <strong class="font-bold">Catatan Revisi Dosen Pembimbing:</strong>
                        <p class="text-slate-700 mt-0.5">{{ $lb->feedback ?? 'Harap perbaiki uraian aktivitas sesuai arahan bimbingan.' }}</p>
                      </div>
                      <button type="button" 
                              onclick="openEditLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->format('Y-m-d') }}', {{ json_encode($lb->content) }})"
                              class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[11px] font-bold transition-colors whitespace-nowrap shadow-sm">
                        Perbaiki Sekarang
                      </button>
                    </div>
                  @elseif($lb->feedback)
                    <div class="p-2.5 bg-blue-50/80 border border-blue-200/80 rounded-xl text-xs text-[#0056B3] flex items-start gap-2 mt-1">
                      <span class="material-symbols-outlined text-[16px] mt-0.5 flex-shrink-0">comment</span>
                      <div>
                        <strong class="font-bold">Catatan Arahan Dosen:</strong>
                        <span class="text-slate-700 ml-1">{{ $lb->feedback }}</span>
                      </div>
                    </div>
                  @endif
                </div>
              </td>

              <td class="py-4 px-4">
                <div class="flex items-center gap-2.5">
                  @if($lb->status === 'approved')
                    <button type="button" 
                            onclick="openVerificationModal('{{ $lb->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}', '{{ $lb->dosen->identifier ?? '198503152010121002' }}', '{{ $lb->updated_at ? $lb->updated_at->translatedFormat('d M Y H:i') . ' WIB' : '22 Okt 2026' }}', 'SHA256: {{ substr(hash('sha256', $lb->id . $lb->subject), 0, 32) }}...')" 
                            class="p-2 rounded-xl bg-teal-50 hover:bg-teal-100 text-[#02796B] border border-teal-200 transition-colors flex items-center justify-center shadow-sm" 
                            title="Lihat Stempel Verifikasi Digital">
                      <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                    </button>
                    <div class="flex flex-col">
                      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold w-max">
                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                        <span>Diverifikasi</span>
                      </span>
                      <span class="text-[11px] text-slate-600 font-medium mt-0.5">{{ $lb->dosen->name ?? 'Dr. Bambang Sutrisno' }}</span>
                      <span class="text-[10px] text-slate-400">Dosen Pembimbing PKL</span>
                    </div>
                  @elseif($lb->status === 'revision')
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200 shrink-0">
                      <span class="material-symbols-outlined text-[18px]">edit_note</span>
                    </div>
                    <div class="flex flex-col">
                      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold w-max">
                        <span class="material-symbols-outlined text-[13px]">error</span>
                        <span>Perlu Revisi</span>
                      </span>
                      <span class="text-[11px] text-slate-500 mt-0.5">{{ $lb->dosen->name ?? 'Dosen Pembimbing' }}</span>
                      <span class="text-[10px] text-rose-500 font-semibold">Menunggu Perbaikan</span>
                    </div>
                  @else
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 shrink-0">
                      <span class="material-symbols-outlined text-[18px] animate-spin">refresh</span>
                    </div>
                    <div class="flex flex-col">
                      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold w-max">
                        <span class="material-symbols-outlined text-[13px]">hourglass_top</span>
                        <span>Menunggu Dosen</span>
                      </span>
                      <span class="text-[11px] text-slate-500 mt-0.5">{{ $lb->dosen->name ?? 'Dr. Bambang Sutrisno' }}</span>
                      <span class="text-[10px] text-slate-400">Antrean Validasi</span>
                    </div>
                  @endif
                </div>
              </td>

              <td class="py-4 px-4 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <!-- View Button -->
                  <button type="button" 
                          onclick="viewLogbookDetail('{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}', {{ $lb->id }}, '{{ $lb->tanggal->format('Y-m-d') }}')"
                          class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-[#0056B3] transition-colors" 
                          title="Lihat Detail Logbook">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                  </button>

                  <!-- Edit & Delete Buttons (for pending or revision) -->
                  @if($lb->status !== 'approved')
                    <button type="button" 
                            onclick="openEditLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->format('Y-m-d') }}', {{ json_encode($lb->content) }})"
                            class="p-1.5 rounded-lg hover:bg-amber-50 text-slate-500 hover:text-amber-700 transition-colors" 
                            title="Ubah Logbook">
                      <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>

                    <button type="button" 
                            onclick="openDeleteLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}')"
                            class="p-1.5 rounded-lg hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors" 
                            title="Hapus Logbook">
                      <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                  @else
                    <span class="p-1.5 text-slate-300 cursor-not-allowed" title="Logbook telah disetujui resmi (Terkunci)">
                      <span class="material-symbols-outlined text-[18px]">lock</span>
                    </span>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-12 px-4 text-center text-slate-500">
                <div class="max-w-sm mx-auto flex flex-col items-center justify-center space-y-3">
                  <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">menu_book</span>
                  </div>
                  <h4 class="text-sm font-bold text-slate-800 font-heading">Belum Ada Logbook Harian</h4>
                  <p class="text-xs text-slate-500 leading-relaxed">
                    Mulai catat aktivitas harian magang industri Anda untuk diverifikasi oleh dosen pembimbing.
                  </p>
                  <button type="button" onclick="toggleModal('modal-add-log')" class="px-4 py-2 bg-[#0056B3] hover:bg-[#00428A] text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Tambah Logbook Sekarang</span>
                  </button>
                </div>
              </td>
            </tr>
          @endforelse

        </tbody>
      </table>
    </div>

    <!-- Pagination / Footer Info -->
    <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 bg-slate-50/80 border-t border-slate-100">
      <span class="text-xs text-slate-500">
        Menampilkan <strong>{{ $totalLogbooksCount }} entri logbook</strong> (Siklus Magang Semester Genap 2025/2026)
      </span>
      <div class="flex items-center gap-1 mt-2 sm:mt-0">
        <span class="px-3 py-1 rounded-lg bg-[#0056B3] text-white text-xs font-bold">1</span>
      </div>
    </div>
  </div>

</div>

<!-- 4. KONTEN TAB F2: USER REQUIREMENT INDUSTRI (MINI KANBAN) -->
<div class="tab-panel hidden flex flex-col space-y-5" id="tab-content-f2">
  <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Form F2: Pemetaan Kebutuhan &amp; Backlog Kompetensi DUDI</h2>
      <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Matriks sasaran kompetensi teknis yang disepakati bersama PT Telkomsel &amp; KPS TI PNJ.</p>
    </div>
    <div class="flex items-center gap-3">
      <span class="text-xs font-bold bg-teal-50 text-[#02796B] border border-teal-200 px-3 py-1.5 rounded-xl">
        F2 Disetujui KPS • Rev 1.2
      </span>
      <button type="button" onclick="showToast('Mengunduh berkas matriks Form F2...')" class="h-9 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px]">file_download</span> Cetak Berkas F2
      </button>
    </div>
  </div>

  <!-- Mini Kanban 3 Kolom: To-Do, In Progress, Done -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    
    <!-- To-Do Column -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
          <span class="text-xs font-bold font-heading text-slate-800">To-Do / Direncanakan</span>
        </div>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">2</span>
      </div>
      
      <div class="space-y-3">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-05</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Disaster Recovery Multi-Region Drill</h4>
          <p class="text-xs text-slate-500">Uji failover automated database replikasi cluster Jakarta-Surabaya.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-slate-400">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">calendar_month</span> Target: Mei M2</span>
            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">Infrastruktur</span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-06</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Audit Keamanan CIS Benchmark K8s</h4>
          <p class="text-xs text-slate-500">Hardening pod security admission dan network policy calico.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-slate-400">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">calendar_month</span> Target: Mei M3</span>
            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">Security</span>
          </div>
        </div>
      </div>
    </div>

    <!-- In Progress Column -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
          <span class="text-xs font-bold font-heading text-slate-800">Sedang Dikerjakan</span>
        </div>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">1</span>
      </div>

      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
        <span class="text-[10px] text-amber-700 font-mono font-bold">REQ-TIK-04</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Penerapan Observability Stack (Tempo &amp; Loki)</h4>
        <p class="text-xs text-slate-500">Implementasi distributed tracing OpenTelemetry pada arsitektur microservices internal.</p>
        <div class="w-full bg-slate-100 rounded-full h-1.5">
          <div class="bg-amber-500 h-1.5 rounded-full w-2/3"></div>
        </div>
        <div class="flex items-center justify-between pt-1 text-[11px] text-slate-500">
          <span>65% Selesai</span>
          <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold">Observability</span>
        </div>
      </div>
    </div>

    <!-- Done Column -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-[#009688]"></span>
          <span class="text-xs font-bold font-heading text-slate-800">Selesai &amp; Diverifikasi</span>
        </div>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">2</span>
      </div>

      <div class="space-y-3">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-01</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Otomasi Provisioning VM via Terraform</h4>
          <p class="text-xs text-slate-500">Modular script Terraform untuk deploy bastion host &amp; private subnets.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-[#02796B]">
            <span class="font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">check_circle</span> Terverifikasi F3</span>
            <span class="px-2 py-0.5 rounded bg-teal-50 font-semibold">IaC</span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-02</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Setup CI/CD Pipeline GitOps ArgoCD</h4>
          <p class="text-xs text-slate-500">Otomasi deployment manifest dan sync state repository konfigurasi.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-[#02796B]">
            <span class="font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">check_circle</span> Terverifikasi F3</span>
            <span class="px-2 py-0.5 rounded bg-teal-50 font-semibold">DevOps</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- 5. KONTEN TAB F4: BUKU PENGHUBUNG DOSEN PEMBIMBING PNJ SUMMARY -->
<div class="tab-panel hidden flex flex-col space-y-5" id="tab-content-f4">
  <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-[10px] font-bold uppercase tracking-wider">FORM AKADEMIK F4</span>
        <span class="text-xs text-slate-500">Minimal 4 Bimbingan Berkala</span>
      </div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Rekam Jejak Konsultasi Dosen Pembimbing Jurusan TIK</h2>
      <p class="text-xs text-slate-500 mt-0.5">Dosen Pembimbing: Dr. Bambang Sutrisno, M.T. (NIP: 198503152010121002)</p>
    </div>
    <button type="button" onclick="showToast('Permintaan jadwal bimbingan ke-4 telah dikirimkan ke Dosen Pembimbing.')" class="h-10 px-4 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-sm">
      <span class="material-symbols-outlined text-[18px]">add_comment</span>
      <span>+ Ajukan Bimbingan Ke-4</span>
    </button>
  </div>

  <div class="space-y-4">
    <!-- Sesi 1 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] font-bold text-base flex items-center justify-center shrink-0">1</div>
      <div class="flex-1 space-y-1">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base">Konsultasi Bab I &amp; Penyesuaian Lingkup Form F2</h4>
          <span class="text-xs text-slate-400">25 Feb 2026 • Ruang Dosen TIK / Online Meet</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          Catatan Dosen: Latar belakang sudah selaras dengan portofolio Cloud Engineer di Telkomsel. Pastikan arsitektur sistem digambarkan dengan standar C4 model pada Bab II.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">check</span> ACC Bab I
          </span>
          <span class="text-xs font-mono text-slate-400">SHA256: 3a9f01bc7d88</span>
        </div>
      </div>
    </div>

    <!-- Sesi 2 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] font-bold text-base flex items-center justify-center shrink-0">2</div>
      <div class="flex-1 space-y-1">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base">Review Metodologi Pelaksanaan &amp; Verifikasi Logbook Sesi 1-20</h4>
          <span class="text-xs text-slate-400">18 Mar 2026 • Asinkron Portal SIM-PKL</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          Catatan Dosen: Uraian logbook harian sudah representatif dengan kompetensi DevOps. Lampiran log sistem harus disensor dari kredensial rahasia industri.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">check</span> Validasi Logbook Periode 1
          </span>
          <span class="text-xs font-mono text-slate-400">SHA256: b412fa099e21</span>
        </div>
      </div>
    </div>

    <!-- Sesi 3 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] font-bold text-base flex items-center justify-center shrink-0">3</div>
      <div class="flex-1 space-y-1">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base">Evaluasi Progres Penulisan Laporan Akhir (Bab III &amp; IV)</h4>
          <span class="text-xs text-slate-400">08 Apr 2026 • Lab Jaringan Gd. AA Lt. 2</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          Catatan Dosen: Bab IV (Pembahasan Proyek) perlu penambahan grafik performa latency sebelum vs setelah implementasi PgBouncer dan ArgoCD.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">check</span> Revisi Minor Bab IV
          </span>
          <span class="text-xs font-mono text-slate-400">SHA256: 77cf2010ea34</span>
        </div>
      </div>
    </div>

    <!-- Sesi 4 Pending -->
    <div class="bg-white p-5 rounded-2xl border border-dashed border-amber-300 bg-amber-50/20 shadow-sm flex flex-col md:flex-row gap-4 items-start">
      <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-800 font-bold text-base flex items-center justify-center shrink-0">4</div>
      <div class="flex-1 space-y-2">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base">Persetujuan Draft Akhir &amp; Rekomendasi Pendaftaran Sidang F7</h4>
          <span class="text-xs font-bold text-amber-800">Menunggu Pengajuan Mahasiswa</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500">
          Silakan ajukan konsultasi ke-4 dan lampirkan naskah draft bab lengkap setelah minimal 10 logbook industri divalidasi oleh dosen pembimbing.
        </p>
        <div>
          <button type="button" onclick="showToast('Silakan pilih jadwal konsultasi dengan Dr. Bambang Sutrisno.')" class="px-4 py-2 bg-[#0056B3] hover:bg-[#00428A] text-white rounded-xl text-xs font-bold transition-colors">
            Ajukan Jadwal Sesi 4
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- MODAL: ADD LOGBOOK (FORM F3) -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-add-log">
  <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <span class="material-symbols-outlined text-[#0056B3] text-[22px]">post_add</span>
        <h3 class="text-base font-bold font-heading text-slate-800">Input Logbook Harian (Form F3)</h3>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-slate-200/70 flex items-center justify-center text-slate-400 hover:text-slate-700" onclick="toggleModal('modal-add-log')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <!-- Form Submits to POST /logbooks -->
    <form action="{{ route('logbooks.store') }}" method="POST" class="p-6 space-y-4">
      @csrf

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kegiatan:</label>
          <input name="tanggal" required type="date" value="{{ date('Y-m-d') }}" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Dosen Pembimbing:</label>
          <input type="text" readonly value="Dr. Bambang Sutrisno, M.T." class="w-full h-10 px-3 bg-slate-100 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-600 cursor-not-allowed">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Subject / Judul Aktivitas Spesifik:</label>
        <input name="subject" required type="text" placeholder="Contoh: Implementasi autoscaling HPA pada ingress Traefik staging" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">
          Uraian Aktivitas &amp; Capaian Kerja (Long Text):
        </label>
        <textarea name="content" required rows="5" placeholder="Uraikan secara detail tugas yang dikerjakan, teknologi/tools yang digunakan, hasil yang dicapai, serta kendala teknis yang dihadapi di tempat magang..." class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
        <button type="button" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors" onclick="toggleModal('modal-add-log')">
          Batal
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold font-heading text-xs sm:text-sm transition-all shadow-md flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">send</span>
          <span>Simpan &amp; Kirim ke Dosen</span>
        </button>
      </div>
    </form>

  </div>
</div>

<!-- MODAL: PREVIEW STEMPEL DIGITAL VERIFIKASI -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-verification">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[#02796B] text-[24px]">verified</span>
        <h3 class="text-base font-bold font-heading text-slate-800">Stempel Digital Bukti Logbook</h3>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400" onclick="closeVerificationModal()">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <div class="p-6 space-y-4">
      <div class="rounded-xl p-4 bg-teal-50/60 border border-teal-200 relative">
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white border border-teal-200 shadow-sm flex items-center justify-center text-[#02796B]">
              <span class="material-symbols-outlined text-[24px]">token</span>
            </div>
            <div>
              <span class="text-[10px] text-[#02796B] font-bold uppercase tracking-wider block">Tervalidasi Digital • Jurusan TIK PNJ</span>
              <span class="text-sm font-bold text-slate-800">Portal SIM-PKL Politeknik Negeri Jakarta</span>
            </div>
          </div>
          <div class="w-12 h-12 rounded-full bg-teal-100 text-[#02796B] flex items-center justify-center font-mono text-[8px] font-bold uppercase text-center p-1 leading-tight">
            PNJ-TIK<br>VERIFIED
          </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
          <div>
            <span class="text-slate-400 block text-[11px]">Dosen Pembimbing / Penilai:</span>
            <span class="font-bold text-slate-800" id="modal-supervisor-name">Dr. Bambang Sutrisno, M.T.</span>
            <span class="text-[10px] text-slate-500 block font-mono" id="modal-supervisor-nrk">NIP: 198503152010121002</span>
          </div>
          <div>
            <span class="text-slate-400 block text-[11px]">Waktu Verifikasi:</span>
            <span class="font-bold text-slate-800" id="modal-verify-time">22 Okt 2026</span>
            <span class="text-[10px] text-emerald-600 block font-semibold">Status: Sah (Buku Saku F3)</span>
          </div>
        </div>

        <div class="mt-4 p-3 bg-white rounded-lg border border-teal-200/80">
          <div class="flex items-center justify-between text-xs text-slate-500 mb-1 font-mono">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">fingerprint</span> SHA-256 Checksum:</span>
            <button type="button" class="text-[#0056B3] hover:underline" onclick="copyHash()">Salin Hash</button>
          </div>
          <div class="font-mono text-[11px] text-slate-700 break-all select-all font-medium" id="modal-hash">
            e8f7a91c5d32b004fa87c9e1d88921a4bc5693072e2cf3891048bc8271a93d01
          </div>
        </div>
      </div>

      <p class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200">
        Verifikasi logbook ini sah diakui oleh Jurusan TIK PNJ untuk memenuhi persyaratan pendaftaran Sidang PKL (Form F7).
      </p>
    </div>

    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
      <span class="text-xs font-mono text-slate-400">UUID: PKL-TIK-2026-F3</span>
      <button type="button" class="px-4 py-2 rounded-xl bg-[#0056B3] text-white text-xs font-bold" onclick="closeVerificationModal()">
        Tutup Rincian
      </button>
    </div>
  </div>
</div>

<!-- MODAL: VIEW LOGBOOK DETAIL -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-view-logbook">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div>
        <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Detail Logbook Harian</span>
        <h3 id="detail-logbook-subject" class="text-base font-bold font-heading text-slate-800"></h3>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400" onclick="toggleModal('modal-view-logbook')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <div class="p-6 space-y-4">
      <div>
        <span class="text-xs text-slate-400 block">Tanggal Pelaksanaan:</span>
        <span id="detail-logbook-tanggal" class="text-xs font-bold text-slate-700"></span>
      </div>
      <div>
        <span class="text-xs text-slate-400 block mb-1">Uraian Aktivitas (Long Text):</span>
        <div id="detail-logbook-content" class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line max-h-56 overflow-y-auto"></div>
      </div>
      <div id="detail-feedback-box" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-[#0056B3]">
        <strong class="font-bold block">Catatan Dosen Pembimbing:</strong>
        <p id="detail-logbook-feedback" class="mt-0.5 text-slate-700"></p>
      </div>
    </div>
    <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end">
      <button type="button" onclick="toggleModal('modal-view-logbook')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold">
        Tutup
      </button>
    </div>
  </div>
</div>

<!-- MODAL: EDIT LOGBOOK -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-edit-log">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-amber-600 text-[22px]">edit_note</span>
        <h3 class="text-base font-bold font-heading text-slate-800">Ubah Logbook Harian</h3>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400" onclick="toggleModal('modal-edit-log')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-edit-log" method="POST" action="" class="p-6 space-y-4">
      @csrf
      @method('PUT')
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Aktivitas:</label>
        <input name="tanggal" id="edit-logbook-tanggal" required type="date" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Subject / Judul Aktivitas:</label>
        <input name="subject" id="edit-logbook-subject" required type="text" placeholder="Subject logbook..." class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas &amp; Capaian Kerja (Long Text):</label>
        <textarea name="content" id="edit-logbook-content" required rows="5" placeholder="Uraikan aktivitas harian Anda secara terperinci..." class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]"></textarea>
      </div>

      <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px] text-amber-600 shrink-0">info</span>
        <span>Perubahan logbook akan mengirimkan ulang data ke dosen pembimbing dengan status <strong>Menunggu Verifikasi</strong>.</span>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
        <button type="button" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors" onclick="toggleModal('modal-edit-log')">
          Batal
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#005f54] text-white font-bold font-heading text-xs sm:text-sm transition-all shadow-md flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">save</span>
          <span>Simpan &amp; Kirim Ulang</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: HAPUS LOGBOOK -->
<div class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-delete-log">
  <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-6 py-4 bg-rose-50 border-b border-rose-100 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-rose-600 text-[22px]">delete_forever</span>
        <h3 class="text-base font-bold font-heading text-rose-800">Hapus Logbook Harian</h3>
      </div>
      <button type="button" class="w-8 h-8 rounded-lg hover:bg-rose-100 flex items-center justify-center text-slate-400" onclick="toggleModal('modal-delete-log')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-delete-log" method="POST" action="" class="p-6 space-y-4">
      @csrf
      @method('DELETE')
      <p class="text-xs sm:text-sm text-slate-600">
        Apakah Anda yakin ingin menghapus logbook berikut? Tindakan ini tidak dapat dibatalkan.
      </p>

      <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800">
        <strong id="delete-logbook-subject" class="font-bold text-slate-900 block"></strong>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
        <button type="button" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors" onclick="toggleModal('modal-delete-log')">
          Batal
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold font-heading text-xs sm:text-sm transition-all shadow-md flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">delete</span>
          <span>Ya, Hapus</span>
        </button>
      </div>
    </form>
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

  function openVerificationModal(name, nrk, time, hash) {
    document.getElementById('modal-supervisor-name').innerText = name;
    document.getElementById('modal-supervisor-nrk').innerText = nrk;
    document.getElementById('modal-verify-time').innerText = time;
    document.getElementById('modal-hash').innerText = hash;
    document.getElementById('modal-verification').classList.remove('hidden');
  }

  function closeVerificationModal() {
    document.getElementById('modal-verification').classList.add('hidden');
  }

  function viewLogbookDetail(subject, tanggal, content, status, feedback) {
    document.getElementById('detail-logbook-subject').innerText = subject;
    document.getElementById('detail-logbook-tanggal').innerText = tanggal;
    document.getElementById('detail-logbook-content').innerText = content;
    
    const feedbackBox = document.getElementById('detail-feedback-box');
    if (feedback && feedback.trim() !== '') {
      document.getElementById('detail-logbook-feedback').innerText = feedback;
      feedbackBox.classList.remove('hidden');
    } else {
      feedbackBox.classList.add('hidden');
    }

    toggleModal('modal-view-logbook');
  }

  function openEditLogbookModal(id, subject, tanggal, content) {
    const form = document.getElementById('form-edit-log');
    form.action = `/logbooks/${id}`;
    document.getElementById('edit-logbook-tanggal').value = tanggal;
    document.getElementById('edit-logbook-subject').value = subject;
    document.getElementById('edit-logbook-content').value = content;
    toggleModal('modal-edit-log');
  }

  function openDeleteLogbookModal(id, subject) {
    const form = document.getElementById('form-delete-log');
    form.action = `/logbooks/${id}`;
    document.getElementById('delete-logbook-subject').innerText = subject;
    toggleModal('modal-delete-log');
  }

  function copyHash() {
    const hashText = document.getElementById('modal-hash').innerText;
    navigator.clipboard.writeText(hashText).then(() => {
      showToast('SHA-256 hash stempel digital berhasil disalin.');
    });
  }

  function downloadRekap() {
    showToast('Mengunduh Berkas Rekapitulasi Form F3 Logbook Mahasiswa (PDF)...');
  }

  function filterLogbookTable() {
    const query = document.getElementById('search-logbook').value.toLowerCase();
    const status = document.getElementById('filter-status').value;
    const rows = document.querySelectorAll('.logbook-row');

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      const rowStatus = row.getAttribute('data-status');

      const matchesQuery = text.includes(query);
      const matchesStatus = (status === 'all') || (rowStatus === status);

      row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
    });
  }
</script>
@endpush
