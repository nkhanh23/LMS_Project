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
F34 = font(34, True)
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
    draw.text((72, 150), title, font=F46, fill=BLUE)
    draw.rounded_rectangle((72, 222, 520, 234), radius=3, fill=BLUE)
    draw.rounded_rectangle((1175, 165, 1460, 216), radius=24, fill=SOFT, outline=BLUE, width=2)
    center(draw, (1175, 165, 1460, 216), badge, F22, BLUE)


def nav_item(draw, x, y, num, title, active=False):
    color = BLUE if active else LINE
    fill = SOFT if active else WHITE
    draw.rounded_rectangle((x, y, x + 250, y + 78), radius=14, fill=fill, outline=color, width=2)
    draw.ellipse((x + 18, y + 17, x + 62, y + 61), fill=BLUE if active else (232, 239, 252))
    center(draw, (x + 18, y + 17, x + 62, y + 61), num, F18, WHITE if active else BLUE)
    paragraph(draw, x + 78, y + 18, title, F18, 150, BLUE if active else MID, 23)


def flow_card(draw, x, y, w, h, num, title, desc, color):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=WHITE, outline=LINE, width=2)
    draw.ellipse((x + w / 2 - 38, y - 42, x + w / 2 + 38, y + 34), fill=SOFT, outline=color, width=3)
    center(draw, (x + w / 2 - 38, y - 42, x + w / 2 + 38, y + 34), num, F24, color)
    center(draw, (x + 18, y + 34, x + w - 18, y + 70), title, F22, BLUE)
    paragraph(draw, x + 24, y + 86, desc, F18, w - 48, MID, 24)
    draw.rounded_rectangle((x + w / 2 - 45, y + h + 18, x + w / 2 + 45, y + h + 55), radius=18, fill=color)
    center(draw, (x + w / 2 - 45, y + h + 18, x + w / 2 + 45, y + h + 55), num, F18, WHITE)


def arrow(draw, x1, y1, x2, y2):
    draw.line((x1, y1, x2, y2), fill=BLUE, width=3)
    draw.polygon([(x2, y2), (x2 - 12, y2 - 7), (x2 - 12, y2 + 7)], fill=BLUE)


def make_overview():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KẾT LUẬN", "CONCLUSION")

    navs = [
        ("01", "Kết quả đạt được"),
        ("02", "Ưu điểm hệ thống"),
        ("03", "Hạn chế / thách thức"),
        ("04", "Hướng phát triển"),
    ]
    for i, item in enumerate(navs):
        nav_item(d, 72, 295 + i * 98, *item, active=False)

    paragraph(
        d,
        380,
        275,
        "Phần kết luận tổng hợp những gì StackLearn đã hoàn thành, điểm mạnh của hệ thống, các hạn chế còn tồn tại và định hướng phát triển trong tương lai.",
        F22,
        980,
        DARK,
        31,
    )

    cards = [
        ("01", "Kết quả đạt được", "Hoàn thành LMS với ba vai trò chính và AI Tutor.", GREEN),
        ("02", "Ưu điểm hệ thống", "Kết hợp học tập, thanh toán, tiến độ và hỏi đáp AI.", BLUE),
        ("03", "Hạn chế / thách thức", "Cần tối ưu giao diện, dữ liệu AI và kiểm thử tải.", ORANGE),
        ("04", "Hướng phát triển", "Mở rộng tính năng, RAG, bảo mật và hiệu năng.", RED),
    ]

    xs = [390, 660, 930, 1200]
    y = 485
    for idx, (x, card_data) in enumerate(zip(xs, cards)):
        flow_card(d, x, y, 230, 175, *card_data)
        if idx < 3:
            arrow(d, x + 235, y + 75, xs[idx + 1] - 18, y + 75)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_01_overview_morph.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


def achievement_card(draw, x, y, w, h, num, title, desc, color):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=WHITE, outline=LINE, width=2)
    draw.ellipse((x + 24, y + 22, x + 78, y + 76), fill=SOFT, outline=color, width=3)
    center(draw, (x + 24, y + 22, x + 78, y + 76), num, F20, color)
    draw.text((x + 96, y + 26), title, font=F22, fill=BLUE)
    paragraph(draw, x + 96, y + 62, desc, F18, w - 120, MID, 24)


