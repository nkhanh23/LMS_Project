from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
BG = (248, 250, 255)
SOFT = (235, 243, 255)
GREEN = (232, 250, 241)
YELLOW = (255, 248, 225)
PURPLE = (244, 239, 255)
RED = (255, 239, 239)
CYAN = (230, 248, 252)


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
F32 = font(32, True)
F50 = font(50, True)


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


def paragraph(draw, x, y, text, fnt, max_width, fill=MID, lh=26):
    for line in wrap(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += lh
    return y


def stack_card(draw, x, y, w, h, title, items, fill):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=16, fill=(255, 255, 255), outline=LINE, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 52), radius=16, fill=fill, outline=LINE, width=2)
    draw.rectangle((x, y + 40, x + w, y + 53), fill=fill)
    center(draw, (x, y + 8, x + w, y + 36), title, F22, BLUE)
    yy = y + 72
    for item in items:
        draw.rounded_rectangle((x + 20, yy, x + w - 20, yy + 34), radius=17, fill=(249, 251, 255), outline=(222, 231, 245), width=1)
        center(draw, (x + 20, yy, x + w - 20, yy + 34), item, F18, DARK)
        yy += 44


def create_stack_diagram():
    W, H = 1180, 680
    im = Image.new("RGB", (W, H), (255, 255, 255))
    d = ImageDraw.Draw(im)
    d.rounded_rectangle((20, 20, W - 20, H - 20), radius=26, fill=(255, 255, 255), outline=LINE, width=2)
    center(d, (40, 45, W - 40, 90), "Tech Stack StackLearn", F32, BLUE)

    stack_card(d, 70, 125, 310, 220, "Frontend", ["Blade", "Tailwind CSS", "Alpine.js", "Vite + JavaScript"], SOFT)
    stack_card(d, 435, 125, 310, 220, "Backend", ["PHP 8.2", "Laravel 12", "Laravel Breeze", "Service / Repository"], YELLOW)
    stack_card(d, 800, 125, 310, 220, "Data & Realtime", ["PostgreSQL", "Database Queue", "Reverb / Echo", "Notifications"], GREEN)
    stack_card(d, 250, 405, 310, 180, "AI Tutor", ["Gemini API", "Embedding / Retrieval", "OpenAI Transcription"], PURPLE)
    stack_card(d, 620, 405, 310, 180, "Integrations", ["Stripe / VNPay", "Cloudflare R2 / S3", "Socialite Google/Facebook"], RED)

    # simple center node and arcs
    d.ellipse((525, 315, 655, 445), fill=(235, 243, 255), outline=BLUE, width=3)
    center(d, (525, 315, 655, 445), "StackLearn", F20, BLUE)
    for p in [(380, 235), (435, 235), (745, 235), (800, 235), (560, 495), (620, 495)]:
        d.line((590, 380, p[0], p[1]), fill=(120, 141, 180), width=2)
    return im


def create_slide_placeholder():
    W, H = 1600, 900
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)

    d.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
    d.text((91, 82), "NTU", font=F20, fill=BLUE)
    d.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
    d.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)

    d.text((72, 155), "TECH STACK", font=F50, fill=BLUE)
    d.rounded_rectangle((72, 235, 430, 247), radius=3, fill=BLUE)
    paragraph(
        d,
        72,
        285,
        "StackLearn sử dụng Laravel làm lõi backend, Blade/Tailwind cho giao diện, PostgreSQL cho dữ liệu và Gemini API cho AI Tutor.",
        F22,
        560,
        DARK,
        30,
    )

    items = [
        ("Frontend", "Blade, Tailwind CSS, Alpine.js, Vite"),
        ("Backend", "PHP 8.2, Laravel 12, Breeze"),
        ("Database", "PostgreSQL, database queue"),
        ("AI Tutor", "Gemini API, retrieval, transcription"),
        ("Integrations", "Stripe/VNPay, R2/S3, Socialite"),
    ]
    y = 405
    for title, desc in items:
        d.ellipse((82, y + 4, 110, y + 32), fill=BLUE)
        center(d, (82, y + 4, 110, y + 32), "✓", F18, (255, 255, 255))
        d.text((125, y), title, font=F22, fill=BLUE)
        paragraph(d, 125, y + 28, desc, F18, 500, MID, 24)
        y += 80

    px1, py1, px2, py2 = 690, 180, 1515, 790
    d.rounded_rectangle((px1, py1, px2, py2), radius=22, fill=(255, 255, 255), outline=LINE, width=3)
    d.rounded_rectangle((px1 + 28, py1 + 28, px2 - 28, py2 - 28), radius=14, outline=(142, 165, 205), width=3)
    for off in range(-520, 840, 26):
        d.line((px1 + 28 + off, py2 - 28, px1 + 28 + off + 560, py1 + 28), fill=(230, 237, 248), width=1)
    center(d, (px1 + 40, py1 + 250, px2 - 40, py1 + 295), "CHÈN ẢNH TECH STACK", F28, BLUE)
    center(d, (px1 + 40, py1 + 305, px2 - 40, py1 + 340), "Frontend / Backend / Database / AI / Integrations", F20, MID)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    return im


out_dir = Path("outputs/manual-stacklearn-ppt")
out_dir.mkdir(parents=True, exist_ok=True)
diagram_path = out_dir / "stacklearn_tech_stack_diagram_only.png"
slide_path = out_dir / "stacklearn_tech_stack_placeholder_slide.png"
create_stack_diagram().save(diagram_path)
create_slide_placeholder().save(slide_path)
print(diagram_path.resolve())
print(slide_path.resolve())
