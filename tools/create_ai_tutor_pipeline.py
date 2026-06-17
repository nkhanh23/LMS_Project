from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "canva" / "ai_tutor_pipeline.png"
W, H = 1920, 1080

BG = "#F8FAFF"
BLUE = "#2C58C9"
DARK_BLUE = "#183B8E"
NAVY = "#1C2B4A"
GRAY = "#5F6778"
LINE = "#C7D9FF"
CARD = "#FFFFFF"
LIGHT_BLUE = "#EDF4FF"
LIGHT_GREEN = "#EAFBF4"
GREEN = "#11A36A"
ORANGE = "#F57C00"
PURPLE = "#7654D9"
RED = "#E84545"


def load_font(kind: str, size: int):
    font_dir = Path(r"C:\Windows\Fonts")
    candidates = {
        "bold": ["arialbd.ttf"],
        "regular": ["arial.ttf"],
        "italic": ["ariali.ttf"],
    }[kind]
    for name in candidates:
        path = font_dir / name
        if path.exists():
            return ImageFont.truetype(str(path), size)
    return ImageFont.load_default()


F_TITLE = load_font("bold", 54)
F_SUB = load_font("regular", 25)
F_SECTION = load_font("bold", 27)
F_CARD_TITLE = load_font("bold", 26)
F_CARD_BODY = load_font("regular", 19)
F_SMALL = load_font("regular", 18)
F_BADGE = load_font("bold", 22)


img = Image.new("RGB", (W, H), BG)
draw = ImageDraw.Draw(img)


def wrap(text, font, max_width):
    words = text.split()
    lines, cur = [], ""
    for word in words:
        test = f"{cur} {word}".strip()
        if draw.textbbox((0, 0), test, font=font)[2] <= max_width:
            cur = test
        else:
            if cur:
                lines.append(cur)
            cur = word
    if cur:
        lines.append(cur)
    return "\n".join(lines)


def center(text, box, font, fill=NAVY, spacing=4):
    x1, y1, x2, y2 = box
    bbox = draw.multiline_textbbox((0, 0), text, font=font, spacing=spacing, align="center")
    tw, th = bbox[2] - bbox[0], bbox[3] - bbox[1]
    draw.multiline_text(
        (x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2),
        text,
        font=font,
        fill=fill,
        spacing=spacing,
        align="center",
    )


def arrow(x1, y1, x2, y2, color=BLUE, width=4):
    draw.line((x1, y1, x2, y2), fill=color, width=width)
    size = 15
    if abs(x2 - x1) >= abs(y2 - y1):
        if x2 >= x1:
            pts = [(x2, y2), (x2 - size, y2 - 9), (x2 - size, y2 + 9)]
        else:
            pts = [(x2, y2), (x2 + size, y2 - 9), (x2 + size, y2 + 9)]
    else:
        if y2 >= y1:
            pts = [(x2, y2), (x2 - 9, y2 - size), (x2 + 9, y2 - size)]
        else:
            pts = [(x2, y2), (x2 - 9, y2 + size), (x2 + 9, y2 + size)]
    draw.polygon(pts, fill=color)


def card(x, y, w, h, no, title, body, accent=BLUE):
    shadow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    sd = ImageDraw.Draw(shadow)
    sd.rounded_rectangle((x + 6, y + 8, x + w + 6, y + h + 8), radius=18, fill=(35, 65, 130, 18))
    global img, draw
    img = Image.alpha_composite(img.convert("RGBA"), shadow).convert("RGB")
    draw = ImageDraw.Draw(img)
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=CARD, outline=LINE, width=2)
    draw.ellipse((x + 18, y + 18, x + 62, y + 62), fill=accent)
    center(no, (x + 18, y + 18, x + 62, y + 62), F_BADGE, "white")
    draw.text((x + 76, y + 18), title, font=F_CARD_TITLE, fill=accent)
    draw.text((x + 76, y + 56), wrap(body, F_CARD_BODY, w - 96), font=F_CARD_BODY, fill=GRAY, spacing=5)


def pill(x, y, text, w, fill="#FFFFFF", outline=LINE, color=NAVY):
    draw.rounded_rectangle((x, y, x + w, y + 38), radius=19, fill=fill, outline=outline, width=2)
    center(text, (x + 8, y, x + w - 8, y + 38), F_SMALL, color)


# Background accents.
draw.ellipse((-70, -80, 90, 80), fill="#EAF1FF")
draw.ellipse((1810, 40, 1870, 100), fill="#EAF1FF")
draw.ellipse((1768, 930, 1860, 1022), fill="#EAF1FF")
draw.line((740, 96, 1180, 96), fill=LINE, width=3)

# Header.
center("CƠ CHẾ XỬ LÝ CÂU HỎI CỦA AI TUTOR", (120, 112, 1800, 176), F_TITLE, BLUE)
center(
    "Thuật toán kết hợp RAG: kiểm tra quyền, truy xuất vector/keyword, chấm độ tin cậy evidence, dựng prompt và lưu citation.",
    (190, 180, 1730, 218),
    F_SUB,
    GRAY,
)

