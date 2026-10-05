# PROJECT BASE INTEGRATION MANAGER [PBIM]

**DOCUMENT ID:** `[KOWARE-IAPD-PMO]-[BASE]-[PROJECT]-PBIM`
**DOCUMENT REVISION:** R4.0 — Generic, Implementation-Ready Consolidated Operating Model
**FILE VERSION:** v1.04.00-generic
**PARENT REVISION:** R3.0 (v1.03.00-generic), which consolidated the R1.5 control set
**DATE:** 2026-10-05
**DOCUMENT CLASS:** Reusable project-management and software-engineering operating model (GENERIC)
**STATUS:** CONSOLIDATED IMPLEMENTATION CANDIDATE — MANUAL UPDATE REQUIRED
**ARCHITECTURAL STATUS:** DESIGNED (see §4). Not ENFORCEABLE, not ENFORCED, not INDEPENDENTLY VERIFIED.
**DEFAULT ALIGNMENT PROFILE:** `AP-1 / PMBOK8` — declared as the default, NOT yet confirmed (§27.2, §36.1 Q3)
**STANDARDS CURRENCY VERIFIED AS AT:** 2026-10-05 (§0.4)
**PBIM TERMINAL SECTION:** `[BASE]-[PROJECT]-0004.10` — Project Charter Development and PBIM→Project Integration (§28.10)
**IMPLEMENTATION AUTHORIZATION:** NOT GRANTED
**PRODUCTION AUTHORIZATION:** NOT GRANTED
**AECC CLOSURE:** PENDING
**FRESH R4.0 AEC REQUIRED:** YES — before any implementation authorization

---

### HOW TO USE THIS DOCUMENT

This is the **generic** PBIM. It is a reusable operating model, not a project plan.

* Every identifier is written as `[BASE]-[PROJECT]-NNNN.NN[.NN]`. Substitute the project's
  base identifier and project abbreviation at instantiation. **Every decimal group is exactly
  two digits, zero-padded** (§7.1.1). The one-digit spelling is prohibited.
* The concrete **Buzzjuice Payment Gateway Bridge Development (`BZJ-PGBD`)** is the reference
  instantiation used for examples only. Example-specific detail must never leak into the
  generic architecture.
* The ten pre-charter sections `[0004.01]`–`[0004.10]` (§28) are populated with
  implementation-ready content, register templates (§33), exit criteria, and an **Optimal
  Agent Prompt** in Lead and Collaborating variants for each section.
* **`[0004.10]` is the terminal PBIM section.** PBIM identifiers end at Project Charter
  Development, which is the point at which PBIM integrates with the project (§28.10).
  Nothing after `[0004.10]` is authored by PBIM; downstream positions belong to the
  project's own project-management plan (§28.10.3).
* **This document must be manually updated by the human project authority before it enters
  the Architectural Engineering pipeline.** See §36. It is not an approved architecture and it
  does not authorize work.

**Canonical source of this document:** the file named in the Controlled Document Register
(§33.1). Symbolic branch names alone never establish authoritative identity (§25.2).

---

# 0. MODERN PROJECT-MANAGEMENT CONFORMANCE

PBIM is a **control layer placed on top of** a recognised project-management method. It does
not replace one. PBIM is deliberately generic: a project may adopt PMBOK, PRINCE2, ISO 21502,
SAFe, Scrum or a bespoke method as its *delivery framework*, and PBIM supplies the
governance, assurance and evidence controls that sit above it.

## 0.1 Principles adopted

1. **Principles over ritual.** Each control states the *intent* it serves. A control that
   cannot name the principle it serves is a candidate for deletion.
2. **Tailoring is mandatory, not optional.** The LIGHT / STANDARD / HIGH-ASSURANCE profiles
   (§19.1) are the tailoring mechanism. Ceremony scales; **protected controls (§7.2) do not**.
3. **Value delivery.** Every gate asks one question: does this work deliver authorised value?
   Paper completion is not value.
4. **Systems thinking.** Cumulative effects are assessed across tasks, dependencies, releases
   and workstreams (§19.2, §20.2). Local optimisation is a defect, not a virtue.
5. **Evidence before opinion.** Claims are classified and evidenced (§10.1). Consensus is not
   evidence (§16.1).
6. **Right work, right size.** Small projects get a proportionate subset (§19.1); no project
   is forced to carry the full ceremonial weight.
7. **Adaptive governance.** The control model changes when the risk model changes — by
   recorded decision (§19.3), never by local instruction (§18.7).
8. **Human authority is explicit and reachable.** Strategic decisions have a named human
   owner (§6.4). No agent may acquire that authority by capability or default (§8.2).
9. **Failure is designed for.** Failure states, stop conditions and reset paths are specified
   before execution, not discovered during it (§18, §25).

## 0.2 Standards and regulatory applicability register

PBIM is a **control layer placed on top of** a recognised project-management method. It does
not replace one. A project may adopt PMBOK, PRINCE2, ISO 21502, SAFe, Scrum or a bespoke
method as its *delivery framework*; PBIM supplies the governance, assurance and evidence
controls that sit above it.

The register below is the **declared conformance basis as at the currency date in §0.4**. It
is a register, not a claim of certification: listing a standard means PBIM's controls are
*mapped* to it, not that a project conforms to it. A project SHALL record, in the Standards
and Regulatory Applicability Register (§33.21), which rows apply to it, which do not, and the
named authority that decided.

### 0.2.1 Project management and portfolio governance

| Standard | Current state at §0.4 currency date | PBIM coverage | PBIM sections |
|-|-|-|-|
| PMI **PMBOK Guide — Eighth Edition** (ANSI/PMI 99-001-2025, published November 2025; errata issued for the second printing) | 6 principles; 7 performance domains (Governance, Scope, Schedule, Finance, Stakeholders, Resources, Risk); 5 focus areas; 40 reintroduced, non-prescriptive processes | Default alignment profile `AP-1` (§27.2) | §0.1, §27, §28 |
| PMI **The Standard for Project Management** (bound in the same volume) | 12 principles | Control intent for §0.1 | §0.1 |
| PMBOK Guide — Sixth Edition (2017) | 49 processes, 10 knowledge areas, 5 process groups | **Legacy continuity only** — profile `AP-2`, deprecated for new instantiation (§27.2) | §27.2 |
| **ISO 21502:2020** — Project, programme and portfolio management: guidance on project management | Edition 1, 2020-12; supersedes ISO 21500:2012 | Governance, lifecycle tailoring, gates, pre/post-project activity | §0.1, §19.1, §29 |
| **ISO 31000:2018** — Risk management: guidelines | Current | Risk profiles, cumulative risk, risk classification record | §19 |
| Agile Manifesto and successors; **SAFe**; **Scrum Guide** | Current | Iteration, backlog, definition of done, cadence | §21.4, §22.3 |
| **ITIL 4** | Current | Change enablement, service transition, operational readiness | §21, §23 |

### 0.2.2 Systems and software engineering

| Standard | Current state at §0.4 currency date | PBIM coverage | PBIM sections |
|-|-|-|-|
| **ISO/IEC 12207:2017** (+ amendments) — software lifecycle processes | Current | Lifecycle alignment, deliverables, verification | §27, §22 |
| **ISO/IEC 24748-1:2024** — governance of IT for the organization | Current | Governance-of-governance boundary, decision rights | §6, §26 |
| **ISO/IEC 25010:2011** (SQuaRE) — product quality model | Current | Quality planning, Definition of Done, completeness | §22 |
| **ISO 8601-1:2019** — date and time representations | Current | Timestamp format for all controlled artefacts | §5.2, §10.2 |
| **RFC 2119 / RFC 8174** — requirement-level keywords | Current | `SHALL` / `SHOULD` / `MAY` semantics used throughout PBIM | Document-wide |

### 0.2.3 Security, supply chain and configuration control

| Standard | Current state at §0.4 currency date | PBIM coverage | PBIM sections |
|-|-|-|-|
| **ISO/IEC 27001:2022** — information security management | 3rd edition, 2022-10 | Access control, change control, supply-chain security | §8, §21 |
| **NIST SP 800-53 Rev. 5** — security and privacy controls | Current | Authority–permission mapping, privileged-account audit | §8 |
| **NIST SP 800-218 (SSDF)** — Secure Software Development Framework | Current | Secure development practices, stop conditions, evidence | §17, §18, §10 |
| **CISA Secure by Design** and **NSA/CISA Developer Guide** | Current | Vulnerability disclosure, secure defaults | §21, §23 |
| **SLSA** (OpenSSF) — software supply-chain levels | Current | Provenance and durable build identity | §25.2, §24 |
| **OWASP ASVS** and **OWASP Top 10** | Current (web/app security) | Secure-review obligations in execution controls | §22.3 |
| **Regulation (EU) 2024/2847 — Cyber Resilience Act (CRA)** | In force 10 December 2024; vulnerability-reporting obligations apply from 11 September 2026; main obligations from 11 December 2027; Commission guidance published 27 July 2026 | Applicability to products with digital elements; reporting obligation routing | §0.4, §18.2, §33.21 |

### 0.2.4 AI and multi-agent governance — mandatory for PBIM

PBIM's defining feature is that it governs work performed by autonomous or semi-autonomous
software agents. Controls that are adequate for human teams are **not** adequate for agent
teams: agents act at machine speed, share credentials, inherit context, are replacable by
vendor, and can be socially steered by their own output. PBIM therefore treats AI-governance
instruments as **core**, not optional.

| Standard | Current state at §0.4 currency date | PBIM coverage | PBIM sections |
|-|-|-|-|
| **ISO/IEC 42001:2023** — AI management systems | 1st edition, 2023-12; certifiable by an accredited body | AI system inventory, AI policy, impact assessment, lifecycle controls, continual improvement | §0.1, §8, §9, §19 |
| **ISO/IEC 23894:2023** — AI risk management guidance | Current; extends ISO 31000 for AI | AI-specific risk treatment feeding the risk profile | §19 |
| **NIST AI RMF 1.0** (released 26 January 2023) | Govern / Map / Measure / Manage; **1.0 is under revision** following the White House AI Action Plan | Govern → policy and accountability; Map → context and risk framing; Measure → evidence; Manage → response | §0.1, §10, §19 |
| **NIST AI 600-1** — Generative AI Profile (26 July 2024) | Current | GenAI-specific hazards | §9.5, §19 |
| **NIST AI 100-2** (March 2025) | Names AI agents as a threat surface for the first time | Agent threat surface | §9, §13.2 |
| **NIST AI Agent Standards Initiative** (NIST CAISI, launched February 2026); **NIST IR 8596** preliminary draft (December 2025); **NCCoE** concept paper on software and AI-agent identity and authorization (February 2026) | In progress — **no published normative standard yet** | Tracked as watch items; PBIM does not claim conformance | §0.4, §33.21 |
| **OWASP Top 10 for Agentic Applications — 2026** (published 9 December 2025) | ASI01 Agent Goal Hijack; ASI02 Tool Misuse and Exploitation; ASI03 Identity and Privilege Abuse; ASI04 Agentic Supply Chain Vulnerabilities; ASI05 Unexpected Code Execution (RCE); ASI06 Memory and Context Poisoning; ASI07 Insecure Inter-Agent Communication; ASI08 Cascading Failures; ASI09 Human-Agent Trust Exploitation; ASI10 Rogue Agents | Attack taxonomy for §13.2 and §13.4 | §9, §13.2, §13.4, §17.4 |
| **Regulation (EU) 2024/1689 — EU AI Act** | General application **2 August 2026**; Art. 5 prohibitions, GPAI regime and Art. 50 transparency obligations in force; GPAI enforcement (Art. 101) from 2 August 2026; additional Art. 5 prohibitions and Art. 50(2) marking of pre-existing generative systems from **2 December 2026** | Applicability determination, AI-system classification record, human-oversight evidence, transparency record | §0.4, §6.5, §23, §33.21 |
| **Regulation (EU) 2026/1744 — Digital Omnibus on AI** (OJ 24 July 2026, in force 27 July 2026) | Amends the AI Act timetable: Annex III high-risk obligations apply from **2 December 2027**; Annex I embedded high-risk from **2 August 2028**; pre-existing high-risk systems used by public authorities by **2 August 2030** | Compliance-calendar tracking; a stale deadline copied into a PBIM is a defect | §0.4, §33.21 |

### 0.2.5 Rule for applying the register

1. PBIM does **not** mandate a single standard. A project SHALL rewrite this register for
   itself at instantiation (`[0004.01]` → `[0004.025]`, §28.1) and record the decision, the
   decider and the date in the Standards and Regulatory Applicability Register (§33.21).
2. A row marked *in progress* or *watch item* SHALL NOT be cited as a conformance basis. Only
   published, citable editions may be.
3. Where a regulation applies and PBIM's controls do not yet satisfy it, that is a **known
   gap** to be recorded as a requirement, not a reason to ignore the regulation.
4. Regulation dates are the most perishable item in this register. Compliance calendars SHALL
   be re-verified at each AEA, not inherited from a prior revision.

## 0.3 Canonical process count — reconciliation and supersession note

Historic PBIM documents cited "40 project management processes" attributed to the "PMBOK 8th
edition", while the list actually attached to that claim was a **hybrid**: a condensed
40-item enumeration that cross-referenced a further 10 processes by reference. Collaborating
agents correctly raised this as a blocking defect.

**That hybrid is superseded by R4.0 and SHALL NOT be cited in any new artefact.**

**Resolution adopted in R4.0:**

1. **The lifecycle reference is a pinned Alignment Profile, not a bare number** (§27.2). A
   project declares exactly one profile — `AP-1 / PMBOK8` (default), `AP-2 / PMBOK6` (legacy
   continuity only) or `AP-3 / TAILORED` (project-defined) — and records it.
2. **The bare figure "40" and the bare figure "49" are both superseded as standalone claims.**
   They are now only meaningful as *profile descriptors*: 40 processes in PMBOK 8, 49 in
   PMBOK 6. An artefact that cites a bare number without a named profile is defective.
3. **PBIM's own identifiers are PBIM-native and are not borrowed from any process list.**
   Section positions are minted in a PBIM-owned namespace (§7.1) and *mapped* to a profile for
   navigation. This closes the finding that the process numbering was simultaneously
   encoding process group, lifecycle position, knowledge area, document type and artefact
   type — a taxonomy overloaded into an identity system, and the reason a process list must
   never be used to drive identity.
4. **Process names SHALL match the declared profile's own vocabulary.** PMBOK 6 spellings
   (*Collect Requirements*, *Create WBS*, *Determine Budget*, *Direct and Manage Project
   Work*, *Perform Integrated Change Control*) are wrong under `AP-1`, whose spellings are
   *Elicit and Analyze Requirements*, *Develop Scope Structure*, *Develop Budget*, *Manage
   Project Execution*, *Assess and Implement Changes*.

This removes the count ambiguity, the double-listing, the mixed vocabulary, and the risk of a
reviewer reconciling two incompatible lists.

## 0.4 Currency and staleness control

PBIM governs projects whose delivery horizon commonly exceeds the life of the standards and
regulations they were written against. A control set that is not re-verified decays into
plausible-sounding text. This section makes decay detectable.

### 0.4.1 Verified currency basis

The standards, editions and regulatory dates in §0.2 were verified against publisher and
regulator sources as at **2026-10-05**. The following were specifically checked because the
predecessor documents were wrong or silent about them:

| Check | Predecessor state | Verified state at §0.4 currency date |
|-|-|-|
| Current PMBOK edition | "PMPBOK 8th edition" with a 6th-edition process list | 8th edition published November 2025: 6 principles, 7 performance domains, 5 focus areas, 40 processes |
| Process vocabulary | 6th-edition spellings mixed with 8th-edition names | Two vocabularies coexist; spelling is profile-dependent and SHALL be pinned |
| AI governance instruments | Absent | ISO/IEC 42001:2023; ISO/IEC 23894:2023; NIST AI RMF 1.0 (in revision); NIST AI 600-1; NIST AI Agent Standards Initiative (Feb 2026) |
| Agentic AI threat taxonomy | Absent | OWASP Top 10 for Agentic Applications 2026 (ASI01–ASI10), 9 Dec 2025 |
| EU AI Act timeline | Absent | General application 2 Aug 2026; high-risk regime deferred by Reg. (EU) 2026/1744 to 2 Dec 2027 / 2 Aug 2028 / 2 Aug 2030 |
| Cyber Resilience Act | Absent | Reg. (EU) 2024/2847: reporting from 11 Sep 2026, main obligations from 11 Dec 2027 |
| Software-engineering governance | Absent | ISO/IEC 24748-1:2024 |

### 0.4.2 Staleness rule

1. **Every PBIM revision SHALL carry a currency date** and the set of rows of §0.2 it was
   verified against.
2. **A PBIM whose currency date is more than 12 months old SHALL NOT be instantiated for a
   new project** until §0.2 is re-verified and the revision number is incremented.
3. **A PBIM instantiated on an older revision SHALL NOT silently inherit a newer revision's
   approvals.** Cross-revision approval inheritance is prohibited (§34.2 rule 3) and this
   rule is the most common form of its violation: a project keeps citing an expired profile
   or an expired compliance date because nobody re-checked.
4. **Re-verification is recorded, not assumed.** The Standards and Regulatory Applicability
   Register (§33.21) carries the verification date and the verifying authority.
5. **Watch items are not controls.** Standards in progress (§0.2.4) are tracked as watch items
   and SHALL NOT be cited as a conformance basis until published. Anticipating a standard is
   good practice; pretending it exists is a reporting failure (§35.3).

---

# 1. PBIM PURPOSE AND SCOPE

The **Project Base Integration Manager [PBIM]** is the reusable governance, assurance,
project-initialisation and software-engineering operating model used to establish a
controlled project environment **before** substantive project execution begins.

PBIM provides a repeatable foundation from which a project-specific:

1. project identity and identifier registry;
2. project governance structure;
3. project-management workflow and lifecycle alignment;
4. repository and branch architecture;
5. agent ecosystem and role model;
6. authority and permission model;
7. requirements and traceability model;
8. architectural review pipeline (AEA → AEV → AEC → AECC);
9. implementation controls (Task Packets, scope enforcement);
10. verification controls and independence model;
11. challenge and stop controls;
12. evidence and integrity system;
13. risk and materiality management system;
14. change-management system;
15. operational-readiness and release system;
16. project-closure and after-life system

can be established.

## 1.1 In scope

Pre-charter project bootstrap; lifecycle alignment; governance registers; the assurance
pipeline; execution controls; release authorization; closure.

## 1.2 Out of scope

PBIM does **not** define: product requirements for any specific system; delivery method
selection; commercial terms; staffing levels; or engineering implementation detail. Those
belong to the project proposal and project template produced from this model (§28).

## 1.3 Non-goals

PBIM shall not: replace human judgement; guarantee project success; act as a substitute for
professional engineering review; or be used to justify process for its own sake.

---

# 2. GOVERNING PRINCIPLE

PBIM operates according to:

**GOVERNANCE → ASSURANCE → EXECUTION**

### Governance establishes
authority; constraints; scope; permissions; requirements; decision rights; approval
conditions; risk thresholds; protected controls; release authority.

### Assurance performs
analysis; verification; challenge; testing; audit; evidence recording; drift detection;
independence evaluation; and determination of whether evidence supports advancement.

### Execution performs
authorised work; approved changes; artefact generation; testing; implementation evidence
recording; and operation within approved Task Packet scope.

## 2.1 Non-derivation rules

These are constitutional. They may not be weakened by any section, prompt, profile or agent.

1. No technical capability automatically creates governance authority.
2. No consensus automatically creates governance authority.
3. No agent title, model name or vendor automatically establishes independence.
4. No implementation artefact automatically constitutes verification evidence.
5. No document describing a control makes that control enforced.
6. No agent may author, approve and verify the same artefact where independent verification
   is required.
7. No local instruction, prompt or configuration may reduce a risk profile (§18.7).
8. No emergency mechanism may outlive its authorisation window (§18.6).

---

# 3. ARCHITECTURAL STATE MODEL

PBIM distinguishes four control-maturity states:

**DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED**

| State | Meaning | Evidence required |
|-|-|-|
| `DESIGNED` | Control is specified in an approved baseline | Baseline document, revision ID, approval |
| `ENFORCEABLE` | A technical mechanism exists that *could* enforce the control | Mechanism identified, tested in isolation |
| `ENFORCED` | The mechanism is active and operating in the real environment | Live configuration, observed behaviour |
| `INDEPENDENTLY VERIFIED` | Independent evidence demonstrates the control operates | Evidence from a verifier who did not implement it |

## 3.1 Mandatory state discipline

1. A documented control is not an implemented control.
2. A technically possible control is not an enforced control.
3. An enforced control is not an independently verified control.
4. **The state of every material governance control SHALL be recorded** in the Control State
   Register (§33.12). A control with no recorded state is treated as `DESIGNED`.
5. No document, report, prompt or status line may claim `ENFORCED` without a state-register
   entry backed by evidence.
6. Progression is not automatic. Each transition requires its own evidence and its own
   approver.

---

# 4. ARCHITECTURAL PRINCIPLES

PBIM is bound by the following design principles. A proposal that violates one SHALL be
recorded as a nonconformity.

| # | Principle | Test applied |
|-|-|-|
| P1 | **Single authoritative source** | Can exactly one file be named as authoritative for each artefact class? (§25.1) |
| P2 | **One identifier grammar** | Does every identifier parse under §7.1 with no alternative readings? |
| P3 | **Separation of duties** | Can the same actor author, approve and verify one artefact? (§8.3) |
| P4 | **Evidence before assertion** | Is every substantive claim classified and evidenced? (§10.1) |
| P5 | **Design ≠ implementation** | Are `DESIGNED` and `ENFORCED` never conflated? (§3) |
| P6 | **Dissent preserved** | Is minority position retained in the approved record? (§16.3) |
| P7 | **Bounded convergence** | Is there a hard revision cap with an escalation path? (§16.4) |
| P8 | **Tailorable, not weakenable** | Can ceremony scale down while protected controls hold? (§19.1) |
| P9 | **Machine-checkable where it matters** | Can scope, stop and drift controls be enforced automatically? (§17.4, §24.2) |
| P10 | **Durable references** | Does every authoritative reference survive branch deletion? (§25.2) |
| P11 | **No empty stubs** | Does every declared section contain resolvable content? (§22.5) |
| P12 | **Proportionate failure response** | Does failure stop the work without erasing history? (§18) |

---

# 5. CANONICAL SOURCE CONTROL

## 5.1 Canonical Source Manifest

Every project instantiating PBIM SHALL publish a **Canonical Source Manifest** naming, for
each artefact class, exactly one authoritative location. This resolves the defect in which
two divergent workflow guides existed with no canonical marker.

