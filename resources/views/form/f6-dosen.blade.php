@extends('layouts.app', [
  'title' => 'Form F6: Evaluasi Akademik Dosen - SIM-PKL TIK Politeknik Negeri Jakarta',
  'pageTitle' => 'Form F6: Evaluasi Akademik Dosen',
  'breadcrumbParent' => 'Portal Dosen Pembimbing',
  'activePage' => 'f6'
])

@section('content')
<!-- Header & Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Dosen</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Kurikulum PKL TIK</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#0056B3] font-semibold">Form F6 (Evaluasi Akademik Kampus)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F6: Evaluasi Nilai Akademik Dosen Pembimbing
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Penilaian naskah laporan, presentasi sidang, dan kedisiplinan asistensi berkala dengan bobot <strong>40%</strong>.
    </p>
  </div>

  <div class="flex items-center gap-2">
    @if($selectedPengajuan && $evalF6)
      <a href="{{ route('dokumen.f6', $selectedPengajuan->id) }}" target="_blank" class="h-10 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs sm:text-sm transition-colors flex items-center gap-2 shadow-sm">
        <span class="material-symbols-outlined text-[18px]">print</span>
        <span>Cetak Form F6</span>
      </a>
    @endif

    @if($selectedPengajuan && $selectedPengajuan->nilai_akhir_fuzzy)
      <a href="{{ route('dokumen.f7', $selectedPengajuan->id) }}" target="_blank" class="h-10 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-sm">
        <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
        <span>Cetak Form F7 Selesai</span>
      </a>
    @endif
  </div>
</div>

@if(!$selectedPengajuan)
  <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 p-8 shadow-sm">
    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#0056B3] flex items-center justify-center mx-auto mb-4">
      <span class="material-symbols-outlined text-3xl">school</span>
    </div>
    <h3 class="text-base font-bold text-slate-800 font-heading">Belum Ada Mahasiswa Bimbingan yang Ditetapkan</h3>
    <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
      KPS (Koordinator Program Studi) akan menetapkan mahasiswa bimbingan secara terpusat ke akun Anda.
    </p>
  </div>
@else

