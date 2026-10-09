# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
|---|---|
| Document | Project Base Integration Manager — Generic Edition |
| Version | **v3.01.11** |
| Document Class | Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework |
| Status | **CONTROLLED GENERIC CANDIDATE — RETURN CORRECTED FOR INDEPENDENT RE-REVIEW** |
| Maturity | `DESIGNED` — specification only; implementation is not established by this document |
| Scope | Pre-charter probing, project-framework design, controlled initialization, simulation and Charter readiness |
| PBIM terminal boundary | `PBI-09-0004.00.09` → H0 transition decision → `[GOV-01-0004.01]` handoff |
| PBIM Document Creation identifier | `[PROJECT-KEY]::[PBI-01-0004.00.01]` |
| Prompt namespace | `PROMPT-01` through `PROMPT-25` |
| Expected timing fields | `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` |
| Implementation authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Production authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Genericity | Technology-, vendor-, repository-, organization-, product- and project-neutral |
| Patch basis | v3.01.10 + independent Prompt 2 findings: granular consolidation lineage, canonical identifier repair, explicit independence controls, stop-state semantics, prompt-contract restoration, authority-role normalization |
| Review date | 2026-10-08 |

> This revision is a specification correction. It does not establish that any registry, workflow, TTL mechanism, durable-reference mechanism, CI control, or other mechanism is implemented or operating.

---

# 0. READER'S GUIDE

## 0.1 Purpose

PBIM is a reusable pre-charter framework for moving an initiative from an identified need, opportunity or request toward a controlled, evidence-backed and Charter-ready state. It establishes proportionate governance, context, proposal definition, project-template design, configuration planning, simulation and readiness evidence.

PBIM is not a Project Charter, Project Management Plan, implementation authorization, production authorization, procurement authorization, legal opinion, security certification, regulatory approval, budget commitment, operational authorization, or substitute for organizational governance.

PBIM ends when H0 or a formally recorded H0 delegate authorizes transition into the formal Charter process at `[GOV-01-0004.01]`. Charter-stage work is outside the operational PBIM lifecycle.

## 0.2 Operating principles

1. Authority defines who may decide; capability defines what an actor can do.
2. Evidence defines what may be claimed; documentation alone does not establish operation.
3. Classification identifiers do not become workflow commands merely because they look sequential.
4. A prompt is an instruction artifact, not a security boundary by itself.
5. Protected controls require an enforceable mechanism where enforcement is claimed.
6. Independent challenge preserves dissent; agreement does not constitute proof.
7. Risk scaling may reduce ceremony but may not remove protected legal, security, safety, evidence-integrity or authority controls.
8. Unknown facts remain `UNKNOWN` until supported by evidence.
9. Material blockers cannot be hidden by scores, confidence, consensus or majority agreement.
10. Every material change that invalidates an assurance premise triggers re-assurance from the earliest affected stage.
11. Technical privilege does not create governance authority.
12. A transition boundary must identify both the authorization decision and the post-transition handoff.
13. Specification, enforceability, enforcement, independent verification and authorization are separate states.
14. Emergency operational authority is separate from Charter-transition authority.
15. Agent decision vocabulary is advisory/status vocabulary unless an explicitly recorded human authority is bound to the decision.
16. A control may be consolidated only if its objective, owner, evidence requirement and advancement consequence remain preserved or an explicit supersession decision is recorded.

---

# 1. CONSOLIDATION AND SOURCE-LINEAGE CONTROL

The v3.00.00–v3.00.04 PBIM family and the AEA/AEV/AEC records contain repeated concepts. v3.01.11 consolidates materially identical objectives but does not treat renaming as consolidation.

### 1.1 Canonical consolidation register

| Source family / historical concept | Canonical v3.01.11 control | Treatment | Preservation requirement |
|---|---|---|---|
| Evidence classes, provenance, canonical source, integrity anchors | §5 Evidence & Traceability | MERGED | Evidence class, provenance, authority and integrity remain mandatory |
| Stop, hold, recovery, reset, emergency delegation | §4.6–4.8 Controlled State & Recovery | MERGED | Stop authority, evidence preservation, resume/reset and TTL retained |
| Repeated prompt boilerplate | §6 Universal Prompt Contract | MERGED | Role, context, objective, resources, constraints, method, evidence classification, output, acceptance criteria, stop conditions, decision set and authority boundary retained |
| PBIM section identifiers | §3 Identifier Architecture | NORMALIZED | Canonical `PBI-XX-0004.00.SS` grammar established; legacy aliases retained only for traceability |
| Project/task/document/lifecycle/PM classification identities | §3.3 Identifier Taxonomy | MERGED/SEPARATED | No identifier may serve multiple identity dimensions |
| AEA → AEV → AEC → AECC | §8 + Prompts 3–8, 11–13, 16–17 | PARAMETERIZED | Stage-specific inputs and dispositions retained |
| Authority / permissions / separation of duties | §2 | MERGED | Human authority and technical capability remain distinct |
| Expected duration/start/end | §1 | MERGED | Calendar, interval, timezone and arithmetic consistency retained |
| Risk profiles and cumulative materiality | §4.4–4.5 | MERGED | Protected-control floor retained |
| Task Packet schema and scope enforcement | §7 | MERGED | Mechanical enforcement remains an implementation obligation |
| Durable references | §5.1–5.2 | MERGED | Immutable identity + integrity reference required for critical evidence |
| AI governance references | §5.4 | MERGED | Applicability, human oversight and current-reference checks retained |
| Legacy PM process counts | §3.5 | SUPERSEDED AS NORMATIVE | May remain a classification/crosswalk only |
| Historical Charter anchor `0004.1` | §12 | ALIAS ONLY | Canonical terminal identifier is `[GOV-01-0004.01]` |
| Prompt 8 authority ambiguity | Prompt 8 | CORRECTED | Only a recorded human assurance authority may dispose |
| Prompt 13 authority ambiguity | Prompt 13 | CORRECTED | Only a recorded human closure authority may dispose |
| Prompt 16 self-certification ambiguity | Prompt 16 | CORRECTED | Engineering and independent challenge require distinct accountable actors |
| Prompt 17 authority ambiguity | Prompt 17 | CORRECTED | Human assurance authority explicitly bound |
| Standards currency | §5.5 | CORRECTED | Status is date-sensitive and must be rechecked when adopted |

### 1.2 Consolidation rule

A source control is considered successfully consolidated only when all of the following are recorded:

`SOURCE CONTROL → CANONICAL CONTROL → TREATMENT → RATIONALE → OWNER → EVIDENCE REQUIREMENT → ADVANCEMENT CONSEQUENCE`

A family-level summary alone is not sufficient to prove complete consolidation.

---

# 2. PROJECT IDENTITY AND EXPECTED TIMING

Complete the identity block before `PBI-01-0004.00.01`.

