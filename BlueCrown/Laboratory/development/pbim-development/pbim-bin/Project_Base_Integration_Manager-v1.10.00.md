# PROJECT BASE INTEGRATION MANAGER \[PBIM]

**Document Version:** v2.00.00
**Document Class:** Generic Project Governance, Integration, Assurance and Delivery Operating Model
**Document Status:** IMPLEMENTATION-READY BASELINE — MANUAL PROJECT CONFIGURATION REQUIRED
**Architectural Status:** NOT YET ARCHITECTURALLY ENGINEERED
**Implementation Authorization:** NOT GRANTED
**Production Authorization:** NOT GRANTED
**Prepared Date:** \[YYYY-MM-DDTHH:MM:SSZ]

\---

# 1\. DOCUMENT IDENTITY

## 1.1 Project Identity

**\[PROJECT-NAME]:** \[Project Name]

**\[PROJECT-BASE]:** \[Base Organization / Platform / Product]

**\[BASE-IDENTIFIER]:** \[Base Identifier]

**\[PROJECT-ABBREVIATION]:** \[Project Abbreviation]

**\[PROJECT-IDENTIFIER]:** `\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]`

**\[PBIM-IDENTIFIER]:**
`\[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-PBIM`

**Document Repository:** \[Repository]

**Project Repository:** \[Repository]

**Project Documentation Repository:** \[Repository]

**Project Lead:** \[Name / Role]

**Project Manager:** \[Name / Role]

**Architecture Authority:** \[Name / Role]

**Approval Authority:** \[Name / Role]

**Lead Agent:** \[Agent]

**Collaborating Agents:** \[Agents]

\---

# 2\. PURPOSE

The Project Base Integration Manager (PBIM) is the governing framework used to establish, integrate, assure, execute, monitor, control and close a project.

PBIM provides a reusable control layer between organizational governance and project-specific execution.

PBIM establishes:

1. project identity;
2. authority and accountability;
3. scope and requirements governance;
4. planning and execution controls;
5. stakeholder and communication controls;
6. resource and financial controls;
7. quality and risk controls;
8. architectural assurance;
9. agent collaboration controls;
10. evidence and document control;
11. change control;
12. implementation authorization;
13. stop and escalation mechanisms;
14. knowledge management;
15. project closure and handoff.

PBIM is not itself the project implementation plan.

The relationship is:

```text
PBIM
  ↓
Project Proposal
  ↓
Architecturally Engineered Project Proposal
  ↓
Project Template
  ↓
Architecturally Engineered Project Template
  ↓
Project Initialization
  ↓
Project Execution
  ↓
Monitoring \& Control
  ↓
Project / Phase Closure
```

\---

# 3\. GOVERNANCE PRINCIPLE

PBIM operates according to:

```text
GOVERNANCE
     ↓
ASSURANCE
     ↓
AUTHORIZED EXECUTION
     ↓
MONITORING \& CONTROL
     ↓
CLOSURE
```

Execution must not become the mechanism by which architecture, scope, authority or governance is discovered.

Where material uncertainty exists, the project stops and resolves the uncertainty before proceeding.

\---

# 4\. CONTROL MATURITY MODEL

Every material control should be evaluated against four maturity states.

### Level 1 — DESIGNED

The control is defined in documentation.

### Level 2 — ENFORCEABLE

A practical enforcement mechanism has been identified.

Examples:

* workflow rule;
* CI check;
* schema validator;
* approval gate;
* repository protection;
* automated test;
* access-control rule.

### Level 3 — ENFORCED

Evidence demonstrates that the control is operational.

### Level 4 — INDEPENDENTLY VERIFIED

An independent reviewer has verified the control under relevant conditions.

No control should be represented as operational merely because it is documented.

\---

# 5\. PBIM DEVELOPMENT LIFECYCLE

The PBIM itself passes through the following controlled lifecycle:

```text
RESOURCE COMPILATION
        ↓
PBIM DRAFT
        ↓
AEA QUERY
        ↓
AEA REPORTS
        ↓
AEV BASELINE CANDIDATE
        ↓
AEV REVIEW / RESPONSES
        ↓
AEC ADVERSARIAL CHALLENGE
        ↓
AEC RESULTS
        ↓
AECC / CHALLENGE CLOSURE
        ↓
MANUAL PBIM CONFIGURATION
        ↓
PBIM APPROVAL
```

No implementation authority is created merely by completing an AEA, AEV or AEC cycle.

\---

# 6\. PBIM IDENTIFIER ARCHITECTURE

## 6.1 Canonical Process Anchors

The canonical project-management process anchors are retained as stable traceability references.

Examples:

```text
GOV-01-0004.1
STK-01-0013.1
GOV-02-1004.2
SCP-01-1005.1
...
GOV-09-9004.7
```

These anchors SHALL NOT be renumbered merely because a project-specific workflow changes.

\---

## 6.2 PBIM Section Identifiers

PBIM-controlled sections use:

```text
\[PBIM-ID]-\[PROCESS-ANCHOR]
```

Example:

```text
\[BASE-PROJECT-PBIM]-\[SCP-02-1005.2]
```

\---

## 6.3 Pre-Charter Identifiers

Pre-charter and PBIM-development activities use:

```text
\[DOMAIN]-\[INDEX]-0000.xx
```

Recommended range:

```text
0000.01 — Resource Compilation
0000.02 — PBIM Baseline Construction
0000.03 — AEA Preparation
0000.04 — AEV Verification
0000.05 — AEC Challenge
0000.06 — AECC Closure
0000.07 — Project Proposal Generation
0000.08 — Project Template Generation
0000.09 — PBIM Handoff
```

\---

## 6.4 Artifact Identifiers

Every controlled artifact shall use:

```text
\[PROJECT-ID]-\[ARTIFACT-CLASS]-\[SECTION]-\[SEQUENCE]
```

Examples:

```text
\[PROJECT-ID]-BL-1005.20
\[PROJECT-ID]-TP-001
\[PROJECT-ID]-ADR-001
\[PROJECT-ID]-RSK-001
\[PROJECT-ID]-AEV-001
\[PROJECT-ID]-AEC-001
```

\---

## 6.5 Identifier Registry

The project shall maintain an authoritative identifier registry.

Minimum fields:

```text
identifier
parent\_identifier
title
domain
process\_anchor
sequence\_index
artifact\_class
status
owner
created\_at
updated\_at
supersedes
superseded\_by
```

Execution ordering SHALL use the registry's numeric `sequence\_index`.

String sorting SHALL NOT be used as the authoritative execution-order mechanism.

\---

# 7\. GOVERNANCE AND AUTHORITY

## 7.1 Authority Separation

