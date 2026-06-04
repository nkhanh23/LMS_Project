# StackLearn - Advanced Learning Management System (LMS)

StackLearn is a robust, feature-rich Learning Management System built with Laravel 12. It features a triple-portal architecture (Admin, Instructor, Learner) and incorporates modern AI-driven tools like automated transcripts and an AI Tutor.

## 🏗️ Architecture

The system follows the **Model-View-Controller (MVC)** pattern provided by Laravel, enhanced with real-time capabilities and AI integrations.

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS + Vite
- **Real-time**: Laravel Reverb (WebSocket) for private messaging and notifications.
- **Database**: Relational Database (MySQL/PostgreSQL supported).
- **AI Engine**: Google Gemini integration for AI Chatbots, automated lecture transcripts, and RAG-based knowledge retrieval.
- **Payments**: Integrated with VnPay and Stripe for secure transactions.
- **Auth**: Laravel Breeze with Socialite (Google & Facebook Login).

---

## 📦 Module List

### 1. 🛡️ Administrative Module
- **Dashboard**: Real-time system health, analytics, and revenue stats.
- **Approvals**: Centralized hub for approving new instructors and reviewing submitted courses.
- **User Management**: Comprehensive control over students and instructors.
- **Finance**: Management of payout requests, refunds, and coupon settings.
- **System Settings**: SMTP, Payment Gateways, and AI API configurations.

### 2. 👨‍🏫 Instructor Module
- **Course Studio**: Drag-and-drop section and lecture management.
- **Assessment Tools**: Quiz builder with multiple-choice questions.
- **AI Tools**: One-click YouTube/Video transcript generation and AI document indexing.
- **Student Interaction**: Discussion boards and real-time private chat.
- **Revenue Dashboard**: Track earnings, view order history, and request payouts.

### 3. 🎓 Learner (User) Module
- **Learning Dashboard**: Track course progress, continue where left off.
- **Course Player**: High-performance video player with sidebar navigation, lecture notes, and discussions.
- **AI Tutor**: RAG-powered chatbot that answers questions based on course content.
- **Discovery**: Advanced course filtering, category browsing, and wishlist management.
- **Assessments**: Take quizzes and view historical performance.

---

## 📂 Folder Explanation

- `app/Http/Controllers/backend`: Handles administrative, instructor-level, and system-wide management logic.
- `app/Http/Controllers/frontend`: Manages learner-facing features, course discovery, and the learning experience.
- `app/Models`: Contains 50+ Eloquent models defining the complex relationships of the LMS.
- `resources/views`: Organized into `backend/` (Admin/Instructor panels) and `frontend/` (Public/Learner views).
- `database/migrations`: Defines the schema for courses, payments, AI sessions, and more.
- `routes/web.php`: The primary router containing role-protected groups for `admin`, `instructor`, and `user`.

---

## 🔄 Feature Flow

### Student Journey
1. **Discovery**: Search courses -> Add to Wishlist/Cart.
2. **Purchase**: Checkout via VnPay/Stripe -> Automated Enrollment.
3. **Learning**: Access Course Player -> Watch Lectures -> Take Notes -> Discuss with Instructor.
4. **Assessment**: Complete Quizzes -> Progress tracking.

### Instructor Journey
1. **Onboarding**: Request Instructor role -> Admin Approval.
2. **Content Creation**: Create Course -> Add Sections -> Upload Lectures/Videos.
3. **AI Enhancement**: Generate AI transcripts -> Index documents for AI Tutor.
4. **Publication**: Submit for Review -> Admin Approval -> Course Published.
5. **Earning**: Manage Orders -> View Revenue -> Request Payout.

---

## 📊 ERD (Logical Entity Relationships)

StackLearn utilizes a highly relational database structure:

- **Identity**: `users` (Roles: Admin, Instructor, Student).
- **Course Content**: `courses` ↔ `categories` | `courses` 1:N `course_sections` 1:N `course_lectures`.
- **Engagement**: `course_lectures` 1:N `lecture_discussions` | `course_lectures` 1:N `lecture_notes`.
- **E-commerce**: `users` 1:N `orders` 1:N `enrollments` N:1 `courses`.
- **Assessment**: `course_lectures` 1:1 `quiz` 1:N `quiz_questions` 1:N `quiz_options`.
- **AI Knowledge**: `ai_chat_sessions` 1:N `ai_chat_messages` | `ai_documents` 1:N `ai_document_chunks`.
- **Finance**: `payout_requests`, `refund_requests`, `coupons`.

---

## ⚡ API Structure (AJAX / Internal)

While primarily a Blade-based app, StackLearn uses several critical AJAX endpoints for its modern UI:

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/chatbot/ask` | `POST` | Interacts with the AI Tutor for course-specific Q&A. |
| `/cart/add` | `POST` | Adds a course to the session-based shopping cart. |
| `/wishlist/add` | `POST` | Toggles course status in the user's wishlist. |
| `/learning/lecture/{id}/progress` | `POST` | Updates student completion progress for a lecture. |
| `/chat/send/{id}` | `POST` | Sends a real-time message via Reverb WebSockets. |
| `/apply-coupon` | `POST` | Validates and applies discount codes during checkout. |

---

## 🛠️ How to Install

1. **Clone the repo**: `git clone <repository-url>`
2. **Install dependencies**: `composer install` & `npm install`
3. **Environment**: Copy `.env.example` to `.env` and configure database & AI keys.
4. **Key & Migrations**: `php artisan key:generate` & `php artisan migrate --seed`
5. **Build Assets**: `npm run build`
6. **Run Server**: `php artisan serve` & `php artisan reverb:start`
