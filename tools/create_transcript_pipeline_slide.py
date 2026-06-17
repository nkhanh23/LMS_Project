from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "canva" / "transcript_video_pipeline.png"
W, H = 1920, 1080

BG = "#F8FAFF"
NAVY = "#16233F"
BLUE = "#2C58C9"
TEAL = "#168A8C"
PURPLE = "#7353C7"
GREEN = "#0E9F6E"
RED = "#E84545"
GRAY = "#5F6778"
LINE = "#C5D7FF"
LANE_UI = "#EEF5FF"
LANE_APP = "#ECFBFB"
LANE_PROCESS = "#F3EFFF"
LANE_DB = "#ECFBF4"


def load_font(kind: str, size: int):
    font_dir = Path(r"C:\Windows\Fonts")
    names = {
        "bold": ["arialbd.ttf"],
        "regular": ["arial.ttf"],
    }[kind]
    for name in names:
        path = font_dir / name
        if path.exists():
            return ImageFont.truetype(str(path), size)
    return ImageFont.load_default()


F_TITLE = load_font("bold", 48)
F_SUB = load_font("regular", 24)
F_LANE = load_font("bold", 24)
F_CARD_TITLE = load_font("bold", 24)
F_CARD_BODY = load_font("regular", 19)
F_NO = load_font("bold", 18)
F_SMALL = load_font("regular", 16)


img = Image.new("RGB", (W, H), BG)
draw = ImageDraw.Draw(img)


def wrap_text(text, font, max_width):
    words = text.split()
    lines = []
    cur = ""
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
    size = 14
    if abs(x2 - x1) >= abs(y2 - y1):
        pts = [(x2, y2), (x2 - size if x2 >= x1 else x2 + size, y2 - 8), (x2 - size if x2 >= x1 else x2 + size, y2 + 8)]
    else:
        pts = [(x2, y2), (x2 - 8, y2 - size if y2 >= y1 else y2 + size), (x2 + 8, y2 - size if y2 >= y1 else y2 + size)]
    draw.polygon(pts, fill=color)


def card(x, y, w, h, no, title, body, accent=BLUE, fill="#FFFFFF"):
    shadow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    sd = ImageDraw.Draw(shadow)
    sd.rounded_rectangle((x + 5, y + 6, x + w + 5, y + h + 6), radius=18, fill=(32, 64, 128, 16))
    global img, draw
    img = Image.alpha_composite(img.convert("RGBA"), shadow).convert("RGB")
    draw = ImageDraw.Draw(img)
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=fill, outline=accent, width=2)
    draw.ellipse((x + 18, y + 18, x + 52, y + 52), fill=accent)
    center(no, (x + 18, y + 18, x + 52, y + 52), F_NO, "white")
    draw.text((x + 66, y + 16), title, font=F_CARD_TITLE, fill=NAVY)
    draw.text((x + 66, y + 52), wrap_text(body, F_CARD_BODY, w - 86), font=F_CARD_BODY, fill=GRAY, spacing=5)


# Header
draw.ellipse((-60, -60, 90, 90), fill="#EAF1FF")
draw.ellipse((1810, 50, 1870, 110), fill="#EAF1FF")
center("CƠ CHẾ TẠO TRANSCRIPT VIDEO", (120, 30, 1800, 86), F_TITLE, NAVY)
center("Luồng chính từ yêu cầu của Instructor đến transcript được lưu và index cho AI Tutor", (160, 88, 1760, 122), F_SUB, GRAY)
draw.line((690, 130, 1230, 130), fill=LINE, width=3)

# Lanes
lanes = [
    ("UI Instructor", 175, LANE_UI, BLUE),
    ("Controller / Queue", 175, LANE_APP, TEAL),
    ("FFmpeg + AI Processing", 210, LANE_PROCESS, PURPLE),
    ("Database + Index", 160, LANE_DB, GREEN),
]

left = 52
label_w = 210
right = 1868
y = 160
lane_boxes = []
for label, h, fill, accent in lanes:
    draw.rounded_rectangle((left, y, right, y + h), radius=12, fill=fill, outline=accent, width=2)
    draw.line((left + label_w, y, left + label_w, y + h), fill=accent, width=1)
    center(label, (left, y, left + label_w, y + h), F_LANE, accent)
    lane_boxes.append((y, y + h))
    y += h + 18

