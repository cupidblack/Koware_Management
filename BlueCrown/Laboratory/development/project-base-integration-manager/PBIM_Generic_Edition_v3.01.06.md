\
# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
|---|---|
| Document | Project Base Integration Manager — Generic Edition |
| Version | **v3.01.00** |
| Document Class | Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework |
| Status | **CONTROLLED GENERIC CANDIDATE — DESIGNED ONLY** |
| Maturity | `DESIGNED` — this document is a specification; it is not evidence that controls operate |
| Scope | Pre-charter probing and readiness only |
| PBIM terminal boundary | `[PROJECT-KEY][GOV-01-0004.1] — Initiate Project or Phase / Develop Project Charter` |
| PBIM Document Creation identifier | `[PROJECT-KEY][PBI-01-0004.01]` |
| Expected timing fields | `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` |
| Implementation authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Production authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Genericity | Technology-, vendor-, repository-, organization-, product- and project-neutral |
| Supersedes | The supplied v3.00.00–v3.00.04 PBIM candidate family conceptually; historical identifiers remain traceable through the consolidation register |

---

# 0. READER'S GUIDE

## 0.1 Purpose

PBIM is a reusable probing framework that moves an initiative from an identified need, opportunity or request toward a controlled, evidence-backed and Charter-ready state.

PBIM establishes enough governance, context, proposal definition, operating-template design, configuration planning, simulation and readiness evidence to support formal Project Charter development.

PBIM does **not** itself constitute:

- a Project Charter;
- a Project Management Plan;
- an implementation authorization;
- a production authorization;
- a procurement authorization;
- a legal opinion;
- a security certification;
- a regulatory approval;
- a budget commitment;
- an operational authorization;
- a substitute for organizational governance.

PBIM ends when the initiative is authorized to enter the formal Charter process at `[GOV-01-0004.1]`.

## 0.2 How to use this document

1. Instantiate the Project Identity Block.
2. Establish the authority, risk, jurisdiction and evidence context.
3. Run PBIM sections in sequence.
4. Do not bypass an exit gate because a later section could theoretically repair the deficiency.
5. Replace every `{{TOKEN}}` before sending a prompt.
6. Attach durable resources inside the prompt's named resource block.
7. Preserve all substantive responses and evidence.
8. Record every material decision in the Decision Ledger.
9. Preserve dissent, conditions, unknowns and adverse findings.
10. Treat agent output as analysis or evidence, never as human authority.
11. Re-run the appropriate assurance stage whenever a material change affects an already-assured artifact.
12. At the terminal boundary, hand the Charter-ready package into the project's ordinary governance framework.

## 0.3 Core architectural principle

```text
AUTHORITY
    ↓
GOVERNANCE
    ↓
ASSURANCE
    ↓
READINESS
    ↓
CHARTER
    ↓
ORDINARY PROJECT LIFECYCLE
```

The sequence is intentional:

- authority defines who may decide;
- governance defines what is controlled;
- assurance tests whether the design is adequate;
- readiness tests whether the configured framework behaves as expected;
- the Charter establishes formal project authorization;
- ordinary project governance takes over after the PBIM boundary.

**Specification is not implementation. Documentation is not evidence of operation. Consensus is not proof.**

---

# 1. CONSOLIDATION AND MODERNIZATION REGISTER

The five supplied PBIM revisions were treated as a related revision family. Repeated or materially similar concepts were consolidated rather than copied forward.

| ID | Repeated/similar concept | Consolidated treatment |
|---|---|---|
| M-01 | Repeated Lead/Collaborating prompt instructions across sections | One Universal Prompt Engineering Contract plus section-specific prompts |
| M-02 | Multiple AEA/AEV/AEC/AECC prompt cycles for PBIM, Proposal and Template | One reusable assurance protocol with subject-specific resources and decisions |
| M-03 | Human authority, agent roles, technical privileges and approval rights described separately | One Authority–Permission–Independence Model |
| M-04 | Evidence classes, durable references, hashes, canonical sources and provenance repeated | One Evidence Integrity and Provenance Model |
| M-05 | Multiple maturity/state ladders | Three orthogonal state axes: Control Maturity, Artifact State and Authorization State |
| M-06 | Multiple stop/reset/emergency-delegation models | One Stop, Reset and Emergency Control Model |
| M-07 | Task Packet defined in several places | One Task Packet Control and schema |
| M-08 | Multiple readiness/activation/final-gate checklists | One Gate Register and Final Pre-Charter Gate |
| M-09 | Several identifier grammars and legacy namespaces | One identifier grammar plus explicit PBIM `0004.01–0004.09` and Charter `0004.1` separation |
| M-10 | Risk profiles repeated within sections | One materiality/risk model referenced by section |
| M-11 | Repeated expected-date rules | One Expected Project Timing Rule |
| M-12 | Proposal and template verification duplicated in different wording | Standardized verification pattern with section-specific tests |
| M-13 | Simulation/readiness and activation treated as interchangeable | Simulation produces evidence; activation is a human governance decision |
| M-14 | PM process names mixed with PBIM internal identifiers | PM classification and PBIM section identity are separate |
| M-15 | Product/vendor-specific architecture embedded in a generic framework | Generic role bindings and placeholders; no vendor is normative |
| M-16 | “Approval” sometimes used for design, implementation and production | Explicit decision and authorization vocabularies |
| M-17 | Fixed process-count claims treated as normative | Process classifications are mapping aids, not PBIM architecture |
| M-18 | Prompt resources sometimes outside the prompt | Resources are embedded inside named `<<START ...>>` / `<<STOP ...>>` blocks |
| M-19 | Prompt end markers varied between revisions | One mandatory prompt marker format |
| M-20 | Missing/unknown facts sometimes filled by inference | `UNKNOWN`, owner and target date required instead of invention |

## 1.1 Defect classes corrected

This edition specifically corrects recurring risks found across the source family:

- identifier ambiguity;
- section ID versus artifact ID confusion;
- prompt-marker inconsistency;
- duplicated assurance logic;
- inconsistent decision vocabularies;
- conflation of specification and enforcement;
- unsupported “unanimous approval” logic;
- aggregate scoring being allowed to hide blockers;
- expected dates being treated as commitments;
- agent capability being confused with governance authority;
- branch names being treated as immutable evidence;
- project-specific examples being treated as generic rules;
- readiness documentation being mistaken for operational proof;
- implementation authority being implied by configuration;
- production authority being implied by Charter readiness.

---

# 2. PROJECT IDENTITY AND EXPECTED TIMING

Complete this block before `PBI-01-0004.01`.

```text
PROJECT-KEY                  : [BASE-ID]-[PROJECT-ID]
PROJECT-NAME                 : [PROJECT-FULL-NAME]
PROJECT-BASE                 : [PROJECT-BASE-NAME]
BASE-ID                      : [BASE-ID]
PROJECT-ID                   : [PROJECT-ID]
ORGANIZATION-CHAIN           : [ORGANIZATION → DEPARTMENT → PMO/CONTROL FUNCTION]
PROJECT-LOCATION             : [JURISDICTION / LOCATION]
PROJECT-FOLDER               : [PROJECT-KEY]
GOVERNANCE-REPOSITORY        : [DURABLE REPOSITORY]
PRODUCTION-REPOSITORY        : [PRODUCTION REPOSITORY, IF APPLICABLE]
DOCUMENT-OWNER               : [HUMAN AUTHORITY]
LEAD-AGENT                   : [BOUND LEAD ROLE]
COLLABORATING-AGENTS         : [BOUND COLLABORATING ROLES]
RISK-PROFILE                 : LIGHT | STANDARD | HIGH-ASSURANCE
DELIVERY-APPROACH-HYPOTHESIS: PREDICTIVE | ITERATIVE | INCREMENTAL | ADAPTIVE | HYBRID | OTHER
JURISDICTION(S)              : [APPLICABLE JURISDICTION(S)]
EXPECTED-PROJECT-DURATION    : [EXPECTED VALUE OR TBD]
EXPECTED-PROJECT-START-DATE  : [EXPECTED DATE OR TBD]
EXPECTED-PROJECT-END-DATE    : [EXPECTED DATE OR TBD]
PBIM-STATE                   : DRAFT
CHARTER-STATUS               : NOT YET DEVELOPED
IMPLEMENTATION-AUTHORIZATION : NOT GRANTED
PRODUCTION-AUTHORIZATION     : NOT GRANTED
```

## 2.1 Expected Project Timing Rule

The three timing fields are deliberately named **EXPECTED** because PBIM is a probing, pre-charter framework.

### `EXPECTED-PROJECT-DURATION`

Record:

- calendar duration;
- working-day convention;
- expected working hours;
- resource/capacity assumptions;
- dependencies;
- expected non-working days where material;
- estimating method;
- optimistic/expected/pessimistic range;
- confidence or evidence quality;
- source of the estimate.

### `EXPECTED-PROJECT-START-DATE`

Use the earliest date at which the required authority, intended resources, dependencies and enabling conditions are reasonably expected to exist.

Do not simply use the PBIM document creation date.

### `EXPECTED-PROJECT-END-DATE`

Derive from the expected start and expected duration while accounting for the stated working calendar and material dependencies.

### If evidence is insufficient

Use:

```text
TBD — Evidence Required: [missing evidence]
Owner: [owner]
Target Date: [date]
Reason: [why calculation is not yet credible]
```

Never manufacture a precise date to make the document look complete.

### Status rule

The expected values:

- are not commitments;
- are not contractual dates;
- are not approved baselines;
- do not authorize expenditure;
- do not authorize implementation;
- do not override later Charter or schedule decisions.

---

# 3. AUTHORITY, ROLES AND INDEPENDENCE

## 3.1 Human authority model