The project shall distinguish:

* organizational authority;
* project authority;
* architecture authority;
* implementation authority;
* review authority;
* release authority;
* administrative authority.

No individual or agent should silently acquire authority merely by having technical access.

\---

## 7.2 Authority Register

The Authority Register shall identify:

|Field|Requirement|
|-|-|
|Identity|Named person or controlled agent|
|Role|Governance / architecture / execution / review|
|Authority|Decisions permitted|
|Scope|Boundaries of authority|
|Expiration|Where applicable|
|Escalation|Next authority|
|Evidence|Authorization source|

\---

## 7.3 Approval Independence

Where practical, the person or agent implementing a material change should not be the sole authority approving that same change.

Self-approval shall be treated as a risk requiring explicit justification.

\---

# 8\. AGENT COLLABORATION MODEL

The agent ecosystem is project-configurable.

PBIM SHALL NOT permanently require a specific vendor or AI platform.

Possible roles include:

* Lead Architecture Agent;
* Requirements Analyst;
* Feasibility Analyst;
* Security Analyst;
* Reliability / Test Agent;
* Implementation Agent;
* Documentation Agent;
* Independent Challenge Agent.

### Lead Agent

Responsible for:

* integration;
* synthesis;
* conflict resolution;
* architecture coordination;
* decision preparation;
* baseline preparation;
* gate management.

### Collaborating Agents

Responsible for:

* independent analysis;
* technical review;
* alternative solutions;
* defect discovery;
* challenge;
* verification.

### Human Project Authority

Retains authority for:

* governance;
* approvals;
* material scope;
* material changes;
* release authorization;
* exception acceptance.

\---

# 9\. STANDARD TASK PACKET

Every substantive agent assignment should use a controlled Task Packet.

```text
TASK ID:
PROJECT ID:
SECTION ID:
TASK TITLE:

OBJECTIVE:

BACKGROUND:

INPUT DOCUMENTS:

INPUT DATA:

REQUIRED ANALYSIS:

REQUIREMENTS:

CONSTRAINTS:

ASSIGNED AGENT:

COLLABORATING AGENTS:

EXPECTED OUTPUT:

REQUIRED EVIDENCE:

ACCEPTANCE CRITERIA:

STOP CONDITIONS:

DEPENDENCIES:

DUE DATE:

OUTPUT LOCATION:

REVISION:

STATUS:

NEXT GATE:
```

An agent should not be expected to infer missing material constraints from conversational context.

\---

# 10\. PROMPT ENGINEERING STANDARD

Every production prompt embedded in PBIM shall explicitly define:

1. role;
2. project context;
3. objective;
4. resources;
5. requirements;
6. constraints;
7. prohibited assumptions;
8. analysis criteria;
9. expected output;
10. evidence requirements;
11. acceptance criteria;
12. failure conditions;
13. handoff destination;
14. next gate.

## Standard Prompt

```text
ROLE
Act as \[ROLE].

CONTEXT
You are working on \[PROJECT-ID / PROJECT-NAME].

OBJECTIVE
\[Clearly state the intended result.]

AUTHORITATIVE INPUTS
Review:
1. \[RESOURCE]
2. \[RESOURCE]
3. \[RESOURCE]

REQUIREMENTS
You must:
1. ...
2. ...
3. ...

CONSTRAINTS
Do not:
1. ...
2. ...
3. ...

DO NOT ASSUME
Do not infer missing requirements, authority, architecture,
credentials, dependencies, or approval.

ANALYSIS STANDARD
Evaluate:
- completeness;
- correctness;
- consistency;
- dependencies;
- risk;
- security;
- maintainability;
- scalability;
- operational impact;
- implementation feasibility.

OUTPUT
Return:
1. findings;
2. recommendations;
3. unresolved issues;
4. required decisions;
5. proposed next action.

EVIDENCE
For each material conclusion identify the supporting source,
artifact, test, observation, or assumption.

ACCEPTANCE CRITERIA
The response is acceptable only when:
\[criteria]

STOP CONDITIONS
Stop and report if:
\[conditions]

HANDOFF
Return the completed result to:
\[LEAD / REVIEW / GOVERNANCE DESTINATION]
```

\---

# 11\. DOCUMENT AND EVIDENCE CONTROL

Every controlled artifact shall contain:

* identifier;
* title;
* project;
* version;
* date/time;
* author;
* status;
* source references;
* revision history;
* approval status.

Material evidence should preserve:

* original artifact;
* source;
* timestamp;
* author;
* verifier;
* verification result;
* integrity information where appropriate.

Evidence must not be recreated from memory when the original evidence can be retained.

\---

# 12\. CONTROLLED STATUS VALUES

Permitted document statuses:

```text
DRAFT
UNDER REVIEW
REVISION REQUIRED
CONDITIONALLY APPROVED
APPROVED
SUPERSEDED
REJECTED
ARCHIVED
BLOCKED
```

\---

# 13\. DECISION MANAGEMENT

Material decisions shall be recorded in the Decision Ledger or applicable ADR system.

