# PROJECT BASE INTEGRATION MANAGER [PBIM]

**Generic Edition:** v3.00.00  
**Document Class:** Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework  
**Status:** Controlled Generic Candidate — requires project-specific authorization and its own AEA/AEV/AEC/AECC cycle  
**Generated:** 2026-10-08  
**Supersedes conceptually:** PBIM v1.00.00 through v2.00.00 reference lineage  
**Implementation authorization:** NOT GRANTED  
**Production authorization:** NOT GRANTED  
**Architectural state:** DESIGNED — this document is a specification, not evidence that controls are implemented or enforced

---

# 0. PURPOSE, SCOPE AND NON-AUTHORIZATION

PBIM is a generic pre-charter integration framework for taking an initiative from an identified need or opportunity through a controlled project proposal, project operating template, governance initialization, readiness simulation and charter-ready state.

PBIM is not a Project Charter, Project Management Plan, architecture approval, implementation authorization, production authorization, procurement authorization, legal opinion, security exception, or substitute for human governance.

The PBIM lifecycle terminates at:

`[BASE-ID]-[PROJECT-ID][GOV-01-0004.01] — Initiate Project or Phase / Develop Project Charter`

After the terminal boundary, the project-specific Charter and approved project operating framework become authoritative for ordinary project execution.

## 0.1 Genericity rule

This document MUST remain technology-, vendor-, repository-, organization-, product- and project-neutral unless a project-specific instantiation explicitly binds those variables.

Example values are illustrative only and MUST NOT become inherited defaults.

## 0.2 Expected project timing

PBIM is a probing and planning instrument. Its timing fields are therefore explicitly expected rather than committed:

- `EXPECTED-PROJECT-DURATION`
- `EXPECTED-PROJECT-START-DATE`
- `EXPECTED-PROJECT-END-DATE`

These values are estimates used to probe feasibility, sequencing, capacity and readiness. They do not authorize work, create contractual commitments or establish a baseline schedule.

If insufficient evidence exists to calculate a credible expected value, record `TBD` with the missing evidence, owner and target date rather than inventing a value.

---

# 1. DOCUMENT CONTROL AND PROJECT IDENTITY

| Field | Generic Value |
|---|---|
| PBIM Name | `PROJECT BASE INTEGRATION MANAGER [PBIM]` |
| PBIM Version | `v3.00.00` |
| PBIM State | `DRAFT / CONTROLLED / APPROVED / BLOCKED / RESET` |
| BASE-ID | `[BASE-ID]` |
| PROJECT-ID | `[PROJECT-ID]` |
| PROJECT-KEY | `[BASE-ID]-[PROJECT-ID]` |
| PROJECT-NAME | `[PROJECT-FULL-NAME]` |
| PROJECT-BASE | `[PROJECT-BASE-NAME]` |
| PROJECT-LOCATION | `[JURISDICTION / LOCATION]` |
| EXPECTED-PROJECT-DURATION | `[EXPECTED VALUE OR TBD]` |
| EXPECTED-PROJECT-START-DATE | `[EXPECTED DATE OR TBD]` |
| EXPECTED-PROJECT-END-DATE | `[EXPECTED DATE OR TBD]` |
| Delivery Approach | `PREDICTIVE / ITERATIVE / INCREMENTAL / ADAPTIVE / HYBRID / OTHER` |
| Risk Profile | `LIGHT / STANDARD / HIGH-ASSURANCE` |
| Human Project Authority | `[H0]` |
| Constitutional Authority | `[CA, IF APPLICABLE]` |
| Lead Agent | `[LEAD ROLE/IDENTITY]` |
| Collaborating Agents | `[ROLE/IDENTITY ROSTER]` |
| Canonical Source | `[DURABLE REFERENCE]` |
| Integrity Anchor | `[COMMIT/TAG/OBJECT/HASH OR EQUIVALENT]` |
| Parent Revision | `[SUPERSEDES]` |

## 1.1 Timing calculation rule

`EXPECTED-PROJECT-DURATION` should be derived from the proposed work, dependencies, resource availability, delivery approach, jurisdictional constraints and known working-time rules.

`EXPECTED-PROJECT-END-DATE` should be calculated from the expected start date plus the expected duration and stated calendar assumptions.

The PBIM MUST identify whether dates are:
- evidence-supported estimates;
- scenario estimates;
- provisional assumptions; or
- unknown.

---

# 2. GOVERNANCE, ASSURANCE AND AUTHORITY MODEL

PBIM uses three layers:

`GOVERNANCE → ASSURANCE → EXECUTION`

Governance defines authority and constraints. Assurance evaluates evidence, architecture and readiness. Execution performs only authorized work.

## 2.1 Human authority

- `CA` — Constitutional Authority, where the organization has a constitutional governance layer.
- `H0` — Human Project Authority; final human authority for project-level material decisions.
- `H1` — Delegated authority within recorded limits.
- `H2` — Authorized participant without authority beyond the delegation register.

PBIM cannot create, appoint or supersede constitutional authority.

## 2.2 Agent roles

| Role | Responsibility |
|---|---|
| Lead Agent | Synthesis, orchestration, evidence preservation and gate preparation |
| Analysis Agent | Independent architectural/domain analysis |
| Verification Agent | Evidence sufficiency, reproducibility and traceability |
| Challenge Agent | Adversarial challenge and failure analysis |
| Implementation Agent | Executes approved work only |
| Test/Validation Agent | Tests outcomes, including negative and stop conditions |
| Documentation Agent | Identifier, revision, provenance and record integrity |
| Operations/Readiness Agent | Operational readiness, support, rollback and transition evidence |

A role does not grant authority by itself.

## 2.3 Authority-capability separation

Technical privilege is not governance authority.

Repository administration, cloud administration, CI/CD control, database access, deployment access or agent/tool capability MUST NOT by themselves authorize changes to protected governance resources.

## 2.4 Independence

Independence is risk-scaled and must be demonstrated rather than declared.

For HIGH-ASSURANCE work, independence SHOULD be evaluated across:
- organizational independence;
- evidence independence;
- technical independence;
- governance/decision independence.

If required independence cannot be established, the applicable review state is `CHALLENGE-BLOCKED`.

---

# 3. IDENTIFIER, REGISTRY AND TRACEABILITY ARCHITECTURE

The PBIM uses separate identities for different objects.

## 3.1 Canonical identifiers

### Project

`[BASE-ID]-[PROJECT-ID]`

### PBIM pre-charter section

`[BASE-ID]-[PROJECT-ID][PBI-<NN>-0004.00.<SS>]`

Example:

`[BASE-ID]-[PROJECT-ID][PBI-01-0004.00.01]`

### Charter transition

`[BASE-ID]-[PROJECT-ID][GOV-01-0004.01]`

