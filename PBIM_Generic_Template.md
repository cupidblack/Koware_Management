# PROJECT BASE INTEGRATION MANAGER [PBIM]

TIMESTAMP: {{TIMESTAMP}}

---

# [PROJECT-NAME: {{PROJECT_NAME}}]

# [PROJECT-BASE: {{PROJECT_BASE}}]

## PROJECT MANAGEMENT OFFICE

## {{PMO_DEPARTMENT}}

### [KOWARE-IAPD-PMO]-[{{BASE_IDENTIFIER}}]-[{{PROJECT_ABBREVIATION}}]-[PBIM-IDENTIFIER]

BASE PROJECT R&D FOLDER: {{BASE_RD_FOLDER}}

BASE PROJECT R&D DOCS FOLDER: {{BASE_RD_DOCS_FOLDER}}

BASE PROJECT PRODUCTION REPOSITORY: {{BASE_PROD_REPO}}

LEAD AGENT: {{LEAD_AGENT}}

SUPPORTING AGENTS: {{SUPPORTING_AGENTS}}

---

## DEVELOPMENT WORKFLOW REVIEW

### 40 PROJECT MANAGEMENT PROCESSES (PMBOK 8TH EDITION)

The following 40 project management processes are referenced as the foundation for the PBIM workflow. Each process is ordered by process group and knowledge area, with a unique identifier for traceability.

#### <<START 40 PROCESSES>>

| CODE | PROCESS GROUP | KNOWLEDGE AREA | PROCESS |
|------|--------------|----------------|---------|
| GOV-01-0004.1 | Initiating | Project Integration | Initiate Project or Phase |
| STK-01-0013.1 | Initiating | Stakeholders | Identify Stakeholders |
| GOV-02-1004.2 | Planning | Project Integration | Integrate and Align Project Plans |
| SCP-01-1005.1 | Planning | Scope | Plan Scope Management |
| SCP-02-1005.2 | Planning | Scope | Elicit and Analyze Requirements |
| SCP-03-1005.3 | Planning | Scope | Define Scope |
| SCP-04-1005.4 | Planning | Scope | Develop Scope Structure |
| SCH-01-1006.1 | Planning | Schedule | Plan Schedule Management |
| SCH-02-1006.2 | Planning | Schedule | Develop Schedule |
| FIN-01-2007.1 | Planning | Financial | Plan Financial Management |
| FIN-02-2007.2 | Planning | Financial | Estimate Costs |
| FIN-03-2007.3 | Planning | Financial | Develop Budget |
| GOV-05-2008.1 | Planning | Quality | Manage Quality Assurance |
| RES-01-2009.1 | Planning | Resources | Plan Resource Management |
| RES-02-2009.2 | Planning | Resources | Estimate Resources |
| STK-03-2010.1 | Planning | Communications | Plan Communications Management |
| RSK-01-2011.1 | Planning | Risk | Plan Risk Management |
| RSK-02-3011.2 | Executing | Risk | Identify Risks |
| RSK-03-3011.3 | Executing | Risk | Perform Risk Analysis |
| RSK-04-3011.5 | Executing | Risk | Plan Risk Responses |
| GOV-03-3012.1 | Executing | Procurement | Plan Sourcing Strategy |
| STK-02-3013.2 | Executing | Stakeholders | Plan Stakeholder Engagement |
| GOV-04-4004.3 | Executing | Project Integration | Manage Project Execution |
| GOV-06-4004.4 | Executing | Project Integration | Manage Project Knowledge |
| RES-03-5009.3 | Executing | Resources | Acquire Resources |
| RES-04-6009.4 | Executing | Resources | Lead the Team |
| STK-05-6010.2 | Executing | Communications | Manage Communications |
| RSK-05-6011.6 | Executing | Risk | Implement Risk Responses |
| STK-04-6013.3 | Executing | Stakeholders | Manage Stakeholder Engagement |
| GOV-08-7004.5 | Monitoring & Controlling | Project Integration | Monitor and Control Project Performance |
| GOV-07-7004.6 | Monitoring & Controlling | Project Integration | Assess and Implement Changes |
| SCP-06-7005.5 | Monitoring & Controlling | Scope | Validate Scope |
| SCP-05-7005.6 | Monitoring & Controlling | Scope | Monitor and Control Scope |
| SCH-03-8006.6 | Monitoring & Controlling | Schedule | Monitor and Control Schedule |
| FIN-04-8007.4 | Monitoring & Controlling | Financial | Monitor and Control Finances |
| RES-05-8009.6 | Monitoring & Controlling | Resources | Monitor and Control Resourcing |
| STK-07-8010.3 | Monitoring & Controlling | Communications | Monitor Communications |
| RSK-06-8011.7 | Monitoring & Controlling | Risk | Monitor Risks |
| STK-06-8013.4 | Monitoring & Controlling | Stakeholders | Monitor Stakeholder Engagement |
| GOV-09-9004.7 | Closing | Project Integration | Close Project or Phase |

