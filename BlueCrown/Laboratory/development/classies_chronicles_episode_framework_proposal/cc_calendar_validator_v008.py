#!/usr/bin/env python3
"""Classies Chronicles v0.08.00: deterministic *proposed* local-date calendar evaluator.

This tool deliberately does NOT fetch or authorize a phase calendar, mission, season ID,
character, role chart, or repository. All phase boundaries are explicit command-line inputs;
external editorial approval remains a separate precondition for definitive assignment.

Examples are exclusively illustrative; do not treat their phase dates as approved canon.
"""
from __future__ import annotations

import argparse
import json
from dataclasses import asdict, dataclass
from datetime import date, datetime, timedelta
from zoneinfo import ZoneInfo, ZoneInfoNotFoundError
import unittest


class CalendarError(ValueError):
    pass


@dataclass(frozen=True)
class Slot:
    status: str
    local_date: str
    production_timezone: str
    phase_start_local_date: str
    phase_end_local_date: str
    phase_end_inclusive: bool
    first_monday_anchor: str
    episode_ordinal: int | None = None
    chronicle_ordinal: int | None = None
    chapter_working_day: int | None = None
    episode_monday: str | None = None
    episode_second_friday: str | None = None
    explanation: str = ""


def to_local_date(value: str, tz: ZoneInfo) -> date:
    """Return local date of an ISO date or *offset-aware* ISO timestamp."""
    try:
        if len(value) == 10:
            return date.fromisoformat(value)
        moment = datetime.fromisoformat(value.replace("Z", "+00:00"))
        if moment.tzinfo is None or moment.utcoffset() is None:
            raise CalendarError("Timestamp must be offset-aware; unqualified local times are forbidden")
        return moment.astimezone(tz).date()
    except (TypeError, ValueError) as e:
        if isinstance(e, CalendarError):
            raise
        raise CalendarError(f"Invalid ISO date or offset-aware timestamp: {value!r}") from e


def evaluate(phase_start: str, phase_end: str, target: str,
             timezone: str, phase_end_inclusive: bool = True) -> Slot:
    """Evaluate ordinary 4x14-day chapter; never assert calendar approval.

    Returned 1-based episode and 1-based Chronicle #1–#10 only on scheduled weekdays
    whose FULL episode window lies within supplied phase; otherwise ordinals are None.
    """
    try:
        tz = ZoneInfo(timezone)
    except (ZoneInfoNotFoundError, ValueError) as e:
        raise CalendarError(f"Unknown IANA production timezone: {timezone}") from e
    start = to_local_date(phase_start, tz)
    end = to_local_date(phase_end, tz)
    day = to_local_date(target, tz)
    last = end if phase_end_inclusive else end - timedelta(days=1)
    if last < start:
        raise CalendarError("Approved phase boundaries must define a nonempty local date range")
    anchor = start + timedelta(days=(7 - start.weekday()) % 7)
    common = dict(local_date=day.isoformat(), production_timezone=timezone,
                  phase_start_local_date=start.isoformat(),
                  phase_end_local_date=end.isoformat(),
                  phase_end_inclusive=phase_end_inclusive,
                  first_monday_anchor=anchor.isoformat())

    def out(status: str, explanation: str) -> Slot:
        return Slot(status=status, explanation=explanation, **common)

    if day < start or day > last:
        return out("OUTSIDE_PHASE", "Target local civil day is not inside the supplied phase")
    if day < anchor:
        return out("BEFORE_FIRST_MONDAY", "Partial initial week is not an ordinary numbered episode")
    elapsed = (day - anchor).days
    ordinal = elapsed // 14 + 1
    if ordinal > 4:
        return out("UNALLOCATED_CHAPTER_TAIL", "Beyond the four standard episode blocks")
    episode_monday = anchor + timedelta(days=14*(ordinal-1))
    episode_friday = episode_monday + timedelta(days=11)
    if episode_friday > last:
        return out("INCOMPLETE_EPISODE_WINDOW", "The whole 14-day standard episode does not fit the supplied phase")
    block_day = elapsed % 14
    if block_day in (5, 6, 12, 13):
        return out("WEEKEND_NOT_SCHEDULED", "Weekends do not consume a standard Chronicle number")
    c = block_day+1 if block_day < 5 else block_day-1
    working_day = 10*(ordinal-1) + c
    return Slot(status="SCHEDULED", episode_ordinal=ordinal,
                chronicle_ordinal=c, chapter_working_day=working_day,
                episode_monday=episode_monday.isoformat(),
                episode_second_friday=episode_friday.isoformat(),
                explanation="Arithmetic-only slot; authoritative calendar approval is NOT implied", **common)


