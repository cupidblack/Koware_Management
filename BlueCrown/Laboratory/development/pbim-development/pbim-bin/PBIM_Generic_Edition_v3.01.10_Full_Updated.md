# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
|---|---|
| Document | Project Base Integration Manager — Generic Edition |
| Version | **v3.01.10** |
| Document Class | Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework |
| Status | **CONTROLLED GENERIC CANDIDATE — INDEPENDENT REVIEW CORRECTED** |
| Maturity | `DESIGNED` — specification only; implementation is not established by this document |
| Scope | Pre-charter probing, project-framework design, controlled initialization, simulation and Charter readiness |
| PBIM terminal boundary | `PBI-09-0004.09` → H0 transition decision → `[GOV-01-0004.1]` handoff |
| PBIM Document Creation identifier | `[PROJECT-KEY]::[PBI-01-0004.01]` |
| Expected timing fields | `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` |
| Implementation authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Production authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Genericity | Technology-, vendor-, repository-, organization-, product- and project-neutral |
| Patch basis | v3.01.09 + independent Prompt 2 review + authority/decision-vocabulary, consolidation-traceability, timing and identifier corrections |
| Review date | 2026-10-08 |

> This revision contains specification corrections only. It does not establish that any CI workflow, registry, emergency TTL mechanism, durable-reference mechanism, or other control is implemented or operating.

---
# 0. READER'S GUIDE
## 0.1 Purpose
PBIM is a reusable pre-charter framework for moving an initiative from an identified need, opportunity or request toward a controlled, evidence-backed and Charter-ready state. It establishes proportionate governance, context, proposal definition, project-template design, configuration planning, simulation and readiness evidence.

PBIM is not a Project Charter, Project Management Plan, implementation authorization, production authorization, procurement authorization, legal opinion, security certification, regulatory approval, budget commitment, operational authorization, or substitute for organizational governance.

PBIM ends when H0 or a formally recorded H0 delegate authorizes transition into the formal Charter process at `[GOV-01-0004.1]`. Charter-stage work is outside the operational PBIM lifecycle.

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

## 0.3 Consolidation statement and traceability requirement
Repeated control objectives from the v3.00.00–v3.00.04 source family and the AEA/AEV/AEC records are consolidated where the objective is materially identical. Repetition of standalone prompt boilerplate is retained only where needed for prompt integrity.

The consolidation claim is not established by renaming alone. Each retained source control must map to a canonical target control, or be explicitly marked as retained, specialized, superseded, or intentionally not adopted.

| Source concept family | Canonical v3.01.10 target | Treatment |
|---|---|---|
| AEA → AEV → AEC → AECC assurance cycle | §8 + Prompts 3–8, 11–13, 16–17 | Consolidated protocol with stage-specific roles |
| Authority / permissions / role separation | §2 + Prompt authority rules | Consolidated authority-capability-independence model |
| Durable references / provenance | §5 | Consolidated evidence-integrity model |
| State ladders / readiness states | §4 | Orthogonalized artifact, control and authorization states |
| Stop / reset / emergency controls | §4.6–4.8 | Consolidated recovery model; emergency TTL retained from adversarial evidence |
| Task Packet scope and enforcement | §7 | Consolidated schema and mechanical-enforcement requirement |
| Identifier registry | §3 | Separate identifier namespaces and registry constraints |
| Expected timing | §1 | Calendar, interval, timezone and duration-consistency rules |
| Risk scaling / protected controls | §4.4–4.5 | One materiality model with protected-control floor |
| Prompt grammar / resources / markers | §6 + §10–11 | One prompt contract plus standalone instances |

---
# 1. PROJECT IDENTITY AND EXPECTED TIMING
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
PRODUCTION-AUTHORIZATION      : NOT GRANTED
```

## 1.1 Normative expected-project timing rule
Expected timing is planning information, not a commitment or authorization. Duration must state calendar type, working-day convention, material working hours, capacity assumptions, dependencies, non-working days, estimating method, range, confidence/evidence quality, source and timezone where a boundary matters.

Normative interval semantics:
```text
START = first instant/date interval included in planned execution.
END   = completion boundary after the declared duration under the declared calendar.
```

Date-only schedules must state whether the end date is inclusive or exclusive. Timestamped schedules require an explicit timezone or UTC offset. Cross-jurisdictional schedules must not rely on an unstated local timezone.

**Consistency invariant:** where all three values are known, `END` must be derivable from `START + DURATION` under the declared calendar and working-time convention, within the declared rounding rule. If they do not reconcile, the timing block is `CONFLICTED`, advancement is blocked, and an owner must resolve the discrepancy. If evidence is insufficient, values remain `TBD` with owner, evidence requirement, target date and reason.

---
# 2. AUTHORITY, CAPABILITY AND INDEPENDENCE
## 2.1 Human authority
| Code | Generic role | Authority boundary |
|---|---|---|
| `H0` | Human Project Authority | Final project-level decisions, risk acceptance within mandate, resets and PBIM-to-Charter transition |
| `H1` | Delegated Human Governance/Technical Authority | Acts only within recorded delegation |
| `H2` | Authorized Operational/Technical Actor | Performs specifically authorized actions; access does not create governance authority |
| `CA` | Optional Constitutional/Organizational Authority | Use only where the organization actually has such a superior control role |

Technical access never creates governance authority.

## 2.2 Agent capabilities
`LEAD`, `ANL`, `VER`, `SEC`, `IMP`, `TST`, `OPS` and `DOC` are capability roles, not governance authorities. An agent may prepare, analyze, verify, challenge or recommend, but may not exercise a human governance decision merely because a prompt gives it a decision vocabulary.

## 2.3 Role-combination rule
```text
ACTOR → CAPABILITIES → TECHNICAL PERMISSIONS → GOVERNANCE ROLE
       → DECISION RIGHTS → INDEPENDENCE CLASS → EXPIRY/REVIEW
