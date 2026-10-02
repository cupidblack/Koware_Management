# IAPD / PMO — ARCHITECTURAL ENGINEERING ANALYSIS

## OF THE PROJECT BASE INTEGRATION INITIALIZATION

```
================================================================================
DOCUMENT IDENTITY
================================================================================
  Document ................ IAPD-PMO-BZJ-PGBD-Architectural-Engineering-Analysis.md
  Document type ........... AEA — Architectural Engineering Analysis (RECORD)
  Analyzes ............... IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md
                            sections 0000.01 - 0000.09 and the boundary at
                            [BZJ-PGBD-0004.1] Develop Project Charter
  Generic identifier ..... BZJ-[PROJECT]
  Concrete example ....... BZJ-PGBD (Buzzjuice Payment Gateway Bridge Development)
  Analysed by ............ Lead Agent, ChatGPT / Codex (architecture role)
  Orchestrated by ........ Kilo Code (orchestration and gate running)
  Status ................. PROPOSED — verified CONDITIONALLY; awaiting the
                             Architectural Engineering Challenge and the human
                             gate at 0004.1 / G0
  Revision ............... 1.1  (supersedes 1.0, never baselined)

  THIS IS A RECORD, NOT A TEMPLATE.
  Every {{placeholder}} below is a live gap awaiting a project to fill it.

  REVISION HISTORY
    1.0  Initial analysis. Proposed Charter renumber 0004.1 -> 0004.10 to
         clear the 0004.09 / 0004.1 collision.
    1.1  Supersedes 1.0 after the AEV and the human gate. RULING D6 moves the
         block to knowledge area 00 (0000.01 - 0000.09) and REJECTS the
         Charter renumber. 0004.1 Develop Project Charter is retained unchanged.
         1.0 was never baselined, so no competing copy is left in the tree.

  PROVENANCE OF REVISION 1.1
    AEV   Architectural-Engineering-Verification-Codex.txt
          (GitHub cupidblack/Koware_Management, BlueCrown/Laboratory/development)
          49 sections. Disposition: CONDITIONALLY VERIFIED, NOT BASELINED.
          Sections 7 and 8 REJECTED — see Part 2.12. Sections 1-6, 9-28 and
          29-46 ACCEPTED, four of which improve this document (recorded at
          Part 7.4).
    Human gate ruling: knowledge-area-00 relocation adopted; Charter anchor
          0004.1 retained.

  Companion artifacts in the four-artifact protocol:
    AEA   Architectural Engineering Analysis      THIS DOCUMENT
    AEV   Architectural Engineering Verification produced by collaborating agents
    AEC   Architectural Engineering Challenge    produced by collaborating agents
    AECC  Architectural Engineering Challenge Closure
================================================================================
```

---

## HOW TO READ THIS DOCUMENT

The user brief names the same artifact four different ways — "Architectural
Engineering Analysis", "Architectural Systems and Engineering Analysis",
"Architectural Systems and Engineering Verification", "Architectural Engineering
Verification". **Ruling D2** standardises on the shorter set and retires the
others as duplicates.

Every claim below is tagged:

| Tag | Meaning |
|---|---|
| `FACT` | Carries a file path and line number, or a command and its output |
| `INFERENCE` | A reasoned conclusion. Two agents agreeing on an inference does not upgrade it to a fact |
| `RECOMMENDATION` | A proposal requiring approval at the human gate |

Evidence rule inherited from `bcrd-production-wordkflow-Kilo-0.5.txt:677-687`.

---

# PART 0 — RULINGS

## 0.1 DECISIONS LOCKED BEFORE THIS ANALYSIS WAS WRITTEN

| # | Question | Ruling | Authority |
|---|---|---|---|
| D1 | How does the pre-charter block sort before the Charter? | **SUPERSEDED BY D6.** Originally: renumber the Charter | Project authority |
| D2 | Which artifact name set? | **Architectural Engineering Analysis / Verification / Challenge / Challenge Closure** | Project authority |
| D3 | Who passes a gate? | **A named human authority.** Agents raise findings at severity | Project authority |
| D4 | Who authors, and what is the roster? | **Per-section assignable roster, seeded by a project default table**, with an enforced independence check | Project authority |
| D5 | Scope of this document | **PBI block + reconciliation.** Bands 1–9 referenced, not re-derived | Project authority |
| **D6** | **Where does the pre-charter block live?** | **Knowledge area `00`: `0000.01`–`0000.09`. `0004.1 Develop Project Charter` is RETAINED unchanged.** Supersedes D1 | Project authority, at the human gate |

## 0.2 THE NUMBERING PROBLEM, AND HOW IT WAS ACTUALLY RESOLVED

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:147` — "Review and develop
this section 0004.01 to 0004.09 before Project Charter Development."

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:431` — the block is followed by
`[BZJ-PGBD-0004.1] Develop Project Charter`.

`FACT` `BlueCrown/Laboratory/development/bcrd-production-wordkflow-Kilo-0.5.txt:222-227`
— PM anchor identifiers keep their native one-digit form, and **for ordering only**
a one-digit form is read as zero-padded.

Applied to the identifiers as originally published:

```
  0004.01  ->  0004.01   (sub-section  1)
  0004.09  ->  0004.09   (sub-section  9)
  0004.1   ->  0004.01   (sub-section  1)   <-- COLLIDES with 0004.01
```

`INFERENCE` Two independent defects, both located **inside** knowledge area 04.

1. **Collision.** `0004.01` and `0004.1` are the same sortable value. The ladder
   is not merely mis-ordered; it is ambiguous.
2. **Inversion.** `0004.09` (9) sorts after `0004.1` (1), contradicting line 147.

Both are fixed by moving the block **out of** knowledge area 04, not by moving
the anchor within it. `FACT` This is exactly the AEV's objection, and it is
correct. Revision 1.0 proposed moving the Charter instead; that treated the
symptom and left the underlying collision class intact.

`RECOMMENDATION` — **RULING D6** introduces a tenth knowledge area:

```
  00  Project Framework Initialization (PRE-PMBOK)
```

`00` sorts before `04` as a knowledge area, so `0000.01`–`0000.09` precede
`0004.1` under the **ordinary** ordering rule at 0.5 — no special case, no
renumbered anchor, no change to bands 4–9. See Part 1.2 for the inversion test.

`INFERENCE` This is also semantically honest. The nine sections do not execute
PMBOK 4.1; they build the framework that 4.1 will later be run through. A
knowledge area that admits pre-PMBOK work states that in the identifier itself.

`FACT` The AEV independently reached the same conclusion by a different route.
AEV section 9 established "PM process identifiers are immutable reference
anchors" — a constraint **not present** in the source brief, since
`IAPD-PMO-PROJECT-TEMPLATE.md:77` offers renumbering as a legitimate move.
Holding that immutability is what forces the block out of area 04.

## 0.3 THE IDENTIFIER GRAMMAR

```
================================================================================
  BZJ-[PROJECT] - <S><KK><P>.<SS>
================================================================================
  BZJ-[PROJECT]   project prefix, fixed for the life of the project
  S               stage band, 1 digit — THE PROJECT CLOCK
  KK              knowledge area, 2 digits (00 plus PMBOK 04-13)
  P               section position within that knowledge area, 1 digit
  SS              sub-section, 2 digits, zero padded
================================================================================
```

`FACT` Grammar adopted from `bcrd-production-wordkflow-Kilo-0.5.txt:193-210`,
which resolved a direct conflict between two competing rules. `FACT`
`bcrd-production-wordkflow-Kilo-0.5.txt:162-179` records both:

- **Rule A** (`IAPD-PMO-PROJECT-TEMPLATE.md:343`) — "four digits… the second
  digit is used as the section counter whereas the last two digits identify a
  specific subsection. For instance, BZJ-PGB-4324 means subsection 24 of section
  43." Purely ordinal. Carries no process meaning.
- **Rule B** (`IAPD-PMO-PROJECT-TEMPLATE.md:53`) — the 49 PM processes, each
  prefixed by a band digit so the lifecycle sequence is preserved.

`RECOMMENDATION` Rule B is adopted; Rule A is retired. Rule A cannot satisfy
source Note 6 (`IAPD-PMO-PROJECT-TEMPLATE.md:533`), which requires a project
manager to relate a suffix to one or more PM processes, nor Note 12
(`:557`), which requires a developer to read stage off the identifier.

## 0.4 KNOWLEDGE AREA DIGIT MAP

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:322-334` for `04`–`13`.
`RECOMMENDATION` `00` is added by ruling D6 and is **workflow-local, not PMBOK**.

```
  00  Project Framework Initialization   PRE-PMBOK — added by D6. Not a PMBOK
                                          knowledge area. Holds the sections
                                          that build the framework the 49 PM
                                          processes will later run through.
  04  Project Integration Management      09  Project Resource Management
  05  Project Scope Management            10  Project Communications Management
  06  Project Schedule Management         11  Project Risk Management
  07  Project Cost Management             12  Project Procurement Management
  08  Project Quality Management          13  Project Stakeholder Management
```

Note that PMBOK defines 10 knowledge areas, not 49. The 49 processes are spread
across them; P subdivides further and is never reused. `00` makes eleven.

`INFERENCE` `00` must be labelled pre-PMBOK in every artifact and section header
that cites it. A reader who sees `0000.03` and searches for PMBOK knowledge area
00 will find nothing; the header must supply the meaning directly.

## 0.5 THE ORDERING RULE

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:211-236`

Sections are ordered by, in strict precedence:

1. stage band digit `S`, ascending
2. knowledge area digits `KK`, ascending
3. section position digit `P`, ascending
4. sub-section `SS`, read as a zero-padded two-digit integer, ascending

**Inversion test.** Sort the whole ladder and confirm it is strictly ascending.
Any inversion means a section was numbered in the wrong band and must be
renumbered, not re-described.

`RECOMMENDATION` This rule is **uniform** — no case depends on whether the
sub-section has one digit or two. AEV section 8 proposed a second rule placing
two-digit custom sub-sections before the one-digit PM anchor at the same
knowledge-area and position. That alternative was tested and rejected at
Part 2.12: it fixes knowledge area 04 only by inverting eight sections across
bands 4–9.

## 0.6 THE BAND TABLE — THE PROJECT CLOCK

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:238-269`

```
  0xxxx   INITIATION                 project is being chartered and templated
  1xxxx   PLANNING A                 control plane, scope, schedule frame
  2xxxx   PLANNING B                 schedule, cost, quality, resource,
                                      communications and risk plans
  3xxxx   PLANNING C                 risk analysis, procurement, engagement
  4xxxx   EXECUTION A                analysis, decisions, specifications,
                                      dispatch — the design spine
  5xxxx   EXECUTION B                deliverable quality and resources
  6xxxx   EXECUTION C                implementation, code checks, testing,
                                      team, communications, risk response,
                                      procurement, human approval
  7xxxx   MONITORING AND CONTROL A   work, change control, scope
  8xxxx   MONITORING AND CONTROL B   schedule, cost, quality, resources,
                                      communications, risk, procurement,
                                      stakeholders, independent review
  9xxxx   CLOSING
