@extends('layouts.app', [
  'title' => 'Dashboard KPS - SIM-PKL JTIK PNJ',
  'pageTitle' => 'Penetapan Pembimbing PKL',
  'breadcrumbParent' => 'Portal KPS',
  'activePage' => 'dashboard_kps'
])

@section('content')
<!-- Ringkasan Metrik KPS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Total Mahasiswa</span>
      <div class="text-2xl font-extrabold font-heading text-slate-800 mt-0.5">{{ $totalStudents }}</div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">school</span>
    </div>
  </div>

  <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Sudah Ditetapkan</span>
      <div class="text-2xl font-extrabold font-heading text-emerald-700 mt-0.5">{{ $assignedStudentsCount }}</div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">verified</span>
    </div>
  </div>

  <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between {{ $pendingCount > 0 ? 'ring-1 ring-amber-400 bg-amber-50/20' : '' }}">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Menunggu Penetapan KPS</span>
      <div class="text-2xl font-extrabold font-heading text-amber-600 mt-0.5">{{ $pendingCount }}</div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">assignment_turned_in</span>
    </div>
  </div>

  <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between">
    <div>
      <span class="text-[11px] font-bold text-slate-400 uppercase">Kuota Dosen Terisi</span>
      <div class="text-2xl font-extrabold font-heading text-[#0056B3] mt-0.5">{{ $usedCapacity }} / {{ $totalCapacity }}</div>
    </div>
    <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">pie_chart</span>
    </div>
  </div>
</div>

<!-- Navigasi Tab KPS -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-1.5 shadow-sm flex items-center gap-1.5 overflow-x-auto">
  <button type="button" onclick="switchTab('plotting')" id="tab-btn-plotting" class="kps-tab-btn px-4 py-2 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all bg-[#0056B3] text-white shadow-sm">
    <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
    <span>Penetapan &amp; Plotting Form F1</span>
    @if($pendingCount > 0)
      <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-400 text-slate-900 font-extrabold">{{ $pendingCount }}</span>
    @endif
  </button>

  <button type="button" onclick="switchTab('langsung')" id="tab-btn-langsung" class="kps-tab-btn px-4 py-2 rounded-xl text-xs font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-slate-50 transition-all">
    <span class="material-symbols-outlined text-[18px]">bolt</span>
    <span>Plotting Langsung</span>
    @if($unassignedCount > 0)
      <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-bold">{{ $unassignedCount }}</span>
    @endif
  </button>

  <button type="button" onclick="switchTab('kuota')" id="tab-btn-kuota" class="kps-tab-btn px-4 py-2 rounded-xl text-xs font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-slate-50 transition-all">
    <span class="material-symbols-outlined text-[18px]">pie_chart</span>
    <span>Distribusi Kuota Dosen</span>
  </button>

  <button type="button" onclick="switchTab('rekap')" id="tab-btn-rekap" class="kps-tab-btn px-4 py-2 rounded-xl text-xs font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-slate-50 transition-all">
    <span class="material-symbols-outlined text-[18px]">badge</span>
    <span>Rekapitulasi Seluruh Mahasiswa</span>
  </button>
</div>

