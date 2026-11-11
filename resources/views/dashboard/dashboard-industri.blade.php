@extends('layouts.app', [
  'title' => 'Dashboard Pembimbing Industri - SIM-PKL TIK Politeknik Negeri Jakarta',
  'pageTitle' => 'Portal Pembimbing Lapangan Industri (DUDI)',
  'breadcrumbParent' => 'Portal Industri (DUDI)',
  'activePage' => 'industri'
])

@section('content')
<!-- Header Context & Industri Profile Banner -->
<div class="bg-gradient-to-r from-teal-800 via-teal-700 to-slate-900 rounded-2xl p-6 text-white mb-6 shadow-md relative overflow-hidden">
  <div class="absolute -right-10 -bottom-10 opacity-10 text-white pointer-events-none">
    <span class="material-symbols-outlined text-[180px]">domain</span>
  </div>

  <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-start gap-4">
      <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-bold text-2xl text-teal-200 shadow-inner">
        <span class="material-symbols-outlined text-[30px]">apartment</span>
      </div>
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-teal-400/20 text-teal-200 border border-teal-300/30">
            Mitra DUDI Terverifikasi
          </span>
          <span class="text-xs text-teal-100/70">• Tahun Panduan 2026</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold font-heading tracking-tight">
          {{ $industri->nama_perusahaan ?? 'Mitra Industri PKL' }}
        </h1>
        <p class="text-xs sm:text-sm text-teal-100/80 mt-1 max-w-2xl">
          {{ $industri->bidang_usaha ?: 'Teknologi Informasi, Telekomunikasi & Rekayasa Perangkat Lunak' }} • {{ $industri->kota ?: 'DKI Jakarta' }}
        </p>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
      <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/10 text-xs">
        <span class="text-teal-200/80 block text-[10px] uppercase font-bold">Penanggung Jawab:</span>
        <strong class="font-bold text-white">{{ Auth::user()->name }}</strong>
      </div>
    </div>
  </div>
</div>

<!-- Mahasiswa Selector & Status Row -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-teal-600 text-[24px]">group</span>
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mahasiswa Magang Terpilih:</span>
      <div class="text-base font-bold text-slate-800 font-heading">
        {{ $activePengajuan?->mahasiswa?->name ?? 'Belum ada mahasiswa terdaftar' }}
        @if($activePengajuan?->mahasiswa)
          <span class="text-xs font-normal text-slate-500 font-mono">({{ $activePengajuan->mahasiswa->identifier }})</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Dropdown Ganti Mahasiswa -->
  <div class="flex items-center gap-2">
    @if($pengajuans->count() > 1)
      <form method="GET" action="{{ route('dashboard.industri') }}" class="flex items-center gap-2">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <select name="student_id" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border-slate-200 py-2 px-3 text-slate-700 focus:border-teal-500 focus:ring-teal-500">
          @foreach($pengajuans as $p)
            <option value="{{ $p->mahasiswa_id }}" {{ $activePengajuan?->mahasiswa_id === $p->mahasiswa_id ? 'selected' : '' }}>
              {{ $p->mahasiswa->name }} ({{ $p->mahasiswa->identifier }})
            </option>
          @endforeach
        </select>
      </form>
    @endif

    @if($activePengajuan)
      <a href="{{ route('dokumen.f2', $activePengajuan->id) }}" target="_blank" class="px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1.5 transition-colors">
        <span class="material-symbols-outlined text-[16px]">print</span>
        <span>Cetak Form F2</span>
      </a>
      <a href="{{ route('dokumen.f3', $activePengajuan->id) }}" target="_blank" class="px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1.5 transition-colors">
        <span class="material-symbols-outlined text-[16px]">print</span>
        <span>Cetak Form F3</span>
      </a>
    @endif
  </div>
</div>

