# PROJECT BASE INTEGRATION MANAGER [PBIM]

**DOCUMENT ID:** `[KOWARE-IAPD-PMO]-[BASE]-[PROJECT]-PBIM`
**DOCUMENT REVISION:** `R4.0 — Generic Implementation-Ready Consolidated Operating Model`
**FILE VERSION:** `v1.04.00-generic`
**DATE:** 2026-10-04
**DOCUMENT CLASS:** Reusable project-management, governance and software-engineering operating model
**STATUS:** CONSOLIDATED IMPLEMENTATION CANDIDATE — MANUAL UPDATE REQUIRED
**ARCHITECTURAL STATUS:** DESIGNED — NOT YET ENFORCEABLE, ENFORCED OR INDEPENDENTLY VERIFIED
**IMPLEMENTATION AUTHORIZATION:** NOT GRANTED
**PRODUCTION AUTHORIZATION:** NOT GRANTED
**AECC CLOSURE:** PENDING FOR THIS REVISION

---
## 0. PURPOSE, SCOPE AND AUTHORITY

This generic PBIM defines the controlled operating model used to initialize a project, develop its proposal, generate its project-specific management/engineering template, initialize the working environment, and enter formal project charter execution.

It is **not** a project charter, project plan, architecture approval, implementation authorization, production authorization, or substitute for human governance.

### 0.1 Instantiation

Replace only the declared tokens `[BASE]`, `[PROJECT]`, `[DOCUMENT-ID]`, authority identities, repository references, risk profile and project-specific parameters during manual instantiation. Generic governance rules SHALL NOT inherit example-specific technology, paths, vendors, databases, branches or product assumptions.

### 0.2 Standards alignment

This PBIM uses PMI's current PMBOK Guide — Eighth Edition as a modern reference point for principles, performance domains, tailoring and reintroduced non-prescriptive process guidance. The PBIM's 49 lifecycle positions are a **local PBIM lifecycle-coordinate model**, derived from the historical project workflow; they are not claimed to be 49 canonical PMBOK 8 processes. ISO 21502:2020 is used as a complementary project-management guidance reference, with tailoring for predictive, iterative, adaptive or hybrid delivery. The ISO 21502 revision is currently under development, so the instantiated project should record the applicable edition/reference at initialization.

### 0.3 Governing hierarchy

`CA → Human Governance → Approved PBIM Baseline → Approved Project Proposal → Approved Project Template → Task Packet / Section Instruction → Local Agent Instructions`

A lower-level instruction cannot weaken or override a higher-level protected control.

---
# 1. DOCUMENT CONTROL

| Field | Value |
|---|---|
| Base | `[BASE]` |
| Project | `[PROJECT]` |
| PBIM ID | `[KOWARE-IAPD-PMO]-[BASE]-[PROJECT]-PBIM` |
| Revision | `R4.0` |
| Parent | `{{previous PBIM revision/reference}}` |
| Owner | `{{human authority}}` |
| Lead Agent | `{{designated lead agent}}` |
| Collaborating Agents | `{{roster}}` |
| Risk Profile | `LIGHT / STANDARD / HIGH-ASSURANCE` |
| Canonical Source | `{{durable reference}}` |
| Integrity Anchor | `{{hash / object / tag}}` |
| Status | `MANUAL UPDATE REQUIRED` |

### 1.1 State vocabulary

`DESIGNED` = control specified but not yet demonstrated. `ENFORCEABLE` = mechanism exists and can prevent/permit the declared outcome. `ENFORCED` = enforcement has been demonstrated in the applicable environment. `INDEPENDENTLY VERIFIED` = a suitably independent verifier has verified operation. No document may claim a higher state without evidence in the Control State Register.

---
# 2. PBIM OPERATING PRINCIPLES
1. **P1.** Single authoritative source per artefact class.
2. **P2.** One identifier grammar with registry-backed allocation.
3. **P3.** Separation of duties: author, approver, executor, verifier and challenger are distinct where required by risk.
4. **P4.** Evidence before assertion; unverified claims are never upgraded by confidence or consensus.
5. **P5.** Design, enforceability, enforcement and independent verification are distinct control states.
6. **P6.** Material dissent is preserved; consensus does not erase minority findings.
7. **P7.** Revision is bounded; failed convergence triggers reset/restart rather than endless editing.
8. **P8.** Tailoring may reduce ceremony only where risk permits; it may not weaken protected controls.
9. **P9.** Important controls and stop conditions should be machine-checkable where technically feasible.
10. **P10.** Authoritative references must be durable and survive branch/workspace changes.
11. **P11.** No empty sections or silent placeholders.
12. **P12.** Failure stops progression without erasing history.

---
# 3. GOVERNANCE, ROLES AND SEPARATION OF DUTIES

### 3.1 Constitutional Authority (CA)
`CA` is the external authority that defines the constitutional boundary of the operating model. CA is not created by PBIM and PBIM cannot amend its own constitutional boundary.

### 3.2 Human authorities
| Role | Responsibility |
|---|---|
| H0 | Primary human authority; grants material approvals and resolves escalation |
| H1 | Delegated deputy within explicitly recorded authority limits |
| H2 | Human project/operational participant without authority beyond the delegation register |

### 3.3 Agent roles
| Role | Primary responsibility |
|---|---|
| Lead Agent | Consolidation, orchestration, evidence preservation, gate preparation |
| Analysis Agent | Independent architectural/technical analysis |
| Verification Agent | Evidence sufficiency, traceability and reproducibility |
| Challenge Agent | Adversarial attack of architecture and controls |
| Implementation Agent | Executes approved Task Packets; does not approve its own work |
| Test Agent | Proves implemented behavior, including negative/stop tests |
| Documentation Agent | Identifier, revision, provenance and decision integrity |
| Operations/Release Agent | Operational readiness, monitoring, rollback, support and release evidence |