def make_achievement():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KẾT LUẬN", "ACHIEVEMENT")

    navs = [
        ("01", "Kết quả đạt được"),
        ("02", "Ưu điểm hệ thống"),
        ("03", "Hạn chế / thách thức"),
        ("04", "Hướng phát triển"),
    ]
    for i, item in enumerate(navs):
        nav_item(d, 72, 295 + i * 98, *item, active=(i == 0))

    d.text((380, 278), "KẾT QUẢ ĐẠT ĐƯỢC", font=F34, fill=BLUE)
    paragraph(
        d,
        380,
        330,
        "StackLearn đã đáp ứng mục tiêu xây dựng một nền tảng LMS có thể vận hành với các chức năng học tập, quản lý nội dung, thanh toán và hỗ trợ học tập bằng AI.",
        F22,
        980,
        DARK,
        31,
    )

    items = [
        ("01", "LMS với 3 vai trò", "Học viên, giảng viên và quản trị viên có khu vực chức năng riêng.", GREEN),
        ("02", "Học tập trực tuyến", "Khóa học, bài giảng, quiz, ghi chú, thảo luận và tiến độ học tập.", BLUE),
        ("03", "Thương mại học tập", "Ghi danh, giỏ hàng, thanh toán, đơn hàng và quản lý doanh thu.", ORANGE),
        ("04", "AI Tutor", "Tích hợp Gemini API, hỏi đáp theo ngữ cảnh bài học và lưu lịch sử chat.", RED),
    ]
    positions = [(390, 455), (930, 455), (390, 625), (930, 625)]
    for (x, y), item in zip(positions, items):
        achievement_card(d, x, y, 500, 130, *item)

    d.rounded_rectangle((380, 795, 1430, 830), radius=18, fill=SOFT, outline=BLUE, width=2)
    center(d, (380, 795, 1430, 830), "StackLearn hoàn thành mục tiêu: LMS đầy đủ chức năng + AI Tutor hỗ trợ học tập theo ngữ cảnh", F18, BLUE)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_02_achievement_morph.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


def advantage_panel(draw, x, y, w, h, title, desc, color):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=WHITE, outline=LINE, width=2)
    draw.rounded_rectangle((x + 22, y + 22, x + 84, y + 84), radius=12, fill=SOFT, outline=color, width=2)
    center(draw, (x + 22, y + 22, x + 84, y + 84), "✓", F28, color)
    draw.text((x + 105, y + 24), title, font=F22, fill=BLUE)
    paragraph(draw, x + 105, y + 62, desc, F18, w - 130, MID, 24)


def make_advantages():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KẾT LUẬN", "ADVANTAGES")

    navs = [
        ("01", "Kết quả đạt được"),
        ("02", "Ưu điểm hệ thống"),
        ("03", "Hạn chế / thách thức"),
        ("04", "Hướng phát triển"),
    ]
    for i, item in enumerate(navs):
        nav_item(d, 72, 295 + i * 98, *item, active=(i == 1))

    d.text((380, 278), "ƯU ĐIỂM CỦA STACKLEARN", font=F34, fill=BLUE)
    paragraph(
        d,
        380,
        330,
        "StackLearn không chỉ quản lý khóa học như LMS truyền thống, mà còn tích hợp AI Tutor trực tiếp trong trải nghiệm học tập để hỗ trợ người học theo ngữ cảnh.",
        F22,
        980,
        DARK,
        31,
    )

    # Central emphasis block inspired by the Advantages sample.
    d.rounded_rectangle((610, 445, 1200, 640), radius=26, fill=SOFT, outline=BLUE, width=2)
    center(d, (610, 470, 1200, 520), "LMS + AI TUTOR", F34, BLUE)
    paragraph(
        d,
        690,
        535,
        "Một nền tảng thống nhất cho học tập, quản lý nội dung, thanh toán và hỏi đáp thông minh.",
        F20,
        430,
        DARK,
        27,
    )

    panels = [
        (390, 430, 330, 130, "Nền tảng thống nhất", "LMS và AI Tutor nằm trong cùng một hệ thống.", GREEN),
        (1080, 430, 330, 130, "Trả lời theo ngữ cảnh", "Dựa trên bài học, transcript, tài liệu và lịch sử chat.", BLUE),
        (390, 650, 330, 130, "Nhiều vai trò", "Hỗ trợ học viên, giảng viên và quản trị viên.", ORANGE),
        (1080, 650, 330, 130, "Dễ mở rộng", "Queue, storage và API AI giúp mở rộng về sau.", RED),
    ]
    for panel in panels:
        advantage_panel(d, *panel)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_03_advantages_morph.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


