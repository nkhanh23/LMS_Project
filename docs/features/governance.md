# Governance, Moderation, And Quality

This document covers platform governance workflows in StackLearn.

## Main Areas

- Instructor onboarding and suspension.
- Course review and publication.
- Course quality checklist.
- Content reports.
- Admin audit logs.
- Instructor risk score.
- Learning analytics and system health.

## Instructor Governance

Controllers:

- `AdminInstructorRequestController`
- `AdminInstructorController`

Tables:

- `instructor_requests`
- `users`
- `instructor_risk_scores`
- `admin_audit_logs`

Workflow:

1. A user submits an instructor request.
2. Admin reviews request.
3. Admin approves or rejects request.
4. Approved users become instructors and can enter instructor portal after approval.
5. Admin can suspend instructor accounts.

Important user fields:

- `role`
- `status`
- `instructor_approval_status`
- `instructor_reviewed_at`

## Course Governance

Controllers:

- `AdminCourseApprovalController`
- `AdminApprovalCenterController`
- `CourseController` for instructor submission

Services:

- `CourseQualityChecklistService`
- `GovernanceQueueService`
- `AdminAuditLogService`
- `InstructorRiskScoreService`

Tables:

- `courses`
- `course_quality_checks`
- `admin_audit_logs`

Workflow:

1. Instructor creates course content.
2. Instructor submits course for review.
3. Quality checklist is evaluated.
4. Admin publishes, rejects, or hides the course.
5. Audit/risk data is updated where applicable.

Course status fields:

- `approval_status`
- `submitted_for_review_at`
- `reviewed_at`
- `approved_at`

## Content Moderation

Controllers:

- `ContentReportController`
- `AdminModerationController`

Service:

- `ModerationService`

Tables:

- `content_reports`
- `moderation_policies`
- `moderation_action_templates`
- `lecture_discussions`
- `course_reviews`

Report targets:

- Reviews.
- Lecture discussions.

Admin outcomes can include:

- Mark report resolved.
- Hide target content.
- Delete target content.
- Lock course.
- Lock/suspend instructor.
- Record resolution note.
- Update risk/audit data.

## Audit Logs

Controller:

- `AdminAuditLogController`

Service/repository:

- `AdminAuditLogService`
- `AdminAuditLogRepository`

Use audit logs for material admin actions, especially:

- Instructor approval/rejection/suspension.
- Course approval/rejection/hide.
- Refund/manual cancel decisions.
- Payout approval/rejection.
- Moderation resolutions.

## Risk Scores

Service:

- `InstructorRiskScoreService`

Table:

- `instructor_risk_scores`

Inputs include:

- Confirmed reports count.
- Refund requests count.
- Rejected courses count.
- Warnings count.

Risk scores should be recalculated in workflows that change these inputs.

## Analytics And Health

Controllers:

- `AdminLearningAnalyticsController`
- `AdminSystemHealthController`

Services:

- `AdminLearningAnalyticsService`
- `SystemHealthService`

Repositories:

- `AdminLearningAnalyticsRepository`
- `SystemHealthRepository`

Analytics areas:

- Course completion stats.
- User learning stats.
- Summary metrics.

System health areas:

- Queue/job state.
- Recent failures.
- Business workflow alerts.
- Configuration status.

## Safety Rules

- Governance actions should use transactions when multiple tables change.
- Rejections and suspensions should store reasons when the UI/request supports it.
- Never publish a course by only changing a Blade/UI flag; update the persistent course status.
- Never hide/delete user-generated content without preserving report/admin context when moderation flow supports it.

## Verification Checklist

- Submit instructor request and approve/reject it.
- Submit course for review and publish/reject/hide it.
- Resolve report for review/discussion.
- Confirm audit log records are created for key admin actions.
- Confirm instructor risk score changes after relevant moderation/refund/rejection events.

