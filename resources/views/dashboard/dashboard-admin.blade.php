@extends('layouts.app', [
  'title' => 'Dashboard Admin Jurusan - SIM-PKL TIK Politeknik Negeri Jakarta',
  'pageTitle' => 'Dashboard Administrasi PKL Jurusan TIK',
  'breadcrumbParent' => 'Portal Admin Jurusan',
  'activePage' => 'admin'
])

@section('content')
<!-- Header & Notification Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>SIM-PKL JTIK PNJ</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Portal Admin Jurusan</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Pusat Kendali Administrasi PKL
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Kelola onboarding mahasiswa, penerbitan surat pengantar, registrasi mitra industri, dan manajemen kredensial.
    </p>
  </div>

  <div class="flex items-center gap-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-[#0056B3] border border-blue-200 text-xs font-bold shadow-sm">
      <span class="material-symbols-outlined text-[16px]">verified_user</span>
      <span>Biro Akademik JTIK PNJ</span>
    </span>
  </div>
</div>

<!-- Quick Stats Counter Row -->
<div class="grid grid-cols-2 lg:grid-cols-6 gap-3 mb-6">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
      <span class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Mahasiswa</span>
      <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
        <span class="material-symbols-outlined text-[18px]">school</span>
      </div>
    </div>
    <div class="text-2xl font-black font-heading text-slate-900 mt-2">{{ $stats['total_mahasiswa'] }}</div>
    <div class="text-[10px] text-slate-400 mt-0.5">Terdaftar di sistem</div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
      <span class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Dosen</span>
      <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
        <span class="material-symbols-outlined text-[18px]">person_check</span>
      </div>
    </div>
    <div class="text-2xl font-black font-heading text-slate-900 mt-2">{{ $stats['total_dosen'] }}</div>
    <div class="text-[10px] text-slate-400 mt-0.5">Dosen Pembimbing</div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
      <span class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Mitra DUDI</span>
      <div class="w-8 h-8 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
        <span class="material-symbols-outlined text-[18px]">domain</span>
      </div>
    </div>
    <div class="text-2xl font-black font-heading text-slate-900 mt-2">{{ $stats['total_industri'] }}</div>
    <div class="text-[10px] text-slate-400 mt-0.5">Perusahaan terdaftar</div>
  </div>

  <div class="bg-white rounded-2xl border border-amber-200/80 p-4 shadow-sm bg-gradient-to-br from-amber-50/40 to-white">
    <div class="flex items-center justify-between">
      <span class="text-amber-800 text-[11px] font-bold uppercase tracking-wider">Surat Pending</span>
      <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
        <span class="material-symbols-outlined text-[18px]">mail</span>
      </div>
    </div>
    <div class="text-2xl font-black font-heading text-amber-900 mt-2">{{ $stats['surat_pending'] }}</div>
    <div class="text-[10px] text-amber-700 font-semibold mt-0.5">Menunggu terbit</div>
  </div>

  <div class="bg-white rounded-2xl border border-emerald-200/80 p-4 shadow-sm bg-gradient-to-br from-emerald-50/40 to-white">
    <div class="flex items-center justify-between">
      <span class="text-emerald-800 text-[11px] font-bold uppercase tracking-wider">Surat Terbit</span>
      <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
        <span class="material-symbols-outlined text-[18px]">mark_email_read</span>
      </div>
    </div>
    <div class="text-2xl font-black font-heading text-emerald-900 mt-2">{{ $stats['surat_diterbitkan'] }}</div>
    <div class="text-[10px] text-emerald-700 font-semibold mt-0.5">Resmi ber-KOP</div>
  </div>

  <div class="bg-white rounded-2xl border border-purple-200/80 p-4 shadow-sm bg-gradient-to-br from-purple-50/40 to-white">
    <div class="flex items-center justify-between">
      <span class="text-purple-800 text-[11px] font-bold uppercase tracking-wider">DUDI Pending</span>
      <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
        <span class="material-symbols-outlined text-[18px]">business</span>
      </div>
    </div>
    <div class="text-2xl font-black font-heading text-purple-900 mt-2">{{ $stats['industri_pending'] }}</div>
    <div class="text-[10px] text-purple-700 font-semibold mt-0.5">Perlu verifikasi</div>
  </div>
</div>

<!-- Navigation Tabs -->
<div class="flex items-center gap-2 border-b border-slate-200 mb-6 overflow-x-auto pb-px">
  <a href="{{ route('dashboard.admin', ['tab' => 'surat']) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'surat' || $tab === 'overview' ? 'border-[#0056B3] text-[#0056B3]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">mark_email_read</span>
    <span>Verifikasi Surat Pengantar</span>
    @if($stats['surat_pending'] > 0)
      <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white">{{ $stats['surat_pending'] }}</span>
    @endif
  </a>

  <a href="{{ route('dashboard.admin', ['tab' => 'industri']) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'industri' ? 'border-[#0056B3] text-[#0056B3]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">domain_verification</span>
    <span>Registrasi &amp; Mitra Industri</span>
    @if($stats['industri_pending'] > 0)
      <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-600 text-white">{{ $stats['industri_pending'] }}</span>
    @endif
  </a>

  <a href="{{ route('dashboard.admin', ['tab' => 'mahasiswa']) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'mahasiswa' ? 'border-[#0056B3] text-[#0056B3]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">tune</span>
    <span>Full Control Mahasiswa &amp; Import</span>
  </a>

  <a href="{{ route('dashboard.admin', ['tab' => 'users']) }}"
     class="inline-flex items-center gap-2 px-4 py-3 border-b-2 text-xs sm:text-sm font-bold tracking-tight transition-all {{ $tab === 'users' ? 'border-[#0056B3] text-[#0056B3]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
    <span class="material-symbols-outlined text-[18px]">lock_reset</span>
    <span>Reset Password Pengguna</span>
  </a>
