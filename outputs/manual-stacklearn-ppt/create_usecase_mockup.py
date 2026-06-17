from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (247, 250, 255)
BLUE = (34, 82, 214)
BLUE2 = (22, 68, 185)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (232, 240, 255)
GREEN = (18, 150, 92)
ORANGE = (229, 132, 26)
PURPLE = (105, 84, 205)


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
F24 = font(24)
F26 = font(26, True)
F28 = font(28, True)
F54 = font(54, True)

out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)
margin = 72

# Header
d.ellipse((margin, 72, margin + 68, 140), outline=BLUE, width=3)
d.text((margin + 18, 95), "NTU", font=F20, fill=BLUE)
d.text((margin + 92, 92), "STACKLEARN", font=F28, fill=BLUE)
d.text((1240, 96), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

# Title
d.text((margin, 188), "SƠ ĐỒ USE CASE HỆ THỐNG", font=F54, fill=BLUE)
d.rounded_rectangle((margin, 278, 690, 290), radius=3, fill=BLUE)
d.text(
    (margin, 320),
    "Use case được rút gọn từ tài liệu thiết kế để dễ trình bày trên slide.",
    font=F26,
    fill=DARK,
)

# System boundary
box = (210, 390, 1285, 778)
d.rounded_rectangle(box, radius=24, outline=LINE, width=2, fill=(255, 255, 255))
d.text((650, 412), "Hệ thống StackLearn", font=F28, fill=BLUE)

sections = [
    (
        "Học viên",
        (245, 468, 610, 730),
        BLUE,
        [
            "Đăng ký / đăng nhập",
            "Tìm kiếm & xem khóa học",
            "Wishlist & giỏ hàng",
            "Thanh toán & ghi danh",
            "Học bài, quiz, tiến độ",
            "Ghi chú, thảo luận, AI Tutor",
        ],
    ),
    (
        "Giảng viên",
        (635, 468, 930, 730),
        GREEN,
        [
            "Quản lý khóa học",
            "Quản lý bài giảng & tài liệu",
            "Quản lý quiz",
            "Tạo transcript bài giảng",
            "Quản lý thảo luận",
            "Theo dõi học viên & doanh thu",
        ],
    ),
    (
        "Quản trị viên",
        (955, 468, 1250, 730),
        ORANGE,
        [
            "Quản lý người dùng",
            "Quản lý danh mục",
            "Duyệt khóa học",
            "Quản lý đơn hàng / hoàn tiền",
            "Duyệt payout",
            "Moderation & analytics",
        ],
    ),
]

for title, rect, color, items in sections:
    x1, y1, x2, y2 = rect
    d.rounded_rectangle(rect, radius=18, outline=(210, 220, 236), width=2, fill=(250, 252, 255))
    d.text((x1 + 24, y1 + 20), title, font=F26, fill=color)
    y = y1 + 66
    for item in items:
        d.rounded_rectangle((x1 + 22, y, x2 - 22, y + 34), radius=17, fill=SOFT, outline=(206, 219, 242), width=1)
        d.text((x1 + 38, y + 7), item, font=F18, fill=DARK)
        y += 41


def actor(cx, cy, color, label):
    d.ellipse((cx - 20, cy - 62, cx + 20, cy - 22), outline=color, width=3)
    d.line((cx, cy - 22, cx, cy + 30), fill=color, width=3)
    d.line((cx - 38, cy - 2, cx + 38, cy - 2), fill=color, width=3)
    d.line((cx, cy + 30, cx - 34, cy + 78), fill=color, width=3)
    d.line((cx, cy + 30, cx + 34, cy + 78), fill=color, width=3)
    bbox = d.textbbox((0, 0), label, font=F20)
    d.text((cx - (bbox[2] - bbox[0]) / 2, cy + 90), label, font=F20, fill=DARK)


actor(115, 520, BLUE, "Student")
actor(115, 638, GREEN, "Instructor")
actor(115, 756, ORANGE, "Admin")

for y, target in [(520, 535), (638, 535), (756, 535)]:
    d.line((155, y, 210, target), fill=(154, 170, 196), width=2)

external_systems = [
    ("Stripe/VNPay", "Thanh toán", 1398, 485, BLUE),
    ("Gemini/OpenAI", "Dịch vụ AI", 1398, 610, PURPLE),
    ("S3/R2", "Lưu trữ file", 1398, 735, GREEN),
]

for name, sub, cx, cy, color in external_systems:
    d.rounded_rectangle((1320, cy - 42, 1500, cy + 42), radius=16, fill=(255, 255, 255), outline=LINE, width=2)
    d.ellipse((1340, cy - 18, 1376, cy + 18), fill=SOFT, outline=color, width=2)
    d.text((1390, cy - 22), name, font=F20, fill=color)
    d.text((1390, cy + 4), sub, font=F18, fill=MID)

for cy, ty in [(485, 625), (610, 688), (735, 544)]:
    d.line((1320, cy, 1285, ty), fill=(154, 170, 196), width=2)

d.rounded_rectangle((310, 815, 1290, 865), radius=24, outline=BLUE, width=2, fill=(236, 244, 255))
d.text(
    (440, 828),
    "Sơ đồ báo cáo nên dùng bản rút gọn; bản chi tiết đầy đủ đặt trong tài liệu.",
    font=F24,
    fill=BLUE,
)
d.text((650, 875), "StackLearn | LMS tích hợp AI Tutor", font=F18, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_usecase_slide_mockup.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