| Artefact class | Authoritative location (to be instantiated) | Branch | Protection |
|-|-|-|-|
| PBIM baseline | `{{governance-root}}/pbi/PBIM.md` | `{{governance-branch}}` | Protected, 2-review |
| Controlled document register | `{{governance-root}}/pbi/register/documents.yml` | `{{governance-branch}}` | Protected |
| Identifier Registry | `{{governance-root}}/pbi/register/identifiers.yml` | `{{registry-branch}}` | Protected, serialised |
| Authority Register | `{{governance-root}}/pbi/register/authority.yml` | `{{governance-branch}}` | Protected |
| Authority–Permission Matrix | `{{governance-root}}/pbi/register/permissions.yml` | `{{governance-branch}}` | Protected, serialised |
| Decision Ledger | `{{governance-root}}/pbi/decisions/DECISION-LEDGER.md` | `{{governance-branch}}` | Append-only |
| ADRs | `{{governance-root}}/pbi/decisions/adr/` | `{{governance-branch}}` | Append-only |
| Task Packets | `{{project-root}}/docs/task-packets/` | `{{governance-branch}}` | Protected |
| Evidence store | `{{evidence-root}}` | `{{evidence-branch}}` | Write-once, retained |
| AEA/AEV/AEC/AECC records | `{{governance-root}}/pbi/assurance/` | `{{governance-branch}}` | Protected |
| Project template | `{{governance-root}}/pbi/template/` | `{{governance-branch}}` | Protected |
| Production code | `{{production-repo}}` | `{{integration-branch}}` | Branch protection, CI |
| Development records | `{{project-root}}/docs/` | agent branches | Open write, reviewed merge |

## 5.2 Canonical source rules

1. Exactly one location per artefact class. If two documents claim authority, the conflict is
   a `CONFLICTING-SOURCE` finding (§16.5) and SHALL be resolved before advancement.
2. **Production code SHALL NOT be stored in the development docs folder.**
   Responses and records go to the development `docs` folder on the agent's own branch;
   implementation code goes to the production repository on the assigned integration branch
   (§5.1, last two rows). This preserves the separation the earlier workflow required.
3. Every canonical location SHALL be named with a **durable reference** (§25.2), not a
   symbolic branch name alone.
4. `AGENTS.md` files are **derived** from this manifest and SHALL never be authoritative over
   it. Where an `AGENTS.md` conflicts with the PBIM baseline, the PBIM baseline prevails and
   the conflict is logged (§8.6). This resolves the verified defect where `AGENTS.md`
   contradicted the architecture on roster, authority and branch naming.
5. The Canonical Source Manifest is itself a protected governance resource (§7.2).

---

# 6. CONSTITUTIONAL AUTHORITY AND HUMAN AUTHORITY

## 6.1 Constitutional Authority (CA)

Every PBIM project SHALL identify an external **CA — Constitutional Authority**.

CA is the constitutional root of trust. CA exists **outside** the ordinary PBIM execution
structure. The authority boundary is:

**CA → PBIM → H0 → H1 → H2**

| Level | Meaning | Examples |
|-|-|-|
| **CA** | Constitutional Authority | Governing body, board, constitution, founding instrument |
| **H0** | Human Project Authority | Project sponsor, product owner, accountable human |
| **H1** | Human Technical / Assurance Authority | Technical approver, assurance approver, security approver |
| **H2** | Execution Worker | Implementers, including AI agents and CI automation |

PBIM shall not create an authority above CA and shall not authorise its own constitutional
amendment (§26.1).

## 6.2 CA instantiation

Project initialisation SHALL record: CA identity; CA organisational basis; CA authority
scope; CA decision rights; CA succession mechanism (if any); CA review requirements; CA
evidence requirements.

**A merely conceptual CA is insufficient** for a project requiring constitutional decisions.
Where the Koware Group operates under a written constitution, bylaws or an equivalent
instrument, that instrument is the CA reference and SHALL be cited durably.

## 6.3 CA unavailability and compromise

If CA becomes unavailable and no formally authorised succession mechanism exists:

**`CA-UNAVAILABLE` → `CONSTITUTIONAL-BLOCKED`**

No H0, H1, H2, agent, administrator or emergency delegate may self-assume CA authority. Any
succession mechanism SHALL be pre-authorised, scope-bounded, time-bounded, recorded and
independently verifiable.

A compromised CA SHALL be recorded as **`CA-TRUST-BOUNDARY-COMPROMISED`**. PBIM shall not
claim that a project-level process can restore constitutional trust after compromise of its
constitutional root.

## 6.4 Human Authority Register and H0 unavailability

The **Human Authority Register** (§33.3) SHALL record, for each human role: identity, role,
authority scope, decision rights, availability, substitution rule, and review date.

Where **H0 is unavailable**:

1. Record `H0-UNAVAILABLE` with evidence and timestamp.
2. Apply the pre-authorised substitution rule. If none exists, the project enters
   `STOPPED` (§18.1).
3. A substitute H0 SHALL assume only the decision rights explicitly granted, never the
   identity.
4. Unavailability SHALL NOT be used to silently expand scope, reduce controls or change a
   risk profile.
5. Substitution SHALL be time-bounded and SHALL lapse back to `STOPPED` unless confirmed.

## 6.5 Strategic human decision points

Human authority SHALL be exercised, and SHALL NOT be delegated to an agent, at:

| # | Decision | Authority |
|-|-|-|
| 1 | Constitutional amendment | CA |
| 2 | Risk profile change | H0 |
| 3 | Budget or contractual commitment | H0 |
| 4 | Requirements baseline approval | H0 |
| 5 | Release authorization | H0 |
| 6 | Production rollback | H0 |
| 7 | Risk classification downgrade | H0 |
| 8 | Emergency delegation grant | H0 |
| 9 | Exception to a protected control | H0 + H1 |
| 10 | Project closure | H0 |

---

# 7. PROJECT IDENTITY AND IDENTIFIER ARCHITECTURE

## 7.1 Canonical identifier grammar

The reusable identifier model is:

`[BASE]-[PROJECT]-[ANCHOR].[POS][.[SUB]]`

| Part | Width | Meaning |
|-|-|-|
| `BASE` | 2–6 uppercase alphanumeric | project base identifier (e.g. `BZJ`) |
| `PROJECT` | 2–8 uppercase alphanumeric | project abbreviation (e.g. `PGBD`) |
| `ANCHOR` | exactly 4 digits, zero-padded | lifecycle anchor band (§7.1.4) |
| `POS` | exactly 2 digits, zero-padded | position within the anchor |
| `SUB` | exactly 2 digits, zero-padded, optional | subsection within the position |

Work-item, document, evidence, decision, change and release identifiers are **separate**
identifier classes and SHALL NOT be expressed as lifecycle positions (§7.3).

A worked identifier: `[BASE]-[PROJECT]-0004.01` → `[BZJ-PGBD-0004.01]`.

### 7.1.1 Zero-padding is mandatory — the one-digit form is void

**Every decimal group in a PBIM position SHALL be exactly two digits, zero-padded.**

* `0004.01` is the only valid spelling of position `01` under anchor `0004`.
* `0004.1` is **prohibited**. It is not an alternative spelling of `0004.01`; under the
  predecessor document's decimal rule it was the *different* position `0004.10`. Two
  incompatible conventions were therefore live at once, and readers chose between them by
  guesswork.
* `0004.1` is reserved in meaning for the **Project Charter** position, spelled
  `0004.10` (§7.1.3, §28.10). Its appearance as `0004.1` anywhere is a defect to be migrated,
  not a synonym to be honoured.

**Rationale, stated because it is why this is a rule and not a style preference.**
Zero-padded fixed-width groups are what make identifiers orderable by plain string
comparison. With variable width, a tenth item renders `...0110` and a ninth renders `...019`;
lexicographic order then places item 10 *before* item 9, silently corrupting lifecycle order
in every sort, index and generated table. This is not hypothetical: identifier strings in this
project's own working set were found not to sort lexicographically at all, and canonical
process numbers were found to sort out of order under `strcmp`.

**Machine ordering is nevertheless delegated to an explicit integer.** No consumer of PBIM
identifiers SHALL rely on string ordering. The Identifier Registry SHALL carry a numeric
`sequence_index` for every entry and sorting SHALL use that integer (§7.5). Identifier
strings are human labels; `sequence_index` is the ordering key.

### 7.1.2 Sub-identifier rule

A sub-item of `[ANCHOR].[POS]` extends the identifier by appending a further
**zero-padded two-digit group**: `[0004.01]` → `[0004.01.01]` → `[0004.01.01.05]`.

| Kind | Form | Example | Position |
|-|-|-|-|
| Section | `[ANCHOR].[POS]` | `[0004.01]` | base |
| Sub-item *n* | + `.` + `nn` | `[0004.01.01]` … `[0004.01.13]` | inside `[0004.01]`, before `[0004.02]` |
| Sub-sub-item *m* | + `.` + `mm` | `[0004.01.01.05]` | inside `[0004.01.01]` |
| Prompt artefact | `[ANCHOR].[POS]-PROMPT-LEAD` | `[0004.01]-PROMPT-LEAD` | artefact, not a position |
| Prompt artefact | `[ANCHOR].[POS]-PROMPT-COLLAB` | `[0004.01]-PROMPT-COLLAB` | artefact, not a position |

**Ordering proof** (the rule is only useful if it orders, by `sequence_index`):

`0004.01 → 0004.01.01 → 0004.01.01.05 → 0004.01.02 → … → 0004.01.13 → 0004.02`

A sub-item nests strictly inside its parent; every sub-item stays strictly inside its section.

**Rules.** Sub-identifiers SHALL NOT be minted ad hoc; they SHALL be reserved in the
Identifier Registry at creation time (§7.4). Each level SHALL be exactly two digits, zero
padded, and SHALL NOT exceed 99 items per level — beyond that, a new lifecycle position or a
new section SHALL be created instead. Prompt artefacts use the hyphenated form so prompt
identity is never mistaken for a lifecycle position. The registry SHALL record the parent of
every sub-identifier and a `sequence_index` for every entry.

### 7.1.3 Void, reserved and prohibited identifier forms

**Void — these forms are retired and SHALL NOT be minted:**

| Void form | Why it is void | Migration |
|-|-|-|
| `0000.NN` (e.g. `0000.01`–`0000.09`) | A second numbering root was used concurrently with `0004.NN`, producing two authoritative namespaces for the same PBIM stages. Two roots is one too many. | Migrate to `0004.01`–`0004.09`; record `superseded_by` |
| `0004.1`, `0004.2`, … (one decimal digit) | Padding was undefined, so `0004.1` meant both `0004.01` and `0004.10` depending on the reader | Migrate to the zero-padded two-digit position |
| Retired anchors coexisting with their replacements (e.g. `0004.10`/`0004.11` from the predecessor lineage) | Two spellings of one position make the registry non-deterministic | `DEPRECATED` in the registry; never re-minted |

**Reserved — available only by explicit recorded decision:**

| Reserved | Meaning |
|-|-|
| `0004.10` | Project Charter Development — PBIM→Project Integration. The **terminal PBIM position** (§28.10). Reserving the charter anchor is what lets PBIM stop cleanly at integration instead of trailing off into the project. |
| `9999.99` | Reserved terminal sentinel for a final closing position a project may choose to declare. SHALL NOT be used as filler. |

**Prohibited outright:**

* alternative grammars for the same section (two spellings meaning one thing);
* two-digit customs attached to an anchor that also has a one-digit form in circulation;
* a retired identifier still present alongside its replacement;
* any identifier encoding two semantic purposes;
* an identifier minted by an agent, template consumer or documentation system without a
  registry reservation;
* an identifier that appears in a **different project subtree** from the one named in the
  Canonical Source Manifest. This exact misplacement occurred in the reference corpus and is
  how a response report ends up filed as though it were a controlled artefact.

### 7.1.4 Anchor bands

`ANCHOR` encodes **lifecycle position only**. It does not encode process group, knowledge
area, artefact type or document type; those are separate registry attributes (§7.3, §7.5).

| Anchor band | Lifecycle position |
|-|-|
| `0000`–`0003` | **VOID** (§7.1.3). Never minted. |
| `0004` | Initiating — charter and everything PBIM authors before it |
| `0005`–`0009` | Reserved for custom initiating insertions before the charter |
| `1xxx` | Planning |
| `2xxx`–`3xxx` | Planning (detail and later planning sub-positions) |
| `4xxx`–`6xxx` | Executing |
| `7xxx`–`8xxx` | Monitoring and Controlling |
| `9xxx` | Closing |

**Custom anchors.** A project MAY allocate a custom anchor (for example `0005`) for
PBIM-specific engineering content that must sort between the charter band and the planning
band. A custom anchor SHALL be declared in the Alignment Profile (§27.2) and reserved in the
registry. It SHALL NOT reuse a void band and SHALL NOT shadow a profile process anchor.

**Anchor immutability.** Once published in an approved baseline, an anchor is immutable. A
change to what sits at an anchor requires a new revision with a Decision Ledger entry — not an
in-place reinterpretation. This rule was retained without dissent through the reference
assurance cycle and is the reason the identifier space stays auditable.

### 7.1.5 The PBIM boundary in the identifier space

PBIM authors `[0004.01]` through `[0004.10]` and nothing else (§28). `[0004.10]` is the
Project Charter position. Once the charter is approved, the project's own project-management
plan continues the numbering using the declared profile's process anchors. PBIM does not
mint, review or own those positions; it hands off at GATE 6 (§29.1, §28.10.3).

## 7.2 Generic identifier

The concrete project identifier SHALL NOT be treated as the generic PBIM architecture. The
generic token set is `[BASE]`, `[PROJECT]`, `[IDENTIFIER]`; a concrete project substitutes
its own values.

## 7.3 Identifier separation

PBIM shall not overload a single identifier with unrelated meanings. These classes remain
conceptually distinct:

| Class | Purpose | Example form |
|-|-|-|
| Project identifier | Identity of the project | `[BASE]-[PROJECT]` |
| Lifecycle position | Where in the lifecycle a section sits | `[ANCHOR].[POS][.[SUB]]` |
| Process classification | Which process / performance domain / knowledge area | `GOV`, `SCP`, `SCH`, `RSK` code — a **registry attribute**, not part of the position |
| Work-item identifier | A unit of execution | `TASK-[NNNN]` |
| Document identifier | A controlled artefact | `DOC-[NNNN]` |
| Evidence identifier | A piece of proof | `EVD-[NNNN.NN]` |
| Decision identifier | A recorded decision | `DEC-[NNNN]` |
| ADR identifier | An architecture decision record | `ADR-[NNNN]` |
| Change identifier | A change request | `CR-[NNNN]` |
| Risk identifier | A risk record | `RSK-[NNNN]` |
| Finding identifier | A challenge finding | `FND-[NNNN.NN]` |
| Load-bearing assumption identifier | An assumption under challenge | `LA-NN` |
| Response identifier | An agent's submitted response artefact | `RSP-[ANCHOR].[POS].[AGENT]` (§30.6) |
| Release identifier | A release | `REL-[NNNN.NN]` |

Correlation between classes is permitted and expected; **collapse is not**. The specific
failure being prevented: a single string that is simultaneously a section number, a document
name, a process code and a lifecycle position, so that renaming the document renumbers the
lifecycle.

## 7.4 Identifier Registry

The canonical Identifier Registry uses the serialised sequence:

**REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM**

Registry updates SHALL be serialised. Every authoritative registry revision SHALL preserve:
revision ID; parent revision; content hash; authorising authority; timestamp; verifier;
verification result.

Sub-identifiers are **reserved** at `RESERVE` and become `COMMITTED` only when the parent
section is implemented.

## 7.5 Registry schema (minimum)

Each entry SHALL contain: `identifier`; `sequence_index` (integer; the **only** permitted
ordering key); `kind` (section / sub-item / prompt-artefact / document / response / …);
`parent`; `title`; `lifecycle_anchor`; `performance_domain` or `process_code` (alignment
attribute); `status` (`RESERVED` / `COMMITTED` / `DEPRECATED` / `SUPERSEDED` / `VOID`);
`authoritative_reference`; `created`; `created_by`; `verified_by`; `verified_at`;
`superseded_by`.

A registry validator SHALL reject, on write: duplicate identifiers; identifiers violating the
grammar of §7.1; identifiers in a void namespace (§7.1.3); missing mandatory fields; illegal
status transitions; missing or non-monotonic `sequence_index`; and an identifier whose
authoritative reference lies outside the subtree named in the Canonical Source Manifest.

## 7.6 Registry conflict, failure and recovery

Conflicting concurrent writes SHALL produce **`IDENTIFIER-REGISTRY-CONFLICT`** and SHALL NOT
be resolved by last-write-wins.

If the registry is unavailable or corrupt: **`REGISTRY-BLOCKED`**. Recovery SHALL:

1. identify the last trusted revision;
2. reconcile pending reservations;
3. independently verify integrity against the integrity anchor (§10.3);
4. restore canonical state;
5. obtain independent approval to reactivate.

A stale local registry SHALL NEVER become authoritative because the canonical registry is
unavailable. No emergency identifier creation SHALL bypass the registry.

## 7.7 Traceability requirement

A project instantiating PBIM SHALL maintain a **Requirements Traceability Matrix (RTM)**
linking requirement → design section → decision/ADR → task packet → change → test → evidence
→ release. Absence of an RTM is a blocking pre-baseline defect (§36.2 item 7).

---

# 8. AUTHORITY, PERMISSIONS AND TECHNICAL CAPABILITY

## 8.1 Privilege boundary

PBIM maintains a formal boundary between **GOVERNANCE AUTHORITY** and **TECHNICAL
CAPABILITY**.

Technical capability may include: repository administration; organisation ownership; CI/CD
administration; infrastructure administration; database administration; cloud administration;
deployment credentials; automation credentials.

**Technical capability does not confer governance authority.** A repository administrator who
can merge anything has not thereby been authorised to approve a release.

## 8.2 Authority–Permission Matrix

The canonical Authority–Permission Matrix (§33.4) SHALL identify for every row: principal;
authority role; technical identity; action; scope; environment; approval; verification;
expiration.

Every privileged account with governance-relevant capability SHALL map to a named identity, an
authority role, a declared technical capability, permitted scope, governance scope, and an
expiration or review date.

Unexpected privilege SHALL trigger **`AUTHORITY-PERMISSION-DRIFT`** and the applicable stop.

## 8.3 Dual control and separation of duties

1. Author, approver and verifier SHALL be distinct actors for any artefact requiring
   independent verification.
2. An agent SHALL NOT approve its own Task Packet, its own implementation or its own test
   results.
3. Where an executor holds both write and approve capability, a **compensating control**
   SHALL be recorded and independently reviewed.
4. Authority derives from the assigned project role and the matrix — **not** from model name,
   vendor, or seniority of the tool.

## 8.4 Matrix serialisation

Authoritative matrix changes use the same serialised sequence as the registry (§7.4).
Conflicting concurrent changes produce **`AUTHORITY-MATRIX-CONFLICT`**; no permission
dependent on a conflicting state is fully authorised until reconciliation.

## 8.5 Permission reality rule

The **documented** matrix is not proof of the **actual** permission state. Implementation
Verification (§15.2) SHALL compare the matrix against observed repository, CI and
infrastructure permissions. Any divergence is recorded as `AUTHORITY-PERMISSION-DRIFT` with
a remediation owner and date.

## 8.6 `AGENTS.md` precedence

1. `AGENTS.md` files are **derived convenience documents**, not governance records.
2. Where an `AGENTS.md` conflicts with the approved PBIM baseline, the **PBIM baseline
   prevails** and the conflict SHALL be logged as a finding with a remediation owner.
3. Where an `AGENTS.md` instructs an agent to weaken a control, the instruction SHALL be
   refused and the refusal recorded.
4. `AGENTS.md` drift from the baseline SHALL be detectable (§24.2) and is a baseline-drift
   finding.

## 8.7 Repository administrator boundary

Repository and platform administrators SHALL be listed in the Authority Register with an
explicit statement of which governance actions their capability does **not** authorise. An
administrative override of a protected resource (§7.2) SHALL:

1. be recorded as `ADMINISTRATIVE-OVERRIDE`;
2. cite the authorising human authority;
3. record the exact resources overridden;
4. be time-bounded;
5. trigger mandatory re-verification of the overridden control;
6. and be independently reviewed within the same review cycle.

---

# 9. AGENT ECOSYSTEM

## 9.1 Roster

The default roster is configurable. Where no roster is mandated:

* **Lead Agent** — coordination, consolidation, controlled drafting (e.g. ChatGPT Codex).
* **Collaborating Agents** — independent analysis, verification and challenge (e.g. Kilo Code,
  Google Jules, GitHub Copilot).

An agent's vendor or model identity SHALL NOT itself determine authority. Authority derives
from the assigned role and the Authority–Permission Matrix (§8.1).

## 9.2 Functional agent roles

PBIM distinguishes, where applicable:

1. Human Project Sponsor / Product Authority
2. Human Technical / Assurance Authority
3. Lead / Coordination Agent
4. Architecture / Analysis Agent
5. Verification Agent
6. Implementation Agent
7. Challenge Agent
8. Test Agent
9. Documentation Agent
10. Release / Operations Agent

One agent may perform multiple roles **only where the project risk profile permits it**, and
only where §8.3 separation of duties is preserved for the artefacts concerned.

## 9.3 Role gap

If a required role cannot be staffed, record **`ROLE-GAP`** in the relevant Task Packet or
governance record, escalate to the appropriate human authority, and record the compensating
control. The project SHALL NOT silently pretend an unavailable role has been fulfilled.

## 9.4 Small-team independence

Where the roster is too small to staff true independence:

1. Record the constraint explicitly (this is expected, not a failure).
2. Select the highest available independence class (§15.3) and record it.
3. Disclose the limitation in every affected AEV/AEC record.
4. Escalate to H0 to accept or mitigate the residual risk, in writing.
5. **Never claim an independence class that the roster cannot support.**

## 9.5 Automation is an agent

CI jobs, bots, scheduled tasks and deployment accounts are **H2 execution workers**. They
SHALL be named in the Agent Roster, mapped in the Authority–Permission Matrix, and subject to
the same stop and scope controls. Automation SHALL NOT be treated as an authority.

## 9.6 Compromised automation

Where automation is suspected compromised, record **`AUTOMATION-COMPROMISED`**, revoke its
credentials, preserve its logs as evidence, treat every artefact it touched as suspect, and
require re-verification of affected controls before advancement.

---

# 10. EVIDENCE AND INTEGRITY

## 10.1 Evidence classification

Every substantive claim SHALL be classified as exactly one of:

| Class | Meaning |
|-|-|
| `VERIFIED FACT` | Directly evidenced by a retained, retrievable artefact |
| `INFERENCE` | Derived by reasoning from verified facts; derivation shown |
| `ASSUMPTION` | Taken as true without evidence; must be listed and tested |
| `PROPOSAL` | Suggested future state; not yet decided |
| `RISK` | A potential adverse outcome with likelihood/impact reasoning |
| `UNKNOWN` | Recognised gap; owned by a named party with a due date |

No substantive architectural claim SHALL be presented as fact without appropriate evidence.
An `ASSUMPTION` presented as a `VERIFIED FACT` is a reportable nonconformity.

## 10.2 Evidence integrity

Material evidence SHALL preserve: source artefact; integrity hash; original timestamp; author
identity; verifier identity; verification result.

Raw evidence SHALL remain retrievable by authorised independent reviewers. Evidence SHALL NOT
be recreated from memory when the original artefact can be retained.