<!-- Tabs Navigation -->
<div class="flex items-center gap-2 border-b border-slate-200 mb-6 overflow-x-auto pb-px">
  <a href="{{ route('dashboard.industri', ['tab' => 'mahasiswa', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'mahasiswa' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">badge</span>
    <span>Daftar Mahasiswa Bimbingan ({{ $pengajuans->count() }})</span>
  </a>

  <a href="{{ route('dashboard.industri', ['tab' => 'f2', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'f2' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">task_alt</span>
    <span>Form F2: To-Do List &amp; Spesifikasi Tugas</span>
    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-teal-100 text-teal-800">{{ $tugasList->count() }}</span>
  </a>

  <a href="{{ route('dashboard.industri', ['tab' => 'f3', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'f3' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">history_edu</span>
    <span>Form F3: Verifikasi Logbook Harian</span>
    @php
      $pendingLogbooks = $logbooks->where('status_industri', 'pending')->count();
    @endphp
    @if($pendingLogbooks > 0)
      <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white">{{ $pendingLogbooks }}</span>
    @endif
  </a>

  <a href="{{ route('dashboard.industri', ['tab' => 'f5', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'f5' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">lock</span>
    <span>Form F5: Evaluasi Nilai Rahasia (Bobot 60%)</span>
    @if($evaluasiF5)
      <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Tersimpan</span>
    @endif
  </a>
</div>

<!-- TAB 1: MAHASISWA BIMBINGAN LIST -->
@if($tab === 'mahasiswa')
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
  <h3 class="text-base font-bold font-heading text-slate-800 mb-4">Daftar Mahasiswa Praktik Kerja Lapangan (PKL)</h3>
  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="w-full text-left text-xs text-slate-600">
      <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
        <tr>
          <th class="py-3 px-4">Mahasiswa &amp; NIM</th>
          <th class="py-3 px-4">Posisi &amp; Judul PKL</th>
          <th class="py-3 px-4">Dosen Pembimbing PNJ</th>
          <th class="py-3 px-4">Progres Tugas F2</th>
          <th class="py-3 px-4">Status Nilai F5</th>
          <th class="py-3 px-4 text-center">Aksi Bimbingan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium">
        @forelse($pengajuans as $p)
        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="py-3.5 px-4 font-bold text-slate-900">
            <div>{{ $p->mahasiswa->name }}</div>
            <div class="font-mono text-slate-400 text-[11px]">{{ $p->mahasiswa->identifier }}</div>
            <div class="text-[10px] text-slate-500">{{ $p->mahasiswa->prodi }}</div>
          </td>
          <td class="py-3.5 px-4">
            <div class="font-semibold text-slate-800">{{ $p->posisi_magang ?: 'Intern' }}</div>
            <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $p->judul_magang ?: 'Proyek PKL Industri' }}</div>
          </td>
          <td class="py-3.5 px-4">
            @if($p->dosen)
              <span class="text-[#0056B3] font-bold">{{ $p->dosen->name }}</span>
            @else
              <span class="text-slate-400 italic">Belum Ditetapkan</span>
            @endif
          </td>
          <td class="py-3.5 px-4">
            @php
              $totalT = $p->tugasF2->count();
              $accT = $p->tugasF2->where('status', 'acc')->count();
            @endphp
            <div class="font-bold text-slate-800">{{ $accT }}/{{ $totalT }} Modul ACC</div>
            <div class="w-24 bg-slate-100 h-1.5 rounded-full mt-1 overflow-hidden">
              <div class="bg-teal-500 h-full rounded-full" style="width: {{ $totalT > 0 ? ($accT / $totalT) * 100 : 0 }}%"></div>
            </div>
          </td>
          <td class="py-3.5 px-4">
            @if($p->evaluasiF5)
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="material-symbols-outlined text-[13px]">check_circle</span> Terisi ({{ $p->evaluasiF5->rata_rata_f5 }})
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="material-symbols-outlined text-[13px]">pending</span> Belum Dinilai
              </span>
            @endif
          </td>
          <td class="py-3.5 px-4 text-center">
            <a href="{{ route('dashboard.industri', ['tab' => 'f2', 'student_id' => $p->mahasiswa_id]) }}" class="px-3 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs inline-flex items-center gap-1 shadow-sm transition-colors">
              <span>Buka Menu F2 &amp; F3</span>
              <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="py-8 text-center text-slate-400">Belum ada mahasiswa yang dialokasikan ke industri ini.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endif

<!-- TAB 2: FORM F2 TO-DO LIST (PRD 5.3.1 & 7.2) -->
@if($tab === 'f2')
@if(!$activePengajuan)
  <div class="bg-amber-50 rounded-2xl border border-amber-200 p-6 text-amber-800 text-sm">
    Pilih mahasiswa terlebih dahulu untuk mengelola Form F2.
  </div>
