# SYSTEM ARCHITECTURE AUDIT

## Alquds Academy — Laravel Quran Memorisation Platform

---

# 1. Executive Summary

**Current Architecture:** Traditional MVC Laravel 12 application with very early-stage feature-folder modularisation (4 of 41 controllers extracted to `app/Features/`). The codebase follows standard Laravel patterns: Eloquent Active Record models, controller-centric request handling, Blade server-rendered views, Spatie Permission roles/gates, and a thin service layer.

**Main Architectural Problems:**
- **Mixed responsibilities:** `QuranController` (365 lines) mixes web views and JSON API responses in a single class, duplicating logic also present in `QuranApiController`
- **Fat controllers:** `RecordingController` (429 lines) contains raw DB queries, file upload orchestration, validation, notification dispatching, and try/catch error handling — violating Single Responsibility
- **Monolithic reporting:** `SystemReportController` (281 lines, single method) computes all system statistics, alerts, and AI insights in one action
- **Duplicated progress logic:** The same Approved-submissions-grouped-by-surah calculation appears in `StudentDashboardController`, `StudentProfileController::show`, `ReportController::studentReport`, and `StudentApiController::show`
- **Inline authorisation:** 5 Policies exist but many controllers still authorise via inline role checks (`$user->hasRole('super admin')`) scattered across methods
- **No Jobs or Queues:** File uploads and notifications are handled synchronously in controllers — no queue workers
- **No database transactions:** Multi-step operations (student creation, progress record creation, file uploads) are not wrapped in transactions
- **Feature extraction incomplete:** Only 4 of 41 controllers are in the feature-folder pattern; 37 remain in traditional MVC

**Current Project Complexity:** Medium. ~13 models, 26 tables, 41 controllers, 6 services, 5 actions, 5 policies, 2 commands, 3 seeders, ~88 blade views, ~40+ web routes, ~25 API routes. Core domain involves Quran reference data, student/teacher management, circle (halaqah) enrolment, memorisation recording submissions, progress tracking, and reporting.

**Refactoring Safety:** Currently **Low**. Only 14 test files exist — the most comprehensive is `QuranDataTest` (164 lines, 13 data integrity tests). Critical business workflows (submissions, progress, reporting, enrolment) have zero test coverage. No tests could be executed (EPERM on shell). Refactoring without expanding the test suite first carries high risk of regression.

**Recommended Target Architecture:** **Modular MVC (Modular Monolith)** — migrate gradually from traditional MVC to a feature-module structure using the existing `app/Features/StudentProgress` as the reference pattern. Introduce Clean MVC practices (Form Requests, Policies, Actions, DTOs, Repository interfaces) within module boundaries, but only for complex business domains. Keep simple CRUD and reference data in traditional MVC. Do not adopt Full DDD.

**Full Rewrite Required:** **No.** The codebase is functional and follows standard Laravel patterns. The existing feature extraction in `app/Features/` proves that incremental modularisation is feasible. A rewrite would destroy working behaviour with zero benefit.

---

# 2. Actual Project Statistics

| Category | Count | Verification Method |
|---|---|---|
| **Models** | 13 | Static source inspection: `app/Models/` |
| **Controllers (total)** | 41 | Static source inspection |
| ├ Web Controllers | 28 | Under `app/Http/Controllers/` (including Auth) |
| ├ API Controllers | 9 | Under `app/Http/Controllers/Api/` |
| └ Feature Controllers | 4 | Under `app/Features/*/Controllers/` |
| **Migrations** | 26 | Static source inspection: `database/migrations/` |
| **Services** | 6 | Under `app/Services/` (CacheService, JuzProgressService, RecordingBulkImportService, FileUploadService, DomainValidationService, StudentImportService) |
| **Feature Services** | 1 | `StudentProgress/Services/StudentProgressService` |
| **Actions** | 5 | `app/Features/StudentProgress/Actions/*` (Create, Update, Delete) |
| **Form Requests** | 4 | 1 in `app/Http/Requests/` + 3 in Features |
| **Policies** | 5 | Under `app/Policies/` |
| **Middleware** | 3 | Under `app/Http/Middleware/` |
| **Jobs** | 0 | No `app/Jobs/` directory |
| **Events** | 1 | `StudentProgressCreated` (in Feature) |
| **Listeners** | 1 | `NotifyTeacherProgressUpdated` (in Feature) |
| **Commands** | 2 | `AuthCleanCommand`, `CreateStudentImportTemplate` |
| **Seeders** | 3 | `DatabaseSeeder`, `RoleAndPermissionSeeder`, `QuranSeeder` |
| **Blade views** | ~88 | Static glob count of `*.blade.php` in `resources/views/` |
| **Web routes** | ~40+ | Static inspection of `routes/web.php` |
| **API routes** | ~25 | Static inspection of `routes/api.php` |
| **Test files** | 14 | Static glob: 11 in `tests/Feature/` + 1 in `tests/Unit/` + 2 config files |
| **Livewire components** | 0 | No Livewire in `composer.json` or source |
| **Notifications** | 2 feature-based | `NewSubmissionNotification`, `StudentProgressNotification` |
| **API Resources** | 2 feature-based | `StudentProgressResource` (Feature), no `app/Http/Resources/` |
| **DTOs** | 1 | `StudentProgressData` (in Feature) |
| **Repositories** | 2 | `StudentProgressRepositoryInterface` + `EloquentStudentProgressRepository` (in Feature) |
| **View Components** | 2 | `AppLayout`, `GuestLayout` |
| **Dedicated API routes file** | Yes | `routes/api.php` with 25 endpoints under `api/` prefix |

**Counts not executably verifiable (EPERM):**
- Exact number of route entries (some auto-generated by `Route::resource`)
- Whether all blade views compile without error
- Whether all tests pass
- Whether all migrations have been applied
- Exact composer package versions (read from `composer.json` statically)

---

# 3. Current Architecture

## Request Flow

```
HTTP Request
  → public/index.php
    → bootstrap/app.php
      → Service Providers (AppServiceProvider)
        → Middleware (CheckRole, CheckPermission, AutoPermission)
          → Route matched (web.php or api.php)
            → Controller method
              → (optional) Form Request for validation
                → (optional) Policy for authorisation
                  → Eloquent Model queries
                    → (optional) Service class
                      → Blade view rendered (web) OR JSON response (api)
```

## Controllers

41 controllers organised into three groups:

1. **Traditional Web Controllers** (`app/Http/Controllers/`): 28 controllers handling Blade-rendered HTML responses. These are standard Laravel resource controllers with `index`, `create`, `store`, `show`, `edit`, `update`, `destroy` patterns where applicable.

2. **API Controllers** (`app/Http/Controllers/Api/`): 9 controllers returning JSON responses with a `{'success': bool, 'data': ...}` envelope pattern.

3. **Feature Controllers** (`app/Features/*/Controllers/`): 4 controllers (StudentProgress, StudentProgressApi, StudentSubmission, StudentSubmissionApi) that follow the extracted feature-folder pattern with Actions, DTOs, Repositories, and Resources.

**Critical finding:** `QuranController` (web controller at `app/Http/Controllers/QuranController.php`) contains 7 web methods AND 7 API methods (`apiSurahs`, `apiSurahAyahs`, `apiJuz`, `apiJuzAyahs`, `apiStatistics`, `apiSearchSurahs`, `apiSurahJuz`, `apiSurahJuzAyahs`) — mixing concerns in a single file. A separate `QuranApiController` in `app/Http/Controllers/Api/` duplicates some of this logic.

## Models

All 13 models follow Eloquent Active Record pattern. They are generally thin (anemic), with the following exceptions:

- `Circle` (118 lines): Contains business logic methods (`hasCapacity`, `isStudentEnrolled`, `isTeacherAssigned`, `getTeacherAttribute`, `primaryTeacher`)
- `StudentProfile` (114 lines): Contains `getCircleAttribute`, `isEnrolledInCircle`, `getCircleTeacher`
- `Juz` (45 lines): Contains computed attribute `getSurahsBreakdownAttribute` with raw SQL grouping
- `PendingRegistration` (51 lines): Contains domain logic (`hasReachedMaxAttempts`, `isExpired`, query scopes)

The remaining models (Surah, Ayah, Organization, CircleStudent, CircleTeacher) are pure data containers with only relationship definitions.

## Services

6 standalone services in `app/Services/`:

- **`CacheService`**: Cached Quran data retrieval (surahs, juz, ayahs, statistics, search)
- **`JuzProgressService`**: Complex progress calculation engine with hardcoded Juz start boundaries and covered-ayah counting — used across 5+ controllers
- **`RecordingBulkImportService`**: CSV/Excel file parsing and batch recording creation
- **`FileUploadService`**: Audio/image file upload validation and storage
- **`DomainValidationService`**: Cross-entity validation (student belongs to circle, teacher belongs to circle)
- **`StudentImportService`**: Excel/CSV import with PhpSpreadsheet

Plus 1 feature-service: `StudentProgress/Services/StudentProgressService` (thin facade over 3 Actions).

## Actions

5 Actions exist, all within `app/Features/StudentProgress/Actions/`:
- `CreateStudentProgressAction` (23 lines)
- `UpdateStudentProgressAction` (19 lines)
- `DeleteStudentProgressAction` (18 lines)