**Evidence verification** requires that a verifier confirm the evidence actually demonstrates
the claim — not merely that it exists. **Evidence-of-evidence** (the hash, the signature, the
CI run identity) SHALL itself be verifiable by a third party.

## 10.3 Integrity anchors

Material authoritative artefacts SHALL have integrity anchors providing:

1. immutability or equivalent protection;
2. out-of-band protection;
3. independent verifiability;
4. durability;
5. association with a specific revision.

The artefact and its integrity anchor SHALL NOT depend on the same compromised trust domain
where independent protection is required.

## 10.4 Evidence trust boundary

Where evidence is stored in a system writable by the party it is meant to constrain, the
evidence does not constrain that party. Material approval and verification evidence SHALL be
stored where the verified party cannot unilaterally alter it, or SHALL be countersigned by an
independent party.

## 10.5 Evidence retention

Material evidence SHALL include, where applicable: test logs; deployment logs; Task Packet
approvals; registry changes; permission-matrix changes; stop records; reset records; emergency
reconciliations; release evidence; operational-readiness evidence.

Retention periods SHALL be set at instantiation. Evidence SHALL NOT be deleted on the basis of
a stop, reset or supersession.

---

# 11. AEA — ARCHITECTURAL ENGINEERING ANALYSIS

## 11.1 Purpose

The AEA is the **independent analysis** stage. Its function is to discover what the proposed
architecture is trying to do, what is sound, what is incomplete, contradictory, ambiguous,
over-complex, missing, automatable, or irreducibly human — **before** any approval is sought.

## 11.2 AEA Query generation

The AEA Query SHALL be prepared by the Lead Agent **after** the PBIM has been manually
reviewed and updated (§36). It SHALL identify:

problem statement; current state; desired outcome; constraints; requirements; existing
decisions; dependencies; risks; unknowns; explicit questions; alternatives; trade-offs;
failure scenarios; security considerations; operational considerations; maintainability;
scalability; cost and complexity; migration implications; verification criteria;
recommended architecture; dissenting views; unresolved questions; evidence references.

The Query SHALL also state the **analysis directive**: the agent's task is not to improve
wording or formatting, and every proposal is subject to verification.

## 11.3 Independence of analysis

The AEA Query SHALL be independently reviewed by each designated collaborating agent. Each
report SHALL:

1. be produced without sight of other agents' reports (independent first);
2. classify every substantive claim per §10.1;
3. cite the specific section, line or artefact relied upon;
4. record what it could **not** verify, and why;
5. state its own conflicts of interest (§15.2);
6. conclude with a decision from the decision set (§11.5);
7. and be labelled as an **analysis report**, never as an approved architecture.

## 11.4 Generic vs concrete separation

Every AEA report SHALL distinguish:

* **Generic architecture** — rules that apply to all future projects instantiating PBIM.
* **Concrete example** — detail that exists only because of one project.

Example-specific detail SHALL NOT be promoted into the generic architecture. This is the
principal defence against template contamination.

## 11.5 AEA decision set

`ARCHITECTURALLY VIABLE` · `VIABLE WITH CONDITIONS` · `NOT YET VIABLE` · `BLOCKED`

AEA reports do not approve. Approval occurs only at AEV (§12.3).

## 11.6 AEA outputs

| Artefact | Owner | Content |
|-|-|-|
| AEA Query | Lead | The controlled analysis brief |
| AEA Report | Each collaborating agent | Independent classified analysis |
| AEA Findings Register | Lead | Consolidated findings, deduplicated, owners assigned |

---

# 12. AEV — ARCHITECTURAL ENGINEERING VERIFICATION

## 12.1 Controlled AEV candidate

The Lead Agent SHALL consolidate AEA reports into a **controlled AEV baseline candidate**.
The candidate SHALL:

* preserve material dissent verbatim or by explicit reference;
* reference source evidence for each control;
* record contradictions between reports rather than resolving them silently;
* distinguish verified design from proposed design;
* identify unresolved issues with named owners;
* maintain revision history and parent revision;
* map each finding to a proposed control or an explicit rejection with rationale.

## 12.2 Evidence-based approval

AEV approval SHALL be evidence-based and role-authorised. Consensus alone SHALL NOT create
authority. A reviewer SHALL NOT be treated as independent merely because it holds a different
title.

## 12.3 AEV decision set

`APPROVE` · `APPROVE WITH MATERIAL OBSERVATION` · `CONDITIONAL APPROVAL` · `DISAPPROVE` ·
`CHALLENGE-BLOCKED`

An AEV decision SHALL be accompanied by: the findings verified; the findings rejected and
why; residual risks accepted; and the conditions attached. A conditional approval SHALL name
its conditions and their closure evidence — an unenforced condition is an open finding.

## 12.4 Unanimity rule

The default approval rule is **unanimous approval by all designated collaborating agents**.
Where unanimous approval is not achievable, the divergence SHALL be escalated to H0 (§16.4),
never resolved by majority vote alone.

## 12.5 Bounded convergence

To prevent indefinite revision oscillation:

* **Revision cap.** If the AEV is not approved by revision **1.4**, no further AEV revisions
  SHALL be produced in that cycle.
* **Restart instead of revision.** On reaching the cap, the Lead Agent SHALL request the
  **full evidence set** and generate a **new PBIM document** to restart the cycle:
  1. the current PBIM document;
  2. the AEA Query document;
  3. all AEA reports;
  4. all AEV statement revisions;
  5. all AEV responses;
  6. all AEC Adversarial Duel documents;
  7. all AEC results.
* **Why this rule exists.** Revision convergence by continued editing of the same artefact
  produces oscillation, not convergence. Restating the problem from the consolidated evidence
  is a different and more reliable operation.
* The restarted PBIM SHALL be manually updated before a new AEA Query is prepared (§36).

## 12.6 Rejection handling

If any agent disapproves: record the blocking findings; resolve them; issue the next revision;
reshare to **all** collaborating agents for review and approval. Partial reshare to only the
dissenting agent is prohibited — approval must be re-established by the whole set.

---

# 13. AEC — ARCHITECTURAL ENGINEERING CHALLENGE

## 13.1 Adversarial challenge gate

After AEV approval, an adversarial challenge SHALL attack the architecture rather than refine
it. **A challenge that only refines is not a challenge** and SHALL be rejected as
non-compliant with §4 P12 intent.

The challenger SHALL attempt to demonstrate whether the architecture can:

* contradict itself;
* permit unauthorised authority;
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
* defeat operational-readiness controls;
* be captured by majority or collusion;
* be falsely closed.

## 13.2 Threat model

Challenges SHALL be organised against a declared adversary model. Minimum personas:

| ID | Adversary | Capability assumed |
|-|-|-|
| A1 | Honest error | No malice; plausible mistake |
| A2 | Ambiguous authority | No malice; genuine uncertainty about permission |
| A3 | Malicious principal | Deliberate policy violation |
| A4 | Compromised principal | Valid credentials, hostile intent |
| A5 | Privileged administrator | Full repository/infra capability |
| A6 | Compromised automation | Malicious or faulty bot/CI |
| A7 | Stale agent context | Acting on outdated instructions |
| A8 | Evidence manipulator | Can write to the evidence store |
| A9 | Artifact loss | Accidental or deliberate deletion |
| A10 | Human unavailability | H0/H1 temporarily or permanently absent |
| A11 | Concurrent governance | Simultaneous conflicting decisions |
| A12 | Irreversible change | Destructive migration or deletion |
| A13 | Governance drift | Slow divergence from baseline |
| A14 | Emergency pressure | Deadline used to justify bypass |
| A15 | Conflicting constitutional reading | Two parties, two valid interpretations |
| A16 | Collusion / majority capture | Reviewers acting in concert |
| A17 | Scope inflation | Many individually approved changes, one material aggregate |
| A18 | Agent goal hijack | Objective or instruction substitution through untrusted content (ASI01) |
| A19 | Tool misuse | Agent invoking a tool or capability beyond its assignment (ASI02) |
| A20 | Agent identity and privilege abuse | Shared, over-broad or non-attributable agent credentials (ASI03) |
| A21 | Agentic supply-chain compromise | Compromised model, tool, plugin or MCP-style server (ASI04) |
| A22 | Unexpected code execution | Agent-authored or agent-induced code execution (ASI05) |
| A23 | Memory and context poisoning | Corrupted persistent context or injected history (ASI06) |
| A24 | Insecure inter-agent communication | An agent message trusted as an instruction (ASI07) |
| A25 | Cascading failure | One agent's error propagating through dependent agents (ASI08) |
| A26 | Human–agent trust exploitation | Flattering or confident output substituted for verification (ASI09) |
| A27 | Rogue agent | An agent exceeding its task to pursue its own objective (ASI10) |

A18–A27 are **mandatory** personas, not optional. An agentic architecture challenged without
them is not challenged. The identifiers in the final column are the OWASP Top 10 for Agentic
Applications 2026 entries (§0.2.4) and are recorded so a finding can be traced to a published
taxonomy rather than to the reviewer's preference.

**Independence requirement for the challenge.** The challenger SHALL disclose, before
reporting: whether it authored the architecture; whether it authored the baseline under
challenge; whether it participated in a previous challenge of this baseline; whether it holds
repository modification capability; whether it is organisationally independent of the design
and implementation owners; and whether its evidence was independently generated. An agent
SHALL NOT claim independence it cannot evidence, and **epistemic independence SHALL NOT be
presented as organisational independence** (§15.4). Where the required independence cannot
be established, the correct outcome is `CHALLENGE-BLOCKED` — not a challenge performed under a
disclosed limitation.

## 13.3 Load-bearing assumptions

Each challenge SHALL enumerate the **load-bearing assumptions** of the architecture — the
propositions whose failure would invalidate the design — assign each an `LA-NN` identifier
(§7.3), and record a verdict on each: `HOLDS` · `HOLDS WITH CONDITIONS` · `FAILS` ·
`UNKNOWN`. Each assumption SHALL state the mechanism by which it is expected to survive.

If **any** load-bearing assumption is `FAILS`, the challenge SHALL record **architectural
invalidation** and a full architectural reset is required (§18.4). An assumption whose
survival mechanism is unidentified has not been shown to hold; it is `UNKNOWN`, and an
unresolved `UNKNOWN` on a load-bearing assumption is treated as `FAILS` until proven.

## 13.4 Finding classification and disposition

Every finding SHALL be classified with exactly one of these five severities. The vocabulary
is closed; a finding MAY NOT be assigned a severity outside it, and severity MAY NOT be
lowered for any reason including closure convenience.

| Severity | Meaning |
|-|-|
| `BLOCKING` | A load-bearing architectural assumption is invalidated, **or** the architecture permits a material governance bypass that no existing control contains. Blocks advancement until resolved. |
| `MATERIAL` | A significant weakness exists; the architecture remains recoverable through a defined amendment. Becomes an AECC closure criterion (§14.2). |
| `CONTAINED` | A weakness exists, but a **named, identified** existing control limits its effect. Naming the mechanism is part of the finding; a weakness is not `CONTAINED` because an administrator *should not* act, a human *would notice*, an agent *would probably* stop, or an implementation *could later* fix it. |
| `MINOR` | A non-load-bearing improvement, clarity fix or ambiguity. |
| `FALSE POSITIVE` | The apparent defect does not violate the architecture when the architecture's defined boundaries are correctly applied. Requires the boundary reasoning to be recorded, not merely asserted. |

**Annotations are not severities.** `OBSERVATION`, `NOTE`, `NIT`, `FOR DISCUSSION` and
similar labels SHALL NOT be used to park a finding outside the severity scale. A remark that
is not a finding belongs in the report's narrative, not in the findings register.

Every `BLOCKING` or `MATERIAL` finding SHALL carry: finding ID (`FND-NNNN.NN`); the attack
attempted; the preconditions for success; the exploit path; the control expected to stop it;
the **actual** result (`prevents` / `detects` / `contains` / `fails`); evidence; severity;
architectural impact; and the resolution requirement if the finding is valid. A finding that
does not state how it would have succeeded is an assertion, not a finding.

## 13.5 Challenge decision set

`AEC PASS` · `AEC PASS WITH AMENDMENTS` · `AEC FAIL — RETURN TO AEV` · `AEC RESET REQUIRED` ·
`CHALLENGE-BLOCKED`

| Verdict | Condition |
|-|-|
| `AEC PASS` | No `BLOCKING` or `MATERIAL` architectural finding remains. |
| `AEC PASS WITH AMENDMENTS` | No fundamental invalidation, but material amendments are required before closure. **This is not closure.** |
| `AEC FAIL — RETURN TO AEV` | A load-bearing architectural failure exists but the foundation survives amendment. |
| `AEC RESET REQUIRED` | The foundational assumptions are invalidated; the current baseline must undergo Architectural Reset (§18.4). |
| `CHALLENGE-BLOCKED` | The challenge could not be performed to the required independence or scope (§13.2). |

Mandatory material findings become AECC closure criteria and SHALL be closed before
implementation authorization. A challenger MAY NOT authorize advancement to the next gate; a
challenger reports, and the Lead Agent and named authority act (§14.4).

---

# 14. AECC — ARCHITECTURAL ENGINEERING CHALLENGE CLOSURE

## 14.1 Closure requirement

AEC SHALL NOT be considered closed because the Lead Agent believes the findings are resolved.
Closure SHALL require:

1. a **findings register** covering every finding raised by every challenger;
2. **resolution mapping** from each finding to the control or artefact changed;
3. **evidence** demonstrating each resolution;
4. **independent review** by a party that did not implement the resolution;
5. the **revised baseline** issued as a new revision, not an in-place edit;
6. a **residual-risk determination** per finding;
7. **closure authority** — the named human or authorised role that closed it.

## 14.2 False closure prohibition

A closure that omits any raised finding, or that records a resolution without evidence, or
that relies on the challenger's own concurrence, SHALL be recorded as
**`FALSE-CLOSURE`** and the AECC SHALL be rejected.

### 14.2.1 Administrative closure — the seven moves that are not closure

A finding is closed only when the underlying failure is removed. The following are
**administrative closure** and SHALL each be rejected on sight, whichever name they are given:

1. **Rewriting the finding** — restating the finding until it no longer matches the text of
   the raised finding.
2. **Downgrading severity** — reclassifying `MATERIAL` as `MINOR` to reach a closure count.
3. **Changing terminology** — renaming the defect so the findings register no longer appears
   to contain it.
4. **Reclassifying it as implementation-only** — moving a design defect into an
   implementation backlog when the design permitted it.
5. **Splitting the finding** — dividing one failure path into several small items, each below
   the resolution threshold. The cumulative rule (§20.2) applies to findings as it does to
   changes.
6. **Replacing the challenged baseline** — superseding the architecture so the finding no
   longer describes it, without carrying the finding forward.
7. **Creating a new baseline without resolving the failure** — re-baselining around the defect
   and declaring the previous finding closed against an artefact nobody challenged.

**Test for each closed finding:** can an independent reader, given only the original finding
and the revised baseline, demonstrate that the failure path no longer exists? If not, the
finding is open. Closure evidence is a demonstration, not an assertion.

## 14.3 AECC entry conditions

AECC SHALL NOT be convened until **all** of the following hold. Each is recorded in the
AECC document with evidence, not asserted.

1. Every independent AEC response has been received and indexed (§30.6).
2. Every `BLOCKING` finding has a recorded disposition.
3. Every failed or `UNKNOWN` load-bearing assumption has been addressed.
4. Architectural Reset has been performed where a verdict required it.
5. Material disagreements are resolved, or formally accepted by the named authority with the
   minority position preserved (§16.3).
6. The identifier architecture has been validated against the grammar of §7.1, with a
   `sequence_index`-based ordering check and a void-namespace scan.
7. Authority boundaries have been tested, including the repository-administrator boundary
   (§8.7).
8. Stop conditions and the reset path have been tested, not merely listed.
9. AEC independence has been demonstrated and recorded for each challenger (§13.2).
10. Emergency-change governance has been tested, including delegation expiry (§18.6).
11. Repository and document integrity have been tested, including durable-reference survival
    (§25.2).
12. Implementation and release authority have been defined and separated (§8.3).
13. Every unresolved risk has a named owner and a review date.
14. The architecture is either confirmed, or returned to the appropriate earlier stage —
    **whichever the evidence supports**. The existence of this condition is not a decision to
    confirm.

**None of the following constitutes closure or advancement:** three agents agreeing; a
majority vote; the absence of objections; an expression of confidence; "looks reasonable";
"probably safe"; "implementation can fix it later".

## 14.4 Canonical sequence and lead-agent processing

**AEA → AEV → AEC → AEC Results → AECC → Approved PBIM → Charter Integration → Implementation Verification**

This sequence SHALL NOT be collapsed to reduce elapsed time (§32).

**Lead-agent processing of AEC results — nine ordered steps, performed on the record:**

1. Preserve every material finding exactly as raised. Do not summarise findings away.
2. Identify duplicate findings across challengers and merge them without losing any
   challenger attribution.
3. Distinguish genuine contradictions from terminology differences. A terminology difference
   is a documentation fix, not a defect.
4. Test each disputed finding against the baseline it was raised against — never against the
   challenger's self-declared summary of it.
5. Classify each finding by severity (§13.4) and record the severity rationale.
6. Identify invalidated load-bearing assumptions (§13.3).
7. Determine whether the required amendments are sufficient or whether a reset is required.
8. Convene AECC **only if** the architecture survives steps 1–7. AECC does not begin
   because two or three agents reported PASS.
9. Otherwise return to AEV.

**Aggregation is mandatory.** A challenge round with no comparative synthesis produced by the
Lead Agent has no authoritative verdict and SHALL NOT be treated as complete. The synthesis
is recorded as a controlled artefact with its own response index (§30.6), and the
cross-challenger disagreement — including a split verdict where two agents passed and one
declared blockers — is preserved in it rather than averaged into a single result.

---

# 15. INDEPENDENCE MODEL

## 15.1 Purpose

Independence is the property that makes assurance meaningful. PBIM treats it as a measurable
condition, not an assumption.

## 15.2 Four independence dimensions

For HIGH-ASSURANCE work, independence SHALL be evaluated and disclosed across:

| ID | Dimension | Question |
|-|-|-|
| **I1** | Organisational | Is the reviewer organisationally separate from the implementer? |
| **I2** | Evidence | Does the reviewer derive conclusions from independent evidence? |
| **I3** | Technical | Does the reviewer possess the competence to reach an independent conclusion? |
| **I4** | Governance | Does the reviewer hold decision rights separate from the implementer? |

Each dimension SHALL be recorded as `SATISFIED` / `PARTIAL` / `NOT SATISFIED` / `NOT
APPLICABLE`, with the basis stated.

## 15.3 Independence classes

| Class | Description | Requirement |
|-|-|-|
| **C1 — Independent challenge agent** | Challenger did not author or implement the artefact | Preferred for HIGH-ASSURANCE |
| **C2 — Rotating challenge agent** | Challenger participated in authoring but not the disputed part | Acceptable for STANDARD with disclosure |
| **C3 — Human challenge authority** | Qualified human reviews without authorship | Acceptable for STANDARD; required for constitutional questions |
| **C4 — Self-challenge** | Author challenges own work | **Not independent.** Prohibited for anything above LIGHT |

Where the required class cannot be staffed, record **`CHALLENGE-INDEPENDENCE-FAILED`** and,
if no acceptable alternative exists, **`CHALLENGE-BLOCKED`**. Advancement stops.

## 15.4 Epistemic vs organisational independence

These are **not** interchangeable and SHALL be recorded separately.

* **Epistemic independence** — reaching the conclusion from the evidence rather than from the
  author's reasoning. This is what assurance actually needs.
* **Organisational independence** — separation of reporting lines and incentives.

An agent on a different platform, with a different model, still using the same source
documents, reasoning pattern and evidence store provides **epistemic partial** and
**organisational nil** independence. Recording it as fully independent is a false-independence
finding.

## 15.5 Independence record

Each AEC/AECC SHALL include an Independence Record (§33.15) listing: challenger identity;
class claimed per §15.3; I1–I4 status; shared credentials, decision rights, incentives or
evidence stores disclosed; and an explicit statement of residual independence risk.

## 15.6 Conflicts of interest

Reviewers SHALL disclose: prior authorship of the artefact; financial interest; reporting
relationship; prior disagreement escalated; and any other factor bearing on independence.
Non-disclosure is a governance finding.

---

# 16. CONSENSUS, DISSENT AND CONFLICT

## 16.1 Consensus is not authority

Agreement between agents indicates convergence of judgement, not correctness. Approval
authority derives from the Authority Register and the AEV decision record, never from a vote
count.

## 16.2 Quorum

No quorum rule SHALL substitute for unanimity where §12.4 applies. Where a project adopts a
quorum rule, it SHALL be recorded as a deviation with a named human approver and a stated
residual risk.

## 16.3 Dissent preservation

Approved records SHALL retain material dissent. A revision SHALL NOT remove a dissenting
position; it SHALL record the disposition of that position and the reason. Silently averaging
away dissent is a governance nonconformity.

## 16.4 Majority capture and escalation

If agents deadlock or a majority position suppresses a substantive minority position:

1. The Lead Agent SHALL identify the conflict, the evidence and the classification of the
   disagreement (factual, interpretive, or value/preference).
2. Factual and interpretive conflicts SHALL be resolved by evidence.
3. **Value and preference conflicts are not resolvable by evidence** and SHALL be escalated to
   H0 for decision.
4. The decision, the decider and the retained minority position SHALL be recorded in the
   Decision Ledger.

## 16.5 Conflicting documents

Where two controlled documents assert authority over the same subject, record
**`CONFLICTING-SOURCE`**, designate one canonical source (§5.1), and correct or withdraw the
other. Conflicting sources SHALL be resolved before gate advancement.

---

# 17. TASK PACKETS AND SCOPE ENFORCEMENT

## 17.1 Task Packet

Every material implementation task SHALL be represented by a controlled Task Packet (§33.8)
defining: objective; requirements; direct file scope; generated-file scope; dependency scope;
configuration scope; build-artifact scope; schema scope; infrastructure scope;
external-effect scope; risk profile; required tests; required evidence; authorised executor;
approval authority; verification authority.

**No material work SHALL begin without an approved Task Packet.** Starting work first and
writing the packet afterwards is a scope-control failure.

## 17.2 Machine-readable scope manifest

Each Task Packet SHALL contain a machine-readable scope manifest (e.g. `scope.yml`) listing
include and exclude globs per scope class, so that the actual changeset can be compared
mechanically against the approved scope.

## 17.3 Enforcement

The canonical enforcement mechanism is:

`.github/workflows/task-scope-check.yml`

The workflow SHALL compare the actual changeset against the approved manifest and SHALL fail
the build on out-of-scope change. Out-of-scope change triggers:

**STOP → REPORT → NEW or SUPERSEDING TASK PACKET**

unless a formally authorised emergency mechanism applies (§18.6).

Where CI enforcement is not technically available, an equivalent independent check SHALL be
performed and recorded, and the limitation SHALL be logged as an open finding. Enforcement
that depends solely on the executor's honesty is not enforcement.

## 17.4 Dependency expansion and derived effects

