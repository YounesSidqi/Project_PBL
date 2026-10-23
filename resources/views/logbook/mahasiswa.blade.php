@extends('layouts.app', [
  'title' => 'Form F3: Logbook Harian Mahasiswa - SIM-PKL',
  'pageTitle' => 'Form F3: Logbook Harian',
  'breadcrumbParent' => 'Portal Mahasiswa TIK',
  'activePage' => 'logbook'
])

@section('content')
<!-- Sub-header Breadcrumb & Institutional Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Mahasiswa</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Buku Jurnal PKL</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Form F3 (Logbook Harian)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F3: Buku Penghubung Industri (Logbook Harian)
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Catatan aktivitas dan jurnal harian Praktik Kerja Lapangan. Diisi berkala setiap hari kerja selama masa magang tanpa batasan kuota.
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

<!-- 1. Top Metrics Bar: Logbook Harian (Tanpa Batasan Kuota / Bebas Diisi Setiap Hari) -->
@php
  $totalLogbooksCount = isset($logbooks) ? $logbooks->count() : 0;
  $approvedLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'approved')->count() : 0;
  $pendingLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0;
  $revisionLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'revision')->count() : 0;
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Total Logbook Diisi</span>
      <span class="text-2xl font-extrabold text-slate-900 font-heading mt-1 block">{{ $totalLogbooksCount }} Jurnal</span>
      <span class="text-[11px] text-[#0056B3] font-medium">Diisi berkala setiap hari kerja</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">menu_book</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Telah Disetujui</span>
      <span class="text-2xl font-extrabold text-emerald-600 font-heading mt-1 block">{{ $approvedLogbooksCount }} Jurnal</span>
      <span class="text-[11px] text-emerald-600 font-medium">Validasi stempel digital dosen</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">verified</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Menunggu Validasi</span>
      <span class="text-2xl font-extrabold text-amber-600 font-heading mt-1 block">{{ $pendingLogbooksCount }} Jurnal</span>
      <span class="text-[11px] text-amber-700 font-medium">Dalam antrean telaah dosen</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">pending_actions</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Perlu Revisi</span>
      <span class="text-2xl font-extrabold text-rose-600 font-heading mt-1 block">{{ $revisionLogbooksCount }} Jurnal</span>
      <span class="text-[11px] text-rose-600 font-medium">Periksa catatan arahan dosen</span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[24px]">edit_note</span>
    </div>
  </div>
</div>

<!-- 2. KONTEN LOGBOOK HARIAN MAHASISWA -->
<div class="flex flex-col space-y-5">
  
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
                            onclick="openVerificationModal('{{ $lb->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}', '{{ $lb->dosen->identifier ?? '198503152010121002' }}', '{{ $lb->updated_at ? $lb->updated_at->translatedFormat('d M Y H:i') . ' WIB' : now()->translatedFormat('d M Y H:i') . ' WIB' }}', 'SHA256: {{ substr(hash('sha256', $lb->id . $lb->subject), 0, 32) }}...')" 
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





<!-- MODAL: ADD LOGBOOK (FORM F3) -->
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-add-log">
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
          <input type="text" readonly value="{{ Auth::user()->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}" class="w-full h-10 px-3 bg-slate-100 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-600 cursor-not-allowed">
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
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-verification">
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
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-view-logbook">
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
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-edit-log">
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
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-delete-log">
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

  function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
      const isHidden = modal.classList.toggle('hidden');
      if (isHidden) {
        modal.classList.remove('flex');
      } else {
        modal.classList.add('flex');
      }
    }
  }

  function openVerificationModal(name, nrk, time, hash) {
    document.getElementById('modal-supervisor-name').innerText = name;
    document.getElementById('modal-supervisor-nrk').innerText = nrk;
    document.getElementById('modal-verify-time').innerText = time;
    document.getElementById('modal-hash').innerText = hash;
    const modal = document.getElementById('modal-verification');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  }

  function closeVerificationModal() {
    const modal = document.getElementById('modal-verification');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
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
