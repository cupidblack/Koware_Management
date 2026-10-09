# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
|---|---|
| Document | Project Base Integration Manager — Generic Edition |
| Version | **v3.01.07** |
| Document Class | Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework |
| Status | **CONTROLLED GENERIC CANDIDATE — AMENDED AFTER INDEPENDENT REVIEW** |
| Maturity | `DESIGNED` — this document is a specification; it is not evidence that controls operate |
| Scope | Pre-charter probing, project-framework design, controlled initialization and readiness only |
| PBIM terminal boundary | `[PROJECT-KEY][GOV-01-0004.1] — Initiate Project or Phase / Develop Project Charter` |
| PBIM Document Creation identifier | `[PROJECT-KEY][PBI-01-0004.01]` |
| Expected timing fields | `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` |
| Implementation authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Production authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Genericity | Technology-, vendor-, repository-, organization-, product- and project-neutral |
| Patch basis | v3.01.06 + independent baseline review amendments |

> **Important:** This revision is a specification. A statement that a control exists in this document does not prove that the control is deployed, enforceable or operating.

---

# 0. READER'S GUIDE

## 0.1 Purpose

PBIM is a reusable pre-charter framework for moving an initiative from an identified need, opportunity or request toward a controlled, evidence-backed and Charter-ready state.

PBIM establishes proportionate governance, context, proposal definition, project-template design, configuration planning, simulation and readiness evidence. It does not replace the organization's ordinary project governance after the PBIM boundary.

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

PBIM ends when the authorized human project authority permits transition into the formal Charter process at `[GOV-01-0004.1]`.

## 0.2 Operating principles

1. Authority defines who may decide; capability defines what an actor can do.
2. Evidence defines what may be claimed; documentation alone does not establish operation.
3. A classification identifier does not become a workflow command merely because it looks sequential.
4. A prompt is an instruction artifact, not a security boundary by itself.
5. Protected controls must have an enforceable mechanism where enforcement is claimed.
6. Independent challenge preserves dissent; agreement does not constitute proof.
7. Risk scaling may reduce ceremony but may not remove protected legal, security, safety, evidence-integrity or authority controls.
8. Unknown facts remain `UNKNOWN` until supported by evidence.
9. Material blockers cannot be hidden by aggregate scores or majority agreement.
10. Every material change that invalidates an assurance premise triggers re-assurance from the earliest affected stage.

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
HUMAN CHARTER AUTHORIZATION
   ↓
