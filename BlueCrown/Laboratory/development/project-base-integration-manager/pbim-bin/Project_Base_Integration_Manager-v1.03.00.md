# PROJECT BASE INTEGRATION MANAGER \[PBIM]

**Document Type:** Project Management / Project Development Master Template
**Document Status:** Implementation-Ready Baseline — Manual Update Required
**Template Version:** v2.00.00
**Template Identifier:** \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[PBIM]
**Last Updated:** \[YYYY-MM-DDTHH:MM:SSZ]
**Prepared By:** \[LEAD / PROJECT MANAGER / LEAD AGENT]
**Architectural Engineering Status:** NOT STARTED
**Production Implementation Status:** NOT AUTHORIZED

\---

# 1\. DOCUMENT CONTROL

## 1.1 Project Identity

**\[PROJECT-NAME]:** {{Project name}}

**\[PROJECT-BASE]:** {{Base organization / platform / product}}

**\[PROJECT-ABBREVIATION]:** {{Project abbreviation}}

**\[BASE-IDENTIFIER]:** {{Base identifier}}

**\[PROJECT-IDENTIFIER]:** \[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]

**\[PBIM-IDENTIFIER]:** \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[PBIM]

\---

## 1.2 Project Management Authority

**Project Management Office:**
{{PMO / department / organization}}

**Project Sponsor:**
{{Name / role}}

**Project Manager:**
{{Name / role}}

**Lead Agent:**
{{Agent}}

**Collaborating Agents:**
{{Agent list}}

**Technical Lead:**
{{Name / role}}

**Business / Product Owner:**
{{Name / role}}

**Architecture Authority:**
{{Name / role}}

\---

## 1.3 Repository and Working Locations

**Project Management Repository:**
{{URL}}

**Project Development Repository:**
{{URL}}

**Production Repository:**
{{URL}}

**Project Documentation Folder:**
{{URL/path}}

**Architecture Documentation Folder:**
{{URL/path}}

**ADR Location:**
{{URL/path}}

**Decision Ledger:**
{{URL/path}}

**Task Packet Location:**
{{URL/path}}

**Agent Instructions / AGENTS.md Locations:**
{{locations}}

\---

# 2\. PBIM PURPOSE

The Project Base Integration Manager establishes the controlled management, development, verification, initialization, and handoff framework from which a project-specific management system will be created.

The PBIM is not itself the project implementation.

It is the **integration layer that converts an approved project concept into a controlled project operating framework**.

The PBIM must establish:

1. project identity;
2. governance;
3. roles and responsibilities;
4. development workflow;
5. project-management process mapping;
6. agent collaboration rules;
7. document and evidence controls;
8. architectural engineering gates;
9. verification requirements;
10. challenge and closure mechanisms;
11. project proposal generation;
12. project-template generation;
13. project initialization;
14. execution readiness;
15. change-control principles;
16. monitoring and reporting expectations;
17. project handoff and operational continuity.

\---

# 3\. PBIM GOVERNING PRINCIPLES

The following principles apply throughout the PBIM.

### 3.1 Evidence Before Decision

No material architectural, technical, financial, scope, schedule, security, or implementation decision should be treated as established solely because an agent proposes it.

Decisions should be supported by:

* requirements;
* source documents;
* repository evidence;
* technical analysis;
* stakeholder requirements;
* constraints;
* risks;
* alternatives;
* verification results;
* challenge results.

\---

### 3.2 Separation of Analysis, Verification and Implementation

The following activities are distinct:

**Analysis**

> Determine what should be done and identify possible solutions.

**Verification**

> Determine whether the proposed solution is sufficiently coherent, complete, feasible, traceable, and acceptable.

**Challenge**

> Attempt to demonstrate that the proposed solution can fail.

**Closure**

> Determine whether identified weaknesses have been resolved or formally accepted.

**Implementation**

> Execute the approved solution.

No stage should silently substitute for another.