#### <<STOP 40 PROCESSES>>

### WORKFLOW INTEGRATION STRATEGY

The 40 PMBOK 8th Edition processes are mapped to PBIM sections using a consistent identifier scheme. PBIM sections use the format `[BASE-PROJECT][PROCESS-CODE-SECTION]` where:
- `PROCESS-CODE` aligns with the PMBOK process code (e.g., `GOV-01-0004.1`)
- `SECTION` is a PBIM-specific subsection number

Intermediate/custom section suffixes may be inserted into logical positions between formal PMBOK process numbers to maintain exact numerical sequence throughout the lifecycle.

### PREVIOUS WORKFLOW (LEGACY)

The previous production workflow followed a sequential prompt-sharing model between ChatGPT, GitHub, and other agents. This workflow is superseded by the architectural engineering pipeline defined in this document.

### ARCHITECTURAL ENGINEERING PIPELINE (CURRENT)

The current workflow aligns with the 40 PMBOK processes and follows the architectural engineering pipeline:

**AEA (Architectural Engineering Analysis)** → **AEV (Architectural Engineering Verification)** → **AEC (Architectural Engineering Challenge)** → **AECC (Architectural Engineering Challenge Closure)**

Each stage produces controlled artifacts with evidence classification, independent review, and explicit approval authority.

---

### [{{BASE_PROJECT_ABBR}}][GOV-01-0004.01]

### PBIM DOCUMENT CREATION

#### RESOURCE COMPILATION

Before creating the PBIM document, all necessary resources must be compiled. The following is a compilation of available PBIM documents that should be referenced to create a generic version of the PBIM document:

**<<START RESOURCES>>**

1. The current PBIM document:
{{CURRENT_PBIM_URL}}

2. The 'PBIM Architectural Engineering Analysis Query' document:
{{AEA_QUERY_URL}}

3. All 'PBIM Architectural Engineering Analysis Query' reports:
{{AEA_REPORTS_URL}}

4. All revisions of the 'PBIM Architectural Engineering Verification' statement documents:
{{AEV_STATEMENT_URL}}

5. All responses to the 'PBIM Architectural Engineering Verification' statement documents:
{{AEV_RESPONSES_URL}}

6. All versions of the 'PBIM Architectural Engineering Challenge - Adversarial Duel' documents:
{{AEC_DUEL_URL}}

7. All results from the 'PBIM Architectural Engineering Challenge - Adversarial Duel':
{{AEC_RESULTS_URL}}

**<<STOP RESOURCES>>**

#### <<START PROMPT: PBIM DOCUMENT CREATION>>

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Generate a generic PBIM document based on the shared reference documents.

**Instructions:**
1. Review and analyze all reference documents listed above.
2. Pay particular attention to the structure and requirements of the initial PBIM document.
3. Develop and update identifier headings/labels in each section so they are ready for step-by-step implementation.
4. Review and analyze the prompts used in each section, then develop and update them so the implementer obtains optimal responses when shared with lead or collaborating agents.
5. Check for inconsistencies with modern architecture, project management, engineering principles and industry policies, practices and standards. Update identifiers, headings, subheadings, labels and section content accordingly.
6. Format as a generic template using `[BASE-PROJECT]` pattern (concrete example: `[BZJ-PGBD]`).

**Expected Output:** A complete generic PBIM document with:
- Updated identifier scheme
- Modernized section content
- Optimized prompts for each stage
- Evidence classification requirements
- Architectural engineering pipeline integration
- Risk-based governance profiles
- Clear authority boundaries
- Stop conditions and emergency governance

**Evidence Classification:** Classify all substantive claims as VERIFIED FACT, INFERENCE, ASSUMPTION, PROPOSAL, RISK, or UNKNOWN.

**References:**
{{PBIM_DOCUMENT_LINK}}

<<STOP PROMPT: PBIM DOCUMENT CREATION>>

---

### [{{BASE_PROJECT_ABBR}}][SCP-04-0004.02]

### PBIM DOCUMENT DEVELOPMENT

#### ARCHITECTURAL ENGINEERING OF PROJECT PROPOSAL

Review and develop sections 0004.01 through 0004.12 before Project Charter Development.

#### <<START ANALYSIS AND DEVELOPMENT OF THE PBIM DOCUMENT>>

Referencing the entire PBIM document, including requirements, prompts, notes, and the Project Base Integration Initialization:

**Step 1: Architectural Engineering Analysis Query (AEA)**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Develop a PBIM Architectural Engineering Analysis Query document to share with collaborating agents.