Minimum decision record:

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
RELATED ARTIFACTS:
STATUS:
```

Rejected alternatives should be retained when their future rediscovery would create material cost or risk.

\---

# 14\. CHANGE MANAGEMENT

No material project change shall be introduced merely by editing an existing instruction.

A change shall identify:

* affected requirement;
* affected PBIM section;
* affected architecture;
* reason;
* impact;
* alternatives;
* risk;
* cost;
* schedule;
* dependencies;
* authority;
* approval;
* resulting revision.

Emergency changes shall be explicitly identified as emergency changes and retrospectively reconciled.

\---

# 15\. STOP, RESET AND ESCALATION CONTROL

Any participant may request a governance stop where:

1. a critical requirement is unknown;
2. conflicting instructions exist;
3. authority is unclear;
4. production impact is unknown;
5. required evidence is unavailable;
6. a security issue remains unresolved;
7. a critical dependency is missing;
8. an unauthorized material change exists;
9. required approval is missing;
10. safe execution cannot be demonstrated.

A stop shall produce a documented issue.

An executor shall not unilaterally override a mandatory governance stop.

\---

# 16\. PRE-CHARTER PBIM DEVELOPMENT PROCESS

## \[GOV-01-0000.01] Resource Compilation and Baseline Discovery

**Purpose:** Compile all information required to construct the project-specific PBIM.

**Required inputs:**

* previous PBIM versions;
* organizational standards;
* applicable project-management standards;
* project requirements;
* existing workflows;
* AEA queries;
* AEA reports;
* AEV statements;
* AEV responses;
* AEC challenges;
* AEC results;
* lessons learned;
* repository information;
* agent ecosystem;
* governance requirements.

**Prompt:**

> Act as the PBIM Baseline Analyst. Compile and classify every available source relevant to constructing the PBIM for \[PROJECT]. Separate authoritative requirements, historical material, recommendations, assumptions, obsolete instructions, unresolved questions and project-specific implementation details. Do not silently promote historical or project-specific information into generic policy. Produce a traceable Resource Register and identify all missing evidence before PBIM construction begins.

**Output:** `\[PROJECT-ID]-BL-0000.01 Resource Register`

\---

## \[SCP-04-0000.02] PBIM Baseline Construction

**Purpose:** Produce the initial generic PBIM using the compiled evidence.

**Prompt:**

> Act as Lead PBIM Architect. Construct the PBIM for \[PROJECT] from the approved Resource Register. Preserve canonical process anchors for traceability while separating generic governance rules from project-specific implementation details. Define identifiers, governance, roles, controls, evidence requirements, prompts, gates and handoffs. Identify unresolved design decisions rather than inventing answers.

**Output:** `\[PROJECT-ID]-BL-0000.02 PBIM Baseline Candidate`

\---

## \[AE-01-0000.03] PBIM Architectural Engineering Analysis Preparation

**Purpose:** Prepare the AEA query that will independently test the PBIM.

**Prompt:**

> Review the complete PBIM Baseline Candidate. Construct an Architectural Engineering Analysis Query that attacks its assumptions, identifiers, governance model, prompt design, evidence controls, implementation boundaries, agent collaboration model, change control, stop conditions, and lifecycle. Require independent reviewers to identify contradictions, omissions, circular dependencies, unsafe authority combinations and implementation barriers.

**Output:** `\[PROJECT-ID]-AEA-0000.03 AEA Query`

\---

## \[AE-02-0000.04] Architectural Verification Preparation

**Purpose:** Establish the controlled AEV baseline after AEA reports.

**Prompt:**

> Review the PBIM Baseline Candidate and all independent AEA reports. Separate consensus findings, disagreements, unresolved findings and unsupported recommendations. Produce a controlled AEV baseline candidate. Every proposed modification must identify the source finding, affected PBIM section, rationale, impact and verification requirement.

**Output:** `\[PROJECT-ID]-AEV-0000.04 AEV Baseline Candidate`

\---

## \[AE-03-0000.05] Adversarial Architectural Challenge

**Purpose:** Attack the proposed PBIM rather than merely improve it.

**Prompt:**

> Act as an independent adversarial architecture reviewer. Attempt to break the proposed PBIM. Test authority escalation, identifier collisions, prompt ambiguity, circular approvals, evidence gaps, self-approval, scope leakage, change-control bypass, agent conflict, missing dependencies, incomplete handoffs, emergency exceptions and production authorization paths. Do not redesign the system merely to make it appear stronger. Identify actual failure conditions and their severity.

**Output:** `\[PROJECT-ID]-AEC-0000.05 Adversarial Challenge Results`

\---

## \[GOV-01-0000.06] Architectural Challenge Closure

**Purpose:** Determine whether identified weaknesses are resolved, accepted or remain blocking.

**Prompt:**

> Review all AEC findings against the current PBIM. For every finding classify it as RESOLVED, ACCEPTED, DEFERRED, NON-ISSUE or BLOCKING. Do not mark a finding resolved without evidence. Produce a closure matrix showing the affected section, remediation, verification evidence and remaining residual risk.

**Output:** `\[PROJECT-ID]-AECC-0000.06 Challenge Closure`

\---

## \[SCP-04-0000.07] Project Proposal Generation

**Purpose:** Generate the first project proposal from the PBIM.

**Prompt:**

> Using only the approved PBIM baseline and authorized project inputs, generate the Project Proposal for \[PROJECT]. Define purpose, business/operational rationale, objectives, measurable outcomes, preliminary scope, exclusions, stakeholders, assumptions, constraints, dependencies, risks, governance, delivery approach and approval requirements. Clearly distinguish confirmed information from assumptions requiring validation.

**Output:** `\[PROJECT-ID]-BL-0000.07 Project Proposal`

\---

## \[SCP-04-0000.08] Project Template Generation

**Purpose:** Convert the approved proposal into a project execution template.

**Prompt:**

> Convert the approved Project Proposal into a reusable Project Execution Template. Map work to the canonical PBIM process anchors, define deliverables, task packets, gates, roles, dependencies, evidence requirements, review points and acceptance criteria. Ensure that project-specific technical details remain configurable rather than embedded into generic governance.

**Output:** `\[PROJECT-ID]-BL-0000.08 Project Template`

\---

## \[GOV-09-0000.09] PBIM Handoff and Baseline Closure

**Purpose:** Transfer the approved PBIM into project initialization.

**Prompt:**

> Verify that the PBIM, Project Proposal and Project Template form a coherent governance-to-execution chain. Confirm approvals, identifiers, repositories, authorities, evidence controls, task-management mechanisms, decision management, risk controls, change control and release boundaries. Identify anything preventing project initialization.

**Output:** `\[PROJECT-ID]-BL-0000.09 PBIM Handoff Package`

\---

# 17\. CANONICAL PROJECT-MANAGEMENT PROCESS MODEL

The following 40 process anchors are retained as the project's primary traceability framework.

\---

## PHASE 0 — INITIATION

### GOV-01-0004.1 — Initiate Project or Phase

**Purpose:** Authorize the project or phase at the appropriate governance level.

**Required output:** Project Charter.

**Prompt:**

> Act as the Project Initiation Lead. Using the approved PBIM and authorized project inputs, prepare the Project Charter. Define the project purpose, objectives, success criteria, authority, high-level scope, constraints, assumptions, major risks, dependencies, stakeholders and governance boundaries. Do not define detailed implementation architecture unless it is necessary to establish the charter boundary. Flag every unresolved material issue.

**Artifact:** `\[PROJECT-ID]-BL-0004.10 Project Charter`

\---

### STK-01-0013.1 — Identify Stakeholders

**Purpose:** Identify stakeholders, decision makers, affected parties and delivery roles.

**Prompt:**

> Construct the Stakeholder Register and Responsibility Matrix for \[PROJECT]. Identify human authorities, project roles, technical roles, operational owners, affected stakeholders and collaborating agents. Define responsibility, accountability, consultation and information requirements. Identify conflicts of interest and approval dependencies.

**Artifact:** `\[PROJECT-ID]-BL-0013.10 Stakeholder \& Responsibility Matrix`

\---

# PHASE 1 — PLANNING

### GOV-02-1004.2 — Integrate and Align Project Plans

**Purpose:** Establish the integrated Project Management Plan.

**Prompt:**

> Develop the Integrated Project Management Plan for \[PROJECT]. Integrate scope, schedule, cost, quality, resources, communications, risk, sourcing, stakeholder engagement, architecture, evidence and change management. Resolve conflicts between subordinate plans and identify the authoritative baseline for each control domain.

**Artifact:** `\[PROJECT-ID]-BL-1004.20 Integrated Project Management Plan`

\---

### SCP-01-1005.1 — Plan Scope Management

**Purpose:** Establish how scope will be defined, validated and controlled.

**Prompt:**

> Define the Scope Management Plan for \[PROJECT]. Establish inclusion, exclusion, boundary, acceptance, change and traceability rules. Identify what constitutes scope creep and define the approval required for material scope changes.

**Artifact:** `\[PROJECT-ID]-BL-1005.10 Scope Management Plan`

\---

### SCP-02-1005.2 — Elicit and Analyze Requirements

**Purpose:** Establish the requirements baseline.

**Prompt:**

> Elicit and analyze the requirements for \[PROJECT]. Classify functional, non-functional, operational, security, regulatory, data, integration, usability, performance and maintainability requirements. Identify conflicts, dependencies, assumptions and verification methods. Every material requirement must be testable or otherwise objectively verifiable.

**Artifact:** `\[PROJECT-ID]-BL-1005.20 Requirements Specification`

\---

### SCP-03-1005.3 — Define Scope

**Purpose:** Convert requirements into an authoritative scope boundary.

**Prompt:**

> Produce the Scope Specification for \[PROJECT]. Map approved requirements to project deliverables and explicitly define exclusions. Identify architecture boundaries, interfaces, dependencies and acceptance criteria. Do not introduce functionality merely because it would be technically convenient.

**Artifact:** `\[PROJECT-ID]-BL-1005.30 Scope \& Architecture Contract`

\---

### SCP-04-1005.4 — Develop Scope Structure

**Purpose:** Decompose approved scope into manageable work.

**Prompt:**

> Develop the Work Breakdown Structure for \[PROJECT]. Decompose scope into work packages and controlled Task Packets. Each work package must have a clear objective, dependency, owner, input, output, acceptance criteria, evidence requirement and stop condition.

**Artifact:** `\[PROJECT-ID]-BL-1005.40 WBS \& Task Packet Register`

\---

### SCH-01-1006.1 — Plan Schedule Management

**Purpose:** Define how schedule performance will be planned and controlled.

**Prompt:**

> Develop the Schedule Management Plan for \[PROJECT]. Define milestones, dependencies, estimation rules, review gates, baseline dates, schedule tolerances and escalation rules. Do not create artificial precision where dependencies are not sufficiently known.

**Artifact:** `\[PROJECT-ID]-BL-1006.10 Schedule Management Plan`

\---

### SCH-02-1006.2 — Develop Schedule

**Purpose:** Establish the project schedule.

**Prompt:**

> Build the integrated project schedule from the approved WBS and Task Packet Register. Identify dependencies, critical paths, review gates, approvals and external constraints. Highlight activities whose duration depends on unresolved information.

**Artifact:** `\[PROJECT-ID]-BL-1006.20 Integrated Project Schedule`

\---

### FIN-01-2007.1 — Plan Financial Management

**Purpose:** Establish cost and financial governance.

**Prompt:**

> Develop the Financial Management Plan for \[PROJECT]. Define cost categories, budget authority, estimation rules, approval thresholds, financial tracking, variance handling and reporting requirements. Include project-specific technical or computational costs where applicable without assuming that all projects incur them.

**Artifact:** `\[PROJECT-ID]-BL-2007.10 Financial Management Plan`

\---

### FIN-02-2007.2 — Estimate Costs

**Purpose:** Estimate project costs and resource consumption.

**Prompt:**

> Estimate project costs using the available scope, schedule and resource information. Separate known costs, estimated costs, contingency and unknowns. Identify assumptions and sensitivity drivers.

**Artifact:** `\[PROJECT-ID]-BL-2007.20 Cost Estimate`

\---

### FIN-03-2007.3 — Develop Budget

**Purpose:** Establish the approved cost baseline.

**Prompt:**

> Develop the project budget baseline from approved cost estimates. Identify authorization limits, contingency, reserve assumptions, expenditure controls and change thresholds.

**Artifact:** `\[PROJECT-ID]-BL-2007.30 Budget Baseline`

\---

### GOV-05-2008.1 — Manage Quality Assurance

**Purpose:** Define quality standards and assurance mechanisms.

**Prompt:**

> Define the Quality Management Plan for \[PROJECT]. Establish measurable quality criteria, review methods, testing requirements, defect classification, acceptance thresholds and independent assurance requirements. Ensure quality controls are appropriate to the project's actual risk rather than copied from another project.

**Artifact:** `\[PROJECT-ID]-BL-2008.10 Quality Management Plan`

\---

### RES-01-2009.1 — Plan Resource Management

**Purpose:** Define required human, technical and organizational resources.

**Prompt:**

> Develop the Resource Management Plan for \[PROJECT]. Identify people, skills, environments, infrastructure, software, services, tools and other resources required for each major work package. Identify resource constraints and fallback options.

**Artifact:** `\[PROJECT-ID]-BL-2009.10 Resource Management Plan`

\---

### RES-02-2009.2 — Estimate Resources

**Purpose:** Determine the resources required to perform the planned work.

**Prompt:**

> Estimate the resources required for each WBS work package and Task Packet. Map required capabilities to available personnel or agents. Identify capability gaps and avoid assigning work solely because an agent is available.

**Artifact:** `\[PROJECT-ID]-BL-2009.20 Resource Assignment Matrix`

\---

### STK-03-2010.1 — Plan Communications Management

**Purpose:** Establish controlled project communications.

**Prompt:**

> Develop the Communications Management Plan. Define stakeholder information needs, communication channels, frequency, ownership, escalation, confidentiality, record retention and authoritative communication locations. Prevent material decisions from existing only in informal conversation.

**Artifact:** `\[PROJECT-ID]-BL-2010.10 Communications Management Plan`

\---

### RSK-01-2011.1 — Plan Risk Management

**Purpose:** Establish the project's risk framework.

**Prompt:**

> Develop the Risk Management Plan for \[PROJECT]. Define risk categories, scoring methodology, ownership, escalation, response strategies, review frequency, residual-risk handling and acceptance authority.

**Artifact:** `\[PROJECT-ID]-BL-2011.10 Risk Management Plan`

\---

# PHASE 2 — RISK ANALYSIS, SOURCING AND ENGAGEMENT

### RSK-02-3011.2 — Identify Risks

**Purpose:** Identify threats, opportunities, uncertainties and failure modes.

**Prompt:**

> Independently identify project risks across requirements, architecture, implementation, security, operations, dependencies, schedule, cost, resources, quality and stakeholder factors. Include edge cases, failure states and foreseeable misuse. Do not restrict the analysis to previously known risks.

**Artifact:** `\[PROJECT-ID]-RSK-3011.20 Risk Register`

\---

### RSK-03-3011.3 — Perform Risk Analysis

**Purpose:** Analyze probability, impact and exposure.

**Prompt:**

> Analyze every material project risk using the approved risk methodology. Identify probability, impact, exposure, affected objectives, dependencies and uncertainty. Where quantitative analysis is justified, state the model and assumptions. Identify risks that cannot be meaningfully quantified.

**Artifact:** `\[PROJECT-ID]-RSK-3011.30 Risk Analysis`

\---

### RSK-04-3011.5 — Plan Risk Responses

**Purpose:** Define risk treatment.

**Prompt:**

> Develop response plans for material risks. Prefer prevention and architectural mitigation where appropriate. Define contingency, fallback, rollback, monitoring and escalation mechanisms. Assign an accountable owner and verification method to each material response.

**Artifact:** `\[PROJECT-ID]-RSK-3011.50 Risk Response Plan`

\---

### GOV-03-3012.1 — Plan Sourcing Strategy

**Purpose:** Govern external suppliers, libraries, services and dependencies.

**Prompt:**

> Develop the Sourcing Strategy for \[PROJECT]. Identify external dependencies, suppliers, libraries, APIs, services and licenses. Evaluate security, reliability, compatibility, cost, ownership, lock-in and lifecycle risk. No external dependency shall be introduced without appropriate approval.

**Artifact:** `\[PROJECT-ID]-BL-3012.10 Sourcing Strategy`

\---

### STK-02-3013.2 — Plan Stakeholder Engagement

**Purpose:** Establish stakeholder participation and approval mechanisms.

**Prompt:**

> Develop the Stakeholder Engagement Plan. Define stakeholder expectations, engagement methods, review points, demonstrations, decision gates, escalation paths and approval requirements. Identify stakeholders whose approval is required before material changes proceed.

**Artifact:** `\[PROJECT-ID]-BL-3013.20 Stakeholder Engagement Plan`

\---

# PHASE 3 — EXECUTION

### GOV-04-4004.3 — Manage Project Execution

**Purpose:** Execute approved work within authorized scope.

**Prompt:**

> Act as the authorized implementation lead for the assigned Task Packet. Implement only the approved work. Review all authoritative instructions and dependencies before modification. Do not expand scope, alter architecture, bypass controls or introduce unrelated refactoring. Produce implementation evidence, tests and a precise change summary.

**Artifact:** `\[PROJECT-ID]-BL-4004.30 Implementation Evidence`

\---

### GOV-06-4004.4 — Manage Project Knowledge

**Purpose:** Capture durable project knowledge.

**Prompt:**

> Review project decisions, discoveries, failures, successful approaches and architectural changes. Determine which information should become an ADR, Decision Ledger entry, reusable pattern, lesson learned, Task Packet improvement or project documentation update.

**Artifact:** `\[PROJECT-ID]-BL-4004.40 Knowledge \& ADR Register`

\---

### RES-03-5009.3 — Acquire Resources

**Purpose:** Provision resources and authorize Task Packet execution.

**Prompt:**

> Provision the resources required by the approved Task Packet. Verify branch/workspace, tools, permissions, environment, dependencies and agent assignment. Do not grant broader access than required for the task.

**Artifact:** `\[PROJECT-ID]-BL-5009.30 Resource \& Task Dispatch Record`

\---

### RES-04-6009.4 — Lead the Team

**Purpose:** Maintain team and agent alignment.

**Prompt:**

> Reconcile current architecture, requirements, decisions, risks and implementation context across the project team and collaborating agents. Identify contradictory instructions or stale context. Do not silently resolve conflicting authoritative instructions; escalate them for decision.

**Artifact:** `\[PROJECT-ID]-BL-6009.40 Context Alignment Record`

\---

### STK-05-6010.2 — Manage Communications

**Purpose:** Maintain controlled execution communications.

**Prompt:**

> Produce the required project communication record for the current work package. Summarize completed work, decisions, changes, risks, blockers, evidence and next gates. Ensure that material decisions are transferred into the authoritative project record.

**Artifact:** `\[PROJECT-ID]-BL-6010.20 Execution Communication Record`

\---

### RSK-05-6011.6 — Implement Risk Responses

**Purpose:** Execute approved risk treatments.

**Prompt:**

> Implement only approved risk responses associated with the identified risk records. Verify the intended mitigation, document evidence, assess residual risk and escalate if the response fails or introduces a new material risk.

**Artifact:** `\[PROJECT-ID]-BL-6011.60 Risk Response Implementation Record`

\---

### STK-04-6013.3 — Manage Stakeholder Engagement

**Purpose:** Maintain stakeholder participation throughout execution.

**Prompt:**

> Prepare the current stakeholder progress report. Show approved scope, completed work, upcoming gates, unresolved decisions, material risks, required approvals and changes. Avoid reporting activity as progress unless the activity produced an accepted deliverable or verified outcome.

**Artifact:** `\[PROJECT-ID]-BL-6013.30 Stakeholder Progress Report`

\---

# PHASE 4 — MONITORING AND CONTROLLING

### GOV-08-7004.5 — Monitor and Control Project Performance

**Purpose:** Monitor overall project performance.

**Prompt:**

> Assess project performance against approved scope, schedule, cost, quality, risk, resource, communication and architecture baselines. Identify variances, trends, threshold breaches and emerging problems. Distinguish factual performance evidence from interpretation.

**Artifact:** `\[PROJECT-ID]-BL-7004.50 Performance Monitoring Report`

\---

### GOV-07-7004.6 — Assess and Implement Changes

**Purpose:** Control project changes.

**Prompt:**

> Review the proposed change against the approved baseline. Determine impact on requirements, architecture, scope, schedule, cost, quality, risk, resources, stakeholders and operations. Recommend APPROVE, REJECT, DEFER or REQUEST-FURTHER-ANALYSIS. No implementation shall begin without the required authorization.

**Artifact:** `\[PROJECT-ID]-BL-7004.60 Change Control Dossier`

\---

### SCP-06-7005.5 — Validate Scope

**Purpose:** Confirm deliverables satisfy approved scope.

**Prompt:**

> Validate each completed deliverable against its approved requirements and acceptance criteria. Identify unmet criteria, deviations, assumptions and evidence gaps. Do not approve a deliverable merely because the implementation appears functional.

**Artifact:** `\[PROJECT-ID]-BL-7005.50 Scope Validation Report`

\---

### SCP-05-7005.6 — Monitor and Control Scope

**Purpose:** Prevent unauthorized scope expansion.

**Prompt:**

> Audit current work and changes against the approved scope baseline and Task Packet boundaries. Identify scope creep, unauthorized refactoring, unapproved dependencies and work performed without authorization.

**Artifact:** `\[PROJECT-ID]-BL-7005.60 Scope Control Audit`

\---

### SCH-03-8006.6 — Monitor and Control Schedule

**Purpose:** Maintain schedule control.

**Prompt:**

> Compare actual progress against the approved schedule and dependencies. Identify milestone variance, blocked activities, critical-path changes and emerging schedule risk. Recommend corrective action where required.

**Artifact:** `\[PROJECT-ID]-BL-8006.60 Schedule Control Report`

\---

### FIN-04-8007.4 — Monitor and Control Finances

**Purpose:** Control financial and resource expenditure.

**Prompt:**

> Compare actual project expenditure and resource consumption with the approved budget. Identify variances, unauthorized expenditure, forecast changes and efficiency opportunities. Do not classify cost savings as beneficial if they introduce material quality, security or operational risk.

**Artifact:** `\[PROJECT-ID]-BL-8007.40 Financial Control Report`

\---

### RES-05-8009.6 — Monitor and Control Resourcing

**Purpose:** Ensure resources remain available and correctly assigned.

**Prompt:**

> Audit project resource availability, permissions, environments, workspaces, branches and assignments. Identify stale resources, excessive permissions, unavailable dependencies and resource bottlenecks.

**Artifact:** `\[PROJECT-ID]-BL-8009.60 Resource Control Audit`

\---

### STK-07-8010.3 — Monitor Communications

**Purpose:** Ensure communications remain accurate, secure and auditable.

**Prompt:**

> Audit project communications and logs for completeness, confidentiality, traceability and appropriate retention. Verify that sensitive information is not exposed and that material decisions are recorded in authoritative locations.

**Artifact:** `\[PROJECT-ID]-BL-8010.30 Communication \& Auditability Report`

\---

### RSK-06-8011.7 — Monitor Risks

**Purpose:** Continuously monitor risk exposure and response effectiveness.

**Prompt:**

> Reassess active risks against current project conditions. Verify that risk responses remain effective, identify newly emerged risks and determine residual exposure. Test recovery and reconciliation mechanisms where applicable.

**Artifact:** `\[PROJECT-ID]-BL-8011.70 Risk Monitoring \& Recovery Report`

\---

### STK-06-8013.4 — Monitor Stakeholder Engagement

**Purpose:** Confirm operational readiness and stakeholder release approval.

**Prompt:**

> Compile the release-readiness dossier. Confirm acceptance criteria, outstanding risks, test results, security review, operational readiness, rollback capability, monitoring, support ownership, stakeholder approval and release authority. Do not recommend production release where a mandatory gate remains incomplete.

**Artifact:** `\[PROJECT-ID]-BL-8013.40 Release Readiness Dossier`

\---

# PHASE 5 — CLOSING

### GOV-09-9004.7 — Close Project or Phase

**Purpose:** Formally close the project or phase.

**Prompt:**

> Prepare the controlled project or phase closure. Verify that deliverables were accepted, outstanding issues were resolved or formally accepted, documentation was completed, knowledge was retained, operational ownership was transferred, temporary resources were retired and release evidence was preserved. Confirm that all required approvals are recorded before declaring closure.

**Artifact:** `\[PROJECT-ID]-BL-9004.70 Project / Phase Closure Record`

\---

# 18\. ARCHITECTURAL ENGINEERING MODEL

The PBIM architecture shall be independently evaluated through four controlled stages.

## 18.1 AEA — Architectural Engineering Analysis

AEA asks:

* Is the architecture coherent?
* Are assumptions explicit?
* Are dependencies understood?
* Are authority boundaries safe?
* Are controls enforceable?
* Are identifiers unambiguous?
* Are prompts actionable?
* Are evidence requirements adequate?
* Are there circular dependencies?
* Can the model be implemented?
* Can the model be maintained?
* Can the model scale?

AEA is an analysis activity, not an approval.

\---

## 18.2 AEV — Architectural Engineering Verification

AEV determines whether the proposed architecture satisfies the required criteria.

Permitted outcomes:

```text
APPROVE
CONDITIONALLY APPROVE
REVISION REQUIRED
BLOCK
```

Every finding must identify:

```text
Finding ID
Source
Affected Section
Severity
Finding
Evidence
Required Action
Verification Method
Status
```

\---

## 18.3 AEC — Adversarial Engineering Challenge

AEC shall attempt to break the proposed architecture.

The challenge shall examine at minimum:

* authority escalation;
* self-approval;
* identifier collisions;
* prompt ambiguity;
* conflicting instructions;
* evidence forgery;
* evidence loss;
* scope leakage;
* dependency failure;
* agent disagreement;
* emergency bypass;
* change-control bypass;
* incomplete handoff;
* release authorization failure;
* operational failure;
* rollback failure.

A successful adversarial finding is not itself a project failure.

It demonstrates that the assurance mechanism found a weakness before implementation.

\---

## 18.4 AECC — Architectural Engineering Challenge Closure

Every AEC finding must be dispositioned.

```text
RESOLVED
ACCEPTED
DEFERRED
NON-ISSUE
BLOCKING
```

A finding may only be marked RESOLVED when evidence demonstrates that the proposed remediation addresses the actual failure mode.

\---

# 19\. IMPLEMENTATION AUTHORIZATION GATE

Implementation may begin only after applicable gates are satisfied.

Minimum conditions:

* approved project charter;
* approved scope;
* approved requirements;
* approved architecture;
* approved implementation approach;
* identified material risks;
* defined risk responses;
* defined rollback/recovery;
* defined acceptance criteria;
* required resources available;
* Task Packet issued;
* required independent challenge completed;
* required approvals recorded;
* change authority established;
* evidence location established.

Production implementation requires an additional release authorization gate.

\---

# 20\. IMPLEMENTATION EVIDENCE STANDARD

Each material implementation shall retain sufficient evidence to demonstrate:

1. what was changed;
2. why it was changed;
3. who/what performed the change;
4. what requirements authorized it;
5. what files or components were affected;
6. what tests were executed;
7. what results were obtained;
8. what review occurred;
9. what risks were identified;
10. what approval authorized progression.

\---

# 21\. QUALITY CONTROL

Quality assurance should address, as applicable:

* requirements compliance;
* functional correctness;
* security;
* performance;
* compatibility;
* maintainability;
* accessibility;
* reliability;
* observability;
* recoverability;
* documentation;
* operational readiness.

Quality thresholds must be established by project risk and requirements.

PBIM shall not impose arbitrary numerical thresholds merely for the appearance of rigor.

\---

# 22\. SECURITY AND PRIVILEGE CONTROL

Security requirements shall be integrated into:

* architecture;
* requirements;
* implementation;
* testing;
* evidence;
* operations;
* release;
* closure.

Principles:

* least privilege;
* separation of duties;
* controlled credentials;
* protected secrets;
* auditable privileged actions;
* secure evidence handling;
* controlled emergency access;
* timely privilege revocation.

\---

# 23\. PROJECT KNOWLEDGE MANAGEMENT

The project shall retain reusable knowledge including:

* important decisions;
* rejected alternatives;
* ADRs;
* successful approaches;
* failed approaches;
* lessons learned;
* reusable Task Packets;
* reusable prompts;
* testing lessons;
* operational lessons;
* known failure modes.

The objective is institutional learning rather than repeated rediscovery.

\---

# 24\. MONITORING FRAMEWORK

Project monitoring shall consider at least:

### Scope

* approved requirements;
* unauthorized work;
* scope variance.

### Schedule

* milestones;
* dependencies;
* delays;
* critical-path changes.

### Cost

* actual expenditure;
* forecast;
* variance;
* resource consumption.

### Quality

* acceptance;
* defects;
* test evidence;
* review findings.

### Risk

* new risks;
* residual exposure;
* response effectiveness.

### Resources

* personnel;
* agents;
* infrastructure;
* tools;
* permissions.

### Stakeholders

* approvals;
* engagement;
* unresolved decisions.

### Architecture

* baseline conformity;
* unauthorized architectural changes;
* technical debt.

### Governance

* approvals;
* evidence;
* change control;
* stop conditions.

\---

# 25\. AGENT CHALLENGE BEFORE SIGNIFICANT BUILD

Before significant implementation, an independent challenge shall attempt to identify ways the implementation could fail.

### Standard Challenge Prompt

> Act as an independent adversarial reviewer. Attempt to break the proposed implementation before it is built or released.
>
> Examine requirements, architecture, dependencies, security, data integrity, authorization, concurrency, compatibility, rollback, testing, observability, maintainability, scalability and operational impact.
>
> Identify:
>
> 1. blocking defects;
> 2. high-risk weaknesses;
> 3. ambiguous requirements;
> 4. hidden dependencies;
> 5. unsafe assumptions;
> 6. missing controls;
> 7. inadequate evidence;
> 8. failure-recovery weaknesses.
>
> Do not modify production code.
>
> Return evidence-based findings and state exactly what must be resolved before implementation may proceed.

\---

# 26\. CHANGE AND EXCEPTION CONTROL

Exceptions shall not silently become permanent policy.

Every exception shall contain:

```text
EXCEPTION ID:
DATE:
REQUESTOR:
AFFECTED CONTROL:
REASON:
RISK:
ALTERNATIVES:
TEMPORARY MITIGATION:
APPROVER:
EXPIRATION:
REVIEW DATE:
CLOSURE:
```

Expired exceptions shall automatically become review items.

\---

# 27\. EMERGENCY CONTROL

Emergency execution may be permitted only where delay creates greater material risk.

Emergency execution must still preserve:

* identity;
* authorization;
* minimum required evidence;
* scope boundary;
* rollback/recovery;
* retrospective review.

Emergency authority shall not become a permanent bypass mechanism.

\---

# 28\. PROJECT INITIALIZATION CHECKLIST

Before project activation:

### Project

* \[ ] Project identifier established
* \[ ] Project repositories established
* \[ ] Documentation location established
* \[ ] Project Charter approved
* \[ ] Project Proposal approved
* \[ ] Project Template approved

### Governance

* \[ ] Sponsor established
* \[ ] Project Manager established
* \[ ] Architecture Authority established
* \[ ] Approval Authority established
* \[ ] Change Authority established
* \[ ] Escalation path established

### Agents

* \[ ] Lead Agent assigned
* \[ ] Collaborating agents assigned
* \[ ] Agent responsibilities defined
* \[ ] Agent access defined
* \[ ] Agent instructions reviewed
* \[ ] Relevant AGENTS.md / equivalent instructions reviewed

### Architecture

* \[ ] Architecture baseline established
* \[ ] ADR location established
* \[ ] Decision Ledger established
* \[ ] Architecture ownership established

### Controls

* \[ ] Identifier Registry established
* \[ ] Risk Register established
* \[ ] Change Control established
* \[ ] Evidence control established
* \[ ] Quality controls established
* \[ ] Stop conditions established
* \[ ] Release controls established

\---

# 29\. PROJECT FRAMEWORK ACTIVATION

The project-management framework becomes operational only after initialization confirms:

* project team assembled;
* communication channels active;
* repositories operational;
* documentation controls operational;
* Task Packet mechanism operational;
* identifier registry operational;
* Decision Ledger operational;
* ADR system operational;
* risk register active;
* change control active;
* review schedule established;
* reporting schedule established.

\---

# 30\. PROJECT EXECUTION CONTROL

Once activated:

```text
PBIM
 ↓
