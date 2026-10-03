# PROJECT BASE INTEGRATION MANAGER \[PBIM]

**DOCUMENT REVISION:** R2.0 — Consolidated Post-AEV/AEC Implementation Candidate
**TIMESTAMP:** 2026-10-03
**DOCUMENT CLASS:** Reusable Project Management and Software Engineering Operating Model
**STATUS:** MANUAL-UPDATE REQUIRED BEFORE NEXT AEA QUERY
**ARCHITECTURAL STATUS:** CONSOLIDATED DESIGN CANDIDATE
**IMPLEMENTATION AUTHORIZATION:** NOT GRANTED
**PRODUCTION AUTHORIZATION:** NOT GRANTED
**AECC CLOSURE:** PENDING
**FRESH R2.0 AEC:** REQUIRED BEFORE IMPLEMENTATION AUTHORIZATION

\---

# \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[IDENTIFIER]

## PROJECT BASE INTEGRATION MANAGER \[PBIM]

### Project Management Office

### Independent Assignments \& Project Development National Department

### \[IAPD National]

### Koware Group

\---

# 1\. PBIM PURPOSE

The **Project Base Integration Manager \[PBIM]** is the reusable governance, assurance, project-initialization and software-engineering operating model used to establish a controlled project environment before substantive project execution begins.

PBIM is intended to provide a repeatable foundation from which a project-specific:

1. project identity;
2. project governance structure;
3. project-management workflow;
4. project repository structure;
5. agent ecosystem;
6. authority model;
7. requirements model;
8. architectural-review process;
9. implementation controls;
10. verification controls;
11. challenge controls;
12. evidence system;
13. risk-management system;
14. change-management system;
15. operational-readiness system; and
16. project-closure system

can be established.

PBIM is a **reusable operating model**, not the project itself.

The concrete Buzzjuice Payment Gateway Bridge Development \[BZJ-PGBD] is treated as a reference implementation of the generic model.

\---

# 2\. GOVERNING PRINCIPLE

PBIM shall operate according to:

**GOVERNANCE → ASSURANCE → EXECUTION**

### Governance

Governance establishes:

* authority;
* constraints;
* scope;
* permissions;
* requirements;
* decision rights;
* approval conditions;
* risk thresholds;
* protected controls; and
* release authority.

### Assurance

Assurance:

* analyses;
* verifies;
* challenges;
* tests;
* audits;
* records evidence;
* detects drift;
* evaluates independence; and
* determines whether evidence supports advancement.

### Execution

Execution:

* performs authorized work;
* implements approved changes;
* generates artifacts;
* performs tests;
* records implementation evidence; and
* operates within approved Task Packet scope.

No technical capability automatically creates governance authority.

No consensus automatically creates governance authority.

No agent title automatically establishes independence.

No implementation artifact automatically constitutes verification evidence.

\---

# 3\. ARCHITECTURAL STATE MODEL

PBIM shall distinguish the following control-maturity states:

**DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED**

A documented control is not automatically an implemented control.

A technically possible control is not automatically an enforced control.

An enforced control is not automatically independently verified.

The state of every material governance control shall therefore be capable of being identified.

\---

# 4\. CONSTITUTIONAL AUTHORITY

## \[BASE]-\[PROJECT]-0004.01.01 — Establish Constitutional Authority

Every PBIM project shall identify an external:

**CA — Constitutional Authority**

CA is the constitutional root of trust for PBIM.

CA exists outside the ordinary PBIM execution structure.

The authority boundary shall be:

**CA → PBIM → H0 → H1 → H2**

Where:

* **CA** = Constitutional Authority;
* **H0** = Human Project Authority;
* **H1** = Human Technical/Assurance Authority;
* **H2** = Execution Worker, including AI agents where applicable.

PBIM shall not create an authority above CA.

PBIM shall not authorize its own constitutional amendment.

## \[BASE]-\[PROJECT]-0004.01.02 — Instantiate CA

The project initialization shall record:

* CA identity;
* CA organizational basis;
* CA authority scope;
* CA decision rights;
* CA succession mechanism, if one exists;
* CA review requirements;
* CA evidence requirements.

A merely conceptual CA is insufficient for a project requiring constitutional decisions.

## \[BASE]-\[PROJECT]-0004.01.03 — CA Unavailability

If CA becomes unavailable and no formally authorized succession mechanism exists:

**CA UNAVAILABLE → CONSTITUTIONAL-BLOCKED**

No H0, H1, H2, agent, administrator or emergency delegate may self-assume CA authority.

Any succession mechanism must be:

* pre-authorized;
* scope-bounded;
* time-bounded;
* recorded; and
* independently verifiable.

## \[BASE]-\[PROJECT]-0004.01.04 — CA Compromise

A compromised CA shall be recorded as:

`CA-TRUST-BOUNDARY-COMPROMISED`

PBIM shall not falsely claim that a project-level process can restore constitutional trust after compromise of its constitutional root.

\---

# 5\. PROJECT IDENTITY AND IDENTIFIER ARCHITECTURE

## \[BASE]-\[PROJECT]-0004.01.05 — Generic Project Identifier

The reusable identifier model shall be:

`\[BASE]-\[PROJECT]-\[IDENTIFIER]`

Example:

`BZJ-PGBD-0004.01`

Where:

* `BZJ` = project base;
* `PGBD` = project abbreviation;
* `0004.01` = project-specific lifecycle/section identifier.

The concrete project identifier shall not be treated as the generic PBIM architecture.

## \[BASE]-\[PROJECT]-0004.01.06 — Identifier Separation

PBIM shall not overload a single identifier with unrelated meanings.

The following shall remain conceptually distinct:

* project identifier;
* lifecycle stage;
* process classification;
* task/work-item identifier;
* document identifier;
* evidence identifier;
* decision identifier;
* release identifier.

A project may correlate these identifiers, but correlation shall not collapse their semantic purposes.

## \[BASE]-\[PROJECT]-0004.01.07 — Identifier Registry

