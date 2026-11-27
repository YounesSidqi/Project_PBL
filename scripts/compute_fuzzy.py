#!/usr/bin/env python3
"""
SIM-PKL JTIK PNJ 2026 - Fuzzy Sugeno Evaluation Engine
Takagi-Sugeno-Kang (TSK) Orde-1 Fuzzy Inference System per Kategori KUK
Form F5: Pembimbing DUDI (Softskill 50 + Hardskill 50 = 100 | Bobot Akhir: 40%)
Form F6: Pembimbing PNJ (Pelaporan 30 + Presentasi 40 + Bimbingan 30 = 100 | Bobot Akhir: 60%)
Output Akhir: Skala 0-100 (Format Display: "XX.X/100")
"""

import argparse
import json
import sys
from typing import Any, Dict, List, Tuple


FORM_SPECS: Dict[str, Dict[str, Any]] = {
    "f5": {
        "label": "Form F5 - Pembimbing DUDI",
        "weight": 0.40,
        "weight_percent": 40,
        "max_total": 100.0,
        "categories": {
            "softskill": {
                "label": "Softskill",
                "kuk_codes": ["1.1", "1.2", "1.3", "1.4", "1.5"],
                "min_point": 5.0,
                "max_point": 10.0,
            },
            "hardskill": {
                "label": "Hardskill",
                "kuk_codes": ["2.1", "2.2", "2.3", "2.4", "2.5"],
                "min_point": 5.0,
                "max_point": 10.0,
            },
        },
    },
    "f6": {
        "label": "Form F6 - Pembimbing PNJ",
        "weight": 0.60,
        "weight_percent": 60,
        "max_total": 100.0,
        "categories": {
            "pelaporan": {
                "label": "Pelaporan",
                "kuk_codes": ["1.1", "1.2", "1.3"],
                "min_point": 5.0,
                "max_point": 10.0,
            },
            "presentasi": {
                "label": "Presentasi",
                "kuk_codes": ["2.1", "2.2", "2.3", "2.4"],
                "min_point": 5.0,
                "max_point": 10.0,
            },
            "bimbingan": {
                "label": "Bimbingan",
                "kuk_codes": ["3.1", "3.2"],
                "min_point": 5.0,
                "max_point": 15.0,
            },
        },
    },
}


def trapezoid(x: float, a: float, b: float, c: float, d: float) -> float:
    if x <= a or x >= d:
        return 0.0
    if a < x < b:
        return (x - a) / (b - a) if b > a else 1.0
    if b <= x <= c:
        return 1.0
    if c < x < d:
        return (d - x) / (d - c) if d > c else 1.0
    return 0.0


def triangle(x: float, a: float, b: float, c: float) -> float:
    return trapezoid(x, a, b, b, c)


def fuzzify_category_score(raw_cat_score: float, max_cat_score: float) -> Tuple[Dict[str, float], Dict[str, List[float]]]:
    """
    Fuzzifikasi skor kategori secara proporsional terhadap max_cat_score:
    Titik batas domain: 0%, 50%, 65%, 80%, 92%, dan 100% dari max_cat_score.
    - kurang: Trapesium Bahu Kiri [0%, 0%, 50%, 65%]
    - cukup: Segitiga [50%, 65%, 80%]
    - baik: Segitiga [65%, 80%, 92%]
    - sangat_baik: Trapesium Bahu Kanan [80%, 92%, 100%, 100%]
    """
    p0 = 0.0
    p50 = round(0.50 * max_cat_score, 4)
    p65 = round(0.65 * max_cat_score, 4)
    p80 = round(0.80 * max_cat_score, 4)
    p92 = round(0.92 * max_cat_score, 4)
    p100 = round(1.00 * max_cat_score, 4)

    memberships = {
        "kurang": trapezoid(raw_cat_score, p0, p0, p50, p65),
        "cukup": triangle(raw_cat_score, p50, p65, p80),
        "baik": triangle(raw_cat_score, p65, p80, p92),
        "sangat_baik": trapezoid(raw_cat_score, p80, p92, p100, p100),
    }

    domain_bounds = {
        "kurang": [p0, p0, p50, p65],
        "cukup": [p50, p65, p80],
        "baik": [p65, p80, p92],
        "sangat_baik": [p80, p92, p100, p100],
    }

    return memberships, domain_bounds