```

`FACT` `IAPD-PMO-PROJECT-TEMPLATE.md:533` — "6009.4 would mean just passed half
way through the project." `bcrd-production-wordkflow-Kilo-0.5.txt:267-269`
confirms this as the intended semantics of the leading digit.

**Band dominates.** If a proposed section does not belong to the stage its band
implies, the number is wrong, not the description.

## 0.7 THE TWO REQUIRED READINGS

Source Note 6 (`:533`) and Note 7 (`:537`) require dual readability. Note 7:
"Where an identifier already has a name referenced by a project management
process, a subtitle should be implemented related to the programming or coding
section or phase."

Every section block in Part 6 therefore carries a header with `Subtitle`, the
PMBOK anchor, `Knowledge Area`, `Process Group` and `Stage Band`. The
requirement is satisfied by construction, not by convention.

## 0.8 THE PBI BLOCK IS PRE-PMBOK WORK, AND NOW SAYS SO

`INFERENCE` Sections `0000.01`–`0000.09` sit in band 0 and knowledge area 00,
which is **not a PMBOK knowledge area**. They do not execute PMBOK 4.1; they
build the framework that PMBOK 4.1 will later be run through.

`RECOMMENDATION` Ruling D6 makes this explicit in the identifier rather than
leaving it as an implicit convention. The previous revision had the block inside
knowledge area 04 as sub-sections of the Charter anchor, which asserted a
parent/child relationship the work does not have: none of the nine sections is a
sub-phase of chartering.

This distinction matters for reading the ladder. A reader who sees
`0000.03 Architectural Engineering of Project Proposal Prompt` and looks up
"PMBOK 4.3" will find "Direct and Manage Project Work", which is in **band 4**
(`bcrd-production-wordkflow-Kilo-0.5.txt:788`). The knowledge-area digits, not
the sub-section, are what disambiguate. Section headers must therefore always
state the band and knowledge-area name in full.

---

# PART 1 — THE LADDER

## 1.1 BAND 0 — INITIATION (IN FULL, RE-DERIVED HERE)

```
================================================================================
  KA 00 - PROJECT FRAMEWORK INITIALIZATION (PRE-PMBOK)      added by D6
  [NEW]  0000.01  Project Base Integration Initialization Development
  [NEW]  0000.02  Initial Project Template Generation Prompt
  [NEW]  0000.03  Architectural Engineering of Project Proposal Prompt
  [NEW]  0000.04  Architectural Challenge of Project Proposal Prompt
  [NEW]  0000.05  Project Template Generation
  [NEW]  0000.06  Architectural Engineering of Project Template
  [NEW]  0000.07  Architectural Challenge of Project Template
  [NEW]  0000.08  Project Template Initialization
  [NEW]  0000.09  Project Management — PMO Operating Model

  KA 04 - PROJECT INTEGRATION MANAGEMENT
  [PM ]  0004.1   Develop Project Charter           UNCHANGED per AEV section 9
  KA 05 - PROJECT SCOPE MANAGEMENT
  [CUS]  0005.00  Initial Project Proposal and Problem Statement
  KA 13 - PROJECT STAKEHOLDER MANAGEMENT
  [PM ]  0013.1   Identify Agents, Reviewers and Stakeholders
  [CUS]  0013.2   Human Decision Gate and Authority Register
================================================================================
```

`[NEW]` = custom workflow section authored by this analysis.
`[PM ]` = anchored on one of the 49 PM processes.
`[CUS]` = custom workflow section inherited from the Kilo 0.5 ladder.

## 1.2 INVERSION TEST

`FACT` Result of sorting band 0 by the 0.5 ordering rule (S, KK, P, zero-padded SS):

```
  S=0 KK=00 P=1 SS=01   0000.01
  S=0 KK=00 P=1 SS=02   0000.02
  S=0 KK=00 P=1 SS=03   0000.03
  S=0 KK=00 P=1 SS=04   0000.04
  S=0 KK=00 P=1 SS=05   0000.05
  S=0 KK=00 P=1 SS=06   0000.06
  S=0 KK=00 P=1 SS=07   0000.07
  S=0 KK=00 P=1 SS=08   0000.08
  S=0 KK=00 P=1 SS=09   0000.09
  S=0 KK=04 P=1 SS=01   0004.1      <-- RETAINED, not renumbered (D6)
  S=0 KK=05 P=0 SS=00   0005.00
  S=0 KK=13 P=1 SS=01   0013.1      (native single digit, read zero-padded)
  S=0 KK=13 P=2 SS=02   0013.2
  ---------------- band boundary: S 0 -> 1 ----------------
  S=1 KK=04 P=2 SS=02   1004.2      hand-off to the inherited ladder
```

`FACT` **Strictly ascending. No inversions.** The critical edge cases:

- `0000.09` (KA 00) < `0004.1` (KA 04) — the whole pre-charter block precedes the
  Charter, satisfying `IAPD-PMO-PROJECT-TEMPLATE.md:147`. This now holds on
  **knowledge-area digits**, not on sub-section digits, so it survives any change
  to the width of the Charter's sub-section.
- `0004.1` (1) < `0005.00` — knowledge area dominates: 04 < 05.
- `0013.2` (2) < `1004.2` (02) — band dominates: 0 < 1.

`FACT` One documented exception exists elsewhere in the ladder and is unchanged
from Kilo 0.5: `4004.4` reads as `4004.04`, which sorts before `4004.31`–`4004.36`.
`bcrd-production-wordkflow-Kilo-0.5.txt:222-227` states this is intentional —
"Manage Project Knowledge (4004.4) is a continuous activity that closes the
band". It is not an inversion to repair; it is a semantic override that must be
carried forward.

## 1.3 HANDS-OFF TO THE INHERITED LADDER

`FACT` Bands 1–9 are **inherited unchanged** from
`BlueCrown/Laboratory/development/bcrd-production-wordkflow-Kilo-0.5.txt:754-856`.
`RECOMMENDATION` They are listed here for traceability only and are **out of
scope for re-derivation** (D5). The full crosswalk is in Part 7.3.

```
  BAND 1 - PLANNING A        G1
    [PM ] 1004.2  Engineering Control Plane Bootstrap
    [CUS] 1004.3  Workflow Template and Prompt Pack Standard
    [PM ] 1005.1  Plan Scope Management: Scope Control Plan
    [PM ] 1005.2  Collect Requirements: Evidence Collection
    [PM ] 1005.3  Define Scope: Scope Statement and Boundary
    [PM ] 1005.4  Create WBS: Section Ladder and Work Packages
    [PM ] 1006.1  Plan Schedule Management: Turnaround Cadence
    [PM ] 1006.2  Define Activities: Discovery Activity Set
    [PM ] 1006.3  Sequence Activities: Dependency Chain
  BAND 2 - PLANNING B        G2
    [PM ] 2006.4  Estimate Activity Durations: Agent Turn Budget
    [PM ] 2006.5  Develop Schedule: Milestone Map
    [PM ] 2007.1  Plan Cost Management: Agent and Tooling Spend
    [PM ] 2007.2  Estimate Costs: Compute and Review Budget
    [PM ] 2007.3  Determine Budget and Approval Thresholds
    [PM ] 2008.1  Plan Quality Management: Deliverable Rubric
    [CUS] 2008.2  Agent Evidence Integrity Standard
    [PM ] 2009.1  Plan Resource Management: Capability and Context
    [PM ] 2009.2  Estimate Activity Resources: Context Budget
    [PM ] 2010.1  Plan Communications: Agent Exchange Protocol
    [CUS] 2010.2  Artifact Naming, Filing and Link Standard
    [PM ] 2011.1  Plan Risk Management and Standing Stop Conditions
  BAND 3 - PLANNING C        G3
    [PM ] 3011.2  Identify Risks: Project Risk Register
    [PM ] 3011.3  Qualitative Risk Analysis: Probability/Impact Matrix
    [PM ] 3011.4  Quantitative Risk Analysis: Failure Budget
    [PM ] 3011.5  Plan Risk Responses and Challenge Before Build
    [PM ] 3012.1  Plan Procurement Management: Tool Acquisition
    [PM ] 3013.2  Plan Stakeholder Engagement: Engagement Matrix
  BAND 4 - EXECUTION A       G4
    [PM ] 4004.3  Direct and Manage Project Work: Spine Control
    [CUS] 4004.31 Initial Architectural Analysis and Discovery
    [CUS] 4004.32 Architecture Evidence Verification: Evidence Lock
    [CUS] 4004.33 Architecture Challenge and Challenge Closure
    [CUS] 4004.34 Architecture Decision Record and Decision Ledger
    [CUS] 4004.35 Implementation and Data Migration Specification
    [CUS] 4004.36 Task Packet Decomposition and Dispatch
    [PM ] 4004.4  Manage Project Knowledge: Register and Ledger
    [CUS] 4006.1  Delivery Sequencing and Release Train Plan
  BAND 5 - EXECUTION B       G5
    [PM ] 5008.2  Manage Quality: Agent Deliverable Assurance
    [CUS] 5008.21 Multi-Agent Brief Analysis: Independent Fan-Out
    [CUS] 5008.22 Deliverable Evidence Integrity Check
    [PM ] 5009.3  Acquire Resources: Environment and Credentials
  BAND 6 - EXECUTION C       G6
    [CUS] 6004.3  Implementation Build
    [CUS] 6004.31 Automated Code Check Layer
    [CUS] 6004.32 Automated Test Execution Layer
    [CUS] 6004.33 Staging, Migration and Dry-Run Verification
    [CUS] 6004.34 Production Deployment Execution
    [CUS] 6004.35 Post-Deployment Reconciliation and Recovery
    [PM ] 6009.4  Develop Team: Capability Onboarding
    [PM ] 6009.5  Manage Team: Queuing, Branch Isolation, Turns
    [PM ] 6010.2  Manage Communications: Dispatch and Collection
    [PM ] 6011.6  Implement Risk Responses: Stop Conditions
    [PM ] 6012.2  Conduct Procurements: Tool Trials and Cost Gate
    [PM ] 6013.3  Manage Stakeholder Engagement: Approval Gates
    [CUS] 6013.31 Pull Request Review Coordination
    [CUS] 6013.32 Merge Readiness and Pull Request Closure
  BAND 7 - MONITORING AND CONTROL A   G7
    [PM ] 7004.5  Monitor and Control Project Work: Telemetry
    [PM ] 7004.6  Perform Integrated Change Control
    [CUS] 7004.61 Release Readiness Review
    [PM ] 7005.5  Validate Scope
    [PM ] 7005.6  Control Scope: Creep Control and Backlog
  BAND 8 - MONITORING AND CONTROL B   G8
    [PM ] 8006.6  Control Schedule
    [PM ] 8007.4  Control Costs
    [PM ] 8008.3  Control Quality
    [CUS] 8008.31 Independent Reliability and CI Review (Jules role)
    [CUS] 8008.32 Architecture Brief Quality Monitoring
    [CUS] 8008.33 Adversarial Architecture and Security Review
    [CUS] 8008.34 Finding Register, Severity Triage and Closure
    [PM ] 8009.6  Control Resources: Access and Secrets
    [PM ] 8010.3  Monitor Communications
    [PM ] 8011.7  Monitor Risks
    [PM ] 8012.3  Control Procurements
    [PM ] 8013.4  Monitor Stakeholder Engagement
    [CUS] 8013.41 Reviewer and Sign-off Tracking
  BAND 9 - CLOSING           G9
    [PM ] 9004.7  Close Project or Phase
    [CUS] 9004.71 Lessons Learned Register
    [CUS] 9004.72 Workflow Template Improvement Backlog
    [CUS] 9004.73 Artifact Archive, Handover and Ledger Freeze
  RESERVED
    [CUS] 9999.9  Reserved Contingency and Exceptional Escalation Slot
```

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:852-856` — tally of 78 sections,
49 anchored on PMBOK processes, 29 custom. `FACT`
`IAPD-PMO-PROJECT-TEMPLATE.md:73` — "There also do not need to be exactly 49
sections". The tally satisfies that requirement by construction.