def challenge_list_card(draw, x, y, w, h, title, items, color, muted=False):
    fill = (252, 254, 255) if not muted else (246, 248, 252)
    outline = color if not muted else LINE
    draw.rounded_rectangle((x, y, x + w, y + h), radius=22, fill=fill, outline=outline, width=2)
    draw.rounded_rectangle((x + 28, y + 24, x + 92, y + 88), radius=14, fill=SOFT, outline=outline, width=2)
    symbol = "!" if not muted else "✓"
    center(draw, (x + 28, y + 24, x + 92, y + 88), symbol, F28, color if not muted else MID)
    draw.text((x + 116, y + 35), title, font=F28, fill=color if not muted else MID)
    yy = y + 118
    for item in items:
        draw.ellipse((x + 36, yy + 8, x + 48, yy + 20), fill=color if not muted else LINE)
        paragraph(draw, x + 66, yy, item, F18, w - 100, DARK if not muted else MID, 24)
        yy += 62


def make_challenges():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KẾT LUẬN", "CHALLENGES")

    navs = [
        ("01", "Kết quả đạt được"),
        ("02", "Ưu điểm hệ thống"),
        ("03", "Hạn chế / thách thức"),
        ("04", "Hướng phát triển"),
    ]
    for i, item in enumerate(navs):
        nav_item(d, 72, 295 + i * 98, *item, active=(i == 2))

    d.text((380, 278), "HẠN CHẾ / THÁCH THỨC", font=F34, fill=BLUE)
    paragraph(
        d,
        380,
        330,
        "Bên cạnh các kết quả đạt được, StackLearn vẫn cần tiếp tục cải thiện về trải nghiệm quản trị, chất lượng dữ liệu AI, phạm vi kiểm thử và khả năng vận hành khi mở rộng.",
        F22,
        980,
        DARK,
        31,
    )

    advantage_items = [
        "LMS và AI Tutor đã hoạt động trong cùng nền tảng.",
        "Các luồng học tập, thanh toán và hỏi đáp AI đã hoàn thành.",
    ]
    challenge_items = [
        "Một số giao diện quản trị và giảng viên còn nhiều dữ liệu.",
        "AI Tutor phụ thuộc transcript, tài liệu học tập và Gemini API.",
        "Kiểm thử chủ yếu ở môi trường cục bộ.",
        "Cần cải thiện hiệu năng khi nhiều người dùng đồng thời.",
    ]

    challenge_list_card(d, 390, 455, 360, 280, "Advantages", advantage_items, GREEN, muted=True)
    d.ellipse((790, 552, 865, 627), fill=SOFT, outline=BLUE, width=2)
    center(d, (790, 552, 865, 627), "VS", F24, BLUE)
    challenge_list_card(d, 910, 420, 500, 360, "Challenges", challenge_items, RED, muted=False)

    d.rounded_rectangle((390, 795, 1410, 830), radius=18, fill=SOFT, outline=BLUE, width=2)
    center(d, (390, 795, 1410, 830), "Các hạn chế này là cơ sở để xác định hướng phát triển tiếp theo của StackLearn", F18, BLUE)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_04_challenges_morph.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


def future_branch(draw, x, y, w, h, num, title, desc, color, icon_text):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=WHITE, outline=LINE, width=2)
    draw.ellipse((x + w / 2 - 34, y - 38, x + w / 2 + 34, y + 30), fill=SOFT, outline=color, width=3)
    center(draw, (x + w / 2 - 34, y - 38, x + w / 2 + 34, y + 30), icon_text, F22, color)
    draw.rounded_rectangle((x + w / 2 - 45, y + 22, x + w / 2 + 45, y + 58), radius=18, fill=color)
    center(draw, (x + w / 2 - 45, y + 22, x + w / 2 + 45, y + 58), num, F18, WHITE)
    paragraph(draw, x + 24, y + 78, title, F20, w - 48, BLUE, 24)
    paragraph(draw, x + 24, y + 132, desc, F16, w - 48, MID, 21)


def dashed_arrow(draw, x1, y1, x2, y2):
    dash = 12
    gap = 8
    total = x2 - x1
    cur = x1
    while cur < x2:
        end = min(cur + dash, x2)
        draw.line((cur, y1, end, y2), fill=BLUE, width=2)
        cur += dash + gap
    draw.polygon([(x2, y2), (x2 - 12, y2 - 7), (x2 - 12, y2 + 7)], fill=BLUE)


