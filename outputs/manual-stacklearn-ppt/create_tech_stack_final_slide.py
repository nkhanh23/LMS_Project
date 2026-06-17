from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (235, 243, 255)


def font(size, bold=False):
    candidates = []
    if bold:
        candidates += [r"C:\Windows\Fonts\arialbd.ttf", r"C:\Windows\Fonts\segoeuib.ttf"]
    candidates += [r"C:\Windows\Fonts\arial.ttf", r"C:\Windows\Fonts\segoeui.ttf", r"C:\Windows\Fonts\tahoma.ttf"]
    for path in candidates:
        if Path(path).exists():
            return ImageFont.truetype(path, size)
    return ImageFont.load_default()


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
    lines, cur = [], ""
    for word in text.split():
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


def paragraph(draw, x, y, text, fnt, max_width, fill=MID, lh=26):
    for line in wrap(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += lh
    return y


def bullet(draw, x, y, title, desc):
    draw.ellipse((x, y + 4, x + 28, y + 32), fill=BLUE)
    center(draw, (x, y + 4, x + 28, y + 32), "✓", F18, (255, 255, 255))
    draw.text((x + 44, y), title, font=F22, fill=BLUE)
    paragraph(draw, x + 44, y + 30, desc, F18, 420, MID, 24)


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

d.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
d.text((91, 82), "NTU", font=F20, fill=BLUE)
d.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
d.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

d.text((72, 155), "TECH STACK", font=F50, fill=BLUE)
d.rounded_rectangle((72, 235, 430, 247), radius=3, fill=BLUE)
paragraph(
    d,
    72,
    285,
    "Sơ đồ công nghệ tổng hợp các lớp chính của StackLearn: giao diện, Laravel backend, dữ liệu, AI Tutor và các dịch vụ tích hợp.",
    F22,
    520,
    DARK,
    30,
)

bullet(d, 82, 410, "Laravel làm lõi hệ thống", "Xử lý nghiệp vụ khóa học, học tập, thanh toán và AI Tutor.")
bullet(d, 82, 520, "PostgreSQL lưu dữ liệu", "Quản lý người dùng, khóa học, đơn hàng, tiến độ và lịch sử chat.")
bullet(d, 82, 630, "AI Tutor dùng Gemini API", "Kết hợp transcript, tài liệu học tập và lịch sử hội thoại.")

px1, py1, px2, py2 = 600, 155, 1518, 805
d.rounded_rectangle((px1, py1, px2, py2), radius=22, fill=(255, 255, 255), outline=LINE, width=3)
d.rounded_rectangle((px1 + 26, py1 + 26, px2 - 26, py2 - 26), radius=14, outline=(142, 165, 205), width=3)
for off in range(-560, 950, 26):
    d.line((px1 + 26 + off, py2 - 26, px1 + 26 + off + 620, py1 + 26), fill=(230, 237, 248), width=1)
center(d, (px1 + 40, py1 + 285, px2 - 40, py1 + 330), "CHÈN SƠ ĐỒ TECH STACK", F28, BLUE)
center(d, (px1 + 40, py1 + 340, px2 - 40, py1 + 372), "Dùng ảnh sơ đồ bạn đã tạo, crop gọn phần viền trắng nếu cần", F18, MID)

d.rounded_rectangle((720, 825, 1390, 866), radius=20, fill=SOFT, outline=BLUE, width=2)
center(d, (720, 825, 1390, 866), "Frontend → Backend Laravel → Database / AI / External Services", F20, BLUE)
d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_tech_stack_final_placeholder_slide.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
