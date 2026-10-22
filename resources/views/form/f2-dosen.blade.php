@extends('layouts.app', [
  'title' => 'Form F2: Capaian Kompetensi Industri - SIM-PKL Dosen',
  'pageTitle' => 'Form F2: Capaian Industri',
  'breadcrumbParent' => 'Portal Dosen TIK',
  'activePage' => 'f2'
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
      <span class="text-[#0056B3] font-semibold">Form F2 (Capaian Kompetensi DUDI)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F2: Pemantauan Capaian Kompetensi Industri
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Sinkronisasi dan verifikasi target capaian kompetensi magang mahasiswa bimbingan di mitra DUDI.
    </p>
  </div>

  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold shadow-sm self-start md:self-auto">
    <span class="material-symbols-outlined text-[16px]">school</span>
    <span>Kurikulum TIK Berbasis OBE 2026</span>
  </span>
</div>

<!-- Mahasiswa Selector Card -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-lg shadow-sm">
      AF
    </div>
    <div>
      <div class="flex items-center gap-2">
        <h3 class="text-base font-bold text-slate-800 font-heading">Ahmad Fauzi</h3>
        <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-xs font-mono font-semibold">NIM: 2107411002</span>
      </div>
      <p class="text-xs text-slate-500 mt-0.5">PT Telekomunikasi Seluler • Cloud Platform &amp; DevOps Engineering</p>
    </div>
  </div>

  <div class="flex items-center gap-2">
    <button type="button" onclick="showToast('Rekapitulasi capaian Form F2 diekspor.')" class="h-9 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5 shadow-sm">
      <span class="material-symbols-outlined text-[16px]">file_download</span> Unduh Form F2
    </button>
  </div>
</div>

<!-- Matriks Kompetensi F2 (Kanban) -->
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card space-y-5">
  <div class="flex items-center justify-between pb-4 border-b border-slate-100">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Status Capaian Kompetensi Teknis Lapangan</h2>
      <p class="text-xs text-slate-500">Hasil sinkronisasi backlog kompetensi mahasiswa di tempat magang.</p>
    </div>
    <span class="text-xs font-semibold px-3 py-1 bg-teal-50 text-[#02796B] rounded-lg">
      3 Selesai • 1 Berjalan • 2 Direncanakan
    </span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <!-- To Do -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold text-slate-700">Direncanakan (To-Do)</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">2</span>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-05</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Disaster Recovery Multi-Region Drill</h4>
        <p class="text-xs text-slate-500">Mahasiswa: Ahmad Fauzi • Target: Mei 2026</p>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-06</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Audit Keamanan CIS Benchmark K8s</h4>
        <p class="text-xs text-slate-500">Mahasiswa: Ahmad Fauzi • Target: Mei 2026</p>
      </div>
    </div>

    <!-- In Progress -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold text-amber-800">Sedang Berjalan</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">1</span>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-amber-700 font-mono font-bold">REQ-TIK-04</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Penerapan Observability Stack (Tempo &amp; Loki)</h4>
        <p class="text-xs text-slate-500">Mahasiswa: Ahmad Fauzi • Progres: 65%</p>
      </div>
    </div>

    <!-- Done -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold text-[#02796B]">Selesai &amp; Terverifikasi</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">3</span>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-01</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Otomasi Provisioning VM via Terraform</h4>
        <p class="text-xs text-slate-500">Telah diverifikasi pada logbook sesi #22</p>
      </div>
      <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
        <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-02</span>
        <h4 class="text-xs sm:text-sm font-bold text-slate-800">Setup CI/CD Pipeline GitOps ArgoCD</h4>
        <p class="text-xs text-slate-500">Telah diverifikasi pada logbook sesi #23</p>
      </div>
    </div>
  </div>
</div>
@endsection