The pre-charter `0004.00.xx` namespace and the terminal `0004.01` anchor eliminate the historical ambiguity between `0004.01`, `0004.1` and `0004.10`.

## 3.2 Artifact classes

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
| Release/Transition | `REL-####.#` |
| Prompt | `<SECTION-ID>-P##` |

## 3.3 Identifier registry

The registry is authoritative and machine-readable where feasible.

Allocation:

`REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM`

Conflicting, stale or unverifiable registry state blocks progression.

Retired identifiers remain tombstoned and are never silently reused.

## 3.4 Traceability chain

Material work SHOULD be traceable through:

`Need/Objectives → Requirement → Decision → Architecture → Task Packet → Change → Verification → Release/Transition`

A missing link is recorded as a traceability finding.

---

# 4. EVIDENCE, DECISION, BASELINE AND CONTROL-STATE MODEL

## 4.1 Evidence classes

Every substantive claim is classified as one of:

`VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RECOMMENDATION / RISK / UNKNOWN / DISPUTED`

Repetition, consensus or confidence does not upgrade evidence.

## 4.2 Control maturity

PBIM distinguishes:

`DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED`

A document describing a control is not proof that the control operates.

## 4.3 Canonical source and durable reference

Each authoritative artifact class MUST have one canonical source.

A durable reference SHOULD identify:
- artifact identifier;
- immutable commit/tag/object/version;
- integrity reference where practical;
- revision;
- supersession relationship.

Branch names and mutable web pointers are convenience references, not sufficient authority for critical evidence.

## 4.4 Decision record

Material decisions SHOULD contain:
- decision ID;
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
- superseded decision, if applicable.

## 4.5 Protected governance resources

Where applicable, the following are protected:
- PBIM baseline;
- authority register;
- authority-permission matrix;
- identifier registry;
- canonical source manifest;
- decision ledger;
- ADRs;
- AEA/AEV/AEC/AECC records;
- Task Packet state;
- evidence-integrity records;
- readiness/transition records.

---

# 5. RISK, MATERIALITY, SECURITY AND TAILORING

## 5.1 Risk profiles

| Profile | Intended use | Minimum posture |
|---|---|---|
| LIGHT | Low-consequence, bounded work | Proportionate controls; protected governance and evidence rules retained |
| STANDARD | Normal project/product work | Full PBIM controls proportionate to materiality |
| HIGH-ASSURANCE | Safety, finance, security, regulated, sensitive-data, irreversible or high-blast-radius work | Stronger evidence, independence, challenge and operational verification |

Tailoring may reduce ceremony, not protected controls.

## 5.2 Cumulative materiality

Risk MUST be assessed across:
- related tasks;
- dependencies;
- migrations;
- concurrent changes;
- releases;
- shared infrastructure;
- shared data;
- combined blast radius.

Several low-risk changes can constitute a material aggregate change.

## 5.3 Security and privacy boundary

Where software, data or AI systems are involved, the project SHOULD establish:
- data classification;
- secrets boundary;
- privileged-access model;
- dependency/supply-chain controls;
- secure development controls;
- logging/audit requirements;
- privacy obligations;
- incident and recovery requirements.

Applicable local law and binding organizational policy take precedence over generic guidance.

---

# 6. ASSURANCE PROTOCOL

The reusable assurance protocol is:

`AEA → AEV → AEC → AECC`

It applies to PBIM itself and, where material, to the Project Proposal and Project Template.

## 6.1 AEA — Architectural Engineering Analysis

AEA asks whether the proposed design, workflow, governance model or control system is coherent and whether its assumptions survive independent analysis.

AEA does not approve.

## 6.2 AEV — Architectural Engineering Verification

AEV determines whether the baseline specifies sufficient:
- authority;
- scope;
- states;
- controls;
- evidence;
- transitions;
- failure handling;
- security/privacy boundaries;
- materiality rules;
- acceptance criteria.

AEV does not prove implementation.

## 6.3 AEC — Adversarial Engineering Challenge

AEC attempts to invalidate the baseline.

Relevant attack domains include:
- hidden authority;
- privilege bypass;
- identifier/registry corruption;
- evidence manipulation;
- false independence;
- task-scope escape;
- instruction conflict;
- emergency bypass;
- human unavailability;
- automation compromise;
- baseline/dependency drift;
- cumulative materiality;
- operational readiness;
- rollback/recovery assumptions;
- durable-reference failure.

## 6.4 AECC — Architectural Engineering Challenge Closure

AECC closes only findings that have evidence-supported disposition.

Every material finding records:
- finding;
- severity/materiality;
- affected control;
- disposition;
- corrective action;
- evidence;
- independent confirmation where required;
- residual risk;
- authority;
- closure state;
- supersession.

A vote does not close a blocker.

## 6.5 Reset rule

If a load-bearing assumption is invalidated, the workflow returns to the affected design stage or triggers `ARCHITECTURAL RESET`.

A failed gate must not be converted into approval through wording changes.

---

# 7. PBIM SECTION MAP

| Section ID | Section | Primary Output | Gate |
|---|---|---|---|
| `PBI-01-0004.00.01` | PBIM Document Creation & Baseline Intake | Generic PBIM candidate | Resource completeness |
| `PBI-02-0004.00.02` | PBIM Architectural Assurance | AEA/AEV/AEC/AECC evidence set | PBIM baseline approval |
| `PBI-03-0004.00.03` | Project Context & Proposal Definition | Initial project proposal | Context completeness |
| `PBI-04-0004.00.04` | Project Proposal Engineering & Verification | Verified project proposal | Proposal baseline |
| `PBI-05-0004.00.05` | Project Template Assembly | Project template candidate | Assembly completeness |
| `PBI-06-0004.00.06` | Project Template Engineering & Verification | Verified project template | Template readiness |
| `PBI-07-0004.00.07` | Project Configuration & Governance Initialization | Configured governance environment | Configuration integrity |
| `PBI-08-0004.00.08` | Project Simulation & Readiness Review | Simulation/readiness evidence | Operational readiness |
| `PBI-09-0004.00.09` | PBIM Activation & Charter Readiness | Charter-ready pre-charter package | Human authorization |
| `GOV-01-0004.01` | Initiate Project or Phase / Develop Project Charter | Project Charter | PBIM boundary |

---

# 8. STANDARD SECTION-CARD MODEL

Every PBIM section SHALL contain:

1. Identifier and title.
2. Purpose.
3. Inputs.
4. Activities.
5. Outputs.
6. Dependencies.
7. Evidence requirements.
8. Acceptance/exit criteria.
9. Stop conditions.
10. Gate.
11. Lead-agent prompt.
12. Collaborating-agent prompt(s), where applicable.
13. Required decision vocabulary.
14. Resource blocks using explicit START/STOP markers.
15. Notes below resources.
16. No implicit authority.

