# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Generic Value |
|---|---|
| **Document** | Project Base Integration Manager — Generic Consolidated Edition |
| **Version** | **v3.00.05** |
| **Generated** | 2026-10-08 (UTC) |
| **Status** | **CONTROLLED GENERIC CANDIDATE — DESIGNED ONLY** |
| **Architectural Status** | `DESIGNED` — not evidence of implementation, enforcement, or independent verification |
| **Implementation Authorization** | `NOT GRANTED BY THIS DOCUMENT` |
| **Production Authorization** | `NOT GRANTED BY THIS DOCUMENT` |
| **PBIM Boundary** | Ends at `[PROJECT-ID][GOV-01-0004.01] — Develop Project Charter` |
| **Nature** | Generic probing/pre-charter framework; expected dates are estimates, not commitments |
| **Supersedes for candidate development** | v3.00.04 and prior v3.00.x candidate lineage |
| **Primary design objective** | Establish a controlled, evidence-traceable, risk-scaled project-ready baseline before formal Project Charter development |

---

# 0. DOCUMENT CONTROL, PURPOSE AND READER'S GUIDE

## 0.1 What PBIM is

The Project Base Integration Manager (PBIM) is a generic **pre-charter governance, integration, assurance and readiness framework**. It probes whether an idea, request, opportunity or proposed initiative can be converted into a sufficiently coherent and controlled pre-charter baseline for formal Project Charter development.

PBIM establishes:

1. project identity and governance boundaries;
2. authority and decision-right separation;
3. evidence, provenance and traceability controls;
4. expected—not committed—project timing;
5. project context and proposal definition;
6. proposal assurance and challenge;
7. project-template assembly and verification;
8. project-framework initialization where justified;
9. controlled simulation and operational-readiness review;
10. final pre-charter readiness and handover.

PBIM does **not** itself constitute:

- a Project Charter;
- a Project Management Plan;
- a detailed technical design;
- a production authorization;
- an implementation authorization;
- a legal opinion;
- a statutory approval;
- a security certification;
- a safety certification;
- an operational approval;
- a substitute for competent professional judgment;
- an organization's constitutional, legal, regulatory or governance authority.

## 0.2 How to use this document

Run the PBIM sections sequentially:

`PBI-01 → PBI-02 → PBI-03 → PBI-04 → PBI-05 → PBI-06 → PBI-07 → PBI-08 → PBI-09 → GOV-01-0004.01`

A later section may not silently bypass a mandatory predecessor gate.

For each prompt:

1. copy the prompt exactly;
2. replace every declared `{{TOKEN}}`;
3. attach the listed resources as durable references;
4. preserve the prompt's designation;
5. send the prompt to the designated agent(s);
6. preserve the complete response;
7. classify substantive claims;
8. record findings, decisions and evidence in the applicable registers;
9. do not treat an agent response as authority merely because the response is confident or unanimous;
10. advance only when the section's exit criteria are satisfied.

## 0.3 Genericity rule

This document contains no project-specific organization, product, vendor, repository, technology stack, country, legal conclusion, person, agent product, branch, budget or schedule.

Instantiation replaces placeholders only with information supported by project evidence or an explicitly recorded assumption/proposal.

A project-specific fact must never be introduced into the generic PBIM merely because it appeared in an historical PBIM document.

---

# 0.4 CONSOLIDATED MERGE REGISTER

The following repeated or materially similar concepts found across the supplied v3.00.00–v3.00.04 lineage are deliberately consolidated rather than reproduced.

| ID | Repeated/similar concepts | Consolidated control | Result |
|---|---|---|---|
| **M-01** | Repeated AEA → AEV → AEC → AECC cycles for PBIM, Proposal and Template | **Reusable Assurance Cycle** | One assurance protocol invoked by subject rather than copied three times |
| **M-02** | Evidence classes, provenance, canonical links, hashes, traceability and durable references | **Evidence & Traceability Control** | One evidence chain from source through decision and verification |
| **M-03** | Stop states, reset rules, emergency delegation, failure recovery and blocked states | **Controlled State & Recovery Model** | One state/recovery mechanism |
| **M-04** | Human authority, agent roles, tool-specific roles and independence rules | **Authority & Role Model** | Capability is separated from authority |
| **M-05** | Activation checklist, readiness checklist, charter-readiness checklist and final gate | **Pre-Charter Gate Register** | One authoritative final gate |
| **M-06** | Multiple Task Packet definitions and execution-boundary descriptions | **Task Packet Control** | One bounded work authorization mechanism |
| **M-07** | Multiple identifier schemes and incompatible numeric forms | **Identifier Architecture & Registry** | One grammar, registry and supersession model |
| **M-08** | Risk profiles, materiality rules and cumulative-change warnings | **Risk & Materiality Control** | One scalable risk model |
| **M-09** | Prompt formatting rules repeated across sections | **Controlled Prompt Contract** | One mandatory prompt anatomy used by every prompt |
| **M-10** | Authorization, document, maturity and lifecycle states described separately or inconsistently | **Orthogonal State Model** | Control maturity, artifact state and authorization state remain distinct |
| **M-11** | PBIM creation, document development, proposal development, template development and framework setup were sometimes treated as implementation | **Specification-to-Implementation Boundary** | Specification never proves implementation |
| **M-12** | Standards lists repeated without applicability logic | **Standards Applicability Register** | Standards are assessed for relevance and currency rather than assumed applicable |

---

# 0.5 IMPORTANT DEFECTS CORRECTED IN THIS EDITION

| ID | Defect pattern | Correction |
|---|---|---|
| D-01 | Mixed `0004.1`, `0004.10`, `0000.0x` and `0004.0x` forms | Fixed-width integer-segment grammar |
| D-02 | PBIM steps confused with PM process identifiers | `PBI` reserved for PBIM pre-charter sections |
| D-03 | Final Charter identifier appeared as multiple incompatible IDs | One terminal identifier: `GOV-01-0004.01` |
| D-04 | Section and artifact IDs could diverge | Artifact identifiers reference their governing section |
| D-05 | Repeated assurance prompts invited copy/paste errors | Parameterized assurance protocol |
| D-06 | "Unanimous approval" or percentage scoring used as sole closure authority | Evidence-based gates; dissent remains material |
| D-07 | Expected project duration was sometimes treated as a commitment | Explicit `EXPECTED-*` semantics |
| D-08 | Expected start/end dates were absent or insufficiently defined | Three expected schedule fields plus derivation rules |
| D-09 | Project-specific examples were embedded in generic controls | Generic placeholders only |
| D-10 | Documentation was sometimes treated as evidence that controls operate | Maturity model requires operational evidence |
| D-11 | Agent role and human authority were conflated | Authority is explicitly human/organizational; agents are capabilities |
| D-12 | Emergency powers could become open-ended | Scope-, authority-, evidence- and time-bounded emergency delegation |
| D-13 | Independent challenge could be satisfied by a different title held by the same actor | Independence is assessed, not declared |
| D-14 | Security/privacy/AI considerations could appear only late in lifecycle | Applicability is assessed from context and throughout relevant stages |
| D-15 | Project-management framework references could be mistaken for a fixed PMBOK process list | Local classification spine is explicitly non-canonical |
| D-16 | Some later versions were labelled with earlier version numbers | This candidate has a single explicit version identity |
| D-17 | Prompt start/end syntax varied | Exact prompt marker contract below is mandatory |

---

# 1. GOVERNANCE BOUNDARY AND ARCHITECTURAL MODEL

## 1.1 PBIM boundary

PBIM ends at:

`[PROJECT-ID][GOV-01-0004.01] — Develop Project Charter`

PBIM may prepare evidence and inputs for the Charter, but it does not authorize the project merely by reaching its final gate.

After the boundary, the authorized Project Charter and the project's applicable governance/lifecycle framework become authoritative.

PBIM must not create project-lifecycle identifiers beyond the Charter boundary.

## 1.2 Three-layer architecture

```text
┌──────────────────────────────────────────────┐
│ GOVERNANCE                                   │
│ authority • constraints • decision rights    │
│ protected controls • applicability           │
└──────────────────────┬───────────────────────┘
                       ↓
┌──────────────────────────────────────────────┐
│ ASSURANCE                                    │
│ AEA → AEV → AEC → AECC                      │
│ evidence • verification • challenge          │
│ materiality • independence • closure         │
└──────────────────────┬───────────────────────┘
                       ↓
┌──────────────────────────────────────────────┐
│ AUTHORIZED EXECUTION                         │
│ bounded work • Task Packets • verification   │
│ operational readiness • recovery             │
└──────────────────────────────────────────────┘
```

Core principles:

- authority precedes capability;
- specification is not implementation;
- documentation is not operational evidence;
- consensus is not proof;
- an agent cannot manufacture authority by wording;
- a lower-level instruction cannot weaken a protected higher-level control;
- material dissent must remain visible;
- a stop cannot be cleared by the executor who caused or encountered the stop.

## 1.3 Instruction precedence

Unless a higher authority is legally or organizationally required, the generic precedence model is:

`Applicable law/regulation → constitutional/organizational authority → protected governance/security controls → approved PBIM baseline → approved governance documents → authorized Project Charter → project-level controlled instructions → Task Packet → tool/runtime defaults → informal instruction`

Where two requirements conflict, the lower-level instruction does not silently override the higher-level control.

A conflict that cannot be resolved by evidence and recorded authority produces a governance stop.

---

# 2. PROJECT IDENTITY AND EXPECTED PROJECT SCHEDULE

Complete the identity block before PBI-01 is executed.