```text
PROJECT-KEY                   : [BASE-ID]-[PROJECT-ID]
PROJECT-NAME                  : [PROJECT-FULL-NAME]
PROJECT-BASE                  : [PROJECT-BASE-NAME]
BASE-ID                       : [BASE-ID]
PROJECT-ID                    : [PROJECT-ID]
ORGANIZATION-CHAIN            : [ORGANIZATION → DEPARTMENT → PMO/CONTROL FUNCTION]
PROJECT-LOCATION              : [JURISDICTION / LOCATION]
PROJECT-FOLDER                : [PROJECT-KEY]
GOVERNANCE-REPOSITORY         : [DURABLE REPOSITORY]
PRODUCTION-REPOSITORY         : [PRODUCTION REPOSITORY, IF APPLICABLE]
DOCUMENT-OWNER                : [HUMAN AUTHORITY]
LEAD-AGENT                    : [BOUND LEAD ROLE]
COLLABORATING-AGENTS          : [BOUND COLLABORATING ROLES]
RISK-PROFILE                  : LIGHT | STANDARD | HIGH-ASSURANCE
DELIVERY-APPROACH-HYPOTHESIS : PREDICTIVE | ITERATIVE | INCREMENTAL | ADAPTIVE | HYBRID | OTHER
JURISDICTION(S)               : [APPLICABLE JURISDICTION(S)]
EXPECTED-PROJECT-DURATION     : [VALUE + UNIT + CALENDAR + RANGE/CONFIDENCE OR TBD]
EXPECTED-PROJECT-START-DATE   : [DATE/TIME + TIMEZONE OR TBD]
EXPECTED-PROJECT-END-DATE     : [DATE/TIME + TIMEZONE OR TBD]
PBIM-STATE                    : DRAFT
CHARTER-STATUS                : NOT YET DEVELOPED
IMPLEMENTATION-AUTHORIZATION  : NOT GRANTED
PRODUCTION-AUTHORIZATION     : NOT GRANTED
```

## 2.1 Timing semantics

Expected timing is planning information, not a commitment or authorization.

Duration must identify, where material:

- calendar type;
- working-day convention;
- working hours/capacity assumptions;
- dependencies;
- non-working days;
- estimating method;
- range/confidence;
- evidence quality/source;
- timezone where a boundary matters.

```text
START = first instant/date interval included in planned execution.
END   = completion boundary after the declared duration under the declared calendar.
```

Date-only schedules must state whether END is inclusive or exclusive. Timestamped schedules require an explicit timezone or UTC offset. Cross-jurisdictional schedules must not rely on an unstated local timezone.

If all three values are known:

`END = START + DURATION`

under the declared calendar and working-time convention, subject to the declared rounding rule. If they do not reconcile, the timing block is `CONFLICTED` and advancement is blocked until resolved. If evidence is insufficient, retain `TBD` with owner, evidence requirement, target date and reason.

---

# 3. AUTHORITY, CAPABILITY AND INDEPENDENCE

## 3.1 Human authority

| Code | Generic role | Authority boundary |
|---|---|---|
| `H0` | Human Project Authority | Final project-level decisions, risk acceptance within mandate, resets and PBIM-to-Charter transition |
| `H1` | Delegated Human Governance/Technical Authority | Acts only within recorded delegation |
| `H2` | Authorized Human Operational/Technical Actor | Performs specifically authorized actions; access does not create governance authority |
| `CA` | Optional Constitutional/Organizational Authority | Use only where the organization actually has such a superior control role |

Technical access never creates governance authority.

## 3.2 Agent capability roles

`LEAD`, `ANL`, `VER`, `SEC`, `IMP`, `TST`, `OPS`, `DOC` and `CHAL` are capability roles, not governance authorities.

## 3.3 Assurance authority normalization

The terms `Authorized Assurance Authority` and `Authorized Human Closure Authority` are not independent authority classes. They are designations bound to an existing human authority record, normally `H1` or `H0`, with:

`actor → capability → technical permissions → human authority class → decision rights → independence class → scope → expiry/review`

If no valid human authority record exists, the prompt is `BLOCKED`; it must not infer authority from title, role label, tool access or agent capability.

## 3.4 Independence

Independence must be evidenced, not merely declared.

Where an assurance activity requires independence, record:

- independent actor identity;
- independence class;
- distinct evidence/input path where required;
- conflict-of-interest assessment;
- authority relationship;
- whether the reviewer participated in creating the item under review;
- residual independence limitations.

A single actor may not self-certify its own engineering work where independent verification is required. If required independence cannot be obtained, the result is `CHALLENGE-BLOCKED` or the applicable blocking state.

## 3.5 Decision vocabulary

- `ASSESSMENT` — analytical/status result only.
- `RECOMMENDATION` — proposed disposition requiring the named authority.
- `AUTHORIZATION` — executable governance decision only when an authorized human authority is explicitly bound and verified.

Agents must not use `APPROVE`, `AUTHORIZE`, `BASELINE` or `RELEASE` as if they independently possess governance authority.

---

# 4. IDENTIFIER ARCHITECTURE AND REGISTRY

## 4.1 Canonical namespaces

The canonical PBIM section grammar is:

`PBI-[NN]-0004.00.[SS]`

Examples:

- `PBI-01-0004.00.01`
- `PBI-02-0004.00.02`
- `PBI-09-0004.00.09`

The Charter boundary is:

`GOV-01-0004.01`

Prompt identifiers are a separate namespace:

`PROMPT-01` … `PROMPT-25`

They are not PBIM section identifiers.

## 4.2 Legacy aliases

The following historical forms are traceability aliases, not new canonical identifiers:

- `0004.01` … `0004.09`
- `PBI-01-0004.01` … `PBI-09-0004.09`
- `GOV-01-0004.1`
- `GOV-01-0004.01`

An instantiated registry must map each legacy alias to one canonical identifier and must never allow the alias and canonical identifier to represent two different objects.

## 4.3 Identifier taxonomy

Keep separate:

Project ID; PBIM Section ID; PM Process Classification ID; Prompt ID; Requirement ID; Work ID; Task Packet ID; Artifact ID; Evidence ID; Decision ID; Risk ID; Finding ID; ADR ID; Change ID; Release ID; repository object/commit; lifecycle state; authorization state.

## 4.4 Registry requirements

The canonical machine-readable registry must contain at least:

```yaml
identifier:
identifier_type:
project_id:
pbim_section:
prompt_id:
pm_process_classification:
work_id:
task_id:
artifact_type:
sequence_index:
classification:
lifecycle_state:
authorization_state:
status:
revision:
created_at:
created_by:
canonical_location:
supersedes:
legacy_aliases:
integrity_reference:
authority:
grammar_version:
issuer:
retirement_state:
```

Uniqueness:

```text
UNIQUE(project_id, identifier_type, identifier)
UNIQUE(project_id, identifier_type, sequence_index) where applicable
```

The registry must reject duplicate identifiers, invalid grammar, missing mandatory fields, illegal lifecycle transitions and conflicting authoritative revisions. Retired identifiers are tombstoned and never silently reused. Machine ordering uses `sequence_index`, never lexical interpretation.

---

# 5. EVIDENCE, STATE, RISK AND CONTROL MODEL

## 5.1 Evidence classes

`VERIFIED FACT`, `INFERENCE`, `ASSUMPTION`, `PROPOSAL`, `RECOMMENDATION`, `RISK`, `UNKNOWN`, `DISPUTED`.

Agreement or confidence does not upgrade evidence. `UNKNOWN` requires owner, evidence required, target date and advancement consequence.

## 5.2 Control maturity

`DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED`

Documentation alone does not prove implementation or operation.

