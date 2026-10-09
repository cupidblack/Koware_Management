# PROJECT BASE INTEGRATION MANAGER [PBIM]

**Generic Edition:** v2.00.00  
**Generated:** 2026-10-05  
**Status:** Controlled Generic PBIM Candidate — derived from the v1.12.00 structure and the supplied AEA/AEV/AEC evidence set  
**Scope:** Pre-charter project initialization only; PBIM identifiers terminate at `0004.1 Develop Project Charter`

---

# 1. PURPOSE, STATUS AND GOVERNANCE BOUNDARY

PBIM is the pre-charter governance and project-integration framework used to establish a project as an identifiable, authorized, risk-scaled, evidence-traceable and implementation-ready initiative before normal project execution begins.

PBIM does **not** replace the project charter, project management plan, organizational governance, security policy, legal authority, procurement authority, or operational controls. It prepares and integrates them.

The PBIM lifecycle ends at:

`[PROJECT-ID][GOV-01-0004.1] Develop Project Charter`

At `0004.1`, the project becomes formally integrated into its ordinary project lifecycle. Subsequent planning, execution, monitoring/controlling and closing identifiers belong to the project framework, not PBIM.

## 1.1 Architectural model

PBIM uses three separated layers:

```text
GOVERNANCE
    ↓
ASSURANCE
    ↓
EXECUTION
```

Governance establishes authority, constraints and decisions. Assurance evaluates architecture, evidence, risk and verification. Execution performs only authorized work.

The principal architectural assurance cycle is:

```text
AEA → AEV → AEC → AECC
```

Where:

- **AEA** — Architectural Engineering Analysis
- **AEV** — Architectural Engineering Verification
- **AEC** — Architectural Engineering Challenge
- **AECC** — Architectural Engineering Challenge Closure

A control may be specified without being implemented. PBIM therefore distinguishes:

```text
DESIGNED
    ↓
ENFORCEABLE
    ↓
ENFORCED
    ↓
INDEPENDENTLY VERIFIED
```

Documentation never constitutes proof that an enforcement mechanism exists.

## 1.2 Human constitutional boundary

PBIM shall not be self-authorizing.

The generic authority model is:

```text
CA — Constitutional Authority
 ↓
H0 — Human Project Authority
 ↓
H1 — Lead/Delegated Project Governance Authority
 ↓
H2 — Authorized Operational/Technical Authority
```

`CA` is external to PBIM and is responsible for constitutional-level changes to the governance framework. `H0` owns project-level final authority. Technical access does not create governance authority.

Where an actual organization does not use these labels, the project must map its real authorities explicitly before activation.

## 1.3 Risk-scaled profiles

Each project shall select one profile:

| Profile | Intended use | Minimum principle |
|---|---|---|
| `LIGHT` | Low-risk, bounded work | Reduce ceremony, never remove essential authority/security/traceability |
| `STANDARD` | Normal project work | Full PBIM controls with proportionate evidence |
| `HIGH-ASSURANCE` | Financial, security-sensitive, safety-sensitive, irreversible, regulated, high-blast-radius or production-critical work | Full assurance, stronger evidence, independent challenge and operational controls |

Risk scaling affects governance depth and evidence depth. It does not permit protected controls to be bypassed.

---

# 2. REFERENCE BASIS AND MODERN ALIGNMENT

This generic edition was synthesized from the supplied PBIM reference set:

1. Current PBIM v1.12.00.
2. PBIM Architectural Engineering Analysis Query.
3. Consolidated AEA reports.
4. Consolidated AEV statement revisions.
5. Consolidated AEV responses.
6. PBIM AEC Adversarial Duel.
7. Consolidated AEC results.

The evidence set established that the earlier architecture was conceptually strong but required explicit controls for authority/permission separation, registry integrity and recovery, genuine challenge independence, task-scope enforcement, emergency/reset governance, evidence integrity, operational readiness, and cumulative materiality.

The new generic edition incorporates those architectural resolutions while preserving the distinction between specification and implementation evidence.

## 2.1 External standards alignment

PBIM is designed to be compatible with modern project and software-engineering practice, including:

- PMI's **PMBOK® Guide — Eighth Edition** and The Standard for Project Management.
- ISO 21502 project-management guidance, while recognizing that ISO/CD 21502 Edition 2 is under development and therefore is not treated as a final normative requirement.
- NIST Secure Software Development Framework (SSDF), including the direction represented by the SSDF 1.2 public draft where applicable.
- Risk-based secure-development, DevSecOps, CI/CD, change-control, evidence and operational-readiness practices.

PBIM must not claim that any external framework is fully implemented merely because it is referenced.

## 2.2 PM process classification rule

The legacy/local PM process sequence may be retained as a **classification spine** for project traceability.

It shall not be used as:

- the sole project identifier;
- the sole work-item identifier;
- the sole document identifier;
- an implicit state machine;
- or a claim that PMBOK 8 contains exactly the same process list.

The semantic separation is:

```text
PROJECT ID
    ↓
WORK / TASK ID
    ↓
DOCUMENT / ARTIFACT ID
    ↓
LIFECYCLE STATE
    ↓
PM PROCESS / KNOWLEDGE-AREA REFERENCE
```

A machine-readable identifier registry shall be authoritative for local identifiers and sequence ordering.

---

# 3. IDENTIFIER ARCHITECTURE

## 3.1 Generic project identifier

```text
[BZJ-[PROJECT]]
```

Example:

```text
[BZJ-PGBD]
```

## 3.2 PBIM section identifier

```text
[BZJ-[PROJECT]][DOMAIN-XX-0004.XX]
```

Examples:

```text
[BZJ-PGBD][RES-03-0004.01]
[BZJ-PGBD][GOV-01-0004.07]
[BZJ-PGBD][GOV-01-0004.1]
```

The `0004.xx` range is a PBIM pre-charter namespace. It is not a PMBOK process identifier.