<!-- TAB 1: PENETAPAN & PLOTTING FORM F1 -->
<div id="tab-content-plotting" class="kps-tab-pane space-y-5">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
      <div>
        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Pengajuan Form F1 Mahasiswa</h3>
        <p class="text-xs text-slate-500">Setujui dosen pilihan mahasiswa atau alihkan ke dosen lain (Maks. 7 mahasiswa/dosen).</p>
      </div>
    </div>

    <div class="space-y-3">
      @forelse($pendingApplications as $app)
        @php
          $mInitials = collect(explode(' ', $app->student->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
          $targetDosen = $app->dosen;
          $targetCount = $targetDosen ? \App\Models\User::where('role', 'mahasiswa')->where('dosen_id', $targetDosen->id)->count() : 0;
          $isQuotaAvailable = $targetCount < 7;
        @endphp
        <div class="p-4 rounded-xl bg-amber-50/30 border border-amber-200/80 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
          <div class="flex items-start gap-3.5 min-w-0">
            <div class="w-11 h-11 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
              {{ $mInitials }}
            </div>
            <div class="min-w-0 space-y-1">
              <div class="flex items-center gap-2 flex-wrap">
                <h4 class="text-sm sm:text-base font-bold text-slate-800">{{ $app->student->name }}</h4>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-[11px] font-mono font-semibold">{{ $app->student->identifier }}</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Menunggu Penetapan KPS</span>
              </div>
              <p class="text-xs text-slate-600">
                <strong>{{ $app->company_name ?? 'Instansi PKL' }}</strong> — {{ $app->internship_title ?? '-' }}
              </p>
              <div class="flex items-center gap-2 text-xs pt-0.5">
                <span class="text-slate-500">Preferensi:</span>
                <span class="font-bold text-[#0056B3]">{{ $targetDosen->name ?? 'Belum memilih' }}</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ $isQuotaAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                  {{ $targetCount }}/7 {{ $isQuotaAvailable ? 'Tersedia' : 'Penuh' }}
                </span>
              </div>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2 shrink-0 justify-end">
            @if($isQuotaAvailable)
              <form action="{{ route('kps.f1.approve', $app->id) }}" method="POST" onsubmit="return confirm('Tetapkan {{ addslashes($app->student->name) }} ke {{ addslashes($targetDosen->name) }}?')">
                @csrf
                <input type="hidden" name="dosen_id" value="{{ $targetDosen->id }}">
                <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[16px]">gavel</span>
                  <span>Tetapkan</span>
                </button>
              </form>
            @else
              <span class="text-xs text-rose-600 font-bold px-2.5 py-1.5 bg-rose-50 rounded-lg border border-rose-200">Kuota Penuh</span>
            @endif

            <button type="button" 
                    onclick="openReassignModal('{{ $app->id }}', '{{ addslashes($app->student->name) }}', '{{ $app->student->identifier }}', '{{ $targetDosen->id ?? '' }}', '{{ addslashes($targetDosen->name ?? '') }}')"
                    class="bg-[#02796B] hover:bg-[#006054] text-white px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
              <span>Alihkan</span>
            </button>

            <button type="button" 
                    onclick="openRejectModal('{{ $app->id }}', '{{ addslashes($app->student->name) }}')"
                    class="p-2 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 rounded-xl" title="Tolak">
              <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
          </div>
        </div>
      @empty
        <div class="p-6 text-center bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-500">
          Tidak ada pengajuan Form F1 yang menunggu penetapan.
        </div>
      @endforelse
    </div>

    @if(count($approvedApplications ?? []) > 0)
      <div class="pt-4 border-t border-slate-100 space-y-2.5">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Riwayat Penetapan Terbaru ({{ count($approvedApplications) }})</h4>
        <div class="overflow-x-auto">
          <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
              <tr>
                <th class="py-2 px-3">Mahasiswa</th>
                <th class="py-2 px-3">NIM</th>
                <th class="py-2 px-3">Instansi PKL</th>
                <th class="py-2 px-3">Dosen Pembimbing</th>
                <th class="py-2 px-3 text-right">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach($approvedApplications as $app)
                <tr>
                  <td class="py-2 px-3 font-bold text-slate-800">{{ $app->student->name }}</td>
                  <td class="py-2 px-3 font-mono text-slate-600">{{ $app->student->identifier }}</td>
                  <td class="py-2 px-3 text-slate-600">{{ $app->company_name ?? '-' }}</td>
                  <td class="py-2 px-3 font-semibold text-[#0056B3]">{{ $app->dosen->name ?? '-' }}</td>
                  <td class="py-2 px-3 text-right">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Ditetapkan</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>
</div>

<!-- TAB 2: PLOTTING LANGSUNG -->
<div id="tab-content-langsung" class="kps-tab-pane hidden space-y-5">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <div>
        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Plotting Langsung Mahasiswa</h3>
        <p class="text-xs text-slate-500">Tetapkan dosen pembimbing secara langsung bagi mahasiswa yang belum terplot.</p>
      </div>
      <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#0056B3] font-bold text-xs">
        {{ count($unassignedStudents) }} Belum Terplot
      </span>
    </div>

    <form action="{{ route('kps.student.plot') }}" method="POST" class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
        <div>
          <label for="plot_student_id" class="block text-xs font-bold text-slate-700 mb-1">Mahasiswa <span class="text-rose-500">*</span></label>
          <select name="student_id" id="plot_student_id" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs">
            <option value="" disabled selected>-- Pilih Mahasiswa --</option>
            @foreach($unassignedStudents as $st)
              <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->identifier }})</option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="plot_dosen_id" class="block text-xs font-bold text-slate-700 mb-1">Dosen Pembimbing <span class="text-rose-500">*</span></label>
          <select name="dosen_id" id="plot_dosen_id" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs">
            <option value="" disabled selected>-- Pilih Dosen Pembimbing --</option>
            @foreach($availableDosen as $d)
              <option value="{{ $d->id }}">{{ $d->name }} (Sisa: {{ 7 - ($d->students_count ?? 0) }}/7)</option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="plot_company_name" class="block text-xs font-bold text-slate-700 mb-1">Perusahaan PKL (Opsional)</label>
          <input type="text" name="company_name" id="plot_company_name" placeholder="Nama Perusahaan" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs">
        </div>

        <div>
          <label for="plot_internship_title" class="block text-xs font-bold text-slate-700 mb-1">Topik PKL (Opsional)</label>
          <input type="text" name="internship_title" id="plot_internship_title" placeholder="Topik Magang" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs">
        </div>
      </div>

      <div class="flex justify-end pt-2 border-t border-slate-200">
        <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs font-bold">
          Tetapkan Mahasiswa
        </button>
      </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      @forelse($unassignedStudents as $st)
        <div class="p-3.5 rounded-xl bg-white border border-slate-200 flex items-center justify-between gap-3">
          <div class="min-w-0">
            <span class="text-xs font-bold text-slate-800 block truncate">{{ $st->name }}</span>
            <span class="text-[11px] text-slate-500 font-mono">NIM: {{ $st->identifier }}</span>
          </div>
          <button type="button" onclick="selectStudentForPlotting('{{ $st->id }}')" class="px-3 py-1.5 rounded-lg bg-blue-50 text-[#0056B3] hover:bg-[#0056B3] hover:text-white text-xs font-bold shrink-0">
            Pilih
          </button>
        </div>
      @empty
        <div class="col-span-2 p-5 text-center bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-500">
          Seluruh mahasiswa sudah memiliki dosen pembimbing.
        </div>
      @endforelse
    </div>
  </div>