These are single-method classes with `execute()` that call the Repository. They fire Events on create.

## Views

~88 Blade views across 15+ directories. Uses a tailored Breeze-based layout with:
- `layouts/app.blade.php` — admin panel layout
- `layouts/student.blade.php` — student panel layout  
- `layouts/guest.blade.php` — public/auth layout
- `components/` — 14 Blade components (buttons, inputs, modal, navigation)

## API Routes

25 endpoints in `routes/api.php`, all under `api/` prefix:
- Public: `POST auth/login`, `POST auth/register`
- Authenticated: Quran, Circles, Students, Teachers, Submissions, Progress, Notifications, Dashboard

Additional 10+ API routes defined in `routes/web.php` under `/admin/api/` and `/student/api/` prefixes (within the feature controllers).

## Authentication

Multi-step registration: User submits name/email/password → verification code sent via email → code verified → User + StudentProfile created → logged in. Uses Laravel Breeze scaffolding with custom verification code flow (6-digit code, max 3 attempts, 1-hour expiry, expired record cleanup via `pending:clean` Artisan command).

API authentication uses Laravel Sanctum token-based auth.

## Authorisation

Two-layer system:
1. **Role middleware** (`CheckRole`): Route-level role gating (`role:super admin,admin,teacher`, `role:student,super admin`)
2. **Permission middleware** (`CheckPermission`): Route-name-based permission checking with Spatie
3. **Policies** (5): Gate-based authorisation for Circle, StudentProfile, StudentSubmission, StudentProgress, TeacherProfile

**Gap:** Several controllers bypass policies and use inline checks (e.g., `$user->hasRole('super admin')` in `RecordingController::rate`, `StudentSubmissionController::index`, `CircleController::studentRecordings`).

## Database Access

Exclusively through Eloquent ORM with 4 exceptions:
- `RecordingController::apiSurahs` — raw `DB::table('ayahs')` query for Juz count per surah
- `RecordingController::apiSearchSurahs` — same raw query duplicated
- `SystemReportController::index` — raw `DATE_FORMAT` and `GROUP BY` queries
- `Juz::getSurahsBreakdownAttribute` — raw `selectRaw` query with aggregations

## Business Logic Placement

Business logic is spread across four layers inconsistently:

| Logic Type | Typical Location |
|---|---|
| Simple CRUD | Controller → Eloquent Model |
| Validation | Controller inline `$request->validate()` or Form Request |
| Authorisation | Controller inline `$user->hasRole()` or Policy |
| Progress calculation | `JuzProgressService` (static methods) |
| File handling | `FileUploadService` (static methods) |
| Domain validation | `DomainValidationService` (static methods) |
| Reporting calculations | `SystemReportController::index` (monolithic method) |
| Student progress creation | Feature: Action → Event → Listener → Notification |

## Classification

**Traditional MVC with partial modularisation.** The project started as standard Laravel MVC and 4 controllers have been extracted into a feature-folder structure. The codebase is in transition — 90% traditional MVC, 10% modular. There is no service layer for most controllers. Business logic lives primarily in controllers and static service methods.

---

# 4. Existing Business Modules

| Module | Main Models | Controllers | Tables | Complexity | Coupling | Recommended Architecture |
|---|---|---|---|---|---|---|
| **Quran (Reference)** | Surah, Ayah, Juz (3) | QuranController (365L), QuranApiController (150L) | `surahs`, `ayahs`, `juz` | Low — static reference data with read-only queries | Low — Quran models are referenced by StudentSubmission, StudentProgress, Circle | **Stay Simple MVC** with `CacheService`; no DDD needed |
| **Students** | StudentProfile, User (2) | StudentProfileController (268L), StudentImportController (90L), StudentApiController (120L) | `student_profiles`, `users` | Medium — CRUD + file import + automated User creation | High — referenced by Circles, Submissions, Progress, Reports | **Modular MVC** with Student module |
| **Teachers** | TeacherProfile, User (2) | TeacherProfileController (134L), TeacherApiController (59L) | `teacher_profiles`, `users` | Low — simple CRUD | Medium — linked to Circles (pivot), Students, Progress | **Stay Simple MVC** |
| **Circles (Halaqat)** | Circle, CircleTeacher, CircleStudent (3) | CircleController (164L), CircleTeacherController (80L), CircleStudentController (75L), CircleApiController (100L) | `circles`, `circle_teachers`, `circle_students` | Medium — CRUD + enrolment + capacity management + progress calculation | High — links Teachers, Students, Organizations, Juz; used by Submissions, Progress, Reports | **Modular MVC** — core business boundary |
| **Organizations** | Organization (1) | OrganizationController (59L) | `organizations` | Low — simple CRUD | Low — only referenced by Circle | **Stay Simple MVC** |
| **Submissions (Recordings)** | StudentSubmission (1) | RecordingController (429L), SubmissionApiController (220L), StudentSubmissionController (Feature, 188L), StudentSubmissionApiController (Feature) | `student_submissions` | High — file upload, validation, review workflow, scoring, notifications, bulk import | High — references Student, Circle, Surah, Juz, Teacher | **Modular MVC with Actions/Events** — prime extraction candidate |
| **Memorisation Progress** | StudentProgress (1) | StudentProgressController (Feature, 125L), StudentProgressApiController, ProgressApiController (91L) | `student_progress` | Medium — CRUD + progress tracking from submissions + notifications | Medium — references Circle, Student, Teacher, Surah, Juz | **Already Modularised** — reference pattern |
| **Reports & Analytics** | — | SystemReportController (281L), ReportController (119L), DashboardApiController (54L) | — (queried from other tables) | High — complex aggregations across 6+ tables, AI insights, trend analysis | Highest — touches every module | **Extract into dedicated Report module** with query services |
| **Roles & Permissions** | — (Spatie) | RoleController (65L), UserRoleController (37L) | Spatie tables (6) | Low — Spatie configuration | Low — used by Middleware and Policies | **Stay Simple MVC** — framework concern |
| **Authentication** | PendingRegistration, User (2) | Auth controllers (8) | `pending_registrations`, `users` | Medium — verification code flow with expiry and attempt limits | Low — standalone feature | **Stay Simple MVC** — framework concern |
| **Notifications** | — (Laravel notifications) | NotificationApiController (52L) | `notifications` | Low — standard Laravel notifications | Low — used by Submissions and Progress events | **Stay Simple MVC** — infrastructure concern |
| **Student Dashboard** | — | StudentDashboardController (185L) | — (aggregates from other tables) | Medium — aggregates submissions, progress, circles, available circles for a student | High — touches Student, Circle, Submission, Progress, Juz | **Keep as thin orchestrator** to feature modules |

---

# 5. Controller Analysis

## All 41 Controllers — Detailed Breakdown

