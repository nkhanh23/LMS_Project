# Phân tích Database Dự án StackLearn

StackLearn là hệ thống LMS tích hợp AI, được xây dựng trên nền tảng **Laravel 12** và sử dụng **PostgreSQL** làm hệ quản trị cơ sở dữ liệu mặc định. Cơ sở dữ liệu bao gồm **61 bảng** được thiết kế chặt chẽ để phục vụ các luồng nghiệp vụ từ quản lý học tập, thanh toán, tương tác thời gian thực cho đến các tính năng AI nâng cao (RAG, Transcripts, Concept extraction).

## Sơ đồ Phân nhóm Hệ thống (Modules)

Các bảng trong cơ sở dữ liệu được chia thành các phân hệ chức năng chính như sau:

### Hệ thống & Xác thực (Core & Auth)
- **`users`**: Bảng lưu trữ tất cả người dùng trong hệ thống (Học viên, Giảng viên, Quản trị viên).
- **`password_reset_tokens`**: Lưu trữ token phục vụ tính năng đặt lại mật khẩu.
- **`sessions`**: Lưu trữ các phiên làm việc của người dùng (Session driver: database).
- **`admin_audit_logs`**: Nhật ký ghi lại các hoạt động/hành động nhạy cảm của Admin.
- **`notifications`**: Bảng lưu trữ thông báo hệ thống (Database Notifications của Laravel).

### Quản lý Khóa học & Bài giảng (Courses & Lectures)
- **`categories`**: Danh mục chính của các khóa học (ví dụ: Công nghệ thông tin, Thiết kế đồ họa).
- **`sub_categories`**: Danh mục con nằm trong danh mục chính để phân loại chi tiết hơn.
- **`courses`**: Bảng chứa thông tin tổng quan của các khóa học.
- **`course_goals`**: Lưu trữ các mục tiêu, yêu cầu hoặc đối tượng hướng tới của khóa học.
- **`course_sections`**: Các chương học/phần học trong cấu trúc của một khóa học.
- **`course_lectures`**: Các bài học chi tiết (video, tài liệu) nằm trong mỗi chương học.
- **`course_quality_checks`**: Kết quả kiểm tra chất lượng khóa học trước khi duyệt hiển thị.

### Tiến độ Học tập & Đăng ký (Enrollments & Progress)
- **`enrollments`**: Đăng ký học (mối liên kết xác nhận học viên có quyền truy cập khóa học).
- **`lesson_progress`**: Tiến độ học tập chi tiết của học viên trên từng bài học (đã hoàn thành hay chưa).
- **`course_progress`**: Tiến độ học tập tổng quan (phần trăm hoàn thành) của học viên trong khóa học.

### Thương mại điện tử & Thanh toán (Commerce & Payment)
- **`carts`**: Giỏ hàng tạm thời của học viên khi mua khóa học.
- **`wishlists`**: Danh sách khóa học yêu thích/muốn mua sau của học viên.
- **`coupons`**: Mã giảm giá/khuyến mãi áp dụng khi thanh toán khóa học.
- **`orders`**: Đơn hàng mua khóa học của học viên.
- **`payments`**: Thông tin chi tiết giao dịch thanh toán của đơn hàng.
- **`refund_requests`**: Yêu cầu hoàn tiền từ học viên đối với các khóa học đã mua.
- **`order_status_histories`**: Lịch sử ghi nhận sự thay đổi trạng thái của đơn hàng.
- **`payout_requests`**: Yêu cầu rút tiền doanh thu của giảng viên gửi lên hệ thống.

### Tương tác & Thảo luận (Interactions & Discussions)
- **`lecture_discussions`**: Các câu hỏi, thảo luận của học viên và giảng viên tại mỗi bài học.
- **`lecture_notes`**: Ghi chú cá nhân của học viên trong quá trình xem bài học.
- **`course_reviews`**: Đánh giá, xếp hạng sao và nhận xét của học viên về khóa học.
- **`conversations`**: Các cuộc hội thoại trực tiếp giữa các người dùng (Học viên - Giảng viên).
- **`messages`**: Tin nhắn chi tiết trong các cuộc hội thoại chat realtime.

### Kiểm duyệt & Đánh giá Rủi ro (Moderation & Risk Assessment)
- **`content_reports`**: Báo cáo nội dung vi phạm (bài giảng, thảo luận, đánh giá) từ phía học viên.
- **`moderation_policies`**: Các chính sách kiểm duyệt nội dung của hệ thống.
- **`moderation_action_templates`**: Các mẫu phản hồi/hành động kiểm duyệt định sẵn.
- **`instructor_risk_scores`**: Điểm đánh giá mức độ rủi ro hoặc vi phạm của giảng viên.
- **`instructor_requests`**: Đơn đăng ký và thông tin yêu cầu làm giảng viên từ người dùng.

### Hỗ trợ AI & Trích xuất Tri thức (AI Tutor & RAG)
- **`ai_chat_sessions`**: Phiên trò chuyện giữa học viên và AI Tutor tại một bài học cụ thể.
- **`ai_chat_messages`**: Các tin nhắn trao đổi chi tiết trong phiên chat AI.
- **`ai_documents`**: Tài liệu học tập được tải lên để làm cơ sở tri thức cho RAG (AI Tutor tham chiếu).
- **`ai_document_chunks`**: Các đoạn văn bản được chia nhỏ từ tài liệu và các vector nhúng (embeddings) để phục vụ tìm kiếm ngữ nghĩa.
- **`ai_message_citations`**: Các trích dẫn/nguồn tham chiếu từ tài liệu học tập cho câu trả lời của AI.
- **`gemini_settings`**: Cấu hình API Key và các tham số cho mô hình ngôn ngữ lớn Gemini.
- **`concepts`**: Các khái niệm cốt lõi được hệ thống trích xuất tự động từ nội dung bài học.
- **`lesson_concepts`**: Mối quan hệ giữa bài học và các khái niệm được trích xuất.
- **`document_concepts`**: Mối quan hệ giữa tài liệu tham khảo và các khái niệm được trích xuất.
- **`transcript_jobs`**: Tiến trình/Job xử lý chuyển đổi video bài giảng thành văn bản (transcription).

