# AI, Transcript, Documents, And Realtime Features

This document covers StackLearn AI tutor, transcript generation, document indexing, realtime discussions, and private chat.

## AI Tutor

Main endpoint:

- `POST /chatbot/ask`

Controller:

- `ChatbotController`

Services:

- `ChatSessionService`
- `GeminiChatService`
- `GeminiConfigService`
- `AiChatOrchestratorService`
- `AiRetrieverService`
- `AiPromptBuilderService`

Tables:

- `ai_chat_sessions`
- `ai_chat_messages`
- `ai_message_citations`

Flow:

1. Request validates with `ChatbotAskRequest`.
2. Controller resolves course/lecture context and access.
3. Chat session is created or reused.
4. Retriever finds relevant indexed chunks.
5. Prompt builder prepares context-aware prompt.
6. Gemini provider generates answer.
7. Message and citations are stored.
8. JSON response returns answer, status, and citations.

Rules:

- Do not answer from inaccessible course/lecture context.
- Prefer retrieval-grounded answers.
- If evidence is weak or unavailable, return a clear fallback rather than inventing course facts.
- Store latency/token/provider metadata where available.

## Gemini Configuration

Settings are stored in `gemini_settings` and exposed through:

- `GeminiSetting`
- `GeminiConfigService`
- `GeminiConfigServiceProvider`
- `GeminiProviderService`

Avoid hard-coding model names, API keys, base URL, temperature, or enabled flags.

## Document Indexing

Services:

- `AiDocumentExtractionService`
- `AiChunkingService`
- `AiEmbeddingService`
- `AiDocumentIndexService`
- `OntologyService`

Jobs:

- `ProcessAiDocumentJob`

Tables:

- `ai_documents`
- `ai_document_chunks`
- `concepts`
- `lesson_concepts`
- `document_concepts`

Document flow:

1. `ai_documents` row is created with source type and file metadata.
2. Job or service extracts text from PDF/DOCX/plain text/transcript source.
3. Text is normalized.
4. Text is split into overlapping chunks.
5. Embeddings and metadata are stored in `ai_document_chunks`.
6. Document index status moves to indexed or failed.

Index statuses are pending/processing/indexed/failed style states.

## Transcript Generation

Controller:

- `InstructorTranscriptController`

Services:

- `TranscriptOrchestratorService`
- `YoutubeTranscriptService`
- `OpenAiTranscriptionService`
- `AiDocumentIndexService`

Job:

- `GenerateTranscriptJob`

Table:

- `transcript_jobs`

Supported operations:

- Generate transcript.
- Poll job status.
- Get transcript.
- Update transcript.
- Store manual transcript.
- Delete transcript.
- Reindex transcript.

Flow:

1. Instructor requests transcript for a lecture.
2. System validates instructor/course ownership.
3. `transcript_jobs` row is created.
4. Queue job runs transcript generation.
5. Transcript is stored as AI document/source content.
6. Document is indexed for AI tutor retrieval.

## Realtime Discussion

Event:

- `DiscussionCreated`

Channel:

- `lecture.{lectureId}`

Authorization:

- The channel loads `CourseLecture`.
- User must pass `hasAccessToCourse($lecture->course)`.

Discussion controllers:

- Learner: `LectureDiscussionController`
- Instructor: `InstructorLectureDiscussionController`

Tables:

- `lecture_discussions`

Rules:

- Only enrolled/authorized users should read or post lecture discussions.
- Instructor moderation actions should affect only discussions for owned courses.
- UI should work even if broadcasting is disabled or temporarily unavailable.

## Private Chat

Event:

- `MessageSent`

Channel:

- `conversation.{conversationId}`

Authorization:

- User must be either `student_id` or `instructor_id` for the conversation.

Tables:

- `conversations`
- `messages`

Controller:

- `ChatController`

Routes:

- `GET /chat`
- `GET /chat/conversation/{instructorId}`
- `POST /chat/send/{conversationId}`

## Frontend Realtime Setup

Relevant files:

- `resources/js/echo.js`
- `resources/js/bootstrap.js`
- `resources/js/app.js`

Dependencies:

- `laravel-echo`
- `pusher-js`
- `laravel/reverb`

Check `.env` broadcasting variables before debugging frontend realtime issues.

## Verification Checklist

- Ask AI tutor from an enrolled lecture.
- Ask AI tutor without indexed content and confirm fallback behavior.
- Generate transcript through queue.
- Reindex manual transcript.
- Confirm document chunks are created.
- Subscribe to lecture presence/private channel as enrolled and non-enrolled users.
- Send private chat message between student/instructor.
- Confirm unauthorized users cannot join conversation channel.

