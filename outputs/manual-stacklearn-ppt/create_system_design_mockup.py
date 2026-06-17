from PIL import Image, ImageDraw, ImageFont
from pathlib import Path


OUT = Path("outputs/manual-stacklearn-ppt/stacklearn_system_design_slide_mockup.png")
W, H = 1600, 900


def font(size, bold=False):
    candidates = [
        r"C:\Windows\Fonts\arialbd.ttf" if bold else r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeuib.ttf" if bold else r"C:\Windows\Fonts\segoeui.ttf",
    ]
    for path in candidates:
        if Path(path).exists():
            return ImageFont.truetype(path, size)
    return ImageFont.load_default()


img = Image.new("RGB", (W, H), "#F7FAFF")
draw = ImageDraw.Draw(img)

blue = "#1D4ED8"
deep = "#0F172A"
muted = "#475569"
line = "#CBD5E1"
soft_blue = "#DBEAFE"
soft_green = "#DCFCE7"
white = "#FFFFFF"


def rounded_box(x1, y1, x2, y2, fill, outline=line, radius=22, width=2):
    draw.rounded_rectangle((x1, y1, x2, y2), radius=radius, fill=fill, outline=outline, width=width)


def pill(x1, y1, x2, y2, text, fill, color=blue):
    rounded_box(x1, y1, x2, y2, fill, outline=line, radius=18, width=2)
    draw.text(((x1 + x2) / 2, (y1 + y2) / 2), text, font=font(24, True), fill=color, anchor="mm")


def draw_icon_lms(cx, cy):
    draw.rounded_rectangle((cx - 45, cy - 32, cx + 45, cy + 28), radius=8, fill=soft_blue, outline=blue, width=3)
    draw.rectangle((cx - 32, cy - 16, cx + 32, cy - 6), fill=white)
    draw.rectangle((cx - 32, cy + 4, cx + 18, cy + 12), fill=white)
    draw.rectangle((cx - 18, cy + 30, cx + 18, cy + 36), fill=blue)


def draw_icon_ai(cx, cy):
    draw.ellipse((cx - 38, cy - 38, cx + 38, cy + 38), fill=soft_green, outline="#22C55E", width=3)
    draw.ellipse((cx - 18, cy - 10, cx - 6, cy + 2), fill=blue)
    draw.ellipse((cx + 6, cy - 10, cx + 18, cy + 2), fill=blue)
    draw.arc((cx - 18, cy - 2, cx + 18, cy + 22), 0, 180, fill=blue, width=3)


# Header
draw.ellipse((74, 72, 142, 140), fill="#EAF2FF", outline=blue, width=3)
draw.text((108, 106), "NTU", font=font(18, True), fill=blue, anchor="mm")
draw.text((160, 91), "STACKLEARN", font=font(28, True), fill=blue)
draw.text((1240, 92), "Đồ án tốt nghiệp", font=font(24), fill=blue)

# Title
draw.text((90, 198), "THIẾT KẾ HỆ THỐNG", font=font(58, True), fill=blue)
draw.rectangle((94, 282, 625, 292), fill=blue)
draw.text(
    (92, 330),
    "StackLearn được thiết kế thành hai nhóm chính: nền tảng LMS và mô-đun AI Tutor\n"
    "để vừa quản lý học tập, vừa hỗ trợ hỏi đáp theo ngữ cảnh bài học.",
    font=font(25),
    fill=deep,
    spacing=7,
)

# Main table container
rounded_box(90, 435, 1510, 765, white, radius=24)
draw.line((800, 435, 800, 765), fill=line, width=3)

draw_icon_lms(195, 515)
draw_icon_ai(910, 500)
draw.text((405, 490), "LMS CORE", font=font(30, True), fill=blue, anchor="mm")
draw.text((1195, 490), "AI TUTOR", font=font(30, True), fill=blue, anchor="mm")

lms_items = [
    ("Authentication & Authorization", "Phân quyền 3 vai trò"),
    ("Course, Lecture, Quiz", "Khóa học, bài giảng, quiz"),
    ("Order & Payment", "Giỏ hàng, thanh toán, ghi danh"),
    ("Progress & Discussion", "Tiến độ, ghi chú, thảo luận"),
]
ai_items = [
    ("Context Builder", "Course, Lesson, Transcript, History"),
    ("Prompt Engineering", "Tạo prompt theo ngữ cảnh"),
    ("Gemini API", "Xử lý ngôn ngữ tự nhiên"),
    ("Chat History", "Lưu hội thoại học tập"),
]

row_y = [560, 610, 660, 710]
for idx, ((title, desc), (ai_title, ai_desc)) in enumerate(zip(lms_items, ai_items)):
    y = row_y[idx]
    draw.line((130, y - 16, 760, y - 16), fill="#E2E8F0", width=2)
    draw.line((840, y - 16, 1470, y - 16), fill="#E2E8F0", width=2)
    draw.text((150, y), title, font=font(22, True), fill=deep, anchor="lm")
    draw.text((470, y), desc, font=font(18), fill=muted, anchor="lm")
    draw.text((860, y), ai_title, font=font(22, True), fill=deep, anchor="lm")
    draw.text((1165, y), ai_desc, font=font(18), fill=muted, anchor="lm")

# Bottom design flow
rounded_box(265, 812, 1335, 866, "#EAF2FF", outline=blue, radius=18, width=2)
draw.text(
    (800, 839),
    "Luồng thiết kế: Người dùng → Frontend → Laravel Backend → PostgreSQL → Gemini API",
    font=font(24, True),
    fill=blue,
    anchor="mm",
)

OUT.parent.mkdir(parents=True, exist_ok=True)
img.save(OUT)
print(OUT.resolve())
