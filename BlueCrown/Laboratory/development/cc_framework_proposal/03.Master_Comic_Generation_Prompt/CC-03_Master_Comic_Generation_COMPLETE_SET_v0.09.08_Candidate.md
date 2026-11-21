# CLASSIES CHRONICLES — CC-03 COMPLETE COMIC PRODUCTION SET
## v0.09.08 CANDIDATE (THREE DOCUMENTS, UNCUT)

**Status:** Proposed. The three documents below remain individually authoritative within their own scope; this file is only a convenient complete concatenation.



---

<!-- BEGIN CC-03.01-Master_Comic_Generation_Prompt_v0.09.08_Candidate.md -->

# CLASSIES CHRONICLES
## CC-03.01 — MASTER COMIC GENERATION PROMPT
### Version `v0.09.08_Candidate` | Format-verified comics, tiered covers, integrated opening pages

| Control | Governing value |
|---|---|
| Stable logical ID | `CC-03.01 / CC-MCGP` — unchanged |
| Current document operation | `COMIC_REVIEW` / specification refactor only; this file's creation does **not** generate artwork |
| Supersedes for a run expressly selecting it | `CC-03.01 v0.09.05_Candidate`; preserves compatible safeguards and history |
| Companion set | `CC-03.02` review and traceability `v0.09.08`; `CC-03.03` QA and file manifest `v0.09.08` |
| Upstream | Selected effective CC-01.01/01.02/01.03 and CC-02.01/02.02/02.03/02.04; unresolved count alignment disclosed in §3 |
| Canonical standalone page canvas | Exact **9:16 portrait**, source image **at least 1080×1920 real pixels**; equivalent higher exact ratios allowed |
| Canonical four-page sheet | Exactly four complete unique comic pages in **2 columns × 2 rows**, TL → TR → BL → BR; at least **2160×3840 pixels** when pages are 1080×1920 |
| Default daily commission | **8 story-bearing pages** / 2 sheets; **4 pages** / 1 sheet for explicitly classified very short stories; longer counts must be commissioned and divisible by four for sheet-only mode |
| Season/chapter covers | One **standalone, full-bleed, full-page 9:16** season cover per season and chapter cover per chapter, independently versioned and **outside** story-page counts |
| Episode opening | P01 is an actual story page; integrated upper **1/4–1/3** episode header, not a separate cover-only page |
| Daily opening | P01 of each daily comic is an actual story page; integrated upper **1/5–1/4** daily header, not a separate cover-only page |
| Logo rule | **Exactly one** chosen/authorized Classies Chronicles logo in an opening header; no separate repetition of its name or logo |
| Rendering default | `SHEET_STEP`: deliver one validated 2×2 sheet per separately authorized user continuation; stop |
| Alternative | `PAGE_STEP`: one page per authorized step, assemble four accepted pages into the sheet; no extra pages |
| Public challenge | One exact public Challenge A only on global final story page PN of the current story commission, at literal bottom |
| Release | Actual pixel/source/lettering/continuity/rights QA and owner authorization; generating or saving files alone never equals publication |

> **EXECUTABLE CORE — v0.09.08.** Read one authoritative public chronicle (or expressly authorized season/chapter/episode compilation). Lock a source-backed story map and visual continuity state. For daily comics, plan **N=8** by default or **N=4** when independently justified as very short. For each sheet, make **exactly four distinct complete 9:16 portrait pages**, each with genuinely generated/detail-bearing source dimensions **≥1080×1920**; assemble them in a zero-gutter **2160×3840 minimum** 2×2 collage. If a renderer cannot deliver that quality/dimension, use four individually generated compliant pages and deterministic assembly. **Never** treat a low-resolution quadrant or enlarged/upscaled 2:3 image as native-compliant. Introduce the episode or daily chronicle *within* its first story page rather than consuming a separate story page as a cover. Render only the exact next four page IDs; validate real pixels and words; checkpoint; stop until separately instructed. Create reusable season and chapter full-page covers as distinct cover assets when their scoped production is authorized. Preserve all rights, P/H/F, character, Challenge A, and source-truth safeguards below.

---

# PART I — AUTHORITY, RUN MODES AND SOURCE TRUTH

## 1. Request classification and operating boundaries

Choose the narrowest expressly authorized action:

| Operation | Authorized output / prohibition |
|---|---|
| `COMIC_REVIEW` | Study/refactor prompts, decision register, validation design; **no images** |
| `COMIC_PLAN` | Source/rights/visual-identity/cover inventory/page-budget plan only |
| `COMIC_STORYBOARD` | Frozen N-page distinct story beats plus integrated first-page header design |
| `SEASON_COVER` | One complete season-scoped standalone cover asset or a named revision; **no daily page consumed** |
| `CHAPTER_COVER` | One complete chapter-scoped standalone cover asset or a named revision; **no daily page consumed** |
| `COMIC_GENERATE` | Render only the current authorized page/sheet of a locked story plan |
| `COMIC_CONTINUE` | Continue from last permitted accepted page/sheet; never repeat or reboot |
| `COMIC_REVISE` | Repair specifically identified P, S or cover ID; maintain history |
| `COMIC_ASSEMBLE` | Mechanically compose verified page assets into a true 2×2 sheet |
| `COMIC_VALIDATE` | Measure and inspect actual output, without inventing missing results |
| `COMIC_FINALIZE` | Consolidated outputs, C01–C22 results, exact manifest, outstanding approvals |

**Do not** infer `SEASON_COVER` or `CHAPTER_COVER` from a request to continue S02 or from a review-only operation. Season/chapter covers are required *members of the full season/chapter publication package*: commission once per scope when that package is initiated; reuse already accepted assets. A daily rendering continuation must not unexpectedly produce separate covers. Do not launch CC-04 media, answer a challenge, publish, commit files or claim permanent repository storage without separate authorization.

## 2. Priority, documentary provenance and rights

Authority in its proper scope: applicable law/privacy/copyright/consent/platform rules → explicit current owner/editor decisions → actually adopted governance and canon → accepted continuity and approved character/visual models → the selected exact public **Output A** (or authorized compilation) → specific comic commission → actually effective CC-01/CC-02 controls → this selected comic specialist → aesthetic samples. A version string or presence on GitHub is **not** adoption.

Before rendering, record `source_id`, `source_title`, `source_revision`, source location, date/accepted status, source digest **only if measured**, P/H/F state and actual human locations, rights/consent, visual identity approval, source’s **exact public Challenge A**, total N, page numbering scope, run mode, story/cover scope, page-map digest and pending conflict/exception IDs. An unavailable fact is `UNVERIFIED`/`NOT_AVAILABLE`; do not manufacture hashes, characters, rights grants or plot outcomes.

Treat internal B1–B8 and the 15 B6 handover divisions as internal continuity input. Honor the existing **19** canonical ledger families without inventing a new one. Restricted Challenge C/answer keys must not enter public page prompts, artwork, metadata, alt text or public exports. Visual references are appearance guidance, never source authority for fictional findings or news. Third-party marks and identifiable people must have use-appropriate permission; a provisional draft is not public portrayal clearance. The real Koware Group logo, when authorized, is an **in-world** identity and must not be fused with the Classies Chronicles logo/masthead.

## 3. Default-count conflict resolution and the v0.09.08 decision

A selected upstream **CC-01.01 v0.09.01 candidate §42** historically specifies a standard sixteen-page comic. A separate **CC-01.01 comic-count alignment amendment v0.09.04** proposes eight but is explicitly not adopted. Predecessor CC-03.01 v0.09.05 preserved this conflict. **The owner's current instruction now directs v0.09.08 to use eight as the default with a four-page short minimum.** Therefore this candidate implements **8 as its specialist default when specifically selected for the run**, and records `UPSTREAM_COUNT_ALIGNMENT_PENDING` until CC-01 is independently amended/selected. It does not falsely claim that the old upstream file was changed.

Page allocation for *each separate daily comic commission*:

- **N=8** is the ordinary default, even for dense prose. Produce exactly S01 P01–P04 and S02 P05–P08.
- **N=4** is permitted only for an objectively very short, self-contained chronicle with no more than four separately visualizable narrative beats after considering continuity, dialogue, field observation and exact final Challenge A; record the criterion and editor's four-page selection. Do not omit indispensable source events merely to qualify.
- **N=12/16/20/...** are valid with an explicit editorial count or justified approved longer format. A previously explicit N=16 remains N=16 unless the owner changes it. A source does not acquire 16 pages merely because an earlier episode used 16.
- **N must equal 4k** for required all-four-page sheet delivery, with `k ≥ 1`. If an explicit N is not divisible by four, resolve the conflict by obtaining a revised count or expressly choosing a different delivery contract; **never invent filler pages, blank cells, or misleading extra pages**. No implicit automatic rounding.
- Season and chapter covers are **not** included in N; integrated episode and chronicle headers **are included in P01**, creating no new page IDs.

If an upstream master with N=16 still purports to control an unqualified commission and the new specialist is not expressly selected, flag `COUNT_CONFLICT` and do not pretend the eight-page setting is globally ratified. The companion proposed upstream-alignment patch supplies a concrete route to reconciliation.

## 4. Truth firewall: visualized facts are not proven events

Distinguish `OBSERVED`, `REPORTED`, `CALCULATED`, `HYPOTHESIZED`, `FICTIONAL_EVENT`, `PENDING`, `UNVERIFIED`, `CONFIRMED` per scene. Explicitly separate **P** present/production, **H** historical reference, **F** probing snapshot, and accepted **physical** character presence. A probe reading is not proof of physical travel. Kuala Lumpur field observation cannot be depicted as live Accra communication if the selected source says only delayed Edubeam message/report transport. A local print queue status is not proof of recipient delivery or permission. A photographed intact seal is not an opened archive. Conflicting exit signs are not validated emergency guidance. A proposed rule, allegation or media headline is not a verified enacted fact. Unresolved cases remain unresolved until supported by Output A. Never translate nonliteral metaphors into an unsupported supernatural revelation.

