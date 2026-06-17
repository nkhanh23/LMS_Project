from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (235, 243, 255)
YELLOW = (255, 247, 224)
GREEN = (232, 250, 241)
PURPLE = (244, 239, 255)


def font(size, bold=False):
    candidates = []
    if bold:
        candidates += [
            r"C:\Windows\Fonts\arialbd.ttf",
            r"C:\Windows\Fonts\segoeuib.ttf",
        ]
    candidates += [
        r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeui.ttf",
        r"C:\Windows\Fonts\tahoma.ttf",
    ]
    for path in candidates:
        if Path(path).exists():
            return ImageFont.truetype(path, size)
    return ImageFont.load_default()


F18 = font(18)
F20 = font(20)
F22 = font(22)
F24 = font(24, True)
F26 = font(26, True)
F28 = font(28, True)
F34 = font(34, True)
F54 = font(54, True)


def center_text(draw, box, text, fnt, fill=DARK):
    bbox = draw.textbbox((0, 0), text, font=fnt)
    tw, th = bbox[2] - bbox[0], bbox[3] - bbox[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def wrap_text(draw, text, fnt, max_width):
    words = text.split()
    lines = []
    current = ""
    for word in words:
        trial = (current + " " + word).strip()
        if draw.textbbox((0, 0), trial, font=fnt)[2] <= max_width:
            current = trial
        else:
            if current:
                lines.append(current)
            current = word
    if current:
        lines.append(current)
    return lines


def paragraph(draw, xy, text, fnt, fill, max_width, line_h):
    x, y = xy
    for line in wrap_text(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += line_h
    return y


def layer_card(draw, x, y, num, title, desc, fill):
    draw.rounded_rectangle((x, y, x + 465, y + 76), radius=8, fill=fill, outline=LINE, width=2)
    draw.ellipse((x + 20, y + 18, x + 60, y + 58), fill=BLUE)
    center_text(draw, (x + 20, y + 18, x + 60, y + 58), num, F20, (255, 255, 255))
    draw.text((x + 78, y + 15), title, font=F24, fill=BLUE)
    draw.text((x + 78, y + 43), desc, font=F18, fill=MID)


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

# Header
d.ellipse((72, 68, 140, 136), outline=BLUE, width=3)
d.text((91, 91), "NTU", font=F20, fill=BLUE)
d.text((162, 90), "STACKLEARN", font=F28, fill=BLUE)
d.text((1240, 96), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

# Title
d.text((72, 190), "KIẾN TRÚC HỆ THỐNG", font=F54, fill=BLUE)
d.rounded_rectangle((72, 280, 600, 292), radius=3, fill=BLUE)
paragraph(
    d,
    (72, 325),
    "StackLearn được tổ chức theo kiến trúc nhiều lớp: người dùng tương tác qua giao diện web, Laravel xử lý nghiệp vụ, PostgreSQL lưu dữ liệu và AI Tutor kết nối Gemini API.",
    F24,
    DARK,
    560,
    33,
)

# Layer summary cards
layer_card(d, 72, 470, "01", "Giao diện Web", "Học viên, giảng viên, quản trị viên", SOFT)
layer_card(d, 72, 560, "02", "Laravel Application", "Xác thực, khóa học, thanh toán, quiz", YELLOW)
layer_card(d, 72, 650, "03", "PostgreSQL DB", "Lưu người dùng, khóa học, tiến độ, chat", GREEN)
layer_card(d, 72, 740, "04", "AI Tutor + Gemini API", "Trả lời theo ngữ cảnh bài học", PURPLE)

# Diagram placeholder
px1, py1, px2, py2 = 690, 170, 1515, 790
d.rounded_rectangle((px1, py1, px2, py2), radius=22, fill=(255, 255, 255), outline=LINE, width=3)
d.rounded_rectangle((px1 + 28, py1 + 28, px2 - 28, py2 - 28), radius=14, outline=(142, 165, 205), width=3)
for offset in range(0, int((px2 - px1) + (py2 - py1)), 28):
    x_start = px1 + 28 + offset
    y_start = py1 + 28
    x_end = px1 + 28
    y_end = py1 + 28 + offset
    if x_start > px2 - 28:
        y_start += x_start - (px2 - 28)
        x_start = px2 - 28
    if y_end > py2 - 28:
        x_end += y_end - (py2 - 28)
        y_end = py2 - 28
    if y_start <= py2 - 28 and x_end <= px2 - 28:
        d.line((x_start, y_start, x_end, y_end), fill=(232, 238, 248), width=1)

center_text(d, (px1 + 50, py1 + 230, px2 - 50, py1 + 285), "CHÈN ẢNH SƠ ĐỒ KIẾN TRÚC Ở ĐÂY", F28, BLUE)
center_text(d, (px1 + 50, py1 + 288, px2 - 50, py1 + 328), "Hình 3.1. Kiến trúc tổng thể hệ thống StackLearn", F22, MID)
center_text(d, (px1 + 50, py1 + 332, px2 - 50, py1 + 370), "Web UI → Laravel Application → PostgreSQL DB / AI Tutor → Gemini API", F20, MID)

# Footer
d.text((72, 835), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_architecture_placeholder_slide.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
