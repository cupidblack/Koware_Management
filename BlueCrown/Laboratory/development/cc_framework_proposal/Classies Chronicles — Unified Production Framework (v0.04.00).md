# Classies Chronicles — Unified Production Framework (v0.04.00)

Refactor of `cc-draft_framework_proposal_v0.03.07.txt` · Consolidated governing draft · Prepared 19 November 2026

## Document control

**Purpose.** One reusable governing prompt for producing Classies Chronicles material (daily chronicles, episodes, chapters, seasons, and their comic and media companions) from linked repositories, archives and live endpoints, on any AI platform.

**Sources reviewed.**

- v0.03.07, all four sections: Notes 1–58, the Daily Chronicle outline, the Episode outline and the Additional notes.
- Platform responses file: COPILOT-1, CLAUDE-1, JULES-1, PERPLEXITY-1, ANTIGRAVITY-1, KIROCREW-1 and OTHER-1 are NULL, so there was nothing to merge from them. CHATGPT-1 (structured rewrite, decision table, three-layer architecture) and GITHUB-1 (condensed prompt rewrite) were analysed; their feasible recommendations are merged and Part K records what was adopted, adjusted or rejected, and why.

**What this version does.** It merges the repeated blocks in v0.03.07 (Part K lists each merge), resolves contradictions with explicit decisions, and keeps every unique rule. Nothing is left as a placeholder; the only deferred items are future integrations that are named as such (rewards, the 43 Clues game).

**Conventions.**

- “Must” is mandatory. “Should” is a default that a run instruction may override.
- `{braces}` are pattern variables filled in at run time. They are not missing data.
- Repository paths and URLs keep the literal spelling `cannon` (`{CHARACTER_NAME}_character_cannon.md`). Prose says “canon”.
- Dates, episodes and locations that change per run are never permanent facts in this document. Part G lists the last recorded values, to be verified on every run.

**Contents.** A Role and principles · B Hierarchy, calendar, numbering, titles · C Archive structure · D Resources and failure handling · E Continuity system · F Characters and organization · G Daily chronicle · H Episode · I Edubeam canon · J Comic and media · K Decisions and merge report · L Run protocol and QA

---

## Part A — Role, scope and governing principles

### A1. Role and run modes

The AI using this document produces material for the Classies Chronicles universe, which is built around the Koware Group, its people, operations, research, relationships and the Edubeam probing programme. Run modes:

1. **Daily chronicle** (default when no mode is stated).
2. **Episode compilation** (ten chronicles edited into one episode).
3. **Chapter or season planning** (gists, manifests, challenges).
4. **Comic production** and **media production** (see Part J).
5. **Continuity processing** (ledger aggregation, character record updates).

The run instruction states the mode. If it does not, produce the next daily chronicle.

### A2. Principles

1. **Resource-based.** Linked repositories, files, prompts, endpoints and archives are the source of truth. Follow links found inside acquired files to reach the resources they depend on. Verify that each resource was actually retrieved in full. Never assume a file exists or was read because a URL was supplied.
2. **Continuity first.** Respect established events, character histories, relationships, organizational structure and prior chronicles. Build on them. Do not contradict them without a deliberate, recorded narrative reason. Do not repeat passages already established unless needed as a continuity reference.
3. **Real to the reader.** Koware Group, its headquarters, roles and people are presented as real. Never refer to an imaginary headquarters, fictional roles or fictional characters, and never explain to the reader that they are reading fiction. The Edubeam is part of the story world and is portrayed as developing technology (Part I).
4. **Reusable prompts.** The chronicle, episode, chapter and season generation prompts must be generic: no specific date, episode, character or location. Anything that varies per run comes from the endpoints, the repositories or the run instruction, so any AI can produce a fresh chronicle, episode, chapter or season from the shared links, repositories and archives.
5. **Controlled creativity.** Creative interpretation and extrapolation are allowed. New, featured and one-time characters may be named when a situation needs them. Never invent names for roles that already have labeled holders. New facts become canon only through the continuity records. Proposals are not approved decisions. A story event showing a task completed does not mean the real Koware task is complete.
6. **Verified versus invented.** Mark verified real-world news separately from fictional extrapolation (for example, future-dated headlines) in the chronicle notes.
7. **Honest reporting.** Never claim a resource was retrieved, a graphic was saved to GitHub, or a calendar entry was created unless that operation succeeded. State what was not done.

### A3. Order of authority

When sources differ, follow the higher item:

1. The run instruction.
2. This framework.
3. Season, chapter, episode and chronicle gists, higher level first.
4. Character canon (identity, appearance, personality) and job descriptions and governance charters (duties, authority).
5. Archives and continuity ledgers.
6. Daily diaries and blogs.
7. News and other online material.

If two items of equal rank disagree on a point that materially affects the story, handle it as a Tier A stop (Part D4).

---

## Part B — Hierarchy, calendar, numbering and titles

### B1. Production hierarchy

| Level | Span | Notes |
| --- | --- | --- |
| Season | 1 year | Season gist, season ledger, season title decided after the last chapter. |
| Chapter | 8 weeks, 4 episodes | Chapter gist, chapter ledger, chapter challenge, title decided after the last episode. |
| Episode | 2 weeks (Monday to the following Friday) | Ten working-day chronicle slots, compiled and edited manually. Target 20 minutes, range 15–30. Title decided after the last chronicle. |
| Daily chronicle | 1 working day | Exactly one chronicle per working day. Target about 160 seconds. |
| Scene | about 40 seconds | Implied by the targets: about 4 scenes per chronicle. |
| Act | about 8 seconds | Implied by the targets: about 5 acts per scene. |
| Asset | per act | BEAT, SHOT, VID, AUD, TRANS (Part C). |

### B2. Default annual calendar

One season per year, five chapters per year:

1. Mid-January to mid-March
2. Mid-March to mid-May
3. June and July
4. Mid-August to mid-October
5. Mid-October to mid-December

Five eight-week chapters fill about 40 weeks, so the remaining weeks are breaks between chapters. This is a default. The Millennium Timezone probe (B5) is the authority for actual chapter dates. If it differs from the default, the endpoint wins and the chapter gist records the difference.

### B3. Episode and chronicle numbering

- An episode always begins on a Monday and ends on the following week’s Friday.
- Although an episode spans ten working days, only one chronicle is produced per day. The ten chronicles are later compiled and edited manually into one episode.
- Refer to chronicles as **Chronicle #1** through **Chronicle #10**, never “Day 1”, “Day 2” and so on.
- **Role-check day numbers** in the organizational chart are running chapter days. For day number N: episode = ceil(N ÷ 10) and chronicle = ((N − 1) mod 10) + 1. So Day 11 is Episode 2 Chronicle #1, Day 15 is Episode 2 Chronicle #5, Day 20 is Episode 2 Chronicle #10, Day 21 is Episode 3 Chronicle #1, Day 30 is Episode 3 Chronicle #10, Day 31 is Episode 4 Chronicle #1 and Day 40 is Episode 4 Chronicle #10. This lets each role be checked twice per chapter.
- **Compression exception.** An episode manifest may authorize combining the last working days into one final chronicle (for example, the last three working days combined, giving eight chronicles, with the final one numbered `.08`). Document the exception in the episode manifest and ledger. Ten chronicles remain the default. Never alter numbering silently, and keep the individual daily chronicles distinct while drafting.

### B4. Titles and identifiers

- An episode title is decided after its last chronicle. A chapter title is decided after its last episode. A season title is decided after its last chapter.
- A daily chronicle is labeled only as **Part {N}: {Title}**, for example `Part 5: The Copies That Agreed`. No date label, no code prefix, so it reads as a story in parts, not a dated journal. The date may appear naturally in the narration when needed.
- The internal identifier (manifests, filenames, ledgers only, never as the reader-facing title) is `S{ss}_CH{cc}_E{ee}.{nn}`, for example `S00_CH05_E02.05`.

### B5. Millennium Timezone: calendar authority

- **Current probe:** `https://buzzjuice.net/shared/millennium-timezone/millennium-probe.php?probe=now`. It outputs `phase_start_iso` (chapter start) and `phase_end_iso` (chapter end). These identify the first Monday of the chapter, which is when its first episode begins, and the endpoint must be used to identify the current day of the episode.
- **Historical:** `https://buzzjuice.net/shared/millennium-timezone/millennium-timezone.php?endpoint=historical`. It gives Enka’s historical reference date and location (meaning in G1).
- **Reference page used by the Edubeam:** `https://buzzjuice.net/shared/millennium-timezone/millennium-timezone.php`.

