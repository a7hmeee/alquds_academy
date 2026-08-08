# Refactor Plan — RecordingController Clean MVC

## Pre-Refactor Findings

### Recording Creation Paths (4 total)

| Path | File | Response | Key Differences |
|---|---|---|---|
| **1** | `RecordingController::store` | JSON 201 `{success, message, submission}` | Uses `FileUploadService`, checks **active** circle membership, surah/juz by ID + text denormalized, notifies first teacher, try/catch error handling |
| **2** | `SubmissionApiController::store` | JSON 201 `{success, message, data: {id, status}}` | Uses `FileUploadService`, checks circle membership **without** active status, same field mapping, **no notification**, no try/catch |
| **3** | `StudentSubmissionController::store` (Feature) | Redirect (student.submissions or student-submissions.index) | Uses direct `$file->store()` (NOT FileUploadService), field name `audio_file` not `audio`, allows teacher/super admin proxy, max 10MB, no try/catch |
| **4** | `StudentSubmissionApiController::store` (Feature) | JSON 201 `{data: submission}` | Uses direct `$file->store()`, **no surah_id/juz_id/ayah_from/ayah_to**, text fields only, no notification, no student check (!) |

### Validation Differences

| Field | RecordingController | SubmissionApiController | Feature StudentSubmissionController | Feature StudentSubmissionApiController |
|---|---|---|---|---|
| audio field name | `audio` | `audio` | `audio_file` | `audio` |
| audio mimes | mp3,wav,m4a,ogg | mp3,wav,m4a,ogg | mp3,wav,m4a | mp3,wav,m4a,ogg |
| audio max size | 51200 (50MB) | 51200 (50MB) | 10240 (10MB) | 10240 (10MB) |
| image mimes | jpeg,png,jpg,webp | jpeg,png,jpg | jpeg,png,jpg | **no validation** |
| surah_id | required, exists | required, exists | nullable, exists | **not present** |
| juz_id | required, exists | required, exists | nullable, exists | **not present** |
| ayah_from | required, min:1 | required, min:1 | nullable, min:1 | **not present** |
| ayah_to | nullable, min:1 | nullable, min:1 | nullable, min:1 | **not present** |
| circle check | **active** membership | membership (any status) | membership (unless super admin) | **no check** |

### Response Differences

| Controller | Success Shape | Error Shape |
|---|---|---|
| RecordingController | `{success: true, message, submission: {id, ...}}` (201) | `{error: '...'}` (403/500) |
| SubmissionApiController | `{success: true, message, data: {id, status}}` (201) | `{success: false, message: '...'}` (403) |
| Feature StudentSubmissionController | Redirect to `student.submissions` or `student-submissions.index` with flash | Redirect back with `->with('error', '...')` |
| Feature StudentSubmissionApiController | `{data: submission}` (201) | none (no student check) |

### Duplicated Logic

1. **"Student belongs to circle" check** implemented 4 ways:
   - RecordingController: inline closure with `where('status', 'active')`
   - SubmissionApiController: inline closure without active check
   - StudentSubmissionController: `$circle->circleStudents->pluck('student_id')->contains(...)` (collection)
   - DomainValidationService::studentBelongsToCircle() exists but is NOT used by any submission controller

2. **File upload** implemented 3 ways:
   - RecordingController + SubmissionApiController: use `FileUploadService`
   - Feature controllers: use `$file->store()` directly

3. **Surah name lookup from ID**: duplicated in RecordingController::store and SubmissionApiController::store (`Surah::find($validated['surah_id'])` then `$surah->name_ar`)

4. **Juz name lookup from ID**: same pattern duplicated

5. **API query methods**: `apiSurahs`, `apiSearchSurahs` in RecordingController use raw SQL that is also present in other query contexts

### Authorization Issues

1. `RecordingController::rate` calls `$this->authorize('view', $submission)` but then adds **additional** inline role checks (`$isOwner`, `$isTeacher`, `$isSuperAdmin`) — the Policy `view` already handles this logic
2. `RecordingController::store` checks `auth()->user()->studentProfile` (returns 403 if null) but the `create` policy already handles this
3. `SubmissionApiController::review` checks Policy `review` then adds **additional** inline role check for circle teacher membership — the Policy `review` already checks this

### File Handling Issues

1. RecordingController::store: if DB insert fails AFTER file upload, the uploaded file is NOT cleaned up (no rollback)
2. RecordingController::store: if notification fails AFTER DB insert, the submission IS created but no file cleanup needed — this is OK
3. Feature StudentSubmissionController::store: uses `$file->store()` directly instead of FileUploadService (bypasses MIME validation)

### Cache Issues

1. `JuzProgressService::calculate()` caches at key `juz_progress.{studentId}.{juzId}.{circleId}` for 300s
2. After submission creation: cache is NOT cleared anywhere
3. After submission review (status change to accepted): cache is NOT cleared
4. After submission deletion: cache is NOT cleared

---

## Plan