```text
PROJECT-KEY                 : {{PROJECT-KEY}}
PROJECT-NAME                : {{PROJECT-FULL-NAME}}
PROJECT-ID                  : {{PROJECT-ID}}
PROJECT-BASE                : {{PROJECT-BASE-NAME}}
BASE-ID                     : {{BASE-ID}}
ORGANIZATION-CHAIN          : {{ORG}} → {{DEPARTMENT}} → {{PMO/OWNER}}
PROJECT-LOCATION            : {{TOWN/DISTRICT/CITY/REGION/COUNTRY OR N/A}}
GOVERNANCE-REPOSITORY       : {{GOVERNANCE-REPOSITORY}}
PROJECT-REPOSITORY          : {{PROJECT-REPOSITORY OR N/A}}
RISK-PROFILE                : LIGHT | STANDARD | HIGH-ASSURANCE
DELIVERY-APPROACH           : PREDICTIVE | ITERATIVE | INCREMENTAL | ADAPTIVE | HYBRID | OTHER
JURISDICTION(S)             : {{JURISDICTION(S)}}
EXPECTED-PROJECT-DURATION   : {{VALUE + UNIT + RANGE + BASIS}}
EXPECTED-PROJECT-START-DATE : {{YYYY-MM-DD + RANGE + BASIS}}
EXPECTED-PROJECT-END-DATE   : {{YYYY-MM-DD + RANGE + BASIS}}
PBIM-STATE                  : DRAFT
IMPLEMENTATION-AUTHORIZATION: NOT GRANTED
PRODUCTION-AUTHORIZATION    : NOT GRANTED
```

## 2.1 Expected-project-date semantics

The fields:

- `EXPECTED-PROJECT-DURATION`
- `EXPECTED-PROJECT-START-DATE`
- `EXPECTED-PROJECT-END-DATE`

are **expected planning estimates** because PBIM is a probing/pre-charter document.

They are not:

- approved commitments;
- investor promises;
- sponsor guarantees;
- schedule baselines;
- implementation authorization;
- release authorization.

If an input does not support a defensible estimate, use `UNKNOWN` rather than inventing a value.

## 2.2 Expected duration derivation

Record:

1. estimating basis: analogous, parametric, three-point, expert, historical, other;
2. expected effort;
3. expected calendar duration;
4. human capacity assumptions;
5. planned review/verification latency;
6. dependencies;
7. non-working days where material;
8. jurisdictional working-time constraints where applicable;
9. optimistic / expected / pessimistic range;
10. confidence or uncertainty statement.

Generic model:

```text
EXPECTED-PROJECT-DURATION
≈ expected effort
  ÷ realistic available human capacity
  + dependency latency
  + assurance/review latency
  + material calendar constraints
```

Do not treat agent compute time as equivalent to human governance capacity.

## 2.3 Expected start and end dates

`EXPECTED-PROJECT-START-DATE` is the earliest plausible project-start expectation based on known authority, dependencies, resource availability and intended delivery approach.

`EXPECTED-PROJECT-END-DATE` is derived from the expected start, expected duration and applicable working calendar.

If the derived end date and supplied end date disagree materially, record a finding and do not silently correct the input.

## 2.4 Expected-date lifecycle

Expected dates are reviewed at minimum:

- PBI-03;
- PBI-05;
- PBI-08;
- PBI-09;
- Charter development.

Historical values remain traceable.

Only a properly authorized Charter/schedule baseline may replace expected values with committed/baselined values.

---

# 3. AUTHORITY, ROLES AND INDEPENDENCE

## 3.1 Human authority model

| Code | Role | Generic responsibility |
|---|---|---|
| `CA` | Constitutional/Root Governance Authority | Protects constitutional or organizational controls where such a role exists |
| `H0` | Human Project Authority | Final governance decision-maker within delegated project authority |
| `H1` | Delegated Governance Authority | Acts within recorded delegation |
| `H2` | Authorized Operational/Technical Authority | Executes authorized operational/technical decisions without inheriting governance authority |

Organizations may map their own titles to these roles.

Technical access does not create governance authority.

If a required authority is unavailable, the project enters the applicable blocked state rather than allowing an agent to self-assume authority.

## 3.2 Agent capability model

| Code | Capability | Function |
|---|---|---|
| `LEAD` | Lead Agent | orchestration, synthesis, gate preparation, evidence preservation |
| `ANL` | Analysis Agent | independent analysis |
| `VER` | Verification Agent | evidence, reproducibility and traceability verification |
| `SEC` | Security/Challenge Agent | adversarial challenge |
| `IMP` | Implementation Agent | authorized execution only |
| `TST` | Test Agent | functional, negative and control testing |
| `OPS` | Operations/Readiness Agent | operational readiness and recovery |
| `DOC` | Documentation/Registry Agent | identifiers, records, provenance |
| `HUMAN` | Human Authority | authorization and escalation |

Tools or vendors are replaceable bindings. The PBIM does not prescribe a particular AI product.

## 3.3 Role combination

A single actor may hold more than one capability only when:

- the risk profile permits it;
- independence remains sufficient;
- the actor does not approve its own work where separation is required;
- the combination is recorded.

"Same person, different title" is not automatically independent.

## 3.4 Prompt designations

Every prompt uses one explicit designation:

- `[Designation: Lead Agent]`
- `[Designation: Collaborating Agents]`
- `[Designation: Lead Agent / Collaborating Agents]`
- `[Designation: Authorized Human Authority]`
- `[Designation: Lead Agent / Authorized Human Authority]`
- another explicitly authorized role where necessary.

Designation is a routing instruction, not a grant of authority.

---

# 4. IDENTIFIER ARCHITECTURE

## 4.1 PBIM section grammar

PBIM pre-charter sections use:

```text
[PROJECT-ID][PBI-<STAGE>-0004.00.<SECTION>]
```

Examples:

```text
[PROJECT-ID][PBI-01-0004.00.01]
[PROJECT-ID][PBI-02-0004.00.02]
[PROJECT-ID][PBI-09-0004.00.09]
```

The final Charter boundary is:

```text
[PROJECT-ID][GOV-01-0004.01]
```

`0004.01` is an integer-segment anchor, not a decimal.

## 4.2 Identifier rules

1. Integer segments are compared as integers.
2. Zero padding is mandatory.
3. PBIM internal sections use `PBI`.
4. The Charter boundary uses the controlled project-management classification.
5. Identifiers are allocated by the registry.
6. Agents may propose identifiers but do not unilaterally allocate authoritative identifiers.
7. Retired identifiers are tombstoned and never reused.
8. A conflict blocks progression.
9. An identifier identifies one identity dimension only.
10. Project ID, section ID, task ID, artifact ID, decision ID, finding ID, lifecycle state and PM classification remain distinct.

## 4.3 Artifact identifiers

Recommended generic artifact classes:

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
| AEA Query | `[SECTION]-AEA-Q-##` |
| AEA Report | `[SECTION]-AEA-R-<AGENT>-##` |
| AEV Statement | `[SECTION]-AEV-S-R<cycle>.<revision>` |
| AEV Decision | `[SECTION]-AEV-D-<AGENT>-##` |
| AEC Challenge | `[SECTION]-AEC-Q-##` |
| AEC Result | `[SECTION]-AEC-R-<AGENT>-##` |
| AECC Closure | `[SECTION]-AECC-##` |

## 4.4 Identifier registry

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

Mutation sequence:

```text
REQUEST
→ RESERVE
→ VALIDATE
→ COMMIT
→ VERIFY
→ CONFIRM
```

Last-write-wins is prohibited for authoritative registry state.

---

# 5. CONTROL MODEL

## 5.1 Evidence & Traceability Control

All material claims and artifacts follow a traceability chain:

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
ARCHITECTURE / PLAN
 ↓
TASK / CHANGE
 ↓
VERIFICATION
 ↓
