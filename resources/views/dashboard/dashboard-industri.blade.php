@extends('layouts.app', [
  'title' => 'Dashboard Mitra Industri - SIM-PKL JTIK PNJ',
  'pageTitle' => 'Portal Pembimbing Industri (DUDI)',
  'breadcrumbParent' => 'Portal DUDI',
  'activePage' => 'industri'
])

@section('content')
<!-- Banner Profil Industri -->
<div class="bg-gradient-to-r from-teal-800 to-slate-900 rounded-2xl p-5 sm:p-6 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-teal-200 shrink-0">
      <span class="material-symbols-outlined text-[26px]">apartment</span>
    </div>
    <div>
      <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-teal-400/20 text-teal-200">Mitra DUDI Terverifikasi</span>
      <h1 class="text-lg sm:text-xl font-extrabold font-heading mt-0.5">
        {{ $industri->nama_perusahaan ?? 'Mitra Industri PKL' }}
      </h1>
      <p class="text-xs text-teal-100/80 mt-0.5">
        {{ $industri->bidang_usaha ?: 'Teknologi Informasi' }} • {{ $industri->kota ?: 'Indonesia' }}
      </p>
    </div>
  </div>

  <div class="bg-white/10 rounded-xl px-3.5 py-2 border border-white/10 text-xs self-start md:self-center">
    <span class="text-teal-200/80 block text-[10px] uppercase font-bold">Penanggung Jawab</span>
    <strong class="font-bold text-white">{{ Auth::user()->name }}</strong>
  </div>
</div>

<!-- Pemilih Mahasiswa & Cetak Dokumen -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-teal-600 text-[22px]">group</span>
    <div>
      <span class="text-[10px] font-bold text-slate-400 uppercase block">Mahasiswa Aktif</span>
      <div class="text-sm font-bold text-slate-800">
        {{ $activePengajuan?->mahasiswa?->name ?? 'Belum ada mahasiswa terdaftar' }}
        @if($activePengajuan?->mahasiswa)
          <span class="text-xs font-normal text-slate-500 font-mono">({{ $activePengajuan->mahasiswa->identifier }})</span>
        @endif
      </div>
    </div>
  </div>

  <div class="flex items-center gap-2 flex-wrap">
    @if($pengajuans->count() > 1)
      <form method="GET" action="{{ route('dashboard.industri') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <select name="student_id" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border border-slate-200 py-1.5 px-3 text-slate-700 bg-slate-50">
          @foreach($pengajuans as $p)
            <option value="{{ $p->mahasiswa_id }}" {{ $activePengajuan?->mahasiswa_id === $p->mahasiswa_id ? 'selected' : '' }}>
              {{ $p->mahasiswa->name }} ({{ $p->mahasiswa->identifier }})
            </option>
          @endforeach
        </select>
      </form>
    @endif

    @if($activePengajuan)
      <a href="{{ route('dokumen.f2', $activePengajuan->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1">
        <span class="material-symbols-outlined text-[15px]">print</span>
        <span>Cetak F2</span>
      </a>
      <a href="{{ route('dokumen.f3', $activePengajuan->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1">
        <span class="material-symbols-outlined text-[15px]">print</span>
        <span>Cetak F3</span>
      </a>
    @endif
  </div>
</div>

<!-- Tab Navigasi -->
<div class="flex items-center gap-1.5 border-b border-slate-200 overflow-x-auto pb-px">
  <a href="{{ route('dashboard.industri', ['tab' => 'mahasiswa', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-1.5 px-4 py-2.5 border-b-2 text-xs font-bold transition-all {{ $tab === 'mahasiswa' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[17px]">badge</span>
    <span>Mahasiswa ({{ $pengajuans->count() }})</span>
  </a>

  <a href="{{ route('dashboard.industri', ['tab' => 'f2', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-1.5 px-4 py-2.5 border-b-2 text-xs font-bold transition-all {{ $tab === 'f2' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[17px]">task_alt</span>
    <span>Matriks Tugas (F2)</span>
    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">{{ $tugasList->count() }}</span>
  </a>

  <a href="{{ route('dashboard.industri', ['tab' => 'f3', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-1.5 px-4 py-2.5 border-b-2 text-xs font-bold transition-all {{ $tab === 'f3' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[17px]">history_edu</span>
    <span>Logbook Harian (F3)</span>
    @php $pendingLogbooks = $logbooks->where('status_industri', 'pending')->count(); @endphp
    @if($pendingLogbooks > 0)
      <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">{{ $pendingLogbooks }}</span>
    @endif
  </a>

  <a href="{{ route('dashboard.industri', ['tab' => 'f5', 'student_id' => $activePengajuan?->mahasiswa_id]) }}"
     class="inline-flex items-center gap-1.5 px-4 py-2.5 border-b-2 text-xs font-bold transition-all {{ $tab === 'f5' ? 'border-teal-600 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[17px]">grade</span>
    <span>Evaluasi Kinerja (F5 - 60%)</span>
    @if($evaluasiF5)
      <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Tersimpan</span>
    @endif
  </a>