| Controller | Lines | Method Count | Responsibilities | Main Problems | Risk | Recommended Refactor |
|---|---:|---:|---|---|---|---|
| **RecordingController** | 429 | 13 | Upload page, Ajax endpoints (surahs, juz, ayahs), store recording, rate, delete, bulk import, download template | Raw SQL queries (duplicated), validation inline, sync notification, file upload + DB write + notification in single method, mixed web pages + JSON API | HIGH | Extract: store → Action, bulk import → Service method, API endpoints → dedicated API controller, queries → Query class |
| **QuranController** | 365 | 15 | Web: index, showSurah, indexJuz, showJuz, search, statistics, showAyah. **API**: apiSurahs, apiSurahAyahs, apiJuz, apiJuzAyahs, apiStatistics, apiSearchSurahs, apiSurahJuz, apiSurahJuzAyahs | Web + API mixed in single controller. API methods duplicate `QuranApiController`. Inline data transformation in every method | HIGH | Separate: keep web methods only, remove all api* methods (already exist in QuranApiController) |
| **SystemReportController** | 281 | 1 | Monolithic system report with general stats, circles report, students report, teachers report, submissions report, issues/alerts, AI insights | Single 281-line method. Does not use Services or Query classes. Duplicates queries found in other controllers | HIGH | Extract: one service per report section, one query class per aggregation |
| **StudentProfileController** | 268 | 7 | CRUD + student creation with user creation + progress record creation | Creates User, StudentProfile, AND StudentProgress in single method. Photo upload inline. No transaction wrapping | MEDIUM | Extract: store → Action with DB transaction, separate User creation from Student creation |
| **SubmissionApiController** | 220 | 5 | API CRUD for submissions + review workflow | Repeats store logic from RecordingController::store. Inline file uploads | MEDIUM | Extract: store → reusable Action, review → Action with Policy enforcement |
| **StudentDashboardController** | 185 | 8 | Dashboard, submissions page, upload form, recordings list, circles page, progress page, join circle | Duplicates progress calculation from StudentProfileController and ReportController. joinCircle has business logic (capacity check, duplicate check) | MEDIUM | Extract: joinCircle → dedicated Action, progress calculation → shared Service |
| **StudentSubmissionController (Feature)** | 188 | 6 | List, create, store, review, updateReview, download | Store method is 60 lines with file upload, notification, role branching. Notification uses `->first()` teacher instead of all teachers | MEDIUM | Extract: store → Action, notification dispatch → Event/Listener |
| **CircleController** | 164 | 9 | CRUD + show with progress calculation + studentRecordings | show method loads heavy calculations (progress per student, available teachers/students). Schema::hasColumn check indicates incomplete migration | MEDIUM | Extract: show data → Query class, progress calculation → Service |
| **QuranApiController** | 150 | 9 | API: surahs, surah, surahAyahs, juzList, juz, juzAyahs, statistics, search | Uses CacheService (good). Search returns ayah_count directly from DB (not cached). Some duplication with QuranController API methods | LOW | Keep as is; add caching for search results |
| **TeacherProfileController** | 134 | 6 | CRUD + user creation | Creates User and assigns role inline. Photo upload inline | LOW | Extract: store → Action |
| **StudentProgressController (Feature)** | 125 | 7 | CRUD + studentView | Constructor injection of Service + Repository (good pattern). studentView queries Progress directly (bypasses Repository) | LOW | Move studentView query to Repository |
| **StudentApiController** | 120 | 4 | API: index, show, progress, submissions | Duplicates progress-by-surah calculation (same pattern as StudentProfileController, StudentDashboardController, ReportController) | MEDIUM | Extract shared progress calculation |
| **ReportController** | 119 | 5 | Student, teacher, circle, organization reports, index | studentReport duplicates progress calculation. teacherReport manually joins CircleTeacher → circle_ids → StudentProfile → submissions | MEDIUM | Extract: report query classes per entity |
| **AuthController (Api)** | 111 | 4 | Login, register, logout, me | Standard Sanctum auth. Login returns permissions list. Register assigns role inline | LOW | Keep as is |
| **CircleApiController** | 100 | 5 | API: index, show, students, teachers | Thin API wrapper. Uses Policy for show/students/teachers (good) | LOW | Keep as is |
| **ExecutiveDashboardController** | 93 | 2 | Dashboard + refreshCache | Caches entire dashboard result for 300s. Top students query is expensive (subquery with AVG) | LOW | Extract expensive queries to Services |
| **StudentImportController** | 90 | 3 | Show import page, process import, download template | Delegates to StudentImportService (good). File cleanup in try/catch (good) | LOW | Keep as is |
| **VerifyCodeController** | 89 | 2 | Show verification form, verify code | Complex workflow with DB transaction for User+StudentProfile creation. Max attempt enforcement | LOW | Keep as is |
| **CircleTeacherController** | 80 | 3 | Add teacher to circle, update, remove | Batch add with duplicate detection. Pivot model updates | LOW | Keep as is |
| **CircleStudentController** | 75 | 3 | Add student to circle, update, remove | Schema::hasColumn check indicates migration issue | LOW | Keep as is; fix migration |
| **RoleController** | 65 | 4 | CRUD for roles + permissions | Spatie wrapper | LOW | Keep as is |
| **RegisteredUserController** | 66 | 2 | Show registration form, store | Creates PendingRegistration with verification code, sends email | LOW | Keep as is |
| **ProfileController** | 60 | 3 | Edit, update, delete profile | Standard Breeze profile handling | LOW | Keep as is |
| **OrganizationController** | 59 | 6 | CRUD | Standard resource controller | LOW | Keep as is |
| **TeacherApiController** | 59 | 2 | Index, show | Thin API wrapper | LOW | Keep as is |
| **DashboardApiController** | 54 | 2 | Stats, recentActivities | Cached stats (good) | LOW | Keep as is |
| **NotificationApiController** | 52 | 4 | Index, markAsRead, markAllAsRead, unreadCount | Standard notification handling | LOW | Keep as is |
| **UserRoleController** | 37 | 2 | Index users, change role | Thin wrapper | LOW | Keep as is |
| **ProgressApiController** | 91 | 2 | Index, store progress API | Verifies student belongs to circle inline | LOW | Move validation to Policy |
| **StudentProgressApiController (Feature)** | — | — | API progress endpoints | (in Features) | LOW | Keep as is |
| **StudentSubmissionApiController (Feature)** | — | — | API submission endpoints | (in Features) | LOW | Keep as is |
| **Auth controllers (8)** | various | — | Login, password reset, email verification, confirm password | Standard Breeze auth controllers | LOW | Keep as is |

## Key Patterns Identified

**Controllers over 200 lines (fat controllers):**
1. RecordingController — 429 lines
2. QuranController — 365 lines
3. SystemReportController — 281 lines
4. StudentProfileController — 268 lines
5. SubmissionApiController — 220 lines

**Methods over 40 lines:**
- `SystemReportController::index` — 281 lines (entire method)
- `RecordingController::store` — ~84 lines
- `RecordingController::apiSurahJuzAyahs` — ~37 lines
- `StudentProfileController::store` — ~96 lines
- `StudentProfileController::update` — ~70 lines
- `StudentSubmissionController::store` (Feature) — ~60 lines
- `StudentSubmissionController::index` (Feature) — ~45 lines
- `CircleController::show` — ~55 lines
- `SubmissionApiController::store` — ~64 lines
- `SubmissionApiController::review` — ~51 lines

**Web + API logic mixed:**
- `QuranController` — 7 web + 8 API methods in one file

**Validation inside controllers (not using Form Requests):**
- `RecordingController::store`, `RecordingController::rate`
- `CircleController::store`, `CircleController::update`
- `OrganizationController::store`, `OrganizationController::update`
- `RoleController::store`
- `StudentProfileController::store`, `StudentProfileController::update`
- `TeacherProfileController::store`, `TeacherProfileController::update`
- `CircleTeacherController::store`, `CircleTeacherController::update`
- `CircleStudentController::store`, `CircleStudentController::update`
- Many more

**Raw database queries:**
- `RecordingController::apiSurahs` — `DB::table('ayahs')...selectRaw`
- `RecordingController::apiSearchSurahs` — duplicate of above
- `SystemReportController::index` — `DATE_FORMAT`, `DATE()`, `GROUP BY`
- `Juz::getSurahsBreakdownAttribute` — `selectRaw`, `GROUP BY`

**Incomplete Form Request usage:** Only 4 Form Requests exist. The majority of controllers call `$request->validate()` inline.

---

# 6. Model Analysis

| Model | Lines | Relations | Business Logic | Coupling | Risk | Recommendation |
|---|---:|---:|---|---|---|---|
| **Circle** | 118 | 9 relations (BelongsTo: org, juz; HasMany: circleTeachers, circleStudents, studentProgresses, submissions; BelongsToMany: students, teachers; custom: primaryTeacher) | `hasCapacity()`, `isStudentEnrolled()`, `isTeacherAssigned()`, `getTeacherAttribute` | HIGH — references 7 other models | CORE | Keep domain methods; extract capacity into a service if rules become complex |
| **StudentProfile** | 114 | 7 relations (BelongsTo: user, teacher; HasMany: circles, progresses, progressRecords, submissions; HasOne: latestProgress) | `getCircleAttribute()`, `isEnrolledInCircle()`, `getCircleTeacher()`, `getProgressPercentAttribute()` | HIGH — central entity used by 8+ controllers | CORE | Keep domain methods; consider extracting enrolment to dedicated service |
| **StudentSubmission** | 74 | 5 relations + 2 accessors | `getSurahDisplayAttribute()`, `getJuzDisplayAttribute()` | HIGH — references Student, Circle, Surah, Juz, Teacher (reviewer) | HIGH — many controllers write directly | Keep; add casts for score/rating |
| **User** | 62 | 2 relations + 4 role helpers | `isSuperAdmin()`, `isAdmin()`, `isTeacher()`, `isStudent()` | MEDIUM — referenced everywhere via auth() | — | Keep; standard Laravel |
| **StudentProgress** | 54 | 6 relations | None (pure data container) | MEDIUM | — | Keep; consider adding domain methods for progress status |
| **PendingRegistration** | 51 | 0 | `hasReachedMaxAttempts()`, `isExpired()`, scopes: `expired()`, `valid()` | LOW — standalone | LOW | Keep; well-designed domain logic |
| **Juz** | 45 | 1 HasMany + 1 computed attribute | `getSurahsBreakdownAttribute()` with raw SQL | LOW — reference data | LOW | Move raw SQL to a query class; keep attribute as cached wrapper |
| **CircleStudent** | 38 | 2 relations + `primaryTeacher()` | `primaryTeacher()` walks circle→teachers→pivot | MEDIUM — pivot with behaviour | LOW | Keep |
| **CircleTeacher** | 26 | 2 relations | None | LOW — pure pivot | LOW | Keep |
| **Surah** | 21 | 1 HasMany | None | LOW — pure reference data | LOW | Keep anemic; add no logic |
| **Organization** | 19 | 1 HasMany | None | LOW — pure CRUD | LOW | Keep anemic |
| **Ayah** | 29 | 2 BelongsTo | None | LOW — pure reference data | LOW | Keep anemic |
| **TeacherProfile** | 55 | 5 relations | None | MEDIUM | LOW | Keep |

## Key Model Findings

**Fat models:** None. All models are thin (under 120 lines). `Circle` has the most business logic at 118 lines.

**Anemic models:** Surah (21L), Ayah (29L), Organization (19L), CircleTeacher (26L), CircleStudent (38L) — these are pure Active Record containers. This is acceptable for reference data and pivot tables.

**Models with too many relationships:** `Circle` (9 relations), `StudentProfile` (7 relations). This indicates these are central hub entities, which is natural for a domain like halaqah management.