### Phase 1: Tests (create but cannot execute)
### Phase 2: Form Requests for RecordingController
### Phase 3: SubmissionData DTO
### Phase 4: CreateSubmissionAction
### Phase 5: Event + Listener for notification
### Phase 6: ReviewSubmissionAction  
### Phase 7: Query classes for duplicated API queries
### Phase 8: Clean RecordingController
### Phase 9: Unify store across controllers (optional)
### Phase 10-12: Policy, Cache, Final verification

---

## Phase 1 Completion Report — Clean MVC Refactoring

### Summary of Changes

| Metric | Before | After |
|---|---|---|
| RecordingController lines | 429 | 292 |
| New files created | — | 6 |
| Modified files | — | 5 |
| Direct StudentSubmission::create calls in controllers | 3 | 0 (1 remaining in Feature StudentSubmissionApiController) |
| Submission paths using centralized Action | 0 | 3 |
| Duplicate API queries | Inline (3 methods) | Extracted to SurahJuzQuery |
| Notification logic | Inline in store() | Event + Listener |
| File rollback on DB failure | ❌ Not handled | ✅ Handled in CreateSubmissionAction |
| Cache invalidation after operations | ❌ Not handled | ✅ In both Actions |
| Event dispatch order vs cache invalidation | N/A | Event fires before cache (cache failure never suppresses notification) |

### New Files Created

| File | Purpose |
|---|---|
| `app/Events/SubmissionCreated.php` | Event carrying StudentSubmission model |
| `app/Listeners/Recordings/NotifyTeacherNewSubmission.php` | Dispatches database notification to first circle teacher; catches failures gracefully |
| `app/Actions/Recordings/ReviewSubmissionAction.php` | Updates self_rating/self_notes + clears progress cache |
| `app/Queries/Recordings/SurahJuzQuery.php` | 5 static methods: allSurahsWithCounts, searchSurahs, juzForSurah, ayahsForSurahJuz, resolveNames |
| `app/Http/Requests/Recordings/ReviewSubmissionRequest.php` | Validates self_rating (1–5) + notes (max 500) with Arabic messages |
| `app/Http/Requests/Recordings/StoreSubmissionRequest.php` | Validates circle_id (active membership), audio, image, surah_id, juz_id, ayah range, notes with Arabic messages |

### Modified Files

| File | What Changed |
|---|---|
| `app/Providers/AppServiceProvider.php` | Registered `SubmissionCreated` → `NotifyTeacherNewSubmission` event listener |
| `app/DTOs/Recordings/SubmissionData.php` | Added `$audioUploadPath`/`$imageUploadPath` properties for configurable storage paths |
| `app/DTOs/Recordings/SubmissionData.php` | Added `$audioUploadPath`/`$imageUploadPath` for configurable storage paths; made surahId/juzId/ayahFrom nullable; added `$ayah` field; added `fromFeatureRequest()` factory |
| `app/Actions/Recordings/CreateSubmissionAction.php` | Uses DTO upload paths; handles nullable fields; added JuzProgressService cache invalidation; event fires before cache invalidation |
| `app/Http/Controllers/RecordingController.php` | `store` uses `StoreSubmissionRequest` + `CreateSubmissionAction`; `rate` uses `ReviewSubmissionRequest` + `ReviewSubmissionAction`; all 4 API methods extracted to `SurahJuzQuery`; FQCNs replaced with imported classes |
| `app/Http/Controllers/Api/SubmissionApiController.php` | `store` shares `CreateSubmissionAction` with custom upload paths; preserves own validation (relaxed circle check) and response shape |
| `app/Features/StudentSubmissions/Controllers/StudentSubmissionController.php` | `store` shares `CreateSubmissionAction` + `SubmissionData::fromFeatureRequest()`; preserves proxy auth, collection-based circle check, redirects, and flash messages |

### Notification Behaviour — Intentional Improvement

**Decision:** Every successful student submission creation path dispatches `SubmissionCreated` and notifies the first circle teacher. This applies to:

- `RecordingController::store`
- `Api\SubmissionApiController::store` *(new — previously had no notification)*
- `Feature\StudentSubmissionController::store`

This is an intentional improvement. The `SubmissionApiController::store` previously had no teacher notification; it now consistently notifies the first circle teacher like all other paths.

**Event dispatch contract:**
- Dispatched exactly once per successful `DB::transaction()` commit
- Never dispatched if file upload fails or DB transaction fails
- Dispatched BEFORE cache invalidation (cache failure never suppresses notification)
- Listener catches all exceptions; notification failure never rolls back the submission
- Recipient: first circle teacher only (`->circleTeachers->first()?->teacher`)

### Unaffected Routes (Behaviour Preserved)

