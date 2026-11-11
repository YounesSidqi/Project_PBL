<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form F2: Spesifikasi Tugas & User Requirement - {{ $pengajuan->mahasiswa->name }}</title>
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
    <button onclick="window.print()" class="px-5 py-2 bg-[#02796B] hover:bg-teal-800 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-colors">
      <span>Cetak / Unduh PDF F2</span>
    </button>
  </div>

  <div class="w-full max-w-4xl bg-white p-8 sm:p-14 shadow-lg border border-slate-200 print-shadow-none text-slate-900">
    <div class="border-b-4 border-double border-slate-900 pb-3 mb-6 text-center">
      <h3 class="text-sm sm:text-base font-bold uppercase">POLITEKNIK NEGERI JAKARTA - JURUSAN TEKNIK INFORMATIKA DAN KOMPUTER</h3>
      <h2 class="text-lg sm:text-xl font-bold uppercase text-[#02796B]">FORM F2: SPESIFIKASI TUGAS &amp; USER REQUIREMENT INDUSTRI</h2>
      <p class="text-xs text-slate-600 font-sans mt-0.5">Pemetaan Backlog Teknis &amp; Ruang Lingkup Pekerjaan Mahasiswa di DUDI</p>
    </div>

    <div class="text-xs sm:text-sm mb-6 space-y-1">
      <div class="flex"><span class="w-40 font-bold">Mahasiswa / NIM</span>: <span class="ml-2 font-bold">{{ $pengajuan->mahasiswa->name }} ({{ $pengajuan->mahasiswa->identifier }})</span></div>
      <div class="flex"><span class="w-40 font-bold">Perusahaan / DUDI</span>: <span class="ml-2 font-bold">{{ $pengajuan->industri->nama_perusahaan ?? 'PT Telekomunikasi Seluler' }}</span></div>
      <div class="flex"><span class="w-40 font-bold">Pembimbing Lapangan</span>: <span class="ml-2">{{ $pengajuan->industri->kontak_person ?? 'Bagas Wicaksono, S.T.' }}</span></div>
      <div class="flex"><span class="w-40 font-bold">Dosen Pembimbing PNJ</span>: <span class="ml-2">{{ $pengajuan->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</span></div>
    </div>

    <!-- Matriks Tugas F2 Table -->
    <table class="w-full border-collapse border border-slate-300 text-xs text-left mb-8">
      <thead>
        <tr class="bg-slate-100 font-sans text-slate-800 uppercase tracking-wider">
          <th class="border border-slate-300 p-2.5 text-center w-10">No</th>
          <th class="border border-slate-300 p-2.5 w-44">Modul Pekerjaan</th>
          <th class="border border-slate-300 p-2.5">Spesifikasi Kebutuhan &amp; Deliverables</th>
          <th class="border border-slate-300 p-2.5 text-center w-24">Target</th>
          <th class="border border-slate-300 p-2.5 text-center w-24">Status ACC</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tugasList as $idx => $t)
        <tr>
          <td class="border border-slate-300 p-2.5 text-center font-bold">{{ $idx + 1 }}</td>
          <td class="border border-slate-300 p-2.5 font-bold">{{ $t->modul_pekerjaan }}</td>
          <td class="border border-slate-300 p-2.5">
            <p>{{ $t->spesifikasi_kebutuhan }}</p>
            @if($t->catatan_industri)
              <p class="text-[10px] text-teal-800 italic mt-1">Catatan: {{ $t->catatan_industri }}</p>
            @endif
          </td>
          <td class="border border-slate-300 p-2.5 text-center">{{ $t->target_minggu ?: 'Minggu ' . ($idx + 1) }}</td>
          <td class="border border-slate-300 p-2.5 text-center font-sans font-bold text-[11px] {{ $t->status === 'acc' ? 'text-emerald-700' : 'text-amber-700' }}">
            {{ strtoupper($t->status) }}
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="border border-slate-300 p-4 text-center text-slate-400">Belum ada rincian tugas yang ditambahkan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Footer Legalisasi Industri (PRD 8) -->
    <div class="mt-8 pt-4 flex justify-between items-end">
      <div class="text-[11px] text-slate-500 font-sans max-w-xs space-y-1">
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-teal-100 border-2 border-teal-600 text-teal-800 font-bold text-sm">
            ✓
          </span>
          <div>
            <div class="font-bold text-slate-800">Diverifikasi Mitra DUDI</div>
            <div class="text-[10px]">Otoritas Penuh Pembimbing Industri</div>
          </div>
        </div>
      </div>

      <div class="text-center relative pr-4">
        <p class="text-xs sm:text-sm font-bold">Pembimbing Lapangan Industri,</p>
        <div class="relative w-44 h-24 mx-auto my-1 flex items-center justify-center">
          <img src="{{ asset('images/stempel-dudi.svg') }}" alt="Stempel DUDI" class="absolute w-24 h-24 opacity-85 rotate-[-3deg] pointer-events-none">
          <div class="relative z-10 inline-flex items-center justify-center px-3.5 py-1 rounded-full bg-white/90 border border-teal-600 text-teal-800 font-sans font-bold text-[11px] shadow-sm">
            <span class="text-teal-600 mr-1 font-black">✓</span> DISETUJUI DUDI
          </div>
        </div>
        <p class="text-xs sm:text-sm font-bold underline">{{ $pengajuan->industri->kontak_person ?? 'Bagas Wicaksono, S.T.' }}</p>
        <p class="text-xs font-mono">{{ $pengajuan->industri->nama_perusahaan ?? 'PT Telekomunikasi Seluler' }}</p>
      </div>
    </div>
  </div>

</body>
</html>
