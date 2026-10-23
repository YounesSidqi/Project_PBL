<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard & Penempatan PKL - SIM-PKL TIK Politeknik Negeri Jakarta</title>
  <meta name="description" content="Dashboard Sistem Informasi Manajemen Praktik Kerja Lapangan Jurusan Teknik Informatika dan Komputer Politeknik Negeri Jakarta">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Material Symbols Outlined -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              blue: '#0056B3',
              blueDark: '#00428A',
              blueLight: '#EBF3FC',
              tealDark: '#02796B',
              teal: '#009688',
              tealLight: '#E0F2F1',
              white: '#FFFFFF',
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
          },
          boxShadow: {
            'card': '0 4px 20px -2px rgba(0, 86, 179, 0.06), 0 2px 6px -1px rgba(0, 0, 0, 0.04)',
            'card-hover': '0 12px 30px -4px rgba(0, 86, 179, 0.12), 0 4px 10px -2px rgba(0, 0, 0, 0.04)',
            'nav-active': '0 4px 14px 0 rgba(0, 86, 179, 0.25)',
          }
        }
      }
    }
  </script>

  <style>
    * {
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      vertical-align: middle;
    }
    .material-symbols-fill {
      font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    /* Subtle dot grid pattern */
    .bg-grid-pattern {
      background-image: radial-gradient(rgba(0, 86, 179, 0.06) 1px, transparent 1px);
      background-size: 24px 24px;
    }
    /* Custom clean scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>

<body class="bg-[#F8FAFC] font-sans text-gray-800 selection:bg-[#009688]/20 selection:text-[#0056B3] min-h-screen flex antialiased">
  @php
    $activeF1 = $f1Application ?? null;
  @endphp

  <!-- Mobile Sidebar Backdrop -->
  <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

  <!-- Sidebar Navigation Component -->
  @include('layouts.partials.sidebar', ['activePage' => 'dashboard'])

  <!-- Main Wrapper -->
  <div class="lg:pl-72 flex-1 flex flex-col min-h-screen">
    
    <!-- Top Header Bar -->
    <header class="h-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8">
      
      <!-- Left: Mobile Menu Toggle & Breadcrumbs -->
      <div class="flex items-center gap-3 sm:gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Buka menu navigasi">
          <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>

        <div class="flex items-center gap-2 text-xs sm:text-sm">
          <span class="text-slate-500 hidden sm:inline">Portal Magang TIK PNJ</span>
          <span class="material-symbols-outlined text-slate-300 text-[16px] hidden sm:inline">chevron_right</span>
          <span class="font-semibold text-slate-800 font-heading">Dashboard Penempatan</span>
        </div>

        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded-full text-xs font-semibold">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span class="hidden sm:inline">Siklus PKL</span> Aktif
        </span>
      </div>

      <!-- Right: Role Switcher, Notifications, & Profile -->
      <div class="flex items-center gap-2 sm:gap-4">

        <!-- Notification Bell -->
        <div class="relative">
          <button type="button" aria-label="Notifikasi" class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined text-[22px]">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-rose-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center ring-2 ring-white">5</span>
          </button>
        </div>

        <!-- Divider -->
        <div class="h-6 w-px bg-slate-200"></div>

        <!-- User Profile Pill -->
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Ahmad Fauzi' }}</span>
            <span class="text-[11px] text-slate-500 font-medium leading-tight">
              {{ Auth::user()->identifier ? 'NIM: ' . Auth::user()->identifier : 'TIK PNJ' }}
            </span>
          </div>
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm">
            {{ strtoupper(substr(Auth::user()->name ?? 'AF', 0, 2)) }}
          </div>
          <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
            @csrf
            <button type="submit" title="Keluar" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
              <span class="material-symbols-outlined text-[20px]">logout</span>
            </button>
          </form>
        </div>

      </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-[1440px] w-full mx-auto">
      
      @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm animate-fade-in">
          <span class="material-symbols-outlined text-emerald-600 text-[22px]">check_circle</span>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if(session('info'))
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-[#0056B3] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm animate-fade-in">
          <span class="material-symbols-outlined text-[#0056B3] text-[22px]">info</span>
          <span>{{ session('info') }}</span>
        </div>
      @endif

      @if(session('error') || $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm animate-fade-in">
          <span class="material-symbols-outlined text-rose-600 text-[22px]">error</span>
          <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
      @endif
      <!-- Top Hero Identity Card -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0056B3] via-[#004799] to-[#02796B] text-white p-6 sm:p-8 shadow-card">
        <!-- Ambient decorative shapes -->
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute right-48 -bottom-20 w-72 h-72 rounded-full bg-[#009688]/20 blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
          
          <!-- Student Avatar & Academic Identity -->
          <div class="flex items-start sm:items-center gap-4 sm:gap-5">
            <div class="relative flex-shrink-0">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner">
                <span class="material-symbols-outlined text-[38px] sm:text-[44px]">account_circle</span>
              </div>
              <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#009688] rounded-full ring-2 ring-white flex items-center justify-center" title="Akun Aktif">
                <span class="material-symbols-outlined text-white text-[12px] font-bold">check</span>
              </span>
            </div>

            <div class="flex flex-col">
              <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-extrabold font-heading tracking-tight text-white">
                  {{ Auth::user()->name ?? 'Ahmad Fauzi' }}
                </h1>
                <span class="px-2.5 py-0.5 rounded-md bg-white/15 backdrop-blur-sm text-blue-50 text-xs font-mono font-medium tracking-wide">
                  NIM: {{ Auth::user()->identifier ?? '2107411002' }}
                </span>
                <span class="px-2.5 py-0.5 rounded-md bg-[#009688]/30 border border-[#009688]/40 text-teal-100 text-xs font-semibold">
                  Aktif Semester 6
                </span>
              </div>

              <div class="text-blue-100/90 text-xs sm:text-sm mt-2 flex items-center gap-2 sm:gap-3 flex-wrap">
                <span class="font-medium">Program Studi: {{ Auth::user()->prodi ?? 'D4 Teknik Informatika' }}</span>
                <span class="w-1.5 h-1.5 rounded-full bg-blue-200/40"></span>
                <span class="font-medium">Kelas: TI-6A</span>
                <span class="w-1.5 h-1.5 rounded-full bg-blue-200/40"></span>
                <span>Jurusan Teknik Informatika &amp; Komputer PNJ</span>
              </div>
            </div>
          </div>

          <!-- Eligibility Status & Action -->
          <div class="flex items-center gap-3 flex-wrap self-start lg:self-center">
            <div class="bg-white/10 backdrop-blur-md border border-white/15 px-4 py-3 rounded-xl flex items-center gap-3 shadow-inner">
              <div class="w-10 h-10 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">verified</span>
              </div>
              <div class="flex flex-col">
                <span class="text-[11px] text-blue-200 uppercase tracking-wider font-semibold">Status Kelayakan PKL</span>
                <span class="text-sm font-bold text-white font-heading">Memenuhi Syarat (110 SKS)</span>
              </div>
            </div>

            <button type="button" class="bg-white text-[#0056B3] hover:bg-blue-50 px-4 py-3 rounded-xl text-xs sm:text-sm font-bold font-heading flex items-center gap-2 transition-all shadow-md">
              <span class="material-symbols-outlined text-[18px]">badge</span>
              <span>Profil Lengkap</span>
            </button>
          </div>

        </div>
      </div>

      <!-- Dual Column Layout: Left (Placement & Official Letter) + Right (F1 Endorsement & Guidance Agenda) -->
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        <!-- Left Column (7 Cols on xl) -->
        <div class="xl:col-span-7 space-y-6">
          
          <!-- DUDI Placement Detail Card -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-5">
            
            <!-- Placement Header -->
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                  <span class="material-symbols-outlined text-[32px]">cell_tower</span>
                </div>
                <div>
                  <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Industri Mitra PKL (DUDI)</span>
                  <h3 class="text-xl font-bold font-heading text-slate-800 leading-tight mt-0.5">
                    PT Telekomunikasi Seluler (Telkomsel)
                  </h3>
                  <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                    Smart Office Jakarta • Unit Cloud Platform &amp; DevOps Architecture
                  </p>
                </div>
              </div>
            </div>

            <!-- Official Status Banner -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50/50 border border-emerald-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-600"></span>
                </span>
                <div>
                  <span class="text-[11px] text-slate-500 font-medium">Keputusan Seleksi Mitra</span>
                  <p class="text-xs sm:text-sm font-bold text-emerald-800 font-heading">
                    STATUS: DITERIMA RESMI OLEH MITRA DUDI
                  </p>
                </div>
              </div>
              <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold shadow-sm self-start sm:self-auto">
                <span class="material-symbols-outlined text-[14px]">done_all</span>
                <span>Kuota Terkonfirmasi</span>
              </span>
            </div>

            <!-- Placement Metadata Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/60">
                <div class="flex items-center gap-2 text-slate-400 mb-1">
                  <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                  <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Periode Pelaksanaan</span>
                </div>
                <p class="text-sm font-bold text-slate-800 font-heading">10 Feb 2026 s/d 11 Jul 2026</p>
                <span class="text-xs text-slate-500 mt-0.5 block">Durasi: 5 Bulan Penuh (20 Minggu)</span>
              </div>

              <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/60">
                <div class="flex items-center gap-2 text-slate-400 mb-1">
                  <span class="material-symbols-outlined text-[18px]">supervised_user_circle</span>
                  <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pembimbing Industri (Mentor)</span>
                </div>
                <p class="text-sm font-bold text-slate-800 font-heading">Bagas Wicaksono, S.T.</p>
                <span class="text-xs text-slate-500 mt-0.5 block">Lead Infrastructure Cloud Architect</span>
              </div>
            </div>

            <!-- Workplace Snapshot Visual -->
            <div class="relative h-44 w-full rounded-xl overflow-hidden bg-slate-900 shadow-inner group">
              <img class="w-full h-full object-cover opacity-60 mix-blend-luminosity group-hover:scale-105 transition-transform duration-500" 
                   src="https://lh3.googleusercontent.com/aida-public/AB6AXuBAZNGiaRXJwH59iVNwUufz5bYtuLtiOC0hXCETTFkxFw7JXgv5ZzysoHf-mMLgrHDUqLGc8enUfg_As9hoHqZgHZMRAKXuwvdSw7V_12b63Tq2groTZGWbKqbsbP1lES2TQxqG_exQ31bySRBRQMUgMH5lMywmdAu5GpLCzxLRyhjy7SBhaPFRyStGxOSxOMDKv2Jzmo48jJdwiW3vomzvfoQrZpvwH9WFIbD5c85n14Z9S5K7l-rd" 
                   alt="Modern telecommunications corporate office Telkomsel">
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/50 to-transparent p-5 flex flex-col justify-end">
                <span class="text-[11px] font-bold text-[#009688] uppercase tracking-wider">Lokasi Penempatan Magang</span>
                <p class="text-sm sm:text-base font-bold text-white font-heading mt-0.5">
                  Telkomsel Smart Office (TSO) - Gatot Subroto Kav. 52, Jakarta Selatan
                </p>
                <p class="text-xs text-slate-300 mt-1">Mode: Hybrid Working (3 Hari Onsite, 2 Hari Remote)</p>
              </div>
            </div>

            <!-- Quick Metrics Bar -->
            <div class="grid grid-cols-3 gap-3 pt-1 text-center">
              <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60">
                <span class="text-[11px] font-semibold text-slate-500">Logbook Terverifikasi</span>
                <p class="text-lg sm:text-xl font-extrabold text-[#0056B3] font-heading mt-1">
                  16 <span class="text-xs font-normal text-slate-400">/ 20 Hari</span>
                </p>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60">
                <span class="text-[11px] font-semibold text-slate-500">Kehadiran (Absensi)</span>
                <p class="text-lg sm:text-xl font-extrabold text-[#02796B] font-heading mt-1">100%</p>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60">
                <span class="text-[11px] font-semibold text-slate-500">Progress Laporan</span>
                <p class="text-lg sm:text-xl font-extrabold text-[#0056B3] font-heading mt-1">
                  Bab II <span class="text-xs font-normal text-emerald-600 font-semibold">Selesai</span>
                </p>
              </div>
            </div>

          </div>

          <!-- Official Document Card: Surat Pengantar Kajur TIK -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-5">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center flex-shrink-0">
                  <span class="material-symbols-outlined text-[24px]">description</span>
                </div>
                <div>
                  <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Dokumen Administrasi Utama</span>
                  <h4 class="text-lg font-bold font-heading text-slate-800">Surat Pengantar Resmi Jurusan</h4>
                </div>
              </div>
              <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/70 text-xs font-semibold rounded-lg flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px]">lock_open</span>
                <span>Terbit &amp; Berlaku</span>
              </span>
            </div>

            <!-- Letter Detail Container -->
            <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/60 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
              <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                  <span class="text-xs text-slate-500 uppercase font-mono">No. Dokumen:</span>
                  <span class="text-xs font-bold text-[#0056B3] font-mono bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                    042/PL3.TIK/PKL/2026
                  </span>
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-700">
                  Pengesahan Penugasan PKL Mahasiswa Semester Genap 2025/2026
                </p>
                <div class="flex items-center gap-2 text-xs text-slate-600">
                  <span class="material-symbols-outlined text-[16px] text-[#009688]">verified_user</span>
                  <span>Ditandatangani: <strong>Dr. Anita Hidayati, S.Kom., M.Kom.</strong> (Ketua Jurusan TIK)</span>
                </div>
              </div>
            </div>

            <!-- Action & Note -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-1">
              <span class="text-xs text-slate-500 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-500 text-[16px]">info</span>
                <span>Wajib diarsipkan dalam Bab I Laporan Akhir PKL.</span>
              </span>
              <button type="button" onclick="handleDocumentAction(this, 'Mengunduh Surat Pengantar...')" class="w-full sm:w-auto bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl font-bold font-heading text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Unduh Surat Pengantar (PDF)</span>
              </button>
            </div>
          </div>

        </div>

        <!-- Right Column (5 Cols on xl) -->
        <div class="xl:col-span-5 space-y-6">
          
          <!-- Form F1 Card Banner -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-5">
            @php
              $activeF1 = $f1Application ?? null;
              $hasAssignedDosen = !empty(Auth::user()->dosen_id) && $dosen != null;
              $isF1Approved = ($activeF1 && $activeF1->status === 'approved') || $hasAssignedDosen;
              $isF1Pending = ($activeF1 && $activeF1->status === 'pending') && !$hasAssignedDosen;

              $dospem = $dosen ?? ($activeF1?->dosen ?? null);
              $dospemName = $dospem?->name ?? 'Dr. Bambang Sutrisno, M.T.';
              $dospemNip = $dospem?->identifier ?? '198503152010121002';
              $words = explode(' ', trim($dospemName));
              $initials = '';
              foreach ($words as $w) {
                $clean = preg_replace('/[^a-zA-Z]/', '', $w);
                if (strlen($clean) > 0 && !in_array(strtolower($clean), ['dr', 'eng', 'kom', 'mt', 'mkom', 'drs', 'h', 'st', 'si', 'ti'])) {
                  $initials .= strtoupper($clean[0]);
                  if (strlen($initials) >= 2) break;
                }
              }
              if (!$initials) $initials = 'BS';
            @endphp

            <div class="flex items-start justify-between gap-3">
              <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Status Pengesahan Jurusan</span>
                <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Form F1: Kesediaan Pembimbing</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#009688] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">assignment_turned_in</span>
              </div>
            </div>

            @if($isF1Approved)
              <!-- State 1: DISAHKAN / DI-ACC DOSEN -->
              <div class="p-4 sm:p-5 rounded-xl bg-slate-50/80 border border-slate-200/60 space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Dosen Pengampu / Pembimbing PKL</span>
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[11px] font-bold">
                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                    <span>Disetujui &amp; Disahkan</span>
                  </span>
                </div>
                <div class="flex items-center gap-3.5">
                  <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-sm flex-shrink-0">
                    {{ $initials }}
                  </div>
                  <div class="flex flex-col min-w-0">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 font-heading truncate">{{ $dospemName }}</span>
                    <span class="text-xs text-slate-500 font-mono">NIP: {{ $dospemNip }}</span>
                  </div>
                </div>
              </div>

              <!-- Action: Download / View PDF Surat Pernyataan -->
              <a href="{{ $activeF1 ? route('f1.surat.download', $activeF1->id) : '#' }}" target="_blank" class="w-full bg-[#0056B3] hover:bg-[#00428A] text-white font-bold font-heading text-xs sm:text-sm py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                <span>Lihat Surat Pernyataan Kesediaan Dosen (PDF)</span>
              </a>

            @elseif($isF1Pending)
              <!-- State 2: MENUNGGU PERSETUJUAN & UPLOAD PDF DARI DOSEN -->
              <div class="p-4 sm:p-5 rounded-xl bg-amber-50/70 border border-amber-200/80 space-y-3.5">
                <div class="flex items-center justify-between">
                  <span class="text-[11px] text-amber-800 font-bold uppercase tracking-wider">Pengajuan Form F1</span>
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[11px] font-bold">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Menunggu Persetujuan Dosen</span>
                  </span>
                </div>

                <div class="flex items-center gap-3.5">
                  <div class="w-11 h-11 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-sm flex-shrink-0">
                    {{ $initials }}
                  </div>
                  <div class="flex flex-col min-w-0">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 font-heading truncate">{{ $activeF1->dosen->name ?? $dospemName }}</span>
                    <span class="text-xs text-slate-500 font-mono">NIP: {{ $activeF1->dosen->identifier ?? $dospemNip }}</span>
                  </div>
                </div>

                <div class="p-3 bg-white/90 rounded-xl border border-amber-200 text-xs text-slate-600 space-y-1">
                  <p><strong class="text-slate-800">Tempat PKL:</strong> {{ $activeF1->company_name }}</p>
                  <p><strong class="text-slate-800">Topik PKL:</strong> {{ $activeF1->internship_title }}</p>
                  <p class="text-[11px] text-slate-500 font-mono pt-1 border-t border-amber-100 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px] text-amber-600">schedule</span>
                    <span>Diajukan pada: {{ $activeF1->created_at ? $activeF1->created_at->translatedFormat('d M Y, H:i') : now()->translatedFormat('d M Y, H:i') }} WIB ({{ $activeF1->created_at ? $activeF1->created_at->diffForHumans() : 'baru saja' }})</span>
                  </p>
                  <p class="text-[11px] text-amber-700 pt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">info</span>
                    <span>Dosen sedang meninjau pengajuan Anda dan akan menerbitkan Surat Pernyataan Kesediaan (PDF) sebelum ACC.</span>
                  </p>
                </div>

                <!-- Cancel / Change Button -->
                <form action="{{ route('f1.cancel', $activeF1->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini dan memilih dosen pembimbing lain?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="w-full bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 font-bold font-heading text-xs py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    <span>Batalkan / Ganti Pilihan Dosen</span>
                  </button>
                </form>
              </div>

            @else
              <!-- State 3: BELUM MEMILIH DOSEN PEMBIMBING -->
              <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-center space-y-3.5">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-blue-100/70 text-[#0056B3] flex items-center justify-center">
                  <span class="material-symbols-outlined text-[28px]">person_search</span>
                </div>
                <div>
                  <h4 class="text-sm font-bold font-heading text-slate-800">Belum Ada Dosen Pembimbing</h4>
                  <p class="text-xs text-slate-500 mt-1">
                    Anda belum mengajukan dosen pembimbing PKL. Silakan pilih dosen pembimbing yang sesuai dengan bidang dan topik PKL Anda.
                  </p>
                </div>

                <button type="button" onclick="openSelectDosenModal()" class="w-full bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#005f54] text-white font-bold font-heading text-xs sm:text-sm py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md">
                  <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                  <span>Pilih Dosen Pembimbing (Form F1)</span>
                </button>
              </div>
            @endif
          </div>

          <!-- Agenda Bimbingan Dospem Widget -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0056B3] flex items-center justify-center">
                  <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                </div>
                <h4 class="text-base font-bold font-heading text-slate-800">Agenda Bimbingan Dospem</h4>
              </div>
              <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-[#0056B3]">Tahap Form F3</span>
            </div>

            <!-- Schedule List -->
            <div class="space-y-2.5">
              
              <!-- Event 1 (Completed) -->
              <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white flex flex-col items-center justify-center font-mono font-bold leading-none shadow-sm flex-shrink-0">
                    <span class="text-[9px]">FEB</span>
                    <span class="text-xs">20</span>
                  </div>
                  <div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-800 font-heading">Bimbingan Bab I &amp; II (Logbook F3-1)</h5>
                    <p class="text-xs text-slate-500">Metode: Daring via Zoom PKL • 15:30 WIB</p>
                  </div>
                </div>
                <span class="material-symbols-outlined text-emerald-500 text-[20px]" title="Bimbingan Selesai">task_alt</span>
              </div>

              <!-- Event 2 (Upcoming) -->
              <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-700 flex flex-col items-center justify-center font-mono font-bold leading-none flex-shrink-0">
                    <span class="text-[9px]">MAR</span>
                    <span class="text-xs">12</span>
                  </div>
                  <div>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-800 font-heading">Review Kemajuan Lapangan (Logbook F3-2)</h5>
                    <p class="text-xs text-slate-500">Laporan Docker &amp; CI/CD pipeline</p>
                  </div>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-[#0056B3] text-[10px] font-bold uppercase tracking-wider">
                  Mendatang
                </span>
              </div>

            </div>
          </div>

        </div>

      </div>

      <!-- Mahasiswa Logbook Section (Form F2-F4) -->
      <div id="logbook-section" class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Logbook Kegiatan PKL</span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs text-slate-500 font-medium">Terhubung ke Dosen Pembimbing ({{ $dospemName ?? 'Dr. Bambang Sutrisno, M.T.' }})</span>
            </div>
            <h3 class="text-lg sm:text-xl font-bold font-heading text-slate-800 mt-0.5">
              Jurnal Harian &amp; Lembar Kerja Bimbingan
            </h3>
          </div>
          <button type="button" onclick="openCreateLogbookModal()" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl font-bold font-heading text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-md self-start sm:self-auto">
            <span class="material-symbols-outlined text-[18px]">post_add</span>
            <span>+ Tulis Logbook Baru</span>
          </button>
        </div>

        <!-- Logbooks List -->
        <div class="space-y-4">
          @forelse($logbooks ?? [] as $lb)
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200 hover:border-blue-300 transition-all space-y-3">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                  <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#0056B3] flex items-center justify-center font-bold text-xs">
                    <span class="material-symbols-outlined text-[18px]">history_edu</span>
                  </span>
                  <div>
                    <h4 class="text-sm sm:text-base font-bold font-heading text-slate-800">{{ $lb->subject }}</h4>
                    <span class="text-xs text-slate-500 font-mono flex items-center gap-1 mt-0.5">
                      <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                      <span>{{ $lb->tanggal->translatedFormat('d F Y') }}</span>
                    </span>
                  </div>
                </div>

                <div>
                  @if($lb->status === 'approved')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                      <span class="material-symbols-outlined text-[14px]">check_circle</span>
                      <span>Disetujui Dosen Pembimbing</span>
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                      <span class="material-symbols-outlined text-[14px] animate-spin">refresh</span>
                      <span>Menunggu Validasi Dosen</span>
                    </span>
                  @endif
                </div>
              </div>

              <!-- Long Text Content (Uraian Kegiatan) -->
              <div class="p-4 bg-white rounded-xl border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-normal">
                {{ $lb->content }}
              </div>

              @if($lb->feedback)
                <div class="p-3 bg-blue-50/80 border border-blue-200/80 rounded-xl text-xs text-[#0056B3] flex items-start gap-2">
                  <span class="material-symbols-outlined text-[18px] mt-0.5 flex-shrink-0">comment</span>
                  <div>
                    <strong class="font-bold">Catatan Dosen Pembimbing:</strong>
                    <p class="mt-0.5 text-slate-700">{{ $lb->feedback }}</p>
                  </div>
                </div>
              @endif
            </div>
          @empty
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200 space-y-3">
              <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0056B3] mx-auto flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">assignment</span>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-800">Belum Ada Logbook yang Ditulis</h4>
                <p class="text-xs text-slate-500 mt-1">Mulai tulis aktivitas PKL harian Anda agar dapat langsung ditinjau oleh Dosen Pembimbing.</p>
              </div>
              <button type="button" onclick="openCreateLogbookModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0056B3] text-white text-xs font-bold rounded-xl hover:bg-[#00428A] transition-colors">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>Tulis Logbook Pertama</span>
              </button>
            </div>
          @endforelse
        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 px-8 border-t border-slate-200/80 bg-white text-center text-xs text-slate-500">
      <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>© 2026 Jurusan Teknik Informatika dan Komputer - Politeknik Negeri Jakarta</p>
        <p class="text-slate-400">Sistem Informasi Manajemen Praktik Kerja Lapangan (SIM-PKL)</p>
      </div>
    </footer>

  </div>

  <!-- MODAL: Tambah Logbook Baru Mahasiswa -->
  <div id="modal-create-logbook" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Workspace Logbook Mahasiswa (F2-F4)</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Tulis Logbook Aktivitas PKL</h3>
          <p class="text-xs text-slate-500 mt-0.5">Catatan ini akan otomatis diteruskan ke Dosen Pembimbing Anda.</p>
        </div>
        <button onclick="closeCreateLogbookModal()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form action="{{ route('logbooks.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Subject -->
        <div>
          <label for="logbook-subject" class="block text-xs font-bold text-slate-700 mb-1.5">
            Subject / Topik Kegiatan <span class="text-rose-500">*</span>
          </label>
          <input type="text" id="logbook-subject" name="subject" required placeholder="Contoh: Konfigurasi Multi-Cloud Terraform Manifest & Provisioning EKS" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- Tanggal -->
        <div>
          <label for="logbook-tanggal" class="block text-xs font-bold text-slate-700 mb-1.5">
            Tanggal Kegiatan <span class="text-rose-500">*</span>
          </label>
          <input type="date" id="logbook-tanggal" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- Long Text Content -->
        <div>
          <label for="logbook-content" class="block text-xs font-bold text-slate-700 mb-1.5">
            Uraian Rinci Aktivitas / Kegiatan (Long Text) <span class="text-rose-500">*</span>
          </label>
          <textarea id="logbook-content" name="content" rows="6" required placeholder="Tuliskan secara lengkap deskripsi tugas, fitur yang dikerjakan, teknologi yang dipakai, hasil yang dicapai, serta kendala teknis yang dihadapi di tempat magang..." class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]"></textarea>
          <span class="text-[11px] text-slate-400 mt-1 block">Pastikan uraian jelas dan mencakup poin-poin capaian pembelajaran PKL.</span>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <button type="button" onclick="closeCreateLogbookModal()" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Batal
          </button>
          <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-md">
            <span class="material-symbols-outlined text-[18px]">send</span>
            <span>Simpan &amp; Kirim ke Dosen</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: Pilih Dosen Pembimbing PKL (Form F1) -->
  <div id="modal-select-dosen" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up max-h-[92vh] flex flex-col">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4 flex-shrink-0">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Form F1: Kesediaan Pembimbing</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Pilih Dosen Pembimbing PKL</h3>
          <p class="text-xs text-slate-500 mt-0.5">Pilih dosen yang sesuai dengan bidang atau topik magang industri Anda.</p>
        </div>
        <button type="button" onclick="closeSelectDosenModal()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form action="{{ route('f1.select-dosen') }}" method="POST" class="space-y-4 overflow-y-auto pr-1 flex-1">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="f1-company-name" class="block text-xs font-bold text-slate-700 mb-1">
              Instansi / Perusahaan Tempat PKL <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="f1-company-name" name="company_name" required value="" placeholder="Contoh: PT Telekomunikasi Seluler (Telkomsel)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
          </div>
          <div>
            <label for="f1-internship-title" class="block text-xs font-bold text-slate-700 mb-1">
              Rencana Topik / Judul PKL <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="f1-internship-title" name="internship_title" required value="" placeholder="Contoh: Cloud Infrastructure & Kubernetes Microservices Platform" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="block text-xs font-bold text-slate-700">
              Pilih Dosen Pembimbing PKL <span class="text-rose-500">*</span>
            </label>
            <span class="text-[11px] text-slate-500">Tersedia {{ count($allDosen ?? []) }} Dosen Jurusan TIK</span>
          </div>

          <!-- Search Lecturer -->
          <div class="relative mb-3">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" id="dosen-search-box" onkeyup="filterDosenListModal()" placeholder="Cari dosen berdasarkan nama atau NIP..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
          </div>

          <!-- Lecturer List Options -->
          <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1" id="dosen-options-container">
            @forelse($allDosen ?? [] as $d)
              @php
                $words = explode(' ', trim($d->name));
                $dInitials = '';
                foreach ($words as $w) {
                  $clean = preg_replace('/[^a-zA-Z]/', '', $w);
                  if (strlen($clean) > 0 && !in_array(strtolower($clean), ['dr', 'eng', 'kom', 'mt', 'mkom', 'drs', 'h', 'st', 'si', 'ti'])) {
                    $dInitials .= strtoupper($clean[0]);
                    if (strlen($dInitials) >= 2) break;
                  }
                }
                if (!$dInitials) $dInitials = 'DS';
              @endphp
              <label class="dosen-option-item flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-[#0056B3] hover:bg-blue-50/40 cursor-pointer transition-all has-[:checked]:border-[#0056B3] has-[:checked]:bg-blue-50/70" data-search="{{ strtolower($d->name . ' ' . $d->identifier . ' ' . $d->prodi) }}">
                <div class="flex items-center gap-3 min-w-0">
                  <input type="radio" name="dosen_id" value="{{ $d->id }}" required {{ $loop->first ? 'checked' : '' }} class="w-4 h-4 text-[#0056B3] focus:ring-[#0056B3] border-slate-300">
                  <div class="w-9 h-9 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                    {{ $dInitials }}
                  </div>
                  <div class="flex flex-col min-w-0">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 font-heading truncate">{{ $d->name }}</span>
                    <span class="text-[11px] text-slate-500 font-mono">NIP: {{ $d->identifier ?? '-' }} • {{ $d->prodi ?? 'Teknik Informatika' }}</span>
                  </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                  <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ ($d->students_count ?? 0) >= 7 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                    Kuota: {{ $d->students_count ?? 0 }}/7
                  </span>
                </div>
              </label>
            @empty
              <p class="text-xs text-slate-500 text-center py-4">Belum ada data dosen terdaftar.</p>
            @endforelse
          </div>
        </div>

        <div class="p-3 bg-blue-50/80 rounded-xl border border-blue-200 text-xs text-[#0056B3] flex items-start gap-2.5">
          <span class="material-symbols-outlined text-[20px] flex-shrink-0 mt-0.5">info</span>
          <p class="leading-relaxed">
            Setelah memilih, pengajuan Form F1 akan langsung masuk ke akun Dosen. 
            <strong>Dosen wajib mengunggah Surat Pernyataan Kesediaan (PDF)</strong> sebelum pengajuan disetujui secara resmi.
          </p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 flex-shrink-0">
          <button type="button" onclick="closeSelectDosenModal()" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Batal
          </button>
          <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-md">
            <span class="material-symbols-outlined text-[18px]">send</span>
            <span>Kirim Pengajuan Form F1</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Interactive Scripts -->
  <script>
    // Toggle Mobile Sidebar Drawer
    function toggleSidebar() {
      const sidebar = document.getElementById('main-sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }
    }

    // Modal Create Logbook Handlers
    function openCreateLogbookModal() {
      const modal = document.getElementById('modal-create-logbook');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeCreateLogbookModal() {
      const modal = document.getElementById('modal-create-logbook');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    // Modal Select Dosen Handlers
    function openSelectDosenModal() {
      const modal = document.getElementById('modal-select-dosen');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeSelectDosenModal() {
      const modal = document.getElementById('modal-select-dosen');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function filterDosenListModal() {
      const query = (document.getElementById('dosen-search-box')?.value || '').toLowerCase();
      const items = document.querySelectorAll('.dosen-option-item');
      items.forEach(item => {
        const text = item.getAttribute('data-search') || '';
        item.style.display = text.includes(query) ? 'flex' : 'none';
      });
    }

    // Interactive button state demonstration for PDF actions
    function handleDocumentAction(btn, loadingMessage) {
      const originalHTML = btn.innerHTML;
      btn.innerHTML = `<span class="material-symbols-outlined text-[18px] animate-spin">sync</span><span>${loadingMessage}</span>`;
      btn.disabled = true;
      btn.classList.add('opacity-80', 'cursor-not-allowed');

      setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        btn.classList.remove('opacity-80', 'cursor-not-allowed');
        
        // Show lightweight toast notification
        showToast('Dokumen berhasil diverifikasi dan disiapkan.');
      }, 1200);
    }

    // Simple Toast Notification
    function showToast(message) {
      const existingToast = document.getElementById('sim-toast');
      if (existingToast) existingToast.remove();

      const toast = document.createElement('div');
      toast.id = 'sim-toast';
      toast.className = 'fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-2.5 z-50 text-xs sm:text-sm animate-fade-in transition-all';
      toast.innerHTML = `
        <span class="material-symbols-outlined text-emerald-400 text-[20px]">check_circle</span>
        <span>${message}</span>
      `;
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
  </script>

</body>
</html>