The canonical Identifier Registry shall use:

**REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM**

Registry updates shall be serialized.

Every authoritative registry revision shall preserve:

* revision ID;
* parent revision;
* content hash;
* authorizing authority;
* timestamp;
* verifier;
* verification result.

## \[BASE]-\[PROJECT]-0004.01.08 — Registry Failure

If the canonical registry becomes unavailable or corrupted:

`REGISTRY-BLOCKED`

The recovery process shall:

1. identify the last trusted revision;
2. reconcile pending reservations;
3. independently verify integrity;
4. restore canonical state;
5. independently approve reactivation.

A stale local registry shall never become authoritative merely because the canonical registry is unavailable.

No emergency identifier creation shall bypass the registry.

\---

# 6\. AUTHORITY, PERMISSIONS AND TECHNICAL CAPABILITY

## \[BASE]-\[PROJECT]-0004.01.09 — Privilege Boundary

PBIM shall maintain a formal boundary between:

**GOVERNANCE AUTHORITY**

and

**TECHNICAL CAPABILITY**

Technical capability may include:

* repository administration;
* organization ownership;
* CI/CD administration;
* infrastructure administration;
* database administration;
* cloud administration;
* deployment credentials;
* automation credentials.

Technical capability does not independently confer governance authority.

## \[BASE]-\[PROJECT]-0004.01.10 — Authority–Permission Matrix

The canonical Authority–Permission Matrix shall identify:

* principal;
* authority role;
* technical identity;
* action;
* scope;
* environment;
* approval;
* verification;
* expiration.

## \[BASE]-\[PROJECT]-0004.01.11 — Privileged Account Mapping

Every privileged account with governance-relevant capability shall map to:

* named identity;
* authority role;
* technical capability;
* permitted scope;
* governance scope;
* expiration/review date.

Unexpected privilege shall trigger:

`AUTHORITY-PERMISSION-DRIFT`

and the applicable governance stop.

## \[BASE]-\[PROJECT]-0004.01.12 — Matrix Serialization

Authoritative Authority–Permission Matrix changes shall use:

**REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM**

Conflicting concurrent changes shall produce:

`AUTHORITY-MATRIX-CONFLICT`

No permission dependent on a conflicting state shall be treated as fully authorized until reconciliation.

\---

# 7\. GOVERNANCE REPOSITORY AND PROTECTED RESOURCES

## \[BASE]-\[PROJECT]-0004.01.13 — Protected Governance Resources

The following shall be treated as protected governance resources:

* PBIM baseline;
* Authority Register;
* Authority–Permission Matrix;
* Identifier Registry;
* protected-control definitions;
* AEV records;
* AEC records;
* AECC records;
* Decision Ledger;
* authoritative ADRs;
* Task Packet state;
* evidence-integrity records;
* release authorization records.

Where technically possible, repository and infrastructure controls shall prevent unilateral administrative modification.

## \[BASE]-\[PROJECT]-0004.01.14 — Governance Repository

The project shall identify:

* canonical governance repository;
* authoritative branch;
* protected files;
* branch protection;
* review requirements;
* access model;
* recovery mechanism;
* integrity mechanism.

The symbolic branch name alone shall not establish authoritative identity.

\---

# 8\. AGENT ECOSYSTEM

## \[BASE]-\[PROJECT]-0004.01.15 — Agent Roster

The default project agent ecosystem may contain:

**Lead Agent**

* ChatGPT Codex

**Collaborating Agents**

* Kilo Code
* Google Jules
* GitHub Copilot

The roster shall remain configurable.

Additional agents may be designated where useful.

An agent's vendor/model identity shall not itself determine authority.

Authority derives from the assigned project role and Authority–Permission Matrix.

## \[BASE]-\[PROJECT]-0004.01.16 — Functional Agent Roles

PBIM shall distinguish, where applicable:

1. Human Project Sponsor / Product Authority
2. Human Technical Authority
3. Lead/Coordination Agent
4. Architecture/Analysis Agent
5. Verification Agent
6. Implementation Agent
7. Challenge Agent
8. Test Agent
9. Documentation Agent
10. Release/Operations Agent

One agent may perform multiple roles only where the project risk profile permits it.

A single role shall not approve its own implementation where independent verification is required.

## \[BASE]-\[PROJECT]-0004.01.17 — Role Gap

If a required role cannot be staffed:

`ROLE-GAP`

shall be recorded in the relevant Task Packet or governance record.

The role gap shall be escalated to the appropriate human authority.

The project shall not silently pretend that an unavailable role has been fulfilled.

\---

# 9\. PBIM INITIALIZATION

## \[BASE]-\[PROJECT]-0004.01.18 — Project Base Integration Initialization

PBIM initialization shall establish only the minimum information required to safely bootstrap the project.

The initialization shall establish:

* project identity;
* project abbreviation;
* project repository;
* branch strategy;
* governance repository;
* source-of-truth locations;
* agent roster;
* agent roles;
* human authorities;
* required registers;
* baseline location;
* approval model;
* risk profile;
* communication model;
* evidence model;
* stop conditions;
* initial project state.

PBIM initialization shall not attempt to pre-approve every downstream project activity.

\---

# 10\. PROJECT BASE INTEGRATION INITIALIZATION WORKFLOW

## \[BASE]-\[PROJECT]-0004.01.19 — Initialization Sequence

The initialization sequence shall be:

1. Identify project.
2. Establish CA.
3. Establish H0/H1/H2 roles.
4. Establish project repository.
5. Establish governance repository.
6. Establish identifier registry.
7. Establish Authority Register.
8. Establish Authority–Permission Matrix.
9. Establish agent roster.
10. Establish source-of-truth locations.
11. Establish evidence requirements.
12. Establish risk profile.
13. Establish requirement materiality model.
14. Establish baseline state.
15. establish protected controls.
16. establish Task Packet model.
17. establish stop conditions.
18. establish verification model.
19. establish challenge model.
20. record initialization evidence.
21. perform initialization review.
22. establish the first controlled PBIM baseline.