### 3.4 Separation of duties
The required independence class SHALL be selected according to risk. An agent cannot establish its own independence merely by declaring it. Shared credentials, shared decision rights, shared incentives, prior authorship and shared evidence stores SHALL be disclosed.

---
# 4. IDENTIFIER ARCHITECTURE

### 4.1 Primary section identifier

`[BASE]-[PROJECT]-[LIFECYCLE-POSITION]`

Example: `[BASE]-[PROJECT]-1005.2`.

### 4.2 Decimal insertion rule
Custom engineering sections MAY use decimal suffixes between existing lifecycle positions. They SHALL sort strictly between their declared neighbors, SHALL NOT renumber an existing position, SHALL be registered before use, and SHALL contain substantive content.

### 4.3 Sub-item rule
Sub-items extend the parent using fixed-width groups: `[0004.01] → [0004.0101] → [0004.010101]`.

### 4.4 Prompt identifiers
Prompt artefacts use `[SECTION]-PROMPT-LEAD`, `[SECTION]-PROMPT-COLLAB`, `[SECTION]-PROMPT-VERIFY`, etc. Prompt identifiers SHALL never be confused with lifecycle positions.

### 4.5 Artefact classes
| Class | Pattern |
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

### 4.6 Identifier Registry
Allocation sequence: `REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM`. Registry writes are serialized. Conflicts SHALL stop the workflow; last-write-wins is prohibited.

---
# 5. EVIDENCE, PROVENANCE AND DURABLE REFERENCES

### 5.1 Evidence classification
Every substantive claim SHALL be classified as one of: `VERIFIED FACT`, `INFERENCE`, `ASSUMPTION`, `PROPOSAL`, `RISK`, `UNKNOWN`.

An UNKNOWN requires an owner and target date. A missing fact must never be silently converted into an assumption.

### 5.2 Canonical Source Manifest
For each artefact class, identify exactly one authoritative source. Supporting copies are evidence, not competing authorities.

### 5.3 Durable reference
Authoritative references SHALL survive branch deletion, workspace destruction and branch rewriting. Minimum form:

`{{artifact-id}} @ {{immutable-commit/tag/object-id}} # {{integrity-hash}}`

Branch names alone are non-authoritative.

### 5.4 Supersession
Superseded artefacts are retained and explicitly marked. They are never silently overwritten.

---
# 6. RISK, MATERIALITY AND TAILORING

### 6.1 Profiles
| Profile | Typical use | Minimum posture |
|---|---|---|
| LIGHT | Low consequence documentation/internal tooling | Proportionate ceremony; protected controls retained |
| STANDARD | Normal product/software work | Full assurance for material artefacts |
| HIGH-ASSURANCE | Security, finance, safety, infrastructure, sensitive data or high consequence | Full assurance plus stronger independence, evidence and operational verification |

Tailoring may reduce ceremony but SHALL NOT weaken protected controls, evidence rules, separation of duties or stop enforcement.

### 6.2 Cumulative risk
Risk is assessed across related tasks, dependencies, migrations, releases and concurrent work. Multiple individually low-risk changes must not conceal a materially high-risk aggregate change.

---
# 7. ASSURANCE PIPELINE

`AEA → AEV → AEC → AECC`

| Stage | Purpose |
|---|---|
| AEA | Independent architectural analysis against a defined query |
| AEV | Controlled verification baseline candidate and all-agent decision |
| AEC | Adversarial attempt to break the proposed architecture |
| AECC | Evidence-based closure, residual-risk disposition and gate authorization |

### 7.1 Revision cap
AEV revisions are capped at `1.4` within a cycle. If approval is not achieved by the cap, the cycle SHALL restart from the complete evidence set rather than continue editing the same artefact indefinitely.

### 7.2 Rejection
A single material disapproval is not erased by majority consensus. Blocking findings are resolved and the revised AEV is re-shared to the complete collaborating set.

### 7.3 Adversarial requirement
AEC must attack the architecture, not merely improve wording. The challenger must construct concrete failure scenarios and test load-bearing assumptions.

---
# 8. STOP, RESET AND EMERGENCY CONTROLS

Progress SHALL stop when a protected control is bypassed, evidence is materially missing, independence fails, an identifier conflicts, a gate cannot be reached, a material finding is unresolved, or an actor is instructed to exceed authority.

A stop cannot be self-cleared by the executor. Resume requires the authority defined by the applicable gate.

Emergency authority, if permitted, must have an explicit scope, TTL, evidence requirement and post-event reconciliation. Emergency action does not permanently weaken the baseline.

---
# 9. CHANGE CONTROL

Changes are classified by materiality and routed through the appropriate authority. Changes affecting constitutional authority, protected controls, evidence integrity or assurance gates require a fresh PBIM assurance cycle. Approved changes receive a new revision and durable reference.

---
# 10. TASK PACKET CONTROL

Every implementation Task Packet SHALL define: objective; requirements; direct file scope; generated-file scope; dependency scope; configuration/schema/infrastructure scope; external effects; required tests; required evidence; executor; approval authority; verification authority; machine-readable scope manifest; and supersession state.

A Task Packet cannot authorize work outside its declared scope. Derived effects must be assessed before execution.

---
# 11. PROMPT ENGINEERING CONTRACT

Every operational prompt SHALL contain these blocks:

ROLE
CONTEXT
OBJECTIVE
INPUTS / REFERENCES
CONSTRAINTS
METHOD
EVIDENCE CLASSIFICATION
OUTPUT STRUCTURE
STOP CONDITIONS
DECISION SET

Prompts must:
1. Identify the designated agent or explicitly state ALL AGENTS.
2. Attach the minimum sufficient evidence set with labelled START/STOP blocks.
3. Use durable references for authoritative artefacts.
4. Tell the agent what it must not infer or invent.
5. Require explicit treatment of inaccessible or unverifiable material.
6. Separate facts, inferences, assumptions, proposals, risks and unknowns.
7. Require findings to include evidence, impact, affected control and proposed disposition.
8. Require independence disclosure where the agent is reviewing/challenging another artefact.
9. End with a bounded decision set rather than open-ended prose.
10. Never grant authority merely by instruction text.