## 3.3 Identity separation

The following identifiers must remain distinct:

- Project ID
- PBIM section ID
- Work/Task Packet ID
- Document/Artifact ID
- Repository object/commit reference
- PM process classification
- Lifecycle state
- Decision ID
- Risk/Finding ID

No single identifier shall be overloaded to mean all of these.

## 3.4 Identifier registry

The project shall maintain a machine-readable registry, for example:

```yaml
identifier:
sequence_index:
project_id:
artifact_type:
domain:
pbim_section:
pm_process_reference:
lifecycle_state:
status:
revision:
canonical_location:
authority:
created_at:
supersedes:
integrity_reference:
```

The registry is a governed state store. It is not an ordinary convenience file.

Registry mutations shall use:

```text
REQUEST
→ RESERVE
→ VALIDATE
→ COMMIT
→ VERIFY
→ CONFIRM
```

A stale revision, concurrent update, branch restore, force-push, deletion or local copy shall not silently become authoritative.

---

# 4. CONTROL, EVIDENCE AND DECISION MODEL

## 4.1 Evidence classes

PBIM recognizes:

- `FACT`
- `INFERENCE`
- `ASSUMPTION`
- `PROPOSAL`
- `RECOMMENDATION`
- `RISK`
- `UNKNOWN`
- `DISPUTED`

Repetition does not upgrade an evidence class.

## 4.2 Decision records

Material decisions shall identify:

- decision ID;
- decision owner;
- authority;
- question;
- alternatives considered;
- evidence;
- decision;
- rationale;
- consequences;
- affected artifacts;
- effective revision;
- superseded decision, if any.

## 4.3 Traceability chain

Material project decisions should be traceable through:

```text
Need / Objective
→ Requirement
→ Decision
→ Architecture
→ Task Packet
→ Change
→ Verification
→ Release / Handoff
```

A break in traceability shall be recorded and classified.

## 4.4 Protected governance resources

Protected resources include, where applicable:

- PBIM baseline;
- Authority Register;
- Authority–Permission Matrix;
- identifier registry;
- protected-control definitions;
- AEA/AEV/AEC/AECC artifacts;
- Decision Ledger;
- authoritative ADRs;
- Task Packet state;
- evidence-integrity records;
- release/readiness records.

Technical administrators may possess capability to alter infrastructure but do not thereby acquire authority to redefine governance.

## 4.5 Protected instruction precedence

Unless a higher organizational/legal requirement applies, the generic precedence model is:

```text
CA / Constitutional Authority
→ Protected Security & Governance Controls
→ Approved PBIM / Architectural Baseline
→ Controlled Governance Documents
→ Project Charter
→ Repository AGENTS.md
→ Subsystem AGENTS.md
→ Task Packet Instructions
→ Tool / Runtime Defaults
→ Informal Instructions
```

Lower-level instructions may not weaken higher-level protected controls.

---

# 5. AEA / AEV / AEC / AECC OPERATING PROTOCOL

## 5.1 AEA

AEA asks whether the proposed architecture, workflow or governance mechanism is coherent.

The Lead Agent synthesizes the question and collaborating agents independently analyze it.

## 5.2 AEV

AEV determines whether the proposed architecture specifies sufficient:

- authorities;
- boundaries;
- states;
- controls;
- evidence requirements;
- transitions;
- failure handling;
- security constraints;
- materiality rules.

AEV does not prove implementation.

## 5.3 AEC

AEC attempts to invalidate the architecture.

It must attack, at minimum when relevant:

- hidden authority;
- technical privilege bypass;
- registry corruption/recovery;
- evidence manipulation;
- false independence;
- task-scope escape;
- instruction drift;
- emergency bypass;
- human unavailability;
- automation compromise;
- baseline drift;
- dependency drift;
- cumulative materiality;
- operational readiness;
- rollback/data-recovery assumptions;
- durable artifact references.

For High-Assurance work, independence must be real, not merely declared.

Independence dimensions:

- `I1` Organizational Independence
- `I2` Evidence Independence
- `I3` Technical Independence
- `I4` Governance Independence

“Same person/agent, different title” is not independence.

If genuine independence cannot be obtained:

```text
CHALLENGE-BLOCKED
```

must prevent false closure.

## 5.4 AECC

AECC closes the adversarial cycle only after all material findings are resolved or formally dispositioned under the applicable authority.

AECC must state:

- challenged baseline;
- findings;
- disposition;
- residual risks;
- evidence;
- closure authority;
- final baseline revision;
- implementation authorization status.

## 5.5 Revision and reset rule

A failed AEV or AEC does not receive an artificial approval.

The architecture shall either:

1. return to the appropriate amendment stage; or
2. trigger Architectural Reset to AEA where a load-bearing assumption has failed.

A bounded number of review cycles may be configured for efficiency, but a cycle limit must never suppress material dissent. Escalation to the appropriate human authority or constitutional authority is required when convergence fails.

---

# 6. PBIM SECTION MAP

| Identifier | Section | Primary output | Gate |
|---|---|---|---|
| `0004.01` | PBIM Document Creation | Generic PBIM candidate | Resource completeness |
| `0004.02` | PBIM Architectural Engineering & Baseline | PBIM AEA/AEV/AEC/AECC evidence set | Architectural baseline readiness |
| `0004.03` | Project Context & Proposal Definition | Initial project proposal | Context completeness |
| `0004.04` | Project Proposal Engineering & Verification | Verified proposal | Proposal approval |
| `0004.05` | Project Template Assembly | Project template skeleton | Assembly completeness |
| `0004.06` | Project Template Engineering & Readiness | Operating project template | Template readiness |
| `0004.07` | Project Framework Initialization | Initialized project controls | Configuration integrity |
| `0004.08` | Project Simulation & Readiness Exercise | Simulation/readiness evidence | Operational readiness |
| `0004.09` | PBIM Activation & Charter Readiness | Activated pre-charter package | Charter readiness |
| `0004.1` | Develop Project Charter | Project Charter | **PBIM integration boundary** |

