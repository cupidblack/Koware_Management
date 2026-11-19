# Classies Chronicles Comic Production System — v0.09.00 Candidate

This package contains the coordinated CC-03.01 / CC-03.02 / CC-03.03 candidate set.

## Files

- `CC-03.01-Master_Comic_Generation_Prompt_v0.09.00_Candidate.md` — full comic-generation specialist.
- `CC-03.02-Comic_Refactor_Review_Conflict_Register_and_Change_Traceability_v0.09.00_Candidate.md` — review, decisions, compatibility and adoption record.
- `CC-03.03-Comic_QA_and_File_Manifest_v0.09.00_Candidate.md` — documentary QA and actual package manifest.
- `cc_comic_sheet_tool.py` — local utility for assembling four 9:16 pages into a 2×2 sheet and slicing a no-gutter 2×2 sheet into four pages.

## Production standard

Default: sixteen unique 9:16 portrait pages, delivered as four sequential 2×2 sheets. Each sheet is itself 9:16. At 1080×1920 per page, a no-gutter sheet is 2160×3840.

Visual direction: cinematic semi-realistic graphic-novel art with realistic anatomy and character identity, plus restrained, context-sensitive animation/toony effects. See CC-03.01 §§13–17.

## Utility requirements

Python 3.10+ and Pillow:

```powershell
py -m pip install Pillow
```

Assemble four pages:

```powershell
py cc_comic_sheet_tool.py assemble --pages P01.png P02.png P03.png P04.png --output S01.png
```

Slice a no-gutter sheet:

```powershell
py cc_comic_sheet_tool.py slice --input S01.png --output-dir S01_pages
```

Run synthetic geometry tests:

```powershell
py cc_comic_sheet_tool.py self-test
```

The utility does not generate artwork, infer gutters, typeset dialogue or perform semantic/rights/continuity QA. It refuses invalid geometry rather than silently stretching images.

## Important evidence note

The reference PNGs supplied in the request were not available as binary image data through the inspected GitHub file connector, and the development response compilation file returned zero bytes. The documents therefore do not claim pixel-level reference inspection or incorporate recommendations that could not be read. The visual pilot remains a required next validation step.

## Adoption

These are proposed candidate documents. Creating this package does not update GitHub, ratify the documents, grant image rights, approve canon or publish comics.
