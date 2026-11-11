<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form F4: Buku Catatan Bimbingan Akademik Dosen - {{ $pengajuan->mahasiswa->name }}</title>
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
      <span>Cetak / Unduh PDF F4</span>
    </button>
  </div>

  <div class="w-full max-w-4xl bg-white p-8 sm:p-14 shadow-lg border border-slate-200 print-shadow-none text-slate-900">
    <div class="border-b-4 border-double border-slate-900 pb-3 mb-6 text-center">
      <h3 class="text-sm sm:text-base font-bold uppercase">POLITEKNIK NEGERI JAKARTA - JURUSAN TEKNIK INFORMATIKA DAN KOMPUTER</h3>
      <h2 class="text-lg sm:text-xl font-bold uppercase text-[#0056B3]">FORM F4: BUKU CATATAN BIMBINGAN DOSEN PNJ</h2>
      <p class="text-xs text-slate-600 font-sans mt-0.5">Pemenuhan Standar Asistensi Akademik (Wajib Minimum 8 Kali Pertemuan)</p>
    </div>

    <div class="text-xs sm:text-sm mb-6 space-y-1">
      <div class="flex"><span class="w-40 font-bold">Mahasiswa / NIM</span>: <span class="ml-2 font-bold">{{ $pengajuan->mahasiswa->name }} ({{ $pengajuan->mahasiswa->identifier }})</span></div>
      <div class="flex"><span class="w-40 font-bold">Program Studi</span>: <span class="ml-2">{{ $pengajuan->mahasiswa->prodi ?? 'Teknik Informatika (D4)' }}</span></div>
      <div class="flex"><span class="w-40 font-bold">Dosen Pembimbing</span>: <span class="ml-2 font-bold">{{ $pengajuan->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</span></div>
      <div class="flex"><span class="w-40 font-bold">Total Sesi Bimbingan</span>: <span class="ml-2 font-bold font-sans {{ $bimbingans->count() >= 8 ? 'text-emerald-700' : 'text-amber-700' }}">{{ $bimbingans->count() }} Sesi Terpenuhi {{ $bimbingans->count() >= 8 ? '(Memenuhi Syarat F6/Sidang)' : '(Kurang dari 8 Sesi)' }}</span></div>
    </div>

    <!-- Table Bimbingan F4 -->
    <table class="w-full border-collapse border border-slate-300 text-xs text-left mb-8">
      <thead>
        <tr class="bg-slate-100 font-sans text-slate-800 uppercase tracking-wider">
          <th class="border border-slate-300 p-2.5 text-center w-12">Sesi</th>
          <th class="border border-slate-300 p-2.5 w-28">Tanggal</th>
          <th class="border border-slate-300 p-2.5">Materi &amp; Bahasan Bimbingan</th>
          <th class="border border-slate-300 p-2.5 w-44">Progres Laporan / Bab</th>
          <th class="border border-slate-300 p-2.5 text-center w-24">Paraf Dosen</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bimbingans as $b)
        <tr>
          <td class="border border-slate-300 p-2.5 text-center font-bold font-sans">#{{ $b->sesi_ke }}</td>
          <td class="border border-slate-300 p-2.5 font-sans">{{ $b->tanggal_bimbingan->format('d/m/Y') }}</td>
          <td class="border border-slate-300 p-2.5">
            <p class="font-semibold">{{ $b->materi_bimbingan }}</p>
            @if($b->catatan_dosen)
              <p class="text-[10px] text-[#0056B3] italic mt-1">Arahan: {{ $b->catatan_dosen }}</p>
            @endif
          </td>
          <td class="border border-slate-300 p-2.5 text-slate-600">{{ $b->progres_laporan ?: 'Penyelesaian Naskah PKL' }}</td>
          <td class="border border-slate-300 p-2.5 text-center">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 border border-emerald-500 text-emerald-800 text-[10px] font-bold">
              ✓
            </span>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="border border-slate-300 p-4 text-center text-slate-400">Belum ada sesi bimbingan yang tercatat.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Footer Legalisasi Dosen (PRD 8) -->
    <div class="mt-8 pt-4 flex justify-between items-end">
      <div class="text-[11px] text-slate-500 font-sans max-w-xs space-y-1">
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 border-2 border-emerald-500 text-emerald-700 font-bold text-sm">
            ✓
          </span>
          <div>
            <div class="font-bold text-slate-800">Verifikasi Dosen Pembimbing</div>
            <div class="text-[10px]">Tervalidasi Digital SIM-PKL PNJ</div>
          </div>
        </div>
      </div>

      <div class="text-center relative pr-4">
        <p class="text-xs sm:text-sm font-bold">Dosen Pembimbing PKL,</p>
        <div class="relative w-44 h-24 mx-auto my-1 flex items-center justify-center">
          <img src="{{ asset('images/stempel-pnj.svg') }}" alt="Stempel PNJ" class="absolute w-24 h-24 opacity-85 rotate-[-4deg] pointer-events-none">
          <div class="relative z-10 inline-flex items-center justify-center px-3.5 py-1 rounded-full bg-white/90 border border-emerald-500 text-emerald-800 font-sans font-bold text-[11px] shadow-sm">
            <span class="text-emerald-600 mr-1 font-black">✓</span> DISETUJUI DOSEN
          </div>
        </div>
        <p class="text-xs sm:text-sm font-bold underline">{{ $pengajuan->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.' }}</p>
        <p class="text-xs font-mono">NIP. {{ $pengajuan->dosen->identifier ?? '198503152010121002' }}</p>
      </div>
    </div>
  </div>

</body>
</html>
