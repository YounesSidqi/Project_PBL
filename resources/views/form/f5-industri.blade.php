@extends('layouts.app', [
  'title' => 'Form F5: Evaluasi Kinerja Industri - SIM-PKL TIK Politeknik Negeri Jakarta',
  'pageTitle' => 'Form F5: Evaluasi Industri',
  'breadcrumbParent' => 'Portal Mitra DUDI',
  'activePage' => 'industri_f5'
])

@section('content')
<!-- Header & Context -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
  <div>
    <div class="flex items-center gap-2 text-slate-500 text-xs mb-1">
      <span>Portal Mitra DUDI</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span>Kurikulum PKL TIK</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#02796B] font-semibold">Form F5 (Evaluasi Rahasia Industri)</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 tracking-tight">
      Form F5: Evaluasi Nilai Kinerja Mahasiswa di Industri
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Penilaian kinerja magang oleh Pembimbing Industri dengan bobot <strong>60%</strong> pada nilai akhir mahasiswa.
    </p>
  </div>

  <div class="flex items-center gap-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold shadow-sm">
      <span class="material-symbols-outlined text-[16px]">lock</span>
      <span>Confidential Grade (PRD 11.1)</span>
    </span>

    @if($selectedPengajuan && $evaluasiF5)
      <a href="{{ route('dokumen.f5', $selectedPengajuan->id) }}" target="_blank" class="h-10 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs sm:text-sm transition-colors flex items-center gap-2 shadow-sm">
        <span class="material-symbols-outlined text-[18px]">print</span>
        <span>Cetak Form F5</span>
      </a>
    @endif
  </div>
</div>

@if(!$selectedPengajuan)
  <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 p-8 shadow-sm">
    <div class="w-16 h-16 rounded-2xl bg-teal-50 text-[#02796B] flex items-center justify-center mx-auto mb-4">
      <span class="material-symbols-outlined text-3xl">groups</span>
    </div>
    <h3 class="text-base font-bold text-slate-800 font-heading">Belum Ada Mahasiswa Bimbingan</h3>
    <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
      Saat ini belum ada mahasiswa yang ditempatkan di instansi Anda. Admin Jurusan akan mengalokasikan penempatan mahasiswa secara resmi.
    </p>
  </div>
@else

<!-- Student Selector Bar -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#02796B] to-[#0056B3] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
      {{ strtoupper(substr($selectedPengajuan->mahasiswa->name, 0, 2)) }}
    </div>
    <div class="min-w-0">
      <span class="text-[10px] font-bold uppercase tracking-wider text-[#02796B] block">Mahasiswa yang Dinilai:</span>
      <h3 class="text-base font-bold text-slate-900 font-heading truncate">{{ $selectedPengajuan->mahasiswa->name }}</h3>
      <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
        <span class="font-mono font-semibold text-slate-700">NIM: {{ $selectedPengajuan->mahasiswa->identifier }}</span>
        <span>•</span>
        <span>{{ $selectedPengajuan->posisi_magang ?: 'Intern' }}</span>
        <span>•</span>
        <span class="text-slate-600 font-medium">{{ $selectedPengajuan->industri->nama_perusahaan ?? 'Perusahaan Mitra' }}</span>
      </div>
    </div>
  </div>

  <div class="flex items-center gap-3">
    @if($pengajuans->count() > 1)
      <div class="flex items-center gap-2">
        <label class="text-xs font-bold text-slate-500 uppercase">Pilih Mahasiswa:</label>
        <select onchange="window.location.href='{{ route('form.f5.industri') }}?pengajuan_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-300 text-xs font-semibold bg-slate-50 text-slate-700 focus:ring-2 focus:ring-[#02796B]/20">
          @foreach($pengajuans as $p)
            <option value="{{ $p->id }}" {{ $selectedPengajuan->id === $p->id ? 'selected' : '' }}>
              {{ $p->mahasiswa->name }} ({{ $p->mahasiswa->identifier }}) {{ $p->evaluasiF5 ? '✓ Terisi' : '• Belum' }}
            </option>
          @endforeach
        </select>
      </div>
    @endif

    <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-center">
      <span class="text-[10px] text-slate-400 font-bold uppercase block">Status Form F5</span>
      @if($evaluasiF5)
        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700">
          <span class="material-symbols-outlined text-[13px]">verified</span> Nilai Tersimpan
        </span>
      @else
        <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700">
          <span class="material-symbols-outlined text-[13px]">pending</span> Menunggu Input
        </span>
      @endif
    </div>
  </div>
