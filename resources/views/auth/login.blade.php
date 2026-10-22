<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - SIM-PKL Jurusan TIK Politeknik Negeri Jakarta</title>
  <meta name="description" content="Sistem Informasi Manajemen Praktik Kerja Lapangan Jurusan Teknik Informatika dan Komputer Politeknik Negeri Jakarta">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
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
              tealDark: '#02796B',
              teal: '#009688',
              white: '#FFFFFF',
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
          },
          boxShadow: {
            'card': '0 10px 40px -10px rgba(0, 86, 179, 0.08), 0 2px 10px rgba(0, 0, 0, 0.02)',
            'card-hover': '0 20px 50px -12px rgba(0, 86, 179, 0.12), 0 4px 16px rgba(0, 0, 0, 0.03)',
            'btn': '0 4px 14px 0 rgba(0, 86, 179, 0.25)',
            'btn-hover': '0 6px 20px rgba(0, 86, 179, 0.35)',
          }
        }
      }
    }
  </script>

  <style>
    /* Smooth anti-aliasing & custom scrollbar */
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
    /* Subtle dot grid pattern on white */
    .bg-grid-pattern {
      background-image: radial-gradient(rgba(0, 86, 179, 0.07) 1px, transparent 1px);
      background-size: 28px 28px;
    }
  </style>
</head>