The prompts below intentionally use a common structure while adding section-specific reasoning. This replaces the repeated generic prompts found across earlier PBIM revisions.

---

# 9. `[PBI-01-0004.00.01]` — PBIM DOCUMENT CREATION & BASELINE INTAKE

## Purpose

Construct a generic PBIM candidate from the complete available reference set, reconcile historical versions, identify contradictions, modernize obsolete structures and establish a controlled baseline candidate.

## Inputs

- all supplied historical PBIM versions;
- current organizational governance requirements;
- applicable external standards/guidance;
- known project-base requirements;
- any prior AEA/AEV/AEC/AECC evidence.

## Activities

1. Inventory every source.
2. Establish version lineage.
3. Identify repeated concepts, contradictions and obsolete terminology.
4. Compare identifiers and headings.
5. Compare prompts and resource-marker conventions.
6. Reconcile architecture/implementation boundaries.
7. Establish the generic section map.
8. Record unresolved assumptions.
9. Produce the candidate PBIM.

## Outputs

- PBIM candidate;
- version/lineage map;
- identifier migration map;
- prompt migration map;
- unresolved-items register;
- modernization summary.

## Exit criteria

All supplied sources are accounted for; material conflicts are recorded; genericity is preserved; the section map is complete; the prompt contract is explicit.

## Gate

`GATE PBI-01 — RESOURCE AND BASELINE COMPLETENESS`

### <<START Prompt 1. PBIM Document Creation & Baseline Synthesis>>

[Designation: Lead Agent]

Thoroughly review the complete PBIM source set and produce a generic PBIM candidate.

Perform a version-by-version comparison of:
- document purpose and boundary;
- section identifiers;
- headings and labels;
- lifecycle structure;
- governance and authority model;
- evidence model;
- identifier model;
- assurance model;
- prompts;
- resource markers;
- notes;
- stop conditions;
- outputs and gates;
- project-management and engineering assumptions;
- standards claims.

Merge repeated or materially equivalent controls instead of reproducing them. Preserve useful historical traceability through a migration/supersession table.

Do not inherit project-specific names, technologies, repositories, vendors, branches, dates, agents or architecture as generic requirements.

Classify substantive claims as VERIFIED FACT, INFERENCE, ASSUMPTION, PROPOSAL, RECOMMENDATION, RISK, UNKNOWN or DISPUTED.

Return:
1. Executive synthesis.
2. Version lineage and major changes.
3. Repeated/similar concepts identified and merged.
4. Identifier migration map.
5. Prompt migration map.
6. Modernization and standards-alignment changes.
7. Complete generic PBIM candidate.
8. Unresolved assumptions and evidence gaps.
9. Gate/stop assessment.

### <<START PBIM Historical Source Set>>
{{Attach or link every PBIM version being used as evidence.}}
<<STOP PBIM Historical Source Set>>

Notes:
- Treat source documents as evidence, not automatic authority.
- Do not silently resolve material contradictions.
- Do not claim implementation or enforcement merely because a control is specified.

<<STOP Prompt 1. PBIM Document Creation & Baseline Synthesis>>

### <<START Prompt 2. PBIM Baseline Independent Review>>

[Designation: Collaborating Agents]

Independently review the PBIM candidate and the source set.

Test whether the candidate:
- preserves important historical intent;
- removes obsolete or contradictory structures;
- merges repeated controls without losing meaning;
- uses unambiguous identifiers;
- uses the required prompt/resource syntax;
- separates governance, assurance and execution;
- separates specification from implementation evidence;
- provides actionable gates and stop conditions;
- remains genuinely generic.

Identify concrete failure modes, not wording preferences.

Return:
1. Review scope.
2. Findings with IDs, evidence and materiality.
3. Merged-concept assessment.
4. Identifier/prompt defects.
5. Missing evidence.
6. Independence disclosure.
7. Decision: APPROVE / APPROVE-WITH-OBSERVATIONS / RETURN / BLOCKED.

### <<START PBIM Candidate>>
{{Link to the controlled PBIM candidate.}}
<<STOP PBIM Candidate>>

### <<START PBIM Source Set>>
{{Link to the historical PBIM documents and applicable standards/policies.}}
<<STOP PBIM Source Set>>

Notes:
- If any required source cannot be read, identify it and do not infer its contents.
- Do not approve merely because the candidate is comprehensive.

<<STOP Prompt 2. PBIM Baseline Independent Review>>

---

# 10. `[PBI-02-0004.00.02]` — PBIM ARCHITECTURAL ASSURANCE

## Purpose

Subject the PBIM architecture to the reusable AEA→AEV→AEC→AECC assurance protocol.

## Outputs

- AEA query;
- independent AEA reports;
- AEV statement and decisions;
- AEC challenge packet/results;
- AECC closure;
- approved or reset PBIM baseline.

## Exit criteria

No material unresolved blocker remains; required independence is established; residual risks are explicitly dispositioned; human authority authorizes adoption.

## Gate

`GATE PBI-02 — PBIM ARCHITECTURAL BASELINE`

### <<START Prompt 3. PBIM Architectural Engineering Analysis>>

[Designation: Lead Agent]

Develop and conduct an AEA of the complete PBIM architecture.

Evaluate:
- authority and decision rights;
- identifier and registry integrity;
- evidence/provenance;
- prompt architecture;
- agent separation and independence;
- assurance lifecycle;
- risk scaling;
- Task Packet boundaries;
- stop/resume/reset behavior;
- emergency authority;
- security/privacy boundary;
- instruction precedence;
- baseline and dependency drift;
- cumulative materiality;
- operational readiness;
- recovery;
- human unavailability;
- automation compromise.

For each finding provide evidence, affected control, consequence, materiality and recommendation.

Do not approve the architecture.

### <<START PBIM Candidate>>
{{Controlled PBIM candidate and durable reference.}}
<<STOP PBIM Candidate>>

### <<START Applicable Standards and Governance References>>
{{Current standards, policies and governance sources relevant to the analysis.}}
<<STOP Applicable Standards and Governance References>>

Notes:
- External standards are reference frameworks unless explicitly adopted.
- Draft standards must be labeled as draft.

<<STOP Prompt 3. PBIM Architectural Engineering Analysis>>

### <<START Prompt 4. PBIM Architectural Engineering Verification>>

[Designation: Collaborating Agents]

Review the AEV candidate as an independent verifier.

Determine whether each material control has:
- an authority;
- a scope;
- a defined state;
- an evidence path;
- a transition;
- a failure response;
- an acceptance criterion;
- an owner.

Check that the architecture does not claim enforcement without implementation evidence.

Return one decision:
`APPROVE / RETURN / DISAPPROVE / CHALLENGE-BLOCKED`

A conditional approval is a RETURN unless the project-specific governance model explicitly defines and records conditional acceptance.

