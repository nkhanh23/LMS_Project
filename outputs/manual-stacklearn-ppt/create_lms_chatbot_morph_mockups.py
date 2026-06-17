from PIL import Image, ImageDraw, ImageFont
from pathlib import Path

OUT_DIR = Path("outputs/manual-stacklearn-ppt")
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


C = {
    "bg": "#F7FAFF",
    "blue": "#1D4ED8",
    "deep": "#0F172A",
    "muted": "#475569",
    "line": "#CBD5E1",
    "white": "#FFFFFF",
    "soft_blue": "#DBEAFE",
    "soft_green": "#DCFCE7",
    "soft_purple": "#EDE9FE",
    "soft_yellow": "#FEF3C7",
    "tab": "#EAF2FF",
}


def rounded(draw, xy, fill, outline=None, radius=24, width=2):
    draw.rounded_rectangle(xy, radius=radius, fill=fill, outline=outline or fill, width=width)


def header(draw, title):
    draw.ellipse((74, 72, 142, 140), fill="#EAF2FF", outline=C["blue"], width=3)
    draw.text((108, 106), "NTU", font=font(18, True), fill=C["blue"], anchor="mm")
    draw.text((160, 91), "STACKLEARN", font=font(28, True), fill=C["blue"])
    draw.text((1240, 92), "Đồ án tốt nghiệp", font=font(24), fill=C["blue"])
    draw.text((90, 198), title, font=font(58, True), fill=C["blue"])
    draw.rectangle((94, 282, 625, 292), fill=C["blue"])


def tab_menu(draw, active):
    x1, y1, x2, y2 = 92, 430, 252, 770
    rounded(draw, (x1, y1, x2, y2), "#EEF2FF", C["line"], radius=26, width=2)
    tabs = [("LMS", 458, 572), ("Chatbot", 598, 712)]
    for name, ty1, ty2 in tabs:
        is_active = name == active
        fill = C["blue"] if is_active else "#F8FAFC"
        text = C["white"] if is_active else C["muted"]
        rounded(draw, (112, ty1, 232, ty2), fill, C["blue"] if is_active else "#E2E8F0", radius=20, width=2)
        draw.text((172, (ty1 + ty2) / 2), name, font=font(19, True), fill=text, anchor="mm")
    if active == "LMS":
        draw.polygon([(244, 515), (274, 497), (274, 533)], fill=C["blue"])
    else:
        draw.polygon([(244, 655), (274, 637), (274, 673)], fill=C["blue"])


def draw_lms_icon(draw, cx, cy):
    rounded(draw, (cx - 68, cy - 50, cx + 68, cy + 44), C["soft_blue"], C["blue"], radius=14, width=4)
    draw.rectangle((cx - 46, cy - 24, cx + 46, cy - 10), fill=C["white"])
    draw.rectangle((cx - 46, cy + 4, cx + 26, cy + 16), fill=C["white"])
    draw.rectangle((cx - 30, cy + 48, cx + 30, cy + 58), fill=C["blue"])


def draw_bot_icon(draw, cx, cy):
    draw.ellipse((cx - 58, cy - 58, cx + 58, cy + 58), fill=C["soft_green"], outline="#16A34A", width=4)
    draw.ellipse((cx - 28, cy - 18, cx - 10, cy), fill=C["blue"])
    draw.ellipse((cx + 10, cy - 18, cx + 28, cy), fill=C["blue"])
    draw.arc((cx - 28, cy - 6, cx + 28, cy + 34), 0, 180, fill=C["blue"], width=5)
    draw.line((cx, cy - 60, cx, cy - 90), fill="#16A34A", width=4)
    draw.ellipse((cx - 8, cy - 100, cx + 8, cy - 84), fill="#16A34A")


def feature(draw, x, y, icon, title, desc, fill):
    draw.ellipse((x, y, x + 48, y + 48), fill=fill, outline=C["blue"], width=2)
    draw.text((x + 24, y + 25), icon, font=font(13, True), fill=C["blue"], anchor="mm")
    draw.text((x + 70, y - 1), title, font=font(24, True), fill=C["deep"])
    draw.text((x + 70, y + 31), desc, font=font(18), fill=C["muted"])


def make_slide(kind):
    img = Image.new("RGB", (W, H), C["bg"])
    draw = ImageDraw.Draw(img)
    header(draw, "THIẾT KẾ HỆ THỐNG")
    draw.text(
        (92, 330),
        "Hai thành phần chính được thiết kế cùng một kiến trúc để dễ chuyển đổi khi thuyết trình.",
        font=font(26),
        fill=C["deep"],
    )
    tab_menu(draw, "LMS" if kind == "lms" else "Chatbot")

    rounded(draw, (285, 430, 1510, 770), C["white"], C["line"], radius=28, width=2)
    draw.line((745, 460, 745, 740), fill=C["line"], width=3)

    if kind == "lms":
        draw.text((525, 485), "LMS CORE", font=font(34, True), fill=C["blue"], anchor="mm")
        draw_lms_icon(draw, 525, 600)
        draw.text((525, 705), "Quản lý học tập trực tuyến", font=font(24, True), fill=C["deep"], anchor="mm")
        rows = [
            ("AUTH", "Phân quyền người dùng", "Student, Instructor, Admin"),
            ("CRS", "Quản lý khóa học", "Course, section, lecture, quiz"),
            ("PAY", "Thanh toán và ghi danh", "Cart, order, payment, enrollment"),
            ("PROG", "Theo dõi học tập", "Progress, note, discussion"),
        ]
        footer = "Slide kế tiếp Morph sang Chatbot: giữ menu và khung, chỉ chuyển vùng nội dung chính."
    else:
        draw.text((525, 485), "AI TUTOR", font=font(34, True), fill=C["blue"], anchor="mm")
        draw_bot_icon(draw, 525, 590)
        draw.text((525, 705), "Hỏi đáp theo ngữ cảnh bài học", font=font(24, True), fill=C["deep"], anchor="mm")
        rows = [
            ("CTX", "Context Builder", "Course, lesson, transcript, history"),
            ("PRM", "Prompt Engineering", "Tạo prompt theo câu hỏi"),
            ("API", "Gemini API", "Xử lý và sinh phản hồi"),
            ("SAVE", "Chat History", "Lưu lịch sử học tập"),
        ]
        footer = "Morph từ LMS sang Chatbot: tab Chatbot được chọn, nội dung chuyển sang AI Tutor."

    draw.text((1120, 485), "THÀNH PHẦN CHÍNH", font=font(32, True), fill=C["blue"], anchor="mm")
    colors = [C["soft_blue"], C["soft_green"], C["soft_purple"], C["soft_yellow"]]
    for i, (ic, title, desc) in enumerate(rows):
        feature(draw, 835, 535 + i * 58, ic, title, desc, colors[i])

    rounded(draw, (285, 812, 1510, 866), "#EAF2FF", C["blue"], radius=18, width=2)
    draw.text((897, 839), footer, font=font(21, True), fill=C["blue"], anchor="mm")
    return img


OUT_DIR.mkdir(parents=True, exist_ok=True)
make_slide("lms").save(OUT_DIR / "stacklearn_morph_lms_slide.png")
make_slide("chatbot").save(OUT_DIR / "stacklearn_morph_chatbot_slide.png")
print((OUT_DIR / "stacklearn_morph_lms_slide.png").resolve())
print((OUT_DIR / "stacklearn_morph_chatbot_slide.png").resolve())