---

# 7. [BZJ-[PROJECT]][RES-03-0004.01] — PBIM DOCUMENT CREATION

## 7.1 Purpose

Create or update the generic PBIM document from the complete reference package.

## 7.2 Required resources

The prompt must attach or link:

1. Current PBIM.
2. AEA Query.
3. All AEA reports.
4. All AEV revisions.
5. All AEV responses.
6. All AEC challenge documents.
7. All AEC results.
8. Any approved AECC closure, if one exists.
9. Current applicable external standards or organizational policies when material.

## 7.3 Required analysis

The Lead Agent shall:

- identify every existing PBIM section identifier;
- identify every prompt;
- identify obsolete terminology;
- identify contradictory numbering;
- identify unsupported standards claims;
- identify architecture/implementation boundary violations;
- reconcile AEA, AEV and AEC findings;
- preserve useful legacy structure;
- explicitly record superseded structures;
- produce a generic, project-independent document.

## 7.4 Creation prompt

```text
[Designated Target: Lead Agent / All Collaborating Agents]

Using the complete PBIM reference package, produce a generic PBIM candidate.

Requirements:
1. Treat the current PBIM as the structural baseline, not as unquestionable authority.
2. Compare every identified section, heading, identifier, prompt, rule and workflow against the full AEA/AEV/AEC evidence.
3. Preserve useful traceability while correcting obsolete, contradictory or overloaded identifiers.
4. Separate Project ID, Work/Task ID, Document/Artifact ID, lifecycle state and PM-process classification.
5. Retain the local PM process spine only as a classification reference; do not represent it as a PMBOK 8 process count.
6. Incorporate all material architectural findings and their resolutions.
7. Preserve the specification-versus-implementation boundary.
8. Define explicit authorities, stop conditions, escalation, reset and release/readiness boundaries.
9. Define risk-scaled governance profiles.
10. Define evidence, integrity, traceability and durable-reference requirements.
11. Define genuine independence requirements for High-Assurance challenge work.
12. Do not claim a control is ENFORCED unless implementation evidence proves it.
13. End PBIM identifiers at [GOV-01-0004.1] Develop Project Charter.
14. Produce a change summary mapping legacy sections to the new generic structure.
15. Identify unresolved questions rather than silently inventing answers.

Output:
A. Executive synthesis
B. Identifier map
C. Updated PBIM
D. Superseded/retained decisions
E. Remaining assumptions
F. Verification gates
G. Implementation obligations
H. Final authorization status
```

## 7.5 Exit criteria

- All source artifacts accounted for.
- Identifier grammar resolved.
- No contradictory PMBOK/process-count claim.
- Architecture/implementation boundary explicit.
- PBIM scope ends at `0004.1`.
- AEA/AEV/AEC/AECC status explicitly recorded.

---

# 8. [BZJ-[PROJECT]][SCP-04-0004.02] — PBIM ARCHITECTURAL ENGINEERING & BASELINE

## 8.1 Purpose

Establish the controlled architectural baseline for the PBIM framework itself.

## 8.2 Required sequence

```text
PBIM Draft
→ AEA Query
→ Independent AEA Reports
→ AEV Baseline Candidate
→ Independent AEV Decisions
→ AEC Adversarial Duel
→ AEC Results
→ AECC
→ Approved PBIM
```

No stage may be skipped because a previous revision was approved.

## 8.3 Prompt — AEA

```text
[Designated Target: Lead Agent]

Analyze the PBIM architecture as a governance system rather than merely as a document.

Evaluate:
- authority model;
- identifier model;
- registry integrity;
- evidence provenance;
- agent role separation;
- AEA/AEV/AEC/AECC lifecycle;
- risk scaling;
- Task Packet control;
- stop/resume/reset;
- emergency delegation;
- security/secret boundary;
- instruction precedence;
- baseline drift;
- dependency drift;
- cumulative materiality;
- operational readiness;
- durable references;
- failure recovery;
- human unavailability;
- automation compromise.

For each finding classify:
AGREEMENT / DISAGREEMENT / UNKNOWN / ASSUMPTION / RISK / REQUIRED DECISION / RECOMMENDATION.

Do not approve the architecture. Produce analysis for Lead-Agent synthesis.
```

## 8.4 Prompt — AEV

```text
[Designated Target: Collaborating Agents]

Review the controlled PBIM AEV candidate.

Determine whether it specifies adequate:
1. authority;
2. permissions;
3. boundaries;
4. state transitions;
5. evidence requirements;
6. security controls;
7. challenge independence;
8. failure handling;
9. risk scaling;
10. operational-readiness requirements.

Explicitly distinguish:
- what is architecturally specified;
- what is enforceable by design;
- what still requires implementation;
- what requires independent operational verification.

Decision:
AEV APPROVE
AEV APPROVE WITH CONDITIONS
AEV RETURN FOR AMENDMENT
AEV BLOCK
ARCHITECTURAL RESET

State all material conditions.
```

## 8.5 Prompt — AEC

```text
[Designated Target: Authorized Independent Challenge Agent]

Attempt to invalidate the PBIM baseline.

Do not refine it merely to make it more coherent.

Construct falsifiable attacks against:
- authority/permission mismatch;
- hidden authority;
- registry concurrency/corruption/recovery;
- evidence manipulation;
- false challenge independence;
- Task Packet scope escape;
- protected-control weakening;
- AGENTS.md or instruction drift;
- emergency delegation;
- stop/reset bypass;
- cumulative low-risk changes;
- dependency/environment drift;
- approval expiry;
- operational-readiness bypass;
- rollback/data rollback;
- human unavailability;
- compromised automation;
- durable-reference failure;
- governance-of-governance;
- excessive or insufficient governance.

For each attack report:
attack → expected control → observed weakness → severity → evidence → disposition.

A challenge that cannot satisfy required independence must return CHALLENGE-BLOCKED rather than falsely pass.
```

