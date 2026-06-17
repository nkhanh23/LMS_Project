import shutil
import zipfile
from pathlib import Path
from xml.etree import ElementTree as ET

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / "StackLearn-khanh-ver3-usecase-updated.docx"
if not SOURCE.exists():
    SOURCE = ROOT / "StackLearn-khanh-ver3.docx"
TARGET = ROOT / "StackLearn-khanh-ver3-final-updated.docx"

NS = {"w": "http://schemas.openxmlformats.org/wordprocessingml/2006/main"}
ET.register_namespace("w", NS["w"])


def q(name):
    return f"{{{NS['w']}}}{name}"


def paragraph_text(p):
    return "".join(t.text or "" for t in p.findall(".//w:t", NS)).strip()


def clone_ppr(source_p):
    ppr = source_p.find("w:pPr", NS)
    return ET.fromstring(ET.tostring(ppr)) if ppr is not None else None


def clone_rpr(source_p):
    r = source_p.find("w:r", NS)
    if r is None:
        return None
    rpr = r.find("w:rPr", NS)
    return ET.fromstring(ET.tostring(rpr)) if rpr is not None else None


def make_paragraph(text, ppr=None, rpr=None):
    p = ET.Element(q("p"))
    if ppr is not None:
        p.append(ET.fromstring(ET.tostring(ppr)))
    r = ET.SubElement(p, q("r"))
    if rpr is not None:
        r.append(ET.fromstring(ET.tostring(rpr)))
    t = ET.SubElement(r, q("t"))
    t.set("{http://www.w3.org/XML/1998/namespace}space", "preserve")
    t.text = text
    return p