---

# PART II — IDENTITY SYSTEM AND COVER HIERARCHY

## 5. Locked franchise appearance and chosen logo

Use an **original cinematic, semi-photorealistic illustrated graphic-novel** idiom: lifelike anatomy and expressive character acting, individualized Ghanaian/West African faces where applicable, rich environmental context, restrained inking, high-contrast navy/teal/amber and legible white dialogue. Let location/time change lighting naturally without changing identity. Humor may briefly heighten expression when the actual story warrants it, then revert; no generalized anime/chibi drift and no imitation of a proprietary comic franchise.

One `CLASSIES_LOGO_APPROVED_ASSET` is selected by the owner from appropriately cleared brand assets, with file path/version/hash when actually available. **Do not improvise a second logo or add a separate typeset “Classies Chronicles” wordmark beside a logo that already contains that name.** Use a single authorized mark; if no approved graphic is accessible, log `LOGO_ASSET_MISSING` and do not falsely substitute an unrelated or fabricated logo as official. Keep logo clear, distinguishable from Koware Group branding and away from bubble tails. Existing supplied logo screenshots are *candidates*, not automatically approved assets.

The user preferred less clutter in episode cover art: apply **one dominant story symbol/subject, at most two supporting motifs**, meaningful negative space, a disciplined palette, clear title hierarchy, and readable mobile-size type. Do not put every location, clock, character, news story, receipt, instrument and book on one cover merely because each exists in the chronicle. Retain continuity with previously *accepted* cover art only as far as compatible with simplicity and originality.

## 6. Season cover — independent full page

**ID** `S{SS}_SEASON_COVER_rNN`, one accepted primary per season (additional revisions preserve prior versions). Create a single **full-bleed 9:16 portrait cover**, **≥1080×1920 genuine page detail**, not a 2×2 sheet quadrant, when season-cover generation is authorized. It introduces the entire accepted season arc and must not invent a completed ending or imply an unapproved season title is final. Required content: **one chosen Classies Chronicles logo**, season number (`Season 00` format), accepted season title if established, and restrained original imagery expressing the season's governing theme. Optional by explicit brand decision: a single short established season strapline. Avoid credits, entire cast lineup, episode-level receipt spoilers and future plot resolutions. Cover-only asset is separate from all N daily/episode page sequences and public challenges.

**Artwork rule:** one season-wide central visual metaphor, limited supporting background, clean typography-safe zones; distinguish season cover from the more specific chapter motif. If final season title is not yet authorized, mark title status `PROVISIONAL` in internal manifest and render only approved wording; do not invent canonical title. May require later same-ID revision when finalized.

## 7. Chapter cover — independent full page

**ID** `S{SS}CH{CC}_CHAPTER_COVER_rNN`, one accepted primary per chapter, same actual 9:16/≥1080×1920 and full-bleed rules. Required content: the **single** chosen series logo, `Season SS · Chapter CC`, accepted chapter title if available, one strong chapter-scale motif and visually subordinate context. Avoid replacing the chapter cover with a miniature season cover or an episode-specific clue collage. Preserve season branding without turning the chapter cover into an extra daily page. A chapter cover represents the **whole chapter**, including all episodes, not merely the first day's scene. If approval/title is pending, use only approved number/name and carry explicit provisional metadata.

## 8. Episode introduction — story P01 also serves as episode cover

For a separately commissioned **episode-level comic** or the **first chronicle's first page** opening a new episode, do not create a cover-only narrative page. The episode's first story page `P01` opens with an integrated header occupying **25% to 33⅓%** of the height (`480–640 px` on a 1920px page). Beneath and/or partially blended into that header, **real first-scene/first-act action begins on the same P01**. An object, environment, document, character pose, light cue or other genuine P01 scene element may bridge both regions, provided dialogue readability and chronology survive. Leave sufficient space for approximately 2–3 well-paced first-story panels or equivalent visual beats. The header is **not** a separate comic page or static advertisement.

**Exact allowable episode-header content:** the single approved Classies Chronicles logo; the **episode title**; season, chapter and episode identifiers; relevant non-textual narrative graphics. No second wordmark, repeated logo, sponsor/ad, unrelated slogan, daily/chapter/season headline, extra navigation strip inside the header, or a redundant large “Classies Chronicles” text. The page can carry **one small folio** outside the art/header at its topmost or bottommost safe margin, for example:

`Season 00 - Chapter 05 - Episode 03  01/08`

Page labels use the **commission's page-number denominator** and two-digit formatting (`01/08`, `04/04`, `01/16`); the example is formatting only, not proof Episode 03 has exactly 8 total pages. Scene captions and dialogue from P01 may appear in the **story panels below**, not as additional masthead labels.

## 9. Daily chronicle introduction — story P01 also serves as daily cover

For **every separately commissioned daily chronicle comic**, its first story page P01 receives a visually integrated top **20%–25%** header (`384–480 px` of 1920), no detachable cover-only first page. It can blend with a real object or shot from that day's opening scene. The **remaining 75%–80%** supports actual source-backed scene action (typically 2–3 readable panels); do not reproduce an arbitrary daily summary or consume one of the four P IDs for a standalone poster.

**Exact allowable daily-header content:** the one approved series logo; **episode title**; season, chapter, episode and **Chronicle #NN** identifiers; appropriate non-textual story imagery. Do **not** add a second series name/logo, separate unapproved daily title, date, advertising tagline or third-party mark to the header. A day-specific public title, if required by the actual story, belongs in a normal caption within the story area, not an extra header title. Small optional navigation folio, outside the header in the page edge safe zone:

`Season 00 - Chapter 05 - Episode 03 - Chronicle #05  01/08`

**Coincident first episode/day:** If P01 simultaneously opens the episode *and* its Chronicle #01, show **one header only**, not a stacked episode and daily masthead. Use the shared **25%** size (permitted for both); include the episode title, one logo and `Chronicle #01` identifier, and ensure scene action still begins on P01. In an episode compilation consisting of already published daily pages, retain their validated individual headers as part of the source unless a separately commissioned adaptation explicitly reflows the compilation. Do not introduce a second episode cover just because the same P01 is both scope boundaries.

## 10. Header and folio layout rules for every page

The season/chapter standalone covers are allowed to be full cover artwork. **Only P01** of a commissioned episode/daily sequence gets the large integrated opening header. **P02…PN do not repeat large episode/daily header or series logo**; they may carry the same small, consistent navigation folio if requested. Don't place an episode header on every sheet's upper-left page (P05, P09, … are **not** openings). First-page header is part of the first real page and must not be mistaken for a fifth collage page. A correct 2×2 sheet has four full story pages; S01 may include one opening header *inside* the TL page, never one cover-only cell.

Header text is generally best applied through deterministic lettering after illustration. Allocate the logo and episode title safe zones first and protect the first-scene characters/props from overlap. No text or faces should cross into an adjacent sheet quadrant. For mobile, proof legibility after extracting P01 at 1080×1920, not just in the full 2160×3840 sheet preview. Use normal folio `01/08` for daily scope, `01/NN` for episode scope, never mix their denominators. The exact navigation label is optional; when used it must be accurate and consistent.

---

# PART III — STORYBOARDS, PAGE MAPPING AND CONTINUITY

## 11. Freeze the exact storyboard before producing art

Select only the locked public Output A, not a blend of provisional competing versions. Build one immutable N-page story map with **distinct source-backed action and change on every page**. Sequence scene/act progression causally, preserve approved flashback/parallel cross-cutting, distinguish who knows what, and keep unresolved clues unresolved. Allocate the final public Challenge A **solely to PN**. The first P01 includes the introductory header *and* advances the first scene; it is not a wasted title card. A season/chapter cover does not substitute for a P01 scene.

For N=4: `S01 = TL P01, TR P02, BL P03, BR P04`. For N=8: `S01[P01,P02,P03,P04]`, `S02[P05,P06,P07,P08]`. For N=16: add S03[P09–P12] and S04[P13–P16]. The terminal challenge appears on **P04, P08 or P16 respectively**, not every lower-right page. A run with accepted S01 must continue S02; never reboot the opening to recap the premise.

## 12. Required distinct page record (`page_record`)

Store real values for: `run_id`, `story_scope` (`DAILY`/`EPISODE`), `page_id` PNN, `sheet_id` SNN, `cell` TL/TR/BL/BR, `page_number/denominator`, `source_revision` and exact source scenes, `unique_causal_purpose`, `arrival/incoming_state`, `departing/outgoing_state`, `first_scene_action`, `2–4 panel order`, `cast look-reference IDs`, wardrobe/props, `physical_location`, `P/H/F` and temporal presentation type, verified visual/lettering instructions, `header_mode` (`EPISODE`, `DAILY`, `COMBINED`, `NONE`), `header_fraction`, `logo_asset_ref`, `challenge_allowed` (true only if page=PN), `rights`, `native_target_px`, `source_detail_px`, `export_px`, `transform_history`, and `QA_risks`.

Do not compress four unrelated source events onto P01 merely because it now has an intro header. It may carry fewer balloons than subsequent pages but must contain meaningful action. Preserve any scene-specific dialogue exactly where necessary; if AI lettering miswrites proper nouns, replace it with verified deterministic typography rather than approving misinformation.

## 13. Character, object and cross-sheet continuity lock

Maintain per-character cards with accepted identity/rights, consistent facial geometry, complexion, hairstyle/hairline, build, eyes/brows, glasses/jewelry, current wardrobe, emotional state, posture and last verified location; maintain scene-object cards for letters, seals, documents, bags, devices, signs and route diagrams. Record the most recent accepted actual image as a visual anchor whenever rights/tool support allow. On S01→S02, S02→S03, etc., compare real images (not only text descriptions). Outgoing P04's state must lead plausibly to P05, and so on. Do not change one character into another across the page boundary or move Edubeam field personnel physically without source evidence.

