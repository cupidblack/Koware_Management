# PROJECT BASE INTEGRATION MANAGER [PBIM]

**Generic Edition:** v2.01.00  
**Generated:** 2026-10-07  
**Document Class:** Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework  
**Status:** CONTROLLED GENERIC CANDIDATE — DESIGNED ONLY  
**Architectural Status:** DESIGNED — NOT YET ENFORCED OR INDEPENDENTLY VERIFIED  
**Implementation Authorization:** NOT GRANTED  
**Production Authorization:** NOT GRANTED  
**PBIM Boundary:** Ends at `0004.1 Develop Project Charter`

---

# 0. DOCUMENT CONTROL

| Field | Generic Value |
|---|---|
| PROJECT-NAME | `{{PROJECT-NAME}}` |
| PROJECT-ID | `{{PROJECT-ID}}` |
| PROJECT-BASE | `{{PROJECT-BASE}}` |
| BASE-ID | `{{BASE-ID}}` |
| PBIM-ID | `[{{PROJECT-ID}}][PBI-0004]` |
| PBIM-VERSION | `v2.01.00` |
| PARENT-BASELINE | `v2.00.00` |
| DOCUMENT-OWNER | `{{HUMAN-AUTHORITY}}` |
| LEAD-AGENT | `{{LEAD-AGENT}}` |
| COLLABORATING-AGENTS | `{{COLLABORATING-AGENTS}}` |
| RISK-PROFILE | `LIGHT / STANDARD / HIGH-ASSURANCE` |
| PROJECT-LOCATION | `{{TOWN}}, {{DISTRICT}}, {{CITY}}, {{REGION}}, {{COUNTRY}}` |
| EXPECTED-PROJECT-DURATION | `{{EXPECTED-PROJECT-DURATION}}` |
| EXPECTED-PROJECT-START-DATE | `{{EXPECTED-PROJECT-START-DATE}}` |
| EXPECTED-PROJECT-END-DATE | `{{EXPECTED-PROJECT-END-DATE}}` |
| CANONICAL-SOURCE | `{{DURABLE-CANONICAL-REFERENCE}}` |
| INTEGRITY-ANCHOR | `{{HASH / IMMUTABLE OBJECT / TAG}}` |
| PBIM-STATE | `DRAFT / UNDER-AEA / AEV-CANDIDATE / AEV-APPROVED / AEC-IN-PROGRESS / AECC-CLOSED / SUPERSEDED` |

### 0.1 Expected-project-date rule

`EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, and `EXPECTED-PROJECT-END-DATE` are deliberately labelled **EXPECTED** because PBIM is a probing/pre-charter framework. They are planning estimates, not approved commitments.

The expected duration shall state its basis, including calendar-day or working-day convention, working-hour assumptions, dependencies, public holidays where material, resource availability, delivery approach and known constraints. Jurisdiction-specific working-time requirements must be checked during project instantiation.

No expected date becomes an approved baseline merely because an agent generated it.

### 0.2 Genericity rule

This document must remain project-independent. Replaceable project tokens may be instantiated, but project-specific technology, vendors, repositories, databases, credentials, branches, laws, architecture, budgets or implementation decisions must not be converted into generic rules.

---

# 1. PURPOSE, SCOPE AND BOUNDARY

The Project Base Integration Manager (PBIM) is a **pre-charter integration and assurance framework**. It converts an initial project concept into a sufficiently governed, evidenced and simulated package for formal Project Charter development.

PBIM provides:

1. project identity and authority definition;
2. evidence and decision traceability;
3. risk-scaled governance;
4. architectural analysis, verification and adversarial challenge;
5. controlled project proposal development;
6. project-template assembly and readiness;
7. framework initialization;
8. simulation and operational-readiness probing;
9. final readiness determination for Project Charter development.

PBIM does **not** replace:

- organizational governance;
- legal or regulatory authority;
- a Project Charter;
- the project management plan;
- architecture approval;
- procurement authority;
- information-security policy;
- privacy obligations;
- operational controls;
- production authorization.

### 1.1 PBIM integration boundary

```text
PROJECT IDEA / OPPORTUNITY
        ↓
PBIM
        ↓
VERIFIED PROJECT PROPOSAL
        ↓
OPERATING PROJECT TEMPLATE
        ↓
INITIALIZED GOVERNANCE ENVIRONMENT
        ↓
SIMULATED READINESS
        ↓
READY FOR PROJECT CHARTER
        ↓
0004.1 DEVELOP PROJECT CHARTER
        ↓
NORMAL PROJECT LIFECYCLE
```

`0004.1` is the final PBIM identifier.

---

# 2. ARCHITECTURAL MODEL

PBIM uses three control layers:

```text
GOVERNANCE
    ↓
ASSURANCE
    ↓
AUTHORIZED EXECUTION
```

Governance establishes authority, constraints, decision rights and protected controls.

Assurance evaluates architecture, evidence, risk, feasibility, security, operational readiness and challenge results.

Execution performs only work authorized by the applicable governance mechanism.

Technical capability never creates governance authority.

The core assurance cycle is:

```text
AEA → AEV → AEC → AECC
```

Where:

- **AEA — Architectural Engineering Analysis:** structured examination and questioning.
- **AEV — Architectural Engineering Verification:** controlled verification of the proposed baseline.
- **AEC — Architectural Engineering Challenge:** adversarial attempt to invalidate the baseline.
- **AECC — Architectural Engineering Challenge Closure:** evidence-based disposition and closure.

---

# 3. CONTROL MATURITY MODEL

Every material control shall be assigned one of four states:

| State | Meaning |
|---|---|
| `DESIGNED` | Control is specified in the governing documentation. |
| `ENFORCEABLE` | A practical mechanism exists that can prevent, permit or detect the declared outcome. |
| `ENFORCED` | Evidence demonstrates that the mechanism operates in the applicable environment. |
| `INDEPENDENTLY VERIFIED` | A suitably independent verifier has tested the control and confirmed operation. |

Documentation is not implementation evidence.

An agent statement such as "this control is implemented" is not sufficient evidence.

---

# 4. GOVERNANCE AND AUTHORITY

## 4.1 Authority hierarchy

The generic authority model is:

```text
CA — Constitutional / Organizational Constitutional Authority
 ↓
H0 — Primary Human Project Authority
 ↓
H1 — Delegated Human Authority
 ↓
H2 — Authorized Operational / Technical Participant
 ↓
