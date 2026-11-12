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
      'plotting' => 'admin_plotting',
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
    $userName = ($user && $user->role === 'admin') ? $user->name : 'Admin JTIK';
    $userRoleLabel = 'Admin Jurusan TIK';
  } elseif ($role === 'kps') {
    $userName = ($user && in_array($user->role, ['kps', 'admin'])) ? $user->name : 'KPS Teknik Informatika';
    $userRoleLabel = 'Koordinator Prodi';
  } elseif ($role === 'industri' || $role === 'dudi') {
    $userName = ($user && $user->isIndustri()) ? $user->name : 'Pembimbing DUDI';
    $userRoleLabel = 'Mitra Industri';
  } elseif ($role === 'dosen') {
    $userName = ($user && $user->role === 'dosen') ? $user->name : 'Dosen Pembimbing';
    $userRoleLabel = 'Dosen Pembimbing';
  } else {
    $userName = $user->name ?? 'Mahasiswa';
    $userRoleLabel = 'Mahasiswa PKL';
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

  $suratPendingCount = \App\Models\PengajuanMagang::forAdmin($user)
    ->where(function ($q) {
      $q->where('status_surat_pengantar', 'pending')
        ->orWhere(function ($sub) {
          $sub->whereNotNull('industri_id')->where('status_penempatan', 'pending');
        });
    })
    ->count();
  $f1PendingAdminCount = \App\Models\F1Application::forAdmin($user)
    ->whereIn('status', [
      \App\Models\F1Application::STATUS_PENDING_ADMIN,
      \App\Models\F1Application::STATUS_REVISION_REQUESTED,
      'pending',
    ])
    ->count();
  $industriPendingCount = \App\Models\Industri::where('status_verifikasi', 'pending')->count();
  $totalPendingAdmin = $suratPendingCount + $industriPendingCount;

  $adminNav = [
    [
      'id' => 'admin_surat',
      'label' => 'Verifikasi Pengajuan',
      'icon' => 'mark_email_read',
      'route' => route('dashboard.admin', ['tab' => 'surat']),
      'badge' => ($totalPendingAdmin > 0 ? $totalPendingAdmin : null),
      'badgeClass' => 'bg-amber-100 text-amber-800 font-bold',
    ],
    [
      'id' => 'admin_plotting',
      'label' => 'Plotting Dosen (F1)',
      'icon' => 'assignment_ind',
      'route' => route('dashboard.admin', ['tab' => 'plotting']),
      'badge' => ($f1PendingAdminCount > 0 ? $f1PendingAdminCount : null),
      'badgeClass' => 'bg-rose-100 text-rose-800 font-bold',
    ],
    [
      'id' => 'admin_mahasiswa',
      'label' => 'Data Mahasiswa & PKL',
      'icon' => 'school',
      'route' => route('dashboard.admin', ['tab' => 'mahasiswa']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'admin_industri',
      'label' => 'Mitra Industri (DUDI)',
      'icon' => 'domain',
      'route' => route('dashboard.admin', ['tab' => 'industri']),
      'badge' => ($industriPendingCount > 0 ? $industriPendingCount . ' Pending' : null),
      'badgeClass' => 'bg-amber-100 text-amber-800 font-bold',
    ],
    [
      'id' => 'admin_users',
      'label' => 'Dosen & Akun Pengguna',
      'icon' => 'manage_accounts',
      'route' => route('dashboard.admin', ['tab' => 'users']),
      'badge' => null,
      'badgeClass' => '',
    ],
  ];

  $pendingKpsCount = \App\Models\F1Application::where('status', \App\Models\F1Application::STATUS_PENDING_KPS)->count();
  $totalDosenCount = \App\Models\User::where('role', 'dosen')->count();

  $kpsNav = [
    [
      'id' => 'dashboard_kps',
      'label' => 'Ringkasan KPS',
      'icon' => 'dashboard',
      'route' => route('dashboard.kps', ['tab' => 'ringkasan']),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'plotting_f1',
      'label' => 'Penetapan F1',
      'icon' => 'assignment_turned_in',
      'route' => route('dashboard.kps', ['tab' => 'plotting']),
      'badge' => ($pendingKpsCount > 0 ? $pendingKpsCount : null),
      'badgeClass' => 'bg-amber-100 text-amber-800 font-bold',
    ],
    [
      'id' => 'kuota_dosen',
      'label' => 'Kuota Dosen',
      'icon' => 'pie_chart',
      'route' => route('dashboard.kps', ['tab' => 'kuota']),
      'badge' => $totalDosenCount,
      'badgeClass' => 'bg-blue-100 text-[#0056B3] font-semibold',
    ],
    [
      'id' => 'rekap_mahasiswa',
      'label' => 'Rekap Mahasiswa',
      'icon' => 'badge',
      'route' => route('dashboard.kps', ['tab' => 'rekap']),
      'badge' => null,
      'badgeClass' => '',
    ],
  ];

  $industriStudentId = request()->query('student_id') ?? ($activePengajuan->mahasiswa_id ?? ($selectedPengajuan->mahasiswa_id ?? null));
  $industriParams = fn (array $extra = []) => array_filter(array_merge($extra, ['student_id' => $industriStudentId]));

  $industriNav = [
    [
      'id' => 'industri_mahasiswa',
      'label' => 'Mahasiswa Magang',
      'icon' => 'groups',
      'route' => route('dashboard.industri', $industriParams(['tab' => 'mahasiswa'])),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'industri_f2',
      'label' => 'Matriks Tugas (F2)',
      'icon' => 'task_alt',
      'route' => route('dashboard.industri', $industriParams(['tab' => 'f2'])),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'industri_f3',
      'label' => 'Logbook Harian (F3)',
      'icon' => 'history_edu',
      'route' => route('dashboard.industri', $industriParams(['tab' => 'f3'])),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'industri_f5',
      'label' => 'Evaluasi Kinerja (F5)',
      'icon' => 'grade',
      'route' => route('form.f5.industri', $industriParams()),
      'badge' => '60%',
      'badgeClass' => 'bg-teal-100 text-teal-800 font-bold',
    ],
  ];

  $mhsPengajuan = ($user && $role === 'mahasiswa')
    ? \App\Models\PengajuanMagang::with('industri')->where('mahasiswa_id', $user->id)->latest()->first()
    : null;
  $mhsHasCompany = $mhsPengajuan && $mhsPengajuan->industri && $mhsPengajuan->status_verifikasi_efektif === 'acc';
  $mhsHasDosenStep = ($user && !empty($user->dosen_id))
    || ($mhsPengajuan && !empty($mhsPengajuan->dosen_id))
    || ($user && \App\Models\F1Application::where('student_id', $user->id)->whereIn('status', ['pending_admin', 'pending_kps', 'revision_requested', 'pending', 'approved'])->exists());

  $mahasiswaNav = [
    [
      'id' => 'dashboard',
      'label' => 'Dashboard & Penempatan',
      'icon' => 'dashboard',
      'route' => route('dashboard.mahasiswa'),
      'badge' => null,
      'badgeClass' => '',
      'locked' => false,
    ],
    [
      'id' => 'f2',
      'label' => 'Matriks Tugas (F2)',
      'icon' => 'terminal',
      'route' => $mhsHasCompany ? route('form.f2.mahasiswa') : '#',
      'badge' => null,
      'badgeClass' => '',
      'locked' => !$mhsHasCompany,
    ],
    [
      'id' => 'logbook',
      'label' => 'Logbook Harian (F3)',
      'icon' => 'menu_book',
      'route' => $mhsHasDosenStep ? route('logbook.mahasiswa') : '#',
      'badge' => null,
      'badgeClass' => '',
      'locked' => !$mhsHasDosenStep,
    ],
    [
      'id' => 'f4',
      'label' => 'Bimbingan Dosen (F4)',
      'icon' => 'co_present',
      'route' => $mhsHasDosenStep ? route('form.f4.mahasiswa') : '#',
      'badge' => null,
      'badgeClass' => '',
      'locked' => !$mhsHasDosenStep,
    ],
  ];

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
      'badgeClass' => 'bg-blue-100 text-[#0056B3] font-bold',
    ],
    [
      'id' => 'f2',
      'label' => 'Matriks Tugas (F2)',
      'icon' => 'terminal',
      'route' => route('form.f2.dosen'),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'logbook',
      'label' => 'Logbook Harian (F3)',
      'icon' => 'rate_review',
      'route' => route('logbook.dosen'),
      'badge' => ($pendingCount > 0 ? $pendingCount : null),
      'badgeClass' => 'bg-amber-100 text-amber-800 font-bold',
    ],
    [
      'id' => 'f4',
      'label' => 'Buku Bimbingan (F4)',
      'icon' => 'co_present',
      'route' => route('form.f4.dosen'),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'f6',
      'label' => 'Evaluasi Akademik (F6)',
      'icon' => 'grade',
      'route' => route('form.f6.dosen'),
      'badge' => '40%',
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
<aside id="main-sidebar" class="fixed top-0 bottom-0 left-0 w-64 sm:w-72 bg-white border-r border-slate-200/80 z-50 flex flex-col justify-between -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
  <div class="flex flex-col flex-1 overflow-y-auto">
    
    <!-- Brand Header -->
    <div class="h-16 px-5 border-b border-slate-100 flex items-center justify-between">
      <a href="{{ $role === 'admin' ? route('dashboard.admin') : ($role === 'kps' ? route('dashboard.kps') : ($role === 'industri' || $role === 'dudi' ? route('dashboard.industri') : ($role === 'dosen' ? route('dashboard.dosen') : route('dashboard.mahasiswa')))) }}" class="flex items-center gap-3 group">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#009688] flex items-center justify-center text-white shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-[20px]">
            {{ $role === 'admin' ? 'admin_panel_settings' : ($role === 'kps' ? 'gavel' : ($role === 'industri' || $role === 'dudi' ? 'domain' : ($role === 'dosen' ? 'supervisor_account' : 'school'))) }}
          </span>
        </div>
        <div class="flex flex-col min-w-0">
          <div class="flex items-center gap-1.5">
            <span class="font-heading font-extrabold text-sm tracking-tight text-slate-900">SIM-PKL</span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-[#0056B3] tracking-wide uppercase">
              {{ $role === 'admin' ? 'ADMIN' : ($role === 'kps' ? 'KPS' : ($role === 'industri' || $role === 'dudi' ? 'DUDI' : ($role === 'dosen' ? 'DOSEN' : 'MHS'))) }}
            </span>
          </div>
          <span class="text-[10px] text-slate-400 font-medium truncate">JTIK Politeknik Negeri Jakarta</span>
        </div>
      </a>
      <button onclick="toggleSidebar()" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 lg:hidden" aria-label="Tutup menu">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>

    <!-- Navigation Links -->
    <div class="px-3 py-4 flex-1">
      <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</div>
      <nav class="space-y-1">
        @foreach($navItems as $item)
          @php
            $isSelected = ($active === $item['id']);
            $isLocked = !empty($item['locked']);
          @endphp

          @if($isLocked)
            <div title="Selesaikan tahapan sebelumnya di Dashboard untuk membuka menu ini"
                 class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm text-slate-400 bg-slate-50/50 cursor-not-allowed select-none opacity-65">
              <span class="material-symbols-outlined text-[19px] text-slate-300">
                {{ $item['icon'] }}
              </span>
              <span class="truncate flex-1">{{ $item['label'] }}</span>
              <span class="material-symbols-outlined text-[15px] text-slate-400">lock</span>
            </div>
          @else
            <a href="{{ $item['route'] }}" 
               data-nav-item="{{ $item['id'] }}"
               @if(!empty($item['onclick'])) onclick="{!! $item['onclick'] !!}" @endif
               class="sidebar-nav-link group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all relative
                      {{ $isSelected 
                         ? 'bg-[#0056B3] text-white font-semibold shadow-sm' 
                         : 'text-slate-600 hover:text-[#0056B3] hover:bg-slate-50 font-medium' }}">
              
              <span class="material-symbols-outlined sidebar-nav-icon text-[19px] transition-colors
                           {{ $isSelected ? 'text-white material-symbols-fill' : 'text-slate-400 group-hover:text-[#0056B3]' }}">
                {{ $item['icon'] }}
              </span>

              <span class="truncate flex-1">{{ $item['label'] }}</span>

              @if(!empty($item['badge']))
                <span data-original-badge="{{ $item['badgeClass'] }}"
                      class="sidebar-nav-badge text-[10px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap
                             {{ $isSelected ? 'bg-white/20 text-white' : $item['badgeClass'] }}">
                  {{ $item['badge'] }}
                </span>
              @endif
            </a>
          @endif
        @endforeach
      </nav>
    </div>

  </div>

  <!-- User Account Footer Profile -->
  <div class="p-3.5 border-t border-slate-100 bg-slate-50/60">
    <div class="flex items-center justify-between gap-2">
      <div class="flex items-center gap-2.5 min-w-0">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
          {{ $initials }}
        </div>
        <div class="flex flex-col min-w-0">
          <span class="text-xs font-bold text-slate-800 truncate">{{ $userName }}</span>
          <span class="text-[10px] text-slate-400 truncate">{{ $userRoleLabel }}</span>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Keluar">
          <span class="material-symbols-outlined text-[18px]">logout</span>
        </button>
      </form>
    </div>
  </div>
</aside>
