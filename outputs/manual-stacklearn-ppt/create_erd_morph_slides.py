from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (74, 88, 112)
LINE = (184, 198, 220)
TABLE_BORDER = (78, 103, 150)
HEADER = (235, 243, 255)
COLORS = {
    "blue": (232, 240, 255),
    "green": (232, 250, 241),
    "yellow": (255, 248, 225),
    "purple": (244, 239, 255),
    "red": (255, 239, 239),
    "cyan": (230, 248, 252),
}


def font(size, bold=False):
    candidates = []
    if bold:
        candidates += [
            r"C:\Windows\Fonts\arialbd.ttf",
            r"C:\Windows\Fonts\segoeuib.ttf",
        ]
    candidates += [
        r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeui.ttf",
        r"C:\Windows\Fonts\tahoma.ttf",
    ]
    for path in candidates:
        if Path(path).exists():
            return ImageFont.truetype(path, size)
    return ImageFont.load_default()


F13 = font(13)
F14 = font(14)
F16 = font(16)
F18 = font(18)
F20 = font(20)
F22 = font(22, True)
F24 = font(24, True)
F28 = font(28, True)
F50 = font(50, True)


def center_text(draw, box, text, fnt, fill=DARK):
    bbox = draw.textbbox((0, 0), text, font=fnt)
    tw, th = bbox[2] - bbox[0], bbox[3] - bbox[1]
    x1, y1, x2, y2 = box
    draw.text((x1 + (x2 - x1 - tw) / 2, y1 + (y2 - y1 - th) / 2), text, font=fnt, fill=fill)


def draw_header(draw, subtitle):
    draw.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
    draw.text((91, 82), "NTU", font=F20, fill=BLUE)
    draw.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
    draw.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)
    draw.text((72, 160), "SƠ ĐỒ ERD CƠ SỞ DỮ LIỆU", font=F50, fill=BLUE)
    draw.rounded_rectangle((72, 242, 760, 254), radius=3, fill=BLUE)
    draw.rounded_rectangle((1210, 170, 1460, 222), radius=24, fill=(235, 243, 255), outline=BLUE, width=2)
    center_text(draw, (1210, 170, 1460, 222), subtitle, F22, BLUE)
    draw.text((72, 278), "Các bảng chính được rút gọn từ ERD tổng thể để phù hợp trình bày.", font=F22, fill=DARK)


def table(draw, key, title, rows, x, y, w=250, color="blue"):
    row_h = 28
    h = 42 + row_h * len(rows)
    fill = COLORS[color]
    draw.rounded_rectangle((x, y, x + w, y + h), radius=8, fill=(255, 255, 255), outline=TABLE_BORDER, width=2)
    draw.rounded_rectangle((x, y, x + w, y + 40), radius=8, fill=fill, outline=TABLE_BORDER, width=2)
    draw.rectangle((x, y + 30, x + w, y + 42), fill=fill)
    center_text(draw, (x, y, x + w, y + 40), title, F18, BLUE)
    for i, r in enumerate(rows):
        yy = y + 42 + i * row_h
        draw.line((x, yy, x + w, yy), fill=(222, 230, 242), width=1)
        prefix = "PK  " if i == 0 else "FK  " if r.endswith("_id") or r in {"user_id", "course_id", "lecture_id", "quiz_id", "session_id", "document_id", "chunk_id"} else "    "
        draw.text((x + 12, yy + 6), prefix + r, font=F13, fill=DARK)
    return {"key": key, "x": x, "y": y, "w": w, "h": h}


def anchor(t, side):
    x, y, w, h = t["x"], t["y"], t["w"], t["h"]
    if side == "left":
        return (x, y + h / 2)
    if side == "right":
        return (x + w, y + h / 2)
    if side == "top":
        return (x + w / 2, y)
    if side == "bottom":
        return (x + w / 2, y + h)
    return (x + w / 2, y + h / 2)


def connect(draw, a, b, side_a="right", side_b="left"):
    p1 = anchor(a, side_a)
    p2 = anchor(b, side_b)
    mx = (p1[0] + p2[0]) / 2
    draw.line((p1[0], p1[1], mx, p1[1], mx, p2[1], p2[0], p2[1]), fill=(105, 122, 150), width=2)


