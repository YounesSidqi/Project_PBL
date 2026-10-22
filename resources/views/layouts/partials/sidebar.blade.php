@php
  $user = Auth::user();
  $role = $role ?? (request()->query('role') ?? (request()->routeIs('*dosen*') ? 'dosen' : ($user->role ?? 'mahasiswa')));
  
  $tab = request()->query('tab');
  if ($role === 'dosen' && $tab === 'f1') {
    $active = 'validasi_f1';
  } elseif (!empty($activePage)) {
    $active = $activePage;
  } elseif (request()->routeIs('form.f2*')) {
    $active = 'f2';
  } elseif (request()->routeIs('form.f4*')) {
    $active = 'f4';
  } else {
    $active = request()->routeIs('*logbook*') ? 'logbook' : (request()->routeIs('*dashboard*') ? 'dashboard' : '');
  }
  
  $userName = ($role === 'dosen' && ($user->role ?? '') !== 'dosen') ? 'Dr. Bambang Sutrisno, M.T.' : ($user->name ?? 'Pengguna SIM-PKL');
  $userRoleLabel = ($role === 'dosen') ? 'Dosen Pembimbing' : 'Mahasiswa (D4 TI)';
  
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

  // Mahasiswa Menu Items (Consistent with SIM-PKL standard)
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
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'penilaian_industri',
      'label' => 'Penilaian Industri (F5)',
      'icon' => 'domain_verification',
      'route' => '#',
      'onclick' => "if(typeof showToast === 'function') { showToast('Modul Penilaian Industri (F5) akan terbuka setelah masa magang.'); } return false;",
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'penilaian_sidang',
      'label' => 'Penilaian Dosen & Sidang (F6-F7)',
      'icon' => 'assignment_turned_in',
      'route' => '#',
      'onclick' => "if(typeof showToast === 'function') { showToast('Pendaftaran Sidang (F7) memerlukan minimal 4 bimbingan F4.'); } return false;",
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'repositori',
      'label' => 'Repositori Laporan (Bab VII)',
      'icon' => 'folder_zip',
      'route' => '#',
      'onclick' => "if(typeof showToast === 'function') { showToast('Unggah naskah laporan akhir terbuka menjelang batas sidang.'); } return false;",
      'badge' => null,
      'badgeClass' => '',
    ],
  ];

  // Dosen Menu Items (Consistent order & labels)
  $dosenId = ($user && $user->role === 'dosen') ? $user->id : (\App\Models\User::where('role', 'dosen')->value('id') ?? 1);
  $studentCount = \App\Models\User::where('role', 'mahasiswa')->where('dosen_id', $dosenId)->count();
  $pendingCount = \App\Models\Logbook::where('dosen_id', $dosenId)
                    ->where('status', 'pending')
                    ->count();

  $dosenNav = [
    [
      'id' => 'dashboard',
      'label' => 'Mahasiswa Bimbingan',
      'icon' => 'groups',
      'route' => route('dashboard.dosen'),
      'onclick' => "updateSidebarActive('dashboard'); if(window.location.hash){ history.replaceState(null, null, window.location.pathname); } if(typeof filterStudents === 'function') { const sf = document.getElementById('status-filter'); if(sf) { sf.value='all'; filterStudents(); } }",
      'badge' => $studentCount . '/7',
      'badgeClass' => 'bg-white/20 text-white',
    ],
    [
      'id' => 'validasi_f1',
      'label' => 'Validasi Form F1',
      'icon' => 'assignment_turned_in',
      'route' => route('dashboard.dosen', ['tab' => 'f1']) . '#mahasiswa-section',
      'onclick' => "updateSidebarActive('validasi_f1'); if(typeof filterStudents === 'function') { const sf = document.getElementById('status-filter'); if(sf) { sf.value='f1'; filterStudents(); } }",
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'f2',
      'label' => 'Capaian Industri (F2)',
      'icon' => 'terminal',
      'route' => route('form.f2.dosen'),
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'logbook',
      'label' => 'Verifikasi Logbook (F3)',
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
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'sidang_f7',
      'label' => 'Penilaian Sidang (F6-F7)',
      'icon' => 'grade',
      'route' => '#',
      'onclick' => "if(typeof showToast === 'function') { showToast('Jadwal sidang PKL mahasiswa akan dirilis oleh Panitia Jurusan.'); } return false;",
      'badge' => null,
      'badgeClass' => '',
    ],
    [
      'id' => 'repositori_dosen',
      'label' => 'Repositori Laporan PKL',
      'icon' => 'folder_zip',
      'route' => '#',
      'onclick' => "if(typeof showToast === 'function') { showToast('Akses arsip repositori laporan PKL mahasiswa.'); } return false;",
      'badge' => null,
      'badgeClass' => '',
    ],
  ];

  $navItems = ($role === 'dosen') ? $dosenNav : $mahasiswaNav;
@endphp