```
If one actor holds multiple capabilities, record why the combination is acceptable and what independent control remains. A role label cannot manufacture independence.

## 2.4 Decision-authority rule
Every prompt decision vocabulary must be interpreted as one of:
- `ASSESSMENT`: analytical/status result only;
- `RECOMMENDATION`: proposed disposition requiring the named authority;
- `AUTHORIZATION`: executable governance decision, permitted only when the prompt explicitly binds an authorized human authority and the authority record is verified.

Agent prompts must not use unqualified governance verbs such as `APPROVE`, `ACCEPT`, `AUTHORIZE`, `BASELINE` or `RELEASE` as if the agent itself possessed that authority. Human-authority prompts may use them only within their recorded authority boundary.

## 2.5 Privileged-account reconciliation
Any privileged technical account must reconcile to a named actor, capability, technical permission, governance role, authority basis, scope, expiry/review and independent check. Unreconciled privileged access is an authority-boundary finding and may require `MANDATORY-STOP` or `RESET-REQUIRED`.

---
# 3. IDENTIFIER ARCHITECTURE AND CANONICAL REGISTRY
## 3.1 PBIM identifiers
PBIM internal identifiers use `PBI-[SECTION]-0004.[STEP]`, for example `PBI-01-0004.01` through `PBI-09-0004.09`. These are not interchangeable with the formal Charter coordinate `[GOV-01-0004.1]`.

Prompt identifiers are a separate namespace: `PROMPT-01` through `PROMPT-25`. They must not be interpreted as PBIM section identifiers.

Document creation identifiers use an explicit delimiter: `[PROJECT-KEY]::[PBI-01-0004.01]`. Role/designation labels are never identifiers for authority.

## 3.2 Identifier grammar
At minimum, every identifier type must declare its grammar, namespace, case-sensitivity, allowed characters, issuer, project scope, revision behavior and retirement rule before the registry is treated as authoritative. A string that merely looks sequential is not a valid workflow command.

## 3.3 Identifier taxonomy
Keep separate: Project ID; PBIM Section ID; PM Process Classification ID; Prompt ID; Requirement ID; Work ID; Task Packet ID; Artifact ID; Evidence ID; Decision ID; Risk ID; Finding ID; ADR ID; Change ID; Release ID; repository object/commit; lifecycle state; authorization state.

## 3.4 Canonical Identifier Registry
Before PBIM can be treated as executable for a project, establish a canonical machine-readable Identifier Registry under the Governance Repository.

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
integrity_reference:
authority:
grammar_version:
issuer:
retirement_state:
```

Normative uniqueness:
```text
UNIQUE(project_id, identifier_type, identifier)
UNIQUE(project_id, identifier_type, sequence_index) where sequence_index is applicable
```

The registry must reject duplicate identifiers, invalid grammar, missing mandatory fields, illegal lifecycle transitions and conflicting authoritative revisions. Retired identifiers are tombstoned and never silently reused. Machine ordering uses explicit `sequence_index`, never lexical interpretation.

---
# 4. EVIDENCE, STATE, RISK AND CONTROL MODEL
## 4.1 Evidence classes
`VERIFIED FACT`, `INFERENCE`, `ASSUMPTION`, `PROPOSAL`, `RECOMMENDATION`, `RISK`, `UNKNOWN`, `DISPUTED`. Agent agreement or confidence does not upgrade evidence. `UNKNOWN` requires owner, evidence required, target date and advancement consequence.

## 4.2 Control maturity
```text
DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED
```
`DESIGNED` means specified; `ENFORCEABLE` means a credible bounded mechanism exists; `ENFORCED` requires evidence that the mechanism acts; `INDEPENDENTLY VERIFIED` requires appropriately independent verification.