</div>

<!-- TAB 3: DISTRIBUSI KUOTA DOSEN -->
<div id="tab-content-kuota" class="kps-tab-pane hidden space-y-5">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <div>
        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Distribusi Kuota Dosen Pembimbing</h3>
        <p class="text-xs text-slate-500">Kapasitas maksimal 7 mahasiswa per dosen.</p>
      </div>
      <span class="text-xs px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold">{{ $totalDosen }} Dosen</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      @foreach($allDosen as $d)
        @php
          $assignedCount = $d->students_count ?? 0;
          $percent = round(($assignedCount / 7) * 100);
          $isFull = $assignedCount >= 7;
        @endphp
        <div class="p-4 rounded-xl bg-white border {{ $isFull ? 'border-rose-200' : 'border-slate-200' }} space-y-3">
          <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
              <h4 class="text-sm font-bold text-slate-800 truncate">{{ $d->name }}</h4>
              <span class="text-[11px] text-slate-500 font-mono">NIP: {{ $d->identifier ?? '-' }}</span>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isFull ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
              {{ $assignedCount }}/7
            </span>
          </div>

          <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
            <div class="h-full rounded-full {{ $isFull ? 'bg-rose-500' : ($assignedCount >= 5 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $percent) }}%"></div>
          </div>

          <div class="space-y-1.5 pt-1">
            @forelse($d->students ?? [] as $st)
              <div class="p-2 rounded-lg bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-2 text-xs">
                <div class="min-w-0">
                  <span class="font-bold text-slate-800 truncate block">{{ $st->name }} ({{ $st->identifier }})</span>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                  <button type="button" onclick="openMoveLecturerModal('{{ $st->id }}', '{{ addslashes($st->name) }}', '{{ $d->id }}', '{{ addslashes($d->name) }}')" class="p-1 text-blue-600 hover:bg-blue-50 rounded" title="Pindahkan Dosen">
                    <span class="material-symbols-outlined text-[15px]">swap_horiz</span>
                  </button>
                  <form action="{{ route('kps.student.unassign', $st->id) }}" method="POST" onsubmit="return confirm('Lepaskan {{ addslashes($st->name) }} dari bimbingan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1 text-rose-600 hover:bg-rose-50 rounded" title="Lepas Penempatan">
                      <span class="material-symbols-outlined text-[15px]">person_remove</span>
                    </button>
                  </form>
                </div>
              </div>
            @empty
              <p class="text-[11px] text-slate-400 italic text-center py-1.5">Belum ada mahasiswa bimbingan.</p>
            @endforelse
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- TAB 4: REKAPITULASI SELURUH MAHASISWA -->
<div id="tab-content-rekap" class="kps-tab-pane hidden space-y-5">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-card space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
      <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Rekapitulasi Seluruh Mahasiswa</h3>
      <div class="relative w-full sm:w-60">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[17px]">search</span>
        <input type="text" id="master-student-search" onkeyup="filterMasterTable()" placeholder="Cari nama atau NIM..." class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-xs text-left" id="master-students-table">
        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase text-[11px]">
          <tr>
            <th class="py-2.5 px-3">Mahasiswa</th>
            <th class="py-2.5 px-3">NIM</th>
            <th class="py-2.5 px-3">Instansi PKL</th>
            <th class="py-2.5 px-3">Status</th>
            <th class="py-2.5 px-3">Dosen Pembimbing</th>
            <th class="py-2.5 px-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach($allStudents as $st)
            @php
              $hasDosen = !empty($st->dosen_id);
              $f1 = $st->latestF1Application;
              $isPending = ($f1 && $f1->status === 'pending');
            @endphp
            <tr class="hover:bg-slate-50/80 master-row" data-search="{{ strtolower($st->name . ' ' . $st->identifier . ' ' . ($st->dosen->name ?? '')) }}">
              <td class="py-2.5 px-3 font-bold text-slate-800">{{ $st->name }}</td>
              <td class="py-2.5 px-3 font-mono text-slate-600">{{ $st->identifier }}</td>
              <td class="py-2.5 px-3 text-slate-600">{{ $f1?->company_name ?? '-' }}</td>
              <td class="py-2.5 px-3">
                @if($hasDosen)
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Sudah Ditetapkan</span>
                @elseif($isPending)
                  <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">Menunggu Penetapan KPS</span>
                @else
                  <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium text-[10px]">Belum Mengajukan</span>
                @endif
              </td>
              <td class="py-2.5 px-3 font-semibold text-slate-800">
                {{ $st->dosen->name ?? ($f1?->dosen?->name ? $f1->dosen->name . ' (Preferensi)' : '-') }}
              </td>
              <td class="py-2.5 px-3 text-right">
                @if($hasDosen)
                  <button type="button" onclick="openMoveLecturerModal('{{ $st->id }}', '{{ addslashes($st->name) }}', '{{ $st->dosen_id }}', '{{ addslashes($st->dosen->name ?? '') }}')" class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#0056B3] hover:bg-[#0056B3] hover:text-white text-[11px] font-bold">
                    Ubah Dosen
                  </button>
                @elseif($isPending)
                  <button type="button" onclick="switchTab('plotting')" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 text-[11px] font-bold">
                    Proses F1
                  </button>
                @else
                  <button type="button" onclick="selectStudentForPlotting('{{ $st->id }}')" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-[11px] font-bold">
                    Plot
                  </button>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL: Alihkan Dosen Pembimbing F1 -->