\---

### 3.3 Lead Agent Is Integrator, Not Sole Authority

The lead agent is responsible for integrating the work of collaborating agents.

The lead agent must not treat its own proposal as automatically authoritative.

Where appropriate, independent agents should challenge:

* assumptions;
* requirements;
* architecture;
* identifiers;
* dependencies;
* workflow;
* security;
* maintainability;
* scalability;
* operational feasibility;
* project-management controls.

\---

### 3.4 Production Code Is Gated

No agent may modify production implementation merely because the PBIM exists.

Production implementation requires the applicable downstream authorization gate.

\---

### 3.5 Reproducibility

A future project manager or agent should be able to determine:

* why a decision was made;
* who made it;
* what evidence was used;
* what alternatives were considered;
* what was rejected;
* what risks remained;
* what approval was obtained;
* what document superseded it.

\---

# 4\. IDENTIFIER SYSTEM

## 4.1 Primary Project Identifier

Use:

`\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]`

Example:

`BZJ-PGB`

\---

## 4.2 Controlled Section Identifier

Use:

`\[PROJECT-IDENTIFIER]-\[PROCESS-IDENTIFIER]-\[SEQUENCE]`

Example:

`BZJ-PGB-GOV-01-0004.01`

\---

## 4.3 Numeric Sequence

The numeric sequence represents project lifecycle position.

The general model is:

* `0000–0999` — Initiation / project-base definition
* `1000–1999` — Planning
* `2000–3999` — Risk, sourcing, stakeholder and preparation activities
* `4000–6999` — Execution
* `7000–8999` — Monitoring and controlling
* `9000–9999` — Closure

Intermediate identifiers may be inserted where required.

Example:

`0004.01`

comes before:

`0004.1`

and permits controlled subdivision without destroying the lifecycle ordering.

\---

## 4.4 Identifier Governance

An identifier must not be reused for a different substantive purpose after publication.

If a section changes materially:

1. preserve the original identifier;
2. record the change;
3. increment the document revision;
4. create a new identifier only where the change creates a distinct management activity.

\---

# 5\. PROJECT-MANAGEMENT PROCESS ALIGNMENT

The PBIM uses the organization's project-management process architecture as its lifecycle reference.

The project does not need to contain exactly the same number of sections as the reference framework.

Instead, each project section should map to one or more applicable project-management processes.

At minimum, the project should maintain traceability to:

* project initiation;
* stakeholder identification;
* integration;
* scope;
* requirements;
* schedule;
* cost;
* quality;
* resources;
* communications;
* risk;
* sourcing;
* stakeholder engagement;
* execution;
* knowledge;
* monitoring;
* change;
* validation;
* closure.

\---

# 6\. PBIM DEVELOPMENT LIFECYCLE

The PBIM development lifecycle consists of nine controlled stages:

|Identifier|Stage|Primary Outcome|
|-|-|-|
|`GOV-01-0004.01`|PBIM Baseline Assembly|Complete source-controlled PBIM baseline|
|`GOV-02-0004.02`|PBIM Architectural Engineering|Verified PBIM architecture|
|`SCP-03-0004.03`|Project Proposal Definition|Controlled proposal-generation framework|
|`SCP-04-0004.04`|Project Proposal Architectural Engineering|Verified proposal framework|
|`RES-03-0004.05`|Project Template Generation|Initial project template|
|`SCP-04-0004.06`|Project Template Architectural Engineering|Verified project template|
|`GOV-01-0004.07`|Project Initialization|Initialized project environment|
|`GOV-02-0004.08`|Project Framework Activation|Operational project framework|
|`GOV-09-0004.09`|PBIM Handoff and Baseline Closure|Controlled project handoff|

\---

# 7\. \[GOV-01-0004.01] PBIM BASELINE ASSEMBLY

## Purpose

Compile the resources required to construct or revise the generic PBIM.

This section must be completed before PBIM Architectural Engineering begins.