Agent / Service / Automation Capabilities
```

The actual project must map these abstract roles to real authorities.

### 4.2 Role versus authority

Agent roles are capabilities, not authority grants.

Typical roles include:

| Role | Function |
|---|---|
| Lead Agent | orchestration, synthesis, evidence preservation and gate preparation |
| Analysis Agent | independent analysis |
| Verification Agent | evidence, traceability and reproducibility verification |
| Challenge Agent | adversarial challenge |
| Implementation Agent | execution of authorized work |
| Test Agent | functional, negative and control testing |
| Documentation Agent | records, identifiers and provenance |
| Operations/Release Agent | operational readiness and release evidence |
| Human Authority | authorization and escalation |

No role may approve its own work where the risk profile requires separation.

---

# 5. RISK-SCALED GOVERNANCE

| Profile | Intended use | Minimum posture |
|---|---|---|
| `LIGHT` | low-consequence, bounded work | proportionate ceremony with protected controls retained |
| `STANDARD` | ordinary project work | full PBIM controls proportionate to materiality |
| `HIGH-ASSURANCE` | safety, finance, security, regulated, sensitive-data, irreversible or high-blast-radius work | enhanced evidence, genuine independence, challenge and operational verification |

Tailoring may reduce ceremony. It may not weaken protected authority, security, evidence, traceability, stop or independence controls.

### 5.1 Cumulative materiality

Materiality shall be assessed across:

- related tasks;
- dependencies;
- migrations;
- releases;
- concurrent changes;
- security exposure;
- data impact;
- financial exposure;
- operational impact.

A sequence of individually low-risk changes must not be used to conceal a materially high-risk aggregate change.

---

# 6. MERGED CONTROL FAMILIES

This revision explicitly merges repeated concepts found throughout the PBIM lineage.

## 6.1 Merged Concept A — Evidence, Provenance, Traceability and Durable References

Earlier versions repeatedly described evidence classification, provenance, canonical sources, integrity anchors, traceability and durable references in separate locations.

These are now one controlled mechanism:

### Evidence & Traceability Control

```text
SOURCE
 ↓
EVIDENCE CLASS
 ↓
PROVENANCE
 ↓
CLAIM / REQUIREMENT
 ↓
DECISION
 ↓
ARCHITECTURE
 ↓
TASK / CHANGE
 ↓
VERIFICATION
 ↓
RELEASE / HANDOFF
```

Every material claim shall be classified as:

`FACT / INFERENCE / ASSUMPTION / PROPOSAL / RECOMMENDATION / RISK / UNKNOWN / DISPUTED`

Every material artifact shall identify:

- artifact ID;
- canonical source;
- revision;
- provenance;
- owner;
- authority;
- integrity reference where material;
- supersession relationship;
- applicable lifecycle state.

Branch names, temporary chat sessions and transient agent workspaces are not authoritative references by themselves.

## 6.2 Merged Concept B — Stop, Reset, Emergency, Failure and Recovery

Earlier versions repeated stop conditions, emergency delegation, architectural reset, failure recovery and blocked-state rules.

These are now one controlled mechanism:

### Controlled State & Recovery Model

```text
NORMAL
 ↓
ADVISORY / HOLD
 ↓
MANDATORY STOP
 ↓
RECOVERY / DECISION
 ↓
RESUME
       OR
RESET
       OR
ESCALATE
```

A stop shall preserve evidence.

A stop cannot be self-cleared by the executor.

Emergency authority, where permitted, shall have:

- explicit scope;
- named authority;
- time limit/TTL;
- evidence requirement;
- post-event reconciliation;
- prohibition on permanent baseline weakening.

Reset is required when a load-bearing assumption fails or the current baseline can no longer be trusted.

Reset must not erase adverse evidence.

## 6.3 Merged Concept C — Prompt Engineering Contract

Earlier versions repeated nearly identical Lead, Collaborator, Verification and Challenge instructions in every section.

This revision uses one common prompt contract. Each section prompt still contains the required explicit `START/STOP` structure, but shared control expectations are centralized here.

Every operational prompt shall contain:

1. role/designation;
2. context;
3. objective;
4. inputs/resources;
5. constraints;
6. method;
7. evidence classification;
8. required output;
9. stop conditions;
10. decision set;
11. authority boundary.

Section-specific prompts may add requirements but may not weaken this contract.

---

# 7. IDENTIFIER ARCHITECTURE

PBIM separates identity dimensions.

```text
PROJECT-ID
    ↓
PBIM SECTION ID
    ↓
WORK / TASK ID
    ↓
DOCUMENT / ARTIFACT ID
    ↓
LIFECYCLE STATE
    ↓
PM PROCESS / DOMAIN CLASSIFICATION
```

A single identifier must not be overloaded to mean all six.

## 7.1 PBIM section grammar

```text
[PROJECT-ID][PBI-XX-0004.XX]
```

Examples:

```text
[PROJECT-ID][PBI-01-0004.01]
[PROJECT-ID][PBI-02-0004.02]
[PROJECT-ID][PBI-09-0004.09]
[PROJECT-ID][GOV-01-0004.1]
```

`PBI` means **Project Base Integration** and is reserved for PBIM pre-charter sections.

`0004.1` remains the Charter integration boundary and is not treated as a PMBOK process-count claim.

## 7.2 Artifact identifiers

Recommended generic classes:

| Artifact | Pattern |
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
| Prompt | `[SECTION]-PROMPT-<ROLE>-##` |

---

# 8. IDENTIFIER REGISTRY

The registry is the authoritative state store for local identifiers.

