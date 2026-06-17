from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (18, 26, 43)
MID = (70, 82, 104)
LINE = (116, 131, 158)
LIGHT = (245, 248, 255)
EXT = (255, 250, 235)
GOLD = (218, 151, 42)


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


def centered(draw, box, text, fnt, fill=DARK):
    bbox = draw.textbbox((0, 0), text, font=fnt)
    tw, th = bbox[2] - bbox[0], bbox[3] - bbox[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2 - 1), text, font=fnt, fill=fill)


def actor(draw, cx, cy, label):
    draw.ellipse((cx - 16, cy - 56, cx + 16, cy - 24), outline=DARK, width=3)
    draw.line((cx, cy - 24, cx, cy + 28), fill=DARK, width=3)
    draw.line((cx - 36, cy - 2, cx + 36, cy - 2), fill=DARK, width=3)
    draw.line((cx, cy + 28, cx - 32, cy + 76), fill=DARK, width=3)
    draw.line((cx, cy + 28, cx + 32, cy + 76), fill=DARK, width=3)
    lines = label.split("\n")
    y = cy + 90
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=F16)
        draw.text((cx - (bbox[2] - bbox[0]) / 2, y), line, font=F16, fill=DARK)
        y += 20


def use_case(draw, cx, cy, text, w=245, h=39):
    box = (cx - w / 2, cy - h / 2, cx + w / 2, cy + h / 2)
    draw.ellipse(box, fill=(255, 255, 255), outline=DARK, width=2)
    centered(draw, box, text, F14, DARK)
    return box


def external_actor(draw, cx, cy, label):
    actor(draw, cx, cy, label)


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

# Header
d.ellipse((72, 58, 142, 128), outline=BLUE, width=3)
d.text((91, 82), "NTU", font=F20, fill=BLUE)
d.text((172, 82), "STACKLEARN", font=F26, fill=BLUE)
d.text((1215, 88), "Đồ án tốt nghiệp", font=F22, fill=BLUE)
d.text((420, 45), "SƠ ĐỒ USE CASE HỆ THỐNG", font=F48, fill=BLUE)

# Boundary
sys = (245, 135, 1240, 820)
d.rectangle(sys, outline=DARK, width=3, fill=(255, 255, 255))
centered(d, (245, 140, 1240, 175), "Hệ thống StackLearn", F22, DARK)

# Dividers
d.line((245, 368, 1240, 368), fill=DARK, width=2)
d.line((245, 590, 1240, 590), fill=DARK, width=2)

# Actors
actor(d, 90, 235, "Học viên\n(Student)")
actor(d, 90, 475, "Giảng viên\n(Instructor)")
actor(d, 90, 705, "Quản trị viên\n(Admin)")
external_actor(d, 1485, 235, "Cổng thanh toán\nStripe/VNPay")
external_actor(d, 1485, 475, "Dịch vụ AI\nGemini/OpenAI")
external_actor(d, 1485, 705, "Lưu trữ S3/R2")

# Use cases
student_cases = [
    ("Đăng ký / đăng nhập", 490, 195),
    ("Tìm kiếm & xem khóa học", 490, 235),
    ("Wishlist & giỏ hàng", 490, 275),
    ("Thanh toán & ghi danh", 490, 315),
    ("Học bài & làm quiz", 790, 195),
    ("Ghi chú / thảo luận", 790, 235),
    ("AI Tutor hỗ trợ học tập", 790, 275),
    ("Theo dõi tiến độ", 790, 315),
]
instructor_cases = [
    ("Quản lý khóa học", 500, 410),
    ("Quản lý bài giảng & tài liệu", 500, 455),
    ("Quản lý quiz", 500, 500),
    ("Tạo transcript bài giảng", 500, 545),
    ("Quản lý thảo luận", 830, 410),
    ("Theo dõi học viên & doanh thu", 830, 455),
    ("Yêu cầu rút tiền", 830, 500),
]
admin_cases = [
    ("Quản lý người dùng", 500, 635),
    ("Quản lý danh mục", 500, 680),
    ("Duyệt khóa học", 500, 725),
    ("Quản lý đơn hàng / hoàn tiền", 830, 635),
    ("Duyệt payout", 830, 680),
    ("Moderation & audit log", 830, 725),
    ("Learning analytics", 830, 770),
]

case_boxes = {}
for text, x, y in student_cases + instructor_cases + admin_cases:
    case_boxes[text] = use_case(d, x, y, text)

# Actor connections
def connect_actor_to_cases(actor_x, actor_y, cases):
    for case in cases:
        bx = case_boxes[case]
        d.line((actor_x + 48, actor_y, bx[0], (bx[1] + bx[3]) / 2), fill=LINE, width=2)


connect_actor_to_cases(90, 220, [x[0] for x in student_cases])
connect_actor_to_cases(90, 460, [x[0] for x in instructor_cases])
connect_actor_to_cases(90, 690, [x[0] for x in admin_cases])

# External system connections
def connect_case_to_external(case, ex_x, ex_y):
    bx = case_boxes[case]
    d.line((bx[2], (bx[1] + bx[3]) / 2, ex_x - 48, ex_y), fill=LINE, width=2)


connect_case_to_external("Thanh toán & ghi danh", 1485, 220)
connect_case_to_external("Yêu cầu rút tiền", 1485, 220)
connect_case_to_external("Quản lý đơn hàng / hoàn tiền", 1485, 220)
connect_case_to_external("AI Tutor hỗ trợ học tập", 1485, 460)
connect_case_to_external("Tạo transcript bài giảng", 1485, 460)
connect_case_to_external("Quản lý bài giảng & tài liệu", 1485, 690)

# Redraw use cases after associations so connector lines do not cross labels.
for text, x, y in student_cases + instructor_cases + admin_cases:
    use_case(d, x, y, text)

# Small labels for sections
d.text((270, 343), "Student use cases", font=F15, fill=MID)
d.text((270, 565), "Instructor use cases", font=F15, fill=MID)
d.text((270, 795), "Admin use cases", font=F15, fill=MID)

d.text((655, 858), "StackLearn | LMS tích hợp AI Tutor", font=F18, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_usecase_diagram_slide.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