**Query scopes:** Only `PendingRegistration` has custom scopes (`expired`, `valid`).

**Accessors:** Only `StudentSubmission` has accessors (`getSurahDisplayAttribute`, `getJuzDisplayAttribute`). These handle denormalised text fields with fallback to relations.

**Casts:** `PendingRegistration` (age, attempts, last_sent_at, expires_at), `User` (email_verified_at, password), `CircleStudent` (joined_at).

**Boot logic:** None of the models use `boot()` or model events.

**Domain rules:** Found in `Circle::hasCapacity()`, `Circle::isStudentEnrolled()`, `StudentProfile::isEnrolledInCircle()`, `PendingRegistration::hasReachedMaxAttempts()`.

**N+1 risks:** 
- `CircleController::show` eager-loads everything but the `foreach ($circle->circleStudents as $cs)` loop could trigger additional queries when accessing `$cs->student`
- `SystemReportController::index` loads circle progress in a loop over all circles
- `StudentProgressController::index` (Feature) loops over `circle->circleStudents` to calculate per-student progress

**Mass assignment:** All models use `$fillable` (safe). None use `$guarded`.

---

# 7. Database Analysis

## Main Tables (26 migrations)

| Table | Module | Foreign Keys | Key Indexes | Unique Constraints |
|---|---|---|---|---|
| `users` | Auth | — | email | email unique |
| `surahs` | Quran | — | id (PK) | — |
| `ayahs` | Quran | surah_id → surahs, juz_id → juz | surah_id, juz_id, (surah_id, ayah_number) composite | — |
| `juz` | Quran | — | id (PK) | — |
| `student_profiles` | Students | user_id → users, teacher_id → teacher_profiles | user_id, teacher_id, status | — |
| `teacher_profiles` | Teachers | user_id → users | user_id | — |
| `organizations` | Organizations | — | — | — |
| `circles` | Circles | organization_id → organizations, juz_id → juz | organization_id, status, juz_id | — |
| `circle_teachers` | Circles (pivot) | circle_id → circles, teacher_id → teacher_profiles | circle_id, teacher_id | (circle_id, teacher_id) unique? |
| `circle_students` | Circles (pivot) | circle_id → circles, student_id → student_profiles | circle_id, student_id, status | — |
| `student_progress` | Memorisation | student_id → student_profiles, circle_id → circles, surah_id → surahs, juz_id → juz, teacher_id → teacher_profiles, created_by → users | student_id, circle_id, juz_id | — |
| `student_submissions` | Submissions | student_id → student_profiles, circle_id → circles, surah_id → surahs, juz_id → juz, reviewed_by → teacher_profiles | student_id, circle_id, surah_id, juz_id, status | — |
| `pending_registrations` | Auth | — | email, expires_at | — |
| `notifications` | Laravel | — | notifiable_type, notifiable_id | — |
| Spatie permissions (6) | Roles | — | — | Spatie conventions |
| `cache` | Laravel | — | key | key unique |
| `jobs` | Laravel | — | queue | — |
| `add_missing_foreign_keys` (patching) | Cross-cutting | Various FK additions | — | — |
| `add_performance_indexes` (patching) | Cross-cutting | — | Various composite indexes | — |
| Additional patch migrations | Various | surah_id, juz_id, score, recording fields, etc. | — | — |

## Quran Dataset Facts

| Metric | Value | Verified |
|---|---|---|
| Surahs | 114 | QuranDataTest |
| Juz | 30 | QuranDataTest |
| Numbered ayahs (ayah_number > 0) | 6,236 | QuranDataTest |
| Basmala records (ayah_number = 0) | 112 | QuranDataTest |
| Total ayah records | 6,348 | QuranDataTest |

## Schema Observations

**Strong foreign key integrity:** Most tables have proper FK constraints. A migration `add_missing_foreign_keys` suggests some were added retrospectively.

**Performance indexes:** Dedicated migration `add_performance_indexes` adds composite indexes, indicating production profiling.

**Nullable foreign keys:**
- `student_profiles.teacher_id` — nullable (student may not have a teacher)
- `circles.organization_id` — nullable (circle may not belong to an organization)
- `circles.juz_id` — nullable (circle may not be linked to a specific juz)
- `student_progress.circle_id` — nullable (made nullable in a patch migration)

**Denormalised data:** `student_submissions` stores `surah` (text), `ayah` (text), `juz` (text) in addition to FK columns `surah_id`, `juz_id`. This is a dual-write pattern where both the FK and the text name are stored. The accessors `getSurahDisplayAttribute` and `getJuzDisplayAttribute` select between them.

**Business boundaries visible in schema:**
- Quran reference data (surahs, ayahs, juz) is clearly separated
- Core domain tables (circles, circle_teachers, circle_students) are tightly connected
- Submissions and Progress cross-reference everything (student, circle, surah, juz, teacher)
- No payments/finance tables exist
- No dedicated exam or attendance tables

**High-coupling tables:**
- `student_submissions` — FKs to 5 other tables (student, circle, surah, juz, teacher)
- `student_progress` — FKs to 5 other tables (student, circle, surah, juz, teacher, user)
- `circles` — FKs to 2 tables + used by 4 other tables as FK source

---

# 8. Business Process Analysis

| Process | Current Files | Complexity | Transaction Needed | Domain Candidate | Risk |
|---|---|---|---|---|---|
| **Display a surah** | QuranController::showSurah, Ayah query, Log | LOW | No | No — pure read | LOW |
| **Display a juz** | QuranController::showJuz, Juz query with sub-query | LOW | No | No — pure read | LOW |
| **Student enrolment (web)** | StudentProfileController::store (creates User + StudentProfile + StudentProgress) | MEDIUM | **Yes** — 3 writes (User, StudentProfile, StudentProgress) + file upload | Yes — CreateStudentEnrolmentAction | MEDIUM |
| **Student import (bulk)** | StudentImportController + StudentImportService | MEDIUM | **Yes** — batch creates StudentProfile + StudentProgress per row | Yes — already has a service | MEDIUM |
| **Add student to circle** | CircleStudentController::store | LOW | No — single insert | No — simple pivot | LOW |
| **Add teacher to circle** | CircleTeacherController::store | LOW | No — batch insert with duplicate check | No — simple pivot | LOW |
| **Join circle (student self-service)** | StudentDashboardController::joinCircle | MEDIUM | **Yes** — capacity check + duplicate check + insert | Yes | LOW |
| **Record submission (student)** | RecordingController::store / SubmissionApiController::store / StudentSubmissionController::store (Feature) | HIGH — file upload + validation + DB insert + notification | **Yes** — file storage + DB insert + notification | **Yes — CreateSubmissionAction** | HIGH (3 implementations of same process) |
| **Review submission (teacher)** | SubmissionApiController::review / StudentSubmissionController::updateReview (Feature) | HIGH — score, rating, status, notes, authorization | **Yes** — DB update + notification | **Yes — ReviewSubmissionAction** | MEDIUM (2 implementations) |
| **Bulk recording import** | RecordingController::bulkImport + RecordingBulkImportService | MEDIUM | **Yes** — multiple DB inserts | Yes — already has a service | LOW |
| **Progress calculation** | JuzProgressService::calculate (used by 5+ controllers) | HIGH — surah ranges, coverage tracking, caching | No — pure computation | Yes — already extracted as Service | MEDIUM (IC担心 duplicated across 5 callers) |
| **View student progress** | StudentProgressController (Feature), StudentDashboardController, StudentApiController, ReportController | MEDIUM | No | Yes — already has module | LOW |
| **Create progress record (manual)** | StudentProgressController::store (Feature) | LOW | **Yes** — insert + event + notification | Yes — already has Action | LOW |
| **System report generation** | SystemReportController::index | HIGH — 8+ aggregate computations, issue detection, AI insights | No (read-only) | Yes — ReportQueryService | HIGH — monolithic |
| **Student report per report** | ReportController::studentReport | MEDIUM | No (read-only) | Yes — ReportQueryService | MEDIUM (duplicates progress calc) |
| **Register new user** | RegisteredUserController + VerifyCodeController | MEDIUM — verification code flow | **Yes** — User + StudentProfile in transaction | No — standard auth | LOW |
| **Change user role** | UserRoleController::change | LOW | No | No — Spatie management | LOW |
| **Phone verification flow** | PendingRegistration model + controllers | MEDIUM — attempts tracking, expiry, code generation | No (single writes) | No — standard pattern | LOW |
| **Dashboard stats** | ExecutiveDashboardController + DashboardApiController | MEDIUM | No (read-only with cache) | Yes — DashboardQueryService | LOW |

---

# 9. Coupling and Dependency Analysis

## Dependency Map