| Code | Generic role | Core authority |
|---|---|---|
| `CA` | Constitutional / organizational constitutional authority | Protects constitutional or organizational control boundaries |
| `H0` | Human Project Authority | Final project-level decisions, risk acceptance, resets and transition authorization |
| `H1` | Delegated Human Governance Authority | Acts only within explicit delegation |
| `H2` | Authorized Operational/Technical Authority | Performs authorized operational/technical actions; does not gain governance authority merely through access |

Organizations may map these codes to their actual titles.

**Technical access never creates governance authority.**

If a required authority does not exist or cannot be verified, the relevant gate is blocked.

## 3.2 Agent roles

| Code | Role | Primary function |
|---|---|---|
| `LEAD` | Lead Agent | synthesis, orchestration, evidence preservation, gate preparation |
| `ANL` | Analysis Agent | independent analysis |
| `VER` | Verification Agent | evidence, traceability, reproducibility and verification |
| `SEC` | Security/Challenge Agent | adversarial challenge and security analysis |
| `IMP` | Implementation Agent | executes only authorized Task Packets |
| `TST` | Test Agent | behavioral and negative-path verification |
| `OPS` | Operations/Readiness Agent | operational readiness, recovery, rollback and handoff |
| `DOC` | Documentation/Registry Agent | identifiers, provenance, revisions and registry integrity |

Roles are capabilities, not authorities.

A single agent may hold multiple roles only when required independence remains valid.

## 3.3 Independence classes

- `I1` — organizational independence;
- `I2` — evidence independence;
- `I3` — technical independence;
- `I4` — governance independence.

Independence must be evidenced where the risk profile requires it.

“Same person, different title” is not automatically independent.

---

# 4. IDENTIFIER ARCHITECTURE

## 4.1 PBIM internal identifier grammar

PBIM uses a dedicated internal namespace:

```text
PBI-[SECTION]-0004.[STEP]
```

Examples:

```text
PBI-01-0004.01
PBI-02-0004.02
PBI-03-0004.03
...
PBI-09-0004.09
```

### Mandatory distinction

The following identifiers are **not interchangeable**:

```text
0004.01  = PBIM Document Creation section suffix
0004.02  = PBIM Architectural Assurance section suffix
...
0004.09  = PBIM Activation & Charter Readiness section suffix

0004.1   = Official project-management coordinate:
           Initiate Project or Phase / Develop Project Charter
```

Therefore:

- `0004.01` is a PBIM internal coordinate;
- `0004.1` is the formal Charter process coordinate;
- PBIM must not silently convert one into the other;
- file names, headings and prompts must preserve the distinction.

## 4.2 Section identifiers

```text
[PBI-01-0004.01] PBIM Document Creation & Baseline Initialization
[PBI-02-0004.02] PBIM Architectural Assurance
[PBI-03-0004.03] Project Context & Proposal Definition
[PBI-04-0004.04] Project Proposal Engineering & Verification
[PBI-05-0004.05] Project Template Assembly
[PBI-06-0004.06] Project Template Engineering & Verification
[PBI-07-0004.07] Project Configuration & Governance Initialization
[PBI-08-0004.08] Project Simulation & Readiness Review
[PBI-09-0004.09] PBIM Activation & Charter Readiness

[GOV-01-0004.1] Initiate Project or Phase / Develop Project Charter
```

## 4.3 Artifact identity

Keep these identities separate:

- Project ID;
- PBIM Section ID;
- Prompt ID;
- Requirement ID;
- Evidence ID;
- Decision ID;
- Risk ID;
- Finding ID;
- ADR ID;
- Change ID;
- Task Packet ID;
- Release ID;
- Document/Artifact ID;
- repository object/commit;
- PM process classification;
- lifecycle state;
- authorization state.

## 4.4 Identifier registry

The Identifier Registry is authoritative.

Recommended fields:

```text
identifier
identifier_type
project_id
pbim_section
sequence
artifact_type
classification
lifecycle_state
status
revision
canonical_location
authority
created_at
supersedes
integrity_reference
```

Allocation sequence:

```text
REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM
```

Conflicts stop the allocation workflow.

Retired identifiers are tombstoned and never silently reused.

## 4.5 Artifact naming

A generic artifact pattern is:

```text
[PROJECT-KEY][SECTION-ID]_<Subject>_<Artifact-Type>-<Agent>-<UTC>.md
```

For authoritative artifacts, include machine-readable metadata such as:

```yaml
id:
revision:
state:
created_at:
supersedes:
integrity_reference:
authority:
```

---

# 5. EVIDENCE, STATE, RISK AND CONTROL MODEL

## 5.1 Evidence classes

Every substantive claim must be classified:

```text
VERIFIED FACT
INFERENCE
ASSUMPTION
PROPOSAL
RECOMMENDATION
RISK
UNKNOWN
DISPUTED
```

Repetition, agent agreement, confidence scores or majority opinion do not upgrade evidence.

An `UNKNOWN` must have:

- owner;
- evidence required;
- target date;
- advancement consequence.

## 5.2 Control maturity

```text
DESIGNED
    ↓
ENFORCEABLE
    ↓
ENFORCED
    ↓
INDEPENDENTLY VERIFIED
```

Meaning:

- **DESIGNED** — specified;
- **ENFORCEABLE** — a credible enforcement mechanism is defined and bounded;
- **ENFORCED** — operation has evidence showing the mechanism acts as intended;
- **INDEPENDENTLY VERIFIED** — an appropriately independent verification has confirmed operation.

Documentation alone cannot establish `ENFORCED` or `INDEPENDENTLY VERIFIED`.

## 5.3 Artifact state

```text
DRAFT
→ ANALYSIS
→ CONTROLLED CANDIDATE
→ VERIFICATION
→ APPROVED
→ SUPERSEDED
→ ARCHIVED
```

## 5.4 Authorization state

Use only where relevant:

```text
NOT-AUTHORIZED
→ AUTHORIZED
→ IMPLEMENTATION-VERIFIED
→ OPERATIONALLY-READY
→ RELEASE-AUTHORIZED
→ PRODUCTION
```

Exception states:

```text
STOPPED
RESET-REQUIRED
```

PBIM itself does not grant production authorization.

## 5.5 Risk profiles

| Profile | Typical use | Minimum posture |
|---|---|---|
| `LIGHT` | bounded, reversible, low-consequence work | proportionate ceremony; protected controls retained |
| `STANDARD` | ordinary project work | material controls and independent review proportionate to risk |
| `HIGH-ASSURANCE` | safety, financial, security, regulated, sensitive-data, irreversible or high-blast-radius work | stronger evidence, independence, challenge and operational verification |

Tailoring may reduce unnecessary ceremony, but it may not remove protected governance, evidence, security or legal controls.

## 5.6 Cumulative materiality

Assess materiality across:

- related tasks;
- dependencies;
- migrations;
- concurrent changes;
- shared infrastructure;
- shared data;
- releases;
- combined blast radius.

Several individually low-risk changes may form one material change.

## 5.7 Stop states

```text
S0 RUNNING
S1 ADVISORY-STOP
S2 MANDATORY-STOP
S3 SYSTEM-STOP
S4 EMERGENCY-SAFETY-STOP
```

Every stop records:

```text
trigger
timestamp
affected scope
authority
evidence
disposition
resume criteria
```

The executor may not self-clear a stop.

## 5.8 Architectural Reset

An Architectural Reset is required when a load-bearing premise fails, including:

- foundational requirement failure;
- authority-boundary failure;
- security-model failure;
- evidence-integrity failure;
- identifier-model failure;
- material architecture failure;
- unresolvable assurance failure.

Reset procedure:

```text
IDENTIFY FAILURE
→ FREEZE AFFECTED WORK
→ PRESERVE EVIDENCE
→ IDENTIFY RESET AUTHORITY
→ RETURN TO EARLIEST AFFECTED STAGE
→ REVISE
→ RE-ASSURE
```

Reset never deletes adverse evidence.

## 5.9 Emergency delegation

Where an organization permits emergency delegation, it must specify:

- authority;
- scope;
- permitted action;
- start time;
- expiry/TTL;
- evidence requirements;
- post-event reconciliation.

Emergency authority never permanently weakens the baseline.

---

# 6. DURABLE REFERENCES, SECURITY AND MODERN PRACTICE

## 6.1 Durable reference rule

For authoritative evidence, prefer:

```text
ARTIFACT-ID @ IMMUTABLE COMMIT/TAG/OBJECT
# INTEGRITY-REFERENCE
```

A mutable branch URL is a convenience pointer, not sufficient authority for critical evidence.

Superseded artifacts remain available and are marked as superseded.

## 6.2 Security and privacy boundary

Where software, data, infrastructure or AI is involved, assess as applicable:

- data classification;
- privacy obligations;
- secrets management;
- least privilege;
- separation of duties;
- privileged access;
- dependency/supply-chain risk;
- secure development;
- logging and auditability;
- vulnerability management;
- backup and recovery;
- incident response;
- resilience;
- secure release;
- data retention/deletion.

Applicable law and binding organizational policy take precedence over generic guidance.

## 6.3 AI-enabled work

Where AI systems or agents are involved, assess:

- model/system role;
- authority boundary;
- data exposure;
- prompt/instruction precedence;
- tool permissions;
- output verification;
- provenance;
- model or dependency changes;
- adversarial inputs;
- human oversight;
- misuse/abuse;
- privacy;
- security;
- operational fallback.

AI output is not authoritative solely because a model generated it.

## 6.4 Standards currency rule

PBIM should reference the applicable current edition of a standard at the time of project instantiation.

As of this edition:

- PMI's **PMBOK Guide — Eighth Edition** is the current PMI guide and retains six principles and seven performance domains while expanding treatment of AI, PMOs and procurement. citeturn4search9
- ISO 21502:2020 remains the published project-management guidance while **ISO/CD 21502 Edition 2** is under development in 2026; the draft must not be represented as a published standard. citeturn4search1turn4search0
- ISO 31000:2018 remains the current published risk-management guideline, confirmed in 2023, while revision activity is underway. citeturn4search3
- ISO/IEC 27001:2022 remains the published ISMS requirements standard and has the 2024 climate-action amendment. citeturn5search0turn5search1
- NIST AI RMF 1.0 remains an applicable voluntary AI risk framework; NIST states that it is being revised and has also published a generative-AI profile. citeturn4search2turn4search11
- OWASP ASVS 5.0.0 is a current application-security verification reference where web/application security is in scope. citeturn5search12

