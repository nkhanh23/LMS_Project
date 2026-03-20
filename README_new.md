# StackLearn - Hệ thống Quản lý Học tập Trực tuyến (LMS)

StackLearn là một nền tảng quản lý học tập (LMS) hiện đại, được xây dựng trên nền tảng **Laravel**, cung cấp giải pháp toàn diện cho việc dạy và học trực tuyến. Hệ thống được thiết kế để kết nối Giảng viên và Học viên một cách hiệu quả, hỗ trợ quy trình từ đăng ký khóa học đến kiểm tra đánh giá.

---

## 🎯 Mục tiêu Nghiệp vụ
Hệ thống StackLearn hướng tới việc tối ưu hóa trải nghiệm học tập thông qua:
- **Quản lý nội dung học tập**: Bài giảng video, tài liệu, và ghi chú.
- **Tương tác trực tiếp**: Thảo luận ngay trong bài giảng và hệ thống câu hỏi trắc nghiệm.
- **Kinh doanh khóa học**: Tích hợp thanh toán trực tuyến, mã giảm giá và quản lý đơn hàng.
- **Quản lý chất lượng**: Quy trình phê duyệt nghiêm ngặt đối với khóa học và giảng viên.

---

## 🚀 Các Tính năng Chính

### 👨‍🎓 Dành cho Học viên (Student)
- **Danh mục khóa học**: Tìm kiếm và lọc khóa học theo danh mục (Category) và danh mục con (Sub-category).
- **Hành trình học tập**: Theo dõi tiến độ bài học, xem video bài giảng, ghi chú cá nhân (Lecture Notes).
- **Tương tác**: Tham gia thảo luận trong từng bài giảng (Lecture Discussions).
- **Kiểm tra & Đánh giá**: Làm bài kiểm tra (Quizzes) cuối chương/khóa học, xem kết quả và điểm số tức thì.
- **Mua sắm & Thanh toán**: Thêm khóa học vào giỏ hàng (Cart), danh sách yêu thích (Wishlist), sử dụng mã giảm giá (Coupon) và thanh toán an toàn qua Stripe.

### 👨‍🏫 Dành cho Giảng viên (Instructor)
- **Đăng ký Giảng viên**: Quy trình gửi yêu cầu (Instructor Request) và chờ Admin phê duyệt.
- **Quản lý Khóa học**: Tạo và chỉnh sửa thông tin khóa học, thêm chương (Section) và bài giảng (Lecture).
- **Hệ thống Quắc nghiệm**: Tạo bộ câu hỏi, tùy chọn đáp án và thiết lập điểm đạt cho Quiz.
- **Theo dõi Hiệu quả**: Xem danh sách học viên đăng ký và quản lý các thảo luận liên quan đến bài giảng của mình.

### 🛡️ Dành cho Quản trị viên (Admin)
- **Trung tâm Phê duyệt Khóa học**: Xem xét nội dung khóa học trước khi cho phép xuất bản (Published/Rejected).
- **Quản lý Giảng viên**: Phê duyệt yêu cầu làm giảng viên, quản lý và đình chỉ (Suspended) tài khoản nếu cần.
- **Quản lý Hệ thống**: Cấu hình thông tin site, quản lý banner (Sliders), đối tác (Partners), và cài đặt SMTP.
- **Báo cáo & Thống kê**: Theo dõi đơn hàng, doanh thu và lưu lượng người dùng.

---

## 🛠️ Công nghệ Sử dụng
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Template engine, CSS Vanilla, JavaScript.
- **Cơ sở dữ liệu**: MySQL
- **Tích hợp**: Stripe (Thanh toán), Mailgun/SMTP (Email thông báo).

---

## 📦 Cài đặt & Triển khai
1. **Clone repository**: `git clone https://github.com/stacklearn/lms.git`
2. **Cài đặt dependencies**: `composer install` & `npm install`
3. **Cấu hình môi trường**: Sao chép `.env.example` thành `.env` và cập nhật thông tin Database, Stripe, Mail.
4. **Khởi tạo Database**: `php artisan migrate --seed`
5. **Chạy server**: `php artisan serve`

---

© 2026 StackLearn. Phát triển bởi đội ngũ đam mê giáo dục.