@else
<div class="space-y-6">
  <!-- Form Tambah Modul Tugas F2 -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
        <span class="material-symbols-outlined text-[22px]">add_task</span>
      </div>
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Tambah Spesifikasi Tugas &amp; User Requirement (Form F2)</h3>
        <p class="text-xs text-slate-500">
          Pembimbing Industri memegang hak akses penuh untuk menambah, merevisi, dan meng-ACC penyelesaian tugas teknis mahasiswa.
        </p>
      </div>
    </div>

    <form method="POST" action="{{ route('industri.tugas_f2.store', $activePengajuan->id) }}" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="sm:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Modul Pekerjaan / Fitur</label>
          <input type="text" name="modul_pekerjaan" required placeholder="Contoh: Implementasi Terraform VPC &amp; Kubernetes EKS" class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-teal-500 focus:ring-teal-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Periode</label>
          <input type="text" name="target_minggu" placeholder="Contoh: Minggu 1-4" class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-teal-500 focus:ring-teal-500">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Spesifikasi Kebutuhan &amp; Acceptance Criteria</label>
        <textarea name="spesifikasi_kebutuhan" rows="3" required placeholder="Rincikan deskripsi teknis pekerjaan, standar deliverable, tools, dan kriteria penyelesaian yang diharapkan..." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:border-teal-500 focus:ring-teal-500"></textarea>
      </div>

      <div class="flex items-center justify-end">
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs flex items-center gap-2 shadow-sm transition-colors">
          <span class="material-symbols-outlined text-[16px]">save</span>
          <span>Simpan ke Matriks Form F2</span>
        </button>
      </div>
    </form>
  </div>

  <!-- Backlog Matriks Tugas F2 -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex items-center justify-between gap-4 mb-4">
      <h3 class="text-base font-bold font-heading text-slate-800">Backlog Tugas Teknis ({{ $tugasList->count() }} Modul)</h3>
      <span class="text-xs text-slate-400">Dosen Pembimbing PNJ memiliki akses Read-Only pada matriks ini.</span>
    </div>

    <div class="space-y-3">
      @forelse($tugasList as $t)
      <div class="rounded-xl border border-slate-200 p-4 hover:border-teal-200 transition-colors {{ $t->status === 'acc' ? 'bg-teal-50/20' : 'bg-white' }}">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <h4 class="font-bold text-slate-900 text-sm">{{ $t->modul_pekerjaan }}</h4>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">{{ $t->target_minggu }}</span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">{{ $t->spesifikasi_kebutuhan }}</p>
            @if($t->catatan_industri)
              <div class="text-[11px] text-teal-800 bg-teal-50 p-2 rounded-lg mt-2 border border-teal-200/60">
                <strong>Catatan Evaluasi Industri:</strong> {{ $t->catatan_industri }}
              </div>
            @endif
          </div>

          <!-- Status & Kontrol Form F2 -->
          <div class="flex items-center gap-2 shrink-0">
            <form method="POST" action="{{ route('industri.tugas_f2.update', $t->id) }}" class="flex items-center gap-1.5">
              @csrf
              @method('PUT')
              <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border-slate-200 py-1.5 px-2.5 {{ $t->status === 'acc' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : ($t->status === 'in_progress' ? 'bg-blue-50 text-blue-800 border-blue-300' : 'bg-slate-50 text-slate-800') }}">
                <option value="todo" {{ $t->status === 'todo' ? 'selected' : '' }}>To-Do (Rencana)</option>
                <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="acc" {{ $t->status === 'acc' ? 'selected' : '' }}>ACC (Selesai)</option>
              </select>
            </form>

            <form method="POST" action="{{ route('industri.tugas_f2.destroy', $t->id) }}" onsubmit="return confirm('Hapus tugas {{ $t->modul_pekerjaan }}?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Tugas">
                <span class="material-symbols-outlined text-[18px]">delete</span>
              </button>
            </form>
          </div>
        </div>
      </div>
      @empty
      <div class="py-8 text-center text-slate-400 text-xs">Belum ada modul tugas yang dibuat. Silakan tambahkan melalui form di atas.</div>
      @endforelse
    </div>
  </div>
</div>
@endif
@endif

<!-- TAB 3: FORM F3 VERIFIKASI LOGBOOK HARIAN DENGAN PENANDA KETERLAMBATAN BACKDATE (PRD 5.3.2 & 7.2) -->
@if($tab === 'f3')
@if(!$activePengajuan)
  <div class="bg-amber-50 rounded-2xl border border-amber-200 p-6 text-amber-800 text-sm">
    Pilih mahasiswa terlebih dahulu untuk meninjau logbook.
  </div>
