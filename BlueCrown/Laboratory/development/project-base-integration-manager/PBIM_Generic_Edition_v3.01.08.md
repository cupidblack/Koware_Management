# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
|---|---|
| Document | Project Base Integration Manager — Generic Edition |
| Version | **v3.01.08** |
| Document Class | Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework |
| Status | **CONTROLLED GENERIC CANDIDATE — TARGETED CLOSURE REVISION** |
| Maturity | `DESIGNED` — this document is a specification; it is not evidence that controls operate |
| Scope | Pre-charter probing, project-framework design, controlled initialization, simulation and Charter readiness |
| PBIM terminal boundary | `PBI-09-0004.09` → H0 transition decision → `[GOV-01-0004.1]` handoff |
| PBIM Document Creation identifier | `[PROJECT-KEY][PBI-01-0004.01]` |
| Expected timing fields | `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` |
| Implementation authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Production authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Genericity | Technology-, vendor-, repository-, organization-, product- and project-neutral |
| Patch basis | v3.01.07 + independent baseline review closure amendments |
| Review date | 2026-10-08 |

> **Important:** This revision is a specification. A control stated here is not evidence that it is deployed, enforceable or operating.

---

# 0. READER'S GUIDE

## 0.1 Purpose

PBIM is a reusable pre-charter framework for moving an initiative from an identified need, opportunity or request toward a controlled, evidence-backed and Charter-ready state.

PBIM establishes proportionate governance, context, proposal definition, project-template design, configuration planning, simulation and readiness evidence. It does not replace ordinary project governance after the PBIM boundary.

PBIM does **not** constitute:
* a Project Charter;
* a Project Management Plan;
* an implementation authorization;
* a production authorization;
* a procurement authorization;
* a legal opinion;
* a security certification;
* a regulatory approval;
* a budget commitment;
* an operational authorization; or
* a substitute for organizational governance.

PBIM ends when H0 or a formally recorded H0 delegate authorizes transition into the formal Charter process at `[GOV-01-0004.1]`. The Charter-stage work itself is outside the operational PBIM lifecycle.

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

## 0.3 Core lifecycle

```text
AUTHORITY
   ↓
GOVERNANCE
   ↓
ASSURANCE
   ↓
READINESS
   ↓
H0 CHARTER-TRANSITION AUTHORIZATION
   ↓
FORMAL HANDOFF
   ↓
ORDINARY PROJECT / CHARTER LIFECYCLE
```

Specification is not implementation. Implementation is not verification. Verification is not authority.

---

# 1. CONSOLIDATION AND INDEPENDENT-REVIEW AMENDMENT REGISTER

The supplied v3.00.00–v3.00.04 family and AEA/AEV/AEC evidence were treated as design evidence rather than unquestionable authority. Repeated concepts were consolidated where the control objective was materially the same.

| ID | Consolidated concept | v3.01.08 treatment | Status |
|---|---|---|---|
| M-01 | Lead/collaborator prompt boilerplate | Universal Prompt Engineering Contract + explicit prompt instances | Strengthened |
| M-02 | AEA/AEV/AEC/AECC cycles | Reusable assurance protocol | Retained |
| M-03 | Authority, roles and permissions | Authority–Permission–Independence Model | Strengthened |
| M-04 | Evidence/provenance/durable references | Evidence Integrity and Durable Reference Model | Strengthened with immutable identity/hash rule |
| M-05 | Multiple state ladders | Control Maturity, Artifact State, Authorization State | Retained as orthogonal axes |
| M-06 | Stop/reset/emergency models | Controlled State & Recovery Model | Strengthened with hard emergency TTL |
| M-07 | Repeated Task Packet definitions | One Task Packet schema + named mechanical enforcement workflow | Strengthened |
| M-08 | Readiness/final gates | Gate Register + final pre-Charter gate | Retained |
| M-09 | Identifier grammars | PBIM identifiers separated from PM classifications + canonical registry | Strengthened with uniqueness constraint |
| M-10 | Repeated risk profiles | Materiality/risk model | Retained |
| M-11 | Expected-date rules | One Expected Project Timing Rule | Strengthened with interval/calendar/timezone semantics |
| M-12 | Proposal/template verification duplication | Standardized verification pattern | Retained |
| M-13 | Simulation vs activation | Simulation produces evidence; H0 authorization activates transition | Retained |
| M-14 | PM process IDs mixed with PBIM IDs | PM classification and PBIM identity remain separate | Retained |
| M-15 | Project-specific implementation details | Generic role bindings/placeholders | Retained |
| M-16 | Ambiguous approval terminology | Decision vocabulary separated from authorization vocabulary | Strengthened |
| M-17 | Fixed process-count claims | Process classifications are mapping aids, not architecture | Retained |
| M-18 | Prompt resources outside prompts | Named resource blocks inside prompts | Retained |
| M-19 | Variable prompt end markers | One mandatory marker grammar | Retained |
| M-20 | Inferred missing facts | `UNKNOWN` with owner, evidence requirement and advancement consequence | Retained |
| M-21 | Prompt-only enforcement | Mechanical Task Packet scope control | Strengthened with `.github/workflows/task-scope-check.yml` specification |
| M-22 | Identifier registry described but not instantiated | Canonical machine-readable registry with explicit uniqueness key | Strengthened |
| M-23 | Secret leakage risk | Evidence redaction control | Retained |
| M-24 | Reset authority ambiguity | Explicit reset authority and escalation path | Retained |
| M-25 | Role-combination ambiguity | Combination matrix + independence test | Retained |
| M-26 | AI governance reference gap | Conditional ISO/IEC 42001:2023 reference | Retained |
| M-27 | Durable-reference material finding | Critical reference requires immutable object identity and SHA-256/object-ID evidence | **Closed in design** |
| M-28 | Emergency TTL material finding | Hard 72-hour TTL and automatic `STOPPED` fallback | **Closed in design** |
| M-29 | Privileged-account drift | Technical privilege must reconcile to governance authority | **New closure control** |
| M-30 | PBIM/Charter boundary ambiguity | Prompt 25 is explicitly post-PBIM handoff material | **New closure control** |

**Consolidation claim:** repeated objectives are standardized into governing models, while prompt instances retain only prompt-specific role, objective, resources, tests and decision vocabulary. Identical control boilerplate is not treated as independent controls.

---

# 2. PROJECT IDENTITY AND EXPECTED TIMING