\---

# 11\. AEA — ARCHITECTURAL ENGINEERING ANALYSIS

## \[BASE]-\[PROJECT]-0004.01.20 — AEA Query Generation

The AEA Query shall be prepared by the Lead Agent after the PBIM has been manually reviewed and updated.

The AEA Query shall identify:

* problem statement;
* current architecture;
* desired outcome;
* constraints;
* requirements;
* existing decisions;
* dependencies;
* risks;
* unknowns;
* explicit questions;
* alternatives;
* trade-offs;
* failure scenarios;
* security considerations;
* operational considerations;
* maintainability;
* scalability;
* cost/complexity;
* migration implications;
* verification criteria;
* recommended architecture;
* dissenting views;
* unresolved questions;
* evidence references.

## \[BASE]-\[PROJECT]-0004.01.21 — Independent AEA

The AEA Query shall be independently reviewed by designated collaborating agents.

Each collaborating agent shall distinguish:

* VERIFIED FACT;
* INFERENCE;
* ASSUMPTION;
* PROPOSAL;
* RISK;
* UNKNOWN.

No substantive architectural claim shall be presented as fact without appropriate evidence.

\---

# 12\. AEV — ARCHITECTURAL ENGINEERING VERIFICATION

## \[BASE]-\[PROJECT]-0004.01.22 — Controlled AEV Candidate

The Lead Agent shall consolidate AEA responses into a controlled AEV baseline candidate.

The candidate shall:

* preserve material dissent;
* reference source evidence;
* record contradictions;
* distinguish verified design from proposed design;
* identify unresolved issues;
* maintain revision history;
* map findings to proposed controls.

## \[BASE]-\[PROJECT]-0004.01.23 — AEV Approval

AEV approval shall be evidence-based and role-authorized.

Consensus alone shall not create authority.

A reviewer shall not be treated as independent merely because the reviewer has been assigned a different title.

For High-Assurance AEC work, independence shall be evaluated across:

* I1 — Organizational Independence;
* I2 — Evidence Independence;
* I3 — Technical Independence;
* I4 — Governance Independence.

Where required independence cannot be established:

`CHALLENGE-INDEPENDENCE-FAILED`

or

`CHALLENGE-BLOCKED`

shall prevent the applicable High-Assurance challenge from proceeding.

\---

# 13\. AEC — ARCHITECTURAL ENGINEERING CHALLENGE

## \[BASE]-\[PROJECT]-0004.01.24 — Adversarial Challenge Gate

After the AEV architecture has received the required approval, an adversarial challenge shall attack the architecture rather than merely refine it.

The challenger shall attempt to demonstrate whether the architecture can:

* contradict itself;
* permit unauthorized authority;
* permit technical privilege to become hidden authority;
* permit silent bypass;
* lose governance state;
* accept invalid evidence;
* permit false independence;
* permit registry corruption;
* permit Task Packet scope escape;
* bypass stop controls;
* misuse emergency authority;
* conceal architectural defects;
* defeat baseline integrity;
* defeat operational-readiness controls.

## \[BASE]-\[PROJECT]-0004.01.25 — Challenge Disposition

AEC results shall classify findings as:

* blocking;
* material;
* minor;
* contained weakness;
* false positive;
* observation.

Every material finding shall have:

* finding ID;
* evidence;
* impact;
* affected control;
* resolution;
* verification;
* residual risk.

\---

# 14\. AECC — ARCHITECTURAL ENGINEERING CHALLENGE CLOSURE

## \[BASE]-\[PROJECT]-0004.01.26 — Challenge Closure

AEC shall not be considered closed merely because the Lead Agent believes the findings have been resolved.

Closure shall require:

1. findings register;
2. resolution mapping;
3. evidence;
4. independent review;
5. revised baseline;
6. residual-risk determination;
7. closure authority.

The sequence shall remain:

**AEV → AEC → AEC Results → AECC → Approved PBIM → Implementation Verification**

\---

# 15\. IMPLEMENTATION VERIFICATION

## \[BASE]-\[PROJECT]-0004.01.27 — Design/Implementation Boundary

PBIM shall explicitly distinguish:

**DESIGNED**

from

**ENFORCEABLE**

from

**ENFORCED**

from

**INDEPENDENTLY VERIFIED**

An architecture document shall never claim that a mechanism is operational merely because the mechanism is specified.

## \[BASE]-\[PROJECT]-0004.01.28 — Implementation Verification

Implementation Verification shall verify, where applicable:

* repository permissions;
* branch protection;
* workflow enforcement;
* Task Packet enforcement;
* Identifier Registry implementation;
* Authority–Permission Matrix implementation;
* integrity anchors;
* evidence storage;
* evidence retention;
* baseline-drift monitoring;
* stop enforcement;
* emergency delegation enforcement;
* operational-readiness controls.

\---

# 16\. TASK PACKETS

## \[BASE]-\[PROJECT]-0004.01.29 — Task Packet

Every material implementation task shall be represented by a controlled Task Packet.

A Task Packet shall define:

* objective;
* requirements;
* direct file scope;
* generated-file scope;
* dependency scope;
* configuration scope;
* build-artifact scope;
* schema scope;
* infrastructure scope;
* external-effect scope;
* risk profile;
* required tests;
* required evidence;
* authorized executor;
* approval authority;
* verification authority.

## \[BASE]-\[PROJECT]-0004.01.30 — Machine-Readable Scope Manifest

Each applicable Task Packet shall contain a machine-readable scope manifest.

The canonical enforcement mechanism shall be:

`.github/workflows/task-scope-check.yml`

The workflow shall compare the actual changeset with the approved Task Packet scope.

Out-of-scope change shall trigger:

**STOP → REPORT → NEW/SUPERSEDING TASK PACKET**

unless a formally authorized emergency mechanism applies.

\---

# 17\. EVIDENCE AND INTEGRITY

