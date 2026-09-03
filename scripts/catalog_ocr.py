from __future__ import annotations

import argparse
import json
from pathlib import Path

import numpy as np
import pymupdf
from PIL import Image, ImageDraw
from rapidocr_onnxruntime import RapidOCR


ROOT = Path(__file__).resolve().parents[1]
PDF = ROOT / "storage" / "app" / "catalog" / "catalogo-hgw-agosto-2026.pdf"
OUTPUT = ROOT / "storage" / "app" / "catalog" / "extraction"


def render_page(page: pymupdf.Page, scale: float = 1.7) -> Image.Image:
    pixmap = page.get_pixmap(matrix=pymupdf.Matrix(scale, scale), alpha=False)
    return Image.frombytes("RGB", (pixmap.width, pixmap.height), pixmap.samples)


def ordered_lines(result: list[list[object]] | None) -> list[dict[str, object]]:
    if not result:
        return []

    lines: list[dict[str, object]] = []
    for box, text, score in result:
        points = [[float(x), float(y)] for x, y in box]
        lines.append({"text": str(text), "confidence": round(float(score), 4), "box": points})
    return sorted(lines, key=lambda item: (item["box"][0][1], item["box"][0][0]))


def contact_sheet(thumbnails: list[tuple[int, Image.Image]]) -> Image.Image:
    width, height = 1200, 315
    sheet = Image.new("RGB", (width, height * len(thumbnails)), "white")
    drawer = ImageDraw.Draw(sheet)
    for row, (page_number, image) in enumerate(thumbnails):
        preview = image.copy()
        preview.thumbnail((width - 70, height - 20))
        y = row * height + 10
        sheet.paste(preview, (60, y))
        drawer.text((12, y + 4), str(page_number), fill="#183b2b")
    return sheet


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--start", type=int, default=1)
    parser.add_argument("--end", type=int)
    args = parser.parse_args()

    OUTPUT.mkdir(parents=True, exist_ok=True)
    pages_dir = OUTPUT / "pages"
    sheets_dir = OUTPUT / "sheets"
    pages_dir.mkdir(exist_ok=True)
    sheets_dir.mkdir(exist_ok=True)

    document = pymupdf.open(PDF)
    end = min(args.end or len(document), len(document))
    engine = RapidOCR()
    sheet_pages: list[tuple[int, Image.Image]] = []

    for page_number in range(args.start, end + 1):
        page = document[page_number - 1]
        image = render_page(page)
        result, _ = engine(np.asarray(image))
        lines = ordered_lines(result)
        payload = {
            "page": page_number,
            "width": image.width,
            "height": image.height,
            "lines": lines,
            "text": "\n".join(str(line["text"]) for line in lines),
        }
        (pages_dir / f"page-{page_number:03}.json").write_text(
            json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8"
        )

        preview = image.copy()
        preview.thumbnail((1130, 295))
        sheet_pages.append((page_number, preview))
        if len(sheet_pages) == 10 or page_number == end:
            first = sheet_pages[0][0]
            last = sheet_pages[-1][0]
            contact_sheet(sheet_pages).save(sheets_dir / f"pages-{first:03}-{last:03}.jpg", quality=88)
            sheet_pages.clear()

        print(f"page={page_number} lines={len(lines)}", flush=True)


if __name__ == "__main__":
    main()