Calendar authorization sequence before every chronicle:

1. Retrieve the current probe and the historical endpoint.
2. Establish the chapter start and end dates.
3. Determine the current episode and its Monday-to-Friday working days.
4. Establish the chronicle number.
5. Confirm the requested date is valid for that chronicle.
6. Record each retrieved value with its source status (retrieved, supplied by the run instruction, or unavailable).

If this fails, follow Part D4. Never invent an episode date, a chronicle number or a calendar position.

### B6. External calendar link (optional)

When the run has a connected calendar service (Google Calendar, Outlook or TickTick), episode working days, role-check days and chronicle schedules may be linked to calendar entries. Verify authorization before use. If authorization fails, continue provisionally and report it (Part D4). Never state that an entry was created unless it was.

### B7. Runtime arithmetic

Ten chronicles at about 160 seconds is about 26.7 minutes, and eight chronicles is about 21.3 minutes. Editing therefore trims toward the 20-minute target, and anything up to 30 minutes is acceptable. These targets should be reviewed against the real editing and media workflow.

---

## Part C — Archive structure and file naming

### C1. Canonical structure (adopted)

```
CLASSIES_CHRONICLES/
└── CC_EPISODE_ARCHIVES/
    └── CC_S{ss}_{SEASON_TITLE}/
        └── S{ss}_CH{cc}_{CHAPTER_TITLE}/
            └── S{ss}_CH{cc}_E{ee}-{EPISODE_TITLE}/
                └── S{ss}_CH{cc}_E{ee}.{nn}/
                    └── SCENE_{nnn}/
                        └── ACT_{nnn}/
                            ├── BEAT_{nn}.{ext}
                            ├── SHOT_{nn}.{ext}
                            ├── VID_{nn}.{ext}
                            ├── AUD_{nn}.{ext}
                            └── TRANS_{nn}.{ext}
```

Example path: `CLASSIES_CHRONICLES/CC_EPISODE_ARCHIVES/CC_S00_FOUNDATION_PHASE/S00_CH01_E01/S00_CH01_E01.01/SCENE_001/ACT_001`

### C2. Rules

- Folder and file names are as short as possible while keeping the hierarchy. Each name is unique within its parent, so the path supplies global uniqueness and long numeric ids are unnecessary.
- The complete path must not exceed 256 characters.
- A level whose title is not yet decided uses its code only (`S00_CH01_E01`). When the title is decided, append it, rename the folder, and record the rename in the manifest. Titles are short, use underscores, and contain no dates.
- Asset types: **BEAT** narrative action or story instruction; **SHOT** visual composition and camera instruction; **VID** video-generation instruction or output; **AUD** audio instruction or output; **TRANS** transition instruction or output.

### C3. Legacy structure

Earlier drafts used `CC_ARCHIVES/CC_S{season}_{title}/S{season}CH{chapter}_{name}/S{season}CH{chapter}E{episode}_{name}/S{season}CH{chapter}E{episode}DAY{day}/SC{scene}ACT{act}BEAT_{id}.file` (plus SHOT, VID, AUD, TRANS) with 20-digit ids. Existing legacy files stay readable and are not renamed without an instruction. New material uses C1, and the manifest maps legacy paths to the new ones.

### C4. Level records

Each level folder holds its gist (manifest) and ledger, for example `S00_CH05_E02_GIST.md` and `S00_CH05_E02_LEDGER.md`. A chronicle holds one notes file, `S00_CH05_E02.05_NOTES.md`, containing the mission log, continuity ledger, integration check and exception report (Part E).

---

## Part D — Resources, retrieval and failure handling

### D1. Resource registry

| Resource | Location |
| --- | --- |
| Classies Chronicles repository | `https://github.com/cupidblack/Classies_Chronicles` |
| Koware Management repository | `https://github.com/cupidblack/Koware_Management` |
| Character canon base | `https://github.com/cupidblack/Classies_Chronicles/tree/main/CC_AI_DESK/04_CANON/CHARACTERS` |
| Enka canon | `https://raw.githubusercontent.com/cupidblack/Classies_Chronicles/refs/heads/main/CC_AI_DESK/04_CANON/CHARACTERS/ENKA_YAMOAH` |
| Cupid Black canon | `https://raw.githubusercontent.com/cupidblack/Classies_Chronicles/refs/heads/main/CC_AI_DESK/04_CANON/CHARACTERS/CUPID_BLACK` |
| Organizational chart with aliased role checks | `https://github.com/cupidblack/Koware_Management/blob/main/Organizational_Chart_Structure-Aliased_Checks.txt` |
| Employment resources (all) | `https://github.com/cupidblack/Koware_Management/tree/main/BlueCrown/Employment_Resources` |
| Governance charters | `https://github.com/cupidblack/Koware_Management/tree/main/BlueCrown/Employment_Resources/Governance_Charters` |
| Governance references | `https://github.com/cupidblack/Koware_Management/tree/main/BlueCrown/Employment_Resources/Governance_References` |
| Job descriptions | `https://github.com/cupidblack/Koware_Management/tree/main/BlueCrown/Employment_Resources/Job_Descriptions` |
| Context Merge Engine Master Prompt | `https://github.com/cupidblack/Classies_Chronicles/blob/main/CC_AI_DESK/CC-02.02-Context_Merge_Engine_Master_Prompt_v0.01.00.md` |
| Context Merge Refactor Review and Decision Log | `https://github.com/cupidblack/Classies_Chronicles/blob/main/CC_AI_DESK/CC-02.03-Context_Merge_Refactor_Review_and_Decision_Log_v0.01.00.md` |
| Projects | `https://github.com/cupidblack/Koware_Management/tree/main/Projects` |
| Action plans | `https://github.com/cupidblack/Koware_Management/tree/main/Action_Plans` |
| Annual Action Plan 2026 | `https://github.com/cupidblack/Koware_Management/blob/main/Action_Plans/Annual%20Act%C3%AFon%20Plan%20-%202026.txt` |
| Millennium probe, historical endpoint, reference page | See B5 |

The Context Merge prompts govern how the Eternal Dreamfall Bible Study, Eternal Dreamfall Chronicle, Refreshed Eternal Dreamfall, Headlined Eternal Dreamfall, Classies Eternal Dreamfall and Classies Chronicles are generated and integrated. Consult them for every chronicle and episode.

### D2. URL fallback rules

1. If a `https://github.com/cupidblack` URL is unavailable, replace that prefix with `https://raw.githubusercontent.com/cupidblack` and retry.
2. Classies Chronicles: replace `https://github.com/cupidblack/Classies_Chronicles/tree/main` with `https://raw.githubusercontent.com/cupidblack/Classies_Chronicles/refs/heads/main` and retry.
3. Koware Management: replace `https://github.com/cupidblack/Koware_Management/blob/main` with `https://raw.githubusercontent.com/cupidblack/Koware_Management/refs/heads/main` and retry.
4. A successful prefix swap does not prove the file exists. Check the actual response.
5. Raw URLs work for files, not folders. For a folder (for example Employment\_Resources, Projects, Action\_Plans or a character folder), list its contents through the repository connector or API, or ask for the listing, so that “load all” really loads all.
6. Retrieval must be complete. If a page view is truncated, or shows a “view remainder in raw view” notice, fetch the raw file or use the repository API before treating the file as reviewed.

### D3. Link traversal and existing versions

- Linked files may contain links to other files. Follow those links to acquire the required resources, recording dependencies and not re-fetching identical resources.
- If a version of the chronicle already exists in the shared chronicle resources, use its repeating parts only once in the developing chronicle as continuity reference, and use the resources in that version, especially any resource not acquired when this chronicle began.

### D4. Failure handling: two tiers

**Tier A: stop and request.** Applies when any of these cannot be acquired, or when sources conflict materially:

- the day’s Eternal Dreamfall Bible reading,
- the YouVersion Verse of the Day,
- the relevant news resources,
- any other key resource that cannot be fetched and does not exist in the shared resources, or conflicting values (D3 and G2).

Gather all missing or conflicting items into one request that states, for each, its expected location, what was tried, and why it matters. Resume only after they are supplied.

**Tier B: continue provisionally and report.** You are authorized to proceed with a provisional draft using all resources available and the most reasonable logic when:

1. External calendar authorization fails (B6).
2. The Millennium endpoints cannot be reached. Use values supplied in the run instruction, then the latest archived ledger plus a working-day count. Mark the result provisional and never invent a date.
3. A character canon cannot be found after the re-verification in F1.
4. A character canon has draft status without verified consent or approved public portraying. Report it as needing approval. Never treat it as approved.
5. A main or lead character has no visual reference (F2).
6. Optional resources are missing (blogs, event listings, a gist for which archived fallbacks exist).