## \[BASE]-\[PROJECT]-0004.01.31 — Evidence Classification

Material evidence shall preserve:

* source artifact;
* integrity hash;
* original timestamp;
* author identity;
* verifier identity;
* verification result.

Raw evidence shall remain retrievable by authorized independent reviewers.

Evidence shall not be recreated from memory when the original artifact can be retained.

## \[BASE]-\[PROJECT]-0004.01.32 — Integrity Anchors

Material authoritative artifacts shall have integrity anchors providing:

1. immutability or equivalent protection;
2. out-of-band protection;
3. independent verifiability;
4. durability;
5. association with a specific revision.

The artifact and its integrity anchor shall not depend on the same compromised trust domain where independent protection is required.

## \[BASE]-\[PROJECT]-0004.01.33 — Evidence Retention

Material evidence shall include, where applicable:

* test logs;
* deployment logs;
* Task Packet approvals;
* registry changes;
* Authority–Permission changes;
* stop records;
* reset records;
* release evidence;
* operational-readiness evidence.

\---

# 18\. STOP, RESET AND EMERGENCY CONTROLS

## \[BASE]-\[PROJECT]-0004.01.34 — Governance Stop

A material governance stop shall affect execution authorization.

Where technically possible, a stop shall machine-enforce:

* merge blocking;
* deployment blocking;
* release blocking;
* new-task dispatch blocking;
* applicable verification advancement blocking.

An executor shall not self-resume a mandatory stop.

## \[BASE]-\[PROJECT]-0004.01.35 — Stop Record

A material stop record shall preserve:

* stop ID;
* initiator;
* subject;
* evidence;
* authority;
* timestamp;
* reviewer;
* resume authority;
* resolution state.

## \[BASE]-\[PROJECT]-0004.01.36 — Architectural Reset

Reset shall preserve:

* previous baseline;
* previous evidence;
* affected artifacts;
* reason for invalidation;
* authority decision;
* new baseline;
* traceability between old and new baseline.

Reset shall never erase challenged history.

## \[BASE]-\[PROJECT]-0004.01.37 — Reset Anti-Evasion

Reset shall not be used to evade:

* AEC findings;
* evidence;
* Task Packet violations;
* materiality review;
* operational-readiness requirements.

## \[BASE]-\[PROJECT]-0004.01.38 — Emergency Delegation

Emergency delegation shall be:

* scope-limited;
* action-limited;
* risk-limited;
* time-limited;
* logged;
* reviewable.

Where emergency delegation is granted because H0 is unavailable, the default maximum emergency delegation period shall be:

**72 HOURS**

unless the governing constitutional mechanism establishes a stricter limit.

At expiry, the project shall automatically return to a stopped state unless properly confirmed.

## \[BASE]-\[PROJECT]-0004.01.39 — Emergency Reconciliation

Emergency actions shall require post-event reconciliation covering:

* actions taken;
* authority used;
* evidence;
* affected artifacts;
* requirement changes;
* security effects;
* operational effects;
* rollback/recovery status;
* required AEV/AEC review.

\---

# 19\. RISK MANAGEMENT

## \[BASE]-\[PROJECT]-0004.01.40 — Risk Profiles

PBIM shall support risk-scaled operating profiles:

### LIGHT

For low-risk work where protected controls remain applicable but ceremony and evidence depth may be reduced.

### STANDARD

Default project profile.

### HIGH-ASSURANCE

For work involving elevated security, financial, operational, safety, infrastructure or other material consequences.

Risk scaling shall reduce unnecessary ceremony without weakening protected controls.

## \[BASE]-\[PROJECT]-0004.01.41 — Cumulative Risk

Risk shall be evaluated cumulatively across:

* tasks;
* related changes;
* dependencies;
* migrations;
* releases;
* concurrent workstreams.

Multiple individually low-risk changes shall not be used to conceal a materially high-risk aggregate change.

## \[BASE]-\[PROJECT]-0004.01.42 — Risk Classification Record

Material risk records shall contain:

* risk profile;
* rationale;
* affected scope;
* blast radius;
* reversibility;
* security impact;
* data impact;
* financial impact;
* operational impact;
* dependency impact;
* reviewer;
* approval.

The implementer shall not unilaterally downgrade a risk classification.

\---

# 20\. REQUIREMENT MATERIALITY

## \[BASE]-\[PROJECT]-0004.01.43 — Requirement Materiality Levels

Requirement changes shall be classified:

* Level 0;
* Level 1;
* Level 2;
* Level 3.

The exact thresholds shall be project-defined during initialization.

## \[BASE]-\[PROJECT]-0004.01.44 — Cumulative Materiality

PBIM shall evaluate cumulative effects across:

* common objective;
* common architecture;
* common release;
* dependency;
* shared blast radius;
* shared data;
* shared security boundary;
* aggregate operational effect.

Splitting one material architectural change into multiple smaller changes shall not defeat materiality review.

## \[BASE]-\[PROJECT]-0004.01.45 — Materiality Review Points

Cumulative materiality shall be reassessed at:

* charter;
* task dispatch;
* major implementation milestone;
* major requirement change;
* release;
* architectural reset;
* emergency reconciliation.

\---

# 21\. OPERATIONAL READINESS

## \[BASE]-\[PROJECT]-0004.01.46 — Release State

A release shall progress through:

**IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED**

Implementation verification alone does not authorize production release.

## \[BASE]-\[PROJECT]-0004.01.47 — Operational Readiness Checklist

The applicable release checklist shall evaluate:

* monitoring;
* alert ownership;
* rollback;
* tested recovery;
* backup;
* restore;
* data recovery;
* incident ownership;
* dependency readiness;
* security readiness;
* capacity;
* support;
* migration recovery;
* controlled rollout.

## \[BASE]-\[PROJECT]-0004.01.48 — Not Applicable

A readiness item marked `NOT APPLICABLE` shall contain:

* justification;
* scope basis;
* risk rationale;
* approving authority;
* reviewer.