Save a compact honest checkpoint: `run_id`, `locked_source_digest_if_measured`, `N`, `page_scope`, `accepted_sheet`, `accepted_page_range`, `next_sheet/page_ids`, real asset refs and measured sizes, visual reference, character/prop/knowledge state, last panel action, pending repair flags, logo/header statuses, challenge count, rights/editorial status. A previous sheet can be a *liked style reference* while failing geometry; **do not convert that into release QA acceptance**.

---

# PART IV — NON-NEGOTIABLE NATIVE GEOMETRY AND LEGIBILITY

## 14. Exact four-page collage geometry

Each real independent comic page has pixel dimensions `(W,H)` such that:

```text
16 × W == 9 × H                  # strict exact 9:16 portrait, no tolerance
W >= 1080 and H >= 1920         # minimum requested source-image target
sheet_width  = 2 × W
sheet_height = 2 × H
minimum correctly composed sheet = 2160 × 3840
```

**One** 2×2 grid comprises exactly four equal-sized distinct page assets, ordered TL P(4k+1), TR P(4k+2), BL P(4k+3), BR P(4k+4), with no fifth panel outside those page cells, no cover-only cell, no unexplained margins or mixed aspect ratios. At minimum the collage canvas is 2160×3840 pixels. Any page borders/dividers belong **inside** their pages so extraction needs no content-destructive trimming.

For zero-gutter `sheet=(2W,2H)`, crop rectangles in `(left,top,right,bottom)` coordinates: TL `(0,0,W,H)`; TR `(W,0,2W,H)`; BL `(0,H,W,2H)`; BR `(W,H,2W,2H)`. All four pages then individually pass the exact ratio. A 2×2 collage that merely has 9:16 **overall dimensions** may still contain undersized quadrants: `1080×1920 sheet -> 540×960 pages = aspect PASS / detail FAIL`. `1024×1536 sheet -> 512×768 pages = aspect FAIL / detail FAIL`. `2160×3840 sheet -> 1080×1920 pages = baseline geometry PASS`, subject to actual source details.

A mixed high/low-resolution sheet must be rejected or clearly marked unfit for release, even if software enlarged it. **Do not stretch, crop faces, cut speech, or add letterbox bars as a counterfeit geometry repair.** No page, header, or speech balloon spills into a neighboring quadrant. Footer folios and terminal Challenge A must remain inside the 9:16 page safe area.

## 15. Source detail vs target export — no upscaling misrepresentation

Distinguish:

1. `NATIVE_PAGE_SOURCE`: measured width/height of actually rendered, detail-bearing original per page and provider evidence if accessible.
2. `POSTPROCESSED_PAGE`: measured file after permissible spelling corrections, color edits or non-distorting compositing.
3. `UPSCALING_OR_SYNTHETIC_DETAIL`: any explicit enlarging or generative super-resolution; **not relabeled native**.
4. `DELIVERED_PAGE`: the exported page file and `DELIVERED_SHEET`: its sheet; dimensions both measured.

**Acceptance condition:** geometric minimum at final export **and** the original page's usable/detail-bearing resolution meeting or demonstrably achieving comparable source quality to the 1080×1920 target. An upscale from 512×768 is not proof of native 1080×1920 illustration. Where a provider has no genuine page-size option, the run may stop at `RESOLUTION_BLOCKED`, or deliver a **clearly provisional below-target draft** if expressly authorized; no assertion that the 1080 target was met. Pixel geometry by itself cannot verify true native image generation — combine file dimensions, provider/output metadata and visual inspection.

## 16. Mandatory renderer-choice algorithm

**Preferred robust workflow (required when direct 2×2 cannot actually meet detail):**

1. Render **Paa, Pbb, Pcc, Pdd as four real complete 9:16 pages separately**, each ≥1080×1920 native/detail-bearing source pixels where technically available, using stable shared character/style references and per-page layout/lettering instructions.
2. Inspect each P immediately: exact ratio, minimum detail, correct scene, panel completeness, character identity, header scope and text-safe zones. Repair the same P ID if necessary.
3. Apply deterministic text/folio overlays as required, verify content and real output pixels.
4. Compose the **four accepted page files** mechanically by pixel copy into one 2160×3840 (or higher) zero-gutter sheet with ordered cell coordinates. No scaling or quality loss in assembly.
5. Deliver **one finished 2×2 sheet** for the current `SHEET_STEP`; retain real individual page files for archive/QA if feasible.

**Direct four-at-once alternative:** allowed only if provider truly returns one sheet of at least 2160×3840 and **each cropped cell** is independently a complete ≥1080×1920 9:16 comic page with enough readable visual/lettering detail. **Prompting** a provider with 2160×3840 without that actual output is not compliance. If actual output is a 1024×1536 collage, **reject/repair the sheet before accepting it**; do not continue through S02–S04 with a known geometry defect.

If tool cannot provide four compliant native page images or one compliant collage, explicitly disclose `RENDERER_CAPABILITY_BLOCKER`, preserve the storyboard and existing draft assets for repair, and refrain from certifying/automatically continuing a noncompliant run. Do not promise background renders or inaccessible tool capabilities.

## 17. Lettering, mobile-safe design and text QA

At real 1080×1920 page size, leave design-aware device-safe areas near top and bottom (initial check: ~72–108 px; adjust for panel/folio not platform-specific hardcode). Use legible high-contrast text: a preliminary 44–60 px font range for principal dialogue if compatible with panel density, not a universal claim of readability. Aim for 2–4 internal story panels on ordinary pages; first integrated-header page may use fewer but still contains scene action. Keep balloons concise, correct tails, no hidden text. Never squeeze a long lesson, exact Scripture or news transcript into a tiny caption. Source-sensitive exact names, dates, labels, versions, proof statements and terminal Challenge A are best **typeset deterministically** after generating art-safe zones.

Check each final cropped page at native pixels and phone-width reduction. Do not validate lettering only on the 2×2 thumbnail. Check scene labels and real facts against selected Output A, particularly safety directions, chronology, legal permission and document delivery status. Remove/correct AI-invented labels cleanly. An image without readable source-critical words is `LETTERING_FAIL` regardless of artistic quality.

---

# PART V — CONTROLLED EXECUTION, REPAIR AND ACCEPTANCE

## 18. Step-gated state machine

`SOURCE_LOCKED → STORYBOARD_LOCKED → [COVER_SCOPE_ASSETS_IF_COMMISSIONED] → S01_PREFLIGHT → S01_RENDERED_DRAFT → S01_GEOMETRY_AND_VISUAL_QA → S01_WORKING_ACCEPTED_OR_REPAIR_REQUIRED → WAIT_FOR_USER → S02_PREFLIGHT ... → SN_FINAL_QA → FINAL_MANIFEST → RELEASE_REVIEW`.

In `SHEET_STEP` the user authorizes **one sheet per generation/continuation message**; within that sheet the implementation may create four individual pages before assembly. On failure repair the same sheet, but do not silently render another numbered sheet in the same response. On completion of an accepted sheet stop; user must request next sheet. In `PAGE_STEP`, one page per generation turn, and only after four accepted pages mechanically assemble the sheet. An arbitrary `continue` from an accepted S01 starts at S02, **not P01**. Cover generation is a separate operation from continuing story sheets.

Rendering is **not** editorial acceptance. Track separately `ASSET_RENDERED_DRAFT`, `GEOMETRY_PASSED`, `DETAIL_PASSED`, `EDITORIAL_WORKING_ACCEPTED`, `RIGHTS_APPROVED`, `SOURCE_ACCEPTED`, `PERSISTENCE_VERIFIED`, `PUBLIC_RELEASE_APPROVED`. An image may be stylistically liked while blocked by 2:3 or illegible text; do not advance into a release-ready state by calling it "final." Provisional continuation across sheets requires an authorized working-acceptance decision, explicitly distinct from full release.

## 19. Prompt compiler for each sheet or page

Construct every renderer instruction from this concise, specific contract rather than resending a whole script to be re-summarized:

**A — Scope:** `ONE SHEET Sxx ONLY`, exact page IDs TL/TR/BL/BR, `N`, individual `1080×1920 minimum` and exact `9:16`, sheet `2160×3840 minimum`, assembled if necessary. One collage, four *complete distinct comic pages*, not four panels of one page.

**B — Identity:** one approved series logo asset; repeat the **previous accepted artwork character appearance**, scene objects, costumes and lighting logic; original cinematic graphic-novel style; no Koware mark fused with the logo.

**C — Header switch (explicit, per page):** `EPISODE_TOP_25_TO_33`, `DAILY_TOP_20_TO_25`, `COMBINED_TOP_25` or `NONE`. Only story P01 has an opening header; contents restricted by §§8–10. The P01 header shares its page with genuine first-act panels; never a stand-alone cover cell. If not S01, every page is `NONE` unless the authorized storyboard truly begins a distinct daily comic at that separate P01.

**D — Four current page beat cards:** For each page, give `incoming state → precise scene/action/panel order → source-backed dialogue/caption → outgoing state`. State physical location and evidence modality. Never retell earlier beat cards or include future ones.

**E — Exclusions:** no story reset, fifth page, wrong aspect, upscaled-crop compliance claim, duplicated logo, repeated large intro on P05+, unsupported archive opening/route safety/field return, false approval, random character substitution, or half-page advertising cover.

**F — Finality switch:** `challenge_allowed=false` everywhere except global PN; when `true`, insert the **full exact public Challenge A** at literal bottom of PN **once**, with nothing after it. Do not expose Challenge A to early sheet renderer calls and never leak the restricted answer.

**G — Verification:** inspect measured output, page/folio denominator, safe-zone readability, visual continuity and source truth. Log results; accept only a compliant sheet/authorized draft; **STOP** after current sheet.

### 19.1 Reusable `COMIC_CONTINUE` invocation