Complete this block before `PBI-01-0004.01`.

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

## 2.1 Normative Expected Project Timing Rule

Expected timing is planning information, not a commitment or authorization.

`EXPECTED-PROJECT-DURATION` must state:
* calendar type;
* working-day convention;
* expected working hours where material;
* capacity assumptions;
* dependencies;
* non-working days where material;
* estimating method;
* range;
* confidence/evidence quality;
* source;
* timezone where a date/time boundary matters.

### Interval semantics

PBIM uses the following normative convention:

```text
START = first instant/date interval included in planned execution.
END   = completion boundary after the declared duration under the declared calendar.
```

For date-only schedules, the project must state whether the calendar treats the end date as inclusive or exclusive. For timestamped schedules, use an explicit timezone or UTC offset. Cross-jurisdictional schedules must not rely on an unstated local timezone.

A one-calendar-day interval beginning at `2026-10-10 00:00` therefore completes at the corresponding end boundary under the declared calendar; the document must not silently assume whether `2026-10-10` or `2026-10-11` is the inclusive end date.

Dependency pauses, calendar changes and material capacity changes require recalculation and, where material, change control.

`EXPECTED-PROJECT-END-DATE` is derived from the declared start, duration, calendar, timezone and material dependencies.

If evidence is insufficient:

```text
TBD — Evidence Required: [missing evidence]
Owner: [owner]
Target Date: [date]
Reason: [why calculation is not yet credible]
```

Never manufacture a precise date to make the document look complete.

---

# 3. AUTHORITY, CAPABILITY AND INDEPENDENCE

## 3.1 Human authority

| Code | Generic role | Authority boundary |
|---|---|---|
| `H0` | Human Project Authority | Final project-level decisions, risk acceptance within mandate, resets and PBIM-to-Charter transition |
| `H1` | Delegated Human Governance/Technical Authority | Acts only within recorded delegation |
| `H2` | Authorized Operational/Technical Actor | Performs specifically authorized actions; access does not create governance authority |
| `CA` | Optional Constitutional/Organizational Authority | Use only where the organization actually has such a superior control role |

Technical access never creates governance authority.

## 3.2 Agent capabilities

`LEAD`, `ANL`, `VER`, `SEC`, `IMP`, `TST`, `OPS` and `DOC` are capability roles, not governance authorities.

An agent may not approve its own work merely because it holds a lead or verification capability.

## 3.3 Role-combination rule

For every project:

```text
ACTOR → CAPABILITIES → TECHNICAL PERMISSIONS → GOVERNANCE ROLE
       → DECISION RIGHTS → INDEPENDENCE CLASS → EXPIRY/REVIEW
```

If one actor holds multiple capabilities, record why the combination is acceptable and what independent control remains.

## 3.4 Privileged-account reconciliation

Any privileged technical account must be reconciled before it is treated as governance-capable:

```text
PRIVILEGED ACCOUNT
→ NAMED ACTOR
→ CAPABILITY
→ TECHNICAL PERMISSION
→ GOVERNANCE ROLE
→ AUTHORITY BASIS
→ SCOPE
→ EXPIRY/REVIEW
→ INDEPENDENT CHECK
```

A repository administrator, infrastructure administrator, automation account or equivalent technical principal is not a governance authority merely because it can bypass technical controls.

Unreconciled privileged access is an authority-boundary finding and may require `MANDATORY-STOP` or `RESET-REQUIRED` depending on materiality.

## 3.5 Independence classes

* `I1` organizational independence;
* `I2` evidence independence;
* `I3` technical independence;
* `I4` governance independence.

The required class is determined by materiality and the control being verified.

---

# 4. IDENTIFIER ARCHITECTURE AND CANONICAL REGISTRY

## 4.1 PBIM internal identifiers

PBIM uses:

```text
PBI-[SECTION]-0004.[STEP]
```

Examples:

```text
PBI-01-0004.01
PBI-02-0004.02
...
PBI-09-0004.09
```

`0004.01`–`0004.09` are PBIM internal section coordinates. They are not interchangeable with the formal Charter process coordinate `0004.1`.

```text
[GOV-01-0004.1] = Initiate Project or Phase / Develop Project Charter
```

## 4.2 Identifier taxonomy

Keep separate:
* Project ID;
* PBIM Section ID;
* PM Process Classification ID;
* Prompt ID;
* Requirement ID;
* Work ID;
* Task Packet ID;
* Artifact ID;
* Evidence ID;
* Decision ID;
* Risk ID;
* Finding ID;
* ADR ID;
* Change ID;
* Release ID;
* repository object/commit;
* lifecycle state;
* authorization state.

A PM classification is not an executable workflow instruction unless a separate governance system explicitly makes it one.

## 4.3 Canonical Identifier Registry

Before PBIM can be treated as executable for a project, the project must establish a canonical machine-readable Identifier Registry under the Governance Repository.

Minimum schema:

```yaml
identifier:
identifier_type:
project_id:
pbim_section:
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
integrity_reference:
authority:
```

### Normative uniqueness rule

Unless a more restrictive project rule is approved:

```text
UNIQUE(project_id, identifier_type, identifier)
UNIQUE(project_id, identifier_type, sequence_index) where sequence_index is applicable
```

If the organization instead requires globally unique identifiers across all types, that stricter rule must be recorded in the registry schema.

The registry must reject:
* duplicate composite identifiers;
* duplicate sequence indexes where uniqueness is required;
* invalid grammar;
* missing mandatory fields;
* illegal lifecycle transitions;
* conflicting authoritative revisions.

Machine ordering must use explicit `sequence_index`, never lexical interpretation of numeric strings.

Retired identifiers are tombstoned and never silently reused.

## 4.4 Registry authority and integrity

Allocation follows:

```text
REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM
```

Only the canonical registry transaction establishes authoritative identity.

Conflicting writes cause `REGISTRY-BLOCKED` until resolved and independently verified.

Each revision records revision ID, parent revision where applicable, content hash/integrity reference, change record, accountable actor, timestamp and verification status.

## 4.5 Registry readiness

```text
DESIGNED → SCHEMA-VALIDATED → AUTHORITATIVE → INDEPENDENTLY-VERIFIED
```

A described but uninstantiated registry remains `DESIGNED`.

---

# 5. EVIDENCE, STATE, RISK AND CONTROL MODEL

## 5.1 Evidence classes

