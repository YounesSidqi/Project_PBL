@extends('layouts.app', [
  'title' => 'Form F6: Evaluasi Akademik - SIM-PKL',
  'pageTitle' => 'Form F6: Evaluasi Akademik',
  'breadcrumbParent' => 'Dosen Pembimbing',
  'activePage' => 'f6'
])

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="text-xl font-bold font-heading text-slate-900">Form F6: Evaluasi Akademik Dosen</h2>
    <p class="text-xs text-slate-500 mt-0.5">Penilaian laporan, presentasi, dan bimbingan mahasiswa (bobot 40%).</p>
  </div>

  <div class="flex items-center gap-2">
    @if($selectedPengajuan && $evalF6)
      <a href="{{ route('dokumen.f6', $selectedPengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">print</span>
        <span>Cetak F6</span>
      </a>
    @endif

    @if($selectedPengajuan && $selectedPengajuan->nilai_akhir_fuzzy)
      <a href="{{ route('dokumen.f7', $selectedPengajuan->id) }}" target="_blank" class="h-9 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">workspace_premium</span>
        <span>Cetak F7</span>
      </a>
    @endif
  </div>
</div>

@if(!$selectedPengajuan)
  <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 p-6 shadow-sm">
    <span class="material-symbols-outlined text-3xl text-slate-300 mb-2 block">school</span>
    <h3 class="text-sm font-bold text-slate-800">Belum Ada Mahasiswa Bimbingan</h3>
    <p class="text-xs text-slate-500 mt-1">Mahasiswa bimbingan akan ditetapkan oleh KPS.</p>
  </div>
@else

<!-- Pemilih Mahasiswa -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
  <div class="flex items-center gap-3.5">
    <div class="w-11 h-11 rounded-xl bg-[#0056B3] text-white flex items-center justify-center font-bold text-sm shrink-0">
      {{ strtoupper(substr($selectedPengajuan->mahasiswa->name, 0, 2)) }}
    </div>
    <div class="min-w-0">
      <h3 class="text-sm font-bold text-slate-900 truncate">{{ $selectedPengajuan->mahasiswa->name }}</h3>
      <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5 flex-wrap">
        <span class="font-mono font-semibold text-slate-700">NIM: {{ $selectedPengajuan->mahasiswa->identifier }}</span>
        <span>•</span>
        <span>{{ $selectedPengajuan->industri->nama_perusahaan ?? 'Perusahaan Mitra' }}</span>
      </div>
    </div>
  </div>

  <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
    @if($students->count() > 1)
      <select onchange="window.location.href='{{ route('form.f6.dosen') }}?pengajuan_id=' + this.value" class="h-9 px-3 rounded-xl border border-slate-200 text-xs font-semibold bg-slate-50 text-slate-700">
        @foreach($students as $st)
          <option value="{{ $st->id }}" {{ $selectedPengajuan->id === $st->id ? 'selected' : '' }}>
            {{ $st->mahasiswa->name }} ({{ $st->bimbingans->count() }}/8 Sesi) {{ $st->evaluasiF6 ? '✓' : '' }}
          </option>
        @endforeach
      </select>
    @endif

    <div class="px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-center shrink-0">
      <span class="text-[10px] text-slate-400 font-bold uppercase block">Bimbingan</span>
      <span class="text-xs font-extrabold {{ $bimbingansCount >= 8 ? 'text-emerald-600' : 'text-amber-700' }}">
        {{ $bimbingansCount }}/8 Sesi
      </span>
    </div>
  </div>
</div>

<!-- Info Prasyarat 8 Sesi -->
@if($bimbingansCount < 8)
  <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-amber-900">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-amber-600 text-[18px]">warning</span>
      <span>Mahasiswa baru menyelesaikan <strong>{{ $bimbingansCount }}/8 sesi bimbingan (Form F4)</strong>. Minimal 8 sesi diperlukan untuk kalkulasi nilai akhir.</span>
    </div>
    <a href="{{ route('form.f4.dosen', ['pengajuan_id' => $selectedPengajuan->id]) }}" class="font-bold text-amber-800 hover:underline shrink-0">
      Buka Form F4 →
    </a>
  </div>
@endif