## 8.6 Prompt — AECC

```text
[Designated Target: Lead Agent + Authorized Challenge Authority]

Close the adversarial cycle only after all material findings are resolved or formally accepted by the correct authority.

For every finding:
- preserve the original finding;
- record disposition;
- identify amendment;
- identify verification evidence;
- identify residual risk;
- state whether re-verification is required.

Produce:
1. closure decision;
2. final baseline revision;
3. residual-risk register;
4. implementation obligations;
5. authorization status.

AECC closure does not itself authorize production.
```

---

# 9. [BZJ-[PROJECT]][SCP-04-0004.03] — PROJECT CONTEXT & PROPOSAL DEFINITION

## 9.1 Purpose

Translate the approved PBIM framework into the project's initial context without prematurely treating assumptions as requirements.

## 9.2 Required project inputs

At minimum:

- project name and abbreviation;
- problem/opportunity;
- strategic objective;
- intended outcomes/value;
- sponsor/authority;
- affected stakeholders;
- preliminary scope;
- known constraints;
- assumptions;
- dependencies;
- target environments;
- data/security considerations;
- delivery approach;
- risk profile;
- preliminary success criteria;
- known regulatory/legal obligations.

## 9.3 Initial Project Template Generation Prompt

```text
[Designated Target: Lead Agent / Project Authority]

Using the approved PBIM baseline, generate a project-specific initialization package.

Do not invent missing facts.

For every item classify it as:
FACT / ASSUMPTION / PROPOSAL / UNKNOWN / DECISION REQUIRED.

Produce:
1. project identity;
2. project repository and governance locations;
3. authority map;
4. stakeholder map;
5. preliminary objective/outcome model;
6. preliminary scope boundaries;
7. risk profile;
8. security/data classification;
9. delivery approach;
10. agent-role map;
11. artifact map;
12. initial PM-process classification map;
13. dependency map;
14. initial decision ledger;
15. initial risk and assumption register;
16. proposed Project Charter inputs.

Identify every unresolved item requiring human authority.
```

## 9.4 Exit criteria

- Project identity established.
- Authority identified.
- Scope boundaries are preliminary but explicit.
- Unknowns are recorded.
- No proposal is silently treated as an approved requirement.

---

# 10. [BZJ-[PROJECT]][SCP-04-0004.04] — PROJECT PROPOSAL ENGINEERING & VERIFICATION

## 10.1 Purpose

Architecturally verify the project proposal before assembling the operating project template.

## 10.2 Proposal AEA prompt

```text
[Designated Target: Lead Agent]

Analyze the project proposal for:
- strategic alignment;
- value/outcomes;
- scope coherence;
- stakeholder impacts;
- technical feasibility;
- security/privacy;
- dependencies;
- risk;
- delivery approach;
- resource feasibility;
- procurement implications;
- measurable success criteria;
- operational implications.

Identify contradictions and missing decisions.

Do not rewrite assumptions as facts.
```

## 10.3 Proposal AEV prompt

```text
[Designated Target: Collaborating Agents]

Verify the proposal against the approved PBIM baseline.

Check:
- authority;
- scope boundary;
- requirements quality;
- risk profile;
- evidence;
- security;
- feasibility;
- governance;
- stakeholder obligations;
- measurable outcomes.

Return one formal decision:
APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCK / RESET.

Every condition must identify an owner, evidence requirement and advancement consequence.
```

## 10.4 Proposal AEC prompt

```text
[Designated Target: Independent Challenge Authority]

Attack the proposal.

Try to demonstrate:
- false business value;
- hidden scope;
- unowned authority;
- impossible constraints;
- underestimated risk;
- security/privacy gaps;
- stakeholder conflict;
- dependency failure;
- operational impossibility;
- procurement/legal contradiction;
- success criteria that cannot be measured.

Do not optimize the proposal during the challenge.
```

## 10.5 Exit criteria

Proposal has:

- controlled identity;
- approved/conditional objectives;
- explicit assumptions;
- risk classification;
- authority;
- stakeholder baseline;
- measurable preliminary success criteria;
- resolved material blockers.

---

# 11. [BZJ-[PROJECT]][RES-03-0004.05] — PROJECT TEMPLATE ASSEMBLY

## 11.1 Purpose

Assemble the project-specific operating-template skeleton from the approved PBIM and proposal.

## 11.2 Required template components

At minimum:

```text
Project Identity
Governance
Authority Register
Authority–Permission Matrix
Identifier Registry
Stakeholders
Objectives / Outcomes
Scope
Requirements
Architecture / ADR index
Risk / Assumption / Issue registers
Security boundary
Data classification
Task Packet model
Decision Ledger
Evidence model
Repository model
Agent instructions
Communication model
Change model
Quality model
Verification model
Operational-readiness model
Release/handoff model
```

## 11.3 Assembly prompt

```text
[Designated Target: Lead Agent / Implementation Support]

Assemble the project template from the verified proposal.

For every required artifact:
- assign an artifact ID;
- define canonical location;
- define owner/authority;
- define status;
- define evidence requirements;
- define dependencies;
- define entry and exit criteria.

Do not populate unknown project facts by invention.

Distinguish:
SKELETON
from
OPERATING TEMPLATE.

A skeleton may contain placeholders.
An operating template may not contain unresolved mandatory controls.
```

## 11.4 Exit criteria

All mandatory template artifacts exist as either:

- populated;
- explicitly not applicable with rationale;
- intentionally deferred with authority and due condition;
- or blocked with recorded reason.

---

# 12. [BZJ-[PROJECT]][SCP-04-0004.06] — PROJECT TEMPLATE ENGINEERING & READINESS

## 12.1 Purpose