The above are references, not automatic adoption. A project must explicitly identify which standards, laws and policies are binding.

---

# 7. UNIVERSAL PROMPT ENGINEERING CONTRACT

Every operational prompt in PBIM must follow this structure.

```text
#**<<START Prompt N. {{Prompt Label}}>>**#
[Designation: Lead Agent / Collaborating Agents]
ROLE
...
OBJECTIVE
...
CONTEXT
...
INSTRUCTIONS
...
<<START {{Resource Label}}>>
...
<<STOP {{Resource Label}}>>
Note the following:
...
OUTPUT
...
STOP CONDITIONS
...
DECISION SET
...
#**<<STOP Prompt N. {{Prompt Label}}>>**#
```

## 7.1 Mandatory rules

1. Prompt start marker must be:
   `<<START Prompt N. {{Prompt Label}}>>`
2. Prompt must explicitly state `[Designation: ...]`.
3. Instructions must distinguish analysis from authority.
4. Resources must use:
   `<<START {{Resource Label}}>>`
   and
   `<<STOP {{Resource Label}}>>`
5. Notes, where present, appear below the resource block.
6. Prompt end marker must be:
   `<<STOP Prompt N. {{Prompt Label}}>>`
7. Missing information must be marked `UNKNOWN`, not invented.
8. Each material finding must include evidence and materiality.
9. Each condition must have an owner, evidence requirement and advancement consequence.
10. A prompt must state its output.
11. A prompt must state stop conditions where applicable.
12. A prompt must state the permitted decision vocabulary where a decision is required.
13. A collaborating agent must not be instructed to approve its own work.
14. The Lead Agent must preserve minority findings.
15. A prompt must never imply implementation or production authority merely because the prompt asks for configuration, analysis or drafting.

## 7.2 Standard finding format

Use:

```text
Finding
→ Evidence
→ Evidence Class
→ Impact
→ Severity
→ Materiality
→ Affected Control/Artifact
→ Recommendation
→ Owner
→ Required Verification
→ Advancement Consequence
```

## 7.3 Standard decision vocabulary

Use only the decision vocabulary applicable to the stage.

Common values:

```text
APPROVE
APPROVE WITH CONDITIONS
RETURN
BLOCK
RESET
CHALLENGE-BLOCKED
READY
READY WITH CONDITIONS
NOT READY
```

Do not use “unanimous approval” as a substitute for evidence.

---

# 8. ASSURANCE PROTOCOL

## 8.1 Reusable assurance sequence

```text
SUBJECT CANDIDATE
→ AEA
→ INDEPENDENT AEA REPORTS
→ AEV
→ AEV DECISIONS
→ AEC ADVERSARIAL CHALLENGE
→ AEC RESULTS
→ AECC CLOSURE
→ CONTROLLED BASELINE
```

The same structure is reused for:

- PBIM;
- Project Proposal;
- Project Template;

but the subject, resources, questions and materiality criteria change.

## 8.2 AEA

Architectural Engineering Analysis asks:

- Is the subject coherent?
- Is authority defined?
- Are boundaries explicit?
- Are assumptions visible?
- Are controls testable?
- Is evidence sufficient?
- Are dependencies realistic?
- Are failure modes addressed?
- Are security/privacy/regulatory obligations recognized?
- Is the architecture proportionate to risk?
- Is implementation distinct from specification?

AEA does not approve.

## 8.3 AEV

Architectural Engineering Verification asks:

- Does the subject satisfy its stated requirements?
- Are findings resolved?
- Are conditions explicit?
- Are evidence labels accurate?
- Are unknowns preserved?
- Are controls specified and, where applicable, enforceable?
- Is required independence present?

A conditional approval is a return until its conditions are explicitly resolved or formally accepted by the authorized human authority.

## 8.4 AEC

Architectural Engineering Challenge deliberately tries to invalidate the subject.

Challenge:

- authority;
- scope;
- assumptions;
- dependencies;
- evidence;
- security;
- privacy;
- legal/regulatory applicability;
- failure handling;
- stop behavior;
- recovery;
- human unavailability;
- agent disagreement;
- stale baselines;
- evidence loss;
- cumulative materiality;
- false closure.

AEC does not optimize the subject while attacking it.

## 8.5 AECC

AECC closes only findings with evidence-supported disposition.

Each material finding records:

```text
finding
materiality
affected control
disposition
corrective action
evidence
independent confirmation, where required
residual risk
authority
closure state
supersession
```

A vote cannot close a blocker.

---

# 9. PBIM SECTION MAP

| Section | Identifier | Primary output | Gate |
|---:|---|---|---|
| 1 | `PBI-01-0004.01` | Generic PBIM candidate | G1 — Baseline Readiness |
| 2 | `PBI-02-0004.02` | Assured PBIM baseline | G2 — PBIM Assurance Closure |
| 3 | `PBI-03-0004.03` | Initial project proposal | G3 — Context Readiness |
| 4 | `PBI-04-0004.04` | Verified project proposal | G4 — Proposal Baseline |
| 5 | `PBI-05-0004.05` | Project operating template | G5 — Template Assembly |
| 6 | `PBI-06-0004.06` | Verified project template | G6 — Template Readiness |
| 7 | `PBI-07-0004.07` | Initialized governance environment | G7 — Configuration Integrity |
| 8 | `PBI-08-0004.08` | Simulation/readiness evidence | G8 — Readiness |
| 9 | `PBI-09-0004.09` | Charter-ready pre-charter package | G9 — Human Transition Authorization |
| Terminal | `GOV-01-0004.1` | Project Charter | PBIM boundary |

Every section contains:

1. Purpose;
2. Inputs;
3. Activities;
4. Outputs;
5. Dependencies;
6. Evidence requirements;
7. Exit criteria;
8. Stop conditions;
9. Prompts;
10. Gate decision.

---

# 10. [PBI-01-0004.01] — PBIM DOCUMENT CREATION & BASELINE INITIALIZATION

## Purpose

Create or update the generic PBIM from the complete supplied evidence set and prepare a controlled candidate for assurance.

## Inputs

- current PBIM;
- historical PBIM revisions;
- AEA/AEV/AEC/AECC records, where available;
- applicable standards and policies;
- transition decisions;
- known defects and open items.

## Required activities

1. inventory every source;
2. identify sections and identifiers;
3. compare prompt structures;
4. identify repeated concepts;
5. identify obsolete or contradictory controls;
6. reconcile project-management and engineering terminology;
7. establish the generic identifier model;
8. establish the universal prompt contract;
9. preserve historical traceability;
10. produce the generic candidate.

## Exit criteria

- all supplied sources have been assessed or explicitly declared unavailable;
- at least two repeated/similar concepts have been merged;
- no project-specific assumption is silently promoted to a generic rule;
- prompt markers conform to Section 7;
- identifiers are internally consistent.

## Gate

`G1 — PBIM BASELINE READINESS`

### **<<START Prompt 1. PBIM Generic Baseline Synthesis>>**
[Designation: Lead Agent]

ROLE  
Act as the Lead Agent responsible for producing the generic PBIM candidate.

OBJECTIVE  
Synthesize a modern, generic, project-independent PBIM from the supplied source family and assurance evidence.

CONTEXT  
PBIM is a probing pre-charter framework. It must not become a project-specific implementation manual.

<<START PBIM Reference Package>>
1. Current PBIM revision: `{{REFERENCE-1}}`
2. Historical PBIM revisions: `{{REFERENCE-2}}`
3. AEA/AEV/AEC/AECC evidence: `{{REFERENCE-3}}`
4. Applicable standards/policies: `{{REFERENCE-4}}`
5. Transition/open-item records: `{{REFERENCE-5}}`
<<STOP PBIM Reference Package>>

Note the following:
1. Treat historical documents as evidence and design input, not unquestionable authority.
2. Do not invent missing facts.
3. Explicitly identify and merge at least two repeated or similar concepts.
4. Preserve important controls even when wording changes.
5. Separate PBIM internal identifiers from official project-process identifiers.
6. Ensure the three expected project timing fields remain provisional.
7. Apply modern project-management, engineering, security and governance terminology.
8. Record any unresolved source limitation.

INSTRUCTIONS
1. Compare every supplied section, identifier, prompt and control.
2. Create a consolidation matrix.
3. Create a defect/correction matrix.
4. Produce the updated generic PBIM.
5. Produce an identifier crosswalk.
6. Produce a prompt crosswalk.
7. State which source concepts were intentionally not carried forward and why.

OUTPUT
1. Generic PBIM candidate.
2. Consolidation register.
3. Defect register.
4. Identifier crosswalk.
5. Prompt crosswalk.
6. Open-item register.
7. Baseline-readiness recommendation.

STOP CONDITIONS
Stop if a required source is inaccessible, materially contradictory without a resolution basis, or necessary authority cannot be determined.

DECISION SET
`BASELINE-READY / READY-WITH-OPEN-ITEMS / BLOCKED`

#**<<STOP Prompt 1. PBIM Generic Baseline Synthesis>>**#

### **<<START Prompt 2. PBIM Baseline Independent Review>>**
[Designation: Collaborating Agents]

ROLE  
Act as an independent reviewer of the PBIM candidate.

OBJECTIVE  
Attempt to identify substantive architectural, governance, engineering, identifier and prompt defects.

<<START PBIM Candidate>>
{{DURABLE-PBIM-CANDIDATE}}
<<STOP PBIM Candidate>>

<<START PBIM Source Set>>
{{DURABLE-SOURCE-SET}}
<<STOP PBIM Source Set>>