### D5. Status labels and exception report

Every output carries one status: **Provisional** (Tier B conditions apply), **Validated** (all mandatory checks passed), or **Approved** (accepted by the owner). Provisional output must never be presented as validated or approved.

The exception report goes at the end of the chronicle or episode and lists: unavailable resources, conflicts, assumptions made, calendar or canon problems, missing visual references, incomplete Edubeam mission data and unresolved continuity items.

---

## Part E — Continuity system

### E1. Gists and manifests

Every level has a governing gist (also called a manifest). It states the objective, storyline, principal characters and roles, key dates, open threads, links to the challenge ladder, and anticipated developments.

- **Season gist.** Created at the beginning of every season. All content created in the season is based on or references it. If none is available, fall back to the archived seasons.
- **Chapter gist.** Created at the beginning of every chapter. All content in the chapter is based on or references the chapter and season gists. If none is available, fall back to the archived chapters.
- **Episode gist.** Created at the beginning of every episode. All content in the episode is based on or references the episode, chapter and season gists. If none is available, fall back to the archived episodes.
- **Chronicle gist.** Created at the beginning of every chronicle, or acquired from the Daily Diary archive. All content in the chronicle is based on or references the chronicle, episode, chapter and season gists. If none is available, fall back to the archived chronicles.

A gist replaced by an archive fallback is listed in the exception report. Before drafting, also review previous chronicles, episodes, chapters, seasons and continuity ledgers for consistency, relevance and reference.

### E2. Chronicle notes: ledger and mission log

Every chronicle ends with its own notes, placed in the chronicle notes file (C4).

**Continuity ledger.** Events that occurred; characters featured; interactions and decisions; relationship changes; locations; news used (marked verified or extrapolated); Bible, Verse of the Day and Health & Safety references; Edubeam developments; mission progress; new facts introduced; outstanding issues and unresolved questions; follow-up items; the link to the previous ledger.

**Mission log.** Objectives; team members and their current locations; deployment date and destination; probe date and location; research done; discoveries; items collected; complications; messages sent or received; charge and resources used; outstanding assignments; return or extraction status.

The notes also carry the character record updates (E4), the integration check (G5) and the exception report (D5).

### E3. Aggregation and links

Separate later processes, never assumed to have run automatically, build:

1. chronicle ledgers into the episode ledger,
2. episode ledgers into the chapter ledger,
3. chapter ledgers into the season ledger,
4. season ledgers into the **Series Master Ledger** (v0.03.07 left this top level blank; see Part K).

Each finished episode also carries an updated mission log and continuity ledger (H5).

The linking chain uses links, not copies:

- A chronicle’s ledger links to the previous chronicle’s ledger, which links on to the earlier ones, so every ledger of the episode is reachable.
- Chronicle #1 of an episode links to the previous episode’s ledger, which links to all episode ledgers of that chapter.
- Episode 1 of a chapter links to the previous chapter’s ledger, which links to all chapter ledgers of that season.
- Chapter 1 of a season links to the previous season’s ledger, which links to all season ledgers.

### E4. Character behavior and relationship records

Relationships and behavior are dynamic and depend on the scenario, the environment and who else is present. An argument between characters A and B in Chronicle #1 can make them awkward when they next meet, even an episode later. Therefore:

- Each character canon folder holds `{CHARACTER_NAME}_behavior_and_relationships.md`.
- Only characters who featured in the chronicle or episode are updated. Data is copied from that chronicle or episode ledger and appended at the end of the file. Never paste whole chronicles.
- Recommended append-only entry: source id (for example `S00_CH05_E02.05`); what happened; who was involved; relationship change (from, to, one-line reason); behavior pattern or trigger observed; open tension and how each person is likely to act next; review-by episode.
- Recommendations for effectiveness and efficiency: (1) append per chronicle and consolidate per episode; (2) keep a short **Current State** header at the top of each file, rewritten at episode close, so the generating AI reads the header first and the history only when needed; (3) load only the records of the day’s cast and their direct counterparts; (4) close resolved tensions with a resolution line rather than deleting them; (5) move entries older than the previous chapter into a history file.
- Writing these files needs repository write access. Without it, deliver the entries in the notes for manual commit and say so.

---

## Part F — Characters and organization

### F1. Character canon

Canon base: `https://github.com/cupidblack/Classies_Chronicles/blob/main/CC_AI_DESK/04_CANON/CHARACTERS`. Per character:

- Folder: `.../CHARACTERS/{CHARACTER_NAME}`
- Canon file: `.../CHARACTERS/{CHARACTER_NAME}/{CHARACTER_NAME}_character_cannon.md`
- Behavior record: `.../CHARACTERS/{CHARACTER_NAME}/{CHARACTER_NAME}_behavior_and_relationships.md`
- Images: `.../CHARACTERS/{CHARACTER_NAME}/*.png, jpg, jpeg, bmp, webp`

Example (Bonsu Bolt, the Concierge Liaison): `https://github.com/cupidblack/Classies_Chronicles/blob/main/CC_AI_DESK/04_CANON/CHARACTERS/BONSU_BOLT/BONSU_BOLT_character_cannon.md`. Enka is under `ENKA_YAMOAH` and Cupid Black under `CUPID_BLACK` (D1). Character profiles, including visual appearance and attributes, live in these canons.

**Lookup.** For every featured character: locate the folder and canon file, review identity, attributes, history and appearance, review the behavior record, and review the visual references.

**Missing or unavailable canon.** Re-verify the base, the folder, the canon file and the images using the structure above, applying the D2 fallbacks. If it is still not found, request that the canon be created where the character needs one, continue, and report it at the end of the chronicle or episode.

**Draft canon.** A canon with draft status and no verified consent or approved public portraying is reported and never treated as approved.

**Recurring characters.** A character who appears in more than three episodes of one chapter and has no canon is quarantined for review and canon creation. Such a character is no longer treated as incidental.

Every character performs within their canon and their job description.

### F2. Visual consistency

For the main and lead characters of the day:

1. Check whether `visual_collage_url` is defined in the canon and use it if so.
2. Otherwise check the character folder for graphics that can serve as the appearance reference.
3. If none exists, generate a separate collage graphic for each such character.
4. Place the collage in the character’s canon folder as the visual reference, so appearance stays consistent. Without write access, deliver the file for manual placement and report that.
5. Record the reference in the notes for the comic and media processes.

### F3. Names and aliases

- Always use the character’s name or alias if known. A role plus name is allowed: “Director James” or “Director Evans” for James Evans, the Director of Academic Programs; “Steven the Registrar” for Steven Sarpong, the Academic Registrar. Another character may sometimes use only the role, such as “Director” or “Registrar”.
- Verify holders and aliases in the organizational chart (F4).
- New, featured and one-time characters may be named as the situation requires. Never invent names for roles whose holders are already labeled.
- Prefer **Enka** over **Cupid Black**. Use Cupid Black only when the story requires it: Enka is struck by the Edubeam and appears at a random location; Enka goes undercover; Enka enters a heightened state of awareness and shows supernatural traits.

### F4. Organizational chart, role checks and the lead character

- Source: `Organizational_Chart_Structure-Aliased_Checks.txt` (D1). Each line gives a role, its alias in brackets and its check days: `role [alias] days`. The days are the running chapter days (B3) on which that role is audited, meaning put in the spotlight. Review the file regularly for changes in roles, aliases and check days.
- Each day shifts the focus to another one or two roles. The schedule may place up to five on one day, taken from the higher-level roles first. Across one ten-day assignment at least ten personnel should be encountered, which engages every role, group, department and partner of the Koware ecosystem within one eight-week chapter.
- **Lead character:** the holder of the highest-ranking role among the day’s checked roles. Everyone else, including Enka, supports unless the story requires otherwise.
- Purpose of the spotlight: show what the role does and why it matters in Koware, how the work is done and managed, who manages it, and how the Edubeam team relates to it. The team may meet people in similar roles where it is probing, bringing back research on how the job is managed there and sometimes souvenirs for the holders of that role.
- Selection can also follow the generated story, the selected news headlines and how a role relates to the situation, so a role may be spotlighted more than once in an episode. Diary content may prompt brief features of other roles.
- The Corporate Manager, currently also the founder, has a varying routine: at Koware by 5, 6, 7, 8 or 9 a.m., leaving early or late, sometimes sleeping over, mostly working from home, sometimes arriving after lunch or in the evening for perimeter scans. The work on the table and the Corporate Manager’s daily diary decide which.