**Instructions:**
1. Thoroughly review and analyze the entire PBIM document, including requirements, prompts, notes, and the Project Base Integration Initialization.
2. Develop it into a PBIM Architectural Engineering Analysis Query document that would be shared with collaborating agents.
3. Agent responses will be shared with the lead agent to produce a controlled PBIM Architectural Engineering Verification baseline candidate document.

**<<START PBIM DOCUMENT REFERENCE>>**

{{PBIM_DOCUMENT_LINK}}

**<<STOP PBIM DOCUMENT REFERENCE>>**

---

**Step 2: AEA Report Generation**

**To:** Each Collaborating Agent ({{SUPPORTING_AGENTS}})

**Objective:** Generate an independent AEA report.

**Instructions:**
1. Thoroughly review and analyze the PBIM Architectural Engineering Analysis Query document.
2. Generate a report following the mandated structure (Sections A-W, plus document control).
3. Classify all substantive claims as VERIFIED FACT, INFERENCE, ASSUMPTION, PROPOSAL, RISK, or UNKNOWN.
4. Identify: what the architecture is attempting to accomplish, what principles can be extracted, what is sound, incomplete, contradictory, ambiguous, unnecessarily complex, what controls are missing, what should be redesigned, consolidated, separated, automated, or remain human-controlled.
5. State what must be resolved before the system can become the controlled baseline.

**<<START AEA QUERY REFERENCE>>**

{{AEA_QUERY_LINK}}

**<<STOP AEA QUERY REFERENCE>>**

---

**Step 3: Controlled AEV Baseline Candidate**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Develop a controlled AEV baseline candidate document.

**Instructions:**
1. Thoroughly review and analyze the PBIM Architectural Engineering Analysis Query reports from all collaborating agents.
2. Develop a controlled PBIM Architectural Engineering Verification baseline candidate document based on their reports.
3. The document must not simply represent the Lead Agent's preferred interpretation.
4. It must provide a defensible record of how the architecture was examined and why unresolved alternatives were accepted or rejected.
5. Include a resolution matrix mapping each finding to specific sections.

**<<START AEA REPORTS REFERENCE>>**

{{AEA_REPORTS_LINK}}

**<<STOP AEA REPORTS REFERENCE>>**

---

**Step 4: AEV Statement Review**

**To:** Each Collaborating Agent ({{SUPPORTING_AGENTS}})

**Objective:** Review and approve the AEV statement.

**Instructions:**
1. Thoroughly review and analyze the PBIM Architectural Engineering Verification Statement document.
2. Share your decision for approval here.
3. If any agent disapproves, resolve all issues, update the AEV document, then reshare with all collaborating agents for review and approval.
4. If approval is not obtained after revisions 1.0, 1.1, 1.2, 1.3, and 1.4, request all documents generate an updated PBIM document to restart the cycle.

**<<START AEV STATEMENT REFERENCE>>**

{{AEV_STATEMENT_LINK}}

**<<STOP AEV STATEMENT REFERENCE>>**

---

**Step 5: AEV Response Compilation**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Compile AEV responses and prepare for AEC if approved.

**Instructions:**
1. Review the PBIM Architectural Engineering Verification responses from all collaborating agents.
2. If all agents approve, prepare a PBIM Architectural Engineering Challenge - Adversarial Duel document to attack the proposed PBIM architecture.
3. If any agent disapproves, resolve issues, update the AEV document, and reshare for approval.

**<<START AEV RESPONSES REFERENCE>>**

{{AEV_RESPONSES_LINK}}

**<<STOP AEV RESPONSES REFERENCE>>**

---

**Step 6: Architectural Engineering Challenge (AEC)**

**To:** Each Collaborating Agent ({{SUPPORTING_AGENTS}})

**Objective:** Complete the adversarial duel to test break points in the proposed architecture.

**Instructions:**
1. Complete the PBIM Architectural Engineering Challenge - Adversarial Duel.
2. The purpose is NOT to refine PBIM, but to attempt to BREAK it.
3. Assume every control is potentially bypassable.
4. Do not accept "the process says so", "the agent is trusted", or "the implementation will enforce it" as proof of resilience.
5. Identify the mechanism by which each control is expected to survive adversarial conditions.
6. Report findings using the required format: Finding ID, Severity (BLOCKER/MATERIAL/MINOR), Description, Evidence, Resolution.

**<<START AEC ADVERSARIAL DUEL>>**

{{AEC_DUEL_LINK}}

**<<STOP AEC ADVERSARIAL DUEL>>**

---

**Step 7: AEC Results and Closure**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Compile AEC results and prepare closure.