Minimum fields:

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
owner:
created_at:
supersedes:
integrity_reference:
```

Registry mutation shall follow:

```text
REQUEST
→ RESERVE
→ VALIDATE
→ COMMIT
→ VERIFY
→ CONFIRM
```

Conflicts, stale revisions, ambiguous ownership or untrusted registry state shall stop progression.

---

# 9. PMBOK 8 AND INDUSTRY ALIGNMENT

PBIM is compatible with modern project-management practice but does not claim that its local lifecycle is identical to PMBOK.

PMBOK Guide — Eighth Edition is used as a reference for:

### Six principles

1. Adopt a holistic view.
2. Focus on value.
3. Embed quality.
4. Lead accountably.
5. Integrate sustainability.
6. Build an empowered team.

### Seven performance domains

1. Governance.
2. Scope.
3. Schedule.
4. Finance.
5. Stakeholders.
6. Resources.
7. Risk.

PBIM may maintain a local process/classification spine for traceability, but that spine shall not be described as the canonical PMBOK 8 process list.

Applicable complementary references may include:

- ISO 21502:2020 project-management guidance;
- ISO 31000:2018 risk-management guidance;
- NIST SP 800-218 SSDF for software projects;
- NIST AI RMF and/or ISO/IEC 42001 when AI governance is materially relevant;
- applicable information-security, privacy, safety, quality, procurement and sector-specific standards;
- applicable laws and regulations.

Standards are references, not proof of implementation.

The project must determine applicability during instantiation.

---

# 10. PRE-CHARTER SECTION MAP

| Section | Identifier | Primary output | Gate |
|---:|---|---|---|
| 1 | `PBI-01-0004.01` | PBIM candidate | Resource completeness |
| 2 | `PBI-02-0004.02` | PBIM assurance baseline | PBIM architectural readiness |
| 3 | `PBI-03-0004.03` | Project proposal | Context completeness |
| 4 | `PBI-04-0004.04` | Verified proposal | Proposal AECC |
| 5 | `PBI-05-0004.05` | Project template skeleton | Assembly completeness |
| 6 | `PBI-06-0004.06` | Operating project template | Template readiness |
| 7 | `PBI-07-0004.07` | Initialized project controls | Configuration integrity |
| 8 | `PBI-08-0004.08` | Simulation/readiness evidence | Operational readiness |
| 9 | `PBI-09-0004.09` | PBIM activation package | Charter readiness |
| Final | `GOV-01-0004.1` | Project Charter | **PBIM boundary** |

---

# 11. PBI-01 — PBIM DOCUMENT CREATION AND BASELINE INITIALIZATION

**Identifier:** `[PROJECT-ID][PBI-01-0004.01]`

## Purpose

Create or update the generic PBIM from the complete available reference set.

## Required resources

At minimum:

1. current PBIM;
2. relevant prior PBIM revisions;
3. AEA query and reports;
4. AEV statement and responses;
5. AEC challenge and results;
6. AECC closure, if available;
7. applicable current standards/policies;
8. relevant implementation lessons;
9. known unresolved findings.

## Required outputs

- PBIM candidate;
- resource manifest;
- standards/applicability register;
- identifier change register;
- prompt change register;
- assumptions/questions register;
- revision/change record.

## Exit gate

All source artifacts are accounted for; contradictions and evidence gaps are explicit; the candidate is ready for independent architectural analysis.

### **<<START Prompt 1. PBIM Document Creation>>**
[Designation: Lead Agent]

You are the Lead Agent responsible for creating or updating a generic PBIM.

Review the complete supplied PBIM reference set as a revision lineage rather than treating the latest document as automatically correct.

Perform, in order:

1. compile and validate the Resource Manifest;
2. compare all available PBIM revisions;
3. identify repeated, overlapping, contradictory and obsolete concepts;
4. identify every section with an identifier;
5. identify every operational prompt;
6. identify obsolete architecture, project-management practices, engineering practices, policy assumptions and unsupported standards claims;
7. merge repeated concepts where one stronger control can replace multiple weaker or duplicated controls;
8. preserve necessary legacy traceability through a crosswalk;
9. separate project identity, task identity, artifact identity, lifecycle state and PM classification;
10. preserve the PBIM-to-Project-Charter boundary;
11. define expected rather than committed project duration/start/end fields;
12. ensure no generic rule contains project-specific technology or organizational assumptions;
13. classify material claims;
14. produce the updated generic PBIM and change summary.

<<START PBIM Reference Resources>>
1. Current PBIM and all supplied prior revisions.
2. Applicable AEA/AEV/AEC/AECC records.
3. Current applicable standards, policies and regulations.
4. Relevant implementation lessons and unresolved findings.
<<STOP PBIM Reference Resources>>

Notes:
1. Do not claim a control is implemented merely because the document specifies it.
2. If a resource cannot be accessed, record it as UNKNOWN and identify its effect.
3. Preserve material dissent.
4. Explicitly identify at least two merged repeated/similar concepts and explain the resulting consolidation.
5. Do not silently resolve conflicts that require human authority.

Required output:
A. Executive synthesis.
B. Revision/change summary.
C. Legacy-to-new identifier map.
D. Merged-concept register.
E. Updated PBIM.
F. Remaining assumptions and unknowns.
G. Verification gates.
H. Implementation obligations.
I. Authorization status.

Decision set:
`READY / READY-WITH-OBSERVATIONS / BLOCKED`

Stop if an authoritative source conflicts materially, required evidence is inaccessible, authority is undefined, an identifier cannot be resolved, or a protected control would be weakened.

<<STOP Prompt 1. PBIM Document Creation>>

### **<<START Prompt 2. PBIM Document Independent Review>>**
[Designation: Collaborating Agents]

Independently review the PBIM candidate produced by Prompt 1.

<<START PBIM Candidate>>
{{Durable link to PBIM candidate}}
<<STOP PBIM Candidate>>

Evaluate:

- internal consistency;
- identifier integrity;
- section completeness;
- prompt executability;
- merged-concept correctness;
- authority separation;
- evidence controls;
- standards applicability;
- expected-date semantics;
- PBIM boundary;
- risk scaling;
- stop/reset/emergency controls;
- architectural versus implementation claims.

For each material finding provide:

`Finding ID → Evidence → Impact → Severity/Materiality → Affected Control → Recommendation → Required Decision`

Do not rewrite the candidate merely to make it more coherent. Test it.

Notes:
1. Preserve minority findings.
2. Disclose inaccessible resources.
3. If independence cannot be established where required, return `CHALLENGE-BLOCKED`.

Decision set:
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCK / CHALLENGE-BLOCKED`

<<STOP Prompt 2. PBIM Document Independent Review>>

---

# 12. PBI-02 — PBIM ARCHITECTURAL ENGINEERING AND BASELINE ASSURANCE

**Identifier:** `[PROJECT-ID][PBI-02-0004.02]`

## Purpose

Subject the PBIM itself to AEA → AEV → AEC → AECC.

## Assurance sequence

```text
PBIM Candidate
→ AEA Query
→ Independent AEA Reports
→ AEV Candidate
→ AEV Decisions
→ AEC
→ AEC Results
→ AECC
→ Controlled PBIM Baseline
```

No material stage may be skipped merely because a previous revision was approved.

### **<<START Prompt 3. PBIM Architectural Engineering Analysis>>**
[Designation: Lead Agent]

Develop a formal PBIM AEA Query that tests the PBIM as a governance and engineering system.