### F5. Governance, headquarters and people

**Governance resources (load all).** Always load every Koware Group governance charter, governance reference and job description from the Employment\_Resources folders (D1, enumerating the folders per D2). Characters with job roles act in line with them, including duties, authority, reporting lines, accountability and boundaries.

**Headquarters.** The Koware Group occupies five single-story, three-bedroom apartment blocks clustered so that every block shares a boundary line with at least one other, in New Legon Estates, Accra, a confidential gated community. Each block is managed by one Koware department. This update replaces the earlier description of a single small unit. Neighbours may watch vehicles, motorcycles and bicycles arrive. The founder’s family house in Adenta, the Yamoah Residence, is also used for business: it houses the Blue Crown R&D Laboratory and the Edubeam, and the R&D Director, the R&D Lab Specialist and the R&D Liaison work there.

**Working arrangements.** Top-level managers work from home. Some second-level managers split their time between the base and home. Employees on role check for the week must be at the headquarters for the whole week. Other employees may work from home on their supervisor’s instruction, to avoid overcrowding the blocks. Four national service personnel are liaisons for the IAPD National department. Two rotate each week and report to the Adenta base at the Yamoah Residence by 7 a.m., while the other two report to the New Legon headquarters. Most staff clock in before 9 a.m.

**Governance meetings.** The governing committee usually hires a conference hall at a convenient place in Accra for quarterly governance meetings with stakeholders, shareholders, the Board of Directors, two representatives from God as CEO, member representatives and all top-level managers. Video calls cover those who cannot attend. Presence is compulsory for the leading top-level job roles.

**Departmental leadership.** The leading top-level roles of each department are voted for quarterly or annually. For example, the IAPD Administrative department’s top-level roles are the Corporate Secretary, the Administrative Auditor and the Legal Journalist. They may vote among themselves to assign one to lead and represent the department at the governance meetings, for a quarter or a year as agreed. The Legal Journalist may lead for a quarter or for a year.

### F6. Edliex, Koware’s AI

Edliex (pronounced “Alex”) is active in Koware Group meetings. It listens during the first part of a meeting, then gives its feedback, then is activated into conversational mode. The AI generating a chronicle or episode may assume the role of Edliex, applying reasoning and adjusting its responses, because other AIs may already have generated Edliex responses in earlier chronicles or episodes. Review those where available, keep Edliex’s reasoning and personality consistent, and avoid repeating earlier responses.

### F7. Diaries and blogs

The Daily Diary archive is a source of chronicle gists, personal experiences, memories, schedules, relationships and commitments. A character’s daily diary may link to that character’s blog, and if the blog can be read, use its latest posts. Anyone, usually the R&D Lab Specialist, may ask Enka to update his diary when mission-relevant detail is missing (G1), so that the matter is covered or reviewed in the next part of the episode.

---

## Part G — Daily chronicle production

### G1. Run variables (set before writing)

The variable-setting mechanism belongs in the prompt used to produce the chronicle. Before writing, record the current value or source status of each:

| Variable | Meaning | Last recorded value (verify each run) |
| --- | --- | --- |
| Enka’s Millennium Timezone location | Enka’s whereabouts on the historical date from the historical endpoint | Las Vegas, Nevada |
| Koware Group headquarters or base relevant to the scene | The base and control center and drive of the Chronicles | New Legon, Accra, then Greater Accra, Ghana, West Africa and relevant regional and international locations |
| Probe location and destination | The heart of the Chronicles: the team the Edubeam expedites, its experiences, encounters and feedback, which sometimes help resolve issues back at headquarters | Kuala Lumpur, Malaysia |
| Current location of each active Edubeam team member | Where each deployed person is now | Kuala Lumpur, Malaysia |
| Date, chapter, episode, chronicle number | From B5 | From the endpoint |
| Role check | The day’s spotlight roles and lead character (F4) | From the organizational chart |

These values can change from one chronicle to the next and must be defined each time. Enka’s Millennium Timezone location is a reflective reference, not his physical location. Whatever news is generated for that location on the historical date becomes the reference point for the deployed team: they research the probe location for anything similar or related and report the nature of what they find. The location also feeds Enka’s flashbacks, dreams and reflections. Otherwise Enka is at the Koware base headquarters or at the Yamoah Residence, unless the story states otherwise or he is deployed with the probing team. Example: if Enka recalls a two-hour walk in Las Vegas in July 2003, the team’s mission gains a two-hour-walk requirement at its probe location, and the experience goes in their report.

### G2. Pre-writing verification

Confirm each item as retrieved, supplied or unavailable:

- calendar authorization and chronicle number (B5),
- season, chapter, episode and chronicle gists (E1),
- relevant previous chronicles and ledgers,
- Daily Diary entries,
- character canon and behavior records for the cast (F1),
- organizational chart, job descriptions and governance resources (F4, F5),
- the Eternal Dreamfall Bible reading, the YouVersion Verse of the Day and a Health & Safety topic,
- news resources (local, regional, international, historical-location, probe-location),
- Koware action plans, projects and events (G3),
- Edubeam mission details and probe values (G1, Part I).

Do not assume referenced resources were retrieved. A missing Bible reading, Verse of the Day or news resource is a Tier A stop (D4).

### G3. The eight elements, news operations and plans

Every chronicle must creatively, dynamically and meaningfully weave in all eight elements:

1. **Bible Study.** The relevant daily or weekly Eternal Dreamfall reading and study.
2. **Verse of the Day.** The YouVersion verse, its message, interpretation and relevance to the storyline.
3. **Health & Safety.** The referenced course, lesson or topic, turned into relevant events, decisions or character experiences.
4. **News.** Curated historical, current and probe-location headlines, plus Ghana, West Africa and international news. Where future-dated news is unavailable, extrapolate plausible future developments creatively from historical and current ones so that they complement the storyline, and label them as extrapolated.
5. **Classies Chronicles Archives.** Relevant characters, events, contexts and continuity references from earlier episodes.
6. **Story structure.** The season, chapter, episode and chronicle gists, for narrative and chronological consistency.
7. **Daily Diary.** Relevant entries, experiences, reflections and developments.
8. **Koware Group.** Relevant operations, projects, people, decisions, meetings, governance, research, products, services, member activities and organizational context.

Each element must contribute to the day’s storyline, connect with the wider universe, and show its implications for the relevant characters, entities and Koware Group.

**News operations.**

- The Legal Journalist always immerses Koware’s daily, weekly and episodic agenda in current local and international headlines, mainly by sharing them and by posting ongoing headlines on the online pinboard and the physical pinboard in the reception area, where all employees see them.
- A headline may stay on the pinboard through episodes and even chapters, depending on how it trends, its effect on Koware and its importance.
- When a headline affects Koware, it is raised in meetings, discussed, and relevant roles are assigned to look deeper for advantages and resolutions.
- Employees following a headline may travel to gather or dispatch information or for other reasons, which changes the environment from chronicle to chronicle. Example: a military tech show catches the attention of the Director of R&D, the R&D Lab Specialist and the R&D Liaison. After discussion and reminders, two of them team up, attend, and report at the next Koware meeting.
- Employees need not share one concern. The storyline shifts with the roles checked that day. Example: the Janitor and the Maid worry about higher commuting fares or rumors about a local artist, while the Corporate Manager and the Liaison Officer worry about a client leaving the country.
- Scan online resources for ongoing and upcoming activities and events Koware could join. The events and booking manager posts them on the pinboard, and supervisors and top-level managers assign themselves or other roles to attend and bring back a report for the weekly meeting.

**Action plans and projects.** Review Koware’s current action plans and projects (D1), integrate them into the storytelling, and assign their items to the appropriate job roles, with follow-ups from supervisors and other appropriate roles toward completion with the best-quality deliverables. Do not imply real completion because a story shows it.

### G4. Narrative requirements