Project Proposal
 ↓
Project Template
 ↓
Project Initialization
 ↓
Task Packet
 ↓
Authorized Execution
 ↓
Independent Review
 ↓
Acceptance
 ↓
Release / Operational Handoff
```

PBIM remains the governing integration framework.

The Project Template becomes the project-specific execution framework.

\---

# 31\. PBIM HANDOFF PACKAGE

The PBIM handoff shall contain, as applicable:

1. approved PBIM;
2. Project Proposal;
3. Project Template;
4. architecture baseline;
5. ADR register;
6. Decision Ledger;
7. requirements baseline;
8. scope baseline;
9. risk register;
10. stakeholder register;
11. schedule;
12. resource plan;
13. communication plan;
14. Task Packet register;
15. agent responsibility matrix;
16. repository map;
17. evidence register;
18. quality controls;
19. change-control process;
20. implementation authorization requirements;
21. AEA evidence;
22. AEV evidence;
23. AEC evidence;
24. AECC closure evidence.

\---

# 32\. PBIM COMPLETION GATE

The PBIM development stage may be declared complete only when:

* \[ ] PBIM baseline reviewed;
* \[ ] identifiers verified;
* \[ ] prompts verified;
* \[ ] governance verified;
* \[ ] project-management mappings verified;
* \[ ] AEA completed;
* \[ ] AEV completed;
* \[ ] AEC completed;
* \[ ] AECC completed;
* \[ ] material findings resolved or formally accepted;
* \[ ] project-specific configuration completed;
* \[ ] approval authorities confirmed;
* \[ ] implementation boundary established;
* \[ ] evidence controls established;
* \[ ] handoff package complete.

### Required Approval

```text
Project Manager:        \[ ] APPROVED
Lead Agent:             \[ ] APPROVED
Architecture Authority: \[ ] APPROVED
Required Reviewers:     \[ ] APPROVED
Project Sponsor:        \[ ] APPROVED
```

### Final Status

```text
\[ PENDING ]
\[ CONDITIONALLY APPROVED ]
\[ APPROVED ]
\[ REJECTED ]
```

\---

# 33\. PBIM REVISION POLICY

PBIM revisions shall preserve traceability.

A revision shall not silently delete an existing governance requirement.

Where a control is removed or materially changed, the revision record shall identify:

* previous control;
* reason for change;
* evidence supporting change;
* impact;
* replacement control;
* approving authority.

Recommended versioning:

```text
MAJOR.MINOR.PATCH

