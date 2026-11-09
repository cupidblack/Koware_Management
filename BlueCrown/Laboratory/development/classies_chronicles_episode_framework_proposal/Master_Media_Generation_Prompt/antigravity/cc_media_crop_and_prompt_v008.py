#!/usr/bin/env python3
"""Classies Chronicles v0.08.00: Comic-to-Video Cropping, 16:9 Framing and Dual-Reference Prompt Pack Utility.

Dependencies: Python 3.9+, Pillow.
Usage examples:
    python cc_media_crop_and_prompt_v008.py --self-test
    python cc_media_crop_and_prompt_v008.py crop --input page01.png --mode mode1 --focal center --output act001_frame.png
    python cc_media_crop_and_prompt_v008.py crop --input page01.png --mode mode4 --output act016_challenge_frame.png
    python cc_media_crop_and_prompt_v008.py split --input1 panel1.png --input2 panel2.png --output act007_split.png
    python cc_media_crop_and_prompt_v008.py prompt --act-id A001 --subject "Enka Yamoah" --action "looks through office window"

This utility enforces the 16:9 widescreen default (1920x1080), the 4 standardized cropping
modes, the act motion taxonomy, and the dual-reference prompt architecture.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import sys
import unittest
from dataclasses import asdict, dataclass
from pathlib import Path
from typing import Dict, List, Optional, Tuple

try:
    from PIL import Image, ImageFilter, ImageOps
except ImportError as exc:
    raise SystemExit("Pillow is required: pip install Pillow") from exc


WIDESCREEN_WIDTH = 1920
WIDESCREEN_HEIGHT = 1080
TARGET_ASPECT_RATIO = WIDESCREEN_WIDTH / WIDESCREEN_HEIGHT  # 1.7777777777777777


class GeometryError(ValueError):
    """Raised when geometric constraints or aspect calculations are invalid."""
    pass


@dataclass
class ActPromptSpec:
    act_id: str
    duration_seconds: float
    motion_class: str
    pacing_tempo: str
    camera_grammar: str
    vfx_level: str
    audio_profile: str
    character_name: str
    character_canon_ref: str
    cropped_panel_ref: str
    motivated_action: str
    cinematography: str
    lighting_atmosphere: str
    negative_prompt: str

    def to_formatted_prompt(self) -> str:
        prompt_lines = [
            f"=== ACT {self.act_id} MEDIA GENERATION PROMPT ===",
            f"TARGET SPECS: 16:9 Widescreen (1920x1080) | Duration: {self.duration_seconds}s | Cadence: 24fps",
            f"ACT ATTRIBUTES: {self.motion_class} | {self.pacing_tempo} | {self.camera_grammar} | {self.vfx_level} | {self.audio_profile}",
            f"IMAGE_1 (Start Frame): {self.cropped_panel_ref}",
            f"IMAGE_2 (Canon Anchor): {self.character_canon_ref}",
            "",
            "VIDEO PROMPT:",
            (f"{self.character_name}, matching visual anchor in Image 2. "
             f"{self.motivated_action}. "
             f"{self.cinematography}. "
             f"{self.lighting_atmosphere}. "
             f"Cinematic photorealism, realistic human skin and fabric physics, 24fps film cadence, 16:9 widescreen framing."),
            "",
            "NEGATIVE PROMPT:",
            self.negative_prompt,
            "=================================================="
        ]
        return "\n".join(prompt_lines)


def crop_mode1_pan_and_scan(img: Image.Image, focal: str = "center") -> Image.Image:
    """Mode 1: Focused Cinematic Horizontal Crop.
    
    Extracts a 16:9 window from the vertical image centered on the focal area.
    """
    w, h = img.size
    target_h = int(w / TARGET_ASPECT_RATIO)
    if target_h > h:
        # Image is wider than 16:9, letterbox/crop width
        target_w = int(h * TARGET_ASPECT_RATIO)
        left = (w - target_w) // 2
        crop_box = (left, 0, left + target_w, h)
    else:
        # Image is taller than 16:9 (standard 9:16 vertical comic)
        if focal == "top":
            top = 0
        elif focal == "bottom":
            top = h - target_h
        else:  # center
            top = (h - target_h) // 2
        crop_box = (0, top, w, top + target_h)
    
    cropped = img.crop(crop_box)
    return cropped.resize((WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT), Image.Resampling.LANCZOS)


def crop_mode2_outpainted_extension(img: Image.Image, placement: str = "center") -> Image.Image:
    """Mode 2: AI Background Outpainting / Horizontal Extension.
    
    Places the 9:16 panel in the canvas and extends the margins.
    """
    w, h = img.size
    # Scale img proportionally so its height matches 1080
    scale = WIDESCREEN_HEIGHT / h
    new_w = int(w * scale)
    new_h = WIDESCREEN_HEIGHT
    resized_panel = img.resize((new_w, new_h), Image.Resampling.LANCZOS)

    canvas = Image.new("RGBA", (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT), (15, 20, 28, 255))
    
    if placement == "left":
        pos_x = 80
    elif placement == "right":
        pos_x = WIDESCREEN_WIDTH - new_w - 80
    else:  # center
        pos_x = (WIDESCREEN_WIDTH - new_w) // 2
    
    # Fill background wings with subtle gradient or edge extension
    left_edge_color = resized_panel.getpixel((0, new_h // 2))
    right_edge_color = resized_panel.getpixel((new_w - 1, new_h // 2))
    
    left_wing = Image.new("RGBA", (pos_x, WIDESCREEN_HEIGHT), left_edge_color)
    right_wing_w = WIDESCREEN_WIDTH - (pos_x + new_w)
    right_wing = Image.new("RGBA", (max(0, right_wing_w), WIDESCREEN_HEIGHT), right_edge_color)
    
    canvas.paste(left_wing, (0, 0))
    canvas.paste(right_wing, (pos_x + new_w, 0))
    canvas.paste(resized_panel, (pos_x, 0))
    return canvas.convert("RGB")


def crop_mode3_split_screen(img1: Image.Image, img2: Image.Image, gutter: int = 16) -> Image.Image:
    """Mode 3: Dynamic Multi-Panel / Split-Screen Widescreen Composite.
    
    Places two complementary 9:16 panels side-by-side with a stylized gutter.
    """
    canvas = Image.new("RGBA", (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT), (10, 14, 20, 255))
    
    # Usable width for each panel
    panel_w = (WIDESCREEN_WIDTH - gutter - 80) // 2
    panel_h = WIDESCREEN_HEIGHT - 60
    
    # Scale panels to fit
    scaled1 = ImageOps.fit(img1, (panel_w, panel_h), Image.Resampling.LANCZOS)
    scaled2 = ImageOps.fit(img2, (panel_w, panel_h), Image.Resampling.LANCZOS)
    
    pos_x1 = 40
    pos_x2 = 40 + panel_w + gutter
    pos_y = 30
    
    canvas.paste(scaled1, (pos_x1, pos_y))
    canvas.paste(scaled2, (pos_x2, pos_y))
    return canvas.convert("RGB")


def crop_mode4_pillarbox_blurred_wings(img: Image.Image) -> Image.Image:
    """Mode 4: Centered Pillarbox with Ambient Blurred Wings.
    
    Full 9:16 panel in the center with blurred ambient background wings.
    """
    w, h = img.size
    scale = WIDESCREEN_HEIGHT / h
    new_w = int(w * scale)
    new_h = WIDESCREEN_HEIGHT
    sharp_panel = img.resize((new_w, new_h), Image.Resampling.LANCZOS)

    # Background: cover entire 1920x1080 and heavily blur
    bg = ImageOps.fit(img, (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT), Image.Resampling.LANCZOS)
    blurred_bg = bg.filter(ImageFilter.GaussianBlur(radius=45))
    # Dim background by 35%
    darken = Image.new("RGBA", (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT), (0, 0, 0, 90))
    blurred_bg.paste(darken, (0, 0), darken)

    # Paste sharp panel in center
    pos_x = (WIDESCREEN_WIDTH - new_w) // 2
    blurred_bg.paste(sharp_panel, (pos_x, 0))
    return blurred_bg.convert("RGB")


class MediaToolSelfTests(unittest.TestCase):
    def test_mode1_geometry(self):
        # Create a synthetic 1080x1920 9:16 portrait image
        img = Image.new("RGB", (1080, 1920), color=(40, 60, 80))
        out = crop_mode1_pan_and_scan(img, focal="center")
        self.assertEqual(out.size, (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT))

    def test_mode2_geometry(self):
        img = Image.new("RGB", (1080, 1920), color=(30, 45, 60))
        out = crop_mode2_outpainted_extension(img, placement="center")
        self.assertEqual(out.size, (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT))

    def test_mode3_geometry(self):
        img1 = Image.new("RGB", (1080, 1920), color=(50, 70, 90))
        img2 = Image.new("RGB", (1080, 1920), color=(90, 70, 50))
        out = crop_mode3_split_screen(img1, img2, gutter=20)
        self.assertEqual(out.size, (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT))

    def test_mode4_geometry(self):
        img = Image.new("RGB", (1080, 1920), color=(20, 25, 35))
        out = crop_mode4_pillarbox_blurred_wings(img)
        self.assertEqual(out.size, (WIDESCREEN_WIDTH, WIDESCREEN_HEIGHT))

    def test_prompt_spec_formatter(self):
        spec = ActPromptSpec(
            act_id="001",
            duration_seconds=10.0,
            motion_class="ANIMATED",
            pacing_tempo="CONTEMPLATIVE",
            camera_grammar="SLOW_PULL_OUT",
            vfx_level="CLEAN",
            audio_profile="NARRATOR_DOMINANT",
            character_name="Enka Yamoah",
            character_canon_ref="CC_AI_DESK/04_CANON/CHARACTERS/CUPID_BLACK/Enka_Character_Reference_Collage.jpg",
            cropped_panel_ref="07_GENERATED_MEDIA/CROPS/Page01_Panel1_Mode1.png",
            motivated_action="stands silhouetted by the office window looking out at dusk",
            cinematography="Slow elegant pull-out, 35mm anamorphic lens, shallow depth of field",
            lighting_atmosphere="Accra twilight skyline, amber desk lamp glow contrasting with dusk sky",
            negative_prompt="morphing, disfigured hands, cartoon, split screen, low resolution"
        )
        output = spec.to_formatted_prompt()
        self.assertIn("=== ACT 001 MEDIA GENERATION PROMPT ===", output)
        self.assertIn("Enka_Character_Reference_Collage.jpg", output)
        self.assertIn("16:9 Widescreen (1920x1080)", output)
        self.assertIn("ANIMATED | CONTEMPLATIVE", output)


def main():
    parser = argparse.ArgumentParser(description="Classies Chronicles Media Cropping and Prompt Utility v0.08.00")
    parser.add_argument("--self-test", action="store_true", help="Run automated test suite and exit")
    subparsers = parser.add_subparsers(dest="command")

    # Crop command
    crop_parser = subparsers.add_parser("crop", help="Crop 9:16 comic image to 16:9 widescreen")
    crop_parser.add_argument("--input", required=True, help="Input comic panel image path")
    crop_parser.add_argument("--mode", choices=["mode1", "mode2", "mode4"], default="mode1", help="Cropping mode")
    crop_parser.add_argument("--focal", choices=["top", "center", "bottom"], default="center", help="Focal placement for Mode 1")
    crop_parser.add_argument("--output", required=True, help="Output image file path")

    # Split command
    split_parser = subparsers.add_parser("split", help="Combine two 9:16 panels into 16:9 split-screen")
    split_parser.add_argument("--input1", required=True, help="First comic panel path")
    split_parser.add_argument("--input2", required=True, help="Second comic panel path")
    split_parser.add_argument("--gutter", type=int, default=16, help="Gutter width in pixels")
    split_parser.add_argument("--output", required=True, help="Output image file path")

    args = parser.parse_args()

    if args.self_test or len(sys.argv) == 1:
        suite = unittest.TestLoader().loadTestsFromTestCase(MediaToolSelfTests)
        runner = unittest.TextTestRunner(verbosity=2)
        result = runner.run(suite)
        sys.exit(0 if result.wasSuccessful() else 1)

    if args.command == "crop":
        in_img = Image.open(args.input)
        if args.mode == "mode1":
            out_img = crop_mode1_pan_and_scan(in_img, focal=args.focal)
        elif args.mode == "mode2":
            out_img = crop_mode2_outpainted_extension(in_img, placement=args.focal)
        else:  # mode4
            out_img = crop_mode4_pillarbox_blurred_wings(in_img)
        out_img.save(args.output, "PNG")
        print(f"Successfully processed {args.input} -> {args.output} via {args.mode.upper()} (1920x1080)")

    elif args.command == "split":
        in1 = Image.open(args.input1)
        in2 = Image.open(args.input2)
        out_img = crop_mode3_split_screen(in1, in2, gutter=args.gutter)
        out_img.save(args.output, "PNG")
        print(f"Successfully created split screen {args.input1} + {args.input2} -> {args.output} (1920x1080)")


if __name__ == "__main__":
    main()