ORDINARY PROJECT LIFECYCLE
```

Specification is not implementation. Implementation is not verification. Verification is not authority.

---

# 1. CONSOLIDATION AND INDEPENDENT-REVIEW AMENDMENT REGISTER

The supplied v3.00.00–v3.00.04 family and the associated AEA/AEV/AEC evidence were treated as design evidence rather than unquestionable authority. Repeated concepts were consolidated where their control objective was materially the same.

| ID | Consolidated concept | v3.01.07 treatment | Review status |
|---|---|---|---|
| M-01 | Repeated lead/collaborator prompt rules | Universal Prompt Engineering Contract + section prompts | Retained |
| M-02 | Repeated AEA/AEV/AEC/AECC cycles | Reusable assurance protocol with subject-specific evidence | Retained |
| M-03 | Authority, roles and permissions | Authority–Permission–Independence Model | Strengthened with mechanical enforcement boundary |
| M-04 | Evidence/provenance/durable references | Evidence Integrity and Provenance Model | Strengthened with canonical-source rule |
| M-05 | Multiple state ladders | Control Maturity, Artifact State and Authorization State | Retained as orthogonal axes |
| M-06 | Stop/reset/emergency models | Stop, Reset and Emergency Control Model | Strengthened with named reset authority |
| M-07 | Repeated Task Packet definitions | One Task Packet schema and enforcement contract | Strengthened with authorized-path enforcement |
| M-08 | Multiple readiness/final gates | Gate Register + Final Pre-Charter Gate | Retained |
| M-09 | Identifier grammars | PBIM identifiers separated from PM classification; canonical registry required | Strengthened; implementation blocker removed only by registry evidence |
| M-10 | Repeated risk profiles | Materiality/risk model | Retained |
| M-11 | Expected-date rules | One Expected Project Timing Rule | Retained |
| M-12 | Proposal/template verification duplication | Standardized verification pattern | Retained |
| M-13 | Simulation vs activation | Simulation produces evidence; human authorization activates transition | Retained |
| M-14 | PM process IDs mixed with PBIM IDs | PM classification and PBIM section identity are separate | Retained |
| M-15 | Project-specific implementation details | Generic role bindings/placeholders | Retained |
| M-16 | Ambiguous approval terminology | Decision vocabulary separated from authorization vocabulary | Strengthened |
| M-17 | Fixed process-count claims | Process classifications are mapping aids, not architecture | Retained |
| M-18 | Prompt resources outside prompts | Named resource blocks inside prompts | Retained |
| M-19 | Variable prompt end markers | One mandatory marker grammar | Retained |
| M-20 | Inferred missing facts | `UNKNOWN` with owner, evidence requirement and target | Retained |
| M-21 | Prompt-only enforcement | Task Packet authorized-path enforcement must be mechanical where enforcement is claimed | **New amendment** |
| M-22 | Identifier registry described but not instantiated | Canonical registry schema, authority, revision, integrity and sequence index explicitly required | **New amendment** |
| M-23 | Secret leakage risk in evidence | Evidence redaction control required for sensitive logs | **New amendment** |
| M-24 | Reset authority ambiguity | Reset decision owner and escalation path explicitly required | **New amendment** |
| M-25 | Role-combination ambiguity | Combination matrix and independence test required | **New amendment** |
| M-26 | AI governance reference gap | ISO/IEC 42001:2023 may be evaluated where AI governance is in scope | **New conditional reference** |

The amendment register demonstrates actual consolidation: repeated control objectives are represented once in a governing model and referenced by later sections rather than copied with different labels.

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

Expected timing is planning information, not a commitment or authorization.

`EXPECTED-PROJECT-DURATION` records calendar duration, working-day convention, expected working hours, capacity assumptions, dependencies, non-working days where material, estimating method, range, confidence/evidence quality and source.

`EXPECTED-PROJECT-START-DATE` is the earliest date at which authority, intended resources, dependencies and enabling conditions are reasonably expected to exist.

`EXPECTED-PROJECT-END-DATE` is derived from expected start and duration using the stated calendar and material dependencies.

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
| `CA` | Optional Constitutional/Organizational Authority | Use only where the organization actually has such a superior constitutional control role; never assume it exists |

A required authority must be identifiable, available or have a formally approved delegation. Technical access never creates governance authority.

## 3.2 Agent capabilities

`LEAD`, `ANL`, `VER`, `SEC`, `IMP`, `TST`, `OPS` and `DOC` are capability roles, not governance authorities.

An agent may not approve its own work merely because it holds a verification or lead capability.

## 3.3 Role-combination rule

For every project, record a role-combination matrix:

```text
ACTOR → CAPABILITIES → PERMISSIONS → DECISION RIGHTS → INDEPENDENCE CLASS
```

If one actor holds multiple capabilities, the project must document why the combination is acceptable for its risk profile and what independent control remains. For high-assurance work, independence must not be satisfied by relabeling the same actor.

## 3.4 Independence classes

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
PBI-03-0004.03
...
PBI-09-0004.09
```

`0004.01`–`0004.09` are PBIM internal section coordinates. They are not interchangeable with the formal Charter process coordinate `0004.1`.

```text
[GOV-01-0004.1] = Initiate Project or Phase / Develop Project Charter
```

The distinction must be preserved in filenames, headings, prompts and registries.

## 4.2 Identifier taxonomy

Keep these identities separate:

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

A PM classification is not an executable workflow instruction unless a separate project governance system explicitly makes it one.

## 4.3 Canonical Identifier Registry — mandatory control

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

The registry must reject, as applicable:

* duplicate identifiers;
* duplicate sequence indexes where uniqueness is required;
* invalid grammar;
* missing mandatory fields;
* illegal lifecycle transitions;
* conflicting authoritative revisions.

Machine ordering must use explicit `sequence_index`; human-readable numeric strings must never be used as the machine sorting algorithm.

## 4.4 Registry authority and integrity

The registry is a controlled state store, not merely a documentation page.

Allocation follows:

```text
REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM
```

Only the canonical registry transaction may establish authoritative identity. Stale branches cannot create authoritative identity. Conflicting writes cause `REGISTRY-BLOCKED` until resolved and independently verified.

Each registry revision records revision ID, parent revision where applicable, content hash/integrity reference, change record, accountable actor, timestamp and verification status.

Retired identifiers are tombstoned and never silently reused.

## 4.5 Registry readiness states

```text
DESIGNED → SCHEMA-VALIDATED → AUTHORITATIVE → INDEPENDENTLY-VERIFIED
```

