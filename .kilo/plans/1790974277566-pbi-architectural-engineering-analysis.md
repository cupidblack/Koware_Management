# Plan — Architectural Engineering Analysis of the Project Base Integration Initialization (BZJ-[PROJECT]-0004.01 … 0004.09)

## Context

**Input of record:** `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md` — the canonical PMO template. Lines 133–449 hold the `<<START PROJECT BASE INTEGRATION INITIALIZATION>>` block (sections `0004.01`–`0004.09`, then `[BZJ-PGBD-0004.1] Develop Project Charter`). The task brief in chat is a paste of this file plus framing.

**Supporting evidence:**
- `BlueCrown/Laboratory/development/bcrd-production-wordkflow-Kilo-0.5.txt` — strongest prior art. Defines the identifier grammar, stage bands, gates G0–G9, roles, tool tiers, and a 78-section ladder. **It has no pre-charter block** — it starts at `0004.1`. Marked `PROPOSED — requires human adoption`.
- `BlueCrown/Laboratory/development/bcrd-production-wordkflow-{Jules,Github}-0.5.txt` — alternative grammars, weaker.
- `BlueCrown/Laboratory/DEVELOPER TEMPLATE.md` — flat 4-digit grammar, `pgb/bzj-pgb-003` branch scheme, records Claude replaced by Kilo.
- `BlueCrown/Laboratory/AGENTS.md` §12/§13/§17/§22 — Architecture/Spec authority, Agent Roles, Pull-Request Rules, Final Principle.
- `BlueCrown/Laboratory/development/payment-gateway/docs/` — 19 real artifacts proving the practised naming (`Brief-Analysis-Report`, `Collaborative-Architecture-Verification`, `Architecture-Challenge`, `Architecture-Decision-Record`).

**Deliverable produced by this plan:** one new file —
`IAPD/AGENCY/IAPD-PMO-BZJ-PGBD-Architectural-Engineering-Analysis.md`

It is a **record** (already decided), not a template. It analyses and repairs the PBI block so the collaborating agents can produce an AEV against it. It does **not** modify `IAPD-PMO-PROJECT-TEMPLATE.md`.

---

## Decisions locked

| # | Decision | Ruling |
|---|---|---|
| D1 | Pre-charter numbering | Keep `0004.01`–`0004.09`; **renumber Charter to `0004.10`**. Zero inversions, no other anchor moves. |
| D2 | Artifact names | **Architectural Engineering Analysis (AEA) / Verification (AEV) / Challenge (AEC) / Challenge Closure (AECC).** Retire "Architectural Systems and Engineering …" and "Architectural Engineering … Verification" as duplicates. |
| D3 | Gate authority | **Named human authority passes every gate.** Agents issue findings at severity; all BLOCK findings resolved or human risk-accepted. Contradicts literal step 5 and is governed by `AGENTS.md` §22. |
| D4 | Roster | **Per-section assignable roster seeded by a project-level default assignment table.** Enforced independence check on every override. |
| D5 | Scope | **PBI block + reconciliation.** Bands 1–9 referenced, not rewritten. |

### Numbering model (from D1)

```
BZJ-[PROJECT] - <S><KK><P>.<SS>
                 S   stage band  (0 Initiating … 9 Closing)  — the project clock
                 KK  PMBOK knowledge area (04 Integration … 13 Stakeholder)
                 P   section position within that knowledge area
                 SS  sub-section, two digits, zero padded
```

Sort ascending by S, then KK, then P, then SS. Single-digit PM anchors are read zero-padded for ordering (`0013.1` → `0013.01`).

Resulting band 0, verified strictly ascending, no inversions:

```
0004.01  Project Base Integration Initialization Development
0004.02  Initial Project Template Generation Prompt
0004.03  Architectural Engineering of Project Proposal Prompt
0004.04  Architectural Challenge of Project Proposal Prompt
0004.05  Project Template Generation
0004.06  Architectural Engineering of Project Template
0004.07  Architectural Challenge of Project Template
0004.08  Project Template Initialization
0004.09  Project Management
0004.10  Develop Project Charter          <-- documented exception, see R3
0005.00  Initial Project Proposal and Problem Statement   (Kilo 0.5 ladder)
0013.01  Identify Agents, Reviewers and Stakeholders       (Kilo 0.5 ladder)
0013.02  Human Decision Gate and Authority Register        (Kilo 0.5 ladder)
0013.1   Identify Stakeholders                           <-- hand-off to PM ladder
---- band 1 ---- 1004.02 Engineering Control Plane Bootstrap … 9004.7 Close Project
```

Band-to-progress reading is preserved, including the confirmed convention: **`6009.4` = just past half way through the project.**

---

## Finding register — what the Analysis must correct

These are the substantive outputs. Each needs a ruling, not a restatement.

**F1 — Step 11 duplicates step 7 and orphans Level C.** Step 7 begins Level B (Analysis of the *Project Proposal Prompt*). Step 11 restates that same Analysis step with the same subject, then the brief ends. Level B never reaches a Challenge, and Level C (Analysis of the generated *Template*) never begins.
**Ruling:** the PBI is a **three-level nested loop where every level runs the identical AEA→AEV→AEC→AECC protocol**. Level A analyses the PBI itself; Level B analyses the Proposal Prompt; Level C analyses the generated Template. The bug is that only Level A and half of Level B were written out.