<!-- Form Evaluasi F6 -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
    <div>
      <h3 class="text-base font-bold font-heading text-slate-900">Rubrik Evaluasi Akademik (Skala 0–100)</h3>
      <p class="text-xs text-slate-500 mt-0.5">Penilaian laporan PKL, penguasaan materi presentasi, dan kedisiplinan bimbingan.</p>
    </div>

    <div class="bg-blue-50/80 border border-blue-200 px-4 py-2.5 rounded-xl text-center min-w-[150px]">
      <span class="text-[10px] text-[#0056B3] uppercase font-bold block">Rata-Rata F6</span>
      <div class="flex items-baseline justify-center gap-1">
        <span id="live-average-display" class="text-2xl font-black font-heading text-[#0056B3]">
          {{ $evalF6 ? number_format($evalF6->rata_rata_f6, 1) : '88.0' }}
        </span>
        <span class="text-xs font-bold text-slate-500">/ 100</span>
      </div>
    </div>
  </div>

  <form method="POST" action="{{ route('dosen.evaluasi_f6.store', $selectedPengajuan->id) }}" id="form-f6-evaluation" class="space-y-6">
    @csrf

    <!-- A. Laporan PKL -->
    <div class="space-y-3">
      <h4 class="text-xs font-bold uppercase tracking-wider text-[#0056B3]">A. Naskah Laporan PKL (3 Indikator)</h4>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">1. Kesesuaian Judul &amp; Isi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_kesesuaian_judul" required value="{{ old('skor_kesesuaian_judul', $evalF6?->skor_kesesuaian_judul ?? 90) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#0056B3] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">2. Tata Bahasa &amp; EYD</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_tata_bahasa" required value="{{ old('skor_tata_bahasa', $evalF6?->skor_tata_bahasa ?? 85) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#0056B3] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">3. Kepatuhan Format</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_format_laporan" required value="{{ old('skor_format_laporan', $evalF6?->skor_format_laporan ?? 88) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#0056B3] p-2 text-center">
        </div>
      </div>
    </div>

    <!-- B. Presentasi & Ujian -->
    <div class="space-y-3">
      <h4 class="text-xs font-bold uppercase tracking-wider text-[#02796B]">B. Penguasaan Materi &amp; Presentasi (4 Indikator)</h4>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">4. Penguasaan Materi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_pengetahuan" required value="{{ old('skor_pengetahuan', $evalF6?->skor_pengetahuan ?? 90) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#02796B] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">5. Tanya Jawab</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_tanya_jawab" required value="{{ old('skor_tanya_jawab', $evalF6?->skor_tanya_jawab ?? 88) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#02796B] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">6. Etika &amp; Sikap</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_etika" required value="{{ old('skor_etika', $evalF6?->skor_etika ?? 95) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#02796B] p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">7. Justifikasi Solusi</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_justifikasi" required value="{{ old('skor_justifikasi', $evalF6?->skor_justifikasi ?? 86) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-[#02796B] p-2 text-center">
        </div>
      </div>
    </div>

    <!-- C. Rutinitas Bimbingan -->
    <div class="space-y-3">
      <h4 class="text-xs font-bold uppercase tracking-wider text-purple-700">C. Kedisiplinan Bimbingan &amp; Progres (2 Indikator)</h4>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">8. Rutinitas Bimbingan (Min. 8 Sesi)</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_rutinitas_bimbingan" required value="{{ old('skor_rutinitas_bimbingan', $evalF6?->skor_rutinitas_bimbingan ?? 95) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-purple-800 p-2 text-center">
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
          <label class="block text-xs font-bold text-slate-700 mb-1.5">9. Progres Capaian Magang</label>
          <input type="number" step="0.5" min="0" max="100" name="skor_progres" required value="{{ old('skor_progres', $evalF6?->skor_progres ?? 88) }}" class="f6-score-input w-full text-sm rounded-lg border-slate-300 font-bold text-purple-800 p-2 text-center">
        </div>
      </div>
    </div>

    <!-- D. Catatan Dosen -->
    <div>
      <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
        D. Catatan Evaluasi Dosen
      </label>
      <textarea name="catatan_dosen" rows="3" placeholder="Tuliskan catatan evaluasi atau revisi akhir laporan..." class="w-full text-xs rounded-xl border-slate-200 p-3 focus:ring-2 focus:ring-[#0056B3]/20 focus:border-[#0056B3]">{{ old('catatan_dosen', $evalF6?->catatan_dosen) }}</textarea>
    </div>

    <div class="flex items-center justify-end pt-3 border-t border-slate-100">
      <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0056B3] hover:bg-[#00428A] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
        <span class="material-symbols-outlined text-[16px]">save</span>
        <span>Simpan Nilai F6</span>
      </button>
    </div>
  </form>
</div>

<!-- Kalkulasi Skor Akhir Fuzzy Logic -->
<div class="bg-slate-900 rounded-2xl p-5 sm:p-6 text-white shadow-md space-y-4">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
    <div>
      <h3 class="text-base font-bold font-heading text-white">Kalkulasi Nilai Akhir (Fuzzy Logic)</h3>
      <p class="text-xs text-slate-300 mt-0.5">
        Penggabungan nilai F5 Industri (60%) dan F6 Dosen (40%) untuk menerbitkan nilai akhir Form F7.
      </p>
    </div>

    @if($selectedPengajuan->nilai_akhir_fuzzy)
      <div class="bg-white/10 border border-emerald-400/40 px-4 py-2 rounded-xl text-center shrink-0">
        <span class="text-[10px] text-emerald-300 font-bold uppercase block">Skor Akhir</span>
        <div class="text-xl font-black font-heading text-emerald-300">{{ $selectedPengajuan->nilai_akhir_fuzzy }}</div>
        <span class="text-[10px] text-slate-300">{{ $selectedPengajuan->fuzzy_percentage ?? '86.4' }}%</span>
      </div>
    @endif
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
    <div class="bg-white/5 border border-white/10 p-3 rounded-xl">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">1. Form F5 (Industri - 60%)</span>
      @if($evalF5)
        <strong class="text-emerald-400 font-bold block mt-0.5">Rata-Rata: {{ $evalF5->rata_rata_f5 }}/100</strong>
      @else
        <span class="text-rose-400 font-bold block mt-0.5">Belum Diisi</span>
      @endif
    </div>

    <div class="bg-white/5 border border-white/10 p-3 rounded-xl">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">2. Form F6 (Dosen - 40%)</span>
      @if($evalF6)
        <strong class="text-emerald-400 font-bold block mt-0.5">Rata-Rata: {{ $evalF6->rata_rata_f6 }}/100</strong>
      @else
        <span class="text-amber-300 font-bold block mt-0.5">Belum Disimpan</span>
      @endif
    </div>

    <div class="bg-white/5 border border-white/10 p-3 rounded-xl">
      <span class="text-slate-400 block text-[10px] uppercase font-bold">3. Bimbingan F4 (Min. 8)</span>
      @if($bimbingansCount >= 8)
        <strong class="text-emerald-400 font-bold block mt-0.5">Terpenuhi ({{ $bimbingansCount }} Sesi)</strong>
      @else
        <span class="text-rose-400 font-bold block mt-0.5">Belum Memenuhi ({{ $bimbingansCount }}/8)</span>
      @endif
    </div>
  </div>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
    <p class="text-xs text-slate-300">
      @if(!$canTransmitFuzzy)
        <span class="text-amber-300 font-semibold">Lengkapi nilai F5, F6, dan minimal 8 sesi bimbingan F4 untuk mengaktifkan kalkulasi.</span>
      @else
        <span class="text-emerald-300 font-semibold">Semua prasyarat terpenuhi. Nilai akhir siap dikalkulasi.</span>
      @endif
    </p>

    <form method="POST" action="{{ route('dosen.kalkulasi-fuzzy', $selectedPengajuan->id) }}">
      @csrf
      <button type="submit" {{ !$canTransmitFuzzy ? 'disabled' : '' }} class="px-5 py-2.5 rounded-xl {{ $canTransmitFuzzy ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold cursor-pointer' : 'bg-slate-700 text-slate-400 cursor-not-allowed' }} text-xs inline-flex items-center gap-1.5 transition-colors">
        <span class="material-symbols-outlined text-[16px]">calculate</span>
        <span>Hitung Nilai Akhir (F7)</span>
      </button>
    </form>
  </div>
</div>
@endif

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
