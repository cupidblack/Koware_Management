Below is a revised generic PBIM document that incorporates the structure, governance logic, and practical implementation patterns from the repository’s PBIM materials while making it usable as a modern, implementation-ready template for future project work.

This version keeps the original project-identity pattern, updates the structure for implementation readiness, and improves the prompts so that lead and collaborating agents can work consistently and produce higher-quality outputs.

# PROJECT BASE INTEGRATION MANAGER \[PBIM]

TIMESTAMP: 2026-10-04T00:00:00Z

# \[PROJECT-NAME: <PROJECT NAME> \[PN]]

# \[PROJECT-BASE: <ORGANIZATION> \[BASE]]

# \[PROJECT-IDENTIFIER: <BASE-IDENTIFIER>-<PROJECT-ABBREVIATION>]

## Project Management Office

## Independent Assignments \& Project Development National Department

## \[IAPD National]

### \[KOWARE GROUP]

### \[\[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[IDENTIFIER]]

BASE PROJECT R\&D FOLDER:
<REPOSITORY DEVELOPMENT FOLDER URL>

BASE PROJECT R\&D DOCS FOLDER:
<REPOSITORY DOCS FOLDER URL>

BASE PROJECT PRODUCTION REPOSITORY:
<PRODUCTION REPOSITORY URL>

LEAD AGENT:
<PRIMARY AGENT NAME>

SUPPORTING AGENTS:
<AGENT 1>, <AGENT 2>, <AGENT 3>, <AGENT 4>

\---

# 1\. Purpose

The Project Base Integration Manager (PBIM) is a project governance, delivery, and assurance model for creating, validating, and maintaining structured project execution across technical, operational, and organizational domains.

PBIM provides:

* a consistent project identity model;
* governance boundaries between authority, responsibility, and technical privilege;
* structured agent and collaborator workflows;
* evidence-based architectural verification;
* controlled implementation and release processes;
* project challenge and review layers that reduce drift, risk, and unauthorized change.

PBIM is designed to work in modern project management environments and may be adapted for software engineering, product delivery, infrastructure, operational transformation, and hybrid project teams.

\---

# 2\. Governance Model

PBIM follows the model:

Governance → Assurance → Execution

This means:

* Governance establishes authority, boundaries, controls, and approval mechanisms.
* Assurance tests governance, architecture, implementation quality, and risk posture.
* Execution performs authorized work within defined scope.

No technical capability creates governance authority.
No agent role automatically creates independence.
No project consensus replaces a formal authority structure.
No implementation artifact replaces a verified baseline.

\---

# 3\. PBIM Operating Principles

PBIM shall be based on the following principles:

1. Authority is explicit and traceable.
2. Technical privilege is separated from governance authority.
3. Scope is controlled and measurable.
4. Evidence is preserved and independently reviewable.
5. Risk is cumulative and assessed at the correct governance layer.
6. Baseline drift is detected and remediated.
7. Architectural challenge is independent and adversarial.
8. Stop, reset, pause, and emergency controls are formalized.
9. Production release requires evidence-backed operational readiness.
10. Project control may not be created by convenience, informal consensus, or undocumented exception.

\---

# 4\. Project Identity and Naming Convention

The PBIM naming pattern should remain explicit, stable, and machine-readable.

General format:

\[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[IDENTIFIER]

Examples:

* \[KOWARE-IAPD-PMO]-\[BZJ]-\[PGBD]-\[GOV-01-0004.01]
* \[KOWARE-IAPD-PMO]-\[BZJ]-\[PGBD]-\[SCP-04-0004.02]
* \[KOWARE-IAPD-PMO]-\[BZJ]-\[PGBD]-\[AEV-01-0004.09]

Identifiers shall use:

* a project base prefix;
* a project abbreviation;
* a section type or process family;
* a sequence or numeric reference tied to the project lifecycle.

\---

# 5\. Project Structure

## 5.1 Repository Layout

A PBIM-enabled project should use:

* production repository for approved implementation;
* development repository or folder for planning, documentation, validation, and experimental work;
* docs folder for governance, prompts, analysis, challenge records, and evidence artifacts;
* branch structure for individual agent work;
* protected governance resources for baseline and authority records.

Example structure:

* /project-production
* /project-development
* /project-development/docs
* /project-development/docs/adr
* /project-development/docs/decision-ledger
* /project-development/docs/task-packets
* /project-development/docs/evidence
* /project-development/docs/reviews

## 5.2 Agent Workspace Model

Each major collaborator should operate in an assigned workspace or branch, with a clearly defined responsibility.

Example:

* Lead Agent: governance, synthesis, final review
* Agent A: analysis and architecture review
* Agent B: challenge testing and adversarial critique
* Agent C: implementation and technical verification
* Agent D: operational alignment and release readiness

\---

# 6\. PBIM Lifecycle Structure

The PBIM lifecycle shall align to modern project management domains while preserving the repository-specific numbering logic.

## 6.1 Governance Process Domain

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[GOV-01-0004.01]

### PBIM Document Creation

Purpose:
Establish all documentation, references, repository locations, project metadata, and required review inputs before work begins.

Required actions:

* compile all reference materials;
* validate repository structure;
* define project identity and governance boundaries;
* confirm lead and collaborator roles;
* prepare the project baseline and record conditions.

Prompt:
<<START PROMPT: LEAD AGENT>>
Review all available PBIM reference materials, repository structure, and project context. Compile a complete development baseline for the project. Identify all required governance, approval, and review inputs. Then draft a PBIM document that is implementation-ready and aligned with modern project management principles. Ensure that:

* section identifiers are consistent;
* prompts are actionable and appropriate to the assigned agent;
* governance structures are explicit;
* implementation requirements are traceable;
* the document is generic enough for reuse but specific enough for project execution.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[GOV-01-0004.02]

### Governance Charter and Authority Definition

Purpose:
Define authority, project sponsorship, decision rights, and constitutional or project-specific governance structure.

Required outputs:

* project charter summary;
* authority sources;
* accountability mapping;
* governance boundaries;
* escalation paths.

Prompt:
<<START PROMPT: LEAD AGENT>>
Develop the project governance charter using the project identity and operating principles defined in the PBIM framework. Describe:

* project authority model;
* governing stakeholders;
* decision-making hierarchy;
* authority boundaries;
* escalation procedures;
* required approvals;
* relationship between governance, assurance, and execution.
Ensure the output is clear, traceable, and implementable.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[GOV-01-0004.03]

### Role, Agent, and Independence Register

Purpose:
Define project roles, responsibilities, agent assignments, and independence criteria.

Required outputs:

* role matrix;
* collaboration model;
* independence checks for reviewers and challengers;
* conflict-of-interest declaration;
* repository permissions map.

Prompt:
<<START PROMPT: LEAD AGENT>>
Create a structured register of all project roles and collaborators. Include:

* person or agent name;
* function;
* authority level;
* technical access;
* governance responsibilities;
* conflict obligations;
* review or approval duties.
For any reviewer or challenger, explicitly document independence conditions and any material or organizational conflicts.
<<STOP PROMPT: LEAD AGENT>>

\---

## 6.2 Scope and Planning Domain

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[SCP-01-0004.04]

### Scope Definition and Project Baseline

Purpose:
Define the project scope, boundaries, assumptions, constraints, deliverables, and baseline conditions.

Required outputs:

* scope statement;
* in-scope and out-of-scope items;
* baseline assumptions;
* constraints and dependencies;
* delivery objectives.

Prompt:
<<START PROMPT: LEAD AGENT>>
Prepare the project scope statement and baseline definition. Describe the initial project purpose, expected deliverables, known assumptions, constraints, critical dependencies, and out-of-scope items. Identify what must be controlled as part of the project baseline versus what may remain operational or dynamic.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[SCP-02-0004.05]

### Task Packet and Change Control

Purpose:
Define the mechanism for task-scoped work packages, controlled change, and verification gating.

Required outputs:

* task packet template;
* scope manifest;
* review gate;
* dependency map;
* stop/resume logic.

Prompt:
<<START PROMPT: LEAD AGENT>>
Design a Task Packet model for the project. Each packet must clearly define:

* objective;
* direct file and artifact scope;
* generated file scope;
* dependencies;
* out-of-scope behavior;
* required approvals;
* verification criteria;
* stop and resume rules.
Ensure the model prevents scope drift and requires evidence before approval.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[SCP-03-0004.06]

### Project Proposal Creation

Purpose:
Generate the initial project proposal and supporting materials before implementation begins.

Required outputs:

* project proposal;
* purpose and objectives;
* stakeholder context;
* business or operational rationale;
* preliminary scope and constraints.

Prompt:
<<START PROMPT: LEAD AGENT>>
Create the initial project proposal for the defined initiative. Include project purpose, business context, objectives, constraints, dependencies, expected outcomes, and the initial project assumptions. Make the proposal clear enough for architecture review and formal approval.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[SCP-04-0004.07]

### Project Proposal Development

Purpose:
Refine the proposal using structured analysis and challenge review before implementation.

Required outputs:

* revised proposal;
* architecture considerations;
* review notes;
* issue log;
* consolidation of feedback.

Prompt:
<<START PROMPT: LEAD AGENT>>
Refine the initial project proposal by reviewing assumptions, dependencies, risks, and potential architectural weaknesses. Consolidate review findings into a revised proposal that is more robust and closer to implementation readiness.
<<STOP PROMPT: LEAD AGENT>>

\---

## 6.3 Risk, Quality, and Evidence Domain

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[RSK-01-0004.08]

### Risk Register and Classification

Purpose:
Identify, classify, and track project risk at the appropriate governance level.

Required outputs:

* risk register;
* risk profile;
* impact assessment;
* mitigation or control plan;
* ownership.

Prompt:
<<START PROMPT: LEAD AGENT>>
Develop the project risk register. Include:

* risk description;
* category;
* trigger;
* likely impact;
* affected scope;
* control or mitigation;
* owner;
* decision authority.
Classify risks according to project impact and cumulative effect across tasks, systems, or workstreams.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[RSK-02-0004.09]

### Quality and Control Baseline

Purpose:
Create the project quality expectations and control framework.

Required outputs:

* quality baseline;
* acceptance criteria;
* review gates;
* test and verification criteria;
* design standards or conventions.

Prompt:
<<START PROMPT: LEAD AGENT>>
Define the quality and control baseline used across project execution. Specify:

* quality objectives;
* acceptance standards;
* verification points;
* required review gates;
* known quality risks;
* reporting expectations.
The result should be suitable for technical and governance review.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[RES-01-0004.10]

### Evidence and Integrity Registry

Purpose:
Preserve the evidence trail used to support design, implementation, review, and release decisions.

Required outputs:

* evidence register;
* hash or immutable reference records;
* provenance metadata;
* retention rules;
* retrieval path.

Prompt:
<<START PROMPT: LEAD AGENT>>
Create an evidence management approach that ensures the project preserves raw evidence and reviewable artifacts. Describe:

* what evidence is required;
* where it is stored;
* how it is hashed or anchored;
* who verifies it;
* how it is retained;
* how it is reviewed.
The guidance should support independent verification and auditability.
<<STOP PROMPT: LEAD AGENT>>

\---

## 6.4 Architecture and Challenge Domain

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[AEV-01-0004.11]

### Architectural Engineering Analysis Query

Purpose:
Prepare analytical questions that test the architecture, process model, and control assumptions before final approval.

Required outputs:

* architecture analysis prompt set;
* review criteria;
* challenge questions;
* expected findings;
* verification hypotheses.

Prompt:
<<START PROMPT: COLLABORATING AGENT>>
Review the project proposal, governance model, and task control design. Identify architectural assumptions, dependency risks, and control weaknesses. Generate a structured analysis using the PBIM framework. Focus on:

* authority boundaries;
* security and governance separation;
* implementation barriers;
* operational readiness risks;
* evidence and independence gaps;
* challenge or attack surfaces.
Provide findings in a structured, actionable format.
<<STOP PROMPT: COLLABORATING AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[AEV-02-0004.12]

### Architectural Engineering Verification Statement

Purpose:
Document whether the architecture is acceptable, conditional, or blocked based on independent review and evidence.

Required outputs:

* verification status;
* findings summary;
* residual risks;
* approval conditions;
* unresolved blockers.

Prompt:
<<START PROMPT: LEAD AGENT>>
Review all analysis reports and challenge outputs. Create a controlled Architectural Engineering Verification Statement that states:

* whether the architecture is approved, conditionally approved, or rejected;
* the key findings and their materiality;
* required remediation items;
* unresolved dependencies or blockers;
* evidence requirements before production authorization.
Ensure the statement is explicit and evidence-based.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[AEV-03-0004.13]

### Adversarial Engineering Challenge

Purpose:
Stress-test the architecture against realistic failure modes and governance circumvention attempts.

Required outputs:

* adversarial challenge packet;
* attack scenarios;
* use-case and edge-case review;
* failure-mode summary;
* required remediation.

Prompt:
<<START PROMPT: COLLABORATING AGENT>>
Act as an adversarial reviewer and challenge the project architecture as if you are testing for failure, bypass, drift, sabotage, privilege escalation, governance circumvention, or unreviewed operational risk. Evaluate:

* control bypasses;
* agent overreach;
* authority confusion;
* change control gaps;
* evidence tampering scenarios;
* operational readiness holes.
Provide findings in a direct, evidence-based, non-defensive format.
<<STOP PROMPT: COLLABORATING AGENT>>

\---

## 6.5 Execution, Monitoring, and Release Domain

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[EXEC-01-0004.14]

### Execution and Delivery Control

Purpose:
Define how authorized work is dispatched, tracked, and verified.

Required outputs:

* execution workflow;
* delivery gates;
* review checkpoints;
* stop conditions;
* change and recovery controls.

Prompt:
<<START PROMPT: LEAD AGENT>>
Define the execution roadmap for the project, including:

* work dispatch method;
* stage gates;
* review points;
* quality inspection points;
* stop and recovery conditions;
* evidence requirements at each stage.
The process should be compatible with modern project management practice and execution control.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[MNT-01-0004.15]

### Monitoring, Drift Detection, and Controls

Purpose:
Track project drift and identify when the project is deviating from the approved baseline.

Required outputs:

* drift register;
* control log;
* baseline integrity checks;
* issue tracking;
* correction and resolution workflow.

Prompt:
<<START PROMPT: LEAD AGENT>>
Create a monitoring and drift detection framework for the project. Include:

* baseline comparison logic;
* control failure detection;
* drift severity levels;
* evidence needed for drift resolution;
* approver roles and escalation paths.
The framework should support both technical and governance drift.
<<STOP PROMPT: LEAD AGENT>>

\---

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[RLS-01-0004.16]

### Release Readiness and Production Authorization

Purpose:
Determine whether the project is ready for production release and what evidence must exist before final approval.

Required outputs:

* release checklist;
* readiness statement;
* rollback plan;
* support model;
* approval record.

Prompt:
<<START PROMPT: LEAD AGENT>>
Prepare the operational readiness and release authorization package. Include:

* release scope;
* environment impact;
* rollback plan;
* data and service continuity considerations;
* monitoring and owner assignments;
* release risks and mitigations;
* approval record.
Make explicit what evidence must exist before production authorization.
<<STOP PROMPT: LEAD AGENT>>

\---

## 6.6 Closure and Handover

### \[KOWARE-IAPD-PMO]-\[BASE-IDENTIFIER]-\[PROJECT-ABBREVIATION]-\[GOV-09-0004.17]

### Project Closure and Handover

Purpose:
Formalize project closure, evidence retention, operational transition, and final governance record.

Required outputs:

* closure note;
* final evidence archive;
* lessons learned;
* stakeholder sign-off;
* operational handover summary.

Prompt:
<<START PROMPT: LEAD AGENT>>
Prepare a closure and handover package for the project. Include:

* final delivered scope;
* outstanding issues or residual risks;
* operational ownership transition;
* archival status;
* evidence retention confirmation;
* lessons learned;
* final governance and sign-off summary.
Ensure the closure is explicit and reviewable.
<<STOP PROMPT: LEAD AGENT>>

\---

# 7\. Standard Prompt Design for PBIM Implementation

The prompts below are intentionally structured to improve output quality and reduce ambiguity when shared with lead agents or collaborating agents.

## 7.1 Lead Agent Prompt Template

<<START PROMPT: LEAD AGENT TEMPLATE>>
You are the lead project governance and synthesis agent for this PBIM implementation.

Your responsibilities:

* establish project structure and control boundaries;
* ensure the output follows the PBIM identity and governance model;
* review all contributor findings;
* consolidate inputs into a coherent and implementation-ready final artifact;
* confirm that the result aligns with modern project management principles and evidence-based controls.

Instructions:

1. Use the PBIM naming and numbering system exactly where applicable.
2. Preserve the project governance hierarchy: Governance → Assurance → Execution.
3. Explicitly document authority, scope, and approval conditions.
4. Highlight any unresolved assumptions or blockers.
5. Ensure the final output is structured, reviewable, and ready for manual refinement before Architectural Engineering review.
6. Include prompts or placeholders where a human decision is required.
7. Distinguish between project documentation, operational evidence, and implementation artifacts.
<<STOP PROMPT: LEAD AGENT TEMPLATE>>

## 7.2 Collaborating Agent Prompt Template

<<START PROMPT: COLLABORATING AGENT TEMPLATE>>
You are a collaborating project reviewer for this PBIM implementation.

Your responsibilities:

* evaluate the project from your domain perspective;
* identify risks, gaps, assumptions, and control weaknesses;
* provide structured findings with supporting rationale;
* avoid approving architecture or implementation simply because it appears plausible;
* challenge assumptions and identify practical failure modes.

Instructions:

1. Review the referenced PBIM governance and project context before responding.
2. Identify both strengths and weaknesses.
3. Flag any ambiguity, governance drift, or missing evidence path.
4. Prioritize material issues that could affect authority, independence, approval, scope, or release readiness.
5. Use clear headings and structured output.
6. Suggest corrective actions or governance changes where needed.
7. Keep findings evidence-based and implementation-oriented.
<<STOP PROMPT: COLLABORATING AGENT TEMPLATE>>

\---

# 8\. PBIM Implementation Checklist

Before project work proceeds, the following shall be confirmed:

* Project identity and naming format are assigned.
* Governance authority is defined and explicit.
* Roles and agent independence are documented.
* Project scope, assumptions, and baseline are established.
* Task packets and change control are defined.
* Risk and quality baseline are created.
* Evidence and retention controls are identified.
* Architectural analysis and verification are scheduled.
* Challenge and adversarial review is planned.
* Monitoring and drift detection are defined.
* Release readiness and closure procedures are established.

\---

# 9\. Final Notes for Manual Refinement

This generic PBIM template is intentionally structured to be:

* clear enough for implementation;
* flexible enough for reuse across different projects;
* aligned with modern project governance standards;
* suitable for manual review before entering the Architectural Engineering development process.

Before formal Architectural Engineering verification, a human reviewer should manually refine:

* the project-specific authority model;
* the actual repository paths;
* the roles and branch assignments;
* the specific task packet and release conditions;
* the risk and evidence requirements relevant to the real project.

This manual refinement step is required before the PBIM becomes a project-specific operational baseline.

\---

# 10\. Summary

The revised PBIM framework above preserves the original structure and intent of the repository’s PBIM materials while making the document:

* more implementation-ready;
* easier for lead and collaborator agents to use;
* clearer in authority, evidence, and scope control;
* aligned with modern governance and project management standards.

If you want, I can also turn this into:

1. a cleaner repository-ready Markdown file,
2. a shorter project template version,
3. or a fully numbered “production-ready PBIM” document in the exact style of the repository’s existing documentation.