```
Quran Reference Data (Surah, Ayah, Juz)
 ├── Circle (via juz_id FK)
 ├── StudentSubmission (via surah_id, juz_id FKs)
 ├── StudentProgress (via surah_id, juz_id FKs)
 ├── JuzProgressService (hardcoded Juz start boundaries)
 ├── QuranController / QuranApiController
 ├── RecordingController (4 API methods + store)
 └── StudentImportService (Surah/Juz lookup)

Students (User, StudentProfile)
 ├── Circles (via circle_students pivot)
 ├── Teachers (via teacher_id FK, circle_teachers)
 ├── Submissions (via student_id FK)
 ├── Progress (via student_id FK)
 ├── Reports (SystemReportController, ReportController)
 ├── Auth controllers
 └── Every dashboard

Teachers (User, TeacherProfile)
 ├── Circles (via circle_teachers pivot)
 ├── Students (via student_id FK & circle cross-query)
 ├── Submissions (via reviewed_by FK, circle cross-query)
 ├── Progress (via teacher_id FK)
 └── Reports

Circles (Circle, CircleTeacher, CircleStudent)
 ├── Submissions (via circle_id FK)
 ├── Progress (via circle_id FK)
 ├── JuzProgressService (progress calculation per circle)
 ├── Reports
 └── CircleController + API

Submissions (StudentSubmission)
 ├── Files (stored in storage/app/public/)
 ├── Notifications (NewSubmissionNotification)
 ├── JuzProgressService (reads submissions for progress)
 ├── Reports
 └── 3+ controllers with store logic

Progress (StudentProgress)
 ├── Events → Listeners → Notifications
 ├── JuzProgressService (used alongside)
 └── 2+ controllers
```

## Identified Coupling Issues

**Strongly coupled pair: Circles ↔ Students ↔ Submissions.** These three modules are so tightly interconnected that extracting one without the others is impossible. Any modularisation must treat them as a single bounded context.

**Circular dependency risk:** Not currently circular, but the Reporting module reads from ALL tables. If Reports is extracted, it must depend on all other modules (acceptable for reporting).

**Shared progress calculation:** `JuzProgressService::calculate()` is called from:
1. `CircleController::show` — per-student in a loop
2. `CircleController::studentRecordings` — single student
3. `StudentDashboardController::submissions` — per juz
4. `StudentProgressController::index` (Feature) — per-student in a loop
5. `ReportController::circleReport` — per-student in a loop

Each caller receives the same data structure but presents it differently. This is a correctly extracted shared service.

**Duplicated store logic:** Submissions can be created from 3 places:
1. `RecordingController::store` — JSON, file upload, custom validation
2. `SubmissionApiController::store` — JSON, file upload, custom validation
3. `StudentSubmissionController::store` (Feature) — web redirect, file upload, different validation rules

Each has slightly different validation rules and response formats. This is a high-risk duplication.

**Repeated validation:** "Student belongs to circle" validation is implemented in:
- `RecordingController::store` (inline closure)
- `SubmissionApiController::store` (inline closure)
- `SubmissionApiController::review` (inline)
- `CircleController::studentRecordings` (inline)
- `DomainValidationService::studentBelongsToCircle()` (available but not always used)

---

# 10. Testing and Refactor Safety

## Current Test Coverage

| Test File | Type | Modules Covered | Assertions |
|---|---|---|---|
| `Feature/Quran/QuranDataTest.php` | PHPUnit (TestCase) | Quran data integrity | 13 tests: surah count, juz count, ayah counts, basmala records, ordering, FK integrity |
| `Feature/Auth/AuthenticationTest.php` | Pest | Login/logout | Standard Breeze tests |
| `Feature/Auth/RegistrationTest.php` | Pest | Registration form | 2 tests: screen renders, user can register |
| `Feature/Auth/EmailVerificationTest.php` | Pest | Email verification | Standard Breeze tests |
| `Feature/Auth/PasswordConfirmationTest.php` | Pest | Password confirmation | Standard Breeze tests |
| `Feature/Auth/PasswordResetTest.php` | Pest | Password reset | Standard Breeze tests |
| `Feature/Auth/PasswordUpdateTest.php` | Pest | Password update | Standard Breeze tests |
| `Feature/ProfileTest.php` | Pest | Profile edit/update | Standard Breeze tests |
| `Feature/ExampleTest.php` | Pest | Welcome page | 1 test: page returns 200 |
| `Unit/ExampleTest.php` | Pest | Basic assertion | 1 test: true is true |
| `Feature/QuranDataTest.php` | Pest | Quran data | (Unknown — possible duplicate of QuranDataTest) |
| `Pest.php` | Pest config | — | Test configuration |
| `tests/Pest.php` | Pest config | — | Test configuration |
| `TestCase.php` | Base | — | CreatesApplication |

## Coverage Gaps

| Module | Tests | Status |
|---|---|---|
| Quran reference data | **Yes** — 13 comprehensive data integrity tests | ADEQUATE |
| Authentication | **Yes** — Breeze standard tests | ADEQUATE |
| Profile | **Yes** — Breeze standard tests | ADEQUATE |
| **Student CRUD** | **No** | **CRITICAL GAP** |
| **Teacher CRUD** | **No** | **CRITICAL GAP** |
| **Circle CRUD** | **No** | **CRITICAL GAP** |
| **Submissions** | **No** | **CRITICAL GAP** |
| **Progress tracking** | **No** | **CRITICAL GAP** |
| **Reports** | **No** | **LOW PRIORITY** |
| **Role/Permission management** | **No** | **MEDIUM GAP** |
| **Email verification flow** | **No** | **MEDIUM GAP** |
| **API endpoints** | **No** | **CRITICAL GAP** |
| **Bulk import** | **No** | **MEDIUM GAP** |
| **Notifications** | **No** | **LOW PRIORITY** |

## Refactor Safety Rating: **LOW**

**Reasons:**
1. Zero tests exist for any business workflow (submissions, progress, enrolment, circles, reports)
2. Tests were not executed (EPERM — shell returns permissions error)
3. Runtime pass/fail status of existing Quran tests is unknown
4. All 14 test files cover only Quran data integrity (13 tests) and Breeze scaffolding (11 tests)
5. No API contract tests exist to protect JSON response shapes
6. Refactoring `RecordingController`, `QuranController`, `SystemReportController` would require manual regression testing

---

# 11. Architecture Options Comparison

## Legend
Each criterion scored 1 (worst) to 10 (best)

| Criterion | MVC (Current) | Clean MVC | Modular MVC | DDD Lite | Full DDD |
|---|---:|---:|---:|---:|---:|
| **Development Speed** | 7 — Already built, fast to add new features | 6 — Slightly slower due to Form Requests & Services | 5 — Module boundaries slow down cross-module features | 4 — Value Objects, Domain Events add friction | 2 — Full DDD ceremony slows iteration significantly |
| **Maintainability** | 3 — Mixed concerns, duplicated logic, fat controllers | 7 — Clear separation: Controllers thin, Services hold logic | 8 — Module boundaries enforce separation naturally | 8 — Domain model encapsulates rules | 7 — Can become overly abstract |
| **Scalability** | 3 — Tight coupling prevents team scaling | 5 — Better but no clear module boundaries | 7 — Modules can be extracted to microservices later | 8 — Domain boundaries map to microservice boundaries | 9 — Maximum strategic design |
| **Migration Risk** | 10 — No migration needed (current state) | 7 — Moderate: extract Services, add Form Requests | 5 — Significant: move files, change namespaces, update routes | 3 — High: introduce new concepts, rewrite business logic | 1 — Extremely high: full rewrite of domain layer |
| **Learning Curve** | 10 — Standard Laravel, any PHP developer can work | 8 — Slightly more concepts (Services, Actions, DTOs) | 6 — Module structure requires team alignment | 4 — DDD concepts (Value Objects, Aggregates, Domain Events) are new to most Laravel devs | 2 — Full DDD requires experienced practitioners |
| **Fit for Current Codebase** | 10 — Exactly what it is today | 8 — Controllers need thinning but models stay | 5 — Most code is in traditional MVC, would need major restructuring | 3 — Current Eloquent models are not DDD aggregates | 1 — Complete mismatch with current architecture |
| **Testability** | 4 — Business logic in controllers is hard to unit test | 7 — Services and Actions are easy to unit test | 8 — Modules can be tested in isolation | 8 — Domain model is purely testable | 7 — Eventual consistency adds testing complexity |
| **Overengineering Risk** | 10 — No overengineering (none applied) | 8 — Low risk: Form Requests and Services are standard Laravel | 6 — Medium risk: could create too many small modules | 4 — High risk: DTOs, Repositories for every model, unnecessary abstraction | 2 — Very high risk: Domain Events, Value Objects for simple lookups |
| **Total** | **55** | **56** | **50** | **42** | **31** |

## Score Explanation

**MVC (Current) scores highest on development speed, learning curve, and fit** because the project is already built and working in this pattern. It scores lowest on maintainability (3) and testability (4) due to fat controllers, duplicated logic, and inline business rules.

**Clean MVC scores highest overall (56)** because it is the most pragmatic next step. Adding Form Requests, Policies, Actions, and extracting Services improves maintainability and testability without requiring major restructuring. The migration risk is moderate and the fit for the current codebase is high.

**Modular MVC scores 50** — strong on maintainability and testability but significantly higher migration risk. The current codebase has 37 traditional controllers that would need relocation. The `app/Features/StudentProgress` reference shows it is feasible, but completing the migration for all modules would be months of work.

**DDD Lite scores 42** — introduces valuable concepts for complex domains (Submissions, Progress) but adds unnecessary complexity for CRUD modules (Organizations, Quran reference data). The learning curve and overengineering risk are problematic for the current team.