Verify that the assembled template is internally consistent, executable as a governance mechanism, and ready for project simulation.

## 12.2 Required checks

- identifier uniqueness;
- canonical-location consistency;
- authority/permission alignment;
- protected-control precedence;
- Task Packet state model;
- stop/resume/reset model;
- risk profile;
- evidence classification;
- decision traceability;
- security/secret boundary;
- durable references;
- dependency declarations;
- change/materiality routing;
- operational-readiness definition;
- agent-role separation;
- human escalation;
- failure recovery.

## 12.3 Verification prompt

```text
[Designated Target: Collaborating Agents]

Verify the complete project template as a governance system.

For each mandatory control:
1. identify the requirement;
2. identify the mechanism;
3. identify the authority;
4. identify the evidence;
5. identify the failure state;
6. identify the recovery path;
7. determine whether the mechanism is merely DESIGNED, ENFORCEABLE, ENFORCED, or INDEPENDENTLY VERIFIED.

Do not upgrade maturity without evidence.

Return all material defects and all conditions required before simulation.
```

## 12.4 AEC prompt

```text
[Designated Target: Independent Challenge Authority]

Attempt to break the project template without modifying it.

Attack:
- duplicate identifiers;
- conflicting instructions;
- missing authorities;
- hidden technical privileges;
- Task Packet expansion;
- emergency bypass;
- weak stop conditions;
- evidence replacement;
- stale references;
- risk misclassification;
- low-risk change accumulation;
- missing operational readiness;
- human/agent role collision;
- compromised CI;
- repository administration override.

Report the smallest reproducible failure scenario for every material finding.
```

---

# 13. [BZJ-[PROJECT]][GOV-01-0004.07] — PROJECT FRAMEWORK INITIALIZATION

## 13.1 Purpose

Instantiate the approved project template in the actual development/governance environment.

## 13.2 Initialization controls

The project shall establish, as applicable:

- production repository;
- governance repository;
- project R&D folder;
- docs folder;
- branch strategy;
- AGENTS.md hierarchy;
- skills/extensions;
- ADR location;
- Decision Ledger;
- Task Packet storage;
- identifier registry;
- Authority Register;
- Authority–Permission Matrix;
- protected controls;
- CI policy;
- evidence storage;
- security/secret boundary;
- audit logging.

## 13.3 Agent roles

Roles are capabilities, not automatic authorities.

A typical ecosystem may include:

- **Lead Agent** — architecture, strategy, synthesis and orchestration.
- **Implementation Agent** — authorized implementation.
- **Reliability/Verification Agent** — independent verification and CI/reliability review.
- **Security/Challenge Agent** — adversarial challenge.
- **Human Project Authority** — project authorization.
- **Constitutional Authority** — governance-level authority where required.

Agents may be replaced or additional agents added. The role model must survive agent substitution.

## 13.4 Initialization prompt

```text
[Designated Target: Lead Agent + Authorized Project Operator]

Initialize the project framework using the verified project template.

Before changing any repository:
1. verify project identity;
2. verify authority;
3. verify repository ownership;
4. verify branch protection;
5. verify canonical governance locations;
6. verify identifier registry;
7. verify Task Packet mechanism;
8. verify security/secret boundary;
9. verify AGENTS.md precedence;
10. verify audit/evidence paths.

Record every initialization action.

If a required control is absent, do not silently substitute an informal mechanism. Enter the appropriate BLOCKED/NOT-READY state and escalate.
```

## 13.5 Authority–Permission Matrix

The matrix shall identify at minimum:

| Field | Requirement |
|---|---|
| Principal | Named human/service/agent identity |
| Authority role | H0/H1/H2/other mapped role |
| Technical identity | Actual account or service |
| Action | Specific capability |
| Scope | Repository/system/resource |
| Environment | Dev/test/staging/prod |
| Approval | Required authority |
| Verification | Required independent verifier |
| Expiration | Time or event bound |

Unauthorized capability shall produce:

```text
AUTHORITY-PERMISSION-DRIFT
```

## 13.6 Exit criteria

Framework initialization is complete only when the project can demonstrate:

- canonical sources;
- authority mapping;
- identifier control;
- repository control;
- protected instruction precedence;
- evidence path;
- Task Packet control;
- stop/reset path;
- security boundary.

---

# 14. [BZJ-[PROJECT]][GOV-02-0004.08] — PROJECT SIMULATION & READINESS EXERCISE

## 14.1 Purpose

Run the project framework without executing production work to demonstrate that governance mechanisms operate coherently.

## 14.2 Simulation scenarios

At minimum simulate:

1. normal task issuance;
2. Task Packet scope expansion;
3. requirement change;
4. risk reclassification;
5. conflicting agent recommendations;
6. AEV failure;
7. AEC failure;
8. human authority unavailability;
9. emergency delegation;
10. registry conflict;
11. AGENTS.md conflict;
12. evidence integrity failure;
13. unauthorized technical privilege;
14. dependency drift;
15. operational-readiness failure;
16. release authorization refusal.

## 14.3 Simulation prompt

```text
[Designated Target: Lead Agent / All Collaborating Agents]

Execute a dry-run of the initialized PBIM/project framework.

For each scenario:
- identify trigger;
- identify current state;
- identify authorized actor;
- identify expected transition;
- identify required evidence;
- identify stop condition;
- identify recovery;
- identify audit record.

Do not perform production changes.

The simulation is successful only if the expected governance result can be reproduced from the defined controls rather than from informal agent knowledge.
```

## 14.4 Operational-readiness model

Before project activation, the framework must define how it will later evaluate:

- monitoring;
- alerting;
- logging;
- incident ownership;
- rollback;
- data recovery;
- backup/restore;
- migration reversibility;
- security response;
- dependency availability;
- capacity;
- support ownership;
- documentation;
- user/stakeholder readiness.

Not every criterion must apply to every project, but applicability must be explicitly decided and evidenced.