<div id="modal-reassign-f1" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-scale-up">
    <div class="flex items-start justify-between border-b border-slate-100 pb-3">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Alihkan ke Dosen Lain</h3>
        <p class="text-xs text-slate-500 mt-0.5" id="reassign-modal-subtitle"></p>
      </div>
      <button onclick="closeModal('modal-reassign-f1')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-reassign-f1" method="POST" class="space-y-3.5">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Dosen Pembimbing:</label>
        <select name="dosen_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
          <option value="" disabled selected>-- Pilih Dosen Pembimbing --</option>
          @foreach($availableDosen as $d)
            <option value="{{ $d->id }}">{{ $d->name }} (Sisa: {{ 7 - ($d->students_count ?? 0) }}/7)</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan (Opsional):</label>
        <textarea name="notes" rows="2" placeholder="Catatan pengalihan..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeModal('modal-reassign-f1')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs font-bold">Tetapkan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Pindahkan Dosen Pembimbing -->
<div id="modal-move-lecturer" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-scale-up">
    <div class="flex items-start justify-between border-b border-slate-100 pb-3">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Ubah Dosen Pembimbing</h3>
        <p class="text-xs text-slate-500 mt-0.5" id="move-modal-subtitle"></p>
      </div>
      <button onclick="closeModal('modal-move-lecturer')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-move-lecturer" method="POST" class="space-y-3.5">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Dosen Pengganti:</label>
        <select name="new_dosen_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
          <option value="" disabled selected>-- Pilih Dosen Pengganti --</option>
          @foreach($availableDosen as $d)
            <option value="{{ $d->id }}">{{ $d->name }} (Sisa: {{ 7 - ($d->students_count ?? 0) }}/7)</option>
          @endforeach
        </select>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeModal('modal-move-lecturer')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="bg-[#02796B] hover:bg-[#006054] text-white px-4 py-2 rounded-xl text-xs font-bold">Pindahkan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Tolak Pengajuan F1 -->
