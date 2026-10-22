<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - SIM-PKL Jurusan TIK Politeknik Negeri Jakarta</title>

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
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
              tealDark: '#02796B',
              teal: '#009688',
              white: '#FFFFFF',
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>
<body class="bg-white min-h-screen flex flex-col font-sans text-gray-800 selection:bg-[#009688]/20 selection:text-[#0056B3]">

  <!-- Navbar -->
  <header class="w-full bg-white border-b border-gray-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      
      <!-- Logo -->
      <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0056B3] via-[#02796B] to-[#009688] p-[2px]">
          <div class="w-full h-full bg-white rounded-[10px] flex items-center justify-center">
            <span class="material-symbols-outlined text-[#0056B3] text-[22px]">school</span>
          </div>
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-2">
            <span class="font-heading font-extrabold text-base tracking-tight text-[#0056B3]">SIM-PKL</span>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#009688]/10 text-[#02796B] uppercase">TIK PNJ</span>
          </div>
          <span class="text-[11px] text-gray-400 font-medium">Politeknik Negeri Jakarta</span>
        </div>
      </a>

      <!-- User Menu & Logout -->
      <div class="flex items-center gap-4">
        <div class="hidden sm:flex flex-col text-right">
          <span class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</span>
          <span class="text-[10px] text-[#02796B] font-medium uppercase tracking-wider">{{ Auth::user()->role }}</span>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="inline">
          @csrf
          <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 text-xs font-semibold transition-all">
            <span class="material-symbols-outlined text-[16px]">logout</span>
            <span>Keluar</span>
          </button>
        </form>
      </div>

    </div>
  </header>

  <!-- Flash Message -->
  @if (session('success'))
  <div class="max-w-4xl mx-auto px-4 mt-6 w-full">
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm">
      <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
      <span>{{ session('success') }}</span>
    </div>
  </div>
  @endif

  <!-- Main Content -->
  <main class="flex-1 max-w-4xl mx-auto px-4 py-8 w-full">
    
    <!-- Welcome Card -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm p-6 sm:p-8 relative overflow-hidden mb-6">
      <div class="h-1.5 w-full bg-gradient-to-r from-[#0056B3] via-[#02796B] to-[#009688] absolute top-0 left-0 right-0"></div>

      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-2">
        <div>
          <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#009688]/10 text-[#02796B] border border-[#009688]/20 uppercase">
            {{ ucfirst(Auth::user()->role) }} Aktif
          </span>
          <h1 class="font-heading text-2xl font-bold text-gray-900 mt-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
          <p class="text-xs sm:text-sm text-gray-500 mt-1">Anda telah berhasil masuk ke Sistem Informasi PKL Jurusan TIK PNJ.</p>
        </div>

        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0056B3] to-[#02796B] flex items-center justify-center text-white shrink-0 shadow-md">
          <span class="material-symbols-outlined text-[32px]">account_circle</span>
        </div>
      </div>

      <!-- Info Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100 text-xs">
        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100">
          <div class="text-gray-400 font-medium mb-1">NIM / NIP / ID</div>
          <div class="font-semibold text-gray-800 text-sm">{{ Auth::user()->identifier ?? '-' }}</div>
        </div>
        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100">
          <div class="text-gray-400 font-medium mb-1">Email Resmi</div>
          <div class="font-semibold text-gray-800 text-sm">{{ Auth::user()->email }}</div>
        </div>
        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100">
          <div class="text-gray-400 font-medium mb-1">Program Studi / Instansi</div>
          <div class="font-semibold text-gray-800 text-sm">{{ Auth::user()->prodi ?? 'Jurusan TIK PNJ' }}</div>
        </div>
      </div>
    </div>

    <!-- Quick Status PKL -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="p-5 rounded-2xl border border-gray-200/90 bg-white">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-[#0056B3]/10 text-[#0056B3] flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">description</span>
          </div>
          <div>
            <div class="text-xs font-semibold text-gray-800">Status Proposal PKL</div>
            <div class="text-[11px] text-gray-400">Tahun Akademik 2024/2025</div>
          </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
          <span class="text-gray-500">Kesiapan Berkas:</span>
          <span class="font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Terkoneksi Database</span>
        </div>
      </div>

      <div class="p-5 rounded-2xl border border-gray-200/90 bg-white">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-[#009688]/10 text-[#02796B] flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">database</span>
          </div>
          <div>
            <div class="text-xs font-semibold text-gray-800">Koneksi Database Aktif</div>
            <div class="text-[11px] text-gray-400">MySQL Server • Database: simpkl</div>
          </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
          <span class="text-gray-500">Status Database:</span>
          <span class="font-semibold text-[#0056B3] bg-blue-50 px-2 py-0.5 rounded">Connected (simpkl)</span>
        </div>
      </div>
    </div>

  </main>

  <!-- Footer -->
  <footer class="w-full bg-white border-t border-gray-100 py-4 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-400">
      &copy; {{ date('Y') }} SIM-PKL Politeknik Negeri Jakarta • Jurusan Teknik Informatika dan Komputer
    </div>
  </footer>

</body>
</html>
