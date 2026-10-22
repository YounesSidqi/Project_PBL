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
        
        <!-- Role Switcher Badge / Select -->
        <div class="hidden md:flex items-center bg-slate-100/80 hover:bg-slate-100 rounded-xl px-3 py-1.5 border border-slate-200/60 text-xs font-medium text-slate-700">
          <span class="material-symbols-outlined text-slate-400 text-[16px] mr-1.5">switch_account</span>
          <span class="text-slate-500 mr-1.5">Akses:</span>
          <select id="role-switcher" onchange="window.location.href = '/dashboard?role=' + this.value" class="bg-transparent font-semibold text-slate-800 focus:outline-none cursor-pointer">
            <option value="mahasiswa" {{ (request('role', Auth::user()->role ?? 'mahasiswa')) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            <option value="dosen" {{ (request('role', Auth::user()->role ?? 'mahasiswa')) == 'dosen' ? 'selected' : '' }}>Dosen Pembimbing</option>
            <option value="dudi" {{ (request('role', Auth::user()->role ?? 'mahasiswa')) == 'dudi' ? 'selected' : '' }}>Supervisor DUDI</option>
            <option value="admin" {{ (request('role', Auth::user()->role ?? 'mahasiswa')) == 'admin' ? 'selected' : '' }}>Panitia / Admin</option>
          </select>
        </div>

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

      <!-- 6-Stage Milestone Stepper (Siklus Tahapan PKL) -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Milestone Progres</span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs text-slate-500 font-medium">Batas Sidang: Juli 2026</span>
            </div>
            <h2 class="text-lg sm:text-xl font-bold font-heading text-slate-800 mt-0.5">
              Siklus Tahapan Praktik Kerja Lapangan 2026
            </h2>
          </div>
          <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-100 text-[#0056B3] rounded-xl text-xs font-semibold self-start sm:self-auto">
            <span class="material-symbols-outlined text-[16px] animate-spin">refresh</span>
            <span>Tahap 5 dari 6 Sedang Berjalan</span>
          </div>
        </div>

        <!-- 6 Step Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
          
          <!-- Step 1: Completed -->
          <div class="flex flex-col p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 hover:border-teal-200 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
              <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                <span class="material-symbols-outlined text-[16px]">check</span>
              </span>
              <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                Selesai
              </span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 font-heading">1. Pengajuan Mandiri</h3>
            <p class="text-xs text-slate-500 mt-1 leading-snug">Verifikasi berkas CV &amp; Proposal ke Koor PKL</p>
            <span class="text-[11px] text-slate-400 mt-3 font-mono">15 Jan 2026</span>
          </div>

          <!-- Step 2: Completed -->
          <div class="flex flex-col p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 hover:border-teal-200 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
              <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                <span class="material-symbols-outlined text-[16px]">check</span>
              </span>
              <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                Selesai
              </span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 font-heading">2. Surat Pengantar</h3>
            <p class="text-xs text-slate-500 mt-1 leading-snug">Penerbitan surat izin resmi jurusan ke mitra</p>
            <span class="text-[11px] text-slate-400 mt-3 font-mono">22 Jan 2026</span>
          </div>

          <!-- Step 3: Completed -->
          <div class="flex flex-col p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 hover:border-teal-200 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
              <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                <span class="material-symbols-outlined text-[16px]">check</span>
              </span>
              <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                Diterima
              </span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 font-heading">3. Konfirmasi Industri</h3>
            <p class="text-xs text-slate-500 mt-1 leading-snug">Approval resmi &amp; offering letter Telkomsel</p>
            <span class="text-[11px] text-slate-400 mt-3 font-mono">02 Feb 2026</span>
          </div>

          <!-- Step 4: Completed -->
          <div class="flex flex-col p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 hover:border-teal-200 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
              <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                <span class="material-symbols-outlined text-[16px]">check</span>
              </span>
              <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                Terverifikasi
              </span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 font-heading">4. Form F1 Dospem</h3>
            <p class="text-xs text-slate-500 mt-1 leading-snug">Penetapan Pembimbing &amp; Pengesahan KPS</p>
            <span class="text-[11px] text-slate-400 mt-3 font-mono">06 Feb 2026</span>
          </div>

          <!-- Step 5: Active Pulse -->
          <div class="flex flex-col p-4 rounded-xl bg-gradient-to-b from-blue-50/90 to-teal-50/50 border-2 border-[#0056B3] shadow-md transition-all relative">
            <div class="flex items-center justify-between mb-2.5">
              <span class="w-7 h-7 rounded-full bg-[#0056B3] text-white flex items-center justify-center text-xs font-bold shadow-sm">
                <span class="material-symbols-outlined text-[16px] animate-pulse">autorenew</span>
              </span>
              <span class="text-[10px] text-[#0056B3] font-bold uppercase tracking-wider bg-blue-100/70 px-2 py-0.5 rounded-full">
                Berjalan
              </span>
            </div>
            <h3 class="text-sm font-bold text-[#0056B3] font-heading">5. Logbook (F2-F4)</h3>
            <p class="text-xs text-slate-600 mt-1 leading-snug">Jurnal mingguan, absensi, &amp; bimbingan</p>
            <span class="text-[11px] text-[#0056B3] font-bold mt-3 font-mono">Minggu ke-4 / 20</span>
          </div>

          <!-- Step 6: Locked -->
          <div class="flex flex-col p-4 rounded-xl bg-slate-50/50 border border-slate-200/60 opacity-60">
            <div class="flex items-center justify-between mb-2.5">
              <span class="w-7 h-7 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-xs font-bold">
                <span class="material-symbols-outlined text-[16px]">lock</span>
              </span>
              <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded-full">
                Terkunci
              </span>
            </div>
            <h3 class="text-sm font-bold text-slate-500 font-heading">6. Evaluasi &amp; Bab VII</h3>
            <p class="text-xs text-slate-400 mt-1 leading-snug">Penilaian DUDI (F5), Sidang (F6/F7)</p>
            <span class="text-[11px] text-slate-400 mt-3 font-mono">Juli 2026</span>
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

              <!-- Barcode / Digital Legal Stamp -->
              <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm flex-shrink-0 self-stretch md:self-auto justify-between md:justify-start">
                <div class="flex flex-col text-left">
                  <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Legalitas Digital</span>
                  <span class="text-xs font-mono font-bold text-[#0056B3]">PNJ-TIK-2026-AUTH</span>
                  <span class="text-[10px] text-emerald-600 font-medium">QR Tervalidasi</span>
                </div>
                <!-- Simulated Digital Stamp -->
                <div class="w-10 h-10 bg-[#0056B3]/10 text-[#0056B3] p-1 rounded-lg flex items-center justify-center">
                  <span class="material-symbols-outlined text-[24px]">qr_code_2</span>
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
            <div class="flex items-start justify-between gap-3">
              <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Status Pengesahan Jurusan</span>
                <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Form F1: Kesediaan Pembimbing</h3>
                <p class="text-xs text-slate-500 mt-1">
                  Surat Pernyataan Kesediaan Bimbingan &amp; Persetujuan Kelayakan Tempat PKL
                </p>
              </div>
              <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#009688] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">assignment_turned_in</span>
              </div>
            </div>

            <!-- Dual Endorsement Breakdown -->
            <div class="space-y-3">
              
              <!-- Endorsement 1: Dospem -->
              <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/60 space-y-2.5">
                <div class="flex items-center justify-between">
                  <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">1. Dosen Pembimbing PKL</span>
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[11px] font-bold">
                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                    <span>Disetujui</span>
                  </span>
                </div>
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                    NA
                  </div>
                  <div class="flex flex-col min-w-0">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 font-heading truncate">Dr. Eng. Nur Aisyah, S.Kom., M.T.</span>
                    <span class="text-xs text-slate-500 font-mono">NIP: 198204122008122001</span>
                  </div>
                </div>
                <div class="p-2 bg-white rounded-lg border border-slate-200/80 flex items-center justify-between text-slate-400 text-[11px]">
                  <span class="font-mono flex items-center gap-1.5 text-slate-600">
                    <span class="material-symbols-outlined text-[#009688] text-[15px]">fingerprint</span>
                    <span>SHA256: 8a4f9...de19b</span>
                  </span>
                  <span class="font-mono text-slate-400 text-[10px]">04 Feb 2026 • 14:22 WIB</span>
                </div>
              </div>

              <!-- Endorsement 2: KPS TI -->
              <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/60 space-y-2.5">
                <div class="flex items-center justify-between">
                  <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">2. Koordinator Program Studi (KPS TI)</span>
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[11px] font-bold">
                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                    <span>Disahkan</span>
                  </span>
                </div>
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                    BW
                  </div>
                  <div class="flex flex-col min-w-0">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 font-heading truncate">Drs. Bambang Wahyudi, M.T.</span>
                    <span class="text-xs text-slate-500 font-mono">NIP: 196803151993031002</span>
                  </div>
                </div>
                <div class="p-2 bg-white rounded-lg border border-slate-200/80 flex items-center justify-between text-slate-400 text-[11px]">
                  <span class="font-mono flex items-center gap-1.5 text-slate-600">
                    <span class="material-symbols-outlined text-[#009688] text-[15px]">fingerprint</span>
                    <span>SHA256: 41c8e...ff02a</span>
                  </span>
                  <span class="font-mono text-slate-400 text-[10px]">06 Feb 2026 • 09:15 WIB</span>
                </div>
              </div>

            </div>

            <!-- Action Button -->
            <button type="button" onclick="handleDocumentAction(this, 'Membuka Salinan Dokumen...')" class="w-full bg-slate-50 hover:bg-slate-100 text-[#0056B3] border border-slate-200/80 font-bold font-heading text-xs sm:text-sm py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
              <span class="material-symbols-outlined text-[18px]">visibility</span>
              <span>Lihat Salinan Dokumen Form F1 (PDF)</span>
            </button>
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

            <!-- Notification helper -->
            <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl flex items-start gap-2.5">
              <span class="material-symbols-outlined text-amber-600 text-[18px] mt-0.5 flex-shrink-0">help_outline</span>
              <p class="text-xs text-amber-800 leading-relaxed">
                Minimal kehadiran bimbingan dospem adalah <strong>8 kali</strong> sebelum pendaftaran Sidang PKL (Form F6) dibuka pada bulan Juli.
              </p>
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
              <span class="text-xs text-slate-500 font-medium">Terhubung ke Dosen Pembimbing (Dr. Bambang Sutrisno, M.T.)</span>
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

      <!-- Quick Access to Workspace Logbook -->
      <div class="bg-gradient-to-r from-blue-50 via-teal-50/40 to-slate-50 border border-blue-100/80 rounded-2xl p-6 sm:p-7 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center shadow-md flex-shrink-0">
            <span class="material-symbols-outlined text-[26px]">folder_special</span>
          </div>
          <div>
            <h4 class="text-base sm:text-lg font-bold font-heading text-slate-800">Akses Cepat Modul Workspace Logbook</h4>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola lembar kerja mingguan (F2) dan absensi harian PKL Anda.</p>
          </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
          <a href="{{ route('logbook.mahasiswa') }}" class="w-full sm:w-auto bg-[#0056B3] hover:bg-[#00428A] text-white px-6 py-3 rounded-xl font-bold font-heading text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2">
            <span>Buka Workspace Logbook (F2)</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
          </a>
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
  <div id="modal-create-logbook" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
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
      document.getElementById('modal-create-logbook').classList.remove('hidden');
    }

    function closeCreateLogbookModal() {
      document.getElementById('modal-create-logbook').classList.add('hidden');
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