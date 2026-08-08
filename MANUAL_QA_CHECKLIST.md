# Manual QA Checklist

## 1. Login

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| Login with valid credentials | Admin | `/login` | Redirect to dashboard | |
| Login with invalid password | Any | `/login` | Error message, stay on login | |
| Login with inactive account | Any | `/login` | Error message | |
| Access login page when authenticated | Any | `/login` | Redirect away | |
| Logout | Any | `/logout` | Redirect to login | |

## 2. Student CRUD

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View student list | Admin | `/students` | Paginated list of students | |
| View create form | Admin | `/students/create` | Form with all fields | |
| Create student with new user | Admin | `POST /students` | Student created, redirect with success | |
| Create student with existing user | Admin | `POST /students` | Student linked to existing user | |
| Create student without user | Admin | `POST /students` | Student created with null user_id | |
| Create student with photo | Admin | `POST /students` | Photo uploaded, path stored | |
| Create student with memorization data | Admin | `POST /students` | StudentProgress created | |
| View student detail | Admin | `/students/{id}` | Profile, submissions, progress | |
| Edit student | Admin | `/students/{id}/edit` | Pre-filled form | |
| Update student | Admin | `PUT /students/{id}` | Updated, redirect with success | |
| Update student photo | Admin | `PUT /students/{id}` | Old photo replaced | |
| Archive student | Admin | `DELETE /students/{id}` | Status=archived, redirect | |
| Access student page unauthenticated | None | `/students` | Redirect to login | |
| Access student page as student | Student | `/students` | 403 or redirect | |

## 3. Teacher CRUD

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View teacher list | Admin | `/teachers` | List with user info | |
| Create teacher with new user | Admin | `/teachers/create` | User+profile+role created | |
| Create teacher with existing user | Admin | `POST /teachers` | Profile linked to existing user | |
| Create teacher with photo | Admin | `POST /teachers` | Photo uploaded | |
| View teacher detail | Admin | `/teachers/{id}` | Profile, students, circles | |
| Update teacher | Admin | `PUT /teachers/{id}` | Updated successfully | |
| Delete teacher | Admin | `DELETE /teachers/{id}` | Profile+photo deleted | |

## 4. Circle Management

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View circles list | Admin | `/circles` | Paginated list | |
| Create circle | Admin | `POST /circles` | Created, redirect | |
| Update circle | Admin | `PUT /circles/{id}` | Updated, redirect | |
| Archive circle | Admin | `DELETE /circles/{id}` | Status=archived | |
| View circle detail | Admin | `/circles/{id}` | Teachers, students, progress | |

## 5. Student Enrolment

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| Student joins active circle | Student | `POST /circles/{id}/join` | Enrolled, success message | |
| Student joins inactive circle | Student | `POST /circles/{id}/join` | Rejected, error message | |
| Student joins full circle | Student | `POST /circles/{id}/join` | Rejected, capacity error | |
| Student joins already-enrolled circle | Student | `POST /circles/{id}/join` | Rejected, duplicate error | |
| Admin adds student to circle | Admin | `POST /circles/{id}/students` | Added, success message | |
| Admin adds duplicate student | Admin | `POST /circles/{id}/students` | Rejected, duplicate error | |
| Admin removes student | Admin | `DELETE /circle-students/{id}` | Removed | |

## 6. Teacher Assignment

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| Admin adds teacher to circle | Admin | `POST /circles/{id}/teachers` | Added, success message | |
| Add duplicate teacher | Admin | `POST /circles/{id}/teachers` | Skipped with message | |
| Update teacher role | Admin | `PUT /circle-teachers/{id}` | Role updated | |
| Remove teacher from circle | Admin | `DELETE /circle-teachers/{id}` | Removed | |

## 7. Recording Workflow

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| Upload recording (web) | Student | `POST /student-submissions/{circle}` | Created, redirect to list | |
| Upload recording (API) | Student | `POST /api/submissions` | JSON 201 with data | |
| Upload recording without audio | Student | `POST` | Validation error | |
| Upload recording with invalid file type | Student | `POST` | Validation error | |
| Upload recording to non-existent circle | Student | `POST` | 404 | |
| View own recordings | Student | `/student/submissions` | List of submissions | |
| View all recordings | Admin | `/student-submissions` | All submissions | |