</div>

<!-- TAB 1: VERIFIKASI & TERBITKAN SURAT PENGANTAR (PRD 5.1 & 7.1) -->
@if($tab === 'surat' || $tab === 'overview')
@php
  $verifiedIndustris = $industris->where('status_verifikasi', 'acc');
@endphp
<div class="space-y-6">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div>
        <h2 class="text-lg font-bold font-heading text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-[#0056B3]">description</span>
          <span>Daftar Permintaan Surat Pengantar &amp; Penempatan Industri Mahasiswa</span>
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          Verifikasi perusahaan tujuan (ACC perusahaan baru atau forward ke mitra terdaftar), lalu terbitkan Surat Pengantar digital ber-KOP resmi PNJ.
        </p>
      </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
      <table class="w-full text-left text-xs text-slate-600">
        <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">Mahasiswa &amp; NIM</th>
            <th class="py-3 px-4">Perusahaan / Industri &amp; Status DUDI</th>
            <th class="py-3 px-4">Posisi &amp; Periode</th>
            <th class="py-3 px-4">Nomor Surat</th>
            <th class="py-3 px-4">Status Surat</th>
            <th class="py-3 px-4 text-center">Aksi Admin</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          @forelse($suratPengantars as $sp)
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="py-3.5 px-4">
              <div class="font-bold text-slate-900">{{ $sp->mahasiswa->name }}</div>
              <div class="text-[11px] font-mono text-slate-400">NIM: {{ $sp->mahasiswa->identifier }}</div>
              <div class="text-[10px] text-slate-500">{{ $sp->mahasiswa->prodi }}</div>
            </td>
            <td class="py-3.5 px-4">
              <div class="font-bold text-slate-800">{{ $sp->industri->nama_perusahaan ?? 'Belum ditentukan' }}</div>
              <div class="text-[10px] text-slate-500">
                {{ $sp->industri->kota ?? 'Jakarta' }}
                @if($sp->industri?->kontak_person)
                  • PIC: {{ $sp->industri->kontak_person }}
                @endif
              </div>
              <div class="mt-1">
                @if($sp->industri?->status_verifikasi === 'acc')
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 text-[#02796B] border border-teal-200">
                    <span class="material-symbols-outlined text-[12px]">verified</span> Mitra Terverifikasi
                  </span>
                @elseif($sp->industri?->status_verifikasi === 'reject')
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="material-symbols-outlined text-[12px]">cancel</span> Industri Ditolak
                  </span>
                @elseif($sp->industri)
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    <span class="material-symbols-outlined text-[12px]">pending</span> Industri Baru (Perlu ACC)
                  </span>
                @endif
              </div>
            </td>
            <td class="py-3.5 px-4">
              <div class="text-slate-800 font-semibold">{{ $sp->posisi_magang ?: 'Internship Track' }}</div>
              <div class="text-[10px] text-slate-400">
                @if($sp->tanggal_mulai && $sp->tanggal_selesai)
                  {{ $sp->tanggal_mulai->format('d M') }} - {{ $sp->tanggal_selesai->format('d M Y') }}
                @else
                  Semester Genap 2026
                @endif
              </div>
            </td>
            <td class="py-3.5 px-4">
              @if($sp->nomor_surat_pengantar)
                <span class="inline-block px-2.5 py-1 rounded-lg bg-blue-50 text-[#0056B3] font-mono text-[11px] font-bold border border-blue-200">
                  {{ $sp->nomor_surat_pengantar }}
                </span>
              @else
                <span class="text-slate-400 italic text-[11px]">Belum Diterbitkan</span>
              @endif
            </td>
            <td class="py-3.5 px-4">
              @if($sp->status_surat_pengantar === 'diterbitkan')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <span class="material-symbols-outlined text-[13px]">check_circle</span> Diterbitkan
                </span>
              @elseif($sp->status_surat_pengantar === 'pending')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                  <span class="material-symbols-outlined text-[13px]">schedule</span> Menunggu ACC
                </span>
              @elseif($sp->status_surat_pengantar === 'ditolak')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                  <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                  Draft
                </span>
              @endif
            </td>
            <td class="py-3.5 px-4 text-center">
              <div class="flex flex-wrap items-center justify-center gap-1.5">
                @if($sp->status_surat_pengantar === 'pending' || $sp->status_surat_pengantar === 'draft')
                  <form method="POST" action="{{ route('admin.surat.terbitkan', $sp->id) }}" onsubmit="return confirm('Accept pengajuan tempat PKL {{ $sp->industri->nama_perusahaan ?? '' }} & terbitkan Surat Pengantar resmi untuk {{ $sp->mahasiswa->name }}?')">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1 shadow-sm transition-colors">
                      <span class="material-symbols-outlined text-[15px]">check_circle</span> Accept &amp; Terbitkan
                    </button>
                  </form>

                  <!-- Tombol Tolak Modal Trigger -->
                  <button type="button" onclick="document.getElementById('modal-tolak-{{ $sp->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold text-xs border border-slate-200 transition-colors">
                    Tolak
                  </button>
                @endif

                <!-- Tombol Forward ke Industri Terdaftar (PRD 5.2) -->
                @if($verifiedIndustris->count() > 0)
                  <button type="button" onclick="document.getElementById('modal-forward-{{ $sp->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-[#02796B] border border-teal-200 font-bold text-xs flex items-center gap-1 transition-colors" title="Forward penugasan mahasiswa ke akun industri yang sudah terdaftar (PRD 5.2)">
                    <span class="material-symbols-outlined text-[15px]">forward</span> Forward Mitra
                  </button>
                @endif

                @if($sp->status_surat_pengantar === 'diterbitkan')
                  <!-- PRD 11.3: Reset ke Draft jika Ditolak Perusahaan -->
                  <button type="button" onclick="document.getElementById('modal-reset-draft-{{ $sp->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 font-bold text-xs flex items-center gap-1 transition-colors" title="Ditolak Perusahaan? Kembalikan status ke Draft Request (PRD 11.3)">
                    <span class="material-symbols-outlined text-[15px]">restart_alt</span> Reset ke Draft
                  </button>
                @endif

                <a href="{{ route('dokumen.surat-pengantar', $sp->id) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center gap-1 shadow-sm transition-colors">
                  <span class="material-symbols-outlined text-[15px]">print</span> Berkas KOP
                </a>

                <form method="POST" action="{{ route('admin.mahasiswa.destroy-pkl', $sp->mahasiswa_id) }}" onsubmit="return confirm('Hapus tempat PKL ({{ $sp->industri->nama_perusahaan ?? '' }}) untuk mahasiswa {{ $sp->mahasiswa->name }}? Mahasiswa akan kembali ke status Belum Ada Penempatan.')">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="redirect_tab" value="surat">
                  <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 font-bold text-xs flex items-center gap-1 transition-colors" title="Hapus Tempat PKL Mahasiswa">
                    <span class="material-symbols-outlined text-[15px]">delete</span> Hapus PKL
                  </button>
                </form>
              </div>

              <!-- Modal Forward Pengajuan ke Industri Terdaftar (PRD 5.2) -->
              <div id="modal-forward-{{ $sp->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-[#02796B] text-2xl">forward</span>
                    <h3 class="text-base font-bold text-slate-900">Forward ke Mitra Industri Terdaftar</h3>
                  </div>
                  <p class="text-xs text-slate-500 mb-4">
                    Sesuai <strong>PRD 5.2</strong>: Jika perusahaan tempat mahasiswa magang sudah terdaftar sebagai Mitra DUDI resmi, teruskan (forward) penugasan <strong>{{ $sp->mahasiswa->name }}</strong> ke akun industri yang sudah ada.
                  </p>

                  <form method="POST" action="{{ route('admin.pengajuan.forward', $sp->id) }}">
                    @csrf
                    <input type="hidden" name="redirect_tab" value="surat">
                    <div class="mb-4">
                      <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Akun Mitra Industri Terverifikasi</label>
                      <select name="industri_id" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-teal-500 focus:ring-teal-500">
                        @foreach($verifiedIndustris as $vInd)
                          <option value="{{ $vInd->id }}" {{ $sp->industri_id === $vInd->id ? 'selected' : '' }}>
                            {{ $vInd->nama_perusahaan }} (Akun: {{ $vInd->user->email ?? 'Aktif' }})
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                      <button type="button" onclick="document.getElementById('modal-forward-{{ $sp->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                      <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#02796B] hover:bg-teal-800 text-white shadow-sm">Forward Penugasan</button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Modal Tolak Permintaan -->
              <div id="modal-tolak-{{ $sp->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                  <h3 class="text-base font-bold text-slate-900 mb-1">Tolak Permintaan Surat Pengantar</h3>
                  <p class="text-xs text-slate-500 mb-4">Berikan alasan penolakan agar mahasiswa dapat memperbaiki data pengajuannya.</p>

                  <form method="POST" action="{{ route('admin.surat.tolak', $sp->id) }}">
                    @csrf
                    <div class="mb-4">
                      <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alasan Penolakan</label>
                      <textarea name="catatan_penolakan" rows="3" required placeholder="Contoh: Nama perusahaan belum lengkap atau kuota penerimaan instansi tersebut belum terkonfirmasi..." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                      <button type="button" onclick="document.getElementById('modal-tolak-{{ $sp->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                      <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-sm">Kirim Penolakan</button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Modal Reset ke Draft (PRD 11.3: Ditolak Industri) -->
              <div id="modal-reset-draft-{{ $sp->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-amber-600 text-2xl">restart_alt</span>
                    <h3 class="text-base font-bold text-slate-900">Kembalikan ke Draft Request</h3>
                  </div>
                  <p class="text-xs text-slate-500 mb-4">
                    Sesuai panduan <strong>PRD 11.3</strong>: Jika mahasiswa dinyatakan ditolak oleh perusahaan setelah Surat Pengantar diterbitkan, Admin Jurusan dapat mengembalikan status mahasiswa ke <em>Draft Request</em> agar mahasiswa dapat mengajukan industri mitra yang baru.
                  </p>

                  <form method="POST" action="{{ route('admin.pengajuan.reset-draft', $sp->id) }}">
                    @csrf
                    <div class="mb-4">
                      <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan / Keterangan Penolakan Perusahaan</label>
                      <textarea name="catatan_penolakan" rows="3" placeholder="Contoh: Perusahaan telah mengonfirmasi bahwa kuota magang telah penuh. Mahasiswa dipersilakan memilih perusahaan lain." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:border-amber-500 focus:ring-amber-500"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                      <button type="button" onclick="document.getElementById('modal-reset-draft-{{ $sp->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                      <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white shadow-sm">Reset ke Draft</button>
                    </div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada pengajuan surat pengantar PKL.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

<!-- TAB 2: REGISTRASI & MITRA INDUSTRI (PRD 5.2 & 7.3) -->
@if($tab === 'industri')
@php
  $verifiedIndustris = $industris->where('status_verifikasi', 'acc');
@endphp
<div class="space-y-6">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div>
        <h2 class="text-lg font-bold font-heading text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-[#02796B]">domain</span>
          <span>Verifikasi Usulan Tempat PKL Mahasiswa &amp; Daftar Mitra Industri</span>
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          Periksa perusahaan yang diajukan mahasiswa (status <strong>Pending</strong>), lalu klik <strong>Accept</strong> untuk memasukkannya ke sistem &amp; membuat 1 akun DUDI otomatis.
        </p>
      </div>
      <button type="button" onclick="document.getElementById('modal-add-industri-admin').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-[#0056B3] hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition-colors shrink-0 self-start sm:self-auto">
        <span class="material-symbols-outlined text-[16px]">add_business</span>
        <span>+ Input Mitra Industri (Oleh Admin)</span>
      </button>
    </div>

    <!-- Modal Tambah Mitra Industri Langsung oleh Admin -->
    <div id="modal-add-industri-admin" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-left shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
          <div>
            <h3 class="text-base font-bold text-slate-900">Input Mitra Industri Resmi Baru</h3>
            <p class="text-xs text-slate-500">Perusahaan langsung terverifikasi (ACC) dan dibuatkan 1 akun DUDI.</p>
          </div>
          <button type="button" onclick="document.getElementById('modal-add-industri-admin').classList.add('hidden')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>
        <form method="POST" action="{{ route('admin.industri.store') }}" class="space-y-3">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Perusahaan / Instansi <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_perusahaan" required placeholder="Contoh: PT Bank Rakyat Indonesia (Persero) Tbk" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidang Usaha</label>
              <input type="text" name="bidang_usaha" placeholder="Contoh: Perbankan & IT" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kota</label>
              <input type="text" name="kota" placeholder="Contoh: Jakarta Pusat" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap</label>
            <textarea name="alamat" rows="2" placeholder="Alamat lengkap perusahaan..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama PIC</label>
              <input type="text" name="kontak_person" placeholder="Nama HRD/Mentor" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email PIC</label>
              <input type="email" name="email_pj" placeholder="hrd@perusahaan.id" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telepon</label>
              <input type="text" name="telepon" placeholder="021-xxxxxxx" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
          </div>
          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <button type="button" onclick="document.getElementById('modal-add-industri-admin').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0056B3] hover:bg-blue-700 text-white shadow-sm">Simpan &amp; Buat Akun DUDI</button>
          </div>
        </form>
      </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
      <table class="w-full text-left text-xs text-slate-600">
        <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">Nama Perusahaan &amp; Mahasiswa Pengaju</th>
            <th class="py-3 px-4">Alamat &amp; Kontak PIC</th>
            <th class="py-3 px-4">Akun Penanggung Jawab</th>
            <th class="py-3 px-4">Status Verifikasi</th>
            <th class="py-3 px-4 text-center">Aksi Verifikasi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          @forelse($industris as $ind)
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="py-3.5 px-4">
              <div class="font-bold text-slate-900 text-sm">{{ $ind->nama_perusahaan }}</div>
              <div class="text-[10px] text-[#02796B] font-semibold">{{ $ind->bidang_usaha ?: 'Teknologi Informasi & Digital' }}</div>
              @if($ind->pengajuanMagangs->isNotEmpty())
                <div class="mt-1.5 space-y-1">
                  @foreach($ind->pengajuanMagangs as $pm)
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 text-[#0056B3] border border-blue-200/70 text-[10px] font-bold">
                      <span class="material-symbols-outlined text-[12px]">person</span>
                      <span>{{ $pm->mahasiswa->name ?? 'Mahasiswa' }} ({{ $pm->mahasiswa->identifier ?? '-' }})</span>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-[10px] text-slate-400 mt-0.5">Belum ada mahasiswa terkait</div>
              @endif
            </td>
            <td class="py-3.5 px-4">
              <div class="text-slate-800 font-semibold">{{ $ind->kontak_person ?: 'Divisi HRD / Talent Acquisition' }}</div>
              @if($ind->email_pj)
                <div class="text-[11px] font-mono text-[#0056B3]">{{ $ind->email_pj }}</div>
              @endif
              <div class="text-[11px] text-slate-500">{{ $ind->telepon ?: '021-xxxxxxxx' }} • {{ $ind->kota ?: 'Jakarta' }}</div>
              <div class="text-[10px] text-slate-400 truncate max-w-xs" title="{{ $ind->alamat }}">{{ $ind->alamat ?: 'Alamat belum dilengkapi' }}</div>
            </td>
            <td class="py-3.5 px-4">
              @if($ind->user)
                <div class="font-mono text-xs text-slate-800 font-bold">{{ $ind->user->email }}</div>
                <div class="text-[10px] text-slate-400">ID: {{ $ind->user->identifier }}</div>
                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] bg-teal-50 text-[#02796B] font-bold">Akun Aktif</span>
              @else
                <span class="text-amber-600 text-xs font-semibold flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">warning</span> Belum Dibuat
                </span>
                <span class="text-[10px] text-slate-400 block">Dibuat otomatis saat ACC</span>
              @endif
            </td>
            <td class="py-3.5 px-4">
              @if($ind->status_verifikasi === 'acc')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <span class="material-symbols-outlined text-[13px]">verified</span> Terverifikasi
                </span>
              @elseif($ind->status_verifikasi === 'reject')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                  <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak
                </span>
                @if($ind->catatan_admin)
                  <span class="text-[10px] text-rose-600 block mt-0.5 truncate max-w-xs" title="{{ $ind->catatan_admin }}">{{ $ind->catatan_admin }}</span>
                @endif
              @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 animate-pulse">
                  <span class="material-symbols-outlined text-[13px]">pending</span> Menunggu ACC
                </span>
              @endif
            </td>
            <td class="py-3.5 px-4 text-center">
              @if($ind->status_verifikasi === 'acc')
                <span class="text-slate-400 text-xs font-semibold">Telah Terverifikasi</span>
              @else
                <div class="flex flex-wrap items-center justify-center gap-1.5">
                  <button type="button" onclick="document.getElementById('modal-accept-ind-{{ $ind->id }}').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1 shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-[15px]">check_circle</span> Periksa &amp; Accept
                  </button>

                  @if($ind->pengajuanMagangs->isNotEmpty() && $verifiedIndustris->count() > 0)
                    <button type="button" onclick="document.getElementById('modal-forward-ind-{{ $ind->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-[#02796B] border border-teal-200 font-bold text-xs flex items-center gap-1 transition-colors" title="Perusahaan sudah terdaftar? Forward mahasiswa ke mitra terdaftar">
                      <span class="material-symbols-outlined text-[14px]">forward</span> Forward Mitra
                    </button>
                  @endif

                  <button type="button" onclick="document.getElementById('modal-reject-ind-{{ $ind->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold text-xs border border-slate-200 transition-colors">
                    Tolak
                  </button>
                </div>

                <!-- Modal Periksa & Accept Usulan Perusahaan Mahasiswa -->
                <div id="modal-accept-ind-{{ $ind->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                  <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-left shadow-2xl border border-slate-200">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="material-symbols-outlined text-emerald-600 text-2xl">verified</span>
                      <h3 class="text-base font-bold text-slate-900">Periksa &amp; Accept Tempat PKL Mahasiswa</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">
                      Periksa atau lengkapi data perusahaan yang diajukan mahasiswa sebelum dimasukkan secara resmi ke sistem dan dibuatkan akun Pembimbing Industri (DUDI).
                    </p>

                    <form method="POST" action="{{ route('admin.industri.acc', $ind->id) }}" class="space-y-3">
                      @csrf
                      <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Perusahaan Resmi <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_perusahaan" required value="{{ $ind->nama_perusahaan }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                      </div>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidang Usaha</label>
                          <input type="text" name="bidang_usaha" value="{{ $ind->bidang_usaha }}" placeholder="Teknologi Informasi" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kota</label>
                          <input type="text" name="kota" value="{{ $ind->kota }}" placeholder="Jakarta" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                        </div>
                      </div>
                      <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5">{{ $ind->alamat }}</textarea>
                      </div>
                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama PIC</label>
                          <input type="text" name="kontak_person" value="{{ $ind->kontak_person }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email PIC</label>
                          <input type="email" name="email_pj" value="{{ $ind->email_pj }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telepon</label>
                          <input type="text" name="telepon" value="{{ $ind->telepon }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                        </div>
                      </div>
                      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" onclick="document.getElementById('modal-accept-ind-{{ $ind->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center gap-1">
                          <span class="material-symbols-outlined text-[15px]">check_circle</span>
                          <span>Accept &amp; Masukkan ke Sistem</span>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>

                @if($ind->pengajuanMagangs->isNotEmpty() && $verifiedIndustris->count() > 0)
                  @php $firstPm = $ind->pengajuanMagangs->first(); @endphp
                  <!-- Modal Forward Mahasiswa dari Industri Pending ke Mitra Terdaftar (PRD 5.2) -->
                  <div id="modal-forward-ind-{{ $ind->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                      <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-[#02796B] text-2xl">forward</span>
                        <h3 class="text-base font-bold text-slate-900">Forward ke Industri yang Sudah Terdaftar</h3>
                      </div>
                      <p class="text-xs text-slate-500 mb-4">
                        Jika <strong>{{ $ind->nama_perusahaan }}</strong> merupakan perusahaan yang sudah terdaftar di sistem, teruskan pengajuan mahasiswa <strong>{{ $firstPm->mahasiswa->name ?? '' }}</strong> ke akun mitra industri yang sudah ada (PRD 5.2).
                      </p>

                      <form method="POST" action="{{ route('admin.pengajuan.forward', $firstPm->id) }}">
                        @csrf
                        <input type="hidden" name="redirect_tab" value="industri">
                        <div class="mb-4">
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Mitra Industri Terdaftar</label>
                          <select name="industri_id" required class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-teal-500 focus:ring-teal-500">
                            @foreach($verifiedIndustris as $vInd)
                              <option value="{{ $vInd->id }}">
                                {{ $vInd->nama_perusahaan }} (Akun: {{ $vInd->user->email ?? 'Aktif' }})
                              </option>
                            @endforeach
                          </select>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                          <button type="button" onclick="document.getElementById('modal-forward-ind-{{ $ind->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                          <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#02796B] hover:bg-teal-800 text-white shadow-sm">Forward ke Mitra</button>
                        </div>
                      </form>
                    </div>
                  </div>
                @endif

                <!-- Modal Tolak Pendaftaran Industri -->
                <div id="modal-reject-ind-{{ $ind->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                  <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="material-symbols-outlined text-rose-600 text-2xl">cancel</span>
                      <h3 class="text-base font-bold text-slate-900">Tolak Pendaftaran Industri</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">
                      Berikan alasan penolakan untuk mitra industri <strong>{{ $ind->nama_perusahaan }}</strong>.
                    </p>

                    <form method="POST" action="{{ route('admin.industri.reject', $ind->id) }}">
                      @csrf
                      <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan / Alasan Penolakan</label>
                        <textarea name="catatan_admin" rows="3" required placeholder="Contoh: Nomor telepon dan email penanggung jawab tidak dapat dihubungi untuk konfirmasi legalitas instansi." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:border-rose-500 focus:ring-rose-500"></textarea>
                      </div>
                      <div class="flex items-center justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-reject-ind-{{ $ind->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-sm">Tolak Pendaftaran</button>
                      </div>
                    </form>
                  </div>
                </div>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada mitra industri terdaftar.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

<!-- TAB 3: MANAJEMEN MAHASISWA & IMPORT (PRD 5.1 & 7.1) -->
@if($tab === 'mahasiswa')
<div class="space-y-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Upload Import File Excel / CSV -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
          <span class="material-symbols-outlined text-[22px]">upload_file</span>
        </div>
        <div>
          <h3 class="text-base font-bold font-heading text-slate-800">Import Mahasiswa</h3>
          <p class="text-xs text-slate-500">Upload file template .csv / .xlsx</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.mahasiswa.import') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih File Skema</label>
          <input type="file" name="file_mahasiswa" accept=".csv,.txt" required class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#0056B3] hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1">
        </div>

        <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/80 text-[11px] text-slate-600">
          <div class="font-bold text-slate-800 mb-1">Format Kolom:</div>
          <code>NIM, Nama Lengkap, Email, Program Studi, No HP</code>
        </div>

        <button type="submit" class="w-full py-2.5 rounded-xl bg-[#0056B3] hover:bg-blue-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-colors">
          <span class="material-symbols-outlined text-[16px]">cloud_upload</span>
          <span>Proses Import Data Mahasiswa</span>
        </button>
      </form>
    </div>

    <!-- Form Tambah Mahasiswa Manual -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
          <span class="material-symbols-outlined text-[22px]">person_add</span>
        </div>
        <div>
          <h3 class="text-base font-bold font-heading text-slate-800">Pendaftaran Mahasiswa Mandiri</h3>
          <p class="text-xs text-slate-500">Tambah akun mahasiswa tunggal ke dalam basis data SIM-PKL</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.mahasiswa.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIM Mahasiswa</label>
          <input type="text" name="identifier" required placeholder="Contoh: 2107411099" class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
          <input type="text" name="name" required placeholder="Contoh: Fajar Ramadhan" class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Mahasiswa</label>
          <input type="email" name="email" required placeholder="fajar.ramadhan@stu.pnj.ac.id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Program Studi</label>
          <select name="prodi" class="w-full text-xs rounded-xl border-slate-200 p-2.5 focus:border-blue-500 focus:ring-blue-500">
            <option value="Teknik Informatika (D4)">Teknik Informatika (D4)</option>
            <option value="Teknik Komputer & Jaringan (D1/D3)">Teknik Komputer &amp; Jaringan</option>
            <option value="Broadband Multimedia (D4)">Broadband Multimedia (D4)</option>
          </select>
        </div>
        <div class="sm:col-span-2 flex items-center justify-end">
          <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 shadow-sm transition-colors">
            <span class="material-symbols-outlined text-[16px]">save</span>
            <span>Simpan Mahasiswa Baru</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Full Control Data Mahasiswa & Penempatan PKL -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-[#0056B3]">tune</span>
          <span>Full Control Data Mahasiswa, Status &amp; Tempat PKL ({{ $mahasiswas->count() }})</span>
        </h3>
        <p class="text-xs text-slate-500">
          Admin memiliki akses penuh untuk mengedit profil mahasiswa, mengubah status/dosen pembimbing, mengganti perusahaan, atau menghapus tempat PKL mahasiswa.
        </p>
      </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
      <table class="w-full text-left text-xs text-slate-600">
        <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">NIM &amp; Nama</th>
            <th class="py-3 px-4">Prodi &amp; Kontak</th>
            <th class="py-3 px-4">Dosen Pembimbing</th>
            <th class="py-3 px-4">Tempat PKL &amp; Status</th>
            <th class="py-3 px-4 text-center">Full Control Admin</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          @foreach($mahasiswas as $m)
          @php
            $pmAktif = $m->pengajuanMagangAktif;
          @endphp
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="py-3.5 px-4 font-bold text-slate-900">
              <div>{{ $m->name }}</div>
              <div class="font-mono text-slate-400 text-[11px]">NIM: {{ $m->identifier }}</div>
            </td>
            <td class="py-3.5 px-4">
              <div class="font-semibold text-slate-800">{{ $m->prodi }}</div>
              <div class="text-slate-500 text-[11px]">{{ $m->email }}</div>
              <div class="text-slate-400 text-[10px]">{{ $m->phone ?: '-' }}</div>
            </td>
            <td class="py-3.5 px-4">
              @if($m->dosen)
                <span class="text-emerald-700 font-bold block">{{ $m->dosen->name }}</span>
                <span class="text-[10px] text-slate-400 font-mono">NIP: {{ $m->dosen->identifier }}</span>
              @else
                <span class="text-slate-400 italic">Belum Ditetapkan</span>
              @endif
            </td>
            <td class="py-3.5 px-4">
              @if($pmAktif && $pmAktif->industri)
                <div class="text-[#0056B3] font-bold">{{ $pmAktif->industri->nama_perusahaan }}</div>
                <div class="text-[10px] text-slate-500">{{ $pmAktif->posisi_magang ?: 'Internship' }}</div>
                <div class="flex flex-wrap items-center gap-1 mt-1">
                  @if($pmAktif->status_surat_pengantar === 'diterbitkan')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Surat Terbit</span>
                  @elseif($pmAktif->status_surat_pengantar === 'pending')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending Admin</span>
                  @elseif($pmAktif->status_surat_pengantar === 'ditolak')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                  @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Draft</span>
                  @endif
                </div>
              @else
                <span class="text-slate-400 italic">Belum Ada Tempat PKL</span>
              @endif
            </td>
            <td class="py-3.5 px-4 text-center">
              <div class="flex flex-wrap items-center justify-center gap-1.5">
                <!-- Tombol Edit Data & Status Mahasiswa -->
                <button type="button" onclick="document.getElementById('modal-edit-mhs-{{ $m->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-xl bg-blue-50 hover:bg-[#0056B3] text-[#0056B3] hover:text-white border border-blue-200 font-bold text-xs flex items-center gap-1 transition-colors">
                  <span class="material-symbols-outlined text-[15px]">edit_square</span> Edit Data &amp; Status
                </button>

                <!-- Tombol Hapus Tempat PKL -->
                @if($pmAktif)
                  <form method="POST" action="{{ route('admin.mahasiswa.destroy-pkl', $m->id) }}" onsubmit="return confirm('Hapus tempat PKL ({{ $pmAktif->industri->nama_perusahaan ?? '' }}) dari mahasiswa {{ $m->name }}?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="redirect_tab" value="mahasiswa">
                    <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-600 text-amber-800 hover:text-white border border-amber-200 font-bold text-xs flex items-center gap-1 transition-colors" title="Reset / Hapus Tempat PKL Mahasiswa">
                      <span class="material-symbols-outlined text-[15px]">domain_disabled</span> Hapus Tempat PKL
                    </button>
                  </form>
                @endif

                <!-- Tombol Hapus Akun Mahasiswa -->
                <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m->id) }}" onsubmit="return confirm('Yakin ingin menghapus akun mahasiswa {{ $m->name }} (NIM: {{ $m->identifier }}) secara permanen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-1.5 rounded-xl bg-slate-100 hover:bg-rose-600 text-slate-500 hover:text-white border border-slate-200 transition-colors" title="Hapus Akun Mahasiswa">
                    <span class="material-symbols-outlined text-[15px]">person_remove</span>
                  </button>
                </form>
              </div>

              <!-- Modal Full Control Edit Mahasiswa & Status PKL -->
              <div id="modal-edit-mhs-{{ $m->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-2xl w-full p-6 text-left shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto">
                  <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                      <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">tune</span>
                      </div>
                      <div>
                        <h3 class="text-base font-bold text-slate-900">Full Control Mahasiswa: {{ $m->name }}</h3>
                        <p class="text-xs text-slate-500">Edit profil akademik, dosen pembimbing, penempatan tempat PKL, dan status administrasi.</p>
                      </div>
                    </div>
                    <button type="button" onclick="document.getElementById('modal-edit-mhs-{{ $m->id }}').classList.add('hidden')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
                      <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                  </div>

                  <form method="POST" action="{{ route('admin.mahasiswa.update', $m->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_tab" value="mahasiswa">

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                      <div class="text-[11px] font-extrabold uppercase tracking-wider text-[#0056B3]">1. Identitas &amp; Profil Akademik Mahasiswa</div>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIM Mahasiswa <span class="text-rose-500">*</span></label>
                          <input type="text" name="identifier" required value="{{ $m->identifier }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                          <input type="text" name="name" required value="{{ $m->name }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Kampus <span class="text-rose-500">*</span></label>
                          <input type="email" name="email" required value="{{ $m->email }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Telepon / WhatsApp</label>
                          <input type="text" name="phone" value="{{ $m->phone }}" placeholder="0812xxxxxxxx" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>
                        <div class="sm:col-span-2">
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Program Studi</label>
                          <select name="prodi" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                            <option value="Teknik Informatika (D4)" {{ $m->prodi === 'Teknik Informatika (D4)' ? 'selected' : '' }}>Teknik Informatika (D4)</option>
                            <option value="Teknik Komputer & Jaringan (D1/D3)" {{ str_contains($m->prodi ?? '', 'Jaringan') ? 'selected' : '' }}>Teknik Komputer &amp; Jaringan</option>
                            <option value="Broadband Multimedia (D4)" {{ str_contains($m->prodi ?? '', 'Multimedia') ? 'selected' : '' }}>Broadband Multimedia (D4)</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-teal-50/40 border border-teal-200/70 space-y-3">
                      <div class="text-[11px] font-extrabold uppercase tracking-wider text-[#02796B]">2. Kontrol Tempat PKL, Status Surat &amp; Dosen Pembimbing</div>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Perusahaan / Tempat PKL</label>
                          <select name="industri_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                            <option value="">-- Belum Ditempatkan / Tidak Diubah --</option>
                            @foreach($industris as $indOpt)
                              <option value="{{ $indOpt->id }}" {{ $pmAktif?->industri_id === $indOpt->id ? 'selected' : '' }}>
                                {{ $indOpt->nama_perusahaan }} [Status: {{ strtoupper($indOpt->status_verifikasi) }}]
                              </option>
                            @endforeach
                          </select>
                        </div>

                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Posisi / Divisi PKL</label>
                          <input type="text" name="posisi_magang" value="{{ $pmAktif?->posisi_magang }}" placeholder="Contoh: Software Engineer Intern" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>

                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Pengajuan &amp; Surat Pengantar</label>
                          <select name="status_surat_pengantar" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                            <option value="pending" {{ $pmAktif?->status_surat_pengantar === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi Admin)</option>
                            <option value="diterbitkan" {{ $pmAktif?->status_surat_pengantar === 'diterbitkan' ? 'selected' : '' }}>Diterbitkan (Disetujui / Resmi)</option>
                            <option value="draft" {{ $pmAktif?->status_surat_pengantar === 'draft' ? 'selected' : '' }}>Draft (Mahasiswa Bisa Input Ulang)</option>
                            <option value="ditolak" {{ $pmAktif?->status_surat_pengantar === 'ditolak' ? 'selected' : '' }}>Ditolak (Mahasiswa Bisa Input Ulang)</option>
                          </select>
                        </div>

                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai PKL</label>
                          <input type="date" name="tanggal_mulai" value="{{ $pmAktif?->tanggal_mulai?->format('Y-m-d') }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>

                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Selesai PKL</label>
                          <input type="date" name="tanggal_selesai" value="{{ $pmAktif?->tanggal_selesai?->format('Y-m-d') }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                        </div>

                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Dosen Pembimbing PKL</label>
                          <select name="dosen_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                            <option value="">-- Belum Ditetapkan / Kosongkan Dosen --</option>
                            @foreach($dosens ?? [] as $dsn)
                              <option value="{{ $dsn->id }}" {{ $m->dosen_id === $dsn->id ? 'selected' : '' }}>
                                {{ $dsn->name }} ({{ $dsn->identifier }})
                              </option>
                            @endforeach
                          </select>
                        </div>

                        <div>
                          <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Dosen Pembimbing</label>
                          <select name="status_dosen" class="w-full text-xs rounded-xl border-slate-200 p-2.5 bg-white">
                            <option value="pending" {{ $pmAktif?->status_dosen === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="usulan" {{ $pmAktif?->status_dosen === 'usulan' ? 'selected' : '' }}>Usulan Mahasiswa</option>
                            <option value="acc_kps" {{ $pmAktif?->status_dosen === 'acc_kps' || $m->dosen_id ? 'selected' : '' }}>Disahkan (ACC KPS)</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                      <button type="button" onclick="document.getElementById('modal-edit-mhs-{{ $m->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                      <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#0056B3] hover:bg-blue-700 text-white shadow-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <span>Simpan Perubahan</span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

<!-- TAB 4: RESET PASSWORD PENGGUNA (PRD 11.1) -->
@if($tab === 'users')
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-rose-600">lock_reset</span>
        <span>Manajemen Kredensial &amp; Reset Password Manual</span>
      </h2>
      <p class="text-xs text-slate-500 mt-1">
        Sesuai PRD 11.1, fitur lupa password mandiri ditiadakan. Seluruh reset kredensial pengguna dikontrol langsung oleh Admin Jurusan.
      </p>
    </div>

    <form method="GET" action="{{ route('dashboard.admin') }}" class="flex items-center gap-2">
      <input type="hidden" name="tab" value="users">
      <input type="text" name="search_user" value="{{ request('search_user') }}" placeholder="Cari nama, NIM, NIP, email..." class="text-xs rounded-xl border-slate-200 p-2.5 w-64 focus:border-blue-500 focus:ring-blue-500">
      <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition-colors">Cari</button>
    </form>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="w-full text-left text-xs text-slate-600">
      <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
        <tr>
          <th class="py-3 px-4">Nama Pengguna</th>
          <th class="py-3 px-4">Identifier / NIM / NIP</th>
          <th class="py-3 px-4">Email</th>
          <th class="py-3 px-4">Peran (Role)</th>
          <th class="py-3 px-4 text-center">Tindakan Reset</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium">
        @forelse($users as $u)
        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="py-3.5 px-4 font-bold text-slate-900">{{ $u->name }}</td>
          <td class="py-3.5 px-4 font-mono text-slate-700">{{ $u->identifier ?: '-' }}</td>
          <td class="py-3.5 px-4 text-slate-800">{{ $u->email }}</td>
          <td class="py-3.5 px-4">
            @php
              $roleBadge = match($u->role) {
                'admin' => 'bg-blue-100 text-blue-800',
                'kps' => 'bg-purple-100 text-purple-800',
                'dosen' => 'bg-emerald-100 text-emerald-800',
                'industri', 'dudi' => 'bg-teal-100 text-teal-800',
                default => 'bg-slate-100 text-slate-800'
              };
            @endphp
            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $roleBadge }}">
              {{ $u->role }}
            </span>
          </td>
          <td class="py-3.5 px-4 text-center">
            <form method="POST" action="{{ route('admin.users.reset-password', $u->id) }}" onsubmit="return confirm('Reset password untuk akun {{ $u->name }} ({{ $u->email }}) ke default &quot;password123&quot;?')">
              @csrf
              <input type="hidden" name="new_password" value="password123">
              <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 font-bold text-xs border border-slate-200 transition-colors flex items-center gap-1 mx-auto">
                <span class="material-symbols-outlined text-[15px]">key</span> Reset ke "password123"
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="py-8 text-center text-slate-400">Pengguna tidak ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $users->links() }}
  </div>
</div>
@endif
@endsection
