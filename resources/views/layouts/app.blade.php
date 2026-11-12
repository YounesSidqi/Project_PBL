<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'SIM-PKL' }} - JTIK Politeknik Negeri Jakarta</title>
  <meta name="description" content="Sistem Informasi Manajemen Praktik Kerja Lapangan Jurusan TIK Politeknik Negeri Jakarta">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: {
              blue: '#0056B3',
              tealDark: '#02796B',
              teal: '#009688',
              white: '#FFFFFF',
            },
            primary: '#0056B3',
            secondary: '#02796B',
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
          },
          boxShadow: {
            'card': '0 2px 12px -2px rgba(15, 23, 42, 0.04), 0 1px 4px -1px rgba(15, 23, 42, 0.02)',
            'card-hover': '0 8px 24px -4px rgba(0, 86, 179, 0.08), 0 2px 8px -2px rgba(15, 23, 42, 0.04)',
            'nav-active': '0 4px 12px rgba(0, 86, 179, 0.2)',
          }
        }
      }
    }
  </script>

  <style>
    * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      vertical-align: middle;
    }
    .material-symbols-fill {
      font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24 !important;
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    @keyframes scaleUp {
      from { transform: scale(0.97); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .animate-scale-up { animation: scaleUp 0.16s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
  </style>
  @stack('styles')
</head>
<body class="h-full flex flex-col font-sans text-slate-800 bg-[#F8FAFC]">

  <!-- Mobile Sidebar Backdrop -->
  <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

  <!-- Sidebar -->
  @include('layouts.partials.sidebar', ['activePage' => $activePage ?? ''])

  <!-- Main Content Wrapper -->
  <div class="lg:pl-72 flex-1 flex flex-col min-h-screen">
    
    <!-- Header -->
    @include('layouts.partials.header', [
      'pageTitle' => $pageTitle ?? 'Dashboard',
      'breadcrumbParent' => $breadcrumbParent ?? null,
      'activePage' => $activePage ?? ''
    ])

    <!-- Flash Notifications -->
    @if(session('success'))
      <div class="max-w-[1440px] w-full mx-auto px-4 sm:px-8 pt-5">
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm animate-scale-up">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
            <span class="material-symbols-outlined text-[16px]">close</span>
          </button>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="max-w-[1440px] w-full mx-auto px-4 sm:px-8 pt-5">
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm animate-scale-up">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
            <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
            <span class="material-symbols-outlined text-[16px]">close</span>
          </button>
        </div>
      </div>
    @endif

    <!-- Page Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1440px] w-full mx-auto space-y-5">
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 px-4 sm:px-8 border-t border-slate-200/80 bg-white text-xs text-slate-400">
      <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>© {{ date('Y') }} Jurusan Teknik Informatika dan Komputer — Politeknik Negeri Jakarta</p>
        <p class="font-medium text-slate-400">SIM-PKL JTIK</p>
      </div>
    </footer>

  </div>

  <script>
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

    function showToast(message, type = 'success') {
      const existingToast = document.getElementById('sim-toast');
      if (existingToast) existingToast.remove();

      const toast = document.createElement('div');
      toast.id = 'sim-toast';
      const icon = (type === 'error') ? 'error' : 'check_circle';
      const iconColor = (type === 'error') ? 'text-rose-400' : 'text-emerald-400';

      toast.className = 'fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-2.5 z-50 text-xs sm:text-sm transition-all duration-300 animate-scale-up';
      toast.innerHTML = `
        <span class="material-symbols-outlined ${iconColor} text-[18px]">${icon}</span>
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

  @stack('scripts')
</body>
</html>