A change to a shared dependency, common library, schema, or configuration SHALL be evaluated
for **derived effects** across all consumers before approval. Approved scope does not
authorise unexamined blast radius.

## 17.5 Task Packet immutability

An approved Task Packet is immutable. Required changes are made by issuing a **superseding
Task Packet** that references its parent, states the delta, and is re-approved. Silent editing
of an approved packet destroys the audit trail.

## 17.6 Task Packet override

Any override of Task Packet scope SHALL: be recorded as `TASK-SCOPE-OVERRIDE`; cite the
authorising human authority; state the exact scope delta; be time-bounded; trigger mandatory
re-verification of affected controls; and be independently reviewed in the same cycle.

## 17.7 Small-packet rule

For LIGHT-profile work, a single combined packet covering a coherent change unit is
acceptable. The **required fields are not reduced**; only the granularity and the review
count are.

---

# 18. STOP, RESET AND EMERGENCY CONTROLS

## 18.1 Stop classes

| State | Meaning | Who may resume |
|-|-|-|
| `STOPPED` | Work halted; conditions safe; awaiting decision | Named resume authority (§18.5) |
| `BLOCKED` | Work cannot proceed; an unresolved blocking condition exists | Only after the blocking condition is cleared |
| `CONSTITUTIONAL-BLOCKED` | CA unavailable or compromised (§6.3) | CA only |

## 18.2 Stop trigger

A governance stop SHALL be triggered by any of: blocking AEV/AEC/AECC finding; scope escape;
integrity-anchor failure; registry or matrix conflict; loss of required authority; missing
evidence; risk classification exceeding threshold; unexplained baseline drift; unverified
rollback capability; instruction to weaken a protected control; or any condition the
Authority Register names as a stop trigger.

## 18.3 Stop enforcement

A material stop SHALL affect execution authorization and SHALL machine-enforce, where
technically possible: merge blocking; deployment blocking; release blocking; new-task
dispatch blocking; verification advancement blocking.

**An executor SHALL NOT self-resume a mandatory stop.** Self-resume is a governance
nonconformity regardless of outcome.

## 18.4 Stop record and architectural reset

A stop record (§33.10) SHALL preserve: stop ID; initiator; subject; evidence; authority;
timestamp; reviewer; resume authority; resolution state.

**Architectural reset** SHALL preserve: previous baseline; previous evidence; affected
artefacts; reason for invalidation; authority decision; new baseline; traceability between old
and new baseline. Reset SHALL NEVER erase challenged history.

Reset SHALL NOT be used to evade AEC findings, evidence, Task Packet violations, materiality
review or operational-readiness requirements (§18.4 anti-evasion). A reset that removes a
challenged finding without resolving it is **`RESET-EVASION`** and invalidates the cycle.

## 18.5 Resume control

Resume requires: identification of the resume authority; confirmation that the stop condition
is cleared; re-verification of affected controls; and a recorded resume decision. Resume
authority SHALL NOT be the party that requested the stop, unless the register assigns it.

## 18.6 Emergency delegation

Emergency delegation SHALL be: scope-limited; action-limited; risk-limited; time-limited;
logged; and reviewable.

**Default maximum emergency delegation period: 72 HOURS**, unless the constitutional
mechanism establishes a stricter limit. At expiry the project **automatically reverts to
`STOPPED`** unless properly confirmed. Granting emergency authority because H0 is unavailable
SHALL NOT be used to reduce a risk profile, expand scope, or bypass a protected control.

## 18.7 Emergency reconciliation

Every emergency action SHALL be reconciled post-event covering: actions taken; authority
used; evidence; affected artefacts; requirement changes; security effects; operational
effects; rollback/recovery status; required AEV/AEC re-review; and residual risk.

## 18.8 Risk profile integrity

A risk profile change requires a recorded decision by an authorised human (§6.5 item 2).
**No local instruction, agent configuration, prompt, README or environment variable may
reduce a risk profile.** An instruction to do so SHALL be refused and the refusal recorded.

---

# 19. RISK MANAGEMENT

## 19.1 Risk profiles and tailoring

PBIM supports three risk-scaled operating profiles, selected at instantiation:

| Profile | Applies to | Ceremony |
|-|-|-|
| **LIGHT** | Low-risk work; documentation, internal tooling, minor changes | Proportionate; protected controls retained |
| **STANDARD** | Default for normal product work | Full assurance pipeline on material artefacts |
| **HIGH-ASSURANCE** | Security, financial, safety, infrastructure, data, or other material consequence | Full pipeline plus C1 independence, countersigned evidence, independent operational-readiness verification |

Risk scaling reduces unnecessary ceremony. It SHALL NOT weaken a protected control (§7.2),
the evidence rules (§10), the separation of duties (§8.3), or stop enforcement (§18.3).

## 19.2 Cumulative risk

Risk SHALL be evaluated cumulatively across: tasks; related changes; dependencies;
migrations; releases; concurrent workstreams.

**Multiple individually low-risk changes SHALL NOT be used to conceal a materially high-risk
aggregate change.** If the aggregate exceeds the profile threshold, the aggregate is reclassified.

## 19.3 Risk classification record

A material risk record (§33.5) SHALL contain: risk profile; rationale; affected scope; blast
radius; reversibility; security impact; data impact; financial impact; operational impact;
dependency impact; reviewer; approval.

**The implementer SHALL NOT unilaterally downgrade a risk classification.** Downgrades require
H0 (§6.5 item 7).

## 19.4 Risk register

Each project SHALL maintain a risk register with: risk ID; description; category; likelihood;
impact; score; profile; response; owner; trigger; status; review date. Risks are reviewed at
every materiality review point (§20.3).

---

# 20. REQUIREMENT AND CHANGE MATERIALITY

## 20.1 Materiality levels

Requirement and change items SHALL be classified by materiality. Thresholds are
project-defined at instantiation, but SHALL be recorded and SHALL NOT be changed to
reclassify a specific item.

| Level | Characteristic | Approval |
|-|-|-|
| **L0** | No effect on scope, behaviour, security, cost or schedule | Executor |
| **L1** | Local effect; reversible; within approved scope | Task Packet approver |
| **L2** | Cross-cutting effect on design, dependency or operations | H1 + change control |
| **L3** | Affects objectives, architecture, security model, cost or external commitments | H0 + full AEA→AECC |

## 20.2 Cumulative materiality

PBIM SHALL evaluate cumulative effects across a common objective, architecture, release,
dependency, blast radius, data set, security boundary, or operational outcome.

**Splitting one material change into several smaller changes SHALL NOT defeat materiality
review.** If the aggregate of individually-approved items reaches a higher level, the aggregate
is reviewed at that level.

## 20.3 Materiality review points

Cumulative materiality SHALL be reassessed at: charter; task dispatch; major implementation
milestone; major requirement change; release; architectural reset; emergency reconciliation.

## 20.4 Requirements baseline

Requirements SHALL be baselined before the design that satisfies them is verified. The RTM
(§7.7) SHALL be the authoritative link. Unbaselined requirements SHALL NOT be treated as
committed scope.

---

# 21. CHANGE CONTROL AND CONFIGURATION MANAGEMENT

## 21.1 Change control

Every change to a baselined artefact SHALL be raised as a **change record** (§33.11) with:
change ID; origin; description; affected requirements; affected artefacts; materiality level;
risk profile; impact analysis; decision; approver; date; resulting revisions.

Emergency changes are permitted under §18.6 and SHALL be reconciled under §18.7.

## 21.2 Configuration identification

Every controlled artefact SHALL be uniquely identified by document ID, revision, content hash
and durable reference (§25). "The latest version" is not an acceptable configuration
identifier.

## 21.3 Configuration control

Changes to controlled artefacts SHALL occur through the change process, SHALL be recorded
against the Identifier Registry where identifiers are affected, and SHALL preserve
traceability to the revision they replaced.

## 21.4 Backlog and iteration discipline

Where the project adopts an iterative method:

1. The backlog is the authoritative record of candidate work; items SHALL carry identifiers
   and materiality levels.
2. An iteration SHALL NOT silently absorb an unplanned material change; it SHALL be raised as
   a change record first.
3. Carried-over items SHALL be re-assessed, not automatically carried.
4. Definition of Done (§22.3) SHALL be satisfied before an item is called done.

## 21.5 Backlog scope creep detection

Where accepted items in one iteration exceed the approved capacity or introduce material
scope not present at iteration start, the project SHALL record `SCOPE-CREEP-REVIEW` and
obtain H0 confirmation.

---

# 22. QUALITY ENGINEERING AND COMPLETENESS

## 22.1 Quality planning

Each project SHALL define its quality approach at instantiation: standards to apply;
verification methods; review depth by profile; test strategy; defect severity model;
acceptance criteria; and who may accept a defect.

## 22.2 Definition of Done (DoD)

An item is done only when: requirements are met and traced; the change is within an approved
Task Packet; required tests pass; required evidence is recorded and verifiable; independent
review is complete where required; documentation and decision records are updated; no open
blocking finding exists; and the operational impact is recorded.

## 22.3 Review placement

PR review, code check and testing activities SHALL be placed in the lifecycle range matching
their actual phase — monitoring and controlling (`7xxx`/`8xxx`) or executing (`6xxx`) — and
**never at an early lifecycle prefix**. A review step mislabelled with a low identifier implies
a false lifecycle position and is a defect (§4 P2, §7.1).

## 22.4 In-line usability of the template

Notes, prompts and placeholders SHALL appear **in the sections where they are naturally
encountered during production**, not collected in an appendix. A project manager using the
template SHALL meet a prompt at the moment the corresponding work arises.

Each prompt SHALL name its designated agent, or state explicitly that it is for all agents.

## 22.5 No empty stubs

A declared section SHALL NOT remain empty, or contain only a heading, an ellipsis, or an
unresolved placeholder, once its implementation stage begins. An unimplemented section SHALL
carry an explicit status marker:

`NOT IMPLEMENTED — OWNER: {{role}} — TARGET: {{date}} — BLOCKING: {{gate}}`

This rule exists because the predecessor template left sections `0004.02`–`0004.09` as empty
stubs while still presenting the document as complete. An empty stub presented as a finished
section is a reporting failure.

## 22.6 Placeholder resolution gate

Before a template or proposal is treated as ready, every `{{placeholder}}` SHALL either be
resolved or be declared intentional with a stated reason. Unresolved placeholders are
reported at the corresponding gate.

## 22.7 Contradictory-source gate

Before advancement, a scan SHALL confirm that no two controlled documents give contradictory
instructions for the same action (§16.5). Contradictory workflow sources were a verified
defect in the predecessor and SHALL be treated as a blocking finding.

---

# 23. OPERATIONAL READINESS AND RELEASE

## 23.1 Release state

A release SHALL progress through:

**IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED**

Implementation verification alone does **not** authorise production release.

## 23.2 Operational readiness checklist

The applicable release checklist (§33.17) SHALL evaluate: monitoring; alert ownership;
rollback; tested recovery; backup; restore; data recovery; incident ownership; dependency
readiness; security readiness; capacity; support ownership; migration recovery; controlled
rollout.

## 23.3 Controlled `NOT APPLICABLE`

An item marked `NOT APPLICABLE` SHALL carry justification; scope basis; risk rationale;
approving authority; reviewer.

**`NOT APPLICABLE` SHALL NOT be used to remove inconvenient controls.** Marking an item N/A
to avoid doing the work is a governance finding.

## 23.4 Release blocking conditions

Release SHALL be blocked where any of the following applies: rollback is untested; alert
ownership is undefined; data recovery is unverified; dependency recovery is untested;
required security readiness is unverified; operational ownership is absent.

HIGH-ASSURANCE releases require **independent** operational-readiness verification — the
implementer may not verify their own readiness.

## 23.5 Release authorization

Release authorization is an H0 decision (§6.5 item 5), recorded with the readiness evidence,
the residual risk accepted, and the rollback plan.

---

# 24. BASELINE DRIFT

## 24.1 Drift definition

PBIM treats divergence from the approved baseline as **`BASELINE-DRIFT`**. Drift includes
potential divergence involving: the PBIM baseline; `AGENTS.md` files; CI workflows; the
Authority–Permission Matrix; the Identifier Registry; protected control definitions; Task
Packets; implementation; and operational configuration.

## 24.2 Drift monitoring

An independent drift-monitoring mechanism SHALL be established where technically feasible
**outside the ordinary execution path**. A monitor controlled by the same path it monitors
provides weak assurance and this limitation SHALL be recorded.

## 24.3 Drift record and remediation

A material drift record (§33.18) SHALL contain source; affected artefact; authority owner;
detection time; evidence; remediation; resolution; reviewer.

**Baseline-drift correction requires appropriate human governance approval.** Drift correction
is not a self-service action.

---

# 25. DURABLE ARTIFACT IDENTITY

## 25.1 Durable references

Authoritative references SHALL survive branch deletion; session expiration; agent workspace
destruction; and branch rewriting. A reference that names only a branch does not.

## 25.2 Immutable reference form

Every authoritative durable reference SHALL contain: durable artefact identifier; immutable
commit or object identifier; integrity hash where applicable.

Symbolic branch names alone SHALL NOT establish authoritative identity.

Required form:

`{{artifact-id}} @ {{immutable-commit-or-tag-object-id}} # {{integrity-hash}}`

The absence of immutable references was a verified blocking defect in the predecessor.

## 25.3 Superseded artefacts

Superseded artefacts SHALL be archived, not silently overwritten. The canonical current
artefact SHALL remain distinguishable from superseded artefacts. Retention periods apply
(§10.5).

---

# 26. GOVERNANCE SELF-AMENDMENT

## 26.1 Constitutional amendment

PBIM SHALL NOT authorise its own constitutional amendment. Constitutional changes require
CA-level authorisation.

## 26.2 Ordinary governance amendment

Ordinary PBIM changes SHALL remain distinguishable from constitutional changes. A change that
alters the authority model, the protected-control set, or the constitutional boundary is a
**constitutional change** regardless of how it is described. Disguising it as an ordinary
change is a governance nonconformity.

## 26.3 Amendment path

Ordinary amendments follow the change path (§21.1) with H1 approval. Constitutional amendments
require CA authorisation, a fresh AEA→AECC cycle on the amended model, and re-issuance of the
baseline. **An amended PBIM does not inherit prior approvals for the amended parts.**

---

# 27. PROJECT LIFECYCLE AND ALIGNMENT PROFILE

## 27.1 Purpose

The alignment exists so that a project manager can relate a PBIM section position to a
recognised project-management concept, and so a developer can read a numeric anchor and infer
roughly how far through the lifecycle the project has reached.

Two rules constrain what it may be used for:

1. **Alignment is a navigation aid, never an identity system.** The numeric anchor
   communicates lifecycle position; it is never the sole semantic identity of an artefact
   (§7.3). A process list SHALL NOT be used to mint identifiers, and renaming a process
   SHALL NOT renumber the lifecycle.
2. **Alignment is pinned to exactly one declared profile.** An artefact citing a process
   model without naming its profile, edition and vocabulary is defective (§0.3 rule 2).

## 27.2 Alignment Profile Registry

A project SHALL declare exactly one profile, record it in the Standards and Regulatory
Applicability Register (§33.21), and use it for the whole project. Changing profile mid-project
is a **material change** (§20.1) requiring a Decision Ledger entry, a re-baseline of affected
identifiers and a fresh challenge (§14.3).

| Profile | Source and edition | Structure | Status |
|-|-|-|-|
| **`AP-1` / `PMBOK8`** | PMI **PMBOK Guide — Eighth Edition**, ANSI/PMI 99-001-2025, published November 2025 (second printing errata applies) | 6 principles; 7 performance domains (Governance, Scope, Schedule, Finance, Stakeholders, Resources, Risk); 5 focus areas; 40 processes, reintroduced as non-prescriptive guidance | **DEFAULT** for new instantiation |
| **`AP-2` / `PMBOK6`** | PMI **PMBOK Guide — Sixth Edition**, 2017 | 49 processes; 10 knowledge areas; 5 process groups | **LEGACY** — continuity for projects already instantiated on it; SHALL NOT be adopted by a new project |
| **`AP-3` / `TAILORED`** | A project-defined alignment: ISO 21502:2020, PRINCE2, SAFe, Scrum, a sector standard, or a bespoke lifecycle | Project-defined | Permitted. SHALL define its own anchors, its own count check and its own vocabulary, and register both |

**Default.** Where no profile is declared, `AP-1` applies. A project that adopts `AP-2` or
`AP-3` without recording the decision and a rationale is a reporting failure (§35.3).

**Why the default changed.** The predecessor documents cited the "8th edition" while
attaching a 6th-edition process list, and a later revision treated the 49-process model as
canon without stating which edition it came from. Both were unverifiable as written. Pinning
the profile makes the citation checkable: edition, date, structure and vocabulary now all
travel with the reference.

### 27.2.1 `AP-1` process vocabulary

Where `AP-1` is declared, PBIM uses the Eighth Edition's own process names. The renamings
that matter most, because the two vocabularies are frequently mixed in the same document:

| Sixth-edition name (do **not** use under `AP-1`) | Eighth-edition name (use) |
|-|-|
| Collect Requirements | Elicit and Analyze Requirements |
| Create WBS | Develop Scope Structure |
| Plan Cost Management | Plan Financial Management |
| Estimate Costs | Estimate Financials |
| Determine Budget | Develop Budget |
| Plan Quality Management | Manage Quality Assurance |
| Estimate Activity Resources | Estimate Resources |
| Plan Communications Management | Plan Communications |
| Plan Risk Management | Plan Risk Management |
| Perform Qualitative Risk Analysis | Perform Risk Analysis |
| Plan Procurement Management | Plan Sourcing Strategy |
| Plan Stakeholder Engagement | Plan Stakeholder Engagement |
| Direct and Manage Project Work | Manage Project Execution |
| Manage Project Knowledge | Manage Project Knowledge |
| Develop Team / Manage Team | Lead the Team |
| Conduct Procurements | — (merged into `AP-1` sourcing practice) |
| Perform Integrated Change Control | Assess and Implement Changes |
| Monitor and Control Project Work | Monitor and Control Project Performance |
| Control Scope / Validate Scope | Monitor and Control Scope / Validate Scope |
| Control Schedule | Monitor and Control Schedule |
| Control Costs | Monitor and Control Finances |
| Control Quality | — (folded into performance-domain check results) |
| Control Resources | Monitor and Control Resourcing |
| Control Procurements | — (folded into `AP-1` sourcing practice) |

Where `AP-1` renames or removes a process that the predecessor `AP-2` list relied on, the
project uses the `AP-1` position for the surviving process and records the removed process as
a **`TAILORED` custom position** in the registry — it does not silently drop the work, and it
does not keep the old name.

### 27.2.2 `AP-1` performance-domain model

`AP-1` organises work by **performance domain**, not by process group. PBIM records the
domain alongside each position as a registry attribute (§7.5) so a reader can navigate by
either view:

| Domain | Governs |
|-|-|
| Governance | Authority, decision rights, tailoring, compliance, closure |
| Scope | Scope management, requirements, scope structure, scope verification |
| Schedule | Schedule management, activity definition and sequencing, schedule control |
| Finance | Financial planning, estimating, budgeting, financial control |
| Stakeholders | Stakeholder identification, engagement planning and management |
| Resources | Resource planning, estimation, acquisition, team leadership |
| Risk | Risk planning, identification, analysis, response planning, monitoring |

Every PBIM section SHALL map to at least one domain. A section that maps to none is either
mis-scoped or belongs in `TAILORED` custom content; either way the gap is recorded rather than
left implicit.

## 27.3 Anchor mapping

PBIM anchors (`§7.1.4`) are PBIM-owned. The mapping below gives each anchor band its
profile meaning, which is what makes `[0004.10]` recognisable as the charter.

| Anchor | `AP-1` domain / phase | `AP-2` knowledge area / process group | Content |
|-|-|-|-|
| `0004` | Governance — initiating | Initiating | PBIM pre-charter integration and the **charter position `[0004.10]`** |
| `0005`–`0009` | Governance / cross-domain — initiating | Custom | Pre-charter custom insertions (§7.1.4) |
| `1xxx` | Governance, Scope, Schedule, Finance, Stakeholders, Resources, Risk — planning | Planning | Planning activities |
| `2xxx`–`3xxx` | as above — planning detail | Planning | Planning detail and later planning sub-positions |
| `4xxx`–`6xxx` | Resources, Risk, Stakeholders — executing | Executing | Execution activities |
| `7xxx`–`8xxx` | all domains — monitoring and controlling | Monitoring and Controlling | Monitoring, control, verification, release readiness |
| `9xxx` | Governance — closing | Closing | Closure and after-life |

**The charter position.** `[0004.10]` is reserved for **Project Charter Development** — the
terminal PBIM position and the point at which PBIM integrates with the project (§28.10). This
is why the anchor was reserved rather than left open: it gives the pre-charter block a
defined, ordered end, and it places the charter where a reader of any profile will look for
it.

## 27.4 Custom section insertion rule

PBIM-specific engineering controls MAY be inserted between any two lifecycle positions.
Insertion SHALL:

1. preserve strict ordering by `sequence_index` — an inserted section SHALL sort between its
   declared neighbours;
2. never renumber an existing position, and never re-use a void namespace (§7.1.3);
3. carry a **process or domain name plus an engineering subtitle**, so a developer understands
   the work (§27.5);
4. be reserved in the Identifier Registry with a `sequence_index` (§7.4, §7.5);
5. appear in the project template **with content** — never as an empty stub (§22.5);
6. be declared in the Alignment Profile if it introduces a custom anchor (§27.2).

Worked examples, all of which order correctly by `sequence_index`:

| Identifier | Section | Ordering proof |
|-|-|-|
| `[0004.01]`–`[0004.09` ] | PBIM pre-charter integration build-out | all < `[0004.10]` |
| `[0004.10]` | Project Charter Development — PBIM terminal position | > `[0004.09]`, < `1xxx` |
| `[0004.01.01]` | PBIM sub-item | `[0004.01]` < `[0004.01.01]` < `[0004.02]` |
| `[0005.01]` | Architecture Evidence Register | `[0004.10]` < `[0005.01]` < `1xxx` |
| `[0005.02]` | Architecture Dependency Review | `[0005.01]` < `[0005.02]` < `1xxx` |
| `[2005.45]` | Custom scope analysis | `2005.4` < `2005.45` < `3005.1` |

## 27.5 Subtitle convention

Where a section corresponds to a process or performance domain whose name is too abstract for
a developer, the section SHALL carry the **process or domain name** plus an **engineering
subtitle**.

> `## [BASE]-[PROJECT]-6011.06 — Implement Risk Responses`
> `### Engineering activity: rollout and rollback drills for the new gateway path`

This is a direct response to the verified finding that process names alone did not tell a
developer what work a section required.

## 27.6 Placement rules

1. **Review, check and test steps belong to executing or monitoring-and-controlling.** A pull
   request review SHALL NOT sit at an initiating or planning position (§22.3). The predecessor
   workflow carried a `0620 COPILOT PR REVIEW`-style identifier that placed review near the
   start of the project; that is prohibited.