### <<START PBIM AEV Statement>>
{{Attach the complete AEV statement.}}
<<STOP PBIM AEV Statement>>

### <<START PBIM AEA Evidence>>
{{Attach all relevant AEA reports and source evidence.}}
<<STOP PBIM AEA Evidence>>

Notes:
- Read the complete statement before deciding.
- Preserve dissent.

<<STOP Prompt 4. PBIM Architectural Engineering Verification>>

### <<START Prompt 5. PBIM Adversarial Engineering Challenge>>

[Designation: Collaborating Agents / Challenge Agents]

Attempt to invalidate the PBIM architecture rather than improve its wording.

Construct realistic scenarios involving:
- missing or unavailable human authority;
- conflicting instructions;
- malicious or mistaken agent behavior;
- privileged technical override;
- task-scope escape;
- secret/data exposure;
- evidence tampering;
- identifier/registry corruption;
- stale baseline;
- dependency failure;
- emergency change;
- legal/regulatory conflict;
- cumulative materiality;
- schedule/cost collapse;
- sponsor withdrawal;
- operational incident;
- recovery failure;
- false closure.

For each scenario identify:
1. trigger;
2. detection;
3. expected stop state;
4. authorized decision-maker;
5. evidence;
6. recovery;
7. reset requirement;
8. preventive control;
9. residual risk.

Do not copy the baseline's own conclusion as proof.

### <<START PBIM AEV-Approved Baseline>>
{{Attach the exact AEV-approved candidate under challenge.}}
<<STOP PBIM AEV-Approved Baseline>>

### <<START PBIM AEC Evidence>>
{{Attach prior relevant challenge evidence, if any.}}
<<STOP PBIM AEC Evidence>>

Notes:
- If a live protected-control failure is discovered, mark it BLOCKING and stop.
- Do not downgrade severity to reach closure.

<<STOP Prompt 5. PBIM Adversarial Engineering Challenge>>

### <<START Prompt 6. PBIM Assurance Closure & Regeneration>>

[Designation: Lead Agent + Authorized Assurance Authority]

Reconcile every AEA, AEV and AEC finding.

Do not close a material finding by:
- renaming it;
- splitting it;
- downgrading severity;
- replacing the baseline without addressing the underlying risk;
- relying on consensus alone.

For each finding record:
- original finding;
- materiality;
- disposition;
- corrective control;
- evidence;
- independent confirmation;
- residual risk;
- authority;
- closure state;
- supersession.

If a load-bearing assumption failed, initiate ARCHITECTURAL RESET.

Only after closure requirements are met, regenerate the PBIM as the next controlled revision. Do not claim ENFORCED or INDEPENDENTLY VERIFIED unless implementation evidence exists.

### <<START PBIM AEC Results>>
{{Attach complete adversarial challenge results.}}
<<STOP PBIM AEC Results>>

### <<START PBIM AEV Statement and Decisions>>
{{Attach the latest AEV statement and all decisions.}}
<<STOP PBIM AEV Statement and Decisions>>

Notes:
- One material unresolved blocker prevents gate advancement.
- AECC records closure; it does not substitute for implementation evidence.

<<STOP Prompt 6. PBIM Assurance Closure & Regeneration>>

---

# 11. `[PBI-03-0004.00.03]` — PROJECT CONTEXT & PROPOSAL DEFINITION

## Purpose

Translate the authorized PBIM context into a project-specific proposal without inventing architecture, scope or authority.

## Required outputs

- project context;
- problem/opportunity statement;
- objectives/outcomes;
- stakeholder context;
- preliminary scope;
- constraints;
- assumptions;
- dependencies;
- expected timing;
- initial risk/materiality view;
- proposal candidate.

### <<START Prompt 7. Project Context & Proposal Definition>>

[Designation: Lead Agent]

Using only the approved PBIM baseline and supplied project evidence, establish the initial project proposal.

Define:
- problem/opportunity;
- intended value/outcomes;
- measurable objectives;
- stakeholders;
- preliminary scope and exclusions;
- constraints;
- dependencies;
- assumptions;
- expected project duration;
- expected start date;
- expected end date;
- delivery approach hypothesis;
- risk profile;
- evidence gaps.

Do not design detailed implementation architecture unless explicitly supported by evidence and necessary to define feasibility.

### <<START Approved PBIM Baseline>>
{{Link to the approved PBIM baseline.}}
<<STOP Approved PBIM Baseline>>

### <<START Project Context and Source Evidence>>
{{Attach project request, business/operational context, known requirements and constraints.}}
<<STOP Project Context and Source Evidence>>

Notes:
- Expected dates are probing estimates only.
- Record unknowns rather than inventing facts.

<<STOP Prompt 7. Project Context & Proposal Definition>>

### <<START Prompt 8. Project Proposal Independent Review>>

[Designation: Collaborating Agents]

Independently review the project proposal for:
- value and objective coherence;
- scope integrity;
- stakeholder completeness;
- dependency realism;
- assumption quality;
- schedule plausibility;
- risk/materiality;
- security/privacy/regulatory triggers;
- operational sustainability;
- architecture feasibility at the level justified by evidence.

Identify conditions that would invalidate the proposal.

Return:
`APPROVE / RETURN / BLOCKED`

### <<START Project Proposal Candidate>>
{{Attach complete proposal candidate.}}
<<STOP Project Proposal Candidate>>

### <<START PBIM Baseline>>
{{Attach relevant PBIM baseline sections.}}
<<STOP PBIM Baseline>>

Notes:
- A well-written proposal is not automatically a feasible proposal.

<<STOP Prompt 8. Project Proposal Independent Review>>

---

# 12. `[PBI-04-0004.00.04]` — PROJECT PROPOSAL ENGINEERING & VERIFICATION

## Purpose

Turn the proposal candidate into a verified, challenged and baselined proposal.

### <<START Prompt 9. Project Proposal Architectural Engineering Analysis>>

[Designation: Lead Agent]

Develop an AEA query for the project proposal and analyze it against the PBIM baseline.

Test:
- feasibility;
- value logic;
- requirements coherence;
- stakeholder impact;
- technical plausibility;
- financial assumptions;
- schedule/resource realism;
- security/privacy;
- regulatory applicability;
- AI use, if any;
- sustainability where material;
- procurement;
- operational transition;
- failure modes.

Produce a findings register and recommended proposal baseline.

### <<START Project Proposal Candidate>>
{{Attach the current proposal.}}
<<STOP Project Proposal Candidate>>

### <<START Relevant PBIM Baseline>>
{{Attach applicable PBIM controls and gates.}}
<<STOP Relevant PBIM Baseline>>

Notes:
- Distinguish verified evidence from assumptions.

<<STOP Prompt 9. Project Proposal Architectural Engineering Analysis>>

### <<START Prompt 10. Project Proposal Verification & Challenge>>