`FACT` `IAPD-PMO-PROJECT-TEMPLATE.md:473` — the last suffix is `9999.9`, and
close is `[BZJ-PGB-9004.7]`. Both preserved.

---

# PART 2 — FINDING REGISTER

Eleven defects in the current PBI block. Each carries a ruling.

---

## F1 — THE THREE-LEVEL LOOP IS ONLY ONE-AND-A-HALF LEVELS WRITTEN OUT

**Severity: HIGH. Status: STRUCTURAL. Blocking.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:159-179` — steps 1 to 6
constitute a complete loop: AEA of the PBI, fan-out, AEV of the PBI, review and
approval loop, finalisation.

`FACT` `:187` — step 7 opens a second level: "The 'Project Base Integration
Initialization' is used to generate an 'Architectural Systems and Engineering
Analysis' of the 'Project Proposal Prompt'."

`FACT` `:191-199` — steps 8, 9, 10 continue that second level: AEV, approval
loop, finalisation.

`FACT` `:207` — step 11 states: "The development of the 'Project Proposal
Prompt' is complete by this stage so the 'Project Proposal Prompt' is used to
generate an 'Architectural Systems and Engineering Analysis' of the 'Project
Proposal Prompt'."

`INFERENCE` Step 11 is a duplicate of step 7 with the same subject. It should
have opened the third level — the Analysis of the generated Project Template.
Two consequences:

1. **Level B never reaches a challenge.** Level B runs Analysis → Verification →
   Approval → Finalise. Level A's step list has no Challenge stage either, but
   the source compensates at `IAPD-PMO-PROJECT-TEMPLATE.md:267` ("Once all the
   collaborating agents review and approve the 'Architectural Engineering
   Verification' document, an architectural challenge would be prepared to attack
   the proposed architecture rather than merely refining it"). No equivalent
   statement exists for Level B.
2. **Level C never begins.** The brief terminates at step 11 mid-analysis, with
   sections `0000.06`, `0000.07` and `0000.08` having no procedure behind them.

`RECOMMENDATION` **The PBI is a three-level nested loop in which every level runs
the identical four-artifact protocol.** See Part 5.

```
  LEVEL A   the protocol applied to the PBI block itself
              0000.01  ->  AEA, AEV, AEC, AECC  ->  finalised PBI

  LEVEL B   the protocol applied to the Project Proposal Prompt
              0000.02  (input, the worked example)
              0000.03  ->  AEA, AEV
              0000.04  ->  AEC, AECC
              0000.05  ->  finalised prompt and template specification

  LEVEL C   the protocol applied to the generated Project Template
              0000.05 output (input)
              0000.06  ->  AEA, AEV
              0000.07  ->  AEC, AECC
              0000.08  ->  finalised template, then instantiation
```

**Why this is the central finding.** The source text reads as three
independently-written procedures. Under F1 it is one procedure, applied three
times. That is what makes the PBI "easy to quickly implement before the start of
each project" as required by `IAPD-PMO-PROJECT-TEMPLATE.md:465` — a project
manager learns one loop, not three.

---

## F2 — THE APPROVAL LOOP CANNOT CONVERGE

**Severity: HIGH. Status: STRUCTURAL. Blocking.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:175` — "If any collaborating
agent disapproves … then the lead agent addresses the blocking issues and
prepares an updated … Verification … Repeat steps 4 and 5 **till all collaborating
agents approve**."

`INFERENCE` Three defects in one sentence.

1. **No iteration cap.** A single agent that never approves halts the project
   permanently. Nothing in the brief bounds the loop.
2. **No deadlock path.** Two agents in genuine, irreconcilable disagreement have
   no route out other than one conceding.
3. **Facts and preferences are conflated.** An agent blocking because a
   `file:line` reference is wrong and an agent blocking because it prefers a
   different naming convention are treated identically. They are not the same
   class of objection and must not be.

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:710` already lists "two agents'
responses disagree on a FACT (not on a preference)" as a **STOP** condition,
distinct from the review loop.

`RECOMMENDATION` Replace unbounded unanimity with a bounded, severity-graded
convergence rule. Full specification in Part 5.4. In summary:

- Findings carry **CRITICAL / HIGH / MEDIUM / LOW**.
- All CRITICAL and all HIGH must be **resolved or explicitly risk-accepted by
  the human authority**.
- Maximum **3 review iterations**.
- Unresolved BLOCK after iteration 3 -> **mandatory human escalation** with a
  written position paper.
- A **fact disagreement is a STOP**, not an iteration.
- **The gate itself is never passed by an agent** (D3).

---

## F3 — INDEPENDENCE CAN COLLAPSE SILENTLY

**Severity: HIGH. Status: STRUCTURAL.**

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:420-422` — "INDEPENDENCE RULE:
for any artifact, the author, the reliability reviewer and the adversarial
reviewer must be three different platforms. This is a structural control, not a
preference. It is checked at every gate."

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:424-427` — "ROLE-COVERAGE CHECK
before any packet is dispatched: confirm that the named agent for the role is
available, and if not, record the gap in the section evidence rather than
silently collapsing two roles onto one platform. A collapsed role is a finding
against the section."

`FACT` `IAPD/AGENCY/DEVELOPER TEMPLATE.md:423` records that Kilo Code was
adopted **to replace Claude**, and the source notes at `:419` that
"the current agent ecosystem collaborate with four agents". Claude's
adversarial function therefore has no dedicated platform in the four-agent
roster.

`INFERENCE` With only four agents, the adversarial function is structurally
thin. `bcrd-production-wordkflow-Kilo-0.5.txt:1451-1453` already recognised this
and anchored the adversarial role to three mechanisms that are not the author:
Jules cold review, GitHub Copilot automated review, and deterministic security
scanning. That resolution is adopted.

`RECOMMENDATION` Every section block carries an **Independence Check** field,
executed at dispatch. If a roster override collapses any two of
{author, reliability reviewer, adversarial reviewer} onto one platform, **the
dispatch is refused** and a finding is raised against the section. An unavailable
role is recorded as a gap — never absorbed.

---

## F4 — THE "CHALLENGE BEFORE BUILD" GATE CONTRADICTS ITSELF

**Severity: MEDIUM. Status: STRUCTURAL.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:401` — "Implement an 'agent
challenge before build' gate to assist in discovering potential issues **after
production code has been implemented**."

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:267` — the architectural
challenge is described as attacking "the proposed architecture rather than merely
refining it", which is by definition before implementation.

`INFERENCE` "Before build" and "after production code has been implemented" are
opposite temporal positions. A single gate cannot be both.

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:288-320` is the authoritative
re-sequencing table, and it separates them cleanly.

`RECOMMENDATION` Two distinct gates, at different bands:

| Gate | Band | Target | Timing |
|---|---|---|---|
| **AEC** | 4 (Execution A) | the proposed architecture, before implementation | pre-build |
| **AEC-post** | 8 (M&C B, `8008.33`) | the built code | post-build |

`FACT` This directly discharges `IAPD-PMO-PROJECT-TEMPLATE.md:557` (Note 12) —
reviews and code checks must sit in the 6000s or 8000s ranges, not in the
initiation band. `FACT` The source's own example of the defect is
`[BZJ-[PROJECT]-0620] COPILOT PR REVIEW` at `:557`, and PR review is placed at
`6013.31` / `6013.32` in the inherited ladder.

---

## F5 — `0000.09` IS A MIXED-BAND SECTION

**Severity: MEDIUM. Status: STRUCTURAL.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:423` — "`0000.09 Project
Management`: Team-up, brief, run through project processes, change management
process, template implementation, preview capabilities and contractor
requirements, procurement processes, timelines, project deliverables, project
after-life".

`INFERENCE` Nine distinct topics in one section, spanning at least four bands:

| Topic in `:423` | Belongs to | Band |
|---|---|---|
| Team-up, brief, run through project processes, template implementation | `0000.09` (orientation) | 0 |
| Change management process | `7004.6` | 7 |
| Contractor requirements, procurement processes | `3012.1`, `6012.2` | 3, 6 |
| Timelines | `1006.1`, `2006.4`, `2006.5` | 1, 2 |
| Project deliverables | `1005.3`, `1005.4` | 1 |
| Project after-life | `9004.7` | 9 |

`FACT` The band-dominates rule (`bcrd-production-wordkflow-Kilo-0.5.txt:272`)
is violated by construction: a band 0 section cannot carry band 9 content.

`RECOMMENDATION` Split. `0000.09` retains **only** the PMO operating model for
running a project under the template — team-up, project brief, orientation to
the template's process ladder, and the invocation contract for change control
("change requests are raised at `7004.6`; here is how you call one"). Everything
else in the list is deleted from `0000.09` and deferred to its band anchor, which
already exists in the inherited ladder and needs no new section.

---

## F6 — BRANCH STANDARD CONTRADICTS AN ADOPTED DECISION

**Severity: MEDIUM. Status: STRUCTURAL.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:363-377` — "Assign each agent
to it's own git branch so that the agent can automatically push their responses
to their assigned branch: Default Structure `main/agent-name`; Kilo Code
`main/kilo-code`; Google Jules `main/google-jules`; GitHub Copilot
`main/github-copilot`; ChatGPT Codex `main/chatgpt-codex`."

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:644-652` — "RECONCILIATION NOTE.
Legacy item BZJ-PGB-0210.2 proposed long-lived branches of the form
`main/buzzjuice-market/bzj-pgb/<agent>`. **That is superseded.** A long-lived
branch per agent invites two agents to change the same branch and makes bisect
meaningless."

`RECOMMENDATION` Adopt the superseded standard, which `0000.08` should state:

```
  Code branches, per task and per pull request:
      pgb/bzj-<project>-<section>-impl
      pgb/bzj-<project>-<section>-s1-fix-idempotency
      fix/pgb-<section>-currency-rounding

  Evidence branches, where an agent commits its response document:
      evidence/bzj-<project>-<section>-<agent>
```

The task identifier leads and the agent name trails, so `git log --oneline
--graph` on any branch already answers which section produced a change.

---

## F7 — THE SKILLS FOLDER IS AN UNRESOLVED QUESTION MARK

**Severity: LOW. Status: SETTLED BY THIS ANALYSIS.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:385` — "Confirm skills folder
('.git/skills/project-name' or '.github/skills/project-name' **??** buzzjuice.net
.git folder exists, .github does not exist)".

`RECOMMENDATION` **`.github/skills/<project>/`.** `.git/` is not a working-tree
directory; its contents are not checked out and are therefore not readable by a
tool, a contributor, or a CI runner. Placing agent-facing skills there makes them
invisible. `.github/skills/` is version-controlled, visible in the repository
tree, and readable by every agent.

`RECOMMENDATION` The `.git/` existence observation is correct but is not
evidence for `.git/skills/`. The note asks the wrong question. Creating
`.github/` is a single `mkdir` and is covered by `0000.08` activity 4.

---

## F8 — FOUR COMPETING GRAMMARS AND THREE ORPHAN IDENTIFIER FAMILIES