2. **Verification of a control belongs after the control is specified.** Implementation
   verification is a monitoring-and-controlling activity, never an initiating one.
3. **Release and operational readiness belong to monitoring and controlling / closing**, never
   to initiating.
4. **Bootstrap belongs before charter.** The ten PBIM sections (§28) are the pre-charter
   integration layer, ending at `[0004.10]`.
5. **A count check belongs to the profile, not to PBIM.** Where the profile has a defined
   process count (`AP-1`: 40; `AP-2`: 49), a project on that profile SHALL include the count
   check in its own AEA report (§22.6) against its declared profile — never against a bare
   number.

# 28. TEN PBIM PRE-CHARTER SECTIONS — IMPLEMENTATION READY

## 28.0 How these sections are used

The following ten sections run in order, and **all ten complete before Project Charter
Development (`[0004.10]`)**. `[0004.10]` is the terminal PBIM section: the point at which
PBIM integrates with the project and hands authority over (§28.10). Each section below
contains, in order:

| Element | Purpose |
|-|-|
| **Purpose** | Why the section exists |
| **Inputs** | What must exist before the section starts |
| **Activities** | What is actually done |
| **Outputs** | The artefacts produced |
| **Records created** | Which register/template instances are populated |
| **Exit criteria** | The verifiable condition that closes the section |
| **Gate** | Which implementation gate (§29) the exit satisfies |
| **Prompt — Lead** | Copy-paste-ready prompt for the Lead Agent |
| **Prompt — Collaborating** | Copy-paste-ready prompt for each collaborating agent |

| Position | Section | Gate satisfied on exit |
|-|-|-|
| `[0004.01]` | PBIM Development and Architectural Initialisation | GATE 0, GATE 1 |
| `[0004.02]` | Initial Project Proposal and Template Generation Prompt | GATE 2 (feeds) |
| `[0004.03]` | Architectural Engineering of Project Proposal — AEA → AEV | GATE 2, GATE 3 |
| `[0004.04]` | Architectural Challenge and Closure of Project Proposal — AEC → AECC | GATE 4, GATE 5 |
| `[0004.05]` | Project Template Generation | GATE 2 (feeds) |
| `[0004.06]` | Architectural Engineering of Project Template — AEA → AEV | GATE 2, GATE 3 |
| `[0004.07]` | Architectural Challenge and Closure of Project Template — AEC → AECC | GATE 4, GATE 5 |
| `[0004.08]` | Project Template Initialisation | GATE 1 |
| `[0004.09]` | Project Initialisation and Controlled Bootstrap | GATE 1 |
| **`[0004.10]`** | **Project Charter Development and PBIM→Project Integration** | **GATE 6 — PBIM terminal boundary** |

**Prompt drafting rules are defined in §30.** Every prompt below conforms to the Prompt
Contract (§30.1: ROLE, CONTEXT, OBJECTIVE, CONSTRAINTS, METHOD, EVIDENCE CLASSIFICATION,
OUTPUT STRUCTURE, STOP CONDITIONS, DECISION SET) **and** the stage response contract of §30.5
where the section issues an AEA, AEV or AEC query.

**Identifier convention inside these sections.** Every decimal group is zero-padded to two
digits (§7.1.1). Sub-items extend the section identifier by a further zero-padded group:
`[0004.01]` → `[0004.01.01]` → `[0004.01.01.05]` (§7.1.2). Prompt artefacts use the
hyphenated form `[0004.01]-PROMPT-LEAD` / `[0004.01]-PROMPT-COLLAB` so a prompt is never
mistaken for a lifecycle position. All such identifiers SHALL be reserved in the Identifier
Registry.

**Position-based evolution.** The nine sections `[0004.01]`–`[0004.09]` are the PBIM
integration build-out. `[0004.10]` is where PBIM stops. A project needing more pre-charter
work allocates `[0005.xx]` and later, never `[0004.11]` and later (§7.1.3, §7.1.4).

---

## 28.1 `[BASE]-[PROJECT]-0004.01` — PBIM Development and Architectural Initialisation

**Purpose.** Establish the PBIM itself: governance, project identity, authority, agent
ecosystem, the AEA→AEV→AEC→AECC pipeline, evidence, the implementation boundary and the
first controlled baseline.

**Inputs.** This PBIM document (manually updated per §36); the canonical evidence set
(current PBIM, prior AEA Query, all AEA reports, all AEV statements, all AEV responses, all
AEC challenges, all AEC results).

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.011]` | Resolve `[BASE]`, `[PROJECT]` and the document identifier; register them |
| `[0004.012]` | Establish CA (§6.1–6.3) and record it with a durable reference |
| `[0004.013]` | Establish H0/H1/H2 roles in the Human Authority Register; confirm availability and substitution |
| `[0004.014]` | Publish the Canonical Source Manifest (§5.1); resolve conflicting sources (§16.5) |
| `[0004.015]` | Instantiate the Identifier Registry, Authority Register and Authority–Permission Matrix |
| `[0004.016]` | Declare protected governance resources (§7.2) and their protection mechanism |
| `[0004.017]` | Select the risk profile (§19.1) and record the rationale |
| `[0004.018]` | Set requirement materiality thresholds (§20.1) and set up the RTM (§7.7) |
| `[0004.019]` | Define evidence requirements, integrity anchor location and retention (§10) |
| `[0004.0110]` | Define stop conditions, reset path and emergency delegation bounds (§18) |
| `[0004.0111]` | Define the verification and challenge models, including independence classes (§15) |
| `[0004.0112]` | Record the control state of every control declared here (`DESIGNED`) (§3, §33.12) |
| `[0004.0113]` | Store the controlled PBIM baseline with an integrity anchor and a durable reference (§25.2) |

**Outputs.** PBIM baseline (durable-referenced); Canonical Source Manifest; three
governance registers; Control State Register; risk profile decision; confirmation that the
AEA Query is authorised to be generated.

**Exit criteria.** A durable-referenced PBIM baseline exists at the canonical location; CA,
registers and matrix are recorded and verified; no unresolved `CONFLICTING-SOURCE` finding;
every declared control carries a state; a named human has authorised the AEA Query.

**Gate.** Satisfies **GATE 1 — Initialization**.

### Prompt `[0004.01]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent / PBIM Coordination Agent for [PROJECT]. You coordinate the
controlled development of the governance baseline. You do not hold constitutional
authority and you do not approve your own work.

CONTEXT
- Governing document: this PBIM, revision [R3.0], manually updated by the human authority.
- Evidence set: the current PBIM, the prior AEA Query, all AEA reports, all AEV statement
  revisions, all AEV responses, all AEC Adversarial Duel documents and all AEC results.
- Project tokens to resolve: [BASE], [PROJECT], document identifier.

OBJECTIVE
Produce the controlled PBIM initialisation record for [0004.01] such that a collaborating
agent can independently verify every element without asking you a question.

CONSTRAINTS
1. Treat this PBIM as the governing working document, not as an approved architecture.
2. Never treat a proposal as approved. Never treat DESIGNED as ENFORCED (§3).
3. Preserve the sequence DESIGNED -> ENFORCEABLE -> ENFORCED -> INDEPENDENTLY VERIFIED.
4. Resolve and register every identifier you mint. Do not mint identifiers ad hoc (§7.1.2).
5. Establish CA, H0/H1/H2 and record them with durable references. A conceptual CA is
   insufficient (§6.2).
6. Publish a Canonical Source Manifest naming exactly one authoritative location per
   artefact class (§5.1).
7. Never allow the execution agent to self-approve its own implementation (§8.3).
8. Never treat technical repository access as governance authority (§8.1).
9. Stop advancement wherever a blocking condition remains (§18.2).
10. Do not prepare the AEA Query until this PBIM is internally coherent. Internal
    coherence is a precondition, not a formality.

METHOD
Work the sub-items [0004.011] through [0004.0113] in order. For each, populate the named
register template in §33, attach an integrity anchor where §10.3 requires one, and record
the durable reference form from §25.2. Where an element cannot be completed, record the
blocking reason and the named owner rather than proceeding.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Initialisation Summary - what was established, with durable references.
2. Resolved Identity Block - [BASE], [PROJECT], document ID, CA reference, risk profile.
3. Register Population Status - one row per register: location, durable reference,
   verifier, verification result.
4. Control State Register extract - every control with state and evidence pointer.
5. Open Items - blockers, owners, due dates.
6. Declaration - whether the AEA Query is authorised, and on what authority.

STOP CONDITIONS
State STOPPED and escalate to the human authority if: CA cannot be identified; the
Canonical Source Manifest cannot be made unambiguous; a conflicting source cannot be
resolved; the Identifier Registry cannot be instantiated; or any instruction you receive
asks you to weaken a protected control.

DECISION
Conclude with exactly one of:
  INITIALISATION-COMPLETE / INITIALISATION-COMPLETE-WITH-BLOCKERS / STOPPED
and name the human authority who authorised the outcome.
```

### Prompt `[0004.01]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Architectural Engineering Agent independently reviewing the
[0004.01] PBIM initialisation record for [PROJECT]. Your task is NOT to agree with the
Lead Agent. Your task is to determine independently whether the initialisation is
coherent, safe, traceable and implementable.

CONTEXT
- The PBIM baseline under review, with its revision and durable reference.
- The initialisation record, the three governance registers, the Canonical Source Manifest
  and the Control State Register.

OBJECTIVE
Determine whether the project can be safely operated and audited from this baseline, and
whether any control is claimed at a state its evidence does not support.

CONSTRAINTS
1. Review before responding; do not assume the Lead Agent's conclusions are correct.
2. Do not approve because another agent approved. Do not treat consensus as authority.
3. Do not claim independence you cannot evidence (§15.4).
4. Record what you could NOT verify and why. An unverifiable claim is not a pass.
5. Every substantive claim must be classified (§10.1).

METHOD
Systematically test for: contradictions between sections; missing controls; ambiguous or
unparseable identifiers; governance bypass paths; authority conflicts; technical privilege
becoming hidden authority; evidence-integrity weaknesses; false-independence risk;
registry failure modes; Task Packet scope-escape mechanisms; stop/reset/emergency bypasses;
cumulative risk and materiality weaknesses; baseline-drift mechanisms;
operational-readiness weaknesses; design-versus-implementation confusion; and assumptions
requiring human confirmation.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope of Review - what you read, what you could not access.
2. Findings - each with ID, affected section, classification, evidence, impact.
3. Claim Classification Table - every substantive claim you rely on, marked
   VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK / UNKNOWN.
4. Independence Disclosure - your class per §15.3, I1-I4 status, shared credentials,
   decision rights, incentives and evidence stores.
5. Dissent - any position you hold that the record does not reflect.
6. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
Conclude with exactly one of:
  APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
  CHALLENGE-BLOCKED
State the evidence supporting the decision. If you cannot establish the independence the
risk profile requires, the correct answer is CHALLENGE-BLOCKED, not APPROVE.
```

---

## 28.2 `[BASE]-[PROJECT]-0004.02` — Initial Project Proposal and Template Generation Prompt

**Purpose.** Generate the first project proposal and the project-template-generation
instruction set from the approved PBIM.

**Inputs.** An AECC-closed, approved PBIM baseline (`[0004.01]` exit satisfied); the
Canonical Source Manifest; the risk profile.

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.021]` | State the project business objective, problem statement and desired outcome |
| `[0004.022]` | Capture constraints, dependencies, assumptions and unknowns |
| `[0004.023]` | Capture requirements at a level sufficient for baselining and RTM creation |
| `[0004.024]` | Define expected outputs and acceptance criteria |
| `[0004.025]` | Select the delivery framework and record the §0.2 conformance decision |
| `[0004.026]` | Define the engineering model: governance/assurance/execution, risk profile, gates |
| `[0004.027]` | Define template requirements: 49-process sections, custom insertions, in-line prompts |
| `[0004.028]` | Define agent responsibilities, authority boundaries and independence classes |
| `[0004.029]` | Define verification requirements, evidence model and stop conditions |
| `[0004.0210]` | State explicitly what the proposal does NOT authorise |

**Outputs.** `[0004.02]` Initial Project Proposal / Template Generation Prompt — a single
controlled document, registered, integrity-anchored and durable-referenced.

**Exit criteria.** The prompt is registered and durable-referenced; every requirement is
traceable to the RTM; the document contains no implementation authorisation; it is internally
consistent with §27 and §28.1.

**Gate.** Feeds **GATE 2 — AEA**.

> **Authority boundary.** `[0004.02]` is a *proposal instruction set*. It is not an
> implementation authorization. It is itself subjected to AEA→AEV→AEC→AECC at `[0004.03]` and
> `[0004.04]`.

### Prompt `[0004.02]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent producing the Initial Project Proposal / Template Generation
Prompt for [PROJECT].

CONTEXT
- Approved, AECC-closed PBIM baseline [0004.01], durable reference [REF].
- Risk profile: [LIGHT | STANDARD | HIGH-ASSURANCE].
- Conformance decision: the delivery framework this project adopts (§0.2).

OBJECTIVE
Produce a single controlled prompt document from which a later, project-specific Project
Template can be generated without further architectural invention.

CONSTRAINTS
1. Derive everything from the approved PBIM baseline. Do not introduce architecture that
   the baseline does not contain.
2. Do not pre-approve implementation. State explicitly what the proposal does not authorise.
3. Every requirement SHALL be traceable into the RTM.
4. Do not encode any assumption that lets an agent skip a governance gate in §29.
5. Separate what is generic (applies to all [BASE] projects) from what is specific to
   [PROJECT]. Example detail must not leak into generic rules (§11.4).
6. Register the artefact, attach an integrity anchor, record a durable reference (§25.2).

METHOD
Address, in order: objectives; constraints; requirements; expected outputs; acceptance
criteria; delivery framework; engineering model; template requirements; agent
responsibilities; verification requirements; explicit non-authorisations. For each,
state the source in the PBIM baseline that authorises it.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Proposal Control Block - document ID, revision, parent, author, authority, date,
   durable reference, status.
2. Project Objective and Outcome.
3. Constraints, Dependencies, Assumptions, Unknowns.
4. Requirements (RTM-ready, each with an ID).
5. Expected Outputs and Acceptance Criteria.
6. Delivery Framework and Conformance Decision.
7. Engineering Model - governance, assurance, execution, profile, gates.
8. Template Requirements.
9. Agent Responsibilities and Independence Classes.
10. Verification, Evidence and Stop Requirements.
11. Explicit Non-Authorisations.
12. Traceability Map - requirement -> PBIM section.

STOP CONDITIONS
State STOPPED if the proposal cannot be derived from the approved baseline, if requirements
cannot be traced, or if you are asked to encode an approval that no authority has granted.

DECISION
Conclude with exactly one of:
  PROPOSAL-READY-FOR-AEA / PROPOSAL-READY-WITH-BLOCKERS / STOPPED
```

### Prompt `[0004.02]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent independently reviewing the [0004.02] proposal instruction
set. Your task is not to improve its wording; it is to determine whether it is safe to feed
into the AEA stage.

CONTEXT
- The approved PBIM baseline [0004.01] and its durable reference.
- The [0004.02] proposal instruction set under review.

OBJECTIVE
Determine whether the proposal instruction set can be executed as written and fed into
the AEA stage without introducing a bypass, an untraceable requirement or an
unauthorised approval.

CONSTRAINTS
1. Verify against the baseline; do not assume consistency because it appears plausible.
2. Classify every substantive claim (§10.1).
3. Do not approve because it reads well. Test whether it can be executed.

METHOD
Determine whether the proposal instruction set is: complete against the PBIM baseline;
coherent; free of pre-emptive implementation authorization; correctly aligned to the 49-process
model (§27.2); free of generic/example contamination; ready to generate a template; and
traced to the RTM. Specifically hunt for: missing objectives; ambiguous requirements; an
instruction that would let an agent skip a gate; an unstated risk profile; a missing
stop condition; an authority that is asserted but not registered; and a decision that no
human authority is empowered to make.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope of Review.
2. Findings - ID, section, classification, evidence, impact, proposed resolution.
3. Claim Classification Table.
4. Independence Disclosure - class, I1-I4, conflicts.
5. Dissent.
6. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
```

---

## 28.3 `[BASE]-[PROJECT]-0004.03` — Architectural Engineering of Project Proposal

**Purpose.** Apply `AEA → AEV → AEC → AECC` to the Project Proposal Prompt. This is the first
full assurance cycle on project-specific content.

**Inputs.** `[0004.02]` proposal instruction set, manually reviewed.

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.031]` | Lead Agent manually reviews `[0004.02]` for internal coherence |
| `[0004.032]` | Lead Agent authors the AEA Query of `[0004.02]` (§11.2) |
| `[0004.033]` | Collaborating agents produce independent AEA reports (§11.3) |
| `[0004.034]` | Lead Agent consolidates reports into a controlled AEV baseline candidate (§12.1) |
| `[0004.035]` | Collaborating agents review the AEV and return a decision (§12.3) |
| `[0004.036]` | On approval, Lead Agent prepares a fresh AEC adversarial challenge (§13) |
| `[0004.037]` | Collaborating agents return AEC results with finding classification (§13.4) |
| `[0004.038]` | Lead Agent prepares AECC closure (§14.1) |

**Outputs.** AEA Query; AEA Reports; AEV Candidate; AEV Responses; AEC Challenge; AEC
Results; AECC Closure record.

**Exit criteria.** AECC record exists with closure authority; every material finding is
resolved or formally dispositioned; the proposal has passed the sequence without a stage
being collapsed.

**Gate.** Satisfies **GATE 3 — AEV**, **GATE 4 — AEC**, **GATE 5 — AECC**.

> **Bounded convergence applies here too.** If the AEV is not approved by revision 1.4, stop
> revising; request the full evidence set and restart with a new PBIM document (§12.5).

### Prompt `[0004.03]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent running the Project Proposal assurance cycle for [PROJECT].

CONTEXT
- Subject under assurance: the [0004.02] proposal instruction set.
- Pipeline: AEA -> AEV -> AEC -> AECC (§11-§14).
- Roster: [list collaborating agents].

OBJECTIVE
Carry the proposal from independent analysis to closed challenge, preserving evidence,
dissent and the stage sequence.

CONSTRAINTS
1. Manually review the subject for internal coherence BEFORE authoring the AEA Query.
   Coherence is a precondition.
2. Do not collapse stages to reduce elapsed time (§14.3).
3. Preserve material dissent; do not average it away (§16.3).
4. Record factual and interpretive conflicts as conflicts; escalate value/preference
   conflicts to H0 (§16.4).
5. Stop revising the AEV after revision 1.4; on failure to converge, request the full
   evidence set and restart with a new PBIM document (§12.5).
6. A challenge that only refines is not a challenge (§13.1).
7. If any load-bearing assumption fails, declare architectural invalidation and require a
   full reset (§13.3).

METHOD
1. Review the subject. Record internal contradictions found.
2. Author the AEA Query with every element required by §11.2.
3. Dispatch to each collaborating agent independently. Collect reports. Deduplicate into
   an AEA Findings Register with owners.
4. Consolidate into a controlled AEV candidate: cite evidence per control, preserve
   dissent, record contradictions, map findings to controls or explicit rejections.
5. Reshare to ALL collaborating agents for decision.
6. On approval, author a fresh AEC challenge using the threat model (§13.2) and requiring
   load-bearing assumption verdicts (§13.3).
7. Consolidate AEC results into a findings register, classify each finding, and produce
   the AECC closure with closure authority named.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Cycle Control Block - subject, revision, roster, dates, durable references.
2. Internal Coherence Review of the subject.
3. AEA Query issued (reference).
4. AEA Findings Register - consolidated, with owners.
5. AEV Candidate - controls with evidence references, dissent preserved.
6. AEV Decisions Received - per agent, per §12.3.
7. Revisions Issued (cap: 1.4) or restart decision.
8. AEC Challenge issued (reference) and threat coverage.
9. AEC Findings Register - classified per §13.4.
10. AECC Closure - resolution mapping, evidence, independent review, residual risk,
    closure authority.
11. Gate status for GATE 3, GATE 4, GATE 5.

STOP CONDITIONS
State STOPPED on: unresolved blocking finding; failed independence (§15.3); architectural
invalidation; exhausted revision cap without approval; or any instruction to skip a stage.

DECISION
Conclude with exactly one of:
  AECC-CLOSED / AECC-CLOSED-WITH-RESIDUAL-RISK / RESTART-REQUIRED / STOPPED
```

### Prompt `[0004.03]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent in the Project Proposal assurance cycle. For each stage you
are given, act in that stage's role. Do not merge stages.

CONTEXT
- Stage: [AEA QUERY | AEV REVIEW | AEV RESPONSE | AEC CHALLENGE | AEC RESULTS].
- Subject and revision under assurance.
- The PBIM baseline and the approved proposal.

OBJECTIVE
Act in the role the assigned stage requires, and establish on independent evidence
whether the proposal architecture survives that stage.

CONSTRAINTS
1. Independent first: do not read another agent's report before producing your own.
2. Classify every substantive claim (§10.1). Cite the specific section relied upon.
3. Do not treat consensus as authority. Do not approve because another agent approved.
4. Disclose your independence class and I1-I4 status every time (§15.2, §15.5).
5. State clearly what you could not verify.

METHOD
- AEA QUERY: Determine what the proposal attempts, what is sound, what is incomplete,
  contradictory, ambiguous, over-complex, missing, automatable, irreducibly human. Separate
  generic architecture from concrete example.
- AEV REVIEW: Test whether the candidate is evidence-based and role-authorised; whether any
  control is asserted without evidence; whether consensus is being substituted for
  authority; whether dissent has been preserved.
- AEV RESPONSE: Return a decision from the §12.3 set with conditions and their closure
  evidence.
- AEC CHALLENGE: Attack the architecture. Attempt to make it contradict itself, permit
  unauthorised authority, convert technical privilege into hidden authority, permit silent
  bypass, lose governance state, accept invalid evidence, permit false independence,
  corrupt the registry, permit Task Packet scope escape, bypass stops, misuse emergency
  authority, conceal defects, defeat baseline integrity, or defeat operational readiness.
  Test each persona in §13.2.
- AEC RESULTS: Give a verdict on every load-bearing assumption, and classify every finding.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
Per stage: Scope; Method; Findings with classification; Claim Classification Table;
Independence Disclosure; Dissent; Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
If the independence the risk profile requires cannot be established, CHALLENGE-BLOCKED is
the correct answer.
```

---

## 28.4 `[BASE]-[PROJECT]-0004.04` — Architectural Challenge and Closure of Project Proposal

**Purpose.** Attack the proposal architecture and record challenge closure before any
template generation.

**Inputs.** Approved AEV for the proposal; collaborating-agent findings.

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.041]` | Publish the challenge domain list and the personas exercised (§13.2) |
| `[0004.042]` | Publish the load-bearing assumption list and collect verdicts (§13.3) |
| `[0004.043]` | Consolidate the findings register with classification (§13.4) |
| `[0004.044]` | Map each material finding to a resolution and evidence |
| `[0004.045]` | Obtain independent review of each resolution |
| `[0004.046]` | Determine residual risk per finding |
| `[0004.047]` | Record closure authority and issue the revised proposal baseline |