def base_slide(subtitle):
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    draw_header(d, subtitle)
    d.rounded_rectangle((72, 330, 1528, 805), radius=18, fill=(255, 255, 255), outline=LINE, width=2)
    d.text((72, 840), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    return im, d


def lms_slide():
    im, d = base_slide("LMS CORE")
    tables = {}
    tables["users"] = table(d, "users", "users", ["id", "name", "email", "role"], 105, 380, 210, "blue")
    tables["category"] = table(d, "category", "categories", ["id", "name"], 360, 360, 220, "green")
    tables["sub"] = table(d, "sub", "sub_categories", ["id", "category_id", "name"], 360, 475, 220, "green")
    tables["courses"] = table(d, "courses", "courses", ["id", "user_id", "category_id", "title", "price"], 635, 360, 245, "yellow")
    tables["sections"] = table(d, "sections", "course_sections", ["id", "course_id", "title"], 930, 350, 245, "yellow")
    tables["lectures"] = table(d, "lectures", "course_lectures", ["id", "course_id", "section_id", "title", "video"], 1210, 350, 250, "yellow")
    tables["enrollments"] = table(d, "enrollments", "enrollments", ["id", "user_id", "course_id", "status"], 105, 610, 230, "purple")
    tables["progress"] = table(d, "progress", "course_progress", ["id", "enrollment_id", "percent"], 380, 620, 230, "purple")
    tables["lesson_progress"] = table(d, "lesson_progress", "lesson_progress", ["id", "enrollment_id", "lecture_id"], 655, 620, 230, "purple")
    tables["quiz"] = table(d, "quiz", "quizzes", ["id", "lecture_id", "title"], 930, 585, 220, "cyan")
    tables["questions"] = table(d, "questions", "quiz_questions", ["id", "quiz_id", "question"], 1210, 575, 245, "cyan")
    tables["cart"] = table(d, "cart", "carts", ["id", "user_id", "course_id"], 105, 485, 210, "red")
    tables["orders"] = table(d, "orders", "orders", ["id", "user_id", "course_id", "payment_id"], 930, 705, 220, "red")
    tables["payments"] = table(d, "payments", "payments", ["id", "amount", "method", "status"], 1210, 705, 245, "red")
    tables["notes"] = table(d, "notes", "lecture_notes", ["id", "user_id", "lecture_id"], 655, 455, 230, "blue")
    tables["discussions"] = table(d, "discussions", "lecture_discussions", ["id", "user_id", "lecture_id"], 655, 510, 230, "blue")

    connect(d, tables["category"], tables["sub"], "bottom", "top")
    connect(d, tables["sub"], tables["courses"])
    connect(d, tables["users"], tables["courses"])
    connect(d, tables["courses"], tables["sections"])
    connect(d, tables["sections"], tables["lectures"])
    connect(d, tables["users"], tables["enrollments"], "bottom", "left")
    connect(d, tables["courses"], tables["enrollments"], "bottom", "top")
    connect(d, tables["enrollments"], tables["progress"])
    connect(d, tables["progress"], tables["lesson_progress"])
    connect(d, tables["lectures"], tables["lesson_progress"], "bottom", "right")
    connect(d, tables["lectures"], tables["quiz"], "bottom", "top")
    connect(d, tables["quiz"], tables["questions"])
    connect(d, tables["users"], tables["cart"], "bottom", "left")
    connect(d, tables["cart"], tables["courses"], "right", "bottom")
    connect(d, tables["orders"], tables["payments"])
    connect(d, tables["lectures"], tables["notes"], "left", "right")
    connect(d, tables["lectures"], tables["discussions"], "left", "right")

    # redraw tables above connectors
    return im


def ai_slide():
    im, d = base_slide("AI TUTOR / CHATBOT")
    tables = {}
    tables["users"] = table(d, "users", "users", ["id", "name", "email", "role"], 105, 380, 210, "blue")
    tables["courses"] = table(d, "courses", "courses", ["id", "user_id", "title"], 360, 360, 220, "yellow")
    tables["lectures"] = table(d, "lectures", "course_lectures", ["id", "course_id", "title", "transcript"], 635, 350, 245, "yellow")
    tables["sessions"] = table(d, "sessions", "ai_chat_sessions", ["id", "user_id", "course_id", "lecture_id"], 930, 350, 250, "purple")
    tables["messages"] = table(d, "messages", "ai_chat_messages", ["id", "session_id", "role", "content"], 1210, 350, 250, "purple")
    tables["docs"] = table(d, "docs", "ai_documents", ["id", "course_id", "lecture_id", "source_type"], 360, 585, 240, "green")
    tables["chunks"] = table(d, "chunks", "ai_document_chunks", ["id", "document_id", "content", "embedding"], 635, 585, 250, "green")
    tables["citations"] = table(d, "citations", "ai_message_citations", ["id", "message_id", "chunk_id"], 930, 610, 250, "green")
    tables["transcripts"] = table(d, "transcripts", "transcript_jobs", ["id", "lecture_id", "status"], 105, 610, 220, "cyan")
    tables["concepts"] = table(d, "concepts", "concepts", ["id", "name", "description"], 1210, 575, 235, "blue")
    tables["lesson_concepts"] = table(d, "lesson_concepts", "lesson_concepts", ["id", "lecture_id", "concept_id"], 1210, 680, 235, "blue")
    tables["doc_concepts"] = table(d, "doc_concepts", "document_concepts", ["id", "document_id", "concept_id"], 930, 720, 250, "blue")
    tables["settings"] = table(d, "settings", "gemini_settings", ["id", "model", "api_key", "base_url"], 105, 485, 220, "red")

    connect(d, tables["users"], tables["sessions"])
    connect(d, tables["courses"], tables["lectures"])
    connect(d, tables["lectures"], tables["sessions"])
    connect(d, tables["sessions"], tables["messages"])
    connect(d, tables["courses"], tables["docs"], "bottom", "top")
    connect(d, tables["lectures"], tables["docs"], "bottom", "top")
    connect(d, tables["docs"], tables["chunks"])
    connect(d, tables["messages"], tables["citations"], "bottom", "top")
    connect(d, tables["chunks"], tables["citations"])
    connect(d, tables["lectures"], tables["transcripts"], "left", "right")
    connect(d, tables["transcripts"], tables["docs"])
    connect(d, tables["lectures"], tables["lesson_concepts"], "right", "left")
    connect(d, tables["docs"], tables["doc_concepts"], "right", "left")
    connect(d, tables["concepts"], tables["lesson_concepts"], "bottom", "top")
    connect(d, tables["concepts"], tables["doc_concepts"], "left", "right")

    return im


out_dir = Path("outputs/manual-stacklearn-ppt")
out_dir.mkdir(parents=True, exist_ok=True)
lms = lms_slide()
ai = ai_slide()
lms_path = out_dir / "stacklearn_erd_lms_core_slide.png"
ai_path = out_dir / "stacklearn_erd_ai_tutor_slide.png"
lms.save(lms_path)
ai.save(ai_path)
print(lms_path.resolve())
print(ai_path.resolve())