[Designation: Collaborating Agents]

Review the proposal AEV statement and challenge its material assumptions.

Verify:
- authority;
- scope;
- requirements;
- acceptance criteria;
- evidence sufficiency;
- risk treatment;
- dependencies;
- delivery approach;
- timing assumptions;
- regulatory/security/privacy applicability;
- transition readiness.

Then attack the proposal using realistic failure scenarios.

Return:
`APPROVE / RETURN / DISAPPROVE / CHALLENGE-BLOCKED`

### <<START Project Proposal AEV Statement>>
{{Attach complete AEV statement.}}
<<STOP Project Proposal AEV Statement>>

### <<START Project Proposal AEC Results>>
{{Attach complete challenge results, if available.}}
<<STOP Project Proposal AEC Results>>

Notes:
- A material disapproval requires remediation and re-review.

<<STOP Prompt 10. Project Proposal Verification & Challenge>>

### <<START Prompt 11. Project Proposal Closure & Baseline>>

[Designation: Lead Agent + Authorized Assurance Authority]

Close the proposal assurance cycle only after material findings are resolved or formally dispositioned.

Produce:
- proposal AECC/closure record;
- verified proposal;
- residual-risk register;
- decision record;
- durable baseline reference.

The baselined proposal becomes the only authorized input for project-template generation unless an approved change is recorded.

### <<START Project Proposal Review Package>>
{{Attach AEA, AEV, AEC and relevant decisions.}}
<<STOP Project Proposal Review Package>>

Notes:
- Do not silently change proposal scope during closure.

<<STOP Prompt 11. Project Proposal Closure & Baseline>>

---

# 13. `[PBI-05-0004.00.05]` — PROJECT TEMPLATE ASSEMBLY

## Purpose

Generate a project-specific operating template from the baselined proposal and PBIM.

The template must select and justify an appropriate delivery approach rather than forcing every project into one methodology.

It should define, as applicable:
- governance;
- lifecycle;
- scope;
- requirements;
- schedule;
- finance;
- quality;
- resources;
- stakeholders;
- communications;
- risks;
- procurement;
- architecture/engineering;
- security;
- privacy/data;
- AI governance;
- repository/documentation;
- ADRs and decisions;
- Task Packets;
- evidence;
- testing;
- change control;
- release;
- operations/readiness;
- closure and retention.

### <<START Prompt 12. Project Template Assembly>>

[Designation: Lead Agent]

Generate the project-specific operating template from the verified proposal and approved PBIM.

Select and justify the delivery model:
`PREDICTIVE / ITERATIVE / INCREMENTAL / ADAPTIVE / HYBRID / OTHER`

Do not force unnecessary ceremony.

Define the minimum operating controls required for the project's risk profile and expected lifecycle.

For each proposed control identify:
- owner;
- authority;
- evidence;
- acceptance criterion;
- lifecycle placement;
- stop condition.

### <<START Verified Project Proposal>>
{{Attach the baselined project proposal.}}
<<STOP Verified Project Proposal>>

### <<START Approved PBIM Baseline>>
{{Attach the PBIM baseline and applicable controls.}}
<<STOP Approved PBIM Baseline>>

Notes:
- The template is a project-specific design, not a generic copy of PBIM.
- Do not grant implementation authority.

<<STOP Prompt 12. Project Template Assembly>>

### <<START Prompt 13. Project Template Completeness Review>>

[Designation: Collaborating Agents]

Review the template for missing, excessive, contradictory, unowned or unenforceable controls.

Check:
- lifecycle fit;
- delivery-method fit;
- authority;
- scope;
- requirements;
- evidence;
- security/privacy;
- operational readiness;
- change control;
- testing;
- release;
- records/retention.

Return:
`COMPLETE / RETURN / BLOCKED`

### <<START Project Template Candidate>>
{{Attach the complete template candidate.}}
<<STOP Project Template Candidate>>

### <<START Verified Project Proposal>>
{{Attach the verified proposal.}}
<<STOP Verified Project Proposal>>

Notes:
- Identify controls with no realistic enforcement or evidence path.

<<STOP Prompt 13. Project Template Completeness Review>>

---

# 14. `[PBI-06-0004.00.06]` — PROJECT TEMPLATE ENGINEERING & VERIFICATION

## Purpose

Assure the project template using the reusable assurance protocol.

### <<START Prompt 14. Project Template Architectural Engineering>>

[Designation: Lead Agent]

Analyze the project template against the baselined proposal and PBIM.

Identify:
- missing controls;
- contradictory controls;
- unnecessary ceremony;
- unowned controls;
- controls without evidence paths;
- controls without approval paths;
- controls that cannot be enforced;
- security/privacy gaps;
- regulatory gaps;
- operational gaps;
- technical architecture gaps;
- AI-governance gaps where applicable.

Produce the AEV candidate and prepare it for independent review.

### <<START Project Template Candidate>>
{{Attach complete template.}}
<<STOP Project Template Candidate>>

### <<START Proposal and PBIM Baselines>>
{{Attach authoritative proposal and PBIM references.}}
<<STOP Proposal and PBIM Baselines>>

Notes:
- Do not redesign the project beyond the approved proposal without recording a change.

<<STOP Prompt 14. Project Template Architectural Engineering>>

### <<START Prompt 15. Project Template Verification & Adversarial Challenge>>

[Designation: Collaborating Agents / Challenge Agents]

Verify the project template and then attempt to break it.

Construct scenarios involving:
- ambiguous authority;
- scope escape;
- conflicting instructions;
- dependency failure;
- failed verification;
- intermittent or misleading tests;
- evidence loss;
- emergency change;
- security/privacy incident;
- operational failure;
- rollback failure;
- human unavailability.

For each scenario identify detection, stop state, authority, evidence, recovery, reset and prevention.

Return:
`APPROVE / RETURN / DISAPPROVE / CHALLENGE-BLOCKED`

### <<START Project Template AEV Statement>>
{{Attach the AEV statement.}}
<<STOP Project Template AEV Statement>>

### <<START Project Template AEC Challenge>>
{{Attach the challenge packet/results.}}
<<STOP Project Template AEC Challenge>>

Notes:
- Do not mark a scenario successful because participants verbally agree.

<<STOP Prompt 15. Project Template Verification & Adversarial Challenge>>

### <<START Prompt 16. Project Template Closure & Baseline>>

[Designation: Lead Agent + Authorized Assurance Authority]

Resolve all material template findings and create the template closure record.

The resulting template must:
- be traceable to the verified proposal;
- preserve PBIM governance controls;
- define implementation boundaries;
- define Task Packet requirements;
- define evidence and verification;
- define operational readiness;
- retain residual risks.

Create the durable baseline reference.

### <<START Project Template Assurance Package>>
{{Attach AEA, AEV, AEC, decisions and evidence.}}
<<STOP Project Template Assurance Package>>

