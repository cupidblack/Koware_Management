# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
| --- | --- |
| **Document** | Project Base Integration Manager — Generic Edition |
| **Version** | v3.00.00 |
| **Generated** | 2026-10-07 (UTC) |
| **Supersedes** | v2.00.00 (generic candidate), v1.12.05 (v2 candidate), v1.12.00, v1.11.00 and earlier lineage back to v1.00.00 |
| **Status** | **CONTROLLED CANDIDATE — not AEV/AEC/AECC-approved.** Must pass Section `PBI-02-0004.00.02` before replacing any earlier version |
| **Maturity of this document** | `DESIGNED` only. Nothing in it is evidence that a control operates |
| **Scope** | Pre-charter project initialization only. PBIM identifiers end at `GOV-01-0004.01` (Develop Project Charter) |
| **Nature** | A *probing* document. Dates, durations and costs inside it are **expected** values, never commitments |

---

# 0. READER'S GUIDE

## 0.1 What PBIM is

PBIM takes an idea to a signed Project Charter through nine controlled steps (`PBI-01` to `PBI-09`) using a Lead Agent, Collaborating Agents and human authorities. It prepares governance; it does not replace the charter, project plan, organizational policy, legal authority or operational controls.

## 0.2 How to run it

1. Fill the **Project Identity Block** (Part 2).
2. Run Sections in order (Part 8). A Section may not start until the previous Section's exit gate is recorded in the Decision Ledger.
3. For each prompt: copy it from the Section (or from the AE Cycle Prompt Library, Part 7), replace every `{{token}}`, attach the listed resources as **durable references**, send it to the designated agent(s), and file the response where the prompt says.

## 0.3 Merge register — repeated or similar concepts consolidated in this edition

| ID | Concept (earlier forms) | Consolidated into | Where |
| --- | --- | --- | --- |
| M-01 | Three hand-copied 8-prompt AEA→AEV→AEC→AECC cycles in `0004.02`, `0004.04`, `0004.06` (v1.12.00); per-section LEAD/COLLAB prompts (v1.11.00) | **One parameterised AE Cycle Prompt Library**, bound per Section by a binding table | Part 7; §8.2, §8.4, §8.6 |
| M-02 | 3-state maturity (v1.12.00), 4-state maturity (v1.11/v2.00), authorization-state list (v2.00 §25), document-state ladder (v1.12.05) | **One State Model** (control maturity + artifact state + authorization state) | §5.2 |
| M-03 | Stop classes `S1–S4` (v1.12.00, v1.12.05) and `S0–S4` (v2.00); stop rules in v1.11 §8 | **One Stop/Reset/Emergency model** `S0–S4` | §5.6 |
| M-04 | Human roles (H0/H1/H2, CA) and agent roles (v1.11 eight roles; v1.12.05 LEAD/SEC/REL/IMP; v1.12.00 product-named agents) | **One Role Model** (authority roles + agent roles + role bindings) | §3 |
| M-05 | Four overlapping readiness lists: Final Pre-Charter Gate (v2.00 §26), Activation Checklist (v2.00 §15), Charter Readiness (v1.12.05 PBI-09), Gate 1–7 (v1.11) | **One Gate Register** (`G0`–`G9`) with a single Final Pre-Charter Gate | §9 |
| M-06 | Task Packet defined three times (v1.12.00 item 7, v1.11 §10, v2.00 §17) | **One Task Packet Control** | §5.8, Appendix C |
| M-07 | Evidence labels (v1.11, v1.12.05 six labels; v2.00 eight labels incl. FACT/DISPUTED) | **One Evidence Class set** | §5.1 |
| M-08 | Revision bounding stated three ways (x.0–x.4; `R<c>.<r>`; "bounded but never suppress dissent") | **One Revision Rule** | §5.9 |
| M-09 | Durable reference rules repeated (v1.11 §5.3, v2.00 §23, v1.12.05 OP-06) | **One Durable Reference rule** | §4.7 |
| M-10 | Risk profiles restated in each section (LIGHT/STANDARD/HIGH-ASSURANCE) plus profile tables | **One Risk Profile table** referenced by id | §5.3 |
| M-11 | Identifier schemes: legacy domain+anchor (v1.12.00), lifecycle position (v1.11), grammar (v1.12.05), `0004.xx` namespace (v2.00) | **One Identifier Grammar** + alias table | §4, Appendix B |
| M-12 | Emergency delegation (v1.12.00 H0-delegate, v1.12.05 GM-11, v2.00 §19.3) and Architectural Reset (three places) | Folded into §5.6 | §5.6 |
| M-13 | Duplicate section approval decision codes (`PBIM/PROPOSAL/TEMPLATE APPROVE`, mis-copied in v1.12.00) | **One decision vocabulary with `{{SUBJECT-CODE}}` prefix** | §7.2 |

## 0.4 Defects in earlier versions that this edition corrects

| ID | Defect | Fix |
| --- | --- | --- |
| D-01 | Process list titled "40", described as "49", containing 48 entries; Control Quality missing | Appendix A lists 49 stable positions, count-checked |
| D-02 | Domain tags inverted or reused (`SCP-05/06`, `RES-03` for PBIM creation, `GOV-09` vs `GOV-12` terminal) | `PBI` domain for PBIM steps; fresh tag sequence; terminal `GOV-13-9004.07` |
| D-03 | `0004.1` equals `0004.10` numerically; `0000.0x` and `0004.0x` prefixes coexist | Integer-segment grammar with mandatory zero padding (§4.1) |
| D-04 | Section IDs differ from artifact IDs in file names and prompts | File IDs derive from the Section anchor (§4.4) |
| D-05 | Copy-paste errors in repeated cycles (wrong subject link, wrong decision codes, wrong IDs) | Single library with tokens (M-01) |
| D-06 | `0004.07`–`0004.09` were stubs; empty `<>` markers; `<<START>>` without `<<STOP>>` | Every Section has steps, exit gate, prompts; closed markers (§4.5) |
| D-07 | `main/<agent-name>` branches cannot coexist with `main` in Git | `agent/<agent-slug>/<task-id>` (§4.6) |
| D-08 | `.git/skills/...` is Git's internal directory and is never committed | Tracked path `.github/skills/` or equivalent (PBI-07 control 10) |
| D-09 | "Unanimous approval" vs conditional approvals; "≥90% pass" as the only challenge gate | Objective gates (§5.9) |
| D-10 | Free-text `PROJECT-DURATION` with no formula; no start/end dates | `EXPECTED-PROJECT-DURATION / START-DATE / END-DATE` with derivation rule (§2.2) |
| D-11 | Project-specific examples (product names, countries, repos) embedded as rules | Placeholders only; examples isolated and marked |
| D-12 | Prompt-end marker inconsistent across instructions (`<<STOP {{Label}}>>` vs `<<STOP Prompt N. {{Label}}>>`) | `<<STOP Prompt N. {{Label}}>>` everywhere (§7.1) |

## 0.5 Honest limits of this candidate

- Source coverage: v2.00.00, v1.12.05 (first part), v1.12.00, v1.11.00 (first part) and v1.00.00 were read; v1.13.00 (≈1.09 MB) rendered no content in the viewer; v1.10.00, v1.05.00, v1.04.00 and v1.03.00 were not opened. Concepts unique to those files may be missing — see Open Item OI-01.
- The AEA/AEV/AEC evidence documents linked from the sources were not re-read for this edition; their conclusions are taken as summarized in v2.00.00 and v1.12.05.
- No control described here is claimed implemented. No AECC closure is claimed.
- Standards statements carry a currency flag and must be re-verified at `PBI-01` (§6).

---

# 1. PURPOSE, GOVERNANCE BOUNDARY AND ARCHITECTURE

## 1.1 Boundary

PBIM ends at `[PROJECT-KEY][GOV-01-0004.01]` Develop Project Charter. After the Charter is authorized, the project's own lifecycle and process-classification framework govern. PBIM never creates identifiers beyond that point.

## 1.2 Architectural model

```
GOVERNANCE  →  ASSURANCE  →  EXECUTION
authority,     AEA→AEV→AEC→AECC,    authorized work
constraints    verification,         under Task Packets
               challenge, evidence
```

Authority precedes capability. Specification is not implementation. Consensus is not proof.

## 1.3 Precedence

`Constitutional Authority → protected security/governance controls → approved PBIM baseline → controlled governance documents → Project Charter (once authorized) → repository AGENTS.md → subsystem AGENTS.md → Task Packet → tool/runtime defaults → informal instruction`. A lower level cannot weaken a higher one.

---

# 2. PROJECT IDENTITY BLOCK

Complete before `PBI-01`. Bracketed tokens are replaced at instantiation; generic rules never inherit example values.

```
PROJECT-KEY                : [BASE-ID]-[PROJECT-ID]        e.g. ACM-PLAT   (example only)
PROJECT-NAME               : [PROJECT-FULL-NAME]
ORGANIZATION CHAIN         : [ORG] → [DEPARTMENT] → [PMO]
PROJECT-BASE               : [PROJECT-BASE-NAME] [BASE-ID]
PROJECT-LOCATION           : [TOWN][DISTRICT][CITY][REGION][COUNTRY]
PROJECT-FOLDER             : [PROJECT-KEY]
PRODUCTION-REPO-NAME       : [PRODUCTION-REPO-NAME]
RISK-PROFILE               : LIGHT | STANDARD | HIGH-ASSURANCE   (see §5.3)
DELIVERY-APPROACH          : predictive | iterative | adaptive | hybrid
EXPECTED-PROJECT-DURATION  : [value + unit; also working hours]   — see §2.2
EXPECTED-PROJECT-START-DATE: [YYYY-MM-DD]                          — see §2.2
EXPECTED-PROJECT-END-DATE  : [YYYY-MM-DD]                          — see §2.2
```

| Location | Path |
| --- | --- |
| R&D folder | `[GOVERNANCE-REPO]/[PROJECT-FOLDER]` |
| Docs folder | `[GOVERNANCE-REPO]/[PROJECT-FOLDER]/docs` |
| Governance folder (registers) | `[GOVERNANCE-REPO]/[PROJECT-FOLDER]/governance` |
| Production repository | `[PRODUCTION-REPO-URL]` |

## 2.1 Why these values are "expected"

PBIM is a probing document. At this stage the project has no approved charter, budget, team or schedule baseline. The three `EXPECTED-*` values are **planning estimates used to test feasibility**, size reviewer capacity and prepare sponsor discussion. They:

- are not commitments, promises to investors/sponsors, or baselines;
- carry an evidence label (§5.1) — normally `ASSUMPTION` or `INFERENCE`;
- are re-estimated at `PBI-03`, `PBI-05`, `PBI-08` and replaced by **baselined** `PROJECT-DURATION / START-DATE / END-DATE` only when the Charter (`GOV-01-0004.01`) and later schedule baseline (`SCH-05-1006.05`) are authorized.

## 2.2 Derivation rule and validation

1. `EXPECTED-PROJECT-START-DATE` — earliest date at which authority, funding intent and key resources are *expected* to be available (not the date of this document). Format ISO 8601.
2. `EXPECTED-PROJECT-DURATION` — stated as calendar duration **and** effort: `calendar duration = ceil(total expected working hours ÷ weekly human capacity)`.
3. Weekly human capacity `= Σ over named people of min(local statutory/contractual weekly maximum, contracted hours) − leave − public holidays`. The cap and its source are recorded in the Local Regulatory Overlay (§6.2). Agent compute and token budgets are a separate budgeted resource. Reviewer and Human Project Authority time is counted explicitly, because verification cycles are the usual bottleneck.
4. `EXPECTED-PROJECT-END-DATE` — `START + duration`, adjusted for non-working days; must equal the result of rule 2 or the discrepancy is logged as a finding.
5. State the **estimating basis** (analogy, parametric, three-point, other) and a **range** (optimistic / expected / pessimistic) beside each value.
6. If the proposal gives dates that violate the rule, the Lead Agent returns the proposal with a finding; it never silently "corrects" them.