<div id="modal-reject-f1" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-scale-up">
    <div class="flex items-start justify-between border-b border-slate-100 pb-3">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Tolak Pengajuan Form F1</h3>
        <p class="text-xs text-slate-500 mt-0.5" id="reject-modal-subtitle"></p>
      </div>
      <button onclick="closeModal('modal-reject-f1')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <form id="form-reject-f1" method="POST" class="space-y-3.5">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
        <textarea name="notes" rows="3" required placeholder="Alasan penolakan..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeModal('modal-reject-f1')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl text-xs font-bold">Tolak</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function switchTab(tabId) {
    document.querySelectorAll('.kps-tab-pane').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.kps-tab-btn').forEach(btn => {
      btn.className = "kps-tab-btn px-4 py-2 rounded-xl text-xs font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-slate-50 transition-all";
    });

    const activePane = document.getElementById('tab-content-' + tabId);
    const activeBtn = document.getElementById('tab-btn-' + tabId);
    if (activePane) activePane.classList.remove('hidden');
    if (activeBtn) {
      activeBtn.className = "kps-tab-btn px-4 py-2 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all bg-[#0056B3] text-white shadow-sm";
    }

    const url = new URL(window.location);
    url.searchParams.set('tab', tabId);
    window.history.replaceState({}, '', url);
  }

  function openModal(modalId) {
    const m = document.getElementById(modalId);
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
  }

  function closeModal(modalId) {
    const m = document.getElementById(modalId);
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
  }

  function openReassignModal(appId, studentName, studentNim, currentDosenId, currentDosenName) {
    document.getElementById('form-reassign-f1').action = `/kps/f1/${appId}/approve`;
    document.getElementById('reassign-modal-subtitle').innerText = `${studentName} (${studentNim}) — Preferensi: ${currentDosenName}`;
    openModal('modal-reassign-f1');
  }

  function openMoveLecturerModal(studentId, studentName, currentDosenId, currentDosenName) {
    document.getElementById('form-move-lecturer').action = `/kps/students/${studentId}/reassign`;
    document.getElementById('move-modal-subtitle').innerText = `${studentName} — Dosen saat ini: ${currentDosenName}`;
    openModal('modal-move-lecturer');
  }

  function openRejectModal(appId, studentName) {
    document.getElementById('form-reject-f1').action = `/kps/f1/${appId}/reject`;
    document.getElementById('reject-modal-subtitle').innerText = `Mahasiswa: ${studentName}`;
    openModal('modal-reject-f1');
  }

  function selectStudentForPlotting(studentId) {
    switchTab('langsung');
    const select = document.getElementById('plot_student_id');
    if (select) { select.value = studentId; select.focus(); }
  }

  function filterMasterTable() {
    const query = (document.getElementById('master-student-search')?.value || '').toLowerCase();
    document.querySelectorAll('.master-row').forEach(row => {
      const searchVal = row.getAttribute('data-search') || '';
      row.style.display = searchVal.includes(query) ? '' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab && ['plotting', 'langsung', 'kuota', 'rekap'].includes(tab)) {
      switchTab(tab);
    }
  });
</script>
@endpush