def evaluate_category_sugeno(
    form_key: str,
    cat_key: str,
    cat_spec: Dict[str, Any],
    kuk_scores: Dict[str, float],
) -> Dict[str, Any]:
    """
    Validasi KUK, akumulasi raw_cat_score, fuzzifikasi proporsional,
    inferensi Sugeno Orde-1 (z_i = p_i * raw_cat_score + r_i),
    dan defuzzifikasi Weighted Average per kategori.
    """
    expected_codes: List[str] = cat_spec["kuk_codes"]
    min_point: float = float(cat_spec["min_point"])
    max_point: float = float(cat_spec["max_point"])
    cat_label: str = cat_spec["label"]
    form_label: str = FORM_SPECS[form_key]["label"]

    if len(kuk_scores) != len(expected_codes):
        raise ValueError(
            f"Kategori '{cat_label}' pada {form_label} membutuhkan {len(expected_codes)} item KUK "
            f"({', '.join(expected_codes)}), tetapi diterima {len(kuk_scores)} item."
        )

    validated_kuk: Dict[str, float] = {}
    for code in expected_codes:
        if code not in kuk_scores:
            raise ValueError(
                f"Item KUK '{code}' tidak ditemukan pada kategori '{cat_label}' ({form_label})."
            )
        try:
            val = float(kuk_scores[code])
        except (TypeError, ValueError) as exc:
            raise ValueError(
                f"Nilai KUK '{code}' pada kategori '{cat_label}' ({form_label}) harus berupa angka."
            ) from exc

        if val < min_point or val > max_point:
            raise ValueError(
                f"Nilai KUK '{code}' pada kategori '{cat_label}' ({form_label}) = {val} "
                f"di luar rentang valid [{min_point:g} - {max_point:g}]."
            )
        validated_kuk[code] = round(val, 4)

    raw_cat_score = round(sum(validated_kuk.values()), 4)
    min_cat_score = round(len(expected_codes) * min_point, 4)
    max_cat_score = round(len(expected_codes) * max_point, 4)

    memberships, domain_bounds = fuzzify_category_score(raw_cat_score, max_cat_score)

    # Basis aturan Fuzzy Sugeno Orde-1 per kategori:
    # z_i = p_i * raw_cat_score + r_i (dengan r_i proporsional terhadap max_cat_score)
    sugeno_rules = [
        ("R1", "kurang", 0.92, -0.02 * max_cat_score),
        ("R2", "cukup", 0.97, -0.01 * max_cat_score),
        ("R3", "baik", 1.00, 0.01 * max_cat_score),
        ("R4", "sangat_baik", 1.00, 0.02 * max_cat_score),
    ]

    total_alpha = 0.0
    weighted_z_sum = 0.0
    active_rules: List[Dict[str, Any]] = []

    for rule_id, level, p_coeff, r_const in sugeno_rules:
        alpha = memberships[level]
        if alpha > 0.0001:
            raw_z = (p_coeff * raw_cat_score) + r_const
            z_i = max(0.0, min(max_cat_score, raw_z))
            total_alpha += alpha
            weighted_z_sum += alpha * z_i
            active_rules.append({
                "rule": rule_id,
                "category_level": level,
                "alpha": round(alpha, 4),
                "p": p_coeff,
                "r": round(r_const, 4),
                "z": round(z_i, 4),
            })

    if total_alpha > 0.0:
        defuzzified = weighted_z_sum / total_alpha
    else:
        defuzzified = raw_cat_score

    fuzzy_cat_score = max(0.0, min(max_cat_score, round(defuzzified, 2)))

    return {
        "category": cat_key,
        "label": cat_label,
        "kuk_range": [min_point, max_point],
        "kuk_scores": validated_kuk,
        "raw_cat_score": round(raw_cat_score, 2),
        "min_cat_score": min_cat_score,
        "max_cat_score": max_cat_score,
        "domain_bounds": domain_bounds,
        "fuzzification": {k: round(v, 4) for k, v in memberships.items()},
        "active_rules": active_rules,
        "fuzzy_cat_score": fuzzy_cat_score,
    }