## 5.3 Artifact and authorization states

Artifact:

`DRAFT → ANALYSIS → CONTROLLED CANDIDATE → VERIFICATION → APPROVED → SUPERSEDED → ARCHIVED`

Authorization:

`NOT-AUTHORIZED → AUTHORIZED → IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED → PRODUCTION`

Exception states:

`STOPPED`, `RESET-REQUIRED`, `REGISTRY-BLOCKED`, `CONFLICTED`.

PBIM does not grant production authorization.

## 5.4 Risk profiles

| Profile | Minimum posture |
|---|---|
| `LIGHT` | Core authority, evidence, traceability, stop and verification controls; reduced ceremony elsewhere |
| `STANDARD` | Material controls, independent review where required, change and recovery controls |
| `HIGH-ASSURANCE` | Stronger evidence, genuine independence, challenge, mechanical enforcement and operational verification |

Risk scaling may reduce ceremony but cannot omit a legally mandatory, safety-critical, security-critical, authority-critical, evidence-integrity-critical or explicitly protected control.

## 5.5 Materiality escalation

`DETECT MATERIALITY INCREASE → FREEZE AFFECTED ADVANCEMENT → RECLASSIFY RISK → RE-RUN AFFECTED ASSURANCE → OBTAIN REQUIRED AUTHORITY → UPDATE BASELINE → RESUME ONLY AFTER CONTROLLED RELEASE`

Cumulative materiality must be assessed across related tasks, dependencies, migrations, releases and concurrent changes.

## 5.6 Stop states

| State | Meaning | Advancement effect |
|---|---|---|
| `S0 RUNNING` | Normal controlled work | Continue |
| `S1 ADVISORY-HOLD` | Warning or non-blocking concern | Current step may continue only after recorded review; it is not a mandatory stop |
| `S2 MANDATORY-STOP` | Material ambiguity/control deficiency | Current step stops |
| `S3 SYSTEM-STOP` | Verification, security, integrity or governance failure | Affected progression stops |
| `S4 EMERGENCY-SAFETY-STOP` | Critical authority, legal, financial, data or safety violation | Immediate governed stop |

Every `S1–S4` record contains trigger, timestamp, affected scope, invoking authority, evidence, disposition and resume criteria. No executor self-clears `S2–S4`. `S1` requires recorded review but is explicitly distinguished from a mandatory stop.

## 5.7 Architectural reset

Reset is required when a load-bearing premise fails or the current baseline can no longer be trusted. Preserve adverse evidence, freeze affected work, identify reset authority, return to the earliest affected stage, revise and re-assure.

## 5.8 Emergency delegation

Emergency delegation is an exception, not a permanent bypass. The PBIM ceiling is 72 hours unless a stricter legal, regulatory or organizational rule requires a shorter period.

The record must contain authority, delegate, scope, permitted/prohibited actions, start/expiry time, evidence requirements, reconciliation requirements and confirmation authority.

If confirmation does not occur before expiry, the delegation must become unusable and the governed state must enter `STOPPED`. Expiry enforcement must be proven in implementation; the specification does not prove it.

Emergency operational authority does not itself authorize PBIM-to-Charter transition.

---

# 6. DURABLE REFERENCES, SECURITY, AI AND STANDARDS

## 6.1 Durable evidence

Critical evidence uses:

`ARTIFACT-ID @ IMMUTABLE OBJECT ID + INTEGRITY-HASH`

Mutable branch URLs alone are insufficient for critical claims.

## 6.2 Provenance

Each critical evidence item records Evidence ID, source, canonical location, immutable identity, integrity reference, capture time, capture actor, evidence class and authority basis.

## 6.3 Security/privacy

Where applicable assess classification, privacy obligations, secrets management, least privilege, separation of duties, privileged access, supply-chain risk, secure development, logging/auditability, vulnerability management, backup/recovery, incident response, resilience, secure release and retention/deletion.

Never place credentials, authentication tokens or secrets in PBIM prompts or ordinary evidence artifacts.

## 6.4 AI-enabled work

Where AI systems or agents are involved assess system role, authority boundary, data exposure, instruction precedence, tool permissions, output verification, provenance, model/dependency changes, adversarial inputs, human oversight, misuse, privacy, security and operational fallback.

## 6.5 Standards currency

References are advisory unless adopted or made binding by law, contract or organizational governance. Certification or legal compliance must never be inferred from citation alone.

At the 2026-10-08 review date:

- PMBOK Guide — Eighth Edition is the current PMI edition.
- ISO 21502:2020 remains a published International Standard and is under revision; ISO/CD 21502 Edition 2 is development material, not a published International Standard.
- ISO 31000:2018 remains published and is under revision.
- ISO/IEC 27001:2022 + Amendment 1:2024 is a published reference where applicable.
- ISO/IEC 42001:2023 is an AI-management reference where applicable.
- NIST AI RMF 1.0 remains a voluntary reference and is being revised; current status must be checked when adopted.
- OWASP ASVS 5.0.0 is the current stable ASVS reference identified by OWASP at review time.

---

# 7. UNIVERSAL PROMPT ENGINEERING CONTRACT

Every operational PBIM prompt shall contain:

1. exact matching prompt start/end pair;
2. explicit designation;
3. `ROLE`;
4. `OBJECTIVE`;
5. `CONTEXT`;
6. named resource block where resources apply;
7. constraints;
8. method;
9. evidence classification;
10. required output;
11. acceptance criteria or explicit statement that acceptance criteria are not applicable;
12. stop conditions;
13. decision set;
14. explicit authority boundary / prohibited authority.

Resource blocks must be balanced. The resource block is placed before its Notes and before Output/Stop/Decision sections.

Prompt integrity checks:

- exactly one matching prompt pair;
- balanced resource markers;
- no resource block contains its prompt end marker;
- placeholders are resolvable or explicitly `UNKNOWN`;
- resource labels match the supplied evidence;
- no secret is embedded;
- decision authority is not inferred from capability;
- decision vocabulary is explicit;
- independence limitations are disclosed;
- implementation claims are distinguished from specification.

## 7.1 Standard finding format

```text
Finding:
Evidence:
Impact:
Severity:
Materiality:
Affected Control:
Recommendation:
Owner:
Evidence Required:
Advancement Consequence:
Dissent / Alternative View:
```

---

# 8. TASK PACKET CONTROL AND MECHANICAL ENFORCEMENT

A Task Packet is the controlled unit of executable work.

Minimum fields:

`project_id, task_id, lifecycle_stage, objective, scope, exclusions, authoritative references, source files, authorized paths, requirements, constraints, assigned actor, permitted/prohibited actions, deliverables, verification requirements, acceptance criteria, output location, branch/worktree requirement, logging requirement, dependencies, stop/escalation conditions, authorization reference, expiry/validity, revision`

Where repository/file scope is claimed as enforced, a mechanical control must detect unauthorized paths, protected-resource changes, missing authorization, stale/superseded Task Packets and inconsistent task identity.

A named workflow is a specification example only. Its presence in documentation does not prove implementation.

---

# 9. TRACEABILITY AND ASSURANCE

PBIM supports:

`Requirement → Analysis → Architectural Decision → Verification → Task Packet / Implementation → Test → Approval / Authorization → Release`