**Outputs.** AECC closure record for the proposal; revised proposal baseline; residual-risk
register.

**Exit criteria.** Closure authority named; every material finding resolved or formally
dispositioned with evidence; no finding silently omitted (false-closure check, §14.2).

**Gate.** Satisfies **GATE 5 — AECC**. Authorises advancement to `[0004.05]`.

### Prompt `[0004.04]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent closing the Project Proposal architectural challenge for [PROJECT].

CONTEXT
- Approved AEV revision, AEC challenge issued, and all AEC results received.
- Candidate resolutions from the collaborating agents.

OBJECTIVE
Close the challenge on evidence, not on confidence, and authorise advancement only if the
closure is genuinely complete.

CONSTRAINTS
1. The AEC is NOT closed because you believe findings are resolved (§14.1).
2. Every finding raised by every challenger must appear in the register. Omission is
   FALSE-CLOSURE and invalidates this closure (§14.2).
3. A resolution without evidence is not a resolution.
4. Independent review of a resolution SHALL come from a party that did not implement it.
5. The revised baseline is a new revision, not an in-place edit (§25.3).

METHOD
Build the findings register: ID, challenger, evidence, impact, affected control,
classification. Then for each material finding: proposed resolution, evidence of
resolution, independent reviewer, residual risk. Verify the register is complete against
every challenger's report before issuing closure.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Closure Control Block.
2. Challenge Coverage - domains and personas exercised.
3. Load-Bearing Assumption Register - assumption, verdict, evidence.
4. Findings Register - complete, classified.
5. Resolution Mapping - finding -> control/artefact -> evidence -> independent reviewer.
6. Residual Risk Determination.
7. Closure Authority - named individual, role, date, basis.
8. Revised Baseline Reference.
9. Advancement Authorisation for [0004.05].

STOP CONDITIONS
State STOPPED if: the register is incomplete; any material finding lacks evidence; any
resolution lacks independent review; or architectural invalidation was declared.

DECISION
Conclude with exactly one of:
  AECC-CLOSED / AECC-CLOSED-WITH-RESIDUAL-RISK / RE-CHALLENGE-REQUIRED / STOPPED
```

### Prompt `[0004.04]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent performing the adversarial challenge on the Project Proposal
architecture for [PROJECT].

CONTEXT
- The approved proposal architecture and its revision.
- The PBIM baseline, §13.1 attack list and the §13.2 threat model.
- Your assigned attack domains.

OBJECTIVE
Demonstrate, with concrete scenarios, whether the proposal architecture can be made to
fail - and record a verdict on every load-bearing assumption.

CONSTRAINTS
1. Attack, do not refine. A challenge that improves wording is non-compliant (§13.1).
2. Use concrete scenarios, not abstract concerns. State the exact sequence of events that
   produces the failure.
3. Do not report a finding you cannot demonstrate. Label unproven concerns as UNKNOWN or
   OBSERVATION, not BLOCKING.
4. Record a verdict on every load-bearing assumption you are given.
5. Disclose independence (§15.5).

METHOD
For each assigned domain, construct a concrete scenario naming the actor (from the threat
model), the capability they hold, the exact steps they take, the control that should have
stopped them, and whether it does. Then state whether the architecture permits the outcome
and what evidence proves it.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope and Assigned Domains.
2. Independence Disclosure.
3. Scenario-by-Scenario Results - actor, steps, control tested, outcome, evidence.
4. Load-Bearing Assumption Verdicts.
5. Findings - ID, domain, classification (§13.4), evidence, impact, affected control,
   proposed resolution.
6. Dissent.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
AEC PASS / AEC PASS WITH AMENDMENTS / AEC FAIL - ARCHITECTURAL INVALIDATION /
CHALLENGE-BLOCKED
```

---

## 28.5 `[BASE]-[PROJECT]-0004.05` — Project Template Generation

**Purpose.** Generate the project-specific management and engineering template — the
document a project manager actually uses to run the project.

**Inputs.** AECC-closed approved proposal (`[0004.04]` exit satisfied).

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.051]` | Instantiate all 49 lifecycle sections with project-specific titles (§27.2) |
| `[0004.052]` | Add engineering subtitles wherever the process name is too abstract (§27.4) |
| `[0004.053]` | Insert custom PBIM sections as decimal suffixes at correct lifecycle positions (§27.3) |
| `[0004.054]` | Embed notes, prompts and placeholders **in-line at the point of use** (§22.4) |
| `[0004.055]` | Assign each prompt a designated agent, or state "all agents" (§22.4) |
| `[0004.056]` | Re-sequence review, code check and test steps into executing / M&C ranges (§22.3) |
| `[0004.057]` | Populate every section with resolvable content; no empty stubs (§22.5) |
| `[0004.058]` | Resolve or declare every `{{placeholder}}` (§22.6) |
| `[0004.059]` | Verify the 49-count and ordering programmatically or by explicit check |
| `[0004.0510]` | Register, anchor and durable-reference the template |

**Outputs.** `[0004.05]` Project Template — registered, integrity-anchored, durable-referenced.

**Exit criteria.** All 49 positions present and correctly ordered; custom sections inserted
without breaking sequence; in-line prompts positioned at points of use; every prompt has a
designated agent; no late-stage activity carries an early prefix; no empty stubs; no
unresolved placeholders; the count check passes.

**Gate.** Feeds **GATE 2 — AEA** for the template.

### Prompt `[0004.05]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent generating the project-specific template for [PROJECT].

CONTEXT
- AECC-closed approved Project Proposal [0004.02]/[0004.04] and its durable reference.
- This PBIM baseline, in particular §27 (lifecycle alignment) and §22 (quality/completeness).

OBJECTIVE
Produce a template a project manager can follow start to finish without inventing process,
and a developer can read without guessing what a section requires of them.

CONSTRAINTS
1. Instantiate every one of the 49 positions in §27.2. Run the count check and report the
   result. A count other than 49 is a defect.
2. Add an engineering subtitle to every section whose process name alone does not tell a
   developer what to do (§27.4).
3. Insert custom sections only as decimal suffixes that order strictly between their
   declared neighbours. Never renumber an existing position (§27.3).
4. Place notes, prompts and placeholders in-line at the point of use, not in an appendix
   (§22.4).
5. Every prompt SHALL name its designated agent, or explicitly state "all agents".
6. PR review, code checks and testing SHALL sit in the 6xxx or 7xxx/8xxx ranges, never at an
   early lifecycle prefix (§22.3).
7. No section may be empty, heading-only, or an ellipsis. Where content is genuinely not
   yet available, use the explicit marker form in §22.5 with owner, target and blocking
   gate.
8. Every {{placeholder}} SHALL be resolved or declared intentional with a reason (§22.6).
9. Do not drop a governance gate inherited from the proposal.

METHOD
Work section by section. For each: identifier, process name, engineering subtitle, purpose,
inputs, activities, outputs, records, in-line prompts with designated agent, exit criteria,
gate. Then run the global checks: 49-count; ordering; no early-prefixed late-stage activity;
no empty stub; no unresolved placeholder; no gate lost.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Template Control Block - ID, revision, parent, author, date, durable reference, status.
2. Generated Section Index - identifier, title, engineering subtitle, gate.
3. Full template body, section by section.
4. Global Check Results - 49-count, ordering, stub scan, placeholder scan, gate coverage.
5. Deviations from the approved proposal, each with a change record reference.
6. Open Items with owners.

STOP CONDITIONS
State STOPPED if: the count check fails; ordering cannot be made correct without
renumbering; a governance gate cannot be placed at a valid lifecycle position; or the
proposal does not authorise a section you are asked to add.

DECISION
Conclude with exactly one of:
  TEMPLATE-READY-FOR-AEA / TEMPLATE-READY-WITH-DEFECTS / STOPPED
```

### Prompt `[0004.05]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent independently verifying the generated project template for
[PROJECT].

CONTEXT
- The template under verification and the approved proposal it must faithfully implement.

OBJECTIVE
Establish by inspection and mechanical check that the generated template is complete,
correctly ordered, executable, and a faithful implementation of the approved proposal.

CONSTRAINTS
1. Verify by inspection and by mechanical check where possible; do not accept a claim that
   a check passed without seeing the check.
2. Classify every finding (§10.1). Classify findings per §13.4 where severity is needed.
3. Do not approve a template you did not read in full.

METHOD
Confirm: all 49 positions present and correctly ordered; the count is exactly 49; custom
sections inserted without breaking sequence; in-line notes/prompts/placeholders positioned
where a project manager will actually meet them; every prompt names a designated agent;
late-stage activities (reviews, tests, releases) carry late-stage prefixes; no section is an
empty stub; every placeholder is resolved or declared; no governance gate was silently
dropped; the template does not contradict the approved proposal or the PBIM baseline.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope and Method of Verification - including which checks you ran mechanically.
2. Check Results - one row per check, with PASS/FAIL and evidence.
3. Findings - ID, section, severity, evidence, impact.
4. Claim Classification Table.
5. Independence Disclosure.
6. Dissent.
7. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
```

---

## 28.6 `[BASE]-[PROJECT]-0004.06` — Architectural Engineering of Project Template

**Purpose.** Run `AEA → AEV → AEC → AECC` against the generated template, focused on
operational correctness rather than conceptual soundness.

**Inputs.** `[0004.05]` template; the approved proposal.

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.061]` | Lead authors the template AEA Query — focus: identifier integrity, prompt executability, gate reachability, stop wiring, PBIM consistency |
| `[0004.062]` | Collaborating agents return independent template AEA reports |
| `[0004.063]` | Lead consolidates a controlled template AEV candidate |
| `[0004.064]` | Collaborating agents review and approve/disapprove the template AEV |
| `[0004.065]` | On approval, Lead prepares a fresh template AEC challenge |
| `[0004.066]` | Collaborating agents return template AEC results |
| `[0004.067]` | Lead prepares the template AECC closure |

**Outputs.** Template AEA Query; Template AEA Reports; Template AEV Candidate and Responses;
Template AEC Challenge and Results; Template AECC record.

**Exit criteria.** Template AECC closed; every identifier resolves against the registry;
every prompt is executable by its designated agent; every gate is reachable in order.

**Gate.** Satisfies **GATE 3**, **GATE 4**, **GATE 5** for the template.

### Prompt `[0004.06]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent running the Project Template assurance cycle for [PROJECT].

CONTEXT
- Subject: the [0004.05] template. Reference baseline: the approved proposal.
- Pipeline: AEA -> AEV -> AEC -> AECC.

OBJECTIVE
Establish, on independent evidence, that the template can actually be executed as written -
that its identifiers resolve, its prompts are actionable, its gates are reachable and its
stop conditions are wired.

CONSTRAINTS
1. Do not collapse stages (§14.3).
2. A template that is conceptually elegant but not executable is NOT verified. Executability
   is the subject of this cycle.
3. Preserve dissent; escalate value/preference conflicts to H0.
4. Revision cap 1.4 applies (§12.5).

METHOD
1. Review the template for internal coherence.
2. Author the template AEA Query covering identifier integrity; prompt executability; gate
   reachability; stop-condition wiring; consistency with the PBIM baseline and the approved
   proposal.
3. Dispatch; collect; consolidate into a controlled template AEV candidate.
4. Obtain collaborating-agent decision.
5. On approval, author a fresh template AEC challenge (§28.7 defines the domains).
6. Consolidate and close.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Cycle Control Block.
2. Coherence Review of the template.
3. Template AEA Query and Findings Register.
4. Template AEV Candidate and Decisions Received.
5. Template AEC Challenge and Findings Register.
6. Template AECC Closure with closure authority.
7. Gate status.

STOP CONDITIONS
State STOPPED on: an identifier that cannot resolve; a gate that cannot be reached; a stop
condition that is declared but not wired; exhausted revision cap; or architectural
invalidation.

DECISION
Conclude with exactly one of:
  TEMPLATE-AECC-CLOSED / TEMPLATE-AECC-CLOSED-WITH-RESIDUAL-RISK / RESTART-REQUIRED / STOPPED
```

### Prompt `[0004.06]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent verifying the [0004.05] project template for [PROJECT].

CONTEXT
- The template, the approved proposal and the PBIM baseline.

OBJECTIVE
Establish, on independent evidence, that the template can actually be executed as
written: identifiers resolve, prompts are actionable, gates reachable, stops wired.

CONSTRAINTS
1. Test executability, not elegance.
2. Classify every finding. Do not report a finding without evidence.
3. Disclose independence (§15.5).

METHOD
Determine whether: every identifier resolves against the Identifier Registry; every prompt
is executable by its designated agent without further instruction; every gate is reachable
in the declared order; every declared stop condition is actually wired to something that
enforces it; no section is an empty stub; the template does not contradict the PBIM baseline
or the approved proposal; the 49-count and ordering hold; and late-stage activities carry
late-stage identifiers.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope and Method.
2. Executability Test Results - one row per test, PASS/FAIL, evidence.
3. Findings - ID, section, severity, evidence, impact, proposed resolution.
4. Claim Classification Table.
5. Independence Disclosure.
6. Dissent.
7. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
```

---

## 28.7 `[BASE]-[PROJECT]-0004.07` — Architectural Challenge and Closure of Project Template

**Purpose.** Attack the project-specific template for structural and operational failure modes,
then record challenge and closure.

**Inputs.** Approved template AEV; template AEC challenge.

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.071]` | Assign template-specific attack domains (below) |
| `[0004.072]` | Collect scenario results and assumption verdicts |
| `[0004.073]` | Consolidate the findings register |
| `[0004.074]` | Map resolutions with evidence and independent review |
| `[0004.075]` | Record residual risk and closure authority |
| `[0004.076]` | Issue the revised template baseline |

**Template-specific attack domains.**

| Domain | Attack |
|-|-|
| TD-1 | Identifier collision — two sections or records sharing an identifier |
| TD-2 | Out-of-sequence custom section |
| TD-3 | Prompt with no designated agent |
| TD-4 | Gate that cannot be entered in the declared order |
| TD-5 | Stop condition that an executor can self-resume |
| TD-6 | Task Packet scope escape in the template's own workflow |
| TD-7 | Late-stage activity mislabelled with an early prefix |
| TD-8 | Ephemeral evidence reference (branch name only) |
| TD-9 | `NOT APPLICABLE` readiness item with no justification record |
| TD-10 | Empty stub presented as complete |
| TD-11 | Unresolved placeholder that changes meaning |
| TD-12 | Contradictory instructions between two template sections |
| TD-13 | Template that cannot be instantiated in a different repository layout |
| TD-14 | Section whose exit criteria cannot be objectively evaluated |

**Outputs.** Template AECC closure record; revised template baseline; residual-risk register.

**Exit criteria.** Closure authority named; all material findings resolved or dispositioned;
the revised template is durable-referenced and superseded cleanly.

**Gate.** Satisfies **GATE 5 — AECC** for the template. Authorises `[0004.08]`.

### Prompt `[0004.07]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent closing the Project Template architectural challenge for [PROJECT].

CONTEXT
- Approved template AEV, issued template AEC challenge, and all AEC results.
- Template attack domains TD-1 to TD-14.

OBJECTIVE
Close the challenge on evidence and authorise template initialisation only if the closure is
complete.

CONSTRAINTS
1. Closure is evidence-based, not confidence-based (§14.1).
2. Every finding from every challenger appears in the register; omission is FALSE-CLOSURE.
3. The revised baseline is a new revision with traceability to its parent (§25.3).

METHOD
Build the complete findings register against TD-1..TD-14, with classification, evidence and
affected control. For each material finding, record resolution, evidence and independent
reviewer. Verify completeness against each challenger's report before issuing closure.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Closure Control Block.
2. Domain Coverage Matrix - TD-1..TD-14, challenger, result.
3. Findings Register - complete, classified.
4. Resolution Mapping with independent review.
5. Residual Risk Determination.
6. Closure Authority.
7. Revised Template Baseline reference.
8. Advancement Authorisation for [0004.08].

STOP CONDITIONS
State STOPPED if the register is incomplete, any material finding lacks evidence or
independent review, or architectural invalidation was declared.

DECISION
AECC-CLOSED / AECC-CLOSED-WITH-RESIDUAL-RISK / RE-CHALLENGE-REQUIRED / STOPPED
```

### Prompt `[0004.07]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent attacking the project-specific template for [PROJECT].

CONTEXT
- The template and the approved proposal it implements.
- Assigned attack domains from TD-1 to TD-14.

OBJECTIVE
Demonstrate whether the project-specific template can be made to fail in operation, and
record a verdict on every load-bearing assumption.

CONSTRAINTS
1. Attack, do not refine.
2. Use concrete failure narratives: exact steps, exact identifier, exact control, outcome.
3. Do not claim a finding you cannot demonstrate.
4. Disclose independence.

METHOD
Hunt specifically for: identifier collisions; out-of-sequence custom sections; prompts with
no designated agent; unreachable gates; self-resumable stop conditions; Task Packet scope
escape; late-stage activities with early prefixes; ephemeral evidence references;
unjustified `NOT APPLICABLE`; empty stubs; placeholders that change meaning; contradictory
instructions between sections; template paths that assume one specific repository layout;
and exit criteria that cannot be objectively evaluated.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope and Assigned Domains.
2. Independence Disclosure.
3. Scenario Results - narrative per finding.
4. Findings - ID, domain, classification, evidence, impact, affected control, proposed
   resolution.
5. Dissent.
6. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
AEC PASS / AEC PASS WITH AMENDMENTS / AEC FAIL - ARCHITECTURAL INVALIDATION /
CHALLENGE-BLOCKED
```

---

## 28.8 `[BASE]-[PROJECT]-0004.08` — Project Template Initialisation

**Purpose.** Configure the project environment so the approved template can actually be used:
identifiers, repository, governance files, agents, platforms, directories, environments,
variables, permissions, workflows and evidence stores.

**Inputs.** AECC-closed approved template (`[0004.07]` exit satisfied).

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.081]` | Create the project record with description and attributes |
| `[0004.082]` | Resolve `[BASE]`/`[PROJECT]` and instantiate the Canonical Source Manifest for this repository layout |
| `[0004.083]` | Create the project base folder and documents folder in the development repository |
| `[0004.084]` | Record the production repository and the integration branch |
| `[0004.085]` | Create agent branches — one per agent — so responses push without conflict (§5.2) |
| `[0004.086]` | Populate the Identifier Registry with this project's sections and artefacts |
| `[0004.087]` | Populate the Human Authority Register and the Authority–Permission Matrix |
| `[0004.088]` | Review and update `AGENTS.md` as a derived document, with PBIM precedence recorded (§8.6) |
| `[0004.089]` | Confirm skills folder location and record the decision |
| `[0004.0810]` | Confirm and create the ADR location and Decision Ledger location |
| `[0004.0811]` | Create the Task Packet directory and the `task-scope-check.yml` workflow (§17.3) |
| `[0004.0812]` | Create the evidence store, integrity anchor location and retention policy |
| `[0004.0813]` | Create the drift-monitor path outside the ordinary execution path (§24.2) |
| `[0004.0814]` | Define the secret boundary: where secrets may and may not appear (§10.4) |
| `[0004.0815]` | Declare the protected governance resources and their protection mechanism |
| `[0004.0816]` | Instantiate the Control State Register; set every control to its true state |
| `[0004.0817]` | Record the risk profile, materiality thresholds and stop conditions |
| `[0004.0818]` | Record the RTM skeleton and the retention schedule |
| `[0004.0819]` | Verify instantiation: every identifier resolves; every gate is reachable |

**Agent branch convention.** Default `main/<agent-name>`, e.g. `main/kilo-code`,
`main/google-jules`, `main/github-copilot`, `main/chatgpt-codex`. Each agent pushes its
**responses and records** to the development `docs` folder on its own branch.
**Implementation code** goes to the production repository on the assigned integration branch
(§5.2).

**Secret boundary rule.** Secrets SHALL NOT be committed to any repository, SHALL NOT appear
in any PBIM, prompt, Task Packet, evidence record or log, and SHALL be referenced only by
identifier. A secret boundary violation is an immediate stop.

**Outputs.** Configured project environment; populated registers; declared protected
resources; Control State Register; secret boundary record.

**Exit criteria.** Every identifier resolves; every gate is reachable; registers populated
and verified; protected resources declared with a protection mechanism; the Control State
Register truthfully reflects reality; the secret boundary is defined and enforced.

**Gate.** Satisfies **GATE 1 — Initialization** for the instantiated project.

### Prompt `[0004.08]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent initialising the project environment for [PROJECT] from the
approved template.

CONTEXT
- AECC-closed approved template [0004.05]-[0004.07].
- Target repositories, branches, directories and platforms.
- This PBIM baseline: §5 canonical source, §7 identifiers, §8 authority, §17 task packets,
  §24 drift, §25 durable identity.

OBJECTIVE
Produce a working, auditable project environment in which the approved template can be
executed without any undocumented step.

CONSTRAINTS
1. Instantiate exactly one canonical location per artefact class (§5.1). Two authoritative
   sources for one artefact class is a defect.
2. Development records go to the development docs folder on the agent's own branch.
   Implementation code goes to the production repository on the assigned integration branch
   (§5.2). Never mix them.
3. `AGENTS.md` is derived, not authoritative. Record the precedence explicitly (§8.6).
4. Every privileged account SHALL be in the Authority Register and the
   Authority–Permission Matrix (§8.2).
5. Automation accounts are H2 workers and SHALL be named, scoped and mapped (§9.5).
6. The Control State Register SHALL reflect the TRUE state. A control with no mechanism
   is `DESIGNED`, not `ENFORCED` (§3).
7. Secrets SHALL NOT be committed anywhere and SHALL NOT appear in any document, prompt,
   packet, evidence record or log. Reference them by identifier only.
8. Protected resources SHALL have a real protection mechanism, not a stated intention.
9. Do not claim a control is enforced because you have configured it.

METHOD
Execute sub-items [0004.081] through [0004.0819] in order. For each, record what was
created, its durable reference (§25.2), who verified it, and the verification result. Then
run the instantiation verification: identifier resolution; gate reachability; register
completeness; protection check; secret-boundary check.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Initialisation Control Block.
2. Environment Map - repository, branch, folder, purpose, durable reference.
3. Register Population - each register, location, reference, verifier, result.
4. Protected Resource Declaration - resource, mechanism, verified by.
5. Agent Roster and Branch Assignment.
6. Control State Register - every control with its true state and evidence pointer.
7. Secret Boundary Record.
8. Instantiation Verification Results - each check with PASS/FAIL and evidence.
9. Open Items with owners and dates.
10. Items deferred to Implementation Verification (GATE 7), listed explicitly.

STOP CONDITIONS
State STOPPED if: two canonical sources cannot be resolved; a protected resource cannot be
protected; a secret is found committed anywhere; the registry cannot be instantiated; or a
control would have to be recorded at a state its mechanism does not support.

DECISION
Conclude with exactly one of:
  ENVIRONMENT-READY / ENVIRONMENT-READY-WITH-BLOCKERS / STOPPED
```

### Prompt `[0004.08]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent independently verifying the instantiated project environment
for [PROJECT].

CONTEXT
- The environment as built; the registers; the Control State Register; the Canonical Source
  Manifest.