## 4.3 Artifact and authorization state
Artifact: `DRAFT → ANALYSIS → CONTROLLED CANDIDATE → VERIFICATION → APPROVED → SUPERSEDED → ARCHIVED`.
Authorization: `NOT-AUTHORIZED → AUTHORIZED → IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED → PRODUCTION`.
Exception states: `STOPPED`, `RESET-REQUIRED`, `REGISTRY-BLOCKED`, `CONFLICTED`. PBIM does not grant production authorization.

## 4.4 Risk profiles
| Profile | Typical use | Minimum posture |
|---|---|---|
| `LIGHT` | Low-consequence, bounded, reversible work | Core authority, evidence, traceability, stop and verification controls; reduced ceremony elsewhere |
| `STANDARD` | Ordinary project work | Material controls, independent review where required, change and recovery controls |
| `HIGH-ASSURANCE` | Safety, financial, security, regulated, sensitive-data, irreversible or high-blast-radius work | Stronger evidence, independence, challenge, mechanical enforcement and operational verification |

Risk scaling changes ceremony, evidence depth, review breadth and assurance intensity. It does not permit omission of a legally mandatory, safety-critical, security-critical, authority-critical, evidence-integrity-critical or explicitly protected control. If applicability is uncertain, the control remains protected until applicability is resolved by an authorized decision.

## 4.5 Materiality escalation
```text
DETECT MATERIALITY INCREASE → FREEZE AFFECTED ADVANCEMENT → RECLASSIFY RISK
→ RE-RUN AFFECTED ASSURANCE → OBTAIN REQUIRED AUTHORITY → UPDATE BASELINE
→ RESUME ONLY AFTER CONTROLLED RELEASE
```
Materiality cannot be neutralized by splitting a material change into individually small tasks.

## 4.6 Stop states
```text
S0 RUNNING
S1 ADVISORY-STOP
S2 MANDATORY-STOP
S3 SYSTEM-STOP
S4 EMERGENCY-SAFETY-STOP
```
Every stop records trigger, timestamp, affected scope, invoking authority, evidence, disposition and resume criteria. No executor may self-clear a stop.

Resume requires the authority specified by the project Authority–Permission Matrix. If that matrix is unavailable or does not identify a resume authority, advancement is blocked and the matter escalates to the highest applicable human governance authority; H0 is a default escalation target only where H0 is validly established for the project.

## 4.7 Architectural reset
Reset is required when a load-bearing premise fails. Preserve evidence, freeze affected work, identify reset authority, return to the earliest affected stage, revise and re-assure.

## 4.8 Emergency delegation
Emergency delegation is an exception, not a permanent bypass. The maximum TTL is 72 hours as a PBIM ceiling, unless a stricter legal, regulatory or organizational rule requires a shorter period. The record must contain authority, delegate, scope, permitted/prohibited actions, start/expiry time, evidence requirements, reconciliation requirements and confirmation authority.

If confirmation does not occur before expiry, the delegation must become unusable and the governed state must enter `STOPPED`. Expired delegation cannot silently renew. The implementation must prove expiry enforcement; the specification alone does not.

Emergency operational authority does not, by itself, confer authority to approve PBIM-to-Charter transition. Unless the governing organization expressly and lawfully provides otherwise, Charter transition remains reserved to H0 or the formally recorded H0 delegate for that decision.

---
# 5. DURABLE REFERENCES, SECURITY AND MODERN PRACTICE
## 5.1 Critical durable reference rule
Critical evidence uses `ARTIFACT-ID @ IMMUTABLE OBJECT ID + INTEGRITY-HASH`. Mutable branch URLs alone are insufficient for critical claims.

## 5.2 Evidence provenance
Each critical evidence item records Evidence ID, source, canonical location, immutable identity, integrity reference, capture time, capture actor, evidence class and authority basis.

## 5.3 Security/privacy
Where applicable assess classification, privacy obligations, secrets management, least privilege, separation of duties, privileged access, supply-chain risk, secure development, logging/auditability, vulnerability management, backup/recovery, incident response, resilience, secure release and retention/deletion. Never place credentials, authentication tokens or secrets in PBIM prompts or ordinary evidence artifacts.

## 5.4 AI-enabled work
Where AI systems or agents are involved assess system role, authority boundary, data exposure, instruction precedence, tool permissions, output verification, provenance, model/dependency changes, adversarial inputs, human oversight, misuse, privacy, security and operational fallback. NIST AI RMF 1.0 may be used as a voluntary reference; its current revision status must be checked when adopted.

## 5.5 Standards currency
- PMBOK Guide — Eighth Edition: current PMI edition at review date; advisory unless adopted.
- ISO 21502:2020: published International Standard; ISO records it as under revision.
- ISO/CD 21502 Edition 2: development material, not a published International Standard.
- ISO 31000:2018: published International Standard; ISO records it as under revision.
- ISO/IEC 27001:2022 + Amendment 1:2024: published reference where applicable.
- ISO/IEC 42001:2023: AI-management reference where AI governance is in scope.
- NIST AI RMF 1.0: voluntary reference and currently being revised; do not treat version 1.0 as the final/current NIST AI RMF revision without checking the applicable NIST publication.
- OWASP ASVS 5.0.0: application-security verification reference where applicable.

