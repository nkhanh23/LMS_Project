from PIL import Image, ImageDraw, ImageFont
from pathlib import Path
import math


OUT = Path("outputs/manual-stacklearn-ppt/stacklearn_survey_slide_with_logos.png")
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
soft_yellow = "#FEF3C7"
soft_purple = "#EDE9FE"
white = "#FFFFFF"


def rounded_box(x1, y1, x2, y2, fill, outline=line, radius=28, width=2):
    draw.rounded_rectangle((x1, y1, x2, y2), radius=radius, fill=fill, outline=outline, width=width)


def draw_star(cx, cy, r, fill):
    pts = []
    for i in range(10):
        ang = -math.pi / 2 + i * math.pi / 5
        rr = r if i % 2 == 0 else r * 0.42
        pts.append((cx + math.cos(ang) * rr, cy + math.sin(ang) * rr))
    draw.polygon(pts, fill=fill)


def draw_atom(cx, cy, color):
    for angle in [0, 60, -60]:
        box = (cx - 26, cy - 10, cx + 26, cy + 10)
        layer = Image.new("RGBA", (W, H), (0, 0, 0, 0))
        ld = ImageDraw.Draw(layer)
        ld.ellipse(box, outline=color, width=3)
        layer = layer.rotate(angle, center=(cx, cy), resample=Image.Resampling.BICUBIC)
        img.paste(Image.alpha_composite(Image.new("RGBA", (W, H), (0, 0, 0, 0)), layer).convert("RGB"), mask=layer.split()[-1])
    draw.ellipse((cx - 5, cy - 5, cx + 5, cy + 5), fill=color)


def draw_chatgpt_mark(cx, cy, color):
    for i in range(6):
        ang = i * math.pi / 3
        x = cx + math.cos(ang) * 14
        y = cy + math.sin(ang) * 14
        draw.arc((x - 18, y - 18, x + 18, y + 18), 25, 230, fill=color, width=4)
    draw.ellipse((cx - 8, cy - 8, cx + 8, cy + 8), outline=color, width=3)


def logo_card(x, y, w, h, name, fill, kind):
    rounded_box(x, y, x + w, y + h, fill, outline=line, radius=22)
    icon_x, icon_y = x + 42, y + h // 2
    if kind == "moodle":
        draw.text((icon_x, icon_y + 2), "m", font=font(34, True), fill="#F97316", anchor="mm")
        draw.polygon([(icon_x - 18, icon_y - 26), (icon_x + 18, icon_y - 26), (icon_x, icon_y - 38)], fill="#111827")
        draw.line((icon_x + 16, icon_y - 26, icon_x + 30, icon_y - 18), fill="#111827", width=3)
    elif kind == "coursera":
        draw.ellipse((icon_x - 24, icon_y - 24, icon_x + 24, icon_y + 24), fill="#0056D2")
        draw.text((icon_x, icon_y), "C", font=font(28, True), fill=white, anchor="mm")
    elif kind == "udemy":
        draw.text((icon_x, icon_y), "U", font=font(34, True), fill="#A435F0", anchor="mm")
        draw.polygon([(icon_x - 15, icon_y - 30), (icon_x + 15, icon_y - 30), (icon_x, icon_y - 42)], fill="#A435F0")
    elif kind == "edx":
        draw.text((icon_x, icon_y), "edX", font=font(21, True), fill="#111827", anchor="mm")
    elif kind == "elsa":
        draw_atom(icon_x, icon_y, "#22C55E")
    elif kind == "khan":
        draw.ellipse((icon_x - 24, icon_y - 24, icon_x + 24, icon_y + 24), fill="#10B981")
        draw.text((icon_x, icon_y), "K", font=font(28, True), fill=white, anchor="mm")
    elif kind == "chatgpt":
        draw_chatgpt_mark(icon_x, icon_y, "#111827")
    elif kind == "gemini":
        draw_star(icon_x, icon_y, 25, "#7C3AED")
    draw.text((x + 83, y + h / 2), name, font=font(26, True), fill=blue, anchor="lm")


# Header
draw.ellipse((74, 72, 142, 140), fill="#EAF2FF", outline=blue, width=3)
draw.text((108, 106), "NTU", font=font(18, True), fill=blue, anchor="mm")
draw.text((160, 91), "STACKLEARN", font=font(28, True), fill=blue)
draw.text((1240, 92), "Đồ án tốt nghiệp", font=font(24), fill=blue)

# Title
draw.text((90, 205), "KHẢO SÁT HỆ THỐNG LIÊN QUAN", font=font(54, True), fill=blue)
draw.rectangle((94, 285, 735, 295), fill=blue)
draw.text(
    (92, 330),
    "Các nền tảng hiện có thường mạnh về LMS hoặc AI riêng lẻ,\n"
    "nhưng chưa tập trung vào AI Tutor theo ngữ cảnh bài học.",
    font=font(28),
    fill=deep,
    spacing=8,
)

# Column containers
rounded_box(90, 455, 720, 745, white)
rounded_box(880, 455, 1510, 745, white)

draw.text((405, 495), "LMS TRUYỀN THỐNG", font=font(28, True), fill=blue, anchor="mm")
draw.text((1195, 495), "AI / TRỢ LÝ HỌC TẬP", font=font(28, True), fill=blue, anchor="mm")

logo_card(140, 545, 250, 70, "Moodle", soft_blue, "moodle")
logo_card(430, 545, 250, 70, "Coursera", soft_green, "coursera")
logo_card(140, 635, 250, 70, "Udemy", soft_yellow, "udemy")
logo_card(430, 635, 250, 70, "edX", soft_purple, "edx")

logo_card(930, 545, 250, 70, "ELSA", soft_purple, "elsa")
logo_card(1220, 545, 250, 70, "Khanmigo", soft_green, "khan")
logo_card(930, 635, 250, 70, "ChatGPT", soft_blue, "chatgpt")
logo_card(1220, 635, 250, 70, "Gemini", soft_yellow, "gemini")

draw.text(
    (405, 765),
    "Mạnh về khóa học, bài giảng, quiz, thanh toán\nnhưng thiếu hỏi đáp theo ngữ cảnh.",
    font=font(21),
    fill=muted,
    anchor="ma",
    align="center",
    spacing=5,
)
draw.text(
    (1195, 765),
    "Mạnh về hỏi đáp và giải thích kiến thức\nnhưng không phải LMS hoàn chỉnh.",
    font=font(21),
    fill=muted,
    anchor="ma",
    align="center",
    spacing=5,
)

# Center bridge
draw.line((800, 470, 800, 760), fill=line, width=3)
draw.ellipse((730, 560, 870, 700), fill=blue)
draw.text((800, 610), "STACK", font=font(22, True), fill=white, anchor="mm")
draw.text((800, 645), "LEARN", font=font(22, True), fill=white, anchor="mm")
draw.polygon([(716, 630), (690, 614), (690, 646)], fill=blue)
draw.polygon([(884, 630), (910, 614), (910, 646)], fill=blue)

# Conclusion strip
rounded_box(330, 823, 1270, 876, "#EAF2FF", outline=blue, radius=18, width=2)
draw.text(
    (800, 850),
    "Định hướng StackLearn: kết hợp LMS hoàn chỉnh với Context-Aware AI Tutor",
    font=font(25, True),
    fill=blue,
    anchor="mm",
)

OUT.parent.mkdir(parents=True, exist_ok=True)
img.save(OUT)
print(OUT.resolve())
