# PROJECT BASE INTEGRATION MANAGER [PBIM]

**Generic Edition:** v3.00.00  
**Document Class:** Generic Pre-Charter Project Governance, Integration, Assurance and Delivery Framework  
**Document Status:** Controlled Generic PBIM Candidate — probing document; not a project implementation authorization  
**Prepared Date:** 2026-10-07  
**Supersedes:** Historical PBIM v1.00.00–v2.00.00 reference family  
**Scope Boundary:** PBIM pre-charter lifecycle ending at `0004.1 Develop Project Charter`

---

## 0. DOCUMENT CONTROL

| Field | Value |
|---|---|
| PROJECT-NAME | `[PROJECT-FULL-NAME]` |
| PROJECT-BASE | `[PROJECT-BASE-NAME]` |
| BASE-ID | `[BASE-ID]` |
| PROJECT-ID | `[PROJECT-ID]` |
| PBIM-ID | `[PBIM-ID]` |
| DOCUMENT-REVISION | `v3.00.00` |
| DOCUMENT-OWNER | `[HUMAN AUTHORITY]` |
| LEAD-AGENT | `[LEAD AGENT]` |
| COLLABORATING-AGENTS | `[AGENT ROSTER]` |
| RISK-PROFILE | `LIGHT / STANDARD / HIGH-ASSURANCE` |
| DELIVERY-MODEL | `PREDICTIVE / ITERATIVE / ADAPTIVE / HYBRID / OTHER` |
| JURISDICTION | `[APPLICABLE JURISDICTION(S)]` |
| CANONICAL-SOURCE | `[DURABLE REFERENCE]` |
| INTEGRITY-ANCHOR | `[COMMIT / TAG / OBJECT / HASH, IF AVAILABLE]` |
| **EXPECTED-PROJECT-DURATION** | `Expected/provisional; determined during PBIM probing and project initialization` |
| **EXPECTED-PROJECT-START-DATE** | `Expected/provisional; TBD during PBIM probing` |
| **EXPECTED-PROJECT-END-DATE** | `Expected/provisional; TBD during PBIM probing` |
| IMPLEMENTATION-AUTHORIZATION | `NOT GRANTED BY THIS DOCUMENT` |
| PRODUCTION-AUTHORIZATION | `NOT GRANTED BY THIS DOCUMENT` |
| CHARTER-STATUS | `NOT YET DEVELOPED` |

### 0.1 Expected-duration rule

The three expected project schedule fields are deliberately provisional. PBIM is a probing and pre-charter framework; it shall not manufacture a committed schedule before the project proposal, scope, dependencies, resource assumptions and delivery model have been sufficiently established. An instantiated PBIM may replace the provisional values only with an explicitly classified **expected** estimate supported by evidence.

---

# 1. PURPOSE, SCOPE AND GOVERNANCE BOUNDARY

PBIM is the reusable pre-charter governance and integration framework used to establish a project as identifiable, authorized, risk-scaled, evidence-traceable and ready to enter ordinary project lifecycle management.

PBIM:

1. establishes project identity and governance boundaries;
2. distinguishes authority from technical capability;
3. establishes evidence and traceability controls;
4. develops and verifies the initial project proposal;
5. assembles and initializes a project operating template;
6. performs proportionate readiness and simulation checks;
7. prepares the project for formal Charter development.

PBIM is **not**:

- a Project Charter;
- a complete Project Management Plan;
- an implementation authorization;
- a production authorization;
- a legal opinion;
- a security certification;
- a substitute for organizational governance;
- a substitute for competent professional judgment.

The PBIM lifecycle ends at:

`[PROJECT-ID][GOV-01-0004.1] Develop Project Charter`

After that boundary, ordinary project governance becomes authoritative.

## 1.1 Governance → Assurance → Execution

```text
GOVERNANCE
    ↓
ASSURANCE
    ↓
AUTHORIZED EXECUTION
```

Governance establishes authority, constraints and decisions. Assurance evaluates whether the proposed controls and evidence are adequate. Execution occurs only within authorized scope.

## 1.2 Architectural assurance cycle

```text
AEA → AEV → AEC → AECC
```

- **AEA — Architectural Engineering Analysis:** examine coherence and load-bearing assumptions.
- **AEV — Architectural Engineering Verification:** verify that the proposed architecture specifies adequate controls and evidence.
- **AEC — Architectural Engineering Challenge:** deliberately attempt to invalidate the architecture.
- **AECC — Architectural Engineering Challenge Closure:** resolve or formally disposition material findings.

## 1.3 Control-state model

PBIM distinguishes:

```text
DESIGNED
   ↓
ENFORCEABLE
   ↓
ENFORCED
   ↓
INDEPENDENTLY VERIFIED
```

A written requirement is not proof of implementation. A successful design review is not proof of operational enforcement.

---

# 2. MODERN ALIGNMENT AND TAILORING

PBIM is intended to be compatible with contemporary project-management, systems-engineering, secure-development, DevSecOps, governance, evidence-management and operational-readiness practices.

It may be aligned to applicable editions of:

- PMI project-management guidance;
- ISO 21502 project-management guidance;
- applicable organizational governance policies;
- applicable jurisdictional legal/regulatory requirements;
- secure software-development guidance such as NIST SSDF where relevant;
- domain-specific engineering, safety, quality, privacy and security standards.

PBIM shall record the actual editions or organizational policies used by an instantiated project.

**Important:** referencing a standard does not establish compliance with that standard.

## 2.1 Delivery-model neutrality

The project shall explicitly select a delivery model:

`PREDICTIVE | ITERATIVE | ADAPTIVE | HYBRID | OTHER`

PBIM controls are tailored to the selected model. Tailoring may reduce ceremony but may not weaken protected authority, evidence, security, separation-of-duties or stop controls.

## 2.2 PM-process classification

Historical/local PM process lists may be retained as a **classification and traceability layer**.

They shall not be represented as:

- the sole project identifier;
- the sole task identifier;
- the sole document identifier;
- an implicit state machine;
- an assertion that a particular PM standard contains exactly that number of processes.

The semantic layers are:

```text
PROJECT ID
   ↓
PBIM SECTION ID
   ↓
WORK / TASK ID
   ↓
DOCUMENT / ARTIFACT ID
   ↓
LIFECYCLE STATE
   ↓
PM PROCESS / KNOWLEDGE-AREA CLASSIFICATION
```

---

# 3. PBIM IDENTIFIER ARCHITECTURE

## 3.1 Generic project identifier

```text
[BASE-ID]-[PROJECT-ID]
```

## 3.2 PBIM section identifier

```text
[PROJECT-ID][DOMAIN-XX-0004.XX]
```

Example:

```text
[BZJ-PGBD][GOV-01-0004.01]
```

The `0004.xx` namespace represents PBIM pre-charter positions. It is a local PBIM coordinate and is not a PM standard process identifier.

## 3.3 Identity separation

The following identifiers shall remain distinct:

- Project ID;
- PBIM Section ID;
- Prompt ID;
- Task Packet ID;
- Requirement ID;
- Evidence ID;
- Decision ID;
- ADR ID;
- Risk ID;
- Finding ID;
- Change ID;
- Release ID;
- Repository commit/object reference;
- Lifecycle state;
- PM-process classification.

No identifier shall be overloaded to mean all of these.

## 3.4 Generic artifact grammar

| Artifact | Generic pattern |
|---|---|
| Requirement | `REQ-####` |
| Evidence | `EVD-####` |
| Decision | `DEC-####` |
| ADR | `ADR-####` |
| Change | `CR-####` |
| Risk | `RSK-####` |
| Finding | `FND-####.##` |
| Task Packet | `TASK-####` |
| Release | `REL-####.#` |
| Prompt | `[SECTION-ID]-PROMPT-[ROLE]` |

## 3.5 Identifier Registry

The Identifier Registry is authoritative for allocation, status and supersession.

Allocation sequence:

```text
REQUEST
→ RESERVE
→ VALIDATE
→ COMMIT
→ VERIFY
→ CONFIRM
```

Concurrent conflicts, stale revisions, deletion, force-push, branch restoration or conflicting local copies shall not silently become authoritative.

---

# 4. GOVERNANCE, AUTHORITY AND SEPARATION OF DUTIES

## 4.1 Authority hierarchy

```text
CA — Constitutional / Organizational Constitutional Authority
 ↓
H0 — Primary Human Project Authority
 ↓
H1 — Delegated Human Authority
 ↓
H2 — Authorized Human Operational / Technical Authority
 ↓
Agents / Tools — Bounded capabilities
```

The labels may be mapped to real organizational roles.

Technical access does not create governance authority.

## 4.2 Agent roles

| Role | Responsibility |
|---|---|
| Lead Agent | synthesis, orchestration, evidence preservation and gate preparation |
| Analysis Agent | independent analysis |
| Verification Agent | evidence, traceability and reproducibility verification |
| Challenge Agent | adversarial challenge |
| Implementation Agent | executes authorized Task Packets |
| Test Agent | verifies implemented behavior and negative cases |
| Documentation Agent | identifiers, provenance and revision integrity |
| Operations/Release Agent | operational readiness, rollback and release evidence |

Agent composition may change. Governance authority must never be inferred from agent count, model prestige or tool access.

## 4.3 Separation-of-duties classes

For each assurance activity, the project shall select the required independence level:

- **I1 — Organizational Independence**
- **I2 — Evidence Independence**
- **I3 — Technical Independence**
- **I4 — Governance Independence**

For High-Assurance work, required independence must be evidenced rather than declared.

---

# 5. EVIDENCE, PROVENANCE, TRACEABILITY AND DECISIONS

## 5.1 Evidence classes

Every substantive claim shall be classified:

`FACT | INFERENCE | ASSUMPTION | PROPOSAL | RECOMMENDATION | RISK | UNKNOWN | DISPUTED`

Repetition or agent consensus does not upgrade evidence.

## 5.2 Decision record

Material decisions shall record:

- Decision ID;
- decision owner;
- authority;
- question;
- alternatives;
- evidence;
- decision;
- rationale;
- consequences;
- affected artifacts;
- effective revision;
- superseded decision, if any.

## 5.3 Traceability chain

```text
Need / Objective
→ Requirement
→ Decision
→ Architecture
→ Task Packet
→ Change
→ Verification
→ Readiness
→ Release / Handoff
```

Any material break shall be recorded and classified.

## 5.4 Canonical source and durable references

Each artifact class shall have one authoritative source.

A durable reference should identify an immutable or otherwise governed object, such as:

```text
ARTIFACT-ID @ COMMIT/TAG/OBJECT-ID # INTEGRITY-ANCHOR
```

A branch name alone is not sufficient evidence of immutability.

Superseded artifacts shall be retained and marked rather than silently overwritten.

---

# 6. RISK, MATERIALITY, STOP STATES AND CHANGE CONTROL

## 6.1 Risk profiles

| Profile | Intended use | Minimum posture |
|---|---|---|
| LIGHT | bounded, low-consequence work | proportionate ceremony; protected controls retained |
| STANDARD | normal project work | full material assurance controls |
| HIGH-ASSURANCE | financial, safety, security, regulated, sensitive-data, irreversible or high-blast-radius work | stronger independence, evidence and operational verification |

## 6.2 Cumulative materiality

Materiality shall be assessed across related tasks, dependencies, migrations, releases and concurrent changes. Several individually low-risk changes shall not be used to conceal a materially high-risk aggregate change.

## 6.3 Standard stop states

- `S1 ADVISORY-STOP` — warning; continuation requires recorded review.
- `S2 MANDATORY-STOP` — ambiguity or control deficiency; current step stops.
- `S3 SYSTEM-STOP` — verification, security, integrity or governance failure.
- `S4 EMERGENCY-SAFETY-STOP` — critical authority, legal, financial, data or safety violation.

