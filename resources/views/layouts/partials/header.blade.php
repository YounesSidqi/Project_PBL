@php
  $user = Auth::user();
  $role = request()->query('role') ?? (request()->routeIs('*.dosen') ? 'dosen' : ($user->role ?? 'mahasiswa'));
  $userName = ($role === 'dosen' && ($user->role ?? '') !== 'dosen') ? 'Dr. Bambang Sutrisno, M.T.' : ($user->name ?? 'Pengguna');
  $initials = strtoupper(substr($userName, 0, 2));
  $identifier = ($role === 'dosen' && ($user->role ?? '') !== 'dosen') ? '198503152010121002' : ($user->identifier ?? '2107411002');
  $currentRouteName = Route::currentRouteName() ?? '';
  $isLogbookPage = str_contains($currentRouteName, 'logbook') || ($activePage ?? '') === 'logbook';
@endphp

<!-- Top Header Bar -->
<header class="h-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8">
  
  <!-- Left: Mobile Toggle & Breadcrumbs -->
  <div class="flex items-center gap-3 sm:gap-4">
    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Buka menu navigasi">
      <span class="material-symbols-outlined text-[24px]">menu</span>
    </button>

    <div class="flex items-center gap-2 text-xs sm:text-sm">
      <span class="text-slate-500 hidden sm:inline">{{ $breadcrumbParent ?? ($role === 'dosen' ? 'Portal Dosen TIK' : 'Portal Magang TIK') }}</span>
      <span class="material-symbols-outlined text-slate-300 text-[16px] hidden sm:inline">chevron_right</span>
      <span class="font-semibold text-slate-800 font-heading">{{ $pageTitle ?? 'Monitoring PKL' }}</span>
    </div>

    <span class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 bg-teal-50 text-teal-800 border border-teal-200/70 rounded-full text-xs font-semibold">
      <span class="w-1.5 h-1.5 rounded-full bg-[#009688] animate-pulse"></span>
      <span>Semester Genap 2025/2026</span>
    </span>
  </div>

  <!-- Right: Role Switcher, Notification & Profile -->
  <div class="flex items-center gap-2 sm:gap-4">
    
    <!-- Quick Role Switcher -->
    <div class="flex items-center bg-slate-100/80 hover:bg-slate-100 rounded-xl px-3 py-1.5 border border-slate-200/60 text-xs font-medium text-slate-700">
      <span class="material-symbols-outlined text-slate-400 text-[16px] mr-1.5">switch_account</span>
      <span class="text-slate-500 mr-1.5 hidden sm:inline">Peran:</span>
      <select id="header-role-switcher" 
              onchange="handleRoleSwitch(this.value)" 
              class="bg-transparent font-semibold text-slate-800 focus:outline-none cursor-pointer text-xs">
        <option value="mahasiswa" {{ $role === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
        <option value="dosen" {{ $role === 'dosen' ? 'selected' : '' }}>Dosen Pembimbing</option>
        <option value="dudi">Supervisor DUDI</option>
        <option value="admin">Panitia PKL</option>
      </select>
    </div>

    <!-- Notification Bell -->
    <div class="relative">
      <button type="button" 
              onclick="showToast('Pusat notifikasi: Semua verifikasi terbaru telah disinkronkan.')" 
              class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors" 
              aria-label="Notifikasi">
        <span class="material-symbols-outlined text-[22px]">notifications</span>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
      </button>
    </div>

    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

    <!-- User Profile Pill -->
    <div class="flex items-center gap-2.5 pl-1">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm">
        {{ $initials }}
      </div>
      <div class="hidden sm:flex flex-col text-left">
        <span class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[140px]">{{ $userName }}</span>
        <span class="text-[10px] text-slate-500 font-mono leading-tight">
          {{ $role === 'dosen' ? 'NIP: ' . $identifier : 'NIM: ' . $identifier }}
        </span>
      </div>
    </div>

  </div>

</header>

<script>
  function handleRoleSwitch(newRole) {
    const isLogbook = {{ $isLogbookPage ? 'true' : 'false' }};
    if (newRole === 'dosen') {
      window.location.href = isLogbook ? '{{ route("logbook.dosen") }}' : '{{ route("dashboard.dosen") }}';
    } else if (newRole === 'mahasiswa') {
      window.location.href = isLogbook ? '{{ route("logbook.mahasiswa") }}' : '{{ route("dashboard.mahasiswa") }}';
    } else {
      if (typeof showToast === 'function') {
        showToast('Akses peran ' + newRole + ' saat ini dalam mode simulasi terintegrasi.');
      }
    }
  }
</script>