Standards are advisory unless adopted or made binding by applicable law, contract or organizational governance. Certification or legal compliance must never be inferred from citation alone.

---
# 6. UNIVERSAL PROMPT ENGINEERING CONTRACT
Every operational PBIM prompt must use exactly one matching prompt start/end pair, explicit designation, named balanced resource blocks, explicit output and stop conditions, and its actual decision vocabulary.

Required prompt grammar:
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

Prompt integrity checks: exactly one matching prompt pair; balanced resource markers; no resource block contains the prompt end marker; placeholders are resolvable or explicitly `UNKNOWN`; resource labels match evidence; decision authority is not inferred from capability; no secret is embedded; decision vocabulary is explicit; agent decision terms are advisory/status terms unless human authority is explicitly bound.

## 6.1 Standard finding format
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
# 7. TASK PACKET CONTROL AND MECHANICAL ENFORCEMENT
A Task Packet is the controlled unit of executable work. Minimum fields include project_id, task_id, lifecycle_stage, objective, scope, exclusions, authoritative references, source files, authorized paths, requirements, constraints, assigned actor, permitted/prohibited actions, deliverables, verification requirements, acceptance criteria, output location, branch/worktree requirement, logging requirement, dependencies, stop/escalation conditions, authorization reference, expiry/validity and revision.

Where repository/file scope is enforced, a mechanical scope control must detect changes outside authorized paths, unauthorized protected-resource changes, missing authorization reference, stale/superseded Task Packets and inconsistent task identity. A named workflow such as `.github/workflows/task-scope-check.yml` is a specification example only and does not prove implementation.

---
# 8. TRACEABILITY AND ASSURANCE
PBIM supports `Requirement → Analysis → Architectural Decision → Verification → Task Packet / Implementation → Test → Approval / Authorization → Release`.

AEA = architectural analysis; AEV = independent verification; AEC = adversarial challenge; AECC = closure and formal disposition. Consensus is not verification. Verification is not authorization. Human authority owns governance decisions.

Blocking findings include protected-control failure, load-bearing premise failure, mandatory legal/security/safety failure, authority-boundary failure, evidence-integrity failure, identifier-integrity failure, timing conflict or unmet transition criteria. Material dissent is preserved.

---
# 9. PBIM LIFECYCLE
1. `PBI-01-0004.01` — Document Creation & Baseline Initialization — Gate G1: identity and authority context established.
2. `PBI-02-0004.02` — Architectural Assurance — Gate G2: material findings resolved or formally dispositioned.
3. `PBI-03-0004.03` — Project Context & Proposal Definition — Gate G3: context/proposal evidence sufficient.
4. `PBI-04-0004.04` — Project Proposal Engineering & Verification — Gate G4: proposal coherent and dispositioned.
5. `PBI-05-0004.05` — Project Template Assembly — Gate G5: controls identified, owned and risk-scaled.
6. `PBI-06-0004.06` — Project Template Engineering & Verification — Gate G6: protected controls sufficiently complete and enforceable by design.
7. `PBI-07-0004.07` — Project Configuration & Governance Initialization — Gate G7: initialized framework verifies against approved configuration and authority boundaries.
8. `PBI-08-0004.08` — Project Simulation & Readiness Review — Gate G8: evidence demonstrates control behavior.
9. `PBI-09-0004.09` — PBIM Activation & Charter Readiness — Gate G9: only H0 or formally recorded H0 delegate may authorize transition.

Minimum simulation families: normal Task Packet progression; ambiguous requirement; identifier collision; failed verification; material security defect; emergency delegation; unavailable human authority; agent disagreement; evidence loss; rollback/recovery; dependency/environment drift; timing inconsistency; readiness/release failure; unauthorized decision attempt.

---
# 10. STANDARDIZED PROMPT SET
Prompts 1–24 belong to the operational PBIM lifecycle. Prompt 25 is a post-PBIM Charter-stage handoff artifact and is not a PBIM gate.

## Prompt 1 — PBIM Generic Baseline Synthesis
PROMPT-ID: `PROMPT-01`
<<START Prompt 1 — PBIM Generic Baseline Synthesis>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START PBIM Reference Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Reference Package>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
APPROACHABLE / APPROACHABLE WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 1 — PBIM Generic Baseline Synthesis>>

