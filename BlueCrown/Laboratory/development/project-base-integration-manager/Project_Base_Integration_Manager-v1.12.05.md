# **PROJECT BASE INTEGRATION MANAGER \[PBIM]**

|Field|Value|
|-|-|
|**Version**|v1.12.05|
|**Status**|**CANDIDATE — NOT AEV/AEC/AECC-APPROVED.** Must pass `\[PROJECT-KEY]\[PBI-02-0004.02]` before it replaces v1.12.00|
|**Supersedes**|Project\_Base\_Integration\_Manager-v1.12.00.md (2026-10-03T00:02:13Z)|
|**Generated**|2026-10-07 (UTC), by `\[PBI-01-0004.01]`|
|**Baseline inputs**|v1.12.00; AEA Query; AEA Reports; AEV Statement R1.5; AEV Responses; AEC Adversarial Duel R1.4 and Results|
|**Authority**|H0 approves adoption. Constitutional changes (Part 4, GM-1) additionally require CA|
|**Maturity of this document**|`DESIGNED` only. Nothing here is evidence that a control operates|

\---

## 0\. READER'S GUIDE

**What PBIM is.** A pre-charter integration framework. It takes a project from "idea" to a signed Project Charter (`GOV-01-0004.01`) through nine controlled steps (`PBI-01` to `PBI-09`), using a Lead Agent, collaborating agents and human authorities. PBIM identifiers **end at the Charter**; from there the project's own template governs.

**How to run it.** Work through Part 8 in order. Every section is a *Section Card*: purpose, inputs, steps, exit gate, stop conditions, and ready-to-paste prompts with their resource slots. Prompts are inline where used (v1.12.00 note 5 retained) but are *generated from one Prompt Library* (Part 7) so they cannot drift.

**What changed from v1.12.00 (summary).**

1. One identifier grammar, one registry, an alias table (Part 3, Appendix B). Retires the `0004.1` / `0004.10` numeric collision and the dual `0000`/`0004` prefixes.
2. Process catalogue corrected: v1.12.00's list is titled "40", described as "49", and contains **48** entries (Control Quality is missing) (D-01). Anchors are kept stable; PMBOK 8 alignment is by domain (Part 5).
3. Pre-charter sections get their own domain tag `PBI` instead of borrowing PM tags that mean something else (D-05).
4. Agent branches renamed: `main/<agent-name>` cannot exist next to `main` in Git (D-11).
5. Approval, revision-bounding and challenge thresholds made objective (Part 7).
6. Governance model (Part 4) aligned with AEV R1.5, with its unverified status stated.
7. Local-first regulatory overlay with a currency gate (Part 5), replacing the single sentence in v1.12.00 note 3.
8. Sections `0004.07`–`0004.09`, previously stubs, now have steps, exit gates and prompts.

**Honest limits of this candidate (read before relying on it).**

* The GitHub viewer truncates long files and raw access was blocked. Read directly: v1.12.00 (complete); AEA Query §§1–16 of 30; AEA Reports first \~1,000 of 1,857 lines (Kilo, Jules, Copilot reports seen); AEV Statement R1.5 §§1–61 of 78+ (later sections, including §§62–78 on consensus, failure handling, resolution matrix and gates, are known only from agent citations); AEV Responses first \~1,000 of 2,961 lines (R1.5 and R1.4 responses seen); AEC Results first \~1,000 of 2,597 lines. **The AEC Adversarial Duel document itself was not opened**; its shape is inferred from agent results. Agents cite its attack domains inconsistently (Kilo: A–AG; Jules: A–AL) — see OPEN-05.
* **R1.5 is not yet unanimously approved as written.** Kilo and Jules issued unconditional AEV APPROVE; GitHub Copilot issued "conditional approval". R1.5 §72 (as cited) says conditional approval is insufficient. This candidate therefore treats R1.5 as the *best available* baseline, not an approved one (OPEN-03).
* Legal and standards statements in Part 5 carry a confidence tag. None is legal advice.

\---

## 1\. PROJECT IDENTITY BLOCK

```
Generic Format : \[KOWARE]\[IAPD]\[PMO]\[BASE-ID]\[PROJECT-ID]\[PBIM]\[SECTION-ID]
PROJECT-KEY    : \[BASE-ID]-\[PROJECT-ID]            e.g. BZJ-PGBD
PROJECT-NAME   : \[PROJECT-FULL-NAME]
PROJECT-BASE   : \[PROJECT-BASE-NAME] \[BASE-ID]
PROJECT-LOCATION : \[TOWN]\[DISTRICT]\[CITY]\[REGION]\[COUNTRY]     (COUNTRY added: needed for the jurisdiction chain, Part 5)
PROJECT-DURATION : computed per Part 5.4 (not typed free-hand)
PROJECT-FOLDER   : \[PROJECT-KEY]
PRODUCTION-REPO-NAME : \[PRODUCTION-REPO-NAME]
```

|Item|Location|
|-|-|
|Base project R\&D folder|`https://github.com/cupidblack/Koware\_Management/tree/main/BlueCrown/Laboratory/development/\[PROJECT-FOLDER]`|
|R\&D docs folder|`…/\[PROJECT-FOLDER]/docs`|
|**Governance folder** (new; authority register, matrix, registry, ledger)|`…/\[PROJECT-FOLDER]/governance`|
|Production repository|`https://github.com/cupidblack/\[PRODUCTION-REPO-NAME]`|

Organisation chain: Koware Group `\[KOWARE]` → IAPD National Department `\[IAPD]` → Project Management Office `\[PMO]`.

### 1.1 Roles (tool-agnostic) and default bindings

v1.12.00 bound authority to product names. Roles are now primary; products are bindings that can change without editing the process.

|Role|Code|Function|Default binding|
|-|-|-|-|
|Constitutional Authority|`CA`|External root of trust; approves constitutional change|Appointed per project (Part 4, GM-1)|
|Human Project Authority|`H0` (+ delegate, `H1` reviewers)|Final sign-off, resets, risk-profile decisions|Named in Authority Register|
|Lead Architect Agent|`LEAD`|Writes queries, synthesises baselines, orchestrates review|ChatGPT Codex|
|Security \& Adversarial Agent|`SEC`|Challenge duels, threat review|Kilo Code / Claude Code|
|Reliability, Verification \& CI Agent|`REL`|Independent verification, tests, CI|Google Jules|
|Implementation Agent|`IMP`|Writes production code under Task Packets|GitHub Copilot|

Rules: no role approves its own output; one human may hold several roles only if Part 4 GM-7 (independence) still passes; every binding change is recorded in the Decision Ledger. "Kilo Code / Claude Code" in v1.12.00 named two different tools as one — record the actual tool used per task.

### 1.2 Branches and documents

|Purpose|Name|
|-|-|
|Agent working branch|`agent/<agent-slug>/<task-id>` (replaces `main/<agent-name>`; Git cannot hold both `main` and `main/x`)|
|Controlled candidates and baselines|`governance/<PROJECT-KEY>` (PR-merged, ruleset-protected)|
|Authoritative reference form|`<path>@<commit-SHA>` plus content hash. Branch names and `blob/<branch>` URLs are *convenience pointers only*|

An agent submission is **non-authoritative** until merged to `governance/<PROJECT-KEY>` and cited by commit SHA.

\---

## 2\. SCOPE AND PRECEDENCE

Order of precedence (higher cannot be weakened by lower): `CA` → protected controls (Part 4) → approved baseline → this document → Charter (after `0004.01`) → repository `AGENTS.md` → subsystem `AGENTS.md` → Task Packet → agent default behaviour.

\---

## 3\. IDENTIFIER GRAMMAR (single, canonical)

```
SECTION-ID = DOMAIN "-" DSEQ "-" ANCHOR            e.g. GOV-01-0004.01   PBI-02-0004.02
ANCHOR     = BAND AREA "." PROC \*("." SUB)
BAND       = 1DIGIT   lifecycle band
AREA       = 3DIGIT   zero-padded PM area / domain number (004 = Integration)
PROC       = 2DIGIT   zero-padded process number; "00" = PBIM pre-process step
SUB        = 2DIGIT | "P" 2DIGIT (prompt) | "A" 2DIGIT (artifact)
DOMAIN     = GOV | STK | SCP | SCH | FIN | RES | RSK | PBI
```

**Rules.**

* **ID-1 Integer segments.** Segments compare as integers, never decimals. This is what removes the v1.12.00 collision where `0004.1` and `0004.10` are numerically equal.
* **ID-2 Zero padding is mandatory.** Old `0004.1` is new `0004.01`. Old `0004.01` (a PBIM step) is new `0004.01`. Lexical order equals lifecycle order.
* **ID-3 Domain meaning is fixed.** Tags `GOV STK SCP SCH FIN RES RSK` match the seven PMBOK 8 performance domains and are used **only** for catalogued processes (Appendix A). PBIM-internal steps use `PBI`.
* **ID-4 Anchors are immutable.** They derive from the stable Koware Process Catalogue, not from a PMI edition number, so a new PMBOK edition never renumbers history.
* **ID-5 Registry-allocated.** Agents never invent IDs. Allocation: `REQUEST → RESERVE → VALIDATE → COMMIT → VERIFY → CONFIRM` in `governance/Identifier-Registry.yaml`. Retired IDs stay as tombstones and are never reused.
* **ID-6 Aliases.** Old IDs resolve through Appendix B for the transition window (Part 6, OP-11).
* **ID-7 Engineering subtitle.** Every heading with a PM process name carries a software-engineering subtitle.
* **ID-8 Verification placement.** PR reviews, code checks, security audits and tests sit in bands 4–6 (executing) or 7–8 (monitoring); never in early planning bands.
* **ID-9 Heading form.** `### \*\*\[\[BASE-ID]-\[PROJECT-ID]]\[DOMAIN-DSEQ-ANCHOR]\*\* — Title — \*engineering subtitle\*`.

**Lifecycle bands.**

|Band|Meaning|
|-|-|
|`0xxx`|Initiating, including PBIM pre-charter (`0004.NN`) and Charter (`0004.01`)|
|`1xxx`–`3xxx`|Planning (1 core plans, 2 resource/cost/quality, 3 risk/procurement/engagement)|
|`4xxx`–`6xxx`|Executing (build, PR review, test, team, comms, procurement)|
|`7xxx`–`8xxx`|Monitoring and controlling (change control, quality, CI gates, scope/schedule/cost control)|
|`9xxx`|Closing|

**Artifact file names.** `\[<PROJECT-KEY>-<ANCHOR>]<SUBJECT>\_<ARTCODE>-<agent-slug>-<UTC yyyymmddThhmmZ>.md`, with YAML front matter (`id`, `rev`, `state`, `sha256`, `supersedes`). `ARTCODE`: `AEA-Q` query, `AEA-R` report, `AEV-S` statement (`R<c>.<r>`), `AEV-D` decision, `AEC-D` duel, `AEC-R` duel result, `AECC` closure. `<ANCHOR>` is the *section's* anchor, so file IDs and section IDs always match (fixes D-07).

**Markers** (kept from v1.12.00, now closed and named): `<<START PROMPT: id | vX.Y | TARGET: role>>…<<STOP PROMPT: id>>`; `<<START RESOURCES: id>>…<<STOP RESOURCES: id>>` holding `{{slots}}`; `<<START RESPONSES: id>>…<<STOP RESPONSES: id>>`.

\---

## 4\. GOVERNANCE OPERATING MODEL

*Sourced from AEV R1.5 (§ numbers where read directly) and the AEA reports. State: `DESIGNED`. Where R1.5 changes after its own review, R1.5's final text wins over this summary.*

**Model:** Governance → Assurance → Execution. Governance sets authority and constraints; Assurance verifies and challenges; Execution performs authorised work. No technical capability, consensus or role label creates authority by itself (R1.5 §3).

