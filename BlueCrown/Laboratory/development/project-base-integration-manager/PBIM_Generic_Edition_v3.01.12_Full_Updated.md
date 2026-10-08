# PROJECT BASE INTEGRATION MANAGER [PBIM]

| Field | Value |
|---|---|
| Document | Project Base Integration Manager — Generic Edition |
| Version | **v3.01.12** |
| Document Class | Generic Pre-Charter Project Integration, Governance, Assurance and Readiness Framework |
| Status | **CONTROLLED GENERIC CANDIDATE — RETURNED FOR AMENDMENT** |
| Maturity | `DESIGNED` — specification only; implementation is not established by this document |
| Scope | Pre-charter probing, project-framework design, controlled initialization, simulation and Charter readiness |
| PBIM terminal boundary | `PBI-09-0004.00.09` → `H0` decision → `[GOV-01-0004.01]` handoff |
| Implementation authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Production authorization | **NOT GRANTED BY THIS DOCUMENT** |
| Review date | 2026-10-08 |

> This revision is a corrective amendment drafted after independent review. It does not establish any operational registry, workflow, policy engine, or automated enforcement mechanism.

---

# 0. REVIEWED DEFECTS AND REMEDIATION SUMMARY

This version addresses the material issues identified in the independent review of the v3.01.11 candidate:

1. Consolidation claims were expanded from a summary map to a traceable register that records treatment, rationale, owner, evidence requirement, and advancement consequence.
2. Identifier grammar was normalized to a single canonical form and composite identifiers were explicitly prohibited unless they are registered and mapped.
3. Prompt marker consistency was normalized to a single exact start/stop pattern and the resource-block ordering rule was reaffirmed.
4. Human-authority requirements were operationalized via an explicit authority record and a required verification gate before any closure or disposal decision.
5. Timing semantics and stop-state semantics were clarified to preserve evidence and prevent self-clearance.

---

# 1. CONSOLIDATION AND SOURCE-LINEAGE CONTROL

## 1.1 Canonical consolidation register

A source control is considered successfully consolidated only when the following are recorded:

`SOURCE CONTROL → CANONICAL CONTROL → TREATMENT → RATIONALE → OWNER → EVIDENCE REQUIREMENT → ADVANCEMENT CONSEQUENCE`

| Source family / historical concept | Canonical control | Treatment | Rationale | Owner | Evidence requirement | Advancement consequence |
|---|---|---|---|---|---|---|
| Evidence classes, provenance, integrity anchors | §5 Evidence & Traceability | MERGED | Preserves required evidence semantics and provenance chain | Control steward | Immutable identity + hash and source metadata | No advancement without evidence classification |
| Stop, hold, recovery, reset, emergency delegation | §4.6–4.8 Controlled State & Recovery | MERGED | Preserves authority, stop logic, and recovery requirements | H0/H1 authority | Stop record + resume criteria + expiry evidence | Block advancement until stop state is resolved |
| Repeated prompt boilerplate | §7 Universal Prompt Contract | MERGED | Avoids duplicated instructions without changing required semantics | Prompt steward | Prompt contract check | Prompt invalid if contract is not satisfied |
| PBIM section identifiers | §3 Identifier Architecture | NORMALIZED | Canonical grammar replaced ambiguous historical aliases | Registry owner | Registry entry + alias map | No advancement with unresolved alias conflict |
| Project/task/document/lifecycle/PM identities | §3.3 Identifier Taxonomy | MERGED/SEPARATED | Prevents overloaded identity semantics | Registry owner | Unique registry keys | Registry-blocked if duplicate or ambiguous |
| AEA → AEV → AEC → AECC | §8 + prompts | PARAMETERIZED | Preserves stage-specific evidence and challenge semantics | Assurance lead | Complete package and dissent log | Material blockers require re-assurance |
| Authority / permissions / separation of duties | §2 | MERGED | Human authority remains distinct from technical capability | H0/H1 | Authority record and scope matrix | Cannot authorize without verified authority |
| Expected project timing fields | §2 | MERGED | Prevents commitment semantics while preserving schedule logic | Project authority | Calendar basis and timezone evidence | Timing conflict blocks advancement |
| Risk profiles and cumulative materiality | §4.4–4.5 | MERGED | Preserves protected-control floor | Risk owner | Risk profile and materiality assessment | Higher risk requires fresh assurance |
| Durable references | §5.1–5.2 | MERGED | Critical evidence requires immutable identity | Evidence owner | Canonical object + integrity hash | Mutable pointers are insufficient for critical claims |
| Standards currency | §5.5 | CORRECTED | Date-based review required before adoption | Standards owner | Current edition check | Unsupported or stale claims are non-binding |

---

# 2. PROJECT IDENTITY AND EXPECTED TIMING

Complete the identity block before `PBI-01-0004.00.01`.

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
PRODUCTION-AUTHORIZATION     : NOT GRANTED
```

Timing semantics:

- `EXPECTED-*` values are planning estimates, not commitments or authorization.
- `END = START + DURATION` under the declared calendar and working-time convention.
- If the values are not consistent, the timing block is `CONFLICTED` and advancement is blocked.
- Date-only schedules must state whether `END` is inclusive or exclusive.
- Timestamped schedules require explicit timezone or UTC offset.

---

# 3. AUTHORITY, CAPABILITY AND INDEPENDENCE

## 3.1 Human authority record (mandatory)

No prompt may proceed without a human authority record containing, at minimum:

```yaml
human_authority:
  authority_id:
  authority_type: H0 | H1 | H2 | CA
  human_identity:
  scope:
  delegation_basis:
  start_time:
  expiry_time:
  decision_rights:
  prohibited_actions:
  evidence_required:
  verification_record:
  review_interval:
```

If no valid human authority record exists, the prompt is `BLOCKED`.

## 3.2 Capability roles

`LEAD`, `ANL`, `VER`, `SEC`, `IMP`, `TST`, `OPS`, `DOC`, and `CHAL` remain capability roles only; they do not create governance authority.

## 3.3 Independence record

Where independence is required, record:

- independent actor identity;
- independence class;
- distinct evidence/input path;
- conflict-of-interest assessment;
- authority relationship;
- whether the reviewer participated in creating the subject under review;
- residual independence limitations.

A single actor may not self-certify a critical review. If independence cannot be evidenced, the result is `CHALLENGE-BLOCKED`.

---

# 4. IDENTIFIER ARCHITECTURE AND REGISTRY

## 4.1 Canonical grammar

The canonical PBIM section grammar is:

`PBI-[NN]-0004.00.[SS]`

Examples:

- `PBI-01-0004.00.01`
- `PBI-09-0004.00.09`
- `GOV-01-0004.01`

## 4.2 Prohibited composite identifiers

Composite forms such as `[PROJECT-KEY]::[PBI-01-0004.00.01]` are not canonical identifiers unless they are explicitly registered as a derived project-scoped alias with an unambiguous mapping and a unique registry record.

## 4.3 Registry minimum fields

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
legacy_aliases:
integrity_reference:
authority:
grammar_version:
issuer:
retirement_state:
```

Uniqueness constraints:

```text
UNIQUE(project_id, identifier_type, identifier)
UNIQUE(project_id, identifier_type, sequence_index) where applicable
```

The registry must reject duplicate identifiers, invalid grammar, missing mandatory fields, illegal lifecycle transitions, and conflicting authoritative revisions.

---

# 5. EVIDENCE, STATE, RISK AND CONTROL MODEL

## 5.1 Evidence classes

`VERIFIED FACT`, `INFERENCE`, `ASSUMPTION`, `PROPOSAL`, `RECOMMENDATION`, `RISK`, `UNKNOWN`, `DISPUTED`.

Evidence does not become stronger by consensus. Unknown facts remain `UNKNOWN` until supported by evidence.

## 5.2 Control maturity

`DESIGNED → ENFORCEABLE → ENFORCED → INDEPENDENTLY VERIFIED`

Documentation alone does not prove implementation or operation.

## 5.3 Stop states

| State | Meaning | Advancement effect |
|---|---|---|
| `S0 RUNNING` | Normal controlled work | Continue |
| `S1 ADVISORY-HOLD` | Warning or non-blocking concern | Current step may continue only after recorded review |
| `S2 MANDATORY-STOP` | Material ambiguity/control deficiency | Current step stops |
| `S3 SYSTEM-STOP` | Verification, security, integrity or governance failure | Affected progression stops |
| `S4 EMERGENCY-SAFETY-STOP` | Critical authority, legal, financial, data or safety violation | Immediate governed stop |

No executor self-clears `S2`–`S4`. A stop record must include trigger, timestamp, affected scope, invoking authority, evidence, disposition, and resume criteria.

---

# 6. UNIVERSAL PROMPT ENGINEERING CONTRACT

Every operational prompt must use this exact structure and label contract:

```text
<<START Prompt N. {{Prompt Label}}>>
[Designation: ...]
ROLE
OBJECTIVE
CONTEXT
<<START {{Resource Label}}>>
...durable references...
<<STOP {{Resource Label}}>>
Note the following:
...notes...
INSTRUCTIONS
OUTPUT
STOP CONDITIONS
DECISION SET
<<STOP Prompt N. {{Prompt Label}}>>
```

Prompt integrity rules:

- exactly one matching prompt pair;
- balanced resource markers;
- no resource block contains its prompt end marker;
- placeholders resolved or explicitly `UNKNOWN`;
- resource labels match the supplied evidence;
- no secret embedded;
- authority is not inferred from capability;
- decision vocabulary is explicit;
- independence limitations are disclosed;
- implementation claims are distinguished from specification.

Marker normalization:

- `<<START Prompt N. Label>>` and `<<STOP Prompt N. Label>>` are the required canonical form.
- Mixed forms such as `<<START Prompt N — Label>>` are non-canonical and must be normalized before acceptance.

---

# 7. REQUIRED AMENDMENTS TO THE CANDIDATE

1. Replace the non-canonical composite identifier form `[PROJECT-KEY]::[PBI-01-0004.00.01]` with a registered alias or a direct canonical section ID.
2. Expand the canonical consolidation table to include `rationale`, `owner`, `evidence requirement`, and `advancement consequence` for every merged concept.
3. Add a mandatory human authority record and require verification before closure or approval decisions are accepted.
4. Normalize prompt markers and resource ordering to a single exact pattern throughout the full prompt set.
5. State that all registry, workflow, TTL, and enforcement mechanisms are specification-only unless implementation evidence is supplied.
6. Add a stop-state record schema and forbid executor self-clearing of `S2`–`S4`.
7. Ensure every standards claim is date-checked and tagged as adopted or advisory; do not infer compliance from citation alone.

---

# 8. DECISION

Decision: `RETURN`

Reason: The document is a useful design specification, but it does not yet demonstrate sufficiently complete consolidation, identifier integrity, authority evidence, or prompt-contract consistency to qualify for independent approval.

This version is a remediation patch only; it does not constitute implementation authorization or production authorization.

---

# 9. FILE NOTE

This file is the corrected remediation revision generated after review. It is not an implementation approval and is not a production authorization.