**Instructions:**
1. Review the PBIM Architectural Engineering Challenge Duel results from all collaborating agents.
2. If all agents approve implementation and production of the PBIM document, prepare a PBIM Architectural Engineering Challenge Closure document.
3. Serve the closure document with the approved PBIM AEV document to produce an updated PBIM document.
4. Share the updated PBIM document with each collaborating agent for final approval.

**<<START AEC RESULTS REFERENCE>>**

{{AEC_RESULTS_LINK}}

**<<STOP AEC RESULTS REFERENCE>>**

---

#### <<STOP ANALYSIS AND DEVELOPMENT OF THE PBIM DOCUMENT>>

---

### [{{BASE_PROJECT_ABBR}}][SCP-03-0004.03]

### PROJECT PROPOSAL DEFINITION

#### INCLUDES: INITIAL PROJECT PROPOSAL, PROJECT DEFINITIONS, REQUIREMENTS, EXPLANATIONS, AND TEMPLATE GENERATION PROMPT

**<<START [{{BASE_PROJECT_ABBR}}-0004.02] INITIAL PROJECT TEMPLATE GENERATION PROMPT>>**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Generate the initial project template unique to this project.

**Instructions:**
1. Review the approved PBIM document and all architectural engineering artifacts.
2. Develop an initial project template generation prompt that is unique to this project.
3. The template must include: project identifier architecture, lifecycle stages, control gates, agent roles, task packet structure, evidence requirements, verification criteria, and approval workflows.
4. Ensure the template supports both LIGHT (simple projects) and HIGH-ASSURANCE (complex projects) governance profiles.
5. Include placeholders for project-specific content that will be filled during project execution.

**References:**
{{APPROVED_PBIM_LINK}}
{{AEV_APPROVAL_LINK}}
{{AEC_CLOSURE_LINK}}

**<<STOP [{{BASE_PROJECT_ABBR}}-0004.02] INITIAL PROJECT TEMPLATE GENERATION PROMPT>>**

---

### [{{BASE_PROJECT_ABBR}}][SCP-04-0004.04]

### PROJECT PROPOSAL DEVELOPMENT

#### ARCHITECTURAL ENGINEERING OF PROJECT PROPOSAL

Referencing the Initial Project Template Generation Prompt that will generate a custom project template unique to this project:

**Step 1: AEA of Project Proposal**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Develop an AEA query for the project proposal.

**Instructions:**
1. Thoroughly review and analyze the Initial Project Template Generation Prompt.
2. Develop it into an Architectural Engineering Analysis Query document to share with collaborating agents.
3. Agent responses will be shared here to produce a controlled Architectural Engineering Verification baseline candidate document.

**<<START PROJECT PROPOSAL AEA QUERY>>**

{{PROJECT_PROPOSAL_AEA_LINK}}

**<<STOP PROJECT PROPOSAL AEA QUERY>>**

---

**Step 2: AEA Report Compilation**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Compile AEA reports and develop AEV baseline.

**Instructions:**
1. Review Architectural Engineering Analysis reports from all collaborating agents.
2. Develop a controlled Architectural Engineering Verification baseline candidate document.

**<<START AEA REPORTS>>**

{{ARCHITECTURAL_ENGINEERING_ANALYSIS_REPORTS}}

**<<STOP AEA REPORTS>>**

---

**Step 3: AEV Statement Review**

**To:** Each Collaborating Agent ({{SUPPORTING_AGENTS}})

**Objective:** Review and approve the AEV statement for the project proposal.

**Instructions:**
1. Thoroughly review and analyze the Architectural Engineering Verification document.
2. Share your decision for approval.
3. If any agent disapproves, resolve issues, update the AEV document, and reshare.

**<<START AEV STATEMENT>>**

{{AEV_STATEMENT_LINK}}

**<<STOP AEV STATEMENT>>**

---

**Step 4: AEV Response Compilation**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Compile AEV responses and prepare AEC if approved.

**Instructions:**
1. Review Architectural Engineering Verification responses from all collaborating agents.
2. If all agents approve, prepare an architectural challenge to attack the proposed architecture.

**<<START AEV RESPONSES>>**

{{AEV_RESPONSES_LINK}}

**<<STOP AEV RESPONSES>>**

---

**Step 5: Architectural Challenge and Closure**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Complete AEC and prepare closure.

**Instructions:**
1. If all collaborating agents approved the AEV document, prepare an Architectural Engineering Challenge - Adversarial Duel.
2. Share with each collaborating agent.
3. Compile results and prepare closure document.

**<<START AEC CHALLENGE>>**

{{AEC_CHALLENGE_LINK}}

**<<STOP AEC CHALLENGE>>**

---

### [{{BASE_PROJECT_ABBR}}][RES-03-0004.05]

### PROJECT TEMPLATE CREATION