---

# 3. ROLE MODEL (merged: M-04)

## 3.1 Authority roles

| Code | Role | Responsibility |
| --- | --- | --- |
| `CA` | Constitutional Authority | External root of trust; approves changes to protected controls and to PBIM's own constitution. PBIM defines nothing above it |
| `H0` | Human Project Authority | Final sign-off, risk-profile decisions, resets, activation |
| `H1` | Delegated governance authority | Acts only within recorded delegation limits |
| `H2` | Authorized operational/technical authority | Operates systems; holds no governance authority |

Where an organization uses other titles, map them in the Authority Register before activation. Technical access never creates governance authority. If `CA` is unavailable: `CONSTITUTIONAL-BLOCKED`; nobody self-assumes it.

## 3.2 Agent roles (capabilities, not authorities)

| Code | Role | Function |
| --- | --- | --- |
| `LEAD` | Lead Architect Agent | Queries, synthesis, orchestration, gate preparation, evidence preservation |
| `ANL` | Analysis Agent | Independent architectural/technical analysis |
| `VER` | Verification/Reliability Agent | Evidence sufficiency, traceability, CI, reproducibility |
| `SEC` | Security & Challenge Agent | Adversarial attack of architecture and controls |
| `IMP` | Implementation Agent | Executes approved Task Packets only |
| `OPS` | Operations/Release Agent | Operational readiness, monitoring, rollback, support |
| `DOC` | Documentation/Registry Agent | Identifiers, revisions, provenance, ledger integrity |

One agent may hold several roles only if independence (§5.7) still passes. No role approves its own output. Role bindings (which tool/vendor fills each role) live in the Authority Register and are changed by recording a decision; the process text never names a product.

## 3.3 Prompt designations (used in every prompt)

| Designation | Meaning |
| --- | --- |
| `Lead Agent` | The `LEAD` role |
| `Collaborating Agents` | All other bound agent roles (`ANL`, `VER`, `SEC`, `IMP`, `OPS`, `DOC` as bound) |
| `Lead Agent / Collaborating Agents` | Both receive the same prompt |

---

# 4. IDENTIFIER ARCHITECTURE (merged: M-11)

## 4.1 Grammar

```
SECTION-ID = DOMAIN "-" DSEQ "-" ANCHOR          e.g. PBI-02-0004.00.02   GOV-01-0004.01
ANCHOR     = BAND AREA "." PROC *("." SUB)
BAND       = 1 digit    lifecycle band
AREA       = 3 digits   PM area / domain number (004 = Integration)
PROC       = 2 digits   process number; "00" = PBIM pre-charter step
SUB        = 2 digits   | "P" 2 digits (prompt) | "A" 2 digits (artifact)
DOMAIN     = GOV | STK | SCP | SCH | FIN | RES | RSK | PBI
```

Rules:

- **ID-1** Segments compare as integers, never decimals. `0004.1` and `0004.10` no longer collide.
- **ID-2** Zero padding is mandatory; lexical order equals lifecycle order. PBIM steps (`0004.00.NN`) sort before the Charter (`0004.01`).
- **ID-3** Tags `GOV STK SCP SCH FIN RES RSK` mirror the seven PMBOK Guide 8th Edition performance domains and are used only for catalogued processes (Appendix A). PBIM-internal steps use `PBI`.
- **ID-4** Anchors derive from the stable local Process Catalogue, not from a PMI edition number, so a new PMBOK edition never renumbers history. The catalogue is a **classification spine**, not a claim that PMBOK 8 has the same 49 positions.
- **ID-5** Identifiers are allocated only through the registry (§4.3). Agents never invent IDs; they propose them.
- **ID-6** Custom intermediate sections may be inserted between catalogued positions using an extra `SUB` group; they sort strictly between neighbours, are registered before use, and contain substantive content.
- **ID-7** A heading carrying a PM process name also carries an engineering subtitle.
- **ID-8** Reviews, tests, security audits and release checks are placed in bands 4–8, never in early planning bands.
- **ID-9** Heading form: `### [PROJECT-KEY][DOMAIN-DSEQ-ANCHOR] — Title — *engineering subtitle*`.

Identity separation — these stay distinct and are never overloaded: Project ID · PBIM section ID · Work/Task Packet ID · Document/Artifact ID · repository object/commit · PM-process classification · lifecycle state · Decision ID · Risk/Finding ID.

## 4.2 Lifecycle bands

| Band | Meaning |
| --- | --- |
| `0xxx` | Initiating, incl. PBIM pre-charter (`0004.00.NN`), Charter (`0004.01`), stakeholder identification |
| `1xxx` | Planning — core plans: management plan, scope, schedule |
| `2xxx` | Planning — cost, quality, resources, communications |
| `3xxx` | Planning — risk, procurement, stakeholder engagement |
| `4xxx`–`6xxx` | Executing — direct work, implementation, PR review, testing, team, communications, procurement |
| `7xxx`–`8xxx` | Monitoring & controlling — change control, quality, CI gates, scope/schedule/cost control |
| `9xxx` | Closing |

## 4.3 Identifier registry

A machine-readable, serialized state store, e.g. `governance/Identifier-Registry.yaml`, with fields: `identifier, sequence_index, project_id, artifact_type, domain, pbim_section, pm_process_reference, lifecycle_state, status, revision, canonical_location, authority, created_at, supersedes, integrity_reference`.

Mutation sequence: `REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM`. Conflict stops the workflow; last-write-wins is prohibited. Stale revisions, restores, force-pushes or local copies never silently become authoritative. Retired identifiers remain as tombstones and are never reused. Registry untrustworthy → `REGISTRY-BLOCKED`.

## 4.4 Artifact file names

`[<PROJECT-KEY>-<ANCHOR>]<Subject>_<ARTCODE>-<agent-slug>-<UTC yyyymmddThhmmZ>.md` with YAML front matter (`id, rev, state, sha256, supersedes`). `<ANCHOR>` is the **Section's** anchor, so file IDs and Section IDs always match.

`ARTCODE`: `AEA-Q` query · `AEA-R` report · `AEV-S` statement (`R<cycle>.<rev>`) · `AEV-D` decision · `AEC-D` duel · `AEC-R` duel result · `AECC` closure.

## 4.5 Markers (closed, named)

```
#**<<START Prompt N. {{Prompt Label}}>>**#
[Designation: …]
<instructions>
<<START {{Resource Label}}>>
<links>
<<STOP {{Resource Label}}>>
Note the following:
<notes>
#**<<STOP Prompt N. {{Prompt Label}}>>**#
```

Agent responses are filed between `<<START Responses: {{Prompt Label}}>>` and `<<STOP Responses: {{Prompt Label}}>>`. Notes, prompts and resource slots live **inside the Section where they are used**, not only at the end of the document.

## 4.6 Branches

| Purpose | Name |
| --- | --- |
| Agent working branch | `agent/<agent-slug>/<task-id>` (replaces `main/<agent-name>`, which Git cannot hold beside `main`) |
| Controlled candidates and baselines | `governance/<PROJECT-KEY>`, ruleset-protected, merged by reviewed pull request |

An agent submission is **non-authoritative** until merged to the governance branch and cited by commit.

## 4.7 Durable references (merged: M-09)

Authoritative references use `path@commit-SHA` plus a content hash (or a permanent tag/object ID). Branch names, `blob/<branch>` URLs, chat sessions, temporary workspaces, deletable review branches and transient execution IDs are convenience pointers only. Superseded artifacts are retained and marked, never overwritten.

---

# 5. CONTROL MODEL

## 5.1 Evidence classes (merged: M-07)

Every substantive claim carries one label: `VERIFIED FACT` · `INFERENCE` · `ASSUMPTION` · `PROPOSAL` · `RECOMMENDATION` · `RISK` · `UNKNOWN` · `DISPUTED`. An `UNKNOWN` has an owner and target date. Repetition or agent agreement never upgrades a label. Evidence records keep source, hash, timestamp, author, verifier, result and provenance; raw evidence stays retrievable and a sanitized report never replaces it.

## 5.2 State model (merged: M-02)

**Control maturity** — `DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED`. Documentation is never proof a mechanism exists. Claiming a higher state requires evidence in the Control State Register.

**Artifact state** — `Draft → Analysis → Controlled Candidate → Verification → Approved → Superseded → Archived`.

**Authorization state** — `IMPLEMENTATION-NOT-AUTHORIZED → IMPLEMENTATION-AUTHORIZED → IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED → PRODUCTION`, plus `STOPPED`, `RESET-REQUIRED`. Omitted states must be declared not applicable. PBIM never grants production authorization.

## 5.3 Risk profiles and materiality (merged: M-10)

| Profile | Use | AEA/AEV | AEC | Independence |
| --- | --- | --- | --- | --- |
| `LIGHT` | Low-risk, bounded, reversible | One combined review; `H0` decides | Checklist | Epistemic acceptable |
| `STANDARD` | Normal project work | Full AEA then AEV | One independent challenger | `I1` or `I3` |
| `HIGH-ASSURANCE` | Financial, security-, safety- or privacy-sensitive, regulated, irreversible, production-critical | Full, all collaborators + `H0` | Several independent challengers; separate evidence path | `I1`–`I4`; external reviewer if the team cannot supply it |

Classification is a governance decision at Charter, implementation start, major requirement change and release; it is assessed cumulatively, implementers cannot downgrade it, and the higher profile applies during dispute. Scaling reduces ceremony, never protected controls. Defaults: PBIM document → `HIGH-ASSURANCE`; Proposal and Template → `STANDARD`, raised for payment, authentication, personal data or safety.

**Change materiality**: Level 0 task-level; Level 1 limited, possible baseline impact (AEV impact review); Level 2 material architecture/scope/security (AEV + AEC); Level 3 load-bearing or constitutional (Architectural Reset / `CA`). Splitting a material change into small ones does not defeat review.

## 5.4 Decisions and traceability

Material decisions record: decision ID, owner, authority, question, alternatives, evidence, decision, rationale, consequences, affected artifacts, effective revision, superseded decision. **ADR** = durable architectural rationale; **Decision Ledger** = operational record of decisions, approvals, state, unresolved items. Trace chain: need → requirement → decision → architecture → Task Packet → change → verification → release/handoff. A matrix is required for `HIGH-ASSURANCE`; lighter profiles may use ledger links.

## 5.5 Protected resources and authority–permission separation

Protected: PBIM baseline, Authority Register, Authority–Permission Matrix, identifier registry, control definitions, AEA/AEV/AEC/AECC artifacts, Decision Ledger, ADRs, Task Packet state, evidence-integrity records, release records. Every privileged account maps to a named role; a mismatch raises `AUTHORITY-PERMISSION-DRIFT`. Matrix and registry change only through the serialized sequence in §4.3; concurrent change raises `AUTHORITY-MATRIX-CONFLICT`. Privileged-account audits are periodic. Matrix fields are in Appendix C.

## 5.6 Stop, reset and emergency (merged: M-03, M-12)

| State | Meaning | Resume authority |
| --- | --- | --- |
| `S0` | Running | — |
| `S1` | Advisory — work continues; review item logged | Owner |
| `S2` | Mandatory stop — scope/requirement mismatch | Issuer of the stop or `H1`+ |
| `S3` | System/security stop — CI failure, vulnerability, integrity failure | `VER`/`SEC` + `H1`+ |
| `S4` | Emergency safety stop — financial, data, authority violation | `H0` (and `CA` if constitutional) |

Executors never self-resume. Enforce by machine where feasible (blocked merge, release, dispatch).

**Architectural Reset** applies when a load-bearing assumption fails: identify it, freeze affected work, preserve evidence and prior baseline, name the reset authority, return to AEA (or the earliest affected stage) and record it in the Ledger. Reset can never erase adverse evidence.