|Ref|Control|Requirement|
|-|-|-|
|GM-1|**Constitutional Authority** (R1.5 §§4–9)|CA is an external root of trust; PBIM defines nothing above it. Before `CHARTERED` the project records CA identity, accountable principal, authority source, scope, appointment, review date, succession. No valid CA record → `CONSTITUTIONAL-AUTHORITY-BLOCKED`. CA unavailable → `CONSTITUTIONAL-BLOCKED`; nobody self-assumes CA. CA compromise → `CA-TRUST-BOUNDARY-COMPROMISED`, remediated above PBIM. H0 cannot modify CA.|
|GM-2|**Authority ≠ capability** (§§10–14)|Repo-admin, CI, cloud and deploy rights confer no governance authority. Every privileged account maps to a named role in the Authority–Permission Matrix; mismatch → `AUTHORITY-PERMISSION-DRIFT`. Protected governance resources (baseline, matrix, registry, AEV/AEC/AECC records, ledger, ADRs, Task Packet state, release records) must resist unilateral admin change. Periodic privileged-account audit.|
|GM-3|**Serialised state** (§§15–20)|Matrix and registry change only through `REQUEST→RESERVE→VALIDATE→COMMIT→VERIFY→CONFIRM`. Each revision: ID, parent, hash, authoriser, timestamp, verifier, result. Conflict → `AUTHORITY-MATRIX-CONFLICT`. Registry loss → `REGISTRY-BLOCKED`; no ungoverned emergency registry.|
|GM-4|**Integrity anchors and evidence** (§§21–27)|Anchors must be immutable, out-of-band, independently verifiable, durable, revision-bound; an anchor rewritable from the same trust domain is *not* out-of-band. Evidence keeps source, hash, timestamp, author, verifier, result, provenance; raw evidence stays retrievable; a sanitised report never replaces raw evidence.|
|GM-5|**Maturity states**|`DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED`. *Replaces the 3-state model of v1.12.00.* A document describing a control is never evidence the control runs.|
|GM-6|**Evidence labels**|Every substantive claim: `VERIFIED FACT`, `INFERENCE`, `ASSUMPTION`, `PROPOSAL`, `RISK`, `UNKNOWN`.|
|GM-7|**Independence** (§§28–31)|Verified, not declared: I1 organisational, I2 evidence, I3 technical, I4 governance. Reviewer files an independence record (principal, relationships, permissions, shared credentials, decision rights, incentives, conflicts). Fail → `CHALLENGE-INDEPENDENCE-FAILED`/`CHALLENGE-BLOCKED`. "Same person, different title" is not independence; resource limits delay work, they do not create independence.|
|GM-8|**Task Packets** (§§32–35; v1.12.00 item 7)|Bounded scope: direct, generated, dependency, config, build-artifact, schema, infrastructure, external-effect. Machine-readable scope manifest; CI check `.github/workflows/task-scope-check.yml` compares the actual change set. Out-of-scope → `STOP → REPORT → NEW/SUPERSEDING PACKET`. Packets immutable per cycle.|
|GM-9|**Stop classes** (§§36–38)|`S1` advisory, `S2` mandatory (scope/requirement mismatch), `S3` system (CI/security failure), `S4` emergency safety (financial, data, authority). Machine-enforced where possible (blocks merge, release, dispatch). Resume authority differs from stopper; executors never self-resume.|
|GM-10|**Reset** (§§39–40)|Architectural Reset preserves prior baseline, evidence, reasons and traceability; cannot be used to evade a finding.|
|GM-11|**Emergency delegation** (§§41–43)|Scope-, action-, risk-, time-limited, logged. Default TTL 72 h (a *challengeable default*; CA may tighten), then `STOPPED` unless confirmed. Post-event reconciliation mandatory.|
|GM-12|**Risk profiles** (§§44–47)|`LIGHT`, `STANDARD`, `HIGH-ASSURANCE`. Classification is a governance decision at charter, implementation start, major requirement change, release. Assessed **cumulatively**; implementers cannot downgrade; the higher profile applies during dispute.|
|GM-13|**Operational readiness** (§§48–51)|`IMPLEMENTATION-VERIFIED → OPERATIONALLY-READY → RELEASE-AUTHORIZED`. Checklist (monitoring, alert ownership, rollback, backup/restore, data recovery, incident ownership, dependencies, security, capacity, support, migration recovery, canary). `NOT APPLICABLE` needs justification and approver. Untested rollback or unowned alerts block release.|
|GM-14|**Drift, durability, materiality** (§§52–60)|`BASELINE-DRIFT` monitor outside the execution path. Authoritative references carry commit/object ID + hash. Requirement materiality Levels 0–3 assessed cumulatively; splitting a material change does not defeat review.|
|GM-15|**Self-amendment** (§61)|PBIM cannot authorise its own constitutional amendment; path is `AEA→AEV→AEC→AECC→CA`.|
|GM-16|**Decision records**|ADR = durable architectural decision and rationale. Decision Ledger = operational record of decisions, approvals, state, unresolved items. Locations: `\[PRODUCTION-REPO-NAME]/data/docs/ADR` and `…/ADR/decisions`.|
|GM-17|**Traceability**|Requirement → analysis → decision → verification → implementation → test → approval → release, as linked IDs in the Ledger. A formal matrix is required for `HIGH-ASSURANCE`; lighter profiles may use ledger links.|

**Implementation reality (from AEA reports, dated 2026-10-03).** The reports state that none of ADR/, Decision Ledger, task packets, `.github/skills`, branch conventions, registry or enforcement CI existed in the live repository, and that live `AGENTS.md` contradicted the architecture. R1.5 lists these as implementation-verification obligations. They are **not** claimed here.

**Human-in-the-loop (required, not ceremonial):** initiation, scope approval, architecture approval for `STANDARD`+ projects, security exceptions, production deployment, financial or external commitments, destructive operations, major scope change, closure. H0 review time is a scheduled, capacity-counted resource (Part 5.4).

\---

## 5\. LOCAL-FIRST STANDARDS AND REGULATORY OVERLAY

v1.12.00 required conformance with "local modern" standards but gave no mechanism. This Part is the mechanism.

### 5.1 Jurisdiction chain (local before global)

Resolve in this order and let the **more specific, stricter-for-the-project rule win** unless law says otherwise: `TOWN/DISTRICT` assembly bylaws → `CITY` municipal/metropolitan rules → `REGION` → `COUNTRY` statute and regulator rules → regional bloc (e.g. ECOWAS, AU) → international standards. Record the result in the **Local Regulatory Overlay (LRO) register**.

### 5.2 LRO entry (required fields)

`id`, `instrument`, `authority`, `applies\_to`, `status` (`IN-FORCE`/`PENDING`/`REPEALED`), `obligation`, `project\_impact`, `source\_url`, `last\_verified` (UTC), `verified\_by`, `confidence` (`SEARCHED`/`RECALL-UNVERIFIED`).

**Currency gate.** The LRO must be re-verified at `PBI-01`, `PBI-07`, `GOV-01-0004.01` and at every major baseline, and in any case at most every 90 days. Any `PENDING` instrument that could change a project obligation is tracked as a risk (`RSK`).

### 5.3 Global baseline (applies unless a local rule is stricter)

|Area|Reference|Use in PBIM|
|-|-|-|
|Project management|**PMBOK Guide 8th Ed.** (released Nov 2025; 6 principles, 7 performance domains, 5 focus areas, 40 non-prescriptive processes)|Domains = ID tags. Process crosswalk: OPEN-01|
|Project management|ISO 21502:2020|Secondary cross-check for governance wording|
|Secure development|NIST SSDF (SP 800-218); OWASP ASVS and Top 10|`HIGH-ASSURANCE` and `STANDARD` code gates|
|AI-agent governance|OWASP Top 10 for LLM Applications; NIST AI RMF; ISO/IEC 42001|Agent action logging, human oversight, prompt/tool injection tests|
|Supply chain|SLSA provenance, artifact attestations, signed commits/tags|Durable references, release evidence|
|Payments (if applicable)|PCI DSS v4.x; applicable central-bank rules|Payment/auth/data code is `HIGH-ASSURANCE` by default|
|Records|ADR practice; SemVer; Conventional Commits|Decision and change records|
|Agent instructions|`AGENTS.md` open convention|Repository-level agent directives, below protected controls|

### 5.4 Duration and capacity rule (replaces the free-text `PROJECT-DURATION`)

`human\_capacity\_hours/week = Σ (person × min(local\_statutory\_weekly\_cap, contracted\_hours)) − leave − public holidays`. Agent compute is a separate, budgeted resource. **H0 and reviewer hours are capacity-counted** because verification cycles (Part 7) are the usual bottleneck. Record the cap and its source in the LRO.

### 5.5 Reference overlay — projects located in Ghana

*Included because `\[DISTRICT]`/`\[REGION]` is a Ghanaian location schema; confirm applicability for the project. Tags: **\[S]** = searched 2026-10-07, <b>\[R]</b> = recall, unverified. Verify every row before relying on it.*

|Instrument|Status / note|Project impact|Tag|
|-|-|-|-|
|Labour Act, 2003 (Act 651)|In force. Max 8 h/day and 40 h/week, overtime rules apply; secondary sources disagree on section numbers and overtime rates. A **Labour Bill to replace it is under development**|Sets §5.4 cap; track the Bill as a risk|\[S]|
|Data Protection Act, 2012 (Act 843); Data Protection Commission|In force. Registration of controllers; cross-border and cloud processing duties; breach notification (secondary sources report 72 h — verify). A **Data Protection Bill, 2025 would repeal it** and create an Authority|Privacy-by-design now; design for regulatory change|\[S]|
|Cybersecurity Act, 2020 (Act 1038); Cyber Security Authority|In force. Incident reporting (secondary source reports 24 h — verify), service-provider licensing, critical information infrastructure. **Cybersecurity (Amendment) Bill, 2025 proposed**|Incident runbooks, `S3`/`S4` stop classes tie to reporting duties|\[S]|
|Copyright Act, 2005 (Act 690)|Closed-list fair dealing; no TDM exception reported|Licensing review for agent training/reference material|\[S]|
|ECOWAS Supplementary Act A/SA.1/01/10 on data protection|Regional baseline|Cross-border data flows|\[S]|
|Payment Systems and Services Act, 2019 (Act 987); Bank of Ghana directives|Applies to payment projects|Licensing, security, reporting|\[R]|
|Electronic Transactions Act, 2008 (Act 772)|E-signature and e-records validity|Charter and approval records|\[R]|
|Public Procurement Act, 2003 (Act 663) as amended|Applies to public-sector work|`GOV-03`/`GOV-08`|\[R]|

\---

## 6\. TRANSITION FROM OUTDATED PRACTICE

Each row gives old practice, replacement, and a feasible path. "Window" = maximum period both forms are accepted; aliases resolve via Appendix B.

|ID|Old (v1.12.00)|New (v1.12.05)|Transition path|
|-|-|-|-|
|OP-01|"40 processes" / "49 processes" / 48 listed|Koware Process Catalogue: 49 stable anchors + PMBOK 8 domains|Add Control Quality; keep anchors; fill PMBOK 8 crosswalk (OPEN-01). No file renamed|
|OP-02|3-state maturity|4-state, adds `INDEPENDENTLY VERIFIED`|Existing `ENFORCED` claims re-labelled `ENFORCED (unverified)` until independent check|
|OP-03|`main/<agent-name>` branches|`agent/<slug>/<task-id>`|None existed (AEA reports); update `AGENTS.md` and prompts|
|OP-04|Branch protection by habit|Repository **rulesets** + CODEOWNERS on governance paths|Enable on `governance/<KEY>` first, then production repo|
|OP-05|Long-lived personal tokens for agents|Per-agent identities (GitHub App/bot), short-lived OIDC credentials, least privilege|Inventory tokens; rotate; map in the Matrix|
|OP-06|Links to `blob/<branch>`, ephemeral session branches|`path@commit-SHA` + hash|Re-pin all baseline links when promoting a candidate|
|OP-07|Unanimous agent approval|Role-based: no open BLOCKER, required roles APPROVE, minority blockers preserved; consensus is not proof|Apply from next AEV cycle|
|OP-08|"≥90% of challenges passed"|90% is a *floor*; zero open BLOCKER/MATERIAL is the gate|Apply from next AEC cycle|
|OP-09|Product-named authority|Roles with bindings (§1.1)|Edit roster only|
|OP-10|Three copies of the 8-prompt cycle|Prompt Library; inline instances stamped `vX.Y`|Regenerate sections; diff-check|
|OP-11|`0004.1` vs `0004.10`, `0000.0x`/`0004.0x`, retired `0004.10/.11`|Single grammar (§3)|Accept old IDs for 90 days via alias table, then registry rejects them|
|OP-12|Loose `.txt`/`.md` with ad hoc names|Front-matter + hash + document states: `Draft → Analysis → Controlled Candidate → Verification → Approved → Superseded → Archived`|Convert on next revision|
|OP-13|Compliance by single sentence|LRO register + currency gate|Build LRO at `PBI-01`|
|OP-14|Revision bound ambiguous|Defined in §7.5|OPEN-02 for current cycle|
|OP-15|`AGENTS.md` unreconciled with architecture|Precedence in §2; drift monitor|Conformance is an implementation-verification item|
|OP-16|No AI-specific controls|Agent action logging, injection testing, human oversight (§5.3)|Add to Task Packet schema|

**Defect register for v1.12.00 (inconsistencies found and where fixed).**

