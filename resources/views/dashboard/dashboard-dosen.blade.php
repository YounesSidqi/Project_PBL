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
  $percentFilled = round(($studentCount / 7) * 100);
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
        $slotNum = $index + 1;
        $initials = collect(explode(' ', $st->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
        $stLogbooksCount = $st->logbooks ? $st->logbooks->count() : 0;
        $stPendingCount = $st->logbooks ? $st->logbooks->where('status', 'pending')->count() : 0;
        $companyName = $st->pengajuanMagangAktif?->industri?->nama_perusahaan ?? $st->company ?? 'Belum Ada Tempat PKL';
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

          <div class="flex items-center gap-2.5 shrink-0 justify-end">
            <span class="text-xs font-mono text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200">
              {{ $stLogbooksCount }} Jurnal
            </span>
            <a href="{{ route('logbook.dosen', ['student_id' => $st->id]) }}" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
              <span class="material-symbols-outlined text-[15px]">rate_review</span>
              <span>Logbook</span>
            </a>
            <a href="{{ route('form.f4.dosen') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl text-xs font-bold transition-colors">
              F4 / F6
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

<!-- Evaluasi F6, Bimbingan F4, & Kalkulasi Fuzzy Logic -->
@php
  $activeSt = $selectedStudent ?? ($students ? $students->first() : null);
  $activePengajuan = $activeSt?->pengajuanMagangAktif;
  $bimbingansCount = $activePengajuan?->bimbingans?->count() ?? 0;
  $evalF5 = $activePengajuan?->evaluasiF5;
  $evalF6 = $activePengajuan?->evaluasiF6;
@endphp

@if($activeSt && $activePengajuan)
<div id="penilaian-fuzzy-section" class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
    <div>
      <span class="text-[10px] font-bold uppercase tracking-wider text-[#0056B3]">Bimbingan &amp; Evaluasi Akhir</span>
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800">
        {{ $activeSt->name }} ({{ $activeSt->identifier }})
      </h2>
    </div>

    @if(count($students ?? []) > 1)
    <form method="GET" action="{{ route('dashboard.dosen') }}#penilaian-fuzzy-section" class="flex items-center gap-2">
      <select name="student_id" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border border-slate-200 py-1.5 px-3 text-slate-700 bg-slate-50">
        @foreach($students as $st)
          <option value="{{ $st->id }}" {{ $activeSt->id === $st->id ? 'selected' : '' }}>
            {{ $st->name }} ({{ $st->identifier }})
          </option>
        @endforeach
      </select>
    </form>
    @endif
  </div>

  <!-- 3 Status Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div class="p-3.5 rounded-xl border {{ $bimbingansCount >= 8 ? 'bg-emerald-50/50 border-emerald-200' : 'bg-amber-50/50 border-amber-200' }}">
      <span class="text-[10px] font-bold uppercase {{ $bimbingansCount >= 8 ? 'text-emerald-800' : 'text-amber-800' }}">Bimbingan F4</span>
      <div class="text-xl font-black font-heading mt-1 {{ $bimbingansCount >= 8 ? 'text-emerald-900' : 'text-amber-900' }}">
        {{ $bimbingansCount }} / 8 Sesi
      </div>
    </div>

    <div class="p-3.5 rounded-xl border {{ $evalF5 ? 'bg-teal-50/50 border-teal-200' : 'bg-slate-50 border-slate-200' }}">
      <span class="text-[10px] font-bold uppercase text-slate-600">Nilai Industri F5 (60%)</span>
      <div class="text-xl font-black font-heading mt-1 {{ $evalF5 ? 'text-[#02796B]' : 'text-slate-400' }}">
        {{ $evalF5 ? $evalF5->rata_rata_f5 . '/100' : 'Belum Diisi' }}
      </div>
    </div>

    <div class="p-3.5 rounded-xl border {{ $activePengajuan->nilai_akhir_fuzzy ? 'bg-blue-50/60 border-blue-200' : 'bg-slate-50 border-slate-200' }}">
      <span class="text-[10px] font-bold uppercase text-[#0056B3]">Nilai Akhir Fuzzy</span>
      <div class="text-xl font-black font-heading mt-1 font-mono text-[#0056B3]">
        {{ $activePengajuan->nilai_akhir_fuzzy ?? 'Belum Dikalkulasi' }}
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <!-- Catat Sesi Bimbingan F4 -->
    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="text-xs sm:text-sm font-bold font-heading text-slate-800">Catat Sesi Bimbingan (Form F4)</h3>
        <a href="{{ route('dokumen.f4', $activePengajuan->id) }}" target="_blank" class="text-xs text-[#0056B3] font-bold hover:underline">Cetak F4</a>
      </div>

      <form method="POST" action="{{ route('bimbingan_f4.store', $activePengajuan->id) }}" class="space-y-2.5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
          <div>
            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Tanggal</label>
            <input type="date" name="tanggal_bimbingan" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border border-slate-200 p-2 bg-white">
          </div>
          <div>
            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Progres Laporan</label>
            <input type="text" name="progres_laporan" placeholder="Bab III Metodologi" class="w-full text-xs rounded-lg border border-slate-200 p-2 bg-white">
          </div>
        </div>

        <div>
          <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Materi Bimbingan</label>
          <textarea name="materi_bimbingan" rows="2" required placeholder="Pokok bahasan bimbingan..." class="w-full text-xs rounded-lg border border-slate-200 p-2 bg-white"></textarea>
        </div>

        <div>
          <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Catatan Dosen</label>
          <input type="text" name="catatan_dosen" placeholder="Arahan lanjutan..." class="w-full text-xs rounded-lg border border-slate-200 p-2 bg-white">
        </div>

        <div class="flex justify-end pt-1">
          <button type="submit" class="px-4 py-2 rounded-xl bg-[#0056B3] hover:bg-blue-700 text-white font-bold text-xs">
            Simpan Sesi Ke-{{ $bimbingansCount + 1 }}
          </button>
        </div>
      </form>
    </div>

    <!-- Evaluasi F6 & Trigger Fuzzy -->
    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="text-xs sm:text-sm font-bold font-heading text-slate-800">Evaluasi Akademik (Form F6 - 40%)</h3>
        @if($evalF6)
          <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Rata-rata: {{ $evalF6->rata_rata_f6 }}</span>
        @endif
      </div>

      <form method="POST" action="{{ route('dosen.evaluasi_f6.store', $activePengajuan->id) }}" class="space-y-2.5">
        @csrf
        <div class="grid grid-cols-3 gap-2">
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Judul &amp; Isi</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_kesesuaian_judul" required value="{{ old('skor_kesesuaian_judul', $evalF6?->skor_kesesuaian_judul ?? 90) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Tata Bahasa</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_tata_bahasa" required value="{{ old('skor_tata_bahasa', $evalF6?->skor_tata_bahasa ?? 85) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Format</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_format_laporan" required value="{{ old('skor_format_laporan', $evalF6?->skor_format_laporan ?? 88) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Penguasaan</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_pengetahuan" required value="{{ old('skor_pengetahuan', $evalF6?->skor_pengetahuan ?? 90) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Tanya Jawab</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_tanya_jawab" required value="{{ old('skor_tanya_jawab', $evalF6?->skor_tanya_jawab ?? 88) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Etika</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_etika" required value="{{ old('skor_etika', $evalF6?->skor_etika ?? 95) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Justifikasi</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_justifikasi" required value="{{ old('skor_justifikasi', $evalF6?->skor_justifikasi ?? 86) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Rutinitas</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_rutinitas_bimbingan" required value="{{ old('skor_rutinitas_bimbingan', $evalF6?->skor_rutinitas_bimbingan ?? 95) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
          <div class="bg-white p-2 rounded-lg border border-slate-200">
            <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Progres</label>
            <input type="number" step="0.5" min="0" max="100" name="skor_progres" required value="{{ old('skor_progres', $evalF6?->skor_progres ?? 88) }}" class="w-full text-xs rounded border border-slate-200 font-bold p-1">
          </div>
        </div>

        <div>
          <input type="text" name="catatan_dosen" value="{{ old('catatan_dosen', $evalF6?->catatan_dosen) }}" placeholder="Catatan evaluasi..." class="w-full text-xs rounded-lg border border-slate-200 p-2 bg-white">
        </div>

        <div class="flex justify-end">
          <button type="submit" class="px-4 py-2 rounded-xl bg-[#02796B] hover:bg-teal-800 text-white font-bold text-xs">
            Simpan Nilai F6
          </button>
        </div>
      </form>

      <!-- Kalkulasi Fuzzy -->
      <div class="p-3.5 rounded-xl bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <span class="text-xs font-bold text-teal-300 block">Kalkulasi Nilai Akhir (Fuzzy Logic)</span>
          <span class="text-[11px] text-slate-300">Gabungan F5 (60%) &amp; F6 (40%)</span>
        </div>

        <form method="POST" action="{{ route('dosen.kalkulasi-fuzzy', $activePengajuan->id) }}">
          @csrf
          <button type="submit" class="px-3.5 py-2 rounded-xl bg-teal-400 hover:bg-teal-300 text-slate-950 font-black text-xs flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">play_arrow</span>
            <span>Hitung Nilai</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

<!-- Logbook Terbaru -->
<div id="logbook-review-section" class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
  <div class="flex items-center justify-between pb-3 border-b border-slate-100">
    <div>
      <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Form F3</span>
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800">Logbook Mahasiswa Bimbingan</h2>
    </div>
    <span class="px-2.5 py-1 bg-blue-50 text-[#0056B3] rounded-lg text-xs font-bold">
      {{ $pendingLogbooksCount }} Menunggu
    </span>
  </div>

  <div class="space-y-3">
    @forelse($logbooks ?? [] as $lb)
      <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200 space-y-2.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <div class="flex items-center gap-2">
              <strong class="text-xs sm:text-sm font-bold text-slate-800">{{ $lb->student->name ?? 'Mahasiswa' }}</strong>
              <span class="text-[11px] font-mono text-[#0056B3]">{{ $lb->student->identifier ?? '-' }}</span>
              <span class="text-[11px] text-slate-400">• {{ $lb->tanggal->translatedFormat('d M Y') }}</span>
            </div>
            <h3 class="text-sm font-bold text-slate-900 mt-0.5">{{ $lb->subject }}</h3>
          </div>

          <div class="flex items-center gap-2 self-start sm:self-auto">
            @if($lb->status === 'approved')
              <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">Disetujui</span>
            @else
              <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold">Menunggu</span>
            @endif

            <button type="button" 
                    onclick="openReviewModal({{ $lb->id }}, '{{ addslashes($lb->student->name ?? 'Mahasiswa') }}', '{{ addslashes($lb->student->identifier ?? '') }}', '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}')"
                    class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3 py-1.5 rounded-lg text-xs font-bold">
              Review
            </button>
          </div>
        </div>

        <p class="p-3 bg-white rounded-lg border border-slate-200/80 text-xs text-slate-700 whitespace-pre-line">{{ $lb->content }}</p>
      </div>
    @empty
      <div class="p-6 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
        Belum ada logbook masuk.
      </div>
    @endforelse
  </div>
</div>

<!-- MODAL: Review Logbook -->
<div id="modal-logbook" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
  <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-scale-up">
    <div class="flex items-start justify-between border-b border-slate-100 pb-3">
      <div>
        <h3 id="modal-student-name" class="text-base font-bold font-heading text-slate-800">Review Logbook</h3>
        <p id="modal-student-sub" class="text-xs text-slate-500"></p>
      </div>
      <button onclick="closeModal('modal-logbook')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl">
      <div class="flex items-center justify-between text-xs text-slate-500 mb-0.5">
        <span class="font-bold text-[#0056B3]">Topik</span>
        <span id="modal-logbook-tanggal" class="font-mono"></span>
      </div>
      <h4 id="modal-logbook-subject" class="text-sm font-bold text-slate-800"></h4>
    </div>

    <div>
      <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas:</label>
      <div id="modal-logbook-content" class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 whitespace-pre-line max-h-52 overflow-y-auto"></div>
    </div>

    <form id="form-approve-logbook" method="POST" action="" class="space-y-3">
      @csrf
      <input type="hidden" name="status" value="approved">
      <div>
        <label for="review-feedback" class="block text-xs font-bold text-slate-700 mb-1">Catatan Dosen:</label>
        <textarea id="review-feedback" name="feedback" rows="3" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">Logbook sesuai progres. Lanjutkan ke tahap berikutnya.</textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeModal('modal-logbook')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Tutup</button>
        <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs font-bold">Setujui Logbook</button>
      </div>
    </form>
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
      const statusAttr = card.getAttribute('data-status') || '';
      const matchesQuery = nameAttr.includes(query);
      const matchesStatus = (statusFilter === 'all') || (statusAttr === statusFilter);
      card.style.display = (matchesQuery && matchesStatus) ? 'block' : 'none';
    });
  }

  function openReviewModal(id, studentName, studentNim, subject, tanggal, content, status, feedback) {
    document.getElementById('modal-student-name').innerText = `Review Logbook: ${studentName}`;
    document.getElementById('modal-student-sub').innerText = `NIM: ${studentNim}`;
    document.getElementById('modal-logbook-subject').innerText = subject;
    document.getElementById('modal-logbook-tanggal').innerText = tanggal;
    document.getElementById('modal-logbook-content').innerText = content;
    document.getElementById('form-approve-logbook').action = `/logbooks/${id}/status`;
    if (feedback && feedback.trim() !== '') {
      document.getElementById('review-feedback').value = feedback;
    }
    openModal('modal-logbook');
  }

  function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
  }
</script>
@endpush