### 11.1 Lead-agent emphasis
Consolidation, evidence preservation, dissent retention, revision control, stop discipline, gate preparation and explicit human authorization boundaries.

### 11.2 Collaborating-agent emphasis
Independent reasoning first; contradiction hunting; evidence classification; disclosure of inaccessible evidence; no consensus-based approval.

### 11.3 Verification-agent emphasis
Traceability, provenance, reproducibility, baseline identity, control-state evidence and evidence sufficiency.

### 11.4 Challenge-agent emphasis
Unauthorised authority, hidden privilege, bypass, evidence manipulation, registry failure, scope escape, stop bypass, emergency abuse, reset evasion, false independence and false closure.

---
# 12. PRE-CHARTER PBIM SECTIONS

The following nine sections execute before formal Project Charter Development. They are the implementation-ready core requested by this generic PBIM.

## [BASE]-[PROJECT]-0004.01 — PBIM Development & Architectural Initialization

**Purpose.** Establish the project-specific PBIM baseline: identity, governance boundary, human authority, agent roster, evidence model, risk profile, lifecycle coordinate model, assurance pipeline, registers, protected controls, stop conditions, and implementation boundary.

**Inputs.**
- Current manually updated PBIM
- Compiled historical PBIM evidence set
- Human governance decisions
- Project/base tokens

**Required activities.**
1. Resolve BASE/PROJECT/document identity
2. Establish constitutional and human authority
3. Create Canonical Source Manifest and Identifier Registry
4. Set risk/materiality profile and evidence rules
5. Declare protected controls, stop/reset rules and independence model
6. Anchor and durably reference the baseline

**Outputs.** Durable PBIM baseline; source manifest; authority register; permission matrix; identifier registry; control-state register; risk decision; RTM skeleton

**Exit criteria.** Every material control has an owner and state; all identifiers resolve; canonical sources are resolved; durable reference exists; human authority authorizes AEA preparation.

**Gate.** GATE 1 — PBIM INITIALIZATION

### Prompt `[BASE]-[PROJECT]-0004.01-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.01 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.01 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.01-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.01 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.02 — Initial Project Proposal & Template-Generation Prompt

**Purpose.** Convert the approved PBIM into a controlled proposal/template-generation instruction set without inventing project architecture or granting implementation authority.

**Inputs.**
- AECC-closed PBIM baseline
- RTM skeleton
- Risk profile
- Project identity

**Required activities.**
1. Extract authorized objectives/constraints
2. Define proposal outputs and acceptance criteria
3. Map requirements to RTM
4. Define project-template generation rules and non-authorizations
5. Create durable controlled prompt

**Outputs.** Controlled Initial Project Proposal / Template Generation Prompt; traceability map

**Exit criteria.** Every proposal instruction is traceable to PBIM; no implementation authorization is embedded; all gates and stop conditions remain intact.

**Gate.** Feeds GATE 2 — PROPOSAL AEA

### Prompt `[BASE]-[PROJECT]-0004.02-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.02 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.02 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.02-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.02 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.03 — Architectural Engineering of Project Proposal

**Purpose.** Apply the AEA → AEV → AEC → AECC assurance pipeline to the proposal instruction set.

**Inputs.**
- 0004.02 manually reviewed subject
- PBIM baseline
- AEA/AEV/AEC procedures

**Required activities.**
1. Lead coherence review
2. Issue AEA query
3. Collect independent AEA reports
4. Consolidate controlled AEV candidate
5. Obtain all-agent AEV decision
6. Run adversarial AEC
7. Resolve findings and close AECC

**Outputs.** AEA query; AEA findings register; AEV baseline candidate; decisions; AEC challenge/results; AECC closure

**Exit criteria.** No unresolved blocking finding; independence requirements satisfied; all material findings have evidence and disposition; closure authority named.

**Gate.** GATE 3 — PROPOSAL AECC

### Prompt `[BASE]-[PROJECT]-0004.03-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.03 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.03 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.03-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.03 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.04 — Project Proposal Baseline & Approval

**Purpose.** Record the approved project proposal as a controlled baseline and explicitly preserve dissent, residual risk, assumptions, and non-authorizations.

**Inputs.**
- AECC closure from 0004.03
- Approved AEV evidence
- Decision register

**Required activities.**
1. Issue approved proposal revision
2. Record conditions and residual risk
3. Durably reference baseline
4. Confirm authority boundary

**Outputs.** Approved Project Proposal baseline; decision record; residual-risk register

**Exit criteria.** Approval authority, revision, durable reference, conditions, and residual risks are explicit.

**Gate.** Authorizes GATE 4 — TEMPLATE GENERATION

### Prompt `[BASE]-[PROJECT]-0004.04-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.04 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.04 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.04-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.04 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.05 — Project Template Generation

**Purpose.** Instantiate the project-specific management/engineering template from the approved proposal while preserving the 49 lifecycle coordinates and PBIM controls.

**Inputs.**
- Approved proposal
- PBIM lifecycle model
- Identifier Registry

**Required activities.**
1. Instantiate 49 lifecycle positions
2. Add engineering subtitles
3. Insert authorized decimal custom sections
4. Embed prompts/notes at point of use
5. Assign prompt owners
6. Add measurable exit criteria

**Outputs.** Project-specific template candidate; identifier map; prompt inventory; count/order report

**Exit criteria.** Exactly 49 base positions are present; custom positions sort correctly; no empty stubs; prompts are executable and assigned.

**Gate.** GATE 5 — TEMPLATE AEA

### Prompt `[BASE]-[PROJECT]-0004.05-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.05 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.05 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.05-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.05 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.06 — Architectural Engineering of Project Template