> Continue the **locked** Classies Chronicles comic from the **last authorized accepted sheet**, not from the beginning. Generate **only Sxx containing Paa, Pbb, Pcc, Pdd** as four separate internally complete **9:16 portrait comic pages**, **≥1080×1920 genuine per-page source detail**, ordered TL/TR/BL/BR into one **≥2160×3840 2×2 sheet**. If direct collage does not actually supply these four compliant pages, render them individually and mechanically assemble. Preserve prior character/prop/story states and the four specifically allocated *new* source beats. Apply an integrated opening header **only if one of these pages is P01** and use the specified single approved logo, episode title, identifiers and correct scope; no extra cover cell. Enforce the exact Challenge A **only on the global final PN**. Inspect actual page crops, resolution provenance, final words and story/rights continuity, record a checkpoint, present only this sheet and **stop**.

This invocation requires real sheet/page IDs, source references and a real checkpoint to be functional; it is not a license to guess missing facts.

## 20. Revision policy, exceptions and no deceptive repairs

For a failed asset use stable IDs with revision suffixes, e.g. `P01_r02`, `S01_r02`, `S00CH05_CHAPTER_COVER_r02`; preserve earlier hashes/links when actual. Aspect repair should **recompose or regenerate the page at proper geometry**, preserving its accepted visual motifs, rather than crop away clues or distort characters. Artwork from a previous 2:3 collage is a **reference**, not already fixed simply because a 1080×1920 export was made. If relettering alters the art, recheck the final rendered file. Do not silently substitute a different image, overwrite the accepted character anchor or erase the rejection trail.

A proposed `EXCEPTION_AUTHORIZED` may be recorded only when an actual owner approves a documented exception, but **v0.09.08 format-conforming release must still meet exact 9:16 and specified target detail**. A noncompliant exception is a *separate explicitly labeled draft format*, not a `C11/C19 PASS` under this strict contract.

## 21. Completion, deliverables and cadence

At story completion provide the exact N unique real pages; N/4 mechanically correct real sheets; page and sheet pixel sizes; true native/postprocessed/upscaled status; season/chapter cover asset refs if they were commissioned; accurate P01 episode/daily opening-header mode; lettering QA and challenge result; full C01–C22 status with evidence and blockers; image versions/checksums where actually measured; consent/source/publishing state; and a consolidated continuity handover. At season/chapter packaging, separately include the **one chosen primary full-page season and chapter cover** with their own versions, acceptance and rights record. Do **not** repeat QA reports between every stepped image unless a blocking fault needs disclosure.

Public deliverables are artwork and public-safe metadata only. Internal B1–B8, B6, rights evidence, source stamps, rejected variants and private challenge information remain in internal records. No automatic video, narrated script, GitHub commit, public upload or background processing.

---

# PART VI — ORIGINAL C01–C22 GOVERNANCE AND REVISION TRACE

## 22. Preserve official C01–C22 identifiers exactly

The exact legacy gates remain unchanged; updated covers/intro/detail requirements become **subtests**, not unapproved new official gates:

| Gate | v0.09.08 interpretation, evaluated only on real relevant assets |
|---|---|
| `C01_COMMISSION` | Correct selected scope, N8/N4/explicit N, cover operations, step/rights authority |
| `C02_SOURCE_REVISION` | Exact authoritative public source, real revision/hash if measured |
| `C03_FIDELITY` | Each panel from eligible Output A; no fabricated case resolution or misleading cover |
| `C04_PAGE_PURPOSE` | Every P distinct; P01 header **and** real first-act storytelling |
| `C05_THREE_CLOCKS` | P/H/F and actual physical locations/modalities consistent |
| `C06_CANON_CHARACTERS` | Stable approved visual identity across panels and S boundaries |
| `C07_MISSION_TECH` | Correct Edubeam/report/probe/project states and communication limits |
| `C08_ENVIRONMENT` | Geographically/culturally coherent season/chapter/daily scenes |
| `C09_RIGHTS_CONSENT` | Per-image rights, likenesses, chosen logo and Koware marks authorized |
| `C10_ORIGINALITY` | Distinct franchise style and readable, uncluttered cover grammar |
| `C11_PAGE_ASPECT` | **Exact measured** 9:16 for every full page, independent covers and 2×2 cells |
| `C12_PANEL_FLOW` | One sheet = four complete pages; P01 integrated header does not consume page |
| `C13_LETTERING` | Correct header strings, short readable dialogue, accurate Challenge A |
| `C14_VISIBLE_LABELS` | Single series logo/header, exact season/chapter/episode/chronicle/folio labels |
| `C15_CHALLENGE_SOURCE` | Exact current public Challenge A, not private answer |
| `C16_CHALLENGE_PLACEMENT` | Zero before PN, exactly one at literal bottom PN |
| `C17_NO_LEAKAGE` | Restricted answer/keys never public or in prompts |
| `C18_COUNT_INVENTORY` | Exact N story pages and N/4 complete sheets; covers outside N |
| `C19_EXPORT_QUALITY` | **≥1080×1920 genuine per-page detail**, sheet ≥2160×3840, mobile proof, honest upscale disclosure |
| `C20_DEPENDENCY` | Source/canon/covers/logo/checkpoint and CC-01 upstream alignment valid or flagged |
| `C21_RECORD_INTEGRITY` | Real file paths/hashes/revision status and provenance, not invented |
| `C22_DISCLOSURE` | Public art vs private handover and provisional/release status separated |

Internal subchecks `SC098_*` are defined in CC-03.03. Count/subtests do not rename C gate IDs or overwrite historical checks. QA is `PASS / FAIL / BLOCKED / NOT_CHECKED / NOT_APPLICABLE` with actual evidence, not an inferred PASS from this document.

## 23. Compatibility/preservation statement

This specification **retains** v0.09.05's immutable eligible public A, rights and privacy, B1–B8 handover separation, P/H/F truth firewall, character look cards, distinct source-backed P IDs, actual image continuity, resumable checkpoints, one-sheet-per-user-step and optional PAGE_STEP, global PN-only public Challenge A, accurate C01–C22 names, repair-with-same-ID, and no publication/media assumptions. It **replaces** ambiguous 16-as-ordinary-default language *within expressly selected v0.09.08 runs*, permits the editor-selected four-page very short minimum, turns per-cell 1080×1920 into an explicit blocking target, adds a season/chapter independent cover layer, and creates integrated episode/daily first-page headers with a single logo. Former hardcoded Day04 P01–P16 scene mapping remains **historical example only**, not a general instruction to draw Day04 or to copy Day05's unverified artwork. The v0.09.05 reference observed at 1024×1536 and the November 20 Day05 four collages measured at 1024×1536 are **not** grandfathered into geometry compliance.

**Adoption:** `v0.09.08_Candidate` is delivered for owner review. Selection of this file for a specified run sets that run's comic operating contract; broad franchise default alignment requires the separately proposed CC-01 edit and actual owner decision. This file creation does not modify the repository or prove a rendered 1080×1920 page.

**END — CC-03.01 Master Comic Generation Prompt v0.09.08 Candidate**


<!-- END CC-03.01-Master_Comic_Generation_Prompt_v0.09.08_Candidate.md -->


---

<!-- BEGIN CC-03.02-Comic_Refactor_Review_Conflict_Register_and_Change_Traceability_v0.09.08_Candidate.md -->

# CLASSIES CHRONICLES
## CC-03.02 — COMIC REFACTOR REVIEW, CONFLICT REGISTER AND CHANGE TRACEABILITY
### `v0.09.08_Candidate` | Review findings, owner-request implementation and unresolved controls

| Control | Value |
|---|---|
| Stable ID | `CC-03.02 / CC-MCGP-REVIEW` |
| Review operation | `COMIC_REVIEW`; no artwork generated in this revision |
| In-scope sources | Public GitHub `CC-03.01`, `CC-03.02`, `CC-03.03` v0.09.05; upstream CC-01.01 v0.09.01 and its proposed v0.09.04 count patch; user instructions in this conversation |
| New companion master | `CC-03.01 v0.09.08_Candidate` |
| New companion QA | `CC-03.03 v0.09.08_Candidate` |
| Issue classes | Native page geometry; collage-only failure; season/chapter covers; episode/daily opening header; logo repetition; default page count; source/canon/rights and C-gate preservation |
| Evidence rule | Document rules are **specified**, not shown to work until real images/assets are tested |
| Status | `TEXTUAL_REFACTOR_PREPARED`; `OWNER_ADOPTION_PENDING`; `NEW_ARTWORK_NOT_RENDERED` |

## 1. Reviewed baseline and primary determination

The v0.09.05 master already sets exact 9:16 pages, four cells in a 2×2 TL/TR/BL/BR layout, unique scene beats, `SHEET_STEP`, native-dimension honesty, one complete public Challenge A at global PN, character look anchoring, a private checkpoint and unchanged C01–C22 gates. Its companion review documents earlier failures: later batches restarting the story, duplicate challenges and wrong story lengths; four-page requests that became pages/panels of the wrong topology; changed character appearances; undocumented physical travel; and 2:3 crops presented as 9:16. Its QA companion correctly warns that 1080×1920 after resampling is not evidence of 1080×1920 native illustration.

**The root deficiency in v0.09.05 is therefore not absence of all geometry language, but insufficient operational enforcement and incomplete branding hierarchy.** The source permits separate native-page assembly if direct collage cannot deliver geometry, yet ordinary image generation can still produce 1024×1536 sheets and continue unless each outcome is actually checked. The title/cover experience also lacked a clear **season → chapter → episode → daily** architecture. Owner feedback on an overbusy episode cover adds a visual-simplicity constraint. Existing scene-page page headers repeat branding across pages, whereas the requested behavior is one introductory *story* page with a smaller folio elsewhere. The eight-page proposal from v0.09.04 had not been adopted at the CC-01 master level.

## 2. Actual image evidence available for this review