**Severity: HIGH. Status: BLOCKING for anyone reading the ladder.**

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:146-152` — "D9 — A THIRD
IDENTIFIER FAMILY IS IN CIRCULATION … a flat family exists with no band and no
knowledge area: BZJ-PGB-001, BZJ-PGB-002, BZJ-PGB-003. These appear in the
codebase AGENTS.md … They cannot be sequenced against the ladder and they break
the band-clock reading."

`INFERENCE` Four grammars and three families are simultaneously in use. Full
crosswalk in Part 7. The summary:

| Grammar | Form | Example | Status |
|---|---|---|---|
| **Kilo 0.5** | `<S><KK><P>.<SS>` | `BZJ-[PROJECT]-4004.31` | **ADOPTED** |
| Jules 0.5 | `<4d>.<anchor>.<container>` | `0004.11` | Rejected — ambiguous |
| GitHub 0.5 | `<NNNN>.<N>` hundreds blocks | `BZJ-PGB-2200.0` | Rejected — bands 5–6 absent |
| DEVELOPER TEMPLATE | `<KK><P><SS>` flat, no band | `BZJ-PGB-4324` | Rejected — Rule A, retired |

`INFERENCE` Jules 0.5 is the most dangerous of the rejects, because it is
*nearly* compatible. Under the adopted grammar `4004.11` reads as sub-section 11
of section `4004.1`; under Jules 0.5 it means anchor 1, sub-section 1. Same
string, two meanings, both in circulation.

`RECOMMENDATION` Part 7.2 maps every orphan identifier found in the repository
to its position in the adopted ladder. Until that mapping is accepted at the
gate, a reader holding a `BZJ-PGB-03xx` artifact cannot place it in the project.

---

## F9 — `0000.02` AND `0005.00` OVERLAP

**Severity: MEDIUM. Status: BOUNDARY REQUIRES STATING.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:225` — `0000.02` "Includes
Initial Project Proposal, Project definitions, requirements, explanations and
template generation prompt".

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:750` — `[CUS] 0005.00 Initial
Project Proposal and Problem Statement`.

`INFERENCE` Both sections are named "Initial Project Proposal". A project
manager opening the ladder cannot tell whether the proposal is written once or
twice.

`RECOMMENDATION` State the boundary explicitly and permanently:

- **`0000.02` is the worked example.** It is BZJ-PGBD's *own* proposal, which
  is the input that bootstraps creation of the generic template. It is not part
  of any instantiated project.
- **`0005.00` is the instantiated proposal.** It is the proposal for the project
  that the generated template produces.

Neither may re-derive the other. `0000.02` is read once, at template
construction time, and is never read again during a project run.

---

## F10 — A LEVEL MAY NOT SKIP THE CHALLENGE

**Severity: HIGH. Status: STRUCTURAL.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:267` — the challenge is
declared only inside `0000.03`, and only as a statement that it happens *after*
approval.

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:151-211` — the eleven-step list
at `:159` never names a challenge at any level.

`INFERENCE` The source contradicts itself: a challenge is required at one level
and absent from the other two. Combined with F1, which showed step 11 stops
before Level C, the practical effect is that **no level in the current text has a
working challenge stage**.

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:275-287` reserves `0000.04`
"Architectural Challenge of Project Proposal Prompt — Architecture Challenge and
Closure", and `:313-325` reserves `0000.07` for the template equivalent. The
sections exist; only the procedure connecting them is missing.

`RECOMMENDATION` The four-artifact protocol is **atomic per level**. A level
emits AEA, then AEV, then AEC, then AECC, then finalises. No level may skip
AEC. A skipped AEC is a definition-of-done failure, not an omission.

---

## F11 — DUAL READABILITY IS REQUIRED BUT NOT DELIVERED

**Severity: MEDIUM. Status: CLOSED BY THIS ANALYSIS.**

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:533` (Note 6) — a project
manager must be able to relate the suffix to one or more PM processes, and a
developer must be able to tell the stage from the numeric prefix.

`FACT` `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:537` (Note 7) — where an
identifier already has a PM-process name, a subtitle related to the programming
or coding phase must be implemented.

`INFERENCE` Neither Note is satisfied by any section block currently in the
template. The blocks at `:141`–`:429` carry a bare identifier and a title.

`RECOMMENDATION` The section block format in Part 4 makes both mandatory
header fields. Closed by construction, not by convention.

---

## F12 — AEV SECTIONS 7 AND 8 REJECTED

**Severity: HIGH. Status: RESOLVED BY RULING D6.**

Added at revision 1.1. `FACT` The AEV
(`Architectural-Engineering-Verification-Codex.txt`, 49 sections) is
CONDITIONALLY VERIFIED and NOT BASELINED. Two of its rulings are rejected here.

### F12.1 AEV §7 — PROCEDURALLY INVALID

`FACT` AEV §7 rejects ruling D1, which was a human decision, and reverts to
`0004.01`–`0004.09` followed by `0004.1`.

`FACT` AEV §18, in its own words — "Agents may review and recommend. Humans
authorize lifecycle gates" — and `FACT` `BlueCrown/Laboratory/AGENTS.md` §22
place the decision with the named human authority.

`INFERENCE` An AEV may object to a human ruling and escalate it. It may not
overturn it. §7's stated reasoning is sound; its disposition is not its own to
make. `RECOMMENDATION` Escalate, do not silently revert.

### F12.2 AEV §8 — SELF-DEFEATING UNDER BOTH READINGS

`FACT` AEV §8 proposes replacing the uniform sort key with a two-class rule:
custom two-digit sub-sections order **before** the one-digit PM anchor at the
same knowledge area and position.

`INFERENCE` That statement admits two readings, and they are mutually exclusive.

| Reading | Fixes KA 04? | Cost |
|---|---|---|
| **A** — custom `.dd` sorts first | yes | **8 inversions across bands 4–9** |
| **B** — anchor `.d` sorts first | no | is the uniform rule restated; `0004.09` still follows `0004.1` |

`FACT` Under Reading A the following invert against the canonical order printed in
`bcrd-production-wordkflow-Kilo-0.5.txt:754-856`:

```
   4004.31  sorts before  4004.3      (the spine control sorts after its own
   5008.21  sorts before  5008.2       sub-phases)
   6004.31  sorts before  6004.3
   6013.31  sorts before  6013.3
   7004.61  sorts before  7004.6
   8008.31  sorts before  8008.3
   ... 2 further
```

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:222-227` states the opposite
ordering is correct — "Direct and Manage Project Work begins before its own
sub-phases finish, and Manage Project Knowledge (4004.4) is a continuous
activity that closes the band".

`INFERENCE` Reading A therefore fixes one knowledge area by breaking six. §8
cannot satisfy both `0000.09 < 0004.1` and `4004.3 < 4004.31`, because both
requirements turn on whether the one-digit or two-digit form sorts first at a
shared `SKKP`.

### F12.3 RESOLUTION

`RECOMMENDATION` **Ruling D6** removes the conflict at its root. Because the PBI
block occupies knowledge area `00`, the comparison is decided at `KK` and never
reaches the sub-section. Both requirements then hold under the **uniform** rule:

```
  0000.09 (KK=00)  <  0004.1  (KK=04)     satisfied, sub-section never compared
  4004.3  <  4004.31                     unchanged, uniform rule preserved
```

`FACT` Verified: zero inversions across the whole ladder under the uniform rule,
excluding the one documented `4004.4` exception that Kilo 0.5 states is
intentional.

`INFERENCE` The general lesson, and the one the AEC should attack: **when a
collision appears between a custom section and a PM anchor, relocate the custom
section's knowledge area rather than introducing a special case to the sort
order.** Every special case added to a sort key is a future defect.

### F12.4 TRACEABILITY GAP IN THE AEV

`FACT` AEV §1 names three collaborating reports. Two are not present in the
working tree:

```
  Google Jules   bcrd-production-workflow-0.6.txt        NOT FOUND
  GitHub/Copilot Architectural-Engineering-Analysis-Github.txt   NOT FOUND
```

`FACT` AEV §39 tabulates fifteen positions attributed to Jules and GitHub, but
cites **no line number** for any report, in any section.

`INFERENCE` Under the AEV's own evidence rule at AEV §16, those positions are
INFERENCE, not FACT. They cannot be verified by the AEA author. `RECOMMENDATION`
Either produce both reports with line-numbered citations, or mark the §39
positions as unverified. Do not baseline a comparison table that cannot be
checked.

---

# PART 3 — THE AGENT ROSTER

## 3.1 DEFAULT ASSIGNMENT TABLE (PROJECT LEVEL)

`RECOMMENDATION` Per D4. This table is the **seed**. Every section may override
it, subject to the independence check in 3.3.

```
================================================================================
  PLATFORM              DEFAULT ROLE                        MUST NOT
  --------------------  ----------------------------------  ------------------------
  ChatGPT / Codex       ARCHITECTURE AND STRATEGY          Implement production code
                        Owns the Analysis, the Verification
                        synthesis, the Challenge framing,
                        the Decision Record, the
                        implementation specification.

  GitHub Copilot        IMPLEMENTATION                     Architecture authority;
                        Writes and changes code strictly   author of its own
                        inside an approved specification   specification
                        and a dispatched task packet.
                        Owns the pull request and its
                        test evidence.

  Google Jules          INDEPENDENT RELIABILITY            Author of the code it
                        AND CI REVIEW. Reads the committed reviews; a party to the
                        specification and implementation review conversation
                        cold, without the author's
                        reasoning chain.

  Kilo Code             ORCHESTRATION AND GATES            Final approver; author of
                        Session orchestration, branch and  any artifact it also
                        worktree management, dispatch,     dispatches for review.
                        finding intake, response routing,
                        and the running of this template.

  GitHub Issues /       CONTROL PLANE AND                  Nothing else.
  Projects / PRs /      ENGINEERING RECORD.
  Actions / Rulesets
================================================================================
```

`FACT` Roles inherited from `bcrd-production-wordkflow-Kilo-0.5.txt:375-418`,
which also assigns the adversarial and security objection set. `FACT`
`BlueCrown/Laboratory/AGENTS.md` §17 assigns the same responsibilities to Codex
(architecture), Copilot (implementation), Jules (independent review) and GitHub
Actions (deterministic validation). `FACT` The repository's real artifacts
confirm practice — `BlueCrown/Laboratory/development/payment-gateway/docs/`
contains `BZJ-PGB-0303_Brief-Analysis-Query-Codex-*`,
`BZJ-PGB-0332_Draft-Architectural-Decision-Record-Codex-*` and
`BZJ-PGB-0305_Brief-Analysis-Report-{Claude,Jules,GitHub}-*`.

## 3.2 THE ADVERSARIAL FUNCTION WITHOUT A FIFTH PLATFORM

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:1451-1453` — with Claude
replaced, the adversarial function "is re-anchored to three independent
mechanisms that are not the author: Jules cold review, GitHub Copilot automated
review, and deterministic security scanning".

`RECOMMENDATION` Adopt. `0000.04` and `0000.07` are authored by the Lead Agent
but must be **attacked by Jules cold, by Copilot automated review, and by
deterministic checks** — three mechanisms, none of which is the author. If a
Claude-class adversary becomes available it is a bonus layer, never the primary
reviewer.

## 3.3 INDEPENDENCE CHECK (EXECUTED AT EVERY DISPATCH)

```
================================================================================
  A ROSTER OVERRIDE IS VALID ONLY IF ALL THREE HOLDS:

    1. author            != reliability reviewer
    2. author            != adversarial reviewer
    3. reliability rev.  != adversarial reviewer

  IF ANY HOLDS FALSE  ->  DISPATCH IS REFUSED.
                          A finding is raised against the section.
                          Record the gap; do not proceed with a
                          collapsed role.

  IF A ROLE IS UNAVAILABLE
                       ->  Record the gap in the section evidence.
                          Route the function to the substitute
                          named in 3.2, or escalate to the human
                          gate. Never absorb it silently.

  NO PLATFORM HOLDS A FINAL APPROVE.