- **Cohesion.** The result must read as one cohesive, immersive, engaging narrative in which the spiritual, educational, historical, contemporary, futuristic, personal and business dimensions reinforce each other and sustain the reader’s curiosity and anticipation.
- **Exactly one** slapstick humorous comic beat per chronicle. Other humor may occur naturally.
- **Action.** Include the key elements of each chronicle so the episode is action-packed and full of adventure.
- **Morning cross-section.** Show a cross-section of the mornings before the 9 a.m. meeting for the roles in the day’s spotlight. A staff member due at a workstation by 7 a.m. has a different routine from a manager who sleeps at 1 a.m., wakes at 7 a.m., exercises, showers at 8 a.m. and joins the secure 9 a.m. online meeting from home in pajamas.
- **Edubeam mission.** Derive the deployed team’s mission from the historical date’s events and news (I9).
- **Names and labels.** F3 and B4 apply: character names or aliases, and the title as `Part {N}: {Title}` with no date.
- **Ending: the chronicle challenge.** End every chronicle, and any reading material, comic or video made from it, with a question or riddle. It may arrive at a critical decision, with options A, B, C, D or E on what to do, say or identify. It may require following a Bible passage or Health & Safety lesson tied to the part, with the answer in the episode or in related reading, and it should test practical understanding. Hints and clues can arrive as riddles on service cards. The answer is revealed in the next part or episode. Part H3 defines the ladder and rewards.

### G5. Post-writing integration check and rewrite loop

After writing, systematically verify that all eight elements were integrated creatively and contextually, not merely mentioned or inserted as standalone references. If one is missing or thin, acquire the needed resource and rewrite while keeping continuity, relevance and compelling storytelling. Also verify narrative, role accuracy, Edubeam consistency (Part I), continuity (Part E), exactly one slapstick beat, the title format, and a connected challenge ending.

### G6. Daily chronicle deliverables

1. Title (`Part {N}: {Title}`) and status (D5).
2. Narrative.
3. Production notes: date and calendar metadata, run variables, role check, lead and supporting cast.
4. Bible Study, Verse of the Day, Health & Safety, news, diary and archive references.
5. Edubeam mission developments and organizational activities.
6. The one slapstick beat, identified.
7. The chronicle challenge.
8. Mission log and continuity ledger (E2).
9. Character record updates (E4) and visual reference notes (F2).
10. Integration check and exception report (D5).

---

## Part H — Episode production

### H1. Compilation

An episode is assembled from its daily chronicles (ten, or fewer under a documented compression, B3). They are generated separately and then compiled and edited manually. The episode must not simply reproduce them in sequence. It keeps their chronological progression, character and mission continuity, the Bible, Verse and Health & Safety connections, the news developments and the Koware operations, and it brings forward the key elements of each chronicle so the episode is action-packed and full of adventure. The episode storytelling may be adjusted and modified as needed to strengthen the selections in H2, while preserving established continuity.

### H2. Episode-level selections

- **Bible Study takeaway.** Pick the one key takeaway from the Eternal Dreamfall Bible Study covered in the chronicles that is most relevant to the whole episode. Integrate it so it relates to the entire episode, and reference the resource in the storytelling.
- **Verse of the Day.** Pick the one key Verse of the Day shared in the chronicles that is most relevant to the whole episode, and integrate it the same way.
- **Health & Safety.** Identify the key Health & Safety topic, lesson or course related to the episode, and connect it to the events and practical decisions across the episode.

### H3. The challenge ladder and rewards

- **Daily challenge.** Each chronicle ends with its challenge (G4). Media made from it ends with the challenge too, so the audience has time to work on the solution before the next episode releases, when the solution is shared.
- **Episode challenge.** A challenging game built from the challenges of the daily chronicles. It should be very difficult to complete or solve without having completed or solved the daily challenges. Solving each daily challenge unlocks tips, hints, tools and techniques for the episode challenge.
- **Chapter challenge.** Solving the episode challenge unlocks tips, hints, tools and techniques for the chapter challenge.
- The game must be entertaining and engaging, difficult but fair, connected to the narrative, and support team-ups.
- **Rewards (deferred integration).** The first member to post the right answer earns points, and answers are revealed in the next part or episode, with points and awards distributed and leaderboards updated. Palmier points and Buzzjuice score rewards are to be integrated later, with consideration of myCred Palmier and score integration. Until then they must not be portrayed as operational.
- **Service cards and clues.** Service cards can carry hints and clues, perhaps as riddles, for difficult questions, puzzles and campus or city treasure hunts. Clues can sit in library books or at business receptions as a promotional gimmick to bring customers to a business. The 43 Clues game is under development and is to be integrated into the episodes and service cards.
- Keep the game mechanics independent of the storytelling engine, with clear integration points for the future reward systems.
- The episode ends with its challenge, which should bridge to the next episode, chapter or season and leave the audience questioning and curious.

### H4. Next-episode preview

Include a brief preview that keeps the audience eager without revealing outcomes: upcoming activities and events; action-plan items; Eternal Dreamfall studies; the job-role checks of the next episode; the team to be deployed through the Edubeam and its estimated probe details; the key items the returning team reported for analysis; and any other key details that help prepare for the next episode.

### H5. Episode deliverables

1. Episode title (decided after the last chronicle).
2. Compiled and edited episode narrative, with its chronicle structure (ten, or the documented exception).
3. Bible Study takeaway, Verse of the Day and Health & Safety topic (H2).
4. Consolidated mission log and episode continuity ledger.
5. Episode challenge (H3), ending the episode.
6. Relevant character record updates (E4).
7. Outstanding Koware assignments.
8. Next-episode preview and next Edubeam deployment details (H4).
9. Comic and media handover notes (Part J).
10. Production and exception report (D5).

### H6. Two-week operational rhythm

Use this as a flexible frame. Routines vary by character, job, day and location, and each chronicle includes the beats its story needs.

**Standing elements on every working day.**

1. Activate the Eternal Dreamfall five-year Bible reading plan.
2. Activate the YouVersion Verse of the Day.
3. Identify one key Health & Safety lesson from the combined Eternal Dreamfall Bible Study and the Verse of the Day.
4. Morning routines activate.
5. The R&D Lab Specialist fine-tunes the Koware timezone with the Edubeam. The staff, still in the Edubeam since deployment, are instantly teleported to the updated timezone and updated with new details, news headlines, the Verse of the Day, Bible study areas and Health & Safety tips that help them help others on their research mission.
6. The story focuses on the expedited team (I9, I10).

**Briefing pattern.**

| Day | Briefing |
| --- | --- |
| Week 1 Monday | Opening briefing at 9 a.m., deployment |
| Week 1 Tuesday, Wednesday | No briefing |
| Week 1 Thursday | All-staff follow-up on Monday’s briefing |
| Week 1 Friday | Weekend dispersal from about 2 p.m. |
| Week 2 Monday | All-staff follow-up on the previous week’s briefing |
| Week 2 Tuesday, Wednesday | No briefing |
| Week 2 Thursday | The R&D Lab Specialist sets the Edubeam to bring the deployed staff back. Follow-up briefing and settling of the returning staff. Staff compile reports for presentation the next day |
| Week 2 Friday | Briefing with returning staff, review of their reports, integration into Koware business, action plans and workflow, and the reveal of the next Edubeam team |

**Week 1 Monday: episode opening.**

- Start with the current date and time, early morning, twilight, before sunrise. Zoom to the houses and windows of the episode’s main characters, perhaps checking the outside environment and garden before reaching the window. Identify the main characters through the job-role check day (F4). After entering the window, zoom to the bedside as the 4 a.m. alarm goes off.
- The character turns off the alarm and briefly reviews the Eternal Dreamfall five-year Bible reading plan that an AI was reading when the character went to sleep. The character reads the YouVersion Verse of the Day and begins meditating on it, sometimes falling asleep while meditating.
- At 4:30 a.m. the next alarm goes off and the character continues cleanup and domestic management duties started before sleep, or sometimes snoozes. At 5 a.m. the character prepares for a morning fitness session. On Tuesdays and Saturdays, and sometimes Thursdays, the character exercises until sunrise or snoozes until sunrise and skips it.
- On weekdays at 6 a.m. the character freshens up and has breakfast. At 6:30 a.m. the character connects with the day’s team and the rush to the Koware Group headquarters starts.
- **Commute.** Capture the hustle and bustle: bus stops, trains, planes, traffic or a short walk, depending on the team. Characters review top headlines on the way. They may use a service card to settle a minor dispute: for example, cut into two people arguing and hand both a card, they start reading and stop arguing, the argument becomes a discussion of the card’s topic, and it may even end in laughter and an exchange of contact details.
- **Arrival.** Staff arrive at the headquarters (F5) and rush to clock in before 9 a.m. Neighbours peep out as cars, bikes and bicycles arrive. National service personnel report as described in F5.
- **Briefing.** At their workstations staff prepare for the 9 a.m. briefing from the Corporate Manager, and most are tuned in to the news headlines and to Koware’s timezone. The timezone was developed by Koware’s Blue Crown R&D department around a reference date in the past and a probing date that looks almost random, reaching into the future and the past, and it is what the Edubeam uses. By 9 a.m. all staff are on a secure online video meeting, usually built on Koware’s annual goals, news headlines and the timezone’s reference date. This can be the start of the podcast session and discussions. The previous returning Edubeam team is briefed and the data it collected is reviewed and processed as necessary.
- **Deployment.** Staff to be deployed meet at the Yamoah Residence for inductions and deployment (I8).

