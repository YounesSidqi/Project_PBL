<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Pengantar PKL - {{ $pengajuan->mahasiswa->name }} ({{ $pengajuan->mahasiswa->identifier }})</title>
  <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      body { background: white !important; padding: 0 !important; }
      .no-print { display: none !important; }
      .print-shadow-none { box-shadow: none !important; border: none !important; }
    }
    .font-formal {
      font-family: 'Times New Roman', Times, serif;
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-900 font-formal p-4 sm:p-8 min-h-screen flex flex-col items-center">

  <!-- Print Control Bar -->
  <div class="no-print w-full max-w-4xl bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex items-center justify-between font-sans">
    <div class="flex items-center gap-3">
      <a href="{{ url()->previous() }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        &larr; Kembali
      </a>
      <span class="text-xs text-slate-500 font-medium">Legalisasi Digital: Surat Pengantar PKL JTIK PNJ 2026</span>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-5 py-2 bg-[#0056B3] hover:bg-blue-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        <span>Cetak / Simpan PDF</span>
      </button>
    </div>
  </div>

  <!-- Sheet Paper (A4 Style) -->
  <div class="w-full max-w-4xl bg-white p-8 sm:p-14 shadow-lg border border-slate-200 print-shadow-none text-slate-900">
    
    <!-- KOP SURAT RESMI PNJ JTIK -->
    <div class="border-b-4 border-double border-slate-900 pb-3 mb-6 text-center relative">
      <div class="flex items-center justify-between gap-4">
        <!-- Logo PNJ Emblem Placeholder / Icon -->
        <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center">
          <svg viewBox="0 0 100 100" class="w-16 h-16 text-[#0056B3]" fill="currentColor">
            <circle cx="50" cy="50" r="45" stroke="#0056B3" stroke-width="4" fill="none"/>
            <polygon points="50,15 80,75 20,75" fill="#0056B3" opacity="0.8"/>
            <circle cx="50" cy="55" r="14" fill="#02796B"/>
          </svg>
        </div>

        <div class="flex-1">
          <h3 class="text-sm sm:text-base font-bold tracking-wide uppercase">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h3>
          <h2 class="text-lg sm:text-xl font-bold tracking-wider uppercase text-[#0056B3]">POLITEKNIK NEGERI JAKARTA</h2>
          <h1 class="text-base sm:text-lg font-bold uppercase tracking-wider">JURUSAN TEKNIK INFORMATIKA DAN KOMPUTER</h1>
          <p class="text-xs text-slate-600 font-sans mt-0.5">
            Jl. Prof. Dr. G.A. Siwabessy, Kampus UI Depok 16425 • Telp: (021) 7270036, Fax: (021) 7270034<br>
            Laman: http://tik.pnj.ac.id • Pos-el: tik@pnj.ac.id
          </p>
        </div>

        <div class="w-20 h-20 flex-shrink-0"></div>
      </div>
    </div>

    <!-- Letter Metadata -->
    <div class="flex justify-between items-start text-xs sm:text-sm mb-6">
      <div>
        <table>
          <tr>
            <td class="pr-2 font-bold">Nomor</td>
            <td class="pr-2">:</td>
            <td class="font-mono font-bold">{{ $pengajuan->nomor_surat_pengantar ?? 'B/001/PL3.TIK/PKL/' . date('Y') }}</td>
          </tr>
          <tr>
            <td class="pr-2 font-bold">Lampiran</td>
            <td class="pr-2">:</td>
            <td>1 (Satu) Berkas Proposal &amp; Form F1</td>
          </tr>
          <tr>
            <td class="pr-2 font-bold">Perihal</td>
            <td class="pr-2">:</td>
            <td class="font-bold underline">Permohonan Izin Praktik Kerja Lapangan (PKL)</td>
          </tr>
        </table>
      </div>
      <div class="text-right">
        Depok, {{ $pengajuan->updated_at ? $pengajuan->updated_at->translatedFormat('d F Y') : date('d F Y') }}
      </div>
    </div>

    <!-- Recipient -->
    <div class="text-xs sm:text-sm mb-6">
      <p>Kepada Yth.</p>
      <p class="font-bold">{{ $pengajuan->industri->kontak_person ?? 'Pimpinan / HRD' }}</p>
      <p class="font-bold">{{ $pengajuan->nama_perusahaan_surat ?: ($pengajuan->industri->nama_perusahaan ?? 'Mitra Industri PKL') }}</p>
      <p class="text-slate-600">{{ $pengajuan->alamat_perusahaan_surat ?: ($pengajuan->industri->alamat ?? 'Di Tempat') }}</p>
    </div>

    <!-- Letter Content Body -->
    <div class="text-xs sm:text-sm leading-relaxed text-justify space-y-3 mb-8">
      <p>Dengan hormat,</p>
      <p>
        Sehubungan dengan kurikulum akademik program studi di Jurusan Teknik Informatika dan Komputer Politeknik Negeri Jakarta, setiap mahasiswa diwajibkan melaksanakan mata kuliah <strong>Praktik Kerja Lapangan (PKL)</strong> guna mengimplementasikan keilmuan teknologi informasi secara nyata pada industri dan dunia kerja.
      </p>
      <p>
        Bersama ini kami mengajukan permohonan izin agar mahasiswa berikut dapat melaksanakan kegiatan PKL pada perusahaan / institusi yang Bapak/Ibu pimpin:
      </p>

      <div class="my-4 px-6">
        <table class="w-full text-xs sm:text-sm">
          <tr>
            <td class="py-1 w-44 font-bold">Nama Mahasiswa</td>
            <td class="w-4">:</td>
            <td class="font-bold">{{ $pengajuan->mahasiswa->name }}</td>
          </tr>
          <tr>
            <td class="py-1 font-bold">Nomor Induk Mahasiswa (NIM)</td>
            <td>:</td>
            <td class="font-mono">{{ $pengajuan->mahasiswa->identifier }}</td>
          </tr>
          <tr>
            <td class="py-1 font-bold">Program Studi / Jenjang</td>
            <td>:</td>
            <td>{{ $pengajuan->mahasiswa->prodi ?? 'Teknik Informatika (D4)' }}</td>
          </tr>
          <tr>
            <td class="py-1 font-bold">Posisi Magang</td>
            <td>:</td>
            <td>{{ $pengajuan->posisi_magang ?: 'Software & Cloud Engineering Intern' }}</td>
          </tr>
          <tr>
            <td class="py-1 font-bold">Periode Pelaksanaan</td>
            <td>:</td>
            <td>
              @if($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
                {{ $pengajuan->tanggal_mulai->translatedFormat('d F Y') }} s/d {{ $pengajuan->tanggal_selesai->translatedFormat('d F Y') }} (5 Bulan)
              @else
                15 Januari 2026 s/d 15 Juli 2026 (Semester Genap 2025/2026)
              @endif
            </td>
          </tr>
        </table>
      </div>

      <p>
        Besar harapan kami agar mahasiswa tersebut diberikan kesempatan menimba pengalaman kerja di bawah bimbingan tenaga ahli di perusahaan Bapak/Ibu. Segala ketentuan kerahasiaan dan etika industri akan dipatuhi sepenuhnya.
      </p>
      <p>
        Demikian surat pengantar ini kami sampaikan. Atas perhatian, perkenan, dan kerjasama yang baik, kami ucapkan terima kasih.
      </p>
    </div>

    <!-- Official Validation Footer with Oval Checklist & Digital Stamp (PRD 8) -->
    <div class="mt-8 pt-4 flex justify-between items-end">
      <div class="text-[11px] text-slate-500 font-sans max-w-xs space-y-1">
        <div class="flex items-center gap-2">
          <!-- Visual Checklist Oval (PRD 8.2) -->
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 border-2 border-emerald-500 text-emerald-700 font-bold text-sm">
            ✓
          </span>
          <div>
            <div class="font-bold text-slate-800">Dokumen Sah Terverifikasi</div>
            <div class="text-[10px]">Tervalidasi secara digital SIM-PKL</div>
          </div>
        </div>
        <p class="font-mono text-[9px] text-slate-400">HASH: {{ hash('sha256', $pengajuan->id . $pengajuan->nomor_surat_pengantar . 'PNJ_JTIK_2026') }}</p>
      </div>

      <div class="text-center relative pr-4">
        <p class="text-xs sm:text-sm font-bold">Ketua Jurusan TIK PNJ,</p>
        
        <!-- Stempel Digital Resmi PNJ Otomatis (PRD 8.3) -->
        <div class="relative w-44 h-28 mx-auto my-1 flex items-center justify-center">
          <img src="{{ asset('images/stempel-pnj.svg') }}" alt="Stempel Digital PNJ" class="absolute w-28 h-28 opacity-85 rotate-[-6deg] pointer-events-none">
          <!-- Simbol Checklist Oval Penanda TTD Digital -->
          <div class="relative z-10 inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-white/90 border border-emerald-500 text-emerald-800 font-sans font-bold text-xs shadow-sm">
            <span class="text-emerald-600 mr-1 font-black">✓</span> DISAHKAN DIGITAL
          </div>
        </div>

        <p class="text-xs sm:text-sm font-bold underline">Dr. Anita Hidayati, S.Kom., M.Kom.</p>
        <p class="text-xs font-mono">NIP. 197908022003122001</p>
      </div>
    </div>

  </div>

</body>
</html>