**Full DDD scores 31** — completely unjustified for this project. The domain is not complex enough to warrant Aggregates, Domain Events, Event Sourcing, or CQRS. The migration risk is extreme and the fit with the current Eloquent-based codebase is very poor.

---

# 12. Migration Cost Analysis

| Option | Files Affected | Namespace Changes | Route Impact | View Impact | Database Impact | Risk | Size |
|---|---|---|---|---|---|---|---|
| **Stay on MVC** | 0 | None | None | None | None | None | None |
| **Clean MVC** | 30-50 | None (same namespace) | None | None | None | LOW | **Medium** — extract services, add form requests |
| **Modular MVC** | 60-90 | Major: `App\Http\Controllers\*` → `App\Modules\*\Controllers\*` | None (route aliases unchanged) | None (partial path changes) | None | MEDIUM | **Large** — move files, update imports, refactor |
| **DDD Lite** | 80-120 | Major: new Value Objects, Domain models, Repositories | None | Minimal | None | HIGH | **Very Large** — new concepts, rewrite business logic |
| **Full DDD** | 150+ | Complete restructure | Minimal | Minimal | None | VERY HIGH | **Very Large** — near-rewrite of business layer |

**Note:** No option requires database changes. The schema is stable and well-designed for the domain.

---

# 13. Final Recommendation

**Recommended Architecture: Clean MVC (first) → Gradual Modular MVC (second phase)**

**Why this fits this exact project:**
1. The codebase is functional and working — a full architecture change would introduce risk without proportional benefit
2. The existing `app/Features/StudentProgress` module proves that modularisation is feasible incrementally
3. The most critical issues (fat controllers, duplicated logic, no tests) are solved by Clean MVC practices without requiring module boundaries
4. The domain (Quran memorisation tracking) is not complex enough to warrant DDD — it is fundamentally CRUD with one complex calculation (progress computation)
5. The team can immediately implement Clean MVC improvements without learning new paradigms

**Why Full DDD is NOT justified:**
1. The domain has simple business rules: students submit recordings, teachers review them, progress is calculated from approved submissions
2. All models are naturally mapped to database tables — there is no object-relational impedance mismatch
3. No complex state machines, no multi-step sagas, no event-sourcing requirements
4. The hardcoded Juz start boundaries in `JuzProgressService` are the only complex business logic — this is already extracted as a Service
5. DDD would add Value Objects for simple fields (ayah_number as int, status as string) that provide no benefit over Eloquent casts

**Rewrite required:** **No.** Every architecture option can be applied incrementally to the existing codebase.

**Parts that should remain unchanged:**
- Quran reference models (Surah, Ayah, Juz) — anemic, stable, read-only reference data
- Organization model — simple CRUD, no business logic
- Auth controllers — standard Breeze, no changes needed
- Role/Permission management — Spatie handles this well
- Notifications — standard Laravel notification system
- File upload service — well-designed, reusable

**Parts that need extraction:**
1. `RecordingController::store` → `CreateSubmissionAction` (3 duplicate implementations → 1 shared Action)
2. `QuranController` API methods → remove (already in `QuranApiController`)
3. `SystemReportController::index` → multiple ReportQuery classes
4. Progress calculation → already extracted as `JuzProgressService`, but standardise the caller interface
5. `StudentProfileController::store` → `CreateStudentAction` with DB transaction

**Safest migration strategy:** Phase 0 (add tests) → Phase 1 (Clean MVC: Form Requests, Policies, Service extraction) → Phase 2 (Modular MVC: move extracted code into `app/Modules/`)

---

# 14. Modules That Should Stay Simple MVC

| Module | Reason |
|---|---|
| **Surahs** | Static reference data. 114 records. Read-only after seeding. No business behaviour. |
| **Ayahs** | Static reference data. 6,348 records. Read-only after seeding. Never modified at runtime. |
| **Juz** | Static reference data. 30 records. Read-only after seeding. |
| **Organizations** | Simple CRUD with 2 fields (name, type). No business rules. No state machine. |
| **Roles & Permissions** | Fully managed by Spatie package. Only 2 controllers (RoleController, UserRoleController) handle CRUD — both under 65 lines. |
| **Auth** | Standard Breeze scaffolding with verification code extension. Well-structured, no architectural problems. |
| **Notifications** | Standard Laravel notifications. Thin API controller (52 lines). No extraction needed. |
| **Pending Registration** | Single-purpose model with well-encapsulated domain logic (expiry, max attempts). Scopes and methods are already good design. |

---

# 15. Modules That May Need Actions, Services, or DDD Lite

## 1. Submission Recording Workflow

**Current problem:** 3 duplicate implementations (`RecordingController::store`, `SubmissionApiController::store`, `StudentSubmissionController::store`) with different validation rules, inconsistent error handling, and missing transactions.