|ID|Defect|Fixed in|
|-|-|-|
|D-01|Process count 40 / 49 / 48 listed; Control Quality missing|Appendix A|
|D-02|`SCP-05`/`SCP-06` tags inverted against anchors (`7005.5` Validate tagged SCP-06; `7005.6` Control tagged SCP-05)|Appendix A (swapped)|
|D-03|Terminal anchor written `GOV-09-9004.7`; list says `GOV-12-9004.7`|§3, Appendix A|
|D-04|`0004.1` equals `0004.10` numerically; retired IDs still present; two prefixes (`0000.0x`/`0004.0x`)|§3|
|D-05|Domain tags misused: `RES-03` (Acquire Resources) for PBIM creation; `GOV-02` (Develop PM Plan) for `0004.08`/`0004.09`|`PBI` tag|
|D-06|Section IDs (`0004.02`) differ from artifact IDs (`0004.01`) in file names and prompts|§3 file names|
|D-07|Copy-paste errors in `0004.04`/`0004.06`: Proposal AEA prompt links the PBIM document; Template cycle cites `0004.04.01` and "Project\_Proposal"; Template final decision uses `PROPOSAL APPROVE`; PBIM Prompt 8 links AEC results instead of the created document|Part 7 generator|
|D-08|Empty `<>` markers; `<<START>>` without matching `<<STOP>>`|§3 markers|
|D-09|`0004.07`–`0004.09` lack steps, inputs, exit gates; `0004.08`/`0004.09` are one-line stubs|Part 8|
|D-10|`main/<agent>` branch scheme unimplementable in Git|§1.2|
|D-11|Revision bound "no revisions beyond x.4" vs AEV Statement reaching R1.5|§7.5, OPEN-02|
|D-12|"Unanimous approval" vs R1.5 "unconditional" and a conditional Copilot response|§7.3, OPEN-03|
|D-13|Typos altering meaning ("retying", "ony", "byrunning") and "Cove" unexplained|Rewritten|
|D-14|`PROJECT-DURATION` asks for hours with no formula or source|§5.4|
|D-15|Agent attack-domain counts differ between agents (A–AG vs A–AL)|OPEN-05|
|D-16|Skills path `.git/skills/…` is Git's internal directory and is never committed|`PBI-07` item 10|

\---

## 7\. AE CYCLE PROTOCOL AND PROMPT LIBRARY

The same four-artifact cycle verifies three artifacts: the PBIM document (`PBI-02`), the Project Proposal (`PBI-04`) and the Project Template (`PBI-06`). It is defined once here; Part 8 instantiates it.

### 7.1 Stages and artifacts

|Stage|Artifact|Author|Reviewers|
|-|-|-|-|
|AEA|Query (`AEA-Q`), Reports (`AEA-R`)|LEAD issues; all collaborators answer independently|—|
|AEV|Statement (`AEV-S R<c>.<r>`), Decisions (`AEV-D`)|LEAD synthesises|REL, SEC, IMP (+H0 for `HIGH-ASSURANCE`)|
|AEC|Duel (`AEC-D`), Results (`AEC-R`)|SEC prepares; challengers answer|Independent per GM-7|
|AECC|Closure|LEAD + SEC|H0 approves|

### 7.2 Vocabulary

* **AEV decision:** `AEV APPROVE` (unconditional) · `AEV APPROVE WITH CONDITIONS` · `AEV RETURN` · `AEV BLOCK` · `ARCHITECTURAL RESET`.
* **AEC verdict:** `AEC PASS` · `AEC PASS WITH AMENDMENTS` · `AEC BLOCKED`.
* **Finding severity (unified):** `BLOCKER` (also "BLOCKING") · `MATERIAL` (also "MATERIAL RISK") · `MINOR` · `OBSERVATION`.
* **Final artifact decision:** `<CODE> APPROVE` · `<CODE> APPROVE WITH CONDITIONS` · `<CODE> RETURN` · `<CODE> BLOCK` · `ARCHITECTURAL RESET`, with `<CODE>` = `PBIM` | `PROPOSAL` | `TEMPLATE`.

### 7.3 Gates (objective)

1. **AEV → AEC:** every required reviewer issues `AEV APPROVE` *unconditional*; no open `BLOCKER`. A conditional approval is a `RETURN` until its conditions are written into a new revision and re-approved.
2. **AEC → AECC:** zero open `BLOCKER` and zero open `MATERIAL`; each challenger scored ≥ 90% *and* independence record passes; minority `BLOCKER`s stay open however many agents disagree.
3. **AECC → approval:** material architectural change after an AEC requires a fresh AEV and a fresh AEC on the amended artifact.
4. **Consensus is not proof.** Agreement of agents never substitutes for evidence or authority.

### 7.4 Profile tailoring

|Profile|AEA/AEV|AEC|Independence|
|-|-|-|-|
|`LIGHT`|One combined review by REL; H0 decides|Checklist only|Epistemic acceptable|
|`STANDARD`|Full AEA then AEV|One independent challenger|I1 or I3|
|`HIGH-ASSURANCE`|Full, all collaborators|Independent challengers, evidence path separate|I1–I4; external reviewer if the team cannot supply it|

Defaults: PBIM document → `HIGH-ASSURANCE`; Proposal and Template → `STANDARD`, raised if the project touches payment, authentication or personal data (GM-12).

### 7.5 Revision bounding

Within one cycle, AEV revisions are `R<c>.0` to `R<c>.4`. If `R<c>.4` is not unanimously approved, **Architectural Reset**: compile all records, open cycle `R<c+1>.0`. *Open question:* the current PBIM cycle reached `R1.5`, which exceeds this bound as written in v1.12.00 (OPEN-02). H0 must either rule R1.5 a legitimate extension recorded in the Ledger or declare the reset.

### 7.6 Prompt anatomy (every prompt in this document)

`TARGET` (role) · `OBJECTIVE` · `INPUTS` (durable refs only) · `TASK` (numbered) · `OUTPUT` (file name, location, front matter) · `DECISION VOCABULARY` · `EVIDENCE LABELS` · `STOP CONDITIONS` · `DO NOT`. Prompts are versioned; changing one bumps its version and the section's `prompt\_set\_version`.

### 7.7 Prompt Library

The eight cycle prompts (`P01`–`P08`) are defined once in the generator and instantiated in `PBI-02`, `PBI-04`, `PBI-06` with artifact-specific names, file IDs and decision codes. Index: Appendix C. Edit the library, regenerate, and diff; never edit an instance by hand.

\---

## 8\. PROJECT BASE INTEGRATION INITIALIZATION — SECTIONS

Run in order. A section may not start until the previous section's exit gate is recorded in the Decision Ledger. Every prompt below is `v1.0` of the candidate; resource slots `{{…}}` must be filled with **durable references** (`path@commit-SHA`).

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-01-0004.01]** — PBIM Document Creation — *generate or refresh the generic PBIM*

*(was `\[RES-03-0004.01]`)*

|Card||
|-|-|
|**Purpose**|Build or refresh the abstract, generic PBIM. Run for first creation and for maintenance (after a standards-currency review, an Architectural Reset, or a changed role binding). Not run per project.|
|**Profile**|`HIGH-ASSURANCE`|
|**Inputs**|The seven reference documents (R1–R7 below); current LRO register|
|**Outputs**|Candidate PBIM `vX.Y.Z` with change log, defect register, alias table; state `Controlled Candidate`|
|**Exit gate**|Candidate committed to `governance/<PROJECT-KEY>`; passes `PBI-02`|
|**Stop if**|Any reference is missing or only available as a branch/session link; a reference cannot be read in full (record the coverage limit); LRO older than 90 days|

**Resource manifest (fill before prompting).**

<<START RESOURCES: P01>>

* R1 Current PBIM document — {{path@SHA}}
* R2 AEA Query — {{path@SHA}}
* R3 AEA Reports (all agents) — {{path@SHA}}
* R4 AEV Statements (all revisions) — {{path@SHA}}
* R5 AEV Responses (all agents, all revisions) — {{path@SHA}}
* R6 AEC Adversarial Duel documents (all versions) — {{path@SHA}}
* R7 AEC Results (all agents) — {{path@SHA}}
* R8 Local Regulatory Overlay register — {{path@SHA}}
<<STOP RESOURCES: P01>>

<<START PROMPT: P01 | v1.0 | TARGET: LEAD and all collaborating agents>>
**OBJECTIVE.** Produce a new generic PBIM version from R1–R8, keeping the structure and requirements of R1 and correcting its defects.

**TASK.**

1. *Identifiers.* Inventory every section that carries an identifier. For each: confirm the domain tag matches its content, the anchor matches its lifecycle stage, and the heading carries an engineering subtitle. Emit one grammar, one registry rule set and an old→new alias table. Do not invent IDs; propose them for registry allocation.
2. *Prompts.* Review each prompt for target role, inputs, output location, decision vocabulary, stop conditions. Rewrite so an implementer gets a usable response on first try. Generate repeated cycles from one prompt library; never hand-copy.
3. *Currency.* Check every process, practice and standard against current editions, **local jurisdiction first** (resolve `\[PROJECT-LOCATION]` through the LRO, then regional, then global). For each outdated practice, give the replacement and a feasible transition path with a window.
4. *Consistency.* List contradictions between R1 and R2–R7 (counts, tags, ranges, thresholds, roles) and resolve or escalate each as an Open Item. Carry R4's controls forward at maturity `DESIGNED` and say so.
5. *Boundary.* PBIM identifiers end at the Project Charter (`GOV-01-0004.01`).

**OUTPUT.** `\[<PROJECT-KEY>-0004.01]PBIM\_Document-<agent-slug>-<UTC>.md` with front matter; defect register; open items.

**LABELS.** Mark every claim `VERIFIED FACT | INFERENCE | ASSUMPTION | PROPOSAL | RISK | UNKNOWN`. State which parts of R1–R7 you could not read.

**STOP** and ask H0 if: a reference is unreadable, two references conflict on authority, or a change would alter a protected control (that needs CA).

**DO NOT** reproduce project-specific examples as generic rules; claim any control is implemented.
<<STOP PROMPT: P01>>

<<START RESPONSES: P01>>
{{links to agent-generated PBIM candidates}}
<<STOP RESPONSES: P01>>

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-02-0004.02]** — PBIM Document Development — *architectural verification of the PBIM itself*

*(was `\[SCP-04-0004.02]`)*

Run after `PBI-01` or during maintenance. Subject: the candidate from `PBI-01`. Profile: `HIGH-ASSURANCE`.

|Card||
|-|-|
|**Purpose**|Verify the PBIM Document through AEA → AEV → AEC → AECC before it is relied on. Run after `PBI-01-0004.01` or as maintenance|
|**Profile**|`HIGH-ASSURANCE` default (§7.4)|
|**Subject**|The PBIM Document produced by `PBI-01-0004.01`, cited as `path@commit-SHA`|
|**Exit gate**|Final decision `PBIM APPROVE` (or `APPROVE WITH CONDITIONS` accepted by H0); gates in §7.3 met; AECC filed|
|**Stop if**|Subject reference is a branch/session link; a reviewer fails independence (GM-7); revision bound reached (§7.5)|

State ladder: `Draft → Analysis → Controlled Candidate → Verification → Approved → Superseded → Archived`. Dissent and conditions are preserved in each record.

**PBI-02-0004.02.P01 — Generate the AEA Query**

<<START RESOURCES: PBI-02-0004.02.P01>>

* R1 Subject PBIM Document (the artifact under analysis) — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P01>>

<<START PROMPT: PBI-02-0004.02.P01 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Turn the PBIM Document into an Architectural Engineering Analysis Query that collaborators can answer independently.
**TASK.** 1) Separate generic rules from project-specific examples. 2) State problem, current state, desired outcome, constraints, existing decisions, dependencies, risks, unknowns. 3) Ask numbered questions: a core set for every agent plus a specialist set per role (security, reliability, implementation). 4) Require evidence labels and the Finding record (Appendix D). 5) State the decision vocabulary (§7.2) and verification criteria.
**OUTPUT.** `\[<PROJECT-KEY>-0004.02]PBIM\_AEA-Q-<agent-slug>-<UTC>.md`, state `Analysis Request — not an approved architecture`, committed to the docs folder; reply with `path@SHA`.
**STOP** if the subject is unreadable or the ID is not registry-allocated. **DO NOT** propose the answer inside the question.
<<STOP PROMPT: PBI-02-0004.02.P01>>

<<START RESPONSES: PBI-02-0004.02.P01>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P01>>

**PBI-02-0004.02.P02 — Answer the AEA Query independently**

<<START RESOURCES: PBI-02-0004.02.P02>>

* R1 AEA Query — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P02>>