OBJECTIVE
Establish whether the instantiated environment matches what the registers and the
Control State Register claim - and expose any readiness inflation.

CONSTRAINTS
1. Verify observed state, not documented intent. Read the actual configuration.
2. A control recorded at a higher state than its evidence supports is a blocking finding.
3. Do not accept "it should work" as verification.

METHOD
Determine whether: every identifier resolves against the registry; every declared protected
resource actually has a protection mechanism; the Authority–Permission Matrix matches
observed permissions (§8.5); automation accounts are named and scoped; the development and
production separation holds (§5.2); `AGENTS.md` does not contradict the baseline; the secret
boundary holds and no secret is committed; the drift monitor is outside the execution path;
every gate is reachable; and the Control State Register is truthful.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope and Verification Method - what you inspected and how.
2. Observed vs Documented - any divergence between actual permission/config and the
   registers, recorded as AUTHORITY-PERMISSION-DRIFT.
3. Findings - ID, area, classification, evidence, impact.
4. Control State Audit - controls recorded above their supported state.
5. Claim Classification Table.
6. Independence Disclosure.
7. Dissent.
8. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
```

---

## 28.9 `[BASE]-[PROJECT]-0004.09` — Project Initialisation and Controlled Bootstrap

**Purpose.** Conduct the final controlled bootstrap immediately before
`[BASE]-[PROJECT]-0004.1 — Develop Project Charter`.

**Inputs.** Initialised environment (`[0004.08]` exit satisfied); populated registers;
declared gates and stop conditions.

**Activities.**

| Sub-item | Activity |
|-|-|
| `[0004.091]` | Team-up: confirm the roster, roles, availability, substitution and independence classes |
| `[0004.092]` | Project briefing: objective, constraints, scope boundaries, non-authorisations |
| `[0004.093]` | Workflow orientation: walk the 49-process model and the gate sequence |
| `[0004.094]` | Project-management process briefing: tailoring decision and profile rationale |
| `[0004.095]` | Change management briefing: materiality levels, change path, approval matrix |
| `[0004.096]` | Template implementation confirmation: gates, prompts, designated agents |
| `[0004.097]` | Preview capabilities: state honestly what is ENFORCEABLE, ENFORCED and unverified |
| `[0004.098]` | Contractor and third-party requirements: authority, evidence, independence |
| `[0004.099]` | Procurement: any procurement follows §21 change control and §8 dual control |
| `[0004.0910]` | Timelines, deliverables and the Definition of Done (§22.2) |
| `[0004.0911]` | Operational ownership and project after-life: support, maintenance, closure |
| `[0004.0912]` | Stop-condition briefing and the first stop drill |
| `[0004.0913]` | Confirm H0 availability and the substitution path |
| `[0004.0914]` | Charter readiness review against `[0004.1]` entry criteria |

**Outputs.** Bootstrap record; readiness confirmation; charter entry checklist; capability
declaration; ownership assignments.

**Exit criteria.** H0 available and substitution path confirmed; the team can state the
stop conditions and the escalation path; the charter entry checklist is complete; the
capability declaration truthfully lists unverified controls; operational ownership is named.

**Gate.** Satisfies **GATE 1** completion and authorises entry to `[0004.1]`.

### Prompt `[0004.09]-PROMPT-LEAD`

```
ROLE
You are the Lead Agent conducting the controlled project bootstrap for [PROJECT], preparing
the project to enter Project Charter Development.

CONTEXT
- Initialised environment, populated registers, approved template.
- Charter entry target: [BASE]-[PROJECT]-0004.1 Develop Project Charter.

OBJECTIVE
Establish that the project is genuinely ready to be chartered, and record honestly what is
and is not in place.

CONSTRAINTS
1. Preview capabilities honestly. List every control that is DESIGNED but not ENFORCEABLE,
   and every control that is ENFORCEABLE but not ENFORCED. Do not overstate readiness
   (§3, §29).
2. Do not begin charter work before this bootstrap closes.
3. Operational ownership SHALL be named, not assumed.
4. A stop drill SHALL actually be exercised, not merely described.

METHOD
Run sub-items [0004.091] through [0004.0914]. For the stop drill, initiate a stop condition,
confirm it is enforced, confirm the resume authority is the correct party, and confirm the
stop record is complete. Record the outcome.

EVIDENCE CLASSIFICATION
Classify every substantive claim you make as VERIFIED FACT / INFERENCE / ASSUMPTION /
PROPOSAL / RISK / UNKNOWN. Cite the register, artefact or durable reference relied upon.
Anything you could not complete is recorded as an ASSUMPTION or UNKNOWN with a named owner
and a date - never a silent omission.

OUTPUT STRUCTURE
1. Bootstrap Control Block.
2. Roster, Roles, Availability, Independence Classes.
3. Briefing Record - what was covered and what each participant confirmed.
4. Change Management and Approval Matrix.
5. Stop Drill Result - stop ID, enforcement observed, record completeness, resume path.
6. Capability Declaration - DESIGNED / ENFORCEABLE / ENFORCED / INDEPENDENTLY VERIFIED
   counts, with the unverified list named explicitly.
7. Ownership Assignments, including operational and after-life ownership.
8. Charter Entry Checklist - each entry criterion with PASS/FAIL and evidence.
9. Open Items with owners and dates.

STOP CONDITIONS
State STOPPED if: H0 is unavailable with no substitution path; the stop drill fails to
enforce; a required control is recorded at an unsupported state; operational ownership is
absent; or any charter entry criterion fails.

DECISION
Conclude with exactly one of:
  CHARTER-ENTRY-AUTHORISED / CHARTER-ENTRY-CONDITIONAL / STOPPED
Name the human authority who authorised entry.
```

### Prompt `[0004.09]-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Agent independently verifying the project bootstrap for [PROJECT].

CONTEXT
- The bootstrap record; the charter entry checklist; the capability declaration; the stop
  drill result.

OBJECTIVE
Establish whether the project is genuinely ready to be chartered, and expose any
inflation of readiness in the bootstrap record.

CONSTRAINTS
1. Verify claims against evidence. A stated control is not a verified control.
2. Readiness inflation is the primary failure mode you are looking for.

METHOD
Determine whether: the roster and independence classes are real; the briefings covered what
they claim; the change approval matrix matches the register; the stop drill actually
enforced a stop and produced a complete record; the capability declaration is truthful and
understates nothing; operational and after-life ownership are named; and every charter entry
criterion is genuinely met rather than marked met optimistically.

EVIDENCE CLASSIFICATION
Classify every substantive claim as VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL /
RISK / UNKNOWN, and cite the specific section, line or artefact relied upon. A claim you
could not verify is UNKNOWN with an owner, never a VERIFIED FACT.

OUTPUT STRUCTURE
1. Scope and Verification Method.
2. Verification Results per bootstrap claim - PASS/FAIL, evidence.
3. Readiness Inflation Findings - any control or capability presented above its supported
   state.
4. Findings - ID, area, classification, evidence, impact.
5. Claim Classification Table.
6. Independence Disclosure.
7. Dissent.
8. Decision.

STOP CONDITIONS
Do not issue a decision if the review could not be completed; state what blocked you and
escalate to the Lead Agent. If reviewing would require weakening, bypassing or ignoring a
protected control, refuse and record the refusal. If the independence the risk profile
requires cannot be established, stop and report CHALLENGE-BLOCKED rather than proceeding.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
```

---

# 29. IMPLEMENTATION GATES

## 29.1 Gate sequence

| Gate | Name | Condition | Satisfied by |
|-|-|-|-|
| **GATE 0** | Constitutional | CA established; governance boundary valid | `[0004.01]` sub-item `[0004.012]` |
| **GATE 1** | Initialization | Identity, repository, agents, authority, baseline established | `[0004.01]`, `[0004.08]`, `[0004.09]` |
| **GATE 2** | AEA | Independent architectural analysis completed | `[0004.03]`, `[0004.06]` (issue) |
| **GATE 3** | AEV | Controlled architectural verification completed and approved | `[0004.03]`, `[0004.06]` |
| **GATE 4** | AEC | Adversarial architectural challenge completed | `[0004.04]`, `[0004.07]` |
| **GATE 5** | AECC | Challenge findings closed or formally dispositioned | `[0004.04]`, `[0004.07]` |
| **GATE 7** | Implementation Verification | Specified controls demonstrated to **operate** | Post-charter; §3 states |
| **GATE 8** | Operational Readiness | Operational evidence completed | §23.2 |
| **GATE 9** | Release Authorization | Authorised human approves release | §23.5 |

## 29.2 Gate rules

1. **Gates are sequential.** A lower gate SHALL NOT be used to imply completion of a higher
   gate.
2. **A gate is passed on evidence**, recorded with the passing artefact's durable reference,
   the deciding authority and the date.
3. **A gate may be re-opened** by new evidence. Re-opening is recorded, not silently applied.
4. **GATE 7 is where DESIGNED becomes real.** Until GATE 7, every control in this document is
   `DESIGNED`. GATE 7 evidence promotes controls to `ENFORCED` and, with independent
   verification, to `INDEPENDENTLY VERIFIED`.
5. **Risk profile may add gates.** HIGH-ASSURANCE may require independent operational
   readiness (§23.4) and an extra challenge round. It may not remove any.
6. **LIGHT profile** may satisfy GATE 2–GATE 5 on a single combined artefact where the
   profile permits, provided the artefact still contains all required elements and the
   decision is recorded.

## 29.3 Gate register

Each gate transition SHALL be recorded in the Gate Register (§33.19): gate; condition
evidence; durable reference; deciding authority; date; state transitions granted; conditions
attached; re-opened flag.

---

# 30. PROMPT CONTRACT AND PROMPT QUALITY STANDARD

Every prompt in this document is built to the same contract. A prompt that omits a block is
incomplete and SHALL NOT be dispatched.

## 30.1 The nine mandatory blocks

| # | Block | Purpose | Failure if omitted |
|-|-|-|-|
| 1 | **ROLE** | Fix the agent's identity, authority limits and what it is *not* | Agent assumes authority it lacks |
| 2 | **CONTEXT** | Supply the governing documents, revisions, durable references, roster, profile | Agent reasons from stale or wrong baseline |
| 3 | **OBJECTIVE** | One sentence stating the single outcome required | Scope drift; the agent optimizes the wrong thing |
| 4 | **CONSTRAINTS** | The non-derivation rules and prohibitions that bind this task | The agent does something reasonable but unauthorised |
| 5 | **METHOD** | The ordered procedure, referencing PBIM sub-items | Inconsistent execution between agents |
| 6 | **EVIDENCE CLASSIFICATION** | `VERIFIED FACT` / `INFERENCE` / `ASSUMPTION` / `PROPOSAL` / `RISK` / `UNKNOWN` | Assumptions harden into unexamined "facts" |
| 7 | **OUTPUT STRUCTURE** | The exact sections the response must contain | Unusable responses that must be re-prompted |
| 8 | **STOP CONDITIONS** | When to halt and escalate instead of proceeding | Silent bypass under pressure |
| 9 | **DECISION SET** | The closed set of permitted verdicts | Vague verdicts that cannot gate anything |

## 30.2 Rules for attaching references to a prompt

1. A prompt that says "review the document" without a **durable reference** is incomplete
   (§25.2). Symbolic branch names are not references.
2. Reference blocks SHALL be delimited with labelled `<<START ...>>` / `<<STOP ...>>`
   markers so the agent can tell supplied material from its own output.
3. Every attached reference SHALL be labelled with the artefact it represents, so the agent
   knows whether it is reading an approved baseline, a candidate, or a superseded revision.
4. Where a prompt requires the agent to reason from a *prior stage's output*, that output
   SHALL be attached explicitly. Referring to "the previous step" is a defect.
5. Attached material SHALL be the minimum sufficient set. Attaching an entire evidence
   history to a narrow question degrades response quality.

## 30.3 Agent-specific prompt tailoring

| Agent class | Prompt emphasis |
|-|-|
| **Lead Agent** | Consolidation, evidence preservation, dissent retention, revision control, stop discipline, gate authorisation |
| **Collaborating Agent (analysis)** | Independence first, classification, contradiction hunting, "what could I not verify" |
| **Collaborating Agent (verification)** | Evidence sufficiency, traceability, provenance, reproducibility |
| **Collaborating Agent (challenge)** | Attack not refine, concrete scenarios, assumption verdicts, no unproven findings |
| **Implementation Agent** | Scope discipline, Definition of Done, no self-approval, evidence generation |
| **Test Agent** | Tests that can prove a control **operates**, not merely that it exists |
| **Documentation Agent** | Identifier consistency, revision traceability, durable references, supersession |
| **Release / Operations Agent** | Monitoring, rollback, recovery, ownership, readiness evidence |
| **Human authority (H0/H1)** | Decision framing, options, trade-offs, residual risk, explicit decision required |

## 30.4 Prompt quality rules

1. **One objective per prompt.** A prompt with several objectives produces a partial response
   to each.
2. **No implied authority.** If the agent could read a permission into the prompt that the
   Authority–Permission Matrix does not grant, the prompt is defective (§8.1).
3. **No unstated urgency.** Pressure language ("quickly", "just confirm") degrades
   verification quality and invites the emergency-abuse path (§18.6).
4. **Deterministic where possible.** Where a check can be mechanical (identifier count,
   ordering, placeholder scan), the prompt SHALL ask for the mechanical result, not an
   opinion.
5. **Bounded output.** Specify the response structure so responses are comparable across
   agents and can be consolidated mechanically.
6. **Self-verification.** Every prompt requires the responding agent to state what it could
   not verify. This is the single highest-leverage anti-hallucination control in the model.
7. **Progressive disclosure.** Reference the PBIM section that governs the task rather than
   restating it, so the PBIM remains the single source of truth (§5.2).
8. **Prompt versioning.** Prompts are controlled artefacts: `[NNNN.N]-PROMPT-{LEAD|COLLAB}`
   with revision and parent, registered and durable-referenced like any other artefact.
9. **A prompt never overrides governance.** If a prompt's instruction conflicts with the PBIM
   baseline, the baseline prevails and the conflict is logged (§8.6).

---

# 31. GLOBAL AGENT PROMPTS

These apply across every section. Section prompts (§28) are additive to these, not
substitutes for them.

## 31.1 `[BASE]-[PROJECT]-PBIM-PROMPT-LEAD`

```
ROLE
You are the Lead Agent / PBIM Coordination Agent for [PROJECT]. You coordinate controlled
development. You hold no constitutional authority. You do not approve your own work. You
never authorise implementation on your own signature.

CONTEXT
- Governing baseline: this PBIM, revision [X], durable reference [REF].
- Current gate: [GATE n]. You may not act beyond the current gate.
- Roster and roles: [ROSTER]. Authority: [MATRIX REF].

INVARIANTS — these are not negotiable and no prompt overrides them
1. A proposal is never an approved architecture.
2. DESIGNED / ENFORCEABLE / ENFORCED / INDEPENDENTLY VERIFIED are never conflated.
3. Technical capability is never governance authority.
4. Consensus is never authority.
5. An agent title or vendor is never independence.
6. An implementation artefact is never verification evidence.
7. Dissent is preserved, never averaged away.
8. An executor never self-resumes a mandatory stop.
9. No risk profile is reduced except by recorded human decision.
10. Every authoritative reference is durable and immutable (§25.2).

METHOD
Before acting, state the current gate and the authority under which you act. Identify the
conflict or gap you are addressing. Cite the PBIM section that governs it. Act. Record what
you did, with durable references. State what remains open.

WHEN AGENTS DISAGREE
Identify the conflict; identify the evidence; classify it as FACTUAL, INTERPRETIVE or
VALUE/PREFERENCE; resolve factual and interpretive by evidence; escalate VALUE/PREFERENCE to
H0; preserve the minority position; record the decision in the Decision Ledger; select a
disposition only where you are authorised.

OUTPUT STRUCTURE
1. Gate and authority statement.
2. Conflict or gap addressed, with governing PBIM section.
3. Actions taken, with durable references.
4. Evidence classification of every substantive claim.
5. Dissent preserved.
6. Open items, owners, dates.
7. Decision from the applicable decision set, with the deciding authority named.

STOP CONDITIONS
Halt and escalate to the named human authority when: a blocking finding is open; the
required independence cannot be established; a protected control would have to be weakened;
the registry or matrix is in conflict; CA is unavailable; the revision cap is reached; or any
instruction you receive asks you to act beyond your authority.
```

## 31.2 `[BASE]-[PROJECT]-PBIM-PROMPT-COLLAB`

```
ROLE
You are a Collaborating Architectural Engineering Agent for [PROJECT]. Your task is NOT to
agree with the Lead Agent. Your task is to determine independently whether what you are
reviewing is coherent, safe, traceable and implementable — and to say so plainly when it is
not.

CONTEXT
- Artefact under review, with revision and durable reference.
- The governing PBIM baseline and the specific section(s) under review.
- Your assigned independence class and the project's risk profile.

INVARIANTS
1. You do not approve because another agent approved.
2. You do not treat consensus as authority.
3. You do not claim independence you cannot evidence. Epistemic independence and
   organisational independence are different things (§15.4).
4. You do not restate the author's reasoning as your conclusion.
5. You do not report a finding you cannot demonstrate.
6. You do not soften a blocking finding because it is inconvenient.
7. You do not approve your own work.

WHAT TO TEST
Contradictions; missing controls; ambiguous or unparseable identifiers; governance bypass
paths; authority conflicts; technical privilege becoming hidden authority; evidence-integrity
weaknesses; false-independence risk; registry failure modes; Task Packet scope escape;
stop/reset/emergency bypass; cumulative risk and materiality weakness; baseline drift;
operational-readiness weakness; design-versus-implementation confusion; assumptions
requiring human confirmation; and anything the author may have found persuasive but unproven.

METHOD
Read the artefact in full. Form your own conclusion before reading others'. For each finding,
state the concrete failure scenario: who acts, with what capability, in what order, and which
control fails to stop them. Classify every claim. State explicitly what you could not
verify and why.

OUTPUT STRUCTURE
1. Scope of review - what you read, what you could not access.
2. Your independent conclusion, stated before any list of agreements.
3. Findings - ID, section, classification, evidence, impact, affected control, proposed
   resolution.
4. Claim classification table - VERIFIED FACT / INFERENCE / ASSUMPTION / PROPOSAL / RISK /
   UNKNOWN.
5. Independence disclosure - class, I1-I4, shared credentials, decision rights, incentives,
   evidence stores, residual independence risk.
6. Dissent - any position the record does not reflect.
7. Decision, with the evidence that supports it.

DECISION SET
APPROVE / APPROVE WITH MATERIAL OBSERVATION / CONDITIONAL APPROVAL / DISAPPROVE /
CHALLENGE-BLOCKED
If the independence the risk profile requires cannot be established, CHALLENGE-BLOCKED is
the correct and honest answer. It is not a failure of your review.
```

## 31.3 Specialised collaborating-agent instructions

**Architecture / Analysis Agent.** Attack the conceptual architecture: completeness,
consistency, dependencies, alternatives, scalability, maintainability, failure modes.

**Verification Agent.** Determine whether the architecture is supported by adequate evidence:
traceability, verification criteria, evidence provenance, baseline identity,
reproducibility, unresolved claims.

**Challenge Agent.** Attempt to break the architecture: unauthorised authority, privilege
bypass, evidence manipulation, registry corruption, false independence, Task Packet escape,
stop bypass, emergency abuse, reset evasion, majority capture, false closure.

**Implementation Agent.** Determine whether the architecture can be implemented within real
technical constraints. **The Implementation Agent SHALL NOT approve its own implementation.**

**Test Agent.** Design tests capable of proving that implemented controls actually operate —
including negative tests that a stopped control truly blocks.

**Documentation Agent.** Ensure identifiers are consistent; revisions traceable; decisions
recorded; evidence references durable; superseded artefacts remain identifiable.

**Release / Operations Agent.** Determine whether monitoring, rollback, recovery, support,
security, ownership, deployment and operational readiness are adequately established.

---

# 32. AGENT REVIEW SEQUENCE

## 32.1 Canonical sequence

```
Manual human update of the PBIM
            ↓
Lead PBIM draft / controlled baseline candidate
            ↓
Independent AEA Query
            ↓
Collaborating-agent AEA reports  (independent, no sight of each other)
            ↓
Lead AEV candidate
            ↓
Collaborating-agent AEV review → decision
            ↓
AEV approval
            ↓
Fresh adversarial AEC
            ↓
AEC results
            ↓
AECC closure
            ↓
Approved PBIM baseline
            ↓
Implementation verification (DESIGNED → ENFORCED → INDEPENDENTLY VERIFIED)
            ↓
Manual human review
            ↓