Notes:
- No unresolved material blocker may be hidden in an appendix.

<<STOP Prompt 16. Project Template Closure & Baseline>>

---

# 15. `[PBI-07-0004.00.07]` — PROJECT CONFIGURATION & GOVERNANCE INITIALIZATION

## Purpose

Configure the non-production project environment and governance records required to operate the approved template.

This section plans/configures controls. It does not authorize production implementation.

## Required controls

- project identity;
- authority register;
- permission matrix;
- identifier registry;
- canonical source manifest;
- repository/document structure;
- protected governance resources;
- Task Packet mechanism;
- evidence register;
- decision ledger;
- issue/risk registers;
- CI/quality/security controls where applicable;
- backup/recovery arrangements;
- logging/audit trail;
- emergency delegation if permitted.

### <<START Prompt 17. Project Configuration & Governance Initialization>>

[Designation: Lead Agent]

Using only the approved PBIM, proposal and template, produce the configuration plan and governance initialization package.

For every configuration item identify:
- purpose;
- owner;
- authority;
- required permission;
- intended state;
- verification method;
- evidence;
- rollback/recovery;
- stop condition.

Do not apply changes merely because the prompt requests planning.

### <<START Approved Project Template>>
{{Attach the approved template.}}
<<STOP Approved Project Template>>

### <<START Governance and Configuration References>>
{{Attach repository, environment, organizational policy and configuration evidence.}}
<<STOP Governance and Configuration References>>

Notes:
- A configuration action requiring authority not present in the register is blocked.
- Secrets MUST NOT be placed in PBIM prompts or ordinary evidence artifacts.

<<STOP Prompt 17. Project Configuration & Governance Initialization>>

### <<START Prompt 18. Configuration Verification>>

[Designation: Verification/Operations Collaborating Agents]

Verify the configured governance environment against the approved configuration plan.

Test:
- identity;
- permissions;
- identifier allocation;
- protected resources;
- source manifests;
- Task Packet controls;
- evidence capture;
- audit logging;
- backup/recovery;
- CI/security controls where applicable.

Return:
`READY / READY-WITH-CONDITIONS / RETURN / BLOCKED`

### <<START Configuration Evidence>>
{{Attach configuration evidence, test results and immutable references.}}
<<STOP Configuration Evidence>>

Notes:
- Do not treat intended configuration as actual configuration.

<<STOP Prompt 18. Configuration Verification>>

---

# 16. `[PBI-08-0004.00.08]` — PROJECT SIMULATION & READINESS REVIEW

## Purpose

Conduct a controlled dry-run/tabletop exercise without production changes.

## Required scenario classes

At minimum, where applicable:
- normal work dispatch;
- requirement change;
- scope expansion;
- failed verification;
- security/privacy event;
- dependency failure;
- evidence loss;
- emergency action;
- unavailable authority;
- rollback/recovery;
- release/no-release decision.

### <<START Prompt 19. Project Simulation & Readiness Exercise>>

[Designation: Lead Agent + Collaborating Assurance Agents]

Conduct a controlled dry-run of the approved project operating model. Do not implement production changes.

For each scenario:
1. invoke the stated workflow;
2. identify responsible authority;
3. identify required evidence;
4. identify expected state;
5. identify stop/resume behavior;
6. identify bypass opportunities;
7. identify missing mechanisms;
8. classify the weakness;
9. recommend the smallest effective correction.

Classify failures as DOCUMENTATION, PROCESS, AUTHORITY, TECHNICAL, SECURITY, PRIVACY, REGULATORY, RESOURCE, EVIDENCE or OPERATIONAL.

Conclude:
`READY / READY-WITH-CONDITIONS / RETURN / BLOCK / RESET`

### <<START Approved Project Operating Template>>
{{Attach the exact template being simulated.}}
<<STOP Approved Project Operating Template>>

### <<START Simulation Scenarios>>
{{Attach mandatory and project-specific scenarios.}}
<<STOP Simulation Scenarios>>

Notes:
- A documented control is not assumed to work.
- Every failure requires an owner and disposition.

<<STOP Prompt 19. Project Simulation & Readiness Exercise>>

### <<START Prompt 20. Readiness Review>>

[Designation: Collaborating Agents + H0]

Review the simulation evidence and determine whether the project is ready to transition to charter development.

Verify:
- material blockers are closed;
- governance controls are instantiated to required maturity;
- expected timing is plausible;
- risks are owned;
- dependencies are understood;
- charter inputs are complete;
- residual risks are accepted by the correct authority.

Return:
`READY-FOR-CHARTER / RETURN / BLOCKED`

### <<START Simulation Results>>
{{Attach complete simulation results and corrective actions.}}
<<STOP Simulation Results>>

### <<START Readiness Evidence>>
{{Attach control-state, risk, dependency and evidence registers.}}
<<STOP Readiness Evidence>>

Notes:
- H0 approval is not replaced by agent consensus.

<<STOP Prompt 20. Readiness Review>>

---

# 17. `[PBI-09-0004.00.09]` — PBIM ACTIVATION & CHARTER READINESS

## Purpose

Assemble the final pre-charter package and obtain human authorization to cross the PBIM boundary into formal Charter development.

## Required outputs

- activation/readiness record;
- final PBIM baseline reference;
- verified project proposal;
- verified project template;
- configured governance evidence;
- simulation/readiness evidence;
- residual-risk disposition;
- charter input package;
- transition decision.

### <<START Prompt 21. PBIM Activation & Charter Readiness>>

[Designation: Lead Agent]

Assemble the final PBIM transition package.

Confirm:
- PBIM baseline identity;
- project identity;
- authority register;
- risk profile;
- proposal baseline;
- template baseline;
- configuration state;
- simulation results;
- material findings;
- residual risks;
- expected project duration;
- expected project start date;
- expected project end date;
- charter inputs.

Do not activate if any mandatory item is UNCONFIRMED.

The output must explicitly distinguish:
`DESIGNED / ENFORCEABLE / ENFORCED / INDEPENDENTLY VERIFIED`

Recommend:
`READY-FOR-H0-AUTHORIZATION / RETURN / BLOCKED`

### <<START PBIM Final Baseline>>
{{Attach final controlled PBIM baseline.}}
<<STOP PBIM Final Baseline>>

### <<START Project Proposal Baseline>>
{{Attach verified proposal.}}
<<STOP Project Proposal Baseline>>

### <<START Project Template Baseline>>
{{Attach verified project template.}}
<<STOP Project Template Baseline>>

### <<START Configuration & Simulation Evidence>>
{{Attach final readiness evidence.}}
<<STOP Configuration & Simulation Evidence>>

Notes:
- This prompt does not itself grant activation authority.
- H0 must make the final project-level authorization decision.