## Prompt 2 — PBIM Baseline Independent Review
PROMPT-ID: `PROMPT-02`
<<START Prompt 2 — PBIM Baseline Independent Review>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START PBIM Candidate + PBIM Source Set>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + PBIM Source Set>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Analyze whether the PBIM architecture is coherent, governable, evidence-based, risk-scaled and generic.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START PBIM Candidate + Applicable Standards and Governance References>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Candidate + Applicable Standards and Governance References>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED
<<STOP Prompt 3 — PBIM Architectural Engineering Analysis>>

## Prompt 4 — PBIM Architectural Engineering Verification
PROMPT-ID: `PROMPT-04`
<<START Prompt 4 — PBIM Architectural Engineering Verification>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START AEA Package + PBIM Candidate>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA Package + PBIM Candidate>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Consolidate AEV decisions and prepare a bounded adversarial challenge package without converting recommendations into authority.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START AEV Decisions + Current AEV Statement>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV Decisions + Current AEV Statement>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
PREPARED / PREPARED WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 5 — PBIM AEV Decisions and Adversarial Challenge Preparation>>

## Prompt 6 — PBIM Architectural Engineering Challenge
PROMPT-ID: `PROMPT-06`
<<START Prompt 6 — PBIM Architectural Engineering Challenge>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START AEV-Approved Candidate + Existing AEC Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEV-Approved Candidate + Existing AEC Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Reconcile assurance findings without hiding material dissent and prepare a closure recommendation.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START AEC Results + AEV Statement and Decisions>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEC Results + AEV Statement and Decisions>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
CLOSURE-READY / CLOSURE-READY WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 7 — PBIM AEC Results and Closure Preparation>>

## Prompt 8 — PBIM Architectural Engineering Challenge Closure
PROMPT-ID: `PROMPT-08`
<<START Prompt 8 — PBIM Architectural Engineering Challenge Closure>>
[Designation: Lead Agent for preparation; Authorized Assurance Authority for disposition]

ROLE
Lead Agent for preparation; Authorized Assurance Authority for disposition.

OBJECTIVE
Prepare the closure package; only the recorded Authorized Assurance Authority may make the closure decision.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START AEA/AEV/AEC/Amendment Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP AEA/AEV/AEC/Amendment Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Develop an evidence-classified project proposal from the approved PBIM baseline.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved PBIM Baseline>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
DEFINED / DEFINED WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 9 — Project Context and Proposal Definition>>

## Prompt 10 — Project Context Independent Review
PROMPT-ID: `PROMPT-10`
<<START Prompt 10 — Project Context Independent Review>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Project Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Proposal + PBIM Baseline>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Controlled Project Proposal + Supporting Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Controlled Project Proposal + Supporting Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 11 — Project Proposal AEA>>

## Prompt 12 — Project Proposal AEV and AEC Verification
PROMPT-ID: `PROMPT-12`
<<START Prompt 12 — Project Proposal AEV and AEC Verification>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Proposal AEA Package + Current Proposal + PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal AEA Package + Current Proposal + PBIM Baseline>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / AEC BLOCKED / RETURN / BLOCKED
<<STOP Prompt 12 — Project Proposal AEV and AEC Verification>>

## Prompt 13 — Project Proposal AECC Closure and Baseline
PROMPT-ID: `PROMPT-13`
<<START Prompt 13 — Project Proposal AECC Closure and Baseline>>
[Designation: Lead Agent for preparation; Authorized Human Closure Authority for disposition]

ROLE
Lead Agent for preparation; Authorized Human Closure Authority for disposition.

OBJECTIVE
Prepare the closure package; only the recorded authorized human closure authority may make the baseline decision.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Proposal Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Proposal Assurance Package + Decisions + Corrective Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Verified Project Proposal + Approved PBIM Baseline>>
{{DURABLE-RESOURCE-SET}}
<<STOP Verified Project Proposal + Approved PBIM Baseline>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
DRAFTED / DRAFTED WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 14 — Project Template Assembly>>

## Prompt 15 — Project Template Completeness Review
PROMPT-ID: `PROMPT-15`
<<START Prompt 15 — Project Template Completeness Review>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Project Template + Verified Proposal>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Verified Proposal>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
CONCUR / CONCUR WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED
<<STOP Prompt 15 — Project Template Completeness Review>>

## Prompt 16 — Project Template Engineering, Verification and Challenge
PROMPT-ID: `PROMPT-16`
<<START Prompt 16 — Project Template Engineering, Verification and Challenge>>
[Designation: Lead Agent for engineering; Collaborating Agents for independent challenge/verification]

ROLE
Lead Agent for engineering; Collaborating Agents for independent challenge/verification.

OBJECTIVE
Engineer the candidate while preserving independent challenge and preventing self-certification.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Project Template + Requirements + Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Project Template + Requirements + Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / AEC BLOCKED / RETURN / BLOCKED
<<STOP Prompt 16 — Project Template Engineering, Verification and Challenge>>

## Prompt 17 — Project Template AECC Closure and Baseline
PROMPT-ID: `PROMPT-17`
<<START Prompt 17 — Project Template AECC Closure and Baseline>>
[Designation: Lead Agent for preparation; Authorized Assurance Authority for disposition]

