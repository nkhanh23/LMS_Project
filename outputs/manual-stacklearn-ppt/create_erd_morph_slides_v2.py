from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (74, 88, 112)
LINE = (184, 198, 220)
LINK = (92, 110, 142)
TABLE_BORDER = (70, 96, 145)
COLORS = {
    "user": (232, 240, 255),
    "course": (255, 248, 225),
    "learn": (244, 239, 255),
    "quiz": (230, 248, 252),
    "pay": (255, 239, 239),
    "ai": (244, 239, 255),
    "doc": (232, 250, 241),
    "config": (255, 239, 239),
}


def font(size, bold=False):
    paths = []
    if bold:
        paths += [r"C:\Windows\Fonts\arialbd.ttf", r"C:\Windows\Fonts\segoeuib.ttf"]
    paths += [r"C:\Windows\Fonts\arial.ttf", r"C:\Windows\Fonts\segoeui.ttf", r"C:\Windows\Fonts\tahoma.ttf"]
    for p in paths:
        if Path(p).exists():
            return ImageFont.truetype(p, size)
    return ImageFont.load_default()


F12 = font(12)
F13 = font(13)
F16 = font(16)
F18 = font(18)
F20 = font(20)
F22 = font(22, True)
F24 = font(24, True)
F28 = font(28, True)
F50 = font(50, True)


def center(draw, box, text, fnt, fill=DARK):
    b = draw.textbbox((0, 0), text, font=fnt)
    tw, th = b[2] - b[0], b[3] - b[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def header(draw, badge):
    draw.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
    draw.text((91, 82), "NTU", font=F20, fill=BLUE)
    draw.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
    draw.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)
    draw.text((72, 160), "SƠ ĐỒ ERD CƠ SỞ DỮ LIỆU", font=F50, fill=BLUE)
    draw.rounded_rectangle((72, 242, 760, 254), radius=3, fill=BLUE)
    draw.text((72, 278), "Các bảng chính được rút gọn từ ERD tổng thể để phù hợp trình bày.", font=F22, fill=DARK)
    draw.rounded_rectangle((1210, 170, 1460, 222), radius=24, fill=(235, 243, 255), outline=BLUE, width=2)
    center(draw, (1210, 170, 1460, 222), badge, F22, BLUE)


def table_box(draw, t):
    x, y, w = t["x"], t["y"], t["w"]
    rows = t["rows"]
    h = 34 + 22 * len(rows)
    t["h"] = h
    fill = COLORS[t["group"]]
    draw.rounded_rectangle((x, y, x + w, y + h), radius=7, fill=(255, 255, 255), outline=TABLE_BORDER, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 32), radius=7, fill=fill, outline=TABLE_BORDER, width=2)
    draw.rectangle((x, y + 24, x + w, y + 33), fill=fill)
    center(draw, (x, y, x + w, y + 32), t["title"], F16, BLUE)
    for i, row in enumerate(rows):
        yy = y + 34 + i * 22
        draw.line((x, yy, x + w, yy), fill=(224, 232, 244), width=1)
        draw.text((x + 10, yy + 4), row, font=F12, fill=DARK)


def point(t, side):
    x, y, w, h = t["x"], t["y"], t["w"], t.get("h", 100)
    return {
        "left": (x, y + h / 2),
        "right": (x + w, y + h / 2),
        "top": (x + w / 2, y),
        "bottom": (x + w / 2, y + h),
    }[side]


def connect(draw, a, b, sa="right", sb="left"):
    p1, p2 = point(a, sa), point(b, sb)
    mx = (p1[0] + p2[0]) / 2
    draw.line((p1[0], p1[1], mx, p1[1], mx, p2[1], p2[0], p2[1]), fill=LINK, width=2)


