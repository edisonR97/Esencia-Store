from pathlib import Path
import pymupdf

root = Path(__file__).resolve().parents[1]
pdf = root / "storage/app/catalog/catalogo-hgw-agosto-2026.pdf"
out = root / "tmp/pdfs/product-pages"
out.mkdir(parents=True, exist_ok=True)
doc = pymupdf.open(pdf)
for number in (28, 29, 32, 33):
    page = doc[number - 1]
    pix = page.get_pixmap(matrix=pymupdf.Matrix(2.5, 2.5), alpha=False)
    pix.save(out / f"page-{number:03}.png")
    print(number, pix.width, pix.height)
