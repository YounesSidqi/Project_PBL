@php
  $user = Auth::user();
  $role = $role ?? (request()->query('role') ?? (
      request()->routeIs('*admin*') ? 'admin' :
      (request()->routeIs('*kps*') ? 'kps' :
      (request()->routeIs('*industri*') ? 'industri' :
      (request()->routeIs('*dosen*') ? 'dosen' : ($user->role ?? 'mahasiswa'))))
  ));
  
  $tab = request()->query('tab');
  if ($role === 'admin') {
    $active = match ($tab) {
      'surat' => 'admin_surat',
      'industri' => 'admin_industri',
      'mahasiswa' => 'admin_mahasiswa',
      'users' => 'admin_users',
      default => 'admin_surat',
    };
  } elseif ($role === 'kps') {
    $active = match ($tab) {
      'plotting' => 'plotting_f1',
      'kuota' => 'kuota_dosen',
      'rekap' => 'rekap_mahasiswa',
      default => 'dashboard_kps',
    };
  } elseif ($role === 'industri' || $role === 'dudi') {
    $active = match ($tab) {
      'f2' => 'industri_f2',
      'f3' => 'industri_f3',
      'f5' => 'industri_f5',
      default => 'industri_mahasiswa',
    };
  } elseif (!empty($activePage)) {
    $active = $activePage;
  } elseif (request()->routeIs('form.f2*')) {
    $active = 'f2';
  } elseif (request()->routeIs('form.f4*')) {
    $active = 'f4';
  } elseif (request()->routeIs('form.f5*')) {
    $active = 'industri_f5';
  } elseif (request()->routeIs('form.f6*')) {
    $active = 'f6';
  } else {
    $active = request()->routeIs('*logbook*') ? 'logbook' : (request()->routeIs('*dashboard*') ? 'dashboard' : '');
  }
  
  if ($role === 'admin') {
    $userName = ($user && $user->role === 'admin') ? $user->name : 'Biro Administrasi JTIK';
    $userRoleLabel = 'Admin Jurusan TIK';
  } elseif ($role === 'kps') {
    $userName = ($user && in_array($user->role, ['kps', 'admin'])) ? $user->name : 'Dr. Eng. Nur Aisyah, S.Kom., M.T. (KPS)';
    $userRoleLabel = 'KPS Teknik Informatika';
  } elseif ($role === 'industri' || $role === 'dudi') {
    $userName = ($user && $user->isIndustri()) ? $user->name : 'Pembimbing Lapangan DUDI';
    $userRoleLabel = 'Mitra Industri (DUDI)';
  } elseif ($role === 'dosen') {
    $userName = ($user && $user->role === 'dosen') ? $user->name : 'Dr. Bambang Sutrisno, M.T.';
    $userRoleLabel = 'Dosen Pembimbing';
  } else {
    $userName = $user->name ?? 'Ahmad Fauzi';
    $userRoleLabel = 'Mahasiswa (D4 TI)';
  }
  
  if (str_starts_with($userName, 'Dr. ')) {
    $clean = trim(substr($userName, 4));
    $parts = preg_split('/\s+/', $clean);
    $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr($parts[1] ?? '', 0, 1));
  } else {
    $parts = preg_split('/\s+/', $userName);
    if (count($parts) >= 2) {
      $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    } else {
      $initials = strtoupper(substr($userName, 0, 2));
    }
  }

  // 1. Admin Jurusan Navigation (PRD 3 & 5.1, 7.1, 7.3, 10.1)
  $suratPendingCount = \App\Models\PengajuanMagang::where('status_surat_pengantar', 'pending')->count();
  $industriPendingCount = \App\Models\Industri::where('status_verifikasi', 'pending')->count();

  $adminNav = [
    [
      'id' => 'admin_surat',
      'label' => 'Surat Pengantar PKL',
      'icon' => 'mark_email_read',
      'route' => route('dashboard.admin', ['tab' => 'surat']),
      'badge' => ($suratPendingCount > 0 ? $suratPendingCount . ' Pending' : null),
      'badgeClass' => 'bg-amber-100 text-amber-800 font-bold',
    ],
    [
      'id' => 'admin_industri',
      'label' => 'Verifikasi Mitra Industri',
      'icon' => 'domain_verification',
      'route' => route('dashboard.admin', ['tab' => 'industri']),
      'badge' => ($industriPendingCount > 0 ? $industriPendingCount . ' Baru' : null),
      'badgeClass' => 'bg-purple-100 text-purple-800 font-bold',
    ],
    [
      'id' => 'admin_mahasiswa',
      'label' => 'Manajemen Mahasiswa & Import',
      'icon' => 'upload_file',
      'route' => route('dashboard.admin', ['tab' => 'mahasiswa']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'admin_users',
      'label' => 'Reset Password Pengguna',
      'icon' => 'lock_reset',
      'route' => route('dashboard.admin', ['tab' => 'users']),
      'badge' => null,
      'badgeClass' => '',
    ],
  ];

  // 2. KPS Navigation
  $pendingKpsCount = \App\Models\F1Application::where('status', 'pending')->count();
  $totalDosenCount = \App\Models\User::where('role', 'dosen')->count();

  $kpsNav = [
    [
      'id' => 'dashboard_kps',
      'label' => 'Dashboard KPS',
      'icon' => 'dashboard',
      'route' => route('dashboard.kps', ['tab' => 'ringkasan']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'plotting_f1',
      'label' => 'Penetapan & Plotting (F1)',
      'icon' => 'assignment_turned_in',
      'route' => route('dashboard.kps', ['tab' => 'plotting']),
      'badge' => ($pendingKpsCount > 0 ? $pendingKpsCount . ' Baru' : null),
      'badgeClass' => ($pendingKpsCount > 0 ? 'bg-amber-100 text-amber-800 font-bold' : ''),
    ],
    [
      'id' => 'kuota_dosen',
      'label' => 'Distribusi Kuota Dosen',
      'icon' => 'pie_chart',
      'route' => route('dashboard.kps', ['tab' => 'kuota']),
      'badge' => $totalDosenCount . ' Dosen',
      'badgeClass' => 'bg-blue-100 text-[#0056B3] font-semibold',
    ],
    [
      'id' => 'rekap_mahasiswa',
      'label' => 'Rekap Seluruh Mahasiswa',
      'icon' => 'badge',
      'route' => route('dashboard.kps', ['tab' => 'rekap']),
      'badge' => null,
      'badgeClass' => '',
    ],
  ];

  // 3. Pembimbing Industri (DUDI) Navigation (PRD 3 & 5.3)
  $industriNav = [
    [
      'id' => 'industri_mahasiswa',
      'label' => 'Mahasiswa Magang',
      'icon' => 'groups',
      'route' => route('dashboard.industri', ['tab' => 'mahasiswa']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'industri_f2',
      'label' => 'To-Do List Tugas (F2)',
      'icon' => 'task_alt',
      'route' => route('dashboard.industri', ['tab' => 'f2']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'industri_f3',
      'label' => 'Verifikasi Logbook (F3)',
      'icon' => 'history_edu',
      'route' => route('dashboard.industri', ['tab' => 'f3']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'industri_f5',
      'label' => 'Evaluasi Kinerja (F5)',
      'icon' => 'lock',
      'route' => route('form.f5.industri'),
      'badge' => 'Bobot 60%',
      'badgeClass' => 'bg-rose-100 text-rose-800 font-bold',
    ],
  ];

  // 4. Mahasiswa Menu Items
  $mahasiswaNav = [
    [
      'id' => 'dashboard',
      'label' => 'Dashboard & Penempatan (F1)',
      'icon' => 'dashboard',
      'route' => route('dashboard.mahasiswa'),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'f2',
      'label' => 'Requirement Industri (F2)',
      'icon' => 'terminal',
      'route' => route('form.f2.mahasiswa'),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'logbook',
      'label' => 'Logbook Harian (F3)',
      'icon' => 'menu_book',
      'route' => route('logbook.mahasiswa'),
      'badge' => 'Aktif',
      'badgeClass' => 'bg-[#009688]/10 text-[#02796B]',
    ],
    [
      'id' => 'f4',
      'label' => 'Bimbingan Dosen (F4)',
      'icon' => 'co_present',
      'route' => route('form.f4.mahasiswa'),
      'badge' => 'Min 8',
      'badgeClass' => 'bg-blue-100 text-[#0056B3]',
    ],
  ];

  // 5. Dosen Menu Items
  $dosenId = ($user && $user->role === 'dosen') ? $user->id : (\App\Models\User::where('role', 'dosen')->value('id') ?? 1);
  $studentCount = \App\Models\User::where('role', 'mahasiswa')->where('dosen_id', $dosenId)->count();
  $pendingCount = \App\Models\Logbook::where('dosen_id', $dosenId)->where('status', 'pending')->count();

  $dosenNav = [
    [
      'id' => 'dashboard',
      'label' => 'Mahasiswa Bimbingan',
      'icon' => 'groups',
      'route' => route('dashboard.dosen'),
      'badge' => $studentCount . '/7',
      'badgeClass' => 'bg-white/20 text-white',
    ],
    [
      'id' => 'f2',
      'label' => 'Capaian Industri (F2)',
      'icon' => 'terminal',
      'route' => route('form.f2.dosen'),
      'badge' => 'Read-Only',
      'badgeClass' => 'bg-slate-100 text-slate-600',
    ],
    [
      'id' => 'logbook',
      'label' => 'Logbook Industri (F3)',
      'icon' => 'rate_review',
      'route' => route('logbook.dosen'),
      'badge' => ($pendingCount > 0 ? $pendingCount . ' Baru' : '0 Baru'),
      'badgeClass' => ($pendingCount > 0 ? 'bg-amber-100 text-amber-800 font-bold' : 'bg-blue-100 text-[#0056B3]'),
    ],
    [
      'id' => 'f4',
      'label' => 'Buku Bimbingan (F4)',
      'icon' => 'co_present',
      'route' => route('form.f4.dosen'),
      'badge' => 'Wajib 8',
      'badgeClass' => 'bg-teal-100 text-teal-800',
    ],
    [
      'id' => 'f6',
      'label' => 'Evaluasi Akademik (F6)',
      'icon' => 'grade',
      'route' => route('form.f6.dosen'),
      'badge' => 'Bobot 40%',
      'badgeClass' => 'bg-emerald-100 text-emerald-800 font-bold',
    ],
  ];

  if ($role === 'admin') {
    $navItems = $adminNav;
  } elseif ($role === 'kps') {
    $navItems = $kpsNav;
  } elseif ($role === 'industri' || $role === 'dudi') {
    $navItems = $industriNav;
  } elseif ($role === 'dosen') {
    $navItems = $dosenNav;
  } else {
    $navItems = $mahasiswaNav;
  }
@endphp

<!-- Sidebar Navigation Component -->
<aside id="main-sidebar" class="fixed top-0 bottom-0 left-0 w-72 bg-white border-r border-slate-200/80 z-50 flex flex-col justify-between -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-sm">
  <div class="flex flex-col flex-1 overflow-y-auto">
    
    <!-- Brand Header -->
    <div class="h-20 px-6 border-b border-slate-100 flex items-center justify-between">
      <a href="{{ $role === 'admin' ? route('dashboard.admin') : ($role === 'kps' ? route('dashboard.kps') : ($role === 'industri' || $role === 'dudi' ? route('dashboard.industri') : ($role === 'dosen' ? route('dashboard.dosen') : route('dashboard.mahasiswa')))) }}" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0056B3] via-[#02796B] to-[#009688] p-[2px] shadow-sm transition-transform group-hover:scale-105 flex-shrink-0">
          <div class="w-full h-full bg-white rounded-[10px] flex items-center justify-center overflow-hidden">
            <span class="material-symbols-outlined text-[#0056B3] text-[22px]">
              {{ $role === 'admin' ? 'admin_panel_settings' : ($role === 'kps' ? 'gavel' : ($role === 'industri' || $role === 'dudi' ? 'domain' : ($role === 'dosen' ? 'supervisor_account' : 'school'))) }}
            </span>
          </div>
        </div>
        <div class="flex flex-col min-w-0">
          <div class="flex items-center gap-1.5">
            <span class="font-heading font-extrabold text-base tracking-tight text-[#0056B3]">SIM-PKL</span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $role === 'admin' ? 'bg-blue-100 text-blue-800' : ($role === 'kps' ? 'bg-amber-100 text-amber-800' : ($role === 'industri' || $role === 'dudi' ? 'bg-teal-100 text-teal-800' : ($role === 'dosen' ? 'bg-[#02796B]/10 text-[#02796B]' : 'bg-[#009688]/10 text-[#02796B]'))) }} tracking-wide">
              {{ $role === 'admin' ? 'ADMIN' : ($role === 'kps' ? 'KPS TI' : ($role === 'industri' || $role === 'dudi' ? 'DUDI' : ($role === 'dosen' ? 'DOSEN' : 'MHS'))) }}
            </span>
          </div>
          <span class="text-[11px] text-slate-500 font-medium truncate">Politeknik Negeri Jakarta</span>
        </div>
      </a>
      <button onclick="toggleSidebar()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 lg:hidden" aria-label="Tutup menu">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <!-- Academic Cycle Badge -->
    <div class="px-5 pt-4 pb-2">
      <div class="px-3.5 py-2.5 bg-gradient-to-r from-blue-50/70 to-teal-50/50 rounded-xl border border-blue-100/60 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-[#009688] animate-pulse"></span>
          <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
            {{ $role === 'admin' ? 'Biro Jurusan' : ($role === 'kps' ? 'Portal KPS' : ($role === 'industri' || $role === 'dudi' ? 'Portal DUDI' : ($role === 'dosen' ? 'Portal Dosen' : 'Modul PKL'))) }}
          </span>
        </div>
        <span class="text-xs font-bold text-[#0056B3]">TA 2025/2026</span>
      </div>
    </div>

    <!-- Navigation Links -->
    <nav class="px-3 py-3 space-y-1.5 flex-1">
      @foreach($navItems as $item)
        @php
          $isSelected = ($active === $item['id']);
        @endphp

        <a href="{{ $item['route'] }}" 
           data-nav-item="{{ $item['id'] }}"
           @if(!empty($item['onclick'])) onclick="{!! $item['onclick'] !!}" @endif
           class="sidebar-nav-link group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all relative
                  {{ $isSelected 
                     ? 'bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white shadow-nav-active font-medium' 
                     : 'text-slate-600 hover:text-[#0056B3] hover:bg-blue-50/60 font-medium' }}">
          
          <span class="material-symbols-outlined sidebar-nav-icon text-[20px] transition-colors
                       {{ $isSelected ? 'text-white material-symbols-fill' : 'text-slate-400 group-hover:text-[#0056B3]' }}">
            {{ $item['icon'] }}
          </span>

          <span class="truncate flex-1">{{ $item['label'] }}</span>

          @if(!empty($item['badge']))
            <span data-original-badge="{{ $item['badgeClass'] }}"
                  class="sidebar-nav-badge text-[10px] font-bold px-1.5 py-0.5 rounded-full whitespace-nowrap transition-colors
                         {{ $isSelected ? 'bg-white/20 text-white' : $item['badgeClass'] }}">
              {{ $item['badge'] }}
            </span>
          @endif
        </a>
      @endforeach
    </nav>

    <!-- Sidebar Bottom Info Banner -->
    <div class="p-4 border-t border-slate-100">
      @if($role === 'admin')
        <div class="bg-gradient-to-br from-slate-50 to-blue-50/60 border border-blue-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-[#0056B3]">
            <span class="material-symbols-outlined text-[18px]">verified_user</span>
            <span class="text-xs font-bold font-heading">Otoritas Admin Jurusan</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Pusat administrasi PKL, verifikasi surat pengantar resmi &amp; legalisasi data industri 2026.
          </p>
        </div>
      @elseif($role === 'kps')
        <div class="bg-gradient-to-br from-slate-50 to-amber-50/60 border border-amber-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-amber-800">
            <span class="material-symbols-outlined text-[18px]">gavel</span>
            <span class="text-xs font-bold font-heading">Otoritas Penetapan KPS</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Hanya KPS yang memiliki hak penetapan resmi dosen pembimbing.
          </p>
        </div>
      @elseif($role === 'industri' || $role === 'dudi')
        <div class="bg-gradient-to-br from-slate-50 to-teal-50/60 border border-teal-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-teal-800">
            <span class="material-symbols-outlined text-[18px]">domain</span>
            <span class="text-xs font-bold font-heading">Pembimbing Industri</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Kelola spesifikasi tugas F2, ACC logbook harian F3, &amp; evaluasi kinerja F5 rahasia (60%).
          </p>
        </div>
      @elseif($role === 'dosen')
        <div class="bg-gradient-to-br from-slate-50 to-teal-50/60 border border-teal-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-[#02796B]">
            <span class="material-symbols-outlined text-[18px]">info</span>
            <span class="text-xs font-bold font-heading">Evaluasi &amp; Fuzzy Logic</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Min. 8 sesi bimbingan F4 wajib terpenuhi sebelum transmisi nilai akhir Fuzzy Logic F6.
          </p>
        </div>
      @else
        <div class="bg-gradient-to-br from-slate-50 to-blue-50/60 border border-blue-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-[#0056B3]">
            <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
            <span class="text-xs font-bold font-heading">Panduan PKL 2026</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Digitalisasi formulir F1-F7 tanpa tanda tangan basah ber-KOP resmi PNJ.
          </p>
        </div>
      @endif
    </div>

  </div>

  <!-- User Account Footer Profile -->
  <div class="p-4 border-t border-slate-100 bg-slate-50/50">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3 min-w-0">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">
          {{ $initials }}
        </div>
        <div class="flex flex-col min-w-0">
          <span class="text-xs font-bold text-slate-800 truncate">{{ $userName }}</span>
          <span class="text-[10px] text-slate-400 truncate">{{ $userRoleLabel }}</span>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Keluar dari Akun">
          <span class="material-symbols-outlined text-[20px]">logout</span>
        </button>
      </form>
    </div>
  </div>
</aside>