**F2 — The approval loop cannot converge.** *"Repeat steps 4 and 5 till all collaborating agents approve"* has no iteration cap, no deadlock path, and treats a fact disagreement as a preference disagreement.
**Ruling:** cap at **3 iterations**. Unresolved BLOCK after iteration 3 → mandatory human escalation with a written position paper; the human records an Accepted Risk or directs a specific change. "Two agents disagree on a **FACT**" is a STOP condition, not a loop iteration. Findings carry CRITICAL / HIGH / MEDIUM / LOW; all CRITICAL and HIGH must be resolved or human-accepted.

**F3 — Independence can collapse silently.** D4 allows a per-section roster override; nothing prevents the override naming the same platform as author, reliability reviewer, and adversarial reviewer.
**Ruling:** every section block carries an **Independence Check** field. If the override collapses any two of the three onto one platform, the dispatch is refused and a finding is raised against the section. Roster unavailability is recorded as a gap, never silently absorbed.

**F4 — Note 8's gate is self-contradictory.** *"an 'agent challenge before build' gate to assist in discovering potential issues after production code has been implemented."*
**Ruling:** two distinct gates, not one. **AEC (pre-implementation, in band 4)** attacks the architecture; **AEC-post (post-implementation, in band 8)** attacks the built code. See `bcrd-production-wordkflow-Kilo-0.5.txt:288` for the re-sequencing table.

**F5 — `0004.09 Project Management` is a mixed-band section.** Its content ("change management, procurement, timelines, deliverables, project after-life") belongs to bands 1–3 (`1004.2`, `2007.1`, `2010.1`, `3012.1`) and band 9 (`9004.7`).
**Ruling:** split. `0004.09` retains only the PMO operating model for *running a project under the template* — team-up, brief, change-management invocation, contractor requirements, deliverable hand-off. Everything else defers to its band anchor. Violates the band-dominates rule if left whole.

**F6 — `0004.08` note 2.ii contradicts an adopted decision.** It assigns long-lived branches `main/kilo-code`, `main/google-jules`, etc. The Kilo 0.5 analysis already ruled this superseded (`bcrd-production-wordkflow-Kilo-0.5.txt:634`).
**Ruling:** per-task branches lead, agent name trails: `pgb/bzj-<proj>-<section>-impl`, `pgb/bzj-<proj>-<section>-s1-fix-idempotency`, `evidence/bzj-<proj>-<section>-<agent>`. Two agents editing one branch is the defect being removed.

**F7 — `0004.08` note 4 is unresolved.** `.git/skills/project-name` vs `.github/skills/project-name`.
**Ruling:** `.github/skills/`. `.git/` is not a working-tree directory; nothing checked out there is readable by a tool or a contributor.

**F8 — Three competing grammars and three orphan identifier families are live.** Must be crosswalked or the ladder stays unreadable.
**Ruling:** crosswalk table covering —
- Grammars: Kilo `BZJ-[PROJECT]-<S><KK><P>.<SS>` (adopted) vs Jules `<4d>.<anchor>.<container>` (ambiguous: `.11` reads as SS=11 or anchor 1/sub 1) vs GitHub `2200.0/2300.0/…` (hundreds blocks, bands 5–6 missing) vs DEVELOPER TEMPLATE `<KK><P><SS>` flat 4-digit, no band.
- Orphans: `BZJ-PGB-001` / `-002` / `-003` (`AGENTS.md` §12/§13), `BZJ-PGB-0100`, `-0200`, `-0210`, `-4324`, `-0620`, and the 19 `BZJ-PGB-03xx` artifacts.
- Defect D10: `BlueCrown/development-workflow-guide.md` vs `BlueCrown/Laboratory/development/development-workflow-guide.md` carry the same material at `0100/0200` vs `0010/0020` numbering with no canonical path declared. Crosswalk both; declare neither canonical in this pass.

