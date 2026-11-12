@extends('layouts.app', [
  'title' => 'Form F2: Matriks Tugas Industri - SIM-PKL JTIK PNJ',
  'pageTitle' => 'Form F2: Matriks Tugas Industri',
  'breadcrumbParent' => 'Portal Mahasiswa',
  'activePage' => 'f2'
])

@section('content')
<!-- Info Penempatan -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-3.5">
    <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#02796B] border border-teal-100 flex items-center justify-center shrink-0">
      <span class="material-symbols-outlined text-[24px]">domain</span>
    </div>
    <div>
      <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B]">Mitra Industri (DUDI)</span>
      <h3 class="text-sm sm:text-base font-bold text-slate-800 font-heading">{{ $pengajuan?->industri?->nama_perusahaan ?? $pengajuan?->nama_perusahaan ?? 'Belum Terdaftar' }}</h3>
      <p class="text-xs text-slate-500">Dosen Pembimbing: {{ $pengajuan?->dosen?->name ?? 'Belum Ditetapkan' }}</p>
    </div>
  </div>

  @if($pengajuan)
    <a href="{{ route('dokumen.f2', $pengajuan->id) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1.5">
      <span class="material-symbols-outlined text-[16px]">print</span>
      <span>Unduh PDF F2</span>
    </a>
  @endif
</div>

<!-- Kanban Matriks Tugas F2 -->
<div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-card space-y-4">
  <div class="flex items-center justify-between pb-3 border-b border-slate-100">
    <div>
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800">Matriks Tugas &amp; Target Kompetensi (Form F2)</h2>
      <p class="text-xs text-slate-500">Daftar spesifikasi tugas dari Pembimbing Industri.</p>
    </div>
    <span class="text-xs font-bold px-2.5 py-1 bg-blue-50 text-[#0056B3] rounded-lg">
      {{ $allTasks->count() }} Modul
    </span>
  </div>

  @if($allTasks->isEmpty())
    <div class="text-center py-10 bg-slate-50/60 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
      Belum ada modul tugas pada Form F2.
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- To-Do -->
      <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
          <span class="text-xs font-bold text-slate-700">To-Do (Rencana)</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $tasksTodo->count() }}</span>
        </div>
        @forelse($tasksTodo as $task)
          <div class="bg-white p-3.5 rounded-xl border border-slate-200 space-y-1.5 shadow-sm">
            <div class="flex items-start justify-between gap-2">
              <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $task->modul_pekerjaan ?? $task->judul_tugas }}</h4>
              @if($task->target_minggu)
                <span class="shrink-0 text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold">
                  {{ is_numeric($task->target_minggu) ? 'Minggu '.$task->target_minggu : $task->target_minggu }}
                </span>
              @endif
            </div>
            <p class="text-xs text-slate-500">{{ $task->spesifikasi_kebutuhan ?? $task->deskripsi }}</p>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
        @endforelse
      </div>

      <!-- In Progress -->
      <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
          <span class="text-xs font-bold text-amber-800">Progres (Sedang Berjalan)</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">{{ $tasksProgress->count() }}</span>
        </div>
        @forelse($tasksProgress as $task)
          <div class="bg-white p-3.5 rounded-xl border border-amber-200/80 space-y-1.5 shadow-sm">
            <div class="flex items-start justify-between gap-2">
              <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $task->modul_pekerjaan ?? $task->judul_tugas }}</h4>
              @if($task->target_minggu)
                <span class="shrink-0 text-[10px] px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold">
                  {{ is_numeric($task->target_minggu) ? 'Minggu '.$task->target_minggu : $task->target_minggu }}
                </span>
              @endif
            </div>
            <p class="text-xs text-slate-500">{{ $task->spesifikasi_kebutuhan ?? $task->deskripsi }}</p>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic text-center py-3">Kosong</p>
        @endforelse
      </div>

      <!-- Done -->
      <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
          <span class="text-xs font-bold text-teal-800">Selesai (ACC)</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">{{ $tasksDone->count() }}</span>
        </div>
        @forelse($tasksDone as $task)
          <div class="bg-white p-3.5 rounded-xl border border-teal-200/80 space-y-1.5 shadow-sm">
            <div class="flex items-start justify-between gap-2">
              <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $task->modul_pekerjaan ?? $task->judul_tugas }}</h4>
              @if($task->target_minggu)
                <span class="shrink-0 text-[10px] px-2 py-0.5 rounded bg-teal-50 text-[#02796B] font-semibold">
                  {{ is_numeric($task->target_minggu) ? 'Minggu '.$task->target_minggu : $task->target_minggu }}
                </span>
              @endif
            </div>
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
