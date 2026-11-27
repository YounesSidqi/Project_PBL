@extends('layouts.app', [
  'title' => 'Form F5: Evaluasi Industri - SIM-PKL',
  'pageTitle' => 'Form F5: Evaluasi Industri',
  'breadcrumbParent' => 'Mitra Industri',
  'activePage' => 'industri_f5'
])

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="text-xl font-bold font-heading text-slate-900">Form F5: Evaluasi Kinerja Industri</h2>
    <p class="text-xs text-slate-500 mt-0.5">Penilaian Kriteria Unjuk Kerja (KUK) mahasiswa oleh pembimbing lapangan (bobot 40%).</p>
  </div>

  <div class="flex items-center gap-2">
    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold">
      <span class="material-symbols-outlined text-[15px]">lock</span>
      <span>Nilai Rahasia</span>
    </span>

    @if($selectedPengajuan && $evaluasiF5)
      <a href="{{ route('dokumen.f5', $selectedPengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">print</span>
        <span>Cetak PDF</span>
      </a>
    @endif
  </div>
</div>

@if(!$selectedPengajuan)
  <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 p-6 shadow-sm">
    <span class="material-symbols-outlined text-3xl text-slate-300 mb-2 block">groups</span>
    <h3 class="text-sm font-bold text-slate-800">Belum Ada Mahasiswa Magang</h3>
    <p class="text-xs text-slate-500 mt-1">Belum ada mahasiswa yang ditempatkan pada perusahaan Anda.</p>
  </div>
@else

<!-- Pemilih Mahasiswa -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-3.5">
    <div class="w-11 h-11 rounded-xl bg-[#02796B] text-white flex items-center justify-center font-bold text-sm shrink-0">
      {{ strtoupper(substr($selectedPengajuan->mahasiswa->name, 0, 2)) }}
    </div>
    <div class="min-w-0">
      <h3 class="text-sm font-bold text-slate-900 truncate">{{ $selectedPengajuan->mahasiswa->name }}</h3>
      <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5 flex-wrap">
        <span class="font-mono font-semibold text-slate-700">NIM: {{ $selectedPengajuan->mahasiswa->identifier }}</span>
        <span>•</span>
        <span>{{ $selectedPengajuan->posisi_magang ?: 'Peserta PKL' }}</span>
      </div>
    </div>
  </div>

  <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
    @if($pengajuans->count() > 1)
      <select onchange="window.location.href='{{ route('form.f5.industri') }}?student_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-200 text-xs font-semibold bg-slate-50 text-slate-700">
        @foreach($pengajuans as $p)
          <option value="{{ $p->mahasiswa_id }}" {{ $selectedPengajuan->id === $p->id ? 'selected' : '' }}>
            {{ $p->mahasiswa->name }} ({{ $p->mahasiswa->identifier }}) {{ $p->evaluasiF5 ? '✓' : '' }}
          </option>
        @endforeach
      </select>
    @endif

    <div class="px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-center shrink-0">
      <span class="text-[10px] text-slate-400 font-bold uppercase block">Status</span>
      @if($evaluasiF5)
        <span class="text-xs font-bold text-emerald-700">Tersimpan</span>
      @else
        <span class="text-xs font-bold text-amber-700">Belum Diisi</span>
      @endif
    </div>
  </div>
</div>