**Emergency delegation** is explicit, scope-, action- and time-limited, logged, and expires automatically (default TTL 72 h, tightenable by `CA`; shorter is preferred). Post-event reconciliation is mandatory; emergency action never permanently weakens a baseline.

## 5.7 Independence

Independence is verified, not declared. Dimensions: `I1` organizational · `I2` evidence · `I3` technical · `I4` governance. A reviewer or challenger files an independence record (principal, relationships, permissions, shared credentials, decision rights, incentives, prior authorship, shared evidence stores). Failure → `CHALLENGE-INDEPENDENCE-FAILED`; unobtainable independence → `CHALLENGE-BLOCKED`, which prevents false closure. "Same person, different title" is not independence; scarce resources delay work, they do not create independence.

## 5.8 Task Packet control (merged: M-06)

All post-activation work is issued through bounded Task Packets where the governance model requires them (fields in Appendix C). Scope classes: direct, generated, dependency, configuration, build artifact, schema, infrastructure, external effect. Packets are immutable for an execution cycle unless formally superseded and cannot authorize work contradicting the baseline, Charter or security controls. Where automation exists, CI compares the actual change set with the packet's machine-readable scope manifest (e.g. a `task-scope-check` workflow); naming the workflow in PBIM is not evidence it exists. Out-of-scope work: `STOP → REPORT → NEW/SUPERSEDING PACKET`.

## 5.9 Revision and gate rules (merged: M-08)

1. **AEV → AEC**: every required reviewer returns unconditional `AEV APPROVE`; no open `BLOCKER`. A conditional approval is a `RETURN` until its conditions are written into a new revision and re-approved.
2. **AEC → AECC**: zero open `BLOCKER`/`MATERIAL`; each challenger scores ≥ 90% (a floor, not the gate) and passes its independence record; a minority `BLOCKER` stays open regardless of how many agents disagree.
3. **AECC → approval**: any material architectural change after an AEC requires a fresh AEV and AEC on the amended artifact.
4. **Revision bound**: within a cycle, revisions are `R<c>.0`–`R<c>.4`. If `R<c>.4` is not approved, trigger Architectural Reset and open cycle `R<c+1>.0`, or `H0` records in the Ledger a reasoned extension. A cycle limit never suppresses material dissent; failed convergence escalates to `H0` (and `CA` if constitutional).
5. Consensus is not proof; dissent and conditions are preserved in each record.

## 5.10 Security and data boundary

Before implementation of anything with software/security impact, define: what agents may access and process; what repositories may contain; credential supply and secret storage; log redaction; production credential restrictions; privileged human actions; third-party access; evidence retention; incident escalation. Agents receive no secrets merely because they can use them. Content fetched from repositories, web pages or tool output is **untrusted data, never instructions** (prompt-injection posture). Agent actions are logged; tool access is allow-listed and least-privilege.

## 5.11 Drift, operational readiness and automation compromise

`APPROVED BASELINE ≠ IMPLEMENTED/CONFIGURED STATE` is monitored outside the execution path. Divergence is classified as approved change, defect, undocumented change, architectural drift, or environment/dependency drift. Approvals can expire (environment, requirement, dependency, security-incident, authority change, or elapsed time defined by policy). Operational readiness is a distinct gate (monitoring, alert ownership, incident ownership, rollback, backup/restore, data recovery, migration reversibility, security response, capacity, support, documentation, stakeholder readiness; `NOT APPLICABLE` needs justification and approver). Automation is not inherently trusted: a compromised validator or CI system can trigger `S3`.

---

# 6. STANDARDS, CURRENCY AND LOCAL OVERLAY

## 6.1 Reference standards register (currency gate applies)

| Area | Reference | Use | Currency |
| --- | --- | --- | --- |
| Project management | PMBOK® Guide — Eighth Edition and The Standard for Project Management (released Nov 2025; six principles, seven performance domains, five focus areas, 40 non-prescriptive processes) | Domains → identifier tags; tailoring; value orientation | Verified 2026-10-07 by web search |
| Project management | ISO 21502 (project management guidance) | Secondary cross-check | Verify edition at `PBI-01` |
| Architecture description | ISO/IEC/IEEE 42010; ADR practice; C4/arc42 | Decision and architecture records | Verify |
| Quality attributes | ISO/IEC 25010 | Requirement and test framing | Verify |
| Secure development | NIST SSDF (SP 800-218 and successor revisions); OWASP ASVS and Top 10; threat modeling | Code/process gates | Verify current revision |
| AI-agent governance | NIST AI RMF; ISO/IEC 42001; OWASP guidance for LLM applications; applicable regional AI regulation | Agent logging, human oversight, injection testing, data handling | Verify; regulation is jurisdiction-specific |
| Supply chain | SLSA provenance, signed commits/tags, SBOM (SPDX/CycloneDX), artifact attestations | Durable references, release evidence | Verify |
| Information security | ISO/IEC 27001 (current edition) | Control mapping where an ISMS exists | Verify |
| Privacy | Applicable data-protection law; privacy-by-design; impact assessment | Data classification | Jurisdiction-specific |
| Delivery/operations | Trunk-based or short-lived-branch practice; SemVer; Conventional Commits; SLO/error-budget and runbook practice; DORA delivery metrics | Release and operational readiness | Verify |
| Accessibility (if UI) | WCAG current version | Acceptance criteria | Verify |

A framework that is *referenced* is never claimed *implemented*. Items marked "Verify" must be confirmed (and the date recorded) at `PBI-01`; items awaiting a new edition are treated as non-normative drafts.

## 6.2 Local Regulatory Overlay (LRO)

Resolve obligations **local first**: `TOWN/DISTRICT → CITY → REGION → COUNTRY → regional bloc → international standard`; the more specific, stricter-for-the-project rule wins unless law says otherwise. Record each obligation in an LRO register: `id, instrument, authority, applies_to, status (IN-FORCE/PENDING/REPEALED), obligation, project_impact, source_url, last_verified (UTC), verified_by, confidence (SEARCHED/RECALL-UNVERIFIED)`.

**Currency gate**: re-verify at `PBI-01`, `PBI-03`, `PBI-07`, `GOV-01-0004.01`, every major baseline, and at most every 90 days. Pending instruments that could change an obligation are tracked as risks. Capture at minimum: working-time limits (feeds §2.2), data protection, cybersecurity incident reporting, payments/financial regulation if relevant, electronic records/signature validity, procurement law, copyright/licensing for agent inputs, tax/employment. None of this is legal advice.

## 6.3 Transition from outdated practice

| ID | Outdated | Modern replacement | Feasible transition (window) |
| --- | --- | --- | --- |
| OP-01 | "40/49 PMBOK processes" claim | Local catalogue as classification spine + PMBOK 8 domains | Relabel; no file renamed |
| OP-02 | 3-state maturity | 4-state maturity (§5.2) | Existing `ENFORCED` claims relabelled "ENFORCED (unverified)" until independently checked |
| OP-03 | `main/<agent-name>` branches | `agent/<slug>/<task-id>` + `governance/<KEY>` | Update AGENTS.md and prompts |
| OP-04 | Branch protection by habit | Repository rulesets, CODEOWNERS on governance paths, signed commits, no force-push | Governance branch first, then production |
| OP-05 | Long-lived personal tokens for agents | Per-agent identities, short-lived OIDC credentials, least privilege | Inventory → rotate → map in Matrix |
| OP-06 | Branch/session links as references | `path@SHA` + hash | Re-pin when promoting a candidate |
| OP-07 | Unanimous agent vote | Role-based gate with preserved minority blockers (§5.9) | From next AEV cycle |
| OP-08 | "≥90% passed" as gate | 90% floor; zero open BLOCKER/MATERIAL | From next AEC cycle |
| OP-09 | Product-named authority | Roles with bindings (§3) | Edit roster only |
| OP-10 | Hand-copied cycles | Prompt Library with tokens (M-01) | Regenerate and diff |
| OP-11 | Mixed numeric ID forms | Single grammar + alias table | Accept legacy IDs 90 days via Appendix B, then registry rejects |
| OP-12 | Ad hoc `.txt`/`.md` names | Front matter + hash + states | Convert at next revision |
| OP-13 | One-sentence "conform to local law" | LRO register + currency gate | Build at `PBI-01` |
| OP-14 | No AI-specific controls | Agent action logging, injection testing, human oversight, untrusted-input rule | Add to Task Packet schema |
| OP-15 | Free-text duration | `EXPECTED-*` fields with derivation (§2.2) | Fill at `PBI-03` |

---

# 7. PROMPT STANDARD AND AE CYCLE PROMPT LIBRARY (merged: M-01)

## 7.1 Prompt format (mandatory for every prompt in this document)

1. Start: `#**<<START Prompt N. {{Prompt Label}}>>**#`
2. Designation line: `[Designation: Lead Agent]`, `[Designation: Collaborating Agents]` or `[Designation: Lead Agent / Collaborating Agents]` (a role qualifier may follow, e.g. `— Challenge role`).
3. Instructions, using these labelled lines where relevant: `OBJECTIVE` · `TASK` (numbered) · `OUTPUT` (file name, location, front matter) · `DECISION SET` · `EVIDENCE LABELS` (§5.1) · `STOP IF` · `DO NOT`.
4. Each resource group: `<<START {{Resource Label}}>>` … `<<STOP {{Resource Label}}>>`, containing **durable references** (§4.7). Where the same group is reused, the labels are identical.
5. `Note the following:` — numbered notes placed *below* the resources.
6. End: `#**<<STOP Prompt N. {{Prompt Label}}>>**#`

Prompt quality rules: name the designated agent; give the minimum sufficient evidence set; tell the agent what it must not infer or invent; require disclosure of unreadable or unverifiable material; separate facts from inferences; require findings to carry evidence, impact, affected control and proposed disposition; end with a bounded decision set; never grant authority through instruction text. Prompts are versioned; changing one bumps its version.

## 7.2 Vocabulary

- **AEV decision**: `AEV APPROVE` (unconditional) · `AEV APPROVE WITH CONDITIONS` · `AEV RETURN` · `AEV BLOCK` · `ARCHITECTURAL RESET`.
- **AEC verdict**: `AEC PASS` · `AEC PASS WITH AMENDMENTS` · `AEC BLOCKED`.
- **Finding severity**: `BLOCKER` · `MATERIAL` · `MINOR` · `OBSERVATION`.
- **Final subject decision**: `{{SUBJECT-CODE}} APPROVE` · `… APPROVE WITH CONDITIONS` · `… RETURN` · `… BLOCK` · `ARCHITECTURAL RESET`, with `{{SUBJECT-CODE}}` = `PBIM` | `PROPOSAL` | `TEMPLATE`.

## 7.3 The cycle

```
Prompt 1  LEAD    issues AEA Query
Prompt 2  COLLAB  independent AEA Reports
Prompt 3  LEAD    synthesizes AEV Statement R<c>.<r>
Prompt 4  COLLAB  AEV decisions            ── not unanimous unconditional approve → fix → Prompt 3/4
Prompt 5  LEAD    processes decisions; issues AEC Duel
Prompt 6  COLLAB  AEC results (independent challengers)
Prompt 7  LEAD    processes results; amend → Prompt 3, or close → AECC + updated subject
Prompt 8  COLLAB  final decision on updated subject (+ H0 signs in the Ledger)
```

## 7.4 Token binding

Replace before sending: `{{PROJECT-KEY}}`, `{{ANCHOR}}` (the Section anchor, e.g. `0004.00.02`), `{{SUBJECT}}` (e.g. "PBIM Document"), `{{SUBJECT-CODE}}`, `{{SECTION-ID}}` (e.g. `PBI-02-0004.00.02`), `{{PROFILE}}`, `{{CYCLE}}` (e.g. `R1`), `{{path@SHA}}` links. Do not edit prompt wording; change the library and regenerate.

---

## 7.5 AE CYCLE PROMPTS