Every stop records:

`trigger → timestamp → affected scope → authority → evidence → disposition → resume criteria`

A stop may not be self-cleared by the executor.

## 6.4 Emergency delegation

Emergency authority, if permitted, must have:

- explicit scope;
- named authority;
- start time;
- expiry/TTL;
- evidence requirement;
- post-event reconciliation.

Emergency action shall not permanently weaken the baseline.

## 6.5 Change control

Changes are classified by materiality.

Changes affecting constitutional authority, protected controls, assurance gates, evidence integrity or the PBIM architecture itself require an appropriate assurance cycle.

---

# 7. UNIVERSAL PROMPT ENGINEERING CONTRACT

This section merges the repeated prompt-engineering concepts found across the historical PBIM revisions into one reusable contract. Section-specific prompts shall add only the work unique to their section.

## 7.1 Mandatory prompt structure

Every operational PBIM prompt shall use:

1. `<<START Prompt N. {{Prompt Label}}>>`
2. `[Designation: Lead Agent / Collaborating Agents]`
3. `ROLE`
4. `OBJECTIVE`
5. `CONTEXT`
6. `<<START {{Resource Label}}>>` for supplied resources, where applicable
7. resource links/items
8. `<<STOP {{Resource Label}}>>`
9. `NOTES`, where applicable, below the resource block
10. `INSTRUCTIONS`
11. `METHOD`
12. `EVIDENCE CLASSIFICATION`
13. `OUTPUT STRUCTURE`
14. `STOP CONDITIONS`
15. `DECISION SET`
16. `<<STOP Prompt N. {{Prompt Label}}>>`

## 7.2 Prompt rules

Every prompt shall:

- identify the exact artifact or decision under review;
- identify the intended decision;
- identify the designated agent(s);
- provide relevant authoritative references;
- distinguish authoritative and non-authoritative references;
- prohibit fabrication of missing facts;
- require explicit uncertainty;
- preserve dissent;
- specify the applicable jurisdiction where material;
- require traceability;
- define acceptance criteria;
- define stop conditions and escalation;
- state what the agent is **not authorized** to do;
- distinguish specification from implementation evidence;
- use bounded decision options.

## 7.3 Universal response schema

Unless the section specifies a stricter schema, agents shall return:

```text
decision:
confidence:
scope_reviewed:
evidence:
assumptions:
unknowns:
findings:
materiality:
risks:
dissent:
required_changes:
verification:
acceptance_criteria:
stop_condition:
recommended_next_state:
```

Confidence is not a substitute for evidence.

---

# 8. PBIM PRE-CHARTER SECTION MAP

| ID | Section | Primary output | Gate |
|---|---|---|---|
| `0004.01` | PBIM Document Creation & Baseline Initialization | Controlled generic/project PBIM baseline | Resource completeness |
| `0004.02` | PBIM Architectural Engineering & Assurance | AEA/AEV/AEC/AECC evidence set | PBIM baseline readiness |
| `0004.03` | Project Context & Proposal Definition | Initial project proposal | Context completeness |
| `0004.04` | Project Proposal Engineering & Verification | Verified proposal | Proposal approval |
| `0004.05` | Project Template Assembly | Project operating-template skeleton | Assembly completeness |
| `0004.06` | Project Template Engineering & Readiness | Controlled project template | Template readiness |
| `0004.07` | Project Framework Initialization | Initialized project controls | Configuration integrity |
| `0004.08` | Project Simulation & Readiness Exercise | Readiness evidence | Operational readiness |
| `0004.09` | PBIM Activation & Charter Readiness | Activated pre-charter package | Charter readiness |
| `0004.1` | Develop Project Charter | Project Charter | **PBIM boundary** |

---

# 9. [PROJECT-ID][GOV-01-0004.01] — PBIM DOCUMENT CREATION & BASELINE INITIALIZATION

## Purpose

Create or update a generic PBIM from the complete historical/reference evidence set and establish a controlled baseline.

## Required inputs

- current PBIM;
- historical PBIM revisions;
- AEA query and reports;
- AEV revisions and responses;
- AEC challenge material and results;
- AECC closure material, if any;
- applicable external standards/policies;
- transition decisions from superseded PBIM versions.

## Required activities

1. identify every historical section and identifier;
2. identify repeated concepts and duplicated prompts;
3. identify obsolete, contradictory or overloaded concepts;
4. reconcile governance, engineering and project-management terminology;
5. preserve useful historical traceability;
6. establish the modern identifier model;
7. establish the universal prompt contract;
8. record superseded structures and migration decisions;
9. produce a generic project-independent baseline.

## Gate

`GATE 1 — PBIM BASELINE READINESS`

### <<START Prompt 1. PBIM Generic Baseline Synthesis>>  
[Designation: Lead Agent]

ROLE  
Act as the Lead Agent responsible for synthesizing the generic PBIM baseline.

OBJECTIVE  
Produce a modern, generic, implementation-ready PBIM probing document from the supplied historical and assurance evidence.

CONTEXT  
The PBIM is a pre-charter framework, not a project implementation authorization.

<<START PBIM Reference Package>>
1. Current and historical PBIM revisions.
2. AEA/AEV/AEC/AECC evidence, where available.
3. Applicable project-management and engineering standards.
4. Existing transition decisions.
<<STOP PBIM Reference Package>>

NOTES  
Treat historical material as evidence and design input, not as unquestionable authority. Do not invent missing project facts.

INSTRUCTIONS  
1. Compare every historical identifier, section, prompt, rule and workflow.
2. Identify at least two repeated/similar concepts and explicitly merge them where appropriate.
3. Separate project, task, document, lifecycle and PM-classification identities.
4. Preserve the architecture/implementation boundary.
5. Modernize obsolete standards claims.
6. Preserve material historical controls unless superseded with rationale.
7. Add expected-project-duration, expected-project-start-date and expected-project-end-date as provisional PBIM fields.
8. Produce a migration/supersession map.
9. Identify unresolved questions.