Four generated November 20, 2026 S00CH05E03DAY05 2×2 comic collages are locally available in the conversation workspace. Their decoded intrinsic dimensions were measured as **1024×1536 pixels each** (not inferred from the file name or prior assistant reply). Under the zero-gutter symmetric four-cell layout, each cell is **512×768 pixels**, which simplifies to **2:3** and is far below the new **1080×1920** per-page target. Each 1024×1536 asset also fails the new required sheet floor of **2160×3840**.

Observed files (names as stored in the current conversation workspace):

| File | Measured sheet | Equal-grid cell | Geometry verdict |
|---|---:|---:|---|
| `a_detailed_comic_book_style_page_with_four_comic_p.png` | 1024×1536 | 512×768 | `FAIL_2_3_BELOW_MINIMUM` |
| `a_detailed_comic_style_comic_page_collage_4_panel.png` | 1024×1536 | 512×768 | `FAIL_2_3_BELOW_MINIMUM` |
| `a_detailed_comic_style_collage_image_four_comic_p.png` | 1024×1536 | 512×768 | `FAIL_2_3_BELOW_MINIMUM` |
| `a_detailed_comic_book_style_composite_illustratio.png` | 1024×1536 | 512×768 | `FAIL_2_3_BELOW_MINIMUM` |

This measurement confirms a **geometry error**; it does **not**, by itself, prove all story beats are wrong or that rights are cleared. The original v0.09.05 historical review separately reports a first example at 1024×1536 for Day04. That finding is preserved as a cited historical observation rather than being falsely re-measured here.

Two subtle distinctions matter: (i) four quadrants can all look portrait-oriented while still being **2:3**, and (ii) a sheet of exactly **1080×1920** is 9:16 overall yet yields **540×960** 9:16 pages—**still below** 1080×1920 per page. Neither case is acceptable to v0.09.08. **Upscaling to 2160×3840 can satisfy final file dimensions without supplying authentic high-resolution source detail.** The evidence manifest must distinguish both facts.

## 3. Exact changes requested by owner and decisions adopted into the candidate

| Owner requirement | Before v0.09.05 | v0.09.08 implementation | Acceptance proof needed |
|---|---|---|---|
| Exactly 4 pages per 2×2 collage | Required, but earlier failures demonstrate drift | Four distinct complete page IDs, row-major; one current sheet, zero-gutter geometry | Actual S sheet cell inspection |
| Each page strict 9:16 | Already worded; first liked example failed | `16*W==9*H` **per real cropped page** with no tolerance; no sheet-only shortcut | Pixel dimension audit of all four cells |
| ≥1080×1920 *per page* | Preferred before; not reliably enforced as blocking native target | Hard v0.09.08 target with measurable native/source-detail vs export provenance | Page pixel/native metadata + final visual inspection |
| Full season cover | Not part of regular v0.09.05 flow | One independent full-bleed season cover asset per season, outside N | 9:16, source theme/rights, actual file |
| Full chapter cover | Not part of regular v0.09.05 flow | One independent full-bleed chapter cover asset per chapter, outside N | Same, chapter scope verified |
| Episode first page is also episode cover | v0.09.05 prohibited unrelated extra cover, but no integrated design | P01 header occupies top **1/4–1/3** while first story act continues in the same page | P01 visual inspection; no cover-only cell |
| Daily first page is also daily cover | No explicit day-specific proportional rule | P01 header occupies top **1/5–1/4** while first scene continues | P01 visual inspection, body action present |
| Single series mark / no duplication | Series title bands often repeated | Exactly one approved logo in opening; no redundant wordmark in header; no large masthead on P02+ | Header text/mark count and logo rights check |
| Folio format | Page numbers could repeat sheet headers | `Season 00 - Chapter 05 - Episode 03 - Chronicle #05 01/08` etc, small consistent top/bottom folio | Numerator, denominator, identifiers validated |
| 8 default, 4 short minimum | v0.09.05 kept master v0.09.01 N16 conflict open | This selected specialist defaults 8; very short justified 4; explicit N16 retained | Version/commission controls; upstream alignment |
| Complex cover looks too busy | No strict density cap | One hero motif, up to two supports, whitespace and restrained palette | Human mobile-size approval |

## 4. Conflict resolution matrix

| ID | Collision | Resolution in v0.09.08 | Residual/decision owner |
|---|---|---|---|
| `CON-098-01` | Upstream CC-01.01 v0.09.01 §42 N16 vs requested default N8 | In an expressly selected v0.09.08 run the owner's **new** specialist instruction controls its default; include upstream proposed patch | `UPSTREAM_COUNT_ALIGNMENT_PENDING` until separate master adoption |
| `CON-098-02` | Existing explicit 16-page commissions vs new 8 default | Explicit job count wins; no retroactive truncation of accepted sixteen-page runs | None for commissioned count; check scope |
| `CON-098-03` | Source may have many scenes but short form asks 4 | N4 only after independent very-short editorial criterion; cannot remove required beats | Editorial signoff |
| `CON-098-04` | Exact four pages/sheet vs nonmultiple-of-four N | Block and request an authorized compatible count/mode; no filler | Commission owner |
| `CON-098-05` | One collage per user continuation vs need four native high-detail renders | Four internal per-page renders may be mechanically assembled to **one** deliverable sheet within the one authorized step | Provider availability, cost, consent; verify four files |
| `CON-098-06` | Provider output image ratio/size vs strict requested native minimum | Reject noncompliant direct sheet; use individually rendered pages or declare capability blocker | Actual renderer test |
| `CON-098-07` | First episode page and first daily page both require an opening header | **One shared 25% integrated header**, with both identifiers and P01 story action | UI design check |
| `CON-098-08` | Full season/chapter covers vs no bonus covers mid-run | Covers are independent scoped assets for full collections, not unsolicited extra images during `COMIC_CONTINUE` | Cover commission/packaging owner |
| `CON-098-09` | Episode title required in daily header vs desire for daily introduction | Daily header uses episode title + Chronicle #NN; daily title may be in first story caption, not competing header | Typography owner |
| `CON-098-10` | Full title/logo repeat inside and outside header | One authorized logo (with included wordmark counted as title); no duplicate; folio outside header | Brand owner |
| `CON-098-11` | Noncompliant S01 liked visually vs release needing exact aspect and native minimum | Preserve as style anchor, repair/regenerate same IDs; no silent crop/stretch | Approval of repaired design |
| `CON-098-12` | Source/unapproved final season/chapter title vs cover requirement | Produce provisional only using actually approved title/number; never invent final canonical title | Editorial title approval |
| `CON-098-13` | Strict challenge on PN vs intro header occupying some page space | Challenge always on **final** PN, not P01 unless N=1 (disallowed); first header cannot displace it | QA at PN |
| `CON-098-14` | Existing C01–C22 IDs vs expanded checks | C-gate names preserved, new independent SC098 subchecks | QA owner |
| `CON-098-15` | Same logo usage vs real Koware Group identity | Chosen series mark only for branding; authorized Koware logo appears only in-world | Rights/brand owner |

## 5. Cumulative refactor decision register — new IDs only

Previous v0.09.05 `CR-095-01…CR-095-22`, older `CR-094-01…CR-094-50`, and referenced historical `M01…M40` / `R01…R18` remain intact **by reference**; they are not renamed, renumbered or represented as re-executed. New rows are additive:

| Decision ID | Concrete v0.09.08 action | Status |
|---|---|---|
| `CR-098-01` | Enforce **four distinct complete pages** in one 2×2 sheet | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-02` | Make exact `16W=9H` **per cropped page** mandatory | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-03` | Make original/detail target ≥1080×1920 **per** page, sheet ≥2160×3840 | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-04` | Specify native/source vs postprocess vs export; no false upscale PASS | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-05` | Prefer four separate page renders and mechanical assembly when direct sheet fails | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-06` | Introduce `RESOLUTION_BLOCKED` / `RENDERER_CAPABILITY_BLOCKER` | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-07` | Add season full-page primary cover asset and revision namespace | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-08` | Add chapter full-page primary cover asset and revision namespace | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-09` | Keep season/chapter covers outside P-count and sheet stepping | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-10` | Episode P01 merges 25%–33⅓% introduction with real first act | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-11` | Daily P01 merges 20%–25% introduction with real first act | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-12` | Reconcile simultaneous first episode/chronicle P01 using one shared 25% header | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-13` | One selected series logo; no duplicate series wordmark/logo in header | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-14` | Replace repetitive large series bands with small optional folios on P02+ | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-15` | Formalize episode and daily folio grammar and `01/N` denominator | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-16` | Default N8 and justified N4 short floor; honor explicit 4k counts | `IMPLEMENTED_IN_DOCUMENT / UPSTREAM_ALIGNMENT_PENDING` |
| `CR-098-17` | Provide targeted CC-01.01 count-alignment patch; do not claim application | `PATCH_PREPARED_ONLY` |
| `CR-098-18` | Limit cover to one hero motif + ≤2 secondary motifs | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-19` | Maintain v0.09.05 source lock, P/H/F, rights, scene truth and character anchors | `PRESERVED` |
| `CR-098-20` | Preserve exactly original C01–C22 identifiers with documented subchecks | `PRESERVED` |
| `CR-098-21` | Preserve one Challenge A at global final PN, not per sheet | `PRESERVED` |
| `CR-098-22` | Explicit noncompliant-image repair gate before continuation | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-23` | Add real manifest fields for cover/header/folio/detail/geometry | `IMPLEMENTED_IN_DOCUMENT` |
| `CR-098-24` | Write a mechanical image geometry/assembly validator and synthetic selftests | `UTILITY_INCLUDED; tests executed separately if reported` |
| `CR-098-25` | No implicit images, media, repository mutation, public release from review | `PRESERVED` |
| `CR-098-26` | Preserve older aesthetics as references while rejecting 2:3 release status | `IMPLEMENTED_IN_DOCUMENT` |

**Status semantics:** `IMPLEMENTED_IN_DOCUMENT` means the behavior is written into a candidate contract. It does not mean an image model obeyed it, a logo was selected, an artist approved the design, a connected repository was updated or a C gate passed. Static checks may be carried out on the files/validator but are not a live image-production acceptance trial.

## 6. Detailed per-requirement visual interpretation

### 6.1 Four genuine 9:16 pages

The page is the atomic story unit, not the quadrant-shaped preview. A comic sheet has exactly four real pages, each itself containing 2–4 story panels as appropriate. The total sheet **must** preserve relative 9:16 geometry and at least 1080×1920 detail in **each** cell. A native four-at-once renderer is eligible only after a successful actual 2160×3840 (or greater) result; otherwise four independent page renders plus deterministic assembly prevent the common 1024×1536 collage failure. The validator checks arithmetic, but cannot infer true native provenance by looking only at an enlarged image.

### 6.2 Season vs chapter covers

A season cover communicates the entire season, a chapter cover communicates the whole chapter, and neither is a daily page. Both carry the approved series identity once and should remain readable in miniature. The previous overcomplex episode-cover feedback becomes a general visual rule: one principal symbolic object/subject, limited background motifs, avoid sprawling cast/time/location montages. Title/provisional-state validation applies separately to season, chapter and episode layers.

### 6.3 Episode vs daily integrated headers

An episode opener is P01 with a top-quarter/top-third artistic integration. Every separate daily issue also has a 20–25% top header on P01. When both are literally the same page, 25% satisfies both; duplicating them would violate the user's one-logo and scene-bearing-page requirements. Later pages retain story content and optionally a small folio, not a recurring magazine masthead. Each `P01` folio uses the scope's true denominator; the `01/08` example does not imply a full two-week episode contains only eight pages.

### 6.4 Minimum four pages without condensing away story

A four-page daily is an exceptional short-form determination, not an automatic downgrade whenever an arbitrary word count is low. The editor must establish that four distinct page functions carry the opening, development, consequence and final public challenge without compressing/erasing source scenes. Otherwise use the normal N8 or an explicit longer count. Both N4 and N8 map cleanly into four-page sheets.

## 7. Regression scenarios and expected status

| Test ID | Synthetic/actual scenario | Expected |
|---|---|---|
| `T098-01` | Four 1080×1920 native pages assembled, sheet 2160×3840 | `GEOMETRY_PASS`, source/visual still checked separately |
| `T098-02` | Four 512×768 pages, 1024×1536 sheet | `C11_FAIL`, `C19_FAIL` |
| `T098-03` | 1080×1920 **entire sheet** → 540×960 page cells | `C11_PASS`, `C19_FAIL` |
| `T098-04` | 2160×3840 upscale of original 1024×1536 | If stretched, `C11_FAIL`; if reflowed, source detail provenance remains `C19_BLOCKED/FAIL`, not native PASS |
| `T098-05` | Sheet contains three pages plus a stand-alone title poster | `C12_FAIL`, `C18_FAIL` |
| `T098-06` | S02 P05 repeats episode intro/logo | `HEADER_SCOPE_FAIL`, `C04/C14_FAIL` |
| `T098-07` | Episode P01 uses 25% header and active scene; Chronicle #01 displayed once | `HEADER_LAYOUT_PASS` subject to legibility/source review |
| `T098-08` | Day05 P01 header uses 20% and extra wordmark beside full logo | `LOGO_DEDUP_FAIL` |
| `T098-09` | N8 with Challenge A on P04 and P08 | `C16_FAIL` |
| `T098-10` | N4 (owner selected), four distinct story pages, Challenge on P04 | Count/placement structurally eligible |
| `T098-11` | N7 with strict four-page sheet-only delivery | `COMMISSION_CONFLICT` / no filler |
| `T098-12` | Season cover replaces TL P01 | `C12/C18_FAIL` |
| `T098-13` | Two separate intro bands on coincident episode/day P01 | `HEADER_DUPLICATION_FAIL` |
| `T098-14` | Public art uses real Koware logo as series masthead | `C09/C14_FAIL` pending authorized exceptional design, never assumed |
| `T098-15` | No available approved series logo | `LOGO_ASSET_MISSING`/cover or header release blocked; do not invent official logo |
| `T098-16` | All pixels correct, but central incident invented or lettering misquotes proof | `C03/C13_FAIL` despite geometry PASS |

## 8. Acceptance and adoption sequence

1. Authorize/select the three v0.09.08 specialist candidates for a clearly identified production scope, without treating the current document review as that authorization.
2. Separately reconcile the CC-01.01 master N16 default via the proposed upstream patch or an approved superseding master revision. Preserve legacy runs/explicit counts.
3. Select and version the real Classies Chronicles logo from cleared identity assets; decide season/chapter title approval states and approved cover cues.
4. Pilot a **new or expressly repaired** four-page sheet with real detail-bearing **1080×1920** pages, then inspect actual sheet pixels, four crops, native evidence, text and characters; stop on any failure.
5. Verify P01 episode and daily layouts (including the coincident case) visually; verify no repeating header on P02/P05 and exact folio grammar.
6. Complete an eight-page pilot across two separately authorized sheet steps. Challenge A appears only at the bottom of P08, and continuity is assessed at P04→P05.
7. Prepare season/chapter cover assets only in their separately authorized scope and validate independently from daily N pages.
8. Run the actual full C01–C22 plus SC098 evidence, resolve rights, approve release if eligible. Do not claim actual deployment or public publishing until verified.

## 9. Preserved vs changed contract crosswalk

| v0.09.05 area | v0.09.08 action |
|---|---|
| §§1–3 authority, Output A, rights, P/H/F truth | Preserved; §§1–4 of master |
| §§4–6 art style, look card, checkpoint | Preserved and extended with chosen single-logo and simplicity; §§5,13 |
| §§7–8 full beat map, page geometry | Preserved; added N8/N4, source detail minimum, fail-fast; §§3,11,14–16 |
| §§9–10 lettering and per-page briefs | Preserved; expanded intro/header/folio/source fields; §§8–12,17 |
| §10.1 Day04 16-page example | Retained historically, no longer used as default narrative across episodes |
| §§11–13 single-step/continuation/prompt compiler | Preserved, added header switch and individual high-detail page assembly; §§18–19 |
| §§14–15 repair/deliverable cadence | Preserved, cover separate and noncompliance blocking; §§20–21 |
| §§16–18 C01–C22, records/finalization | Preserved IDs, broader true evidence, no fictitious PASS; §22 and companion QA |
| §19 preservation crosswalk | Kept as version lineage; this review adds direct v0.09.05→v0.09.08 crosswalk |

**Review conclusion:** The three requested documents are updated as one mutually consistent **candidate set**. The added utility makes geometry and count objectively testable. **No claim of a renderer producing 1080×1920 native pages, owner approval of a specific logo/cover, GitHub commit or franchise-wide adopted count is made.**

**END — CC-03.02 Refactor Review v0.09.08 Candidate**


<!-- END CC-03.02-Comic_Refactor_Review_Conflict_Register_and_Change_Traceability_v0.09.08_Candidate.md -->


---

<!-- BEGIN CC-03.03-Comic_QA_and_File_Manifest_v0.09.08_Candidate.md -->

# CLASSIES CHRONICLES
## CC-03.03 — COMIC QA AND FILE MANIFEST
### `v0.09.08_Candidate` | Four-page pixel enforcement, identity headers, independent covers

| Field | Value |
|---|---|
| Stable logical ID | `CC-03.03 / CC-MCGP-QA` |
| Companion master/review | `CC-03.01` / `CC-03.02 v0.09.08_Candidate` |
| Evaluation scope in this edition | Documentary review, test design, available real November 20 pixel geometry and included utility selftests; **no new images produced** |
| Official gates | Preserve original **C01 through C22** unchanged; new rules are subtests `SC098_*` |
| Scope types | `SEASON_COVER`, `CHAPTER_COVER`, `EPISODE_COMIC`, `DAILY_COMIC` |
| Primary target | Exactly **four complete native/detail-bearing ≥1080×1920, exact 9:16** pages per 2×2 sheet |
| Status caution | `PASS` only if actual evidence supports it; a specification and a synthetic test do not prove a production renderer worked |

## 1. Evidence states and non-equivalence

Every audit entry has an evidence state: `OBSERVED_REAL_PIXELS`, `MEASURED_FILE_BYTES`, `PROVIDER_NATIVE_METADATA`, `VERIFIED_SOURCE_TEXT`, `OWNER_APPROVAL`, `USER_VISUAL_FEEDBACK`, `DOCUMENT_SPECIFICATION`, `SYNTHETIC_TEST_ONLY`, `NOT_RUN`, `NOT_CHECKED`, `NOT_AVAILABLE`, `BLOCKED` or `UNVERIFIED`. Every C-gate/subcheck result is `PASS`, `FAIL`, `BLOCKED`, `NOT_CHECKED` or `NOT_APPLICABLE`. Record inspector, date, artifact path/asset revision and specific evidence.

Important non-equivalences:

- `REQUESTED_PIXELS` **is not** `GENERATED_PIXELS`.
- `EXPORTED_1080x1920` **is not automatically** `NATIVE_SOURCE_1080x1920`.
- A **9:16 whole sheet** **is not automatically four pages of 1080×1920**.
- A liked visual **is not** `GEOMETRY_PASS`, a performed render **is not** `EDITORIAL_ACCEPTED`, and editorial acceptance **is not** rights or release authority.
- A four-cell segmentation **is not** verification of four distinct sequential pages, four approved logos, truthful chronology or exact challenge spelling.
- A season/chapter cover file **is not** part of the daily page count or an episode P01 replacement.

## 2. Run manifest — mandatory machine-readable fields

The manifest should be versioned JSON or another lossless structured format with these mandatory record groups. Use actual values, typed null/status fields for unavailable evidence, and **do not put restricted Challenge C contents in a public manifest**.

**`run`:** `run_id`, `manifest_schema_version=0.09.08`, `selected_master_edition`, `master_adoption_status`, `scope`, `source_id`, `source_revision`, `source_uri`, `source_sha256_if_measured`, `source_provisional_status`, `commission_owner`, `authorized_N`, `page_count_rule` (`DEFAULT_8` / `SHORT_4_EDITORIAL` / `EXPLICIT_N`), `why_very_short_if_N4`, `stepping_mode`, `approved_logo_asset_ref`, `logo_rights_status`, `approved_cover_inventory`, `upstream_count_alignment_status`, `public_challenge_source_reference`, `rights_status`, `publication_status`, `created_at_utc`, `updated_at_utc`.

**`storyboard`:** `locked_digest_if_measured`, `N`, `story_beats[P01…PN]`, source spans, unique causal purposes, expected incoming/outgoing continuity, `challenge_reserved_page=PN`, cover-introduction mode, P/H/F clock/physical state, selected character designs and word-for-word source-critical text map.

**`page_records`:** one per P ID, with `page_id`, `sheet_id`, `cell`, `story_scope`, `page_numerator`, `page_denominator`, `source_scene_spans`, `story_progression`, `header_mode`, `header_height_fraction`, `header_actual_pixel_bbox`, `approved_logo_occurrences`, `series_name_duplicate_occurrences`, `header_text_proof`, `folio_text`, `character_look_reference`, `physical_location`, `modality`, `incoming/outgoing_state`, `challenge_allowed`, `challenge_visible_count`, `actual_page_asset_path`, `asset_revision`, `actual_page_sha256_if_measured`, `provider`, `native_generation_px_if_proven`, `native_generation_evidence`, `source_image_px_if_measured`, `postprocessed_px`, `export_px`, `resampling/transforms`, `exact_ratio_check`, `minimum_detail_check`, `last_mobile_inspection`, `text_accuracy_status`, `continuity_status`, `rights_status`, `editorial_status`, `QA_findings`.

**`sheet_records`:** one per S ID, with `sheet_id`, `revision`, four ordered P IDs, `actual_sheet_path`, `measured_sheet_px`, `page_crop_boxes`, `measured_page_crop_px`, `asset_sha256_if_measured`, `origin` (`DIRECT_SHEET` / `ASSEMBLED_NATIVE_PAGES` / `ASSEMBLED_PROVISIONAL`), `direct_render_provider_size_if_known`, `page_sources` (paths and hash references), `assembly_transform_log`, `header_presence_by_cell`, `challenge_occurrences_by_cell`, `source_vs_export_provenance`, `visual_reference_loaded`, `continuity_checkpoint_id`, `manual_visual_reviewer`, `pixel_validation`, `native_detail_validation`, `editorial_working_acceptance`, `repairs`, `release_eligibility`.

**`cover_records`:** separate one-primary-per-season / one-primary-per-chapter records: `cover_type`, `cover_scope_id`, `cover_asset_id`, `title_text_as_approved`, `title_status`, `selected_logo_asset_ref`, `logo_occurrence_count`, `dominant_symbol`, `secondary_motif_count`, `cover_standalone_page=true`, `included_in_story_N=false`, `actual_asset_path`, `actual_px`, `native_source_detail`, `source_transforms`, `revision`, `rights`, `typography_QA`, `editorial_status` and `release_status`. Chapter covers may depend on accepted chapter title, and season covers on accepted season title, without inventing either.

**`acceptance`:** per-gate C01–C22 matrix, SC098 subcheck matrix, `last_accepted_page`, `last_accepted_sheet`, `next_page_cursor`, `next_sheet_cursor`, `last_accepted_actual_visual_reference`, `current_scene/prop/actor_checkpoint`, `blocked_reasons`, `repair_history`, `final_export_paths`, `persistence_state`, `published_uri_if_verified`.

Store source hashes **only when actually computed**. Separate internal and public manifest projections. If actual source is inaccessible, preserve `NOT_AVAILABLE`, never a plausible-looking synthetic hash.

## 3. Preflight gates before the first render

1. An actual owner-selected source/commission exists: standalone season/chapter cover, episode-level adaptation or daily Output A. Separate review-only tasks from image-generation tasks.
2. The rights and visual-use context is declared; a user-authorized provisional characterization is not the same as release consent.
3. Exactly one designated series logo asset (and its rights) is selected for publication. If absent, record `LOGO_ASSET_MISSING`; do not fabricate an official wordmark. Check that corporate Koware identity is not used as the comic masthead.
4. For daily: `N=8` by selected v0.09.08 default, or `N=4` with an explicit very-short editorial explanation, or another explicitly commissioned multiple of four. Record the upstream N16-versus-N8 adoption conflict until reconciled.
5. For a season/chapter cover: check one accepted scope ID, existing primary cover/revisions, accepted/approved title wording, and full-page geometry. Do not treat it as P01.
6. For story comics: freeze exactly N distinct page purposes, all sourced, final public Challenge A reserved for only PN, complete character/prop P/H/F states and header mode for **P01 only**.
7. Verify renderer size capabilities before choosing **direct** 2×2: if actual direct output will be <2160×3840 or per-cell detail insufficient, **choose four individual 9:16 source pages plus lossless assembly**.
8. Establish `WAIT_FOR_USER` gate: one 2×2 story sheet delivered per generation turn. Any separate season/chapter cover requires cover scope authorization and is not snuck into Sxx.

## 4. Mandatory pixel audit, sheet by sheet

**Programmatic tests** for each source/composited page: file exists and is decodable; image dimensions `W,H`; `W>0,H>0`; exact ratio `16W==9H`; `W>=1080 AND H>=1920` for the real source-detail target. For a sheet: `sheet_width % 2 == 0`, `sheet_height % 2 == 0`; `page_crop_width=sheet_width/2`, `page_crop_height=sheet_height/2`; all four cells exact 9:16 and ≥1080×1920; page-order IDs TL/TR/BL/BR; each extracted crop inside the image; no unintended gutters or labels outside pages. Compare per-page source against assembled crop pixel equality when lossless native-page assembly was used. Record SHA-256 of actual encoded files and optionally decoded pixel bytes, not implied from a filename.

| Actual image | Cropped page | Exact ratio | Min page detail | Structural result |
|---|---|---|---|---|
| 2160×3840 sheet from four genuine 1080×1920 pages | 1080×1920 | PASS | PASS if genuine source/provenance established | Structurally compliant |
| 2880×5120 sheet from four genuine 1440×2560 pages | 1440×2560 | PASS | PASS if source provenance established | Structurally compliant |
| 1080×1920 sheet | 540×960 | PASS | **FAIL** | Not acceptable as final v0.09.08 four-page sheet |
| 1024×1536 sheet | 512×768 | **FAIL** | **FAIL** | Not acceptable; observed Day05 examples |
| 2160×3840 sheet from enlarged 1024×1536 artwork | 1080×1920 | May pass only if aspect reflowed without distortion | **Native proof absent**; likely below target | `REVIEW/BLOCKED`, not automatic pass |
| 2160×3840 sheet with one blank/duplicate page | 1080×1920 | PASS | Potential PASS | **C04/C12/C18 FAIL** |

**Inspect actual rendered content manually:** each cell represents a complete comic page with real panel boundaries; correct character clothes/face, scene order, genuine first-page header only where permitted, proper folio denominator, non-duplicated logo and header title, scene-linked evidence status, no inaccessible restricted matter, no made-up source text. A computer cannot certify these by dimensional checks.

## 5. Header/cover-specific tests

| Test ID | Condition | Fail mode |
|---|---|---|
| `SC098_SEASON_COVER_FULL_PAGE` | One distinct season primary cover; full-bleed exact 9:16, ≥1080×1920; independent of N | Cover missing, inserted as story page, wrong title or format |
| `SC098_CHAPTER_COVER_FULL_PAGE` | One distinct chapter primary cover meeting same dimensions and scope | Reused season poster for chapter without proper purpose or rights |
| `SC098_COVER_SIMPLICITY` | One dominant motif, at most two purposeful secondary motifs; mobile-title readability | Cover is a cluttered cast/location/object montage |
| `SC098_LOGO_IDENTITY` | Real approved series logo once; allowed rights; Koware mark not combined as series logo | Unapproved/improvised logo or repeated wordmark |
| `SC098_EPISODE_P01_HEADER` | P01 header takes **25%–33⅓%** and includes episode title/IDs/one logo plus correct relevant art | Header fraction out of range, extra text, no first-act action |
| `SC098_DAILY_P01_HEADER` | P01 header takes **20%–25%**; includes Chronicle #NN and episode title/IDs/one logo | Repeated series name, extra daily headline in header, no scene |
| `SC098_COMBINED_P01_HEADER` | Episode+Chronicle #01 same P01: **one 25% header** and first-act action | Two stacked header strips/logos |
| `SC098_NO_REPEAT_OPENERS` | P02…PN: no new large opening logo/header; optional small folio allowed | P05 starts a new title cover, story restarts |
| `SC098_FOLIO_GRAMMAR` | `Season 00 - Chapter 05 - Episode 03 - Chronicle #05  01/08` or episode-only form, correct real IDs/N | Wrong denominator, wrong day index, repeated/oversized folio |
| `SC098_P01_STORY_PRESENCE` | P01 body has source-backed scene/act with visible change | P01 is cover-only poster |