AEA = architectural analysis.  
AEV = independent verification.  
AEC = adversarial challenge.  
AECC = closure and formal disposition.

Consensus is not verification. Verification is not authorization. Human authority owns governance decisions.

Blocking findings include protected-control failure, load-bearing premise failure, mandatory legal/security/safety failure, authority-boundary failure, evidence-integrity failure, identifier-integrity failure, timing conflict, failed required independence or unmet transition criteria.

---

# 10. PBIM LIFECYCLE

1. `PBI-01-0004.00.01` — Document Creation & Baseline Initialization — G1.
2. `PBI-02-0004.00.02` — Architectural Assurance — G2.
3. `PBI-03-0004.00.03` — Project Context & Proposal Definition — G3.
4. `PBI-04-0004.00.04` — Project Proposal Engineering & Verification — G4.
5. `PBI-05-0004.00.05` — Project Template Assembly — G5.
6. `PBI-06-0004.00.06` — Project Template Engineering & Verification — G6.
7. `PBI-07-0004.00.07` — Project Configuration & Governance Initialization — G7.
8. `PBI-08-0004.00.08` — Project Simulation & Readiness Review — G8.
9. `PBI-09-0004.00.09` — PBIM Activation & Charter Readiness — G9.
10. `[GOV-01-0004.01]` — Initiate Project or Phase / Develop Project Charter — PBIM boundary.

Minimum simulation families include normal Task Packet progression, ambiguous requirement, identifier collision, failed verification, security defect, emergency delegation, unavailable human authority, agent disagreement, evidence loss, rollback/recovery, dependency drift, timing inconsistency, readiness failure and unauthorized decision attempt.

---

# 11. STANDARDIZED PROMPT SET

Prompts 1–24 belong to the operational PBIM lifecycle. Prompt 25 is a post-PBIM handoff artifact and is not a PBIM gate.

Each prompt below uses the same mandatory control contract. Section-specific objectives and decision sets remain distinct.

## Prompt 1 — PBIM Generic Baseline Synthesis

PROMPT-ID: `PROMPT-01`

<<START Prompt 1 — PBIM Generic Baseline Synthesis>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Synthesize a generic PBIM baseline from the supplied source set.

CONTEXT  
Use only supplied authoritative resources. Do not infer missing authority, identity, evidence or implementation.

INSTRUCTIONS  
Compare historical identifiers, controls, prompts and workflows; identify repeated concepts; preserve protected controls; classify evidence; produce a migration and supersession view.

<<START PBIM Reference Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Reference Package>>
Note: Critical claims require provenance and immutable identity.

OUTPUT  
Synthesis, source-to-control lineage, findings, unresolved items, implementation limitations and recommendation.

ACCEPTANCE CRITERIA  
No material source control is silently dropped; every retained/superseded control has a recorded treatment.

STOP CONDITIONS  
Material source conflict, inaccessible required evidence, protected-control weakening, malformed resources or authority ambiguity.

DECISION SET  
APPROACHABLE / APPROACHABLE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 1 — PBIM Generic Baseline Synthesis>>

## Prompt 2 — PBIM Baseline Independent Review

PROMPT-ID: `PROMPT-02`

<<START Prompt 2 — PBIM Baseline Independent Review>>
[Designation: Collaborating Agents]

ROLE  
Independent collaborating reviewers.

OBJECTIVE  
Identify substantive architectural, governance, engineering, identifier and prompt defects.

CONTEXT  
Use only the supplied candidate and source set. Do not infer missing authority or implementation.

INSTRUCTIONS  
Verify consolidation claims; test repeated concepts for actual merging; check identifiers, markers, resources, timing, authority/capability, evidence/implementation, risk/stop states, standards currency and unnecessary ceremony. Preserve dissent.

<<START PBIM Candidate + PBIM Source Set>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + PBIM Source Set>>
Note: Mutable pointers alone do not establish critical evidence.

OUTPUT  
Review scope; findings in standard finding format; consolidation assessment; identifier defects; prompt defects; evidence limitations; required amendments; decision.

ACCEPTANCE CRITERIA  
Every material finding cites candidate/source evidence and states advancement consequence.

STOP CONDITIONS  
Required independent evidence cannot be reviewed or genuine independence is unavailable.

DECISION SET  
RECOMMEND APPROVE / RECOMMEND APPROVE WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 2 — PBIM Baseline Independent Review>>

## Prompt 3 — PBIM Architectural Engineering Analysis

PROMPT-ID: `PROMPT-03`

<<START Prompt 3 — PBIM Architectural Engineering Analysis>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Analyze architecture, governance, evidence, risk scaling and genericity.

CONTEXT  
Use supplied authoritative resources only.

INSTRUCTIONS  
Test load-bearing assumptions, authority boundaries, identifiers, evidence model, recovery, Task Packets, standards and Charter boundary.

<<START PBIM Candidate + Applicable Standards and Governance References>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + Applicable Standards and Governance References>>
Note: Standards are advisory unless adopted.

OUTPUT  
Architectural findings and recommendation.

ACCEPTANCE CRITERIA  
All protected controls are addressed or explicitly marked unresolved.

STOP CONDITIONS  
Missing authority, protected-control failure, identifier conflict, failed independence or invalid scope.

DECISION SET  
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 3 — PBIM Architectural Engineering Analysis>>

## Prompt 4 — PBIM Architectural Engineering Verification

PROMPT-ID: `PROMPT-04`

<<START Prompt 4 — PBIM Architectural Engineering Verification>>
[Designation: Collaborating Agents]

ROLE  
Independent verification agents.

OBJECTIVE  
Verify material controls against authority, ownership, evidence, transition, failure and acceptance criteria.

CONTEXT  
Use the AEA package and candidate.

INSTRUCTIONS  
Distinguish designed, enforceable, implemented and independently verified controls. Do not self-certify engineering work.

<<START AEA Package + PBIM Candidate>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA Package + PBIM Candidate>>
Note: Documentation is not implementation evidence.

OUTPUT  
Verification findings and recommendation.

ACCEPTANCE CRITERIA  
Material controls have evidence-classified verification status.

STOP CONDITIONS  
Failed independence, evidence-integrity failure or material contradiction.

DECISION SET  
VERIFY / VERIFY WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 4 — PBIM Architectural Engineering Verification>>

## Prompt 5 — PBIM AEV Decisions and Adversarial Challenge Preparation

PROMPT-ID: `PROMPT-05`

<<START Prompt 5 — PBIM AEV Decisions and Adversarial Challenge Preparation>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Consolidate AEV results and prepare a bounded challenge package.

CONTEXT  
Do not convert recommendations into authority.

INSTRUCTIONS  
Preserve dissent, unresolved verification issues and challenge targets.

<<START AEV Decisions + Current AEV Statement>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV Decisions + Current AEV Statement>>
Note: AEV agreement is not authorization.

OUTPUT  
Challenge package and recommendation.

ACCEPTANCE CRITERIA  
Every material AEV issue has disposition or remains explicitly open.

STOP CONDITIONS  
Missing AEV evidence or authority ambiguity.

DECISION SET  
PREPARED / PREPARED WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 5 — PBIM AEV Decisions and Adversarial Challenge Preparation>>

