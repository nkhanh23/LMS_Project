from pathlib import Path

from pypdf import PdfReader

out_dir = Path("tools/khanh_turning_images")
out_dir.mkdir(exist_ok=True)

reader = PdfReader("khanh-turning.pdf")
for page_index, page in enumerate(reader.pages, start=1):
    for image_index, image in enumerate(page.images, start=1):
        out = out_dir / f"page-{page_index}-image-{image_index}.{image.name.split('.')[-1] if '.' in image.name else 'png'}"
        out.write_bytes(image.data)
        print(out.resolve(), len(image.data), image.name)