#### CREATION OF PROJECT TEMPLATE

**<<START PROMPT: PROJECT TEMPLATE CREATION>>**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Create the project template based on the approved project proposal.

**Instructions:**
1. Use the approved Project Proposal and all architectural engineering artifacts.
2. Generate a complete project template with:
   - Project identifier structure
   - Lifecycle stage definitions
   - Control gate specifications
   - Agent role assignments
   - Task packet templates
   - Evidence collection requirements
   - Verification criteria
   - Approval workflows
   - Stop conditions
   - Emergency governance procedures
3. The template must be usable by a project manager without additional architectural explanation.
4. Include inline notes, prompts, and placeholders where they would naturally be encountered during project execution.

**References:**
{{APPROVED_PROPOSAL_LINK}}
{{AEV_APPROVAL_LINK}}
{{AEC_CLOSURE_LINK}}

**<<STOP PROMPT: PROJECT TEMPLATE CREATION>>**

---

### [{{BASE_PROJECT_ABBR}}][SCP-04-0004.06]

### PROJECT TEMPLATE DEVELOPMENT

#### ANALYSIS AND VERIFICATION APPROVAL

**Step 1: AEA of Project Template**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Develop AEA query for the project template.

**Instructions:**
1. Review the generated project template.
2. Develop an Architectural Engineering Analysis Query document.
3. Share with collaborating agents for independent analysis.

**<<START TEMPLATE AEA QUERY>>**

{{TEMPLATE_AEA_LINK}}

**<<STOP TEMPLATE AEA QUERY>>**

---

**Step 2: AEA Report Compilation**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Compile AEA reports and develop AEV baseline.

**Instructions:**
1. Review AEA reports from collaborating agents.
2. Develop controlled AEV baseline candidate.

**<<START TEMPLATE AEA REPORTS>>**

{{TEMPLATE_AEA_REPORTS}}

**<<STOP TEMPLATE AEA REPORTS>>**

---

**Step 3: AEV Review and Approval**

**To:** Each Collaborating Agent ({{SUPPORTING_AGENTS}})

**Objective:** Review and approve the AEV statement for the project template.

**Instructions:**
1. Review the Architectural Engineering Verification document.
2. Share your decision for approval.
3. Resolve any disapprovals before proceeding.

**<<START TEMPLATE AEV STATEMENT>>**

{{TEMPLATE_AEV_LINK}}

**<<STOP TEMPLATE AEV STATEMENT>>**

---

**Step 4: AEC Challenge and Closure**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Complete AEC and closure for the project template.

**Instructions:**
1. If all agents approve, prepare Architectural Engineering Challenge.
2. Compile results and prepare closure.
3. Finalize the approved project template.

**<<START TEMPLATE AEC CHALLENGE>>**

{{TEMPLATE_AEC_LINK}}

**<<STOP TEMPLATE AEC CHALLENGE>>**

---

#### ARCHITECTURE CHALLENGE AND CLOSURE

---

### [{{BASE_PROJECT_ABBR}}][GOV-01-0004.07]

### PROJECT FRAMEWORK INITIALIZATION

#### UPDATE PROJECT ATTRIBUTES AND SETTINGS

**<<START PROMPT: PROJECT FRAMEWORK INITIALIZATION>>**

**To:** Lead Agent ({{LEAD_AGENT}}) and Implementation Agents

**Objective:** Initialize the project framework in the repository.

**Instructions:**

1. **Create GitHub Project** and set project attributes:
   - Use the `[{{BASE_PROJECT_ABBR}}]` prefix identifier with four digits to identify specific sections
   - The second digit is the section counter; the last two digits identify a specific subsection
   - Example: `{{BASE_PROJECT_ABBR}}-4324` means subsection 24 of section 43

2. **Establish project folder structure**:
   - Base Production Repository: {{BASE_PROD_REPO}}
   - Base Project Development Folder: {{BASE_DEV_FOLDER}}
   - Project Documents Folder: {{BASE_DOCS_FOLDER}}

3. **Assign agent branches** (if using multi-agent workflow):
   - Default structure: `main/agent-name`
   - Each agent pushes responses to their assigned branch
   - Implementation code pushes to the project's production folder on the agent's assigned branch

4. **Review and update AGENTS.md** in all relevant repositories:
   - Production repository
   - wp-content (if applicable)
   - wp-content/mu-plugins (if applicable)
   - streams, social, shared, data (if applicable)

5. **Confirm skills folder** location:
   - Preferred: `.github/skills/project-name`
   - Alternative: `.git/skills/project-name`

6. **Confirm Architecture Decision Records (ADR) location**:
   - Preferred: `data/docs/ADR`

7. **Confirm Decision Ledger location**:
   - Preferred: `data/docs/ADR/decisions`

