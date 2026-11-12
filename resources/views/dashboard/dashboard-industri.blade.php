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

  @php
    $tasksTodo = $tugasList->whereIn('status', ['todo', 'direncanakan']);
    $tasksProgress = $tugasList->whereIn('status', ['in_progress', 'sedang_berjalan']);
    $tasksDone = $tugasList->whereIn('status', ['acc', 'selesai']);
  @endphp

  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <div>
        <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800">Matriks Tugas Mahasiswa ({{ $tugasList->count() }})</h3>
        <p class="text-xs text-slate-500">Ubah status tugas mahasiswa dari To-Do &rarr; Progres &rarr; Selesai (ACC).</p>
      </div>
      <span class="text-xs font-bold px-2.5 py-1 bg-teal-50 text-[#02796B] rounded-lg">
        {{ $tasksDone->count() }}/{{ $tugasList->count() }} Selesai
      </span>
    </div>

    @if($tugasList->isEmpty())
      <div class="py-8 text-center text-slate-400 text-xs bg-slate-50/60 rounded-xl border border-dashed border-slate-200">
        Belum ada tugas pada Form F2.
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Kolom 1: To-Do -->
        <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
          <div class="flex items-center justify-between pb-2 border-b border-slate-200">
            <span class="text-xs font-bold text-slate-700">To-Do (Rencana)</span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $tasksTodo->count() }}</span>
          </div>
          @forelse($tasksTodo as $t)
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 space-y-2.5 shadow-sm">
              <div class="space-y-1">
                <div class="flex items-start justify-between gap-2">
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900">{{ $t->modul_pekerjaan }}</h4>
                  @if($t->target_minggu)
                    <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">
                      {{ is_numeric($t->target_minggu) ? 'Minggu '.$t->target_minggu : $t->target_minggu }}
                    </span>
                  @endif
                </div>
                <p class="text-xs text-slate-500">{{ $t->spesifikasi_kebutuhan }}</p>
              </div>

              <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                <form method="POST" action="{{ route('industri.tugas_f2.destroy', $t->id) }}" onsubmit="return confirm('Hapus tugas ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus Tugas">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                  </button>
                </form>

                <form method="POST" action="{{ route('industri.tugas_f2.update', $t->id) }}">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="in_progress">
                  <button type="submit" class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold flex items-center gap-1 transition-colors">
                    <span>Progres</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                  </button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
          @endforelse
        </div>

        <!-- Kolom 2: Progres (Sedang Berjalan) -->
        <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
          <div class="flex items-center justify-between pb-2 border-b border-slate-200">
            <span class="text-xs font-bold text-amber-800">Progres (Sedang Berjalan)</span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">{{ $tasksProgress->count() }}</span>
          </div>
          @forelse($tasksProgress as $t)
            <div class="bg-white p-3.5 rounded-xl border border-amber-200/80 space-y-2.5 shadow-sm">
              <div class="space-y-1">
                <div class="flex items-start justify-between gap-2">
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900">{{ $t->modul_pekerjaan }}</h4>
                  @if($t->target_minggu)
                    <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700">
                      {{ is_numeric($t->target_minggu) ? 'Minggu '.$t->target_minggu : $t->target_minggu }}
                    </span>
                  @endif
                </div>
                <p class="text-xs text-slate-500">{{ $t->spesifikasi_kebutuhan }}</p>
              </div>

              <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-1.5">
                <div class="flex items-center gap-1">
                  <form method="POST" action="{{ route('industri.tugas_f2.destroy', $t->id) }}" onsubmit="return confirm('Hapus tugas ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus Tugas">
                      <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                  </form>

                  <form method="POST" action="{{ route('industri.tugas_f2.update', $t->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="todo">
                    <button type="submit" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-[11px] font-bold flex items-center gap-0.5 transition-colors">
                      <span class="material-symbols-outlined text-[13px]">arrow_back</span>
                      <span>To-Do</span>
                    </button>
                  </form>
                </div>

                <form method="POST" action="{{ route('industri.tugas_f2.update', $t->id) }}">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="acc">
                  <button type="submit" class="px-2.5 py-1 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-[11px] font-bold flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                    <span>Selesai (ACC)</span>
                  </button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
          @endforelse
        </div>

        <!-- Kolom 3: Selesai (ACC) -->
        <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
          <div class="flex items-center justify-between pb-2 border-b border-slate-200">
            <span class="text-xs font-bold text-teal-800">Selesai (ACC)</span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">{{ $tasksDone->count() }}</span>
          </div>
          @forelse($tasksDone as $t)
            <div class="bg-white p-3.5 rounded-xl border border-teal-200/80 space-y-2.5 shadow-sm">
              <div class="space-y-1">
                <div class="flex items-start justify-between gap-2">
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900">{{ $t->modul_pekerjaan }}</h4>
                  @if($t->target_minggu)
                    <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-50 text-[#02796B]">
                      {{ is_numeric($t->target_minggu) ? 'Minggu '.$t->target_minggu : $t->target_minggu }}
                    </span>
                  @endif
                </div>
                <p class="text-xs text-slate-500">{{ $t->spesifikasi_kebutuhan }}</p>
              </div>

              <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                <div class="flex items-center gap-1">
                  <form method="POST" action="{{ route('industri.tugas_f2.destroy', $t->id) }}" onsubmit="return confirm('Hapus tugas ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus Tugas">
                      <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                  </form>

                  <form method="POST" action="{{ route('industri.tugas_f2.update', $t->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-[11px] font-bold flex items-center gap-0.5 transition-colors">
                      <span class="material-symbols-outlined text-[13px]">arrow_back</span>
                      <span>Ke Progres</span>
                    </button>
                  </form>
                </div>

                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#02796B]">
                  <span class="material-symbols-outlined text-[14px]">verified</span>
                  ACC
                </span>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
          @endforelse
        </div>
      </div>
    @endif
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
@endsection