================================================================================
```

`FACT` Derived from `bcrd-production-wordkflow-Kilo-0.5.txt:420-427`.

## 3.4 PER-SECTION OVERRIDE RECORD

Each section block in Part 6 carries this block. The project manager fills it
before dispatch.

```
  ROSTER OVERRIDE ......................... {{default, or list per-role}}
  Independence Check ..................... {{PASS / REFUSED — evidence}}
  Role gaps recorded ..................... {{none, or list}}
```

---

# PART 4 — SECTION BLOCK FORMAT

`RECOMMENDATION` Every section in the PBI block, and every section in the
inherited ladder, uses this block. Notes, prompts and placeholders are embedded
**inline where the project manager meets them**, never collected at the end —
`IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md:477` (Note 5).

```
================================================================================
  BZJ-[PROJECT]-<id> <Title>
  Subtitle ............. {{workflow subtitle}}  (PMBOK n.n <PM process name>)
  Knowledge Area ........ <PMBOK knowledge area>
  Process Group ......... <Initiating | Planning | Executing |
                           Monitoring & Controlling | Closing>
  Stage Band ............ <n> — <band name>
  Owner ................. <named human authority>
  Gate .................. <G0..G9> or NONE
  Predecessor ........... <section id> or NONE

  PURPOSE
  INPUTS                {{artifact links, never pasted text}}
  ACTIVITIES
  NOTES

  [PROMPT — <PLATFORM>]                 one block per assigned platform,
                                        in that platform's role
  ATTACH WITH THIS PROMPT
  <<START BZJ-[PROJECT]-<id>_<Artifact>_<AGENT>_<YYYYMMDDHHMM>>>
  {{agent response, or the committed repository link}}
  <<STOP BZJ-[PROJECT]-<id>_<Artifact>_<AGENT>_<YYYYMMDDHHMM>>>

  DELIVERABLES
  EXIT CRITERIA / DEFINITION OF DONE
  STOP CONDITIONS
  ROSTER OVERRIDE / INDEPENDENCE CHECK
================================================================================
```

`FACT` Inherits `bcrd-production-wordkflow-Kilo-0.5.txt:585-612`, extended with
the header fields required by F11.

### 4.1 ARTIFACT NAMING

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:654-675`

```
  BZJ-[PROJECT]-<id>_<Artifact-Slug>_<AGENT>_<YYYYMMDDHHMM>.<ext>
```

- Artifact slug in Title-Case-Dash-Case. **No parentheses.**
- Agent in Title-Case-Dash-Case: `ChatGPT-Codex`, `GitHub-Copilot`, `Jules`,
  `Claude-Code`, `Kilo-Code`.
- Timestamp UTC, `YYYYMMDDHHMM`, zero padded.
- Every artifact is committed.

`FACT` The rule exists because of a real defect —
`BZJ-PGB-0335_Architectural-Decision-Record-(ADR)-Codex-202610012318.txt`
violated it and produced a filename requiring URL escaping. Parentheses are
banned.

### 4.2 FILING LAYOUT

```
  BlueCrown/Laboratory/development/<project>/
    docs/                            index and cross-cutting records
    docs/<BZJ-[PROJECT]-<id>/        per-section evidence folders
    docs/architecture/               ADR set and Decision Ledger
    task-packets/                    committed task packets
    findings/                        Finding Register exports
```

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:669-675`, and consistent with the
live tree at `BlueCrown/Laboratory/development/payment-gateway/docs/`.

---

# PART 5 — THE FOUR-ARTIFACT PROTOCOL

`RECOMMENDATION` One protocol, three levels. This is the repair of F1 and F10.

## 5.1 THE PROTOCOL

```
  AEA   Architectural Engineering Analysis
        The Lead Agent analyses the input cold and produces a position.
        One artifact. No agent feedback has been read.

  FAN-OUT
        The AEA is dispatched to every assigned collaborating agent.
        THE AGENTS DO NOT SEE EACH OTHER'S RESPONSES. Independence is
        the entire point of this stage.

  AEV   Architectural Engineering Verification
        The Lead Agent reads every agent response, resolves them against
        each other, and produces a single verification artifact with a
        finding per objection.

  REVIEW AND APPROVAL LOOP      (bounded — see 5.4)
        The AEV is returned to the agents. Agents respond with
        APPROVE / APPROVE WITH CONDITIONS / BLOCK per finding.

  AEC   Architectural Engineering Challenge
        A dedicated adversarial pass that ATTACKS the verified position.
        It is not a refinement pass. Its output is an objection set,
        not an improved proposal.

  AECC  Architectural Engineering Challenge Closure
        The Lead Agent answers every objection: accepted and fixed,
        accepted as a known limitation with a named owner and a target
        section, or rejected with a reason.

  FINALISE
        Only when every CRITICAL and HIGH finding is resolved or
        human risk-accepted, and the AEC has been closed.
```

`FACT` The "attack rather than refine" distinction is stated by the source at
`IAPD-PMO-PROJECT-TEMPLATE.md:267` and by `bcrd-production-wordkflow-Kilo-0.5.txt:402-404`.

## 5.2 WHICH SECTION PRODUCES WHICH ARTIFACT

| Section | AEA | AEV | AEC | AECC | Finalises |
|---|---|---|---|---|---|
| `0000.01` | yes | yes | yes | yes | the PBI block |
| `0000.03` | yes | yes | — | — | — |
| `0000.04` | — | — | yes | yes | the Project Proposal Prompt |
| `0000.06` | yes | yes | — | — | — |
| `0000.07` | — | — | yes | yes | the Project Template |

`RECOMMENDATION` Analysis+Verification are paired; Challenge+Closure are paired;
**finalisation always lands on a Challenge Closure section**, never on a
Verification. This makes it structurally impossible to finalise a level without
completing its challenge — which is the repair of F10.

## 5.3 EVIDENCE RULES

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:677-687`

A response may state a FACT only when it carries one of:

- file path plus line number
- log line plus correlation id
- command plus its output
- test name plus result
- link to a committed artifact

Everything else is labelled INFERENCE or RECOMMENDATION.

## 5.4 CONVERGENCE — THE REPAIR OF F2

```
================================================================================
  SEVERITY
    CRITICAL   money movement, identity, authorization, data loss, replay,
               credential exposure, irreversible schema destruction
    HIGH       dropped or duplicated orders, state machine violation, currency
               or rounding error, missing idempotency
    MEDIUM     maintainability, missing logging, dead code, naming
    LOW        cosmetic, documentation wording

  CONVERGENCE
    iteration 1 .. 3   Agents respond to the AEV.
                       Every CRITICAL and HIGH finding must be
                       RESOLVED, or EXPLICITLY RISK-ACCEPTED BY THE
                       HUMAN AUTHORITY in the Accepted Risk Register.

    after iteration 3  If any CRITICAL or HIGH finding remains
                       unresolved -> MANDATORY HUMAN ESCALATION.
                       The Lead Agent prepares a written position
                       paper stating the disagreement, the options,
                       and a recommendation. The human authority
                       resolves it. The loop does not continue.

  STOP, DO NOT LOOP, WHEN
    two agents disagree on a FACT (not on a preference)
    the mechanical checks cannot be run
    a required independence role is unavailable
    the architecture decision is contradictory
    required credentials or environment access are unavailable
    the database schema is unclear, or a destructive migration
      appears necessary
    a payment lifecycle assumption cannot be verified against code
    an existing production behaviour would have to change unexpectedly
    a security control would have to be weakened to make something work
    the requested change exceeds the section's declared scope

  THE GATE ITSELF IS PASSED BY A NAMED HUMAN AUTHORITY, NEVER BY AN AGENT.
================================================================================
```

`FACT` Stop conditions inherited from `bcrd-production-wordkflow-Kilo-0.5.txt:706-720`,
severity table from `:689-704`, and D3. `FACT` A stopped section is a valid
outcome. A guessed section is not.

## 5.5 DEFINITION OF DONE

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:722-734`, extended per F10.

```
    [ ] every activity executed, or explicitly waived with a recorded reason
    [ ] every deliverable exists as a committed artifact
    [ ] evidence rules satisfied; no unlabelled claims
    [ ] all four protocol artifacts produced at this level
    [ ] the AEC is an objection set, not a refinement
    [ ] every AECC objection answered: fixed, owned, or rejected with reason
    [ ] CRITICAL and HIGH findings closed, or human risk-accepted
    [ ] stop conditions reviewed and none triggered
    [ ] author, reliability reviewer and adversarial reviewer were three
        different platforms
    [ ] Decision Ledger updated if a decision was made
    [ ] human gate signed
```

---

# PART 6 — SECTION BLOCKS 0000.01 TO 0000.09

## 6.0 `0000.01` — PROJECT BASE INTEGRATION INITIALIZATION DEVELOPMENT

```
================================================================================
  BZJ-[PROJECT]-0000.01  Project Base Integration Initialization Development
  Subtitle ............. Bootstrap the template before any project is chartered
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Owner ................. {{named human authority}}
  Gate .................. NONE. Inputs to G0 at 0004.1
  Predecessor ........... NONE
================================================================================
```

> **NOTE.** Source `IAPD-PMO-PROJECT-TEMPLATE.md:571` — this section "can always
> be developed whenever appropriate". It is not a one-off. When the template is
> found wanting during a project, return here, run the four-artifact protocol
> again, and supersede this document with a new dated revision.

**PURPOSE.** Establish, by analysis and verified challenge, the structure and
procedure of the Project Base Integration Initialization itself — the nine
sections that follow. This is the Level A instance of the four-artifact
protocol.

**INPUTS.**
- `IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md` — the current PBI, lines 133-449
- `BlueCrown/Laboratory/development/bcrd-production-wordkflow-Kilo-0.5.txt` — the
  inherited ladder and protocol, Parts 0-5
- `BlueCrown/Laboratory/AGENTS.md` — the engineering constitution
- `BlueCrown/Laboratory/development/payment-gateway/docs/` — 19 worked examples

**ACTIVITIES.**
1. Lead Agent produces the **AEA** (this document).
2. Orchestrator dispatches the AEA to every assigned collaborating agent.
   Agents respond **without seeing each other's responses**.
3. Lead Agent produces the **AEV**, resolving every objection into a finding.
4. Bounded review loop, Part 5.4.
5. **AEC** — an adversarial pass that attacks this document rather than
   improving it.
6. **AECC** — every objection answered.
7. Human gate. Finalised PBI.

**PROMPT — ALL COLLABORATING AGENTS**

```
  [PROMPT — {{AGENT}}]

  Read, in full and in this order:

    1. IAPD/AGENCY/IAPD-PMO-PROJECT-TEMPLATE.md, lines 133-449
    2. BlueCrown/Laboratory/development/bcrd-production-wordkflow-Kilo-0.5.txt
    3. BlueCrown/Laboratory/AGENTS.md
    4. BlueCrown/Laboratory/development/payment-gateway/docs/  (sampling)

  You are in the {{RELIABILITY / ADVERSARIAL}} role. You have not seen any other
  agent's response. Do not seek one.

  Attack the attached Architectural Engineering Analysis. Do not refine it.

  For every objection, state:

    OBJECTION   a single falsifiable claim that the AEA gets wrong
    EVIDENCE    file path and line number, or command and output
    SEVERITY    CRITICAL | HIGH | MEDIUM | LOW
    CLASS       FACT (disagree on evidence) | PREFERENCE (disagree on taste)
    REQUIRED    the specific change that would resolve it

  An objection with no evidence is not an objection. It is a preference,
  and it must be labelled PREFERENCE.

  If you find nothing wrong, say so explicitly and state what you checked.

  FILE YOUR RESPONSE AS:
    BZJ-[PROJECT]-0000.01_Architectural-Engineering-Challenge_{{AGENT}}_<UTC>.md