<!-- Form Evaluasi -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
    <div>
      <h3 class="text-base font-bold font-heading text-slate-900">Rubrik Penilaian KUK Industri (Total Maksimal 100 | Bobot 40%)</h3>
      <p class="text-xs text-slate-500 mt-0.5">Rentang nilai setiap Kriteria Unjuk Kerja (KUK) adalah <strong>5 – 10</strong> (Softskill maks 50 + Hardskill maks 50).</p>
    </div>

    <div class="bg-teal-50/80 border border-teal-200 px-4 py-2.5 rounded-xl text-center min-w-[150px]">
      <span class="text-[10px] text-teal-800 uppercase font-bold block">Total Skor F5</span>
      <div class="flex items-baseline justify-center gap-1">
        <span id="live-average-display" class="text-2xl font-black font-heading text-teal-800">
          {{ $evaluasiF5 ? number_format($evaluasiF5->rata_rata_f5, 1) : '88.0' }}
        </span>
        <span class="text-xs font-bold text-slate-500">/ 100</span>
      </div>
    </div>
  </div>

  <form method="POST" action="{{ route('industri.evaluasi_f5.store', $selectedPengajuan->id) }}" id="form-f5-evaluation" class="space-y-6">
    @csrf

    <!-- A. Softskills -->
    <div class="space-y-3">
      <div class="flex items-center justify-between">
        <h4 class="text-xs font-bold uppercase tracking-wider text-[#02796B]">A. Softskill (KUK 1.1 – 1.5 | Rentang Poin: 5 – 10 | Maks: 50)</h4>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">1.1 Komunikasi</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_komunikasi" required value="{{ old('skor_komunikasi', ($evaluasiF5 && $evaluasiF5->skor_komunikasi <= 10) ? $evaluasiF5->skor_komunikasi : 9) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-teal-800 p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">1.2 Berpikir Kritis</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_berpikir_kritis" required value="{{ old('skor_berpikir_kritis', ($evaluasiF5 && $evaluasiF5->skor_berpikir_kritis <= 10) ? $evaluasiF5->skor_berpikir_kritis : 8.5) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-teal-800 p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">1.3 Kerjasama Tim</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_kerjasama" required value="{{ old('skor_kerjasama', ($evaluasiF5 && $evaluasiF5->skor_kerjasama <= 10) ? $evaluasiF5->skor_kerjasama : 9) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-teal-800 p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">1.4 Inisiatif</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_inisiatif" required value="{{ old('skor_inisiatif', ($evaluasiF5 && $evaluasiF5->skor_inisiatif <= 10) ? $evaluasiF5->skor_inisiatif : 9) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-teal-800 p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">1.5 Literasi &amp; Etika</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_literasi" required value="{{ old('skor_literasi', ($evaluasiF5 && $evaluasiF5->skor_literasi <= 10) ? $evaluasiF5->skor_literasi : 8.5) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-teal-800 p-2 text-center">
        </div>
      </div>
    </div>

    <!-- B. Hardskills -->
    <div class="space-y-3">
      <div class="flex items-center justify-between">
        <h4 class="text-xs font-bold uppercase tracking-wider text-[#0056B3]">B. Hardskill (KUK 2.1 – 2.5 | Rentang Poin: 5 – 10 | Maks: 50)</h4>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">2.1 Deskripsi Produk</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_deskripsi_produk" required value="{{ old('skor_deskripsi_produk', ($evaluasiF5 && $evaluasiF5->skor_deskripsi_produk <= 10) ? $evaluasiF5->skor_deskripsi_produk : 9) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-[#0056B3] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">2.2 Spesifikasi Kebutuhan</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_spesifikasi" required value="{{ old('skor_spesifikasi', ($evaluasiF5 && $evaluasiF5->skor_spesifikasi <= 10) ? $evaluasiF5->skor_spesifikasi : 9) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-[#0056B3] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">2.3 Desain Arsitektur</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_desain" required value="{{ old('skor_desain', ($evaluasiF5 && $evaluasiF5->skor_desain <= 10) ? $evaluasiF5->skor_desain : 8.5) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-[#0056B3] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">2.4 Implementasi Teknis</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_implementasi" required value="{{ old('skor_implementasi', ($evaluasiF5 && $evaluasiF5->skor_implementasi <= 10) ? $evaluasiF5->skor_implementasi : 9) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-[#0056B3] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1">2.5 Pengujian / QA</label>
          <span class="block text-[10px] text-slate-400 mb-1.5">Rentang: 5 – 10</span>
          <input type="number" step="0.5" min="5" max="10" name="skor_pengujian" required value="{{ old('skor_pengujian', ($evaluasiF5 && $evaluasiF5->skor_pengujian <= 10) ? $evaluasiF5->skor_pengujian : 8.5) }}" class="f5-score-input w-full text-sm rounded-lg border border-slate-300 bg-white font-bold text-[#0056B3] p-2 text-center">
        </div>
      </div>
    </div>

    <!-- C. Catatan Industri -->
    <div>
      <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
        C. Catatan &amp; Rekomendasi Industri
      </label>
      <textarea name="saran_industri" rows="3" placeholder="Tuliskan evaluasi atau rekomendasi untuk mahasiswa..." class="w-full text-xs rounded-xl border border-slate-300 p-3 focus:ring-2 focus:ring-[#02796B]/20 focus:border-[#02796B]">{{ old('saran_industri', $evaluasiF5?->saran_industri) }}</textarea>
    </div>

    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
      @if($evaluasiF5)
        <a href="{{ route('dokumen.f5', $selectedPengajuan->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
          <span class="material-symbols-outlined text-[16px]">print</span>
          <span>Cetak PDF</span>
        </a>
      @endif

      <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#02796B] hover:bg-[#016054] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
        <span class="material-symbols-outlined text-[16px]">save</span>
        <span>Simpan Nilai F5</span>
      </button>
    </div>
  </form>
</div>
@endif

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
      const display = document.getElementById('live-average-display');
      if (display) {
        display.innerText = total.toFixed(1);
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
