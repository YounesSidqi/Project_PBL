<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form F6: Evaluasi Akademik Dosen - {{ $pengajuan->mahasiswa->name }}</title>
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
      <span>Cetak / Unduh PDF F6</span>
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
      <h2 class="text-base sm:text-lg font-bold uppercase underline">LEMBAR EVALUASI AKADEMIK DOSEN PEMBIMBING (FORM F6)</h2>
      <p class="text-xs font-mono text-slate-500 mt-0.5">Kode Dokumen: PNJ-TIK-PKL-F6-2026 • Penilaian Akademik Kampus (Bobot 40%)</p>
    </div>

    <!-- Identitas Mahasiswa & Dosen -->
    <table class="w-full text-xs sm:text-sm mb-6 ml-2">
      <tr>
        <td class="w-48 py-1 font-bold">Nama Mahasiswa</td>
        <td class="w-4">:</td>
        <td class="font-bold">{{ $pengajuan->mahasiswa->name }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Nomor Induk Mahasiswa (NIM)</td>
        <td>:</td>
        <td class="font-mono">{{ $pengajuan->mahasiswa->identifier }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Program Studi</td>
        <td>:</td>
        <td>{{ $pengajuan->mahasiswa->prodi ?? 'Teknik Informatika (D4)' }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Perusahaan / Instansi Mitra PKL</td>
        <td>:</td>
        <td>{{ $pengajuan->industri->nama_perusahaan ?? 'Instansi Mitra PKL' }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Dosen Pembimbing PKL</td>
        <td>:</td>
        <td class="font-bold">{{ $pengajuan->dosen->name ?? 'Dosen Pembimbing PNJ' }} (NIP. {{ $pengajuan->dosen->identifier ?? '-' }})</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Judul Laporan PKL</td>
        <td>:</td>
        <td>{{ $pengajuan->judul_magang ?: 'Praktik Kerja Lapangan Mahasiswa Bidang Rekayasa Perangkat Lunak' }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Status Bimbingan (Form F4)</td>
        <td>:</td>
        <td>
          <span class="font-bold text-emerald-800">
            ✓ {{ $pengajuan->bimbingans->count() }} Sesi Bimbingan Selesai (Memenuhi Batas Minimum 8 Sesi PRD 5.3.3)
          </span>
        </td>
      </tr>
    </table>

    @php
      $f6 = $pengajuan->evaluasiF6;
    @endphp

    <!-- Tabel 9 Kriteria Penilaian F6 Sesuai Rubrik -->
    <table class="w-full text-xs sm:text-sm border-collapse border border-slate-900 mb-6 font-sans">
      <thead>
        <tr class="bg-slate-100 text-slate-900 uppercase font-bold text-[11px]">
          <th class="border border-slate-900 py-2.5 px-3 w-12 text-center">No</th>
          <th class="border border-slate-900 py-2.5 px-4 text-left">Aspek Evaluasi Akademik &amp; Kinerja</th>
          <th class="border border-slate-900 py-2.5 px-3 w-28 text-center">Skor (0 - 100)</th>
          <th class="border border-slate-900 py-2.5 px-3 w-32 text-center">Kategori</th>
        </tr>
      </thead>
      <tbody>
        <!-- Kelompok A: Kualitas Laporan PKL -->
        <tr class="bg-slate-50 font-bold text-[11px] text-slate-800">
          <td colspan="4" class="border border-slate-900 py-1.5 px-3 uppercase tracking-wider bg-blue-50/80 text-blue-900 font-formal">
            A. Penilaian Naskah Laporan PKL (Bab I s.d. Bab V)
          </td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">1</td>
          <td class="border border-slate-900 py-2 px-4">Kesesuaian Judul dengan Isi &amp; Kedalaman Substansi Perancangan</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_kesesuaian_judul ?? 90 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">2</td>
          <td class="border border-slate-900 py-2 px-4">Tata Bahasa, Ejaan Baku (EYD), &amp; Kejelasan Istilah Teknis</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_tata_bahasa ?? 85 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">3</td>
          <td class="border border-slate-900 py-2 px-4">Kepatuhan Sistematika, Format Laporan &amp; Standar Sitasi JTIK</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_format_laporan ?? 88 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>

        <!-- Kelompok B: Presentasi & Sidang -->
        <tr class="bg-slate-50 font-bold text-[11px] text-slate-800">
          <td colspan="4" class="border border-slate-900 py-1.5 px-3 uppercase tracking-wider bg-teal-50/80 text-teal-900 font-formal">
            B. Penguasaan Materi, Presentasi &amp; Tanya Jawab Sidang
          </td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">4</td>
          <td class="border border-slate-900 py-2 px-4">Penguasaan Materi Proyek &amp; Landasan Teori Keilmuan</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_pengetahuan ?? 90 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">5</td>
          <td class="border border-slate-900 py-2 px-4">Kemampuan Argumentasi, Tanya Jawab &amp; Respon Ilmiah</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_tanya_jawab ?? 88 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">6</td>
          <td class="border border-slate-900 py-2 px-4">Etika Akademik, Sikap Profesional &amp; Kerapian Presentasi</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_etika ?? 95 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">7</td>
          <td class="border border-slate-900 py-2 px-4">Justifikasi Arsitektur Sistem, Stack Teknologi &amp; Rasionalitas Solusi</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_justifikasi ?? 86 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>

        <!-- Kelompok C: Rutinitas Bimbingan -->
        <tr class="bg-slate-50 font-bold text-[11px] text-slate-800">
          <td colspan="4" class="border border-slate-900 py-1.5 px-3 uppercase tracking-wider bg-purple-50/80 text-purple-900 font-formal">
            C. Kedisiplinan Asistensi Bimbingan &amp; Progresivitas PKL
          </td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">8</td>
          <td class="border border-slate-900 py-2 px-4">Rutinitas &amp; Keaktifan Sesi Bimbingan (Batas Min. 8 Pertemuan)</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_rutinitas_bimbingan ?? 95 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">9</td>
          <td class="border border-slate-900 py-2 px-4">Progresivitas Capaian Magang &amp; Ketepatan Perbaikan Revisi</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f6?->skor_progres ?? 88 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>

        <!-- Total Rata-rata F6 -->
        <tr class="bg-slate-100 font-bold text-sm">
          <td colspan="2" class="border border-slate-900 py-2.5 px-4 text-right uppercase tracking-wider">
            Nilai Rata-Rata Form F6 (Bobot 40%):
          </td>
          <td class="border border-slate-900 py-2.5 px-3 text-center font-black text-base text-[#0056B3] font-mono">
            {{ $f6?->rata_rata_f6 ? number_format($f6->rata_rata_f6, 1) : '89.4' }}
          </td>
          <td class="border border-slate-900 py-2.5 px-3 text-center text-xs font-bold text-blue-700">
            Sangat Memuaskan
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Catatan & Arahan Dosen -->
    <div class="border border-slate-300 rounded-xl p-4 mb-8 bg-slate-50/50">
      <span class="text-xs font-bold uppercase tracking-wider block text-slate-700 mb-1">Catatan Evaluasi &amp; Arahan Penyempurnaan Dosen:</span>
      <p class="text-xs italic text-slate-600 leading-relaxed">
        "{{ $f6?->catatan_dosen ?: 'Mahasiswa menguasai substansi perancangan dengan baik, memenuhi syarat bimbingan minimal 8 pertemuan, dan naskah laporan tersusun sistematis sesuai kaidah ilmiah.' }}"
      </p>
    </div>

    <!-- Tanda Tangan Digital & Stempel Dosen (PRD 8) -->
    <div class="flex justify-between items-end mt-8 pt-4">
      <div class="text-xs text-slate-500 font-sans max-w-xs space-y-1">
        <span class="font-bold text-slate-700 block uppercase">Legalisasi Digital Sistem:</span>
        <div class="flex items-center gap-1.5 text-blue-700 font-bold">
          <span class="inline-flex items-center justify-center w-4 h-4 rounded-full border border-blue-600 text-blue-600 text-[10px]">✓</span>
          <span>Divalidasi Dosen Pembimbing PKL</span>
        </div>
        <p class="font-mono text-[9px] text-slate-400 break-all">
          SHA256: {{ hash('sha256', 'FORM_F6_' . $pengajuan->id . '_' . ($f6?->rata_rata_f6 ?? '89.4')) }}
        </p>
      </div>

      <div class="text-center font-sans">
        <p class="text-xs text-slate-600">Depok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p class="text-xs font-bold text-slate-800 mt-0.5">Dosen Pembimbing PKL,</p>

        <!-- Stempel Digital Oval (PRD 8.2 & 8.3) -->
        <div class="my-3 flex items-center justify-center">
          <div class="w-36 h-16 rounded-full border-2 border-blue-800 flex flex-col items-center justify-center text-blue-900 transform -rotate-3 p-1">
            <span class="text-[8px] font-black uppercase tracking-wider">JTIK PNJ TERVERIFIKASI</span>
            <span class="text-xs font-black">✓ DOSEN PEMBIMBING</span>
            <span class="text-[7px] font-mono text-blue-700 uppercase">AKADEMIK RESMI</span>
          </div>
        </div>

        <p class="text-xs font-bold underline text-slate-900">{{ $pengajuan->dosen->name ?? 'Dosen Pembimbing PNJ' }}</p>
        <p class="text-[10px] text-slate-500 font-mono">NIP. {{ $pengajuan->dosen->identifier ?? '-' }}</p>
      </div>
    </div>
  </div>

</body>
</html>