8. **Create task packet** usable by all agents:
   - Consider a centralized task packet file
   - Include project identifier, task identifier, lifecycle stage, objective, scope, exclusions, authoritative references, source files, requirements, constraints, assigned agent, permitted actions, prohibited actions, expected deliverables, verification requirements, acceptance criteria, output location, branch requirement, logging requirement, dependencies, stop conditions, escalation conditions.

9. **Implement an 'agent challenge before build' gate**:
   - Assist in discovering potential issues after production code has been implemented
   - Must be completed before implementation begins

10. **Implement explicit stop conditions**:
    - Define objective conditions for stopping analysis, architecture generation, implementation, testing, deployment, merge, release, project progression, or project closure

**<<STOP PROMPT: PROJECT FRAMEWORK INITIALIZATION>>**

---

### [{{BASE_PROJECT_ABBR}}][GOV-02-0004.08]

### PROJECT SIMULATION

#### TEAM-UP, BRIEF, AND RUN-THROUGH

**<<START PROMPT: PROJECT SIMULATION>>**

**To:** Lead Agent ({{LEAD_AGENT}}) and All Supporting Agents ({{SUPPORTING_AGENTS}})

**Objective:** Conduct project simulation before implementation begins.

**Instructions:**

1. **Team Briefing:**
   - Review project charter, scope, and objectives
   - Confirm agent roles and responsibilities
   - Review communication channels and escalation paths

2. **Process Walkthrough:**
   - Run through each PMBOK process mapped in the PBIM
   - Identify handoff points between agents
   - Verify task packet structure and content

3. **Change Management Process:**
   - Review change request workflow
   - Confirm impact analysis requirements
   - Verify architectural review gates
   - Confirm baseline update procedures

4. **Template Implementation Preview:**
   - Walk through the project template with a sample task
   - Verify all prompts, placeholders, and notes are functional
   - Confirm evidence collection points

5. **Contractor Requirements Review:**
   - If external contractors are involved, review their integration points
   - Confirm governance applies to contractor work
   - Verify contractor deliverables meet PBIM standards

6. **Procurement Processes:**
   - Review sourcing strategy (GOV-03-3012.1)
   - Confirm procurement governance controls

7. **Timeline and Deliverables Review:**
   - Verify project timeline aligns with PMBOK process sequence
   - Confirm deliverable dates and review gates
   - Validate resource availability

8. **Project After-Life:**
   - Review closure requirements (GOV-09-9004.7)
   - Confirm archival and knowledge transfer plans

**<<STOP PROMPT: PROJECT SIMULATION>>**

---

### [{{BASE_PROJECT_ABBR}}][GOV-02-0004.09]

### PBIM IMPLEMENTATION

#### FINAL PBIM DOCUMENT INTEGRATION

**<<START PROMPT: PBIM IMPLEMENTATION>>**

**To:** Lead Agent ({{LEAD_AGENT}})

**Objective:** Finalize the PBIM document for project integration.

**Instructions:**

1. **Compile Final PBIM Document:**
   - Integrate all approved sections from 0004.01 through 0004.09
   - Ensure all identifiers are consistent and follow the `[BASE-PROJECT][PROCESS-SECTION]` pattern
   - Verify all prompts are optimized for agent responses
   - Confirm all evidence classification requirements are explicit

2. **Validate Against Modern Standards:**
   - Verify alignment with PMBOK 8th Edition
   - Confirm modern DevSecOps integration
   - Verify evidence-based governance compliance
   - Validate risk-based scaling (LIGHT/STANDARD/HIGH-ASSURANCE)
   - Confirm authority boundary definitions
   - Verify serialized transaction patterns for critical state

3. **Final Review:**
   - Conduct final AEA review if significant changes were made
   - Obtain AEV approval from all collaborating agents
   - Complete AEC adversarial duel
   - Obtain AECC closure approval

4. **Integration with Project:**
   - The PBIM document is now ready for project charter development
   - From this point, the PBIM officially integrates with the project
   - All subsequent project artifacts reference the PBIM as the governing framework

**<<STOP PROMPT: PBIM IMPLEMENTATION>>**

---

### [{{BASE_PROJECT_ABBR}}][GOV-01-0004.1]

### INITIATE PROJECT OR PHASE

#### PROJECT CHARTER DEVELOPMENT

This is the final PBIM section before the PBIM officially integrates with the project. The project charter development uses all preceding PBIM sections as input.

**<<START PROMPT: INITIATE PROJECT OR PHASE>>**

**To:** Lead Agent ({{LEAD_AGENT}}) and Human Project Authority (H0)

**Objective:** Develop the project charter based on the approved PBIM framework.

**Instructions:**

