@php
  $user = Auth::user();
  $role = request()->query('role') ?? (request()->routeIs('*.dosen') ? 'dosen' : ($user->role ?? 'mahasiswa'));
  $userName = $user->name ?? 'Pengguna';
  $initials = strtoupper(substr($userName, 0, 2));
  $identifier = $user->identifier ?? '-';

  $roleBadge = match ($role) {
    'admin' => 'Admin Jurusan',
    'kps' => 'KPS',
    'industri', 'dudi' => 'Mitra DUDI',
    'dosen' => 'Dosen Pembimbing',
    default => 'Mahasiswa',
  };
@endphp

<!-- Top Header Bar -->
<header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8">
  
  <!-- Left: Mobile Toggle & Breadcrumbs -->
  <div class="flex items-center gap-3">
    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Buka menu navigasi">
      <span class="material-symbols-outlined text-[22px]">menu</span>
    </button>

    <div class="flex items-center gap-2 text-xs sm:text-sm">
      <span class="text-slate-400 font-medium hidden sm:inline">{{ $breadcrumbParent ?? 'SIM-PKL JTIK' }}</span>
      <span class="material-symbols-outlined text-slate-300 text-[16px] hidden sm:inline">chevron_right</span>
      <span class="font-bold text-slate-800 font-heading">{{ $pageTitle ?? 'Dashboard' }}</span>
    </div>
  </div>

  <!-- Right: User Profile & Logout -->
  <div class="flex items-center gap-3">
    <span class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-semibold">
      <span class="w-1.5 h-1.5 rounded-full bg-[#009688]"></span>
      <span>TA 2025/2026</span>
    </span>

    <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>

    <div class="flex items-center gap-2.5">
      <div class="hidden sm:flex flex-col text-right">
        <span class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[180px]">{{ $userName }}</span>
        <span class="text-[10px] text-slate-500 font-mono leading-tight">{{ $identifier }} • {{ $roleBadge }}</span>
      </div>
      <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-xs shadow-sm">
        {{ $initials }}
      </div>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" title="Keluar" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
          <span class="material-symbols-outlined text-[19px]">logout</span>
        </button>
      </form>
    </div>
  </div>

</header>
