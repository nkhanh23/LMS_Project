from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
BG = (248, 250, 255)
SOFT = (235, 243, 255)
GREEN = (232, 250, 241)
YELLOW = (255, 248, 225)
PURPLE = (244, 239, 255)
RED = (255, 239, 239)
CYAN = (230, 248, 252)


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
F32 = font(32, True)
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


def box(draw, x, y, w, h, title, subtitle, fill):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=14, fill=(255, 255, 255), outline=LINE, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 48), radius=14, fill=fill, outline=LINE, width=2)
    draw.rectangle((x, y + 36, x + w, y + 49), fill=fill)
    center(draw, (x, y + 8, x + w, y + 34), title, F22, BLUE)
    center(draw, (x + 12, y + 58, x + w - 12, y + h - 18), subtitle, F18, MID)
    return (x, y, x + w, y + h)


def arrow(draw, start, end, color=BLUE):
    import math

    x1, y1 = start
    x2, y2 = end
    draw.line((x1, y1, x2, y2), fill=color, width=4)
    ang = math.atan2(y2 - y1, x2 - x1)
    size = 13
    p1 = (x2, y2)
    p2 = (x2 - size * math.cos(ang - 0.45), y2 - size * math.sin(ang - 0.45))
    p3 = (x2 - size * math.cos(ang + 0.45), y2 - size * math.sin(ang + 0.45))
    draw.polygon([p1, p2, p3], fill=color)


def create_diagram():
    W, H = 1100, 650
    im = Image.new("RGB", (W, H), (255, 255, 255))
    d = ImageDraw.Draw(im)
    d.rounded_rectangle((20, 20, W - 20, H - 20), radius=24, fill=(255, 255, 255), outline=LINE, width=2)
    center(d, (40, 45, W - 40, 85), "Mô hình triển khai StackLearn", F28, BLUE)

    user = box(d, 65, 280, 210, 105, "Người dùng", "Student / Instructor / Admin", SOFT)
    web = box(d, 365, 260, 250, 145, "Web Server", "Laravel + Nginx/Apache\nBlade + Vite assets", YELLOW)
    db = box(d, 710, 130, 250, 110, "PostgreSQL", "Users, courses, orders,\nprogress, AI chat history", GREEN)
    storage = box(d, 710, 270, 250, 110, "R2 / S3 Storage", "Video, document,\ncourse image files", PURPLE)
    queue = box(d, 710, 410, 250, 110, "Queue Worker", "Transcript jobs\nAI document indexing", CYAN)
    gemini = box(d, 365, 475, 250, 110, "Gemini API", "Sinh phản hồi\ncho AI Tutor", RED)

    arrow(d, (275, 333), (365, 333))
    arrow(d, (615, 285), (710, 185))
    arrow(d, (615, 333), (710, 325))
    arrow(d, (615, 380), (710, 465))
    arrow(d, (490, 405), (490, 475))
    arrow(d, (710, 465), (615, 530))

    d.text((300, 312), "HTTP", font=F16, fill=MID)
    d.text((625, 205), "DB query", font=F16, fill=MID)
    d.text((626, 310), "file", font=F16, fill=MID)
    d.text((620, 438), "job", font=F16, fill=MID)
    d.text((505, 430), "AI request", font=F16, fill=MID)
    return im


def create_slide_placeholder():
    W, H = 1600, 900
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)

    d.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
    d.text((91, 82), "NTU", font=F20, fill=BLUE)
    d.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
    d.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

    d.text((72, 155), "TRIỂN KHAI HỆ THỐNG", font=F50, fill=BLUE)
    d.rounded_rectangle((72, 235, 720, 247), radius=3, fill=BLUE)

    paragraph(
        d,
        72,
        285,
        "Mô hình triển khai đề xuất cho StackLearn sau khi hoàn thiện, bám theo kiến trúc trong tài liệu và code dự án.",
        F22,
        560,
        DARK,
        30,
    )

    items = [
        ("Web Server", "Laravel Application, Blade, Vite assets"),
        ("PostgreSQL", "Lưu người dùng, khóa học, đơn hàng, tiến độ, lịch sử AI"),
        ("Queue Worker", "Xử lý transcript và lập chỉ mục tài liệu AI"),
        ("R2 / S3 Storage", "Lưu video, tài liệu bài giảng, hình ảnh khóa học"),
        ("Gemini API", "Sinh phản hồi cho AI Tutor theo ngữ cảnh"),
    ]
    y = 395
    for title, desc in items:
        d.ellipse((82, y + 4, 110, y + 32), fill=BLUE)
        center(d, (82, y + 4, 110, y + 32), "✓", F18, (255, 255, 255))
        d.text((125, y), title, font=F22, fill=BLUE)
        paragraph(d, 125, y + 28, desc, F18, 490, MID, 24)
        y += 82

    # placeholder
    px1, py1, px2, py2 = 690, 180, 1515, 790
    d.rounded_rectangle((px1, py1, px2, py2), radius=22, fill=(255, 255, 255), outline=LINE, width=3)
    d.rounded_rectangle((px1 + 28, py1 + 28, px2 - 28, py2 - 28), radius=14, outline=(142, 165, 205), width=3)
    for off in range(-520, 840, 26):
        d.line((px1 + 28 + off, py2 - 28, px1 + 28 + off + 560, py1 + 28), fill=(230, 237, 248), width=1)
    center(d, (px1 + 40, py1 + 250, px2 - 40, py1 + 295), "CHÈN ẢNH SƠ ĐỒ DEPLOYMENT", F28, BLUE)
    center(d, (px1 + 40, py1 + 305, px2 - 40, py1 + 340), "Web Server → PostgreSQL / Queue / Storage / Gemini API", F20, MID)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    return im


out_dir = Path("outputs/manual-stacklearn-ppt")
out_dir.mkdir(parents=True, exist_ok=True)
diagram_path = out_dir / "stacklearn_deployment_diagram_only.png"
slide_path = out_dir / "stacklearn_deployment_placeholder_slide.png"
create_diagram().save(diagram_path)
create_slide_placeholder().save(slide_path)
print(diagram_path.resolve())
print(slide_path.resolve())