## Prompt 6 — PBIM Architectural Engineering Challenge

PROMPT-ID: `PROMPT-06`

<<START Prompt 6 — PBIM Architectural Engineering Challenge>>
[Designation: Collaborating Agents / Challenge Agents]

ROLE  
Independent challenge agents.

OBJECTIVE  
Attempt to break the architecture rather than defend it.

CONTEXT  
Use supplied evidence and the applicable risk profile.

INSTRUCTIONS  
Attack hidden authority, privilege bypass, registry corruption, evidence manipulation, false independence, scope escape, emergency abuse, stop/reset bypass, timing conflict and Charter-boundary leakage.

<<START AEV Candidate + Relevant Architecture and Governance Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV Candidate + Relevant Architecture and Governance Evidence>>
Note: Independence limitations must be disclosed.

OUTPUT  
Falsifiable attacks, evidence, materiality, disposition and dissent.

ACCEPTANCE CRITERIA  
Material attacks are traceable to expected controls.

STOP CONDITIONS  
Independence is not genuine or required evidence is unavailable.

DECISION SET  
AEC PASS / AEC PASS WITH AMENDMENTS / AEC FAIL / AEC BLOCKED

<<STOP Prompt 6 — PBIM Architectural Engineering Challenge>>

## Prompt 7 — PBIM AEC Results and Closure Preparation

PROMPT-ID: `PROMPT-07`

<<START Prompt 7 — PBIM AEC Results and Closure Preparation>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Reconcile challenge results without suppressing dissent.

CONTEXT  
Do not convert consensus into proof.

INSTRUCTIONS  
Map each attack to evidence, disposition, residual risk, owner and re-verification.

<<START AEC Results + AEV Statement and Decisions>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEC Results + AEV Statement and Decisions>>
Note: Closure requires the designated human authority.

OUTPUT  
Closure recommendation and unresolved issues.

ACCEPTANCE CRITERIA  
No material challenge is silently discarded.

STOP CONDITIONS  
Material blocker, missing evidence or missing closure authority.

DECISION SET  
CLOSURE-READY / CLOSURE-READY WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 7 — PBIM AEC Results and Closure Preparation>>

## Prompt 8 — PBIM Architectural Engineering Challenge Closure

PROMPT-ID: `PROMPT-08`

<<START Prompt 8 — PBIM Architectural Engineering Challenge Closure>>
[Designation: Lead Agent for preparation; recorded Human Assurance Authority for disposition]

ROLE  
Lead Agent prepares; a recorded human assurance authority disposes.

OBJECTIVE  
Close the architectural assurance cycle.

CONTEXT  
The designation does not create authority. The human authority record must identify an H0/H1 authority with decision scope.

INSTRUCTIONS  
Preserve original findings, amendments, verification evidence, residual risk and dissent.

<<START AEA/AEV/AEC/Amendment Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA/AEV/AEC/Amendment Evidence>>
Note: If the human assurance authority cannot be verified, stop.

OUTPUT  
Closure package and disposition.

ACCEPTANCE CRITERIA  
All material blockers are closed or formally dispositioned by the recorded human authority.

STOP CONDITIONS  
Missing human authority, unresolved material blocker or failed independence.

DECISION SET  
CLOSE / CLOSE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 8 — PBIM Architectural Engineering Challenge Closure>>

## Prompt 9 — Project Context and Proposal Definition

PROMPT-ID: `PROMPT-09`

<<START Prompt 9 — Project Context and Proposal Definition>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Develop an evidence-classified project proposal from the controlled PBIM baseline.

CONTEXT  
Do not invent missing facts.

INSTRUCTIONS  
Establish identity, authority, objectives, scope, assumptions, dependencies, risk profile, delivery approach, security/data considerations and expected timing.

<<START Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved PBIM Baseline>>
Note: Expected dates remain provisional.

OUTPUT  
Controlled initial project proposal.

ACCEPTANCE CRITERIA  
Material facts are classified and traceable.

STOP CONDITIONS  
Missing authority, material ambiguity or unauthorized implementation requirement.

DECISION SET  
DEFINED / DEFINED WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 9 — Project Context and Proposal Definition>>

## Prompt 10 — Project Context Independent Review

PROMPT-ID: `PROMPT-10`

<<START Prompt 10 — Project Context Independent Review>>
[Designation: Collaborating Agents]

ROLE  
Independent reviewers.

OBJECTIVE  
Review the initial proposal for contradictions, hidden scope, unsupported assumptions, authority gaps and material risks.

CONTEXT  
Use the proposal and baseline only.

<<START Project Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Proposal + PBIM Baseline>>
Note: Review independence must be disclosed.

OUTPUT  
Findings, evidence, dissent and recommendation.

ACCEPTANCE CRITERIA  
No material proposal element is accepted solely from assertion.

STOP CONDITIONS  
Failed independence or unavailable required evidence.

DECISION SET  
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 10 — Project Context Independent Review>>

## Prompt 11 — Project Proposal AEA

PROMPT-ID: `PROMPT-11`

<<START Prompt 11 — Project Proposal AEA>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Analyze proposal coherence, feasibility, evidence and material risk.

CONTEXT  
Use the controlled proposal and evidence.

<<START Controlled Project Proposal + Supporting Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Controlled Project Proposal + Supporting Evidence>>
Note: Do not approve on behalf of authority.

OUTPUT  
Proposal AEA findings and recommendation.

ACCEPTANCE CRITERIA  
Scope, feasibility, dependencies, security/privacy and success criteria are addressed.

STOP CONDITIONS  
Material contradiction or authority gap.

DECISION SET  
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 11 — Project Proposal AEA>>

## Prompt 12 — Project Proposal AEV and AEC Verification

PROMPT-ID: `PROMPT-12`

<<START Prompt 12 — Project Proposal AEV and AEC Verification>>
[Designation: Collaborating Agents]

ROLE  
Independent verification/challenge agents.

OBJECTIVE  
Verify and challenge the project proposal.

CONTEXT  
Use the proposal AEA and current proposal.

<<START Proposal AEA Package + Current Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal AEA Package + Current Proposal + PBIM Baseline>>
Note: Preserve dissent.

OUTPUT  
Verification/challenge findings.

ACCEPTANCE CRITERIA  
Material proposal risks and assumptions have evidence-based dispositions.

STOP CONDITIONS  
Failed independence, missing evidence or material contradiction.

DECISION SET  
VERIFY / VERIFY WITH CONDITIONS / AEC BLOCKED / RETURN / BLOCKED

<<STOP Prompt 12 — Project Proposal AEV and AEC Verification>>

## Prompt 13 — Project Proposal AECC Closure and Baseline

PROMPT-ID: `PROMPT-13`

<<START Prompt 13 — Project Proposal AECC Closure and Baseline>>
[Designation: Lead Agent for preparation; recorded Human Closure Authority for disposition]

ROLE  
Lead Agent prepares; recorded human closure authority disposes.

OBJECTIVE  
Close the proposal assurance package and authorize its baseline status only within recorded human authority.

CONTEXT  
The human closure authority must be verified.

<<START Proposal Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal Assurance Package + Decisions + Corrective Evidence>>
Note: An agent may not self-authorize the baseline.

OUTPUT  
Closure package, baseline disposition, residual risk and dissent.