<<STOP Prompt 21. PBIM Activation & Charter Readiness>>

### <<START Prompt 22. Human Authorization Decision>>

[Designation: Human Project Authority H0]

Review the complete PBIM transition package and determine whether the project may cross the PBIM boundary into Charter development.

Confirm:
1. project identity;
2. authority;
3. risk profile;
4. proposal baseline;
5. template baseline;
6. configuration/readiness evidence;
7. residual risks;
8. expected timing;
9. charter inputs;
10. legal/policy conditions.

Decision:
`AUTHORIZE-CHARTER-TRANSITION / RETURN-FOR-REMEDIATION / BLOCK`

Record rationale, conditions, effective revision and authority.

### <<START PBIM Transition Package>>
{{Attach the complete final package.}}
<<STOP PBIM Transition Package>>

Notes:
- Agent consensus cannot substitute for H0 authorization.
- Authorization to develop a Charter is not production authorization.

<<STOP Prompt 22. Human Authorization Decision>>

---

# 18. PBIM TERMINAL INTEGRATION BOUNDARY

## `[GOV-01-0004.01] — INITIATE PROJECT OR PHASE / DEVELOP PROJECT CHARTER`

Upon successful H0 authorization, PBIM ends and the project Charter process begins.

The Charter becomes the formal project authorization boundary.

PBIM MUST NOT create additional project-execution identifiers beyond this boundary.

---

# 19. PROMPT ENGINEERING CONTRACT

Every PBIM prompt MUST:

1. Start with `<<START Prompt N. {{Prompt Label}}>>`.
2. Declare `[Designation: Lead Agent / Collaborating Agents / Human Authority]`.
3. State a clear objective.
4. Identify the expected reasoning method.
5. State constraints and non-authorizations.
6. Attach resources using named START/STOP blocks.
7. Place notes below the resources.
8. Require evidence classification.
9. Require explicit treatment of inaccessible evidence.
10. Require structured outputs.
11. Define stop conditions.
12. Define a bounded decision set.
13. Avoid granting authority through prompt text.
14. Preserve dissent.
15. End with `<<STOP Prompt N. {{Prompt Label}}>>`.

## 19.1 Resource block contract

Resource sections use:

`<<START {{Resource Label}}>>`

`{{resource links or placeholders}}`

`<<STOP {{Resource Label}}>>`

Notes follow the resource block and precede the prompt's final STOP marker.

## 19.2 Prompt decision vocabulary

Preferred bounded decisions:

- `APPROVE`
- `APPROVE-WITH-OBSERVATIONS`
- `RETURN`
- `DISAPPROVE`
- `BLOCKED`
- `CHALLENGE-BLOCKED`
- `READY`
- `READY-WITH-CONDITIONS`
- `READY-FOR-CHARTER`
- `RESET`

Project-specific governance may add states, but must define them explicitly.

---

# 20. STOP, ESCALATION, RESET AND EMERGENCY RULES

Progress MUST stop when:
- protected controls are bypassed;
- material evidence is missing;
- authority is unresolved;
- required independence fails;
- an identifier conflict exists;
- a gate cannot be demonstrated;
- a material finding remains unresolved;
- a participant exceeds authority;
- a security/privacy boundary is materially compromised;
- a load-bearing assumption fails.

A stop cannot be self-cleared by the executor.

Emergency authority, if permitted by the governing organization, must have:
- explicit scope;
- authorized actor;
- time limit/TTL where appropriate;
- evidence requirement;
- post-event reconciliation;
- rollback/recovery expectation.

Emergency action does not permanently weaken the PBIM baseline.

---

# 21. CHANGE CONTROL

Changes are classified by materiality.

A change affecting:
- constitutional authority;
- protected governance controls;
- evidence integrity;
- assurance gates;
- project scope;
- material architecture;
- security/privacy boundaries;
- charter authority

requires routing through the applicable authority and, where material, a fresh assurance cycle.

Approved changes receive:
- new revision;
- durable reference;
- decision record;
- affected-artifact mapping;
- updated risk/evidence state.

---

# 22. IMPLEMENTATION AND TASK PACKET BOUNDARY

PBIM does not authorize implementation.

After Charter transition, implementation work SHOULD be dispatched through controlled Task Packets.

A Task Packet should contain:
- objective;
- requirements;
- direct scope;
- generated-file scope;
- dependency scope;
- configuration/schema/infrastructure scope;
- external effects;
- tests;
- evidence;
- executor;
- approval authority;
- verification authority;
- machine-readable scope manifest;
- supersession state;
- rollback/recovery expectations.

No Task Packet can authorize work outside its declared authority.

---

# 23. MODERN ENGINEERING AND MANAGEMENT ALIGNMENT

This PBIM is designed to be compatible with modern project and engineering practice without claiming conformance merely by reference.

Relevant reference frameworks include:
- PMI PMBOK® Guide — Eighth Edition;
- ISO 21502:2020, with awareness that Edition 2 is under development;
- NIST SP 800-218 SSDF 1.1;
- the NIST SSDF 1.2 public draft where explicitly useful and clearly labeled as draft;
- applicable information-security, privacy, safety, procurement and sector-specific requirements;
- organizational engineering standards and policies.

The PBIM adopts principles of:
- tailoring;
- value/outcome orientation;
- accountable governance;
- risk-proportionate assurance;
- secure development;
- evidence-based decision making;
- traceability;
- operational readiness;
- continuous learning;
- explicit human authority.

It does not claim certification or compliance with any framework.

---

# 24. HISTORICAL CONSOLIDATION AND MERGED CONCEPTS

This revision deliberately merges repeated concepts found across the supplied PBIM lineage.

## 24.1 Merged Concept A — Evidence, Provenance, Integrity and Durable References

Earlier versions repeatedly described:
- evidence classification;
- evidence registers;
- immutable/durable references;
- canonical sources;
- hashes/integrity anchors;
- provenance;
- supersession.

These are now one integrated `Evidence, Decision, Baseline and Control-State Model` rather than separate overlapping controls.

## 24.2 Merged Concept B — Identifier Architecture and Registry Control

Earlier versions separately described:
- project identifiers;
- section identifiers;
- process mappings;
- prompt identifiers;
- artifact identifiers;
- identifier registries;
- decimal insertion;
- allocation and conflict rules.

These are now one canonical Identifier/Registry Architecture with distinct identity classes and one allocation lifecycle.

## 24.3 Merged Concept C — Repeated AEA/AEV/AEC/AECC Cycles

Earlier versions repeated essentially the same assurance cycle for:
- PBIM;
- Project Proposal;
- Project Template.

The common assurance mechanics are now centralized in Section 6 and reused by Sections 10, 12 and 14. Section-specific prompts retain the domain questions without duplicating the governance protocol.

## 24.4 Merged Concept D — Repeated Lead/Collaborating Prompt Skeletons