Note the following:
Do not approve merely because the document is comprehensive or well formatted.

INSTRUCTIONS
1. Verify the consolidation claims.
2. Check whether repeated concepts were actually merged rather than renamed.
3. Check identifiers for collision or ambiguity.
4. Check all prompt start/end markers.
5. Check resource block placement.
6. Check expected timing semantics.
7. Check authority versus capability.
8. Check specification versus implementation evidence.
9. Check risk scaling and stop states.
10. Identify outdated or unsupported standards statements.
11. Identify missing controls and unnecessary ceremony.
12. Preserve dissent.

OUTPUT
1. Review scope.
2. Findings using the standard finding format.
3. Consolidation assessment.
4. Identifier defects.
5. Prompt defects.
6. Evidence limitations.
7. Required amendments.
8. Decision.

STOP CONDITIONS
If independent evidence cannot be reviewed, state the limitation. If required independence is unavailable, return `CHALLENGE-BLOCKED`.

DECISION SET
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED`

### **<<STOP Prompt 2. PBIM Baseline Independent Review>>**#

---

# 11. [PBI-02-0004.02] — PBIM ARCHITECTURAL ASSURANCE

## Purpose

Subject the PBIM candidate to AEA → AEV → AEC → AECC before it becomes the controlled generic baseline.

## Required sequence

```text
PBIM Candidate
→ AEA
→ AEV
→ AEC
→ AECC
→ Controlled PBIM Baseline
```

## Gate

`G2 — PBIM ARCHITECTURAL ASSURANCE`

### **<<START Prompt 3. PBIM Architectural Engineering Analysis>>**
[Designation: Lead Agent]

ROLE  
Act as the Lead Agent conducting AEA of the PBIM architecture.

OBJECTIVE  
Determine whether the PBIM architecture is coherent, governable, evidence-based, risk-scaled and implementable as a pre-charter framework.

<<START PBIM Candidate>>
{{DURABLE-PBIM-CANDIDATE}}
<<STOP PBIM Candidate>>

<<START Applicable Standards and Governance References>>
{{CURRENT-APPLICABLE-STANDARDS}}
<<STOP Applicable Standards and Governance References>>

INSTRUCTIONS
Analyze:
1. authority and decision rights;
2. role separation;
3. identifier architecture;
4. registry integrity;
5. evidence provenance;
6. prompt architecture;
7. assurance lifecycle;
8. risk scaling;
9. stop/reset behavior;
10. emergency delegation;
11. Task Packet boundaries;
12. security/privacy boundary;
13. instruction precedence;
14. cumulative materiality;
15. dependency drift;
16. readiness model;
17. human unavailability;
18. recovery;
19. AI/automation risks where applicable;
20. genericity.

For every finding use:
`Finding → Evidence → Impact → Severity → Materiality → Affected Control → Recommendation`

Do not approve the architecture.

OUTPUT
AEA query/report package and prioritized findings.

DECISION SET
`AEA-COMPLETE / MORE-EVIDENCE-REQUIRED / BLOCKED`

#**<<STOP Prompt 3. PBIM Architectural Engineering Analysis>>**#

### **<<START Prompt 4. PBIM Architectural Engineering Verification>>**
[Designation: Collaborating Agents]

ROLE  
Act as independent verification agents.

OBJECTIVE  
Verify whether the proposed PBIM baseline adequately resolves the AEA findings and defines testable controls.

<<START PBIM AEA Package>>
{{AEA-QUERY-AND-REPORTS}}
<<STOP PBIM AEA Package>>

<<START PBIM Candidate>>
{{DURABLE-CANDIDATE}}
<<STOP PBIM Candidate>>

INSTRUCTIONS
For every material control determine whether it has:
1. authority;
2. scope;
3. owner;
4. defined state;
5. evidence path;
6. transition rule;
7. failure response;
8. acceptance criterion;
9. reset behavior where applicable.

Explicitly distinguish:
`DESIGNED / ENFORCEABLE / ENFORCED / INDEPENDENTLY VERIFIED`.

Do not upgrade a state without evidence.

OUTPUT
Verification matrix, unresolved findings, conditions and decision.

DECISION SET
`AEV APPROVE / AEV APPROVE WITH CONDITIONS / AEV RETURN / AEV BLOCK / ARCHITECTURAL RESET`

#**<<STOP Prompt 4. PBIM Architectural Engineering Verification>>**#

### **<<START Prompt 5. PBIM AEV Decisions and Adversarial Challenge Preparation>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent preparing the PBIM for adversarial challenge.

OBJECTIVE  
Consolidate all AEV decisions, resolve eligible conditions and prepare the exact AEC challenge package.

<<START PBIM AEV Decisions>>
{{ALL-AEV-DECISIONS}}
<<STOP PBIM AEV Decisions>>

<<START Current PBIM AEV Statement>>
{{CURRENT-AEV-STATEMENT}}
<<STOP Current PBIM AEV Statement>>

Note the following:
A `RETURN`, `BLOCK` or unresolved material condition prevents progression to AEC.

INSTRUCTIONS
1. Create a finding-by-agent matrix.
2. Preserve minority findings.
3. Resolve only findings that can be resolved with available evidence.
4. Create the next bounded revision where required.
5. Do not suppress disagreement to obtain convergence.
6. Prepare challenge scenarios based on the strongest material risks.

OUTPUT
1. AEV decision matrix.
2. Revised AEV candidate, if required.
3. AEC challenge package.
4. Open-condition register.

DECISION SET
`AEC-READY / REVISE-AEV / BLOCKED / ARCHITECTURAL-RESET`

#**<<STOP Prompt 5. PBIM AEV Decisions and Adversarial Challenge Preparation>>**#

### **<<START Prompt 6. PBIM Architectural Engineering Challenge>>**
[Designation: Collaborating Agents / Challenge Agents]

ROLE  
Act as independent adversarial challenge agents.

OBJECTIVE  
Attempt to break the PBIM architecture rather than improve or defend it.

<<START PBIM AEV-Approved Candidate>>
{{DURABLE-AEV-CANDIDATE}}
<<STOP PBIM AEV-Approved Candidate>>

<<START Existing AEC Evidence>>
{{PRIOR-AEC-EVIDENCE-IF-ANY}}
<<STOP Existing AEC Evidence>>

INSTRUCTIONS
Construct and test realistic failure scenarios involving:
- unavailable human authority;
- conflicting instructions;
- malicious or mistaken agent behavior;
- privileged technical override;
- scope escape;
- evidence tampering;
- identifier collision/corruption;
- stale baseline;
- dependency failure;
- emergency change;
- legal/regulatory conflict;
- security/privacy failure;
- cumulative materiality;
- schedule/capacity collapse;
- sponsor withdrawal;
- operational incident;
- recovery failure;
- false closure.

For each scenario record:
`Trigger → Detection → Expected Stop State → Authority → Evidence → Recovery → Reset Requirement → Preventive Control → Residual Risk`

Do not copy the baseline conclusion as proof.

OUTPUT
Adversarial challenge results with severity and materiality.

STOP CONDITIONS
A live protected-control failure is `BLOCKING`.

DECISION SET
`PASS / MATERIAL-FINDING / BLOCKED / CHALLENGE-INCOMPLETE`

#**<<STOP Prompt 6. PBIM Architectural Engineering Challenge>>**#

### **<<START Prompt 7. PBIM AEC Results and Closure Preparation>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent preparing AECC closure.

OBJECTIVE  
Reconcile all AEA, AEV and AEC findings without hiding material dissent.

<<START PBIM AEC Results>>
{{DURABLE-AEC-RESULTS}}
<<STOP PBIM AEC Results>>

<<START PBIM AEV Statement and Decisions>>
{{DURABLE-AEV-PACKAGE}}
<<STOP PBIM AEV Statement and Decisions>>

INSTRUCTIONS
For every material finding record:
1. original finding;
2. severity/materiality;
3. affected control;
4. disposition;
5. amendment;
6. evidence;
7. independent confirmation where required;
8. residual risk;
9. owner;
10. closure state;
11. supersession.

Do not close a finding by renaming, splitting, downgrading or replacing the artifact without addressing the underlying issue.

OUTPUT
AECC closure candidate and updated PBIM baseline candidate.

DECISION SET
`AECC-READY / RETURN / BLOCKED / ARCHITECTURAL-RESET`

#**<<STOP Prompt 7. PBIM AEC Results and Closure Preparation>>**#

### **<<START Prompt 8. PBIM Architectural Engineering Challenge Closure>>**
[Designation: Lead Agent / Authorized Assurance Authority]

ROLE  
Act as the closure authority within the permitted governance delegation.

OBJECTIVE  
Determine whether the PBIM assurance cycle can be formally closed.

<<START PBIM Assurance Package>>
{{AEA-REPORTS}}
{{AEV-STATEMENT-AND-DECISIONS}}
{{AEC-RESULTS}}
{{AMENDMENT-EVIDENCE}}
<<STOP PBIM Assurance Package>>

INSTRUCTIONS
1. Confirm every material finding has evidence-supported disposition.
2. Confirm required independence.
3. Confirm no material blocker remains hidden.
4. Confirm dissent is preserved.
5. Confirm the final candidate is traceable to the assured revision.
6. Confirm no implementation or production authority has been created.
7. Record residual risk.
8. Establish the durable baseline reference.

OUTPUT
1. AECC closure record.
2. Approved/reset PBIM baseline.
3. Residual-risk register.
4. Remaining dissent.
5. Authorization status.

DECISION SET
`AECC CLOSED / AECC CLOSED WITH ACCEPTED RESIDUAL RISK / RETURN / BLOCKED / ARCHITECTURAL RESET`

#**<<STOP Prompt 8. PBIM Architectural Engineering Challenge Closure>>**#

---

# 12. [PBI-03-0004.03] — PROJECT CONTEXT & PROPOSAL DEFINITION

## Purpose

Instantiate the generic PBIM for a particular project without inventing facts or prematurely designing implementation architecture.

## Required outputs

- project identity;
- authority/stakeholder context;
- problem/opportunity;
- value/outcomes;
- objectives;
- preliminary scope and exclusions;
- constraints;
- assumptions;
- dependencies;
- risk profile;
- security/data considerations;
- delivery approach hypothesis;
- expected timing;
- open decisions;
- proposal candidate.

## Gate

`G3 — CONTEXT READINESS`

### **<<START Prompt 9. Project Context and Proposal Definition>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent for project-specific PBIM instantiation.

OBJECTIVE  
Develop an evidence-classified initial Project Proposal.

<<START Approved PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP Approved PBIM Baseline>>

<<START Project Context Resources>>
{{PROJECT-REQUEST}}
{{BUSINESS/OPERATIONAL-CONTEXT}}
{{KNOWN-REQUIREMENTS}}
{{KNOWN-CONSTRAINTS}}
{{KNOWN-POLICIES}}
{{DOMAIN/TECHNICAL-REFERENCES}}
<<STOP Project Context Resources>>

Note the following:
Do not invent missing facts. Use `UNKNOWN`, `ASSUMPTION`, `DECISION REQUIRED` or `NOT APPLICABLE` with rationale as appropriate.

INSTRUCTIONS
Establish:
1. project identity;
2. authority map;
3. stakeholders;
4. problem/opportunity;
5. intended value/outcomes;
6. measurable objectives;
7. preliminary scope and exclusions;
8. constraints;
9. assumptions;
10. dependencies;
11. risk/materiality;
12. security/privacy/data considerations;
13. delivery approach hypothesis;
14. expected duration/start/end;
15. preliminary success criteria;
16. evidence gaps;
17. proposed Charter inputs.

OUTPUT
Controlled initial Project Proposal and open-decision register.

STOP CONDITIONS
Stop if authority is missing, a material requirement is ambiguous, or requested work would require unauthorized implementation.

DECISION SET
`CONTEXT-READY / READY-WITH-OPEN-DECISIONS / BLOCKED`

#**<<STOP Prompt 9. Project Context and Proposal Definition>>**#

### **<<START Prompt 10. Project Context Independent Review>>**
[Designation: Collaborating Agents]

ROLE  
Act as independent reviewers of the initial Project Proposal.

OBJECTIVE  
Find contradictions, unsupported assumptions, hidden scope and material risks.

<<START Project Proposal>>
{{DURABLE-INITIAL-PROPOSAL}}
<<STOP Project Proposal>>

<<START PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP PBIM Baseline>>

INSTRUCTIONS
Test:
- strategic/value alignment;
- objective measurability;
- scope integrity;
- stakeholder completeness;
- dependency realism;
- assumption quality;
- expected schedule plausibility;
- resource/capacity assumptions;
- security/privacy;
- legal/regulatory triggers;
- procurement implications;
- operational sustainability;
- architecture feasibility at the evidence-supported level.

Every condition must identify an owner, evidence requirement and advancement consequence.

OUTPUT
Independent review and decision.

DECISION SET
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED`