ACCEPTANCE CRITERIA  
Every material finding is closed or formally accepted by the recorded authority.

STOP CONDITIONS  
Missing authority or unresolved material blocker.

DECISION SET  
BASELINE / BASELINE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 13 — Project Proposal AECC Closure and Baseline>>

## Prompt 14 — Project Template Assembly

PROMPT-ID: `PROMPT-14`

<<START Prompt 14 — Project Template Assembly>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Assemble a proportionate project operating template.

CONTEXT  
Use the verified proposal and approved PBIM baseline.

<<START Verified Project Proposal + Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Verified Project Proposal + Approved PBIM Baseline>>
Note: Do not claim implementation.

OUTPUT  
Template draft and control map.

ACCEPTANCE CRITERIA  
Required governance, authority, identity, evidence, security, change, Task Packet and recovery controls are represented.

STOP CONDITIONS  
Missing protected control or unauthorized implementation.

DECISION SET  
DRAFTED / DRAFTED WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 14 — Project Template Assembly>>

## Prompt 15 — Project Template Completeness Review

PROMPT-ID: `PROMPT-15`

<<START Prompt 15 — Project Template Completeness Review>>
[Designation: Collaborating Agents]

ROLE  
Independent template reviewers.

OBJECTIVE  
Identify missing, excessive, contradictory or unenforceable template controls.

CONTEXT  
Review the template against the verified proposal and PBIM baseline.

<<START Project Template + Verified Proposal>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Verified Proposal>>
Note: Identify unnecessary ceremony as well as omissions.

OUTPUT  
Findings, evidence, dissent and recommendation.

ACCEPTANCE CRITERIA  
The review distinguishes missing controls from optional ceremony.

STOP CONDITIONS  
Failed independence or unavailable evidence.

DECISION SET  
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 15 — Project Template Completeness Review>>

## Prompt 16 — Project Template Engineering, Verification and Challenge

PROMPT-ID: `PROMPT-16`

<<START Prompt 16 — Project Template Engineering, Verification and Challenge>>
[Designation: Lead Agent for engineering; distinct Collaborating Agents for independent challenge/verification]

ROLE  
Engineering lead and independent challenge function with distinct accountable actors.

OBJECTIVE  
Engineer the template without self-certification.

CONTEXT  
The engineering actor may not be the sole verifier/challenger.

<<START Template + Completeness Review + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Template + Completeness Review + Corrective Evidence>>
Note: If distinct independence is unavailable, return `CHALLENGE-BLOCKED`.

OUTPUT  
Engineered template, verification findings, challenge findings and unresolved obligations.

ACCEPTANCE CRITERIA  
Independent challenge is performed by a distinct accountable actor and material findings are dispositioned.

STOP CONDITIONS  
Self-certification, failed independence, protected-control failure or scope escape.

DECISION SET  
VERIFY / VERIFY WITH CONDITIONS / AEC BLOCKED / RETURN / BLOCKED

<<STOP Prompt 16 — Project Template Engineering, Verification and Challenge>>

## Prompt 17 — Project Template AECC Closure and Baseline

PROMPT-ID: `PROMPT-17`

<<START Prompt 17 — Project Template AECC Closure and Baseline>>
[Designation: Lead Agent for preparation; recorded Human Assurance Authority for disposition]

ROLE  
Lead Agent prepares; recorded human assurance authority disposes.

OBJECTIVE  
Close template assurance and establish the template baseline only through verified human authority.

CONTEXT  
Authority and independence must be recorded.

<<START Template Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Template Assurance Package + Decisions + Corrective Evidence>>
Note: Baseline status is not implementation authorization.

OUTPUT  
Closure package, baseline decision, residual risks and implementation obligations.

ACCEPTANCE CRITERIA  
Material findings are closed or formally dispositioned by the recorded human authority.

STOP CONDITIONS  
Missing authority, failed independence or unresolved material blocker.

DECISION SET  
BASELINE / BASELINE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 17 — Project Template AECC Closure and Baseline>>

## Prompt 18 — Project Governance Configuration and Initialization

PROMPT-ID: `PROMPT-18`

<<START Prompt 18 — Project Governance Configuration and Initialization>>
[Designation: Lead Agent operating under recorded implementation authority]

ROLE  
Lead Agent within recorded implementation authority.

OBJECTIVE  
Instantiate approved governance configuration only within authorized technical and governance scope.

CONTEXT  
The implementation authority must be recorded before execution.

<<START Approved Template + Configuration Authorization>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Template + Configuration Authorization>>
Note: Specification does not prove successful initialization.

OUTPUT  
Initialization record and evidence.

ACCEPTANCE CRITERIA  
Only approved configuration is instantiated; unauthorized changes are absent or stopped.

STOP CONDITIONS  
Missing implementation authority, scope escape, security defect or identifier conflict.

DECISION SET  
INITIALIZATION-RECOMMENDED / INITIALIZATION-RECOMMENDED WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 18 — Project Governance Configuration and Initialization>>

## Prompt 19 — Project Governance Configuration Verification

PROMPT-ID: `PROMPT-19`

<<START Prompt 19 — Project Governance Configuration Verification>>
[Designation: Collaborating Agents / Verification Roles]

ROLE  
Independent verification roles; operational actors must not self-certify their own initialization.

OBJECTIVE  
Verify the initialized governance environment.

CONTEXT  
Compare approved configuration to observed evidence.

<<START Approved Configuration Plan + Initialized Governance Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Configuration Plan + Initialized Governance Evidence>>
Note: Distinguish evidence of existence from evidence of correct operation.

OUTPUT  
Verification report.

ACCEPTANCE CRITERIA  
Material configuration controls have evidence-backed status.

STOP CONDITIONS  
Failed independence, unauthorized changes or missing evidence.

DECISION SET  
VERIFY / VERIFY WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 19 — Project Governance Configuration Verification>>

## Prompt 20 — PBIM Project Simulation and Readiness Exercise

PROMPT-ID: `PROMPT-20`

<<START Prompt 20 — PBIM Project Simulation and Readiness Exercise>>
[Designation: Lead Agent / Collaborating Assurance Agents]

ROLE  
Simulation coordinator and assurance participants.

OBJECTIVE  
Exercise configured governance under normal and adverse scenarios.

CONTEXT  
Simulation evidence demonstrates behavior only for tested scenarios.

<<START Initialized Project Framework + Task Packet/Control Model>>
{{DURABLE-RESOURCE-SET}}
<<STOP Initialized Project Framework + Task Packet/Control Model>>
Note: Simulation is not production authorization.

OUTPUT  
Scenario results, failures, evidence, readiness observations and recommendations.

ACCEPTANCE CRITERIA  
Required normal and adverse scenarios are exercised with recorded outcomes.

STOP CONDITIONS  
Unsafe simulation, missing authority or inability to preserve evidence.

DECISION SET  
READY FOR CHALLENGE / READY WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 20 — PBIM Project Simulation and Readiness Exercise>>

## Prompt 21 — PBIM Readiness Independent Challenge

PROMPT-ID: `PROMPT-21`

<<START Prompt 21 — PBIM Readiness Independent Challenge>>
[Designation: Collaborating Agents / Challenge Agents]

ROLE  
Independent challenge agents.