## 14.5 Exit criteria

The simulation demonstrates that:

- governance states are deterministic;
- unauthorized work stops;
- material changes escalate;
- emergency paths are bounded;
- failure does not silently create new authority;
- evidence remains traceable;
- operational readiness is a distinct gate.

---

# 15. [BZJ-[PROJECT]][GOV-01-0004.09] — PBIM ACTIVATION & CHARTER READINESS

## 15.1 Purpose

Finalize the pre-charter governance package and determine whether the project is ready to enter formal project charter development.

## 15.2 Activation checklist

The Lead Agent shall verify:

- [ ] Project identity established.
- [ ] Project authority established.
- [ ] Constitutional authority mapped where required.
- [ ] Governance repository established.
- [ ] Identifier registry established.
- [ ] Authority–Permission Matrix established.
- [ ] Project proposal verified.
- [ ] Risk profile approved.
- [ ] Project template assembled.
- [ ] Mandatory controls classified.
- [ ] Security/secret boundary defined.
- [ ] Task Packet mechanism defined.
- [ ] Evidence model defined.
- [ ] Decision Ledger established.
- [ ] Stop/resume/reset controls defined.
- [ ] AEA/AEV/AEC/AECC status known.
- [ ] Simulation completed.
- [ ] Material blockers resolved or formally escalated.
- [ ] Charter inputs prepared.

## 15.3 Activation prompt

```text
[Designated Target: Lead Agent + Human Project Authority]

Determine whether PBIM may advance to Project Charter Development.

Review:
1. identity;
2. authority;
3. proposal;
4. risk;
5. architecture;
6. template;
7. repository/governance controls;
8. security boundary;
9. evidence;
10. Task Packet control;
11. simulation;
12. unresolved findings.

Return exactly one:
READY FOR CHARTER
READY WITH FORMAL CONDITIONS
BLOCKED
ARCHITECTURAL RESET

Do not convert unresolved assumptions into approvals.

For every condition or blocker:
- state the owner;
- evidence required;
- advancement impact;
- escalation authority.
```

## 15.4 PBIM activation state

PBIM may be marked:

```text
PBIM-READY-FOR-CHARTER
```

only when all mandatory pre-charter gates have passed.

`PBIM-READY-FOR-CHARTER` does not mean the project itself is approved. It means the project is sufficiently prepared to enter the formal charter process.

---

# 16. [BZJ-[PROJECT]][GOV-01-0004.1] — DEVELOP PROJECT CHARTER

## 16.1 PBIM integration boundary

This is the final PBIM identifier.

At this section:

```text
PBIM
  ↓
PROJECT CHARTER
  ↓
NORMAL PROJECT LIFECYCLE
```

PBIM no longer creates new `0004.xx` governance sections.

## 16.2 Charter-development prompt

```text
[Designated Target: Lead Agent + Human Project Authority]

Using the approved PBIM activation package, prepare the Project Charter.

The Charter must establish, as applicable:
- project purpose;
- business/organizational need;
- measurable objectives/outcomes;
- high-level requirements;
- high-level scope and exclusions;
- major deliverables;
- major milestones;
- assumptions and constraints;
- high-level risks;
- stakeholders;
- governance and authority;
- sponsor/project authority;
- project manager/lead;
- delivery approach;
- success criteria;
- acceptance authority;
- funding/resource authorization;
- major dependencies;
- security/privacy/regulatory obligations;
- approval/signature mechanism.

Trace each material Charter element to its PBIM source artifact or decision.

Do not introduce material facts that were not established through the PBIM process without recording them as new assumptions, decisions or Charter-stage inputs.

Once the Charter is authorized, transition the project to the project's ordinary lifecycle governance and PM process framework.
```

## 16.3 Charter acceptance gate

The Charter shall not be considered authorized merely because an agent generated it.

Required authority must approve it under the organization's governance model.

Upon authorization:

```text
PBIM STATUS = COMPLETE
PROJECT STATUS = CHARTERED / ACTIVE
PBIM IDENTIFIERS = TERMINATED AT 0004.1
```

---

# 17. TASK PACKET CONTROL

All implementation work performed after PBIM activation shall be issued through bounded Task Packets where the project governance model requires them.

A Task Packet should contain:

```text
Task Packet ID
Project ID
Authority
Objective
Scope
Out-of-Scope
Target Files/Resources
Dependencies
Required Evidence
Risk Profile
Acceptance Criteria
Verification Requirements
Stop Conditions
Expected Deliverables
Expiration / Validity
Issued Revision
```

Task Packets are immutable for the execution cycle unless formally superseded.

A Task Packet may not authorize work that contradicts the approved architecture, Charter, security controls or higher authority.

## 17.1 Scope enforcement

Where automation is available, CI should compare the actual change set against the Task Packet scope manifest.

The architecture may specify a canonical mechanism such as:

```text
.github/workflows/task-scope-check.yml
```

but the presence of that filename in PBIM is not evidence that the workflow exists or works.

---

# 18. CHANGE MATERIALITY

Requirement or architecture changes shall be classified before implementation.

A generic four-level model is:

| Level | Meaning | Required handling |
|---|---|---|
| `0` | Task-level, non-material | Task control |
| `1` | Limited change with possible baseline impact | AEV impact review |
| `2` | Material architecture/scope/security change | AEV + AEC |
| `3` | Load-bearing or constitutional change | Architectural Reset / CA process where applicable |

Classification must consider cumulative effects.

A sequence of individually low-risk changes may become material when their combined effect crosses a threshold.

Splitting one material change into multiple small changes must not defeat governance.

---

# 19. STOP, RESET AND EMERGENCY GOVERNANCE

## 19.1 Stop states

A project may use:

```text
S0 — RUNNING
S1 — ADVISORY
S2 — MANDATORY STOP
S3 — SYSTEM/SECURITY STOP
S4 — EMERGENCY SAFETY STOP
```

