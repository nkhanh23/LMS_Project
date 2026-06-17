from PIL import Image, ImageDraw, ImageFont
from pathlib import Path


OUT = Path("outputs/manual-stacklearn-ppt/stacklearn_intro_slide_mockup.png")
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
cyan = "#E0F2FE"
green = "#DCFCE7"
line = "#CBD5E1"
card = "#FFFFFF"

# Header
draw.ellipse((74, 72, 142, 140), fill="#EAF2FF", outline=blue, width=3)
draw.text((91, 92), "NTU", font=font(18, True), fill=blue)
draw.text((160, 91), "STACKLEARN", font=font(28, True), fill=blue)
draw.text((1240, 92), "Đồ án tốt nghiệp", font=font(24), fill=blue)

# Main title
draw.text((90, 220), "GIỚI THIỆU", font=font(72, True), fill=blue)
draw.rectangle((94, 320, 440, 330), fill=blue)
draw.text(
    (92, 365),
    "StackLearn là hệ thống học trực tuyến tích hợp\nAI Tutor hỗ trợ học viên học tập theo ngữ cảnh.",
    font=font(30),
    fill=deep,
    spacing=10,
)

# Illustration panel
draw.rounded_rectangle((880, 175, 1485, 515), radius=28, fill="#FFFFFF", outline=line, width=2)
draw.rounded_rectangle((930, 245, 1235, 430), radius=18, fill="#DBEAFE", outline=blue, width=3)
draw.rectangle((960, 285, 1205, 315), fill="#FFFFFF")
draw.rectangle((960, 335, 1160, 360), fill="#FFFFFF")
draw.rectangle((960, 380, 1130, 405), fill="#FFFFFF")
draw.rectangle((1020, 430, 1150, 448), fill=blue)
draw.ellipse((1285, 245, 1415, 375), fill=green, outline="#22C55E", width=3)
draw.ellipse((1315, 280, 1340, 305), fill=blue)
draw.ellipse((1360, 280, 1385, 305), fill=blue)
draw.arc((1320, 310, 1380, 350), 0, 180, fill=blue, width=4)
draw.rounded_rectangle((1240, 395, 1460, 455), radius=20, fill=cyan, outline="#38BDF8", width=2)
draw.text((1260, 412), "AI Tutor", font=font(28, True), fill=blue)

# Flow cards
items = [
    ("01", "BỐI CẢNH", "Học trực tuyến phát triển mạnh\nLMS trở thành nền tảng quan trọng"),
    ("02", "VẤN ĐỀ", "Học viên khó hỏi đáp tức thời\nGiảng viên không hỗ trợ 24/7"),
    ("03", "CƠ HỘI", "AI hỗ trợ giải thích bài học\nGemini API xử lý ngôn ngữ tự nhiên"),
    ("04", "DỰ ÁN", "StackLearn LMS + AI Tutor\nTrả lời theo ngữ cảnh bài học"),
]

x0, y0 = 92, 610
card_w, card_h, gap = 335, 180, 28
for i, (num, title, body) in enumerate(items):
    x = x0 + i * (card_w + gap)
    draw.rounded_rectangle((x, y0, x + card_w, y0 + card_h), radius=18, fill=card, outline=line, width=2)
    draw.ellipse((x + 22, y0 + 22, x + 76, y0 + 76), fill=blue)
    draw.text((x + 37, y0 + 36), num, font=font(18, True), fill="#FFFFFF")
    draw.text((x + 96, y0 + 30), title, font=font(26, True), fill=blue)
    draw.text((x + 28, y0 + 100), body, font=font(20), fill=muted, spacing=5)
    if i < len(items) - 1:
        ax = x + card_w + 4
        ay = y0 + card_h // 2
        draw.line((ax, ay, ax + gap - 8, ay), fill=blue, width=4)
        draw.polygon([(ax + gap - 8, ay), (ax + gap - 22, ay - 9), (ax + gap - 22, ay + 9)], fill=blue)

# Footer
draw.text((92, 835), "StackLearn | LMS tích hợp AI Tutor", font=font(22), fill=muted)

OUT.parent.mkdir(parents=True, exist_ok=True)
img.save(OUT)
print(OUT.resolve())
