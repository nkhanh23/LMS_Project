from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
PANEL = (255, 255, 255)
HEADERS = [(232, 240, 255), (244, 239, 255), (232, 250, 241)]


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
F52 = font(52, True)


def center(draw, box, text, fnt, fill=DARK):
    b = draw.textbbox((0, 0), text, font=fnt)
    tw, th = b[2] - b[0], b[3] - b[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def wrap(draw, text, fnt, max_width):
    words = text.split()
    lines = []
    cur = ""
    for word in words:
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


def paragraph(draw, x, y, text, fnt, max_width, fill=MID, lh=23):
    for line in wrap(draw, text, fnt, max_width):
        draw.text((x, y), line, font=fnt, fill=fill)
        y += lh
    return y


def code_panel(draw, x, y, w, h, title, subtitle, items, header_fill):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=14, fill=PANEL, outline=LINE, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 58), radius=14, fill=header_fill, outline=LINE, width=2)
    draw.rectangle((x, y + 45, x + w, y + 59), fill=header_fill)
    center(draw, (x, y + 6, x + w, y + 34), title, F22, BLUE)
    center(draw, (x + 12, y + 32, x + w - 12, y + 55), subtitle, F16, MID)
    yy = y + 80
    for item in items:
        draw.rounded_rectangle((x + 24, yy, x + w - 24, yy + 46), radius=6, fill=(248, 251, 255), outline=(220, 230, 245), width=1)
        draw.text((x + 40, yy + 13), item, font=F18, fill=DARK)
        yy += 58


out = Image.new("RGB", (W, H), BG)
d = ImageDraw.Draw(out)

# Header
d.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
d.text((91, 82), "NTU", font=F20, fill=BLUE)
d.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
d.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)
d.text((72, 160), "TRIỂN KHAI AI TUTOR", font=F52, fill=BLUE)
d.rounded_rectangle((72, 242, 690, 254), radius=3, fill=BLUE)
paragraph(
    d,
    72,
    282,
    "Phần AI Tutor được triển khai qua giao diện học bài, controller xử lý yêu cầu và các service xây dựng ngữ cảnh, prompt, truy xuất tài liệu rồi gọi Gemini API.",
    F22,
    1180,
    DARK,
    30,
)

code_panel(
    d,
    90,
    385,
    430,
    365,
    "Chatbot Widget",
    "Giao diện học bài",
    [
        "learning/index.blade.php",
        "partials/chatbot-panel.blade.php",
        "public/customjs/learning/chatbot.js",
    ],
    HEADERS[0],
)

code_panel(
    d,
    585,
    385,
    430,
    365,
    "Backend Chatbot",
    "Route, request, controller",
    [
        "routes/web.php",
        "ChatbotAskRequest.php",
        "frontend/ChatbotController.php",
    ],
    HEADERS[1],
)

code_panel(
    d,
    1080,
    385,
    430,
    365,
    "AI Services",
    "Context, RAG, Gemini",
    [
        "AiChatOrchestratorService.php",
        "AiPromptBuilderService.php",
        "AiRetrieverService.php",
        "GeminiProviderService.php",
    ],
    HEADERS[2],
)

# Flow arrows
for x in [535, 1030]:
    d.line((x, 560, x + 35, 560), fill=BLUE, width=4)
    d.polygon([(x + 35, 560), (x + 22, 550), (x + 22, 570)], fill=BLUE)

d.rounded_rectangle((340, 795, 1260, 845), radius=24, fill=(235, 243, 255), outline=BLUE, width=2)
center(d, (340, 795, 1260, 845), "Widget → Controller → Context Builder / Prompt / Retrieval → Gemini API", F22, BLUE)
d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)

path = Path("outputs/manual-stacklearn-ppt/stacklearn_ai_tutor_implementation_slide.png")
path.parent.mkdir(parents=True, exist_ok=True)
out.save(path)
print(path.resolve())