</div>

<!-- TAB 1: DAFTAR MAHASISWA -->
@if($tab === 'mahasiswa')
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
  <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800 mb-3">Mahasiswa Magang</h3>
  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="w-full text-left text-xs text-slate-600">
      <thead class="bg-slate-50 text-[11px] font-bold text-slate-600 uppercase border-b border-slate-200">
        <tr>
          <th class="py-2.5 px-4">Mahasiswa</th>
          <th class="py-2.5 px-4">Posisi</th>
          <th class="py-2.5 px-4">Dosen Pembimbing</th>
          <th class="py-2.5 px-4">Progres F2</th>
          <th class="py-2.5 px-4">Nilai F5</th>
          <th class="py-2.5 px-4 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($pengajuans as $p)
        <tr class="hover:bg-slate-50/80">
          <td class="py-3 px-4">
            <div class="font-bold text-slate-900">{{ $p->mahasiswa->name }}</div>
            <div class="font-mono text-slate-400 text-[11px]">{{ $p->mahasiswa->identifier }}</div>
          </td>
          <td class="py-3 px-4 font-medium text-slate-800">{{ $p->posisi_magang ?: 'Intern' }}</td>
          <td class="py-3 px-4">
            @if($p->dosen)
              <span class="text-[#0056B3] font-semibold">{{ $p->dosen->name }}</span>
            @else
              <span class="text-slate-400">Belum Ditetapkan</span>
            @endif
          </td>
          <td class="py-3 px-4">
            @php
              $totalT = $p->tugasF2->count();
              $accT = $p->tugasF2->where('status', 'acc')->count();
            @endphp
            <span class="font-bold text-slate-800">{{ $accT }}/{{ $totalT }} Selesai</span>
          </td>
          <td class="py-3 px-4">
            @if($p->evaluasiF5)
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                Terisi ({{ $p->evaluasiF5->rata_rata_f5 }})
              </span>
            @else
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                Belum Dinilai
              </span>
            @endif
          </td>
          <td class="py-3 px-4 text-center">
            <a href="{{ route('dashboard.industri', ['tab' => 'f2', 'student_id' => $p->mahasiswa_id]) }}" class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs inline-flex items-center gap-1">
              <span>Kelola</span>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="py-6 text-center text-slate-400">Belum ada mahasiswa magang di perusahaan ini.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endif

<!-- TAB 2: FORM F2 MATRIKS TUGAS -->
@if($tab === 'f2')
@if(!$activePengajuan)
  <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5 text-amber-800 text-xs">
    Pilih mahasiswa terlebih dahulu untuk mengelola Form F2.
  </div>
