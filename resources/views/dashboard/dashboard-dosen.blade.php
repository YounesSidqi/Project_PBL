@extends('layouts.app', [
  'title' => 'Dashboard Dosen Pembimbing - SIM-PKL JTIK PNJ',
  'pageTitle' => 'Mahasiswa Bimbingan',
  'breadcrumbParent' => 'Portal Dosen',
  'activePage' => 'dashboard'
])

@section('content')
@php
  $isRealDosen = (Auth::user()->role ?? '') === 'dosen';
  $lecturerName = $isRealDosen ? Auth::user()->name : ($dosen->name ?? 'Dosen Pembimbing');
  $lecturerNip = $isRealDosen ? (Auth::user()->identifier ?? '-') : ($dosen->identifier ?? '-');
  $studentCount = isset($students) ? $students->count() : 0;
  $remainingCapacity = max(0, 7 - $studentCount);
  $pendingLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0;
@endphp

<!-- Banner Profil & Kuota Dosen -->
<div class="rounded-2xl bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white p-5 sm:p-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-5">
  <div class="flex items-center gap-4">
    <div class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-white shrink-0">
      <span class="material-symbols-outlined text-[32px]">school</span>
    </div>
    <div>
      <div class="flex items-center gap-2 flex-wrap">
        <h1 class="text-lg sm:text-xl font-extrabold font-heading text-white">{{ $lecturerName }}</h1>
        <span class="px-2 py-0.5 rounded bg-white/15 text-blue-50 text-xs font-mono">NIP: {{ $lecturerNip }}</span>
      </div>
      <p class="text-blue-100 text-xs mt-1">
        Penetapan Mahasiswa Bimbingan Dikelola Terpusat oleh KPS
      </p>
    </div>
  </div>

  <!-- Kuota 7 Mahasiswa -->
  <div class="bg-white/10 border border-white/20 rounded-xl p-4 w-full lg:w-80">
    <div class="flex items-center justify-between text-xs mb-1.5">
      <span class="font-bold text-blue-100">Kuota Bimbingan</span>
      <span class="font-bold text-white">{{ $studentCount }} / 7 Mahasiswa ({{ $remainingCapacity }} Tersedia)</span>
    </div>
    <div class="grid grid-cols-7 gap-1.5">
      @for($i = 1; $i <= 7; $i++)
        <div class="h-2 rounded-full {{ $i <= $studentCount ? 'bg-emerald-400' : 'bg-white/25' }}"></div>
      @endfor
    </div>
  </div>
</div>

<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-card flex items-center justify-between">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Mahasiswa Bimbingan</span>
      <div class="text-2xl font-extrabold text-[#0056B3] font-heading mt-0.5">{{ $studentCount }} <span class="text-xs font-semibold text-slate-400">/ 7 Maks</span></div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">groups</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-card flex items-center justify-between">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Logbook Menunggu Review</span>
      <div class="text-2xl font-extrabold text-amber-600 font-heading mt-0.5">{{ $pendingLogbooksCount }} <span class="text-xs font-semibold text-slate-400">Jurnal</span></div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">rate_review</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-card flex items-center justify-between">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Sisa Kuota</span>
      <div class="text-2xl font-extrabold text-[#02796B] font-heading mt-0.5">{{ $remainingCapacity }} <span class="text-xs font-semibold text-slate-400">Slot</span></div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">pie_chart</span>
    </div>
  </div>
</div>

<!-- Daftar Mahasiswa Bimbingan -->
<div id="mahasiswa-section" class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
    <div>
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800">Daftar Mahasiswa Bimbingan ({{ $studentCount }}/7)</h2>
    </div>

    <div class="flex items-center gap-2.5 flex-wrap">
      <div class="relative flex-1 sm:w-56">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[17px]">search</span>
        <input type="text" id="student-search" onkeyup="filterStudents()" placeholder="Cari nama atau NIM..." class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0056B3]">
      </div>

      <select id="status-filter" onchange="filterStudents()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none">
        <option value="all">Semua ({{ $studentCount }})</option>
        <option value="review">Perlu Review</option>
        <option value="uptodate">Up to Date</option>
      </select>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-3" id="student-list-container">
    @forelse($students ?? [] as $index => $st)
      @php
        $initials = collect(explode(' ', $st->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
        $stLogbooksCount = $st->logbooks ? $st->logbooks->count() : 0;
        $stPendingCount = $st->logbooks ? $st->logbooks->where('status', 'pending')->count() : 0;
        $pm = $st->pengajuanMagangAktif;
        $companyName = $pm?->industri?->nama_perusahaan ?? $st->company ?? 'Belum Ada Tempat PKL';
      @endphp
      <div class="student-card p-4 rounded-xl bg-white border border-slate-200 hover:border-[#0056B3]/40 transition-all" data-status="{{ $stPendingCount > 0 ? 'review' : 'uptodate' }}" data-name="{{ strtolower($st->name . ' ' . $st->identifier) }}">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <div class="flex items-center gap-3.5 min-w-0">
            <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-xs shrink-0">
              {{ $initials }}
            </div>
            <div class="min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">{{ $st->name }}</h3>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-[11px] font-mono font-semibold">{{ $st->identifier }}</span>
                @if($stPendingCount > 0)
                  <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">{{ $stPendingCount }} Perlu Review</span>
                @else
                  <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">Logbook Aman</span>
                @endif
              </div>
              <p class="text-xs text-slate-500 mt-0.5 truncate">
                {{ $companyName }} • {{ $st->email }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2 shrink-0 justify-end flex-wrap">
            <span class="text-xs font-mono text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200">
              {{ $stLogbooksCount }} Jurnal
            </span>
            <a href="{{ route('logbook.dosen', ['student_id' => $st->id]) }}" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
              <span class="material-symbols-outlined text-[15px]">rate_review</span>
              <span>Logbook (F3)</span>
            </a>
            <a href="{{ route('form.f4.dosen', $pm ? ['pengajuan_id' => $pm->id] : []) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl text-xs font-bold transition-colors">
              Bimbingan (F4)
            </a>
            <a href="{{ route('form.f6.dosen', $pm ? ['pengajuan_id' => $pm->id] : []) }}" class="bg-teal-50 hover:bg-teal-100 text-[#02796B] border border-teal-200 px-3 py-2 rounded-xl text-xs font-bold transition-colors">
              Evaluasi (F6)
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="p-6 text-center bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-500">
        Belum ada mahasiswa bimbingan yang ditetapkan oleh KPS.
      </div>
    @endforelse
  </div>
</div>
@endsection

@push('scripts')
<script>
  function filterStudents() {
    const query = (document.getElementById('student-search')?.value || '').toLowerCase();
    const statusFilter = document.getElementById('status-filter')?.value || 'all';
    document.querySelectorAll('.student-card').forEach(card => {
      const nameAttr = (card.getAttribute('data-name') || '').toLowerCase();
      const statusAttr = (card.getAttribute('data-status') || '');
      const matchesQuery = nameAttr.includes(query);
      const matchesStatus = (statusFilter === 'all') || (statusAttr === statusFilter);
      card.style.display = (matchesQuery && matchesStatus) ? 'block' : 'none';
    });
  }
</script>
@endpush
