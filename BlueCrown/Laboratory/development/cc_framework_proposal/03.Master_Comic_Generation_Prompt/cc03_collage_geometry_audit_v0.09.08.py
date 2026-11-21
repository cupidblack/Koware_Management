#!/usr/bin/env python3
"""CC-03.03 v0.09.08: rigorous *mechanical* comic-page/sheet geometry audit.

Requirements: Python 3.10+, Pillow. Does NOT prove native AI-provider details,
scene accuracy, authorized logo rights, human likeness, editorial acceptance,
lettering accuracy, or challenge correctness. Always pair with human QA.

Examples:
  python cc03_collage_geometry_audit_v0.09.08.py selftest
  python cc03_collage_geometry_audit_v0.09.08.py inspect --kind sheet --input S01.png --json audit.json
  python cc03_collage_geometry_audit_v0.09.08.py inspect --kind page --input P01.png
  python cc03_collage_geometry_audit_v0.09.08.py assemble --pages P01.png P02.png P03.png P04.png --output S01.png --json S01_audit.json

Always store true native-render output metadata separately. Never claim pixel
upscaling creates new original native source detail.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import os
import sys
import tempfile
from pathlib import Path
from typing import Any
from PIL import Image, ImageDraw

MIN_W, MIN_H = 1080, 1920
PAGE_RATIO = (9, 16)
ORDER = ("TL", "TR", "BL", "BR")


def raw_file_sha256(path: Path) -> str:
    hasher = hashlib.sha256()
    with path.open("rb") as file:
        for block in iter(lambda: file.read(1024 * 1024), b""):
            hasher.update(block)
    return hasher.hexdigest()


def pixel_sha256(image: Image.Image) -> str:
    canon = image.convert("RGBA")
    return hashlib.sha256(canon.tobytes()).hexdigest()


def dimensions_of(path: Path) -> tuple[int, int]:
    if not path.is_file():
        raise ValueError(f"Missing image: {path}")
    with Image.open(path) as image:
        image.verify()
    with Image.open(path) as image:
        return image.size


def geometry(w: int, h: int) -> dict[str, Any]:
    ratio_ok = w > 0 and h > 0 and 16 * w == 9 * h
    min_ok = w >= MIN_W and h >= MIN_H
    return {
        "pixels": [w, h],
        "exact_9_by_16": ratio_ok,
        "at_least_1080_by_1920": min_ok,
        "geometry_pass": bool(ratio_ok and min_ok),
        "warning": None if ratio_ok and min_ok else (
            "BAD_ASPECT_RATIO" if not ratio_ok else "BELOW_PER_PAGE_DETAIL_TARGET"
        ),
        "native_provider_detail_status": "NOT_VERIFIABLE_FROM_FILE_DIMENSIONS_ALONE",
    }


def inspect_page(path: Path, kind: str = "page") -> dict[str, Any]:
    w, h = dimensions_of(path)
    result = {
        "asset_kind": kind,
        "asset_path": str(path.resolve()),
        "file_sha256": raw_file_sha256(path),
        "measurement_evidence": "MEASURED_FILE_BYTES_AND_DECODED_PIXELS",
    }
    result.update(geometry(w, h))
    return result


def inspect_sheet(path: Path) -> dict[str, Any]:
    w, h = dimensions_of(path)
    result: dict[str, Any] = {
        "asset_kind": "sheet_2x2",
        "asset_path": str(path.resolve()),
        "file_sha256": raw_file_sha256(path),
        "sheet_dimensions": [w, h],
        "sheet_at_least_2160_by_3840": w >= 2 * MIN_W and h >= 2 * MIN_H,
        "sheet_ratio_exact_9_by_16": 16 * w == 9 * h,
        "has_even_dimensions_for_equal_2x2": w % 2 == 0 and h % 2 == 0,
        "expected_page_count": 4,
        "measured_page_count_by_quadrant": 4 if w % 2 == 0 and h % 2 == 0 else 0,
        "measurement_evidence": "MEASURED_FILE_BYTES_AND_DECODED_PIXELS",
        "page_crops": [],
        "native_provider_detail_status": "NOT_VERIFIABLE_FROM_FILE_DIMENSIONS_ALONE",
    }
    if w % 2 or h % 2:
        result["geometry_pass"] = False
        result["warning"] = "CANNOT_SPLIT_INTO_EQUAL_INTEGER_QUADRANTS"
        return result
    pw, ph = w // 2, h // 2
    crop_boxes = [
        (0, 0, pw, ph), (pw, 0, w, ph),
        (0, ph, pw, h), (pw, ph, w, h),
    ]
    digests: list[str] = []
    with Image.open(path) as sheet:
        for label, bbox in zip(ORDER, crop_boxes):
            cell = sheet.crop(bbox)
            digests.append(pixel_sha256(cell))
            page = {"cell": label, "bbox_ltrb": list(bbox), "pixel_sha256": digests[-1]}
            page.update(geometry(*cell.size))
            result["page_crops"].append(page)
    result["exact_duplicate_cell_count"] = 4 - len(set(digests))
    result["geometry_pass"] = bool(
        result["sheet_at_least_2160_by_3840"]
        and result["sheet_ratio_exact_9_by_16"]
        and all(cell["geometry_pass"] for cell in result["page_crops"])
    )
    result["structural_pixel_checks_pass"] = bool(
        result["geometry_pass"] and len(set(digests)) == 4
    )
    result["narrative_scene_qa"] = "NOT_AUTOMATABLE_REQUIRES_HUMAN_REVIEW"
    result["logo_header_qa"] = "NOT_AUTOMATABLE_REQUIRES_HUMAN_REVIEW"
    result["challenge_qa"] = "NOT_AUTOMATABLE_REQUIRES_HUMAN_REVIEW"
    if not result["geometry_pass"]:
        result["warning"] = "SHEET_OR_CELL_ASPECT_OR_MIN_DETAIL_FAIL"
    elif result["exact_duplicate_cell_count"]:
        result["warning"] = "IDENTICAL_CELLS_REQUIRE_REVIEW"
    else:
        result["warning"] = None
    return result


def assemble(pages: list[Path], output: Path) -> dict[str, Any]:
    if len(pages) != 4:
        raise ValueError("Exactly four page paths required; order must be TL TR BL BR")
    if output.exists():
        raise FileExistsError(f"Output exists; refusing overwrite: {output}")
    resolved = [p.resolve() for p in pages]
    if len(set(resolved)) != 4:
        raise ValueError("Pages must have four different input paths")
    inspections = [inspect_page(p) for p in pages]
    if not all(entry["geometry_pass"] for entry in inspections):
        raise ValueError("Every input page must be exact 9:16 and >=1080x1920")
    widths = {entry["pixels"][0] for entry in inspections}
    heights = {entry["pixels"][1] for entry in inspections}
    if len(widths) != 1 or len(heights) != 1:
        raise ValueError("All four pages must have exactly the same pixel dimensions")
    if len({entry["file_sha256"] for entry in inspections}) != 4:
        raise ValueError("Exactly identical image input files are not distinct story pages")
    w, h = next(iter(widths)), next(iter(heights))
    with Image.open(pages[0]) as first:
        format_mode = "RGBA" if ("A" in first.getbands()) else "RGB"
    sheet = Image.new(format_mode, (2 * w, 2 * h))
    pixel_digest_inputs: list[str] = []
    for idx, path in enumerate(pages):
        with Image.open(path) as page:
            render = page.convert(format_mode)
            pixel_digest_inputs.append(pixel_sha256(render))
            sheet.paste(render, ((idx % 2) * w, (idx // 2) * h))
    if len(set(pixel_digest_inputs)) != 4:
        raise ValueError("Exactly identical decoded page pixels are not four unique pages")
    output.parent.mkdir(parents=True, exist_ok=True)
    # Use a non-overwriting exclusive placeholder; then replace only the placeholder.
    fd = os.open(output, os.O_CREAT | os.O_EXCL | os.O_WRONLY, 0o644)
    os.close(fd)
    try:
        with tempfile.NamedTemporaryFile(dir=output.parent, suffix=".png", delete=False) as tmp:
            temp_path = Path(tmp.name)
        try:
            sheet.save(temp_path, format="PNG", optimize=True)
            with Image.open(temp_path) as img:
                for i, bbox in enumerate(((0, 0, w, h), (w, 0, 2*w, h),
                                          (0, h, w, 2*h), (w, h, 2*w, 2*h))):
                    if pixel_sha256(img.crop(bbox)) != pixel_digest_inputs[i]:
                        raise AssertionError(f"Assembly changed pixel values: {ORDER[i]}")
            os.replace(temp_path, output)
        finally:
            if temp_path.exists():
                temp_path.unlink()
    except BaseException:
        if output.exists():
            output.unlink()
        raise
    result = inspect_sheet(output)
    result["source_page_paths_TL_TR_BL_BR"] = [str(p.resolve()) for p in pages]
    result["source_page_file_sha256"] = [e["file_sha256"] for e in inspections]
    result["lossless_page_pixel_identity"] = True
    result["native_provider_detail_status"] = "UNVERIFIED_REQUIRES_SOURCE_PROVIDER_EVIDENCE"
    return result


def selftest() -> dict[str, Any]:
    cases: dict[str, Any] = {}
    with tempfile.TemporaryDirectory(prefix="cc03_098_selftest_") as directory:
        root = Path(directory)
        # Four distinct, correctly dimensioned test fixtures, not generative art.
        paths: list[Path] = []
        for i, color in enumerate(("#881122", "#116677", "#665500", "#224477")):
            page = Image.new("RGB", (MIN_W, MIN_H), color)
            draw = ImageDraw.Draw(page)
            draw.rectangle((24+i*5, 28, 170+i*11, 210), fill="#ffffff")
            p = root / f"fixture_P{i+1:02d}.png"
            page.save(p)
            paths.append(p)
        result = assemble(paths, root / "four_page_sheet.png")
        cases["T098_01_native_sized_fixture"] = result["geometry_pass"] and result["lossless_page_pixel_identity"]
        cases["T098_02_reject_1024x1536_sheet"] = not (
            geometry(512, 768)["geometry_pass"] or geometry(1024, 1536)["geometry_pass"]
        )
        cases["T098_03_correct_ratio_but_too_small"] = (
            geometry(540, 960)["exact_9_by_16"] and not geometry(540, 960)["geometry_pass"]
        )
        cases["T098_04_reject_2x3_page"] = not geometry(512, 768)["exact_9_by_16"]
        try:
            assemble([paths[0], paths[0], paths[2], paths[3]], root / "should_not_exist.png")
            cases["T098_05_reject_same_source_path"] = False
        except ValueError:
            cases["T098_05_reject_same_source_path"] = True
        cases["T098_06_prevent_false_native_inference"] = (
            result["native_provider_detail_status"] == "UNVERIFIED_REQUIRES_SOURCE_PROVIDER_EVIDENCE"
        )
    return {
        "test_kind": "SYNTHETIC_TEST_ONLY",
        "all_pass": all(cases.values()),
        "cases": cases,
        "disclaimer": "Synthetic geometry and lossless assembly only; no comic image generation or human story QA was performed",
    }


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    actions = parser.add_subparsers(dest="action", required=True)
    inspect = actions.add_parser("inspect", help="Inspect a real page, cover or 2x2 sheet")
    inspect.add_argument("--kind", choices=("page", "cover", "sheet"), default="sheet")
    inspect.add_argument("--input", type=Path, required=True)
    inspect.add_argument("--json", type=Path)
    assemble_cmd = actions.add_parser("assemble", help="Losslessly assemble exactly four full-size pages")
    assemble_cmd.add_argument("--pages", type=Path, nargs=4, required=True, metavar="PAGE")
    assemble_cmd.add_argument("--output", type=Path, required=True)
    assemble_cmd.add_argument("--json", type=Path)
    test = actions.add_parser("selftest", help="Run synthetic exact-ratio and assembly tests")
    test.add_argument("--json", type=Path)
    args = parser.parse_args()
    try:
        if args.action == "inspect":
            result = inspect_sheet(args.input) if args.kind == "sheet" else inspect_page(args.input, args.kind)
            success = result["geometry_pass"]
        elif args.action == "assemble":
            result = assemble(args.pages, args.output)
            success = result["structural_pixel_checks_pass"]
        else:
            result = selftest()
            success = result["all_pass"]
        payload = json.dumps(result, indent=2, ensure_ascii=False)
        print(payload)
        output_json = getattr(args, "json", None)
        if output_json is not None:
            output_json.parent.mkdir(parents=True, exist_ok=True)
            output_json.write_text(payload + "\n", encoding="utf-8")
        return 0 if success else 2
    except (OSError, ValueError, AssertionError) as error:
        print(json.dumps({"error": type(error).__name__, "detail": str(error)}, indent=2), file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
