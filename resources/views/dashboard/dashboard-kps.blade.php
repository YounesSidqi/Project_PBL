<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard KPS & Penetapan Pembimbing PKL - SIM-PKL TIK Politeknik Negeri Jakarta</title>
  <meta name="description" content="Dashboard Koordinator Program Studi untuk Penetapan dan Plotting Mahasiswa bersama Dosen Pembimbing PKL Jurusan TIK PNJ">

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

  <!-- Sidebar Component -->
  @include('layouts.partials.sidebar', ['activePage' => 'dashboard_kps', 'role' => 'kps'])

  <!-- Main Wrapper -->
  <div class="lg:pl-72 flex-1 flex flex-col min-h-screen">
    
    <!-- Top Header Bar -->
    <header class="h-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8">
      
      <!-- Left: Mobile Menu Toggle & Title -->
      <div class="flex items-center gap-3 sm:gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Buka menu navigasi">
          <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>

        <div class="flex items-center gap-2 text-xs sm:text-sm">
          <span class="text-slate-500 hidden sm:inline">Portal KPS Jurusan TIK</span>
          <span class="material-symbols-outlined text-slate-300 text-[16px] hidden sm:inline">chevron_right</span>
          <span class="font-semibold text-slate-800 font-heading">Penetapan Pembimbing PKL</span>
        </div>

        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-bold">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
          <span>Otoritas KPS</span>
        </span>
      </div>

      <!-- Right: Role Switcher & Profile -->
      <div class="flex items-center gap-2 sm:gap-4">
        
        <!-- Quick Switcher for Demo / Pair Programming -->
        <div class="hidden md:flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-semibold">
          <a href="{{ route('dashboard.mahasiswa') }}" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-white transition-colors" title="Lihat Tampilan Mahasiswa">
            Mahasiswa
          </a>
          <a href="{{ route('dashboard.dosen') }}" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-white transition-colors" title="Lihat Tampilan Dosen">
            Dosen
          </a>
          <span class="px-2.5 py-1 rounded-lg bg-[#0056B3] text-white shadow-sm font-bold">
            KPS (Aktif)
          </span>
        </div>

        <!-- Divider -->
        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- User Profile Pill -->
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Dr. Eng. Nur Aisyah, S.Kom., M.T.' }}</span>
            <span class="text-[11px] text-slate-500 font-medium leading-tight">
              KPS Teknik Informatika
            </span>
          </div>
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-600 to-[#0056B3] text-white flex items-center justify-center font-bold text-xs shadow-sm">
            KP
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
      
      <!-- Flash Alert Feedback Messages -->
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

      <!-- Top Executive Metrics Bar -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Metric 1: Total Mahasiswa PKL -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Mahasiswa PKL</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-bold font-heading text-slate-800">{{ $totalStudents }}</span>
              <span class="text-xs text-slate-500 font-medium">Mahasiswa TIK</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Tahun Akademik 2025/2026</p>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">school</span>
          </div>
        </div>

        <!-- Metric 2: Sudah Ditetapkan (Terplot) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sudah Ditetapkan</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-bold font-heading text-emerald-700">{{ $assignedStudentsCount }}</span>
              <span class="text-xs font-bold text-emerald-600">({{ $totalStudents > 0 ? round(($assignedStudentsCount / $totalStudents) * 100) : 0 }}%)</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Resmi berpasangan dosen</p>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">verified</span>
          </div>
        </div>

        <!-- Metric 3: Menunggu Penetapan KPS (Pending F1) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between {{ $pendingCount > 0 ? 'ring-2 ring-amber-400/50 bg-amber-50/20' : '' }}">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu Penetapan KPS</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-bold font-heading text-amber-600">{{ $pendingCount }}</span>
              <span class="text-xs font-semibold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">Butuh Aksi KPS</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Pengajuan preferensi dosen</p>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">assignment_turned_in</span>
          </div>
        </div>

        <!-- Metric 4: Kapasitas Total Kuota Dosen -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-card flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kapasitas Kuota Dosen</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-bold font-heading text-[#0056B3]">{{ $usedCapacity }} / {{ $totalCapacity }}</span>
              <span class="text-xs text-slate-500">Slot Terisi</span>
            </div>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">{{ $remainingCapacity }} Slot Tersedia ({{ $totalDosen }} Dosen)</p>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-teal-50 text-[#02796B] flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">pie_chart</span>
          </div>
        </div>

      </div>

      <!-- Policy Info Notice: KPS Central Authority -->
      <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white rounded-2xl p-6 shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-0.5 bg-amber-400 text-slate-900 text-xs font-extrabold rounded-full font-heading">
                OTORITAS KPS TIK
              </span>
              <span class="text-xs text-blue-200">• Kebijakan Alur Baru</span>
            </div>
            <h2 class="text-lg sm:text-xl font-extrabold font-heading text-white">
              Penetapan Mahasiswa Bersama Dosen Pembimbing Dilakukan oleh KPS
            </h2>
            <p class="text-xs sm:text-sm text-blue-100/90 max-w-3xl leading-relaxed">
              Mahasiswa mengajukan preferensi dosen tanpa mengetahui beban isi kuota dosen (hanya dosen berstatus tersedia yang muncul). 
              Dosen tidak lagi memilih atau menolak mahasiswa secara sepihak, melainkan menerima bersih penetapan resmi dari KPS. 
              <strong>Gunakan tab-tab di bawah untuk mengelola penetapan, plotting cepat, dan pemantauan distribusi kuota dosen.</strong>
            </p>
          </div>
          <div class="flex items-center gap-2.5 flex-shrink-0">
            <button onclick="switchTab('plotting')" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-slate-900 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-sm">
              <span class="material-symbols-outlined text-[18px]">rule</span>
              <span>Proses Penetapan ({{ $pendingCount }})</span>
            </button>
            <button onclick="switchTab('langsung')" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all">
              <span class="material-symbols-outlined text-[18px]">bolt</span>
              <span>Plotting Cepat</span>
            </button>
          </div>
        </div>
      </div>

      <!-- KPS MAIN ACTION TABS NAVIGATION -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-2 shadow-sm flex items-center gap-2 overflow-x-auto">
        <button type="button" onclick="switchTab('plotting')" id="tab-btn-plotting" class="kps-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold font-heading flex items-center gap-2 transition-all bg-[#0056B3] text-white shadow-sm">
          <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
          <span>1. Penetapan &amp; Plotting Form F1</span>
          @if($pendingCount > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-400 text-slate-900 font-extrabold">{{ $pendingCount }}</span>
          @endif
        </button>

        <button type="button" onclick="switchTab('langsung')" id="tab-btn-langsung" class="kps-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-blue-50/50 transition-all">
          <span class="material-symbols-outlined text-[20px]">bolt</span>
          <span>2. Plotting Langsung Mahasiswa</span>
          @if($unassignedCount > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-bold">{{ $unassignedCount }} Belum Terplot</span>
          @endif
        </button>

        <button type="button" onclick="switchTab('kuota')" id="tab-btn-kuota" class="kps-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-blue-50/50 transition-all">
          <span class="material-symbols-outlined text-[20px]">pie_chart</span>
          <span>3. Distribusi Kuota Dosen</span>
          <span class="px-2 py-0.5 rounded-full text-[10px] bg-blue-50 text-[#0056B3] font-bold">{{ $totalDosen }} Dosen</span>
        </button>

        <button type="button" onclick="switchTab('rekap')" id="tab-btn-rekap" class="kps-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-blue-50/50 transition-all">
          <span class="material-symbols-outlined text-[20px]">badge</span>
          <span>4. Rekapitulasi Seluruh Mahasiswa</span>
        </button>
      </div>

      <!-- ============================================================== -->
      <!-- TAB CONTENT 1: PENETAPAN & PLOTTING PENGAJUAN FORM F1 (KPS)   -->
      <!-- ============================================================== -->
      <div id="tab-content-plotting" class="kps-tab-pane space-y-5">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Tugas Utama KPS</span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                  {{ $pendingCount }} Pengajuan Mahasiswa Menunggu Keputusan
                </span>
              </div>
              <h3 class="text-xl font-bold font-heading text-slate-800 mt-1">
                Daftar Permohonan Mahasiswa &amp; Penetapan Pasangan Pembimbing
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">
                KPS dapat menyetujui mahasiswa bersama dosen pilihannya, atau mengalihkan ke dosen lain jika kuota atau kesesuaian bidang memerlukan penyesuaian.
              </p>
            </div>
            
            <div class="flex items-center gap-2 text-xs">
              <span class="text-slate-500">Maksimal Bimbingan:</span>
              <strong class="text-slate-800 font-bold px-2 py-1 bg-slate-100 rounded-lg">7 Mahasiswa / Dosen</strong>
            </div>
          </div>

          <!-- Pending Applications Cards -->
          <div class="space-y-4">
            @forelse($pendingApplications as $app)
              @php
                $mInitials = collect(explode(' ', $app->student->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
                $targetDosen = $app->dosen;
                $targetCount = $targetDosen ? \App\Models\User::where('role', 'mahasiswa')->where('dosen_id', $targetDosen->id)->count() : 0;
                $isQuotaAvailable = $targetCount < 7;
              @endphp
              <div class="p-5 rounded-2xl bg-gradient-to-r from-amber-50/40 via-white to-slate-50 border border-amber-200/80 hover:border-amber-300 shadow-sm transition-all flex flex-col xl:flex-row xl:items-center justify-between gap-5">
                
                <!-- Left: Student Info & Company -->
                <div class="flex items-start gap-4 min-w-0">
                  <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                    {{ $mInitials }}
                  </div>
                  <div class="flex flex-col min-w-0 space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                      <h4 class="text-base font-bold font-heading text-slate-800">{{ $app->student->name }}</h4>
                      <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-xs font-mono font-semibold">NIM: {{ $app->student->identifier }}</span>
                      <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs font-medium">{{ $app->student->prodi ?? 'Teknik Informatika (D4)' }}</span>
                      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>Menunggu Penetapan KPS</span>
                      </span>
                    </div>

                    <div class="text-xs sm:text-sm text-slate-700 space-y-0.5 pt-0.5">
                      <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-rose-500">domain</span>
                        <strong class="text-slate-800">{{ $app->company_name ?? 'Instansi PKL' }}</strong>
                      </div>
                      <p class="text-xs text-slate-600">
                        <span class="font-semibold text-slate-700">Topik PKL:</span> {{ $app->internship_title ?? 'Praktik Kerja Lapangan' }}
                      </p>
                      <p class="text-[11px] text-slate-400">
                        Diajukan pada: <span class="font-mono text-slate-600">{{ $app->created_at ? $app->created_at->translatedFormat('d M Y, H:i') : 'Baru saja' }} WIB</span> • WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $app->student->phone ?? '') }}" target="_blank" class="text-emerald-600 font-semibold hover:underline">{{ $app->student->phone ?? '-' }}</a>
                      </p>
                    </div>

                    <!-- Lecturer Preference Display -->
                    <div class="pt-2 flex items-center gap-2 text-xs">
                      <span class="text-slate-500 font-semibold">Preferensi Dosen Mahasiswa:</span>
                      <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#0056B3] font-bold border border-blue-200/80 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px]">person</span>
                        <span>{{ $targetDosen->name ?? 'Belum memilih' }}</span>
                      </span>
                      <span class="text-[11px] px-2 py-0.5 rounded-full font-bold {{ $isQuotaAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        Kuota Dosen Saat Ini: {{ $targetCount }}/7 {{ $isQuotaAvailable ? '(Tersedia)' : '(Penuh)' }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Right: KPS Action Buttons (Tetapkan, Alihkan, Tolak) -->
                <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0 justify-end pt-3 xl:pt-0 border-t xl:border-t-0 border-slate-100">
                  
                  <!-- Action 1: Setujui ke Dosen Pilihan -->
                  @if($isQuotaAvailable)
                    <form action="{{ route('kps.f1.approve', $app->id) }}" method="POST" onsubmit="return confirm('Tetapkan mahasiswa {{ addslashes($app->student->name) }} bersama dosen pembimbing {{ addslashes($targetDosen->name) }}?')">
                      @csrf
                      <input type="hidden" name="dosen_id" value="{{ $targetDosen->id }}">
                      <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">gavel</span>
                        <span>Tetapkan ke {{ explode(' ', $targetDosen->name)[0] }}</span>
                      </button>
                    </form>
                  @else
                    <span class="text-xs text-rose-600 font-bold px-3 py-2 bg-rose-50 rounded-xl border border-rose-200">
                      Kuota Dosen Pilihan Penuh!
                    </span>
                  @endif

                  <!-- Action 2: Alihkan ke Dosen Lain Modal Trigger -->
                  <button type="button" 
                          onclick="openReassignModal('{{ $app->id }}', '{{ addslashes($app->student->name) }}', '{{ $app->student->identifier }}', '{{ $targetDosen->id ?? '' }}', '{{ addslashes($targetDosen->name ?? '') }}')"
                          class="bg-[#02796B] hover:bg-[#006054] text-white px-3.5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                    <span>Alihkan ke Dosen Lain</span>
                  </button>

                  <!-- Action 3: Tolak Pengajuan -->
                  <button type="button" 
                          onclick="openRejectModal('{{ $app->id }}', '{{ addslashes($app->student->name) }}')"
                          class="p-2.5 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl transition-colors" title="Tolak Pengajuan">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                  </button>
                </div>

              </div>
            @empty
              <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                <span class="material-symbols-outlined text-4xl text-emerald-600">task_alt</span>
                <h4 class="text-slate-700 font-bold mt-2">Semua Pengajuan Form F1 Telah Diproses!</h4>
                <p class="text-xs text-slate-500 mt-1">Saat ada mahasiswa baru yang mengajukan preferensi dosen pembimbing, permohonan mereka akan muncul di sini untuk Anda tetapkan.</p>
              </div>
            @endforelse
          </div>

          <!-- Recently Approved by KPS -->
          @if(count($approvedApplications ?? []) > 0)
            <div class="pt-6 border-t border-slate-100 space-y-3">
              <div class="flex items-center justify-between">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                  Riwayat Mahasiswa yang Telah Resmi Ditetapkan KPS ({{ count($approvedApplications) }})
                </h4>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                  <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                      <th class="py-2.5 px-3">Mahasiswa</th>
                      <th class="py-2.5 px-3">NIM</th>
                      <th class="py-2.5 px-3">Instansi PKL</th>
                      <th class="py-2.5 px-3">Dosen Pembimbing Ditetapkan</th>
                      <th class="py-2.5 px-3">Tanggal Penetapan</th>
                      <th class="py-2.5 px-3 text-right">Status SK</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    @foreach($approvedApplications as $app)
                      <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-2.5 px-3 font-bold text-slate-800">{{ $app->student->name }}</td>
                        <td class="py-2.5 px-3 font-mono text-slate-600">{{ $app->student->identifier }}</td>
                        <td class="py-2.5 px-3 text-slate-600">{{ $app->company_name ?? '-' }}</td>
                        <td class="py-2.5 px-3 font-semibold text-[#0056B3]">{{ $app->dosen->name ?? '-' }}</td>
                        <td class="py-2.5 px-3 text-slate-500 font-mono">{{ $app->approved_at ? $app->approved_at->translatedFormat('d M Y, H:i') : '-' }} WIB</td>
                        <td class="py-2.5 px-3 text-right">
                          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Resmi Ditetapkan</span>
                          </span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif

        </div>

      </div>

      <!-- ============================================================== -->
      <!-- TAB CONTENT 2: PLOTTING LANGSUNG MAHASISWA OLEH KPS             -->
      <!-- ============================================================== -->
      <div id="tab-content-langsung" class="kps-tab-pane hidden space-y-5">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Penempatan Mandiri KPS</span>
              <h3 class="text-xl font-bold font-heading text-slate-800 mt-1">
                Plotting Langsung Mahasiswa yang Belum Memiliki Pembimbing
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">
                KPS dapat langsung menetapkan mahasiswa ke dosen tertentu tanpa perlu menunggu mahasiswa mengajukan permohonan terlebih dahulu.
              </p>
            </div>
            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-[#0056B3] font-bold text-xs border border-blue-200">
              {{ count($unassignedStudents) }} Mahasiswa Belum Terplot
            </span>
          </div>

          <!-- Plotting Form -->
          <form action="{{ route('kps.student.plot') }}" method="POST" class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            @csrf
            <h4 class="text-sm font-bold font-heading text-slate-800 flex items-center gap-2">
              <span class="material-symbols-outlined text-[#0056B3] text-[20px]">person_add</span>
              <span>Formulir Penetapan Mahasiswa ke Dosen Pembimbing</span>
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Select Student -->
              <div>
                <label for="plot_student_id" class="block text-xs font-bold text-slate-700 mb-1">
                  Pilih Mahasiswa PKL (Belum Terplot) <span class="text-rose-500">*</span>
                </label>
                <select name="student_id" id="plot_student_id" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
                  <option value="" disabled selected>-- Pilih Mahasiswa --</option>
                  @foreach($unassignedStudents as $st)
                    <option value="{{ $st->id }}">
                      {{ $st->name }} (NIM: {{ $st->identifier }}) • {{ $st->prodi ?? 'TIK' }}
                    </option>
                  @endforeach
                </select>
                @if(count($unassignedStudents) === 0)
                  <p class="text-[11px] text-emerald-600 font-semibold mt-1">Seluruh mahasiswa saat ini sudah memiliki dosen pembimbing.</p>
                @endif
              </div>

              <!-- Select Lecturer -->
              <div>
                <label for="plot_dosen_id" class="block text-xs font-bold text-slate-700 mb-1">
                  Pilih Dosen Pembimbing yang Ditugaskan <span class="text-rose-500">*</span>
                </label>
                <select name="dosen_id" id="plot_dosen_id" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
                  <option value="" disabled selected>-- Pilih Dosen Pembimbing --</option>
                  @foreach($availableDosen as $d)
                    <option value="{{ $d->id }}">
                      {{ $d->name }} (Sisa Slot: {{ 7 - ($d->students_count ?? 0) }}/7) • {{ $d->prodi ?? 'TIK' }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Company Name -->
              <div>
                <label for="plot_company_name" class="block text-xs font-bold text-slate-700 mb-1">
                  Nama Instansi / Perusahaan PKL (Opsional)
                </label>
                <input type="text" name="company_name" id="plot_company_name" placeholder="cth. PT Telkom Indonesia Tbk" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
              </div>

              <!-- Internship Title -->
              <div>
                <label for="plot_internship_title" class="block text-xs font-bold text-slate-700 mb-1">
                  Topik / Rencana Magang (Opsional)
                </label>
                <input type="text" name="internship_title" id="plot_internship_title" placeholder="cth. Network Operation Center & Cloud Infra" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
              </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
              <span class="text-[11px] text-slate-500">Penetapan akan langsung berlaku secara resmi di dashboard mahasiswa dan dosen terkait.</span>
              <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span>Tetapkan &amp; Plotkan Mahasiswa</span>
              </button>
            </div>
          </form>

          <!-- List of Unassigned Students -->
          <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">
              Daftar Mahasiswa yang Masih Belum Ditetapkan Dosen Pembimbing ({{ count($unassignedStudents) }})
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              @forelse($unassignedStudents as $st)
                <div class="p-4 rounded-xl bg-white border border-slate-200 hover:border-blue-300 transition-all flex items-center justify-between gap-3 shadow-sm">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                      {{ strtoupper(substr($st->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                      <span class="text-xs font-bold text-slate-800 font-heading truncate">{{ $st->name }}</span>
                      <span class="text-[11px] text-slate-500 font-mono">NIM: {{ $st->identifier }} • {{ $st->prodi ?? 'D4 TI' }}</span>
                      @if($st->latestF1Application && $st->latestF1Application->status === 'pending')
                        <span class="text-[10px] text-amber-600 font-bold mt-0.5">Sudah mengajukan Form F1 (Menunggu Penetapan Tab 1)</span>
                      @else
                        <span class="text-[10px] text-slate-400 mt-0.5">Belum mengajukan preferensi</span>
                      @endif
                    </div>
                  </div>
                  <button type="button" onclick="selectStudentForPlotting('{{ $st->id }}')" class="px-3 py-1.5 rounded-lg bg-blue-50 text-[#0056B3] hover:bg-[#0056B3] hover:text-white transition-all text-xs font-bold flex items-center gap-1 flex-shrink-0">
                    <span>Pilih</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                  </button>
                </div>
              @empty
                <div class="col-span-2 p-6 text-center bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-500">
                  Semua mahasiswa telah berhasil ditetapkan ke dosen pembimbing masing-masing.
                </div>
              @endforelse
            </div>
          </div>

        </div>

      </div>

      <!-- ============================================================== -->
      <!-- TAB CONTENT 3: DISTRIBUSI KUOTA DOSEN PEMBIMBING                -->
      <!-- ============================================================== -->
      <div id="tab-content-kuota" class="kps-tab-pane hidden space-y-5">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Monitoring Kapasitas Pembimbingan</span>
              <h3 class="text-xl font-bold font-heading text-slate-800 mt-1">
                Distribusi &amp; Alokasi Kuota Dosen Pembimbing PKL Jurusan TIK
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">
                Batas maksimal kuota per dosen adalah <strong>7 mahasiswa</strong>. KPS dapat memindahkan mahasiswa antar dosen untuk meratakan beban asistensi.
              </p>
            </div>
            
            <div class="flex items-center gap-2">
              <span class="text-xs px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 font-bold">
                Total Dosen: {{ $totalDosen }}
              </span>
            </div>
          </div>

          <!-- Lecturers Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @foreach($allDosen as $d)
              @php
                $assignedCount = $d->students_count ?? 0;
                $percent = round(($assignedCount / 7) * 100);
                $isFull = $assignedCount >= 7;
              @endphp
              <div class="p-5 rounded-2xl bg-white border {{ $isFull ? 'border-rose-200 bg-rose-50/20' : 'border-slate-200/90' }} shadow-sm space-y-4 hover:shadow-md transition-shadow">
                
                <!-- Dosen Header -->
                <div class="flex items-start justify-between gap-3">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="w-11 h-11 rounded-2xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                      {{ strtoupper(substr($d->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                      <h4 class="text-sm font-bold font-heading text-slate-800 truncate">{{ $d->name }}</h4>
                      <span class="text-xs text-slate-500 font-mono">NIP: {{ $d->identifier ?? '-' }}</span>
                      <span class="text-[11px] text-slate-400">{{ $d->prodi ?? 'Teknik Informatika' }}</span>
                    </div>
                  </div>
                  
                  <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $isFull ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                    {{ $assignedCount }}/7 Mahasiswa
                  </span>
                </div>

                <!-- Progress Bar -->
                <div class="space-y-1.5">
                  <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Kapasitas Kuota</span>
                    <span class="font-bold {{ $isFull ? 'text-rose-600' : 'text-[#0056B3]' }}">{{ $percent }}% Terisi (Sisa {{ 7 - $assignedCount }} Slot)</span>
                  </div>
                  <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300 {{ $isFull ? 'bg-rose-500' : ($assignedCount >= 5 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $percent) }}%"></div>
                  </div>
                </div>

                <!-- Mentored Students List Under This Dosen -->
                <div class="pt-3 border-t border-slate-100 space-y-2">
                  <div class="flex items-center justify-between text-xs font-bold text-slate-600">
                    <span>Mahasiswa Bimbingan Aktif ({{ $assignedCount }}):</span>
                  </div>

                  @if(count($d->students ?? []) > 0)
                    <div class="space-y-2">
                      @foreach($d->students as $st)
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                          <div class="min-w-0">
                            <span class="font-bold text-slate-800 block truncate">{{ $st->name }} (NIM: {{ $st->identifier }})</span>
                            <span class="text-[11px] text-slate-500 truncate block">{{ $st->latestF1Application->company_name ?? 'Instansi PKL' }}</span>
                          </div>
                          
                          <!-- Reassign & Unassign Action -->
                          <div class="flex items-center gap-1.5 flex-shrink-0">
                            <button type="button" 
                                    onclick="openMoveLecturerModal('{{ $st->id }}', '{{ addslashes($st->name) }}', '{{ $d->id }}', '{{ addslashes($d->name) }}')"
                                    class="p-1.5 bg-white text-blue-600 hover:bg-blue-50 border border-slate-200 rounded-lg transition-colors" title="Pindahkan ke Dosen Lain">
                              <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
                            </button>

                            <form action="{{ route('kps.student.unassign', $st->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepaskan mahasiswa {{ addslashes($st->name) }} dari bimbingan {{ addslashes($d->name) }}?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="p-1.5 bg-white text-rose-600 hover:bg-rose-50 border border-slate-200 rounded-lg transition-colors" title="Batalkan Penempatan">
                                <span class="material-symbols-outlined text-[16px]">person_remove</span>
                              </button>
                            </form>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <p class="text-xs text-slate-400 italic py-2 text-center bg-slate-50 rounded-xl">
                      Belum ada mahasiswa yang ditetapkan pada dosen ini.
                    </p>
                  @endif
                </div>

              </div>
            @endforeach
          </div>

        </div>

      </div>

      <!-- ============================================================== -->
      <!-- TAB CONTENT 4: REKAPITULASI MASTER SELURUH MAHASISWA            -->
      <!-- ============================================================== -->
      <div id="tab-content-rekap" class="kps-tab-pane hidden space-y-5">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Master Data PKL</span>
              <h3 class="text-xl font-bold font-heading text-slate-800 mt-1">
                Rekapitulasi Status Penempatan Seluruh Mahasiswa
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">
                Pantau seluruh mahasiswa yang sudah resmi berpasangan dengan dosen, sedang menunggu penetapan, atau belum mengajukan.
              </p>
            </div>

            <!-- Search Filter -->
            <div class="relative w-full sm:w-64">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
              <input type="text" id="master-student-search" onkeyup="filterMasterTable()" placeholder="Cari nama atau NIM..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
            </div>
          </div>

          <!-- Table Master -->
          <div class="overflow-x-auto">
            <table class="w-full text-xs text-left" id="master-students-table">
              <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                <tr>
                  <th class="py-3 px-3">Mahasiswa</th>
                  <th class="py-3 px-3">NIM</th>
                  <th class="py-3 px-3">Program Studi</th>
                  <th class="py-3 px-3">Instansi PKL</th>
                  <th class="py-3 px-3">Status Penempatan</th>
                  <th class="py-3 px-3">Dosen Pembimbing</th>
                  <th class="py-3 px-3 text-right">Aksi KPS</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @foreach($allStudents as $st)
                  @php
                    $hasDosen = !empty($st->dosen_id);
                    $f1 = $st->latestF1Application;
                    $isPending = ($f1 && $f1->status === 'pending');
                  @endphp
                  <tr class="hover:bg-slate-50/80 transition-colors master-row" data-search="{{ strtolower($st->name . ' ' . $st->identifier . ' ' . ($st->dosen->name ?? '')) }}">
                    <td class="py-3 px-3 font-bold text-slate-800">{{ $st->name }}</td>
                    <td class="py-3 px-3 font-mono text-slate-600">{{ $st->identifier }}</td>
                    <td class="py-3 px-3 text-slate-600">{{ $st->prodi ?? 'Teknik Informatika (D4)' }}</td>
                    <td class="py-3 px-3 text-slate-600">{{ $f1?->company_name ?? '-' }}</td>
                    <td class="py-3 px-3">
                      @if($hasDosen)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                          <span>Sudah Ditetapkan</span>
                        </span>
                      @elseif($isPending)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">
                          <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                          <span>Menunggu Penetapan KPS</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium text-[10px]">
                          <span>Belum Mengajukan</span>
                        </span>
                      @endif
                    </td>
                    <td class="py-3 px-3 font-semibold text-slate-800">
                      {{ $st->dosen->name ?? ($f1?->dosen?->name ? $f1->dosen->name . ' (Preferensi)' : 'Belum Ditentukan') }}
                    </td>
                    <td class="py-3 px-3 text-right">
                      @if($hasDosen)
                        <button type="button" onclick="openMoveLecturerModal('{{ $st->id }}', '{{ addslashes($st->name) }}', '{{ $st->dosen_id }}', '{{ addslashes($st->dosen->name ?? '') }}')" class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#0056B3] hover:bg-[#0056B3] hover:text-white transition-colors text-[11px] font-bold">
                          Ubah Dosen
                        </button>
                      @elseif($isPending)
                        <button type="button" onclick="switchTab('plotting')" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 transition-colors text-[11px] font-bold">
                          Proses F1
                        </button>
                      @else
                        <button type="button" onclick="selectStudentForPlotting('{{ $st->id }}')" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors text-[11px] font-bold">
                          Plot Sekarang
                        </button>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>

      </div>

    </main>

  </div>

  <!-- ============================================================== -->
  <!-- MODAL: KPS Alihkan Dosen Pembimbing untuk Pengajuan F1         -->
  <!-- ============================================================== -->
  <div id="modal-reassign-f1" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Otoritas KPS</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Alihkan &amp; Tetapkan ke Dosen Lain</h3>
          <p class="text-xs text-slate-500 mt-0.5" id="reassign-modal-subtitle">Pilih dosen pembimbing baru yang tersedia untuk mahasiswa.</p>
        </div>
        <button onclick="closeModal('modal-reassign-f1')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form id="form-reassign-f1" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Dosen Pembimbing Baru (Tersedia):</label>
          <select name="dosen_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
            <option value="" disabled selected>-- Pilih Dosen Pembimbing --</option>
            @foreach($availableDosen as $d)
              <option value="{{ $d->id }}">
                {{ $d->name }} (Sisa Slot: {{ 7 - ($d->students_count ?? 0) }}/7)
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Penetapan KPS (Opsional):</label>
          <textarea name="notes" rows="2" placeholder="cth. Dialihkan karena kuota dosen sebelumnya penuh / disesuaikan dengan topik PKL." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20"></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeModal('modal-reassign-f1')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Batal
          </button>
          <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">check</span>
            <span>Konfirmasi Penetapan</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL: KPS Pindahkan Mahasiswa yang Sudah Terplot (Reassign)    -->
  <!-- ============================================================== -->
  <div id="modal-move-lecturer" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Perubahan Pembimbing PKL</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Pindahkan Dosen Pembimbing Mahasiswa</h3>
          <p class="text-xs text-slate-500 mt-0.5" id="move-modal-subtitle">Mahasiswa akan dipindahkan ke dosen baru.</p>
        </div>
        <button onclick="closeModal('modal-move-lecturer')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form id="form-move-lecturer" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Dosen Pengganti yang Masih Memiliki Kuota:</label>
          <select name="new_dosen_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
            <option value="" disabled selected>-- Pilih Dosen Pengganti --</option>
            @foreach($availableDosen as $d)
              <option value="{{ $d->id }}">
                {{ $d->name }} (Sisa Slot: {{ 7 - ($d->students_count ?? 0) }}/7)
              </option>
            @endforeach
          </select>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeModal('modal-move-lecturer')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Batal
          </button>
          <button type="submit" class="bg-[#02796B] hover:bg-[#006054] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
            <span>Pindahkan Mahasiswa</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL: KPS Tolak Pengajuan Form F1 Mahasiswa                  -->
  <!-- ============================================================== -->
  <div id="modal-reject-f1" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Penolakan Pengajuan</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Tolak Pengajuan Form F1 Mahasiswa</h3>
          <p class="text-xs text-slate-500 mt-0.5" id="reject-modal-subtitle">Mahasiswa akan diminta mengajukan preferensi dosen kembali.</p>
        </div>
        <button onclick="closeModal('modal-reject-f1')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form id="form-reject-f1" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan / Catatan KPS: <span class="text-rose-500">*</span></label>
          <textarea name="notes" rows="3" required placeholder="Contoh: Kuota dosen telah terpenuhi secara merata / Topik magang memerlukan pembimbing bidang spesifik." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeModal('modal-reject-f1')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Batal
          </button>
          <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">close</span>
            <span>Tolak Pengajuan</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Interactive Client-side Script -->
  <script>
    // Tab Switching Logic
    function switchTab(tabId) {
      document.querySelectorAll('.kps-tab-pane').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.kps-tab-btn').forEach(btn => {
        btn.className = "kps-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold font-heading flex items-center gap-2 text-slate-600 hover:text-[#0056B3] hover:bg-blue-50/50 transition-all";
      });

      const activePane = document.getElementById('tab-content-' + tabId);
      const activeBtn = document.getElementById('tab-btn-' + tabId);

      if (activePane) activePane.classList.remove('hidden');
      if (activeBtn) {
        activeBtn.className = "kps-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold font-heading flex items-center gap-2 transition-all bg-[#0056B3] text-white shadow-sm";
      }

      // Update URL query without page reload
      const url = new URL(window.location);
      url.searchParams.set('tab', tabId);
      window.history.replaceState({}, '', url);
    }

    // Modal Helpers
    function openModal(modalId) {
      const m = document.getElementById(modalId);
      if (m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
      }
    }

    function closeModal(modalId) {
      const m = document.getElementById(modalId);
      if (m) {
        m.classList.add('hidden');
        m.classList.remove('flex');
      }
    }

    // Open Reassign Modal for F1 Application
    function openReassignModal(appId, studentName, studentNim, currentDosenId, currentDosenName) {
      const form = document.getElementById('form-reassign-f1');
      form.action = `/kps/f1/${appId}/approve`;
      document.getElementById('reassign-modal-subtitle').innerText = `Mengalihkan mahasiswa ${studentName} (NIM: ${studentNim}). Preferensi sebelumnya: ${currentDosenName}.`;
      openModal('modal-reassign-f1');
    }

    // Open Move Lecturer Modal for Already Mentored Student
    function openMoveLecturerModal(studentId, studentName, currentDosenId, currentDosenName) {
      const form = document.getElementById('form-move-lecturer');
      form.action = `/kps/students/${studentId}/reassign`;
      document.getElementById('move-modal-subtitle').innerText = `Memindahkan ${studentName} dari bimbingan saat ini: ${currentDosenName}.`;
      openModal('modal-move-lecturer');
    }

    // Open Reject Modal
    function openRejectModal(appId, studentName) {
      const form = document.getElementById('form-reject-f1');
      form.action = `/kps/f1/${appId}/reject`;
      document.getElementById('reject-modal-subtitle').innerText = `Menolak pengajuan Form F1 untuk mahasiswa ${studentName}.`;
      openModal('modal-reject-f1');
    }

    // Select Student in Tab 2 Quick Plotting
    function selectStudentForPlotting(studentId) {
      switchTab('langsung');
      const select = document.getElementById('plot_student_id');
      if (select) {
        select.value = studentId;
        select.focus();
      }
    }

    // Filter Master Table
    function filterMasterTable() {
      const query = (document.getElementById('master-student-search')?.value || '').toLowerCase();
      document.querySelectorAll('.master-row').forEach(row => {
        const searchVal = row.getAttribute('data-search') || '';
        row.style.display = searchVal.includes(query) ? '' : 'none';
      });
    }

    // Toggle Mobile Sidebar
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

    // Check initial tab from URL parameter
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      if (tab && ['plotting', 'langsung', 'kuota', 'rekap'].includes(tab)) {
        switchTab(tab);
      }
    });
  </script>

</body>
</html>
