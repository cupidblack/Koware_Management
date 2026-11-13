# Koware Job Descriptions — Source Reconciliation and Refactor Audit

**Audit date:** 2026-11-13 · **Collection version:** 0.4.0 · **Target branch:** `employment/job-descriptions-v0.1.0` · **Status:** Draft upgrade package, not yet committed or merged.

## Evidence reviewed

1. [Source chart](https://github.com/cupidblack/Koware_Management/blob/main/Organizational_Chart_Structure-Aliased_Checks.txt), all 67 numbered entries. The 67-entry hierarchy was checked against the publicly available live chart on 2026-11-13; recheck source state again at adoption.
2. [Existing branch index](https://raw.githubusercontent.com/cupidblack/Koware_Management/refs/heads/employment/job-descriptions-v0.1.0/BlueCrown/Employment_Resources/JOB_DESCRIPTIONS_INDEX.md), which lists 62 role files and three governance charters.
3. [PR #4](https://github.com/cupidblack/Koware_Management/pull/4), a draft pull request from the pre-existing development branch.
4. Prior multi-platform responses, including contradictory earlier claims about the second liaison post, and sampled branch documents (Corporate Secretary, both liaison variants, Recovery Community Coordinator and governance charters).
5. [Classies Chronicles David Duvor sample](https://github.com/cupidblack/Classies_Chronicles/blob/main/CC_AI_DESK/04_CANON/CHARACTERS/DAVID_DUVOR/DAVID_DUVOR_character_cannon.md), used as inspiration for independent Markdown records, YAML IDs and provenance, not for fictional biographies or real personnel claims.

## Scope and completeness

| Source type | Source lines | Count | Output treatment |
|---|---|---:|---|
| Stakeholders, Shareholders | 1–2 | 2 | One source interpretation reference, not employment descriptions |
| Members Representative, Governing Committee | 3–4 | 2 | Separate governance charters |
| Individual employment positions | 5–66 | 62 | Full dedicated Markdown job description per position instance |
| Board of Directors | 67 | 1 | Separate governance charter |
| **Total** | **1–67** | **67** | **62 job descriptions, 3 charters, 1 ownership note** |

## Material deficiencies corrected

| Priority | Existing collection or earlier advice | Updated decision |
|---|---|---|
| Critical | Some earlier platform responses asserted **two Liaison Assistants**, although the existing branch and later responses identify **Liaison Personnel** at line 17. | The verified live chart has **one Liaison Assistant at line 14** and a separate **Liaison Personnel at line 17**. Keep and refactor **both existing canonical paths**; do not create an invented duplicate. |
| High | Corporate liaison's assistant was at one point assigned beneath Liaison Officer. | Both Liaison Officer (13) and corporate Liaison Assistant (14) report to Corporate Manager (12), as already corrected in branch v0.3. |
| High | Many earlier roles had generic KPIs, repeating approval boilerplate and inconsistent version/status lines. | Rebuilt every role with 7 unique accountabilities, 3 concrete outputs, 3 performance indicators, competency, systems and a specialist boundary. Single coherent v0.4.0 status. |
| High | Administrative Auditor has charted finance and records direct reports. | Preserve chart edge while requiring independent assurance and no self-review, financial approvals or unilateral audit certification. |
| High | Quality Assurance & Compliance Manager sits within the Academic Programmes chain. | Add independent escalation of serious findings and no self-certification. |
| High | Cooperative Intern has direct reports in planning tree. | Add competent supervisor and supervised work-allocation boundary. |
| Medium | **Stylists & Prep Coordinator** is indented 25 spaces against Grooming Manager's 24. | Treat one-space difference as intentional nesting under Grooming Manager and specialists (32 spaces) under Stylists, but require chart-owner confirmation. |
| Medium | Legal Journalist (Law/Legislation vested Pastor/Theologian) label could imply licensed or pastoral services. | Make research/editorial duties explicit and ban unauthorised regulated practice or representation. |
| Medium | “Director of Operations” and “Technical Support & Operations Manager” can be conflated. | Separate programme execution versus technical/front-line support within their charted departments. |
| Medium | “Day 1,21” annotations could be mistaken for employment rosters. | Explicitly prohibit this interpretation. |
| Medium | Example URL doubles `BlueCrown/Employment_Resources`. | Preserve actual existing canonical branch path, no duplicate tree. |

## Branch and publication boundary

The existing branch and draft PR were verified publicly. **This package has not written, pushed or merged anything in GitHub.** It is a drop-in replacement/upgrade designed for a checkout of that same branch. Use the included safe preview/apply script to back up overwritten files while preserving the existing `LIAISON_PERSONNEL` path. The application script checks the Git branch and does not commit, push or open a pull request.

The prior branch index quotes historical chart blob `7b6ed7f1c9ce4d162832f96eadb4c6140c5211f8`. The **current live chart was reviewed in a public GitHub raw-page rendering**; this is not a guarantee the chart is immutable. The bundle source mapping was checked against the live 67-line chart and requires revalidation before formal adoption.

## Outstanding governance adoption gates

1. Chart owner confirms the **Liaison Assistant / Liaison Personnel** distinction and the 25-space Stylists indent interpretation.
2. Corporate governance and qualified Ghanaian legal reviewers confirm authority relationships among shareholders, Governing Committee, Board, member representative, HR and operational executives.
3. Finance, audit and HR approve a formal decision and segregation-of-duties matrix, independent audit oversight and data access design.
4. Education, recovery, hospitality, grooming, R&D and technology specialists validate licensing, safeguarding, safety, professional scope and qualification language against actual operations.
5. Department owners approve service-specific duties, resources, reporting cadences and realistic KPI baselines and targets.
6. Designated approvers adopt each role, actual employment contracts and effective dates through authorised instruments. No draft should be used as a signed contract before then.

## Verification and migration

- Run `python validate_collection.py` at the upgrade package root: enforces file count, title/parent/child line parity, unique IDs, full content, index links, anti-placeholder checks and charter coverage.
- Run `python apply_to_existing_branch.py /path/to/Koware_Management` first without `--apply` for a read-only preview; then with `--apply` after a clean working-tree check.
- Historical Git commits preserve prior documents. The package **updates** the existing `LIAISON_PERSONNEL` document at its established path.