<<START PBIM Subject>>
{{Durable reference to PBIM candidate}}
<<STOP PBIM Subject>>

Analyze at minimum:

1. authority and permission separation;
2. governance hierarchy;
3. identifier architecture and registry integrity;
4. evidence provenance;
5. role separation and independence;
6. risk/materiality scaling;
7. PMBOK/ISO alignment;
8. engineering lifecycle alignment;
9. security and privacy;
10. AI governance where applicable;
11. Task Packet scope;
12. stop/resume/reset/emergency controls;
13. dependency and baseline drift;
14. cumulative materiality;
15. operational readiness;
16. failure recovery;
17. durable artifact references;
18. maintainability and scalability;
19. excessive governance or governance gaps;
20. genericity.

For every material question define:

`Question → Why it matters → Expected evidence → Failure condition → Decision consequence`

Do not answer the query inside the query.

<<STOP Prompt 3. PBIM Architectural Engineering Analysis>>

### **<<START Prompt 4. PBIM AEA Review>>**
[Designation: Collaborating Agents]

Independently answer the PBIM AEA Query.

<<START PBIM AEA Query>>
{{Durable reference to AEA Query}}
<<STOP PBIM AEA Query>>

Review the supplied evidence and report:

- verified facts;
- inferences;
- assumptions;
- risks;
- unknowns;
- contradictions;
- architectural defects;
- implementation-boundary defects;
- recommended corrections.

Every material finding must include evidence and a proposed disposition.

Decision set:
`AEA-CONCUR / AEA-CONCUR-WITH-CONDITIONS / AEA-DISAGREE / AEA-BLOCKED`

<<STOP Prompt 4. PBIM AEA Review>>

### **<<START Prompt 5. PBIM AEV Statement>>**
[Designation: Lead Agent]

Synthesize all AEA reports into a controlled PBIM AEV candidate.

<<START PBIM AEA Reports>>
{{Links to all independent AEA reports}}
<<STOP PBIM AEA Reports>>

Requirements:

1. preserve every material finding;
2. do not use majority agreement as proof;
3. resolve contradictions explicitly;
4. identify controls that are DESIGNED only;
5. define verification conditions;
6. identify residual risks;
7. define advancement conditions;
8. define conditions requiring human authority.

Produce:

`Finding-to-Resolution Matrix → Verified Baseline Requirements → Conditions → Residual Risks → AEV Decision`

Decision set:
`AEV APPROVE / AEV APPROVE WITH CONDITIONS / AEV RETURN / AEV BLOCK / ARCHITECTURAL RESET`

<<STOP Prompt 5. PBIM AEV Statement>>

### **<<START Prompt 6. PBIM AEC Adversarial Challenge>>**
[Designation: Authorized Independent Challenge Agent]

Attempt to invalidate the PBIM AEV baseline.

<<START PBIM AEV Baseline>>
{{Durable reference to AEV candidate}}
<<STOP PBIM AEV Baseline>>

Construct falsifiable attacks against:

- hidden authority;
- privilege escalation;
- registry corruption/concurrency;
- evidence manipulation;
- false independence;
- task-scope escape;
- instruction drift;
- emergency bypass;
- stop/reset bypass;
- cumulative materiality;
- stale baselines;
- dependency/environment drift;
- approval expiry;
- operational-readiness bypass;
- rollback/recovery assumptions;
- human unavailability;
- compromised automation;
- durable-reference failure;
- excessive or insufficient governance.

For each attack report:

`Attack → Expected Control → Failure Scenario → Evidence → Severity → Disposition`

If required independence is unavailable, return `CHALLENGE-BLOCKED`.

Do not improve the architecture during the challenge. Attack it.

<<STOP Prompt 6. PBIM AEC Adversarial Challenge>>

### **<<START Prompt 7. PBIM AEC Results and AECC Closure>>**
[Designation: Lead Agent / Authorized Closure Authority]

Review all AEC results.

<<START PBIM AEC Results>>
{{Links to all AEC results}}
<<STOP PBIM AEC Results>>

For every material finding:

1. preserve the original finding;
2. record severity;
3. identify corrective control;
4. identify evidence;
5. identify independent confirmation;
6. record residual risk;
7. identify authorization;
8. state closure status.

If a material finding invalidates the baseline:

`ARCHITECTURAL RESET`

If remediation changes the baseline materially:

`AEV AMENDMENT → AEC RE-CHALLENGE → AECC`

Do not close findings through renaming, splitting, severity downgrading, administrative wording or baseline replacement without addressing the underlying risk.

Output:
- AECC record;
- residual-risk register;
- final baseline revision;
- implementation obligations;
- authorization status.

AECC closure does not authorize production implementation.

<<STOP Prompt 7. PBIM AEC Results and AECC Closure>>

---

# 13. PBI-03 — PROJECT CONTEXT AND PROPOSAL DEFINITION

**Identifier:** `[PROJECT-ID][PBI-03-0004.03]`

## Purpose

Translate the controlled PBIM into a project-specific proposal without turning assumptions into approved requirements.

Required project inputs:

- project name/ID;
- problem or opportunity;
- strategic objective;
- intended outcomes/value;
- authority/sponsor;
- stakeholders;
- preliminary scope and exclusions;
- constraints;
- assumptions;
- dependencies;
- environment;
- security/privacy considerations;
- delivery approach;
- expected duration;
- expected start/end dates;
- risk profile;
- preliminary success measures;
- legal/regulatory considerations;
- potential solution options.

### **<<START Prompt 8. Project Context and Proposal Definition>>**
[Designation: Lead Agent / Project Authority]

Using the AECC-closed PBIM baseline, generate a project proposal definition package.

<<START PBIM Baseline>>
{{Durable PBIM baseline}}
<<STOP PBIM Baseline>>

<<START Project Inputs>>
{{Project-provided context and source materials}}
<<STOP Project Inputs>>

Classify every substantive item as:

`FACT / INFERENCE / ASSUMPTION / PROPOSAL / DECISION REQUIRED / UNKNOWN`

Produce:

1. project identity;
2. problem/opportunity;
3. strategic alignment;
4. value/outcome model;
5. stakeholders;
6. preliminary scope/exclusions;
7. requirements;
8. constraints;
9. assumptions;
10. dependencies;
11. security/privacy/data considerations;
12. expected duration/start/end;
13. risk/materiality profile;
14. solution options;
15. recommended approach;
16. success measures;
17. legal/regulatory applicability;
18. project-template inputs;
19. unresolved decisions.

Do not invent missing facts.