<!-- Student Selector Bar -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0056B3] to-[#02796B] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
      {{ strtoupper(substr($selectedPengajuan->mahasiswa->name, 0, 2)) }}
    </div>
    <div class="min-w-0">
      <span class="text-[10px] font-bold uppercase tracking-wider text-[#0056B3] block">Mahasiswa Bimbingan:</span>
      <h3 class="text-base font-bold text-slate-900 font-heading truncate">{{ $selectedPengajuan->mahasiswa->name }}</h3>
      <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
        <span class="font-mono font-semibold text-slate-700">NIM: {{ $selectedPengajuan->mahasiswa->identifier }}</span>
        <span>•</span>
        <span>{{ $selectedPengajuan->mahasiswa->prodi ?? 'Teknik Informatika (D4)' }}</span>
        <span>•</span>
        <span class="text-slate-600 font-medium">{{ $selectedPengajuan->industri->nama_perusahaan ?? 'Perusahaan Mitra' }}</span>
      </div>
    </div>
  </div>

  <div class="flex items-center gap-3">
    @if($students->count() > 1)
      <div class="flex items-center gap-2">
        <label class="text-xs font-bold text-slate-500 uppercase">Pilih Mahasiswa:</label>
        <select onchange="window.location.href='{{ route('form.f6.dosen') }}?pengajuan_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-300 text-xs font-semibold bg-slate-50 text-slate-700 focus:ring-2 focus:ring-[#0056B3]/20">
          @foreach($students as $st)
            <option value="{{ $st->id }}" {{ $selectedPengajuan->id === $st->id ? 'selected' : '' }}>
              {{ $st->mahasiswa->name }} ({{ $st->bimbingans->count() }}/8 Sesi) {{ $st->evaluasiF6 ? '✓ F6' : '• Belum' }}
            </option>
          @endforeach
        </select>
      </div>
    @endif

    <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-center">
      <span class="text-[10px] text-slate-400 font-bold uppercase block">Progres Bimbingan</span>
      <span class="text-sm font-extrabold {{ $bimbingansCount >= 8 ? 'text-emerald-600' : 'text-amber-700' }}">
        {{ $bimbingansCount }}/8 Sesi Terlaksana
      </span>
    </div>
  </div>
</div>

<!-- Prerequisite Banner: Syarat Min. 8 Sesi Bimbingan (PRD 5.3.3 & 7.5) -->
@if($bimbingansCount < 8)
  <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex items-start gap-3 text-amber-900 shadow-sm">
    <span class="material-symbols-outlined text-amber-600 text-2xl shrink-0 mt-0.5">warning</span>
    <div class="text-xs space-y-1">
      <h4 class="font-bold text-sm text-amber-950 font-heading">Syarat Minimal 8 Sesi Bimbingan Belum Terpenuhi</h4>
      <p class="leading-relaxed">
        Sesuai panduan akademik kurikulum <strong>PRD Bab 5.3.3 &amp; 7.5</strong>, mahasiswa wajib melaksanakan minimal <strong>8 kali sesi asistensi bimbingan Form F4</strong> sebelum nilai F6 dapat ditransmisikan ke engine Fuzzy Logic. Saat ini mahasiswa baru menyelesaikan <strong>{{ $bimbingansCount }} dari 8 sesi</strong>.
      </p>
      <div class="pt-1">
        <a href="{{ route('form.f4.dosen', ['pengajuan_id' => $selectedPengajuan->id]) }}" class="inline-flex items-center gap-1 font-bold text-amber-800 hover:text-amber-950 underline">
          <span>Buka Form F4 &amp; Tambah Catatan Sesi Bimbingan</span>
          <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
      </div>
    </div>
  </div>
@else
  <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 flex items-center justify-between gap-3 text-emerald-900 shadow-sm">
    <div class="flex items-center gap-2.5">
      <span class="material-symbols-outlined text-emerald-600 text-2xl">verified</span>
      <div>
        <h4 class="font-bold text-xs sm:text-sm font-heading text-emerald-950">Syarat Minimal 8 Sesi Bimbingan Terpenuhi ({{ $bimbingansCount }} Sesi)</h4>
        <p class="text-[11px] text-emerald-700">Mahasiswa telah memenuhi kriteria bimbingan akademik dan layak dilakukan penilaian akhir serta eksekusi Fuzzy Logic.</p>
      </div>
    </div>
    <span class="px-3 py-1 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-bold shrink-0">Layak Ujian &amp; Nilai</span>
  </div>
@endif

<!-- Evaluation Form Card -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-card space-y-6">
  <!-- Top Banner & Real-time Live Average -->
  <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-6 border-b border-slate-100">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-[#0056B3] border border-blue-200">
          Evaluasi Akademik Kampus
        </span>
        <span class="text-xs font-bold text-slate-500">• 9 Parameter Mutu (Skala 0 - 100)</span>
      </div>
      <h2 class="text-xl font-bold font-heading text-slate-900">
        Rubrik Evaluasi Akademik &amp; Naskah Laporan PKL
      </h2>
      <p class="text-xs text-slate-500 mt-1 max-w-2xl">
        Penilaian dosen mencakup kualitas naskah laporan PKL (Bab I - V), penguasaan materi presentasi, dan konsistensi asistensi bimbingan.
      </p>
    </div>

    <!-- Live Average Score Preview -->
    <div class="bg-gradient-to-tr from-blue-50 to-indigo-50 border border-blue-200/80 p-4 rounded-2xl text-center min-w-[180px] shadow-sm">
      <span class="text-[10px] text-[#0056B3] uppercase font-black tracking-wider block">Skor Rata-Rata F6</span>
      <div class="flex items-baseline justify-center gap-1 mt-0.5">
        <span id="live-average-display" class="text-3xl font-black font-heading text-[#0056B3]">
          {{ $evalF6 ? number_format($evalF6->rata_rata_f6, 1) : '88.0' }}
        </span>
        <span class="text-xs font-bold text-slate-500">/ 100</span>
      </div>
      <span class="text-[10px] text-blue-700 font-semibold block mt-0.5">Bobot 40% Nilai Akhir</span>
    </div>
  </div>

  <form method="POST" action="{{ route('dosen.evaluasi_f6.store', $selectedPengajuan->id) }}" id="form-f6-evaluation" class="space-y-8">
    @csrf

    <!-- SECTION A: KUALITAS LAPORAN PKL (3 Parameter) -->
    <div class="space-y-4">
      <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
        <div class="w-7 h-7 rounded-lg bg-blue-100 text-[#0056B3] flex items-center justify-center font-bold text-xs">
          A
        </div>
        <div>
          <h3 class="text-sm font-bold font-heading text-slate-900">Penilaian Naskah Laporan PKL</h3>
          <p class="text-[11px] text-slate-500">Kesesuaian substansi, kejelasan tata bahasa, dan kepatuhan format buku pedoman.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- 1. Kesesuaian Judul -->
        <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-xs font-bold text-slate-800 mb-1">1. Kesesuaian Judul &amp; Isi</label>
          <p class="text-[10px] text-slate-500 mb-2">Relevansi antara judul dengan perancangan modul sistem magang.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_kesesuaian_judul" required value="{{ old('skor_kesesuaian_judul', $evalF6?->skor_kesesuaian_judul ?? 90) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- 2. Tata Bahasa -->
        <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-xs font-bold text-slate-800 mb-1">2. Tata Bahasa &amp; EYD</label>
          <p class="text-[10px] text-slate-500 mb-2">Kejelasan kalimat ilmiah, ejaan baku, dan konsistensi istilah teknis.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_tata_bahasa" required value="{{ old('skor_tata_bahasa', $evalF6?->skor_tata_bahasa ?? 85) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- 3. Format Laporan -->
        <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-xs font-bold text-slate-800 mb-1">3. Kepatuhan Format JTIK</label>
          <p class="text-[10px] text-slate-500 mb-2">Struktur bab, perwajahan, tabel, gambar, dan sitasi daftar pustaka.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_format_laporan" required value="{{ old('skor_format_laporan', $evalF6?->skor_format_laporan ?? 88) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>
      </div>
    </div>

    <!-- SECTION B: PRESENTASI & UJIAN (4 Parameter) -->
    <div class="space-y-4">
      <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
        <div class="w-7 h-7 rounded-lg bg-teal-100 text-[#02796B] flex items-center justify-center font-bold text-xs">
          B
        </div>
        <div>
          <h3 class="text-sm font-bold font-heading text-slate-900">Penguasaan Materi &amp; Presentasi</h3>
          <p class="text-[11px] text-slate-500">Kecakapan mahasiswa saat sesi presentasi, tanya jawab, dan etika akademik.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- 4. Pengetahuan Subjek -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">4. Penguasaan Materi</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Kedalaman pemahaman teori dan implementasi proyek.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_pengetahuan" required value="{{ old('skor_pengetahuan', $evalF6?->skor_pengetahuan ?? 90) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#02796B] p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 5. Tanya Jawab -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">5. Kemampuan Tanya Jawab</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Ketepatan argumen dan kelancaran menjawab sanggahan.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_tanya_jawab" required value="{{ old('skor_tanya_jawab', $evalF6?->skor_tanya_jawab ?? 88) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#02796B] p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 6. Etika & Sikap -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">6. Etika &amp; Sikap</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Kerapian, sopan santun, dan pembawaan diri.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_etika" required value="{{ old('skor_etika', $evalF6?->skor_etika ?? 95) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#02796B] p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 7. Justifikasi Solusi -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">7. Justifikasi Solusi</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Dasar rasional pemilihan stack teknologi / metode kerja.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_justifikasi" required value="{{ old('skor_justifikasi', $evalF6?->skor_justifikasi ?? 86) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#02796B] p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>
      </div>
    </div>

    <!-- SECTION C: RUTINITAS BIMBINGAN (2 Parameter) -->
    <div class="space-y-4">
      <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
        <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
          C
        </div>
        <div>
          <h3 class="text-sm font-bold font-heading text-slate-900">Kedisiplinan Asistensi &amp; Progres PKL</h3>
          <p class="text-[11px] text-slate-500">Konsistensi mahasiswa dalam berkonsultasi selama periode magang.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 hover:border-purple-300 transition-colors">
          <label class="block text-xs font-bold text-slate-800 mb-1">8. Rutinitas Bimbingan (Min. 8 Sesi)</label>
          <p class="text-[10px] text-slate-500 mb-2">Kepatuhan jadwal konsultasi berkala dengan dosen pembimbing.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_rutinitas_bimbingan" required value="{{ old('skor_rutinitas_bimbingan', $evalF6?->skor_rutinitas_bimbingan ?? 95) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-purple-800 p-2 text-center focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
        </div>

        <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 hover:border-purple-300 transition-colors">
          <label class="block text-xs font-bold text-slate-800 mb-1">9. Progres Capaian Magang</label>
          <p class="text-[10px] text-slate-500 mb-2">Kecepatan revisi dan ketuntasan target kompetensi akademik.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_progres" required value="{{ old('skor_progres', $evalF6?->skor_progres ?? 88) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-black text-purple-800 p-2 text-center focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
        </div>
      </div>
    </div>

    <!-- SECTION D: CATATAN DOSEN -->
    <div class="space-y-2">
      <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
        D. Catatan Evaluasi &amp; Arahan Penyempurnaan Akhir
      </label>
      <textarea name="catatan_dosen" rows="3" placeholder="Contoh: Naskah laporan tersusun rapi dengan justifikasi arsitektur yang kuat. Pertahankan etika profesional di dunia kerja." class="w-full text-xs rounded-xl border-slate-200 p-3.5 focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">{{ old('catatan_dosen', $evalF6?->catatan_dosen) }}</textarea>
    </div>

    <!-- Submit Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
      <div class="flex items-center gap-2 text-xs text-slate-500">
        <span class="material-symbols-outlined text-[16px] text-[#0056B3]">verified_user</span>
        <span>Nilai Form F6 akan tersimpan ke database akademik JTIK PNJ.</span>
      </div>

      <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#0056B3] to-[#02796B] hover:from-[#00428A] hover:to-[#016054] text-white font-bold text-xs flex items-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[18px]">save</span>
        <span>Simpan Nilai Form F6 (Bobot 40%)</span>
      </button>
    </div>
  </form>
</div>

<!-- SECTION FUZZY LOGIC PYTHON ENGINE (PRD 5.4.4 & 9) -->
<div class="bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 rounded-2xl p-6 sm:p-7 text-white shadow-xl border border-teal-500/30 space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
    <div>
      <div class="flex items-center gap-2 text-teal-300 text-xs font-bold font-mono uppercase tracking-wider mb-1">
        <span class="material-symbols-outlined text-[16px]">terminal</span>
        <span>Engine Fuzzy Logic Tsukamoto / Mamdani (Python Runtime)</span>
      </div>
      <h3 class="text-lg font-bold font-heading text-white">Kalkulasi Skor Akhir Magang Mahasiswa</h3>
      <p class="text-xs text-slate-300 mt-1 max-w-xl">
        Algoritma inferensi fuzzy mengolah variabel Form F5 Industri (bobot 60%) dan Form F6 Dosen (bobot 40%), menghasilkan nilai numerik format rasio resmi skala 50 (contoh: <code>43.2/50</code>) serta persentase konversi (PRD 8.4).
      </p>
    </div>

    @if($selectedPengajuan->nilai_akhir_fuzzy)
      <div class="bg-white/10 backdrop-blur-sm border border-emerald-400/40 px-5 py-3 rounded-2xl text-center">
        <span class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider block">Skor Akhir Fuzzy</span>
        <div class="text-2xl font-black font-heading text-emerald-300">{{ $selectedPengajuan->nilai_akhir_fuzzy }}</div>
        <span class="text-[10px] text-slate-300 font-semibold">{{ $selectedPengajuan->fuzzy_percentage ?? '86.4' }}% (Grade A / Sangat Memuaskan)</span>
      </div>
    @endif
  </div>

  <!-- Parameter Status Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
    <div class="bg-white/5 border border-white/10 p-3.5 rounded-xl">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">1. Form F5 (Mitra Industri - 60%):</span>
      @if($evalF5)
        <strong class="text-emerald-400 font-bold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[15px]">check_circle</span> Rata-Rata: {{ $evalF5->rata_rata_f5 }}/100
        </strong>
      @else
        <span class="text-rose-400 font-bold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[15px]">hourglass_empty</span> Belum Diisi Industri
        </span>
      @endif
    </div>

    <div class="bg-white/5 border border-white/10 p-3.5 rounded-xl">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">2. Form F6 (Dosen Pembimbing - 40%):</span>
      @if($evalF6)
        <strong class="text-emerald-400 font-bold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[15px]">check_circle</span> Rata-Rata: {{ $evalF6->rata_rata_f6 }}/100
        </strong>
      @else
        <span class="text-amber-300 font-bold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[15px]">edit_note</span> Menunggu Simpan Form F6
        </span>
      @endif
    </div>

    <div class="bg-white/5 border border-white/10 p-3.5 rounded-xl">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">3. Syarat 8 Sesi Bimbingan (F4):</span>
      @if($bimbingansCount >= 8)
        <strong class="text-emerald-400 font-bold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[15px]">verified</span> Terpenuhi ({{ $bimbingansCount }} Sesi)
        </strong>
      @else
        <span class="text-rose-400 font-bold flex items-center gap-1 mt-0.5">
          <span class="material-symbols-outlined text-[15px]">cancel</span> Belum Memenuhi ({{ $bimbingansCount }}/8 Sesi)
        </span>
      @endif
    </div>
  </div>

  <!-- Trigger Button -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
    <p class="text-xs text-slate-300">
      @if(!$canTransmitFuzzy)
        <span class="text-amber-300 font-bold flex items-center gap-1">
          <span class="material-symbols-outlined text-[14px]">info</span>
          Lengkapi Form F5, Form F6, dan minimal 8 sesi bimbingan F4 untuk mengaktifkan kalkulasi.
        </span>
      @else
        <span class="text-emerald-300 font-bold flex items-center gap-1">
          <span class="material-symbols-outlined text-[14px]">check_circle</span>
          Semua prasyarat terpenuhi. Anda dapat mengeksekusi kalkulasi Fuzzy Logic dan menerbitkan Form F7.
        </span>
      @endif
    </p>

    <form method="POST" action="{{ route('dosen.kalkulasi-fuzzy', $selectedPengajuan->id) }}">
      @csrf
      <button type="submit" {{ !$canTransmitFuzzy ? 'disabled' : '' }} class="px-6 py-2.5 rounded-xl {{ $canTransmitFuzzy ? 'bg-gradient-to-r from-teal-400 to-emerald-400 hover:from-teal-300 hover:to-emerald-300 text-slate-950 font-black cursor-pointer shadow-lg' : 'bg-slate-700 text-slate-400 cursor-not-allowed' }} text-xs flex items-center gap-2 transition-all">
        <span class="material-symbols-outlined text-[18px]">play_arrow</span>
        <span>Kalkulasi &amp; Transmit Nilai Fuzzy Logic</span>
      </button>
    </form>
  </div>
</div>
@endif

<!-- Script Real-time Live Average Calculation for F6 -->
<script>
  function calculateF6Average() {
    const inputs = document.querySelectorAll('.f6-score-input');
    let total = 0;
    let count = 0;
    inputs.forEach(input => {
      const val = parseFloat(input.value);
      if (!isNaN(val)) {
        total += val;
        count++;
      }
    });
    if (count > 0) {
      const avg = (total / count).toFixed(1);
      const display = document.getElementById('live-average-display');
      if (display) {
        display.innerText = avg;
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.f6-score-input');
    inputs.forEach(input => {
      input.addEventListener('input', calculateF6Average);
    });
    calculateF6Average();
  });
</script>
@endsection
