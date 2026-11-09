#!/usr/bin/env python3
"""CC-04 v0.08.00 non-generative comic asset processing.

Requires Pillow. Operates on actual local inputs only: no network, no AI,
no identity construction, no watermark/text removal, no inferred permissions.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import sys
from datetime import datetime, timezone
from pathlib import Path
from PIL import Image, ImageFilter, ImageOps

SOFTWARE = "cc_media_reference_crops_v008.py"
Image.MAX_IMAGE_PIXELS = 150_000_000  # protect against absurd decompression input


def digest(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as file:
        for chunk in iter(lambda: file.read(1024 * 1024), b""):
            h.update(chunk)
    return h.hexdigest()


def read_image(path: Path) -> Image.Image:
    if not path.is_file():
        raise ValueError(f"Input image not found: {path}")
    with Image.open(path) as src:
        src.load()
        return ImageOps.exif_transpose(src).convert("RGB")


def check_ratio(width: int, height: int, rw: int, rh: int) -> None:
    if width <= 0 or height <= 0:
        raise ValueError("Width and height must be positive")
    # One-pixel tolerance permits odd-size page crops with rounding.
    if abs(width * rh - height * rw) > max(width, height):
        raise ValueError(f"Aspect mismatch: {width}x{height} is not {rw}:{rh}")


def record_image(path: Path) -> dict:
    with Image.open(path) as im:
        width, height = ImageOps.exif_transpose(im).size
    return {"file": str(path), "sha256": digest(path), "width": width, "height": height}


def metadata(action: str, source: Path, args: dict, outputs: list[dict], warnings: list[str]) -> dict:
    return {
        "schema": "cc-media-reference-provenance-0.08.00",
        "software": SOFTWARE,
        "utc_generated": datetime.now(timezone.utc).isoformat(),
        "operation": action,
        "source": record_image(source),
        "parameters": args,
        "outputs": outputs,
        "warnings": warnings,
        "authz_assertion": "none: operator must verify episode, source, identity and license",
        "render_assertion": "reference editing only: no AI video rendered",
    }


def write_manifest(path: Path, data: dict) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(data, indent=2, ensure_ascii=False) + "\n", encoding="utf-8")


def slice_collage(source: Path, outdir: Path, rows: int, cols: int,
                  min_width: int = 1080, min_height: int = 1920,
                  strict: bool = False) -> dict:
    if rows <= 0 or cols <= 0:
        raise ValueError("Rows and columns must be positive")
    im = read_image(source)
    w, h = im.size
    if w % cols or h % rows:
        raise ValueError("Image dimensions must be divisible exactly by rows and columns; "
                         "gutter, padding or irregular grids require manual preparation")
    cellw, cellh = w // cols, h // rows
    check_ratio(cellw, cellh, 9, 16)
    if strict and (cellw < min_width or cellh < min_height):
        raise ValueError(f"Strict native page size failed: {cellw}x{cellh}, "
                         f"minimum {min_width}x{min_height}")
    warnings = []
    if cellw < min_width or cellh < min_height:
        warnings.append("LOW_RES_REFERENCE: page tiles are below requested native "
                        f"{min_width}x{min_height}; do not label as full-quality pages")
    outdir.mkdir(parents=True, exist_ok=True)
    outputs = []
    index = 0
    for row in range(rows):
        for col in range(cols):
            index += 1
            box = (col * cellw, row * cellh, (col + 1) * cellw, (row + 1) * cellh)
            target = outdir / f"P{index:02d}.png"
            im.crop(box).save(target, format="PNG")
            item = record_image(target)
            item.update({"page_id": f"P{index:02d}", "row": row + 1,
                         "column": col + 1, "crop_xyxy_px": list(box),
                         "quality_flag": "LOW_RES_REFERENCE" if warnings else "PIXEL_GEOMETRY_PASS"})
            outputs.append(item)
    result = metadata("slice", source, {"rows": rows, "cols": cols,
                "min_native_width": min_width, "min_native_height": min_height,
                "strict": strict, "row_major": True}, outputs, warnings)
    write_manifest(outdir / "source_slices_manifest.json", result)
    return result


def extract_crop(source: Path, target: Path, coords: tuple[float, float, float, float],
                 normalized: bool = False) -> dict:
    im = read_image(source)
    width, height = im.size
    coords = tuple(float(x) for x in coords)
    if normalized:
        if any(x < 0.0 or x > 1.0 for x in coords):
            raise ValueError("Normalized crop boundaries must be within [0,1]")
        pixel = (round(coords[0] * width), round(coords[1] * height),
                 round(coords[2] * width), round(coords[3] * height))
    else:
        pixel = tuple(round(x) for x in coords)
    x0, y0, x1, y1 = pixel
    if not (0 <= x0 < x1 <= width and 0 <= y0 < y1 <= height):
        raise ValueError(f"Crop rectangle out of bounds or empty: {pixel} in {im.size}")
    target.parent.mkdir(parents=True, exist_ok=True)
    im.crop(pixel).save(target, format="PNG")
    result = metadata("crop", source, {"crop_xyxy_px": list(pixel),
                 "user_coords": list(coords), "normalized": normalized},
                 [record_image(target)], ["Check manually that crop excludes no essential source content"])
    write_manifest(target.with_suffix(".provenance.json"), result)
    return result


def stage_portrait(source: Path, target: Path, width: int = 1920, height: int = 1080,
                   strategy: str = "fit", blur_radius: int = 45,
                   allow_upscale: bool = False) -> dict:
    check_ratio(width, height, 16, 9)
    im = read_image(source)
    sw, sh = im.size
    target.parent.mkdir(parents=True, exist_ok=True)
    warnings = []
    if strategy == "fit":
        # Background: a blurred (and therefore explicitly non-evidentiary) image-derived fill.
        fill = ImageOps.fit(im, (width, height), method=Image.Resampling.LANCZOS,
                            centering=(0.5, 0.5)).filter(ImageFilter.GaussianBlur(blur_radius))
        ratio = min(width / sw, height / sh, 1e9 if allow_upscale else 1.0)
        iw = max(1, round(sw * ratio))
        ih = max(1, round(sh * ratio))
        front = im.resize((iw, ih), Image.Resampling.LANCZOS)
        ox, oy = (width - iw) // 2, (height - ih) // 2
        fill.paste(front, (ox, oy))
        result_image = fill
        used = [ox, oy, ox + iw, oy + ih]
        if iw < width // 2:
            warnings.append("Foreground has narrow aspect; blurred side stage is substantial")
        if ratio > 1:
            warnings.append("CENTER_ART_UPSCALED: output dimensions exceed source detail")
    elif strategy == "cover":
        # Crops the input to the requested aspect. No distortions; document discarded area.
        if sw / sh >= width / height:
            xspan = round(sh * width / height)
            x0 = (sw - xspan) // 2
            clipbox = (x0, 0, x0 + xspan, sh)
        else:
            yspan = round(sw * height / width)
            y0 = (sh - yspan) // 2
            clipbox = (0, y0, sw, y0 + yspan)
        result_image = im.crop(clipbox).resize((width, height), Image.Resampling.LANCZOS)
        used = list(clipbox)
        warnings.append("COVER_CROPS_CONTENT: operator must inspect lost source boundaries")
        if width > clipbox[2] - clipbox[0] or height > clipbox[3] - clipbox[1]:
            warnings.append("IMAGE_UPSCALED: source detail cannot be inferred from new pixel dimensions")
    else:
        raise ValueError(f"Unknown staging strategy: {strategy}")
    result_image.save(target, "PNG")
    result = metadata("stage", source, {"strategy": strategy,
               "output_width": width, "output_height": height,
               "foreground_or_source_area": used, "blur_radius": blur_radius if strategy == "fit" else None,
               "allow_upscale": allow_upscale if strategy == "fit" else None},
               [record_image(target)], warnings)
    write_manifest(target.with_suffix(".provenance.json"), result)
    return result


def inspect(source: Path) -> dict:
    im = read_image(source)
    out = metadata("inspect", source, {"is_local_file": True}, [],
                   ["Inspection cannot determine rights, identity, legibility or crop intent"])
    out["source"]["mode"] = im.mode
    return out


def make_parser() -> argparse.ArgumentParser:
    p = argparse.ArgumentParser(description=__doc__)
    sp = p.add_subparsers(dest="command", required=True)
    i = sp.add_parser("inspect", help="Print actual image geometry and SHA-256")
    i.add_argument("--input", required=True)
    s = sp.add_parser("slice", help="Slice a uniform unguttered collage into row-major PNG pages")
    s.add_argument("--input", required=True); s.add_argument("--outdir", required=True)
    s.add_argument("--rows", type=int, default=4); s.add_argument("--cols", type=int, default=4)
    s.add_argument("--min-width", type=int, default=1080)
    s.add_argument("--min-height", type=int, default=1920)
    s.add_argument("--strict", action="store_true")
    c = sp.add_parser("crop", help="Non-destructive pixel or normalized-rect crop")
    c.add_argument("--input", required=True); c.add_argument("--out", required=True)
    c.add_argument("--x0", type=float, required=True); c.add_argument("--y0", type=float, required=True)
    c.add_argument("--x1", type=float, required=True); c.add_argument("--y1", type=float, required=True)
    c.add_argument("--normalized", action="store_true")
    v = sp.add_parser("stage", help="Fit or cover image into a 16:9 shot reference")
    v.add_argument("--input", required=True); v.add_argument("--out", required=True)
    v.add_argument("--width", type=int, default=1920); v.add_argument("--height", type=int, default=1080)
    v.add_argument("--strategy", choices=("fit", "cover"), default="fit")
    v.add_argument("--blur", type=int, default=45); v.add_argument("--allow-upscale", action="store_true")
    return p


def main(argv: list[str] | None = None) -> int:
    ns = make_parser().parse_args(argv)
    try:
        if ns.command == "inspect":
            result = inspect(Path(ns.input))
        elif ns.command == "slice":
            result = slice_collage(Path(ns.input), Path(ns.outdir), ns.rows, ns.cols,
                                   ns.min_width, ns.min_height, ns.strict)
        elif ns.command == "crop":
            result = extract_crop(Path(ns.input), Path(ns.out),
                        (ns.x0, ns.y0, ns.x1, ns.y1), ns.normalized)
        elif ns.command == "stage":
            result = stage_portrait(Path(ns.input), Path(ns.out), ns.width, ns.height,
                                    ns.strategy, ns.blur, ns.allow_upscale)
        else:
            raise AssertionError("unreachable")
        print(json.dumps(result, indent=2, ensure_ascii=False))
        return 0
    except (OSError, ValueError, Image.DecompressionBombError) as e:
        print(f"CC-MEDIA ERROR: {e}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