<body class="bg-white min-h-screen flex flex-col font-sans text-gray-800 selection:bg-[#009688]/20 selection:text-[#0056B3] relative">

  <!-- Subtle Ambient Glows on Pure White Canvas -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[720px] h-[360px] bg-gradient-to-b from-[#0056B3]/6 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-[#009688]/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 -left-40 w-96 h-96 bg-[#02796B]/5 rounded-full blur-3xl"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-40"></div>
  </div>

  <!-- Header / Navbar -->
  <header class="w-full bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      
      <!-- Brand Logo & Title -->
      <a href="/" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-[#0056B3]/20 rounded-lg p-1">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0056B3] via-[#02796B] to-[#009688] p-[2px] shadow-sm transition-transform group-hover:scale-105">
          <div class="w-full h-full bg-white rounded-[10px] flex items-center justify-center overflow-hidden">
            <span class="material-symbols-outlined text-[#0056B3] text-[22px]">school</span>
          </div>
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-2">
            <span class="font-heading font-extrabold text-base tracking-tight text-[#0056B3]">SIM-PKL</span>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#009688]/10 text-[#02796B] tracking-wide">TIK PNJ</span>
          </div>
          <span class="text-[11px] text-gray-500 font-medium">Politeknik Negeri Jakarta</span>
        </div>
      </a>

      <!-- Navigation & Status Links -->
      <nav class="flex items-center gap-2 sm:gap-6">
        <a href="#" class="hidden md:flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-[#0056B3] transition-colors">
          <span class="material-symbols-outlined text-[17px] text-[#009688]">support_agent</span>
          Layanan Bantuan
        </a>
        <a href="#" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-[#0056B3] transition-colors">
          <span class="material-symbols-outlined text-[17px] text-[#02796B]">verified</span>
          Akreditasi ASIIN
        </a>
        <div class="h-4 w-[1px] bg-gray-200 hidden sm:block"></div>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
          <span class="w-2 h-2 rounded-full bg-[#009688] animate-pulse"></span>
          <span class="hidden sm:inline">Sistem Aktif</span>
          <span class="sm:hidden">Online</span>
        </span>
      </nav>

    </div>
  </header>

  <!-- Main Content: Centered Login Form -->
  <main class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 w-full z-10">
    <div class="w-full max-w-[460px] mx-auto">
      
      <!-- Card Container -->
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-200/90 shadow-card hover:shadow-card-hover transition-all duration-300 relative overflow-hidden">
        
        <!-- Top Gradient Accent Strip using #0056B3, #02796B, #009688 -->
        <div class="h-1.5 w-full bg-gradient-to-r from-[#0056B3] via-[#02796B] to-[#009688]"></div>

        <div class="p-5 sm:p-7">

          <!-- Card Header & Badges -->
          <div class="text-center mb-5">
            <h1 class="font-heading text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Masuk ke SIM-PKL</h1>
            <p class="text-xs text-gray-500 mt-1">Sistem Informasi Manajemen Praktik Kerja Lapangan Jurusan TIK</p>
          </div>

          <!-- Flash Messages -->
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
          <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-start gap-2">
            <span class="material-symbols-outlined text-[18px] text-red-600 shrink-0 mt-0.5">error</span>
            <div>
              @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Role Selector Tabs -->
          <div class="mb-4">
            <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5 text-center sm:text-left">
              Pilih Peran Akun:
            </div>
            <div class="p-1 rounded-xl bg-gray-100/90 border border-gray-200/70 grid grid-cols-4 gap-1" id="roleTabs">
              <button 
                type="button"
                data-role="mahasiswa" 
                onclick="selectRole('mahasiswa', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all duration-200 {{ old('role', 'mahasiswa') === 'mahasiswa' ? 'bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/70' }}"
              >
                Mahasiswa
              </button>
              <button 
                type="button"
                data-role="dosen" 
                onclick="selectRole('dosen', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all duration-200 {{ old('role') === 'dosen' ? 'bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/70' }}"
              >
                Dosen
              </button>
              <button 
                type="button"
                data-role="dudi" 
                onclick="selectRole('dudi', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all duration-200 {{ old('role') === 'dudi' ? 'bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/70' }}"
              >
                DUDI
              </button>
              <button 
                type="button"
                data-role="admin" 
                onclick="selectRole('admin', this)"
                class="role-btn py-1.5 px-1 rounded-lg text-center font-heading text-xs font-semibold transition-all duration-200 {{ old('role') === 'admin' ? 'bg-gradient-to-r from-[#0056B3] to-[#02796B] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/70' }}"
              >
                KPS / Admin
              </button>
            </div>
          </div>

          <!-- Login Form -->
          <form action="{{ route('login.post') }}" method="POST" class="space-y-3.5" id="loginForm">
            @csrf

            <!-- Hidden Role Input -->
            <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'mahasiswa') }}">
            
            <!-- Identifier Field -->
            <div class="space-y-1">
              <div class="flex items-center justify-between">
                <label for="identifier" id="identifierLabel" class="text-xs font-semibold text-gray-700">
                  NIM atau Email Kampus PNJ
                </label>
                <span class="text-[11px] text-[#02796B] font-medium" id="roleHint">Mahasiswa Aktif</span>
              </div>
              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[19px] pointer-events-none" id="identifierIcon">
                  badge
                </span>
                <input 
                  type="text" 
                  name="identifier"
                  id="identifier" 
                  value="{{ old('identifier') }}"
                  required
                  placeholder="cth. 2107411002@stu.pnj.ac.id" 
                  class="w-full h-10 pl-10 pr-4 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder:text-gray-400 text-xs sm:text-sm focus:outline-none focus:border-[#0056B3] focus:ring-4 focus:ring-[#0056B3]/10 transition-all shadow-sm"
                >
              </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-1">
              <div class="flex items-center justify-between">
                <label for="password" class="text-xs font-semibold text-gray-700">
                  Kata Sandi
                </label>
                <a href="#" class="text-xs font-semibold text-[#0056B3] hover:text-[#009688] hover:underline transition-colors">
                  Lupa Kata Sandi?
                </a>
              </div>
              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[19px] pointer-events-none">
                  lock
                </span>
                <input 
                  type="password" 
                  name="password"
                  id="password" 
                  required
                  placeholder="Masukkan kata sandi terdaftar" 
                  class="w-full h-10 pl-10 pr-10 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder:text-gray-400 text-xs sm:text-sm focus:outline-none focus:border-[#0056B3] focus:ring-4 focus:ring-[#0056B3]/10 transition-all shadow-sm"
                >
                <button 
                  type="button" 
                  onclick="togglePasswordVisibility()" 
                  class="absolute right-3 text-gray-400 hover:text-[#0056B3] p-1 rounded-lg focus:outline-none transition-colors"
                  title="Lihat / Sembunyikan Kata Sandi"
                >
                  <span class="material-symbols-outlined text-[19px]" id="pwdIcon">visibility</span>
                </button>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              class="w-full h-10 sm:h-11 rounded-xl bg-gradient-to-r from-[#0056B3] via-[#02796B] to-[#009688] hover:opacity-95 active:scale-[0.99] text-white font-heading font-semibold text-xs sm:text-sm shadow-btn hover:shadow-btn-hover flex items-center justify-center gap-2 transition-all cursor-pointer mt-1"
            >
              <span>Login</span>
            </button>
          </form>

          <!-- Info Notice Box -->
          <div class="mt-4 p-3 rounded-xl bg-[#009688]/5 border border-[#009688]/20 flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[#02796B] text-[18px] shrink-0 mt-0.5">info</span>
            <p class="text-[11px] text-gray-600 leading-relaxed">
              Seluruh akun didaftarkan secara terpusat oleh <span class="font-semibold text-gray-800">Admin Jurusan TIK PNJ</span>. Jika akun Anda belum terdaftar atau mengalami kendala akses, silakan hubungi kontak panitia di bawah.
            </p>
          </div>

          <!-- Helpdesk Contacts -->
          <div class="mt-4 pt-3.5 border-t border-gray-100 text-center">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pusat Bantuan Teknis</div>
            <p class="text-[11px] text-gray-600">
              WhatsApp: <a class="font-semibold text-[#0056B3] hover:text-[#009688] hover:underline" href="https://wa.me/628129090765" target="_blank">0812-9090-PNJ</a>
              <span class="mx-1 text-gray-300">•</span>
              Email: <a class="font-semibold text-[#0056B3] hover:text-[#009688] hover:underline" href="mailto:pkl.tik@pnj.ac.id">pkl.tik@pnj.ac.id</a>
            </p>
          </div>

        </div>

      </div>

      <!-- Quick Tips / System Announcement below Card -->
      <div class="mt-3 text-center">
        <p class="text-[11px] text-gray-400 flex items-center justify-center gap-1.5">
          <span class="material-symbols-outlined text-[14px] text-[#009688]">verified_user</span>
          Sistem Informasi Resmi Jurusan Teknik Informatika dan Komputer PNJ
        </p>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="w-full bg-white border-t border-gray-100 py-4 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-3 text-center md:text-left">
      
      <!-- Accreditation & Copyright -->
      <div class="flex flex-col gap-1.5">
        <div class="flex items-center justify-center md:justify-start gap-2">
          <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#0056B3]/10 text-[#0056B3] border border-[#0056B3]/20">
            PNJ Unggul
          </span>
          <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#009688]/10 text-[#02796B] border border-[#009688]/20">
            ASIIN Accredited
          </span>
        </div>
        <p class="text-xs text-gray-500">
          Sistem Informasi Manajemen Praktik Kerja Lapangan • Jurusan Teknik Informatika dan Komputer
        </p>
        <p class="text-[11px] text-gray-400">
          &copy; {{ date('Y') }} Politeknik Negeri Jakarta. Hak Cipta Dilindungi Undang-Undang.
        </p>
      </div>

      <!-- Footer Quick Links -->
      <div class="flex items-center gap-3 text-xs text-gray-500">
        <a href="#" class="hover:text-[#0056B3] transition-colors">Kebijakan Privasi</a>
        <span class="text-gray-300">•</span>
        <a href="#" class="hover:text-[#0056B3] transition-colors">Panduan Pengguna</a>
        <span class="text-gray-300">•</span>
        <a href="#" class="hover:text-[#0056B3] transition-colors">Kontak Jurusan</a>
      </div>

    </div>
  </footer>

  <!-- Interactive JavaScript Logic -->
  <script>
    function selectRole(role, element) {
      // Reset all role buttons to inactive state
      const buttons = document.querySelectorAll('.role-btn');
      buttons.forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-[#0056B3]', 'to-[#02796B]', 'text-white', 'shadow-sm');
        btn.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/70');
      });

      // Apply active gradient state to selected button
      element.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/70');
      element.classList.add('bg-gradient-to-r', 'from-[#0056B3]', 'to-[#02796B]', 'text-white', 'shadow-sm');

      // Update hidden input value for form submission
      document.getElementById('selectedRole').value = role;

      // Update Form Labels, Icons, and Placeholders dynamically
      const label = document.getElementById('identifierLabel');
      const input = document.getElementById('identifier');
      const hint = document.getElementById('roleHint');
      const icon = document.getElementById('identifierIcon');

      if (role === 'mahasiswa') {
        label.innerText = 'NIM atau Email Kampus PNJ';
        hint.innerText = 'Mahasiswa Aktif';
        input.placeholder = 'cth. 2107411002@stu.pnj.ac.id';
        icon.innerText = 'badge';
      } else if (role === 'dosen') {
        label.innerText = 'Nomor Induk Pegawai (NIP / NUPTK)';
        hint.innerText = 'Dosen Pembimbing';
        input.placeholder = 'cth. 198503152010121002';
        icon.innerText = 'supervisor_account';
      } else if (role === 'dudi') {
        label.innerText = 'Email Resmi Perusahaan / NRK Mentor';
        hint.innerText = 'Mitra Industri / DUDI';
        input.placeholder = 'cth. mentor@mitra-industri.co.id';
        icon.innerText = 'apartment';
      } else if (role === 'admin') {
        label.innerText = 'ID Koordinator / KPS Jurusan TIK';
        hint.innerText = 'Pengelola PKL';
        input.placeholder = 'cth. admin.tik@pnj.ac.id';
        icon.innerText = 'admin_panel_settings';
      }
      
      // Auto-focus input for rapid keyboard navigation
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

    function handleLoginSubmit() {
      const id = document.getElementById('identifier').value;
      if (id) {
        alert('Memproses verifikasi kredensial untuk ' + id + '.\nMengarahkan ke Dashboard SIM-PKL TIK PNJ...');
      }
    }

    function handleGoogleLogin() {
      alert('Mengarahkan ke Layanan Autentikasi Google Workspace PNJ (@stu.pnj.ac.id)...');
    }
  </script>

</body>
</html>