Every substantive claim is classified as one or more of:

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

Agent agreement, repetition or confidence scores do not upgrade evidence.

An `UNKNOWN` requires owner, evidence required, target date and advancement consequence.

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

`DESIGNED` means specified. `ENFORCEABLE` means a credible bounded mechanism exists. `ENFORCED` requires evidence that the mechanism acts. `INDEPENDENTLY VERIFIED` requires appropriately independent verification.

## 5.3 Artifact state

```text
DRAFT → ANALYSIS → CONTROLLED CANDIDATE → VERIFICATION
→ APPROVED → SUPERSEDED → ARCHIVED
```

## 5.4 Authorization state

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
REGISTRY-BLOCKED
```

PBIM does not grant production authorization.

## 5.5 Risk profiles

| Profile | Typical use | Minimum posture |
|---|---|---|
| `LIGHT` | Low-consequence, bounded, reversible work | Core authority, evidence, traceability, stop and verification controls; reduced ceremony elsewhere |
| `STANDARD` | Ordinary project work | Material controls, independent review where required, change and recovery controls |
| `HIGH-ASSURANCE` | Safety, financial, security, regulated, sensitive-data, irreversible or high-blast-radius work | Stronger evidence, independence, challenge, mechanical enforcement and operational verification |

Tailoring may reduce ceremony but may not remove protected controls.

## 5.6 Materiality escalation

If a change increases materiality after work begins:

```text
DETECT MATERIALITY INCREASE
→ FREEZE AFFECTED ADVANCEMENT
→ RECLASSIFY RISK
→ RE-RUN AFFECTED ASSURANCE
→ OBTAIN REQUIRED AUTHORITY
→ UPDATE BASELINE
→ RESUME ONLY AFTER CONTROLLED RELEASE
```

A materiality increase cannot be neutralized by splitting the change into individually small tasks.

## 5.7 Stop states

```text
S0 RUNNING
S1 ADVISORY-STOP
S2 MANDATORY-STOP
S3 SYSTEM-STOP
S4 EMERGENCY-SAFETY-STOP
```

Every stop records trigger, timestamp, affected scope, authority, evidence, disposition and resume criteria.

The executor cannot self-clear a stop.

## 5.8 Architectural reset

Reset is required when a load-bearing premise fails, including foundational requirement failure, authority-boundary failure, security-model failure, evidence-integrity failure, identifier-model failure, material architecture failure or unresolvable assurance failure.

```text
IDENTIFY FAILURE
→ FREEZE AFFECTED WORK
→ PRESERVE EVIDENCE
→ IDENTIFY RESET AUTHORITY
→ RETURN TO EARLIEST AFFECTED STAGE
→ REVISE
→ RE-ASSURE
```

## 5.9 Emergency delegation

Emergency delegation is an exception, not a permanent bypass.

Where H0 is unavailable and emergency delegation is permitted:

```text
MAXIMUM TTL = 72 HOURS
```

The delegation record must contain:
* authority;
* delegate;
* scope;
* permitted action;
* prohibited action;
* start time;
* expiry time;
* evidence requirements;
* reconciliation requirements;
* H0 confirmation requirement.

If H0 does not confirm before the 72-hour TTL expires:

```text
AUTOMATIC STATE → STOPPED
```

The expired delegation must not silently renew itself. Any extension requires a new recorded authority decision under the applicable governance regime.

---

# 6. DURABLE REFERENCES, SECURITY AND MODERN PRACTICE

## 6.1 Critical durable reference rule

For authoritative or critical evidence, the minimum durable reference grammar is:

```text
ARTIFACT-ID
@
IMMUTABLE COMMIT / TAG OBJECT / IMMUTABLE OBJECT ID
+
INTEGRITY-HASH
```

For critical repository evidence, the reference must include a commit SHA-256 hash **or** an immutable tag/object ID, alongside any symbolic branch/tag name when a symbolic name is useful.

Example:

```text
pbi-baseline/v3.01.08
@ <immutable-object-or-commit-id>
INTEGRITY-SHA256: <sha256>
```

A mutable branch URL alone is never sufficient authority for a critical claim.

A symbolic tag alone is insufficient where the assurance record requires object identity.

## 6.2 Evidence provenance

Each critical evidence item records:

```text
EVIDENCE-ID
SOURCE
CANONICAL-LOCATION
IMMUTABLE-IDENTITY
INTEGRITY-REFERENCE
CAPTURE-TIME
CAPTURE-ACTOR
EVIDENCE-CLASS
AUTHORITY-BASIS
```

## 6.3 Security and privacy

Where applicable assess data classification, privacy obligations, secrets management, least privilege, separation of duties, privileged access, supply-chain risk, secure development, logging/auditability, vulnerability management, backup/recovery, incident response, resilience, secure release and retention/deletion.

Never place credentials, authentication tokens or secrets in PBIM prompts or ordinary evidence artifacts.

## 6.4 AI-enabled work

Where AI systems or agents are involved assess system role, authority boundary, data exposure, instruction precedence, tool permissions, output verification, provenance, model/dependency changes, adversarial inputs, human oversight, misuse, privacy, security and operational fallback.

ISO/IEC 42001:2023 may be considered where AI governance is in scope; it is not automatically binding.

NIST AI RMF remains a voluntary reference unless adopted by the governing organization.

## 6.5 Standards currency

External standards references must record:
* title;
* edition/version;
* status;
* review date;
* authoritative source;
* applicability;
* whether binding or advisory.

At the 2026-10-08 review:
* PMI identifies the **PMBOK Guide — Eighth Edition**, published November 2025, as current. Source: `https://www.pmi.org/standards/pmbok`
* **ISO 21502:2020** remains a published International Standard; ISO/CD 21502 Edition 2 is under development and must not be represented as a published standard. Sources: `https://www.iso.org/standard/74947.html` and `https://committee.iso.org/standard/95368.html`
* **ISO 31000:2018** remains current following confirmation; source: `https://www.iso.org/standard/65694.html`
* **ISO/IEC 27001:2022** remains a published reference, subject to applicable amendments and organizational adoption.
* **NIST AI RMF 1.0** remains a voluntary reference.
* OWASP ASVS 5.0.0 may be used where application-security verification is in scope.

Transient tool-session citations, chat citation tokens and temporary search identifiers are not durable PBIM references.

---

# 7. UNIVERSAL PROMPT ENGINEERING CONTRACT