Measure geometric header bounds from final art where separable; when visually blended, reviewer estimates/evaluates agreed reserved zone against pixel coordinates and documents method. A scene prop may cross intro-to-story transition but may **not** cross sheet quadrant boundaries.

## 6. Additional SC098 subcheck register (not official C gates)

`SC098_NATIVE_PROVENANCE`, `SC098_PG_EXACT_RATIO`, `SC098_PG_MIN_1080x1920`, `SC098_SHEET_MIN_2160x3840`, `SC098_FOUR_CELLS`, `SC098_ROW_MAJOR_ORDER`, `SC098_FOUR_DISTINCT_BEATS`, `SC098_NO_COVER_ONLY_QUADRANT`, `SC098_SOURCE_VS_UPSCALE`, `SC098_SHEET_ASSEMBLY_IDENTITY`, `SC098_MOBILE_LEGIBILITY`, `SC098_SOURCE_TEXT_PROOF`, `SC098_CHARACTER_ANCHOR_LOADED`, `SC098_NEXT_CURSOR`, `SC098_ONE_SHEET_PER_USER_TURN`, `SC098_NO_EARLY_CHALLENGE`, `SC098_FINAL_CHALLENGE_LITERAL_BOTTOM`, `SC098_REPAIR_PRESERVES_IDS`, `SC098_SEASON_COVER_FULL_PAGE`, `SC098_CHAPTER_COVER_FULL_PAGE`, `SC098_COVER_SIMPLICITY`, `SC098_LOGO_IDENTITY`, `SC098_EPISODE_P01_HEADER`, `SC098_DAILY_P01_HEADER`, `SC098_COMBINED_P01_HEADER`, `SC098_NO_REPEAT_OPENERS`, `SC098_FOLIO_GRAMMAR`, `SC098_P01_STORY_PRESENCE`, `SC098_COVER_OUTSIDE_N`, `SC098_STOP_AND_HANDOVER`.

