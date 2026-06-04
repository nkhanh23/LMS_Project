# AGENTS.md - StackLearn

Huong dan cho AI agents va contributors khi lam viec trong du an StackLearn.

## Tong Quan Du An

StackLearn la he thong LMS xay dung bang Laravel. Ung dung co ba vung chinh:

- Frontend hoc vien: danh sach khoa hoc, chi tiet khoa hoc, gio hang, wishlist, checkout, hoc bai, quiz, ghi chu, thao luan, AI tutor.
- Backend instructor: quan ly khoa hoc, section, lecture, quiz, coupon, don hang, doanh thu, transcript, thao luan.
- Backend admin: dashboard, danh muc, user/instructor, duyet khoa hoc, moderation, refund, payout, audit log, learning analytics, system health, cau hinh site/mail/Stripe/Google/Gemini.

Stack thuc te theo repository:

- PHP `^8.2`
- Laravel Framework `^12.0`
- Laravel Breeze auth, Blade views
- Vite + Tailwind CSS + Alpine.js
- PostgreSQL mac dinh trong `.env.example`
- Queue driver mac dinh: database
- Broadcasting/Reverb/Echo co mat trong dependency va `routes/channels.php`
- Tich hop: Stripe, VNPay repository, Google/Facebook Socialite, Cloudflare R2/S3, Gemini, OpenAI transcription service, YouTube transcript, PDF parser, Excel export

Neu README va dependency khac nhau, uu tien `composer.json`, `package.json`, migration va code hien tai.

## Lenh Thuong Dung

Chay local:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
composer run dev
```

`composer run dev` chay dong thoi:

- `php artisan serve`
- `php artisan queue:listen --tries=1`
- `npm run dev`

Lenh rieng:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1
php artisan reverb:start
```

Build frontend:

```bash
npm run build
```

Test:

```bash
composer test
php artisan test
```

Format PHP:

```bash
vendor/bin/pint
```

Cache/config sau khi sua `.env`, config, route, view:

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Cau Truc Quan Trong

- `routes/web.php`: tat ca route web chinh cho admin, instructor, user, frontend, checkout, learning, chat.
- `routes/auth.php`: route auth cua Breeze.
- `routes/channels.php`: private/presence channels cho lecture va conversation.
- `bootstrap/app.php`: middleware alias `role`, `instructor.approved`, `course.enrollment`.
- `app/Http/Controllers/backend`: controller admin, instructor, user dashboard va cac workflow backend.
- `app/Http/Controllers/frontend`: controller trang public, learning, checkout, order, wishlist, note, discussion, quiz, chatbot.
- `app/Http/Requests`: validation request. Khi them/sua input, uu tien tao hoac cap nhat Form Request thay vi validate rải rác trong controller.
- `app/Models`: Eloquent models cho LMS domain.
- `app/Repositories`: lop truy xuat du lieu/domain query.
- `app/Services`: lop nghiep vu. Controller nen mong, day logic vao service/repository theo pattern hien co.
- `app/Jobs`: job xu ly transcript va AI document.
- `app/Events`: event realtime discussion/message.
- `resources/views/frontend`: giao dien public va learning.
- `resources/views/backend/admin`: giao dien admin.
- `resources/views/backend/instructor`: giao dien instructor.
- `resources/views/backend/user`: giao dien user dashboard.
- `resources/css/app.css`, `resources/js/app.js`: entry Vite.
- `public`: asset public va upload/public files.
- `database/migrations`: schema chinh cua he thong.
- `database/seeders`: seed data.

## Domain Chinh

Models/domain can luu y:

- Course: `Course`, `CourseGoal`, `CourseSection`, `CourseLecture`, `CourseQualityCheck`
- Enrollment/progress: `Enrollment`, `LessonProgress`, `CourseProgress`
- Commerce: `Cart`, `Wishlist`, `Coupon`, `Order`, `Payment`, `RefundRequest`, `OrderStatusHistory`, `PayoutRequest`
- Quiz: `Quiz`, `QuizQuestion`, `QuizOption`, `QuizAttempt`, `QuizAttemptAnswer`
- Discussion/content quality: `LectureDiscussion`, `LectureNote`, `CourseReviews`, `ContentReport`, `ModerationPolicy`, `ModerationActionTemplate`, `InstructorRiskScore`
- AI tutor/content: `AiChatSession`, `AiChatMessage`, `AiDocument`, `AiDocumentChunk`, `AiMessageCitation`, `Concept`, `TranscriptJob`
- Realtime chat: `Conversation`, `Message`
- Settings: `Smtp`, `Striipe`, `Google`, `GeminiSetting`, `SiteInfo`, `Slider`, `Partner`, `InfoBox`

## Routing Va Middleware

Role routes:

- Admin: prefix `/admin`, name `admin.`, middleware `auth`, `verified`, `role:admin`.
- Instructor: prefix `/instructor`, name `instructor.`, middleware `auth`, `verified`, `role:instructor`, `instructor.approved`.
- User dashboard: prefix `/user`, name `user.`, middleware `auth`, `verified`, `role:user`.
- Learning route: middleware `auth`, `course.enrollment`.

Khi them route:

- Dat vao group dung role/prefix/name.
- Dung route name on dinh de Blade va JS goi bang `route(...)`.
- Khong lap route name. Hien trong `routes/web.php` co mot vai route trung ten/khai bao lap; neu cham vao khu vuc do thi nen don dung pham vi lien quan.

## Quy Uoc Code

- Giu controller mong: validate bang `app/Http/Requests`, xu ly nghiep vu trong `Services`, query/phuc tap hoa trong `Repositories`.
- Uu tien pattern service/repository da co san cho module tuong ung.
- Model relationship, accessor, scope nen nam trong model khi dung lai nhieu noi.
- Khong dua secret, key, token, credential vao repository. Dung `.env` va config/service provider.
- Khong sua file trong `vendor`, `node_modules`, `storage/framework`, cache build, hoac file IDE helper neu khong duoc yeu cau ro.
- Ton trong thay doi dang co trong working tree. Khong revert code khong lien quan.
- Khi them migration, khong sua migration cu da chay tru khi duoc yeu cau. Tao migration moi de thay doi schema.
- Khi them job xu ly dai, dam bao queue database co migration va worker duoc chay.

## Frontend Va Blade

- Ung dung chu yeu dung Blade templates, khong phai SPA.
- Vite entry hien tai: `resources/css/app.css`, `resources/js/app.js`.
- Tailwind scan `resources/views/**/*.blade.php` va pagination vendor views.
- Giu layout theo module:
  - Public: `resources/views/frontend/master.blade.php` va `frontend/section/*`.
  - Admin: `resources/views/backend/admin/master.blade.php`.
  - Instructor: `resources/views/backend/instructor/master.blade.php`.
  - User: `resources/views/backend/user/master.blade.php`.
- Khi sua UI, dung component/partial gan nhat thay vi nhan ban markup lon.
- JavaScript inline trong Blade nen duoc giu gon; voi logic dung lai, dua vao `resources/js`.
- Kiem tra responsive cho trang public, checkout, learning player, dashboard table/form.

## Database Va Data Integrity