Every operational PBIM prompt must use:

```text
<<START Prompt N. [Prompt Label]>>
[Designation: ...]

ROLE
...

OBJECTIVE
...

CONTEXT
...

INSTRUCTIONS
...

<<START [Resource Label]>>
...
<<STOP [Resource Label]>>

Note the following:
...

OUTPUT
...

STOP CONDITIONS
...

DECISION SET
...

<<STOP Prompt N. [Prompt Label]>>
```

Mandatory rules:

1. Exactly one matching prompt start/end pair.
2. Designation is explicit.
3. Resources are inside named resource blocks.
4. Notes appear below resource blocks when present.
5. Missing facts are `UNKNOWN`.
6. Findings include evidence and materiality.
7. Conditions include owner, evidence requirement and advancement consequence.
8. Output and stop conditions are explicit.
9. Every decision-producing prompt contains its actual decision vocabulary.
10. A collaborating agent cannot approve its own work.
11. Lead synthesis preserves minority findings and unresolved dissent.
12. Prompt instructions cannot create implementation or production authority.
13. Prompt scope is not a substitute for technical enforcement.
14. Real-world modifications require a Task Packet with authorized targets and mechanical enforcement.
15. A post-PBIM prompt must not be presented as a PBIM operational gate.
16. Resource integrity must be sufficient for the claim being made.

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
```

## 7.2 Prompt integrity check

Before use verify:
* exactly one prompt start marker and matching end marker;
* balanced resource markers;
* no resource block contains the prompt end marker;
* placeholders are resolvable or explicitly `UNKNOWN`;
* resource labels match referenced evidence;
* decision authority is not inferred from capability;
* no secret is embedded;
* decision vocabulary is explicit.

---

# 8. TASK PACKET CONTROL AND MECHANICAL ENFORCEMENT

A Task Packet is the controlled unit of executable work.

Minimum fields:

```text
project_id
task_id
lifecycle_stage
objective
scope
exclusions
authoritative_references
source_files
authorized_paths
requirements
constraints
assigned_actor
permitted_actions
prohibited_actions
expected_deliverables
verification_requirements
acceptance_criteria
output_location
branch_or_worktree_requirement
logging_requirement
dependencies
stop_conditions
escalation_conditions
authorization_reference
```

## 8.1 Required mechanical scope workflow

Where repository/file scope is enforced, the normative CI workflow specification is:

```text
.github/workflows/task-scope-check.yml
```

The workflow must compare the actual changeset/diff against the Task Packet scope manifest, including `authorized_paths` and protected-resource rules.

At minimum it must detect:
* changes outside authorized paths;
* unauthorized protected-resource changes;
* missing authorization reference;
* stale or superseded Task Packet state;
* inconsistent task identity.

Equivalent mechanical enforcement may be used only if it provides materially equivalent protection and is recorded as the project’s approved implementation.

**Important evidence boundary:** naming this workflow establishes a normative specification requirement. It does not prove that the workflow exists or operates in a given repository.

If the mechanical control is absent, state remains `DESIGNED` or `ENFORCEABLE`, not `ENFORCED`.

## 8.2 Evidence-log protection

Where logs may contain secrets or sensitive data, define protected patterns/categories, execution point, failure behavior and verification method before claiming evidence integrity.

A failed redaction control causes the applicable evidence path to stop and may require a security incident process.

---

# 9. TRACEABILITY AND DECISION CONTROL

PBIM must support:

```text
Requirement
 → Analysis
 → Architectural Decision
 → Verification
 → Task Packet / Implementation
 → Test
 → Approval / Authorization
 → Release