Several versions repeated almost identical prompts containing:
- role;
- objective;
- context;
- constraints;
- method;
- evidence;
- output;
- stop conditions;
- decision.

The new Prompt Engineering Contract centralizes those common mechanics while every section prompt adds its own substantive instructions.

## 24.5 Merged Concept E — Stop, Reset, Emergency and Failure Handling

Earlier documents distributed stop conditions, reset rules, emergency controls and recovery logic across governance, AEC, configuration and simulation sections.

They are now consolidated into one cross-cutting control model, with section prompts referencing the model and adding local triggers.

---

# 25. IDENTIFIER MIGRATION MAP

| Historical Pattern | Generic v3.00.00 Pattern |
|---|---|
| `0004.01` PBIM Document Creation | `PBI-01-0004.00.01` |
| `0004.02` PBIM Architecture/Development | `PBI-02-0004.00.02` |
| `0004.03` Project Proposal Definition | `PBI-03-0004.00.03` |
| `0004.04` Project Proposal Development | `PBI-04-0004.00.04` |
| `0004.05` Project Template Generation/Creation | `PBI-05-0004.00.05` |
| `0004.06` Project Template Development | `PBI-06-0004.00.06` |
| `0004.07` Project Framework/Configuration Initialization | `PBI-07-0004.00.07` |
| `0004.08` Project Simulation/Readiness | `PBI-08-0004.00.08` |
| `0004.09` PBIM Implementation/Activation/Charter Readiness | `PBI-09-0004.00.09` |
| `0004.1` / `GOV-01-0004.1` | `GOV-01-0004.01` |

The historical identifiers should remain resolvable through an alias table when migrating an existing project.

---

# 26. EXPECTED PROJECT TIMING TEMPLATE

| Field | Value | Evidence/Method | Confidence |
|---|---|---|---|
| EXPECTED-PROJECT-DURATION | `[value/TBD]` | `[basis]` | `[HIGH/MEDIUM/LOW]` |
| EXPECTED-PROJECT-START-DATE | `[date/TBD]` | `[basis]` | `[HIGH/MEDIUM/LOW]` |
| EXPECTED-PROJECT-END-DATE | `[date/TBD]` | `[basis]` | `[HIGH/MEDIUM/LOW]` |

Rules:
1. These are expected values only.
2. They are not authorization.
3. They may change after proposal, template or readiness review.
4. Material changes must be recorded.
5. Unknown timing is preferable to fabricated precision.

---

# 27. PRE-CHARTER COMPLETION CHECKLIST

Before Charter transition, confirm:

- [ ] Project identity is resolved.
- [ ] Human authority is recorded.
- [ ] Constitutional authority requirement is resolved.
- [ ] Risk profile is selected.
- [ ] Identifier registry is initialized.
- [ ] Canonical source manifest exists.
- [ ] Evidence model is established.
- [ ] PBIM assurance cycle is complete.
- [ ] Project proposal is baselined.
- [ ] Project template is baselined.
- [ ] Governance/configuration controls are initialized.
- [ ] Task Packet model exists.
- [ ] Security/privacy boundaries are identified where applicable.
- [ ] Simulation is complete.
- [ ] Material blockers are closed or formally dispositioned.
- [ ] Residual risks have owners and authorities.
- [ ] Expected project duration is recorded.
- [ ] Expected project start date is recorded.
- [ ] Expected project end date is recorded.
- [ ] Charter inputs are complete.
- [ ] H0 authorizes transition.

---

# 28. FINAL PBIM STATUS

**PBIM State:** `CONTROLLED GENERIC CANDIDATE`

**Pre-charter section range:**

`PBI-01-0004.00.01` through `PBI-09-0004.00.09`

**Terminal PBIM integration anchor:**

`[BASE-ID]-[PROJECT-ID][GOV-01-0004.01] — Initiate Project or Phase / Develop Project Charter`

**Implementation authorization:** NOT GRANTED

**Production authorization:** NOT GRANTED

**Control maturity represented by this document:** DESIGNED

The document must undergo project-specific review and the applicable AEA → AEV → AEC → AECC cycle before it becomes an approved project operating baseline.

---

# APPENDIX A — REQUIRED RESOURCE-MARKER EXAMPLE

### <<START Prompt 23. Generic Resource Attachment Example>>

[Designation: Lead Agent]

Use the following resources as evidence. Do not infer content from a resource that cannot be accessed.

### <<START Primary Controlled Resource>>
{{Link to the primary controlled artifact.}}
<<STOP Primary Controlled Resource>>

### <<START Supporting Evidence>>
{{Links to supporting evidence.}}
<<STOP Supporting Evidence>>

Notes:
- Supporting resources do not automatically override the primary controlled resource.
- Record inaccessible resources as evidence gaps.

Produce a structured assessment and a bounded decision.

<<STOP Prompt 23. Generic Resource Attachment Example>>

---

# APPENDIX B — MINIMUM AGENT RESPONSE CONTRACT

Every substantive agent response SHOULD contain:

1. `DECISION`
2. `SCOPE`
3. `EVIDENCE`
4. `FINDINGS`
5. `ASSUMPTIONS`
6. `UNKNOWN/INACCESSIBLE MATERIAL`
7. `IMPACT/MATERIALITY`
8. `RECOMMENDATIONS`
9. `TRACEABILITY`
10. `STOP CONDITIONS TRIGGERED`
11. `DISSENT / INDEPENDENCE DISCLOSURE`
12. `NEXT GATE`

---

# APPENDIX C — GOVERNANCE STATE MACHINE

```text
DRAFT
  ↓
BASELINED
  ↓
AEA
  ↓
AEV
  ├── RETURN → REVISION → AEV
  └── APPROVED
          ↓
         AEC
          ├── BLOCKER → REMEDIATION → AEV/AEC
          ├── LOAD-BEARING FAILURE → ARCHITECTURAL RESET → AEA
          └── CLOSED
                ↓
               AECC
                ↓
          CONTROLLED BASELINE
                ↓
        PROJECT-SPECIFIC USE
                ↓
       H0 CHARTER TRANSITION
```

No state transition is valid merely because an agent writes the transition into a document.

---

# APPENDIX D — DESIGN PRINCIPLES

1. Human authority remains external to the PBIM.
2. Technical capability never equals governance authority.
3. Evidence outranks repetition and confidence.
4. Identifiers are governed data, not decorative labels.
5. Prompts are controlled interfaces, not authority grants.
6. Assurance challenges assumptions, not just wording.
7. Material dissent is preserved.
8. Tailoring reduces ceremony, not protected controls.
9. A design claim is not an implementation claim.
10. A successful simulation is evidence of readiness analysis, not proof of production behavior.
11. Expected dates are estimates, not commitments.
12. PBIM ends at Charter transition.