MAJOR = structural/governance model change
MINOR = significant new capability or process refinement
PATCH = correction with no material governance change
```

\---

# 34\. PBIM MANUAL CONFIGURATION REQUIREMENTS

Before formal AEA of the project-specific PBIM, the Project Manager / Lead Agent shall manually verify:

1. all project placeholders;
2. all identifiers;
3. process-anchor mappings;
4. organizational terminology;
5. governance authorities;
6. approval authorities;
7. agent assignments;
8. repository locations;
9. documentation locations;
10. applicable standards;
11. project-specific constraints;
12. project-specific risks;
13. evidence requirements;
14. revision policy;
15. stop conditions;
16. implementation boundaries;
17. release authority;
18. exception authority;
19. retention requirements;
20. dependencies.

No placeholder should remain where its absence could materially change project behavior.

\---

# 35\. GENERICITY RULE

The PBIM master template SHALL NOT contain assumptions that belong exclusively to one project.

Examples of information that must normally remain parameterized:

* product names;
* programming languages;
* databases;
* payment providers;
* cloud providers;
* repositories;
* specific AI vendors;
* specific APIs;
* specific infrastructure;
* specific financial models;
* specific security mechanisms.

Project-specific implementation requirements belong in the Project Proposal, Project Template, architecture baseline or Task Packets unless they are genuinely part of the universal PBIM governance model.

\---

# 36\. TRACEABILITY MODEL

Material project information should be traceable through:

```text
OBJECTIVE
   ↓
