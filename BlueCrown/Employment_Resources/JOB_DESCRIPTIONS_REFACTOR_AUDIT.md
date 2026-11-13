# Job Description Collection — Refactor Audit

**Audit date:** 2026-11-13  
**Repository:** `cupidblack/Koware_Management`  
**Working branch:** `employment/job-descriptions-v0.1.0`  
**Status:** Development audit; human approval remains required.

## Scope and finding

An earlier collection already exists on the named development branch. It contains an index, a document standard, role folders, individual descriptions for the 62 charted operational positions, and three separate governance charters. This refactor builds on that collection rather than creating a parallel directory. The canonical collection path is `BlueCrown/Employment_Resources/Job_Descriptions/`; the repeated `BlueCrown/Employment_Resources` segment in the example URL is not used because the existing canonical tree is already established.

The authoritative chart reviewed is [`Organizational_Chart_Structure-Aliased_Checks.txt`](https://github.com/cupidblack/Koware_Management/blob/main/Organizational_Chart_Structure-Aliased_Checks.txt), main blob SHA `7b6ed7f1c9ce4d162832f96eadb4c6140c5211f8`. It has 62 individual positions on lines 5–66. Governance constituencies and collective bodies are not counted as ordinary employee positions; the existing separate governance charters should remain separate.

## Material correction made

The existing Liaison Assistant description said it reported to the Liaison Officer. That is inconsistent with the source chart: the two roles are at equal indentation beneath Corporate Manager. The description has been corrected to report to Corporate Manager and now states the source-line interpretation explicitly. Liaison Personnel is a distinct role beneath Cooperative Intern; the chart does **not** show a second Liaison Assistant. This avoids an invented duplicate position and preserves the actual hierarchy.

The job-description standard has been revised to version 0.3.0 to set uniform content, provenance, authority, approval and quality-control requirements for the existing collection. Key safeguards include proportional qualifications; no invented pay, hours, benefits, targets, delegated powers or appointments; respectful treatment of service workers; supervised intern scope; and independent audit boundaries.

## Collection map and exclusions

- The index at [`JOB_DESCRIPTIONS_INDEX.md`](./JOB_DESCRIPTIONS_INDEX.md) is the entry point to the existing role files.
- Individual job descriptions remain in their current role-specific folders under `Job_Descriptions/`.
- The Members Representative, Governing Committee and Board of Directors are documented through governance charters, not employee job descriptions.
- Stakeholders and shareholders are governance constituencies. The chart's “God as CEO” wording is treated as a governance principle, not a job appointment.
- Names/aliases in the chart are not treated as verified employees or as evidence of qualifications.

## Review findings and adoption gates

The collection is a controlled draft, not an approved HR or legal instrument. Before publication or recruitment use, authorised reviewers should:

1. Compare every title, source line and reporting edge against the currently approved chart; resolve any chart changes and synchronize the index.
2. Check each description for role-specific responsibilities, outputs, decision boundaries, escalation route and role-specific performance evidence—not merely generic template language.
3. Confirm actual departmental mandates, service workflows, access rights, staffing and reporting cadence.
4. Approve delegations separately, especially finance, procurement, contract signing, hiring, discipline, public communications, academic records/awards, data access, legal matters and safety decisions.
5. Obtain Human & Employment Resources and relevant Ghanaian legal/specialist review for employment, tax, privacy, safeguarding, occupational safety and regulated professional duties.
6. Set measurable targets only after workload, resources and baselines are established; complete the approval fields and revision history before making any document effective.

## Change record

| Version | Date | Change |
|---|---|---|
| 0.1.0 | 2026-11-13 | Initial audit of the existing branch collection; corrected Liaison Assistant reporting relationship; strengthened the shared document standard and review gates. |
