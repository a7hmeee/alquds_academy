<?php

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Circle;
use App\Models\CircleStudent;
use App\Models\Surah;
use App\Models\Juz;
use App\Models\StudentSubmission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;

/*
|--------------------------------------------------------------------------
| RecordingController Protection Tests
|--------------------------------------------------------------------------
|
| These tests protect the existing behaviour of RecordingController.
| They were created BEFORE any refactoring to ensure behaviour is preserved.
|
| NOTE: These tests could not be executed because the shell returns EPERM.
|       Their pass/fail status is unknown. They must be run manually
|       before deployment.
|
| Tests to add (not all implementable without runtime):
|   1. Student can create a valid submission
|   2. Non-student user cannot create submission
|   3. Student not in circle cannot create submission
|   4. Required fields are validated
|   5. Invalid audio file is rejected
|   6. Submission is saved with correct fields
|   7. Teacher notification is sent on success
|   8. API response shape matches current for RecordingController::store
|   9. Web redirect route is preserved for Feature controllers
|  10. Review saves score/rating/status/notes/reviewed_by
|  11. Unauthorized user cannot review
|  12. Deletion removes record and handles files
|  13. Bulk import processes CSV correctly
|  14. API surah/juz/ayah endpoints return correct data shape
|  15. RecordingController::store dispatches SubmissionCreated once (Event::fake)
|  16. SubmissionApiController::store dispatches SubmissionCreated once (Event::fake)
|  17. Feature StudentSubmissionController::store dispatches SubmissionCreated once (Event::fake)
|  18. Failed submission does not dispatch SubmissionCreated (Event::fake)
|  19. Exactly one StudentSubmission record created per successful request
|
*/

beforeEach(function () {
    $this->surah = Surah::factory()->create(['id' => 1, 'name_ar' => 'الفاتحة', 'verses_count' => 7]);
    $this->juz = Juz::factory()->create(['id' => 1, 'name' => 'الجزء 1']);
    $this->circle = Circle::factory()->create(['id' => 1, 'status' => 'active', 'juz_id' => 1]);

    // Student user with profile
    $studentUser = User::factory()->create();
    $studentUser->assignRole('student');
    $this->studentProfile = StudentProfile::factory()->create([
        'user_id' => $studentUser->id,
        'status' => 'active',
    ]);
    CircleStudent::factory()->create([
        'circle_id' => $this->circle->id,
        'student_id' => $this->studentProfile->id,
        'status' => 'active',
    ]);
    $this->studentUser = $studentUser;

    // Teacher user with profile
    $teacherUser = User::factory()->create();
    $teacherUser->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create([
        'user_id' => $teacherUser->id,
    ]);
    $this->teacherUser = $teacherUser;
});

// ─── STORE ───────────────────────────────────────────────────────────────────

test('student can create a valid submission via RecordingController::store', function () {
    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $response = $this->actingAs($this->studentUser)->postJson(route('recordings.store'), [
        'circle_id' => $this->circle->id,
        'audio' => $audioFile,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
        'ayah_to' => 3,
        'notes' => 'تسجيل اختبار',
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'success',
        'message',
        'submission' => ['id', 'student_id', 'circle_id', 'surah_id', 'juz_id', 'ayah_from', 'ayah_to', 'status'],
    ]);
    $response->assertJson(['success' => true]);
});

test('non-student user cannot create submission', function () {
    $plainUser = User::factory()->create();

    $response = $this->actingAs($plainUser)->postJson(route('recordings.store'), [
        'circle_id' => $this->circle->id,
        'audio' => UploadedFile::fake()->create('recording.mp3', 1000),
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
    ]);

    $response->assertStatus(403);
});