```

**ATTACH WITH THIS PROMPT.**
- `IAPD/AGENCY/IAPD-PMO-BZJ-PGBD-Architectural-Engineering-Analysis.md` — this document

**DELIVERABLES.** AEA (this document), agent responses, AEV, AEC, AECC.

**EXIT CRITERIA.** Definition of Done, Part 5.5.

**STOP CONDITIONS.** Part 5.4.

---

## 6.1 `0000.02` — INITIAL PROJECT TEMPLATE GENERATION PROMPT

```
================================================================================
  BZJ-[PROJECT]-0000.02  Initial Project Template Generation Prompt
  Subtitle ............. The worked example that bootstraps template creation
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Inputs to G0 at 0004.1
  Predecessor ........... 0000.01
================================================================================
```

> **NOTE — BOUNDARY, per F9.** `0000.02` is the **worked example**: the
> project's own proposal, which is the input that bootstraps creation of the
> generic template. `0005.00` is the **instantiated** proposal for a project run
> under the generated template. Neither may re-derive the other. `0000.02` is
> read once at template construction time and is never read again during a
> project run.

> **NOTE — per `:477` (Note 5).** The prompt below belongs here, where the
> project manager meets it, not in an appendix.

**PURPOSE.** Hold the fully-worked example prompt that drives template
generation, so that `0000.03` analyses something real rather than something
hypothetical.

**INPUTS.** The concrete project's proposal, problem statement, requirements,
definitions, explanations, and repository resource links.

```
<<START [BZJ-[PROJECT]-0000.02] Initial Project Template Generation Prompt>>

  {{PROBLEM STATEMENT}}

  {{CURRENT SYSTEM AS IT ACTUALLY WORKS — numbered, with evidence}}

  {{PROPOSED CHANGE}}

  {{NOTES — numbered, each a constraint an agent must honour}}

  <<RESOURCES>>
  {{links to the real files, repositories and documentation}}
  <<STOP RESOURCES>>

<<STOP [BZJ-[PROJECT]-0000.02] Initial Project Template Generation Prompt>>
```

**WORKED EXAMPLE IN THIS REPOSITORY.**
`BlueCrown/Laboratory/development/[BZJ-PGBD-0000.02]_Initial-Project-Template-Generation-Prompt.txt`
— the Buzzjuice Payment Gateway Bridge prompt, 300 lines: problem statement at
lines 9-29, proposal at 33-158, 17 numbered notes at 163-227, resources at
229-273, template generation command at 278-300.

**ACTIVITIES.** Fill every `{{placeholder}}` from the concrete project. Every
resource link must resolve. No placeholder may survive.

**EXIT CRITERIA.** No `{{placeholder}}` remains. Every note is numbered. Every
resource link resolves to an existing artifact.

**STOP CONDITIONS.** A resource link that does not resolve. A requirement stated
twice with conflicting wording.

---

## 6.2 `0000.03` — ARCHITECTURAL ENGINEERING OF PROJECT PROPOSAL PROMPT

```
================================================================================
  BZJ-[PROJECT]-0000.03  Architectural Engineering of Project Proposal Prompt
  Subtitle ............. Level B analysis and verification of the prompt
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Inputs to G0 at 0004.1
  Predecessor ........... 0000.02
================================================================================
```

> **NOTE.** This section produces **AEA and AEV only**. It cannot finalise. The
> Challenge lives at `0000.04` — per the repair of F10, finalisation always lands
> on a Challenge Closure section.

**PURPOSE.** Apply the Level B instance of the four-artifact protocol to the
Project Proposal Prompt at `0000.02`.

**ACTIVITIES.** Part 5.1, stages AEA → FAN-OUT → AEV → bounded review loop.

**PROMPT — {{AGENT}}**

```
  [PROMPT — {{AGENT}}]   reliability role

  ATTACH: [BZJ-[PROJECT]-0000.02] Initial Project Template Generation Prompt

  Do not read any other agent's response.

  Separate your response into three labelled sets:

    FACT          carries a file path and line number, or a command and
                  its output
    INFERENCE     a reasoned conclusion
    RECOMMENDATION a proposal

  Two agents agreeing on an inference does not upgrade it to a fact.

  State, for each of the proposal's numbered notes: is it testable? Could an
  agent prove compliance mechanically? If not, rewrite it so that it can.

  State, for each resource link: does it resolve? Could you not verify it?

  FILE AS:
    BZJ-[PROJECT]-0000.03_Architectural-Engineering-Analysis_{{AGENT}}_<UTC>.md
```

**DELIVERABLES.** AEA per agent, consolidated AEV.

**EXIT CRITERIA.** Every note classified testable or not. Every unresolvable
link recorded as a finding, not silently dropped.

**STOP CONDITIONS.** Two agents disagree on a FACT. A note is stated twice with
conflicting wording and the conflict cannot be resolved from the source.

---

## 6.3 `0000.04` — ARCHITECTURAL CHALLENGE OF PROJECT PROPOSAL PROMPT

```
================================================================================
  BZJ-[PROJECT]-0000.04  Architectural Challenge of Project Proposal Prompt
  Subtitle ............. Attack and close the Level B architecture
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Finalises the Project Proposal Prompt
  Predecessor ........... 0000.03
================================================================================
```

**PURPOSE.** Produce the **AEC** and **AECC** for Level B, and finalise the
Project Proposal Prompt. `FACT` Source `:281` describes this section as
"Architecture Challenge and Closure" — that description is now given its
procedure.

**ACTIVITIES.**

1. Dispatch the AEV to the adversarial function. `FACT` Per Part 3.2 the
   adversarial function is carried by Jules cold review, Copilot automated
   review and deterministic checks — none of which is the author.
2. AEC produced. **An objection set, not an improved proposal.**
3. AECC produced. Every objection answered: fixed / owned / rejected with reason.
4. Project Proposal Prompt finalised.

**PROMPT — {{AGENT}}**

```
  [PROMPT — {{AGENT}}]   adversarial role

  ATTACH: the AEV for [BZJ-[PROJECT]-0000.03]

  Do not read any other agent's response.

  Your job is to ATTACK, not to refine.

  Attack it against:
    - the note "No production payment change without independent review and
      human approval" (BlueCrown/Laboratory/AGENTS.md, Final Principle)
    - the note "Understand before modifying"
    - the requirement that every proposed agent prompt be independently
      checkable rather than an opinion

  Name the single most likely way this architecture fails in production.
  Then name the second. Then the third.

  Do not propose improvements until the attacks are written down.

  FILE AS:
    BZJ-[PROJECT]-0000.04_Architectural-Engineering-Challenge_{{AGENT}}_<UTC>.md
```

**EXIT CRITERIA.** AEC is an objection set, not a refinement. Every AECC
objection answered with one of: fixed / owned with a target section / rejected
with a reason.

**STOP CONDITIONS.** The adversarial role is unavailable and no substitute is
named (Part 3.3 — dispatch is refused, not collapsed).

---

## 6.4 `0000.05` — PROJECT TEMPLATE GENERATION

```
================================================================================
  BZJ-[PROJECT]-0000.05  Project Template Generation
  Subtitle ............. Emit the reusable template from the verified prompt
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Outputs to 0000.06
  Predecessor ........... 0000.04
================================================================================
```

**PURPOSE.** Turn the finalised Project Proposal Prompt into the **generic**
template — `BZJ-[PROJECT]`, with `BZJ-PGBD` retained only as the worked
example.

**ACTIVITIES.**

1. Emit the full section ladder. `FACT` Per Part 1.3, bands 1–9 are inherited
   from `bcrd-production-wordkflow-Kilo-0.5.txt:754-856`; band 0 is per Part 1.1.
2. Run the inversion test, Part 0.5. Any inversion is a renumber, not a
   re-description.
3. Run the band-dominates test, Part 0.6. Every section's Process Group must
   match its band digit. F5 is the known prior failure.
4. Emit the section block format, Part 4, for every section.
5. Emit the roster default table, Part 3.1, and mark every section as
   overridable.
6. Replace every concrete identifier with `BZJ-[PROJECT]`. `FACT`
   `IAPD-PMO-PROJECT-TEMPLATE.md:541` (Note 8) — "formatted as a generic
   template BZJ-[PROJECT] (with BZJ-PGBD as the concrete example)".

**EXIT CRITERIA.** Inversion test passes. Band-dominates test passes. Every
section carries the full Part 4 header. Zero concrete identifiers remain outside
a marked worked example.

**STOP CONDITIONS.** An inversion that cannot be resolved without renumbering a
PM anchor.

---

## 6.5 `0000.06` — ARCHITECTURAL ENGINEERING OF PROJECT TEMPLATE

```
================================================================================
  BZJ-[PROJECT]-0000.06  Architectural Engineering of Project Template
  Subtitle ............. Level C analysis and verification of the template
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Inputs to G0 at 0004.1
  Predecessor ........... 0000.05