### Cấu hình Website (Configuration)
- **`site_infos`**: Cấu hình chung của website (Logo, Tên site, Hotline, Email liên hệ, v.v.).
- **`sliders`**: Quản lý các slide hình ảnh trình chiếu ở trang chủ.
- **`info_boxes`**: Quản lý các hộp thông tin giới thiệu tính năng nổi bật ở trang chủ.
- **`striipes`**: Cấu hình tích hợp cổng thanh toán Stripe.
- **`googles`**: Cấu hình tích hợp dịch vụ Google (OAuth, API, v.v.).
- **`smtps`**: Cấu hình gửi email qua giao thức SMTP.
- **`partners`**: Danh sách đối tác liên kết hiển thị trên trang chủ.

### Hạ tầng & Hàng đợi (Infrastructure & Queues)
- **`cache`**: Lưu dữ liệu cache của hệ thống.
- **`cache_locks`**: Lưu các khóa lock cache để tránh race condition.
- **`jobs`**: Bảng hàng đợi công việc xử lý nền (Queue jobs).
- **`job_batches`**: Bảng theo dõi các nhóm job chạy đồng thời.
- **`failed_jobs`**: Bảng lưu trữ các job chạy nền bị lỗi để debug.
- **`migrations`**: Lưu trữ lịch sử chạy migration của Laravel.

--- 

## Chi tiết Từng Bảng Trong Database