#**<<START Prompt 1. Generate {{SUBJECT}} AEA Query>>**#
[Designation: Lead Agent]
OBJECTIVE. Convert the subject below into an Architectural Engineering Analysis (AEA) Query that each collaborating agent can answer independently.
TASK. 1) Separate generic rules from project-specific examples. 2) State problem, current state, desired outcome, constraints, existing decisions, dependencies, risks and unknowns. 3) Ask numbered questions: a core set for every agent plus a specialist set per role (security, reliability, implementation, operations). 4) Require evidence labels and the Finding record (Appendix C). 5) State the decision vocabulary (§7.2) and verification criteria for profile {{PROFILE}}.
OUTPUT. `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEA-Q-<agent-slug>-<UTC>.md`, state `Analysis Request — not an approved architecture`, committed to the docs folder; reply with `path@SHA`.
EVIDENCE LABELS. Required on every claim.
STOP IF the subject is unreadable, is a branch/session link, or its identifier is not registry-allocated.
DO NOT propose the answer inside the question.
<<START {{SUBJECT}} Under Analysis>>
1. {{SUBJECT}}:
{{path@SHA}}
<<STOP {{SUBJECT}} Under Analysis>>
Note the following:
1. Run only after the subject exists as a Controlled Candidate, or as maintenance.
2. List anything you could not read; do not summarize it from inference.
#**<<STOP Prompt 1. Generate {{SUBJECT}} AEA Query>>**#

#**<<START Prompt 2. Place {{SUBJECT}} AEA Query>>**#
[Designation: Collaborating Agents]
OBJECTIVE. Analyze the subject from your bound role and report independently.
TASK. 1) Answer every core question and your specialist questions. 2) Label each claim; tie `VERIFIED FACT` items to `path@SHA`. 3) List what is sound, incomplete, contradictory, ambiguous and over-complex. 4) Record findings with severity. 5) State what you could not read.
OUTPUT. `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEA-R-<agent-slug>-<UTC>.md` on branch `agent/<agent-slug>/<task-id>`.
STOP IF an answer needs evidence you cannot obtain — mark it `UNKNOWN`.
DO NOT read other agents' reports first; do not adopt the Lead Agent's framing as evidence.
<<START {{SUBJECT}} AEA Query>>
1. {{SUBJECT}} AEA Query:
{{path@SHA}}
<<STOP {{SUBJECT}} AEA Query>>
Note the following:
1. Each agent submits its own report; reports are later compiled into one shared file for the Lead Agent.
#**<<STOP Prompt 2. Place {{SUBJECT}} AEA Query>>**#

#**<<START Prompt 3. Obtain {{SUBJECT}} AEA Reports & Generate AEV Statement>>**#
[Designation: Lead Agent]
OBJECTIVE. Produce a controlled baseline-candidate AEV Statement from the AEA Reports.
TASK. 1) Reconcile reports; for each contradiction cite both sides and decide or escalate. 2) Preserve dissent verbatim in a dissent section. 3) State which controls are `DESIGNED` only. 4) Label the revision `{{CYCLE}}.<r>` with `supersedes`, and include a resolution table mapping every finding to a section. 5) State the gates of §5.9.
OUTPUT. `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEV-S-<agent-slug>-<UTC>.md`, state `Controlled Candidate`; implementation authorization `NOT GRANTED`.
STOP IF a `BLOCKER` or `MATERIAL` finding can be neither resolved nor escalated.
DO NOT drop a finding because only one agent raised it.
<<START {{SUBJECT}} AEA Reports>>
1. Compiled AEA Reports (all agents):
{{path@SHA}}
<<STOP {{SUBJECT}} AEA Reports>>
Note the following:
1. Every report must be attached; a missing agent is a recorded role gap, not a silent omission.
#**<<STOP Prompt 3. Obtain {{SUBJECT}} AEA Reports & Generate AEV Statement>>**#

#**<<START Prompt 4. Present {{SUBJECT}} AEV Statement>>**#
[Designation: Collaborating Agents]
OBJECTIVE. Review the Statement and issue exactly one decision.
TASK. 1) Confirm each of your earlier findings is actually resolved in the cited section, not merely listed as resolved. 2) Look for regressions and new defects. 3) Distinguish what is specified, what is enforceable by design, what still needs implementation, and what needs independent operational verification. 4) Decide. 5) For any decision other than unconditional approve, give a concrete fix per issue. 6) File your independence record (§5.7).
DECISION SET. `AEV APPROVE` · `AEV APPROVE WITH CONDITIONS` · `AEV RETURN` · `AEV BLOCK` · `ARCHITECTURAL RESET`.
OUTPUT. `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEV-D-<agent-slug>-<UTC>.md`, decision alone on line 1.
STOP IF you cannot read the whole Statement — state what is missing.
DO NOT approve conditionally and call it approval; a conditional decision is a `RETURN` until revised.
<<START {{SUBJECT}} AEV Statement>>
1. Latest AEV Statement revision:
{{path@SHA}}
<<STOP {{SUBJECT}} AEV Statement>>
Note the following:
1. Decide from the evidence in the Statement; do not defer to other agents' decisions.
#**<<STOP Prompt 4. Present {{SUBJECT}} AEV Statement>>**#

#**<<START Prompt 5. Obtain {{SUBJECT}} AEV Decisions & Generate AEC Adversarial Duel>>**#
[Designation: Lead Agent]
The following are the AEV decisions from the collaborating agents on the {{SUBJECT}} AEV Statement:
<<START {{SUBJECT}} AEV Decisions>>
1. All AEV Decisions (all agents, all revisions):
{{path@SHA}}
2. Current AEV Statement:
{{path@SHA}}
<<STOP {{SUBJECT}} AEV Decisions>>
TASK. 1) Tabulate decisions. 2) If any decision is not unconditional approve: resolve blocking issues, issue the next revision `{{CYCLE}}.<r+1>` and return to Prompt 4; if the revision would exceed `{{CYCLE}}.4`, stop and request `H0`'s ruling or an Architectural Reset (§5.9). 3) If all are unconditional approve and no `BLOCKER` is open, instruct the Challenge role to prepare an adversarial duel that **attacks** the architecture (assumptions, authority, evidence, scope, stop/reset, drift, cost, migration, rollback, human unavailability, automation compromise) rather than refining it. 4) Require independent challengers per profile {{PROFILE}}.
OUTPUT. Next AEV revision, or `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEC-D-<agent-slug>-<UTC>.md`.
STOP IF independence cannot be established for the profile.
DO NOT start the duel on a conditional approval.
Note the following:
1. All agent decisions have been pasted into the single shared file.
2. The duel is shared with each independent challenger; results return to the Lead Agent.
#**<<STOP Prompt 5. Obtain {{SUBJECT}} AEV Decisions & Generate AEC Adversarial Duel>>**#

#**<<START Prompt 6. Present {{SUBJECT}} AEC Adversarial Duel>>**#
[Designation: Collaborating Agents — Challenge role]
OBJECTIVE. Try to break the {{SUBJECT}} architecture and engineering rather than refine it.
TASK. 1) File your independence record first; if independence fails, stop and report `CHALLENGE-INDEPENDENCE-FAILED`. 2) Attack every domain, scenario and load-bearing assumption in the duel; re-measure live facts yourself. 3) Record each finding (Appendix C) with attack path and consequence. 4) Score your pass rate. 5) Return a verdict.
DECISION SET. `AEC PASS` · `AEC PASS WITH AMENDMENTS` · `AEC BLOCKED`.
OUTPUT. `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEC-R-<agent-slug>-<UTC>.md`.
STOP IF you find a live control failure — flag `S3` immediately.
DO NOT copy earlier findings or the Statement's own resolution table; do not soften severity to reach a pass.
<<START {{SUBJECT}} AEC Adversarial Duel>>
1. AEC Adversarial Duel:
{{path@SHA}}
2. Approved AEV Statement:
{{path@SHA}}
<<STOP {{SUBJECT}} AEC Adversarial Duel>>
Note the following:
1. A pass rate of at least 90% is a floor; the gate is zero open `BLOCKER`/`MATERIAL` findings (§5.9).
#**<<STOP Prompt 6. Present {{SUBJECT}} AEC Adversarial Duel>>**#

#**<<START Prompt 7. {{SUBJECT}} AEC Adversarial Duel Results>>**#
[Designation: Lead Agent]
The following are the results of the '{{SUBJECT}} Architectural Engineering Challenge Duel' from the collaborating agents:
<<START {{SUBJECT}} AEC Adversarial Duel Results>>
1. Compiled Results:
{{path@SHA}}
2. AEA Reports (for reference):
{{path@SHA}}
<<STOP {{SUBJECT}} AEC Adversarial Duel Results>>
TASK. 1) Merge findings; preserve minority `BLOCKER`/`MATERIAL` items. 2) For each, decide: amend the AEV Statement, accept with `H0` sign-off, or escalate to `CA` if constitutional. 3) If the amendment is material, issue a new Statement and repeat Prompts 4–6 (fresh AEV and fresh AEC). 4) If the gates of §5.9 are met, prepare the {{SUBJECT}} Architectural Engineering Challenge Closure (AECC) and the updated {{SUBJECT}}.
OUTPUT. Amended Statement, or `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AECC-<agent-slug>-<UTC>.md` plus the updated subject as `path@SHA`.
STOP IF any `BLOCKER`/`MATERIAL` is open.
DO NOT close on a vote; AECC closure does not authorize implementation or production.
Note the following:
1. All agent results have been pasted into the single shared file.
2. AEA reports are attached for reference.
3. If any agent disapproves implementation based on material risks or blockers discovered in the duel, resolve all vulnerabilities in the AEV Statement, update it, then retry the duel.
4. Once all collaborating agents approve closure, prepare the AECC and the updated {{SUBJECT}} for implementation.
#**<<STOP Prompt 7. {{SUBJECT}} AEC Adversarial Duel Results>>**#

#**<<START Prompt 8. Present Updated {{SUBJECT}} for Final Decision>>**#
[Designation: Collaborating Agents]
OBJECTIVE. Give a final decision on the updated {{SUBJECT}}.
TASK. 1) Confirm every AECC condition is reflected in the artifact. 2) Decide. 3) Give details and fixes for anything other than approve.
DECISION SET. `{{SUBJECT-CODE}} APPROVE` · `{{SUBJECT-CODE}} APPROVE WITH CONDITIONS` · `{{SUBJECT-CODE}} RETURN` · `{{SUBJECT-CODE}} BLOCK` · `ARCHITECTURAL RESET`.
OUTPUT. `[{{PROJECT-KEY}}-{{ANCHOR}}]{{SUBJECT}}_AEV-D-<agent-slug>-<UTC>.md` (final), decision on line 1; `H0` signs in the Decision Ledger.
STOP IF the artifact link is not `path@SHA`.
DO NOT use another subject's decision codes.
<<START Updated {{SUBJECT}}>>
1. Updated {{SUBJECT}}:
{{path@SHA}}
2. AECC:
{{path@SHA}}
<<STOP Updated {{SUBJECT}}>>
Note the following:
1. A decision other than approve returns the artifact to the stage named in the decision.
#**<<STOP Prompt 8. Present Updated {{SUBJECT}} for Final Decision>>**#

---

# 8. PBIM SECTIONS — STEP-BY-STEP IMPLEMENTATION

Run in order. Each Section: Card → Implementation steps → Prompts → Exit gate. Section IDs below use the heading form of §4.1; `[PROJECT-KEY]` is substituted from Part 2.

| Section | Gate | Subject / primary output |
| --- | --- | --- |
| `PBI-01-0004.00.01` | G1 | Generic PBIM candidate |
| `PBI-02-0004.00.02` | G2 | PBIM assurance baseline (AEA→AECC) |
| `PBI-03-0004.00.03` | G3 | Established Project Proposal + variable set |
| `PBI-04-0004.00.04` | G4 | Verified Proposal |
| `PBI-05-0004.00.05` | G5 | Project Template (skeleton → operating) |
| `PBI-06-0004.00.06` | G6 | Verified Template |
| `PBI-07-0004.00.07` | G7 | Initialized governance substrate |
| `PBI-08-0004.00.08` | G8 | Simulation & readiness evidence |
| `PBI-09-0004.00.09` | G9 | Activation Record (`PBIM-READY-FOR-CHARTER`) |
| `GOV-01-0004.01` | — | Project Charter — **PBIM integration boundary** |

