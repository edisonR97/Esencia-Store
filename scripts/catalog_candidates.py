from __future__ import annotations

import json
import re
from pathlib import Path

root = Path(__file__).resolve().parents[1]
pages = root / "storage" / "app" / "catalog" / "extraction" / "pages"
output = root / "storage" / "app" / "catalog" / "product-candidates.txt"

blocks: list[str] = []
summaries: list[str] = []
for path in sorted(pages.glob("page-*.json")):
    payload = json.loads(path.read_text(encoding="utf-8"))
    text = payload["text"]
    codes = sorted(set(re.findall(r"(?:CODIGO|CÓDIGO)\s*[:.]?\s*([0-9]{3,4}(?:-[0-9/]+)?)", text, re.I)))
    prices = sorted(set(re.findall(r"\$\s*([0-9]{2,3}(?:[.,][0-9]{3})+(?:[.,]00)?)", text)))
    if codes or prices:
        useful = [line.strip() for line in text.splitlines() if line.strip()]
        blocks.append(
            f"\n===== PAGE {payload['page']} | CODES: {', '.join(codes) or '-'} | PRICES: {', '.join(prices) or '-'} =====\n"
            + "\n".join(useful)
        )
        summaries.append(f"PAGE {payload['page']}: codes={','.join(codes) or '-'} prices={','.join(prices) or '-'}")

output.write_text("\n".join(blocks), encoding="utf-8")
print(f"pages_with_commercial_data={len(blocks)}")
print("\n".join(summaries))
print(output)