**Purpose.** Assure the generated project template for executability, traceability, gate reachability, prompt completeness, stop wiring, and fidelity to the approved proposal.

**Inputs.**
- 0004.05 template
- Approved proposal
- PBIM baseline

**Required activities.**
1. Run coherence inspection
2. Issue template AEA
3. Collect independent verification
4. Consolidate AEV
5. Obtain decisions
6. Run AEC and AECC

**Outputs.** Template AEA/AEV/AEC evidence; findings register; closure record

**Exit criteria.** All identifiers resolve; all prompts are actionable; gates are reachable; stop conditions are wired; no baseline contradiction.

**Gate.** GATE 5 — TEMPLATE AECC

### Prompt `[BASE]-[PROJECT]-0004.06-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.06 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.06 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.06-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.06 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.07 — Project Template Adversarial Challenge & Closure

**Purpose.** Attack the project template rather than merely improving its wording; demonstrate whether it can fail under concrete scenarios.

**Inputs.**
- Approved template AEV
- AEC threat model
- All challenger reports

**Required activities.**
1. Exercise attack domains
2. Test assumptions and bypass paths
3. Record every finding
4. Independently review resolutions
5. Close or re-challenge

**Outputs.** AEC findings register; resolution mapping; residual-risk decision; closure authority

**Exit criteria.** Every material finding is accounted for and independently reviewed; false closure is prohibited.

**Gate.** Authorizes GATE 6 — ENVIRONMENT INITIALIZATION

### Prompt `[BASE]-[PROJECT]-0004.07-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.07 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.07 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.07-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.07 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.08 — Project Environment Initialization & Governance Setup

**Purpose.** Instantiate the approved project template in its actual repository/tooling environment without silently changing the baseline.

**Inputs.**
- AECC-closed template
- Repository/environment access
- Approved agent roster

**Required activities.**
1. Create project directories/registers
2. Configure branch and evidence conventions
3. Create ADR/decision/task/evidence stores
4. Instantiate scope manifests and controls
5. Declare secret boundary
6. Verify environment

**Outputs.** Initialized project environment; registers; protected resources; control-state register; scope-check mechanism

**Exit criteria.** Every required resource exists or has an explicit owner/date; secrets are absent; controls are recorded at truthful states; identifiers resolve.

**Gate.** GATE 6 — ENVIRONMENT READY

### Prompt `[BASE]-[PROJECT]-0004.08-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.08 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.08 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.08-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.08 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

## [BASE]-[PROJECT]-0004.09 — Project Initialization & Controlled Bootstrap

**Purpose.** Conduct the final human/agent bootstrap immediately before formal Project Charter initiation.

**Inputs.**
- Environment-ready record
- Registers
- Risk profile
- Stop conditions

**Required activities.**
1. Confirm team/roles/independence
2. Brief objectives and boundaries
3. Walk lifecycle and assurance gates
4. Confirm change and escalation paths
5. Run stop drill
6. Confirm operational ownership

**Outputs.** Bootstrap record; readiness checklist; stop-drill evidence; charter-entry authorization

**Exit criteria.** All charter-entry criteria pass; readiness claims are evidence-backed; stop drill works; human authority authorizes charter entry.

**Gate.** GATE 7 — CHARTER ENTRY

### Prompt `[BASE]-[PROJECT]-0004.09-PROMPT-LEAD`
```text
ROLE
You are the Lead Agent responsible for section 0004.09 of [PROJECT].
OBJECTIVE
Execute the purpose of 0004.09 and produce its controlled output without inventing authority.
CONTEXT
Use the approved PBIM baseline, the section inputs, the Canonical Source Manifest and the applicable risk profile.
CONSTRAINTS
Preserve identifier integrity; preserve evidence classification; do not claim approval or enforcement not supported by the registers; do not skip a gate; preserve dissent.
METHOD
1. Confirm inputs and their durable references.
2. Resolve requirements and dependencies.
3. Perform the section activities in order.
4. Record findings, assumptions, unknowns and decisions.
5. Verify exit criteria.
6. Produce the controlled output and update the relevant registers.
EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
OUTPUT STRUCTURE
1. Control block.
2. Inputs and provenance.
3. Activities performed.
4. Findings and decisions.
5. Requirements/evidence traceability.
6. Exit-criteria test.
7. Open items and owners.
8. Gate recommendation.
STOP CONDITIONS
Stop if an authoritative source conflicts; required evidence is inaccessible; an identifier cannot be resolved; authority is missing; a protected control would be weakened; or the exit criteria cannot be demonstrated.
DECISION SET
READY / READY-WITH-OBSERVATIONS / BLOCKED
```

### Prompt `[BASE]-[PROJECT]-0004.09-PROMPT-COLLAB`
```text
ROLE
You are an independent Collaborating Agent reviewing section 0004.09 of [PROJECT].
OBJECTIVE
Determine whether the section is coherent, traceable, executable and safe to advance; do not merely improve its wording.
CONTEXT
Review the supplied section, its parent baseline, durable references, relevant registers and declared risk profile.
METHOD
Test identifiers, authority boundaries, inputs, outputs, dependencies, exit criteria, evidence sufficiency and stop conditions. Identify contradictions and concrete failure modes.
EVIDENCE CLASSIFICATION
Use VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN and cite the exact supporting artefact.
OUTPUT STRUCTURE
1. Scope of review.
2. Findings with IDs and evidence.
3. Claim classification table.
4. Independence disclosure.
5. Dissent.
6. Decision.
STOP CONDITIONS
If the review cannot be completed, state the blocker. If independence required by the risk profile cannot be established, return CHALLENGE-BLOCKED.
DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED
```

---
# 13. 49-POSITION PBIM LIFECYCLE COORDINATE MODEL

The following 49 positions are retained because they provide a stable navigation and identifier framework across projects. They are not represented as a verbatim claim about PMBOK 8's canonical process list.