`NOT APPLICABLE` shall not be used to remove inconvenient controls.

## \[BASE]-\[PROJECT]-0004.01.49 — Release Blocking Conditions

Release shall be blocked where applicable if:

* rollback is untested;
* alert ownership is undefined;
* data recovery is unverified;
* dependency recovery is untested;
* required security readiness is unverified;
* operational ownership is absent.

High-Assurance releases require independent operational-readiness verification.

\---

# 22\. BASELINE DRIFT

## \[BASE]-\[PROJECT]-0004.01.50 — Baseline Drift

PBIM shall treat divergence from the approved baseline as:

`BASELINE-DRIFT`

Drift shall include potential divergence involving:

* PBIM baseline;
* AGENTS.md;
* workflows;
* Authority–Permission Matrix;
* Identifier Registry;
* protected controls;
* Task Packets;
* implementation;
* operational configuration.

## \[BASE]-\[PROJECT]-0004.01.51 — Drift Monitoring

An independent baseline-drift monitoring mechanism shall be established where technically feasible outside the ordinary execution path.

## \[BASE]-\[PROJECT]-0004.01.52 — Drift Record

A material drift record shall contain:

* source;
* affected artifact;
* authority owner;
* detection time;
* evidence;
* remediation;
* resolution;
* reviewer.

Baseline-drift correction requires appropriate human governance approval.

\---

# 23\. DURABLE ARTIFACT IDENTITY

## \[BASE]-\[PROJECT]-0004.01.53 — Durable References

Authoritative references shall survive:

* branch deletion;
* session expiration;
* agent workspace destruction;
* branch rewriting.

## \[BASE]-\[PROJECT]-0004.01.54 — Immutable Reference

Every authoritative durable reference shall contain:

* durable artifact identifier;
* immutable commit/object identifier;
* integrity hash where applicable.

Symbolic branch names alone shall not establish authoritative identity.

Example:

`pbi-baseline/v1.0.0#<immutable-commit-object-id>`

## \[BASE]-\[PROJECT]-0004.01.55 — Superseded Artifacts

Superseded artifacts shall be archived rather than silently overwritten.

The canonical current artifact must remain distinguishable from superseded artifacts.

\---

# 24\. GOVERNANCE SELF-AMENDMENT

## \[BASE]-\[PROJECT]-0004.01.56 — Constitutional Amendment

PBIM shall not authorize its own constitutional amendment.

Constitutional changes require CA-level authorization.

## \[BASE]-\[PROJECT]-0004.01.57 — Ordinary Governance Amendment

Ordinary PBIM changes shall remain distinguishable from constitutional changes.

A project-management change shall not be disguised as an ordinary change when it alters the constitutional authority model.

\---

# 25\. DOCUMENT AND DECISION CONTROL

## \[BASE]-\[PROJECT]-0004.01.58 — Controlled Document

Every controlled architecture document shall identify:

* document ID;
* revision;
* status;
* author;
* authority;
* date/time;
* parent revision;
* source evidence;
* change summary;
* verification status.

## \[BASE]-\[PROJECT]-0004.01.59 — Decision Ledger

Material architectural decisions shall be recorded in a Decision Ledger.

Each decision shall identify:

* decision ID;
* issue;
* alternatives;
* selected option;
* rationale;
* authority;
* evidence;
* dissent;
* date;
* affected artifacts;
* superseded decision, if any.

\---

# 26\. PROJECT LIFECYCLE AND 49-PROCESS ALIGNMENT

PBIM retains the 49-project-management-process alignment as a lifecycle reference.

However, the process number shall not be overloaded to serve simultaneously as project identity, document identity, task identity and architecture identity.

The lifecycle process identifiers shall remain compatible with the established sequence.

## Initiating

`\[BASE]-\[PROJECT]-0004.1` — Develop Project Charter
`\[BASE]-\[PROJECT]-0013.1` — Identify Stakeholders

## Planning

`\[BASE]-\[PROJECT]-1004.2` — Develop Project Management Plan
`\[BASE]-\[PROJECT]-1005.1` — Plan Scope Management
`\[BASE]-\[PROJECT]-1005.2` — Collect Requirements
`\[BASE]-\[PROJECT]-1005.3` — Define Scope
`\[BASE]-\[PROJECT]-1005.4` — Create WBS
`\[BASE]-\[PROJECT]-1006.1` — Plan Schedule Management
`\[BASE]-\[PROJECT]-1006.2` — Define Activities
`\[BASE]-\[PROJECT]-1006.3` — Sequence Activities
`\[BASE]-\[PROJECT]-2006.4` — Estimate Activity Durations
`\[BASE]-\[PROJECT]-2006.5` — Develop Schedule
`\[BASE]-\[PROJECT]-2007.1` — Plan Cost Management
`\[BASE]-\[PROJECT]-2007.2` — Estimate Costs
`\[BASE]-\[PROJECT]-2007.3` — Determine Budget
`\[BASE]-\[PROJECT]-2008.1` — Plan Quality Management
`\[BASE]-\[PROJECT]-2009.1` — Plan Resource Management
`\[BASE]-\[PROJECT]-2009.2` — Estimate Activity Resources
`\[BASE]-\[PROJECT]-2010.1` — Plan Communications Management
`\[BASE]-\[PROJECT]-2011.1` — Plan Risk Management
`\[BASE]-\[PROJECT]-3011.2` — Identify Risks
`\[BASE]-\[PROJECT]-3011.3` — Perform Qualitative Risk Analysis
`\[BASE]-\[PROJECT]-3011.4` — Perform Quantitative Risk Analysis
`\[BASE]-\[PROJECT]-3011.5` — Plan Risk Responses
`\[BASE]-\[PROJECT]-3012.1` — Plan Procurement Management
`\[BASE]-\[PROJECT]-3013.2` — Plan Stakeholder Engagement

## Executing