\---

## 7.1 Required Inputs

The project manager must compile:

1. current PBIM;
2. previous PBIM versions;
3. PBIM AEA query;
4. AEA reports;
5. AEV statement revisions;
6. AEV responses;
7. adversarial challenge document;
8. adversarial challenge results;
9. applicable organizational standards;
10. applicable project-management standards;
11. current development workflow;
12. lessons learned from previous projects;
13. current tool and agent ecosystem;
14. repository structure;
15. governance requirements.

\---

## 7.2 Resource Register

|Resource|Location|Version / Date|Status|Applicable Sections|
|-|-|-|-|-|
|Current PBIM|{{link}}|{{version}}|{{status}}|All|
|AEA Query|{{link}}|{{version}}|{{status}}|0004.02|
|AEA Reports|{{link}}|{{version}}|{{status}}|0004.02|
|AEV Statement|{{link}}|{{version}}|{{status}}|0004.02|
|AEV Responses|{{link}}|{{version}}|{{status}}|0004.02|
|AEC Challenge|{{link}}|{{version}}|{{status}}|0004.02|
|AEC Results|{{link}}|{{version}}|{{status}}|0004.02|

\---

## 7.3 Prompt — Lead Agent

### PROMPT: PBIM BASELINE SYNTHESIS

> Act as the Lead Project Integration Agent.
>
> Review every supplied PBIM source and supporting architectural-engineering artifact.
>
> Determine:
>
> 1. which existing PBIM structures should be retained;
> 2. which structures should be removed;
> 3. which structures should be consolidated;
> 4. which identifiers require correction;
> 5. which sections are missing;
> 6. which prompts are ambiguous;
> 7. which workflow gates are insufficient;
> 8. which responsibilities are unclear;
> 9. which controls are duplicated;
> 10. which controls are absent;
> 11. which recommendations from prior analysis should be incorporated;
> 12. which recommendations should be rejected and why.
>
> Produce a proposed PBIM baseline architecture.
>
> Do not implement production code.
>
> Every material recommendation must identify its evidence or rationale.

\---

## 7.4 Prompt — Collaborating Agents

### PROMPT: INDEPENDENT PBIM ANALYSIS

> Independently review the supplied PBIM source set.
>
> Do not merely improve the existing document.
>
> Identify structural weaknesses that could cause the PBIM to:
>
> \* produce an incomplete project;
> \* produce conflicting instructions;
> \* permit premature implementation;
> \* lose decision traceability;
> \* create ambiguous ownership;
> \* create identifier conflicts;
> \* create uncontrolled document revisions;
> \* weaken architectural review;
> \* create ineffective agent collaboration;
> \* fail during project initialization;
> \* become unusable on projects different from the reference project.
>
> Recommend concrete corrections.
>
> Separate:
>
> \*\*Critical defects\*\*
>
> \*\*Major improvements\*\*
>
> \*\*Minor improvements\*\*
>
> \*\*Optional enhancements\*\*
>
> Do not modify production code.

\---

## 7.5 Output

**PBIM Baseline Candidate:**

`\[PROJECT-IDENTIFIER]-PBIM-Baseline-Candidate-\[VERSION]`

\---

## 7.6 Gate

**Exit condition:**

All required resources have been identified, accessible, versioned, and mapped to the PBIM sections they influence.

**Stop if:**

* required source material is missing;
* source versions conflict materially;
* project identity is unresolved;
* governance authority is unknown;
* the lead agent cannot establish document lineage.

\---

# 8\. \[GOV-02-0004.02] PBIM ARCHITECTURAL ENGINEERING

## Purpose

Subject the PBIM baseline to controlled Architectural Engineering before it becomes an approved project-management instrument.

\---

## 8.1 Stage Sequence

