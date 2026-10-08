# PBIM Generic Edition v3.01.09 — Independent Review Closure Amendments

**Base:** `PBIM_Generic_Edition_v3.01.08.md`  
**Review date:** 2026-10-08  
**Disposition:** RETURN — required corrections below.  
**Purpose:** Exact amendment set required to close the defects identified in the independent baseline review.

> This file is an amendment-complete patch, not a byte-for-byte reprint of v3.01.08. The public GitHub blob was reviewable, but its raw file was not available to the execution environment for safe byte-preserving reconstruction. Therefore the underlying v3.01.08 file must be patched with these exact changes before v3.01.09 is published.

## 1. Version / status

Replace the document metadata:

- Version: `v3.01.09`
- Status: `CONTROLLED GENERIC CANDIDATE — INDEPENDENT REVIEW RETURN CORRECTIONS`
- Patch basis: `v3.01.08 + Prompt Integrity + Standards Currency + Role/Authority Corrections`
- Review date: `2026-10-08`

Add to the Important notice:

> This revision contains specification corrections only. It does not establish that any named CI workflow, registry, emergency TTL mechanism, durable-reference mechanism, or other control is implemented or operating.

## 2. Prompt 1 — repair resource block

Replace:

```text
<> {{DURABLE-RESOURCE-SET}} <>
```

with:

```text
<<START PBIM Source Set + Assurance Evidence>> {{DURABLE-RESOURCE-SET}} <<STOP PBIM Source Set + Assurance Evidence>>
```

The resource label must resolve to the actual supplied source/assurance material when the prompt is instantiated.

## 3. Prompt 9 — repair resource block

Replace:

```text
<> {{DURABLE-RESOURCE-SET}} <>
```

with:

```text
<<START Approved PBIM Baseline + Project Context Sources>> {{DURABLE-RESOURCE-SET}} <<STOP Approved PBIM Baseline + Project Context Sources>>
```

The prompt must not be executable until the resource set is populated or each missing resource is explicitly `UNKNOWN` with owner, evidence requirement, target date and advancement consequence.

## 4. Prompt 23 — repair resource block

Replace:

```text
<> {{DURABLE-RESOURCE-SET}} <>
```

with:

```text
<<START Final PBIM Transition Package + Readiness Evidence + Registers>> {{DURABLE-RESOURCE-SET}} <<STOP Final PBIM Transition Package + Readiness Evidence + Registers>>
```

The resource set must include, at minimum, the final transition package, readiness evidence, applicable registers, material findings/dispositions, and the current approved PBIM baseline.

## 5. Prompt integrity register correction

In the consolidation/amendment register, change the treatment of prompt boilerplate from an assertion of full consolidation to:

> Repeated prompt boilerplate is **standardized under the Universal Prompt Engineering Contract**, while standalone prompt instances retain the minimum self-contained contract needed for safe execution. This is consolidation by governing contract, not physical elimination of repeated wording.

Replace the final consolidation claim with:

> Repeated control objectives are consolidated into governing models where the control objective is materially identical. Prompt instances may repeat mandatory execution boilerplate when standalone integrity requires it; such repetition is not counted as a separate control. Prompt-specific content remains limited to role, objective, context, resources, instructions, tests, stop conditions and decision vocabulary.

## 6. Prompt 13 role-authority correction

Replace:

```text
[Designation: Lead Agent / Authorized Human Closure Authority]
ROLE Lead Agent / Authorized Human Closure Authority.
```

with:

```text
[Designation: Lead Agent for preparation; Authorized Human Closure Authority for disposition]
ROLE Lead Agent prepares the closure package. Only the explicitly recorded authorized human closure authority may make the closure/baseline decision.
```

Add to the instructions:

> The Lead Agent may synthesize and recommend but may not exercise the human closure authority merely by being designated Lead Agent. The authorized human decision-maker must be identified in the Human Authority Register or equivalent authoritative delegation record.

## 7. Prompt 17 role-authority correction

Replace:

```text
[Designation: Lead Agent / Authorized Assurance Authority]
ROLE Lead Agent / Authorized Assurance Authority.
```

with:

```text
[Designation: Lead Agent for preparation; Authorized Assurance Authority for disposition]
ROLE Lead Agent prepares the closure package. Only the recorded Authorized Assurance Authority may make the closure/baseline decision.
```

Add:

> Where the Authorized Assurance Authority is also a Lead Agent, the record must state the independence limitation and the independent control that remains. The same actor may not be treated as independent verification merely because the role label changes.

## 8. Prompt 16 combined-role correction

Replace:

```text
[Designation: Lead Agent / Collaborating Agents]
ROLE Lead Agent / Collaborating Agents.
```

with:

```text
[Designation: Lead Agent for engineering; Collaborating Agents for independent challenge/verification]
ROLE Lead Agent engineers the candidate. Collaborating Agents perform the independent challenge/verification. The Lead Agent may not self-certify the independent result.
```

## 9. Emergency delegation / H0 boundary correction

Immediately after the 72-hour emergency delegation rule add:

> Emergency delegation does not, by itself, confer authority to approve PBIM-to-Charter transition. Unless the governing organization expressly and lawfully provides otherwise, `AUTHORIZE CHARTER TRANSITION` remains reserved to H0 or the formally recorded H0 delegate identified for that decision. Emergency operational authority and Charter-transition authority are separate authority grants and must not be conflated.

Also replace the H0 confirmation wording in the emergency delegation field list with:

```text
H0 confirmation/reconciliation requirement, except where the governing authority model expressly provides an alternative lawful confirmation authority
```

## 10. Standards currency correction

Replace the ISO 31000 statement:

```text
ISO 31000:2018 remains current following confirmation
```

with:

```text
ISO 31000:2018 remains the published/current edition for use while revision is underway; ISO currently records the edition at review stage 90.92, “International Standard to be revised”. ISO/CD 31000 revision material must not be treated as the published standard.
```

This reflects ISO's current status as of 2026-10-08.

Replace the ISO/IEC 27001 statement:

```text
ISO/IEC 27001:2022 remains a published reference, subject to applicable amendments and organizational adoption.
```

with:

```text
ISO/IEC 27001:2022 remains the published International Standard, with Amendment 1:2024 applicable where relevant; applicability, adoption and any certification claim must be assessed separately.
```

Retain the PMBOK Eighth Edition statement. PMI identifies the Eighth Edition as the current edition.

Update the standards reference table accordingly:

| Reference | Status / use |
|---|---|
| PMBOK Guide — Eighth Edition | Current PMI edition; advisory project-management reference |
| ISO 21502:2020 | Published International Standard; currently under revision |
| ISO/CD 21502 Edition 2 | Committee draft under development; never treat as a published standard |
| ISO 31000:2018 | Published/current edition while revision is underway |
| ISO/IEC 27001:2022 + Amd 1:2024 | Published security-management reference where applicable |
| ISO/IEC 42001:2023 | AI-management reference where AI governance is in scope |
| NIST AI RMF 1.0 | Voluntary AI-risk reference unless adopted |
| OWASP ASVS 5.0.0 | Application-security verification reference where applicable |

## 11. Risk-scaling clarification

Add after the risk-profile table:

> Risk scaling changes the amount and frequency of ceremony, evidence depth, review breadth and assurance intensity. It does not permit omission of a control that is legally mandatory, safety-critical, security-critical, authority-critical, evidence-integrity-critical, or explicitly designated protected. If applicability is uncertain, the control remains protected until applicability is resolved by an authorized decision.

## 12. Evidence limitation correction

Change any closure language that describes the following as “closed” solely because the specification now describes them:

- durable-reference implementation;
- Task Packet CI enforcement;
- emergency TTL enforcement;
- canonical Identifier Registry implementation;
- privileged-account reconciliation;
- operational stop/reset enforcement.

Use:

> **Closed in specification / implementation unproven**

unless independently evidenced.

## 13. Required v3.01.09 quality-gate additions

Add these checks to the prompt/document quality gate:

- [ ] Every operational prompt has exactly one syntactically valid prompt start/end pair.
- [ ] Every prompt resource block uses matching `<<START ...>>` / `<<STOP ...>>` markers.
- [ ] No prompt contains an empty or malformed resource block.
- [ ] Prompt 9 and Prompt 23 resource sets are populated or explicitly unresolved.
- [ ] Combined-role prompts identify preparation authority separately from decision authority.
- [ ] Emergency delegation cannot silently expand Charter-transition authority.
- [ ] Standards status reflects the review date, not only the publication date.
- [ ] Standards amendments are included where material.
- [ ] Specification closure is not represented as implementation evidence.

## 14. Decision consequence

After these amendments, the document may be reconsidered as:

`v3.01.09 — CONTROLLED GENERIC CANDIDATE`

It should **not** be represented as implementation-verified or independently verified merely because these textual amendments are applied.

## 15. External status checks used in this review

- PMI identifies the PMBOK Guide — Eighth Edition as current and published November 2025.
- ISO records ISO 21502:2020 as published but under revision, with ISO/CD 21502 Edition 2 under development.
- ISO records ISO 31000:2018 as published and currently under revision at stage 90.92.
- ISO/IEC 27001:2022 is published and has Amendment 1:2024.

These status checks support the amendments above; they do not establish project-specific applicability or legal authority.