`\[BASE]-\[PROJECT]-4004.3` — Direct and Manage Project Work
`\[BASE]-\[PROJECT]-4004.4` — Manage Project Knowledge
`\[BASE]-\[PROJECT]-5008.2` — Manage Quality
`\[BASE]-\[PROJECT]-5009.3` — Acquire Resources
`\[BASE]-\[PROJECT]-6009.4` — Develop Team
`\[BASE]-\[PROJECT]-6009.5` — Manage Team
`\[BASE]-\[PROJECT]-6010.2` — Manage Communications
`\[BASE]-\[PROJECT]-6011.6` — Implement Risk Responses
`\[BASE]-\[PROJECT]-6012.2` — Conduct Procurements
`\[BASE]-\[PROJECT]-6013.3` — Manage Stakeholder Engagement

## Monitoring and Controlling

`\[BASE]-\[PROJECT]-7004.5` — Monitor and Control Project Work
`\[BASE]-\[PROJECT]-7004.6` — Perform Integrated Change Control
`\[BASE]-\[PROJECT]-7005.5` — Validate Scope
`\[BASE]-\[PROJECT]-7005.6` — Control Scope
`\[BASE]-\[PROJECT]-8006.6` — Control Schedule
`\[BASE]-\[PROJECT]-8007.4` — Control Costs
`\[BASE]-\[PROJECT]-8008.3` — Control Quality
`\[BASE]-\[PROJECT]-8009.6` — Control Resources
`\[BASE]-\[PROJECT]-8010.3` — Monitor Communications
`\[BASE]-\[PROJECT]-8011.7` — Monitor Risks
`\[BASE]-\[PROJECT]-8012.3` — Control Procurements
`\[BASE]-\[PROJECT]-8013.4` — Monitor Stakeholder Engagement

## Closing

`\[BASE]-\[PROJECT]-9004.7` — Close Project or Phase

PBIM-specific engineering controls may be inserted between these lifecycle positions using decimal suffixes.

Example:

`\[BASE]-\[PROJECT]-0005.0` — Initial Architecture Brief
`\[BASE]-\[PROJECT]-0005.01` — Architecture Evidence Register
`\[BASE]-\[PROJECT]-0005.02` — Architecture Dependency Review

The numeric sequence shall communicate lifecycle position without becoming the sole semantic identity of the artifact.

\---

# 27\. PBIM PRE-CHARTER ARCHITECTURAL INITIALIZATION

The following nine PBIM sections shall be completed before Project Charter Development.

## \[BASE]-\[PROJECT]-0004.01 — PBIM Development and Architectural Initialization

Purpose:

* establish PBIM;
* establish project identity;
* establish governance;
* establish agent ecosystem;
* establish AEA/AEV/AEC/AECC pipeline;
* establish evidence;
* establish implementation boundary;
* establish baseline.

Primary outputs:

* PBIM;
* AEA Query;
* AEA Reports;
* AEV Candidate;
* AEV Responses;
* AEC Challenge;
* AEC Results;
* AECC Closure.

## \[BASE]-\[PROJECT]-0004.02 — Initial Project Proposal / Template Generation Prompt

Purpose:

Generate the first project proposal and project-template-generation instruction set from the approved PBIM.

The prompt shall identify:

* project objectives;
* project constraints;
* project requirements;
* expected project outputs;
* project-management model;
* engineering model;
* template requirements;
* agent responsibilities;
* verification requirements.

## \[BASE]-\[PROJECT]-0004.03 — Architectural Engineering of Project Proposal

Purpose:

Apply AEA → AEV → AEC → AECC to the Project Proposal Prompt.

## \[BASE]-\[PROJECT]-0004.04 — Architectural Challenge of Project Proposal

Purpose:

Adversarially challenge the Project Proposal architecture before template production.

## \[BASE]-\[PROJECT]-0004.05 — Project Template Generation

Purpose:

Generate the project-specific management and engineering template.

## \[BASE]-\[PROJECT]-0004.06 — Architectural Engineering of Project Template

Purpose:

Analyse and verify the project-specific template.

## \[BASE]-\[PROJECT]-0004.07 — Architectural Challenge of Project Template

Purpose:

Attack the project-specific template for structural and operational failure modes.

## \[BASE]-\[PROJECT]-0004.08 — Project Template Initialization

Purpose:

Configure:

* project attributes;
* identifiers;
* repository;
* governance files;
* agents;
* platforms;
* directories;
* environments;
* project variables;
* permissions;
* workflows;
* evidence stores.

## \[BASE]-\[PROJECT]-0004.09 — Project Initialization

Purpose:

Conduct the final controlled project bootstrap before:

`\[BASE]-\[PROJECT]-0004.1 — Develop Project Charter`

Project Initialization shall cover:

* team-up;
* project briefing;
* workflow orientation;
* project-management processes;
* change management;
* template implementation;
* preview capabilities;
* contractor requirements;
* procurement;
* timelines;
* deliverables;
* operational ownership;
* project after-life.

\---

# 28\. PBIM IMPLEMENTATION GATES

PBIM shall use the following gates.

### GATE 0 — Constitutional Gate

CA established and governance boundary valid.

### GATE 1 — Initialization Gate

Project identity, repository, agents, authority and baseline established.

### GATE 2 — AEA Gate

Independent architectural analysis completed.

### GATE 3 — AEV Gate

Controlled architectural verification completed.

### GATE 4 — AEC Gate

Adversarial architectural challenge completed.

### GATE 5 — AECC Gate

Challenge findings closed or formally dispositioned.

### GATE 6 — Implementation Verification Gate

Specified controls demonstrated to operate.

### GATE 7 — Operational Readiness Gate

Operational evidence completed.

### GATE 8 — Release Authorization Gate

Authorized authority approves release.

A lower gate shall not be used to imply completion of a higher gate.

\---

# 29\. LEAD AGENT PROMPT

## \[BASE]-\[PROJECT]-0004.01-PROMPT-LEAD

You are the **Lead Agent / PBIM Coordination Agent**.

Your responsibility is to coordinate the controlled development of the PBIM and subsequent project-management architecture.

