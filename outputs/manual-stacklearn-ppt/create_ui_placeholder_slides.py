from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (235, 243, 255)


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


def header(draw, title, badge):
    draw.ellipse((72, 58, 140, 126), outline=BLUE, width=3)
    draw.text((91, 82), "NTU", font=F20, fill=BLUE)
    draw.text((162, 80), "STACKLEARN", font=F28, fill=BLUE)
    draw.text((1235, 88), "Đồ án tốt nghiệp", font=F24, fill=BLUE)
    draw.text((72, 152), title, font=F46, fill=BLUE)
    draw.rounded_rectangle((72, 226, 560, 238), radius=3, fill=BLUE)
    draw.rounded_rectangle((1175, 165, 1460, 216), radius=24, fill=SOFT, outline=BLUE, width=2)
    center(draw, (1175, 165, 1460, 216), badge, F22, BLUE)


def bullet(draw, x, y, title, desc):
    draw.ellipse((x, y + 5, x + 28, y + 33), fill=BLUE)
    center(draw, (x, y + 5, x + 28, y + 33), "•", F24, (255, 255, 255))
    draw.text((x + 44, y), title, font=F22, fill=BLUE)
    paragraph(draw, x + 44, y + 29, desc, F18, 430, MID, 24)


def image_placeholder(draw, x, y, w, h, label):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=16, fill=(255, 255, 255), outline=LINE, width=2)
    inner = (x + 18, y + 18, x + w - 18, y + h - 56)
    draw.rounded_rectangle(inner, radius=10, fill=(252, 254, 255), outline=(145, 166, 205), width=2)
    ix1, iy1, ix2, iy2 = inner
    for off in range(-int(iy2 - iy1), int(ix2 - ix1), 24):
        draw.line((ix1 + off, iy2, ix1 + off + (iy2 - iy1), iy1), fill=(230, 237, 248), width=1)
    center(draw, inner, "CHÈN ẢNH GIAO DIỆN", F18, BLUE)
    center(draw, (x + 10, y + h - 42, x + w - 10, y + h - 12), label, F20, DARK)


def make_slide(file_name, title, badge, description, bullets, placeholders, layout="grid4"):
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, title, badge)
    paragraph(d, 72, 275, description, F22, 520, DARK, 30)
    y = 395
    for t, desc in bullets:
        bullet(d, 82, y, t, desc)
        y += 105

    if layout == "grid4":
        coords = [(650, 290), (1090, 290), (650, 555), (1090, 555)]
        size = (390, 210)
    elif layout == "main3":
        image_placeholder(d, 640, 285, 565, 310, placeholders[0])
        image_placeholder(d, 1230, 285, 270, 210, placeholders[1])
        image_placeholder(d, 1230, 540, 270, 210, placeholders[2])
        coords, size = [], (0, 0)
    else:
        coords, size = [], (0, 0)

    if layout == "grid4":
        for (x, y), label in zip(coords, placeholders):
            image_placeholder(d, x, y, *size, label)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt") / file_name
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


make_slide(
    "stacklearn_ui_student_slide.png",
    "GIAO DIỆN NGƯỜI HỌC",
    "STUDENT UI",
    "Các màn hình chính thể hiện luồng học viên: tìm khóa học, ghi danh, thanh toán và học bài trên hệ thống.",
    [
        ("Khám phá khóa học", "Trang chủ, danh sách và chi tiết khóa học."),
        ("Ghi danh & thanh toán", "Giỏ hàng, mã giảm giá và checkout."),
        ("Học tập trực tuyến", "Xem bài học, làm quiz, theo dõi tiến độ."),
    ],
    ["Trang chủ", "Chi tiết khóa học", "Thanh toán", "Trang học bài"],
)

make_slide(
    "stacklearn_ui_ai_tutor_slide.png",
    "GIAO DIỆN AI TUTOR",
    "AI TUTOR UI",
    "AI Tutor là điểm khác biệt của StackLearn, hỗ trợ học viên hỏi đáp theo ngữ cảnh bài học, transcript và lịch sử hội thoại.",
    [
        ("Hỏi đáp trong bài học", "Widget AI Tutor nằm trong màn hình học."),
        ("Trả lời theo ngữ cảnh", "Phản hồi dựa trên khóa học, bài giảng, tài liệu."),
        ("Lưu lịch sử chat", "Học viên có thể xem lại phiên hội thoại."),
    ],
    ["AI Tutor trong trang học", "Kết quả phản hồi", "Lịch sử hội thoại"],
    layout="main3",
)

make_slide(
    "stacklearn_ui_instructor_slide.png",
    "GIAO DIỆN GIẢNG VIÊN",
    "INSTRUCTOR UI",
    "Khu vực giảng viên tập trung vào xây dựng khóa học, quản lý bài giảng, quiz, thảo luận và theo dõi doanh thu.",
    [
        ("Quản lý nội dung", "Tạo khóa học, section, lecture và tài liệu."),
        ("Đánh giá học tập", "Tạo quiz và theo dõi kết quả học viên."),
        ("Doanh thu", "Theo dõi đơn hàng, doanh thu và yêu cầu rút tiền."),
    ],
    ["Dashboard giảng viên", "Quản lý khóa học", "Bài giảng / section", "Quiz / doanh thu"],
)

make_slide(
    "stacklearn_ui_admin_slide.png",
    "GIAO DIỆN QUẢN TRỊ",
    "ADMIN UI",
    "Khu vực quản trị hỗ trợ vận hành hệ thống: người dùng, duyệt khóa học, thanh toán, kiểm duyệt và theo dõi sức khỏe hệ thống.",
    [
        ("Quản lý hệ thống", "User, instructor, category và cấu hình."),
        ("Kiểm duyệt nội dung", "Duyệt khóa học, report, refund, payout."),
        ("Theo dõi vận hành", "Analytics, audit log và system health."),
    ],
    ["Dashboard admin", "Duyệt khóa học", "Learning analytics", "System health / moderation"],
)
