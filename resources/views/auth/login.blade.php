<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - SIM-PKL JTIK Politeknik Negeri Jakarta</title>
  <meta name="description" content="Sistem Informasi Manajemen Praktik Kerja Lapangan Jurusan Teknik Informatika dan Komputer Politeknik Negeri Jakarta">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

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
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
          },
          boxShadow: {
            'card': '0 10px 35px -10px rgba(15, 23, 42, 0.08), 0 2px 8px rgba(15, 23, 42, 0.03)',
            'btn': '0 4px 12px 0 rgba(0, 86, 179, 0.22)',
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
  </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-slate-800 selection:bg-[#009688]/20 selection:text-[#0056B3]">

  <!-- Header -->
  <header class="w-full bg-white border-b border-slate-200/80">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
      <a href="/" class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0056B3] to-[#009688] flex items-center justify-center text-white shadow-sm">
          <span class="material-symbols-outlined text-[20px]">school</span>
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-1.5">
            <span class="font-heading font-extrabold text-sm tracking-tight text-slate-900">SIM-PKL</span>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-[#0056B3]">JTIK PNJ</span>
          </div>
          <span class="text-[11px] text-slate-400 font-medium">Politeknik Negeri Jakarta</span>
        </div>
      </a>

      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        <span>TA 2025/2026</span>
      </span>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 flex items-center justify-center px-4 py-8 w-full">
    <div class="w-full max-w-[420px] mx-auto">
      
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-card overflow-hidden">
        <div class="h-1.5 w-full bg-gradient-to-r from-[#0056B3] via-[#02796B] to-[#009688]"></div>

        <div class="p-6 sm:p-7">
          <div class="text-center mb-5">
            <h1 class="font-heading text-xl font-bold text-slate-900 tracking-tight">Masuk ke SIM-PKL</h1>
            <p class="text-xs text-slate-500 mt-1">Portal Praktik Kerja Lapangan Jurusan TIK</p>
          </div>

          @if (session('success'))
          <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
          </div>
          @endif

          @if (session('info'))
          <div class="mb-4 p-3 rounded-xl bg-blue-50 border border-blue-200 text-[#0056B3] text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">info</span>
            <span>{{ session('info') }}</span>
          </div>
          @endif

          @if ($errors->any())
          <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2">
            <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0 mt-0.5">error</span>
            <div>
              @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Role Selector Tabs -->
          <div class="mb-4">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
              Peran Akun
            </div>
            <div class="p-1 rounded-xl bg-slate-100 border border-slate-200/70 grid grid-cols-4 gap-1" id="roleTabs">
              <button 
                type="button"
                data-role="mahasiswa" 
                onclick="selectRole('mahasiswa', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all {{ old('role', 'mahasiswa') === 'mahasiswa' ? 'bg-[#0056B3] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70' }}"
              >
                Mahasiswa
              </button>
              <button 
                type="button"
                data-role="dosen" 
                onclick="selectRole('dosen', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all {{ old('role') === 'dosen' ? 'bg-[#0056B3] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70' }}"
              >
                Dosen
              </button>
              <button 
                type="button"
                data-role="dudi" 
                onclick="selectRole('dudi', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all {{ old('role') === 'dudi' ? 'bg-[#0056B3] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70' }}"
              >
                Mitra DUDI
              </button>
              <button 
                type="button"
                data-role="admin" 
                onclick="selectRole('admin', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all {{ old('role') === 'admin' ? 'bg-[#0056B3] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70' }}"
              >
                KPS / Admin
              </button>
            </div>
          </div>

          <!-- Login Form -->
          <form action="{{ route('login.post') }}" method="POST" class="space-y-3.5" id="loginForm">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'mahasiswa') }}">
            
            <!-- Identifier Field -->
            <div class="space-y-1">
              <div class="flex items-center justify-between">
                <label for="identifier" id="identifierLabel" class="text-xs font-semibold text-slate-700">
                  NIM / Email Kampus
                </label>
                <span class="text-[11px] text-[#02796B] font-medium" id="roleHint">Mahasiswa</span>
              </div>
              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 text-[18px] pointer-events-none" id="identifierIcon">
                  badge
                </span>
                <input 
                  type="text" 
                  name="identifier"
                  id="identifier" 
                  value="{{ old('identifier') }}"
                  required
                  placeholder="2107411002 / email@stu.pnj.ac.id" 
                  class="w-full h-10 pl-10 pr-4 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none focus:border-[#0056B3] focus:ring-2 focus:ring-[#0056B3]/15 transition-all"
                >
              </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-1">
              <label for="password" class="text-xs font-semibold text-slate-700 block">
                Kata Sandi
              </label>
              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 text-[18px] pointer-events-none">
                  lock
                </span>
                <input 
                  type="password" 
                  name="password"
                  id="password" 
                  required
                  placeholder="Masukkan kata sandi" 
                  class="w-full h-10 pl-10 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none focus:border-[#0056B3] focus:ring-2 focus:ring-[#0056B3]/15 transition-all"
                >
                <button 
                  type="button" 
                  onclick="togglePasswordVisibility()" 
                  class="absolute right-3 text-slate-400 hover:text-[#0056B3] p-1 rounded-lg focus:outline-none"
                  title="Lihat / Sembunyikan Kata Sandi"
                >
                  <span class="material-symbols-outlined text-[18px]" id="pwdIcon">visibility</span>
                </button>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              class="w-full h-10 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-heading font-semibold text-xs sm:text-sm shadow-btn flex items-center justify-center gap-2 transition-all cursor-pointer pt-0.5"
            >
              <span>Masuk</span>
            </button>
          </form>

          <!-- Compact Footer Info -->
          <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
            <span>Bantuan Admin JTIK</span>
            <a class="font-semibold text-[#0056B3] hover:underline" href="mailto:pkl.tik@pnj.ac.id">pkl.tik@pnj.ac.id</a>
          </div>
        </div>
      </div>

    </div>
  </main>

  <footer class="w-full py-4 text-center text-xs text-slate-400 border-t border-slate-200/60 bg-white">
    &copy; {{ date('Y') }} Jurusan Teknik Informatika dan Komputer — Politeknik Negeri Jakarta
  </footer>

  <script>
    function selectRole(role, element) {
      const buttons = document.querySelectorAll('.role-btn');
      buttons.forEach(btn => {
        btn.classList.remove('bg-[#0056B3]', 'text-white', 'shadow-sm');
        btn.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/70');
      });

      element.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/70');
      element.classList.add('bg-[#0056B3]', 'text-white', 'shadow-sm');

      document.getElementById('selectedRole').value = role;

      const label = document.getElementById('identifierLabel');
      const input = document.getElementById('identifier');
      const hint = document.getElementById('roleHint');
      const icon = document.getElementById('identifierIcon');

      if (role === 'mahasiswa') {
        label.innerText = 'NIM / Email Kampus';
        hint.innerText = 'Mahasiswa';
        input.placeholder = '2107411002 / email@stu.pnj.ac.id';
        icon.innerText = 'badge';
      } else if (role === 'dosen') {
        label.innerText = 'NIP / Email Dosen';
        hint.innerText = 'Dosen Pembimbing';
        input.placeholder = '198503152010121002';
        icon.innerText = 'supervisor_account';
      } else if (role === 'dudi') {
        label.innerText = 'Email PIC / ID Mitra DUDI';
        hint.innerText = 'Mitra Industri';
        input.placeholder = 'pembimbing@mitra.co.id / DUDI-0001';
        icon.innerText = 'apartment';
      } else if (role === 'admin' || role === 'kps') {
        label.innerText = 'ID / Email KPS atau Admin';
        hint.innerText = 'KPS / Admin';
        input.placeholder = 'kps.ti@pnj.ac.id / admin.tik@pnj.ac.id';
        icon.innerText = 'admin_panel_settings';
      }
      
      input.focus();
    }

    function togglePasswordVisibility() {
      const pwd = document.getElementById('password');
      const icon = document.getElementById('pwdIcon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.innerText = 'visibility_off';
      } else {
        pwd.type = 'password';
        icon.innerText = 'visibility';
      }
    }
  </script>

</body>
</html>
