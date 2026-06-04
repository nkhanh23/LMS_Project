# Instructor Features

This document covers the instructor portal in StackLearn.

## Route Scope

Instructor routes are defined in `routes/web.php` under:

- Prefix: `/instructor`
- Name prefix: `instructor.`
- Middleware: `auth`, `verified`, `role:instructor`, `instructor.approved`

Primary layout:

- `resources/views/backend/instructor/master.blade.php`

## Main Controllers

- `InstructorController`: login, dashboard, logout.
- `InstructorProfileController`: profile and password settings.
- `CourseController`: instructor course CRUD and submit for review.
- `CourseSectionController`: course section management.
- `LectureController`: lecture CRUD, file/document presigned URLs, document download.
- `QuizController`: quiz list, quiz edit, quiz store/update.
- `CouponController`: instructor coupons.
- `InstructorOrderController`: instructor order list and exports.
- `InstructorRevenueController`: revenue dashboard and payout request.
- `InstructorLectureDiscussionController`: discussion moderation and replies.
- `InstructorTranscriptController`: transcript generation, manual transcript, reindex.
- `ChatController`: private chat with learners.

## Course Authoring Flow

Typical flow:

1. Instructor creates a course.
2. Instructor adds sections.
3. Instructor adds lectures to sections.
4. Instructor optionally adds quizzes to lectures.
5. Instructor generates or uploads transcript/document content.
6. Instructor submits course for admin review.
7. Admin publishes or rejects the course.

Relevant files:

- `app/Services/CourseService.php`
- `app/Services/LectureService.php`
- `app/Services/QuizService.php`
- `app/Services/CourseQualityChecklistService.php`
- `resources/views/backend/instructor/course`
- `resources/views/backend/instructor/course-section`
- `resources/views/backend/instructor/quiz`

## Lecture Assets

Lecture asset handling is centralized in `LectureService`.

Capabilities:

- Create/update/delete lecture records.
- Generate presigned video upload URLs.
- Generate presigned document upload URLs.
- Delete files from R2/S3-compatible storage.
- Generate protected document download URLs.

Rules:

- Validate file extension and MIME type.
- Ensure the authenticated instructor owns the course before allowing upload or update.
- Keep `course_lectures.storage_disk`, `url`, `file_name`, and `mime_type` consistent.

## Quiz Builder

Quiz workflow:

- Instructor lists lectures/quizzes.
- Instructor edits quiz for a specific lecture.
- `QuizStoreRequest` validates the submitted structure.
- `QuizService::saveManualQuiz` saves quiz, questions, and options in a transaction.

Tables:

- `quizzes`
- `quiz_questions`
- `quiz_options`

Learner attempts use separate attempt tables.

## Transcript And AI Indexing

Controllers/services:

- `InstructorTranscriptController`
- `TranscriptOrchestratorService`
- `GenerateTranscriptJob`
- `YoutubeTranscriptService`
- `OpenAiTranscriptionService`
- `AiDocumentIndexService`

Supported actions:

- Generate transcript for a lecture.
- Check transcript job status.
- Fetch current transcript.
- Update transcript manually.
- Store manual transcript.
- Delete transcript.
- Reindex transcript/document content.

Tables:

- `transcript_jobs`
- `ai_documents`
- `ai_document_chunks`
- `document_concepts`

Long-running transcript generation should stay queue-backed.

## Discussion Management

Instructor can manage lecture discussions:

- List discussions.
- Filter lectures by course.
- View discussion detail.
- Approve/unapprove.
- Delete.
- Reply.

Services:

- `InstructorLectureDiscussionService`
- `LectureDiscussionService`

Tables:

- `lecture_discussions`

## Revenue, Orders, And Payouts

Relevant files:

- `InstructorOrderController`
- `InstructorRevenueController`
- `InstructorSalesService`
- `PayoutService`
- `PayoutRepository`

Features:

- View orders tied to instructor courses.
- Export CSV/Excel.
- View revenue dashboard.
- Request payout.

Tables:

- `orders`
- `payments`
- `payout_requests`

Revenue calculations should use completed, non-refunded access/orders only.

## Private Chat

Private chat uses:

- `ChatController`
- `Conversation`
- `Message`
- `MessageSent` event
- `conversation.{conversationId}` broadcast channel

Access is limited to the student and instructor in the conversation.

## Verification Checklist

- `php artisan route:list --name=instructor`
- Test middleware with unapproved instructor.
- Test course ownership checks for edit/upload/delete.
- Test quiz save with multiple questions/options.
- Test transcript queue path and manual transcript path.
- Test payout request validation against available balance.

