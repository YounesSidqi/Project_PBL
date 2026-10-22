@extends('layouts.app', [
  'title' => 'Form F2: User Requirement Industri - SIM-PKL TIK Politeknik Negeri Jakarta',
  'pageTitle' => 'Form F2: Requirement Industri',
  'breadcrumbParent' => 'Portal Mahasiswa TIK',
  'activePage' => 'f2'
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
      <span class="text-[#0056B3] font-semibold">Form F2 (User Requirement Industri)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F2: User Requirement Industri
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Pemetaan kebutuhan kompetensi dan backlog tugas teknis mahasiswa di tempat Praktik Kerja Lapangan.
    </p>
  </div>

  <!-- Status Chip & Actions -->
  <div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-[#02796B] border border-teal-200 text-xs font-bold shadow-sm">
      <span class="material-symbols-outlined text-[16px]">verified</span>
      <span>Disetujui KPS TI &amp; Mitra DUDI</span>
    </span>
    <button type="button" onclick="showToast('Mengunduh berkas matriks Form F2 (PDF)...')" class="h-10 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs sm:text-sm transition-colors flex items-center gap-2 shadow-sm">
      <span class="material-symbols-outlined text-[18px]">file_download</span>
      <span>Unduh PDF F2</span>
    </button>
  </div>
</div>

<!-- Mitra Placement Context Card -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-lg shadow-sm">
      <span class="material-symbols-outlined text-[24px]">domain</span>
    </div>
    <div>
      <span class="text-[11px] font-bold uppercase tracking-wider text-[#02796B]">Penempatan DUDI</span>
      <h3 class="text-base font-bold text-slate-800 font-heading">PT Telekomunikasi Seluler (Telkomsel)</h3>
      <p class="text-xs text-slate-500">Divisi Cloud Infrastructure &amp; DevOps Engineering • Jakarta Selatan</p>
    </div>
  </div>
  <div class="flex items-center gap-4 text-xs text-slate-600 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200/60">
    <div>
      <span class="text-slate-400 block text-[10px] uppercase font-bold">Mentor Industri:</span>
      <strong class="text-slate-800">Bagas Wicaksono, S.T.</strong>
    </div>
    <div class="border-l border-slate-200 pl-4">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">Dosen Pembimbing:</span>
      <strong class="text-slate-800">Dr. Bambang Sutrisno, M.T.</strong>
    </div>
  </div>
</div>

<!-- Backlog Kompetensi DUDI (Mini Kanban 3 Kolom) -->
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-card space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
    <div>
      <h2 class="text-lg font-bold font-heading text-slate-800">Matriks Kebutuhan &amp; Sasaran Kompetensi Teknis</h2>
      <p class="text-xs text-slate-500">Daftar backlog proyek dan kompetensi yang harus dicapai selama PKL.</p>
    </div>
    <span class="text-xs font-semibold px-3 py-1 bg-blue-50 text-[#0056B3] rounded-lg">
      Total 6 Target Kompetensi
    </span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <!-- Kolom 1: To-Do -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
          <span class="text-xs font-bold font-heading text-slate-800">Direncanakan (To-Do)</span>
        </div>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">2</span>
      </div>
      
      <div class="space-y-3">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-05</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Disaster Recovery Multi-Region Drill</h4>
          <p class="text-xs text-slate-500">Uji failover automated database replikasi cluster Jakarta-Surabaya.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-slate-400">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">calendar_month</span> Target: Mei M2</span>
            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">Infrastruktur</span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-slate-400 font-mono">REQ-TIK-06</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Audit Keamanan CIS Benchmark K8s</h4>
          <p class="text-xs text-slate-500">Hardening pod security admission dan network policy calico.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-slate-400">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">calendar_month</span> Target: Mei M3</span>
            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">Security</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom 2: In Progress -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
          <span class="text-xs font-bold font-heading text-slate-800">Sedang Berjalan</span>
        </div>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">1</span>
      </div>

      <div class="space-y-3">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
          <span class="text-[10px] text-amber-700 font-mono font-bold">REQ-TIK-04</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Penerapan Observability Stack (Tempo &amp; Loki)</h4>
          <p class="text-xs text-slate-500">Implementasi distributed tracing dan centralized logging di cluster OpenShift.</p>
          <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
            <div class="bg-amber-500 h-1.5 rounded-full w-[65%]"></div>
          </div>
          <div class="flex items-center justify-between pt-1 text-[11px] text-slate-400">
            <span class="text-amber-700 font-bold">Progres 65%</span>
            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-medium">Monitoring</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom 3: Done -->
    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col space-y-3">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-[#009688]"></span>
          <span class="text-xs font-bold font-heading text-slate-800">Selesai &amp; Terverifikasi</span>
        </div>
        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">3</span>
      </div>

      <div class="space-y-3">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-01</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Otomasi Provisioning VM via Terraform</h4>
          <p class="text-xs text-slate-500">Modul reusable HCL untuk VM private cloud Telkomsel.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-[#02796B]">
            <span class="font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">check_circle</span> Terverifikasi</span>
            <span class="px-2 py-0.5 rounded bg-teal-50 font-semibold">IaC</span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-02</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Setup CI/CD Pipeline GitOps ArgoCD</h4>
          <p class="text-xs text-slate-500">Otomasi deployment manifest dan sync state repository konfigurasi.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-[#02796B]">
            <span class="font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">check_circle</span> Terverifikasi</span>
            <span class="px-2 py-0.5 rounded bg-teal-50 font-semibold">DevOps</span>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
          <span class="text-[10px] text-[#02796B] font-mono font-bold">REQ-TIK-03</span>
          <h4 class="text-xs sm:text-sm font-bold text-slate-800">Konfigurasi Helm Chart Microservices</h4>
          <p class="text-xs text-slate-500">Packaging manifest k8s berparameter untuk staging dan production.</p>
          <div class="flex items-center justify-between pt-2 text-[11px] text-[#02796B]">
            <span class="font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">check_circle</span> Terverifikasi</span>
            <span class="px-2 py-0.5 rounded bg-teal-50 font-semibold">Cloud</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