def normalize_form_input(form_key: str, raw_data: Any) -> Dict[str, Dict[str, float]]:
    """
    Normalisasi struktur JSON input untuk Form F5 atau Form F6 menjadi:
    {
        "<cat_key>": {"1.1": val, "1.2": val, ...},
        ...
    }
    Mendukung:
    1. Dict kategori -> list nilai (contoh: {"softskill": [8, 9, 8, 9, 8], ...})
    2. Dict kategori -> dict kode KUK (contoh: {"softskill": {"1.1": 8, ...}, ...})
    3. Flat dict kode KUK (contoh: {"1.1": 8, ..., "2.1": 9, ...})
    """
    form_spec = FORM_SPECS[form_key]
    form_label = form_spec["label"]
    categories_spec: Dict[str, Dict[str, Any]] = form_spec["categories"]

    if not isinstance(raw_data, dict):
        raise ValueError(
            f"Input untuk {form_label} harus berupa objek JSON (dictionary) berisi kategori atau kode KUK."
        )

    lower_key_map = {str(k).strip().lower(): v for k, v in raw_data.items()}
    normalized: Dict[str, Dict[str, float]] = {}

    # Cek apakah input dikelompokkan berdasarkan nama kategori
    has_category_keys = any(cat_key in lower_key_map for cat_key in categories_spec)

    if has_category_keys:
        missing_cats = [
            cat_spec["label"]
            for cat_key, cat_spec in categories_spec.items()
            if cat_key not in lower_key_map
        ]
        if missing_cats:
            raise ValueError(
                f"Input {form_label} tidak lengkap. Kategori yang belum ada: {', '.join(missing_cats)}."
            )

        for cat_key, cat_spec in categories_spec.items():
            cat_payload = lower_key_map[cat_key]
            expected_codes: List[str] = cat_spec["kuk_codes"]

            if isinstance(cat_payload, list):
                if len(cat_payload) != len(expected_codes):
                    raise ValueError(
                        f"Kategori '{cat_spec['label']}' pada {form_label} membutuhkan {len(expected_codes)} item KUK "
                        f"({', '.join(expected_codes)}), tetapi diterima {len(cat_payload)} item."
                    )
                normalized[cat_key] = {
                    code: cat_payload[idx] for idx, code in enumerate(expected_codes)
                }
            elif isinstance(cat_payload, dict):
                str_keyed = {str(k).strip(): v for k, v in cat_payload.items()}
                normalized[cat_key] = str_keyed
            else:
                raise ValueError(
                    f"Data kategori '{cat_spec['label']}' pada {form_label} harus berupa array/list atau object/dict KUK."
                )
        return normalized

    # Jika berupa flat dict kode KUK {"1.1": 8, "1.2": 9, ...}
    str_keyed_flat = {str(k).strip(): v for k, v in raw_data.items()}
    total_expected = sum(len(c["kuk_codes"]) for c in categories_spec.values())
    if len(str_keyed_flat) != total_expected:
        raise ValueError(
            f"Input {form_label} membutuhkan total {total_expected} item KUK, tetapi diterima {len(str_keyed_flat)} item."
        )

    for cat_key, cat_spec in categories_spec.items():
        cat_kuk: Dict[str, float] = {}
        for code in cat_spec["kuk_codes"]:
            if code not in str_keyed_flat:
                raise ValueError(
                    f"Kode KUK '{code}' untuk kategori '{cat_spec['label']}' ({form_label}) tidak ditemukan."
                )
            cat_kuk[code] = str_keyed_flat[code]
        normalized[cat_key] = cat_kuk

    return normalized