```

A formal RTM is conditional on risk and complexity, but equivalent traceability is required where implementation decisions need durable justification.

## 9.1 ADR vs Decision Ledger

**ADR:** durable architectural decision, context, alternatives, rationale, consequences and decision owner.

**Decision Ledger:** chronological operational/governance decisions, open decisions, conditions, approvals, changes, status and disposition.

They may share infrastructure only if their functions remain distinguishable and auditable.

---

# 10. ASSURANCE PROTOCOL

The reusable assurance cycle is:

```text
AEA → AEV → AEC → AECC
```

* AEA — architectural analysis;
* AEV — independent verification against evidence and controls;
* AEC — adversarial challenge intended to break the design;
* AECC — closure and formal disposition.

Consensus is not verification. Verification is not authorization. Human authority owns governance decisions.

## 10.1 Blocking findings

A material finding blocks progression where it affects:
* a protected control;
* a load-bearing premise;
* mandatory legal/security/safety requirements;
* authority boundaries;
* evidence integrity;
* identifier integrity; or
* required transition criteria.

## 10.2 Dissent

Every material dissent is preserved with author/role, evidence, issue, affected decision and disposition. Majority agreement cannot erase a minority finding.

---

# 11. PBIM LIFECYCLE

## [PBI-01-0004.01] PBIM Document Creation & Baseline Initialization

Establish identity, authority context, source-of-truth references, risk profile, timing expectations and baseline state.

**Gate G1:** identity and authority context established; missing material facts explicitly registered.

## [PBI-02-0004.02] PBIM Architectural Assurance

Subject the PBIM generic candidate to AEA → AEV → AEC → AECC.

**Gate G2:** material architectural findings resolved or formally dispositioned.

## [PBI-03-0004.03] Project Context & Proposal Definition

Develop the project-specific proposal from the approved generic baseline.

**Gate G3:** context and proposal evidence are sufficient.

## [PBI-04-0004.04] Project Proposal Engineering & Verification

Analyze, verify and challenge the proposal.

**Gate G4:** proposal is coherent, evidence-backed and materially dispositioned.

## [PBI-05-0004.05] Project Template Assembly

Translate the verified proposal into a proportionate project operating model.

**Gate G5:** required controls are identified, owned and risk-scaled.

## [PBI-06-0004.06] Project Template Engineering & Verification

Engineer and independently verify the template.

**Gate G6:** protected controls are sufficiently complete and enforceable by design.

## [PBI-07-0004.07] Project Configuration & Governance Initialization

Instantiate only approved governance configuration.

**Gate G7:** initialized framework verifies against approved configuration and authority boundaries.

## [PBI-08-0004.08] Project Simulation & Readiness Review

Exercise normal and adverse scenarios before Charter transition.

Minimum scenario families:
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

**Gate G8:** evidence demonstrates control behavior, not merely document presence.

## [PBI-09-0004.09] PBIM Activation & Charter Readiness

Assemble the final pre-Charter package and obtain the authorized human decision.

**Gate G9:** only H0 or a formally recorded H0 delegate may authorize PBIM-to-Charter transition.

---

# 12. CONFIGURATION AND INITIALIZATION CONTROLS

Configuration planning distinguishes:

```text
DESIGN
→ AUTHORIZED CONFIGURATION
→ EXECUTION
→ OBSERVED RESULT
→ INDEPENDENT VERIFICATION
```

For every configuration item record purpose, owner, authority, permission, intended state, verification method, evidence, rollback/recovery and stop condition.

Configuration must not create authority that was not already granted.

Human Authority Register, Authority–Permission Matrix and Identifier Registry are controlled governance artifacts. Ephemeral chat/local copies are not authoritative.

---

# 13. PROJECT SIMULATION AND READINESS

Simulation may be tabletop, dry-run or controlled non-production execution, proportionate to risk.

Each scenario records:

```text
Scenario
Preconditions
Trigger
Expected Control Behavior
Observed Behavior
Evidence
Deviation
Stop State
Recovery
Residual Risk
Disposition
```

Simulation cannot be represented as production implementation or production proof.

Readiness review must challenge false-positive tests, incomplete scenarios, stop enforcement, authority conflicts, evidence integrity, recovery, human unavailability and emergency delegation expiry.

---

# 14. CHANGE CONTROL

Once a controlled baseline exists:

```text
Change Request
→ Impact Analysis
→ Materiality Classification
→ Architectural Review where required
→ Decision / Authorization
→ Baseline Update
→ Implementation
→ Verification
→ Release
```

A material change that invalidates an assured premise requires re-assurance from the earliest affected stage.

---

# 15. PROTECTED CONTROLS AND MINIMUM PROJECT POSTURE

Every project requires, as applicable:
* project identity;
* authoritative source definition;
* authority definition;
* requirements and constraints;
* evidence classification;
* baseline verification;
* challenge before implementation where implementation is in scope;
* traceability sufficient to explain material work;
* suitable source control;
* stop conditions;
* implementation verification where implementation occurs;
* appropriate human authorization;
* critical durable-reference integrity;
* privileged-account reconciliation;
* emergency-delegation expiry enforcement.

Risk tailoring may reduce ceremony, not protected controls.

---

# 16. AUTHORITY–PERMISSION MATRIX

| Action | Capability | Permission | Governance Authority | Independent Check | Evidence |
|---|---|---|---|---|---|
| Analyze | ANL/LEAD | Analyze source | None by capability | Where required | Analysis record |
| Draft | LEAD/DOC | Write designated artifact | None by capability | Review as required | Artifact revision |
| Modify code | IMP | Authorized Task Packet paths | H1/H2 as delegated | TST/VER as required | Commit/test evidence |
| Change governance baseline | LEAD/DOC | Controlled repository write | H0/H1 as delegated | Independent verification | Decision + diff |
| Release | Release/OPS | Release permission | Explicit release authority | Independent release check | Release evidence |
| Production action | OPS/technical | Explicit production permission | Explicit human authority | Required operational control | Audit evidence |
| Approve own work | Any | **Prohibited** | N/A | N/A | Stop/finding |

Actual project mappings must be recorded before the relevant operation.

---

# 17. PROMPT REGISTER

Each operational prompt receives:

```text
prompt_id
prompt_revision
project_id
pbim_section
purpose
owner
role/designation
resource_set
required_outputs
decision_set
source_references
integrity_reference
status
supersedes
```

Prompt IDs are distinct from PBIM section IDs.

Prompts are not immutable security controls unless an external enforcement mechanism exists.

---

# 18. GATE REGISTER

| Gate | Required condition | Minimum evidence | Blocking examples |
|---|---|---|---|
| G1 | Identity/authority context | Identity + authority register | Missing authority |
| G2 | PBIM assurance closure | AEA/AEV/AEC/AECC package | Load-bearing architecture defect |
| G3 | Proposal readiness | Evidence-classified proposal | Material unknown hidden as fact |
| G4 | Proposal assurance | Verification/challenge records | Unresolved material contradiction |
| G5 | Template assembly | Control-to-source map | Unowned mandatory control |
| G6 | Template assurance | Verification matrix | Unenforceable protected control |
| G7 | Configuration verification | Configuration evidence | Authority/permission drift |
| G8 | Simulation readiness | Scenario/evidence package | Stop/recovery failure |
| G9 | Charter readiness | Final package + independent review | Blocking finding or missing H0 authorization |

No aggregate score can override a blocking condition.

---

# 19. REQUIRED REGISTERS AND CANONICAL ARTIFACTS

As applicable:
* Project Identity Record;
* Human Authority Register;
* Authority–Permission Matrix;
* Identifier Registry;
* Requirements/Constraints Register;
* Risk Register;
* Decision Ledger;
* ADR set where warranted;
* Evidence Index;
* Finding Register;
* Task Packet Register;
* Change Register;
* Prompt Register;
* Verification Records;
* Challenge Records;
* Simulation/Readiness Records;
* Configuration Manifest;
* Release/Deployment evidence for later project phases.

Each controlled register has one canonical authoritative location and a defined revision/integrity mechanism.

---

# 20. STANDARDIZED PROMPT SET

PBIM defines 25 reusable prompt instances. Prompts 1–24 belong to the operational PBIM lifecycle. Prompt 25 is a **post-PBIM Charter-stage handoff artifact** and is not an additional PBIM gate.

## Prompt 1 — PBIM Generic Baseline Synthesis

<<START Prompt 1. PBIM Generic Baseline Synthesis>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Synthesize the generic PBIM baseline from authoritative source material and assurance evidence.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START PBIM Reference Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Reference Package>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 1. PBIM Generic Baseline Synthesis>>
## Prompt 2 — PBIM Baseline Independent Review

<<START Prompt 2. PBIM Baseline Independent Review>>
[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently identify substantive architectural, governance, engineering, identifier and prompt defects.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START PBIM Candidate + PBIM Source Set>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + PBIM Source Set>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 2. PBIM Baseline Independent Review>>
## Prompt 3 — PBIM Architectural Engineering Analysis

<<START Prompt 3. PBIM Architectural Engineering Analysis>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Analyze whether the PBIM architecture is coherent, governable, evidence-based, risk-scaled and generic.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START PBIM Candidate + Applicable Standards and Governance References>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + Applicable Standards and Governance References>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 3. PBIM Architectural Engineering Analysis>>
## Prompt 4 — PBIM Architectural Engineering Verification

<<START Prompt 4. PBIM Architectural Engineering Verification>>
[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Verify material controls against authority, ownership, evidence, transition, failure and acceptance criteria.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START AEA Package + PBIM Candidate>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA Package + PBIM Candidate>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 4. PBIM Architectural Engineering Verification>>
## Prompt 5 — PBIM AEV Decisions and Adversarial Challenge Preparation

<<START Prompt 5. PBIM AEV Decisions and Adversarial Challenge Preparation>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Consolidate AEV decisions and prepare a bounded adversarial challenge package.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START AEV Decisions + Current AEV Statement>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV Decisions + Current AEV Statement>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
PREPARED / PREPARED WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 5. PBIM AEV Decisions and Adversarial Challenge Preparation>>
## Prompt 6 — PBIM Architectural Engineering Challenge

<<START Prompt 6. PBIM Architectural Engineering Challenge>>
[Designation: Collaborating Agents / Challenge Agents]

ROLE
Collaborating Agents / Challenge Agents.

OBJECTIVE
Attempt to break the PBIM architecture rather than defend it.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Attempt falsification, not confirmation. Do not convert a challenge result into authority.

<<START AEV-Approved Candidate + Existing AEC Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV-Approved Candidate + Existing AEC Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
AEC PASS / AEC PASS WITH AMENDMENTS / AEC FAIL / AEC BLOCKED

<<STOP Prompt 6. PBIM Architectural Engineering Challenge>>
## Prompt 7 — PBIM AEC Results and Closure Preparation

<<START Prompt 7. PBIM AEC Results and Closure Preparation>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Reconcile assurance findings without hiding material dissent.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START AEC Results + AEV Statement and Decisions>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEC Results + AEV Statement and Decisions>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CLOSURE-READY / CLOSURE-READY WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 7. PBIM AEC Results and Closure Preparation>>
## Prompt 8 — PBIM Architectural Engineering Challenge Closure

<<START Prompt 8. PBIM Architectural Engineering Challenge Closure>>
[Designation: Lead Agent / Authorized Assurance Authority]

ROLE
Lead Agent / Authorized Assurance Authority.

OBJECTIVE
Determine whether the architecture assurance cycle can be formally closed.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START AEA/AEV/AEC/Amendment Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA/AEV/AEC/Amendment Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CLOSE / CLOSE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 8. PBIM Architectural Engineering Challenge Closure>>
## Prompt 9 — Project Context and Proposal Definition

<<START Prompt 9. Project Context and Proposal Definition>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Develop an evidence-classified project proposal from the approved PBIM baseline.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
ACCEPT / ACCEPT WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 9. Project Context and Proposal Definition>>
## Prompt 10 — Project Context Independent Review

<<START Prompt 10. Project Context Independent Review>>
[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently review the initial project proposal.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Project Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Proposal + PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 10. Project Context Independent Review>>
## Prompt 11 — Project Proposal AEA

<<START Prompt 11. Project Proposal AEA>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Analyze proposal coherence, feasibility, evidence and material risk.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Controlled Project Proposal + Supporting Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Controlled Project Proposal + Supporting Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 11. Project Proposal AEA>>
## Prompt 12 — Project Proposal AEV and AEC Verification

<<START Prompt 12. Project Proposal AEV and AEC Verification>>
[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Verify and challenge the project proposal.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Proposal AEA Package + Current Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal AEA Package + Current Proposal + PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / AEC BLOCKED / RETURN / BLOCKED

<<STOP Prompt 12. Project Proposal AEV and AEC Verification>>
## Prompt 13 — Project Proposal AECC Closure and Baseline

<<START Prompt 13. Project Proposal AECC Closure and Baseline>>
[Designation: Lead Agent / Authorized Human Closure Authority]

ROLE
Lead Agent / Authorized Human Closure Authority.

OBJECTIVE
Close the proposal assurance cycle and establish its controlled baseline.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Proposal Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal Assurance Package + Decisions + Corrective Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
BASELINE / BASELINE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 13. Project Proposal AECC Closure and Baseline>>
## Prompt 14 — Project Template Assembly

<<START Prompt 14. Project Template Assembly>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Assemble a proportionate project operating template.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Verified Project Proposal + Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Verified Project Proposal + Approved PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
DRAFTED / DRAFTED WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 14. Project Template Assembly>>
## Prompt 15 — Project Template Completeness Review

<<START Prompt 15. Project Template Completeness Review>>
[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently identify missing, excessive, contradictory or unenforceable template controls.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Project Template + Verified Proposal>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Verified Proposal>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 15. Project Template Completeness Review>>
## Prompt 16 — Project Template Engineering, Verification and Challenge

<<START Prompt 16. Project Template Engineering, Verification and Challenge>>
[Designation: Lead Agent / Collaborating Agents]

ROLE
Lead Agent / Collaborating Agents.

OBJECTIVE
Engineer, verify and challenge the project template.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Project Template + Requirements + Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Requirements + Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / AEC BLOCKED / RETURN / BLOCKED

<<STOP Prompt 16. Project Template Engineering, Verification and Challenge>>
## Prompt 17 — Project Template AECC Closure and Baseline

<<START Prompt 17. Project Template AECC Closure and Baseline>>
[Designation: Lead Agent / Authorized Assurance Authority]

ROLE
Lead Agent / Authorized Assurance Authority.

OBJECTIVE
Close the project-template assurance cycle.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Template Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Template Assurance Package + Decisions + Corrective Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
BASELINE / BASELINE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 17. Project Template AECC Closure and Baseline>>
## Prompt 18 — Project Governance Configuration and Initialization

<<START Prompt 18. Project Governance Configuration and Initialization>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Instantiate only the approved governance configuration within explicit authority.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Approved Project Template + Authority/Identifier Resources + Repository References>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Project Template + Authority/Identifier Resources + Repository References>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
INITIALIZE / INITIALIZE WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 18. Project Governance Configuration and Initialization>>
## Prompt 19 — Project Governance Configuration Verification

<<START Prompt 19. Project Governance Configuration Verification>>
[Designation: Collaborating Agents / Verification and Operations Roles]

ROLE
Collaborating Agents / Verification and Operations Roles.

OBJECTIVE
Independently verify the initialized governance environment.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Approved Configuration Plan + Initialized Governance Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Configuration Plan + Initialized Governance Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 19. Project Governance Configuration Verification>>
## Prompt 20 — PBIM Project Simulation and Readiness Exercise

<<START Prompt 20. PBIM Project Simulation and Readiness Exercise>>
[Designation: Lead Agent / Collaborating Assurance Agents]

ROLE
Lead Agent / Collaborating Assurance Agents.

OBJECTIVE
Exercise the configured governance framework under normal and adverse scenarios.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START Initialized Project Framework + Task Packet/Control Model>>
{{DURABLE-RESOURCE-SET}}
<<STOP Initialized Project Framework + Task Packet/Control Model>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
READY FOR CHALLENGE / READY WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 20. PBIM Project Simulation and Readiness Exercise>>
## Prompt 21 — PBIM Readiness Independent Challenge

<<START Prompt 21. PBIM Readiness Independent Challenge>>
[Designation: Collaborating Agents / Challenge Agents]

ROLE
Collaborating Agents / Challenge Agents.

OBJECTIVE
Challenge whether simulation evidence demonstrates actual control behavior.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Simulation Results + Readiness Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Simulation Results + Readiness Evidence>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 21. PBIM Readiness Independent Challenge>>
## Prompt 22 — PBIM Activation and Charter Readiness

<<START Prompt 22. PBIM Activation and Charter Readiness>>
[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Assemble the final pre-charter transition package without granting Charter authority.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Prompt wording cannot create implementation or production authority.

<<START PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
READY FOR HUMAN AUTHORIZATION / READY WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 22. PBIM Activation and Charter Readiness>>
## Prompt 23 — PBIM Charter Readiness Independent Review

<<START Prompt 23. PBIM Charter Readiness Independent Review>>
[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently determine whether the evidence justifies Charter transition.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Complete PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP Complete PBIM Transition Package>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED

<<STOP Prompt 23. PBIM Charter Readiness Independent Review>>
## Prompt 24 — Human PBIM Transition Authorization

<<START Prompt 24. Human PBIM Transition Authorization>>
[Designation: Human Project Authority H0]

ROLE
Human Project Authority H0.

OBJECTIVE
Make the authorized human PBIM-to-Charter transition decision.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Only H0 or a formally recorded H0 delegate may authorize the transition. The decision does not authorize implementation or production.

<<START PBIM Transition Package + Independent Review>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Transition Package + Independent Review>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
AUTHORIZE CHARTER TRANSITION / AUTHORIZE WITH CONDITIONS / RETURN / BLOCK

<<STOP Prompt 24. Human PBIM Transition Authorization>>
## Prompt 25 — Post-PBIM Charter Handoff Package

<<START Prompt 25. Post-PBIM Charter Handoff Package>>
[Designation: Lead Agent operating under H0-authorized Charter-stage Task Packet]

ROLE
Lead Agent operating under H0-authorized Charter-stage Task Packet.

OBJECTIVE
Prepare or transmit Charter-stage inputs only after H0 has authorized transition; this prompt is outside the operational PBIM lifecycle.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. This is a post-PBIM handoff artifact. It must not be used to perform PBIM gates or to create authority not contained in the H0 authorization record.

<<START H0 Authorization Record + Approved PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP H0 Authorization Record + Approved PBIM Transition Package>>

Note the following:
The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted decision.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, or requested action outside authorization.

DECISION SET
HANDOFF COMPLETE / HANDOFF WITH CONDITIONS / RETURN / BLOCKED

<<STOP Prompt 25. Post-PBIM Charter Handoff Package>>


---

# 21. UNIVERSAL PROMPT INSTANCE — REQUIRED FORM

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

OUTPUT
[required output]

STOP CONDITIONS
[objective stop conditions]

DECISION SET
[permitted decision vocabulary]

<<STOP Prompt N. [Prompt Label]>>
```