#**<<STOP Prompt 10. Project Context Independent Review>>**#

---

# 13. [PBI-04-0004.04] — PROJECT PROPOSAL ENGINEERING & VERIFICATION

## Purpose

Assure the Project Proposal before it becomes the controlled input to template generation.

## Gate

`G4 — VERIFIED PROJECT PROPOSAL`

### **<<START Prompt 11. Project Proposal AEA>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent for proposal-level Architectural Engineering Analysis.

OBJECTIVE  
Determine whether the proposal is coherent, feasible and sufficiently evidenced for verification.

<<START Controlled Project Proposal>>
{{DURABLE-PROPOSAL}}
<<STOP Controlled Project Proposal>>

<<START Proposal Evidence>>
{{SUPPORTING-EVIDENCE}}
<<STOP Proposal Evidence>>

INSTRUCTIONS
Analyze:
- value;
- objectives;
- scope;
- exclusions;
- requirements;
- authority;
- stakeholders;
- dependencies;
- resources;
- delivery approach;
- expected timing;
- risk/materiality;
- security/privacy;
- legal/regulatory obligations;
- procurement;
- operational implications;
- measurable success.

OUTPUT
AEA findings, assumptions, unknowns, required amendments and conclusion.

DECISION SET
`AEA-COMPLETE / MORE-EVIDENCE-REQUIRED / BLOCKED`

#**<<STOP Prompt 11. Project Proposal AEA>>**#

### **<<START Prompt 12. Project Proposal AEV and AEC Verification>>**
[Designation: Collaborating Agents]

ROLE  
Act as independent verification and challenge agents.

OBJECTIVE  
Verify the proposal and then attempt to invalidate its material assumptions.

<<START Proposal AEA Package>>
{{AEA-REPORTS}}
<<STOP Proposal AEA Package>>

<<START Current Project Proposal>>
{{DURABLE-PROPOSAL}}
<<STOP Current Project Proposal>>

<<START PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP PBIM Baseline>>

INSTRUCTIONS
First verify:
- authority;
- scope;
- requirements;
- evidence;
- assumptions;
- dependencies;
- timing;
- risk;
- security/privacy;
- regulatory applicability;
- success criteria;
- transition implications.

Then challenge:
- hidden scope;
- unowned authority;
- impossible constraints;
- underestimated risk;
- stakeholder conflict;
- dependency failure;
- operational impossibility;
- legal/procurement contradiction;
- unmeasurable success.

Do not optimize the proposal during the challenge.

OUTPUT
1. Verification findings.
2. Adversarial findings.
3. Conditions.
4. Residual risks.
5. Decision.

DECISION SET
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED / RESET`

#**<<STOP Prompt 12. Project Proposal AEV and AEC Verification>>**#

### **<<START Prompt 13. Project Proposal AECC Closure and Baseline>>**
[Designation: Lead Agent / Authorized Human Closure Authority]

ROLE  
Close the Project Proposal assurance cycle within the authorized boundary.

OBJECTIVE  
Resolve or formally disposition every material proposal finding and establish the durable proposal baseline.

<<START Project Proposal Assurance Package>>
{{AEA-REPORTS}}
{{AEV-DECISIONS}}
{{AEC-RESULTS}}
{{AMENDMENTS}}
{{VERIFICATION-EVIDENCE}}
<<STOP Project Proposal Assurance Package>>

INSTRUCTIONS
1. Resolve material findings where evidence permits.
2. Record accepted residual risk only where the correct authority can accept it.
3. Preserve dissent.
4. Do not silently alter proposal scope.
5. Establish the controlled baseline.
6. Record all superseded revisions.

OUTPUT
- Proposal AECC;
- verified Project Proposal;
- residual-risk register;
- decision record;
- durable baseline reference.

DECISION SET
`CLOSED / CLOSED WITH RESIDUAL RISK / RETURN / BLOCKED`

#**<<STOP Prompt 13. Project Proposal AECC Closure and Baseline>>**#

---

# 14. [PBI-05-0004.05] — PROJECT TEMPLATE ASSEMBLY

## Purpose

Generate a project-specific operating template from the verified proposal and PBIM baseline.

## Minimum template domains

- governance;
- authority;
- permissions;
- identifiers;
- stakeholders;
- objectives/outcomes;
- scope;
- requirements;
- schedule;
- finance;
- quality;
- resources;
- communications;
- risk;
- procurement;
- architecture/engineering;
- security;
- privacy/data;
- AI governance where applicable;
- repository/documentation;
- ADRs;
- decisions;
- Task Packets;
- evidence;
- testing;
- change control;
- release;
- operations/readiness;
- closure/retention.

## Gate

`G5 — TEMPLATE ASSEMBLY`

### **<<START Prompt 14. Project Template Assembly>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent assembling the project-specific operating template.

OBJECTIVE  
Translate the verified proposal into a proportionate project operating model without copying unnecessary PBIM ceremony.

<<START Verified Project Proposal>>
{{DURABLE-PROPOSAL-BASELINE}}
<<STOP Verified Project Proposal>>

<<START Approved PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP Approved PBIM Baseline>>

INSTRUCTIONS
1. Select and justify the delivery approach.
2. Define minimum controls.
3. Map each control to its source.
4. Identify owner and authority.
5. Define evidence.
6. Define acceptance criterion.
7. Define lifecycle placement.
8. Define stop condition.
9. Identify controls requiring implementation.
10. Mark unavailable information `UNKNOWN`, `NOT APPLICABLE` with rationale, or `DECISION REQUIRED`.

OUTPUT
1. Project template.
2. Control-to-source traceability map.
3. Unresolved-field register.
4. Gate recommendation.

DECISION SET
`ASSEMBLY-READY / READY-WITH-OPEN-ITEMS / BLOCKED`

#**<<STOP Prompt 14. Project Template Assembly>>**#

### **<<START Prompt 15. Project Template Completeness Review>>**
[Designation: Collaborating Agents]

ROLE  
Act as independent project-template reviewers.

OBJECTIVE  
Identify missing, excessive, contradictory, unowned or unenforceable controls.

<<START Project Template>>
{{DURABLE-TEMPLATE}}
<<STOP Project Template>>

<<START Verified Proposal>>
{{DURABLE-PROPOSAL}}
<<STOP Verified Proposal>>

INSTRUCTIONS
Check:
- lifecycle fit;
- delivery-method fit;
- authority;
- permissions;
- scope;
- requirements;
- evidence;
- security/privacy;
- risk;
- testing;
- change control;
- release;
- operations;
- records/retention;
- Task Packets;
- independence;
- unnecessary ceremony.

OUTPUT
Completeness findings, traceability defects and recommendation.

DECISION SET
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED`

#**<<STOP Prompt 15. Project Template Completeness Review>>**#

---

# 15. [PBI-06-0004.06] — PROJECT TEMPLATE ENGINEERING & VERIFICATION

