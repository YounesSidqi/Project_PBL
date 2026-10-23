<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Dosen Pembimbing - SIM-PKL TIK Politeknik Negeri Jakarta</title>
  <meta name="description" content="Portal Dosen Pembimbing Praktik Kerja Lapangan Jurusan TIK Politeknik Negeri Jakarta">

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
  @php
    $isRealDosen = (Auth::user()->role ?? '') === 'dosen';
    $lecturerName = $isRealDosen ? Auth::user()->name : 'Dr. Bambang Sutrisno, M.T.';
    $lecturerNip = $isRealDosen ? (Auth::user()->identifier ?? '198503152010121002') : '198503152010121002';
    $lecturerInitials = strtoupper(substr($lecturerName, 0, 2));
    if (str_starts_with($lecturerName, 'Dr.')) {
      $lecturerInitials = 'BS';
    }
  @endphp

  <!-- Mobile Sidebar Backdrop -->
  <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

  <!-- Sidebar Navigation Component -->
  @include('layouts.partials.sidebar', ['activePage' => 'dashboard', 'role' => 'dosen'])

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
          <span class="text-slate-500 hidden sm:inline">Portal Dosen TIK</span>
          <span class="material-symbols-outlined text-slate-300 text-[16px] hidden sm:inline">chevron_right</span>
          <span class="font-semibold text-slate-800 font-heading">Monitoring Mahasiswa Bimbingan</span>
        </div>

        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-teal-50 text-teal-800 border border-teal-200/70 rounded-full text-xs font-semibold">
          <span class="w-1.5 h-1.5 rounded-full bg-[#009688] animate-pulse"></span>
          <span>Semester Genap 2025/2026</span>
        </span>
      </div>

      <!-- Right: Role Switcher, Notifications, & Profile -->
      <div class="flex items-center gap-2 sm:gap-4">

        <!-- Notification Bell -->
        <div class="relative">
          <button type="button" aria-label="Notifikasi" class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined text-[22px]">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-amber-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center ring-2 ring-white">3</span>
          </button>
        </div>

        <!-- Divider -->
        <div class="h-6 w-px bg-slate-200"></div>

        <!-- Lecturer Profile Pill -->
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-slate-800 leading-tight">{{ $lecturerName }}</span>
            <span class="text-[11px] text-slate-500 font-mono leading-tight">NIP: {{ $lecturerNip }}</span>
          </div>
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm">
            {{ $lecturerInitials }}
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
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
              if (typeof showToast === 'function') {
                showToast(@json(session('error') ?? $errors->first()), 'error');
              }
            }, 200);
          });
        </script>
      @endif

      @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm animate-fade-in">
          <span class="material-symbols-outlined text-emerald-600 text-[22px]">check_circle</span>
          <span>{{ session('success') }}</span>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
              if (typeof showToast === 'function') {
                showToast(@json(session('success')), 'success');
              }
            }, 200);
          });
        </script>
      @endif
      <!-- Top Hero Lecturer Card & Mentorship Quota Capacity Banner -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0056B3] via-[#004799] to-[#02796B] text-white p-6 sm:p-8 shadow-card">
        <!-- Ambient decorative shapes -->
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute right-48 -bottom-20 w-72 h-72 rounded-full bg-[#009688]/20 blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
          
          <!-- Lecturer Identity -->
          <div class="flex items-start sm:items-center gap-4 sm:gap-5">
            <div class="relative flex-shrink-0">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner">
                <span class="material-symbols-outlined text-[38px] sm:text-[44px]">school</span>
              </div>
              <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#009688] rounded-full ring-2 ring-white flex items-center justify-center" title="Dosen Aktif">
                <span class="material-symbols-outlined text-white text-[12px] font-bold">check</span>
              </span>
            </div>

            <div class="flex flex-col">
              <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-extrabold font-heading tracking-tight text-white">
                  {{ $lecturerName }}
                </h1>
                <span class="px-2.5 py-0.5 rounded-md bg-white/15 backdrop-blur-sm text-blue-50 text-xs font-mono font-medium tracking-wide">
                  NIP: {{ $lecturerNip }}
                </span>
                <span class="px-2.5 py-0.5 rounded-md bg-[#009688]/30 border border-[#009688]/40 text-teal-100 text-xs font-semibold">
                  Dosen Pembimbing Utama
                </span>
              </div>

              <div class="text-blue-100/90 text-xs sm:text-sm mt-2 flex items-center gap-2 sm:gap-3 flex-wrap">
                <span class="font-medium">Program Studi: Teknik Informatika</span>
                <span class="w-1.5 h-1.5 rounded-full bg-blue-200/40"></span>
                <span>Jurusan Teknik Informatika &amp; Komputer PNJ</span>
                <span class="w-1.5 h-1.5 rounded-full bg-blue-200/40"></span>
                <span class="text-teal-200 font-semibold">Tahun Akademik 2025/2026 Genap</span>
              </div>
            </div>
          </div>

          <!-- Highlight: 7 Mahasiswa Capacity Quota Meter -->
          @php
            $studentCount = isset($students) ? $students->count() : 1;
            $remainingCapacity = max(0, 7 - $studentCount);
            $percentFilled = round(($studentCount / 7) * 100);
            $pendingLogbooksCount = isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0;
          @endphp
          <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 sm:p-6 flex flex-col justify-between w-full lg:w-96 shadow-inner">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#009688] text-[20px]">pie_chart</span>
                <span class="text-xs uppercase tracking-wider text-blue-100 font-bold">Kapasitas Kuota Bimbingan</span>
              </div>
              <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 text-[11px] font-bold">
                {{ $remainingCapacity }} Kuota Tersedia
              </span>
            </div>

            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-3xl font-extrabold font-heading text-white">{{ $studentCount }}</span>
              <span class="text-lg text-blue-200 font-medium font-heading">/ 7 Mahasiswa</span>
              <span class="ml-auto text-xs text-blue-100 font-mono">({{ $percentFilled }}% Terisi)</span>
            </div>

            <!-- Segmented 7-Slots Bar Visualizer -->
            <div class="grid grid-cols-7 gap-1.5 mt-3 mb-2">
              @for($i = 1; $i <= 7; $i++)
                @if($i <= $studentCount)
                  <div class="h-2.5 rounded-full bg-emerald-400 shadow-sm" title="Mahasiswa {{ $i }}: {{ isset($students[$i-1]) ? $students[$i-1]->name : 'Aktif' }}"></div>
                @else
                  <div class="h-2.5 rounded-full bg-white/30 border border-dashed border-white/50" title="Slot Kuota {{ $i }}: Tersedia"></div>
                @endif
              @endfor
            </div>

            <p class="text-[11px] text-blue-100/80 leading-snug">
              Batas maksimal <strong class="text-white">7 mahasiswa</strong> bimbingan per dosen sesuai Standar PKL Jurusan TIK PNJ.
            </p>
          </div>

        </div>
      </div>

      <!-- Quick Executive Metric Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Metric 1: Total Bimbingan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-card hover:border-blue-200 transition-all flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Bimbingan</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-extrabold text-[#0056B3] font-heading">{{ $studentCount }}</span>
              <span class="text-xs font-bold text-slate-400">/ 7 Maksimal</span>
            </div>
            <span class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">check_circle</span>
              <span>{{ $remainingCapacity }} Kuota Slot Kosong</span>
            </span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">groups</span>
          </div>
        </div>

        <!-- Metric 2: Logbook Perlu Review -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-card hover:border-amber-200 transition-all flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Logbook Menunggu</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-extrabold text-amber-600 font-heading">
                {{ $pendingLogbooksCount }}
              </span>
              <span class="text-xs text-slate-400 font-medium">Laporan F3</span>
            </div>
            <span class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">pending</span>
              <span>{{ $pendingLogbooksCount > 0 ? 'Perlu Validasi Pekan Ini' : 'Semua Sudah Diverifikasi' }}</span>
            </span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">rate_review</span>
          </div>
        </div>

        <!-- Metric 3: Validasi Form F1 -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-card hover:border-teal-200 transition-all flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengajuan Form F1</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-extrabold text-[#02796B] font-heading">0</span>
              <span class="text-xs text-slate-400 font-medium">Kesediaan Baru</span>
            </div>
            <span class="text-[11px] text-[#02796B] font-semibold mt-1 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">check_circle</span>
              <span>Form F1 Terverifikasi</span>
            </span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">assignment_turned_in</span>
          </div>
        </div>

        <!-- Metric 4: Rata-rata Kehadiran -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-card hover:border-emerald-200 transition-all flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rata-rata Kehadiran</span>
            <div class="flex items-baseline gap-2 mt-1">
              <span class="text-2xl font-extrabold text-emerald-600 font-heading">96.8%</span>
            </div>
            <span class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">trending_up</span>
              <span>Disiplin di Perusahaan DUDI</span>
            </span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-[26px]">verified</span>
          </div>
        </div>

      </div>

      <!-- SECTION: Validasi Form F1 & Mahasiswa yang Memilih Dosen Ini -->
      <div id="f1-section" class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Validasi Form F1 Jurusan TIK</span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ count($incomingF1 ?? []) > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                {{ count($incomingF1 ?? []) }} Pengajuan Menunggu
              </span>
            </div>
            <h2 class="text-xl font-bold font-heading text-slate-800 mt-0.5">
              Mahasiswa yang Memilih Anda sebagai Pembimbing PKL
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
              Sebelum menyetujui, Dosen Pembimbing wajib mengunggah dokumen <strong>Surat Pernyataan Kesediaan (PDF)</strong> untuk mengesahkan mahasiswa bimbingan.
            </p>
          </div>
        </div>

        <!-- Incoming Requests List -->
        <div class="grid grid-cols-1 gap-4">
          @forelse($incomingF1 ?? [] as $app)
            @php
              $mhsInitials = collect(explode(' ', $app->student->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
            @endphp
            <div class="p-5 rounded-2xl bg-gradient-to-r from-amber-50/40 via-white to-slate-50 border border-amber-200/80 hover:border-amber-300 shadow-sm transition-all flex flex-col lg:flex-row lg:items-center justify-between gap-5">
              <div class="flex items-start gap-4 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                  {{ $mhsInitials }}
                </div>
                <div class="flex flex-col min-w-0">
                  <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-base font-bold font-heading text-slate-800">{{ $app->student->name }}</h3>
                    <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-xs font-mono font-semibold">NIM: {{ $app->student->identifier }}</span>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs font-medium">{{ $app->student->prodi ?? 'Teknik Informatika (D4)' }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                      <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                      <span>Menunggu ACC &amp; Upload PDF</span>
                    </span>
                  </div>
                  <div class="mt-2 space-y-1 text-xs sm:text-sm text-slate-700">
                    <div class="flex items-center gap-2">
                      <span class="material-symbols-outlined text-[16px] text-rose-500">domain</span>
                      <strong>{{ $app->company_name ?? 'Perusahaan Magang' }}</strong>
                      <span class="text-slate-400">•</span>
                      <span class="text-slate-500">Topik: {{ $app->internship_title ?? 'Praktik Kerja Lapangan' }}</span>
                    </div>
                    <p class="text-xs text-slate-500">
                      Diajukan pada: <span class="font-mono">{{ $app->created_at ? $app->created_at->translatedFormat('d M Y, H:i') : 'Baru saja' }} WIB</span> <span class="text-slate-400">({{ $app->created_at ? $app->created_at->diffForHumans() : 'baru saja' }})</span> • WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $app->student->phone ?? '') }}" target="_blank" class="text-emerald-600 font-semibold hover:underline">{{ $app->student->phone ?? '-' }}</a>
                    </p>
                  </div>
                </div>
              </div>

              <!-- Action: Approve with PDF or Reject -->
              <div class="flex items-center gap-2.5 flex-shrink-0 justify-end pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                <button type="button" 
                        data-app-id="{{ $app->id }}"
                        data-student-name="{{ $app->student->name }}"
                        data-student-nim="{{ $app->student->identifier }}"
                        data-company="{{ $app->company_name }}"
                        data-title="{{ $app->internship_title }}"
                        onclick="openF1ModalFromBtn(this)" 
                        class="bg-[#02796B] hover:bg-[#006054] text-white px-4 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-2 transition-all shadow-sm">
                  <span class="material-symbols-outlined text-[18px]">upload_file</span>
                  <span>ACC &amp; Upload Surat PDF</span>
                </button>
                <form action="{{ route('f1.reject', $app->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan bimbingan dari {{ addslashes($app->student->name) }}?')">
                  @csrf
                  <button type="submit" class="p-2.5 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl transition-colors" title="Tolak Pengajuan">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                  </button>
                </form>
              </div>
            </div>
          @empty
            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
              <span class="material-symbols-outlined text-4xl text-slate-400">task_alt</span>
              <p class="text-slate-600 font-semibold mt-2">Tidak ada pengajuan Form F1 yang menunggu persetujuan.</p>
              <p class="text-xs text-slate-400 mt-1">Saat mahasiswa memilih Anda sebagai dosen pembimbing, pengajuan mereka akan tampil di sini.</p>
            </div>
          @endforelse
        </div>
      </div>

      <!-- Main Section: Mahasiswa Bimbingan Management -->
      <div id="mahasiswa-section" class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
        
        <!-- Header with Filter & Actions -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Daftar Bimbingan PKL</span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs text-slate-500 font-medium">Beban Kuota Dosen: {{ $studentCount }} / 7 Terisi</span>
            </div>
            <h2 class="text-xl font-bold font-heading text-slate-800 mt-0.5">
              Mahasiswa yang Anda Naungi &amp; Bimbing
            </h2>
          </div>

          <!-- Search & Filter Options -->
          <div class="flex items-center gap-3 flex-wrap">
            <div class="relative flex-1 sm:w-64">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
              <input type="text" id="student-search" onkeyup="filterStudents()" placeholder="Cari nama atau NIM..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
            </div>

            <select id="status-filter" onchange="filterStudents()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20">
              <option value="all">Semua Mahasiswa ({{ $studentCount }})</option>
              <option value="review">Perlu Review Logbook</option>
              <option value="uptodate">Logbook Aman</option>
            </select>

            <button type="button" onclick="openNewStudentModal()" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
              <span class="material-symbols-outlined text-[18px]">person_add</span>
              <span>Tambah Mahasiswa</span>
            </button>
          </div>
        </div>

        <!-- 7 Mentorship Slots Grid -->
        <div class="grid grid-cols-1 gap-4" id="student-list-container">
          
          @forelse($students ?? [] as $index => $st)
            @php
              $slotNum = $index + 1;
              $initials = collect(explode(' ', $st->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
              $stLogbooksCount = $st->logbooks ? $st->logbooks->count() : 0;
              $stPendingCount = $st->logbooks ? $st->logbooks->where('status', 'pending')->count() : 0;
            @endphp
            <!-- STUDENT {{ $slotNum }}: {{ $st->name }} -->
            <div class="student-card p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/90 hover:border-[#0056B3]/40 hover:shadow-card-hover transition-all" data-status="{{ $stPendingCount > 0 ? 'review' : 'uptodate' }}" data-name="{{ strtolower($st->name . ' ' . $st->identifier) }}">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                
                <!-- Student Profile & Placement Details -->
                <div class="flex items-start gap-4 min-w-0">
                  <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-sm sm:text-base shadow-sm">
                      {{ $initials }}
                    </div>
                    <span class="absolute -top-1 -left-1 w-5 h-5 bg-[#0056B3] text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-white" title="Slot Bimbingan {{ $slotNum }}">
                      {{ $slotNum }}
                    </span>
                  </div>

                  <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                      <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">{{ $st->name }}</h3>
                      <span class="px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] text-xs font-mono font-semibold">NIM: {{ $st->identifier }}</span>
                      <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs font-medium">{{ $st->prodi ?? 'TI-6A (D4 TI)' }}</span>
                      @if($stPendingCount > 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200/80 text-xs font-bold">
                          <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                          <span>{{ $stPendingCount }} Logbook Perlu Review</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">
                          <span class="material-symbols-outlined text-[13px]">check_circle</span>
                          <span>Logbook Aman</span>
                        </span>
                      @endif
                    </div>

                    <!-- Mitra & Topic -->
                    <div class="mt-2 space-y-1">
                      <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-700">
                        <span class="material-symbols-outlined text-[16px] text-rose-500">domain</span>
                        <strong class="font-semibold">{{ $st->company ?? 'PT Telekomunikasi Seluler (Telkomsel)' }}</strong>
                        <span class="text-slate-400">•</span>
                        <span class="text-slate-500">{{ $st->email }}</span>
                      </div>
                      <p class="text-xs text-slate-500 line-clamp-1">
                        <strong class="text-slate-600">No. WhatsApp / HP:</strong> {{ $st->phone ?? '081234567890' }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Metrics & Action Center -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 lg:gap-6 flex-shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                  
                  <!-- Quick Progress Summary -->
                  <div class="grid grid-cols-3 gap-3 text-center w-full sm:w-auto">
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 min-w-[70px]">
                      <span class="text-[10px] text-slate-400 font-semibold block uppercase">Logbook</span>
                      <span class="text-sm font-bold text-[#0056B3] font-mono">{{ $stLogbooksCount }} Jurnal</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 min-w-[70px]">
                      <span class="text-[10px] text-slate-400 font-semibold block uppercase">Status</span>
                      <span class="text-sm font-bold {{ $stPendingCount > 0 ? 'text-amber-600' : 'text-emerald-600' }} font-mono">
                        {{ $stPendingCount > 0 ? $stPendingCount . ' Review' : 'Up to Date' }}
                      </span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 min-w-[70px]">
                      <span class="text-[10px] text-slate-400 font-semibold block uppercase">Bimbingan</span>
                      <span class="text-sm font-bold text-[#02796B] font-heading">F2-F4</span>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex items-center gap-2 w-full sm:w-auto justify-end flex-wrap">
                    <a href="{{ route('logbook.dosen', ['student_id' => $st->id]) }}" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3.5 py-2 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
                      <span class="material-symbols-outlined text-[16px]">rate_review</span>
                      <span>Review Logbook</span>
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $st->phone ?? '6281234567890') }}" target="_blank" title="Hubungi via WhatsApp" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-colors">
                      <span class="material-symbols-outlined text-[18px]">chat</span>
                    </a>
                    <a href="{{ route('logbook.dosen', ['student_id' => $st->id]) }}" title="Lihat Aktivitas Mahasiswa" class="p-2 bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                      <span class="material-symbols-outlined text-[18px]">visibility</span>
                    </a>
                    <!-- Tombol Hapus Mahasiswa dari Bimbingan -->
                    <form action="{{ route('dosen.students.remove', $st->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa {{ addslashes($st->name) }} (NIM: {{ $st->identifier }}) dari daftar bimbingan Anda? Seluruh riwayat logbook mahasiswa ini akan dibersihkan dan slot kuota Anda akan kembali terbuka.')" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" title="Hapus Mahasiswa dari Daftar Bimbingan" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 border border-rose-200 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-[18px]">person_remove</span>
                      </button>
                    </form>
                  </div>

                </div>

              </div>
            </div>
          @empty
            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
              <span class="material-symbols-outlined text-4xl text-slate-400">group_off</span>
              <p class="text-slate-600 font-medium mt-2">Belum ada mahasiswa bimbingan yang terdaftar.</p>
            </div>
          @endforelse

          <!-- Available Mentorship Slots up to 7 -->
          @for($slot = ($studentCount ?? count($students ?? [])) + 1; $slot <= 7; $slot++)
            <div class="p-5 sm:p-6 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 hover:bg-blue-50/30 hover:border-blue-300 transition-all flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center font-bold text-sm shadow-sm">
                  {{ $slot }}
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                      Slot Kuota Tersedia ({{ $slot }} dari 7)
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500 font-medium">{{ $slot === 7 ? 'Batas Maksimal Kapasitas' : 'Beban Bimbingan Terbuka' }}</span>
                  </div>
                  <h4 class="text-sm sm:text-base font-bold font-heading text-slate-700 mt-0.5">
                    {{ $slot === 7 ? 'Slot Kuota Terakhir Pembimbingan PKL' : 'Siap Menerima 1 Mahasiswa Bimbingan Tambahan' }}
                  </h4>
                  <p class="text-xs text-slate-400 mt-0.5">Mahasiswa Jurusan TIK dapat mengajukan Anda sebagai dosen pembimbing.</p>
                </div>
              </div>
              <button type="button" onclick="openNewStudentModal()" class="w-full sm:w-auto bg-white hover:bg-slate-100 text-[#0056B3] border border-slate-200 font-bold font-heading text-xs px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">add_circle</span>
                <span>Terima Mahasiswa Baru</span>
              </button>
            </div>
          @endfor

        </div>

      </div>

      <!-- Logbook Masuk dari Mahasiswa Bimbingan Section -->
      <div id="logbook-review-section" class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Verifikasi &amp; Review (Form F3)</span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs text-slate-500 font-medium">Logbook Ditulis oleh Mahasiswa Bimbingan</span>
            </div>
            <h2 class="text-xl font-bold font-heading text-slate-800 mt-0.5">
              Jurnal Aktivitas Harian Mahasiswa yang Perlu Ditinjau
            </h2>
          </div>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-[#0056B3] rounded-xl text-xs font-bold self-start sm:self-auto border border-blue-100">
            <span class="material-symbols-outlined text-[16px]">pending_actions</span>
            <span>{{ isset($logbooks) ? $logbooks->where('status', 'pending')->count() : 0 }} Menunggu Persetujuan</span>
          </span>
        </div>

        <!-- Logbooks Feed -->
        <div class="space-y-4">
          @forelse($logbooks ?? [] as $lb)
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200 hover:border-blue-300 transition-all space-y-3">
              <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0 mt-0.5">
                    {{ strtoupper(substr($lb->student->name ?? 'M', 0, 2)) }}
                  </div>
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <strong class="text-sm font-bold text-slate-800 font-heading">{{ $lb->student->name ?? 'Mahasiswa' }}</strong>
                      <span class="text-xs font-mono px-2 py-0.5 rounded bg-blue-50 text-[#0056B3] font-semibold">NIM: {{ $lb->student->identifier ?? '-' }}</span>
                      <span class="text-xs text-slate-500">{{ $lb->student->prodi ?? 'Teknik Informatika' }}</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 font-heading mt-1">
                      {{ $lb->subject }}
                    </h3>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-mono mt-0.5">
                      <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                      <span>{{ $lb->tanggal->translatedFormat('d F Y') }}</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                  @if($lb->status === 'approved')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                      <span class="material-symbols-outlined text-[14px]">check_circle</span>
                      <span>Telah Disetujui</span>
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                      <span class="material-symbols-outlined text-[14px] animate-spin">refresh</span>
                      <span>Menunggu Validasi</span>
                    </span>
                  @endif

                  <button type="button" 
                          onclick="openReviewModal({{ $lb->id }}, '{{ addslashes($lb->student->name ?? 'Mahasiswa') }}', '{{ addslashes($lb->student->identifier ?? '') }}', '{{ addslashes($lb->subject) }}', '{{ $lb->tanggal->translatedFormat('d F Y') }}', {{ json_encode($lb->content) }}, '{{ $lb->status }}', '{{ addslashes($lb->feedback ?? '') }}')"
                          class="bg-[#0056B3] hover:bg-[#00428A] text-white px-3.5 py-1.5 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[15px]">rate_review</span>
                    <span>Review Logbook</span>
                  </button>
                </div>
              </div>

              <!-- Long Text Content Written by Student -->
              <div class="p-4 bg-white rounded-xl border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-normal">
                {{ $lb->content }}
              </div>

              @if($lb->feedback)
                <div class="p-3 bg-blue-50/80 border border-blue-200/80 rounded-xl text-xs text-[#0056B3] flex items-start gap-2">
                  <span class="material-symbols-outlined text-[18px] mt-0.5 flex-shrink-0">comment</span>
                  <div>
                    <strong class="font-bold">Catatan Arahan Dosen:</strong>
                    <p class="mt-0.5 text-slate-700">{{ $lb->feedback }}</p>
                  </div>
                </div>
              @endif
            </div>
          @empty
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
              <span class="material-symbols-outlined text-slate-400 text-[32px]">folder_open</span>
              <p class="text-xs text-slate-500 mt-2 font-medium">Belum ada logbook baru yang dikirim oleh mahasiswa bimbingan.</p>
            </div>
          @endforelse
        </div>
      </div>

      <!-- Secondary Layout: Guidance Agenda (Jadwal Bimbingan) & Standards Guidance -->
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-6" id="agenda-section">
        
        <!-- Agenda Bimbingan Mendatang (7 Cols on xl) -->
        <div class="xl:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-5">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056B3] flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">calendar_month</span>
              </div>
              <div>
                <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Agenda Bimbingan Dosen Mendatang</h3>
                <p class="text-xs text-slate-500">Jadwal konsultasi laporan &amp; monitoring mahasiswa bimbingan</p>
              </div>
            </div>
            <button type="button" onclick="showToast('Fitur penjadwalan bimbingan kalender aktif.')" class="bg-blue-50 hover:bg-blue-100 text-[#0056B3] px-3 py-1.5 rounded-xl text-xs font-bold font-heading flex items-center gap-1 transition-colors">
              <span class="material-symbols-outlined text-[16px]">add</span>
              <span>Jadwalkan</span>
            </button>
          </div>

          <!-- Agenda List -->
          <div class="space-y-3">
            
            <!-- Agenda 1 -->
            <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-center justify-between gap-4 hover:border-blue-200 transition-colors">
              <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-[#0056B3] text-white flex flex-col items-center justify-center font-mono font-bold leading-none shadow-sm flex-shrink-0">
                  <span class="text-[9px]">FEB</span>
                  <span class="text-sm">24</span>
                </div>
                <div>
                  <h4 class="text-xs sm:text-sm font-bold text-slate-800 font-heading">
                    Review Bab II &amp; Arsitektur Cloud (Ahmad Fauzi)
                  </h4>
                  <p class="text-xs text-slate-500 mt-0.5">Daring via Zoom PKL TIK • 15:30 WIB</p>
                  <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-semibold text-emerald-600">
                    <span class="material-symbols-outlined text-[14px]">videocam</span>
                    <span>Tautan Zoom Terlampir</span>
                  </span>
                </div>
              </div>
              <button type="button" onclick="showToast('Membuka sesi Zoom bimbingan...')" class="px-3 py-1.5 bg-[#0056B3] text-white rounded-lg text-xs font-bold hover:bg-[#00428A] transition-colors flex-shrink-0">
                Mulai Sesi
              </button>
            </div>



          </div>
        </div>

        <!-- Panduan & Standar Pembimbingan Dosen TIK (5 Cols on xl) -->
        <div class="xl:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-card space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#02796B] flex items-center justify-center">
              <span class="material-symbols-outlined text-[22px]">policy</span>
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold font-heading text-slate-800">Panduan Akademik Pembimbing</h3>
              <p class="text-xs text-slate-500">Standar Beban &amp; Mutu PKL Jurusan TIK PNJ 2026</p>
            </div>
          </div>

          <div class="space-y-3 pt-1">
            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-start gap-3">
              <span class="w-6 h-6 rounded-full bg-[#0056B3] text-white flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
              <div>
                <strong class="text-xs font-bold text-slate-800 block">Kapasitas Maksimal 7 Mahasiswa</strong>
                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                  Setiap dosen pembimbing memiliki batasan kuota maksimal 7 mahasiswa untuk menjamin intensitas dan kualitas asistensi bimbingan.
                </p>
              </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-start gap-3">
              <span class="w-6 h-6 rounded-full bg-[#02796B] text-white flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
              <div>
                <strong class="text-xs font-bold text-slate-800 block">Minimal 8 Kali Pertemuan Bimbingan</strong>
                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                  Mahasiswa wajib memenuhi sedikitnya 8 log bimbingan (Form F3) sebelum diizinkan mendaftar Ujian Sidang PKL.
                </p>
              </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-start gap-3">
              <span class="w-6 h-6 rounded-full bg-[#009688] text-white flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
              <div>
                <strong class="text-xs font-bold text-slate-800 block">Penilaian Sidang &amp; Dosen (Form F6/F7)</strong>
                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                  Nilai dari Dosen Pembimbing menyumbang bobot 40% dari total kelulusan mata kuliah Praktik Kerja Lapangan.
                </p>
              </div>
            </div>
          </div>

          <div class="p-3.5 bg-blue-50/70 border border-blue-200/60 rounded-xl flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[#0056B3] text-[20px] mt-0.5 flex-shrink-0">info</span>
            <p class="text-xs text-[#0056B3] leading-relaxed">
              Jika mahasiswa bimbingan telah mencapai batas maksimal 7 orang, sistem akan otomatis mengunci pendaftaran baru ke profil Anda.
            </p>
          </div>
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

  <!-- MODAL: Review Logbook F3 -->
  <div id="modal-logbook" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Verifikasi Logbook (Form F3)</span>
          <h3 id="modal-student-name" class="text-lg font-bold font-heading text-slate-800 mt-0.5">Review Logbook Mahasiswa</h3>
          <p id="modal-student-sub" class="text-xs text-slate-500 mt-0.5">Detail aktivitas harian lapangan</p>
        </div>
        <button onclick="closeModal('modal-logbook')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <!-- Subject & Date Display -->
      <div class="p-3.5 bg-blue-50/70 border border-blue-100 rounded-xl space-y-1">
        <div class="flex items-center justify-between gap-2">
          <span class="text-[10px] font-bold text-[#0056B3] uppercase tracking-wider">Subject Logbook:</span>
          <span id="modal-logbook-tanggal" class="text-xs font-mono text-slate-500"></span>
        </div>
        <h4 id="modal-logbook-subject" class="text-sm sm:text-base font-bold font-heading text-slate-800"></h4>
      </div>

      <!-- Activity Content (Long Text) Written by Student -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Aktivitas Mahasiswa (Long Text):</label>
        <div id="modal-logbook-content" class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line max-h-56 overflow-y-auto"></div>
      </div>

      <!-- Form to Approve with Feedback -->
      <form id="form-approve-logbook" method="POST" action="" class="space-y-4 pt-2">
        @csrf
        <input type="hidden" name="status" value="approved">

        <div>
          <label for="review-feedback" class="block text-xs font-bold text-slate-700 mb-1">
            Catatan / Arahan Bimbingan Dosen:
          </label>
          <textarea id="review-feedback" name="feedback" rows="3" placeholder="Tuliskan catatan arahan bimbingan untuk mahasiswa..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">Logbook terperinci dan progres implementasi sesuai dengan timeline. Lanjutkan ke tahap berikutnya.</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeModal('modal-logbook')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Tutup
          </button>
          <button type="submit" class="bg-[#0056B3] hover:bg-[#00428A] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            <span>Setujui &amp; Validasi Logbook</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: Setujui Form F1 dengan Unggah Dokumen PDF Surat Pernyataan -->
  <div id="modal-f1" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Persetujuan Form F1</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Kesediaan Pembimbing PKL</h3>
          <p id="f1-student-info" class="text-xs text-slate-500 mt-0.5">Pengesahan Dosen Pembimbing</p>
        </div>
        <button type="button" onclick="closeModal('modal-f1')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <form id="form-approve-f1" method="POST" action="{{ route('f1.approve.general') }}" enctype="multipart/form-data" class="space-y-4" onsubmit="return ensureF1FormAction(this)">
        @csrf
        <input type="hidden" id="f1-hidden-app-id" name="application_id" value="">

        <div>
          <label for="f1-pdf-file" class="block text-xs font-bold text-slate-700 mb-1">
            Unggah Berkas Surat Pernyataan Kesediaan (Wajib PDF) <span class="text-rose-500">*</span>
          </label>
          <input type="file" id="f1-pdf-file" name="surat_pernyataan" required accept="application/pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#02796B] file:text-white hover:file:bg-[#006054] cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-1.5 focus:outline-none">
          <div class="flex items-center justify-between mt-1">
            <span class="text-[10px] text-slate-400">Format: .pdf (Maksimal 50 MB)</span>
            <span id="f1-file-size-badge" class="text-[10px] font-semibold text-slate-500 hidden"></span>
          </div>
          <div id="f1-file-feedback" class="text-xs mt-1.5 hidden"></div>
        </div>

        <div>
          <label for="f1-notes" class="block text-xs font-bold text-slate-700 mb-1">
            Catatan / Arahan Bimbingan Awal (Opsional):
          </label>
          <textarea id="f1-notes" name="notes" rows="2" placeholder="Catatan arahan awal bimbingan untuk mahasiswa..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#02796B]/20">Topik PKL dan tempat magang telah disetujui sesuai kurikulum D4 Teknik Informatika.</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeModal('modal-f1')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Batal
          </button>
          <button type="submit" id="btn-submit-f1" class="bg-[#02796B] hover:bg-[#006054] text-white px-5 py-2.5 rounded-xl text-xs font-bold font-heading flex items-center gap-1.5 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[16px]" id="icon-submit-f1">upload_file</span>
            <span id="text-submit-f1">Unggah Surat &amp; Sahkan Form F1</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: Tambah Mahasiswa Bimbingan Baru (Slot Kuota) -->
  <div id="modal-new-student" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5 animate-scale-up">
      <div class="flex items-start justify-between border-b border-slate-100 pb-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#02796B]">Beban Bimbingan (Slot Kuota)</span>
          <h3 class="text-lg font-bold font-heading text-slate-800 mt-0.5">Terima Mahasiswa Bimbingan Baru</h3>
          <p class="text-xs text-slate-500 mt-0.5">Sisa kuota bimbingan Anda: <strong>{{ 7 - ($studentCount ?? 1) }} Mahasiswa</strong></p>
        </div>
        <button onclick="closeModal('modal-new-student')" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Cari Mahasiswa Terdaftar:</label>
          <input type="text" placeholder="Ketik NIM atau Nama Mahasiswa TIK..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Pengajuan Menunggu Persetujuan:</label>
          <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-center">
            <span class="material-symbols-outlined text-slate-400 text-[28px]">inbox</span>
            <p class="text-xs text-slate-500 font-medium mt-1">Saat ini belum ada pengajuan mahasiswa baru yang menunggu persetujuan.</p>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
        <button onclick="closeModal('modal-new-student')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- Interactive JavaScript -->
  <script>
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

    // Filter Students by Name or Status
    function filterStudents() {
      const searchEl = document.getElementById('student-search');
      const filterEl = document.getElementById('status-filter');
      const query = (searchEl ? searchEl.value : '').toLowerCase();
      const statusFilter = (filterEl ? filterEl.value : 'all');
      const cards = document.querySelectorAll('.student-card');

      cards.forEach(card => {
        const nameAttr = (card.getAttribute('data-name') || '').toLowerCase();
        const statusAttr = card.getAttribute('data-status') || '';

        const matchesQuery = nameAttr.includes(query);
        const matchesStatus = (statusFilter === 'all') || (statusAttr === statusFilter) || (statusFilter !== 'review' && statusFilter !== 'uptodate');

        if (matchesQuery && matchesStatus) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }

    // Auto-scroll when visiting with tab=f1 or #f1-section
    function checkF1HashOrTab() {
      const urlParams = new URLSearchParams(window.location.search);
      if (window.location.hash === '#f1-section' || urlParams.get('tab') === 'f1') {
        const section = document.getElementById('f1-section');
        if (section) {
          setTimeout(() => {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 100);
        }
      } else if (window.location.hash === '#mahasiswa-section') {
        const section = document.getElementById('mahasiswa-section');
        if (section) {
          setTimeout(() => {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 100);
        }
      }
    }
    document.addEventListener('DOMContentLoaded', checkF1HashOrTab);
    window.addEventListener('hashchange', checkF1HashOrTab);

    // Open Review Modal with full logbook details
    function openReviewModal(id, studentName, studentNim, subject, tanggal, content, status, feedback) {
      document.getElementById('modal-student-name').innerText = `Review Logbook: ${studentName}`;
      document.getElementById('modal-student-sub').innerText = `NIM: ${studentNim}`;
      document.getElementById('modal-logbook-subject').innerText = subject;
      document.getElementById('modal-logbook-tanggal').innerText = tanggal;
      document.getElementById('modal-logbook-content').innerText = content;
      
      const form = document.getElementById('form-approve-logbook');
      form.action = `/logbooks/${id}/status`;

      if (feedback && feedback.trim() !== '') {
        document.getElementById('review-feedback').value = feedback;
      }

      openModal('modal-logbook');
    }

    function openModal(id) {
      const modal = document.getElementById(id);
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function openNewStudentModal() {
      openModal('modal-new-student');
    }

    function openF1ModalFromBtn(btn) {
      openF1Modal(
        btn.dataset.appId,
        btn.dataset.studentName,
        btn.dataset.studentNim,
        btn.dataset.company,
        btn.dataset.title
      );
    }

    function openF1Modal(applicationId, name, nim, company, topic) {
      const studentInfoEl = document.getElementById('f1-student-info');
      if (studentInfoEl) {
        studentInfoEl.innerText = `${name} (${nim}) • ${company}`;
      }
      
      const form = document.getElementById('form-approve-f1');
      if (form) {
        form.action = `/f1/${applicationId}/approve`;
      }

      const hiddenAppId = document.getElementById('f1-hidden-app-id');
      if (hiddenAppId) {
        hiddenAppId.value = applicationId;
      }

      // Reset modal state
      const pdfInput = document.getElementById('f1-pdf-file');
      if (pdfInput) pdfInput.value = '';
      const feedback = document.getElementById('f1-file-feedback');
      if (feedback) { feedback.className = 'text-xs mt-1.5 hidden'; feedback.innerHTML = ''; }
      const sizeBadge = document.getElementById('f1-file-size-badge');
      if (sizeBadge) { sizeBadge.className = 'text-[10px] font-semibold text-slate-500 hidden'; sizeBadge.innerText = ''; }
      const submitBtn = document.getElementById('btn-submit-f1');
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
      }
      const submitText = document.getElementById('text-submit-f1');
      if (submitText) submitText.innerText = 'Unggah Surat & Sahkan Form F1';
      const submitIcon = document.getElementById('icon-submit-f1');
      if (submitIcon) submitIcon.classList.remove('animate-spin');

      openModal('modal-f1');
    }

    // Client-side file size & extension inspection
    document.addEventListener('DOMContentLoaded', function() {
      const fileInput = document.getElementById('f1-pdf-file');
      if (fileInput) {
        fileInput.addEventListener('change', function() {
          const file = this.files[0];
          const feedback = document.getElementById('f1-file-feedback');
          const sizeBadge = document.getElementById('f1-file-size-badge');
          if (!file) {
            if (feedback) feedback.className = 'text-xs mt-1.5 hidden';
            if (sizeBadge) sizeBadge.className = 'text-[10px] font-semibold text-slate-500 hidden';
            return;
          }

          const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
          if (sizeBadge) {
            sizeBadge.innerText = `${sizeMB} MB`;
            sizeBadge.classList.remove('hidden');
          }

          if (!file.name.toLowerCase().endsWith('.pdf')) {
            if (feedback) {
              feedback.className = 'text-xs mt-1.5 text-rose-600 font-semibold flex items-center gap-1';
              feedback.innerHTML = '<span class="material-symbols-outlined text-[15px]">error</span> Wajib file berekstensi .pdf!';
            }
            this.value = '';
            if (sizeBadge) sizeBadge.classList.add('hidden');
            return;
          }

          if (file.size > 52428800) { // 50MB
            if (feedback) {
              feedback.className = 'text-xs mt-1.5 text-rose-600 font-semibold flex items-center gap-1';
              feedback.innerHTML = `<span class="material-symbols-outlined text-[15px]">error</span> Ukuran file (${sizeMB} MB) melebihi batas maksimal 50 MB!`;
            }
            this.value = '';
            return;
          }

          if (feedback) {
            feedback.className = 'text-xs mt-1.5 text-emerald-600 font-semibold flex items-center gap-1';
            feedback.innerHTML = `<span class="material-symbols-outlined text-[15px]">check_circle</span> File siap: <strong>${file.name}</strong> (${sizeMB} MB)`;
          }
        });
      }
    });

    function ensureF1FormAction(form) {
      const hiddenAppId = document.getElementById('f1-hidden-app-id');
      const appId = hiddenAppId ? hiddenAppId.value : null;
      if (appId) {
        form.action = `/f1/${appId}/approve`;
      }
      const submitBtn = document.getElementById('btn-submit-f1');
      const submitText = document.getElementById('text-submit-f1');
      const submitIcon = document.getElementById('icon-submit-f1');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        if (submitText) submitText.innerText = 'Mengunggah & Memproses...';
        if (submitIcon) submitIcon.classList.add('animate-spin');
      }
      return true;
    }

    function showF1Tab() {
      const section = document.getElementById('f1-section');
      if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    function acceptStudent(name) {
      closeModal('modal-new-student');
      showToast(`${name} berhasil ditambahkan ke dalam daftar bimbingan (Kuota terpakai: 6/7).`);
    }

    // Toast Notification Feedback (Supports 'success' and 'error')
    function showToast(message, type = 'success') {
      const existingToast = document.getElementById('sim-toast');
      if (existingToast) existingToast.remove();

      const isError = type === 'error';
      const toast = document.createElement('div');
      toast.id = 'sim-toast';
      toast.className = `fixed bottom-6 right-6 ${isError ? 'bg-rose-900 border border-rose-700 text-white' : 'bg-slate-900 text-white'} px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 z-50 text-xs sm:text-sm transition-all`;
      toast.innerHTML = `
        <span class="material-symbols-outlined ${isError ? 'text-rose-300' : 'text-emerald-400'} text-[20px]">${isError ? 'error' : 'check_circle'}</span>
        <span>${message}</span>
      `;
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
      }, 5000);
    }
  </script>

</body>
</html>