The exact names may be tailored, but the state semantics must be explicit.

## 19.2 Architectural Reset

Reset is appropriate when a load-bearing assumption fails.

A reset shall:

- identify the failed assumption;
- freeze affected work;
- preserve evidence;
- invalidate affected baseline state;
- identify the reset authority;
- return the architecture to AEA or the appropriate earlier stage;
- record the reset in the Decision Ledger.

A reset may not be used to erase adverse evidence.

## 19.3 Emergency delegation

Emergency delegation must be:

- explicit;
- bounded;
- auditable;
- scope-limited;
- time-limited;
- automatically expired where technically feasible.

A default TTL may be configured by the constitutional authority. The generic PBIM architecture should prefer the shortest period reasonably necessary.

Emergency action does not permanently bypass normal governance. Post-event reconciliation is mandatory.

---

# 20. SECURITY AND DATA BOUNDARY

Before implementation of a project with meaningful software/security impact, the project must define:

- what agents may access;
- what data they may process;
- what repositories may contain;
- credential-supply mechanism;
- secret storage;
- log redaction;
- production credential restrictions;
- privileged human actions;
- third-party/service access;
- evidence retention;
- security incident escalation.

Agents must not receive secrets merely because they are technically capable of using them.

Security controls must be integrated into the development lifecycle rather than added only at release.

---

# 21. FAILURE AND RECOVERY MODEL

## Agent unavailable

Do not silently combine an independent role with another role.

Record the role gap and determine whether:

- an authorized substitute exists;
- human review is required;
- the task stops.

## Conflicting analysis

Preserve both positions.

Reconcile using evidence.

Escalate unresolved material disagreement.

## Failed verification

Return to the appropriate prior stage.

Do not mark the artifact approved.

## Failed challenge

Amend and re-verify, or reset to AEA.

## Registry failure

Enter a blocked governance state if authoritative identifier state cannot be trusted.

Never substitute an unverified local copy as canonical.

## Evidence failure

If evidence provenance or integrity cannot be established, downgrade the decision state and require re-verification.

## Automation compromise

Automation is not inherently trusted.

A compromised validator, CI system or deployment mechanism must be capable of triggering a governance stop and independent review.

---

# 22. DRIFT CONTROL

The project shall periodically evaluate:

```text
APPROVED BASELINE
        ≠
IMPLEMENTED / CONFIGURED STATE
```

Classify divergence as:

- approved change;
- implementation defect;
- undocumented change;
- architectural drift;
- environment/dependency drift.

Undocumented material divergence triggers review.

Approval may also expire because of:

- material environment change;
- requirement change;
- dependency change;
- security incident;
- architecture change;
- organizational authority change;
- significant passage of time where the governance policy defines an expiry period.

---

# 23. DURABLE ARTIFACT REFERENCES

Authoritative governance references shall use durable objects such as:

- immutable commits;
- permanent tags;
- stable artifact IDs;
- versioned repository paths;
- controlled document revisions;
- integrity hashes where appropriate.

Do not rely on:

- ephemeral chat sessions;
- temporary agent workspaces;
- deletable review branches;
- transient execution IDs;
- temporary URLs.

Where an artifact is superseded, retain the supersession relationship.

---

# 24. IMPLEMENTATION-VERIFICATION OBLIGATIONS

PBIM architecture must explicitly defer implementation claims.

Typical obligations include:

1. Authority Register deployed.
2. Authority–Permission Matrix deployed.
3. Permission verifier operating.
4. Registry state store operating.
5. Registry integrity anchors operating.
6. Evidence integrity mechanism operating.
7. Challenge-independence controls operating.
8. AGENTS.md drift detection operating.
9. Task Packet scope enforcement operating.
10. Stop/reset enforcement operating.
11. Repository/branch protections operating.
12. Security/secret controls operating.
13. Operational-readiness gate operating.
14. Durable-reference enforcement operating.
15. Audit/evidence retention operating.

A PBIM document can specify these mechanisms without claiming they already exist.

---

# 25. AUTHORIZATION STATES

The following states should be used consistently:

```text
DRAFT
UNDER-AEA
AEV-CANDIDATE
AEV-APPROVED
AEC-IN-PROGRESS
AEC-BLOCKED
AEC-CLOSED
AECC-CANDIDATE
AECC-CLOSED
IMPLEMENTATION-NOT-AUTHORIZED
IMPLEMENTATION-AUTHORIZED
IMPLEMENTATION-VERIFIED
OPERATIONALLY-READY
RELEASE-AUTHORIZED
PRODUCTION
STOPPED
RESET-REQUIRED
SUPERSEDED
```

Not every project requires every state, but omitted states must be explicitly declared not applicable.

---

# 26. PBIM FINAL PRE-CHARTER GATE

Before `0004.1`, the Lead Agent shall answer:

```text
1. Who has authority?
2. What is the project?
3. Why does it exist?
4. What value/outcomes are expected?
5. What is in scope?
6. What is explicitly out of scope?
7. What is known versus assumed?
8. What are the major risks?
9. What security/privacy constraints apply?
10. What architecture is approved?
11. What evidence supports the architecture?
12. Has adversarial challenge occurred where required?
13. Are material findings closed?
14. Are technical permissions aligned with authority?
15. Is the identifier registry trustworthy?
16. Are Task Packets bounded?
17. Can the project stop safely?
18. Can it recover safely?
19. Can emergency authority be bounded?
20. Is the operating template ready?
21. Has the framework been simulated?
22. Is the project ready for Charter Development?
```

If any mandatory answer is unresolved:

```text
DO NOT ADVANCE TO 0004.1
```

unless the unresolved matter is explicitly classified as an authorized Charter-stage input rather than a pre-charter blocker.

---

# 27. LEGACY-TO-GENERIC STRUCTURE CROSSWALK