## Purpose

Verify that the template can safely govern the selected project lifecycle.

## Gate

`G6 — TEMPLATE READINESS`

### **<<START Prompt 16. Project Template Engineering, Verification and Challenge>>**
[Designation: Lead Agent / Collaborating Agents]

ROLE  
The Lead Agent performs engineering synthesis; Collaborating Agents perform independent verification and challenge.

OBJECTIVE  
Determine whether the template is sufficiently complete, coherent and enforceable by design.

<<START Project Template>>
{{DURABLE-TEMPLATE}}
<<STOP Project Template>>

<<START Verified Project Proposal>>
{{DURABLE-PROPOSAL}}
<<STOP Verified Project Proposal>>

<<START PBIM Controls>>
{{RELEVANT-PBIM-CONTROLS}}
<<STOP PBIM Controls>>

INSTRUCTIONS
Evaluate:
1. missing controls;
2. contradictory controls;
3. unowned controls;
4. controls without evidence paths;
5. controls without approval paths;
6. security/privacy gaps;
7. regulatory gaps;
8. operational gaps;
9. technical architecture gaps;
10. AI-governance gaps where applicable;
11. Task Packet boundaries;
12. testing and verification;
13. rollback/recovery;
14. readiness and release.

Then challenge:
- ambiguous authority;
- scope escape;
- conflicting instructions;
- dependency failure;
- failed verification;
- evidence loss;
- emergency change;
- security incident;
- operational failure;
- rollback failure;
- human unavailability.

Explicitly distinguish:
`SPECIFIED / ENFORCEABLE / REQUIRES IMPLEMENTATION / INDEPENDENTLY VERIFIED`.

OUTPUT
Engineering report, verification matrix, challenge results and residual risks.

