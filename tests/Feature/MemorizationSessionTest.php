<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\MemorizationSession;
use App\Models\MemorizationMistake;
use App\Models\Surah;
use App\Models\Juz;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemorizationSessionTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $student;
    private Circle $circle;
    private StudentProfile $studentProfile;
    private TeacherProfile $teacherProfile;
    private Surah $surah;
    private Juz $juz;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\NewPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('super admin');
        $this->actingAs($admin);

        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');
        $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

        $this->student = User::factory()->create();
        $this->student->assignRole('student');
        $this->studentProfile = StudentProfile::factory()->create(['user_id' => $this->student->id]);

        $this->circle = Circle::factory()->create(['status' => 'active']);
        $this->circle->teachers()->attach($this->teacherProfile->id, ['status' => 'active']);
        $this->circle->students()->attach($this->studentProfile->id, ['status' => 'active']);

        $this->surah = Surah::factory()->create();
        $this->juz = Juz::factory()->create();
    }

    public function test_admin_can_create_session(): void
    {
        $response = $this->post(route('memorization-sessions.store'), [
            'student_id' => $this->studentProfile->id,
            'circle_id' => $this->circle->id,
            'surah_id' => $this->surah->id,
            'juz_id' => $this->juz->id,
            'session_date' => now()->format('Y-m-d'),
            'memorization_score' => 85,
            'tajweed_score' => 80,
            'fluency_score' => 90,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('memorization_sessions', [
            'student_id' => $this->studentProfile->id,
            'memorization_score' => 85,
        ]);
    }

    public function test_session_with_scores_calculates_total(): void
    {
        $session = MemorizationSession::factory()->create([
            'memorization_score' => 80,
            'tajweed_score' => 70,
            'fluency_score' => 90,
        ]);

        $expectedTotal = (int) round(($session->memorization_score + $session->tajweed_score + $session->fluency_score) / 3);
        $this->assertEquals($expectedTotal, $session->total_score);
    }

    public function test_can_add_mistake_to_session(): void
    {
        $session = MemorizationSession::factory()->create([
            'student_id' => $this->studentProfile->id,
            'surah_id' => $this->surah->id,
        ]);

        $response = $this->post(route('memorization-sessions.mistakes.store', $session), [
            'ayah_number' => 5,
            'mistake_type' => 'tajweed',
            'severity' => 'moderate',
            'word_text' => 'wrong_word',
            'correct_text' => 'correct_word',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('memorization_mistakes', [
            'memorization_session_id' => $session->id,
            'mistake_type' => 'tajweed',
            'severity' => 'moderate',
        ]);
    }

    public function test_can_resolve_mistake(): void
    {
        $mistake = MemorizationMistake::factory()->create([
            'is_resolved' => false,
        ]);

        $response = $this->patch(route('memorization-mistakes.resolve', $mistake));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('memorization_mistakes', [
            'id' => $mistake->id,
            'is_resolved' => true,
        ]);
    }

    public function test_session_updates_assignment_when_completed(): void
    {
        $assignment = \App\Models\MemorizationAssignment::factory()->create([
            'student_id' => $this->studentProfile->id,
            'teacher_id' => $this->teacherProfile->id,
            'circle_id' => $this->circle->id,
            'status' => 'in_progress',
        ]);

        $session = MemorizationSession::factory()->create([
            'student_id' => $this->studentProfile->id,
            'memorization_assignment_id' => $assignment->id,
            'status' => 'completed',
            'total_score' => 85,
        ]);

        $this->assertEquals('reviewed', $assignment->fresh()->status);
    }
}
