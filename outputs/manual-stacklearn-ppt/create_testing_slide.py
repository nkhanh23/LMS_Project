from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


W, H = 1600, 900
BG = (248, 250, 255)
BLUE = (34, 82, 214)
DARK = (20, 30, 50)
MID = (72, 88, 112)
LINE = (194, 207, 226)
SOFT = (235, 243, 255)
PALE = (247, 250, 255)


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
    draw.text((72, 152), title, font=F46, fill=BLUE)
    draw.rounded_rectangle((72, 226, 560, 238), radius=3, fill=BLUE)
    draw.rounded_rectangle((1175, 165, 1460, 216), radius=24, fill=SOFT, outline=BLUE, width=2)
    center(draw, (1175, 165, 1460, 216), badge, F22, BLUE)


def bullet(draw, x, y, title, desc):
    draw.ellipse((x, y + 5, x + 28, y + 33), fill=BLUE)
    center(draw, (x, y + 5, x + 28, y + 33), "•", F24, (255, 255, 255))
    draw.text((x + 44, y), title, font=F22, fill=BLUE)
    paragraph(draw, x + 44, y + 29, desc, F18, 430, MID, 24)


def table_placeholder(draw, x, y, w, h):
    draw.rounded_rectangle((x, y, x + w, y + h), radius=22, fill=(255, 255, 255), outline=BLUE, width=2)
    draw.rounded_rectangle((x + 18, y + 18, x + w - 18, y + 74), radius=14, fill=SOFT, outline=LINE, width=1)
    center(draw, (x + 18, y + 18, x + w - 18, y + 74), "BẢNG KIỂM THỬ / KẾT QUẢ", F22, BLUE)

    # table structure
    pad = 22
    tx1, ty1 = x + pad, y + 96
    tx2, ty2 = x + w - pad, y + h - pad
    cols = [0.18, 0.19, 0.15, 0.22, 0.14, 0.12]
    headers = ["Function", "Precondition", "Trigger", "Expected Result", "Actual Result", "Status"]

    cur_x = tx1
    xs = [tx1]
    for frac in cols[:-1]:
        cur_x += int((tx2 - tx1) * frac)
        xs.append(cur_x)
    xs.append(tx2)

    # header row
    row_h = 48
    for i in range(len(headers)):
        x1 = xs[i]
        x2 = xs[i + 1]
        draw.rectangle((x1, ty1, x2, ty1 + row_h), fill=BLUE)
        center(draw, (x1 + 4, ty1, x2 - 4, ty1 + row_h), headers[i], F16, (255, 255, 255))

    # blank body rows
    body_top = ty1 + row_h
    body_bottom = ty2
    rows = 6
    row_h2 = int((body_bottom - body_top) / rows)
    for r in range(rows):
        ry1 = body_top + r * row_h2
        ry2 = ry1 + row_h2
        fill = (251, 253, 255) if r % 2 == 0 else (245, 248, 253)
        draw.rectangle((tx1, ry1, tx2, ry2), fill=fill, outline=LINE, width=1)
        for xline in xs[1:-1]:
            draw.line((xline, ry1, xline, ry2), fill=LINE, width=1)

    # subtle notes inside
    notes = [
        "1. Functional tests cho luồng LMS chính",
        "2. Functional tests cho Chatbot AI",
        "3. Đối chiếu expected vs actual",
        "4. Ghi nhận pass/fail rõ ràng",
    ]
    ny = y + h - 118
    for note in notes:
        draw.text((x + 34, ny), note, font=F18, fill=MID)
        ny += 24


def make_slide():
    im = Image.new("RGB", (W, H), BG)
    d = ImageDraw.Draw(im)
    header(d, "KIỂM THỬ VÀ ĐÁNH GIÁ", "TESTING")

    paragraph(
        d,
        72,
        278,
        "Chương kiểm thử của StackLearn tập trung vào hai phần: các chức năng chính của hệ thống LMS và kiểm thử chatbot AI. Mục tiêu là xác nhận luồng nghiệp vụ chạy đúng, phản hồi đúng ngữ cảnh và kết quả khớp với yêu cầu trong báo cáo.",
        F22,
        510,
        DARK,
        30,
    )

    bullet(d, 82, 420, "Phạm vi kiểm thử", "Đăng ký, đăng nhập, tìm khóa học, ghi danh, thanh toán, học bài, quiz và AI Tutor.")
    bullet(d, 82, 525, "Chatbot AI", "Kiểm thử theo kịch bản hội thoại, ngữ cảnh bài học, lịch sử chat và thời gian phản hồi.")
    bullet(d, 82, 630, "Kết quả mong đợi", "Bảng test case có trạng thái pass/fail rõ ràng, dễ trình bày khi báo cáo.")

    table_placeholder(d, 655, 286, 875, 520)

    draw = d
    draw.text((72, 858), "StackLearn | LMS tích hợp AI Tutor", font=F20, fill=MID)

    path = Path("outputs/manual-stacklearn-ppt/stacklearn_testing_slide.png")
    im.save(path)
    print(path.resolve())


if __name__ == "__main__":
    make_slide()
