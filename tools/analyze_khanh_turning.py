from __future__ import annotations

import json
import re
import sys
from pathlib import Path

from pypdf import PdfReader

if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8")

pdf_path = Path("khanh-turning.pdf")
reader = PdfReader(str(pdf_path))
pages = []
for i, page in enumerate(reader.pages, start=1):
    text = page.extract_text() or ""
    text = re.sub(r"[ \t]+", " ", text)
    text = re.sub(r"\n{3,}", "\n\n", text).strip()
    pages.append({"page": i, "chars": len(text), "text": text})

full_text = "\n\n".join(p["text"] for p in pages)

patterns = [
    r"Similarity\s*(?:Index|Report)?\s*[:\n ]+(\d+%)",
    r"(\d+%)\s*Similarity",
    r"Internet Sources\s*[:\n ]+(\d+%)",
    r"Publications\s*[:\n ]+(\d+%)",
    r"Student Papers\s*[:\n ]+(\d+%)",
    r"AI\s*(?:writing|detection|generated|score)?\s*[:\n ]+(\d+%)",
]
matches = []
for pattern in patterns:
    for m in re.finditer(pattern, full_text, re.I):
        matches.append({"pattern": pattern, "match": m.group(0), "value": m.group(1) if m.groups() else ""})

sources = []
for line in full_text.splitlines():
    clean = line.strip()
    if re.search(r"\b\d+%\b", clean) or re.search(r"https?://|www\.|\.com|\.vn|\.edu", clean, re.I):
        sources.append(clean)

result = {
    "file": str(pdf_path.resolve()),
    "pages": len(reader.pages),
    "metadata": dict(reader.metadata or {}),
    "matches": matches[:100],
    "source_like_lines": sources[:300],
    "page_previews": [{"page": p["page"], "chars": p["chars"], "preview": p["text"][:1800]} for p in pages],
}

Path("tools/khanh_turning_analysis.json").write_text(json.dumps(result, ensure_ascii=False, indent=2), encoding="utf-8")

print(f"pages={result['pages']}")
print("metadata=", result["metadata"])
print("\nMATCHES")
for m in result["matches"][:50]:
    print("-", m["match"])
print("\nSOURCE-LIKE LINES")
for s in result["source_like_lines"][:80]:
    print("-", s)
print("\nPAGE PREVIEWS")
for p in result["page_previews"]:
    print(f"\n--- PAGE {p['page']} chars={p['chars']} ---")
    print(p["preview"])