| # | Code | Position | Phase | Lifecycle process |
|---:|---|---|---|---|
| 1 | `GOV-01` | `0004.1` | Initiating | Develop Project Charter |
| 2 | `STK-01` | `0013.1` | Initiating | Identify Stakeholders |
| 3 | `GOV-02` | `1004.2` | Planning | Develop Project Management Plan |
| 4 | `SCP-01` | `1005.1` | Planning | Plan Scope Management |
| 5 | `SCP-02` | `1005.2` | Planning | Collect Requirements |
| 6 | `SCP-03` | `1005.3` | Planning | Define Scope |
| 7 | `SCP-04` | `1005.4` | Planning | Create Work Breakdown Structure |
| 8 | `SCH-01` | `1006.1` | Planning | Plan Schedule Management |
| 9 | `SCH-02` | `1006.2` | Planning | Define Activities |
| 10 | `SCH-03` | `1006.3` | Planning | Sequence Activities |
| 11 | `SCH-04` | `2006.4` | Planning | Estimate Activity Durations |
| 12 | `SCH-05` | `2006.5` | Planning | Develop Schedule |
| 13 | `FIN-01` | `2007.1` | Planning | Plan Cost Management |
| 14 | `FIN-02` | `2007.2` | Planning | Estimate Costs |
| 15 | `FIN-03` | `2007.3` | Planning | Determine Budget |
| 16 | `GOV-05` | `2008.1` | Planning | Plan Quality Management |
| 17 | `RES-01` | `2009.1` | Planning | Plan Resource Management |
| 18 | `RES-02` | `2009.2` | Planning | Estimate Activity Resources |
| 19 | `STK-03` | `2010.1` | Planning | Plan Communications Management |
| 20 | `RSK-01` | `2011.1` | Planning | Plan Risk Management |
| 21 | `RSK-02` | `3011.2` | Planning | Identify Risks |
| 22 | `RSK-03` | `3011.3` | Planning | Perform Qualitative Risk Analysis |
| 23 | `RSK-04` | `3011.4` | Planning | Perform Quantitative Risk Analysis |
| 24 | `RSK-05` | `3011.5` | Planning | Plan Risk Responses |
| 25 | `GOV-03` | `3012.1` | Planning | Plan Procurement Management |
| 26 | `STK-02` | `3013.2` | Planning | Plan Stakeholder Engagement |
| 27 | `GOV-04` | `4004.3` | Executing | Direct and Manage Project Work |
| 28 | `GOV-06` | `4004.4` | Executing | Manage Project Knowledge |
| 29 | `GOV-09` | `5008.2` | Executing | Manage Quality |
| 30 | `RES-03` | `5009.3` | Executing | Acquire Resources |
| 31 | `RES-04` | `6009.4` | Executing | Develop Team |
| 32 | `RES-05` | `6009.5` | Executing | Manage Team |
| 33 | `STK-05` | `6010.2` | Executing | Manage Communications |
| 34 | `RSK-06` | `6011.6` | Executing | Implement Risk Responses |
| 35 | `GOV-07` | `6012.2` | Executing | Conduct Procurements |
| 36 | `STK-04` | `6013.3` | Executing | Manage Stakeholder Engagement |
| 37 | `GOV-08` | `7004.5` | Monitoring & Controlling | Monitor and Control Project Work |
| 38 | `GOV-10` | `7004.6` | Monitoring & Controlling | Perform Integrated Change Control |
| 39 | `SCP-06` | `7005.5` | Monitoring & Controlling | Validate Scope |
| 40 | `SCP-05` | `7005.6` | Monitoring & Controlling | Control Scope |
| 41 | `SCH-06` | `8006.6` | Monitoring & Controlling | Control Schedule |
| 42 | `FIN-04` | `8007.4` | Monitoring & Controlling | Control Costs |
| 43 | `GOV-11` | `8008.3` | Monitoring & Controlling | Control Quality |
| 44 | `RES-06` | `8009.6` | Monitoring & Controlling | Control Resources |
| 45 | `STK-07` | `8010.3` | Monitoring & Controlling | Monitor Communications |
| 46 | `RSK-07` | `8011.7` | Monitoring & Controlling | Monitor Risks |
| 47 | `GOV-12` | `8012.3` | Monitoring & Controlling | Control Procurements |
| 48 | `STK-06` | `8013.4` | Monitoring & Controlling | Monitor Stakeholder Engagement |
| 49 | `GOV-13` | `9004.7` | Closing | Close Project or Phase |

**Count check:** 2 Initiating + 24 Planning + 10 Executing + 12 Monitoring & Controlling + 1 Closing = **49**.

### 13.1 Section implementation rule
Every instantiated lifecycle section SHALL include: purpose; engineering subtitle where needed; inputs; activities; outputs; requirements/evidence references; owner; prompt; exit criteria; stop conditions; and decision/gate state.

### 13.2 Placement rule
Reviews, tests, implementation verification, release readiness and operational controls belong at execution/monitoring/closing positions appropriate to their lifecycle meaning. They SHALL NOT be artificially placed at an early prefix merely because the activity is important.

---
# 14. GENERIC LIFECYCLE PROMPT MATRIX

Each of the 49 positions must receive a section-specific prompt at project-template generation. The following matrix defines the required intent; the implementer must replace the placeholders with project facts and references.

## [BASE]-[PROJECT]-0004.1 — Develop Project Charter

**Engineering intent:** Translate `Develop Project Charter` into an executable activity for [PROJECT], consistent with the phase `Initiating` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-0004.1 — Develop Project Charter. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-0004.1 — Develop Project Charter. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-0013.1 — Identify Stakeholders

**Engineering intent:** Translate `Identify Stakeholders` into an executable activity for [PROJECT], consistent with the phase `Initiating` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-0013.1 — Identify Stakeholders. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-0013.1 — Identify Stakeholders. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1004.2 — Develop Project Management Plan