A registry described by this document but not instantiated and verified remains `DESIGNED`.

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

`DESIGNED` means specified. `ENFORCEABLE` means a credible bounded mechanism exists. `ENFORCED` requires evidence that the mechanism actually acts. `INDEPENDENTLY VERIFIED` requires appropriately independent verification.

Documentation alone cannot establish the latter two states.

## 5.3 Artifact state

```text
DRAFT → ANALYSIS → CONTROLLED CANDIDATE → VERIFICATION → APPROVED → SUPERSEDED → ARCHIVED
```

## 5.4 Authorization state

Where relevant:

```text
NOT-AUTHORIZED → AUTHORIZED → IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED → PRODUCTION
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

Tailoring may reduce ceremony but may not remove protected legal, safety, security, authority or evidence-integrity controls.

## 5.6 Cumulative materiality

Assess related tasks, dependencies, migrations, concurrent changes, shared infrastructure/data, releases and combined blast radius. Several individually low-risk changes may form one material change.

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

The reset authority must be recorded in the Human Authority Register or applicable delegation record. Reset cannot be decided solely by the executor whose work is affected.

## 5.9 Emergency delegation

Where permitted, record authority, scope, permitted action, start time, expiry/TTL, evidence requirements and post-event reconciliation. Emergency authority never permanently weakens the baseline.

---

# 6. DURABLE REFERENCES, SECURITY AND MODERN PRACTICE

## 6.1 Durable evidence

For authoritative evidence, prefer:

```text
ARTIFACT-ID @ IMMUTABLE COMMIT/TAG/OBJECT
INTEGRITY-REFERENCE
```

Mutable branch URLs are convenience pointers, not sufficient authority for critical evidence.

## 6.2 Security and privacy

Where applicable assess data classification, privacy obligations, secrets management, least privilege, separation of duties, privileged access, supply-chain risk, secure development, logging/auditability, vulnerability management, backup/recovery, incident response, resilience, secure release and retention/deletion.

Never place credentials, authentication tokens or other secrets in PBIM prompts or ordinary evidence artifacts.

## 6.3 AI-enabled work

Where AI systems or agents are involved assess system role, authority boundary, data exposure, instruction precedence, tool permissions, output verification, provenance, model/dependency changes, adversarial inputs, human oversight, misuse, privacy, security and operational fallback.

Where relevant, ISO/IEC 42001:2023 may be considered as an AI-management-system reference; it is not automatically binding. NIST AI RMF remains a voluntary reference unless adopted by the governing organization.

## 6.4 Standards currency rule

Standards references are time-sensitive. PBIM must record the review date and authoritative source for each external reference. A draft, committee document or work item must never be represented as a published standard.

At the 2026-10-08 review date:

* PMI identifies the **PMBOK Guide — Eighth Edition** as its current guide; it was published in November 2025. citeturn6search2
* **ISO 21502:2020** remains published, while ISO lists **ISO/CD 21502 Edition 2** as a committee draft under development; the draft is not a published standard. citeturn6search0turn6search1
* **ISO 31000:2018** remains current following its 2023 confirmation. citeturn6search5
* **ISO/IEC 27001:2022** remains published, with the 2024 climate-action amendment. citeturn6search12turn6search7
* **NIST AI RMF 1.0** remains available and NIST states that it is being revised. citeturn6search10
* OWASP identifies **ASVS 5.0.0** as the latest stable version in its current project documentation. citeturn7search1

The project must explicitly record which references are applicable and binding.

---

# 7. UNIVERSAL PROMPT ENGINEERING CONTRACT

Every operational PBIM prompt must use this grammar:

```text
<<START Prompt N. [Prompt Label]>>
[Designation: Lead Agent / Collaborating Agents / Human Authority]
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

1. The start marker must exactly match `<<START Prompt N. [Prompt Label]>>`.
2. The end marker must exactly match `<<STOP Prompt N. [Prompt Label]>>`.
3. `Designation` must be explicit.
4. Every resource must be inside a named resource block.
5. Notes appear below the resource block when present.
6. Missing facts are `UNKNOWN`, not invented.
7. Material findings include evidence and materiality.
8. Conditions include owner, evidence requirement and advancement consequence.
9. Output and stop conditions are explicit.
10. Decision vocabulary is explicit when a decision is requested.
11. A collaborating agent cannot approve its own work.
12. Lead synthesis must preserve minority findings and unresolved dissent.
13. Prompt instructions cannot create implementation or production authority.
14. Prompt scope is not a substitute for technical enforcement.
15. If a prompt requests a real-world modification, its Task Packet must specify authorized targets and the applicable mechanical enforcement mechanism.

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

