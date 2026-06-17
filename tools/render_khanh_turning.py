from pathlib import Path

from pdf2image import convert_from_path

out_dir = Path("tools/khanh_turning_pages")
out_dir.mkdir(exist_ok=True)
pages = convert_from_path("khanh-turning.pdf", dpi=180)
for index, image in enumerate(pages, start=1):
    path = out_dir / f"page-{index}.png"
    image.save(path)
    print(path.resolve())