RELEASE / HANDOFF
```

Evidence classes:

`VERIFIED FACT | INFERENCE | ASSUMPTION | PROPOSAL | RECOMMENDATION | RISK | UNKNOWN | DISPUTED`

Rules:

- agreement does not upgrade evidence;
- repetition does not upgrade evidence;
- an `UNKNOWN` must have an owner and target date where action is possible;
- material evidence must remain retrievable;
- a sanitized report does not replace source evidence;
- inaccessible evidence remains inaccessible—it is not inferred.

## 5.2 Control maturity

| State | Meaning |
|---|---|
| `DESIGNED` | control is specified |
| `ENFORCEABLE` | a mechanism exists that can prevent, permit or detect the declared outcome |
| `ENFORCED` | evidence shows the mechanism operates in the applicable environment |
| `INDEPENDENTLY VERIFIED` | suitable independent testing confirms operation |

Documentation alone cannot establish `ENFORCED`.

Agent assertions cannot establish `INDEPENDENTLY VERIFIED`.

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

A failed assurance stage returns the artifact to the appropriate prior state.

## 5.4 Authorization state

```text
IMPLEMENTATION-NOT-AUTHORIZED
→ IMPLEMENTATION-AUTHORIZED
→ IMPLEMENTATION-VERIFIED
→ OPERATIONALLY-READY
→ RELEASE-AUTHORIZED
→ PRODUCTION
```

Other states:

`STOPPED | RESET-REQUIRED`

PBIM itself never grants production authorization.

## 5.5 Risk profile

| Profile | Use | Minimum posture |
|---|---|---|
| `LIGHT` | low-consequence, bounded, reversible | proportionate ceremony; protected controls retained |
| `STANDARD` | ordinary project work | full PBIM controls scaled to materiality |
| `HIGH-ASSURANCE` | safety, finance, security, sensitive data, regulated, irreversible or high-blast-radius work | enhanced evidence, independence, challenge and operational verification |

Scaling may reduce ceremony but may not remove protected controls.

## 5.6 Change materiality

| Level | Meaning | Minimum routing |
|---:|---|---|
| `0` | task-level/non-material | task control |
| `1` | limited baseline impact | impact review |
| `2` | material architecture/scope/security change | AEV + AEC as applicable |
| `3` | load-bearing/constitutional change | Architectural Reset or competent authority process |

Cumulative effects count.

Splitting one material change into smaller commits does not make the aggregate change non-material.

## 5.7 Stop, reset, emergency and recovery

| State | Meaning | Resume/decision authority |
|---|---|---|
| `S0` | normal | applicable owner |
| `S1` | advisory/halt-and-review | owner |
| `S2` | mandatory stop | stop issuer or H1+ |
| `S3` | system/security/integrity stop | verification/security authority + H1+ |
| `S4` | emergency safety/financial/data/authority stop | H0; CA if constitutional |

A stop:

1. preserves evidence;
2. prevents prohibited continuation;
3. identifies the decision authority;
4. records the trigger;
5. identifies recovery criteria;
6. cannot be self-cleared by the executor.

### Architectural Reset

Trigger a reset when a load-bearing assumption fails, material challenge invalidates the architecture, or the approved baseline can no longer be trusted.

Reset sequence:

```text
IDENTIFY FAILED ASSUMPTION
→ FREEZE AFFECTED WORK
→ PRESERVE EVIDENCE
→ IDENTIFY LAST TRUSTED BASELINE
→ ASSIGN RESET AUTHORITY
→ RETURN TO AEA OR EARLIEST AFFECTED STAGE
→ RE-VERIFY
→ RE-CHALLENGE
→ RE-CLOSE
```

A reset never erases adverse evidence.

### Emergency delegation

Where emergency delegation is legally and organizationally permitted, it must include:

- named authority;
- exact scope;
- permitted actions;
- prohibited actions;
- start time;
- expiry/TTL;
- evidence requirement;
- post-event reconciliation;
- automatic expiry where technically possible.

Emergency authority cannot permanently weaken the baseline.

## 5.8 Independence

Independence is assessed across applicable dimensions:

- `I1` organizational;
- `I2` evidence;
- `I3` technical;
- `I4` governance.

Consider:

- prior authorship;
- shared credentials;
- shared evidence stores;
- reporting relationship;
- decision rights;
- financial/incentive conflicts;
- operational dependency;
- privileged access.

Failure to establish required independence yields:

`CHALLENGE-INDEPENDENCE-FAILED` or `CHALLENGE-BLOCKED`.

## 5.9 Task Packet control

Where controlled execution is required, work is issued through a bounded Task Packet.

Minimum fields:

```text
task_id
objective
authority_reference
in_scope
out_of_scope
inputs
allowed_tools
prohibited_actions
branch/environment
risk_class
acceptance_criteria
evidence_required
stop_conditions
reviewers
expiry
state
```

Out-of-scope work follows:

`STOP → REPORT → NEW/SUPERSEDING TASK PACKET`

A Task Packet cannot override the PBIM baseline, Charter, applicable law, security control or authorized governance decision.

## 5.10 Decision and traceability control

Material decisions record:

- decision ID;
- date/time;
- authority;
- question;
- alternatives;
- evidence;
- decision;
- rationale;
- consequences;
- affected artifacts;
- effective revision;
- superseded decision.

An ADR records durable architectural rationale.

The Decision Ledger records operational governance history.

---

# 6. MODERN PROJECT, ENGINEERING, SECURITY AND STANDARDS ALIGNMENT

## 6.1 Project-management alignment

PBIM is compatible with contemporary project-management practice but does not claim to reproduce a canonical PMBOK process list.

PMBOK Guide — Eighth Edition is used as a reference for its six principles and seven performance domains:

**Six principles**

1. Adopt a holistic view.
2. Focus on value.
3. Embed quality.
4. Lead accountably.
5. Integrate sustainability.
6. Build an empowered team.

**Seven performance domains**

1. Governance.
2. Scope.
3. Schedule.
4. Finance.
5. Stakeholders.
6. Resources.
7. Risk.

The local PBIM identifier spine is a traceability/classification mechanism, not a claim that PBIM's numbered sections are PMBOK's canonical process list.

## 6.2 Complementary standards and guidance

Applicability is determined during instantiation.

Potential references include:

- ISO 21502 project-management guidance;
- ISO 31000 risk-management guidance;
- NIST SP 800-218 SSDF for software projects;
- NIST AI RMF for AI-related risk management;
- ISO/IEC 42001 for AI management systems where applicable;
- applicable information-security, privacy, safety, quality and procurement standards;
- applicable laws, regulations, codes and sector requirements.

A standard being listed does not establish compliance.

A draft or superseded standard must not be treated as current normative authority without explicit qualification.

## 6.3 Engineering principles

Where applicable, the instantiated project should consider:

- least privilege;
- separation of duties;
- defense in depth;
- secure defaults;
- privacy/data minimization;
- dependency and supply-chain control;
- reproducible evidence;
- automated verification where reliable;
- change traceability;
- rollback/recovery;
- observability;
- resilience;
- accessibility;
- usability;
- quality assurance;
- failure-mode analysis;
- incident and exception handling.

## 6.4 Security and privacy

Security/privacy applicability is assessed early and revisited after material changes.

Controls may include:

- identity and access management;
- secrets management;
- secure configuration;
- dependency and supply-chain risk;
- vulnerability management;
- logging and auditability;
- data classification;
- retention;
- privacy;
- incident response;
- recovery.

## 6.5 AI governance

Where AI is materially involved, assess:

- purpose and intended use;
- human oversight;
- model/data provenance;
- evaluation;
- misuse and abuse;
- security;
- privacy;
- transparency;
- monitoring;
- change management;
- AI-specific risk treatment.

The PBIM does not claim that use of NIST AI RMF or ISO/IEC 42001 alone establishes trustworthy AI or regulatory compliance.

## 6.6 Local regulatory overlay

Every instantiated project identifies applicable jurisdiction(s) and determines:

- laws/regulations;
- working-time constraints where relevant to schedule estimation;
- licensing/permits;
- safety obligations;
- data/privacy obligations;
- procurement obligations;
- professional obligations;
- sector requirements;
- records/retention obligations.

Legal conclusions remain with appropriately authorized competent persons.

---

# 7. CONTROLLED PROMPT CONTRACT

Every PBIM operational prompt must use the following structure.

```text
#**<<START Prompt N. {{Prompt Label}}>>**#
[Designation: Lead Agent / Collaborating Agents / other authorized role]

{{Prompt instructions}}

<<START {{Resource Label}}>>
{{Resource links or resource slots}}
<<STOP {{Resource Label}}>>

Note the following:
1. {{Note}}
2. {{Note}}

{{Additional instructions where required}}