```text
PBIM Baseline
      ↓
AEA Query
      ↓
Independent Agent Analysis
      ↓
AEA Reports
      ↓
Lead-Agent Synthesis
      ↓
AEV Baseline Candidate
      ↓
AEV Review
      ↓
Revision / Resolution
      ↓
Collaborating-Agent Approval
      ↓
AEC Adversarial Challenge
      ↓
Challenge Results
      ↓
Challenge Closure
      ↓
PBIM Approval
```

\---

## 8.2 PBIM AEA Query

### PROMPT

> Thoroughly review the complete PBIM baseline candidate.
>
> Evaluate it as a reusable project-management architecture rather than as a document to be stylistically edited.
>
> Analyze:
>
> 1. lifecycle completeness;
> 2. process sequencing;
> 3. identifier integrity;
> 4. governance;
> 5. role separation;
> 6. decision rights;
> 7. requirements traceability;
> 8. document control;
> 9. agent collaboration;
> 10. prompt quality;
> 11. evidence management;
> 12. risk management;
> 13. change management;
> 14. quality management;
> 15. security;
> 16. operational readiness;
> 17. scalability;
> 18. maintainability;
> 19. project initialization;
> 20. project closure and knowledge retention.
>
> Identify contradictions and missing controls.
>
> For each significant finding provide:
>
> \* finding;
> \* severity;
> \* affected identifier;
> \* evidence;
> \* consequence;
> \* recommendation;
> \* implementation priority.
>
> Do not simply approve the existing architecture.

\---

## 8.3 AEV

The Lead Agent shall produce an **Architectural Engineering Verification baseline candidate**.

The AEV must contain:

* verified requirements;
* architectural decisions;
* accepted assumptions;
* unresolved questions;
* identified risks;
* traceability;
* rationale;
* implementation constraints;
* acceptance criteria.

\---

## 8.4 Verification Decision

Each collaborating agent shall return one of:

**APPROVE**

The architecture is sufficiently complete and coherent.

**APPROVE WITH CONDITIONS**

The architecture is acceptable provided identified conditions are incorporated.

**DISAPPROVE**

One or more blocking defects remain.

\---

## 8.5 Revision Rule

If any agent disapproves:

1. identify each blocking issue;
2. classify the issue;
3. determine corrective action;
4. revise the AEV;
5. resubmit to all required agents.

A verification cycle must not silently close an unresolved blocking issue.

\---

## 8.6 Revision Limit

A controlled revision series may be used:

* Revision 1.0
* Revision 1.1
* Revision 1.2
* Revision 1.3
* Revision 1.4

If approval cannot be achieved after the defined revision ceiling, the process must return to **PBIM Baseline Assembly** rather than producing unlimited incremental revisions.

The return must identify the reason the architecture could not converge.

\---

# 9\. ADVERSARIAL ARCHITECTURAL CHALLENGE

Once AEV approval has been obtained, the architecture must be attacked rather than merely refined.

### PROMPT: PBIM ADVERSARIAL DUEL

> Treat the approved PBIM architecture as a system that you are attempting to break.
>
> Do not defend the architecture.
>
> Attempt to demonstrate failure through:
>
> \* contradictory instructions;
> \* missing dependencies;
> \* identifier collisions;
> \* ambiguous authority;
> \* incomplete lifecycle coverage;
> \* agent disagreement;
> \* invalid assumptions;
> \* document drift;
> \* revision loops;
> \* premature implementation;
> \* inadequate change control;
> \* weak evidence;
> \* insufficient testing;
> \* security failure;
> \* scalability failure;
> \* project initialization failure;
> \* handoff failure;
> \* closure failure.
>
> For each attack provide:
>
> \*\*Attack → Preconditions → Failure Mechanism → Impact → Detectability → Mitigation → Residual Risk\*\*
>
> Identify the three most serious failure modes.

\---

# 10\. ADVERSARIAL CHALLENGE CLOSURE

The Lead Agent must classify every challenge result:

