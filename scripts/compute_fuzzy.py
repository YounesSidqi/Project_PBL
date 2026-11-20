#!/usr/bin/env python3
"""
SIM-PKL JTIK PNJ 2026 - Fuzzy Sugeno Evaluation Engine
Takagi-Sugeno-Kang (TSK) Orde-1 Fuzzy Inference System for Internship Final Scoring
F5: Pembimbing Industri (Bobot 60%)
F6: Dosen Pembimbing PNJ (Bobot 40%)
Output: Rasio numerik skala 50 (Contoh: "43.5/50")
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
    1. Fuzzifikasi Input (F5 dan F6 pada domain [0, 100]):
    - Kurang (kurang): Trapesium Bahu Kiri [0, 0, 50, 65]
    - Cukup (cukup): Segitiga [50, 65, 80]
    - Baik (baik): Segitiga [65, 80, 92]
    - Sangat Baik (sangat_baik): Trapesium Bahu Kanan [80, 92, 100, 100]
    """
    return {
        "kurang": trapezoid(score, 0.0, 0.0, 50.0, 65.0),
        "cukup": triangle(score, 50.0, 65.0, 80.0),
        "baik": triangle(score, 65.0, 80.0, 92.0),
        "sangat_baik": trapezoid(score, 80.0, 92.0, 100.0, 100.0),
    }


def compute_fuzzy_sugeno(f5_val, f6_val):
    """
    2. Inferensi & Defuzzifikasi Metode Fuzzy Sugeno (Orde-1):
    - Setiap aturan berbentuk:
      IF (F5 is A_i) AND (F6 is B_i) THEN z_i = p_i * F5 + q_i * F6 + r_i
    - Operator AND menggunakan fungsi MIN:
      alpha_i = min(mu_A_i(F5), mu_B_i(F6))
    - Defuzzifikasi menggunakan Rata-Rata Terbobot (Weighted Average Sugeno):
      Z = sum(alpha_i * z_i) / sum(alpha_i)
    """
    f5 = max(0.0, min(100.0, float(f5_val)))
    f6 = max(0.0, min(100.0, float(f6_val)))

    f5_mem = fuzzify_score(f5)
    f6_mem = fuzzify_score(f6)

    # Basis Aturan (Rule Base) 16 Aturan Fuzzy Sugeno Orde-1 (Skala Output 0 - 50)
    # Bobot dasar skala 50: F5 (60% -> p = 0.30), F6 (40% -> q = 0.20)
    # Format: (kode_rule, kat_f5, kat_f6, p, q, r)
    rule_definitions = [
        ("R1", "kurang", "kurang", 0.25, 0.15, -2.0),
        ("R2", "kurang", "cukup", 0.26, 0.18, -1.5),
        ("R3", "kurang", "baik", 0.27, 0.20, -1.0),
        ("R4", "kurang", "sangat_baik", 0.28, 0.21, -0.5),
        ("R5", "cukup", "kurang", 0.28, 0.16, -1.0),
        ("R6", "cukup", "cukup", 0.29, 0.19, -0.5),
        ("R7", "cukup", "baik", 0.30, 0.20, 0.0),
        ("R8", "cukup", "sangat_baik", 0.30, 0.21, 0.2),
        ("R9", "baik", "kurang", 0.29, 0.17, -0.5),
        ("R10", "baik", "cukup", 0.30, 0.19, 0.0),
        ("R11", "baik", "baik", 0.30, 0.20, 0.5),
        ("R12", "baik", "sangat_baik", 0.30, 0.20, 0.8),
        ("R13", "sangat_baik", "kurang", 0.30, 0.18, 0.0),
        ("R14", "sangat_baik", "cukup", 0.30, 0.20, 0.3),
        ("R15", "sangat_baik", "baik", 0.30, 0.20, 0.8),
        ("R16", "sangat_baik", "sangat_baik", 0.30, 0.20, 1.0),
    ]

    total_alpha = 0.0
    weighted_z_sum = 0.0
    active_rules = []

    for rule_id, f5_cat, f6_cat, p, q, r in rule_definitions:
        alpha = min(f5_mem[f5_cat], f6_mem[f6_cat])
        if alpha > 0.0001:
            z_i = max(0.0, min(50.0, (p * f5) + (q * f6) + r))
            total_alpha += alpha
            weighted_z_sum += alpha * z_i
            active_rules.append({
                "rule": rule_id,
                "f5_category": f5_cat,
                "f6_category": f6_cat,
                "alpha": round(alpha, 4),
                "z": round(z_i, 2),
            })

    # Defuzzifikasi Sugeno (Weighted Average: Z = sum(alpha_i * z_i) / sum(alpha_i))
    if total_alpha > 0.0:
        final_score = weighted_z_sum / total_alpha
    else:
        final_score = (0.30 * f5) + (0.20 * f6)

    final_score = max(0.0, min(50.0, round(final_score, 1)))
    display_score = f"{int(final_score) if final_score.is_integer() else final_score}/50"
    percentage = round((final_score / 50.0) * 100.0, 1)

    return {
        "status": "success",
        "method": "Fuzzy Sugeno",
        "f5_industri": f5,
        "f6_dosen": f6,
        "bobot_f5": 60,
        "bobot_f6": 40,
        "fuzzification": {
            "f5": {k: round(v, 4) for k, v in f5_mem.items()},
            "f6": {k: round(v, 4) for k, v in f6_mem.items()},
        },
        "active_rules": active_rules,
        "score_num": final_score,
        "max_score": 50,
        "display": display_score,
        "percentage": percentage,
    }


def main():
    parser = argparse.ArgumentParser(description="SIM-PKL PNJ Fuzzy Sugeno Scoring Engine")
    parser.add_argument("--f5", type=float, required=True, help="Nilai Form F5 Industri (0-100)")
    parser.add_argument("--f6", type=float, required=True, help="Nilai Form F6 Dosen (0-100)")

    args = parser.parse_args()

    try:
        result = compute_fuzzy_sugeno(args.f5, args.f6)
        print(json.dumps(result, indent=2))
        sys.exit(0)
    except Exception as e:
        error_res = {
            "status": "error",
            "message": str(e),
        }
        print(json.dumps(error_res), file=sys.stderr)
        sys.exit(1)


if __name__ == "__main__":
    main()