---

### [PROJECT-KEY][PBI-01-0004.00.01] — PBIM Document Creation — *generate or refresh the generic PBIM*

| Card | |
| --- | --- |
| **Purpose** | Build or refresh the abstract, generic PBIM from the full reference package. Run for first creation and for maintenance (standards-currency review, Architectural Reset, role-binding change). Not run per project |
| **Profile** | `HIGH-ASSURANCE` |
| **Inputs** | R1–R8 below |
| **Outputs** | Candidate PBIM with change log, merge register, defect register, alias table |
| **Exit gate (G1)** | Candidate committed to the governance branch as `Controlled Candidate`; every source accounted for; coverage limits recorded |
| **Stop if** | A reference is missing, unreadable in full, or only a branch/session link; LRO older than 90 days |

**Implementation steps**
1. Compile references R1–R8 as `path@SHA`; open the viewer's raw form for large files and record any file that still cannot be read in full.
2. Refresh the LRO register and standards register (§6) and record verification dates.
3. Send Prompt 1 to the Lead Agent and all Collaborating Agents.
4. Compare candidates; the Lead Agent merges into one, retaining dissent in an appendix.
5. Commit the candidate; allocate its identifier through the registry; proceed to `PBI-02`.

#**<<START Prompt 1. Generate Generic PBIM Candidate>>**#
[Designation: Lead Agent / Collaborating Agents]
OBJECTIVE. Produce a new generic PBIM version from the reference package, keeping the structure and requirements of the current PBIM and correcting its defects.
TASK. 1) *Identifiers*: inventory every Section carrying an identifier; confirm the domain tag matches content, the anchor matches lifecycle stage, and the heading carries an engineering subtitle; emit one grammar, one registry rule set and an old→new alias table; propose, never invent, IDs. 2) *Prompts*: review each prompt for designation, inputs, output location, decision vocabulary and stop conditions; rewrite so an implementer gets a usable response first time; generate repeated cycles from one library, never by hand-copy. 3) *Merge*: identify repeated or similar concepts across all versions and consolidate each into one definition; record each in a merge register with sources. 4) *Currency*: check every process, practice and standard against current editions, local jurisdiction first, then regional, then global; for each outdated practice give the replacement and a feasible transition window. 5) *Consistency*: list contradictions across the references (counts, tags, ranges, thresholds, roles, states) and resolve or escalate each as an Open Item. 6) *Generic*: remove project-specific names, paths, vendors and examples from rules; isolate any example and mark it. 7) *Expected values*: keep `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE` and `EXPECTED-PROJECT-END-DATE` as probing estimates with a derivation rule. 8) *Boundary*: PBIM identifiers end at the Project Charter.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.01]PBIM_Document-<agent-slug>-<UTC>.md` with front matter; A) executive synthesis, B) identifier map, C) updated PBIM, D) retained/superseded decisions, E) remaining assumptions, F) verification gates, G) implementation obligations, H) authorization status.
EVIDENCE LABELS. Required; state which parts of each reference you could not read.
STOP IF a reference is unreadable, two references conflict on authority, or a change would alter a protected control (that needs `CA`).
DO NOT reproduce project-specific examples as rules; claim any control is implemented or any AECC closed.
<<START PBIM Reference Package>>
1. Current PBIM document:
{{path@SHA}}
2. PBIM AEA Query:
{{path@SHA}}
3. PBIM AEA Reports (all agents):
{{path@SHA}}
4. PBIM AEV Statements (all revisions):
{{path@SHA}}
5. PBIM AEV Decisions/Responses (all agents, all revisions):
{{path@SHA}}
6. PBIM AEC Adversarial Duel documents (all versions):
{{path@SHA}}
7. PBIM AEC Results (all agents):
{{path@SHA}}
8. Approved AECC closures, if any:
{{path@SHA}}
<<STOP PBIM Reference Package>>
<<START Currency Inputs>>
1. Local Regulatory Overlay register:
{{path@SHA}}
2. Standards register with verification dates:
{{path@SHA}}
<<STOP Currency Inputs>>
Note the following:
1. Treat the current PBIM as the structural baseline, not as unquestionable authority.
2. Run this prompt only for first creation or maintenance.
3. Identify unresolved questions rather than silently inventing answers.
#**<<STOP Prompt 1. Generate Generic PBIM Candidate>>**#

---

### [PROJECT-KEY][PBI-02-0004.00.02] — PBIM Assurance Baseline — *verify the PBIM itself through AEA → AEV → AEC → AECC*

| Card | |
| --- | --- |
| **Purpose** | Establish the controlled architectural baseline of the PBIM framework before it is relied on |
| **Profile** | `HIGH-ASSURANCE` default |
| **Subject** | The PBIM candidate from `PBI-01`, as `path@SHA` |
| **Exit gate (G2)** | Final decision `PBIM APPROVE` (or approve with conditions accepted by `H0`); §5.9 gates met; AECC filed |
| **Stop if** | Subject is a branch/session link; a reviewer fails independence; revision bound reached with no `H0` ruling |

**Cycle binding** (§7.4)

| Token | Value |
| --- | --- |
| `{{SUBJECT}}` | PBIM Document |
| `{{SUBJECT-CODE}}` | `PBIM` |
| `{{SECTION-ID}}` / `{{ANCHOR}}` | `PBI-02-0004.00.02` / `0004.00.02` |
| `{{PROFILE}}` | `HIGH-ASSURANCE` |
| `{{CYCLE}}` | `R1` (increment after each Architectural Reset) |

**Implementation steps**
1. Bind the tokens above and attach the subject as `path@SHA`.
2. Run **AE Cycle Prompts 1–8** (§7.5) in order, filing responses between `<<START Responses: …>>` markers.
3. After Prompt 7 closes, update the PBIM with AECC conditions and re-run Prompt 8 on the updated document.
4. `H0` signs the Decision Ledger; promote the baseline by pull request and tag it `pbim-baseline/<version>@<commit>`.
5. Record AECC status. Until this step completes, no later Section may treat the PBIM as approved.

---

### [PROJECT-KEY][PBI-03-0004.00.03] — Project Context & Proposal Definition — *turn a rough idea into a fundable, buildable proposal*

| Card | |
| --- | --- |
| **Purpose** | Convert a raw draft into an Established Proposal that can be shown to sponsors/investors and that drives template generation |
| **Profile** | `STANDARD` (raise per §5.3) |
| **Inputs** | Draft proposal; other references; approved PBIM; LRO register |
| **Outputs** | Established Proposal (detailed) + Summary on request + **Variable Set** |
| **Exit gate (G3)** | Variable Set complete; every claim labelled; proposal ready for `PBI-04`; `H0` accepts for verification |
| **Stop if** | A required variable is missing — ask; never generate around the gap |

**Required Variable Set** — the proposal must define: `PROJECT-NAME`; `PROJECT-KEY` (`BASE-ID`, `PROJECT-ID`); `PROJECT-LOCATION` (incl. country); `PROJECT-FOLDER`; `PRODUCTION-REPO-NAME`; **`EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` with range and estimating basis (§2.2)**; problem and explanation; candidate solutions; requirements; deliverables; preliminary scope and exclusions; stakeholders; assumptions, constraints, dependencies; data/security classification; proposed risk profile; applicable legal/regulatory obligations (from the LRO); funding or sponsorship request; and a sponsor expression-of-interest path.

**Implementation steps**
1. Draft the proposal in as much detail as possible (problem, idea, environment, solutions, requirements, deliverables).
2. Attach the draft, references, approved PBIM and LRO to Prompt 1.
3. Answer every question the agents raise about missing variables; regenerate only afterward.
4. Review, edit and regenerate until the proposal is clear and detailed enough to pass `PBI-04`.
5. Use Prompt 2 to produce a summary for sponsors who want a short version.

#**<<START Prompt 1. Establish the Project Proposal>>**#
[Designation: Lead Agent / Collaborating Agents]
OBJECTIVE. Produce an Established Project Proposal from the draft, complete and clear enough to pass `PBI-04`.
TASK. 1) List every Required Variable; for each missing one, ask the human and wait — do not regenerate until answered. 2) Break each vague description into concrete steps, then restate it as a brief clear description. 3) Place content under the lifecycle focus area it belongs to (anything about the end of the project goes under closing; risks under risk). 4) Apply the LRO: name governing local obligations first, then regional and global; mark each `IN-FORCE` or `PENDING`. 5) Derive `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE` and `EXPECTED-PROJECT-END-DATE` per §2.2 with range, basis and evidence label; return any inconsistency as a finding. 6) Include a clear expression-of-interest path for sponsors/investors (whom to contact, what to send, what happens next); interest leads to a scheduled charter meeting where deliverables and procedures are reviewed before the Charter is approved. 7) Provide the full detailed proposal now and offer a one-page summary. 8) Review any prompts inside the draft and improve them per §7.1.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.03]Proposal_Established-<agent-slug>-<UTC>.md`.
EVIDENCE LABELS. Required, especially for market, cost, schedule and legal claims.
STOP IF you would have to invent a number, a legal citation or a stakeholder.
DO NOT present estimates as quotes; do not promise returns to investors; do not treat expected dates as commitments.
<<START Draft Project Proposal>>
1. Draft proposal and initial instruction document:
{{path@SHA}}
2. Other reference documents:
{{path@SHA}}
<<STOP Draft Project Proposal>>
<<START Governing References>>
1. Approved PBIM:
{{path@SHA}}
2. Local Regulatory Overlay register:
{{path@SHA}}
<<STOP Governing References>>
Note the following:
1. The generated proposal must define the primary variables PBIM needs to generate the custom template.
2. Any missing requirement or detail must be requested before regenerating.
3. The established proposal will be shared with potential investors and sponsors; write for that audience without exaggeration.
#**<<STOP Prompt 1. Establish the Project Proposal>>**#

