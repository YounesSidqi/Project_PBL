#!/usr/bin/env python3
"""
SIM-PKL JTIK PNJ 2026 - Fuzzy Logic Evaluation Engine
Mamdani / Tsukamoto Hybrid Inference Engine for Internship Final Scoring
F5: Pembimbing Industri (Bobot 60%)
F6: Dosen Pembimbing PNJ (Bobot 40%)
Output: Rasio numerik skala 50 (Contoh: "40/50")
"""

import sys
import json
import argparse

def trapezoid(x, a, b, c, d):
    if x <= a or x >= d:
        return 0.0
    if a < x < b:
        return (x - a) / (b - a) if b > a else 1.0
    if b <= x <= c:
        return 1.0
    if c < x < d:
        return (d - x) / (d - c) if d > c else 1.0
    return 0.0

def triangle(x, a, b, c):
    return trapezoid(x, a, b, b, c)

def fuzzify_score(score):
    """
    Kategori linguistik:
    - Kurang: [0, 0, 50, 65]
    - Cukup: [55, 65, 75]
    - Baik: [70, 80, 90]
    - Sangat Baik: [85, 92, 100, 100]
    """
    kurang = trapezoid(score, 0, 0, 50, 65)
    cukup = triangle(score, 55, 65, 75)
    baik = triangle(score, 70, 80, 90)
    sangat_baik = trapezoid(score, 85, 92, 100, 100)
    return {
        'kurang': kurang,
        'cukup': cukup,
        'baik': baik,
        'sangat_baik': sangat_baik
    }

def compute_fuzzy_score(f5_val, f6_val):
    # Clamp input values between 0 and 100
    f5 = max(0.0, min(100.0, float(f5_val)))
    f6 = max(0.0, min(100.0, float(f6_val)))

    # Weighted baseline: F5 has 60% weight, F6 has 40% weight
    weighted_crisp = (0.60 * f5) + (0.40 * f6)

    # Fuzzy membership for both components
    f5_mem = fuzzify_score(f5)
    f6_mem = fuzzify_score(f6)

    # Tsukamoto monotonic output rules on scale 0 - 50:
    # Rule 1: Kurang & Kurang -> Rendah (z = 20 - 10*(1-alpha))
    # Rule 2: (Kurang & Cukup) or (Cukup & Kurang) -> Rendah-Sedang
    # Rule 3: Cukup & Cukup -> Sedang (z ~ 32)
    # Rule 4: Baik & Cukup or Cukup & Baik -> Sedang-Tinggi (z ~ 38)
    # Rule 5: Baik & Baik -> Tinggi (z ~ 42)
    # Rule 6: Sangat Baik & Baik or Baik & Sangat Baik -> Tinggi-Optimal (z ~ 46)
    # Rule 7: Sangat Baik & Sangat Baik -> Sangat Tinggi (z = 48 - 50)
    
    rules = [
        # (alpha, target_z)
        (min(f5_mem['kurang'], f6_mem['kurang']), 15.0),
        (min(f5_mem['kurang'], f6_mem['cukup']), 22.0),
        (min(f5_mem['cukup'], f6_mem['kurang']), 20.0),
        (min(f5_mem['cukup'], f6_mem['cukup']), 32.5),
        (min(f5_mem['cukup'], f6_mem['baik']), 36.5),
        (min(f5_mem['baik'], f6_mem['cukup']), 38.0),
        (min(f5_mem['baik'], f6_mem['baik']), 42.0),
        (min(f5_mem['baik'], f6_mem['sangat_baik']), 45.0),
        (min(f5_mem['sangat_baik'], f6_mem['baik']), 46.5),
        (min(f5_mem['sangat_baik'], f6_mem['sangat_baik']), 49.5),
    ]

    total_alpha = 0.0
    weighted_z_sum = 0.0

    for alpha, z in rules:
        if alpha > 0.0001:
            total_alpha += alpha
            weighted_z_sum += alpha * z

    if total_alpha > 0:
        fuzzy_z = weighted_z_sum / total_alpha
    else:
        # Fallback to direct weighted conversion if outside rules
        fuzzy_z = (weighted_crisp / 100.0) * 50.0

    # Smooth blending with crisp weighted benchmark to maintain high precision
    # 70% fuzzy Tsukamoto defuzzification + 30% direct crisp linear ratio
    linear_z = (weighted_crisp / 100.0) * 50.0
    final_score = (0.75 * fuzzy_z) + (0.25 * linear_z)

    # Format to 1 decimal place or integer if round
    final_score = max(0.0, min(50.0, round(final_score, 1)))
    display_score = f"{int(final_score) if final_score.is_integer() else final_score}/50"
    percentage = round((final_score / 50.0) * 100.0, 1)

    return {
        "status": "success",
        "f5_industri": f5,
        "f6_dosen": f6,
        "bobot_f5": 60,
        "bobot_f6": 40,
        "score_num": final_score,
        "max_score": 50,
        "display": display_score,
        "percentage": percentage
    }

def main():
    parser = argparse.ArgumentParser(description="SIM-PKL PNJ Fuzzy Logic Scoring Engine")
    parser.add_argument("--f5", type=float, required=True, help="Nilai Form F5 Industri (0-100)")
    parser.add_argument("--f6", type=float, required=True, help="Nilai Form F6 Dosen (0-100)")

    args = parser.parse_args()

    try:
        result = compute_fuzzy_score(args.f5, args.f6)
        print(json.dumps(result, indent=2))
        sys.exit(0)
    except Exception as e:
        error_res = {
            "status": "error",
            "message": str(e)
        }
        print(json.dumps(error_res), file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()