<<START PROMPT: PBI-02-0004.02.P02 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP)>>
**OBJECTIVE.** Analyse the PBIM Document from your role and report independently.
**TASK.** 1) Answer every core question and your specialist questions. 2) Label each claim (`VERIFIED FACT/INFERENCE/ASSUMPTION/PROPOSAL/RISK/UNKNOWN`); tie VERIFIED FACTs to `path@SHA`. 3) List what is sound, incomplete, contradictory, ambiguous, over-complex. 4) Record findings with severity. 5) State what you could not read.
**OUTPUT.** `\[<PROJECT-KEY>-0004.02]PBIM\_AEA-R-<agent-slug>-<UTC>.md` on `agent/<slug>/<task-id>`.
**STOP** if an answer would need evidence you cannot obtain; mark it `UNKNOWN`. **DO NOT** read other agents' reports first; do not copy the Lead's framing.
<<STOP PROMPT: PBI-02-0004.02.P02>>

<<START RESPONSES: PBI-02-0004.02.P02>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P02>>

**PBI-02-0004.02.P03 — Synthesise the AEV Statement**

<<START RESOURCES: PBI-02-0004.02.P03>>

* R1 All AEA Reports (every agent) — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P03>>

<<START PROMPT: PBI-02-0004.02.P03 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Produce a controlled baseline-candidate Statement from the AEA Reports.
**TASK.** 1) Reconcile reports; for each contradiction cite both sides and decide or escalate. 2) Preserve dissent verbatim in a dissent section. 3) State which controls are `DESIGNED` only. 4) Give the revision label `R<c>.<r>`, `supersedes`, and a resolution table mapping every finding to a section. 5) State gates (§7.3).
**OUTPUT.** `\[<PROJECT-KEY>-0004.02]PBIM\_AEV-S-<agent-slug>-<UTC>.md`, state `Controlled Candidate`; implementation authorisation `NOT GRANTED`.
**STOP** if a MATERIAL or BLOCKER finding cannot be resolved or escalated. **DO NOT** drop a finding because only one agent raised it.
<<STOP PROMPT: PBI-02-0004.02.P03>>

<<START RESPONSES: PBI-02-0004.02.P03>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P03>>

**PBI-02-0004.02.P04 — Decide on the AEV Statement**

<<START RESOURCES: PBI-02-0004.02.P04>>

* R1 AEV Statement (latest revision) — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P04>>

<<START PROMPT: PBI-02-0004.02.P04 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP)>>
**OBJECTIVE.** Review the Statement and issue one decision.
**TASK.** 1) Check that each of your earlier findings is *actually* resolved in the cited section, not just listed as resolved. 2) Look for regressions and new defects. 3) Decide: `AEV APPROVE` | `AEV APPROVE WITH CONDITIONS` | `AEV RETURN` | `AEV BLOCK` | `ARCHITECTURAL RESET`. 4) For any decision other than unconditional approve, give a concrete fix per issue. 5) File your independence record (GM-7).
**OUTPUT.** `\[<PROJECT-KEY>-0004.02]PBIM\_AEV-D-<agent-slug>-<UTC>.md`. Begin with the decision on its own line.
**STOP** if you cannot read the whole Statement; say what is missing. **DO NOT** approve conditionally and call it approval; conditional is a `RETURN` until revised.
<<STOP PROMPT: PBI-02-0004.02.P04>>

<<START RESPONSES: PBI-02-0004.02.P04>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P04>>

**PBI-02-0004.02.P05 — Process AEV decisions; revise or issue the AEC Duel**

<<START RESOURCES: PBI-02-0004.02.P05>>

* R1 All AEV Decisions — {{path@SHA}}
* R2 Current AEV Statement — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P05>>

<<START PROMPT: PBI-02-0004.02.P05 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Close the AEV loop and, only if gates are met, prepare the challenge.
**TASK.** 1) Tabulate decisions. 2) If any is not unconditional approve: resolve blocking issues, issue the next revision (`R<c>.<r+1>`) and return to P04. If the revision would exceed `R<c>.4`, stop and request H0's ruling or an Architectural Reset (§7.5). 3) If all are unconditional approve and no BLOCKER is open: instruct SEC to prepare the Duel to **attack** the architecture (assumptions, authority, evidence, scope, stop/reset, drift, cost, migration, rollback). 4) Require independent challengers per profile.
**OUTPUT.** Next AEV revision, or `\[<PROJECT-KEY>-0004.02]PBIM\_AEC-D-<agent-slug>-<UTC>.md` (SEC authors; LEAD reviews for coverage).
**STOP** if independence cannot be established for the profile. **DO NOT** start the Duel on a conditional approval.
<<STOP PROMPT: PBI-02-0004.02.P05>>

<<START RESPONSES: PBI-02-0004.02.P05>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P05>>

**PBI-02-0004.02.P06 — Complete the AEC Adversarial Duel**

<<START RESOURCES: PBI-02-0004.02.P06>>

* R1 AEC Duel — {{path@SHA}}
* R2 Approved AEV Statement — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P06>>

<<START PROMPT: PBI-02-0004.02.P06 | v1.0 | TARGET: Independent challengers (SEC and others per profile)>>
**OBJECTIVE.** Try to break the PBIM Document architecture rather than refine it.
**TASK.** 1) File your independence record first; if independence fails, stop. 2) Attack every domain, scenario and load-bearing assumption in the Duel; re-measure live facts yourself. 3) Record each finding (Appendix D) with the attack path and consequence. 4) Score pass rate. 5) Verdict: `AEC PASS` | `AEC PASS WITH AMENDMENTS` | `AEC BLOCKED`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.02]PBIM\_AEC-R-<agent-slug>-<UTC>.md`.
**STOP** and flag `S3` if you find a live control failure. **DO NOT** copy earlier findings or the Statement's own resolution table; do not soften severity to reach a pass.
<<STOP PROMPT: PBI-02-0004.02.P06>>

<<START RESPONSES: PBI-02-0004.02.P06>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P06>>

**PBI-02-0004.02.P07 — Process Duel results; amend or close (AECC)**

<<START RESOURCES: PBI-02-0004.02.P07>>

* R1 All AEC Results — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P07>>

<<START PROMPT: PBI-02-0004.02.P07 | v1.0 | TARGET: LEAD and SEC>>
**OBJECTIVE.** Resolve findings and either amend or close.
**TASK.** 1) Merge findings; preserve minority BLOCKERs and MATERIALs. 2) For each, decide: fix in the Statement, accept with H0 sign-off, or escalate to CA if constitutional. 3) If the amendment is material, issue a new Statement and repeat P04–P06 (a fresh AEV and a fresh AEC). 4) If gates in §7.3 are met, write the AECC and the updated PBIM Document.
**OUTPUT.** Amended Statement, or `\[<PROJECT-KEY>-0004.02]PBIM\_AECC-<agent-slug>-<UTC>.md` plus the updated PBIM Document `path@SHA`.
**STOP** if any BLOCKER/MATERIAL is open. **DO NOT** close on a vote.
<<STOP PROMPT: PBI-02-0004.02.P07>>

<<START RESPONSES: PBI-02-0004.02.P07>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P07>>

**PBI-02-0004.02.P08 — Approve the finished artifact**

<<START RESOURCES: PBI-02-0004.02.P08>>

* R1 Updated artifact — {{path@SHA}}
* R2 AECC — {{path@SHA}}
<<STOP RESOURCES: PBI-02-0004.02.P08>>

<<START PROMPT: PBI-02-0004.02.P08 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP) and H0>>
**OBJECTIVE.** Give a final decision on the updated PBIM Document.
**TASK.** 1) Confirm every AECC condition is reflected in the artifact. 2) Decide: `PBIM APPROVE` | `PBIM APPROVE WITH CONDITIONS` | `PBIM RETURN` | `PBIM BLOCK` | `ARCHITECTURAL RESET`. 3) Give details and fixes for anything other than `PBIM APPROVE`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.02]PBIM\_AEV-D-<agent-slug>-<UTC>.md` (final), decision on the first line; H0 signs in the Ledger.
**STOP** if the artifact link is not `path@SHA`. **DO NOT** use another artifact's decision codes.
<<STOP PROMPT: PBI-02-0004.02.P08>>

<<START RESPONSES: PBI-02-0004.02.P08>>
{{links to responses}}
<<STOP RESPONSES: PBI-02-0004.02.P08>>



\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-03-0004.03]** — Project Proposal Establishment — *turn a rough idea into a fundable, buildable proposal*

*(was `\[SCP-03-0004.03]`)*

|Card||
|-|-|
|**Purpose**|Convert a raw draft proposal into an established proposal that can be shown to investors/sponsors and drives template generation|
|**Profile**|`STANDARD` (raise per GM-12)|
|**Inputs**|R1 draft proposal (problem, idea, environment, solutions, requirements, deliverables); R2 other references; R3 approved PBIM; R4 LRO|
|**Outputs**|Established Proposal (detailed) + on-request Summary version + **variable set** (below)|
|**Exit gate**|Passes `PBI-04` and H0 approves|
|**Stop if**|A required variable is missing — ask for it; do not generate around gaps|

**Required variable set** (the proposal must define each): `PROJECT-NAME`, `PROJECT-KEY` (`BASE-ID`, `PROJECT-ID`), `PROJECT-LOCATION` (incl. country), `PROJECT-FOLDER`, `PRODUCTION-REPO-NAME`, computed `PROJECT-DURATION` (§5.4), problems, explanations, candidate solutions, requirements, deliverables, proposed risk profile, funding/sponsorship ask, and the **template-generation prompt** for `PBI-05`.

<<START RESOURCES: P03>>

* R1 Draft proposal (rename to `\[<PROJECT-KEY>-0004.03]…`; the v1.12.00 file used `0004.02` for this) — {{path@SHA}}
* R2 Other reference documents — {{path@SHA}}
* R3 Approved PBIM — {{path@SHA}}
* R4 LRO register — {{path@SHA}}
<<STOP RESOURCES: P03>>

<<START PROMPT: P03 | v1.0 | TARGET: LEAD and all collaborating agents>>
**OBJECTIVE.** Produce an established Project Proposal from R1, complete and clear enough to pass `PBI-04`.

**TASK.**

1. List every required variable; for each missing one, **ask the human** and wait. Do not regenerate until answered.
2. Break each vague description into concrete steps, then restate it as a brief, clear description.
3. Place content under the proposal's lifecycle focus areas (anything about the end of the project goes under closing; risks under risk; etc.) with identifiers per §3.
4. Apply the LRO: name the governing local laws, standards and approvals for `\[PROJECT-LOCATION]` first; mark each as in-force or pending.
5. Include a clear **expression-of-interest path** for investors/sponsors (who to contact, what to send, what happens next). Interest leads to a scheduled **charter meeting** where deliverables and procedures are reviewed before the Charter is approved.
6. Provide the full detailed proposal now and offer a one-page summary on request.
7. Review and improve any prompts inside R1 using the §7.6 anatomy.

**OUTPUT.** `\[<PROJECT-KEY>-0004.03]Proposal\_Established-<agent-slug>-<UTC>.md`.

**LABELS.** Evidence labels on every factual claim, especially market, cost and legal claims.

**STOP** if you would have to invent a number, a legal citation or a stakeholder.

**DO NOT** present estimates as quotes, or promise returns to investors.
<<STOP PROMPT: P03>>

<<START RESPONSES: P03>>
{{links to established proposal drafts}}
<<STOP RESPONSES: P03>>

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-04-0004.04]** — Project Proposal Development — *architectural verification of the proposal*

*(was `\[SCP-04-0004.04]`)*

Subject: the Established Proposal from `PBI-03`. Profile: `STANDARD` unless raised.

|Card||
|-|-|
|**Purpose**|Verify the Project Proposal through AEA → AEV → AEC → AECC before it is relied on. Run after `PBI-03-0004.03` or as maintenance|
|**Profile**|`STANDARD` default (§7.4)|
|**Subject**|The Project Proposal produced by `PBI-03-0004.03`, cited as `path@commit-SHA`|
|**Exit gate**|Final decision `PROPOSAL APPROVE` (or `APPROVE WITH CONDITIONS` accepted by H0); gates in §7.3 met; AECC filed|
|**Stop if**|Subject reference is a branch/session link; a reviewer fails independence (GM-7); revision bound reached (§7.5)|

State ladder: `Draft → Analysis → Controlled Candidate → Verification → Approved → Superseded → Archived`. Dissent and conditions are preserved in each record.

**PBI-04-0004.04.P01 — Generate the AEA Query**

<<START RESOURCES: PBI-04-0004.04.P01>>

* R1 Subject Project Proposal (the artifact under analysis) — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P01>>

<<START PROMPT: PBI-04-0004.04.P01 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Turn the Project Proposal into an Architectural Engineering Analysis Query that collaborators can answer independently.
**TASK.** 1) Separate generic rules from project-specific examples. 2) State problem, current state, desired outcome, constraints, existing decisions, dependencies, risks, unknowns. 3) Ask numbered questions: a core set for every agent plus a specialist set per role (security, reliability, implementation). 4) Require evidence labels and the Finding record (Appendix D). 5) State the decision vocabulary (§7.2) and verification criteria.
**OUTPUT.** `\[<PROJECT-KEY>-0004.04]Proposal\_AEA-Q-<agent-slug>-<UTC>.md`, state `Analysis Request — not an approved architecture`, committed to the docs folder; reply with `path@SHA`.
**STOP** if the subject is unreadable or the ID is not registry-allocated. **DO NOT** propose the answer inside the question.
<<STOP PROMPT: PBI-04-0004.04.P01>>