#**<<START Prompt 2. Generate Sponsor Summary of the Project Proposal>>**#
[Designation: Lead Agent]
OBJECTIVE. Produce a one-page summary of the Established Proposal that a sponsor can read on the spot.
TASK. 1) State problem, solution, expected outcomes, deliverables, `EXPECTED-*` timing (labelled expected, with range), funding/sponsorship ask, key risks and the expression-of-interest path. 2) Point to the detailed proposal for everything else. 3) Omit nothing that would mislead by omission.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.03]Proposal_Summary-<agent-slug>-<UTC>.md`.
STOP IF the detailed proposal has open `BLOCKER` findings.
DO NOT add facts that are not in the detailed proposal.
<<START Established Proposal>>
1. Established Project Proposal:
{{path@SHA}}
<<STOP Established Proposal>>
Note the following:
1. Generate on request; the detailed version remains the controlled artifact.
#**<<STOP Prompt 2. Generate Sponsor Summary of the Project Proposal>>**#

---

### [PROJECT-KEY][PBI-04-0004.00.04] — Project Proposal Assurance — *verify the proposal through AEA → AEV → AEC → AECC*

| Card | |
| --- | --- |
| **Purpose** | Verify the Established Proposal before the operating template is assembled |
| **Profile** | `STANDARD` default (raise for payment, authentication, personal data, safety) |
| **Subject** | Established Proposal from `PBI-03`, as `path@SHA` |
| **Exit gate (G4)** | `PROPOSAL APPROVE` (or conditions accepted by `H0`); controlled identity; explicit assumptions; risk classification; stakeholder baseline; measurable preliminary success criteria; material blockers resolved |
| **Stop if** | Subject link is not durable; a reviewer fails independence |

**Cycle binding** — `{{SUBJECT}}` = Project Proposal · `{{SUBJECT-CODE}}` = `PROPOSAL` · `{{ANCHOR}}` = `0004.00.04` · `{{PROFILE}}` = `STANDARD` · `{{CYCLE}}` = `R1`.

**Proposal-specific questions for Prompt 1** (append to the core set): strategic alignment; value/outcomes; scope coherence; stakeholder impact; technical feasibility; security/privacy; dependencies; delivery approach; resource feasibility; procurement implications; measurable success criteria; operational implications; consistency of the `EXPECTED-*` values.

**Implementation steps**: bind tokens → run AE Cycle Prompts 1–8 → record AECC → `H0` signs the Ledger → promote the baseline.

---

### [PROJECT-KEY][PBI-05-0004.00.05] — Project Template Assembly — *build the project-specific operating template*

| Card | |
| --- | --- |
| **Purpose** | Assemble the project-specific template (the plan skeleton from Charter to closure) from the verified proposal |
| **Profile** | `STANDARD` (raise per §5.3) |
| **Inputs** | Verified Proposal; prior successful project documents; approved PBIM; LRO; Appendix A catalogue |
| **Outputs** | Project Template; primary variables; configuration settings; summary on request |
| **Exit gate (G5)** | Every catalogue anchor is instantiated, `NOT APPLICABLE` with rationale, `DEFERRED` with owner and date, or `BLOCKED` with reason; no empty stubs; passes `PBI-06` |
| **Stop if** | Variables missing; proposal contradicts the LRO; a catalogue anchor would have to be invented |

A **skeleton** may contain placeholders. An **operating template** may not contain unresolved mandatory controls.

**Minimum template components**: project identity; governance; Authority Register; Authority–Permission Matrix; identifier registry; stakeholders; objectives/outcomes; scope; requirements; architecture/ADR index; risk, assumption and issue registers; security boundary; data classification; Task Packet model; Decision Ledger; evidence model; repository model; agent instructions; communication model; change model; quality model; verification model; operational-readiness model; release/handoff model; `EXPECTED-*` schedule values carried forward.

**Implementation steps**
1. Collect prior successful project documents as references (distinct from the proposal).
2. Send Prompt 1 with the resources below.
3. Review; request regeneration until implementation-ready; then proceed to `PBI-06`.
4. The template is formally approved at the charter meeting with sponsors.

#**<<START Prompt 1. Generate the Project Template>>**#
[Designation: Lead Agent / Collaborating Agents]
OBJECTIVE. Generate an implementation-ready Project Template unique to the verified proposal.
TASK. 1) Cover the full lifecycle using the Appendix A catalogue: each anchor is instantiated, `NOT APPLICABLE` (justified), `DEFERRED` (owner and date) or `BLOCKED` (reason). 2) Define primary variables and configuration: name, base, key, `EXPECTED-*` schedule values, risk profile, role bindings, repositories, branch scheme, LRO summary; ask for any missing item before regenerating. 3) Place every item under the Section that matches its lifecycle role. 4) Break complex steps into ordered sub-steps; give each Section inputs, outputs, exit gate and, where agents are used, prompts per §7.1. 5) Review and improve prompts found in the reference templates; keep notes, prompts and resource slots inline where used. 6) Check against local-first current practice via the LRO; for each outdated practice give the replacement and a transition path. 7) Write for sponsor review; offer a summary.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.05]Project_Template-<agent-slug>-<UTC>.md`.
EVIDENCE LABELS. Required; flag every assumption.
STOP IF the template would require authority not in the Authority Register.
DO NOT treat template approval as Charter approval.
<<START Template Source Documents>>
1. Verified Project Proposal:
{{path@SHA}}
2. Prior successful project documents (reference only):
{{path@SHA}}
<<STOP Template Source Documents>>
<<START Governing References>>
1. Approved PBIM:
{{path@SHA}}
2. Local Regulatory Overlay register:
{{path@SHA}}
<<STOP Governing References>>
Note the following:
1. The template must be detailed and clear enough to pass `PBI-06`.
2. Close the Proposal and Template reference slots separately; do not point both at one file.
3. Missing requirements must be requested before regenerating the full template.
#**<<STOP Prompt 1. Generate the Project Template>>**#

#**<<START Prompt 2. Generate Sponsor Summary of the Project Template>>**#
[Designation: Lead Agent]
OBJECTIVE. Summarize the Project Template for sponsors reviewing it at the charter meeting.
TASK. 1) List phases, major deliverables, governance gates, `EXPECTED-*` timing (labelled expected), roles and top risks. 2) Link each statement to its template Section ID.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.05]Template_Summary-<agent-slug>-<UTC>.md`.
STOP IF the template is not at least a Controlled Candidate.
DO NOT introduce content absent from the template.
<<START Project Template>>
1. Project Template:
{{path@SHA}}
<<STOP Project Template>>
Note the following:
1. Generate on request.
#**<<STOP Prompt 2. Generate Sponsor Summary of the Project Template>>**#

---

### [PROJECT-KEY][PBI-06-0004.00.06] — Project Template Assurance — *verify the template through AEA → AEV → AEC → AECC*

| Card | |
| --- | --- |
| **Purpose** | Verify the assembled template is internally consistent, executable as a governance mechanism and ready for simulation |
| **Profile** | `STANDARD` default |
| **Subject** | Project Template from `PBI-05`, as `path@SHA` |
| **Exit gate (G6)** | `TEMPLATE APPROVE` (or conditions accepted by `H0`); all checks below pass; AECC filed |
| **Stop if** | Subject link not durable; reviewer fails independence |

**Cycle binding** — `{{SUBJECT}}` = Project Template · `{{SUBJECT-CODE}}` = `TEMPLATE` · `{{ANCHOR}}` = `0004.00.06` · `{{PROFILE}}` = `STANDARD` · `{{CYCLE}}` = `R1`.

**Template-specific checks** (append to Prompt 1 core questions): identifier uniqueness; canonical-location consistency; authority/permission alignment; protected-control precedence; Task Packet state model; stop/resume/reset; risk profile; evidence classification; decision traceability; security/secret boundary; durable references; dependency declarations; change/materiality routing; operational-readiness definition; agent-role separation; human escalation; failure recovery; gate reachability; `EXPECTED-*` consistency with the schedule sections.

For each mandatory control, Prompt 4 reviewers record: requirement, mechanism, authority, evidence, failure state, recovery path, and current maturity (§5.2) — never upgraded without evidence.

**Implementation steps**: bind tokens → run AE Cycle Prompts 1–8 → record AECC → `H0` signs → promote baseline.

---

### [PROJECT-KEY][PBI-07-0004.00.07] — Project Framework Initialization — *stand up and verify the governance substrate*

| Card | |
| --- | --- |
| **Purpose** | Create the repositories, registers, rulesets, agent identities and gates the approved template assumes |
| **Profile** | As recorded in the risk classification |
| **Inputs** | Approved PBIM, Proposal, Template; LRO; role bindings |
| **Outputs** | Configuration Plan; Control State Register; Authority Register; Authority–Permission Matrix; identifier registry |
| **Exit gate (G7)** | Every `M` control ≥ `ENFORCEABLE` (≥ `ENFORCED` for blocking gates under `HIGH-ASSURANCE`), verified by an independent verifier, signed by `H0` |
| **Stop if** | `CA`/`H0` records absent; a control cannot be verified; secrets appear in any prompt, log or document |

`M` mandatory · `C` conditional (state the reason when skipped).

| # | Control | Done-evidence | LIGHT | STANDARD | HIGH |
| --- | --- | --- | --- | --- | --- |
| 1 | Project board/tracker named for the project with the `PROJECT-KEY` prefix | Board URL; key in registry | M | M | M |
| 2 | Folders/repositories: R&D, docs, governance, production | Paths exist; default branches protected | M | M | M |
| 3 | Identifier registry bootstrapped (§4.3) | Registry file with parent hash; serialized write path | C | M | M |
| 4 | `CA` record (identity, accountable principal, source, scope, appointment, review date, succession) | Signed record | C | M | M |
| 5 | Authority Register (`H0`, delegate, `H1`, appointment authority, expiry; `H0` appointed by someone other than `H0`) | Register commit | M | M | M |
| 6 | Authority–Permission Matrix + privileged-account audit (§5.5) | Matrix revision; audit log; no unmapped admin | C | M | M |
| 7 | Repository rulesets + CODEOWNERS on governance and production; force-push denied; workflow changes reviewed; signed commits | Ruleset export | M | M | M |
| 8 | Per-agent identities, least privilege, short-lived credentials | Identity list mapped to roles | M | M | M |
| 9 | `AGENTS.md` hierarchy conforms to §1.3 across affected repositories; code is physical truth, `AGENTS.md` carries durable directives | Drift report clean | M | M | M |
| 10 | Project skills/instructions in a **tracked** path (e.g. `.github/skills/<project>`); never a Git-internal directory | Visible in `git ls-files` | C | M | M |
| 11 | ADR location and Decision Ledger (§5.4) | Paths exist; first entry made | C | M | M |
| 12 | Task Packet schema (Appendix C) + automated scope check | Test change outside scope is blocked | C | M | M |
| 13 | Challenge-before-build gate | Required status check | C | M | M |
| 14 | Stop states `S0–S4`, resume authority, machine enforcement where feasible (§5.6) | Dry-run stop blocks merge | M | M | M |
| 15 | Risk-profile classification record (§5.3) | Signed record | M | M | M |
| 16 | LRO verified within 90 days | `last_verified` dates | M | M | M |
| 17 | Evidence store, retention, integrity anchor in an independent trust domain | Anchor verification record | C | C | M |
| 18 | Secrets boundary: push protection and secret scanning on; no credentials in prompts, logs or evidence | Scanner enabled; redaction tested | M | M | M |
| 19 | Operational-readiness checklist template (§5.11) | Template in release path | C | M | M |
| 20 | Baseline-drift monitor outside the execution path | Monitor alert test | C | C | M |
| 21 | SBOM/provenance and dependency-update tooling for production repositories | Artifact produced in CI | C | M | M |
| 22 | Control State Register: current maturity of each control | Table committed | M | M | M |

**Implementation steps**: (1) Send Prompt 1 for the Configuration Plan. (2) `H0`/administrators apply changes in dependency order (authority and registry → rulesets → identities → gates). (3) Send Prompt 2 to an independent verifier. (4) Record results in the Control State Register; fix failures; re-verify. (5) `H0` signs G7.

#**<<START Prompt 1. Generate the Project Configuration Plan>>**#
[Designation: Lead Agent]
OBJECTIVE. Produce the Configuration Plan for the PBI-07 control table at the recorded risk profile.
TASK. 1) For each control state applicability (`M`/`C`), the exact change, owner role and done-evidence. 2) Order steps by dependency. 3) Separate what requires a human (`H0`/`CA`/administrator) from what agents may do. 4) Mark every control `DESIGNED` until evidence exists.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.07]Configuration_Plan-<agent-slug>-<UTC>.md`.
STOP IF a step needs administrative rights no recorded authority holds.
DO NOT apply any change; this prompt plans only.
<<START Initialization Inputs>>
1. Approved PBIM / Proposal / Template:
{{path@SHA}}
2. Risk classification record:
{{path@SHA}}
3. Local Regulatory Overlay register:
{{path@SHA}}
4. Current repository/settings export:
{{path@SHA}}
<<STOP Initialization Inputs>>
Note the following:
1. Use only fields recorded in the Authority Register; do not substitute informal mechanisms for a missing control.
#**<<STOP Prompt 1. Generate the Project Configuration Plan>>**#