class CalendarTests(unittest.TestCase):
    """Toy fixture: 56 day 4-block phase starts Wednesday, so first Mon is day 5."""
    TZ = "Africa/Accra"
    START = "2026-01-14"  # Wednesday, illustrative ONLY
    END = "2026-03-15"    # Sunday 61-day window, illustrative ONLY

    def eval(self, d: str, **kw):
        return evaluate(self.START, self.END, d, self.TZ, **kw)

    def test_initial_partial_week(self):
        self.assertEqual(self.eval("2026-01-14").status, "BEFORE_FIRST_MONDAY")
        self.assertEqual(self.eval("2026-01-18").status, "BEFORE_FIRST_MONDAY")

    def test_all_40_ordinary_days(self):
        anchor = date(2026, 1, 19)
        seen = []
        for k in range(56):
            d = (anchor+timedelta(days=k)).isoformat()
            result = self.eval(d)
            if k % 14 in (5,6,12,13):
                self.assertEqual(result.status, "WEEKEND_NOT_SCHEDULED")
            else:
                self.assertEqual(result.status, "SCHEDULED")
                expected_chronicle = k % 14 + 1 if k % 14 < 5 else k % 14 - 1
                self.assertEqual(result.chronicle_ordinal, expected_chronicle)
                self.assertEqual(result.chapter_working_day, 10*(k//14)+expected_chronicle)
                seen.append(result.chapter_working_day)
        self.assertEqual(seen, list(range(1,41)))

    def test_explicit_boundaries(self):
        dates = [
            ("2026-01-19",1,1,1), ("2026-01-23",1,5,5),
            ("2026-01-26",1,6,6), ("2026-01-30",1,10,10),
            ("2026-02-02",2,1,11), ("2026-02-13",2,10,20),
            ("2026-02-16",3,1,21), ("2026-02-27",3,10,30),
            ("2026-03-02",4,1,31), ("2026-03-13",4,10,40),
        ]
        for d, e, c, w in dates:
            with self.subTest(d=d):
                s = self.eval(d)
                self.assertEqual((s.episode_ordinal,s.chronicle_ordinal,s.chapter_working_day),(e,c,w))

    def test_out_of_phase_and_tail(self):
        self.assertEqual(self.eval("2026-01-13").status,"OUTSIDE_PHASE")
        self.assertEqual(self.eval("2026-03-16").status,"OUTSIDE_PHASE")
        self.assertEqual(self.eval("2026-03-15").status,"WEEKEND_NOT_SCHEDULED")
        # A five-block/tail window can include days 56+ but they must remain unscheduled.
        r = evaluate(self.START,"2026-04-01","2026-03-16",self.TZ)
        self.assertEqual(r.status,"UNALLOCATED_CHAPTER_TAIL")

    def test_partial_last_episode_no_fabrication(self):
        r = evaluate(self.START,"2026-03-10","2026-03-02",self.TZ)
        self.assertEqual(r.status,"INCOMPLETE_EPISODE_WINDOW")
        self.assertIsNone(r.episode_ordinal)

    def test_exclusive_end(self):
        r = evaluate(self.START,"2026-03-13","2026-03-13",self.TZ,False)
        self.assertEqual(r.status,"OUTSIDE_PHASE")
        r2 = evaluate(self.START,"2026-03-13","2026-03-02",self.TZ,False)
        self.assertEqual(r2.status,"INCOMPLETE_EPISODE_WINDOW")

    def test_timezone_midnight_conversion(self):
        # 2026-01-18 23:30Z is 2026-01-19 07:30 in Singapore
        r = evaluate("2026-01-14","2026-03-15","2026-01-18T23:30:00+00:00","Asia/Singapore")
        self.assertEqual((r.local_date,r.status,r.chronicle_ordinal),
                         ("2026-01-19","SCHEDULED",1))

    def test_timestamp_with_offset_and_Z(self):
        r = self.eval("2026-01-19T00:01:00Z")
        self.assertEqual(r.status,"SCHEDULED")
        self.assertEqual(r.chronicle_ordinal,1)
        r = self.eval("2026-01-18T23:59:00-01:00")
        self.assertEqual(r.local_date,"2026-01-19")

    def test_reject_naive_timestamp_and_bad_calendar(self):
        with self.assertRaises(CalendarError):
            self.eval("2026-01-19T09:00:00")
        with self.assertRaises(CalendarError):
            evaluate("2026-01-31","2026-01-01","2026-01-22",self.TZ)
        with self.assertRaises(CalendarError):
            evaluate(self.START,self.END,"2026-01-19","Mars/Olympus")


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--phase-start", help="Approved local date (YYYY-MM-DD) or offset-aware ISO timestamp")
    parser.add_argument("--phase-end", help="Approved local date (YYYY-MM-DD) or offset-aware ISO timestamp")
    parser.add_argument("--target-date", help="Requested local date or offset-aware ISO instant")
    parser.add_argument("--time-zone", help="Actual approved IANA production timezone, e.g. Africa/Accra")
    parser.add_argument("--end-exclusive", action="store_true", help="Interpret supplied phase-end local date as exclusive")
    parser.add_argument("--self-test", action="store_true", help="Run illustrative deterministic calendar tests")
    a = parser.parse_args()
    if a.self_test:
        suite = unittest.defaultTestLoader.loadTestsFromTestCase(CalendarTests)
        result = unittest.TextTestRunner(verbosity=2).run(suite)
        return 0 if result.wasSuccessful() else 1
    if not (a.phase_start and a.phase_end and a.target_date and a.time_zone):
        parser.error("Provide all four required inputs or --self-test; no date is silently invented")
    try:
        result=evaluate(a.phase_start,a.phase_end,a.target_date,a.time_zone,
                        phase_end_inclusive=not a.end_exclusive)
        print(json.dumps(asdict(result),indent=2,ensure_ascii=False))
        return 0
    except CalendarError as e:
        parser.error(str(e))
        return 2

if __name__ == "__main__":
    raise SystemExit(main())