ROLE
Lead Agent for preparation; Authorized Assurance Authority for disposition.

OBJECTIVE
Prepare the closure package; only the recorded Authorized Assurance Authority may make the baseline decision.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Template Assurance Package + Decisions + Corrective Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Template Assurance Package + Decisions + Corrective Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
BASELINE / BASELINE WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 17 — Project Template AECC Closure and Baseline>>

## Prompt 18 — Project Governance Configuration and Initialization
PROMPT-ID: `PROMPT-18`
<<START Prompt 18 — Project Governance Configuration and Initialization>>
[Designation: Lead Agent operating under recorded implementation authority]

ROLE
Lead Agent operating under recorded implementation authority.

OBJECTIVE
Instantiate only approved governance configuration and only within recorded technical and governance permissions.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Approved Project Template + Configuration Plan>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Project Template + Configuration Plan>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
INITIALIZATION-RECOMMENDED / INITIALIZATION-RECOMMENDED WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 18 — Project Governance Configuration and Initialization>>

## Prompt 19 — Project Governance Configuration Verification
PROMPT-ID: `PROMPT-19`
<<START Prompt 19 — Project Governance Configuration Verification>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Approved Configuration Plan + Initialized Governance Evidence>>
{{DURABLE-RESOURCE-SET}}
<<STOP Approved Configuration Plan + Initialized Governance Evidence>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
VERIFY / VERIFY WITH CONDITIONS / RETURN / BLOCKED / CHALLENGE-BLOCKED
<<STOP Prompt 19 — Project Governance Configuration Verification>>

## Prompt 20 — PBIM Project Simulation and Readiness Exercise
PROMPT-ID: `PROMPT-20`
<<START Prompt 20 — PBIM Project Simulation and Readiness Exercise>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Initialized Project Framework + Task Packet/Control Model>>
{{DURABLE-RESOURCE-SET}}
<<STOP Initialized Project Framework + Task Packet/Control Model>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
READY FOR CHALLENGE / READY WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 20 — PBIM Project Simulation and Readiness Exercise>>

## Prompt 21 — PBIM Readiness Independent Challenge
PROMPT-ID: `PROMPT-21`
<<START Prompt 21 — PBIM Readiness Independent Challenge>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Simulation Results + Readiness Evidence + Registers>>
{{DURABLE-RESOURCE-SET}}
<<STOP Simulation Results + Readiness Evidence + Registers>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Assemble the final pre-Charter transition package without granting Charter authority.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM/Proposal/Template Baselines + Configuration/Simulation Evidence + Registers>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
READY FOR HUMAN AUTHORIZATION / READY WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 22 — PBIM Activation and Charter Readiness>>

## Prompt 23 — PBIM Charter Readiness Independent Review
PROMPT-ID: `PROMPT-23`
<<START Prompt 23 — PBIM Charter Readiness Independent Review>>
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
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START Complete PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP Complete PBIM Transition Package>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

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
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START PBIM Transition Package + Independent Review>>
{{DURABLE-RESOURCE-SET}}
<<STOP PBIM Transition Package + Independent Review>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
AUTHORIZE CHARTER TRANSITION / AUTHORIZE WITH CONDITIONS / RETURN / BLOCK
<<STOP Prompt 24 — Human PBIM Transition Authorization>>

## Prompt 25 — Post-PBIM Charter Handoff Package
PROMPT-ID: `PROMPT-25`
<<START Prompt 25 — Post-PBIM Charter Handoff Package>>
[Designation: Lead Agent operating under H0-authorized Charter-stage Task Packet]

ROLE
Lead Agent operating under H0-authorized Charter-stage Task Packet.

OBJECTIVE
Prepare or transmit Charter-stage inputs only after H0 has authorized transition; this artifact is outside the operational PBIM lifecycle.

CONTEXT
Use only the supplied authoritative resources and applicable governance context. Do not infer missing authority, evidence, identity or implementation status.

INSTRUCTIONS
1. Preserve the distinction between capability, authority, evidence and decision ownership.
2. Classify unknowns explicitly and preserve material dissent.
3. Do not treat documentation as proof of enforcement or operation.
4. Apply the applicable risk profile, protected controls and stop conditions.
5. Do not allow prompt wording to create implementation, production or Charter-transition authority.
6. Agent outputs are recommendations/status assessments unless an explicitly recorded human authority is bound to the prompt and is authorized for that decision.
7. Independence is evidence-based; shared ownership, identical unchallenged inputs or technical access alone do not establish independence.

<<START H0 Authorization Record + Approved PBIM Transition Package>>
{{DURABLE-RESOURCE-SET}}
<<STOP H0 Authorization Record + Approved PBIM Transition Package>>
Note the following: The resource block is authoritative only to the extent provenance, integrity and immutable identity are established. Mutable pointers are not sufficient for critical claims.

