@extends('layouts.app', [
  'title' => 'Form F4: Buku Penghubung Dosen Pembimbing - SIM-PKL TIK',
  'pageTitle' => 'Form F4: Bimbingan Dosen',
  'breadcrumbParent' => 'Portal Mahasiswa TIK',
  'activePage' => 'f4'
])

@section('content')
<!-- Sub-header Breadcrumb & Header Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Mahasiswa</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Kurikulum PKL TIK</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Form F4 (Buku Penghubung Dosen Pembimbing)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F4: Buku Bimbingan Dosen Pembimbing
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Rekam konsultasi berkala, arahan akademik dosen pembimbing, dan persyaratan pendaftaran sidang PKL (Form F7).
    </p>
  </div>

  <!-- Action Button -->
  <button type="button" onclick="showToast('Pengajuan jadwal bimbingan baru berhasil dikirimkan ke Dr. Bambang Sutrisno, M.T.')" class="h-10 px-4 rounded-xl bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#005f54] text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-md self-start md:self-auto">
    <span class="material-symbols-outlined text-[18px]">add_comment</span>
    <span>+ Ajukan Sesi Bimbingan</span>
  </button>
</div>

<!-- Dosen Info & Guidance Progress Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
  <!-- Dosen Card -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
      BS
    </div>
    <div class="min-w-0">
      <span class="text-[10px] font-bold text-[#02796B] uppercase tracking-wider block">Dosen Pembimbing PKL</span>
      <h3 class="text-sm font-bold text-slate-800 truncate">Dr. Bambang Sutrisno, M.T.</h3>
      <span class="text-xs text-slate-500 font-mono">NIP: 198503152010121002</span>
    </div>
  </div>

  <!-- Sesi Terlaksana -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Sesi Terlaksana</span>
      <div class="flex items-baseline gap-1 mt-1">
        <span class="text-2xl font-extrabold text-[#0056B3] font-heading">3</span>
        <span class="text-xs text-slate-500">/ Min. 4 Sesi</span>
      </div>
      <span class="text-[11px] text-amber-700 font-medium">Kurang 1 sesi untuk syarat sidang F7</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">co_present</span>
    </div>
  </div>

  <!-- Status Kelayakan -->
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
    <div>
      <span class="text-xs text-slate-400 font-semibold block uppercase">Kelayakan Sidang F7</span>
      <span class="text-sm font-bold text-slate-800 block mt-1">Menuju Verifikasi</span>
      <span class="text-[11px] text-emerald-600 font-medium">Naskah Bab I-III disetujui</span>
    </div>
    <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
      <span class="material-symbols-outlined text-[22px]">verified</span>
    </div>
  </div>
</div>

<!-- Log Sesi Bimbingan Dosen -->
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card space-y-5">
  <div class="flex items-center justify-between pb-4 border-b border-slate-100">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Riwayat Catatan Bimbingan Akademik</h2>
      <p class="text-xs text-slate-500">Semua sesi asistensi dan arahan revisi yang disahkan oleh dosen pembimbing.</p>
    </div>
    <span class="text-xs font-semibold px-3 py-1 bg-teal-50 text-[#02796B] rounded-lg">
      3 Sesi Terkonfirmasi
    </span>
  </div>

  <div class="space-y-4">
    <!-- Sesi 1 -->
    <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 flex flex-col md:flex-row gap-4 items-start">
      <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">1</div>
      <div class="flex-1 space-y-1.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base font-heading">Konsultasi Bab I &amp; Penyesuaian Lingkup Form F2</h4>
          <span class="text-xs text-slate-400 font-mono">25 Feb 2026 • Daring via Zoom</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/70">
          <strong>Arahan Dosen:</strong> Latar belakang sudah selaras dengan portofolio Cloud Engineer di Telkomsel. Pastikan arsitektur sistem digambarkan dengan standar C4 model pada Bab II.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">verified</span> ACC Bab I
          </span>
          <span class="text-xs font-mono text-slate-400">Tandatangan Digital: NIP.198503152010121002</span>
        </div>
      </div>
    </div>

    <!-- Sesi 2 -->
    <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 flex flex-col md:flex-row gap-4 items-start">
      <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">2</div>
      <div class="flex-1 space-y-1.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base font-heading">Review Metodologi Pelaksanaan &amp; Verifikasi Logbook Sesi 1-20</h4>
          <span class="text-xs text-slate-400 font-mono">18 Mar 2026 • Portal SIM-PKL</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/70">
          <strong>Arahan Dosen:</strong> Uraian logbook harian sudah representatif dengan kompetensi DevOps. Lampiran log sistem harus disensor dari kredensial rahasia industri.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">verified</span> Validasi Logbook Periode 1
          </span>
          <span class="text-xs font-mono text-slate-400">Tandatangan Digital: NIP.198503152010121002</span>
        </div>
      </div>
    </div>

    <!-- Sesi 3 -->
    <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 flex flex-col md:flex-row gap-4 items-start">
      <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">3</div>
      <div class="flex-1 space-y-1.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
          <h4 class="font-bold text-slate-800 text-sm sm:text-base font-heading">Evaluasi Progres Penulisan Laporan Akhir (Bab III &amp; IV)</h4>
          <span class="text-xs text-slate-400 font-mono">08 Apr 2026 • Lab Jaringan Gd. AA</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/70">
          <strong>Arahan Dosen:</strong> Bab IV (Pembahasan Proyek) perlu penambahan grafik performa latency sebelum vs setelah implementasi PgBouncer dan ArgoCD.
        </p>
        <div class="flex items-center gap-2 pt-1">
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold">
            <span class="material-symbols-outlined text-[13px]">verified</span> Revisi Minor Bab IV
          </span>
          <span class="text-xs font-mono text-slate-400">Tandatangan Digital: NIP.198503152010121002</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
