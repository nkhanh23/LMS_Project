# Admin Features

This document covers the admin portal in StackLearn.

## Route Scope

Admin routes are defined in `routes/web.php` under:

- Prefix: `/admin`
- Name prefix: `admin.`
- Middleware: `auth`, `verified`, `role:admin`

Primary layout:

- `resources/views/backend/admin/master.blade.php`

## Main Controllers

- `AdminController`: login, dashboard, logout.
- `AdminProfileController`: profile and password settings.
- `CategoryController`, `SubcategoryController`: catalog taxonomy.
- `SliderController`, `InfoController`, `PartnerController`, `SiteSettingController`: public site CMS.
- `AdminInstructorController`: instructor list and status.
- `AdminInstructorRequestController`: instructor request approval/rejection and suspension.
- `AdminUserController`: learner account management.
- `AdminCourseController`: admin course list/view.
- `AdminCourseApprovalController`: course publish/reject/hide workflow.
- `AdminApprovalCenterController`: governance queue landing page.
- `AdminModerationController`: content reports.
- `AdminRefundController`: refund approval/manual refund/manual cancel.
- `AdminPayoutController`: payout approval/rejection.
- `BackendOrderController`: admin order management.
- `AdminAuditLogController`: audit log listing.
- `AdminLearningAnalyticsController`: learning analytics.
- `AdminSystemHealthController`: health dashboard.
- `SettingController`: SMTP, Stripe, Google, Gemini settings.

## Dashboard

Services/repositories:

- `AdminDashboardService`
- `AdminDashboardRepository`

Dashboard data includes summary counts, revenue chart data, user growth, top courses, top instructors, alerts, recent activity, and system flow stats.

## User And Instructor Management

Users have a `role` field with `user`, `instructor`, and `admin`.

Instructor onboarding has two layers:

- `instructor_requests`: request submitted by a user.
- `users.instructor_approval_status`: current approval/suspension state.

Admin actions include:

- Approve instructor request.
- Reject instructor request with reason.
- Approve an instructor account.
- Suspend an instructor account.
- Toggle user/instructor active status.

Admin changes in approval workflows should use transactions and audit logs where current code already does.

## Course Approval

Course state is tracked by fields on `courses`:

- `approval_status`
- `submitted_for_review_at`
- `reviewed_at`
- `approved_at`

Related services:

- `CourseQualityChecklistService`
- `InstructorRiskScoreService`
- `AdminAuditLogService`

Admin can:

- View course review queue.
- Inspect course details.
- Publish a submitted course.
- Reject a course.
- Hide a course.

Quality checks are stored in `course_quality_checks`.

## Moderation

Moderation tables:

- `content_reports`
- `moderation_policies`
- `moderation_action_templates`
- `instructor_risk_scores`
- `admin_audit_logs`

Services:

- `ModerationService`
- `GovernanceQueueService`
- `InstructorRiskScoreService`

Report targets include course reviews and lecture discussions. Admin resolution may hide/delete target content, lock a course, lock/suspend instructor state, and record audit context.

## Orders, Refunds, And Payouts

Order management uses:

- `BackendOrderController`
- `OrderService`
- `OrderRepository`

Refund management uses:

- `AdminRefundController`
- `RefundService`
- `RefundRepository`

Payout management uses:

- `AdminPayoutController`
- `AdminPayoutService`
- `AdminPayoutRepository`

Sensitive tables:

- `orders`
- `payments`
- `refund_requests`
- `order_status_histories`
- `payout_requests`

Rules:

- Use DB transactions for state changes.
- Keep refund status, order status, enrollment access, and payment refund metadata consistent.
- Do not approve payout without validating available instructor balance.
- Keep admin audit records for material decisions where the module supports it.

## Settings

Admin settings include:

- Mail/SMTP: `smtps`
- Stripe: `striipes`
- Google OAuth: `googles`
- Gemini: `gemini_settings`
- Site settings: `site_infos`

Provider credentials should not be logged. Prefer config service/provider usage over direct reads spread across controllers.

## Verification Checklist

- `php artisan route:list --name=admin`
- Test role middleware with non-admin account.
- Test approval/rejection branches.
- Test transaction rollback on refund/payout failures.
- Confirm views render with paginated empty state and populated state.