New AEA Query
```

## 32.2 Sequence rules

1. The sequence SHALL NOT be collapsed to reduce elapsed time (§14.3).
2. AEA reports are produced **independently**. Consolidating before reporting destroys the
   control.
3. The AEC SHALL be **fresh**. A challenge issued against an earlier revision does not
   challenge the current one.
4. AECC closure SHALL precede any implementation authorization.
5. Implementation verification (GATE 7) is a separate, later stage. AEA/AEV/AEC/AECC concern
   whether the architecture is **sound**; implementation verification concerns whether the
   controls **operate**. Neither substitutes for the other.
6. On non-convergence, the bounded-convergence rule applies (§12.5): cap revisions at 1.4,
   then restart from the consolidated evidence.

---

# 33. REGISTER TEMPLATES

These are the fill-in templates referenced throughout this document. They are the minimum
field sets. A project MAY add fields; it SHALL NOT drop any field without a recorded change
decision.

> **Instantiation rule.** Replace every `{{placeholder}}`. Populate every table. A template
> left with placeholders is `NOT IMPLEMENTED` and blocks its gate (§22.5, §22.6).

## 33.1 Controlled Document Register

| Field | Value |
|-|-|
| Document ID | `{{DOC-####}}` |
| Title | `{{title}}` |
| Artefact class | `{{class per §5.1}}` |
| Revision | `{{R#.#}}` |
| Parent revision | `{{DOC-#### / R#.#}}` |
| Status | `{{DESIGNED / AEV-APPROVED / AECC-CLOSED / SUPERSEDED / ARCHIVED}}` |
| Author | `{{name / agent}}` |
| Authority | `{{role per Authority Register}}` |
| Date/time | `{{ISO-8601}}` |
| Canonical location | `{{path}}` |
| Durable reference | `{{artefact-id @ commit-or-tag-object-id # hash}}` |
| Integrity anchor | `{{anchor type + location}}` |
| Source evidence | `{{evidence IDs}}` |
| Change summary | `{{what changed and why}}` |
| Verification status | `{{state per §3 + verifier}}` |

## 33.2 Identifier Registry

| Identifier | Kind | Parent | Title | Position | Status | Durable reference | Created | Created by | Verified by | Verified at | Superseded by |
|-|-|-|-|-|-|-|-|-|-|-|-|
| `{{...}}` | | | | | | | | | | | |

## 33.3 Human Authority Register

| Role | Identity | Authority scope | Decision rights | Availability | Substitution rule | Review date | Notes |
|-|-|-|-|-|-|-|-|
| CA | | | | | | | |
| H0 | | | | | | | |
| H1 | | | | | | | |
| H2 lead | | | | | | | |

## 33.4 Authority–Permission Matrix

| Principal | Authority role | Technical identity | Action | Scope | Environment | Approval | Verification | Expiry / review |
|-|-|-|-|-|-|-|-|-|
| | | | | | | | | |

## 33.5 Risk Classification Record

| Field | Value |
|-|-|
| Risk ID | `{{RSK-####}}` |
| Description | `{{...}}` |
| Profile claimed | `{{LIGHT / STANDARD / HIGH-ASSURANCE}}` |
| Rationale | `{{...}}` |
| Affected scope | `{{...}}` |
| Blast radius | `{{...}}` |
| Reversibility | `{{reversible / partially / irreversible}}` |
| Security impact | `{{...}}` |
| Data impact | `{{...}}` |
| Financial impact | `{{...}}` |
| Operational impact | `{{...}}` |
| Dependency impact | `{{...}}` |
| Cumulative aggregate | `{{aggregate across related changes}}` |
| Reviewer | `{{...}}` |
| Approval (incl. any downgrade) | `{{name + role + date}}` |

## 33.6 AEA Report Template

1. Header: stage, subject, revision, durable reference, author, date, independence class.
2. Analysis directive acknowledgement.
3. Independent conclusion.
4. What the architecture attempts to accomplish.
5. What is sound / incomplete / contradictory / ambiguous / unnecessarily complex.
6. Missing controls; processes to redesign; items to keep; items to consolidate; items to
   separate; items to automate; items that must remain human.
7. Generic vs concrete separation findings (§11.4).
8. Requirements traceability assessment.
9. Findings register.
10. Claim classification table.
11. What could not be verified.
12. Decision from §11.5 set.

## 33.7 AEV Candidate Template

1. Header: subject, revision, parent, author, authority, date, durable reference, status.
2. Verification objective.
3. Architectural boundary statement (what this document does and does not claim).
4. Governing model.
5. Controls, each with: statement; evidence reference; verification method; independence
   class required; state per §3.
6. Contradictions between reports, recorded not resolved.
7. Material dissent preserved verbatim or by explicit reference.
8. Findings disposition: accepted into control / rejected with reason / deferred with owner.
9. Unresolved issues with named owners.
10. Residual risks accepted, with the accepting authority.
11. Approval conditions and their closure evidence.
12. Decision block.

## 33.8 Task Packet

| Field | Value |
|-|-|
| Packet ID | `{{TASK-####}}` |
| Parent packet | `{{TASK-#### or none}}` |
| Objective | `{{single sentence}}` |
| Requirements addressed | `{{REQ IDs}}` |
| Risk profile | `{{...}}` |
| **Direct file scope** | `{{include globs}}` / `{{exclude globs}}` |
| **Generated-file scope** | `{{...}}` |
| **Dependency scope** | `{{...}}` |
| **Configuration scope** | `{{...}}` |
| **Build-artifact scope** | `{{...}}` |
| **Schema scope** | `{{...}}` |
| **Infrastructure scope** | `{{...}}` |
| **External-effect scope** | `{{...}}` |
| Derived effects assessed | `{{consumers checked}}` |
| Required tests | `{{...}}` |
| Required evidence | `{{EVD IDs}}` |
| Authorised executor | `{{agent + branch}}` |
| Approval authority | `{{role + name}}` |
| Verification authority | `{{role + name — must differ from executor}}` |
| Machine-readable manifest | `{{scope.yml path + hash}}` |
| Approval | `{{name + date}}` |
| Superseded by | `{{TASK-####}}` |

## 33.9 Stop Record

| Field | Value |
|-|-|
| Stop ID | `{{STOP-####}}` |
| Class | `{{STOPPED / BLOCKED / CONSTITUTIONAL-BLOCKED}}` |
| Initiator | `{{...}}` |
| Subject / affected artefacts | `{{...}}` |
| Trigger | `{{§18.2 trigger}}` |
| Evidence | `{{EVD IDs}}` |
| Authority for the stop | `{{role + name}}` |
| Timestamp | `{{ISO-8601}}` |
| Machine-enforced? | `{{merge / deploy / release / dispatch / advancement — which}}` |
| Reviewer | `{{...}}` |
| Resume authority | `{{role + name — not the initiator unless registered}}` |
| Resolution state | `{{OPEN / RESOLVED / ESCALATED}}` |
| Resolution evidence | `{{...}}` |
| Re-verification performed | `{{yes/no + what}}` |

## 33.10 Change Record

| Field | Value |
|-|-|
| Change ID | `{{CR-####}}` |
| Origin | `{{requestor}}` |
| Description and reason | `{{...}}` |
| Affected requirements | `{{REQ IDs}}` |
| Affected artefacts | `{{doc/task/config IDs}}` |
| Materiality | `{{L0 / L1 / L2 / L3}}` |
| Cumulative aggregate | `{{...}}` |
| Risk profile impact | `{{...}}` |
| Impact analysis | `{{scope / schedule / cost / quality / security / operations}}` |
| Decision | `{{APPROVED / REJECTED / DEFERRED}}` |
| Approver | `{{name + role + date}}` |
| Resulting revisions | `{{...}}` |

## 33.11 Decision Ledger entry

| Field | Value |
|-|-|
| Decision ID | `{{DEC-####}}` |
| Date | `{{ISO-8601}}` |
| Issue | `{{...}}` |
| Classification | `{{FACTUAL / INTERPRETIVE / VALUE-PREFERENCE}}` |
| Alternatives considered | `{{...}}` |
| Selected option | `{{...}}` |
| Rationale | `{{...}}` |
| Authority | `{{name + role}}` |
| Evidence | `{{EVD / ADR IDs}}` |
| Dissent retained | `{{position + author}}` |
| Affected artefacts | `{{...}}` |
| Supersedes | `{{DEC-####}}` |

## 33.12 Control State Register

| Control ID | PBIM section | Control statement | State (§3) | Mechanism | Evidence pointer | Last verified | Verifier |
|-|-|-|-|-|-|-|-|
| | | | `{{DESIGNED / ENFORCEABLE / ENFORCED / INDEPENDENTLY VERIFIED}}` | | | | |

**Rule.** A control with no entry is treated as `DESIGNED`. Recording `ENFORCED` without a
live mechanism and observed behaviour is a blocking finding.

## 33.13 AEC Findings Register

| Finding ID | Challenger | Domain | Classification | Evidence | Impact | Affected control | Proposed resolution | Resolution evidence | Independent reviewer | Residual risk | Owner | Status |
|-|-|-|-|-|-|-|-|-|-|-|-|-|
|  |  |  | `{{BLOCKING/MATERIAL/MINOR/CONTAINED/FALSE POSITIVE/OBSERVATION}}` |  |  |  |  |  |  |  |  |  |

## 33.14 ADR

Architecture Decision Records live in `{{decisions/adr/}}` and record a **decision**, not a
task. Minimum fields: ADR ID; date; status (`PROPOSED / ACCEPTED / SUPERSEDED / DEPRECATED`);
context; problem; decision; rationale; alternatives rejected and why; consequences
(positive and negative); affected components; linked decisions; linked evidence; author;
reviewers.

An ADR marked `SUPERSEDED` is retained, never deleted (§25.3).

## 33.15 Independence Record

| Field | Value |
|-|-|
| Challenger identity | `{{...}}` |
| Artefact challenged | `{{ID + revision + durable reference}}` |
| Class claimed (§15.3) | `{{C1 / C2 / C3 / C4}}` |
| **I1 Organisational** | `{{SATISFIED / PARTIAL / NOT SATISFIED / N/A}}` — basis |
| **I2 Evidence** | `{{...}}` — basis |
| **I3 Technical** | `{{...}}` — basis |
| **I4 Governance** | `{{...}}` — basis |
| Shared credentials | `{{...}}` |
| Shared decision rights | `{{...}}` |
| Shared incentives | `{{...}}` |
| Shared evidence store | `{{...}}` |
| Prior authorship of this artefact | `{{yes/no — which parts}}` |
| Residual independence risk | `{{...}}` |
| Required class for the risk profile | `{{...}}` |
| Outcome | `{{sufficient / CHALLENGE-INDEPENDENCE-FAILED}}` |

## 33.16 Requirements Traceability Matrix

| Req ID | Requirement | Source | Materiality | Design section | Decision/ADR | Task packet | Change | Test | Evidence | Release | Status |
|-|-|-|-|-|-|-|-|-|-|-|-|
| `REQ-####` | | | `{{L0-L3}}` | | | | | | | | `{{COVERED / PARTIAL / UNCOVERED}}` |

A requirement with no test and no evidence is `UNCOVERED` and blocks its release.

## 33.17 Operational Readiness Checklist

| Item | Status | Evidence | Owner | N/A justification (if N/A) | Approver | Reviewer |
|-|-|-|-|-|-|-|
| Monitoring | | | | | | |
| Alert ownership | | | | | | |
| Rollback | | | | | | |
| Tested recovery | | | | | | |
| Backup | | | | | | |
| Restore | | | | | | |
| Data recovery | | | | | | |
| Incident ownership | | | | | | |
| Dependency readiness | | | | | | |
| Security readiness | | | | | | |
| Capacity | | | | | | |
| Support ownership | | | | | | |
| Migration recovery |  |  |  |  |  |  |
| Controlled rollout | | | | | | |

`NOT APPLICABLE` requires all of: justification; scope basis; risk rationale; approving
authority; reviewer (§23.3).

## 33.18 Drift Record

| Field | Value |
|-|-|
| Drift ID | `{{DRIFT-####}}` |
| Source | `{{artefact or mechanism}}` |
| Affected artefact | `{{ID + revision}}` |
| Authority owner | `{{...}}` |
| Detection time | `{{ISO-8601}}` |
| Detected by | `{{monitor / agent / human}}` |
| Evidence | `{{...}}` |
| Remediation | `{{...}}` |
| Human approval for correction | `{{name + role + date}}` |
| Resolution | `{{...}}` |
| Reviewer | `{{...}}` |

## 33.19 Gate Register

| Gate | Condition evidence | Durable reference | Deciding authority | Date | States granted | Conditions attached | Re-opened |
|-|-|-|-|-|-|-|-|
| GATE 0 | | | | | | | |
| GATE 1 | | | | | | | |
| GATE 2 | | | | | | | |
| GATE 3 | | | | | | | |
| GATE 4 | | | | | | | |
| GATE 5 | | | | | | | |
| GATE 7 | | | | | | | |
| GATE 8 | | | | | | | |
| GATE 9 | | | | | | | |

---

# 34. PBIM REVISION CONTROL

## 34.1 Controlled revision

Every PBIM revision SHALL identify: revision number; parent revision; reason for revision;
source findings; affected controls; new controls; removed controls; strengthened controls;
unresolved issues; implementation status; approval status.

## 34.2 Rules

1. A revision SHALL NEVER silently replace the previous baseline.
2. A revision that changes the authority model, the protected-control set or the
   constitutional boundary is a **constitutional change** and requires CA authorisation plus a
   fresh assurance cycle (§26.3).
3. A revision SHALL NOT inherit prior approvals for the parts it changes.
4. Superseded revisions SHALL be archived and remain identifiable (§25.3).
5. Revision numbering SHALL be monotonic within a major line (`R3.0`, `R3.1`, `R4.0`).
   A reset to a lower number requires CA authorisation.

---

# 35. R3.0 CONSOLIDATION BASIS

R3.0 consolidates the material architectural lessons from the completed PBIM
AEA → AEV → AEC cycle. The prior cycle produced a verified defect set; each verified defect
is addressed as follows.

## 35.1 Defect-to-resolution register

| # | Verified defect from the prior cycle | Resolution in R3.0 |
|-|-|-|
| D-01 | Process count ambiguous — "40" cited while the aligned list enumerated 40 with 10 referenced separately; conflicting "49" claims | §0.3 reconciliation note; §27.2 single canonical 49-item list with an explicit count check |
| D-02 | Identifier system self-contradictory: four live grammars, retired positions still present, two-digit customs on anchors with one-digit forms | §7.1 single grammar; §7.1.1 decimal semantics; §7.1.2 sub-identifier rule; §7.1.3 prohibited forms; §33.2 registry |
| D-03 | Zero physical implementation — no ADR store, decision ledger, task packets, skills folder or agent branches | §33.14 ADR; §33.11 Decision Ledger; §33.8 Task Packet; §28.8 sub-items `[0004.089]`–`[0004.0811]`; §5.1 canonical manifest |
| D-04 | `AGENTS.md` contradicted the architecture on roster, authority and branch naming | §8.6 precedence rule; §5.2 rule 4; §28.8 `[0004.088]`; drift detection §24 |
| D-05 | Two divergent workflow guides with no canonical marker | §5.1 Canonical Source Manifest; §5.2 single-source rule; §16.5 `CONFLICTING-SOURCE`; §22.7 contradictory-source gate |
| D-06 | Template sections `0004.02`–`0004.09` were empty stubs presented as complete | §28 fully populates all nine sections; §22.5 no-empty-stub rule; §22.6 placeholder-resolution gate |
| D-07 | Self-contradictory "challenge before build … after production code" instruction | §13.1 attack-not-refine rule; §22.3 review placement; §32.1 separates assurance stages from implementation verification |
| D-08 | No durable artifact references — symbolic branch names only | §25.2 immutable reference form; §5.2 rule 3; §33.1 reference field |
| D-09 | No executable gate model | §29 gates GATE 0–GATE 9; §33.19 Gate Register |
| D-10 | No requirements traceability | §7.7 RTM requirement; §33.16 template |
| D-11 | No defined secret boundary | §28.8 `[0004.0814]`; §33.8 secret rule |
| D-12 | Review-step numbering placed at the start of the project (`0620`-style) | §22.3 review placement; §27.5 placement rules |
| D-13 | Architectural specification presented as operational capability | §3 state model; §33.12 Control State Register; §28.9 `[0004.097]` capability declaration |
| D-14 | Human approval treated as substitutable by agent consensus | §2.1 non-derivation rules; §6.5 human decision points; §16.1–16.2 |
| D-15 | Independence assumed from a different agent title or vendor | §15.1–15.5 four dimensions, classes, epistemic vs organisational; §33.15 Independence Record |
| D-16 | No bounded convergence — unlimited revision oscillation | §12.5 revision cap 1.4 then restart with the consolidated evidence set |
| D-17 | No separation of generic architecture from concrete example | §11.4; §1.2; §28.2 constraint 5 |
| D-18 | No `NOT APPLICABLE` discipline | §23.3 controlled `NOT APPLICABLE` |
| D-19 | No cumulative risk / cumulative materiality control | §19.2; §20.2; §33.5; §33.10 aggregate fields |
| D-20 | No self-approval prohibition, no dual control | §8.3; §8.5 permission reality rule; §33.8 distinct verifier field |
| D-21 | No defined response contract for agents | §30 prompt contract; §30.2 reference attachment rules; §30.4 quality rules |
| D-22 | Prompts named agents only implicitly and carried no output structure or decision set | §28 per-section LEAD and COLLAB prompts, all nine blocks; §30.3 agent tailoring |
| D-23 | Process taxonomy overloaded as identity | §7.3 identifier separation table; §27.1 alignment is navigation not identity |
| D-24 | No operational-readiness or release gate distinct from implementation | §23.1 release state; §23.4 blocking conditions; §33.17 checklist |

## 35.2 Blocking pre-baseline conditions

R3.0 may not be advanced past **GATE 1** until the following are true of the concrete
project. Each is a blocker, not a recommendation.

1. A single identifier grammar is in force and the registry is populated (§7.1, §7.4).
2. The Canonical Source Manifest is published and unambiguous (§5.1).
3. `AGENTS.md` is synchronised with the baseline, with precedence recorded (§8.6).
4. All authoritative references are durable and immutable (§25.2).
5. The gate model is instantiated and executable (§29, §33.19).
6. The 49-process body is instantiated with content and passes the count check (§27.2).
7. The RTM exists and links requirements to evidence (§7.7, §33.16).
8. The secret boundary is defined and enforced (§28.8 `[0004.0814]`).
9. All nine pre-charter sections are populated — no empty stubs (§28, §22.5).
10. The Control State Register truthfully records every control's state (§33.12).

## 35.3 Status honesty statement

R3.0 is a **design** artefact. As of this revision:

* no control in this document is `ENFORCEABLE` in any concrete project;
* no gate beyond GATE 0 has been passed for any concrete project;
* the AEA/AEV/AEC/AECC cycle recorded in the prior documents applied to the **prior**
  architecture and does **not** constitute approval of R3.0;
* a **fresh** R3.0 AEA → AEV → AEC → AECC cycle is required (§36, §37).

Any statement to the contrary is a reporting failure.

---

# 36. REQUIRED MANUAL UPDATE BEFORE THE NEXT AEA

This document SHALL be manually reviewed and updated by the human project authority before
it enters the Architectural Engineering pipeline. The manual update is **not** a formality —
it is the step at which a design becomes the project's own.

## 36.1 Decisions the human authority must make

The following require a human decision. Each is answered in the document, with a rationale.

| # | Question | Answer | Rationale |
|-|-|-|-|
| 1 | Is the identifier architecture acceptable? | | |
| 2 | Are the nine pre-charter sections correctly positioned before the charter? | | |
| 3 | Is the 49-process alignment correct, and which standard edition does it map to? | | |
| 4 | Is CA appropriately defined, and what is its durable reference? | | |
| 5 | Are the H0/H1/H2 boundaries appropriate? | | |
| 6 | Is the agent roster correct for this project? | | |
| 7 | Are agent roles correctly assigned, and where does §8.3 require separation? | | |
| 8 | Is the Authority–Permission Matrix model appropriate? | | |
| 9 | Is the Identifier Registry model appropriate? | | |
| 10 | Is the evidence architecture practical for this project? | | |
| 11 | Can the required independence actually be obtained with this roster? | | |
| 12 | Is the 72-hour emergency delegation limit appropriate? | | |
| 13 | Is Task Packet scope enforcement technically feasible here? | | |
| 14 | Is `.github/workflows/task-scope-check.yml` the right enforcement point? | | |
| 15 | Is baseline-drift monitoring feasible outside the execution path? | | |
| 16 | Are durable references practical in the hosting arrangement? | | |
| 17 | Are operational-readiness controls proportionate? | | |
| 18 | Is the LIGHT/STANDARD/HIGH-ASSURANCE model appropriate, and which profile applies? | | |
| 19 | Are additional project-specific controls required? | | |
| 20 | Should any requirement be removed, changed, or promoted to constitutional status? | | |
| 21 | What is the concrete `[BASE]` and `[PROJECT]` token substitution? | | |
| 22 | Which delivery framework and conformance mapping (§0.2) applies? | | |
| 23 | What are the requirement materiality thresholds (§20.1)? | | |
| 24 | What are the evidence retention periods (§10.5)? | | |
| 25 | Is the secret boundary (§28.8) appropriate and enforceable? | | |

## 36.2 Additional manual-edit actions

1. Resolve `[BASE]`, `[PROJECT]` and `[IDENTIFIER]` or record them as unresolved with an owner.
2. Confirm or correct §0.3's process-count reconciliation against the actual standard in use.
3. Replace example-specific content with generic rules where it has leaked (§11.4).
4. Add any missing protected control to the §7.2 declaration.
5. Confirm every §33 template is the right field set for this project.
6. Remove any section this project genuinely does not need, recording the removal as a
   decision rather than leaving an empty stub (§22.5).
7. Record the decision, the decider and the date in the Decision Ledger (§33.11).

## 36.3 Gate on the manual update

Only after this manual review SHALL the Lead Agent prepare the next AEA Query. Preparing an
AEA Query on an unreviewed PBIM repeats the failure the bounded-convergence rule exists to
prevent (§12.5).

---

# 37. NEXT PBIM DEVELOPMENT CYCLE

| Step | Action | Owner | Gate |
|-|-|-|-|
| 1 | Human authority manually updates R3.0 (§36) | H0 / H1 | — |
| 2 | Lead Agent receives the manually updated PBIM | Lead | — |
| 3 | Lead prepares **PBIM Architectural Engineering Analysis Query R3.0** | Lead | GATE 2 |
| 4 | Collaborating agents independently analyse R3.0 | Collaborators | GATE 2 |
| 5 | Lead consolidates into **PBIM AEV R3.x** | Lead | GATE 3 |
| 6 | Collaborating agents approve or disapprove the AEV | Collaborators | GATE 3 |
| 7 | If approved, prepare a **fresh R3.x Adversarial Challenge** | Lead | GATE 4 |
| 8 | Collaborating agents attack the R3.x architecture | Collaborators | GATE 4 |
| 9 | Prepare **AECC closure** with closure authority | Lead | GATE 5 |
| 10 | Produce the approved PBIM baseline | Lead + H0 | GATE 5 |
| 11 | Instantiate the project from the approved baseline | Lead + agents | GATE 1 |
| 12 | Implementation Verification determines whether controls **operate** | Verifiers | GATE 7 |

**Constraint.** Steps 3–9 apply the §12.5 cap: if the AEV is not approved by revision 1.4,
halt revisions and restart from the consolidated evidence set rather than continuing to
iterate on the same artefact.

---

# 38. PBIM FINAL CONTROL STATEMENT

PBIM optimises for:

**CONTROLLED PROGRESS, NOT UNCONTROLLED SPEED.**

The objective is not a process that prevents change. It is a process in which change is:

* authorised;
* scoped;
* traceable;
* evidenced;
* testable;
* reversible where required;
* independently challenged where necessary;
* operationally ready before release;
* and governed by the appropriate authority.

And in which an agent is told, in writing, exactly what it may do, what it may not do, what
evidence it must produce, what it must escalate, and what it must never decide.

**The Lead Agent coordinates. Collaborating agents challenge. Implementation agents execute.
Verification agents verify. Human authority authorises. Constitutional Authority governs
constitutional boundaries.**

No agent, workflow, repository administrator, technical administrator, automation account or
emergency mechanism may silently acquire authority that the governance model has not granted.

---

# 39. DOCUMENT STATUS

| Field | Value |
|-|-|
| **PBIM revision** | R3.0 — Generic, Implementation-Ready |
| **File version** | v1.03.00-generic |
| **Parent revision** | R2.0 Consolidated (v1.02.00) |
| **Status** | Consolidated implementation candidate |
| **Control state of this document's controls** | `DESIGNED` — see §35.3 |
| **Manual review** | **REQUIRED** — see §36 |
| **Next AEA query** | NOT YET PREPARED |
| **Prior AEV** | R1.5 approval recorded in the prior cycle; **does not apply to R3.0** |
| **Fresh R3.0 AEC** | REQUIRED |
| **AECC** | PENDING |
| **Implementation authorization** | NOT GRANTED |
| **Production authorization** | NOT GRANTED |
| **Baseline identifier substitution** | UNRESOLVED — `[BASE]`, `[PROJECT]` pending §36 |
| **Superseded artefacts** | v1.00.00, v1.02.00 retained and identifiable (§25.3) |

**END OF PBIM R3.0 — GENERIC, IMPLEMENTATION-READY**