<!-- Sidebar Navigation Component -->
<aside id="main-sidebar" class="fixed top-0 bottom-0 left-0 w-72 bg-white border-r border-slate-200/80 z-50 flex flex-col justify-between -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-sm">
  <div class="flex flex-col flex-1 overflow-y-auto">
    
    <!-- Brand Header -->
    <div class="h-20 px-6 border-b border-slate-100 flex items-center justify-between">
      <a href="{{ $role === 'dosen' ? route('dashboard.dosen') : route('dashboard.mahasiswa') }}" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0056B3] via-[#02796B] to-[#009688] p-[2px] shadow-sm transition-transform group-hover:scale-105 flex-shrink-0">
          <div class="w-full h-full bg-white rounded-[10px] flex items-center justify-center overflow-hidden">
            <span class="material-symbols-outlined text-[#0056B3] text-[22px]">{{ $role === 'dosen' ? 'supervisor_account' : 'school' }}</span>
          </div>
        </div>
        <div class="flex flex-col min-w-0">
          <div class="flex items-center gap-1.5">
            <span class="font-heading font-extrabold text-base tracking-tight text-[#0056B3]">SIM-PKL</span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $role === 'dosen' ? 'bg-[#02796B]/10 text-[#02796B]' : 'bg-[#009688]/10 text-[#02796B]' }} tracking-wide">
              {{ $role === 'dosen' ? 'DOSEN' : 'TIK' }}
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
            {{ $role === 'dosen' ? 'Portal Dosen' : 'Modul PKL' }}
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
      @if($role === 'dosen')
        <div class="bg-gradient-to-br from-slate-50 to-teal-50/50 border border-slate-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-[#02796B]">
            <span class="material-symbols-outlined text-[18px]">rule</span>
            <span class="text-xs font-bold font-heading">Beban Maksimal Bimbingan</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Maksimal <strong>7 mahasiswa</strong> bimbingan per dosen sesuai ketetapan Jurusan TIK PNJ 2026.
          </p>
        </div>
      @else
        <div class="bg-gradient-to-br from-slate-50 to-blue-50/40 border border-slate-200/80 p-3.5 rounded-xl">
          <div class="flex items-center gap-2 text-[#02796B]">
            <span class="material-symbols-outlined text-[18px]">verified</span>
            <span class="text-xs font-bold font-heading">Standar PKL Vokasi 2026</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
            Terintegrasi Jurusan TIK PNJ (Teknik Informatika, TMJ, TMD).
          </p>
        </div>
      @endif
    </div>

  </div>

  <!-- Quick User & Logout in Sidebar Footer -->
  <div class="p-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
    <div class="flex items-center gap-2.5 min-w-0">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
        {{ $initials }}
      </div>
      <div class="flex flex-col min-w-0">
        <span class="text-xs font-bold text-slate-800 truncate">{{ $userName }}</span>
        <span class="text-[11px] text-slate-500 capitalize truncate">{{ $userRoleLabel }}</span>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" title="Keluar dari akun" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
        <span class="material-symbols-outlined text-[19px]">logout</span>
      </button>
    </form>
  </div>
</aside>

<script>
  function updateSidebarActive(navId) {
    document.querySelectorAll('[data-nav-item]').forEach(el => {
      const isTarget = (el.getAttribute('data-nav-item') === navId);
      const icon = el.querySelector('.sidebar-nav-icon');
      const badge = el.querySelector('.sidebar-nav-badge');

      if (isTarget) {
        el.className = "sidebar-nav-link group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all relative bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white shadow-nav-active font-medium";
        if (icon) {
          icon.className = "material-symbols-outlined sidebar-nav-icon text-[20px] transition-colors text-white material-symbols-fill";
        }
        if (badge) {
          badge.className = "sidebar-nav-badge text-[10px] font-bold px-1.5 py-0.5 rounded-full whitespace-nowrap transition-colors bg-white/20 text-white";
        }
      } else {
        el.className = "sidebar-nav-link group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all relative text-slate-600 hover:text-[#0056B3] hover:bg-blue-50/60 font-medium";
        if (icon) {
          icon.className = "material-symbols-outlined sidebar-nav-icon text-[20px] transition-colors text-slate-400 group-hover:text-[#0056B3]";
        }
        if (badge) {
          const original = badge.getAttribute('data-original-badge') || '';
          badge.className = "sidebar-nav-badge text-[10px] font-bold px-1.5 py-0.5 rounded-full whitespace-nowrap transition-colors " + original;
        }
      }
    });
  }

  (function() {
    function syncHash() {
      const hash = window.location.hash;
      const urlParams = new URLSearchParams(window.location.search);
      if (window.location.pathname.includes('/dashboard/dosen')) {
        if (hash === '#mahasiswa-section' || urlParams.get('tab') === 'f1') {
          updateSidebarActive('validasi_f1');
        } else if (hash === '#agenda-section') {
          updateSidebarActive('agenda');
        } else if (!hash && !urlParams.get('tab')) {
          updateSidebarActive('dashboard');
        }
      }
    }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', syncHash);
    } else {
      syncHash();
    }
    window.addEventListener('hashchange', syncHash);
  })();
</script>