<<START RESPONSES: PBI-04-0004.04.P01>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P01>>

**PBI-04-0004.04.P02 — Answer the AEA Query independently**

<<START RESOURCES: PBI-04-0004.04.P02>>

* R1 AEA Query — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P02>>

<<START PROMPT: PBI-04-0004.04.P02 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP)>>
**OBJECTIVE.** Analyse the Project Proposal from your role and report independently.
**TASK.** 1) Answer every core question and your specialist questions. 2) Label each claim (`VERIFIED FACT/INFERENCE/ASSUMPTION/PROPOSAL/RISK/UNKNOWN`); tie VERIFIED FACTs to `path@SHA`. 3) List what is sound, incomplete, contradictory, ambiguous, over-complex. 4) Record findings with severity. 5) State what you could not read.
**OUTPUT.** `\[<PROJECT-KEY>-0004.04]Proposal\_AEA-R-<agent-slug>-<UTC>.md` on `agent/<slug>/<task-id>`.
**STOP** if an answer would need evidence you cannot obtain; mark it `UNKNOWN`. **DO NOT** read other agents' reports first; do not copy the Lead's framing.
<<STOP PROMPT: PBI-04-0004.04.P02>>

<<START RESPONSES: PBI-04-0004.04.P02>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P02>>

**PBI-04-0004.04.P03 — Synthesise the AEV Statement**

<<START RESOURCES: PBI-04-0004.04.P03>>

* R1 All AEA Reports (every agent) — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P03>>

<<START PROMPT: PBI-04-0004.04.P03 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Produce a controlled baseline-candidate Statement from the AEA Reports.
**TASK.** 1) Reconcile reports; for each contradiction cite both sides and decide or escalate. 2) Preserve dissent verbatim in a dissent section. 3) State which controls are `DESIGNED` only. 4) Give the revision label `R<c>.<r>`, `supersedes`, and a resolution table mapping every finding to a section. 5) State gates (§7.3).
**OUTPUT.** `\[<PROJECT-KEY>-0004.04]Proposal\_AEV-S-<agent-slug>-<UTC>.md`, state `Controlled Candidate`; implementation authorisation `NOT GRANTED`.
**STOP** if a MATERIAL or BLOCKER finding cannot be resolved or escalated. **DO NOT** drop a finding because only one agent raised it.
<<STOP PROMPT: PBI-04-0004.04.P03>>

<<START RESPONSES: PBI-04-0004.04.P03>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P03>>

**PBI-04-0004.04.P04 — Decide on the AEV Statement**

<<START RESOURCES: PBI-04-0004.04.P04>>

* R1 AEV Statement (latest revision) — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P04>>

<<START PROMPT: PBI-04-0004.04.P04 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP)>>
**OBJECTIVE.** Review the Statement and issue one decision.
**TASK.** 1) Check that each of your earlier findings is *actually* resolved in the cited section, not just listed as resolved. 2) Look for regressions and new defects. 3) Decide: `AEV APPROVE` | `AEV APPROVE WITH CONDITIONS` | `AEV RETURN` | `AEV BLOCK` | `ARCHITECTURAL RESET`. 4) For any decision other than unconditional approve, give a concrete fix per issue. 5) File your independence record (GM-7).
**OUTPUT.** `\[<PROJECT-KEY>-0004.04]Proposal\_AEV-D-<agent-slug>-<UTC>.md`. Begin with the decision on its own line.
**STOP** if you cannot read the whole Statement; say what is missing. **DO NOT** approve conditionally and call it approval; conditional is a `RETURN` until revised.
<<STOP PROMPT: PBI-04-0004.04.P04>>

<<START RESPONSES: PBI-04-0004.04.P04>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P04>>

**PBI-04-0004.04.P05 — Process AEV decisions; revise or issue the AEC Duel**

<<START RESOURCES: PBI-04-0004.04.P05>>

* R1 All AEV Decisions — {{path@SHA}}
* R2 Current AEV Statement — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P05>>

<<START PROMPT: PBI-04-0004.04.P05 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Close the AEV loop and, only if gates are met, prepare the challenge.
**TASK.** 1) Tabulate decisions. 2) If any is not unconditional approve: resolve blocking issues, issue the next revision (`R<c>.<r+1>`) and return to P04. If the revision would exceed `R<c>.4`, stop and request H0's ruling or an Architectural Reset (§7.5). 3) If all are unconditional approve and no BLOCKER is open: instruct SEC to prepare the Duel to **attack** the architecture (assumptions, authority, evidence, scope, stop/reset, drift, cost, migration, rollback). 4) Require independent challengers per profile.
**OUTPUT.** Next AEV revision, or `\[<PROJECT-KEY>-0004.04]Proposal\_AEC-D-<agent-slug>-<UTC>.md` (SEC authors; LEAD reviews for coverage).
**STOP** if independence cannot be established for the profile. **DO NOT** start the Duel on a conditional approval.
<<STOP PROMPT: PBI-04-0004.04.P05>>

<<START RESPONSES: PBI-04-0004.04.P05>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P05>>

**PBI-04-0004.04.P06 — Complete the AEC Adversarial Duel**

<<START RESOURCES: PBI-04-0004.04.P06>>

* R1 AEC Duel — {{path@SHA}}
* R2 Approved AEV Statement — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P06>>

<<START PROMPT: PBI-04-0004.04.P06 | v1.0 | TARGET: Independent challengers (SEC and others per profile)>>
**OBJECTIVE.** Try to break the Project Proposal architecture rather than refine it.
**TASK.** 1) File your independence record first; if independence fails, stop. 2) Attack every domain, scenario and load-bearing assumption in the Duel; re-measure live facts yourself. 3) Record each finding (Appendix D) with the attack path and consequence. 4) Score pass rate. 5) Verdict: `AEC PASS` | `AEC PASS WITH AMENDMENTS` | `AEC BLOCKED`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.04]Proposal\_AEC-R-<agent-slug>-<UTC>.md`.
**STOP** and flag `S3` if you find a live control failure. **DO NOT** copy earlier findings or the Statement's own resolution table; do not soften severity to reach a pass.
<<STOP PROMPT: PBI-04-0004.04.P06>>

<<START RESPONSES: PBI-04-0004.04.P06>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P06>>

**PBI-04-0004.04.P07 — Process Duel results; amend or close (AECC)**

<<START RESOURCES: PBI-04-0004.04.P07>>

* R1 All AEC Results — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P07>>

<<START PROMPT: PBI-04-0004.04.P07 | v1.0 | TARGET: LEAD and SEC>>
**OBJECTIVE.** Resolve findings and either amend or close.
**TASK.** 1) Merge findings; preserve minority BLOCKERs and MATERIALs. 2) For each, decide: fix in the Statement, accept with H0 sign-off, or escalate to CA if constitutional. 3) If the amendment is material, issue a new Statement and repeat P04–P06 (a fresh AEV and a fresh AEC). 4) If gates in §7.3 are met, write the AECC and the updated Project Proposal.
**OUTPUT.** Amended Statement, or `\[<PROJECT-KEY>-0004.04]Proposal\_AECC-<agent-slug>-<UTC>.md` plus the updated Project Proposal `path@SHA`.
**STOP** if any BLOCKER/MATERIAL is open. **DO NOT** close on a vote.
<<STOP PROMPT: PBI-04-0004.04.P07>>

<<START RESPONSES: PBI-04-0004.04.P07>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P07>>

**PBI-04-0004.04.P08 — Approve the finished artifact**

<<START RESOURCES: PBI-04-0004.04.P08>>

* R1 Updated artifact — {{path@SHA}}
* R2 AECC — {{path@SHA}}
<<STOP RESOURCES: PBI-04-0004.04.P08>>

<<START PROMPT: PBI-04-0004.04.P08 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP) and H0>>
**OBJECTIVE.** Give a final decision on the updated Project Proposal.
**TASK.** 1) Confirm every AECC condition is reflected in the artifact. 2) Decide: `PROPOSAL APPROVE` | `PROPOSAL APPROVE WITH CONDITIONS` | `PROPOSAL RETURN` | `PROPOSAL BLOCK` | `ARCHITECTURAL RESET`. 3) Give details and fixes for anything other than `PROPOSAL APPROVE`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.04]Proposal\_AEV-D-<agent-slug>-<UTC>.md` (final), decision on the first line; H0 signs in the Ledger.
**STOP** if the artifact link is not `path@SHA`. **DO NOT** use another artifact's decision codes.
<<STOP PROMPT: PBI-04-0004.04.P08>>

<<START RESPONSES: PBI-04-0004.04.P08>>
{{links to responses}}
<<STOP RESPONSES: PBI-04-0004.04.P08>>



\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-05-0004.05]** — Project Template Generation — *build the project-specific operating template*

*(was `\[RES-03-0004.05]`)*

|Card||
|-|-|
|**Purpose**|Produce the custom Project Template (the plan skeleton that runs from the Charter to closure) from the approved proposal|
|**Profile**|`STANDARD` (raise per GM-12)|
|**Inputs**|R1 approved Proposal; R2 prior successful project documents; R3 approved PBIM; R4 LRO|
|**Outputs**|Project Template; primary variables; configuration settings; summary on request|
|**Exit gate**|Passes `PBI-06`; reviewed with sponsors at the charter meeting|
|**Stop if**|Variables missing; the proposal contradicts the LRO; a catalogue anchor would have to be invented|

<<START RESOURCES: P05>>

* R1 Approved Proposal — {{path@SHA}}   *(v1.12.00 pointed both R1 and R2 at the same draft file; keep them distinct)*
* R2 Prior successful project documents — {{path@SHA}}
* R3 Approved PBIM — {{path@SHA}}
* R4 LRO register — {{path@SHA}}
<<STOP RESOURCES: P05>>

<<START PROMPT: P05 | v1.0 | TARGET: LEAD and all collaborating agents>>
**OBJECTIVE.** Generate an implementation-ready Project Template for the approved proposal.

**TASK.**

1. Cover the full lifecycle using the Appendix A catalogue: every anchor is either instantiated, marked `NOT APPLICABLE` with justification, or `DEFERRED` with an owner and date. No empty stubs.
2. Define primary variables and configuration (name, base, key, timeline from §5.4, risk profile, role bindings, repos, branch scheme, LRO summary). Ask for any missing item before regenerating.
3. Place every item under the section that matches its lifecycle role (closing content in closing).
4. Break complex steps into ordered sub-steps; give each section inputs, outputs, exit gate and, where agents are used, prompts per §7.6.
5. Review the template's existing prompts and improve them; keep notes, prompts and resource slots inline in the section where they are used.
6. Check against local-first current practice via the LRO; for each outdated practice give the replacement and a transition path.
7. Write for sponsor review; offer a summary version on request.

**OUTPUT.** `\[<PROJECT-KEY>-0004.05]Project\_Template-<agent-slug>-<UTC>.md`.

**LABELS.** Evidence labels on claims; flag every assumption.

**STOP** if the template would require authority not in the Authority Register.

**DO NOT** treat the template's approval as the Charter's approval; the Template is approved at the charter meeting.
<<STOP PROMPT: P05>>

<<START RESPONSES: P05>>
{{links to template drafts}}
<<STOP RESPONSES: P05>>

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-06-0004.06]** — Project Template Development — *architectural verification of the template*

*(was `\[SCP-04-0004.06]`)*

Subject: the Project Template from `PBI-05`. Profile: `STANDARD` unless raised.

|Card||
|-|-|
|**Purpose**|Verify the Project Template through AEA → AEV → AEC → AECC before it is relied on. Run after `PBI-05-0004.05` or as maintenance|
|**Profile**|`STANDARD` default (§7.4)|
|**Subject**|The Project Template produced by `PBI-05-0004.05`, cited as `path@commit-SHA`|
|**Exit gate**|Final decision `TEMPLATE APPROVE` (or `APPROVE WITH CONDITIONS` accepted by H0); gates in §7.3 met; AECC filed|
|**Stop if**|Subject reference is a branch/session link; a reviewer fails independence (GM-7); revision bound reached (§7.5)|

State ladder: `Draft → Analysis → Controlled Candidate → Verification → Approved → Superseded → Archived`. Dissent and conditions are preserved in each record.

**PBI-06-0004.06.P01 — Generate the AEA Query**

<<START RESOURCES: PBI-06-0004.06.P01>>