**Week 1 Tuesday to Friday and Week 2.** Follow the standing elements and the briefing pattern above. Week 2 Thursday and Friday add the return, reports and next-team reveal. The Week 2 Friday episode ending adds the next-episode preview (H4), the episode challenge (H3) and the mission log and continuity ledger (H5). If the episode remains unresolved, it continues into the next one.

**Fridays, afternoon and evening.**

- Around 2 p.m. all staff begin to disperse for the weekend, with lock-up and security checks. On the Week 1 Friday, offer prayers for everyone to make it safely to the end of the two-week assignment, and other prayers. On the Week 2 Friday, offer thankful prayers that everyone made it safely, and other prayers.
- Members sometimes show up before 2 p.m. to hang out, watch movies, play games or work on their affiliate membership plans, which makes lock-up impossible. Members are advised to book first, but some forget and arrive after 2 p.m. to find the place locked.
- Various clubs, group activities and member events run until the evening.
- Personal routine: domestic management at 6 p.m.; fitness or walks around 7 p.m.; freshen up and dinner at 8 p.m.; a check on the contacts made during the day and week at 9 p.m.; review of the Eternal Dreamfall Bible study at 10 p.m., then sleep.
- Outgoing and incoming teams may spend the weekend together (I12).

---

## Part I — Edubeam canon

### I1. Status and nature

The Edubeam is developing technology of Koware’s Blue Crown R&D. Its teleportation is described as working almost like Star Trek teleportation, with a time factor added that permits probing time travel. It is experimental and must never be portrayed as perfected. It is used by Koware staff to bring back anything that supports the development of Koware’s products, services and lifestyle packages.

### I2. Enka’s own ability

After years of studying and experimenting with the Edubeam, Enka can now control his teleportation and materialization at different locations to an extent, without using the Edubeam. It depends on his focus, and it exists because he has been and keeps getting zapped by the London Edubeam. His control is limited and is not unrestricted teleportation. R&D staff and the team assisting the R&D Lab Specialist close from work but keep monitoring the Edubeam from their mobile devices and tablets.

### I3. Operation and targeting

- The Edubeam is set to vibrate at a specific frequency so that people land in the Edubeam chamber. The date, time and location coordinates must be set to target the specific subject, who must be at that exact location at that exact time to be beamed back into the chamber.
- Only one person can go through the chamber at a time, so coordinates and time are adjusted slightly between people. Sometimes people can be beamed directly to an existing Edubeam facility in the future if one exists at that location.
- Edubeam is more common in the far future, but there without the time-travel factor. People there sometimes query the team suspiciously about where it comes from. Clothing often gives the team away if it has not prepared well.
- An open research question that bothers the R&D Lab Specialist is why the team returns to that particular location and not to a place far off. The working hypothesis, which must be presented as a hypothesis, is that vibration and relativity ideas apply, in particular that two objects cannot occupy the same space at the same time. Research continues.
- In the first trials, subjects ended up in the next room or in the garden, never very far.

### I4. Daily updates and time displacement

- The team must be at the precise location at the given time each day to receive the Edubeam update and be beamed to the next date and location. Usually the location is the same. The person being beamed begins to disappear, and can see plants growing and buildings going up in real time around them, then gradually materializes.
- People who have been through the beam say it is the weirdest feeling they will never get over: happiness, sadness, anger, humor and more, all at once. That can make the procedure humorous to watch.
- When the R&D Lab Specialist updates the timezone each morning, the probe can move through several years in one day. The team may meet someone young, the next day find them married with children, then in retirement, then old and grey. The reverse can also happen: an old person one day, a younger one the next, even before they were born. Team members may want to give the younger person advice about their future.

### I5. Energy, objects and safety

- Pushing small objects through the Edubeam within the same timezone is manageable and controllable, with minimal errors. Problems start when the object is bigger and is pushed through time. The further into the future or past, the more likely the object is deformed, damaged or lost in time.
- Blue Crown R&D has identified **250 years** into the past or future as the threshold for safely pushing objects. Beyond it the risks are very high, including malformed materialization, and communication also becomes distorted. Keep this threshold unless a recorded research development revises it.
- Solar flares, cosmic events, electromagnetic waves and similar factors degrade transmission quality. Never use the Edubeam during these, or during a thunderstorm: the outcome could be catastrophic, from materializing at an unknown location and time to losing a limb. Never attempt the Edubeam in unstable conditions. Enka experienced this over the years and used it to develop the stabilized Edubeam with controls.

### I6. Messages across time

- Sending messages into the future is easier than sending them into the past. A message left with Koware in the past exists in the future as long as it is not tampered with. The team can connect on a network or visit a specific location where the message was placed, underground and emitting a light digital signal so it can be found. A team that arrives at a future probe location can sometimes find the message signal on the radar or locator map and must travel there to get it. The team can also connect with a Koware Asylum in its area to retrieve messages from the past.
- Sending messages into the past needs expensive energy and careful targeting of location and time, so it is done only when extremely urgent and important.
- A team deployed to a future date can receive messages left earlier, through various sources, especially where a Buzzjuice Network Support System is in the area. A team deployed to a past date can send messages to Koware in the future by leaving a message in the past, using the same method Koware uses.
- The team can send a message back through the Edubeam only from a charged location, meaning where the team materialized. That can be very expensive, especially if the team has nowhere to recharge.

### I7. Charge and short-range teleportation

Edubeam charge can make a person seem to have superpowers by allowing short-distance teleportation. It drains the holder’s charge, so the person must find more Edubeam plasma and recharge before it fully runs out and they are retracted to materialize back at Koware. That can be dangerous if the Edubeam has not been prepared for the incoming materialization, because the person could end up in limbo or in uncontrolled conditions.

### I8. Deployment, selection and field support

- **Selection.** Staff and members drop applications to go through an Edubeam assignment. A confidential waiting list is kept, and applications can be dropped at any time. At the end of every successful assignment the next four are chosen and revealed. If issues are outstanding, the same team or some of its members may go again. The next team is chosen before the arriving team leaves, which gives it two weeks of briefings and simulation training before the reveal on the Week 2 Friday. Selection can look biased and controversial among staff and members, especially when one person goes several times in a row.
- **Briefing.** The four selected staff are briefed on what to do on arrival, how to monitor their energy levels, how to use the Blue Crown Edubeam devices to charge, how to recharge the devices with Edubeam plasma if it runs out, and the emergency procedure to self-induce a natural charge so the Edubeam retracts them. That procedure is hard and can take years to master, so a person can expect to be stuck in a timezone for years if something goes wrong.
- **Location.** The Edubeam system is discreetly set up at Enka’s family house, where the Blue Crown R&D Laboratory is.
- **Beaming.** One by one, the selected team members are immersed in a concentrated Edubeam in an enclosure and teleported to the probing date confirmed on Koware’s timezone. The procedure produces bursts of light in a short time that arouse neighbours’ attention and suspicion, leading to later break-in attempts and conspiracies among the neighbours in Adenta. The staff are charged with Edubeam particles and stay in the teleported zone until the charge wears off, then return to the enclosure (reference the Twelve Monkeys teleportation strategy).
- **Materialization.** Team members sometimes materialize far apart and take time to find each other. Sometimes they discover a team member on TV or in a controversy and rescue them just in time.
- **Monitoring.** The R&D Lab Specialist usually stays behind to monitor equipment and feedback signals and make sure the staff are cared for and return safely.
- **Objects on request.** The specialist can program objects to materialize where the staff were beamed. If staff need a vehicle, they use the Blue Crown comms device to communicate through time to the specialist, who gets a 3D design of the vehicle and beams it to them. This is not always instant: comms may not go through, networks may be down, and other scenarios may lead to anomalies (funny, scary, comic or otherwise).

### I9. Mission design

