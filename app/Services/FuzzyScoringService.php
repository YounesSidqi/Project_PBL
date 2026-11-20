<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class FuzzyScoringService
{
    /**
     * Jalankan inferensi Fuzzy Logic Metode Sugeno (Takagi-Sugeno-Kang Orde-1) menggunakan external Python script.
     * F5: Bobot 60% (Evaluasi Industri)
     * F6: Bobot 40% (Evaluasi Akademik Dosen)
     *
     * @param  float  $f5Score  Nilai Form F5 Industri (0-100)
     * @param  float  $f6Score  Nilai Form F6 Dosen (0-100)
     * @return array{score_num: float, max_score: int, display: string, percentage: float, method: string, status: string}
     */
    public function calculate(float $f5Score, float $f6Score): array
    {
        $f5 = max(0.0, min(100.0, $f5Score));
        $f6 = max(0.0, min(100.0, $f6Score));

        $scriptPath = base_path('scripts/compute_fuzzy.py');

        // Gunakan Python 3 eksternal
        $process = new Process([
            'python3',
            $scriptPath,
            "--f5={$f5}",
            "--f6={$f6}",
        ]);

        $process->setTimeout(10);
        $process->run();

        if ($process->isSuccessful()) {
            $output = json_decode($process->getOutput(), true);
            if (is_array($output) && isset($output['score_num'])) {
                return [
                    'score_num' => (float) $output['score_num'],
                    'max_score' => (int) ($output['max_score'] ?? 50),
                    'display' => (string) ($output['display'] ?? "{$output['score_num']}/50"),
                    'percentage' => (float) ($output['percentage'] ?? round(($output['score_num'] / 50) * 100, 1)),
                    'method' => (string) ($output['method'] ?? 'Fuzzy Sugeno'),
                    'status' => 'success',
                ];
            }
        }

        // Logging warning jika proses python gagal
        Log::warning('Fuzzy Sugeno Python engine process failed or returned invalid JSON. Using fallback pure-PHP Sugeno calculation.', [
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
        ]);

        return $this->fallbackSugeno($f5, $f6);
    }

    /**
     * Fallback kalkulasi Fuzzy Sugeno (Orde-1) terintegrasi langsung di PHP jika Python process tidak tersedia.
     *
     * @return array{score_num: float, max_score: int, display: string, percentage: float, method: string, status: string}
     */
    protected function fallbackSugeno(float $f5, float $f6): array
    {
        $f5Mem = $this->fuzzifyScore($f5);
        $f6Mem = $this->fuzzifyScore($f6);

        $rules = [
            ['kurang', 'kurang', 0.25, 0.15, -2.0],
            ['kurang', 'cukup', 0.26, 0.18, -1.5],
            ['kurang', 'baik', 0.27, 0.20, -1.0],
            ['kurang', 'sangat_baik', 0.28, 0.21, -0.5],
            ['cukup', 'kurang', 0.28, 0.16, -1.0],
            ['cukup', 'cukup', 0.29, 0.19, -0.5],
            ['cukup', 'baik', 0.30, 0.20, 0.0],
            ['cukup', 'sangat_baik', 0.30, 0.21, 0.2],
            ['baik', 'kurang', 0.29, 0.17, -0.5],
            ['baik', 'cukup', 0.30, 0.19, 0.0],
            ['baik', 'baik', 0.30, 0.20, 0.5],
            ['baik', 'sangat_baik', 0.30, 0.20, 0.8],
            ['sangat_baik', 'kurang', 0.30, 0.18, 0.0],
            ['sangat_baik', 'cukup', 0.30, 0.20, 0.3],
            ['sangat_baik', 'baik', 0.30, 0.20, 0.8],
            ['sangat_baik', 'sangat_baik', 0.30, 0.20, 1.0],
        ];

        $totalAlpha = 0.0;
        $weightedZSum = 0.0;

        foreach ($rules as [$f5Cat, $f6Cat, $p, $q, $r]) {
            $alpha = min($f5Mem[$f5Cat], $f6Mem[$f6Cat]);
            if ($alpha > 0.0001) {
                $z = max(0.0, min(50.0, ($p * $f5) + ($q * $f6) + $r));
                $totalAlpha += $alpha;
                $weightedZSum += $alpha * $z;
            }
        }

        // Defuzzifikasi Sugeno (Weighted Average: Z = sum(alpha_i * z_i) / sum(alpha_i))
        $rawScore = $totalAlpha > 0.0
            ? ($weightedZSum / $totalAlpha)
            : ((0.30 * $f5) + (0.20 * $f6));

        $scoreNum = max(0.0, min(50.0, round($rawScore, 1)));
        $display = (floor($scoreNum) == $scoreNum ? (int) $scoreNum : $scoreNum).'/50';
        $percentage = round(($scoreNum / 50.0) * 100.0, 1);

        return [
            'score_num' => $scoreNum,
            'max_score' => 50,
            'display' => $display,
            'percentage' => $percentage,
            'method' => 'Fuzzy Sugeno',
            'status' => 'success_fallback',
        ];
    }

    /**
     * Fuzzifikasi nilai input (0-100) ke dalam 4 himpunan fuzzy.
     *
     * @return array{kurang: float, cukup: float, baik: float, sangat_baik: float}
     */
    protected function fuzzifyScore(float $score): array
    {
        return [
            'kurang' => $this->trapezoid($score, 0.0, 0.0, 50.0, 65.0),
            'cukup' => $this->trapezoid($score, 50.0, 65.0, 65.0, 80.0),
            'baik' => $this->trapezoid($score, 65.0, 80.0, 80.0, 92.0),
            'sangat_baik' => $this->trapezoid($score, 80.0, 92.0, 100.0, 100.0),
        ];
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
