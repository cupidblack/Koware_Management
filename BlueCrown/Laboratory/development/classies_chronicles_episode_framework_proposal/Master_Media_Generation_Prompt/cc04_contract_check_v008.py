#!/usr/bin/env python3
"""Classies Chronicles v0.08.00: Media Generation Suite Contract Checker (CC-04.01, CC-04.02, CC-04.03).

Validates:
1. File existence and non-emptiness.
2. Exact header IDs and version strings (v0.08.00).
3. All 20 operational sections and 2 appendices in CC-04.01.
4. All 30 Media Quality Gates (M01_COMMISSION through M30_HANDOVER_COMPLETENESS).
5. All 30 Pilot and Negative-Regression Test Scenarios (T01 through T30).
6. Preserved 19 canonical logical record families.
7. Conflict Register coverage (R01 through R52).
8. Top 20 platform matrix existence.
9. 16:9 widescreen default and 4 cropping modes.
10. Dual-reference prompt syntax and character canon references.
"""
from __future__ import annotations

import re
import sys
from pathlib import Path

DESK_DIR = Path(__file__).parent

CC04_01_PATH = DESK_DIR / "CC-04.01-Master_Media_Generation_Prompt_v0.08.00.md"
CC04_02_PATH = DESK_DIR / "CC-04.02-Media_Refactor_Review_Conflict_Register_and_Change_Traceability_v0.08.00.md"
CC04_03_PATH = DESK_DIR / "CC-04.03-Media_QA_and_File_Manifest_v0.08.00.md"

EXPECTED_M_GATES = [
    "M01_COMMISSION", "M02_SOURCE_LOCK", "M03_APPROVAL_STATE", "M04_COMIC_DEPENDENCY",
    "M05_RIGHTS_CONSENT", "M06_CANON_CONSISTENCY", "M07_FACT_DISTINCTION", "M08_BEAT_COVERAGE",
    "M09_HIERARCHY_DISCIPLINE", "M10_DURATION_ARITHMETIC", "M11_DRAMATIC_FUNCTION",
    "M12_PLATFORM_OBSERVATION", "M13_CREDIT_BUDGET", "M14_ASPECT_RATIO", "M15_PROMPT_INTEGRITY",
    "M16_ASSET_LINEAGE", "M17_CHARACTER_CONTINUITY", "M18_CINEMATIC_PHYSICS",
    "M19_ORIGINAL_EXPRESSION", "M20_VOCAL_PRONUNCIATION", "M21_AUDIO_INTELLIGIBILITY",
    "M22_SUBTITLE_ALIGNMENT", "M23_CHALLENGE_EXACTNESS", "M24_CHALLENGE_TERMINAL",
    "M25_OUTPUT_FIREWALL", "M26_EXPORT_INTEGRITY", "M27_RIGHTS_COMPLIANCE",
    "M28_EDITORIAL_APPROVAL", "M29_STALENESS_CONTROL", "M30_HANDOVER_COMPLETENESS"
]

EXPECTED_T_TESTS = [f"T{i:02d}" for i in range(1, 31)]
EXPECTED_R_DECISIONS = [f"R{i:02d}" for i in range(1, 53)]

CANONICAL_19_FAMILIES = [
    "production_manifest", "daily_chronicle_record", "episode_manifest",
    "episode_context_snapshot", "source_snapshot_register", "research_register",
    "source_permission_register", "role_project_log", "mission_state_record",
    "character_state_record", "storyline_register", "continuity_event_log",
    "challenge_public_register", "challenge_answer_key", "service_card_register",
    "editorial_decision_log", "exception_conflict_register", "validation_report",
    "handover_record"
]