DECISION SET
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED / RESET`

#**<<STOP Prompt 16. Project Template Engineering, Verification and Challenge>>**#

### **<<START Prompt 17. Project Template AECC Closure and Baseline>>**
[Designation: Lead Agent / Authorized Assurance Authority]

ROLE  
Close the project-template assurance cycle.

OBJECTIVE  
Create a controlled template baseline only after material findings are resolved or formally dispositioned.

<<START Project Template Assurance Package>>
{{TEMPLATE-AEA}}
{{TEMPLATE-AEV}}
{{TEMPLATE-AEC}}
{{DECISIONS}}
{{CORRECTIVE-EVIDENCE}}
<<STOP Project Template Assurance Package>>

INSTRUCTIONS
1. Trace each material finding to closure evidence.
2. Confirm no unresolved blocker is hidden.
3. Preserve dissent.
4. Confirm traceability to the verified proposal.
5. Confirm implementation boundaries.
6. Confirm Task Packet requirements.
7. Confirm evidence and verification requirements.
8. Confirm operational-readiness requirements.
9. Record residual risks.
10. Establish durable baseline.

OUTPUT
Controlled project-template baseline and AECC record.

DECISION SET
`CLOSED / CLOSED WITH RESIDUAL RISK / RETURN / BLOCKED`

#**<<STOP Prompt 17. Project Template AECC Closure and Baseline>>**#

---

# 16. [PBI-07-0004.07] — PROJECT CONFIGURATION & GOVERNANCE INITIALIZATION

## Purpose

Configure the non-production project governance environment from the verified template without silently beginning ordinary implementation.

## Required controls where applicable

- project identity;
- authority register;
- authority–permission matrix;
- identifier registry;
- canonical source manifest;
- repository/document structure;
- protected governance resources;
- Task Packet mechanism;
- evidence register;
- decision ledger;
- risk/assumption/issue registers;
- logging/audit;
- backup/recovery;
- security/quality/CI controls;
- emergency delegation.

## Gate

`G7 — CONFIGURATION INTEGRITY`

### **<<START Prompt 18. Project Governance Configuration and Initialization>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent for controlled project-framework initialization.

OBJECTIVE  
Produce and, where explicitly authorized, execute only the configuration needed to instantiate the approved governance framework.

<<START Approved Project Template>>
{{DURABLE-TEMPLATE}}
<<STOP Approved Project Template>>

<<START Authority and Identifier Resources>>
{{AUTHORITY-REGISTER}}
{{AUTHORITY-PERMISSION-MATRIX}}
{{IDENTIFIER-REGISTRY}}
<<STOP Authority and Identifier Resources>>

<<START Repository and Environment References>>
{{DURABLE-REPOSITORY-AND-ENVIRONMENT-REFERENCES}}
<<STOP Repository and Environment References>>

INSTRUCTIONS
For every configuration item record:
- purpose;
- owner;
- authority;
- permission;
- intended state;
- verification method;
- evidence;
- rollback/recovery;
- stop condition.

Do not apply changes merely because the prompt requests configuration planning.

Do not place secrets, credentials or sensitive authentication material in PBIM prompts or ordinary evidence artifacts.

OUTPUT
1. Configuration plan.
2. Initialization manifest.
3. Registry status.
4. Authority/permission status.
5. Evidence index.
6. Rollback/recovery plan.
7. Authorization boundary.

STOP CONDITIONS
Stop on identity conflict, missing authority, registry conflict, protected-control mismatch, inaccessible target or inability to reproduce the configuration.

DECISION SET
`PROJECT-INITIALIZED / INITIALIZED-WITH-CONDITIONS / BLOCKED`

#**<<STOP Prompt 18. Project Governance Configuration and Initialization>>**#

### **<<START Prompt 19. Project Governance Configuration Verification>>**
[Designation: Collaborating Agents / Verification and Operations Roles]

ROLE  
Independently verify the initialized governance environment.

OBJECTIVE  
Confirm that configuration matches the approved template and did not create unauthorized implementation authority.

<<START Approved Configuration Plan>>
{{DURABLE-CONFIGURATION-PLAN}}
<<STOP Approved Configuration Plan>>

<<START Initialized Governance Evidence>>
{{CONFIGURATION-EVIDENCE}}
<<STOP Initialized Governance Evidence>>

INSTRUCTIONS
Test:
- project identity;
- authority;
- permissions;
- identifier allocation;
- protected resources;
- source manifests;
- Task Packet controls;
- evidence capture;
- audit logging;
- backup/recovery;
- security/quality controls;
- instruction precedence;
- reproducibility.

Explicitly test for authority–permission drift.

OUTPUT
Verification results, defects, residual risk and recommendation.

DECISION SET
`READY / READY WITH CONDITIONS / RETURN / BLOCKED`

#**<<STOP Prompt 19. Project Governance Configuration Verification>>**#

---

# 17. [PBI-08-0004.08] — PROJECT SIMULATION & READINESS REVIEW

## Purpose

Exercise the configured governance and control model before Charter transition.

## Minimum scenario families

Where applicable:

1. normal Task Packet progression;
2. ambiguous requirement;
3. identifier collision;
4. failed verification;
5. material security defect;
6. emergency delegation;
7. unavailable human authority;
8. agent disagreement;
9. evidence loss;
10. rollback/recovery;
11. dependency/environment drift;
12. readiness/release failure.

Simulation must not be represented as production implementation.

## Gate

`G8 — READINESS`

### **<<START Prompt 20. PBIM Project Simulation and Readiness Exercise>>**
[Designation: Lead Agent / Collaborating Assurance Agents]

ROLE  
Conduct a controlled pre-charter simulation of the project governance framework.

OBJECTIVE  
Test whether the configured controls behave as designed under normal and adverse scenarios.

<<START Initialized Project Framework>>
{{DURABLE-INITIALIZED-FRAMEWORK}}
<<STOP Initialized Project Framework>>

<<START Task Packet and Control Model>>
{{TASK-PACKET-MODEL}}
{{STOP-STATE-MODEL}}
{{AUTHORITY-PERMISSION-MATRIX}}
<<STOP Task Packet and Control Model>>

INSTRUCTIONS
1. Select scenarios proportionate to risk.
2. Define preconditions.
3. Define expected control behavior.
4. Execute a tabletop, dry-run or controlled non-production test as appropriate.
5. Record observed behavior.
6. Capture evidence.
7. Record deviations.
8. Record recovery.
9. Record residual risk.
10. Identify controls that remain merely designed.
11. Do not perform production implementation.

OUTPUT
Scenario matrix, simulation results, evidence index, defects, corrective actions and readiness recommendation.

DECISION SET
`READY / READY WITH CONDITIONS / NOT READY / BLOCKED`

#**<<STOP Prompt 20. PBIM Project Simulation and Readiness Exercise>>**#

### **<<START Prompt 21. PBIM Readiness Independent Challenge>>**
[Designation: Collaborating Agents / Challenge Agents]

ROLE  
Act as independent readiness challengers.

OBJECTIVE  
Determine whether the simulation demonstrated actual control behavior or merely demonstrated that documentation exists.

<<START Simulation Results>>
{{DURABLE-SIMULATION-RESULTS}}
<<STOP Simulation Results>>

<<START Readiness Evidence>>
{{CONTROL-STATES}}
{{RISK-REGISTER}}
{{DEPENDENCY-REGISTER}}
{{EVIDENCE-INDEX}}
<<STOP Readiness Evidence>>

INSTRUCTIONS
Challenge:
- negative paths;
- stop enforcement;
- authority conflicts;
- evidence integrity;
- recovery;
- human unavailability;
- operational handoff;
- false-positive tests;
- incomplete scenarios;
- residual risk.

Do not approve because the expected result was documented.

OUTPUT
Independent challenge report and recommendation.

DECISION SET
`ACCEPT / ACCEPT WITH CONDITIONS / REPEAT SIMULATION / BLOCKED`

#**<<STOP Prompt 21. PBIM Readiness Independent Challenge>>**#

---

# 18. [PBI-09-0004.09] — PBIM ACTIVATION & CHARTER READINESS

## Purpose

Assemble the final pre-charter package and determine whether the initiative is justified in crossing the PBIM boundary into formal Charter development.

PBIM activation does **not** authorize production implementation.

## Required final package

- approved PBIM baseline;
- verified Project Proposal;
- verified Project Template;
- initialized governance evidence;
- simulation/readiness evidence;
- material findings;
- residual-risk disposition;
- open decisions;
- expected project timing;
- Charter input package;
- explicit non-authorizations.

## Gate

`G9 — HUMAN TRANSITION AUTHORIZATION`

### **<<START Prompt 22. PBIM Activation and Charter Readiness>>**
[Designation: Lead Agent]

ROLE  
Act as Lead Agent assembling the final pre-charter transition package.

OBJECTIVE  
Determine whether all PBIM gates have been satisfied and prepare the package for human authorization.

<<START PBIM Baseline>>
{{PBIM-BASELINE}}
<<STOP PBIM Baseline>>

<<START Project Proposal Baseline>>
{{PROJECT-PROPOSAL}}
<<STOP Project Proposal Baseline>>

<<START Project Template Baseline>>
{{PROJECT-TEMPLATE}}
<<STOP Project Template Baseline>>

<<START Configuration and Simulation Evidence>>
{{CONFIGURATION-EVIDENCE}}
{{SIMULATION-EVIDENCE}}
<<STOP Configuration and Simulation Evidence>>

<<START Risk and Decision Registers>>
{{RISK-REGISTER}}
{{DECISION-LEDGER}}
{{OPEN-FINDINGS}}
<<STOP Risk and Decision Registers>>

Note the following:
PBIM is a probing framework. Expected timing remains expected unless the project governance framework formally establishes a baseline later.

INSTRUCTIONS
Verify:
1. project identity;
2. authority;
3. jurisdiction;
4. scope boundaries;
5. risk profile;
6. proposal baseline;
7. template readiness;
8. configuration integrity;
9. simulation evidence;
10. material findings;
11. residual risks;
12. open decisions;
13. expected duration;
14. expected start date;
15. expected end date;
16. Charter inputs;
17. explicit non-authorizations.

Do not hide an unresolved blocker behind aggregate scoring.

OUTPUT
1. Final gate checklist.
2. Evidence index.
3. Residual-risk statement.
4. Open-decision statement.
5. Charter input package.
6. Authorization boundary.
7. Recommendation.

DECISION SET
`READY-FOR-H0-AUTHORIZATION / RETURN / BLOCKED`

#**<<STOP Prompt 22. PBIM Activation and Charter Readiness>>**#

### **<<START Prompt 23. PBIM Charter Readiness Independent Review>>**
[Designation: Collaborating Agents]

ROLE  
Act as independent reviewers of the complete pre-charter package.

OBJECTIVE  
Determine whether the evidence justifies transition to Charter development.

<<START Complete PBIM Transition Package>>
{{DURABLE-FINAL-PACKAGE}}
<<STOP Complete PBIM Transition Package>>

INSTRUCTIONS
1. Re-check all mandatory gates.
2. Verify evidence references.
3. Check material-risk ownership.
4. Check unresolved decisions.
5. Check expected timing assumptions.
6. Check legal/policy conditions.
7. Check that production authority has not been implied.
8. Check that Charter inputs are complete.
9. Preserve dissent.

Do not approve production implementation.

OUTPUT
Independent readiness decision and findings.

DECISION SET
`READY / READY WITH CONDITIONS / RETURN / BLOCKED`

#**<<STOP Prompt 23. PBIM Charter Readiness Independent Review>>**#

### **<<START Prompt 24. Human PBIM Transition Authorization>>**
[Designation: Human Project Authority H0]

ROLE  
Act as the authorized human decision-maker for the PBIM-to-Charter transition.

OBJECTIVE  
Determine whether the initiative may cross the PBIM boundary into formal Charter development.

<<START PBIM Transition Package>>
{{FINAL-PBIM-PACKAGE}}
<<STOP PBIM Transition Package>>

<<START Independent Review>>
{{INDEPENDENT-READINESS-REVIEW}}
<<STOP Independent Review>>

INSTRUCTIONS
Confirm:
1. identity;
2. authority;
3. jurisdiction;
4. risk profile;
5. proposal baseline;
6. template baseline;
7. configuration/readiness evidence;
8. residual risks;
9. expected timing;
10. Charter inputs;
11. legal/policy conditions;
12. authorization boundary.

Record conditions explicitly.

Do not treat agent consensus as a substitute for human authority.

OUTPUT
Signed/controlled transition decision and Decision Ledger entry.

DECISION SET
`AUTHORIZE-CHARTER-DEVELOPMENT / AUTHORIZE-WITH-CONDITIONS / RETURN / BLOCK`

#**<<STOP Prompt 24. Human PBIM Transition Authorization>>**#

---

# 19. [GOV-01-0004.1] — INITIATE PROJECT OR PHASE / DEVELOP PROJECT CHARTER

## PBIM terminal boundary

This coordinate is deliberately **`0004.1`**, not `0004.01`.

PBIM ends here.

The Project Charter is developed under the applicable project-governance framework using the approved PBIM transition package.

### **<<START Prompt 25. Develop Project Charter>>**
[Designation: Lead Agent / Human Project Authority]

ROLE  
Develop the Project Charter using the approved pre-charter package.

OBJECTIVE  
Translate the Charter-ready evidence into the project's formal authorization instrument under the applicable organizational governance framework.

<<START PBIM Charter Readiness Package>>
{{DURABLE-CHARTER-READINESS-PACKAGE}}
<<STOP PBIM Charter Readiness Package>>

<<START Approved Project Proposal>>
{{DURABLE-PROPOSAL}}
<<STOP Approved Project Proposal>>

<<START Approved Project Template>>
{{DURABLE-TEMPLATE}}
<<STOP Approved Project Template>>

<<START Authority, Risk and Decision Registers>>
{{AUTHORITY-REGISTER}}
{{RISK-REGISTER}}
{{DECISION-LEDGER}}
<<STOP Authority, Risk and Decision Registers>>

<<START Applicable Project Governance References>>
{{ORGANIZATIONAL-POLICIES}}
{{PROJECT-GOVERNANCE-FRAMEWORK}}
{{APPLICABLE-LAW-AND-REGULATORY-REQUIREMENTS}}
<<STOP Applicable Project Governance References>>

Note the following:
1. PBIM ends at this handoff.
2. Do not extend PBIM identifiers into ordinary project execution.
3. Expected PBIM timing values may become proposed Charter baselines only through the applicable formal project-governance process.
4. Do not silently convert assumptions into approved commitments.

INSTRUCTIONS
Produce a Charter addressing, as applicable:
- project authority;
- purpose and value;
- objectives/outcomes;
- scope and exclusions;
- major requirements;
- stakeholders;
- governance;
- delivery approach;
- major assumptions and constraints;
- high-level risks;
- dependencies;
- resource/funding authority where applicable;
- success criteria;
- major milestones;
- authorization boundaries;
- applicable legal/regulatory obligations.

OUTPUT
Project Charter draft and traceability map to the PBIM package.

DECISION SET
`CHARTER-DRAFT / RETURN-FOR-AMENDMENT / BLOCKED`

#**<<STOP Prompt 25. Develop Project Charter>>**#

### **<<START Prompt 26. Project Charter Independent Review>>**
[Designation: Collaborating Agents]

ROLE  
Independently review the Project Charter.

OBJECTIVE  
Confirm that the Charter faithfully represents the approved pre-charter package and does not introduce unsupported scope, authority or commitments.

<<START Project Charter Draft>>
{{DURABLE-CHARTER-DRAFT}}
<<STOP Project Charter Draft>>

<<START PBIM Transition Package>>
{{DURABLE-PBIM-TRANSITION-PACKAGE}}
<<STOP PBIM Transition Package>>

INSTRUCTIONS
Check:
- authority;
- objectives;
- scope;
- outcomes;
- stakeholders;
- major requirements;
- risk;
- assumptions;
- constraints;
- dependencies;
- delivery approach;
- expected/proposed timing;
- success criteria;
- funding/resource authority;
- legal/policy obligations;
- traceability.

Flag:
- unsupported commitments;
- authority drift;
- scope drift;
- schedule/cost drift;
- unmanaged risks;
- missing success measures;
- contradictory governance.

OUTPUT
Charter review report.

DECISION SET
`CHARTER-APPROVE / CHARTER-APPROVE-WITH-CONDITIONS / CHARTER-REVISE / CHARTER-BLOCK`

#**<<STOP Prompt 26. Project Charter Independent Review>>**#

---

# 20. CROSS-CUTTING CONTROLLED REGISTERS

An instantiated PBIM should establish, where applicable:

1. Canonical Source Manifest;
2. Identifier Registry;
3. Human Authority Register;
4. Authority–Permission Matrix;
5. Control State Register;
6. Requirements Traceability Matrix;
7. Decision Ledger;
8. Risk Register;
9. Assumption Register;
10. Issue/Finding Register;
11. Evidence Index;
12. ADR Index;
13. Change Register;
14. Task Packet Register;
15. Readiness Register;
16. Residual-Risk Register;
17. Supersession Map;
18. Standards/Regulatory Applicability Register;
19. Dependency Register;
20. Authorization Register.

## 20.1 Decision Ledger minimum fields

```text
Decision ID
Date/Time
Question
Decision Owner
Authority
Options Considered
Evidence
Decision
Rationale
Conditions
Affected Artifacts
Effective Revision
Residual Risk
Superseded Decision
```

## 20.2 Finding minimum fields

```text
Finding ID
Subject
Source
Evidence
Evidence Class
Severity
Materiality
Affected Control
Disposition
Owner
Due/Target Date
Verification Requirement
Advancement Consequence
Closure State
Superseded By
```

---

# 21. GATE AND ADVANCEMENT RULES

A gate passes only when required evidence exists and the authorized decision-maker has made the required decision.

A gate must not pass because:

- a majority of agents agree;
- a document appears complete;
- an agent reports high confidence;
- a deadline is approaching;
- the expected files exist;
- a later stage could theoretically fix the issue;
- a score exceeds an arbitrary threshold;
- dissent is inconvenient.

## 21.1 Material blockers

A material blocker remains open until:

1. corrected and independently verified; or
2. formally accepted by the correct human authority where acceptance is permitted.

A minority blocker is still a blocker if its materiality is established.

## 21.2 Conditional approval

A conditional approval is not a final approval unless all conditions are:

- explicitly recorded;
- assigned;
- evidenced;
- verified where required;
- dispositioned by the authorized authority.

## 21.3 Revision bounding

A controlled assurance cycle may use bounded revisions:

```text
R<c>.0
R<c>.1
R<c>.2
R<c>.3
R<c>.4
```

If the bounded cycle cannot converge:

```text
ARCHITECTURAL RESET
```

or a documented human-authorized extension.

Revision limits must never suppress material dissent.

---

# 22. TASK PACKET CONTROL

Where Task Packets are required, every material execution task should define:

```text
TASK-ID
OBJECTIVE
SCOPE
TARGET RESOURCES
PREREQUISITES
DEPENDENCIES
ACCEPTANCE CRITERIA
REQUIRED EVIDENCE
AUTHORIZED EXECUTOR
VERIFIER
STOP CONDITIONS
EXPECTED OUTPUT
PARENT DECISION/ADR
EXPIRY/SUPERSESSION
```

A Task Packet cannot override:

- approved governance controls;
- security controls;
- Charter constraints;
- higher-authority instructions;
- protected baselines.

Out-of-scope work follows:

```text
STOP → REPORT → NEW/SUPERSEDING TASK PACKET
```

---

# 23. INSTRUCTION PRECEDENCE

A project may adapt the following hierarchy to its constitutional and organizational structure:

```text
Applicable Law / Binding Regulation
→ Constitutional / Organizational Authority
→ Protected Security and Governance Controls
→ Approved PBIM Baseline
→ Controlled Project Governance Documents
→ Project Charter
→ Project Operating Template
→ Authorized Task Packet
→ Tool/Runtime Defaults
→ Informal Instructions
```

A lower-level instruction cannot weaken a higher-level control.

Where two instructions conflict, stop and escalate rather than silently selecting the more convenient instruction.

---

# 24. MODERN ENGINEERING AND PROJECT-MANAGEMENT PRINCIPLES

The instantiated project should tailor the following principles according to context:

### Governance

- clear authority;
- accountability;
- transparent decisions;
- evidence-based advancement;
- proportional governance;
- protection of dissent.

### Engineering

- explicit boundaries;
- modularity;
- secure-by-design thinking;
- least privilege;
- separation of duties;
- defense in depth;
- failure-mode analysis;
- observability;
- recoverability;
- reproducibility where practical;
- traceability;
- testability.

### Project management

- value orientation;
- quality;
- stakeholder engagement;
- adaptive tailoring;
- risk integration;
- resource realism;
- sustainable delivery;
- outcome-based success criteria.

### Security and privacy

- data minimization;
- confidentiality, integrity and availability;
- identity and access control;
- secure dependencies;
- vulnerability management;
- privacy obligations;
- incident response;
- recovery.

### AI-enabled projects

- human accountability;
- bounded agent authority;
- provenance;
- output verification;
- data governance;
- adversarial testing;
- model/dependency change control;
- fallback and recovery;
- misuse/abuse analysis.

---

# 25. STANDARDS AND POLICY APPLICABILITY

PBIM is standards-aware but does not automatically adopt every external framework.

At project instantiation, create a Standards/Regulatory Applicability Register:

| Reference | Current status | Applicable? | Binding? | Rationale | Owner |
|---|---|---|---|---|---|
| PMBOK Guide — Eighth Edition | Current PMI guide | [ ] | [ ] | [ ] | [ ] |
| ISO 21502 | Published 2020; revision in progress | [ ] | [ ] | [ ] | [ ] |
| ISO 31000 | Current published risk guideline | [ ] | [ ] | [ ] | [ ] |
| ISO/IEC 27001 | Current published ISMS requirements | [ ] | [ ] | [ ] | [ ] |
| NIST AI RMF | Voluntary; revision in progress | [ ] | [ ] | [ ] | [ ] |
| OWASP ASVS | Applicable application-security reference | [ ] | [ ] | [ ] | [ ] |
| Local law/regulation | Jurisdiction-dependent | [ ] | [ ] | [ ] | [ ] |
| Organizational policy | Organization-dependent | [ ] | [ ] | [ ] | [ ] |

Draft or proposed standards must never be represented as mandatory published requirements unless another authority has explicitly adopted them.

---

# 26. FINAL PRE-CHARTER GATE

The project is Charter-ready only if the following are evidenced:

| # | Gate condition | Evidence | Result |
|---:|---|---|---|
| 1 | Project identity established | [ ] | [ ] |
| 2 | Authority established | [ ] | [ ] |
| 3 | Jurisdiction established | [ ] | [ ] |
| 4 | Risk/materiality established | [ ] | [ ] |
| 5 | PBIM baseline assured | [ ] | [ ] |
| 6 | Project Proposal verified | [ ] | [ ] |
| 7 | Project Template verified | [ ] | [ ] |
| 8 | Governance framework initialized | [ ] | [ ] |
| 9 | Simulation/readiness evidence exists | [ ] | [ ] |
| 10 | Material blockers closed or authorized for acceptance | [ ] | [ ] |
| 11 | Residual risks owned | [ ] | [ ] |
| 12 | Open decisions visible | [ ] | [ ] |
| 13 | Expected duration recorded | [ ] | [ ] |
| 14 | Expected start date recorded | [ ] | [ ] |
| 15 | Expected end date recorded | [ ] | [ ] |
| 16 | Charter inputs complete | [ ] | [ ] |
| 17 | Legal/regulatory applicability reviewed | [ ] | [ ] |
| 18 | Security/privacy applicability reviewed | [ ] | [ ] |
| 19 | Authorization boundaries explicit | [ ] | [ ] |
| 20 | Independent readiness review completed | [ ] | [ ] |
| 21 | Human authority decision recorded | [ ] | [ ] |

No aggregate score may override a material blocker.

---

# 27. PBIM STATE SUMMARY

At every transition, report:

```text
PBIM DOCUMENT STATE:
CONTROL MATURITY:
ARTIFACT STATE:
AUTHORIZATION STATE:
CURRENT SECTION:
CURRENT GATE:
OPEN MATERIAL FINDINGS:
OPEN CONDITIONS:
RESIDUAL RISKS:
EXPECTED-PROJECT-DURATION:
EXPECTED-PROJECT-START-DATE:
EXPECTED-PROJECT-END-DATE:
CHARTER STATUS:
IMPLEMENTATION AUTHORIZATION:
PRODUCTION AUTHORIZATION:
HUMAN DECISION AUTHORITY:
CANONICAL BASELINE:
INTEGRITY REFERENCE:
```

---

# 28. CONSOLIDATION TRACEABILITY SUMMARY

The most important consolidation decisions in this edition are:

1. **Prompt repetition → Universal Prompt Engineering Contract**  
   Repeated Lead/Collaborating instructions are centralized while every operational prompt remains fully executable and section-specific.

2. **Repeated AEA/AEV/AEC/AECC cycles → Standardized Assurance Protocol**  
   The same assurance logic is no longer copied with different IDs and decision codes.

3. **Authority + roles + permissions + independence → Authority–Permission–Independence Model**  
   Technical access is explicitly separated from governance authority.

4. **Evidence + provenance + durable references → Evidence Integrity Model**  
   Claims, evidence classes, canonical sources, hashes, revisions and supersession are treated as one control family.

5. **Maturity + artifact + authorization states → Orthogonal State Model**  
   A document can be approved while a control remains merely designed; these states are no longer conflated.

6. **Stop + reset + emergency → Unified Control Model**  
   S0–S4, Architectural Reset and emergency delegation are governed together.

7. **Multiple readiness lists → One Gate Model**  
   Readiness, activation and Charter transition are separated but linked through G1–G9.

8. **Expected schedule concepts → One Expected Timing Rule**  
   `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE` and `EXPECTED-PROJECT-END-DATE` are explicitly provisional.

9. **Legacy identifier variants → Explicit namespace separation**  
   PBIM Document Creation is `0004.01`; the formal Charter process is `0004.1`.

10. **Project-specific examples → Generic placeholders**  
    No project, product, vendor, repository, country, database or technology is normative.

---

# 29. IMPLEMENTATION READINESS OF THIS GENERIC PBIM

This document is **DESIGNED**, not ENFORCED.

Before adopting it as a controlled organizational baseline, it should undergo its own:

```text
AEA
→ AEV
→ AEC
→ AECC
→ HUMAN AUTHORIZATION
```

The document itself must not claim that these controls are operational merely because they are described here.

---

# 30. SOURCE AND CURRENCY NOTE

This edition was developed from the five supplied PBIM candidate documents:

- `Project_Base_Integration_Manager-v3.00.00.md`
- `Project_Base_Integration_Manager-v3.00.01.md`
- `Project_Base_Integration_Manager-v3.00.02.md`
- `Project_Base_Integration_Manager-v3.00.03.md`
- `Project_Base_Integration_Manager-v3.00.04.md`

The source family showed substantial convergence around:

- a nine-stage PBIM lifecycle;
- AEA → AEV → AEC → AECC assurance;
- evidence classification;
- authority separation;
- risk-scaled governance;
- stop/reset controls;
- project proposal and template assurance;
- framework initialization;
- simulation/readiness;
- human Charter transition.

The principal modernization in this edition is not to add ceremony for its own sake, but to make those converging concepts mutually consistent, explicitly traceable and reusable.

---

# END OF PBIM GENERIC EDITION v3.01.00