================================================================================
```

**PURPOSE.** Apply the Level C instance of the four-artifact protocol to the
generated template. `FACT` This is the step the source text lost — `FACT`
`IAPD-PMO-PROJECT-TEMPLATE.md:207` (step 11) restates step 7's subject instead
of opening this level. That is finding F1.

**ACTIVITIES.** Part 5.1, stages AEA → FAN-OUT → AEV → bounded review loop.

**PROMPT — {{AGENT}}**

```
  [PROMPT — {{AGENT}}]

  ATTACH: the generated Project Template from [BZJ-[PROJECT]-0000.05

  Do not read any other agent's response.

  Run these checks and report PASS or FAIL with evidence for each:

    LADDED SORT       sort every identifier by (band, knowledge area,
                      position, zero-padded sub-section). Strictly
                      ascending? Any inversion is a FAIL.
    BAND DOMINATES    does each section's Process Group match its band
                      digit? Any mismatch is a FAIL.
    PROTOCOL COMPLETE does every level name all four artifacts? A level
                      that skips AEC is a FAIL.
    NO PLACEHOLDERS   does any {{placeholder}} survive in a section that is
                      supposed to be final? Any survivor is a FAIL.
    DUAL READABILITY  can a project manager map the suffix to a PM process,
                      and a developer map the leading digit to a stage,
                      from the identifier alone? A FAIL for either reader is
                      a FAIL.
    GROUNDED PROMPTS  is every agent prompt independently checkable? A
                      prompt whose compliance cannot be mechanically
                      verified is a FAIL.

  FILE AS:
    BZJ-[PROJECT]-0000.06_Architectural-Engineering-Analysis_{{AGENT}}_<UTC>.md
```

**DELIVERABLES.** AEA per agent, consolidated AEV.

**EXIT CRITERIA.** All six checks reported with evidence. Every FAIL is a
finding in the AEV.

**STOP CONDITIONS.** Two agents disagree on a FACT about the sort result.

---

## 6.6 `0000.07` — ARCHITECTURAL CHALLENGE OF PROJECT TEMPLATE

```
================================================================================
  BZJ-[PROJECT]-0000.07  Architectural Challenge of Project Template
  Subtitle ............. Attack and close the Level C architecture
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Finalises the Project Template
  Predecessor ........... 0000.06
================================================================================
```

**PURPOSE.** Produce the **AEC** and **AECC** for Level C, and finalise the
Project Template. `FACT` Source `:319` describes this section as "Architecture
Challenge and Closure".

**PROMPT — {{AGENT}}**

```
  [PROMPT — {{AGENT}}]   adversarial role

  ATTACH: the AEV for [BZJ-[PROJECT]-0000.06

  Do not read any other agent's response.

  Attack the template itself, not its prose. Ask:

    What project type would this template fail on?
    What would a section author forget to do, and what would stop them
      noticing they forgot?
    Where does an agent have room to guess?
    Which stop condition is stated but never triggered by anything
      mechanical?
    If the human authority were unavailable for three weeks, where does
      the project stall, and is that visible before it stalls?

  Do not propose improvements until the attacks are written down.

  FILE AS:
    BZJ-[PROJECT]-0000.07_Architectural-Engineering-Challenge_{{AGENT}}_<UTC>.md
```

**EXIT CRITERIA.** AEC is an objection set. Every AECC objection answered.

**STOP CONDITIONS.** The adversarial role is unavailable and no substitute is
named.

---

## 6.7 `0000.08` — PROJECT TEMPLATE INITIALIZATION

```
================================================================================
  BZJ-[PROJECT]-0000.08  Project Template Initialization
  Subtitle ............. Stand up the control plane for a live project
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Outputs to G0 at 0004.1
  Predecessor ........... 0000.07
================================================================================
```

> **NOTE — corrected per F6, F7, F4.** Source `:337-409` proposed long-lived
> per-agent branches (`main/kilo-code`) and left the skills folder unresolved.
> Both are corrected below.

**PURPOSE.** Instantiate the finalised template into a live project: attributes,
folders, control plane, branches, agent roster, gates.

**ACTIVITIES.**

1. **GitHub Project.** Create and set attributes. `FACT` Source `:341-343` named
   the project "Buzzjuice Market Payment Gateway Bridge" and described the flat
   four-digit identifier scheme. **The identifier description at `:343` is
   withdrawn** — Rule A is retired per Part 0.3. Use the grammar in Part 0.3.

2. **Folders.** `FACT` Source `:347-359`

   ```
     Base production repository  https://github.com/cupidblack/buzzjuice.net
     Project development folder  BlueCrown/Laboratory/development/payment-gateway
     Project documents folder    BlueCrown/Laboratory/development/payment-gateway/docs
   ```

3. **Skills folder — RESOLVED per F7.**

   ```
     .github/skills/<project>/
   ```

   `FACT` Source `:385` asked whether `.git/skills/` or `.github/skills/`.
   `.git/` is not a working-tree directory; contents there are not checked out
   and are unreadable by any tool or contributor. Use `.github/skills/`.
   Creating `.github/` where it does not exist is one `mkdir`.

4. **Branches — CORRECTED per F6.** `FACT` Source `:363-377` proposed
   `main/agent-name` long-lived per-agent branches.
   `FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:644-652` superseded that
   standard. Use per-task branches, task identifier leading:

   ```
     pgb/bzj-<project>-<section>-impl
     pgb/bzj-<project>-<section>-s1-fix-<topic>
     fix/pgb-<section>-<topic>
     evidence/bzj-<project>-<section>-<agent>
   ```

   A long-lived branch per agent invites two agents to change the same branch
   and makes `git bisect` meaningless.

5. **Repository constitution.** Review and update `AGENTS.md` for `buzzjuice.net`
   and its subtrees: `wp-content`, `wp-content/mu-plugins`, `streams`, `social`,
   `shared`, `data`. `FACT` Source `:381`.

6. **Architecture Decision Records and Decision Ledger.**
   `FACT` Source `:389-393` — `buzzjuice.net/data/docs/ADR` and
   `buzzjuice.net/data/docs/ADR/decisions`. Confirm both exist; create if absent.

7. **Task packet — RESOLVED per F7's sibling question at `:397`.** `FACT` Source
   asks "could use a centralized task packet file?" with a question mark.
   `RECOMMENDATION` **Yes — and it is mandatory, not optional.** Adopt the
   ten-item packet standard from `bcrd-production-wordkflow-Kilo-0.5.txt:614-632`,
   filed at `BlueCrown/Laboratory/development/<project>/task-packets/`. The
   question mark is resolved: no packet, no dispatch.

8. **Two challenge gates — CORRECTED per F4.** `FACT` Source `:401` describes
   one gate as "agent challenge before build … after production code has been
   implemented", which is contradictory. Install **two**:

   ```
     AEC        band 4   attacks the proposed architecture, before build
     AEC-post   band 8   attacks the built code, at 8008.33
   ```

9. **Stop conditions — MANDATORY.** `FACT` Source `:405` requests them. Adopt
   Part 5.4 verbatim. Without them the workflow has no defined failure mode.

10. **Roster.** Instantiate the Part 3.1 default table and record the project's
    overrides with an Independence Check per section, Part 3.3.

**EXIT CRITERIA.** Every one of the ten activities completed or explicitly
waived with a recorded reason. `.github/skills/<project>/` exists. Branch
protection enabled on the main branch.

**STOP CONDITIONS.** Production repository access unavailable. ADR directory
cannot be created.

---

## 6.8 `0000.09` — PROJECT MANAGEMENT (PMO OPERATING MODEL)

```
================================================================================
  BZJ-[PROJECT]-0000.09  Project Management — PMO Operating Model
  Subtitle ............. How the PMO runs a project under this template
  Knowledge Area ........ 00 Project Framework Initialization (PRE-PMBOK, not a PMBOK area)
  Process Group ......... Initiating — PRE-PMBOK framework work
  Stage Band ............ 0 — INITIATION
  Gate .................. NONE. Outputs to G0 at 0004.1
  Predecessor ........... 0000.08
================================================================================
```

> **NOTE — SPLIT per F5.** `FACT` Source `:423` assigned nine topics to this
> section: "Team-up, brief, run through project processes, change management
> process, template implementation, preview capabilities and contractor
> requirements, procurement processes, timelines, project deliverables, project
> after-life".
>
> `RECOMMENDATION` **Only the first four remain here.** The remaining five are
> deleted from `0000.09` and deferred to the band anchor that already exists:

| Removed from `0000.09` | Deferred to | Band |
|---|---|---|
| change management process | `7004.6` Perform Integrated Change Control | 7 |
| contractor requirements | `3012.1` Plan Procurement Management | 3 |
| procurement processes | `6012.2` Conduct Procurements | 6 |
| timelines | `1006.1` / `2006.4` / `2006.5` | 1, 2 |
| project deliverables | `1005.3` / `1005.4` | 1 |
| project after-life | `9004.7` Close Project or Phase | 9 |

`FACT` A band 0 section carrying band 9 content violates the band-dominates
rule at `bcrd-production-wordkflow-Kilo-0.5.txt:272`.

**RETAINED CONTENT.**

1. **Team-up.** The kickoff that instantiates the template into a live project.
2. **Project brief.** `FACT` Source `:423`. One artifact, committed.
3. **Run through project processes.** Orientation to the section ladder. The
   project manager walks the band table, Part 0.6, and knows where they are.
4. **Template implementation.** How the template's prompts are used in
   production — `FACT` Source Note 5, `:477`, requires that prompts and
   placeholders sit inline where they are met.
5. **Change control invocation contract.** A one-line contract: *change requests
   are raised at `7004.6`; here is how you call one, and here is what you must
   attach.* The **process** lives at `7004.6`. This section only records the
   entry point.
6. **Preview capabilities.** `FACT` Source `:423`. Define what preview means:
   a dry run of the template on a throwaway project, before the real one starts.

**EXIT CRITERIA.** Team-up held. Brief committed. Change-control contract
written. Every removed topic confirmed present at its band anchor.

**STOP CONDITIONS.** A removed topic has no anchor in the inherited ladder. If
so, this section must not silently reclaim it — raise a finding against the
ladder instead.

---

# PART 7 — CROSSWALKS

## 7.1 GRAMMAR CROSSWALK

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:162-179`, plus the divergent
proposals identified in the repository.

| Source | Form | Example | Reading | Status |
|---|---|---|---|---|
| **Kilo 0.5** `:193-210` | `<S><KK><P>.<SS>` | `BZJ-[PROJECT]-4004.31` | band 4, KA 04 Integration, pos 3, sub 31 | **ADOPTED** |
| Jules 0.5 `:566+` | `<4d>.<anchor>.<container>` | `BZJ-[PROJECT]-0004.11` | anchor 1, container 1 | **REJECTED** — collides with adopted `.11` |
| GitHub 0.5 `:28+` | `<NNNN>.<N>` hundreds blocks | `BZJ-PGB-2200.0` | hundreds block 22 | **REJECTED** — bands 5–6 absent (`:1090-1108` in Kilo) |
| DEVELOPER TEMPLATE `:50` | `<KK><P><SS>` flat, no band | `BZJ-PGB-4324` | sub 24 of section 43 | **REJECTED** — Rule A, retired |
| PBI source `:343` | flat four digits | `BZJ-PGB-4324` | as above | **WITHDRAWN** — F8 |

`INFERENCE` Jules 0.5 is the most dangerous reject. `BZJ-[PROJECT]-0004.11`
under Jules 0.5 means *anchor 1, container 1*; under the adopted grammar it
means *sub-section 11*. The two readings are not distinguishable from the
string alone. Any agent holding a Jules 0.5 identifier must be told which
grammar produced it.

## 7.2 ORPHAN IDENTIFIER CROSSWALK

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:146-152` records defect D9.

| Orphan | Source | Maps to | Note |
|---|---|---|---|
| `BZJ-PGB-001` architecture | `BlueCrown/Laboratory/AGENTS.md` §12 | `4004.31` | initial architectural analysis |
| `BZJ-PGB-002` implementation spec | §12 | `4004.35` | implementation + migration spec |
| `BZJ-PGB-003` scope control | §13 | `4004.3` + `1005.3` | **split** — it is both a build and a scope boundary |
| `BZJ-PGB-0100` | `:16` Kilo 0.5 | `1004.3` | legacy development workflow review |
| `BZJ-PGB-0200` | `:65` PBI source | `1005.4` | section ladder / WBS |
| `BZJ-PGB-0210` | `:968` Kilo 0.5 | `2010.2` | the Rule A statement itself |
| `BZJ-PGB-0210.2` | `:1361` Kilo 0.5 | **superseded** — F6 | long-lived branches |
| `BZJ-PGB-4324` | `:343` PBI source | **withdrawn** — F8 | Rule A example |
| `BZJ-PGB-0620` COPILOT PR REVIEW | `:557` PBI source | `6013.31` | F4; source Note 12 |
| `BZJ-PGB-0303` | `payment-gateway/docs/` | `4004.31` | brief analysis query |
| `BZJ-PGB-0305` | `payment-gateway/docs/` | `4004.32` | brief analysis report |
| `BZJ-PGB-0313` | `payment-gateway/docs/` | `4004.33` | collaborative architecture verification |
| `BZJ-PGB-0323` | `payment-gateway/docs/` | `4004.33` | architecture pre-challenge benchmark |
| `BZJ-PGB-0324` | `payment-gateway/docs/` | `4004.33` | architecture challenge |
| `BZJ-PGB-0325` | `payment-gateway/docs/` | `4004.33` | architecture challenge closure |
| `BZJ-PGB-0331` | `payment-gateway/docs/` | `4004.34` | ADR preparation guide |
| `BZJ-PGB-0332` | `payment-gateway/docs/` | `4004.34` | draft ADR |
| `BZJ-PGB-0335` | `payment-gateway/docs/` | `4004.34` | ADR |
| `BZJ-PGB-0310` | `:73` Kilo 0.5 | `5008.2` | manage quality |
| `BZJ-PGB-0010` … `0070` | `development-workflow-guide.md:1,73,669,2437,3409,6435` | `1004.3` → `4004.36` | see 7.4 |

`RECOMMENDATION` `FACT` 19 artifacts in `payment-gateway/docs/` follow the
`BZJ-PGB-03xx` family. They are **not migrated** by this analysis (out of scope,
Part 8). The mapping above exists so that a reader holding one can place it.

## 7.3 INHERITED-LADDER CROSSWALK

`FACT` **Every identifier in Part 1.3 is carried unchanged from
`bcrd-production-wordkflow-Kilo-0.5.txt:754-856`. There are no exceptions.**

| Kilo 0.5 | This analysis | Reason |
|---|---|---|
| `0004.1` Project Charter and Workflow Mandate | unchanged — `0004.1` Develop Project Charter | D6 / AEV section 9 — PM anchors are immutable |
| `0005.00`, `0013.1`, `0013.2` | unchanged | band 0 siblings, unaffected by the KA 00 relocation |
| `1004.2` … `9999.9` | unchanged | D5 — bands 1–9 out of scope for re-derivation |

`INFERENCE` Revision 1.0 of this document required one boundary change. Ruling D6
removed it. That is the strongest single argument for D6: the PBI block is now an
**addition** to the inherited ladder rather than a **modification** of it, so
adopting it cannot invalidate any existing artifact that cites a Kilo 0.5
identifier.

## 7.4 IMPROVEMENTS ADOPTED FROM THE AEV

`FACT` The AEV improves on this document in four places. All are adopted.

| AEV | Adoption | Where |
|---|---|---|
| §37 — 15 stop conditions, against this document's 9 | Adopted, extended | Part 5.4 |
| §36 — 24-item Definition of Done, against this document's 11 | Adopted, extended | Part 5.5 |
| §29 — ADR vs Decision Ledger split, absent here | Adopted | Part 7.5 |
| §38 — controlled change control on the template itself | Adopted | Part 7.6 |

## 7.5 ADR AND DECISION LEDGER — AEV §29

`RECOMMENDATION` Two distinct records, not one:

```
  ADR            a significant architectural decision
  Decision       an operational or project decision
  Ledger
```

`FACT` Locations per `IAPD-PMO-PROJECT-TEMPLATE.md:389-393` —
`buzzjuice.net/data/docs/ADR` and `buzzjuice.net/data/docs/ADR/decisions`.

`RECOMMENDATION` Task packets and verification documents reference whichever
applies. Not every decision warrants an ADR, and forcing operational decisions
into ADRs is how ADR sets become unreadable.

## 7.6 TEMPLATE CHANGE CONTROL — AEV §38

`FACT` `IAPD-PMO-PROJECT-TEMPLATE.md:571` — the PBI section "can always be
developed whenever appropriate". `FACT` AEV §38 makes that baselined-but-amendable
rather than informal:

```
  DISCOVER DEFECT -> RAISE CHANGE -> ANALYZE -> VERIFY
                   -> CHALLENGE -> HUMAN APPROVAL -> SUPERSEDE REVISION
```

`RECOMMENDATION` Revision 1.0 → 1.1 is the first exercise of this loop, and it is
the worked example the template should carry.

## 7.7 DEFECT D10 — TWO DIVERGENT GUIDE COPIES

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:154-160` — "D10 — TWO DIVERGENT
COPIES OF THE GUIDE. `BlueCrown/development-workflow-guide.md` and
`BlueCrown/Laboratory/development/development-workflow-guide.md` carry the same
material at different generations with different section numbers (0100/0200/…
versus 0010/0020/…). There is no stated canonical path."

`FACT` The Laboratory copy is 7996 lines and runs
`BZJ-PGB-0010` → `BZJ-PGB-0074`. The BlueCrown copy was not inspected in this
analysis.

`RECOMMENDATION` Crosswalk both into the adopted grammar; **declare neither
canonical in this pass** — that ruling is out of scope (Part 8.3). `FACT` AEV
§31 keeps this OPEN, so it remains open here.

---

# PART 8 — SCOPE, GATES AND OPEN ITEMS

## 8.1 R3 — RESOLVED BY D6

`FACT` Revision 1.0 carried **R3** as an open item: after the `0004.10` renumber,
`0004.10` would be two digits while `0013.1`, `1004.2` and `8011.7` stayed
single-digit, creating a documented exception in an otherwise uniform ladder.

`RECOMMENDATION` **R3 is withdrawn.** Ruling D6 eliminates the mixed-width
problem at its root: the Charter anchor is never renumbered, so no anchor in the
ladder deviates from the width its canonical PMBOK number gives it. The uniform
ordering rule at Part 0.5 now applies to every identifier without a single
documented exception.

`INFERENCE` This is the second argument for D6, and the quieter one. D1 solved the
collision by creating an exception; D6 removes the need for one.

## 8.2 GATES

`FACT` `bcrd-production-wordkflow-Kilo-0.5.txt:351-369`

```
  G0  Charter Gate                 end of band 0
  G1  Planning Frame Gate           end of band 1
  G2  Planning Complete Gate        end of band 2
  G3  Plan Approval Gate            end of band 3
  G4  Design Approved Gate          end of band 4
  G5  Deliverable Quality Gate      end of band 5
  G6  Human Authorisation Gate      end of band 6
  G7  Control Gate                  end of band 7
  G8  Assurance Gate                end of band 8
  G9  Closure Gate                  end of band 9
```

`RECOMMENDATION` **`0000.01`–`0000.09` carry NO gate of their own.** They are
inputs to **G0**, which sits at `0004.1`. Stated explicitly so that no gate is
implied where none exists, and so that "we are at band 0" and "we are at gate
G0" remain distinguishable mid-block.

`FACT` Per D3, no gate may be passed by an AI agent. `FACT` Per
`bcrd-production-wordkflow-Kilo-0.5.txt:367-368`, the authority is recorded at
`0013.2`. **This analysis does not create a circularity**: the PBI block's human
gate is signed by the PMO authority named in this document's header, and
`0013.2` formalises and extends that register for the instantiated project.

## 8.3 OUT OF SCOPE REGISTER

`RECOMMENDATION` This document explicitly does **not** decide:

1. **Bands 1–9 re-derivation.** Referenced at Part 1.3, inherited unchanged
   (D5).
2. **Which `development-workflow-guide.md` is canonical.** Defect D10. Flagged
   at Part 7.7, and kept OPEN by `FACT` AEV §31.
3. **Migration of the 19 existing `BZJ-PGB-03xx` artifacts** to the adopted
   grammar. Mapped at Part 7.2, not migrated. `FACT` AEV §30 also declines to
   authorise destructive renumbering of historical documents.
4. **Any edit to `IAPD-PMO-PROJECT-TEMPLATE.md`.** This analysis is a record.
   Editing the template is the AECC's output.
5. **The AEV, AEC and AECC.** `FACT` `IAPD-PMO-PROJECT-TEMPLATE.md:121` assigns
   the Verification to the collaborating agents. Producing them here would
   collapse the very independence this analysis requires (F3).

## 8.4 OPEN ITEMS CARRIED TO THE AEC

`FACT` AEV §42 raised ten open items. Disposition:

| AEV item | Disposition here |
|---|---|
| O1 identifier parsing | **CLOSED** by D6 — knowledge-area digits disambiguate; Part 1.2 |
| O2 custom identifier allocation | **CLOSED** — `0000.x` reserved for pre-PMBOK; new custom sections take an unused `SS` in their knowledge area |
| O3 agent independence when a reviewer is unavailable | **CLOSED** — Part 3.3 independence check, dispatch refused rather than collapsed |
| O4 evidence branches mandatory or permitted | **PARTLY** — permitted, not mandatory; record the choice in section evidence |
| O5 canonical workflow guide | **OPEN** — Part 7.7, out of scope here |
| O6 legacy artifact migration | **OPEN** — crosswalk at Part 7.2, migration out of scope |
| O7 automation of transitions | **OPEN** — candidates recorded at Part 3.1 Tier 1 |
| O8 approval authority | **CLOSED** by D3 — named human at every gate; register at `0013.2` |
| O9 template versioning | **CLOSED** by AEV §38 — revision + change-control loop, Part 7.6 |
| O10 cross-agent artifact visibility | **CLOSED** — Part 5.1, agents do not see each other's responses until AEV synthesis |

## 8.5 HANDOFF

```
  THIS DOCUMENT  ──▶  AEV  ──▶  AEC  ──▶  AECC  ──▶  HUMAN GATE
      AEA            agents    agents   agents      PMO authority
                                                     │
                                                     ▼
                                        EDIT IAPD-PMO-PROJECT-TEMPLATE.md
                                        INSTANTIATE THE TEMPLATE
                                                     │
                                                     ▼
                                              G0 at 0004.1
                                              0013.2 Authority Register
                                                     │
                                                     ▼
                                          1004.2 — the inherited ladder
```

---

## SUMMARY OF FINDINGS

| # | Finding | Severity | Disposition |
|---|---|---|---|
| F1 | Three-level loop written as one-and-a-half | HIGH | Part 5 — nested protocol |
| F2 | Approval loop cannot converge | HIGH | Part 5.4 — cap, severity, escalation |
| F3 | Independence can collapse silently | HIGH | Part 3.3 — check at dispatch |
| F4 | "Challenge before build" gate is self-contradictory | MEDIUM | Part 6.7 — two gates, bands 4 and 8 |
| F5 | `0000.09` is a mixed-band section | MEDIUM | Part 6.8 — split, six topics deferred |
| F6 | Branch standard contradicts an adopted decision | MEDIUM | Part 6.7(4) — per-task branches |
| F7 | Skills folder unresolved | LOW | Part 6.7(3) — `.github/skills/` |
| F8 | Four grammars, three orphan families | HIGH | Part 7.1, 7.2 |
| F9 | `0000.02` / `0005.00` overlap | MEDIUM | Part 6.1 — boundary stated |
| F10 | A level may skip the challenge | HIGH | Part 5.2 — atomic protocol |
| F11 | Dual readability undelivered | MEDIUM | Part 4 — mandatory header fields |
| F12 | AEV §7 overturns a human ruling; §8 breaks bands 4–9 | HIGH | Part 2.12 — rejected, resolved by D6 |

`FACT` Twelve findings. Five HIGH, five MEDIUM, two LOW. Ten are structural and
repaired in this document.

`FACT` All eleven revision-1.0 findings were addressed by the AEV, though not
labelled as findings: AEV §6→F1, §14→F2, §19→F3, §13→F4, §23→F5, §21→F6,
§22→F7, §30→F8, §24→F9, §36→F10, §10→F11. F12 was raised by the AEA against the
AEV.

`FACT` Still requiring confirmation at the AEC: F2's escalation owner, F5's six
deferral targets, and the two unproduced collaborating reports at Part 2.12.4.
`FACT` R3 is withdrawn — resolved by D6, Part 8.1.

## REVISION 1.1 CHANGE SUMMARY

| Change | Reason |
|---|---|
| PBI block moved `0004.01`–`0004.09` → `0000.01`–`0000.09` | Ruling D6 |
| Knowledge area `00 Project Framework Initialization (PRE-PMBOK)` added | Ruling D6 |
| Charter anchor `0004.1` **retained unchanged** | Ruling D6 / AEV §9 |
| D1 marked SUPERSEDED BY D6; R3 withdrawn | Ruling D6 |
| Ordering rule restated as uniform, no special case | F12.3 |
| Part 2.12 added — AEV §7 and §8 rejected with proof | F12 |
| Part 7.4–7.6 added — AEV improvements adopted | AEV §29, §36, §37, §38 |
| Part 7.7 D10, Part 8.4 AEV open-item dispositions | completeness |
| Section headers 6.0–6.8 → knowledge area 00 | Ruling D6 |

`<<STOP ARCHITECTURAL ENGINEERING ANALYSIS 1.1>>`