**Engineering intent:** Translate `Develop Project Management Plan` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1004.2 — Develop Project Management Plan. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1004.2 — Develop Project Management Plan. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1005.1 — Plan Scope Management

**Engineering intent:** Translate `Plan Scope Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1005.1 — Plan Scope Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1005.1 — Plan Scope Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1005.2 — Collect Requirements

**Engineering intent:** Translate `Collect Requirements` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1005.2 — Collect Requirements. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1005.2 — Collect Requirements. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1005.3 — Define Scope

**Engineering intent:** Translate `Define Scope` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1005.3 — Define Scope. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1005.3 — Define Scope. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1005.4 — Create Work Breakdown Structure

**Engineering intent:** Translate `Create Work Breakdown Structure` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1005.4 — Create Work Breakdown Structure. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1005.4 — Create Work Breakdown Structure. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1006.1 — Plan Schedule Management

**Engineering intent:** Translate `Plan Schedule Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1006.1 — Plan Schedule Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1006.1 — Plan Schedule Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1006.2 — Define Activities

**Engineering intent:** Translate `Define Activities` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1006.2 — Define Activities. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1006.2 — Define Activities. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-1006.3 — Sequence Activities

**Engineering intent:** Translate `Sequence Activities` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-1006.3 — Sequence Activities. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-1006.3 — Sequence Activities. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2006.4 — Estimate Activity Durations

**Engineering intent:** Translate `Estimate Activity Durations` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2006.4 — Estimate Activity Durations. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2006.4 — Estimate Activity Durations. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2006.5 — Develop Schedule

**Engineering intent:** Translate `Develop Schedule` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2006.5 — Develop Schedule. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2006.5 — Develop Schedule. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2007.1 — Plan Cost Management

**Engineering intent:** Translate `Plan Cost Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2007.1 — Plan Cost Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2007.1 — Plan Cost Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2007.2 — Estimate Costs

**Engineering intent:** Translate `Estimate Costs` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2007.2 — Estimate Costs. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2007.2 — Estimate Costs. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2007.3 — Determine Budget

**Engineering intent:** Translate `Determine Budget` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2007.3 — Determine Budget. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2007.3 — Determine Budget. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2008.1 — Plan Quality Management

**Engineering intent:** Translate `Plan Quality Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2008.1 — Plan Quality Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2008.1 — Plan Quality Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2009.1 — Plan Resource Management

**Engineering intent:** Translate `Plan Resource Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2009.1 — Plan Resource Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2009.1 — Plan Resource Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2009.2 — Estimate Activity Resources

**Engineering intent:** Translate `Estimate Activity Resources` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2009.2 — Estimate Activity Resources. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2009.2 — Estimate Activity Resources. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2010.1 — Plan Communications Management

**Engineering intent:** Translate `Plan Communications Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2010.1 — Plan Communications Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2010.1 — Plan Communications Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-2011.1 — Plan Risk Management

**Engineering intent:** Translate `Plan Risk Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-2011.1 — Plan Risk Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-2011.1 — Plan Risk Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-3011.2 — Identify Risks

**Engineering intent:** Translate `Identify Risks` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-3011.2 — Identify Risks. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-3011.2 — Identify Risks. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-3011.3 — Perform Qualitative Risk Analysis

**Engineering intent:** Translate `Perform Qualitative Risk Analysis` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-3011.3 — Perform Qualitative Risk Analysis. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-3011.3 — Perform Qualitative Risk Analysis. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-3011.4 — Perform Quantitative Risk Analysis

**Engineering intent:** Translate `Perform Quantitative Risk Analysis` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-3011.4 — Perform Quantitative Risk Analysis. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-3011.4 — Perform Quantitative Risk Analysis. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-3011.5 — Plan Risk Responses

**Engineering intent:** Translate `Plan Risk Responses` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-3011.5 — Plan Risk Responses. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-3011.5 — Plan Risk Responses. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-3012.1 — Plan Procurement Management

**Engineering intent:** Translate `Plan Procurement Management` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-3012.1 — Plan Procurement Management. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-3012.1 — Plan Procurement Management. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-3013.2 — Plan Stakeholder Engagement

**Engineering intent:** Translate `Plan Stakeholder Engagement` into an executable activity for [PROJECT], consistent with the phase `Planning` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-3013.2 — Plan Stakeholder Engagement. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-3013.2 — Plan Stakeholder Engagement. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-4004.3 — Direct and Manage Project Work

**Engineering intent:** Translate `Direct and Manage Project Work` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-4004.3 — Direct and Manage Project Work. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-4004.3 — Direct and Manage Project Work. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-4004.4 — Manage Project Knowledge

**Engineering intent:** Translate `Manage Project Knowledge` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-4004.4 — Manage Project Knowledge. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-4004.4 — Manage Project Knowledge. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-5008.2 — Manage Quality

**Engineering intent:** Translate `Manage Quality` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-5008.2 — Manage Quality. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-5008.2 — Manage Quality. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-5009.3 — Acquire Resources

**Engineering intent:** Translate `Acquire Resources` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-5009.3 — Acquire Resources. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-5009.3 — Acquire Resources. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-6009.4 — Develop Team

**Engineering intent:** Translate `Develop Team` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-6009.4 — Develop Team. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-6009.4 — Develop Team. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-6009.5 — Manage Team

**Engineering intent:** Translate `Manage Team` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-6009.5 — Manage Team. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-6009.5 — Manage Team. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-6010.2 — Manage Communications

**Engineering intent:** Translate `Manage Communications` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-6010.2 — Manage Communications. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-6010.2 — Manage Communications. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-6011.6 — Implement Risk Responses

**Engineering intent:** Translate `Implement Risk Responses` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-6011.6 — Implement Risk Responses. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-6011.6 — Implement Risk Responses. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-6012.2 — Conduct Procurements

**Engineering intent:** Translate `Conduct Procurements` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-6012.2 — Conduct Procurements. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-6012.2 — Conduct Procurements. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-6013.3 — Manage Stakeholder Engagement

**Engineering intent:** Translate `Manage Stakeholder Engagement` into an executable activity for [PROJECT], consistent with the phase `Executing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-6013.3 — Manage Stakeholder Engagement. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-6013.3 — Manage Stakeholder Engagement. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-7004.5 — Monitor and Control Project Work