Record a subcheck only when applicable. An unrendered cover in a daily story-only commission may be `NOT_APPLICABLE` to that single daily sheet but can still be `BLOCKED` for an attempted *full season publication package*. `SC098_NATIVE_PROVENANCE` can remain `UNVERIFIED/BLOCKED` even when the strict file-ratio checks pass. Do not silently upgrade its status to PASS.

## 7. Original C01–C22 gate matrix — unchanged identifiers

| Official gate | What the v0.09.08 test requires |
|---|---|
| `C01_COMMISSION` | Correct authorized scope, page count and stepping, cover scope separation |
| `C02_SOURCE_REVISION` | Exact eligible Output A, version/source proof, no mixed drafts |
| `C03_FIDELITY` | No fabricated scene/case result; cover claims do not imply completed outcomes |
| `C04_PAGE_PURPOSE` | N distinct causally advancing pages; P01 is actual scene plus header |
| `C05_THREE_CLOCKS` | P/H/F calculations and physical scene location separate |
| `C06_CANON_CHARACTERS` | Stable and rights-permitted faces/looks, cast continuity across sheet borders |
| `C07_MISSION_TECH` | Accepted Edubeam/probe/field constraints, no fictitious live connection |
| `C08_ENVIRONMENT` | Accurate and contextual environment; no misleading emergency routing |
| `C09_RIGHTS_CONSENT` | Brand logo, likeness, references and publishing rights demonstrably cleared |
| `C10_ORIGINALITY` | Original cinematic style, clear season/chapter design, no copying; uncluttered cover |
| `C11_PAGE_ASPECT` | Strict exact 9:16 for every page **and** independent full-page cover; actual pixels |
| `C12_PANEL_FLOW` | 2×2 sheet exactly four complete ordered pages, one integrated P01, legible panel order |
| `C13_LETTERING` | Exact source-critical dialogue, header fields and final public challenge typography |
| `C14_VISIBLE_LABELS` | Correct season/chapter/episode/chronicle/folio and nonrepeating single approved logo |
| `C15_CHALLENGE_SOURCE` | Exact eligible public Challenge A retained without change |
| `C16_CHALLENGE_PLACEMENT` | Challenge occurs only on global final PN, once, bottom-most content |
| `C17_NO_LEAKAGE` | Restricted Challenge C answers/keys absent from all public exports |
| `C18_COUNT_INVENTORY` | Exactly N distinct story pages, N/4 sheets; covers outside story N |
| `C19_EXPORT_QUALITY` | Actual source detail ≥1080×1920/page, sheet ≥2160×3840, mobile readable; upscale disclosed |
| `C20_DEPENDENCY` | Correct effective CC-01/02, logo, chapter/season title and last visual sheet; conflicts flagged |
| `C21_RECORD_INTEGRITY` | Real file paths/hashes/revisions/inspection outcomes; persistence not invented |
| `C22_DISCLOSURE` | Public art/cover separated from private continuity and restricted data; provisional status honest |

**Stop/repair:** A C11 or C19 `FAIL` on any page blocks *format-compliant acceptance* of its sheet and the full story package. A C13/C14 or header failure requires localized revision with the same page ID. A C15/C16 fail on final PN blocks finalization; duplicate challenges on earlier P cause repair. C09 rights not established blocks publication even if all pixel gates pass. Keep rejection history.

## 8. File naming and real-count manifest rules

Recommended story scheme (stable source prefix and revisions):

```text
S00_SEASON_COVER_r01.png
S00CH05_CHAPTER_COVER_r01.png
S00CH05E03DAY05_P01_r01.png
S00CH05E03DAY05_P02_r01.png
S00CH05E03DAY05_P03_r01.png
S00CH05E03DAY05_P04_r01.png
S00CH05E03DAY05_S01_P01-P04_r01.png
S00CH05E03DAY05_P05_r01.png
S00CH05E03DAY05_P06_r01.png
S00CH05E03DAY05_P07_r01.png
S00CH05E03DAY05_P08_r01.png
S00CH05E03DAY05_S02_P05-P08_r01.png
S00CH05E03DAY05_COMIC_MANIFEST_v0.09.08.json
```

These are **conventions**, not a claim those files currently exist. Never fill a missing example file path with an imaginary active link. In actual N4 include only P01–P04 and S01; in N16 include through P16 and S04. A cover may be retained from prior production by recording its genuine accepted asset ID; do not silently regenerate one cover per day. The final story asset inventory should contain **N separate page artifacts**, **N/4 distinct 2×2 sheet artifacts**, and **zero cover-only story pages**. Independently scoped season/chapter covers add up to one primary each when those full collection packages are commissioned.

## 9. Supported utility, limitations and runtime instructions

The companion `cc03_collage_geometry_audit_v0.09.08.py` provides selftests, `inspect` and `assemble`. Its file-pixel checks verify strict ratio/minimum dimensions, four-cell boundaries, readable file presence, unique page inputs, lossless assembly, and actual output sizes. It cannot determine character identities, scene truth, editorial authority, whether 1080×1920 detail was native at the AI provider, dialogue correctness, logo approval, public rights or mystery continuity. Human inspection and provider metadata are mandatory complements. A synthetic 2160×3840 assembly selftest does **not** certify a generated story page.

Use the utility on each **actual** sheet before accepting its cursor. On an error leave `next_sheet_cursor` unchanged and record `REPAIR_REQUIRED` or `RESOLUTION_BLOCKED`. For source provenance populate true native dimensions only when known from source-generation output rather than a postprocessing guess. Do not automatically mark a previously illustrated S01 acceptable because it has been resized. No web preview alone is a native-quality audit.

## 10. Per-sheet workflow and consolidated reporting

**Before Sxx:** lock source and four unique next P IDs; fetch actual last accepted reference and checkpoint; validate rights/next cursor; select header mode on P01 only; reserve terminal challenge to true PN; preselect native-page assembly if direct provider not proven. **During render:** produce exactly the current four pages and one composite, with source-backed captions; do not draw season/chapter cover in an unsolicited story continuation. **After render:** audit the real file sizes/crops and native metadata; inspect actual cropped page text and acting; compare outgoing/incoming scene; record PASS/FAIL with evidence, repair same IDs or working-accept and STOP. **At global PN:** proof full exact challenge once, collect final C01–C22 and SC098 matrix, accepted files/hashes and rights/publish blockers. Only on a specifically commissioned full season/chapter package include their separately accepted full-page cover assets in the final package.

A rendered page should not be treated as a verified repository commit or published image. Keep interim private manifest concise during stepped rendering and issue one consolidated report on completion or a material blocking failure; no automatic CC-04 video/media production.

## 11. What has actually been checked for this specification revision

| Check | Result and evidence | Limitation |
|---|---|---|
| Public v0.09.05 trilogy and proposed upstream count patch | Read as webpage text and compared | Does not establish GitHub file bytes locally or adoption |
| Four real Day05 collage PNG files available here | Measured **1024×1536**, equal cells 512×768 | Confirms format failure, not every artistic defect |
| Strict baseline math | `1080×1920` page → `2160×3840` sheet; `1024×1536` → `512×768` cell | Arithmetic only |
| v0.09.08 textual refactor | All six owner requirements addressed in three connected documents | Actual renderer compliance not yet tested |
| New v0.09.08 real eight-page comic run | `NOT_RUN` | No C01–C22 global PASS claim |
| New season/chapter cover generation | `NOT_RUN` | User request is **prompt refactor**, not image creation |
| Actual authorized logo asset/rights | `NOT_VERIFIED` | No official approval claimed |
| Upstream master count amendment applied to GitHub | `NOT_RUN` | Proposed patch supplied separately |
| Public release | `NOT_AUTHORIZED` | No commit/publication action |

**Final QA status:** `SPECIFICATION_REVISED`; `NATIVE_PAGE_9x16_MIN_TARGET_ENFORCED_IN_TEXT`; `AUTOMATED_VALIDATOR_INCLUDED`; `REAL_PRIOR_COLLAGES_GEOMETRY_FAIL`; `NEW_8_PAGE_RENDER_NOT_RUN`; `COVER_RENDER_NOT_RUN`; `OWNER_ADOPTION_PENDING`; `UPSTREAM_ALIGNMENT_PENDING`; `PUBLIC_RELEASE_NOT_AUTHORIZED`.

**END — CC-03.03 Comic QA and File Manifest v0.09.08 Candidate**


<!-- END CC-03.03-Comic_QA_and_File_Manifest_v0.09.08_Candidate.md -->