#**<<START Prompt 2. Verify Project Framework Initialization>>**#
[Designation: Collaborating Agents — Verification role]
OBJECTIVE. Independently verify each configured control against its done-evidence.
TASK. 1) For each control, run or inspect the evidence and record `PASS` / `FAIL` / `NOT TESTABLE`, the evidence reference and resulting maturity state. 2) Try once to defeat each control (e.g. propose a change outside a Task Packet). 3) File an independence record (§5.7).
OUTPUT. `[{{PROJECT-KEY}}-0004.00.07]Verification_Record-<agent-slug>-<UTC>.md`.
STOP IF a mandatory control fails — flag `S3`.
DO NOT repair what you verify.
<<START Initialization Evidence>>
1. Configuration Plan:
{{path@SHA}}
2. Repository/settings export after configuration:
{{path@SHA}}
3. Control State Register:
{{path@SHA}}
<<STOP Initialization Evidence>>
Note the following:
1. The verifier must not be the party who applied the changes.
#**<<STOP Prompt 2. Verify Project Framework Initialization>>**#

---

### [PROJECT-KEY][PBI-08-0004.00.08] — Project Simulation & Readiness Exercise — *dry run before real work*

| Card | |
| --- | --- |
| **Purpose** | Prove the initialized framework works end to end on a harmless task and stops when it should |
| **Profile** | Same as the project |
| **Inputs** | Initialized substrate (`PBI-07`); trivial Task Packet; sandbox repository or branch |
| **Outputs** | Simulation Report: timings, decisions, failures, fixes; capacity check of `EXPECTED-*` values |
| **Exit gate (G8)** | All required drills produced the expected state; reviewer/`H0` hours shown realistic; `H0` signs |
| **Stop if** | A drill touches production resources or real credentials |

**Walkthrough scope**: team briefing and roles; change-management path (request → impact → decision → baseline update); template operation and preview; contractor requirements, tendering and procurement steps (apply local procurement rules from the LRO); timeline and deliverables; project after-life (handover, support, archival).

**Required drills**

| Drill | Expected result |
| --- | --- |
| Agent edits a file outside its Task Packet | Scope check blocks; `STOP → REPORT → NEW PACKET` |
| Normal Task Packet issued and completed | Evidence and Ledger entries produced |
| Requirement change / risk reclassification | Materiality routing per §5.3 |
| Conflicting agent recommendations | Both preserved; escalated with evidence |
| AEV failure / AEC failure | Return to correct stage; no artificial approval |
| `H0` unreachable | Delegate acts only within scope; constitutional change `BLOCKED` |
| Concurrent registry/matrix change | Serialized; stale write rejected; conflict state raised |
| `AGENTS.md` conflict | Higher-precedence control wins; drift flagged |
| Reviewer shares credentials with author | `CHALLENGE-INDEPENDENCE-FAILED` |
| Evidence integrity failure | Decision state downgraded; re-verification required |
| Unauthorized technical privilege | `AUTHORITY-PERMISSION-DRIFT` |
| Dependency drift | Detected; routed |
| Emergency delegation passes TTL unconfirmed | `STOPPED` |
| Authoritative link points to a deleted branch | Rejected as non-durable |
| Operational-readiness failure | Release blocked |
| Simulated `S4` event | Immediate halt and human escalation |

**Implementation steps**: (1) Prompt 1 designs drills; `H0` approves. (2) Prompt 2 executes them in the sandbox. (3) Record divergences; fix and re-run failed drills. (4) Compare observed effort with `EXPECTED-*` capacity assumptions and update the estimate with a new range. (5) `H0` signs G8.

#**<<START Prompt 1. Design the Simulation Drills>>**#
[Designation: Lead Agent]
OBJECTIVE. Design a harmless, sandboxed simulation that exercises each required drill.
TASK. 1) Write a trivial Task Packet and a script per drill: trigger, current state, authorized actor, expected transition, required evidence, stop condition, recovery, audit record. 2) Define the sandbox boundary and prove it contains no production resources or real credentials. 3) Define measures: elapsed time per gate, human hours used.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.08]Simulation_Design-<agent-slug>-<UTC>.md`.
STOP IF the sandbox cannot be isolated.
DO NOT perform any production change.
<<START Simulation Inputs>>
1. Verification Record from PBI-07:
{{path@SHA}}
2. Approved Template:
{{path@SHA}}
3. Control State Register:
{{path@SHA}}
<<STOP Simulation Inputs>>
Note the following:
1. The simulation succeeds only if each governance result can be reproduced from defined controls, not from informal agent knowledge.
#**<<STOP Prompt 1. Design the Simulation Drills>>**#

#**<<START Prompt 2. Execute the Simulation and Report>>**#
[Designation: Collaborating Agents — Verification role]
OBJECTIVE. Execute the approved drills and report whether each produced the expected state.
TASK. 1) Run each drill, recording timestamps and evidence. 2) Measure elapsed time and human hours per gate. 3) List every divergence and propose a fix without applying it. 4) Recommend an updated range for the `EXPECTED-*` values with your basis.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.08]Simulation_Report-<agent-slug>-<UTC>.md`.
STOP IF any drill touches production resources or real credentials.
DO NOT mark a drill passed without evidence.
<<START Simulation Design>>
1. Simulation Design:
{{path@SHA}}
<<STOP Simulation Design>>
Note the following:
1. `H0` observes and signs; the executing agent does not sign its own pass.
#**<<STOP Prompt 2. Execute the Simulation and Report>>**#

---

### [PROJECT-KEY][PBI-09-0004.00.09] — PBIM Activation & Charter Readiness — *switch PBIM controls on and hand over to the Charter*

| Card | |
| --- | --- |
| **Purpose** | Finalize the pre-charter package and decide whether the project may enter Charter development |
| **Inputs** | Approved PBIM, Proposal, Template; verified configuration; simulation report; LRO ≤ 90 days old |
| **Outputs** | Activation Record; baseline tag `pbim-baseline/<version>@<commit>`; charter-meeting invitation |
| **Exit gate (G9)** | The Final Pre-Charter Gate (§9) is answered; `H0` (and `CA` where required) signs |
| **Stop if** | Any mandatory line is untrue — never activate on a promise |

**State outcome** (exactly one): `READY FOR CHARTER` · `READY WITH FORMAL CONDITIONS` · `BLOCKED` · `ARCHITECTURAL RESET`. `READY FOR CHARTER` sets `PBIM-READY-FOR-CHARTER`; it does **not** mean the project is approved.

**Implementation steps**: (1) Prompt 1 compiles the Activation Record. (2) Prompt 2 independently confirms each line. (3) `H0` decides in Prompt 3. (4) Tag the baseline; issue the charter-meeting invitation.