# Top runtime pipeline.
top_y = 270
cards = [
    (70, top_y, 255, 158, "01", "Nhận câu hỏi", "Validate request, tạo/lấy chat session và lưu user message.", BLUE),
    (380, top_y, 285, 158, "02", "Kiểm tra quyền", "Xác thực user đã ghi danh và có quyền mở lecture.", DARK_BLUE),
    (720, top_y, 320, 158, "03", "Embedding query", "Biến câu hỏi thành vector để so khớp semantic.", GREEN),
    (1095, top_y, 335, 158, "04", "Retrieve evidence", "Tìm chunk liên quan bằng vector + keyword + concept boost.", ORANGE),
    (1485, top_y, 360, 158, "05", "Prompt + Gemini", "Dựng prompt có luật evidence, gọi Gemini, nhận answer.", BLUE),
]
for item in cards:
    card(*item)
for x1, x2 in [(325, 380), (665, 720), (1040, 1095), (1430, 1485)]:
    arrow(x1, top_y + 72, x2, top_y + 72)

# Retrieval algorithm block.
draw.rounded_rectangle((115, 500, 1805, 765), radius=24, fill=LIGHT_BLUE, outline=LINE, width=3)
center("THUẬT TOÁN TRUY XUẤT NGỮ CẢNH / EVIDENCE", (160, 516, 1760, 552), F_SECTION, BLUE)

algo_cols = [
    ("A. Vector Search", "pgvector cosine similarity\nscore = 1 - distance\nưu tiên lecture hiện tại", GREEN),
    ("B. Keyword Search", "ILIKE + full-text search\nts_rank theo nội dung chunk\nfallback khi vector chưa đủ", ORANGE),
    ("C. Concept Boost", "lấy concept của lecture\nchunk trùng concept: +0.05\nboost tối đa +0.15", PURPLE),
    ("D. Merge & Rank", "gộp lesson vector + keyword\n+ course vector\nunique chunk, sort score giảm dần", BLUE),
    ("E. Evidence Gate", "score > 0.2 và >= 2 chunk: enough\nít hơn: weak\nkhông có: no evidence", RED),
]
col_w = 300
gap = 25
start_x = 165
for i, (title, body, color) in enumerate(algo_cols):
    x = start_x + i * (col_w + gap)
    draw.rounded_rectangle((x, 580, x + col_w, 728), radius=18, fill="#FFFFFF", outline="#C9DAFF", width=2)
    draw.text((x + 20, 598), title, font=F_CARD_TITLE, fill=color)
    draw.text((x + 20, 636), body, font=F_SMALL, fill=GRAY, spacing=6)
    if i < len(algo_cols) - 1:
        arrow(x + col_w + 2, 654, x + col_w + gap - 4, 654, BLUE, 3)

arrow(1260, top_y + 158, 1260, 500, ORANGE, 4)

# Prompt and output block.
draw.rounded_rectangle((115, 820, 1230, 985), radius=24, fill="#FFFFFF", outline=LINE, width=3)
center("PROMPT BUILDER", (150, 836, 570, 870), F_SECTION, BLUE)
pill(170, 895, "Course title", 170)
pill(360, 895, "Lecture title", 175)
pill(560, 895, "Recent history: 8 messages", 260)
pill(845, 895, "Top 5 chunks", 180)
pill(1045, 895, "Evidence strength", 165)
draw.text(
    (170, 943),
    "Luật prompt: chỉ trả lời dựa trên EVIDENCE, không bịa, nêu mức chưa chắc chắn nếu evidence yếu, cuối câu trả lời có nguồn tham khảo.",
    font=F_SMALL,
    fill=GRAY,
)

draw.rounded_rectangle((1290, 820, 1805, 985), radius=24, fill=LIGHT_GREEN, outline="#B9E8D2", width=3)
draw.ellipse((1325, 865, 1378, 918), fill=GREEN)
center("✓", (1325, 860, 1378, 918), load_font("bold", 32), "white")
draw.text((1400, 850), "KẾT QUẢ TRẢ VỀ", font=F_SECTION, fill=GREEN)
draw.text((1400, 892), "answer_status: success / weak_evidence / no_evidence", font=F_SMALL, fill=NAVY)
draw.text((1400, 922), "Lưu assistant message, citation, chunk_id, score và snippet.", font=F_SMALL, fill=GRAY)
draw.text((1400, 952), "Hiển thị câu trả lời trong chatbot của học viên.", font=F_SMALL, fill=GRAY)

# Route the provider result around the evidence panel so it does not cross labels.
draw.line((1665, top_y + 158, 1665, 468), fill=BLUE, width=4)
draw.line((1665, 468, 1828, 468), fill=BLUE, width=4)
draw.line((1828, 468, 1828, 800), fill=BLUE, width=4)
draw.line((1828, 800, 1550, 800), fill=BLUE, width=4)
arrow(1550, 800, 1550, 820, BLUE, 4)
arrow(1230, 902, 1290, 902, GREEN, 4)

# Small footer labels.
draw.text((124, 1015), "StackLearn LMS + AI Tutor", font=F_SMALL, fill=BLUE)
draw.text((1370, 1015), "Model config: temperature 0.1-0.2, max output 900 tokens", font=F_SMALL, fill=GRAY)

img.save(OUT, quality=95)
print(OUT)