**Engineering intent:** Translate `Monitor and Control Project Work` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-7004.5 — Monitor and Control Project Work. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-7004.5 — Monitor and Control Project Work. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-7004.6 — Perform Integrated Change Control

**Engineering intent:** Translate `Perform Integrated Change Control` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-7004.6 — Perform Integrated Change Control. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-7004.6 — Perform Integrated Change Control. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-7005.5 — Validate Scope

**Engineering intent:** Translate `Validate Scope` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-7005.5 — Validate Scope. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-7005.5 — Validate Scope. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-7005.6 — Control Scope

**Engineering intent:** Translate `Control Scope` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-7005.6 — Control Scope. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-7005.6 — Control Scope. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8006.6 — Control Schedule

**Engineering intent:** Translate `Control Schedule` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8006.6 — Control Schedule. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8006.6 — Control Schedule. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8007.4 — Control Costs

**Engineering intent:** Translate `Control Costs` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8007.4 — Control Costs. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8007.4 — Control Costs. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8008.3 — Control Quality

**Engineering intent:** Translate `Control Quality` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8008.3 — Control Quality. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8008.3 — Control Quality. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8009.6 — Control Resources

**Engineering intent:** Translate `Control Resources` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8009.6 — Control Resources. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8009.6 — Control Resources. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8010.3 — Monitor Communications

**Engineering intent:** Translate `Monitor Communications` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8010.3 — Monitor Communications. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8010.3 — Monitor Communications. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8011.7 — Monitor Risks

**Engineering intent:** Translate `Monitor Risks` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8011.7 — Monitor Risks. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8011.7 — Monitor Risks. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8012.3 — Control Procurements

**Engineering intent:** Translate `Control Procurements` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8012.3 — Control Procurements. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8012.3 — Control Procurements. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-8013.4 — Monitor Stakeholder Engagement

**Engineering intent:** Translate `Monitor Stakeholder Engagement` into an executable activity for [PROJECT], consistent with the phase `Monitoring & Controlling` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-8013.4 — Monitor Stakeholder Engagement. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-8013.4 — Monitor Stakeholder Engagement. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

## [BASE]-[PROJECT]-9004.7 — Close Project or Phase

**Engineering intent:** Translate `Close Project or Phase` into an executable activity for [PROJECT], consistent with the phase `Closing` and the approved proposal/template.

**Lead prompt:**
```text
Act as the Lead Agent for [BASE]-[PROJECT]-9004.7 — Close Project or Phase. Using only the approved PBIM, project proposal, applicable requirements, risk profile and durable references, produce the controlled section artefact. Define the project-specific objective, inputs, activities, outputs, dependencies, responsible role, evidence required, acceptance/exit criteria, stop conditions and next-gate recommendation. Do not invent unapproved architecture or authority. Classify all substantive claims as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN. Preserve unresolved issues with owners and dates. End with READY / READY-WITH-OBSERVATIONS / BLOCKED.
```

**Collaborating prompt:**
```text
Independently review [BASE]-[PROJECT]-9004.7 — Close Project or Phase. Test whether the section is correctly placed in the lifecycle, executable, traceable to the approved baseline, appropriately scoped, adequately evidenced and consistent with all applicable controls. Hunt for hidden authority, missing dependencies, ambiguous identifiers, weak exit criteria, unsupported claims and bypass paths. Provide evidence for every material finding, disclose independence, preserve dissent, and conclude APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE / CHALLENGE-BLOCKED.
```

---
# 15. GATES AND ADVANCEMENT MODEL

| Gate | Entry | Required evidence | Exit authority |
|---|---|---|---|
| G1 | PBIM initialization | PBIM baseline, registers, risk/materiality decision | Human authority |
| G2 | Proposal preparation | Controlled proposal prompt, RTM, non-authorizations | Human authority |
| G3 | Proposal assurance | AEA, AEV, AEC, AECC closure | Named closure authority |
| G4 | Proposal baseline | Approved proposal + durable reference | Human authority |
| G5 | Template assurance | Template checks + AEA/AEV/AEC/AECC | Named closure authority |
| G6 | Environment readiness | Environment map, registers, scope controls, secret boundary | Human authority |
| G7 | Charter entry | Bootstrap, stop drill, ownership, readiness evidence | Human authority |

No gate is satisfied by elapsed time, majority consensus, prompt wording, or agent confidence.

---
# 16. CONTROL REGISTERS

### 16.1 Control State Register
| Control ID | Control | Owner | State | Evidence | Verifier | Last verified |
|---|---|---|---|---|---|---|
| `CTL-####` | `{{...}}` | `{{...}}` | `DESIGNED` | `{{EVD-####}}` | `{{...}}` | `{{...}}` |

### 16.2 Requirements Traceability Matrix
| REQ ID | Requirement | Source | Materiality | Design section | Decision/ADR | Task | Test | Evidence | Release | Status |
|---|---|---|---|---|---|---|---|---|---|---|
| `REQ-####` | `{{...}}` | `{{...}}` | L0-L3 | `{{...}}` | `{{...}}` | `{{TASK-####}}` | `{{...}}` | `{{EVD-####}}` | `{{REL-####.#}}` | COVERED / PARTIAL / UNCOVERED |

A material requirement without a test and evidence is `UNCOVERED` and blocks release unless formally dispositioned by the applicable authority.