@else
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h3 class="text-base font-bold font-heading text-slate-800">Persetujuan &amp; Review Logbook Harian (Form F3)</h3>
      <p class="text-xs text-slate-500">
        Mahasiswa {{ $activePengajuan?->mahasiswa?->name ?? 'Mahasiswa' }} • Sistem secara otomatis mencatat dan menandai keterlambatan pengisian backdate.
      </p>
    </div>
  </div>

  <div class="space-y-4">
    @forelse($logbooks as $lb)
    <div class="rounded-2xl border p-5 transition-all {{ $lb->status_industri === 'acc' ? 'border-emerald-200 bg-emerald-50/10' : ($lb->status_industri === 'revisi' ? 'border-rose-200 bg-rose-50/20' : 'border-slate-200 bg-white hover:border-teal-200') }}">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div class="space-y-1.5 flex-1">
          <div class="flex flex-wrap items-center gap-2">
            <span class="font-bold text-slate-900 text-sm">{{ $lb->subject }}</span>
            <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-slate-100 text-slate-700 font-semibold">
              {{ $lb->tanggal->translatedFormat('l, d F Y') }}
            </span>

            <!-- Badge Keterlambatan Backdate Sesuai PRD 7.2 -->
            @if($lb->hari_terlambat > 0)
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">
                <span class="material-symbols-outlined text-[12px]">alarm</span>
                <span>Terlambat {{ $lb->hari_terlambat }} Hari</span>
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="material-symbols-outlined text-[12px]">schedule</span>
                <span>Tepat Waktu</span>
              </span>
            @endif

            <!-- Status ACC Industri -->
            @if($lb->status_industri === 'acc')
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Disetujui Industri</span>
            @elseif($lb->status_industri === 'revisi')
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Perlu Revisi</span>
            @else
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu ACC</span>
            @endif
          </div>

          <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line mt-2">{{ $lb->content }}</p>

          @if($lb->catatan_industri)
            <div class="text-[11px] text-teal-900 bg-teal-50 p-2.5 rounded-xl border border-teal-200/80 mt-2">
              <strong>Ulasan Pembimbing Lapangan:</strong> {{ $lb->catatan_industri }}
            </div>
          @endif
        </div>

        <!-- Tombol Aksi Verifikasi Industri -->
        <div class="flex items-center gap-2 shrink-0">
          @if($lb->status_industri !== 'acc')
            <form method="POST" action="{{ route('industri.logbooks.acc', $lb->id) }}">
              @csrf
              <button type="submit" class="px-3 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs flex items-center gap-1 shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[15px]">check</span>
                <span>ACC Logbook</span>
              </button>
            </form>

            <button type="button" onclick="document.getElementById('modal-revisi-{{ $lb->id }}').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 font-bold text-xs border border-slate-200 transition-colors">
              Minta Revisi
            </button>
          @else
            <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px]">verified</span> Terverifikasi
            </span>
          @endif
        </div>
      </div>

      <!-- Modal Catatan Revisi Industri -->
      <div id="modal-revisi-{{ $lb->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
          <h3 class="text-base font-bold text-slate-900 mb-1">Catatan Revisi Logbook</h3>
          <p class="text-xs text-slate-500 mb-4">Sampaikan instruksi revisi atau uraian yang perlu diperbaiki mahasiswa.</p>

          <form method="POST" action="{{ route('industri.logbooks.revisi', $lb->id) }}">
            @csrf
            <div class="mb-4">
              <textarea name="catatan_industri" rows="3" required placeholder="Contoh: Mohon lengkapi detail output dari pengujian performa API..." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:border-teal-500 focus:ring-teal-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2">
              <button type="button" onclick="document.getElementById('modal-revisi-{{ $lb->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
              <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-sm">Kirim Catatan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="py-8 text-center text-slate-400 text-xs">Belum ada riwayat logbook harian yang dikirim mahasiswa.</div>
    @endforelse
  </div>
</div>
@endif
@endif

<!-- TAB 4: FORM F5 EVALUASI INDUSTRI RAHASIA (BOBOT 60%) (PRD 5.4 & 11.1) -->
@if($tab === 'f5')
@if(!$activePengajuan)
  <div class="bg-amber-50 rounded-2xl border border-amber-200 p-6 text-amber-800 text-sm">
    Pilih mahasiswa terlebih dahulu untuk mengisi evaluasi nilai.
  </div>