def compute_fuzzy_sugeno(f5_raw: Any, f6_raw: Any) -> Dict[str, Any]:
    """
    Hitung nilai Fuzzy Sugeno Orde-1 untuk setiap kategori pada Form F5 (bobot 40%)
    dan Form F6 (bobot 60%), lalu agregasikan menjadi nilai akhir skala 0-100.
    """
    f5_input = normalize_form_input("f5", f5_raw)
    f6_input = normalize_form_input("f6", f6_raw)

    f5_categories_res: Dict[str, Dict[str, Any]] = {}
    for cat_key, cat_spec in FORM_SPECS["f5"]["categories"].items():
        f5_categories_res[cat_key] = evaluate_category_sugeno(
            "f5", cat_key, cat_spec, f5_input[cat_key]
        )

    f6_categories_res: Dict[str, Dict[str, Any]] = {}
    for cat_key, cat_spec in FORM_SPECS["f6"]["categories"].items():
        f6_categories_res[cat_key] = evaluate_category_sugeno(
            "f6", cat_key, cat_spec, f6_input[cat_key]
        )

    raw_total_f5 = round(
        sum(cat["raw_cat_score"] for cat in f5_categories_res.values()), 2
    )
    total_f5 = max(
        0.0,
        min(
            100.0,
            round(sum(cat["fuzzy_cat_score"] for cat in f5_categories_res.values()), 2),
        ),
    )

    raw_total_f6 = round(
        sum(cat["raw_cat_score"] for cat in f6_categories_res.values()), 2
    )
    total_f6 = max(
        0.0,
        min(
            100.0,
            round(sum(cat["fuzzy_cat_score"] for cat in f6_categories_res.values()), 2),
        ),
    )

    w_f5 = FORM_SPECS["f5"]["weight"]  # 0.40
    w_f6 = FORM_SPECS["f6"]["weight"]  # 0.60

    weighted_f5 = round(total_f5 * w_f5, 2)
    weighted_f6 = round(total_f6 * w_f6, 2)

    final_score = max(0.0, min(100.0, round((total_f5 * w_f5) + (total_f6 * w_f6), 1)))
    display_score = f"{final_score:.1f}/100"

    return {
        "status": "success",
        "method": "Fuzzy Sugeno Orde-1",
        "bobot_f5": FORM_SPECS["f5"]["weight_percent"],
        "bobot_f6": FORM_SPECS["f6"]["weight_percent"],
        "f5": {
            "label": FORM_SPECS["f5"]["label"],
            "weight": w_f5,
            "weight_percent": FORM_SPECS["f5"]["weight_percent"],
            "max_total": 100,
            "raw_total_f5": raw_total_f5,
            "total_f5": total_f5,
            "weighted_f5": weighted_f5,
            "categories": f5_categories_res,
        },
        "f6": {
            "label": FORM_SPECS["f6"]["label"],
            "weight": w_f6,
            "weight_percent": FORM_SPECS["f6"]["weight_percent"],
            "max_total": 100,
            "raw_total_f6": raw_total_f6,
            "total_f6": total_f6,
            "weighted_f6": weighted_f6,
            "categories": f6_categories_res,
        },
        "total_f5": total_f5,
        "total_f6": total_f6,
        "score_num": final_score,
        "max_score": 100,
        "percentage": final_score,
        "display": display_score,
    }


def parse_cli_payload(args: argparse.Namespace) -> Tuple[Any, Any]:
    if args.payload_json:
        try:
            payload = json.loads(args.payload_json)
        except json.JSONDecodeError as exc:
            raise ValueError(f"Format --payload-json tidak valid: {exc}") from exc

        if not isinstance(payload, dict) or "f5" not in payload or "f6" not in payload:
            raise ValueError(
                "Argumen --payload-json harus berupa objek JSON yang memiliki key 'f5' dan 'f6'."
            )
        return payload["f5"], payload["f6"]

    if args.f5_json and args.f6_json:
        try:
            f5_data = json.loads(args.f5_json)
        except json.JSONDecodeError as exc:
            raise ValueError(f"Format --f5-json tidak valid: {exc}") from exc

        try:
            f6_data = json.loads(args.f6_json)
        except json.JSONDecodeError as exc:
            raise ValueError(f"Format --f6-json tidak valid: {exc}") from exc

        return f5_data, f6_data

    raise ValueError(
        "Harap berikan argumen --f5-json dan --f6-json, atau gunakan --payload-json."
    )


def main() -> None:
    parser = argparse.ArgumentParser(
        description="SIM-PKL JTIK PNJ 2026 - Fuzzy Sugeno Orde-1 Evaluation Engine (Berbasis KUK)"
    )
    parser.add_argument(
        "--f5-json",
        type=str,
        help='JSON rincian nilai KUK Form F5 (Softskill 1.1-1.5 [5-10], Hardskill 2.1-2.5 [5-10])',
    )
    parser.add_argument(
        "--f6-json",
        type=str,
        help='JSON rincian nilai KUK Form F6 (Pelaporan 1.1-1.3 [5-10], Presentasi 2.1-2.4 [5-10], Bimbingan 3.1-3.2 [5-15])',
    )
    parser.add_argument(
        "--payload-json",
        type=str,
        help='JSON gabungan {"f5": {...}, "f6": {...}}',
    )

    args = parser.parse_args()

    try:
        f5_data, f6_data = parse_cli_payload(args)
        result = compute_fuzzy_sugeno(f5_data, f6_data)
        print(json.dumps(result, indent=2))
        sys.exit(0)
    except Exception as exc:
        error_res = {
            "status": "error",
            "message": str(exc),
        }
        print(json.dumps(error_res, indent=2), file=sys.stderr)
        sys.exit(1)


if __name__ == "__main__":
    main()
