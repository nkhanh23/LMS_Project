# Learner Features

This document covers the learner-facing experience.

## Route Scope

Learner dashboard routes are defined in `routes/web.php` under:

- Prefix: `/user`
- Name prefix: `user.`
- Middleware: `auth`, `verified`, `role:user`

Learning player routes use:

- Middleware: `auth`, `course.enrollment`

Public course discovery routes are outside the user prefix.

## Main Controllers

Public/frontend:

- `FrontEndDashBoardController`: home, course list, course detail, instructor profile.
- `WishlistController`: wishlist page and AJAX actions.
- `CartController`: cart page and AJAX actions.
- `CheckoutController`: checkout page.
- `OrderController`: payment/order flow.

Learner dashboard:

- `UserController`: dashboard and courses.
- `UserLearningController`: my courses, continue learning, quiz history, AI tutor history.
- `UserProfileController`: profile, password, email settings.
- `UserOrderController`: order list/detail/refund request.

Learning:

- `LearningController`: course player, lecture watch page, lecture data, progress, complete lecture.
- `LectureNoteController`: note CRUD.
- `LectureDiscussionController`: discussion list/create/delete.
- `QuizAttempController`: quiz submission.
- `CourseReviewController`: course review submission.
- `ChatbotController`: AI tutor ask/history.

## Course Discovery

Routes:

- `/`
- `/khoa-hoc`
- `/chi-tiet/{slug}`
- `/giang-vien/{id}`

Features:

- Home page course sections.
- Course listing with filters by category/subcategory.
- Course detail page with reviews, related courses, instructor summary, course content preview.
- Instructor public profile.

Views:

- `resources/views/frontend/index.blade.php`
- `resources/views/frontend/pages/courses`
- `resources/views/frontend/pages/course-details`
- `resources/views/frontend/pages/instructor/profile.blade.php`

Only published/approved courses should be visible to public learners.

## Wishlist And Cart

Wishlist endpoints:

- `GET /wishlist/all`
- `POST /wishlist/add`
- `GET /user/wishlist`
- `GET /user/wishlist-data`
- `DELETE /user/wishlist/{id}`

Cart endpoints:

- `GET /cart`
- `POST /cart/add`
- `GET /cart/all`
- `GET /fetch/cart`
- `POST /remove/cart`

Services/repositories:

- `WishlistService`, `WishlistRepository`
- `CartService`, `CartRepository`
- `ApplyCouponService`, `ApplyCouponRepository`

Tables:

- `wishlists`
- `carts`
- `coupons`

## Learning Access

Learning routes:

- `/khoa-hoc/{slug}/hoc`
- `/khoa-hoc/{slug}/bai-hoc/{lecture_id}`

Protected by `course.enrollment`.

Core service:

- `EnrollmentService`
- `LearningProgressService`

Tables:

- `enrollments`
- `lesson_progress`
- `course_progress`

Access rules:

- A user must have an active enrollment for the course.
- Refunded/revoked enrollments should not grant access.
- Sequential course unlock should respect `courses.content_unlock_mode`.

## Course Player

Views:

- `resources/views/frontend/pages/learning/index.blade.php`
- `resources/views/frontend/pages/learning/video-player.blade.php`
- `resources/views/frontend/pages/learning/video-list.blade.php`
- `resources/views/frontend/pages/learning/partials`

Features:

- Lecture video/document display.
- Lecture list/sidebar.
- Notes panel.
- Discussion/Q&A panel.
- Quiz content.
- AI tutor panel.
- Reviews/overview sections.

AJAX endpoints:

- `GET /learning/lecture/{lecture}/data`
- `POST /learning/lecture/{lecture}/progress`
- `POST /learning/lecture/{lecture}/complete`

## Notes And Discussions

Lecture notes:

- `GET /learning/lecture/{lecture}/notes`
- `POST /learning/notes`
- `PATCH /learning/notes/{id}`
- `DELETE /learning/notes/{id}`

Discussions:

- `GET /lecture/{lecture}/discussions`
- `POST /lecture/discussion`
- `DELETE /lecture/discussion/{discussion}`

Services:

- `LectureNoteService`
- `LectureDiscussionService`

Rules:

- Notes are private to the authenticated user.
- Discussion deletion should be limited to owner or authorized role.
- Discussion list should respect lecture/course access.

## Quiz Attempts

Endpoint:

- `POST /quiz/{quiz}/submit`

Controller:

- `QuizAttempController`

Tables:

- `quizzes`
- `quiz_questions`
- `quiz_options`
- `quiz_attempts`
- `quiz_attempt_answers`

User dashboard exposes quiz history:

- `/user/quiz-history`
- `/user/quiz-history/{attempt}`

## AI Tutor

Endpoints:

- `POST /chatbot/ask`
- `GET /chatbot/history`
- `/user/ai-tutor/history`
- `/user/ai-tutor/history/{session}`

Services:

- `ChatSessionService`
- `GeminiChatService`
- `AiChatOrchestratorService`
- `AiRetrieverService`
- `AiPromptBuilderService`

The AI tutor should only answer from accessible course/lecture context and should cite retrieved document chunks where available.

## Reviews And Reports

Reviews:

- `POST /chi-tiet/{slug}/review`

Reports:

- `POST /reports/reviews/{review}`
- `POST /reports/discussions/{discussion}`

Services:

- `CourseReviewService`
- `ModerationService`

Tables:

- `course_reviews`
- `content_reports`

## Verification Checklist

- Test guest public pages.
- Test enrolled and non-enrolled learning access.
- Test progress update and complete lecture.
- Test notes/discussion CRUD.
- Test quiz submit and history.
- Test AI tutor with accessible and inaccessible lecture context.

