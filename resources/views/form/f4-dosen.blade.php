@extends('layouts.app', [
  'title' => 'Form F4: Buku Bimbingan Kampus Dosen - SIM-PKL Dosen',
  'pageTitle' => 'Form F4: Buku Bimbingan',
  'breadcrumbParent' => 'Portal Dosen TIK',
  'activePage' => 'f4'
])

@section('content')
<!-- Sub-header Breadcrumb & Header Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Dosen</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Monitoring Mahasiswa Bimbingan</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Form F4 (Buku Bimbingan Kampus)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F4: Buku Bimbingan Akademik Dosen
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Rekam sesi konsultasi berkala, arahan penyusunan laporan, dan pemenuhan syarat minimal 8 sesi bimbingan sebelum evaluasi akhir.
    </p>
  </div>

  <div class="flex items-center gap-3">
    @if($selectedPengajuan)
      <a href="{{ route('dokumen.f4', $selectedPengajuan->id) }}" target="_blank" class="h-10 px-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[18px]">file_download</span>
        <span>Unduh Form F4</span>
      </a>

      <button type="button" onclick="document.getElementById('modalAddBimbinganF4').classList.remove('hidden')" class="h-10 px-4 rounded-xl bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#005f54] text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-md">
        <span class="material-symbols-outlined text-[18px]">add_task</span>
        <span>+ Catat Sesi Bimbingan</span>
      </button>
    @endif
  </div>
</div>

<!-- Mahasiswa Selector Card -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-lg shadow-sm">
      {{ strtoupper(substr($selectedPengajuan?->mahasiswa?->name ?? 'M', 0, 2)) }}
    </div>
    <div>
      <div class="flex items-center gap-2">
        <h3 class="text-base font-bold text-slate-800 font-heading">{{ $selectedPengajuan?->mahasiswa?->name ?? 'Belum Ada Mahasiswa Bimbingan' }}</h3>
        <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-xs font-mono font-semibold">NIM: {{ $selectedPengajuan?->mahasiswa?->nim ?? '-' }}</span>
      </div>
      <p class="text-xs text-slate-500 mt-0.5">{{ $selectedPengajuan?->nama_perusahaan ?? '-' }} • {{ $selectedPengajuan?->alamat_perusahaan ?? '-' }}</p>
    </div>
  </div>

  <div class="flex items-center gap-3">
    @if($students->count() > 1)
      <select onchange="window.location.href='{{ route('form.f4.dosen') }}?pengajuan_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-300 text-xs font-medium bg-slate-50 text-slate-700">
        @foreach($students as $st)
          <option value="{{ $st->id }}" {{ ($selectedPengajuan && $selectedPengajuan->id == $st->id) ? 'selected' : '' }}>
            {{ $st->mahasiswa->name }} ({{ $st->nama_perusahaan }})
          </option>
        @endforeach
      </select>
    @endif

    <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-center">
      <span class="text-[10px] text-slate-400 font-bold uppercase block">Progres Bimbingan</span>
      <span class="text-sm font-extrabold {{ $totalSessions >= 8 ? 'text-emerald-600' : 'text-amber-700' }}">{{ $totalSessions }}/8 Sesi</span>
    </div>
  </div>
</div>

<!-- Guidance Sesi List -->
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card space-y-5">
  <div class="flex items-center justify-between pb-4 border-b border-slate-100">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Catatan Konsultasi &amp; Validasi Naskah Laporan (Form F4)</h2>
      <p class="text-xs text-slate-500">Standar Kurikulum PKL TIK 2026: Minimal 8 kali bimbingan berkala sebelum evaluasi akhir Form F6/F7.</p>
    </div>
    <span class="text-xs font-bold px-3 py-1 {{ $totalSessions >= 8 ? 'bg-teal-50 text-[#02796B]' : 'bg-amber-50 text-amber-700' }} rounded-lg">
      {{ $totalSessions >= 8 ? 'Syarat 8 Sesi Terpenuhi' : 'Kurang ' . (8 - $totalSessions) . ' Sesi Lagi' }}
    </span>
  </div>

  @if($bimbingans->isEmpty())
    <div class="text-center py-12 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
      <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">history_edu</span>
      <h4 class="text-sm font-bold text-slate-700">Belum Ada Sesi Bimbingan yang Dicatat</h4>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Klik tombol "+ Catat Sesi Bimbingan" di atas untuk menambahkan catatan bimbingan akademik pertama mahasiswa ini.</p>
    </div>
  @else
    <div class="space-y-4">
      @foreach($bimbingans as $sesi)
        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 items-start">
          <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white font-bold text-base flex items-center justify-center shrink-0 shadow-sm">
            {{ $sesi->sesi_ke }}
          </div>
          <div class="flex-1 space-y-1.5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
              <div class="flex items-center gap-2">
                <h4 class="font-bold text-slate-800 text-sm sm:text-base font-heading">{{ $sesi->materi_bimbingan }}</h4>
                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-[#0056B3]">{{ $selectedPengajuan?->mahasiswa?->name }}</span>
              </div>
              <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($sesi->tanggal_bimbingan)->translatedFormat('d M Y') }}</span>
            </div>
            @if($sesi->progres_laporan)
              <p class="text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-200/60">
                <strong class="text-slate-700">Progres Laporan:</strong> {{ $sesi->progres_laporan }}
              </p>
            @endif
            @if($sesi->catatan_dosen)
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/60">
                <strong>Arahan Dosen:</strong> {{ $sesi->catatan_dosen }}
              </p>
            @endif
            <div class="flex items-center justify-between pt-1">
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
                  <span class="material-symbols-outlined text-[13px]">verified</span> Terverifikasi Dosen
                </span>
                <span class="text-xs font-mono text-slate-400">NIP: {{ Auth::user()->nip ?? '-' }}</span>
              </div>
              <form method="POST" action="{{ route('bimbingan_f4.destroy', $sesi->id) }}" onsubmit="return confirm('Hapus sesi bimbingan ke-{{ $sesi->sesi_ke }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<!-- Modal Catat Sesi Bimbingan F4 -->
@if($selectedPengajuan)
  <div id="modalAddBimbinganF4" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h3 class="text-base font-bold text-slate-800">Catat Sesi Bimbingan Baru (Form F4)</h3>
        <button type="button" onclick="document.getElementById('modalAddBimbinganF4').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form method="POST" action="{{ route('bimbingan_f4.store', $selectedPengajuan->id) }}" class="space-y-4 mt-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Sesi Bimbingan</label>
          <input type="date" name="tanggal_bimbingan" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Pokok Bahasan / Materi Konsultasi</label>
          <input type="text" name="materi_bimbingan" placeholder="Contoh: Asistensi Bab III Metodologi & Arsitektur Microservices" required class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Capaian Progres Mahasiswa</label>
          <input type="text" name="progres_laporan" placeholder="Contoh: Draft Bab III selesai 70%, diagram sequence telah direvisi" class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Catatan &amp; Arahan Revisi Dosen</label>
          <textarea name="catatan_dosen" rows="3" placeholder="Arahan perbaikan dan target untuk pertemuan berikutnya..." class="w-full text-xs rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
          <button type="button" onclick="document.getElementById('modalAddBimbinganF4').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Batal
          </button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white text-xs font-bold shadow-sm">
            Simpan Sesi Bimbingan
          </button>
        </div>
      </form>
    </div>
  </div>
@endif
@endsection