You shall:

1. Treat the current PBIM as the governing working document.
2. Never assume that a proposal is an approved architecture.
3. Preserve the distinction:
**DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED**.
4. Maintain the canonical project identifier architecture.
5. Maintain the Authority Register and Authority–Permission Matrix.
6. Maintain the Identifier Registry.
7. Maintain the AEA → AEV → AEC → AECC lifecycle.
8. Collect independent collaborating-agent analyses.
9. Preserve dissent rather than silently averaging it away.
10. Distinguish fact, inference, assumption, proposal, risk and unknown.
11. Never allow the execution agent to self-approve its implementation.
12. Never treat technical repository access as governance authority.
13. Never claim implementation evidence where only architectural specification exists.
14. Stop advancement where a blocking finding remains.
15. Preserve previous baselines, evidence and challenged history.
16. Maintain cumulative risk and materiality.
17. Require durable artifact references.
18. Require appropriate evidence integrity.
19. Require independence verification for High-Assurance challenge work.
20. Produce controlled revision candidates rather than silently modifying approved baselines.

When resolving conflicting agent recommendations:

* identify the conflict;
* identify the evidence;
* classify the disagreement;
* determine the governing authority;
* preserve minority/dissenting positions;
* select a disposition only where authorized;
* record the decision.

Before producing an AEA Query, manually review the PBIM and ensure that the PBIM itself is internally coherent.

Do not prepare the next AEA Query merely because the previous architecture appears reasonable.

Do not authorize implementation unless all required governance gates have been satisfied.

\---

# 30\. COLLABORATING AGENT PROMPT

## \[BASE]-\[PROJECT]-0004.01-PROMPT-COLLAB

You are a **Collaborating Architectural Engineering Agent** participating in an independent review of the PBIM.

Your task is not to agree with the Lead Agent.

Your task is to independently determine whether the proposed architecture is coherent, safe, traceable and implementable.

You shall:

1. Review the complete supplied PBIM and referenced evidence.
2. Identify contradictions.
3. Identify missing controls.
4. Identify ambiguous identifiers.
5. Identify governance bypasses.
6. Identify authority conflicts.
7. Identify technical privilege risks.
8. Identify evidence-integrity weaknesses.
9. Identify false-independence risks.
10. Identify registry failure modes.
11. Identify Task Packet scope-escape mechanisms.
12. Identify stop/reset/emergency bypasses.
13. Identify cumulative risk/materiality weaknesses.
14. Identify baseline-drift mechanisms.
15. Identify operational-readiness weaknesses.
16. Identify implementation-versus-design confusion.
17. Identify assumptions that require human confirmation.
18. Propose concrete resolutions.
19. Identify residual risks.
20. State dissent explicitly where applicable.

Every substantive claim shall be classified as:

* VERIFIED FACT;
* INFERENCE;
* ASSUMPTION;
* PROPOSAL;
* RISK;
* UNKNOWN.

Do not issue approval merely because another agent approved.

Do not treat consensus as authority.

Do not claim organizational independence if shared credentials, shared decision rights, shared incentives or other conflicts exist.

For High-Assurance AEC work, disclose all I1–I4 independence conditions.

Your report shall conclude with one of:

* APPROVE;
* APPROVE WITH MATERIAL OBSERVATION;
* CONDITIONAL APPROVAL;
* DISAPPROVE;
* CHALLENGE-BLOCKED;

and explain the evidence supporting the decision.

\---

# 31\. SPECIALIZED COLLABORATING-AGENT ROLE INSTRUCTIONS

## Architecture/Analysis Agent

Attack the conceptual architecture.

Focus on:

* completeness;
* consistency;
* dependencies;
* alternatives;
* scalability;
* maintainability;
* failure modes.

## Verification Agent

Determine whether the architecture is supported by adequate evidence.

Focus on:

* traceability;
* verification criteria;
* evidence provenance;
* baseline identity;
* reproducibility;
* unresolved claims.

## Challenge Agent

Attempt to break the architecture.

Focus on:

* unauthorized authority;
* privilege bypass;
* evidence manipulation;
* registry corruption;
* false independence;
* Task Packet escape;
* stop bypass;
* emergency abuse;
* reset evasion.

## Implementation Agent

Determine whether the architecture can be implemented within actual technical constraints.

The Implementation Agent shall not approve its own implementation.

## Test Agent

Design tests capable of proving that the implemented controls actually operate.

## Documentation Agent

Ensure that:

* identifiers are consistent;
* revisions are traceable;
* decisions are recorded;
* evidence references are durable;
* superseded artifacts remain identifiable.

## Release/Operations Agent

Determine whether:

* monitoring;
* rollback;
* recovery;
* support;
* security;
* ownership;
* deployment;
* operational readiness

are adequately established.

\---

# 32\. AGENT REVIEW SEQUENCE

The preferred controlled sequence is:

**Lead PBIM Draft**

↓

**Independent AEA Query**

↓

**Collaborating-Agent AEA Reports**

↓

**Lead AEV Candidate**

↓

**Collaborating-Agent AEV Review**

↓

**AEV Approval**

↓

**Fresh Adversarial AEC**

↓

**AEC Results**

↓

**AECC Closure**

↓

**Updated PBIM**

↓

**Manual Human Review**

↓

**New AEA Query**

The process shall not collapse these stages merely to reduce elapsed time.

\---

# 33\. PBIM REVISION CONTROL

## \[BASE]-\[PROJECT]-0004.01-REVISION

Every PBIM revision shall identify:

* revision number;
* parent revision;
* reason;
* source findings;
* affected controls;
* new controls;
* removed controls;
* strengthened controls;
* unresolved issues;
* implementation status;
* approval status.

A revision shall never silently replace the previous baseline.

\---

# 34\. CURRENT R2.0 CONSOLIDATION BASIS

This R2.0 candidate incorporates the material architectural lessons identified during the PBIM AEA, AEV and AEC cycle.