1. **Project Authorization:**
   - Confirm Constitutional Authority (CA) appointment and record
   - Verify H0 authority and delegation scope
   - Document project business case and justification

2. **Project Objectives and Success Criteria:**
   - Define measurable project objectives
   - Establish success criteria aligned with PBIM requirements
   - Confirm stakeholder expectations

3. **High-Level Requirements:**
   - Document high-level business requirements
   - Identify constraints and assumptions
   - Define risk tolerance level (LIGHT/STANDARD/HIGH-ASSURANCE)

4. **Milestone Summary:**
   - Define high-level milestones aligned with PMBOK process sequence
   - Confirm review and approval gates
   - Establish baseline for scope, schedule, and cost

5. **Project Governance:**
   - Confirm Authority-Permission Matrix
   - Validate agent roster and responsibilities
   - Confirm communication plan
   - Establish evidence and documentation requirements

6. **Formal Project Launch:**
   - Obtain CA approval for constitutional-level projects
   - Obtain H0 approval for project charter
   - Distribute project charter to all stakeholders
   - Transition from PBIM framework to project execution

**References:**
All preceding PBIM sections (0004.01 through 0004.09)
Approved PBIM Document
AEV Approval
AEC Closure Approval

**<<STOP PROMPT: INITIATE PROJECT OR PHASE>>**

---

## ARCHITECTURAL ENGINEERING GOVERNANCE FRAMEWORK

The following governance principles apply throughout the PBIM lifecycle:

### CONTROL MATURITY LEVELS

All PBIM artifacts progress through four maturity levels:

1. **DESIGNED** — Architecture specifies required controls
2. **ENFORCEABLE** — Implementation defines enforcement mechanisms
3. **ENFORCED** — Controls operate in production
4. **INDEPENDENTLY VERIFIED** — Independent verification confirms operation

Architectural specification does not constitute implementation evidence. Implementation Verification must establish whether controls actually operate.

### GOVERNANCE MODEL

**Governance** establishes authority and constraints.
**Assurance** verifies and challenges governance, architecture, and implementation.
**Execution** performs authorized work.

No technical capability automatically creates governance authority.
No consensus automatically creates authority.
No role label automatically creates independence.

### EVIDENCE CLASSIFICATION

All substantive claims must be classified as:
- **VERIFIED FACT** — Directly supported by repository contents, source code, configuration, documentation, executable tests, official documentation, reproducible observation, or another authoritative source
- **INFERENCE** — A reasoned conclusion derived from available evidence
- **ASSUMPTION** — A statement accepted temporarily because sufficient evidence is unavailable
- **PROPOSAL** — A recommended architectural change that has not yet been approved
- **RISK** — A condition that could negatively affect the project
- **UNKNOWN** — An unresolved issue requiring further investigation

### AUTHORITY BOUNDARIES

**Governance Authority** is distinct from **Technical Capability**.
Technical privileges (repository administration, CI/CD administration, infrastructure access, deployment credentials) do not independently confer governance authority.

A formal **Privilege Boundary** exists between technical administration and project governance.

### CONSTITUTIONAL AUTHORITY (CA)

CA exists outside the PBIM project-management execution structure.
CA is the constitutional root of trust for PBIM.
PBIM cannot define an authority above CA.
CA unavailability does not automatically transfer CA authority.
CA compromise is an external organizational trust-boundary failure.

### HUMAN AUTHORITY (H0)

H0 remains the Human Project Authority.
H0 cannot modify CA authority, remove CA, redefine CA, or authorize constitutional changes without CA.
H0 may administer project governance within authorized scope.

### IDENTIFIER SYSTEM

Project identifiers use the format: `[BASE-PROJECT][PROCESS-CODE-SECTION]`

- `BASE` = Organization/Project Base identifier (e.g., BZJ for Buzzjuice)
- `PROJECT` = Project abbreviation (e.g., PGBD for Payment Gateway Bridge Development)
- `PROCESS-CODE` = PMBOK process code (e.g., GOV-01-0004.1)
- `SECTION` = PBIM-specific subsection

Intermediate/custom section suffixes may be inserted between formal PMBOK process numbers to maintain exact numerical sequence.

### RISK-BASED GOVERNANCE PROFILES

PBIM supports three governance profiles:

- **LIGHT** — Minimal ceremony for simple, low-blast-radius projects. Reduces operational controls but cannot eliminate required governance determinations.
- **STANDARD** — Balanced governance for typical projects. Default profile.
- **HIGH-ASSURANCE** — Maximum governance for critical systems. All controls enforced with independent verification.

Risk classification is a governance decision occurring at minimum at: charter, implementation commencement, major requirement change, and release.

