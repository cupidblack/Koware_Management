#!/usr/bin/env python3
"""Classies Chronicles: deterministic Sunday-first Bible-study calendar validation.

This utility computes calendar capacity and week number; it DOES NOT choose or
approve a reading from an external plan. The reading-plan-to-week mapping
requires a separate, owner-approved schedule. Pure standard-library Python.

Usage:
    python CC-05.03-Eternal_Dreamfall_Calendar_Validator_v0.01.00.py --date 2026-11-19
    python CC-05.03-Eternal_Dreamfall_Calendar_Validator_v0.01.00.py --self-test
"""
from __future__ import annotations
import argparse
from datetime import date, timedelta
import json

ANCHOR_YEAR = 2023
CYCLE_YEARS = 7
SETUP_YEARS = 2


def first_sunday_on_or_after(d: date) -> date:
    # Python: Monday 0, Sunday 6.
    return d + timedelta(days=(6 - d.weekday()) % 7)


def last_saturday_on_or_before(d: date) -> date:
    return d - timedelta(days=(d.weekday() - 5) % 7)


def schedule(day: date) -> dict:
    cycle_start = ANCHOR_YEAR + CYCLE_YEARS * ((day.year - ANCHOR_YEAR) // CYCLE_YEARS)
    cycle_end = cycle_start + CYCLE_YEARS - 1
    first_reading_year = cycle_start + SETUP_YEARS
    last_reading_year = cycle_end
    baseline = dict(
        target_date=day.isoformat(),
        cycle_start_year=cycle_start,
        cycle_end_year=cycle_end,
        preparation_years=[cycle_start, cycle_start + 1],
        reading_years=[first_reading_year, last_reading_year],
        week_convention="Sunday–Saturday, complete weeks entirely within five-year reading window",
        mapping_authority="NO_APPROVED_READING_ALLOCATION_SUPPLIED",
    )
    first = first_sunday_on_or_after(date(first_reading_year, 1, 1))
    last = last_saturday_on_or_before(date(last_reading_year, 12, 31))
    total = ((last - first).days // 7) + 1
    baseline.update(first_full_study_sunday=first.isoformat(),
                    last_full_study_saturday=last.isoformat(),
                    total_full_study_weeks=total)
    if day.year < first_reading_year:
        baseline.update(phase="PREPARATION_PHASE", week_number=None,
                        study_week_start=None, study_week_end=None)
        return baseline
    if day < first or day > last:
        baseline.update(phase="PARTIAL_BOUNDARY_REVIEW", week_number=None,
                        study_week_start=None, study_week_end=None)
        return baseline
    week = ((day - first).days // 7) + 1
    start = first + timedelta(weeks=week - 1)
    baseline.update(phase="READING_PHASE", week_number=week,
                    study_week_start=start.isoformat(),
                    study_week_end=(start + timedelta(days=6)).isoformat(),
                    source_plan_entry="NOT_VERIFIED_IN_THIS_CALENDAR_UTILITY")
    return baseline


def self_test() -> None:
    tests = [
        ("2025-01-01", "PARTIAL_BOUNDARY_REVIEW", None),
        ("2025-01-05", "READING_PHASE", 1),
        ("2026-11-19", "READING_PHASE", 98),
        ("2029-12-29", "READING_PHASE", 260),
        ("2029-12-30", "PARTIAL_BOUNDARY_REVIEW", None),
        ("2030-01-01", "PREPARATION_PHASE", None),
        ("2032-01-04", "READING_PHASE", 1),
    ]
    for raw, phase, num in tests:
        record = schedule(date.fromisoformat(raw))
        assert record["phase"] == phase, (raw, record)
        assert record["week_number"] == num, (raw, record)
    assert schedule(date(2026, 11, 19))["study_week_start"] == "2026-11-15"
    assert schedule(date(2026, 11, 19))["study_week_end"] == "2026-11-21"
    assert schedule(date(2026, 11, 19))["total_full_study_weeks"] == 260
    print(f"PASS: {len(tests)} boundary/phase/week cases and 3 additional assertions")


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--date", help="Local civil date in ISO YYYY-MM-DD format")
    parser.add_argument("--self-test", action="store_true")
    args = parser.parse_args()
    if args.self_test:
        self_test()
    if args.date:
        print(json.dumps(schedule(date.fromisoformat(args.date)), indent=2, ensure_ascii=False))
    if not args.self_test and not args.date:
        parser.error("supply --date or --self-test")


if __name__ == "__main__":
    main()