**Proposed boundary:** `App\Modules\Recordings\`

**Recommended pattern:**
- **Action:** `CreateSubmissionAction` — single class implementing the store process
- **Service:** `SubmissionFileService` — file validation + upload (extracted from inline code)
- **DTO:** `SubmissionData` — validated input data object
- **Policy:** Already exists (`StudentSubmissionPolicy`) — keep
- **Event:** `SubmissionCreated` — dispatch instead of inline notification
- **Listener:** `NotifyTeacherNewSubmission` — move inline notification logic

**Should NOT add:** Repository (Eloquent is fine), Value Objects (status is string, score is int), Domain Events beyond creation.

## 2. Enrolment (Join Circle)

**Current problem:** `StudentDashboardController::joinCircle` contains business rules (capacity check, duplicate check, status check) inline.

**Proposed boundary:** `App\Modules\Students` or keep in Circle module

**Recommended pattern:**
- **Action:** `JoinCircleAction` — encapsulates capacity + duplicate + status checks + insert
- **DTO:** `JoinCircleData` — student and circle IDs (trivial, may be overengineering)

**Should NOT add:** Domain Events, Value Objects, Repository.

## 3. Progress Calculation

**Current problem:** Already extracted as `JuzProgressService` (good). But called from 5+ places with different context. Cache invalidation is not always called after new submissions.

**Recommended pattern:**
- **Service:** Already exists — keep
- **Improvement:** Add cache invalidation call in `CreateSubmissionAction` (not currently done everywhere)
- **Improvement:** Consider moving the hardcoded Juz start boundaries to a configuration file or database seed for maintainability

## 4. Reporting

**Current problem:** `SystemReportController::index` is a 281-line monolithic method. Report queries are duplicated across `ReportController`, `ExecutiveDashboardController`, `DashboardApiController`.

**Proposed boundary:** `App\Modules\Reports\`

**Recommended pattern:**
- **Query classes:** `SystemReportQuery`, `StudentReportQuery`, `TeacherReportQuery`, `CircleReportQuery`
- **DTO:** `ReportSummaryData` for the system overview
- **Service:** `ReportGenerationService` to orchestrate multiple queries

**Should NOT add:** Policies (reports are admin-only by route middleware), Events (reports are read-only), Repository.

## 5. Student Creation (with User + Photo + Progress)

**Current problem:** `StudentProfileController::store` creates User, uploads photo, creates StudentProfile, creates StudentProgress — all inline without transactions.

**Recommended pattern:**
- **Action:** `CreateStudentAction` — wraps all 4 operations in DB transaction
- **Service:** Keep existing `FileUploadService` for photo upload
- **Should add:** DB transaction (`DB::transaction()`)

---

# 16. Recommended Target Structure

## Phase 1: Clean MVC (Immediate)

```
app/
├── Http/
│   ├── Controllers/          ← Keep existing, thin them
│   ├── Requests/             ← Add Form Requests for every controller
│   └── Resources/            ← Add API Resources for consistent JSON
├── Models/                   ← Keep existing Eloquent models
├── Services/                 ← Already exists — expand
├── Actions/                  ← New: shared action classes
│   ├── CreateSubmissionAction.php
│   ├── CreateStudentAction.php
│   └── JoinCircleAction.php
├── Policies/                 ← Already 5 — add more as needed
├── Queries/                  ← New: query classes for complex reads
│   ├── SystemReportQuery.php
│   ├── StudentProgressQuery.php
│   └── SubmissionStatsQuery.php
└── Support/                  ← New: helpers, constants, enums
```

## Phase 2: Modular MVC (Gradual)

```
app/
├── Http/
│   ├── Controllers/          ← Auth controllers + simple CRUD only
│   ├── Requests/
│   └── Resources/
├── Models/                   ← All Eloquent models (shared across modules)
├── Services/                 ← Cross-cutting services (Cache, FileUpload, JuzProgress)
├── Policies/                 ← All policies (shared across modules)
├── Modules/
│   ├── Quran/                ← Reference data (stay simple)
│   │   └── Queries/
│   │       └── CachedSurahQuery.php
│   ├── Students/
│   │   ├── Controllers/      ← Moved from Http/Controllers/
│   │   ├── Actions/
│   │   │   ├── CreateStudentAction.php
│   │   │   └── ImportStudentsAction.php
│   │   ├── Queries/
│   │   └── Exports/
│   ├── Circles/
│   │   ├── Controllers/
│   │   ├── Actions/
│   │   │   ├── JoinCircleAction.php
│   │   │   └── AddTeacherToCircleAction.php
│   │   └── Queries/
│   ├── Recordings/
│   │   ├── Controllers/      ← RecordingController extracted here
│   │   ├── Actions/
│   │   │   ├── CreateSubmissionAction.php
│   │   │   └── ReviewSubmissionAction.php
│   │   ├── Events/
│   │   │   └── SubmissionCreated.php
│   │   ├── Listeners/
│   │   │   └── NotifyTeacherNewSubmission.php
│   │   ├── Requests/
│   │   └── Notifications/
│   ├── Memorisation/
│   │   ├── Controllers/      ← StudentProgress controllers
│   │   ├── Actions/
│   │   ├── DTOs/
│   │   ├── Repositories/     ← Keep existing
│   │   └── Services/
│   ├── Reports/
│   │   ├── Controllers/      ← SystemReportController, ReportController
│   │   └── Queries/
│   │       ├── SystemReportQuery.php
│   │       ├── StudentReportQuery.php
│   │       ├── TeacherReportQuery.php
│   │       └── CircleReportQuery.php
│   └── Auth/                 ← If any custom non-Breeze auth logic
└── Support/
```

## Principles

1. **Do NOT create Repositories for every model** — only use them where data access logic is complex or needs mocking
2. **Do NOT create Interfaces for every Service** — only use interfaces when you need polymorphism or testing substitution
3. **Do NOT create DTOs for trivial data** — only use DTOs where input data needs validation+transformation separate from the request
4. **Do NOT create Value Objects for simple scalars** — `ayah_number` as `int` is fine, a `AyahNumber` Value Object is overengineering
5. **Share Eloquent models across all modules** — do not duplicate model classes
6. **Use Actions for single-use-case orchestration** — an Action class has one `execute()` method, calls Services/Models, fires Events
7. **Use Events only when side effects are non-critical** — notifications are the right use case

---

# 17. Controller Extraction Priorities

| Priority | Controller | Lines | Reason | Extraction Target | Risk |
|---:|---|---|---|---|---|
| **1** | **RecordingController** | 429 | Highest complexity, raw SQL, mixed web+API, 3 duplicate store implementations | Extract: store → `CreateSubmissionAction`, API endpoints → `RecordingsApiController`, queries → `SubmissionQuery` | HIGH |
| **2** | **QuranController** | 365 | Web+API mixed. API methods duplicate `QuranApiController`. All API endpoints have twin implementations | Remove all `api*` methods (8 methods). Remove `api` prefix routes from web.php pointing to QuranController. | LOW |
| **3** | **SystemReportController** | 281 | Monolithic single-method controller. Touches 8 models. Contains business logic (issues, insights) | Extract: `SystemReportQuery`, `IssuesDetector`, `InsightsGenerator` | MEDIUM |
| **4** | **StudentProfileController** | 268 | Creates User + uploads photo + creates StudentProfile + optionally creates StudentProgress — all inline, no transaction | Extract: `CreateStudentAction` with `DB::transaction()` | LOW |
| **5** | **SubmissionApiController** | 220 | Duplicates RecordingController store logic. Review workflow duplicated with Feature controller. | Extract: share `CreateSubmissionAction` and `ReviewSubmissionAction` with Feature version | MEDIUM |
| **6** | **StudentDashboardController** | 185 | Duplicates progress calculation. joinCircle has inline business rules. | Extract: `JoinCircleAction`, share progress queries | LOW |
| **7** | **StudentSubmissionController (Feature)** | 188 | Store method is 60 lines with notification. Could use Action. | Align with Phase 1 (use shared Actions) | LOW |
| **8** | **CircleController** | 164 | Show method heavy (progress per student, Schema::hasColumn). | Extract: `CircleShowQuery`, `CircleProgressService` | LOW |
| **9** | **StudentApiController** | 120 | Duplicates same progress-by-surah calculation. | Share progress query from `StudentProgressQuery` | LOW |
| **10** | **ReportController** | 119 | Duplicates progress calculation (studentReport). | Extract: `StudentReportQuery` | LOW |

---

# 18. Phased Migration Plan

## Phase 0 — Safety Baseline

**Objective:** Ensure refactoring can be validated

**Candidate files:** `composer.json`, `phpunit.xml`, `tests/`

**Actions:**
1. Document all current route responses with screenshots or cURL examples
2. Document current database state (`SELECT COUNT(*)` for each table, sample rows)
3. Back up the database
4. Resolve EPERM issue to enable test execution
5. Write critical tests (see Phase 0 test priorities below)

**Critical tests to add before any refactoring:**
- `RecordingController::store` returns 201 with valid audio
- `RecordingController::store` returns 403 for non-student user
- `QuranController::showSurah` returns surah detail view
- `StudentProfileController::store` creates User + StudentProfile + optional StudentProgress
- All API endpoints return expected JSON structure
- `CircleController::show` renders without error

**Risk:** LOW (no code changes)

**Completion criteria:** All existing and new tests pass

**Rollback condition:** Tests fail — revert to backup

**Stop condition:** EPERM prevents test execution — do not proceed until resolved

---

## Phase 1 — Clean Existing MVC

**Objective:** Thin controllers, extract responsibilities, add safety

### 1.1 — Form Requests
**Candidate controllers:** All controllers with inline `$request->validate()`

**Actions:**
- Create Form Request classes for every controller that validates inline
- Replace `$request->validate(...)` with `new StoreXxxRequest` injection
- Move custom validation closures into Form Request `withValidator()`

**Risk:** LOW — pure extraction, no behaviour change

### 1.2 — Policies
**Candidate controllers:** Any controller using inline `$user->hasRole('...')` for authorisation

**Actions:**
- Audit all inline role checks
- Add Policy methods where missing (e.g., `RecordingController::rate` checks role inline)
- Register new Policies in `AppServiceProvider`

**Risk:** LOW — behaviour unchanged, additional safety layer

### 1.3 — Database Transactions
**Candidate methods:** `StudentProfileController::store`, `VerifyCodeController::verify`, `RecordingController::store`

**Actions:**
- Wrap multi-write operations in `DB::transaction()`
- Move file uploads outside the transaction (upload first, then DB write)

**Risk:** MEDIUM — transaction could expose deadlocks; test thoroughly

### 1.4 — Remove QuranController API methods
**Actions:**
- Delete all 8 `api*` methods from `QuranController`
- Remove corresponding API routes from `web.php` (lines 202-209)
- Verify `QuranApiController` handles all the same endpoints via `routes/api.php`

**Risk:** LOW — duplicate methods are already covered by QuranApiController

**Completion criteria:** All Phase 0 tests pass. No behaviour changes visible to users.

**Rollback condition:** Route regression on Quran API endpoints

**Stop condition:** Any test failure not caused by pre-existing bug

---

## Phase 2 — Extract Complex Actions

**Objective:** Create reusable Action classes for complex business operations

### 2.1 — CreateSubmissionAction
**Unifies 3 implementations:** `RecordingController::store`, `SubmissionApiController::store`, `StudentSubmissionController::store`

**Candidate files:** 
- `app/Actions/CreateSubmissionAction.php`
- `app/Events/SubmissionCreated.php`
- `app/Listeners/NotifyTeacherNewSubmission.php`

**Actions:**
1. Design the Action with a single `execute(SubmissionData $data): StudentSubmission`
2. Extract file upload logic (already in `FileUploadService` — use it)
3. Create `SubmissionData` DTO
4. Fire `SubmissionCreated` event instead of inline notification
5. Refactor all 3 controllers to call the Action
6. Remove duplicate validation — keep in Form Request per controller if rules differ

**Risk:** MEDIUM — 3 different callers with slightly different behaviour must produce identical results

### 2.2 — CreateStudentAction
**Extracts from:** `StudentProfileController::store`

**Candidate files:** `app/Actions/CreateStudentAction.php`

**Actions:**
1. Wrap User creation + photo upload + StudentProfile creation + optional StudentProgress in `DB::transaction()`
2. Move to Action class

**Risk:** LOW — single caller, pure extraction

### 2.3 — JoinCircleAction
**Extracts from:** `StudentDashboardController::joinCircle`

**Candidate files:** `app/Actions/JoinCircleAction.php`

**Actions:**
1. Encapsulate: student exists → circle is active → not already enrolled → has capacity → insert
2. Return result object with success/error message

**Risk:** LOW — single caller, pure extraction

**Completion criteria:** All 3 store implementations create identical submission records. All existing tests pass.

**Rollback condition:** Submission creation differs between old and new code paths

**Stop condition:** Unable to verify identical behaviour (no tests for submissions)

---

## Phase 3 — Introduce Module Boundaries

**Objective:** Group related files into `app/Modules/` directories

### 3.1 — Recordings Module
**Move:**
- `RecordingController` → `app/Modules/Recordings/Controllers/`
- `CreateSubmissionAction` → `app/Modules/Recordings/Actions/`
- `SubmissionApiController` → `app/Modules/Recordings/Controllers/Api/`
- All submission-related Feature files → consolidated into Recordings module
- Bulk import service → `app/Modules/Recordings/Services/`

**Namespace change:** `App\Http\Controllers\RecordingController` → `App\Modules\Recordings\Controllers\RecordingController`

**Route impact:** Update `use` statements in `routes/web.php` and `routes/api.php`

### 3.2 — Circles Module
**Move:**
- `CircleController`, `CircleTeacherController`, `CircleStudentController` → `app/Modules/Circles/Controllers/`
- `CircleApiController` → `app/Modules/Circles/Controllers/Api/`

### 3.3 — Students Module
**Move:**
- `StudentProfileController`, `StudentApiController` → `app/Modules/Students/Controllers/`
- `StudentImportController`, `StudentImportService` → `app/Modules/Students/`
- `CreateStudentAction` → `app/Modules/Students/Actions/`

### 3.4 — Reports Module
**Move:**
- `SystemReportController`, `ReportController` → `app/Modules/Reports/Controllers/`
- `DashboardApiController` → `app/Modules/Reports/Controllers/Api/`
- Report queries → `app/Modules/Reports/Queries/`

**Risk per module:** MEDIUM — namespace changes affect all imports, blade view references, route definitions

**Completion criteria:** Every route returns the same response before and after the move

**Rollback condition:** Any 404 or 500 error caused by namespace mismatch

**Stop condition:** More than 5 files need rollback per module

---

## Phase 4 — Optional DDD Lite (Selective Only)

**Apply only to:** Submission workflow (if business rules become significantly more complex)

**Candidate additions:**
- Value Object: `SubmissionStatus` (replacing string 'pending'|'reviewed'|'accepted'|'needs_work') — only if status transitions need validation
- Domain Event: `SubmissionReviewed` — only if additional side effects are needed beyond current notification
- Specification: `StudentCanSubmitSpecification` — only if eligibility rules become complex

**Do NOT apply to:** Quran reference data, Organizations, Auth, Roles — these gain nothing from DDD.

**Risk:** MEDIUM — introducing new concepts to an existing working codebase

**Stop condition:** If the DDD additions cause more complexity than they remove, abandon this phase.

---

# 19. Top 10 Quick Wins

| Rank | Improvement | Impact | Risk | Effort |
|---:|---|---|---|---|
| **1** | Remove `QuranController` API methods (delete 8 `api*` methods) | **Medium** — eliminates duplicate code, 50 lines removed | **None** — QuranApiController handles same endpoints | **15 minutes** |
| **2** | Add `DB::transaction()` to `StudentProfileController::store` | **High** — prevents partial creates if photo upload fails | **Low** — single method change | **10 minutes** |
| **3** | Add `DB::transaction()` to `RecordingController::store` | **High** — prevents orphan submission records if notification fails | **Low** — single method change | **10 minutes** |
| **4** | Extract `StudentProfileController::store` inline validation to Form Request | **Medium** — thins controller, centralises rules | **Low** — pure extraction | **30 minutes** |
| **5** | Extract `RecordingController::store` inline validation to Form Request | **Medium** — thins controller | **Low** — pure extraction | **30 minutes** |
| **6** | Add `tests/Feature/RecordingTest.php` basic test | **High** — protects critical workflow | **Low** — copy pattern from existing tests | **1 hour** |
| **7** | Add `tests/Feature/StudentTest.php` basic test | **High** — protects student CRUD | **Low** — copy pattern from existing tests | **1 hour** |
| **8** | Add `tests/Feature/CircleTest.php` basic test | **High** — protects circle management | **Low** — copy pattern from existing tests | **1 hour** |
| **9** | Add cache invalidation to `CreateSubmissionAction` (clear JuzProgressService cache) | **Medium** — progress updates immediately after submission | **Low** — single cache clear call | **15 minutes** |
| **10** | Replace inline `$user->hasRole()` checks with Policy calls in `RecordingController::rate` | **Low** — consistency improvement | **Low** — Policy already exists | **20 minutes** |

**Total effort:** ~4.5 hours

---

# 20. Risks and Mitigations

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| **Refactoring without executable tests** | HIGH | CRITICAL | Phase 0 must resolve EPERM and establish baseline tests before any refactoring. Do not refactor without test safety net. |
| **Breaking web/API responses** | MEDIUM | HIGH | Add response contract tests (assert JSON structure) before refactoring any API endpoint. Document current responses. |
| **Namespace changes breaking route model binding** | MEDIUM | HIGH | All route model binding uses class names in route definitions. When moving controllers, update route files carefully. Use `Route::model()` or explicit type-hints. |
| **Hidden Blade dependencies on controller method names** | LOW | MEDIUM | Blade views reference routes by name, not controller methods. Route names remain unchanged, so view impact is minimal. |
| **Database transaction issues** | LOW | MEDIUM | Wrap multi-write operations in `DB::transaction()` with try/catch. Test rollback scenarios. |
| **Quran `ayah_number = 0` basmala handling** | LOW | MEDIUM | All Quran queries must consistently filter `where('ayah_number', '>', 0)` for numbered ayahs. The `QuranDataTest` verifies this. Keep the test. |
| **Permission regressions** | MEDIUM | HIGH | Add permission tests for each role (student, teacher, admin, super admin) accessing each route before changing authorisation code. |
| **Raw SQL behaviour changes in RecordingController** | LOW | MEDIUM | The `DB::table('ayahs')...selectRaw` queries in RecordingController must produce identical results after extraction. Document the expected output, then verify. |
| **Notification delivery failures** | LOW | LOW | Current inline notifications fail silently (try/catch). Moving to Events would maintain same behaviour. |
| **Cache invalidation not called after new submissions** | MEDIUM | MEDIUM | Progress calculation is cached for 300s. New submissions do not always invalidate the cache. Add `clearStudentCache()` call in `CreateSubmissionAction`. |

---

# 21. EPERM Note

## Why Shell Commands Could Not Execute

The terminal (PowerShell) in this environment returns EPERM (Operation Not Permitted) when attempting to spawn any child process. This affects:

- **`php artisan` commands** — cannot run migrations, seeders, route lists, model caches, or any Artisan command
- **`composer` commands** — cannot install, update, dump-autoload
- **`npm` commands** — cannot build, install, or run scripts
- **`git` commands** — cannot commit, diff, log, status, or perform any git operations
- **`phpunit` / `pest` commands** — cannot execute any tests
- **`node` commands** — cannot run Node.js scripts

## Impact on Analysis

Despite these limitations, the architecture report is based on:

1. **Static source code inspection** — all 13 models, 41 controllers, 6 services, 5 actions, 5 policies, 26 migrations, ~88 blade views, 14 test files, and all configuration files were read and analysed
2. **File structure analysis** — directory listings and glob patterns revealed every PHP file in the project
3. **Code content analysis** — every method signature, every SQL query, every validation rule, every route definition was read

## Limitations

- Runtime behaviour could not be verified (test pass/fail status unknown)
- Composer autoloading could not be tested (namespace resolution)
- Route list could not be generated (some routes may be registered dynamically)
- Exact PHP and package versions are from `composer.json` only (lock file not verified)
- Migration sequence and database state cannot be confirmed

## Required Before Implementation

- Run `php artisan test` to establish baseline test status
- Run `php artisan route:list` to verify all routes
- Run `php artisan migrate:status` to verify database state
- Run `php artisan cache:clear` before any behavioural changes

---

# 22. Final Decision Block

```
RECOMMENDED ARCHITECTURE:   Clean MVC → Gradual Modular MVC (2-phase)
CURRENT ARCHITECTURE:       Traditional MVC with partial feature-folder modularisation (10% modular)
MIGRATION STRATEGY:         Phase 0 (tests) → Phase 1 (Clean MVC) → Phase 2 (Modular MVC) → Phase 3 (optional selective DDD Lite)
FULL REWRITE REQUIRED:      NO — the existing codebase is functional and incrementally improvable
FULL DDD RECOMMENDED:       NO — the domain (Quran memorisation tracking) is not complex enough to justify DDD ceremony
DATABASE CHANGES REQUIRED:  NONE — the schema is stable and well-designed
ESTIMATED RISK:             MEDIUM — risk is entirely from lack of test coverage, not from architectural complexity
REFACTOR SAFETY:            LOW — zero business logic tests exist; tests cannot currently be executed (EPERM)
FIRST CONTROLLER TO IMPROVE: RecordingController (429 lines, 3 duplicate store implementations, raw SQL)
FIRST BUSINESS MODULE TO EXTRACT: Recordings (submissions) — highest complexity, most duplicated code, clearest boundary
MODULES TO LEAVE UNCHANGED: Quran reference data (Surah, Ayah, Juz), Organizations, Auth, Roles, PendingRegistration, Notifications
SHELL STATUS:               EPERM — no commands can execute; all analysis is from static source inspection
REASON IN 5 LINES:
The project is a well-structured Laravel 12 application with standard MVC patterns and an early-stage feature modularisation effort (4 controllers in app/Features/). The biggest problems are fat controllers (RecordingController 429L, QuranController 365L, SystemReportController 281L), duplicated logic (submission creation implemented 3 ways, progress calculation duplicated across 5+ controllers), and zero test coverage for any business workflow. The safest improvement path is Clean MVC first (Form Requests, Policies, Service extraction, DB transactions), then gradual Modular MVC using the existing StudentProgress feature as the reference pattern. Full DDD is not justified because the domain is fundamentally CRUD with one complex calculation (Juz progress) already extracted into a service class. A full rewrite would be destructive and unnecessary — every improvement can be applied incrementally to the existing codebase.
```