## 8. Review Workflow

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| Teacher reviews recording (web) | Teacher | `POST /student-submissions/{id}/review` | Saved, success message | |
| Teacher reviews recording (API) | Teacher | `PUT /api/submissions/{id}/review` | JSON 200 with data | |
| Non-teacher reviews recording | Student | Any review route | 403 error | |
| Review with missing notes | Teacher | Any review route | Validation error | |
| Review with out-of-range score | Teacher | Any review route | Validation error | |
| Review triggers notification | Teacher | Any review route | Student notified | |

## 9. Quran Pages

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View surah index | Authenticated | `/quran` | Paginated surah list | |
| View surah detail | Authenticated | `/quran/surah/{id}` | Ayahs with basmala handling | |
| View ayah detail | Authenticated | `/quran/surah/{id}/ayah/{num}` | Ayah with prev/next | |
| View juz index | Authenticated | `/quran/juz` | 30 juz cards | |
| View juz detail | Authenticated | `/quran/juz/{id}` | Surahs in juz | |
| View statistics | Authenticated | `/quran/statistics` | Summary, charts, surah table | |
| Search surahs | Authenticated | `/quran/search?q=البقرة` | Matching surahs | |
| Search with short query | Authenticated | `/quran/search?q=a` | Error, min 2 chars | |

## 10. Quran Ajax Endpoints (used by student create/edit forms)

| Step | Route | Expected Response | Pass/Fail |
|------|-------|-------------------|-----------|
| Fetch all surahs | `GET /api/quran/surahs` | Bare array of surah objects | |
| Fetch juz for surah | `GET /api/quran/surah/1/juz` | Bare array of juz objects | |
| Fetch ayahs for surah+juz | `GET /api/quran/surah/1/juz/1/ayahs` | Bare array of ayah objects | |

## 11. Reports

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View system report | Admin | `/reports/system` | All sections render | |
| Empty database state | Admin | `/reports/system` | Graceful empty state, no errors | |
| View individual report | Admin | `/reports/{type}` | Loads with data | |

## 12. API Auth

| Step | Route | Expected Result | Pass/Fail |
|------|-------|-----------------|-----------|
| Unauthenticated API request | Any `/api/...` | 401 or redirect | |
| Authenticated API request | Any `/api/...` | Proper JSON response | |

## 13. Authorization

| Step | Expected Result | Pass/Fail |
|------|-----------------|-----------|
| Super admin has full access | All pages accessible | |
| Admin has full access | All pages accessible | |
| Teacher can view circles and review | Circle pages, review pages | |
| Teacher cannot create students | 403 or hidden links | |
| Student can only see own data | Own submissions, own profile | |
| Student cannot access admin pages | 403 or redirect | |
| Unauthenticated user redirected | Redirect to login | |

## 14. File Validation

| Step | Expected Result | Pass/Fail |
|------|-----------------|-----------|
| Upload too-large audio (>10MB) | Validation error | |
| Upload wrong image format | Validation error | |
| Upload oversized image (>2MB) | Validation error | |
| Upload valid files | Success | |

## 15. Edge Cases

| Step | Expected Result | Pass/Fail |
|------|-----------------|-----------|
| Empty student list | Empty state message | |
| Empty circle list | Empty state message | |
| Student with no submissions | Progress shows 0% | |
| Circle with no teacher | "No teachers" indicator | |
| Circle with no students | "No students" indicator | |
| Very long text in notes | Truncated or wrapped | |
| Special characters in names | Displayed correctly | |
| Concurrent circle join (race) | Only one succeeds (lockForUpdate) | |
| PHP memory limit | Pages load within limit | |
| Login session timeout | Redirect to login |

## 16. Memorization Assignments

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View assignments list | Admin/Teacher | `/admin/memorization-assignments` | Paginated list with stats | |
| View assignments list as student | Student | `/admin/memorization-assignments` | 403 forbidden | |
| Create assignment | Admin/Teacher | `POST /admin/memorization-assignments` | Created, redirect with success | |
| Create assignment for non-circle student | Admin/Teacher | `POST` | Validation error | |
| View assignment detail | Admin/Teacher | `/admin/memorization-assignments/{id}` | All info cards render | |
| Complete assignment | Admin | `PUT /admin/memorization-assignments/{id}/complete` | Status=completed, cache cleared | |
| Invalid status transition | Admin | `PATCH /admin/memorization-assignments/{id}/status` | Error message | |
| Student views own assignments | Student | `/student/assignments` | Own assignments listed | |
| Student starts assignment | Student | `PATCH /student/assignments/{id}/status` | in_progress, started_at set | |
| Student submits assignment | Student | `PATCH /student/assignments/{id}/status` | submitted, submitted_at set | |
| AJAX load students by circle | Admin | `GET /admin/memorization-assignments/circles/{id}/students` | JSON students array | |
| Create assignment with invalid circle | Admin | `POST` | Validation error | |