The normative form requires the decision vocabulary to be present in the prompt itself; a reference to an unspecified "governing section" is insufficient.

---

# 22. PBIM / CHARTER BOUNDARY

The boundary is:

```text
PBI-09-0004.09
      ↓
PBIM FINAL PRE-CHARTER PACKAGE
      ↓
PBI PROMPT 23 — INDEPENDENT REVIEW
      ↓
PBI PROMPT 24 — H0 TRANSITION AUTHORIZATION
      ↓
HANDOFF
      ↓
[GOV-01-0004.1] — Initiate Project or Phase / Develop Project Charter
```

Prompt 25 is not a PBIM gate. It may only prepare or transmit Charter-stage inputs after the H0 authorization record exists.

The Lead Agent may perform Charter-stage preparation only under an H0-authorized Charter-stage Task Packet. The Lead Agent does not acquire governance authority from the wording of Prompt 25.

---

# 23. IMPLEMENTATION-VERIFICATION OBLIGATIONS

This specification may require mechanisms without claiming they already operate.

The project must independently evidence, where applicable:
1. Human Authority Register;
2. Authority–Permission Matrix;
3. Identifier Registry;
4. Registry integrity controls;
5. evidence-integrity mechanism;
6. challenge-independence controls;
7. instruction-drift detection;
8. Task Packet scope enforcement;
9. `.github/workflows/task-scope-check.yml` or approved equivalent;
10. stop/reset enforcement;
11. repository/branch protections;
12. secret/security controls;
13. operational-readiness gate;
14. durable-reference controls;
15. privileged-account reconciliation;
16. emergency delegation TTL enforcement;
17. audit/evidence retention.