| v1.12 concept | Generic v2 disposition |
|---|---|
| `0004.01 PBIM Document Creation` | Retained and strengthened |
| `0004.02 PBIM Document Development` | Retained as `PBIM Architectural Engineering & Baseline` |
| Initial Project Template Generation Prompt | Moved into `0004.03` |
| `0004.03 Project Proposal Definition` | Retained and clarified |
| `0004.04 Project Proposal Development` | Retained as proposal engineering/verification |
| `0004.05 Project Template Creation` | Retained as template assembly |
| `0004.06 Project Template Development` | Retained as template engineering/readiness |
| `0004.07 Project Framework Initialization` | Retained and strengthened |
| `0004.08 Project Simulation` | Retained and expanded into readiness exercise |
| `0004.09 PBIM Implementation` | Renamed `PBIM Activation & Charter Readiness` to preserve the PBIM boundary |
| `0004.1 Develop Project Charter` | Retained as final PBIM integration boundary |
| “40/49 PMBOK processes” claim | Removed; replaced by classification-spine rule |
| Numeric ID as universal identity | Removed; identity dimensions separated |
| Agent role = authority | Removed; role/capability separated from authority |
| Documentation = enforcement | Removed; maturity boundary enforced |
| AEC independence by role label | Removed; I1–I4 independence required |
| Unbounded emergency authority | Removed; bounded, auditable delegation required |
| Informal/local copies as canonical state | Removed; canonical registry and durable references required |

---

# 28. PBIM OPERATING PRINCIPLES

1. **Authority precedes capability.**
2. **Specification does not equal implementation.**
3. **Evidence must be traceable to a credible source.**
4. **Material dissent must remain visible.**
5. **Challenge must attempt falsification, not confirmation.**
6. **Independence must be demonstrated, not declared.**
7. **Risk scaling reduces ceremony without weakening protected controls.**
8. **A technical administrator does not become a governance authority through access.**
9. **Identifiers classify; they do not replace project/work/artifact identity.**
10. **Emergency procedures are bounded exceptions, not permanent bypasses.**
11. **Cumulative change can become material even when individual changes appear small.**
12. **Operational readiness is distinct from implementation verification.**
13. **Release authorization is distinct from implementation authorization.**
14. **A failed control causes a governed stop or return, not silent continuation.**
15. **PBIM ends at the Project Charter boundary.**

---

# 29. CURRENT GENERIC BASELINE STATUS

This generic edition incorporates the architectural direction established by the supplied R1.5 AEV evidence, including:

- external constitutional authority;
- authority/permission separation;
- privileged-account review;
- serialized authority/permission changes;
- registry integrity and recovery controls;
- integrity-anchor requirements;
- evidence provenance;
- genuine challenge-independence requirements;
- Task Packet scope enforcement;
- machine-enforceable stop controls;
- bounded emergency delegation;
- cumulative risk/materiality;
- operational-readiness gating;
- baseline/dependency drift;
- durable references;
- protected instruction precedence;
- explicit implementation-verification obligations.

The supplied AEV responses record unconditional R1.5 AEV approvals from the collaborating review cycle and authorize a fresh R1.5 AEC. However, the supplied evidence set does **not** establish that a subsequent fresh R1.5 AEC and AECC closure have been completed. Therefore this document must not state that the PBIM architecture has reached final AECC closure or implementation authorization.

**Current generic disposition:**

```text
PBIM GENERIC ARCHITECTURE
= CONTROLLED CANDIDATE INCORPORATING R1.5 ARCHITECTURAL RESOLUTIONS

FRESH R1.5 AEC
= REQUIRED / STATUS MUST BE VERIFIED

AECC
= NOT CLAIMED CLOSED

IMPLEMENTATION
= NOT CLAIMED AUTHORIZED

PRODUCTION
= NOT CLAIMED AUTHORIZED
```

---

# 30. REQUIRED NEXT GOVERNANCE ACTIONS

Before treating this generic PBIM as an approved operating baseline:

1. Conduct the fresh adversarial challenge against the R1.5-derived architecture.
2. Ensure the challenger satisfies applicable independence requirements.
3. Preserve raw evidence and challenge provenance.
4. Resolve material findings.
5. Produce AECC closure.
6. Obtain the required constitutional/human authorization.
7. Instantiate the PBIM in a project-specific operating template.
8. Perform implementation verification of the controls explicitly deferred by the architecture.
9. Only then authorize implementation under the project's authority model.
10. Proceed to `0004.1 Develop Project Charter`.
11. Terminate PBIM identifiers at `0004.1`.

---

# 31. OFFICIAL PBIM INTEGRATION BOUNDARY

```text
[BZJ-[PROJECT]][GOV-01-0004.1]
DEVELOP PROJECT CHARTER

            ↓

PBIM COMPLETE
            ↓
PROJECT CHARTER AUTHORIZED
            ↓
NORMAL PROJECT GOVERNANCE
            ↓
PROJECT-SPECIFIC PM / DELIVERY LIFECYCLE
```

**PBIM does not continue beyond `0004.1`.**

Any subsequent process identifiers belong to the project's approved lifecycle and process-classification framework.

---

# 32. DOCUMENT CONTROL

| Field | Value |
|---|---|
| Document | Project Base Integration Manager |
| Generic version | v2.00.00 |
| Predecessor | v1.12.00 |
| Lifecycle boundary | `0004.1 Develop Project Charter` |
| Architecture model | Governance → Assurance → Execution |
| Assurance protocol | AEA → AEV → AEC → AECC |
| Maturity model | DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED |
| PM process treatment | Classification spine only |
| Primary authority | Project-defined H0 under applicable constitutional authority |
| High-Assurance challenge | I1/I2/I3/I4 independence required |
| Production authorization | Outside PBIM |
| Final PBIM artifact state | Controlled generic candidate pending required project/AECC authorization |

---

## END OF PROJECT BASE INTEGRATION MANAGER
