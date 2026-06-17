from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (18, 26, 43)
MID = (72, 84, 106)
LINE = (92, 108, 136)
LIGHT = (255, 255, 255)
FILL = (246, 249, 255)
EXT_FILL = (255, 250, 236)
GOLD = (221, 158, 44)
PURPLE = (105, 84, 205)
GREEN = (20, 145, 90)


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


F14 = font(14)
F15 = font(15)
F16 = font(16)
F18 = font(18)
F20 = font(20)
F22 = font(22, True)
F26 = font(26, True)
F48 = font(48, True)


def text_center(draw, box, text, fnt, fill=DARK):
    bbox = draw.textbbox((0, 0), text, font=fnt)
    tw = bbox[2] - bbox[0]
    th = bbox[3] - bbox[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2 - 1), text, font=fnt, fill=fill)


def text_multiline_center(draw, cx, y, text, fnt, fill=DARK, line_h=20):
    lines = text.split("\n")
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=fnt)
        draw.text((cx - (bbox[2] - bbox[0]) / 2, y), line, font=fnt, fill=fill)
        y += line_h


def actor(draw, cx, cy, label, color=DARK):
    draw.ellipse((cx - 15, cy - 54, cx + 15, cy - 24), outline=color, width=3)
    draw.line((cx, cy - 24, cx, cy + 28), fill=color, width=3)
    draw.line((cx - 35, cy - 2, cx + 35, cy - 2), fill=color, width=3)
    draw.line((cx, cy + 28, cx - 31, cy + 74), fill=color, width=3)
    draw.line((cx, cy + 28, cx + 31, cy + 74), fill=color, width=3)
    text_multiline_center(draw, cx, cy + 88, label, F16, DARK)


def use_case(draw, cx, cy, text, w=240, h=42):
    box = (cx - w / 2, cy - h / 2, cx + w / 2, cy + h / 2)
    draw.ellipse(box, fill=LIGHT, outline=DARK, width=2)
    text_center(draw, box, text, F14, DARK)
    return box


def external_box(draw, x, y, title, subtitle, color):
    box = (x, y, x + 210, y + 68)
    draw.rounded_rectangle(box, radius=10, fill=EXT_FILL, outline=color, width=2)
    text_center(draw, (x + 10, y + 8, x + 200, y + 34), title, F16, DARK)
    text_center(draw, (x + 10, y + 34, x + 200, y + 60), subtitle, F15, MID)
    return box


def connect(draw, p1, p2, width=2):
    draw.line((p1[0], p1[1], p2[0], p2[1]), fill=LINE, width=width)


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

# Header
d.ellipse((72, 58, 142, 128), outline=BLUE, width=3)
d.text((91, 82), "NTU", font=F20, fill=BLUE)
d.text((172, 82), "STACKLEARN", font=F26, fill=BLUE)
d.text((1215, 88), "Đồ án tốt nghiệp", font=F22, fill=BLUE)
d.text((420, 45), "SƠ ĐỒ USE CASE HỆ THỐNG", font=F48, fill=BLUE)

# System boundary
sys = (230, 132, 1248, 824)
d.rectangle(sys, outline=BLUE, width=3, fill=(255, 255, 255))
text_center(d, (230, 140, 1248, 174), "Hệ thống StackLearn", F22, BLUE)
d.line((230, 366, 1248, 366), fill=(210, 218, 232), width=2)
d.line((230, 592, 1248, 592), fill=(210, 218, 232), width=2)

# Actors
actor(d, 100, 250, "Học viên\n(Student)")
actor(d, 100, 480, "Giảng viên\n(Instructor)")
actor(d, 100, 710, "Quản trị viên\n(Admin)")

stripe_box = external_box(d, 1330, 218, "Stripe/VNPay", "Thanh toán", GOLD)
ai_box = external_box(d, 1330, 454, "Gemini/OpenAI", "Dịch vụ AI", PURPLE)
s3_box = external_box(d, 1330, 680, "S3/R2", "Lưu trữ file", GREEN)

# Use case coordinates are arranged so actor lines do not pass through other ovals.
student = [
    ("Đăng ký / đăng nhập", 430, 205),
    ("Tìm kiếm & xem khóa học", 430, 255),
    ("Wishlist & giỏ hàng", 430, 305),
    ("Học bài & làm quiz", 710, 205),
    ("Ghi chú / thảo luận", 710, 255),
    ("Theo dõi tiến độ", 710, 305),
    ("Thanh toán & ghi danh", 1000, 225),
    ("AI Tutor hỗ trợ học tập", 1000, 285),
]
instructor = [
    ("Quản lý khóa học", 430, 418),
    ("Quản lý bài giảng & tài liệu", 430, 468),
    ("Quản lý quiz", 430, 518),
    ("Quản lý thảo luận", 710, 418),
    ("Theo dõi học viên & doanh thu", 710, 468),
    ("Yêu cầu rút tiền", 710, 518),
    ("Tạo transcript bài giảng", 1000, 468),
]
admin = [
    ("Quản lý người dùng", 430, 642),
    ("Quản lý danh mục", 430, 692),
    ("Duyệt khóa học", 430, 742),
    ("Learning analytics", 430, 792),
    ("Quản lý đơn hàng / hoàn tiền", 760, 660),
    ("Duyệt payout", 760, 720),
    ("Moderation & audit log", 760, 780),
]

all_cases = student + instructor + admin

# Draw actor associations first.
for _, x, y in student:
    connect(d, (138, 235), (x - 120, y))
for _, x, y in instructor:
    connect(d, (138, 465), (x - 120, y))
for _, x, y in admin:
    connect(d, (138, 695), (x - 120, y))

# External associations, routed horizontally from right-side use cases.
connect(d, (1120, 225), (1330, 252))
connect(d, (1120, 285), (1330, 488))
connect(d, (1120, 468), (1330, 488))
connect(d, (550, 468), (1330, 714))
connect(d, (830, 518), (1330, 252))
connect(d, (880, 660), (1330, 252))
connect(d, (880, 720), (1330, 252))

# Redraw external boxes after lines.
external_box(d, 1330, 218, "Stripe/VNPay", "Thanh toán", GOLD)
external_box(d, 1330, 454, "Gemini/OpenAI", "Dịch vụ AI", PURPLE)
external_box(d, 1330, 680, "S3/R2", "Lưu trữ file", GREEN)

# Draw use cases last so no association line crosses labels.
for text, x, y in all_cases:
    use_case(d, x, y, text)

# Section labels
d.text((252, 338), "Student use cases", font=F15, fill=MID)
d.text((252, 564), "Instructor use cases", font=F15, fill=MID)
d.text((252, 796), "Admin use cases", font=F15, fill=MID)

d.text((652, 858), "StackLearn | LMS tích hợp AI Tutor", font=F18, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_usecase_diagram_clean.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
