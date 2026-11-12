@extends('layouts.app', [
  'title' => 'Verifikasi Logbook (Form F3) - SIM-PKL Dosen',
  'pageTitle' => 'Verifikasi Logbook (Form F3)',
  'breadcrumbParent' => 'Portal Dosen',
  'activePage' => 'logbook'
])

@section('content')
@php
  $totalMentoredStudents = isset($students) ? $students->count() : 0;
  $allLogbooksCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->count()) : (isset($logbooks) ? $logbooks->count() : 0);
  $pendingCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->where('status', 'pending')->count()) : 0;
  $approvedCount = isset($students) ? $students->sum(fn($s) => $s->logbooks->where('status', 'approved')->count()) : 0;
@endphp

<!-- Ringkasan Statistik -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Mahasiswa</span>
      <span class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5 block">{{ $totalMentoredStudents }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">groups</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Total Logbook</span>
      <span class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5 block">{{ $allLogbooksCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">auto_stories</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Menunggu</span>
      <span class="text-2xl font-extrabold text-amber-600 font-heading mt-0.5 block">{{ $pendingCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">pending_actions</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-[11px] text-slate-400 font-bold block uppercase">Disetujui</span>
      <span class="text-2xl font-extrabold text-emerald-600 font-heading mt-0.5 block">{{ $approvedCount }}</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">verified</span>
    </div>
  </div>
</div>

@if(!$selectedStudent)
  <!-- Daftar Mahasiswa Bimbingan -->
  <div class="space-y-4">
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
      <h2 class="text-sm sm:text-base font-bold font-heading text-slate-900">Pilih Mahasiswa Bimbingan</h2>
      <div class="relative w-full sm:w-64">
        <input type="text" id="search-student" onkeyup="filterStudentCards()" placeholder="Cari nama atau NIM..." class="w-full h-9 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
        <span class="material-symbols-outlined absolute left-2.5 top-2 text-slate-400 text-[17px]">search</span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="student-cards-container">
      @forelse($students ?? [] as $st)
        @php
          $stTotal = $st->logbooks->count();
          $stPending = $st->logbooks->where('status', 'pending')->count();
          $stApproved = $st->logbooks->where('status', 'approved')->count();
          $companyName = $st->pengajuanMagangAktif?->industri?->nama_perusahaan ?? $st->company ?? 'Mitra Industri';
        @endphp
        <div class="student-card bg-white rounded-2xl border border-slate-200/90 hover:border-blue-300 transition-all p-5 flex flex-col justify-between space-y-4"
             data-name="{{ strtolower($st->name) }}"
             data-nim="{{ strtolower($st->identifier ?? '') }}">
          <div class="space-y-3">
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-sm shrink-0">
                  {{ strtoupper(substr($st->name, 0, 2)) }}
                </div>
                <div>
                  <h3 class="text-sm sm:text-base font-bold font-heading text-slate-900">{{ $st->name }}</h3>
                  <span class="text-xs font-mono text-[#0056B3] font-semibold">NIM: {{ $st->identifier ?? '-' }}</span>
                  <p class="text-[11px] text-slate-500 mt-0.5">{{ $companyName }}</p>
                </div>
              </div>

              @if($stPending > 0)
                <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold shrink-0">
                  {{ $stPending }} Menunggu
                </span>
              @else
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold shrink-0">
                  Up to Date
                </span>
              @endif
            </div>

            <div class="grid grid-cols-3 gap-2 text-center">
              <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Total</span>
                <span class="text-sm font-extrabold text-slate-800">{{ $stTotal }}</span>
              </div>
              <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                <span class="text-[10px] uppercase font-bold text-emerald-600 block">Disetujui</span>
                <span class="text-sm font-extrabold text-emerald-700">{{ $stApproved }}</span>
              </div>
              <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                <span class="text-[10px] uppercase font-bold text-amber-600 block">Pending</span>
                <span class="text-sm font-extrabold text-amber-700">{{ $stPending }}</span>
              </div>
            </div>
          </div>

          <a href="{{ route('logbook.dosen', ['student_id' => $st->id]) }}" class="w-full py-2 px-4 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
            <span>Buka Logbook ({{ $stTotal }})</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
          </a>
        </div>
      @empty
        <div class="col-span-2 p-8 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
          Belum ada mahasiswa bimbingan.
        </div>
      @endforelse
    </div>
  </div>
@else
  <!-- Daftar Logbook Mahasiswa Terpilih -->
  <div class="space-y-4">
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <a href="{{ route('logbook.dosen') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Kembali">
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        </a>
        <div>
          <h2 class="text-base sm:text-lg font-bold font-heading text-slate-900">{{ $selectedStudent->name }}</h2>
          <span class="text-xs text-slate-500 font-mono">NIM: {{ $selectedStudent->identifier ?? '-' }}</span>
        </div>
      </div>

      <div class="flex items-center gap-2.5">
        <select id="filter-status" onchange="filterSelectedStudentLogbooks()" class="h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 font-semibold">
          <option value="all">Semua ({{ $logbooks->count() }})</option>
          <option value="pending">Menunggu ({{ $logbooks->where('status', 'pending')->count() }})</option>
          <option value="approved">Disetujui ({{ $logbooks->where('status', 'approved')->count() }})</option>
          <option value="revision">Revisi ({{ $logbooks->where('status', 'revision')->count() }})</option>
        </select>

        <input id="search-logbook" onkeyup="filterSelectedStudentLogbooks()" class="h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700" placeholder="Cari logbook..." type="text">
      </div>
    </div>

    <div class="space-y-3" id="logbooks-feed">
      @forelse($logbooks ?? [] as $lb)
        <div class="dosen-logbook-card p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 space-y-3" data-status="{{ $lb->status }}">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2.5 border-b border-slate-100">
            <div>
              <span class="text-xs font-mono text-[#0056B3] font-semibold">{{ $lb->tanggal->translatedFormat('l, d M Y') }}</span>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 mt-0.5">{{ $lb->subject }}</h3>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
              @if($lb->status === 'approved')
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">Disetujui</span>
              @elseif($lb->status === 'revision')
                <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold">Revisi</span>
              @else
                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold">Menunggu</span>
              @endif

              <button type="button" 
                      onclick="openDosenReviewModal({{ $lb->id }}, '{{ addslashes($selectedStudent->name) }}', '{{ addslashes($selectedStudent->identifier ?? '') }}', '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('l, d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}')"
                      class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3.5 py-1.5 rounded-xl text-xs font-bold">
                Review
              </button>
            </div>
          </div>

          <p class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 text-xs text-slate-700 whitespace-pre-line">{{ $lb->content }}</p>

          @if($lb->feedback)
            <div class="p-2.5 bg-blue-50/80 border border-blue-200/80 rounded-xl text-xs text-[#0056B3]">
              <strong>Catatan Dosen:</strong> <span class="text-slate-700">{{ $lb->feedback }}</span>
            </div>
          @endif
        </div>
      @empty
        <div class="p-8 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
          Mahasiswa ini belum memiliki catatan logbook.
        </div>
      @endforelse
    </div>
  </div>
@endif

<!-- MODAL: Review Logbook -->
<div class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" id="modal-dosen-review">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up">
    <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h3 id="modal-student-name" class="text-sm sm:text-base font-bold font-heading text-slate-800">Review Logbook</h3>
        <p id="modal-student-sub" class="text-xs text-slate-500"></p>
      </div>
      <button type="button" class="text-slate-400 hover:text-slate-700" onclick="toggleModal('modal-dosen-review')">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <div class="p-5 space-y-3.5">
      <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl">
        <div class="flex items-center justify-between text-xs mb-0.5">
          <span class="font-bold text-[#0056B3]">Topik Kegiatan</span>
          <span id="modal-logbook-tanggal" class="font-mono text-slate-600"></span>
        </div>
        <h4 id="modal-logbook-subject" class="text-sm font-bold text-slate-900"></h4>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas:</label>
        <div id="modal-logbook-content" class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-800 whitespace-pre-line max-h-56 overflow-y-auto"></div>
      </div>

      <form id="form-approve-logbook" method="POST" action="" class="space-y-3">
        @csrf
        <input type="hidden" name="status" id="review-status-input" value="approved">

        <div>
          <label for="review-feedback" class="block text-xs font-bold text-slate-700 mb-1">Catatan Dosen:</label>
          <textarea id="review-feedback" name="feedback" rows="3" placeholder="Tuliskan catatan arahan atau revisi..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
          <button type="button" onclick="toggleModal('modal-dosen-review')" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Tutup</button>
          <button type="button" onclick="submitDosenReview('revision')" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold">Minta Revisi</button>
          <button type="button" onclick="submitDosenReview('approved')" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs font-bold">Setujui</button>
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
      const isHidden = modal.classList.toggle('hidden');
      if (isHidden) modal.classList.remove('flex');
      else modal.classList.add('flex');
    }
  }

  function openDosenReviewModal(id, studentName, studentNim, subject, tanggal, content, status, feedback) {
    document.getElementById('modal-student-name').innerText = `Review Logbook: ${studentName}`;
    document.getElementById('modal-student-sub').innerText = `NIM: ${studentNim}`;
    document.getElementById('modal-logbook-subject').innerText = subject;
    document.getElementById('modal-logbook-tanggal').innerText = tanggal;
    document.getElementById('modal-logbook-content').innerText = content;
    document.getElementById('form-approve-logbook').action = `/logbooks/${id}/status`;
    document.getElementById('review-feedback').value = (feedback && feedback.trim() !== '') ? feedback : '';
    toggleModal('modal-dosen-review');
  }

  function submitDosenReview(status) {
    const feedbackInput = document.getElementById('review-feedback');
    const feedback = feedbackInput.value.trim();
    if (status === 'revision' && (!feedback || feedback.length < 5)) {
      alert('Harap tuliskan catatan revisi (minimal 5 karakter).');
      feedbackInput.focus();
      return;
    }
    document.getElementById('review-status-input').value = status;
    document.getElementById('form-approve-logbook').submit();
  }

  function filterStudentCards() {
    const query = (document.getElementById('search-student')?.value || '').toLowerCase();
    document.querySelectorAll('.student-card').forEach(card => {
      const match = card.innerText.toLowerCase().includes(query);
      card.style.display = match ? '' : 'none';
    });
  }

  function filterSelectedStudentLogbooks() {
    const query = (document.getElementById('search-logbook')?.value || '').toLowerCase();
    const statusFilter = document.getElementById('filter-status')?.value || 'all';
    document.querySelectorAll('.dosen-logbook-card').forEach(card => {
      const matchesQuery = card.innerText.toLowerCase().includes(query);
      const matchesStatus = (statusFilter === 'all') || (card.getAttribute('data-status') === statusFilter);
      card.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
    });
  }
</script>
@endpush