OUTPUT
Required findings, evidence references, conditions, unresolved items, implementation/evidence limitations and the permitted assessment or recommendation.

STOP CONDITIONS
Stop for missing required authority, contradictory authoritative evidence, protected-control failure, evidence-integrity failure, identifier conflict, invalid scope, failed independence, requested action outside authorization, malformed/unresolved required resources, or an authority-bearing decision vocabulary used without the required human authority.

DECISION SET
HANDOFF COMPLETE / HANDOFF WITH CONDITIONS / RETURN / BLOCKED
<<STOP Prompt 25 — Post-PBIM Charter Handoff Package>>

---
# 11. UNIVERSAL PROMPT INSTANCE — REQUIRED FORM
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
[permitted assessment/recommendation vocabulary]
<<STOP Prompt N. [Prompt Label]>>
```
The normative form requires the decision vocabulary to be present in the prompt itself. An agent prompt may not acquire governance authority merely by using a governance verb.

---
# 12. PBIM / CHARTER BOUNDARY
```text
PBI-09-0004.09
      ↓
PBIM FINAL PRE-CHARTER PACKAGE
      ↓
PROMPT-23 — INDEPENDENT REVIEW
      ↓
PROMPT-24 — H0 TRANSITION AUTHORIZATION
      ↓
HANDOFF
      ↓