Mac dinh `.env.example` dung PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=stacklearn
```

Khi lam viec voi database:

- Dung transaction cho order/payment/refund/enrollment/payout.
- Khong xoa du lieu hoc tap, don hang, payment, audit log neu khong co yeu cau ro.
- Cap nhat status nen ghi history/audit neu module hien co da lam nhu vay.
- Course access phai thong qua enrollment/role middleware, khong chi dua vao id tren request.

## Thanh Toan, Refund, Payout

Module can than:

- Checkout/order: `frontend/CheckoutController`, `frontend/OrderController`, `OrderService`, `PaymentService`, `OrderRepository`
- Stripe: `StripeServiceProvider`, `StripeRepository`, model `Striipe`
- VNPay: `VnPayRepository`
- Refund: `AdminRefundController`, `RefundService`, `RefundRepository`, `RefundRequest`
- Payout: `AdminPayoutController`, `PayoutService`, `PayoutRepository`, `PayoutRequest`

Bat buoc test ky cac case:

- Tao order va payment thanh cong/that bai.
- Ap ma giam gia.
- Hoan tien thu cong/admin approve/reject.
- Enrollment chi tao khi thanh toan hop le.

## AI, Transcript, Documents

Module AI gom:

- Chatbot: `frontend/ChatbotController`, `GeminiChatService`, `AiChatOrchestratorService`
- Retrieval/document: `AiDocument*`, `AiRetrieverService`, `AiEmbeddingService`, `AiDocumentIndexService`
- Transcript: `InstructorTranscriptController`, `TranscriptOrchestratorService`, `GenerateTranscriptJob`, `YoutubeTranscriptService`, `OpenAiTranscriptionService`

Luu y:

- Gemini config co the doc tu DB qua `GeminiSetting` va service provider, khong hard-code key.
- File/PDF/video transcript co the xu ly bat dong bo bang queue.
- Khi thay doi prompt/retrieval/chunking, can test voi lecture/document thuc te va fallback khi khong co citation.

## Realtime, Chat, Discussion

- `routes/channels.php` cap quyen channel `lecture.{lectureId}` theo `hasAccessToCourse`.
- `conversation.{conversationId}` chi cho student/instructor cua conversation.
- Events: `DiscussionCreated`, `MessageSent`.
- Frontend Echo config nam trong `resources/js/echo.js`.

Khi sua realtime:

- Kiem tra auth channel.
- Kiem tra broadcast driver trong `.env`.
- Kiem tra UI fallback neu socket khong ket noi.

## Bao Mat

- Luon validate request bang Form Request neu co input nguoi dung.
- Kiem tra authorization theo role, owner, enrollment, instructor approval.
- Khong tin client-side status/price/course_id.
- Upload file phai validate mime, size, extension, disk va path.
- Presigned URL cho lecture/document phai gioi han theo owner va khoa hoc.
- Chatbot, discussion, review, report nen co throttle/moderation phu hop.
- Khong log secret/API key/payment token.

## Kiem Thu Truoc Khi Ket Thuc Task

Chon muc kiem thu theo pham vi thay doi:

- PHP syntax cho file da sua: `php -l path/to/file.php`
- Unit/feature lien quan: `php artisan test --filter=...`
- Full backend test: `composer test`
- Frontend build khi sua asset/view nhieu: `npm run build`
- Route sanity: `php artisan route:list`
- Migration sanity: `php artisan migrate --pretend` hoac chay tren DB local rieng

Neu khong chay duoc test do thieu DB/service/secret, bao ro trong ket qua va neu co the chay kiem tra nho hon.

## Quy Tac Lam Viec Cho Agents

1. Doc code lien quan truoc khi sua. Dung `rg` de tim controller, route, view, service, repository.
2. Sua dung module, tranh refactor lon ngoai pham vi.
3. Neu them feature moi, di theo luong: route -> request -> controller -> service -> repository/model -> view -> test.
4. Neu sua bug, viet hoac cap nhat test gan bug nhat khi kha thi.
5. Bao ve user data va payment flow hon muc tieu "lam nhanh".
6. Sau khi sua, chay lenh verify hop ly va ghi lai lenh da chay.
7. Khong commit/push neu chua duoc yeu cau.

## File/Thu Muc Nen Tranh Sua

- `.env` tru khi user yeu cau ro.
- `vendor/`, `node_modules/`
- `storage/` tru cac file fixture/test co chu dich.
- `.phpunit.result.cache`
- `_ide_helper.php`, `.phpstorm.meta.php` tru khi dang cap nhat IDE helper co chu dich.
- Output tam nhu `migration_output.txt`, `migration_error.txt` tru khi user yeu cau.

## Checklist Nhanh Khi Them Module Moi

- Migration moi co foreign key/index phu hop.
- Model co `$fillable`/casts/relationships.
- Form Request co authorize/rules/messages neu can.
- Service/repository theo pattern hien co.
- Route nam dung group role.
- Blade view nam dung module layout.
- Authorization owner/enrollment/role day du.
- Test hoac it nhat manual verify duong chinh va duong loi.