OBJECTIVE  
Challenge whether simulation evidence demonstrates actual control behavior.

CONTEXT  
Do not treat a successful simulation narrative as proof of untested behavior.

<<START Simulation Evidence + Initialized Controls>>
{{DURABLE-RESOURCE-SET}}
<<STOP Simulation Evidence + Initialized Controls>>
Note: Independence must be genuine.

OUTPUT  
Challenge findings and readiness recommendation.

ACCEPTANCE CRITERIA  
Negative cases, failures, recovery and stop behavior are challenged.

STOP CONDITIONS  
Failed independence or insufficient simulation evidence.

DECISION SET  
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 21 — PBIM Readiness Independent Challenge>>

## Prompt 22 — PBIM Activation and Charter Readiness

PROMPT-ID: `PROMPT-22`

<<START Prompt 22 — PBIM Activation and Charter Readiness>>
[Designation: Lead Agent]

ROLE  
Lead Agent.

OBJECTIVE  
Assemble the final pre-Charter package without granting Charter authority.

CONTEXT  
Only a human authority may authorize Charter transition.

<<START PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>
Note: Unresolved material blockers prevent advancement.

OUTPUT  
Final transition package and recommendation.

ACCEPTANCE CRITERIA  
All mandatory readiness questions are answered with evidence or formally escalated.

STOP CONDITIONS  
Unresolved protected control, timing conflict, registry failure, evidence-integrity failure or missing authority.

DECISION SET  
READY FOR HUMAN AUTHORIZATION / READY WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 22 — PBIM Activation and Charter Readiness>>

## Prompt 23 — PBIM Charter Readiness Independent Review

PROMPT-ID: `PROMPT-23`

<<START Prompt 23 — PBIM Charter Readiness Independent Review>>
[Designation: Collaborating Agents]

ROLE  
Independent reviewers.

OBJECTIVE  
Determine whether evidence justifies Charter transition recommendation.

CONTEXT  
This is a recommendation, not authorization.

<<START Complete PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP Complete PBIM Transition Package>>
Note: Preserve dissent and distinguish implementation evidence from specification.

OUTPUT  
Independent review with findings and recommendation.

ACCEPTANCE CRITERIA  
Transition evidence is complete, traceable and independently challenged.

STOP CONDITIONS  
Failed independence, material blocker or insufficient evidence.

DECISION SET  
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 23 — PBIM Charter Readiness Independent Review>>

## Prompt 24 — Human PBIM Transition Authorization

PROMPT-ID: `PROMPT-24`

<<START Prompt 24 — Human PBIM Transition Authorization>>
[Designation: Human Project Authority H0 or formally recorded H0 delegate]

ROLE  
Human Project Authority H0 or formally recorded H0 delegate.

OBJECTIVE  
Make the authorized human PBIM-to-Charter transition decision.

CONTEXT  
Verify the H0 authority record and review the complete transition package.

<<START PBIM Transition Package + Independent Review>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Transition Package + Independent Review>>
Note: This prompt may authorize only the PBIM-to-Charter transition within the recorded authority boundary.

OUTPUT  
Human decision record with authority, scope, evidence, rationale and effective boundary.

ACCEPTANCE CRITERIA  
Authority is valid; mandatory readiness conditions are satisfied or expressly and lawfully accepted; transition target is identified.

STOP CONDITIONS  
Invalid authority, unresolved mandatory blocker, evidence-integrity failure, identifier conflict or timing conflict.

DECISION SET  
AUTHORIZE CHARTER TRANSITION / AUTHORIZE WITH CONDITIONS / RETURN / BLOCK

<<STOP Prompt 24 — Human PBIM Transition Authorization>>

## Prompt 25 — Post-PBIM Charter Handoff Package

PROMPT-ID: `PROMPT-25`

<<START Prompt 25 — Post-PBIM Charter Handoff Package>>
[Designation: Lead Agent operating under H0-authorized Charter-stage Task Packet]

ROLE  
Lead Agent operating under an H0-authorized Charter-stage Task Packet.

OBJECTIVE  
Prepare or transmit Charter-stage inputs only after the H0 authorization record exists.

CONTEXT  
Prompt 25 is outside the operational PBIM lifecycle and cannot authorize Charter work by itself.

<<START H0 Authorization Record + Approved PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP H0 Authorization Record + Approved PBIM Transition Package>>
Note: Charter-stage authority must come from the receiving governance process.

OUTPUT  
Traceable Charter handoff package.

ACCEPTANCE CRITERIA  
Every material Charter input is traceable to a PBIM artifact, decision or explicitly recorded new Charter-stage input.

STOP CONDITIONS  
Missing H0 authorization, contradictory evidence, invalid scope or missing Charter-stage authority.

DECISION SET  
HANDOFF COMPLETE / HANDOFF WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 25 — Post-PBIM Charter Handoff Package>>

---

# 12. UNIVERSAL PROMPT INSTANCE — REQUIRED FORM

```text
<<START Prompt N. [Prompt Label]>>
[Designation: [ROLE(S)]]

ROLE
[role]

OBJECTIVE
[objective]

CONTEXT
[context]

INSTRUCTIONS
[instructions]

<<START [Resource Label]>>
[resource content or durable references]
<<STOP [Resource Label]>>
Note the following:
[notes]

METHOD
[method]

EVIDENCE CLASSIFICATION
[required evidence classes]

OUTPUT
[required output]

ACCEPTANCE CRITERIA
[acceptance criteria or NOT-APPLICABLE with rationale]

STOP CONDITIONS
[objective stop conditions]

DECISION SET
[permitted assessment/recommendation vocabulary]

AUTHORITY BOUNDARY
[what the actor is and is not authorized to do]

<<STOP Prompt N. [Prompt Label]>>
```

The opening and closing labels must match exactly. A prompt cannot acquire governance authority merely by using a governance verb.

---

# 13. PBIM / CHARTER BOUNDARY

```text
PBI-09-0004.00.09
        ↓
PBIM FINAL PRE-CHARTER PACKAGE
        ↓
PROMPT-23 — INDEPENDENT REVIEW
        ↓
PROMPT-24 — H0 TRANSITION AUTHORIZATION
        ↓
HANDOFF
        ↓
[GOV-01-0004.01] — Initiate Project or Phase / Develop Project Charter
```

Prompt 25 is not a PBIM gate.

Historical `GOV-01-0004.1` is retained only as a legacy alias and must not be allocated as a second current identifier.

---

# 14. IMPLEMENTATION-VERIFICATION OBLIGATIONS

Where applicable, the instantiated project must independently evidence:

- Human Authority Register;
- Authority–Permission Matrix;
- canonical Identifier Registry;
- registry integrity and alias controls;
- evidence-integrity mechanism;
- challenge-independence controls;
- instruction-drift detection;
- Task Packet scope enforcement;
- mechanical scope workflow;
- stop/reset enforcement;
- repository protections;
- secret/security controls;
- operational-readiness gate;
- durable-reference controls;
- privileged-account reconciliation;
- emergency TTL enforcement;
- audit/evidence retention.

A design amendment is not implementation closure evidence.

---

# 15. CLOSURE MATRIX

