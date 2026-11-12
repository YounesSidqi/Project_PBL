@extends('layouts.app', [
  'title' => 'Form F2: Matriks Tugas Industri - SIM-PKL Dosen',
  'pageTitle' => 'Form F2: Matriks Tugas Industri',
  'breadcrumbParent' => 'Portal Dosen',
  'activePage' => 'f2'
])

@section('content')
<!-- Pemilih Mahasiswa -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-3.5">
    <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-sm shrink-0">
      {{ strtoupper(substr($selectedPengajuan?->mahasiswa?->name ?? 'M', 0, 2)) }}
    </div>
    <div>
      <div class="flex items-center gap-2">
        <h3 class="text-sm sm:text-base font-bold text-slate-800 font-heading">{{ $selectedPengajuan?->mahasiswa?->name ?? 'Belum Ada Mahasiswa Bimbingan' }}</h3>
        @if($selectedPengajuan?->mahasiswa)
          <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-xs font-mono font-semibold">{{ $selectedPengajuan->mahasiswa->identifier }}</span>
        @endif
      </div>
      <p class="text-xs text-slate-500 mt-0.5">{{ $selectedPengajuan?->industri?->nama_perusahaan ?? $selectedPengajuan?->nama_perusahaan ?? '-' }}</p>
    </div>
  </div>

  <div class="flex items-center gap-2.5">
    @if($students->count() > 1)
      <select onchange="window.location.href='{{ route('form.f2.dosen') }}?pengajuan_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-200 text-xs font-semibold bg-slate-50 text-slate-700">
        @foreach($students as $st)
          <option value="{{ $st->id }}" {{ ($selectedPengajuan && $selectedPengajuan->id == $st->id) ? 'selected' : '' }}>
            {{ $st->mahasiswa->name }}
          </option>
        @endforeach
      </select>
    @endif

    @if($selectedPengajuan)
      <a href="{{ route('dokumen.f2', $selectedPengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px]">print</span>
        <span>Unduh F2</span>
      </a>
    @endif
  </div>
</div>

<!-- Kanban Matriks Kompetensi F2 -->
<div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-card space-y-4">
  <div class="flex items-center justify-between pb-3 border-b border-slate-100">
    <div>
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800">Capaian Tugas Industri (Form F2)</h2>
      <p class="text-xs text-slate-500">Pemantauan penyelesaian modul tugas mahasiswa di tempat PKL.</p>
    </div>
    <span class="text-xs font-bold px-2.5 py-1 bg-teal-50 text-[#02796B] rounded-lg">
      {{ $tasksDone->count() }} Selesai / {{ $allTasks->count() }} Total
    </span>
  </div>

  @if($allTasks->isEmpty())
    <div class="text-center py-10 bg-slate-50/60 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
      Mitra Industri belum menginput spesifikasi tugas Form F2.
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- To Do -->
      <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
          <span class="text-xs font-bold text-slate-700">To-Do</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">{{ $tasksTodo->count() }}</span>
        </div>
        @forelse($tasksTodo as $task)
          <div class="bg-white p-3.5 rounded-xl border border-slate-200 space-y-1">
            <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $task->modul_pekerjaan ?? $task->judul_tugas }}</h4>
            <p class="text-xs text-slate-500">{{ $task->spesifikasi_kebutuhan ?? $task->deskripsi }}</p>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
        @endforelse
      </div>

      <!-- In Progress -->
      <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
          <span class="text-xs font-bold text-amber-800">Sedang Berjalan</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">{{ $tasksProgress->count() }}</span>
        </div>
        @forelse($tasksProgress as $task)
          <div class="bg-white p-3.5 rounded-xl border border-slate-200 space-y-1">
            <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $task->modul_pekerjaan ?? $task->judul_tugas }}</h4>
            <p class="text-xs text-slate-500">{{ $task->spesifikasi_kebutuhan ?? $task->deskripsi }}</p>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
        @endforelse
      </div>

      <!-- Done -->
      <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
          <span class="text-xs font-bold text-teal-800">Selesai (ACC DUDI)</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">{{ $tasksDone->count() }}</span>
        </div>
        @forelse($tasksDone as $task)
          <div class="bg-white p-3.5 rounded-xl border border-slate-200 space-y-1">
            <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $task->modul_pekerjaan ?? $task->judul_tugas }}</h4>
            <p class="text-xs text-slate-500">{{ $task->spesifikasi_kebutuhan ?? $task->deskripsi }}</p>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
        @endforelse
      </div>
    </div>
  @endif
</div>
@endsection
