<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class FuzzyScoringService
{
    /**
     * Jalankan inferensi Fuzzy Logic (Mamdani/Tsukamoto) menggunakan external Python script.
     * F5: Bobot 60% (Evaluasi Industri)
     * F6: Bobot 40% (Evaluasi Akademik Dosen)
     *
     * @param  float  $f5Score  Nilai Form F5 Industri (0-100)
     * @param  float  $f6Score  Nilai Form F6 Dosen (0-100)
     * @return array{score_num: float, max_score: int, display: string, percentage: float, status: string}
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
                    'status' => 'success',
                ];
            }
        }

        // Logging warning jika proses python gagal
        Log::warning('Fuzzy Python engine process failed or returned invalid JSON. Using fallback pure-PHP Tsukamoto calculation.', [
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
        ]);

        return $this->fallbackTsukamoto($f5, $f6);
    }

    /**
     * Fallback kalkulasi Tsukamoto Fuzzy terintegrasi langsung di PHP jika Python process tidak tersedia.
     */
    protected function fallbackTsukamoto(float $f5, float $f6): array
    {
        $weightedCrisp = (0.60 * $f5) + (0.40 * $f6);
        $scoreNum = round(($weightedCrisp / 100.0) * 50.0, 1);
        $display = (floor($scoreNum) == $scoreNum ? (int) $scoreNum : $scoreNum).'/50';
        $percentage = round(($scoreNum / 50.0) * 100.0, 1);

        return [
            'score_num' => $scoreNum,
            'max_score' => 50,
            'display' => $display,
            'percentage' => $percentage,
            'status' => 'success_fallback',
        ];
    }
}