#**<<STOP Prompt N. {{Prompt Label}}>>**#
```

Mandatory rules:

1. opening and closing labels must match exactly;
2. prompt number and label must match;
3. designation is explicit;
4. resource blocks use exact start/stop markers;
5. notes appear below resources;
6. resource blocks contain references/links, not unsupported claims;
7. prompts state what the agent must not infer or invent;
8. substantive claims are classified;
9. stop conditions are explicit;
10. decision sets are bounded;
11. prompts cannot grant authority merely through wording;
12. review/challenge prompts disclose independence limitations;
13. inaccessible resources are reported as inaccessible;
14. no artifact is claimed to exist unless evidence establishes existence;
15. material dissent is preserved;
16. prompts are executable without relying on hidden conversation context.

## 7.1 Standard agent response structure

Unless a section specifies another format, responses should use:

```text
1. Scope reviewed
2. Executive finding
3. Evidence
4. Evidence class
5. Materiality/severity
6. Contradictions/unknowns
7. Recommendation
8. Decision
9. Required follow-up
10. Independence disclosure
```

---

# 8. PRE-CHARTER SECTION MAP

| Stage | Identifier | Section | Primary output | Gate |
|---:|---|---|---|---|
| 1 | `PBI-01-0004.00.01` | PBIM Baseline Synthesis | PBIM candidate | Resource completeness |
| 2 | `PBI-02-0004.00.02` | PBIM Assurance | Controlled PBIM baseline candidate | PBIM assurance closure |
| 3 | `PBI-03-0004.00.03` | Project Context & Proposal Definition | Project proposal candidate | Context completeness |
| 4 | `PBI-04-0004.00.04` | Project Proposal Assurance | Verified proposal | Proposal AECC |
| 5 | `PBI-05-0004.00.05` | Project Template Assembly | Template skeleton | Assembly completeness |
| 6 | `PBI-06-0004.00.06` | Project Template Assurance | Operating template | Template assurance closure |
| 7 | `PBI-07-0004.00.07` | Project Framework Initialization | Initialized controls | Initialization verification |
| 8 | `PBI-08-0004.00.08` | Simulation & Readiness | Readiness evidence | Readiness review |
| 9 | `PBI-09-0004.00.09` | PBIM Activation & Charter Readiness | Pre-charter package | Final pre-charter gate |
| — | `GOV-01-0004.01` | Develop Project Charter | Charter input/handoff | Outside PBIM |

---

# 9. PBI-01 — PBIM BASELINE SYNTHESIS

**Identifier:** `[PROJECT-ID][PBI-01-0004.00.01]`

## Purpose

Create a generic PBIM candidate from the supplied PBIM source set.

## Implementation steps

1. Establish the Resource Manifest.
2. Confirm all supplied source versions are accessible.
3. Compare source purposes and boundaries.
4. Compare every identifier-bearing section.
5. Compare every operational prompt.
6. Identify repeated, overlapping, contradictory and obsolete concepts.
7. Identify outdated project-management, engineering, policy and standards assumptions.
8. Merge equivalent controls.
9. Preserve material historical traceability.
10. Produce the candidate PBIM.
11. Record unresolved evidence gaps.
12. Submit for independent review.

## Exit gate

`PBI-01-GATE = READY | READY-WITH-OBSERVATIONS | BLOCKED`

A material inaccessible source or unresolved authority conflict may block progression.

### **<<START Prompt 1. PBIM Generic Baseline Synthesis>>**#
[Designation: Lead Agent]

Thoroughly analyze the complete supplied PBIM source set and produce a generic PBIM candidate.

Compare, at minimum:

1. document purpose and boundary;
2. project identity;
3. expected schedule fields;
4. section identifiers;
5. headings/subheadings/labels;
6. lifecycle structure;
7. governance and authority model;
8. evidence and provenance model;
9. identifier model;
10. assurance lifecycle;
11. prompts and prompt syntax;
12. resource markers and notes;
13. stop/reset/emergency behavior;
14. outputs and gates;
15. project-management assumptions;
16. engineering principles;
17. security/privacy assumptions;
18. AI-governance assumptions where applicable;
19. standards claims and currency;
20. implementation versus specification claims.

Explicitly identify and merge at least two repeated or materially similar concepts. Prefer a single stronger control over multiple duplicated controls.

Do not inherit project-specific names, technologies, vendors, repositories, branches, people, dates, jurisdictions or architecture as generic requirements.

Classify substantive claims as:

`VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RECOMMENDATION / RISK / UNKNOWN / DISPUTED`

Produce:

1. executive synthesis;
2. source/version comparison;
3. merged-concept register;
4. defect/inconsistency register;
5. identifier migration map;
6. prompt migration map;
7. standards modernization assessment;
8. complete generic PBIM candidate;
9. unresolved assumptions/unknowns;
10. verification gates;
11. authorization status.

Stop if an authoritative conflict cannot be safely dispositioned, required evidence is inaccessible, or a proposed merge would weaken a protected control.

<<START PBIM Historical Source Set>>
1. {{PBIM-V3.00.00-DURABLE-REFERENCE}}
2. {{PBIM-V3.00.01-DURABLE-REFERENCE}}
3. {{PBIM-V3.00.02-DURABLE-REFERENCE}}
4. {{PBIM-V3.00.03-DURABLE-REFERENCE}}
5. {{PBIM-V3.00.04-DURABLE-REFERENCE}}
6. {{ADDITIONAL-AUTHORITATIVE-PBIM-REFERENCES-IF-APPLICABLE}}
<<STOP PBIM Historical Source Set>>

Note the following:
1. Source documents are evidence for synthesis, not automatic authority.
2. Do not invent inaccessible content.
3. Preserve material dissent and contradictions.
4. The PBIM-to-Charter boundary must remain explicit.
5. `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE` and `EXPECTED-PROJECT-END-DATE` must remain expected/provisional.

Decision set:

`READY / READY-WITH-OBSERVATIONS / BLOCKED`

#**<<STOP Prompt 1. PBIM Generic Baseline Synthesis>>**#

### **<<START Prompt 2. PBIM Baseline Independent Review>>**#
[Designation: Collaborating Agents]

Independently review the PBIM candidate produced by Prompt 1.

Test, rather than merely edit:

- genericity;
- internal consistency;
- identifier integrity;
- section completeness;
- prompt executability;
- marker correctness;
- merged-concept correctness;
- authority separation;
- evidence controls;
- risk scaling;
- expected-date semantics;
- stop/reset/emergency controls;
- specification/implementation separation;
- standards applicability and currency;
- PBIM boundary.

For every material finding provide:

`Finding ID → Evidence → Impact → Severity/Materiality → Affected Control → Recommendation → Required Decision`

Do not approve merely because the candidate is comprehensive.

<<START PBIM Candidate>>
{{DURABLE-PBIM-CANDIDATE-REFERENCE}}
<<STOP PBIM Candidate>>

<<START PBIM Source Set>>
{{DURABLE-PBIM-SOURCE-SET}}
<<STOP PBIM Source Set>>

Note the following:
1. Preserve minority findings.
2. Identify inaccessible resources.
3. Disclose independence limitations.
4. Do not upgrade evidence labels without evidence.
5. Return `CHALLENGE-BLOCKED` if required independence cannot be established.

Decision set:

`APPROVE / APPROVE WITH CONDITIONS / RETURN / BLOCK / CHALLENGE-BLOCKED`

#**<<STOP Prompt 2. PBIM Baseline Independent Review>>**#

---

# 10. PBI-02 — PBIM ARCHITECTURAL ENGINEERING ASSURANCE

**Identifier:** `[PROJECT-ID][PBI-02-0004.00.02]`

## Purpose

Subject the PBIM itself to the reusable:

`AEA → AEV → AEC → AECC`

assurance cycle.

## Assurance outputs

- AEA Query;
- independent AEA Reports;
- AEV Statement;
- AEV Decisions;
- AEC Challenge;
- AEC Results;
- AECC Closure;
- controlled PBIM baseline candidate or Architectural Reset.

## Exit gate

No material unresolved blocker remains; required independence is established; residual risks are recorded; authorized human governance accepts the closure.

### **<<START Prompt 3. PBIM Architectural Engineering Analysis>>**#
[Designation: Lead Agent]

Develop a formal AEA Query for the PBIM candidate.

Analyze at minimum:

- governance and authority;
- identifier and registry integrity;
- evidence/provenance;
- prompt architecture;
- role separation;
- independence;
- assurance lifecycle;
- risk scaling;
- Task Packet boundary;
- stop/resume/reset behavior;
- emergency authority;
- security/privacy boundary;
- standards applicability;
- instruction precedence;
- baseline drift;
- cumulative materiality;
- operational readiness;
- recovery;
- human unavailability;
- automation compromise;
- expected-date semantics;
- genericity.

For each AEA question define:

`Question → Expected Evidence → Failure Consequence → Materiality`

Do not issue an approval in the AEA Query itself.

<<START PBIM Subject>>
{{DURABLE-PBIM-CANDIDATE-REFERENCE}}
<<STOP PBIM Subject>>

<<START PBIM Standards and Governance References>>
{{APPLICABLE-STANDARDS-AND-GOVERNANCE-REFERENCES}}
<<STOP PBIM Standards and Governance References>>

Note the following:
1. AEA is analysis, not approval.
2. Inaccessible evidence must be reported.
3. Historical wording is not automatically current practice.

#**<<STOP Prompt 3. PBIM Architectural Engineering Analysis>>**#

### **<<START Prompt 4. PBIM AEA Reports and AEV Statement>>**#
[Designation: Lead Agent]

Consolidate the independent AEA reports and prepare the AEV Statement.

For every finding:

1. preserve the original finding;
2. identify supporting evidence;
3. record severity and materiality;
4. identify affected control;
5. resolve the finding or explicitly disposition why it remains open;
6. preserve minority findings;
7. identify changes introduced by remediation;
8. assign the AEV revision.

The AEV Statement shall distinguish:

`VERIFIED FACT / ASSUMPTION / PROPOSAL / RISK / UNKNOWN / DISPUTED`

No condition may be treated as closed without evidence.

<<START PBIM AEA Reports>>
{{LINKS-TO-ALL-PBIM-AEA-REPORTS}}
<<STOP PBIM AEA Reports>>

<<START PBIM Candidate>>
{{DURABLE-PBIM-CANDIDATE-REFERENCE}}
<<STOP PBIM Candidate>>

Note the following:
1. AEV is verification of the proposed baseline, not evidence of operational enforcement.
2. Any material amendment restarts the applicable verification path.

Decision set:

`AEV-APPROVE / AEV-APPROVE-WITH-CONDITIONS / AEV-RETURN / AEV-BLOCK`

#**<<STOP Prompt 4. PBIM AEA Reports and AEV Statement>>**#

### **<<START Prompt 5. PBIM AEV Decision and AEC Preparation>>**#
[Designation: Collaborating Agents]

Review the current AEV Statement independently.

Verify:

- every Critical/High finding;
- resolution adequacy;
- evidence sufficiency;
- preservation of dissent;
- materiality classification;
- independence;
- identifier integrity;
- consistency with the candidate PBIM.

Return one decision and list every condition.

<<START PBIM AEV Statement>>
{{DURABLE-AEV-STATEMENT-REFERENCE}}
<<STOP PBIM AEV Statement>>

<<START PBIM AEA Evidence>>
{{DURABLE-AEA-REPORTS-REFERENCE}}
<<STOP PBIM AEA Evidence>>

Note the following:
1. A conditional approval is not unconditional closure.
2. A material unresolved blocker prevents AEC progression.

Decision set:

`AEV-APPROVE / AEV-RETURN / AEV-BLOCK / CHALLENGE-BLOCKED`

#**<<STOP Prompt 5. PBIM AEV Decision and AEC Preparation>>**#

### **<<START Prompt 6. PBIM AEC Adversarial Challenge>>**#
[Designation: Collaborating Agents]

Attempt to invalidate the PBIM AEV baseline rather than improve it.

Attack:

- hidden authority;
- privilege escalation;
- registry corruption/concurrency;
- evidence manipulation;
- false independence;
- Task Packet scope escape;
- conflicting instructions;
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
- excessive governance;
- insufficient governance;
- genericity failures;
- standards applicability failures.

For each material attack report:

`Attack → Expected Control → Failure Scenario → Evidence → Severity → Materiality → Disposition`

Do not modify the baseline during the challenge.

<<START PBIM AEV Baseline>>
{{DURABLE-AEV-APPROVED-BASELINE}}
<<STOP PBIM AEV Baseline>>

Note the following:
1. Preserve failed attacks and successful defenses as evidence.
2. If required independence is unavailable, return `CHALLENGE-BLOCKED`.
3. A minority material blocker remains open regardless of majority agreement.

Decision set:

`CHALLENGE-PASS / CHALLENGE-PASS-WITH-FINDINGS / CHALLENGE-BLOCKED / ARCHITECTURAL-RESET`

#**<<STOP Prompt 6. PBIM AEC Adversarial Challenge>>**#

### **<<START Prompt 7. PBIM AEC Results and AECC Closure>>**#
[Designation: Lead Agent / Authorized Human Authority]

Review all AEC results.

For every material finding:

1. preserve the original finding;
2. record severity/materiality;
3. identify corrective control;
4. identify evidence;
5. identify independent confirmation;
6. record residual risk;
7. identify authority;
8. state closure status.

If a material finding invalidates the baseline:

`ARCHITECTURAL RESET`

If remediation materially changes the baseline:

`AEV AMENDMENT → AEC RE-CHALLENGE → AECC`

Do not close findings by:

- renaming;
- splitting;
- downgrading;
- replacing the baseline without addressing the risk;
- relying on consensus.

Produce:

- AECC record;
- residual-risk register;
- final PBIM baseline revision;
- implementation obligations;
- authorization status.

AECC closure does not authorize production implementation.

<<START PBIM AEC Results>>
{{LINKS-TO-ALL-AEC-RESULTS}}
<<STOP PBIM AEC Results>>

<<START PBIM AEV Statement and Decisions>>
{{DURABLE-AEV-STATEMENT-AND-DECISIONS}}
<<STOP PBIM AEV Statement and Decisions>>

Note the following:
1. Every material closure requires evidence.
2. Adverse evidence is retained.
3. Human authority remains responsible for governance decisions.

Decision set:

`AECC-CLOSED / AECC-RETURN / ARCHITECTURAL-RESET / BLOCKED`

#**<<STOP Prompt 7. PBIM AEC Results and AECC Closure>>**#

---

# 11. PBI-03 — PROJECT CONTEXT AND PROPOSAL DEFINITION

**Identifier:** `[PROJECT-ID][PBI-03-0004.00.03]`

## Purpose

Translate the controlled PBIM baseline and project evidence into a project-specific proposal without inventing facts or prematurely designing detailed implementation.

## Required inputs

- project identity;
- problem/opportunity;
- intended value;
- objectives/outcomes;
- authority/sponsor;
- stakeholders;
- preliminary scope and exclusions;
- constraints;
- assumptions;
- dependencies;
- environment;
- security/privacy context;
- delivery approach hypothesis;
- expected duration/start/end;
- risk profile;
- preliminary success measures;
- legal/regulatory context;
- potential solution options.

## Exit gate

`PBI-03-GATE = CONTEXT-COMPLETE | RETURN | BLOCKED`

### **<<START Prompt 8. Project Context and Proposal Definition>>**#
[Designation: Lead Agent]

Using only the approved PBIM baseline and supplied project evidence, develop the Project Proposal candidate.

Define:

1. project identity;
2. problem/opportunity;
3. strategic alignment;
4. intended value/outcomes;
5. measurable objectives;
6. stakeholders;
7. preliminary scope and exclusions;
8. requirements at the level justified by evidence;
9. constraints;
10. assumptions;
11. dependencies;
12. security/privacy/data considerations;
13. expected project duration;
14. expected project start date;
15. expected project end date;
16. risk/materiality profile;
17. delivery approach hypothesis;
18. solution options;
19. recommended direction, if evidence supports one;
20. success measures;
21. legal/regulatory applicability;
22. project-template inputs;
23. unresolved decisions.

For missing information:

`UNKNOWN` is preferred to invention.

Do not create detailed implementation architecture unless explicitly required for feasibility and supported by evidence.

<<START Approved PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP Approved PBIM Baseline>>

<<START Project Context and Source Evidence>>
{{PROJECT-REQUEST / BUSINESS-CASE / OPERATIONAL-CONTEXT / KNOWN-REQUIREMENTS / CONSTRAINTS}}
<<STOP Project Context and Source Evidence>>

Note the following:
1. Expected dates are provisional.
2. Do not convert assumptions into approved requirements.
3. Do not introduce project-specific facts into PBIM itself.

Decision set:

`PROPOSAL-CANDIDATE-READY / RETURN / BLOCKED`

#**<<STOP Prompt 8. Project Context and Proposal Definition>>**#

### **<<START Prompt 9. Project Proposal Independent Review>>**#
[Designation: Collaborating Agents]

Independently review the Project Proposal candidate.

Evaluate:

- value and objective coherence;
- scope integrity;
- stakeholder completeness;
- requirement quality;
- dependency realism;
- assumption quality;
- expected schedule plausibility;
- resource realism;
- risk/materiality;
- security/privacy/regulatory triggers;
- operational sustainability;
- delivery-model suitability;
- feasibility;
- success-measure quality.

Identify conditions that could invalidate the proposal.

For each material finding use:

`Finding → Evidence → Impact → Severity/Materiality → Affected Proposal Element → Recommendation`

<<START Project Proposal Candidate>>
{{DURABLE-PROPOSAL-CANDIDATE}}
<<STOP Project Proposal Candidate>>

<<START PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP PBIM Baseline>>

Note the following:
1. A well-written proposal is not automatically a feasible proposal.
2. Preserve dissent.
3. State independence limitations.

Decision set:

`APPROVE / APPROVE-WITH-CONDITIONS / RETURN / BLOCKED`

#**<<STOP Prompt 9. Project Proposal Independent Review>>**#

---

# 12. PBI-04 — PROJECT PROPOSAL ENGINEERING AND VERIFICATION

**Identifier:** `[PROJECT-ID][PBI-04-0004.00.04]`

## Purpose

Subject the proposal to the reusable assurance protocol and establish a verified proposal baseline.

## Exit gate

Proposal AECC closed; material blockers resolved or formally dispositioned; residual risk visible.

### **<<START Prompt 10. Project Proposal AEA Query>>**#
[Designation: Lead Agent]

Analyze the Project Proposal and produce a formal AEA Query.

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
- expected schedule realism;
- resource feasibility;
- dependencies;
- operational sustainability;
- success measures;
- assumptions and unknowns;
- delivery approach;
- procurement implications;
- sustainability where material;
- failure modes.

For every AEA question define:

`Question → Expected Evidence → Failure Consequence`

Do not design implementation merely to eliminate an analytical uncertainty.

<<START Project Proposal>>
{{DURABLE-PROJECT-PROPOSAL}}
<<STOP Project Proposal>>

<<START Applicable PBIM Controls>>
{{PBIM-CONTROLS-AND-GATES}}
<<STOP Applicable PBIM Controls>>

Note the following:
1. Distinguish evidence from assumption.
2. AEA does not approve the proposal.

#**<<STOP Prompt 10. Project Proposal AEA Query>>**#

### **<<START Prompt 11. Project Proposal AEA Review and AEV Preparation>>**#
[Designation: Collaborating Agents]

Independently execute the Project Proposal AEA Query.

For each question:

1. answer directly;
2. cite evidence;
3. classify the claim;
4. identify severity/materiality;
5. identify contradictions;
6. identify missing evidence;
7. identify recommendation;
8. state advancement consequence.

Challenge rather than polish.

<<START Project Proposal AEA Query>>
{{DURABLE-PROPOSAL-AEA-QUERY}}
<<STOP Project Proposal AEA Query>>

<<START Project Proposal>>
{{DURABLE-PROPOSAL}}
<<STOP Project Proposal>>

Note the following:
1. Do not infer missing evidence.
2. Preserve minority findings.

Decision set:

`AEA-CONCUR / AEA-CONCUR-WITH-CONDITIONS / AEA-DISAGREE / AEA-BLOCKED`

#**<<STOP Prompt 11. Project Proposal AEA Review and AEV Preparation>>**#

### **<<START Prompt 12. Project Proposal AEV, AEC and AECC Closure>>**#
[Designation: Lead Agent / Authorized Assurance Authority]

Synthesize the proposal AEA results into an AEV baseline.

Preserve:

- verified facts;
- assumptions;
- proposed decisions;
- material risks;
- unresolved unknowns;
- dissent;
- conditions of advancement.

Every condition must identify:

`Owner → Required Evidence → Advancement Consequence → Escalation Authority`

After AEV closure, perform the authorized AEC challenge.

The AEC must attempt to demonstrate:

- false/weak value;
- hidden scope;
- unowned authority;
- impossible constraints;
- unrealistic expected schedule;
- unrealistic financial assumptions;
- underestimated risk;
- stakeholder conflict;
- legal/procurement contradiction;
- security/privacy weakness;
- dependency failure;
- operational impossibility;
- unmeasurable success criteria.

Close only after material findings are resolved or formally dispositioned.

If remediation materially changes the proposal:

`AEV AMENDMENT → AEC RE-CHALLENGE → AECC`

<<START Proposal AEA Reports>>
{{ALL-PROPOSAL-AEA-REPORTS}}
<<STOP Proposal AEA Reports>>

<<START Proposal AEV>>
{{LATEST-PROPOSAL-AEV}}
<<STOP Proposal AEV>>

<<START Proposal AEC Results>>
{{PROPOSAL-AEC-RESULTS}}
<<STOP Proposal AEC Results>>

Note the following:
1. No material blocker may be closed without evidence.
2. Do not silently change proposal scope during closure.
3. The verified proposal becomes the controlled input for template assembly.

Decision set:

`PROPOSAL-AECC-CLOSED / RETURN / BLOCK / ARCHITECTURAL-RESET`

#**<<STOP Prompt 12. Project Proposal AEV, AEC and AECC Closure>>**#

---

# 13. PBI-05 — PROJECT TEMPLATE ASSEMBLY

**Identifier:** `[PROJECT-ID][PBI-05-0004.00.05]`

## Purpose

Generate a project-specific operating-template skeleton from the verified proposal and approved PBIM controls.

## Minimum applicable template domains

- governance;
- lifecycle;
- scope;
- requirements;
- schedule;
- finance;
- resources;
- stakeholders;
- communications;
- quality;
- risk;
- procurement;
- architecture/engineering;
- security;
- privacy/data;
- AI governance where applicable;
- repository/documentation;
- ADRs and decisions;
- Task Packets;
- evidence;
- testing;
- change control;
- release;
- operations/readiness;
- incident response;
- transition;
- closure/retention.

### **<<START Prompt 13. Project Template Assembly>>**#
[Designation: Lead Agent]

Generate the project-specific operating-template skeleton from the verified Project Proposal and approved PBIM.

Select and justify the delivery approach:

`PREDICTIVE / ITERATIVE / INCREMENTAL / ADAPTIVE / HYBRID / OTHER`

For every mandatory control/artifact identify:

1. purpose;
2. requirement;
3. owner;
4. authority;
5. artifact ID;
6. canonical location;
7. dependencies;
8. entry criteria;
9. exit criteria;
10. evidence requirement;
11. intended maturity;
12. failure state;
13. recovery path.

Every mandatory item must be classified:

`POPULATED / NOT APPLICABLE WITH RATIONALE / DEFERRED WITH AUTHORITY / BLOCKED WITH REASON`

Do not populate unknown facts by invention.

<<START Verified Project Proposal>>
{{DURABLE-VERIFIED-PROPOSAL}}
<<STOP Verified Project Proposal>>

<<START Approved PBIM Baseline>>
{{DURABLE-PBIM-BASELINE}}
<<STOP Approved PBIM Baseline>>

<<START Identifier Registry>>
{{DURABLE-IDENTIFIER-REGISTRY}}
<<STOP Identifier Registry>>

<<START Authority Register>>
{{DURABLE-AUTHORITY-REGISTER}}
<<STOP Authority Register>>

Note the following:
1. Distinguish `SKELETON` from `OPERATING TEMPLATE`.
2. Do not force methodology ceremony that is not justified by materiality or lifecycle.
3. Preserve expected dates as expected values.

Decision set:

`ASSEMBLY-COMPLETE / RETURN / BLOCKED`

#**<<STOP Prompt 13. Project Template Assembly>>**#

---

# 14. PBI-06 — PROJECT TEMPLATE ENGINEERING AND VERIFICATION

**Identifier:** `[PROJECT-ID][PBI-06-0004.00.06]`

## Purpose

Verify that the project template can safely govern the selected delivery model.

### **<<START Prompt 14. Project Template Independent Verification>>**#
[Designation: Collaborating Agents]

Verify the complete project template.

For every mandatory control identify:

`Requirement → Mechanism → Authority → Owner → Evidence → Failure State → Recovery → Maturity`

Check:

- identifiers;
- authority/permission alignment;
- protected-control precedence;
- evidence;
- Task Packet scope;
- stop/reset/emergency controls;
- security boundary;
- dependency declarations;
- change/materiality routing;
- role separation;
- human escalation;
- operational readiness;
- drift controls;
- delivery-model fit;
- expected schedule assumptions.

Do not upgrade maturity without evidence.

<<START Project Template>>
{{DURABLE-PROJECT-TEMPLATE}}
<<STOP Project Template>>

<<START Verified Project Proposal>>
{{DURABLE-VERIFIED-PROPOSAL}}
<<STOP Verified Project Proposal>>

<<START PBIM Controls>>
{{DURABLE-PBIM-CONTROLS}}
<<STOP PBIM Controls>>

Note the following:
1. Distinguish template completeness from implementation.
2. Identify controls that remain only designed.

Decision set:

`READY / READY-WITH-CONDITIONS / RETURN / BLOCK / RESET`

#**<<STOP Prompt 14. Project Template Independent Verification>>**#

### **<<START Prompt 15. Project Template Adversarial Challenge and Closure>>**#
[Designation: Collaborating Agents / Authorized Challenge Authority]

Attempt to invalidate the project template without modifying it.

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
- cumulative low-risk changes;
- operational-readiness gaps;
- human/agent collision;
- compromised automation;
- repository-admin override;
- privacy/security gaps;
- legal/regulatory gaps;
- impossible recovery;
- excessive governance;
- insufficient governance.

For each material issue construct the smallest reproducible failure scenario.

After challenge, closure authority shall reconcile findings and either:

- remediate and re-verify;
- formally disposition residual risk;
- initiate Architectural Reset.

<<START Project Template>>
{{DURABLE-PROJECT-TEMPLATE}}
<<STOP Project Template>>

<<START Project Template Verification>>
{{DURABLE-VERIFICATION-RESULTS}}
<<STOP Project Template Verification>>

Note the following:
1. Attack the template; do not optimize it during the challenge.
2. Preserve all material dissent.
3. AEC closure does not authorize implementation.

Decision set:

`TEMPLATE-AECC-CLOSED / RETURN / BLOCK / ARCHITECTURAL-RESET`

#**<<STOP Prompt 15. Project Template Adversarial Challenge and Closure>>**#

---

# 15. PBI-07 — PROJECT FRAMEWORK INITIALIZATION

**Identifier:** `[PROJECT-ID][PBI-07-0004.00.07]`

## Purpose

Instantiate the verified project framework in the intended governance/development environment without silently substituting missing controls.

## Applicable initialization areas

- governance repository;
- project repository;
- documentation locations;
- ADR location;
- Decision Ledger;
- Task Packet storage;
- identifier registry;
- Authority Register;
- authority-permission matrix;
- branch protections;
- instruction hierarchy;
- security/secret boundary;
- CI/CD controls where applicable;
- evidence storage;
- audit logging;
- backup/recovery;
- records/retention.

### **<<START Prompt 16. Project Framework Initialization>>**#
[Designation: Lead Agent / Authorized Implementation Role]

Using only the approved PBIM, verified Project Proposal and verified Project Template, prepare and execute the authorized initialization plan.

For every initialization action record:

1. action ID;
2. purpose;
3. authority;
4. owner;
5. target;
6. expected state;
7. actual state;
8. evidence;
9. verification method;
10. rollback/recovery;
11. stop condition.

Verify, where applicable:

- project identity;
- human authority;
- repository ownership;
- branch protection;
- canonical governance locations;
- identifier registry;
- Task Packet mechanism;
- security/secret boundary;
- instruction precedence;
- audit/evidence paths;
- backup/recovery.

Do not create an informal substitute for an absent mandatory control.

If the environment cannot safely support a required control, stop and escalate.

<<START Verified Project Template>>
{{DURABLE-VERIFIED-PROJECT-TEMPLATE}}
<<STOP Verified Project Template>>

<<START Authority Register>>
{{DURABLE-AUTHORITY-REGISTER}}
<<STOP Authority Register>>

<<START Identifier Registry>>
{{DURABLE-IDENTIFIER-REGISTRY}}
<<STOP Identifier Registry>>

<<START Environment References>>
{{REPOSITORY / ENVIRONMENT / GOVERNANCE REFERENCES}}
<<STOP Environment References>>

Note the following:
1. This prompt authorizes only actions already authorized by the applicable governance mechanism.
2. Planning language alone does not authorize implementation.
3. Actual state must be evidenced.

Decision set:

`INITIALIZED / INITIALIZED-WITH-CONDITIONS / BLOCKED / RESET`

#**<<STOP Prompt 16. Project Framework Initialization>>**#

---

# 16. PBI-08 — PROJECT SIMULATION AND READINESS

**Identifier:** `[PROJECT-ID][PBI-08-0004.00.08]`

## Purpose

Dry-run the initialized project framework to determine whether intended governance outcomes can actually be reproduced.

## Minimum applicable simulation scenarios

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
11. security/privacy incident;
12. emergency change;
13. rollback/recovery;
14. operational incident;
15. sponsor/authority withdrawal;
16. material scope change;
17. evidence loss/corruption;
18. compromised automation.

### **<<START Prompt 17. Project Simulation and Readiness Exercise>>**#
[Designation: Lead Agent / Collaborating Assurance Agents]

Design and conduct a controlled dry-run of the initialized project framework without performing prohibited production implementation.

For every scenario record:

`Trigger → Preconditions → Action → Expected Control → Observed Result → Evidence → Pass/Fail → Residual Risk → Recovery`

Test at minimum the scenarios listed in this section.

Include negative-path testing.

Where a scenario cannot be safely executed, use a controlled paper/sandbox exercise and state the limitation.

<<START Initialized Project Framework>>
{{DURABLE-INITIALIZATION-REFERENCE}}
<<STOP Initialized Project Framework>>

<<START Task Packet Model>>
{{DURABLE-TASK-PACKET-SCHEMA}}
<<STOP Task Packet Model>>

<<START Stop/Recovery Model>>
{{DURABLE-STOP-RECOVERY-CONTROL}}
<<STOP Stop/Recovery Model>>

<<START Authority/Permission Matrix>>
{{DURABLE-AUTHORITY-PERMISSION-MATRIX}}
<<STOP Authority/Permission Matrix>>

Note the following:
1. Do not create production effects.
2. A documented control is not a successful drill.
3. Unexpected production effect is an immediate stop.

Decision set:

`READINESS-PASS / READINESS-PASS-WITH-FINDINGS / READINESS-RETURN / READINESS-BLOCKED`

#**<<STOP Prompt 17. Project Simulation and Readiness Exercise>>**#

### **<<START Prompt 18. Operational Readiness Review>>**#
[Designation: Collaborating Agents / Operations Authority]

Review the simulation and initialization evidence for operational readiness.

Assess, where applicable:

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
- user/stakeholder readiness;
- handoff;
- continuity/recovery.

For each item classify:

`APPLICABLE / NOT APPLICABLE WITH RATIONALE / DEFERRED / BLOCKED`

Do not treat implementation verification as operational readiness.

<<START Simulation Evidence>>
{{DURABLE-SIMULATION-RESULTS}}
<<STOP Simulation Evidence>>

<<START Operational Readiness Criteria>>
{{DURABLE-OPERATIONAL-READINESS-CRITERIA}}
<<STOP Operational Readiness Criteria>>

Note the following:
1. Operational readiness may require evidence not available during a dry-run.
2. A deferred item must have an owner and authority.

Decision set:

`READY / READY-WITH-CONDITIONS / NOT-READY / BLOCKED`

#**<<STOP Prompt 18. Operational Readiness Review>>**#

---

# 17. PBI-09 — PBIM ACTIVATION AND CHARTER READINESS

**Identifier:** `[PROJECT-ID][PBI-09-0004.00.09]`

## Purpose

Determine whether the complete pre-charter package is sufficiently coherent, evidenced, risk-dispositioned and governed to enter formal Project Charter development.

## Final pre-charter gate

All applicable items must be:

- passed;
- explicitly not applicable with rationale;
- deferred under authorized control;
- or formally escalated/accepted by the competent human authority.

### Pre-Charter Gate Register

| Gate | Requirement | Evidence |
|---|---|---|
| G0 | PBIM identity and version controlled | PBIM baseline |
| G1 | Project identity established | Identity Block |
| G2 | Expected duration/start/end defined as expected values | Schedule evidence |
| G3 | Authority established | Authority Register |
| G4 | Applicable jurisdiction/regulatory overlay assessed | Regulatory record |
| G5 | PBIM assurance closed | AECC |
| G6 | Project proposal verified | Proposal AECC |
| G7 | Project template verified | Template AECC |
| G8 | Identifier Registry established | Registry evidence |
| G9 | Authority-Permission Matrix established where required | Matrix evidence |
| G10 | Evidence/traceability model established | Evidence register |
| G11 | Security/privacy boundary assessed | Security/privacy record |
| G12 | Task Packet mechanism defined where applicable | Task Packet schema |
| G13 | Decision Ledger established | Ledger |
| G14 | Stop/reset/emergency controls defined | State model |
| G15 | Framework initialization verified where applicable | Initialization evidence |
| G16 | Simulation completed | Readiness evidence |
| G17 | Operational readiness reviewed | Operations review |
| G18 | Material blockers resolved or formally escalated | Finding register |
| G19 | Residual risks owned and visible | Risk register |
| G20 | Material dissent preserved | Dissent record |
| G21 | Charter inputs traceable | Traceability matrix |
| G22 | Authorization requested is limited to Charter progression | Activation record |

### **<<START Prompt 19. PBIM Activation and Charter Readiness>>**#
[Designation: Lead Agent / Human Project Authority]

Assemble and evaluate the complete pre-charter package.

Review:

1. PBIM identity and baseline;
2. project identity;
3. authority;
4. expected duration/start/end;
5. regulatory applicability;
6. PBIM assurance;
7. verified proposal;
8. verified template;
9. initialized controls;
10. identifier registry;
11. authority-permission controls;
12. security/privacy boundary;
13. evidence model;
14. Task Packet controls;
15. simulation evidence;
16. operational readiness;
17. findings;
18. residual risks;
19. dissent;
20. Charter inputs;
21. authorization boundary.

For every condition/blocker state:

`Owner → Required Evidence → Advancement Impact → Escalation Authority`

Do not convert assumptions into approvals.

The authorization requested, if any, must be limited to progression into Project Charter development. It must not imply implementation, release or production authorization.

<<START PBIM Activation Package>>
1. {{PBIM-BASELINE}}
2. {{PBIM-AECC}}
3. {{VERIFIED-PROJECT-PROPOSAL}}
4. {{PROPOSAL-AECC}}
5. {{VERIFIED-PROJECT-TEMPLATE}}
6. {{TEMPLATE-AECC}}
7. {{INITIALIZATION-EVIDENCE}}
8. {{SIMULATION-RESULTS}}
9. {{OPERATIONAL-READINESS-REVIEW}}
10. {{RISK-FINDING-REGISTERS}}
11. {{DECISION-LEDGER}}
12. {{EXPECTED-PROJECT-SCHEDULE-HISTORY}}
<<STOP PBIM Activation Package>>

Note the following:
1. A well-documented package is not automatically an approved package.
2. Material blockers cannot be hidden by aggregate scoring.
3. Any failed protected control must remain visible.
4. PBIM does not authorize production.

Decision set:

`READY FOR CHARTER / READY WITH FORMAL CONDITIONS / BLOCKED / ARCHITECTURAL RESET`

#**<<STOP Prompt 19. PBIM Activation and Charter Readiness>>**#

### **<<START Prompt 20. PBIM Activation Independent Review>>**#
[Designation: Collaborating Agents]

Independently review the complete PBIM activation package.

Re-check the evidence behind the material gate items rather than relying on the Lead Agent's summary.

Verify:

- identity;
- authority;
- expected schedule semantics;
- proposal baseline;
- template baseline;
- initialization evidence;
- simulation evidence;
- operational readiness;
- material findings;
- residual risk;
- dissent;
- non-authorizations.

For each material finding provide:

`Finding → Evidence → Impact → Severity/Materiality → Gate → Required Action`

Do not approve production implementation.

<<START PBIM Activation Package>>
{{DURABLE-ACTIVATION-PACKAGE}}
<<STOP PBIM Activation Package>>

<<START Pre-Charter Gate Register>>
{{DURABLE-GATE-REGISTER}}
<<STOP Pre-Charter Gate Register>>

Note the following:
1. If required evidence cannot be independently confirmed, identify the limitation.
2. Independence must be disclosed.
3. No majority vote overrides a material blocker.

Decision set:

`READY / READY-WITH-CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED`

#**<<STOP Prompt 20. PBIM Activation Independent Review>>**#

### **<<START Prompt 21. PBIM Final Decision and Charter Handover>>**#
[Designation: Human Project Authority]

Review the Lead Agent activation recommendation and independent review.

Record:

1. final gate status;
2. authorization scope;
3. conditions;
4. owners;
5. due dates;
6. residual risks;
7. unresolved dissent;
8. required Charter inputs;
9. PBIM baseline reference;
10. effective date;
11. expiry/review condition where applicable.

If approved for Charter progression, create the controlled handover package.

Do not convert expected dates into committed dates unless the Charter-stage authority explicitly establishes them as baselines under the applicable governance process.

<<START Activation Decision>>
{{LEAD-ACTIVATION-RECOMMENDATION}}
<<STOP Activation Decision>>

<<START Independent Review>>
{{INDEPENDENT-ACTIVATION-REVIEW}}
<<STOP Independent Review>>

<<START Final Pre-Charter Gate>>
{{COMPLETED-GATE-REGISTER}}
<<STOP Final Pre-Charter Gate>>

Note the following:
1. This decision does not authorize production.
2. Charter development remains subject to the organization's governance authority.
3. All adverse evidence and residual risks remain part of the record.

Decision set:

`CHARTER-PROGRESSION-AUTHORIZED / CHARTER-PROGRESSION-AUTHORIZED-WITH-CONDITIONS / DO-NOT-ADVANCE / ARCHITECTURAL-RESET`

#**<<STOP Prompt 21. PBIM Final Decision and Charter Handover>>**#

---

# 18. GOV-01-0004.01 — DEVELOP PROJECT CHARTER

**Identifier:** `[PROJECT-ID][GOV-01-0004.01]`

This is the **final PBIM identifier**.

PBIM hands the controlled pre-charter package into formal Project Charter development.

The Charter must trace material content to:

- PBIM evidence;
- proposal evidence;
- decisions;
- approved assumptions;
- explicitly recorded new Charter-stage inputs.

The Charter is not authorized merely because an agent generated a draft.

The organization's competent human authority must approve the Charter under the applicable governance model.

PBIM must not create additional identifiers under the PBIM namespace after this boundary.

---

# 19. STATE AND AUTHORIZATION VOCABULARY

## 19.1 Control maturity

```text
DESIGNED
ENFORCEABLE
ENFORCED
INDEPENDENTLY VERIFIED
```

## 19.2 Artifact state

```text
DRAFT
ANALYSIS
CONTROLLED CANDIDATE
VERIFICATION
APPROVED
SUPERSEDED
ARCHIVED
```

## 19.3 Assurance state

```text
UNDER-AEA
AEA-COMPLETE
AEV-CANDIDATE
AEV-APPROVED
AEC-IN-PROGRESS
AEC-BLOCKED
AEC-CLOSED
AECC-CANDIDATE
AECC-CLOSED
```

## 19.4 Authorization state

```text
IMPLEMENTATION-NOT-AUTHORIZED
IMPLEMENTATION-AUTHORIZED
IMPLEMENTATION-VERIFIED
OPERATIONALLY-READY
RELEASE-AUTHORIZED
PRODUCTION
STOPPED
RESET-REQUIRED
```

Not every project requires every state. An omitted state must be explicitly declared `NOT APPLICABLE` with rationale.

---

# 20. DRIFT CONTROL

The instantiated project periodically compares:

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
- dependency drift;
- environment drift;
- authority drift;
- evidence drift.

Material undocumented divergence triggers review.

Approval may require revalidation after:

- material requirement change;
- architecture change;
- dependency change;
- security/privacy incident;
- environment change;
- authority change;
- governance-defined expiry.

---

# 21. FAILURE AND RECOVERY MODEL

## 21.1 Agent unavailable

Do not silently combine independent roles.

Determine:

- authorized substitute;
- human review requirement;
- whether work must stop.

## 21.2 Conflicting analysis

Preserve both positions.

Reconcile using evidence.

Escalate unresolved material disagreement.

## 21.3 Failed verification

Return to the appropriate prior stage.

Do not mark the artifact approved.

## 21.4 Failed challenge

Remediate and re-verify, or initiate Architectural Reset.

## 21.5 Registry failure

Block governance progression when authoritative identifier state cannot be trusted.

## 21.6 Evidence failure

Downgrade the decision state and require re-verification.

## 21.7 Automation compromise

Automation is not inherently trusted.

A compromised validator, CI system or automation mechanism must be capable of causing a governance stop and independent review where the project's controls require it.

## 21.8 Human authority unavailable

Do not allow an agent to self-assume authority.

Use only a recorded delegation or approved substitute authority.

Otherwise enter the applicable blocked state.

---

# 22. GENERIC QUALITY AND ENGINEERING CONTROL SET

Where applicable, instantiate:

- requirements traceability;
- acceptance criteria;
- quality criteria;
- test strategy;
- negative-path testing;
- security controls;
- privacy controls;
- accessibility;
- resilience;
- observability;
- rollback;
- recovery;
- backup/restore;
- dependency management;
- supply-chain controls;
- configuration management;
- change control;
- incident management;
- records retention;
- operational support;
- transition/handover.

The selected controls must be proportionate to materiality and risk without weakening protected controls.

---

# 23. EXPECTED PROJECT SCHEDULE REGISTER

Use this table throughout PBIM.

| Revision | Date Recorded | Expected Duration | Expected Start | Expected End | Basis | Confidence/Range | Evidence | Owner | Decision |
|---|---|---|---|---|---|---|---|---|---|
| `R0` | `{{UTC}}` | `{{EXPECTED}}` | `{{EXPECTED}}` | `{{EXPECTED}}` | `{{BASIS}}` | `{{RANGE}}` | `{{REF}}` | `{{OWNER}}` | `{{DECISION}}` |
| `R1` | `{{UTC}}` | `{{EXPECTED}}` | `{{EXPECTED}}` | `{{EXPECTED}}` | `{{BASIS}}` | `{{RANGE}}` | `{{REF}}` | `{{OWNER}}` | `{{DECISION}}` |

No row in this table becomes a committed project baseline solely because it appears here.

---

# 24. MERGED CONTROL-FAMILY REFERENCE

## 24.1 Evidence + provenance + traceability + durable reference

**Single control:** Evidence & Traceability Control.

This replaces separate repeated descriptions of:

- evidence labels;
- source links;
- hashes;
- commit references;
- canonical documents;
- provenance;
- traceability matrices;
- supersession.

## 24.2 Stop + reset + emergency + failure + recovery

**Single control:** Controlled State & Recovery Model.

This replaces separate repeated descriptions of:

- stop classes;
- emergency delegation;
- reset;
- failure handling;
- recovery;
- blocked states;
- resume rules.

## 24.3 Repeated assurance cycles

**Single control:** Reusable Assurance Cycle.

The same AEA → AEV → AEC → AECC logic is parameterized by subject:

```text
SUBJECT = PBIM
SUBJECT = PROJECT PROPOSAL
SUBJECT = PROJECT TEMPLATE
```

This prevents copy/paste drift while preserving subject-specific questions.

## 24.4 Repeated readiness lists

**Single control:** Pre-Charter Gate Register.

Activation, simulation, template-readiness and Charter-readiness requirements are traced into one final gate rather than competing checklists.

## 24.5 Prompt boilerplate

**Single control:** Controlled Prompt Contract.

Every operational prompt uses the same exact marker and response architecture.

---

# 25. LEGACY-TO-GENERIC CROSSWALK

| Historical concept | Consolidated/current treatment |
|---|---|
| PBIM creation | `PBI-01-0004.00.01` |
| PBIM AEA/AEV/AEC/AECC | `PBI-02-0004.00.02` |
| Project proposal definition | `PBI-03-0004.00.03` |
| Project proposal assurance | `PBI-04-0004.00.04` |
| Project template assembly | `PBI-05-0004.00.05` |
| Project template assurance | `PBI-06-0004.00.06` |
| Project framework initialization | `PBI-07-0004.00.07` |
| Project simulation/readiness | `PBI-08-0004.00.08` |
| PBIM implementation/activation | `PBI-09-0004.00.09` — reframed as pre-charter readiness |
| Develop Project Charter | `GOV-01-0004.01` — final PBIM boundary |
| Repeated evidence/provenance/durable-reference controls | Evidence & Traceability Control |
| Repeated stop/reset/emergency/recovery controls | Controlled State & Recovery Model |
| Repeated assurance prompts | Reusable Assurance Cycle |
| Multiple readiness lists | Pre-Charter Gate Register |
| Agent as authority | Removed; agents are capabilities |
| Documentation as implementation proof | Removed |
| Percentage score as sole challenge gate | Removed |
| Unbounded emergency authority | Removed |
| Project-specific examples in generic rules | Removed |
| Fixed historical PM process count as PBIM architecture | Removed; local classification only |

---

# 26. IMPLEMENTATION-READINESS OBLIGATIONS

Before an instantiated PBIM is treated as a controlled operational governance artifact, the implementing organization must:

1. identify competent human authority;
2. establish the Authority Register;
3. establish the Identifier Registry;
4. establish evidence storage and provenance rules;
5. establish the Decision Ledger;
6. establish the risk/materiality profile;
7. establish the applicable regulatory overlay;
8. establish the prompt routing and response-storage mechanism;
9. establish required independence;
10. establish protected-resource permissions;
11. establish stop/reset/emergency controls;
12. establish Task Packet controls where applicable;
13. establish security/privacy controls where applicable;
14. establish applicable AI governance controls where relevant;
15. verify repository/environment controls where relevant;
16. conduct assurance;
17. conduct readiness exercises where applicable;
18. preserve residual risk and dissent;
19. obtain required human authorization;
20. retain all evidence needed to support the claimed maturity state.

---

# 27. CONTROLLED PROMPT QUALITY CHECKLIST

Before any PBIM prompt is issued, confirm:

- [ ] correct prompt number;
- [ ] correct prompt label;
- [ ] exact `<<START Prompt N. Label>>`;
- [ ] exact `<<STOP Prompt N. Label>>`;
- [ ] explicit designation;
- [ ] objective is clear;
- [ ] task is executable;
- [ ] resources are durable;
- [ ] resource start/stop labels match;
- [ ] notes are below resources;
- [ ] evidence classes are recognized;
- [ ] unknowns cannot be silently invented;
- [ ] stop conditions are defined;
- [ ] decision set is bounded;
- [ ] authority is not granted by wording;
- [ ] independence limitations are disclosed;
- [ ] expected dates remain expected;
- [ ] implementation is not implied by specification;
- [ ] material dissent can be preserved.

---

# 28. FINAL PBIM STATUS MODEL

A generic PBIM candidate shall display one of:

```text
DRAFT
UNDER-AEA
AEV-CANDIDATE
AEV-APPROVED
AEC-IN-PROGRESS
AEC-BLOCKED
AECC-CANDIDATE
AECC-CLOSED
SUPERSEDED
ARCHIVED
```

Implementation status is separate:

```text
IMPLEMENTATION-NOT-AUTHORIZED
IMPLEMENTATION-AUTHORIZED
IMPLEMENTATION-VERIFIED
OPERATIONALLY-READY
RELEASE-AUTHORIZED
PRODUCTION
```

The generic PBIM document itself defaults to:

```text
PBIM-STATE: DRAFT
CONTROL-MATURITY: DESIGNED
IMPLEMENTATION-AUTHORIZATION: NOT-GRANTED
PRODUCTION-AUTHORIZATION: NOT-GRANTED
```

---

# 29. OPEN ITEMS BEFORE THIS CANDIDATE CAN BECOME A CONTROLLED BASELINE

1. Complete independent AEA.
2. Complete AEV and preserve dissent.
3. Complete AEC adversarial challenge.
4. Complete AECC closure.
5. Re-verify current external standards at the time of project instantiation.
6. Confirm organization-specific legal and regulatory applicability.
7. Confirm authority mappings.
8. Confirm identifier registry implementation.
9. Confirm evidence-integrity implementation.
10. Confirm whether Task Packets are required for the instantiated project.
11. Confirm whether operational simulation is proportionate and feasible.
12. Confirm all expected schedule estimates from project evidence.
13. Confirm that no project-specific implementation assumption has leaked into the generic baseline.

---

# 30. AUTHORITATIVE DESIGN RULES — CONDENSED

The following rules are the minimum constitutional behavior of PBIM:

1. **Authority is human/organizational; agent capability is not authority.**
2. **Specification is not implementation.**
3. **Documentation is not proof of enforcement.**
4. **Evidence must be classified and traceable.**
5. **Unknown information must remain unknown until evidenced.**
6. **Expected dates are estimates, not commitments.**
7. **Material dissent is preserved.**
8. **A material blocker is not defeated by majority vote or aggregate scoring.**
9. **Independence must be evidenced where required.**
10. **A stop cannot be self-cleared by the executor.**
11. **Emergency authority must be bounded and reconciled.**
12. **A load-bearing failure can trigger Architectural Reset.**
13. **Material changes require proportionate assurance.**
14. **Cumulative materiality must be considered.**
15. **Identifiers are registered and never silently reused.**
16. **Durable references identify authoritative state.**
17. **Prompts use the exact controlled marker structure.**
18. **Prompts cannot manufacture authority.**
19. **Standards are applicability references, not implementation proof.**
20. **PBIM ends at Project Charter development.**
21. **PBIM does not grant production authorization.**
22. **The final decision to advance remains with competent human authority.**

---

# APPENDIX A — CURRENT LOCAL PROCESS-CLASSIFICATION PRINCIPLE

PBIM may maintain a local process/classification catalogue for traceability to a project-management framework.

That catalogue:

- must be versioned;
- must identify its source;
- must not be represented as the canonical PMBOK process list;
- must distinguish PM classification from PBIM lifecycle;
- must not force a project into processes that are not applicable;
- must preserve legacy aliases for traceability where useful.

The PBIM pre-charter sections remain under the `PBI` domain.

---

# APPENDIX B — MINIMUM REGISTER SCHEMAS

## B.1 Finding Record

```yaml
id:
source_agent:
resource:
section:
statement:
evidence_label:
severity:
materiality_level:
recommendation:
owner:
status:
closure_evidence:
dissent_reference:
```

## B.2 Decision Ledger

```yaml
decision_id:
date_utc:
authority:
subject:
options:
decision:
rationale:
conditions:
supersedes:
evidence_refs:
effective_revision:
```

## B.3 Authority Register

```yaml
role_code:
person_or_body:
scope:
delegation_from:
start:
expiry:
emergency_allowed:
revoked_at:
```

## B.4 Evidence Record

```yaml
evidence_id:
source:
canonical_location:
commit_or_object:
content_hash:
timestamp:
author:
verifier:
evidence_class:
claim_or_control:
result:
supersedes:
```

## B.5 Risk Record

```yaml
risk_id:
statement:
cause:
event:
effect:
likelihood:
impact:
materiality:
owner:
treatment:
evidence:
residual_risk:
authority:
status:
```

## B.6 Task Packet

```yaml
task_id:
objective:
authority_reference:
in_scope:
out_of_scope:
inputs:
allowed_tools:
prohibited_actions:
branch_or_environment:
risk_class:
acceptance_criteria:
evidence_required:
stop_conditions:
reviewers:
expiry:
state:
```

---

# APPENDIX C — PROMPT RESPONSE ARCHIVE

Every prompt response should be stored with:

```text
PROMPT-ID
PROMPT-LABEL
DESIGNATION
ISSUED-UTC
RECIPIENT-ROLE
SOURCE-REFERENCES
RESPONSE-REFERENCE
RESPONSE-REVISION
EVIDENCE-REFERENCES
DECISION
FINDINGS
DISSENT
INDEPENDENCE-DISCLOSURE
CANONICAL-REFERENCE
INTEGRITY-ANCHOR
SUPERSEDES
```

Temporary chat history is not, by itself, an authoritative archive.

---

# APPENDIX D — SOURCE AND STANDARDS BASIS

## D.1 PBIM source set reviewed

1. `Project_Base_Integration_Manager-v3.00.00.md`
2. `Project_Base_Integration_Manager-v3.00.01.md`
3. `Project_Base_Integration_Manager-v3.00.02.md`
4. `Project_Base_Integration_Manager-v3.00.03.md`
5. `Project_Base_Integration_Manager-v3.00.04.md`

The supplied v3.00.x documents were treated as historical source material for synthesis, not as automatically authoritative.

## D.2 Standards currency principle

At instantiation, the implementer shall re-check the applicable official sources.

The current design recognizes:

- PMI PMBOK Guide — Eighth Edition as the contemporary PMI reference;
- ISO 21502:2020 as published project-management guidance, while recognizing that a second-edition committee draft may be under development;
- ISO 31000:2018 as current published risk-management guidance;
- NIST SP 800-218 as secure software development guidance, while tracking newer draft revision activity;
- NIST AI RMF as a voluntary AI-risk framework that is being revised;
- ISO/IEC 42001:2023 as an AI management-system standard where applicable.

No standard, framework or draft is treated as automatic evidence of compliance.

---

# APPENDIX E — VERSION CONTROL

| Version | Status | Disposition |
|---|---|---|
| v3.00.00 | historical candidate | superseded for this synthesis |
| v3.00.01 | historical candidate | superseded for this synthesis |
| v3.00.02 | historical candidate | superseded for this synthesis |
| v3.00.03 | historical candidate | superseded for this synthesis |
| v3.00.04 | historical candidate | superseded for this synthesis |
| **v3.00.05** | **controlled generic candidate** | **requires AEA → AEV → AEC → AECC before adoption** |

---

# END OF PBIM v3.00.05 GENERIC CONSOLIDATED CANDIDATE
