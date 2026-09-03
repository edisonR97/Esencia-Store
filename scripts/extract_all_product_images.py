from pathlib import Path
import json
import pymupdf
from PIL import Image, ImageEnhance

root=Path(__file__).resolve().parents[1]
pdf=root/'storage/app/catalog/catalogo-hgw-agosto-2026.pdf'
items=json.loads((root/'storage/app/catalog/products-for-images.json').read_text(encoding='utf-8'))
target=root/'storage/app/public/products'; target.mkdir(parents=True,exist_ok=True)

# Product-focused regions, visually reviewed by catalog page. Coordinates are normalized.
boxes={
12:(.10,.18,.56,.86),13:(.12,.16,.55,.88),14:(.12,.18,.60,.88),15:(.18,.16,.66,.88),16:(.08,.22,.55,.88),17:(.08,.28,.60,.88),18:(.12,.25,.58,.88),19:(.06,.15,.65,.87),20:(.07,.16,.63,.88),21:(.08,.24,.58,.90),22:(.08,.18,.60,.88),23:(.05,.34,.68,.90),24:(.06,.13,.52,.90),25:(.08,.18,.58,.88),26:(.06,.20,.62,.89),
28:(.34,.27,.45,.89),29:(.04,.11,.34,.66),30:(.08,.18,.55,.88),31:(.02,.18,.98,.91),32:(.27,.40,.87,.94),33:(.20,.47,.80,.94),34:(.62,.20,.88,.88),35:(.36,.24,.72,.89),36:(.34,.15,.65,.88),38:(.03,.08,.65,.91),39:(.05,.42,.62,.91),40:(.40,.20,.72,.88),41:(.42,.26,.74,.87),42:(.43,.28,.74,.87),43:(.40,.28,.70,.88),44:(.40,.23,.72,.89),45:(.42,.25,.74,.88),46:(.03,.18,.97,.89),48:(.04,.28,.96,.89),49:(.03,.15,.58,.91),50:(.03,.12,.63,.91),51:(.03,.12,.62,.91),52:(.03,.20,.57,.91),53:(.03,.23,.57,.90),54:(.03,.20,.57,.90),55:(.02,.18,.61,.88),56:(.03,.17,.60,.91),58:(.03,.19,.58,.91),59:(.03,.18,.58,.91),60:(.03,.18,.58,.91),61:(.03,.15,.63,.90),62:(.03,.16,.62,.91),63:(.03,.20,.60,.91),64:(.02,.17,.55,.90),65:(.02,.10,.55,.92),66:(.30,.20,.76,.90),67:(.20,.18,.80,.90)}

doc=pymupdf.open(pdf); cache={}
for item in items:
 page=int(item['source_page']); box=boxes.get(page)
 if not box: continue
 if page not in cache:
  pix=doc[page-1].get_pixmap(matrix=pymupdf.Matrix(2.2,2.2),alpha=False)
  cache[page]=Image.frombytes('RGB',(pix.width,pix.height),pix.samples)
 image=cache[page]; w,h=image.size
 crop=image.crop((int(box[0]*w),int(box[1]*h),int(box[2]*w),int(box[3]*h)))
 crop=ImageEnhance.Contrast(crop).enhance(1.02); crop.thumbnail((920,1100),Image.Resampling.LANCZOS)
 canvas=Image.new('RGB',(1000,1200),'#f1f2ec'); canvas.paste(crop,((1000-crop.width)//2,(1200-crop.height)//2))
 filename=f"{item['slug']}.webp"; canvas.save(target/filename,'WEBP',quality=88,method=6)
 item['generated_image']='products/'+filename
(root/'storage/app/catalog/product-images.json').write_text(json.dumps(items,ensure_ascii=False,indent=2),encoding='utf-8')
print(f"generated={sum('generated_image' in x for x in items)} total={len(items)}")