* R1 Subject Project Template (the artifact under analysis) — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P01>>

<<START PROMPT: PBI-06-0004.06.P01 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Turn the Project Template into an Architectural Engineering Analysis Query that collaborators can answer independently.
**TASK.** 1) Separate generic rules from project-specific examples. 2) State problem, current state, desired outcome, constraints, existing decisions, dependencies, risks, unknowns. 3) Ask numbered questions: a core set for every agent plus a specialist set per role (security, reliability, implementation). 4) Require evidence labels and the Finding record (Appendix D). 5) State the decision vocabulary (§7.2) and verification criteria.
**OUTPUT.** `\[<PROJECT-KEY>-0004.06]Template\_AEA-Q-<agent-slug>-<UTC>.md`, state `Analysis Request — not an approved architecture`, committed to the docs folder; reply with `path@SHA`.
**STOP** if the subject is unreadable or the ID is not registry-allocated. **DO NOT** propose the answer inside the question.
<<STOP PROMPT: PBI-06-0004.06.P01>>

<<START RESPONSES: PBI-06-0004.06.P01>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P01>>

**PBI-06-0004.06.P02 — Answer the AEA Query independently**

<<START RESOURCES: PBI-06-0004.06.P02>>

* R1 AEA Query — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P02>>

<<START PROMPT: PBI-06-0004.06.P02 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP)>>
**OBJECTIVE.** Analyse the Project Template from your role and report independently.
**TASK.** 1) Answer every core question and your specialist questions. 2) Label each claim (`VERIFIED FACT/INFERENCE/ASSUMPTION/PROPOSAL/RISK/UNKNOWN`); tie VERIFIED FACTs to `path@SHA`. 3) List what is sound, incomplete, contradictory, ambiguous, over-complex. 4) Record findings with severity. 5) State what you could not read.
**OUTPUT.** `\[<PROJECT-KEY>-0004.06]Template\_AEA-R-<agent-slug>-<UTC>.md` on `agent/<slug>/<task-id>`.
**STOP** if an answer would need evidence you cannot obtain; mark it `UNKNOWN`. **DO NOT** read other agents' reports first; do not copy the Lead's framing.
<<STOP PROMPT: PBI-06-0004.06.P02>>

<<START RESPONSES: PBI-06-0004.06.P02>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P02>>

**PBI-06-0004.06.P03 — Synthesise the AEV Statement**

<<START RESOURCES: PBI-06-0004.06.P03>>

* R1 All AEA Reports (every agent) — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P03>>

<<START PROMPT: PBI-06-0004.06.P03 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Produce a controlled baseline-candidate Statement from the AEA Reports.
**TASK.** 1) Reconcile reports; for each contradiction cite both sides and decide or escalate. 2) Preserve dissent verbatim in a dissent section. 3) State which controls are `DESIGNED` only. 4) Give the revision label `R<c>.<r>`, `supersedes`, and a resolution table mapping every finding to a section. 5) State gates (§7.3).
**OUTPUT.** `\[<PROJECT-KEY>-0004.06]Template\_AEV-S-<agent-slug>-<UTC>.md`, state `Controlled Candidate`; implementation authorisation `NOT GRANTED`.
**STOP** if a MATERIAL or BLOCKER finding cannot be resolved or escalated. **DO NOT** drop a finding because only one agent raised it.
<<STOP PROMPT: PBI-06-0004.06.P03>>

<<START RESPONSES: PBI-06-0004.06.P03>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P03>>

**PBI-06-0004.06.P04 — Decide on the AEV Statement**

<<START RESOURCES: PBI-06-0004.06.P04>>

* R1 AEV Statement (latest revision) — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P04>>

<<START PROMPT: PBI-06-0004.06.P04 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP)>>
**OBJECTIVE.** Review the Statement and issue one decision.
**TASK.** 1) Check that each of your earlier findings is *actually* resolved in the cited section, not just listed as resolved. 2) Look for regressions and new defects. 3) Decide: `AEV APPROVE` | `AEV APPROVE WITH CONDITIONS` | `AEV RETURN` | `AEV BLOCK` | `ARCHITECTURAL RESET`. 4) For any decision other than unconditional approve, give a concrete fix per issue. 5) File your independence record (GM-7).
**OUTPUT.** `\[<PROJECT-KEY>-0004.06]Template\_AEV-D-<agent-slug>-<UTC>.md`. Begin with the decision on its own line.
**STOP** if you cannot read the whole Statement; say what is missing. **DO NOT** approve conditionally and call it approval; conditional is a `RETURN` until revised.
<<STOP PROMPT: PBI-06-0004.06.P04>>

<<START RESPONSES: PBI-06-0004.06.P04>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P04>>

**PBI-06-0004.06.P05 — Process AEV decisions; revise or issue the AEC Duel**

<<START RESOURCES: PBI-06-0004.06.P05>>

* R1 All AEV Decisions — {{path@SHA}}
* R2 Current AEV Statement — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P05>>

<<START PROMPT: PBI-06-0004.06.P05 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Close the AEV loop and, only if gates are met, prepare the challenge.
**TASK.** 1) Tabulate decisions. 2) If any is not unconditional approve: resolve blocking issues, issue the next revision (`R<c>.<r+1>`) and return to P04. If the revision would exceed `R<c>.4`, stop and request H0's ruling or an Architectural Reset (§7.5). 3) If all are unconditional approve and no BLOCKER is open: instruct SEC to prepare the Duel to **attack** the architecture (assumptions, authority, evidence, scope, stop/reset, drift, cost, migration, rollback). 4) Require independent challengers per profile.
**OUTPUT.** Next AEV revision, or `\[<PROJECT-KEY>-0004.06]Template\_AEC-D-<agent-slug>-<UTC>.md` (SEC authors; LEAD reviews for coverage).
**STOP** if independence cannot be established for the profile. **DO NOT** start the Duel on a conditional approval.
<<STOP PROMPT: PBI-06-0004.06.P05>>

<<START RESPONSES: PBI-06-0004.06.P05>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P05>>

**PBI-06-0004.06.P06 — Complete the AEC Adversarial Duel**

<<START RESOURCES: PBI-06-0004.06.P06>>

* R1 AEC Duel — {{path@SHA}}
* R2 Approved AEV Statement — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P06>>

<<START PROMPT: PBI-06-0004.06.P06 | v1.0 | TARGET: Independent challengers (SEC and others per profile)>>
**OBJECTIVE.** Try to break the Project Template architecture rather than refine it.
**TASK.** 1) File your independence record first; if independence fails, stop. 2) Attack every domain, scenario and load-bearing assumption in the Duel; re-measure live facts yourself. 3) Record each finding (Appendix D) with the attack path and consequence. 4) Score pass rate. 5) Verdict: `AEC PASS` | `AEC PASS WITH AMENDMENTS` | `AEC BLOCKED`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.06]Template\_AEC-R-<agent-slug>-<UTC>.md`.
**STOP** and flag `S3` if you find a live control failure. **DO NOT** copy earlier findings or the Statement's own resolution table; do not soften severity to reach a pass.
<<STOP PROMPT: PBI-06-0004.06.P06>>

<<START RESPONSES: PBI-06-0004.06.P06>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P06>>

**PBI-06-0004.06.P07 — Process Duel results; amend or close (AECC)**

<<START RESOURCES: PBI-06-0004.06.P07>>

* R1 All AEC Results — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P07>>

<<START PROMPT: PBI-06-0004.06.P07 | v1.0 | TARGET: LEAD and SEC>>
**OBJECTIVE.** Resolve findings and either amend or close.
**TASK.** 1) Merge findings; preserve minority BLOCKERs and MATERIALs. 2) For each, decide: fix in the Statement, accept with H0 sign-off, or escalate to CA if constitutional. 3) If the amendment is material, issue a new Statement and repeat P04–P06 (a fresh AEV and a fresh AEC). 4) If gates in §7.3 are met, write the AECC and the updated Project Template.
**OUTPUT.** Amended Statement, or `\[<PROJECT-KEY>-0004.06]Template\_AECC-<agent-slug>-<UTC>.md` plus the updated Project Template `path@SHA`.
**STOP** if any BLOCKER/MATERIAL is open. **DO NOT** close on a vote.
<<STOP PROMPT: PBI-06-0004.06.P07>>

<<START RESPONSES: PBI-06-0004.06.P07>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P07>>

**PBI-06-0004.06.P08 — Approve the finished artifact**

<<START RESOURCES: PBI-06-0004.06.P08>>

* R1 Updated artifact — {{path@SHA}}
* R2 AECC — {{path@SHA}}
<<STOP RESOURCES: PBI-06-0004.06.P08>>

<<START PROMPT: PBI-06-0004.06.P08 | v1.0 | TARGET: Collaborating agents (REL, SEC, IMP) and H0>>
**OBJECTIVE.** Give a final decision on the updated Project Template.
**TASK.** 1) Confirm every AECC condition is reflected in the artifact. 2) Decide: `TEMPLATE APPROVE` | `TEMPLATE APPROVE WITH CONDITIONS` | `TEMPLATE RETURN` | `TEMPLATE BLOCK` | `ARCHITECTURAL RESET`. 3) Give details and fixes for anything other than `TEMPLATE APPROVE`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.06]Template\_AEV-D-<agent-slug>-<UTC>.md` (final), decision on the first line; H0 signs in the Ledger.
**STOP** if the artifact link is not `path@SHA`. **DO NOT** use another artifact's decision codes.
<<STOP PROMPT: PBI-06-0004.06.P08>>

<<START RESPONSES: PBI-06-0004.06.P08>>
{{links to responses}}
<<STOP RESPONSES: PBI-06-0004.06.P08>>



\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-07-0004.07]** — Project Configuration \& Initialization — *stand up and verify the governance substrate*

*(was `\[GOV-01-0004.07]`)*

|Card||
|-|-|
|**Purpose**|Create the repositories, registers, rulesets, agent identities and gates that the approved Template assumes|
|**Profile**|Set by the recorded risk classification|
|**Inputs**|Approved PBIM, Proposal, Template; LRO; role bindings|
|**Outputs**|Configuration Plan; control-by-control **maturity record**; Authority Register; Matrix; registry|
|**Exit gate**|Every `M` control ≥ `ENFORCEABLE` (≥ `ENFORCED` for blocking gates under `HIGH-ASSURANCE`), verified by REL, signed by H0|
|**Stop if**|CA or H0 record absent; a control cannot be verified; secrets appear in any prompt, log or document|

Legend: **M** mandatory, **C** conditional (state the reason when skipped). "Done-evidence" is what REL checks.

|#|Control|Done-evidence|`LIGHT`|`STANDARD`|`HIGH`|
|-|-|-|-|-|-|
|1|GitHub Project `\[PROJECT-FULL-NAME]` with board and the `PROJECT-KEY` prefix|Project URL; key recorded in registry|M|M|M|
|2|Folders/repos: R\&D, docs, **governance**, production|Paths exist; default branches protected|M|M|M|
|3|Identifier registry bootstrapped (§3 ID-5)|`Identifier-Registry.yaml` with parent hash; serialised write path|C|M|M|
|4|CA record (GM-1)|Signed record; review date|C|M|M|
|5|Authority Register: H0, delegate, H1, appointment authority, expiry|Register commit; H0 appointed by someone other than H0|M|M|M|
|6|Authority–Permission Matrix + privileged-account audit (GM-2)|Matrix revision; audit log; no unmapped admin|C|M|M|
|7|Repository **rulesets** + CODEOWNERS on governance and production; force-push denied; workflow changes need review|Ruleset export|M|M|M|
|8|Per-agent identities, least privilege, short-lived credentials (OP-05)|Identity list mapped to roles|M|M|M|
|9|`AGENTS.md` conformance to §2 across affected repos. *Example (BZJ-PGBD):* `buzzjuice.net`, `wp-content`, `wp-content/mu-plugins`, `streams`, `social`, `shared`, `data`. Code is the physical source of truth; `AGENTS.md` carries durable agent directives|Drift report = clean|M|M|M|
|10|Project skills/instructions in a **tracked** path, `.github/skills/\[PROJECT-NAME]` (v1.12.00 also named `.git/skills/…`, which is Git's internal directory and is never committed — D-16)|Files visible in `git ls-files`|C|M|M|
|11|ADR and Decision Ledger locations (GM-16)|Paths exist; first entry made|C|M|M|
|12|Task Packet schema (Appendix D) + `task-scope-check.yml`|Test PR with out-of-scope file is blocked|C|M|M|
|13|Challenge-before-build gate|Gate listed as required status check|C|M|M|
|14|Stop classes `S1–S4`, resume authority, machine-enforced where possible|Dry-run stop blocks merge|M|M|M|
|15|Risk profile classification record (GM-12)|Signed record|M|M|M|
|16|LRO register verified within 90 days|`last\_verified` dates|M|M|M|
|17|Evidence store, retention, integrity anchor in an independent trust domain (GM-4)|Anchor verification record|C|C|M|
|18|**Secrets boundary:** push protection and secret scanning on; no credentials in prompts, logs or evidence|Scanner enabled; redaction tested|M|M|M|
|19|Operational-readiness checklist template (GM-13)|Template in release path|C|M|M|
|20|Baseline-drift monitor outside the execution path (GM-14)|Monitor alert test|C|C|M|
|21|Maturity record: each control's current state `DESIGNED/ENFORCEABLE/ENFORCED/INDEPENDENTLY VERIFIED`|Table committed|M|M|M|

<<START RESOURCES: P07>>

* R1 Approved PBIM / Proposal / Template — {{path@SHA}}
* R2 Risk classification record — {{path@SHA}}
* R3 LRO register — {{path@SHA}}
* R4 Current repository settings export — {{path@SHA}}
<<STOP RESOURCES: P07>>

<<START PROMPT: P07a | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Produce the Configuration Plan for the table above, for the recorded risk profile.
**TASK.** 1) For each control, state applicable `M/C`, the exact change to make, the owner role, and the done-evidence. 2) Order steps by dependency (CA/H0 and registry before rulesets before agents). 3) List what needs a human (H0/CA/admin) and what agents may do. 4) Mark every control `DESIGNED` until evidence exists.
**OUTPUT.** `\[<PROJECT-KEY>-0004.07]Configuration\_Plan-<agent-slug>-<UTC>.md`.
**STOP** if a step needs admin rights that no recorded authority holds.
**DO NOT** apply any change; this prompt plans only.
<<STOP PROMPT: P07a>>

