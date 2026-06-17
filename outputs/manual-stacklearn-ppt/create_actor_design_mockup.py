from PIL import Image, ImageDraw, ImageFont
from pathlib import Path


OUT = Path("outputs/manual-stacklearn-ppt/stacklearn_actor_design_slide_mockup.png")
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
white = "#FFFFFF"
soft_blue = "#DBEAFE"
soft_green = "#DCFCE7"
soft_yellow = "#FEF3C7"
soft_purple = "#EDE9FE"


def rounded_box(x1, y1, x2, y2, fill, outline=line, radius=22, width=2):
    draw.rounded_rectangle((x1, y1, x2, y2), radius=radius, fill=fill, outline=outline, width=width)


def user_icon(cx, cy, fill, outline, label):
    draw.ellipse((cx - 34, cy - 48, cx + 34, cy + 20), fill=fill, outline=outline, width=3)
    draw.ellipse((cx - 17, cy - 32, cx + 17, cy + 2), fill=outline)
    draw.arc((cx - 28, cy - 2, cx + 28, cy + 38), 200, -20, fill=outline, width=10)
    draw.text((cx, cy + 60), label, font=font(20, True), fill=deep, anchor="mm")


def feature_row(x, y, title, desc, icon_text, color):
    draw.ellipse((x, y, x + 52, y + 52), fill=color, outline=blue, width=2)
    draw.text((x + 26, y + 27), icon_text, font=font(20, True), fill=blue, anchor="mm")
    draw.text((x + 72, y + 4), title, font=font(25, True), fill=deep)
    draw.text((x + 72, y + 36), desc, font=font(19), fill=muted)


# Header
draw.ellipse((74, 72, 142, 140), fill="#EAF2FF", outline=blue, width=3)
draw.text((108, 106), "NTU", font=font(18, True), fill=blue, anchor="mm")
draw.text((160, 91), "STACKLEARN", font=font(28, True), fill=blue)
draw.text((1240, 92), "Đồ án tốt nghiệp", font=font(24), fill=blue)

# Title
draw.text((90, 198), "TÁC NHÂN VÀ ĐẶC ĐIỂM THIẾT KẾ", font=font(52, True), fill=blue)
draw.rectangle((94, 276, 865, 286), fill=blue)
draw.text(
    (92, 325),
    "Thiết kế StackLearn xoay quanh ba vai trò chính và các yêu cầu trải nghiệm học tập có AI hỗ trợ.",
    font=font(26),
    fill=deep,
)

# Main panel
rounded_box(90, 425, 1510, 770, white, radius=28)
draw.line((620, 445, 620, 750), fill=line, width=3)

# Left side actors
draw.text((350, 470), "ACTORS", font=font(30, True), fill=blue, anchor="mm")
user_icon(230, 585, soft_blue, blue, "Student")
user_icon(350, 585, soft_green, "#16A34A", "Instructor")
user_icon(470, 585, soft_yellow, "#D97706", "Admin")

rounded_box(165, 685, 530, 735, "#EAF2FF", outline=blue, radius=16, width=2)
draw.text((347, 710), "Học tập • Khóa học • Quản trị", font=font(17, True), fill=blue, anchor="mm")

# Right side features
draw.text((1035, 470), "ĐẶC ĐIỂM THIẾT KẾ", font=font(30, True), fill=blue, anchor="mm")
feature_row(700, 520, "Giao diện thân thiện", "Học viên dễ học, giảng viên dễ quản lý", "UI", soft_blue)
feature_row(700, 595, "Quản lý khóa học hoàn chỉnh", "Course, lecture, quiz, enrollment, payment", "LMS", soft_green)
feature_row(700, 670, "AI Tutor theo ngữ cảnh", "Trả lời dựa trên bài học, transcript, lịch sử chat", "AI", soft_purple)

# Bottom conclusion
rounded_box(260, 815, 1340, 865, "#EAF2FF", outline=blue, radius=18, width=2)
draw.text(
    (800, 840),
    "Mục tiêu thiết kế: LMS đầy đủ chức năng + AI Tutor hỗ trợ trong bài học",
    font=font(22, True),
    fill=blue,
    anchor="mm",
)

OUT.parent.mkdir(parents=True, exist_ok=True)
img.save(OUT)
print(OUT.resolve())