|Result|Action|
|-|-|
|No material defect|Record and close|
|Minor defect|Correct before release|
|Major defect|Re-enter appropriate AEV stage|
|Critical defect|Reopen architecture|
|Unresolvable risk|Escalate for governance decision|

The PBIM cannot be declared approved merely because the adversarial challenge has been performed.

It must demonstrate that challenge findings have been resolved, accepted, or formally escalated.

\---

# 11\. PBIM APPROVAL GATE

The PBIM may be approved only when:

* required source documents are complete;
* identifiers are consistent;
* lifecycle is coherent;
* prompts are executable;
* roles are defined;
* decision authority is defined;
* AEA is complete;
* AEV is approved;
* adversarial challenge is complete;
* challenge findings are closed or accepted;
* document control is established;
* implementation boundaries are explicit.

**Approval Status:**

`\[PENDING / APPROVED / CONDITIONALLY APPROVED / REJECTED]`

\---

# 12\. \[SCP-03-0004.03] PROJECT PROPOSAL DEFINITION

## Purpose

Use the approved PBIM to establish the framework from which the project's initial proposal will be generated.

This section does not itself constitute the final project proposal.

\---

## 12.1 Inputs

* approved PBIM;
* project concept;
* stakeholder information;
* business objectives;
* technical context;
* constraints;
* known risks;
* available resources.

\---

## 12.2 Prompt

> Using the approved PBIM as the governing project-management framework, develop a Project Proposal Generation Prompt for this project.
>
> The prompt must establish:
>
> \* project purpose;
> \* problem/opportunity;
> \* objectives;
> \* expected outcomes;
> \* scope boundaries;
> \* exclusions;
> \* stakeholders;
> \* assumptions;
> \* constraints;
> \* dependencies;
> \* risks;
> \* high-level deliverables;
> \* acceptance criteria;
> \* governance;
> \* required resources;
> \* indicative schedule;
> \* financial considerations;
> \* technical considerations;
> \* security considerations;
> \* operational considerations.
>
> Do not invent unknown project facts.
>
> Mark unknown information explicitly as `\[TBD]`.
>
> Produce a proposal framework capable of being subjected to Architectural Engineering.

\---

## 12.3 Output

`\[PROJECT-IDENTIFIER]-Project-Proposal-Prompt-\[VERSION]`

\---

# 13\. \[SCP-04-0004.04] PROJECT PROPOSAL ARCHITECTURAL ENGINEERING

The Project Proposal Prompt shall undergo:

1. AEA;
2. independent analysis;
3. lead synthesis;
4. AEV;
5. collaborating-agent verification;
6. adversarial challenge;
7. challenge closure;
8. approval.

### Stop Condition

No Project Template should be generated from an unapproved Project Proposal Prompt.

\---

# 14\. \[RES-03-0004.05] PROJECT TEMPLATE GENERATION

## Purpose

Convert the approved Project Proposal framework into a project-specific management template.

The generated template should define the project's operational structure without prematurely implementing project deliverables.

\---

## 14.1 Project Template Requirements

The template should contain, where applicable:

* project identity;
* governance;
* scope;
* requirements;
* schedule;
* resources;
* financial management;
* quality;
* communications;
* stakeholders;
* risk;
* sourcing;
* execution;
* monitoring;
* change control;
* technical development;
* testing;
* deployment;
* operations;
* maintenance;
* closure.

\---

## 14.2 Prompt

> Generate a project-specific management template using the approved PBIM and approved Project Proposal Prompt.
>
> Do not create a generic template.
>
> Instantiate the management framework using the known characteristics of this project.
>
> Preserve unresolved information as `\[TBD]`.
>
> Clearly distinguish:
>
> \* management requirements;
> \* technical requirements;
> \* deliverables;
> \* decisions;
> \* tasks;
> \* controls;
> \* evidence;
> \* approval gates.
>
> Every major project activity must have an owner, expected output, evidence requirement, and completion condition.

\---