<<START PROMPT: P07b | v1.0 | TARGET: REL (independent of whoever applied the changes)>>
**OBJECTIVE.** Verify each configured control against its done-evidence.
**TASK.** For each control run or inspect the evidence, record `PASS/FAIL/NOT TESTABLE`, the evidence reference (`path@SHA`) and the resulting maturity state. Try to defeat the control once (e.g. open a PR touching a file outside a Task Packet).
**OUTPUT.** `\[<PROJECT-KEY>-0004.07]Verification\_Record-<agent-slug>-<UTC>.md`; independence record per GM-7.
**STOP** and report `S3` if a mandatory control fails.
**DO NOT** fix what you verify.
<<STOP PROMPT: P07b>>

<<START RESPONSES: P07>>
{{links to plan, verification record, H0 sign-off}}
<<STOP RESPONSES: P07>>

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-08-0004.08]** — Project Simulation — *dry run before real money and real code*

*(was `\[GOV-02-0004.08]`; v1.12.00 held one sentence)*

|Card||
|-|-|
|**Purpose**|Prove the configured process works end to end on a harmless task, and that it stops when it should|
|**Profile**|Same as the project|
|**Inputs**|Configured substrate (`PBI-07`); a deliberately trivial Task Packet; sandbox repository or branch|
|**Outputs**|Simulation Report: timings, decisions, failures, fixes|
|**Exit gate**|All required drills produced the expected state; capacity (§5.4) shown realistic; H0 signs|
|**Stop if**|A drill reaches production resources; any real credential is used|

**Walkthrough scope (from v1.12.00, now itemised):** team briefing and roles; change-management path (request → impact → decision → baseline update); template operations and preview; contractor requirements, tendering and procurement steps (apply the local procurement rules from the LRO); timeline and deliverables; project after-life (handover, support, archival).

**Required drills** (each must trigger the stated state):

|Drill|Expected result|
|-|-|
|Agent edits a file outside its Task Packet|Out-of-scope check blocks; `STOP → REPORT → NEW PACKET`|
|H0 unreachable during a decision|Delegate acts only within scope; constitutional change `BLOCKED`|
|Two concurrent registry/matrix changes|Serialised; stale one rejected; conflict state raised|
|Reviewer shares credentials with author|`CHALLENGE-INDEPENDENCE-FAILED`|
|Emergency delegation passes 72 h without confirmation|`STOPPED`|
|Authoritative link points to a deleted branch|Rejected as non-durable|
|A simulated `S4` event|Immediate halt and human escalation|

<<START PROMPT: P08 | v1.0 | TARGET: REL (runs), LEAD (designs), H0 (observes)>>
**OBJECTIVE.** Run the simulation in the sandbox and report whether each drill produced the expected state.
**TASK.** 1) LEAD designs the trivial Task Packet and drill scripts; H0 approves them. 2) REL executes, recording timestamps and evidence for each drill. 3) Measure elapsed time per gate and human hours used. 4) List every divergence and propose a fix; do not apply it.
**OUTPUT.** `\[<PROJECT-KEY>-0004.08]Simulation\_Report-<agent-slug>-<UTC>.md`.
**STOP** if any drill touches production resources or real credentials.
**DO NOT** mark a drill passed without evidence.
<<STOP PROMPT: P08>>

<<START RESPONSES: P08>>
{{links to simulation report}}
<<STOP RESPONSES: P08>>

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[PBI-09-0004.09]** — PBIM Implementation — *activate PBIM for the live project*

*(was `\[GOV-02-0004.09]`; v1.12.00 held one sentence)*

|Card||
|-|-|
|**Purpose**|Formally switch PBIM controls on and hand the project to the Charter step|
|**Inputs**|Approved PBIM, Proposal, Template; verified configuration; simulation report; LRO ≤ 90 days old|
|**Outputs**|**Activation Record**; baseline tag `pbim-baseline/<version>@<commit-SHA>`; charter-meeting invitation|
|**Exit gate**|H0 (and CA where GM-1 applies) sign; every checklist line is true|
|**Stop if**|Any line is untrue — do not activate on a promise|

**Activation checklist:** (1) `PBIM APPROVE` recorded; (2) `PROPOSAL APPROVE`; (3) `TEMPLATE APPROVE`; (4) `PBI-07` exit gate met; (5) `PBI-08` exit gate met; (6) CA and H0 records current; (7) risk profile signed; (8) LRO verified; (9) open items listed with owners; (10) maturity record states which controls are *only designed*.

<<START PROMPT: P09 | v1.0 | TARGET: LEAD (prepare), REL (verify), H0 (decide)>>
**OBJECTIVE.** Prepare and verify the Activation Record.
**TASK.** 1) LEAD fills the checklist with `path@SHA` evidence for each line. 2) REL independently re-checks each line and marks it `CONFIRMED/UNCONFIRMED`. 3) Summarise residual risk and every `DESIGNED`-only control. 4) Recommend `ACTIVATE` or `HOLD`.
**OUTPUT.** `\[<PROJECT-KEY>-0004.09]Activation\_Record-<agent-slug>-<UTC>.md`.
**STOP** on any `UNCONFIRMED` mandatory line.
**DO NOT** activate; only H0 does.
<<STOP PROMPT: P09>>

<<START RESPONSES: P09>>
{{links to activation record and H0 decision}}
<<STOP RESPONSES: P09>>

\---

### **\[\[BASE-ID]-\[PROJECT-ID]]\[GOV-01-0004.01]** — Initiate Project or Phase — *Develop Project Charter; the PBIM integration boundary*

*(was `\[GOV-01-0004.1]`)*

**PBIM identifiers end here.** After the Charter is approved, work continues through the identifiers in the approved Template: planning (bands 1–3), executing (4–6), monitoring and controlling (7–8), closing `\[GOV-12-9004.07]`. The Charter is the first project document under the project's own template.

**Charter content (minimum):** objectives and success criteria; scope and exclusions; deliverables; milestones and computed duration (§5.4); budget and funding source; stakeholders; assumptions and constraints; risk profile and top risks; CA and H0 identities; local regulatory summary (from the LRO); role bindings; approval signatures.

<<START RESOURCES: P10>>

* R1 Activation Record — {{path@SHA}}
* R2 Approved Proposal and Template — {{path@SHA}}
* R3 Sponsor feedback from the charter meeting — {{path@SHA}}
<<STOP RESOURCES: P10>>

<<START PROMPT: P10 | v1.0 | TARGET: LEAD>>
**OBJECTIVE.** Draft the Project Charter for the charter meeting.
**TASK.** 1) Populate every minimum Charter field from R1–R3; mark gaps `UNKNOWN` and list questions. 2) Reconcile Proposal and Template with sponsor feedback and show differences. 3) Map objectives to template anchors. 4) Prepare the meeting agenda: deliverables, procedures, risk profile, authority, funding.
**OUTPUT.** `\[<PROJECT-KEY>-0004.01]Project\_Charter-<agent-slug>-<UTC>.md`.
**STOP** if CA/H0 are not recorded or sponsors changed scope beyond the approved Proposal (that is a Level 2+ change).
**DO NOT** sign or approve; humans approve the Charter.
<<STOP PROMPT: P10>>

<<START RESPONSES: P10>>
{{links to charter draft, meeting record, signed charter}}
<<STOP RESPONSES: P10>>

\---

## 9\. RUNNING NOTES (carried from v1.12.00, corrected)

1. **Agent ecosystem flexibility.** Role bindings (§1.1) can change; record each change in the Ledger.
2. **Standard pre-charter initialisation.** `PBI-01`…`PBI-09` then `GOV-01-0004.01`.
3. **Tooling.** Linting, schema validation, CI suites, Playwright for front-end verification, secret scanning, SBOM/provenance, and the scope-check workflow are added as controls in `PBI-07`; each starts `DESIGNED`.
4. **Namespace.** Anchors run `0000.00.01` to `9999.99`; terminal anchor is `\[GOV-12-9004.07]`.
5. **Inline notes.** Notes, prompts and resource slots live in the section where used (generated from the Prompt Library).
6. **Stage visibility.** The leading band digit shows the lifecycle stage at a glance.
7. **Subtitles.** Standard PM process names carry an engineering subtitle.
8. **Targets.** Every prompt names its target role.
9. **Re-sequencing.** Reviews, checks and tests sit in bands 4–8 only.
10. **Docs push policy.** Agents push documents to the project `docs/` folder on `agent/<slug>/<task-id>`; candidates are promoted by PR to `governance/<PROJECT-KEY>`.
11. **Production push policy.** Code goes to the production repository on `agent/<slug>/<task-id>`; independent verification and human approval precede merge.

\---

## APPENDIX A — KOWARE PROCESS CATALOGUE (49 stable anchors)

Corrects v1.12.00: adds **Control Quality** (missing), swaps the inverted `SCP-05`/`SCP-06` tags, and states every ID in the §3 grammar. "Legacy" is the v1.12.00 form. PMBOK 8 process crosswalk is OPEN-01 (not filled; PMBOK 8 process names were not available to verify).