#**<<START Prompt 1. Prepare the Activation Record>>**#
[Designation: Lead Agent]
OBJECTIVE. Compile the Activation Record answering the Final Pre-Charter Gate (§9).
TASK. 1) For each of the 22 questions give the answer, evidence as `path@SHA`, and an evidence label. 2) List every mandatory control still only `DESIGNED`. 3) Summarize residual risk and every open finding, condition and blocker with owner, evidence required, advancement impact and escalation authority. 4) Recommend one state outcome.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.09]Activation_Record-<agent-slug>-<UTC>.md`.
STOP IF any mandatory answer is unresolved and not classified as an authorized Charter-stage input.
DO NOT convert unresolved assumptions into approvals; do not activate — only `H0` does.
<<START Activation Evidence>>
1. PBIM, Proposal and Template approval records (AECC and final decisions):
{{path@SHA}}
2. PBI-07 Verification Record and Control State Register:
{{path@SHA}}
3. PBI-08 Simulation Report:
{{path@SHA}}
4. Risk classification record and Authority Register:
{{path@SHA}}
5. Local Regulatory Overlay register:
{{path@SHA}}
<<STOP Activation Evidence>>
Note the following:
1. The `EXPECTED-*` values must reflect the post-simulation estimate.
#**<<STOP Prompt 1. Prepare the Activation Record>>**#

#**<<START Prompt 2. Confirm the Activation Record>>**#
[Designation: Collaborating Agents — Verification role]
OBJECTIVE. Independently re-check every line of the Activation Record.
TASK. 1) Mark each line `CONFIRMED` or `UNCONFIRMED` with your own evidence. 2) List contradictions between lines. 3) Recommend `ACTIVATE` or `HOLD`.
OUTPUT. `[{{PROJECT-KEY}}-0004.00.09]Activation_Confirmation-<agent-slug>-<UTC>.md`.
STOP IF a mandatory line is `UNCONFIRMED`.
DO NOT rely on the Lead Agent's evidence labels without checking.
<<START Activation Record>>
1. Activation Record:
{{path@SHA}}
<<STOP Activation Record>>
Note the following:
1. File an independence record.
#**<<STOP Prompt 2. Confirm the Activation Record>>**#

#**<<START Prompt 3. Record the Activation Decision>>**#
[Designation: Lead Agent]
OBJECTIVE. Prepare the decision record for `H0` and file it after `H0` decides.
TASK. 1) Present both Activation documents side by side with conflicts highlighted. 2) Record `H0`'s outcome, conditions with owners and dates, and escalation authority. 3) Tag the baseline and update the Decision Ledger and identifier registry.
OUTPUT. Decision Ledger entry and `pbim-baseline/<version>` tag reference.
STOP IF `H0` has not signed.
DO NOT record a state outcome `H0` did not choose.
<<START Activation Documents>>
1. Activation Record:
{{path@SHA}}
2. Activation Confirmations (all agents):
{{path@SHA}}
<<STOP Activation Documents>>
Note the following:
1. Charter-stage inputs are explicitly listed, never implied.
#**<<STOP Prompt 3. Record the Activation Decision>>**#

---

### [PROJECT-KEY][GOV-01-0004.01] — Initiate Project or Phase — *Develop Project Charter; the PBIM integration boundary*

**PBIM identifiers end here.** After the Charter is authorized: `PBIM STATUS = COMPLETE`, `PROJECT STATUS = CHARTERED`. An agent-generated charter is not authorized; the required authority must approve it under the organization's governance model. Later work uses the identifiers in the approved Template (Appendix A), ending at `GOV-13-9004.07`.

**Charter minimum content**: purpose and need; measurable objectives/outcomes; high-level requirements; scope and exclusions; deliverables and milestones; assumptions and constraints; high-level risks and risk profile; stakeholders; governance, `CA` and `H0` identities; sponsor/project authority; project manager/lead; delivery approach; success and acceptance criteria; funding and resource authorization; dependencies; security, privacy and regulatory obligations (from the LRO); **baselined `PROJECT-DURATION`, `PROJECT-START-DATE`, `PROJECT-END-DATE`** (replacing the `EXPECTED-*` values, with the estimating basis); role bindings; approval/signature mechanism.

| Card | |
| --- | --- |
| **Profile** | As recorded |
| **Exit** | Charter authorized; Ledger updated; transition to the project's lifecycle recorded |
| **Stop if** | `CA`/`H0` unrecorded; sponsors change scope beyond the approved Proposal (a Level 2+ change) |

#**<<START Prompt 1. Draft the Project Charter>>**#
[Designation: Lead Agent]
OBJECTIVE. Draft the Project Charter for the charter meeting.
TASK. 1) Populate every minimum Charter field from the sources; mark gaps `UNKNOWN` and list the questions. 2) Trace each material Charter element to its PBIM source artifact or decision. 3) Show differences between the Proposal/Template and sponsor feedback. 4) Replace `EXPECTED-*` schedule values with proposed baselined values and state the basis, or keep them expected and list the missing inputs. 5) Prepare the meeting agenda: deliverables, procedures, risk profile, authority, funding. 6) Record any new material fact as a new assumption, decision or Charter-stage input.
OUTPUT. `[{{PROJECT-KEY}}-0004.01]Project_Charter-<agent-slug>-<UTC>.md`.
STOP IF `CA`/`H0` are not recorded.
DO NOT sign or approve; humans approve the Charter.
<<START Charter Sources>>
1. Activation Record and decision:
{{path@SHA}}
2. Approved Proposal and Template:
{{path@SHA}}
3. Sponsor feedback from the charter meeting:
{{path@SHA}}
<<STOP Charter Sources>>
Note the following:
1. The Charter is the first document under the project's own lifecycle framework.
#**<<STOP Prompt 1. Draft the Project Charter>>**#

#**<<START Prompt 2. Review the Draft Project Charter>>**#
[Designation: Collaborating Agents]
OBJECTIVE. Review the draft Charter for traceability and consistency with the approved PBIM package.
TASK. 1) Check each Charter element against its source. 2) Flag any scope, schedule, cost or authority drift. 3) Label every claim. 4) Return `CHARTER SOUND` / `CHARTER RETURN` with findings.
OUTPUT. `[{{PROJECT-KEY}}-0004.01]Charter_Review-<agent-slug>-<UTC>.md`.
STOP IF the draft introduces unrecorded material facts.
DO NOT approve on behalf of the authority.
<<START Draft Charter>>
1. Draft Project Charter:
{{path@SHA}}
<<STOP Draft Charter>>
Note the following:
1. Authorization remains with the human authority.
#**<<STOP Prompt 2. Review the Draft Project Charter>>**#

---

# 9. GATE REGISTER AND FINAL PRE-CHARTER GATE (merged: M-05)

| Gate | Passed when |
| --- | --- |
| G0 | Identity Block complete; `CA`/`H0` identified |
| G1 | PBIM candidate committed; sources accounted for |
| G2 | PBIM AECC closed and `PBIM APPROVE` recorded |
| G3 | Variable Set complete; `EXPECTED-*` derived with range and basis |
| G4 | `PROPOSAL APPROVE`; material blockers resolved |
| G5 | Template has no unresolved anchor and no empty stub |
| G6 | `TEMPLATE APPROVE`; AECC filed |
| G7 | Governance substrate verified at required maturity |
| G8 | Simulation drills passed; capacity realistic |
| G9 | Final Pre-Charter Gate answered; `H0` activates |

**Final Pre-Charter Gate** — the Lead Agent answers each question with evidence. Any unresolved mandatory answer means **DO NOT ADVANCE TO `GOV-01-0004.01`**, unless it is explicitly classified as an authorized Charter-stage input.

1. Who has authority (`CA`, `H0`, delegates)? 2. What is the project? 3. Why does it exist? 4. What value/outcomes are expected? 5. What is in scope? 6. What is explicitly out of scope? 7. What is known versus assumed? 8. What are the major risks and the risk profile? 9. What security, privacy and regulatory constraints apply (LRO current)? 10. What architecture is approved? 11. What evidence supports it? 12. Has adversarial challenge occurred where required, with genuine independence? 13. Are material findings closed or formally dispositioned? 14. Are technical permissions aligned with authority? 15. Is the identifier registry trustworthy? 16. Are Task Packets bounded? 17. Can the project stop safely? 18. Can it recover safely? 19. Is emergency authority bounded? 20. Is the operating template ready? 21. Has the framework been simulated? 22. Are `EXPECTED-PROJECT-DURATION`, `-START-DATE` and `-END-DATE` consistent, labelled and ready to be baselined at the Charter?

---

# APPENDICES

## Appendix A — Process Catalogue (49 stable positions, classification spine only)

Tags follow the seven PMBOK 8 domains; "Legacy" is the pre-v3 anchor for the alias table. Count check: 2 + 24 + 10 + 12 + 1 = **49**.

| # | New ID | Process (engineering subtitle where useful) | Legacy anchor |
| --- | --- | --- | --- |
| 1 | `GOV-01-0004.01` | Initiate Project or Phase (Develop Project Charter) | 0004.1 |
| 2 | `STK-01-0013.01` | Identify Stakeholders | 0013.1 |
| 3 | `GOV-02-1004.02` | Develop Project Management Plan | 1004.2 |
| 4 | `SCP-01-1005.01` | Plan Scope Management | 1005.1 |
| 5 | `SCP-02-1005.02` | Collect Requirements | 1005.2 |
| 6 | `SCP-03-1005.03` | Define Scope | 1005.3 |
| 7 | `SCP-04-1005.04` | Create Work Breakdown Structure | 1005.4 |
| 8 | `SCH-01-1006.01` | Plan Schedule Management | 1006.1 |
| 9 | `SCH-02-1006.02` | Define Activities | 1006.2 |
| 10 | `SCH-03-1006.03` | Sequence Activities | 1006.3 |
| 11 | `SCH-04-1006.04` | Estimate Activity Durations | 1006.4 |
| 12 | `SCH-05-1006.05` | Develop Schedule | 1006.5 |
| 13 | `FIN-01-2007.01` | Plan Cost Management | 2007.1 |
| 14 | `FIN-02-2007.02` | Estimate Costs | 2007.2 |
| 15 | `FIN-03-2007.03` | Determine Budget | 2007.3 |
| 16 | `GOV-03-2008.01` | Plan Quality Management | 2008.1 |
| 17 | `RES-01-2009.01` | Plan Resource Management | 2009.1 |
| 18 | `RES-02-2009.02` | Estimate Activity Resources | 2009.2 |
| 19 | `STK-02-2010.01` | Plan Communications Management | 2010.1 |
| 20 | `RSK-01-3011.01` | Plan Risk Management | 2011.1 |
| 21 | `RSK-02-3011.02` | Identify Risks | 3011.2 |
| 22 | `RSK-03-3011.03` | Perform Qualitative Risk Analysis | 3011.3 |
| 23 | `RSK-04-3011.04` | Perform Quantitative Risk Analysis | 3011.4 |
| 24 | `RSK-05-3011.05` | Plan Risk Responses | 3011.5 |
| 25 | `GOV-04-3012.01` | Plan Procurement Management | 3012.1 |
| 26 | `STK-03-3013.02` | Plan Stakeholder Engagement | 3013.2 |
| 27 | `GOV-05-4004.03` | Direct and Manage Project Work (build under Task Packets) | 4004.3 |
| 28 | `GOV-06-4004.04` | Manage Project Knowledge | 4004.4 |
| 29 | `GOV-07-5008.02` | Manage Quality (PR review, tests, security audit) | 5008.2 |
| 30 | `RES-03-5009.03` | Acquire Resources | 5009.3 |
| 31 | `RES-04-6009.04` | Develop Team | 6009.4 |
| 32 | `RES-05-6009.05` | Manage Team | 6009.5 |
| 33 | `STK-04-6010.02` | Manage Communications | 6010.2 |
| 34 | `RSK-06-6011.06` | Implement Risk Responses | 6011.6 |
| 35 | `GOV-08-6012.02` | Conduct Procurements | 6012.2 |
| 36 | `STK-05-6013.03` | Manage Stakeholder Engagement | 6013.3 |
| 37 | `GOV-09-7004.05` | Monitor and Control Project Work | 7004.5 |
| 38 | `GOV-10-7004.06` | Perform Integrated Change Control | 7004.6 |
| 39 | `SCP-05-7005.05` | Validate Scope | 7005.5 |
| 40 | `SCP-06-7005.06` | Control Scope | 7005.6 |
| 41 | `SCH-06-8006.06` | Control Schedule | 8006.6 |
| 42 | `FIN-04-8007.04` | Control Costs | 8007.4 |
| 43 | `GOV-11-8008.03` | Control Quality (CI gates, release verification) | *(missing in v1.12.00)* |
| 44 | `RES-06-8009.06` | Control Resources | 8009.6 |
| 45 | `STK-06-8010.03` | Monitor Communications | 8010.3 |
| 46 | `RSK-07-8011.07` | Monitor Risks | 8011.7 |
| 47 | `GOV-12-8012.03` | Control Procurements | 8012.3 |
| 48 | `STK-07-8013.04` | Monitor Stakeholder Engagement | 8013.4 |
| 49 | `GOV-13-9004.07` | Close Project or Phase | 9004.7 |

Legacy anchors differ in some earlier versions (e.g. band of `SCH-04/05`, `RSK-01`); the registry alias table resolves each variant. Legacy *domain-tag sequence numbers* were re-issued in this edition and are not aliased by tag, only by anchor.

## Appendix B — Legacy → v3 PBIM Section crosswalk

| Earlier form | v3.00.00 |
| --- | --- |
| `RES-03-0004.01` PBIM Document Creation | `PBI-01-0004.00.01` |
| `SCP-04-0004.02` PBIM Document Development | `PBI-02-0004.00.02` PBIM Assurance Baseline |
| Initial Project Template Generation Prompt (v1.12.00 note) | Folded into `PBI-03` Prompt 1 |
| `SCP-03-0004.03` Project Proposal Establishment | `PBI-03-0004.00.03` |
| `SCP-04-0004.04` Project Proposal Development | `PBI-04-0004.00.04` |
| `RES-03-0004.05` Project Template Generation/Creation | `PBI-05-0004.00.05` |
| `SCP-04-0004.06` Project Template Development | `PBI-06-0004.00.06` |
| `GOV-01-0004.07` Project Configuration & Initialization | `PBI-07-0004.00.07` |
| `GOV-02-0004.08` Project Simulation | `PBI-08-0004.00.08` |
| `GOV-02-0004.09` PBIM Implementation | `PBI-09-0004.00.09` PBIM Activation & Charter Readiness |
| `GOV-01-0004.1` Develop Project Charter | `GOV-01-0004.01` |
| v1.11.00 `0004.01–0004.09` (Development, Proposal, Template, Environment, Bootstrap) | Mapped by purpose to `PBI-01`…`PBI-09` |

Legacy identifiers resolve through the registry for 90 days, then are rejected.

## Appendix C — Registers and records

**Finding record**: `id, severity, source agent/role, section, claim, evidence label, evidence reference, attack path or analysis, impact, affected control, proposed disposition, status, residual risk, re-verification required`.

**Task Packet**: `Task Packet ID, Project ID, authority, objective, requirements, scope (direct/generated/dependency/config/schema/infrastructure/external-effect), out-of-scope, target files/resources, dependencies, required tests and evidence, risk profile, acceptance criteria, verification requirements, stop conditions, expected deliverables, expiration, issued revision, executor, approver, verifier, scope manifest, supersession state`.

**Authority–Permission Matrix**: `principal, authority role, technical identity, action, scope, environment, approval authority, independent verifier, expiration`.

**Independence record**: `principal, relationships, permissions, shared credentials, decision rights, incentives, prior authorship, evidence-store sharing, conflicts, dimensions I1–I4 claimed with proof`.

**Control State Register**: `control id, requirement, mechanism, authority, evidence, failure state, recovery path, maturity, last verified, verifier`.

## Appendix D — Open items

| ID | Item | Owner | Needed by |
| --- | --- | --- | --- |
| OI-01 | Read v1.13.00, v1.10.00, v1.05.00, v1.04.00, v1.03.00 in full and merge any unique concepts | Lead Agent | `PBI-01` |
| OI-02 | Re-verify §6.1 standards entries marked "Verify" and fill the LRO | Documentation role | `PBI-01` |
| OI-03 | Confirm whether the earlier R1.5 AEV cycle (which exceeded the x.4 bound) was an `H0`-approved extension or a reset | `H0` | `PBI-02` |
| OI-04 | Reconcile the agents' differing counts of AEC attack domains | Challenge role | `PBI-02` |
| OI-05 | Fresh AEC and AECC on this edition; none is claimed | `PBI-02` roles | `PBI-02` |
| OI-06 | Decide the PMBOK 8 process crosswalk for Appendix A | Lead Agent | `PBI-02` |

---

## Document control

| Field | Value |
| --- | --- |
| Generic version | v3.00.00 |
| Lifecycle boundary | `GOV-01-0004.01` |
| Architecture | Governance → Assurance → Execution |
| Assurance protocol | AEA → AEV → AEC → AECC (parameterized library) |
| Maturity model | DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED |
| Expected-value fields | `EXPECTED-PROJECT-DURATION`, `EXPECTED-PROJECT-START-DATE`, `EXPECTED-PROJECT-END-DATE` |
| Production authorization | Outside PBIM |
| Artifact state | Controlled Candidate pending `PBI-02` |

## END OF PROJECT BASE INTEGRATION MANAGER