# 15\. \[SCP-04-0004.06] PROJECT TEMPLATE ARCHITECTURAL ENGINEERING

The Project Template must undergo independent Architectural Engineering.

Evaluate:

* completeness;
* applicability;
* sequencing;
* usability;
* governance;
* traceability;
* implementation readiness;
* agent interoperability;
* document control;
* change control;
* risk;
* quality;
* security;
* operational continuity.

The template must not proceed to initialization until approved.

\---

# 16\. \[GOV-01-0004.07] PROJECT INITIALIZATION

## Purpose

Convert the approved Project Template into an initialized project environment.

\---

## 16.1 Initialization Checklist

### Project Environment

* \[ ] Project repository created
* \[ ] Project identifier established
* \[ ] Project folders created
* \[ ] Project documents folder created
* \[ ] Production repository identified
* \[ ] Development repository identified
* \[ ] Documentation repository identified

### Agent Environment

* \[ ] Lead agent assigned
* \[ ] Collaborating agents assigned
* \[ ] Agent responsibilities defined
* \[ ] Agent branches/workspaces defined
* \[ ] Agent instructions established
* \[ ] AGENTS.md reviewed
* \[ ] Task packet established

### Architecture

* \[ ] ADR location established
* \[ ] Decision Ledger established
* \[ ] Architecture baseline established
* \[ ] Architecture ownership established

### Governance

* \[ ] Project sponsor established
* \[ ] Project manager established
* \[ ] Approval authority established
* \[ ] Change authority established
* \[ ] Escalation path established

### Controls

* \[ ] Stop conditions established
* \[ ] Change-control mechanism established
* \[ ] Evidence requirements established
* \[ ] Review requirements established
* \[ ] Testing requirements established
* \[ ] Release controls established

\---

# 17\. AGENT COLLABORATION MODEL

Each agent should operate in a controlled workspace.

Example:

```text
main/
├── lead-agent
├── kilo-code
├── google-jules
├── github-copilot
└── chatgpt-codex
```

The actual agent ecosystem is configurable.

The PBIM must never hard-code a specific vendor ecosystem unless the project explicitly requires it.

\---

## 17.1 Agent Responsibilities

### Lead Agent

Responsible for:

* integration;
* synthesis;
* decision preparation;
* conflict resolution;
* baseline preparation;
* gate management.

### Collaborating Agents

Responsible for:

* independent analysis;
* technical review;
* challenge;
* alternative proposals;
* defect discovery;
* verification.

### Project Manager

Responsible for:

* governance;
* stakeholder management;
* approvals;
* project controls;
* change control;
* final administrative authority.

\---

# 18\. TASK PACKET STANDARD

Every substantive agent assignment should use a controlled task packet.

Minimum fields:

```text
TASK ID:
PROJECT ID:
SECTION ID:
TASK TITLE:
OBJECTIVE:
BACKGROUND:
INPUT DOCUMENTS:
REQUIRED EVIDENCE:
CONSTRAINTS:
ASSIGNED AGENT:
COLLABORATING AGENTS:
EXPECTED OUTPUT:
ACCEPTANCE CRITERIA:
STOP CONDITIONS:
DEPENDENCIES:
DEADLINE:
OUTPUT LOCATION:
REVISION:
STATUS:
```

\---

# 19\. PROMPT ENGINEERING STANDARD

Every production prompt embedded in the PBIM should answer:

1. Who is performing the task?
2. What is the task?
3. Why is it being performed?
4. What resources must be reviewed?
5. What constraints apply?
6. What must not be assumed?
7. What output is required?
8. What evidence is required?
9. What acceptance criteria apply?
10. What constitutes failure?
11. What is the next gate?

\---

## 19.1 Standard Prompt Pattern

