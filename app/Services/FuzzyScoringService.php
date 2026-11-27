<?php

namespace App\Services;

use App\Models\EvaluasiF5;
use App\Models\EvaluasiF6;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class FuzzyScoringService
{
    /**
     * Jalankan inferensi Fuzzy Logic Metode Sugeno Orde-1 berbasis rincian KUK per kategori
     * menggunakan external Python script (scripts/compute_fuzzy.py).
     *
     * Form F5 (Pembimbing DUDI - Bobot 40%, Total Maks 100):
     * - Softskill: 5 KUK (1.1 s.d. 1.5), rentang 5-10 (maks 50)
     * - Hardskill: 5 KUK (2.1 s.d. 2.5), rentang 5-10 (maks 50)
     *
     * Form F6 (Pembimbing PNJ - Bobot 60%, Total Maks 100):
     * - Pelaporan: 3 KUK (1.1 s.d. 1.3), rentang 5-10 (maks 30)
     * - Presentasi: 4 KUK (2.1 s.d. 2.4), rentang 5-10 (maks 40)
     * - Bimbingan: 2 KUK (3.1 s.d. 3.2), rentang 5-15 (maks 30)
     *
     * @param  EvaluasiF5|array<string, mixed>|float  $f5Input
     * @param  EvaluasiF6|array<string, mixed>|float  $f6Input
     * @return array{score_num: float, max_score: int, display: string, percentage: float, total_f5: float, total_f6: float, bobot_f5: int, bobot_f6: int, method: string, status: string}
     */
    public function calculate(EvaluasiF5|array|float $f5Input, EvaluasiF6|array|float $f6Input): array
    {
        $f5Payload = $this->buildF5Payload($f5Input);
        $f6Payload = $this->buildF6Payload($f6Input);

        $scriptPath = base_path('scripts/compute_fuzzy.py');

        $process = new Process([
            'python3',
            $scriptPath,
            '--f5-json='.json_encode($f5Payload),
            '--f6-json='.json_encode($f6Payload),
        ]);

        $process->setTimeout(10);
        $process->run();

        if ($process->isSuccessful()) {
            $output = json_decode($process->getOutput(), true);
            if (is_array($output) && isset($output['score_num'])) {
                $scoreNum = (float) $output['score_num'];

                return [
                    'score_num' => $scoreNum,
                    'max_score' => (int) ($output['max_score'] ?? 100),
                    'display' => (string) ($output['display'] ?? number_format($scoreNum, 1, '.', '').'/100'),
                    'percentage' => (float) ($output['percentage'] ?? $scoreNum),
                    'total_f5' => (float) ($output['total_f5'] ?? 0.0),
                    'total_f6' => (float) ($output['total_f6'] ?? 0.0),
                    'bobot_f5' => (int) ($output['bobot_f5'] ?? 40),
                    'bobot_f6' => (int) ($output['bobot_f6'] ?? 60),
                    'method' => (string) ($output['method'] ?? 'Fuzzy Sugeno Orde-1'),
                    'status' => 'success',
                ];
            }
        }

        Log::warning('Fuzzy Sugeno Python engine process failed or returned invalid JSON. Using fallback pure-PHP Sugeno calculation.', [
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
        ]);

        return $this->fallbackSugeno($f5Payload, $f6Payload);
    }

    /**
     * Bangun struktur KUK Form F5 (Softskill 1.1-1.5 [5-10], Hardskill 2.1-2.5 [5-10]).
     *
     * @param  EvaluasiF5|array<string, mixed>|float  $input
     * @return array{softskill: array<string, float>, hardskill: array<string, float>}
     */
    public function buildF5Payload(EvaluasiF5|array|float $input): array
    {
        if (is_array($input) && isset($input['softskill'], $input['hardskill'])) {
            return $input;
        }

        if ($input instanceof EvaluasiF5) {
            return [
                'softskill' => [
                    '1.1' => $this->normalizeKukScore((float) $input->skor_komunikasi, 5.0, 10.0),
                    '1.2' => $this->normalizeKukScore((float) $input->skor_berpikir_kritis, 5.0, 10.0),
                    '1.3' => $this->normalizeKukScore((float) $input->skor_kerjasama, 5.0, 10.0),
                    '1.4' => $this->normalizeKukScore((float) $input->skor_inisiatif, 5.0, 10.0),
                    '1.5' => $this->normalizeKukScore((float) $input->skor_literasi, 5.0, 10.0),
                ],
                'hardskill' => [
                    '2.1' => $this->normalizeKukScore((float) $input->skor_deskripsi_produk, 5.0, 10.0),
                    '2.2' => $this->normalizeKukScore((float) $input->skor_spesifikasi, 5.0, 10.0),
                    '2.3' => $this->normalizeKukScore((float) $input->skor_desain, 5.0, 10.0),
                    '2.4' => $this->normalizeKukScore((float) $input->skor_implementasi, 5.0, 10.0),
                    '2.5' => $this->normalizeKukScore((float) $input->skor_pengujian, 5.0, 10.0),
                ],
            ];
        }

        $val = is_numeric($input) ? $this->normalizeKukScore((float) $input, 5.0, 10.0) : 8.5;

        return [
            'softskill' => ['1.1' => $val, '1.2' => $val, '1.3' => $val, '1.4' => $val, '1.5' => $val],
            'hardskill' => ['2.1' => $val, '2.2' => $val, '2.3' => $val, '2.4' => $val, '2.5' => $val],
        ];
    }

    /**
     * Bangun struktur KUK Form F6 (Pelaporan 1.1-1.3 [5-10], Presentasi 2.1-2.4 [5-10], Bimbingan 3.1-3.2 [5-15]).
     *
     * @param  EvaluasiF6|array<string, mixed>|float  $input
     * @return array{pelaporan: array<string, float>, presentasi: array<string, float>, bimbingan: array<string, float>}
     */
    public function buildF6Payload(EvaluasiF6|array|float $input): array
    {
        if (is_array($input) && isset($input['pelaporan'], $input['presentasi'], $input['bimbingan'])) {
            return $input;
        }

        if ($input instanceof EvaluasiF6) {
            return [
                'pelaporan' => [
                    '1.1' => $this->normalizeKukScore((float) $input->skor_kesesuaian_judul, 5.0, 10.0),
                    '1.2' => $this->normalizeKukScore((float) $input->skor_tata_bahasa, 5.0, 10.0),
                    '1.3' => $this->normalizeKukScore((float) $input->skor_format_laporan, 5.0, 10.0),
                ],
                'presentasi' => [
                    '2.1' => $this->normalizeKukScore((float) $input->skor_pengetahuan, 5.0, 10.0),
                    '2.2' => $this->normalizeKukScore((float) $input->skor_tanya_jawab, 5.0, 10.0),
                    '2.3' => $this->normalizeKukScore((float) $input->skor_etika, 5.0, 10.0),
                    '2.4' => $this->normalizeKukScore((float) $input->skor_justifikasi, 5.0, 10.0),
                ],
                'bimbingan' => [
                    '3.1' => $this->normalizeKukScore((float) $input->skor_rutinitas_bimbingan, 5.0, 15.0),
                    '3.2' => $this->normalizeKukScore((float) $input->skor_progres, 5.0, 15.0),
                ],
            ];
        }

        $score = is_numeric($input) ? (float) $input : 85.0;
        $val10 = $this->normalizeKukScore($score, 5.0, 10.0);
        $val15 = $this->normalizeKukScore($score, 5.0, 15.0);

        return [
            'pelaporan' => ['1.1' => $val10, '1.2' => $val10, '1.3' => $val10],
            'presentasi' => ['2.1' => $val10, '2.2' => $val10, '2.3' => $val10, '2.4' => $val10],
            'bimbingan' => ['3.1' => $val15, '3.2' => $val15],
        ];
    }

    /**
     * Normalisasi nilai item ke rentang KUK [minPoint, maxPoint].
     * Jika nilai sudah berada di dalam [minPoint, maxPoint], gunakan langsung;
     * jika masih dalam skala 0-100 (> maxPoint), konversi secara proporsional ke [minPoint, maxPoint].
     */
    protected function normalizeKukScore(float $value, float $minPoint, float $maxPoint): float
    {
        if ($value >= $minPoint && $value <= $maxPoint) {
            return round($value, 2);
        }

        if ($value > $maxPoint) {
            $clamped100 = max(0.0, min(100.0, $value));
            $scaled = ($clamped100 / 100.0) * $maxPoint;

            return round(max($minPoint, min($maxPoint, $scaled)), 2);
        }

        return $minPoint;
    }

    /**
     * Fallback kalkulasi Fuzzy Sugeno Orde-1 per kategori di PHP jika Python process gagal.
     *
     * @param  array{softskill: array<string, float>, hardskill: array<string, float>}  $f5Payload
     * @param  array{pelaporan: array<string, float>, presentasi: array<string, float>, bimbingan: array<string, float>}  $f6Payload
     * @return array{score_num: float, max_score: int, display: string, percentage: float, total_f5: float, total_f6: float, bobot_f5: int, bobot_f6: int, method: string, status: string}
     */
    protected function fallbackSugeno(array $f5Payload, array $f6Payload): array
    {
        $softskillScore = $this->computeCategorySugeno(array_values($f5Payload['softskill']), 10.0);
        $hardskillScore = $this->computeCategorySugeno(array_values($f5Payload['hardskill']), 10.0);
        $totalF5 = max(0.0, min(100.0, round($softskillScore + $hardskillScore, 2)));

        $pelaporanScore = $this->computeCategorySugeno(array_values($f6Payload['pelaporan']), 10.0);
        $presentasiScore = $this->computeCategorySugeno(array_values($f6Payload['presentasi']), 10.0);
        $bimbinganScore = $this->computeCategorySugeno(array_values($f6Payload['bimbingan']), 15.0);
        $totalF6 = max(0.0, min(100.0, round($pelaporanScore + $presentasiScore + $bimbinganScore, 2)));

        $finalScore = max(0.0, min(100.0, round(($totalF5 * 0.40) + ($totalF6 * 0.60), 1)));
        $display = number_format($finalScore, 1, '.', '').'/100';

        return [
            'score_num' => $finalScore,
            'max_score' => 100,
            'display' => $display,
            'percentage' => $finalScore,
            'total_f5' => $totalF5,
            'total_f6' => $totalF6,
            'bobot_f5' => 40,
            'bobot_f6' => 60,
            'method' => 'Fuzzy Sugeno Orde-1',
            'status' => 'success_fallback',
        ];
    }

    /**
     * Hitung skor Fuzzy Sugeno Orde-1 untuk satu kategori KUK.
     *
     * @param  array<int, float>  $items
     */
    protected function computeCategorySugeno(array $items, float $maxPointPerItem): float
    {
        $rawCatScore = array_sum($items);
        $maxCatScore = count($items) * $maxPointPerItem;

        $p0 = 0.0;
        $p50 = 0.50 * $maxCatScore;
        $p65 = 0.65 * $maxCatScore;
        $p80 = 0.80 * $maxCatScore;
        $p92 = 0.92 * $maxCatScore;
        $p100 = 1.00 * $maxCatScore;

        $memberships = [
            'kurang' => $this->trapezoid($rawCatScore, $p0, $p0, $p50, $p65),
            'cukup' => $this->trapezoid($rawCatScore, $p50, $p65, $p65, $p80),
            'baik' => $this->trapezoid($rawCatScore, $p65, $p80, $p80, $p92),
            'sangat_baik' => $this->trapezoid($rawCatScore, $p80, $p92, $p100, $p100),
        ];

        $rules = [
            ['kurang', 0.92, -0.02 * $maxCatScore],
            ['cukup', 0.97, -0.01 * $maxCatScore],
            ['baik', 1.00, 0.01 * $maxCatScore],
            ['sangat_baik', 1.00, 0.02 * $maxCatScore],
        ];

        $totalAlpha = 0.0;
        $weightedZSum = 0.0;

        foreach ($rules as [$level, $p, $r]) {
            $alpha = $memberships[$level];
            if ($alpha > 0.0001) {
                $z = max(0.0, min($maxCatScore, ($p * $rawCatScore) + $r));
                $totalAlpha += $alpha;
                $weightedZSum += $alpha * $z;
            }
        }

        $defuzzified = $totalAlpha > 0.0 ? ($weightedZSum / $totalAlpha) : $rawCatScore;

        return max(0.0, min($maxCatScore, round($defuzzified, 2)));
    }

    protected function trapezoid(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a || $x >= $d) {
            return 0.0;
        }
        if ($x > $a && $x < $b) {
            return $b > $a ? ($x - $a) / ($b - $a) : 1.0;
        }
        if ($x >= $b && $x <= $c) {
            return 1.0;
        }
        if ($x > $c && $x < $d) {
            return $d > $c ? ($d - $x) / ($d - $c) : 1.0;
        }

        return 0.0;
    }
}