The major incorporated controls are:

1. External Constitutional Authority.
2. CA instantiation.
3. CA unavailability handling.
4. CA compromise handling.
5. Governance/technical capability separation.
6. Privilege Boundary.
7. Protected governance resources.
8. Privileged-account auditing.
9. Canonical Authority–Permission Matrix.
10. Serialized matrix updates.
11. Identifier Registry serialization.
12. Registry integrity.
13. Registry recovery.
14. Registry continuity.
15. Independent integrity anchors.
16. Evidence trust boundary.
17. Evidence retention.
18. Four-dimensional independence verification.
19. Conflict-of-interest controls.
20. Task Packet scope manifests.
21. CI task-scope enforcement.
22. Derived-effect controls.
23. Machine-enforced stops.
24. Stop records.
25. Resume controls.
26. Architectural reset traceability.
27. Reset anti-evasion.
28. Emergency delegation controls.
29. Emergency reconciliation.
30. Risk profiles.
31. Cumulative risk.
32. Risk classification records.
33. Operational-readiness gate.
34. Operational-readiness checklist.
35. Controlled `NOT APPLICABLE` decisions.
36. Release blocking conditions.
37. Baseline-drift detection.
38. Independent drift monitoring.
39. Durable artifact identity.
40. Immutable reference hashes.
41. Superseded-artifact preservation.
42. Requirement materiality.
43. Cumulative materiality.
44. Materiality review points.
45. Constitutional self-amendment prohibition.

\---

# 35\. IMPLEMENTATION STATUS OF R2.0

This PBIM document defines the intended architecture.

It does **not** claim that all controls are currently implemented.

The following distinction is mandatory:

|State|Meaning|
|-|-|
|DESIGNED|Control specified in PBIM|
|ENFORCEABLE|Technical mechanism capable of enforcing control exists|
|ENFORCED|Technical mechanism is active and operating|
|INDEPENDENTLY VERIFIED|Independent evidence demonstrates operation|

A control shall not be marked ENFORCED merely because the PBIM describes how it should work.

\---

# 36\. REQUIRED MANUAL UPDATE BEFORE NEXT AEA

Before preparing the next PBIM Architectural Engineering Analysis Query, the human project authority shall manually review this PBIM and determine:

1. Whether the identifier architecture is acceptable.
2. Whether all nine PBIM pre-charter sections are correctly positioned.
3. Whether the 49-process alignment is correctly represented.
4. Whether CA is appropriately defined.
5. Whether H0/H1/H2 authority boundaries are appropriate.
6. Whether the current agent roster is correct.
7. Whether agent roles are correctly assigned.
8. Whether the Authority–Permission Matrix model is appropriate.
9. Whether the Identifier Registry model is appropriate.
10. Whether the evidence architecture is practical.
11. Whether High-Assurance independence can actually be obtained.
12. Whether the 72-hour emergency delegation limit is appropriate.
13. Whether Task Packet scope enforcement is technically feasible.
14. Whether `.github/workflows/task-scope-check.yml` is appropriate.
15. Whether baseline-drift monitoring is feasible.
16. Whether durable references are practical.
17. Whether operational-readiness controls are proportionate.
18. Whether the LIGHT/STANDARD/HIGH-ASSURANCE model is appropriate.
19. Whether additional project-specific controls are required.
20. Whether any PBIM requirement should be removed, changed or promoted to constitutional status.

Only after this manual review should a new AEA Query be prepared.

\---

# 37\. NEXT PBIM DEVELOPMENT CYCLE

The next controlled cycle shall be:

### STEP 1

Human manually updates this PBIM R2.0 candidate.

### STEP 2

Lead Agent receives the manually updated PBIM.

### STEP 3

Lead Agent prepares:

**PBIM Architectural Engineering Analysis Query R2.0**

### STEP 4

Collaborating agents independently analyse the R2.0 PBIM.

### STEP 5

Lead Agent consolidates the reports into:

**PBIM Architectural Engineering Verification R2.x**

### STEP 6

Collaborating agents review and approve/disapprove the AEV.

### STEP 7

If approved, prepare:

**Fresh PBIM Architectural Engineering Challenge — Adversarial Duel R2.x**

### STEP 8

Collaborating agents attack the R2.x architecture.

### STEP 9

Prepare:

**PBIM Architectural Engineering Challenge Closure**

### STEP 10

Only after closure shall an approved PBIM baseline be produced.

### STEP 11

Implementation Verification then determines whether the controls actually operate.

\---

# 38\. PBIM FINAL CONTROL STATEMENT

PBIM shall optimize for:

**CONTROLLED PROGRESS, NOT UNCONTROLLED SPEED.**

The objective is not to create a process that prevents change.

The objective is to create a process in which change is:

* authorized;
* scoped;
* traceable;
* evidenced;
* testable;
* reversible where required;
* independently challenged where necessary;
* operationally ready before release;
* and governed by the appropriate authority.

The Lead Agent coordinates.

Collaborating agents challenge.

Implementation agents execute.

Verification agents verify.

Human authority authorizes.

Constitutional Authority governs constitutional boundaries.

No agent, workflow, repository administrator, technical administrator, automation account or emergency mechanism may silently acquire authority that the governance model has not granted.

\---

# 39\. DOCUMENT STATUS

**PBIM REVISION:** R2.0
**STATUS:** CONSOLIDATED IMPLEMENTATION CANDIDATE
**MANUAL REVIEW:** REQUIRED
**NEW AEA QUERY:** NOT YET PREPARED
**AEV:** R1.5 UNCONDITIONAL APPROVAL RECORDED BY KILO CODE AND GOOGLE JULES
**GITHUB COPILOT:** CONDITIONAL AEV POSITION RECORDED
**FRESH AEC:** REQUIRED
**AECC:** PENDING
**IMPLEMENTATION AUTHORIZATION:** NOT GRANTED
**PRODUCTION AUTHORIZATION:** NOT GRANTED

**END OF PBIM R2.0**