Before a prompt is used, verify:

* exactly one start marker and one matching end marker for the prompt;
* balanced resource start/end markers;
* no resource block accidentally contains the prompt end marker;
* all placeholders are resolvable or explicitly `UNKNOWN`;
* the resource labels correspond to the referenced evidence;
* decision authority is not inferred from role capability;
* no secret is embedded.

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

## 8.1 Enforcement rule

Prompt instructions alone do not establish `ENFORCED` scope control.

Where a Task Packet is claimed to restrict file or repository changes, the project must implement a mechanical boundary appropriate to its tooling, such as CI/pre-commit diff validation against `authorized_paths`, repository permissions, protected branches or equivalent controls.

The control must detect at least:

* changes outside authorized paths;
* unauthorized protected-resource changes;
* missing authorization reference;
* stale or superseded Task Packet state;
* and inconsistent task identity.

If the mechanical control is absent, the state remains `DESIGNED` or `ENFORCEABLE`, not `ENFORCED`.

## 8.2 Evidence-log protection

Where logs may contain secrets or sensitive data, the project must define a redaction mechanism before claiming evidence integrity. At minimum define protected patterns/categories, execution point, failure behavior and verification method.

A failed redaction control causes the applicable evidence path to stop and may require a security incident process.

---

# 9. TRACEABILITY AND DECISION CONTROL

PBIM must support traceability:

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

A formal Requirements Traceability Matrix is conditional on risk and complexity, but the project must retain an equivalent traceability mechanism whenever implementation decisions require durable justification.

## 9.1 ADR vs Decision Ledger

**ADR:** durable architectural decision, context, alternatives, rationale, consequences and decision owner.

**Decision Ledger:** chronological operational/governance decisions, open decisions, conditions, approvals, changes, status and disposition.

The two may be implemented in one system only if both functions remain distinguishable and auditable.

---

# 10. ASSURANCE PROTOCOL

The reusable assurance cycle is:

```text
AEA → AEV → AEC → AECC
```

* **AEA** — architectural analysis;
* **AEV** — independent verification against evidence and controls;
* **AEC** — adversarial challenge intended to break the design;
* **AECC** — closure and formal disposition of findings.

Consensus is not verification. Verification is not authorization. Human authority owns governance decisions.

## 10.1 Blocking findings

A material finding blocks progression where it affects a protected control, load-bearing premise, mandatory legal/security/safety requirement, authority boundary, evidence integrity, identifier integrity, or required transition criterion.

A non-blocking finding may proceed only with recorded owner, corrective evidence, advancement consequence and authorized residual-risk disposition.

## 10.2 Dissent

Every material dissent is preserved with author/role, evidence, issue, affected decision and disposition. Majority agreement cannot erase a minority finding.

---

# 11. PBIM LIFECYCLE

## [PBI-01-0004.01] PBIM Document Creation & Baseline Initialization

Establish project identity, initial authority context, source-of-truth references, risk profile, timing expectations and baseline document state.

**Gate G1:** identity and authority context established; missing material facts explicitly registered.

## [PBI-02-0004.02] PBIM Architectural Assurance

Subject the PBIM generic candidate to AEA → AEV → AEC → AECC.

**Gate G2:** material architectural findings resolved or formally dispositioned; identifier and authority controls satisfy their required maturity state.

## [PBI-03-0004.03] Project Context & Proposal Definition

Develop the project-specific proposal from the approved generic baseline. Do not introduce implementation authority merely by drafting.

**Gate G3:** context and proposal evidence are sufficient for proposal engineering.

## [PBI-04-0004.04] Project Proposal Engineering & Verification

Analyze, verify and challenge the proposal.

**Gate G4:** proposal is coherent, evidence-backed and materially dispositioned.

## [PBI-05-0004.05] Project Template Assembly

Translate the verified proposal into a proportionate project operating model.

**Gate G5:** required project controls are identified, owned and risk-scaled.

## [PBI-06-0004.06] Project Template Engineering & Verification

Engineer and independently verify the project template, including permissions, traceability, security, testing, release and operational controls.

**Gate G6:** template is sufficiently complete and enforceable by design.

## [PBI-07-0004.07] Project Configuration & Governance Initialization

Instantiate only the approved governance configuration. Configuration is not implementation authorization.

**Gate G7:** initialized framework independently verifies against approved configuration and authority boundaries.

## [PBI-08-0004.08] Project Simulation & Readiness Review

