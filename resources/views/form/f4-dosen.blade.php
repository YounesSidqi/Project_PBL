@extends('layouts.app', [
  'title' => 'Form F4: Buku Bimbingan - SIM-PKL',
  'pageTitle' => 'Form F4: Buku Bimbingan',
  'breadcrumbParent' => 'Dosen Pembimbing',
  'activePage' => 'f4'
])

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="text-xl font-bold font-heading text-slate-900">Form F4: Buku Bimbingan Akademik</h2>
    <p class="text-xs text-slate-500 mt-0.5">Pencatatan konsultasi laporan PKL mahasiswa (syarat minimal 8 sesi).</p>
  </div>

  @if($selectedPengajuan)
    <div class="flex items-center gap-2">
      <a href="{{ route('dokumen.f4', $selectedPengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors inline-flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">file_download</span>
        <span>Unduh PDF</span>
      </a>

      <button type="button" onclick="document.getElementById('modalAddBimbinganF4').classList.remove('hidden')" class="h-9 px-3.5 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">add</span>
        <span>Catat Sesi Bimbingan</span>
      </button>
    </div>
  @endif
</div>

<!-- Pemilih Mahasiswa -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-3.5">
    <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-sm shrink-0">
      {{ strtoupper(substr($selectedPengajuan?->mahasiswa?->name ?? 'M', 0, 2)) }}
    </div>
    <div>
      <div class="flex items-center gap-2 flex-wrap">
        <h3 class="text-sm font-bold text-slate-800">{{ $selectedPengajuan?->mahasiswa?->name ?? 'Belum Ada Mahasiswa Bimbingan' }}</h3>
        @if($selectedPengajuan)
          <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-mono font-semibold">
            NIM: {{ $selectedPengajuan->mahasiswa?->identifier ?? $selectedPengajuan->mahasiswa?->nim ?? '-' }}
          </span>
        @endif
      </div>
      <p class="text-xs text-slate-500 mt-0.5">
        {{ $selectedPengajuan?->industri?->nama_perusahaan ?? $selectedPengajuan?->nama_perusahaan ?? '-' }}
      </p>
    </div>
  </div>

  <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
    @if($students->count() > 1)
      <select onchange="window.location.href='{{ route('form.f4.dosen') }}?pengajuan_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-200 text-xs font-semibold bg-slate-50 text-slate-700">
        @foreach($students as $st)
          <option value="{{ $st->id }}" {{ ($selectedPengajuan && $selectedPengajuan->id == $st->id) ? 'selected' : '' }}>
            {{ $st->mahasiswa->name }} ({{ $st->industri?->nama_perusahaan ?? $st->nama_perusahaan }})
          </option>
        @endforeach
      </select>
    @endif

    <div class="px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-center shrink-0">
      <span class="text-[10px] text-slate-400 font-bold uppercase block">Progres</span>
      <span class="text-xs font-extrabold {{ $totalSessions >= 8 ? 'text-emerald-600' : 'text-amber-700' }}">{{ $totalSessions }}/8 Sesi</span>
    </div>
  </div>
</div>

<!-- Daftar Sesi Bimbingan -->
<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-4">
  <div class="flex items-center justify-between pb-3 border-b border-slate-100">
    <h3 class="text-sm font-bold font-heading text-slate-800">Riwayat Bimbingan (Form F4)</h3>
    <span class="text-xs font-bold px-2.5 py-1 {{ $totalSessions >= 8 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} rounded-lg">
      {{ $totalSessions >= 8 ? 'Syarat 8 Sesi Terpenuhi' : 'Kurang ' . (8 - $totalSessions) . ' Sesi' }}
    </span>
  </div>

  @if($bimbingans->isEmpty())
    <div class="text-center py-10 bg-slate-50/60 rounded-xl border border-dashed border-slate-200">
      <span class="material-symbols-outlined text-3xl text-slate-300 mb-1.5 block">history_edu</span>
      <h4 class="text-sm font-bold text-slate-700">Belum Ada Sesi Bimbingan</h4>
      <p class="text-xs text-slate-500 mt-0.5">Klik tombol "Catat Sesi Bimbingan" di atas untuk menambahkan catatan baru.</p>
    </div>
  @else
    <div class="space-y-3">
      @foreach($bimbingans as $sesi)
        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200/80 flex gap-3.5 items-start">
          <div class="w-9 h-9 rounded-xl bg-[#0056B3] text-white font-bold text-xs flex items-center justify-center shrink-0">
            #{{ $sesi->sesi_ke }}
          </div>
          <div class="flex-1 min-w-0 space-y-1.5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
              <h4 class="font-bold text-slate-800 text-sm">{{ $sesi->materi_bimbingan }}</h4>
              <div class="flex items-center gap-3 shrink-0">
                <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($sesi->tanggal_bimbingan)->translatedFormat('d M Y') }}</span>
                <form method="POST" action="{{ route('bimbingan_f4.destroy', $sesi->id) }}" onsubmit="return confirm('Hapus sesi bimbingan ke-{{ $sesi->sesi_ke }}?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">delete</span>
                    <span>Hapus</span>
                  </button>
                </form>
              </div>
            </div>
            @if($sesi->progres_laporan)
              <p class="text-xs text-slate-600 bg-white px-3 py-2 rounded-lg border border-slate-200/60">
                <span class="font-semibold text-slate-700">Progres:</span> {{ $sesi->progres_laporan }}
              </p>
            @endif
            @if($sesi->catatan_dosen)
              <p class="text-xs text-slate-600 bg-white px-3 py-2 rounded-lg border border-slate-200/60">
                <span class="font-semibold text-slate-700">Arahan Dosen:</span> {{ $sesi->catatan_dosen }}
              </p>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<!-- Modal Catat Sesi Bimbingan F4 -->
@if($selectedPengajuan)
  <div id="modalAddBimbinganF4" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl border border-slate-100">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h3 class="text-sm font-bold text-slate-800">Catat Sesi Bimbingan (Form F4)</h3>
        <button type="button" onclick="document.getElementById('modalAddBimbinganF4').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
          <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
      </div>

      <form method="POST" action="{{ route('bimbingan_f4.store', $selectedPengajuan->id) }}" class="space-y-3.5 mt-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Bimbingan</label>
          <input type="date" name="tanggal_bimbingan" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Materi Bimbingan</label>
          <input type="text" name="materi_bimbingan" placeholder="Contoh: Asistensi Bab III Metodologi" required class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Progres Laporan</label>
          <input type="text" name="progres_laporan" placeholder="Contoh: Bab III selesai 70%" class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Catatan &amp; Arahan Dosen</label>
          <textarea name="catatan_dosen" rows="3" placeholder="Arahan revisi atau target berikutnya..." class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
          <button type="button" onclick="document.getElementById('modalAddBimbinganF4').classList.add('hidden')" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Batal
          </button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white text-xs font-bold shadow-sm">
            Simpan Sesi
          </button>
        </div>
      </form>
    </div>
  </div>
@endif
@endsection