## 17. Memorization Sessions

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View sessions list | Admin/Teacher | `/admin/memorization-sessions` | Paginated list | |
| Create session with scores | Admin/Teacher | `POST /admin/memorization-sessions` | Created, redirect | |
| Create session with no scores | Admin/Teacher | `POST` | Created with null scores | |
| View session detail | Admin/Teacher | `/admin/memorization-sessions/{id}` | Scores, mistakes, notes | |
| Edit session scores | Admin/Teacher | `PUT /admin/memorization-sessions/{id}` | Updated | |
| Delete session | Admin/Teacher | `DELETE /admin/memorization-sessions/{id}` | Soft deleted | |
| Session auto-updates assignment | System | Complete session with assignment_id | Assignment status=reviewed | |

## 18. Mistakes

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View mistakes list | Admin/Teacher | `/admin/memorization-mistakes` | Paginated mistakes | |
| Add mistake to session | Admin/Teacher | `POST /admin/memorization-sessions/{id}/mistakes` | Created, redirect back | |
| Add mistake with invalid type | Admin/Teacher | `POST` | Validation error | |
| Resolve mistake | Admin/Teacher | `PATCH /admin/memorization-mistakes/{id}/resolve` | is_resolved=true | |

## 19. Revision Plans

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View plans list | Admin/Teacher | `/admin/revision-plans` | List with progress bars | |
| Create plan with items | Admin/Teacher | `POST /admin/revision-plans` | Plan + items created | |
| Create plan without items | Admin/Teacher | `POST` | Validation error | |
| View plan detail | Admin/Teacher | `/admin/revision-plans/{id}` | Items with completion status | |
| Complete plan item | Admin/Teacher | `PATCH /admin/revision-plan-items/{id}/complete` | Item completed | |
| Complete all items auto-completes plan | System | Last item completed | Plan status=completed | |

## 20. Circle Sessions & Attendance

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View circle session list | Admin/Teacher | `/admin/circles/{circle}/sessions` | Sessions for that circle | |
| Create session | Admin/Teacher | `POST /admin/circles/{circle}/sessions` | Created, redirect to attendance | |
| Record attendance with radios | Admin/Teacher | `POST /admin/circles/{circle}/sessions/{id}/attendance` | Records saved | |
| View session with stats | Admin/Teacher | `/admin/circles/{circle}/sessions/{id}` | Stats cards show counts | |
| Update existing attendance | Admin/Teacher | `POST /admin/circles/{circle}/sessions/{id}/attendance` | Records updated (not duplicated) | |

## 21. Quran Exams

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View exams list | Admin/Teacher | `/admin/quran-exams` | Filtered by user role | |
| Create exam | Admin/Teacher | `POST /admin/quran-exams` | Created, redirect | |
| View exam detail | Admin/Teacher | `/admin/quran-exams/{id}` | Results table, add result form | |
| Add passing result | Admin/Teacher | `POST /admin/quran-exams/{id}/results` | Passed=true, correct percentage | |
| Add failing result | Admin/Teacher | `POST /admin/quran-exams/{id}/results` | Passed=false | |
| Add absent result | Admin/Teacher | `POST /admin/quran-exams/{id}/results` | Status=absent, passed=null | |

## 22. Parent Portal

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View parent dashboard | Parent | `/parent/dashboard` | Own children listed | |
| View student progress | Parent | `/parent/students/{id}/progress` | Progress, submissions, attendance | |
| Access other student as parent | Parent | `/parent/students/{id}/progress` | 403 forbidden | |
| Access parent routes as student | Student | `/parent/dashboard` | 403 forbidden | |

## 23. Achievements & Certificates

| Step | User Role | Route | Expected Result | Pass/Fail |
|------|-----------|-------|-----------------|-----------|
| View achievements | Admin | `/admin/achievements` | Cards grid | |
| View certificates | Admin | `/admin/certificates` | Cards with verification codes | |

## 24. Navigation & Dashboards

| Step | User Role | Expected Result | Pass/Fail |
|------|-----------|-----------------|-----------|
| New menu items visible | Admin | المهام والمراجعة, الاختبارات sections | |
| Student sidebar updated | Student | مهام الحفظ link visible | |
| Admin dashboard stats updated | Admin | New stat cards (مهام الحفظ, جلسات التسميع, etc.) | |
| Permissions enforced | Teacher | Can access new features, tab visible | |
| Permissions enforced | Student | Only assignments visible, others hidden | | |