```text
ROLE

Act as \[ROLE].

CONTEXT

You are working on \[PROJECT].

OBJECTIVE

Your objective is to \[OBJECTIVE].

INPUTS

Review the following resources:
\[RESOURCES]

REQUIREMENTS

You must:
1. ...
2. ...
3. ...

CONSTRAINTS

Do not:
1. ...
2. ...

ANALYSIS STANDARD

Evaluate:
...

OUTPUT

Return:
...

EVIDENCE

For each material conclusion provide:
...

ACCEPTANCE CRITERIA

The result is acceptable only when:
...

STOP CONDITIONS

Stop and report if:
...

HANDOFF

Return the completed result to:
...
```

\---

# 20\. DOCUMENT AND EVIDENCE CONTROL

Every substantive artifact must have:

* identifier;
* title;
* version;
* date/time;
* author;
* project;
* status;
* source references;
* revision history;
* approval status.

\---

## 20.1 Status Values

Use controlled statuses:

`DRAFT`

`UNDER REVIEW`

`REVISION REQUIRED`

`CONDITIONALLY APPROVED`

`APPROVED`

`SUPERSEDED`

`REJECTED`

`ARCHIVED`

\---

# 21\. DECISION MANAGEMENT

Material project decisions must be recorded in the Decision Ledger or applicable ADR system.

A decision should contain:

```text
DECISION ID:
PROJECT:
DATE:
DECISION:
CONTEXT:
OPTIONS:
EVALUATION:
SELECTED OPTION:
RATIONALE:
RISKS:
CONSEQUENCES:
APPROVER:
RELATED REQUIREMENTS:
RELATED DOCUMENTS:
STATUS:
```

\---

# 22\. CHANGE MANAGEMENT

No material change should be introduced merely by editing an existing instruction.

A change must identify:

* affected requirement;
* affected identifier;
* reason;
* impact;
* alternatives;
* risk;
* cost;
* schedule;
* dependencies;
* approval authority;
* resulting document revision.

\---

# 23\. MONITORING AND CONTROLLING

Project reviews should verify:

### Scope

* approved requirements remain controlled;
* unauthorized scope is identified.

### Schedule

* milestones remain achievable;
* delays are visible.

### Cost

* expenditure remains controlled;
* financial changes are documented.

### Quality

* deliverables meet acceptance criteria.

### Risk

* risks are actively monitored;
* responses are implemented.

### Resources

* required personnel, tools and infrastructure remain available.

### Communications

* stakeholders receive required information.

### Architecture

* implementation remains consistent with approved architecture.

\---

# 24\. AGENT CHALLENGE BEFORE BUILD

Before significant implementation begins, the proposed implementation should be challenged.

### PROMPT

> Before implementation begins, independently attempt to identify the ways this implementation plan could fail.
>
> Focus on:
>
> \* requirements;
> \* architecture;
> \* dependencies;
> \* security;
> \* compatibility;
> \* data integrity;
> \* rollback;
> \* testing;
> \* operational impact;
> \* maintainability;
> \* scalability.
>
> Identify blocking issues before implementation begins.
>
> Do not modify production code.

A failed challenge does not indicate that the project failed.

It indicates that the control system worked.

\---

# 25\. IMPLEMENTATION AUTHORIZATION

Production implementation requires all applicable gates to be satisfied.

Minimum conditions:

* approved scope;
* approved requirements;
* approved architecture;
* approved implementation plan;
* identified risks;
* defined rollback;
* testing strategy;
* agent challenge completed;
* implementation owner assigned;
* change authority established.

\---

# 26\. STOP CONDITIONS

Any project participant may stop progression when:

1. a critical requirement is unknown;
2. conflicting instructions exist;
3. architectural authority is unclear;
4. production impact is unknown;
5. required evidence is unavailable;
6. a security issue is unresolved;
7. a critical dependency is missing;
8. a material change is unauthorized;
9. an approval is missing;
10. the task cannot be completed safely within the defined scope.

A stop condition must produce a documented issue rather than an informal workaround.

\---

# 27\. PROJECT KNOWLEDGE MANAGEMENT

The project must retain:

* important decisions;
* rejected alternatives;
* lessons learned;
* architecture decisions;
* failed approaches;
* successful approaches;
* reusable prompts;
* reusable task packets;
* testing lessons;
* operational lessons.

The objective is to prevent future projects from repeatedly rediscovering the same problems.

\---

# 28\. \[GOV-02-0004.08] PROJECT FRAMEWORK ACTIVATION

The project-management framework becomes operational after initialization.

Activation must confirm:

* project team assembled;
* communication channels active;
* repositories operational;
* documentation controls operational;
* task management operational;
* decision ledger operational;
* ADR system operational;
* review schedule established;
* reporting schedule established;
* risk register active;
* change control active.

\---

# 29\. PROJECT EXECUTION CONTROL

Once activated, the project follows its project-specific template.

The PBIM remains the governing integration framework.

The project template becomes the operational execution framework.

The distinction is:

```text
PBIM
  ↓
Project Proposal
  ↓
Project Template
  ↓
Project Initialization
  ↓
Project Execution
```

\---

# 30\. \[GOV-09-0004.09] PBIM HANDOFF AND BASELINE CLOSURE

The PBIM development stage is complete when:

* PBIM approved;
* Project Proposal framework approved;
* Project Template approved;
* project environment initialized;
* governance activated;
* project controls operational;
* outstanding PBIM issues resolved or formally accepted.

\---

## 30.1 Handoff Package

The handoff package should contain:

1. approved PBIM;
2. approved Project Proposal;
3. approved Project Template;
4. architecture baseline;
5. ADR register;
6. Decision Ledger;
7. requirements baseline;
8. risk register;
9. stakeholder register;
10. project schedule;
11. task packet;
12. agent assignments;
13. repository map;
14. communication plan;
15. quality controls;
16. change-control process;
17. implementation authorization requirements.

\---

# 31\. PBIM COMPLETION GATE

### Required approvals

**Project Manager:**
`\[ ] APPROVED`

**Lead Agent:**
`\[ ] APPROVED`

**Architecture Authority:**
`\[ ] APPROVED`

**Required Collaborating Agents:**
`\[ ] APPROVED`

**Project Sponsor:**
`\[ ] APPROVED`

\---

### Final Status

`\[PENDING / CONDITIONALLY APPROVED / APPROVED / REJECTED]`

\---

# 32\. PBIM REVISION HISTORY

|Version|Date|Author|Change|Reason|Approval|
|-|-|-|-|-|-|
|v2.00.00|{{date}}|{{author}}|Generic implementation-ready baseline|PBIM modernization|Pending|
|||||||

\---

# 33\. PBIM IMPLEMENTATION NOTES

This document is intentionally an **implementation-ready baseline**, not the final Architecturally Engineered PBIM.

Before AEA begins, the Project Manager / Lead Agent must manually:

1. replace all project placeholders;
2. verify every identifier;
3. verify project-management process mappings;
4. verify organizational terminology;
5. verify agent assignments;
6. verify repository locations;
7. verify governance authority;
8. verify document-control locations;
9. verify applicable standards;
10. remove obsolete instructions;
11. add project-specific constraints;
12. confirm approval authorities;
13. confirm revision limits;
14. confirm stop conditions;
15. confirm evidence requirements.

Only after this manual preparation should the PBIM enter the formal:

```text
PBIM
  ↓
Architectural Engineering Analysis
  ↓
Architectural Engineering Verification
  ↓
Adversarial Architectural Challenge
  ↓
Challenge Closure
  ↓
PBIM Approval
```

\---

# 34\. FINAL GOVERNANCE PRINCIPLE

The PBIM should be treated as a **controlled project-management architecture**, not simply as a long project checklist.

Its primary purpose is to ensure that every project begins with:

**identity → governance → evidence → architecture → verification → challenge → authorization → execution → control → closure**

and that every transition between those states is explicit, traceable and reviewable.

