from pathlib import Path
from PIL import Image, ImageEnhance

root = Path(__file__).resolve().parents[1]
source = root / "tmp/pdfs/product-pages"
target = root / "storage/app/public/products"
target.mkdir(parents=True, exist_ok=True)

crops = {
    "1148-pasta-dental.webp": (28, (1570, 1000, 1960, 3000)),
    "1197-pasta-dental-probiotica.webp": (29, (190, 410, 1240, 2100)),
    "1179-gel-bano-turmalina.webp": (32, (1370, 1540, 1920, 3120)),
    "1177-shampoo-keratina.webp": (32, (3370, 1540, 3740, 3120)),
    "1200-deodorant-woman.webp": (33, (1160, 1900, 1570, 3080)),
    "1199-deodorant-man.webp": (33, (3030, 1900, 3400, 3080)),
}

for filename, (page, box) in crops.items():
    image = Image.open(source / f"page-{page:03}.png").convert("RGB").crop(box)
    image = ImageEnhance.Contrast(image).enhance(1.03)
    image.thumbnail((900, 1100), Image.Resampling.LANCZOS)
    canvas = Image.new("RGB", (1000, 1200), "#f1f2ec")
    canvas.paste(image, ((1000 - image.width) // 2, (1200 - image.height) // 2))
    canvas.save(target / filename, "WEBP", quality=91, method=6)
    print(filename)