METHOD  
Use evidence classification and contradiction analysis before synthesis.

EVIDENCE CLASSIFICATION  
FACT / INFERENCE / ASSUMPTION / PROPOSAL / RECOMMENDATION / RISK / UNKNOWN / DISPUTED.

OUTPUT STRUCTURE  
1. Executive synthesis.
2. Historical comparison.
3. Merged concepts and rationale.
4. Identifier map.
5. Updated PBIM.
6. Supersession/migration map.
7. Open decisions.
8. Verification gates.
9. Authorization status.

STOP CONDITIONS  
Stop if authoritative sources conflict materially, required evidence is inaccessible, or the proposed baseline would silently weaken a protected control.

DECISION SET  
READY / READY-WITH-OBSERVATIONS / BLOCKED  
<<STOP Prompt 1. PBIM Generic Baseline Synthesis>>

### <<START Prompt 2. PBIM Baseline Independent Review>>  
[Designation: Collaborating Agents]

ROLE  
Act as independent reviewers of the proposed PBIM baseline.

OBJECTIVE  
Determine whether the baseline is coherent, generic, traceable, internally consistent and suitable for the next assurance stage.

<<START PBIM Baseline>>
[Attach the proposed PBIM and its durable references.]
<<STOP PBIM Baseline>>

INSTRUCTIONS  
Test identifiers, authority, evidence classes, prompt structure, section boundaries, standards claims, risk scaling, stop states and implementation boundaries. Hunt for contradictions and omissions rather than merely editing language.

OUTPUT STRUCTURE  
1. Scope reviewed.
2. Findings with IDs.
3. Evidence for each finding.
4. Materiality.
5. Dissent.
6. Required changes.
7. Decision.

STOP CONDITIONS  
If evidence cannot be independently reviewed, state the limitation. If required independence is unavailable, return CHALLENGE-BLOCKED.

DECISION SET  
APPROVE / APPROVE WITH CONDITIONS / RETURN FOR AMENDMENT / DISAPPROVE / CHALLENGE-BLOCKED  
<<STOP Prompt 2. PBIM Baseline Independent Review>>

---

# 10. [PROJECT-ID][SCP-04-0004.02] — PBIM ARCHITECTURAL ENGINEERING & ASSURANCE

## Purpose

Subject the PBIM baseline to AEA, AEV, AEC and AECC before it becomes the active generic baseline.

## Required sequence

```text
PBIM Draft
→ AEA Query
→ Independent AEA Reports
→ AEV Candidate
→ AEV Decisions
→ AEC Challenge
→ AEC Results
→ AECC
→ Controlled PBIM Baseline
```

### <<START Prompt 3. PBIM AEA Query>>  
[Designation: Lead Agent]

ROLE  
Act as the Lead Agent preparing the PBIM Architectural Engineering Analysis.

OBJECTIVE  
Analyze the PBIM as a governance and assurance system.

<<START PBIM AEA Resources>>
1. PBIM candidate.
2. Historical transition map.
3. Applicable standards/policies.
4. Existing findings and unresolved risks.
<<STOP PBIM AEA Resources>>

INSTRUCTIONS  
Analyze authority, permissions, identifiers, registry integrity, evidence provenance, role separation, AEA/AEV/AEC/AECC lifecycle, risk scaling, Task Packets, stop/reset, emergency delegation, instruction precedence, baseline/dependency drift, cumulative materiality, operational readiness, durable references and recovery.

For each finding identify:
`finding → evidence → impact → affected control → materiality → recommendation`.

Do not approve the architecture.

DECISION SET  
ANALYSIS COMPLETE / INCOMPLETE-BLOCKED  
<<STOP Prompt 3. PBIM AEA Query>>

### <<START Prompt 4. PBIM AEV Baseline Verification>>  
[Designation: Collaborating Agents]

ROLE  
Act as independent verification agents.

OBJECTIVE  
Determine whether the proposed PBIM baseline specifies sufficient authorities, boundaries, states, controls, evidence, security constraints, failure handling and readiness requirements.

<<START PBIM AEV Resources>>
1. PBIM AEA query.
2. Independent AEA reports.
3. PBIM baseline candidate.
4. Relevant evidence and registers.
<<STOP PBIM AEV Resources>>

INSTRUCTIONS  
Distinguish:
- specified;
- enforceable by design;
- implemented;
- independently verified.

Do not treat documentation as implementation evidence.

DECISION SET  
AEV APPROVE / AEV APPROVE WITH CONDITIONS / AEV RETURN / AEV BLOCK / ARCHITECTURAL RESET  
<<STOP Prompt 4. PBIM AEV Baseline Verification>>

### <<START Prompt 5. PBIM AEC Adversarial Challenge>>  
[Designation: Collaborating Agents]

ROLE  
Act as the authorized independent challenge function.

OBJECTIVE  
Attempt to invalidate the PBIM baseline.

<<START PBIM AEC Resources>>
1. Approved AEV candidate.
2. AEA findings.
3. Relevant architecture and governance artifacts.
4. Applicable risk profile.
<<STOP PBIM AEC Resources>>

INSTRUCTIONS  
Construct falsifiable attacks against hidden authority, privilege bypass, registry corruption, evidence manipulation, false independence, scope escape, protected-control weakening, instruction drift, emergency abuse, stop/reset bypass, cumulative materiality, dependency drift, approval expiry, operational-readiness bypass, rollback assumptions, human unavailability, compromised automation and durable-reference failure.

For each attack report:
`attack → expected control → weakness → severity/materiality → evidence → disposition`.

If independence is not genuine, return CHALLENGE-BLOCKED.