Cumulative risk is assessed across tasks, related changes, dependencies, migrations, releases, and concurrent workstreams. A sequence of individually low-risk changes cannot remain LIGHT if their combined effect crosses a governance threshold.

### STOP CONDITIONS

Stop conditions are objective conditions for stopping: analysis, architecture generation, implementation, testing, deployment, merge, release, project progression, or project closure.

Examples:
- Missing requirement
- Unresolved architectural contradiction
- Critical security issue
- Failed verification
- Failed mandatory test
- Insufficient evidence
- Uncontrolled scope expansion
- Conflicting authoritative instructions
- Unavailable dependency
- Production safety concern
- Unresolved blocking challenge
- Missing approval

Where technically possible, stop states are machine-enforced. A mandatory stop blocks: merge, release, deployment, new task dispatch, and applicable verification advancement.

### RESUME CONTROL

Resume authority is distinct from the authority that initiated the stop where independence is required.
An executor may not self-resume a mandatory governance stop.

### EMERGENCY GOVERNANCE

Emergency delegation is:
- Scope-limited
- Action-limited
- Risk-limited
- Time-limited (maximum 72 hours unless CA establishes stricter limit)
- Logged
- Reviewable

Emergency actions trigger post-event architectural reconciliation.

### TASK PACKET ARCHITECTURE

Task Packets define the authorized change boundary:
- Direct file scope
- Generated file scope
- Dependency scope
- Configuration scope
- Build-artifact scope
- Schema scope
- Infrastructure scope
- External-effect scope

Each implementation Task Packet contains a machine-readable scope manifest. The canonical enforcement mechanism compares actual changeset against approved Task Packet scope manifest.

An out-of-scope result triggers: **STOP → REPORT → NEW/SUPERSEDING TASK PACKET**

### DOCUMENT CONTROL

Document lifecycle states:
- **Draft** — Under development
- **Analysis** — Under architectural review
- **Controlled Candidate** — Proposed baseline
- **Verification** — Under verification review
- **Approved** — Approved baseline
- **Superseded** — Replaced by newer version
- **Archived** — Historical record

### REQUIREMENTS TRACEABILITY

The project maintains traceability between:
`Requirement` → `Analysis` → `Architectural Decision` → `Verification` → `Implementation` → `Test` → `Approval` → `Release`

Essential traceability elements:
- Requirement ID
- Decision ID
- Artifact ID
- Verification ID
- Test ID
- Approval record
- Release record

### MULTI-AGENT ROLE SEPARATION

Minimum useful role model:
- **Human Project Sponsor / Product Authority** — Final business authority
- **Human Technical Authority** — Final technical authority
- **Lead Agent / Coordination Agent** — Orchestrates workflow
- **Analysis Agent** — Conducts AEA
- **Verification Agent** — Conducts AEV
- **Implementation Agent** — Executes authorized work
- **Challenge Agent** — Conducts AEC
- **Test Agent** — Validates implementation
- **Documentation Agent** — Manages artifacts
- **Release/Operations Agent** — Manages deployment and operations

Key rule: No single role should approve its own implementation without independent verification.

### APPROVAL MODEL

- **Verification** is evidence-based
- **Agreement/Consensus** is a process outcome
- **Authority** is assigned by role
- **Decision ownership** is explicit

A project may proceed when all mandatory safety gates are cleared and no blocking objection remains. Dissent is recorded even when not blocking. Human authority overrides agent disagreement in cases involving security, legality, financial commitment, or production risk.

### ARCHITECTURAL CHALLENGE GATE

The challenge occurs:
- Before verification
- Before implementation
- After major architectural change
- During high-risk milestones

The challenge attempts to **break** the architecture by testing: assumptions, requirements, dependencies, security, reliability, performance, scalability, failure recovery, operational complexity, maintainability, agent workflow, repository workflow, human workflow, cost, vendor/platform dependencies, migration, rollback, and long-term architectural drift.

Exit criteria:
- No critical unresolved assumption
- No blocking security issue
- No unresolved failure mode
- No major dependency uncertainty
- No architecture contradiction left unaddressed
- A documented decision path for each unresolved risk

---

## FOOTER

**Document Status:** {{DOCUMENT_STATUS}}
**Implementation Authorization:** NOT GRANTED
**Production Authorization:** NOT GRANTED
**AECC Authorization:** NOT YET GRANTED

**Next Steps:**
1. Complete PBIM sections 0004.01 through 0004.09
2. Develop project charter (0004.1)
3. Obtain AEV approval for each section
4. Complete AEC adversarial duel
5. Obtain AECC closure
6. Integrate PBIM with project execution

---

*This document is a generic template. Replace all `{{PLACEHOLDER}}` values with project-specific content before use.*

*The PBIM identifiers end at the development of the project charter, where the PBIM officially integrates with the project.*