**F9 — `0004.02` and `0005.00` overlap.** `0004.02` already contains "Initial Project Proposal"; `0005.00` is "Initial Project Proposal and Problem Statement".
**Ruling:** state the boundary explicitly. `0004.02` is the **worked example** (BZJ-PGBD's own proposal, which bootstraps template creation). `0005.00` is the **instantiated** proposal for the project the generated template produces. Neither may re-derive the other.

**F10 — `0004.03` reaches Approval but not Challenge.** Its step 4 says an architectural challenge follows Approval; `0004.04` exists to hold it. Meanwhile steps 7–11 omit the challenge entirely for Level B.
**Ruling:** the four-artifact protocol is atomic per level — AEA, AEV, AEC, AECC, then finalise. No level may skip AEC.

**F11 — Notes 6/7 require dual readability that no artifact currently delivers.** A PM must map suffix → PM process; a developer must map leading digit → stage.
**Ruling:** every section block header carries `Subtitle (PMBOK n.n <PM process name>)`, `Knowledge Area`, `Process Group`, `Stage Band`.

---

## Ordered tasks

1. **Header block.** Adopt D1–D5. State the grammar, the sort rule, the band table, and the `0004.10` exception (R3).
2. **Ladder section.** Emit band 0 in full; assert strictly ascending order; state the hand-off at `0013.1` into the Kilo 0.5 ladder for bands 1–9, cited by path and line. Declare bands 1–9 out of scope for re-derivation.
3. **Finding register.** F1–F11 with the rulings above, each tagged FACT (path + line) / INFERENCE / RECOMMENDATION.
4. **Default agent assignment table.** Per D4: platform → default role. Codex/Copilot authorship, Jules independent reliability, Kilo orchestration-and-gates, GitHub Issues/Actions as control plane. Include the D4 independence invariant (author ≠ reliability reviewer ≠ adversarial reviewer) and the role-collapse failure mode.
5. **Section block format.** Define the standard block: header fields, Purpose, Inputs, Activities, Notes, `[PROMPT — <platform>]` one per assigned agent, `<<START …>>` / `<<STOP …>>` attachment markers, Deliverables, Exit Criteria, Stop Conditions, Independence Check, Human Gate.
6. **Protocol definition.** The four-artifact protocol, run identically at Levels A, B, C. Include the fan-out rule (agents do not see each other's responses until AEV synthesis), evidence rules, severity table, the 3-iteration cap, and the escalation path.
7. **Section blocks `0004.01`–`0004.09`.** Each: purpose, inputs, activities, per-agent prompt blocks, placeholders, deliverable, exit criteria, stop conditions. Embed notes and placeholders **inline where the PM meets them**, per source Note 5 — not collected at the end.
8. **Crosswalk tables.** F8 grammar + identifier crosswalks. Include the corrected location for skills (F7) and branch standard (F6).
9. **Gates.** G0 Charter Gate at `0004.10`; the pre-charter blocks carry **no gate of their own** — they are inputs to G0. State this so no gate is implied where none exists.
10. **Out-of-scope register.** Name what this document does *not* decide: bands 1–9 re-derivation, the D10 canonical-path ruling, migration of the 19 `BZJ-PGB-03xx` artifacts, and any change to `IAPD-PMO-PROJECT-TEMPLATE.md`.

### Open item carried into the document, not resolved here

**R3 — mixed anchor widths.** `0004.10` is two digits while `0013.1`, `1004.2`, `8011.7` remain single-digit. Chosen resolution: do **not** renumber the other 48 anchors; record `0004.10` as a documented exception with its reason (it is the boundary between the pre-PMBOK framework block and the PM process ladder), and rely on the zero-padded read rule for ordering. The alternative — normalising all 49 anchors to two digits — is rejected here because it churns the canonical 49-process list the brief preserves. Flag as reversible if the Verification rejects it.

---

## Risks

| Risk | Mitigation |
|---|---|
| Adopting a grammar that conflicts with Kilo 0.5's 78-section ladder | Ladder section cites bands 1–9 by path+line and states they are inherited unchanged; F8 crosswalk makes any residual conflict visible |
| Analysis drifts into re-authoring bands 1–9 | Out-of-scope register, task 10 |
| Per-section roster overrides collapse independence | Independence Check field, F3; dispatch refused on failure |
| "Analysis" read by agents as a template and filled in | Mark the file a record in its header; only the `<<START/STOP>>` markers in `IAPD-PMO-PROJECT-TEMPLATE.md` are fill targets |
| Unbounded review loop returns | 3-iteration cap + human escalation, F2 |

---

## Validation

1. **Ladder sort check** — sort every emitted identifier by (S, KK, P, zero-padded SS); assert strictly ascending. `0004.09` < `0004.10` < `0005.00` < `0013.01` < `0013.02` < `0013.1` < `1004.02`.
2. **Band-dominates check** — every section's Process Group matches its band digit. F5 is the known prior failure; confirm `0004.09` no longer carries band 1–3 or band 9 content.
3. **Inversion sweep** — grep the emitted document for `0004.0[1-9]` and `0004.1` co-occurring in any ordered list.
4. **Protocol completeness** — each of Levels A, B, C names all four artifacts. F1/F10 are the prior failures.
5. **Traceability** — every finding F1–F11 cites a file path and line number in this repo. No unlabelled claims.
6. **Consistency vs Kilo 0.5** — confirm every inherited band 1–9 identifier from the ladder at `bcrd-production-wordkflow-Kilo-0.5.txt:748-856` appears unchanged in the crosswalk.
7. **Human review** — the document is a record and requires the D3 human gate before any section of `IAPD-PMO-PROJECT-TEMPLATE.md` is edited to match it.

---

## Out of scope

- Editing `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md`.
- Re-deriving bands 1–9.
- Ruling which `development-workflow-guide.md` is canonical (defect D10).
- Migrating the 19 existing `BZJ-PGB-03xx` artifacts to the new grammar.
- Producing the AEV. This plan produces the **AEA only**; the AEV is the collaborating agents' deliverable, and `IAPD-PMO-PROJECT-TEMPLATE.md:121` assigns that step to them.