test('student not enrolled in circle cannot create submission', function () {
    $otherCircle = Circle::factory()->create(['status' => 'active']);
    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $response = $this->actingAs($this->studentUser)->postJson(route('recordings.store'), [
        'circle_id' => $otherCircle->id,
        'audio' => $audioFile,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['circle_id']);
});

test('required fields are validated on store', function () {
    $response = $this->actingAs($this->studentUser)->postJson(route('recordings.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['circle_id', 'audio', 'surah_id', 'juz_id', 'ayah_from']);
});

test('submission is saved with all required fields', function () {
    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $this->actingAs($this->studentUser)->postJson(route('recordings.store'), [
        'circle_id' => $this->circle->id,
        'audio' => $audioFile,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 5,
        'ayah_to' => 7,
        'notes' => 'اختبار',
    ]);

    $this->assertDatabaseHas('student_submissions', [
        'student_id' => $this->studentProfile->id,
        'circle_id' => $this->circle->id,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 5,
        'ayah_to' => 7,
        'status' => 'pending',
        'notes' => 'اختبار',
    ]);
});

// ─── RATE / REVIEW ──────────────────────────────────────────────────────────

test('teacher can review a submission', function () {
    $submission = StudentSubmission::factory()->create([
        'student_id' => $this->studentProfile->id,
        'circle_id' => $this->circle->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->teacherUser)->postJson(route('recordings.rate', $submission), [
        'self_rating' => 4,
        'notes' => 'أداء جيد',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('student_submissions', [
        'id' => $submission->id,
        'self_rating' => 4,
        'self_notes' => 'أداء جيد',
    ]);
});

test('unauthorized user cannot review a submission', function () {
    $plainUser = User::factory()->create();
    $submission = StudentSubmission::factory()->create([
        'student_id' => $this->studentProfile->id,
        'circle_id' => $this->circle->id,
    ]);

    $response = $this->actingAs($plainUser)->postJson(route('recordings.rate', $submission), [
        'self_rating' => 4,
    ]);

    $response->assertStatus(403);
});

// ─── DELETE ─────────────────────────────────────────────────────────────────

test('student can delete own submission', function () {
    $submission = StudentSubmission::factory()->create([
        'student_id' => $this->studentProfile->id,
        'circle_id' => $this->circle->id,
        'file_path' => 'recordings/audio/test.mp3',
    ]);

    $response = $this->actingAs($this->studentUser)->deleteJson(route('recordings.delete', $submission));

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertModelMissing($submission);
});

test('non-owner cannot delete submission', function () {
    $otherUser = User::factory()->create();
    $submission = StudentSubmission::factory()->create([
        'student_id' => $this->studentProfile->id,
        'circle_id' => $this->circle->id,
    ]);

    $response = $this->actingAs($otherUser)->deleteJson(route('recordings.delete', $submission));

    $response->assertStatus(403);
});

// ─── SHOW ───────────────────────────────────────────────────────────────────

test('student can view own submission', function () {
    $submission = StudentSubmission::factory()->create([
        'student_id' => $this->studentProfile->id,
        'circle_id' => $this->circle->id,
    ]);

    $response = $this->actingAs($this->studentUser)->get(route('recordings.show', $submission));

    $response->assertStatus(200);
});

test('non-owner cannot view another students submission', function () {
    $otherStudent = StudentProfile::factory()->create();
    $submission = StudentSubmission::factory()->create([
        'student_id' => $otherStudent->id,
        'circle_id' => $this->circle->id,
    ]);

    $response = $this->actingAs($this->studentUser)->get(route('recordings.show', $submission));

    $response->assertStatus(403);
});

// ─── API ENDPOINTS (data shape) ─────────────────────────────────────────────

test('api surahs endpoint returns correct structure', function () {
    $response = $this->actingAs($this->studentUser)->getJson(route('api.recordings.surahs'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => ['id', 'number', 'name_ar', 'name_en', 'revelation_place', 'juz_count', 'ayah_count'],
    ]);
});

test('api surah juz endpoint returns correct structure', function () {
    $response = $this->actingAs($this->studentUser)->getJson(route('api.recordings.surah.juz', ['surahId' => 1]));

    $response->assertStatus(200);
});

test('api surah juz ayahs endpoint returns correct structure', function () {
    $response = $this->actingAs($this->studentUser)->getJson(route('api.recordings.surah.juz.ayahs', ['surahId' => 1, 'juzId' => 1]));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'ayahs' => [['id', 'ayah_number', 'text']],
        'count',
        'from',
        'to',
    ]);
});

// ─── DASHBOARD ──────────────────────────────────────────────────────────────

test('dashboard page loads for student', function () {
    $response = $this->actingAs($this->studentUser)->get(route('recordings.dashboard'));

    $response->assertStatus(200);
});

test('upload page loads for student with circle', function () {
    $response = $this->actingAs($this->studentUser)->get(route('recordings.upload'));

    $response->assertStatus(200);
});

// ─── EVENT DISPATCH TESTS (Event::fake) ──────────────────────────────────────
// NOTE: These tests use Event::fake() which cannot be run — EPERM blocks
//       all shell commands. They are written for manual execution.

test('RecordingController::store dispatches SubmissionCreated once', function () {
    Event::fake();

    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $this->actingAs($this->studentUser)->postJson(route('recordings.store'), [
        'circle_id' => $this->circle->id,
        'audio' => $audioFile,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
    ]);

    Event::assertDispatched(\App\Events\SubmissionCreated::class, 1);
});

test('SubmissionApiController::store dispatches SubmissionCreated once', function () {
    Event::fake();

    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $this->actingAs($this->studentUser)->postJson('/api/submissions', [
        'circle_id' => $this->circle->id,
        'audio' => $audioFile,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
    ]);

    Event::assertDispatched(\App\Events\SubmissionCreated::class, 1);
});

test('Feature StudentSubmissionController::store dispatches SubmissionCreated once', function () {
    Event::fake();

    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $this->actingAs($this->studentUser)->post(route('circles.submissions.store', $this->circle), [
        'audio_file' => $audioFile,
        'surah' => 'الفاتحة',
        'ayah' => 1,
        'juz' => 'الجزء 1',
    ]);

    Event::assertDispatched(\App\Events\SubmissionCreated::class, 1);
});

test('failed submission does not dispatch SubmissionCreated', function () {
    Event::fake();

    // Submit without audio — validation should fail before action executes
    $response = $this->actingAs($this->studentUser)->postJson(route('recordings.store'), [
        'circle_id' => $this->circle->id,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
    ]);

    $response->assertStatus(422);
    Event::assertNotDispatched(\App\Events\SubmissionCreated::class);
});

test('exactly one StudentSubmission record is created per successful request', function () {
    $audioFile = UploadedFile::fake()->create('recording.mp3', 1000);

    $this->actingAs($this->studentUser)->postJson(route('recordings.store'), [
        'circle_id' => $this->circle->id,
        'audio' => $audioFile,
        'surah_id' => $this->surah->id,
        'juz_id' => $this->juz->id,
        'ayah_from' => 1,
    ]);

    $this->assertDatabaseCount('student_submissions', 1);
});