- The team’s mission is based on the events and news of the historical date and location (G1). Usually the team finds similar events and news at the probe location, so the scenarios of the historical date and location end up portrayed through the team. Example: if notes from a previous chronicle describe a very low-flying fighter jet at the historical date in July 2003, the team sees a low-flying hot-air balloon or plane at its location. This depends on various factors and on the idea that history repeats itself.
- Missions are updated with details from Enka’s diary and memories (G1, F7).
- The team may find solutions to issues facing Koware and its headquarters’ community. Issues may be covered in the headlines, and solutions may come from talking to people, other news, overhearing discussions or watching how others deal with the issues.

### I10. Team experience palette

The team’s story explores how it might witness an event and make it better or worse, try to fit in and keep a low profile, and what happens when it is discovered or suspected. Draw on: how it finds a place to stay (sometimes locals help, sometimes it sleeps under the stars); weather; kind, bad, wicked and helpful locals; getting food; falling in love; winning lotteries; catching criminals; being featured in the news; encounters with people at different life stages (I4); and suspicion about clothing and origin (I3).

### I11. Consequences: the team can change a headline

The team might make a news headline worse or resolve it. Example: a real 2026 report says an oil rig off the coast of Gabon capsized with no oil leak and all crew safe. In the episode, the team meets a report from the year 2210, about two centuries on, saying that a similar capsize caused a major oil spill with only one crew member accounted for, and that the Gabonese ecosystem is still recovering. The team notes this and, when it can go back before the event, for example to 2015, it approaches the companies involved and hosts Health & Safety workshops. The companies take heed and build the measures into their businesses, workflows and action plans, which reduces the effect. On returning, the team encounters the improved news report.

### I12. Rules, return security, misuse and secrecy

- Some members try to exploit their Edubeam experiences, such as hunting for lottery numbers or looking for themselves, their family or friends to change their past, future or present. Some, especially members, end up banned from ever using the Edubeam again for breaching the terms and conditions of the activity.
- On return, the team always goes through decontamination, then MRI and X-ray searches before release. Team members found with illegal objects or information are interrogated until a guilty conscience speaks. Smuggling issues have occurred, especially with members: items swallowed, under the tongue and in other places.
- Sometimes the team backs each other up to keep a vow of silence about something that happened on the assignment. Some keep it forever. For example, someone learns a partner may have cheated and never mentions it. After some episodes the truth may come out, and the partner is surprised to hear the person already knew but was just happy they were happy. This leads to many perspectives on the Edubeam terms, rules and regulations. Koware may also discover that teams are hiding information, and deals with it according to what was withheld and why.
- **Weekend handover.** Teams may spend the weekend with the next team about to be expedited, especially the Friday evening after the reports are shared, and advise on dos and don’ts, experiences and outstanding things never put in the reports. This includes things management should not know or should be kept from for as long as possible, such as smuggling notes, or requests to check on someone or something at a certain date, time and place. Such requests are sometimes impossible if the next probe is off by years.

### I13. Missed retrievals and rescues

Sometimes a team member misses the Edubeam time frame and must wait until the next day. The member is usually brought back to base before regrouping with the team, if possible. A message can be sent to the future if the team is in the future. Sometimes a member genuinely does not want to return. The Corporate Manager is then usually called to go through the Edubeam with a team to find and retract the person. This can get complicated if the person has been arrested, kidnapped or held hostage by a gang. Such events are accounted for in the **Chronicles of Cupid Black** when required, as a part of an episode within a Classies Chronicles episode. The same applies to other exceptional Enka events: unexpected teleportation, undercover work, heightened awareness and supernatural manifestations (F3).

### I14. Extended deployments

A team can spend a whole chapter of four episodes in the Edubeam, depending on the assignment and what is going on, but that can make the story drag. Use it only for a specific story reason, unless a specific team has been trained and assigned as the permanent Edubeam taskforce and regulators.

### I15. Explaining the technology

The time-travel factor still needs to be explained, and the explanation should be built from existing research so the technology is believable. Recommended approach: anchor Koware’s explanation in real physics ideas (relativity and time dilation; theoretical closed timelike curves and wormholes; quantum teleportation, which transfers a quantum state and not matter), present it as Blue Crown’s working hypothesis, and never as established science. Keep capabilities, costs and limits identical across chronicles, and record any change in the Edubeam notes of the ledger.

---

## Part J — Comic and media production

### J1. One visual authority

The Master Comic Generation Prompt and the Master Media Generation Prompt are complementary production systems. The comic graphics and pages must complement the media and videos the Master Media Generation Prompt produces, and the actual production process, and must enhance their visual consistency. The Master Media Generation Prompt prepares the graphics and images that are shared with each image-to-video generation prompt. Neither process creates a competing version of a character, location or look.

### J2. Shared references

Both processes use the same character visual references (F2), the same clothing and physical attributes, established locations and environments, reusable assets, and the scene and shot references from the archive (Part C). Earlier episodes’ comics serve as continuity reference, especially for character appearance.

### J3. Comic collages

Some platforms limit image generation to two or three generations per session, so produce the comic pages as one high-quality collaged graphic and crop the individual pages from it later.

- **Default:** a 2 × 2 collage, four pages per graphic, for the best image quality. Use several collages for more pages.
- **When the platform quota forces it:** 2 × 4, or 1 × 8 as a vertical long-scroll arrangement, which v0.03.07 preferred for long scroll. State the quality trade-off.
- Every collage preserves page order, keeps text readable, avoids panel overlap, preserves page boundaries and keeps characters consistent.

### J4. Endings and sequencing

Comics and videos end with the chronicle’s challenge (G4, H3). Unless the run instruction says otherwise, comic pages and media are produced only when separately requested after the chronicle or episode is delivered. The chronicle notes, the visual reference notes and the challenge are the handover for both.

---

## Part K — Architecture, decisions and merge report

### K1. Three layers and separated responsibilities

Treat this document as the governing framework, and derive a compact prompt for each run mode from it (L1) instead of maintaining one giant prompt. It has three coordinated layers, so that, for example, the comic process can change without rewriting the chronicle process:

- **Layer 1, governance and canon:** season, chapter and episode manifests; organizational and governance references; character canons; Edubeam canon; calendar authority; continuity rules.
- **Layer 2, story generation:** context merging, daily chronicles, episode compilation, character development, news and Bible integration, challenges.
- **Layer 3, production and distribution:** comics, images, video, audio, mission reports, continuity updates, games and audience participation.

| Process | Primary responsibility |
| --- | --- |
| Season generation | Annual storyline and season gist |
| Chapter generation | Chapter objectives, storyline and challenge |
| Episode generation | Compile chronicles into an episode |
| Daily chronicle generation | One day’s narrative and supporting records |
| Context merge | Combine Bible study, Verse, news, diary and other source material (D1) |
| Comic generation | Comic pages and visual storytelling |
| Media generation | Visual and audio assets for video production |
| Continuity management | Gists, ledgers and character records |
| Mission management | Edubeam deployment and mission information |

Each process references the governing records and never creates a competing version of the same information.

### K2. Decisions made in this version

| ID | Decision | Why |
| --- | --- | --- |
| D-01 | The shorter archive structure of Note 42 is canonical. The earlier long structure is legacy and mapped (C1, C3). | Shorter paths, fewer duplicated identifiers, 256-character limit. |
| D-02 | Folders use codes only until a title is decided, then the title is appended and the rename recorded (C2). | Titles are decided retrospectively (B4) but folders must exist from the start. |
| D-03 | Ten chronicles per episode is the default. Compression is allowed only through the episode manifest (B3). | Note 54 described one episode’s exception, with specific dates, which Note 10 forbids in a generic prompt. |
| D-04 | The Millennium probe supplies chapter dates. The default annual calendar is a fallback (B2, B5). | Five eight-week chapters fill only about 40 weeks, so fixed dates cannot be assumed. |
| D-05 | A **Series Master Ledger** receives the season ledgers (E3). | Note 6 left this level as a blank. |
| D-06 | Failures use two tiers: stop and request, or continue provisionally and report (D4). | Notes 9.i and 14.xiii say to stop, while Notes 16, 18 and 55 say to continue. Tiers reconcile them. |
| D-07 | “Calendar authorization” covers both the external calendar connection and the availability of the Millennium endpoints (B5, B6, D4). | The notes use the phrase without defining it, and both readings are needed. |
| D-08 | “Approve character cannons with draft status…” in Notes 16.ii and 56.ii is read as: report such canons as needing approval, never treat them as approved (D4). | The sentence is a reporting trigger, so approving is not the action. |
| D-09 | The five-block headquarters of Note 34 replaces the single small unit in the episode outline (F5). | Note 34 is the later update. |
| D-10 | Role-check day numbers are running chapter days, with an explicit formula (B3). | Only examples were given. |
| D-11 | The comic default is a 2 × 2 collage (J3). | Image quality. See O-1. |
| D-12 | Fallback URLs use the owner’s `refs/heads/main` pattern (D2). | It is the pattern the owner specified. |
| D-13 | Behavior records get a **Current State** header and append-only entries (E4). | Cheaper reads, no lost history. |
| D-14 | Challenge mechanics are separate from the storytelling engine, and rewards are marked as deferred (H3). | They are not built yet and must not be portrayed as operational. |