| Control | v3.01.11 disposition | Closure state |
|---|---|---|
| Consolidation traceability | Expanded from family-level to control-lineage register requirement | Closed in specification; source-by-source instantiation still required |
| Identifier ambiguity | Canonical `PBI-XX-0004.00.SS`; legacy aliases explicitly separated | Closed in specification |
| Prompt 8/13/17 authority | Human authority class explicitly required and verified | Closed in specification |
| Prompt 16 independence | Distinct accountable engineering and challenge actors required | Closed in specification |
| Stop-state ambiguity | `S1 ADVISORY-HOLD` explicitly non-mandatory; `S2–S4` blocking semantics explicit | Closed in specification |
| Prompt contract loss | Method, evidence classification, acceptance criteria and authority boundary restored | Closed in specification |
| Durable references | Immutable identity + integrity rule retained | Implementation unproven |
| Task Packet scope | Mechanical enforcement retained | Implementation unproven |
| Emergency delegation | 72-hour ceiling and expiry enforcement retained | Enforcement unproven |
| Timing consistency | Duration/start/end reconciliation retained | Closed in specification |
| Standards currency | Date-sensitive status retained | Recheck required when adopted |

---

# 16. EVIDENCE LIMITATIONS AND PRESERVED DISSENT

1. This document remains `DESIGNED`.
2. A named CI workflow does not prove it exists or operates.
3. A 72-hour TTL requirement does not prove automated expiry exists.
4. An immutable-object/hash requirement does not prove compliant evidence exists.
5. Historical AEA/AEV/AEC records are evidence about prior review, not proof of current operation.
6. Independent agents may agree while sharing flawed evidence; agreement is not verification.
7. Historical source content is not automatically current normative authority.
8. A consolidation register in a specification does not prove every historical control has been correctly migrated in an instantiated implementation.
9. Repository-rendering behavior is not implementation evidence; raw immutable source should be used where exact prompt-marker integrity must be established.
10. Standards status is time-sensitive and must be rechecked when an instantiated project adopts a standard.

---

# 17. FINAL READINESS QUESTIONS

Before H0 transition authorization:

1. Is project identity controlled?
2. Is human authority identified?
3. Are authority and capability separated?
4. Are privileged technical accounts reconciled?
5. Is risk profile defined?
6. Are timing values estimates with explicit calendar, interval and timezone semantics?
7. Do known duration/start/end values reconcile?
8. Is the baseline traceable to immutable evidence?
9. Is the identifier registry authoritative and uniquely constrained?
10. Are identifier grammars, issuers and legacy aliases defined?
11. Are material claims evidence-classified?
12. Is the proposal verified?
13. Has the proposal been challenged?
14. Is the template internally coherent?
15. Are Task Packets bounded?
16. Is mechanical scope enforcement implemented where claimed?
17. Are security/privacy boundaries defined?
18. Can the project stop safely?
19. Can it reset safely?
20. Is emergency authority bounded and expiry enforced where claimed?
21. Has the framework been initialized?
22. Has it been simulated?
23. Has readiness been independently challenged?
24. Are material blockers resolved or formally escalated?
25. Are Charter inputs traceable to PBIM artifacts?
26. Are all agent decision vocabularies interpreted as assessment/recommendation unless human authority is explicitly bound?
27. Is H0 prepared to authorize formal Charter transition?

If any mandatory answer is unresolved:

```text
DO NOT ADVANCE TO [GOV-01-0004.01]
```

---

# 18. DECISION AND RELEASE STATUS

```text
ARTIFACT STATE: CONTROLLED CANDIDATE
CONTROL MATURITY: DESIGNED
IMPLEMENTATION: NOT PROVEN
PRODUCTION AUTHORIZATION: NOT GRANTED
PBIM DECISION: RETURN — PENDING INDEPENDENT RE-REVIEW
```

Required progression conditions:

- independently verify the canonical Identifier Registry and legacy-alias mapping;
- independently verify durable-reference implementation;
- verify or implement mechanical Task Packet scope enforcement;
- demonstrate emergency TTL expiry;
- reconcile privileged accounts;
- preserve immutable baseline evidence;
- independently re-review authority-role normalization and prompt independence;
- validate exact prompt marker balance from the raw immutable document;
- confirm source-by-source consolidation lineage for all material historical controls.

---

# 19. CHANGE HISTORY

| Version | Change |
|---|---|
| v3.01.08 | Independent-review closure candidate |
| v3.01.09 | Authority/capability separation, emergency delegation, prompt namespace, protected-control risk rule, prompt integrity and implementation-evidence language |
| v3.01.10 | Prompt 2 corrections: advisory decision boundary, family-level consolidation matrix, timing invariant, identifier grammar fields, qualified emergency ceiling, standards-currency clarification |
| **v3.01.11** | Independent review corrections: granular consolidation lineage requirement; canonical PBI identifier normalization and legacy aliases; explicit human assurance-authority binding; distinct independence requirement for Prompt 16; explicit S1 semantics; restoration of method/evidence-classification/acceptance/authority-boundary prompt controls |

---

# 20. AUTHORITATIVE REFERENCE REGISTER

| Reference | Status / use |
|---|---|
| PMBOK Guide — Eighth Edition | Current PMI edition at review date; advisory |
| ISO 21502:2020 | Published International Standard; under revision |
| ISO/CD 21502 Edition 2 | Development material; not a published International Standard |
| ISO 31000:2018 | Published International Standard; under revision |
| ISO/IEC 27001:2022 + Amd 1:2024 | Published security-management reference where applicable |
| ISO/IEC 42001:2023 | AI-management reference where applicable |
| NIST AI RMF 1.0 | Voluntary reference; revision status must be checked when adopted |
| OWASP ASVS 5.0.0 | Application-security verification reference where applicable |

---

# 21. FINAL CONTROL PRINCIPLE

PBIM is not made durable by being comprehensive.

It is durable only when:

```text
IDENTITY IS UNAMBIGUOUS
+ CONSOLIDATION IS SOURCE-TRACEABLE
+ AUTHORITY IS EXPLICIT
+ CAPABILITY IS SEPARATE
+ INDEPENDENCE IS EVIDENCED
+ AGENT DECISIONS CANNOT CREATE UNGRANTED AUTHORITY
+ EVIDENCE IS DURABLE
+ TIMING VALUES ARE INTERNALLY CONSISTENT
+ IDENTIFIER GRAMMAR AND UNIQUENESS ARE ENFORCED
+ LEGACY IDENTIFIERS CANNOT COLLIDE WITH CANONICAL IDENTIFIERS
+ SCOPE IS MECHANICALLY BOUNDED WHERE CLAIMED
+ RISK ESCALATES WITH MATERIALITY
+ STOP/RESET STATES HAVE EXPLICIT SEMANTICS AND AUTHORITY
+ EMERGENCY AUTHORITY EXPIRES
+ PROMPTS HAVE BALANCED RESOURCE MARKERS
+ PROMPTS RETAIN METHOD, EVIDENCE, ACCEPTANCE AND AUTHORITY CONTROLS
+ THE PBIM/CHARTER BOUNDARY IS UNAMBIGUOUS
+ IMPLEMENTATION CLAIMS ARE SUPPORTED BY IMPLEMENTATION EVIDENCE
+ DISSENT REMAINS VISIBLE
```

**End of PBIM Generic Edition v3.01.11.**