REQUIREMENT
   ↓
SCOPE
   ↓
WBS
   ↓
TASK PACKET
   ↓
IMPLEMENTATION
   ↓
EVIDENCE
   ↓
TEST / REVIEW
   ↓
ACCEPTANCE
   ↓
RELEASE
```

Architecture decisions should additionally trace through:

```text
QUESTION
   ↓
ANALYSIS
   ↓
OPTIONS
   ↓
DECISION
   ↓
ADR
   ↓
IMPLEMENTATION
   ↓
VERIFICATION
```

\---

# 37\. GOVERNANCE STOP CONDITIONS

A project SHALL stop progression when any applicable condition exists:

```text
UNKNOWN REQUIREMENT
CONFLICTING AUTHORITY
UNKNOWN ARCHITECTURAL OWNER
MISSING MATERIAL EVIDENCE
UNRESOLVED CRITICAL SECURITY ISSUE
MISSING CRITICAL DEPENDENCY
UNAUTHORIZED MATERIAL CHANGE
MISSING REQUIRED APPROVAL
FAILED ACCEPTANCE CRITERION
FAILED MANDATORY TEST
UNSAFE RELEASE CONDITION
UNCONTROLLED SCOPE EXPANSION
UNRESOLVED ARCHITECTURAL CHALLENGE
```

The stop must be recorded as an issue, not bypassed through informal instruction.

\---

# 38\. PBIM OPERATING PRINCIPLES

The following principles govern the use of PBIM:

### 1\. Evidence over assertion

Claims about project state should be supported by evidence.

### 2\. Authority before execution

Work must have an identifiable authorization path.

### 3\. Independent challenge

Important architecture and implementation decisions should be challenged before irreversible action.

### 4\. Explicit boundaries

Scope, responsibility and authority must have identifiable limits.

### 5\. Controlled change

Material changes require impact assessment and authorization.

### 6\. Reversible progression

Where practical, decisions and implementation should preserve rollback or recovery.

### 7\. Traceability

Important decisions must be traceable from requirement through implementation and evidence.

### 8\. Separation of governance and execution

The executor should not become the sole authority over its own work.

### 9\. Project-specific configuration

The generic PBIM should remain reusable.

### 10\. Continuous learning

Project knowledge should improve subsequent projects.

\---

# 39\. PBIM FINAL HANDOFF

The PBIM is ready to enter project-specific Architectural Engineering only when this document has undergone the required manual configuration.

The intended next sequence is:

```text
THIS GENERIC PBIM
       ↓
