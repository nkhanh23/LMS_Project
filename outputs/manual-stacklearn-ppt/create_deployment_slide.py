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
F34 = font(34, True)
F50 = font(50, True)


def center(draw, box, text, fnt, fill=DARK):
    b = draw.textbbox((0, 0), text, font=fnt)
    tw, th = b[2] - b[0], b[3] - b[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def wrap(draw, text, fnt, max_width):
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


def paragraph(draw, x, y, text, fnt, max_width, fill=MID, lh=25):
    for line in wrap(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += lh
    return y


def box(draw, x, y, w, h, title, subtitle, fill):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=14, fill=(255, 255, 255), outline=LINE, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 48), radius=14, fill=fill, outline=LINE, width=2)
    draw.rectangle((x, y + 36, x + w, y + 49), fill=fill)
    center(draw, (x, y + 8, x + w, y + 34), title, F22, BLUE)
    center(draw, (x + 15, y + 58, x + w - 15, y + h - 18), subtitle, F18, MID)
    return (x, y, x + w, y + h)


def arrow(draw, start, end, color=BLUE):
    x1, y1 = start
    x2, y2 = end
    draw.line((x1, y1, x2, y2), fill=color, width=4)
    import math
    ang = math.atan2(y2 - y1, x2 - x1)
    size = 13
    p1 = (x2, y2)
    p2 = (x2 - size * math.cos(ang - 0.45), y2 - size * math.sin(ang - 0.45))
    p3 = (x2 - size * math.cos(ang + 0.45), y2 - size * math.sin(ang + 0.45))
    draw.polygon([p1, p2, p3], fill=color)


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

# Header
d.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
d.text((91, 82), "NTU", font=F20, fill=BLUE)
d.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
d.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

d.text((72, 150), "TRIỂN KHAI HỆ THỐNG", font=F50, fill=BLUE)
d.rounded_rectangle((72, 230, 720, 242), radius=3, fill=BLUE)
paragraph(
    d,
    72,
    270,
    "Mô hình triển khai đề xuất cho StackLearn sau khi hoàn thiện: Laravel chạy trên web server, PostgreSQL lưu dữ liệu, Queue xử lý tác vụ nền, R2/S3 lưu tài nguyên và Gemini API xử lý AI Tutor.",
    F22,
    1260,
    DARK,
    30,
)

# Main architecture frame
d.rounded_rectangle((80, 370, 1520, 790), radius=24, fill=(255, 255, 255), outline=LINE, width=2)

user = box(d, 125, 515, 220, 105, "Người dùng", "Student / Instructor / Admin", SOFT)
web = box(d, 430, 500, 250, 135, "Web Server", "Laravel + Nginx/Apache\nBlade + Vite assets", YELLOW)
db = box(d, 760, 395, 250, 110, "PostgreSQL", "Users, courses, orders,\nprogress, AI chat history", GREEN)
queue = box(d, 760, 640, 250, 110, "Queue Worker", "Transcript jobs\nAI document indexing", CYAN)
storage = box(d, 1100, 395, 260, 110, "R2 / S3 Storage", "Video, document,\ncourse image files", PURPLE)
gemini = box(d, 1100, 640, 260, 110, "Gemini API", "Sinh phản hồi\ncho AI Tutor", RED)

arrow(d, (345, 568), (430, 568))
arrow(d, (680, 540), (760, 450))
arrow(d, (680, 595), (760, 695))
arrow(d, (1010, 450), (1100, 450))
arrow(d, (1010, 695), (1100, 695))
arrow(d, (680, 568), (1100, 695))

# Small labels
d.text((450, 660), "HTTP request", font=F16, fill=MID)
d.text((695, 430), "DB query", font=F16, fill=MID)
d.text((690, 680), "background jobs", font=F16, fill=MID)
d.text((1025, 430), "file upload", font=F16, fill=MID)
d.text((1030, 675), "AI request", font=F16, fill=MID)

d.rounded_rectangle((330, 815, 1270, 860), radius=22, fill=(235, 243, 255), outline=BLUE, width=2)
center(d, (330, 815, 1270, 860), "Web Server → PostgreSQL / Queue / Storage / Gemini API", F22, BLUE)
d.text((72, 865), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_deployment_slide.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