|New ID|PM anchor|Process / engineering subtitle|Legacy (v1.12.00)|
|-|-|-|-|
|`GOV-01-0004.01`|4.1|Initiate Project or Phase (Develop Project Charter)|`GOV-01-0004.1`|
|`STK-01-0013.01`|13.1|Identify Stakeholders|`STK-01-0013.1`|
|`GOV-02-1004.02`|4.2|Integrate and Align Project Plans (Develop Project Management Plan)|`GOV-02-1004.2`|
|`SCP-01-1005.01`|5.1|Plan Scope Management|`SCP-01-1005.1`|
|`SCP-02-1005.02`|5.2|Elicit and Analyze Requirements (Collect Requirements)|`SCP-02-1005.2`|
|`SCP-03-1005.03`|5.3|Define Scope|`SCP-03-1005.3`|
|`SCP-04-1005.04`|5.4|Develop Scope Structure (Create WBS)|`SCP-04-1005.4`|
|`SCH-01-1006.01`|6.1|Plan Schedule Management|`SCH-01-1006.1`|
|`SCH-02-1006.02`|6.2|Define Activities|`SCH-02-1006.2`|
|`SCH-03-1006.03`|6.3|Sequence Activities|`SCH-03-1006.3`|
|`SCH-04-1006.04`|6.4|Estimate Activity Durations|`SCH-04-1006.4`|
|`SCH-05-1006.05`|6.5|Develop Schedule|`SCH-05-1006.5`|
|`FIN-01-2007.01`|7.1|Plan Financial Management (Plan Cost Management)|`FIN-01-2007.1`|
|`FIN-02-2007.02`|7.2|Estimate Costs|`FIN-02-2007.2`|
|`FIN-03-2007.03`|7.3|Develop Budget (Determine Budget)|`FIN-03-2007.3`|
|`GOV-05-2008.01`|8.1|Plan Quality (Manage Quality Assurance in v1.12.00)|`GOV-05-2008.1`|
|`RES-01-2009.01`|9.1|Plan Resource Management|`RES-01-2009.1`|
|`RES-02-2009.02`|9.2|Estimate Activity Resources|`RES-02-2009.2`|
|`STK-03-2010.01`|10.1|Plan Communications Management|`STK-03-2010.1`|
|`RSK-01-2011.01`|11.1|Plan Risk Management|`RSK-01-2011.1`|
|`RSK-02-3011.02`|11.2|Identify Risks|`RSK-02-3011.2`|
|`RSK-03-3011.03`|11.3|Perform Qualitative Risk Analysis|`RSK-03-3011.3`|
|`RSK-04-3011.04`|11.4|Perform Quantitative Risk Analysis|`RSK-04-3011.4`|
|`RSK-05-3011.05`|11.5|Plan Risk Responses|`RSK-05-3011.5`|
|`GOV-03-3012.01`|12.1|Plan Sourcing Strategy (Plan Procurement Management)|`GOV-03-3012.1`|
|`STK-02-3013.02`|13.2|Plan Stakeholder Engagement|`STK-02-3013.2`|
|`GOV-04-4004.03`|4.3|Direct and Manage Project Work|`GOV-04-4004.3`|
|`GOV-06-4004.04`|4.4|Manage Project Knowledge|`GOV-06-4004.4`|
|`GOV-07-4008.02`|8.2|Manage Quality|`GOV-07-4008.2`|
|`RES-03-5009.03`|9.3|Acquire Resources|`RES-03-5009.3`|
|`RES-04-6009.04`|9.4|Develop Team|`RES-04-6009.4`|
|`RES-05-6009.05`|9.5|Manage Team|`RES-05-6009.5`|
|`STK-05-6010.02`|10.2|Manage Communications|`STK-05-6010.2`|
|`RSK-06-6011.06`|11.6|Implement Risk Responses|`RSK-06-6011.6`|
|`GOV-08-6012.02`|12.2|Conduct Procurements|`GOV-08-6012.2`|
|`STK-04-6013.03`|13.3|Manage Stakeholder Engagement|`STK-04-6013.3`|
|`GOV-09-7004.05`|4.5|Monitor and Control Project Work|`GOV-09-7004.5`|
|`GOV-10-7004.06`|4.6|Perform Integrated Change Control|`GOV-10-7004.6`|
|`SCP-05-7005.05`|5.5|Validate Scope|`SCP-06-7005.5 (tag inverted)`|
|`SCP-06-7005.06`|5.6|Control Scope|`SCP-05-7005.6 (tag inverted)`|
|`SCH-06-8006.06`|6.6|Control Schedule|`SCH-06-8006.6`|
|`FIN-04-8007.04`|7.4|Control Costs|`FIN-04-8007.4`|
|`GOV-13-8008.03`|8.3|Control Quality (MISSING in v1.12.00)|`— (absent)`|
|`RES-06-8009.06`|9.6|Control Resources|`RES-06-8009.6`|
|`STK-07-8010.03`|10.3|Monitor Communications|`STK-07-8010.3`|
|`RSK-07-8011.07`|11.7|Monitor Risks|`RSK-07-8011.7`|
|`GOV-11-8012.03`|12.3|Control Procurements|`GOV-11-8012.3`|
|`STK-06-8013.04`|13.4|Monitor Stakeholder Engagement|`STK-06-8013.4`|
|`GOV-12-9004.07`|4.7|Close Project or Phase|`GOV-12-9004.7`|

\---

## APPENDIX B — ALIAS AND CROSSWALK (accept for 90 days, then registry rejects)

|Old ID / prefix|New ID|Note|
|-|-|-|
|`\[RES-03-0004.01]`|`\[PBI-01-0004.01]`|PBIM Document Creation|
|`\[SCP-04-0004.02]`|`\[PBI-02-0004.02]`|PBIM Document Development|
|`\[SCP-03-0004.03]`|`\[PBI-03-0004.03]`|Project Proposal Establishment|
|`\[SCP-04-0004.04]`|`\[PBI-04-0004.04]`|Project Proposal Development|
|`\[RES-03-0004.05]`|`\[PBI-05-0004.05]`|Project Template Generation|
|`\[SCP-04-0004.06]`|`\[PBI-06-0004.06]`|Project Template Development|
|`\[GOV-01-0004.07]`|`\[PBI-07-0004.07]`|Configuration \& Initialization|
|`\[GOV-02-0004.08]`|`\[PBI-08-0004.08]`|Project Simulation|
|`\[GOV-02-0004.09]`|`\[PBI-09-0004.09]`|PBIM Implementation|
|`\[GOV-01-0004.1]`|`\[GOV-01-0004.01]`|Charter|
|`0004.10`, `0004.11`, `0000.01`–`0000.09` (as section IDs)|tombstoned|Never reused|
|Artifact prefix `\[BZJ-PGBD-0004.01]` on PBIM AEA/AEV/AEC files|alias of `0004.02` artifacts|Files keep historic names; registry maps them|
|Artifact prefixes `0000.01-R1.4/R1.5` (AEV), `0000.04` (AEC), `0000.05` (AECC), and `PBIID` in file names|alias of `0004.02` artifacts|Seen in agent output file names|
|Branch `main/<agent-name>`|`agent/<slug>/<task-id>`|OP-03|

Seen in the reference set but not in v1.12.00: AEV revision labels `R1.0`–`R1.5`; decision vocabulary `AEC PASS WITH AMENDMENTS`; finding labels `BLOCKING`/`BLOCKER`, `MATERIAL`/`MATERIAL RISK` (unified in §7.2).

\---

## APPENDIX C — PROMPT LIBRARY INDEX

|ID|Target|Purpose|Version|
|-|-|-|-|
|P01|LEAD + all|Generate PBIM (PBI-01)|v1.0|
|P03|LEAD + all|Establish Proposal (PBI-03)|v1.0|
|P05|LEAD + all|Generate Template (PBI-05)|v1.0|
|P07a / P07b|LEAD / REL|Plan / verify configuration (PBI-07)|v1.0|
|P08|REL, LEAD, H0|Simulation (PBI-08)|v1.0|
|P09|LEAD, REL, H0|Activation Record (PBI-09)|v1.0|
|P10|LEAD|Charter draft (GOV-01-0004.01)|v1.0|
|PBI-02-0004.02.P01|LEAD|Generate AEA Query (PBIM Document)|v1.0|
|PBI-02-0004.02.P02|Collaborators|Answer AEA Query (PBIM Document)|v1.0|
|PBI-02-0004.02.P03|LEAD|Synthesise AEV Statement (PBIM Document)|v1.0|
|PBI-02-0004.02.P04|Collaborators|Decide on AEV Statement (PBIM Document)|v1.0|
|PBI-02-0004.02.P05|LEAD|Process AEV decisions / issue Duel (PBIM Document)|v1.0|
|PBI-02-0004.02.P06|Challengers|Complete AEC Duel (PBIM Document)|v1.0|
|PBI-02-0004.02.P07|LEAD + SEC|Process Duel results / AECC (PBIM Document)|v1.0|
|PBI-02-0004.02.P08|Collaborators + H0|Final artifact decision (PBIM Document)|v1.0|
|PBI-04-0004.04.P01|LEAD|Generate AEA Query (Project Proposal)|v1.0|
|PBI-04-0004.04.P02|Collaborators|Answer AEA Query (Project Proposal)|v1.0|
|PBI-04-0004.04.P03|LEAD|Synthesise AEV Statement (Project Proposal)|v1.0|
|PBI-04-0004.04.P04|Collaborators|Decide on AEV Statement (Project Proposal)|v1.0|
|PBI-04-0004.04.P05|LEAD|Process AEV decisions / issue Duel (Project Proposal)|v1.0|
|PBI-04-0004.04.P06|Challengers|Complete AEC Duel (Project Proposal)|v1.0|
|PBI-04-0004.04.P07|LEAD + SEC|Process Duel results / AECC (Project Proposal)|v1.0|
|PBI-04-0004.04.P08|Collaborators + H0|Final artifact decision (Project Proposal)|v1.0|
|PBI-06-0004.06.P01|LEAD|Generate AEA Query (Project Template)|v1.0|
|PBI-06-0004.06.P02|Collaborators|Answer AEA Query (Project Template)|v1.0|
|PBI-06-0004.06.P03|LEAD|Synthesise AEV Statement (Project Template)|v1.0|
|PBI-06-0004.06.P04|Collaborators|Decide on AEV Statement (Project Template)|v1.0|
|PBI-06-0004.06.P05|LEAD|Process AEV decisions / issue Duel (Project Template)|v1.0|
|PBI-06-0004.06.P06|Challengers|Complete AEC Duel (Project Template)|v1.0|
|PBI-06-0004.06.P07|LEAD + SEC|Process Duel results / AECC (Project Template)|v1.0|
|PBI-06-0004.06.P08|Collaborators + H0|Final artifact decision (Project Template)|v1.0|

\---

## APPENDIX D — SCHEMAS (minimum fields)

```yaml
# Task Packet (immutable once issued)
packet\_id: <PROJECT-KEY>-TP-<n>
anchor: <section anchor>            # lifecycle position
objective: ...
assigned\_role: IMP|REL|SEC|LEAD     # binding recorded separately
authorized\_paths: \[...]             # direct scope
generated\_paths: \[...]              # generated files allowed
dependency\_scope: \[...]             # manifests allowed to change
config\_scope: \[...]  schema\_scope: \[...]  infra\_scope: \[...]  external\_effects: none|\[...]
requirements: \[REQ-ids]  decisions: \[ADR-ids]
prohibited: \[...]
verification: \[checks]  acceptance: \[criteria]
output: {branch: agent/<slug>/<task-id>, docs: path}
risk\_profile: LIGHT|STANDARD|HIGH-ASSURANCE
stop\_conditions: \[S1..S4 triggers]  escalation: H0|CA
issued\_by: LEAD  authorised\_by: H0|H1  hash: sha256
```

```yaml
# Authority Register entry
role: CA|H0|H0-DELEGATE|H1
principal: ...  organisation: ...  authority\_source: ...  appointed\_by: <not self>
scope: ...  appointed: date  review\_by: date  succession: ...
conflicts\_declared: \[...]  emergency\_ttl\_hours: 72
```

```yaml
# Finding record
finding\_id: F-<n>   severity: BLOCKER|MATERIAL|MINOR|OBSERVATION
subject: <anchor + section>  label: VERIFIED FACT|INFERENCE|ASSUMPTION|PROPOSAL|RISK|UNKNOWN
evidence: <path@SHA>  attack\_or\_basis: ...  consequence: ...
required\_change: ...  owner: role  state: OPEN|RESOLVED|ACCEPTED-BY-H0  dissent: \[...]
```

```yaml
# Registry revision
revision: <id>  parent: <id>  sha256: ...  authorised\_by: ...
verified\_by: ...  verification\_result: ...  timestamp: UTC
entries: \[{id, title, state: RESERVED|ACTIVE|RETIRED, aliases: \[...]}]
```

\---

## APPENDIX E — OPEN ITEMS FOR H0

|ID|Item|Owner|
|-|-|-|
|OPEN-01|Fill the PMBOK 8 ↔ 49-anchor crosswalk from the licensed text; do not infer process names|H0 supplies text; LEAD maps|
|OPEN-02|Rule on revision bounding: is AEV `R1.5` a recorded extension or a trigger for Architectural Reset (D-11)?|H0|
|OPEN-03|Obtain an unconditional AEV decision from GitHub Copilot on R1.5, or record the conditions in a revision|IMP, then LEAD|
|OPEN-04|Name CA for the generic PBIM itself (distinct from per-project CA). R1.4 reviewer raised this; no answer seen|Koware / IAPD leadership|
|OPEN-05|Open the AEC Adversarial Duel document and reconcile its attack-domain count (agents cite A–AG and A–AL)|LEAD|
|OPEN-06|Re-run `P01` with full, unabridged R2–R7 (this candidate read only the leading portion of the longer files, see §0)|LEAD|
|OPEN-07|Verify every `\[R]` row and section citation in §5.5; confirm the project country; track the Labour, Data Protection and Cybersecurity bills|H0 / legal adviser|
|OPEN-08|Decide the 72-hour emergency TTL default (reviewer called it long) and a policy for CA unavailability during a critical incident (a concern raised in the R1.4 challenge: the block is safe but can deadlock)|CA / H0|

\---

## APPENDIX F — CHANGE LOG

|Version|Date|Change|
|-|-|-|
|v1.12.00|2026-10-03|Prior baseline|
|v1.12.05 (candidate)|2026-10-07|Single identifier grammar; `PBI` tag; corrected catalogue; roles over products; branch scheme; governance model per AEV R1.5; local-first overlay and currency gate; Prompt Library with generated cycles; sections `0004.07`–`0004.09` made executable; transition and defect registers; open items|

*End of document. Status remains CANDIDATE until `\[PBI-02-0004.02]` closes with `PBIM APPROVE` and H0 signature.*