### 16.3 Findings Register
| Finding | Source | Classification | Severity | Evidence | Affected control | Resolution | Reviewer | Status |
|---|---|---|---|---|---|---|---|---|
| `FND-####.##` | `{{AEA/AEV/AEC}}` | `{{...}}` | `{{...}}` | `{{EVD-####}}` | `{{CTL-####}}` | `{{...}}` | `{{...}}` | OPEN / RESOLVED / ACCEPTED-RISK / REJECTED |

### 16.4 Decision Ledger
| DEC ID | Date | Subject | Decision | Authority | Evidence | Supersedes |
|---|---|---|---|---|---|---|
| `DEC-####` | `{{...}}` | `{{...}}` | `{{...}}` | `{{...}}` | `{{EVD-####}}` | `{{DEC-####}}` |

---
# 17. OPERATIONAL READINESS

Before implementation or release authorization, the project must demonstrate applicable ownership, monitoring, logging, alerting, rollback/recovery, support, security boundaries, data retention, dependency readiness and after-life ownership.

Operational readiness is a monitoring/controlling or closing concern, not a pre-charter claim.

---
# 18. SECURITY AND SECRET BOUNDARY

Secrets SHALL NOT appear in PBIMs, prompts, Task Packets, evidence files, logs or source repositories. References may identify a secret by identifier, vault/key reference or environment name without disclosing its value.

A detected secret-boundary violation is an immediate STOP pending human review.

---
# 19. DRIFT CONTROL

Material divergence from the approved PBIM, proposal, template, authority matrix, identifier registry, protected controls, Task Packets, implementation or operational configuration is `BASELINE-DRIFT`.

Drift correction requires governance approval. It must not be silently corrected by an agent in the ordinary execution path.

---
# 20. ADR AND DECISION MANAGEMENT

ADRs record decisions, not tasks. Minimum fields: ADR ID, date, status, context, problem, decision, rationale, alternatives rejected and why, consequences, affected components, linked decisions, linked evidence, author and reviewers.

Superseded ADRs remain retained and identifiable.

---
# 21. AEA / AEV / AEC RESTART PROTOCOL

If the assurance cycle reaches the revision cap without convergence, the Lead Agent SHALL request the complete evidence set and produce a fresh PBIM cycle. The restart package includes: current PBIM; AEA query; all AEA reports; all AEV statement revisions; all AEV responses; AEC challenges; and AEC results.

The restarted PBIM must be manually updated before a new AEA query is prepared.

---
# 22. IMPLEMENTATION BOUNDARY

PBIM itself does not authorize implementation. Implementation begins only after the instantiated project has a valid approved template, environment readiness, Task Packet authorization, and the applicable human gate.

An agent's technical ability to perform an action is not evidence that it has authority to perform that action.

---
# 23. MANUAL UPDATE CHECKLIST — REQUIRED BEFORE AEA

- [ ] Replace all project tokens and remove accidental example-specific content.
- [ ] Confirm the actual human authority and delegation boundaries.
- [ ] Confirm the collaborating-agent roster and independence constraints.
- [ ] Confirm the canonical source set and durable references.
- [ ] Confirm repository, document, registry, ADR, decision-ledger, evidence and Task Packet locations.
- [ ] Select and justify LIGHT / STANDARD / HIGH-ASSURANCE risk profile.
- [ ] Define materiality thresholds and protected controls.
- [ ] Verify every identifier and reserve custom sections.
- [ ] Review every section prompt for project-specific executability.
- [ ] Confirm no prompt grants implementation or production authority.
- [ ] Confirm all stop conditions have a named authority and recovery path.
- [ ] Confirm the 49-position count and lifecycle ordering.
- [ ] Confirm all placeholders are intentional, owned and dated.
- [ ] Record this manual-update revision and integrity anchor.
- [ ] Authorize preparation of the PBIM AEA Query only after the above checks pass.

---
# 24. PBIM DEVELOPMENT WORKFLOW — CONTROLLED SEQUENCE

`Reference compilation → Manual PBIM update → PBIM AEA Query → Independent AEA reports → Controlled AEV candidate → All-agent AEV review → Bounded revisions → AEC adversarial duel → AECC closure → Updated PBIM → Project Proposal Prompt → Proposal AEA/AEV/AEC/AECC → Project Proposal → Template generation → Template AEA/AEV/AEC/AECC → Environment initialization → Controlled bootstrap → Charter entry`

At every transition, evidence and authority are checked independently of the transition's desired outcome.

---
# 25. REFERENCE BASIS FOR THIS CONSOLIDATION

This generic revision was consolidated from the supplied PBIM evolution, especially the initial PBIM structure and the later generic control model. The historical generic model establishes the nine pre-charter sections, 49 lifecycle coordinates, evidence/identifier controls, bounded AEV convergence, AEC adversarial testing, durable references, Task Packet scope controls, and prompt contract. citeturn5view1turn8view0turn3view1turn3view3

The earlier project-specific versions contributed practical prompt/section patterns but contained project-specific technology and assumptions; those details are intentionally excluded from this generic architecture. The generic model's distinction between design, enforceability, enforcement and independent verification is retained because it prevents governance claims from outrunning evidence. citeturn3view1

### 25.1 Standards note
PMBOK 8 was checked against PMI's current public description: it retains principles and performance-domain foundations, adds/expands AI, PMO and procurement coverage, and reintroduces process guidance in an evolved non-prescriptive form. ISO 21502 remains the applicable published project-management guidance while its second edition is under development. citeturn7search9turn7search1turn7search2

---
# 26. FINAL STATUS

**This document is a generic implementation candidate, not an approved operating constitution.** It SHALL be manually instantiated and reviewed before entering Architectural Engineering development.

**Required next artifact:** `[BASE]-[PROJECT]-0004.01 PBIM Architectural Engineering Analysis Query`.

**Required assurance path:** `AEA → AEV → AEC → AECC`.

**No implementation authorization is granted by this document.**

---
**END OF PBIM v1.04.00-GENERIC**