A design amendment is not closure evidence for an implementation finding.

---

# 24. CLOSURE MATRIX FOR v3.01.07 INDEPENDENT REVIEW

| Finding | v3.01.08 disposition | Closure state |
|---|---|---|
| MF-01 durable reference SHA-256/object ID | Critical reference grammar now requires immutable identity plus SHA-256/object-ID evidence | **Designed closure** |
| MF-02 Task Packet CI workflow | Exact normative path `.github/workflows/task-scope-check.yml` specified; existence remains implementation evidence | **Designed closure / implementation unproven** |
| MF-03 emergency delegation TTL | Hard 72-hour maximum; automatic `STOPPED` fallback | **Designed closure** |
| Prompt decision sets | Every prompt now contains explicit permitted vocabulary | **Closed in specification** |
| PBIM/Charter boundary | Prompt 25 explicitly post-PBIM; H0 authorization precedes handoff | **Closed in specification** |
| H0 vs Lead Agent role ambiguity | Prompt 24 is H0-only; Prompt 25 operates under H0-authorized Task Packet | **Closed in specification** |
| Identifier uniqueness | Composite uniqueness rule explicitly defined | **Closed in specification** |
| Timing semantics | Interval, calendar, inclusive/exclusive and timezone rules added | **Closed in specification** |
| Privileged-account reconciliation | Explicit account-to-authority reconciliation added | **Closed in specification** |
| Consolidation overstatement | Register now distinguishes standardized controls from prompt-specific instances | **Closed in specification** |
| Standards evidence durability | Durable authoritative references replace transient tool-session citation dependence | **Closed in specification** |
| Risk escalation | Mandatory freeze/reclassify/re-assure path added | **Closed in specification** |