def base(badge):
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, badge)
    d.rounded_rectangle((72, 330, 1528, 805), radius=18, fill=(255, 255, 255), outline=LINE, width=2)
    d.text((72, 840), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    return im, d


def draw_all(draw, tables, links):
    for a, b, sa, sb in links:
        connect(draw, tables[a], tables[b], sa, sb)
    for t in tables.values():
        table_box(draw, t)


def make_lms():
    im, d = base("LMS CORE")
    data = {
        "users": ("users", ["PK id", "name", "email", "role"], 105, 380, 190, "user"),
        "carts": ("carts", ["PK id", "FK user_id", "FK course_id"], 105, 505, 190, "pay"),
        "enrollments": ("enrollments", ["PK id", "FK user_id", "FK course_id", "status"], 105, 625, 210, "learn"),
        "categories": ("categories", ["PK id", "name"], 360, 365, 205, "course"),
        "sub_categories": ("sub_categories", ["PK id", "FK category_id", "name"], 360, 485, 205, "course"),
        "course_progress": ("course_progress", ["PK id", "FK enrollment_id", "percent"], 360, 635, 220, "learn"),
        "courses": ("courses", ["PK id", "FK user_id", "FK category_id", "title"], 635, 390, 225, "course"),
        "lesson_progress": ("lesson_progress", ["PK id", "FK enrollment_id", "FK lecture_id"], 635, 635, 225, "learn"),
        "course_sections": ("course_sections", ["PK id", "FK course_id", "title"], 900, 365, 220, "course"),
        "lecture_notes": ("lecture_notes", ["PK id", "FK user_id", "FK lecture_id"], 900, 505, 220, "learn"),
        "lecture_discussions": ("lecture_discussions", ["PK id", "FK user_id", "FK lecture_id"], 900, 635, 220, "learn"),
        "course_lectures": ("course_lectures", ["PK id", "FK course_id", "FK section_id", "title"], 1165, 365, 230, "course"),
        "quizzes": ("quizzes", ["PK id", "FK lecture_id", "title"], 1165, 525, 210, "quiz"),
        "quiz_questions": ("quiz_questions", ["PK id", "FK quiz_id", "question"], 1165, 665, 220, "quiz"),
        "orders": ("orders", ["PK id", "FK user_id", "FK course_id", "FK payment_id"], 1400, 455, 110, "pay"),
        "payments": ("payments", ["PK id", "amount", "method", "status"], 1400, 625, 110, "pay"),
    }
    tables = {k: {"title": v[0], "rows": v[1], "x": v[2], "y": v[3], "w": v[4], "group": v[5], "h": 34 + 22 * len(v[1])} for k, v in data.items()}
    links = [
        ("categories", "sub_categories", "bottom", "top"),
        ("sub_categories", "courses", "right", "left"),
        ("users", "courses", "right", "left"),
        ("courses", "course_sections", "right", "left"),
        ("course_sections", "course_lectures", "right", "left"),
        ("course_lectures", "quizzes", "bottom", "top"),
        ("quizzes", "quiz_questions", "bottom", "top"),
        ("users", "carts", "bottom", "top"),
        ("carts", "courses", "right", "left"),
        ("users", "enrollments", "bottom", "top"),
        ("courses", "enrollments", "bottom", "right"),
        ("enrollments", "course_progress", "right", "left"),
        ("course_progress", "lesson_progress", "right", "left"),
        ("course_lectures", "lesson_progress", "bottom", "right"),
        ("course_lectures", "lecture_notes", "left", "right"),
        ("course_lectures", "lecture_discussions", "left", "right"),
        ("users", "orders", "right", "left"),
        ("courses", "orders", "right", "left"),
        ("orders", "payments", "bottom", "top"),
    ]
    draw_all(d, tables, links)
    return im


def make_ai():
    im, d = base("AI TUTOR / CHATBOT")
    data = {
        "users": ("users", ["PK id", "name", "email", "role"], 105, 380, 190, "user"),
        "gemini_settings": ("gemini_settings", ["PK id", "model", "api_key", "base_url"], 105, 560, 210, "config"),
        "courses": ("courses", ["PK id", "FK user_id", "title"], 360, 375, 205, "course"),
        "transcript_jobs": ("transcript_jobs", ["PK id", "FK lecture_id", "status"], 360, 625, 205, "quiz"),
        "course_lectures": ("course_lectures", ["PK id", "FK course_id", "title", "transcript"], 635, 365, 225, "course"),
        "ai_documents": ("ai_documents", ["PK id", "FK course_id", "FK lecture_id", "source_type"], 635, 610, 225, "doc"),
        "ai_chat_sessions": ("ai_chat_sessions", ["PK id", "FK user_id", "FK course_id", "FK lecture_id"], 900, 365, 235, "ai"),
        "ai_document_chunks": ("ai_document_chunks", ["PK id", "FK document_id", "content", "embedding"], 900, 610, 235, "doc"),
        "ai_chat_messages": ("ai_chat_messages", ["PK id", "FK session_id", "role", "content"], 1190, 365, 235, "ai"),
        "ai_message_citations": ("ai_message_citations", ["PK id", "FK message_id", "FK chunk_id"], 1190, 610, 235, "doc"),
        "concepts": ("concepts", ["PK id", "name", "description"], 1400, 360, 110, "user"),
        "lesson_concepts": ("lesson_concepts", ["PK id", "FK lecture_id", "FK concept_id"], 1400, 520, 110, "user"),
        "document_concepts": ("document_concepts", ["PK id", "FK document_id", "FK concept_id"], 1400, 680, 110, "user"),
    }
    tables = {k: {"title": v[0], "rows": v[1], "x": v[2], "y": v[3], "w": v[4], "group": v[5], "h": 34 + 22 * len(v[1])} for k, v in data.items()}
    links = [
        ("users", "ai_chat_sessions", "right", "left"),
        ("courses", "course_lectures", "right", "left"),
        ("courses", "ai_chat_sessions", "right", "left"),
        ("course_lectures", "ai_chat_sessions", "right", "left"),
        ("ai_chat_sessions", "ai_chat_messages", "right", "left"),
        ("course_lectures", "transcript_jobs", "bottom", "right"),
        ("transcript_jobs", "ai_documents", "right", "left"),
        ("courses", "ai_documents", "bottom", "top"),
        ("course_lectures", "ai_documents", "bottom", "top"),
        ("ai_documents", "ai_document_chunks", "right", "left"),
        ("ai_document_chunks", "ai_message_citations", "right", "left"),
        ("ai_chat_messages", "ai_message_citations", "bottom", "top"),
        ("course_lectures", "lesson_concepts", "right", "left"),
        ("ai_documents", "document_concepts", "right", "left"),
        ("concepts", "lesson_concepts", "bottom", "top"),
        ("concepts", "document_concepts", "bottom", "top"),
    ]
    draw_all(d, tables, links)
    return im


out_dir = Path("outputs/manual-stacklearn-ppt")
out_dir.mkdir(parents=True, exist_ok=True)
lms_path = out_dir / "stacklearn_erd_lms_core_slide_v2.png"
ai_path = out_dir / "stacklearn_erd_ai_tutor_slide_v2.png"
make_lms().save(lms_path)
make_ai().save(ai_path)
print(lms_path.resolve())
print(ai_path.resolve())