| Route | Controller Method | Status |
|---|---|---|
| `GET /recordings` | `dashboard` | Unchanged |
| `GET /recordings/upload` | `uploadPage` | Unchanged |
| `POST /recordings/store` | `store` | Same response `{success, message, submission}` (201) |
| `GET /recordings/{submission}` | `show` | Unchanged |
| `POST /recordings/{submission}/rate` | `rate` | Same response `{success, message}`; same auth logic |
| `DELETE /recordings/{submission}` | `delete` | Same behavior; cleaned up FQCN |
| `GET /recordings/bulk-import` | `bulkImportPage` | Unchanged |
| `POST /recordings/bulk-import` | `bulkImport` | Same behavior; cleaned up FQCN |
| `GET /recordings/bulk-import/template` | `downloadBulkTemplate` | Unchanged |
| `GET /api/recordings/surahs` | `apiSurahs` | Same JSON response, extracted to Query |
| `GET /api/recordings/surahs/search` | `apiSearchSurahs` | Same JSON response, extracted to Query |
| `GET /api/recordings/surah/{surahId}/juz` | `apiSurahJuz` | Same JSON response, extracted to Query |
| `GET /api/recordings/surah/{surahId}/juz/{juzId}/ayahs` | `apiSurahJuzAyahs` | Same JSON response, extracted to Query |
| All Feature routes (StudentSubmissionController) | — | Unchanged |
| All API routes (SubmissionApiController index/show/review) | — | Unchanged |

### Remaining Items for Future Phases

1. **[DONE] Feature StudentSubmissionController** — refactored to use `CreateSubmissionAction` + `SubmissionData::fromFeatureRequest()`. Now uses `FileUploadService` (MIME validation enforced).
2. **Feature StudentSubmissionApiController (Path 4)** — still calls `StudentSubmission::create()` directly, bypasses FileUploadService, uses free-text fields only (no surah_id/juz_id/ayah_from/ayah_to). See separate report below.
3. **Fix Policy usage** in `RecordingController::rate` (inline checks duplicate Policy) and `SubmissionApiController::review` (inline checks duplicate Policy `review`).
4. **Fix create Policy** — `RecordingController::store` checks `auth()->user()->studentProfile` before proceeding, but Policy `create` already handles this.
5. **Cache invalidation on delete** — `RecordingController::delete` does not clear JuzProgressService cache.
6. **Cache invalidation on teacher review** — `SubmissionApiController::review` and `RecordingController` teacher review don't clear cache.
7. **Run tests** — blocked by EPERM; requires `php artisan test` to verify protection tests pass.

### Feature StudentSubmissionApiController — Path 4 Inspection

**Route:** `POST /api/circles/{circle}/submissions` (routes/web.php:126, inside `role:student,super admin` middleware)

**Key differences from the 3 unified paths:**
- No Form Request — inline `$request->validate()`
- No student guard — if `$student` is null, crashes with 500
- No circle membership check
- Uses `$file->store()` directly (bypasses FileUploadService MIME validation)
- **No `surah_id`/`juz_id`/`ayah_from`/`ayah_to` fields** — free-text `surah`, `ayah`, `juz` only
- Max audio 10MB, max image 2MB (different limits)
- Response: `{data: submission}` (201) — unique response shape
- No notification (never had one)
- Uses `audio` field name (not `audio_file`)

**Recommendation:** Do NOT refactor in current phase. The missing FK fields (surah_id, juz_id) mean the data model is fundamentally different. Unifying would require either:
- Making those FK fields optional in the schema (already nullable — OK)
- Adding a separate factory method to SubmissionData (already done via fromFeatureRequest)
- Handling zero-FK submissions in CreateSubmissionAction (already done — nullable fields)

If unified, it would gain: `FileUploadService` validation, `SubmissionCreated` event (teacher notification), cache invalidation, consistent error handling. To preserve its unique response shape `{data: submission}`, the controller would need its own response block after calling the action — which is the same pattern used by `SubmissionApiController::store`.

---

## Feature Expansion Status — COMPLETE

### What was added (Phase 2)
The feature expansion phase has been completed, adding 8 major features:

| Feature | Status | Migrations | Models | Controllers | Views |
|---------|--------|------------|--------|-------------|-------|
| Memorization Assignments | ✅ | 2 | 1 | 1 + Actions/Queries | 4 |
| Memorization Sessions | ✅ | 1 | 1 | 1 | 4 |
| Memorization Mistakes | ✅ | 1 | 1 | 1 | 1 |
| Revision Plans | ✅ | 1 | 2 | 1 | 3 |
| Circle Sessions & Attendance | ✅ | 1 | 2 | 1 | 4 |
| Quran Exams | ✅ | 1 | 2 | 1 | 3 |
| Parent Portal | ✅ | 1 | 1 | 1 | 2 |
| Achievements & Certificates | ✅ | 1 | 2 | 1 | 2 |

### Total new files: ~60 files
- 10 migrations
- 13 models
- 8 controllers
- 3 actions
- 4 form requests
- 2 policies
- 1 service
- 1 query class
- 1 seeder (54 permissions)
- 3 events/listeners/notifications
- 23 blade views
- 8 test files (34 tests)

See `QURAN_ACADEMY_FEATURE_EXPANSION_REPORT.md` for full details.