def main():
    shutil.copyfile(SOURCE, TARGET)
    tmp = ROOT / "outputs" / "_conclusion_docx_tmp"
    if tmp.exists():
        shutil.rmtree(tmp)
    tmp.mkdir(parents=True)

    with zipfile.ZipFile(TARGET, "r") as zf:
        zf.extractall(tmp)

    doc_path = tmp / "word" / "document.xml"
    tree = ET.parse(doc_path)
    root = tree.getroot()
    body = root.find("w:body", NS)
    children = list(body)

    start = end = None
    for i, child in enumerate(children):
        if child.tag != q("p"):
            continue
        text = paragraph_text(child)
        if start is None and text.upper() == "KẾT LUẬN VÀ HƯỚNG PHÁT TRIỂN":
            start = i
        elif start is not None and text.upper() == "TÀI LIỆU THAM KHẢO":
            end = i
            break

    if start is None or end is None or end <= start:
        raise RuntimeError("Không tìm thấy đúng vùng KẾT LUẬN VÀ HƯỚNG PHÁT TRIỂN.")

    title_ppr = clone_ppr(children[start])
    title_rpr = clone_rpr(children[start])

    # Reuse local paragraph styles from the old section where possible.
    old_paragraphs = [c for c in children[start:end] if c.tag == q("p")]
    sub_p = next((p for p in old_paragraphs if paragraph_text(p).startswith("1.")), old_paragraphs[0])
    body_p = next((p for p in old_paragraphs if paragraph_text(p).startswith("Sau quá trình")), old_paragraphs[-1])
    bullet_p = next((p for p in old_paragraphs if paragraph_text(p).startswith("Xây dựng")), body_p)
    sub_ppr, sub_rpr = clone_ppr(sub_p), clone_rpr(sub_p)
    body_ppr, body_rpr = clone_ppr(body_p), clone_rpr(body_p)
    bullet_ppr, bullet_rpr = clone_ppr(bullet_p), clone_rpr(bullet_p)

    content = [
        ("title", "KẾT LUẬN VÀ HƯỚNG PHÁT TRIỂN"),
        ("sub", "1. Kết quả đạt được"),
        ("body", "Sau quá trình nghiên cứu, phân tích, thiết kế và xây dựng, đề tài đã hoàn thành hệ thống StackLearn - website học trực tuyến trên nền tảng Laravel, đáp ứng các nghiệp vụ chính của một hệ thống quản lý học tập trực tuyến. Hệ thống hỗ trợ ba nhóm người dùng gồm học viên, giảng viên và quản trị viên, đồng thời tích hợp AI Tutor nhằm nâng cao khả năng hỗ trợ học tập trong quá trình học bài."),
        ("body", "Các kết quả chính đạt được bao gồm:"),
        ("bullet", "Xây dựng được website học trực tuyến cho phép người dùng xem danh sách khóa học, tìm kiếm khóa học, xem chi tiết khóa học, thêm khóa học vào wishlist và giỏ hàng."),
        ("bullet", "Hoàn thiện luồng đăng ký, đăng nhập, xác thực email, phân quyền theo vai trò học viên, giảng viên và quản trị viên; đồng thời hỗ trợ đăng nhập thông qua Google/Facebook."),
        ("bullet", "Xây dựng chức năng thanh toán và ghi danh khóa học, hỗ trợ xử lý đơn hàng, áp dụng mã giảm giá, ghi nhận thanh toán và cấp quyền học tập sau khi giao dịch hợp lệ."),
        ("bullet", "Xây dựng khu vực học tập cho học viên với chức năng xem bài giảng, làm quiz, ghi chú, thảo luận, theo dõi tiến độ học tập và xem lịch sử học tập."),
        ("bullet", "Xây dựng khu vực giảng viên cho phép quản lý khóa học, section, bài giảng, tài liệu học tập, quiz, coupon, thảo luận, đơn hàng, doanh thu và yêu cầu rút tiền."),
        ("bullet", "Xây dựng khu vực quản trị viên cho phép quản lý người dùng, giảng viên, danh mục, khóa học, đơn hàng, hoàn tiền, payout, kiểm duyệt nội dung, audit log và thống kê học tập."),
        ("bullet", "Tích hợp AI Tutor sử dụng Gemini API để hỗ trợ học viên đặt câu hỏi theo ngữ cảnh bài học, lưu lịch sử hội thoại và khai thác transcript/tài liệu học tập làm dữ liệu hỗ trợ trả lời."),
        ("bullet", "Triển khai các chức năng hỗ trợ như tạo transcript bài giảng, lưu trữ tài nguyên học tập, thảo luận bài học, nhắn tin realtime và kiểm thử các luồng chức năng chính của hệ thống."),
        ("body", "Nhìn chung, StackLearn đã đáp ứng được mục tiêu xây dựng một nền tảng LMS có thể vận hành với các chức năng học tập, quản lý nội dung, thanh toán và hỗ trợ học tập bằng trí tuệ nhân tạo. Đây là cơ sở để tiếp tục hoàn thiện hệ thống theo hướng ổn định, mở rộng và phù hợp hơn với môi trường triển khai thực tế."),
        ("sub", "2. Hạn chế"),
        ("body", "Bên cạnh những kết quả đã đạt được, hệ thống StackLearn vẫn còn một số hạn chế cần tiếp tục cải thiện:"),
        ("bullet", "Một số giao diện quản trị và giảng viên còn nhiều thông tin, cần tối ưu thêm về bố cục, khả năng tìm kiếm, lọc dữ liệu và trải nghiệm sử dụng trên nhiều kích thước màn hình."),
        ("bullet", "Chức năng AI Tutor phụ thuộc vào chất lượng dữ liệu bài học, transcript, tài liệu học tập và khả năng phản hồi của dịch vụ Gemini API; do đó câu trả lời có thể chưa thật sự chính xác trong một số tình huống thiếu ngữ cảnh."),
        ("bullet", "Quá trình tạo transcript, xử lý tài liệu và truy xuất ngữ cảnh vẫn cần được tối ưu thêm về tốc độ, độ ổn định và khả năng xử lý lỗi khi dữ liệu đầu vào không đầy đủ hoặc dịch vụ bên ngoài không phản hồi."),
        ("bullet", "Hệ thống mới được kiểm thử chủ yếu trên môi trường cục bộ với dữ liệu mẫu, chưa đánh giá đầy đủ hiệu năng khi có nhiều người dùng truy cập đồng thời hoặc khi dữ liệu khóa học tăng lớn."),
        ("bullet", "Một số nghiệp vụ nâng cao như phân tích học tập chuyên sâu, gợi ý khóa học cá nhân hóa và kiểm duyệt nội dung tự động mới dừng ở mức nền tảng, chưa được khai thác sâu."),
        ("sub", "3. Hướng phát triển"),
        ("body", "Trong thời gian tới, hệ thống StackLearn có thể tiếp tục được mở rộng và hoàn thiện theo các hướng sau:"),
        ("bullet", "Hoàn thiện giao diện người dùng theo hướng trực quan, dễ thao tác hơn cho học viên, giảng viên và quản trị viên; bổ sung các bộ lọc, tìm kiếm nâng cao và dashboard tổng hợp rõ ràng hơn."),
        ("bullet", "Nâng cấp AI Tutor theo hướng Retrieval-Augmented Generation (RAG), kết hợp transcript, tài liệu học tập, nội dung bài giảng và lịch sử học tập để tăng độ chính xác của câu trả lời."),
        ("bullet", "Bổ sung cơ chế trích dẫn nguồn rõ ràng trong phản hồi của AI Tutor, giúp học viên biết câu trả lời được lấy từ bài giảng, transcript hoặc tài liệu nào."),
        ("bullet", "Tối ưu hiệu năng hệ thống bằng caching, queue/job, tối ưu truy vấn cơ sở dữ liệu, kiểm thử tải và giám sát hệ thống khi triển khai thực tế."),
        ("bullet", "Mở rộng các chức năng học tập như chứng chỉ hoàn thành khóa học, lộ trình học tập, gợi ý khóa học theo năng lực và thống kê tiến độ học tập chi tiết."),
        ("bullet", "Hoàn thiện các nghiệp vụ thương mại như hoàn tiền, payout, hóa đơn, báo cáo doanh thu và tích hợp thêm các cổng thanh toán phù hợp với người dùng Việt Nam."),
        ("bullet", "Tăng cường bảo mật và kiểm soát nội dung thông qua giới hạn truy cập tài nguyên, kiểm duyệt thảo luận/đánh giá, audit log, phân quyền chi tiết và cảnh báo rủi ro cho quản trị viên."),
        ("body", "Với các hướng phát triển trên, StackLearn có thể tiếp tục được hoàn thiện thành một nền tảng học trực tuyến có tính ứng dụng cao, hỗ trợ hiệu quả cho quá trình dạy và học trực tuyến, đồng thời khai thác tốt hơn vai trò của trí tuệ nhân tạo trong việc cá nhân hóa và nâng cao trải nghiệm học tập."),
    ]

    new_nodes = []
    for kind, text in content:
        if kind == "title":
            new_nodes.append(make_paragraph(text, title_ppr, title_rpr))
        elif kind == "sub":
            new_nodes.append(make_paragraph(text, sub_ppr, sub_rpr))
        elif kind == "bullet":
            new_nodes.append(make_paragraph(text, bullet_ppr, bullet_rpr))
        else:
            new_nodes.append(make_paragraph(text, body_ppr, body_rpr))

    for child in children[start:end]:
        body.remove(child)
    ref = children[end]
    for node in new_nodes:
        body.insert(list(body).index(ref), node)

    tree.write(doc_path, encoding="utf-8", xml_declaration=True)

    with zipfile.ZipFile(TARGET, "w", zipfile.ZIP_DEFLATED) as out:
        for file in tmp.rglob("*"):
            if file.is_file():
                out.write(file, file.relative_to(tmp).as_posix())

    shutil.rmtree(tmp)
    print(TARGET)
    print(f"Replaced paragraphs {start}..{end - 1}")


if __name__ == "__main__":
    main()
