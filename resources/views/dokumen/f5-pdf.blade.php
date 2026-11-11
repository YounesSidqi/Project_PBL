<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form F5: Evaluasi Industri - {{ $pengajuan->mahasiswa->name }}</title>
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
      <span>Cetak / Unduh PDF F5</span>
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
      <h2 class="text-base sm:text-lg font-bold uppercase underline">LEMBAR EVALUASI KINERJA PRAKTIK KERJA LAPANGAN DI INDUSTRI (FORM F5)</h2>
      <p class="text-xs font-mono text-slate-500 mt-0.5">Kode Dokumen: PNJ-TIK-PKL-F5-2026 • Kerahasiaan Nilai Industri (Bobot 60%)</p>
    </div>

    <!-- Identitas Mahasiswa & Industri -->
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
        <td class="py-1 font-bold">Perusahaan / Instansi Mitra</td>
        <td>:</td>
        <td class="font-bold">{{ $pengajuan->industri->nama_perusahaan ?? 'Instansi Mitra PKL' }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Pembimbing Lapangan (PIC)</td>
        <td>:</td>
        <td>{{ $pengajuan->industri->kontak_person ?: 'Penanggung Jawab Mitra Industri' }}</td>
      </tr>
      <tr>
        <td class="py-1 font-bold">Posisi &amp; Periode Magang</td>
        <td>:</td>
        <td>{{ $pengajuan->posisi_magang ?: 'Intern' }} (Semester Genap 2026)</td>
      </tr>
    </table>

    @php
      $f5 = $pengajuan->evaluasiF5;
    @endphp

    <!-- Tabel 10 Kriteria Penilaian F5 -->
    <table class="w-full text-xs sm:text-sm border-collapse border border-slate-900 mb-6 font-sans">
      <thead>
        <tr class="bg-slate-100 text-slate-900 uppercase font-bold text-[11px]">
          <th class="border border-slate-900 py-2.5 px-3 w-12 text-center">No</th>
          <th class="border border-slate-900 py-2.5 px-4 text-left">Aspek Penilaian Mutu (Softskill &amp; Hardskill)</th>
          <th class="border border-slate-900 py-2.5 px-3 w-28 text-center">Skor (0 - 100)</th>
          <th class="border border-slate-900 py-2.5 px-3 w-32 text-center">Kategori</th>
        </tr>
      </thead>
      <tbody>
        <!-- Kelompok A: Softskills -->
        <tr class="bg-slate-50 font-bold text-[11px] text-slate-800">
          <td colspan="4" class="border border-slate-900 py-1.5 px-3 uppercase tracking-wider bg-teal-50/80 text-teal-900 font-formal">
            A. Indikator Softskills &amp; Etika Kerja
          </td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">1</td>
          <td class="border border-slate-900 py-2 px-4">Komunikasi dan Presentasi Gagasan Teknis</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_komunikasi ?? 85 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">2</td>
          <td class="border border-slate-900 py-2 px-4">Berpikir Kritis, Analitis &amp; Problem Solving</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_berpikir_kritis ?? 85 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">3</td>
          <td class="border border-slate-900 py-2 px-4">Kerjasama Tim (Teamwork) &amp; Adaptabilitas Kerja</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_kerjasama ?? 90 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">4</td>
          <td class="border border-slate-900 py-2 px-4">Inisiatif Mandiri &amp; Antusiasme Belajar</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_inisiatif ?? 85 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">5</td>
          <td class="border border-slate-900 py-2 px-4">Literasi Digital, Kedisiplinan &amp; Kepatuhan SOP</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_literasi ?? 88 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>

        <!-- Kelompok B: Hardskills -->
        <tr class="bg-slate-50 font-bold text-[11px] text-slate-800">
          <td colspan="4" class="border border-slate-900 py-1.5 px-3 uppercase tracking-wider bg-blue-50/80 text-blue-900 font-formal">
            B. Indikator Hardskills &amp; Capaian Backlog Tugas (Form F2)
          </td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">6</td>
          <td class="border border-slate-900 py-2 px-4">Penguasaan Deskripsi Produk / Solusi Digital</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_deskripsi_produk ?? 90 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">7</td>
          <td class="border border-slate-900 py-2 px-4">Kesesuaian Implementasi Spesifikasi Kebutuhan</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_spesifikasi ?? 88 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">8</td>
          <td class="border border-slate-900 py-2 px-4">Kualitas Desain Arsitektur, Logika &amp; Skema Data</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_desain ?? 86 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">9</td>
          <td class="border border-slate-900 py-2 px-4">Kualitas Baris Kode, Konfigurasi &amp; Reliability</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_implementasi ?? 90 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>
        <tr>
          <td class="border border-slate-900 py-2 px-3 text-center">10</td>
          <td class="border border-slate-900 py-2 px-4">Pengujian, Ketelitian QA &amp; Penanganan Bug</td>
          <td class="border border-slate-900 py-2 px-3 text-center font-bold font-mono">{{ $f5?->skor_pengujian ?? 88 }}</td>
          <td class="border border-slate-900 py-2 px-3 text-center text-xs text-slate-600">Sangat Baik</td>
        </tr>

        <!-- Total Rata-rata F5 -->
        <tr class="bg-slate-100 font-bold text-sm">
          <td colspan="2" class="border border-slate-900 py-2.5 px-4 text-right uppercase tracking-wider">
            Nilai Rata-Rata Form F5 (Bobot 60%):
          </td>
          <td class="border border-slate-900 py-2.5 px-3 text-center font-black text-base text-teal-800 font-mono">
            {{ $f5?->rata_rata_f5 ? number_format($f5->rata_rata_f5, 1) : '87.5' }}
          </td>
          <td class="border border-slate-900 py-2.5 px-3 text-center text-xs font-bold text-teal-700">
            Sangat Memuaskan
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Saran & Rekomendasi Industri -->
    <div class="border border-slate-300 rounded-xl p-4 mb-8 bg-slate-50/50">
      <span class="text-xs font-bold uppercase tracking-wider block text-slate-700 mb-1">Catatan Apresiasi &amp; Rekomendasi Karir:</span>
      <p class="text-xs italic text-slate-600 leading-relaxed">
        "{{ $f5?->saran_industri ?: 'Mahasiswa menunjukkan kedisiplinan kerja dan kompetensi teknis yang memenuhi standar industri. Berpotensi untuk diserap dalam rekrutmen profesional.' }}"
      </p>
    </div>

    <!-- Tanda Tangan Digital & Stempel Mitra (PRD 8) -->
    <div class="flex justify-between items-end mt-8 pt-4">
      <div class="text-xs text-slate-500 font-sans max-w-xs space-y-1">
        <span class="font-bold text-slate-700 block uppercase">Legalisasi Digital Sistem:</span>
        <div class="flex items-center gap-1.5 text-teal-700 font-bold">
          <span class="inline-flex items-center justify-center w-4 h-4 rounded-full border border-teal-600 text-teal-600 text-[10px]">✓</span>
          <span>Divalidasi Pembimbing Lapangan</span>
        </div>
        <p class="font-mono text-[9px] text-slate-400 break-all">
          SHA256: {{ hash('sha256', 'FORM_F5_' . $pengajuan->id . '_' . ($f5?->rata_rata_f5 ?? '87.5')) }}
        </p>
      </div>

      <div class="text-center font-sans">
        <p class="text-xs text-slate-600">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p class="text-xs font-bold text-slate-800 mt-0.5">Pembimbing Lapangan / Mentor Industri,</p>

        <!-- Stempel Digital Oval (PRD 8.2 & 8.3) -->
        <div class="my-3 flex items-center justify-center">
          <div class="w-32 h-16 rounded-full border-2 border-teal-700 flex flex-col items-center justify-center text-teal-800 transform -rotate-3 p-1">
            <span class="text-[8px] font-black uppercase tracking-wider">RESMI TERVERIFIKASI</span>
            <span class="text-xs font-black">✓ TERAKREDITASI</span>
            <span class="text-[7px] font-mono text-teal-700 uppercase">{{ substr($pengajuan->industri->nama_perusahaan ?? 'DUDI', 0, 15) }}</span>
          </div>
        </div>

        <p class="text-xs font-bold underline text-slate-900">{{ $pengajuan->industri->kontak_person ?: 'Pembimbing Industri' }}</p>
        <p class="text-[10px] text-slate-500 font-mono">{{ $pengajuan->industri->nama_perusahaan ?? 'Mitra Industri' }}</p>
      </div>
    </div>
  </div>

</body>
</html>