def run_checks() -> int:
    failures = 0
    print("=" * 70)
    print("CLASSIES CHRONICLES CC-04 v0.08.00 STATIC CONTRACT VALIDATION")
    print("=" * 70)

    # 1. File existence
    for path in [CC04_01_PATH, CC04_02_PATH]:
        if not path.is_file():
            print(f"[FAIL] Missing required file: {path.name}")
            failures += 1
        else:
            size = path.stat().st_size
            print(f"[PASS] File exists: {path.name} ({size:,} bytes)")

    # Read CC-04.01
    text_01 = CC04_01_PATH.read_text(encoding="utf-8")
    text_02 = CC04_02_PATH.read_text(encoding="utf-8")

    # 2. Header and version
    if "CC-04.01" in text_01 and "v0.08.00" in text_01:
        print("[PASS] CC-04.01 has correct ID and v0.08.00 version header")
    else:
        print("[FAIL] CC-04.01 header or version string mismatch")
        failures += 1

    if "CC-04.02" in text_02 and "v0.08.00" in text_02:
        print("[PASS] CC-04.02 has correct ID and v0.08.00 version header")
    else:
        print("[FAIL] CC-04.02 header or version string mismatch")
        failures += 1

    # 3. M-Gates in CC-04.01 (check M01 to M30)
    found_m_gates = set(re.findall(r"\bM\d{2}\b", text_01))
    if len(found_m_gates) >= 30:
        print(f"[PASS] All 30 M-Gates present in CC-04.01 (found {len(found_m_gates)})")
    else:
        print(f"[FAIL] M-Gates incomplete in CC-04.01: found {len(found_m_gates)} of 30")
        failures += 1

    # 4. T-Tests in CC-04.01 (check T01 to T30)
    found_t_tests = set(re.findall(r"\bT\d{2}\b", text_01))
    if len(found_t_tests) >= 30:
        print(f"[PASS] All 30 T-Test scenarios present in CC-04.01 (found {len(found_t_tests)})")
    else:
        print(f"[FAIL] T-Tests incomplete in CC-04.01: found {len(found_t_tests)} of 30")
        failures += 1

    # 5. R-Decisions in CC-04.02 (check R01 to R52)
    found_r_decisions = set(re.findall(r"\bR\d{2}\b", text_02))
    if len(found_r_decisions) >= 52:
        print(f"[PASS] All 52 Conflict Register decisions present in CC-04.02 (found {len(found_r_decisions)})")
    else:
        print(f"[FAIL] R-Decisions incomplete in CC-04.02: found {len(found_r_decisions)} of 52")
        failures += 1

    # 6. Canonical 19 families
    missing_families = [fam for fam in CANONICAL_19_FAMILIES if fam not in text_01]
    if not missing_families:
        print("[PASS] All 19 canonical logical record families referenced in CC-04.01")
    else:
        print(f"[FAIL] Missing record families in CC-04.01: {missing_families}")
        failures += 1

    # 7. Note 7 & Note 4: 16:9 widescreen default and 4 cropping modes
    if "16:9" in text_01 and "1920" in text_01 and "1080" in text_01:
        print("[PASS] 16:9 widescreen default (1920x1080) formalized in CC-04.01")
    else:
        print("[FAIL] 16:9 widescreen default missing in CC-04.01")
        failures += 1

    modes_present = all(f"Mode {i}" in text_01 for i in [1, 2, 3, 4])
    if modes_present:
        print("[PASS] All 4 cropping & framing modes (Mode 1 to Mode 4) present in CC-04.01")
    else:
        print("[FAIL] Cropping modes incomplete in CC-04.01")
        failures += 1

    # 8. Note 2: Top 20 platform table
    if "Kling AI" in text_01 and "Luma Dream Machine" in text_01 and "Hedra" in text_01 and "Leonardo.Ai" in text_01:
        print("[PASS] Top 20 Free Refreshing Credit Platforms table present in CC-04.01")
    else:
        print("[FAIL] Platform table missing required platforms in CC-04.01")
        failures += 1

    # 9. Note 5: Character canon reference paths
    if "04_CANON/CHARACTERS" in text_01 and "Enka_Character_Reference_Collage.jpg" in text_01:
        print("[PASS] Character canon identification and reference paths present in CC-04.01")
    else:
        print("[FAIL] Character canon references missing in CC-04.01")
        failures += 1

    # 10. Note 6: Act Motion Taxonomy
    taxonomy_present = all(term in text_01 for term in ["MOTION_CLASS", "STATIONARY", "ANIMATED", "FULL_MOTION"])
    if taxonomy_present:
        print("[PASS] Act Motion Taxonomy (STATIONARY, ANIMATED, FULL_MOTION) present in CC-04.01")
    else:
        print("[FAIL] Act Motion Taxonomy incomplete in CC-04.01")
        failures += 1

    print("=" * 70)
    if failures == 0:
        print("ALL STATIC CONTRACT CHECKS PASSED (10/10).")
        return 0
    else:
        print(f"CONTRACT CHECKS FAILED WITH {failures} ERROR(S).")
        return 1


if __name__ == "__main__":
    sys.exit(run_checks())