@else
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
          Kerahasiaan Tingkat Tinggi (Confidential)
        </span>
        <span class="text-xs text-slate-400">• Bobot 60% Penilaian Akhir</span>
      </div>
      <h3 class="text-lg font-bold font-heading text-slate-800">Form F5: Evaluasi Nilai Kinerja Mahasiswa di Industri</h3>
      <p class="text-xs text-slate-500 mt-1">
        Sesuai PRD 11.1, form evaluasi ini bersifat tertutup. Mahasiswa tidak dapat melihat rincian poin nilai individu, hanya skor akhir rasio Fuzzy Logic.
      </p>
    </div>

    @if($evaluasiF5)
      <div class="text-right">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Rata-rata Terkini:</span>
        <div class="text-2xl font-black font-heading text-teal-700">{{ $evaluasiF5->rata_rata_f5 }}/100</div>
      </div>
    @endif
  </div>

  <form method="POST" action="{{ route('industri.evaluasi_f5.store', $activePengajuan->id) }}" class="space-y-6">
    @csrf

    <!-- Kategori Softskills (5 Parameter) -->
    <div>
      <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
        <span>A. Parameter Softskills &amp; Etika Kerja (Skala 0 - 100)</span>
      </h4>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Komunikasi &amp; Presentasi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_komunikasi" required value="{{ old('skor_komunikasi', $evaluasiF5?->skor_komunikasi ?? 85) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-teal-800 p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Berpikir Kritis &amp; Analitis</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_berpikir_kritis" required value="{{ old('skor_berpikir_kritis', $evaluasiF5?->skor_berpikir_kritis ?? 85) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-teal-800 p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Kerjasama Tim (Teamwork)</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_kerjasama" required value="{{ old('skor_kerjasama', $evaluasiF5?->skor_kerjasama ?? 90) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-teal-800 p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Inisiatif &amp; Kemandirian</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_inisiatif" required value="{{ old('skor_inisiatif', $evaluasiF5?->skor_inisiatif ?? 85) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-teal-800 p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Literasi Digital &amp; Informasi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_literasi" required value="{{ old('skor_literasi', $evaluasiF5?->skor_literasi ?? 88) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-teal-800 p-2">
        </div>
      </div>
    </div>

    <!-- Kategori Hardskills (5 Parameter) -->
    <div>
      <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-[#0056B3]"></span>
        <span>B. Parameter Hardskills &amp; Mutu Teknis (Skala 0 - 100)</span>
      </h4>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Deskripsi Produk &amp; Fitur</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_deskripsi_produk" required value="{{ old('skor_deskripsi_produk', $evaluasiF5?->skor_deskripsi_produk ?? 88) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-[#0056B3] p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Spesifikasi Kebutuhan</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_spesifikasi" required value="{{ old('skor_spesifikasi', $evaluasiF5?->skor_spesifikasi ?? 86) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-[#0056B3] p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Desain Arsitektur / UI</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_desain" required value="{{ old('skor_desain', $evaluasiF5?->skor_desain ?? 85) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-[#0056B3] p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Implementasi Kode / Sistem</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_implementasi" required value="{{ old('skor_implementasi', $evaluasiF5?->skor_implementasi ?? 90) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-[#0056B3] p-2">
        </div>
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Pengujian &amp; Bug Fixing</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_pengujian" required value="{{ old('skor_pengujian', $evaluasiF5?->skor_pengujian ?? 87) }}" class="w-full text-xs rounded-lg border-slate-200 font-bold text-[#0056B3] p-2">
        </div>
      </div>
    </div>

    <!-- Saran dan Rekomendasi Industri -->
    <div>
      <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Saran / Rekomendasi Karir untuk Mahasiswa</label>
      <textarea name="saran_industri" rows="3" placeholder="Saran profesional untuk pengembangan kompetensi mahasiswa di masa depan..." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:border-teal-500 focus:ring-teal-500">{{ old('saran_industri', $evaluasiF5?->saran_industri) }}</textarea>
    </div>

    <div class="flex items-center justify-end">
      <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-800 hover:bg-teal-900 text-white font-bold text-xs flex items-center gap-2 shadow-sm transition-colors">
        <span class="material-symbols-outlined text-[16px]">lock</span>
        <span>Simpan &amp; Enkripsi Nilai F5 (Bobot 60%)</span>
      </button>
    </div>
  </form>
</div>
@endif
@endif
@endsection