DECISION SET  
PASS / PASS-WITH-FINDINGS / MATERIAL-FINDING / BLOCKED / CHALLENGE-BLOCKED  
<<STOP Prompt 5. PBIM AEC Adversarial Challenge>>

### <<START Prompt 6. PBIM AECC Closure>>  
[Designation: Lead Agent / Authorized Human Closure Authority]

ROLE  
Close the PBIM assurance cycle without suppressing dissent.

OBJECTIVE  
Resolve or formally disposition every material AEA/AEV/AEC finding.

<<START PBIM AECC Resources>>
1. AEA reports.
2. AEV decisions.
3. AEC results.
4. Amendment revisions.
5. Verification evidence.
<<STOP PBIM AECC Resources>>

INSTRUCTIONS  
For every finding preserve the original finding, disposition, amendment, verification evidence, residual risk, owner and re-verification requirement. Do not treat majority consensus as a substitute for materiality-based closure.

OUTPUT  
1. Closure decision.
2. Final baseline revision.
3. Residual-risk register.
4. Implementation obligations.
5. Remaining dissent.
6. Authorization status.

STOP CONDITIONS  
Any unresolved material blocker, missing closure authority or failed required independence prevents AECC closure.

DECISION SET  
AECC CLOSED / AECC CLOSED WITH ACCEPTED RESIDUAL RISK / RETURN / BLOCKED  
<<STOP Prompt 6. PBIM AECC Closure>>

---

# 11. [PROJECT-ID][GOV-01-0004.03] — PROJECT CONTEXT & PROPOSAL DEFINITION

## Purpose

Translate the approved generic PBIM into a project-specific proposal without silently converting assumptions into requirements.

## Required project inputs

- project name and ID;
- base organization;
- problem/opportunity;
- strategic objective;
- intended outcomes/value;
- sponsor/authority;
- stakeholders;
- preliminary scope;
- constraints;
- assumptions;
- dependencies;
- target environments;
- data/security considerations;
- delivery model;
- risk profile;
- expected duration/start/end estimates;
- preliminary success criteria;
- legal/regulatory obligations.

### <<START Prompt 7. Project Context and Proposal Definition>>  
[Designation: Lead Agent]

ROLE  
Act as Lead Agent for project-specific PBIM instantiation.

OBJECTIVE  
Develop a controlled initial project proposal from the approved PBIM baseline.

<<START Project Context Resources>>
1. Approved PBIM baseline.
2. Project request/business case, if available.
3. Organizational policies.
4. Known technical/domain references.
<<STOP Project Context Resources>>

NOTES  
No missing fact may be invented. Expected schedule values remain provisional until sufficient evidence exists.

INSTRUCTIONS  
Classify each item as FACT / ASSUMPTION / PROPOSAL / UNKNOWN / DECISION REQUIRED. Establish project identity, authority map, stakeholders, objectives, preliminary scope, dependencies, risk profile, security/data classification, delivery model, agent roles, artifact map and preliminary success criteria.

OUTPUT STRUCTURE  
1. Project identity.
2. Authority and stakeholder map.
3. Objective/outcome model.
4. Scope boundaries.
5. Constraints/assumptions/dependencies.
6. Risk and materiality profile.
7. Security/data considerations.
8. Delivery model.
9. Expected duration/start/end.
10. Initial registers.
11. Proposed Charter inputs.
12. Open decisions.

STOP CONDITIONS  
Stop when authority is missing, a material requirement is ambiguous, or a proposal would require unauthorized implementation.

DECISION SET  
CONTEXT READY / READY WITH OPEN DECISIONS / BLOCKED  
<<STOP Prompt 7. Project Context and Proposal Definition>>

### <<START Prompt 8. Project Context Independent Review>>  
[Designation: Collaborating Agents]

ROLE  
Review the initial project proposal independently.

OBJECTIVE  
Identify contradictions, hidden scope, unsupported assumptions, missing authority and material risks.

<<START Project Proposal Resources>>
1. Initial project proposal.
2. Approved PBIM baseline.
3. Supporting business/technical evidence.
<<STOP Project Proposal Resources>>

INSTRUCTIONS  
Test strategic alignment, measurable outcomes, scope coherence, stakeholder impacts, feasibility, security/privacy, dependencies, resource assumptions, procurement/legal implications and operational consequences.

DECISION SET  
APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCKED  
<<STOP Prompt 8. Project Context Independent Review>>

---

# 12. [PROJECT-ID][GOV-01-0004.04] — PROJECT PROPOSAL ENGINEERING & VERIFICATION

## Purpose

Perform proposal-level AEA/AEV/AEC/AECC assurance before template assembly.

### <<START Prompt 9. Project Proposal AEA>>  
[Designation: Lead Agent]

Analyze the proposal for strategic alignment, value, scope, feasibility, security/privacy, dependencies, risk, resources, procurement, measurable success and operational implications. Identify contradictions and missing decisions.

<<START Project Proposal Resources>>
1. Controlled initial proposal.
2. PBIM baseline.
3. Supporting evidence.
<<STOP Project Proposal Resources>>

OUTPUT  
Findings, assumptions, unknowns, materiality, recommended amendments and AEA conclusion.

DECISION SET  
AEA COMPLETE / RETURN FOR MORE EVIDENCE / BLOCKED  
<<STOP Prompt 9. Project Proposal AEA>>

### <<START Prompt 10. Project Proposal AEV>>  
[Designation: Collaborating Agents]

Verify the proposal against the PBIM baseline. Check authority, scope, requirements quality, risk, evidence, security, feasibility, governance, stakeholder obligations and measurable outcomes.

Every condition must identify an owner, evidence requirement and advancement consequence.

DECISION SET  
APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCK / RESET  
<<STOP Prompt 10. Project Proposal AEV>>

### <<START Prompt 11. Project Proposal AEC>>  
[Designation: Collaborating Agents]

Attempt to invalidate the proposal. Attack business value, hidden scope, unowned authority, impossible constraints, underestimated risk, security/privacy gaps, stakeholder conflict, dependency failure, operational impossibility, legal/procurement contradiction and unmeasurable success criteria.

