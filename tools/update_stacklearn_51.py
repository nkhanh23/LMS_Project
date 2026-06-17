from __future__ import annotations

from pathlib import Path

from docx import Document


INPUT = Path("StackLearn-khanh.docx")
OUTPUT = Path("StackLearn-khanh-cap-nhat-5-1.docx")

HARDWARE_ROWS = [
    ("Thành phần", "Thông số"),
    ("Thiết bị", "Laptop Lenovo 82K2"),
    ("CPU", "AMD Ryzen 5 5600H with Radeon Graphics, 6 nhân 12 luồng, xung nhịp tối đa khoảng 3.30 GHz"),
    ("RAM", "20 GB"),
    ("Ổ cứng", "Ổ C: khoảng 475 GB theo Windows, dung lượng còn trống khoảng 167 GB"),
    ("Hệ điều hành", "Microsoft Windows 11 Home Single Language 64-bit, phiên bản 10.0.26200"),
]

SOFTWARE_ROWS = [
    ("Công nghệ / Công cụ", "Phiên bản / Ghi chú"),
    ("Hệ điều hành", "Microsoft Windows 11 Home Single Language 64-bit"),
    ("Môi trường chạy local", "XAMPP trên Windows, sử dụng PHP CLI tại C:\\xampp\\php\\php.exe"),
    ("PHP", "8.2.12"),
    ("Composer", "2.9.2"),
    ("Laravel Framework", "12.x, khai báo trong composer.json là ^12.0"),
    ("Laravel Breeze", "^2.3, dùng cho xác thực người dùng"),
    ("Laravel Reverb / Echo", "Reverb ^1.0, Laravel Echo ^2.3.4, hỗ trợ realtime/broadcasting"),
    ("Cơ sở dữ liệu", "PostgreSQL, cấu hình mặc định DB_CONNECTION=pgsql, database stacklearn"),
    ("Queue / Session / Cache", "Database driver theo cấu hình mẫu của dự án"),
    ("Node.js", "v22.11.0"),
    ("npm", "10.9.0"),
    ("Vite", "^7.0.7"),
    ("Tailwind CSS", "^3.1.0"),
    ("Alpine.js", "^3.4.2"),
    ("Thư viện tích hợp chính", "Stripe PHP ^19.4, Laravel Socialite ^5.24, Flysystem AWS S3 v3, Maatwebsite Excel, PDF Parser, YouTube Transcript"),
    ("Dịch vụ tích hợp", "Stripe, VNPay, Google/Facebook Socialite, Cloudflare R2/S3, Gemini API, OpenAI transcription"),
]


def set_table_rows(table, rows):
    while len(table.rows) < len(rows):
        table.add_row()
    for index, row_data in enumerate(rows):
        row = table.rows[index]
        while len(row.cells) < len(row_data):
            table.add_column(1000000)
        for cell_index, value in enumerate(row_data):
            row.cells[cell_index].text = value
    while len(table.rows) > len(rows):
        tr = table.rows[-1]._tr
        tr.getparent().remove(tr)


def main() -> None:
    doc = Document(INPUT)
    hardware_table = None
    software_table = None

    for table in doc.tables:
        first = [cell.text.strip() for cell in table.rows[0].cells] if table.rows else []
        if len(first) >= 2 and first[0] == "Thành phần" and first[1] == "Thông số":
            hardware_table = table
        if len(first) >= 2 and first[0].startswith("Công nghệ") and first[1].startswith("Phiên bản"):
            software_table = table

    if hardware_table is None:
        raise RuntimeError("Không tìm thấy bảng phần cứng mục 5.1.")
    if software_table is None:
        raise RuntimeError("Không tìm thấy bảng phần mềm mục 5.1.")

    set_table_rows(hardware_table, HARDWARE_ROWS)
    set_table_rows(software_table, SOFTWARE_ROWS)
    doc.save(OUTPUT)
    print(OUTPUT.resolve())


if __name__ == "__main__":
    main()