# Lane 1
y1 = lane_boxes[0][0] + 26
card(285, y1, 300, 120, "1", "Instructor tạo yêu cầu", "Mở bài học và bấm Generate Transcript.", BLUE)
card(675, y1, 350, 120, "2", "Kiểm tra loại lecture", "Chỉ cho phép nếu bài học là video hoặc r2_video.", BLUE)
card(1115, y1, 300, 120, "3", "Hiển thị quản lý", "Mở giao diện quản lý transcript cho giảng viên.", BLUE)
card(1505, y1, 270, 120, "4", "Gửi yêu cầu", "Gửi request tạo transcript lên server.", BLUE)
arrow(585, y1 + 60, 675, y1 + 60)
arrow(1025, y1 + 60, 1115, y1 + 60)
arrow(1415, y1 + 60, 1505, y1 + 60)

# Lane 2
y2 = lane_boxes[1][0] + 26
card(285, y2, 320, 120, "5", "Authorize + Validate", "Kiểm tra instructor sở hữu course và lecture hợp lệ.", TEAL)
card(715, y2, 320, 120, "6", "Tạo transcript job", "Lưu status = queued, progress = 0.", TEAL)
card(1145, y2, 300, 120, "7", "Dispatch queue job", "Đưa GenerateTranscriptJob vào queue worker.", TEAL)
card(1535, y2, 250, 120, "ERR", "Nếu lỗi request", "Trả về invalid type hoặc lỗi quyền truy cập.", RED, "#FFF5F5")
arrow(605, y2 + 60, 715, y2 + 60, TEAL)
arrow(1035, y2 + 60, 1145, y2 + 60, TEAL)

# Lane 3
y3 = lane_boxes[2][0] + 30
card(285, y3, 280, 130, "8", "Worker xử lý", "markProcessing, chọn OpenAI Whisper hoặc Local Whisper.", PURPLE)
card(655, y3, 300, 130, "9", "Tải video + tách audio", "Download video, dùng FFmpeg tạo file audio MP3.", PURPLE)
card(1045, y3, 320, 130, "10", "Split nếu audio lớn", "Nếu audio vượt ngưỡng thì cắt thành nhiều chunk.", PURPLE)
card(1455, y3, 310, 130, "11", "AI transcribe + clean", "Whisper transcribe từng chunk, sau đó ghép và clean transcript.", PURPLE)
arrow(565, y3 + 65, 655, y3 + 65, PURPLE)
arrow(955, y3 + 65, 1045, y3 + 65, PURPLE)
arrow(1365, y3 + 65, 1455, y3 + 65, PURPLE)

error_x = 1410
error_y = y3 + 150
draw.rounded_rectangle((error_x, error_y, error_x + 290, error_y + 64), radius=16, fill="#FFF4F4", outline=RED, width=2)
center("Nếu lỗi provider: markFailed + lưu error_message", (error_x + 14, error_y + 10, error_x + 276, error_y + 54), F_SMALL, RED)
arrow(1610, y3 + 130, 1610, error_y, RED, 3)

# Lane 4
y4 = lane_boxes[3][0] + 20
card(285, y4, 320, 112, "12", "Tạo AiDocument", "source_type = transcript, index_status = pending.", GREEN)
card(715, y4, 320, 112, "13", "Chunk + Embedding", "Dispatch index job, tách chunk và tạo vector index.", GREEN)
card(1145, y4, 280, 112, "14", "markDone", "status = done, progress = 100.", GREEN)
card(1515, y4, 270, 112, "15", "Instructor quản lý", "Xem, sửa hoặc re-index transcript.", GREEN)
arrow(605, y4 + 56, 715, y4 + 56, GREEN)
arrow(1035, y4 + 56, 1145, y4 + 56, GREEN)
arrow(1425, y4 + 56, 1515, y4 + 56, GREEN)

# Cross-lane arrows
arrow(1640, y1 + 120, 1640, lane_boxes[1][0] + 24, BLUE, 3)
arrow(1295, y2 + 120, 1295, lane_boxes[2][0] + 24, TEAL, 3)
arrow(1610, y3 + 130, 1610, lane_boxes[3][0] + 20, GREEN, 3)

# Legend
legend_y = 1020
draw.rounded_rectangle((420, 992, 1500, 1054), radius=14, fill="#FFFFFF", outline=LINE, width=2)
items = [
    (470, BLUE, "Luồng người dùng"),
    (730, TEAL, "Controller / Queue"),
    (1010, PURPLE, "FFmpeg / Whisper"),
    (1280, GREEN, "Database / Index"),
]
for x, color, label in items:
    arrow(x, legend_y, x + 46, legend_y, color, 3)
    draw.text((x + 58, legend_y - 10), label, font=F_SMALL, fill=GRAY)

img.save(OUT, quality=95)
print(OUT)