<<STOP Prompt 8. Project Context and Proposal Definition>>

---

# 14. PBI-04 — PROJECT PROPOSAL ENGINEERING AND VERIFICATION

**Identifier:** `[PROJECT-ID][PBI-04-0004.04]`

## Purpose

Verify and challenge the project proposal before template assembly.

### **<<START Prompt 9. Project Proposal AEA Query>>**
[Designation: Lead Agent]

Analyze the established Project Proposal and develop a formal AEA Query.

<<START Project Proposal>>
{{Durable proposal reference}}
<<STOP Project Proposal>>

Test:

- strategic/value alignment;
- problem validity;
- scope coherence;
- requirements quality;
- stakeholder impact;
- technical feasibility;
- security/privacy;
- regulatory exposure;
- financial realism;
- schedule realism;
- resource feasibility;
- dependencies;
- operational sustainability;
- success-measure quality;
- assumptions and unknowns.

For each question define expected evidence and failure consequence.

<<STOP Prompt 9. Project Proposal AEA Query>>

### **<<START Prompt 10. Project Proposal AEA Review>>**
[Designation: Collaborating Agents]

Independently analyze the Project Proposal AEA Query.

<<START Project Proposal AEA Query>>
{{Durable query}}
<<STOP Project Proposal AEA Query>>

Challenge the proposal rather than polishing it.

Report material findings using:

`Finding → Evidence → Impact → Severity → Affected Proposal Element → Recommendation`

Decision set:
`AEA-CONCUR / AEA-CONCUR-WITH-CONDITIONS / AEA-DISAGREE / AEA-BLOCKED`

<<STOP Prompt 10. Project Proposal AEA Review>>

### **<<START Prompt 11. Project Proposal AEV>>**
[Designation: Lead Agent]

Synthesize all proposal AEA reports into a controlled AEV baseline.

<<START Proposal AEA Reports>>
{{Links to all reports}}
<<STOP Proposal AEA Reports>>

Preserve minority findings and distinguish:

- verified facts;
- assumptions;
- proposed decisions;
- material risks;
- unresolved unknowns;
- conditions of advancement.

Every condition shall have an owner, evidence requirement and advancement consequence.

Decision set:
`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCK / RESET`

<<STOP Prompt 11. Project Proposal AEV>>

### **<<START Prompt 12. Project Proposal AEC and Closure>>**
[Designation: Independent Challenge Authority / Lead Agent for Closure]

Attack the verified Project Proposal.

<<START Proposal AEV>>
{{Durable AEV reference}}
<<STOP Proposal AEV>>

Attempt to demonstrate:

- false or weak value;
- hidden scope;
- unowned authority;
- impossible constraints;
- unrealistic schedule;
- unrealistic financial assumptions;
- underestimated risk;
- stakeholder conflict;
- legal/procurement contradiction;
- security/privacy weakness;
- dependency failure;
- operational impossibility;
- unmeasurable success criteria.

For every material challenge, produce evidence and disposition.

No material blocker may be closed without evidence and appropriate authority.

Decision set:
`AECC-CLOSED / AECC-CONDITIONAL / RETURN / BLOCK / RESET`

<<STOP Prompt 12. Project Proposal AEC and Closure>>

---

# 15. PBI-05 — PROJECT TEMPLATE ASSEMBLY

**Identifier:** `[PROJECT-ID][PBI-05-0004.05]`

## Purpose

Assemble the project-specific operating-template skeleton from the verified proposal.

Minimum components, as applicable:

- project identity;
- governance;
- authority register;
- authority-permission matrix;
- identifier registry;
- stakeholders;
- objectives/outcomes;
- scope;
- requirements;
- architecture/ADR index;
- schedule;
- finance;
- resources;
- risk/assumption/issue registers;
- quality;
- security/privacy;
- data classification;
- AI governance where applicable;
- procurement;
- communication;
- Task Packet model;
- evidence model;
- repository model;
- agent instructions;
- decision ledger;
- change model;
- verification;
- testing;
- release;
- operational readiness;
- incident response;
- transition;
- closure.

### **<<START Prompt 13. Project Template Assembly>>**
[Designation: Lead Agent / Authorized Template Engineer]

Generate the project-template skeleton from the verified proposal.

<<START Verified Project Proposal>>
{{Durable proposal reference}}
<<STOP Verified Project Proposal>>

For every mandatory artifact:

- assign an artifact ID;
- assign canonical location;
- identify owner;
- identify authority;
- identify status;
- identify evidence requirement;
- identify dependencies;
- identify entry criteria;
- identify exit criteria.

Distinguish:

`SKELETON` from `OPERATING TEMPLATE`.

Do not populate unknown facts by invention.

Every mandatory item must be:

`POPULATED / NOT APPLICABLE WITH RATIONALE / DEFERRED WITH AUTHORITY / BLOCKED WITH REASON`

<<STOP Prompt 13. Project Template Assembly>>

---

# 16. PBI-06 — PROJECT TEMPLATE ENGINEERING AND READINESS

**Identifier:** `[PROJECT-ID][PBI-06-0004.06]`

## Purpose

Verify that the assembled project template is internally coherent, executable as a governance mechanism and ready for simulation.

### **<<START Prompt 14. Project Template Verification>>**
[Designation: Collaborating Agents]

Verify the complete project template.

<<START Project Template>>
{{Durable template reference}}
<<STOP Project Template>>

For every mandatory control identify:

1. requirement;
2. mechanism;
3. authority;
4. owner;
5. evidence;
6. failure state;
7. recovery path;
8. maturity state.

Test:

- identifiers;
- authority/permission alignment;
- protected-control precedence;
- evidence;
- Task Packet scope;
- stop/reset/emergency controls;
- security boundary;
- dependency declarations;
- change/materiality routing;
- agent-role separation;
- human escalation;
- operational readiness;
- drift controls.

Do not upgrade maturity without evidence.

Decision set:
`READY / READY-WITH-CONDITIONS / RETURN / BLOCK / RESET`

<<STOP Prompt 14. Project Template Verification>>

### **<<START Prompt 15. Project Template Adversarial Challenge>>**
[Designation: Independent Challenge Agent]

Attempt to break the project template without modifying it.

<<START Project Template>>
{{Durable template reference}}
<<STOP Project Template>>

Attack:

- duplicate identifiers;
- conflicting instructions;
- missing authority;
- hidden privilege;
- Task Packet expansion;
- emergency bypass;
- weak stop conditions;
- evidence replacement;
- stale references;
- risk misclassification;
- low-risk change accumulation;
- operational-readiness gaps;
- human/agent collision;
- compromised automation;
- repository-admin override;
- privacy/security gaps;
- legal/regulatory gaps;
- impossible recovery paths.

For each material issue construct the smallest reproducible failure scenario.

Decision set:
`CHALLENGE-PASS / CHALLENGE-PASS-WITH-FINDINGS / CHALLENGE-BLOCKED / ARCHITECTURAL-RESET`

<<STOP Prompt 15. Project Template Adversarial Challenge>>

---

# 17. PBI-07 — PROJECT FRAMEWORK INITIALIZATION

**Identifier:** `[PROJECT-ID][PBI-07-0004.07]`

## Purpose

Instantiate the verified project template in the actual governance/development environment without silently substituting missing controls.

Where applicable establish:

- governance repository;
- project repository;
- project folders;
- documentation locations;
- ADR location;
- Decision Ledger;
- Task Packet storage;
- identifier registry;
- Authority Register;
- Authority-Permission Matrix;
- branch protections;
- AGENTS/instruction hierarchy;
- security/secret boundary;
- CI/CD policy;
- evidence storage;
- audit logging.

### **<<START Prompt 16. Project Framework Initialization>>**
[Designation: Lead Agent + Authorized Project Operator]

Initialize the project framework using the verified template.

<<START Verified Project Template>>
{{Durable template reference}}
<<STOP Verified Project Template>>

Before changing any environment:

1. verify project identity;
2. verify human authority;
3. verify repository ownership;
4. verify branch protections;
5. verify canonical governance locations;
6. verify identifier registry;
7. verify Task Packet mechanism;
8. verify security/secret boundary;
9. verify instruction precedence;
10. verify audit/evidence paths.

Record each initialization action.

If a required control is absent, do not create an informal substitute and proceed as though the control exists. Enter the appropriate blocked/not-ready state and escalate.

Decision set:
`INITIALIZED / INITIALIZED-WITH-CONDITIONS / BLOCKED / RESET`

<<STOP Prompt 16. Project Framework Initialization>>

---

# 18. PBI-08 — PROJECT SIMULATION AND READINESS EXERCISE

**Identifier:** `[PROJECT-ID][PBI-08-0004.08]`

## Purpose

Dry-run the initialized framework to determine whether governance outcomes can be reproduced from defined controls.

Minimum scenarios, where applicable:

1. normal task authorization;
2. unauthorized scope expansion;
3. conflicting instructions;
4. missing human authority;
5. agent unavailability;
6. failed verification;
7. failed challenge;
8. registry conflict;
9. stale baseline;
10. dependency failure;
11. security incident;
12. emergency change;
13. rollback/recovery;
14. operational incident;
15. sponsor/authority withdrawal;
16. material scope change.

### **<<START Prompt 17. PBIM Project Simulation>>**
[Designation: Lead Agent / All Collaborating Agents]

Execute a dry-run of the initialized framework.

<<START Initialized Framework>>
{{Durable initialized-framework reference}}
<<STOP Initialized Framework>>

For each scenario determine:

- trigger;
- current state;
- authorized actor;
- expected transition;
- required evidence;
- stop condition;
- recovery;
- escalation;
- audit record;
- reset requirement.

Do not perform production changes.

The simulation passes only if the expected result can be reproduced from documented and implemented controls rather than informal agent knowledge.

For each failure distinguish:

`DESIGN DEFECT / IMPLEMENTATION DEFECT / OPERATIONAL DEFECT / EVIDENCE DEFECT / HUMAN-AUTHORITY DECISION`

Decision set:
`SIMULATION-PASS / PASS-WITH-CONDITIONS / FAIL / RESET`

<<STOP Prompt 17. PBIM Project Simulation>>

### **<<START Prompt 18. Operational Readiness Review>>**
[Designation: Operations/Release Agent + Collaborating Assurance Agents]

Review operational readiness using the initialized project framework.

<<START Operational Readiness Evidence>>
{{Links to simulation, controls and readiness evidence}}
<<STOP Operational Readiness Evidence>>

Evaluate applicability and evidence for:

- monitoring;
- alerting;
- logging;
- incident ownership;
- rollback;
- backup/restore;
- data recovery;
- migration reversibility;
- security response;
- dependency availability;
- capacity;
- support ownership;
- documentation;
- user/stakeholder readiness.

Explicitly mark each item:

`APPLICABLE / NOT APPLICABLE WITH RATIONALE / DEFERRED / BLOCKED`

Do not treat implementation verification as equivalent to operational readiness.

Decision set:
`READY / READY-WITH-CONDITIONS / NOT-READY / BLOCKED`

<<STOP Prompt 18. Operational Readiness Review>>

---

# 19. PBI-09 — PBIM ACTIVATION AND CHARTER READINESS

**Identifier:** `[PROJECT-ID][PBI-09-0004.09]`

## Purpose

Determine whether the pre-charter package is sufficiently prepared to enter formal Project Charter development.

Activation checklist:

- [ ] project identity established;
- [ ] expected duration/start/end defined and labelled as expected;
- [ ] authority established;
- [ ] constitutional authority mapped where required;
- [ ] PBIM assurance completed;
- [ ] project proposal verified;
- [ ] project template ready;
- [ ] identifier registry established;
- [ ] authority-permission matrix established;
- [ ] security/secret boundary defined;
- [ ] evidence model established;
- [ ] Task Packet mechanism defined where applicable;
- [ ] Decision Ledger established;
- [ ] stop/reset/emergency controls defined;
- [ ] simulation completed;
- [ ] operational readiness reviewed;
- [ ] material blockers resolved or formally escalated;
- [ ] Charter inputs traceable to PBIM artifacts.

### **<<START Prompt 19. PBIM Activation and Charter Readiness>>**
[Designation: Lead Agent + Human Project Authority]

Determine whether PBIM may advance to Project Charter Development.

<<START PBIM Activation Package>>
1. PBIM baseline.
2. Project proposal.
3. Project template.
4. Initialization evidence.
5. Simulation results.
6. Operational-readiness review.
7. Risk/finding registers.
8. Decision Ledger.
<<STOP PBIM Activation Package>>

Review:

1. identity;
2. authority;
3. expected schedule assumptions;
4. proposal;
5. risk;
6. architecture;
7. template;
8. initialized controls;
9. security boundary;
10. evidence;
11. Task Packet controls;
12. simulation;
13. operational readiness;
14. unresolved findings.

