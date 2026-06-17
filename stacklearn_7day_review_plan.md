# Kế hoạch 7 ngày ôn tập bảo vệ đồ án tốt nghiệp - StackLearn

Tài liệu này vạch ra lộ trình học tập, ôn luyện chi tiết từng ngày trong vòng 7 ngày để bạn làm chủ toàn bộ mã nguồn và kiến thức của dự án **StackLearn**. Kế hoạch tập trung sâu vào các tính năng cốt lõi và phức tạp nhất: **Thanh toán**, **Transcript**, **Cloud Storage (R2/S3)**, và **AI Chatbot (RAG)**.

---

## 📅 Tổng quan lộ trình 7 ngày

| Ngày | Chủ đề ôn tập | File mã nguồn trọng tâm |
| :--- | :--- | :--- |
| **Ngày 1** | Cấu trúc Core, Cơ sở dữ liệu & Xác thực | `routes/web.php`, `database_analysis.md`, `bootstrap/app.php` |
| **Ngày 2** | Hệ thống Thương mại & Thanh toán (Stripe & VNPay) | [OrderController.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Http/Controllers/frontend/OrderController.php) / [StripeRepository.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Repositories/StripeRepository.php) / [VnPayRepository.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Repositories/VnPayRepository.php) |
| **Ngày 3** | Hệ thống Hoàn tiền (Refund) & Rút tiền (Payout) | [RefundService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/RefundService.php) / [PayoutService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/PayoutService.php) |
| **Ngày 4** | Trình phát bài học (LMS Player) & Real-time | [LearningController.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Http/Controllers/frontend/LearningController.php) / [routes/channels.php](file:///c:/xampp/htdocs/LMS/StackLearn/routes/channels.php) |
| **Ngày 5** | Cloud Storage & Tải lên tài liệu (Cloudflare R2 / S3) | [filesystems.php](file:///c:/xampp/htdocs/LMS/StackLearn/config/filesystems.php) / [LectureService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/LectureService.php) |
| **Ngày 6** | Trích xuất Phụ đề Video (Transcript Jobs & FFMpeg) | [TranscriptOrchestratorService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/TranscriptOrchestratorService.php) / [OpenAiTranscriptionService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/OpenAiTranscriptionService.php) / [GenerateTranscriptJob.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Jobs/GenerateTranscriptJob.php) |
| **Ngày 7** | Hệ thống AI Tutor & Tìm kiếm ngữ nghĩa (RAG & pgvector) | [AiChatOrchestratorService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/AiChatOrchestratorService.php) / [AiRetrieverService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/AiRetrieverService.php) / [AiEmbeddingService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/AiEmbeddingService.php) |

---

## 🛠️ Chi tiết từng ngày học tập

### Ngày 1: Cấu trúc Core, Database Schema & Authentication
**Mục tiêu**: Nắm được bức tranh toàn cảnh về StackLearn (Laravel 12, PostgreSQL), cách định tuyến (routing), phân quyền (middleware) và luồng hoạt động của DB.

*   **Nội dung cần học**:
    1.  **Cấu trúc thư mục**: Phân nhóm Contr   ollers, Services, Repositories, Jobs, Events.
    2.  **Định tuyến & Middleware**: Cách dùng Middleware `role` để tách biệt các route Admin, Instructor, và User.
    3.  **Database**: Đọc hiểu tài liệu [database_analysis.md](file:///c:/xampp/htdocs/LMS/StackLearn/database_analysis.md) để hiểu 61 bảng. Đặc biệt là các bảng liên quan đến học tập: `courses`, `course_lectures`, `enrollments`, `lesson_progress`.
*   **Mã nguồn cần đọc**:
    *   [routes/web.php](file:///c:/xampp/htdocs/LMS/StackLearn/routes/web.php): Tập trung các route groups có middleware.
    *   [routes/auth.php](file:///c:/xampp/htdocs/LMS/StackLearn/routes/auth.php): Luồng xác thực đăng ký/đăng nhập của Breeze.
    *   [bootstrap/app.php](file:///c:/xampp/htdocs/LMS/StackLearn/bootstrap/app.php): Đăng ký middleware alias và cấu hình chuyển hướng.
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *Làm thế nào để chặn học viên không truy cập được vào trang Instructor hay Admin?*
    *   *Kiến trúc dự án viết theo mẫu gì? (Trả lời: Controller - Service - Repository pattern giúp tách biệt logic nghiệp vụ khỏi database query).*

---

### Ngày 2: Hệ thống Thương mại & Thanh toán (Stripe & VNPay)
**Mục tiêu**: Nắm rõ cách hệ thống xử lý mua khóa học, bảo mật giá cả, tích hợp cổng thanh toán quốc tế (Stripe) và nội địa (VNPay).

*   **Nội dung cần học**:
    1.  **Chống giả mạo giá (Price Tampering Prevention)**: Khi client gửi danh sách ID khóa học lên, server KHÔNG tin giá từ frontend gửi lên mà tự động query DB để tính toán lại giá chuẩn từ cột `selling_price` hoặc `discount_price` (Hàm `getVerifiedOrderData`).
    2.  **Stripe Checkout Flow**:
        *   Tạo Stripe Session với danh sách `line_items` và chuyển hướng người dùng sang Stripe Hosted Checkout.
        *   Stripe trả về URL thành công kèm `session_id`. Server gọi API Stripe để xác thực trạng thái thực sự của giao dịch (`payment_intent`) trước khi lưu DB.
    3.  **VNPay Flow**:
        *   Tạo mã chữ ký HMAC SHA512 từ các tham số giao dịch sắp xếp theo bảng chữ cái.
        *   Chuyển hướng sang VNPay. Khi VNPay callback về `vnpayReturn`, kiểm tra chữ ký SHA512 để xác nhận giao dịch hợp lệ.
    4.  **Cấp quyền (Enrollment)**: Chỉ khi thanh toán thành công mới gọi `EnrollmentService` cấp bản ghi `enrollments` cho học viên.
*   **Mã nguồn cần đọc**:
    *   [OrderController.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Http/Controllers/frontend/OrderController.php): Luồng xử lý thanh toán, callbacks, tạo orders và payments.
    *   [PaymentService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/PaymentService.php): Bộ định tuyến cổng thanh toán.
    *   [StripeRepository.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Repositories/StripeRepository.php): Gọi API Stripe tạo checkout session.
    *   [VnPayRepository.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Repositories/VnPayRepository.php): Tạo URL thanh toán VNPay và kiểm tra chữ ký (verifyResponse).
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *If the user manipulates the course price on the UI, how does the system prevent it?* (Trả lời: Server tự tính toán lại toàn bộ giá dựa trên dữ liệu thật trong DB trước khi gửi tới API thanh toán).
    *   *Tại sao phải dùng Database Transaction trong quá trình lưu Order và Payment?* (Trả lời: Để đảm bảo dữ liệu toàn vẹn. Nếu việc tạo Order hoặc cấp Enrollment bị lỗi giữa chừng, toàn bộ thông tin Payment và Cart sẽ rollback lại trạng thái cũ).

---

### Ngày 3: Hệ thống Hoàn tiền (Refund) & Rút tiền (Payout)
**Mục tiêu**: Hiểu rõ logic nghiệp vụ về tài chính, cách thu hồi quyền học tập khi hoàn tiền và phân phối doanh thu cho giảng viên.

*   **Nội dung cần học**:
    1.  **Yêu cầu hoàn tiền (Refund Request)**: Học viên gửi yêu cầu hoàn tiền khóa học. Khi Admin duyệt:
        *   Chuyển tiền qua cổng API Stripe/VNPay (nếu tự động) hoặc đổi trạng thái hoàn tiền thủ công.
        *   Thu hồi bản ghi học tập (`enrollments->status = 'revoked'`).
        *   Ghi nhật ký hệ thống.
    2.  **Rút tiền (Payout Request)**: Giảng viên gửi yêu cầu rút tiền.
        *   Hệ thống kiểm tra số dư khả dụng từ các đơn hàng thành công sau khi trừ đi phần chia sẻ doanh thu của nền tảng (Platform share %).
        *   Admin duyệt yêu cầu rút tiền và cập nhật số dư.
*   **Mã nguồn cần đọc**:
    *   [RefundService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/RefundService.php): Chứa logic nghiệp vụ xử lý duyệt/từ chối hoàn tiền.
    *   [PayoutService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/PayoutService.php): Tính toán số dư giảng viên và lưu yêu cầu rút tiền.
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *Khi học viên được hoàn tiền thành công, họ có thể vào học tiếp khóa đó không?* (Trả lời: Không, vì trạng thái của enrollment chuyển thành `revoked`, middleware `course.enrollment` sẽ tự chặn).

---

### Ngày 4: Trình phát bài học (LMS Player) & Tương tác Real-time
**Mục tiêu**: Hiểu luồng theo dõi tiến độ bài giảng và cơ chế trao đổi thời gian thực giữa các thành viên.

*   **Nội dung cần học**:
    1.  **Lesson Progress Tracking**: Khi học viên xem video/đọc tài liệu, JavaScript định kỳ gửi ajax cập nhật thời gian đã xem (`watch_seconds`). Khi hoàn thành bài học, cập nhật `lesson_progress` thành `completed` và tính toán lại `%` tiến độ tổng khóa học trong bảng `course_progress`.
    2.  **Real-time Chat & Discussion**: Sử dụng Laravel Reverb (Broadcasting) kết hợp Echo ở frontend để đẩy tin nhắn chat ngay lập tức mà không cần reload trang.
    3.  **Authorization Channels**: Phân quyền kênh private để đảm bảo chỉ những người trong cuộc trò chuyện hoặc đã đăng ký khóa học mới nghe được kênh sự kiện.
*   **Mã nguồn cần đọc**:
    *   [LearningController.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Http/Controllers/frontend/LearningController.php): Load giao diện học bài và lưu tiến độ học tập.
    *   [LearningProgressService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/LearningProgressService.php): Tính toán phần trăm hoàn thành.
    *   [channels.php](file:///c:/xampp/htdocs/LMS/StackLearn/routes/channels.php): Khai báo phân quyền kênh chat realtime (`conversation.{id}`) và bài giảng (`lecture.{id}`).
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *Làm sao để tính ra phần trăm hoàn thành của một khóa học?* (Trả lời: Lấy số bài giảng học viên đã hoàn thành chia cho tổng số bài giảng hiện có của khóa học đó).

---

### Ngày 5: Cloud Storage & Tải lên tài liệu (Cloudflare R2 / S3)
**Mục tiêu**: Nắm rõ giải pháp lưu trữ video/tài liệu bài giảng trên Cloud để giảm tải cho máy chủ local.

*   **Nội dung cần học**:
    1.  **Kiến trúc Cloudflare R2**: R2 sử dụng giao thức chuẩn S3. Stack-configured driver là `s3` trỏ tới Endpoint của Cloudflare R2.
    2.  **Presigned URL (Tải lên trực tiếp từ client lên R2)**:
        *   Thay vì tải file dung lượng lớn lên server Laravel rồi server Laravel up lên Cloud (làm nghẽn băng thông server), server Laravel chỉ tạo một **Presigned URL** (URL có chữ ký bảo mật, sống trong 30 phút).
        *   Frontend dùng URL này đẩy trực tiếp file lên R2 bằng phương thức HTTP PUT.
        *   Khi up xong, frontend gửi lại `key` (đường dẫn file trên R2) cho server để lưu vào database.
    3.  **Tải xuống bảo mật**: Sinh presigned URL tạm thời (hiệu lực 10 phút) để tải tài liệu bài học, ép trình duyệt tải xuống thay vì hiển thị trực tiếp.
*   **Mã nguồn cần đọc**:
    *   [filesystems.php](file:///c:/xampp/htdocs/LMS/StackLearn/config/filesystems.php): Cấu hình disk `r2`.
    *   [LectureService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/LectureService.php): Các hàm `generatePresignedUrl`, `generateDocumentPresignedUrl` và `generateDocumentDownloadUrl`.
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *Tại sao không upload trực tiếp lên thư mục public của server?* (Trả lời: Video bài giảng dung lượng rất lớn, lưu local sẽ nhanh hết ổ cứng server, và tốn tài nguyên server khi stream video. R2/S3 giúp tối ưu chi phí lưu trữ, băng thông và bảo mật hơn).

---

### Ngày 6: Trích xuất Phụ đề Video (Transcript Jobs & FFMpeg)
**Mục tiêu**: Hiểu rõ luồng xử lý nền (Queue) để chuyển giọng nói từ video bài giảng thành văn bản phụ đề (Speech-to-Text).

*   **Nội dung cần học**:
    1.  **Kiến trúc xử lý bất đồng bộ (Queue Jobs)**: Xử lý video tốn rất nhiều thời gian. Khi Instructor yêu cầu làm transcript, server đẩy một Job vào hàng đợi (`jobs` table trong database) rồi báo "Đang xử lý" để giảng viên không phải chờ. Queue Worker sẽ chạy nền để xử lý Job đó.
    2.  **Tiến trình xử lý video native**:
        *   **Bước 1**: Tải file video từ Cloudflare R2 về thư mục tạm ở server local.
        *   **Bước 2**: Sử dụng công cụ **FFMpeg** chạy trên hệ điều hành để bóc tách luồng âm thanh và nén thành chuẩn MP3 mono (16kHz, 32kbps) để giảm tối đa dung lượng.
        *   **Bước 3**: Nếu file MP3 vẫn lớn hơn 24MB, FFMpeg sẽ tự động cắt nhỏ âm thanh thành các đoạn 9 phút.
        *   **Bước 4**: Gửi từng đoạn nhỏ tới API OpenAI Whisper để chuyển thành văn bản.
        *   **Bước 5**: Ghép các chuỗi văn bản lại và lưu vào database.
    3.  **Tiến trình xử lý video YouTube**: Nếu bài học dùng link YouTube, hệ thống gọi API YouTube Transcript hoặc thư viện scraper để lấy phụ đề có sẵn nhanh chóng mà không cần bóc tách âm thanh.
*   **Mã nguồn cần đọc**:
    *   [TranscriptOrchestratorService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/TranscriptOrchestratorService.php): Router dịch vụ (chọn YouTube hay OpenAI Whisper).
    *   [OpenAiTranscriptionService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/OpenAiTranscriptionService.php): Quy trình tải video, chạy FFMpeg, cắt nhỏ file và gọi API OpenAI Whisper.
    *   [GenerateTranscriptJob.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Jobs/GenerateTranscriptJob.php): Job Laravel kế thừa `ShouldQueue` để chạy ngầm.
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *Chuyện gì xảy ra nếu video quá dài khiến API Whisper từ chối (giới hạn 25MB)?* (Trả lời: Hệ thống dùng FFMpeg để tự động nén giảm bitrate, nếu vẫn quá 25MB thì sẽ phân đoạn video thành các chunk 9 phút để xử lý tuần tự rồi nối lại).

---

### Ngày 7: Hệ thống AI Chatbot Tutor & RAG System (Gemini RAG)
**Mục tiêu**: Làm chủ tính năng nổi bật và "ăn điểm" nhất của đồ án: Chatbot trợ lý học tập có ngữ cảnh tài liệu khóa học.

*   **Nội dung cần học**:
    1.  **RAG (Retrieval-Augmented Generation) là gì?**: LLM (như Gemini) không biết nội dung khóa học nội bộ của bạn. RAG giúp tìm kiếm tài liệu liên quan đến câu hỏi của người dùng trước, sau đó nạp tài liệu đó làm ngữ cảnh (context) kèm câu hỏi đưa cho LLM trả lời.
    2.  **Chunking & Embedding (Nạp tri thức)**:
        *   Tài liệu bài học tải lên được chia nhỏ thành các đoạn ngắn (chunks) trong `AiChunkingService`.
        *   Mỗi chunk được chuyển thành vector số thực 768 chiều bằng mô hình `gemini-embedding-2-preview` rồi lưu vào cột `embedding` kiểu dữ liệu `vector` (PostgreSQL pgvector extension).
    3.  **Tìm kiếm lai (Hybrid Semantic Search)**:
        *   Khi học viên hỏi: Chuyển câu hỏi thành vector.
        *   Dùng phép toán khoảng cách cosine (`<=>`) trên PostgreSQL để tìm các chunk tài liệu gần nghĩa nhất với câu hỏi (`searchByVector`).
        *   Nếu không có đủ kết quả chất lượng, server tự động dùng Full-text search (`searchByKeyword` sử dụng `to_tsvector` và `ts_rank` trong PostgreSQL) để bổ trợ.
    4.  **Concept Boosting & Ontology**: Nếu bài học và tài liệu có liên kết các khái niệm cốt lõi (ontology concepts), score của các chunk tài liệu đó sẽ được cộng điểm ưu tiên để đẩy lên làm ngữ cảnh chất lượng hơn.
    5.  **Citations & Chat History**: Trả về câu trả lời kèm các nguồn trích dẫn từ tài liệu nào, đoạn nào và giữ tối đa 8 tin nhắn gần nhất để giữ ngữ cảnh hội thoại.
*   **Mã nguồn cần đọc**:
    *   [AiChatOrchestratorService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/AiChatOrchestratorService.php): Điều phối toàn bộ luồng RAG.
    *   [AiRetrieverService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/AiRetrieverService.php): Chứa truy vấn pgvector và full-text search.
    *   [AiEmbeddingService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/AiEmbeddingService.php): Gọi API Gemini tạo vector.
    *   [GeminiProviderService.php](file:///c:/xampp/htdocs/LMS/StackLearn/app/Services/GeminiProviderService.php): Gửi prompt hoàn chỉnh tới mô hình Gemini để lấy câu trả lời cuối cùng.
*   **Câu hỏi bảo vệ đồ án thường gặp**:
    *   *Làm sao để đảm bảo AI không trả lời linh tinh ngoài nội dung môn học?* (Trả lời: Trong hệ thống có kiểm tra `evidence_strength`. Nếu không tìm thấy thông tin phù hợp trong kho tài liệu với độ tin cậy trên `0.2`, AI sẽ lập tức trả về câu trả lời định sẵn từ chối trả lời).
    *   *pgvector hoạt động như thế nào trong dự án của bạn?* (Trả lời: Sử dụng để tính khoảng cách vector cosine (`1 - (embedding <=> query_vector)`) giữa câu hỏi của học sinh và các đoạn văn bản bài học để tìm các đoạn có ngữ nghĩa tương đồng nhất).

---

## 🎯 Lời khuyên khi đứng trước hội đồng bảo vệ
1.  **Vẽ sẵn sơ đồ luồng (Flowchart)**: Đặc biệt là luồng **RAG Chatbot** và luồng **Presigned URL upload**. Hội đồng thầy cô rất thích sinh viên giải thích sơ đồ trực quan thay vì chỉ lật code.
2.  **Chuẩn bị sẵn dữ liệu demo**: Tạo sẵn một số khóa học, tải lên video, tài liệu, bấm chạy hàng đợi `php artisan queue:listen` để demo chức năng transcript và chatbot chạy mượt mà ngay tại chỗ.
3.  **Tự tin vào giải pháp bảo mật**: Nhấn mạnh việc kiểm tra giá thanh toán trên server và cơ chế kiểm duyệt nội dung tự động/thủ công của giảng viên để chứng tỏ dự án sẵn sàng cho môi trường thực tế.

Chúc bạn ôn tập tốt và đạt kết quả xuất sắc trong buổi bảo vệ đồ án tốt nghiệp!
