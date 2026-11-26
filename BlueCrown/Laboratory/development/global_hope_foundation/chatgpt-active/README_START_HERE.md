# Hopeful Way Oxford House — Consolidated implementation package
**Release:** v1.00 | **26 November 2026 | Ghana**

The requested strategy has been rewritten, corrected against the publicly accessible Hopeful Way and Oxford House sources, and converted into a practical starter system with operational copy and code.

## Files in this package

1. `01_MASTER_IMPLEMENTATION_PLAYBOOK.md` — strategy, policies, scope review, decision gates, 2026 timelines, implementation steps, conversion funnel, safeguarding, governance, metrics and go-live conditions.
2. `02_GOOGLE_FORM_BUILDER.gs` — executable Google Apps Script that creates a **closed** consent-first interest Form, a restricted Google Sheet, pipeline/relationship tabs and an automated non-identifying email alert to `support@buzzjuice.net`. The account owner must run and authorise it.
3. `03_OUTREACH_AND_PRESENTATION_COPY.md` — actual WhatsApp copy (initial, supervisor-forward, Day 3, Day 7, Day 14), applicant replies, approved generic institutional cover-letter body, supervisor brief, and consent-dependent testimony.
4. `04_PORTAL_AND_AMELIA_SETUP.md` — exact steps to publish the two branded URLs, keep the form visible immediately and create/test a separate Amelia presentation service on Koware.org.
5. `05_CASEWORK_PRIVACY_AND_REVIEW_FORMS.md` — applicant information sheet, interview guide, 80% resident vote checklist, move-in induction, withdrawal, retention and incident procedures.
6. `06_STARTER_INSTITUTION_RESEARCH_LEADS.csv` — research-stage publicly listed leads, with phone numbers and source URLs where found; availability, accuracy and permission have not been independently verified for each row. **Verify the current supervisor and permission to contact before messaging.**
7. `07_HOPEFULWAY_CAMPAIGN_TRACKER.xlsx` — polished empty campaign CRM/pipeline workbook with institutional leads, cases by pseudonymous ID, house availability checks, and a KPI dashboard. **Do not enter patient clinical details.**
8. `08_MASTER_PLAYBOOK.docx` — editable 11-page rendered/inspected Word copy of the main playbook.
9. `09_CONVERGENCE_DECISIONS_AND_TRACEABILITY.md` — explicit 23/23 original-item reconciliation, prior-platform conflict resolution, proof gates, and QA status.

## What was reviewed
- Public official Hopeful Way website, including its November 2026 transitional-programme statement.
- Official Oxford House model, FAQ and traditions, including 80% new-member admission vote.
- Recovery Africa Ghana/Hopeful Way background; Ghana DPC guidance and Act 843.
- The full publicly accessible earlier-platform responses file and its recurrent recommendations/limitations.
- YouTube item title and public link. Its audiovisual contents were not directly reviewed.
- GitHub existence and path of the unsigned presentation-letter PDF. The PDF's contents could not be independently opened as a PDF in this environment, the signed version was not accessible here, and a full folder audit was not possible. The package **does not claim otherwise**.

## Execute in sequence

**Gate 0 (no outreach):** Confirm signed authority, house-specific charter wording, occupancy/vacancies, actual costs, current bylaws, lease, Chairperson contact, privacy compliance and adult-consent process with Hopeful Way decision-makers and residents.

**Build (30 Nov–3 Dec 2026):** Run `createHopefulWayIntake()` in the approved Google account. Review/approve the generated form and linked Sheet. Make Buzzjuice's exact public path redirect to the real respondent URL, and make Koware's path redirect to Buzzjuice. Set up Amelia and perform the test booking.

**Launch (from 4 Dec 2026):** Contact 5–10 verified Greater Accra institutions through their agreed channels, send approved short copy and an unaltered signed authorisation if appropriate, nurture respectfully, obtain adult consent, conduct video/house interviews and let the residents decide admissions.

**Weekly:** Track actual move-ins, not just form submissions. Review the lease and contingency by **21 Dec 2026**, with the stated house-occupancy checkpoint on **31 Dec 2026**.

**Public link warning:** `https://buzzjuice.net/hopefulway-oxfordhouse` and the secondary `https://koware.org/hopefulway-oxfordhouse` are proposed routes. **This package did not publish them**. Do not distribute outreach messages containing those links until tested.

## Security
Never commit completed Form/Sheet responses, medical histories, sponsor financial data, signed personal documents, passwords, unpublished charter files, API secrets or other casework material to a public GitHub repo. Enable account MFA and restrict access by name.