[GOV-01-0004.1] — Initiate Project or Phase / Develop Project Charter
```
Prompt 25 is not a PBIM gate. It may prepare or transmit Charter-stage inputs only after the H0 authorization record exists. The Lead Agent does not acquire governance authority from Prompt 25 wording.

---
# 13. IMPLEMENTATION-VERIFICATION OBLIGATIONS
This specification may require mechanisms without claiming they already operate.
Where applicable, the project must independently evidence the Human Authority Register, Authority–Permission Matrix, Identifier Registry, registry integrity controls, evidence-integrity mechanism, challenge-independence controls, instruction-drift detection, Task Packet scope enforcement, mechanical scope workflow, stop/reset enforcement, repository protections, secret/security controls, operational-readiness gate, durable-reference controls, privileged-account reconciliation, emergency TTL enforcement and audit/evidence retention.
A design amendment is not implementation closure evidence.

---
# 14. CLOSURE MATRIX
| Finding/control | v3.01.10 disposition | Closure state |
|---|---|---|
| Durable references | Immutable identity + integrity rule retained | Closed in specification / implementation unproven |
| Task Packet mechanical scope | Workflow requirement retained | Closed in specification / implementation unproven |
| Emergency delegation | 72-hour PBIM ceiling + stricter-rule override + Charter-authority exclusion | Closed in specification / enforcement unproven |
| Prompt resource integrity | Exact balanced marker requirement | Closed in specification |
| Agent decision authority leakage | Advisory/recommendation rule added to universal contract and prompt vocabularies | Closed in specification |
| Consolidation traceability | Source-to-canonical-control matrix added | Closed in specification |
| Timing consistency | Duration/start/end reconciliation invariant added | Closed in specification |
| Identifier grammar | Grammar/issuer/retirement fields added | Closed in specification |
| Prompt 8 authority ambiguity | Preparation/disposition roles separated | Closed in specification |
| Prompt 13 authority ambiguity | Preparation/disposition roles separated | Closed in specification |
| Prompt 16 self-certification ambiguity | Engineering vs independent challenge separated | Closed in specification |
| Prompt 17 authority ambiguity | Preparation/disposition roles separated | Closed in specification |
| Standards currency | NIST revision status made explicit; ISO/PMI statuses retained | Closed in specification |

---
# 15. EVIDENCE LIMITATIONS AND PRESERVED DISSENT
1. This document remains `DESIGNED`.
2. Naming a CI workflow does not prove it exists or operates.
3. Naming a 72-hour TTL does not prove automated expiry exists.
4. A SHA-256/object-ID requirement does not prove compliant hashes exist in evidence.
5. Historical AEA/AEV/AEC records are evidence about prior review, not proof of current operation.
6. Independent agents may agree while sharing flawed evidence; agreement does not constitute verification.
7. Source lineage contains historical and project-specific material; historical content is not automatically current normative authority.
8. Dissent and unresolved implementation obligations remain visible until independently evidenced.
9. The candidate's high-level consolidation statement was not treated as sufficient proof of consolidation; v3.01.10 therefore includes an explicit source-to-canonical-control traceability matrix.
10. Repository-rendering behavior is not treated as implementation evidence; raw immutable source should be used where exact prompt-marker integrity must be established.

---
# 16. REQUIRED FINAL READINESS QUESTIONS
Before H0 transition authorization:
1. Is project identity controlled?
2. Is human authority identified?
3. Are authority and capability separated?
4. Are privileged technical accounts reconciled?
5. Is risk profile defined?
6. Are timing values estimates with explicit calendar/interval/timezone semantics?
7. Do known duration/start/end values reconcile?
8. Is the baseline traceable to immutable evidence?
9. Is the identifier registry authoritative and uniquely constrained?
10. Are identifier grammars and issuers defined?
11. Are material claims evidence-classified?
12. Is the proposal verified?
13. Has the proposal been challenged?
14. Is the template internally coherent?
15. Are Task Packets bounded?
16. Is mechanical scope enforcement implemented where claimed?
17. Are security/privacy boundaries defined?
18. Can the project stop safely?
19. Can it reset safely?
20. Is emergency authority bounded by the 72-hour PBIM ceiling and explicitly excluded from Charter transition unless lawfully delegated?
21. Has the framework been initialized?
22. Has it been simulated?
23. Has readiness been independently challenged?
24. Are material blockers resolved or formally escalated?
25. Are Charter inputs traceable to PBIM artifacts?
26. Are all agent decision vocabularies interpreted as assessment/recommendation unless human authority is explicitly bound?
27. Is H0 prepared to authorize formal Charter transition?
If any mandatory answer is unresolved:
```text
DO NOT ADVANCE TO [GOV-01-0004.1]
```

---
# 17. DECISION AND RELEASE STATUS
```text
ARTIFACT STATE: CONTROLLED CANDIDATE
CONTROL MATURITY: DESIGNED
IMPLEMENTATION: NOT PROVEN
PRODUCTION AUTHORIZATION: NOT GRANTED
PBIM DECISION: RETURN — PENDING INDEPENDENT RE-REVIEW
```
Required progression conditions remain: independently verify durable-reference implementation; verify or implement mechanical Task Packet scope enforcement; demonstrate emergency TTL expiry; instantiate and independently verify the Identifier Registry; reconcile privileged accounts; preserve immutable baseline evidence; and independently re-review the corrected authority/prompt architecture.

---
# 18. CHANGE HISTORY
| Version | Change |
|---|---|
| v3.01.08 | Independent-review closure candidate |
| v3.01.09 | Authority/capability separation for Prompt 8, 13, 16 and 17; emergency delegation/Charter boundary; prompt namespace and document-identifier delimiter; protected-control risk rule; standards currency; prompt integrity; implementation-evidence closure language; stop-state authority/resume semantics |
| **v3.01.10** | Independent Prompt 2 review corrections: agent decision-vocabulary authority boundary; explicit consolidation traceability matrix; duration/start/end consistency invariant; stronger identifier grammar/issuer/retirement controls; qualified emergency TTL ceiling; NIST revision-status clarification; expanded readiness tests |

---
# 19. AUTHORITATIVE REFERENCE REGISTER
| Reference | Status / use |
|---|---|
| PMBOK Guide — Eighth Edition | Current PMI edition at review date; advisory project-management reference |
| ISO 21502:2020 | Published International Standard; under revision |
| ISO/CD 21502 Edition 2 | Development material; not a published International Standard |
| ISO 31000:2018 | Published International Standard; under revision |
| ISO/IEC 27001:2022 + Amd 1:2024 | Published security-management reference where applicable |
| ISO/IEC 42001:2023 | AI-management reference where AI governance is in scope |
| NIST AI RMF 1.0 | Voluntary reference; revision status must be checked when adopted |
| OWASP ASVS 5.0.0 | Application-security verification reference where applicable |

---
# 20. FINAL CONTROL PRINCIPLE
PBIM is not made durable by being comprehensive.
It is durable only when:
```text
IDENTITY IS UNAMBIGUOUS
+ AUTHORITY IS EXPLICIT
+ CAPABILITY IS SEPARATE
+ AGENT DECISIONS CANNOT CREATE UNGRANTED AUTHORITY
+ EVIDENCE IS DURABLE
+ SOURCE CONSOLIDATION IS TRACEABLE
+ TIMING VALUES ARE INTERNALLY CONSISTENT
+ IDENTIFIER GRAMMAR AND UNIQUENESS ARE ENFORCED
+ SCOPE IS MECHANICALLY BOUNDED WHERE CLAIMED
+ RISK ESCALATES WITH MATERIALITY
+ STOP/RESET STATES HAVE EXPLICIT AUTHORITY
+ EMERGENCY AUTHORITY EXPIRES AND CANNOT SILENTLY BECOME CHARTER AUTHORITY
+ PROMPTS HAVE EXPLICIT DECISION VOCABULARY AND BALANCED RESOURCE MARKERS
+ THE PBIM/CHARTER BOUNDARY IS UNAMBIGUOUS
+ IMPLEMENTATION CLAIMS ARE SUPPORTED BY IMPLEMENTATION EVIDENCE
+ DISSENT REMAINS VISIBLE
```
**End of PBIM Generic Edition v3.01.10.**