---

# 25. EVIDENCE LIMITATIONS AND PRESERVED DISSENT

1. This document remains `DESIGNED`.
2. Naming `.github/workflows/task-scope-check.yml` does not prove that the workflow exists or operates.
3. Naming a 72-hour TTL does not prove an enforcement mechanism exists.
4. A SHA-256/object-ID requirement does not prove existing evidence contains compliant hashes.
5. Historical AEA/AEV/AEC conclusions are evidence about prior review, not proof of current operation.
6. Independent agents may agree while sharing flawed evidence; agreement does not constitute verification.
7. The source lineage contains historical architecture and project-specific material; historical content is not automatically current normative authority.
8. Dissent and unresolved implementation obligations must remain visible until independently evidenced.

---

# 26. REQUIRED FINAL READINESS QUESTIONS

Before H0 transition authorization:

1. Is project identity controlled?
2. Is the human authority identified?
3. Are authority and capability separated?
4. Are privileged technical accounts reconciled?
5. Is the risk profile defined?
6. Are timing values estimates with explicit calendar/interval/timezone semantics?
7. Is the PBIM baseline traceable to immutable evidence?
8. Is the identifier registry authoritative and uniquely constrained?
9. Are material claims evidence-classified?
10. Is the proposal verified?
11. Has the proposal been challenged?
12. Is the template internally coherent?
13. Are Task Packets bounded?
14. Is mechanical scope enforcement implemented where claimed?
15. Are security/privacy boundaries defined?
16. Can the project stop safely?
17. Can it reset safely?
18. Is emergency authority bounded by the 72-hour TTL?
19. Has the framework been initialized?
20. Has it been simulated?
21. Has readiness been independently challenged?
22. Are material blockers resolved or formally escalated?
23. Are Charter inputs traceable to PBIM artifacts?
24. Is H0 prepared to authorize formal Charter transition?

If any mandatory answer is unresolved:

```text
DO NOT ADVANCE TO [GOV-01-0004.1]
```

unless the matter is explicitly classified and authorized as a Charter-stage input rather than a pre-Charter blocker.

---

# 27. DECISION AND RELEASE STATUS

This v3.01.08 revision is a targeted closure revision following the independent baseline review.

It **does not claim operational closure** of the AEC material findings merely because the specification now contains their required controls.

The correct state is:

```text
ARTIFACT STATE:
CONTROLLED CANDIDATE

CONTROL MATURITY:
DESIGNED

IMPLEMENTATION:
NOT PROVEN

PRODUCTION AUTHORIZATION:
NOT GRANTED

PBIM DECISION:
APPROVE WITH CONDITIONS
```

Conditions for progression beyond the designed specification:

1. independently verify the actual durable-reference implementation;
2. verify or implement `.github/workflows/task-scope-check.yml` or an approved equivalent;
3. demonstrate automated 72-hour emergency-delegation expiry;
4. instantiate and independently verify the canonical Identifier Registry;
5. reconcile privileged technical accounts against governance authority;
6. preserve immutable evidence for the approved baseline.

---

# 28. CHANGE HISTORY

| Version | Change |
|---|---|
| v3.01.07 | Controlled generic candidate following prior consolidation and review |
| **v3.01.08** | Independent-review closure revision: durable-reference identity/hash rule; named Task Packet CI workflow; 72-hour emergency TTL; explicit prompt decision sets; PBIM/Charter boundary correction; identifier uniqueness; timing interval semantics; privileged-account reconciliation; risk-escalation controls; durable standards references; consolidation-claim correction |

---

# 29. AUTHORITATIVE REFERENCE REGISTER

| Reference | Status / use |
|---|---|
| PMBOK Guide — Eighth Edition | Advisory project-management reference; PMI current edition as of 2026-10-08 |
| ISO 21502:2020 | Published project-management guidance |
| ISO/CD 21502 Edition 2 | Committee draft; never treat as a published standard |
| ISO 31000:2018 | Current risk-management guidance as reviewed/confirmed by ISO |
| ISO/IEC 27001:2022 | Security-management reference where adopted/applicable |
| ISO/IEC 42001:2023 | AI-management reference where AI governance is in scope |
| NIST AI RMF 1.0 | Voluntary AI-risk reference unless adopted |
| OWASP ASVS 5.0.0 | Application-security verification reference where applicable |

Authoritative external references:

```text
PMI:
https://www.pmi.org/standards/pmbok

ISO 21502:2020:
https://www.iso.org/standard/74947.html

ISO/CD 21502:
https://committee.iso.org/standard/95368.html

ISO 31000:2018:
https://www.iso.org/standard/65694.html
```

---

# 30. FINAL CONTROL PRINCIPLE

PBIM is not made durable by being comprehensive.

It is durable only when:

```text
IDENTITY IS UNAMBIGUOUS
+
AUTHORITY IS EXPLICIT
+
CAPABILITY IS SEPARATE
+
EVIDENCE IS DURABLE
+
SCOPE IS MECHANICALLY BOUNDED WHERE CLAIMED
+
RISK ESCALATES WITH MATERIALITY
+
STOP/RESET STATES ARE ENFORCEABLE
+
EMERGENCY AUTHORITY EXPIRES
+
PROMPTS HAVE EXPLICIT DECISION VOCABULARY
+
THE PBIM/CHARTER BOUNDARY IS UNAMBIGUOUS
+
IMPLEMENTATION CLAIMS ARE SUPPORTED BY IMPLEMENTATION EVIDENCE
+
DISSENT REMAINS VISIBLE
```

**End of PBIM Generic Edition v3.01.08.**
