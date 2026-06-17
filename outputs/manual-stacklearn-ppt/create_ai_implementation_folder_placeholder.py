from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (235, 243, 255)
GREEN = (232, 250, 241)
PURPLE = (244, 239, 255)


def font(size, bold=False):
    candidates = []
    if bold:
        candidates += [r"C:\Windows\Fonts\arialbd.ttf", r"C:\Windows\Fonts\segoeuib.ttf"]
    candidates += [r"C:\Windows\Fonts\arial.ttf", r"C:\Windows\Fonts\segoeui.ttf", r"C:\Windows\Fonts\tahoma.ttf"]
    for path in candidates:
        if Path(path).exists():
            return ImageFont.truetype(path, size)
    return ImageFont.load_default()


F16 = font(16)
F18 = font(18)
F20 = font(20)
F22 = font(22, True)
F24 = font(24, True)
F28 = font(28, True)
F50 = font(50, True)


def center(draw, box, text, fnt, fill=DARK):
    b = draw.textbbox((0, 0), text, font=fnt)
    tw, th = b[2] - b[0], b[3] - b[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def wrap(draw, text, fnt, max_width):
    words = text.split()
    lines = []
    cur = ""
    for word in words:
        trial = (cur + " " + word).strip()
        if draw.textbbox((0, 0), trial, font=fnt)[2] <= max_width:
            cur = trial
        else:
            if cur:
                lines.append(cur)
            cur = word
    if cur:
        lines.append(cur)
    return lines


def paragraph(draw, x, y, text, fnt, max_width, fill=MID, lh=25):
    for line in wrap(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += lh
    return y


def placeholder(draw, x, y, w, h, title, path_text, header_fill):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=14, fill=(255, 255, 255), outline=LINE, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 64), radius=14, fill=header_fill, outline=LINE, width=2)
    draw.rectangle((x, y + 48, x + w, y + 65), fill=header_fill)
    center(draw, (x, y + 8, x + w, y + 36), title, F22, BLUE)
    center(draw, (x + 16, y + 35, x + w - 16, y + 60), path_text, F16, MID)
    inner = (x + 26, y + 88, x + w - 26, y + h - 32)
    draw.rounded_rectangle(inner, radius=10, fill=(252, 254, 255), outline=(148, 171, 210), width=2)
    # diagonal placeholder texture
    ix1, iy1, ix2, iy2 = inner
    step = 24
    for offset in range(-int(iy2 - iy1), int(ix2 - ix1), step):
        draw.line((ix1 + offset, iy2, ix1 + offset + (iy2 - iy1), iy1), fill=(230, 237, 248), width=1)
    center(draw, inner, "CHÈN ẢNH CHỤP FOLDER CODE", F20, BLUE)


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

d.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
d.text((91, 82), "NTU", font=F20, fill=BLUE)
d.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
d.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

d.text((72, 155), "TRIỂN KHAI AI TUTOR", font=F50, fill=BLUE)
d.rounded_rectangle((72, 235, 690, 247), radius=3, fill=BLUE)
paragraph(
    d,
    72,
    275,
    "Ba nhóm mã nguồn chính thể hiện luồng triển khai: giao diện widget, backend tiếp nhận yêu cầu và service xử lý ngữ cảnh để gọi Gemini API.",
    F22,
    1240,
    DARK,
    30,
)

placeholder(
    d,
    75,
    375,
    450,
    365,
    "Chatbot Widget",
    "resources/views + public/customjs",
    SOFT,
)
placeholder(
    d,
    575,
    375,
    450,
    365,
    "Backend Chatbot",
    "routes + Http Controller / Request",
    PURPLE,
)
placeholder(
    d,
    1075,
    375,
    450,
    365,
    "AI Services",
    "app/Services",
    GREEN,
)

for x in [535, 1035]:
    d.line((x, 560, x + 30, 560), fill=BLUE, width=4)
    d.polygon([(x + 30, 560), (x + 18, 550), (x + 18, 570)], fill=BLUE)

d.rounded_rectangle((340, 790, 1260, 842), radius=24, fill=(235, 243, 255), outline=BLUE, width=2)
center(d, (340, 790, 1260, 842), "Widget → Controller → Context Builder / Retrieval → Gemini API", F22, BLUE)
d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_ai_tutor_folder_placeholder_slide.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
