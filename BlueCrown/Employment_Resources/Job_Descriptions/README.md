# Koware Group — Job Descriptions v0.4.0: Existing-Branch Upgrade

**Prepared:** 13 November 2026  
**State:** Complete draft deliverables; **not** committed, pushed, merged, adopted or signed.

This bundle **upgrades the collection already maintained** in `cupidblack/Koware_Management` on development branch `employment/job-descriptions-v0.1.0` (draft PR #4). It does not create a disconnected role collection or duplicate the `BlueCrown/Employment_Resources` path. The current source chart has 67 entries: 62 distinct employee position records (lines 5–66), three governance bodies/positions (lines 3–4 and 67), and two ownership constituencies (lines 1–2). This package contains:

- **62 substantial individual job descriptions**, each saved as `BlueCrown/Employment_Resources/Job_Descriptions/POSITION_SLUG/POSITION_SLUG_job_role.md` and containing duties, outputs, KPIs, hiring competencies, decision limits, risks, systems, reporting, source provenance and change history.
- **3 revised governance charters**, each in its previously used `Governance_Charters` path.
- A regenerated **master index** and **job-description document standard**, **source reconciliation and audit**, and a **governance-constituency interpretation note**.
- `ROLE_SOURCE_MANIFEST.json` mapping all 62 roles to 1-based chart lines and exactly one canonical path.
- `validate_collection.py`, a pure-standard-library QA runner; and `apply_to_existing_branch.py`, a safe Git-aware update script.

**Critical reconciliation:** The current live chart shows **Liaison Assistant** at chart line 14 (under Corporate Manager) and **Liaison Personnel** at chart line 17 (under Cooperative Intern). Some earlier platform responses incorrectly described two Liaison Assistants. The upgrade **keeps both existing canonical paths**; it does not delete `LIAISON_PERSONNEL`. The exact chart title of the Legal Journalist record is also preserved: “Legal Journalist (Law/Legislation vested Pastor/Theologian)”.

## Inspect and verify

From the unzipped bundle folder:

```bash
python validate_collection.py
```

The validator checks 62 separate job records; all 1-based chart lines 5–66; exact role titles and parent/child mapping; unique IDs and paths; numbered sections; duties, outputs and KPI evidence; three governance charters and 62 links in the master index. It also detects truncated or unexpected files and common placeholder markers. Nothing in this step accesses or modifies GitHub.

## Upgrade the **existing** Git branch

Use a **local Git checkout of Koware_Management**; do not point the script at a new empty folder. Ensure the previously developed 62-role collection exists and switch to the existing branch:

```bash
cd /path/to/Koware_Management
git switch employment/job-descriptions-v0.1.0
git status --short
```

Preview (read-only):

```bash
python /path/to/Koware_Job_Descriptions_Upgrade_v0.4.0/apply_to_existing_branch.py /path/to/Koware_Management
```

Apply (requires a clean working tree; preserves pre-upgrade contents in Git metadata backups):

```bash
python /path/to/Koware_Job_Descriptions_Upgrade_v0.4.0/apply_to_existing_branch.py /path/to/Koware_Management --apply
```

Then inspect and commit the changes to **that same branch**, and update its **existing draft PR #4** (no second PR needed):

```bash
cd /path/to/Koware_Management
git diff --stat
git diff --check
git add BlueCrown/Employment_Resources
git commit -m "Refactor Koware role descriptions and governance charters v0.4.0"
git push origin employment/job-descriptions-v0.1.0
```

**Note:** The commands above are guidance. They have **not** been run against your GitHub repository. The apply script never commits, pushes, opens a new PR, rewrites source chart data or deletes an existing role.

## Human decision gates before adoption

The chart describes organisational planning, not effective legal delegations or verified employees. The listed aliases and day numbers **do not** establish appointees, contracts, work schedules or qualifications. Management must validate duties and grading; qualified Ghanaian legal, finance, health/safety and regulated-sector reviewers must confirm current requirements. Particular open issues: audit independence where finance/records appear beneath Administrative Auditor; safeguards for intern-supervised liaison work; independent academic compliance escalation; and interpretation of the 25-space Stylists & Prep Coordinator indent.

For a line-by-line analysis of the material changes and review conditions, open `BlueCrown/Employment_Resources/JOB_DESCRIPTIONS_REFACTOR_AUDIT.md`.
