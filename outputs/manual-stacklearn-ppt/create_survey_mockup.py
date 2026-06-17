from PIL import Image, ImageDraw, ImageFont
from pathlib import Path


OUT = Path("outputs/manual-stacklearn-ppt/stacklearn_survey_slide_mockup.png")
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


def logo_card(x, y, w, h, text, fill):
    rounded_box(x, y, x + w, y + h, fill, outline=line, radius=22)
    draw.text((x + w / 2, y + h / 2), text, font=font(28, True), fill=blue, anchor="mm")


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

logo_card(140, 545, 250, 70, "Moodle", soft_blue)
logo_card(430, 545, 250, 70, "Coursera", soft_green)
logo_card(140, 635, 250, 70, "Udemy", soft_yellow)
logo_card(430, 635, 250, 70, "edX", soft_purple)

logo_card(930, 545, 250, 70, "ELSA", soft_purple)
logo_card(1220, 545, 250, 70, "Khanmigo", soft_green)
logo_card(930, 635, 250, 70, "ChatGPT", soft_blue)
logo_card(1220, 635, 250, 70, "Gemini", soft_yellow)

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
