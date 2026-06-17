from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (235, 243, 255)
WHITE = (255, 255, 255)
GREEN = (19, 154, 104)
ORANGE = (235, 147, 42)
RED = (221, 86, 86)


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
F36 = font(36, True)
F46 = font(46, True)


def center(draw, box, text, fnt, fill=DARK):
    b = draw.textbbox((0, 0), text, font=fnt)
    tw, th = b[2] - b[0], b[3] - b[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def wrap(draw, text, fnt, max_width):
    lines, cur = [], ""
    for word in text.split():
        trial = (cur + " " + word).strip()
        if draw.textbbox((0, 0), trial, font=fnt)[2] <= max_width:
            cur = trial
        else:
            if cur:
                lines.append(cur)
            cur = word
    if cur:
        lines.append(cur)
    return lines


def paragraph(draw, x, y, text, fnt, max_width, fill=MID, lh=27):
    for line in wrap(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += lh
    return y


def header(draw):
    draw.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
    draw.text((91, 82), "NTU", font=F20, fill=BLUE)
    draw.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
    draw.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)
    draw.text((72, 152), "KẾT LUẬN", font=F46, fill=BLUE)
    draw.rounded_rectangle((72, 226, 520, 238), radius=3, fill=BLUE)
    draw.rounded_rectangle((1175, 165, 1460, 216), radius=24, fill=SOFT, outline=BLUE, width=2)
    center(draw, (1175, 165, 1460, 216), "CONCLUSION", F22, BLUE)


def icon(draw, cx, cy, label, color):
    draw.ellipse((cx - 34, cy - 34, cx + 34, cy + 34), fill=SOFT, outline=color, width=3)
    center(draw, (cx - 32, cy - 32, cx + 32, cy + 32), label, F24, color)


def card(draw, x, y, w, h, num, title, desc, color):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=WHITE, outline=LINE, width=2)
    draw.rounded_rectangle((x + 22, y + 20, x + 92, y + 58), radius=18, fill=color)
    center(draw, (x + 22, y + 20, x + 92, y + 58), num, F20, WHITE)
    paragraph(draw, x + 112, y + 18, title, F22, w - 130, BLUE, 27)
    paragraph(draw, x + 24, y + 76, desc, F18, w - 48, MID, 24)


def image_placeholder(draw, x, y, w, h):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=24, fill=WHITE, outline=LINE, width=2)
    draw.rounded_rectangle((x + 24, y + 24, x + w - 24, y + h - 68), radius=16, fill=(252, 254, 255), outline=(145, 166, 205), width=2)
    inner = (x + 24, y + 24, x + w - 24, y + h - 68)
    center(draw, inner, "CHÈN ẢNH GIAO DIỆN / DEMO", F22, BLUE)
    center(draw, (x + 20, y + h - 50, x + w - 20, y + h - 16), "Ảnh minh họa kết quả StackLearn", F20, DARK)


def make_slide():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d)

    paragraph(
        d,
        72,
        275,
        "StackLearn đã hoàn thành nền tảng LMS trên Laravel, tích hợp AI Tutor sử dụng Gemini API để hỗ trợ học viên hỏi đáp theo ngữ cảnh bài học.",
        F22,
        610,
        DARK,
        31,
    )

    cards = [
        ("01", "LMS hoàn chỉnh", "Có luồng học viên, giảng viên, quản trị viên, khóa học, quiz, thanh toán và tiến độ học tập.", GREEN),
        ("02", "AI Tutor", "Hỏi đáp theo ngữ cảnh bài học, transcript, tài liệu học tập và lưu lịch sử hội thoại.", BLUE),
        ("03", "Hạn chế", "Một số màn hình nhiều dữ liệu; AI phụ thuộc transcript, tài liệu và dịch vụ Gemini API.", ORANGE),
        ("04", "Hướng phát triển", "Tối ưu RAG, trích dẫn nguồn, caching, queue, kiểm thử tải, chứng chỉ và gợi ý khóa học.", RED),
    ]
    positions = [(72, 410), (430, 410), (72, 620), (430, 620)]
    for (x, y), item in zip(positions, cards):
        card(d, x, y, 320, 160, *item)

    # Visual slot for a final UI/demo screenshot.
    image_placeholder(d, 825, 300, 690, 480)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_overview_slide.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


if __name__ == "__main__":
    make_slide()