Exercise normal and adverse scenarios before Charter transition.

Minimum applicable scenario families:

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

**Gate G8:** simulation evidence demonstrates control behavior rather than merely document presence.

## [PBI-09-0004.09] PBIM Activation & Charter Readiness

Assemble the final pre-charter package and obtain the authorized human decision.

**Gate G9:** only H0 or the formally delegated equivalent may authorize PBIM-to-Charter transition.

---

# 12. CONFIGURATION AND INITIALIZATION CONTROLS

Configuration planning must distinguish:

```text
DESIGN
→ AUTHORIZED CONFIGURATION
→ EXECUTION
→ OBSERVED RESULT
→ INDEPENDENT VERIFICATION
```

For every configuration item record purpose, owner, authority, permission, intended state, verification method, evidence, rollback/recovery and stop condition.

Configuration must not create authority that was not already granted.

The Human Authority Register, Authority–Permission Matrix and Identifier Registry are controlled governance artifacts. Their canonical locations must be recorded; ephemeral chat/local copies are not authoritative.

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

A readiness reviewer must challenge false-positive tests, incomplete scenarios, stop enforcement, authority conflicts, evidence integrity, recovery and human unavailability.

---

# 14. CHANGE CONTROL

Once a controlled baseline exists, material change follows:

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

Change classes may include documentation-only, trivial, configuration, implementation, architectural, security, breaking and emergency changes.

A material change that invalidates an assured premise requires re-assurance from the earliest affected stage.

---

# 15. PROTECTED CONTROLS AND MINIMUM PROJECT POSTURE

Every project requires, at minimum:

* project identity;
* authoritative source definition;
* authority definition;
* requirements and constraints;
* evidence classification;
* baseline verification;
* challenge before implementation where implementation is in scope;
* traceability sufficient to explain material work;
* repository/source control appropriate to the project;
* stop conditions;
* implementation verification where implementation occurs;
* appropriate human authorization.

Conditional controls may include full ADR sets, formal RTM, multi-agent challenge panels, detailed risk registers, complex branch strategies, advanced PM-process mapping, specialized security review and formal cost controls.

Risk tailoring may reduce ceremony, not protected controls.

---

# 16. AUTHORITY–PERMISSION MATRIX

The project must maintain a canonical matrix separating:

| Action | Capability | Permission | Governance Authority | Independent Check | Evidence |
|---|---|---|---|---|---|
| Analyze | ANL/LEAD | Analyze source | None by capability | Where required | Analysis record |
| Draft | LEAD/DOC | Write designated artifact | None by capability | Review as required | Artifact revision |
| Modify code | IMP | Authorized Task Packet paths | H1/H2 as delegated | TST/VER as required | Commit/test evidence |
| Change governance baseline | LEAD/DOC | Controlled repository write | H0/H1 as delegated | Independent verification | Decision + diff |
| Release | Release/OPS capability | Release permission | Explicit release authority | Independent release check | Release evidence |
| Production action | OPS/technical capability | Explicit production permission | Explicit human authority | Required operational control | Audit evidence |
| Approve own work | Any | **Prohibited** | N/A | N/A | Stop/finding |

Actual project mappings must be recorded before the relevant operation.

---

# 17. PROMPT REGISTER

Each operational prompt receives a unique Prompt ID and revision. The prompt register records:

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

Prompts must not be treated as immutable security controls unless an external enforcement mechanism exists.

---

# 18. GATE REGISTER

| Gate | Required condition | Minimum evidence | Blocking examples |
|---|---|---|---|
| G1 | Identity/authority context | Identity + authority register | Missing required authority |
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

At minimum, as applicable:

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
* Release/Deployment evidence where the project later enters those phases.

Each controlled register has one canonical authoritative location and a defined revision/integrity mechanism.

---

# 20. STANDARDIZED PROMPT SET

PBIM defines 25 reusable prompt instances. Each instance is a controlled instruction artifact subordinate to the Universal Prompt Engineering Contract. The following normative forms are intentionally explicit so marker integrity, resource placement and decision boundaries can be mechanically checked.

## Prompt 1 — PBIM Generic Baseline Synthesis

<<START Prompt 1. PBIM Generic Baseline Synthesis>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Synthesize the generic PBIM baseline from authoritative source material and assurance evidence.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START PBIM Reference Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Reference Package>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

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
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 1. PBIM Generic Baseline Synthesis>>

## Prompt 2 — PBIM Baseline Independent Review

<<START Prompt 2. PBIM Baseline Independent Review>>

