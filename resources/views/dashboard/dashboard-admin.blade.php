@extends('layouts.app', [
  'title' => 'Dashboard Admin - SIM-PKL TIK PNJ',
  'pageTitle' => 'Dashboard Admin Jurusan',
  'breadcrumbParent' => 'Portal Admin',
  'activePage' => 'admin'
])

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 tracking-tight">
      Dashboard Admin Jurusan {{ $admin?->effective_jurusan ?? 'TIK' }}
    </h1>
    <p class="text-xs text-slate-500 mt-0.5">
      Verifikasi tempat PKL, surat pengantar, plotting dosen pembimbing, dan data mahasiswa jurusan {{ $admin?->effective_jurusan ?? 'TIK' }}.
    </p>
  </div>

  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-[#0056B3] border border-blue-200 text-xs font-bold self-start sm:self-auto">
    <span class="material-symbols-outlined text-[16px]">verified_user</span>
    <span>Admin {{ $admin?->effective_jurusan ?? 'JTIK' }} PNJ</span>
  </span>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <span class="text-slate-500 text-[11px] font-bold uppercase">Mahasiswa</span>
      <span class="material-symbols-outlined text-[18px] text-[#0056B3]">school</span>
    </div>
    <div class="text-2xl font-black font-heading text-slate-900 mt-1.5">{{ $stats['total_mahasiswa'] }}</div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <span class="text-slate-500 text-[11px] font-bold uppercase">Dosen</span>
      <span class="material-symbols-outlined text-[18px] text-emerald-600">person_check</span>
    </div>
    <div class="text-2xl font-black font-heading text-slate-900 mt-1.5">{{ $stats['total_dosen'] }}</div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
    <div class="flex items-center justify-between">
      <span class="text-slate-500 text-[11px] font-bold uppercase">Mitra DUDI</span>
      <span class="material-symbols-outlined text-[18px] text-[#02796B]">domain</span>
    </div>
    <div class="text-2xl font-black font-heading text-slate-900 mt-1.5">{{ $stats['total_industri'] }}</div>
  </div>

  <div class="bg-white rounded-2xl border border-amber-200/80 p-4 shadow-sm bg-amber-50/30">
    <div class="flex items-center justify-between">
      <span class="text-amber-800 text-[11px] font-bold uppercase">Surat Pending</span>
      <span class="material-symbols-outlined text-[18px] text-amber-600">mail</span>
    </div>
    <div class="text-2xl font-black font-heading text-amber-900 mt-1.5">{{ $stats['surat_pending'] }}</div>
  </div>

  <div class="bg-white rounded-2xl border border-emerald-200/80 p-4 shadow-sm bg-emerald-50/30">
    <div class="flex items-center justify-between">
      <span class="text-emerald-800 text-[11px] font-bold uppercase">Surat Terbit</span>
      <span class="material-symbols-outlined text-[18px] text-emerald-600">mark_email_read</span>
    </div>
    <div class="text-2xl font-black font-heading text-emerald-900 mt-1.5">{{ $stats['surat_diterbitkan'] }}</div>
  </div>

  <div class="bg-white rounded-2xl border border-purple-200/80 p-4 shadow-sm bg-purple-50/30">
    <div class="flex items-center justify-between">
      <span class="text-purple-800 text-[11px] font-bold uppercase">DUDI Pending</span>
      <span class="material-symbols-outlined text-[18px] text-purple-600">business</span>
    </div>
    <div class="text-2xl font-black font-heading text-purple-900 mt-1.5">{{ $stats['industri_pending'] }}</div>
  </div>
</div>

<!-- TAB PLOTTING DOSEN PEMBIMBING (FORM F1) -->
@if($tab === 'plotting' || $tab === 'overview')
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm mb-6 space-y-4">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
    <div>
      <h2 class="text-base font-bold font-heading text-slate-800">Plotting Dosen Pembimbing (Form F1) — Jurusan {{ $admin?->effective_jurusan ?? 'TIK' }}</h2>
      <p class="text-xs text-slate-500">Tentukan dan plot dosen pembimbing untuk mahasiswa jurusan {{ $admin?->effective_jurusan ?? 'TIK' }}, lalu teruskan ke KPS untuk di-approve.</p>
    </div>
    @if(($stats['f1_revision'] ?? 0) > 0)
      <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">
        {{ $stats['f1_revision'] }} Perlu Revisi Plotting
      </span>
    @endif
  </div>

  <div class="space-y-3">
    @forelse($f1Applications ?? [] as $f1App)
      @php
        $recDosen = $f1App->recommendedDosen ?? $f1App->dosen;
        $isRevision = $f1App->status === 'revision_requested';
        $isPendingAdmin = in_array($f1App->status, ['pending_admin', 'pending'], true);
        $isPendingKps = $f1App->status === 'pending_kps';
        $isApproved = $f1App->status === 'approved';
      @endphp
      <div class="p-4 rounded-xl border {{ $isRevision ? 'bg-rose-50/40 border-rose-200' : ($isPendingAdmin ? 'bg-amber-50/30 border-amber-200' : 'bg-slate-50/60 border-slate-200') }} space-y-3">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
          <div class="space-y-1">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-sm font-bold text-slate-900">{{ $f1App->student->name }}</span>
              <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-[11px] font-mono font-semibold">{{ $f1App->student->identifier }}</span>
              <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">{{ $f1App->student->effective_jurusan }}</span>
              @if($isRevision)
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">Diminta Revisi oleh KPS</span>
              @elseif($isPendingAdmin)
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Menunggu Plotting Admin</span>
              @elseif($isPendingKps)
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold">Diteruskan ke KPS (Pending KPS)</span>
              @elseif($isApproved)
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Approved KPS</span>
              @endif
            </div>
            <p class="text-xs text-slate-600">
              <strong>{{ $f1App->company_name ?? 'Instansi PKL' }}</strong> — {{ $f1App->internship_title ?? '-' }}
            </p>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
              <span>Rekomendasi Mahasiswa: <strong class="text-slate-800">{{ $recDosen->name ?? '-' }}</strong></span>
              @if($f1App->plotted_by || $isPendingKps || $isApproved)
                <span>Hasil Plotting Admin: <strong class="text-[#0056B3]">{{ $f1App->dosen->name ?? '-' }}</strong></span>
              @endif
            </div>
          </div>

          @if(!$isApproved)
            <form method="POST" action="{{ route('admin.f1.plot', $f1App->id) }}" class="flex flex-wrap items-center gap-2 shrink-0">
              @csrf
              <select name="dosen_id" required class="text-xs rounded-xl border-slate-200 bg-white px-3 py-2 min-w-[220px]">
                @foreach($dosens ?? [] as $dsn)
                  @php $usedQuota = $dsn->students_count ?? 0; @endphp
                  <option value="{{ $dsn->id }}" {{ $f1App->dosen_id === $dsn->id ? 'selected' : '' }} {{ $usedQuota >= 7 && $f1App->dosen_id !== $dsn->id ? 'disabled' : '' }}>
                    {{ $dsn->name }} ({{ $usedQuota }}/7)
                  </option>
                @endforeach
              </select>
              <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span>{{ $isRevision ? 'Plot Ulang & Teruskan ke KPS' : 'Plot & Teruskan ke KPS' }}</span>
              </button>
            </form>
          @endif
        </div>

        @if($isRevision && $f1App->revision_notes)
          <div class="p-3 rounded-xl bg-white border border-rose-200 text-xs text-rose-800">
            <strong class="block text-rose-900 mb-0.5">Catatan Revisi dari KPS:</strong>
            {{ $f1App->revision_notes }}
          </div>
        @endif
      </div>
    @empty
      <div class="p-6 text-center bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-500">
        Belum ada pengajuan rekomendasi dosen pembimbing (Form F1) dari mahasiswa jurusan {{ $admin?->effective_jurusan ?? 'TIK' }}.
      </div>
    @endforelse
  </div>
</div>
@endif

<!-- TAB 1: SURAT PENGANTAR & PENEMPATAN -->
@if($tab === 'surat' || $tab === 'overview')
@php
  $verifiedIndustris = $industris->where('status_verifikasi', 'acc');
@endphp
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
  <div class="flex items-center justify-between gap-4 mb-4">
    <div>
      <h2 class="text-base font-bold font-heading text-slate-800">Pengajuan Tempat PKL &amp; Surat Pengantar</h2>
      <p class="text-xs text-slate-500">Setujui tempat PKL mahasiswa untuk menerbitkan Surat Pengantar resmi.</p>
    </div>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="w-full text-left text-xs text-slate-600">
      <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase border-b border-slate-200">
        <tr>
          <th class="py-3 px-4">Mahasiswa</th>
          <th class="py-3 px-4">Perusahaan Tujuan</th>
          <th class="py-3 px-4">Posisi &amp; Periode</th>
          <th class="py-3 px-4">No. Surat</th>
          <th class="py-3 px-4">Status</th>
          <th class="py-3 px-4 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium">
        @forelse($suratPengantars as $sp)
        <tr class="hover:bg-slate-50/70 transition-colors">
          <td class="py-3 px-4">
            <div class="font-bold text-slate-900">{{ $sp->mahasiswa->name }}</div>
            <div class="text-[11px] font-mono text-slate-400">{{ $sp->mahasiswa->identifier }} • {{ $sp->mahasiswa->prodi }}</div>
          </td>
          <td class="py-3 px-4">
            @if($sp->industri)
              <div class="font-bold text-slate-800">{{ $sp->industri->nama_perusahaan }}</div>
              <div class="flex flex-wrap items-center gap-1.5 mt-1">
                <span class="text-[11px] text-slate-500">{{ $sp->industri->kota ?: 'Jakarta' }}</span>
                @if($sp->status_verifikasi_efektif === 'acc')
                  <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-[#02796B] border border-teal-200">Terverifikasi</span>
                @elseif($sp->status_verifikasi_efektif === 'reject')
                  <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                @else
                  <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Perlu ACC</span>
                @endif
                @if($sp->file_surat_keterangan)
                  <a href="{{ route('dokumen.surat-keterangan', $sp->id) }}" target="_blank" class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 hover:bg-blue-100 text-[#0056B3] border border-blue-200 transition-colors" title="Lihat Bukti Diterima / Surat Keterangan Industri">
                    <span class="material-symbols-outlined text-[12px]">attach_file</span>
                    <span>Bukti Diterima</span>
                  </a>
                @endif
              </div>
            @elseif($sp->nama_perusahaan_surat)
              <div class="font-bold text-slate-800">{{ $sp->nama_perusahaan_surat }}</div>
              @if($sp->alamat_perusahaan_surat)
                <div class="text-[11px] text-slate-500 max-w-xs truncate" title="{{ $sp->alamat_perusahaan_surat }}">{{ $sp->alamat_perusahaan_surat }}</div>
              @endif
              <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#0056B3] border border-blue-200">Tujuan Surat Pengantar</span>
            @else
              <span class="text-slate-400 text-xs">Belum Mengajukan Perusahaan</span>
            @endif
          </td>
          <td class="py-3 px-4">
            <div class="text-slate-800 font-semibold">{{ $sp->posisi_magang ?: 'Intern' }}</div>
            <div class="text-[11px] text-slate-400">
              @if($sp->tanggal_mulai && $sp->tanggal_selesai)
                {{ $sp->tanggal_mulai->format('d M') }} - {{ $sp->tanggal_selesai->format('d M Y') }}
              @else
                Semester Genap 2026
              @endif
            </div>
          </td>
          <td class="py-3 px-4 whitespace-nowrap">
            @if($sp->nomor_surat_pengantar)
              <span class="px-2 py-1 rounded-lg bg-blue-50 text-[#0056B3] font-mono text-[11px] font-bold border border-blue-200">
                {{ $sp->nomor_surat_pengantar }}
              </span>
            @else
              <span class="text-slate-400 text-[11px]">-</span>
            @endif
          </td>
          <td class="py-3 px-4 whitespace-nowrap">
            @if($sp->status_surat_pengantar === 'diterbitkan' && $sp->status_verifikasi_efektif === 'pending')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                <span class="material-symbols-outlined text-[13px]">schedule</span> Pending Perusahaan
              </span>
            @elseif($sp->status_surat_pengantar === 'diterbitkan' && $sp->status_verifikasi_efektif === 'reject')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="material-symbols-outlined text-[13px]">cancel</span> Perusahaan Ditolak
              </span>
            @elseif($sp->status_surat_pengantar === 'diterbitkan')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="material-symbols-outlined text-[13px]">check_circle</span> Terbit
              </span>
            @elseif($sp->status_surat_pengantar === 'pending')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="material-symbols-outlined text-[13px]">schedule</span> Pending Surat
              </span>
            @elseif($sp->status_surat_pengantar === 'ditolak')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak
              </span>
            @else
              <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Draft</span>
            @endif
          </td>
          <td class="py-3 px-4 text-right whitespace-nowrap">
            <div class="inline-flex items-center justify-end gap-1.5">
              @if($sp->status_surat_pengantar === 'pending' || $sp->status_surat_pengantar === 'draft' || $sp->status_verifikasi_efektif === 'pending')
                <form method="POST" action="{{ route('admin.surat.terbitkan', $sp->id) }}" onsubmit="return confirm('Setujui pengajuan untuk {{ $sp->mahasiswa->name }}?')">
                  @csrf
                  <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs inline-flex items-center gap-1 shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-[14px]">check</span> Accept
                  </button>
                </form>

                <button type="button" onclick="document.getElementById('modal-tolak-{{ $sp->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold text-xs border border-slate-200 transition-colors">
                  Tolak
                </button>
              @endif

              @if($verifiedIndustris->count() > 0)
                <button type="button" onclick="document.getElementById('modal-forward-{{ $sp->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-[#02796B] border border-teal-200 font-bold text-xs inline-flex items-center gap-1 transition-colors" title="Forward ke Mitra Terdaftar">
                  <span class="material-symbols-outlined text-[14px]">forward</span> Forward
                </button>
              @endif

              @if($sp->status_surat_pengantar === 'diterbitkan')
                <button type="button" onclick="document.getElementById('modal-reset-draft-{{ $sp->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 font-bold text-xs inline-flex items-center gap-1 transition-colors" title="Reset ke Draft">
                  <span class="material-symbols-outlined text-[14px]">restart_alt</span> Reset
                </button>
              @endif

              <a href="{{ route('dokumen.surat-pengantar', $sp->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs inline-flex items-center gap-1 transition-colors" title="Cetak PDF">
                <span class="material-symbols-outlined text-[14px]">print</span> PDF
              </a>

              <form method="POST" action="{{ route('admin.mahasiswa.destroy-pkl', $sp->mahasiswa_id) }}" onsubmit="return confirm('Hapus tempat PKL untuk {{ $sp->mahasiswa->name }}?')">
                @csrf
                @method('DELETE')
                <input type="hidden" name="redirect_tab" value="surat">
                <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 transition-colors" title="Hapus Tempat PKL">
                  <span class="material-symbols-outlined text-[15px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="py-8 text-center text-slate-400">Belum ada pengajuan surat pengantar.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modals for Tab 1 -->
@foreach($suratPengantars as $sp)
  <!-- Modal Forward -->
  <div id="modal-forward-{{ $sp->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-5 text-left shadow-2xl border border-slate-200">
      <h3 class="text-sm font-bold text-slate-900 mb-1">Forward ke Mitra Terdaftar</h3>
      <p class="text-xs text-slate-500 mb-3">Hubungkan penempatan <strong>{{ $sp->mahasiswa->name }}</strong> ke akun mitra industri resmi.</p>
      <form method="POST" action="{{ route('admin.pengajuan.forward', $sp->id) }}">
        @csrf
        <input type="hidden" name="redirect_tab" value="surat">
        <div class="mb-4">
          <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Mitra Industri</label>
          <select name="industri_id" required class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            @foreach($verifiedIndustris as $vInd)
              <option value="{{ $vInd->id }}" {{ $sp->industri_id === $vInd->id ? 'selected' : '' }}>
                {{ $vInd->nama_perusahaan }} ({{ $vInd->user->email ?? 'Aktif' }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="flex items-center justify-end gap-2">
          <button type="button" onclick="document.getElementById('modal-forward-{{ $sp->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#02796B] hover:bg-teal-800 text-white">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Tolak -->
  <div id="modal-tolak-{{ $sp->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-5 text-left shadow-2xl border border-slate-200">
      <h3 class="text-sm font-bold text-slate-900 mb-1">Tolak Pengajuan</h3>
      <p class="text-xs text-slate-500 mb-3">Masukkan catatan penolakan untuk <strong>{{ $sp->mahasiswa->name }}</strong>.</p>
      <form method="POST" action="{{ route('admin.surat.tolak', $sp->id) }}">
        @csrf
        <div class="mb-4">
          <textarea name="catatan_penolakan" rows="3" required placeholder="Alasan penolakan..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
        </div>
        <div class="flex items-center justify-end gap-2">
          <button type="button" onclick="document.getElementById('modal-tolak-{{ $sp->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white">Tolak</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Reset Draft -->
  <div id="modal-reset-draft-{{ $sp->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-5 text-left shadow-2xl border border-slate-200">
      <h3 class="text-sm font-bold text-slate-900 mb-1">Kembalikan ke Draft</h3>
      <p class="text-xs text-slate-500 mb-3">Buka kunci form <strong>{{ $sp->mahasiswa->name }}</strong> agar dapat mengajukan perusahaan baru.</p>
      <form method="POST" action="{{ route('admin.pengajuan.reset-draft', $sp->id) }}">
        @csrf
        <div class="mb-4">
          <textarea name="catatan_penolakan" rows="3" placeholder="Keterangan (opsional)..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
        </div>
        <div class="flex items-center justify-end gap-2">
          <button type="button" onclick="document.getElementById('modal-reset-draft-{{ $sp->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white">Reset ke Draft</button>
        </div>
      </form>
    </div>
  </div>
@endforeach
@endif

<!-- TAB 2: MITRA INDUSTRI -->
@if($tab === 'industri')
@php
  $verifiedIndustris = $industris->where('status_verifikasi', 'acc');
@endphp
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
    <div>
      <h2 class="text-base font-bold font-heading text-slate-800">Daftar &amp; Verifikasi Mitra Industri (DUDI)</h2>
      <p class="text-xs text-slate-500">Password default seluruh akun DUDI adalah <code class="font-bold text-[#0056B3] bg-blue-50 px-1.5 py-0.5 rounded">password123</code>.</p>
    </div>
    <button type="button" onclick="document.getElementById('modal-add-industri-admin').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-[#0056B3] hover:bg-blue-700 text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors self-start sm:self-auto">
      <span class="material-symbols-outlined text-[16px]">add_business</span>
      <span>Tambah Mitra</span>
    </button>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="w-full text-left text-xs text-slate-600">
      <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase border-b border-slate-200">
        <tr>
          <th class="py-3 px-4">Perusahaan &amp; Mahasiswa</th>
          <th class="py-3 px-4">Kontak &amp; Lokasi</th>
          <th class="py-3 px-4">Akun Login DUDI</th>
          <th class="py-3 px-4">Status</th>
          <th class="py-3 px-4 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium">
        @forelse($industris as $ind)
        <tr class="hover:bg-slate-50/70 transition-colors">
          <td class="py-3 px-4">
            <div class="font-bold text-slate-900">{{ $ind->nama_perusahaan }}</div>
            <div class="text-[11px] text-[#02796B] font-semibold">{{ $ind->bidang_usaha ?: 'Teknologi Informasi' }}</div>
            @if($ind->pengajuanMagangs->isNotEmpty())
              <div class="flex flex-wrap gap-1 mt-1">
                @foreach($ind->pengajuanMagangs as $pm)
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded {{ $pm->status_verifikasi_efektif === 'pending' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-blue-50 text-[#0056B3] border-blue-200/70' }} border text-[10px] font-bold">
                    <span class="material-symbols-outlined text-[11px]">person</span>
                    {{ $pm->mahasiswa->name ?? 'Mahasiswa' }}
                    @if($pm->status_verifikasi_efektif === 'pending')
                      <span class="text-[9px] uppercase font-extrabold">(Pending)</span>
                    @endif
                  </span>
                  @if($pm->file_surat_keterangan)
                    <a href="{{ route('dokumen.surat-keterangan', $pm->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-[10px] font-bold transition-colors" title="Lihat Bukti Diterima {{ $pm->mahasiswa->name ?? '' }}">
                      <span class="material-symbols-outlined text-[11px]">attach_file</span>
                      <span>Bukti</span>
                    </a>
                  @endif
                @endforeach
              </div>
            @endif
          </td>
          <td class="py-3 px-4">
            <div class="text-slate-800 font-semibold">{{ $ind->kontak_person ?: 'HRD / Mentor' }}</div>
            <div class="text-[11px] text-slate-500">{{ $ind->telepon ?: '-' }} • {{ $ind->kota ?: 'Jakarta' }}</div>
            @if($ind->email_pj)
              <div class="text-[11px] font-mono text-slate-400">{{ $ind->email_pj }}</div>
            @endif
          </td>
          <td class="py-3 px-4">
            @if($ind->user)
              <div class="font-mono text-xs text-slate-900 font-bold">{{ $ind->user->email }}</div>
              <div class="text-[11px] text-slate-500 font-mono">
                ID: <strong class="text-slate-700">{{ $ind->user->identifier }}</strong> • Pass: <strong class="text-[#02796B]">password123</strong>
              </div>
            @else
              <span class="text-amber-600 text-[11px] font-semibold">Dibuat saat di-Accept</span>
            @endif
          </td>
          <td class="py-3 px-4 whitespace-nowrap">
            @if($ind->status_verifikasi === 'acc')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="material-symbols-outlined text-[13px]">verified</span> Aktif
              </span>
            @elseif($ind->status_verifikasi === 'reject')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                <span class="material-symbols-outlined text-[13px]">pending</span> Pending
              </span>
            @endif
          </td>
          <td class="py-3 px-4 text-right whitespace-nowrap">
            <div class="inline-flex items-center justify-end gap-1.5">
              @if($ind->status_verifikasi !== 'acc')
                <button type="button" onclick="document.getElementById('modal-accept-ind-{{ $ind->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs inline-flex items-center gap-1 shadow-sm transition-colors">
                  <span class="material-symbols-outlined text-[14px]">check</span> Accept
                </button>

                @if($ind->pengajuanMagangs->isNotEmpty() && $verifiedIndustris->count() > 0)
                  <button type="button" onclick="document.getElementById('modal-forward-ind-{{ $ind->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-[#02796B] border border-teal-200 font-bold text-xs inline-flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">forward</span> Forward
                  </button>
                @endif

                <button type="button" onclick="document.getElementById('modal-reject-ind-{{ $ind->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold text-xs border border-slate-200 transition-colors">
                  Tolak
                </button>
              @endif

              <form method="POST" action="{{ route('admin.industri.destroy', $ind->id) }}" onsubmit="return confirm('Hapus perusahaan {{ $ind->nama_perusahaan }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-500 hover:text-white border border-slate-200 transition-colors" title="Hapus Perusahaan">
                  <span class="material-symbols-outlined text-[15px]">delete</span>
                </button>
              </form>
            </div>
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

<!-- Modal Tambah Mitra oleh Admin -->
<div id="modal-add-industri-admin" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-lg w-full p-5 text-left shadow-2xl border border-slate-200">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
      <h3 class="text-sm font-bold text-slate-900">Tambah Mitra Industri Baru</h3>
      <button type="button" onclick="document.getElementById('modal-add-industri-admin').classList.add('hidden')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
        <span class="material-symbols-outlined text-[18px]">close</span>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.industri.store') }}" class="space-y-3">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan <span class="text-rose-500">*</span></label>
        <input type="text" name="nama_perusahaan" required placeholder="PT ..." class="w-full text-xs rounded-xl border-slate-200 p-2.5">
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha</label>
          <input type="text" name="bidang_usaha" placeholder="Teknologi Informasi" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Kota</label>
          <input type="text" name="kota" placeholder="Jakarta" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        </div>
      </div>
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat</label>
        <textarea name="alamat" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Nama PIC</label>
          <input type="text" name="kontak_person" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Email PIC</label>
          <input type="email" name="email_pj" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Telepon</label>
          <input type="text" name="telepon" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        </div>
      </div>
      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="document.getElementById('modal-add-industri-admin').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0056B3] hover:bg-blue-700 text-white">Simpan &amp; Buat Akun</button>
      </div>
    </form>
  </div>
</div>

<!-- Modals for Tab 2 -->
@foreach($industris as $ind)
  @if($ind->status_verifikasi !== 'acc')
    <div id="modal-accept-ind-{{ $ind->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-lg w-full p-5 text-left shadow-2xl border border-slate-200">
        <h3 class="text-sm font-bold text-slate-900 mb-1">Verifikasi &amp; Accept Perusahaan</h3>
        <p class="text-xs text-slate-500 mb-3">Pastikan data valid sebelum diaktifkan dan dibuatkan akun DUDI.</p>
        <form method="POST" action="{{ route('admin.industri.acc', $ind->id) }}" class="space-y-3">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_perusahaan" required value="{{ $ind->nama_perusahaan }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha</label>
              <input type="text" name="bidang_usaha" value="{{ $ind->bidang_usaha }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Kota</label>
              <input type="text" name="kota" value="{{ $ind->kota }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full text-xs rounded-xl border-slate-200 p-2.5">{{ $ind->alamat }}</textarea>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nama PIC</label>
              <input type="text" name="kontak_person" value="{{ $ind->kontak_person }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Email PIC</label>
              <input type="email" name="email_pj" value="{{ $ind->email_pj }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Telepon</label>
              <input type="text" name="telepon" value="{{ $ind->telepon }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
            </div>
          </div>
          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <button type="button" onclick="document.getElementById('modal-accept-ind-{{ $ind->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white">Accept &amp; Aktifkan</button>
          </div>
        </form>
      </div>
    </div>

    @if($ind->pengajuanMagangs->isNotEmpty() && $verifiedIndustris->count() > 0)
      @php $firstPm = $ind->pengajuanMagangs->first(); @endphp
      <div id="modal-forward-ind-{{ $ind->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-5 text-left shadow-2xl border border-slate-200">
          <h3 class="text-sm font-bold text-slate-900 mb-1">Forward ke Mitra Terdaftar</h3>
          <p class="text-xs text-slate-500 mb-3">Alihkan penempatan <strong>{{ $firstPm->mahasiswa->name ?? '' }}</strong> ke mitra resmi.</p>
          <form method="POST" action="{{ route('admin.pengajuan.forward', $firstPm->id) }}">
            @csrf
            <input type="hidden" name="redirect_tab" value="industri">
            <div class="mb-4">
              <select name="industri_id" required class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                @foreach($verifiedIndustris as $vInd)
                  <option value="{{ $vInd->id }}">{{ $vInd->nama_perusahaan }}</option>
                @endforeach
              </select>
            </div>
            <div class="flex items-center justify-end gap-2">
              <button type="button" onclick="document.getElementById('modal-forward-ind-{{ $ind->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
              <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#02796B] hover:bg-teal-800 text-white">Forward</button>
            </div>
          </form>
        </div>
      </div>
    @endif

    <div id="modal-reject-ind-{{ $ind->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-md w-full p-5 text-left shadow-2xl border border-slate-200">
        <h3 class="text-sm font-bold text-slate-900 mb-1">Tolak Perusahaan</h3>
        <p class="text-xs text-slate-500 mb-3">Berikan alasan penolakan untuk <strong>{{ $ind->nama_perusahaan }}</strong>.</p>
        <form method="POST" action="{{ route('admin.industri.reject', $ind->id) }}">
          @csrf
          <div class="mb-4">
            <textarea name="catatan_admin" rows="3" required placeholder="Alasan penolakan..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
          </div>
          <div class="flex items-center justify-end gap-2">
            <button type="button" onclick="document.getElementById('modal-reject-ind-{{ $ind->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white">Tolak</button>
          </div>
        </form>
      </div>
    </div>
  @endif
@endforeach
@endif

<!-- TAB 3: DATA MAHASISWA & FULL CONTROL -->
@if($tab === 'mahasiswa')
<div class="space-y-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Import CSV -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
      <h3 class="text-sm font-bold font-heading text-slate-800 mb-1">Import CSV Mahasiswa</h3>
      <p class="text-xs text-slate-500 mb-3">Format: <code>NIM, Nama, Email, Prodi, No HP</code></p>
      <form method="POST" action="{{ route('admin.mahasiswa.import') }}" enctype="multipart/form-data" class="space-y-3">
        @csrf
        <input type="file" name="file_mahasiswa" accept=".csv,.txt" required class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#0056B3] border border-slate-200 rounded-xl p-1">
        <button type="submit" class="w-full py-2 rounded-xl bg-[#0056B3] hover:bg-blue-700 text-white font-bold text-xs flex items-center justify-center gap-1.5">
          <span class="material-symbols-outlined text-[15px]">cloud_upload</span>
          <span>Import Data</span>
        </button>
      </form>
    </div>

    <!-- Tambah Mahasiswa Manual -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
      <h3 class="text-sm font-bold font-heading text-slate-800 mb-3">Tambah Mahasiswa Baru</h3>
      <form method="POST" action="{{ route('admin.mahasiswa.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @csrf
        <input type="text" name="identifier" required placeholder="NIM (cth: 2107411099)" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        <input type="text" name="name" required placeholder="Nama Lengkap" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        <input type="email" name="email" required placeholder="Email (@stu.pnj.ac.id)" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
        <div class="flex gap-2">
          <select name="prodi" class="flex-1 text-xs rounded-xl border-slate-200 p-2.5">
            <option value="Teknik Informatika (D4)">Teknik Informatika (D4)</option>
            <option value="Teknik Komputer & Jaringan (D1/D3)">Teknik Komputer &amp; Jaringan</option>
            <option value="Broadband Multimedia (D4)">Broadband Multimedia (D4)</option>
          </select>
          <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shrink-0">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Mahasiswa -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-base font-bold font-heading text-slate-800">Daftar Mahasiswa ({{ $mahasiswas->count() }})</h3>
        <p class="text-xs text-slate-500">Edit data mahasiswa, ubah status pengajuan, atau hapus tempat PKL.</p>
      </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
      <table class="w-full text-left text-xs text-slate-600">
        <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">Mahasiswa</th>
            <th class="py-3 px-4">Prodi &amp; Kontak</th>
            <th class="py-3 px-4">Dosen Pembimbing</th>
            <th class="py-3 px-4">Tempat PKL &amp; Status</th>
            <th class="py-3 px-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          @foreach($mahasiswas as $m)
          @php $pmAktif = $m->pengajuanMagangAktif; @endphp
          <tr class="hover:bg-slate-50/70 transition-colors">
            <td class="py-3 px-4 font-bold text-slate-900">
              <div>{{ $m->name }}</div>
              <div class="font-mono text-slate-400 text-[11px]">{{ $m->identifier }}</div>
            </td>
            <td class="py-3 px-4">
              <div class="text-slate-800">{{ $m->prodi }}</div>
              <div class="text-slate-400 text-[11px]">{{ $m->email }}</div>
            </td>
            <td class="py-3 px-4">
              @if($m->dosen)
                <span class="text-emerald-700 font-bold">{{ $m->dosen->name }}</span>
              @else
                <span class="text-slate-400">-</span>
              @endif
            </td>
            <td class="py-3 px-4">
              @if($pmAktif && $pmAktif->industri)
                <div class="text-[#0056B3] font-bold">{{ $pmAktif->industri->nama_perusahaan }}</div>
                <div class="flex items-center gap-1.5 mt-0.5">
                  <span class="text-[11px] text-slate-500">{{ $pmAktif->posisi_magang ?: 'Intern' }}</span>
                  @if($pmAktif->status_surat_pengantar === 'diterbitkan')
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Terbit</span>
                  @elseif($pmAktif->status_surat_pengantar === 'pending')
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                  @elseif($pmAktif->status_surat_pengantar === 'ditolak')
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                  @else
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Draft</span>
                  @endif
                </div>
              @else
                <span class="text-slate-400">Belum Ada</span>
              @endif
            </td>
            <td class="py-3 px-4 text-right whitespace-nowrap">
              <div class="inline-flex items-center justify-end gap-1.5">
                <button type="button" onclick="document.getElementById('modal-edit-mhs-{{ $m->id }}').classList.remove('hidden')" class="px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-[#0056B3] text-[#0056B3] hover:text-white border border-blue-200 font-bold text-xs inline-flex items-center gap-1 transition-colors">
                  <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                </button>

                @if($pmAktif)
                  <form method="POST" action="{{ route('admin.mahasiswa.destroy-pkl', $m->id) }}" onsubmit="return confirm('Hapus tempat PKL dari {{ $m->name }}?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="redirect_tab" value="mahasiswa">
                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-600 text-amber-800 hover:text-white border border-amber-200 font-bold text-xs inline-flex items-center gap-1 transition-colors">
                      <span class="material-symbols-outlined text-[14px]">domain_disabled</span> Hapus PKL
                    </button>
                  </form>
                @endif

                <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m->id) }}" onsubmit="return confirm('Hapus akun mahasiswa {{ $m->name }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-500 hover:text-white border border-slate-200 transition-colors" title="Hapus Akun">
                    <span class="material-symbols-outlined text-[15px]">delete</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modals Edit Mahasiswa -->
@foreach($mahasiswas as $m)
  @php $pmAktif = $m->pengajuanMagangAktif; @endphp
  <div id="modal-edit-mhs-{{ $m->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-5 text-left shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
        <h3 class="text-sm font-bold text-slate-900">Edit Data &amp; Status: {{ $m->name }}</h3>
        <button type="button" onclick="document.getElementById('modal-edit-mhs-{{ $m->id }}').classList.add('hidden')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
          <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
      </div>

      <form method="POST" action="{{ route('admin.mahasiswa.update', $m->id) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="redirect_tab" value="mahasiswa">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">NIM</label>
            <input type="text" name="identifier" required value="{{ $m->identifier }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" required value="{{ $m->name }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
            <input type="email" name="email" required value="{{ $m->email }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">No. HP</label>
            <input type="text" name="phone" value="{{ $m->phone }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi</label>
            <select name="prodi" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
              <option value="Teknik Informatika (D4)" {{ $m->prodi === 'Teknik Informatika (D4)' ? 'selected' : '' }}>Teknik Informatika (D4)</option>
              <option value="Teknik Komputer & Jaringan (D1/D3)" {{ str_contains($m->prodi ?? '', 'Jaringan') ? 'selected' : '' }}>Teknik Komputer &amp; Jaringan</option>
              <option value="Broadband Multimedia (D4)" {{ str_contains($m->prodi ?? '', 'Multimedia') ? 'selected' : '' }}>Broadband Multimedia (D4)</option>
            </select>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1">Perusahaan Tempat PKL</label>
            <select name="industri_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
              <option value="">-- Belum Ditempatkan --</option>
              @foreach($industris as $indOpt)
                <option value="{{ $indOpt->id }}" {{ $pmAktif?->industri_id === $indOpt->id ? 'selected' : '' }}>
                  {{ $indOpt->nama_perusahaan }} ({{ strtoupper($indOpt->status_verifikasi) }})
                </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Posisi PKL</label>
            <input type="text" name="posisi_magang" value="{{ $pmAktif?->posisi_magang }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Status Surat Pengantar</label>
            <select name="status_surat_pengantar" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
              <option value="pending" {{ $pmAktif?->status_surat_pengantar === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="diterbitkan" {{ $pmAktif?->status_surat_pengantar === 'diterbitkan' ? 'selected' : '' }}>Diterbitkan (Accept)</option>
              <option value="draft" {{ $pmAktif?->status_surat_pengantar === 'draft' ? 'selected' : '' }}>Draft (Buka Kunci Form)</option>
              <option value="ditolak" {{ $pmAktif?->status_surat_pengantar === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $pmAktif?->tanggal_mulai?->format('Y-m-d') }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $pmAktif?->tanggal_selesai?->format('Y-m-d') }}" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Dosen Pembimbing</label>
            <select name="dosen_id" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
              <option value="">-- Belum Ditetapkan --</option>
              @foreach($dosens ?? [] as $dsn)
                <option value="{{ $dsn->id }}" {{ $m->dosen_id === $dsn->id ? 'selected' : '' }}>{{ $dsn->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Status Dosen</label>
            <select name="status_dosen" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
              <option value="pending" {{ $pmAktif?->status_dosen === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="usulan" {{ $pmAktif?->status_dosen === 'usulan' ? 'selected' : '' }}>Usulan</option>
              <option value="acc_kps" {{ $pmAktif?->status_dosen === 'acc_kps' || $m->dosen_id ? 'selected' : '' }}>ACC KPS</option>
            </select>
          </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
          <button type="button" onclick="document.getElementById('modal-edit-mhs-{{ $m->id }}').classList.add('hidden')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0056B3] hover:bg-blue-700 text-white">Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endforeach
@endif

<!-- TAB 4: KREDENSIAL AKUN & RESET PASSWORD -->
@if($tab === 'users')
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
    <div>
      <h2 class="text-base font-bold font-heading text-slate-800">Kredensial &amp; Reset Password</h2>
      <p class="text-xs text-slate-500">Reset password akun pengguna ke <code class="font-bold text-slate-700">password123</code>.</p>
    </div>

    <form method="GET" action="{{ route('dashboard.admin') }}" class="flex items-center gap-2">
      <input type="hidden" name="tab" value="users">
      <input type="text" name="search_user" value="{{ request('search_user') }}" placeholder="Cari nama, NIM/ID, email..." class="text-xs rounded-xl border-slate-200 p-2 w-56">
      <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900">Cari</button>
    </form>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="w-full text-left text-xs text-slate-600">
      <thead class="bg-slate-50 text-[11px] font-bold text-slate-700 uppercase border-b border-slate-200">
        <tr>
          <th class="py-3 px-4">Nama</th>
          <th class="py-3 px-4">ID / NIM / NIP</th>
          <th class="py-3 px-4">Email Login</th>
          <th class="py-3 px-4">Role</th>
          <th class="py-3 px-4 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium">
        @forelse($users as $u)
        <tr class="hover:bg-slate-50/70 transition-colors">
          <td class="py-3 px-4 font-bold text-slate-900">{{ $u->name }}</td>
          <td class="py-3 px-4 font-mono text-slate-700">{{ $u->identifier ?: '-' }}</td>
          <td class="py-3 px-4 font-mono text-slate-800">{{ $u->email }}</td>
          <td class="py-3 px-4">
            @php
              $roleBadge = match($u->role) {
                'admin' => 'bg-blue-100 text-blue-800',
                'kps' => 'bg-purple-100 text-purple-800',
                'dosen' => 'bg-emerald-100 text-emerald-800',
                'industri', 'dudi' => 'bg-teal-100 text-teal-800',
                default => 'bg-slate-100 text-slate-800'
              };
            @endphp
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $roleBadge }}">{{ $u->role }}</span>
          </td>
          <td class="py-3 px-4 text-right whitespace-nowrap">
            <form method="POST" action="{{ route('admin.users.reset-password', $u->id) }}" onsubmit="return confirm('Reset password {{ $u->name }} ke password123?')">
              @csrf
              <input type="hidden" name="new_password" value="password123">
              <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 font-bold text-xs border border-slate-200 inline-flex items-center gap-1 transition-colors">
                <span class="material-symbols-outlined text-[14px]">key</span> Reset Password
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