MANUAL PROJECT CONFIGURATION
       ↓
PROJECT-SPECIFIC PBIM
       ↓
AEA QUERY
       ↓
AEA REPORTS
       ↓
AEV BASELINE
       ↓
AEV REVIEW / REVISION
       ↓
AEC ADVERSARIAL DUEL
       ↓
AEC RESULTS
       ↓
AECC CLOSURE
       ↓
PBIM APPROVAL
       ↓
PROJECT PROPOSAL
```

This document is therefore **not an implementation authorization**.

It is the reusable governance baseline from which a project-specific PBIM is prepared.

\---

# 40\. PBIM REVISION HISTORY

|Version|Date|Author|Description|Status|
|-|-|-|-|-|
|v1.00.00|2026-10-03|\[Original]|Initial PBIM operating model|Superseded|
|v1.02.00|2026-10-03|\[Development Team]|Governance, evidence and control expansion|Superseded|
|v1.03.00|2026-10-03|\[Development Team]|Generic implementation-ready baseline|Superseded|
|v1.04.00|2026-10-04|\[Development Team]|Generic governance and architectural refinement|Superseded|
|v1.05.00|2026-10-03/04|\[Development Team]|40-process operational master model|Superseded|
|**v2.00.00**|**2026-10-04**|**\[Lead / PBIM Development Team]**|**Generic implementation-ready master baseline; identifiers, prompts, governance and AEA/AEV/AEC lifecycle consolidated**|**MANUAL UPDATE REQUIRED**|

\---

# END OF PROJECT BASE INTEGRATION MANAGER \[PBIM]

