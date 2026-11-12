@extends('layouts.app', [
  'title' => 'Logbook Harian (Form F3) - SIM-PKL JTIK PNJ',
  'pageTitle' => 'Logbook Harian (Form F3)',
  'breadcrumbParent' => 'Portal Mahasiswa',
  'activePage' => 'logbook'
])

@section('content')
@php
  $totalLogbooksCount = isset($logbooks) ? $logbooks->count() : 0;
  $approvedLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'approved')->count() : 0;
  $pendingLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0;
  $revisionLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'revision')->count() : 0;
  $pengajuan = Auth::user()->pengajuanMagangAktif;
@endphp

<!-- Ringkasan Statistik Logbook -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Total Logbook</span>
      <span class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5 block">{{ $totalLogbooksCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">menu_book</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Disetujui</span>
      <span class="text-2xl font-extrabold text-emerald-600 font-heading mt-0.5 block">{{ $approvedLogbooksCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">verified</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Menunggu</span>
      <span class="text-2xl font-extrabold text-amber-600 font-heading mt-0.5 block">{{ $pendingLogbooksCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">pending_actions</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Revisi</span>
      <span class="text-2xl font-extrabold text-rose-600 font-heading mt-0.5 block">{{ $revisionLogbooksCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">edit_note</span>
    </div>
  </div>
</div>

<!-- Toolbar & Tabel Logbook -->
<div class="space-y-4">
  <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2.5">
      <select id="filter-status" onchange="filterLogbookTable()" class="h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 font-semibold focus:outline-none">
        <option value="all">Semua Status ({{ $totalLogbooksCount }})</option>
        <option value="pending">Menunggu ({{ $pendingLogbooksCount }})</option>
        <option value="approved">Disetujui ({{ $approvedLogbooksCount }})</option>
        <option value="revision">Revisi ({{ $revisionLogbooksCount }})</option>
      </select>

      <div class="relative flex-1 md:w-64">
        <input id="search-logbook" onkeyup="filterLogbookTable()" class="w-full h-9 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-[#0056B3]" placeholder="Cari topik atau uraian..." type="text">
        <span class="material-symbols-outlined absolute left-2.5 top-2 text-slate-400 text-[17px]">search</span>
      </div>
    </div>

    <div class="flex items-center gap-2">
      @if($pengajuan)
        <a href="{{ route('dokumen.f3', $pengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">print</span>
          <span>Cetak F3</span>
        </a>
      @endif
      <button type="button" class="h-9 px-4 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs flex items-center gap-1.5 shadow-sm" onclick="toggleModal('modal-add-log')">
        <span class="material-symbols-outlined text-[16px]">add</span>
        <span>Tambah Logbook</span>
      </button>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse" id="table-logbooks">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
            <th class="py-3 px-4 w-12 text-center">No</th>
            <th class="py-3 px-4 w-40">Tanggal</th>
            <th class="py-3 px-4">Topik &amp; Uraian Aktivitas</th>
            <th class="py-3 px-4 w-44">Status</th>
            <th class="py-3 px-4 w-28 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @php $rowNum = 1; @endphp
          @forelse($logbooks ?? [] as $lb)
            <tr class="logbook-row hover:bg-slate-50/70 transition-colors" data-status="{{ $lb->status }}">
              <td class="py-3.5 px-4 text-center font-bold text-slate-400 font-mono">{{ sprintf('%02d', $rowNum++) }}</td>
              
              <td class="py-3.5 px-4">
                <span class="font-bold text-slate-800 block">{{ $lb->tanggal->translatedFormat('d M Y') }}</span>
                <span class="text-[11px] text-slate-400">{{ $lb->tanggal->translatedFormat('l') }}</span>
              </td>

              <td class="py-3.5 px-4">
                <div class="space-y-1">
                  <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $lb->subject }}</h4>
                  <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $lb->content }}</p>

                  @if($lb->status === 'revision')
                    <div class="p-2.5 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800 flex items-center justify-between gap-2 mt-1">
                      <div>
                        <strong>Catatan Revisi Dosen:</strong> {{ $lb->feedback ?? 'Perbaiki sesuai arahan dosen.' }}
                      </div>
                      <button type="button" onclick="openEditLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->format('Y-m-d') }}', {{ json_encode($lb->content) }})" class="px-2.5 py-1 bg-rose-600 text-white rounded text-[11px] font-bold shrink-0">
                        Perbaiki
                      </button>
                    </div>
                  @elseif($lb->feedback)
                    <div class="p-2 bg-blue-50/80 border border-blue-200/80 rounded-lg text-xs text-[#0056B3] mt-1">
                      <strong>Catatan Dosen:</strong> <span class="text-slate-700">{{ $lb->feedback }}</span>
                    </div>
                  @endif

                  @if($lb->status_industri === 'revisi')
                    <div class="p-2.5 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800 flex items-center justify-between gap-2 mt-1">
                      <div>
                        <strong>Catatan Revisi DUDI:</strong> {{ $lb->catatan_industri ?? 'Perbaiki sesuai arahan pembimbing industri.' }}
                      </div>
                      <button type="button" onclick="openEditLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->format('Y-m-d') }}', {{ json_encode($lb->content) }})" class="px-2.5 py-1 bg-rose-600 text-white rounded text-[11px] font-bold shrink-0">
                        Perbaiki
                      </button>
                    </div>
                  @elseif($lb->catatan_industri)
                    <div class="p-2 bg-teal-50/80 border border-teal-200/80 rounded-lg text-xs text-[#02796B] mt-1">
                      <strong>Catatan DUDI:</strong> <span class="text-slate-700">{{ $lb->catatan_industri }}</span>
                    </div>
                  @endif
                </div>
              </td>

              <td class="py-3.5 px-4">
                <div class="flex flex-col items-start gap-1">
                  @if($lb->status === 'approved')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                      <span class="material-symbols-outlined text-[13px]">check_circle</span>
                      <span>Disetujui</span>
                    </span>
                  @elseif($lb->status === 'revision')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold">
                      <span class="material-symbols-outlined text-[13px]">error</span>
                      <span>Revisi</span>
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold">
                      <span class="material-symbols-outlined text-[13px]">hourglass_top</span>
                      <span>Menunggu</span>
                    </span>
                  @endif

                  @if($lb->status_industri === 'acc')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-[#02796B] border border-teal-200">
                      ACC DUDI
                    </span>
                  @elseif($lb->status_industri === 'revisi')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                      Revisi DUDI
                    </span>
                  @endif
                </div>
              </td>

              <td class="py-3.5 px-4 text-right">
                <div class="flex items-center justify-end gap-1">
                  <button type="button" onclick="viewLogbookDetail('{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}')" class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-[#0056B3]" title="Detail">
                    <span class="material-symbols-outlined text-[17px]">visibility</span>
                  </button>

                  @if($lb->status !== 'approved')
                    <button type="button" onclick="openEditLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->format('Y-m-d') }}', {{ json_encode($lb->content) }})" class="p-1.5 rounded-lg hover:bg-amber-50 text-slate-500 hover:text-amber-700" title="Edit">
                      <span class="material-symbols-outlined text-[17px]">edit</span>
                    </button>
                    <button type="button" onclick="openDeleteLogbookModal({{ $lb->id }}, '{{ addslashes($lb->subject) }}')" class="p-1.5 rounded-lg hover:bg-rose-50 text-slate-500 hover:text-rose-600" title="Hapus">
                      <span class="material-symbols-outlined text-[17px]">delete</span>
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-10 px-4 text-center text-slate-400">
                Belum ada catatan logbook harian.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL: Tambah Logbook -->
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-add-log">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">Tambah Logbook Harian</h3>
      <button type="button" class="text-slate-400 hover:text-slate-700" onclick="toggleModal('modal-add-log')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form action="{{ route('logbooks.store') }}" method="POST" class="p-5 space-y-3.5">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kegiatan</label>
        <input name="tanggal" required type="date" value="{{ date('Y-m-d') }}" class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Topik Kegiatan</label>
        <input name="subject" required type="text" placeholder="Contoh: Pengujian endpoint REST API" class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas</label>
        <textarea name="content" required rows="4" placeholder="Tuliskan detail pekerjaan dan capaian hari ini..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100" onclick="toggleModal('modal-add-log')">Batal</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Detail Logbook -->
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-view-logbook">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <h3 id="detail-logbook-subject" class="text-sm sm:text-base font-bold font-heading text-slate-800"></h3>
      <button type="button" class="text-slate-400 hover:text-slate-700" onclick="toggleModal('modal-view-logbook')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <div class="p-5 space-y-3">
      <div>
        <span class="text-[11px] text-slate-400 block">Tanggal</span>
        <span id="detail-logbook-tanggal" class="text-xs font-bold text-slate-700"></span>
      </div>
      <div>
        <span class="text-[11px] text-slate-400 block mb-1">Uraian Aktivitas</span>
        <div id="detail-logbook-content" class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 whitespace-pre-line max-h-56 overflow-y-auto"></div>
      </div>
      <div id="detail-feedback-box" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-[#0056B3]">
        <strong class="font-bold block">Catatan Dosen:</strong>
        <p id="detail-logbook-feedback" class="mt-0.5 text-slate-700"></p>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Edit Logbook -->
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-edit-log">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">Edit Logbook Harian</h3>
      <button type="button" class="text-slate-400 hover:text-slate-700" onclick="toggleModal('modal-edit-log')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-edit-log" method="POST" action="" class="p-5 space-y-3.5">
      @csrf
      @method('PUT')
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal</label>
        <input name="tanggal" id="edit-logbook-tanggal" required type="date" class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Topik Kegiatan</label>
        <input name="subject" id="edit-logbook-subject" required type="text" class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas</label>
        <textarea name="content" id="edit-logbook-content" required rows="4" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100" onclick="toggleModal('modal-edit-log')">Batal</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-[#0056B3] text-white font-bold text-xs">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Hapus Logbook -->
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-delete-log">
  <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-5 py-4 bg-rose-50 border-b border-rose-100 flex items-center justify-between">
      <h3 class="text-sm font-bold text-rose-800">Hapus Logbook</h3>
      <button type="button" class="text-slate-400" onclick="toggleModal('modal-delete-log')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-delete-log" method="POST" action="" class="p-5 space-y-3.5">
      @csrf
      @method('DELETE')
      <p class="text-xs text-slate-600">Hapus logbook <strong id="delete-logbook-subject" class="text-slate-900"></strong>?</p>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100" onclick="toggleModal('modal-delete-log')">Batal</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold text-xs">Hapus</button>
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
      if (isHidden) modal.classList.remove('flex');
      else modal.classList.add('flex');
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
    document.getElementById('form-edit-log').action = `/logbooks/${id}`;
    document.getElementById('edit-logbook-tanggal').value = tanggal;
    document.getElementById('edit-logbook-subject').value = subject;
    document.getElementById('edit-logbook-content').value = content;
    toggleModal('modal-edit-log');
  }

  function openDeleteLogbookModal(id, subject) {
    document.getElementById('form-delete-log').action = `/logbooks/${id}`;
    document.getElementById('delete-logbook-subject').innerText = subject;
    toggleModal('modal-delete-log');
  }

  function filterLogbookTable() {
    const query = document.getElementById('search-logbook').value.toLowerCase();
    const status = document.getElementById('filter-status').value;
    document.querySelectorAll('.logbook-row').forEach(row => {
      const text = row.innerText.toLowerCase();
      const rowStatus = row.getAttribute('data-status');
      const matchesQuery = text.includes(query);
      const matchesStatus = (status === 'all') || (rowStatus === status);
      row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
    });
  }
</script>
@endpush