For every condition or blocker identify:

`Owner → Required Evidence → Advancement Impact → Escalation Authority`

Do not convert assumptions into approvals.

Return exactly one:

`READY FOR CHARTER / READY WITH FORMAL CONDITIONS / BLOCKED / ARCHITECTURAL RESET`

<<STOP Prompt 19. PBIM Activation and Charter Readiness>>

---

# 20. GOV-01-0004.1 — DEVELOP PROJECT CHARTER

**Identifier:** `[PROJECT-ID][GOV-01-0004.1]`

This is the final PBIM identifier.

PBIM hands the verified pre-charter package into formal Project Charter development.

The Charter must trace material elements to PBIM sources, decisions or explicitly recorded new Charter-stage inputs.

The Charter is not authorized merely because an agent generated it.

Required human authority must approve it under the applicable organizational governance model.

Upon Charter authorization:

```text
PBIM STATUS = COMPLETE
PROJECT STATUS = CHARTERED / ACTIVE
PBIM IDENTIFIERS = TERMINATED AT 0004.1
```

### **<<START Prompt 20. Project Charter Handoff>>**
[Designation: Lead Agent + Human Project Authority]

Prepare the Project Charter handoff package from the completed PBIM.

<<START PBIM Charter-Readiness Evidence>>
{{Durable PBIM readiness reference}}
<<STOP PBIM Charter-Readiness Evidence>>

Trace:

- project identity;
- problem/opportunity;
- objectives;
- expected outcomes;
- scope;
- stakeholders;
- authority;
- expected duration/start/end;
- major constraints;
- assumptions;
- risks;
- dependencies;
- governance;
- security/privacy/regulatory obligations;
- delivery approach;
- approval/signature mechanism.

For each material Charter element identify its PBIM source artifact, decision or explicitly new Charter-stage input.

Do not introduce material facts without classification.

The output is a Charter preparation package, not an authorization.

Decision set:
`CHARTER-READY / CHARTER-READY-WITH-CONDITIONS / CHARTER-BLOCKED`

<<STOP Prompt 20. Project Charter Handoff>>

---

# 21. TASK PACKET CONTROL

Where the project uses Task Packets, each implementation packet shall contain at least:

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

A Task Packet cannot authorize work outside its declared scope or above its authority.

Where automation is available, the implementation environment should compare actual changes against the Task Packet scope manifest.

The presence of a proposed automation mechanism in this PBIM is not evidence that it exists or works.

---

# 22. CHANGE MATERIALITY

Use a generic four-level model unless the instantiated project defines a better controlled scale.

| Level | Meaning | Minimum handling |
|---|---|---|
| `0` | non-material task-level change | task control |
| `1` | limited baseline-impacting change | impact review |
| `2` | material architecture/scope/security change | AEV + AEC as applicable |
| `3` | load-bearing or constitutional change | Architectural Reset / competent authority process |

Cumulative effects must be considered.

Splitting a material change into smaller commits does not make it non-material.

---

# 23. SECURITY, PRIVACY AND AI GOVERNANCE

Security and privacy shall be integrated into the lifecycle rather than added only at release.

Controls should address, as applicable:

- least privilege;
- secrets management;
- identity and access management;
- secure development;
- dependency/supply-chain risk;
- vulnerability management;
- logging and auditability;
- privacy/data minimization;
- data classification;
- retention;
- incident response;
- recovery;
- secure configuration.

For AI-enabled projects, applicability should also be assessed for:

- AI system purpose and scope;
- human oversight;
- model/data provenance;
- evaluation and testing;
- misuse and abuse scenarios;
- privacy;
- security;
- transparency;
- monitoring;
- change management;
- AI-specific risk treatment.

Applicable AI guidance may include NIST AI RMF and ISO/IEC 42001.

---

# 24. FAILURE AND RECOVERY

## Agent unavailable

Do not silently combine independent roles.

Determine:

- authorized substitute;
- human review requirement;
- task stop requirement.

## Conflicting analysis

Preserve both positions. Reconcile with evidence. Escalate unresolved material disagreement.

## Failed verification

Return to the appropriate prior stage. Do not mark the artifact approved.

## Failed challenge

Amend and re-verify, or reset to AEA.

## Registry failure

Block governance progression if authoritative identifier state cannot be trusted.

## Evidence failure

Downgrade the decision state and require re-verification.

## Automation compromise

Automation is not inherently trusted. A compromised validator, CI system or deployment mechanism must be capable of triggering a governance stop and independent review.

---

# 25. DRIFT CONTROL

The project shall periodically compare:

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
- dependency/environment drift.

Material undocumented divergence triggers review.

Approval may expire following material:

- environment change;
- requirement change;
- dependency change;
- security incident;
- architecture change;
- authority change;
- governance-defined expiry.

---

# 26. AUTHORIZATION STATE MODEL

The following vocabulary should be used consistently:

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
ARCHIVED
```

Not every project requires every state. An omitted state must be explicitly declared not applicable.

---

# 27. CONTROLLED PROMPT FORMAT STANDARD

Every PBIM operational prompt shall use this exact structural pattern:

```text
<<START Prompt N. {{Prompt Label}}>>
[Designation: Lead Agent / Collaborating Agents / other authorized role]

{{Prompt instructions}}

<<START {{Resource Label}}>
{{Resource links or resource slots}}
<<STOP {{Resource Label}}>

Notes:
1. {{Note}}
2. {{Note}}

{{Additional instructions, if required}}

