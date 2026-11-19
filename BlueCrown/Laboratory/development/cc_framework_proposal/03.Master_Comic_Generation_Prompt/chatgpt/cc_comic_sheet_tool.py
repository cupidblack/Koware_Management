#!/usr/bin/env python3
"""Classies Chronicles 2x2 comic sheet assembler/slicer.

Requires Pillow. Does not resize, stretch, or infer gutters.
"""
from __future__ import annotations
import argparse
from pathlib import Path
from PIL import Image

def is_9_16(width: int, height: int) -> bool:
    return width > 0 and height > 0 and width * 16 == height * 9

def open_rgb(path: Path) -> Image.Image:
    im = Image.open(path)
    im.load()
    return im.convert("RGB")

def validate_page(path: Path) -> tuple[int, int]:
    with Image.open(path) as im:
        w, h = im.size
    if not is_9_16(w, h):
        raise ValueError(f"{path}: page must be exact 9:16; got {w}x{h}")
    return w, h

def assemble(pages: list[Path], output: Path) -> tuple[int, int]:
    if len(pages) != 4:
        raise ValueError("Exactly four pages are required for a 2x2 sheet.")
    sizes = [validate_page(p) for p in pages]
    if len(set(sizes)) != 1:
        raise ValueError(f"All four pages must have identical dimensions; got {sizes}")
    w, h = sizes[0]
    canvas = Image.new("RGB", (2*w, 2*h), "white")
    positions = [(0, 0), (w, 0), (0, h), (w, h)]
    for p, xy in zip(pages, positions):
        canvas.paste(open_rgb(p), xy)
    if not is_9_16(*canvas.size):
        raise AssertionError("Internal geometry error: 2x2 composite must be 9:16.")
    output.parent.mkdir(parents=True, exist_ok=True)
    canvas.save(output, format="PNG", optimize=True)
    return canvas.size

def slice_sheet(source: Path, output_dir: Path) -> list[Path]:
    im = open_rgb(source)
    sw, sh = im.size
    if sw % 2 or sh % 2:
        raise ValueError(f"Sheet dimensions must be divisible by two; got {sw}x{sh}")
    w, h = sw // 2, sh // 2
    if not is_9_16(w, h):
        raise ValueError(f"Each cell must be exact 9:16; inferred {w}x{h}")
    if not is_9_16(sw, sh):
        raise ValueError(f"Composite must be exact 9:16; got {sw}x{sh}")
    output_dir.mkdir(parents=True, exist_ok=True)
    boxes = [
        (0, 0, w, h),
        (w, 0, 2*w, h),
        (0, h, w, 2*h),
        (w, h, 2*w, 2*h),
    ]
    outputs = []
    for idx, box in enumerate(boxes, start=1):
        dest = output_dir / f"P{idx:02d}.png"
        im.crop(box).save(dest, format="PNG", optimize=True)
        outputs.append(dest)
    return outputs

def self_test() -> None:
    from tempfile import TemporaryDirectory
    with TemporaryDirectory() as td:
        root = Path(td)
        pages = []
        for i in range(4):
            p = root / f"page{i+1}.png"
            Image.new("RGB", (90, 160), (i*40, 30, 60)).save(p)
            pages.append(p)
        sheet = root / "sheet.png"
        size = assemble(pages, sheet)
        assert size == (180, 320), size
        crops = slice_sheet(sheet, root / "crops")
        assert len(crops) == 4
        assert all(Image.open(p).size == (90, 160) for p in crops)
        bad = root / "bad.png"
        Image.new("RGB", (90, 150)).save(bad)
        try:
            validate_page(bad)
        except ValueError:
            pass
        else:
            raise AssertionError("Invalid aspect ratio was not rejected.")
        try:
            assemble(pages[:3], root / "bad_sheet.png")
        except ValueError:
            pass
        else:
            raise AssertionError("Three-page input was not rejected.")
    print("SELF-TEST: 4/4 PASS — assemble, 2x2 ratio, slice, invalid-ratio rejection, wrong-count rejection.")

def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    sub = parser.add_subparsers(dest="command", required=True)
    pa = sub.add_parser("assemble", help="assemble exactly four 9:16 pages")
    pa.add_argument("--pages", nargs=4, type=Path, required=True)
    pa.add_argument("--output", type=Path, required=True)
    ps = sub.add_parser("slice", help="slice an equal-cell no-gutter 2x2 sheet")
    ps.add_argument("--input", type=Path, required=True)
    ps.add_argument("--output-dir", type=Path, required=True)
    sub.add_parser("self-test", help="run synthetic geometry tests")
    args = parser.parse_args()
    try:
        if args.command == "assemble":
            size = assemble(args.pages, args.output)
            print(f"ASSEMBLED: {args.output} ({size[0]}x{size[1]})")
        elif args.command == "slice":
            files = slice_sheet(args.input, args.output_dir)
            for f in files:
                print(f"SLICED: {f}")
        else:
            self_test()
    except Exception as exc:
        print(f"ERROR: {exc}")
        return 2
    return 0

if __name__ == "__main__":
    raise SystemExit(main())