@else
<div class="space-y-5">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
    <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800 mb-3">Tambah Tugas (Form F2)</h3>
    <form method="POST" action="{{ route('industri.tugas_f2.store', $activePengajuan->id) }}" class="space-y-3">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="sm:col-span-2">
          <label class="block text-xs font-bold text-slate-700 mb-1">Modul Pekerjaan / Fitur</label>
          <input type="text" name="modul_pekerjaan" required placeholder="Contoh: Pembuatan REST API Modul Pembayaran" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Target Periode</label>
          <input type="text" name="target_minggu" placeholder="Minggu 1-2" class="w-full text-xs rounded-xl border border-slate-200 p-2.5">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Spesifikasi Tugas</label>
        <textarea name="spesifikasi_kebutuhan" rows="2" required placeholder="Rincian teknis pekerjaan dan kriteria penyelesaian..." class="w-full text-xs rounded-xl border border-slate-200 p-2.5"></textarea>
      </div>

      <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs">
          Tambah Tugas
        </button>
      </div>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-3">
    <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">Daftar Tugas ({{ $tugasList->count() }})</h3>
    <div class="space-y-2.5">
      @forelse($tugasList as $t)
      <div class="rounded-xl border border-slate-200 p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $t->status === 'acc' ? 'bg-teal-50/20' : 'bg-white' }}">
        <div class="space-y-0.5">
          <div class="flex items-center gap-2">
            <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $t->modul_pekerjaan }}</h4>
            @if($t->target_minggu)
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">{{ $t->target_minggu }}</span>
            @endif
          </div>
          <p class="text-xs text-slate-500">{{ $t->spesifikasi_kebutuhan }}</p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <form method="POST" action="{{ route('industri.tugas_f2.update', $t->id) }}">
            @csrf
            @method('PUT')
            <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border border-slate-200 py-1.5 px-2.5 {{ $t->status === 'acc' ? 'bg-emerald-50 text-emerald-800' : ($t->status === 'in_progress' ? 'bg-blue-50 text-blue-800' : 'bg-slate-50 text-slate-800') }}">
              <option value="todo" {{ $t->status === 'todo' ? 'selected' : '' }}>To-Do</option>
              <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
              <option value="acc" {{ $t->status === 'acc' ? 'selected' : '' }}>ACC (Selesai)</option>
            </select>
          </form>

          <form method="POST" action="{{ route('industri.tugas_f2.destroy', $t->id) }}" onsubmit="return confirm('Hapus tugas ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50">
              <span class="material-symbols-outlined text-[17px]">delete</span>
            </button>
          </form>
        </div>
      </div>
      @empty
      <div class="py-6 text-center text-slate-400 text-xs">Belum ada tugas pada Form F2.</div>
      @endforelse
    </div>
  </div>
</div>
@endif
@endif

<!-- TAB 3: FORM F3 VERIFIKASI LOGBOOK -->
@if($tab === 'f3')
@if(!$activePengajuan)
  <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5 text-amber-800 text-xs">
    Pilih mahasiswa terlebih dahulu untuk meninjau logbook.
  </div>
@else
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
  <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">Verifikasi Logbook Harian (Form F3)</h3>

  <div class="space-y-3">
    @forelse($logbooks as $lb)
    <div class="rounded-xl border border-slate-200 p-4 space-y-2">
      <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div class="space-y-1 flex-1">
          <div class="flex flex-wrap items-center gap-2">
            <span class="font-bold text-slate-900 text-xs sm:text-sm">{{ $lb->subject }}</span>
            <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-slate-100 text-slate-600">
              {{ $lb->tanggal->translatedFormat('d M Y') }}
            </span>

            @if($lb->hari_terlambat > 0)
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900">Terlambat {{ $lb->hari_terlambat }} Hari</span>
            @endif

            @if($lb->status_industri === 'acc')
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Disetujui</span>
            @elseif($lb->status_industri === 'revisi')
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Revisi</span>
            @else
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
            @endif
          </div>

          <p class="text-xs text-slate-600 whitespace-pre-line pt-1">{{ $lb->content }}</p>

          @if($lb->catatan_industri)
            <div class="text-[11px] text-teal-900 bg-teal-50 p-2 rounded-lg border border-teal-200 mt-1">
              <strong>Catatan DUDI:</strong> {{ $lb->catatan_industri }}
            </div>
          @endif
        </div>

        <div class="flex items-center gap-2 shrink-0">
          @if($lb->status_industri !== 'acc')
            <form method="POST" action="{{ route('industri.logbooks.acc', $lb->id) }}">
              @csrf
              <button type="submit" class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs">
                ACC
              </button>
            </form>
            <button type="button" onclick="document.getElementById('modal-revisi-{{ $lb->id }}').classList.remove('hidden')" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 font-bold text-xs border border-slate-200">
              Revisi
            </button>
          @else
            <span class="text-xs font-bold text-emerald-700">Terverifikasi</span>
          @endif
        </div>
      </div>

      <div id="modal-revisi-{{ $lb->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-5 text-left shadow-2xl border border-slate-200">
          <h3 class="text-sm font-bold text-slate-900 mb-2">Catatan Revisi Logbook</h3>
          <form method="POST" action="{{ route('industri.logbooks.revisi', $lb->id) }}" class="space-y-3">
            @csrf
            <textarea name="catatan_industri" rows="3" required placeholder="Tulis catatan revisi..." class="w-full text-xs rounded-xl border border-slate-200 p-2.5"></textarea>
            <div class="flex justify-end gap-2">
              <button type="button" onclick="document.getElementById('modal-revisi-{{ $lb->id }}').classList.add('hidden')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
              <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-rose-600 text-white">Kirim</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="py-6 text-center text-slate-400 text-xs">Belum ada logbook harian.</div>
    @endforelse
  </div>