<<STOP Prompt N. {{Prompt Label}}>>
```

Rules:

1. The opening and closing labels must match exactly.
2. `[Designation: ...]` must be explicit.
3. Resource blocks must use `<<START ...>>` and `<<STOP ...>>`.
4. Notes, when present, appear below the resource block.
5. Resource blocks contain links/references, not undocumented claims.
6. Prompts must state what the agent must not infer or invent.
7. Prompts must distinguish evidence classes.
8. Prompts must define stop conditions.
9. Prompts must define a bounded decision set.
10. Prompts must not grant authority merely by wording.
11. Review/challenge prompts must disclose independence limitations.
12. Prompts must not claim an artifact exists unless the supplied evidence establishes existence.

---

# 28. LEGACY-TO-GENERIC CROSSWALK

| Legacy concept | v2.01 disposition |
|---|---|
| PBIM Document Creation | Retained as PBI-01 and strengthened |
| PBIM Document Development | Retained as PBI-02 assurance |
| Project Proposal Definition | Retained as PBI-03 |
| Project Proposal Development | Retained as PBI-04 |
| Project Template Creation | Retained as PBI-05 |
| Project Template Development | Retained as PBI-06 |
| Project Framework Initialization | Retained as PBI-07 |
| Project Simulation | Retained as PBI-08 |
| PBIM Implementation | Reframed as PBIM Activation & Charter Readiness |
| Develop Project Charter | Retained as final `0004.1` boundary |
| 40/49 PMBOK process claim | Removed; local process spine is classification only |
| Numeric ID as universal identity | Removed; identity dimensions separated |
| Evidence/provenance/durable references as separate repeated controls | **Merged into Evidence & Traceability Control** |
| Stop/reset/emergency/failure recovery as repeated controls | **Merged into Controlled State & Recovery Model** |
| Repeated Lead/Collaborator prompt boilerplate | **Merged into Prompt Engineering Contract** |
| Agent role as authority | Removed |
| Documentation as enforcement proof | Removed |
| Challenge independence by title alone | Removed |
| Unbounded emergency authority | Removed |
| Informal local copy as canonical state | Removed |
| Project-specific examples as generic requirements | Removed |

---

# 29. PBIM OPERATING PRINCIPLES

1. **Authority precedes capability.**
2. **Specification does not equal implementation.**
3. **Evidence must be traceable to a credible source.**
4. **Material dissent remains visible.**
5. **Challenge attempts falsification rather than confirmation.**
6. **Independence is demonstrated, not declared.**
7. **Risk scaling reduces ceremony without weakening protected controls.**
8. **Technical access does not create governance authority.**
9. **Identifiers classify; they do not replace project/work/artifact identity.**
10. **Emergency procedures are bounded exceptions, not permanent bypasses.**
11. **Cumulative change can become material.**
12. **Operational readiness is distinct from implementation verification.**
13. **Release authorization is distinct from implementation authorization.**
14. **A failed control causes a governed stop, return or reset.**
15. **PBIM ends at the Project Charter boundary.**
16. **Expected dates are planning probes until formally baselined.**
17. **Generic PBIM rules must remain independent of example-specific implementation technology.**
18. **Prompt structure is controlled so that agent outputs remain reproducible and comparable.**

---

# 30. FINAL PBIM READINESS QUESTIONS

Before advancing to `0004.1`, answer:

1. Is the project identity controlled?
2. Is the human authority identified?
3. Are authority and technical capability separated?
4. Is the risk profile defined?
5. Are expected duration/start/end values identified as estimates rather than commitments?
6. Is the PBIM baseline traceable?
7. Is the identifier registry trustworthy?
8. Are material claims evidence-classified?
9. Is the project proposal verified?
10. Has the project proposal been challenged?
11. Is the project template internally coherent?
12. Are Task Packets bounded where applicable?
13. Are security/privacy boundaries defined?
14. Can the project stop safely?
15. Can it recover or reset safely?
16. Is emergency authority bounded?
17. Has the framework been initialized?
18. Has it been simulated?
19. Has operational readiness been assessed?
20. Are material blockers resolved or formally escalated?
21. Are Charter inputs traceable to PBIM artifacts?
22. Is the human authority prepared to enter the formal Charter process?

If any mandatory answer is unresolved:

```text
DO NOT ADVANCE TO 0004.1
```

unless the matter is explicitly classified and authorized as a Charter-stage input rather than a pre-charter blocker.

---

# 31. IMPLEMENTATION-VERIFICATION OBLIGATIONS

This PBIM may specify the following mechanisms without claiming they already operate:

1. Authority Register;
2. Authority-Permission Matrix;
3. identifier registry;
4. registry integrity controls;
5. evidence-integrity mechanism;
6. challenge-independence controls;
7. instruction-drift detection;
8. Task Packet scope enforcement;
9. stop/reset enforcement;
10. repository/branch protections;
11. secret/security controls;
12. operational-readiness gate;
13. durable-reference controls;
14. audit/evidence retention.

Each must be independently evidenced before being represented as `ENFORCED` or `INDEPENDENTLY VERIFIED`.

---

# 32. FINAL STATUS

**Generic PBIM Version:** `v2.01.00`

**Current state:** `DESIGNED — CONTROLLED GENERIC CANDIDATE`

**Implementation authorization:** `NOT GRANTED`

**Production authorization:** `NOT GRANTED`

**Expected project duration:** `{{EXPECTED-PROJECT-DURATION}}`

**Expected project start date:** `{{EXPECTED-PROJECT-START-DATE}}`

**Expected project end date:** `{{EXPECTED-PROJECT-END-DATE}}`

**PBIM completion condition:** successful completion of the pre-charter gates through `PBI-09` and authorized entry into `GOV-01-0004.1`.

**PBIM termination boundary:**

```text
GOV-01-0004.1 DEVELOP PROJECT CHARTER
```

No PBIM section may be created after the Charter boundary under this generic edition.

---

# 33. SOURCE AND ALIGNMENT REGISTER

## PBIM lineage reviewed

1. `Project_Base_Integration_Manager-v2.00.00.md`
2. `Project_Base_Integration_Manager-v1.13.00.md`
3. `Project_Base_Integration_Manager-v1.12.05.md`
4. `Project_Base_Integration_Manager-v1.12.00.md`
5. `Project_Base_Integration_Manager-v1.11.00.md`
6. `Project_Base_Integration_Manager-v1.10.00.md`
7. `Project_Base_Integration_Manager-v1.05.00.md`
8. `Project_Base_Integration_Manager-v1.04.00.md`
9. `Project_Base_Integration_Manager-v1.03.00.md`
10. `Project_Base_Integration_Manager-v1.00.00.md`

## External alignment references

- PMI, PMBOK Guide — Eighth Edition and The Standard for Project Management.
- ISO 21502:2020, Project, programme and portfolio management — Guidance on project management.
- ISO 31000:2018, Risk management — Guidelines.
- NIST SP 800-218, Secure Software Development Framework (SSDF) Version 1.1; draft/revision status shall be checked before project adoption.
- NIST AI Risk Management Framework 1.0 and applicable profiles when AI is material.
- ISO/IEC 42001:2023 when an AI management-system framework is applicable.
- Applicable jurisdictional law, regulation and organizational policy.

## Alignment rule

No external standard, policy or regulation is incorporated as a claim of compliance merely because it appears in this register. Applicability, tailoring and implementation evidence must be established for the instantiated project.

---

# END OF GENERIC PROJECT BASE INTEGRATION MANAGER v2.01.00