[Designation: Collaborating Agents]

ROLE
Act as an independent reviewer of the PBIM candidate.

OBJECTIVE
Attempt to identify substantive architectural, governance, engineering, identifier and prompt defects.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

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
13. Use the standard finding format: Finding → Evidence → Impact → Severity → Materiality → Affected Control → Recommendation.
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + PBIM Source Set>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

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
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START PBIM Candidate + Applicable Standards and Governance References>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + Applicable Standards and Governance References>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 3. PBIM Architectural Engineering Analysis>>

## Prompt 4 — PBIM Architectural Engineering Verification

<<START Prompt 4. PBIM Architectural Engineering Verification>>

[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Verify material controls against authority, ownership, evidence, transition, failure and acceptance criteria.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START AEA Package + PBIM Candidate>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA Package + PBIM Candidate>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 4. PBIM Architectural Engineering Verification>>

## Prompt 5 — PBIM AEV Decisions and Adversarial Challenge Preparation

<<START Prompt 5. PBIM AEV Decisions and Adversarial Challenge Preparation>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Consolidate AEV decisions and prepare a bounded adversarial challenge package.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START AEV Decisions + Current AEV Statement>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV Decisions + Current AEV Statement>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 5. PBIM AEV Decisions and Adversarial Challenge Preparation>>

## Prompt 6 — PBIM Architectural Engineering Challenge

<<START Prompt 6. PBIM Architectural Engineering Challenge>>

[Designation: Collaborating Agents / Challenge Agents]

ROLE
Collaborating Agents / Challenge Agents.

OBJECTIVE
Attempt to break the PBIM architecture rather than defend it.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START AEV-Approved Candidate + Existing AEC Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV-Approved Candidate + Existing AEC Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 6. PBIM Architectural Engineering Challenge>>

## Prompt 7 — PBIM AEC Results and Closure Preparation

<<START Prompt 7. PBIM AEC Results and Closure Preparation>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Reconcile assurance findings without hiding material dissent.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START AEC Results + AEV Statement and Decisions>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEC Results + AEV Statement and Decisions>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 7. PBIM AEC Results and Closure Preparation>>

## Prompt 8 — PBIM Architectural Engineering Challenge Closure

<<START Prompt 8. PBIM Architectural Engineering Challenge Closure>>

[Designation: Lead Agent / Authorized Assurance Authority]

ROLE
Lead Agent / Authorized Assurance Authority.

OBJECTIVE
Determine whether the assurance cycle can be formally closed.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START AEA/AEV/AEC/Amendment Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA/AEV/AEC/Amendment Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 8. PBIM Architectural Engineering Challenge Closure>>

## Prompt 9 — Project Context and Proposal Definition

<<START Prompt 9. Project Context and Proposal Definition>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Develop an evidence-classified project proposal from the approved PBIM baseline.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 9. Project Context and Proposal Definition>>

## Prompt 10 — Project Context Independent Review

<<START Prompt 10. Project Context Independent Review>>

[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently review the initial project proposal.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Project Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Proposal + PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 10. Project Context Independent Review>>

## Prompt 11 — Project Proposal AEA

<<START Prompt 11. Project Proposal AEA>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Analyze proposal coherence, feasibility, evidence and material risk.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Controlled Project Proposal + Supporting Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Controlled Project Proposal + Supporting Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 11. Project Proposal AEA>>

## Prompt 12 — Project Proposal AEV and AEC Verification

<<START Prompt 12. Project Proposal AEV and AEC Verification>>

[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Verify and challenge the project proposal.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Proposal AEA Package + Current Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal AEA Package + Current Proposal + PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 12. Project Proposal AEV and AEC Verification>>

## Prompt 13 — Project Proposal AECC Closure and Baseline

<<START Prompt 13. Project Proposal AECC Closure and Baseline>>

[Designation: Lead Agent / Authorized Human Closure Authority]

ROLE
Lead Agent / Authorized Human Closure Authority.

OBJECTIVE
Close the proposal assurance cycle and establish its controlled baseline.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Proposal Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal Assurance Package + Decisions + Corrective Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 13. Project Proposal AECC Closure and Baseline>>

## Prompt 14 — Project Template Assembly

<<START Prompt 14. Project Template Assembly>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Assemble a proportionate project operating template.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Verified Project Proposal + Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Verified Project Proposal + Approved PBIM Baseline>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 14. Project Template Assembly>>

## Prompt 15 — Project Template Completeness Review

<<START Prompt 15. Project Template Completeness Review>>

[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently identify missing, excessive, contradictory or unenforceable template controls.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Project Template + Verified Proposal>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Verified Proposal>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 15. Project Template Completeness Review>>

## Prompt 16 — Project Template Engineering, Verification and Challenge

<<START Prompt 16. Project Template Engineering, Verification and Challenge>>

[Designation: Lead Agent / Collaborating Agents]

ROLE
Lead Agent / Collaborating Agents.

OBJECTIVE
Engineer, verify and challenge the project template.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Project Template + Requirements + Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Requirements + Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 16. Project Template Engineering, Verification and Challenge>>

## Prompt 17 — Project Template AECC Closure and Baseline

<<START Prompt 17. Project Template AECC Closure and Baseline>>

[Designation: Lead Agent / Authorized Assurance Authority]

ROLE
Lead Agent / Authorized Assurance Authority.

OBJECTIVE
Close the project-template assurance cycle.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Template Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Template Assurance Package + Decisions + Corrective Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 17. Project Template AECC Closure and Baseline>>

## Prompt 18 — Project Governance Configuration and Initialization

<<START Prompt 18. Project Governance Configuration and Initialization>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Instantiate only the approved governance configuration within explicit authority.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Approved Project Template + Authority/Identifier Resources + Repository References>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Project Template + Authority/Identifier Resources + Repository References>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 18. Project Governance Configuration and Initialization>>

## Prompt 19 — Project Governance Configuration Verification

<<START Prompt 19. Project Governance Configuration Verification>>

[Designation: Collaborating Agents / Verification and Operations Roles]

ROLE
Collaborating Agents / Verification and Operations Roles.

OBJECTIVE
Independently verify the initialized governance environment.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Approved Configuration Plan + Initialized Governance Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Configuration Plan + Initialized Governance Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 19. Project Governance Configuration Verification>>

## Prompt 20 — PBIM Project Simulation and Readiness Exercise

<<START Prompt 20. PBIM Project Simulation and Readiness Exercise>>

[Designation: Lead Agent / Collaborating Assurance Agents]

ROLE
Lead Agent / Collaborating Assurance Agents.

OBJECTIVE
Exercise the configured governance framework under normal and adverse scenarios.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Initialized Project Framework + Task Packet/Control Model>>
{{DURABLE-RESOURCE-SET}}
<<STOP Initialized Project Framework + Task Packet/Control Model>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 20. PBIM Project Simulation and Readiness Exercise>>

## Prompt 21 — PBIM Readiness Independent Challenge

<<START Prompt 21. PBIM Readiness Independent Challenge>>

[Designation: Collaborating Agents / Challenge Agents]

ROLE
Collaborating Agents / Challenge Agents.

OBJECTIVE
Challenge whether simulation evidence demonstrates actual control behavior.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Simulation Results + Readiness Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Simulation Results + Readiness Evidence>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 21. PBIM Readiness Independent Challenge>>

## Prompt 22 — PBIM Activation and Charter Readiness

<<START Prompt 22. PBIM Activation and Charter Readiness>>

[Designation: Lead Agent]

ROLE
Lead Agent.

OBJECTIVE
Assemble the final pre-charter transition package.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 22. PBIM Activation and Charter Readiness>>

## Prompt 23 — PBIM Charter Readiness Independent Review

<<START Prompt 23. PBIM Charter Readiness Independent Review>>

[Designation: Collaborating Agents]

ROLE
Collaborating Agents.

OBJECTIVE
Independently determine whether the evidence justifies Charter transition.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START Complete PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP Complete PBIM Transition Package>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 23. PBIM Charter Readiness Independent Review>>

## Prompt 24 — Human PBIM Transition Authorization

<<START Prompt 24. Human PBIM Transition Authorization>>

[Designation: Human Project Authority H0]

ROLE
Human Project Authority H0.

OBJECTIVE
Make the authorized human PBIM-to-Charter transition decision.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START PBIM Transition Package + Independent Review>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Transition Package + Independent Review>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 24. Human PBIM Transition Authorization>>

## Prompt 25 — Develop Project Charter

<<START Prompt 25. Develop Project Charter>>

[Designation: Lead Agent / Human Project Authority]

ROLE
Lead Agent / Human Project Authority.

OBJECTIVE
Develop the Project Charter using the approved pre-charter package.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority or evidence.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly.
3. Preserve material dissent.
4. Do not create implementation or production authority through analysis, drafting or recommendation.
5. Apply the applicable risk profile and stop conditions.

<<START PBIM Charter Readiness Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Charter Readiness Package>>

Note the following:
The resource block is authoritative only to the extent its provenance and integrity are established. A mutable pointer is not sufficient evidence for a critical claim.

OUTPUT
Required findings, evidence references, conditions, unresolved items and the decision permitted by this prompt.

STOP CONDITIONS
Stop for missing required authority, materially contradictory authoritative evidence, unresolved protected-control failure, evidence-integrity failure, identifier conflict, or requested action outside authorization.

DECISION SET
Use only the decision vocabulary defined by the governing section and do not treat the decision as production authorization.

<<STOP Prompt 25. Develop Project Charter>>

# 21. UNIVERSAL PROMPT INSTANCE — REQUIRED FORM

# 21. UNIVERSAL PROMPT INSTANCE — REQUIRED FORM

The following is the normative form for each prompt instance:

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

No prompt may silently omit a required resource, authority boundary, stop condition or decision vocabulary where the operation needs one.

---

# 22. HUMAN PBIM-TO-CHARTER TRANSITION

The final PBIM package must contain:

1. approved/controlled PBIM baseline reference;
2. project proposal baseline;
3. project template baseline;
4. configuration evidence;
5. simulation/readiness evidence;
6. risk and decision registers;
7. open findings and residual-risk statement;
8. expected timing with evidence quality;
9. explicit non-authorizations;
10. independent readiness review;
11. Human Authority decision.

Only the authorized human authority may cross the PBIM boundary.

Permitted transition decisions:

```text
AUTHORIZE-CHARTER-DEVELOPMENT
AUTHORIZE-WITH-CONDITIONS
RETURN
BLOCK
```

`AUTHORIZE-WITH-CONDITIONS` is permitted only when the conditions are explicitly owned, evidenced and allowed by the governing authority. It does not authorize production implementation.

---

# 23. EVIDENCE LIMITATIONS AND NON-CLAIMS

This generic PBIM document does not claim that:

* the Identifier Registry has been deployed in every project;
* CI/pre-commit Task Packet enforcement exists in every repository;
* secret-redaction automation is deployed;
* human authority registers have been populated;
* every prompt has been executed;
* every control is enforced;
* every referenced standard is binding;
* a project is approved merely because it satisfies the document structure;
* or the PBIM candidate has been independently verified merely because the source family contains prior agent approvals.

Those claims require project-specific evidence.

---

# 24. REQUIRED PRE-EXECUTION CONDITIONS FOR THIS GENERIC BASELINE

Before treating this document as an executable generic baseline for a concrete project, the following must be evidenced:

### B-01 — Canonical Identifier Registry

Create and independently validate the project registry using the schema in §4.3, including explicit `sequence_index` semantics.

### B-02 — Authority Register

Populate the Human Authority Register with the actual H0/H1 roles or formally documented equivalent delegation.

### B-03 — Reset Authority

Record who may declare an Architectural Reset, who may resolve governance-level reset disputes, and how escalation works if the primary authority is unavailable.

### B-04 — Task Packet Mechanical Boundary

Implement and verify an appropriate authorized-path/diff enforcement mechanism before claiming Task Packet scope is `ENFORCED`.

### B-05 — Evidence Redaction

Define and verify the redaction mechanism for logs/evidence that could contain credentials, payment tokens, secrets or other sensitive data.

### B-06 — Instruction Precedence

Establish a canonical precedence order for organizational governance, PBIM baseline, project template, Task Packet and agent-local instructions. Lower-level instructions cannot override higher-level authority.

### B-07 — Role Combination

Record the actor/capability/permission/independence matrix before combining lead, implementation, verification or approval responsibilities.

A project may not claim executable maturity above `DESIGNED` for these controls without the corresponding evidence.

---

# 25. FINAL BASELINE POSITION

PBIM v3.01.07 is a **controlled generic candidate** amended after independent review of the supplied candidate, source revisions and assurance evidence.

The principal architectural correction in this revision is the separation between **describing a control** and **proving that the control is enforceable**. In particular, the identifier registry, Task Packet scope boundary, evidence redaction and reset authority are now explicit pre-execution conditions rather than implied consequences of documentation.

The architecture remains intentionally risk-scaled. Small projects may use fewer agents and lighter ceremony, but protected authority, evidence, stop, traceability and appropriate verification controls remain mandatory.

The PBIM boundary remains:

```text
PBIM READINESS
→ HUMAN AUTHORIZATION
→ FORMAL PROJECT CHARTER
```

No implementation or production authority is created by this document.

---

# END OF PBIM GENERIC EDITION v3.01.07