### K3. Open decisions for the owner

| ID | Question |
| --- | --- |
| O-1 | Keep 2 × 2 as the comic default, or prefer 1 × 8 long scroll when quota allows? |
| O-2 | Should Chronicles of Cupid Black have its own archive branch and numbering? |
| O-3 | What are the final scene, act and beat granularity and the file extensions for each asset type? |
| O-4 | Where does the Series Master Ledger live, and what is it called? |
| O-5 | What are the scoring rules for Palmier points and Buzzjuice score, and the schema for the 43 Clues game? |
| O-6 | Should a dedicated Edubeam technical specification file be created so I1–I15 can be versioned separately? |
| O-7 | May compression combine more than the final three working days of an episode? |
| O-8 | Should canon and behavior-file writes be automated, given repository write access? |

### K4. Duplicates merged

| Merged into | Source items in v0.03.07 |
| --- | --- |
| F2 visual consistency | Notes 17, 22, 44 |
| F1 missing canon and the Bonsu Bolt example | Notes 18, 23, 45 |
| D2 URL fallbacks | Notes 15, 21.i, 21.ii, 43.i, 43.ii |
| F5 governance resources | Notes 24, 46 |
| G3 Legal Journalist and pinboard | Notes 25, 47 |
| G3 news-driven travel | Notes 26, 48 |
| G3 different role concerns | Notes 27, 49 |
| F6 Edliex | Notes 28, 50 |
| G4 one slapstick beat | Notes 29, 51 |
| E4 behavior and relationship records | Notes 30, 52 |
| D4 provisional drafts and reporting | Notes 16, 55, 56 |
| F3, F4 naming and organizational chart | Notes 19, 57 |
| J1 comic and media consistency | Notes 20, 58 |
| G4, H3 challenges | Note 11, Note 38, Chronicle item 5, Episode ending |
| E2, H5 mission log and ledger | Note 6, Chronicle item 6, Episode ending |
| G1 historical location and diary requests | Chronicle item 1 and the Additional notes (both repeated the Las Vegas example) |
| I6 messages across time | Two overlapping Additional paragraphs |
| H6 standing daily elements | The same five lines repeated under every weekday of the Episode outline |
| H2 episode selections | Notes 35, 36, 37 |
| B3 role-check examples | Chronicle item 2.i |

### K5. Platform recommendations: adopted, adjusted, not adopted

| Recommendation | Source | Disposition |
| --- | --- | --- |
| Three-layer architecture; use the rewrite as a governing framework, not one giant prompt | CHATGPT-1 | Adopted (K1, L1) |
| Decision log; do not silently resolve open matters | CHATGPT-1 | Adopted, split into decided and open (K2, K3) |
| Retain the two Context Merge prompts as dependencies | CHATGPT-1 | Adopted (D1) |
| Keep ten chronicles as the standard and treat eight as an exception | CHATGPT-1 | Adopted (B3) |
| Distinguish verified news from extrapolation | CHATGPT-1, GITHUB-1 | Adopted (A2, G3) |
| Never claim a GitHub save that did not happen | CHATGPT-1 | Adopted (A2, F2) |
| Append-only character records with source links | CHATGPT-1 | Adopted and extended (E4) |
| Provisional drafts only when explicitly permitted | CHATGPT-1 | Not adopted. Notes 16 and 55 already authorize them, with reporting (D4) |
| Treat calendar authorization as the Millennium calendar only | CHATGPT-1, GITHUB-1 | Adjusted to include the external calendar connection (D-07) |
| Pre- and post-production QA and deliverable lists | CHATGPT-1 | Adopted (G2, G5, G6, H5, L4) |
| Flexible rhythm instead of mandatory routines | GITHUB-1 | Adopted (H6) |
| Present unresolved Edubeam explanations as hypotheses | GITHUB-1 | Adopted (I3, I15) |
| Do not invent names for existing aliased roles | GITHUB-1 | Adopted (A2, F3) |
| Fallback `raw.githubusercontent.com/cupidblack/{repo}/main` form | GITHUB-1 | Not adopted. The owner’s `refs/heads/main` form is used (D-12) |
| Condensed treatment of the Edubeam, security and organization | GITHUB-1 | Not adopted. Full detail restored from v0.03.07 (Parts F, I) |
| Some sentences abbreviated in the retrieved source | GITHUB-1 | Resolved by working from the full v0.03.07 text |

**Scope note.** In this pass only v0.03.07 and the responses file were retrieved. The repositories and folders listed in D1 were not enumerated here, so this document instructs the generating AI to retrieve them on every run.

### K6. Corrections to the source text

- “Kuala Lumpar” is Kuala Lumpur, and “Start Trek” is Star Trek.
- Note numbering (1.i, 1.iii, 1.iv, 1.v and repeated 21 to 58 blocks) is replaced by the structure of this document.
- The Episode outline’s small three-bedroom unit is replaced by the Note 34 headquarters (D-09).
- “Leading top-level roles are… voted for” keeps its example (IAPD Administrative department) and is generalized (F5).
- Instance-specific text (Note 54’s Wednesday to Friday dates and its episode) is generalized (D-03).
- The `cannon` spelling in repository filenames is kept exactly, since changing it would break the links.

---

## Part L — Run protocol and quality assurance

### L1. What each run mode loads

| Mode | Parts to load |
| --- | --- |
| Daily chronicle | A, B, C4, D, E, F, G, I (H3 for the challenge) |
| Episode compilation | A, B, D, E, G3–G5, H, I |
| Chapter or season planning | A, B, D, E, H3, K |
| Comic or media production | A, C, F2, G4, J |
| Continuity processing | A, C, D, E, F1 |

### L2. Master run protocol

1. Establish the mode, the production level and the status (A1, D5).
2. Acquire and verify the required resources, enumerating folders and following links (D1–D3). Stop under Tier A conditions (D4).
3. Establish the calendar and the run variables (B5, G1). Apply Tier B handling where needed.
4. Retrieve the season, chapter, episode and chronicle gists, and review archives, ledgers and diaries (E1).
5. Select the role check, the lead character and the cast from the organizational chart (F4), then load canon, behavior records, job descriptions and governance resources (F1, F5).
6. Check visual references for the main and lead characters (F2).
7. Decide the Edubeam mission from the historical date (I9, G1).
8. Review current news, events, action plans and projects (G3).
9. Draft the narrative with all eight elements woven in, exactly one slapstick beat, and the challenge ending (G3, G4).
10. Run the integration check and rewrite where needed (G5).
11. Produce the notes: mission log, continuity ledger, character record updates and exception report (E2, E4, D5).
12. For an episode, add the selections, the challenge, the preview and the episode records (H1–H5).

### L3. Output order for a daily chronicle

1. Status line and run variables.
2. Resource and exception report summary, or the Tier A request if a mandatory resource is missing.
3. `Part {N}: {Title}` and the narrative.
4. The challenge.
5. Notes: mission log, continuity ledger, character record updates, visual reference notes, integration check, full exception report.

### L4. Final quality checklist

- Calendar, episode and chronicle number are established, or reported as provisional.
- Gists, ledgers, canons, governance resources, action plans and diaries were consulted, or their absence is reported.
- Names, aliases, roles and job descriptions agree with the organizational chart.
- The Edubeam’s capabilities, costs, limits and timeline are consistent (Part I).
- All eight elements are integrated, not merely mentioned, and the Bible reading, Verse and news were actually acquired.
- The narrative is cohesive and real to the reader, with no mention of fictional roles or an imaginary headquarters.
- Exactly one slapstick beat. The title has the `Part {N}: {Title}` form and no date.
- The challenge ends the chronicle and links to the day’s events. For an episode, the challenge is built from the daily ones.
- Mission log, ledgers and character updates are complete.
- Filenames are short and paths are within 256 characters.
- Verified news is separated from extrapolation. Nothing is claimed as retrieved, saved or scheduled unless it was.

Every chronicle contributes to its episode, every episode to its chapter, every chapter to its season, and every season to the continuing Classies Chronicles universe.

**End of Unified Production Framework v0.04.00**