def make_future():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KẾT LUẬN", "FUTURE")

    navs = [
        ("01", "Kết quả đạt được"),
        ("02", "Ưu điểm hệ thống"),
        ("03", "Hạn chế / thách thức"),
        ("04", "Hướng phát triển"),
    ]
    for i, item in enumerate(navs):
        nav_item(d, 72, 295 + i * 98, *item, active=(i == 3))

    d.text((380, 278), "HƯỚNG PHÁT TRIỂN", font=F34, fill=BLUE)
    paragraph(
        d,
        380,
        330,
        "Trong tương lai, StackLearn có thể tiếp tục mở rộng theo hướng nâng cao trải nghiệm học tập, cải thiện chất lượng AI Tutor và tăng độ ổn định khi triển khai thực tế.",
        F22,
        980,
        DARK,
        31,
    )

    branches = [
        ("01", "Mở rộng tính năng", "Chứng chỉ, lộ trình học và gợi ý khóa học.", GREEN, "+"),
        ("02", "Cải thiện UX", "Tối ưu giao diện quản trị và giảng viên.", BLUE, "UI"),
        ("03", "Công nghệ mới", "Tối ưu RAG, transcript, tài liệu, trích dẫn nguồn và AI API.", ORANGE, "AI"),
        ("04", "Hiệu năng & mở rộng", "Bảo mật, caching, queue và kiểm thử tải.", RED, "↗"),
    ]
    xs = [390, 660, 930, 1200]
    ys = [500, 585, 500, 585]
    for i, (x, y, data) in enumerate(zip(xs, ys, branches)):
        future_branch(d, x, y, 230, 185, *data)
        if i < 3:
            dashed_arrow(d, x + 235, y + 85, xs[i + 1] - 18, ys[i + 1] + 85)

    d.rounded_rectangle((380, 795, 1430, 830), radius=18, fill=SOFT, outline=BLUE, width=2)
    center(d, (380, 795, 1430, 830), "Mục tiêu dài hạn: hoàn thiện StackLearn thành nền tảng học trực tuyến có tính ứng dụng cao", F18, BLUE)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_05_future_morph.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


def experience_item(draw, x, y, w, h, title, desc, color, icon_text):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=24, fill=WHITE, outline=LINE, width=2)
    draw.rounded_rectangle((x + w - 82, y + 16, x + w - 24, y + 74), radius=16, fill=SOFT, outline=color, width=2)
    center(draw, (x + w - 82, y + 16, x + w - 24, y + 74), icon_text, F22, color)
    draw.text((x + 26, y + 22), title, font=F22, fill=BLUE)
    paragraph(draw, x + 26, y + 58, desc, F18, w - 128, MID, 24)


def make_experience():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KẾT LUẬN", "EXPERIENCE")

    navs = [
        ("01", "Kết quả đạt được"),
        ("02", "Ưu điểm hệ thống"),
        ("03", "Hạn chế / thách thức"),
        ("04", "Hướng phát triển"),
    ]
    for i, item in enumerate(navs):
        nav_item(d, 72, 295 + i * 98, *item, active=False)

    d.text((380, 278), "KINH NGHIỆM ĐẠT ĐƯỢC", font=F34, fill=BLUE)
    paragraph(
        d,
        380,
        330,
        "Quá trình thực hiện StackLearn giúp củng cố kinh nghiệm phân tích, xây dựng và tích hợp một hệ thống LMS có AI Tutor trong môi trường web thực tế.",
        F22,
        980,
        DARK,
        31,
    )

    # Central source point, inspired by the Experience Gained sample.
    d.ellipse((390, 505, 520, 635), fill=SOFT, outline=BLUE, width=3)
    center(d, (390, 505, 520, 635), "✓", F46, BLUE)
    d.text((390, 650), "StackLearn", font=F22, fill=BLUE)

    items = [
        ("Xây dựng LMS hoàn chỉnh", "Hiểu luồng học viên, giảng viên và quản trị viên.", GREEN, "LMS"),
        ("Tích hợp AI Tutor", "Đưa Gemini API vào luồng học tập theo ngữ cảnh.", BLUE, "AI"),
        ("Áp dụng công nghệ", "Laravel, PostgreSQL, API AI, thanh toán và lưu trữ file.", ORANGE, "DEV"),
        ("Quy trình dự án", "Phân tích, thiết kế, triển khai, kiểm thử và đánh giá.", RED, "QA"),
    ]
    positions = [(650, 430), (790, 545), (930, 660), (1070, 775)]
    for (x, y), item in zip(positions, items):
        d.line((520, 570, x, y + 50), fill=LINE, width=2)
        experience_item(d, x, y, 420, 105, *item)

    d.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)
    path = Path("outputs/manual-stacklearn-ppt/stacklearn_conclusion_06_experience_morph.png")
    path.parent.mkdir(parents=True, exist_ok=True)
    im.save(path)
    print(path.resolve())


if __name__ == "__main__":
    make_overview()
    make_achievement()
    make_advantages()
    make_challenges()
    make_future()
    make_experience()