### Bảng: `admin_audit_logs`
> **Mô tả**: Nhật ký ghi lại các hoạt động/hành động nhạy cảm của Admin.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('admin_audit_logs_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **admin_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng admins |
| **action** | `character varying(255)` | No | *NULL* | - |
| **target_type** | `character varying(255)` | No | *NULL* | - |
| **target_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng targets |
| **note** | `text` | Yes | *NULL* | - |
| **old_values_json** | `json` | Yes | *NULL* | - |
| **new_values_json** | `json` | Yes | *NULL* | - |
| **context_json** | `json` | Yes | *NULL* | - |
| **ip_address** | `character varying(45)` | Yes | *NULL* | Địa chỉ IP của người dùng |
| **user_agent** | `text` | Yes | *NULL* | Thông tin trình duyệt và thiết bị của người dùng |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `admin_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `ai_chat_messages`
> **Mô tả**: Các tin nhắn trao đổi chi tiết trong phiên chat AI.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('ai_chat_messages_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **session_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng tương ứng (Phiên chat AI hoặc Session) |
| **user_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **role** | `character varying(20)` | No | *NULL* | Vai trò/Quyền hạn |
| **content** | `text` | No | *NULL* | Nội dung văn bản |
| **provider** | `character varying(255)` | Yes | *NULL* | - |
| **model** | `character varying(255)` | Yes | *NULL* | - |
| **prompt_tokens** | `integer` | Yes | *NULL* | - |
| **completion_tokens** | `integer` | Yes | *NULL* | - |
| **latency_ms** | `integer` | Yes | *NULL* | - |
| **meta_json** | `json` | Yes | *NULL* | Dữ liệu cấu hình hoặc siêu dữ liệu dạng JSON |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `session_id` liên kết tới `ai_chat_sessions(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `ai_chat_sessions`
> **Mô tả**: Phiên trò chuyện giữa học viên và AI Tutor tại một bài học cụ thể.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('ai_chat_sessions_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **title** | `character varying(255)` | Yes | *NULL* | Tiêu đề |
| **status** | `character varying(255)` | No | `'active'::character varying` | Trạng thái hoạt động/xử lý |
| **last_activity_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `ai_document_chunks`
> **Mô tả**: Các đoạn văn bản được chia nhỏ từ tài liệu và các vector nhúng (embeddings) để phục vụ tìm kiếm ngữ nghĩa.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('ai_document_chunks_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **document_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `ai_documents` (Tài liệu) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **lecture_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **chunk_index** | `integer` | No | *NULL* | - |
| **content** | `text` | No | *NULL* | Nội dung văn bản |
| **content_length** | `integer` | No | `0` | - |
| **meta_json** | `json` | Yes | *NULL* | Dữ liệu cấu hình hoặc siêu dữ liệu dạng JSON |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |
| **embedding** | `USER-DEFINED` | Yes | *NULL* | - |
| **embedding_provider** | `character varying(100)` | Yes | *NULL* | - |
| **embedding_model** | `character varying(150)` | Yes | *NULL* | - |
| **embedding_status** | `character varying(30)` | No | `'pending'::character varying` | - |
| **embedding_error** | `text` | Yes | *NULL* | - |
| **external_vector_id** | `character varying(255)` | Yes | *NULL* | Khóa ngoại liên kết tới bảng external_vectors |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `document_id` liên kết tới `ai_documents(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `ai_documents`
> **Mô tả**: Tài liệu học tập được tải lên để làm cơ sở tri thức cho RAG (AI Tutor tham chiếu).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('ai_documents_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **lecture_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **uploaded_by** | `bigint` | No | *NULL* | - |
| **title** | `character varying(255)` | No | *NULL* | Tiêu đề |
| **source_type** | `character varying(50)` | No | `'manual_upload'::character varying` | - |
| **file_name** | `character varying(255)` | Yes | *NULL* | - |
| **mime_type** | `character varying(255)` | Yes | *NULL* | - |
| **storage_disk** | `character varying(255)` | Yes | *NULL* | - |
| **storage_path** | `character varying(255)` | Yes | *NULL* | - |
| **extracted_text** | `text` | Yes | *NULL* | - |
| **language** | `character varying(10)` | No | `'vi'::character varying` | - |
| **index_status** | `character varying(20)` | No | `'pending'::character varying` | - |
| **index_error** | `text` | Yes | *NULL* | - |
| **indexed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `uploaded_by` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `ai_message_citations`
> **Mô tả**: Các trích dẫn/nguồn tham chiếu từ tài liệu học tập cho câu trả lời của AI.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('ai_message_citations_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **message_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng tương ứng (Tin nhắn) |
| **document_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `ai_documents` (Tài liệu) |
| **chunk_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `ai_document_chunks` (Đoạn tài liệu) |
| **rank** | `integer` | No | `1` | - |
| **score** | `numeric` | Yes | *NULL* | - |
| **snippet** | `text` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `message_id` liên kết tới `ai_chat_messages(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `document_id` liên kết tới `ai_documents(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `chunk_id` liên kết tới `ai_document_chunks(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `cache`
> **Mô tả**: Lưu dữ liệu cache của hệ thống.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **key** | `character varying(255)` | No | *NULL* | - |
| **value** | `text` | No | *NULL* | - |
| **expiration** | `integer` | No | *NULL* | - |

---

### Bảng: `cache_locks`
> **Mô tả**: Lưu các khóa lock cache để tránh race condition.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **key** | `character varying(255)` | No | *NULL* | - |
| **owner** | `character varying(255)` | No | *NULL* | - |
| **expiration** | `integer` | No | *NULL* | - |

---

### Bảng: `carts`
> **Mô tả**: Giỏ hàng tạm thời của học viên khi mua khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('carts_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **guest_token** | `uuid` | Yes | *NULL* | - |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **quantity** | `integer` | No | `1` | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `categories`
> **Mô tả**: Danh mục chính của các khóa học (ví dụ: Công nghệ thông tin, Thiết kế đồ họa).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('categories_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **slug** | `character varying(255)` | No | *NULL* | Đường dẫn thân thiện (SEO URL friendly) |
| **image** | `character varying(255)` | Yes | *NULL* | Đường dẫn ảnh |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `concepts`
> **Mô tả**: Các khái niệm cốt lõi được hệ thống trích xuất tự động từ nội dung bài học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('concepts_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **description** | `text` | Yes | *NULL* | Mô tả chi tiết |
| **synonyms_json** | `json` | Yes | *NULL* | - |
| **is_active** | `boolean` | No | `true` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `content_reports`
> **Mô tả**: Báo cáo nội dung vi phạm (bài giảng, thảo luận, đánh giá) từ phía học viên.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('content_reports_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **reporter_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng reporters |
| **reported_user_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng reported_users |
| **reportable_type** | `character varying(255)` | No | *NULL* | - |
| **reportable_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng reportables |
| **course_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **lecture_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **reason_code** | `character varying(255)` | No | *NULL* | - |
| **description** | `text` | Yes | *NULL* | Mô tả chi tiết |
| **status** | `character varying(255)` | No | `'pending'::character varying` | Trạng thái hoạt động/xử lý |
| **resolution_action** | `character varying(255)` | Yes | *NULL* | - |
| **resolution_note** | `text` | Yes | *NULL* | - |
| **content_snapshot** | `json` | Yes | *NULL* | - |
| **reviewed_by** | `bigint` | Yes | *NULL* | - |
| **reviewed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |
| **policy_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng policys |
| **action_template_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng action_templates |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `reporter_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `reported_user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `reviewed_by` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `policy_id` liên kết tới `moderation_policies(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `action_template_id` liên kết tới `moderation_action_templates(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `conversations`
> **Mô tả**: Các cuộc hội thoại trực tiếp giữa các người dùng (Học viên - Giảng viên).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('conversations_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **student_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng students |
| **instructor_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `student_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `instructor_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `coupons`
> **Mô tả**: Mã giảm giá/khuyến mãi áp dụng khi thanh toán khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('coupons_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **instructor_id** | `integer` | No | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **coupon_code** | `character varying(255)` | No | *NULL* | - |
| **coupon_discount** | `character varying(255)` | No | *NULL* | - |
| **discount_validity** | `character varying(255)` | No | *NULL* | - |
| **status** | `integer` | No | `1` | Trạng thái hoạt động/xử lý |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `course_goals`
> **Mô tả**: Lưu trữ các mục tiêu, yêu cầu hoặc đối tượng hướng tới của khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('course_goals_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **goal_name** | `text` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `course_lectures`
> **Mô tả**: Các bài học chi tiết (video, tài liệu) nằm trong mỗi chương học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('course_lectures_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **section_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `course_sections` (Chương học) |
| **lecture_title** | `character varying(255)` | Yes | *NULL* | - |
| **sort_order** | `integer` | No | `1` | - |
| **is_preview** | `boolean` | No | `false` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **type** | `character varying(20)` | No | `'video'::character varying` | - |
| **url** | `character varying(255)` | Yes | *NULL* | - |
| **file_name** | `character varying(255)` | Yes | *NULL* | - |
| **mime_type** | `character varying(255)` | Yes | *NULL* | - |
| **file_size** | `bigint` | Yes | *NULL* | - |
| **storage_disk** | `character varying(255)` | Yes | *NULL* | - |
| **content** | `text` | Yes | *NULL* | Nội dung văn bản |
| **video_duration** | `numeric` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `section_id` liên kết tới `course_sections(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `course_progress`
> **Mô tả**: Tiến độ học tập tổng quan (phần trăm hoàn thành) của học viên trong khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('course_progress_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **enrollment_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng enrollments |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **total_lectures** | `integer` | No | `0` | - |
| **completed_lectures** | `integer` | No | `0` | - |
| **completion_percent** | `integer` | No | `0` | - |
| **last_lecture_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng last_lectures |
| **last_activity_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **completed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `enrollment_id` liên kết tới `enrollments(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `last_lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `course_quality_checks`
> **Mô tả**: Kết quả kiểm tra chất lượng khóa học trước khi duyệt hiển thị.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('course_quality_checks_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **check_key** | `character varying(255)` | No | *NULL* | - |
| **status** | `character varying(255)` | No | `'fail'::character varying` | Trạng thái hoạt động/xử lý |
| **message** | `text` | Yes | *NULL* | - |
| **reviewed_by** | `bigint` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `course_reviews`
> **Mô tả**: Đánh giá, xếp hạng sao và nhận xét của học viên về khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('course_reviews_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **instructor_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **rating** | `integer` | No | *NULL* | - |
| **comment** | `text` | No | *NULL* | - |
| **is_approved** | `boolean` | No | `false` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |
| **deleted_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm xóa mềm (Soft Delete) |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `instructor_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `course_sections`
> **Mô tả**: Các chương học/phần học trong cấu trúc của một khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('course_sections_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **section_title** | `character varying(255)` | No | *NULL* | - |
| **sort_order** | `integer` | No | `1` | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `courses`
> **Mô tả**: Bảng chứa thông tin tổng quan của các khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('courses_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **category_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `categories` (Danh mục) |
| **subcategory_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng subcategorys |
| **instructor_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **course_image** | `character varying(255)` | Yes | *NULL* | - |
| **course_title** | `text` | Yes | *NULL* | - |
| **course_name** | `text` | Yes | *NULL* | - |
| **course_name_slug** | `character varying(255)` | Yes | *NULL* | - |
| **description** | `text` | Yes | *NULL* | Mô tả chi tiết |
| **video_url** | `character varying(255)` | Yes | *NULL* | - |
| **label** | `character varying(255)` | Yes | *NULL* | - |
| **duration** | `character varying(255)` | Yes | *NULL* | - |
| **resources** | `character varying(255)` | Yes | *NULL* | - |
| **certificate** | `character varying(255)` | Yes | *NULL* | - |
| **selling_price** | `integer` | Yes | *NULL* | - |
| **discount_price** | `integer` | Yes | *NULL* | Số tiền được giảm/Giá sau giảm |
| **prerequisites** | `text` | Yes | *NULL* | - |
| **bestseller** | `character varying(255)` | Yes | *NULL* | - |
| **featured** | `character varying(255)` | Yes | *NULL* | - |
| **highestrated** | `character varying(255)` | Yes | *NULL* | - |
| **course_goals** | `json` | Yes | *NULL* | - |
| **status** | `smallint` | No | `'0'::smallint` | Trạng thái hoạt động/xử lý |
| **approval_status** | `character varying(255)` | No | `'draft'::character varying` | - |
| **content_unlock_mode** | `character varying(255)` | No | `'free'::character varying` | - |
| **approval_note** | `text` | Yes | *NULL* | - |
| **reviewed_by** | `bigint` | Yes | *NULL* | - |
| **reviewed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **submitted_for_review_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **approved_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `document_concepts`
> **Mô tả**: Mối quan hệ giữa tài liệu tham khảo và các khái niệm được trích xuất.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('document_concepts_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **document_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `ai_documents` (Tài liệu) |
| **concept_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng concepts |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `document_id` liên kết tới `ai_documents(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `concept_id` liên kết tới `concepts(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `enrollments`
> **Mô tả**: Đăng ký học (mối liên kết xác nhận học viên có quyền truy cập khóa học).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('enrollments_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **order_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `orders` (Đơn hàng) |
| **source** | `character varying(255)` | No | `'order'::character varying` | - |
| **status** | `character varying(255)` | No | `'active'::character varying` | Trạng thái hoạt động/xử lý |
| **access_granted_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **access_expires_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **last_lecture_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng last_lectures |
| **last_accessed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **completed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **revoked_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **revoked_reason** | `text` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `order_id` liên kết tới `orders(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `last_lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `failed_jobs`
> **Mô tả**: Bảng lưu trữ các job chạy nền bị lỗi để debug.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('failed_jobs_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **uuid** | `character varying(255)` | No | *NULL* | - |
| **connection** | `text` | No | *NULL* | - |
| **queue** | `text` | No | *NULL* | - |
| **payload** | `text` | No | *NULL* | - |
| **exception** | `text` | No | *NULL* | - |
| **failed_at** | `timestamp without time zone` | No | `CURRENT_TIMESTAMP` | - |

---

### Bảng: `gemini_settings`
> **Mô tả**: Cấu hình API Key và các tham số cho mô hình ngôn ngữ lớn Gemini.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('gemini_settings_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **api_key** | `text` | Yes | *NULL* | - |
| **model_name** | `character varying(255)` | No | `'gemini-1.5-flash'::character varying` | - |
| **timeout_seconds** | `integer` | No | `30` | - |
| **temperature** | `numeric` | No | `0.2` | - |
| **max_output_tokens** | `integer` | No | `1024` | - |
| **is_enabled** | `boolean` | No | `true` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **updated_by** | `bigint` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |
| **base_url** | `character varying(255)` | Yes | *NULL* | - |

---

### Bảng: `googles`
> **Mô tả**: Cấu hình tích hợp dịch vụ Google (OAuth, API, v.v.).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('googles_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **client_id** | `character varying(255)` | No | *NULL* | Khóa ngoại liên kết tới bảng clients |
| **secret_key** | `character varying(255)` | No | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `info_boxes`
> **Mô tả**: Quản lý các hộp thông tin giới thiệu tính năng nổi bật ở trang chủ.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('info_boxes_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **icon** | `character varying(255)` | No | *NULL* | - |
| **title** | `character varying(255)` | No | *NULL* | Tiêu đề |
| **description** | `text` | No | *NULL* | Mô tả chi tiết |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `instructor_requests`
> **Mô tả**: Đơn đăng ký và thông tin yêu cầu làm giảng viên từ người dùng.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('instructor_requests_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **headline** | `character varying(255)` | Yes | *NULL* | - |
| **bio** | `text` | Yes | *NULL* | - |
| **experience** | `text` | Yes | *NULL* | - |
| **phone** | `character varying(255)` | Yes | *NULL* | Số điện thoại |
| **status** | `character varying(255)` | No | `'pending'::character varying` | Trạng thái hoạt động/xử lý |
| **admin_note** | `text` | Yes | *NULL* | - |
| **reviewed_by** | `bigint` | Yes | *NULL* | - |
| **reviewed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `reviewed_by` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `instructor_risk_scores`
> **Mô tả**: Điểm đánh giá mức độ rủi ro hoặc vi phạm của giảng viên.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('instructor_risk_scores_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **instructor_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **risk_score** | `integer` | No | `0` | - |
| **confirmed_reports_count** | `integer` | No | `0` | - |
| **refund_requests_count** | `integer` | No | `0` | - |
| **rejected_courses_count** | `integer` | No | `0` | - |
| **warnings_count** | `integer` | No | `0` | - |
| **calculated_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `job_batches`
> **Mô tả**: Bảng theo dõi các nhóm job chạy đồng thời.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `character varying(255)` | No | *NULL* | Khóa chính (Auto Increment) |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **total_jobs** | `integer` | No | *NULL* | - |
| **pending_jobs** | `integer` | No | *NULL* | - |
| **failed_jobs** | `integer` | No | *NULL* | - |
| **failed_job_ids** | `text` | No | *NULL* | Khóa ngoại liên kết tới bảng failed_jobss |
| **options** | `text` | Yes | *NULL* | - |
| **cancelled_at** | `integer` | Yes | *NULL* | - |
| **created_at** | `integer` | No | *NULL* | Thời điểm tạo bản ghi |
| **finished_at** | `integer` | Yes | *NULL* | - |

---

### Bảng: `jobs`
> **Mô tả**: Bảng hàng đợi công việc xử lý nền (Queue jobs).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('jobs_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **queue** | `character varying(255)` | No | *NULL* | - |
| **payload** | `text` | No | *NULL* | - |
| **attempts** | `smallint` | No | *NULL* | - |
| **reserved_at** | `integer` | Yes | *NULL* | - |
| **available_at** | `integer` | No | *NULL* | - |
| **created_at** | `integer` | No | *NULL* | Thời điểm tạo bản ghi |

---

### Bảng: `lecture_discussions`
> **Mô tả**: Các câu hỏi, thảo luận của học viên và giảng viên tại mỗi bài học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('lecture_discussions_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **parent_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng parents |
| **content** | `text` | No | *NULL* | Nội dung văn bản |
| **is_approved** | `boolean` | No | `true` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **deleted_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm xóa mềm (Soft Delete) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `parent_id` liên kết tới `lecture_discussions(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `lecture_notes`
> **Mô tả**: Ghi chú cá nhân của học viên trong quá trình xem bài học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('lecture_notes_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **note** | `text` | No | *NULL* | - |
| **video_second** | `integer` | No | *NULL* | - |
| **formatted_time** | `character varying(20)` | No | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `lesson_concepts`
> **Mô tả**: Mối quan hệ giữa bài học và các khái niệm được trích xuất.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('lesson_concepts_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **concept_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng concepts |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `concept_id` liên kết tới `concepts(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `lesson_progress`
> **Mô tả**: Tiến độ học tập chi tiết của học viên trên từng bài học (đã hoàn thành hay chưa).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('lesson_progress_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **enrollment_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng enrollments |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **section_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `course_sections` (Chương học) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **status** | `character varying(255)` | No | `'not_started'::character varying` | Trạng thái hoạt động/xử lý |
| **progress_percent** | `integer` | No | `0` | - |
| **watch_seconds** | `integer` | No | `0` | - |
| **started_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **last_watched_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **completed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `enrollment_id` liên kết tới `enrollments(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `section_id` liên kết tới `course_sections(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `messages`
> **Mô tả**: Tin nhắn chi tiết trong các cuộc hội thoại chat realtime.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('messages_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **conversation_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `conversations` (Cuộc hội thoại) |
| **sender_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng senders |
| **message** | `text` | No | *NULL* | - |
| **is_read** | `boolean` | No | `false` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `conversation_id` liên kết tới `conversations(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `sender_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `migrations`
> **Mô tả**: Lưu trữ lịch sử chạy migration của Laravel.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `integer` | No | `nextval('migrations_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **migration** | `character varying(255)` | No | *NULL* | - |
| **batch** | `integer` | No | *NULL* | - |

---

### Bảng: `moderation_action_templates`
> **Mô tả**: Các mẫu phản hồi/hành động kiểm duyệt định sẵn.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('moderation_action_templates_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **code** | `character varying(255)` | No | *NULL* | - |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **target_type** | `character varying(255)` | Yes | *NULL* | - |
| **default_note** | `text` | Yes | *NULL* | - |
| **requires_reason** | `boolean` | No | `true` | - |
| **is_active** | `boolean` | No | `true` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `moderation_policies`
> **Mô tả**: Các chính sách kiểm duyệt nội dung của hệ thống.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('moderation_policies_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **code** | `character varying(255)` | No | *NULL* | - |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **target_type** | `character varying(255)` | Yes | *NULL* | - |
| **description** | `text` | Yes | *NULL* | Mô tả chi tiết |
| **is_active** | `boolean` | No | `true` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `notifications`
> **Mô tả**: Bảng lưu trữ thông báo hệ thống (Database Notifications của Laravel).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `uuid` | No | *NULL* | Khóa chính (Auto Increment) |
| **type** | `character varying(255)` | No | *NULL* | - |
| **notifiable_type** | `character varying(255)` | No | *NULL* | - |
| **notifiable_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng notifiables |
| **data** | `text` | No | *NULL* | - |
| **read_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `order_status_histories`
> **Mô tả**: Lịch sử ghi nhận sự thay đổi trạng thái của đơn hàng.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('order_status_histories_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **order_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `orders` (Đơn hàng) |
| **payment_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng payments |
| **from_status** | `character varying(255)` | Yes | *NULL* | - |
| **to_status** | `character varying(255)` | Yes | *NULL* | - |
| **from_refund_status** | `character varying(255)` | Yes | *NULL* | - |
| **to_refund_status** | `character varying(255)` | Yes | *NULL* | - |
| **action** | `character varying(255)` | No | *NULL* | - |
| **actor_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng actors |
| **actor_role** | `character varying(255)` | Yes | *NULL* | - |
| **note** | `text` | Yes | *NULL* | - |
| **meta_json** | `json` | Yes | *NULL* | Dữ liệu cấu hình hoặc siêu dữ liệu dạng JSON |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `order_id` liên kết tới `orders(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `payment_id` liên kết tới `payments(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `actor_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `orders`
> **Mô tả**: Đơn hàng mua khóa học của học viên.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('orders_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **payment_id** | `integer` | No | *NULL* | Khóa ngoại liên kết tới bảng payments |
| **user_id** | `integer` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `integer` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **instructor_id** | `integer` | Yes | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **course_title** | `character varying(255)` | Yes | *NULL* | - |
| **price** | `integer` | Yes | *NULL* | Giá bán thực tế |
| **status** | `character varying(255)` | No | `'completed'::character varying` | Trạng thái hoạt động/xử lý |
| **refund_status** | `character varying(255)` | No | `'none'::character varying` | - |
| **refund_amount** | `numeric` | No | `'0'::numeric` | - |
| **refund_reason** | `text` | Yes | *NULL* | - |
| **cancel_reason** | `text` | Yes | *NULL* | - |
| **refund_requested_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **refunded_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **refunded_by** | `bigint` | Yes | *NULL* | - |
| **cancelled_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **cancelled_by** | `bigint` | Yes | *NULL* | - |
| **access_revoked_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **paid_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **gross_amount** | `numeric` | Yes | *NULL* | - |
| **net_amount** | `numeric` | Yes | *NULL* | - |
| **platform_amount** | `numeric` | No | `'0'::numeric` | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `partners`
> **Mô tả**: Danh sách đối tác liên kết hiển thị trên trang chủ.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('partners_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **name** | `text` | No | *NULL* | Tên hiển thị |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `password_reset_tokens`
> **Mô tả**: Lưu trữ token phục vụ tính năng đặt lại mật khẩu.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **email** | `character varying(255)` | No | *NULL* | Địa chỉ email |
| **token** | `character varying(255)` | No | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |

---

### Bảng: `payments`
> **Mô tả**: Thông tin chi tiết giao dịch thanh toán của đơn hàng.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('payments_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **transaction_id** | `character varying(255)` | No | *NULL* | Khóa ngoại liên kết tới bảng transactions |
| **name** | `character varying(255)` | Yes | *NULL* | Tên hiển thị |
| **email** | `character varying(255)` | Yes | *NULL* | Địa chỉ email |
| **phone** | `character varying(255)` | Yes | *NULL* | Số điện thoại |
| **address** | `character varying(255)` | Yes | *NULL* | Địa chỉ |
| **cash_delivery** | `character varying(255)` | Yes | *NULL* | - |
| **total_amount** | `character varying(255)` | Yes | *NULL* | - |
| **refunded_amount** | `numeric` | No | `'0'::numeric` | - |
| **refunded_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **refund_reference** | `character varying(255)` | Yes | *NULL* | - |
| **provider_payload** | `json` | Yes | *NULL* | - |
| **provider_status** | `character varying(255)` | Yes | *NULL* | - |
| **payment_type** | `character varying(255)` | Yes | *NULL* | - |
| **invoice_no** | `character varying(255)` | Yes | *NULL* | - |
| **order_date** | `character varying(255)` | Yes | *NULL* | - |
| **order_month** | `character varying(255)` | Yes | *NULL* | - |
| **order_year** | `character varying(255)` | Yes | *NULL* | - |
| **status** | `character varying(255)` | Yes | *NULL* | Trạng thái hoạt động/xử lý |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `payout_requests`
> **Mô tả**: Yêu cầu rút tiền doanh thu của giảng viên gửi lên hệ thống.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('payout_requests_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **instructor_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng instructors |
| **amount** | `numeric` | No | *NULL* | - |
| **bank_name** | `character varying(255)` | No | *NULL* | - |
| **account_number** | `character varying(255)` | No | *NULL* | - |
| **account_name** | `character varying(255)` | No | *NULL* | - |
| **status** | `character varying(255)` | No | `'pending'::character varying` | Trạng thái hoạt động/xử lý |
| **admin_note** | `text` | Yes | *NULL* | - |
| **processed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |
| **transaction_reference** | `character varying(255)` | Yes | *NULL* | - |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `instructor_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `quiz_attempt_answers`
> **Mô tả**: Chi tiết câu trả lời của học viên cho từng câu hỏi trong mỗi lượt làm bài.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('quiz_attempt_answers_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **attempt_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng attempts |
| **question_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng questions |
| **selected_option_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng selected_options |
| **is_correct** | `boolean` | No | `false` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `attempt_id` liên kết tới `quiz_attempts(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `question_id` liên kết tới `quiz_questions(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `selected_option_id` liên kết tới `quiz_options(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `quiz_attempts`
> **Mô tả**: Lịch sử lượt làm bài trắc nghiệm của học viên.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('quiz_attempts_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **quiz_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng quizs |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **score** | `integer` | No | `0` | - |
| **total_questions** | `integer` | No | `0` | - |
| **correct_answers** | `integer` | No | `0` | - |
| **status** | `character varying(20)` | No | `'submitted'::character varying` | Trạng thái hoạt động/xử lý |
| **started_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **submitted_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `quiz_id` liên kết tới `quizzes(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `quiz_options`
> **Mô tả**: Các lựa chọn/phương án trả lời cho mỗi câu hỏi trắc nghiệm.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('quiz_options_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **question_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng questions |
| **option_text** | `text` | No | *NULL* | - |
| **is_correct** | `boolean` | No | `false` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **sort_order** | `integer` | No | `1` | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `question_id` liên kết tới `quiz_questions(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `quiz_questions`
> **Mô tả**: Các câu hỏi trong mỗi bài trắc nghiệm.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('quiz_questions_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **quiz_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng quizs |
| **question_text** | `text` | No | *NULL* | - |
| **question_type** | `character varying(30)` | No | `'single_choice'::character varying` | - |
| **explanation** | `text` | Yes | *NULL* | - |
| **points** | `integer` | No | `1` | - |
| **sort_order** | `integer` | No | `1` | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `quiz_id` liên kết tới `quizzes(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `quizzes`
> **Mô tả**: Các bài trắc nghiệm kiểm tra kiến thức trong khóa học.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('quizzes_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **section_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `course_sections` (Chương học) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **title** | `character varying(255)` | Yes | *NULL* | Tiêu đề |
| **description** | `text` | Yes | *NULL* | Mô tả chi tiết |
| **source_type** | `character varying(20)` | No | `'manual'::character varying` | - |
| **time_limit** | `integer` | Yes | *NULL* | - |
| **passing_score** | `integer` | No | `0` | - |
| **max_attempts** | `integer` | Yes | *NULL* | - |
| **shuffle_questions** | `boolean` | No | `false` | - |
| **show_result_immediately** | `boolean` | No | `true` | - |
| **is_active** | `boolean` | No | `true` | Trường cờ đánh dấu (Boolean: true/false hoặc 1/0) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `section_id` liên kết tới `course_sections(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `refund_requests`
> **Mô tả**: Yêu cầu hoàn tiền từ học viên đối với các khóa học đã mua.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('refund_requests_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **order_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `orders` (Đơn hàng) |
| **payment_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng payments |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **request_source** | `character varying(255)` | No | `'user'::character varying` | - |
| **type** | `character varying(255)` | No | `'refund'::character varying` | - |
| **status** | `character varying(255)` | No | `'pending'::character varying` | Trạng thái hoạt động/xử lý |
| **requested_amount** | `numeric` | Yes | *NULL* | - |
| **approved_amount** | `numeric` | Yes | *NULL* | - |
| **reason** | `text` | Yes | *NULL* | - |
| **admin_note** | `text` | Yes | *NULL* | - |
| **provider_ref** | `character varying(255)` | Yes | *NULL* | - |
| **requested_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **reviewed_by** | `bigint` | Yes | *NULL* | - |
| **reviewed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **processed_by** | `bigint` | Yes | *NULL* | - |
| **processed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |
| **instructor_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng instructors |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `order_id` liên kết tới `orders(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `payment_id` liên kết tới `payments(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `reviewed_by` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `processed_by` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `instructor_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `sessions`
> **Mô tả**: Lưu trữ các phiên làm việc của người dùng (Session driver: database).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `character varying(255)` | No | *NULL* | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **ip_address** | `character varying(45)` | Yes | *NULL* | Địa chỉ IP của người dùng |
| **user_agent** | `text` | Yes | *NULL* | Thông tin trình duyệt và thiết bị của người dùng |
| **payload** | `text` | No | *NULL* | - |
| **last_activity** | `integer` | No | *NULL* | - |

---

### Bảng: `site_infos`
> **Mô tả**: Cấu hình chung của website (Logo, Tên site, Hotline, Email liên hệ, v.v.).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('site_infos_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **meta_title** | `character varying(255)` | Yes | *NULL* | - |
| **copyright** | `character varying(255)` | Yes | *NULL* | - |
| **meta_description** | `character varying(255)` | Yes | *NULL* | - |
| **logo** | `character varying(255)` | Yes | *NULL* | - |
| **favicon** | `character varying(255)` | Yes | *NULL* | - |
| **address** | `character varying(255)` | Yes | *NULL* | Địa chỉ |
| **phone** | `character varying(255)` | Yes | *NULL* | Số điện thoại |
| **mail** | `character varying(255)` | Yes | *NULL* | - |
| **facebook** | `character varying(255)` | Yes | *NULL* | - |
| **twitter** | `character varying(255)` | Yes | *NULL* | - |
| **instagram** | `character varying(255)` | Yes | *NULL* | - |
| **linkedin** | `character varying(255)` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `sliders`
> **Mô tả**: Quản lý các slide hình ảnh trình chiếu ở trang chủ.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('sliders_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **title** | `character varying(255)` | No | *NULL* | Tiêu đề |
| **short_description** | `text` | No | *NULL* | - |
| **video_url** | `character varying(255)` | No | *NULL* | - |
| **image** | `character varying(255)` | No | *NULL* | Đường dẫn ảnh |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `smtps`
> **Mô tả**: Cấu hình gửi email qua giao thức SMTP.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('smtps_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **mailer** | `character varying(255)` | Yes | *NULL* | - |
| **host** | `character varying(255)` | Yes | *NULL* | - |
| **port** | `character varying(255)` | Yes | *NULL* | - |
| **username** | `character varying(255)` | Yes | *NULL* | - |
| **password** | `character varying(255)` | Yes | *NULL* | Mật khẩu đã được mã hóa |
| **encryption** | `character varying(255)` | Yes | *NULL* | - |
| **from_address** | `character varying(255)` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `striipes`
> **Mô tả**: Cấu hình tích hợp cổng thanh toán Stripe.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('striipes_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **publish_key** | `character varying(255)` | Yes | *NULL* | - |
| **secret_key** | `character varying(255)` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `sub_categories`
> **Mô tả**: Danh mục con nằm trong danh mục chính để phân loại chi tiết hơn.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('sub_categories_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **category_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `categories` (Danh mục) |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **slug** | `character varying(255)` | No | *NULL* | Đường dẫn thân thiện (SEO URL friendly) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `category_id` liên kết tới `categories(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `transcript_jobs`
> **Mô tả**: Tiến trình/Job xử lý chuyển đổi video bài giảng thành văn bản (transcription).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('transcript_jobs_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **lecture_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `course_lectures` (Bài học) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **requested_by** | `bigint` | No | *NULL* | - |
| **document_id** | `bigint` | Yes | *NULL* | Khóa ngoại liên kết tới bảng `ai_documents` (Tài liệu) |
| **status** | `character varying(30)` | No | `'queued'::character varying` | Trạng thái hoạt động/xử lý |
| **progress** | `smallint` | No | `'0'::smallint` | - |
| **error_message** | `text` | Yes | *NULL* | - |
| **request_payload** | `json` | Yes | *NULL* | - |
| **response_payload** | `json` | Yes | *NULL* | - |
| **started_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **finished_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `lecture_id` liên kết tới `course_lectures(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `requested_by` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `document_id` liên kết tới `ai_documents(id)` (Cascade/Restrict tùy thuộc migration).

---

### Bảng: `users`
> **Mô tả**: Bảng lưu trữ tất cả người dùng trong hệ thống (Học viên, Giảng viên, Quản trị viên).

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('users_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **first_name** | `character varying(255)` | Yes | *NULL* | - |
| **last_name** | `character varying(255)` | Yes | *NULL* | - |
| **name** | `character varying(255)` | No | *NULL* | Tên hiển thị |
| **email** | `character varying(255)` | No | *NULL* | Địa chỉ email |
| **email_verified_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **password** | `character varying(255)` | No | *NULL* | Mật khẩu đã được mã hóa |
| **photo** | `character varying(255)` | Yes | *NULL* | - |
| **phone** | `character varying(255)` | Yes | *NULL* | Số điện thoại |
| **address** | `character varying(255)` | Yes | *NULL* | Địa chỉ |
| **role** | `character varying(255)` | No | `'user'::character varying` | Vai trò/Quyền hạn |
| **status** | `character varying(255)` | No | `'1'::character varying` | Trạng thái hoạt động/xử lý |
| **instructor_approval_status** | `character varying(255)` | No | `'pending'::character varying` | - |
| **instructor_review_note** | `text` | Yes | *NULL* | - |
| **instructor_reviewed_by** | `bigint` | Yes | *NULL* | - |
| **instructor_reviewed_at** | `timestamp without time zone` | Yes | *NULL* | - |
| **bio** | `text` | Yes | *NULL* | - |
| **day** | `integer` | Yes | *NULL* | - |
| **month** | `integer` | Yes | *NULL* | - |
| **year** | `integer` | Yes | *NULL* | - |
| **city** | `character varying(255)` | Yes | *NULL* | - |
| **country** | `character varying(255)` | Yes | *NULL* | - |
| **experience** | `text` | Yes | *NULL* | - |
| **gender** | `character varying(255)` | No | `'male'::character varying` | - |
| **remember_token** | `character varying(100)` | Yes | *NULL* | - |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

---

### Bảng: `wishlists`
> **Mô tả**: Danh sách khóa học yêu thích/muốn mua sau của học viên.

| Tên Cột | Kiểu Dữ Liệu | Nullable | Mặc Định | Mô Tả Ý Nghĩa |
| --- | --- | --- | --- | --- |
| **id** | `bigint` | No | `nextval('wishlists_id_seq'::regclass)` | Khóa chính (Auto Increment) |
| **user_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `users` (Người dùng) |
| **course_id** | `bigint` | No | *NULL* | Khóa ngoại liên kết tới bảng `courses` (Khóa học) |
| **created_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm tạo bản ghi |
| **updated_at** | `timestamp without time zone` | Yes | *NULL* | Thời điểm cập nhật bản ghi |

**Liên kết khóa ngoại (Foreign Keys):**
- Cột `user_id` liên kết tới `users(id)` (Cascade/Restrict tùy thuộc migration).
- Cột `course_id` liên kết tới `courses(id)` (Cascade/Restrict tùy thuộc migration).

---

