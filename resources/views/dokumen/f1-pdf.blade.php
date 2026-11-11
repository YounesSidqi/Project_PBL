<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form F1: Surat Kesediaan Dosen Pembimbing - {{ $application->student->name }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      body { background: white !important; padding: 0 !important; }
      .no-print { display: none !important; }
      .print-shadow-none { box-shadow: none !important; border: none !important; }
    }
    .font-formal { font-family: 'Times New Roman', Times, serif; }
  </style>
</head>
<body class="bg-slate-100 text-slate-900 font-formal p-4 sm:p-8 min-h-screen flex flex-col items-center">

  <div class="no-print w-full max-w-4xl bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex items-center justify-between font-sans">
    <a href="{{ url()->previous() }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
      &larr; Kembali
    </a>
    <button onclick="window.print()" class="px-5 py-2 bg-[#0056B3] hover:bg-blue-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-colors">
      <span>Cetak / Unduh PDF F1</span>
    </button>
  </div>

  <div class="w-full max-w-4xl bg-white p-8 sm:p-14 shadow-lg border border-slate-200 print-shadow-none text-slate-900">
    <!-- KOP RESMI PNJ -->
    <div class="border-b-4 border-double border-slate-900 pb-3 mb-6 text-center">
      <h3 class="text-sm sm:text-base font-bold uppercase">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h3>
      <h2 class="text-lg sm:text-xl font-bold uppercase text-[#0056B3]">POLITEKNIK NEGERI JAKARTA</h2>
      <h1 class="text-base sm:text-lg font-bold uppercase">JURUSAN TEKNIK INFORMATIKA DAN KOMPUTER</h1>
      <p class="text-xs text-slate-600 font-sans mt-0.5">
        Jl. Prof. Dr. G.A. Siwabessy, Kampus UI Depok 16425 • Laman: http://tik.pnj.ac.id
      </p>
    </div>

    <div class="text-center mb-6">
      <h2 class="text-base sm:text-lg font-bold uppercase underline">SURAT KESEDIAAN DOSEN PEMBIMBING PKL (FORM F1)</h2>
      <p class="text-xs font-mono text-slate-500 mt-0.5">Kode Dokumen: PNJ-TIK-PKL-F1-2026</p>
    </div>

    <div class="text-xs sm:text-sm leading-relaxed space-y-4 mb-8">
      <p>Yang bertanda tangan di bawah ini:</p>
      <table class="w-full ml-4 text-xs sm:text-sm">
        <tr>
          <td class="w-44 py-1 font-bold">Nama Dosen Pembimbing</td>
          <td class="w-4">:</td>
          <td class="font-bold">{{ $application->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</td>
        </tr>
        <tr>
          <td class="py-1 font-bold">NIP</td>
          <td>:</td>
          <td class="font-mono">{{ $application->dosen->identifier ?? '198503152010121002' }}</td>
        </tr>
        <tr>
          <td class="py-1 font-bold">Program Studi</td>
          <td>:</td>
          <td>{{ $application->dosen->prodi ?? 'Teknik Informatika' }}</td>
        </tr>
      </table>

      <p>Menyatakan <strong>BERSEDIA</strong> menjadi Dosen Pembimbing Praktik Kerja Lapangan (PKL) bagi mahasiswa:</p>
      <table class="w-full ml-4 text-xs sm:text-sm">
        <tr>
          <td class="w-44 py-1 font-bold">Nama Mahasiswa</td>
          <td class="w-4">:</td>
          <td class="font-bold">{{ $application->student->name }}</td>
        </tr>
        <tr>
          <td class="py-1 font-bold">NIM</td>
          <td>:</td>
          <td class="font-mono">{{ $application->student->identifier }}</td>
        </tr>
        <tr>
          <td class="py-1 font-bold">Program Studi</td>
          <td>:</td>
          <td>{{ $application->student->prodi ?? 'Teknik Informatika (D4)' }}</td>
        </tr>
        <tr>
          <td class="py-1 font-bold">Instansi / Perusahaan PKL</td>
          <td>:</td>
          <td class="font-bold">{{ $application->company_name ?? 'Mitra Industri PKL' }}</td>
        </tr>
        <tr>
          <td class="py-1 font-bold">Judul / Topik Magang</td>
          <td>:</td>
          <td>{{ $application->internship_title ?? 'Praktik Kerja Lapangan Bidang Informatika' }}</td>
        </tr>
      </table>

      <p class="mt-4">
        Demikian Surat Kesediaan ini dibuat dan disahkan secara digital untuk dapat dipergunakan dalam proses administrasi PKL Jurusan TIK Politeknik Negeri Jakarta.
      </p>
    </div>

    <!-- Official Validation Footer with Oval Checklist & Digital Stamp (PRD 8) -->
    <div class="mt-12 pt-4 flex justify-between items-end">
      <div class="text-[11px] text-slate-500 font-sans max-w-xs space-y-1">
        <div class="flex items-center gap-2">
          <!-- Visual Checklist Oval (PRD 8.2) -->
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 border-2 border-emerald-500 text-emerald-700 font-bold text-sm">
            ✓
          </span>
          <div>
            <div class="font-bold text-slate-800">Disetujui KPS &amp; Dosen</div>
            <div class="text-[10px]">Tervalidasi Digital SIM-PKL</div>
          </div>
        </div>
        <p class="font-mono text-[9px] text-slate-400">HASH: {{ hash('sha256', $application->id . 'F1_PNJ') }}</p>
      </div>

      <div class="text-center relative pr-4">
        <p class="text-xs sm:text-sm font-bold">Dosen Pembimbing PKL,</p>
        
        <div class="relative w-44 h-24 mx-auto my-1 flex items-center justify-center">
          <img src="{{ asset('images/stempel-pnj.svg') }}" alt="Stempel Digital PNJ" class="absolute w-24 h-24 opacity-85 rotate-[-4deg] pointer-events-none">
          <div class="relative z-10 inline-flex items-center justify-center px-3.5 py-1 rounded-full bg-white/90 border border-emerald-500 text-emerald-800 font-sans font-bold text-[11px] shadow-sm">
            <span class="text-emerald-600 mr-1 font-black">✓</span> DISAHKAN KPS
          </div>
        </div>

        <p class="text-xs sm:text-sm font-bold underline">{{ $application->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</p>
        <p class="text-xs font-mono">NIP. {{ $application->dosen->identifier ?? '198503152010121002' }}</p>
      </div>
    </div>
  </div>

</body>
</html>