</div>

<!-- Evaluation Form Card -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-card space-y-6">
  <!-- Top Banner & Real-time Live Average -->
  <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-6 border-b border-slate-100">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
          Tertutup / Rahasia
        </span>
        <span class="text-xs font-bold text-slate-500">• 10 Parameter Mutu (Skala 0 - 100)</span>
      </div>
      <h2 class="text-xl font-bold font-heading text-slate-900">
        Rubrik Evaluasi Kinerja Lapangan Mahasiswa
      </h2>
      <p class="text-xs text-slate-500 mt-1 max-w-2xl">
        Nilai Form F5 bersifat tertutup dan tersimpan aman di database. Mahasiswa tidak dapat melihat rincian angka per indikator secara mandiri, melainkan digabung ke dalam inferensi Fuzzy Logic (PRD 11.1).
      </p>
    </div>

    <!-- Live Average Score Preview -->
    <div class="bg-gradient-to-tr from-teal-50 to-emerald-50 border border-teal-200/80 p-4 rounded-2xl text-center min-w-[180px] shadow-sm">
      <span class="text-[10px] text-teal-800 uppercase font-black tracking-wider block">Skor Rata-Rata F5</span>
      <div class="flex items-baseline justify-center gap-1 mt-0.5">
        <span id="live-average-display" class="text-3xl font-black font-heading text-teal-800">
          {{ $evaluasiF5 ? number_format($evaluasiF5->rata_rata_f5, 1) : '87.5' }}
        </span>
        <span class="text-xs font-bold text-slate-500">/ 100</span>
      </div>
      <span class="text-[10px] text-teal-700 font-semibold block mt-0.5">Bobot 60% Nilai Akhir</span>
    </div>
  </div>

  <form method="POST" action="{{ route('industri.evaluasi_f5.store', $selectedPengajuan->id) }}" id="form-f5-evaluation" class="space-y-8">
    @csrf

    <!-- SECTION A: SOFTSKILLS (5 Parameter) -->
    <div class="space-y-4">
      <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
        <div class="w-7 h-7 rounded-lg bg-teal-100 text-[#02796B] flex items-center justify-center font-bold text-xs">
          A
        </div>
        <div>
          <h3 class="text-sm font-bold font-heading text-slate-900">Komponen Softskills &amp; Etika Profesi</h3>
          <p class="text-[11px] text-slate-500">Sikap kerja, adaptasi, komunikasi, dan inisiatif profesional di tempat magang.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
        <!-- 1. Komunikasi -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">1. Komunikasi</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Penyampaian gagasan, presentasi, dan responsivitas.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_komunikasi" required value="{{ old('skor_komunikasi', $evaluasiF5?->skor_komunikasi ?? 85) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-teal-800 p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 2. Berpikir Kritis -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">2. Berpikir Kritis</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Analisis masalah, troubleshooting, dan penalaran logika.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_berpikir_kritis" required value="{{ old('skor_berpikir_kritis', $evaluasiF5?->skor_berpikir_kritis ?? 85) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-teal-800 p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 3. Kerjasama Tim -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">3. Kerjasama Tim</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Kolaborasi dengan divisi lain dan rekan sekerja.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_kerjasama" required value="{{ old('skor_kerjasama', $evaluasiF5?->skor_kerjasama ?? 90) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-teal-800 p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 4. Inisiatif -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">4. Inisiatif</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Proaktif mencari solusi tanpa harus selalu didikte.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_inisiatif" required value="{{ old('skor_inisiatif', $evaluasiF5?->skor_inisiatif ?? 85) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-teal-800 p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>

        <!-- 5. Literasi Digital -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">5. Literasi &amp; Etika</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Kerahasiaan data perusahaan, SOP, dan etika kerja.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_literasi" required value="{{ old('skor_literasi', $evaluasiF5?->skor_literasi ?? 88) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-teal-800 p-2 text-center focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">
        </div>
      </div>
    </div>

    <!-- SECTION B: HARDSKILLS (5 Parameter) -->
    <div class="space-y-4">
      <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
        <div class="w-7 h-7 rounded-lg bg-blue-100 text-[#0056B3] flex items-center justify-center font-bold text-xs">
          B
        </div>
        <div>
          <h3 class="text-sm font-bold font-heading text-slate-900">Komponen Hardskills &amp; Capaian Teknis (Backlog F2)</h3>
          <p class="text-[11px] text-slate-500">Mutu deliverables, kepatuhan arsitektur, dan ketepatan spesifikasi pekerjaan.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
        <!-- 6. Deskripsi Produk -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">6. Deskripsi Produk</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Pemahaman sistemik atas platform/modul yang dikembangkan.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_deskripsi_produk" required value="{{ old('skor_deskripsi_produk', $evaluasiF5?->skor_deskripsi_produk ?? 90) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- 7. Spesifikasi Kebutuhan -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">7. Spesifikasi Kebutuhan</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Penyelesaian backlog tugas Form F2 sesuai requirement.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_spesifikasi" required value="{{ old('skor_spesifikasi', $evaluasiF5?->skor_spesifikasi ?? 88) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- 8. Desain Arsitektur -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">8. Desain Arsitektur</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Struktur code, skema database, atau diagram arsitektur.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_desain" required value="{{ old('skor_desain', $evaluasiF5?->skor_desain ?? 86) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- 9. Implementasi Teknis -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">9. Implementasi Teknis</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Kualitas baris kode, deployment, performa, dan reliability.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_implementasi" required value="{{ old('skor_implementasi', $evaluasiF5?->skor_implementasi ?? 90) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>

        <!-- 10. Pengujian & Debugging -->
        <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 hover:border-blue-300 transition-colors">
          <label class="block text-[11px] font-bold text-slate-800 mb-1">10. Pengujian / QA</label>
          <p class="text-[10px] text-slate-500 mb-2 line-clamp-2">Test cases, unit testing, pencatatan bug, dan verifikasi fitur.</p>
          <input type="number" step="0.5" min="0" max="100" name="skor_pengujian" required value="{{ old('skor_pengujian', $evaluasiF5?->skor_pengujian ?? 88) }}" class="f5-score-input w-full text-sm rounded-lg border-slate-300 font-black text-[#0056B3] p-2 text-center focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">
        </div>
      </div>
    </div>

    <!-- SECTION C: SARAN & REKOMENDASI INDUSTRI -->
    <div class="space-y-2">
      <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
        C. Catatan Kualitatif, Apresiasi &amp; Rekomendasi Karir untuk Mahasiswa
      </label>
      <textarea name="saran_industri" rows="3" placeholder="Contoh: Mahasiswa menunjukkan kemampuan teknis yang sangat solid dalam containerization dan komunikasi yang santun. Direkomendasikan untuk bergabung sebagai talent engineer." class="w-full text-xs rounded-xl border-slate-200 p-3.5 focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">{{ old('saran_industri', $evaluasiF5?->saran_industri) }}</textarea>
    </div>

    <!-- Submit Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
      <div class="flex items-center gap-2 text-xs text-slate-500">
        <span class="material-symbols-outlined text-[16px] text-[#02796B]">verified_user</span>
        <span>Nilai otomatis terenkripsi dan disahkan atas nama akun Pembimbing Lapangan Anda.</span>
      </div>

      <div class="flex items-center gap-3">
        @if($evaluasiF5)
          <a href="{{ route('dokumen.f5', $selectedPengajuan->id) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors">
            <span class="material-symbols-outlined text-[16px]">print</span>
            <span>Cetak PDF Form F5</span>
          </a>
        @endif

        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#02796B] to-[#0056B3] hover:from-[#016054] hover:to-[#00428A] text-white font-bold text-xs flex items-center gap-2 shadow-md transition-all">
          <span class="material-symbols-outlined text-[18px]">save</span>
          <span>Simpan Evaluasi Form F5 (Bobot 60%)</span>
        </button>
      </div>
    </div>
  </form>
</div>
@endif

<!-- Script Real-time Live Average Calculation -->
<script>
  function calculateF5Average() {
    const inputs = document.querySelectorAll('.f5-score-input');
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
    const inputs = document.querySelectorAll('.f5-score-input');
    inputs.forEach(input => {
      input.addEventListener('input', calculateF5Average);
    });
    calculateF5Average();
  });
</script>
@endsection