</div>
@endif
@endif

<!-- TAB 4: FORM F5 EVALUASI INDUSTRI -->
@if($tab === 'f5')
@if(!$activePengajuan)
  <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5 text-amber-800 text-xs">
    Pilih mahasiswa terlebih dahulu untuk mengisi evaluasi nilai.
  </div>
@else
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-5">
  <div class="flex items-center justify-between border-b border-slate-100 pb-3">
    <div>
      <span class="text-[10px] font-bold uppercase text-teal-700">Bobot 60% Nilai Akhir</span>
      <h3 class="text-base font-bold font-heading text-slate-800">Form F5: Evaluasi Kinerja Mahasiswa</h3>
    </div>
    @if($evaluasiF5)
      <div class="text-right">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Rata-rata</span>
        <div class="text-xl font-black font-heading text-teal-700">{{ $evaluasiF5->rata_rata_f5 }}/100</div>
      </div>
    @endif
  </div>

  <form method="POST" action="{{ route('industri.evaluasi_f5.store', $activePengajuan->id) }}" class="space-y-5">
    @csrf
    <div>
      <h4 class="text-xs font-bold text-slate-700 uppercase mb-2.5">A. Softskills (0 – 100)</h4>
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Komunikasi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_komunikasi" required value="{{ old('skor_komunikasi', $evaluasiF5?->skor_komunikasi ?? 85) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-teal-800 p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Berpikir Kritis</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_berpikir_kritis" required value="{{ old('skor_berpikir_kritis', $evaluasiF5?->skor_berpikir_kritis ?? 85) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-teal-800 p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Kerjasama Tim</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_kerjasama" required value="{{ old('skor_kerjasama', $evaluasiF5?->skor_kerjasama ?? 90) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-teal-800 p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Inisiatif</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_inisiatif" required value="{{ old('skor_inisiatif', $evaluasiF5?->skor_inisiatif ?? 85) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-teal-800 p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Literasi Digital</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_literasi" required value="{{ old('skor_literasi', $evaluasiF5?->skor_literasi ?? 88) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-teal-800 p-1.5">
        </div>
      </div>
    </div>

    <div>
      <h4 class="text-xs font-bold text-slate-700 uppercase mb-2.5">B. Hardskills (0 – 100)</h4>
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Deskripsi Produk</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_deskripsi_produk" required value="{{ old('skor_deskripsi_produk', $evaluasiF5?->skor_deskripsi_produk ?? 88) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-[#0056B3] p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Spesifikasi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_spesifikasi" required value="{{ old('skor_spesifikasi', $evaluasiF5?->skor_spesifikasi ?? 86) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-[#0056B3] p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Desain</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_desain" required value="{{ old('skor_desain', $evaluasiF5?->skor_desain ?? 85) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-[#0056B3] p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Implementasi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_implementasi" required value="{{ old('skor_implementasi', $evaluasiF5?->skor_implementasi ?? 90) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-[#0056B3] p-1.5">
        </div>
        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Pengujian</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_pengujian" required value="{{ old('skor_pengujian', $evaluasiF5?->skor_pengujian ?? 87) }}" class="w-full text-xs rounded-lg border border-slate-200 font-bold text-[#0056B3] p-1.5">
        </div>
      </div>
    </div>

    <div>
      <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Saran Industri</label>
      <textarea name="saran_industri" rows="2" placeholder="Saran pengembangan kompetensi untuk mahasiswa..." class="w-full text-xs rounded-xl border border-slate-200 p-2.5">{{ old('saran_industri', $evaluasiF5?->saran_industri) }}</textarea>
    </div>

    <div class="flex justify-end">
      <button type="submit" class="px-5 py-2 rounded-xl bg-teal-800 hover:bg-teal-900 text-white font-bold text-xs">
        Simpan Nilai F5
      </button>
    </div>
  </form>
</div>
@endif
@endif
@endsection