Do not optimize the proposal during the challenge.

DECISION SET  
PASS / MATERIAL-FINDING / BLOCKED  
<<STOP Prompt 11. Project Proposal AEC>>

### <<START Prompt 12. Project Proposal AECC>>  
[Designation: Lead Agent / Authorized Human Closure Authority]

Using the complete AEA/AEV/AEC package, resolve or disposition every material finding, preserve dissent and issue the controlled proposal baseline.

<<START Project Proposal Assurance Resources>>
1. AEA report.
2. AEV decision.
3. AEC results.
4. Amendments and verification evidence.
<<STOP Project Proposal Assurance Resources>>

DECISION SET  
CLOSED / CLOSED WITH RESIDUAL RISK / RETURN / BLOCKED  
<<STOP Prompt 12. Project Proposal AECC>>

---

# 13. [PROJECT-ID][GOV-01-0004.05] — PROJECT TEMPLATE ASSEMBLY

## Purpose

Assemble the project operating-template skeleton from the controlled proposal and PBIM baseline.

## Minimum template components

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
Architecture / ADR Index
Risk / Assumption / Issue Registers
Security Boundary
Data Classification
Task Packet Model
Decision Ledger
Evidence Model
Repository Model
Agent Instructions
Communication Model
Change Model
Verification Model
Operations / Readiness Model
Release / Handoff Model
```

### <<START Prompt 13. Project Template Assembly>>  
[Designation: Lead Agent]

Build the project-specific template skeleton using only approved proposal material and PBIM controls.

<<START Project Template Resources>>
1. AECC-closed project proposal.
2. Approved PBIM baseline.
3. Identifier registry.
4. Authority register.
5. Risk profile.
<<STOP Project Template Resources>>

INSTRUCTIONS  
Map every template component to an approved source. Mark unavailable information as UNKNOWN, NOT-APPLICABLE with rationale, or DECISION-REQUIRED. Do not fill mandatory fields with invented content.

OUTPUT  
Template skeleton, traceability map, unresolved fields, gate recommendation.

DECISION SET  
ASSEMBLY READY / READY WITH OPEN ITEMS / BLOCKED  
<<STOP Prompt 13. Project Template Assembly>>

### <<START Prompt 14. Project Template Assembly Review>>  
[Designation: Collaborating Agents]

Check completeness, traceability, identifier integrity, authority boundaries, separation of duties, security/data classification, Task Packet controls, evidence model and change controls.

Identify both missing controls and unnecessary ceremony.

DECISION SET  
APPROVE / CONDITIONAL / RETURN / BLOCKED  
<<STOP Prompt 14. Project Template Assembly Review>>

---

# 14. [PROJECT-ID][GOV-01-0004.06] — PROJECT TEMPLATE ENGINEERING & READINESS

## Purpose

Architecturally engineer and verify the assembled project template.

### <<START Prompt 15. Project Template Engineering>>  
[Designation: Lead Agent]

Evaluate whether the project template can safely govern the selected delivery model.

<<START Project Template Resources>>
1. Assembled project template.
2. PBIM baseline.
3. Approved proposal.
4. Risk profile.
<<STOP Project Template Resources>>

INSTRUCTIONS  
Analyze authority, permissions, identifiers, evidence, requirements traceability, task scope, security, change control, stop/reset, operational readiness, rollback, dependencies and handoff.

DECISION SET  
READY FOR INDEPENDENT VERIFICATION / RETURN / BLOCKED  
<<STOP Prompt 15. Project Template Engineering>>

### <<START Prompt 16. Project Template Independent Verification>>  
[Designation: Collaborating Agents]

Verify that the template is complete enough to support controlled project execution without confusing template readiness with implementation readiness.

Explicitly identify:
- specified controls;
- enforceable controls;
- controls requiring implementation;
- controls requiring independent operational verification.

DECISION SET  
APPROVE / CONDITIONAL / RETURN / BLOCK  
<<STOP Prompt 16. Project Template Independent Verification>>

---

# 15. [PROJECT-ID][GOV-01-0004.07] — PROJECT FRAMEWORK INITIALIZATION

## Purpose

Instantiate the verified project template without starting ordinary project execution.

## Initialization checklist

- project identity;
- jurisdiction;
- repositories;
- documentation/evidence locations;
- Identifier Registry;
- Human Authority Register;
- Authority–Permission Matrix;
- agent roster;
- instruction files;
- Task Packet mechanism;
- risk profile;
- decision ledger;
- registers;
- stop-state handling;
- security/data boundary.

## Task Packet minimum

Every material execution task shall define:

- Task ID;
- objective;
- scope;
- target files/resources;
- prerequisites;
- dependencies;
- acceptance criteria;
- required evidence;
- authorized executor;
- verifier;
- stop conditions;
- expected output;
- parent decision/ADR;
- expiry/supersession.

Task Packets may not be silently expanded.

### <<START Prompt 17. Project Framework Initialization>>  
[Designation: Lead Agent]

Initialize the project controls from the verified project template.

<<START Initialization Resources>>
1. Verified project template.
2. Authority Register.
3. Identifier Registry.
4. Risk profile.
5. Repository and environment references.
<<STOP Initialization Resources>>

INSTRUCTIONS  
Instantiate configuration without executing implementation work. Verify that each control has an owner, state and authoritative location. Ensure agent instructions cannot override protected controls.

OUTPUT  
1. Initialization manifest.
2. Registry status.
3. Authority/permission status.
4. Task Packet status.
5. Stop-state readiness.
6. Evidence index.
7. Exit-criteria test.

STOP CONDITIONS  
Stop on identity conflict, missing authority, registry conflict, inaccessible repository, protected-control mismatch or inability to reproduce configuration.

DECISION SET  
PROJECT-INITIALIZED / INITIALIZED-WITH-OBSERVATIONS / BLOCKED  
<<STOP Prompt 17. Project Framework Initialization>>

### <<START Prompt 18. Project Framework Initialization Verification>>  
[Designation: Collaborating Agents]

Independently verify that the initialized framework matches the approved template and that no implementation authorization has been inferred from configuration.

Test identifier integrity, authority, permissions, repository references, instruction precedence, Task Packet scope, evidence locations and stop controls.

DECISION SET  
VERIFY / VERIFY WITH CONDITIONS / RETURN / BLOCKED  
<<STOP Prompt 18. Project Framework Initialization Verification>>

---

# 16. [PROJECT-ID][OPS-05-0004.08] — PROJECT SIMULATION & READINESS EXERCISE

## Purpose

Exercise the initialized governance and control model before Charter transition.

Simulation shall be proportionate to risk and may use tabletop, dry-run, controlled test, scenario analysis or other suitable methods.

## Minimum scenarios

Where applicable:

1. normal Task Packet progression;
2. ambiguous requirement;
3. identifier collision;
4. failed verification;
5. material security defect;
6. emergency delegation;
7. unavailable human authority;
8. agent disagreement;
9. evidence loss/inaccessibility;
10. rollback/data-recovery scenario;
11. dependency/environment drift;
12. release/readiness failure.

### <<START Prompt 19. PBIM Readiness Simulation>>  
[Designation: Lead Agent]

Exercise the initialized project controls without performing production implementation.

<<START Readiness Resources>>
1. Initialized framework.
2. Task Packet model.
3. Stop-state model.
4. Authority/permission matrix.
5. Risk profile.
<<STOP Readiness Resources>>

INSTRUCTIONS  
Select scenarios proportionate to risk. Record expected control behavior, observed behavior, evidence, deviations, recovery actions and residual risks.

OUTPUT  
Scenario matrix, observed results, control failures, corrective actions, readiness recommendation.

DECISION SET  
READY / READY WITH CONDITIONS / NOT READY / BLOCKED  
<<STOP Prompt 19. PBIM Readiness Simulation>>

### <<START Prompt 20. PBIM Readiness Challenge>>  
[Designation: Collaborating Agents]

Independently challenge the simulation evidence. Determine whether the exercise actually demonstrated readiness or merely demonstrated that documentation exists.

Focus on negative paths, stop enforcement, authority conflicts, evidence integrity, recovery, human unavailability and operational handoff.

DECISION SET  
ACCEPT / ACCEPT WITH CONDITIONS / REPEAT SIMULATION / BLOCKED  
<<STOP Prompt 20. PBIM Readiness Challenge>>

---

# 17. [PROJECT-ID][GOV-01-0004.09] — PBIM ACTIVATION & CHARTER READINESS

## Purpose

Assemble the final pre-charter package and determine whether the project is ready to enter Charter development.

PBIM activation does **not** authorize production implementation.

### <<START Prompt 21. PBIM Activation and Charter Readiness>>  
[Designation: Lead Agent / Human Authority]

Determine whether all pre-charter gates have been satisfied.

<<START Charter Readiness Resources>>
1. Approved PBIM baseline and AECC.
2. Approved project proposal and proposal AECC.
3. Verified project template.
4. Initialized project framework.
5. Readiness simulation evidence.
6. Open risk/decision registers.
<<STOP Charter Readiness Resources>>

INSTRUCTIONS  
Verify:
- project identity;
- authority;
- jurisdiction;
- scope boundaries;
- risk profile;
- proposal baseline;
- template readiness;
- initialized controls;
- readiness evidence;
- residual risks;
- open decisions;
- expected project schedule fields;
- explicit non-authorizations.

No unresolved material blocker may be hidden by aggregate scoring.

OUTPUT  
1. Gate checklist.
2. Evidence index.
3. Residual-risk statement.
4. Open-decision statement.
5. Charter input package.
6. Authorization boundary.
7. Recommended next state.

DECISION SET  
CHARTER-READY / CHARTER-READY-WITH-CONDITIONS / RETURN / BLOCKED  
<<STOP Prompt 21. PBIM Activation and Charter Readiness>>

### <<START Prompt 22. PBIM Charter Readiness Independent Review>>  
[Designation: Collaborating Agents]

Review the complete pre-charter package and determine whether Charter development is justified.

Do not approve production implementation. Confirm that unresolved issues are visible and correctly owned.

DECISION SET  
READY / READY WITH CONDITIONS / RETURN / BLOCKED  
<<STOP Prompt 22. PBIM Charter Readiness Independent Review>>

---

# 18. [PROJECT-ID][GOV-01-0004.1] — DEVELOP PROJECT CHARTER

## PBIM boundary

This is the final PBIM coordinate.

The Project Charter is developed under the project's ordinary lifecycle and governance framework using the PBIM output package.

### <<START Prompt 23. Develop Project Charter>>  
[Designation: Lead Agent / Human Project Authority]

Using the approved PBIM pre-charter package, develop the Project Charter under the applicable organizational project-governance framework.

<<START Charter Input Resources>>
1. PBIM Charter Readiness package.
2. Approved project proposal.
3. Approved project template.
4. Authority and stakeholder registers.
5. Risk/decision registers.
6. Applicable organizational/project-governance policies.
<<STOP Charter Input Resources>>

NOTES  
PBIM ends at this handoff. Do not extend PBIM identifiers into ordinary project execution.

INSTRUCTIONS  
Produce a Charter that clearly states authority, objectives, scope, outcomes, governance, major assumptions/constraints, high-level risks, funding/resource authority where applicable, delivery approach, success criteria and authorization boundaries.

DECISION SET  
CHARTER DRAFT / RETURN FOR AMENDMENT / BLOCKED  
<<STOP Prompt 23. Develop Project Charter>>

---

# 19. CROSS-CUTTING REGISTERS AND CONTROLLED ARTIFACTS

Every instantiated PBIM should establish, where applicable:

1. Canonical Source Manifest.
2. Identifier Registry.
3. Human Authority Register.
4. Authority–Permission Matrix.
5. Control State Register.
6. Requirements Traceability Matrix.
7. Decision Ledger.
8. Risk Register.
9. Assumption Register.
10. Issue/Finding Register.
11. Evidence Index.
12. ADR Index.
13. Change Register.
14. Task Packet Register.
15. Readiness Register.
16. Residual-Risk Register.
17. Supersession Map.

---

# 20. GATES AND ADVANCEMENT RULES

A gate is passed only when its required evidence exists and its decision authority is satisfied.

A gate shall not be passed because:

- a majority of agents agree;
- a document looks complete;
- a deadline is approaching;
- a branch contains the expected files;
- an agent reports high confidence;
- a later stage could theoretically fix the problem.

Material blockers remain blockers until resolved or formally accepted by the correct authority.

## 20.1 Reset rule

A failed assurance activity returns the work to the appropriate amendment stage.

An Architectural Reset to AEA is required when a load-bearing assumption, authority boundary, security model, identifier model or other foundational premise fails.

---

# 21. LEGACY-TO-MODERN CONSOLIDATION RULES

This generic PBIM explicitly merges and supersedes repeated concepts found across the supplied PBIM family.

## 21.1 Repeated prompt instructions → Universal Prompt Engineering Contract

Historical revisions repeatedly reproduced substantially identical Lead and Collaborating Agent instructions: preserve identifiers, classify evidence, do not invent facts, verify exit criteria, record findings, apply stop conditions and return bounded decisions.

**Modern treatment:** these are now defined once in Section 7 and each operational prompt adds only section-specific instructions.

## 21.2 Authority + permissions + agent roles → Authority–Permission Model

Historical documents separately discussed human authority, agent roles, approval rights, technical privileges and separation of duties.

**Modern treatment:** these are integrated into the Authority Register, Authority–Permission Matrix and independence classes.

## 21.3 Evidence + provenance + durable links → Evidence Integrity Model

Repeated concepts around evidence classification, canonical documents, branch links, hashes, commit references and supersession are consolidated into one Evidence/Provenance model.

## 21.4 AEA/AEV/AEC/AECC repetition → Reusable Assurance Pipeline

The four-stage assurance cycle is retained, but its common controls are centralized. Each project-specific section invokes the same pipeline instead of redefining it.

## 21.5 Project duration/date fields

Historical documents sometimes treated project schedule fields as concrete project commitments. This generic edition treats:

- `EXPECTED-PROJECT-DURATION`
- `EXPECTED-PROJECT-START-DATE`
- `EXPECTED-PROJECT-END-DATE`

as provisional probing estimates until evidence supports an instantiated expectation.

## 21.6 Historical PM process counts

Historical fixed process-count claims are not normative PBIM architecture. Any legacy mapping is retained only for compatibility and traceability.

---

# 22. QUALITY, SECURITY AND ENGINEERING PRINCIPLES

The instantiated project shall tailor, where relevant:

- least privilege;
- separation of duties;
- defense in depth;
- secure defaults;
- privacy/data minimization;
- dependency and supply-chain control;
- reproducible builds/evidence where practical;
- automated verification where reliable;
- change traceability;
- rollback/recovery planning;
- observability and operational readiness;
- accessibility and usability;
- resilience and failure-mode analysis;
- quality assurance;
- incident and exception handling.

PBIM shall not prescribe a technology merely because a historical project used it.

---

# 23. MAINTENANCE OF PBIM

PBIM maintenance is triggered by:

- material changes in applicable law or regulation;
- material changes in project-management standards;
- major engineering/security practice changes;
- repeated governance failures;
- AEC blockers;
- lessons learned with systemic implications;
- changes in constitutional authority;
- significant changes in agent/tool capabilities or permissions;
- new project classes requiring different assurance.

Maintenance shall follow:

```text
CHANGE PROPOSAL
→ AEA
→ AEV
→ AEC
→ AECC
→ NEW BASELINE
→ SUPERSESSION
```

The active baseline shall never be silently modified.

---

# 24. FINAL PRE-CHARTER CHECKLIST

Before `CHARTER-READY`, confirm:

- [ ] Project identity established.
- [ ] Base identity established.
- [ ] Applicable jurisdiction identified.
- [ ] Human authority identified.
- [ ] Authority–Permission Matrix established.
- [ ] Agent roles and independence requirements established.
- [ ] Risk profile selected.
- [ ] Delivery model selected.
- [ ] Expected duration/start/end recorded as provisional expectations.
- [ ] Canonical Source Manifest established.
- [ ] Identifier Registry established.
- [ ] Evidence classification established.
- [ ] Traceability model established.
- [ ] PBIM AEA completed.
- [ ] PBIM AEV completed.
- [ ] PBIM AEC completed.
- [ ] PBIM AECC completed.
- [ ] Project proposal completed.
- [ ] Project proposal assurance completed.
- [ ] Project template assembled.
- [ ] Project template verified.
- [ ] Project framework initialized.
- [ ] Task Packet controls established.
- [ ] Stop/reset controls exercised.
- [ ] Readiness simulation completed.
- [ ] Residual risks recorded.
- [ ] Open decisions recorded.
- [ ] No material blocker is concealed.
- [ ] Charter input package prepared.
- [ ] PBIM boundary at `0004.1` preserved.

---

# 25. STATUS AND AUTHORIZATION STATEMENT

This document is a **generic PBIM probing and pre-charter framework**.

Its existence does not:

- authorize implementation;
- authorize production deployment;
- approve a project;
- establish legal compliance;
- establish security certification;
- establish operational readiness.

Authorization exists only where the applicable human authority, governance framework and evidence records explicitly establish it.

**Current generic status:** `CONTROLLED GENERIC PBIM CANDIDATE — NOT AN IMPLEMENTATION AUTHORIZATION`

